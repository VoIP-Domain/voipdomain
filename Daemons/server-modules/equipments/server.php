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
 * VoIP Domain equipments actions module. This module add the auto provisioning
 * devices configuration functions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "equipment_configure", "equipment_configure");
framework_add_hook ( "firmware_add", "firmware_add");
framework_add_hook ( "firmware_remove", "firmware_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "equipment_wipe", "equipment_wipe");
cleanup_register ( "Equipments", "equipment_wipe");

/**
 * Function to configure en equipment model.
 * Required parameters are: (string) UID, (array (string)) AudioCodecs, (array (string)) VideoCodecs, (array *) ExtraSettings
 * Possible results:
 *   - 200: OK, model configured
 *   - 404: Model not found
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function equipment_configure ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "equipment_configure_start"))
  {
    $parameters = framework_call ( "equipment_configure_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "UID", $parameters))
  {
    $data["UID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "UID", $data) && ! preg_match ( "/^[0-9a-z]+$/i", $parameters["UID"]))
  {
    $data["UID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "AudioCodecs", $parameters))
  {
    $data["AudioCodecs"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "AudioCodecs", $data) && ! is_array ( $parameters["AudioCodecs"]))
  {
    $data["AudioCodecs"] = "Invalid content";
  }
  if ( ! array_key_exists ( "AudioCodecs", $data))
  {
    foreach ( $parameters["AudioCodecs"] as $codec)
    {
      if ( ! preg_match ( "/^[A-Za-z0-9\-_]+$/", $codec))
      {
        $data["AudioCodecs"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "VideoCodecs", $parameters))
  {
    $data["VideoCodecs"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "VideoCodecs", $data) && ! is_array ( $parameters["VideoCodecs"]))
  {
    $data["VideoCodecs"] = "Invalid content";
  }
  if ( ! array_key_exists ( "VideoCodecs", $data))
  {
    foreach ( $parameters["VideoCodecs"] as $codec)
    {
      if ( ! preg_match ( "/^[A-Za-z0-9\-_]+$/", $codec))
      {
        $data["VideoCodecs"] = "Invalid content";
      }
    }
  }
  if ( ! array_key_exists ( "ExtraSettings", $parameters))
  {
    $data["ExtraSettings"] = "Missing parameter";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "equipment_configure_validate"))
  {
    $data = framework_call ( "equipment_configure_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "equipment_configure_sanitize"))
  {
    $parameters = framework_call ( "equipment_configure_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "equipment_configure_pre"))
  {
    $parameters = framework_call ( "equipment_configure_pre", $parameters, false, $parameters);
  }

  // Check if equipment exists
  if ( ! framework_has_hook ( "equipment_model_" . $parameters["UID"] . "_configure"))
  {
    return array ( "code" => 404, "message" => "Model not found.");
  }

  // Write configuration file
  if ( ! write_config ( "config", "sip-model-" . $parameters["UID"], framework_call ( "equipment_model_" . $parameters["UID"] . "_configure", $parameters)))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create equipment data file
  if ( ! write_config ( "datafile", "equipment-" . $parameters["UID"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "equipment_configure_post") && ! framework_call ( "equipment_configure_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "equipment_configure_finish"))
  {
    framework_call ( "equipment_configure_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "OK, model configured");
}

/**
 * Function to add equipment firmware.
 * Required parameters are: (string) Vendor, (string) UID, (string) Filename, (blob) Data
 * Possible results:
 *   - 200: OK, firmware added
 *   - 404: Equipment not found
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function firmware_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "firmware_add_start"))
  {
    $parameters = framework_call ( "firmware_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Vendor", $parameters))
  {
    $data["Vendor"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "UID", $parameters))
  {
    $data["UID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "UID", $data) && ! preg_match ( "/^[0-9a-z]+$/i", $parameters["UID"]))
  {
    $data["UID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Filename", $parameters))
  {
    $data["Filename"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Filename", $data) && $parameters["Filename"] != basename ( $parameters["Filename"]))
  {
    $data["Filename"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Data", $parameters))
  {
    $data["Data"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Data", $data) && base64_encode ( base64_decode ( $parameters["Data"], true)) !== $parameters["Data"])
  {
    $data["Data"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "firmware_add_validate"))
  {
    $data = framework_call ( "firmware_add_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Sanitize parameters
   */
  $parameters["Data"] = base64_decode ( $parameters["Data"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "firmware_add_sanitize"))
  {
    $parameters = framework_call ( "firmware_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "firmware_add_pre"))
  {
    $parameters = framework_call ( "firmware_add_pre", $parameters, false, $parameters);
  }

  /**
   * Check is there's model add hook
   */
  if ( ! framework_has_hook ( "equipment_model_" . $parameters["UID"] . "_firmware_add"))
  {
    return array ( "code" => 404, "message" => "Equipment not found.");
  }

  /**
   * Execute model firmware add hook
   */
  if ( ! framework_call ( "equipment_model_" . $parameters["UID"] . "_firmware_add", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "firmware_add_post") && ! framework_call ( "firmware_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "firmware_add_finish"))
  {
    framework_call ( "firmware_add_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "OK, firmware added");
}

/**
 * Function to remove equipment firmware.
 * Required parameters are: (string) Vendor, (string) UID, (string) Filename
 * Possible results:
 *   - 200: OK, firmware removed
 *   - 404: Equipment not found
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function firmware_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "firmware_remove_start"))
  {
    $parameters = framework_call ( "firmware_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Vendor", $parameters))
  {
    $data["Vendor"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "UID", $parameters))
  {
    $data["UID"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "UID", $data) && ! preg_match ( "/^[0-9a-z]+$/i", $parameters["UID"]))
  {
    $data["UID"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Filename", $parameters))
  {
    $data["Filename"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Filename", $data) && $parameters["Filename"] != basename ( $parameters["Filename"]))
  {
    $data["Filename"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "firmware_remove_validate"))
  {
    $data = framework_call ( "firmware_remove_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Sanitize parameters
   */
  $parameters["Data"] = base64_decode ( $parameters["Data"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "firmware_remove_sanitize"))
  {
    $parameters = framework_call ( "firmware_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "firmware_remove_pre"))
  {
    $parameters = framework_call ( "firmware_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Check is there's model remove hook
   */
  if ( ! framework_has_hook ( "equipment_model_" . $parameters["UID"] . "_firmware_remove"))
  {
    return array ( "code" => 404, "message" => "Equipment not found.");
  }

  /**
   * Execute model firmware remove hook
   */
  if ( ! framework_call ( "equipment_model_" . $parameters["UID"] . "_firmware_remove", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "firmware_remove_post") && ! framework_call ( "firmware_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "firmware_remove_finish"))
  {
    framework_call ( "firmware_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "OK, firmware removed");
}

/**
 * Function to remove all existing equipments configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function equipment_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "equipment_wipe_start"))
  {
    $parameters = framework_call ( "equipment_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "equipment_wipe_pre"))
  {
    framework_call ( "equipment_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "equipment") as $filename)
  {
    unlink_config ( "config", "sip-model-" . substr ( $filename, strrpos ( $filename, "-") + 1));
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "equipment_wipe_post"))
  {
    framework_call ( "equipment_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "equipment_wipe_finish"))
  {
    framework_call ( "equipment_wipe_finish", $parameters);
  }
}
?>
