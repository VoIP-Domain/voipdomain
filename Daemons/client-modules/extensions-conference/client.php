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
 * VoIP Domain extension conferences actions module. This module add the Asterisk
 * client actions calls related to extension conferences.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Conferences
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call hooks
 */
framework_add_hook ( "extension_conference_add", "extension_conference_add");
framework_add_hook ( "extension_conference_change", "extension_conference_change");
framework_add_hook ( "extension_conference_remove", "extension_conference_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "extension_conference_wipe", "extension_conference_wipe");
cleanup_register ( "Extensions-Conference", "extension_conference_wipe", array ( "Before" => array ( "Extensions")));

/**
 * Function to create a new extension associated with a conference.
 * Required parameters are: (int) Number, (string) Conference, (string) Admin
 * Possible results:
 *   - 200: OK, extension conference created (overwritten)
 *   - 201: OK, extension conference created
 *   - 400: Number already in use
 *   - 404: Conference not found
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function extension_conference_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_conference_add_start"))
  {
    $parameters = framework_call ( "extension_conference_add_start", $parameters);
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
  if ( ! array_key_exists ( "Conference", $parameters))
  {
    $data["Conference"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Conference", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Conference"]))
  {
    $data["Conference"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Admin", $parameters))
  {
    $data["Admin"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Admin", $data) && ! in_array ( $parameters["Admin"], array ( "Y", "N")))
  {
    $data["Admin"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_conference_add_validate"))
  {
    $data = framework_call ( "extension_conference_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_conference_add_sanitize"))
  {
    $parameters = framework_call ( "extension_conference_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_conference_add_pre"))
  {
    $parameters = framework_call ( "extension_conference_add_pre", $parameters, false, $parameters);
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

  // Verify if conference exist
  if ( ! check_config ( "config", "confbridge-" . $parameters["Conference"]))
  {
    return array ( "code" => 404, "message" => "Conference not found.");
  }

  // Create file structure
  $conference = "exten => " . $parameters["Number"] . ",1,Verbose(VoIP Domain extension " . $parameters["Number"] . " - Conference " . $parameters["Conference"] . ")\n" .
                " same => n,Set(CDR(userfield)=conference)\n" .
                " same => n,ConfBridge(" . $parameters["Conference"] . ",vd_conf_" . $parameters["Conference"] . ",vd_conf_" . $parameters["Conference"] . "_" . ( $parameters["Admin"] == "Y" ? "admin" : "user") . ")\n";

  // Write extension conference file
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["Number"], $conference))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension conference data file
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], array ( "Number" => $parameters["Number"], "Conference" => $parameters["Conference"], "ConferenceName" => $parameters["Conference"], "Admin" => $parameters["Admin"], "Type" => "conference")))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_conference_add_post") && ! framework_call ( "extension_conference_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_conference_add_finish"))
  {
    framework_call ( "extension_conference_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Extension conference created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Extension conference created.");
  }
}

/**
 * Function to change an existing extension associated with a conference.
 * Required parameters are: (int) Number, (int) NewNumber, (string) Conference,
 *                          (string) Admin
 * Possible results:
 *   - 200: OK, extension conference changed
 *   - 400: Number doesn't exist
 *   - 404: Conference not found
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
function extension_conference_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_conference_change_start"))
  {
    $parameters = framework_call ( "extension_conference_change_start", $parameters);
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
  if ( ! array_key_exists ( "Conference", $parameters))
  {
    $data["Conference"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Conference", $data) && ! preg_match ( "/^[a-z0-9\-\.]+$/", $parameters["Conference"]))
  {
    $data["Conference"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Admin", $parameters))
  {
    $data["Admin"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Admin", $data) && ! in_array ( $parameters["Admin"], array ( "Y", "N")))
  {
    $data["Admin"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_conference_change_validate"))
  {
    $data = framework_call ( "extension_conference_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_conference_change_sanitize"))
  {
    $parameters = framework_call ( "extension_conference_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_conference_change_pre"))
  {
    $parameters = framework_call ( "extension_conference_change_pre", $parameters, false, $parameters);
  }

  // Verify if extension exist
  if ( ! check_config ( "config", "dialplan-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 400, "message" => "Number doesn't exist.");
  }

  // Verify if new extension exists
  if ( $parameters["Number"] != $parameters["NewNumber"] && check_config ( "config", "dialplan-extension-" . $parameters["NewNumber"]))
  {
    return array ( "code" => 409, "message" => "New number already exists.");
  }

  // Verify if conference exist
  if ( ! check_config ( "config", "confbridge-" . $parameters["Conference"]))
  {
    return array ( "code" => 404, "message" => "Conference not found.");
  }

  // Remove extension conference files
  if ( ! unlink_config ( "config", "dialplan-extension-" . $parameters["Number"]) || ! unlink_config ( "datafile", "extension-" . $parameters["Number"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $conference = "exten => " . $parameters["NewNumber"] . ",1,Verbose(VoIP Domain extension " . $parameters["NewNumber"] . " - Conference " . $parameters["Conference"] . ")\n" .
                " same => n,Set(CDR(userfield)=conference)\n" .
                " same => n,ConfBridge(" . $parameters["Conference"] . ",vd_conf_" . $parameters["Conference"] . ",vd_conf_" . $parameters["Conference"] . "_" . ( $parameters["Admin"] == "Y" ? "admin" : "user") . ")\n";

  // Write extension conference file
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["NewNumber"], $conference))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension conference data file
  $parameters["Number"] = $parameters["NewNumber"];
  unset ( $parameters["NewNumber"]);
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], array ( "Number" => $parameters["Number"], "Conference" => $parameters["Conference"], "ConferenceName" => $parameters["Conference"], "Admin" => $parameters["Admin"], "Type" => "conference")))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_conference_change_post") && ! framework_call ( "extension_conference_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_conference_change_finish"))
  {
    framework_call ( "extension_conference_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Extension conference changed.");
}

/**
 * Function to remove an existing conference association.
 * Required parameters are: (int) Number
 * Possible results:
 *   - 200: OK, conference removed
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
function extension_conference_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_conference_remove_start"))
  {
    $parameters = framework_call ( "extension_conference_remove_start", $parameters);
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
  if ( framework_has_hook ( "extension_conference_remove_validate"))
  {
    $data = framework_call ( "extension_conference_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_conference_remove_sanitize"))
  {
    $parameters = framework_call ( "extension_conference_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_conference_remove_pre"))
  {
    $parameters = framework_call ( "extension_conference_remove_pre", $parameters, false, $parameters);
  }

  // Verify if extension conference exist
  if ( ! check_config ( "config", "dialplan-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 400, "message" => "Number doesn't exist.");
  }

  // Remove extension conference files
  if ( ! unlink_config ( "config", "dialplan-extension-" . $parameters["Number"]) || ! unlink_config ( "datafile", "extension-" . $parameters["Number"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_conference_remove_post") && ! framework_call ( "extension_conference_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_conference_remove_finish"))
  {
    framework_call ( "extension_conference_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Extension conference removed.");
}

/**
 * Function to remove all existing conference extension configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function extension_conference_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_conference_wipe_start"))
  {
    $parameters = framework_call ( "extension_conference_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_conference_wipe_pre"))
  {
    framework_call ( "extension_conference_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "extension") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( $data["Type"] == "conference")
    {
      $entryid = (int) substr ( $filename, strrpos ( $filename, "-") + 1);
      unlink_config ( "config", "dialplan-extension-" . $entryid);
      unlink_config ( "datafile", $filename);
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_conference_wipe_post"))
  {
    framework_call ( "extension_conference_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_conference_wipe_finish"))
  {
    framework_call ( "extension_conference_wipe_finish", $parameters);
  }
}
?>
