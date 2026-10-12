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
 * FastAGI daemon with applications to Asterisk server.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk FastAGI Application Daemon
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * VoIP Domain FastAGI server
 *
 * The FastAGI server works with thread client processing or processing at main
 * thread. If your script need to perform single tasks like respond to request
 * data from a local database or local processing without need to do processing
 * at Asterisk side, you can use processing at the main thread. Notice that the
 * entire FastAGI server will be locked up till your application end running. If
 * you hang the processing or keep waiting for Asterisk response, you'll block
 * processing of other concurrent requests that was not on multi-thread style.
 * Any new connection will wait too. The good perspective of this mode is that
 * you didn't get the overhead of forking the process.
 */

/**
 * Function to update internal database fetching data from VoIP Domain data files
 * directory.
 *
 * @global array $_in Framework global configuration variable
 * @param string $filetype The type of VoIP Domain data file to be updated
 * @return void
 */
function update_datafiles ( $filetype)
{
  global $_in;

  // Check if inotify is available (update only modified files, otherwise process all files):
  if ( $_in["inotify"] == true && array_key_exists ( $filetype, $_in["db"]))
  {
    // If any new inotify event in queue, add to internal queue
    if ( inotify_queue_len ( $_in["inotify_fd"]))
    {
      $_in["inotity_queue"] = array_merge ( $_in["inotity_queue"], inotify_read ( $_in["inotify_fd"]));
      foreach ( $_in["inotity_queue"] as $key => $value)
      {
        if ( substr ( $value["name"], -5) != ".json")
        {
          unset ( $_in["inotity_queue"][$key]);
        }
      }
    }

    // Check if any modified file are from the filetype requested:
    foreach ( $_in["inotity_queue"] as $key => $inevent)
    {
      if ( substr ( $inevent["name"], 0, strlen ( $filetype) + 1) == $filetype . "-")
      {
        if ( $inevent["mask"] == IN_CREATE || $inevent["mask"] == IN_MODIFY || $inevent["mask"] == IN_CLOSE_WRITE)
        {
          $_in["db"][$filetype][substr ( $inevent["name"], strlen ( $filetype) + 1, strpos ( $inevent["name"], ".", strlen ( $filetype) + 1))] = json_decode ( file_get_contents ( $_in["general"]["datadir"] . "/" . $inevent["name"]), true);
        }
        if ( $inevent["mask"] == IN_DELETE)
        {
          unset ( $_in["db"][$filetype][substr ( $inevent["name"], strlen ( $filetype) + 1, strpos ( $inevent["name"], ".", strlen ( $filetype) + 1))]);
        }
        unset ( $_in["inotity_queue"][$key]);
      }
    }
  } else {
    // Empty local database:
    $_in["db"][$filetype] = array ();

    // Fetch each data file type:
    foreach ( glob ( $_in["general"]["datadir"] . "/" . basename ( $filetype) . "-*.json") as $filename)
    {
      $_in["db"][$filetype][substr ( $filename, strlen ( $_in["general"]["datadir"] . "/" . basename ( $filetype) . "-"), strpos ( $filename, ".", strlen ( $_in["general"]["datadir"] . "/" . basename ( $filetype) . "-")) - strlen ( $_in["general"]["datadir"] . "/" . basename ( $filetype) . "-"))] = json_decode ( file_get_contents ( $filename), true);
    }

    // If inotify enabled, remove from cache any entry from processed filetype:
    if ( $_in["inotify"] == true)
    {
      foreach ( $_in["inotity_queue"] as $key => $inevent)
      {
        if ( substr ( $inevent["name"], 0, strlen ( $filetype) + 1) == $filetype . "-")
        {
          unset ( $_in["inotity_queue"][$key]);
        }
      }
    }
  }
}

/**
 * Function to register an AGI script path and parameters. Current available
 * application registry options are:
 *  - fork: Fork the main process and process at new thread (default true)
 *  - title: Application title
 *
 * @global array $_agi FastAGI global configuration variable
 * @param string $app Application name
 * @param string $hook Related hook to application
 * @param array $options Array containing application configurations
 * @return void
 */
