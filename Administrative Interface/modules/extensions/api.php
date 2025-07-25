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
framework_add_permission ( "extensions_search", __ ( "Search extensions"));
framework_add_api_call (
  "/extensions",
  "Read",
  "extensions_search",
  array (
    "permissions" => array ( "Administrator", "extensions_search"),
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
   * Add function parameters
   */
  $parameters["function"] = array (
    "DefaultFields" => "ID,Number,Description,Type",
    "PermittedFields" => "ID,Number,Description,Type"
  );

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
  if ( ! array_key_exists ( "Fields", $parameters) || $parameters["Fields"] == "" || ( is_array ( $parameters["Fields"]) && sizeof ( $parameters["Fields"]) == 0))
  {
    $parameters["Fields"] = $parameters["function"]["DefaultFields"];
  }
  if ( ! api_filter_validate ( $parameters["Fields"], $parameters["function"]["PermittedFields"]))
  {
    $data["Fields"] = __ ( "Fields contains invalid values.");
  }

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
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Number`, `Description`, `Type` FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . ( ! empty ( $parameters["Filter"]) ? " AND (`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "')" : "") . ( ! empty ( $parameters["Except"]) ? " AND `ID` != " . (int) $parameters["Except"] : "") . ( ! empty ( $parameters["Type"]) ? " AND `Type` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Type"]) . "'" : "") . " ORDER BY `Description`, `Number`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], $parameters["function"]["DefaultFields"], $parameters["function"]["PermittedFields"]);
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
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Number`, `Description`, `Type` FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . ( ! empty ( $parameters["Filter"]) ? " AND (`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "')" : "") . " ORDER BY `Number`, `Description`"))
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
    "permissions" => array ( "Administrator", "extensions_view"),
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $extension = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

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
    "permissions" => array ( "Administrator", "extensions_inuse"),
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Number` = " . (int) $parameters["Number"]))
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
    "permissions" => array ( "Administrator", "extensions_next_number"),
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Start`, `Finish` FROM `Ranges` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . ( $parameters["Range"] != 0 && $parameters["Server"] != 0 ? " AND `Server` = " . (int) $parameters["Server"] . ( $parameters["Range"] != 0 ? " AND `ID` = " . (int) $parameters["Range"] : "") : "")))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $ranges = array ();
  while ( $range = $result->fetch_assoc ())
  {
    $ranges[$range["ID"]] = array ( "Start" => $range["Start"], "Finish" => $range["Finish"]);
  }
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number`, `Range` FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " ORDER BY `Number`"))
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
        "description" => __ ( "New system extension added successfully.")
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
    "permissions" => array ( "Administrator", "extensions_add"),
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
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Number` = " . (int) $parameters["Number"]))
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
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Extensions` (`Tenant`, `Number`, `Description`, `Range`, `Type`) VALUES (" . (int) $_in["session"]["Tenant"] . ", " . (int) $parameters["Number"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', " . (int) $parameters["Range"]["ID"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Type"]) . "')"))
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
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
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
  array ( "Modify", "Edit"),
  "extensions_edit",
  array (
    "permissions" => array ( "Administrator", "extensions_edit"),
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ORIGINAL"]["Range"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $parameters["ORIGINAL"]["Range"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * If extension number changed, check if is already in use
   */
  if ( ! array_key_exists ( "Number", $data) && $parameters["ORIGINAL"]["Number"] != $parameters["Number"])
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Number` = " . (int) $parameters["Number"]))
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
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Extensions` SET `Number` = " . (int) $parameters["Number"] . ", `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "' WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
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
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Extensions` SET `Number` = " . (int) $parameters["ORIGINAL"]["Number"] . ", `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["Description"]) . "' WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
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
    "permissions" => array ( "Administrator", "extensions_remove"),
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

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
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
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
    "permissions" => array ( "Administrator", "extensions_report"),
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $extension = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND (`srcid` = " . (int) $parameters["ID"] . " OR `dstid` = " . (int) $parameters["ID"] . ") AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
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

/**
 * API call to get list of extensions that a user has webphone extension
 */
