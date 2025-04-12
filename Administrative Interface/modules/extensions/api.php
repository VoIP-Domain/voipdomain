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
 * VoIP Domain extensions module API. This module add the API calls related to
 * extensions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search extensions
 */
framework_add_hook (
  "extensions_search",
  "extensions_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all extensions."),
          "example" => __ ( "filter")
        ),
        "Type" => array (
          "type" => "string",
          "enum" => array (),
          "description" => __ ( "Return only extensions of this type.")
        ),
        "Except" => array (
          "type" => "integer",
          "description" => __ ( "Don't return extension with unique identifier provided here.", true, false),
          "example" => 3
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Number,Description,Type",
          "example" => "Number,Description,Type"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system extensions."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The extension internal system unique identifier."),
                "example" => 1
              ),
              "Number" => array (
                "type" => "integer",
                "description" => __ ( "The telephone number of the extension."),
                "example" => 1000
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the extension."),
                "example" => __ ( "John Doe")
              ),
              "Type" => array (
                "type" => "string",
                "enum" => array (),
                "description" => __ ( "The type of the extension."),
                "example" => ""
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
framework_add_permission ( "extensions_search", __ ( "Search extensions"));
framework_add_api_call (
  "/extensions",
  "Read",
  "extensions_search",
  array (
    "permissions" => array ( "user", "extensions_search"),
    "title" => __ ( "Search extensions"),
    "description" => __ ( "Search for system extensions.")
  )
);

/**
 * Function to search extensions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extensions_search_start"))
  {
    $parameters = framework_call ( "extensions_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Extensions");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extensions_search_validate"))
  {
    $data = framework_call ( "extensions_search_validate", $parameters);
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
  if ( framework_has_hook ( "extensions_search_sanitize"))
  {
    $parameters = framework_call ( "extensions_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extensions_search_pre"))
  {
    $parameters = framework_call ( "extensions_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search extensions
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Number`, `Description`, `Type` FROM `Extensions`" . ( ! empty ( $parameters["Filter"]) ? " WHERE (`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "')" : "") . ( ! empty ( $parameters["Except"]) ? ( ! empty ( $parameters["Filter"]) ? " AND" : " WHERE") . " `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Except"]) : "") . ( ! empty ( $parameters["Type"]) ? ( ! empty ( $parameters["Filter"]) || ! empty ( $parameters["Except"]) ? " AND" : " WHERE") . " `Type` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Type"]) . "'" : "") . " ORDER BY `Description`, `Number`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Number,Description,Type", "ID,Number,Description,Type");
  while ( $result = $results->fetch_assoc ())
  {
    $result["Type"] = "extension_" . $result["Type"];
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extensions_search_post"))
  {
    $data = framework_call ( "extensions_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extensions_search_finish"))
  {
    framework_call ( "extensions_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fast search extensions
 */
framework_add_hook (
  "fastsearch_objects",
  "extensions_fastsearch",
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
                "enum" => array ( "extensions")
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to fast search extensions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_fastsearch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Search extensions
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Number`, `Description`, `Type` FROM `Extensions`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "'" : "") . " ORDER BY `Number`, `Description`"))
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
    $data[] = array ( "ID" => $result["ID"], "Number" => $result["Number"], "Type" => "extension_" . $result["Type"], "Description" => $result["Description"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get extension information
 */
framework_add_hook (
  "extensions_view",
  "extensions_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the extension."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Number" => array (
              "type" => "integer",
              "description" => __ ( "The number of the extension."),
              "example" => 1000
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the extension."),
              "example" => __ ( "John Doe")
            ),
            "Type" => array (
              "type" => "string",
              "enum" => array (),
              "description" => __ ( "The type of the extension.")
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
              "example" => __ ( "Invalid extension ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "extensions_view", __ ( "View extensions information"));
framework_add_api_call (
  "/extensions/:ID",
  "Read",
  "extensions_view",
  array (
    "permissions" => array ( "user", "extensions_view"),
    "title" => __ ( "View extensions"),
    "description" => __ ( "Get a system extension information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The extension internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate extension information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extensions_view_start"))
  {
    $parameters = framework_call ( "extensions_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Extensions");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid extension ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extensions_view_validate"))
  {
    $data = framework_call ( "extensions_view_validate", $parameters);
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
  if ( framework_has_hook ( "extensions_view_sanitize"))
  {
    $parameters = framework_call ( "extensions_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extensions_view_pre"))
  {
    $parameters = framework_call ( "extensions_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = api_filter_entry ( array ( "Number", "Description", "Type"), $extension);

  /**
   * Call view subhook if exist
   */
  if ( framework_has_hook ( "extensions_view_" . $data["Type"]))
  {
    $data = framework_call ( "extensions_view_" . $data["Type"], $parameters, false, $data);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extensions_view_post"))
  {
    $data = framework_call ( "extensions_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extensions_view_finish"))
  {
    framework_call ( "extensions_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to check if an extension number is in use
 */
framework_add_hook (
  "extensions_inuse",
  "extensions_inuse",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "The extension number is already in use.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Number" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid extension number.")
            )
          )
        )
      ),
      404 => array (
        "description" => __ ( "The extension number is available.")
      )
    )
  )
);
framework_add_permission ( "extensions_inuse", __ ( "Check if an extension number is available"));
framework_add_api_call (
  "/extensions/:Number/inuse",
  "Read",
  "extensions_inuse",
  array (
    "permissions" => array ( "user", "extensions_inuse"),
    "title" => __ ( "Extension availability"),
    "description" => __ ( "Check if a system extension number is available."),
    "parameters" => array (
      array (
        "name" => "Number",
        "type" => "integer",
        "description" => __ ( "The extension number."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate extension availability check.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_inuse ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extensions_inuse_start"))
  {
    $parameters = framework_call ( "extensions_inuse_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Extensions");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters) || ! is_numeric ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "Invalid extension number.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extensions_inuse_validate"))
  {
    $data = framework_call ( "extensions_inuse_validate", $parameters);
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
  $parameters["Number"] = (int) $parameters["Number"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "extensions_inuse_sanitize"))
  {
    $parameters = framework_call ( "extensions_inuse_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extensions_inuse_pre"))
  {
    $parameters = framework_call ( "extensions_inuse_pre", $parameters, false, $parameters);
  }

  /**
   * Search extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Number` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extensions_inuse_post"))
  {
    $data = framework_call ( "extensions_inuse_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extensions_inuse_finish"))
  {
    framework_call ( "extensions_inuse_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch next available extension number
 */
framework_add_hook (
  "extensions_next_number",
  "extensions_next_number",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Server" => array (
          "type" => "integer",
          "required" => false,
          "description" => __ ( "The server ID where the extension should be."),
          "example" => 1
        ),
        "Range" => array (
          "type" => "integer",
          "required" => false,
          "description" => __ ( "The range ID where the extension should be."),
          "example" => 1
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An extension number."),
        "Number" => array (
          "type" => "integer",
          "description" => __ ( "The number of next available extension for the required parameters. If no extension available for the required parameters, will return 0."),
          "example" => 1000
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Server" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid server value.")
            ),
            "Range" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid range value.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "extensions_next_number", __ ( "Next extension number"));
framework_add_api_call (
  "/extensions/nextnumber",
  "Read",
  "extensions_next_number",
  array (
    "permissions" => array ( "user", "extensions_next_number"),
    "title" => __ ( "Next extension number"),
    "description" => __ ( "Find next available extension number.")
  )
);

/**
 * Function to search for next available extension number.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_next_number ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extensions_next_number_start"))
  {
    $parameters = framework_call ( "extensions_next_number_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( array_key_exists ( "Server", $parameters) && ! is_numeric ( $parameters["Server"]))
  {
    $data["Server"] = __ ( "Invalid server value.");
  }
  if ( array_key_exists ( "Range", $parameters) && ! is_numeric ( $parameters["Range"]))
  {
    $data["Range"] = __ ( "Invalid range value.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extensions_next_number_validate"))
  {
    $data = framework_call ( "extensions_next_number_validate", $parameters);
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
   * Sanitize received parameters
   */
  $parameters["Server"] = (int) $parameters["Server"];
  $parameters["Range"] = (int) $parameters["Range"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "extensions_next_number_sanitize"))
  {
    $parameters = framework_call ( "extensions_next_number_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extensions_next_number_pre"))
  {
    $parameters = framework_call ( "extensions_next_number_pre", $parameters, false, $parameters);
  }

  /**
   * Get ranges for the required parameters
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Start`, `Finish` FROM `Ranges`" . ( $parameters["Range"] != 0 && $parameters["Server"] != 0 ? " WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Server"]) . ( $parameters["Range"] != 0 ? " AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Range"]) : "") : "")))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $ranges = array ();
  while ( $range = $result->fetch_assoc ())
  {
    $ranges[$range["ID"]] = array ( "Start" => $range["Start"], "Finish" => $range["Finish"]);
  }
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number`, `Range` FROM `Extensions` ORDER BY `Number`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $number = 0;
  while ( $data = $result->fetch_assoc ())
  {
    if ( array_key_exists ( $data["Range"], $ranges))
    {
      if ( $data["Number"] == $ranges[$data["Range"]]["Start"])
      {
        if ( $ranges[$data["Range"]]["Start"] == $ranges[$data["Range"]]["Finish"])
        {
          unset ( $ranges[$data["Range"]]);
        } else {
          $ranges[$data["Range"]]["Start"]++;
        }
      } else {
        $number = $ranges[$data["Range"]]["Start"];
        break;
      }
    }
  }
  if ( $number == 0)
  {
    if ( $parameters["Range"] != 0)
    {
      $number = ( array_key_exists ( $parameters["Range"], $ranges) ? $ranges[$parameters["Range"]]["Start"] : 0);
    } else {
      $number = ( sizeof ( $ranges) != 0 ? $ranges[array_key_first ( $ranges)]["Start"] : 0);
    }
  }
  $data = array ( "Number" => $number);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extensions_next_number_post"))
  {
    $data = framework_call ( "extensions_next_number_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extensions_next_number_finish"))
  {
    framework_call ( "extensions_next_number_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new extension
 */
framework_add_hook (
  "extensions_add",
  "extensions_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Number" => array (
          "type" => "integer",
          "description" => __ ( "The number of the extension."),
          "required" => true,
          "example" => 1000
        ),
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the extension."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Type" => array (
          "type" => "string",
          "description" => __ ( "The extension type."),
          "enum" => array (),
          "required" => true
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system extension added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Number" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The number is already in use.")
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The description is required.")
            ),
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected type is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "extensions_add_abort",
  "extensions_add_abort"
);
framework_add_permission ( "extensions_add", __ ( "Add extensions"));
framework_add_api_call (
  "/extensions",
  "Create",
  "extensions_add",
  array (
    "permissions" => array ( "user", "extensions_add"),
    "title" => __ ( "Add extensions"),
    "description" => __ ( "Add a new system extension.")
  )
);

/**
 * Function to add a new extension.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extensions_add_start"))
  {
    $parameters = framework_call ( "extensions_add_start", $parameters);
  }

  /**
   * Call start subhook if exist
   */
  if ( framework_has_hook ( "extensions_add_" . $parameters["Type"] . "_start"))
  {
    $parameters = framework_call ( "extensions_add_" . $parameters["Type"] . "_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = __ ( "The number is required.");
  }
  if ( ! array_key_exists ( "Number", $data) && ! is_numeric ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The informed number is invalid.");
  }
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The description is required.");
  }
  $extensionstypes = (array) filters_call ( "objects_types");
  $valid = false;
  foreach ( $extensionstypes as $type)
  {
    if ( $type["object"] == "extension_" . $parameters["Type"])
    {
      $valid = true;
      break;
    }
  }
  if ( ! $valid)
  {
    $data["Type"] = __ ( "The selected type is invalid.");
    $parameters["Type"] = "";
  }
  if ( ! array_key_exists ( "Type", $data) && empty ( $parameters["Type"]))
  {
    $data["Type"] = __ ( "The type is required.");
  }

  /**
   * Check if extension number is already in use
   */
  if ( ! array_key_exists ( "Number", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Number` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Number"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Number"] = __ ( "The number is already in use.");
    }
  }

  /**
   * Check if extension number is inside a valid range
   */
  if ( ! array_key_exists ( "Number", $data))
  {
    $parameters["Range"] = filters_call ( "search_range", array ( "Number" => (int) $parameters["Number"]));
    if ( sizeof ( $parameters["Range"]) == 0)
    {
      $data["Number"] = __ ( "The number is not inside a valid system range.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extensions_add_validate"))
  {
    $data = framework_call ( "extensions_add_validate", $parameters, false, $data);
  }

  /**
   * Call validate subhook if exist
   */
  if ( framework_has_hook ( "extensions_add_" . $parameters["Type"] . "_validate"))
  {
    $data = framework_call ( "extensions_add_" . $parameters["Type"] . "_validate", $parameters, false, $data);
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
   * Sanitize received parameters
   */
  $parameters["Number"] = (int) $parameters["Number"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "extensions_add_sanitize"))
  {
    $parameters = framework_call ( "extensions_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call sanitize subhook if exist
   */
  if ( framework_has_hook ( "extensions_add_" . $parameters["Type"] . "_sanitize"))
  {
    $parameters = framework_call ( "extensions_add_" . $parameters["Type"] . "_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extensions_add_pre"))
  {
    $parameters = framework_call ( "extensions_add_pre", $parameters, false, $parameters);
  }

  /**
   * Call pre subhook if exist
   */
  if ( framework_has_hook ( "extensions_add_" . $parameters["Type"] . "_pre"))
  {
    $parameters = framework_call ( "extensions_add_" . $parameters["Type"] . "_pre", $parameters, false, $parameters);
  }

  /**
   * Add new extension record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Extensions` (`Number`, `Description`, `Range`, `Type`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Range"]["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Type"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extensions_add_post"))
  {
    framework_call ( "extensions_add_post", $parameters);
  }

  /**
   * Call post subhook if exist
   */
  if ( framework_has_hook ( "extensions_add_" . $parameters["Type"] . "_post"))
  {
    framework_call ( "extensions_add_" . $parameters["Type"] . "_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extensions_add_finish"))
  {
    framework_call ( "extensions_add_finish", $parameters, false);
  }

  /**
   * Execute subhook finish hook if exist
   */
  if ( framework_has_hook ( "extensions_add_" . $parameters["Type"] . "_finish"))
  {
    framework_call ( "extensions_add_" . $parameters["Type"] . "_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/extensions/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to abort an extension addition.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_abort ( $buffer, $parameters)
{
  global $_in;

  /**
   * Remove new extension record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * API call to edit an existing extension
 */
framework_add_hook (
  "extensions_edit",
  "extensions_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Number" => array (
          "type" => "integer",
          "description" => __ ( "The number of the extension."),
          "required" => true,
          "example" => 1000
        ),
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the extension."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Type" => array (
          "type" => "string",
          "description" => __ ( "The extension type."),
          "enum" => array (),
          "required" => true
        )
      )
    ),
    "response" => array (
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Number" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The number is already in use.")
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The description is required.")
            ),
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected type is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_hook ( "extensions_edit_abort", "extensions_edit_abort");
framework_add_permission ( "extensions_edit", __ ( "Edit extensions"));
framework_add_api_call (
  "/extensions/:ID",
  "Modify",
  "extensions_edit",
  array (
    "permissions" => array ( "user", "extensions_edit"),
    "title" => __ ( "Edit extensions"),
    "description" => __ ( "Edit a system extension.")
  )
);
framework_add_api_call (
  "/extensions/:ID",
  "Edit",
  "extensions_edit",
  array (
    "permissions" => array ( "user", "extensions_edit"),
    "title" => __ ( "Edit extensions"),
    "description" => __ ( "Edit a system extension.")
  )
);

/**
 * Function to edit an existing extension.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * First, we get actual extension from database to get extension type
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
  $parameters["Type"] = $parameters["ORIGINAL"]["Type"];

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extensions_edit_start"))
  {
    $parameters = framework_call ( "extensions_edit_start", $parameters);
  }

  /**
   * Call start subhook if exist
   */
  if ( framework_has_hook ( "extensions_edit_" . $parameters["Type"] . "_start"))
  {
    $parameters = framework_call ( "extensions_edit_" . $parameters["Type"] . "_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Number", $parameters))
  {
    $data["Number"] = __ ( "The number is required.");
  }
  if ( ! array_key_exists ( "Number", $data) && ! is_numeric ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The informed number is invalid.");
  }
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The description is required.");
  }
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The description is required.");
  }

  /**
   * Get actual extension range from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["Range"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $parameters["ORIGINAL"]["Range"] = $result->fetch_assoc ();

  /**
   * If extension number changed, check if is already in use
   */
  if ( ! array_key_exists ( "Number", $data) && $parameters["ORIGINAL"]["Number"] != $parameters["Number"])
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Number` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Number"] = __ ( "The number is already in use.");
    }
  }

  /**
   * Check if extension is inside a valid range
   */
  if ( ! array_key_exists ( "Number", $data))
  {
    $parameters["Range"] = filters_call ( "search_range", array ( "Number" => $parameters["Number"]));
    if ( sizeof ( $parameters["Range"]) == 0)
    {
      $data["Number"] = __ ( "The number is not inside a valid system range.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extensions_edit_validate"))
  {
    $data = framework_call ( "extensions_edit_validate", $parameters, false, $data);
  }

  /**
   * Call validate subhook if exist
   */
  if ( framework_has_hook ( "extensions_edit_" . $parameters["Type"] . "_validate"))
  {
    $data = framework_call ( "extensions_edit_" . $parameters["Type"] . "_validate", $parameters, false, $data);
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
  $parameters["Number"] = (int) $parameters["Number"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "extensions_edit_sanitize"))
  {
    $parameters = framework_call ( "extensions_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call sanitize subhook if exist
   */
  if ( framework_has_hook ( "extensions_edit_" . $parameters["Type"] . "_sanitize"))
  {
    $parameters = framework_call ( "extensions_edit_" . $parameters["Type"] . "_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extensions_edit_pre"))
  {
    $parameters = framework_call ( "extensions_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Call pre subhook if exist
   */
  if ( framework_has_hook ( "extensions_edit_" . $parameters["Type"] . "_pre"))
  {
    $parameters = framework_call ( "extensions_edit_" . $parameters["Type"] . "_pre", $parameters, false, $parameters);
  }

  /**
   * Update extension database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Extensions` SET `Number` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . ", `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extensions_edit_post"))
  {
    framework_call ( "extensions_edit_post", $parameters);
  }

  /**
   * Call post subhook if exist
   */
  if ( framework_has_hook ( "extensions_edit_" . $parameters["Type"] . "_post"))
  {
    framework_call ( "extensions_edit_" . $parameters["Type"] . "_post", $parameters);
  }

  /**
   * Call extension number change hooker if needed
   */
  if ( $parameters["Number"] != $parameters["ORIGINAL"]["Number"] && framework_has_hook ( "extensions_number_changed"))
  {
    framework_call ( "extensions_number_changed", array ( "ID" => $parameters["ID"], "Old" => $parameters["ORIGINAL"]["Number"], "New" => $parameters["Number"], "OldServer" => $parameters["ORIGINAL"]["Range"]["Server"], "NewServer" => $parameters["Range"]["Server"]));
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extensions_edit_finish"))
  {
    framework_call ( "extensions_edit_finish", $parameters, false);
  }

  /**
   * Execute subhook finish hook if exist
   */
  if ( framework_has_hook ( "extensions_edit_" . $parameters["Type"] . "_finish"))
  {
    framework_call ( "extensions_edit_" . $parameters["Type"] . "_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to abort an extension edition.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_abort ( $buffer, $parameters)
{
  global $_in;

  /**
   * Restore extension record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Extensions` SET `Number` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["Number"]) . ", `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["Description"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * API call to remove a extension
 */
framework_add_hook (
  "extensions_remove",
  "extensions_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system extension was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid extension ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "extensions_remove", __ ( "Remove extensions"));
framework_add_api_call (
  "/extensions/:ID",
  "Delete",
  "extensions_remove",
  array (
    "permissions" => array ( "user", "extensions_remove"),
    "title" => __ ( "Remove extensions"),
    "description" => __ ( "Remove a system extension.")
  )
);

/**
 * Function to remove an existing extension.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * First, we get actual extension from database to get extension type
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extensions_remove_start"))
  {
    $parameters = framework_call ( "extensions_remove_start", $parameters);
  }

  /**
   * Call start subhook if exist
   */
  if ( framework_has_hook ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_start"))
  {
    $parameters = framework_call ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid extension ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extensions_remove_validate"))
  {
    $data = framework_call ( "extensions_remove_validate", $parameters, false, $data);
  }

  /**
   * Call validate subhook if exist
   */
  if ( framework_has_hook ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_validate"))
  {
    $data = framework_call ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "extensions_remove_sanitize"))
  {
    $parameters = framework_call ( "extensions_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call sanitize subhook if exist
   */
  if ( framework_has_hook ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_sanitize"))
  {
    $parameters = framework_call ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extensions_remove_pre"))
  {
    $parameters = framework_call ( "extensions_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Call pre subhook if exist
   */
  if ( framework_has_hook ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_pre"))
  {
    $parameters = framework_call ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_pre", $parameters, false, $parameters);
  }

  /**
   * Remove extension database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extensions_remove_post"))
  {
    framework_call ( "extensions_remove_post", $parameters);
  }

  /**
   * Call post subhook if exist
   */
  if ( framework_has_hook ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_post"))
  {
    framework_call ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_post", $parameters);
  }

  /**
   * Call finish hook if exist
   */
  if ( framework_has_hook ( "extensions_remove_finish"))
  {
    framework_call ( "extensions_remove_finish", $parameters);
  }

  /**
   * Call finish subhook if exist
   */
  if ( framework_has_hook ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_finish"))
  {
    framework_call ( "extensions_remove_" . $parameters["ORIGINAL"]["Type"] . "_finish", $parameters);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to generate extension call's report
 */
framework_add_hook (
  "extensions_report",
  "extensions_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-04-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-31T23:59:59Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the call records made by the required extension."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "\$ref" => "#/components/schemas/call"
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid start date.")
            ),
            "End" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid end date.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "extensions_report", __ ( "Extensions use report"));
framework_add_api_call (
  "/extensions/:ID/report",
  "Read",
  "extensions_report",
  array (
    "permissions" => array ( "user", "extensions_report"),
    "title" => __ ( "Extension report"),
    "description" => __ ( "Generate an extension call's usage report.", true, false),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The extension internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extensions_report_start"))
  {
    $parameters = framework_call ( "extensions_report_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( empty ( $parameters["Start"]))
  {
    $data["Start"] = __ ( "Missing start date.");
  }
  $datecheck = format_form_datetime ( $parameters["Start"]);
  if ( ! array_key_exists ( "Start", $data) && empty ( $datecheck))
  {
    $data["Start"] = __ ( "Invalid start date.");
  }
  if ( empty ( $parameters["End"]))
  {
    $data["End"] = __ ( "Missing end date.");
  }
  $datecheck = format_form_datetime ( $parameters["End"]);
  if ( ! array_key_exists ( "End", $data) && empty ( $datecheck))
  {
    $data["End"] = __ ( "Invalid end date.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extensions_report_validate"))
  {
    $data = framework_call ( "extensions_report_validate", $parameters);
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
  $parameters["Start"] = format_form_datetime ( $parameters["Start"]);
  $parameters["End"] = format_form_datetime ( $parameters["End"]);
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "extensions_report_sanitize"))
  {
    $parameters = framework_call ( "extensions_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extensions_report_pre"))
  {
    $parameters = framework_call ( "extensions_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get user extension information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE ( `srcid` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " OR `dstid` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ") AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $data = array ();
  while ( $call = $records->fetch_assoc ())
  {
    $call["extension"] = $extension["Number"];
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extensions_report_post"))
  {
    $data = framework_call ( "extensions_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extensions_report_finish"))
  {
    framework_call ( "extensions_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
