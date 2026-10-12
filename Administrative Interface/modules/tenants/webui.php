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
 * VoIP Domain multi-tenants module WebUI.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Tenants
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_hook ( "multitenant_login_page_generate", "multitenant_login_page_generate", IN_HOOK_INSERT_FIRST);
framework_add_path ( "/master", "multitenant_login_page_generate");

/**
 * Function to create the multi-tenant login page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function multitenant_login_page_generate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Ensure to clear any existing cookie
   */
  setcookie ( $_in["general"]["cookie"] . "_adm", "", -1, "/");

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
    $head .= "  <title>" . addslashes ( strip_tags ( $_in["general"]["title"])) . " - " . __ ( "Master authentication") . "</title>\n";
  } else {
    $head .= "  <title>" . __ ( "Master authentication") . "</title>\n";
  }
  if ( framework_has_hook ( "template_header_tags"))
  {
    $head .= framework_call ( "template_header_tags");
  }
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
  $body .= "  <div class=\"background\"><img src=\"/img/bg-multitenant.jpg\" alt=\"\"></div>\n";
  $body .= "  <div class=\"auth-block\">\n";
  $body .= "    <div class=\"auth-top\">\n";
  $body .= "      <h1>" . ( ! empty ( $_in["general"]["title"]) ? strip_tags ( $_in["general"]["title"]) : "") . "</h1>\n";
  $body .= "      <h2>" . __ ( "Master authentication") . "</h2>\n";
  $body .= "    </div>\n";
  $body .= "    <form id=\"login\"" . ( ! $_in["security"]["loginformautocomplete"] ? " autocomplete=\"off\"" : "") . ">\n";
  $body .= "      <li><input id=\"log_" . $usernamefieldname . "\" name=\"" . $usernamefieldname . "\" type=\"text\" placeholder=\"" . __ ( "User") . "\" spellcheck=\"false\" autocapitalize=\"off\" autocorrect=\"off\" autocomplete=\"" . ( $_in["security"]["loginformautocomplete"] ? "username" : "new-text") . "\" /><i class=\"icon user\"></i></li>\n";
  $body .= "      <li id=\"login_pass\"><input id=\"log_" . $passwordfieldname . "\" name=\"" . $passwordfieldname . "\" type=\"password\" placeholder=\"" . __ ( "Password") . "\" autocomplete=\"" . ( $_in["security"]["loginformautocomplete"] ? "current-password" : "new-password") . "\" /><i class=\"icon lock\"></i></li>\n";
  $body .= "      <li id=\"login_totp\" class=\"hidden\"><input id=\"log_" . $totpfieldname . "\" name=\"" . $totpfieldname . "\" type=\"text\" placeholder=\"" . __ ( "Code") . "\" autocomplete=\"new-text\" /><i class=\"icon lock\"></i></li>\n";
  $body .= "      <div class=\"auth-bottom\">\n";
  $body .= "        <span id=\"login_forgot\"><a href=\"mailto:" . addslashes ( strip_tags ( $_in["general"]["contact"])) . "\" class=\"auth-link\">" . __ ( "Forgot your password?") . "</a></span>\n";
  $body .= "        <span id=\"login_remember\" class=\"hidden\"><input type=\"checkbox\" id=\"log_remember\" name=\"remember\"> <label for=\"log_remember\">" . __ ( "Remember me") . "</label></span>\n";
  $body .= "        <button type=\"submit\" id=\"submit\" class=\"ladda-button\" data-style=\"zoom-in\">" . __ ( "Login") . "</button>\n";
  $body .= "      </div>\n";
  $body .= "    </form>\n";
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
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/jQuery-Mask-Plugin/dist/jquery.mask" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";

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
  if ( ! empty ( $parameters["message"]))
  {
    $footer .= "      new PNotify ( { title: '" . __ ( "Authentication") . "', text: '" . htmlentities ( $parameters["message"], ENT_COMPAT, "UTF-8") . "', type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});";
  }
  $footer .= "    }\n";
  $footer .= "    image.src = '/img/bg-multitenant.jpg';\n";
  $footer .= "    var l = Ladda.create ( $('#submit')[0]);\n";
  $footer .= "    $('#log_" . $totpfieldname . "').mask ( '000 000');\n";
  $footer .= "    $('#login').on ( 'submit', function ( e)\n";
  $footer .= "    {\n";
  $footer .= "      e && e.preventDefault ();\n";
  $footer .= "      l.start ();\n";
  $footer .= "      $('#log_" . $usernamefieldname . ", #log_" . $passwordfieldname . ", button[type=\"submit\"]').attr ( 'disabled', 'disabled');\n";
  $footer .= "      $.ajax (\n";
  $footer .= "      {\n";
  $footer .= "        type: 'POST',\n";
  $footer .= "        url: '/api/session',\n";
  $footer .= "        data: JSON.stringify ( { Username: $('#log_" . $usernamefieldname . "').val (), Password: $('#log_" . $passwordfieldname . "').val (), Domain: 'Multi-Tenant', Code: $('#log_" . $totpfieldname . "').val ().replace ( / /g, ''), Remember: $('#log_remember').prop ( 'checked')}),\n";
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
  $footer .= "                      location.reload ();\n";
  $footer .= "                      return;\n";
  $footer .= "                    }\n";
  $footer .= "                    if ( jqXHR.status == 423)\n";
  $footer .= "                    {\n";
  $footer .= "                      new PNotify ( { title: '" . __ ( "Authentication") . "', text: '" . __ ( "Please provide your second factor authentication code.") . "', type: 'success', styling: 'bootstrap3', 'animate_speed': 'slow'});\n";
  $footer .= "                      l.stop ();\n";
  $footer .= "                      $('#log_" . $usernamefieldname . "').attr ( 'disabled', 'disabled');\n";
  $footer .= "                      $('#login_pass').addClass ( 'hidden');\n";
  $footer .= "                      $('#login_totp').removeClass ( 'hidden');\n";
  $footer .= "                      $('#login_options').removeClass ( 'hidden');\n";
  $footer .= "                      $('#login_forgot').addClass ( 'hidden');\n";
  $footer .= "                      $('#login_remember').removeClass ( 'hidden');\n";
  $footer .= "                      $('#login_remember').find ( 'label').css ( 'color', '#ffffff');\n";
  $footer .= "                      $('#log_" . $totpfieldname . "').focus ();\n";
  $footer .= "                      return;\n";
  $footer .= "                    }\n";
  $footer .= "                    try\n";
  $footer .= "                    {\n";
  $footer .= "                      var data = JSON.parse ( jqXHR.responseText);\n";
  $footer .= "                    } catch ( e) {\n";
  $footer .= "                      var data = {};\n";
  $footer .= "                    }\n";
  $footer .= "                    new PNotify ( { title: '" . __ ( "Authentication") . "', text: ( data.Message ? data.Message : '" . __ ( "Error authorizing user.") . "'), type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});\n";
  $footer .= "                    if ( ! $('#login_pass').hasClass ( 'hidden'))\n";
  $footer .= "                    {\n";
  $footer .= "                      $('#log_" . $usernamefieldname . ", #log_" . $passwordfieldname . ", button[type=\"submit\"]').removeAttr ( 'disabled');\n";
  $footer .= "                      $('#log_" . $usernamefieldname . "').focus ();\n";
  $footer .= "                    } else {\n";
  $footer .= "                      $('#log_" . $totpfieldname . "').focus ();\n";
  $footer .= "                    }\n";
  $footer .= "                    l.stop ();\n";
  $footer .= "                  }\n";
  $footer .= "      });\n";
  $footer .= "    });\n";
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

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/tenants", "tenants_search_page", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "tenants_search_page", "tenants_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/tenants/add", "tenants_add_page", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "tenants_add_page", "tenants_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/tenants/:id/clone", "tenants_clone_function", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "tenants_clone_function", "tenants_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/tenants/:id/view", "tenants_view_page", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "tenants_view_page", "tenants_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/tenants/:id/edit", "tenants_edit_page", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "tenants_edit_page", "tenants_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main tenants page.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_search_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Tenants"));
  sys_set_subtitle ( __ ( "Search tenants"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Tenants"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Tenants search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add tenant remove modal code
   */
  $output .= "<div id=\"tenant_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"tenant_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove tenant") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are you sure you want to remove tenant %s (%s)?"), "<span id=\"tenant_delete_name\"></span>", "<span id=\"tenant_delete_domain\"></span>") . "</p><input type=\"hidden\" id=\"tenant_delete_id\" value=\"\"></div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "        <button class=\"btn btn-primary del ladda-button\" data-style=\"expand-left\">" . __ ( "Remove") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add search table JavaScript code
   */
  sys_addjs ( "$('#datatables').data ( 'dt', $('#datatables').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Name', title: '" . __ ( "Name") . "', width: '45%', class: 'export all'},\n" .
              "             { data: 'Domain', title: '" . __ ( "Domain") . "', width: '25%', class: 'export all'},\n" .
              "             { data: 'Status', title: '" . __ ( "Status") . "', width: '5%', class: 'export all center', render: function ( data, type, row, meta)\n" .
              "                                                                                                           {\n" .
              "                                                                                                             if ( type === 'sort')\n" .
              "                                                                                                             {\n" .
              "                                                                                                               return row.Status;\n" .
              "                                                                                                             }\n" .
              "                                                                                                             switch ( row.StatusEN)\n" .
              "                                                                                                             {\n" .
              "                                                                                                               case 'Active':\n" .
              "                                                                                                                 display = '" . __ ( "Active") . "';\n" .
              "                                                                                                                 displayclass = 'success';\n" .
              "                                                                                                                 break;\n" .
              "                                                                                                               case 'Suspended':\n" .
              "                                                                                                                 display = '" . __ ( "Suspended") . "';\n" .
              "                                                                                                                 displayclass = 'warning';\n" .
              "                                                                                                                 break;\n" .
              "                                                                                                               default:\n" .
              "                                                                                                                 display = '" . __ ( "Unknown") . "';\n" .
              "                                                                                                                 displayclass = 'default';\n" .
              "                                                                                                                 break;\n" .
              "                                                                                                             }\n" .
              "                                                                                                             return '<span class=\"label label-' + displayclass + '\" title=\"\">' + display + '</span>';\n" .
              "                                                                                                           }},\n" .
              "             { data: 'UserCount', title: '" . __ ( "Users") . "', width: '5%', class: 'export min-tablet-l'},\n" .
              "             { data: 'ExtensionCount', title: '" . __ ( "Extensions") . "', width: '5%', class: 'export min-tablet-l'},\n" .
              "             { data: 'GatewayCount', title: '" . __ ( "Gateways") . "', width: '5%', class: 'export min-tablet-l'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/tenants/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/tenants/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/tenants/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-name=\"' + row.Name + '\" data-domain=\"' + row.Domain + '\"><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/tenants', fields: 'ID,Name,Domain,Status,StatusEN,UserCount,ExtensionCount,GatewayCount,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/tenants/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#tenant_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#tenant_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#tenant_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#tenant_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#tenant_delete_domain').html ( $(this).data ( 'domain'));\n" .
              "  $('#tenant_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#tenant_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/tenants/' + encodeURIComponent ( $('#tenant_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#tenant_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Tenant removal") . "', text: '" . __ ( "Tenant removed successfully!") . "', type: 'success'});\n" .
              "    $('#tenant_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Tenant removal") . "', text: '" . __ ( "Error removing tenant!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the tenant add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Tenants"));
  sys_set_subtitle ( __ ( "Add tenants"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Tenants"), "link" => "/tenants"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "flag-icons", "src" => "/vendors/flag-icons/css/flag-icons.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "maphighlight", "src" => "/vendors/maphilight/jquery.maphilight.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "timezonepicker", "src" => "/vendors/timezonepicker/lib/jquery.timezone-picker.js", "dep" => array ( "maphighlight")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"tenant_add_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#tenant_add_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#tenant_add_tab_geographical\">" . __ ( "Geographical") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#tenant_add_tab_account\">" . __ ( "Account") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"tenant_add_tab_basic\">\n";

  // Add name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"tenant_add_name\" placeholder=\"" . __ ( "Tenant name") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add domain field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Domain\" class=\"form-control\" id=\"tenant_add_domain\" placeholder=\"" . __ ( "Tenant domain") . "\" maxlength=\"255\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add email field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"email\" name=\"Email\" class=\"form-control\" id=\"tenant_add_email\" placeholder=\"" . __ ( "Tenant administrator e-mail") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Geographical panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"tenant_add_tab_geographical\">\n";

  // Add timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Map") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"hidden\" name=\"Offset\" id=\"tenant_add_offset\" value=\"\">\n";
  $output .= "          <img id=\"tenant_add_timezone_image\" src=\"/vendors/timezonepicker/images/living-600.jpg\" width=\"600\" height=\"300\" usemap=\"#tenant_add_timezone_map\" />\n";
  $output .= "          <img class=\"timezone-pin\" src=\"/vendors/timezonepicker/images/pin.png\" style=\"padding-top: 4px;\" />\n";
  $output .= "          <map name=\"tenant_add_timezone_map\" id=\"tenant_add_timezone_map\">\n";
  $output .= voipdomain_timezonepicker_map_markup ();
  $output .= "          </map>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add country selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Country\" id=\"tenant_add_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Tenant country") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_timezone\" class=\"control-label col-xs-2\">" . __ ( "Time zone") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"TimeZone\" id=\"tenant_add_timezone\" class=\"form-control\" data-placeholder=\"" . __ ( "Tenant time zone") . "\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= voipdomain_timezonepicker_select_markup ();
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add language field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Language\" class=\"form-control\" id=\"tenant_add_language\" data-placeholder=\"" . __ ( "Tenant language") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add currency field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_currency\" class=\"control-label col-xs-2\">" . __ ( "Currency") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Currency\" class=\"form-control\" id=\"tenant_add_currency\" data-placeholder=\"" . __ ( "Tenant currency") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Account panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"tenant_add_tab_account\">\n";

  // Add name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_adminname\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"AdminName\" class=\"form-control\" id=\"tenant_add_adminname\" placeholder=\"" . __ ( "Tenant administrator name") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add login field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"tenant_add_username\" placeholder=\"" . __ ( "Tenant administrator login name") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"password\" name=\"Password\" class=\"form-control\" id=\"tenant_add_password\" placeholder=\"" . __ ( "Tenant administrator password") . "\" autocomplete=\"off\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add password confirmation field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_add_confirmation\" class=\"control-label col-xs-2\">" . __ ( "Confirmation") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"password\" name=\"Confirmation\" class=\"form-control\" id=\"tenant_add_confirmation\" placeholder=\"" . __ ( "Tenant administrator password confirmation") . "\" autocomplete=\"off\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/tenants\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <div class=\"btn-group\">\n";
  $output .= "        <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Add") . "</button>\n";
  $output .= "        <button class=\"btn btn-primary dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">\n";
  $output .= "          <span class=\"caret\"><span>\n";
  $output .= "          <span class=\"sr-only\">" . __ ( "Toggle options") . "</span>\n";
  $output .= "        </button>\n";
  $output .= "        <ul class=\"dropdown-menu\">\n";
  $output .= "          <li><a class=\"dropdown-item add-new\" href=\"#\">" . __ ( "and create new") . "</a></li>\n";
  $output .= "          <li><a class=\"dropdown-item add-duplicate\" href=\"#\">" . __ ( "and duplicate content") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#tenant_add_country').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  templateResult: function ( e)\n" .
              "                  {\n" .
              "                    if ( id = e.text.match ( /.*\((.*)\)$/))\n" .
              "                    {\n" .
              "                      return '<span class=\"fi fi-' + id[1].toLowerCase () + '\"></span> ' + e.text;\n" .
              "                    } else {\n" .
              "                      return e.text;\n" .
              "                    }\n" .
              "                  },\n" .
              "  templateSelection: function ( e)\n" .
              "                     {\n" .
              "                       if ( id = e.text.match ( /.*\((.*)\)$/))\n" .
              "                       {\n" .
              "                         return '<span class=\"fi fi-' + id[1].toLowerCase () + '\"></span> ' + e.text;\n" .
              "                       } else {\n" .
              "                         return e.text;\n" .
              "                       }\n" .
              "                     },\n" .
              "  escapeMarkup: function ( m)\n" .
              "                {\n" .
              "                  return m;\n" .
              "                },\n" .
              "  data: VoIP.APIsearch ( { path: '/countries', fields: 'Code,Name,Alpha2', formatID: 'Alpha2', formatText: '%Name% (%Alpha2%)'})\n" .
              "});\n" .
              "$('#tenant_add_timezone_image').timezonePicker (\n" .
              "{\n" .
              "  changeHandler: function ( timezoneName, countryName, offset)\n" .
              "                 {\n" .
              "                   if ( $('#tenant_add_country').val () != countryName)\n" .
              "                   {\n" .
              "                     $('#tenant_add_country').val ( countryName).trigger ( 'change');\n" .
              "                   }\n" .
              "                   if ( $('#tenant_add_timezone').val () != timezoneName)\n" .
              "                   {\n" .
              "                     $('#tenant_add_timezone').val ( timezoneName).trigger ( 'change');\n" .
              "                   }\n" .
              "                   $('#tenant_add_offset').val ( offset);\n" .
              "                 }\n" .
              "}).timezonePicker ( 'detectLocation');\n" .
              "$('#tenant_add_timezone').select2 ().on ( 'change', function ( e)\n" .
              "{\n" .
              "  $('#tenant_add_timezone_image').timezonePicker ( 'updateTimezone', $('#tenant_add_timezone').val ());\n" .
              "});\n" .
              "$('#tenant_add_form').find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  $('#tenant_add_timezone_image').timezonePicker ( 'updateTimezone', $('#tenant_add_timezone').val ());\n" .
              "});\n" .
              "$('#tenant_add_currency').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/currencies', fields: 'Code,Symbol,Name', filter: function ( results, fields)\n" .
              "        {\n" .
              "          var result = new Array ();\n" .
              "          for ( var x = 0; x < results.length; x++)\n" .
              "          {\n" .
              "            result.push ( { id: results[x].Code, text: results[x].Symbol + ' (' + results[x].Name + ')'});\n" .
              "          }\n" .
              "          return result;\n" .
              "        }\n" .
              "  })\n" .
              "});\n" .
              "$('#tenant_add_language').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/locales', fields: 'Code,Name', formatID: 'Code', formatText: '%Name%'})\n" .
              "});\n" .
              "$('#tenant_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#tenant_add_name'),\n" .
              "    URL: '/tenants',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Add tenant") . "',\n" .
              "    fail: '" . __ ( "Error adding tenant!") . "',\n" .
              "    success: '" . __ ( "Tenant added successfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/tenants', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");  

  return $output;
}

