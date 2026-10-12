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
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ALL);
ini_set ( "display_errors", "false");

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
require_once ( dirname ( __FILE__) . "/includes/file-transfer.inc.php");
require_once ( dirname ( __FILE__) . "/includes/systemd.inc.php");
require_once ( dirname ( __FILE__) . "/includes/plugin-install.inc.php");
require_once ( dirname ( __FILE__) . "/includes/bootstrap.inc.php");
require_once ( dirname ( __FILE__) . "/includes/error-handler.inc.php");

/**
 * Register error handlers for clear crash reporting
 */
daemon_register_error_handlers ();

/**
 * Ensure single instance
 */
daemon_lock ( "client");

/**
 * Parse configuration file. You should put your configuration file OUTSIDE
 * the web server files path, or you must block access to this file at the
 * web server configuration. Your configuration would contain passwords and
 * other sensitive configurations.
 */
$_in = parse_ini_file ( "/etc/voipdomain/client.conf", true);
if ( $_in === false)
{
  echo "Error: Cannot parse configuration file /etc/voipdomain/client.conf\n";
  exit ( 1);
}
if ( ! is_array ( $_in["general"]))
{
  echo "Error: Cannot find \"general\" section at configuration file.\n";
  exit ( 1);
}
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
$_in["verbose"] = false;
for ( $x = 1; $x < $argc; $x++)
{
  switch ( $argv[$x])
  {
    case "--debug":
    case "-d":
      $_in["debug"] = true;
      break;
    case "--verbose":
    case "-v":
      $_in["verbose"] = true;
      break;
    case "--help":
    case "-h":
      echo "Usage: " . basename ( $argv[0]) . " [--help|-h] [--debug|-d] [--verbose|-v]\n";
      echo "  --help|-h:     Show this help information\n";
      echo "  --debug|-d:    Enable debug messages (do not fork the daemon)\n";
      echo "  --verbose|-v:  Show loaded configuration\n";
      exit ();
      break;
    default:
      echo "ERROR: Invalid parameter \"" . $argv[$x] . "\"!\n";
      exit ( -1);
      break;
  }
}

/**
 * Show loaded configuration if verbose mode
 */
if ( $_in["verbose"])
{
  echo "Loaded [general] configuration:\n";
  foreach ( $_in["general"] as $key => $value)
  {
    echo "  " . $key . " = '" . $value . "'\n";
  }
  echo "\nChecking 'address' key:\n";
  echo "  array_key_exists('address', \$_in['general']): " . ( array_key_exists ( "address", $_in["general"]) ? "true" : "false") . "\n";
  if ( array_key_exists ( "address", $_in["general"]))
  {
    echo "  Value: '" . $_in["general"]["address"] . "'\n";
    echo "  empty(): " . ( empty ( $_in["general"]["address"]) ? "true" : "false") . "\n";
    echo "  trim(): '" . trim ( $_in["general"]["address"]) . "'\n";
    echo "  strlen(): " . strlen ( $_in["general"]["address"]) . "\n";
    echo "  Hex dump: ";
    for ( $i = 0; $i < strlen ( $_in["general"]["address"]); $i++)
    {
      echo sprintf ( "%02x ", ord ( $_in["general"]["address"][$i]));
    }
    echo "\n";
  }
  echo "\n";
}

/**
 * Check for cryptography key paths (set defaults if not configured)
 */
if ( ! array_key_exists ( "privateKey", $_in["general"]) || empty ( $_in["general"]["privateKey"]))
{
  $_in["general"]["privateKey"] = "/etc/voipdomain/client-certificate.key";
}
if ( ! array_key_exists ( "publicKey", $_in["general"]) || empty ( $_in["general"]["publicKey"]))
{
  $_in["general"]["publicKey"] = "/etc/voipdomain/client-certificate.pub";
}
if ( ! array_key_exists ( "masterPublicKey", $_in["general"]) || empty ( $_in["general"]["masterPublicKey"]))
{
  $_in["general"]["masterPublicKey"] = "/etc/voipdomain/master-certificate.pub";
}

/**
 * Load criptography keys
 */
