#!/usr/bin/php -q
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
 * This daemon connects to Asterisk through AMI interface, providing a command
 * line interface to send events and watch for events.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage AMI Interface
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR);
ini_set ( "display_errors", "false");
// error_reporting ( E_ALL); ini_set ( "display_errors", "true");

/**
 * Check if script is running from CLI
 */
if ( ! defined ( "STDIN"))
{
  echo "This script must be executed from the CLI!\n";
  exit ( 1);
}

/**
 * Include functions library
 */
require_once ( dirname ( __FILE__) . "/includes/asterisk.inc.php");

/**
 * Readline callback functions
 */
function rl_callback ( $string)
{
  global $prompt, $history;

  if ( $string)
  {
    readline_add_history ( $string);
    $history[] = $string;
    exec_command ( $string);
  }
  readline_callback_handler_install ( $prompt, "rl_callback");
}

function rl_completion ( $string, $index)
{
  global $commands, $amicommands;

  // Get info about the current buffer
  $rl_info = readline_info ();

  // Figure out what the entire input is
  $full_input = substr ( $rl_info["line_buffer"], 0, $rl_info["end"]);

  // Search commands and AMI commands
  $matches = array();
  foreach ( $commands as $command)
  {
    if ( substr ( $command, 0, strlen ( $full_input)) == $full_input)
    {
      $matches[] = substr ( $command, $index);
    }
  }
  foreach ( $amicommands as $command)
  {
    if ( substr ( $command, 0, strlen ( $full_input)) == $full_input)
    {
      $matches[] = substr ( $command, $index);
    }
  }

  return $matches;
}

function readline_refresh ()
{
  global $prompt;

  // Get info about the current buffer
  $rl_info = readline_info ();

  // Print prompt
  echo $prompt . ( ord ( substr ( $rl_info["line_buffer"], -1)) != 10 ? $rl_info["line_buffer"] . chr ( 27) . "[" . strlen ( $rl_info["line_buffer"]) . "C" : "");

  // Reward if not in the end
  if ( ord ( substr ( $rl_info["line_buffer"], -1)) != 10 && $rl_info["end"] != strlen ( $rl_info["line_buffer"]))
  {
    echo chr ( 27) . "[" . ( strlen ( $rl_info["line_buffer"]) - $rl_info["end"]) . "D";
  }
}

