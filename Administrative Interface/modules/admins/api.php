<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain administrators module API. This module add the api calls related
 * to master portal administrators.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Admins
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search administrators
 */
framework_add_hook (
  "admins_search",
  "admins_search",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system administrators."),
        "schema" => array (
          "type" => "array",
          "items" => array (
            "type" => "object",
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the administrator."),
                "example" => 1
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the administrator."),
                "example" => __ ( "John Doe")
              ),
              "Username" => array (
                "type" => "string",
                "description" => __ ( "The username of the administrator."),
                "example" => __ ( "john.doe")
              ),
              "Email" => array (
                "type" => "string",
                "format" => "email",
                "description" => __ ( "The e-mail of the administrator."),
                "example" => __ ( "john.doe@voipdomain.io")
              ),
              "Since" => array (
                "type" => "string",
                "format" => "date-time",
                "description" => __ ( "The date and time of administrator creation."),
                "example" => "2024-01-15T10:30:00-03:00"
              ),
              "SinceTimestamp" => array (
                "type" => "integer",
                "description" => __ ( "The UNIX timestamp of administrator creation."),
                "example" => 1705320600
              )
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "admins_search", __ ( "Search administrators"));
framework_add_api_call (
  "/admins",
  "Read",
  "admins_search",
  array (
    "permissions" => array ( "Super-Administrator", "admins_search"),
    "title" => __ ( "Search administrators"),
    "description" => __ ( "Search for system administrators."),
    "parameters" => array (
      array (
        "name" => "Filter",
        "type" => "string",
        "description" => __ ( "Filter search with this string. If not provided, return all administrators."),
        "example" => "admin"
      ),
      array (
        "name" => "Fields",
        "type" => "string",
        "description" => __ ( "A comma-separated list of fields to return."),
        "example" => "ID,Name,Username"
      )
    )
  )
);

/**
 * Function to search administrators.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add function parameters
   */
  $parameters["function"] = array (
    "DefaultFields" => "ID,Name,Username,Email,Since,SinceTimestamp",
    "PermittedFields" => "ID,Name,Username,Email,Since,SinceTimestamp"
  );

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "admins_search_start"))
  {
    $parameters = framework_call ( "admins_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Admins");

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
    $data["Fields"] = __ ( "Fields contain invalid values.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "admins_search_validate"))
  {
    $data = framework_call ( "admins_search_validate", $parameters);
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
  if ( framework_has_hook ( "admins_search_sanitize"))
  {
    $parameters = framework_call ( "admins_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "admins_search_pre"))
  {
    $parameters = framework_call ( "admins_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search administrators
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Name`, `Username`, `Email`, `Since` FROM `Admins`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Username` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Name`, `Username`"))
  {
    database_query_error ( "admins_search");
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], $parameters["function"]["DefaultFields"], $parameters["function"]["PermittedFields"]);
  while ( $result = $results->fetch_assoc ())
  {
    $result["SinceTimestamp"] = format_db_timestamp ( $result["Since"]);
    $result["Since"] = format_db_iso8601 ( $result["Since"]);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "admins_search_post"))
  {
    $data = framework_call ( "admins_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "admins_search_finish"))
  {
    framework_call ( "admins_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get administrator information
 */
framework_add_hook (
  "admins_view",
  "admins_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system administrator."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name of the administrator."),
              "example" => __ ( "John Doe")
            ),
            "Username" => array (
              "type" => "string",
              "description" => __ ( "The username of the administrator."),
              "example" => __ ( "john.doe")
            ),
            "Email" => array (
              "type" => "string",
              "format" => "email",
              "description" => __ ( "The e-mail of the administrator."),
              "example" => __ ( "john.doe@voipdomain.io")
            ),
            "Language" => array (
              "type" => "string",
              "description" => __ ( "The language of the administrator."),
              "example" => "en_US"
            ),
            "SFA" => array (
              "type" => "boolean",
              "description" => __ ( "If the administrator has second factor authentication enabled."),
              "example" => true
            ),
            "Permissions" => array (
              "type" => "array",
              "description" => __ ( "An array with all the system permissions for the administrator."),
              "items" => array (
                "type" => "string",
                "description" => __ ( "The system permission token.")
              )
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid administrator ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "admins_view", __ ( "View administrator information"));
framework_add_api_call (
  "/admins/:ID",
  "Read",
  "admins_view",
  array (
    "permissions" => array ( "Super-Administrator", "admins_view"),
    "title" => __ ( "View administrators"),
    "description" => __ ( "Get system administrator information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The internal unique identification number of the administrator."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate administrator information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "admins_view_start"))
  {
    $parameters = framework_call ( "admins_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Admins");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid administrator ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "admins_view_validate"))
  {
    $data = framework_call ( "admins_view_validate", $parameters);
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
  if ( framework_has_hook ( "admins_view_sanitize"))
  {
    $parameters = framework_call ( "admins_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "admins_view_pre"))
  {
    $parameters = framework_call ( "admins_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search administrators
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Admins`.*, `AdminSFA`.`Key` FROM `Admins` LEFT JOIN `AdminSFA` ON `Admins`.`ID` = `AdminSFA`.`UID` AND `AdminSFA`.`Status` = 'Active' WHERE `Admins`.`ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "admins_view");
  }
  if ( ! $admin = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Format data
   */
  $admin["Permissions"] = json_decode ( $admin["Permissions"], true);
  $admin["Language"] = ( $admin["Language"] != "" ? $admin["Language"] : "default");
  $admin["SFA"] = $admin["Key"] != "";
  $data = api_filter_entry ( array ( "Name", "Username", "Email", "Language", "SFA", "Permissions"), $admin);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "admins_view_post"))
  {
    $data = framework_call ( "admins_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "admins_view_finish"))
  {
    framework_call ( "admins_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new administrator
 */
framework_add_hook (
  "admins_add",
  "admins_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the administrator."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Username" => array (
          "type" => "string",
          "description" => __ ( "The username of the administrator."),
          "required" => true,
          "example" => __ ( "johndoe")
        ),
        "Email" => array (
          "type" => "email",
          "description" => __ ( "The e-mail of the administrator."),
          "required" => true,
          "example" => __ ( "johndoe@voipdomain.io")
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "The password of the administrator."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Confirmation" => array (
          "type" => "password",
          "description" => __ ( "The confirmation of the administrator's password."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The language of the administrator."),
          "example" => __ ( "en_US")
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "A new system administrator was added successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The administrator name is required.")
            ),
            "Username" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The administrator login name is already in use.")
            ),
            "Email" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided e-mail is invalid.")
            ),
            "Password" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The administrator password is required.")
            ),
            "Confirmation" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The passwords do not match.", true, false)
            ),
            "Language" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected language is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "admins_add", __ ( "Add administrators"));
framework_add_api_call (
  "/admins",
  "Create",
  "admins_add",
  array (
    "permissions" => array ( "Super-Administrator", "admins_add"),
    "title" => __ ( "Add administrators"),
    "description" => __ ( "Add a new system administrator.")
  )
);

/**
 * Function to add a new administrator.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "admins_add_start"))
  {
    $parameters = framework_call ( "admins_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The administrator name is required.");
  }
  $parameters["Username"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Username"])));
  if ( empty ( $parameters["Username"]))
  {
    $data["Username"] = __ ( "The administrator login name is required.");
  }
  $parameters["Email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Email"])));
  if ( empty ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The administrator e-mail is required.");
  }
  if ( ! empty ( $parameters["Email"]) && ! validate_email ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The provided e-mail is invalid.");
  }
  if ( empty ( $parameters["Password"]))
  {
    $data["Password"] = __ ( "The administrator password is required.");
  }
  if ( empty ( $parameters["Confirmation"]))
  {
    $data["Confirmation"] = __ ( "The administrator confirmation password is required.");
  }
  if ( ! empty ( $parameters["Password"]) && ! empty ( $parameters["Confirmation"]) && $parameters["Password"] != $parameters["Confirmation"])
  {
    $data["Confirmation"] = __ ( "The passwords do not match.");
  }
  if ( $parameters["Language"] == "default")
  {
    $parameters["Language"] = "";
  } else {
    if ( ! array_key_exists ( $parameters["Language"], $_in["languages"]))
    {
      $data["Language"] = __ ( "The selected language is invalid.");
    }
  }

  /**
   * Check if administrator was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Admins` WHERE `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "'"))
  {
    database_query_error ( "admins_add");
  }
  if ( $result->num_rows != 0)
  {
    $data["Username"] = __ ( "The administrator login name is already in use.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "admins_add_validate"))
  {
    $data = framework_call ( "admins_add_validate", $parameters, false, $data);
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
  $parameters["Salt"] = secure_rand ( 32);
  $permissions = array_key_exists ( "Permissions", $parameters) ? $parameters["Permissions"] : array ();
  $parameters["Permissions"] = array ();
  foreach ( $_in["reservedpermissions"] as $rp)
  {
    if ( in_array ( $rp, $permissions) && ! in_array ( $rp, $parameters["Permissions"]))
    {
      $parameters["Permissions"][] = $rp;
    }
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "admins_add_sanitize"))
  {
    $parameters = framework_call ( "admins_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "admins_add_pre"))
  {
    $parameters = framework_call ( "admins_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new administrator record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Admins` (`Name`, `Username`, `Password`, `Permissions`, `Email`, `Since`, `Salt`, `Iterations`, `Language`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "', '" . hash_pbkdf2 ( "sha256", $parameters["Password"], $parameters["Salt"], ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000), 64) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Permissions"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Email"]) . "', NOW(), '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Salt"]) . "', " . (int) ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "')"))
  {
    database_query_error ( "admins_add");
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "admins_add_post"))
  {
    framework_call ( "admins_add_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "admins_add_finish"))
  {
    framework_call ( "admins_add_finish", $parameters, false);
  }

  /**
   * Return OK to administrator
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . voipdomain_location_url ( "/api/admins/" . $parameters["ID"]));
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing administrator
 */
framework_add_hook (
  "admins_edit",
  "admins_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the administrator."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Username" => array (
          "type" => "string",
          "description" => __ ( "The username of the administrator."),
          "required" => true,
          "example" => __ ( "johndoe")
        ),
        "Email" => array (
          "type" => "email",
          "description" => __ ( "The e-mail of the administrator."),
          "required" => true,
          "example" => __ ( "johndoe@voipdomain.io")
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "The password of the administrator."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Confirmation" => array (
          "type" => "password",
          "description" => __ ( "The confirmation of the administrator's password."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The language of the administrator."),
          "example" => __ ( "en_US")
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system administrator was successfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The administrator name is required.")
            ),
            "Username" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The administrator login name is already in use.")
            ),
            "Email" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided e-mail is invalid.")
            ),
            "Password" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The administrator password is required.")
            ),
            "Confirmation" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The passwords do not match.", true, false)
            ),
            "Language" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected language is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "admins_edit", __ ( "Edit administrator"));
