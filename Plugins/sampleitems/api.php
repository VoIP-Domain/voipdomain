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
 * VoIP Domain sample items module API. This module add the API calls related to
 * system sample items CRUD example.
 *
 * This file shows how to expose a set of CRUD endpoints backed by an SQL table
 * using VoIP Domain's hook-based micro-framework.
 * 
 * Database schema (MariaDB):
 *
 *   CREATE TABLE `SampleItems` (
 *     `ID`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
 *     `Name`        VARCHAR(64)  NOT NULL,
 *     `Description` TEXT         NULL,
 *     PRIMARY KEY   (`ID`)
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 *
 * This file will be automatically loaded by VoIP Domain's core.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Sample Items
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add hook that will manage the sampleitems search API call, also document the
 * endpoint request variables and response schema. This will allow system to
 * fulfill the OpenAPI specification.
 */
framework_add_hook (
  "sampleitems_search",
  "sampleitems_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, all items will be returned."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Name,Description",
          "example" => "ID,Name"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the sample items."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "SampleItems",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "SampleItem"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The ID of the sample item."),
                "example" => 1
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the sample item."),
                "example" => __ ( "Sample Item 1")
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the sample item."),
                "example" => __ ( "This is a sample item description.")
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
            "Fields" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Fields contains invalid values.")
            )
          )
        )
      )
    )
  )
);
/**
 * Add permission - Note that permissions are optional but recommended.
 * When you add a permission to the API call, you allow access to the endpoint
 * using system tokens.
 */
framework_add_permission ( "sampleitems_search", __ ( "Search sample items"));
/**
 * Add API call for search sample items
 * GET /api/sampleitems
 */
framework_add_api_call (
  "/sampleitems",                // URI path (automatically prefixed by /api)
  "Read",                        // HTTP verb
  "sampleitems_search",          // main hook
  array (
    "permissions" => array ( "User", "sampleitems_search"),
    "title"       => __ ( "Search sample items"),
    "description" => __ ( "Return the list of sample items")
  )
);