/**
 * Function to generate the tenant clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/tenants/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/tenants/add', true, function ()\n" .
              "  {\n" .
              "    $('#tenant_add_name').val ( data.Name);\n" .
              "    $('#tenant_add_domain').val ( data.Domain);\n" .
              "    $('#tenant_add_email').val ( data.Email);\n" .
              "    $('#tenant_add_country').val ( data.Country['ISO3166-2']).trigger ( 'change');\n" .
              "    $('#tenant_add_timezone_image').timezonePicker ( 'updateTimezone', data.TimeZone);\n" .
              "    $('#tenant_add_timezone').val ( data.TimeZone).trigger ( 'change');\n" .
              "    $('#tenant_add_language').val ( data.Language.Code).trigger ( 'change');\n" .
              "    $('#tenant_add_currency').val ( data.Currency).trigger ( 'change');\n" .
              "    $('#tenant_add_adminname').val ( data.AdminName);\n" .
              "    $('#tenant_add_username').val ( data.Username);\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Clone tenant") . "', text: '" . __ ( "Error retrieving tenant data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the tenant view page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_view_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Tenants"));
  sys_set_subtitle ( __ ( "View tenants"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Tenants"), "link" => "/tenants"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "flag-icons", "src" => "/vendors/flag-icons/css/flag-icons.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "maphighlight", "src" => "/vendors/maphilight/jquery.maphilight.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "timezonepicker", "src" => "/vendors/timezonepicker/lib/jquery.timezone-picker.js", "dep" => array ( "maphighlight")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"tenant_view_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#tenant_view_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#tenant_view_tab_geographical\">" . __ ( "Geographical") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#tenant_view_tab_account\">" . __ ( "Account") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#tenant_view_tab_statistics\">" . __ ( "Statistics") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"tenant_view_tab_basic\">\n";

  // Add status field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_status\" class=\"control-label col-xs-2\">" . __ ( "Status") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"form-control\" id=\"tenant_view_status\" disabled=\"disabled\"><span style=\"color: #999; opacity: 1;\">" . __ ( "Tenant status") . "</span></div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"tenant_view_name\" placeholder=\"" . __ ( "Tenant name") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add domain field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"Domain\" class=\"form-control\" id=\"tenant_view_domain\" placeholder=\"" . __ ( "Tenant domain") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-domainlink\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Visit tenant site") . "\" disabled=\"disabled\"><i class=\"fa fa-external-link-alt\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add email field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"email\" name=\"Email\" class=\"form-control\" id=\"tenant_view_email\" placeholder=\"" . __ ( "Tenant administrator e-mail") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Geographical panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"tenant_view_tab_geographical\">\n";

  // Add timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Map") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"hidden\" name=\"Offset\" id=\"tenant_view_offset\" />\n";
  $output .= "          <img id=\"tenant_view_timezone_image\" src=\"/vendors/timezonepicker/images/living-600.jpg\" width=\"600\" height=\"300\" usemap=\"#tenant_view_timezone_map\" />\n";
  $output .= "          <img class=\"timezone-pin\" src=\"/vendors/timezonepicker/images/pin.png\" style=\"padding-top: 4px;\" />\n";
  $output .= "          <map name=\"tenant_view_timezone_map\" id=\"tenant_view_timezone_map\">\n";
  $output .= voipdomain_timezonepicker_map_markup ();
  $output .= "          </map>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add country selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Country\" id=\"tenant_view_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Tenant country") . "\" disabled=\"disabled\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_timezone\" class=\"control-label col-xs-2\">" . __ ( "Time zone") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"TimeZone\" id=\"tenant_view_timezone\" class=\"form-control\" data-placeholder=\"" . __ ( "Tenant time zone") . "\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= voipdomain_timezonepicker_select_markup ();
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add language field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Language\" class=\"form-control\" id=\"tenant_view_language\" data-placeholder=\"" . __ ( "Tenant language") . "\" disabled=\"disabled\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add currency field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_currency\" class=\"control-label col-xs-2\">" . __ ( "Currency") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Currency\" class=\"form-control\" id=\"tenant_view_currency\" data-placeholder=\"" . __ ( "Tenant currency") . "\" disabled=\"disabled\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Account panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"tenant_view_tab_account\">\n";

  // Add name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_adminname\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"AdminName\" class=\"form-control\" id=\"tenant_view_adminname\" placeholder=\"" . __ ( "Tenant administrator name") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add login field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"tenant_view_username\" placeholder=\"" . __ ( "Tenant administrator login name") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Statistics panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"tenant_view_tab_statistics\">\n";

  // Add user count field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_usercount\" class=\"control-label col-xs-2\">" . __ ( "Users") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"UserCount\" class=\"form-control\" id=\"tenant_view_usercount\" placeholder=\"" . __ ( "Number of users on tenant") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension count field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_extensioncount\" class=\"control-label col-xs-2\">" . __ ( "Extensions") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"ExtensionCount\" class=\"form-control\" id=\"tenant_view_extensioncount\" placeholder=\"" . __ ( "Number of extensions on tenant") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway count field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_view_gatewaycount\" class=\"control-label col-xs-2\">" . __ ( "Gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"GatewayCount\" class=\"form-control\" id=\"tenant_view_gatewaycount\" placeholder=\"" . __ ( "Number of gateways on tenant") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/tenants\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#tenant_view_country').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  templateResult: function ( e)\n" .
              "                  {\n" .
              "                    if ( id = e.text.match ( /.*\((.*)\)$/))\n" .
              "                    {\n" .
              "                      return '<span class=\"fi fi-' + id[1].toLowerCase () + '\"></span> ' + e.text;\n" .
              "                    } else {\n" .
              "                      return e.text;\n" .
              "                    }\n" .
              "                  },\n" .
              "  templateSelection: function ( e)\n" .
              "                     {\n" .
              "                       if ( id = e.text.match ( /.*\((.*)\)$/))\n" .
              "                       {\n" .
              "                         return '<span class=\"fi fi-' + id[1].toLowerCase () + '\"></span> ' + e.text;\n" .
              "                       } else {\n" .
              "                         return e.text;\n" .
              "                       }\n" .
              "                     },\n" .
              "  escapeMarkup: function ( m)\n" .
              "                {\n" .
              "                  return m;\n" .
              "                },\n" .
              "  data: VoIP.APIsearch ( { path: '/countries', fields: 'Code,Name,Alpha2', formatID: 'Alpha2', formatText: '%Name% (%Alpha2%)'})\n" .
              "});\n" .
              "$('#tenant_view_currency').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/currencies', fields: 'Code,Symbol,Name', filter: function ( results, fields)\n" .
              "        {\n" .
              "          var result = new Array ();\n" .
              "          for ( var x = 0; x < results.length; x++)\n" .
              "          {\n" .
              "            result.push ( { id: results[x].Code, text: results[x].Symbol + ' (' + results[x].Name + ')'});\n" .
              "          }\n" .
              "          return result;\n" .
              "        }\n" .
              "  })\n" .
              "});\n" .
              "$('#tenant_view_language').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/locales', fields: 'Code,Name', formatID: 'Code', formatText: '%Name%'})\n" .
              "});\n" .
              "$('#tenant_view_timezone').select2 ();\n" .
              "$('#tenant_view_timezone_image').timezonePicker (\n" .
              "{\n" .
              "  readOnly: true\n" .
              "});\n" .
              "$('.btn-domainlink').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  window.open ( 'https://' + $('#tenant_view_domain').val () + '/', '_blank').focus ();\n" .
              "});\n" .
              "$('#tenant_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  switch ( data.StatusEN)\n" .
              "  {\n" .
              "    case 'Active':\n" .
              "      display = '" . __ ( "Active") . "';\n" .
              "      displayclass = 'success';\n" .
              "      break;\n" .
              "    case 'Suspended':\n" .
              "      display = '" . __ ( "Suspended") . "';\n" .
              "      displayclass = 'warning';\n" .
              "      break;\n" .
              "    default:\n" .
              "      display = '" . __ ( "Unknown") . "';\n" .
              "      displayclass = 'default';\n" .
              "      break;\n" .
              "  }\n" .
              "  $('#tenant_view_status').html ( '<span class=\"label label-' + displayclass + '\" title=\"\">' + display + '</span>');\n" .
              "  $('#tenant_view_name').val ( data.Name);\n" .
              "  $('#tenant_view_domain').val ( data.Domain);\n" .
              "  $('.btn-domainlink').removeAttr ( 'disabled');\n" .
              "  $('#tenant_view_email').val ( data.Email);\n" .
              "  $('#tenant_view_country').val ( data.Country['ISO3166-2']).trigger ( 'change');\n" .
              "  $('#tenant_view_timezone_image').timezonePicker ( 'updateTimezone', data.TimeZone);\n" .
              "  $('#tenant_view_timezone').val ( data.TimeZone).trigger ( 'change');\n" .
              "  $('#tenant_view_language').val ( data.Language.Code).trigger ( 'change');\n" .
              "  $('#tenant_view_currency').val ( data.Currency).trigger ( 'change');\n" .
              "  $('#tenant_view_adminname').val ( data.AdminName);\n" .
              "  $('#tenant_view_username').val ( data.Username);\n" .
              "  $('#tenant_view_usercount').val ( data.UserCount);\n" .
              "  $('#tenant_view_extensioncount').val ( data.ExtensionCount);\n" .
              "  $('#tenant_view_gatewaycount').val ( data.GatewayCount);\n" .
              "});\n" .
              "$('#tenant_view_form').find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  $('#tenant_view_timezone_image').timezonePicker ( 'updateTimezone', $('#tenant_view_timezone').val ());\n" .
              "});\n" .
              "VoIP.rest ( '/tenants/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#tenant_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "View tenants") . "', text: '" . __ ( "Error viewing tenant!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the tenant edit page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_edit_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Tenants"));
  sys_set_subtitle ( __ ( "Edit tenants"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Tenants"), "link" => "/tenants"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "flag-icons", "src" => "/vendors/flag-icons/css/flag-icons.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "maphighlight", "src" => "/vendors/maphilight/jquery.maphilight.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "timezonepicker", "src" => "/vendors/timezonepicker/lib/jquery.timezone-picker.js", "dep" => array ( "maphighlight")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"tenant_edit_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#tenant_edit_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#tenant_edit_tab_geographical\">" . __ ( "Geographical") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#tenant_edit_tab_account\">" . __ ( "Account") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"tenant_edit_tab_basic\">\n";

  // Add name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"tenant_edit_name\" placeholder=\"" . __ ( "Tenant name") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add domain field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Domain\" class=\"form-control\" id=\"tenant_edit_domain\" placeholder=\"" . __ ( "Tenant domain") . "\" maxlength=\"255\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add email field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"email\" name=\"Email\" class=\"form-control\" id=\"tenant_edit_email\" placeholder=\"" . __ ( "Tenant administrator e-mail") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Geographical panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"tenant_edit_tab_geographical\">\n";

  // Add timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Map") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"hidden\" name=\"Offset\" id=\"tenant_edit_offset\" value=\"\">\n";
  $output .= "          <img id=\"tenant_edit_timezone_image\" src=\"/vendors/timezonepicker/images/living-600.jpg\" width=\"600\" height=\"300\" usemap=\"#tenant_edit_timezone_map\" />\n";
  $output .= "          <img class=\"timezone-pin\" src=\"/vendors/timezonepicker/images/pin.png\" style=\"padding-top: 4px;\" />\n";
  $output .= "          <map name=\"tenant_edit_timezone_map\" id=\"tenant_edit_timezone_map\">\n";
  $output .= voipdomain_timezonepicker_map_markup ();
  $output .= "          </map>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add country selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Country\" id=\"tenant_edit_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Tenant country") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_timezone\" class=\"control-label col-xs-2\">" . __ ( "Time zone") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"TimeZone\" id=\"tenant_edit_timezone\" class=\"form-control\" data-placeholder=\"" . __ ( "Tenant time zone") . "\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= voipdomain_timezonepicker_select_markup ();
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add language field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Language\" class=\"form-control\" id=\"tenant_edit_language\" data-placeholder=\"" . __ ( "Tenant language") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add currency field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_currency\" class=\"control-label col-xs-2\">" . __ ( "Currency") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Currency\" class=\"form-control\" id=\"tenant_edit_currency\" data-placeholder=\"" . __ ( "Tenant currency") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Account panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"tenant_edit_tab_account\">\n";

  // Add name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_adminname\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"AdminName\" class=\"form-control\" id=\"tenant_edit_adminname\" placeholder=\"" . __ ( "Tenant administrator name") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add login field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"tenant_edit_username\" placeholder=\"" . __ ( "Tenant administrator login name") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"password\" name=\"Password\" class=\"form-control\" id=\"tenant_edit_password\" placeholder=\"" . __ ( "Tenant administrator password") . "\" autocomplete=\"off\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add password confirmation field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"tenant_edit_confirmation\" class=\"control-label col-xs-2\">" . __ ( "Confirmation") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"password\" name=\"Confirmation\" class=\"form-control\" id=\"tenant_edit_confirmation\" placeholder=\"" . __ ( "Tenant administrator password confirmation") . "\" autocomplete=\"off\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/tenants\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#tenant_edit_country').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  templateResult: function ( e)\n" .
              "                  {\n" .
              "                    if ( id = e.text.match ( /.*\((.*)\)$/))\n" .
              "                    {\n" .
              "                      return '<span class=\"fi fi-' + id[1].toLowerCase () + '\"></span> ' + e.text;\n" .
              "                    } else {\n" .
              "                      return e.text;\n" .
              "                    }\n" .
              "                  },\n" .
              "  templateSelection: function ( e)\n" .
              "                     {\n" .
              "                       if ( id = e.text.match ( /.*\((.*)\)$/))\n" .
              "                       {\n" .
              "                         return '<span class=\"fi fi-' + id[1].toLowerCase () + '\"></span> ' + e.text;\n" .
              "                       } else {\n" .
              "                         return e.text;\n" .
              "                       }\n" .
              "                     },\n" .
              "  escapeMarkup: function ( m)\n" .
              "                {\n" .
              "                  return m;\n" .
              "                },\n" .
              "  data: VoIP.APIsearch ( { path: '/countries', fields: 'Code,Name,Alpha2', formatID: 'Alpha2', formatText: '%Name% (%Alpha2%)'})\n" .
              "});\n" .
              "$('#tenant_edit_timezone_image').timezonePicker (\n" .
              "{\n" .
              "  changeHandler: function ( timezoneName, countryName, offset)\n" .
              "                 {\n" .
              "                   if ( $('#tenant_edit_country').val () != countryName)\n" .
              "                   {\n" .
              "                     $('#tenant_edit_country').val ( countryName).trigger ( 'change');\n" .
              "                   }\n" .
              "                   if ( $('#tenant_edit_timezone').val () != timezoneName)\n" .
              "                   {\n" .
              "                     $('#tenant_edit_timezone').val ( timezoneName).trigger ( 'change');\n" .
              "                   }\n" .
              "                   $('#tenant_edit_offset').val ( offset);\n" .
              "                 }\n" .
              "}).timezonePicker ( 'detectLocation');\n" .
              "$('#tenant_edit_timezone').select2 ().on ( 'change', function ( e)\n" .
              "{\n" .
              "  $('#tenant_edit_timezone_image').timezonePicker ( 'updateTimezone', $('#tenant_edit_timezone').val ());\n" .
              "});\n" .
              "$('#tenant_edit_form').find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  $('#tenant_edit_timezone_image').timezonePicker ( 'updateTimezone', $('#tenant_edit_timezone').val ());\n" .
              "});\n" .
              "$('#tenant_edit_currency').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/currencies', fields: 'Code,Symbol,Name', filter: function ( results, fields)\n" .
              "        {\n" .
              "          var result = new Array ();\n" .
              "          for ( var x = 0; x < results.length; x++)\n" .
              "          {\n" .
              "            result.push ( { id: results[x].Code, text: results[x].Symbol + ' (' + results[x].Name + ')'});\n" .
              "          }\n" .
              "          return result;\n" .
              "        }\n" .
              "  })\n" .
              "});\n" .
              "$('#tenant_edit_language').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/locales', fields: 'Code,Name', formatID: 'Code', formatText: '%Name%'})\n" .
              "});\n" .
              "$('#tenant_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#tenant_edit_name').val ( data.Name);\n" .
              "  $('#tenant_edit_domain').val ( data.Domain);\n" .
              "  $('#tenant_edit_email').val ( data.Email);\n" .
              "  $('#tenant_edit_country').val ( data.Country['ISO3166-2']).trigger ( 'change');\n" .
              "  $('#tenant_edit_timezone_image').timezonePicker ( 'updateTimezone', data.TimeZone);\n" .
              "  $('#tenant_edit_timezone').val ( data.TimeZone).trigger ( 'change');\n" .
              "  $('#tenant_edit_language').val ( data.Language.Code).trigger ( 'change');\n" .
              "  $('#tenant_edit_currency').val ( data.Currency).trigger ( 'change');\n" .
              "  $('#tenant_edit_adminname').val ( data.AdminName);\n" .
              "  $('#tenant_edit_username').val ( data.Username);\n" .
              "  $('#tenant_edit_name').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/tenants/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#tenant_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Edit tenant") . "', text: '" . __ ( "Error retrieving tenant data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#tenant_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/tenants/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Edit tenant") . "',\n" .
              "    fail: '" . __ ( "Error changing tenant!") . "',\n" .
              "    success: '" . __ ( "Tenant changed successfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/tenants', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");  

  return $output;
}
?>
