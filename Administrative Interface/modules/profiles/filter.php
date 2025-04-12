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
 * VoIP Domain profiles module filters. This module add the filter calls related
 * to profiles.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Profiles
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add profile's filters
 */
framework_add_filter ( "page_menu_registers", "profiles_menu");
framework_add_filter ( "get_profiles", "get_profiles");
framework_add_filter ( "audio_inuse", "profiles_moh");

/**
 * Function to add entry to registers menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function profiles_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "type" => "entry", "icon" => "globe", "href" => "/profiles", "text" => __ ( "Profiles"))));
}

/**
 * Function to get profiles filtered by ID, description or domain.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function get_profiles ( $buffer, $parameters)
{
  global $_in;

  /**
   * Create where clause
   */
  $where = "";
  if ( array_key_exists ( "ID", $parameters))
  {
    $where .= " AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]);
  }
  if ( array_key_exists ( "Domain", $parameters))
  {
    $where .= " AND `Domain` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Domain"]) . "'";
  }
  if ( array_key_exists ( "Text", $parameters))
  {
    $where .= " AND `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["Text"])))) . "%'";
  }

  /**
   * Check into database if profiles exists
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Profiles`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    while ( $profile = $result->fetch_assoc ())
    {
      $data[] = $profile;
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to check if profile is using specific audio id.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function profiles_moh ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Search profile using audio file
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Profiles` WHERE `Moh` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $data = array ();
  while ( $profile = $result->fetch_assoc ())
  {
    $data[] = array ( "Type" => "Profile", "ID" => $profile["ID"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
