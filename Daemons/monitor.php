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
 * This daemon connects to Asterisk through AMI interface, and keep listening to
 * events and doing actions when needed.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage AMI Interface
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
require_once ( dirname ( __FILE__) . "/includes/asterisk.inc.php");
require_once ( dirname ( __FILE__) . "/includes/systemd.inc.php");
require_once ( dirname ( __FILE__) . "/includes/error-handler.inc.php");

/**
 * Register error handlers for clear crash reporting
 */
daemon_register_error_handlers ();

/**
 * Ensure single instance
 */
daemon_lock ( "monitor");

/**
 * Parse configuration file. You should put your configuration file OUTSIDE
 * the web server files path, or you must block access to this file at the
 * web server configuration. Your configuration would contain passwords and
 * other sensitive configurations.
 */
$_in = parse_ini_file ( "/etc/voipdomain/monitor.conf", true);

/**
 * Include all modules configuration files
 */
foreach ( glob ( dirname ( __FILE__) . "/monitor-modules/*/monitor.php") as $filename)
{
  require_once ( $filename);
}

/**
 * Include all plugin files from monitor-plugins/
 */
$_in["loaded_plugins"] = array ();
$pluginsdir = dirname ( __FILE__) . "/monitor-plugins";
if ( is_dir ( $pluginsdir))
{
  foreach ( scandir ( $pluginsdir) as $entry)
  {
    if ( $entry == "." || $entry == "..")
    {
      continue;
    }
    $pluginpath = $pluginsdir . "/" . $entry;
    if ( is_dir ( $pluginpath) && is_readable ( $pluginpath . "/monitor.php"))
    {
      require_once ( $pluginpath . "/monitor.php");
      $_in["loaded_plugins"][] = $entry;
    }
  }
}

/**
 * Resolve deferred hook registrations
 */
framework_resolve_deferred_hooks ();

/**
 * Check for mandatory basic configurations (if didn't exist, set default)
 */
if ( ! array_key_exists ( "general", $_in))
{
  $_in["general"] = array ();
}
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
echo chr ( 27) . "[1;37mVoIP Domain Asterisk Monitor Daemon" . chr ( 27) . "[1;0m v" . $_in["version"] . "\n";
echo "\n";

/**
 * Validate MySQL session
 */
if ( ! array_key_exists ( "mysql", $_in) || ! is_array ( $_in["mysql"]))
{
  echo "Error: Cannot find \"mysql\" session at configuration file.\n";
  exit ( 1);
}

/**
 * Validate AMI session
 */
if ( ! array_key_exists ( "ami", $_in) || ! is_array ( $_in["ami"]))
{
  echo "Error: Cannot find \"ami\" session at configuration file.\n";
  exit ( 1);
}

/**
 * Validate gearman session
 */
if ( ! array_key_exists ( "gearman", $_in) || ! is_array ( $_in["gearman"]))
{
  echo "Error: Cannot find \"gearman\" session at configuration file.\n";
  exit ( 1);
}

/**
 * Check if required PHP modules are available
 */
