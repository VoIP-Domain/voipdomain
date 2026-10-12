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
 * VoIP Domain framework main API file. This file contains all functions and
 * routines needed to provide the system API basic infra structure. This file
 * will handle every API request, validate the client, check permissions, fire
 * requested call, format output data into requested format and return to the
 * user.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR | E_USER_ERROR);
ini_set ( "display_errors", 0);
// error_reporting ( E_ALL); ini_set ( "display_errors", 1);

/**
 * Start output buffer to prevent any stray PHP output from corrupting JSON
 */
ob_start ();

/**
 * Bootstrap webroot and load configuration (does not depend on PHP-FPM chdir).
 */
require_once ( __DIR__ . "/includes/bootstrap.inc.php");

/**
 * Custom error handler: captures all PHP errors, warnings, notices, and
 * deprecations into a structured array instead of letting them output as HTML.
 */
$_in["php_errors"] = array ();
set_error_handler ( function ( $errno, $errstr, $errfile, $errline) {
  global $_in;
  $types = array (
    E_WARNING => "warning", E_NOTICE => "notice",
    E_DEPRECATED => "deprecated", E_USER_WARNING => "user_warning",
    E_USER_NOTICE => "user_notice", E_USER_DEPRECATED => "user_deprecated",
    E_USER_ERROR => "user_error"
  );
  $_in["php_errors"][] = array (
    "type" => isset ( $types[$errno]) ? $types[$errno] : "unknown(" . $errno . ")",
    "message" => $errstr,
    "file" => basename ( $errfile),
    "line" => $errline
  );
  return true;
}, E_ALL & ~E_ERROR & ~E_PARSE & ~E_CORE_ERROR & ~E_COMPILE_ERROR);

/**
 * Uncaught exception handler: catches TypeError, ValueError, and other throwables
 * that PHP 8+ promotes to exceptions instead of fatal errors.
 */
set_exception_handler ( function ( $e) {
  global $_in;
  if ( ob_get_level ())
  {
    ob_end_clean ();
  }
  if ( ! headers_sent ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    header ( "Content-Type: application/json");
  }
  $response = array ( "result" => false, "error" => "Uncaught exception");
  if ( is_array ( $_in) && array_key_exists ( "general", $_in) && ! empty ( $_in["general"]["debug"]))
  {
    $response["message"] = $e->getMessage ();
    $response["_debug"] = array (
      "exception" => array (
        "class" => get_class ( $e),
        "message" => $e->getMessage (),
        "file" => basename ( $e->getFile ()),
        "line" => $e->getLine (),
        "trace" => array_map ( function ( $frame) {
          return array (
            "file" => isset ( $frame["file"]) ? basename ( $frame["file"]) : "[internal]",
            "line" => $frame["line"] ?? 0,
            "function" => ( isset ( $frame["class"]) ? $frame["class"] . $frame["type"] : "") . ( $frame["function"] ?? "")
          );
        }, array_slice ( $e->getTrace (), 0, 10))
      ),
      "php_errors" => isset ( $_in["php_errors"]) ? $_in["php_errors"] : array ()
    );
  }
  echo json_encode ( $response);
  exit ( 1);
});

/**
 * Register shutdown function (post execution hook)
 */
