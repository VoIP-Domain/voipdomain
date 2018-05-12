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
 * VoIP Domain servers actions module. This module add the Asterisk client actions
 * calls related to servers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Servers
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "createserver", "servers_create");
framework_add_hook ( "changeserver", "servers_change");
framework_add_hook ( "removeserver", "servers_remove");

/**
 * Function to create a new server.
 * Required parameters are: ID, Name, Address
 * Possible results:
 *   - 200: OK, server created
 *   - 400: Server already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "Address", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if server exist
  if ( file_exists ( $_in["general"]["confdir"] . "/sip-server-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Server already exist.");
  }

  // Create file structure
  $server = "[server_" . $parameters["ID"] . "]\n" .
             "type=peer\n" .
             "host=" . $parameters["Address"] . "\n" .
             "port=5060\n" .
             "directmedia=no\n" .
             "insecure=invite,port\n" .
             "description=" . $parameters["Name"] . "\n";

  // Write server file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/sip-server-" . (int) $parameters["ID"] . ".conf", $server))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Server created.");
}

/**
 * Function to change an existing server.
 * Required parameters are: ID, Name, Address
 * Possible results:
 *   - 200: OK, server changed
 *   - 400: Server doesn't exist
 *   - 401: Invalid parameters
 *   - 402: Server new number already exist
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "Address", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if server exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/sip-server-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Server doesn't exist.");
  }

  // Change server parameters
  $server = file_get_contents ( $_in["general"]["confdir"] . "/sip-server-" . (int) $parameters["ID"] . ".conf");
  if ( preg_match ( "/^host=(.*)$/m", $server))
  {
    $server = preg_replace ( "/(.*host=)(.*)(\n.*)/m", "\${1}" . $parameters["Address"] . "\${3}", $server);
  } else {
    $server .= "host=" . $parameters["Address"] . "\n";
  }
  if ( preg_match ( "/^description=(.*)$/m", $server))
  {
    $server = preg_replace ( "/(.*description=)(.*)(\n.*)/m", "\${1}" . $parameters["Name"] . "\${3}", $server);
  } else {
    $server .= "description=" . $parameters["Name"] . "\n";
  }

  // Write server file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/sip-server-" . (int) $parameters["ID"] . ".conf", $server))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Server changed.");
}

/**
 * Function to remove an existing server.
 * Required parameters are: ID
 * Possible results:
 *   - 200: OK, server removed
 *   - 400: Server doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if server exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/sip-server-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Server doesn't exist.");
  }

  // Remove server file
  if ( ! unlink ( $_in["general"]["confdir"] . "/sip-server-" . (int) $parameters["ID"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Server removed.");
}
?>
