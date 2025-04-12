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
 * VoIP Domain agents actions module. This module add the Asterisk client actions
 * calls related to agents.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Agents
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call's hook's
 */
framework_add_hook ( "agent_add", "agent_add");
framework_add_hook ( "agent_change", "agent_change");
framework_add_hook ( "agent_remove", "agent_remove");

/**
 * Cleanup functions
 */
framework_add_hook ( "agent_wipe", "agent_wipe");
cleanup_register ( "Agents", "agent_wipe");

/**
 * Function to create a new agent.
 * Required parameters are: (string) Code, (string) Name, (string) Password
 * Possible results:
 *   - 200: OK, agent created (overwritten)
 *   - 201: OK, agent created
 *   - 400: Agent already exist
 *   - 406: Invalid parameters
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function agent_add ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agent_add_start"))
  {
    $parameters = framework_call ( "agent_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Code", $parameters))
  {
    $data["Code"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Code", $data) && ! preg_match ( "/^[0-9]{4}$/", $parameters["Code"]))
  {
    $data["Code"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[0-9]{6}$/", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "agent_add_validate"))
  {
    $data = framework_call ( "agent_add_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "agent_add_sanitize"))
  {
    $parameters = framework_call ( "agent_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agent_add_pre"))
  {
    $parameters = framework_call ( "agent_add_pre", $parameters, false, $parameters);
  }

  // Verify if agent exist
  if ( check_config ( "config", "agent-" . $parameters["Code"]))
  {
    if ( ! check_overwrite ())
    {
      return array ( "code" => 400, "message" => "Agent already exist.");
    } else {
      $overwritten = true;
    }
  } else {
    $overwritten = false;
  }

  // Create file structure
  $agent = "[" . $parameters["Code"] . "]\n" .
           "fullname=" . $parameters["Name"] . "\n";

  // Write agent file
  if ( ! write_config ( "config", "agent-" . $parameters["Code"], $agent))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Write dialplan database file
  if ( ! write_config ( "configdb", "agent-" . $parameters["Code"], array ( "name" => "agent_" . $parameters["Code"], "data" => array ( "name" => $parameters["Name"], "password" => $parameters["Password"]))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create agent data file
  if ( ! write_config ( "datafile", "agent-" . $parameters["Code"], $parameters))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agent_add_post") && ! framework_call ( "agent_add_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk agents and dialplan
  asterisk_exec ( "module reload app_agent_pool.so");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agent_add_finish"))
  {
    framework_call ( "agent_add_finish", $parameters);
  }

  // Finish event
  if ( $overwritten)
  {
    return array ( "code" => 200, "message" => "Agent created (overwritten).");
  } else {
    return array ( "code" => 201, "message" => "Agent created.");
  }
}

/**
 * Function to change an existing agent.
 * Required parameters are: (string) Code, (string) Name, (string) NewCode, (string) Password
 * Possible results:
 *   - 200: OK, agent changed
 *   - 404: Agent doesn't exist
 *   - 406: Invalid parameters
 *   - 409: Agent new code already exist
 *   - 500: Error removing file
 *   - 500: Error writing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function agent_change ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agent_change_start"))
  {
    $parameters = framework_call ( "agent_change_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Code", $parameters))
  {
    $data["Code"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Code", $data) && ! preg_match ( "/^[0-9]{4}$/", $parameters["Code"]))
  {
    $data["Code"] = "Invalid content";
  }
  if ( ! array_key_exists ( "NewCode", $parameters))
  {
    $data["NewCode"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "NewCode", $data) && ! preg_match ( "/^[0-9]{4}$/", $parameters["NewCode"]))
  {
    $data["NewCode"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Name", $parameters))
  {
    $data["Name"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Name", $data) && ! preg_match ( "/^[^\x01-\x1E]*$/u", $parameters["Name"]))
  {
    $data["Name"] = "Invalid content";
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[0-9]{6}$/", $parameters["Password"]))
  {
    $data["Password"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "agent_change_validate"))
  {
    $data = framework_call ( "agent_change_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "agent_change_sanitize"))
  {
    $parameters = framework_call ( "agent_change_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agent_change_pre"))
  {
    $parameters = framework_call ( "agent_change_pre", $parameters, false, $parameters);
  }

  // Verify if agent exist
  if ( ! check_config ( "config", "agent-" . $parameters["Code"]))
  {
    return array ( "code" => 404, "message" => "Agent doesn't exist.");
  }

  // Verify if new agent exist
  if ( $parameters["Code"] != $parameters["NewCode"] && check_config ( "config", "agent-" . $parameters["NewCode"]))
  {
    return array ( "code" => 409, "message" => "Agent new code already exist.");
  }

  // Remove agent files
  if ( ! unlink_config ( "config", "agent-" . $parameters["Code"]) || ! unlink_config ( "datafile", "agent-" . $parameters["Code"]) || ! unlink_config ( "configdb", "agent-" . $parameters["Code"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Create file structure
  $agent = "[" . $parameters["NewCode"] . "]\n" .
           "fullname=" . $parameters["Name"] . "\n";

  // Write agent file
  if ( ! config_write ( "config", "agent-" . $parameters["NewCode"], $agent))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Write dialplan database file
  if ( ! config_write ( "configdb", "agent-" . $parameters["NewCode"], array ( "name" => "agent_" . $parameters["NewCode"], "data" => array ( "name" => $parameters["Name"], "password" => $parameters["Password"]))))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Create agent data file
  $agentdata = $parameters;
  $agentdata["Code"] = $agentdata["NewCode"];
  unset ( $agentdata["NewCode"]);
  if ( ! write_config ( "datafile", "agent-" . $agentdata["Code"], $agentdata))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agent_change_post") && ! framework_call ( "agent_change_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error writing file.");
  }

  // Reload Asterisk agents and dialplan
  asterisk_exec ( "module reload app_agent_pool.so");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agent_change_finish"))
  {
    framework_call ( "agent_change_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Agent changed.");
}

/**
 * Function to remove an existing agent.
 * Required parameters are: (string) Code
 * Possible results:
 *   - 200: OK, agent removed
 *   - 404: Agent doesn't exist
 *   - 406: Invalid parameters
 *   - 500: Error removing file
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of the operation array ( "code" => _CODE_, "message" =>
 *               _MESSAGE_)
 */
