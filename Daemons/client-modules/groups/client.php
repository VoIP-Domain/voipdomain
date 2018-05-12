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
 * VoIP Domain groups actions module. This module add the Asterisk client actions
 * calls related to groups.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Groups
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "group_add", "group_add");
framework_add_hook ( "group_change", "group_change");
framework_add_hook ( "group_remove", "group_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "group_wipe", "group_wipe");
cleanup_register ( "Groups", "group_wipe");

/**
 * Function to create a new group.
 * Required parameters are: (int) ID, (int) Profile, (int) CostCenter
 * Possible results:
 *   - 200: OK, group created (overwrite)
 *   - 201: OK, group created
 *   - 400: Group already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function group_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "group_add_start"))
  {
    $parameters = framework_call ( "group_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters))
  {
    $data["ID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "ID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["ID"]))
  {
    $data["ID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Profile", $parameters))
  {
    $data["Profile"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Profile", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Profile"]))
  {
    $data["Profile"] = "Invalid content";
  }
  if ( ! array_key_exists ( "CostCenter", $parameters))
  {
    $data["CostCenter"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "CostCenter", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["CostCenter"]))
  {
    $data["CostCenter"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "group_add_validate"))
  {
    $data = framework_call ( "group_add_validate", $parameters);
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
  if ( framework_has_hook ( "group_add_sanitize"))
  {
    $parameters = framework_call ( "group_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "group_add_pre"))
  {
    $parameters = framework_call ( "group_add_pre", $parameters, false, $parameters);
  }

  // Verify if group exist
  if ( check_config ( "config", "sip-group-" . $parameters["ID"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Group already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Create file structure
  $group = "[vd_group_" . $parameters["ID"] . "](vd_group_default,!)\n" .
           "context=VoIPDomain-Profile-" . $parameters["Profile"] . "\n" .
           "named_call_group=" . $parameters["ID"] . "\n" .
           "named_pickup_group=" . $parameters["ID"] . "\n" .
           "accountcode=" . $parameters["CostCenter"] . "\n";

  // Write group file
  if ( ! write_config ( "config", "sip-group-" . $parameters["ID"], $group))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Write dialplan database file
  if ( ! write_config ( "configdb", "group-" . $parameters["ID"], array ( "name" => "group_" . $parameters["ID"], "data" => array ( "profile" => $parameters["Profile"], "costcenter" => $parameters["CostCenter"]))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create group data file
  if ( ! write_config ( "datafile", "group-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "group_add_post") && ! framework_call ( "group_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP and dialplan configurations
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "group_add_finish"))
  {
    framework_call ( "group_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Group created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Group created.");
  }
}

/**
 * Function to change an existing group.
 * Required parameters are: (int) ID, (int) Profile, (int) CostCenter
 * Possible results:
 *   - 200: OK, group changed
 *   - 404: Group doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function group_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "group_change_start"))
  {
    $parameters = framework_call ( "group_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters))
  {
    $data["ID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "ID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["ID"]))
  {
    $data["ID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Profile", $parameters))
  {
    $data["Profile"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Profile", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Profile"]))
  {
    $data["Profile"] = "Invalid content";
  }
  if ( ! array_key_exists ( "CostCenter", $parameters))
  {
    $data["CostCenter"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "CostCenter", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["CostCenter"]))
  {
    $data["CostCenter"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "group_change_validate"))
  {
    $data = framework_call ( "group_change_validate", $parameters);
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
  if ( framework_has_hook ( "group_change_sanitize"))
  {
    $parameters = framework_call ( "group_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "group_change_pre"))
  {
    $parameters = framework_call ( "group_change_pre", $parameters, false, $parameters);
  }

  // Verify if group exist
  if ( ! check_config ( "config", "sip-group-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Group doesn't exist.");
  }

  // Remove group files
  if ( ! unlink_config ( "config", "sip-group-" . $parameters["ID"]) || ! unlink_config ( "configdb", "group-" . $parameters["ID"]) || ! unlink_config ( "datafile", "group-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $group = "[vd_group_" . $parameters["ID"] . "](vd_group_default,!)\n" .
           "context=VoIPDomain-Profile-" . $parameters["Profile"] . "\n" .
           "named_call_group=" . $parameters["ID"] . "\n" .
           "named_pickup_group=" . $parameters["ID"] . "\n" .
           "accountcode=" . $parameters["CostCenter"] . "\n";

  // Write group file
  if ( ! write_config ( "config", "sip-group-" . $parameters["ID"], $group))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Write dialplan database file
  if ( ! write_config ( "configdb", "group-" . $parameters["ID"], array ( "name" => "group_" . $parameters["ID"], "data" => array ( "profile" => $parameters["Profile"], "costcenter" => $parameters["CostCenter"]))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create group data file
  if ( ! write_config ( "datafile", "group-" . $parameters["ID"], $parameters))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "group_change_post") && ! framework_call ( "group_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP and dialplan configuration
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "group_change_finish"))
  {
    framework_call ( "group_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Group changed.");
}

/**
 * Function to remove an existing group.
 * Required parameters are: (string) ID
 * Possible results:
 *   - 200: OK, group removed
 *   - 404: Group doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function group_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "group_remove_start"))
  {
    $parameters = framework_call ( "group_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters))
  {
    $data["ID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "ID", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["ID"]))
  {
    $data["ID"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "group_remove_validate"))
  {
    $data = framework_call ( "group_remove_validate", $parameters);
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
  if ( framework_has_hook ( "group_remove_sanitize"))
  {
    $parameters = framework_call ( "group_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "group_remove_pre"))
  {
    $parameters = framework_call ( "group_remove_pre", $parameters, false, $parameters);
  }

  // Verify if group exist
  if ( ! check_config ( "config", "sip-group-" . $parameters["ID"]))
  {
    return array ( "code" => 404, "message" => "Group doesn't exist.");
  }

  // Remove group files
  if ( ! unlink_config ( "config", "sip-group-" . $parameters["ID"]) || ! unlink_config ( "configdb", "group-" . $parameters["ID"]) || ! unlink_config ( "datafile", "group-" . $parameters["ID"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "group_remove_post") && ! framework_call ( "group_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP and dialplan configurations
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "group_remove_finish"))
  {
    framework_call ( "group_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Group removed.");
}

/**
 * Function to remove all existing groups configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function group_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "group_wipe_start"))
  {
    $parameters = framework_call ( "group_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "group_wipe_pre"))
  {
    framework_call ( "group_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "group") as $filename)
  {
    $entryid = (int) substr ( $filename, strrpos ( $filename, "-") + 1);
    unlink_config ( "config", "sip-group-" . $entryid);
    unlink_config ( "configdb", "group-" . $entryid);
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "group_wipe_post"))
  {
    framework_call ( "group_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk SIP and dialplan configurations
  asterisk_exec ( "pjsip reload");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "group_wipe_finish"))
  {
    framework_call ( "group_wipe_finish", $parameters);
  }
}
?>
