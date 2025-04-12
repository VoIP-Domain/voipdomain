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
 * VoIP Domain configuration module filters. This module add the filter calls
 * related to global system configuration.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Configuration
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add configuration's filters
 */
framework_add_filter ( "page_menu_configurations", "config_menu");
framework_add_filter ( "page_menu_resources", "tools_menu");
framework_add_filter ( "audio_inuse", "config_moh");

/**
 * Function to add entries to configurations menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function config_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array (
    array ( "type" => "entry", "icon" => "asterisk", "href" => "/config", "text" => __ ( "General")),
    array ( "type" => "entry", "icon" => "puzzle-piece", "href" => "/config/plugins", "text" => __ ( "Plugins")),
    array ( "type" => "entry", "icon" => "user-shield", "href" => "/config/permissions", "text" => __ ( "Permissions")),
  ));
}

/**
 * Function to add entries to tools menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function tools_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array (
    array ( "type" => "entry", "icon" => "shield-alt", "href" => "/config/dns", "text" => __ ( "DNS checker"))
  ));
}

/**
 * Function to check if system is using specific audio id.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function config_moh ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Search profile using audio file
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` = 'MOH' AND `Data` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $data = array ();
  if ( $result->num_rows != 0)
  {
    $data[] = array ( "Type" => "Config", "ID" => 0);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