framework_add_api_call (
  "/admins/:ID",
  array ( "Modify", "Edit"),
  "admins_edit",
  array (
    "permissions" => array ( "Super-Administrator", "admins_edit"),
    "title" => __ ( "Edit administrator"),
    "description" => __ ( "Change system administrator information.")
  )
);

/**
 * Function to edit an existing administrator.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "admins_edit_start"))
  {
    $parameters = framework_call ( "admins_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The administrator name is required.");
  }
  $parameters["Username"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Username"])));
  if ( empty ( $parameters["Username"]))
  {
    $data["Username"] = __ ( "The administrator login name is required.");
  }
  $parameters["Email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Email"])));
  if ( empty ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The administrator e-mail is required.");
  }
  if ( ! empty ( $parameters["Email"]) && ! validate_email ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The provided e-mail is invalid.");
  }
  if ( ! empty ( $parameters["Password"]) && empty ( $parameters["Confirmation"]))
  {
    $data["Confirmation"] = __ ( "The administrator confirmation password is required.");
  }
  if ( ! empty ( $parameters["Password"]) && ! empty ( $parameters["Confirmation"]) && $parameters["Password"] != $parameters["Confirmation"])
  {
    $data["Confirmation"] = __ ( "The passwords do not match.");
  }
  if ( $parameters["Language"] == "default")
  {
    $parameters["Language"] = "";
  } else {
    if ( ! array_key_exists ( $parameters["Language"], $_in["languages"]))
    {
      $data["Language"] = __ ( "The selected language is invalid.");
    }
  }

  /**
   * Check if administrator username is already in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Admins` WHERE `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "' AND `ID` != " . (int) $parameters["ID"]))
  {
    database_query_error ( "admins_edit");
  }
  if ( $result->num_rows != 0)
  {
    $data["Username"] = __ ( "The administrator login name is already in use.");
  }

  /**
   * Check if administrator exist (could be removed by other administrator meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Admins` WHERE `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "admins_edit");
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "admins_edit_validate"))
  {
    $data = framework_call ( "admins_edit_validate", $parameters, false, $data);
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
  $parameters["Salt"] = secure_rand ( 32);
  $permissions = array_key_exists ( "Permissions", $parameters) ? $parameters["Permissions"] : array ();
  $parameters["Permissions"] = array ();
  foreach ( $_in["reservedpermissions"] as $rp)
  {
    if ( in_array ( $rp, $permissions) && ! in_array ( $rp, $parameters["Permissions"]))
    {
      $parameters["Permissions"][] = $rp;
    }
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "admins_edit_sanitize"))
  {
    $parameters = framework_call ( "admins_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "admins_edit_pre"))
  {
    $parameters = framework_call ( "admins_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change administrator record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Admins` SET `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "', `Permissions` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Permissions"])) . "', `Email` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Email"]) . "'" . ( ! empty ( $parameters["Password"]) ? ", `Password` = '" . hash_pbkdf2 ( "sha256", $parameters["Password"], $parameters["Salt"], ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000), 64) . "', `Salt` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Salt"]) . "', `Iterations` = " . (int) ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000) : "") . ", `Language` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "' WHERE `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "admins_edit");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "admins_edit_post"))
  {
    framework_call ( "admins_edit_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "admins_edit_finish"))
  {
    framework_call ( "admins_edit_finish", $parameters, false);
  }

  /**
   * Return OK to administrator
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove an administrator
 */