function exec_command ( $line)
{
  global $ami, $conf, $history;

  if ( substr ( $line, 0, 1) == "/")
  {
    switch ( strtolower ( strtok ( substr ( $line, 1), " ")))
    {
      case "events":
        if ( strpos ( $line, " ") !== false)
        {
          $line = substr ( $line, strpos ( $line, " ") + 1);
          switch ( strtolower ( ( strpos ( $line, " ") !== false ? substr ( $line, 0, strpos ( $line, " ")) : $line)))
          {
            case "on":
            case "off":
              $conf["events"] = ( strpos ( " ", $line) !== false ? substr ( $line, 0, strpos ( $line, " ")) : $line) == "on";
              $ami->events_set ( $conf["events"]);
              echo "Events turned: " . chr ( 27) . "[1;37m" . ( $conf["events"] ? "On" : "Off") . chr ( 27) . "[1;0m\n\n";
              break;
            case "filter":
              $filters = substr ( $line, strpos ( $line, " ") + 1);
              if ( $filters == "none")
              {
                $conf["filters"] = array ();
              } else {
                $conf["filters"] = explode ( " ", $filters);
              }
              echo "Events filters set: " . chr ( 27) . "[1;37m" . ( sizeof ( $conf["filters"]) == 0 ? "none" : implode ( $conf["filters"], " ")) . chr ( 27) . "[1;0m\n\n";
              break;
            default:
              echo chr ( 27) . "[1;37mError" . chr ( 27) . "[1;0m: Unknown option to events command!\n";
              $line = "";
              return;
              break;
          }
        } else {
          echo "Events: " . chr ( 27) . "[1;37m" . ( $conf["events"] === true ? "On" : "Off") . chr ( 27) . "[1;0m\n";
          echo "Events filters: " . chr ( 27) . "[1;37m" . ( sizeof ( $conf["filters"]) == 0 ? "none" : implode ( $conf["filters"], " ")) . chr ( 27) . "[1;0m\n\n";
        }
        $line = "";
        break;
      case "version":
        $line = "";
        $version = $ami->request ( "CoreSettings", array ());
        if ( $version["Response"] != "Success")
        {
          echo chr ( 27) . "[1;37mError" . chr ( 27) . "[1;0m: Cannot get Asterisk server settings!\n";
          return;
        }
        echo "Asterisk version: " . chr ( 27) . "[1;37m" . $version["AsteriskVersion"] . chr ( 27) . "[1;0m\nAMI protocol version: " . chr ( 27) . "[1;37m" . $version["AMIversion"] . chr ( 27) . "[1;0m\nMax calls: " . chr ( 27) . "[1;37m" . $version["CoreMaxCalls"] . chr ( 27) . "[1;0m\nMax load average: " . chr ( 27) . "[1;37m" . $version["CoreMaxLoadAvg"] . chr ( 27) . "[1;0m\n\n";
        break;
      case "status":
        $line = "";
        $status = $ami->request ( "CoreStatus", array ());
        if ( $status["Response"] != "Success")
        {
          echo chr ( 27) . "[1;37mError" . chr ( 27) . "[1;0m: Cannot get Asterisk server status!\n";
          return;
        }
        echo "AMI protocol version: " . chr ( 27) . "[1;37m" . $ami->ami_version () . chr ( 27) . "[1;0m\nStarted at: " . chr ( 27) . "[1;37m" . date ( "r", mktime ( substr ( $status["CoreStartupTime"], 0, 2), substr ( $status["CoreStartupTime"], 3, 2), substr ( $status["CoreStartupTime"], 6, 2), substr ( $status["CoreStartupDate"], 5, 2), substr ( $status["CoreStartupDate"], 8, 2), substr ( $status["CoreStartupDate"], 0, 4))) . chr ( 27) . "[1;0m\nLast reload at: " . chr ( 27) . "[1;37m" . date ( "r", mktime ( substr ( $status["CoreReloadTime"], 0, 2), substr ( $status["CoreReloadTime"], 3, 2), substr ( $status["CoreReloadTime"], 6, 2), substr ( $status["CoreReloadDate"], 5, 2), substr ( $status["CoreReloadDate"], 8, 2), substr ( $status["CoreReloadDate"], 0, 4))) . chr ( 27) . "[1;0m\nEvents: " . chr ( 27) . "[1;37m" . ( $conf["events"] === true ? "On" : "Off") . chr ( 27) . "[1;0m\n\n";
        break;
      case "history":
        if ( strpos ( $line, " ") !== false)
        {
          $line = substr ( $line, strpos ( $line, " ") + 1);
          switch ( strtolower ( ( strpos ( $line, " ") !== false ? substr ( $line, 0, strpos ( $line, " ")) : $line)))
          {
            case "clean":
              $history = array ();
              readline_clear_history ();
              echo "History cleaned.\n\n";
              break;
            default:
              echo chr ( 27) . "[1;37mError" . chr ( 27) . "[1;0m: Unknown option to history command!\n";
              $line = "";
              break;
          }
          return;
        }
        $size = strlen ( (string) count ( $history));
        $count = 0;
        foreach ( $history as $entry)
        {
          $count++;
          printf ( "%" . $size . "s %s\n", $count, $entry);
        }
        $line = "";
        break;
      case "help":
        $line = "";
        echo "At console, you can send actions with optional parameters with syntax:\n";
        echo "  action param1=value1 param2=value2...\n";
        echo "\n";
        echo "Also, you can use the following commands at console:\n";
        echo "  /events [on|off]: Enable/disable events notification. If no parameter given,\n";
        echo "                    show events status.\n";
        echo "  /events filter X: Show only events listed on this line (separed by spaces).\n";
        echo "  /history:         Show commands history.\n";
        echo "  /history clean:   Clean commands history.\n";
        echo "  /version:         Show connected server version information.\n";
        echo "  /status:          Show connected server status.\n";
        echo "  /commands:        Show available commands (send \"ListCommands\" action).\n";
        echo "  /help:            Show this help message.\n";
        echo "  /quit:            End current session and exit.\n\n";
        break;
      case "commands":
        $line = "ListCommands";
        break;
      case "quit":
        $quit = $ami->request ( "LogOff", array ());
        echo "\n";
        echo "Disconnected: " . chr ( 27) . "[1;37m" . $quit["Message"] . chr ( 27) . "[1;0m\n";
        readline_callback_handler_remove ();
        exit ();
        break;
      default:
        $line = "";
        echo chr ( 27) . "[1;37mError" . chr ( 27) . "[1;0m: Invalid command! Use \"/help\" to see available commands.\n\n";
        break;
    }
    if ( empty ( $line))
    {
      return;
    }
  }
  if ( $line != "")
  {
    if ( strpos ( $line, " ") !== false)
    {
      $command = substr ( $line, 0, strpos ( $line, " "));
      $line = substr ( $line, strpos ( $line, " ") + 1);
      $params = array ();
      $invar = true;
      $block = false;
      $var = "";
      $val = "";
      $params = array ();
      for ( $x = 0; $x <= strlen ( $line); $x++)
      {
        $char = substr ( $line, $x, 1);
        if ( $invar)
        {
          if ( $char == "=")
          {
            $invar = false;
          } else {
            $var .= $char;
          }
          continue;
        }
        if ( ( $char == "\"" || $char == "'") && empty ( $val))
        {
          $block = $char;
          continue;
        }
        if ( $char == "=" && ! $block)
        {
          if ( strpos ( $val, " ") !== true)
          {
            echo "Invalid parameters.\n";
            $command = "";
            break;
          }
          $newvar = substr ( $val, strrpos ( $val, " ") + 1);
          $val = substr ( $val, 0, strrpos ( $val, " "));
          $params[trim ( $var)] = trim ( $val);
          $var = $newvar;
          $val = "";
          continue;
        }
        if ( $char == $block && $block)
        {
          $params[trim ( $var)] = trim ( $val);
          $var = "";
          $val = "";
          $invar = true;
          $block = false;
          continue;
        }
        $val .= $char;
      }
      if ( ! empty ( $var))
      {
        if ( $block || $invar)
        {
          echo "Invalid parameters.\n";
          $command = "";
        }
        $params[trim ( $var)] = trim ( $val);
      }
    } else {
      $command = $line;
      $params = array ();
    }
    if ( ! empty ( $command))
    {
      echo "Result for action \"" . chr ( 27) . "[1;37m" . $command . chr ( 27) . "[1;0m\" (";
      $result = $ami->request ( $command, $params);
      unset ( $result["ActionID"]);
      if ( $result["Response"] == "Error")
      {
        echo chr ( 27) . "[1;31mError" . chr ( 27) . "[1;0m";
      } else {
        echo $result["Response"];
      }
      unset ( $result["Response"]);
      echo "):\n" . str_replace ( "array (\n", "", str_replace ( ",\n)", "\n", var_export ( $result, true))) . "\n";
      if ( strtolower ( $command) == "logoff")
      {
        exit ();
      }
    }
    $command = "";
    $params = array ();
    $line = "";
  }
}

