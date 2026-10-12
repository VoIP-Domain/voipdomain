<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * FastAGI gateways module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk FastAGI Agents Application
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Update gateway list from local data files
 *
 * @global array $_in Framework global configuration variable
 * @return null
 */
function update_gateways ()
{
  global $_in;

  if ( ! isset ( $_in["db"]["gateway"]) || ! is_array ( $_in["db"]["gateway"]) || sizeof ( $_in["db"]["gateway"]) == 0)
  {
    $_in["db"]["gateway"] = array ();
    foreach ( list_config ( "datafile", "gateway") as $filename)
    {
      $data = read_config ( "datafile", $filename);
      if ( $data === false || ! is_array ( $data))
      {
        writeLog ( "Cannot read gateway data file: " . $filename, VoIP_LOG_WARNING);
        continue;
      }
      if ( ! array_key_exists ( "ID", $data))
      {
        writeLog ( "Gateway data file missing ID: " . $filename, VoIP_LOG_WARNING);
        continue;
      }
      $_in["db"]["gateway"][$data["ID"]] = $data;
    }
    if ( sizeof ( $_in["db"]["gateway"]) == 0)
    {
      writeLog ( "No gateways found in local data files.", VoIP_LOG_NOTICE);
    }
  }
}

/**
 * Register gateways hooks
 */
framework_add_hook ( "gateways_parse_manual", "gateways_parse_manual");

/**
 * Function to parse manual configuration gateway.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_parse_manual ( $buffer, $parameters)
{
  // Process each gateway route:
  $newroutes = array ();
  foreach ( $parameters["Routes"] as $id => $route)
  {
    $routelen = 0;
    $group = false;
    $staticroute = true;
    $tmp = $route["Route"];
    for ( $x = 0; $x <= strlen ( $route["Route"]); $x++)
    {
      $char = ord ( substr ( $route["Route"], $x, 1));
      if ( $group)
      {
        if ( $char == 93)
        {
          $group = false;
        }
      } else {
        if ( $char == 43 || ( $char >= 48 && $char <= 57))
        {
          $routelen++;
        }
        if ( $char == 91 && ! $group)
        {
          $group = true;
          $staticroute = false;
          $routelen++;
        }
      }
    }
    $parameters["Routes"][$id]["static"] = $staticroute;
    $parameters["Routes"][$id]["length"] = $routelen;
    $newroutes[$routelen][] = $parameters["Routes"][$id];
  }
  krsort ( $newroutes);
  $parameters["Routes"] = array ();
  foreach ( $newroutes as $addroute)
  {
    $parameters["Routes"] = array_merge ( $parameters["Routes"], $addroute);
  }

  // Merge buffer and return data:
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $parameters);
}
?>
