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
 * VoIP Domain queues module API. This module add the API calls related to
 * queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search queues
 */
framework_add_hook (
  "queues_search",
  "queues_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all queues."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Name,Strategy,StrategyText",
          "example" => "Description,Name,Strategy"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system queues."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "queue"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the queue."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the queue."),
                "example" => __ ( "Help Desk")
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The queue name keywork of the queue."),
                "example" => __ ( "helpdesk")
              ),
              "Strategy" => array (
                "type" => "string",
                "enum" => array ( "ringall", "roundrobin", "leastrecent", "fewestcalls", "random", "rrmemory"),
                "description" => __ ( "The ring strategy of the queue."),
                "example" => "rrmemory"
              ),
              "StrategyText" => array (
                "type" => "string",
                "description" => __ ( "The ring strategy description of the queue."),
                "example" => __ ( "Round Robin Memory")
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
            "Filter" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid filter content.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "queues_search", __ ( "Search queues"));
framework_add_api_call (
  "/queues",
  "Read",
  "queues_search",
  array (
    "permissions" => array ( "user", "queues_search"),
    "title" => __ ( "Search queues"),
    "description" => __ ( "Search for system queues.")
  )
);

/**
 * Function to search queues.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queues_search_start"))
  {
    $parameters = framework_call ( "queues_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Queues");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queues_search_validate"))
  {
    $data = framework_call ( "queues_search_validate", $parameters);
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
  if ( framework_has_hook ( "queues_search_sanitize"))
  {
    $parameters = framework_call ( "queues_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queues_search_pre"))
  {
    $parameters = framework_call ( "queues_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search queues
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Name`, `Description`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Name,Strategy,StrategyText", "ID,Description,Name,Strategy,StrategyText");
  while ( $result = $results->fetch_assoc ())
  {
    $result["StrategyText"] = __ ( $_in["queuestypes"][$result["Strategy"]]);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queues_search_post"))
  {
    $data = framework_call ( "queues_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queues_search_finish"))
  {
    framework_call ( "queues_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get queue information
 */
