<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2025 Ernani José Camargo Azevedo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * Asterisk manager interface (AMI) connection class.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage AMI Interface
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Asterisk connection class
 */
class asterisk
{
  private $socket = null;
  private $hostname;
  private $port;
  private $username;
  private $password;
  private $events;
  private $timeout;
  private $connect_timeout;
  private $event_buffer = array ();
  private $response_buffer = array ();
  private $actionid = 0;
  private $header = "";
  private $buffer = "";

  /**
   * Class constructor. You can pass an array with options to change default
   * values.
   *
   * @param $options[optional] array Array with desired options.
   * @return [mixed] Null if auto_connect off or result of $this->connect () if on.
   */
  function asterisk ( $options)
  {
    $this->hostname = array_key_exists ( "hostname", $options) ? $options["hostname"] : "127.0.0.1";
    $this->port = array_key_exists ( "port", $options) ? (int) $options["port"] : 5038;
    $this->username = array_key_exists ( "username", $options) ? $options["username"] : "";
    $this->password = array_key_exists ( "password", $options) ? $options["password"] : "";
    $this->auth_type = array_key_exists ( "auth_type", $options) ? $options["auth_type"] : "MD5";
    $this->events = array_key_exists ( "events", $options) ? (boolean) $options["events"] : false;
    $this->connect_timeout = array_key_exists ( "connect_timeout", $options) ? (int) $options["connect_timeout"] : 60;
    $this->timeout = array_key_exists ( "timeout", $options) ? (int) $options["timeout"] : 5;

    if ( array_key_exists ( "auto_connect", $options) && $options["auto_connect"])
    {
      return $this->connect ();
    } else {
      return;
    }
  }

  /**
   * Connect to AMI server, authenticate and return the result.
   *
   * @result boolean Result of connection attempt.
   */
  function connect ()
  {
    if ( $this->socket)
    {
      $this->close ();
    }
    if ( ! $this->socket = stream_socket_client ( "tcp://" . $this->hostname . ":" . $this->port, $errno, $errstr, $this->connect_timeout))
    {
      return false;
    }
    stream_set_read_buffer ( $this->socket, 65535);
    stream_set_write_buffer ( $this->socket, 65535);
    $this->header = str_replace ( "\n", "", str_replace ( "\r", "", fgets ( $this->socket)));
    stream_set_blocking ( $this->socket, false);
    stream_set_timeout ( $this->socket, $this->timeout);
    if ( $this->auth_type == "MD5")
    {
      $challenge = $this->request (
                     "Challenge",
                     array (
                       "AuthType" => "MD5"
                     )
                   );
      if ( $challenge["Response"] != "Success")
      {
        return false;
      }
      $response = $this->request (
                    "Login",
                    array (
                      "AuthType" => "MD5",
                      "Username" => $this->username,
                      "Key" => md5 ( $challenge["Challenge"] . $this->password),
                      "Events" => ( $this->events ? "ON" : "OFF")
                    )
                  );
    } else {
      $response = $this->request (
                    "Login",
                    array (
                      "Username" => $this->username,
                      "Secret" => $this->password,
                      "Events" => ( $this->events ? "ON" : "OFF")
                    )
                  );
    }
    return $response["Response"] == "Success";
  }

  /**
   * Disconnect from AMI server.
   *
   * @return null
   */
  function disconnect ()
  {
    $this->post ( "Logoff");
    stream_socket_shutdown ( $this->socket,  STREAM_SHUT_RDWR);
    $this->socket = null;
    $this->header = "";
  }

  /**
   * Send a new event to AMI server and wait for response.
   *
   * @param $action string Action to be sent.
   * @param $params[optional] array Array with attributes to be appended to the
   *                                event.
   * @param $timeout[optional] integer How many time to wait for a response.
   * @return [mixed] Event result (false if error or array with content if
   *                 successfull).
   */
  function request ( $action, $params = null, $timeout = -1)
  {
    if ( $id = $this->post ( $action, $params))
    {
      do
      {
        $this->wait_event ( $timeout);
        if ( ! $this->checkbuffer ())
        {
          return false;
        }
        $this->read_anything ();
      } while ( ! $this->response_buffer[$id]);
      return $this->response_buffer[$id];
    } else {
      return false;
    }
  }

  /**
   * Internal class method to send a new event to AMI server. This method didn't
   * wait for a response.
   *
   * @param $action string Action to be sent.
   * @param $params[optional] array Array with attributes to be appended to the
   *                                event.
   * @return [mixed] ID of event if successfull or false on error.
   */
  private function post ( $action, $params = null)
  {
    if ( ! $this->socket)
    {
      return false;
    }
    $request_params = "";
    if ( is_array ( $params))
    {
      foreach ( $params as $key => $value)
      {
        $request_params .= "$key: " . $value . "\r\n";
      }
    } else {
      $request_params = $params;
    }
    $id = ++$this->actionid;
    $request = "Action: " . $action . "\r\n" . $request_params . "ActionID: " . $id . "\r\n\r\n";
    if ( fwrite ( $this->socket, $request))
    {
      return $id;
    }
    return false;
  }

