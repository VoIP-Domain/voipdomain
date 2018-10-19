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
 * VoIP Domain agents api module. This module add the api calls related to
 * agents.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Agents
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch agents listing
 */
framework_add_hook ( "agents_fetch", "agents_fetch");
framework_add_permission ( "agents_fetch", __ ( "Request agent listing"));
framework_add_api_call ( "/agents/fetch", "Read", "agents_fetch", array ( "permissions" => array ( "user", "agents_fetch")));

/**
 * Function to generate agent list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Agents");

  /**
   * Search agents
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create table structure
   */
  $data = array ();
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = array ( $result["ID"], $result["Name"], $result["Code"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get agent information
 */
framework_add_hook ( "agents_view", "agents_view");
framework_add_permission ( "agents_view", __ ( "View agent informations"));
framework_add_api_call ( "/agents/:id", "Read", "agents_view", array ( "permissions" => array ( "user", "agents_view")));

/**
 * Function to generate agent informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Agents");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search agents
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $agent = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["name"] = $agent["Name"];
  $data["code"] = $agent["Code"];
  $data["password"] = $agent["Password"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new agent
 */
framework_add_hook ( "agents_add", "agents_add");
framework_add_permission ( "agents_add", __ ( "Add agents"));
framework_add_api_call ( "/agents", "Create", "agents_add", array ( "permissions" => array ( "user", "agents_add")));

/**
 * Function to add a new agent.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["name"])));
  if ( empty ( $parameters["name"]))
  {
    $data["result"] = false;
    $data["name"] = __ ( "The agent name is required.");
  }
  $parameters["code"] = (int) $parameters["code"];
  if ( ! $parameters["code"])
  {
    $data["result"] = false;
    $data["code"] = __ ( "The agent code is required.");
  }
  $parameters["password"] = (int) $parameters["password"];
  if ( ! $parameters["password"])
  {
    $data["result"] = false;
    $data["password"] = __ ( "The agent password is required.");
  }
  if ( ! array_key_exists ( "password", $data) && strlen ( (string) $parameters["password"]) != 6)
  {
    $data["result"] = false;
    $data["password"] = __ ( "The agent password must have 6 digits.");
  }

  /**
   * Check if code was already added
   */
  if ( ! array_key_exists ( "code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["code"] = __ ( "The provided code was already in use.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "agents_add_sanitize"))
  {
    $data = framework_call ( "agents_add_sanitize", $parameters, false, $data);
  }

  /**
   * Return error data if some error ocurred
   */
  if ( $data["result"] == false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call add pre hook, if exist
   */
  if ( framework_has_hook ( "agents_add_pre"))
  {
    $parameters = framework_call ( "agents_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new agent record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Agents` (`Name`, `Code`, `Password`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["password"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "agents_add_post"))
  {
    framework_call ( "agents_add_post", $parameters);
  }

  /**
   * Add new agent at Asterisk servers
   */
  $notify = array ( "Name" => $parameters["name"], "Code" => $parameters["code"]);
  if ( framework_has_hook ( "agents_add_notify"))
  {
    $notify = framework_call ( "agents_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "createagent", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Name" => $parameters["name"], "Code" => $parameters["code"], "Password" => $parameters["password"]);
  if ( framework_has_hook ( "agents_add_audit"))
  {
    $audit = framework_call ( "agents_add_audit", $parameters, false, $audit);
  }
  audit ( "agent", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "agents/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing agent
 */
framework_add_hook ( "agents_edit", "agents_edit");
framework_add_permission ( "agents_edit", __ ( "Edit agents"));
framework_add_api_call ( "/agents/:id", "Modify", "agents_edit", array ( "permissions" => array ( "user", "agents_edit")));
framework_add_api_call ( "/agents/:id", "Edit", "agents_edit", array ( "permissions" => array ( "user", "agents_edit")));

/**
 * Function to edit an existing agent.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["name"])));
  if ( empty ( $parameters["name"]))
  {
    $data["result"] = false;
    $data["name"] = __ ( "The agent name is required.");
  }
  $parameters["code"] = (int) $parameters["code"];
  if ( ! $parameters["code"])
  {
    $data["result"] = false;
    $data["code"] = __ ( "The agent code is required.");
  }
  $parameters["password"] = (int) $parameters["password"];
  if ( ! $parameters["password"])
  {
    $data["result"] = false;
    $data["password"] = __ ( "The agent password is required.");
  }
  if ( ! array_key_exists ( "password", $data) && strlen ( (string) $parameters["password"]) != 6)
  {
    $data["result"] = false;
    $data["password"] = __ ( "The agent password must have 6 digits.");
  }

  /**
   * Check if code was already added
   */
  if ( ! array_key_exists ( "code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["code"] = __ ( "The provided code was already in use.");
    }
  }

  /**
   * Check if agent exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $agent = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "agents_edit_sanitize"))
  {
    $data = framework_call ( "agents_edit_sanitize", $parameters, false, $data);
  }

  /**
   * Return error data if some error ocurred
   */
  if ( $data["result"] == false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call edit pre hook, if exist
   */
  if ( framework_has_hook ( "agents_edit_pre"))
  {
    $parameters = framework_call ( "agents_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change agent record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Agents` SET `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', `Code` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . ", `Password` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["password"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "agents_edit_post"))
  {
    framework_call ( "agents_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Name" => $parameters["name"], "Code" => $agent["Code"], "NewCode" => $parameters["code"]);
  if ( framework_has_hook ( "agents_edit_notify"))
  {
    $notify = framework_call ( "agents_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "changeagent", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $parameters["id"];
  if ( $agent["Name"] != $parameters["name"])
  {
    $audit["Name"] = array ( "Old" => $agent["Name"], "New" => $parameters["name"]);
  }
  if ( $agent["Code"] != $parameters["code"])
  {
    $audit["Code"] = array ( "Old" => $agent["Code"], "New" => $parameters["code"]);
  }
  if ( $agent["Password"] != $parameters["password"])
  {
    $audit["Password"] = array ( "Old" => $agent["Password"], "New" => $parameters["password"]);
  }
  if ( framework_has_hook ( "agents_edit_audit"))
  {
    $audit = framework_call ( "agents_edit_audit", $parameters, false, $audit);
  }
  audit ( "agent", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a agent
 */
framework_add_hook ( "agents_remove", "agents_remove");
framework_add_permission ( "agents_remove", __ ( "Remove agents"));
framework_add_api_call ( "/agents/:id", "Delete", "agents_remove", array ( "permissions" => array ( "user", "agents_remove")));

/**
 * Function to remove an existing agent.
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

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if agent exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $agent = $result->fetch_assoc ();

  /**
   * Call remove pre hook, if exist
   */
  if ( framework_has_hook ( "agents_remove_pre"))
  {
    $parameters = framework_call ( "agents_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove agent database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Agents` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "agents_remove_post"))
  {
    framework_call ( "agents_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Code" => $agent["Code"]);
  if ( framework_has_hook ( "agents_remove_notify"))
  {
    $notify = framework_call ( "agents_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "removeagent", $notify);

  /**
   * Insert audit registry
   */
  $audit = $agent;
  if ( framework_has_hook ( "agents_remove_audit"))
  {
    $audit = framework_call ( "agents_remove_audit", $parameters, false, $audit);
  }
  audit ( "agent", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to intercept new server and server reinstall
 */
framework_add_hook ( "servers_add_post", "agents_server_reconfig");
framework_add_hook ( "servers_reinstall_config", "agents_server_reconfig");

/**
 * Function to notify server to include all agents.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all agents and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $agent = $result->fetch_assoc ())
  {
    $notify = array ( "Name" => $agent["Name"], "Code" => $agent["Code"]);
    if ( framework_has_hook ( "agents_add_notify"))
    {
      $notify = framework_call ( "agents_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["id"], "createagent", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
