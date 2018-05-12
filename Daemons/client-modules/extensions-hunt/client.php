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
 * VoIP Domain extension hunts actions module. This module add the Asterisk
 * client actions calls related to extension hunts.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Hunts
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "extension_hunt_add", "extension_hunt_add");
framework_add_hook ( "extension_hunt_change", "extension_hunt_change");
framework_add_hook ( "extension_hunt_remove", "extension_hunt_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "extension_hunt_wipe", "extension_hunt_wipe");
cleanup_register ( "Extensions-Hunt", "extension_hunt_wipe", array ( "Before" => array ( "Extensions")));

/**
 * Function to create a new extension hunt.
 * Required parameters are: (int) Number, (string) Description, (array (int)) Extensions[]
 * Possible results:
 *   - 200: OK, extension hunt created (overwritten)
 *   - 201: OK, extension hunt created
 *   - 400: Number already in use
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function extension_hunt_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_add_start"))
  {
    $parameters = framework_call ( "extension_hunt_add_start", $parameters);
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
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Extensions", $data) && ! is_array ( $parameters["Extensions"]))
  {
    $data["Extensions"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Extensions", $data))
  {
    foreach ( $parameters["Extensions"] as $extension)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $extension))
      {
        $data["Extensions"] = "Invalid content";
      }
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_add_validate"))
  {
    $data = framework_call ( "extension_hunt_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_hunt_add_sanitize"))
  {
    $parameters = framework_call ( "extension_hunt_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_add_pre"))
  {
    $parameters = framework_call ( "extension_hunt_add_pre", $parameters, false, $parameters);
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

  // Write extension hunt file
  $dialstring = "";
  foreach ( $parameters["Extensions"] as $extension)
  {
    $dialstring .= "&LOCAL/" . $extension . "@" . fetch_config ( "contexts", "direct");
  }
  $hunt = "exten => " . $parameters["Number"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Hunt: " . $parameters["Description"] . "))\n" .
          " same => n,Set(CDR(userfield)=hunt)\n" .
          " same => n,Dial(" . substr ( $dialstring, 1) . ")\n";
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["Number"], $hunt))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension data file
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], array_merge_recursive ( $parameters, array ( "Type" => "hunt"))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_add_post") && ! framework_call ( "extension_hunt_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_add_finish"))
  {
    framework_call ( "extension_hunt_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Extension hunt created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Extension hunt created.");
  }
}

/**
 * Function to change an existing extension hunt.
 * Required parameters are: (int) Number, (int) NewNumber, (string) Description, (array (int)) Extensions[]
 * Possible results:
 *   - 200: OK, extension hunt changed
 *   - 404: Number doesn't exist
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
function extension_hunt_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_change_start"))
  {
    $parameters = framework_call ( "extension_hunt_change_start", $parameters);
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
  if ( ! array_key_exists ( "Description", $parameters))
  {
    $data["Description"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Description", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Description"]))
  {
    $data["Description"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Extensions", $data) && ! is_array ( $parameters["Extensions"]))
  {
    $data["Extensions"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Extensions", $data))
  {
    foreach ( $parameters["Extensions"] as $extension)
    {
      if ( ! preg_match ( "/^[0-9]+$/", $extension))
      {
        $data["Extensions"] = "Invalid content";
      }
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_change_validate"))
  {
    $data = framework_call ( "extension_hunt_change_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_hunt_change_sanitize"))
  {
    $parameters = framework_call ( "extension_hunt_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_change_pre"))
  {
    $parameters = framework_call ( "extension_hunt_change_pre", $parameters, false, $parameters);
  }

  // Verify if extension hunt exist
  if ( ! check_config ( "config", "dialplan-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 404, "message" => "Number doesn't exist.");
  }

  // Verify if new extension hunt number is in use
  if ( $parameters["Number"] != $parameters["NewNumber"] && check_config ( "config", "dialplan-extension-" . $parameters["NewNumber"]))
  {
    return array ( "code" => 409, "message" => "New number already exist.");
  }

  // Remove extension hunt files
  if ( ! unlink_config ( "config", "dialplan-extension-" . $parameters["Number"]) || ! unlink_config ( "datafile", "extension-" . $parameters["Number"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Write extension hunt file
  $dialstring = "";
  foreach ( $parameters["Extensions"] as $extension)
  {
    $dialstring .= "&LOCAL/" . $extension . "@" . fetch_config ( "contexts", "direct");
  }
  $hunt = "exten => " . $parameters["NewNumber"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Hunt: " . $parameters["Description"] . "))\n" .
          " same => n,Set(CDR(userfield)=hunt)\n" .
          " same => n,Dial(" . substr ( $dialstring, 1) . ")\n";
  if ( ! write_config ( "config", "dialplan-extension-" . $parameters["NewNumber"], $hunt))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create extension data file
  $parameters["Number"] = $parameters["NewNumber"];
  unset ( $parameters["NewNumber"]);
  if ( ! write_config ( "datafile", "extension-" . $parameters["Number"], array_merge_recursive ( $parameters, array ( "Type" => "hunt"))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_change_post") && ! framework_call ( "extension_hunt_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_change_finish"))
  {
    framework_call ( "extension_hunt_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Extension hunt changed.");
}

/**
 * Function to remove an existing extension hunt.
 * Required parameters are: (int) Number
 * Possible results:
 *   - 200: OK, extension hunt removed
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
function extension_hunt_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_remove_start"))
  {
    $parameters = framework_call ( "extension_hunt_remove_start", $parameters);
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
  if ( framework_has_hook ( "extension_hunt_remove_validate"))
  {
    $data = framework_call ( "extension_hunt_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extension_hunt_remove_sanitize"))
  {
    $parameters = framework_call ( "extension_hunt_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_remove_pre"))
  {
    $parameters = framework_call ( "extension_hunt_remove_pre", $parameters, false, $parameters);
  }

  // Verify if extension hunt exist
  if ( ! check_config ( "config", "dialplan-extension-" . $parameters["Number"]))
  {
    return array ( "code" => 404, "message" => "Number doesn't exist.");
  }

  // Remove extension hunt files
  if ( ! unlink_config ( "config", "dialplan-extension-" . $parameters["Number"]) || ! unlink_config ( "datafile", "extension-" . $parameters["Number"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_remove_post") && ! framework_call ( "extension_hunt_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_remove_finish"))
  {
    framework_call ( "extension_hunt_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Extension hunt removed.");
}

/**
 * Function to remove all existing extension hunts configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function extension_hunt_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_wipe_start"))
  {
    $parameters = framework_call ( "extension_hunt_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_wipe_pre"))
  {
    framework_call ( "extension_hunt_wipe_pre", $parameters, false, true);
  }

  // Remove all extensions files of extension hunt type:
  foreach ( list_config ( "datafile", "extension") as $filename)
  {
    $data = read_config ( "datafile", $filename);
    if ( $data["Type"] == "hunt")
    {
      $entryid = (int) substr ( $filename, strrpos ( $filename, "-") + 1);
      unlink_config ( "config", "dialplan-extension-" . $entryid);
      unlink_config ( "datafile", $filename);
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_wipe_post"))
  {
    framework_call ( "extension_hunt_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extension_hunt_wipe_finish"))
  {
    framework_call ( "extension_hunt_wipe_finish", $parameters);
  }
}
?>
