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
 * VoIP Domain queues actions module. This module add the Asterisk client actions
 * calls related to queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "createqueue", "queues_create");
framework_add_hook ( "changequeue", "queues_change");
framework_add_hook ( "removequeue", "queues_remove");
framework_add_hook ( "joinqueue", "queues_join");
framework_add_hook ( "logoutqueue", "queues_logout");
framework_add_hook ( "pausequeue", "queues_pause");
framework_add_hook ( "unpausequeue", "queues_unpause");

/**
 * Function to create a new queue.
 * Required parameters are: Description, Extension
 * Possible results:
 *   - 200: OK, queue created
 *   - 400: Queue already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Description", $parameters) || ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if queue exist
  if ( file_exists ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue already exist.");
  }

  // Create file structure
  $queue = "[queue_" . (int) $parameters["Extension"] . "]\n" .
           "musicclass=default      ; play [default] music\n" .
           "strategy=rrmemory       ; use the Round Robin Memory strategy\n" .
           "joinempty=yes           ; join the queue when no members available\n" .
           "logoutwhenempty=no       ; don't logout the queue no members available\n" .
           "ringinuse=no            ; don't ring members when already InUse\n";

  // Write queue file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Extension"] . ".conf", $queue))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension entry file structure
  $queue = "exten => " . (int) $parameters["Extension"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Queue " . $parameters["Description"] . ")\n" .
           " same => n,Macro(Queue," . (int) $parameters["Extension"] . ")\n";

  // Write queue extension entry file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/queueext-" . (int) $parameters["Extension"] . ".conf", $queue))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Queue created.");
}

/**
 * Function to change an existing queue.
 * Required parameters are: Description, Extension, NewExtension
 * Possible results:
 *   - 200: OK, queue changed
 *   - 400: Queue doesn't exist
 *   - 401: Invalid parameters
 *   - 402: Queue new number already exist
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Description", $parameters) || ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "NewExtension", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if queue exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue doesn't exist.");
  }

  // Verify if new queue exist
  if ( (int) $parameters["Extension"] != (int) $parameters["NewExtension"] && file_exists ( $_in["general"]["confdir"] . "/queueext-" . (int) $parameters["NewExtension"] . ".conf"))
  {
    return array ( "code" => 402, "message" => "Queue new number already exist.");
  }

  // Remove queue file
  if ( ! unlink ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $queue = "[queue_" . (int) $parameters["NewExtension"] . "]\n" .
           "musicclass=default      ; play [default] music\n" .
           "strategy=rrmemory       ; use the Round Robin Memory strategy\n" .
           "joinempty=yes           ; join the queue when no members available\n" .
           "logoutwhenempty=no       ; don't logout the queue no members available\n" .
           "ringinuse=no            ; don't ring members when already InUse\n";

  // Write queue file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["NewExtension"] . ".conf", $queue))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Remove queue extension entry file
  if ( ! unlink ( $_in["general"]["confdir"] . "/queueext-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create extension entry file structure
  $queue = "exten => " . (int) $parameters["NewExtension"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Queue " . $parameters["Description"] . ")\n" .
           " same => n,Macro(Queue," . (int) $parameters["Extension"] . ")\n";

  // Write queue extension entry file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/queueext-" . (int) $parameters["NewExtension"] . ".conf", $queue))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Queue changed.");
}

/**
 * Function to remove an existing queue.
 * Required parameters are: Extension
 * Possible results:
 *   - 200: OK, queue removed
 *   - 400: Queue doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if queue exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue doesn't exist.");
  }

  // Remove queue file
  if ( ! unlink ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Verify if queue extension entry exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/queueext-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue doesn't exist.");
  }

  // Remove queue extension entry file
  if ( ! unlink ( $_in["general"]["confdir"] . "/queueext-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Queue removed.");
}

/**
 * Function to add a member to a queue.
 * Required parameters are: Queue, Extension
 * Possible results:
 *   - 200: OK, member added to queue
 *   - 400: Queue doesn't exist
 *   - 401: Extension doesn't exist
 *   - 402: Invalid parameters
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_join ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Queue", $parameters) || ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 402, "message" => "Invalid parameters.");
  }

  // Verify if queue exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Queue"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue doesn't exist.");
  }

  // Verify if extension exist
  $extensions = array ();
  for ( $x = 0; $x < 10; $x++)
  {
    if ( file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . $x . ".conf"))
    {
      $extensions[] = $x;
      break;
    }
  }
  if ( sizeof ( $extensions) == 0)
  {
    return array ( "code" => 402, "message" => "Extension doesn't exist.");
  }

  // Add member extensions to the queue
  foreach ( $extensions as $extension)
  {
    asterisk_exec ( "queue add member SIP/u" . (int) $parameters["Extension"] . "-" . $extension . " to queue_" . (int) $parameters["Queue"]);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Member added to queue.");
}

/**
 * Function to remove a member from a queue.
 * Required parameters are: Queue, Extension
 * Possible results:
 *   - 200: OK, member removed from queue
 *   - 400: Queue doesn't exist
 *   - 401: Extension doesn't exist
 *   - 402: Invalid parameters
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_logout ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Queue", $parameters) || ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 402, "message" => "Invalid parameters.");
  }

  // Verify if queue exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Queue"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue doesn't exist.");
  }

  // Verify if extension exist
  $extensions = array ();
  for ( $x = 0; $x < 10; $x++)
  {
    if ( file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . $x . ".conf"))
    {
      $extensions[] = $x;
      break;
    }
  }
  if ( sizeof ( $extensions) == 0)
  {
    return array ( "code" => 402, "message" => "Extension doesn't exist.");
  }

  // Add member extensions to the queue
  foreach ( $extensions as $extension)
  {
    asterisk_exec ( "queue remove member SIP/u" . (int) $parameters["Extension"] . "-" . $extension . " from queue_" . (int) $parameters["Queue"]);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Member removed from queue.");
}

/**
 * Function to pause a member from a queue.
 * Required parameters are: Queue, Extension
 * Possible results:
 *   - 200: OK, member paused from queue
 *   - 400: Queue doesn't exist
 *   - 401: Extension doesn't exist
 *   - 402: Invalid parameters
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_pause ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Queue", $parameters) || ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 402, "message" => "Invalid parameters.");
  }

  // Verify if queue exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Queue"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue doesn't exist.");
  }

  // Verify if extension exist
  $extensions = array ();
  for ( $x = 0; $x < 10; $x++)
  {
    if ( file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . $x . ".conf"))
    {
      $extensions[] = $x;
      break;
    }
  }
  if ( sizeof ( $extensions) == 0)
  {
    return array ( "code" => 402, "message" => "Extension doesn't exist.");
  }

  // Add member extensions to the queue
  foreach ( $extensions as $extension)
  {
    asterisk_exec ( "queue pause member SIP/u" . (int) $parameters["Extension"] . "-" . $extension . " queue queue_" . (int) $parameters["Queue"]);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Member paused from queue.");
}

/**
 * Function to unpause a member from a queue.
 * Required parameters are: Queue, Extension
 * Possible results:
 *   - 200: OK, member unpaused from queue
 *   - 400: Queue doesn't exist
 *   - 401: Extension doesn't exist
 *   - 402: Invalid parameters
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_unpause ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Queue", $parameters) || ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 402, "message" => "Invalid parameters.");
  }

  // Verify if queue exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/queue-" . (int) $parameters["Queue"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Queue doesn't exist.");
  }

  // Verify if extension exist
  $extensions = array ();
  for ( $x = 0; $x < 10; $x++)
  {
    if ( file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . $x . ".conf"))
    {
      $extensions[] = $x;
      break;
    }
  }
  if ( sizeof ( $extensions) == 0)
  {
    return array ( "code" => 402, "message" => "Extension doesn't exist.");
  }

  // Add member extensions to the queue
  foreach ( $extensions as $extension)
  {
    asterisk_exec ( "queue unpause member SIP/u" . (int) $parameters["Extension"] . "-" . $extension . " queue queue_" . (int) $parameters["Queue"]);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Member unpaused from queue.");
}
?>
