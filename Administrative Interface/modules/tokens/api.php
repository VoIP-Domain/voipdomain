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
 * VoIP Domain tokens module API. This module add the API calls related to
 * tokens.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Tokens
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search token permissions
 */
framework_add_hook (
  "tokens_permissions_search",
  "tokens_permissions_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all tokens."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "Token,Description",
          "example" => "Token"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system tokens."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "permission"
            ),
            "properties" => array (
              "Token" => array (
                "type" => "string",
                "description" => __ ( "The name of the token."),
                "example" => "tokens_search"
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the token."),
                "example" => __ ( "Search tokens")
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
framework_add_api_call (
  "/tokens/permissions",
  "Read",
  "tokens_permissions_search",
  array (
    "permissions" => array ( "user", "token"),
    "title" => __ ( "Search tokens"),
    "description" => __ ( "Search for system permission tokens.")
  )
);

/**
 * Function to search token permissions.
 *
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_permissions_search ( $buffer, $parameters)
{
  global $_api;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tokens_permissions_search_start"))
  {
    $parameters = framework_call ( "tokens_permissions_search_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tokens_permissions_search_validate"))
  {
    $data = framework_call ( "tokens_permissions_search_validate", $parameters);
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
  if ( framework_has_hook ( "tokens_permissions_search_sanitize"))
  {
    $parameters = framework_call ( "tokens_permissions_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tokens_permissions_search_pre"))
  {
    $parameters = framework_call ( "tokens_permissions_search_pre", $parameters, false, $parameters);
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "Token,Description", "Token,Description");
  foreach ( $_api["permissions"] as $token => $description)
  {
    $result = array ();
    $result["Token"] = $token;
    $result["Description"] = $description;
    if ( ! empty ( $parameters["Filter"]) && strpos ( $result["Description"], $parameters["Filter"]) !== false)
    {
      continue;
    }
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tokens_permissions_search_post"))
  {
    $data = framework_call ( "tokens_permissions_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tokens_permissions_search_finish"))
  {
    framework_call ( "tokens_permissions_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to search tokens
 */
framework_add_hook (
  "tokens_search",
  "tokens_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all tokens."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Expire,ExpireTimestamp",
          "example" => "Description,Expire"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system tokens."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "token"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the token."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the token."),
                "example" => __ ( "CRM integration")
              ),
              "Expire" => array (
                "type" => "string",
                "description" => __ ( "The date and time of the token expiration (ISO8601 format)."),
                "example" => "2020-04-01T20:09:10Z"
              ),
              "ExpireTimestamp" => array (
                "type" => "integer",
                "description" => __ ( "The UNIX timestamp of the token expiration."),
                "example" => 1587510559
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
framework_add_permission ( "tokens_search", __ ( "Search tokens"));
framework_add_api_call (
  "/tokens",
  "Read",
  "tokens_search",
  array (
    "permissions" => array ( "administrator", "tokens_search"),
    "title" => __ ( "Search tokens"),
    "description" => __ ( "Search for system tokens.")
  )
);

/**
 * Function to search tokens.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tokens_search_start"))
  {
    $parameters = framework_call ( "tokens_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Tokens");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tokens_search_validate"))
  {
    $data = framework_call ( "tokens_search_validate", $parameters);
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
  if ( framework_has_hook ( "tokens_search_sanitize"))
  {
    $parameters = framework_call ( "tokens_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tokens_search_pre"))
  {
    $parameters = framework_call ( "tokens_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search tokens
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Description`, `Expire` FROM `Tokens`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Description`, `Expire`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Expire,ExpireTimestamp", "ID,Description,Expire,ExpireTimestamp");
  while ( $result = $results->fetch_assoc ())
  {
    $result["ExpireTimestamp"] = format_db_timestamp ( $result["Expire"]);
    $result["Expire"] = format_db_iso8601 ( $result["Expire"]);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tokens_search_post"))
  {
    $data = framework_call ( "tokens_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tokens_search_finish"))
  {
    framework_call ( "tokens_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get token information
 */
framework_add_hook (
  "tokens_view",
  "tokens_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system token."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the token."),
              "example" => __ ( "CRM integration")
            ),
            "Token" => array (
              "type" => "string",
              "description" => __ ( "The token hash of the token."),
              "example" => "7412fb7d1a498b08-09780187-2e2ae81f-386b3688-76e0e0a242ac0a472e0837d1"
            ),
            "Access" => array (
              "type" => "string",
              "description" => __ ( "The CIDR access allowed to use this token."),
              "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}\/([0-9]|[1-2][0-9]|3[0-2])$/",
              "example" => "192.168.1.0/24"
            ),
            "Permissions" => array (
              "type" => "array",
              "description" => __ ( "The list of permissions this token has."),
              "xml" => array (
                "name" => "Permissions",
                "wrapped" => true
              ),
              "items" => array (
                "type" => "object",
                "aditionalProperties" => array (
                  "type" => "string",
                  "description" => __ ( "The token permission description, associated to token key."),
                )
              ),
              "example" => array (
                "tokens_view" => __ ( "View token information"),
                "tokens_add" => __ ( "Add tokens"),
                "tokens_edit" => __ ( "Edit tokens"),
              )
            ),
            "Validity" => array (
              "type" => "string",
              "description" => __ ( "The date and time of the token expiration (ISO8601 format)."),
              "example" => "2020-04-01T20:09:10Z"
            ),
            "Language" => array (
              "type" => "string",
              "description" => __ ( "The language of the token. If system default, will be `default`."),
              "example" => "en_US"
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
              "example" => __ ( "Invalid token ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "tokens_view", __ ( "View token information"));
framework_add_api_call (
  "/tokens/:ID",
  "Read",
  "tokens_view",
  array (
    "permissions" => array ( "administrator", "tokens_view"),
    "title" => __ ( "View tokens"),
    "description" => __ ( "Get a system token information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The system token internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate token information.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_view ( $buffer, $parameters)
{
  global $_in, $_api;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tokens_view_start"))
  {
    $parameters = framework_call ( "tokens_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Tokens");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid token ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tokens_view_validate"))
  {
    $data = framework_call ( "tokens_view_validate", $parameters);
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
  if ( framework_has_hook ( "tokens_view_sanitize"))
  {
    $parameters = framework_call ( "tokens_view_sanitize", $parameters, false, $parameters);
  }
  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tokens_view_pre"))
  {
    $parameters = framework_call ( "tokens_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search tokens
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $token = $result->fetch_assoc ();
  $permissions = explode ( ",", $token["Permissions"]);

  /**
   * Format data
   */
  $token["Permissions"] = array ();
  foreach ( $permissions as $permission)
  {
    $token["Permissions"][$permission] = $_api["permissions"][$permission];
  }
  $token["Validity"] = format_db_iso8601 ( $token["Expire"]);
  $token["Language"] = ( $token["Language"] != "" ? $token["Language"] : "default");
  $data = api_filter_entry ( array ( "Description", "Token", "Access", "Permissions", "Validity", "Language"), $token);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tokens_view_post"))
  {
    $data = framework_call ( "tokens_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tokens_view_finish"))
  {
    framework_call ( "tokens_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new token
 */
framework_add_hook (
  "tokens_add",
  "tokens_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Token" => array (
          "type" => "string",
          "description" => __ ( "The token hash of the token."),
          "required" => true,
          "example" => "7412fb7d1a498b08-09780187-2e2ae81f-386b3688-76e0e0a242ac0a472e0837d1"
        ),
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the token."),
          "required" => true,
          "example" => __ ( "CRM Integration")
        ),
        "Access" => array (
          "type" => "string",
          "description" => __ ( "The network CIDR from which token will have permission to request."),
          "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}\/([0-9]|[1-2][0-9]|3[0-2])$/",
          "required" => true,
          "example" => "192.168.0.0/16"
        ),
        "Permissions" => array (
          "description" => __ ( "An array containing all system permissions token."),
          "type" => "array",
          "items" => array (
            "type" => "string"
          ),
          "required" => true,
          "example" => array (
            "tokens_add",
            "tokens_edit",
            "tokens_view"
          )
        ),
        "Validity" => array (
          "type" => "string",
          "description" => __ ( "The date and time of the token expiration (ISO8601 format)."),
          "required" => true,
          "example" => "2020-04-01T20:09:10Z"
        ),
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system token added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The token description is required.")
            ),
            "Token" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided token is invalid.")
            ),
            "Access" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The access CIDR provided is invalid.")
            ),
            "Permissions" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "At least one permission is required.")
            ),
            "Validity" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The token validity is required.")
            ),
            "Language" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The select language are invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "tokens_add", __ ( "Add tokens"));
framework_add_api_call (
  "/tokens",
  "Create",
  "tokens_add",
  array (
    "permissions" => array ( "administrator", "tokens_add"),
    "title" => __ ( "Add tokens"),
    "description" => __ ( "Add a new system token.")
  )
);

/**
 * Function to add a new token.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_add ( $buffer, $parameters)
{
  global $_in, $_api;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tokens_add_start"))
  {
    $parameters = framework_call ( "tokens_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The token description is required.");
  }
  $parameters["Token"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Token"])));
  if ( empty ( $parameters["Token"]))
  {
    $data["Token"] = __ ( "The token is required.");
  }
  if ( ! array_key_exists ( "Token", $data) && ! preg_match ( "/^[a-z0-9]{16}-[a-z0-9]{8}-[a-z0-9]{8}-[a-z0-9]{8}-[a-z0-9]{24}\$/", $parameters["Token"]))
  {
    $data["Token"] = __ ( "The provided token is invalid.");
  }
  $parameters["Access"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Access"])));
  if ( empty ( $parameters["Access"]))
  {
    $data["Access"] = __ ( "The token access CIDR is required.");
  }
  if ( ! array_key_exists ( "Access", $data) && inet_pton ( substr ( $parameters["Access"], 0, strpos ( $parameters["Access"], "/"))) === false)
  {
    $data["Access"] = __ ( "The access CIDR provided is invalid.");
  }
  foreach ( $parameters["Permissions"] as $key => $value)
  {
    if ( ! array_key_exists ( $value, $_api["permissions"]))
    {
      $data["Permissions"] = __ ( "Invalid permission.");
    }
  }
  if ( ! array_key_exists ( "Permissions", $data) && sizeof ( $parameters["Permissions"]) == 0)
  {
    $data["Permissions"] = __ ( "At least one permission is required.");
  }
  if ( empty ( $parameters["Validity"]))
  {
    $data["Validity"] = __ ( "The token validity is required.");
  }

  /**
   * Check if token was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `Token` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Token"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Token"] = __ ( "The provided token is already in use.");
  }
  if ( $parameters["Language"] == "default")
  {
    $parameters["Language"] = "";
  } else {
    if ( ! array_key_exists ( $parameters["Language"], $_in["languages"]))
    {
      $data["Language"] = __ ( "The select language are invalid.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tokens_add_validate"))
  {
    $data = framework_call ( "tokens_add_validate", $parameters, false, $data);
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
  $parameters["Validity"] = format_form_datetime ( empty ( $parameters["Validity"]) ? gmdate ( "d/m/Y 23:59", time ()) : urldecode ( $parameters["Validity"]));

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "tokens_add_sanitize"))
  {
    $parameters = framework_call ( "tokens_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tokens_add_pre"))
  {
    $parameters = framework_call ( "tokens_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new token record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Tokens` (`Description`, `Token`, `Access`, `Permissions`, `Expire`, `Language`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Token"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Access"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $parameters["Permissions"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Validity"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tokens_add_post"))
  {
    framework_call ( "tokens_add_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tokens_add_finish"))
  {
    framework_call ( "tokens_add_finish", $parameters, false);
  }

  /**
   * Return OK to token
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/tokens/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing token
 */
framework_add_hook (
  "tokens_edit",
  "tokens_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Token" => array (
          "type" => "string",
          "description" => __ ( "The token hash of the token."),
          "required" => true,
          "example" => "7412fb7d1a498b08-09780187-2e2ae81f-386b3688-76e0e0a242ac0a472e0837d1"
        ),
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the token."),
          "required" => true,
          "example" => __ ( "CRM Integration")
        ),
        "Access" => array (
          "type" => "string",
          "description" => __ ( "The network CIDR from which token will have permission to request."),
          "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}\/([0-9]|[1-2][0-9]|3[0-2])$/",
          "required" => true,
          "example" => "192.168.0.0/16"
        ),
        "Permissions" => array (
          "description" => __ ( "An array containing all system permissions token."),
          "type" => "array",
          "items" => array (
            "type" => "string"
          ),
          "required" => true,
          "example" => array (
            "tokens_add",
            "tokens_edit",
            "tokens_view"
          )
        ),
        "Validity" => array (
          "type" => "string",
          "description" => __ ( "The date and time of the token expiration (ISO8601 format)."),
          "required" => true,
          "example" => "2020-04-01T20:09:10Z"
        ),
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system token was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The token description is required.")
            ),
            "Token" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided token is invalid.")
            ),
            "Access" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The access CIDR provided is invalid.")
            ),
            "Permissions" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "At least one permission is required.")
            ),
            "Validity" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The token validity is required.")
            ),
            "Language" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The select language are invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "tokens_edit", __ ( "Edit tokens"));
framework_add_api_call (
  "/tokens/:ID",
  "Modify",
  "tokens_edit",
  array (
    "permissions" => array ( "administrator", "tokens_edit"),
    "title" => __ ( "Edit token"),
    "description" => __ ( "Change a system token information.")
  )
);
framework_add_api_call (
  "/tokens/:ID",
  "Edit",
  "tokens_edit",
  array (
    "permissions" => array ( "administrator", "tokens_edit"),
    "title" => __ ( "Edit token"),
    "description" => __ ( "Change a system token information.")
  )
);

/**
 * Function to edit an existing token.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_edit ( $buffer, $parameters)
{
  global $_in, $_api;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tokens_edit_start"))
  {
    $parameters = framework_call ( "tokens_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The token description is required.");
  }
  $parameters["Token"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Token"])));
  if ( empty ( $parameters["Token"]))
  {
    $data["Token"] = __ ( "The token is required.");
  }
  if ( ! array_key_exists ( "Token", $data) && ! preg_match ( "/^[a-z0-9]{16}-[a-z0-9]{8}-[a-z0-9]{8}-[a-z0-9]{8}-[a-z0-9]{24}\$/", $parameters["Token"]))
  {
    $data["Token"] = __ ( "The provided token is invalid.");
  }
  $parameters["Access"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Access"])));
  if ( empty ( $parameters["Access"]))
  {
    $data["Access"] = __ ( "The token access CIDR is required.");
  }
  if ( ! array_key_exists ( "Access", $data) && inet_pton ( substr ( $parameters["Access"], 0, strpos ( $parameters["Access"], "/"))) === false)
  {
    $data["Access"] = __ ( "The access CIDR provided is invalid.");
  }
  if ( ! is_array ( $parameters["Permissions"]))
  {
    if ( ! empty ( $parameters["Permissions"]))
    {
      $parameters["Permissions"] = array ( $parameters["Permissions"]);
    } else {
      $parameters["Permissions"] = array ();
    }
  }
  foreach ( $parameters["Permissions"] as $key => $value)
  {
    if ( ! array_key_exists ( $value, $_api["permissions"]))
    {
      $data["Permissions"] = __ ( "Invalid permission.");
    }
  }
  if ( ! array_key_exists ( "Permissions", $data) && sizeof ( $parameters["Permissions"]) == 0)
  {
    $data["Permissions"] = __ ( "At least one permission is required.");
  }
  if ( empty ( $parameters["Validity"]))
  {
    $data["Validity"] = __ ( "The token validity is required.");
  }
  if ( $parameters["Language"] == "default")
  {
    $parameters["Language"] = "";
  } else {
    if ( ! array_key_exists ( $parameters["Language"], $_in["languages"]))
    {
      $data["Language"] = __ ( "The select language are invalid.");
    }
  }

  /**
   * Check if token was already in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `Token` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Token"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Token"] = __ ( "The provided token is already in use.");
  }

  /**
   * Check if token exist (could be removed by other token meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
  if ( framework_has_hook ( "tokens_edit_validate"))
  {
    $data = framework_call ( "tokens_edit_validate", $parameters, false, $data);
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
  $parameters["Validity"] = format_form_datetime ( empty ( $parameters["Validity"]) ? gmdate ( "d/m/Y 23:59", time ()) : urldecode ( $parameters["Validity"]));

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "tokens_edit_sanitize"))
  {
    $parameters = framework_call ( "tokens_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tokens_edit_pre"))
  {
    $parameters = framework_call ( "tokens_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change token record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Tokens` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Token` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Token"]) . "', `Access` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Access"]) . "', `Permissions` = '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $parameters["Permissions"])) . "', `Expire` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Validity"]) . "', `Language` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tokens_edit_post"))
  {
    framework_call ( "tokens_edit_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tokens_edit_finish"))
  {
    framework_call ( "tokens_edit_finish", $parameters, false);
  }

  /**
   * Return OK to token
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a token
 */
framework_add_hook (
  "tokens_remove",
  "tokens_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system token was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid token ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "tokens_remove", __ ( "Remove tokens"));
framework_add_api_call (
  "/tokens/:ID",
  "Delete",
  "tokens_remove",
  array (
    "permissions" => array ( "administrator", "tokens_remove"),
    "title" => __ ( "Remove tokens"),
    "description" => __ ( "Remove a system token.")
  )
);

/**
 * Function to remove an existing token.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tokens_remove_start"))
  {
    $parameters = framework_call ( "tokens_remove_start", $parameters);
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
  if ( framework_has_hook ( "tokens_remove_validate"))
  {
    $data = framework_call ( "tokens_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "tokens_remove_sanitize"))
  {
    $parameters = framework_call ( "tokens_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if token exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
  if ( framework_has_hook ( "tokens_remove_pre"))
  {
    $parameters = framework_call ( "tokens_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove token database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Tokens` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tokens_remove_post"))
  {
    framework_call ( "tokens_remove_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tokens_remove_finish"))
  {
    framework_call ( "tokens_remove_finish", $parameters, false);
  }

  /**
   * Return OK to token
   */
  return $buffer;
}
?>
