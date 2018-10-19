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
 * VoIP Domain centrals actions module. This module add the Asterisk client actions
 * calls related to centrals.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Centrals
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "createcentral", "centrals_create");
framework_add_hook ( "changecentral", "centrals_change");
framework_add_hook ( "removecentral", "centrals_remove");

/**
 * Function to create a new central.
 * Required parameters are: Extension, Name, Extensions[]
 * Possible results:
 *   - 200: OK, central created
 *   - 400: Central already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "Extensions", $parameters) || ! is_array ( $parameters["Extensions"]))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if number is allocated exist
  if ( file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "0.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "1.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "2.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "3.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "4.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "5.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "6.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "7.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "8.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["Extension"] . "9.conf") || file_exists ( $_in["general"]["confdir"] . "/central-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Central already exist.");
  }

  // Write central file
  $dialstring = "";
  foreach ( $parameters["Extensions"] as $extension)
  {
    $dialstring .= "&LOCAL/" . (int) $extension . "@" . $_in["contexts"]["direct"];
  }
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/central-" . (int) $parameters["Extension"] . ".conf", "exten => " . (int) $parameters["Extension"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Central: " . $parameters["Name"] . "))\n same => n,Set(CDR(userfield)=central)\n same => n,Dial(" . substr ( $dialstring, 1) . ")\n"))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Central created.");
}

/**
 * Function to change an existing central.
 * Required parameters are: Extension, NewExtension, Name, Extensions[]
 * Possible results:
 *   - 200: OK, central changed
 *   - 400: Central doesn't exist
 *   - 401: Invalid parameters
 *   - 402: Central new number already exist
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters) || ! array_key_exists ( "NewExtension", $parameters) || ! array_key_exists ( "Name", $parameters) || ! array_key_exists ( "Extensions", $parameters) || ! is_array ( $parameters["Extensions"]))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if central exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/central-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Central doesn't exist.");
  }

  // Verify if new central number is in use
  if ( ( (int) $parameters["Extension"] != (int) $parameters["NewExtension"] && file_exists ( $_in["general"]["confdir"] . "/central-" . (int) $parameters["NewExtension"] . ".conf")) || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "0.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "1.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "2.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "3.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "4.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "5.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "6.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "7.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "8.conf") || file_exists ( $_in["general"]["confdir"] . "/sip-user-" . (int) $parameters["NewExtension"] . "9.conf"))
  {
    return array ( "code" => 402, "message" => "Central new number already exist.");
  }

  // Remove central file
  if ( ! unlink ( $_in["general"]["confdir"] . "/central-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Write central file
  $dialstring = "";
  foreach ( $parameters["Extensions"] as $extension)
  {
    $dialstring .= "&LOCAL/" . (int) $extension . "@" . $_in["contexts"]["direct"];
  }
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/central-" . (int) $parameters["NewExtension"] . ".conf", "exten => " . (int) $parameters["NewExtension"] . ",1,Verbose(1,\${STRFTIME(\${EPOCH},GMT+3,%c)}: New call from \${CALLERID(num)} to \${EXTEN} (Central: " . $parameters["Name"] . "))\n same => n,Set(CDR(userfield)=central)\n same => n,Dial(" . substr ( $dialstring, 1) . ")\n"))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Central changed.");
}

/**
 * Function to remove an existing central (global number black list).
 * Required parameters are: Extension
 * Possible results:
 *   - 200: OK, central removed
 *   - 400: Central doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Extension", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if central exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/central-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Central doesn't exist.");
  }

  // Remove central file
  if ( ! unlink ( $_in["general"]["confdir"] . "/central-" . (int) $parameters["Extension"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "dialplan reload");

  // Finish event
  return array ( "code" => 200, "message" => "Central removed.");
}
?>
