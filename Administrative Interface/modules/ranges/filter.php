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
 * VoIP Domain ranges module filters. This module add the filter calls related to
 * ranges.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Ranges
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add range's filters
 */
framework_add_filter ( "page_menu_registers", "ranges_menu");
framework_add_filter ( "get_ranges", "get_ranges");
framework_add_filter ( "search_range", "search_range");

/**
 * Function to add entry to registers menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function ranges_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "type" => "entry", "icon" => "cloud", "href" => "/ranges", "text" => __ ( "Ranges"))));
}

/**
 * Function to get ranges filtered by ID, description or server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function get_ranges ( $buffer, $parameters)
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
  if ( array_key_exists ( "Server", $parameters))
  {
    $where .= " AND `Server` = '" . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Server"]) . "'";
  }
  if ( array_key_exists ( "Text", $parameters))
  {
    $where .= " AND `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["Text"])))) . "%'";
  }

  /**
   * Check into database if ranges exists
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    while ( $range = $result->fetch_assoc ())
    {
      $data[] = $range;
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to search for a range based on number.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function search_range ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check into database if range exists
   */
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `Ranges`.*, `Servers`.`NTP` FROM `Ranges` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID` WHERE `Ranges`.`Start` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Number"]) . " AND `Ranges`.`Finish` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Number"])))
  {
    if ( $result->num_rows != 0)
    {
      $data = $result->fetch_assoc ();
      $data["NTP"] = json_decode ( $data["NTP"], true);
    } else {
      $data = array ();
    }
    $buffer = array_merge ( ( is_array ( $buffer) ? $buffer : array ()), $data);
  }
  return $buffer;
}
?>
