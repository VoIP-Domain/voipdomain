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
 * VoIP Domain framework configuration load file. This file will parse all the
 * framework configuration, check for mandatory configuration, set defaults if
 * needed and connect to the database server. This file also contains the
 * framework functions needed to control JavaScript and CSS dependencies, page
 * informations, and more.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Load miscelaneous function library
 */
require_once ( dirname ( __FILE__) . "/functions.inc.php");

/**
 * Load modular plugin library
 */
require_once ( dirname ( __FILE__) . "/plugins.inc.php");

/**
 * Parse configuration file. You should put your configuration file OUTSIDE
 * the web server files path, or you must block access to this file at the
 * web server configuration. Your configuration would contain passwords and
 * other sensitive configurations.
 */
$_in = parse_ini_file ( "/etc/voipdomain/voipdomain.conf", true);

/**
 * Initialize page variables
 */
$_in["page"]["title"] = "";
$_in["page"]["subtitle"] = "";
$_in["page"]["path"] = array ();
$_in["page"]["i18n"] = array ();

/**
 * Include all modules configuration files
 */
foreach ( glob ( dirname ( __FILE__) . "/../modules/*/config.php") as $filename)
{
  require_once ( $filename);
}

/**
 * Check for mandatory basic configurations (if didn't exist, set default)
 */
if ( ! array_key_exists ( "version", $_in["general"]))
{
  $_in["version"] = "1.0";
} else {
  $_in["version"] = $_in["general"]["version"];
  unset ( $_in["general"]["version"]);
}
if ( ! array_key_exists ( "charset", $_in["general"]))
{
  $_in["general"]["charset"] = "UTF-8";
}
if ( ! array_key_exists ( "language", $_in["general"]))
{
  $_in["general"]["language"] = "en_US";
}
if ( ! array_key_exists ( "cookie", $_in["general"]))
{
  $_in["general"]["cookie"] = "vd";
}
if ( ! array_key_exists ( "timeout", $_in["general"]))
{
  $_in["general"]["timeout"] = 3600;
}
if ( ! array_key_exists ( "title", $_in["general"]))
{
  $_in["general"]["title"] = "VoIP Domain";
}

/**
 * Include basic available languages
 */
$_in["languages"] = array ( "en_US" => "English (United States)", "pt_BR" => "Português (Brasil)");

/**
 * Configure locale and encoding
 */
mb_internal_encoding ( $_in["general"]["charset"]);
setlocale ( LC_ALL, $_in["general"]["language"] . "." . $_in["general"]["charset"]);

/**
 * Check if variables was changed by magic_quotes_gpc and correct it if needed
 */
if ( ini_get ( "magic_quotes_gpc"))
{
  foreach ( $_GET as $var => $val)
  {
    $_GET[$var] = stripslashes ( $val);
  }
  foreach ( $_POST as $var => $val)
  {
    $_POST[$var] = stripslashes ( $val);
  }
  foreach ( $_REQUEST as $var => $val)
  {
    $_REQUEST[$var] = stripslashes ( $val);
  }
  foreach ( $_COOKIE as $var => $val)
  {
    $_COOKIE[$var] = stripslashes ( $val);
  }
}

/**
 * Validate MySQL configuration section
 */
if ( ! is_array ( $_in["mysql"]))
{
  header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
  if ( array_key_exists ( "HTTP_X_INFRAMEWORK", $_SERVER) && $_in["general"]["debug"] === true)
  {
    echo json_encode ( array ( "result" => false, "message" => "Cannot find the \"mysql\" section into configuration file.", "error" => 503));
  }
  exit ( 1);
}

/**
 * Connect to database server
 */
$_in["mysql"]["id"] = @new mysqli ( $_in["mysql"]["hostname"] . ( ! empty ( $_in["mysql"]["port"]) ? ":" . $_in["mysql"]["port"] : ""), $_in["mysql"]["username"], $_in["mysql"]["password"], $_in["mysql"]["database"]);
if ( $_in["mysql"]["id"]->connect_errno)
{
  header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
  if ( array_key_exists ( "HTTP_X_INFRAMEWORK", $_SERVER) && $_in["general"]["debug"] === true)
  {
    echo json_encode ( array ( "result" => false, "message" => "Cannot connect to MySQL server.", "error" => 503));
  }
  exit ();
}

