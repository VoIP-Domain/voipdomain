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
 * VoIP Domain users module API. This module add the API calls related to users.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Users
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search users
 */
framework_add_hook (
  "users_search",
  "users_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all users."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Name,Username,Email,Since,SinceTimestamp",
          "example" => "Name,Username,Email"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system users."),
        "schema" => array (
          "type" => "array",
          "items" => array (
            "type" => "object",
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the user."),
                "example" => 1
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the user."),
                "example" => __ ( "John Doe")
              ),
              "Username" => array (
                "type" => "string",
                "description" => __ ( "The username of the user."),
                "example" => __ ( "john.doe")
              ),
              "Email" => array (
                "type" => "string",
                "format" => "email",
                "description" => __ ( "The email of the user."),
                "example" => __ ( "john.doe@voipdomain.io")
              ),
              "Since" => array (
                "type" => "string",
                "description" => __ ( "The date and time of user creation."),
                "example" => "2020-04-21T23:11:57Z"
              ),
              "SinceTimestamp" => array (
                "type" => "integer",
                "description" => __ ( "The UNIX timestamp of user creation."),
                "example" => 1587521517
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
framework_add_permission ( "users_search", __ ( "Search users"));
framework_add_api_call (
  "/users",
  "Read",
  "users_search",
  array (
    "permissions" => array ( "Administrator", "users_search"),
    "title" => __ ( "Search users"),
    "description" => __ ( "Search for system users.")
  )
);

/**
 * Function to search users.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_search ( $buffer, $parameters)
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
  if ( framework_has_hook ( "users_search_start"))
  {
    $parameters = framework_call ( "users_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Users");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Fields", $parameters) || $parameters["Fields"] == "" || sizeof ( $parameters["Fields"]) == 0)
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
  if ( framework_has_hook ( "users_search_validate"))
  {
    $data = framework_call ( "users_search_validate", $parameters);
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
  if ( framework_has_hook ( "users_search_sanitize"))
  {
    $parameters = framework_call ( "users_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "users_search_pre"))
  {
    $parameters = framework_call ( "users_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search users
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Name`, `Username`, `Email`, `Since` FROM `Users` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . ( ! empty ( $parameters["Filter"]) ? " AND (`Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Username` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%')" : "") . " ORDER BY `Name`, `Username`"))
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
    $result["SinceTimestamp"] = format_db_timestamp ( $result["Since"]);
    $result["Since"] = format_db_iso8601 ( $result["Since"]);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "users_search_post"))
  {
    $data = framework_call ( "users_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "users_search_finish"))
  {
    framework_call ( "users_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get user information
 */
framework_add_hook (
  "users_view",
  "users_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system user."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name of the user."),
              "example" => __ ( "John Doe")
            ),
            "Username" => array (
              "type" => "string",
              "description" => __ ( "The username of the user."),
              "example" => __ ( "john.doe")
            ),
            "Email" => array (
              "type" => "string",
              "format" => "email",
              "description" => __ ( "The email of the user."),
              "example" => __ ( "john.doe@voipdomain.io")
            ),
            "Language" => array (
              "type" => "string",
              "description" => __ ( "The language of the user."),
              "example" => "en_US"
            ),
            "SFA" => array (
              "type" => "boolean",
              "description" => __ ( "If the user has second factor authentication enabled."),
              "example" => true
            ),
            "Permissions" => array (
              "type" => "array",
              "description" => __ ( "An array with all the system permissions for the user."),
              "items" => array (
                "type" => "string",
                "description" => __ ( "The system permission token."),
                "example" => array (
                  "Administrator"
                )
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
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid user ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "users_view", __ ( "View users information"));
framework_add_api_call (
  "/users/:ID",
  "Read",
  "users_view",
  array (
    "permissions" => array ( "Administrator", "users_view"),
    "title" => __ ( "View users"),
    "description" => __ ( "Get a system user information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The internal unique identification number of the user."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate user information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "users_view_start"))
  {
    $parameters = framework_call ( "users_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Users");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid user ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "users_view_validate"))
  {
    $data = framework_call ( "users_view_validate", $parameters);
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
  if ( framework_has_hook ( "users_view_sanitize"))
  {
    $parameters = framework_call ( "users_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "users_view_pre"))
  {
    $parameters = framework_call ( "users_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search users
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Users`.*, `UserSFA`.`Key` FROM `Users` LEFT JOIN `UserSFA` ON `Users`.`ID` = `UserSFA`.`UID` AND `UserSFA`.`Status` = 'Active' WHERE `Users`.`Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Users`.`ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $user = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Format data
   */
  $user["Permissions"] = json_decode ( $user["Permissions"], true);
  $user["Language"] = ( $user["Language"] != "" ? $user["Language"] : "default");
  $user["SFA"] = $user["Key"] != "";
  $data = api_filter_entry ( array ( "Name", "Username", "Email", "Language", "SFA", "Permissions"), $user);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "users_view_post"))
  {
    $data = framework_call ( "users_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "users_view_finish"))
  {
    framework_call ( "users_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new user
 */
framework_add_hook (
  "users_add",
  "users_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the user."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Username" => array (
          "type" => "string",
          "description" => __ ( "The username of the user."),
          "required" => true,
          "example" => __ ( "johndoe")
        ),
        "Email" => array (
          "type" => "email",
          "description" => __ ( "The email of the user."),
          "required" => true,
          "example" => __ ( "johndoe@voipdomain.io")
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "The password of the user."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Confirmation" => array (
          "type" => "password",
          "description" => __ ( "The confirmation of the user password."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The language of the user."),
          "example" => __ ( "en_US")
        ),
        "Permissions" => array (
          "type" => "array",
          "description" => __ ( "An array with all the system permissions for the user."),
          "items" => array (
            "type" => "string",
            "description" => __ ( "The system permission token."),
            "example" => array (
              "Administrator"
            )
          )
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system user added successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The user name is required.")
            ),
            "Username" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The user login name is already in use.")
            ),
            "Email" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The informed e-mail is invalid.")
            ),
            "Password" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The user password is required.")
            ),
            "Confirmation" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The passwords didn't match.", true, false)
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
framework_add_permission ( "users_add", __ ( "Add users"));
framework_add_api_call (
  "/users",
  "Create",
  "users_add",
  array (
    "permissions" => array ( "Administrator", "users_add"),
    "title" => __ ( "Add users"),
    "description" => __ ( "Add a new system user.")
  )
);

/**
 * Function to add a new user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "users_add_start"))
  {
    $parameters = framework_call ( "users_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The user name is required.");
  }
  $parameters["Username"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Username"])));
  if ( empty ( $parameters["Username"]))
  {
    $data["Username"] = __ ( "The user login name is required.");
  }
  $parameters["Email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Email"])));
  if ( empty ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The user e-mail is required.");
  }
  if ( ! empty ( $parameters["Email"]) && ! validate_email ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The informed e-mail is invalid.");
  }
  if ( empty ( $parameters["Password"]))
  {
    $data["Password"] = __ ( "The user password is required.");
  }
  if ( empty ( $parameters["Confirmation"]))
  {
    $data["Confirmation"] = __ ( "The user confirmation password is required.");
  }
  if ( ! empty ( $parameters["Password"]) && ! empty ( $parameters["Confirmation"]) && $parameters["Password"] != $parameters["Confirmation"])
  {
    $data["Confirmation"] = __ ( "The passwords didn't match.");
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
   * Check if user was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Username"] = __ ( "The user login name is already in use.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "users_add_validate"))
  {
    $data = framework_call ( "users_add_validate", $parameters, false, $data);
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
  if ( in_array ( "Administrator", $permissions))
  {
    $parameters["Permissions"][] = "Administrator";
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "users_add_sanitize"))
  {
    $parameters = framework_call ( "users_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "users_add_pre"))
  {
    $parameters = framework_call ( "users_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new user record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Users` (`Tenant`, `Name`, `Username`, `Password`, `Permissions`, `Email`, `Since`, `Salt`, `Iterations`, `Language`) VALUES (" . (int) $_in["session"]["Tenant"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "', '" . hash_pbkdf2 ( "sha256", $parameters["Password"], $parameters["Salt"], ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000), 64) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Permissions"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Email"]) . "', NOW(), '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Salt"]) . "', " . (int) ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "users_add_post"))
  {
    framework_call ( "users_add_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "users_add_finish"))
  {
    framework_call ( "users_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/users/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing user
 */
framework_add_hook (
  "users_edit",
  "users_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the user."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Username" => array (
          "type" => "string",
          "description" => __ ( "The username of the user."),
          "required" => true,
          "example" => __ ( "johndoe")
        ),
        "Email" => array (
          "type" => "email",
          "description" => __ ( "The email of the user."),
          "required" => true,
          "example" => __ ( "johndoe@voipdomain.io")
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "The password of the user."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Confirmation" => array (
          "type" => "password",
          "description" => __ ( "The confirmation of the user password."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The language of the user."),
          "example" => __ ( "en_US")
        ),
        "Permissions" => array (
          "type" => "array",
          "description" => __ ( "An array with all the system permissions for the user."),
          "items" => array (
            "type" => "string",
            "description" => __ ( "The system permission token."),
            "example" => array (
              "Administrator"
            )
          )
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system user was successfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The user name is required.")
            ),
            "Username" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The user login name is already in use.")
            ),
            "Email" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The informed e-mail is invalid.")
            ),
            "Password" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The user password is required.")
            ),
            "Confirmation" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The passwords didn't match.", true, false)
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
framework_add_permission ( "users_edit", __ ( "Edit users"));
framework_add_api_call (
  "/users/:ID",
  array ( "Modify", "Edit"),
  "users_edit",
  array (
    "permissions" => array ( "Administrator", "users_edit"),
    "title" => __ ( "Edit users"),
    "description" => __ ( "Change a system user information.")
  )
);

/**
 * Function to edit an existing user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "users_edit_start"))
  {
    $parameters = framework_call ( "users_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The user name is required.");
  }
  $parameters["Username"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Username"])));
  if ( empty ( $parameters["Username"]))
  {
    $data["Username"] = __ ( "The user login name is required.");
  }
  $parameters["Email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Email"])));
  if ( empty ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The user e-mail is required.");
  }
  if ( ! empty ( $parameters["Email"]) && ! validate_email ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The informed e-mail is invalid.");
  }
  if ( ! empty ( $parameters["Password"]) && empty ( $parameters["Confirmation"]))
  {
    $data["Confirmation"] = __ ( "The user confirmation password is required.");
  }
  if ( ! empty ( $parameters["Password"]) && ! empty ( $parameters["Confirmation"]) && $parameters["Password"] != $parameters["Confirmation"])
  {
    $data["Confirmation"] = __ ( "The passwords didn't match.");
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
   * Check if user was already in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "' AND `ID` != " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Username"] = __ ( "The user login name is already in use.");
  }

  /**
   * Check if user exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
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
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "users_edit_validate"))
  {
    $data = framework_call ( "users_edit_validate", $parameters, false, $data);
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
  if ( in_array ( "Administrator", $permissions))
  {
    $parameters["Permissions"][] = "Administrator";
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "users_edit_sanitize"))
  {
    $parameters = framework_call ( "users_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "users_edit_pre"))
  {
    $parameters = framework_call ( "users_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change user record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Users` SET `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "', `Permissions` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Permissions"])) . "', `Email` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Email"]) . "'" . ( ! empty ( $parameters["Password"]) ? ", `Password` = '" . hash_pbkdf2 ( "sha256", $parameters["Password"], $parameters["Salt"], ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000), 64) . "', `Salt` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Salt"]) . "', `Iterations` = " . (int) ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000) : "") . ", `Language` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "' WHERE `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "users_edit_post"))
  {
    framework_call ( "users_edit_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "users_edit_finish"))
  {
    framework_call ( "users_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove an user
 */
framework_add_hook (
  "users_remove",
  "users_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system user was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid user ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "users_remove", __ ( "Remove users"));
framework_add_api_call (
  "/users/:ID",
  "Delete",
  "users_remove",
  array (
    "permissions" => array ( "Administrator", "users_remove"),
    "title" => __ ( "Remove users"),
    "description" => __ ( "Remove a user from system.")
  )
);

/**
 * Function to remove an existing user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "users_remove_start"))
  {
    $parameters = framework_call ( "users_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid user ID.");
  }

  /**
   * Check if are removing itself
   */
  if ( $in["session"]["Method"] == "Session" && $_in["session"]["Data"]["ID"] == $parameters["ID"])
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "users_remove_validate"))
  {
    $data = framework_call ( "users_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "users_remove_sanitize"))
  {
    $parameters = framework_call ( "users_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if user exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Users` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
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
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "users_remove_pre"))
  {
    $parameters = framework_call ( "users_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove user database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Users` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "users_remove_post"))
  {
    framework_call ( "users_remove_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "users_remove_finish"))
  {
    framework_call ( "users_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to setup second factor authentication to the current user
 */
framework_add_hook (
  "users_add_sfa",
  "users_add_sfa",
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
              "description" => __ ( "The user second factor authentication key."),
              "example" => "HF5GC6SZMFKDER22NIZUU2TRKVUDQ5CR"
            ),
            "URI" => array (
              "type" => "string",
              "description" => __ ( "The user second factor authentication URI."),
              "example" => "otpauth://totp/VoIP%20Domain:admin@voipdomain.io?secret=HF5GC6SZMFKDER22NIZUU2TRKVUDQ5CR&issuer=VoIP%20Domain"
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
              "example" => __ ( "The user code is invalid.")
            )
          )
        )
      ),
      423 => array (
        "description" => __ ( "User has already a second factor authentication activated.")
      )
    )
  )
);
framework_add_permission ( "users_add_sfa", __ ( "Add user second factor authentication"));
framework_add_api_call (
  "/users/sfa",
  "Create",
  "users_add_sfa",
  array (
    "permissions" => array ( "User"),
    "title" => __ ( "Add user second factor authentication"),
    "description" => __ ( "Add user second factor authentication.")
  )
);

/**
 * Function to add a second factor authentication (RFC6238/TOTP) to the current
 * user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_add_sfa ( $buffer, $parameters)
{
  global $_in;

  /**
   * If not authenticated using Session method, just fail
   */
  if ( $_in["session"]["Method"] != "Session")
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * If no code sent, check to create a key to the user
   */
  if ( ! array_key_exists ( "Code", $parameters))
  {
    /**
     * Check if user has a SFA
     */
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `UserSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Status` = 'Active'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 423 Locked");
      return array ();
    }

    /**
     * Remove any older SFA not active to the user
     */
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `UserSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Status` != 'Active'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }

    /**
     * Create new SFA to the user
     */
    $key = base32_encode ( random_password ( 20));

    /**
     * Add new SFA with pending status
     */
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `UserSFA` (`UID`, `Key`, `Status`) VALUES (" . (int) $_in["session"]["Data"]["ID"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $key) . "', 'Pending')"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }

    /**
     * Return Key and URI to the user
     */
    return array ( "Key" => $key, "URI" => rfc6238_uri ( substr ( $_in["session"]["Data"]["Email"], 0, strpos ( $_in["session"]["Data"]["Email"], "@")), substr ( $_in["session"]["Data"]["Email"], strpos ( $_in["session"]["Data"]["Email"], "@") + 1), $key, "VoIP Domain"));
  }

  /**
   * The code was sent, validate it
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `UserSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Status` = 'Pending'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
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
      if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `UserSFA` SET `Status` = 'Active' WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Key` = '" . $_in["mysql"]["id"]->real_escape_string ( $data["Key"]) . "'"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }

      /**
       * Key successfully authenticated
       */
      header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
      header ( "Location: " . $_in["api"]["baseurl"] . "/users/" . $_in["session"]["Data"]["ID"]);
      return $buffer;
    }
  }

  /**
   * If reached here, code is invalid
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
  return array ( "Code" => __ ( "The user code is invalid."));
}

/**
 * API call to remove an user SFA
 */
framework_add_hook (
  "users_remove_sfa",
  "users_remove_sfa",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The user second factor authentication was removed.")
      ),
      422 => array (
        "description" => __ ( "The user does not have an active second factor authentication.")
      )
    )
  )
);
framework_add_permission ( "users_remove_sfa", __ ( "Remove user second factor authentication"));
framework_add_api_call (
  "/users/sfa",
  "Delete",
  "users_remove_sfa",
  array (
    "permissions" => array ( "User"),
    "title" => __ ( "Remove user second factor authentication"),
    "description" => __ ( "Remove user second factor authentication.")
  )
);

/**
 * Function to remove user second factor authentication.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_remove_sfa ( $buffer, $parameters)
{
  global $_in;

  /**
   * If not authenticated using Session method, just fail
   */
  if ( $_in["session"]["Method"] != "Session")
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Check if user has a SFA
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `UserSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Status` = 'Active'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return array ();
  }

  /**
   * Remove any SFA cache to the user
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `UserSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove active SFA to the user
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `UserSFA` WHERE `UID` = " . (int) $_in["session"]["Data"]["ID"] . " AND `Status` = 'Active'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Return OK to user
   */
  return $buffer;
}
?>