framework_add_hook (
  "extensions_webphone_list",
  "extensions_webphone_list",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter list with this string. If not provided, return all extensions."),
          "example" => __ ( "filter")
        ),
        "Except" => array (
          "type" => "integer",
          "description" => __ ( "Don't return extension with unique identifier provided here.", true, false),
          "example" => 3
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Number,Description",
          "example" => "Number,Description"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the list of available webphone extensions."),
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
                "description" => __ ( "The telephone number of the webphone extension."),
                "example" => 1000
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the webphone extension."),
                "example" => __ ( "John Doe")
              ),
              "Account" => array (
                "type" => "string",
                "description" => __ ( "The username of the webphone extension."),
                "example" => "u1000-0"
              ),
              "Password" => array (
                "type" => "string",
                "description" => __ ( "The password of the webphone extension."),
                "example" => "4e47e410ca"
              ),
              "Domain" => array (
                "type" => "string",
                "description" => __ ( "The SIP domain of the webphone extension."),
                "example" => "voipdomain.io"
              ),
              "Language" => array (
                "type" => "object",
                "description" => __ ( "The language used on this webphone extension."),
                "properties" => array (
                  "Code" => array (
                    "type" => "string",
                    "description" => __ ( "The code of the language."),
                    "example" => "en_US"
                  ),
                  "DescriptionEN" => array (
                    "type" => "string",
                    "description" => __ ( "The description in English of the language."),
                    "example" => "English (United States)"
                  ),
                  "Description" => array (
                    "type" => "string",
                    "description" => __ ( "The translated description of the language."),
                    "example" => __ ( "English (United States)")
                  )
                )
              ),
              "AreaCode" => array (
                "type" => "integer",
                "description" => __ ( "The area code of the webphone extension."),
                "example" => 704
              ),
              "Prefix" => array (
                "type" => "string",
                "description" => __ ( "The prefix to access PSTN of the webphone extension."),
                "example" => "0"
              ),
              "TimeZone" => array (
                "type" => "string",
                "description" => __ ( "The time zone of the webphone extension."),
                "example" => "America/Los_Angeles"
              ),
              "Offset" => array (
                "type" => "float",
                "description" => __ ( "The time offset of the webphone extension."),
                "example" => -8
              ),
              "Country" => array (
                "type" => "string",
                "description" => __ ( "The country ISO 3166-2 code of the webphone extension."),
                "example" => __ ( "US")
              ),
              "Address" => array (
                "type" => "string",
                "description" => __ ( "The IP address of the server."),
                "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
                "example" => "192.168.0.1"
              ),
              "Port" => array (
                "type" => "integer",
                "description" => __ ( "The IP port of the server."),
                "minimum" => 0,
                "maximum" => 65535,
                "example" => 5060
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
framework_add_permission ( "extensions_list", __ ( "Get webphone extensions list"));
framework_add_api_call (
  "/extensions/webphone",
  "Read",
  "extensions_webphone_list",
  array (
    "permissions" => array ( "AuthToken"),
    "title" => __ ( "Get webphone extensions list"),
    "description" => __ ( "Get list of user webphone extensions.")
  )
);

/**
 * Function to get list of user webphone extensions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_webphone_list ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add function parameters
   */
  $parameters["function"] = array (
    "DefaultFields" => "ID,Number,Description,Account,Password,Domain,Language,AreaCode,Prefix,TimeZone,Offset,Country,Address,Port",
    "PermittedFields" => "ID,Number,Description,Account,Password,Domain,Language,AreaCode,Prefix,TimeZone,Offset,Country,Address,Port"
  );

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "extensions_webphone_list_start"))
  {
    $parameters = framework_call ( "extensions_webphone_list_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Extensions");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Fields", $parameters) || $parameters["Fields"] == "" || ( is_array ( $parameters["Fields"]) && sizeof ( $parameters["Fields"]) == 0))
  {
    $parameters["Fields"] = $parameters["function"]["DefaultFields"];
  }
  if ( ! api_filter_validate ( $parameters["Fields"], $parameters["function"]["PermittedFields"]))
  {
    $data["Fields"] = __ ( "Fields contains invalid values.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "extensions_webphone_list_validate"))
  {
    $data = framework_call ( "extensions_webphone_list_validate", $parameters);
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
  if ( framework_has_hook ( "extensions_webphone_list_sanitize"))
  {
    $parameters = framework_call ( "extensions_webphone_list_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "extensions_webphone_list_pre"))
  {
    $parameters = framework_call ( "extensions_webphone_list_pre", $parameters, false, $parameters);
  }

  /**
   * Search extensions
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Number`, `Extensions`.`Description`, `PhoneAccounts`.`Username` AS `Account`, `PhoneAccounts`.`Password`, `Profiles`.`Domain`, `Profiles`.`Language`, `Profiles`.`AreaCode`, `Profiles`.`Prefix`, `Profiles`.`TimeZone`, `Profiles`.`Offset`, `Countries`.`ISO3166-2` AS `Country`, `Servers`.`Address`, `Servers`.`Port` FROM `ExtensionPhone` LEFT JOIN `Extensions` ON `Extensions`.`ID` = `ExtensionPhone`.`Extension` LEFT JOIN `Ranges` ON `Ranges`.`ID` = `Extensions`.`Range` LEFT JOIN `PhoneAccounts` ON `PhoneAccounts`.`Extension` = `Extensions`.`ID` LEFT JOIN `Equipments` ON `Equipments`.`ID` = `PhoneAccounts`.`Equipment` LEFT JOIN `Groups` ON `Groups`.`ID` = `ExtensionPhone`.`Group` LEFT JOIN `Profiles` ON `Profiles`.`ID` = `Groups`.`Profile` LEFT JOIN `Countries` ON `Countries`.`Code` = `Profiles`.`Country` LEFT JOIN `Servers` ON `Servers`.`ID` = `Ranges`.`Server` WHERE `Extensions`.`Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Equipments`.`UID` = 'webphone' AND `ExtensionPhone`.`Email` = '" . $_in["mysql"]["id"]->real_escape_string ( $_in["session"]["Data"]["Email"]) . "'" . ( ! empty ( $parameters["Filter"]) ? " AND (`Extensions`.`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Extensions`.`Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "')" : "") . ( ! empty ( $parameters["Except"]) ? " AND `Extensions`.`ID` != " . (int) $parameters["Except"] : "") . " ORDER BY `Number`, `Description`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], $parameters["function"]["DefaultFields"], $parameters["function"]["PermittedFields"]);
  while ( $result = $results->fetch_assoc ())
  {
    $result["Language"] = filters_call ( "get_locale", array ( "Code" => $result["Language"]));
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "extensions_webphone_list_post"))
  {
    $data = framework_call ( "extensions_webphone_list_post", $parameters, false, $data); 
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "extensions_webphone_list_finish"))
  {
    framework_call ( "extensions_webphone_list_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data); 
}
?>
