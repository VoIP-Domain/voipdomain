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
 * Remote Asterisk server configuration and infra structure auto provisioning
 * daemon.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk Client Daemon
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
require_once ( dirname ( __FILE__) . "/includes/functions.inc.php");
require_once ( dirname ( __FILE__) . "/includes/plugins.inc.php");

/**
 * Parse configuration file. You should put your configuration file OUTSIDE
 * the web server files path, or you must block access to this file at the
 * web server configuration. Your configuration would contain passwords and
 * other sensitive configurations.
 */
$_in = parse_ini_file ( "/etc/voipdomain/client.conf", true);
$_in["processed"] = array ();
$_in["db"] = array ();
$_in["module"] = "";
$_in["cleanup"] = array ();
$_in["cleanup"]["raw"] = array ();
$_in["cleanup"]["entries"] = array ();

/**
 * Include all modules configuration files
 */
foreach ( glob ( dirname ( __FILE__) . "/client-modules/*/config.php") as $filename)
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
 * Check for minimum requirements
 */
if ( ! extension_loaded ( "gearman"))
{
  echo "Error: You need to install PHP PECL Gearman extension!\n";
  exit ( 1);
}
if ( ! extension_loaded ( "mysqli"))
{
  echo "Error: You need to install PHP MySQLi extension!\n";
  exit ( 1);
}

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
$_in["debug"] = false;
for ( $x = 1; $x < $argc; $x++)
{
  switch ( $argv[$x])
  {
    case "--debug":
    case "-d":
      $_in["debug"] = true;
      break;
    case "--help":
    case "-h":
      echo "Usage: " . basename ( $argv[0]) . " [--help|-h] [--debug|-d]\n";
      echo "  --help|-h:    Show this help information\n";
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
 * Load criptography keys
 */
$_in["keys"] = array ();
if ( ! $_in["keys"]["privateKey"] = file_get_contents ( $_in["general"]["privateKey"]))
{
  writeLog ( "Cannot read client private key!", VoIP_LOG_FATAL);
}
if ( ! $_in["keys"]["publicKey"] = file_get_contents ( $_in["general"]["publicKey"]))
{
  writeLog ( "Cannot read client public key!", VoIP_LOG_FATAL);
}
if ( ! $_in["keys"]["masterPublicKey"] = file_get_contents ( $_in["general"]["masterPublicKey"]))
{
  writeLog ( "Cannot read master server public key!", VoIP_LOG_FATAL);
}

/**
 * Conect to the database
 */
echo "Executing: Connecting to database... ";
if ( ! $_in["mysql"]["id"] = @new mysqli ( $_in["mysql"]["hostname"] . ( ! empty ( $_in["mysql"]["port"]) ? ":" . $_in["mysql"]["port"] : ""), $_in["mysql"]["username"], $_in["mysql"]["password"], $_in["mysql"]["database"]))
{
  writeLog ( "Cannot connect to database server!", VoIP_LOG_FATAL);
}
echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";

/**
 * Include all modules files
 */
echo "Executing: Loading modules... ";
foreach ( glob ( dirname ( __FILE__) . "/client-modules/*/client.php") as $filename)
{
  require_once ( $filename);
}
echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";

/**
 * If possible, change process name
 */
if ( extension_loaded ( "proctitle"))
{
  echo "Executing: Changing process name... ";
  setproctitle ( "VoIP Domain client daemon");
  echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";
}

/**
 * Change current work directory to root
 */
echo "Executing: Changing working directory... ";
chdir ( "/");
echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";

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
echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";

/**
 * Set envents file name if debug enabled
 */
if ( $_in["debug"])
{
  $_in["eventsfile"] = @tempnam ( sys_get_temp_dir (), "vd-client-events-");
}

/**
 * Show start of operations message
 */
echo "Everything done. Waiting for events!\n\n";

/**
 * Log system initialization
 */
writeLog ( "VoIP Domain client daemon initialized.");
echo "\n";

/**
 * Fork process to daemon mode (except if in debug mode)
 */
error_reporting ( E_ERROR);
set_time_limit ( 0);
if ( ! $_in["debug"])
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
 * Connect to Gearman server(s)
 */
$_in["gearman"]["worker"] = new GearmanWorker ();
if ( ! $_in["gearman"]["worker"]->addServer ( $_in["gearman"]["servers"]))
{

  echo "Error: Cannot connect to Gearman servers (" . $_in["gearman"]["worker"]->error () . ")!\n";
  exit ( 1);
}

/**
 * Register client task
 */
$_in["gearman"]["worker"]->addFunction ( "vd_client_" . $_in["daemon"]["serverid"] . "_task", "task_manager");

/**
 * Flush all events until no one left
 */
$_in["gearman"]["worker"]->addOptions ( GEARMAN_WORKER_NON_BLOCKING);
while ( @$_in["gearman"]["worker"]->work () || $_in["gearman"]["worker"]->returnCode () == GEARMAN_IO_WAIT || $_in["gearman"]["worker"]->returnCode () == GEARMAN_NO_JOBS)
{
  if ( $_in["gearman"]["worker"]->returnCode () == GEARMAN_SUCCESS)
  {
    continue;
  }

  break;
}
$_in["gearman"]["worker"]->removeOptions ( GEARMAN_WORKER_NON_BLOCKING);
echo "Finished flusing pending events, requesting a task flush!\n";

/**
 * Pingback interface to flush any unprocessed request queued (result code 200 and event id 0)
 */
system_event ( "task_replay", array ());

/**
 * Start the main loop
 */
while ( $_in["gearman"]["worker"]->work ());

/**
 * Function to add a register to cleanup system.
 *
 * @global array $_in Framework global configuration variable
 * @param string $handler The name of the register
 * @param string $hook The hook to be called
 * @param array[optional] The configuration (before or after another register)
 * @return null
 */
function cleanup_register ( $handler, $hook, $configuration = array ())
{
  global $_in;

  // Add new entry:
  if ( array_key_exists ( $handler, $_in["cleanup"]["raw"]))
  {
    writeLog ( "Redeclaring cleanup handler \"" . $handler . "\"!", VoIP_LOG_WARNING);
  }
  $_in["cleanup"]["raw"][$handler] = array ( "Hook" => $hook, "Configuration" => $configuration);
}

/**
 * Function to check if configuration overwrite is allowed.
 *
 * @global array $_in Framework global configuration variable
 * @return boolean If configuration should be overwrite
 */
function check_overwrite ()
{
  global $_in;

  if ( ! array_key_exists ( "overwrite", $_in["general"]) || ! $_in["general"]["overwrite"])
  {
    return false;
  } else {
    return true;
  }
}

/**
 * Function to check the cleanup registers dependencies.
 *
 * @global array $_in Framework global configuration variable
 * @return null
 */
function check_cleanup_registers ()
{
  global $_in;

  // Empty cleanup array:
  $_in["cleanup"]["entries"] = array ();

  // Add each raw entry:
  foreach ( $_in["cleanup"]["raw"] as $handler => $entry)
  {
    $_in["cleanup"]["entries"][$handler] = array ( "Hook" => $entry["Hook"], "Deps" => array ());
  }

  // Process each dependency:
  foreach ( $_in["cleanup"]["raw"] as $handler => $entry)
  {
    if ( array_key_exists ( "Before", $entry["Configuration"]))
    {
      foreach ( $entry["Configuration"]["Before"] as $dep)
      {
        if ( ! array_key_exists ( $dep, $_in["cleanup"]["entries"]))
        {
          writeLog ( "Cleanup handler \"" . $handler . "\" with non-exist dependency (before)!", VoIP_LOG_WARNING);
        } else {
          $_in["cleanup"]["entries"][$dep]["Deps"][] = $handler;
        }
      }
      foreach ( $entry["Configuration"]["After"] as $dep)
      {
        if ( ! array_key_exists ( $dep, $_in["cleanup"]["entries"]))
        {
          writeLog ( "Cleanup handler \"" . $handler . "\" with non-exist dependency (after)!", VoIP_LOG_WARNING);
        } else {
          $_in["cleanup"]["entries"][$handler]["Deps"][] = $dep;
        }
      }
    }
  }

  // Remove raw entries:
  unset ( $_in["cleanup"]["raw"]);
}

/**
 * Function to convert mp3 to wav.
 *
 * @global array $_in Framework global configuration variable
 * @param string $source Source MP3 file
 * @param string $target Target wav file
 * @return boolean Operation result
 */
function mp3towav ( $source, $target)
{
  global $_in;

  // Be sure that the directory has a trailing "/":
  $dir = preg_replace ( "/\/+$/", "", $_in["general"]["soundsdir"]) . "/";

  // Add directory name and remove trailing "/" from filename:
  $source = $dir . preg_replace ( "/^\/+/", "", $source);
  $target = $dir . preg_replace ( "/^\/+/", "", $target);

  // Check if source file exists:
  if ( ! file_exists ( $source))
  {
    return false;
  }

  // Convert MP3 to WAV using mpg123:
  exec ( "mpg123 -w \"" . addslashes ( $target) . "\" \"" . addslashes ( $source) . "\" 1>/dev/null 2>&1", $output, $resultcode);

  // Return result code:
  return $resultcode == 0;
}

/**
 * Function to convert audio to raw.
 *
 * @global array $_in Framework global configuration variable
 * @param string $source Source audio file
 * @param string $target Target raw file
 * @return boolean Operation result
 */
function audiotoraw ( $source, $target)
{
  global $_in;

  // Be sure that the directory has a trailing "/":
  $dir = preg_replace ( "/\/+$/", "", $_in["general"]["soundsdir"]) . "/";

  // Add directory name and remove trailing "/" from filename:
  $source = $dir . preg_replace ( "/^\/+/", "", $source);
  $target = $dir . preg_replace ( "/^\/+/", "", $target);

  // Check if source file exists:
  if ( ! file_exists ( $source))
  {
    return false;
  }

  // Convert audio to raw using sox:
  exec ( "sox \"" . addslashes ( $source) . "\" -t raw -r 8000 -e signed-integer -b 16 -c 1 \"" . addslashes ( $target) . "\" 1>/dev/null 2>&1", $output, $resultcode);

  // Return result code:
  return $resultcode == 0;
}

/**
 * Function to manage tasks.
 *
 * @global array $_in Framework global configuration variable
 * @param object $job Gearman job object
 * @return null
 */
function task_manager ( $job)
{
  global $_in;

  /**
   * Get task payload
   */
  $data = json_decode ( $job->workload (), true);
  if ( ! array_key_exists ( "payload", $data) || ! array_key_exists ( "iv", $data) || ! array_key_exists ( "key", $data) || ! array_key_exists ( "hmac", $data) || ! array_key_exists ( "sign", $data))
  {
    writeLog ( "Received event without minimum data. Ignoring task!", VoIP_LOG_WARNING);
    return;
  }
  $data["payload"] = base64_decode ( $data["payload"]);
  $data["iv"] = base64_decode ( $data["iv"]);
  $data["key"] = base64_decode ( $data["key"]);
  $data["hmac"] = base64_decode ( $data["hmac"]);
  $data["sign"] = base64_decode ( $data["sign"]);

  /**
   * Decrypt key
   */
  openssl_private_decrypt ( $data["key"], $key, $_in["keys"]["privateKey"]);

  /**
   * Check job payload content sign
   */
  $signcheck = openssl_verify ( $key . $data["iv"] . $data["hmac"] . $data["payload"], $data["sign"], $_in["keys"]["masterPublicKey"], OPENSSL_ALGO_SHA1);
  if ( $signcheck != 1)
  {
    writeLog ( "Received task with wrong server signature. Ignoring task!", VoIP_LOG_WARNING);
    return;
  }

  /**
   * Validate HMAC
   */
  $calcmac = hash_hmac ( "sha256", $data["payload"], $key, true);
  if ( ! hash_equals ( $data["hmac"], $calcmac))
  {
    writeLog ( "Received task with wrong HMAC signature. Ignoring task!", VoIP_LOG_WARNING);
    return;
  }

  /**
   * Decrypt information
   */
  $payload = json_decode ( openssl_decrypt ( $data["payload"], "aes-256-cbc", $key, OPENSSL_RAW_DATA, $data["iv"]), true);

  /**
   * Check decrypted content
   */
  if ( ! array_key_exists ( "data", $payload) || ! array_key_exists ( "event", $payload) || ! array_key_exists ( "id", $payload))
  {
    writeLog ( "Received event without minimum data. Ignoring task!", VoIP_LOG_WARNING);
    return;
  }

  /**
   * Write event information if debug enabled
   */
  if ( $_in["debug"])
  {
    file_put_contents ( $_in["eventsfile"], "New event received: " . $payload["event"] . "\n" . print_r ( $payload["event"] == "profile_add" ? array_merge ( $payload["data"], array ( "Data" => "***FILE CONTENT***")) : $payload["data"], true) . "\n", FILE_APPEND);
  }

  /**
   * Check for event to execute
   */
  if ( array_key_exists ( $payload["id"], $_in["processed"]) && ! ( $_in["processed"][$payload["id"]] >= 200 && $_in["processed"][$payload["id"]] <= 299))
  {
    writeLog ( $payload["event"] . "(" . $payload["id"] . ") - Ignoring already processed event.", VoIP_LOG_WARNING);
    return;
  }

  /**
   * Check for grouped batch events
   */
  if ( $payload["event"] == "VD_NOTIFY_GROUP")
  {
    $eventgroup = $payload["data"];
    while ( true)
    {
      $allok = true;
      $allfail = true;
      foreach ( $eventgroup as $eventid => $eventdata)
      {
        if ( ! array_key_exists ( "result", $eventdata) || ! ( $eventdata["result"] >= 200 && $eventdata["result"] <= 299))
        {
          $result = framework_call ( $eventdata["event"], $eventdata["data"]);
          if ( ! is_array ( $result) || ! array_key_exists ( "code", $result) || ! array_key_exists ( "message", $result))
          {
            $eventgroup[$eventid]["result"] = $result;
            writeLog ( $eventdata["event"] . "(" . $payload["id"] . " group) - Event didn't return correctly result.", VoIP_LOG_WARNING);
          } else {
            $eventgroup[$eventid]["result"] = $result["code"];
            writeLog ( $eventdata["event"] . "(" . $payload["id"] . " group) - Result " . $result["code"] . ": " . $result["message"], ( $result["code"] < 200 || $result["code"] > 299 ? VoIP_LOG_WARNING : VoIP_LOG_NOTICE));
          }
          if ( $result["code"] >= 200 && $result["code"] <= 299)
          {
            $allfail = false;
          } else {
            $allok = false;
          }
          @$eventgroup[$eventid]["tries"]++;
        }
      }
      if ( $allok)
      {
        reply_event ( $payload["id"], array ( "code" => 200, "message" => "All events proceeed."));
        writeLog ( "Grouped event (" . $payload["id"] . ") - Result 200: All events proceeed.", VoIP_LOG_NOTICE);
        break;
      }
      if ( $allfail)
      {
        writeLog ( "Grouped event (" . $payload["id"] . ") - Failed, pending events with error.", VoIP_LOG_WARNING);
        if ( $_in["debug"])
        {
          foreach ( $eventgroup as $eventid => $eventdata)
          {
            if ( $eventdata["result"] >= 200 && $eventdata["result"] <= 299)
            {
              unset ( $eventgroup[$eventid]);
            }
          }
          if ( $debugfile = @tempnam ( sys_get_temp_dir (), "vd-client-"))
          {
            file_put_contents ( $debugfile, "Grouped event (" . $payload["id"] . ") - Failed, pending events with error.\n\nFailed events:\n" . json_encode ( $eventgroup) . "\n\nHuman readable:\n" . print_r ( $eventgroup, true) . "\n");
            echo "DEBUG: Wrote debug file \"" . $debugfile . "\"!\n";
          } else {
            echo "WARNING: Cannot create debug file!\n";
          }
        }
        break;
      }
      writeLog ( "Grouped event (" . $payload["id"] . ") - Rerunning, pending events with error.", VoIP_LOG_WARNING);
    }
  } else {
    $result = framework_call ( $payload["event"], $payload["data"]);
    if ( ! is_array ( $result) || ! array_key_exists ( "code", $result) || ! array_key_exists ( "message", $result))
    {
      $_in["processed"][$payload["id"]] = $result;
      writeLog ( $payload["event"] . "(" . $payload["id"] . ") - Event didn't return correctly result.", VoIP_LOG_WARNING);
      if ( $_in["debug"])
      {
        if ( $debugfile = @tempnam ( sys_get_temp_dir (), "vd-client-"))
        {
          file_put_contents ( $debugfile, "Event " . $payload["event"] . " (" . $payload["id"] . ") - Failed with result:\n" . print_r ( $result, true) . "Event data:\n" . json_encode ( $payload["data"]) . "\n\nHuman readable:\n" . print_r ( $payload["data"], true) . "\n");
          echo "DEBUG: Wrote debug file \"" . $debugfile . "\"!\n";
        } else {
          echo "WARNING: Cannot create debug file!\n";
        }
      }
    } else {
      reply_event ( $payload["id"], $result);
      $_in["processed"][$payload["id"]] = $result["code"];
      writeLog ( $payload["event"] . "(" . $payload["id"] . ") - Result " . $result["code"] . ": " . $result["message"] . ( $result["content"] ? " (Content: " . json_encode ( $result["content"]) : ""), ( $result["code"] < 200 || $result["code"] > 299 ? VoIP_LOG_WARNING : VoIP_LOG_NOTICE));
    }
  }
}
?>
