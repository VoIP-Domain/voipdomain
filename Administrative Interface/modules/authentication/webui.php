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
 * VoIP Domain authentication module WebUI.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Authentication
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/config/authentication", "authentication_page", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "authentication_page", "authentication_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/auth", "authentication_login_page_generate");
framework_add_hook ( "authentication_login_page_generate", "authentication_login_page_generate", IN_HOOK_INSERT_FIRST);

/**
 * Function to create the authentication page code.
 *
 * @param string $output Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_page ( $output, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Authentication"));
  sys_set_subtitle ( __ ( "global authentication"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Authentication"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "multiselect", "src" => "/vendors/multiselect/dist/js/multiselect.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "sortable", "src" => "/vendors/Sortable/Sortable.js", "dep" => array ()));

  /**
   * First, we call sub authentication add hook's to populate tabs
   */
  $subpages = filters_call ( "authentication_subpages", array (), array ( "tabs" => array (), "js" => array ( "init" => array (), "onshow" => array (), "onfilter" => array ()), "html" => ""));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"authentication_form\">\n";

  // Add authentication panels
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#authentication_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#authentication_tab_password\">" . __ ( "Password") . "</a></li>\n";
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#authentication_tab_plugin_" . $tab . "\">" . $tabinfo["label"] . "</a></li>\n";
  }
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"authentication_tab_basic\">\n";

  // Add authentication status option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_status\" class=\"control-label col-xs-2\">" . __ ( "Status") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Status\" id=\"authentication_status\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add authentication page background image selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_background\" class=\"control-label col-xs-2\">" . __ ( "Background") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Background\" id=\"authentication_background\" class=\"form-control\" data-placeholder=\"" . __ ( "Select the authentication page background") . "\">\n";
  $output .= "            <option value=\"1\">Computer Lockdown (Blue Coat Photos)</option>\n";
  $output .= "            <option value=\"2\">Genova Liguria (gnuckx select1)</option>\n";
  $output .= "            <option value=\"3\">My Mark (Dwayne Bent)</option>\n";
  $output .= "            <option value=\"4\">Norstead - Recreated Viking Village (Larry Syverson)</option>\n";
  $output .= "            <option value=\"5\">Bagaciu: Biserica Evanghelica Fortificata (Daniel Engelvin)</option>\n";
  $output .= "            <option value=\"6\">Computer Protection (Blue Coat Photos)</option>\n";
  $output .= "            <option value=\"7\">Roma Italy (gnuckx select1)</option>\n";
  $output .= "            <option value=\"8\">Vatican Castielli (gnuckx select1)</option>\n";
  $output .= "            <option value=\"9\">Padlock (spells out 'LOCK') (Blue Coat Photos)</option>\n";
  $output .= "            <option value=\"10\">Secure Piggy Bank (Blue Coat Photos)</option>\n";
  $output .= "            <option value=\"11\">Security in the dictionary (Blue Coat Photos)</option>\n";
  $output .= "            <option value=\"12\">Safety in the dictionary (Blue Coat Photos)</option>\n";
  $output .= "            <option value=\"13\">Protection in the dictionary (Blue Coat Photos)</option>\n";
  $output .= "            <option value=\"14\">Privacy in the dictionary (Blue Coat Photos)</option>\n";
  $output .= "          </select><br />\n";
  $output .= "          <div style=\"width: 420px; height: 240px; text-align: center; vertical-align: middle; display: table-cell; border: 1px solid black\">\n";
  $output .= "            <img src=\"\" id=\"authentication_background_img\" style=\"display: none\" width=\"420\" height=\"240\"></img>\n";
  $output .= "            <span id=\"authentication_background_loader\" class=\"imgloader\">" . __ ( "Loading") . "</span>\n";
  $output .= "          </div>\n";
  $output .= "          <b>" . __ ( "Title") . "</b>: <span id=\"authentication_background_title\">Computer Lockdown</span><br />\n";
  $output .= "          <b>" . __ ( "Author") . "</b>: <span id=\"authentication_background_author\">Blue Coat Photos</span>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Close basic panel
  $output .= "    </div>\n";

  // Password authentication panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"authentication_tab_password\">\n";

  // Add authentication password status option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_password\" class=\"control-label col-xs-2\">" . __ ( "Status") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Password\" id=\"authentication_password\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Close authentication password panel
  $output .= "    </div>\n";

  // Add extension type tabs
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"authentication_tab_plugin_" . $tab . "\">\n";
    $output .= $tabinfo["html"];
    $output .= "    </div>\n";
  }
  $output .= "  </div>\n";

  // Add buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add other subpages html code
   */
  $output .= $subpages["html"];

  /**
   * Prepare onshow and onfilter JavaScript code
   */
  $onshow = "";
  foreach ( $subpages["js"]["onshow"] as $js)
  {
    $onshow .= $js;
  }
  $onfilter = "";
  foreach ( $subpages["js"]["onfilter"] as $js)
  {
    $onfilter .= $js;
  }

  /**
   * Add authentication JavaScript code
   */
  sys_addjs ( "$('#authentication_background').select2 ().on ( 'change', function ( e)\n" .
              "                                                           {\n" .
              "                                                             $('#authentication_background_img').attr ( 'src', '/img/bg-authentication-' + $(this).val () + '.jpeg').css ( 'display', 'none');\n" .
              "                                                             $('#authentication_background_loader').css ( 'display', '');\n" .
              "                                                             let text = $(this).find ( 'option:selected').text ();\n" .
              "                                                             $('#authentication_background_title').text ( text.substr ( 0, text.lastIndexOf ( ' (')));\n" .
              "                                                             let author = text.substr ( text.lastIndexOf ( ' (') + 2);\n" .
              "                                                             $('#authentication_background_author').text ( author.substr ( 0, author.length - 1));\n" .
              "                                                           });\n" .
              "$('#authentication_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Enabled") . "', off: '" . __ ( "Disabled") . "'});\n" .
              "$('#authentication_form').find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  $($(e.target).attr ( 'href')).find ( 'input[type=checkbox]').bootstrapToggle ( 'destroy').bootstrapToggle ( { on: '" . __ ( "Enabled") . "', off: '" . __ ( "Disabled") . "'});\n" .
              "});\n" .
              "$('#authentication_background_img').on ( 'load', function ()\n" .
              "{\n" .
              "  $('#authentication_background_img').css ( 'display', 'block');\n" .
              "  $('#authentication_background_loader').css ( 'display', 'none');\n" .
              "});\n" .
              "$('#authentication_status').on ( 'change', function ( e)\n" .
              "                                           {\n" .
              "                                             if ( ! $(this).prop ( 'checked'))\n" .
              "                                             {\n" .
              "                                               $('#authentication_background').attr ( 'disabled', 'disabled');\n" .
              "                                               $('#authentication_form').find ( '.nav-tablink').not ( 'a[href=\"#authentication_tab_basic\"]').each ( function ()\n" .
              "                                                                                                                                                      {\n" .
              "                                                                                                                                                        $(this).parent ().addClass ( 'disabled');\n" .
              "                                                                                                                                                      });\n" .
              "                                             } else {\n" .
              "                                               $('#authentication_background').removeAttr ( 'disabled');\n" .
              "                                               $('#authentication_form').find ( '.nav-tablink').not ( 'a[href=\"#authentication_tab_basic\"]').each ( function ()\n" .
              "                                                                                                                                                      {\n" .
              "                                                                                                                                                        $(this).parent ().removeClass ( 'disabled');\n" .
              "                                                                                                                                                      });\n" .
              "                                             }\n" .
              "                                           });\n" .
              "$('#authentication_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#authentication_status').bootstrapToggle ( data.Status ? 'on' : 'off');\n" .
              "  $('#authentication_background').val ( data.Background).trigger ( 'change');\n" .
              "  $('#authentication_password').bootstrapToggle ( data.Password ? 'on' : 'off');\n" .
              $onshow .
              "}).alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/config/authentication',\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Authentication") . "',\n" .
              "    fail: '" . __ ( "Error changing configurations!") . "',\n" .
              "    success: '" . __ ( "Authentication successfully changed!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#authentication_form').data ( 'formData');\n" .
              "  formData.Status = $('#authentication_status').prop ( 'checked');\n" .
              "  formData.Background = $('#authentication_background').val ();\n" .
              "  formData.Password = $('#authentication_password').prop ( 'checked');\n" .
              "  formData.Plugins = new Object ();\n" .
              $onfilter .
              "  $('#authentication_form').data ( 'formData', formData);\n" .
              "});\n" .
              "VoIP.rest ( '/config/authentication', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#authentication_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Authentication") . "', text: '" . __ ( "Error retrieving configurations!") . "', type: 'error'});\n" .
              "});\n");

  /**
   * Add subpages add form JavaScript code
   */
  foreach ( $subpages["js"]["init"] as $js)
  {
    sys_addjs ( $js);
  }

  return $output;
}