  /**
   * Private class method to read any content from connection socket and process
   * it adding new received events into events buffer.
   *
   * @return null
   */
  private function read_anything ()
  {
    $this->buffer .= stream_get_contents ( $this->socket);
    if ( strlen ( $this->buffer) == 0)
    {
      return;
    }

    // Process incoming buffer:
    $this->buffer = str_replace ( "\r", "", $this->buffer);
    $response = array ();
    while ( strpos ( $this->buffer, "\n\n") !== false)
    {
      $event = substr ( $this->buffer, 0, strpos ( $this->buffer, "\n\n"));
      $this->buffer = substr ( $this->buffer, strpos ( $this->buffer, "\n\n") + 2);
      if ( substr ( $event, 0, 7) == "Event: ")
      {
        $output = array ();
        foreach ( explode ( "\n", $event) as $line)
        {
          if ( strpos ( $line, ": ") !== false)
          {
            $output[substr ( $line, 0, strpos ( $line, ": "))] = substr ( $line, strpos ( $line, ": ") + 2);
          }
        }
        $this->event_buffer[] = $output;
      }
      if ( substr ( $event, 0, 10) == "Response: ")
      {
        $output = array ();
        if ( substr ( $event, 10, 7) == "Follows")
        {
          $output["Message"] = "";
          foreach ( explode ( "\n", $event) as $line)
          {
            if ( substr ( $line, 0, 10) == "Response: " && ! array_key_exists ( "Response", $output))
            {
              $output[substr ( $line, 0, strpos ( $line, ": "))] = substr ( $line, strpos ( $line, ": ") + 2);
              continue;
            }
            if ( substr ( $line, 0, 11) == "Privilege: " && ! array_key_exists ( "Privilege", $output))
            {
              $output[substr ( $line, 0, strpos ( $line, ": "))] = substr ( $line, strpos ( $line, ": ") + 2);
              continue;
            }
            if ( substr ( $line, 0, 10) == "ActionID: " && ! array_key_exists ( "ActionID", $output))
            {
              $output[substr ( $line, 0, strpos ( $line, ": "))] = substr ( $line, strpos ( $line, ": ") + 2);
              continue;
            }
            if ( substr ( $line, 0, 15) != "--END COMMAND--")
            {
              $output["Message"] .= $line . "\n";
            }
          }
        } else {
          foreach ( explode ( "\n", $event) as $line)
          {
            if ( strpos ( $line, ": ") !== false)
            {
              if ( array_key_exists ( substr ( $line, 0, strpos ( $line, ": ")), $output))
              {
                $output[substr ( $line, 0, strpos ( $line, ": "))] = $output[substr ( $line, 0, strpos ( $line, ": "))] . PHP_EOL . substr ( $line, strpos ( $line, ": ") + 2);
              } else {
                $output[substr ( $line, 0, strpos ( $line, ": "))] = substr ( $line, strpos ( $line, ": ") + 2);
              }
            }
          }
        }
        $this->response_buffer[(int) $output["ActionID"]] = $output;
      }
    }
  }

  /**
   * Method to check if events are enabled at connection.
   *
   * @return boolean Status of connection events.
   */
  function events_status ()
  {
    return $this->events;
  }

  /**
   * Method to set the status of connection events.
   *
   * @param $status boolean New status to be set.
   * @return boolean Result of event command.
   */
  function events_set ( $status)
  {
    $this->events = ( $status ? true : false);
    if ( $this->socket)
    {
      if ( ! $response = $this->request ( "EVENTS", array ( "EVENTMASK" => ( $status ? "ON" : "OFF"))))
      {
        return false;
      }
      return $response["Response"] == "Success";
    }
  }

  /**
   * Method to toggle the status of connection events.
   *
   * @return boolean Result of event command.
   */
  function events_toggle ()
  {
    return $this->events_set ( ! $this->events);
  }

  /**
   * Check how many events are pending into the events buffer.
   *
   * @return int Count of events.
   */
  function events_check ()
  {
    if ( count ( $this->event_buffer) > 0)
    {
      return count ( $this->event_buffer);
    }
    if ( $this->socket)
    {
      $this->read_anything ();
    }
    return count ( $this->event_buffer);
  }

  /**
   * Check if there's any content pending to be read at socket connection.
   *
   * @return boolean Result of status.
   */
  function checkbuffer ()
  {
    $pArr = array ( $this->socket);
    if ( false === ( $num_changed_streams = stream_select ( $pArr, $write = NULL, $except = NULL, 0)))
    {
      return false;
    } else {
      if ( $num_changed_streams > 0)
      {
        return true;
      } else {
        return false;
      }
    }
  }

  /**
   * Method to block code execution and wait for new event.
   *
   * @param $timeout[optional] integer How many time to wait for an event.
   * @return null
   */
  function wait_event ( $timeout = -1)
  {
    stream_set_blocking ( $this->socket, true);
    if ( $timeout != -1)
    {
      stream_set_timeout ( $this->socket, $timeout);
    }
    $this->buffer .= stream_get_contents ( $this->socket, 1);
    stream_set_blocking ( $this->socket, false);
    if ( $timeout != -1)
    {
      stream_set_timeout ( $this->socket, $this->timeout);
    }
  }

  /**
   * Shift an event off the events buffer.
   *
   * @return array Event data.
   */
  function events_shift ()
  {
    return array_shift ( $this->event_buffer);
  }

  /**
   * Pop an event off the events buffer.
   *
   * @return array Event data.
   */
  function events_pop ()
  {
    return array_pop ( $this->event_buffer);
  }

  /**
   * Get current connection AMI server version.
   *
   * @return string AMI server version.
   */
  function ami_version ()
  {
    return substr ( $this->header, strpos ( $this->header, "/") + 1);
  }
}
?>
