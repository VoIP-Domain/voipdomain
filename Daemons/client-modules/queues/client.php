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
 * VoIP Domain queues actions module. This module add the Asterisk client actions
 * calls related to queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "queue_add", "queue_add");
framework_add_hook ( "queue_change", "queue_change");
framework_add_hook ( "queue_remove", "queue_remove");
framework_add_hook ( "queue_join_member", "queue_join_member");
framework_add_hook ( "queue_leave_member", "queue_leave_member");
framework_add_hook ( "queue_pause_member", "queue_pause_member");
framework_add_hook ( "queue_resume_member", "queue_resume_member");

/**
 * Cleanup functions
 */
framework_add_hook ( "queue_wipe", "queue_wipe");
cleanup_register ( "Queues", "queue_wipe");

/**
 * Function to create a new queue.
 * Required parameters are: (string) Name, (string) Description, (enum['ringall','roundrobin','leastrecent','fewestcalls','random','rrmemory']) Strategy[]

 * Possible results:
 *   - 200: OK, queue created (overwritten)
 *   - 201: OK, queue created
 *   - 400: Queue already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function queue_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queue_add_start"))
  {
    $parameters = framework_call ( "queue_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Strategy", $parameters))
  {
    $data["Strategy"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Strategy", $data) && ! in_array ( $parameters["Strategy"], array ( "ringall", "roundrobin", "leastrecent", "fewestcalls", "random", "rrmemory")))
  {
    $data["Strategy"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queue_add_validate"))
  {
    $data = framework_call ( "queue_add_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queue_add_sanitize"))
  {
    $parameters = framework_call ( "queue_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queue_add_pre"))
  {
    $parameters = framework_call ( "queue_add_pre", $parameters, false, $parameters);
  }

  // Verify if queue exist
  if ( check_config ( "config", "queue-" . $parameters["Name"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Queue already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Create file structure
  $queue = "# VoIP Domain - Queue " . $parameters["Name"] . " - " . $parameters["Description"] . "\n" .
           "[vd_queue_" . $parameters["Name"] . "]\n" .
           "musicclass=default\n" .
           "strategy=" . $parameters["Strategy"] . "\n" .
           "joinempty=yes\n" .
           "ringinuse=no\n";

  // Write queue file
  if ( ! write_config ( "config", "queue-" . $parameters["Name"], $queue))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create queue data file
  if ( ! write_config ( "datafile", "queue-" . $parameters["Name"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queue_add_post") && ! framework_call ( "queue_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk queue module
  asterisk_exec ( "module reload app_queue.so");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queue_add_finish"))
  {
    framework_call ( "queue_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Queue created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Queue created.");
  }
}

/**
 * Function to change an existing queue.
 * Required parameters are: (string) Name, (string) OldName, (string) Description, (enum['ringall','roundrobin','leastrecent','fewestcalls','random','rrmemory']) Strategy[]
 * Possible results:
 *   - 200: OK, queue changed
 *   - 404: Queue doesn't exist
 *   - 406: Invalid parameters
 *   - 409: Queue new name already exist
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function queue_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queue_change_start"))
  {
    $parameters = framework_call ( "queue_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "OldName", $parameters))
  {
    $data["OldName"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "OldName", $data) && ! preg_match ( "/^[a-z0-9\-\.]$/", $parameters["OldName"]))
  {
    $data["OldName"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Strategy", $parameters))
  {
    $data["Strategy"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Strategy", $data) && ! in_array ( $parameters["Strategy"], array ( "ringall", "roundrobin", "leastrecent", "fewestcalls", "random", "rrmemory")))
  {
    $data["Strategy"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queue_change_validate"))
  {
    $data = framework_call ( "queue_change_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queue_change_sanitize"))
  {
    $parameters = framework_call ( "queue_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queue_change_pre"))
  {
    $parameters = framework_call ( "queue_change_pre", $parameters, false, $parameters);
  }

  // Verify if queue exist
  if ( ! check_config ( "config", "queue-" . $parameters["OldName"]))
  {
    return array ( "code" => 404, "message" => "Queue doesn't exist.");
  }

  // Verify if new queue exist
  if ( $parameters["OldName"] != $parameters["Name"] && config_check ( "config", "queue-" . $parameters["Name"]))
  {
    return array ( "code" => 402, "message" => "New queue already exist.");
  }

  // Remove queue files
  if ( ! unlink_config ( "config", "queue-" . $parameters["OldName"]) || ! remove_config ( "datafile", "queue-" . $parameters["OldName"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $queue = "# VoIP Domain - Queue " . $parameters["Name"] . " - " . $parameters["Description"] . "\n" .
           "[vd_queue_" . $parameters["Name"] . "]\n" .
           "musicclass=default\n" .
           "strategy=" . $parameters["Strategy"] . "\n" .
           "joinempty=yes\n" .
           "ringinuse=no\n";

  // Write queue file
  if ( ! write_config ( "config", "queue-" . $parameters["Name"], $queue))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create queue data file
  $parameters["Name"] = $parameters["NewName"];
  unset ( $parameters["NewName"]);
  if ( ! write_config ( "datafile", "queue-" . $parameters["Name"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queue_change_post") && ! framework_call ( "queue_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk queue module
  asterisk_exec ( "module reload app_queue.so");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queue_change_finish"))
  {
    framework_call ( "queue_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Queue changed.");
}

/**
 * Function to remove an existing queue.
 * Required parameters are: (string) Name
 * Possible results:
 *   - 200: OK, queue removed
 *   - 404: Queue doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function queue_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queue_remove_start"))
  {
    $parameters = framework_call ( "queue_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queue_remove_validate"))
  {
    $data = framework_call ( "queue_remove_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queue_remove_sanitize"))
  {
    $parameters = framework_call ( "queue_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queue_remove_pre"))
  {
    $parameters = framework_call ( "queue_remove_pre", $parameters, false, $parameters);
  }

  // Verify if queue exist
  if ( ! check_config ( "config", "queue-" . $parameters["Name"]))
  {
    return array ( "code" => 404, "message" => "Queue doesn't exist.");
  }

  // Remove queue files
  if ( ! unlink_config ( "config", "queue-" . $parameters["Name"]) || ! unlink_config ( "datafile", "queue-" . $parameters["Name"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queue_remove_post") && ! framework_call ( "queue_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queue_remove_finish"))
  {
    framework_call ( "queue_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Queue removed.");
}

/**
 * Function to join a member into a queue.
 * Required parameters are: (string) Name, (string) Member
 * Possible results:
 *   - 200: OK, member joined to queue
 *   - 404: Queue doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error joining member
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function queue_join_member ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queue_join_member_start"))
  {
    $parameters = framework_call ( "queue_join_member_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Member", $parameters))
  {
    $data["Member"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Member", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Member"]))
  {
    $data["Member"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queue_join_member_validate"))
  {
    $data = framework_call ( "queue_join_member_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queue_join_member_sanitize"))
  {
    $parameters = framework_call ( "queue_join_member_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queue_join_member_pre"))
  {
    $parameters = framework_call ( "queue_join_member_pre", $parameters, false, $parameters);
  }

  // Verify if queue exist
  if ( ! check_config ( "config", "queue-" . $parameters["Name"]))
  {
    return array ( "code" => 404, "message" => "Queue doesn't exist.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queue_join_member_post") && ! framework_call ( "queue_join_member_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Add member to the queue
  asterisk_exec ( "queue add member " . $parameters["Member"] . " to vd_queue_" . $parameters["Name"]);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queue_join_member_finish"))
  {
    framework_call ( "queue_join_member_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Member joined to queue.");
}

/**
 * Function to leave a member from a queue.
 * Required parameters are: (string) Name, (string) Member
 * Possible results:
 *   - 200: OK, member leaved queue
 *   - 404: Queue doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing member
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function queue_leave_member ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queue_leave_member_start"))
  {
    $parameters = framework_call ( "queue_leave_member_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Member", $parameters))
  {
    $data["Member"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Member", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Member"]))
  {
    $data["Member"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queue_leave_member_validate"))
  {
    $data = framework_call ( "queue_leave_member_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queue_leave_member_sanitize"))
  {
    $parameters = framework_call ( "queue_leave_member_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queue_leave_member_pre"))
  {
    $parameters = framework_call ( "queue_leave_member_pre", $parameters, false, $parameters);
  }

  // Verify if queue exist
  if ( ! check_config ( "config", "queue-" . $parameters["Name"]))
  {
    return array ( "code" => 404, "message" => "Queue doesn't exist.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queue_leave_member_post") && ! framework_call ( "queue_leave_member_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Remove member from the queue
  asterisk_exec ( "queue remove member " . $parameters["Member"] . " to vd_queue_" . $parameters["Name"]);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queue_leave_member_finish"))
  {
    framework_call ( "queue_leave_member_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Member leaved queue.");
}

/**
 * Function to pause a member into a queue.
 * Required parameters are: (string) Name, (string) Member
 * Possible results:
 *   - 200: OK, member paused from queue
 *   - 404: Queue doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error pausing member
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function queue_pause_member ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queue_pause_member_start"))
  {
    $parameters = framework_call ( "queue_pause_member_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Member", $parameters))
  {
    $data["Member"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Member", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Member"]))
  {
    $data["Member"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queue_pause_member_validate"))
  {
    $data = framework_call ( "queue_pause_member_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queue_pause_member_sanitize"))
  {
    $parameters = framework_call ( "queue_pause_member_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queue_pause_member_pre"))
  {
    $parameters = framework_call ( "queue_pause_member_pre", $parameters, false, $parameters);
  }

  // Verify if queue exist
  if ( ! check_config ( "config", "queue-" . $parameters["Name"]))
  {
    return array ( "code" => 404, "message" => "Queue doesn't exist.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queue_pause_member_post") && ! framework_call ( "queue_pause_member_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Pause member into queue
  asterisk_exec ( "queue pause member " . $parameters["Member"] . " queue vd_queue_" . $parameters["Queue"]);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queue_pause_member_finish"))
  {
    framework_call ( "queue_pause_member_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Member paused from queue.");
}

/**
 * Function to resume a member into a queue.
 * Required parameters are: (string) Name, (string) Member
 * Possible results:
 *   - 200: OK, member unpaused from queue
 *   - 404: Queue doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error resuming member.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function queue_resume_member ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queue_resume_member_start"))
  {
    $parameters = framework_call ( "queue_resume_member_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[a-z0-9\-\.]$/", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Member", $parameters))
  {
    $data["Member"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Member", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Member"]))
  {
    $data["Member"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queue_resume_member_validate"))
  {
    $data = framework_call ( "queue_resume_member_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queue_resume_member_sanitize"))
  {
    $parameters = framework_call ( "queue_resume_member_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queue_resume_member_pre"))
  {
    $parameters = framework_call ( "queue_resume_member_pre", $parameters, false, $parameters);
  }

  // Verify if queue exist
  if ( ! check_config ( "config", "queue-" . $parameters["Name"]))
  {
    return array ( "code" => 404, "message" => "Queue doesn't exist.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queue_resume_member_post") && ! framework_call ( "queue_resume_member_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Unpause member from queue
  asterisk_exec ( "queue unpause member " . $parameters["Member"] . " queue vd_queue_" . $parameters["Queue"]);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queue_resume_member_finish"))
  {
    framework_call ( "queue_resume_member_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Member unpaused from queue.");
}

/**
 * Function to remove all existing queues configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function queue_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queue_wipe_start"))
  {
    $parameters = framework_call ( "queue_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queue_wipe_pre"))
  {
    framework_call ( "queue_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "queue") as $filename)
  {
    unlink_config ( "config", "queue-" . (int) substr ( $filename, strrpos ( $filename, "-") + 1));
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queue_wipe_post"))
  {
    framework_call ( "queue_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk queue module
  asterisk_exec ( "module reload app_queue.so");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queue_wipe_finish"))
  {
    framework_call ( "queue_wipe_finish", $parameters);
  }
}
?>
