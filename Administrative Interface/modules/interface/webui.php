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
 * VoIP Domain main framework interface module WebUI. This module build all the
 * interface to the user browser. It controls the authentication, menu, basic
 * interface forms like user profile page, and other things related to UI.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Interface
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Function to create framework menu structure.
 *
 * @global array $_in Framework global configuration variable
 * @param array $structure The menu structure array
 * @param string[optional] $indent The basic indentation for this level
 * @return string Processed output menu code
 */
function page_menu ( $structure, $indent = "")
{
  global $_in;

  // First, we need to group entries (and separate groups with divisor):
  $newstructure = array ();
  while ( sizeof ( $structure) != 0)
  {
    $entry = array_shift ( $structure);
    if ( ! array_key_exists ( "type", $entry))
    {
      continue;
    }
    if ( array_key_exists ( "permission", $entry) && ! $_in["session"]["Permissions"][$entry["permission"]])
    {
      continue;
    }
    if ( ! array_key_exists ( "group", $entry))
    {
      $entry["group"] = "";
    }
    if ( ! array_key_exists ( $entry["group"], $newstructure))
    {
      $newstructure[$entry["group"]] = array ();
    }
    $newstructure[$entry["group"]][] = $entry;
  }
  foreach ( $newstructure as $group => $entry)
  {
    $structure = array_merge_recursive ( $structure, $entry);
    $structure[] = array ( "type" => "divider");
  }
  array_pop ( $structure);

  // Now we process each entry:
  $content = "";
  foreach ( $structure as $entry)
  {
    switch ( $entry["type"])
    {
      case "header":
        $content .= $indent . "        <li class=\"header\">" . $entry["text"] . "</li>\n";
        break;
      case "submenu":
        $content .= $indent . "        <li class=\"treeview\">\n";
        $content .= $indent . "          <a href=\"\">";
        if ( ! empty ( $entry["icon"]))
        {
          $content .= "<i class=\"fa fa-" . $entry["icon"] . ( isset ( $entry["color"]) ? " text-" . $entry["color"] : "") . "\"></i> ";
        }
        $content .= "<span>" . $entry["text"] . "</span><span class=\"pull-right-container\">";
        if ( isset ( $entry["labels"]))
        {
          foreach ( $entry["labels"] as $label)
          {
            $content .= "<small class=\"label pull-right bg-" . $label["color"] . "\">" . $label["text"] . "</small>";
          }
        } else {
          $content .= "<i class=\"fa fa-angle-left pull-right\"></i>";
        }
        $content .= "</span></a>\n";
        $content .= $indent . "          <ul class=\"treeview-menu\">\n";
        $content .= page_menu ( $entry["entries"], $indent . "    ");
        $content .= $indent . "          </ul>\n";
        $content .= $indent . "        </li>\n";
        break;
      case "divider":
        $content .= $indent . "        <hr class=\"sidebar-menu\">\n";
        break;
      case "entry":
        $content .= $indent . "        <li><a href=\"" . $entry["href"] . "\">";
        if ( ! empty ( $entry["icon"]))
        {
          $content .= "<i class=\"fa fa-" . $entry["icon"] . ( isset ( $entry["color"]) ? " text-" . $entry["color"] : "") . "\"></i> ";
        }
        $content .= "<span>" . $entry["text"] . "</span>";
        if ( isset ( $entry["labels"]))
        {
          $content .= "<span class=\"pull-right-container\">";
          foreach ( $entry["labels"] as $label)
          {
            $content .= "<small class=\"label pull-right bg-" . $label["color"] . "\">" . $label["text"] . "</small>";
          }
          $content .= "</span>";
        }
        $content .= "</a></li>\n";
        break;
    }
  }

  return $content;
}

/**
 * Add framework page structure hook
 */
framework_add_hook ( "framework_page_generate", "framework_page_generate", IN_HOOK_INSERT_FIRST);

/**
 * Main framework interface function. This function get the output made by the
 * system and create the HTML structure, adding CSS, JavaScript files and
 * scripts, framework information, etc.
 *
 * @global array $_in Framework global configuration variable
 * @param string $content Content of the output buffer capture
 * @return string Processed output page ready to sent to final user
 */
