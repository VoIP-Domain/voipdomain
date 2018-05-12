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
 * VoIP Domain GitHub OAuth2 authentication module API. This module add the API
 * calls related to GitHub OAuth2 authentication support.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Authentication GitHub
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
                  "GitHub" => array (
                    "type" => "object",
                    "description" => __ ( "GitHub OAuth2 authentication configuration."),
                    "properties" => array (
                      "Status" => array (
                        "type" => "boolean",
                        "description" => __ ( "If the GitHub authentication is enabled."),
                        "example" => true
                      ),
                      "ClientID" => array (
                        "type" => "string",
                        "description" => __ ( "The GitHub OAuth2 client ID."),
                        "example" => "333327397147-2c2bmibvgol19v1sop5nnu2v62p84igp.apps.githubusercontent.com"
                      ),
                      "ClientSecret" => array (
                        "type" => "string",
                        "description" => __ ( "If GitHub OAuth2 client secret."),
                        "example" => "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"
                      ),
                      "RedirectURI" => array (
                        "type" => "string",
                        "description" => __ ( "The GitHub OAuth2 URI to be redirected."),
                        "example" => "https://devel.voipdomain.io/api/auth/github"
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
  "authentication_view_post_github"
);

/**
 * Function to generate system GitHub authentication information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_view_post_github ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get authentication from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` = 'Authentication_GitHub'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $buffer["Plugins"]["GitHub"] = array ();
  if ( $tmp = $result->fetch_assoc () && is_json ( $tmp["Data"]))
  {
    $buffer["Plugins"]["GitHub"] = json_decode ( $tmp["Data"], true);
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
              "GitHub" => array (
                "type" => "object",
                "description" => __ ( "GitHub OAuth2 authentication configuration."),
                "required" => false,
                "properties" => array (
                  "Status" => array (
                    "type" => "boolean",
                    "description" => __ ( "If the GitHub authentication is enabled."),
                    "required" => true,
                    "example" => true
                  ),
                  "ClientID" => array (
                    "type" => "string",
                    "description" => __ ( "The GitHub OAuth2 client ID."),
                    "required" => true,
                    "example" => "333327397147-2c2bmibvgol19v1sop5nnu2v62p84igp.apps.githubusercontent.com"
                  ),
                  "ClientSecret" => array (
                    "type" => "string",
                    "description" => __ ( "If GitHub OAuth2 client secret."),
                    "required" => true,
                    "example" => "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"
                  ),
                  "RedirectURI" => array (
                    "type" => "string",
                    "description" => __ ( "The GitHub OAuth2 URI to be redirected."),
                    "required" => true,
                    "example" => "https://devel.voipdomain.io/api/auth/github"
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
  "authentication_edit_validate_github"
);
framework_add_hook (
  "authentication_edit_sanitize",
  "authentication_edit_sanitize_github"
);
framework_add_hook (
  "authentication_edit_post",
  "authentication_edit_post_github"
);

/**
 * Function to validate system GitHub authentication information.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_edit_validate_github ( $buffer, $parameters)
{
  /**
   * Check if GitHub authentication parameters were provided
   */
  if ( ! array_key_exists ( "GitHub", $parameters["Plugins"]))
  {
    return $buffer;
  }

  /**
   * Validate received parameters
   */
  if ( ! array_key_exists ( "Status", $parameters["Plugins"]["GitHub"]))
  {
    $buffer["Plugin_GitHub_Status"] = __ ( "The provided status is not valid.");
  }
  if ( (boolean) $parameters["Plugins"]["GitHub"]["Status"])
  {
    if ( ! array_key_exists ( "ClientID", $parameters["Plugins"]["GitHub"]) || empty ( $parameters["Plugins"]["GitHub"]["ClientID"]))
    {
      $buffer["Plugin_GitHub_Client_ID"] = __ ( "The provided client ID is not valid.");
    }
    if ( ! array_key_exists ( "ClientSecret", $parameters["Plugins"]["GitHub"]) || empty ( $parameters["Plugins"]["GitHub"]["ClientSecret"]))
    {
      $buffer["Plugin_GitHub_Client_Secret"] = __ ( "The provided client secret is not valid.");
    }
    if ( ! array_key_exists ( "RedirectURI", $parameters["Plugins"]["GitHub"]) || empty ( $parameters["Plugins"]["GitHub"]["RedirectURI"]))
    {
      $buffer["Plugin_GitHub_Redirect_URI"] = __ ( "The provided redirect URI is not valid.");
    }
    if ( ! array_key_exists ( "Plugin_GitHub_Redirect_URI", $buffer) && ! preg_match ( "/^http(s)?:\/\/(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])\/api\/auth\/github$/", $parameters["Plugins"]["GitHub"]["RedirectURI"]))
    {
      $buffer["Plugin_GitHub_Redirect_URI"] = __ ( "The redirect URI must be valid and point to /api/auth/github.");
    }
  }

  return $buffer;
}

/**
 * Function to sanitize system GitHub authentication information.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_edit_sanitize_github ( $buffer, $parameters)
{
  /**
   * Sanitize parameters
   */
  $parameters["Plugins"]["GitHub"]["Status"] = (boolean) $parameters["Plugins"]["GitHub"]["Status"];

  return $parameters;
}

