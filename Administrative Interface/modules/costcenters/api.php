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
 * VoIP Domain cost centers module API. This module add the API calls related to
 * cost centers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Cost Centers
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search costcenters
 */
framework_add_hook (
  "costcenters_search",
  "costcenters_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all cost centers."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Code",
          "example" => "Description,Code"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the cost centers."),
        "schema" => array (
          "type" => "array",
          "items" => array (
            "type" => "object",
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the cost center."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the cost center."),
                "example" => __ ( "IT Department")
              ),
              "Code" => array (
                "type" => "string",
                "description" => __ ( "The code of the cost center."),
                "pattern" => "/^[0-9]+$/",
                "example" => "100001"
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
framework_add_permission ( "costcenters_search", __ ( "Search costs centers"));
framework_add_api_call (
  "/costcenters",
  "Read",
  "costcenters_search",
  array (
    "permissions" => array ( "user", "costcenters_search"),
    "title" => __ ( "Search cost centers"),
    "description" => __ ( "Search for cost centers.")
  )
);

/**
 * Function to search costs centers.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "costcenterss_search_start"))
  {
    $parameters = framework_call ( "costcenterss_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "CostCenters");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "costcenters_search_validate"))
  {
    $data = framework_call ( "costcenters_search_validate", $parameters);
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
  if ( framework_has_hook ( "costcenters_search_sanitize"))
  {
    $parameters = framework_call ( "costcenters_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "costcenters_search_pre"))
  {
    $parameters = framework_call ( "costcenters_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search cost centers
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Code` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Description`, `Code`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Code", "ID,Description,Code");
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "costcenters_search_post"))
  {
    $data = framework_call ( "costcenters_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "costcenters_search_finish"))
  {
    framework_call ( "costcenters_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get costcenter information
 */
framework_add_hook (
  "costcenters_view",
  "costcenters_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the cost center."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "integer",
              "description" => __ ( "The cost center internal unique identifier."),
              "example" => 1
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the cost center."),
              "example" => __ ( "IT Department")
            ),
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The code of the cost center."),
              "pattern" => "/^[0-9]+$/",
              "example" => "100001"
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
              "example" => __ ( "Invalid cost center ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "costcenters_view", __ ( "View costs centers information"));
framework_add_api_call (
  "/costcenters/:ID",
  "Read",
  "costcenters_view",
  array (
    "permissions" => array ( "user", "costcenters_view"),
    "title" => __ ( "View cost centers"),
    "description" => __ ( "Get a cost center information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The cost center internal unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate costcenter information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "costcenters_view_start"))
  {
    $parameters = framework_call ( "costcenters_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "CostCenters");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid cost center ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "costcenters_view_validate"))
  {
    $data = framework_call ( "costcenters_view_validate", $parameters);
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
  if ( framework_has_hook ( "costcenters_view_sanitize"))
  {
    $parameters = framework_call ( "costcenters_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "costcenters_view_pre"))
  {
    $parameters = framework_call ( "costcenters_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search cost centers
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $costcenter = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = api_filter_entry ( array ( "ID", "Description", "Code"), $costcenter);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "costcenters_view_post"))
  {
    $data = framework_call ( "costcenters_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "costcenters_view_finish"))
  {
    framework_call ( "costcenters_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new costcenter
 */
framework_add_hook (
  "costcenters_add",
  "costcenters_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the cost center."),
          "required" => true,
          "example" => __ ( "IT Department")
        ),
        "Code" => array (
          "type" => "string",
          "description" => __ ( "The code of the cost center."),
          "pattern" => "/^[0-9]+$/",
          "required" => true,
          "eaxmple" => "100001"
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New cost center added sucessfully.")
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
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The informed code is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "costcenters_add", __ ( "Add costs centers"));
framework_add_api_call (
  "/costcenters",
  "Create",
  "costcenters_add",
  array (
    "permissions" => array ( "user", "costcenters_add"),
    "title" => __ ( "Add cost centers"),
    "description" => __ ( "Add a new cost center.")
  )
);

/**
 * Function to add a new costcenter.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "costcenters_add_start"))
  {
    $parameters = framework_call ( "costcenters_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The cost center description is required.");
  }
  if ( empty ( $parameters["Code"]))
  {
    $data["Code"] = __ ( "The cost center code is required.");
  }
  if ( ! array_key_exists ( "Code", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Code"]))
  {
    $data["Code"] = __ ( "The informed code is invalid.");
  }

  /**
   * Check if code was in use
   */
  if ( ! array_key_exists ( "Code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Code"] = __ ( "The code was already in use.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "costcenters_add_validate"))
  {
    $data = framework_call ( "costcenters_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "costcenters_add_sanitize"))
  {
    $parameters = framework_call ( "costcenters_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "costcenters_add_pre"))
  {
    $parameters = framework_call ( "costcenters_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new costcenter record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `CostCenters` (`Description`, `Code`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "costcenters_add_post"))
  {
    framework_call ( "costcenters_add_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "costcenters_add_finish"))
  {
    framework_call ( "costcenters_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "costcenters/" . $parameters["ID"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing costcenter
 */
framework_add_hook (
  "costcenters_edit",
  "costcenters_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the cost center."),
          "required" => true,
          "example" => __ ( "IT Department")
        ),
        "Code" => array (
          "type" => "string",
          "description" => __ ( "The code of the cost center."),
          "pattern" => "/^[0-9]+$/",
          "required" => true,
          "example" => "100001"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The cost center was sucessfully updated.")
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
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The informed code is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "costcenters_edit", __ ( "Edit cost centers"));
framework_add_api_call (
  "/costcenters/:ID",
  "Modify",
  "costcenters_edit",
  array (
    "permissions" => array ( "user", "costcenters_edit"),
    "title" => __ ( "Edit cost centers"),
    "description" => __ ( "Edit a cost center.")
  )
);
framework_add_api_call (
  "/costcenters/:ID",
  "Edit",
  "costcenters_edit",
  array (
    "permissions" => array ( "user", "costcenters_edit"),
    "title" => __ ( "Edit costcenters"),
    "description" => __ ( "Edit a cost center.")
  )
);

/**
 * Function to edit an existing costcenter.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "costcenters_edit_start"))
  {
    $parameters = framework_call ( "costcenters_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The cost center description is required.");
  }
  if ( empty ( $parameters["Code"]))
  {
    $data["Code"] = __ ( "The cost center code is required.");
  }
  if ( ! array_key_exists ( "Code", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["Code"]))
  {
    $data["Code"] = __ ( "The informed code is invalid.");
  }

  /**
   * Check if code was in use
   */
  if ( ! array_key_exists ( "Code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Code"] = __ ( "The code was already in use.");
    }
  }

  /**
   * Check if cost center exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
  if ( framework_has_hook ( "costcenters_edit_validate"))
  {
    $data = framework_call ( "costcenters_edit_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "costcenters_edit_sanitize"))
  {
    $parameters = framework_call ( "costcenters_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "costcenters_edit_pre"))
  {
    $parameters = framework_call ( "costcenters_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change cost center record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `CostCenters` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "costcenters_edit_post"))
  {
    framework_call ( "costcenters_edit_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "costcenters_edit_finish"))
  {
    framework_call ( "costcenters_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a costcenter
 */
framework_add_hook (
  "costcenters_remove",
  "costcenters_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The cost center was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid cost center ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "costcenters_remove", __ ( "Remove costs centers"));
framework_add_api_call (
  "/costcenters/:ID",
  "Delete",
  "costcenters_remove",
  array (
    "permissions" => array ( "user", "costcenters_remove"),
    "title" => __ ( "Remove cost centers"),
    "description" => __ ( "Remove a cost center.")
  )
);

/**
 * Function to remove an existing costcenter.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "costcenters_remove_start"))
  {
    $parameters = framework_call ( "costcenters_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid cost center ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "costcenters_remove_validate"))
  {
    $data = framework_call ( "costcenters_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "costcenters_remove_sanitize"))
  {
    $parameters = framework_call ( "costcenters_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if costcenter exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
  if ( framework_has_hook ( "costcenters_remove_pre"))
  {
    $parameters = framework_call ( "costcenters_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove cost center database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "costcenters_remove_post"))
  {
    framework_call ( "costcenters_remove_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "costcenters_remove_finish"))
  {
    framework_call ( "costcenters_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}
?>