function shutdown ()
{
  global $_in;

  /**
   * Check for fatal errors that bypassed the custom error handler.
   * Fatal errors (E_ERROR, E_PARSE, E_COMPILE_ERROR) cannot be caught by
   * set_error_handler but are visible via error_get_last() in the shutdown function.
   */
  $lastError = error_get_last ();
  if ( $lastError && in_array ( $lastError["type"], array ( E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR)))
  {
    if ( ob_get_level ())
    {
      ob_end_clean ();
    }
    if ( ! headers_sent ())
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
      header ( "Content-Type: application/json");
    }
    $response = array ( "result" => false, "error" => "Fatal error");
    if ( is_array ( $_in) && array_key_exists ( "general", $_in) && ! empty ( $_in["general"]["debug"]))
    {
      $response["message"] = $lastError["message"];
      $response["_debug"] = array (
        "fatal" => array (
          "type" => $lastError["type"],
          "message" => $lastError["message"],
          "file" => basename ( $lastError["file"]),
          "line" => $lastError["line"]
        ),
        "php_errors" => isset ( $_in["php_errors"]) ? $_in["php_errors"] : array ()
      );
    }
    echo json_encode ( $response);
    return;
  }

  /**
   * Pre-shutdown hook: Used to be executed before closing connection to client
   */
  if ( framework_has_hook ( "pre_shutdown"))
  {
    $data = framework_call ( "pre_shutdown", $parameters, false, $data);
  }

  /**
   * Close connection to client. After this point, any execution of code will not
   * return any content to the client, but will allow the execution of long tasks
   * without hanging the connection. Be aware that if you're using PHP-FPM, the
   * thread executing this task will hang, and if you use all the threads the
   * server will fail to execute any other request.
   */
  if ( framework_has_hook ( "shutdown") || sizeof ( $_in["notifycapture"]) != 0)
  {
    /**
     * Force script to continue executing even on user abort and without time limit
     */
    set_time_limit ( 0);
    session_write_close ();
    ignore_user_abort ( true);

    /**
     * Close the connection to server and client
     */
    if ( function_exists ( "fastcgi_finish_request"))
    {
      fastcgi_finish_request ();
    } else {
      @header ( "Connection: close");
      @ob_end_flush ();
      @ob_flush ();
      @flush ();
    }
  }

  /**
   * Shutdown hook: Used to be executed after closing connection to client
   */
  if ( framework_has_hook ( "shutdown"))
  {
    framework_call ( "shutdown", $parameters, false, $data);
  }

  /**
   * If any notify capture enabled, flush the events
   */
  if ( sizeof ( $_in["notifycapture"]) != 0)
  {
    foreach ( $_in["notifycapture"] as $server => $events)
    {
      notify_capture_send ( $server);
    }
  }
}
register_shutdown_function ( "shutdown");

/**
 * Set debugging if enabled
 */
if ( array_key_exists ( "debug", $_in["general"]) && $_in["general"]["debug"] == true)
{
  error_reporting ( E_ALL);
}

/**
 * Include module files when running in normal mode
 */
if ( $_in["mode"] == "normal")
{
  /**
   * Include main interface module
   */
  $_in["module"] = "";
  require_once ( "modules/interface/language.php");
  require_once ( "modules/interface/api.php");
  require_once ( "modules/interface/filter.php");

  /**
   * Include all modules files
   */
  foreach ( glob ( "modules/*") as $module)
  {
    if ( $module == "modules/interface" || ! is_dir ( $module))
    {
      continue;
    }
    $_in["module"] = basename ( $module);
    if ( is_readable ( $module . "/functions.php"))
    {
      require_once ( $module . "/functions.php");
    }
    if ( is_readable ( $module . "/functions-api.php"))
    {
      require_once ( $module . "/functions-api.php");
    }
    if ( is_readable ( $module . "/config.php"))
    {
      require_once ( $module . "/config.php");
    }
    if ( is_readable ( $module . "/language.php"))
    {
      require_once ( $module . "/language.php");
    }
    if ( is_readable ( $module . "/filter.php"))
    {
      require_once ( $module . "/filter.php");
    }
    if ( is_readable ( $module . "/api.php"))
    {
      require_once ( $module . "/api.php");
    }
  }
}

/**
 * Include install files when running in install mode
 */
if ( $_in["mode"] == "install")
{
  /**
   * Include main interface module
   */
  $_in["module"] = "";
  require_once ( "modules/interface/language.php");
  require_once ( "modules/interface/install.php");

  /**
   * Include all modules files
   */
  foreach ( glob ( "modules/*") as $module)
  {
    if ( $module == "modules/interface" || ! is_dir ( $module))
    {
      continue;
    }
    $_in["module"] = basename ( $module);
    if ( is_readable ( $module . "/install.php"))
    {
      require_once ( $module . "/install.php");
    }
  }
}

/**
 * Include all plugins files (except when calling from internal access)
 */
if ( $_SERVER["REMOTE_ADDR"] != "127.0.0.1")
{
  if ( $plugins = glob ( "plugins/*"))
  {
    foreach ( $plugins as $module)
    {
      if ( substr ( $module, 0, 17) == "plugins/disabled_" || ! is_dir ( $module))
      {
        continue;
      }
      $_in["module"] = basename ( $module);
      if ( is_readable ( $module . "/functions.php"))
      {
        require_once ( $module . "/functions.php");
      }
      if ( is_readable ( $module . "/functions-api.php"))
      {
        require_once ( $module . "/functions-api.php");
      }
      if ( is_readable ( $module . "/config.php"))
      {
        require_once ( $module . "/config.php");
      }
      if ( is_readable ( $module . "/language.php"))
      {
        require_once ( $module . "/language.php");
      }
      if ( is_readable ( $module . "/filter.php"))
      {
        require_once ( $module . "/filter.php");
      }
      if ( is_readable ( $module . "/api.php"))
      {
        require_once ( $module . "/api.php");
      }
    }
  }
}

