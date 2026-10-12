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
 * VoIP Domain daemon error handling infrastructure.
 *
 * Provides centralized error, exception, and fatal crash handlers for all
 * daemon processes. Ensures that PHP errors, uncaught exceptions, and fatal
 * crashes are logged clearly before the process terminates.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Map of PHP error constants to human-readable names.
 */
$_error_type_names = array (
  E_ERROR             => "E_ERROR",
  E_WARNING           => "E_WARNING",
  E_PARSE             => "E_PARSE",
  E_NOTICE            => "E_NOTICE",
  E_CORE_ERROR        => "E_CORE_ERROR",
  E_CORE_WARNING      => "E_CORE_WARNING",
  E_COMPILE_ERROR     => "E_COMPILE_ERROR",
  E_COMPILE_WARNING   => "E_COMPILE_WARNING",
  E_USER_ERROR        => "E_USER_ERROR",
  E_USER_WARNING      => "E_USER_WARNING",
  E_USER_NOTICE       => "E_USER_NOTICE",
  E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
  E_DEPRECATED        => "E_DEPRECATED",
  E_USER_DEPRECATED   => "E_USER_DEPRECATED"
);

/**
 * Register all error handling infrastructure for a daemon process.
 *
 * @return void
 */
function daemon_register_error_handlers ()
{
  set_error_handler ( "daemon_error_handler");
  set_exception_handler ( "daemon_exception_handler");
  register_shutdown_function ( "daemon_shutdown_handler");
}

/**
 * Shutdown handler — detects fatal errors that bypass set_error_handler().
 *
 * Called automatically when the PHP process terminates. Checks error_get_last()
 * for fatal error types and logs them before the process exits.
 *
 * @return void
 */
function daemon_shutdown_handler ()
{
  global $_error_type_names;

  $error = error_get_last ();
  if ( $error === null)
  {
    return;
  }

  $fatal_types = array ( E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING);
  if ( ! in_array ( $error["type"], $fatal_types))
  {
    return;
  }

  $type_name = array_key_exists ( $error["type"], $_error_type_names) ? $_error_type_names[$error["type"]] : "UNKNOWN(" . $error["type"] . ")";
  $message = "DAEMON CRASH: " . $error["message"] . " in " . $error["file"] . ":" . $error["line"] . " [" . $type_name . "]";

  if ( function_exists ( "writeLog"))
  {
    writeLog ( $message, VoIP_LOG_FATAL);
  } else {
    error_log ( "voipdomain: " . $message);
  }
}

/**
 * Custom error handler for non-fatal PHP errors.
 *
 * Respects the error_reporting() bitmask (so @ operator still suppresses).
 * Logs warnings/notices/deprecations with file:line context.
 *
 * @param int $errno Error level constant
 * @param string $errstr Error message
 * @param string $errfile File where the error occurred
 * @param int $errline Line number where the error occurred
 * @return boolean True if the error was handled, false to trigger PHP default
 */
function daemon_error_handler ( $errno, $errstr, $errfile, $errline)
{
  global $_error_type_names;

  if ( ! ( error_reporting () & $errno))
  {
    return true;
  }

  $type_name = array_key_exists ( $errno, $_error_type_names) ? $_error_type_names[$errno] : "UNKNOWN(" . $errno . ")";

  switch ( $errno)
  {
    case E_RECOVERABLE_ERROR:
    case E_USER_ERROR:
      $message = "[PHP " . $type_name . "] " . $errstr . " in " . $errfile . ":" . $errline;
      if ( function_exists ( "writeLog"))
      {
        writeLog ( $message, VoIP_LOG_WARNING);
      }
      return false;

    case E_WARNING:
    case E_USER_WARNING:
    case E_CORE_WARNING:
    case E_COMPILE_WARNING:
      $message = "[PHP " . $type_name . "] " . $errstr . " in " . $errfile . ":" . $errline;
      if ( function_exists ( "writeLog"))
      {
        writeLog ( $message, VoIP_LOG_WARNING);
      }
      return true;

    case E_NOTICE:
    case E_USER_NOTICE:
    case E_STRICT:
    case E_DEPRECATED:
    case E_USER_DEPRECATED:
      $message = "[PHP " . $type_name . "] " . $errstr . " in " . $errfile . ":" . $errline;
      if ( function_exists ( "writeLog"))
      {
        writeLog ( $message, VoIP_LOG_DEBUG);
      }
      return true;

    default:
      $message = "[PHP " . $type_name . "] " . $errstr . " in " . $errfile . ":" . $errline;
      if ( function_exists ( "writeLog"))
      {
        writeLog ( $message, VoIP_LOG_WARNING);
      }
      return true;
  }
}

/**
 * Uncaught exception handler.
 *
 * Logs the full exception with class, message, file:line, and stack trace
 * before the process terminates.
 *
 * @param Throwable $exception The uncaught exception or error
 * @return void
 */
function daemon_exception_handler ( $exception)
{
  $class = get_class ( $exception);
  $message = "UNCAUGHT EXCEPTION [" . $class . "]: " . $exception->getMessage () . " in " . $exception->getFile () . ":" . $exception->getLine ();

  if ( function_exists ( "writeLog"))
  {
    writeLog ( $message, VoIP_LOG_WARNING);

    $trace = $exception->getTraceAsString ();
    $trace_lines = explode ( "\n", $trace);
    $max_lines = 10;
    $shown = 0;
    foreach ( $trace_lines as $line)
    {
      if ( $shown >= $max_lines)
      {
        writeLog ( "  ... " . ( sizeof ( $trace_lines) - $max_lines) . " more frames", VoIP_LOG_WARNING);
        break;
      }
      writeLog ( "  " . $line, VoIP_LOG_WARNING);
      $shown++;
    }
  } else {
    error_log ( "voipdomain: " . $message);
    error_log ( "voipdomain: " . $exception->getTraceAsString ());
  }

  exit ( 1);
}
?>