/**
 * Clear authentication variables
 */
$_in["session"] = array ();
$_in["session"]["type"] = "unauthenticated";

/**
 * Default template javascript codes to load
 */
$_in["page"]["js"] = array ();
$_in["page"]["inlinejs"] = "";

/**
 * Default template css style sheet files to load
 */
$_in["page"]["css"] = array ();
$_in["page"]["inlinecss"] = "";

/**
 * Function to clear all CSS from framework.
 *
 * @global array $_in Framework global configuration variable
 * @return void
 */
function sys_clear_css ()
{
  global $_in;

  $_in["page"]["css"] = array ();
  $_in["page"]["inlinecss"] = "";
}

/**
 * Function to add new CSS file or code to the framework.
 *
 * @global array $_in Framework global configuration variable
 * @param array $script Array containing the "src" variable pointing the file
 *                      path or CSS code, "name" is optional, and refers to the
 *                      script name (if not provided, will be the name of the
 *                      script, without path and suffix) and "dep" as an array
 *                      listing the script dependencies (default is no deps)
 * @return void
 */
function sys_addcss ( $script)
{
  global $_in;

  /**
   * Check if provided variable is a string, and transform to array
   */
  if ( is_string ( $script))
  {
    $script = array ( "src" => $script);
  }

  /**
   * Check if passed script is a file and it's readable
   */
  if ( strpos ( $script["src"], "\n") !== false || substr ( $script["src"], strlen ( $script["src"]) - 4) != ".css")
  {
    $_in["page"]["inlinecss"] .= $script["src"];
  } else {
    if ( substr ( $script["src"], strlen ( $script["src"]) - 8) == ".min.css")
    {
      $script["src"] = substr ( $script["src"], 0, strlen ( $script["src"]) - 8) . ".css";
    }
    foreach ( $_in["page"]["css"] as $css)
    {
      if ( $css["src"] == $script["src"])
      {
        return;
      }
    }
    if ( ! array_key_exists ( "name", $script))
    {
      $script["name"] = basename ( $script["src"], ".css");
    }
    if ( ! array_key_exists ( "dep", $script))
    {
      $script["dep"] = array ();
    }
    $script["src"] = substr ( $script["src"], 0, strrpos ( $script["src"], ".css")) . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css";
    $_in["page"]["css"][] = $script;
  }
}

/**
 * Function to clear all JavaScript from framework.
 *
 * @global array $_in Framework global configuration variable
 * @return void
 */
function sys_clear_js ()
{
  global $_in;

  $_in["page"]["js"] = array ();
  $_in["page"]["inlinejs"] = "";
}

/**
 * Function to add new JavaScript file or code to the framework.
 *
 * @global array $_in Framework global configuration variable
 * @param array $script Array containing the "src" variable pointing the file
 *                      path or JavaScript code, "name" is optional, and refers
 *                      to the script name (if not provided, will be the name of
 *                      the script, without path and suffix) and "dep" as an
 *                      array listing the script dependencies (default is no
 *                      deps)
 * @return void
 */
function sys_addjs ( $script)
{
  global $_in;

  /**
   * Check if provided variable is a string, and transform to array
   */
  if ( is_string ( $script))
  {
    $script = array ( "src" => $script);
  }

  /**
   * Check if passed script is a file and it's readable
   */
  if ( strpos ( $script["src"], "\n") !== false || substr ( $script["src"], strlen ( $script["src"]) - 3) != ".js")
  {
    $_in["page"]["inlinejs"] .= $script["src"];
  } else {
    if ( substr ( $script["src"], strlen ( $script["src"]) - 7) == ".min.js")
    {
      $script["src"] = substr ( $script["src"], 0, strlen ( $script["src"]) - 7) . ".js";
    }
    foreach ( $_in["page"]["js"] as $js)
    {
      if ( $js["src"] == $script["src"])
      {
        return;
      }
    }
    if ( ! array_key_exists ( "name", $script))
    {
      $script["name"] = basename ( $script["src"], ".js");
    }
    if ( ! array_key_exists ( "dep", $script))
    {
      $script["dep"] = array ();
    }
    $script["src"] = substr ( $script["src"], 0, strrpos ( $script["src"], ".js")) . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js";
    $_in["page"]["js"][] = $script;
  }
}