/**
 * Show software version header
 */
echo chr ( 27) . "[1;37mVoIP Domain AMI Console" . chr ( 27) . "[1;0m v1.0\n";
echo "\n";

/**
 * Read configuration file
 */
if ( is_readable ( $_SERVER["HOME"] . "/.amicli"))
{
  $conf = parse_ini_file ( $_SERVER["HOME"] . "/.amicli", true);
} else {
  $conf = array ();
}
if ( ! array_key_exists ( "hostname", $conf) || empty ( $conf["hostname"]))
{
  $conf["hostname"] = "localhost";
}
if ( ! array_key_exists ( "port", $conf) || empty ( $conf["port"]))
{
  $conf["port"] = 5038;
}
if ( ! array_key_exists ( "events", $conf) || ( empty ( $conf["events"]) && $conf["events"] !== false))
{
  $conf["events"] = true;
}
if ( ! array_key_exists ( "filters", $conf) || empty ( $conf["filters"]))
{
  $conf["filters"] = array ();
} else {
  $conf["filters"] = explode ( " ", $conf["filters"]);
}

/**
 * Process parameters
 */
for ( $x = 1; $x < $argc; $x++)
{
  switch ( $_SERVER["argv"][$x])
  {
    case "--username":
    case "-u":
      $x++;
      $conf["username"] = $_SERVER["argv"][$x];
      break;
    case "--secret":
    case "-s":
      $x++;
      $conf["secret"] = $_SERVER["argv"][$x];
      break;
    case "--hostname":
    case "-h":
      $x++;
      $conf["hostname"] = $_SERVER["argv"][$x];
      break;
    case "--port":
    case "-p":
      $x++;
      $conf["port"] = (int) $_SERVER["argv"][$x];
      break;
    case "--noevents":
    case "-n":
      $conf["events"] = false;
      break;
    case "--help":
      echo "Usage: " . basename ( $_SERVER["argv"][0]) . " [--help] [--debug|-d]\n";
      echo "  --help:        Show this help information\n";
      echo "  --username|-u: AMI username\n";
      echo "  --secret|-s:   AMI secret (password)\n";
      echo "  --hostname|-h: AMI server hostname/IP address (default localhost)\n";
      echo "  --port|-p:     AMI server port (default 5038)\n";
      echo "  --noevents|-n: Disable events display\n";
      echo "\n";
      echo "At console, you can send actions with optional parameters with syntax:\n";
      echo "  action param1=value1 param2=value2...\n";
      echo "\n";
      echo "Also, you can use the following commands at console:\n";
      echo "  /events [on|off]: Enable/disable events notification. If no parameter given,\n";
      echo "                    show events status.\n";
      echo "  /events filter X: Show only events listed on this line (separed by spaces).\n";
      echo "  /history:         Show commands history.\n";
      echo "  /history clean:   Clean commands history.\n";
      echo "  /version:         Show connected server version information.\n";
      echo "  /status:          Show connected server status.\n";
      echo "  /help:            Show this help message.\n";
      echo "  /quit:            End current session and exit.\n";
      exit ();
      break;
    default:
      echo "ERROR: Invalid parameter \"" . $_SERVER["argv"][$x] . "\"!\n";
      exit ( -1);
      break;
  }
}