/**
 * Function to create the authentication login page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_login_page_generate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get current tenant ID
   */
  $tenantid = get_tenant ( ! empty ( $parameters["Context"]) ? $parameters["Context"] : $_SERVER["HTTP_HOST"]);

  /**
   * Get authentication config from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Tenant` = " . (int) $tenantid . " AND `Key` = 'Authentication'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $config = json_decode ( $result->fetch_assoc ()["Data"], true) || $config["Status"] === false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit ();
  }

  /**
   * Get authentication plugins from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Tenant` = " . (int) $tenantid . " AND `Key` LIKE 'Authentication_%'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $plugins = array ();
  while ( $entry = $result->fetch_assoc ())
  {
    $tmp = json_decode ( $entry["Data"], true);
    if ( $tmp["Status"])
    {
      $plugins[] = substr ( $entry["Key"], 15);
    }
  }

  /**
   * Get system authentication plugins
   */
  $authenticationplugins = (array) filters_call ( "authentication_plugins");

  /**
   * If login autocomplete are turned off, generate random string for username and password fields, to avoid browsers autocomplete.
   */
  if ( ! $_in["security"]["loginformautocomplete"])
  {
    $usernamefieldname = random_password ();
    $passwordfieldname = random_password ();
    $totpfieldname = random_password ();
  } else {
    $usernamefieldname = "username";
    $passwordfieldname = "password";
    $totpfieldname = "totp";
  }

  /**
   * Loading message
   */
  $loading = __ ( "LOADING");

  /**
   * Prepare the page HTML head
   */
  $head = "<!DOCTYPE html>\n";
  $head .= generate_html_banner ();
  $head .= "<html lang=\"" . ( ! empty ( $_in["general"]["language"]) ? $_in["general"]["language"] : "en_US") . "\">\n";
  $head .= "<head>\n";
  $head .= "  <meta charset=\"" . ( ! empty ( $_in["general"]["charset"]) ? strtolower ( $_in["general"]["charset"]) : "utf-8") . "\">\n";
  $head .= "  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
  $head .= "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\">\n";
  $head .= "  <meta name=\"application-name\" content=\"VoIP Domain\">\n";
  $head .= "  <meta name=\"description\" content=\"" . __ ( "VoIP PBX management system.") . "\">\n";
  $head .= "  <meta name=\"author\" content=\"Ernani José Camargo Azevedo\">\n";
  if ( ! empty ( $_in["general"]["version"]))
  {
    $head .= "  <meta name=\"version\" content=\"" . addslashes ( strip_tags ( $_in["general"]["version"])) . "\">\n";
  }
  if ( ! empty ( $_in["general"]["favicon"]))
  {
    $head .= "  <link rel=\"icon\" type=\"" . mime_content_type ( strip_tags ( dirname ( __FILE__) . "/../.." . $_in["general"]["favicon"])) . "\" href=\"" . addslashes ( strip_tags ( ( substr ( $_in["general"]["favicon"], 0, 1) != "/" ? "/" : "") . $_in["general"]["favicon"])) . "\">\n";
  }
  if ( ! empty ( $_in["general"]["title"]))
  {
    $head .= "  <title>" . addslashes ( strip_tags ( $_in["general"]["title"])) . " - " . __ ( "Authentication") . "</title>\n";
  } else {
    $head .= "  <title>" . __ ( "Authentication") . "</title>\n";
  }
  $head .= framework_call ( "template_header_tags");
  $head .= "  <style type=\"text/css\" nonce=\"" . $_in["general"]["nonce"] . "\">\n";
  $head .= "    .no-js .loader, .no-js .auth-block\n";
  $head .= "    {\n";
  $head .= "      display: none;\n";
  $head .= "    }\n";
  $head .= "    .loader\n";
  $head .= "    {\n";
  $head .= "      position: fixed;\n";
  $head .= "      background-color: #ffffff;\n";
  $head .= "      width: 100%;\n";
  $head .= "      height: 100%;\n";
  $head .= "      left: 0px;\n";
  $head .= "      top: 0px;\n";
  $head .= "      overflow: hidden;\n";
  $head .= "      cursor: default;\n";
  $head .= "      z-index: 1001;\n";
  $head .= "    }\n";
  $head .= "    .loader-animation\n";
  $head .= "    {\n";
  $head .= "      position: fixed;\n";
  $head .= "      width: 600px;\n";
  $head .= "      height: 36px;\n";
  $head .= "      left: 50%;\n";
  $head .= "      top: 50%;\n";
  $head .= "      margin-left: -300px;\n";
  $head .= "      margin-top: -18px;\n";
  $head .= "      overflow: visible;\n";
  $head .= "    }\n";
  $head .= "    .loader-animation div\n";
  $head .= "    {\n";
  $head .= "      position: absolute;\n";
  $head .= "      width: 20px;\n";
  $head .= "      height: 36px;\n";
  $head .= "      opacity: 0;\n";
  $head .= "      font-family: Helvetica, Arial, sans-serif;\n";
  $head .= "      animation: move " . sprintf ( "%.1F", 0.2 * ( strlen ( $loading) + 3)) . "s linear infinite;\n";
  $head .= "      -o-animation: move " . sprintf ( "%.1F", 0.2 * ( strlen ( $loading) + 3)) . "s linear infinite;\n";
  $head .= "      -moz-animation: move " . sprintf ( "%.1F", 0.2 * ( strlen ( $loading) + 3)) . "s linear infinite;\n";
  $head .= "      -webkit-animation: move " . sprintf ( "%.1F", 0.2 * ( strlen ( $loading) + 3)) . "s linear infinite;\n";
  $head .= "      transform: rotate(180deg);\n";
  $head .= "      -o-transform: rotate(180deg);\n";
  $head .= "      -moz-transform: rotate(180deg);\n";
  $head .= "      -webkit-transform: rotate(180deg);\n";
  $head .= "      color: #35c4f0;\n";
  $head .= "    }\n";
  for ( $x = 1; $x <= strlen ( $loading); $x++)
  {
    $head .= "    .loader-animation div:nth-child(" . ( $x + 1) . ")\n";
    $head .= "    {\n";
    $head .= "      animation-delay: " . sprintf ( "%.1F", 0.2 * $x) . "s;\n";
    $head .= "      -o-animation-delay: " . sprintf ( "%.1F", 0.2 * $x) . "s;\n";
    $head .= "      -moz-animation-delay: " . sprintf ( "%.1F", 0.2 * $x) . "s;\n";
    $head .= "      -webkit-animation-delay: " . sprintf ( "%.1F", 0.2 * $x) . "s;\n";
    $head .= "    }\n";
  }
  $head .= "    @keyframes move\n";
  $head .= "    {\n";
  $head .= "      0%\n";
  $head .= "      {\n";
  $head .= "        left: 0;\n";
  $head .= "        opacity: 0;\n";
  $head .= "      }\n";
  $head .= "      35%\n";
  $head .= "      {\n";
  $head .= "        left: 41%; \n";
  $head .= "        -moz-transform: rotate(0deg);\n";
  $head .= "        -webkit-transform: rotate(0deg);\n";
  $head .= "        -o-transform: rotate(0deg);\n";
  $head .= "        transform: rotate(0deg);\n";
  $head .= "        opacity: 1;\n";
  $head .= "      }\n";
  $head .= "      65%\n";
  $head .= "      {\n";
  $head .= "        left: 59%; \n";
  $head .= "        -moz-transform: rotate(0deg); \n";
  $head .= "        -webkit-transform: rotate(0deg); \n";
  $head .= "        -o-transform: rotate(0deg);\n";
  $head .= "        transform: rotate(0deg); \n";
  $head .= "        opacity: 1;\n";
  $head .= "      }\n";
  $head .= "      100%\n";
  $head .= "      {\n";
  $head .= "        left: 100%; \n";
  $head .= "        -moz-transform: rotate(-180deg); \n";
  $head .= "        -webkit-transform: rotate(-180deg); \n";
  $head .= "        -o-transform: rotate(-180deg); \n";
  $head .= "        transform: rotate(-180deg);\n";
  $head .= "        opacity :0;\n";
  $head .= "      }\n";
  $head .= "    }\n";
  $head .= "    @-moz-keyframes move\n";
  $head .= "    {\n";
  $head .= "      0%\n";
  $head .= "      {\n";
  $head .= "        left: 0; \n";
  $head .= "        opacity: 0;\n";
  $head .= "      }\n";
  $head .= "      35%\n";
  $head .= "      {\n";
  $head .= "        left: 41%; \n";
  $head .= "        -moz-transform: rotate(0deg); \n";
  $head .= "        transform: rotate(0deg);\n";
  $head .= "        opacity: 1;\n";
  $head .= "      }\n";
  $head .= "      65%\n";
  $head .= "      {\n";
  $head .= "        left: 59%; \n";
  $head .= "        -moz-transform: rotate(0deg); \n";
  $head .= "        transform: rotate(0deg);\n";
  $head .= "        opacity: 1;\n";
  $head .= "      }\n";
  $head .= "      100%\n";
  $head .= "      {\n";
  $head .= "        left: 100%; \n";
  $head .= "        -moz-transform: rotate(-180deg); \n";
  $head .= "        transform: rotate(-180deg);\n";
  $head .= "        opacity: 0;\n";
  $head .= "      }\n";
  $head .= "    }\n";
  $head .= "    @-webkit-keyframes move\n";
  $head .= "    {\n";
  $head .= "      0%\n";
  $head .= "      {\n";
  $head .= "        left: 0; \n";
  $head .= "        opacity: 0;\n";
  $head .= "      }\n";
  $head .= "      35%\n";
  $head .= "      {\n";
  $head .= "        left: 41%; \n";
  $head .= "        -webkit-transform: rotate(0deg); \n";
  $head .= "        transform: rotate(0deg); \n";
  $head .= "        opacity: 1;\n";
  $head .= "      }\n";
  $head .= "      65%\n";
  $head .= "      {\n";
  $head .= "        left: 59%; \n";
  $head .= "        -webkit-transform: rotate(0deg); \n";
  $head .= "        transform: rotate(0deg); \n";
  $head .= "        opacity: 1;\n";
  $head .= "      }\n";
  $head .= "      100%\n";
  $head .= "      {\n";
  $head .= "        left: 100%;\n";
  $head .= "        -webkit-transform: rotate(-180deg); \n";
  $head .= "        transform: rotate(-180deg); \n";
  $head .= "        opacity: 0;\n";
  $head .= "      }\n";
  $head .= "    }\n";
  $head .= "    @-o-keyframes move\n";
  $head .= "    {\n";
  $head .= "      0%\n";
  $head .= "      {\n";
  $head .= "        left: 0; \n";
  $head .= "        opacity: 0;\n";
  $head .= "      }\n";
  $head .= "      35%\n";
  $head .= "      {\n";
  $head .= "        left: 41%; \n";
  $head .= "        -o-transform: rotate(0deg); \n";
  $head .= "        transform: rotate(0deg); \n";
  $head .= "        opacity: 1;\n";
  $head .= "      }\n";
  $head .= "      65%\n";
  $head .= "      {\n";
  $head .= "        left: 59%; \n";
  $head .= "        -o-transform: rotate(0deg); \n";
  $head .= "        transform: rotate(0deg); \n";
  $head .= "        opacity: 1;\n";
  $head .= "      }\n";
  $head .= "      100%\n";
  $head .= "      {\n";
  $head .= "        left: 100%; \n";
  $head .= "        -o-transform: rotate(-180deg); \n";
  $head .= "        transform: rotate(-180deg); \n";
  $head .= "        opacity: 0;\n";
  $head .= "      }\n";
  $head .= "    }\n";
  $head .= "  </style>\n";

  // Add interface CSS files
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/vendors/bootstrap/css/bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/vendors/pnotify/dist/pnotify" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/vendors/ladda/dist/ladda" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/vendors/font-awesome/css/all" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/css/login" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";

  // Add support to IE8 of HTML5 elements and media queries
  $head .= "  <!--[if lt IE 9]>\n";
  $head .= "    <script type=\"text/javascript\" src=\"/vendors/html5shiv/dist/html5shiv" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $head .= "    <script type=\"text/javascript\" src=\"/vendors/respond/" . ( $_in["general"]["debug"] === false ? "dest" : "src") . "/respond" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $head .= "  <![endif]-->\n";

  // End of header
  $head .= "</head>\n";

  /**
   * Print page body content
   */
  $body = "<body class=\"no-js\">\n";
  $body .= "  <script type=\"text/javascript\" nonce=\"" . $_in["general"]["nonce"] . "\">\n";
  $body .= "    document.body.classList.remove ( 'no-js');\n";
  $body .= "  </script>\n";
  $body .= "  <div class=\"loader\">\n";
  $body .= "    <div class=\"loader-animation\">\n";
  for ( $x = strlen ( $loading) - 1; $x >= 0; $x--)
  {
    $body .= "      <div>" . substr ( $loading, $x, 1) . "</div>\n";
  }
  $body .= "    </div>\n";
  $body .= "  </div>\n";
  $body .= "  <div class=\"background\"><img src=\"/img/bg-authentication-" . (int) $config["Background"] . ".jpeg\" alt=\"\"></div>\n";
  $body .= "  <div class=\"auth-block\">\n";
  $body .= "    <div class=\"auth-top\">\n";
  $body .= "      <h1>" . ( ! empty ( $_in["general"]["title"]) ? strip_tags ( $_in["general"]["title"]) : "") . "</h1>\n";
  $body .= "      <h2>" . __ ( "Authentication") . "</h2>\n";
  $body .= "    </div>\n";
  if ( $config["Password"] === true)
  {
    $body .= "    <form id=\"login\"" . ( ! $_in["security"]["loginformautocomplete"] ? " autocomplete=\"off\"" : "") . ">\n";
    $body .= "      <li><input id=\"log_" . $usernamefieldname . "\" name=\"" . $usernamefieldname . "\" type=\"text\" placeholder=\"" . __ ( "User") . "\" spellcheck=\"false\" autocapitalize=\"off\" autocorrect=\"off\" autocomplete=\"" . ( $_in["security"]["loginformautocomplete"] ? "username" : "new-text") . "\" /><i class=\"icon user\"></i></li>\n";
    $body .= "      <li id=\"login_pass\"><input id=\"log_" . $passwordfieldname . "\" name=\"" . $passwordfieldname . "\" type=\"password\" placeholder=\"" . __ ( "Password") . "\" autocomplete=\"" . ( $_in["security"]["loginformautocomplete"] ? "current-password" : "new-password") . "\" /><i class=\"icon lock\"></i></li>\n";
    $body .= "      <div class=\"auth-bottom\">\n";
    $body .= "        <span id=\"login_forgot\"><a href=\"mailto:" . addslashes ( strip_tags ( $_in["general"]["contact"])) . "\" class=\"auth-link\">" . __ ( "Forgot your password?") . "</a></span>\n";
    $body .= "        <span id=\"login_remember\" class=\"hidden\"><input type=\"checkbox\" id=\"log_remember\" name=\"remember\"> <label for=\"log_remember\">" . __ ( "Remember me") . "</label></span>\n";
    $body .= "        <button type=\"submit\" id=\"submit\" class=\"ladda-button\" data-style=\"zoom-in\">" . __ ( "Login") . "</button>\n";
    $body .= "      </div>\n";
    if ( sizeof ( $plugins) != 0)
    {
      $body .= "      <br /><br />\n";
      $body .= "      <div class=\"line-container\">\n";
      $body .= "        <div class=\"line-with-text\">\n";
      $body .= "          <div class=\"line\"></div>\n";
      $body .= "          <div class=\"text\">" . __ ( "OR AUTHENTICATE USING") . "</div>\n";
      $body .= "          <div class=\"line\"></div>\n";
      $body .= "        </div>\n";
      $body .= "      </div>\n";
      $body .= "      <div class=\"authentication-icons\">\n";
      foreach ( $plugins as $plugin)
      {
        $body .= "        <div class=\"authentication-box\"><a href=\"" . $authenticationplugins[$plugin]["AuthenticationURL"] . ( $parameters["Callback"] ? "?Callback=" . urlencode ( $parameters["Callback"]) : "") . "\" title=\"" . strip_tags ( $authenticationplugins[$plugin]["Name"]) . "\"><i class=\"fab fa-" . $authenticationplugins[$plugin]["Icon"] . "\"></i></a></div>\n";
      }
      $body .= "      </div>\n";
    }
    $body .= "    </form>\n";
  } else {
    $body .= "    <form>\n";
    $body .= "      <div class=\"line-container\">\n";
    $body .= "        <div class=\"line-with-text\">\n";
    $body .= "          <div class=\"line\"></div>\n";
    $body .= "          <div class=\"text\">" . __ ( "AUTHENTICATE USING") . "</div>\n";
    $body .= "          <div class=\"line\"></div>\n";
    $body .= "        </div>\n";
    $body .= "      </div>\n";
    $body .= "      <div class=\"authentication-icons\">\n";
    foreach ( $plugins as $plugin)
    {
      $body .= "        <div class=\"authentication-box\"><a href=\"" . $authenticationplugins[$plugin]["AuthenticationURL"] . ( $parameters["Callback"] ? "?Callback=" . urlencode ( $parameters["Callback"]) : "") . "\" title=\"" . strip_tags ( $authenticationplugins[$plugin]["Name"]) . "\"><i class=\"fab fa-" . $authenticationplugins[$plugin]["Icon"] . "\"></i></a></div>\n";
    }
    $body .= "      </div>\n";
    $body .= "    </form>\n";
  }
  $body .= "  </div>\n";
  $body .= "\n";
  $body .= "  <noscript>\n";
  $body .= "    <div class=\"alert alert-block col-sm-10\">\n";
  $body .= "      <h4 class=\"alert-bodying\">" . __ ( "Warning!") . "</h4>\n";
  $body .= "      <p>" . __ ( "You must have") . " <a href=\"http://pt.wikipedia.org/wiki/JavaScript\" target=\"_blank\" rel=\"noopener\">JavaScript</a> " . __ ( "enabled to use this system.") . "</p>\n";
  $body .= "    </div>\n";
  $body .= "  </noscript>\n";
  $body .= "</body>\n";

  /**
   * Prepare page javascript codes
   */
  $footer = "\n";

  // jQuery is mandatory
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/jquery/dist/jquery" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";

  // Add interface framework JavaScript files
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/pnotify/dist/pnotify" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $footer .= "<script type=\"module\" src=\"/vendors/spin/spin-module" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $footer .= "<script type=\"module\" src=\"/vendors/ladda/js/ladda-module" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";

  /**
   * Add authentication page javascript code
   */
  $footer .= "<script type=\"text/javascript\" nonce=\"" . $_in["general"]["nonce"] . "\">\n";
  $footer .= "  $(document).ready ( function ()\n";
  $footer .= "  {\n";
  $footer .= "    var image = new Image ();\n";
  $footer .= "    image.onload = function ()\n";
  $footer .= "    {\n";
  $footer .= "      $('.loader').fadeOut ( 'slow');\n";
  $footer .= "      $('#log_" . $usernamefieldname . "').focus ();\n";
  if ( ! empty ( $_GET["Message"]))
  {
    $footer .= "      new PNotify ( { title: '" . __ ( "Authentication") . "', text: '" . htmlentities ( $_GET["Message"], ENT_COMPAT, "UTF-8") . "', type: '" . htmlentities ( $_GET["MessageType"] ? $_GET["MessageType"] : "error") . "', styling: 'bootstrap3', 'animate_speed': 'slow'});";
  }
  $footer .= "    }\n";
  $footer .= "    image.src = '/img/bg-authentication-" . (int) $config["Background"] . ".jpeg';\n";
  if ( $config["Password"] === true)
  {
    $footer .= "    var l = Ladda.create ( $('#submit')[0]);\n";
    $footer .= "    $('#login').on ( 'submit', function ( e)\n";
    $footer .= "    {\n";
    $footer .= "      e && e.preventDefault ();\n";
    $footer .= "      l.start ();\n";
    $footer .= "      $('#log_" . $usernamefieldname . ", #log_" . $passwordfieldname . ", button[type=\"submit\"]').attr ( 'disabled', 'disabled');\n";
    $footer .= "      $.ajax (\n";
    $footer .= "      {\n";
    $footer .= "        type: 'POST',\n";
    $footer .= "        url: '/api/auth',\n";
    $footer .= "        data: JSON.stringify ( { Username: $('#log_" . $usernamefieldname . "').val (), Password: $('#log_" . $passwordfieldname . "').val (), Context: window.location.hostname}),\n";
    $footer .= "        headers: {\n";
    $footer .= "                   'X-HTTP-Method-Override': 'POST',\n";
    $footer .= "                   'Accept': 'application/json'\n";
    $footer .= "                 },\n";
    $footer .= "        contentType: 'application/json; charset=utf-8',\n";
    $footer .= "        dataType: 'json',\n";
    $footer .= "        complete: function ( jqXHR, textStatus)\n";
    $footer .= "                  {\n";
    $footer .= "                    if ( jqXHR.status == 201)\n";
    $footer .= "                    {\n";
    $footer .= "                      document.location = jqXHR.getResponseHeader ( 'Location');\n";
    $footer .= "                      return;\n";
    $footer .= "                    }\n";
    $footer .= "                    try\n";
    $footer .= "                    {\n";
    $footer .= "                      var data = JSON.parse ( jqXHR.responseText);\n";
    $footer .= "                    } catch ( e) {\n";
    $footer .= "                      var data = {};\n";
    $footer .= "                    }\n";
    $footer .= "                    new PNotify ( { title: '" . __ ( "Authentication") . "', text: ( data.Message ? data.Message : '" . __ ( "Error authorizing user.") . "'), type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});\n";
    $footer .= "                    $('#log_" . $totpfieldname . "').focus ();\n";
    $footer .= "                    l.stop ();\n";
    $footer .= "                  }\n";
    $footer .= "      });\n";
    $footer .= "    });\n";
  }
  $footer .= "  });\n";
  $footer .= "</script>\n";

  /**
   * Output HTML footer
   */
  $footer .= "\n";
  $footer .= "</body>\n";
  $footer .= "</html>\n";

  return $head . $body . $footer;
}
?>
