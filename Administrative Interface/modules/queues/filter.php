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
 * VoIP Domain queues filter module. This module add the filter calls related
 * to queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights queued.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add queue's filters
 */
framework_add_filter ( "page_menu_registers", "queues_menu");
framework_add_filter ( "get_queues", "get_queues");
framework_add_filter ( "get_allocations", "get_queues");
framework_add_filter ( "count_queues", "count_queues");
framework_add_filter ( "count_allocations", "count_queues");

/**
 * Function to add entry to registers menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function queues_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "type" => "entry", "icon" => "code-branch", "href" => "/queues", "text" => __ ( "Queues"))));
}

/**
 * Function to get queues filtered by ID, number, description or range.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function get_queues ( $buffer, $parameters)
{
  global $_in;

  /**
   * Create where clause
   */
  $where = "";
  if ( array_key_exists ( "id", $parameters) && $parameters["called_filter"] == "get_queues")
  {
    $where .= " AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"]);
  }
  if ( array_key_exists ( "number", $parameters))
  {
    $where .= " AND `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["number"]);
  }
  if ( array_key_exists ( "text", $parameters))
  {
    $where .= " AND `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["text"])))) . "%'";
  }
  if ( array_key_exists ( "range", $parameters))
  {
    $where .= " AND `Range` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["range"]);
  }

  /**
   * Check into database if queue exists
   */
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    while ( $queue = $result->fetch_assoc ())
    {
      $data = array ();
      $data["Type"] = "Queue";
      $data["Extension"] = $queue["Extension"];
      $data["ViewPath"] = "/queues/" . $queue["ID"] . "/view";
      $data["Record"] = $queue;
      $buffer = array_merge ( ( is_array ( $buffer) ? $buffer : array ()), array ( $data));
    }
  }
  return $buffer;
}

/**
 * Function to count how many queues are allocated.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function count_queues ( $buffer, $parameters)
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
    $where .= " AND `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["text"])))) . "%'";
  }
  if ( array_key_exists ( "range", $parameters))
  {
    $where .= " AND `Range` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["range"]);
  }

  /**
   * Count allocated queues
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `Queues`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    return $buffer;
  }
  $data = array ( "Queues" => intval ( $count->fetch_assoc ()["Total"]));

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