/**
 * Show start of operations message
 */
echo "Connecting to AMI server... ";
$ami = new asterisk ( array ( "username" => $conf["username"], "password" => $conf["secret"], "hostname" => $conf["hostname"], "port" => $conf["port"], "connect_timeout" => 1, "events" => $conf["events"]));
if ( ! $ami->connect ())
{
  echo chr ( 27) . "[1;37mERROR: Cannot connect to AMI server, check your configurations!" . chr ( 27) . "[1;0m\n";
  exit ( 1);
}
echo chr ( 27) . "[1;37mConnected!" . chr ( 27) . "[1;0m\n\n";
$ignoreauth = time ();

/**
 * Start loop
 */
set_time_limit ( 0);
stream_set_blocking ( STDIN, false);
$line = "";
$lastevent = time ();
$prompt = $conf["username"] . "@" . $conf["hostname"] . ":" . $conf["port"] . "> ";
$history = array ();
$commands = array (
  "/events",
  "/events on",
  "/events off",
  "/events filter",
  "/version",
  "/status",
  "/history",
  "/history clean",
  "/help",
  "/commands",
  "/quit"
);
$amicommands = array ();

while ( true)
{
  /**
   * Read stdin
   */
  $w = NULL;
  $e = NULL;
  $n = stream_select ( $r = array ( STDIN), $w, $e, 0, 0);
  if ( $n && in_array ( STDIN, $r))
  {
    // read a character, will call the callback when a newline is entered
    readline_callback_read_char ();
  }
  usleep ( 100);

  /**
   * Check if connection is alive
   */
  if ( time () - $lastevent > 600 && $ami)
  {
    $ping = $ami->request ( "Ping", array ());
    if ( $ping["Response"] != "Success" || $ping["Pong"])
    {
      echo chr ( 27) . "[1;37mERROR: Conection lost!" . chr ( 27) . "[1;0m\n";
      exit ( 2);
    }
  }

  /**
   * Loop while there's events
   */
  if ( $ami->events_check ())
  {
    $event = $ami->events_shift ();
    if ( $event["Event"] == "FullyBooted")
    {
      echo "AMI protocol version: " . chr ( 27) . "[1;37m" . $ami->ami_version () . chr ( 27) . "[1;0m\nStarted at: " . chr ( 27) . "[1;37m" . date ( "r", time () - $event["Uptime"]) . chr ( 27) . "[1;0m\nLast reload at: " . chr ( 27) . "[1;37m" . date ( "r", time () - $event["LastReload"]) . chr ( 27) . "[1;0m\nEvents: " . chr ( 27) . "[1;37m" . ( $conf["events"] === true ? "On" : "Off") . chr ( 27) . "[1;0m\n\n";
      echo "Getting available commands... ";
      $list = $ami->request ( "ListCommands", array ());
      foreach ( $list as $com => $tmp)
      {
        $amicommands[] = $com;
      }
      echo chr ( 27) . "[1;37mdone!" . chr ( 27) . "[1;0m\n\n";
      readline_completion_function ( "rl_completion");
      readline_callback_handler_install ( $prompt, "rl_callback");
    } else {
      if ( sizeof ( $conf["filters"]) == 0 || in_array ( $event["Event"], $conf["filters"]))
      {
        if ( $ignoreauth == 0 || ! $event["Event"] == "SuccessfulAuth" || ( $event["Event"] == "SuccessfulAuth" && $ignoreauth + 5 < time ()))
        {
          echo chr ( 27) . "[0K" . chr ( 27) . "[1K" . chr ( 27) . "[1F\n";
          echo "Received event \"" . chr ( 27) . "[1;37m" . $event["Event"] . chr ( 27) . "[1;0m\":\n" . str_replace ( "array (\n", "", str_replace ( ",\n)", "\n", var_export ( $event, true))) . "\n";
          echo chr ( 27) . "[1E";
          readline_refresh ();
        } else {
          $ignoreauth = 0;
        }
      }
    }
    $lastevent = time ();
  }
}
?>