/**
 * Resolve any deferred hook registrations (from framework_add_hook_at calls
 * where the reference function was not yet registered at call time)
 */
framework_resolve_deferred_hooks ();

/**
 * Enable CORS to any origin
 */
header ( "Access-Control-Allow-Origin: *");
header ( "Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS, HEAD, TRACE, COPY, PURGE, LINK, UNLINK");
header ( "Access-Control-Allow-Credentials: true");
header ( "Access-Control-Max-Age: 86400");
header ( "Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token,  Accept, Authorization, X-Requested-With, X-API-Version, X-HTTP-Method-Override, X-" . strtoupper ( $_in["general"]["cookie"]) . "-Token, X-" . strtoupper ( $_in["general"]["cookie"]) . "-SID, X-" . strtoupper ( $_in["general"]["cookie"]) . "-SPWD, X-" . strtoupper ( $_in["general"]["cookie"]) . "-Auth");

/**
 * First, check for user authentication, token or internal access
 */
if ( $_SERVER["REMOTE_ADDR"] == "127.0.0.1")
{
  $_in["session"]["Authenticated"] = true;
  $_in["session"]["Method"] = "Internal";
  $_in["session"]["Tenant"] = null;
  $_in["session"]["Permissions"][] = "Internal";
  $_in["session"]["Language"] = $_in["general"]["language"];
}
if ( $_in["mode"] == "install")
{
  $_in["session"]["Authenticated"] = true;
  $_in["session"]["Method"] = "Install";
  $_in["session"]["Tenant"] = null;
  $_in["session"]["Permissions"][] = "Install";
  $_in["session"]["Language"] = $_in["general"]["language"];
}
if ( $_in["mode"] != "install" && ( array_key_exists ( "HTTP_X_" . strtoupper ( $_in["general"]["cookie"]) . "_TOKEN", $_SERVER) || array_key_exists ( "token", $_GET)))
{
  // Get token from database:
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `Token` = '" . $_in["mysql"]["id"]->real_escape_string ( ( array_key_exists ( "HTTP_X_" . strtoupper ( $_in["general"]["cookie"]) . "_TOKEN", $_SERVER) ? $_SERVER["HTTP_X_" . strtoupper ( $_in["general"]["cookie"]) . "_TOKEN"] : $_GET["token"])) . "'"))
  {
    database_query_error ( "token_lookup");
  }
  if ( $token = $result->fetch_assoc ())
  {
    // Check if client IP is allowed:
    if ( ! cidr_match ( $_SERVER["REMOTE_ADDR"], $token["Access"]))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
      exit ();
    }

    /**
     * Set session variables
     */
    $_in["session"]["Authenticated"] = true;
    $_in["session"]["Method"] = "Token";
    $_in["session"]["Tenant"] = $token["Tenant"];
    $_in["session"]["Language"] = $token["Language"] ? $token["Language"] : $_in["general"]["language"];
    $_in["session"]["Data"]["ID"] = $token["ID"];
    $_in["session"]["Data"]["Description"] = $token["Description"];
    $_in["session"]["Data"]["Access"] = $token["Access"];
    $_in["session"]["Data"]["Expires"] = $token["Expires"];
    $_in["session"]["Permissions"][] = "Token";

    /**
     * Inject token permissions in session
     */
    foreach ( explode ( ",", $token["Permissions"]) as $permission)
    {
      $_in["session"]["Permissions"][] = $permission;
    }

    /**
     * Set system language if token has different language than system default
     */
    if ( ! empty ( $token["Language"]) && array_key_exists ( $token["Language"], $_in["languages"]))
    {
      $_in["general"]["language"] = $_in["token"]["Language"];
    }
  }
}
if ( $_in["mode"] != "install" && array_key_exists ( "HTTP_X_" . strtoupper ( $_in["general"]["cookie"]) . "_SID", $_SERVER) && array_key_exists ( "HTTP_X_" . strtoupper ( $_in["general"]["cookie"]) . "_SPWD", $_SERVER))
{
  // Get server from database:
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = '" . $_in["mysql"]["id"]->real_escape_string ( (int) $_SERVER["HTTP_X_" . strtoupper ( $_in["general"]["cookie"]) . "_SID"]) . "'"))
  {
    database_query_error ( "server_auth_lookup");
  }
  if ( $server = $result->fetch_assoc ())
  {
    // Check for server password
    if ( $_SERVER["HTTP_X_" . strtoupper ( $_in["general"]["cookie"]) . "_SPWD"] != hash ( "sha256", $server["Password"]))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
      exit ();
    }

    /**
     * Set session variables
     */
    $_in["session"]["Authenticated"] = true;
    $_in["session"]["Method"] = "Server";
    $_in["session"]["Tenant"] = $server["Tenant"];
    $_in["session"]["Language"] = $_in["general"]["language"];
    $_in["session"]["Data"]["ID"] = $server["ID"];
    $_in["session"]["Data"]["Description"] = $server["Description"];
    $_in["session"]["Data"]["PublicKey"] = $server["PublicKey"];
    $_in["session"]["Permissions"][] = "Server";
  }
}
if ( $_in["mode"] != "install" && array_key_exists ( $_in["general"]["cookie"] . "_authtoken", $_COOKIE))
{
  // Get server from database:
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `AuthenticationToken` WHERE `Token` = '" . $_in["mysql"]["id"]->real_escape_string ( $_COOKIE[$_in["general"]["cookie"] . "_authtoken"]) . "'"))
  {
    database_query_error ( "authtoken_lookup");
  }
  if ( $authtoken = $result->fetch_assoc ())
  {
    /**
     * Set session variables
     */
    $_in["session"]["Authenticated"] = true;
    $_in["session"]["Method"] = "AuthToken";
    $_in["session"]["Tenant"] = $authtoken["Tenant"];
    $_in["session"]["Language"] = $_in["general"]["language"];
    $_in["session"]["Data"]["Token"] = $authtoken["Token"];
    $_in["session"]["Data"]["Plugin"] = $authtoken["Plugin"];
    $_in["session"]["Data"]["Email"] = $authtoken["Email"];
    $_in["session"]["Data"]["IssueDate"] = $authtoken["IssueDate"];
    $_in["session"]["Data"]["LastSeen"] = $authtoken["LastSeen"];
    $_in["session"]["Data"]["Expires"] = $authtoken["Expires"];
    $_in["session"]["Permissions"][] = "AuthToken";
  }
}
if ( $_in["mode"] != "install" && array_key_exists ( $_in["general"]["cookie"], $_COOKIE))
{
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Sessions`.`SID`, `Sessions`.`LastSeen`, `Users`.* FROM `Sessions`, `Users` WHERE `Sessions`.`SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $_COOKIE[$_in["general"]["cookie"]]) . "' AND `Sessions`.`User` = `Users`.`ID`"))
  {
    database_query_error ( "session_lookup");
  }
  if ( $session = $result->fetch_assoc ())
  {
    /**
     * Check if session has expired
     */
    if ( $_in["general"]["timeout"] > 0 && $session["LastSeen"] + $_in["general"]["timeout"] < time ())
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 401 Unauthorized");
      setcookie ( $_in["general"]["cookie"], "", -1, "/");
      echo json_encode ( array ( "event" => "session_timeout"));
      exit ();
    }

    /**
     * Update session last seen
     */
    @$_in["mysql"]["id"]->query ( "UPDATE `Sessions` SET `LastSeen` = '" . $_in["mysql"]["id"]->real_escape_string ( time ()) . "' WHERE `SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $session["SID"]) . "'");

    /**
     * Set session variables
     */
    $_in["session"]["Authenticated"] = true;
    $_in["session"]["Method"] = "Session";
    $_in["session"]["Tenant"] = $session["Tenant"];
    $_in["session"]["Language"] = $session["Language"];
    $_in["session"]["Data"]["ID"] = $session["ID"];
    $_in["session"]["Data"]["Username"] = $session["Username"];
    $_in["session"]["Data"]["Name"] = $session["Name"];
    $_in["session"]["Data"]["Email"] = $session["Email"];
    $_in["session"]["Data"]["Since"] = $session["Since"];
    $_in["session"]["Data"]["LastSeen"] = $session["LastSeen"];
    $_in["session"]["Data"]["Expires"] = time () + $_in["general"]["timeout"];
    $_in["session"]["Permissions"][] = "User";

    /**
     * Inject user permissions in session
     */
    foreach ( json_decode ( $session["Permissions"], true) as $permission)
    {
      $_in["session"]["Permissions"][] = $permission;
    }

    /**
     * Set system language if user has different language than system default
     */
    if ( ! empty ( $session["Language"]) && array_key_exists ( $session["Language"], $_in["languages"]))
    {
      $_in["general"]["language"] = $session["Language"];
    }
  }
}
if ( $_in["mode"] != "install" && array_key_exists ( $_in["general"]["cookie"] . "_adm", $_COOKIE))
{
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `AdminSessions`.`SID`, `AdminSessions`.`LastSeen`, `Admins`.* FROM `AdminSessions`, `Admins` WHERE `AdminSessions`.`SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $_COOKIE[$_in["general"]["cookie"] . "_adm"]) . "' AND `AdminSessions`.`Admin` = `Admins`.`ID`"))
  {
    database_query_error ( "admin_session_lookup");
  }
  if ( $session = $result->fetch_assoc ())
  {
    /**
     * Check if session has expired
     */
    if ( $_in["general"]["timeout"] > 0 && $session["LastSeen"] + $_in["general"]["timeout"] < time ())
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 401 Unauthorized");
      setcookie ( $_in["general"]["cookie"] . "_adm", "", -1, "/");
      echo json_encode ( array ( "event" => "session_timeout"));
      exit ();
    }

    /**
     * Update session last seen
     */
    @$_in["mysql"]["id"]->query ( "UPDATE `AdminSessions` SET `LastSeen` = '" . $_in["mysql"]["id"]->real_escape_string ( time ()) . "' WHERE `SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $session["SID"]) . "'");

    /**
     * Set session variables
     */
    $_in["session"]["Authenticated"] = true;
    $_in["session"]["Method"] = "Session";
    $_in["session"]["Tenant"] = 0;
    $_in["session"]["Language"] = $session["Language"];
    $_in["session"]["Data"]["ID"] = $session["ID"];
    $_in["session"]["Data"]["Username"] = $session["Username"];
    $_in["session"]["Data"]["Name"] = $session["Name"];
    $_in["session"]["Data"]["Email"] = $session["Email"];
    $_in["session"]["Data"]["Since"] = $session["Since"];
    $_in["session"]["Data"]["LastSeen"] = $session["LastSeen"];
    $_in["session"]["Data"]["Expires"] = time () + $_in["general"]["timeout"];
    $_in["session"]["Permissions"][] = "Super-Administrator";

    /**
     * Inject user permissions in session
     */
    foreach ( json_decode ( $session["Permissions"], true) as $permission)
    {
      $_in["session"]["Permissions"][] = $permission;
    }

    /**
     * Set system language if user has different language than system default
     */
    if ( ! empty ( $session["Language"]) && array_key_exists ( $session["Language"], $_in["languages"]))
    {
      $_in["general"]["language"] = $session["Language"];
    }
  }
}

