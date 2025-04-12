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
 * VoIP Domain blocks module API. This module add the API calls related to
 * blocks.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Blocks
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search blocks
 */
framework_add_hook (
  "blocks_search",
  "blocks_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all blocks."),
          "example" => __ ( "SPAM")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Number",
          "example" => "Description,Number"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system blocked numbers."),
        "schema" => array (
          "type" => "array",
          "items" => array (
            "type" => "object",
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The blocked number internal system unique identifier."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the blocked number."),
                "example" => __ ( "SPAM")
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
framework_add_permission ( "blocks_search", __ ( "Search blocks"));
framework_add_api_call (
  "/blocks",
  "Read",
  "blocks_search",
  array (
    "permissions" => array ( "user", "blocks_search"),
    "title" => __ ( "Search blocks"),
    "description" => __ ( "Search for system blocked numbers.")
  )
);

/**
 * Function to search blocks.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "blocks_search_start"))
  {
    $parameters = framework_call ( "blocks_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Blocks");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "blocks_search_validate"))
  {
    $data = framework_call ( "blocks_search_validate", $parameters);
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
  if ( framework_has_hook ( "blocks_search_sanitize"))
  {
    $parameters = framework_call ( "blocks_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "blocks_search_pre"))
  {
    $parameters = framework_call ( "blocks_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search blocks
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Number` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Description`, `Number`"))
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
  if ( framework_has_hook ( "blocks_search_post"))
  {
    $data = framework_call ( "blocks_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "blocks_search_finish"))
  {
    framework_call ( "blocks_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fast search blocks
 */
framework_add_hook (
  "fastsearch_objects",
  "blocks_fastsearch",
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
                "enum" => array ( "blocks")
              )
            )
          )
        )
      )
    )
  )
);

/**
 * Function to fast search blocks.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_fastsearch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Search blocks
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Number`, `Description` FROM `Blocks`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "'" : "") . " ORDER BY `Number`, `Description`"))
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
    $data[] = array ( "ID" => $result["ID"], "Number" => $result["Number"], "Type" => "block", "Description" => $result["Description"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get block information
 */
