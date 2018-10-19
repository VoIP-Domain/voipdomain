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
 * VoIP Domain debug module. This is an internal debug module, and should NEVER
 * be loaded at production environment.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Debug
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_hook ( "debug_exec_hook", "debug_exec_hook");
framework_add_path ( "/debug/exec_hook/:hook", "debug_exec_hook", array ( "exactonly" => false, "unauthenticated" => true));
framework_add_hook ( "debug_exec_filter", "debug_exec_filter");
framework_add_path ( "/debug/exec_filter/:filter", "debug_exec_filter", array ( "exactonly" => false, "unauthenticated" => true));
framework_add_hook ( "debug_dump_hooks", "debug_dump_hooks");
framework_add_path ( "/debug/dump_hooks", "debug_dump_hooks", array ( "unauthenticated" => true));
framework_add_hook ( "debug_dump_paths", "debug_dump_paths");
framework_add_path ( "/debug/dump_paths", "debug_dump_paths", array ( "unauthenticated" => true));
framework_add_hook ( "debug_dump_filters", "debug_dump_filters");
framework_add_path ( "/debug/dump_filters", "debug_dump_filters", array ( "unauthenticated" => true));
framework_add_hook ( "debug_get_extension", "debug_get_extension");
framework_add_path ( "/debug/get_extension/:extension", "debug_get_extension", array ( "exactonly" => false, "unauthenticated" => true));
framework_add_hook ( "debug_dup_i18n", "debug_dup_i18n");
framework_add_path ( "/debug/dup_i18n", "debug_dup_i18n", array ( "exactonly" => false, "unauthenticated" => true));
framework_add_hook ( "debug_e164", "debug_e164");
framework_add_path ( "/debug/e164", "debug_e164", array ( "unauthenticated" => true));
framework_add_hook ( "ping", "ping", IN_HOOK_INSERT_FIRST);
framework_add_path ( "/ping", "ping", array ( "unauthenticated" => true, "exactonly" => false));

/**
 * Function to return an user ping.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ping ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "ping"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  return "pong";
}

/**
 * Function to get an extension, based on number or text.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_get_extension ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "extension dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Search for data
   */
  $output = "Searching for " . ( (int) $parameters["extension"] != 0 ? "number " . (int) $parameters["extension"] : "text " . $parameters["extension"]) . "<br />\n";
  $output .= "<pre>";
  $output .= print_r ( filters_call ( "get_extensions", ( (int) $parameters["extension"] != 0 ? array ( "number" => (int) $parameters["extension"]) : array ( "text" => $parameters["extension"]))), true);
  $output .= "</pre>";

  return $output;
}

/**
 * Function to execute a hook and debug it.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_exec_hook ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "hook dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook output
   */
  $output = "HTML output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
  $output .= "<pre>\n";
  $output .= str_replace ( "<", "&lt;", print_r ( framework_call ( $parameters["hook"]), true));
  $output .= "</pre>\n";
  if ( sizeof ( $_in["page"]["css"]) != 0)
  {
    $output .= "<br />\n";
    $output .= "CSS output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
    $output .= "<pre>\n";
    $output .= str_replace ( "<", "&lt;", print_r ( $_in["page"]["css"], true));
    $output .= "</pre>\n";
  }
  if ( $_in["page"]["inlinecss"] != "")
  {
    $output .= "<br />\n";
    $output .= "Inline CSS output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
    $output .= "<pre>\n";
    $output .= str_replace ( "<", "&lt;", print_r ( $_in["page"]["inlinecss"], true));
    $output .= "</pre>\n";
  }
  if ( sizeof ( $_in["page"]["js"]) != 0)
  {
    $output .= "<br />\n";
    $output .= "JavaScript output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
    $output .= "<pre>\n";
    $output .= str_replace ( "<", "&lt;", print_r ( $_in["page"]["js"], true));
    $output .= "</pre>\n";
  }
  if ( $_in["page"]["inlinejs"] != "")
  {
    $output .= "<br />\n";
    $output .= "Inline JavaScript output of hook \"" . $parameters["hook"] . "\" execution:<br />\n";
    $output .= "<pre>\n";
    $output .= str_replace ( "<", "&lt;", print_r ( $_in["page"]["inlinejs"], true));
    $output .= "</pre>\n";
  }

  /**
   * Clear JavaScript and CSS generated code
   */
  sys_clear_js ();
  sys_clear_css ();

  return $output;
}

