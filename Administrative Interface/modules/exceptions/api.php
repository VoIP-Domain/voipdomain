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
 * VoIP Domain exceptions module API. This module add the API calls related to
 * exceptions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Exceptions
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search exceptions
 */
framework_add_hook (
  "exceptions_search",
  "exceptions_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all exceptions."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Number",
          "example" => "ID,Description"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system exception numbers."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "exception"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The exception number internal system unique identifier."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the exception number."),
                "example" => __ ( "John Doe")
              ),
              "Number" => array (
                "type" => "string",
                "description" => __ ( "An E.164 formatted number."),
                "example" => __ ( "+1 123 5551212")
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
framework_add_permission ( "exceptions_search", __ ( "Search exceptions"));
framework_add_api_call (
  "/exceptions",
  "Read",
  "exceptions_search",
  array (
    "permissions" => array ( "user", "exceptions_search"),
    "title" => __ ( "Search exceptions"),
    "description" => __ ( "Search for system exception numbers.")
  )
);

/**
 * Function to search exceptions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "exceptions_search_start"))
  {
    $parameters = framework_call ( "exceptions_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Exceptions");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "exceptions_search_validate"))
  {
    $data = framework_call ( "exceptions_search_validate", $parameters);
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
  if ( framework_has_hook ( "exceptions_search_sanitize"))
  {
    $parameters = framework_call ( "exceptions_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "exceptions_search_pre"))
  {
    $parameters = framework_call ( "exceptions_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search exceptions
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Number` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Description`, `Number`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Number", "ID,Description,Number");
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "exceptions_search_post"))
  {
    $data = framework_call ( "exceptions_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "exceptions_search_finish"))
  {
    framework_call ( "exceptions_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fast search exceptions
 */
framework_add_hook (
  "fastsearch_objects",
  "exceptions_fastsearch",
  IN_HOOK_NULL
);
framework_add_function_documentation (
  "fastsearch",
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "items" => array (
            "properties" => array (
              "Type" => array (
                "enum" => array ( "exceptions")
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to fast search exceptions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_fastsearch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Search exceptions
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Number`, `Description` FROM `Exceptions`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "'" : "") . " ORDER BY `Number`, `Description`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = array ( "ID" => $result["ID"], "Number" => $result["Number"], "Type" => "exception", "Description" => $result["Description"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get exception information
 */
framework_add_hook (
  "exceptions_view",
  "exceptions_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the exception number."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the exception number."),
              "example" => __ ( "John Doe")
            ),
            "Number" => array (
              "type" => "string",
              "description" => __ ( "An E.164 formatted number."),
              "example" => __ ( "+1 123 5551212")
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
              "example" => __ ( "Invalid exception ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "exceptions_view", __ ( "View exceptions information"));
framework_add_api_call (
  "/exceptions/:ID",
  "Read",
  "exceptions_view",
  array (
    "permissions" => array ( "user", "exceptions_view"),
    "title" => __ ( "View exceptions"),
    "description" => __ ( "Get an exception number information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The exception number internal system unique identifier."),
        "example" => 1
      )
    ),
  )
);

/**
 * Function to generate exception information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "exceptions_view_start"))
  {
    $parameters = framework_call ( "exceptions_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Exceptions");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid exception ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "exceptions_view_validate"))
  {
    $data = framework_call ( "exceptions_view_validate", $parameters);
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
  if ( framework_has_hook ( "exceptions_view_sanitize"))
  {
    $parameters = framework_call ( "exceptions_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "exceptions_view_pre"))
  {
    $parameters = framework_call ( "exceptions_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search exceptions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $exception = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = api_filter_entry ( array ( "Description", "Number"), $exception);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "exceptions_view_post"))
  {
    $data = framework_call ( "exceptions_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "exceptions_view_finish"))
  {
    framework_call ( "exceptions_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new exception
 */
framework_add_hook (
  "exceptions_add",
  "exceptions_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the exception number."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Number" => array (
          "type" => "string",
          "description" => __ ( "The exception number in E.164 format."),
          "required" => true,
          "example" => __ ( "+1 123 5551212")
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system exception number added sucessfully.")
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
            "Number" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The number must be in E.164 format, including the prefix +.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "exceptions_add", __ ( "Add exceptions"));
framework_add_api_call (
  "/exceptions",
  "Create",
  "exceptions_add",
  array (
    "permissions" => array ( "user", "exceptions_add"),
    "title" => __ ( "Add exceptions"),
    "description" => __ ( "Add a new system exception number.")
  )
);

/**
 * Function to add a new exception.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "exceptions_add_start"))
  {
    $parameters = framework_call ( "exceptions_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The exception description is required.");
  }
  $parameters["Number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Number"])));
  if ( empty ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The exception number is required.");
  }
  if ( ! validate_E164 ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The number must be in E.164 format, including the + prefix.");
  }

  /**
   * Check if exception already exist
   */
  if ( ! array_key_exists ( "Number", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Number"] = __ ( "The number entered is already registered in the system.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "exceptions_add_validate"))
  {
    $data = framework_call ( "exceptions_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "exceptions_add_sanitize"))
  {
    $parameters = framework_call ( "exceptions_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "exceptions_add_pre"))
  {
    $parameters = framework_call ( "exceptions_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new exception record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Exceptions` (`Description`, `Number`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "exceptions_add_post"))
  {
    framework_call ( "exceptions_add_post", $parameters);
  }

  /**
   * Add new exception at Asterisk servers
   */
  $notify = array ( "Description" => $parameters["Description"], "Number" => $parameters["Number"]);
  if ( framework_has_hook ( "exceptions_add_notify"))
  {
    $notify = framework_call ( "exceptions_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "exception_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "exceptions_add_finish"))
  {
    framework_call ( "exceptions_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/exceptions/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing exception
 */
framework_add_hook (
  "exceptions_edit",
  "exceptions_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the exception number."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Number" => array (
          "type" => "string",
          "description" => __ ( "The exception number in E.164 format."),
          "required" => true,
          "example" => __ ( "+1 123 5551212")
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system exception number was sucessfully updated.")
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
            "Number" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The number must be in E.164 format, including the prefix +.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "exceptions_edit", __ ( "Edit exceptions"));
framework_add_api_call (
  "/exceptions/:ID",
  "Modify",
  "exceptions_edit",
  array (
    "permissions" => array ( "user", "exceptions_edit"),
    "title" => __ ( "Edit exceptions"),
    "description" => __ ( "Edit a system exceptions number.")
  )
);
framework_add_api_call (
  "/exceptions/:ID",
  "Edit",
  "exceptions_edit",
  array (
    "permissions" => array ( "user", "exceptions_edit"),
    "title" => __ ( "Edit exceptions"),
    "description" => __ ( "Edit a system exceptions number.")
  )
);

/**
 * Function to edit an existing exception.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "exceptions_edit_start"))
  {
    $parameters = framework_call ( "exceptions_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The exception description is required.");
  }
  $parameters["Number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Number"])));
  if ( empty ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The exception number is required.");
  }
  if ( ! validate_E164 ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The number must be in E.164 format, including the + prefix.");
  }

  /**
   * Check if exception already exist
   */
  if ( ! array_key_exists ( "Number", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Number"] = __ ( "The number entered is already registered in the system.");
    }
  }

  /**
   * Check if exception exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
  if ( framework_has_hook ( "exceptions_edit_validate"))
  {
    $data = framework_call ( "exceptions_edit_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "exceptions_edit_sanitize"))
  {
    $parameters = framework_call ( "exceptions_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "exceptions_edit_pre"))
  {
    $parameters = framework_call ( "exceptions_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change exception record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Exceptions` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "exceptions_edit_post"))
  {
    framework_call ( "exceptions_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Description" => $parameters["Description"], "Number" => $parameters["ORIGINAL"]["Number"], "NewNumber" => $parameters["Number"]);
  if ( framework_has_hook ( "exceptions_edit_notify"))
  {
    $notify = framework_call ( "exceptions_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "exception_change", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "exceptions_edit_finish"))
  {
    framework_call ( "exceptions_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove an exception
 */
framework_add_hook (
  "exceptions_remove",
  "exceptions_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system exception number was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid exception ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "exceptions_remove", __ ( "Remove exceptions"));
framework_add_api_call (
  "/exceptions/:ID",
  "Delete",
  "exceptions_remove",
  array (
    "permissions" => array ( "user", "exceptions_remove"),
    "title" => __ ( "Remove exceptions"),
    "description" => __ ( "Remove a exception number from system.")
  )
);

/**
 * Function to remove an existing exception.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "exceptions_remove_start"))
  {
    $parameters = framework_call ( "exceptions_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid exception ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "exceptions_remove_validate"))
  {
    $data = framework_call ( "exceptions_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "exceptions_remove_sanitize"))
  {
    $parameters = framework_call ( "exceptions_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if exception exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
  if ( framework_has_hook ( "exceptions_remove_pre"))
  {
    $parameters = framework_call ( "exceptions_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove exception database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Exceptions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "exceptions_remove_post"))
  {
    framework_call ( "exceptions_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Number" => $parameters["ORIGINAL"]["Number"]);
  if ( framework_has_hook ( "exceptions_remove_notify"))
  {
    $notify = framework_call ( "exceptions_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "exception_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "exceptions_remove_finish"))
  {
    framework_call ( "exceptions_remove_finish", $parameters, false);
  }
  $audit = $parameters["ORIGINAL"];
  if ( framework_has_hook ( "exceptions_remove_audit"))
  {
    $audit = framework_call ( "exceptions_remove_audit", $parameters, false, $audit);
  }
  audit ( "exception", "remove", $audit);

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "exceptions_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "exceptions_server_reconfig");

/**
 * Function to notify server to include all exceptions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all exceptions and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $exception = $result->fetch_assoc ())
  {
    $notify = array ( "Description" => $exception["Description"], "Number" => $exception["Number"]);
    if ( framework_has_hook ( "exceptions_add_notify"))
    {
      $notify = framework_call ( "exceptions_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "exception_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
