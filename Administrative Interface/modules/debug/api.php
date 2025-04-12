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
 * VoIP Domain debug module API. This is an internal debug API, and should NEVER
 * be loaded at production environment.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Debug
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to ping/pong
 */
framework_add_hook (
  "debug_ping",
  "debug_ping",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "A string containing a pong."),
        "schema" => array (
          "type" => "string",
          "example" => __ ( "Pong")
        )
      )
    )
  )
);
framework_add_permission ( "debug_ping", __ ( "Debug ping/pong hook"));
framework_add_api_call (
  "/debug/ping",
  "Read",
  "debug_ping",
  array (
    "permissions" => array ( "user", "debug_ping"),
    "title" => __ ( "Debug ping/pong"),
    "description" => __ ( "Debug ping/pong messages.")
  )
);

/**
 * Function to return pong string.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function debug_ping ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "debug_ping_start"))
  {
    $parameters = framework_call ( "debug_ping_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "debug_ping_validate"))
  {
    $data = framework_call ( "debug_ping_validate", $parameters);
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
  if ( framework_has_hook ( "debug_ping_sanitize"))
  {
    $parameters = framework_call ( "debug_ping_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "debug_ping_pre"))
  {
    $parameters = framework_call ( "debug_ping_pre", $parameters, false, $parameters);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "debug_ping_post"))
  {
    $data = framework_call ( "debug_ping_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "debug_ping_finish"))
  {
    framework_call ( "debug_ping_finish", $parameters);
  }

  /**
   * Return data
   */
  return "Pong";
}

/**
 * API call to fetch blocks listing
 */
framework_add_hook (
  "debug_call_hook",
  "debug_call_hook",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the requested hook."),
        "schema" => array (
          "type" => "object",
          "additionalProperties" => array (
            "type" => "string"
          )
        )
      )
    )
  )
);
framework_add_permission ( "debug_call_hook", __ ( "Debug an internal hook"));
framework_add_api_call (
  "/debug/callhook/:Hook",
  "Create",
  "debug_call_hook",
  array (
    "permissions" => array ( "administrator", "debug_call_hook"),
    "title" => __ ( "Debug an internal hook"),
    "description" => __ ( "Debug an internal hook."),
    "parameters" => array (
      array (
        "name" => "Hook",
        "type" => "string",
        "description" => __ ( "The name of the hook to be called."),
        "example" => "server_rebuid_config"
      )
    )
  )
);

