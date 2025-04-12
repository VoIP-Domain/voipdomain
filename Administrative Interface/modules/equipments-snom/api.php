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
 * VoIP Domain Snom equipments module API. This module add the API calls
 * related to Snom equipments.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Snom
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

framework_add_hook (
  "equipments_configure_710_sanitize",
  "equipments_configure_snom_adminuserpass_sanitize"
);
framework_add_hook (
  "equipments_configure_710_validate",
  "equipments_configure_snom_adminuserpass_validate"
);
framework_add_hook (
  "equipments_configure_720_sanitize",
  "equipments_configure_snom_adminuserpass_sanitize"
);
framework_add_hook (
  "equipments_configure_720_validate",
  "equipments_configure_snom_adminuserpass_validate"
);
framework_add_hook (
  "equipments_configure_870_sanitize",
  "equipments_configure_snom_adminuserpass_sanitize"
);
framework_add_hook (
  "equipments_configure_870_validate",
  "equipments_configure_snom_adminuserpass_validate"
);

/**
 * Function to extend equipments configuration sanitize of Snom models.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_configure_snom_adminuserpass_sanitize ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanitize incoming ExtraSettings data
   */
  $extrasettings = array (
    "Username" => $parameters["ExtraSettings"]["Username"],
    "Password" => $parameters["ExtraSettings"]["Password"],
    "AdminModePassword" => $parameters["ExtraSettings"]["AdminModePassword"]
  );
  $buffer["ExtraSettings"] = $extrasettings;

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend equipments addition/edition validate of Snom models.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_configure_snom_adminuserpass_validate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Validate incoming data
   */
  if ( empty ( $parameters["ExtraSettings"]["Username"]))
  {
    $buffer[strtoupper ( $parameters["UID"]) . "_Username"] = __ ( "The username is required.");
  }
  if ( empty ( $parameters["ExtraSettings"]["Password"]))
  {
    $buffer[strtoupper ( $parameters["UID"]) . "_Password"] = __ ( "The password is required.");
  } else {
    if ( strlen ( $parameters["ExtraSettings"]["Password"]) < 6)
    {
      $buffer[strtoupper ( $parameters["UID"]) . "_Password"] = __ ( "The password must have at least 6 digits.");
    }
  }
  if ( empty ( $parameters["ExtraSettings"]["AdminModePassword"]))
  {
    $buffer[strtoupper ( $parameters["UID"]) . "_AdminModePassword"] = __ ( "The password is required.");
  } else {
    if ( strlen ( $parameters["ExtraSettings"]["AdminModePassword"]) < 6)
    {
      $buffer[strtoupper ( $parameters["UID"]) . "_AdminModePassword"] = __ ( "The password must have at least 6 digits.");
    }
  }

  /**
   * Return data
   */
  return $buffer;
}
?>