function framework_page_generate ( $content)
{
  global $_in;

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
  if ( ! empty ( $_in["general"]["favicon"]) && is_readable ( $_SERVER["DOCUMENT_ROOT"] . $_in["general"]["favicon"]))
  {
    $head .= "  <link rel=\"icon\" type=\"" . mime_content_type ( $_SERVER["DOCUMENT_ROOT"] . $_in["general"]["favicon"]) . "\" href=\"" . addslashes ( strip_tags ( ( substr ( $_in["general"]["favicon"], 0, 1) != "/" ? "/" : "") . $_in["general"]["favicon"])) . "\">\n";
  }
  $head .= "  <title>" . addslashes ( strip_tags ( $_in["general"]["title"])) . "</title>\n";
  $head .= framework_call ( "template_header_tags");
  $head .= "  <style type=\"text/css\" nonce=\"" . $_in["general"]["nonce"] . "\">\n";
  $head .= "    .no-js .loader, .no-js .wrapper, .no-js #vd_debug\n";
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

  // Add support to IE8 of HTML5 elements and media queries
  $head .= "  <!--[if lt IE 9]>\n";
  $head .= "    <script type=\"text/javascript\" src=\"/vendors/html5shiv/dist/html5shiv" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $head .= "    <script type=\"text/javascript\" src=\"/vendors/respond/" . ( $_in["general"]["debug"] === false ? "dest" : "src") . "/respond" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $head .= "  <![endif]-->\n";

  // End of header
  $head .= "</head>\n";

  /**
   * Page body
   */
  $body = "<body class=\"hold-transition skin-blue sidebar-mini no-js\">\n";
  $body .= "<script type=\"text/javascript\" nonce=\"" . $_in["general"]["nonce"] . "\">\n";
  $body .= "  document.body.classList.remove ( 'no-js');\n";
  $body .= "</script>\n";
  $body .= "<div class=\"loader\">\n";
  $body .= "  <div class=\"loader-animation\">\n";
  for ( $x = strlen ( $loading) - 1; $x >= 0; $x--)
  {
    $body .= "    <div>" . substr ( $loading, $x, 1) . "</div>\n";
  }
  $body .= "  </div>\n";
  $body .= "</div>\n";
  $body .= "<div class=\"loader-bar\"><div class=\"percentage\"></div></div>\n";
  $body .= "\n";
  $body .= "<noscript>\n";
  $body .= "  <div class=\"alert alert-block col-sm-10\">\n";
  $body .= "    <h4 class=\"alert-bodying\">" . __ ( "Warning!") . "</h4>\n";
  $body .= "    <p>" . __ ( "You must have") . " <a href=\"http://pt.wikipedia.org/wiki/JavaScript\" target=\"_blank\" rel=\"noopener\">JavaScript</a> " . __ ( "enabled to use this system.") . "</p>\n";
  $body .= "  </div>\n";
  $body .= "</noscript>\n";
  $body .= "\n";

  /**
   * Print the framework page structure
   */
  $body .= "<div class=\"wrapper\">\n";
  $body .= "  <header class=\"main-header\">\n";
  $body .= "    <a href=\"/\" class=\"logo\">\n";
  if ( $_in["logo"]["filename"])
  {
    $body .= "      <img src=\"" . $_in["logo"]["filename"] . "\"";
    if ( $_in["logo"]["width"])
    {
      $body .= " width=\"" . (int) $_in["logo"]["width"] . "\"";
    }
    if ( $_in["logo"]["height"])
    {
      $body .= " height=\"" . (int) $_in["logo"]["height"] . "\"";
    }
    $body .= " alt=\"\" />\n";
  }
  $body .= "      <span class=\"logo-mini\"><b>V</b>D</span>\n";
  $body .= "      <span class=\"logo-lg\"><b>VoIP</b> Domain</span>\n";
  $body .= "    </a>\n";
  $body .= "    <nav class=\"navbar navbar-static-top\">\n";
  $body .= "      <a href=\"#\" class=\"sidebar-toggle\" data-toggle=\"push-menu\" role=\"button\">\n";
  $body .= "        <span class=\"sr-only\">" . __ ( "Toggle navigation") . "</span>\n";
  $body .= "        <span class=\"icon-bar\"></span>\n";
  $body .= "        <span class=\"icon-bar\"></span>\n";
  $body .= "        <span class=\"icon-bar\"></span>\n";
  $body .= "      </a>\n";
  $body .= "      <div class=\"navbar-custom-menu\">\n";
  $body .= "        <ul class=\"nav navbar-nav\">\n";
  $body .= "          <li class=\"dropdown user user-menu\">\n";
  $body .= "            <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">\n";
  $body .= "              <img src=\"/img/avatar.jpg\" class=\"user-image avatar\" alt=\"\">\n";
  $body .= "              <span class=\"hidden-xs\">" . strip_tags ( $_in["session"]["Name"]) . "</span>\n";
  $body .= "            </a>\n";
  $body .= "            <ul class=\"dropdown-menu\">\n";
  $body .= "              <li class=\"user-header\">\n";
  $body .= "                <img src=\"/img/avatar.jpg\" class=\"img-circle avatar\" alt=\"\">\n";
  $body .= "                <p>\n";
  $body .= "                  " . strip_tags ( $_in["session"]["Name"]) . " - " . ( $_in["session"]["Permissions"]["administrator"] == true ? __ ( "Administrator") : __ ( "User")) . "\n";
  $body .= "                  <small>" . __ ( "Since") . " " . date ( __ ( "m/d/Y"), mktime ( 0, 0, 0, substr ( $_in["session"]["Since"], 5, 2), substr ( $_in["session"]["Since"], 8, 2), substr ( $_in["session"]["Since"], 0, 4))) . "</small>\n";
  $body .= "                </p>\n";
  $body .= "              </li>\n";
  $body .= "              <li class=\"user-footer\">\n";
  $body .= "                <div class=\"pull-left\">\n";
  $body .= "                  <a href=\"/users/" . $_in["session"]["ID"] . "/edit\" class=\"btn btn-default btn-flat\">" . __ ( "Profile") . "</a>\n";
  $body .= "                </div>\n";
  $body .= "                <div class=\"pull-right\">\n";
  $body .= "                  <a href=\"/logout\" class=\"btn btn-default btn-flat\">" . __ ( "Logout") . "</a>\n";
  $body .= "                </div>\n";
  $body .= "              </li>\n";
  $body .= "            </ul>\n";
  $body .= "          </li>\n";
  $body .= "        </ul>\n";
  $body .= "      </div>\n";
  $body .= "    </nav>\n";
  $body .= "  </header>\n";

  /**
   * Create basic menu structure
   */
  $menu = array ();
  $menu[] = array ( "type" => "entry", "icon" => "home", "color" => "red", "href" => "/", "text" => __ ( "Start"));
  $menu[] = array ( "type" => "submenu", "icon" => "book", "text" => __ ( "Entries"), "entries" => (array) filters_call ( "page_menu_registers"));
  $menu[] = array ( "type" => "submenu", "icon" => "file", "text" => __ ( "Reports"), "entries" => (array) filters_call ( "page_menu_reports"));
  $menu[] = array ( "type" => "submenu", "icon" => "cog", "text" => __ ( "Configurations"), "permission" => "administrator", "entries" => (array) filters_call ( "page_menu_configurations"));
  $menu[] = array ( "type" => "submenu", "icon" => "suitcase", "text" => __ ( "Resources"), "entries" => (array) filters_call ( "page_menu_resources"));
  $menu[] = (array) filters_call ( "page_menu_others");
  $menu[] = array ( "type" => "entry", "icon" => "info-circle", "color" => "blue", "href" => "/about", "text" => __ ( "About"));

  /**
   * Print menu
   */
  $body .= "  <aside class=\"main-sidebar\">\n";
  $body .= "    <section class=\"sidebar\">\n";
  $body .= "      <div class=\"user-panel\">\n";
  $body .= "        <div class=\"pull-left image\">\n";
  $body .= "          <img src=\"/img/avatar.jpg\" class=\"img-circle avatar\" alt=\"\">\n";
  $body .= "        </div>\n";
  $body .= "        <div class=\"pull-left info\">\n";
  $body .= "          <p>" . strip_tags ( $_in["session"]["Name"]) . "</p>\n";
  $body .= "          <a href=\"#\"><i class=\"fa fa-circle text-success\"></i> " . __ ( "Online") . "</a>\n";
  $body .= "        </div>\n";
  $body .= "      </div>\n";
  $body .= "      <form class=\"sidebar-form\" action=\"\" id=\"fastsearch\">\n";
  $body .= "        <div class=\"input-group\">\n";
  $body .= "          <input type=\"search\" name=\"q\" class=\"form-control\" placeholder=\"" . __ ( "Search") . "...\">\n";
  $body .= "          <span class=\"input-group-btn\">\n";
  $body .= "            <button type=\"submit\" class=\"btn btn-flat\"><i class=\"fa fa-search\"></i></button>\n";
  $body .= "          </span>\n";
  $body .= "        </div>\n";
  $body .= "      </form>\n";
  $body .= "      <ul class=\"sidebar-menu\" data-widget=\"tree\">\n";
  $body .= page_menu ( $menu);
  $body .= "      </ul>\n";
  $body .= "    </section>\n";
  $body .= "  </aside>\n";

  /**
   * Continue with the page
   */
  $body .= "  <div class=\"content-wrapper\">\n";
  $body .= "    <section class=\"content-header\">\n";
  $body .= "      <h1><span id=\"page_title\"></span><small><i><span id=\"page_subtitle\"></span></i></small></h1>\n";
  $body .= "      <ol class=\"breadcrumb\" id=\"page_breadcrumb\"></ol>\n";
  $body .= "    </section>\n";
  $body .= "    <section class=\"content\">\n";
  $body .= "      <div class=\"row\">\n";
  $body .= "        <div class=\"col-xs-12\">\n";
  $body .= "          <div id=\"content\" class=\"box\"></div>\n";
  $body .= "        </div>\n";
  $body .= "      </div>\n";
  $body .= "    </section>\n";
  $body .= "  </div>\n";
  $body .= "  <footer class=\"main-footer\">\n";
  if ( ! empty ( $_in["general"]["version"]))
  {
    $body .= "    <div class=\"pull-right hidden-xs\"><b>" . __ ( "Version") . "</b> " . $_in["general"]["version"] . "</div>\n";
  }
  $body .= "    <strong>" . __ ( "Developed by") . " <address><a href=\"mailto:azevedo@voipdomain.io\">Ernani Azevedo</a> (<a href=\"tel:+5551992425885\">+55 51 992425885</a>)</address>. " . sprintf ( __ ( "Published under %s license."), "<a href=\"https://www.gnu.org/licenses/gpl-3.0.en.html\" target=\"_blank\" rel=\"noopener\">GPLv3</a>") . "</strong>\n";
  $body .= "  </footer>\n";
  $body .= "</div>\n";

  // If debug was enabled, implement the debugging modal
  if ( $_in["general"]["debug"] == true)
  {
    $body .= "\n";
    $body .= "<div id=\"vd_debug\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"vd_debug\" aria-hidden=\"true\">\n";
    $body .= "  <div class=\"modal-dialog modal-lg\">\n";
    $body .= "    <div class=\"modal-content\">\n";
    $body .= "      <div class=\"modal-header\">\n";
    $body .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
    $body .= "        <h3 class=\"modal-title\">" . __ ( "VoIP Domain debugging") . "</h3>\n";
    $body .= "      </div>\n";
    $body .= "      <div class=\"modal-body\"></div>\n";
    $body .= "      <div class=\"modal-footer\">\n";
    $body .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
    $body .= "      </div>\n";
    $body .= "    </div>\n";
    $body .= "  </div>\n";
    $body .= "</div>\n";
  }

  /**
   * Prepare page javascript codes
   */
  $footer = "\n";

  // jQuery and jQuery Loader are mandatory
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/jquery/dist/jquery" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/jquery-loader/jquery.loader" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";

  // Add builtin code
  $footer .= "<script type=\"text/javascript\" class=\"jsonload\" nonce=\"" . $_in["general"]["nonce"] . "\">\n";
  $footer .= "  $(document).ready ( function ()\n";
  $footer .= "  {\n";

  // If debug was enabled, implement the debugging response parser method and CSP events dump
  if ( $_in["general"]["debug"] == true)
  {
    $footer .= "    document.dump_vd_debug = function ( request)\n";
    $footer .= "    {\n";
    $footer .= "      console.log ( request);\n";
    $footer .= "      display = '<strong>Endpoint</strong>: ' + request.endpoint + '<br />';\n";
    $footer .= "      display += '<strong>Route</strong>: ' + request.route + '<br />';\n";
    $footer .= "      display += '<strong>Data</strong>: <span id=\"vd_debug_data\"></span><br />';\n";
    $footer .= "      display += '<strong>Sent headers</strong>: <span id=\"vd_debug_sentheaders\"></span><br />';\n";
    $footer .= "      display += '<strong>Received headers</strong>: <span id=\"vd_debug_receivedheaders\"></span><br />';\n";
    $footer .= "      display += '<strong>Response</strong>: ' + request.result.API.statusText + ' (' + request.result.API.statusCode + ')<br />';\n";
    $footer .= "      if ( request.result.API.content.search ( /__X_VD_DEBUG__/))\n";
    $footer .= "      {\n";
    $footer .= "        display += '<strong>Backtrace</strong>:<br />';\n";
    $footer .= "        content = request.result.API.content.replace ( /.*__X_VD_DEBUG__/gm, '');\n";
    $footer .= "        backtrace = JSON.parse ( content.substring ( content.search ( /:/) + 1));\n";
    $footer .= "        counter = 1;\n";
    $footer .= "        for ( const property in backtrace)\n";
    $footer .= "        {\n";
    $footer .= "          display += '&nbsp;<strong>' + counter++ + '</strong>) ' + ( backtrace[property].hasOwnProperty ( 'file') ? backtrace[property].file + ' (line ' + backtrace[property].line + ')' : '[internal function]') + ': ' + backtrace[property].function + ' (<span id=\"vd_debug_' + property + '\"></span>)<br />';\n";
    $footer .= "        }\n";
    $footer .= "      }\n";
    $footer .= "      if ( request.result.API.content.search ( /__X_VD_DEBUG__/) != 1)\n";
    $footer .= "      {\n";
    $footer .= "        display += '<strong>Content</strong>: <textarea readonly=\"readonly\">' + request.result.API.content.substring ( 0, request.result.API.content.search ( /__X_VD_DEBUG__/)) + '</textarea>';\n";
    $footer .= "      }\n";
    $footer .= "      $('#vd_debug').find ( '.modal-body').html ( display);\n";
    $footer .= "      $('#vd_debug').modal ( 'show');\n";
    $footer .= "      $('#vd_debug_data').jsonViewer ( request.data, { collapsed: true, withQuotes: true});\n";
    $footer .= "      $('#vd_debug_sentheaders').jsonViewer ( request.sentHeaders, { collapsed: true, withQuotes: true});\n";
    $footer .= "      headers = request.result.API.headers.replace ( /(\\r\\n|\\n|\\r)/gm, '\\n').split ( '\\n');\n";
    $footer .= "      var obj = {};\n";
    $footer .= "      for ( const property in headers)\n";
    $footer .= "      {\n";
    $footer .= "        if ( headers[property] != '')\n";
    $footer .= "        {\n";
    $footer .= "          split = headers[property].split ( ':');\n";
    $footer .= "          obj[split[0].trim ()] = split[1].trim ();\n";
    $footer .= "        }\n";
    $footer .= "      }\n";
    $footer .= "      $('#vd_debug_receivedheaders').jsonViewer ( obj, { collapsed: true, withQuotes: true});\n";
    $footer .= "      if ( request.result.API.content.search ( /__X_VD_DEBUG__/))\n";
    $footer .= "      {\n";
    $footer .= "        for ( const property in backtrace)\n";
    $footer .= "        {\n";
    $footer .= "          $('#vd_debug_' + property).jsonViewer ( backtrace[property].args, { collapsed: true, withQuotes: true});\n";
    $footer .= "        }\n";
    $footer .= "      }\n";
    $footer .= "    };\n";
    $footer .= "\n";
    $footer .= "    if ( 'SecurityPolicyViolationEvent' in window)\n";
    $footer .= "    {\n";
    $footer .= "      document.addEventListener ( 'securitypolicyviolation', ( e) => {\n";
    $footer .= "        console.log ( 'VoIP Domain - CSP violation alert:');\n";
    $footer .= "        console.log ( e);\n";
    $footer .= "      });\n";
    $footer .= "    } else {\n";
    $footer .= "      console.log ( 'VoIP Domain - SecurityPolicyViolationEvent unsupported!');\n";
    $footer .= "    }\n";
    $footer .= "\n";
  }

  // Load all interface framework JavaScript and CSS files
  $footer .= "    $('html, body').css ( 'overflow', 'hidden');\n";
  $footer .= "    $.loader (\n";
  $footer .= "    {\n";
  $footer .= "      js: [\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'bootstrap',\n";
  $footer .= "              'src': '/vendors/bootstrap/js/bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 39680 : 75484) . ",\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'slimscroll',\n";
  $footer .= "              'src': '/vendors/jquery-slimscroll/jquery.slimscroll" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 4724 : 13832) . ",\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'history',\n";
  $footer .= "              'src': '/vendors/history/history" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 8205 : 38828) . ",\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'select2',\n";
  $footer .= "              'src': '/vendors/select2/dist/js/select2.full" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 79212 : 173566) . ",\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'select2-i18n',\n";
  $footer .= "              'src': '/vendors/select2/dist/js/i18n/pt-BR" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 872 : 876) . ",\n";
  $footer .= "              'dep': [ 'select2']\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'pnotify',\n";
  $footer .= "              'src': '/vendors/pnotify/dist/pnotify" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 13068 : 13203) . ",\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'spin',\n";
  $footer .= "              'src': '/vendors/spin/spin-module" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'type': 'module',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 4277 : 11607) . ",\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'ladda',\n";
  $footer .= "              'src': '/vendors/ladda/js/ladda-module" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'type': 'module',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 5040 : 11755) . ",\n";
  $footer .= "              'dep': [ 'spin']\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'driver.js',\n";
  $footer .= "              'src': '/vendors/driver.js/dist/driver" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 46930 : 46930) . ",\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'AdminLTE',\n";
  $footer .= "              'src': '/js/adminlte" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 13611 : 29749) . ",\n";
  $footer .= "              'dep': [ 'bootstrap', 'slimscroll']\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'moment',\n";
  $footer .= "              'src': '/vendors/moment/min/moment-with-locales" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 369019 : 622045) . ",\n";
  $footer .= "              'dep': [ 'bootstrap'],\n";
  $footer .= "              'onload': function ()\n";
  $footer .= "                        {\n";
  $footer .= "                          moment.locale ( '";
  switch ( $_in["general"]["language"])
  {
    case "en_US":
      $footer .= "en-us";
      break;
    case "pt_BR":
      $footer .= "pt-br";
      break;
  }
  $footer .= "');\n";
  $footer .= "                        }\n";
  $footer .= "            },\n";
  if ( $_in["general"]["debug"] == true)
  {
    $footer .= "            {\n";
    $footer .= "              'name': 'jsonViewer',\n";
    $footer .= "              'src': '/vendors/jquery-json-viewer/json-viewer/jquery.json-viewer" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
    $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 2976 : 5834) . ",\n";
    $footer .= "              'dep': []\n";
    $footer .= "            },\n";
  }
  $footer .= "            {\n";
  $footer .= "              'name': 'voipdomain',\n";
  $footer .= "              'src': '/js/voipdomain" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'weight': " . ( $_in["general"]["debug"] === false ? 29915 : 57953) . ",\n";
  $footer .= "              'dep': [ 'AdminLTE', 'history', 'select2'],\n";
  $footer .= "              'onload': function ()\n";
  $footer .= "                        {\n";
  $intobjects = (array) filters_call ( "objects_types");
  foreach ( $intobjects as $object)
  {
    $footer .= "                          VoIP.interface.addObject ( { 'object': '" . addslashes ( $object["object"]) . "', 'path': '" . addslashes ( $object["path"]) . "', 'type': '" . addslashes ( $object["type"]) . "', 'icon': '" . addslashes ( $object["icon"]) . "', 'label': '" . addslashes ( $object["label"]) . "', 'text': { 'singular': '" . addslashes ( $object["text"]["singular"]) . "', 'plural': '" . addslashes ( $object["text"]["plural"]) . "'}});\n";
  }
  $footer .= "                        }\n";
  $footer .= "            }\n";
  $footer .= "          ],\n";
  $footer .= "      css: [\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'bootstrap',\n";
  $footer .= "               'src': '/vendors/bootstrap/css/bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 121457 : 145933) . ",\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'select2',\n";
  $footer .= "               'src': '/vendors/select2/dist/css/select2" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 14966 : 17358) . ",\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'select2-bootstrap',\n";
  $footer .= "               'src': '/vendors/select2-bootstrap-theme/dist/select2-bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 16792 : 23192) . ",\n";
  $footer .= "               'dep': [ 'bootstrap', 'select2', 'AdminLTE']\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'driver.js',\n";
  $footer .= "               'src': '/vendors/driver.js/dist/driver" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 4309 : 4309) . ",\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'pnotify',\n";
  $footer .= "               'src': '/vendors/pnotify/dist/pnotify" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 2075 : 2074) . ",\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'ladda',\n";
  $footer .= "               'src': '/vendors/ladda/dist/ladda-themeless" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 7795 : 10042) . ",\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'font-awesome',\n";
  $footer .= "               'src': '/vendors/font-awesome/css/all" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 59305 : 73577) . ",\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'ionicons',\n";
  $footer .= "               'src': '/vendors/ionicons/docs/css/ionicons" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 45219 : 45219) . ",\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'AdminLTE',\n";
  $footer .= "               'src': '/css/AdminLTE" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 106548 : 127585) . ",\n";
  $footer .= "               'dep': [ 'bootstrap', 'font-awesome', 'ionicons']\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'adminlte-all-skins',\n";
  $footer .= "               'src': '/css/skins/_all-skins" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 41635 : 48473) . ",\n";
  $footer .= "               'dep': [ 'AdminLTE']\n";
  $footer .= "             },\n";
  if ( $_in["general"]["debug"] == true)
  {
    $footer .= "            {\n";
    $footer .= "              'name': 'jsonViewer',\n";
    $footer .= "              'src': '/vendors/jquery-json-viewer/json-viewer/jquery.json-viewer" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
    $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 680 : 1080) . ",\n";
    $footer .= "              'dep': []\n";
    $footer .= "            },\n";
  }
  $footer .= "             {\n";
  $footer .= "               'name': 'system',\n";
  $footer .= "               'src': '/css/system" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'weight': " . ( $_in["general"]["debug"] === false ? 12622 : 17536) . ",\n";
  $footer .= "               'dep': [ 'AdminLTE', 'bootstrap', 'select2']\n";
  $footer .= "             }\n";
  $footer .= "           ],\n";
  $footer .= "      onrefresh: function ( loaded, total, percent)\n";
  $footer .= "                 {\n";
  $footer .= "                   $('.percent').animate ( { width: percent + '%'}, 50);\n";
  $footer .= "                 },\n";
  $footer .= "      onfinish: function ()\n";
  $footer .= "                {\n";

  /**
   * Call page load onfinish hook's
   */
  if ( framework_has_hook ( "pageload_onfinish"))
  {
    $footer .= framework_call ( "pageload_onfinish", array ());
  }

  /**
   * Display jQuery loader order into console if debugging enabled
   */
  if ( $_in["general"]["debug"] == true)
  {
    $footer .= "                  console.log ( 'jQuery.loader order:');\n";
    $footer .= "                  console.log ( $.loader.order);\n";
  }

  /**
   * Set system variables
   */
  $footer .= "                  VoIP.settings (\n";
  $footer .= "                  {\n";
  $footer .= "                    title: '" . addslashes ( strip_tags ( $_in["general"]["title"])) . "',\n";
  $footer .= "                    user: '" . addslashes ( strip_tags ( $_in["session"]["Username"])) . "',\n";
  $footer .= "                    name: '" . addslashes ( strip_tags ( $_in["session"]["Name"])) . "',\n";
  $footer .= "                    uid: '" . addslashes ( strip_tags ( $_in["session"]["ID"])) . "',\n";
  $footer .= "                    nonce: '" . addslashes ( strip_tags ( $_in["general"]["nonce"])) . "',\n";
  $footer .= "                    defaultcurrency: '" . addslashes ( strip_tags ( $_in["general"]["defaultcurrency"])) . "'\n";
  $footer .= "                  });\n";

  /**
   * Set internationalization parameters
   */
  $i18n = i18n_dump ( ( ! empty ( $_in["general"]["language"]) ? $_in["general"]["language"] : "en_US"), "I18N_GLOBAL");
  foreach ( $i18n as $string1 => $string2)
  {
    $footer .= "                  VoIP.i18n.add ( '" . str_replace ( "'", "\'", str_replace ( "\\", "\\\\", $string1)) . "', '" . str_replace ( "'", "\'", str_replace ( "\\", "\\\\", $string2)) . "');\n";
  }

  /**
   * Display content
   */
  $footer .= "                  $('html, body').css ( 'overflow', '');\n";
  $footer .= "                  $('.percent').fadeOut ( 'slow').prependTo ( 'div.content-wrapper');\n";
  $footer .= "                  $('.loader').fadeOut ( 'slow');\n";
  $footer .= "                  VoIP.path.call ( document.location.href.replace ( /^.*\/\/[^\/]+/, ''), false);\n";
  $footer .= "                }\n";
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
framework_add_hook ( "call_report_page_generate", "call_report_page_generate", IN_HOOK_INSERT_FIRST);