$_in["keys"] = array ();
if ( ! file_exists ( $_in["general"]["privateKey"]))
{
  echo "Error: Client private key file not found: " . $_in["general"]["privateKey"] . "\n";
  echo "TIP: Run the installation script to generate and install the client certificates.\n";
  exit ( 1);
}
if ( ! $_in["keys"]["privateKey"] = file_get_contents ( $_in["general"]["privateKey"]))
{
  writeLog ( "Cannot read client private key!", VoIP_LOG_FATAL);
}
if ( ! file_exists ( $_in["general"]["publicKey"]))
{
  echo "Error: Client public key file not found: " . $_in["general"]["publicKey"] . "\n";
  echo "TIP: Run the installation script to generate and install the client certificates.\n";
  exit ( 1);
}
if ( ! $_in["keys"]["publicKey"] = file_get_contents ( $_in["general"]["publicKey"]))
{
  writeLog ( "Cannot read client public key!", VoIP_LOG_FATAL);
}
if ( ! file_exists ( $_in["general"]["masterPublicKey"]))
{
  echo "Error: Master server public key file not found: " . $_in["general"]["masterPublicKey"] . "\n";
  echo "TIP: Run the installation script to download the master server certificate.\n";
  exit ( 1);
}
if ( ! $_in["keys"]["masterPublicKey"] = file_get_contents ( $_in["general"]["masterPublicKey"]))
{
  writeLog ( "Cannot read master server public key!", VoIP_LOG_FATAL);
}

/**
 * Validate local server address configuration
 */
if ( ! array_key_exists ( "address", $_in["general"]) || empty ( trim ( $_in["general"]["address"])))
{
  echo "Error: Local server address not configured.\n";
  echo "TIP: Set 'address = <server_ip>' in [general] section of /etc/voipdomain/client.conf\n";
  exit ( 1);
}
$_in["general"]["address"] = trim ( $_in["general"]["address"]);

/**
 * Connect to the database
 */