/**
 * Function to add text internacionalization string.
 *
 * @global array $_in Framework global configuration variable
 * @param string $original The original string (english)
 * @param string $translation The current language translation. Could be a string
 *                            or a JavaScript function that return's the desired
 *                            string.
 * @return void
 */
function sys_addtext ( $original, $translation)
{
  global $_in;

  $_in["page"]["i18n"][$original] = $translation;
}

/**
 * Function to set framework page title.
 *
 * @global array $_in Framework global configuration variable
 * @param string $title New page title
 * @return void
 */
function sys_set_title ( $title)
{
  global $_in;

  $_in["page"]["title"] = $title;
}

/**
 * Function to set framework page subtitle.
 *
 * @global array $_in Framework global configuration variable
 * @param string $subtitle New page subtitle
 * @return void
 */
function sys_set_subtitle ( $subtitle)
{
  global $_in;

  $_in["page"]["subtitle"] = $subtitle;
}

/**
 * Function to set framework page path.
 *
 * @global array $_in Framework global configuration variable
 * @param array $path New page path
 * @return void
 */
function sys_set_path ( $path)
{
  global $_in;

  $_in["page"]["path"] = $path;
}

/**
 * Initialize framework API call variable
 */
$_api = array ();
$_api["permissions"] = array ();

/**
 * Initialize framework path variable
 */
$_paths = array ();

/**
 * Initialize framework filter variable
 */
$_filters = array ();

/**
 * Function to add filter call to function's. This basically add a chain of
 * function's that filter an information. This method is used internally into the
 * framework to do some operation that could be extended by plugins.
 *
 * @global array $_filters Framework global filter configuration variable
 * @global array $_in Framework global configuration variable
 * @param string $filter Filter name
 * @param string $function Function to be fired
 * @return boolean Return if filter has been added or not
 */
function framework_add_filter ( $filter, $function)
{
  global $_filters, $_in;

  /**
   * Add filter if not exist
   */
  if ( ! array_key_exists ( $filter, $_filters))
  {
    $_filters[$filter] = array ();
  }

  /**
   * If function already registered, return false
   */
  if ( in_array ( $function, $_filters[$filter]))
  {
    return false;
  }

  /**
   * Add function to the filter variable
   */
  $_filters[$filter][$_in["module"]] = $function;

  return true;
}

/**
 * Check for a filter and call the assigned functions.
 *
 * @global array $_filters Framework global filter configuration variable
 * @global array $_in Framework global configuration variable
 * @param string $filter Filter to be processed
 * @param array $parameters[optional] Optional parameters to be sent to each
 *                                    filter function
 * @return boolean|mixed Return false if no function has fired, otherwise return
 *                       the function call result
 */
function filters_call ( $filter, $parameters = array ())
{
  global $_filters, $_in;

  /**
   * If there's no filter, return false
   */
  if ( ! array_key_exists ( $filter, $_filters))
  {
    return false;
  }

  /**
   * Prepare parameters
   */
  if ( ! is_array ( $parameters))
  {
    $parameters = array ();
  }
  $parameters["called_filter"] = $filter;

  /**
   * Process every filter entry
   */
  $oldmodule = $_in["module"];
  $buffer = array ();
  foreach ( $_filters[$filter] as $module => $function)
  {
    if ( ! function_exists ( $function))
    {
      framework_error ( "IntelliNews Framework: Registered function \"" . $function . "\" doesn't exist at filter \"" . $filter . "\"");
      continue;
    }
    $_in["module"] = $module;
    $buffer = call_user_func ( $function, $buffer, $parameters);
  }
  $_in["module"] = $oldmodule;
  return $buffer;
}