framework_add_hook (
  "blocks_view",
  "blocks_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the blocked number."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "integer",
              "description" => __ ( "The blocked number internal system unique identifier."),
              "example" => 1
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the blocked number."),
              "example" => __ ( "SPAM")
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
              "example" => __ ( "Invalid block ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "blocks_view", __ ( "View block information"));
framework_add_api_call (
  "/blocks/:ID",
  "Read",
  "blocks_view",
  array (
    "permissions" => array ( "user", "blocks_view"),
    "title" => __ ( "View blocks"),
    "description" => __ ( "Get a blocked number information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The blocked number internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate block information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "blocks_view_start"))
  {
    $parameters = framework_call ( "blocks_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Blocks");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid block ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "blocks_view_validate"))
  {
    $data = framework_call ( "blocks_view_validate", $parameters);
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
  if ( framework_has_hook ( "blocks_view_sanitize"))
  {
    $parameters = framework_call ( "blocks_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "blocks_view_pre"))
  {
    $parameters = framework_call ( "blocks_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search blocks
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $block = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = api_filter_entry ( array ( "ID", "Description", "Number"), $block);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "blocks_view_post"))
  {
    $data = framework_call ( "blocks_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "blocks_view_finish"))
  {
    framework_call ( "blocks_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new block
 */
framework_add_hook (
  "blocks_add",
  "blocks_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the blocked number."),
          "required" => true,
          "example" => __ ( "SPAM")
        ),
        "Number" => array (
          "type" => "string",
          "description" => __ ( "The blocked number in E.164 format."),
          "required" => true,
          "example" => __ ( "+1 123 5551212")
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system blocked number added sucessfully.")
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
framework_add_permission ( "blocks_add", __ ( "Add blocks"));
framework_add_api_call (
  "/blocks",
  "Create",
  "blocks_add",
  array (
    "permissions" => array ( "user", "blocks_add"),
    "title" => __ ( "Add blocks"),
    "description" => __ ( "Add a new system blocked number.")
  )
);

/**
 * Function to add a new block.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "blocks_add_start"))
  {
    $parameters = framework_call ( "blocks_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The block description is required.");
  }
  $parameters["Number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Number"])));
  if ( empty ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The block number is required.");
  }
  if ( ! array_key_exists ( "Number", $data) && ! validate_E164 ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The number must be in E.164 format, including the prefix +.");
  }

  /**
   * Check if number was already added
   */
  if ( ! array_key_exists ( "Number", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Number"] = __ ( "The provided number was already in use.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "blocks_add_validate"))
  {
    $data = framework_call ( "blocks_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "blocks_add_sanitize"))
  {
    $parameters = framework_call ( "blocks_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "blocks_add_pre"))
  {
    $parameters = framework_call ( "blocks_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new block record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Blocks` (`Description`, `Number`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "blocks_add_post"))
  {
    framework_call ( "blocks_add_post", $parameters);
  }

  /**
   * Add new block at Asterisk servers
   */
  $notify = array ( "Description" => $parameters["Description"], "Number" => $parameters["Number"]);
  if ( framework_has_hook ( "blocks_add_notify"))
  {
    $notify = framework_call ( "blocks_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "block_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "blocks_add_finish"))
  {
    framework_call ( "blocks_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "blocks/" . $parameters["ID"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing block
 */
framework_add_hook (
  "blocks_edit",
  "blocks_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the blocked number."),
          "required" => true,
          "example" => __ ( "SPAM")
        ),
        "Number" => array (
          "type" => "string",
          "description" => __ ( "The blocked number in E.164 format."),
          "required" => true,
          "example" => __ ( "+1 123 5551212")
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system blocked number was sucessfully updated.")
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
framework_add_permission ( "blocks_edit", __ ( "Edit blocks"));
framework_add_api_call (
  "/blocks/:ID",
  "Modify",
  "blocks_edit",
  array (
    "permissions" => array ( "user", "blocks_edit"),
    "title" => __ ( "Edit blocks"),
    "description" => __ ( "Edit a system blocked number.")
  )
);
framework_add_api_call (
  "/blocks/:ID",
  "Edit",
  "blocks_edit",
  array (
    "permissions" => array ( "user", "blocks_edit"),
    "title" => __ ( "Edit blocks"),
    "description" => __ ( "Edit a system blocked number.")
  )
);

/**
 * Function to edit an existing block.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "blocks_edit_start"))
  {
    $parameters = framework_call ( "blocks_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The block description is required.");
  }
  $parameters["Number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Number"])));
  if ( empty ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The block number is required.");
  }
  if ( ! array_key_exists ( "Number", $data) && ! validate_E164 ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The number must be in E.164 format, including the prefix +.");
  }

  /**
   * Check if number was already added
   */
  if ( ! array_key_exists ( "Number", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Number"] = __ ( "The provided number was already in use.");
    }
  }

  /**
   * Check if block exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $parameters["ORIGINAL"] = $result->fetch_assoc ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "blocks_edit_validate"))
  {
    $data = framework_call ( "blocks_edit_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "blocks_edit_sanitize"))
  {
    $parameters = framework_call ( "blocks_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "blocks_edit_pre"))
  {
    $parameters = framework_call ( "blocks_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change block record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Blocks` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "blocks_edit_post"))
  {
    framework_call ( "blocks_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Description" => $parameters["Description"], "Number" => $parameters["ORIGINAL"]["Number"], "NewNumber" => $parameters["Number"]);
  if ( framework_has_hook ( "blocks_edit_notify"))
  {
    $notify = framework_call ( "blocks_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "block_change", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "blocks_edit_finish"))
  {
    framework_call ( "blocks_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a block
 */
framework_add_hook (
  "blocks_remove",
  "blocks_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system blocked number was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid block ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "blocks_remove", __ ( "Remove blocks"));
framework_add_api_call (
  "/blocks/:ID",
  "Delete",
  "blocks_remove",
  array (
    "permissions" => array ( "user", "blocks_remove"),
    "title" => __ ( "Remove blocks"),
    "description" => __ ( "Remove a blocked number from system.")
  )
);

/**
 * Function to remove an existing block.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "blocks_remove_start"))
  {
    $parameters = framework_call ( "blocks_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid block ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "blocks_remove_validate"))
  {
    $data = framework_call ( "blocks_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "blocks_remove_sanitize"))
  {
    $parameters = framework_call ( "blocks_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if block exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
  if ( framework_has_hook ( "blocks_remove_pre"))
  {
    $parameters = framework_call ( "blocks_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove block database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Blocks` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "blocks_remove_post"))
  {
    framework_call ( "blocks_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Number" => $parameters["ORIGINAL"]["Number"]);
  if ( framework_has_hook ( "blocks_remove_notify"))
  {
    $notify = framework_call ( "blocks_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "block_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "blocks_remove_finish"))
  {
    framework_call ( "blocks_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "blocks_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "blocks_server_reconfig");

/**
 * Function to notify server to include all blocks.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all blocks and send to server
   */
  if ( ! $result = $_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $block = $result->fetch_assoc ())
  {
    $notify = array ( "Description" => $block["Description"], "Number" => $block["Number"]);
    if ( framework_has_hook ( "blocks_add_notify"))
    {
      $notify = framework_call ( "blocks_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "block_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
