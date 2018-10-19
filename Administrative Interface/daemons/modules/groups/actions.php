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
 * VoIP Domain groups actions module. This module add the Asterisk client actions
 * calls related to groups.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Groups
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "creategroup", "groups_create");
framework_add_hook ( "changegroup", "groups_change");
framework_add_hook ( "removegroup", "groups_remove");

/**
 * Function to create a new group.
 * Required parameters are: ID, Code, CostCenter
 * Possible results:
 *   - 200: OK, group created
 *   - 400: Group already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Code", $parameters) || ! array_key_exists ( "CostCenter", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if group exist
  if ( file_exists ( $_in["general"]["confdir"] . "/sip-group-" . $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Group already exist.");
  }

  // Create file structure
  $group = "[g_" . $parameters["Code"] . "](vd_g_default,!)\n" .
           "context=" . $_in["contexts"]["internal"] . "\n" .
           "namedcallgroup=" . $parameters["ID"] . "\n" .
           "namedpickupgroup=" . $parameters["ID"] . "\n" .
           "accountcode=" . $parameters["CostCenter"] . "\n";

  // Write group file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/sip-group-" . $parameters["Code"] . ".conf", $group))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Group created.");
}

/**
 * Function to change an existing group.
 * Required parameters are: ID, Code, NewCode, CostCenter
 * Possible results:
 *   - 200: OK, group changed
 *   - 400: Group doesn't exist
 *   - 401: Invalid parameters
 *   - 402: New group code already exist
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "ID", $parameters) || ! array_key_exists ( "Code", $parameters) || ! array_key_exists ( "NewCode", $parameters) || ! array_key_exists ( "CostCenter", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if group exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/sip-group-" . $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Group doesn't exist.");
  }

  // Verify if new group exist
  if ( $parameters["Code"] != $parameters["NewCode"] && file_exists ( $_in["general"]["confdir"] . "/sip-group-" . $parameters["NewCode"] . ".conf"))
  {
    return array ( "code" => 402, "message" => "New group code already exist.");
  }

  // Remove group file
  if ( ! unlink ( $_in["general"]["confdir"] . "/sip-group-" . $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $group = "[g_" . $parameters["NewCode"] . "](vd_g_default,!)\n" .
           "context=" . $_in["contexts"]["internal"] . "\n" .
           "namedcallgroup=" . $parameters["ID"] . "\n" .
           "namedpickupgroup=" . $parameters["ID"] . "\n" .
           "accountcode=" . $parameters["CostCenter"] . "\n";

  // Write group file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/sip-group-" . $parameters["NewCode"] . ".conf", $group))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Check all user accounts for group if changed
  if ( $parameters["Code"] != $parameters["NewCode"])
  {
    foreach ( glob ( $_in["general"]["confdir"] . "/sip-user-*.conf") as $filename)
    {
      $file = file_get_contents ( $filename);
      if ( preg_match ( "/[,|\(]g_" . $parameters["Code"] . "[,|\)]/m", $file))
      {
        $file = preg_replace ( "/(.*[,|\(]g_)(" . $parameters["Code"] . ")([,|\)].*)/m", "\${1}" . $parameters["NewCode"] . "\${3}", $file);
        if ( ! file_put_contents ( $filename, $file))
        {
          writeLog ( "changegroup - error updating user account (" . basename ( $filename) . ").", VoIP_LOG_WARNING);
        }
      }
    }
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Group changed.");
}

/**
 * Function to remove an existing group.
 * Required parameters are: Code
 * Possible results:
 *   - 200: OK, group removed
 *   - 400: Group doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Code", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if group exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/sip-group-" . $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Group doesn't exist.");
  }

  // Remove group file
  if ( ! unlink ( $_in["general"]["confdir"] . "/sip-group-" . $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk SIP configurations
  asterisk_exec ( "sip reload");

  // Finish event
  return array ( "code" => 200, "message" => "Group removed.");
}
?>
