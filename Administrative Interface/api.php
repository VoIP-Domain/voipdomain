<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR);
ini_set ( "display_errors", "false");
// error_reporting ( E_ALL); ini_set ( "display_errors", "true");

/**
 * Include main configuration parser and functions
 */
require_once ( "includes/config.inc.php");

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
  if ( $module == "modules/interface")
  {
    continue;
  }
  $_in["module"] = basename ( $module);
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

/**
 * First, check if user is authenticated or token is valid
 */
if ( array_key_exists ( "HTTP_X_VD_TOKEN", $_SERVER))
{
  // Get token from database:
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tokens` WHERE `Token` = '" . $_in["mysql"]["id"]->real_escape_string ( $_SERVER["HTTP_X_VD_TOKEN"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 401 Unauthorized");
    exit ();
  }
  $token = $result->fetch_assoc ();

  // Check if client IP is allowed:
  $networks = json_decode ( $token["Networks"], true);
  $allowed = false;
  foreach ( $networks as $cidr)
  {
    if ( cidrMatch ( $_SERVER["REMOTE_ADDR"], $cidr))
    {
      $allowed = true;
    }
  }
  if ( ! $allowed)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
    exit ();
  }

  /**
   * Create token permissions variable
   */
  $_in["token"] = $token;
  $_in["permissions"] = array_merge ( array ( "token"), explode ( ",", $_in["token"]["Permissions"]));
}
if ( array_key_exists ( "HTTP_X_VD_SID", $_SERVER) && array_key_exists ( "HTTP_X_VD_SPWD", $_SERVER))
{
  // Get server from database:
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = '" . $_in["mysql"]["id"]->real_escape_string ( (int) $_SERVER["HTTP_X_VD_SID"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 401 Unauthorized");
    exit ();
  }
  $server = $result->fetch_assoc ();

  // Check for server password
  if ( $_SERVER["HTTP_X_VD_SPWD"] != hash ( "sha256", $server["Password"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
    exit ();
  }

  /**
   * Create server permissions variable
   */
  $_in["server"] = $server;
  $_in["permissions"] = array ( "server");
}
if ( ! array_key_exists ( "HTTP_X_VD_TOKEN", $_SERVER) && ! array_key_exists ( "HTTP_X_VD_SID", $_SERVER))
{
  $_in["session"] = array ();
  if ( array_key_exists ( $_in["general"]["cookie"], $_COOKIE) && $result = @$_in["mysql"]["id"]->query ( "SELECT `Sessions`.`SID`, `Sessions`.`LastSeen`, `Users`.* FROM `Sessions`, `Users` WHERE `Sessions`.`SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $_COOKIE[$_in["general"]["cookie"]]) . "' AND `Sessions`.`User` = `Users`.`ID`"))
  {
    $_in["session"] = $result->fetch_assoc ();
    /**
     * Check if session has expired
     */
    if ( $_in["general"]["timeout"] > 0 && $_in["session"]["LastSeen"] + $_in["general"]["timeout"] < time ())
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 401 Unauthorized");
      echo json_encode ( array ( "event" => "session_timeout"));
      exit ();
    }

    /**
     * Update session last seen
     */
    $_in["session"]["LastSeen"] = time ();
    @$_in["mysql"]["id"]->query ( "UPDATE `Sessions` SET `LastSeen` = '" . $_in["mysql"]["id"]->real_escape_string ( time ()) . "' WHERE `SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $_in["session"]["SID"]) . "'");

    /**
     * Create user permissions variable
     */
    $_in["permissions"] = array_merge ( array ( "user" => true), json_decode ( $_in["session"]["Permissions"], true));
  }
}

/**
 * Process route request.
 */

// Respect Google recomendation for X-HTTP-Method-Override header:
if ( array_key_exists ( "HTTP_X_HTTP_METHOD_OVERRIDE", $_SERVER))
{
  $route = $_SERVER["HTTP_X_HTTP_METHOD_OVERRIDE"];
} else {
  $route = $_SERVER["REQUEST_METHOD"];
}