/**
 * Function to create the call report page.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function call_report_page_generate ( $buffer, $parameters)
{
  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "sequence-diagrams", "src" => "/vendors/js-sequence-diagrams/dist/sequence-diagram.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "webfontloader", "src" => "/vendors/webfontloader/webfontloader.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "Snap.svg", "src" => "/vendors/Snap.svg/dist/snap.svg.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "underscore", "src" => "/vendors/underscore/underscore.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "sequence-diagrams", "src" => "/vendors/js-sequence-diagrams/dist/sequence-diagram.js", "dep" => array ( "webfontloader", "Snap.svg", "underscore")));

  /**
   * Create page code
   */
  if ( $parameters["Filters"])
  {
    $buffer .= "<div class=\"container\">\n";
    if ( $parameters["FiltersOverwrite"])
    {
      $buffer .= $parameters["Filters"];
    } else {
      $buffer .= "  <form id=\"filters\">\n";
      $buffer .= "  <div class=\"col-xs-12\">\n";
      $buffer .= "    <div class=\"col-md-3\">\n";
      $buffer .= "      <div class=\"form-group\">\n";
      $buffer .= "        <div class=\"input-group date\">\n";
      $buffer .= "          <input name=\"Start\" id=\"start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
      $buffer .= "          <div class=\"input-group-btn\">\n";
      $buffer .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
      $buffer .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
      $buffer .= "          </div>\n";
      $buffer .= "        </div>\n";
      $buffer .= "      </div>\n";
      $buffer .= "    </div>\n";
      $buffer .= "    <div class=\"col-md-3\">\n";
      $buffer .= "      <div class=\"form-group\">\n";
      $buffer .= "        <div class=\"input-group date\">\n";
      $buffer .= "          <input name=\"End\" id=\"end\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
      $buffer .= "          <div class=\"input-group-btn\">\n";
      $buffer .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
      $buffer .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
      $buffer .= "          </div>\n";
      $buffer .= "        </div>\n";
      $buffer .= "      </div>\n";
      $buffer .= "    </div>\n";
      $buffer .= $parameters["Filters"];
      $buffer .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n";
      $buffer .= "  </div>\n";
      $buffer .= "  </form>\n";
    }
    $buffer .= "</div>\n";
  }
  $buffer .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\"><tfoot><th colspan=\"4\">" . __ ( "Totals") . "</th><th colspan=\"2\"></th><th colspan=\"4\"></th></tfoot></table>\n";

  /**
   * Call details modal
   */
  $buffer .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $buffer .= "  <div class=\"modal-dialog modal-lg\">\n";
  $buffer .= "    <div class=\"modal-content\">\n";
  $buffer .= "      <div class=\"modal-header\">\n";
  $buffer .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $buffer .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $buffer .= "      </div>\n";
  $buffer .= "      <div class=\"modal-body\">\n";
  $buffer .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $buffer .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $buffer .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $buffer .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $buffer .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $buffer .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $buffer .= "        </ul>\n";
  $buffer .= "        <div class=\"tab-content\"><br />\n";

  // Basic information tab
  $buffer .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add source number
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add destination number
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add date and time
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add duration
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add billing duration
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add call result
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add cost
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add call type
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add cost center
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add gateway
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add who hung up
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_whohungup\" class=\"control-label col-xs-2\">" . __ ( "Who hung up") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <select name=\"whohungup\" id=\"call_view_whohungup\" class=\"form-control\" data-placeholder=\"" . __ ( "Who hung up") . "\" disabled=\"disabled\">\n";
  $buffer .= "                  <option value=\"\"></option>\n";
  $buffer .= "                  <option value=\"Caller\">" . __ ( "Caller") . "</option>\n";
  $buffer .= "                  <option value=\"Called\">" . __ ( "Called") . "</option>\n";
  $buffer .= "                </select>\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add codecs
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_codec_native\" class=\"control-label col-xs-2\">" . __ ( "Codecs") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10 inputGroupContainer\" style=\"display: flex\">\n";
  $buffer .= "                <div class=\"col-xs-4 input-group\">\n";
  $buffer .= "                  <input type=\"text\" name=\"codec_native\" class=\"form-control\" id=\"call_view_codec_native\" placeholder=\"" . __ ( "Native codec") . "\" disabled=\"disabled\" />\n";
  $buffer .= "                  <span class=\"input-group-addon input-icon-button btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Native codec") . "\"><i class=\"fas fa-wave-square\"></i></span>\n";
  $buffer .= "                </div>\n";
  $buffer .= "                <div class=\"col-xs-4 input-group\">\n";
  $buffer .= "                  <input type=\"text\" name=\"codec_read\" class=\"form-control\" id=\"call_view_codec_read\" placeholder=\"" . __ ( "Read codec") . "\" disabled=\"disabled\" />\n";
  $buffer .= "                  <span class=\"input-group-addon input-icon-button btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Read codec") . "\"><i class=\"fas fa-sign-in-alt\"></i></span>\n";
  $buffer .= "                </div>\n";
  $buffer .= "                <div class=\"col-xs-4 input-group\">\n";
  $buffer .= "                  <input type=\"text\" name=\"codec_write\" class=\"form-control\" id=\"call_view_codec_write\" placeholder=\"" . __ ( "Write codec") . "\" disabled=\"disabled\" />\n";
  $buffer .= "                  <span class=\"input-group-addon input-icon-button btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Write codec") . "\"><i class=\"fas fa-sign-out-alt\"></i></span>\n";
  $buffer .= "                </div>\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";
  $buffer .= "          </div>\n";

  // QOS tab
  $buffer .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add called SSRC
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add received packets counter
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add sent packets counter
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add receive packet lost
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add sent packet lost
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add receive jitter
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add sent jitter
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";

  // Add RTT
  $buffer .= "            <div class=\"form-group\">\n";
  $buffer .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $buffer .= "              <div class=\"col-xs-10\">\n";
  $buffer .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $buffer .= "              </div>\n";
  $buffer .= "            </div>\n";
  $buffer .= "          </div>\n";

  // Call flow tab
  $buffer .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $buffer .= "            <div class=\"diagram\"></div>\n";
  $buffer .= "          </div>\n";

  // Download tab
  $buffer .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $buffer .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><i class=\"fa fa-paperclip\" aria-hidden=\"true\"></i> " . __ ( "PCAP") . "</button>\n";
  $buffer .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><i class=\"fa fa-copy\" aria-hidden=\"true\"></i> " . __ ( "Text") . "</button>\n";
  $buffer .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><i class=\"fa fa-headphones\" aria-hidden=\"true\"></i> " . __ ( "Audio") . "</button>\n";
  $buffer .= "          </div>\n";

  // Finish modal
  $buffer .= "        </div>\n";
  $buffer .= "        </form>\n";
  $buffer .= "      </div>\n";
  $buffer .= "      <div class=\"modal-footer\">\n";
  $buffer .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $buffer .= "      </div>\n";
  $buffer .= "    </div>\n";
  $buffer .= "  </div>\n";
  $buffer .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server,#call_view_type,#call_view_cc,#call_view_gateway,#call_view_whohungup').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
              $parameters["JS"]["Start"] .
              "$('#filter').on ( 'keyup', function ( e)\n" .
              "{\n" .
              "  $('#report').data ( 'dt').search ( $(this).val ()).draw ();\n" .
              "});\n" .
              "$('#filters .btn-clean').on ( 'click', function ()\n" .
              "{\n" .
              "  $('#report').data ( 'dt').search ( '').draw ();\n" .
              "});\n" .
              "$('#call_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#call_view_server').find ( 'option').remove ();\n" .
              "  if ( data.Server.ID != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.Server.ID + '\" selected=\"selected\">' + data.Server.Description + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.Server.ID).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( moment ( data.CallDates.Start.Datetime).isValid () ? moment ( data.CallDates.Start.Datetime).format ( 'L LTS') : '');\n" .
              "  $('#call_view_duration').val ( data.Duration);\n" .
              "  $('#call_view_billsec').val ( data.BillingTime);\n" .
              "  $('#call_view_disposition').val ( data.ResultI18N);\n" .
              "  $('#call_view_source').val ( data.Source.Number);\n" .
              "  $('#call_view_destination').val ( data.Destination.Number);\n" .
              "  $('#call_view_cost').val ( data.Value ? number_format ( data.Value, 2, '" . __ ( ".") . "', '" . __ ( ",") . "') : '" . __ ( "N/A") . "');\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.Destination.Type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.Destination.Type + '_' + data.Destination.Endpoint + '\" selected=\"selected\">' + VoIP.interface.objTextSingular ( data.Destination.Type + '_' + data.Destination.Endpoint) + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.Destination.Type + '_' + data.Destination.Endpoint).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.CostCenter.ID != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.CostCenter.ID + '\" selected=\"selected\">' + data.CostCenter.Description + ' (' + data.CostCenter.Code + ')</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.CostCenter.ID).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.Gateway.ID != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.Gateway.ID + '\" selected=\"selected\">' + data.Gateway.Description + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.Gateway.ID).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_whohungup').val ( data.WhoHungUp ? data.WhoHungUp : '').trigger ( 'change');\n" .
              "  if ( data.CODEC.Native)\n" .
              "  {\n" .
              "    $('#call_view_codec_native').val ( data.CODEC.Native);\n" .
              "  }\n" .
              "  if ( data.CODEC.Read)\n" .
              "  {\n" .
              "    $('#call_view_codec_read').val ( data.CODEC.Read);\n" .
              "  }\n" .
              "  if ( data.CODEC.Write)\n" .
              "  {\n" .
              "    $('#call_view_codec_write').val ( data.CODEC.Write);\n" .
              "  }\n" .
              "  if ( data.Quality.QOS.SSRC.Our)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.Quality.QOS.SSRC.Our);\n" .
              "    $('#call_view_qos_themssrc').val ( data.Quality.QOS.SSRC.Their);\n" .
              "    $('#call_view_qos_rxlp').val ( data.Quality.QOS.ReceivedPackets.Lost);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.Quality.QOS.ReceivedPackets.Jitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.Quality.QOS.ReceivedPackets.Packets);\n" .
              "    $('#call_view_qos_txjitter').val ( data.Quality.QOS.SentPackets.Jitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.Quality.QOS.SentPackets.Packets);\n" .
              "    $('#call_view_qos_txlp').val ( data.Quality.QOS.SentPackets.Lost);\n" .
              "    $('#call_view_qos_rtt').val ( data.Quality.QOS.RTT);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('a[href=\"#c_tab_siptrace\"],a[href=\"#c_tab_download\"]').addClass ( 'loading-tab');\n" .
              "  $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "  $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  $('.diagram').empty ();\n" .
              "  $('#call_view_pcap,#call_view_text,#call_view_audio').addClass ( 'disabled');\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "  if ( data.Captured)\n" .
              "  {\n" .
              "    var l1 = Ladda.create ( $('a[href=\"#c_tab_siptrace\"]')[0]);\n" .
              "    l1.start ();\n" .
              "    VoIP.rest ( '/calls/' + encodeURIComponent ( data.UniqueID) + '/sipdump', 'GET').done ( function ( result, textStatus, jqXHR)\n" .
              "    {\n" .
              "      if ( result.Result)\n" .
              "      {\n" .
              "        $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "        $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "        $('.diagram').html ( result.SIPDump.Diagram);\n" .
              "        $('#call_view_pcap').data ( 'content', result.SIPDump.PCAP).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap').removeClass ( 'disabled');\n" .
              "        $('#call_view_text').data ( 'content', result.SIPDump.Text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt').removeClass ( 'disabled');\n" .
              "      }\n" .
              "    }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "    {\n" .
              "      new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call information!") . "', type: 'error'});\n" .
              "    }).always ( function ()\n" .
              "    {\n" .
              "      l1.stop ();\n" .
              "    });\n" .
              "  }\n" .
              "  if ( data.Recorded)\n" .
              "  {\n" .
              "    var l2 = Ladda.create ( $('a[href=\"#c_tab_download\"]')[0]);\n" .
              "    l2.start ();\n" .
              "    VoIP.rest ( '/calls/' + encodeURIComponent ( data.UniqueID) + '/recording', 'GET').done ( function ( result, textStatus, jqXHR)\n" .
              "    {\n" .
              "      if ( result.Result)\n" .
              "      {\n" .
              "        $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "        $('#call_view_audio').data ( 'content', result.Audio).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').removeClass ( 'disabled');\n" .
              "      }\n" .
              "    }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "    {\n" .
              "      new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call information!") . "', type: 'error'});\n" .
              "    }).always ( function ()\n" .
              "    {\n" .
              "      l2.stop ();\n" .
              "    });\n" .
              "  }\n" .
              "  $('a[href=\"#c_tab_siptrace\"],a[href=\"#c_tab_download\"').removeClass ( 'loading-tab');\n" .
              "});\n" .
              "$('#call_view').on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( ! $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( ! $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( ! $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'audio/mpeg'}), $(this).data ( 'filename'));\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'CallDates', title: '" . __ ( "Date/Time") . "', width: '20%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return moment ( row.CallDates.Datetime).isValid () ? moment ( row.CallDates.Start.Datetime).format ( 'L LTS') : ''; } else { return row.CallDates.Start.Timestamp; }}},\n" .
              "             { data: 'Flow', title: '" . __ ( "Direction") . "', width: '5%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return row.Flow == 'in' ? '<i aria-hidden=\"true\" class=\"fa fa-cloud-download-alt\"></i> " . __ ( "In") . "' : '<i aria-hidden=\"true\" class=\"fa fa-cloud-upload-alt\"></i> " . __ ( "Out") . "'; }},\n" .
              "             { data: 'Source.Number', title: '" . __ ( "Source") . "', width: '15%', class: 'export all', render: function ( data, type, row, meta) { return '<span class=\"label label-' + VoIP.interface.objLabel ( row.Source.Type + '_' + row.Source.Endpoint) + '\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-value=\"' + row.Source.TypeI18N + '\" data-original-title=\"' + VoIP.interface.objTextSingular ( row.Source.Type + '_' + row.Source.Endpoint) + '\" title=\"\"><i class=\"fa fa-' + VoIP.interface.objIcon ( row.Source.Type + '_' + row.Source.Endpoint) + '\"></i></span>' + row.Source.Number; }},\n" .
              "             { data: 'Destination.Number', title: '" . __ ( "Destination") . "', width: '15%', class: 'export all', render: function ( data, type, row, meta) { return '<span class=\"label label-' + VoIP.interface.objLabel ( row.Destination.Type + '_' + row.Destination.Endpoint) + '\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-value=\"' + row.Destination.TypeI18N + '\" data-original-title=\"' + VoIP.interface.objTextSingular ( row.Destination.Type + '_' + row.Destination.Endpoint) + '\" title=\"\"><i class=\"fa fa-' + VoIP.interface.objIcon ( row.Destination.Type + '_' + row.Destination.Endpoint) + '\"></i></span>' + row.Destination.Number; }},\n" .
              "             { data: 'Duration', title: '" . __ ( "Duration") . "', width: '10%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return format_secs_to_string ( row.Duration); }},\n" .
              "             { data: 'ResultI18N', title: '" . __ ( "Result") . "', width: '15%', class: 'export min-tablet-l'},\n" .
              "             { data: 'Value', title: '" . __ ( "Cost") . "', width: '10%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return row.ValueI18N ? row.ValueI18N : '--'; }},\n" .
              "             { data: 'Quality', title: '" . __ ( "Quality") . "', width: '2%', class: 'export min-tablet-l', render: function ( data, type, row, meta)\n" .
              "                                                                                                                     {\n" .
              "                                                                                                                       if ( type === 'display' || type === 'filter')\n" .
              "                                                                                                                       {\n" .
              "                                                                                                                         var tmp = '';\n" .
              "                                                                                                                         if ( row.Quality.QOS.SSRC.Our)\n" .
              "                                                                                                                         {\n" .
              "                                                                                                                           tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + number_format ( row.Quality.QOS.ReceivedPackets.LostPercentage, 2, '" . __ ( ".") . "', '" . __ ( ",") . "') + '% " . __ ( "rx") . " / ' + number_format ( row.Quality.QOS.SentPackets.LostPercentage, 2, '" . __ ( ".") . "', '" . __ ( ",") . "') + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + number_format ( row.Quality.QOS.ReceivedPackets.Jitter, 2, '" . __ ( ".") . "', '" . __ ( ",") . "') + ' ms " . __ ( "rx") . " / ' + number_format ( row.Quality.QOS.SentPackets.Jitter, 2, '" . __ ( ".") . "', '" . __ ( ",") . "') + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + number_format ( row.Quality.QOS.RTT, 2, '" . __ ( ".") . "', '" . __ ( ",") . "') + ' ms\">';\n" .
              "                                                                                                                         }\n" .
              "                                                                                                                         if ( row.Quality.MOS)\n" .
              "                                                                                                                         {\n" .
              "                                                                                                                           tmp += '<span class=\"label label-' + ( row.Quality.Color == 'red' ? 'warning' : 'success') + '\">' + number_format ( row.Quality.MOS, 2, '" . __ ( ".") . "', '" . __ ( ",") . "') + '</span>';\n" .
              "                                                                                                                         } else {\n" .
              "                                                                                                                           tmp += '<span class=\"label label-default\">" . __ ( "N/A") . "</span>';\n" .
              "                                                                                                                         }\n" .
              "                                                                                                                         if ( row.Quality.QOS.SSRC.Our)\n" .
              "                                                                                                                         {\n" .
              "                                                                                                                           tmp += '</abbr>';\n" .
              "                                                                                                                         }\n" .
              "                                                                                                                         return tmp;\n" .
              "                                                                                                                       } else {\n" .
              "                                                                                                                         return row.Quality.MOS ? row.Quality.MOS : '';\n" .
              "                                                                                                                       }\n" .
              "                                                                                                                     }},\n" .
              "             { data: 'Details', title: '" . __ ( "Observations") . "', width: '6%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/extensions/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Report") . "\" role=\"button\" title=\"\" href=\"/extensions/' + row.ID + '/report#' + btoa ( JSON.stringify ( { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''})) + '\"><i class=\"fa fa-list\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/extensions/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-extension=\"' + row.Number + '\" data-name=\"' + row.Description + '\"><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false},\n" .
              "             { data: 'NULL', title: '', width: '2%', class: 'all', render: function ( data, type, row, meta) { return '<button class=\"btn btn-xs btn-info btn-call_view\" data-call=\"' + btoa ( JSON.stringify ( row)) + '\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Call details") . "\" role=\"button\" title=\"\"><i class=\"fa fa-search\"></i></button>';}, orderable: false, searchable: false}\n" .
              "           ],\n" .
              "  order: [[ 0, 'asc' ]],\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>',\n" .
              "  footerCallback: function ( row, data, start, end, display)\n" .
              "                  {\n" .
              "                    var api = this.api (), duration = 0, cost = 0;\n" .
              "\n" .
              "                    if ( ! $('#report').data ( 'dt'))\n" .
              "                    {\n" .
              "                      return;\n" .
              "                    }\n" .
              "\n" .
              "                    var data = $('#report').data ( 'dt').table ().rows ( { filter: 'applied'}).data ();\n" .
              "                    for ( var x = 0; x < data.length; x++)\n" .
              "                    {\n" .
              "                      duration += data[x].Duration;\n" .
              "                      cost += data[x].Value;\n" .
              "                    }\n" .
              "\n" .
              "                    $(api.column ( 5).footer ()).html ( format_secs_to_string ( duration));\n" .
              "                    $(api.column ( 7).footer ()).html ( number_format ( cost, 2, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "                  }\n" .
              "}));\n" .
              "$('#start').datetimepicker ( { useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#end').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "});\n" .
              "$('#end').datetimepicker ( { useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#start').data ( 'DateTimePicker').maxDate ( e.date);\n" .
              "});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#report').on ( 'click', 'button.btn-call_view', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  try\n" .
              "  {\n" .
              "    var data = JSON.parse ( atob ( $(this).data ( 'call')));\n" .
              "  } catch ( e) {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call information!") . "', type: 'error'});\n" .
              "    return;\n" .
              "  }\n" .
              "  $('#call_view_form').trigger ( 'fill', data);\n" .
              "});\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "    $('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY LTS") . "')).focus ();\n" .
              $parameters["JS"]["StartFilters"] .
              "    $('#report').data ( 'dt').clear ().draw ().responsive.recalc ();\n" .
              "    $('#" . ( $parameters["JS"] && $parameters["JS"]["Focus"] ? $parameters["JS"]["Focus"] : "end") . "').focus ();\n" .
              "    return;\n" .
              "  }\n" .
              "  if ( data && 'Start' in data)\n" .
              "  {\n" .
              "    $('#start').val ( moment ( data.Start).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "  }\n" .
              "  if ( data && 'End' in data)\n" .
              "  {\n" .
              "    $('#end').val ( moment ( data.End).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "  }\n" .
              $parameters["JS"]["Filters"] .
              "  if ( $('#start').val () == '')\n" .
              "  {\n" .
              "    $('#start').alerts ( 'add', { message: '" . __ ( "The start date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#end').val () == '')\n" .
              "  {\n" .
              "    $('#end').alerts ( 'add', { message: '" . __ ( "The finish date is required.") . "'});\n" .
              "  }\n" .
              $parameters["JS"]["CheckFilters"] .
              "  if ( " . ( $parameters["JS"]["CheckIf"] ? $parameters["JS"]["CheckIf"] : "$('#start').val () == '' || $('#end').val () == ''") . ")\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { data: " . ( $parameters["HashForm"] ? $parameters["HashForm"] : "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}") . "});\n" .
              "  var table = $('#report').data ( 'dt');\n" .
              "  table.processing ( true);\n" .
              "  VoIP.rest ( " . $parameters["Endpoint"]["URL"] . ", '" . $parameters["Endpoint"]["Method"] . "', " . $parameters["Endpoint"]["Parameters"] . ").done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    table.clear ();\n" .
              "    for ( var x in data)\n" .
              "    {\n" .
              "      data[x].Details = '';\n" .
              "      data[x].NULL = '';\n" .
              $parameters["DataFilter"] .
              "    }\n" .
              "    table.rows.add ( data);\n" .
              "    table.draw ().responsive.recalc ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Calls report") . "', text: '" . __ ( "Error processing calls request.") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    table.processing ( false);\n" .
              "    $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "  });\n" .
              "});\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  /**
   * Return page buffer
   */
  return $buffer;
}

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_hook ( "login_page_generate", "login_page_generate", IN_HOOK_INSERT_FIRST);

