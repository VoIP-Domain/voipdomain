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
 * VoIP Domain authentication module API. This module add the API calls related
 * to system authentication.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Authentication
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to get system authentication information
 */
framework_add_hook (
  "authentication_view",
  "authentication_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing system authentication information."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Status" => array (
              "type" => "boolean",
              "description" => __ ( "If the authentication endpoint is enabled."),
              "example" => true
            ),
            "Background" => array (
              "type" => "integer",
              "description" => __ ( "The authentication page background image."),
              "example" => 1
            ),
            "Password" => array (
              "type" => "boolean",
              "description" => __ ( "If the extension password will be accepted to authenticate user."),
              "example" => true
            ),
            "Plugins" => array (
              "type" => "array",
              "xml" => array (
                "name" => "Plugins",
                "wrapped" => true
              ),
              "description" => __ ( "A list of authentication plugins with their specific configuration."),
              "items" => array (
                "anyOf" => array ()
              )
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "authentication_view", __ ( "View system authentication information"));
framework_add_api_call (
  "/config/authentication",
  "Read",
  "authentication_view",
  array (
    "permissions" => array ( "Administrator", "authentication_view"),
    "title" => __ ( "View authentication"),
    "description" => __ ( "View system authentication information.")
  )
);

/**
 * Function to generate system authentication information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "authentication_view_start"))
  {
    $parameters = framework_call ( "authentication_view_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "authentication_view_validate"))
  {
    $data = framework_call ( "authentication_view_validate", $parameters);
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
  if ( framework_has_hook ( "authentication_view_sanitize"))
  {
    $parameters = framework_call ( "authentication_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "authentication_view_pre"))
  {
    $parameters = framework_call ( "authentication_view_pre", $parameters, false, $parameters);
  }

  /**
   * Get authentication from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Key` = 'Authentication'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $data = array ( "Status" => false, "Background" => 1, "Password" => true, "Plugins" => array ());
  if ( $tmp = $result->fetch_assoc ())
  {
    if ( is_json ( $tmp["Data"]))
    {
      $tmp = json_decode ( $tmp["Data"], true);
      $data["Status"] = $tmp["Status"];
      $data["Background"] = $tmp["Background"];
      $data["Password"] = $tmp["Password"];
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "authentication_view_post"))
  {
    $data = framework_call ( "authentication_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "authentication_view_finish"))
  {
    framework_call ( "authentication_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to change system authentication
 */
framework_add_hook (
  "authentication_edit",
  "authentication_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Status" => array (
          "type" => "boolean",
          "description" => __ ( "If the authentication endpoint is enabled."),
          "required" => true,
          "example" => true
        ),
        "Background" => array (
          "type" => "integer",
          "description" => __ ( "The authentication page background image."),
          "required" => true,
          "example" => 1
        ),
        "Password" => array (
          "type" => "boolean",
          "description" => __ ( "If the extension password will be accepted to authenticate user."),
          "required" => true,
          "example" => true
        ),
        "Plugins" => array (
          "type" => "array",
          "xml" => array (
            "name" => "Plugins",
            "wrapped" => true
          ),
          "description" => __ ( "A list of authentication plugins with their specific configuration."),
          "required" => false,
          "items" => array (
            "anyOf" => array ()
          )
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "System authentication updated successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Status" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided status is not valid.")
            ),
            "Background" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided background is not valid.")
            ),
            "Password" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided password enabled field is not valid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "authentication_edit", __ ( "Change authentication"));
framework_add_api_call (
  "/config/authentication",
  array ( "Modify", "Edit"),
  "authentication_edit",
  array (
    "permissions" => array ( "Administrator", "authentication_edit"),
    "title" => __ ( "Change authentication"),
    "description" => __ ( "Change system authentication.")
  )
);

/**
 * Function to change system authentication.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "authentication_edit_start"))
  {
    $parameters = framework_call ( "authentication_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Status", $parameters))
  {
    $data["Status"] = __ ( "The provided status is not valid.");
  }
  if ( ! array_key_exists ( "Background", $parameters) || (int) $parameters["Background"] <= 0 || (int) $parameters["Background"] >= 15)
  {
    $data["Background"] = __ ( "The provided background is not valid.");
  }
  if ( ! array_key_exists ( "Password", $parameters))
  {
    $data["Password"] = __ ( "The provided password enabled field is not valid.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "authentication_edit_validate"))
  {
    $data = framework_call ( "authentication_edit_validate", $parameters, false, $data);
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
  $parameters["Status"] = (boolean) $parameters["Status"];
  $parameters["Background"] = (int) $parameters["Background"];
  $parameters["Password"] = (boolean) $parameters["Password"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "authentication_edit_sanitize"))
  {
    $parameters = framework_call ( "authentication_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "authentication_edit_pre"))
  {
    $parameters = framework_call ( "authentication_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change configuration entry
   */
  $tmp = json_encode ( array ( "Status" => $parameters["Status"], "Background" => $parameters["Background"], "Password" => $parameters["Password"]));
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Authentication', " . (int) $_in["session"]["Tenant"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $tmp) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( $tmp) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "authentication_edit_post"))
  {
    framework_call ( "authentication_edit_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "authentication_edit_finish"))
  {
    framework_call ( "authentication_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to extension password authentication
 */
framework_add_hook (
  "authentication_password",
  "authentication_password",
  IN_HOOK_INSERT_FIRST,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Username" => array (
          "type" => "string",
          "description" => __ ( "The username of user to authenticate."),
          "required" => true,
          "example" => "1001"
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "The password of user to authenticate."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Context" => array (
          "type" => "string",
          "description" => __ ( "The tenant domain of user to authenticate. If not provided, the server hostname will be used."),
          "required" => false,
          "example" => "voipdomain.io"
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New user authentication token created successfully."),
        "headers" => array (
          "Set-Cookie" => array (
            "schema" => array (
              "type" => "string"
            ),
            "description" => __ ( "System authentication token cookie."),
            "example" => $_in["general"]["cookie"] . "_authtoken=80a2a3c21a167ca3cd1781c49875f0ac16d669afd11181c92589e0d73c482b00; path=/"
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Result" => array (
              "type" => "boolean",
              "description" => __ ( "The status of authentication token creation request."),
              "example" => false
            ),
            "Message" => array (
              "type" => "string",
              "description" => __ ( "The login error message, if failed."),
              "example" => __ ( "Invalid username and/or password.")
            )
          )
        )
      ),
      401 => array ()
    )
  )
);
framework_add_api_call (
  "/auth",
  "Create",
  "authentication_password",
  array (
    "unauthenticated" => true,
    "title" => __ ( "User authentication token"),
    "description" => __ ( "Create a new user token.")
  )
);

