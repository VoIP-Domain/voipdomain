<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * VoIP Domain equipments database module. This module provides the equipments
 * database API to the system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search equipments
 */
framework_add_hook ( "equipments_search", "equipments_search");
framework_add_permission ( "equipments_search", __ ( "Search equipments (select list standard)"));
framework_add_api_call ( "/equipments/search", "Read", "equipments_search", array ( "permissions" => array ( "user", "equipments_search")));

/**
 * Function to generate equipments list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Equipments");

  /**
   * Search equipments
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` " . ( ! empty ( $parameters["q"]) ? "WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' " : "") . "ORDER BY `Name`"))
  {
    while ( $equipments = $result->fetch_assoc ())
    {
      if ( array_key_exists ( "complete", $parameters) && $parameters["complete"])
      {
        $data[] = array ( $equipments["ID"], $equipments["Name"], $equipments["AP"], $equipments["Shortcuts"], $equipments["Extensions"], $equipments["ShortsExt"]);
      } else {
        $data[] = array ( $equipments["ID"], $equipments["Name"]);
      }
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get equipments information
 */
framework_add_hook ( "equipments_view", "equipments_view");
framework_add_permission ( "equipments_view", __ ( "View equipment informations"));
framework_add_api_call ( "/equipments/:code", "Read", "equipments_view", array ( "permissions" => array ( "user", "equipments_view")));

/**
 * Function to generate equipments informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Equipments");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search equipments
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $equipment = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["name"] = $equipment["Name"];
  $data["template"] = $equipment["Template"];
  $data["ap"] = ( $equipment["AP"] == "Y" ? true : false);
  $data["shortcuts"] = $equipment["Shortcuts"];
  $data["extensions"] = $equipment["Extensions"];
  $data["shortsext"] = $equipment["ShortsExt"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