/**
 * Function to add path call to hook's. This basically check for framework call
 * path (like friendly URL call's to your system) using preg_match, check for
 * permissions and call hook's if needed. Path use "/" (slash) as separator.
 *
 * This function accept's options, that can be:
 *   boolean "unauthenticated"   -> Allow or disallow execution of the path for
 *                                  unauthenticated users (use for public calls)
 *   array "parameters"          -> Parameters to be passed when called the hook
 *   string|array "permissions"  -> Check if user has at least one of the
 *                                  provided permission. You could use a string,
 *                                  a comma separated list or an array.
 *
 * @global array $_paths Framework global path configuration variable
 * @param string $path Path to the hook
 * @param string|array $hook Hook to be fired, or array containing existing
 *                           request (POST or GET) variable as key and hook as
 *                           value
 * @param array[optional] $options Many options to be assigned to this path
 * @return boolean Return if path has been added or not
 */
function framework_add_path ( $path, $hook, $options = array ())
{
  global $_paths;

  /**
   * Check if path start with slash
   */
  if ( substr ( $path, 0, 1) != "/")
  {
    $path = "/" . $path;
  }

  /**
   * If hook already exist, return false
   */
  if ( array_key_exists ( $path, $_paths))
  {
    return false;
  }

  /**
   * Add hook to the paths variable
   */
  $permissions = ( array_key_exists ( "permissions", $options) && ! empty ( $options["permissions"]) ? $options["permissions"] : array ());
  $_paths[$path] = array (
    "hook" => ( is_string ( $hook) ? array ( "*" => $hook) : $hook),
    "options" => array (
      "unauthenticated" => ( array_key_exists ( "unauthenticated", $options) && $options["unauthenticated"] === true ? true : false),
      "parameters" => ( array_key_exists ( "parameters", $options) && is_array ( $options["parameters"]) ? $options["parameters"] : array ()),
      "permissions" => ( is_string ( $permissions) ? explode ( ",", $permissions) : $permissions)
    )
  );

  return true;
}

/**
 * Function to check the requested variables (POST and GET) for matched hook
 * configuration. This is used to fire the correct event.
 *
 * @param array $hook Array containing the hooks
 * @return string The best match hook
 */
function match_hook_entry ( $hook)
{
  $closestkey = "*";
  foreach ( array_merge ( $_POST, $_GET) as $key => $value)
  {
    if ( array_key_exists ( $key, $hook))
    {
      $closestkey = $key;
      break;
    }
  }
  if ( ! array_key_exists ( $closestkey, $hook))
  {
    $closestkey = key ( $hook);
  }
  return $hook[$closestkey];
}

/**
 * Function to add new permission to API system.
 *
 * @global array $_api Framework global API configuration variable
 * @param string $permission The token key to the permission
 * @param string $description The permission description
 * @return void
 */
function framework_add_permission ( $permission, $description)
{
  global $_api;

  /**
   * Check for reserved permissions
   */
  if ( in_array ( $permission, array ( "user", "token", "server", "administrator", "auditor")))
  {
    trigger_error ( __FUNCTION__ . "(): Ignoring new permission token using reserved value: " . $permission, E_USER_WARNING);
    return false;
  }

  /**
   * Just add the permission and the description to API variable
   */
  $_api["permissions"][$permission] = $description;

  /**
   * Return true
   */
  return true;
}

