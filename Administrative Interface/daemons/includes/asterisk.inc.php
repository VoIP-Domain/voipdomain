<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Asterisk connection class
 */
class asterisk
{
  private $socket;
  private $hostname;
  private $port;
  private $username;
  private $password;
  private $events = false;
  private $event_buffer = array ();
  private $response_buffer = array ();
  private $actionid = 0;
  private $header = "";
  private $buffer = "";

  function asterisk ( $username = "username", $password = "password", $hostname = "127.0.0.1", $port = "5038", $autologin = true, $authtype = "MD5")
  {
    if ( $autologin)
    {
      return $this->open ( $username, $password, $hostname, $port, $authtype);
    }
  }

  function open ( $username = null, $password = null, $hostname = null, $port = null, $authtype = "MD5")
  {
    $this->username = $username ? $username : $this->username;
    $this->password = $password ? $password : $this->password;
    $this->hostname = $hostname ? $hostname : $this->hostname;
    $this->port = $port ? $port : $this->port;
    if ( $this->socket)
    {
      $this->close ();
    }
    if ( ! $this->socket = stream_socket_client ( "tcp://" . $this->hostname . ":" . $this->port))
    {
      return false;
    }
    stream_set_read_buffer ( $this->socket, 65535);
    stream_set_write_buffer ( $this->socket, 65535);
    $this->header = str_replace ( "\n", "", str_replace ( "\r", "", fgets ( $this->socket)));
    stream_set_blocking ( $this->socket, false);
    if ( $authtype == "MD5")
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

  function close ()
  {
    $this->post ( "Logoff");
    stream_socket_shutdown ( $this->socket,  STREAM_SHUT_RDWR);
    $this->socket = null;
    $this->header = "";
  }

  function request ( $action, $params = null)
  {
    if ( $id = $this->post ( $action, $params))
    {
      do
      {
        $this->read_anything ();
      } while ( ! $this->response_buffer[$id]);
      return $this->response_buffer[$id];
    } else {
      return false;
    }
  }

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

  private function read_anything ()
  {
    $this->buffer .= stream_get_contents ( $this->socket);
    if ( strlen ( $this->buffer) == 0)
    {
      return "";
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
              $output[substr ( $line, 0, strpos ( $line, ": "))] = substr ( $line, strpos ( $line, ": ") + 2);
            }
          }
        }
        $this->response_buffer[(int) $output["ActionID"]] = $output;
      }
    }
  }

  function events_status ()
  {
    return $this->events;
  }

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

  function events_toggle ()
  {
    return $this->events_set ( ! $this->events);
  }

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

  function wait_event ()
  {
    stream_set_blocking ( $this->socket, true);
    $this->buffer .= stream_get_contents ( $this->socket, 1);
    stream_set_blocking ( $this->socket, false);
  }

  function events_shift ()
  {
    return array_shift ( $this->event_buffer);
  }

  function events_pop ()
  {
    return array_pop ( $this->event_buffer);
  }

  function ami_version ()
  {
    return substr ( $this->header, strpos ( $this->header, "/") + 1);
  }
}
?>