if ( ! extension_loaded ( "gearman"))
{
  echo "Error: Your PHP must have the Gearman module loaded!\n";
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
 * If possible, change process name
 */
if ( extension_loaded ( "proctitle"))
{
  setproctitle ( "VoIP Domain monitor daemon");
}

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
 * Connect to gearman
 */
echo "Executing: Connecting to message router... ";
$gearman_host = ! empty ( $_in["gearman"]["hostname"]) ? $_in["gearman"]["hostname"] : "127.0.0.1";
$gearman_port = ! empty ( $_in["gearman"]["port"]) ? $_in["gearman"]["port"] : 4730;
try
{
  $gm = new GearmanClient ();
  if ( ! @$gm->addServer ( $gearman_host, $gearman_port))
  {
    throw new Exception ( "addServer returned false");
  }
} catch ( Exception $e)
{
  echo chr ( 27) . "[1;31mFAILED" . chr ( 27) . "[1;0m\n\n";
  echo chr ( 27) . "[1;31mGearman connection error:" . chr ( 27) . "[1;0m " . $e->getMessage () . "\n\n";
  echo "Please check:\n";
  echo "  - Gearman server is running: systemctl status gearmand\n";
  echo "  - Gearman is listening on ${gearman_host}:${gearman_port}\n";
  echo "  - Test connection: nc -zv ${gearman_host} ${gearman_port}\n";
  echo "  - Firewall allows port ${gearman_port}: firewall-cmd --list-ports\n";
  echo "  - Configuration in /etc/voipdomain/monitor.conf [gearman] section\n\n";
  writeLog ( "Cannot connect to Gearman: " . $e->getMessage (), VoIP_LOG_FATAL);
}
echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";

/**
 * Show start of operations message
 */
echo "Everything done. Waiting for events!\n\n";

/**
 * Log system initialization
 */
writeLog ( "VoIP Domain monitor daemon initialized.");

/**
 * Fork process to daemon mode (except if in debug mode)
 */
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
 * Register systemd reload handler and continue as worker process
 */
daemon_register_systemd_reload_handler ();

/**
 * Initialize non-blocking Gearman worker for plugin task processing
 */
$_in["gearman_worker"] = null;
try
{
  $_in["gearman_worker"] = new GearmanWorker ();
  $_in["gearman_worker"]->addServer ( $gearman_host, $gearman_port);
  $_in["gearman_worker"]->addOptions ( GEARMAN_WORKER_NON_BLOCKING);
  $_in["gearman_worker"]->setTimeout ( 0);

  $serverid = ! empty ( $_in["daemon"]["serverid"]) ? $_in["daemon"]["serverid"] : "0";
  $_in["gearman_worker"]->addFunction ( "vd_monitor_" . $serverid . "_task", "monitor_gearman_task_handler");
  writeLog ( "Gearman worker registered: vd_monitor_" . $serverid . "_task");
} catch ( Exception $e) {
  writeLog ( "Gearman worker init failed: " . $e->getMessage (), VoIP_LOG_WARNING);
  $_in["gearman_worker"] = null;
}

/**
 * Gearman task handler for monitor plugins
 */
function monitor_gearman_task_handler ( $job)
{
  $payload = json_decode ( $job->workload (), true);
  if ( ! is_array ( $payload) || ! array_key_exists ( "hook", $payload))
  {
    writeLog ( "Gearman task received with invalid payload.", VoIP_LOG_WARNING);
    return json_encode ( array ( "result" => false, "error" => "Invalid payload"));
  }
  writeLog ( "Gearman task received: " . $payload["hook"]);
  $taskdata = array_key_exists ( "data", $payload) ? $payload["data"] : array ();
  try
  {
    $result = framework_call ( $payload["hook"], $taskdata, false, "");
  } catch ( Throwable $e)
  {
    writeLog ( "Task \"" . $payload["hook"] . "\" threw " . get_class ( $e) . ": " . $e->getMessage () . " in " . $e->getFile () . ":" . $e->getLine (), VoIP_LOG_WARNING);
    return json_encode ( array ( "result" => false, "error" => $e->getMessage ()));
  }
  return json_encode ( array ( "result" => true, "data" => $result));
}

/**
 * Register the Asterisk server shutdown event.
 *
 * @global object $gm Gearman client object
 * @global object $ami Asterisk AMI connection object
 * @global boolean $reconnect Reconnection system flag
 * @return [mixed] $buffer Hook buffer
 */
function shutdown ( $buffer, $parameters)
{
  global $gm, $ami, $reconnect;

  $gm->doBackground ( "event", json_encode ( array ( "ServerID" => $_in["daemon"]["serverid"], "Event" => "Shutdown", "Mode" => $parameters["Shutdown"], "Restart" => (bool) $parameters["Restart"])));
  $ami->disconnect ();
  $reconnect = true;

  // End event:
  return $buffer;
}

/**
 * Add shutdown main hook
 */
framework_add_hook ( "Shutdown", "shutdown");

/**
 * Start monitor loop
 */
$_in["Status"] = array ();
$reconnect = true;
$logreconnect = true;
$lastevent = time ();
while ( true)
{
  /**
   * If has a reconnect set, destroy any current connection
   */
  if ( $reconnect == true)
  {
    unset ( $ami);
  }

  /**
   * If doesn't have an active connection, connect to AMI server
   */
  if ( $reconnect)
  {
    $ami = new asterisk ( array ( "username" => $_in["ami"]["username"], "password" => $_in["ami"]["password"], "hostname" => $_in["ami"]["hostname"], "port" => $_in["ami"]["port"], "connect_timeout" => 1, "events" => true));
    if ( ! $ami->connect ())
    {
      if ( $logreconnect)
      {
        writelog ( "Error connecting to server " . $_in["ami"]["hostname"] . ":" . $_in["ami"]["port"] . ".", VoIP_LOG_WARNING);
        $logreconnect = false;
      }
      usleep ( 250);
      continue;
    }
    $reconnect = false;
    $logreconnect = true;
    $lastevent = time ();
    writelog ( "Connected to server " . $_in["ami"]["hostname"] . ":" . $_in["ami"]["port"] . " (AMI version " . $ami->ami_version () . ").");
  }

  /**
   * Check if connection is alive
   */
  if ( time () - $lastevent > 5 && $ami)
  {
    $ping = $ami->request ( "Ping", array (), 1);
    if ( $ping["Response"] != "Success" || $ping["Pong"])
    {
      writelog ( "Closing inactive session to server.", VoIP_LOG_WARNING);
      $reconnect = true;
      continue;
    }
  }

  /**
   * Loop while there's events
   */
  if ( $ami->events_check ())
  {
    $event = $ami->events_shift ();
    if ( $_in["debug"])
    {
      @file_put_contents ( "debug/" . $event["Event"] . ".txt", date ( "r") . ":\n" . str_replace ( "array (\n", "", str_replace ( ",\n)", "\n\n", var_export ( $event, true))), FILE_APPEND);
    }
    if ( array_key_exists ( $event["Event"], $_plugins["hooks"]))
    {
      echo "Framework call: " . $event["Event"] . "\n";
      try
      {
        framework_call ( $event["Event"], $event);
      } catch ( Throwable $e)
      {
        writeLog ( "Event \"" . $event["Event"] . "\" threw " . get_class ( $e) . ": " . $e->getMessage () . " in " . $e->getFile () . ":" . $e->getLine (), VoIP_LOG_WARNING);
        $trace_lines = explode ( "\n", $e->getTraceAsString ());
        foreach ( array_slice ( $trace_lines, 0, 5) as $trace_line)
        {
          writeLog ( "  " . $trace_line, VoIP_LOG_WARNING);
        }
      }
    } else {
      echo "Unknown event received: " . $event["Event"] . "\n";
    }
  } else {
    $ami->wait_event ();
    if ( $ami->checkbuffer ())
    {
      $lastevent = time ();
    }
  }

  /**
   * Poll Gearman worker for pending tasks (non-blocking)
   */
  if ( $_in["gearman_worker"] !== null)
  {
    @$_in["gearman_worker"]->work ();
    if ( $_in["gearman_worker"]->returnCode () != GEARMAN_SUCCESS && $_in["gearman_worker"]->returnCode () != GEARMAN_IO_WAIT && $_in["gearman_worker"]->returnCode () != GEARMAN_NO_JOBS)
    {
      writeLog ( "Gearman worker error: " . $_in["gearman_worker"]->error (), VoIP_LOG_WARNING);
    }
  }
}
?>