/**
 * Function to execute an internal hook.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function debug_call_hook ( $buffer, $parameters)
{
  /**
   * Check if requested hook is not ourself (avoid recursive call)
   */
  if ( $parameters["Hook"] == $parameters["api"]["hook"])
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "debug_call_hook_start"))
  {
    $parameters = framework_call ( "debug_call_hook_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "debug_call_hook_validate"))
  {
    $data = framework_call ( "debug_call_hook_validate", $parameters);
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
  if ( framework_has_hook ( "debug_call_hook_sanitize"))
  {
    $parameters = framework_call ( "debug_call_hook_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "debug_call_hook_pre"))
  {
    $parameters = framework_call ( "debug_call_hook_pre", $parameters, false, $parameters);
  }

  /**
   * Check if framework has the requested hook
   */
  if ( ! framework_has_hook ( $parameters["Hook"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit ();
  }

  /**
   * Execute hook
   */
  $data = framework_call ( $parameters["Hook"], $parameters);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "debug_call_hook_post"))
  {
    $data = framework_call ( "debug_call_hook_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "debug_call_hook_finish"))
  {
    framework_call ( "debug_call_hook_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch blocks listing
 */
framework_add_hook (
  "debug_explain",
  "debug_explain",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the requested number."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "E164" => array (
              "type" => "string",
              "description" => __ ( "The number in E.164 format."),
              "example" => __ ( "+11235551212")
            ),
            "Country" => array (
              "type" => "integer",
              "description" => __ ( "The country code of number, if available."),
              "example" => "76"
            ),
            "CountryCode" => array (
              "type" => "integer",
              "description" => __ ( "The numeric telephony country code of number, if available."),
              "example" => "1"
            ),
            "Country" => array (
              "type" => "string",
              "description" => __ ( "The country name of number, if available."),
              "example" => __ ( "United States of America")
            ),
            "Alpha2" => array (
              "type" => "string",
              "description" => __ ( "The Alpha2 (abbreviation of country with 2 letters) country of the number, if available."),
              "minLength" => 2,
              "maxLength" => 2,
              "pattern" => "/^[A-Z]{2}$/",
              "example" => "US"
            ),
            "Alpha3" => array (
              "type" => "string",
              "description" => __ ( "The Alpha3 (abbreviation of country with 3 letters) country of the number, if available."),
              "minLength" => 3,
              "maxLength" => 3,
              "pattern" => "/^[A-Z]{3}$/",
              "example" => "USA"
            ),
            "AreaCode" => array (
              "type" => "integer",
              "description" => __ ( "The area code of number, if available."),
              "example" => 123
            ),
            "AreaName" => array (
              "type" => "string",
              "description" => __ ( "The area name of number, if available."),
              "example" => __ ( "New York")
            ),
            "City" => array (
              "type" => "string",
              "description" => __ ( "The city name of number, if available."),
              "example" => __ ( "Springfield")
            ),
            "Operator" => array (
              "type" => "string",
              "description" => __ ( "The network operator of number, if available."),
              "example" => __ ( "ACME Mobile")
            ),
            "Number" => array (
              "type" => "integer",
              "description" => __ ( "The local number of number, if available."),
              "example" => "5551212"
            ),
            "Type" => array (
              "type" => "integer",
              "description" => __ ( "The type of number, if available. The types could be:<br />0 - Unknown;<br />1 - Land line;<br />2 - Mobile;<br />4 - VoIP;<br />8 - Fixed-Mobile Convergence (FMC);<br />16 - Premium Rate Number (PRN);<br />32 - Very-Small-Aperture Terminal (VSAT);<br />64 - Toll free;<br />128 - Pay phone;<br />256 - Audiotext terminal;<br />512 - Marine radio telephone;<br />1024 - Paging;<br />2048 - VPN access;<br />4096 - Terrestrial Trunked Radio (TETRA);<br />8192 - Faximile (FAX);<br />16384 - Voicemail box;<br />32768 - Special charge number;<br />65535 - Services.", false, false),
              "example" => 2
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "debug_explain", __ ( "Debug E.164 number"));
framework_add_api_call (
  "/debug/explain/:Number",
  "Read",
  "debug_explain",
  array (
    "permissions" => array ( "user", "debug_explain"),
    "title" => __ ( "Debug E.164 number"),
    "description" => __ ( "Debug/explain an E.164 number."),
    "parameters" => array (
      array (
        "name" => "Number",
        "type" => "string",
        "description" => __ ( "The number in E.164 format to be explained."),
        "example" => __ ( "+1 123 5551212")
      )
    )
  )
);

/**
 * Function to explain an E.164 number.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function debug_explain ( $buffer, $parameters)
{
  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "debug_explain_start"))
  {
    $parameters = framework_call ( "debug_explain_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "debug_explain_validate"))
  {
    $data = framework_call ( "debug_explain_validate", $parameters);
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
  $parameters["Number"] = str_replace ( " ", "", urldecode ( $parameters["Number"]));

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "debug_explain_sanitize"))
  {
    $parameters = framework_call ( "debug_explain_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "debug_explain_pre"))
  {
    $parameters = framework_call ( "debug_explain_pre", $parameters, false, $parameters);
  }

  /**
   * Get number information
   */
  $data = filters_call ( "e164_identify", array ( "Number" => $parameters["Number"]));

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "debug_explain_post"))
  {
    $data = framework_call ( "debug_explain_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "debug_explain_finish"))
  {
    framework_call ( "debug_explain_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch API entries map
 */
framework_add_hook (
  "debug_api_map",
  "debug_api_map",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing a large three with internal system API information."),
        "schema" => array (
          "type" => "object",
          "additionalProperties" => array (
            "type" => "string"
          )
        )
      )
    )
  )
);
framework_add_permission ( "debug_api_map", __ ( "Generate API map"));
framework_add_api_call (
  "/debug/api/map",
  "Read",
  "debug_api_map",
  array (
    "permissions" => array ( "administrator", "debug_api_map"),
    "title" => __ ( "Generate API map"),
    "description" => __ ( "Dump all internal API structure three.")
  )
);

/**
 * Function to generate an API map.
 *
 * @global array $_api Framework global API configuration variable
 * @global array $_plugins The main plugin system variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function debug_api_map ( $buffer, $parameters)
{
  global $_api, $_plugins;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "debug_api_map_start"))
  {
    $parameters = framework_call ( "debug_api_map_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "debug_api_map_validate"))
  {
    $data = framework_call ( "debug_api_map_validate", $parameters);
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
  if ( framework_has_hook ( "debug_api_map_sanitize"))
  {
    $parameters = framework_call ( "debug_api_map_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "debug_api_map_pre"))
  {
    $parameters = framework_call ( "debug_api_map_pre", $parameters, false, $parameters);
  }

  /**
   * Create API map tree
   */
  $map = array ( "FullPath" => "/");
  foreach ( $_api as $path => $data)
  {
    // Process only API path:
    if ( substr ( $path, 0, 1) != "/")
    {
      continue;
    }

    // Split path into pieces and create sub arrays if needed:
    $path = explode ( "/", $path);
    array_shift ( $path);
    $current = &$map;
    $fullpath = "";
    foreach ( $path as $pathpiece)
    {
      $fullpath .= "/" . $pathpiece;
      if ( ! array_key_exists ( $pathpiece, $current))
      {
        $current[$pathpiece] = array ( "FullPath" => $fullpath, "Actions" => array ());
      }
      $current = &$current[$pathpiece];
    }

    // Locate each action hook file and line location:
    foreach ( $data as $action => $actiondata)
    {
      $data[$action]["hook"] = array ( $data[$action]["hook"] => $_plugins["hooks"][$data[$action]["hook"]]);
      $data[$action]["hookmap"] = array ();
      foreach ( $_plugins["hooks"][$actiondata["hook"]] as $function)
      {
        $module = $_plugins["map"][$function] == "" ? "interface" : $_plugins["map"][$function];
        $file = "/modules/" . $module . "/api.php";
        $line = 0;
        $counter = 0;
        if ( $fp = @fopen ( $_SERVER["DOCUMENT_ROOT"] . $file, "r"))
        {
          while ( ! feof ( $fp))
          {
            $counter++;
            if ( preg_match ( "/function( |\\t)+" . $function . "( |\\t|\\()/", fgets ( $fp, 4096)))
            {
              $line = $counter;
              break;
            }
          }
          fclose ( $fp);
        }
        $data[$action]["hookmap"][$function] = array ( "module" => $module, "file" => $file, "line" => $line);
      }
    }

    // Merge data into current path:
    $current["Actions"] = array_merge_recursive ( $current["Actions"], $data);

    // Translate titles:
    foreach ( $current["Actions"] as $action => $data)
    {
      $current["Actions"][$action]["title"] = __ ( $data["title"], true, true, $module);
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "debug_api_map_post"))
  {
    $data = framework_call ( "debug_api_map_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "debug_api_map_finish"))
  {
    framework_call ( "debug_api_map_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $map);
}

/**
 * API call to export API structure (OpenAPI v3 format)
 */
framework_add_hook (
  "debug_api_export",
  "debug_api_export",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing an OpenAPI v3 documentation of system API."),
        "schema" => array (
          "type" => "object",
          "additionalProperties" => array (
            "type" => "string"
          )
        )
      )
    )
  )
);
framework_add_permission ( "debug_api_export", __ ( "Export API documentation"));
framework_add_api_call (
  "/debug/api/export",
  "Read",
  "debug_api_export",
  array (
    "permissions" => array ( "administrator", "debug_api_export"),
    "title" => __ ( "Export API documentation"),
    "description" => __ ( "Generate an OpenAPI v3 API documentation JSON structure."),
    "parameters" => array (
      array (
        "in" => "query",
        "name" => "Language",
        "schema" => array (
          "type" => "string",
          "enum" => array_keys ( $_in["languages"])
        ),
        "description" => __ ( "The language of document."),
        "example" => "en_US"
      )
    )
  )
);

/**
 * Function to export API structure (OpenAPI v3 format).
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @global array $_plugins The main plugin system variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function debug_api_export ( $buffer, $parameters)
{
  global $_in, $_api, $_plugins;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "debug_api_export_start"))
  {
    $parameters = framework_call ( "debug_api_export_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "debug_api_export_validate"))
  {
    $data = framework_call ( "debug_api_export_validate", $parameters);
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
  if ( framework_has_hook ( "debug_api_export_sanitize"))
  {
    $parameters = framework_call ( "debug_api_export_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "debug_api_export_pre"))
  {
    $parameters = framework_call ( "debug_api_export_pre", $parameters, false, $parameters);
  }

  /**
   * Set locale if requested
   */
  if ( array_key_exists ( "Language", $_REQUEST) && array_key_exists ( $_REQUEST["Language"], $_in["languages"]))
  {
    $_in["general"]["language"] = $_REQUEST["Language"];
  }

  /**
   * Create API paths tree
   */
  $paths = array ();
  foreach ( $_api as $path => $data)
  {
    // Process only API path:
    if ( substr ( $path, 0, 1) != "/")
    {
      continue;
    }

    // Get list (if any) of path variables and replace syntax to Swagger type:
    $pathvars = array ();
    $newpath = "";
    foreach ( explode ( "/", $path) as $pathpiece)
    {
      if ( $pathpiece != "")
      {
        $newpath .= "/";
      }
      if ( substr ( $pathpiece, 0, 1) == ":")
      {
        $pathvars[] = substr ( $pathpiece, 1);
        $newpath .= "{" . substr ( $pathpiece, 1) . "}";
      } else {
        $newpath .= $pathpiece;
      }
    }

    // Insert path into map:
    if ( ! array_key_exists ( $newpath, $paths))
    {
      $paths[$newpath] = array ();
    }

    // Process each path method:
    foreach ( $data as $method => $methoddata)
    {
      if ( array_key_exists ( "parameters", $methoddata))
      {
        // Path parameters:
        if ( sizeof ( $methoddata["parameters"]) != 0)
        {
          foreach ( $methoddata["parameters"] as $parameter)
          {
            $parameter = format_parameters ( $parameter, "path");
            if ( is_array ( $parameter))
            {
              unset ( $name);
              if ( $parameter["style"] == "simple" && $parameter["explode"] == true)
              {
                $name = $parameter["name"] . "*";
              }
              if ( $parameter["style"] == "label" && $parameter["explode"] == false)
              {
                $name = "." . $parameter["name"];
              }
              if ( $parameter["style"] == "label" && $parameter["explode"] == true)
              {
                $name = "." . $parameter["name"] . "*";
              }
              if ( $parameter["style"] == "matrix" && $parameter["explode"] == false)
              {
                $name = ";" . $parameter["name"];
              }
              if ( $parameter["style"] == "matrix" && $parameter["explode"] == true)
              {
                $name = ";" . $parameter["name"] . "*";
              }
              if ( ! empty ( $name))
              {
                $currentpath = $newpath;
                $newpath = preg_replace ( "/{" . preg_quote ( $parameter["name"]) . "}/", "{" . $name . "}", $newpath);
                if ( $newpath != $currentpath)
                {
                  $paths[$newpath] = $paths[$currentpath];
                  unset ( $paths[$currentpath]);
                }
              }
              if ( ! array_key_exists ( "parameters", $paths[$newpath]))
              {
                $paths[$newpath]["parameters"] = array ();
              }
              $paths[$newpath]["parameters"][] = $parameter;
            }
          }
        }
      }

      // Path method tags:
      $tags = array ();
      foreach ( $_plugins["hooks"][$methoddata["hook"]] as $function)
      {
        $module = $_plugins["map"][$function] == "" ? "interface" : $_plugins["map"][$function];
        if ( ! in_array ( $module, $tags))
        {
          $tags[] = $module;
        }
      }

      // Path method route:
      $method = strtolower ( array_search ( $method, $GLOBALS["apiRoutes"]));
      $paths[$newpath][$method] = array ();

      // Basic path method variables:
      $paths[$newpath][$method]["tags"] = $tags;
      $paths[$newpath][$method]["summary"] = $methoddata["title"];
      $paths[$newpath][$method]["description"] = $methoddata["description"];
      $paths[$newpath][$method]["operationId"] = str_replace ( "/", "_", $method . $newpath);

      // Path method security requirements:
      $paths[$newpath][$method]["security"] = array ();
      $permissions = array ();
      foreach ( $methoddata["permissions"] as $permission)
      {
        switch ( $permission)
        {
          case "user":
            if ( ! array_key_exists ( "SessionCookie", $permissions))
            {
              $permissions["SessionCookie"] = array ();
            }
            $permissions["SessionCookie"][] = __ ( "User");
            break;
          case "administrator":
            if ( ! array_key_exists ( "SessionCookie", $permissions))
            {
              $permissions["SessionCookie"] = array ();
            }
            $permissions["SessionCookie"][] = __ ( "Administrator");
            break;
          case "auditor":
            if ( ! array_key_exists ( "SessionCookie", $permissions))
            {
              $permissions["SessionCookie"] = array ();
            }
            $permissions["SessionCookie"][] = __ ( "Auditor");
            break;
          case "server":
            $paths[$newpath][$method]["security"][] = array ( "ServerAuthID" => array (), "ServerAuthPass" => array ());
            break;
          case "internal":
            break;
          default:
            if ( ! array_key_exists ( "ApiKeyAuth", $permissions))
            {
              $permissions["ApiKeyAuth"] = array ();
            }
            $permissions["ApiKeyAuth"][] = $permission;
            if ( ! array_key_exists ( "ApiKeyGET", $permissions))
            {
              $permissions["ApiKeyGET"] = array ();
            }
            $permissions["ApiKeyGET"][] = $permission;
            break;
        }
      }
      foreach ( $permissions as $permission => $permdata)
      {
        $paths[$newpath][$method]["security"][] = array ( $permission => $permdata);
      }

      // Path method parameters:
      $parameters = array ();
      foreach ( $_plugins["hooks"][$methoddata["hook"]]["parameters"] as $parameter)
      {
        $parameter = format_parameters ( $parameter, ( $method == "get" ? "query" : null));
        if ( is_array ( $parameter))
        {
          $parameters[] = $parameter;
        }
      }

      // Path method request body:
      $requests = array ();
      $required = false;
      foreach ( $_plugins["hooks"][$methoddata["hook"]] as $function)
      {
        if ( array_key_exists ( "required", $_plugins["functions"][$function]["requests"]) && $_plugins["functions"][$function]["requests"]["required"])
        {
          $required = true;
        }
        if ( sizeof ( $_plugins["functions"][$function]["requests"]) != 0)
        {
          $request = format_schema ( $_plugins["functions"][$function]["requests"]);
          if ( is_array ( $request))
          {
            $requests = array_merge_recursive_distinct_with_sequencial ( $requests, $request);
          }
        }
      }
      if ( sizeof ( $requests) && $method == "get")
      {
        foreach ( $requests["properties"] as $index => $data)
        {
          $parameter = array ( "in" => "query", "schema" => array ());
          $parameter["name"] = $index;
          $parameter["schema"]["type"] = $data["type"];
          if ( array_key_exists ( "format", $data))
          {
            $parameter["schema"]["format"] = $data["format"];
          }
          if ( array_key_exists ( "description", $data))
          {
            $parameter["description"] = $data["description"];
          }
          if ( array_key_exists ( "example", $data))
          {
            $parameter["example"] = $data["example"];
          }
          if ( array_key_exists ( "default", $data))
          {
            $parameter["default"] = $data["default"];
          }
          $parameters[] = $parameter;
          unset ( $requests["properties"][$index]);
        }
        if ( ! sizeof ( $requests["properties"]))
        {
          $requests = array ();
        }
      }

      // Add path parameters if available:
      if ( sizeof ( $parameters) != 0)
      {
        $paths[$newpath][$method]["parameters"] = $parameters;
      }

      // Add data to path:
      if ( sizeof ( $requests) != 0)
      {
        $paths[$newpath][$method]["requestBody"] = array ();
        $paths[$newpath][$method]["requestBody"]["description"] = __ ( "Object request body available parameters.");
        $paths[$newpath][$method]["requestBody"]["required"] = $required;
        $paths[$newpath][$method]["requestBody"]["content"] = array (
          "application/json" => array ( "schema" => $requests),
          "application/xml" => array ( "schema" => array_merge ( array ( "xml" => array ( "name" => "request")), $requests)),
          "application/x-www-form-urlencoded" => array ( "schema" => $requests),
          "application/x-php" => array ( "schema" => array ( "type" => "string", "example" => format_php_schema ( $requests)))
        );
      }

      // Path method responses:
      $paths[$newpath][$method]["responses"] = array ();
      if ( $method != "post" && $method != "delete")
      {
        $paths[$newpath][$method]["responses"]["200"] = array ( "description" => __ ( "Successfully executed."));
      }
      if ( $method == "post")
      {
        $paths[$newpath][$method]["responses"]["201"] = array ( "description" => __ ( "Object sucessfully created."), "headers" => array ( "Location" => array ( "schema" => array ( "type" => "string"), "description" => __ ( "The URL location of the created object."))));
      }
      if ( $method == "delete")
      {
        $paths[$newpath][$method]["responses"]["204"] = array ( "description" => __ ( "Object sucessfully removed."));
      }
      $paths[$newpath][$method]["responses"]["400"] = array ( "description" => __ ( "Object not found."));
      $paths[$newpath][$method]["responses"]["401"] = array ( "description" => __ ( "Invalid authentication used."));
      $paths[$newpath][$method]["responses"]["403"] = array ( "description" => __ ( "Access privileges doesn't fit request."));
      $paths[$newpath][$method]["responses"]["404"] = array ( "description" => __ ( "Requested endpoint not found."));
      $paths[$newpath][$method]["responses"]["405"] = array ( "description" => __ ( "Method not allowed to requested path. See `Allow` response header for valid methods."));
      $paths[$newpath][$method]["responses"]["406"] = array ( "description" => __ ( "Invalid response parameters content type."));
      $paths[$newpath][$method]["responses"]["415"] = array ( "description" => __ ( "Invalid parameters content type."));
      if ( $method == "post")
      {
        $paths[$newpath][$method]["responses"]["422"] = array ( "description" => __ ( "Error processing object creating parameters."));
      }
      $paths[$newpath][$method]["responses"]["500"] = array ( "description" => __ ( "Internal server error."));
      $paths[$newpath][$method]["responses"]["503"] = array ( "description" => __ ( "Internal server communication error."));
      foreach ( $_plugins["hooks"][$methoddata["hook"]] as $function)
      {
        foreach ( $_plugins["functions"][$function]["response"] as $code => $response)
        {
          if ( sizeof ( $response) == 0)
          {
            unset ( $paths[$newpath][$method]["responses"][$code]);
            continue;
          }
          if ( array_key_exists ( "description", $response))
          {
            $paths[$newpath][$method]["responses"][$code]["description"] = $response["description"];
          }
          if ( ! array_key_exists ( "schema", $response))
          {
            continue;
          }
          if ( ! array_key_exists ( "content", $paths[$newpath][$method]["responses"][$code]))
          {
            $paths[$newpath][$method]["responses"][$code]["content"] = array ( "application/json" => array ( "schema" => array ()), "application/xml" => array ( "schema" => array ( "xml" => array ( "name" => "response"))), "application/javascript" => array ( "schema" => array ( "type" => "string", "example" => __ ( "callback ( 'variables')", true, false))), "application/x-php" => array ( "schema" => array ( "type" => "string", "example" => serialize ( array ()))));
          }
          $formattedschema = format_schema ( $response["schema"]);
          if ( ! is_array ( $formattedschema))
          {
            $formattedschema = array ();
          }
          if ( array_key_exists ( "\$ref", $response["schema"]))
          {
            if ( sizeof ( $paths[$newpath][$method]["responses"][$code]["content"]["application/json"]["schema"]) != 0)
            {
              $paths[$newpath][$method]["responses"][$code]["content"]["application/json"]["schema"] = array ( "allOf" => array ( $paths[$newpath][$method]["responses"][$code]["content"]["application/json"]["schema"], $response["schema"]));
            } else {
              $paths[$newpath][$method]["responses"][$code]["content"]["application/json"]["schema"] = $response["schema"];
            }
            $paths[$newpath][$method]["responses"][$code]["content"]["application/xml"]["schema"] = array ( "allOf" => array ( $paths[$newpath][$method]["responses"][$code]["content"]["application/xml"]["schema"], $response["schema"]));
            $paths[$newpath][$method]["responses"][$code]["content"]["application/x-php"]["schema"]["example"] = serialize ( array_merge_recursive_distinct_with_sequencial ( unserialize ( $paths[$newpath][$method]["responses"][$code]["content"]["application/x-php"]["schema"]["example"]), format_php_schema ( $_plugins["components"][substr ( $response["schema"]["\$ref"], strrpos ( $response["schema"]["\$ref"], "/") + 1)], false)));
          } else {
            if ( sizeof ( $formattedschema) != 0)
            {
              $paths[$newpath][$method]["responses"][$code]["content"]["application/json"]["schema"] = array_merge_recursive_distinct_with_sequencial ( $paths[$newpath][$method]["responses"][$code]["content"]["application/json"]["schema"], $formattedschema);
              $paths[$newpath][$method]["responses"][$code]["content"]["application/xml"]["schema"] = array_merge_recursive_distinct_with_sequencial ( $paths[$newpath][$method]["responses"][$code]["content"]["application/xml"]["schema"], $formattedschema);
              $paths[$newpath][$method]["responses"][$code]["content"]["application/x-php"]["schema"]["example"] = serialize ( array_merge_recursive_distinct_with_sequencial ( unserialize ( $paths[$newpath][$method]["responses"][$code]["content"]["application/x-php"]["schema"]["example"]), format_php_schema ( $formattedschema, false)));
            }
          }
        }
      }
    }
  }

  // If buffer is empty, create basic Swagger structure to merge API paths:
  $data = array ();
  if ( ( is_array ( $buffer) && sizeof ( $buffer) == 0) || empty ( $buffer))
  {
    $languages = "";
    foreach ( $_in["languages"] as $locale => $language)
    {
      $languages .= "<a href=\"?Language=" . urlencode ( $locale) . "\">" . strip_tags ( $locale) . "</a> - " . __ ( $language) . "<br />";
    }
    $data["openapi"] = "3.0.0";
    $data["info"] = array ();
    $data["info"]["title"] = "VoIP Domain";
    $data["info"]["version"] = $_in["version"];
    $data["info"]["description"] = __ ( "This is the VoIP Domain REST API documentation. You can find more about VoIP Domain at [https://voipdomain.io/](https://voipdomain.io/). You can always access this documentation at your local VoIP Domain installation, logged into system or importing this Swagger documentation json file to an external viewer.") . "<br /><br />**" . __ ( "Definitions") . "**<br /><br />" . __ ( "*Variables*: All variables will be [UpperCamelCase](https://en.wikipedia.org/wiki/CamelCase) (also knows as PascalCase);<br />*Date/Time*: The date and time values will **always** be in GMT-0 using ISO-8601 format \"YYYY-MM-DD'T'HH:MM:SS'Z'\", with milliseconds optional (.sss after seconds) (you need to convert to your localtime).", false, false) . "<br /><br />**" . __ ( "Languages") . "**<br /><br />" . __ ( "This documentation is available in the following languages:") . "<br />" . $languages;
    $data["info"]["version"] = $_in["version"];
    $data["info"]["termsOfService"] = "https://voipdomain.io/terms";
    $data["info"]["contact"] = array ( "name" => "Ernani José Camargo Azevedo", "url" => "https://voipdomain.io/", "email" => "azevedo@voipdomain.io");
    $data["info"]["license"] = array ( "name" => "GPL v3", "url" => __ ( "https://www.gnu.org/licenses/gpl-3.0.en.html"));

    $data["servers"] = array ();
    $data["servers"][] = array ( "url" => "https://" . ( ! empty ( $_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : $_SERVER["SERVER_NAME"]) . "/api", "description" => __ ( "Local VoIP Domain server."));
    $data["servers"][] = array ( "url" => "https://cloud.voipdomain.io/apivalidate", "description" => __ ( "VoIP Domain demo server."));

    $data["tags"] = array ();

    $data["paths"] = $paths;

    $data["components"] = array ();
    $data["components"]["securitySchemes"] = array ();
    $data["components"]["securitySchemes"]["ApiKeyAuth"] = array ( "type" => "apiKey", "name" => "X-VD-Token", "in" => "header");
    $data["components"]["securitySchemes"]["ApiKeyGET"] = array ( "type" => "apiKey", "name" => "token", "in" => "query");
    $data["components"]["securitySchemes"]["SessionCookie"] = array ( "type" => "apiKey", "name" => "vd", "in" => "cookie");
    $data["components"]["securitySchemes"]["ServerAuthID"] = array ( "type" => "apiKey", "name" => "X-VD-SID", "in" => "header");
    $data["components"]["securitySchemes"]["ServerAuthPass"] = array ( "type" => "apiKey", "name" => "X-VD-SPWD", "in" => "header");
    $data["components"]["responses"] = array ();
    $data["components"]["responses"]["UnauthorizedError"] = array ();
    $data["components"]["responses"]["UnauthorizedError"]["description"] = __ ( "API key is missing or invalid.");
    $data["components"]["responses"]["ForbiddenError"] = array ();
    $data["components"]["responses"]["ForbiddenError"]["description"] = __ ( "Used API key doesn't have enough permissions.");
    $data["components"]["schemas"] = array ();
    foreach ( $_plugins["components"] as $component => $plugin)
    {
      $data["components"]["schemas"][$component] = format_schema ( $plugin);
    }
  } else {
    $var1 = is_array ( $buffer["paths"]) ? $buffer["paths"] : array ();
    $buffer["paths"] = array_merge_recursive_distinct_with_sequencial ( $var1, $paths);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "debug_api_export_post"))
  {
    $data = framework_call ( "debug_api_export_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "debug_api_export_finish"))
  {
    framework_call ( "debug_api_export_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to format documentation parameters into serialized PHP string, to be
 * added as PHP example.
 *
 * @param array $schema Structure to be extracted.
 * @param boolean[optional] $serialize Format result as PHP serialized string.
 * @return string PHP serialized schema example.
 */
function format_php_schema ( $schema, $serialize = true)
{
  $result = array ();
  foreach ( $schema["properties"] as $name => $parameters)
  {
    $result[$name] = $parameters["type"] != "object" ? $parameters["example"] : format_php_schema ( $parameters, false);
  }
  return $serialize ? serialize ( $result) : $result;
}

/**
 * Function to format path parameters documentation variables. This method will
 * filter all valid values received and return a sanitized array.
 *
 * @param array $parameters Structure to be validated.
 * @param string[optional] $in Default type of parameter.
 * @return array Sanitized values.
 */
function format_parameters ( $parameters, $in = "query")
{
  $result = array ();

  // Name and type are required:
  if ( ! array_key_exists ( "name", $parameters) || ( ! array_key_exists ( "type", $parameters) && ! array_key_exists ( "schema", $parameters)))
  {
    return;
  }
  $result["name"] = $parameters["name"];
  $result["schema"] = array ();

  // Check object type consistency:
  if ( $parameters["type"] == "object" && ( ! array_key_exists ( "properties", $parameters) && ! array_key_exists ( "additionalProperties", $parameters)))
  {
    return;
  }
  if ( $parameters["type"] == "array" && ! array_key_exists ( "items", $parameters))
  {
    return;
  }

  // Add description if available:
  if ( array_key_exists ( "description", $parameters))
  {
    $result["description"] = $parameters["description"];
  }

  // Add example if available:
  if ( array_key_exists ( "example", $parameters))
  {
    $result["example"] = $parameters["example"];
  }

  // Add enum if available:
  if ( array_key_exists ( "enum", $parameters) && is_array ( $parameters["enum"]))
  {
    $result["enum"] = $parameters["enum"];
  }

  // Add pattern if available:
  if ( array_key_exists ( "pattern", $parameters) && @preg_match ( $parameters["pattern"], "") !== false)
  {
    $result["pattern"] = $parameters["pattern"];
  }

  // Check parameter type:
  if ( ! array_key_exists ( "in", $parameters))
  {
    $parameters["in"] = $in;
  }
  if ( in_array ( $parameters["in"], array ( "path", "query", "header", "cookie")))
  {
    $result["in"] = $parameters["in"];
  } else {
    $result["in"] = "query";
  }

  // Add required value:
  if ( ( array_key_exists ( "required", $parameters) && $parameters["required"]) || $result["in"] == "path")
  {
    $result["required"] = true;
  }

  // Proccess typed or subschema:
  if ( array_key_exists ( "schema", $parameters) && ! array_key_exists ( "type", $parameters))
  {
    $result["schema"] = format_schema ( $parameters["schema"]);
  } else {
    switch ( $parameters["type"])
    {
      case "array":
      case "object":
      case "string":
      case "boolean":
      case "integer":
      case "null":
        $result["schema"]["type"] = $parameters["type"];
        break;
      case "byte":
      case "date":
      case "date-time":
      case "password":
      case "email":
      case "uuid":
        $result["schema"]["type"] = "string";
        $result["schema"]["format"] = $parameters["type"];
        break;
      case "int32":
      case "int64":
        $result["schema"]["type"] = "integer";
        $result["schema"]["format"] = $parameters["type"];
        break;
      case "long":
        $result["schema"]["type"] = "integer";
        $result["schema"]["format"] = "int64";
        break;
      case "float":
      case "double":
        $result["schema"]["type"] = "number";
        $result["schema"]["format"] = $parameters["type"];
        break;
    }
  }

  // Process style and explode parameters:
  if ( array_key_exists ( "style", $parameters) && ( $parameters["type"] == "array" || $parameters["type"] == "object"))
  {
    switch ( $result["in"])
    {
      case "path":
        if ( in_array ( $parameters["style"], array ( "simple", "label", "matrix")))
        {
          $result["style"] = $parameters["style"];
        }
        if ( array_key_exists ( "explode", $parameters) && $parameters["explode"])
        {
          $result["explode"] = true;
        }
        if ( array_key_exists ( "explode", $parameters) && ! $parameters["explode"] && $result["style"])
        {
          $result["explode"] = false;
        }
        break;
      case "query":
        if ( in_array ( $parameters["style"], array ( "form", "spaceDelimited", "pipeDelimited", "deepObject")))
        {
          $result["style"] = $parameters["style"];
        }
        if ( array_key_exists ( "explode", $parameters) && $parameters["explode"])
        {
          $result["explode"] = true;
        }
        if ( array_key_exists ( "explode", $parameters) && ! $parameters["explode"] && $result["style"] && $result["style"] != "deepObject")
        {
          $result["explode"] = false;
        }
        break;
      case "header":
        if ( in_array ( $parameters["style"], array ( "simple")))
        {
          $result["style"] = $parameters["style"];
        }
        if ( array_key_exists ( "explode", $parameters) && $parameters["explode"])
        {
          $result["explode"] = true;
        }
        if ( array_key_exists ( "explode", $parameters) && ! $parameters["explode"] && $result["style"])
        {
          $result["explode"] = false;
        }
        break;
      case "cookie":
        if ( in_array ( $parameters["style"], array ( "form")))
        {
          $result["style"] = $parameters["style"];
        }
        if ( array_key_exists ( "explode", $parameters) && $parameters["explode"])
        {
          $result["explode"] = true;
        }
        if ( array_key_exists ( "explode", $parameters) && ! $parameters["explode"] && $result["style"])
        {
          $result["explode"] = false;
        }
        break;
    }
  }

  // Process type specific parameters:
  switch ( $result["schema"]["type"])
  {
    case "integer":
    case "number":
      if ( array_key_exists ( "multipleOf", $parameters))
      {
        $result["schema"]["multipleOf"] = $parameters["multipleOf"];
      }
      if ( array_key_exists ( "maximum", $parameters))
      {
        $result["schema"]["maximum"] = $parameters["maximum"];
      }
      if ( array_key_exists ( "exclusiveMaximum", $parameters))
      {
        $result["schema"]["exclusiveMaximum"] = $parameters["exclusiveMaximum"];
      }
      if ( array_key_exists ( "minimum", $parameters))
      {
        $result["schema"]["minimum"] = $parameters["minimum"];
      }
      if ( array_key_exists ( "exclusiveMinimum", $parameters))
      {
        $result["schema"]["exclusiveMinimum"] = $parameters["exclusiveMinimum"];
      }
      if ( array_key_exists ( "default", $parameters) && is_numeric ( $parameters["default"]))
      {
        $result["schema"]["default"] = $parameters["default"];
      }
      if ( array_key_exists ( "nullable", $parameters) && ! $result["required"])
      {
        $result["schema"]["nullable"] = (boolean) $parameters["nullable"];
      }
      break;
    case "string":
      if ( array_key_exists ( "maxLength", $parameters))
      {
        $result["schema"]["maxLength"] = $parameters["maxLength"];
      }
      if ( array_key_exists ( "minLength", $parameters))
      {
        $result["schema"]["minLength"] = $parameters["minLength"];
      }
      if ( array_key_exists ( "default", $parameters) && is_string ( $parameters["default"]))
      {
        $result["schema"]["default"] = $parameters["default"];
      }
      break;
    case "boolean":
      if ( array_key_exists ( "default", $parameters) && is_bool ( $parameters["default"]))
      {
        $result["schema"]["default"] = (boolean) $parameters["default"];
      }
      break;
    case "object":
      if ( array_key_exists ( "maxProperties", $parameters))
      {
        $result["schema"]["maxProperties"] = $parameters["maxProperties"];
      }
      if ( array_key_exists ( "minProperties", $parameters))
      {
        $result["schema"]["minProperties"] = $parameters["minProperties"];
      }
      if ( array_key_exists ( "properties", $parameters))
      {
        $result["schema"]["properties"] = array ();
        foreach ( $parameters["properties"] as $property => $schema)
        {
          $result["schema"]["properties"][$property] = format_schema ( $schema);
        }
      }
      if ( array_key_exists ( "additionalProperties", $parameters))
      {
        if ( is_array ( $parameters["additionalProperties"]))
        {
          $result["schema"]["additionalProperties"] = array ();
          foreach ( $parameters["additionalProperties"] as $property => $schema)
          {
            $result["schema"]["additionalProperties"][$property] = format_schema ( $schema);
          }
        } else {
          $result["schema"]["additionalProperties"] = (boolean) $parameters["additionalProperties"];
        }
      }
      $required = array ();
      foreach ( $result["schema"]["properties"] as $property => $schema)
      {
        if ( array_key_exists ( "required", $schema))
        {
          if ( $schema["required"])
          {
            $required[] = $property;
          }
          unset ( $result["schema"]["properties"][$property]["required"]);
        }
      }
      if ( sizeof ( $required) != 0)
      {
        $result["schema"]["required"] = $required;
      }
      break;
    case "array":
      if ( array_key_exists ( "maxItems", $parameters))
      {
        $result["schema"]["maxItems"] = $parameters["maxItems"];
      }
      if ( array_key_exists ( "minItems", $parameters))
      {
        $result["schema"]["minItems"] = $parameters["minItems"];
      }
      if ( array_key_exists ( "uniqueItems", $parameters))
      {
        $result["schema"]["uniqueItems"] = $parameters["uniqueItems"];
      }
      if ( array_key_exists ( "type", $parameters["items"]) && sizeof ( $parameters["items"]) == 1)
      {
        $result["schema"]["items"] = $parameters["items"];
      } else {
        $result["schema"]["items"] = format_schema ( $parameters["items"]);
      }
      break;
  }

  return $result;
}

/**
 * Function to format request body parameters documentation variables. This
 * method will filter all valid values received and return a sanitized array.
 *
 * @param array $parameters Structure to be validated.
 * @return array Sanitized values.
 */
function format_schema ( $parameters)
{
  $result = array ();

  // If it's a reference, return it:
  if ( array_key_exists ( "\$ref", $parameters))
  {
    return $parameters;
  }

  // Process allOf, oneOf, anyOf and not properties first:
  if ( array_key_exists ( "allOf", $parameters))
  {
    $result["allOf"] = array ();
    foreach ( $parameters["allOf"] as $parameter)
    {
      $result["allOf"][] = format_schema ( $parameter);
    }
  }
  if ( array_key_exists ( "oneOf", $parameters))
  {
    $result["oneOf"] = array ();
    foreach ( $parameters["oneOf"] as $parameter)
    {
      $result["oneOf"][] = format_schema ( $parameter);
    }
  }
  if ( array_key_exists ( "anyOf", $parameters))
  {
    $result["anyOf"] = array ();
    foreach ( $parameters["anyOf"] as $parameter)
    {
      $result["anyOf"][] = format_schema ( $parameter);
    }
  }
  if ( array_key_exists ( "not", $parameters))
  {
    $result["not"] = array ();
    foreach ( $parameters["not"] as $parameter)
    {
      $result["not"][] = format_schema ( $parameter);
    }
  }

  // If has oneOf or anyOf, permit to have discriminator property:
  if ( ( array_key_exists ( "oneOf", $parameters) || array_key_exists ( "anyOf", $parameters)) && array_key_exists ( "discriminator", $parameters) && array_key_exists ( "propertyName", $parameters["discriminator"]))
  {
    $result["discriminator"] = array ();
    $result["discriminator"]["propertyName"] = $parameters["discriminator"]["propertyName"];
    if ( array_key_exists ( "mapping", $parameters["discriminator"]) && is_array ( $parameters["discriminator"]["mapping"]))
    {
      $result["discriminator"]["mapping"] = $parameters["discriminator"]["mapping"];
    }
  }

  // Check object type consistency:
  if ( $parameters["type"] == "object" && ( ! array_key_exists ( "properties", $parameters) && ! array_key_exists ( "additionalProperties", $parameters)))
  {
    return;
  }
  if ( $parameters["type"] == "array" && ! array_key_exists ( "items", $parameters))
  {
    return;
  }

  // Process schema with sub schema:
  if ( array_key_exists ( "schema", $parameters))
  {
    $result["schema"] = format_schema ( $parameters["schema"]);
  }

  // Process typed schema:
  switch ( $parameters["type"])
  {
    case "array":
    case "object":
    case "string":
    case "boolean":
    case "integer":
    case "null":
      $result["type"] = $parameters["type"];
      break;
    case "byte":
    case "date":
    case "date-time":
    case "password":
    case "email":
    case "uuid":
      $result["type"] = "string";
      $result["format"] = $parameters["type"];
      break;
    case "int32":
    case "int64":
      $result["type"] = "integer";
      $result["format"] = $parameters["type"];
      break;
    case "long":
      $result["type"] = "integer";
      $result["format"] = "int64";
      break;
    case "float":
    case "double":
      $result["type"] = "number";
      $result["format"] = $parameters["type"];
      break;
  }
  if ( ! array_key_exists ( "type", $result) && ! array_key_exists ( "schema", $parameters))
  {
    return ( sizeof ( $result) == 0 ? "" : $result);
  }

  // Add XML attributes fields (filtering valid attributes):
  if ( array_key_exists ( "xml", $parameters))
  {
    $result["xml"] = array ();
    foreach ( $parameters["xml"] as $xmlattribute => $xmlvalue)
    {
      switch ( $xmlattribute)
      {
        case "attribute":
        case "wrapped":
          $result["xml"][$xmlattribute] = (boolean) $xmlvalue;
          break;
        case "name":
        case "namespace":
        case "prefix":
          $result["xml"][$xmlattribute] = $xmlvalue;
          break;
      }
    }
  }

  // Add description if available:
  if ( array_key_exists ( "description", $parameters))
  {
    $result["description"] = $parameters["description"];
  }

  // Add example if available:
  if ( array_key_exists ( "example", $parameters))
  {
    $result["example"] = $parameters["example"];
  }

  // Add enum if available:
  if ( array_key_exists ( "enum", $parameters) && is_array ( $parameters["enum"]))
  {
    $result["enum"] = $parameters["enum"];
  }

  // Add pattern if available:
  if ( array_key_exists ( "pattern", $parameters) && @preg_match ( $parameters["pattern"], "") !== false)
  {
    $result["pattern"] = $parameters["pattern"];
  }

  // Add required value:
  if ( array_key_exists ( "required", $parameters) && $parameters["required"])
  {
    $result["required"] = true;
  }

  // Process type specific parameters:
  switch ( $result["type"])
  {
    case "integer":
    case "number":
      if ( array_key_exists ( "multipleOf", $parameters))
      {
        $result["multipleOf"] = $parameters["multipleOf"];
      }
      if ( array_key_exists ( "maximum", $parameters))
      {
        $result["maximum"] = $parameters["maximum"];
      }
      if ( array_key_exists ( "exclusiveMaximum", $parameters))
      {
        $result["exclusiveMaximum"] = $parameters["exclusiveMaximum"];
      }
      if ( array_key_exists ( "minimum", $parameters))
      {
        $result["minimum"] = $parameters["minimum"];
      }
      if ( array_key_exists ( "exclusiveMinimum", $parameters))
      {
        $result["exclusiveMinimum"] = $parameters["exclusiveMinimum"];
      }
      if ( array_key_exists ( "default", $parameters) && is_numeric ( $parameters["default"]))
      {
        $result["default"] = $parameters["default"];
      }
      if ( array_key_exists ( "nullable", $parameters) && ! $result["required"])
      {
        $result["nullable"] = (boolean) $parameters["nullable"];
      }
      break;
    case "string":
      if ( array_key_exists ( "maxLength", $parameters))
      {
        $result["maxLength"] = $parameters["maxLength"];
      }
      if ( array_key_exists ( "minLength", $parameters))
      {
        $result["minLength"] = $parameters["minLength"];
      }
      if ( array_key_exists ( "default", $parameters) && is_string ( $parameters["default"]))
      {
        $result["default"] = $parameters["default"];
      }
      break;
    case "boolean":
      if ( array_key_exists ( "default", $parameters) && is_bool ( $parameters["default"]))
      {
        $result["default"] = (boolean) $parameters["default"];
      }
      break;
    case "object":
      if ( array_key_exists ( "maxProperties", $parameters))
      {
        $result["maxProperties"] = $parameters["maxProperties"];
      }
      if ( array_key_exists ( "minProperties", $parameters))
      {
        $result["minProperties"] = $parameters["minProperties"];
      }
      if ( array_key_exists ( "properties", $parameters))
      {
        $result["properties"] = array ();
        foreach ( $parameters["properties"] as $property => $schema)
        {
          if ( $property == "discriminator" && ( array_key_exists ( "oneOf", $parameters["properties"]) || array_key_exists ( "anyOf", $parameters["properties"])) && array_key_exists ( "propertyName", $parameters["properties"]["discriminator"]))
          {
            $result["properties"]["discriminator"] = array ();
            $result["properties"]["discriminator"]["propertyName"] = $parameters["properties"]["discriminator"]["propertyName"];
            if ( array_key_exists ( "mapping", $parameters["properties"]["discriminator"]) && is_array ( $parameters["properties"]["discriminator"]["mapping"]))
            {
              $result["properties"]["discriminator"]["mapping"] = $parameters["properties"]["discriminator"]["mapping"];
            }
          } else {
            $result["properties"][$property] = ( $property == "allOf" || $property == "oneOf" || $property == "anyOf" || $property == "not" ? format_schema ( array ( $property => $schema))[$property] : format_schema ( $schema));
          }
        }
      }
      if ( array_key_exists ( "additionalProperties", $parameters))
      {
        if ( is_array ( $parameters["additionalProperties"]))
        {
          $result["additionalProperties"] = array ();
          foreach ( $parameters["additionalProperties"] as $property => $schema)
          {
            $result["additionalProperties"][$property] = ( $property == "allOf" || $property == "oneOf" || $property == "anyOf" || $property == "not" ? format_schema ( array ( $property => $schema))[$property] : format_schema ( $schema));
          }
        } else {
          $result["additionalProperties"] = (boolean) $parameters["additionalProperties"];
        }
      }
      $required = array ();
      foreach ( $result["properties"] as $property => $schema)
      {
        if ( array_key_exists ( "required", $schema))
        {
          if ( $schema["required"])
          {
            $required[] = $property;
          }
          unset ( $result["properties"][$property]["required"]);
        }
      }
      if ( sizeof ( $required) != 0)
      {
        $result["required"] = $required;
      }
      break;
    case "array":
      if ( array_key_exists ( "maxItems", $parameters))
      {
        $result["maxItems"] = $parameters["maxItems"];
      }
      if ( array_key_exists ( "minItems", $parameters))
      {
        $result["minItems"] = $parameters["minItems"];
      }
      if ( array_key_exists ( "uniqueItems", $parameters))
      {
        $result["uniqueItems"] = $parameters["uniqueItems"];
      }
      if ( array_key_exists ( "type", $parameters["items"]) && sizeof ( $parameters["items"]) == 1)
      {
        $result["items"] = $parameters["items"];
      } else {
        $result["items"] = format_schema ( $parameters["items"]);
      }
      break;
  }

  return ( sizeof ( $result) == 0 ? "" : $result);
}
?>
