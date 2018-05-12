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
 * VoIP Domain general configuration actions module. This module add the general
 * system configuration data to the database and to Asterisk dialplan.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Configuration
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "configs_language", "configs_language");
framework_add_hook ( "configs_operator", "configs_operator");
framework_add_hook ( "configs_moh", "configs_moh");
framework_add_hook ( "configs_ntp", "configs_ntp");
framework_add_hook ( "changepermissions", "configs_permissions");

/**
 * Cleanup functions
 */
framework_add_hook ( "config_wipe", "config_wipe");
cleanup_register ( "Configs", "config_wipe");

/**
 * Function to configurate system default language.
 * Required parameters are: (string) Language
 * Possible results:
 *   - 200: OK, configuration created
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function configs_language ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "configs_language_start"))
  {
    $parameters = framework_call ( "configs_language_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Language", $parameters))
  {
    $data["Language"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Language", $data) && ! preg_match ( "/^[A-Za-z_\-]+$/", $parameters["Language"]))
  {
    $data["Language"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "configs_language_validate"))
  {
    $data = framework_call ( "configs_language_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "configs_language_sanitize"))
  {
    $parameters = framework_call ( "configs_language_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "configs_language_pre"))
  {
    $parameters = framework_call ( "configs_language_pre", $parameters, false, $parameters);
  }

  // Create configuration file structure
  $config = "[globals](+)\n" .
            "VD_LANGUAGE=" . $parameters["Language"] . "\n";

  // Write configuration file
  if ( ! write_config ( "config", "dialplan-configs-language", $config))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create language data file
  if ( ! write_config ( "datafile", "configs-language", $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "configs_language_post") && ! framework_call ( "configs_language_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "configs_language_finish"))
  {
    framework_call ( "configs_language_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Language configuration created.");
}

/**
 * Function to configurate system default operator.
 * Required parameters are: (integer) Operator
 * Possible results:
 *   - 200: OK, configuration created
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function configs_operator ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "configs_operator_start"))
  {
    $parameters = framework_call ( "configs_operator_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Operator", $parameters))
  {
    $data["Operator"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Operator", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Operator"]))
  {
    $data["Operator"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "configs_operator_validate"))
  {
    $data = framework_call ( "configs_operator_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "configs_operator_sanitize"))
  {
    $parameters = framework_call ( "configs_operator_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "configs_operator_pre"))
  {
    $parameters = framework_call ( "configs_operator_pre", $parameters, false, $parameters);
  }

  // Create configuration file structure
  $config = "[globals](+)\n" .
            "VD_OPERATOR=" . (int) $parameters["Operator"] . "\n";

  // Write configuration file
  if ( ! write_config ( "config", "dialplan-configs-operator", $config))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create operator data file
  if ( ! write_config ( "datafile", "configs-operator", $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "configs_operator_post") && ! framework_call ( "configs_operator_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "configs_operator_finish"))
  {
    framework_call ( "configs_operator_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Operator configuration created.");
}

/**
 * Function to configurate system default music on hold (MOH).
 * Required parameters are: (integer) MOH
 * Possible results:
 *   - 200: OK, configuration created
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function configs_moh ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "configs_moh_start"))
  {
    $parameters = framework_call ( "configs_moh_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "MOH", $parameters))
  {
    $data["MOH"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "MOH", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["MOH"]))
  {
    $data["MOH"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "configs_moh_validate"))
  {
    $data = framework_call ( "configs_moh_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "configs_moh_sanitize"))
  {
    $parameters = framework_call ( "configs_moh_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "configs_moh_pre"))
  {
    $parameters = framework_call ( "configs_moh_pre", $parameters, false, $parameters);
  }

  // Create configuration file structure
  $config = "[globals](+)\n" .
            "VD_MOH=" . (int) $parameters["MOH"] . "\n";

  // Write configuration file
  if ( ! write_config ( "config", "dialplan-configs-moh", $config))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create MOH data file
  if ( ! write_config ( "datafile", "configs-moh", $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "configs_moh_post") && ! framework_call ( "configs_moh_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "configs_moh_finish"))
  {
    framework_call ( "configs_moh_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "MOH configuration created.");
}

/**
 * Function to configurate system default NTP servers.
 * Required parameters are: (array (string)) NTP[]
 * Possible results:
 *   - 200: OK, configuration created
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function configs_ntp ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "configs_ntp_start"))
  {
    $parameters = framework_call ( "configs_ntp_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "NTP", $parameters))
  {
    $data["NTP"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NTP", $data) && ! is_array ( $parameters["NTP"]))
  {
    $data["NTP"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NTP", $data))
  {
    foreach ( $parameters["NTP"] as $addr)
    {
      if ( ! preg_match ( "/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^(?:(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-fA-F]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:(?:[0-9a-fA-F]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,1}(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:(?:[0-9a-fA-F]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,2}(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:(?:[0-9a-fA-F]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,3}(?:(?:[0-9a-fA-F]{1,4})))?::(?:(?:[0-9a-fA-F]{1,4})):)(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,4}(?:(?:[0-9a-fA-F]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,5}(?:(?:[0-9a-fA-F]{1,4})))?::)(?:(?:[0-9a-fA-F]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-fA-F]{1,4})):){0,6}(?:(?:[0-9a-fA-F]{1,4})))?::))))$/", $addr))
      {
        $data["NTP"] = "Invalid content";
      }
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "configs_ntp_validate"))
  {
    $data = framework_call ( "configs_ntp_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "configs_ntp_sanitize"))
  {
    $parameters = framework_call ( "configs_ntp_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "configs_ntp_pre"))
  {
    $parameters = framework_call ( "configs_ntp_pre", $parameters, false, $parameters);
  }

  // Create configuration file structure
  $config = "[globals](+)\n" .
            "VD_NTP=" . implode ( ",", $parameters["NTP"]) . "\n";

  // Write configuration file
  if ( ! write_config ( "config", "dialplan-configs-ntp", $config))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create ntp data file
  if ( ! write_config ( "datafile", "configs-ntp", $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "configs_ntp_post") && ! framework_call ( "configs_ntp_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "configs_ntp_finish"))
  {
    framework_call ( "configs_ntp_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "NTP configuration created.");
}

/**
 * Function to configurate system default permissions.
 * Required parameters are: (array (enum['y','n','p'] Local, enum['y','n','p'] Interstate, enum['y','n','p'] International)) Landline[], (array (enum['y','n','p'] Local, enum['y','n','p'] Interstate, enum['y','n','p'] International)) Mobile[], (array (enum['y','n','p'] Local, enum['y','n','p'] Interstate, enum['y','n','p'] International)) Marine[], (array (enum['y','n','p'] Local, enum['y','n','p'] International)) Tollfree[], (array (enum['y','n','p'] Local, enum['y','n','p'] International)) PRN[], (array (enum['y','n','p'] Local, enum['y','n','p'] International)) Satellite[]
 * Possible results:
 *   - 200: OK, configuration created
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function configs_permissions ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "configs_permissions_start"))
  {
    $parameters = framework_call ( "configs_permissions_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Landline", $parameters) || ! array_key_exists ( "Local", $parameters["Landline"]) || ! array_key_exists ( "Interstate", $parameters["Landline"]) || ! array_key_exists ( "International", $parameters["Landline"]))
  {
    $data["Landline"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Landline", $data) && ( ! preg_match ( "/^[ynp]$/", $parameters["Landline"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Landline"]["Interstate"]) || ! preg_match ( "/^[ynp]$/", $parameters["Landline"]["International"])))
  {
    $data["Landline"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Mobile", $parameters) || ! array_key_exists ( "Local", $parameters["Mobile"]) || ! array_key_exists ( "Interstate", $parameters["Mobile"]) || ! array_key_exists ( "International", $parameters["Mobile"]))
  {
    $data["Mobile"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Mobile", $data) && ( ! preg_match ( "/^[ynp]$/", $parameters["Mobile"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Mobile"]["Interstate"]) || ! preg_match ( "/^[ynp]$/", $parameters["Mobile"]["International"])))
  {
    $data["Mobile"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Marine", $parameters) || ! array_key_exists ( "Local", $parameters["Marine"]) || ! array_key_exists ( "Interstate", $parameters["Marine"]) || ! array_key_exists ( "International", $parameters["Marine"]))
  {
    $data["Marine"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Marine", $data) && ( ! preg_match ( "/^[ynp]$/", $parameters["Marine"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Marine"]["Interstate"]) || ! preg_match ( "/^[ynp]$/", $parameters["Marine"]["International"])))
  {
    $data["Marine"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Tollfree", $parameters) || ! array_key_exists ( "Local", $parameters["Tollfree"]) || ! array_key_exists ( "International", $parameters["Tollfree"]))
  {
    $data["Tollfree"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Tollfree", $data) && ( ! preg_match ( "/^[ynp]$/", $parameters["Tollfree"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Tollfree"]["International"])))
  {
    $data["Tollfree"] = "Invalid content";
  }
  if ( ! array_key_exists ( "PRN", $parameters) || ! array_key_exists ( "Local", $parameters["PRN"]) || ! array_key_exists ( "International", $parameters["PRN"]))
  {
    $data["PRN"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "PRN", $data) && ( ! preg_match ( "/^[ynp]$/", $parameters["PRN"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["PRN"]["International"])))
  {
    $data["PRN"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Satellite", $parameters) || ! array_key_exists ( "Local", $parameters["Satellite"]) || ! array_key_exists ( "International", $parameters["Satellite"]))
  {
    $data["Satellite"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Satellite", $data) && ( ! preg_match ( "/^[ynp]$/", $parameters["Satellite"]["Local"]) || ! preg_match ( "/^[ynp]$/", $parameters["Satellite"]["International"])))
  {
    $data["Satellite"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "configs_permissions_validate"))
  {
    $data = framework_call ( "configs_permissions_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "configs_permissions_sanitize"))
  {
    $parameters = framework_call ( "configs_permissions_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "configs_permissions_pre"))
  {
    $parameters = framework_call ( "configs_permissions_pre", $parameters, false, $parameters);
  }

  // Create configuration file structure
  $config = "[globals](+)\n" .
            "VD_LANDLINE_LOCAL=" . $parameters["Landline"]["Local"] . "\n" .
            "VD_LANDLINE_INTERSTATE=" . $parameters["Landline"]["Interstate"] . "\n" .
            "VD_LANDLINE_INTERNATIONAL=" . $parameters["Landline"]["International"] . "\n" .
            "VD_MOBILE_LOCAL=" . $parameters["Mobile"]["Local"] . "\n" .
            "VD_MOBILE_INTERSTATE=" . $parameters["Mobile"]["Interstate"] . "\n" .
            "VD_MOBILE_INTERNATIONAL=" . $parameters["Mobile"]["International"] . "\n" .
            "VD_MARINE_LOCAL=" . $parameters["Marine"]["Local"] . "\n" .
            "VD_MARINE_INTERSTATE=" . $parameters["Marine"]["Interstate"] . "\n" .
            "VD_MARINE_INTERNATIONAL=" . $parameters["Marine"]["International"] . "\n" .
            "VD_TOLLFREE_LOCAL=" . $parameters["Tollfree"]["Local"] . "\n" .
            "VD_TOLLFREE_INTERNATIONAL=" . $parameters["Tollfree"]["International"] . "\n" .
            "VD_PRN_LOCAL=" . $parameters["PRN"]["Local"] . "\n" .
            "VD_PRN_INTERNATIONAL=" . $parameters["PRN"]["International"] . "\n" .
            "VD_SATELLITE_LOCAL=" . $parameters["Satellite"]["Local"] . "\n" .
            "VD_SATELLITE_INTERNATIONAL=" . $parameters["Satellite"]["International"] . "\n";

  // Write configuration file
  if ( ! write_config ( "config", "dialplan-configs-permissions", $config))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create permissions data file
  if ( ! write_config ( "datafile", "configs-permissions", $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "configs_permissions_post") && ! framework_call ( "configs_permissions_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "configs_permissions_finish"))
  {
    framework_call ( "configs_permissions_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Permissions configuration created.");
}

/**
 * Function to remove all existing configs configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function config_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "configs_wipe_start"))
  {
    $parameters = framework_call ( "configs_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "configs_wipe_pre"))
  {
    framework_call ( "configs_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  unlink_config ( "config", "dialplan-configs-language");
  unlink_config ( "datafile", "configs-language");
  unlink_config ( "config", "dialplan-configs-operator");
  unlink_config ( "datafile", "configs-operator");
  unlink_config ( "config", "dialplan-configs-moh");
  unlink_config ( "datafile", "configs-moh");
  unlink_config ( "config", "dialplan-configs-ntp");
  unlink_config ( "datafile", "configs-ntp");
  unlink_config ( "config", "dialplan-configs-permissions");
  unlink_config ( "datafile", "configs-permissions");

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "configs_wipe_post"))
  {
    framework_call ( "configs_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "configs_wipe_finish"))
  {
    framework_call ( "configs_wipe_finish", $parameters);
  }
}
?>
