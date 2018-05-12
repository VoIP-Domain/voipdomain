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
 * VoIP Domain main framework interface module. This module build all the
 * interface to the user browser. It controls the authentication, menu, basic
 * interface forms like user profile page, and other things related to UI.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Interface
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
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

framework_add_hook ( "framework_page_generate", "page_structure", IN_HOOK_INSERT_FIRST);

/**
 * Main framework interface function. This function get the output made by the
 * system and create the HTML structure, adding CSS, JavaScript files and
 * scripts, framework informations, etc.
 *
 * @global array $_in Framework global configuration variable
 * @param string $content Content of the output buffer capture
 * @return string Processed output page ready to sent to final user
 */
function page_structure ( $content)
{
  global $_in;

  /**
   * Prepare the page HTML head
   */
  $head = "<!DOCTYPE html>\n";
  $head .= "<html lang=\"" . ( ! empty ( $_in["general"]["language"]) ? $_in["general"]["language"] : "en_US") . "\" class=\"no-js\">\n";
  $head .= "<head>\n";
  $head .= "  <meta charset=\"" . ( ! empty ( $_in["general"]["charset"]) ? strtolower ( $_in["general"]["charset"]) : "utf-8") . "\">\n";
  $head .= "  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
  $head .= "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\">\n";
  if ( ! empty ( $_in["general"]["description"]))
  {
    $head .= "  <meta name=\"description\" content=\"" . addslashes ( strip_tags ( $_in["general"]["description"])) . "\">\n";
  }
  if ( ! empty ( $_in["general"]["author"]))
  {
    $head .= "  <meta name=\"author\" content=\"" . addslashes ( strip_tags ( $_in["general"]["author"])) . "\">\n";
  }
  if ( ! empty ( $_in["general"]["version"]))
  {
    $head .= "  <meta name=\"version\" content=\"" . addslashes ( strip_tags ( $_in["general"]["version"])) . "\">\n";
  }
  if ( ! empty ( $_in["general"]["favicon"]))
  {
    $head .= "  <link rel=\"icon\" type=\"" . mime_content_type ( strip_tags ( dirname ( __FILE__) . $_in["general"]["favicon"])) . "\" href=\"" . addslashes ( strip_tags ( ( substr ( $_in["general"]["favicon"], 0, 1) != "/" ? "/" : "") . $_in["general"]["favicon"])) . "\">\n";
  }
  $head .= "  <title>" . addslashes ( strip_tags ( $_in["general"]["title"])) . "</title>\n";
  $head .= framework_call ( "template_header_tags");
  $head .= "  <style type=\"text/css\">\n";
  $head .= "    .no-js #loader\n";
  $head .= "    {\n";
  $head .= "      display: none;\n";
  $head .= "    }\n";
  $head .= "    .js #loader\n";
  $head .= "    {\n";
  $head .= "      display: block;\n";
  $head .= "      position: absolute;\n";
  $head .= "      left: 100px;\n";
  $head .= "      top: 0;\n";
  $head .= "    }\n";
  $head .= "    .preload\n";
  $head .= "    {\n";
  $head .= "      position: fixed;\n";
  $head .= "      left: 0px;\n";
  $head .= "      top: 0px;\n";
  $head .= "      width: 100%;\n";
  $head .= "      height: 100%;\n";
  $head .= "      z-index: 9999;\n";
  $head .= "      background: url('/img/preloader.gif') center no-repeat #fff;\n";
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
  $body = "<body class=\"hold-transition skin-blue sidebar-mini\">\n";
  $body .= "\n";
  $body .= "<div class=\"loader\"><div class=\"percentage\"></div></div>\n";
  $body .= "<div class=\"preload\"></div>\n";
  $body .= "\n";
  $body .= "<noscript>\n";
  $body .= "  <div class=\"alert alert-block col-sm-10\">\n";
  $body .= "    <h4 class=\"alert-bodying\">" . __ ( "Warning!") . "</h4>\n";
  $body .= "    <p>" . __ ( "You must have") . " <a href=\"http://pt.wikipedia.org/wiki/JavaScript\" target=\"_blank\">JavaScript</a> " . __ ( "enabled to use this system.") . "</p>\n";
  $body .= "  </div>\n";
  $body .= "</noscript>\n";
  $body .= "\n";

  /**
   * Print the framework page structure
   */
  $body .= "<div class=\"wrapper\">\n";
  $body .= "  <header class=\"main-header\">\n";
  $body .= "    <a href=\"/\" class=\"logo\">\n";
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
/*
  Dropdown de mensagens...

  $body .= "          <li class=\"dropdown messages-menu\">\n";
  $body .= "            <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">\n";
  $body .= "              <i class=\"fa fa-envelope-o\"></i>\n";
  $body .= "              <span class=\"label label-success\">4</span>\n";
  $body .= "            </a>\n";
  $body .= "            <ul class=\"dropdown-menu\">\n";
  $body .= "              <li class=\"header\">You have 4 messages</li>\n";
  $body .= "              <li>\n";
  $body .= "                <ul class=\"menu\">\n";
  $body .= "                  <li>\n";
  $body .= "                    <a href=\"#\">\n";
  $body .= "                      <div class=\"pull-left\">\n";
  $body .= "                        <img src=\"/img/avatars/profile-" . ( ! empty ( $_in["session"]["AvatarID"]) ? $_in["session"]["AvatarID"] : "default") . ".jpg\" class=\"img-circle\" alt=\"\">\n";
  $body .= "                      </div>\n";
  $body .= "                      <h4>\n";
  $body .= "                        Support Team\n";
  $body .= "                        <small><i class=\"fa fa-calendar-alt\"></i> 5 mins</small>\n";
  $body .= "                      </h4>\n";
  $body .= "                      <p>Why not buy a new awesome theme?</p>\n";
  $body .= "                    </a>\n";
  $body .= "                  </li>\n";
  $body .= "                </ul>\n";
  $body .= "              </li>\n";
  $body .= "              <li class=\"footer\"><a href=\"#\">See All Messages</a></li>\n";
  $body .= "            </ul>\n";
  $body .= "          </li>\n";
*/

/*
  Dropdown de notificações...

  $body .= "          <li class=\"dropdown notifications-menu\">\n";
  $body .= "            <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">\n";
  $body .= "              <i class=\"fa fa-bell-o\"></i>\n";
  $body .= "              <span class=\"label label-warning\">10</span>\n";
  $body .= "            </a>\n";
  $body .= "            <ul class=\"dropdown-menu\">\n";
  $body .= "              <li class=\"header\">You have 10 notifications</li>\n";
  $body .= "              <li>\n";
  $body .= "                <ul class=\"menu\">\n";
  $body .= "                  <li>\n";
  $body .= "                    <a href=\"#\">\n";
  $body .= "                      <i class=\"fa fa-users text-aqua\"></i> 5 new members joined today\n";
  $body .= "                    </a>\n";
  $body .= "                  </li>\n";
  $body .= "                </ul>\n";
  $body .= "              </li>\n";
  $body .= "              <li class=\"footer\"><a href=\"#\">View all</a></li>\n";
  $body .= "            </ul>\n";
  $body .= "          </li>\n";
*/

/*
  Dropdown de tarefas...

  $body .= "          <li class=\"dropdown tasks-menu\">\n";
  $body .= "            <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">\n";
  $body .= "              <i class=\"fa fa-flag-o\"></i>\n";
  $body .= "              <span class=\"label label-danger\">9</span>\n";
  $body .= "            </a>\n";
  $body .= "            <ul class=\"dropdown-menu\">\n";
  $body .= "              <li class=\"header\">You have 9 tasks</li>\n";
  $body .= "              <li>\n";
  $body .= "                <ul class=\"menu\">\n";
  $body .= "                  <li>\n";
  $body .= "                    <a href=\"#\">\n";
  $body .= "                      <h3>\n";
  $body .= "                        Design some buttons\n";
  $body .= "                        <small class=\"pull-right\">20%</small>\n";
  $body .= "                      </h3>\n";
  $body .= "                      <div class=\"progress xs\">\n";
  $body .= "                        <div class=\"progress-bar progress-bar-aqua\" style=\"width: 20%\" role=\"progressbar\"\n";
  $body .= "                             aria-valuenow=\"20\" aria-valuemin=\"0\" aria-valuemax=\"100\">\n";
  $body .= "                          <span class=\"sr-only\">20% Complete</span>\n";
  $body .= "                        </div>\n";
  $body .= "                      </div>\n";
  $body .= "                    </a>\n";
  $body .= "                  </li>\n";
  $body .= "                </ul>\n";
  $body .= "              </li>\n";
  $body .= "              <li class=\"footer\">\n";
  $body .= "                <a href=\"#\">View all tasks</a>\n";
  $body .= "              </li>\n";
  $body .= "            </ul>\n";
  $body .= "          </li>\n";
*/

  $body .= "          <li class=\"dropdown user user-menu\">\n";
  $body .= "            <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">\n";
  $body .= "              <img src=\"/img/avatars/profile-" . ( ! empty ( $_in["session"]["AvatarID"]) ? $_in["session"]["AvatarID"] : "default") . ".jpg\" class=\"user-image\" alt=\"\">\n";
  $body .= "              <span class=\"hidden-xs\">" . strip_tags ( $_in["session"]["Name"]) . "</span>\n";
  $body .= "            </a>\n";
  $body .= "            <ul class=\"dropdown-menu\">\n";
  $body .= "              <li class=\"user-header\">\n";
  $body .= "                <img src=\"/img/avatars/profile-" . ( ! empty ( $_in["session"]["AvatarID"]) ? $_in["session"]["AvatarID"] : "default") . ".jpg\" class=\"img-circle\" alt=\"\">\n";
  $body .= "                <p>\n";
  $body .= "                  " . strip_tags ( $_in["session"]["Name"]) . " - " . ( $_in["session"]["Permissions"]["administrator"] == true ? __ ( "Administrator") : __ ( "User")) . "\n";
  $body .= "                  <small>" . __ ( "Since") . " " . date ( __ ( "m/d/Y"), mktime ( 0, 0, 0, substr ( $_in["session"]["Since"], 5, 2), substr ( $_in["session"]["Since"], 8, 2), substr ( $_in["session"]["Since"], 0, 4))) . "</small>\n";
  $body .= "                </p>\n";
  $body .= "              </li>\n";
/*
  Aba de baixo do nome do usuário...

  $body .= "              <li class=\"user-body\">\n";
  $body .= "                <div class=\"row\">\n";
  $body .= "                  <div class=\"col-xs-4 text-center\">\n";
  $body .= "                    <a href=\"#\">Followers</a>\n";
  $body .= "                  </div>\n";
  $body .= "                  <div class=\"col-xs-4 text-center\">\n";
  $body .= "                    <a href=\"#\">Sales</a>\n";
  $body .= "                  </div>\n";
  $body .= "                  <div class=\"col-xs-4 text-center\">\n";
  $body .= "                    <a href=\"#\">Friends</a>\n";
  $body .= "                  </div>\n";
  $body .= "                </div>\n";
  $body .= "              </li>\n";
*/
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

/*
  Slider de configurações...

  $body .= "          <li>\n";
  $body .= "            <a href=\"#\" data-toggle=\"control-sidebar\"><i class=\"fa fa-gears\"></i></a>\n";
  $body .= "          </li>\n";
*/

  $body .= "        </ul>\n";
  $body .= "      </div>\n";
  $body .= "    </nav>\n";
  $body .= "  </header>\n";

//  $body .= "        <div class=\"navbar nav_title\" style=\"border: 0;\"><a href=\"/\" class=\"site_title\"><img src=\"/images/" . preg_replace ( "([^\w\s\d\.\-_~,;:\[\]\(\]]|[\.]{2,})", "", $_in["logo"]["filename"]) . "\" width=\"" . (int) $_in["logo"]["width"] . "\" height=\"" . (int) $_in["logo"]["height"] . "\" alt=\"\" /> <span>VoIP Domain</span></a></div>\n";
//  $body .= "            <h2>" . strip_tags ( $_in["session"]["Name"]) . "</h2>\n";

  /**
   * Create basic menu structure
   */
  $menu = array ();
  // $menu[] = array ( "type" => "entry", "icon" => "home", "color" => "red", "href" => "/", "text" => __ ( "Start"), "labels" => array ( array ( "color" => "yellow", "text" => 2), array ( "color" => "aqua", "text" => 1)));
  $menu[] = array ( "type" => "entry", "icon" => "home", "color" => "red", "href" => "/", "text" => __ ( "Start"));
  $menu[] = array ( "type" => "submenu", "icon" => "book", "text" => __ ( "Entries"), "entries" => (array) filters_call ( "page_menu_registers"));
  $menu[] = array ( "type" => "submenu", "icon" => "file", "text" => __ ( "Reports"), "entries" => array_merge ( array (
              array ( "type" => "submenu", "group" => "subs", "icon" => "cloud-upload-alt", "text" => "Ligações efetuadas", "entries" => array (
                array ( "type" => "entry", "icon" => "user", "href" => "/reports/made/user", "text" => "Por usuário"),
                array ( "type" => "entry", "icon" => "users", "href" => "/reports/made/group", "text" => "Por grupo"),
                array ( "type" => "entry", "icon" => "cloud-upload-alt", "href" => "/reports/made/gateway", "text" => "Por gateway"),
                array ( "type" => "entry", "icon" => "globe", "href" => "/reports/made/all", "text" => "Todas")
              )),
              array ( "type" => "submenu", "group" => "subs", "icon" => "cloud-download-alt", "text" => "Ligações recebidas", "entries" => array (
                array ( "type" => "entry", "icon" => "user", "href" => "/reports/received/user", "text" => "Por usuário"),
                array ( "type" => "entry", "icon" => "users", "href" => "/reports/received/group", "text" => "Por grupo"),
                array ( "type" => "entry", "icon" => "cloud-upload-alt", "href" => "/reports/received/gateway", "text" => "Por gateway"),
                array ( "type" => "entry", "icon" => "globe", "href" => "/reports/received/all", "text" => "Todas")
              )),
              array ( "type" => "submenu", "group" => "subs", "icon" => "dollar-sign", "text" => "Financeiros", "entries" => array_merge ( array (
                array ( "type" => "entry", "icon" => "dollar-sign", "href" => "/reports/financial/costcenter", "text" => "Centros de Custo"),
                array ( "type" => "entry", "icon" => "users", "href" => "/reports/financial/group", "text" => "Grupos"),
                array ( "type" => "entry", "icon" => "cloud-upload-alt", "href" => "/reports/financial/gateway", "text" => "Gateways")
              ), (array) filters_call ( "page_menu_reports_financial"))),
              array ( "type" => "submenu", "group" => "subs", "icon" => "paint-brush", "text" => "Gráficos", "entries" => array_merge ( array (
                array ( "type" => "entry", "icon" => "globe", "href" => "/reports/graphs/server", "text" => "Por servidor"),
                array ( "type" => "entry", "icon" => "dollar-sign", "href" => "/reports/graphs/cost", "text" => "Custos"),
                array ( "type" => "entry", "icon" => "search", "href" => "/reports/graphs/monitor", "text" => "Monitoria")
              ), (array) filters_call ( "page_menu_reports_graph"))),
              array ( "type" => "entry", "group" => "listing", "icon" => "list", "href" => "/reports/list", "text" => "Listagem de ramais"),
              array ( "type" => "entry", "group" => "listing", "icon" => "random", "href" => "/reports/ranges", "text" => "Listagem de faixas"),
              array ( "type" => "entry", "group" => "listing", "icon" => "thermometer-empty", "href" => "/reports/activity", "text" => "Listagem por atividade"),
              array ( "type" => "entry", "group" => "listing", "icon" => "heart", "href" => "/reports/status", "text" => "Estado do servidor"),
            ), (array) filters_call ( "page_menu_reports")));
//  $menu[] = array ( "type" => "submenu", "icon" => "cog", "text" => __ ( "Configurations"), "permission" => "administrator", "entries" => (array) filters_call ( "page_menu_configurations"));
  $menu[] = array ( "type" => "submenu", "icon" => "suitcase", "text" => __ ( "Resources"), "entries" => (array) filters_call ( "page_menu_resources"));
  $menu[] = (array) filters_call ( "page_menu_others");

  /**
   * Print menu
   */
  $body .= "  <aside class=\"main-sidebar\">\n";
  $body .= "    <section class=\"sidebar\">\n";
  $body .= "      <div class=\"user-panel\">\n";
  $body .= "        <div class=\"pull-left image\">\n";
  $body .= "          <img src=\"/img/avatars/profile-" . ( ! empty ( $_in["session"]["AvatarID"]) ? $_in["session"]["AvatarID"] : "default") . ".jpg\" class=\"img-circle\" alt=\"\">\n";
  $body .= "        </div>\n";
  $body .= "        <div class=\"pull-left info\">\n";
  $body .= "          <p>" . strip_tags ( $_in["session"]["Name"]) . "</p>\n";
  $body .= "          <a href=\"#\"><i class=\"fa fa-circle text-success\"></i> " . __ ( "Online") . "</a>\n";
  $body .= "        </div>\n";
  $body .= "      </div>\n";
  $body .= "      <form class=\"sidebar-form\">\n";
  $body .= "        <div class=\"input-group\">\n";
  $body .= "          <input id=\"fastsearch\" type=\"text\" name=\"q\" class=\"form-control\" placeholder=\"" . __ ( "Search") . "...\">\n";
  $body .= "          <span class=\"input-group-btn\">\n";
  $body .= "            <button type=\"submit\" name=\"search\" id=\"search-btn\" class=\"btn btn-flat\"><i class=\"fa fa-search\"></i></button>\n";
  $body .= "          </span>\n";
  $body .= "        </div>\n";
  $body .= "      </form>\n";
  $body .= "      <ul class=\"sidebar-menu\" data-widget=\"tree\">\n";
  $body .= page_menu ( $menu);

/*
  $body .= "        <li class=\"header\">MAIN NAVIGATION</li>\n";
  $body .= "        <li class=\"treeview\">\n";
  $body .= "          <a href=\"#\">\n";
  $body .= "            <i class=\"fa fa-dashboard\"></i> <span>Dashboard</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <i class=\"fa fa-angle-left pull-right\"></i>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "          <ul class=\"treeview-menu\">\n";
  $body .= "            <li><a href=\"../../index.html\"><i class=\"fa fa-circle-o\"></i> Dashboard v1</a></li>\n";
  $body .= "            <li><a href=\"../../index2.html\"><i class=\"fa fa-circle-o\"></i> Dashboard v2</a></li>\n";
  $body .= "          </ul>\n";
  $body .= "        </li>\n";
  $body .= "        <li class=\"treeview\">\n";
  $body .= "          <a href=\"#\">\n";
  $body .= "            <i class=\"fa fa-files-o\"></i>\n";
  $body .= "            <span>Layout Options</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <span class=\"label label-primary pull-right\">4</span>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "          <ul class=\"treeview-menu\">\n";
  $body .= "            <li><a href=\"../layout/top-nav.html\"><i class=\"fa fa-circle-o\"></i> Top Navigation</a></li>\n";
  $body .= "            <li><a href=\"../layout/boxed.html\"><i class=\"fa fa-circle-o\"></i> Boxed</a></li>\n";
  $body .= "            <li><a href=\"../layout/fixed.html\"><i class=\"fa fa-circle-o\"></i> Fixed</a></li>\n";
  $body .= "            <li><a href=\"../layout/collapsed-sidebar.html\"><i class=\"fa fa-circle-o\"></i> Collapsed Sidebar</a></li>\n";
  $body .= "          </ul>\n";
  $body .= "        </li>\n";
  $body .= "        <li>\n";
  $body .= "          <a href=\"../widgets.html\">\n";
  $body .= "            <i class=\"fa fa-th\"></i> <span>Widgets</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <small class=\"label pull-right bg-green\">Hot</small>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "        </li>\n";
  $body .= "        <li class=\"treeview\">\n";
  $body .= "          <a href=\"#\">\n";
  $body .= "            <i class=\"fa fa-pie-chart\"></i>\n";
  $body .= "            <span>Charts</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <i class=\"fa fa-angle-left pull-right\"></i>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "          <ul class=\"treeview-menu\">\n";
  $body .= "            <li><a href=\"../charts/chartjs.html\"><i class=\"fa fa-circle-o\"></i> ChartJS</a></li>\n";
  $body .= "            <li><a href=\"../charts/morris.html\"><i class=\"fa fa-circle-o\"></i> Morris</a></li>\n";
  $body .= "            <li><a href=\"../charts/flot.html\"><i class=\"fa fa-circle-o\"></i> Flot</a></li>\n";
  $body .= "            <li><a href=\"../charts/inline.html\"><i class=\"fa fa-circle-o\"></i> Inline charts</a></li>\n";
  $body .= "          </ul>\n";
  $body .= "        </li>\n";
  $body .= "        <li class=\"treeview\">\n";
  $body .= "          <a href=\"#\">\n";
  $body .= "            <i class=\"fa fa-laptop\"></i>\n";
  $body .= "            <span>UI Elements</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <i class=\"fa fa-angle-left pull-right\"></i>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "          <ul class=\"treeview-menu\">\n";
  $body .= "            <li><a href=\"../UI/general.html\"><i class=\"fa fa-circle-o\"></i> General</a></li>\n";
  $body .= "            <li><a href=\"../UI/icons.html\"><i class=\"fa fa-circle-o\"></i> Icons</a></li>\n";
  $body .= "            <li><a href=\"../UI/buttons.html\"><i class=\"fa fa-circle-o\"></i> Buttons</a></li>\n";
  $body .= "            <li><a href=\"../UI/sliders.html\"><i class=\"fa fa-circle-o\"></i> Sliders</a></li>\n";
  $body .= "            <li><a href=\"../UI/timeline.html\"><i class=\"fa fa-circle-o\"></i> Timeline</a></li>\n";
  $body .= "            <li><a href=\"../UI/modals.html\"><i class=\"fa fa-circle-o\"></i> Modals</a></li>\n";
  $body .= "          </ul>\n";
  $body .= "        </li>\n";
  $body .= "        <li class=\"treeview\">\n";
  $body .= "          <a href=\"#\">\n";
  $body .= "            <i class=\"fa fa-edit\"></i> <span>Forms</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <i class=\"fa fa-angle-left pull-right\"></i>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "          <ul class=\"treeview-menu\">\n";
  $body .= "            <li><a href=\"../forms/general.html\"><i class=\"fa fa-circle-o\"></i> General Elements</a></li>\n";
  $body .= "            <li><a href=\"../forms/advanced.html\"><i class=\"fa fa-circle-o\"></i> Advanced Elements</a></li>\n";
  $body .= "            <li><a href=\"../forms/editors.html\"><i class=\"fa fa-circle-o\"></i> Editors</a></li>\n";
  $body .= "          </ul>\n";
  $body .= "        </li>\n";
  $body .= "        <li class=\"treeview\">\n";
  $body .= "          <a href=\"#\">\n";
  $body .= "            <i class=\"fa fa-table\"></i> <span>Tables</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <i class=\"fa fa-angle-left pull-right\"></i>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "          <ul class=\"treeview-menu\">\n";
  $body .= "            <li><a href=\"../tables/simple.html\"><i class=\"fa fa-circle-o\"></i> Simple tables</a></li>\n";
  $body .= "            <li><a href=\"../tables/data.html\"><i class=\"fa fa-circle-o\"></i> Data tables</a></li>\n";
  $body .= "          </ul>\n";
  $body .= "        </li>\n";
  $body .= "        <li>\n";
  $body .= "          <a href=\"../calendar.html\">\n";
  $body .= "            <i class=\"fa fa-calendar\"></i> <span>Calendar</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <small class=\"label pull-right bg-red\">3</small>\n";
  $body .= "              <small class=\"label pull-right bg-blue\">17</small>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "        </li>\n";
  $body .= "        <li>\n";
  $body .= "          <a href=\"../mailbox/mailbox.html\">\n";
  $body .= "            <i class=\"fa fa-envelope\"></i> <span>Mailbox</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <small class=\"label pull-right bg-yellow\">12</small>\n";
  $body .= "              <small class=\"label pull-right bg-green\">16</small>\n";
  $body .= "              <small class=\"label pull-right bg-red\">5</small>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "        </li>\n";
  $body .= "        <li class=\"treeview active\">\n";
  $body .= "          <a href=\"#\">\n";
  $body .= "            <i class=\"fa fa-folder\"></i> <span>Examples</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <i class=\"fa fa-angle-left pull-right\"></i>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "          <ul class=\"treeview-menu\">\n";
  $body .= "            <li><a href=\"invoice.html\"><i class=\"fa fa-circle-o\"></i> Invoice</a></li>\n";
  $body .= "            <li><a href=\"profile.html\"><i class=\"fa fa-circle-o\"></i> Profile</a></li>\n";
  $body .= "            <li><a href=\"login.html\"><i class=\"fa fa-circle-o\"></i> Login</a></li>\n";
  $body .= "            <li><a href=\"register.html\"><i class=\"fa fa-circle-o\"></i> Register</a></li>\n";
  $body .= "            <li><a href=\"lockscreen.html\"><i class=\"fa fa-circle-o\"></i> Lockscreen</a></li>\n";
  $body .= "            <li><a href=\"404.html\"><i class=\"fa fa-circle-o\"></i> 404 Error</a></li>\n";
  $body .= "            <li><a href=\"500.html\"><i class=\"fa fa-circle-o\"></i> 500 Error</a></li>\n";
  $body .= "            <li class=\"active\"><a href=\"blank.html\"><i class=\"fa fa-circle-o\"></i> Blank Page</a></li>\n";
  $body .= "            <li><a href=\"pace.html\"><i class=\"fa fa-circle-o\"></i> Pace Page</a></li>\n";
  $body .= "          </ul>\n";
  $body .= "        </li>\n";
  $body .= "        <li class=\"treeview\">\n";
  $body .= "          <a href=\"#\">\n";
  $body .= "            <i class=\"fa fa-share\"></i> <span>Multilevel</span>\n";
  $body .= "            <span class=\"pull-right-container\">\n";
  $body .= "              <i class=\"fa fa-angle-left pull-right\"></i>\n";
  $body .= "            </span>\n";
  $body .= "          </a>\n";
  $body .= "          <ul class=\"treeview-menu\">\n";
  $body .= "            <li><a href=\"#\"><i class=\"fa fa-circle-o\"></i> Level One</a></li>\n";
  $body .= "            <li class=\"treeview\">\n";
  $body .= "              <a href=\"#\"><i class=\"fa fa-circle-o\"></i> Level One\n";
  $body .= "                <span class=\"pull-right-container\">\n";
  $body .= "                  <i class=\"fa fa-angle-left pull-right\"></i>\n";
  $body .= "                </span>\n";
  $body .= "              </a>\n";
  $body .= "              <ul class=\"treeview-menu\">\n";
  $body .= "                <li><a href=\"#\"><i class=\"fa fa-circle-o\"></i> Level Two</a></li>\n";
  $body .= "                <li class=\"treeview\">\n";
  $body .= "                  <a href=\"#\"><i class=\"fa fa-circle-o\"></i> Level Two\n";
  $body .= "                    <span class=\"pull-right-container\">\n";
  $body .= "                      <i class=\"fa fa-angle-left pull-right\"></i>\n";
  $body .= "                    </span>\n";
  $body .= "                  </a>\n";
  $body .= "                  <ul class=\"treeview-menu\">\n";
  $body .= "                    <li><a href=\"#\"><i class=\"fa fa-circle-o\"></i> Level Three</a></li>\n";
  $body .= "                    <li><a href=\"#\"><i class=\"fa fa-circle-o\"></i> Level Three</a></li>\n";
  $body .= "                  </ul>\n";
  $body .= "                </li>\n";
  $body .= "              </ul>\n";
  $body .= "            </li>\n";
  $body .= "            <li><a href=\"#\"><i class=\"fa fa-circle-o\"></i> Level One</a></li>\n";
  $body .= "          </ul>\n";
  $body .= "        </li>\n";
  $body .= "        <li><a href=\"https://adminlte.io/docs\"><i class=\"fa fa-book\"></i> <span>Documentation</span></a></li>\n";
  $body .= "        <li class=\"header\">LABELS</li>\n";
  $body .= "        <li><a href=\"#\"><i class=\"fa fa-circle-o text-red\"></i> <span>Important</span></a></li>\n";
  $body .= "        <li><a href=\"#\"><i class=\"fa fa-circle-o text-yellow\"></i> <span>Warning</span></a></li>\n";
  $body .= "        <li><a href=\"#\"><i class=\"fa fa-circle-o text-aqua\"></i> <span>Information</span></a></li>\n";
*/
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
  $body .= "    <strong>" . __ ( "Developed by") . " <a href=\"mailto:azevedo@intellinews.com.br\">Ernani Azevedo</a>. " . sprintf ( __ ( "Published under %s license."), "<a href=\"https://www.gnu.org/licenses/gpl-3.0.en.html\" target=\"_blank\">GPLv3</a>") . "</strong>\n";
  $body .= "  </footer>\n";
/*
  $body .= "  <aside class=\"control-sidebar control-sidebar-dark\">\n";
  $body .= "    <ul class=\"nav nav-tabs nav-justified control-sidebar-tabs\">\n";
  $body .= "      <li class=\"active\"><a href=\"#control-sidebar-home-tab\" data-toggle=\"tab\"><i class=\"fa fa-home\"></i></a></li>\n";
  $body .= "      <li><a href=\"#control-sidebar-settings-tab\" data-toggle=\"tab\"><i class=\"fa fa-gears\"></i></a></li>\n";
  $body .= "    </ul>\n";
  $body .= "    <div class=\"tab-content\">\n";
  $body .= "      <div class=\"tab-pane active\" id=\"control-sidebar-home-tab\">\n";
  $body .= "        <h3 class=\"control-sidebar-heading\">Recent Activity</h3>\n";
  $body .= "        <ul class=\"control-sidebar-menu\">\n";
  $body .= "          <li>\n";
  $body .= "            <a href=\"javascript:void(0)\">\n";
  $body .= "              <i class=\"menu-icon fa fa-birthday-cake bg-red\"></i>\n";
  $body .= "              <div class=\"menu-info\">\n";
  $body .= "                <h4 class=\"control-sidebar-subheading\">Langdon's Birthday</h4>\n";
  $body .= "                <p>Will be 23 on April 24th</p>\n";
  $body .= "              </div>\n";
  $body .= "            </a>\n";
  $body .= "          </li>\n";
  $body .= "          <li>\n";
  $body .= "            <a href=\"javascript:void(0)\">\n";
  $body .= "              <i class=\"menu-icon fa fa-user bg-yellow\"></i>\n";
  $body .= "              <div class=\"menu-info\">\n";
  $body .= "                <h4 class=\"control-sidebar-subheading\">Frodo Updated His Profile</h4>\n";
  $body .= "                <p>New phone +1(800)555-1234</p>\n";
  $body .= "              </div>\n";
  $body .= "            </a>\n";
  $body .= "          </li>\n";
  $body .= "          <li>\n";
  $body .= "            <a href=\"javascript:void(0)\">\n";
  $body .= "              <i class=\"menu-icon fa fa-envelope-o bg-light-blue\"></i>\n";
  $body .= "              <div class=\"menu-info\">\n";
  $body .= "                <h4 class=\"control-sidebar-subheading\">Nora Joined Mailing List</h4>\n";
  $body .= "                <p>nora@example.com</p>\n";
  $body .= "              </div>\n";
  $body .= "            </a>\n";
  $body .= "          </li>\n";
  $body .= "          <li>\n";
  $body .= "            <a href=\"javascript:void(0)\">\n";
  $body .= "              <i class=\"menu-icon fa fa-file-code-o bg-green\"></i>\n";
  $body .= "              <div class=\"menu-info\">\n";
  $body .= "                <h4 class=\"control-sidebar-subheading\">Cron Job 254 Executed</h4>\n";
  $body .= "                <p>Execution time 5 seconds</p>\n";
  $body .= "              </div>\n";
  $body .= "            </a>\n";
  $body .= "          </li>\n";
  $body .= "        </ul>\n";
  $body .= "        <h3 class=\"control-sidebar-heading\">Tasks Progress</h3>\n";
  $body .= "        <ul class=\"control-sidebar-menu\">\n";
  $body .= "          <li>\n";
  $body .= "            <a href=\"javascript:void(0)\">\n";
  $body .= "              <h4 class=\"control-sidebar-subheading\">\n";
  $body .= "                Custom Template Design\n";
  $body .= "                <span class=\"label label-danger pull-right\">70%</span>\n";
  $body .= "              </h4>\n";
  $body .= "              <div class=\"progress progress-xxs\">\n";
  $body .= "                <div class=\"progress-bar progress-bar-danger\" style=\"width: 70%\"></div>\n";
  $body .= "              </div>\n";
  $body .= "            </a>\n";
  $body .= "          </li>\n";
  $body .= "          <li>\n";
  $body .= "            <a href=\"javascript:void(0)\">\n";
  $body .= "              <h4 class=\"control-sidebar-subheading\">\n";
  $body .= "                Update Resume\n";
  $body .= "                <span class=\"label label-success pull-right\">95%</span>\n";
  $body .= "              </h4>\n";
  $body .= "              <div class=\"progress progress-xxs\">\n";
  $body .= "                <div class=\"progress-bar progress-bar-success\" style=\"width: 95%\"></div>\n";
  $body .= "              </div>\n";
  $body .= "            </a>\n";
  $body .= "          </li>\n";
  $body .= "          <li>\n";
  $body .= "            <a href=\"javascript:void(0)\">\n";
  $body .= "              <h4 class=\"control-sidebar-subheading\">\n";
  $body .= "                Laravel Integration\n";
  $body .= "                <span class=\"label label-warning pull-right\">50%</span>\n";
  $body .= "              </h4>\n";
  $body .= "              <div class=\"progress progress-xxs\">\n";
  $body .= "                <div class=\"progress-bar progress-bar-warning\" style=\"width: 50%\"></div>\n";
  $body .= "              </div>\n";
  $body .= "            </a>\n";
  $body .= "          </li>\n";
  $body .= "          <li>\n";
  $body .= "            <a href=\"javascript:void(0)\">\n";
  $body .= "              <h4 class=\"control-sidebar-subheading\">\n";
  $body .= "                Back End Framework\n";
  $body .= "                <span class=\"label label-primary pull-right\">68%</span>\n";
  $body .= "              </h4>\n";
  $body .= "              <div class=\"progress progress-xxs\">\n";
  $body .= "                <div class=\"progress-bar progress-bar-primary\" style=\"width: 68%\"></div>\n";
  $body .= "              </div>\n";
  $body .= "            </a>\n";
  $body .= "          </li>\n";
  $body .= "        </ul>\n";
  $body .= "      </div>\n";
  $body .= "      <div class=\"tab-pane\" id=\"control-sidebar-stats-tab\">Stats Tab Content</div>\n";
  $body .= "      <div class=\"tab-pane\" id=\"control-sidebar-settings-tab\">\n";
  $body .= "        <form method=\"post\">\n";
  $body .= "          <h3 class=\"control-sidebar-heading\">General Settings</h3>\n";
  $body .= "          <div class=\"form-group\">\n";
  $body .= "            <label class=\"control-sidebar-subheading\">\n";
  $body .= "              Report panel usage\n";
  $body .= "              <input type=\"checkbox\" class=\"pull-right\" checked>\n";
  $body .= "            </label>\n";
  $body .= "            <p>\n";
  $body .= "              Some information about this general settings option\n";
  $body .= "            </p>\n";
  $body .= "          </div>\n";
  $body .= "          <div class=\"form-group\">\n";
  $body .= "            <label class=\"control-sidebar-subheading\">\n";
  $body .= "              Allow mail redirect\n";
  $body .= "              <input type=\"checkbox\" class=\"pull-right\" checked>\n";
  $body .= "            </label>\n";
  $body .= "            <p>\n";
  $body .= "              Other sets of options are available\n";
  $body .= "            </p>\n";
  $body .= "          </div>\n";
  $body .= "          <div class=\"form-group\">\n";
  $body .= "            <label class=\"control-sidebar-subheading\">\n";
  $body .= "              Expose author name in posts\n";
  $body .= "              <input type=\"checkbox\" class=\"pull-right\" checked>\n";
  $body .= "            </label>\n";
  $body .= "            <p>\n";
  $body .= "              Allow the user to show his name in blog posts\n";
  $body .= "            </p>\n";
  $body .= "          </div>\n";
  $body .= "          <h3 class=\"control-sidebar-heading\">Chat Settings</h3>\n";
  $body .= "          <div class=\"form-group\">\n";
  $body .= "            <label class=\"control-sidebar-subheading\">\n";
  $body .= "              Show me as online\n";
  $body .= "              <input type=\"checkbox\" class=\"pull-right\" checked>\n";
  $body .= "            </label>\n";
  $body .= "          </div>\n";
  $body .= "          <div class=\"form-group\">\n";
  $body .= "            <label class=\"control-sidebar-subheading\">\n";
  $body .= "              Turn off notifications\n";
  $body .= "              <input type=\"checkbox\" class=\"pull-right\">\n";
  $body .= "            </label>\n";
  $body .= "          </div>\n";
  $body .= "          <div class=\"form-group\">\n";
  $body .= "            <label class=\"control-sidebar-subheading\">\n";
  $body .= "              Delete chat history\n";
  $body .= "              <a href=\"javascript:void(0)\" class=\"text-red pull-right\"><i class=\"fa fa-trash-o\"></i></a>\n";
  $body .= "            </label>\n";
  $body .= "          </div>\n";
  $body .= "        </form>\n";
  $body .= "      </div>\n";
  $body .= "    </div>\n";
  $body .= "  </aside>\n";
  $body .= "  <div class=\"control-sidebar-bg\"></div>\n";
*/
  $body .= "</div>\n";

  /**
   * Prepare page javascript codes
   */
  $footer = "\n";

  // jQuery and jQuery Loader are mandatory
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/jquery/jquery" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/jquery-loader/jquery.loader" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";

  // Add builtin code
  $footer .= "<script type=\"text/javascript\" class=\"jsonload\">\n";
  $footer .= "  $(document).ready ( function ()\n";
  $footer .= "  {\n";

  // Load all interface framework JavaScript and CSS files
  $footer .= "    $('html, body').css ( 'overflow', 'hidden');\n";
  $footer .= "    $.loader (\n";
  $footer .= "    {\n";
  $footer .= "      js: [\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'bootstrap',\n";
  $footer .= "              'src': '/vendors/bootstrap/js/bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'slimscroll',\n";
  $footer .= "              'src': '/vendors/jquery-slimscroll/jquery.slimscroll" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'fastclick',\n";
  $footer .= "              'src': '/vendors/fastclick/lib/fastclick" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'history',\n";
  $footer .= "              'src': '/vendors/history/history" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'select2',\n";
  $footer .= "              'src': '/vendors/select2/dist/js/select2.full" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'select2-i18n',\n";
  $footer .= "              'src': '/vendors/select2/dist/js/i18n/pt-BR" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': [ 'select2']\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'pnotify',\n";
  $footer .= "              'src': '/vendors/pnotify/dist/pnotify" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'spin',\n";
  $footer .= "              'src': '/vendors/spin/spin" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'ladda',\n";
  $footer .= "              'src': '/vendors/ladda/dist/ladda" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': [ 'spin']\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'scrollTo',\n";
  $footer .= "              'src': '/vendors/jquery-scrollTo/jquery.scrollTo" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'aSimpleTour',\n";
  $footer .= "              'src': '/vendors/aSimpleTour/jquery.aSimpleTour" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': [ 'scrollTo']\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'AdminLTE',\n";
  $footer .= "              'src': '/js/adminlte" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': [ 'bootstrap']\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'voipdomain',\n";
  $footer .= "              'src': '/js/voipdomain" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'system',\n";
  $footer .= "              'src': '/js/system" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js',\n";
  $footer .= "              'dep': [ 'bootstrap', 'AdminLTE', 'voipdomain']\n";
  $footer .= "            }\n";
  $footer .= "          ],\n";
  $footer .= "      css: [\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'bootstrap',\n";
  $footer .= "               'src': '/vendors/bootstrap/css/bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'select2',\n";
  $footer .= "               'src': '/vendors/select2/dist/css/select2" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'select2-bootstrap',\n";
  $footer .= "               'src': '/vendors/select2-bootstrap-theme/dist/select2-bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'dep': [ 'bootstrap', 'select2', 'AdminLTE']\n";
  $footer .= "             },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'pnotify',\n";
  $footer .= "              'src': '/vendors/pnotify/dist/pnotify" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "            {\n";
  $footer .= "              'name': 'ladda',\n";
  $footer .= "              'src': '/vendors/ladda/dist/ladda-themeless" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "              'dep': []\n";
  $footer .= "            },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'font-awesome',\n";
  $footer .= "               'src': '/vendors/font-awesome/web-fonts-with-css/css/fontawesome-all" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'ionicons',\n";
  $footer .= "               'src': '/vendors/ionicons/css/ionicons" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'dep': []\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'AdminLTE',\n";
  $footer .= "               'src': '/css/AdminLTE" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'dep': [ 'bootstrap', 'font-awesome', 'ionicons']\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'adminlte-all-skins',\n";
  $footer .= "               'src': '/css/skins/_all-skins" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'dep': [ 'AdminLTE']\n";
  $footer .= "             },\n";
  $footer .= "             {\n";
  $footer .= "               'name': 'system',\n";
  $footer .= "               'src': '/css/system" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css',\n";
  $footer .= "               'dep': [ 'AdminLTE']\n";
  $footer .= "             }\n";
  $footer .= "           ],\n";
  $footer .= "      onrefresh: function ( loaded, total, percent)\n";
  $footer .= "                 {\n";
  $footer .= "                   $('.percent').animate ( { width: percent + '%'}, 50);\n";
  $footer .= "                 },\n";
  $footer .= "      onfinish: function ()\n";
  $footer .= "                {\n";
  $footer .= "                  VoIP.settings (\n";
  $footer .= "                  {\n";
  $footer .= "                    title: '" . addslashes ( strip_tags ( $_in["general"]["title"])) . "',\n";
  $footer .= "                    user: '" . addslashes ( strip_tags ( $_in["session"]["User"])) . "',\n";
  $footer .= "                    name: '" . addslashes ( strip_tags ( $_in["session"]["Name"])) . "',\n";
  $footer .= "                    uid: '" . addslashes ( strip_tags ( $_in["session"]["ID"])) . "',\n";
  $footer .= "                  });\n";
  $footer .= "                  $('html, body').css ( 'overflow', '');\n";
  $footer .= "                  $('.percent').fadeOut ( 'slow').prependTo ( 'div.content-wrapper');\n";
  $footer .= "                  $('.preload').fadeOut ( 'slow');\n";
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
framework_add_hook ( "login_page_generate", "page_login", IN_HOOK_INSERT_FIRST);

/**
 * Function to create the main login page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function page_login ( $buffer, $parameters)
{
  global $_in;

  /**
   * Prepare the page HTML head
   */
  $head = "<!DOCTYPE html>\n";
  $head .= "<html lang=\"" . ( ! empty ( $_in["general"]["language"]) ? $_in["general"]["language"] : "en_US") . "\" class=\"no-js\">\n";
  $head .= "<head>\n";
  $head .= "  <meta charset=\"" . ( ! empty ( $_in["general"]["charset"]) ? strtolower ( $_in["general"]["charset"]) : "utf-8") . "\">\n";
  $head .= "  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
  $head .= "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\">\n";
  if ( ! empty ( $_in["general"]["description"]))
  {
    $head .= "  <meta name=\"description\" content=\"" . addslashes ( strip_tags ( $_in["general"]["description"])) . "\">\n";
  }
  if ( ! empty ( $_in["general"]["author"]))
  {
    $head .= "  <meta name=\"author\" content=\"" . addslashes ( strip_tags ( $_in["general"]["author"])) . "\">\n";
  }
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
  $head .= "  <style type=\"text/css\">\n";
  $head .= "    .no-js #loader\n";
  $head .= "    {\n";
  $head .= "      display: none;\n";
  $head .= "    }\n";
  $head .= "    .js #loader\n";
  $head .= "    {\n";
  $head .= "      display: block;\n";
  $head .= "      position: absolute;\n";
  $head .= "      left: 100px;\n";
  $head .= "      top: 0;\n";
  $head .= "    }\n";
  $head .= "    .preload\n";
  $head .= "    {\n";
  $head .= "      position: fixed;\n";
  $head .= "      left: 0px;\n";
  $head .= "      top: 0px;\n";
  $head .= "      width: 100%;\n";
  $head .= "      height: 100%;\n";
  $head .= "      z-index: 9999;\n";
  $head .= "      background: url('/img/preloader.gif') center no-repeat #fff;\n";
  $head .= "    }\n";
  $head .= "  </style>\n";

  // Add interface CSS files
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/vendors/bootstrap/css/bootstrap" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family=Open+Sans:600italic,400,300,600,700?type=.css\" />\n";
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/vendors/pnotify/dist/pnotify" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";
  $head .= "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/vendors/pnotify/dist/pnotify-animate" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";
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
  $body = "<main class=\"noselect\">\n";
  $body .= "  <div class=\"preload\"></div>\n";
  $body .= "  <div class=\"auth-block\">\n";
  $body .= "    <div class=\"auth-top\">\n";
  $body .= "      <h1>" . ( ! empty ( $_in["general"]["title"]) ? strip_tags ( $_in["general"]["title"]) : "") . "</h1>\n";
  $body .= "      <h2>" . __ ( "Authentication") . "</h2>\n";
  $body .= "    </div>\n";
  $body .= "    <form id=\"login\">\n";
  $body .= "      <li><input id=\"user\" name=\"user\" type=\"text\" placeholder=\"" . __ ( "User") . "\" spellcheck=\"false\" autocapitalize=\"off\" autocorrect=\"off\" autocomplete=\"off\" /><i class=\"icon user\"></i></li>\n";
  $body .= "      <li><input id=\"pass\" name=\"pass\" type=\"password\" placeholder=\"" . __ ( "Password") . "\" autocomplete=\"off\" /><i class=\"icon lock\"></i></li>\n";
  $body .= "      <div class=\"auth-bottom\">\n";
  $body .= "        <a href=\"mailto:" . addslashes ( strip_tags ( $_in["general"]["administrator"])) . "\" class=\"auth-link\">" . __ ( "Forgot your password?") . "</a>\n";
  $body .= "        <button type=\"submit\" class=\"ladda-button\" data-style=\"zoom-in\">" . __ ( "Login") . "</button>\n";
  $body .= "      </div>\n";
  $body .= "    </form>\n";
  $body .= "  </div>\n";
  $body .= "</main>\n";

  /**
   * Prepare page javascript codes
   */
  $footer = "\n";

  // jQuery is mandatory
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/jquery/jquery" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";

  // Add interface framework JavaScript files
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/pnotify/dist/pnotify" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/spin/spin" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/ladda/dist/ladda" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";
  $footer .= "<script type=\"text/javascript\" src=\"/vendors/ladda/dist/ladda.jquery" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>\n";

  /**
   * Add authentication page javascript code
   */
  $footer .= "<script type=\"text/javascript\" class=\"jsonload\">\n";
  $footer .= "  $(document).ready ( function ()\n";
  $footer .= "  {\n";
  $footer .= "    var image = new Image ();\n";
  $footer .= "    image.onload = function ()\n";
  $footer .= "    {\n";
  $footer .= "      $('.preload').fadeOut ( 'slow');\n";
  if ( ! empty ( $parameters["message"]))
  {
    $footer .= "      new PNotify ( { title: '" . __ ( "Authentication") . "', text: '" . htmlentities ( $parameters["message"], ENT_COMPAT, "UTF-8") . "', type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});";
  }
  $footer .= "    }\n";
  $footer .= "    image.src = '/img/bg-login.jpg';\n";
  $footer .= "    $('#user').focus ();\n";
  $footer .= "    $('#login').on ( 'submit', function ( e)\n";
  $footer .= "    {\n";
  $footer .= "      e && e.preventDefault ();\n";
  $footer .= "      var l = $('button[type=\"submit\"]').ladda ();\n";
  $footer .= "      l.ladda ( 'start');\n";
  $footer .= "      $('#user, #pass, button[type=\"submit\"]').attr ( 'disabled', 'disabled');\n";
  $footer .= "      $.ajax (\n";
  $footer .= "      {\n";
  $footer .= "        type: 'POST',\n";
  $footer .= "        url: '/api/sys/session',\n";
  $footer .= "        data: JSON.stringify ( { user: $('#user').val (), pass: $('#pass').val ()}),\n";
  $footer .= "        headers: {\n";
  $footer .= "                   'X-HTTP-Method-Override': 'POST',\n";
  $footer .= "                   'Accept': 'application/json'\n";
  $footer .= "                 },\n";
  $footer .= "        contentType: 'application/json',\n";
  $footer .= "        dataType: 'json',\n";
  $footer .= "        success: function ( data, textStatus, jqXHR)\n";
  $footer .= "                 {\n";
  $footer .= "                   if ( data.result == true)\n";
  $footer .= "                   {\n";
  $footer .= "                     location.reload ();\n";
  $footer .= "                   } else {\n";
  $footer .= "                     new PNotify ( { title: '" . __ ( "Authentication") . "', text: ( data.message ? data.message : '" . __ ( "Error authorizing user.") . "'), type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});\n";
  $footer .= "                     l.ladda ( 'stop');\n";
  $footer .= "                     $('#user, #pass, button[type=\"submit\"]').removeAttr ( 'disabled');\n";
  $footer .= "                     $('#user').focus ();\n";
  $footer .= "                   }\n";
  $footer .= "                 },\n";
  $footer .= "        error: function ( jqXHR, textStatus, errorThrown)\n";
  $footer .= "               {\n";
  $footer .= "                 new PNotify ( { title: '" . __ ( "Authentication") . "', text: ( data.message ? data.message : '" . __ ( "Error authorizing user.") . "'), type: 'error', styling: 'bootstrap3', 'animate_speed': 'slow'});\n";
  $footer .= "                 l.ladda ( 'stop');\n";
  $footer .= "                 $('#user, #pass, button[type=\"submit\"]').removeAttr ( 'disabled');\n";
  $footer .= "                 $('#user').focus ();\n";
  $footer .= "               }\n";
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
  sys_addcss ( array ( "name" => "bootstrap-datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "daterangepicker", "src" => "/vendors/daterangepicker/daterangepicker.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "jquery-knob", "src" => "/vendors/jquery-knob/dist/jquery.knob.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "daterangepicker", "src" => "/vendors/daterangepicker/daterangepicker.js", "dep" => array ( "moment")));

  /**
   * Dashboard
   */
  $output = "<div class=\"pull-right\">\n";
  $output .= "  <div id=\"range\" class=\"daterangepicker-input\"><i class=\"glyphicon glyphicon-calendar\"></i> <span> - </span> <b class=\"caret\"></b></div>\n";
  $output .= "</div>\n";
  $output .= "<div class=\"clearfix\"></div>\n";

  // Draw informations knob dials
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
              "    'Hoje': [ moment (), moment ()],\n" .
              "    'Ontem': [ moment ().subtract ( 1, 'days'), moment ().subtract ( 1, 'days')],\n" .
              "    'Últimos 7 dias': [ moment ().subtract ( 6, 'days'), moment ()],\n" .
              "    'Últimos 30 dias': [ moment ().subtract ( 29, 'days'), moment ()],\n" .
              "    'Este mês': [ moment ().startOf ( 'month'), moment ().endOf ( 'month')],\n" .
              "    'Último mês': [ moment ().subtract ( 1, 'month').startOf ( 'month'), moment ().subtract ( 1, 'month').endOf ( 'month')]\n" .
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
              "  $('#stats').trigger ( 'update', start.format ( 'MM/DD/YYYY'), end.format ( 'MM/DD/YYYY'));\n" .
              "});\n" .
              "$('#stats').on ( 'update', function ( event, start, end)\n" .
              "{\n" .
              "  var dashboard = VoIP.rest ( '/sys/dashboard', 'GET', { start: start, end: end});\n" .
              "  if ( dashboard.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#dashboard_asr').val ( dashboard.result.asr.percent).trigger ( 'change');\n" .
              "    $('#dashboard_asr_value').html ( dashboard.result.asr.value);\n" .
              "    $('#dashboard_asr_total').html ( dashboard.result.asr.total);\n" .
              "    $('#dashboard_ner').val ( dashboard.result.ner.percent).trigger ( 'change');\n" .
              "    $('#dashboard_ner_value').html ( dashboard.result.ner.value);\n" .
              "    $('#dashboard_ner_total').html ( dashboard.result.ner.total);\n" .
              "    $('#dashboard_sbr').val ( dashboard.result.sbr.percent).trigger ( 'change');\n" .
              "    $('#dashboard_sbr_value').html ( dashboard.result.sbr.value);\n" .
              "    $('#dashboard_sbr_total').html ( dashboard.result.sbr.total);\n" .
              "    $('#dashboard_scr').val ( dashboard.result.scr.percent).trigger ( 'change');\n" .
              "    $('#dashboard_scr_value').html ( dashboard.result.scr.value);\n" .
              "    $('#dashboard_scr_total').html ( dashboard.result.scr.total);\n" .
              "    $('#dashboard_lcr').val ( dashboard.result.lcr.percent).trigger ( 'change');\n" .
              "    $('#dashboard_lcr_value').html ( dashboard.result.lcr.value);\n" .
              "    $('#dashboard_lcr_total').html ( dashboard.result.lcr.total);\n" .
              "    $('#dashboard_allocation').val ( dashboard.result.allocation.percent).trigger ( 'change');\n" .
              "    $('#dashboard_allocation_value').html ( dashboard.result.allocation.value);\n" .
              "    $('#dashboard_allocation_total').html ( dashboard.result.allocation.total);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Dashboard") . "', text: '" . __ ( "Error requesting statistics!") . "', type: 'error'});\n" .
              "  }\n" .
              "}).trigger ( 'update');\n");

  /**
   * Return generated HTML
   */
  return $output;
}
?>