/**
 * Function to create the main login page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function login_page_generate ( $buffer, $parameters)
{
  global $_in;

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
  $body .= "  <div class=\"auth-block\">\n";
  $body .= "    <div class=\"auth-top\">\n";
  $body .= "      <h1>" . ( ! empty ( $_in["general"]["title"]) ? strip_tags ( $_in["general"]["title"]) : "") . "</h1>\n";
  $body .= "      <h2>" . __ ( "Authentication") . "</h2>\n";
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
  $footer .= "    image.src = '/img/bg-login.jpg';\n";
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
  $footer .= "        data: JSON.stringify ( { Username: $('#log_" . $usernamefieldname . "').val (), Password: $('#log_" . $passwordfieldname . "').val (), Code: $('#log_" . $totpfieldname . "').val ().replace ( / /g, ''), Remember: $('#log_remember').prop ( 'checked')}),\n";
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
 * Add install framework hooks, with the relative function.
 */
framework_add_hook ( "install_page_generate", "install_page_generate", IN_HOOK_INSERT_FIRST);

/**
 * Function to create the installation page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function install_page_generate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Loading message
   */
  $loading = __ ( "LOADING");

  /**
   * Generate random variable names (avoid browser cache)
   */
  $dbaddr = random_password ();
  $dbuser = random_password ();
  $dbpass = random_password ();

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
    $head .= "  <title>" . addslashes ( strip_tags ( $_in["general"]["title"])) . " - " . __ ( "Installation") . "</title>\n";
  } else {
    $head .= "  <title>" . __ ( "Installation") . "</title>\n";
  }
  $head .= framework_call ( "template_header_tags");
  $head .= "  <style type=\"text/css\" nonce=\"" . $_in["general"]["nonce"] . "\">\n";
  $head .= "    .no-js .loader\n";
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
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/vendors/bootstrap/css/bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/css/install" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";

  // Add system icon
  $head .= "  <link rel=\"icon\" type=\"" . mime_content_type ( dirname ( __FILE__) . "/../../img/phone.png") . "\" href=\"/img/phone.png\">\n";

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
  $body = "<body>\n";
  $body .= "  <div class=\"loader\">\n";
  $body .= "    <div class=\"loader-animation\">\n";
  for ( $x = strlen ( $loading) - 1; $x >= 0; $x--)
  {
    $body .= "      <div>" . substr ( $loading, $x, 1) . "</div>\n";
  }
  $body .= "    </div>\n";
  $body .= "  </div>\n";
  $body .= "  <div id=\"page1\" class=\"install-block\">\n";
  $body .= "    <div class=\"install-top\">\n";
  $body .= "      <h1>" . ( ! empty ( $_in["general"]["title"]) ? strip_tags ( $_in["general"]["title"]) : "") . "</h1>\n";
  $body .= "      <h2>" . __ ( "Installation") . "</h2>\n";
  $body .= "      <br />\n";
  $body .= "      <p>" . __ ( "Welcome to VoIP Domain installation. The system will perform a permissions check and will ask for database credentials to configure the system.") . "</p>\n";
  $body .= "      <br /><br />\n";
  $body .= "    </div>\n";
  $body .= "    <button type=\"button\" id=\"button1\" class=\"ladda-button\" data-style=\"zoom-in\">" . __ ( "Start installation") . "</button>\n";
  $body .= "    <div class=\"install-bottom\"></div>\n";
  $body .= "  </div>\n";
  $body .= "  <div id=\"page2\" style=\"opacity: 0;left: 100%\" class=\"install-block\">\n";
  $body .= "    <div class=\"install-top\">\n";
  $body .= "      <h1>" . ( ! empty ( $_in["general"]["title"]) ? strip_tags ( $_in["general"]["title"]) : "") . "</h1>\n";
  $body .= "      <h2>" . __ ( "Installation") . "</h2>\n";
  $body .= "      <br />\n";
  $body .= "      <p>" . __ ( "Provide the MySQL database server credentials below:") . "</p>\n";
  $body .= "      <form role=\"presentation\" autocomplete=\"off\" action=\"\">\n";
  $body .= "        <li><input id=\"" . $dbaddr . "\" name=\"" . $dbaddr . "\" type=\"text\" placeholder=\"" . __ ( "Database address") . "\" spellcheck=\"false\" autocapitalize=\"off\" autocorrect=\"off\" autocomplete=\"new-text\" /><i class=\"icon server\"></i></li>\n";
  $body .= "        <li><input id=\"" . $dbuser . "\" name=\"" . $dbuser . "\" type=\"text\" placeholder=\"" . __ ( "Super-user name") . "\" spellcheck=\"false\" autocapitalize=\"off\" autocorrect=\"off\" autocomplete=\"new-text\" /><i class=\"icon user\"></i></li>\n";
  $body .= "        <li><input id=\"" . $dbpass . "\" name=\"" . $dbpass . "\" type=\"password\" placeholder=\"" . __ ( "Super-user password") . "\" spellcheck=\"false\" autocapitalize=\"off\" autocorrect=\"off\" autocomplete=\"new-password\" /><i class=\"icon lock\"></i></li>\n";
  $body .= "      </form>\n";
  $body .= "    </div>\n";
  $body .= "    <button type=\"button\" id=\"button2\" class=\"ladda-button\" data-style=\"zoom-in\">" . __ ( "Deploy database") . "</button>\n";
  $body .= "    <div class=\"install-bottom\"></div>\n";
  $body .= "  </div>\n";
  $body .= "  <div id=\"page3\" style=\"opacity: 0;left: 100%\" class=\"install-block\">\n";
  $body .= "    <div class=\"install-top\">\n";
  $body .= "      <h1>" . ( ! empty ( $_in["general"]["title"]) ? strip_tags ( $_in["general"]["title"]) : "") . "</h1>\n";
  $body .= "      <h2>" . __ ( "Installation") . "</h2>\n";
  $body .= "      <br />\n";
  $body .= "      <p>" . __ ( "System installed successfully!") . "</p>\n";
  $body .= "      <br />\n";
  $body .= "      <p>" . __ ( "The default administrator username is 'admin' and passtrord 'admin'.", true, false) . "</p>\n";
  $body .= "      <br /><br />\n";
  $body .= "    </div>\n";
  $body .= "    <button type=\"button\" id=\"button3\">" . __ ( "Go to VoIP Domain") . "</button>\n";
  $body .= "    <div class=\"install-bottom\"></div>\n";
  $body .= "  </div>\n";
  $body .= "  <div class=\"install-dots\">\n";
  $body .= "    <span id=\"dot1\" class=\"dot\" style=\"background-color: #fff\"></span>\n";
  $body .= "    <span id=\"dot2\" class=\"dot\"></span>\n";
  $body .= "    <span id=\"dot3\" class=\"dot\"></span>\n";
  $body .= "  </div>\n";
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
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/jquery-color/dist/jquery.color" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/bootstrap/js/bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";

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
  $footer .= "    };\n";
  $footer .= "    image.src = '/img/bg-install.jpg';\n";
  $footer .= "    var l1 = Ladda.create ( $('#button1')[0]);\n";
  $footer .= "    $('#button1').on ( 'click', function ( e)\n";
  $footer .= "    {\n";
  $footer .= "      e && e.preventDefault ();\n";
  $footer .= "      l1.start ();\n";
  $footer .= "      setTimeout ( function ()\n";
  $footer .= "      {\n";
  $footer .= "        $.ajax (\n";
  $footer .= "        {\n";
  $footer .= "          type: 'GET',\n";
  $footer .= "          url: '/api/install/check',\n";
  $footer .= "          headers: {\n";
  $footer .= "                     'X-HTTP-Method-Override': 'GET',\n";
  $footer .= "                     'Accept': 'application/json'\n";
  $footer .= "                   },\n";
  $footer .= "          contentType: 'application/json; charset=utf-8',\n";
  $footer .= "          dataType: 'json',\n";
  $footer .= "          complete: function ( jqXHR, textStatus)\n";
  $footer .= "                    {\n";
  $footer .= "                      if ( jqXHR.status == 200)\n";
  $footer .= "                      {\n";
  $footer .= "                        try\n";
  $footer .= "                        {\n";
  $footer .= "                          var data = JSON.parse ( jqXHR.responseText);\n";
  $footer .= "                        } catch ( e) {\n";
  $footer .= "                          var data = {};\n";
  $footer .= "                        }\n";
  $footer .= "                        if ( data.Result == true)\n";
  $footer .= "                        {\n";
  $footer .= "                          $('#page1').animate ( { 'left': '-100%', 'opacity': 0}, 'slow');\n";
  $footer .= "                          $('#page2').animate ( { 'left': '0', 'opacity': '100%'}, 'slow');\n";
  $footer .= "                          $('#dot1').animate ( { 'background-color': '#aaaaaa'}, 'slow');\n";
  $footer .= "                          $('#dot2').animate ( { 'background-color': '#ffffff'}, 'slow');\n";
  $footer .= "                          $('#" . $dbaddr . "').focus ();\n";
  $footer .= "                        } else {\n";
  $footer .= "                          if ( data.hasOwnProperty ( 'Description'))\n";
  $footer .= "                          {\n";
  $footer .= "                            new PNotify ( { title: '" . __ ( "Installation") . "', text: data.Description, type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});\n";
  $footer .= "                          }\n";
  $footer .= "                        }\n";
  $footer .= "                      } else {\n";
  $footer .= "                        new PNotify ( { title: '" . __ ( "Installation") . "', text: '" . __ ( "Error checking system.") . "', type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});\n";
  $footer .= "                      }\n";
  $footer .= "                      l1.stop ();\n";
  $footer .= "                      return;\n";
  $footer .= "                    }\n";
  $footer .= "      })}, 500);\n";
  $footer .= "    });\n";
  $footer .= "    var l2 = Ladda.create ( $('#button2')[0]);\n";
  $footer .= "    $('#button2').on ( 'click', function ( e)\n";
  $footer .= "    {\n";
  $footer .= "      e && e.preventDefault ();\n";
  $footer .= "      l2.start ();\n";
  $footer .= "      $('#page2').find ( 'input.alert-danger').each ( function () { $(this).removeClass ( 'alert-danger').popover ( 'hide'); });\n";
  $footer .= "      setTimeout ( function ()\n";
  $footer .= "      {\n";
  $footer .= "        $.ajax (\n";
  $footer .= "        {\n";
  $footer .= "          type: 'POST',\n";
  $footer .= "          url: '/api/install/db',\n";
  $footer .= "          data: JSON.stringify ( { Hostname: $('#" . $dbaddr . "').val (), Username: $('#" . $dbuser . "').val (), Password: $('#" . $dbpass . "').val ()}),\n";
  $footer .= "          headers: {\n";
  $footer .= "                     'X-HTTP-Method-Override': 'POST',\n";
  $footer .= "                     'Accept': 'application/json'\n";
  $footer .= "                   },\n";
  $footer .= "          contentType: 'application/json; charset=utf-8',\n";
  $footer .= "          dataType: 'json',\n";
  $footer .= "          complete: function ( jqXHR, textStatus)\n";
  $footer .= "                    {\n";
  $footer .= "                      if ( jqXHR.status == 200)\n";
  $footer .= "                      {\n";
  $footer .= "                        try\n";
  $footer .= "                        {\n";
  $footer .= "                          var data = JSON.parse ( jqXHR.responseText);\n";
  $footer .= "                        } catch ( e) {\n";
  $footer .= "                          var data = {};\n";
  $footer .= "                        }\n";
  $footer .= "                        if ( data.Result == true)\n";
  $footer .= "                        {\n";
  $footer .= "                          $('#page2').animate ( { 'left': '-100%', 'opacity': 0}, 'slow');\n";
  $footer .= "                          $('#page3').animate ( { 'left': '0', 'opacity': '100%'}, 'slow');\n";
  $footer .= "                          $('#dot2').animate ( { 'background-color': '#aaaaaa'}, 'slow');\n";
  $footer .= "                          $('#dot3').animate ( { 'background-color': '#ffffff'}, 'slow');\n";
  $footer .= "                        } else {\n";
  $footer .= "                          if ( data.hasOwnProperty ( 'Hostname'))\n";
  $footer .= "                          {\n";
  $footer .= "                            $('#" . $dbaddr . "').addClass ( 'alert-danger').popover ( { placement: 'auto right', trigger: 'manual', delay: { show: 500, hide: 0}, content: data.Hostname}).popover ( 'show');\n";
  $footer .= "                          }\n";
  $footer .= "                          if ( data.hasOwnProperty ( 'Username'))\n";
  $footer .= "                          {\n";
  $footer .= "                            $('#" . $dbuser . "').addClass ( 'alert-danger').popover ( { placement: 'auto right', trigger: 'manual', delay: 500, content: data.Username}).popover ( 'show');\n";
  $footer .= "                          }\n";
  $footer .= "                          if ( data.hasOwnProperty ( 'Password'))\n";
  $footer .= "                          {\n";
  $footer .= "                            $('#" . $dbpass . "').addClass ( 'alert-danger').popover ( { placement: 'auto right', trigger: 'manual', timeout: 5000, content: data.Password}).popover ( 'show');\n";
  $footer .= "                          }\n";
  $footer .= "                          if ( data.hasOwnProperty ( 'Hostname'))\n";
  $footer .= "                          {\n";
  $footer .= "                            $('#" . $dbaddr . "').focus ();\n";
  $footer .= "                          } else {\n";
  $footer .= "                            if ( data.hasOwnProperty ( 'Username'))\n";
  $footer .= "                            {\n";
  $footer .= "                              $('#" . $dbuser . "').focus ();\n";
  $footer .= "                            } else {\n";
  $footer .= "                              if ( data.hasOwnProperty ( 'Password'))\n";
  $footer .= "                              {\n";
  $footer .= "                                $('#" . $dbpass . "').focus ();\n";
  $footer .= "                              }\n";
  $footer .= "                            }\n";
  $footer .= "                          }\n";
  $footer .= "                          if ( data.hasOwnProperty ( 'Message'))\n";
  $footer .= "                          {\n";
  $footer .= "                            new PNotify ( { title: '" . __ ( "Installation") . "', text: data.Message, type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});\n";
  $footer .= "                          }\n";
  $footer .= "                        }\n";
  $footer .= "                      } else {\n";
  $footer .= "                        new PNotify ( { title: '" . __ ( "Installation") . "', text: '" . __ ( "Error installing system.") . "', type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});\n";
  $footer .= "                      }\n";
  $footer .= "                      l2.stop ();\n";
  $footer .= "                      return;\n";
  $footer .= "                    }\n";
  $footer .= "      })}, 500);\n";
  $footer .= "    });\n";
  $footer .= "    $('#button3').on ( 'click', function ( e)\n";
  $footer .= "    {\n";
  $footer .= "      e && e.preventDefault ();\n";
  $footer .= "      window.location = window.location.protocol + '//' + window.location.hostname;\n";
  $footer .= "      return;\n";
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
 * Dashboard
 */
