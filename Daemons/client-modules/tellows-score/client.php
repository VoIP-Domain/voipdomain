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
 * VoIP Domain Tellows Scrore actions module. This module add the Tellows Score
 * key to the database and activate if requested the anti-spam based on the
 * Tellows Score.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Tellows Score
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "configs_tellows", "configs_tellows");
framework_add_hook ( "configs_tellows_cleanup_remove", "configs_tellows_cleanup");

/**
 * Cleanup functions
 */
framework_add_hook ( "config_wipe", "tellows_wipe");

/**
 * Function to configurate Tellows Score.
 * Required parameters are: (string) Key, (boolean) AntiSpam, (integer) Score
 * Possible results:
 *   - 200: OK, configuration created (overwritten)
 *   - 201: OK, configuration created
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function configs_tellows ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "configs_tellows_start"))
  {
    $parameters = framework_call ( "configs_tellows_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Key", $parameters))
  {
    $data["Key"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Key", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Key"]))
  {
    $data["Key"] = "Invalid content";
  }
  if ( ! array_key_exists ( "AntiSpam", $parameters))
  {
    $data["AntiSpam"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "AntiSpam", $data) && ! is_bool ( $parameters["AntiSpam"]))
  {
    $data["AntiSpam"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Score", $parameters))
  {
    $data["Score"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Score", $data) && ( $parameters["Score"] < 6 || $parameters["Score"] > 10))
  {
    $data["Score"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "configs_tellows_validate"))
  {
    $data = framework_call ( "configs_tellows_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "configs_tellows_sanitize"))
  {
    $parameters = framework_call ( "configs_tellows_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "configs_tellows_pre"))
  {
    $parameters = framework_call ( "configs_tellows_pre", $parameters, false, $parameters);
  }

  // Create configuration file structure
  $config = "[globals](+)\n" .
            "VD_TELLOWS_ANTISPAM=" . ( $parameters["AntiSpam"] ? "true" : "false") . "\n" .
            "VD_TELLOWS_SCORE=" . $parameters["Score"] . "\n";

  // Write configuration file
  if ( ! write_config ( "config", "dialplan-configs-tellows", $config))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create tellows data file
  if ( ! write_config ( "datafile", "configs-tellows", $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "configs_tellows_post") && ! framework_call ( "configs_tellows_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "configs_tellows_finish"))
  {
    framework_call ( "configs_tellows_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Tellows created.");
}

/**
 * Function to remove all existing Tellows configs configurations.
 * Required parameters are: none
 * Possible results:
 *   - 200: OK, cleaned configs
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function configs_tellows_cleanup ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tellows_cleanup_start"))
  {
    $parameters = framework_call ( "tellows_cleanup_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tellows_cleanup_validate"))
  {
    $data = framework_call ( "tellows_cleanup_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "configs" => array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data)));
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "tellows_cleanup_sanitize"))
  {
    $parameters = framework_call ( "tellows_cleanup_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tellows_cleanup_pre"))
  {
    $parameters = framework_call ( "tellows_cleanup_pre", $parameters, false, $parameters);
  }

  // Remove all files
  if ( ! unlink_config ( "config", "dialplan-configs-tellows") || ! unlink_config ( "datafile", "configs-tellows"))
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "configs" => array ( "code" => 500, "message" => "Error removing file.")));
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tellows_cleanup_post") && ! framework_call ( "tellows_cleanup_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tellows_cleanup_finish"))
  {
    framework_call ( "tellows_cleanup_finish", $parameters);
  }

  // Finish event
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "configs" => array ( "code" => 200, "message" => "Cleaned configs.")));
}

/**
 * Function to remove Tellows Score configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function tellows_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tellows_wipe_start"))
  {
    $parameters = framework_call ( "tellows_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tellows_wipe_pre"))
  {
    framework_call ( "tellows_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  unlink_config ( "config", "dialplan-configs-tellows");
  unlink_config ( "datafile", "configs-tellows");

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tellows_wipe_post"))
  {
    framework_call ( "tellows_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tellows_wipe_finish"))
  {
    framework_call ( "tellows_wipe_finish", $parameters);
  }
}
?>