function register_app ( $app, $hook, $options = array ())
{
  global $_agi;

  if ( array_key_exists ( $app, $_agi["applications"]))
  {
    writeLog ( "Trying to register an existing application \"" . $app . "\"!", VoIP_LOG_FATAL);
    exit ();
  }
  $_agi["applications"][$app] = array ( "hook" => $hook, "title" => ( array_key_exists ( "title", $options) ? $options["title"] : $app), "fork" => ( array_key_exists ( "fork", $options) ? $options["fork"] : true));
}

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
 * Initialize FastAGI server variable
 */
$_agi = array ();
$_agi["applications"] = array ();
$_agi["clients"] = array ();

/**
 * Include functions library
 */
require_once ( dirname ( __FILE__) . "/includes/functions.inc.php");
require_once ( dirname ( __FILE__) . "/includes/plugins.inc.php");
require_once ( dirname ( __FILE__) . "/includes/systemd.inc.php");
require_once ( dirname ( __FILE__) . "/includes/error-handler.inc.php");

/**
 * Register error handlers for clear crash reporting
 */
daemon_register_error_handlers ();

/**
 * Ensure single instance
 */
daemon_lock ( "fastagi");

/**
 * Parse configuration file. You should put your configuration file OUTSIDE
 * the web server files path, or you must block access to this file at the
 * web server configuration. Your configuration would contain passwords and
 * other sensitive configurations.
 */
$_in = parse_ini_file ( "/etc/voipdomain/fastagi.conf", true);
$_in["db"] = array ();

/**
 * Include all modules configuration files
 */
foreach ( glob ( dirname ( __FILE__) . "/fastagi-modules/*/fastagi.php") as $filename)
{
  require_once ( $filename);
}

/**
 * Include all plugin files from fastagi-plugins/
 */