/**
 * Function to authenticate an extension based on their password, creating
 * authentication token to user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Result of execution
 */
function authentication_password ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get current tenant ID
   */
  $tenantid = get_tenant ( ! empty ( $parameters["Context"]) ? $parameters["Context"] : $_SERVER["HTTP_HOST"]);

  /**
   * Get authentication config from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Tenant` = " . (int) $tenantid . " AND `Key` LIKE 'Authentication'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $config = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit ();
  }
  $config["Data"] = json_decode ( $config["Data"], true);

  /**
   * Return if password authentication is disabled
   */
  if ( $config["Data"]["Password"] === false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit ();
  }

  /**
   * Disable API debug function
   */
  framework_remove_hook_function ( "pre_shutdown", "send_internal_debug");

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "authentication_password_start"))
  {
    $parameters = framework_call ( "authentication_password_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( empty ( $parameters["Username"]))
  {
    $data["Username"] = __ ( "The username is required.");
  }
  if ( empty ( $parameters["Password"]))
  {
    $data["Password"] = __ ( "The password is required.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "authentication_password_validate"))
  {
    $data = framework_call ( "authentication_password_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    $data["Result"] = false;
    if ( ! array_key_exists ( "Message", $data))
    {
      $data["Message"] = __ ( "Error authenticating user.");
    }
    return $data;
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "authentication_password_sanitize"))
  {
    $parameters = framework_call ( "authentication_password_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "authentication_password_pre"))
  {
    $parameters = framework_call ( "authentication_password_pre", $parameters, false, $parameters);
  }

  /**
   * Validate user into database
   */
  if ( ! $result = $_in["mysql"]["id"]->query ( "SELECT `ExtensionPhone`.* FROM `ExtensionPhone` LEFT JOIN `Extensions` ON `Extensions`.`ID` = `ExtensionPhone`.`Extension` WHERE `Extension`.`Tenant` = " . (int) $tenantid . " AND `ExtensionPhone`.`Email` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "' AND `ExtensionPhone`.`Password` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Password"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $userInfo = $result->fetch_assoc ())
  {
    /**
     * Call authentication failure plugin modules if exists
     */
    filters_call ( "authentication_token_failure");

    /**
     * And return error message
     */
    $data["Result"] = false;
    $data["Message"] = __ ( "Invalid username and/or password.");
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Insert token in database
   */
  $token = bin2hex ( openssl_random_pseudo_bytes ( 16));
  $cookie = bin2hex ( openssl_random_pseudo_bytes ( 8));
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `AuthenticationToken` (`Token`, `Tenant`, `Plugin`, `Email`, `IssueDate`, `LastSeen`, `Expires`, `TokenData`, `UserData`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $cookie) . "', " . (int) $tenantid . ", 'Password', '" . $_in["mysql"]["id"]->real_escape_string ( $userInfo["Email"]) . "', NOW(), NOW(), '" . $_in["mysql"]["id"]->real_escape_string ( date ( "Y-m-d h:i:s", time () + $_in["general"]["timeout"])) . "', '', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $userInfo)) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "authentication_password_post"))
  {
    $data = framework_call ( "authentication_password_post", $parameters, false, $data);
  }

  /**
   * Start user session
   */
  setcookie ( $_in["general"]["cookie"] . "_authtoken", $cookie, time () + $_in["general"]["timeout"], "/" . ( PHP_VERSION_ID < 70300 ? "; SameSite=Strict" : ""));

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "authentication_password_finish"))
  {
    framework_call ( "authentication_password_finish", $parameters);
  }

  /**
   * Return OK
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . ( $callback ? $callback : "/auth") . "?Message=" . urlencode ( __ ( "User authenticated!")) . "&MessageType=info");
  return $buffer;
}

/**
 * Implement tenant addition hook
 */
framework_add_hook ( "tenants_add_post", "authentication_tenant_add_post");

/**
 * Function to add default authentication settings to new tenant.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_tenant_add_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add authentication settings
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Authentication', " . (int) $parameters["ID"] . ", '{\"Status\":false,\"Background\":1,\"Password\":true}}')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Return data to user
   */
  return $buffer;
}
?>