framework_add_hook (
  "queues_view",
  "queues_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the queue."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the queue."),
              "example" => __ ( "Help Desk")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The queue name keywork of the queue."),
              "example" => __ ( "helpdesk")
            ),
            "Strategy" => array (
              "type" => "string",
              "enum" => array ( "ringall", "roundrobin", "leastrecent", "fewestcalls", "random", "rrmemory"),
              "description" => __ ( "The ring strategy of the queue."),
              "example" => "rrmemory"
            ),
            "StrategyText" => array (
              "type" => "string",
              "description" => __ ( "The ring strategy description of the queue."),
              "example" => __ ( "Round Robin Memory")
            ),
            "StrategyTextEN" => array (
              "type" => "string",
              "description" => __ ( "The ring strategy description of the queue in English."),
              "example" => __ ( "Round Robin Memory")
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
              "example" => __ ( "Invalid queue ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "queues_view", __ ( "View queue information"));
framework_add_api_call (
  "/queues/:ID",
  "Read",
  "queues_view",
  array (
    "permissions" => array ( "user", "queues_view"),
    "title" => __ ( "View queues"),
    "description" => __ ( "Get a queue information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The queue internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate queue information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queues_view_start"))
  {
    $parameters = framework_call ( "queues_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Queues");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid queue ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queues_view_validate"))
  {
    $data = framework_call ( "queues_view_validate", $parameters);
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
  if ( framework_has_hook ( "queues_view_sanitize"))
  {
    $parameters = framework_call ( "queues_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queues_view_pre"))
  {
    $parameters = framework_call ( "queues_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search queues
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $queue = $result->fetch_assoc ();

  /**
   * Format data
   */
  $queue["StrategyTextEN"] = $_in["queuestypes"][$queue["Strategy"]];
  $queue["StrategyText"] = __ ( $data["StrategyTextEN"]);
  $data = api_filter_entry ( array ( "Description", "Name", "Strategy", "StrategyTextEN", "StrategyText"), $queue);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queues_view_post"))
  {
    $data = framework_call ( "queues_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queues_view_finish"))
  {
    framework_call ( "queues_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new queue
 */
framework_add_hook (
  "queues_add",
  "queues_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the queue."),
          "required" => true,
          "example" => __ ( "Help Desk")
        ),
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name keyword of the queue."),
          "pattern" => "/^[a-z0-9\-\.]$/",
          "required" => true,
          "example" => __ ( "helpdesk")
        ),
        "Strategy" => array (
          "type" => "string",
          "enum" => array ( "ringall", "roundrobin", "leastrecent", "fewestcalls", "random", "rrmemory"),
          "description" => __ ( "The ring strategy of the queue."),
          "required" => true,
          "example" => "rrmemory"
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New queue added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The description is required.")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The name could only contain lower case characters, numbers, hifen and dot.")
            ),
            "Strategy" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected strategy is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "queues_add", __ ( "Add queues"));
framework_add_api_call (
  "/queues",
  "Create",
  "queues_add",
  array (
    "permissions" => array ( "user", "queues_add"),
    "title" => __ ( "Add queues"),
    "description" => __ ( "Add a new queue.")
  )
);

/**
 * Function to add a new queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queues_add_start"))
  {
    $parameters = framework_call ( "queues_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The description is required.");
  }
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name is required.");
  }
  if ( ! array_key_exists ( "Name", $data) && $parameters["Name"] != preg_replace ( "/[^a-z0-9\-\.]/", "", $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name could only contain lower case characters, numbers, hifen and dot.");
  }
  if ( empty ( $parameters["Strategy"]))
  {
    $data["Strategy"] = __ ( "The strategy is required.");
  }
  if ( ! array_key_exists ( "Strategy", $data) && ! array_key_exists ( $parameters["Strategy"], $_in["queuestypes"]))
  {
    $data["Strategy"] = __ ( "The selected strategy is invalid.");
  }

  /**
   * Check if queue was already added
   */
  if ( ! array_key_exists ( "Name", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Name"] = __ ( "The provided name was already in use.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queues_add_validate"))
  {
    $data = framework_call ( "queues_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "queues_add_sanitize"))
  {
    $parameters = framework_call ( "queues_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queues_add_pre"))
  {
    $parameters = framework_call ( "queues_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new queue record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Queues` (`Description`, `Name`, `Strategy`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Strategy"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queues_add_post"))
  {
    framework_call ( "queues_add_post", $parameters);
  }

  /**
   * Add new queue at Asterisk server
   */
  $notify = array ( "Description" => $parameters["Description"], "Name" => $parameters["Name"], "Strategy" => $parameters["Strategy"]);
  if ( framework_has_hook ( "queues_add_notify"))
  {
    $notify = framework_call ( "queues_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "queue_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queues_add_finish"))
  {
    framework_call ( "queues_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/queues/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing queue
 */
framework_add_hook (
  "queues_edit",
  "queues_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the queue."),
          "required" => true,
          "example" => __ ( "Help Desk")
        ),
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name keyword of the queue."),
          "pattern" => "/^[a-z0-9\-\.]$/",
          "required" => true,
          "example" => __ ( "helpdesk")
        ),
        "Strategy" => array (
          "type" => "string",
          "enum" => array ( "ringall", "roundrobin", "leastrecent", "fewestcalls", "random", "rrmemory"),
          "description" => __ ( "The ring strategy of the queue."),
          "required" => true,
          "example" => "rrmemory"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The queue was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The description is required.")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The name could only contain lower case characters, numbers, hifen and dot.")
            ),
            "Strategy" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected strategy is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "queues_edit", __ ( "Edit queues"));
framework_add_api_call (
  "/queues/:ID",
  "Modify",
  "queues_edit",
  array (
    "permissions" => array ( "user", "queues_edit"),
    "title" => __ ( "Edit queues"),
    "description" => __ ( "Change a queue information.")
  )
);
framework_add_api_call (
  "/queues/:ID",
  "Edit",
  "queues_edit",
  array (
    "permissions" => array ( "user", "queues_edit"),
    "title" => __ ( "Edit queues"),
    "description" => __ ( "Change a queue information.")
  )
);

/**
 * Function to edit an existing queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queues_edit_start"))
  {
    $parameters = framework_call ( "queues_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The queue description is required.");
  }
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name is required.");
  }
  if ( ! array_key_exists ( "Name", $data) && $parameters["Name"] != preg_replace ( "/[^a-z0-9\-\.]/", "", $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name could only contain lower case characters, numbers, hifen and dot.");
  }
  if ( empty ( $parameters["Strategy"]))
  {
    $data["Strategy"] = __ ( "The strategy is required.");
  }
  if ( ! array_key_exists ( "Strategy", $data) && ! array_key_exists ( $parameters["Strategy"], $_in["queuestypes"]))
  {
    $data["Strategy"] = __ ( "The selected strategy is invalid.");
  }

  /**
   * Check if queue was already added
   */
  if ( ! array_key_exists ( "Name", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Name"] = __ ( "The provided name was already in use.");
    }
  }

  /**
   * Check if queue exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
  if ( framework_has_hook ( "queues_edit_validate"))
  {
    $data = framework_call ( "queues_edit_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "queues_edit_sanitize"))
  {
    $parameters = framework_call ( "queues_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queues_edit_pre"))
  {
    $parameters = framework_call ( "queues_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change queue record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Queues` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', `Strategy` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Strategy"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queues_edit_post"))
  {
    framework_call ( "queues_edit_post", $parameters);
  }

  /**
   * Notify server about change
   */
  $notify = array ( "OldName" => $parameters["ORIGINAL"]["Name"], "Name" => $parameters["Name"], "Description" => $parameters["Description"], "Strategy" => $parameters["Strategy"]);
  if ( framework_has_hook ( "queues_edit_notify"))
  {
    $notify = framework_call ( "queues_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "queue_change", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queues_edit_finish"))
  {
    framework_call ( "queues_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a queue
 */
framework_add_hook (
  "queues_remove",
  "queues_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The queue was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid queue ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "queues_remove", __ ( "Remove queues"));
framework_add_api_call (
  "/queues/:ID",
  "Delete",
  "queues_remove",
  array (
    "permissions" => array ( "user", "queues_remove"),
    "title" => __ ( "Remove queues"),
    "description" => __ ( "Remove a queue from system.")
  )
);

/**
 * Function to remove an existing queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queues_remove_start"))
  {
    $parameters = framework_call ( "queues_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid queue ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queues_remove_validate"))
  {
    $data = framework_call ( "queues_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "queues_remove_sanitize"))
  {
    $parameters = framework_call ( "queues_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
   * Check if queue was in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionQueue` WHERE `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queues_remove_pre"))
  {
    $parameters = framework_call ( "queues_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove queue database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queues_remove_post"))
  {
    framework_call ( "queues_remove_post", $parameters);
  }

  /**
   * Notify server about change
   */
  $notify = array ( "Name" => $parameters["ORIGINAL"]["Name"]);
  if ( framework_has_hook ( "queues_remove_notify"))
  {
    $notify = framework_call ( "queues_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "queue_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queues_remove_finish"))
  {
    framework_call ( "queues_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to join a member into queue
 */
framework_add_hook (
  "queues_join_member",
  "queues_join_member",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "The agent was sucessfully added to the queue.")
      ),
      201 => array (),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Queue" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided queue doesn't exist.")
            ),
            "Agent" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided agent doesn't exist.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "queues_join_member", __ ( "Join member to queues"));
framework_add_api_call (
  "/queues/:Queue/join/:Agent",
  "Create",
  "queues_join_member",
  array (
    "permissions" => array ( "user", "queues_join_member"),
    "title" => __ ( "Join member to queues"),
    "description" => __ ( "Join a system agent member to a queue."),
    "parameters" => array (
      array (
        "name" => "Queue",
        "type" => "integer",
        "description" => __ ( "The queue internal system unique identifier."),
        "example" => 1
      ),
      array (
        "name" => "Agent",
        "type" => "integer",
        "description" => __ ( "The agent internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to join a member into queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_join_member ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queues_join_member_start"))
  {
    $parameters = framework_call ( "queues_join_member_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["Queue"] = __ ( "The provided queue doesn't exist.");
  } else {
    $queue = $result->fetch_assoc ();
  }

  /**
   * Check if agent exists
   */
  $agent = filters_call ( "get_agents", array ( "code" => (int) $parameters["Agent"]));
  if ( sizeof ( $Agent) != 1)
  {
    $data["Agent"] = __ ( "The provided agent doesn't exist.");
  }

  /**
   * Check if member isn't already logged at queue
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `QueuesLogged` WHERE `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"]) . " AND `Member` = '" . $_in["mysql"]["id"]->real_escape_string ( "Agent/" . (int) $parameters["Agent"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Queue"] = __ ( "The provided agent is already logged at this queue.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queues_join_member_validate"))
  {
    $data = framework_call ( "queues_join_member_validate", $parameters);
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
  $parameters["Queue"] = (int) $parameters["Queue"];
  $parameters["Agent"] = (int) $parameters["Agent"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queues_join_member_sanitize"))
  {
    $parameters = framework_call ( "queues_join_member_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queues_join_member_pre"))
  {
    $parameters = framework_call ( "queues_join_member_pre", $parameters, false, $parameters);
  }

  /**
   * Add database registry
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `QueueMembers` (`Queue`, `Member`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Queue"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( "Agent/" . $parameters["Agent"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queues_join_member_post"))
  {
    $data = framework_call ( "queues_join_member_post", $parameters, false, $data);
  }

  /**
   * Notify server to join member
   */
  $notify = array ( "Queue" => $queue["Name"], "Member" => "Agent/" . $parameters["Agent"]);
  if ( framework_has_hook ( "queues_join_member_notify"))
  {
    $notify = framework_call ( "queues_join_member_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "queue_join_member", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queues_join_member_finish"))
  {
    framework_call ( "queues_join_member_finish", $parameters);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to member leave from a queue
 */
framework_add_hook (
  "queues_leave_member",
  "queues_leave_member",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "The agent was sucessfully removed from the queue.")
      ),
      201 => array (),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Queue" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided queue doesn't exist.")
            ),
            "Agent" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided agent doesn't exist.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "queues_leave_member", __ ( "Member leave from queues"));
framework_add_api_call (
  "/queues/:Queue/leave/:Agent",
  "Create",
  "queues_leave_member",
  array (
    "permissions" => array ( "user", "queues_leave_member"),
    "title" => __ ( "Remove member from queues"),
    "description" => __ ( "Remove a system agent member from a queue."),
    "parameters" => array (
      array (
        "name" => "Queue",
        "type" => "integer",
        "description" => __ ( "The queue internal system unique identifier."),
        "example" => 1
      ),
      array (
        "name" => "Agent",
        "type" => "integer",
        "description" => __ ( "The agent internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to member leave from queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_leave_member ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queues_leave_member_start"))
  {
    $parameters = framework_call ( "queues_leave_member_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["Queue"] = __ ( "The provided queue doesn't exist.");
  } else {
    $queue = $result->fetch_assoc ();
  }

  /**
   * Check if agent exists
   */
  $agent = filters_call ( "get_agents", array ( "code" => (int) $parameters["Agent"]));
  if ( sizeof ( $agent) != 1)
  {
    $data["Agent"] = __ ( "The provided agent doesn't exist.");
  }

  /**
   * Check if member is logged at queue
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `QueuesLogged` WHERE `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"]) . " AND `Member` = '" . $_in["mysql"]["id"]->real_escape_string ( "Agent/" . (int) $parameters["Agent"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Queue"] = __ ( "The provided agent are not logged at this queue.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queues_leave_member_validate"))
  {
    $data = framework_call ( "queues_leave_member_validate", $parameters);
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
  $parameters["Queue"] = (int) $parameters["Queue"];
  $parameters["Agent"] = (int) $parameters["Agent"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queues_leave_member_sanitize"))
  {
    $parameters = framework_call ( "queues_leave_member_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queues_leave_member_pre"))
  {
    $parameters = framework_call ( "queues_leave_member_pre", $parameters, false, $parameters);
  }

  /**
   * Remove database registry
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `QueueMembers` WHERE `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Queue"]) . " AND `Member` = '" . $_in["mysql"]["id"]->real_escape_string ( "Agent/" . $parameters["Agent"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queues_leave_member_post"))
  {
    $data = framework_call ( "queues_leave_member_post", $parameters, false, $data);
  }

  /**
   * Notify server to member leave
   */
  $notify = array ( "Queue" => $queue["Name"], "Member" => "Agent/" . $parameters["Agent"]);
  if ( framework_has_hook ( "queues_leave_member_notify"))
  {
    $notify = framework_call ( "queues_leave_member_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "queue_leave_member", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queues_leave_member_finish"))
  {
    framework_call ( "queues_leave_member_finish", $parameters);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to member pause from a queue
 */
framework_add_hook (
  "queues_pause_member",
  "queues_pause_member",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "The agent was sucessfully paused at the queue.")
      ),
      201 => array (),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Queue" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided queue doesn't exist.")
            ),
            "Agent" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided agent doesn't exist.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "queues_pause_member", __ ( "Member pause from queues"));
framework_add_api_call (
  "/queues/:Queue/pause/:Agent",
  "Create",
  "queues_pause_member",
  array (
    "permissions" => array ( "user", "queues_pause_member"),
    "title" => __ ( "Pause member at queues"),
    "description" => __ ( "Pause a system agent member at a queue."),
    "parameters" => array (
      array (
        "name" => "Queue",
        "type" => "integer",
        "description" => __ ( "The queue internal system unique identifier."),
        "example" => 1
      ),
      array (
        "name" => "Agent",
        "type" => "integer",
        "description" => __ ( "The agent internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to member pause from queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_pause_member ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queues_pause_member_start"))
  {
    $parameters = framework_call ( "queues_pause_member_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["Queue"] = __ ( "The provided queue doesn't exist.");
  } else {
    $queue = $result->fetch_assoc ();
  }

  /**
   * Check if agent exists
   */
  $agent = filters_call ( "get_agents", array ( "code" => (int) $parameters["Agent"]));
  if ( sizeof ( $agent) != 1)
  {
    $data["Agent"] = __ ( "The provided agent doesn't exist.");
  }

  /**
   * Check if member is logged at queue
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `QueuesLogged` WHERE `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Queue"]["ID"]) . " AND `Member` = '" . $_in["mysql"]["id"]->real_escape_string ( "Agent/" . (int) $parameters["Agent"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Queue"] = __ ( "The provided agent are not logged at this queue.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queues_pause_member_validate"))
  {
    $data = framework_call ( "queues_pause_member_validate", $parameters);
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
  $parameters["Queue"] = (int) $parameters["Queue"];
  $parameters["Agent"] = (int) $parameters["Agent"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queues_pause_member_sanitize"))
  {
    $parameters = framework_call ( "queues_pause_member_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queues_pause_member_pre"))
  {
    $parameters = framework_call ( "queues_pause_member_pre", $parameters, false, $parameters);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queues_pause_member_post"))
  {
    $data = framework_call ( "queues_pause_member_post", $parameters, false, $data);
  }

  /**
   * Notify server to member pause
   */
  $notify = array ( "Queue" => $queue["Name"], "Member" => "Agent/" . $parameters["Agent"]);
  if ( framework_has_hook ( "queues_pause_member_notify"))
  {
    $notify = framework_call ( "queues_pause_member_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "queue_pause_member", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queues_pause_member_finish"))
  {
    framework_call ( "queues_pause_member_finish", $parameters);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to member resume from a queue
 */
framework_add_hook (
  "queues_resume_member",
  "queues_resume_member",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "The agent was sucessfully resumed at the queue.")
      ),
      201 => array (),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Queue" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided queue doesn't exist.")
            ),
            "Agent" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided agent doesn't exist.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "queues_resume_member", __ ( "Member resume from queues"));
framework_add_api_call (
  "/queues/:Queue/resume/:Agent",
  "Create",
  "queues_resume_member",
  array (
    "permissions" => array ( "user", "queues_resume_member"),
    "title" => __ ( "Resume a paused member at queues"),
    "description" => __ ( "Resume a paused system agent member at a queue."),
    "parameters" => array (
      array (
        "name" => "Queue",
        "type" => "integer",
        "description" => __ ( "The queue internal system unique identifier."),
        "example" => 1
      ),
      array (
        "name" => "Agent",
        "type" => "integer",
        "description" => __ ( "The agent internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to member resume from queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_resume_member ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "queues_resume_member_start"))
  {
    $parameters = framework_call ( "queues_resume_member_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["Queue"] = __ ( "The provided queue doesn't exist.");
  } else {
    $queue = $result->fetch_assoc ();
  }

  /**
   * Check if agent exists
   */
  $agent = filters_call ( "get_agents", array ( "code" => (int) $parameters["Agent"]));
  if ( sizeof ( $agent) != 1)
  {
    $data["Agent"] = __ ( "The provided agent doesn't exist.");
  }

  /**
   * Check if member is logged at queue
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `QueuesLogged` WHERE `Queue` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Queue"]) . " AND `Member` = '" . $_in["mysql"]["id"]->real_escape_string ( "Agent/" . (int) $parameters["Agent"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Queue"] = __ ( "The provided agent are not logged at this queue.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "queues_resume_member_validate"))
  {
    $data = framework_call ( "queues_resume_member_validate", $parameters);
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
  $parameters["Queue"] = (int) $parameters["Queue"];
  $parameters["Agent"] = (int) $parameters["Agent"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "queues_resume_member_sanitize"))
  {
    $parameters = framework_call ( "queues_resume_member_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "queues_resume_member_pre"))
  {
    $parameters = framework_call ( "queues_resume_member_pre", $parameters, false, $parameters);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "queues_resume_member_post"))
  {
    $data = framework_call ( "queues_resume_member_post", $parameters, false, $data);
  }

  /**
   * Notify server to member resume
   */
  $notify = array ( "Queue" => $queue["Name"], "Member" => "Agent/" . $parameters["Agent"]);
  if ( framework_has_hook ( "queues_resume_member_notify"))
  {
    $notify = framework_call ( "queues_resume_member_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "queue_resume_member", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "queues_resume_member_finish"))
  {
    framework_call ( "queues_resume_member_finish", $parameters);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "queues_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "queues_server_reconfig");

/**
 * Function to notify server to include all queues.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all queues and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $queue = $result->fetch_assoc ())
  {
    $notify = array ( "Description" => $queue["Description"], "Name" => $queue["Name"], "Strategy" => $queue["Strategy"]);
    if ( framework_has_hook ( "queues_add_notify"))
    {
      $notify = framework_call ( "queues_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "queue_add", $notify);
  }

  /**
   * Add all logged members at queues
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Queues`.`Name`, `QueueMembers`.`Member` FROM `QueueMembers` LEFT JOIN `Queues` ON `QueueMembers`.`Queue` = `Queues`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $queuemember = $result->fetch_assoc ())
  {
    $notify = array ( "Queue" => $queuemember["Name"], "Member" => $queuemember["Member"]);
    if ( framework_has_hook ( "queues_add_member_notify"))
    {
      $notify = framework_call ( "queues_add_member_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "queue_add_member", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