/**
 * Process requested route
 */
$apiRoutes = array ( "POST" => "create", "GET" => "read", "PUT" => "update", "DELETE" => "delete", "PATCH" => "modify", "OPTIONS" => "help", "HEAD" => "test", "TRACE" => "trace", "COPY" => "copy", "PURGE" => "purge", "LINK" => "link", "UNLINK" => "unlink");

/**
 * Respect Google recommendation for X-HTTP-Method-Override header
 */
if ( array_key_exists ( "HTTP_X_HTTP_METHOD_OVERRIDE", $_SERVER))
{
  $route = $_SERVER["HTTP_X_HTTP_METHOD_OVERRIDE"];
} else {
  $route = $_SERVER["REQUEST_METHOD"];
}

/**
 * Validate route
 */
if ( ! array_key_exists ( $route, $apiRoutes))
{
  // Trigger an error, invalid route requested!
  header ( $_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed");
  $allow = "";
  ksort ( $apiRoutes);
  foreach ( $apiRoutes as $route => $method)
  {
    $allow .= ", " . $route;
  }
  header ( "Allow: " . substr ( $allow, 2));
  exit ();
}
$inputRoute = $apiRoutes[$route];

/**
 * If route requested is OPTIONS, return available methods based on client permissions
 */
if ( $inputRoute == "help")
{
  /**
   * Get request variables
   */
  $path = $_SERVER["REQUEST_URI"];

  /**
   * Remove /api from start of path
   */
  if ( strpos ( $_in["api"]["baseuri"], "://") !== false)
  {
    $baseuri = substr ( $_in["api"]["baseuri"], strpos ( $_in["api"]["baseuri"], "/", strpos ( $_in["api"]["baseuri"], "://") + 3));
  } else {
    $baseuri = $_in["api"]["baseuri"];
  }
  if ( substr ( $path, 0, strlen ( $baseuri)) == $baseuri)
  {
    $path = substr ( $path, strlen ( $baseuri));
  }

  /**
   * If there's API version before path (v1, v2, etc..), set version variable
   * and remove it from path
   */
  if ( preg_match ( "/^\/v(\d+(\.\d+)*)\//", $path, $matches))
  {
    $api_version = substr ( $matches[1]);
    $path = substr ( $path, strpos ( $path, "/", 1));
  } else {
    if ( array_key_exists ( "HTTP_X_API_VERSION", $_SERVER) && preg_match ( "/^\d+(\.\d+)*$/", $_SERVER["HTTP_X_API_VERSION"], $matches))
    {
      $api_version = $matches[0];
    } else {
      $api_version = 1;
    }
  }

  /**
   * If GET method parameters are given at path URL, remove it
   */
  if ( strpos ( $path, "?") !== false)
  {
    $path = substr ( $path, 0, strpos ( $path, "?"));
  }

  /**
   * Process every path entry
   */
  $allow = array ( "HEAD");
  foreach ( $_api as $entrypath => $entry)
  {
    /**
     * Check if path match
     */
    $vars = match_path ( $entrypath, $path);
    if ( $vars === false)
    {
      continue;
    }

    /**
     * Check if path is allowed for non authenticated users, and if user is
     * logged in or not.
     */
    foreach ( $apiRoutes as $route)
    {
      if ( $entry[$route]["unauthenticated"] == false && ! $_session["Authenticated"])
      {
        continue;
      }

      /**
       * Check for a specific permission
       */
      if ( $entry[$route]["unauthenticated"] == false)
      {
        $authenticated = false;
        foreach ( $entry[$route]["permissions"] as $permission)
        {
          if ( in_array ( $permission, $_in["session"]["Permissions"]))
          {
            $authenticated = true;
          }
        }
        if ( ! $authenticated)
        {
          continue;
        }
      }

      /**
       * Add hook method
       */
      $allow[] = array_search ( $route, $apiRoutes);
    }
  }
  ksort ( $allow);

  /**
   * Check if any method was found, otherwise it's a not found
   */
  if ( sizeof ( $allow) == 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    header ( "Content-Length: 0");
    exit ();
  }

  /**
   * Return user allowed methods for requested path
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 204 No Content");
  header ( "Allow: " . implode ( ", ", $allow));
  header ( "Content-Length: 0");
  exit ();
}

/**
 * Process incoming data
 */
$apiRequestFormats = array ( "text/json" => "json", "application/json" => "json", "text/xml" => "xml", "application/xml" => "xml", "application/atom+xml" => "xml", "text/php" => "php", "text/x-php" => "php", "application/php" => "php", "application/x-php" => "php", "application/x-httpd-php" => "php", "application/x-httpd-php-source" => "php", "multipart/form-data" => "post", "text/html" => "get");
$inputFormat = "text/html";
$inputData = file_get_contents ( "php://input");
if ( array_key_exists ( "HTTP_CONTENT_TYPE", $_SERVER))
{
  $inputFormat = $_SERVER["HTTP_CONTENT_TYPE"];
  $inputCharset = mb_detect_encoding ( $inputData);
  if ( strpos ( $inputFormat, ";") !== false)
  {
    $tmp = trim ( substr ( $inputFormat, strpos ( $inputFormat, ";") + 1));
    $inputFormat = substr ( $inputFormat, 0, strpos ( $inputFormat, ";"));
    if ( stripos ( $tmp, "charset=") !== false)
    {
      $inputCharset = trim ( substr ( $tmp, stripos ( $tmp, "charset=") + 8));
    }
    if ( stripos ( $tmp, "boundary=") !== false)
    {
      $inputBoundary = trim ( substr ( $tmp, stripos ( $tmp, "boundary=") + 9));
    }
  }
} else {
  // No input format given, check if any data was post:
  if ( is_JSON ( $inputData))
  {
    $inputFormat = "application/json";
  }
  if ( is_XML ( $inputData))
  {
    $inputFormat = "application/xml";
  }
  $inputCharset = mb_detect_encoding ( $inputData);
}
if ( strtolower ( $inputCharset) != "utf-8" && strtolower ( $inputCharset) != "ascii")
{
  $inputData = mb_convert_encoding ( $inputData, "UTF-8");
}
if ( ! array_key_exists ( $inputFormat, $apiRequestFormats))
{
  // Trigger an error, invalid route requested!
  header ( $_SERVER["SERVER_PROTOCOL"] . " 415 Unsupported Media Type");
  exit ();
}
switch ( $apiRequestFormats[$inputFormat])
{
  case "json":
    $inputData = json_decode ( $inputData, true);
    break;
  case "xml":
    $inputData = json_decode ( json_encode ( simplexml_load_string ( $inputData, "SimpleXMLElement", LIBXML_NOCDATA)));
    break;
  case "php":
    $inputData = unserialize ( $inputData);
    break;
  case "post":
    $inputData = $_POST;
    break;
  case "get":
    if ( empty ( $inputData))
    {
      $inputData = $_GET;
    }
    break;
}

/**
 * Process response encoding
 */
$apiResponseFormats = array ( "text/json" => "json", "application/json" => "json", "text/javascript" => "javascript", "application/javascript" => "javascript", "text/xml" => "xml", "application/xml" => "xml", "application/atom+xml" => "xml", "text/php" => "php", "text/x-php" => "php", "application/php" => "php", "application/x-php" => "php", "application/x-httpd-php" => "php", "application/x-httpd-php-source" => "php", "text/html" => "text", "text/plain" => "text");
$outputFormat = "";
if ( array_key_exists ( "HTTP_ACCEPT", $_SERVER))
{
  foreach ( explode ( ",", $_SERVER["HTTP_ACCEPT"]) as $format)
  {
    if ( strpos ( ";", $format))
    {
      $format = substr ( $format, 0, strpos ( ";", $format) - 1);
    }
    if ( array_key_exists ( trim ( $format), $apiResponseFormats))
    {
      $outputFormat = trim ( $format);
      break;
    }
  }
}
if ( empty ( $outputFormat))
{
  if ( array_key_exists ( "HTTP_CONTENT_TYPE", $_SERVER))
  {
    $outputFormat = $_SERVER["HTTP_CONTENT_TYPE"];
  } else {
    $outputFormat = $inputFormat;
  }
}
if ( ! array_key_exists ( $outputFormat, $apiResponseFormats))
{
  // Trigger an error, invalid route requested!
  header ( $_SERVER["SERVER_PROTOCOL"] . " 406 Not Acceptable");
  exit ();
}

/**
 * Process request
 */
$outputData = api_call ( $_SERVER["REQUEST_URI"], ( $inputRoute == "test" ? "read" : $inputRoute), $inputData);

/**
 * Discard any buffered stray output (PHP warnings captured by error handler)
 */
if ( ob_get_level ())
{
  ob_end_clean ();
}

/**
 * In debug mode, inject captured PHP errors into the response for JSON output
 */
if ( array_key_exists ( "debug", $_in["general"]) && $_in["general"]["debug"] == true && ! empty ( $_in["php_errors"]))
{
  if ( is_array ( $outputData))
  {
    $outputData["_debug"] = array ( "php_errors" => $_in["php_errors"]);
  }
}

/**
 * Format output
 */
switch ( $apiResponseFormats[$outputFormat])
{
  case "json":
    $outputData = json_encode ( $outputData);
    break;
  case "javascript":
    $outputData = ( array_key_exists ( "callback", $_REQUEST) ? $_REQUEST["callback"] : "result") . " ( " . json_encode ( $outputData) . ")";
    break;
  case "xml":
    $outputData = array2xml ( $outputData, "response");
    break;
  case "php":
    $outputData = serialize ( $outputData);
    break;
  case "text":
    $outputData = print_r ( $outputData, true);
    break;
}

/**
 * If route is delete and has no output data, return 204 No Content
 */
if ( $inputRoute == "delete" && ( ! is_array ( $outputData) || sizeof ( $outputData) == 0))
{
  header ( $_SERVER["SERVER_PROTOCOL"] . " 204 No Content");
  exit ();
}

/**
 * Return data to client
 */
header ( "Content-Type: " . $outputFormat);
if ( $inputRoute != "test")
{
  echo $outputData;
}
?>
