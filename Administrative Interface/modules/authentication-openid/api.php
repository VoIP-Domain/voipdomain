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
 * VoIP Domain OpenID authentication module API. This module add the API calls
 * related to OpenID authentication support.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Authentication OpenID
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Extend system API authentication call view hook's
 */
framework_add_function_documentation (
  "authentication_view",
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "properties" => array (
            "Plugins" => array (
              "items" => array (
                "anyOf" => array (
                  "OpenID" => array (
                    "type" => "object",
                    "description" => __ ( "OpenID authentication configuration."),
                    "properties" => array (
                      "Status" => array (
                        "type" => "boolean",
                        "description" => __ ( "If the OpenID authentication is enabled."),
                        "example" => true
                      ),
                      "ClientID" => array (
                        "type" => "string",
                        "description" => __ ( "The OpenID client ID."),
                        "example" => "333327397147-2c2bmibvgol19v1sop5nnu2v62p84igp.apps.openidusercontent.com"
                      ),
                      "ClientSecret" => array (
                        "type" => "string",
                        "description" => __ ( "If OpenID client secret."),
                        "example" => "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"
                      ),
                      "RedirectURI" => array (
                        "type" => "string",
                        "description" => __ ( "The OpenID URI to be redirected."),
                        "example" => "https://devel.voipdomain.io/api/auth/openid"
                      ),
                      "ServerURI" => array (
                        "type" => "string",
                        "description" => __ ( "The base OpenID server URI."),
                        "example" => "https://rhsso.example.com/"
                      ),
                      "Realm" => array (
                        "type" => "string",
                        "description" => __ ( "The OpenID realm."),
                        "example" => "voipdomain"
                      )
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "authentication_view_post",
  "authentication_view_post_openid"
);

/**
 * Function to generate system OpenID authentication information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_view_post_openid ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get authentication from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Key` = 'Authentication_OpenID'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $buffer["Plugins"]["OpenID"] = array ();
  if ( $tmp = $result->fetch_assoc () && is_json ( $tmp["Data"]))
  {
    $buffer["Plugins"]["OpenID"] = json_decode ( $tmp["Data"], true);
  }

  /**
   * Return structured data
   */
  return $buffer;
}

/**
 * Extend system API authentication call edit hook's
 */
framework_add_function_documentation (
  "authentication_edit",
  array (
    "requests" => array (
      "properties" => array (
        "Plugins" => array (
          "items" => array (
            "anyOf" => array (
              "OpenID" => array (
                "type" => "object",
                "description" => __ ( "OpenID authentication configuration."),
                "required" => false,
                "properties" => array (
                  "Status" => array (
                    "type" => "boolean",
                    "description" => __ ( "If the OpenID authentication is enabled."),
                    "required" => true,
                    "example" => true
                  ),
                  "ClientID" => array (
                    "type" => "string",
                    "description" => __ ( "The OpenID client ID."),
                    "required" => true,
                    "example" => "333327397147-2c2bmibvgol19v1sop5nnu2v62p84igp.apps.openidusercontent.com"
                  ),
                  "ClientSecret" => array (
                    "type" => "string",
                    "description" => __ ( "If OpenID client secret."),
                    "required" => true,
                    "example" => "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"
                  ),
                  "RedirectURI" => array (
                    "type" => "string",
                    "description" => __ ( "The OpenID URI to be redirected."),
                    "required" => true,
                    "example" => "https://devel.voipdomain.io/api/auth/openid"
                  ),
                  "ServerURI" => array (
                    "type" => "string",
                    "description" => __ ( "The base OpenID server URI."),
                    "required" => true,
                    "example" => "https://rhsso.example.com/"
                  ),
                  "Realm" => array (
                    "type" => "string",
                    "description" => __ ( "The OpenID realm."),
                    "required" => true,
                    "example" => "voipdomain"
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "authentication_edit_validate",
  "authentication_edit_validate_openid"
);
framework_add_hook (
  "authentication_edit_sanitize",
  "authentication_edit_sanitize_openid"
);
framework_add_hook (
  "authentication_edit_post",
  "authentication_edit_post_openid"
);

/**
 * Function to validate system OpenID authentication information.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_edit_validate_openid ( $buffer, $parameters)
{
  /**
   * Check if OpenID authentication parameters were provided
   */
  if ( ! array_key_exists ( "OpenID", $parameters["Plugins"]))
  {
    return $buffer;
  }

  /**
   * Validate received parameters
   */
  if ( ! array_key_exists ( "Status", $parameters["Plugins"]["OpenID"]))
  {
    $buffer["Plugin_OpenID_Status"] = __ ( "The provided status is not valid.");
  }
  if ( (boolean) $parameters["Plugins"]["OpenID"]["Status"])
  {
    if ( ! array_key_exists ( "ClientID", $parameters["Plugins"]["OpenID"]) || empty ( $parameters["Plugins"]["OpenID"]["ClientID"]))
    {
      $buffer["Plugin_OpenID_Client_ID"] = __ ( "The provided client ID is not valid.");
    }
    if ( ! array_key_exists ( "ClientSecret", $parameters["Plugins"]["OpenID"]) || empty ( $parameters["Plugins"]["OpenID"]["ClientSecret"]))
    {
      $buffer["Plugin_OpenID_Client_Secret"] = __ ( "The provided client secret is not valid.");
    }
    if ( ! array_key_exists ( "RedirectURI", $parameters["Plugins"]["OpenID"]) || empty ( $parameters["Plugins"]["OpenID"]["RedirectURI"]))
    {
      $buffer["Plugin_OpenID_Redirect_URI"] = __ ( "The provided redirect URI is not valid.");
    }
    if ( ! array_key_exists ( "Plugin_OpenID_Redirect_URI", $buffer) && ! preg_match ( "/^http(s)?:\/\/(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])\/api\/auth\/openid$/", $parameters["Plugins"]["OpenID"]["RedirectURI"]))
    {
      $buffer["Plugin_OpenID_Redirect_URI"] = __ ( "The redirect URI must be valid and point to /api/auth/openid.");
    }
    if ( ! array_key_exists ( "ServerURI", $parameters["Plugins"]["OpenID"]) || empty ( $parameters["Plugins"]["OpenID"]["ServerURI"]))
    {
      $buffer["Plugin_OpenID_Server_URI"] = __ ( "The provided server URI is not valid.");
    }
    if ( ! array_key_exists ( "Plugin_OpenID_Server_URI", $buffer) && ! preg_match ( "/^https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)$/", $parameters["Plugins"]["OpenID"]["ServerURI"]))
    {
      $buffer["Plugin_OpenID_Server_URI"] = __ ( "The server URI must be valid.");
    }
    if ( ! array_key_exists ( "Plugin_OpenID_Server_URI", $buffer) && ( ( substr_count ( $parameters["Plugins"]["OpenID"]["ServerURI"], "/") == 3 && substr ( $parameters["Plugins"]["OpenID"]["ServerURI"], -1) != "/") || ( substr_count ( $parameters["Plugins"]["OpenID"]["ServerURI"], "/") == 2 && substr ( $parameters["Plugins"]["OpenID"]["ServerURI"], -1) == "/") || substr_count ( $parameters["Plugins"]["OpenID"]["ServerURI"], "/") > 3))
    {
      $buffer["Plugin_OpenID_Server_URI"] = __ ( "The server URI must be valid.");
    }
    if ( ! array_key_exists ( "Realm", $parameters["Plugins"]["OpenID"]) || empty ( $parameters["Plugins"]["OpenID"]["Realm"]))
    {
      $buffer["Plugin_OpenID_Realm"] = __ ( "The provided Realm is not valid.");
    }
    if ( ! array_key_exists ( "Plugin_OpenID_Realm", $buffer) && ! preg_match ( "/^[-a-zA-Z0-9()@:%_\+.~#?&=]+$/", $parameters["Plugins"]["OpenID"]["Realm"]))
    {
      $buffer["Plugin_OpenID_Realm"] = __ ( "The provided Realm is not valid.");
    }
  }

  return $buffer;
}

/**
 * Function to sanitize system OpenID authentication information.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_edit_sanitize_openid ( $buffer, $parameters)
{
  /**
   * Sanitize parameters
   */
  $parameters["Plugins"]["OpenID"]["Status"] = (boolean) $parameters["Plugins"]["OpenID"]["Status"];
  if ( substr_count ( $parameters["Plugins"]["OpenID"]["ServerURI"], "/") == 2)
  {
    $parameters["Plugins"]["OpenID"]["ServerURI"] .= "/";
  }

  return $parameters;
}

/**
 * Function to update system OpenID authentication information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_edit_post_openid ( $buffer, $parameters)
{
  global $_in;

  /**
   * Change configuration entry
   */
  $tmp = json_encode ( array ( "Status" => $parameters["Plugins"]["OpenID"]["Status"], "ClientID" => $parameters["Plugins"]["OpenID"]["ClientID"], "ClientSecret" => $parameters["Plugins"]["OpenID"]["ClientSecret"], "RedirectURI" => $parameters["Plugins"]["OpenID"]["RedirectURI"], "ServerURI" => $parameters["Plugins"]["OpenID"]["ServerURI"], "Realm" => $parameters["Plugins"]["OpenID"]["Realm"]));
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Authentication_OpenID', " . (int) $_in["session"]["Tenant"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $tmp) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( $tmp) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Return structured data
   */
  return $buffer;
}

/**
 * API call to redirect user to OpenID Authentication endpoint
 */
framework_add_hook (
  "authentication_openid_redirect",
  "authentication_openid_redirect"
);
framework_add_api_call (
  "/auth/openid",
  "Read",
  "authentication_openid_redirect",
  array (
    "unauthenticated" => true,
    "documented" => false
  )
);

/**
 * Function to redirect user to OpenID Authentication endpoint.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_openid_redirect ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get current tenant ID
   */
  $tenantid = get_tenant ( ! empty ( $parameters["Context"]) ? $parameters["Context"] : $_SERVER["HTTP_HOST"]);

  /**
   * Get authentication plugin information from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Tenant` = " . (int) $tenantid . " AND `Key` LIKE 'Authentication_OpenID'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $plugin = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit ();
  }
  $plugin["Data"] = json_decode ( $plugin["Data"], true);

  /**
   * Return if plugin is disabled
   */
  if ( $plugin["Data"]["State"] === false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit ();
  }

  /**
   * Disable API debug function
   */
  framework_remove_hook_function ( "pre_shutdown", "send_internal_debug");

  /**
   * If no code provided, redirect to OpenID
   */
  if ( ! array_key_exists ( "code", $parameters))
  {
    /**
     * Generate session security hash and store it
     */
    $state = bin2hex ( openssl_random_pseudo_bytes ( 16));
    $cookie = bin2hex ( openssl_random_pseudo_bytes ( 8));
    $expire = time () + 300;
    setcookie ( $_in["general"]["cookie"] . "_oauth2_" . $cookie, $state, $expire, "/api/auth/openid");
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `AuthenticationCache` (`Tenant`, `Plugin`, `Cookie`, `State`, `Expire`, `Callback`) VALUES (" . (int) $tenantid . ", 'OpenID', '" . $_in["mysql"]["id"]->real_escape_string ( $cookie) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $state) . "', " . (int) $expire . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Callback"]) . "')"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }

    /**
     * Redirect user to OpenID Authentication endpoint
     */
    header ( $_SERVER["SERVER_PROTOCOL"] . " 302 Redirected");
    header ( "Location: " . $plugin["Data"]["ServerURI"] . "auth/realms/" . urlencode ( $plugin["Data"]["Realm"]) . "/protocol/openid-connect/auth?" . http_build_query ( array (
                                                                                                                                                                            "response_type" => "code",
                                                                                                                                                                            "client_id" => $plugin["Data"]["ClientID"],
                                                                                                                                                                            "redirect_uri" => $plugin["Data"]["RedirectURI"],
                                                                                                                                                                            "scope" => "openid email profile",
                                                                                                                                                                            "state" => $state
                                                                                                                                                                          )
                                                                                                                                                                        )
    );
    exit ();
  }

  /**
   * Validate state
   */
  foreach ( $_COOKIE as $key => $val)
  {
    if ( substr ( $key, 0, strlen ( $_in["general"]["cookie"] . "_oauth2_")) == $_in["general"]["cookie"] . "_oauth2_")
    {
      $cookie = substr ( $key, strlen ( $_in["general"]["cookie"] . "_oauth2_"));
      $state = $val;
      setcookie ( $key, "", -1);
    }
  }
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `AuthenticationCache` WHERE `Plugin` = 'OpenID' AND `Cookie` = '" . $_in["mysql"]["id"]->real_escape_string ( $cookie) . "' AND `State` = '" . $_in["mysql"]["id"]->real_escape_string ( $state) . "' AND `Expire` >= " . time ()))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $cache = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 302 Redirected");
    header ( "Location: /auth?Message=" . urlencode ( __ ( "Invalid authentication token!")));
    exit ();
  }

  /**
   * Clean authentication cache
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `AuthenticationCache` WHERE `Plugin` = 'OpenID' AND `Cookie` = '" . $_in["mysql"]["id"]->real_escape_string ( $cookie) . "' AND `State` = '" . $_in["mysql"]["id"]->real_escape_string ( $state) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Exchange code for session token
   */
  $ch = curl_init ( $plugin["Data"]["ServerURI"] . "auth/realms/" . urlencode ( $plugin["Data"]["Realm"]) . "/protocol/openid-connect/token");
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ( $ch, CURLOPT_POST, true);
  curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ( array (
                                                              "grant_type" => "authorization_code",
                                                              "client_id" => $plugin["Data"]["ClientID"],
                                                              "client_secret" => $plugin["Data"]["ClientSecret"],
                                                              "code" => $parameters["code"],
                                                              "redirect_uri" => $plugin["Data"]["RedirectURI"]
                                                            )
                                                          )
  );
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
                                           "User-Agent: VoIP Domain" . ( $_in["general"]["version"] ? " v" . $_in["general"]["version"] : ""),
                                           "Content-Type: application/x-www-form-urlencoded"
                                         )
  );
  $response = curl_exec ( $ch);
  curl_close ( $ch);
  $tokenInfo = json_decode ( $response, true);
  if ( ! array_key_exists ( "access_token", $tokenInfo))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 302 Redirected");
    header ( "Location: /auth?" . ( $cache["Callback"] ? "Callback=" . urlencode ( $cache["Callback"]) . "&" : "") . "Message=" . urlencode ( __ ( "Invalid authentication token!")));
    exit ();
  }
  $accessToken = $tokenInfo["access_token"];

  /**
   * Fetch user information
   */
  $ch = curl_init ( $plugin["Data"]["ServerURI"] . "auth/realms/" . urlencode ( $plugin["Data"]["Realm"]) . "/protocol/openid-connect/userinfo");
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
                                           "User-Agent: VoIP Domain" . ( $_in["general"]["version"] ? " v" . $_in["general"]["version"] : ""),
                                           "Authorization: Bearer " . $accessToken
                                         )
  );
  $response = curl_exec ( $ch);
  curl_close ( $ch);
  $userInfo = json_decode ( $response, true);

  /**
   * Insert token in database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `AuthenticationToken` (`Token`, `Tenant`, `Plugin`, `Email`, `IssueDate`, `LastSeen`, `Expires`, `TokenData`, `UserData`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $cookie) . "', " . (int) $tenantid . ", 'OpenID', '" . $_in["mysql"]["id"]->real_escape_string ( $userInfo["email"]) . "', NOW(), NOW(), '" . $_in["mysql"]["id"]->real_escape_string ( date ( "Y-m-d h:i:s", time () + $tokenInfo["expires_in"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $tokenInfo)) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $userInfo)) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  setcookie ( $_in["general"]["cookie"] . "_authtoken", $cookie, time () + $tokenInfo["expires_in"], "/");

  /**
   * Redirect user to authentication page
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 302 Redirected");
  header ( "Location: " . ( $cache["Callback"] ? $cache["Callback"] : "/auth") . "?Message=" . urlencode ( __ ( "User authenticated!")) . "&MessageType=info");
  exit ();
}

/**
 * Implement tenant addition hook
 */
framework_add_hook ( "tenants_add_post", "authentication_openid_tenant_add_post");

/**
 * Function to add default OpenID authentication settings to new tenant.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_openid_tenant_add_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add authentication settings
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Tenant`, `Data`) VALUES ('Authentication_Plugin_OpenID', " . (int) $parameters["ID"] . ", '{\"Status\":false,\"ClientID\":\"\",\"ClientSecret\":\"\",\"RedirectURI\":\"\"}}')"))
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
