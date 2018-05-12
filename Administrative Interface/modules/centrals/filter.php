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
 * VoIP Domain centrals filter module. This module add the filter calls related
 * to centrals.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Centrals
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add central's filters
 */
framework_add_filter ( "page_menu_registers", "centrals_menu");
framework_add_filter ( "get_centrals", "get_centrals");
framework_add_filter ( "get_allocations", "get_centrals");
framework_add_filter ( "count_centrals", "count_centrals");
framework_add_filter ( "count_allocations", "count_centrals");

/**
 * Function to add entry to registers menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function centrals_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "type" => "entry", "icon" => "arrows-alt", "href" => "/centrals", "text" => __ ( "Centrals"))));
}

/**
 * Function to get centrals filtered by ID, number, description or range.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function get_centrals ( $buffer, $parameters)
{
  global $_in;

  /**
   * Create where clause
   */
  $where = "";
  if ( array_key_exists ( "id", $parameters) && $parameters["called_filter"] == "get_centrals")
  {
    $where .= " AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"]);
  }
  if ( array_key_exists ( "number", $parameters))
  {
    $where .= " AND `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["number"]);
  }
  if ( array_key_exists ( "text", $parameters))
  {
    $where .= " AND `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["text"])))) . "%'";
  }
  if ( array_key_exists ( "range", $parameters))
  {
    $where .= " AND `Range` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["range"]);
  }

  /**
   * Check into database if central exists
   */
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Centrals`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    while ( $central = $result->fetch_assoc ())
    {
      $data = array ();
      $data["Type"] = "Central";
      $data["Extension"] = $central["Extension"];
      $data["ViewPath"] = "/centrals/" . $central["ID"] . "/view";
      $data["Record"] = $central;
      $buffer = array_merge ( ( is_array ( $buffer) ? $buffer : array ()), array ( $data));
    }
  }
  return $buffer;
}

/**
 * Function to count how many centrals are allocated.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function count_centrals ( $buffer, $parameters)
{
  global $_in;

  /**
   * Create where clause
   */
  $where = "";
  if ( array_key_exists ( "number", $parameters))
  {
    $where .= " AND `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["number"]);
  }
  if ( array_key_exists ( "text", $parameters))
  {
    $where .= " AND `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["text"])))) . "%'";
  }
  if ( array_key_exists ( "range", $parameters))
  {
    $where .= " AND `Range` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["range"]);
  }

  /**
   * Count allocated centrals
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `Centrals`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    return $buffer;
  }
  $data = array ( "Centrals" => intval ( $count->fetch_assoc ()["Total"]));

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
