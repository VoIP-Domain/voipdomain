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
 * VoIP Domain Yealink equipments module API. This module add the API calls
 * related to Yealink equipments.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Yealink
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

framework_add_hook (
  "equipments_configure_t18p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t18p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t19pe2_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t19pe2_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t20p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t20p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t21p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t21p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t21pe2_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t21pe2_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t22p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t22p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t23p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t23p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t23g_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t23g_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t26p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t26p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t27p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t27p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t27g_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t27g_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t29g_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t29g_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t30p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t30p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t31_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t31_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t31p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t31p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t31g_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t31g_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t38g_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t38g_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t40p_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t40p_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t46s_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t46s_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_t48s_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_t48s_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_cp920_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_cp920_validate",
  "equipments_configure_yealink_userpass_validate"
);
framework_add_hook (
  "equipments_configure_vp530_sanitize",
  "equipments_configure_yealink_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_vp530_validate",
  "equipments_configure_yealink_userpass_validate"
);

/**
 * Function to extend equipments configuration sanitize of Yealink models.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_configure_yealink_userpass_sanitize ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanitize incoming ExtraSettings data
   */
  $extrasettings = array (
    "User" => array (
      "Name" => $parameters["ExtraSettings"]["User"]["Name"],
      "Password" => $parameters["ExtraSettings"]["User"]["Password"]
    ),
    "Admin" => array (
      "Name" => $parameters["ExtraSettings"]["Admin"]["Name"],
      "Password" => $parameters["ExtraSettings"]["Admin"]["Password"]
    )
  );
  $buffer["ExtraSettings"] = $extrasettings;

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend equipments addition/edition validate of Yealink models.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_configure_yealink_userpass_validate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Validate incoming data
   */
  if ( empty ( $parameters["ExtraSettings"]["User"]["Name"]))
  {
    $buffer[strtoupper ( $parameters["UID"]) . "_Username"] = __ ( "The non-privileged user name is required.");
  }
  if ( empty ( $parameters["ExtraSettings"]["Admin"]["Name"]))
  {
    $buffer[strtoupper ( $parameters["UID"]) . "_Adminname"] = __ ( "The privileged administrator name is required.");
  }
  if ( empty ( $parameters["ExtraSettings"]["User"]["Password"]))
  {
    $buffer[strtoupper ( $parameters["UID"]) . "_Userpass"] = __ ( "The password is required.");
  } else {
    if ( strlen ( $parameters["ExtraSettings"]["User"]["Password"]) < 6)
    {
      $buffer[strtoupper ( $parameters["UID"]) . "_Userpass"] = __ ( "The password must have at least 6 digits.");
    }
  }
  if ( empty ( $parameters["ExtraSettings"]["Admin"]["Password"]))
  {
    $buffer[strtoupper ( $parameters["UID"]) . "_Adminpass"] = __ ( "The password is required.");
  } else {
    if ( strlen ( $parameters["ExtraSettings"]["Admin"]["Password"]) < 6)
    {
      $buffer[strtoupper ( $parameters["UID"]) . "_Adminpass"] = __ ( "The password must have at least 6 digits.");
    }
  }

  /**
   * Return data
   */
  return $buffer;
}
?>