framework_add_hook ( "dashboard_page", "dashboard_page");
framework_add_path ( "/dashboard", "dashboard_page");

/**
 * Function to draw a dashboard to user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $page Buffer from plugin system if processed by other function
 *                     before
 * @param array $parameters Framework page structure
 * @return array Framework page structure with generated content
 */
function dashboard_page ( $page, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Dashboard"));
  sys_set_subtitle ( __ ( "system dashboard"));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "daterangepicker", "src" => "/vendors/daterangepicker/daterangepicker.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "jquery-knob", "src" => "/vendors/jquery-knob/dist/jquery.knob.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "daterangepicker", "src" => "/vendors/daterangepicker/daterangepicker.js", "dep" => array ( "moment")));

  /**
   * Dashboard
   */
  $output = "<div class=\"pull-right\">\n";
  $output .= "  <div id=\"range\" class=\"daterangepicker-input\"><i class=\"fa fa-calendar\"></i> <span> - </span> <b class=\"caret\"></b></div>\n";
  $output .= "</div>\n";
  $output .= "<div class=\"clearfix\"></div>\n";

  // Draw information knob dials
  $output .= "<div id=\"stats\" class=\"row circleStats\">\n";

  // Answer-Seizure Ratio (ASR)
  $output .= "  <div class=\"col-md-2 col-sm-4 col-xs-6\">\n";
  $output .= "    <div class=\"circleStatsItem blue\">\n";
  $output .= "      <i class=\"fa fa-bullhorn\"></i>\n";
  $output .= "      <span class=\"plus\">+</span>\n";
  $output .= "      <span class=\"percent\">%</span>\n";
  $output .= "      <input type=\"text\" id=\"dashboard_asr\" value=\"0\" class=\"blueCircle\" />\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"box-small-title\"><span class=\"legend\" data-original-title=\"" . __ ( "Answered calls") . " * 100 / " . __ ( "Calls total") . "\" data-toggle=\"tooltip\">" . __ ( "Answer-Seizure Ratio") . "</span><br /><span id=\"dashboard_asr_value\"></span> " . __ ( "of") . " <span id=\"dashboard_asr_total\"></span></div>\n";
  $output .= "  </div>\n";

  // Network Efficiency Ratio (NER)
  $output .= "  <div class=\"col-md-2 col-sm-4 col-xs-6\">\n";
  $output .= "    <div class=\"circleStatsItem red\">\n";
  $output .= "      <i class=\"fa fa-thumbs-up\"></i>\n";
  $output .= "      <span class=\"plus\">+</span>\n";
  $output .= "      <span class=\"percent\">%</span>\n";
  $output .= "      <input type=\"text\" id=\"dashboard_ner\" value=\"0\" class=\"orangeCircle\" />\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"box-small-title\"><span class=\"legend\" data-original-title=\"" . __ ( "Answered or not answered or busy calls") . " * 100 / " . __ ( "Calls total") . "\" data-toggle=\"tooltip\">" . __ ( "Network Efficiency Ratio") . "</span><br /><span id=\"dashboard_ner_value\"></span> " . __ ( "of") . " <span id=\"dashboard_ner_total\"></span></div>\n";
  $output .= "  </div>\n";

  // Subscriber Busy Ratio
  $output .= "  <div class=\"col-md-2 col-sm-4 col-xs-6\">\n";
  $output .= "    <div class=\"circleStatsItem yellow\">\n";
  $output .= "      <i class=\"fa fa-user\"></i>\n";
  $output .= "      <span class=\"plus\">+</span>\n";
  $output .= "      <span class=\"percent\">%</span>\n";
  $output .= "      <input type=\"text\" id=\"dashboard_sbr\" value=\"0\" class=\"yellowCircle\" />\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"box-small-title\"><span class=\"legend\" data-original-title=\"" . __ ( "Busy calls") . " * 100 / " . __ ( "Answered or busy calls") . "\" data-toggle=\"tooltip\">" . __ ( "Subscriber Busy Ratio") . "</span><br /><span id=\"dashboard_sbr_value\"></span> " . __ ( "of") . " <span id=\"dashboard_sbr_total\"></span></div>\n";
  $output .= "  </div>\n";

  // Short Calls Ratio
  $output .= "  <div class=\"col-md-2 col-sm-4 col-xs-6\">\n";
  $output .= "    <div class=\"circleStatsItem green\">\n";
  $output .= "      <i class=\"fa fa-chart-bar\"></i>\n";
  $output .= "      <span class=\"plus\">+</span>\n";
  $output .= "      <span class=\"percent\">%</span>\n";
  $output .= "      <input type=\"text\" id=\"dashboard_scr\" value=\"0\" class=\"greenCircle\" />\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"box-small-title\"><span class=\"legend\" data-original-title=\"" . __ ( "Answered calls") . " <= 60 " . __ ( "seconds") . " * 100 / " . __ ( "Answered calls") . "\" data-toggle=\"tooltip\">" . __ ( "Short Calls Ratio") . "</span><br /><span id=\"dashboard_scr_value\"></span> " . __ ( "of") . " <span id=\"dashboard_scr_total\"></span></div>\n";
  $output .= "  </div>\n";

  // Long Calls Ratio
  $output .= "  <div class=\"col-md-2 col-sm-4 col-xs-6\">\n";
  $output .= "    <div class=\"circleStatsItem lightorange\">\n";
  $output .= "      <i class=\"fa fa-shopping-cart\"></i>\n";
  $output .= "      <span class=\"plus\">+</span>\n";
  $output .= "      <span class=\"percent\">%</span>\n";
  $output .= "      <input type=\"text\" id=\"dashboard_lcr\" value=\"0\" class=\"lightOrangeCircle\" />\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"box-small-title\"><span class=\"legend\" data-original-title=\"" . __ ( "Answered calls") . " >= 5 " . __ ( "minutes") . " * 100 / " . __ ( "Answered calls") . "\" data-toggle=\"tooltip\">" . __ ( "Long Calls Ratio") . "</span><br /><span id=\"dashboard_lcr_value\"></span> " . __ ( "of") . " <span id=\"dashboard_lcr_total\"></span></div>\n";
  $output .= "  </div>\n";

  // Allocation percent
  $output .= "  <div class=\"col-md-2 col-sm-4 col-xs-6\">\n";
  $output .= "    <div class=\"circleStatsItem pink\">\n";
  $output .= "      <i class=\"fa fa-globe\"></i>\n";
  $output .= "      <span class=\"plus\">+</span>\n";
  $output .= "      <span class=\"percent\">%</span>\n";
  $output .= "      <input type=\"text\" id=\"dashboard_allocation\" value=\"0\" class=\"pinkCircle\" />\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"box-small-title\"><span class=\"legend\" data-original-title=\"" . __ ( "Registered extensions") . " * 100 / " . __ ( "Extensions total") . "\" data-toggle=\"tooltip\">" . __ ( "Allocation Ratio") . "</span><br /><span id=\"dashboard_allocation_value\"></span> " . __ ( "of") . " <span id=\"dashboard_allocation_total\"></span></div>\n";
  $output .= "  </div>\n";
  $output .= "</div>";

  /**
   * Add dashboard JavaScript code
   */
  sys_addjs ( "$('.greenCircle').knob (\n" .
              "{\n" .
              "  'min': 0,\n" .
              "  'max': 100,\n" .
              "  'readOnly': true,\n" .
              "  'width': 120,\n" .
              "  'height': 120,\n" .
              "  'fgColor': '#b9e672',\n" .
              "  'dynamicDraw': true,\n" .
              "  'thickness': 0.2,\n" .
              "  'tickColorizeValues': true,\n" .
              "  'skin': 'tron',\n" .
              "  'animated': 2000\n" .
              "});\n" .
              "$('.orangeCircle').knob (\n" .
              "{\n" .
              "  'min': 0,\n" .
              "  'max': 100,\n" .
              "  'readOnly': true,\n" .
              "  'width': 120,\n" .
              "  'height': 120,\n" .
              "  'fgColor': '#FA5833',\n" .
              "  'dynamicDraw': true,\n" .
              "  'thickness': 0.2,\n" .
              "  'tickColorizeValues': true,\n" .
              "  'skin': 'tron',\n" .
              "  'animated': 2000\n" .
              "});\n" .
              "$('.lightOrangeCircle').knob (\n" .
              "{\n" .
              "  'min': 0,\n" .
              "  'max': 100,\n" .
              "  'readOnly': true,\n" .
              "  'width': 120,\n" .
              "  'height': 120,\n" .
              "  'fgColor': '#f4a70c',\n" .
              "  'dynamicDraw': true,\n" .
              "  'thickness': 0.2,\n" .
              "  'tickColorizeValues': true,\n" .
              "  'skin': 'tron',\n" .
              "  'animated': 2000\n" .
              "});\n" .
              "$('.blueCircle').knob (\n" .
              "{\n" .
              "  'min': 0,\n" .
              "  'max': 100,\n" .
              "  'readOnly': true,\n" .
              "  'width': 120,\n" .
              "  'height': 120,\n" .
              "  'fgColor': '#2FABE9',\n" .
              "  'dynamicDraw': true,\n" .
              "  'thickness': 0.2,\n" .
              "  'tickColorizeValues': true,\n" .
              "  'skin': 'tron',\n" .
              "  'animated': 2000\n" .
              "});\n" .
              "$('.yellowCircle').knob (\n" .
              "{\n" .
              "  'min': 0,\n" .
              "  'max': 100,\n" .
              "  'readOnly': true,\n" .
              "  'width': 120,\n" .
              "  'height': 120,\n" .
              "  'fgColor': '#e7e572',\n" .
              "  'dynamicDraw': true,\n" .
              "  'thickness': 0.2,\n" .
              "  'tickColorizeValues': true,\n" .
              "  'skin': 'tron',\n" .
              "  'animated': 2000\n" .
              "});\n" .
              "$('.pinkCircle').knob (\n" .
              "{\n" .
              "  'min': 0,\n" .
              "  'max': 100,\n" .
              "  'readOnly': true,\n" .
              "  'width': 120,\n" .
              "  'height': 120,\n" .
              "  'fgColor': '#e42b75',\n" .
              "  'dynamicDraw': true,\n" .
              "  'thickness': 0.2,\n" .
              "  'tickColorizeValues': true,\n" .
              "  'skin': 'tron',\n" .
              "  'animated': 2000\n" .
              "});\n" .
              "$('span.legend').tooltip (\n" .
              "{\n" .
              "  delay: { 'show': 100, 'hide': 100 }\n" .
              "});\n" .
              "$('#range span').html ( moment ().subtract ( 29, 'days').format ( '" . __ ( "MM/DD/YYYY") . "') + ' - ' + moment ().format ( '" . __ ( "MM/DD/YYYY") . "'));\n" .
              "$('#range').css ( 'display', 'block').daterangepicker (\n" .
              "{\n" .
              "  startDate: moment ().subtract ( 29, 'days').format ( '" . __ ( "MM/DD/YYYY") . "'),\n" .
              "  endDate: moment ().format ( '" . __ ( "MM/DD/YYYY") . "'),\n" .
              "  minDate: '" . $_in["general"]["installdate"] . "',\n" .
              "  maxDate: moment ().format ( '" . __ ( "MM/DD/YYYY") . "'),\n" .
              "  dateLimit: {\n" .
              "    days: 365\n" .
              "  },\n" .
              "  showDropdowns: true,\n" .
              "  showWeekNumbers: true,\n" .
              "  timePicker: false,\n" .
              "  timePickerIncrement: 1,\n" .
              "  timePicker12Hour: true,\n" .
              "  ranges: {\n" .
              "    '" . __ ( "Today") . "': [ moment (), moment ()],\n" .
              "    '" . __ ( "Yesterday") . "': [ moment ().subtract ( 1, 'days'), moment ().subtract ( 1, 'days')],\n" .
              "    '" . __ ( "Last 7 days") . "': [ moment ().subtract ( 6, 'days'), moment ()],\n" .
              "    '" . __ ( "Last 30 days") . "': [ moment ().subtract ( 29, 'days'), moment ()],\n" .
              "    '" . __ ( "This month") . "': [ moment ().startOf ( 'month'), moment ().endOf ( 'month')],\n" .
              "    '" . __ ( "Previous month") . "': [ moment ().subtract ( 1, 'month').startOf ( 'month'), moment ().subtract ( 1, 'month').endOf ( 'month')]\n" .
              "  },\n" .
              "  opens: 'left',\n" .
              "  buttonClasses: [ 'btn btn-default'],\n" .
              "  applyClass: 'btn-small btn-primary',\n" .
              "  cancelClass: 'btn-small',\n" .
              "  separator: ' " . __ ( "through") . " ',\n" .
              "  locale: {\n" .
              "    applyLabel: '" . __ ( "Apply") . "',\n" .
              "    cancelLabel: '" . __ ( "Cancel") . "',\n" .
              "    format: '" . __ ( "MM/DD/YYYY") . "',\n" .
              "    fromLabel: '" . __ ( "From") . "',\n" .
              "    toLabel: '" . __ ( "To") . "',\n" .
              "    customRangeLabel: '" . __ ( "Customize") . "',\n" .
              "    daysOfWeek: [ '" . __ ( "Su") . "', '" . __ ( "Mo") . "', '" . __ ( "Tu") . "', '" . __ ( "We") . "', '" . __ ( "Th") . "', '" . __ ( "Fr") . "', '" . __ ( "Sa") . "'],\n" .
              "    monthNames: [ '" . __ ( "January") . "', '" . __ ( "February") . "', '" . __ ( "March") . "', '" . __ ( "April") . "', '" . __ ( "May") . "', '" . __ ( "June") . "', '" . __ ( "July") . "', '" . __ ( "August") . "', '" . __ ( "September") . "', '" . __ ( "October") . "', '" . __ ( "November") . "', '" . __ ( "December") . "'],\n" .
              "    firstDay: 1\n" .
              "  }\n" .
              "}, function ( start, end, label)\n" .
              "{\n" .
              "  $('#range span').html ( start.format ( '" . __ ( "MM/DD/YYYY") . "') + ' - ' + end.format ( '" . __ ( "MM/DD/YYYY") . "'));\n" .
              "  $('#stats').trigger ( 'update', { Start: moment ( start).format ( 'YYYY-MM-DD'), End: moment ( end).format ( 'YYYY-MM-DD')});\n" .
              "});\n" .
              "$('#stats').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  if ( typeof ( data) != 'object')\n" .
              "  {\n" .
              "    data = {};\n" .
              "  }\n" .
              "  if ( ! data.hasOwnProperty ( 'Start') || data.Start == '')\n" .
              "  {\n" .
              "    data.Start = moment ().subtract ( 29, 'days').format ( 'YYYY-MM-DD');\n" .
              "  }\n" .
              "  if ( ! data.hasOwnProperty ( 'End') || data.End == '')\n" .
              "  {\n" .
              "    data.End = moment ().format ( 'YYYY-MM-DD');\n" .
              "  }\n" .
              "  VoIP.rest ( '/dashboard', 'GET', { Start: data.Start, End: data.End}).done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#dashboard_asr').val ( data.ASR.Percent).trigger ( 'change');\n" .
              "    $('#dashboard_asr_value').html ( data.ASR.Value);\n" .
              "    $('#dashboard_asr_total').html ( data.ASR.Total);\n" .
              "    $('#dashboard_ner').val ( data.NER.Percent).trigger ( 'change');\n" .
              "    $('#dashboard_ner_value').html ( data.NER.Value);\n" .
              "    $('#dashboard_ner_total').html ( data.NER.Total);\n" .
              "    $('#dashboard_sbr').val ( data.SBR.Percent).trigger ( 'change');\n" .
              "    $('#dashboard_sbr_value').html ( data.SBR.Value);\n" .
              "    $('#dashboard_sbr_total').html ( data.SBR.Total);\n" .
              "    $('#dashboard_scr').val ( data.SCR.Percent).trigger ( 'change');\n" .
              "    $('#dashboard_scr_value').html ( data.SCR.Value);\n" .
              "    $('#dashboard_scr_total').html ( data.SCR.Total);\n" .
              "    $('#dashboard_lcr').val ( data.LCR.Percent).trigger ( 'change');\n" .
              "    $('#dashboard_lcr_value').html ( data.LCR.Value);\n" .
              "    $('#dashboard_lcr_total').html ( data.LCR.Total);\n" .
              "    $('#dashboard_allocation').val ( data.Allocation.Percent).trigger ( 'change');\n" .
              "    $('#dashboard_allocation_value').html ( data.Allocation.Value);\n" .
              "    $('#dashboard_allocation_total').html ( data.Allocation.Total);\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Dashboard") . "', text: '" . __ ( "Error requesting statistics!") . "', type: 'error'});\n" .
              "  });\n" .
              "}).trigger ( 'update');\n");

  /**
   * Return generated HTML
   */
  return $output;
}

