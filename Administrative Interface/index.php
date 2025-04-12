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
 * VoIP Domain framework main file. This file contains all functions and
 * default variables to provide the basic interface like authentication,
 * session control, output buffer control, basic CRUD and more.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR | E_USER_ERROR);
ini_set ( "display_errors", 0);
// error_reporting ( E_ALL); ini_set ( "display_errors", 1);

/**
 * Include main configuration parser and functions
 */
require_once ( "includes/config.inc.php");

/**
 * Include interface module
 */
$_in["module"] = "";
require_once ( "modules/interface/language.php");
require_once ( "modules/interface/filter.php");
require_once ( "modules/interface/webui.php");

/**
 * Check if system is configured, otherwise show the installation page
 */
if ( $_in["mode"] == "install")
{
  echo framework_call ( "install_page_generate");
  exit ();
}

/**
 * Include modules cache (or create it if not found)
 */
if ( ! $_in["general"]["debug"] && $cache = json_decode ( @file_get_contents ( "modules/cache.json"), true))
{
  $_plugins["map"] = $cache["map"];
  $_plugins["hooks"] = $cache["hooks"];
  $_plugins["functions"] = $cache["functions"];
  $_paths = $cache["paths"];
  $_in["i18n"] = $cache["i18n"];
  $_filters = $cache["filters"];
  unset ( $cache);

  /**
   * Include modules functions files
   */
  foreach ( glob ( "modules/*/functions.php") as $module)
  {
    require_once ( $module);
  }
  foreach ( glob ( "modules/*/functions-webui.php") as $module)
  {
    require_once ( $module);
  }

  /**
   * Include modules config files
   */
  foreach ( glob ( "modules/*/config.php") as $module)
  {
    require_once ( $module);
  }

  /**
   * Include plugins config files
   */
  foreach ( glob ( "plugins/*/config.php") as $module)
  {
    require_once ( $module);
  }
} else {
  /**
   * Include all modules and filter files
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
    if ( is_readable ( $module . "/functions-webui.php"))
    {
      require_once ( $module . "/functions-webui.php");
    }
    if ( is_readable ( $module . "/language.php"))
    {
      require_once ( $module . "/language.php");
    }
    if ( is_readable ( $module . "/config.php"))
    {
      require_once ( $module . "/config.php");
    }
    if ( is_readable ( $module . "/filter.php"))
    {
      require_once ( $module . "/filter.php");
    }
    if ( is_readable ( $module . "/webui.php"))
    {
      require_once ( $module . "/webui.php");
    }
  }

  /**
   * Include all plugins and filter files
   */
  foreach ( glob ( "plugins/*") as $module)
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
    if ( is_readable ( $module . "/functions-webui.php"))
    {
      require_once ( $module . "/functions-webui.php");
    }
    if ( is_readable ( $module . "/language.php"))
    {
      require_once ( $module . "/language.php");
    }
    if ( is_readable ( $module . "/config.php"))
    {
      require_once ( $module . "/config.php");
    }
    if ( is_readable ( $module . "/filter.php"))
    {
      require_once ( $module . "/filter.php");
    }
    if ( is_readable ( $module . "/webui.php"))
    {
      require_once ( $module . "/webui.php");
    }
  }

  /**
   * Write cache file (only if not in debug mode)
   */
  if ( ! $_in["general"]["debug"])
  {
    @file_put_contents ( "modules/cache.json", json_encode ( array ( "map" => $_plugins["map"], "hooks" => $_plugins["hooks"], "functions" => $_plugins["functions"], "paths" => $_paths, "i18n" => $_in["i18n"], "filters" => $_filters)));
  }
}

/**
 * Set HTTP headers (avoid any kind of cache) if called from API
 */