framework_add_hook (
  "admins_remove",
  "admins_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system administrator was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid administrator ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "admins_remove", __ ( "Remove administrators"));
framework_add_api_call (
  "/admins/:ID",
  "Delete",
  "admins_remove",
  array (
    "permissions" => array ( "Super-Administrator", "admins_remove"),
    "title" => __ ( "Remove administrators"),
    "description" => __ ( "Remove an administrator from the system.")
  )
);

/**
 * Function to remove an existing administrator.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "admins_remove_start"))
  {
    $parameters = framework_call ( "admins_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid administrator ID.");
  }

  /**
   * Check if are removing itself
   */
  if ( $_in["session"]["Method"] == "Session" && $_in["session"]["Data"]["ID"] == $parameters["ID"])
  {
    api_error_response ( 403, "self_removal", "Cannot remove own account");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "admins_remove_validate"))
  {
    $data = framework_call ( "admins_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "admins_remove_sanitize"))
  {
    $parameters = framework_call ( "admins_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if administrator exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Admins` WHERE `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "admins_remove");
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "admins_remove_pre"))
  {
    $parameters = framework_call ( "admins_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove administrator database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Admins` WHERE `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "admins_remove");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "admins_remove_post"))
  {
    framework_call ( "admins_remove_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "admins_remove_finish"))
  {
    framework_call ( "admins_remove_finish", $parameters, false);
  }

  /**
   * Return OK to administrator
   */
  return $buffer;
}

/**
 * API call to setup second factor authentication to the current administrator
 */
framework_add_hook (
  "admins_add_sfa",
  "admins_add_sfa",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => false,
      "properties" => array (
        "Code" => array (
          "type" => "integer",
          "description" => __ ( "The authentication code to be validated."),
          "required" => false,
          "example" => "238120"
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "Second factor authentication successfully validated and activated.")
      ),
      200 => array (
        "description" => __ ( "Second factor authentication preflight data generated."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Key" => array (
              "type" => "string",
              "description" => __ ( "The administrator second factor authentication key."),
              "example" => "HF5GC6SZMFKDER22NIZUU2TRKVUDQ5CR"
            ),
            "URI" => array (
              "type" => "string",
              "description" => __ ( "The administrator second factor authentication URI."),
              "example" => "otpauth://totp/VoIP%20Domain:admin@voipdomain.io?secret=HF5GC6SZMFKDER22NIZUU2TRKVUDQ5CR&issuer=VoIP%20Domain"
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The administrator code is invalid.")
            )
          )
        )
      ),
      423 => array (
        "description" => __ ( "Administrator already has second factor authentication activated.")
      )
    )
  )
);
framework_add_permission ( "admins_add_sfa", __ ( "Add administrator second factor authentication"));
framework_add_api_call (
  "/admins/sfa",
  "Create",
  "admins_add_sfa",
  array (
    "permissions" => array ( "Super-Administrator"),
    "title" => __ ( "Add administrator second factor authentication"),
    "description" => __ ( "Add administrator second factor authentication.")
  )
);

/**
 * Function to add a second factor authentication (RFC6238/TOTP) to the current
 * administrator.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_add_sfa ( $buffer, $parameters)
{
  global $_in;

  /**
   * If not authenticated using Session method, just fail
   */
  if ( $_in["session"]["Method"] != "Session")
  {
    api_error_response ( 403, "session_required", "Session authentication required");
  }

  /**
   * If no code sent, check to create a key to the administrator
   */
  if ( ! array_key_exists ( "Code", $parameters))
  {
    /**
     * Check if administrator has a SFA
     */
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `AdminSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Status` = 'Active'"))
    {
      database_query_error ( "admins_add_sfa");
    }
    if ( $result->num_rows != 0)
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 423 Locked");
      return array ();
    }

    /**
     * Remove any older SFA not active to the administrator
     */
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `AdminSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Status` != 'Active'"))
    {
      database_query_error ( "admins_add_sfa");
    }

    /**
     * Create new SFA to the administrator
     */
    $key = base32_encode ( random_password ( 20));

    /**
     * Add new SFA with pending status
     */
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `AdminSFA` (`UID`, `Key`, `Status`) VALUES (" . (int) $_in["session"]["Data"]["ID"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $key) . "', 'Pending')"))
    {
      database_query_error ( "admins_add_sfa");
    }

    /**
     * Return Key and URI to the administrator
     */
    return array ( "Key" => $key, "URI" => rfc6238_uri ( substr ( $_in["session"]["Data"]["Email"], 0, strpos ( $_in["session"]["Data"]["Email"], "@")), substr ( $_in["session"]["Data"]["Email"], strpos ( $_in["session"]["Data"]["Email"], "@") + 1), $key, "VoIP Domain"));
  }

  /**
   * The code was sent, validate it
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `AdminSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Status` = 'Pending'"))
  {
    database_query_error ( "admins_add_sfa");
  }
  if ( $data = $result->fetch_assoc ())
  {
    /**
     * Validate the code
     */
    if ( rfc6238_validate ( $data["Key"], $parameters["Code"], $_in["security"]["totprange"]))
    {
      /**
       * Promote key from Pending to Active
       */
      if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `AdminSFA` SET `Status` = 'Active' WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Key` = '" . $_in["mysql"]["id"]->real_escape_string ( $data["Key"]) . "'"))
      {
        database_query_error ( "admins_add_sfa");
      }

      /**
       * Key successfully authenticated
       */
      header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
      header ( "Location: " . voipdomain_location_url ( "/api/admins/" . $_in["session"]["Data"]["ID"]));
      return $buffer;
    }
  }

  /**
   * If reached here, code is invalid
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
  return array ( "Code" => __ ( "The administrator code is invalid."));
}

/**
 * API call to remove an administrator SFA
 */
framework_add_hook (
  "admins_remove_sfa",
  "admins_remove_sfa",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The administrator second factor authentication was removed.")
      ),
      422 => array (
        "description" => __ ( "The administrator does not have an active second factor authentication.")
      )
    )
  )
);
framework_add_permission ( "admins_remove_sfa", __ ( "Remove administrator second factor authentication"));
framework_add_api_call (
  "/admins/sfa",
  "Delete",
  "admins_remove_sfa",
  array (
    "permissions" => array ( "Super-Administrator"),
    "title" => __ ( "Remove administrator second factor authentication"),
    "description" => __ ( "Remove administrator second factor authentication.")
  )
);

/**
 * Function to remove administrator second factor authentication.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_remove_sfa ( $buffer, $parameters)
{
  global $_in;

  /**
   * If not authenticated using Session method, just fail
   */
  if ( $_in["session"]["Method"] != "Session")
  {
    api_error_response ( 403, "session_required", "Session authentication required");
  }

  /**
   * Check if administrator has a SFA
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `AdminSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Status` = 'Active'"))
  {
    database_query_error ( "admins_remove_sfa");
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return array ();
  }

  /**
   * Remove any SFA cache to the administrator
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `AdminSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"]))
  {
    database_query_error ( "admins_remove_sfa");
  }

  /**
   * Return OK to administrator
   */
  return $buffer;
}
?>