/**
 * Fast search
 */
framework_add_hook ( "fastsearch_page", "fastsearch_page");
framework_add_path ( "/fastsearch/:q", "fastsearch_page");

/**
 * Function to generate the fast search page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function fastsearch_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Fast search"));
  sys_set_subtitle ( __ ( "results"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Fast search"))
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
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "accent-neutralise", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Fast search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Number") . "</th>\n";
  $output .= "      <th>" . __ ( "Type") . "</th>\n";
  $output .= "      <th>" . __ ( "Description") . "</th>\n";
  $output .= "      <th>" . __ ( "Actions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add search table JavaScript code
   */
  sys_addjs ( "$('#datatables').data ( 'dt', $('#datatables').DataTable (\n" .
              "{\n" .
              "  data: VoIP.APIsearch ( { path: document.location.pathname, fields: 'ID,Number,Type,Description,NULL'}),\n" .
              "  columns: [\n" .
              "             { data: 'ID', class: 'never', visible: false, orderable: false, searchable: false},\n" .
              "             { data: 'Number', width: '17%', class: 'export all'},\n" .
              "             { data: 'Type', width: '3%', class: 'export min-tablet-l center', render: function ( data, type, full) { return '<span class=\"label label-' + VoIP.interface.objLabel ( full.Type) + '\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"' + VoIP.interface.objTextSingular ( full.Type) + '\" title=\"\"><i class=\"fa fa-' + VoIP.interface.objIcon ( full.Type) + '\"></i></span>'; }, orderable: false, searchable: false},\n" .
              "             { data: 'Description', width: '70%', class: 'export all'},\n" .
              "             { data: 'NULL', width: '10%', class: 'all',  render: function ( data, type, full) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"' + VoIP.interface.objPath ( full.Type) + '/' + full.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"' + VoIP.interface.objPath ( full.Type) + '/' + full.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "List") . "\" role=\"button\" title=\"\" href=\"' + VoIP.interface.objPath ( full.Type) + '?filter=' + encodeURIComponent ( document.location.pathname.substr ( document.location.pathname.lastIndexOf ( '/') + 1)) + '\"><i class=\"fa fa-list\"></i></a></span>'; }, orderable: false, searchable: false}\n" .
              "           ],\n" .
              "}));\n" .
              "$('#fastsearch input[name=\"q\"]').val ( '');\n");

  return $output;
}