if ( array_key_exists ( "HTTP_X_INFRAMEWORK", $_SERVER) && $_SERVER["HTTP_X_INFRAMEWORK"] == "page")
{
  // Set HTTP headers (avoid any kind of cache):
  header ( "Content-Type: application/json; charset=utf-8");              // Always return JSON content type.
  header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT");                     // Date in the past.
  header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s") . " GMT");      // Always modified.
  header ( "Cache-Control: no-cache, must-revalidate");                   // HTTP/1.1
  header ( "Pragma: no-cache");                                           // HTTP/1.0
} else {
  // Set HTTP headers (CSP) when providing HTML code:
  $_in["general"]["nonce"] = hash ( "sha256", uniqid ( "", true));
  header ( "Content-Security-Policy: default-src 'self' 'nonce-" . $_in["general"]["nonce"] . "';img-src 'self' blob: data:;style-src 'self' 'unsafe-inline';media-src 'self' blob: data:");                // Permit request of contents only from our self server

  // Set X-Frame-Options to deny processing of frames, iframes and objects:
  header ( "X-Frame-Options: DENY");

  // Set X-XSS-Protection to block XSS attacks:
  header ( "X-XSS-Protection: 1; mode=block");

  // Set X-Content-Type-Options to block mime type sniffing:
  header ( "X-Content-Type-Options: nosniff");

  // Set Strict-Transport-Security to ensure the content will be delivered through HTTPS:
  header ( "Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
}

/**
 * Check if user is authenticated
 */
$_in["session"] = array ();
if ( array_key_exists ( $_in["general"]["cookie"], $_COOKIE) && $result = @$_in["mysql"]["id"]->query ( "SELECT `Sessions`.`SID`, `Sessions`.`LastSeen`, `Users`.* FROM `Sessions` LEFT JOIN `Users` ON `Sessions`.`User` = `Users`.`ID` WHERE `Sessions`.`SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $_COOKIE[$_in["general"]["cookie"]]) . "'"))
{
  $_in["session"] = $result->fetch_assoc ();

  /**
   * Extend session variables
   */
  filters_call ( "session_extend");

  /**
   * Check if session has expired
   */
  if ( $_in["general"]["timeout"] > 0 && $_in["session"]["LastSeen"] + $_in["general"]["timeout"] < time ())
  {
    /**
     * Insert audit entry
     */
    audit ( "system", "logout", array ( "ID" => $_in["session"]["ID"], "Username" => $_in["session"]["Username"], "Reason" => "Timedout"));

    /**
     * Print response
     */
    if ( array_key_exists ( "HTTP_X_INFRAMEWORK", $_SERVER) && $_SERVER["HTTP_X_INFRAMEWORK"] == "page")
    {
      echo json_encode ( array ( "event" => "session_timeout"));
    } else {
      setcookie ( $_in["general"]["cookie"], null, -1, "/");
      echo framework_call ( "login_page_generate", array ( "message" => __ ( "Session expired.")));
    }
    exit ();
  }

  /**
   * Update session last seen
   */
  $_in["session"]["LastSeen"] = time ();
  @$_in["mysql"]["id"]->query ( "UPDATE `Sessions` SET `LastSeen` = '" . $_in["mysql"]["id"]->real_escape_string ( time ()) . "' WHERE `SID` = '" . $_in["mysql"]["id"]->real_escape_string ( $_in["session"]["SID"]) . "'");

  /**
   * Set system language if user has different language than system default
   */
  if ( ! empty ( $_in["session"]["Language"]) && array_key_exists ( $_in["session"]["Language"], $_in["languages"]))
  {
    $_in["general"]["language"] = $_in["session"]["Language"];
  }

  /**
   * Create user permissions variable
   */
  $_in["permissions"] = array_merge ( array ( "user" => true), json_decode ( $_in["session"]["Permissions"], true));
}

/**
 * If request was not made from API, show login page if unauthenticated, or show framework
 */
if ( ! array_key_exists ( "HTTP_X_INFRAMEWORK", $_SERVER) || $_SERVER["HTTP_X_INFRAMEWORK"] != "page")
{
  if ( array_key_exists ( "SID", $_in["session"]))
  {
    echo framework_call ( "framework_page_generate");
  } else {
    echo framework_call ( "login_page_generate");
  }
  exit ();
}

/**
 * Call requested system path
 */
$path = $_SERVER["REQUEST_URI"];

/**
 * If path has GET parameters, remove it
 */
if ( strpos ( $path, "?") !== false)
{
  $path = substr ( $path, 0, strpos ( $path, "?"));
}

/**
 * Process every path entry
 */
foreach ( $_paths as $entrypath => $entry)
{
  /**
   * Check if path match
   */
  $vars = match_path ( $entrypath, $path, array ());
  if ( $vars === false)
  {
    continue;
  }

  /**
   * Check if path is allowed for non authenticated users, and if user is
   * logged in or not.
   */
  if ( $entry["options"]["unauthenticated"] == false && ! array_key_exists ( "SID", $_in["session"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
    exit ();
  }

  /**
   * Check for a specific permission
   */
  if ( $entry["options"]["unauthenticated"] == false && sizeof ( $entry["options"]["permissions"]) != 0)
  {
    $allowed = false;
    foreach ( $entry["options"]["permissions"] as $permission)
    {
      if ( array_key_exists ( $permission, $_in["permissions"]) && $_in["permissions"][$permission] != false)
      {
        $allowed = true;
      }
    }
    if ( ! $allowed)
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
      exit ();
    }
  }

  /**
   * Call framework hooks for this path
   */
  $hook = match_hook_entry ( $entry["hook"]);
  $content = framework_call ( $hook, array_merge ( $entry["options"]["parameters"], $vars));

  /**
   * Format result data
   */
  $return = array ();
  $return["index"] = $entrypath;
  $return["hook"] = $hook;
  $return["type"] = $_in["page"]["type"] ? $_in["page"]["type"] : "page";
  $return["content"] = $content;
  $return["title"] = $_in["page"]["title"];
  $return["subtitle"] = $_in["page"]["subtitle"];
  $return["breadcrumb"] = $_in["page"]["path"];
  $return["css"] = $_in["page"]["css"];
  $return["inlinecss"] = $_in["page"]["inlinecss"];
  $return["js"] = $_in["page"]["js"];
  $return["inlinejs"] = $_in["page"]["inlinejs"];
  if ( ! empty ( $_in["page"]["hashevent"]["id"]) && ! empty ( $_in["page"]["hashevent"]["event"]))
  {
    $return["hashevent"] = array ( "id" => $_in["page"]["hashevent"]["id"], "event" => $_in["page"]["hashevent"]["event"]);
  }
  if ( ! empty ( $_in["page"]["startevent"]["id"]) && ! empty ( $_in["page"]["startevent"]["event"]))
  {
    $return["startevent"] = array ( "id" => $_in["page"]["startevent"]["id"], "event" => $_in["page"]["startevent"]["event"], "data" => $_in["page"]["startevent"]["data"]);
  }

  /**
   * Return data to user and end execution
   */
  header ( "Content-Type: application/json");
  echo json_encode ( $return, true);
  exit ();
}

/**
 * If reached here, there's no path registered that match que requested page, so, return error
 */
header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
exit ();
?>