/**
 * Function to add API call to hook's. This basically check for framework API
 * call path (like friendly URL call's to your system) using preg_match, check
 * for route, permissions and call hook's if needed. Path use "/" (slash) as
 * separator. The path can't have the version, it's managed by API internally.
 * The route parameter must be an array with every accepted route to the path,
 * the corresponding hook to the route path and the options if needed. The
 * options could be:
 *   boolean "unauthenticated"   -> Allow or disallow execution of the path
 *                                  without any permission check (public access
 *                                  to the path)
 *   array "parameters"          -> Parameters to be passed when called the hook
 *   string|array "permissions"  -> Check if user has at least one of the
 *                                  provided permission. Special permissions are
 *                                  "user", to allow any interface user request
 *                                  and "token", to allow any request made using
 *                                  tokens. Don't confuse "token" and "user"
 *                                  with specific call permission. Any listed
 *                                  permission granted to user or token will be
 *                                  accepted. You can use a single permission, a
 *                                  comma separated list or an array list.
 *
 * @global array $_api Framework global API configuration variable
 * @param string $path Path to the API call
 * @param string $route The route for this call
 * @param string $hook The hook to be fired when API path and route called
 * @param array[optional] $options Many options to be assigned to this call
 * @return boolean Return if routes has been added or not
 */
function framework_add_api_call ( $path, $route, $hook, $options = array ())
{
  global $_api;

  /**
   * Check if required route is valid
   */
  $route = strtolower ( $route);
  switch ( $route)
  {
    case "create":
    case "read":
    case "update":
    case "delete":
    case "modify":
    case "help":
    case "test":
    case "trace":
      break;
    default:
      return false;
      break;
  }

  /**
   * Check if path start with slash
   */
  if ( substr ( $path, 0, 1) != "/")
  {
    $path = "/" . $path;
  }

  /**
   * If hook already exist with the requested route, return false
   */
  if ( ! array_key_exists ( $path, $_api))
  {
    $_api[$path] = array ();
  } else {
    if ( array_key_exists ( $route, $_api[$path]))
    {
      return false;
    }
  }

  /**
   * Filter options
   */
  $filtered = array ();
  $filtered["hook"] = $hook;
  $filtered["unauthenticated"] = ( array_key_exists ( "unauthenticated", $options) && $options["unauthenticated"] === true ? true : false);
  $filtered["parameters"] = ( array_key_exists ( "parameters", $options) && is_array ( $options["parameters"]) ? $options["parameters"] : array ());
  $filtered["permissions"] = ( array_key_exists ( "permissions", $options) && ! empty ( $options["permissions"]) ? $options["permissions"] : array ());
  if ( is_string ( $filtered["permissions"]))
  {
    $filtered["permissions"] = explode ( ",", $filtered["permissions"]);
  }

  /**
   * Add hook to the api variable
   */
  $_api[$path][$route] = $filtered;

  return true;
}

/**
 * Check for a path and call the assigned hook if needed.
 *
 * @global array $_api Framework global API configuration variable
 * @global array $_in Framework global configuration variable
 * @param string $path Path to be processed
 * @return boolean|string Return false if no hook has fired, otherwise return the
 *                        hook call result
 */
function api_call ( $path, $route, $parameters = array ())
{
  global $_api, $_in;

  /**
   * If path has GET parameters, remove it
   */
  if ( strpos ( $path, "?") !== false)
  {
    $path = substr ( $path, 0, strpos ( $path, "?"));
  }

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
  if ( preg_match ( "/^\/v\d\//", $path, $matches))
  {
    $apiversion = (int) substr ( $matches[0], 2);
    $path = substr ( $path, strpos ( $path, "/", 1));
  } else {
    $apiversion = 1;
  }

  /**
   * Process every path entry
   */
  foreach ( $_api as $entrypath => $entry)
  {
    /**
     * Check if path match
     */
    $vars = matchPath ( $entrypath, $path);
    if ( $vars === false)
    {
      continue;
    }

    /**
     * Check if route is available
     */
    if ( ! array_key_exists ( $route, $entry))
    {
      continue;
    }

    /**
     * Check if path is allowed for non authenticated users, and if user is
     * logged in or not.
     */
    if ( $entry[$route]["unauthenticated"] == false && ! array_key_exists ( "token", $_in) && ! array_key_exists ( "session", $_in) && ! array_key_exists ( "server", $_in))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 401 Unauthorized");
      exit ();
    }

    /**
     * Check for a specific permission
     */
    if ( $entry[$route]["unauthenticated"] == false)
    {
      $authenticated = false;
      foreach ( $entry[$route]["permissions"] as $permission)
      {
        if ( array_key_exists ( $permission, $_in["permissions"]) && $_in["permissions"][$permission] != false)
        {
          $authenticated = true;
        }
      }
      if ( ! $authenticated)
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
        exit ();
      }
    }

    /**
     * Call hook
     */
    return framework_call ( $entry[$route]["hook"], array_merge ( (array) $vars, (array) $entry[$route]["parameters"], (array) $parameters));
  }

  /**
   * If reached this point, there's no path match.
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit ();
}

/**
 * Function to check if a path matches a system path with possible variables use
 * (:var) in the path. If match, return an array with variables and values.
 *
 * @param $match string System path to check for needle.
 * @param $needle string The user requested path.
 * @return boolean|array False if not match, or array containing key/value pair.
 */