/**
 * About
 */
framework_add_hook ( "about_page", "about_page");
framework_add_path ( "/about", "about_page");

/**
 * Function to displau system information (about page).
 *
 * @global array $_in Framework global configuration variable
 * @param string $page Buffer from plugin system if processed by other function
 *                     before
 * @param array $parameters Framework page structure
 * @return array Framework page structure with generated content
 */
function about_page ( $page, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "About"));
  sys_set_subtitle ( __ ( "system information"));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "daterangepicker", "src" => "/vendors/daterangepicker/daterangepicker.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "jquery-knob", "src" => "/vendors/jquery-knob/dist/jquery.knob.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "daterangepicker", "src" => "/vendors/daterangepicker/daterangepicker.js", "dep" => array ( "moment")));

  /**
   * Dashboard
   */
  $output = "<div class=\"center\"><span style=\"font-family: 'Source Sans Pro', sans-serif; font-size: 24px; font-weight: 500; line-height: 26.4px;\">VoIP Domain</span><br /><strong>" . __ ( "Version") . " " . $_in["version"] . "</strong></div><br />\n";
  $output .= "<div style=\"text-align: justified; text-indent: 3em\">" . __ ( "VoIP Domain was created in 2016 by Ernani Azevedo, with the aim of creating a simpler way to deploy a medium to large VoIP telephone system. The system tries to keep complex tasks as simple as possible, with as few computational terms and automating everything as possible, so that any manager with a little VoIP knowledge can deploy a large, stable and feature-rich PBX.") . "</div><br />\n";
  $output .= "<div style=\"text-align: justified; text-indent: 3em\">" . __ ( "This project is published as Open Source under GPL version 3 license. Fell free to contribute and make this a great tool for everyone.") . "</div>\n";

  return $output;
}
?>
