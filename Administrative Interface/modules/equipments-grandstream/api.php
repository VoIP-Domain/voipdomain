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
 * VoIP Domain Grandstream equipments module API. This module add the API calls
 * related to Grandstream equipments.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Grandstream
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

framework_add_hook (
  "equipments_configure_gxp1160_sanitize",
  "equipments_configure_grandstream_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_gxp1160_validate",
  "equipments_configure_grandstream_userpass_validate"
);
framework_add_hook (
  "equipments_configure_gxp1165_sanitize",
  "equipments_configure_grandstream_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_gxp1165_validate",
  "equipments_configure_grandstream_userpass_validate"
);
framework_add_hook (
  "equipments_configure_gxp1610_sanitize",
  "equipments_configure_grandstream_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_gxp1610_validate",
  "equipments_configure_grandstream_userpass_validate"
);
framework_add_hook (
  "equipments_configure_gxp1615_sanitize",
  "equipments_configure_grandstream_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_gxp1615_validate",
  "equipments_configure_grandstream_userpass_validate"
);
framework_add_hook (
  "equipments_configure_gxp1620_sanitize",
  "equipments_configure_grandstream_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_gxp1620_validate",
  "equipments_configure_grandstream_userpass_validate"
);
framework_add_hook (
  "equipments_configure_gxp1625_sanitize",
  "equipments_configure_grandstream_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_gxp1625_validate",
  "equipments_configure_grandstream_userpass_validate"
);
framework_add_hook (
  "equipments_configure_gxp1628_sanitize",
  "equipments_configure_grandstream_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_gxp1628_validate",
  "equipments_configure_grandstream_userpass_validate"
);
framework_add_hook (
  "equipments_configure_gxp1630_sanitize",
  "equipments_configure_grandstream_userpass_sanitize"
);
framework_add_hook (
  "equipments_configure_gxp1630_validate",
  "equipments_configure_grandstream_userpass_validate"
);

/**
 * Function to extend equipments configuration sanitize of Grandstream models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_configure_grandstream_userpass_sanitize ( $buffer, $parameters)
{
  /**
   * Sanitize incoming ExtraSettings data
   */
  $extrasettings = array (
    "UserPassword" => $parameters["ExtraSettings"]["UserPassword"],
    "AdminPassword" => $parameters["ExtraSettings"]["AdminPassword"]
  );
  $buffer["ExtraSettings"] = $extrasettings;

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend equipments addition/edition validate of Grandstream models.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_configure_grandstream_userpass_validate ( $buffer, $parameters)
{
  /**
   * Validate incoming data
   */
  if ( empty ( $parameters["ExtraSettings"]["UserPassword"]))
  {
    $buffer[strtoupper ( $parameters["UID"]) . "_Userpass"] = __ ( "The password is required.");
  } else {
    if ( strlen ( $parameters["ExtraSettings"]["UserPassword"]) < 6)
    {
      $buffer[strtoupper ( $parameters["UID"]) . "_Userpass"] = __ ( "The password must have at least 6 digits.");
    }
  }
  if ( empty ( $parameters["ExtraSettings"]["AdminPassword"]))
  {
    $buffer[strtoupper ( $parameters["UID"]) . "_Adminpass"] = __ ( "The password is required.");
  } else {
    if ( strlen ( $parameters["ExtraSettings"]["AdminPassword"]) < 6)
    {
      $buffer[strtoupper ( $parameters["UID"]) . "_Adminpass"] = __ ( "The password must have at least 6 digits.");
    }
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Implement tenant addition hook
 */
framework_add_hook ( "tenants_add_post", "equipments_grandstream_tenant_add_post");

/**
 * Function to add default Grandstream equipments settings to new tenant.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_grandstream_tenant_add_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add authentication settings
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Equipment_gxp1160', " . (int) $parameters["ID"] . ", '{\"AudioCodecs\":[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"],\"VideoCodecs\":[],\"ExtraSettings\":{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}}')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Equipment_gxp1165', " . (int) $parameters["ID"] . ", '{\"AudioCodecs\":[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"],\"VideoCodecs\":[],\"ExtraSettings\":{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}}')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Equipment_gxp1610', " . (int) $parameters["ID"] . ", '{\"AudioCodecs\":[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"],\"VideoCodecs\":[],\"ExtraSettings\":{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}}')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Equipment_gxp1615', " . (int) $parameters["ID"] . ", '{\"AudioCodecs\":[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"],\"VideoCodecs\":[],\"ExtraSettings\":{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}}')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Equipment_gxp1620', " . (int) $parameters["ID"] . ", '{\"AudioCodecs\":[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"],\"VideoCodecs\":[],\"ExtraSettings\":{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}}')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Equipment_gxp1625', " . (int) $parameters["ID"] . ", '{\"AudioCodecs\":[\"ULAW\",\"ALAW\",\"G723\",\"G729\",\"G722\",\"ILBC\",\"G726\"],\"VideoCodecs\":[],\"ExtraSettings\":{\"UserPassword\":\"vduser\",\"AdminPassword\":\"vdadmin\"}}')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Equipment_gxp1628', " . (int) $parameters["ID"] . ", '{\"AudioCodecs\":[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"],\"VideoCodecs\":[],\"ExtraSettings\":[]}')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Equipment_gxp1630', " . (int) $parameters["ID"] . ", '{\"AudioCodecs\":[\"ALAW\",\"ULAW\",\"G723\",\"G729\",\"ILBC\",\"G726\",\"GSM\"],\"VideoCodecs\":[],\"ExtraSettings\":[]}')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Return data to user
   */
  return $buffer;
}
?>
