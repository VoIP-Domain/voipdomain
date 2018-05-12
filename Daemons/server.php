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
 * Remote Asterisk server configuration and infra structure auto provisioning
 * daemon.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk Client Daemon
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
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
  echo "This script must be executed into CLI!\n";
  exit ( 1);
}

/**
 * Include functions library
 */
require_once ( dirname ( __FILE__) . "/includes/functions.inc.php");
require_once ( dirname ( __FILE__) . "/includes/plugins.inc.php");

/**
 * Parse configuration file. You should put your configuration file OUTSIDE
 * the web server files path, or you must block access to this file at the
 * web server configuration. Your configuration would contain passwords and
 * other sensitive configurations.
 */
$_in = parse_ini_file ( "/etc/voipdomain/client.conf", true);

/**
 * Include all modules configuration files
 */
foreach ( glob ( dirname ( __FILE__) . "/modules/*/config.php") as $filename)
{
  require_once ( $filename);
}

/**
 * Check for mandatory basic configurations (if didn't exist, set default)
 */
if ( ! array_key_exists ( "version", $_in["general"]))
{
  $_in["version"] = "1.0";
} else {
  $_in["version"] = $_in["general"]["version"];
  unset ( $_in["general"]["version"]);
}
if ( ! array_key_exists ( "charset", $_in["general"]))
{
  $_in["general"]["charset"] = "UTF-8";
}
if ( ! array_key_exists ( "language", $_in["general"]))
{
  $_in["general"]["language"] = "en_US";
}

/**
 * Configure locale and encoding
 */
mb_internal_encoding ( $_in["general"]["charset"]);
setlocale ( LC_ALL, $_in["general"]["language"] . "." . $_in["general"]["charset"]);

/**
 * Show software version header
 */
echo chr ( 27) . "[1;37mVoIP Domain Client Daemon" . chr ( 27) . "[1;0m v" . $_in["version"] . "\n";
echo "\n";

/**
 * Validate MySQL session
 */
if ( ! is_array ( $_in["mysql"]))
{
  echo "Error: Cannot find \"mysql\" session at configuration file.\n";
  exit ( 1);
}

/**
 * Process parameters
 */
$debug = false;
for ( $x = 1; $x < $argc; $x++)
{
  switch ( $argv[$x])
  {
    case "--debug":
    case "-d":
      $debug = true;
      break;
    case "--help":
    case "-h":
      echo "Usage: " . basename ( $argv[0]) . " [--help|-h] [--debug|-d]\n";
      echo "  --help|-h:    Show this help informations\n";
      echo "  --debug|-d:   Enable debug messages (do not fork the daemon)\n";
      exit ();
      break;
    default:
      echo "ERROR: Invalid parameter \"" . $argv[$x] . "\"!\n";
      exit ( -1);
      break;
  }
}

/**
 * Conect to the database
 */
echo "Executing: Connecting to database... ";
if ( ! $_in["mysql"]["id"] = @new mysqli ( $_in["mysql"]["hostname"] . ( ! empty ( $_in["mysql"]["port"]) ? ":" . $_in["mysql"]["port"] : ""), $_in["mysql"]["username"], $_in["mysql"]["password"], $_in["mysql"]["database"]))
{
  writeLog ( "Cannot connect to database server!", VoIP_LOG_FATAL);
}
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * Include all modules files
 */
echo "Executing: Loading modules... ";
foreach ( glob ( dirname ( __FILE__) . "/modules/*/actions.php") as $filename)
{
  require_once ( $filename);
}
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * If possible, change process name
 */
if ( function_exists ( "setproctitle"))
{
  echo "Executing: Changing process name... ";
  setproctitle ( "VoIP Domain client daemon");
  echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";
}

/**
 * Change current work directory to root
 */
echo "Executing: Changing working directory... ";
chdir ( '/');
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * Change effective UID/GID to an unprivileged user
 */
echo "Executing: Changing effective UID/GID... ";
if ( ! $uid = posix_getpwnam ( $_in["daemon"]["uid"]))
{
  writeLog ( "Cannot check for the user \"" . $_in["daemon"]["uid"] . "\"!", VoIP_LOG_FATAL);
}
if ( ! $gid = posix_getgrnam ( $_in["daemon"]["gid"]))
{
  writeLog ( "Cannot check for the group \"" . $_in["daemon"]["gid"] . "\"!", VoIP_LOG_FATAL);
}
if ( ! posix_setgid ( $gid["gid"]))
{
  writeLog ( "Cannot change to GID " . $gid["gid"] . " \"" . $_in["daemon"]["gid"] . "\"!", VoIP_LOG_FATAL);
}
if ( ! posix_setuid ( $uid["uid"]))
{
  writeLog ( "Cannot change to UID " . $uid["uid"] . " \"" . $_in["daemon"]["uid"] . "\"!", VoIP_LOG_FATAL);
}
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * Show start of operations message
 */
echo "Everything done. Waiting for events!\n\n";

/**
 * Log system initialization
 */