/**
 * Function to execute a filter and debug it.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_exec_filter ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "filter dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook output
   */
  $output = "Content output of filter \"" . $parameters["filter"] . "\" execution:<br />\n";
  $output .= "<pre>\n";
  $result = filters_call ( $parameters["filter"], $parameters);
  $output .= print_r ( $result, true);
  $output .= "</pre>\n";

  return $output;
}

/**
 * Function to dump the framework hook table.
 *
 * @global array $_plugins Framework internal plugin system variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_dump_hooks ( $buffer, $parameters)
{
  global $_plugins;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "hook structure dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook structure
   */
  $output = "Framework hook structure:<br />\n";
  $output .= "<pre>\n";
  $output .= print_r ( $_plugins, true);
  $output .= "</pre>\n";

  return $output;
}

/**
 * Function to dump the framework paths table.
 *
 * @global array $_paths Framework internal plugin system variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_dump_paths ( $buffer, $parameters)
{
  global $_paths;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "paths structure dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook structure
   */
  $output = "Framework paths structure:<br />\n";
  $output .= "<pre>\n";
  $output .= print_r ( $_paths, true);
  $output .= "</pre>\n";

  return $output;
}

/**
 * Function to dump the framework filters table.
 *
 * @global array $_filters Framework internal filters system variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_dump_filters ( $buffer, $parameters)
{
  global $_filters;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "filters structure dump"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook structure
   */
  $output = "Framework filters structure:<br />\n";
  $output .= "<pre>\n";
  $output .= print_r ( $_filters, true);
  $output .= "</pre>\n";

  return $output;
}

/**
 * Function to create E.164 debug page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_e164 ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "E.164 number debug"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "jquery-json-viewer-switch", "src" => "/vendors/jquery-json-viewer/json-viewer/jquery.json-viewer.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-json-viewer", "src" => "/vendors/jquery-json-viewer/json-viewer/jquery.json-viewer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"debug_e164\">\n";

  // Add debug number field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"block_add_description\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"number\" class=\"form-control\" id=\"debug_number\" placeholder=\"" . __ ( "Number (E.164 format)") . "\" maxlength=\"16\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Search") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  // Result field
  $output .= "<div id=\"debug_e164_result\" style=\"border: 1px solid; width: 100%; height: 5em; display: inline-table; padding-left: 16px\"></div>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#debug_e164').on ( 'submit', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#debug_e164_result').html ( '');\n" .
              "  var debug = VoIP.rest ( '/debug/explain/' + $('#debug_number').val (), 'GET');\n" .
              "  if ( debug.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#debug_e164_result').jsonViewer ( debug.result, { collapsed: false, withQuotes: true});\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "E.164 debug") . "', text: '" . __ ( "Error requesting number debug!") . "', type: 'error'});\n" .
              "  }\n" .
              "});\n");

  return $output;
}

/**
 * Function to check for duplicated internationalization strings between modules.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function debug_dup_i18n ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Debug"));
  sys_set_subtitle ( __ ( "duplicated internationalization entries"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Debug"))
  ));

  /**
   * Show hook structure
   */
  $output = "Duplicated internationalization strings:<br />\n";
  $output .= "<pre>";

  /**
   * Check for each string
   */
  foreach ( $_in["i18n"] as $text => $array)
  {
    $dup = array_unique ( array_diff_assoc ( $_in["i18n"][$text], array_unique ( $_in["i18n"][$text])));
    if ( sizeof ( $dup) == 1)
    {
      $dup = reset ( $dup);
      $output .= $text . ": ";
      foreach ( $_in["i18n"][$text] as $key => $value)
      {
        if ( $value == $dup)
        {
          $output .= $key . ", ";
        }
      }
      $output = substr ( $output, 0, strlen ( $output) - 2) . "\n";
    }
  }
  $output .= "</pre>";

  return $output;
}
?>