/**
 * Function to search sample items.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function sampleitems_search ( $buffer, $parameters)
{
  /**
   * Get the database connection global variable
   */
  global $_in;

  /**
   * Set function parameters
   */
  $parameters["function"] = array (
    "Fields" => "ID,Name,Description"
  );

  /**
   * Call start hook if exist. This hook is used to do initial modification of
   * received parameters. I.E.: You can capture this hook and add some
   * information to be used later, or to modify the function logic changing the
   * parameters.
   */
  if ( framework_has_hook ( "sampleitems_search_start"))
  {
    $parameters = framework_call ( "sampleitems_search_start", $parameters);
  }

  /**
   * Check for modifications time. This is used to check if the table has been
   * modified since the last time the user called the API.
   */
  check_table_modification ( "SampleItems");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Fields", $parameters) || $parameters["Fields"] == "" || sizeof ( $parameters["Fields"]) == 0)
  {
    $parameters["Fields"] = $parameters["function"]["DefaultFields"];
  }
  if ( ! api_filter_validate ( $parameters["Filter"], $parameters["function"]["Fields"]))
  {
    $data["Fields"] = __ ( "Fields contains invalid values.");
  }

  /**
   * Call validate hook if exist. This hook is used to validate the parameters.
   */
  if ( framework_has_hook ( "sampleitems_search_validate"))
  {
    $data = framework_call ( "sampleitems_search_validate", $parameters);
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
   * Call sanitize hook if exist. This hook is used to sanitize the parameters.
   */
  if ( framework_has_hook ( "sampleitems_search_sanitize"))
  {
    $parameters = framework_call ( "sampleitems_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist. This hook is used to change parameters prior SQL
   * query being executed.
   */
  if ( framework_has_hook ( "sampleitems_search_pre"))
  {
    $parameters = framework_call ( "sampleitems_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search sampleitems. This is the main logic of the function.
   */
  $sql = "SELECT `ID`, `Name`, `Description` FROM `SampleItems`";
  if ( ! empty ( $parameters["Filter"]))
  {
    $sql .= " WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'";
  }
  $sql .= " ORDER BY `Name`";
  if ( ! $results = @$_in["mysql"]["id"]->query ( $sql))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure. This is used to create the result structure.
   * The api_filter_fields() function is used to generate a filtered fields
   * that client requested, making sure they'll be valid. Later the
   * api_filter_entry() function is used to filter the result of the query,
   * limiting the output to the requested fields.
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Name,Description", "ID,Name,Description");
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist. This hook is used to do final modification of the
   * result before it's returned to the client.
   */
  if ( framework_has_hook ( "sampleitems_search_post"))
  {
    $data = framework_call ( "sampleitems_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist. This hook is used to do final tasks before
   * finishing the function.
   */
  if ( framework_has_hook ( "sampleitems_search_finish"))
  {
    framework_call ( "sampleitems_search_finish", $parameters);
  }

  /**
   * Return structured data. This is used to return the result to the client.
   * Remember to always merge the buffer with the data, so the plugin can
   * respect the data from another hook.
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get sample item information
 */
framework_add_hook (
  "sampleitems_view",
  "sampleitems_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the sample item."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "integer",
              "description" => __ ( "The ID of the sample item."),
              "example" => 1
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name of the sample item."),
              "example" => __ ( "Sample Item 1")
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the sample item."),
              "example" => __ ( "This is a sample item description.")
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
              "example" => __ ( "Invalid sample item ID.")
            )
          )
        )
      )
    )
  )
);
/**
 * Add permission - Note that permissions are optional but recommended.
 * When you add a permission to the API call, you allow access to the endpoint
 * using system tokens.
 */
framework_add_permission ( "sampleitems_view", __ ( "View sample item"));
/**
 * Add API call for get sample item information
 * GET /api/sampleitems/:ID
 */
framework_add_api_call (
  "/sampleitems/:ID",
  "Read",
  "sampleitems_view",
  array (
    "permissions" => array ( "User", "sampleitems_view"),
    "title"       => __ ( "View sample item"),
    "description" => __ ( "Return a single sample item by ID"),
    "parameters"  => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "Sample item ID"),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate sample item information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function sampleitems_view ( $buffer, $parameters)
{
  /**
   * Get the database connection global variable
   */
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "sampleitems_view_start"))
  {
    $parameters = framework_call ( "sampleitems_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "SampleItems");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid sample item ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "sampleitems_view_validate"))
  {
    $data = framework_call ( "sampleitems_view_validate", $parameters);
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
  if ( framework_has_hook ( "sampleitems_view_sanitize"))
  {
    $parameters = framework_call ( "sampleitems_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "sampleitems_view_pre"))
  {
    $parameters = framework_call ( "sampleitems_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search sampleitem. This is the main logic of the function.
   */
  $sql = "SELECT `ID`, `Name`, `Description` FROM `SampleItems` WHERE `ID` = " . (int) $parameters["ID"];
  if ( ! $result = @$_in["mysql"]["id"]->query ( $sql))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $item = $result->fetch_assoc())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Create result structure. This is used to create the result structure.
   * The api_filter_entry() function is used to filter the result of the query,
   * limiting the output to the provided fields.
   */
  $data = api_filter_entry ( array ( "ID", "Name", "Description"), $item);

  /**
   * Call post hook if exist. This hook is used to do final modification of the
   * result before it's returned to the client.
   */
  if ( framework_has_hook ( "sampleitems_view_post"))
  {
    $data = framework_call ( "sampleitems_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist. This hook is used to do final tasks before
   * finishing the function.
   */
  if ( framework_has_hook ( "sampleitems_view_finish"))
  {
    framework_call ( "sampleitems_view_finish", $parameters);
  }

  /**
   * Return structured data. This is used to return the result to the client.
   * Remember to always merge the buffer with the data, so the plugin can
   * respect the data from another hook.
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new sample item
 * POST /api/sampleitems
 */
framework_add_hook (
  "sampleitems_add",
  "sampleitems_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the sample item."),
          "required" => true,
          "example" => __ ( "Sample Item 1")
        ),
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the sample item."),
          "required" => false,
          "example" => __ ( "This is a sample item description.")
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New sample item added successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The sample item name is required.")
            )
          )
        )
      )
    )
  )
);
/**
 * Add permission - Note that permissions are optional but recommended.
 * When you add a permission to the API call, you allow access to the endpoint
 * using system tokens.
 */
framework_add_permission ( "sampleitems_add", __ ( "Add sample items"));
framework_add_api_call (
  "/sampleitems",
  "Create",
  "sampleitems_add",
  array (
    "permissions" => array ( "User", "sampleitems_add"),
    "title" => __ ( "Add sample items"),
    "description" => __ ( "Add a new sample item.")
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
function sampleitems_add ( $buffer, $parameters)
{
  /**
   * Get the database connection global variable
   */
  global $_in;

  /**
   * Call start hook if exist. This hook is used to do initial modification of
   * received parameters. I.E.: You can capture this hook and add some
   * information to be used later, or to modify the function logic changing the
   * parameters.
   */
  if ( framework_has_hook ( "sampleitems_add_start"))
  {
    $parameters = framework_call ( "sampleitems_add_start", $parameters);
  }

  /**
   * Validate received parameters. This is used to validate the parameters.
   */
  $data = array ();
  $name = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  if ( empty ( $name))
  {
    $data["Name"] = __ ( "The sample item name is required.");
  }

  /**
   * Call validate hook if exist. This hook is used to validate the parameters.
   */
  if ( framework_has_hook ( "sampleitems_add_validate"))
  {
    $data = framework_call ( "sampleitems_add_validate", $parameters, false, $data);
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
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));

  /**
   * Call sanitize hook if exist. This hook is used to sanitize the parameters.
   */
  if ( framework_has_hook ( "sampleitems_add_sanitize"))
  {
    $parameters = framework_call ( "sampleitems_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist. This hook is used to change parameters prior SQL
   * query being executed.
   */
  if ( framework_has_hook ( "sampleitems_add_pre"))
  {
    $parameters = framework_call ( "sampleitems_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add sample item to database. This is the main logic of the function.
   */
  $sql = "INSERT INTO `SampleItems` (`Name`, `Description`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "')";
  if ( ! $result = @$_in["mysql"]["id"]->query ( $sql))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $result->insert_id;

  /**
   * Call post hook if exist. This hook is used to do final modification of the
   * result before it's returned to the client.
   */
  if ( framework_has_hook ( "sampleitems_add_post"))
  {
    $data = framework_call ( "sampleitems_add_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist. This hook is used to do final tasks before
   * finishing the function.
   */
  if ( framework_has_hook ( "sampleitems_add_finish"))
  {
    framework_call ( "sampleitems_add_finish", $parameters);
  }

  /**
   * Return code 201 (created) with location of created object in the interface.
   * Also, return the $buffer, so the plugin can respect the data from another
   * hook.
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/sampleitems/" . $parameters["ID"]);
  return $buffer;
}

/**
 * API call to edit an existing sample item
 * PUT|PATCH /api/sampleitems/:ID
 */
framework_add_hook (
  "sampleitems_edit",
  "sampleitems_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the sample item."),
          "required" => true,
          "example" => __ ( "Sample Item 1")
        ),
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the sample item."),
          "required" => false,
          "example" => __ ( "This is a sample item description.")
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The sample item was successfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid sample item ID.")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The sample item name is required.")
            )
          )
        )
      )
    )
  )
);
/**
 * Add permission - Note that permissions are optional but recommended.
 * When you add a permission to the API call, you allow access to the endpoint
 * using system tokens.
 */
