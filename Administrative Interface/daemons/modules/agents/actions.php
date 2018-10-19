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
 * VoIP Domain agents actions module. This module add the Asterisk client actions
 * calls related to agents.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Agents
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "createagent", "agents_create");
framework_add_hook ( "changeagent", "agents_change");
framework_add_hook ( "removeagent", "agents_remove");

/**
 * Function to create a new agent.
 * Required parameters are: Code, Name
 * Possible results:
 *   - 200: OK, agent created
 *   - 400: Agent already exist
 *   - 401: Invalid parameters
 *   - 500: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_create ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Code", $parameters) || ! array_key_exists ( "Name", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if agent exist
  if ( file_exists ( $_in["general"]["confdir"] . "/agent-" . (int) $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Agent already exist.");
  }

  // Create file structure
  $agent = "[" . (int) $parameters["Code"] . "]\nfullname=" . $parameters["Name"] . "\n";

  // Write agent file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/agent-" . (int) $parameters["Code"] . ".conf", $agent))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "module reload app_agent_pool.so");

  // Finish event
  return array ( "code" => 200, "message" => "Agent created.");
}

/**
 * Function to change an existing agent.
 * Required parameters are: Code, Name, NewCode
 * Possible results:
 *   - 200: OK, agent changed
 *   - 400: Agent doesn't exist
 *   - 401: Invalid parameters
 *   - 402: Agent new code already exist
 *   - 500: Error removing file
 *   - 501: Error writing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_change ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Code", $parameters) || ! array_key_exists ( "NewCode", $parameters) || ! array_key_exists ( "Name", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if agent exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/agent-" . (int) $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Agent doesn't exist.");
  }

  // Verify if new agent exist
  if ( (int) $parameters["Code"] != (int) $parameters["NewCode"] && file_exists ( $_in["general"]["confdir"] . "/agent-" . (int) $parameters["NewCode"] . ".conf"))
  {
    return array ( "code" => 402, "message" => "Agent new code already exist.");
  }

  // Remove agent file
  if ( ! unlink ( $_in["general"]["confdir"] . "/agent-" . (int) $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $agent = "[" . (int) $parameters["NewCode"] . "]\nfullname=" . $parameters["Name"] . "\n";

  // Write agent file
  if ( ! file_put_contents ( $_in["general"]["confdir"] . "/agent-" . (int) $parameters["NewCode"] . ".conf", $agent))
  {
    return array ( "code" => 501, "message" => "Error writing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "module reload app_agent_pool.so");

  // Finish event
  return array ( "code" => 200, "message" => "Agent changed.");
}

/**
 * Function to remove an existing agent.
 * Required parameters are: Code
 * Possible results:
 *   - 200: OK, agent removed
 *   - 400: Agent doesn't exist
 *   - 401: Invalid parameters
 *   - 500: Error removing file
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_remove ( $buffer, $parameters)
{
  global $_in;

  // Check if basic parameters was provided
  if ( ! array_key_exists ( "Code", $parameters))
  {
    return array ( "code" => 401, "message" => "Invalid parameters.");
  }

  // Verify if agent exist
  if ( ! file_exists ( $_in["general"]["confdir"] . "/agent-" . (int) $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 400, "message" => "Agent doesn't exist.");
  }

  // Remove agent file
  if ( ! unlink ( $_in["general"]["confdir"] . "/agent-" . (int) $parameters["Code"] . ".conf"))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk dialplan
  asterisk_exec ( "module reload app_agent_pool.so");

  // Finish event
  return array ( "code" => 200, "message" => "Agent removed.");
}
?>