echo "Executing: Connecting to database... ";
try
{
  $_in["mysql"]["id"] = new mysqli ( $_in["mysql"]["hostname"] . ( ! empty ( $_in["mysql"]["port"]) ? ":" . $_in["mysql"]["port"] : ""), $_in["mysql"]["username"], $_in["mysql"]["password"], $_in["mysql"]["database"]);
} catch ( mysqli_sql_exception $e)
{
  echo chr ( 27) . "[1;31mFAILED" . chr ( 27) . "[1;0m\n\n";
  echo chr ( 27) . "[1;31mDatabase connection error:" . chr ( 27) . "[1;0m " . $e->getMessage () . "\n\n";
  echo "Please check:\n";
  echo "  - MariaDB/MySQL is running: systemctl status mariadb\n";
  echo "  - Database exists: mysql -e \"SHOW DATABASES LIKE '" . $_in["mysql"]["database"] . "'\"\n";
  echo "  - User/password in /etc/voipdomain/client.conf matches the database\n";
  echo "  - User has access: mysql -u " . $_in["mysql"]["username"] . " -p " . $_in["mysql"]["database"] . "\n\n";
  writeLog ( "Cannot connect to database: " . $e->getMessage (), VoIP_LOG_FATAL);
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
 * Include all plugin files from client-plugins/
 */
$_in["loaded_plugins"] = array ();
$pluginsdir = dirname ( __FILE__) . "/client-plugins";
if ( is_dir ( $pluginsdir))
{
  echo "Executing: Loading plugins... ";
  foreach ( scandir ( $pluginsdir) as $entry)
  {
    if ( $entry == "." || $entry == "..")
    {
      continue;
    }
    $pluginpath = $pluginsdir . "/" . $entry;
    if ( is_dir ( $pluginpath) && is_readable ( $pluginpath . "/client.php"))
    {
      require_once ( $pluginpath . "/client.php");
      $_in["loaded_plugins"][] = $entry;
    }
  }
  echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m (" . sizeof ( $_in["loaded_plugins"]) . " plugins)\n";
}

/**
 * Resolve deferred hook registrations
 */
framework_resolve_deferred_hooks ();

/**
 * Restore pending VDFT file transfer sessions from disk
 */
echo "Executing: Restoring file transfer sessions... ";
$restored = file_transfer_restore_sessions ();
echo chr ( 27) . "[1;37m" . $restored . " session(s)" . chr ( 27) . "[1;0m\n";

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
 * Open log file before dropping privileges (keep handle open)
 */
echo "Executing: Opening log file... ";
if ( ! openLogFile ())
{
  echo chr ( 27) . "[1;31mFAILED" . chr ( 27) . "[1;0m\n";
  exit ( 1);
}
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
 * Register systemd reload handler and continue as worker process
 */
daemon_register_systemd_reload_handler ();

/**
 * Connect to Gearman server(s)
 */
echo "Executing: Connecting to Gearman... ";
$gearman_host = ! empty ( $_in["gearman"]["hostname"]) ? $_in["gearman"]["hostname"] : "127.0.0.1";
$gearman_port = ! empty ( $_in["gearman"]["port"]) ? (int) $_in["gearman"]["port"] : 4730;
try
{
  $_in["gearman"]["worker"] = new GearmanWorker ();
  if ( ! @$_in["gearman"]["worker"]->addServer ( $gearman_host, $gearman_port))
  {
    throw new Exception ( $_in["gearman"]["worker"]->error () ?: "addServer returned false");
  }
} catch ( Exception $e)
{
  echo chr ( 27) . "[1;31mFAILED" . chr ( 27) . "[1;0m\n\n";
  echo chr ( 27) . "[1;31mGearman connection error:" . chr ( 27) . "[1;0m " . $e->getMessage () . "\n\n";
  echo "Please check:\n";
  echo "  - Gearman server is running: systemctl status gearmand\n";
  echo "  - Gearman is listening: gearadmin --status\n";
  echo "  - Configuration in /etc/voipdomain/client.conf [gearman] section\n";
  echo "  - Current setting: hostname = ${gearman_host}, port = ${gearman_port}\n\n";
  writeLog ( "Cannot connect to Gearman: " . $e->getMessage (), VoIP_LOG_FATAL);
}
echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";

/**
 * Register client task workers
 */
$_in["gearman"]["worker"]->addFunction ( gearman_client_task_name ( $_in["daemon"]["serverid"]), "task_manager");
if ( array_key_exists ( "backup", $_in["daemon"]) && gearman_backup_mode_enabled ( $_in["daemon"]["backup"]))
{
  $_in["gearman"]["worker"]->addFunction ( gearman_backup_task_name ( $_in["daemon"]["serverid"]), "task_manager");
}

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
 * Fire daemon startup hook (bootstrap regeneration, module verification, etc.)
 */
echo "Executing: Running startup hooks... ";
if ( framework_has_hook ( "daemon_startup"))
{
  framework_call ( "daemon_startup", array (), false, "");
}
echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";

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
    if ( array_key_exists ( "Before", $entry["Configuration"]) && is_array ( $entry["Configuration"]["Before"]))
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
    }
    if ( array_key_exists ( "After", $entry["Configuration"]) && is_array ( $entry["Configuration"]["After"]))
    {
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
  return $resultcode == 0 && is_readable ( $target);
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
  return $resultcode == 0 && is_readable ( $target);
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
  $signcheck = openssl_verify ( $key . $data["iv"] . $data["hmac"] . $data["payload"], $data["sign"], $_in["keys"]["masterPublicKey"], OPENSSL_ALGO_SHA256);
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
    file_put_contents ( $_in["eventsfile"], "New event received: " . $payload["event"] . "\n" . print_r ( $payload["event"] == "profile_add" || $payload["event"] == "file_transfer_chunk" ? array_merge ( $payload["data"], array ( "Data" => "***FILE CONTENT***")) : $payload["data"], true) . "\n", FILE_APPEND);
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
          try
          {
            $result = framework_call ( $eventdata["event"], $eventdata["data"]);
          } catch ( Throwable $e)
          {
            $result = array ( "code" => 500, "message" => "Exception: " . $e->getMessage ());
            writeLog ( $eventdata["event"] . "(" . $payload["id"] . " group) threw " . get_class ( $e) . ": " . $e->getMessage () . " in " . $e->getFile () . ":" . $e->getLine (), VoIP_LOG_WARNING);
          }
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
    try
    {
      $result = framework_call ( $payload["event"], $payload["data"]);
    } catch ( Throwable $e)
    {
      $result = array ( "code" => 500, "message" => "Exception: " . $e->getMessage ());
      writeLog ( $payload["event"] . "(" . $payload["id"] . ") threw " . get_class ( $e) . ": " . $e->getMessage () . " in " . $e->getFile () . ":" . $e->getLine (), VoIP_LOG_WARNING);
      $trace_lines = explode ( "\n", $e->getTraceAsString ());
      foreach ( array_slice ( $trace_lines, 0, 5) as $trace_line)
      {
        writeLog ( "  " . $trace_line, VoIP_LOG_WARNING);
      }
    }
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
      writeLog ( $payload["event"] . "(" . $payload["id"] . ") - Result " . $result["code"] . ": " . $result["message"] . ( ! empty ( $result["content"]) ? " (Content: " . json_encode ( $result["content"]) . ")" : ""), ( $result["code"] < 200 || $result["code"] > 299 ? VoIP_LOG_WARNING : VoIP_LOG_NOTICE));
    }
  }
}
?>