$_in["loaded_plugins"] = array ();
$pluginsdir = dirname ( __FILE__) . "/fastagi-plugins";
if ( is_dir ( $pluginsdir))
{
  foreach ( scandir ( $pluginsdir) as $entry)
  {
    if ( $entry == "." || $entry == "..")
    {
      continue;
    }
    $pluginpath = $pluginsdir . "/" . $entry;
    if ( is_dir ( $pluginpath) && is_readable ( $pluginpath . "/fastagi.php"))
    {
      require_once ( $pluginpath . "/fastagi.php");
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
echo chr ( 27) . "[1;37mVoIP Domain FastAGI Daemon" . chr ( 27) . "[1;0m v" . $_in["version"] . "\n";
echo "\n";

/**
 * Validate data directory configuration
 */
if ( ! array_key_exists ( "data", $_in) || ! is_array ( $_in["data"]) || ! array_key_exists ( "datadir", $_in["data"]))
{
  echo "Error: Cannot find \"data\" session with \"datadir\" at configuration file.\n";
  exit ( 1);
}
$_in["general"]["datadir"] = $_in["data"]["datadir"];

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
 * If inotify available, start to monitor VoIP Domain data file directory
 */
if ( extension_loaded ( "inotify"))
{
  $_in["inotify"] = true;
  $_in["inotify_fd"] = inotify_init ();
  stream_set_blocking ( $_in["inotify_fd"], 0);
  $_in["inotify_wd"] = inotify_add_watch ( $_in["inotify_fd"], $_in["general"]["datadir"], IN_CREATE + IN_MODIFY + IN_CLOSE_WRITE + IN_DELETE);
  $_in["inotity_queue"] = array ();
} else {
  $_in["inotify"] = false;
}

/**
 * Fetch gateways from local data files
 */
echo "Executing: Loading gateways from local data... ";
update_datafiles ( "gateway");
echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";

/**
 * If possible, change process name
 */
if ( extension_loaded ( "proctitle"))
{
  setproctitle ( "VoIP Domain FastAGI daemon (main thread)");
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
 * Create new socket and start listening port
 */
echo "Executing: Creating TCP socket at port " . $_in["daemon"]["port"] . "... ";
$socket = socket_create ( AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option ( $socket, SOL_SOCKET, SO_REUSEADDR, 1);
if ( ! socket_bind ( $socket, 0, $_in["daemon"]["port"]))
{
  writeLog ( "Cannot bind to TCP port " . $_in["daemon"]["port"] . "!", VoIP_LOG_FATAL);
}
socket_listen ( $socket, $_in["daemon"]["max_clients"]);
echo chr ( 27) . "[1;37mOK" . chr ( 27) . "[1;0m\n";

/**
 * Show start of operations message
 */
echo "Everything done. Waiting for connections!\n\n";

/**
 * Warn if not using inotify
 */
if ( ! $_in["inotify"])
{
  echo chr ( 27) . "[1;37mWarning" . chr ( 27) . "[1;0m: PHP inotify module not found. This could cause disk I/O overload on huge environments!\n\n";
}

/**
 * Log system initialization
 */
writeLog ( "VoIP Domain FastAGI daemon initialized.");

/**
 * Log each registered application
 */
foreach ( $_agi["applications"] as $app => $data)
{
  echo "Registered application \"" . $app . "\": " . $data["title"] . "\n";
}

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
if ( array_key_exists ( "gearman", $_in) && is_array ( $_in["gearman"]) && extension_loaded ( "gearman"))
{
  $gm_host = ! empty ( $_in["gearman"]["hostname"]) ? $_in["gearman"]["hostname"] : "127.0.0.1";
  $gm_port = ! empty ( $_in["gearman"]["port"]) ? $_in["gearman"]["port"] : 4730;
  try
  {
    $_in["gearman_worker"] = new GearmanWorker ();
    $_in["gearman_worker"]->addServer ( $gm_host, $gm_port);
    $_in["gearman_worker"]->addOptions ( GEARMAN_WORKER_NON_BLOCKING);
    $_in["gearman_worker"]->setTimeout ( 0);

    $serverid = ! empty ( $_in["general"]["serverid"]) ? $_in["general"]["serverid"] : "0";
    $_in["gearman_worker"]->addFunction ( "vd_fastagi_" . $serverid . "_task", "fastagi_gearman_task_handler");
    writeLog ( "Gearman worker registered: vd_fastagi_" . $serverid . "_task");
  } catch ( Exception $e) {
    writeLog ( "Gearman worker init failed: " . $e->getMessage (), VoIP_LOG_WARNING);
    $_in["gearman_worker"] = null;
  }
}

/**
 * Gearman task handler for FastAGI plugins
 */
function fastagi_gearman_task_handler ( $job)
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
 * Instantiate client array
 */
$_agi["clients"] = array ( "0" => array ( "socket" => $socket));

/**
 * Start infinite loop to process connections
 */
while ( true)
{
  $read = array ();
  $read[0] = $socket;

  for ( $i = 1; $i <= count ( $_agi["clients"]); ++$i)
  {
    if ( $_agi["clients"][$i] != NULL)
    {
      $read[$i + 1] = $_agi["clients"][$i]["socket"];
    }
  }

  $write = NULL;
  $except = NULL;
  $tv_sec = $_in["gearman_worker"] !== null ? 0 : NULL;
  $tv_usec = $_in["gearman_worker"] !== null ? 100000 : NULL;
  $ready = socket_select ( $read, $write, $except, $tv_sec, $tv_usec);

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

  if ( in_array ( $socket, $read))
  {
    for ( $i = 1; $i <= $_in["daemon"]["max_clients"]; ++$i)
    {
      if ( ! isset ( $_agi["clients"][$i]))
      {
        $_agi["clients"][$i]["socket"] = socket_accept ( $socket);

        socket_getpeername ( $_agi["clients"][$i]["socket"], $ip, $port);

        $_agi["clients"][$i]["ip"] = $ip;
        $_agi["clients"][$i]["port"] = $port;
        $_agi["clients"][$i]["vars"] = array ();
        $_agi["clients"][$i]["headers"] = true;
        $_agi["clients"][$i]["buffer"] = array ();
        $_agi["clients"][$i]["last"] = "";

        writeLog ( "New client connected: " . $_agi["clients"][$i]["ip"] . ":" . $_agi["clients"][$i]["port"]);
        break;
      } else {
        if ( $i == $_in["daemon"]["max_clients"] - 1)
        {
          writeLog ( "Too many Clients connected!", VoIP_LOG_WARNING);
        }
      }

      if ( $ready < 1)
      {
        continue;
      }
    }
  }

  for ( $i = 1; $i <= $_in["daemon"]["max_clients"]; ++$i)
  {
    if ( in_array ( $_agi["clients"][$i]["socket"], $read))
    {
      $data = @socket_read ( $_agi["clients"][$i]["socket"], 65535, PHP_NORMAL_READ);

      if ( $data === FALSE)
      {
        writeLog ( "Client " . $i . " (" . $_agi["clients"][$i]["ip"] . ":" . $_agi["clients"][$i]["port"] . ") disconnected!", VoIP_LOG_WARNING);
        unset ( $_agi["clients"][$i]);
        continue;
      }

      $data = trim ( $data);

      if ( empty ( $data))
      {
        if ( $_agi["clients"][$i]["headers"])
        {
          $_agi["clients"][$i]["headers"] = false;

          $args = "";
          $x = 0;
          while ( true)
          {
            $x++;
            if ( ! array_key_exists ( "arg_" . $x, $_agi["clients"][$i]["vars"]))
            {
              break;
            }
            $args .= ", argument " . $x . " as \"" . $_agi["clients"][$i]["vars"]["arg_" . $x] . "\"";
          }
          writeLog ( "Client " . $i . " (" . $_agi["clients"][$i]["ip"] . ":" . $_agi["clients"][$i]["port"] . ") requested application \"" . $_agi["clients"][$i]["vars"]["network_script"] . "\" with " . substr ( $args, 2));

          // Check for requested application:
          if ( array_key_exists ( $_agi["clients"][$i]["vars"]["network_script"], $_agi["applications"]))
          {
            try
            {
              $_agi["clients"][$i]["buffer"] = array_merge_recursive ( $_agi["clients"][$i]["buffer"], framework_call ( $_agi["applications"][$_agi["clients"][$i]["vars"]["network_script"]]["hook"], array_merge_recursive ( $_agi["clients"][$i]["vars"], array ( "AGI_Client" => "Client " . $i . " (" . $_agi["clients"][$i]["ip"] . ":" . $_agi["clients"][$i]["port"] . ") "))));
              $_agi["clients"][$i]["buffer"] = array_merge_recursive ( $_agi["clients"][$i]["buffer"], array ( "NOOP"));
            } catch ( Throwable $e)
            {
              writeLog ( "AGI application \"" . $_agi["clients"][$i]["vars"]["network_script"] . "\" threw " . get_class ( $e) . ": " . $e->getMessage () . " in " . $e->getFile () . ":" . $e->getLine (), VoIP_LOG_WARNING);
              $trace_lines = explode ( "\n", $e->getTraceAsString ());
              foreach ( array_slice ( $trace_lines, 0, 5) as $trace_line)
              {
                writeLog ( "  " . $trace_line, VoIP_LOG_WARNING);
              }
              $_agi["clients"][$i]["buffer"] = array ( "NOOP");
            }
          } else {
            writeLog ( "Invalid application \"" . $_agi["clients"][$i]["vars"]["network_script"] . "\" requested from client " . $i . " (" . $_agi["clients"][$i]["ip"] . ":" . $_agi["clients"][$i]["port"] . "). Closing connection.", VoIP_LOG_WARNING);
            socket_close ( $_agi["clients"][$i]["socket"]);
            unset ( $_agi["clients"][$i]);
          }
        }
      } else {
        if ( $_agi["clients"][$i]["headers"] && preg_match ( "/^agi_(.*): (.*)$/", $data))
        {
          $_agi["clients"][$i]["vars"][substr ( $data, 4, strpos ( $data, ":") - 4)] = substr ( $data, strpos ( $data, " ") + 1);
          break;
        }
        if ( $data == "HANGUP" || ( substr ( $data, 0, 4) == "200 " && sizeof ( $_agi["clients"][$i]["buffer"]) == 0))
        {
          socket_close ( $_agi["clients"][$i]["socket"]);
          writeLog ( "Client " . $i . " (" . $_agi["clients"][$i]["ip"] . ":" . $_agi["clients"][$i]["port"] . ") is exiting.");
          unset ( $_agi["clients"][$i]);
          continue;
        }
      }

      if ( sizeof ( $_agi["clients"][$i]["buffer"]) != 0)
      {
        $output = array_shift ( $_agi["clients"][$i]["buffer"]) . "\n";
        $_agi["clients"][$i]["last"] = $output;
        socket_write ( $_agi["clients"][$i]["socket"], $output);
      }
    }
  }
}
?>
