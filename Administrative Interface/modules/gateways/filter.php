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
 * VoIP Domain gateways module filters. This module add the filter calls related
 * to gateways.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Gateways
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add gateway's filters
 */
framework_add_filter ( "page_menu_registers", "gateways_menu");
framework_add_filter ( "get_gateways", "get_gateways");

/**
 * Function to add entry to registers menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function gateways_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "type" => "entry", "icon" => "cloud-upload-alt", "href" => "/gateways", "text" => __ ( "Gateways"))));
}

/**
 * Function to get gateways filtered by ID, name or number.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function get_gateways ( $buffer, $parameters)
{
  global $_in;

  /**
   * Create where clause
   */
  $where = "";
  if ( array_key_exists ( "ID", $parameters))
  {
    if ( is_array ( $parameters["ID"]))
    {
      $where .= " AND `ID` IN (";
      foreach ( $parameters["ID"] as $id)
      {
        $where .= $_in["mysql"]["id"]->real_escape_string ( (int) $id) . ", ";
      }
      $where = substr ( $where, 0, strlen ( $where) - 2) . ")";
    } else {
      $where .= " AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]);
    }
  }
  if ( array_key_exists ( "Text", $parameters))
  {
    $where .= " AND `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["Text"])))) . "%'";
  }
  if ( array_key_exists ( "Active", $parameters))
  {
    $where .= " AND `Active` = '" . $_in["mysql"]["id"]->real_escape_string ( (boolean) $parameters["Active"]) . "'";
  }

  /**
   * Check into database if gateways exists
   */
  $data = array ();
  if ( $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Description`, `Active`, `Type`, `Priority`, `Number` FROM `Gateways`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    /**
     * Create result structure
     */
    while ( $result = $results->fetch_assoc ())
    {
      $result["Active"] = (boolean) $result["Active"];
      $result["TypeEN"] = $result["Type"];
      $result["Type"] = __ ( $result["Type"]);
      switch ( $result["Priority"])
      {
        case 1:
          $result["PriorityEN"] = "High";
          break;
        case 2:
          $result["PriorityEN"] = "Medium";
          break;
        default:
          $result["PriorityEN"] = "Low";
          break;
      }
      $result["Priority"] = __ ( $result["PriorityEN"]);
      $data[] = api_filter_entry ( array ( 'ID', 'Description', 'Active', 'Type', 'TypeEN', 'Priority', 'PriorityEN', 'Number'), $result);
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