function matchPath ( $match, $needle)
{
  $matches = array ();
  while ( $match != "")
  {
    $search = substr ( $match, 0, 1);
    $match = substr ( $match, 1);
    if ( $search == ":")
    {
      $key = "";
      $value = "";
      // First, get value:
      if ( strpos ( $needle, "/") === false)
      {
        $value = $needle;
        $needle = "";
      } else {
        $value = substr ( $needle, 0, strpos ( $needle, "/"));
        $needle = substr ( $needle, strpos ( $needle, "/") + 1);
      }
      // Now, get key:
      if ( strpos ( $match, "/") === false)
      {
        $key = $match;
        $match = "";
      } else {
        $key = substr ( $match, 0, strpos ( $match, "/"));
        $match = substr ( $match, strpos ( $match, "/") + 1);
      }
      $matches[$key] = $value;
    } else {
      if ( $search != substr ( $needle, 0, 1))
      {
        return false;
      }
      $needle = substr ( $needle, 1);
    }
  }

  if ( strlen ( $needle) != 0)
  {
    return false;
  }

  return $matches;
}

/**
 * Check for table last update time, set response header and check for request
 * headers limitations.
 *
 * @global array $_in Framework global configuration variable
 * @param string|array $table Table or list of tables to check
 * @return int Unix timestamp of lastest modification
 */
function check_table_modification ( $table)
{
  global $_in;

  /**
   * Create where clause
   */
  $where = "";
  if ( is_array ( $table))
  {
    foreach ( $table as $entry)
    {
      $where .= " OR `Table` = '" . $_in["mysql"]["id"]->real_escape_string ( $entry) . "'";
    }
    $where = substr ( $where, 4) . " ORDER BY `Updated` DESC LIMIT 0,1";
  } else {
    $where = "`Table` = '" . $_in["mysql"]["id"]->real_escape_string ( $table) . "'";
  }

  /**
   * Get table update time
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Updated` FROM `Cache` WHERE " . $where))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 1)
  {
    $last = strtotime ( $result->fetch_object ()->Updated);
  } else {
    $last = 0;
  }

  /**
   * Set Last-Modified HTTP header
   */
  header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s T", $last));

  /**
   * Check if If-Modified-Since HTTP header was provided
   */
  if ( array_key_exists ( "HTTP_IF_MODIFIED_SINCE", $_SERVER))
  {
    if ( $last <= strtotime ( $_SERVER["HTTP_IF_MODIFIED_SINCE"]))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 304 Not Modified");
      exit ();
    }
  }

  /**
   * Check if If-Unmodified-Since HTTP header was provided
   */
  if ( array_key_exists ( "HTTP_IF_UNMODIFIED_SINCE", $_SERVER))
  {
    if ( $last > strtotime ( $_SERVER["HTTP_IF_UNMODIFIED_SINCE"]))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 412 Precondition Failed");
      exit ();
    }
  }

  /**
   * Return last modified Unix timestamp
   */
  return $last;
}
?>