writeLog ( "VoIP Domain client daemon initialized.");

/**
 * Fork process to daemon mode (except if in debug mode)
 */
error_reporting ( E_ERROR);
set_time_limit ( 0);
if ( ! $debug)
{
  $pid = pcntl_fork ();
  if ( $pid == -1)
  {
    writeLog ( "Cannot fork process!", VoIP_LOG_FATAL);
  }
  if ( $pid)
  {
    exit ();
  }
}

/**
 * Pingback interface to flush any unprocessed request queued (result code 200 and event id 0)
 */
reply_event ( 0, 200);

/**
 * Prepare longpolling HTTP connection socket
 */
$socket = curl_init ();
curl_setopt ( $socket, CURLOPT_URL, $_in["daemon"]["lpurl"] . "?server=" . $_in["daemon"]["serverid"] . ".b10000");
curl_setopt ( $socket, CURLOPT_USERAGENT, "VoIP Domain Client v" . $_in["version"] . " (Linux; U)");
curl_setopt ( $socket, CURLOPT_HEADER, true);
curl_setopt ( $socket, CURLOPT_RETURNTRANSFER, true);
curl_setopt ( $socket, CURLOPT_TIMEOUT, 35);
curl_setopt ( $socket, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt ( $socket, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt ( $socket, CURLOPT_FORBID_REUSE, false);

/**
 * Start the longpolling HTTP server connection
 */
$etag = "0";
$last = "Thu, 1 Jan 1970 00:00:00 GMT";
$firstevent = true;
$processed = array ();
while ( true)
{
  curl_setopt ( $socket, CURLOPT_HTTPHEADER, array ( "If-None-Match: " . $etag . "\nIf-Modified-Since: " . $last));
  $result = curl_exec ( $socket);
  $status = curl_getinfo ( $socket, CURLINFO_HTTP_CODE);
  $header = substr ( $result, 0, curl_getinfo ( $socket, CURLINFO_HEADER_SIZE));
  $body = substr ( $result, curl_getinfo ( $socket, CURLINFO_HEADER_SIZE));

  // If there's an ETag header, update the local variable
  foreach ( explode ( "\n", str_replace ( "\r", "", $header)) as $line)
  {
    if ( strpos ( $line, "Etag: ") !== false)
    {
      $etag = substr ( $line, strpos ( $line, ": ") + 2);
    }
    if ( strpos ( $line, "Last-Modified: ") !== false)
    {
      $last = substr ( $line, strpos ( $line, ": ") + 2);
    }
  }

  // If ocurred timeout, reconnect
  if ( $status == 304)
  {
    continue;
  }

  // If return code was different of 200, log and wait 30 seconds to reconnect
  if ( $status != 200)
  {
    writeLog ( "Received HTTP code " . $status . " response. Waiting 30 seconds to try a new connection.", VoIP_LOG_WARNING);
    sleep ( 30);
    continue;
  }

  /**
   * If it's the first event received, change URL to remove request of old messages
   */
  if ( $firstevent == true)
  {
    curl_setopt ( $socket, CURLOPT_URL, $_in["daemon"]["lpurl"] . "?server=" . $_in["daemon"]["serverid"]);
    $firstevent = false;
  }

  /**
   * Process events
   */
  while ( substr ( $body, 0, "19") == "a:1:{s:5:\"event\";s:")
  {
    // Decrypt informations
    $size = (int) substr ( $body, 19, 19 - strpos ( $body, ":", 19));
    if ( $event = unserialize ( openssl_decrypt ( substr ( $body, strpos ( $body, "\"", 19) + 1, $size), "AES-256-CBC", $_in["daemon"]["key"], OPENSSL_RAW_DATA)))
    {
      if ( ! array_key_exists ( "event", $event) || ! array_key_exists ( "id", $event))
      {
        writeLog ( "Received event without event name and/or ID. Ignoring.", VoIP_LOG_WARNING);
      } else {
        if ( array_key_exists ( $event["id"], $processed))
        {
          writeLog ( $event["event"] . "(" . $event["id"] . ") - Ignoring already processed event.", VoIP_LOG_WARNING);
        } else {
          $result = framework_call ( $event["event"], $event);
          if ( ! is_array ( $result) || ! array_key_exists ( "code", $result) || ! array_key_exists ( "message", $result))
          {
            $processed[$event["id"]] = $result;
            writeLog ( $event["event"] . "(" . $event["id"] . ") - Event didn't return correctly result.", VoIP_LOG_WARNING);
          } else {
            $processed[$event["id"]] = $result["code"];
            reply_event ( $event["id"], $result["code"]);
            writeLog ( $event["event"] . "(" . $event["id"] . ") - " . $result["message"], ( $result["code"] != 200 ? VoIP_LOG_WARNING : VoIP_LOG_NOTICE));
          }
        }
      }
    } else {
      writeLog ( "Error decrypting command. Ignoring event.", VoIP_LOG_WARNING);
    }
    $body = substr ( $body, 24 + strlen ( $size) + $size);
  }
}
?>
