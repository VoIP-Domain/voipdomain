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
 * VoIP Domain ranges actions module. This module add the Asterisk client actions
 * calls related to ranges.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Ranges
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "createrange", "ranges_create");
framework_add_hook ( "changerange", "ranges_change");
framework_add_hook ( "removerange", "ranges_remove");

/**
 * Function to create a new range.
 * Required parameters are: ID, Server, Start, Finish
 * Possible results:
 *   - 200: OK, range created
 *   - 400: Range already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Server", $parameters) || ! array_key_exists ( "Start", $parameters) || ! array_key_exists ( "Finish", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if range exist
  if ( file_exists ( $_in["general"]["confdir"] . "/range-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Range already exist.");
  }

  // Create file structure
  $range = "; Server " . (int) $parameters["Server"] . " range from " . (int) $parameters["Start"] . " to " . (int) $parameters["Finish"] . "\n" .
           " same => n,ExecIf(\$[\$[\"\${SERVER}\" != \"" . (int) $parameters["Server"] . "\"] & \$[\"\${ARG1}\" >= \"" . (int) $parameters["Start"] . "\"] & \$[\"\${ARG1}\" <= \"" . (int) $parameters["Finish"] . "\"]]?Dial(SIP/\${ARG1}@server_" . (int) $parameters["Server"] . "))\n";

  // Write range file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/range-" . (int) $parameters["ID"] . ".conf", $range))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Range created.");
}

/**
 * Function to change an existing range.
 * Required parameters are: ID, Server, Start, Finish
 * Possible results:
 *   - 200: OK, range changed
 *   - 400: Range doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Server", $parameters) || ! array_key_exists ( "Start", $parameters) || ! array_key_exists ( "Finish", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if range exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/range-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Range doesn't exist.");
  }

  // Remove range file
  if ( ! unlink ( $_in["general"]["confdir"] . "/range-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $range = "; Server " . (int) $parameters["Server"] . " range from " . (int) $parameters["Start"] . " to " . (int) $parameters["Finish"] . "\n" .
           " same => n,ExecIf(\$[\$[\"\${SERVER}\" != \"" . (int) $parameters["Server"] . "\"] & \$[\"\${ARG1}\" >= \"" . (int) $parameters["Start"] . "\"] & \$[\"\${ARG1}\" <= \"" . (int) $parameters["Finish"] . "\"]]?Dial(SIP/\${ARG1}@server_" . (int) $parameters["Server"] . "))\n";

  // Write range file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/range-" . (int) $parameters["ID"] . ".conf", $range))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Range changed.");
}

/**
 * Function to remove an existing range.
 * Required parameters are: ID
 * Possible results:
 *   - 200: OK, range removed
 *   - 400: Range doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if range exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/range-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Range doesn't exist.");
  }

  // Remove range file
  if ( ! unlink ( $_in["general"]["confdir"] . "/range-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Range removed.");
}
?>
