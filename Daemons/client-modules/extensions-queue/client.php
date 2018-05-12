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
 * VoIP Domain extension queues actions module. This module add the Asterisk
 * client actions calls related to extension queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "extension_queue_add", "extension_queue_add");
framework_add_hook ( "extension_queue_change", "extension_queue_change");
framework_add_hook ( "extension_queue_remove", "extension_queue_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "extension_queue_wipe", "extension_queue_wipe");
cleanup_register ( "Extensions-Queue", "extension_queue_wipe", array ( "Before" => array ( "Extensions")));

/**
 * Function to create a new extension associated to a queue.
 * Required parameters are: (int) Number, (string) Queue
 * Possible results:
 *   - 200: OK, extension queue created (overwritten)
 *   - 201: OK, extension queue created
 *   - 400: Number already in use
 *   - 404: Queue not found
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function extension_queue_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_queue_add_start"))
  {
    $parameters = framework_call ( "extension_queue_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Queue", $parameters))
  {
    $data["Queue"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Queue", $data) && ! preg_match ( "/^[a-z0-9\-\.]$/", $parameters["Queue"]))
  {
    $data["Queue"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_queue_add_validate"))
  {
    $data = framework_call ( "extension_queue_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_queue_add_sanitize"))
  {
    $parameters = framework_call ( "extension_queue_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_queue_add_pre"))
  {
    $parameters = framework_call ( "extension_queue_add_pre", $parameters, false, $parameters);
  }

  // Verify if extension are in use
  if ( check_config ( "config", "dialplan-extension-" . $parameters["Number"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Number already in use.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Verify if queue exist
  if ( ! check_config ( "config", "queue-" . $parameters["Queue"]))
  {
    return array ( "code" => 404, "message" => "Queue not found.");
  }

  // Create file structure
  $queue = "exten => " . $parameters["Number"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Queue " . $parameters["Queue"] . ")\n" .
           " same => n,Wait(2)\n" .
           " same => n,Queue(\"vd_queue_" . addslashes ( $parameters["Queue"]) . "\")\n";

  // Write extension queue file
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["Number"], $queue))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension queue data file
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], array_merge_recursive ( $parameters, array ( "Type" => "queue"))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_queue_add_post") && ! framework_call ( "extension_queue_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_queue_add_finish"))
  {
    framework_call ( "extension_queue_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Extension queue created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Extension queue created.");
  }
}

/**
 * Function to change an existing extension associated to a queue.
 * Required parameters are: (int) Number, (int) NewNumber, (string) Queue
 * Possible results:
 *   - 200: OK, extension queue changed
 *   - 400: Number doesn't exist
 *   - 404: Queue not found
 *   - 406: Invalid parameters
 *   - 409: New number already exist
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function extension_queue_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_queue_change_start"))
  {
    $parameters = framework_call ( "extension_queue_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NewNumber", $parameters))
  {
    $data["NewNumber"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NewNumber", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["NewNumber"]))
  {
    $data["NewNumber"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Queue", $parameters))
  {
    $data["Queue"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Queue", $data) && ! preg_match ( "/^[a-z0-9\-\.]$/", $parameters["Queue"]))
  {
    $data["Queue"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_queue_change_validate"))
  {
    $data = framework_call ( "extension_queue_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_queue_change_sanitize"))
  {
    $parameters = framework_call ( "extension_queue_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_queue_change_pre"))
  {
    $parameters = framework_call ( "extension_queue_change_pre", $parameters, false, $parameters);
  }

  // Verify if extension exist
  if ( ! check_config ( "config", "dialplan-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 400, "message" => "Number doesn't exist.");
  }

  // Verify if new extension exists
  if ( $parameters["Number"] != $parameters["NewNumber"] && check_config ( "config", "dialplan-extension-" . $parameters["NewNumber"]))
  {
    return array ( "code" => 409, "message" => "New number already exist.");
  }

  // Verify if queue exist
  if ( ! config_check ( "config", "queue-" . $parameters["Queue"]))
  {
    return array ( "code" => 404, "message" => "Queue not found.");
  }

  // Remove extension queue files
  if ( ! unlink_config ( "config", "dialplan-extension-" . $parameters["Number"]) || ! unlink_config ( "datafile", "extension-" . $parameters["Number"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $queue = "exten => " . $parameters["NewNumber"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Queue " . $parameters["Queue"] . ")\n" .
           " same => n,Wait(2)\n" .
           " same => n,Queue(\"vd_queue_" . addslashes ( $parameters["Queue"]) . "\")\n";

  // Write extension queue file
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["NewNumber"], $queue))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension queue data file
  $parameters["Number"] = $parameters["NewNumber"];
  unset ( $parameters["NewNumber"]);
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], array_merge_recursive ( $parameters, array ( "Type" => "queue"))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_queue_change_post") && ! framework_call ( "extension_queue_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_queue_change_finish"))
  {
    framework_call ( "extension_queue_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Queue changed.");
}

/**
 * Function to remove an existing queue association.
 * Required parameters are: (int) Number
 * Possible results:
 *   - 200: OK, queue removed
 *   - 404: Number doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function extension_queue_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_queue_remove_start"))
  {
    $parameters = framework_call ( "extension_queue_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Number", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Number"]))
  {
    $data["Number"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_queue_remove_validate"))
  {
    $data = framework_call ( "extension_queue_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_queue_remove_sanitize"))
  {
    $parameters = framework_call ( "extension_queue_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_queue_remove_pre"))
  {
    $parameters = framework_call ( "extension_queue_remove_pre", $parameters, false, $parameters);
  }

  // Verify if extension queue exist
  if ( ! check_config ( "config", "dialplan-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 400, "message" => "Number doesn't exist.");
  }

  // Remove extension queue files
  if ( ! unlink_config ( "config", "dialplan-extension-" . $parameters["Number"]) || ! unlink_config ( "datafile", "extension-" . $parameters["Number"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_queue_remove_post") && ! framework_call ( "extension_queue_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_queue_remove_finish"))
  {
    framework_call ( "extension_queue_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Extension queue removed.");
}

/**
 * Function to remove all existing queues configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Array with Asterisk commands to be executed later
 */
function extension_queue_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_queue_wipe_start"))
  {
    $parameters = framework_call ( "extension_queue_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_queue_wipe_pre"))
  {
    framework_call ( "extension_queue_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "extension") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( $data["Type"] == "queue")
    {
      $entryid = (int) substr ( $filename, strrpos ( $filename, "-") + 1);
      config_unlink ( "config", "dialplan-extension-" . $entryid);
      config_unlink ( "datafile", $filename);
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_queue_wipe_post"))
  {
    framework_call ( "extension_queue_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_queue_wipe_finish"))
  {
    framework_call ( "extension_queue_wipe_finish", $parameters);
  }
}
?>
