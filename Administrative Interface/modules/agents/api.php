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
 * VoIP Domain agents module API. This module add the API calls related to
 * agents.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Agents
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search agents
 */
framework_add_hook (
  "agents_search",
  "agents_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all agents."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Name,Code",
          "example" => "Name,Code"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system call center agents."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "agent"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The call center agent internal system unique identifier."),
                "example" => 1
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the system call center agent."),
                "example" => __ ( "John Doe")
              ),
              "Code" => array (
                "type" => "string",
                "description" => __ ( "A four digit number of the agent."),
                "pattern" => "/^[0-9]{4}$/",
                "example" => "1234"
              )
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Code must have four digit numbers.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "agents_search", __ ( "Search agents"));
framework_add_api_call (
  "/agents",
  "Read",
  "agents_search",
  array (
    "permissions" => array ( "user", "agents_search"),
    "title" => __ ( "Search agents"),
    "description" => __ ( "Search for system call center agents.")
  )
);

/**
 * Function to search agents.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agents_search_start"))
  {
    $parameters = framework_call ( "agents_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Agents");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( array_key_exists ( "Code", $parameters) && ! preg_match ( "^[0-9]{4}$", $paramters["Code"]))
  {
    $data["Code"] = __ ( "Code must have four digit numbers.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "agents_search_validate"))
  {
    $data = framework_call ( "agents_search_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "agents_search_sanitize"))
  {
    $parameters = framework_call ( "agents_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agents_search_pre"))
  {
    $parameters = framework_call ( "agents_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search agents
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Name`, `Code` FROM `Agents`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Code` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Name`, `Code`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Name,Code", "ID,Name,Code");
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agents_search_post"))
  {
    $data = framework_call ( "agents_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agents_search_finish"))
  {
    framework_call ( "agents_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get agent information
 */