/**
 * Function to update system GitHub authentication information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_edit_post_github ( $buffer, $parameters)
{
  global $_in;

  /**
   * Change configuration entry
   */
  $tmp = json_encode ( array ( "Status" => $parameters["Plugins"]["GitHub"]["Status"], "ClientID" => $parameters["Plugins"]["GitHub"]["ClientID"], "ClientSecret" => $parameters["Plugins"]["GitHub"]["ClientSecret"], "RedirectURI" => $parameters["Plugins"]["GitHub"]["RedirectURI"]));
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Config` (`Key`, `Data`) VALUES ('Authentication_GitHub', '" . $_in["mysql"]["id"]->real_escape_string ( $tmp) . "') ON DUPLICATE KEY UPDATE `Data` = '" . $_in["mysql"]["id"]->real_escape_string ( $tmp) . "'"))
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
 * API call to redirect user to GitHub Authentication endpoint
 */
framework_add_hook (
  "authentication_github_redirect",
  "authentication_github_redirect"
);
framework_add_api_call (
  "/auth/github",
  "Read",
  "authentication_github_redirect",
  array (
    "unauthenticated" => true,
    "documented" => false
  )
);

/**
 * Function to redirect user to GitHub Authentication endpoint.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_github_redirect ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get authentication plugins from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` LIKE 'Authentication_GitHub'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $plugin = json_decode ( $result->fetch_assoc ()["Data"], true))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit ();
  }

  /**
   * Return if plugin is disabled
   */
  if ( $plugin["State"] === false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit ();
  }

  /**
   * Disable API debug function
   */
  framework_remove_hook_function ( "pre_shutdown", "send_internal_debug");

  /**
   * If no code provided, redirect to GitHub OAuth2
   */
  if ( ! array_key_exists ( "code", $parameters))
  {
    /**
     * Generate session security hash and store it
     */
    $state = bin2hex ( openssl_random_pseudo_bytes ( 16));
    $cookie = bin2hex ( openssl_random_pseudo_bytes ( 8));
    $expire = time () + 300;
    setcookie ( $_in["general"]["cookie"] . "_oauth2_" . $cookie, $state, $expire, "/api/auth/github");
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `AuthenticationCache` (`Plugin`, `Cookie`, `State`, `Expire`, `Callback`) VALUES ('GitHub', '" . $_in["mysql"]["id"]->real_escape_string ( $cookie) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $state) . "', " . $_in["mysql"]["id"]->real_escape_string ( $expire) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Callback"]) . "')"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }

    /**
     * Redirect user to GitHub Authentication endpoint
     */
    header ( $_SERVER["SERVER_PROTOCOL"] . " 302 Redirected");
    header ( "Location: https://github.com/login/oauth/authorize?" . http_build_query ( array (
                                                                                          "client_id" => $plugin["ClientID"],
                                                                                          "redirect_uri" => $plugin["RedirectURI"],
                                                                                          "scope" => "read:user user:email",
                                                                                          "state" => $state,
                                                                                          "allow_signup" => "false"
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `AuthenticationCache` WHERE `Plugin` = 'GitHub' AND `Cookie` = '" . $_in["mysql"]["id"]->real_escape_string ( $cookie) . "' AND `State` = '" . $_in["mysql"]["id"]->real_escape_string ( $state) . "' AND `Expire` >= " . time ()))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $callback = $result->fetch_assoc ()["Callback"])
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 302 Redirected");
    header ( "Location: /auth?Message=" . urlencode ( __ ( "Invalid authentication token!")));
    exit ();
  }

  /**
   * Clean authentication cache
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `AuthenticationCache` WHERE `Plugin` = 'GitHub' AND `Cookie` = '" . $_in["mysql"]["id"]->real_escape_string ( $cookie) . "' AND `State` = '" . $_in["mysql"]["id"]->real_escape_string ( $state) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Exchange code for session token
   */
  $ch = curl_init ( "https://github.com/login/oauth/access_token");
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ( $ch, CURLOPT_POST, true);
  curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ( array (
                                                              "client_id" => $plugin["ClientID"],
                                                              "client_secret" => $plugin["ClientSecret"],
                                                              "code" => $parameters["code"],
                                                              "redirect_uri" => $plugin["RedirectURI"]
                                                            )
                                                          )
  );
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
                                           "User-Agent: VoIP Domain" . ( $_in["general"]["version"] ? " v" . $_in["general"]["version"] : ""),
                                           "Accept: application/json"
                                         )
  );
  $response = curl_exec ( $ch);
  curl_close ( $ch);
  $tokenInfo = json_decode ( $response, true);
  if ( ! array_key_exists ( "access_token", $tokenInfo))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 302 Redirected");
    header ( "Location: /auth?" . ( $callback ? "Callback=" . urlencode ( $callback) . "&" : "") . "Message=" . urlencode ( __ ( "Invalid authentication token!")));
    exit ();
  }
  $accessToken = $tokenInfo["access_token"];

  /**
   * Fetch user information
   */
  $ch = curl_init ( "https://api.github.com/user");
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
                                           "User-Agent: VoIP Domain" . ( $_in["general"]["version"] ? " v" . $_in["general"]["version"] : ""),
                                           "Authorization: Bearer " . $accessToken
                                         )
  );
  $response = curl_exec ( $ch);
  curl_close ( $ch);
  $userInfo = json_decode ( $response, true);
  $ch = curl_init ( "https://api.github.com/user/emails");
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
                                           "User-Agent: VoIP Domain" . ( $_in["general"]["version"] ? " v" . $_in["general"]["version"] : ""),
                                           "Authorization: Bearer " . $accessToken
                                         )
  );
  $response = curl_exec ( $ch);
  curl_close ( $ch);
  $userInfo = array ( "userInfo" => $userInfo, "emailInfo" => json_decode ( $response, true));

  /**
   * Insert token in database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `AuthenticationToken` (`Token`, `Plugin`, `Email`, `IssueDate`, `LastSeen`, `Expires`, `TokenData`, `UserData`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $cookie) . "', 'GitHub', '" . $_in["mysql"]["id"]->real_escape_string ( $userInfo["emailInfo"][0]["email"]) . "', NOW(), NOW(), '" . $_in["mysql"]["id"]->real_escape_string ( date ( "Y-m-d h:i:s", time () + $_in["general"]["timeout"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $tokenInfo)) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $userInfo)) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  setcookie ( $_in["general"]["cookie"] . "_authtoken", $cookie, time () + $_in["general"]["timeout"], "/");

  /**
   * Redirect user to authentication page
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 302 Redirected");
  header ( "Location: " . ( $callback ? $callback : "/auth") . "?Message=" . urlencode ( __ ( "User authenticated!")) . "&MessageType=info");
  exit ();
}
?>