function agent_remove ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agent_remove_start"))
  {
    $parameters = framework_call ( "agent_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Code", $parameters))
  {
    $data["Code"] = "Missing parameter";
  }
  if ( ! array_key_exists ( "Code", $data) && ! preg_match ( "/^[0-9]{4}$/", $parameters["Code"]))
  {
    $data["Code"] = "Invalid content";
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "agent_remove_validate"))
  {
    $data = framework_call ( "agent_remove_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    return array ( "code" => 406, "message" => "Invalid parameters.", "content" => $data);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "agent_remove_sanitize"))
  {
    $parameters = framework_call ( "agent_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agent_remove_pre"))
  {
    $parameters = framework_call ( "agent_remove_pre", $parameters, false, $parameters);
  }

  // Verify if agent exist
  if ( ! check_config ( "config", "agent-" . $parameters["Code"]))
  {
    return array ( "code" => 404, "message" => "Agent doesn't exist.");
  }

  // Remove agent files
  if ( ! unlink_config ( "config", "agent-" . $parameters["Code"]) || ! unlink_config ( "datafile", "agent-" . $parameters["Code"]) || ! unlink_config ( "configdb", "agent-" . $parameters["Code"]))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agent_remove_post") && ! framework_call ( "agent_remove_post", $parameters, false, true))
  {
    return array ( "code" => 500, "message" => "Error removing file.");
  }

  // Reload Asterisk agents and dialplan
  asterisk_exec ( "module reload app_agent_pool.so");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agent_remove_finish"))
  {
    framework_call ( "agent_remove_finish", $parameters);
  }

  // Finish event
  return array ( "code" => 200, "message" => "Agent removed.");
}

/**
 * Function to remove all existing agents configurations.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function agent_wipe ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agent_wipe_start"))
  {
    $parameters = framework_call ( "agent_wipe_start", $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agent_wipe_pre"))
  {
    framework_call ( "agent_wipe_pre", $parameters, false, true);
  }

  // Remove all files
  foreach ( list_config ( "datafile", "agent") as $filename)
  {
    $entryid = substr ( $filename, strrpos ( $filename, "-") + 1);
    unlink_config ( "config", "agent-" . $entryid);
    unlink_config ( "configdb", "agent-" . $entryid);
    unlink_config ( "datafile", $filename);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agent_wipe_post"))
  {
    framework_call ( "agent_wipe_post", $parameters, false, true);
  }

  // Reload Asterisk agents and dialplan
  asterisk_exec ( "module reload app_agent_pool.so");
  asterisk_exec ( "dialplan reload");

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agent_wipe_finish"))
  {
    framework_call ( "agent_wipe_finish", $parameters);
  }
}
?>
