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
 * VoIP Domain extensions filter module. This module add the filter calls related
 * to extensions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add extensions's filters
 */
framework_add_filter ( "page_menu_registers", "extensions_menu", IN_HOOK_INSERT_FIRST);
framework_add_filter ( "get_extensions", "get_extensions");
framework_add_filter ( "get_allocations", "get_extensions");
framework_add_filter ( "count_extensions", "count_extensions");
framework_add_filter ( "count_allocations", "count_extensions");

/**
 * Function to add entry to registers menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function extensions_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "type" => "entry", "icon" => "phone", "href" => "/extensions", "text" => __ ( "Extensions"))));
}

/**
 * Function to get extensions filtered by ID, number, description or range.
 * Function to check for an extension based on number or name.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function get_extensions ( $buffer, $parameters)
{
  global $_in;

  /**
   * Create where clause
   */
  $where = "";
  if ( array_key_exists ( "id", $parameters) && $parameters["called_filter"] == "get_extensions")
  {
    $where .= " AND `Extensions`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"]);
  }
  if ( array_key_exists ( "number", $parameters))
  {
    $where .= " AND `Extensions`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["number"]);
  }
  if ( array_key_exists ( "text", $parameters))
  {
    $where .= " AND ( `Extensions`.`Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["text"])))) . "%' OR `Extensions`.`NameFon` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( fonetiza ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["text"]))))) . "%')";
  }
  if ( array_key_exists ( "range", $parameters))
  {
    $where .= " AND `Extensions`.`Range` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["range"]);
  }
  if ( array_key_exists ( "group", $parameters) && $parameters["called_filter"] == "get_extensions")
  {
    $where .= " AND `Extensions`.`Group` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["group"]);
  }
  if ( array_key_exists ( "costcenter", $parameters) && $parameters["called_filter"] == "get_extensions")
  {
    $where .= " AND `Extensions`.`CostCenter` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["costcenter"]);
  }

  /**
   * Check into database if extension exists
   */
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.*, `Groups`.`Description` AS `GroupName`, `Servers`.`Name` AS `ServerName`, `Ranges`.`Description` AS `RangeName`, `Ranges`.`Server` FROM `Extensions` LEFT JOIN `Groups` ON `Extensions`.`Group` = `Groups`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    while ( $extension = $result->fetch_assoc ())
    {
      $data = array ();
      $data["Type"] = "Extension";
      $data["Extension"] = $extension["Extension"];
      $data["ViewPath"] = "/extensions/" . $extension["ID"] . "/view";
      $data["Record"] = $extension;
      $buffer = array_merge ( ( is_array ( $buffer) ? $buffer : array ()), array ( $data));
    }
  }
  return $buffer;
}

/**
 * Function to count how many extensions are allocated.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function count_extensions ( $buffer, $parameters)
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
    $where .= " AND ( `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["text"])))) . "%' OR `NameFon` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( fonetiza ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["text"]))))) . "%')";
  }
  if ( array_key_exists ( "range", $parameters))
  {
    $where .= " AND `Range` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["range"]);
  }
  if ( array_key_exists ( "group", $parameters) && $parameters["called_filter"] == "count_extensions")
  {
    $where .= " AND `Group` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["group"]);
  }
  if ( array_key_exists ( "costcenter", $parameters) && $parameters["called_filter"] == "count_extensions")
  {
    $where .= " AND `CostCenter` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["costcenter"]);
  }

  /**
   * Count allocated extensions
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `Extensions`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    return $buffer;
  }
  $data = array ( "Extensions" => intval ( $count->fetch_assoc ()["Total"]));

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