// Validate route:
switch ( $route)
{
  case "POST":
    $route = "create";
    break;
  case "GET":
    $route = "read";
    break;
  case "PUT":
    $route = "update";
    break;
  case "DELETE":
    $route = "delete";
    break;
  case "PATCH":
    $route = "modify";
    break;
  case "OPTIONS":
    $route = "help";
    break;
  case "HEAD":
    $route = "test";
    break;
  case "TRACE":
    $route = "trace";
    break;
  default:
    // Trigger an error, invalid route requested!
    header ( $_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed");
    header ( "Allow: GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD, TRACE");
    exit ();
    break;
}

/**
 * Process incoming data
 */
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
  if ( isJSON ( $inputData))
  {
    $inputFormat = "application/json";
  }
  if ( isXML ( $inputData))
  {
    $inputFormat = "application/xml";
  }
  $inputCharset = mb_detect_encoding ( $inputData);
}
if ( strtolower ( $inputCharset) != "utf-8" && strtolower ( $inputCharset) != "ascii")
{
  $inputData = mb_convert_encoding ( $inputData, "UTF-8");
}
switch ( $inputFormat)
{
  case "text/json":
  case "application/json":
    $inputData = json_decode ( $inputData, true);
    break;
  case "text/xml":
  case "application/xml":
  case "application/atom+xml":
    $inputData = json_decode ( json_encode ( simplexml_load_string ( $inputData, "SimpleXMLElement", LIBXML_NOCDATA)));
    break;
  case "text/php":
  case "text/x-php":
  case "application/php":
  case "application/x-php":
  case "application/x-httpd-php":
  case "application/x-httpd-php-source":
    $inputData = unserialize ( $inputData);
    break;
  case "multipart/form-data":
    $inputData = $_POST;
    break;
  default:
    // Trigger an error, invalid route requested!
    header ( $_SERVER["SERVER_PROTOCOL"] . " 415 Unsupported Media Type");
    exit ();
    break;
}

/**
 * Process request
 */
$outputData = api_call ( $_SERVER["REQUEST_URI"], $route, $inputData);

/**
 * Process response encoding
 */
if ( array_key_exists ( "HTTP_ACCEPT", $_SERVER))
{
  $outputFormat = $_SERVER["HTTP_ACCEPT"];
} else {
  if ( array_key_exists ( "HTTP_CONTENT_TYPE", $_SERVER))
  {
    $outputFormat = $_SERVER["HTTP_CONTENT_TYPE"];
  } else {
    $outputFormat = $inputFormat;
  }
}
switch ( $outputFormat)
{
  case "text/json":
  case "application/json":
    $outputData = json_encode ( $outputData);
    break;
  case "text/javascript":
  case "application/javascript":
    $outputData = ( array_key_exists ( "callback", $_REQUEST) ? $_REQUEST["callback"] : "result") . " ( " . json_encode ( $outputData) . ")";
    break;
  case "text/xml":
  case "application/xml":
    $outputData = array2xml ( $outputData, "response");
    break;
  case "text/php":
  case "text/x-php":
  case "application/php":
  case "application/x-php":
  case "application/x-httpd-php":
  case "application/x-httpd-php-source":
    $outputData = serialize ( $outputData);
    break;
  default:
    // Trigger an error, invalid route requested!
    header ( $_SERVER["SERVER_PROTOCOL"] . " 415 Unsupported Media Type");
    exit ();
    break;
}

/**
 * Return data to client
 */
header ( "Content-Type: " . $outputFormat);
echo $outputData;

/**
 * Essentially, a route is made of:
 *
 * 1. A route controller that corresponds to a HTTP header method:
 *
 *    onCreate()   ->   POST          |        onModify()   ->   PATCH
 *    onRead()     ->   GET           |        onHelp()     ->   OPTIONS
 *    onUpdate()   ->   PUT           |        onTest()     ->   HEAD
 *    onDelete()   ->   DELETE        |        onTrace()    ->   TRACE
 *
 * 2. A route path corresponding to a Request-URI.
 *    It may represent a specific and static resource entity, such as:
 *
 *    /search/france/paris
 *
 *    It may also be dynamic, and may include one or many variables indicated by a colon :, such as:
 *
 *    /search/:country/:city
 */
?>