framework_add_permission ( "sampleitems_edit", __ ( "Edit sample items"));
framework_add_api_call (
  "/sampleitems/:ID",
  array ( "Modify", "Edit"),
  "sampleitems_edit",
  array (
    "permissions" => array ( "User", "sampleitems_edit"),
    "title" => __ ( "Edit sample items"),
    "description" => __ ( "Change a sample item information.")
  )
);

/**
 * Function to edit an existing sample item.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function sampleitems_edit ( $buffer, $parameters)
{
  /**
   * Get the database connection global variable
   */
  global $_in;

  /**
   * Call start hook if exist. This hook is used to do initial modification of
   * received parameters. I.E.: You can capture this hook and add some
   * information to be used later, or to modify the function logic changing the
   * parameters.
   */
  if ( framework_has_hook ( "sampleitems_edit_start"))
  {
    $parameters = framework_call ( "sampleitems_edit_start", $parameters);
  }

  /**
   * Validate received parameters. This is used to validate the parameters.
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid sample item ID.");
  }
  $name = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  if ( empty ( $name))
  {
    $data["Name"] = __ ( "The agent name is required.");
  }

  /**
   * Call validate hook if exist. This hook is used to validate the parameters.
   */
  if ( framework_has_hook ( "sampleitems_edit_validate"))
  {
    $data = framework_call ( "sampleitems_edit_validate", $parameters, false, $data);
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
   * Sanitize parameters. This is used to sanitize the parameters.
   */
  $parameters["ID"] = (int) $parameters["ID"];
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));

  /**
   * Call sanitize hook if exist. This hook is used to sanitize the parameters.
   */
  if ( framework_has_hook ( "sampleitems_edit_sanitize"))
  {
    $parameters = framework_call ( "sampleitems_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist. This hook is used to change parameters prior SQL
   * query being executed.
   */
  if ( framework_has_hook ( "sampleitems_edit_pre"))
  {
    $parameters = framework_call ( "sampleitems_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Update sample item in database. This is the main logic of the function.
   */
  $sql = "UPDATE `SampleItems` SET `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "' WHERE `ID` = " . (int) $parameters["ID"];
  if ( ! $result = @$_in["mysql"]["id"]->query ( $sql))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist. This hook is used to do final modification of the
   * result before it's returned to the client.
   */
  if ( framework_has_hook ( "sampleitems_edit_post"))
  {
    $data = framework_call ( "sampleitems_edit_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist. This hook is used to do final tasks before
   * finishing the function.
   */
  if ( framework_has_hook ( "sampleitems_edit_finish"))
  {
    framework_call ( "sampleitems_edit_finish", $parameters);
  }

  /**
   * Return code 200 (OK). Also, return the $buffer, so the plugin can respect
   * the data from another hook.
   */
  return $buffer;
}

/**
 * API call to remove an existing sample item
 * DELETE /api/sampleitems/:ID
 */
framework_add_hook (
  "sampleitems_remove",
  "sampleitems_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The sample item was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid sample item ID.")
            )
          )
        )
      )
    )
  )
);
/**
 * Add permission - Note that permissions are optional but recommended.
 * When you add a permission to the API call, you allow access to the endpoint
 * using system tokens.
 */