framework_add_hook (
  "agents_view",
  "agents_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the call center agent."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "integer",
              "description" => __ ( "The call center agent internal system unique identifier."),
              "example" => 1
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name of the system call center agent."),
              "example" => __ ( "John Doe")
            ),
            "Code" => array (
              "type" => "string",
              "description" => __ ( "A four digit number of the agent."),
              "pattern" => "/^[0-9]{4}$/",
              "example" => "1234"
            ),
            "Password" => array (
              "type" => "string",
              "format" => "password",
              "description" => __ ( "A six digit password of the agent."),
              "pattern" => "/^[0-9]{6}$/",
              "example" => "123456"
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid agent ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "agents_view", __ ( "View agent information"));
framework_add_api_call (
  "/agents/:ID",
  "Read",
  "agents_view",
  array (
    "permissions" => array ( "user", "agents_view"),
    "title" => __ ( "View agents"),
    "description" => __ ( "Get a call center agent information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The call center agent internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate agent information.
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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agents_view_start"))
  {
    $parameters = framework_call ( "agents_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Agents");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid agent ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "agents_view_validate"))
  {
    $data = framework_call ( "agents_view_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "agents_view_sanitize"))
  {
    $parameters = framework_call ( "agents_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agents_view_pre"))
  {
    $parameters = framework_call ( "agents_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search agents
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $agent = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = api_filter_entry ( array ( "ID", "Name", "Code", "Password"), $agent);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agents_view_post"))
  {
    $data = framework_call ( "agents_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agents_view_finish"))
  {
    framework_call ( "agents_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new agent
 */
framework_add_hook (
  "agents_add",
  "agents_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the system call center agent."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Code" => array (
          "type" => "string",
          "description" => __ ( "A four digit system call center agent identifier."),
          "pattern" => "/^[0-9]{4}$/",
          "required" => true,
          "example" => "1234"
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "A six digit system call center agent password."),
          "pattern" => "/^[0-9]{6}$/",
          "required" => true,
          "example" => "123456"
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system call center agent added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The agent name is required.")
            ),
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The agent code is required.")
            ),
            "Password" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The agent password must have 6 digits.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "agents_add", __ ( "Add agents"));
framework_add_api_call (
  "/agents",
  "Create",
  "agents_add",
  array (
    "permissions" => array ( "user", "agents_add"),
    "title" => __ ( "Add agents"),
    "description" => __ ( "Add a new system call center agent.")
  )
);

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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agents_add_start"))
  {
    $parameters = framework_call ( "agents_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The agent name is required.");
  }
  if ( ! $parameters["Code"])
  {
    $data["Code"] = __ ( "The agent code is required.");
  }
  if ( ! array_key_exists ( "Code", $data) && ! preg_match ( "/^[0-9]{4}$/", $parameters["Code"]))
  {
    $data["Code"] = __ ( "The agent code must have 4 digits.");
  }
  if ( ! $parameters["Password"])
  {
    $data["Password"] = __ ( "The agent password is required.");
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[0-9]{6}$/", $parameters["Password"]))
  {
    $data["Password"] = __ ( "The agent password must have 6 digits.");
  }

  /**
   * Check if code was already added
   */
  if ( ! array_key_exists ( "Code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Code"] = __ ( "The provided code was already in use.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "agents_add_validate"))
  {
    $data = framework_call ( "agents_add_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "agents_add_sanitize"))
  {
    $parameters = framework_call ( "agents_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agents_add_pre"))
  {
    $parameters = framework_call ( "agents_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new agent record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Agents` (`Name`, `Code`, `Password`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Password"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agents_add_post"))
  {
    framework_call ( "agents_add_post", $parameters);
  }

  /**
   * Add new agent at Asterisk servers
   */
  $notify = array ( "Name" => $parameters["Name"], "Code" => $parameters["Code"], "Password" => $parameters["Password"]);
  if ( framework_has_hook ( "agents_add_notify"))
  {
    $notify = framework_call ( "agents_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "agent_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agents_add_finish"))
  {
    framework_call ( "agents_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/agents/" . $parameters["ID"]);
  return $buffer;
}

/**
 * API call to edit an existing agent
 */
framework_add_hook (
  "agents_edit",
  "agents_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the system call center agent."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Code" => array (
          "type" => "string",
          "description" => __ ( "A four digit system call center agent identifier."),
          "pattern" => "/^[0-9]{4}$/",
          "required" => true,
          "example" => "1234"
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "A six digit system call center agent password."),
          "pattern" => "/^[0-9]{6}$/",
          "required" => true,
          "example" => "123456"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system call center agent was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid agent ID.")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The agent name is required.")
            ),
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The agent code is required.")
            ),
            "Password" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The agent password must have 6 digits.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "agents_edit", __ ( "Edit agents"));
framework_add_api_call (
  "/agents/:ID",
  "Modify",
  "agents_edit",
  array (
    "permissions" => array ( "user", "agents_edit"),
    "title" => __ ( "Edit agents"),
    "description" => __ ( "Change a call center agent information.")
  )
);
framework_add_api_call (
  "/agents/:ID",
  "Edit",
  "agents_edit",
  array (
    "permissions" => array ( "user", "agents_edit"),
    "title" => __ ( "Edit agent"),
    "description" => __ ( "Change a call center agent information.")
  )
);

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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agents_edit_start"))
  {
    $parameters = framework_call ( "agents_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid agent ID.");
  }
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The agent name is required.");
  }
  if ( ! $parameters["Code"])
  {
    $data["Code"] = __ ( "The agent code is required.");
  }
  if ( ! array_key_exists ( "Code", $data) && ! preg_match ( "/^[0-9]{4}$/", $parameters["Code"]))
  {
    $data["Code"] = __ ( "The agent code must have 4 digits.");
  }
  if ( ! $parameters["Password"])
  {
    $data["Password"] = __ ( "The agent password is required.");
  }
  if ( ! array_key_exists ( "Password", $data) && ! preg_match ( "/^[0-9]{6}$/", $parameters["Password"]))
  {
    $data["Password"] = __ ( "The agent password must have 6 digits.");
  }

  /**
   * Check if code was already added
   */
  if ( ! array_key_exists ( "Code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Code"] = __ ( "The provided code was already in use.");
    }
  }

  /**
   * Check if agent exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $parameters["ORIGINAL"] = $result->fetch_assoc ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "agents_edit_validate"))
  {
    $data = framework_call ( "agents_edit_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "agents_edit_sanitize"))
  {
    $parameters = framework_call ( "agents_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agents_edit_pre"))
  {
    $parameters = framework_call ( "agents_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change agent record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Agents` SET `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "', `Password` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Password"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agents_edit_post"))
  {
    framework_call ( "agents_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Name" => $parameters["Name"], "Code" => $parameters["ORIGINAL"]["Code"], "NewCode" => $parameters["Code"], "Password" => $parameters["Password"]);
  if ( framework_has_hook ( "agents_edit_notify"))
  {
    $notify = framework_call ( "agents_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "agent_edit", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agents_edit_finish"))
  {
    framework_call ( "agents_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to remove an agent
 */
framework_add_hook (
  "agents_remove",
  "agents_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system call center agent was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid agent ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "agents_remove", __ ( "Remove agents"));
framework_add_api_call (
  "/agents/:ID",
  "Delete",
  "agents_remove",
  array (
    "permissions" => array ( "user", "agents_remove"),
    "title" => __ ( "Remove agents"),
    "description" => __ ( "Remove a call center agent from system.")
  )
);

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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "agents_remove_start"))
  {
    $parameters = framework_call ( "agents_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid agent ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "agents_remove_validate"))
  {
    $data = framework_call ( "agents_remove_validate", $parameters, false, $data);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "agents_remove_sanitize"))
  {
    $parameters = framework_call ( "agents_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if agent exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Agents` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $parameters["ORIGINAL"] = $result->fetch_assoc ();

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "agents_remove_pre"))
  {
    $parameters = framework_call ( "agents_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove agent database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Agents` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "agents_remove_post"))
  {
    framework_call ( "agents_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Code" => $parameters["ORIGINAL"]["Code"]);
  if ( framework_has_hook ( "agents_remove_notify"))
  {
    $notify = framework_call ( "agents_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "agent_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "agents_remove_finish"))
  {
    framework_call ( "agents_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "agents_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "agents_server_reconfig");

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
    $notify = array ( "Name" => $agent["Name"], "Code" => $agent["Code"], "Password" => $agent["Password"]);
    if ( framework_has_hook ( "agents_add_notify"))
    {
      $notify = framework_call ( "agents_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "agent_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
