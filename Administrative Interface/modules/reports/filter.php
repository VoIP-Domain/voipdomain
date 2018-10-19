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
 * VoIP Domain reports filter module. This module add the filter calls related
 * to reports.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Reports
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add report's filters
 */
framework_add_filter ( "page_menu_reports", "reports_menu");

/**
 * Function to add entries to reports menu.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function reports_menu ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array (
           array ( "type" => "submenu", "group" => "subs", "icon" => "cloud-upload-alt", "text" => __ ( "Calls made"), "entries" => array_merge ( array (
             array ( "type" => "entry", "icon" => "user", "href" => "/reports/made/user", "text" => __ ( "Per user")),
             array ( "type" => "entry", "icon" => "users", "href" => "/reports/made/group", "text" => __ ( "Per group")),
             array ( "type" => "entry", "icon" => "cloud-upload-alt", "href" => "/reports/made/gateway", "text" => __ ( "Per gateway")),
             array ( "type" => "entry", "icon" => "globe", "href" => "/reports/made/all", "text" => __ ( "All"))
           ), (array) filters_call ( "page_menu_reports_made"))),
           array ( "type" => "submenu", "group" => "subs", "icon" => "cloud-download-alt", "text" => __ ( "Calls received"), "entries" => array_merge ( array (
             array ( "type" => "entry", "icon" => "user", "href" => "/reports/received/user", "text" => __ ( "Per user")),
             array ( "type" => "entry", "icon" => "users", "href" => "/reports/received/group", "text" => __ ( "Per group")),
             array ( "type" => "entry", "icon" => "cloud-upload-alt", "href" => "/reports/received/gateway", "text" => __ ( "Per gateway")),
             array ( "type" => "entry", "icon" => "globe", "href" => "/reports/received/all", "text" => __ ( "All"))
           ), (array) filters_call ( "page_menu_reports_received"))),
           array ( "type" => "submenu", "group" => "subs", "icon" => "snowflake", "text" => __ ( "Consolidated"), "entries" => array_merge ( array (
             array ( "type" => "entry", "icon" => "user", "href" => "/reports/consolidated/user", "text" => __ ( "Per user")),
             array ( "type" => "entry", "icon" => "users", "href" => "/reports/consolidated/group", "text" => __ ( "Per group")),
             array ( "type" => "entry", "icon" => "cloud-upload-alt", "href" => "/reports/consolidated/gateway", "text" => __ ( "Per gateway"))
           ), (array) filters_call ( "page_menu_reports_consolidated"))),
           array ( "type" => "submenu", "group" => "subs", "icon" => "dollar-sign", "text" => __ ( "Financial"), "entries" => array_merge ( array (
             array ( "type" => "entry", "icon" => "dollar-sign", "href" => "/reports/financial/costcenter", "text" => __ ( "Cost Centers")),
             array ( "type" => "entry", "icon" => "users", "href" => "/reports/financial/group", "text" => __ ( "Groups")),
             array ( "type" => "entry", "icon" => "cloud-upload-alt", "href" => "/reports/financial/gateway", "text" => __ ( "Gateways"))
           ), (array) filters_call ( "page_menu_reports_financial"))),
           array ( "type" => "submenu", "group" => "subs", "icon" => "paint-brush", "text" => __ ( "Graphs"), "entries" => array_merge ( array (
             array ( "type" => "entry", "icon" => "globe", "href" => "/reports/graphs/server", "text" => __ ( "Per server")),
             array ( "type" => "entry", "icon" => "dollar-sign", "href" => "/reports/graphs/cost", "text" => __ ( "Costs")),
             array ( "type" => "entry", "icon" => "search", "href" => "/reports/graphs/monitor", "text" => __ ( "Monitory")),
             array ( "type" => "entry", "icon" => "fire", "href" => "/reports/graph/heat", "text" => __ ( "Heat map"))
           ), (array) filters_call ( "page_menu_reports_graph"))),
           array ( "type" => "entry", "group" => "listing", "icon" => "list", "href" => "/reports/list", "text" => __ ( "Extensions listing")),
           array ( "type" => "entry", "group" => "listing", "icon" => "random", "href" => "/reports/ranges", "text" => __ ( "Ranges listing")),
           array ( "type" => "entry", "group" => "listing", "icon" => "thermometer-empty", "href" => "/reports/activity", "text" => __ ( "Activity listing")),
           array ( "type" => "entry", "group" => "listing", "icon" => "heart", "href" => "/reports/status", "text" => __ ( "Server status"))
         ));
}
?>