framework_add_permission ( "sampleitems_remove", __ ( "Remove sample items"));
framework_add_api_call (
  "/sampleitems/:ID",
  "Delete",
  "sampleitems_remove",
  array (
    "permissions" => array ( "User", "sampleitems_remove"),
    "title" => __ ( "Remove sample items"),
    "description" => __ ( "Remove a sample item from system.")
  )
);

/**
 * Function to remove an existing sample item.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function sampleitems_remove ( $buffer, $parameters)
{
  /**
   * Get the database connection global variable
   */
  global $_in;

  /**
   * Call start hook if exist. This hook is used to do initial modification of
   * received parameters. I.E.: You can capture this hook and add some
   * information to be used later, or to modify the function logic changing the
   * parameters.
   */
  if ( framework_has_hook ( "sampleitems_remove_start"))
  {
    $parameters = framework_call ( "sampleitems_remove_start", $parameters);
  }

  /**
   * Validate received parameters. This is used to validate the parameters.
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid sample item ID.");
  }

  /**
   * Call validate hook if exist. This hook is used to validate the parameters.
   */
  if ( framework_has_hook ( "sampleitems_remove_validate"))
  {
    $data = framework_call ( "sampleitems_remove_validate", $parameters, false, $data);
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
   * Sanitize parameters. This is used to sanitize the parameters.
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist. This hook is used to sanitize the parameters.
   */
  if ( framework_has_hook ( "sampleitems_remove_sanitize"))
  {
    $parameters = framework_call ( "sampleitems_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist. This hook is used to change parameters prior SQL
   * query being executed.
   */
  if ( framework_has_hook ( "sampleitems_remove_pre"))
  {
    $parameters = framework_call ( "sampleitems_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove sample item from database. This is the main logic of the function.
   */
  $sql = "DELETE FROM `SampleItems` WHERE `ID` = " . (int) $parameters["ID"];
  if ( ! $result = @$_in["mysql"]["id"]->query ( $sql))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist. This hook is used to do final modification of the
   * result before it's returned to the client.
   */
  if ( framework_has_hook ( "sampleitems_remove_post"))
  {
    $data = framework_call ( "sampleitems_remove_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist. This hook is used to do final tasks before
   * finishing the function.
   */
  if ( framework_has_hook ( "sampleitems_remove_finish"))
  {
    framework_call ( "sampleitems_remove_finish", $parameters);
  }

  /**
   * Return code 204 (No Content). Also, return the $buffer, so the plugin can
   * respect the data from another hook.
   */
  return $buffer;
}
?>
