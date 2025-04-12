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
 * VoIP Domain profiles module WebUI. This module manage profiles. You can have
 * one or more profiles managed by the system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Profiles
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/profiles", "profiles_search_page");
framework_add_hook ( "profiles_search_page", "profiles_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/profiles/add", "profiles_add_page");
framework_add_hook ( "profiles_add_page", "profiles_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/profiles/:id/clone", "profiles_clone_function");
framework_add_hook ( "profiles_clone_function", "profiles_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/profiles/:id/view", "profiles_view_page");
framework_add_hook ( "profiles_view_page", "profiles_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/profiles/:id/edit", "profiles_edit_page");
framework_add_hook ( "profiles_edit_page", "profiles_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main profiles page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Profiles"));
  sys_set_subtitle ( __ ( "profiles search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Profiles"))
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
   * Profile search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add profile remove modal code
   */
  $output .= "<div id=\"profile_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"profile_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove profile") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the profile %s?"), "<span id=\"profile_delete_description\"></span>") . "</p><input type=\"hidden\" id=\"profile_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '60%', class: 'export all'},\n" .
              "             { data: 'Domain', title: '" . __ ( "Domain") . "', width: '20%', class: 'export all'},\n" .
              "             { data: 'InUse', title: '" . __ ( "In use") . "', width: '10%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return '<span class=\"label label-' + ( row.InUse ? 'success' : 'danger') + '\">' + ( row.InUse ? '" . __ ( "Yes") . "' : '" . __ ( "No") . "') + '</span>'; }},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/profiles/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/profiles/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/profiles/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-description=\"' + row.Description + '\"' + ( row.InUse != 0 ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/profiles', fields: 'ID,Description,Domain,InUse,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/profiles/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#profile_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#profile_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#profile_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#profile_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#profile_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#profile_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/profiles/' + encodeURIComponent ( $('#profile_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#profile_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Profile remove") . "', text: '" . __ ( "Profile removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#profile_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Profile remove") . "', text: '" . __ ( "Error removing profile!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the profile add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Profiles"));
  sys_set_subtitle ( __ ( "profiles addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Profiles"), "link" => "/profiles"),
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
  sys_addjs ( array ( "name" => "multiselect", "src" => "/vendors/multiselect/dist/js/multiselect.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "maphighlight", "src" => "/vendors/maphilight/jquery.maphilight.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "timezonepicker", "src" => "/vendors/timezonepicker/lib/jquery.timezone-picker.js", "dep" => array ( "maphighlight")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"profile_add_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#profile_add_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#profile_add_tab_geographical\">" . __ ( "Geographical") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#profile_add_tab_gateways\">" . __ ( "Gateways") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"profile_add_tab_basic\">\n";

  // Add profile description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"profile_add_description\" placeholder=\"" . __ ( "Profile description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile domain field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Domain\" class=\"form-control\" id=\"profile_add_domain\" placeholder=\"" . __ ( "Profile domain") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile external prefix field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_prefix\" class=\"control-label col-xs-2\">" . __ ( "External prefix") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Prefix\" class=\"form-control\" id=\"profile_add_prefix\" placeholder=\"" . __ ( "Prefix to access PSTN calls") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile music on hold selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_moh\" class=\"control-label col-xs-2\">" . __ ( "Music on hold") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"MOH\" id=\"profile_add_moh\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile music on hold") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile emergency numbers shortcut option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_emergency\" class=\"control-label col-xs-2\">" . __ ( "Emergency shortcut") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Emergency\" id=\"profile_add_emergency\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Geographical panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"profile_add_tab_geographical\">\n";

  // Add timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Map") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"hidden\" name=\"Offset\" id=\"profile_add_offset\" value=\"\">\n";
  $output .= "          <img id=\"profile_add_timezone_image\" src=\"/vendors/timezonepicker/images/living-600.jpg\" width=\"600\" height=\"300\" usemap=\"#profile_add_timezone_map\" />\n";
  $output .= "          <img class=\"timezone-pin\" src=\"/vendors/timezonepicker/images/pin.png\" style=\"padding-top: 4px;\" />\n";
  $output .= "          <map name=\"profile_add_timezone_map\" id=\"profile_add_timezone_map\">\n";
  $output .= "            <area data-timezone=\"Africa/Abidjan\" data-country=\"CI\" data-pin=\"293,141\" data-offset=\"0\" shape=\"poly\" coords=\"290,142,287,143,288,140,286,139,286,137,287,137,286,136,287,136,286,133,290,132,290,133,291,133,293,134,295,133,296,136,295,139,295,141,290,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Accra\" data-country=\"GH\" data-pin=\"300,141\" data-offset=\"0\" shape=\"poly\" coords=\"301,140,297,142,295,142,295,141,295,139,296,136,295,132,300,132,301,136,301,139,302,140,301,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Addis_Ababa\" data-country=\"ET\" data-pin=\"365,135\" data-offset=\"3\" shape=\"poly\" coords=\"375,142,373,142,370,143,368,143,366,144,360,142,358,139,355,137,355,136,357,136,357,132,358,132,359,129,360,129,361,126,363,127,363,125,364,126,368,126,371,129,370,132,372,132,371,132,372,134,380,137,375,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Algiers\" data-country=\"DZ\" data-pin=\"305,89\" data-offset=\"1\" shape=\"poly\" coords=\"316,100,317,104,317,106,316,106,317,109,319,110,320,111,310,118,306,118,305,117,303,116,300,113,286,105,286,102,286,102,291,100,292,99,294,98,294,97,295,97,295,96,298,96,298,96,297,95,297,92,296,92,305,89,314,88,314,89,314,92,313,94,315,97,316,100\" />\n";
  $output .= "            <area data-timezone=\"Africa/Asmara\" data-country=\"ER\" data-pin=\"365,124\" data-offset=\"3\" shape=\"poly\" coords=\"367,125,372,129,371,129,367,126,364,126,363,125,363,127,361,126,362,122,364,120,366,125,366,124,367,125\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bamako\" data-country=\"ML\" data-pin=\"287,129\" data-offset=\"0\" shape=\"poly\" coords=\"293,129,292,130,291,130,291,133,290,133,290,132,288,133,287,133,287,133,286,132,286,132,286,131,285,129,282,130,281,130,281,128,280,126,281,124,282,125,284,124,291,124,289,108,292,108,302,115,303,116,305,117,305,118,307,118,307,122,306,124,299,125,295,127,294,128,293,128,293,129\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bangui\" data-country=\"CF\" data-pin=\"331,143\" data-offset=\"1\" shape=\"poly\" coords=\"341,142,339,142,338,142,337,143,332,141,331,143,331,144,328,144,327,146,325,142,324,140,326,137,331,137,331,135,334,135,336,132,338,132,339,134,339,135,340,135,340,136,342,137,346,142,343,141,342,142,341,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Banjul\" data-country=\"GM\" data-pin=\"272,128\" data-offset=\"0\" shape=\"poly\" coords=\"277,127,272,128,275,127,277,127\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bissau\" data-country=\"GW\" data-pin=\"274,130\" data-offset=\"0\" shape=\"poly\" coords=\"274,131,274,131,274,131\" />\n";
  $output .= "            <area data-timezone=\"Africa/Blantyre\" data-country=\"MW\" data-pin=\"358,176\" data-offset=\"2\" shape=\"poly\" coords=\"358,172,360,175,359,179,357,177,358,174,356,174,354,173,356,171,355,168,356,168,355,166,358,167,358,169,357,171,358,172\" />\n";
  $output .= "            <area data-timezone=\"Africa/Brazzaville\" data-country=\"CG\" data-pin=\"325,157\" data-offset=\"1\" shape=\"poly\" coords=\"319,157,320,156,319,154,321,154,321,153,323,154,324,153,324,151,323,150,324,148,323,148,322,148,322,146,327,147,328,144,331,144,330,151,327,154,327,157,324,158,324,157,322,158,321,157,320,158,319,157\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bujumbura\" data-country=\"BI\" data-pin=\"349,156\" data-offset=\"2\" shape=\"poly\" coords=\"350,157,349,157,348,154,349,155,350,154,351,154,351,155,350,157\" />\n";
  $output .= "            <area data-timezone=\"Asia/Oral\" data-country=\"KZ\" data-pin=\"386,65\" data-offset=\"5\" shape=\"poly\" coords=\"379,66,381,67,381,67,381,66,382,65,385,64,387,64,391,65,391,67,388,69,386,69,382,70,377,69,379,66\" />\n";
  $output .= "            <area data-timezone=\"Africa/Cairo\" data-country=\"EG\" data-pin=\"352,100\" data-offset=\"2\" shape=\"poly\" coords=\"353,113,342,113,341,100,342,97,348,99,352,97,353,97,353,98,357,98,358,101,357,104,355,102,354,100,354,101,360,110,359,110,359,111,357,114,353,113\" />\n";
  $output .= "            <area data-timezone=\"Africa/Casablanca\" data-country=\"MA\" data-pin=\"287,94\" data-offset=\"0\" shape=\"poly\" coords=\"290,100,288,101,286,102,286,104,278,104,283,101,284,100,284,98,285,96,289,93,290,90,291,90,293,91,295,91,297,92,298,96,295,96,295,97,294,97,294,98,292,99,290,100\" />\n";
  $output .= "            <area data-timezone=\"Africa/Ceuta\" data-country=\"ES\" data-pin=\"291,90\" data-offset=\"1\" shape=\"poly\" coords=\"291,90,291,90,291,90\" />\n";
  $output .= "            <area data-timezone=\"Africa/Conakry\" data-country=\"GN\" data-pin=\"277,134\" data-offset=\"0\" shape=\"poly\" coords=\"285,137,284,138,284,136,282,136,283,136,281,133,279,134,278,135,277,133,276,133,275,131,277,130,277,129,281,129,282,130,285,129,286,131,286,132,286,132,287,133,286,134,287,136,286,136,286,137,285,137\" />\n";
  $output .= "            <area data-timezone=\"Africa/Dakar\" data-country=\"SN\" data-pin=\"271,126\" data-offset=\"0\" shape=\"poly\" coords=\"272,128,275,127,277,128,275,127,272,127,271,125,273,122,276,122,280,126,281,129,275,129,272,129,273,129,272,129,272,128\" />\n";
  $output .= "            <area data-timezone=\"Africa/Dar_es_Salaam\" data-country=\"TZ\" data-pin=\"365,161\" data-offset=\"3\" shape=\"poly\" coords=\"367,167,367,167,367,168,362,170,358,169,357,166,352,164,349,161,349,158,351,155,351,155,351,153,351,152,357,152,363,155,363,156,365,158,365,161,366,162,365,164,367,167\" />\n";
  $output .= "            <area data-timezone=\"Asia/Yekaterinburg\" data-country=\"RU\" data-pin=\"401,55\" data-offset=\"6\" shape=\"poly\" coords=\"400,63,403,64,402,65,399,66,397,65,395,65,393,66,391,65,391,66,389,64,385,64,387,62,387,60,389,60,389,58,389,58,390,57,389,57,391,56,390,55,391,55,389,52,390,52,389,51,390,50,387,50,386,49,392,49,394,47,399,47,399,42,410,38,410,37,410,37,409,37,409,36,408,35,410,35,411,35,409,35,414,36,415,35,414,34,411,34,412,33,412,32,411,32,414,30,416,28,421,29,420,31,421,32,421,33,421,35,423,36,420,39,415,39,415,39,420,40,425,37,424,35,427,35,429,36,428,37,429,38,433,38,429,37,430,36,429,35,423,35,423,34,424,32,422,31,425,30,425,29,426,30,425,31,426,31,431,32,427,30,430,30,429,30,430,29,434,30,432,31,434,32,435,33,432,34,437,35,438,36,436,37,437,37,437,38,439,39,439,40,441,41,440,42,443,42,443,43,442,44,443,45,441,46,443,47,443,48,437,49,429,49,428,51,424,53,419,53,418,52,417,54,419,55,418,56,417,58,415,58,409,59,402,60,402,61,404,62,402,62,401,62,402,63,400,63\" />\n";
  $output .= "            <area data-timezone=\"Africa/Djibouti\" data-country=\"DJ\" data-pin=\"372,131\" data-offset=\"3\" shape=\"poly\" coords=\"372,130,372,130,372,130\" />\n";
  $output .= "            <area data-timezone=\"Africa/Douala\" data-country=\"CM\" data-pin=\"316,143\" data-offset=\"1\" shape=\"poly\" coords=\"324,140,325,142,327,145,327,147,324,146,317,146,316,143,315,143,314,142,315,140,316,139,318,138,319,139,320,138,323,132,324,131,323,128,325,129,325,132,326,133,323,134,326,137,324,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Freetown\" data-country=\"SL\" data-pin=\"278,136\" data-offset=\"0\" shape=\"poly\" coords=\"281,138,281,138,278,136,278,135,279,134,281,133,282,134,282,136,283,136,281,138\" />\n";
  $output .= "            <area data-timezone=\"Africa/Gaborone\" data-country=\"BW\" data-pin=\"343,191\" data-offset=\"2\" shape=\"poly\" coords=\"345,190,342,193,338,192,337,194,334,195,335,193,333,191,333,187,335,187,335,181,339,180,339,181,342,180,344,183,346,184,347,186,349,187,345,190\" />\n";
  $output .= "            <area data-timezone=\"Africa/Harare\" data-country=\"ZW\" data-pin=\"352,180\" data-offset=\"2\" shape=\"poly\" coords=\"352,187,347,186,346,184,344,183,342,180,345,180,349,176,355,178,355,182,355,183,354,186,352,187\" />\n";
  $output .= "            <area data-timezone=\"Africa/El_Aaiun\" data-country=\"EH\" data-pin=\"278,105\" data-offset=\"0\" shape=\"poly\" coords=\"280,107,280,111,278,112,278,114,272,114,272,115,272,114,274,110,273,111,275,109,276,106,277,105,278,104,286,104,286,107,280,107\" />\n";
  $output .= "            <area data-timezone=\"Africa/Johannesburg\" data-country=\"ZA\" data-pin=\"347,194\" data-offset=\"2\" shape=\"poly\" coords=\"339,207,333,208,331,207,331,207,330,205,330,204,330,203,327,198,328,197,329,198,330,198,333,197,333,191,335,193,334,195,336,195,338,192,342,193,345,189,348,187,352,187,353,191,353,193,352,193,351,195,353,196,353,196,353,195,355,195,354,198,347,205,343,207,339,207\" />\n";
  $output .= "            <area data-timezone=\"Africa/Juba\" data-country=\"SS\" data-pin=\"353,142\" data-offset=\"3\" shape=\"poly\" coords=\"358,140,359,141,360,141,360,142,357,142,356,144,351,144,349,142,347,143,346,142,344,139,340,135,342,133,343,133,344,134,348,134,350,133,352,134,354,132,353,130,355,130,355,132,357,133,357,136,355,136,355,137,357,137,358,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Kampala\" data-country=\"UG\" data-pin=\"354,149\" data-offset=\"3\" shape=\"poly\" coords=\"352,152,349,152,350,149,352,146,351,146,351,144,356,144,357,143,358,147,357,150,357,152,352,152\" />\n";
  $output .= "            <area data-timezone=\"Africa/Khartoum\" data-country=\"SD\" data-pin=\"354,124\" data-offset=\"3\" shape=\"poly\" coords=\"360,129,358,132,357,132,357,134,355,132,355,130,355,130,353,130,354,132,352,134,350,133,348,134,344,134,343,133,342,133,341,135,339,135,339,134,338,132,337,129,336,129,338,124,340,124,340,117,342,117,342,113,352,113,357,114,359,111,361,113,362,115,362,119,364,120,362,122,361,127,360,129,360,129\" />\n";
  $output .= "            <area data-timezone=\"Africa/Kinshasa\" data-country=\"CD\" data-pin=\"326,157\" data-offset=\"1\" shape=\"poly\" coords=\"325,158,327,157,327,154,330,151,331,143,332,142,333,141,334,143,339,144,338,144,339,146,338,146,337,147,339,150,338,151,339,151,339,151,341,153,338,153,337,153,337,154,335,154,334,157,333,157,333,162,333,162,332,163,329,164,328,160,320,160,322,158,322,158,324,157,324,158,325,158\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lagos\" data-country=\"NG\" data-pin=\"306,139\" data-offset=\"1\" shape=\"poly\" coords=\"313,143,310,143,308,139,305,139,305,135,306,132,306,131,307,128,309,127,312,128,313,128,316,129,318,128,321,128,323,127,324,130,324,131,323,132,320,138,319,139,317,138,315,140,314,142,313,143\" />\n";
  $output .= "            <area data-timezone=\"Africa/Libreville\" data-country=\"GA\" data-pin=\"316,149\" data-offset=\"1\" shape=\"poly\" coords=\"323,150,324,151,324,154,321,153,321,154,319,154,320,156,319,157,316,154,314,151,315,151,316,149,317,150,316,149,316,148,319,148,319,146,322,146,322,148,324,148,324,149,323,150\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lome\" data-country=\"TG\" data-pin=\"302,140\" data-offset=\"0\" shape=\"poly\" coords=\"302,140,301,139,301,136,300,131,302,132,301,133,302,133,303,140,302,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Kigali\" data-country=\"RW\" data-pin=\"350,153\" data-offset=\"2\" shape=\"poly\" coords=\"350,154,350,155,348,154,349,153,351,152,351,154,350,154\" />\n";
  $output .= "            <area data-timezone=\"Africa/Luanda\" data-country=\"AO\" data-pin=\"322,165\" data-offset=\"1\" shape=\"poly\" coords=\"337,168,337,169,340,168,340,172,337,172,337,177,339,179,335,180,331,179,323,179,322,178,320,179,321,172,323,170,323,168,322,165,322,164,321,160,328,160,329,164,332,163,333,162,336,162,336,166,337,168\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lubumbashi\" data-country=\"CD\" data-pin=\"346,169\" data-offset=\"2\" shape=\"poly\" coords=\"349,158,349,161,351,164,348,164,347,166,348,166,347,169,348,171,350,170,350,172,348,172,345,169,345,170,343,170,342,169,341,169,340,168,337,169,336,162,333,162,333,160,334,159,333,157,334,157,335,154,337,154,337,153,338,153,341,153,339,151,339,151,338,151,339,150,337,147,338,146,339,146,338,144,339,144,337,143,338,142,342,142,343,141,345,141,347,143,349,142,352,144,351,146,352,146,350,149,349,152,348,154,349,158\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lusaka\" data-country=\"ZM\" data-pin=\"347,176\" data-offset=\"2\" shape=\"poly\" coords=\"348,177,348,178,344,180,341,179,339,179,337,177,337,172,340,172,340,168,341,169,342,169,343,170,345,170,345,169,348,172,350,172,350,170,348,171,347,170,348,166,347,166,348,164,351,164,355,166,356,168,355,168,356,171,354,173,355,173,350,175,351,176,348,177\" />\n";
  $output .= "            <area data-timezone=\"Africa/Malabo\" data-country=\"GQ\" data-pin=\"315,144\" data-offset=\"1\" shape=\"poly\" coords=\"319,147,319,148,316,148,316,146,319,146,319,147\" />\n";
  $output .= "            <area data-timezone=\"Africa/Maputo\" data-country=\"MZ\" data-pin=\"354,193\" data-offset=\"2\" shape=\"poly\" coords=\"355,192,354,193,354,194,355,193,355,195,354,195,353,191,352,187,354,186,355,183,355,182,355,178,351,177,350,175,355,173,356,174,357,174,358,175,357,177,359,179,360,175,358,172,358,169,362,170,368,167,368,175,366,177,363,179,361,181,358,183,358,184,359,187,359,190,355,192\" />\n";
  $output .= "            <area data-timezone=\"Africa/Mbabane\" data-country=\"SZ\" data-pin=\"352,194\" data-offset=\"2\" shape=\"poly\" coords=\"353,193,354,195,353,196,351,194,352,193,353,193\" />\n";
  $output .= "            <area data-timezone=\"Africa/Mogadishu\" data-country=\"SO\" data-pin=\"376,147\" data-offset=\"3\" shape=\"poly\" coords=\"371,150,369,153,368,151,368,145,370,143,375,142,380,137,373,135,371,132,372,131,374,133,385,130,385,133,386,133,385,133,385,134,380,143,371,150\" />\n";
  $output .= "            <area data-timezone=\"Africa/Monrovia\" data-country=\"LR\" data-pin=\"282,140\" data-offset=\"0\" shape=\"poly\" coords=\"287,142,287,143,286,142,281,139,283,136,284,136,284,138,285,138,286,137,286,138,286,139,288,140,287,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Nairobi\" data-country=\"KE\" data-pin=\"361,152\" data-offset=\"3\" shape=\"poly\" coords=\"369,153,367,155,365,158,363,156,363,155,357,152,357,150,358,147,357,143,357,142,360,142,366,144,368,143,370,143,368,145,368,151,369,153\" />\n";
  $output .= "            <area data-timezone=\"Africa/Maseru\" data-country=\"LS\" data-pin=\"346,199\" data-offset=\"2\" shape=\"poly\" coords=\"347,200,346,201,345,199,348,198,349,199,347,200\" />\n";
  $output .= "            <area data-timezone=\"Africa/Ndjamena\" data-country=\"TD\" data-pin=\"325,130\" data-offset=\"1\" shape=\"poly\" coords=\"333,135,331,135,331,137,326,138,323,134,324,133,326,133,325,132,325,129,323,128,322,126,326,122,327,116,325,114,325,112,327,111,340,117,340,124,338,124,336,129,337,129,338,131,336,132,335,134,333,135\" />\n";
  $output .= "            <area data-timezone=\"Africa/Niamey\" data-country=\"NE\" data-pin=\"304,127\" data-offset=\"1\" shape=\"poly\" coords=\"308,127,306,129,306,130,305,129,304,130,303,129,302,128,302,128,301,127,300,125,306,124,307,122,307,118,310,118,320,111,324,112,325,112,325,114,327,116,326,122,322,126,323,127,321,128,318,128,316,129,313,128,312,128,309,127,308,127\" />\n";
  $output .= "            <area data-timezone=\"Africa/Nouakchott\" data-country=\"MR\" data-pin=\"273,120\" data-offset=\"0\" shape=\"poly\" coords=\"280,124,280,125,276,122,273,122,272,123,273,120,272,118,273,116,272,115,272,115,278,114,278,112,280,111,280,107,286,107,286,105,292,108,289,108,291,124,284,124,282,125,280,124\" />\n";
  $output .= "            <area data-timezone=\"Africa/Ouagadougou\" data-country=\"BF\" data-pin=\"297,129\" data-offset=\"0\" shape=\"poly\" coords=\"299,132,295,132,295,134,295,133,292,134,291,133,291,130,292,130,293,128,294,128,296,126,300,125,300,125,301,127,302,128,302,128,303,129,304,130,302,132,299,132\" />\n";
  $output .= "            <area data-timezone=\"Africa/Porto-Novo\" data-country=\"BJ\" data-pin=\"304,139\" data-offset=\"1\" shape=\"poly\" coords=\"305,137,305,139,303,140,303,135,301,133,302,131,303,131,305,129,306,130,306,132,305,135,305,137\" />\n";
  $output .= "            <area data-timezone=\"Africa/Tunis\" data-country=\"TN\" data-pin=\"317,89\" data-offset=\"1\" shape=\"poly\" coords=\"319,96,317,97,317,99,316,100,315,97,313,95,313,93,314,91,314,89,315,88,317,88,317,89,318,88,317,90,319,91,317,93,319,95,319,96\" />\n";
  $output .= "            <area data-timezone=\"Africa/Sao_Tome\" data-country=\"ST\" data-pin=\"311,149\" data-offset=\"0\" shape=\"poly\" coords=\"312,147,312,147,312,147\" />\n";
  $output .= "            <area data-timezone=\"Africa/Tripoli\" data-country=\"LY\" data-pin=\"322,95\" data-offset=\"2\" shape=\"poly\" coords=\"342,105,342,117,340,117,340,117,327,111,324,112,317,109,316,106,317,106,317,104,316,100,317,99,317,97,319,96,319,95,325,96,326,98,332,100,333,99,334,96,337,95,339,96,342,97,341,100,342,105\" />\n";
  $output .= "            <area data-timezone=\"Africa/Windhoek\" data-country=\"NA\" data-pin=\"329,188\" data-offset=\"2\" shape=\"poly\" coords=\"333,196,333,197,332,198,329,198,328,197,327,198,326,197,325,194,324,188,320,181,320,179,322,178,323,179,331,179,335,180,340,179,342,180,339,181,339,180,335,181,335,187,333,187,333,196\" />\n";
  $output .= "            <area data-timezone=\"America/Adak\" data-country=\"US\" data-pin=\"6,64\" data-offset=\"-10\" shape=\"poly\" coords=\"8,63,8,63,10,63,8,63\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Salta\" data-country=\"AR\" data-pin=\"191,191\" data-offset=\"-3\" shape=\"poly\" coords=\"194,216,194,218,195,218,192,218,192,220,180,220,180,216,182,214,181,212,182,210,184,212,186,213,186,210,192,210,192,208,194,208,194,216\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Salta\" data-country=\"AR\" data-pin=\"191,191\" data-offset=\"-3\" shape=\"poly\" coords=\"191,187,193,188,193,187,195,187,196,187,196,191,194,193,189,194,189,193,189,192,186,192,186,191,188,190,189,190,189,189,191,191,193,191,193,189,192,189,191,187\" />\n";
  $output .= "            <area data-timezone=\"America/Anchorage\" data-country=\"US\" data-pin=\"50,48\" data-offset=\"-9\" shape=\"poly\" coords=\"51,50,48,51,47,51,48,50,47,50,48,49,52,49,50,48,51,47,47,48,43,51,45,52,43,53,36,56,36,57,30,58,30,57,33,57,33,56,37,54,37,53,39,52,36,52,36,52,35,53,33,52,30,52,30,44,32,44,31,43,32,42,30,42,30,40,32,40,30,39,30,39,31,39,30,38,30,33,39,31,41,31,40,32,42,31,46,32,47,32,46,32,47,33,61,33,65,34,65,50,64,50,60,50,55,49,56,48,53,48,54,48,52,49,53,49,53,50,53,50,51,50\" />\n";
  $output .= "            <area data-timezone=\"America/Anguilla\" data-country=\"AI\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/Antigua\" data-country=\"AG\" data-pin=\"197,122\" data-offset=\"-4\" shape=\"poly\" coords=\"197,121,197,121,197,121\" />\n";
  $output .= "            <area data-timezone=\"America/Araguaina\" data-country=\"BR\" data-pin=\"220,162\" data-offset=\"-3\" shape=\"poly\" coords=\"222,163,223,163,222,165,224,167,222,169,223,169,223,171,220,172,219,172,218,171,218,172,216,171,216,171,216,171,216,168,218,164,218,162,219,161,220,159,219,159,221,159,220,162,222,163\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Buenos_Aires\" data-country=\"AR\" data-pin=\"203,208\" data-offset=\"-3\" shape=\"poly\" coords=\"200,206,203,207,203,208,205,209,204,210,206,212,203,214,198,215,196,215,197,216,196,215,196,217,196,218,195,218,194,218,194,207,197,207,198,206,200,206\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Catamarca\" data-country=\"AR\" data-pin=\"190,197\" data-offset=\"-3\" shape=\"poly\" coords=\"191,225,188,225,187,227,181,227,181,225,180,225,181,224,180,224,181,223,180,221,191,220,193,221,194,220,194,221,192,221,193,222,191,223,191,225\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Catamarca\" data-country=\"AR\" data-pin=\"190,197\" data-offset=\"-3\" shape=\"poly\" coords=\"192,200,189,197,185,196,186,195,186,192,189,192,189,193,190,195,190,196,191,197,191,197,192,200\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Cordoba\" data-country=\"AR\" data-pin=\"193,202\" data-offset=\"-3\" shape=\"poly\" coords=\"195,207,194,207,194,208,192,208,192,204,190,203,190,202,192,200,191,197,193,193,194,193,196,191,196,187,198,190,204,192,202,196,207,196,209,194,209,193,210,193,210,195,207,197,204,201,203,207,200,205,197,207,195,207\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Jujuy\" data-country=\"AR\" data-pin=\"191,190\" data-offset=\"-3\" shape=\"poly\" coords=\"188,188,190,186,191,187,191,188,192,189,193,189,193,190,192,191,191,191,189,189,189,190,189,190,188,188\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/La_Rioja\" data-country=\"AR\" data-pin=\"189,199\" data-offset=\"-3\" shape=\"poly\" coords=\"187,200,185,199,185,198,184,197,185,196,189,197,191,200,190,203,189,203,187,200\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Mendoza\" data-country=\"AR\" data-pin=\"185,205\" data-offset=\"-3\" shape=\"poly\" coords=\"183,204,187,203,188,205,189,210,186,210,186,213,184,212,183,211,182,209,184,206,183,204\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Rio_Gallegos\" data-country=\"AR\" data-pin=\"185,236\" data-offset=\"-3\" shape=\"poly\" coords=\"181,227,187,227,189,228,190,229,190,230,185,234,185,236,186,237,180,237,179,236,180,234,178,235,177,234,178,232,180,231,179,230,180,229,181,227\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/San_Juan\" data-country=\"AR\" data-pin=\"186,203\" data-offset=\"-3\" shape=\"poly\" coords=\"183,200,184,197,185,198,185,199,187,200,189,203,188,203,188,204,185,203,183,204,182,202,183,200\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/San_Luis\" data-country=\"AR\" data-pin=\"189,206\" data-offset=\"-3\" shape=\"poly\" coords=\"190,203,192,204,192,210,189,210,188,203,190,203\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Tucuman\" data-country=\"AR\" data-pin=\"191,195\" data-offset=\"-3\" shape=\"poly\" coords=\"190,194,193,194,192,196,191,197,190,196,190,195,190,194\" />\n";
  $output .= "            <area data-timezone=\"America/Aruba\" data-country=\"AW\" data-pin=\"183,129\" data-offset=\"-4\" shape=\"poly\" coords=\"183,129,184,129,183,129\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Ushuaia\" data-country=\"AR\" data-pin=\"186,241\" data-offset=\"-3\" shape=\"poly\" coords=\"186,241,186,238,186,239,186,239,188,240,191,241,189,242,186,241\" />\n";
  $output .= "            <area data-timezone=\"America/Asuncion\" data-country=\"PY\" data-pin=\"204,192\" data-offset=\"-3\" shape=\"poly\" coords=\"209,194,207,196,202,195,204,192,198,190,196,187,197,183,200,182,203,183,203,187,207,187,208,190,210,190,209,194\" />\n";
  $output .= "            <area data-timezone=\"America/Bahia_Banderas\" data-country=\"MX\" data-pin=\"125,115\" data-offset=\"-6\" shape=\"poly\" coords=\"125,115,124,115,125,115\" />\n";
  $output .= "            <area data-timezone=\"America/Atikokan\" data-country=\"CA\" data-pin=\"147,69\" data-offset=\"-5\" shape=\"poly\" coords=\"150,70,146,69,147,68,148,68,148,69,150,70\" />\n";
  $output .= "            <area data-timezone=\"America/Bahia\" data-country=\"BR\" data-pin=\"236,172\" data-offset=\"-2\" shape=\"poly\" coords=\"224,175,223,175,223,169,222,169,223,168,224,167,225,168,227,167,227,166,229,166,231,165,232,166,234,164,236,165,237,167,236,168,238,169,235,172,235,176,234,181,232,179,234,177,233,176,231,176,230,175,227,175,226,174,224,175\" />\n";
  $output .= "            <area data-timezone=\"America/Barbados\" data-country=\"BB\" data-pin=\"201,128\" data-offset=\"-4\" shape=\"poly\" coords=\"201,128,201,128,201,128\" />\n";
  $output .= "            <area data-timezone=\"America/Belem\" data-country=\"BR\" data-pin=\"219,152\" data-offset=\"-3\" shape=\"poly\" coords=\"214,151,215,150,215,151,216,150,219,150,219,152,216,153,218,153,218,154,219,152,220,151,223,152,222,156,219,159,220,160,218,162,218,164,216,166,212,166,213,165,212,163,213,161,212,158,212,156,214,155,213,153,214,152,212,151,211,148,209,147,209,146,212,146,214,143,216,146,217,148,214,151\" />\n";
  $output .= "            <area data-timezone=\"America/Belize\" data-country=\"BZ\" data-pin=\"153,121\" data-offset=\"-6\" shape=\"poly\" coords=\"154,121,153,121,154,121\" />\n";
  $output .= "            <area data-timezone=\"America/Blanc-Sablon\" data-country=\"CA\" data-pin=\"205,64\" data-offset=\"-4\" shape=\"poly\" coords=\"202,64,201,66,202,64\" />\n";
  $output .= "            <area data-timezone=\"America/Boa_Vista\" data-country=\"BR\" data-pin=\"199,145\" data-offset=\"-4\" shape=\"poly\" coords=\"200,142,200,142,201,143,200,146,200,147,202,148,202,150,200,150,199,151,198,151,197,152,196,151,196,147,193,146,192,143,195,144,196,143,198,142,199,141,200,142\" />\n";
  $output .= "            <area data-timezone=\"America/Bogota\" data-country=\"CO\" data-pin=\"177,142\" data-offset=\"-5\" shape=\"poly\" coords=\"184,152,183,157,182,156,183,155,183,154,180,154,178,154,175,150,171,149,168,147,172,143,171,143,171,140,170,138,171,137,171,136,172,137,172,136,174,134,175,132,178,131,181,129,181,130,179,132,178,135,179,136,179,138,180,138,183,138,184,140,188,140,187,142,188,144,187,145,188,146,189,148,188,146,184,147,184,148,185,149,183,149,184,152\" />\n";
  $output .= "            <area data-timezone=\"America/Boise\" data-country=\"US\" data-pin=\"106,77\" data-offset=\"-7\" shape=\"poly\" coords=\"115,79,115,80,105,80,105,79,103,79,103,76,105,76,106,74,106,73,106,74,110,74,112,76,115,76,115,79\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"119,22,120,23,119,23,121,23,120,24,123,23,124,24,123,25,117,25,117,24,119,24,117,24,118,23,117,23,119,22\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"130,43,116,42,114,41,111,41,97,36,97,34,107,35,110,36,107,37,108,37,117,37,120,38,119,38,121,39,120,39,122,39,121,38,122,38,120,37,124,36,119,36,119,36,123,35,126,37,128,36,131,37,136,37,136,36,138,36,139,36,139,37,140,36,139,38,141,38,141,37,144,36,144,36,144,35,142,35,144,34,139,33,140,32,139,32,139,31,142,30,141,30,144,30,145,31,145,32,147,33,146,33,146,33,147,33,145,34,149,34,148,34,149,35,149,36,150,36,152,35,152,38,130,38,130,43\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"138,29,139,29,139,30,139,30,136,30,137,31,135,31,129,29,133,29,133,28,138,29\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"120,28,124,28,126,31,126,32,132,33,132,34,127,34,128,34,128,35,130,34,130,35,128,35,125,35,125,35,122,34,118,35,111,36,111,35,111,35,106,34,105,33,117,33,117,28,119,29,120,31,121,30,120,28,120,28\" />\n";
  $output .= "            <area data-timezone=\"America/Campo_Grande\" data-country=\"BR\" data-pin=\"209,184\" data-offset=\"-3\" shape=\"poly\" coords=\"212,180,212,181,215,182,215,184,210,190,208,190,207,187,203,187,204,180,206,179,208,179,211,179,210,180,212,180\" />\n";
  $output .= "            <area data-timezone=\"America/Cancun\" data-country=\"MX\" data-pin=\"155,115\" data-offset=\"-6\" shape=\"poly\" coords=\"154,114,155,115,154,117,154,117,154,120,153,119,152,120,151,120,151,117,154,116,154,114\" />\n";
  $output .= "            <area data-timezone=\"America/Caracas\" data-country=\"VE\" data-pin=\"188,133\" data-offset=\"-4.5\" shape=\"poly\" coords=\"196,134,198,134,198,136,200,136,199,137,200,138,198,139,198,140,199,142,195,143,195,144,192,143,193,146,194,146,191,149,191,148,189,149,189,148,188,146,187,145,188,144,187,142,188,140,184,140,183,138,180,138,179,135,178,135,179,132,181,130,180,131,181,132,180,134,180,135,181,135,182,134,181,132,183,131,183,130,184,131,186,131,186,133,190,132,192,133,194,133,193,132,197,132,195,132,196,134\" />\n";
  $output .= "            <area data-timezone=\"America/Cayenne\" data-country=\"GF\" data-pin=\"213,142\" data-offset=\"-3\" shape=\"poly\" coords=\"211,141,214,143,212,146,209,146,210,144,209,142,210,140,211,141\" />\n";
  $output .= "            <area data-timezone=\"America/Cayman\" data-country=\"KY\" data-pin=\"164,118\" data-offset=\"-5\" shape=\"poly\" coords=\"167,117,167,117,167,117\" />\n";
  $output .= "            <area data-timezone=\"America/Chicago\" data-country=\"US\" data-pin=\"154,80\" data-offset=\"-6\" shape=\"poly\" coords=\"154,86,159,88,159,90,157,92,159,96,158,100,156,99,156,99,155,99,155,99,153,100,153,99,153,99,149,100,151,100,150,101,152,101,151,102,150,101,150,102,144,100,142,101,142,100,141,102,139,103,140,102,139,102,139,103,138,103,138,104,137,104,138,105,138,107,135,106,134,104,131,100,129,100,128,102,126,101,125,99,125,97,128,97,128,88,130,88,130,87,131,87,131,84,130,84,130,83,131,83,131,80,132,80,131,77,133,76,132,75,133,75,133,73,131,73,132,72,132,71,127,71,127,68,141,68,141,68,142,69,151,70,147,72,149,72,148,72,153,73,154,75,153,76,154,75,154,80,154,81,156,81,154,82,154,86\" />\n";
  $output .= "            <area data-timezone=\"America/Chihuahua\" data-country=\"MX\" data-pin=\"123,102\" data-offset=\"-7\" shape=\"poly\" coords=\"128,102,127,104,127,106,126,106,123,105,122,107,118,103,119,103,119,98,120,98,120,97,122,97,125,99,125,101,128,102\" />\n";
  $output .= "            <area data-timezone=\"America/Coral_Harbour\" data-country=\"CA\" data-pin=\"160,42\" data-offset=\"-5\" shape=\"poly\" coords=\"158,40,158,41,159,41,164,42,164,43,163,43,166,44,165,44,162,43,158,45,157,44,155,44,156,43,157,41,158,40\" />\n";
  $output .= "            <area data-timezone=\"America/Costa_Rica\" data-country=\"CR\" data-pin=\"160,133\" data-offset=\"-6\" shape=\"poly\" coords=\"158,134,157,133,157,131,161,132,162,134,162,134,162,137,158,133,158,134\" />\n";
  $output .= "            <area data-timezone=\"America/Creston\" data-country=\"CA\" data-pin=\"106,68\" data-offset=\"-7\" shape=\"poly\" coords=\"107,68,105,68,105,67,107,68\" />\n";
  $output .= "            <area data-timezone=\"America/Cuiaba\" data-country=\"BR\" data-pin=\"207,176\" data-offset=\"-3\" shape=\"poly\" coords=\"214,175,212,178,212,180,210,180,211,179,208,179,206,179,204,180,203,179,203,177,200,177,199,173,200,171,200,169,197,168,197,165,202,165,203,162,204,165,205,166,216,166,215,169,216,171,214,175\" />\n";
  $output .= "            <area data-timezone=\"America/Curacao\" data-country=\"CW\" data-pin=\"185,130\" data-offset=\"-4\" shape=\"poly\" coords=\"185,130,185,129,185,130\" />\n";
  $output .= "            <area data-timezone=\"America/Danmarkshavn\" data-country=\"GL\" data-pin=\"269,22\" data-offset=\"0\" shape=\"poly\" coords=\"268,18,267,18,269,18,265,19,265,19,263,21,265,20,268,20,268,21,265,21,269,21,270,22,262,22,267,23,264,23,268,24,262,23,262,17,268,18\" />\n";
  $output .= "            <area data-timezone=\"America/Dawson\" data-country=\"CA\" data-pin=\"68,43\" data-offset=\"-8\" shape=\"poly\" coords=\"68,43,68,43,68,43\" />\n";
  $output .= "            <area data-timezone=\"America/Dawson_Creek\" data-country=\"CA\" data-pin=\"100,50\" data-offset=\"-7\" shape=\"poly\" coords=\"100,55,100,60,95,58,94,55,100,55\" />\n";
  $output .= "            <area data-timezone=\"America/Denver\" data-country=\"US\" data-pin=\"125,84\" data-offset=\"-7\" shape=\"poly\" coords=\"112,76,110,74,109,74,109,72,107,71,107,68,127,68,127,71,130,71,130,72,132,73,131,73,133,74,133,75,132,75,133,76,131,77,132,80,131,80,131,83,130,83,130,84,131,84,131,87,130,87,130,88,128,88,128,97,125,97,125,99,122,97,118,98,118,91,115,91,114,89,114,88,110,88,110,80,115,80,115,76,112,76\" />\n";
  $output .= "            <area data-timezone=\"America/Detroit\" data-country=\"US\" data-pin=\"162,79\" data-offset=\"-5\" shape=\"poly\" coords=\"159,81,155,80,156,79,156,77,156,76,157,75,158,75,159,74,161,75,161,75,161,76,160,78,162,77,163,78,161,80,159,81\" />\n";
  $output .= "            <area data-timezone=\"America/Detroit\" data-country=\"US\" data-pin=\"162,79\" data-offset=\"-5\" shape=\"poly\" coords=\"150,72,152,71,153,72,156,73,158,72,161,73,157,73,156,74,155,73,154,75,155,74,154,73,151,73,150,72\" />\n";
  $output .= "            <area data-timezone=\"America/Dominica\" data-country=\"DM\" data-pin=\"198,125\" data-offset=\"-4\" shape=\"poly\" coords=\"198,124,198,125,198,124\" />\n";
  $output .= "            <area data-timezone=\"America/Edmonton\" data-country=\"CA\" data-pin=\"111,61\" data-offset=\"-7\" shape=\"poly\" coords=\"110,68,106,68,106,66,102,63,103,63,103,62,100,61,100,50,117,50,117,61,119,62,117,62,117,68,110,68\" />\n";
  $output .= "            <area data-timezone=\"America/Eirunepe\" data-country=\"BR\" data-pin=\"184,161\" data-offset=\"-4\" shape=\"poly\" coords=\"187,166,177,162,178,161,179,159,180,158,183,157,187,166\" />\n";
  $output .= "            <area data-timezone=\"America/El_Salvador\" data-country=\"SV\" data-pin=\"151,127\" data-offset=\"-6\" shape=\"poly\" coords=\"151,127,150,127,151,126,154,127,153,128,151,127\" />\n";
  $output .= "            <area data-timezone=\"America/Fortaleza\" data-country=\"BR\" data-pin=\"236,156\" data-offset=\"-3\" shape=\"poly\" coords=\"242,161,242,163,241,162,238,164,238,163,238,162,236,163,232,162,232,164,230,165,227,166,227,167,225,168,223,167,222,165,223,163,220,162,221,159,219,159,222,156,223,152,224,152,224,153,225,152,226,153,225,156,226,154,226,155,228,154,230,155,233,155,238,158,241,159,242,161\" />\n";
  $output .= "            <area data-timezone=\"America/Glace_Bay\" data-country=\"CA\" data-pin=\"200,73\" data-offset=\"-4\" shape=\"poly\" coords=\"200,73,200,74,199,73,200,73\" />\n";
  $output .= "            <area data-timezone=\"America/Godthab\" data-country=\"GL\" data-pin=\"214,43\" data-offset=\"-3\" shape=\"poly\" coords=\"225,50,225,50,226,49,225,50,226,49,225,49,225,49,223,49,224,48,223,49,225,48,220,49,221,48,219,48,220,48,218,47,219,47,218,47,219,47,218,47,219,47,217,47,218,46,216,46,217,45,216,45,216,45,216,45,217,45,215,45,216,44,215,44,216,44,214,44,215,43,214,43,216,43,214,43,216,43,215,42,216,42,217,43,215,41,216,42,213,43,213,42,215,42,213,42,213,41,212,41,216,40,212,41,212,40,211,41,214,40,211,40,216,39,211,40,211,40,211,39,213,39,211,39,213,39,210,38,216,38,210,38,217,37,213,37,214,37,210,37,211,37,213,37,211,36,216,37,214,36,215,36,211,36,215,36,216,35,214,35,215,35,216,35,215,35,216,35,216,34,215,35,216,34,215,34,216,33,209,32,216,33,214,32,215,32,213,32,215,32,213,31,214,31,212,31,214,31,212,31,214,30,211,30,210,30,210,31,208,31,207,31,209,30,207,30,208,29,207,29,209,29,207,28,208,28,207,27,207,27,206,27,206,26,204,26,206,26,202,24,203,24,203,24,194,23,189,18,192,17,192,17,194,16,188,16,194,15,195,15,194,15,198,15,198,14,199,14,198,14,199,14,206,14,201,13,202,13,209,13,211,13,211,14,212,13,217,14,215,13,218,13,215,12,216,12,226,14,226,14,225,13,226,13,226,13,229,13,224,12,234,12,224,11,236,12,235,12,238,11,235,11,242,11,257,11,241,12,258,11,259,12,257,12,264,12,245,14,259,13,255,14,256,14,265,13,264,14,260,16,268,14,267,14,274,14,281,14,274,16,265,16,274,16,266,16,267,17,271,17,270,17,262,17,262,23,268,24,267,25,263,24,264,24,262,24,266,25,263,25,268,26,263,26,263,26,262,27,264,27,264,27,266,27,266,28,263,28,260,27,263,27,257,27,259,27,254,28,256,28,254,28,254,28,258,28,254,29,259,29,256,29,258,29,257,30,259,29,259,31,252,30,254,30,253,31,258,31,253,31,254,32,251,33,256,33,252,33,254,33,258,33,263,33,256,36,250,37,247,37,246,36,247,37,245,37,242,39,238,40,238,40,237,40,238,39,237,39,237,40,236,40,236,41,234,40,233,41,234,41,233,42,231,41,233,43,231,43,232,43,232,44,231,44,232,44,230,44,231,45,230,45,230,45,228,45,230,46,228,46,230,47,229,47,230,47,228,48,229,48,227,48,229,48,227,49,229,49,226,49,228,50,227,49,225,50\" />\n";
  $output .= "            <area data-timezone=\"America/Goose_Bay\" data-country=\"CA\" data-pin=\"199,61\" data-offset=\"-4\" shape=\"poly\" coords=\"205,63,194,63,193,63,194,62,194,62,193,62,192,64,189,63,189,62,188,62,189,61,187,60,188,59,188,58,189,59,189,58,190,59,193,59,194,58,194,58,194,57,194,57,193,56,194,54,193,53,194,52,192,52,193,52,193,51,192,51,192,50,194,51,193,52,195,52,194,53,196,53,194,53,197,54,196,54,198,55,196,55,197,56,196,56,199,57,199,57,199,58,200,58,200,58,201,58,200,59,201,58,201,59,204,59,201,60,203,60,199,61,204,60,205,60,204,61,205,63\" />\n";
  $output .= "            <area data-timezone=\"America/Grand_Turk\" data-country=\"TC\" data-pin=\"181,114\" data-offset=\"-5\" shape=\"poly\" coords=\"181,114,181,114,181,114\" />\n";
  $output .= "            <area data-timezone=\"America/Grenada\" data-country=\"GD\" data-pin=\"197,130\" data-offset=\"-4\" shape=\"poly\" coords=\"198,129,198,129,198,129\" />\n";
  $output .= "            <area data-timezone=\"America/Guadeloupe\" data-country=\"GP\" data-pin=\"197,123\" data-offset=\"-4\" shape=\"poly\" coords=\"197,123,198,123,197,123\" />\n";
  $output .= "            <area data-timezone=\"America/Guatemala\" data-country=\"GT\" data-pin=\"149,126\" data-offset=\"-6\" shape=\"poly\" coords=\"150,127,147,126,146,125,147,123,149,123,148,121,148,121,148,120,151,120,151,124,153,124,150,127\" />\n";
  $output .= "            <area data-timezone=\"America/Guayaquil\" data-country=\"EC\" data-pin=\"167,154\" data-offset=\"-5\" shape=\"poly\" coords=\"169,156,168,158,167,157,166,157,167,154,166,155,165,154,167,149,169,148,171,149,173,149,175,150,174,150,175,152,169,156\" />\n";
  $output .= "            <area data-timezone=\"America/Guyana\" data-country=\"GY\" data-pin=\"203,139\" data-offset=\"-4\" shape=\"poly\" coords=\"204,140,205,141,203,142,203,143,206,147,204,147,202,148,200,147,200,145,201,143,200,142,200,142,198,140,198,139,200,138,199,137,200,136,200,136,203,138,202,139,203,139,204,140\" />\n";
  $output .= "            <area data-timezone=\"America/Halifax\" data-country=\"CA\" data-pin=\"194,76\" data-offset=\"-4\" shape=\"poly\" coords=\"193,75,194,74,192,74,193,73,196,74,197,74,198,74,193,76,191,78,190,77,190,76,192,74,193,75\" />\n";
  $output .= "            <area data-timezone=\"America/Havana\" data-country=\"CU\" data-pin=\"163,111\" data-offset=\"-5\" shape=\"poly\" coords=\"175,116,176,116,170,117,171,116,170,115,169,114,164,113,163,113,164,112,164,112,158,114,161,112,166,111,175,116\" />\n";
  $output .= "            <area data-timezone=\"America/Hermosillo\" data-country=\"MX\" data-pin=\"115,102\" data-offset=\"-7\" shape=\"poly\" coords=\"108,97,109,96,115,98,119,98,119,103,118,103,119,105,118,106,116,105,116,104,113,102,112,98,108,97\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Petersburg\" data-country=\"US\" data-pin=\"155,86\" data-offset=\"-5\" shape=\"poly\" coords=\"155,86,154,86,155,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Tell_City\" data-country=\"US\" data-pin=\"155,87\" data-offset=\"-6\" shape=\"poly\" coords=\"156,87,156,86,156,87\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Vevay\" data-country=\"US\" data-pin=\"158,85\" data-offset=\"-5\" shape=\"poly\" coords=\"159,85,158,85,159,85\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Indianapolis\" data-country=\"US\" data-pin=\"156,84\" data-offset=\"-5\" shape=\"poly\" coords=\"158,86,156,86,156,85,154,85,154,82,156,82,156,80,159,80,159,84,158,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Knox\" data-country=\"US\" data-pin=\"156,81\" data-offset=\"-6\" shape=\"poly\" coords=\"156,81,155,81,156,81\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Marengo\" data-country=\"US\" data-pin=\"156,86\" data-offset=\"-5\" shape=\"poly\" coords=\"156,86,156,86,156,86,156,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Vincennes\" data-country=\"US\" data-pin=\"154,86\" data-offset=\"-5\" shape=\"poly\" coords=\"154,86,154,85,156,85,156,86,154,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Winamac\" data-country=\"US\" data-pin=\"156,82\" data-offset=\"-5\" shape=\"poly\" coords=\"156,81,155,82,156,81\" />\n";
  $output .= "            <area data-timezone=\"America/Inuvik\" data-country=\"CA\" data-pin=\"77,36\" data-offset=\"-7\" shape=\"poly\" coords=\"78,36,77,36,78,36\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"167,24,166,24,168,25,166,25,168,25,158,26,158,24,167,24\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"167,12,174,12,173,13,174,12,171,12,187,12,185,12,187,12,187,14,184,14,187,14,183,15,187,15,187,15,182,16,183,16,179,16,183,17,181,17,176,17,175,17,178,17,170,18,176,18,170,18,176,19,172,20,174,20,174,20,169,20,170,21,169,21,163,21,170,22,169,23,165,23,165,23,162,22,163,23,158,22,158,21,161,21,160,21,163,20,159,21,158,18,163,19,161,19,164,18,159,18,161,18,158,17,158,16,165,17,167,17,161,16,173,15,168,15,172,14,168,15,168,15,167,15,158,16,158,15,163,15,158,15,158,13,159,14,158,13,168,14,162,13,166,12,164,12,169,12,166,12,167,12\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"158,35,159,35,158,35,159,34,163,34,161,34,164,35,163,35,165,36,162,36,165,38,161,39,160,38,158,38,158,35\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"173,29,167,29,165,27,170,27,173,29\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"159,28,164,27,166,29,165,29,166,29,165,30,167,29,170,30,169,30,171,30,169,29,171,29,175,29,175,30,173,30,176,30,174,31,176,31,175,31,177,30,176,31,177,31,177,32,178,31,178,32,179,31,181,31,179,32,180,32,179,32,182,32,180,33,181,32,181,33,184,32,182,33,186,32,183,34,186,33,183,34,187,33,186,34,183,34,187,34,185,35,186,35,185,35,187,35,186,35,187,35,184,35,187,36,187,40,186,40,187,40,187,44,185,44,187,45,187,46,181,45,180,44,181,44,179,44,178,43,178,42,177,43,178,42,176,43,177,42,176,42,175,42,176,43,170,43,170,42,171,41,178,41,176,40,180,38,177,36,178,36,177,36,177,36,174,35,172,36,174,35,171,34,172,34,171,34,172,33,171,34,171,33,168,32,167,33,168,33,169,34,164,33,165,34,162,33,164,33,158,33,158,32,159,31,158,31,158,30,159,30,158,29,158,28,159,29,158,28,160,28,158,28,159,28\" />\n";
  $output .= "            <area data-timezone=\"America/La_Paz\" data-country=\"BO\" data-pin=\"186,178\" data-offset=\"-4\" shape=\"poly\" coords=\"196,185,196,187,193,187,193,188,192,187,190,186,188,188,187,188,185,184,186,182,184,179,185,177,184,176,186,171,184,168,186,169,189,166,191,166,192,170,199,173,200,177,203,177,203,179,204,180,203,183,201,182,197,183,196,185\" />\n";
  $output .= "            <area data-timezone=\"America/Jamaica\" data-country=\"JM\" data-pin=\"172,120\" data-offset=\"-5\" shape=\"poly\" coords=\"173,120,171,120,169,120,171,119,173,120\" />\n";
  $output .= "            <area data-timezone=\"America/Juneau\" data-country=\"US\" data-pin=\"76,53\" data-offset=\"-9\" shape=\"poly\" coords=\"71,52,74,50,79,54,77,55,78,54,77,54,79,54,77,53,78,54,77,53,77,52,75,53,74,51,74,51,75,53,73,53,74,52,73,52,73,52,72,52,73,52,72,53,70,52,71,52\" />\n";
  $output .= "            <area data-timezone=\"America/Kentucky/Louisville\" data-country=\"US\" data-pin=\"157,86\" data-offset=\"-5\" shape=\"poly\" coords=\"157,87,156,86,158,86,157,87\" />\n";
  $output .= "            <area data-timezone=\"America/Kentucky/Monticello\" data-country=\"US\" data-pin=\"159,89\" data-offset=\"-5\" shape=\"poly\" coords=\"159,88,159,89,158,89,159,88\" />\n";
  $output .= "            <area data-timezone=\"America/Kralendijk\" data-country=\"BQ\" data-pin=\"186,130\" data-offset=\"-4\" shape=\"poly\" coords=\"195,121,195,121,195,121\" />\n";
  $output .= "            <area data-timezone=\"America/Lima\" data-country=\"PE\" data-pin=\"172,170\" data-offset=\"-5\" shape=\"poly\" coords=\"184,179,184,180,183,181,181,179,175,176,173,174,173,172,167,162,165,160,165,159,164,157,165,156,166,156,166,157,168,158,169,156,174,153,175,152,174,150,175,150,178,154,180,154,183,155,182,156,183,157,182,157,179,159,178,161,177,163,178,165,178,166,179,166,180,167,183,166,182,168,184,168,186,171,184,176,185,177,184,179\" />\n";
  $output .= "            <area data-timezone=\"America/Managua\" data-country=\"NI\" data-pin=\"156,130\" data-offset=\"-6\" shape=\"poly\" coords=\"158,132,157,131,154,128,155,128,155,127,157,127,158,125,161,125,161,132,158,132\" />\n";
  $output .= "            <area data-timezone=\"America/Manaus\" data-country=\"BR\" data-pin=\"200,155\" data-offset=\"-4\" shape=\"poly\" coords=\"200,150,202,150,203,152,206,154,203,161,203,162,203,165,197,165,195,163,194,163,193,165,192,166,189,166,189,166,187,166,183,157,184,152,183,149,185,149,184,148,184,147,188,146,188,148,189,149,191,148,191,149,193,147,195,146,196,149,196,151,197,152,198,151,199,151,200,150\" />\n";
  $output .= "            <area data-timezone=\"America/Los_Angeles\" data-country=\"US\" data-pin=\"103,93\" data-offset=\"-8\" shape=\"poly\" coords=\"109,74,106,74,106,73,106,74,105,76,103,76,103,79,105,79,105,80,110,80,110,90,109,90,110,93,109,94,109,95,105,96,102,93,99,92,99,91,96,88,96,87,95,87,94,85,93,83,93,81,92,79,93,73,94,73,93,73,94,72,93,72,94,72,93,72,92,69,95,70,95,71,96,70,96,71,95,72,96,71,95,68,107,68,107,71,109,72,109,74\" />\n";
  $output .= "            <area data-timezone=\"America/Lower_Princes\" data-country=\"SX\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/Maceio\" data-country=\"BR\" data-pin=\"240,166\" data-offset=\"-3\" shape=\"poly\" coords=\"239,168,237,169,236,168,237,167,236,166,237,165,238,166,241,165,239,168\" />\n";
  $output .= "            <area data-timezone=\"America/Marigot\" data-country=\"MF\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/Martinique\" data-country=\"MQ\" data-pin=\"198,126\" data-offset=\"-4\" shape=\"poly\" coords=\"198,125,198,125,198,125\" />\n";
  $output .= "            <area data-timezone=\"America/Matamoros\" data-country=\"MX\" data-pin=\"138,107\" data-offset=\"-6\" shape=\"poly\" coords=\"136,107,134,106,131,101,129,101,128,102,128,102,129,100,131,100,134,104,135,106,138,107,138,107,136,107\" />\n";
  $output .= "            <area data-timezone=\"America/Mazatlan\" data-country=\"MX\" data-pin=\"123,111\" data-offset=\"-7\" shape=\"poly\" coords=\"125,112,127,114,126,115,124,115,125,114,124,112,120,108,118,107,118,106,120,105,122,109,125,112\" />\n";
  $output .= "            <area data-timezone=\"America/Mazatlan\" data-country=\"MX\" data-pin=\"123,111\" data-offset=\"-7\" shape=\"poly\" coords=\"110,103,112,103,117,111,117,112,113,109,113,107,111,105,111,105,108,104,110,104,110,103\" />\n";
  $output .= "            <area data-timezone=\"America/Menominee\" data-country=\"US\" data-pin=\"154,75\" data-offset=\"-6\" shape=\"poly\" coords=\"154,74,153,73,149,72,154,73,155,74,154,74\" />\n";
  $output .= "            <area data-timezone=\"America/Mexico_City\" data-country=\"MX\" data-pin=\"135,118\" data-offset=\"-6\" shape=\"poly\" coords=\"143,123,142,123,139,124,137,123,125,118,124,116,125,115,126,115,127,115,126,113,127,110,129,109,129,108,132,109,133,112,137,113,140,119,143,120,146,119,147,120,148,120,148,121,148,121,149,123,147,123,146,126,143,123,143,123\" />\n";
  $output .= "            <area data-timezone=\"America/Merida\" data-country=\"MX\" data-pin=\"151,115\" data-offset=\"-6\" shape=\"poly\" coords=\"149,115,150,115,154,114,154,116,151,117,151,120,147,120,146,119,148,119,149,115\" />\n";
  $output .= "            <area data-timezone=\"America/Metlakatla\" data-country=\"US\" data-pin=\"81,58\" data-offset=\"-8\" shape=\"poly\" coords=\"81,58,81,58,81,58\" />\n";
  $output .= "            <area data-timezone=\"America/Miquelon\" data-country=\"PM\" data-pin=\"206,72\" data-offset=\"-3\" shape=\"poly\" coords=\"206,72,206,71,206,72\" />\n";
  $output .= "            <area data-timezone=\"America/Moncton\" data-country=\"CA\" data-pin=\"192,73\" data-offset=\"-4\" shape=\"poly\" coords=\"189,70,192,70,191,72,192,72,192,73,194,73,190,75,188,75,187,74,187,72,185,71,186,70,189,70\" />\n";
  $output .= "            <area data-timezone=\"America/Monterrey\" data-country=\"MX\" data-pin=\"133,107\" data-offset=\"-6\" shape=\"poly\" coords=\"129,108,129,109,127,110,126,113,125,112,121,108,123,105,126,106,127,106,127,104,128,102,129,101,131,101,135,106,138,107,137,108,138,108,137,108,137,113,133,112,132,109,129,108\" />\n";
  $output .= "            <area data-timezone=\"America/Montevideo\" data-country=\"UY\" data-pin=\"206,208\" data-offset=\"-2\" shape=\"poly\" coords=\"207,208,204,207,203,207,204,200,205,200,207,202,207,201,212,205,210,208,207,208\" />\n";
  $output .= "            <area data-timezone=\"America/Montreal\" data-country=\"CA\" data-pin=\"176,73\" data-offset=\"-5\" shape=\"poly\" coords=\"175,75,176,74,172,74,169,73,167,71,167,64,169,65,168,64,169,63,168,62,168,60,167,59,170,58,172,56,172,53,169,52,171,50,170,49,171,49,170,49,171,49,170,49,171,47,170,47,170,46,176,46,177,46,180,47,180,47,181,47,180,48,181,48,184,48,184,50,182,50,184,50,184,51,185,52,183,52,185,52,186,52,184,54,187,52,186,53,187,53,187,53,189,52,190,53,190,52,191,52,190,51,192,51,191,50,192,50,192,51,193,51,193,52,192,52,194,52,193,53,194,54,193,56,194,57,194,57,194,58,194,58,193,59,190,59,189,58,189,59,188,58,188,59,187,60,189,61,188,62,189,62,189,63,192,64,193,62,194,62,194,62,193,63,194,63,205,63,205,64,202,64,201,66,197,66,189,66,189,66,188,68,186,68,182,72,179,73,189,68,192,68,193,69,191,70,186,70,183,72,182,74,181,75,175,75\" />\n";
  $output .= "            <area data-timezone=\"America/Montserrat\" data-country=\"MS\" data-pin=\"196,122\" data-offset=\"-4\" shape=\"poly\" coords=\"196,122,196,122,196,122\" />\n";
  $output .= "            <area data-timezone=\"America/Nassau\" data-country=\"BS\" data-pin=\"171,108\" data-offset=\"-5\" shape=\"poly\" coords=\"172,107,172,107,172,107\" />\n";
  $output .= "            <area data-timezone=\"America/New_York\" data-country=\"US\" data-pin=\"177,82\" data-offset=\"-5\" shape=\"poly\" coords=\"158,99,157,92,159,90,159,89,156,87,159,85,158,85,159,81,164,81,168,79,168,78,173,77,173,76,175,75,182,75,185,71,187,72,187,74,188,75,186,76,185,76,185,77,183,77,182,79,183,80,183,80,183,81,181,81,181,80,181,81,177,82,175,85,174,84,175,86,173,88,174,87,173,85,173,84,172,85,173,87,171,86,173,87,172,87,173,88,172,88,173,88,174,90,173,89,174,90,172,90,174,91,172,91,173,91,172,92,173,92,172,92,165,96,164,98,167,105,166,108,165,108,163,106,164,105,163,106,162,104,163,104,162,104,162,101,160,100,158,101,158,99\" />\n";
  $output .= "            <area data-timezone=\"America/Nipigon\" data-country=\"CA\" data-pin=\"153,68\" data-offset=\"-5\" shape=\"poly\" coords=\"153,68,153,68,153,68\" />\n";
  $output .= "            <area data-timezone=\"America/Nome\" data-country=\"US\" data-pin=\"24,42\" data-offset=\"-9\" shape=\"poly\" coords=\"30,38,27,38,26,37,22,36,23,35,27,35,30,33,30,38\" />\n";
  $output .= "            <area data-timezone=\"America/Nome\" data-country=\"US\" data-pin=\"24,42\" data-offset=\"-9\" shape=\"poly\" coords=\"30,42,29,43,23,42,22,41,23,41,20,41,27,39,26,40,30,40,30,42\" />\n";
  $output .= "            <area data-timezone=\"America/Nome\" data-country=\"US\" data-pin=\"24,42\" data-offset=\"-9\" shape=\"poly\" coords=\"30,50,26,50,24,49,25,48,23,47,26,45,28,45,30,44,30,50\" />\n";
  $output .= "            <area data-timezone=\"America/Noronha\" data-country=\"BR\" data-pin=\"246,156\" data-offset=\"-2\" shape=\"poly\" coords=\"246,156,246,156,246,156\" />\n";
  $output .= "            <area data-timezone=\"America/North_Dakota/Beulah\" data-country=\"US\" data-pin=\"130,71\" data-offset=\"-6\" shape=\"poly\" coords=\"131,71,130,72,130,71,131,71\" />\n";
  $output .= "            <area data-timezone=\"America/North_Dakota/Center\" data-country=\"US\" data-pin=\"131,71\" data-offset=\"-6\" shape=\"poly\" coords=\"132,71,130,72,132,71\" />\n";
  $output .= "            <area data-timezone=\"America/North_Dakota/New_Salem\" data-country=\"US\" data-pin=\"131,72\" data-offset=\"-6\" shape=\"poly\" coords=\"132,72,132,72,132,73,130,72,132,72\" />\n";
  $output .= "            <area data-timezone=\"America/Ojinaga\" data-country=\"MX\" data-pin=\"126,101\" data-offset=\"-7\" shape=\"poly\" coords=\"122,97,128,102,125,101,125,99,122,97,119,98,120,98,120,97,122,97\" />\n";
  $output .= "            <area data-timezone=\"America/Panama\" data-country=\"PA\" data-pin=\"167,135\" data-offset=\"-5\" shape=\"poly\" coords=\"171,136,171,137,170,138,169,137,170,136,170,136,168,135,166,136,167,137,166,138,165,138,165,137,165,137,163,136,162,137,162,136,162,134,165,135,168,134,171,136\" />\n";
  $output .= "            <area data-timezone=\"America/Pangnirtung\" data-country=\"CA\" data-pin=\"190,40\" data-offset=\"-5\" shape=\"poly\" coords=\"187,12,192,12,198,13,187,14,187,12\" />\n";
  $output .= "            <area data-timezone=\"America/Pangnirtung\" data-country=\"CA\" data-pin=\"190,40\" data-offset=\"-5\" shape=\"poly\" coords=\"187,41,188,41,189,42,189,42,192,43,191,43,192,43,192,44,192,45,191,44,192,45,191,45,191,46,189,44,189,45,187,44,187,41\" />\n";
  $output .= "            <area data-timezone=\"America/Pangnirtung\" data-country=\"CA\" data-pin=\"190,40\" data-offset=\"-5\" shape=\"poly\" coords=\"187,36,189,36,187,36,190,36,189,37,189,37,190,37,190,37,192,37,191,37,192,37,193,38,192,38,193,38,192,38,195,38,194,39,195,38,195,39,197,38,198,39,195,39,197,40,195,40,196,40,195,40,196,41,194,40,194,42,191,40,193,39,190,40,191,39,190,40,187,39,188,39,187,39,188,40,187,40,187,36\" />\n";
  $output .= "            <area data-timezone=\"America/Paramaribo\" data-country=\"SR\" data-pin=\"208,140\" data-offset=\"-3\" shape=\"poly\" coords=\"209,141,210,144,209,146,207,146,207,147,206,147,203,143,204,142,205,141,205,140,210,140,209,141\" />\n";
  $output .= "            <area data-timezone=\"America/Phoenix\" data-country=\"US\" data-pin=\"113,94\" data-offset=\"-7\" shape=\"poly\" coords=\"118,96,118,98,115,98,109,96,110,93,109,90,110,90,110,88,114,88,114,89,115,91,118,91,118,96\" />\n";
  $output .= "            <area data-timezone=\"America/Port-au-Prince\" data-country=\"HT\" data-pin=\"179,119\" data-offset=\"-5\" shape=\"poly\" coords=\"178,118,179,119,178,118\" />\n";
  $output .= "            <area data-timezone=\"America/Port_of_Spain\" data-country=\"TT\" data-pin=\"197,132\" data-offset=\"-4\" shape=\"poly\" coords=\"197,132,197,132,197,132\" />\n";
  $output .= "            <area data-timezone=\"America/Porto_Velho\" data-country=\"BR\" data-pin=\"194,165\" data-offset=\"-4\" shape=\"poly\" coords=\"200,170,199,172,197,173,192,170,191,166,189,166,189,166,192,166,193,165,194,163,195,163,198,165,197,168,200,169,200,170\" />\n";
  $output .= "            <area data-timezone=\"America/Puerto_Rico\" data-country=\"PR\" data-pin=\"190,119\" data-offset=\"-4\" shape=\"poly\" coords=\"190,119,190,119,190,119\" />\n";
  $output .= "            <area data-timezone=\"America/Rainy_River\" data-country=\"CA\" data-pin=\"142,69\" data-offset=\"-6\" shape=\"poly\" coords=\"142,69,142,69,142,69\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,14,150,15,149,15,155,14,147,14,158,13,158,14\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"136,22,137,23,137,24,138,24,133,25,132,24,134,24,130,24,130,24,131,24,130,23,134,24,133,23,134,23,132,23,136,22\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"150,19,151,20,146,20,145,19,147,19,143,18,149,18,141,18,140,17,143,17,139,17,143,17,142,17,143,16,139,16,144,16,141,15,145,15,143,15,145,14,152,16,154,16,153,16,155,17,154,17,158,18,154,19,153,18,153,19,154,19,152,19,152,20,150,19\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,26,147,25,146,25,146,24,145,23,141,23,142,23,138,22,141,22,145,22,144,23,148,22,149,23,147,23,151,23,148,23,152,24,158,24,158,26\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,22,151,22,156,21,153,21,155,20,158,21,158,22\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,33,152,32,151,32,155,32,150,31,150,30,151,30,150,29,153,27,158,27,155,29,156,29,156,30,158,31,155,32,158,31,158,33\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"150,43,149,44,143,43,147,44,149,45,146,45,147,46,144,47,145,47,143,48,144,48,142,50,130,50,130,38,152,38,152,35,153,35,154,36,153,37,155,38,156,38,157,35,158,35,158,40,155,39,157,40,154,41,148,40,155,41,153,43,150,43\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"141,27,144,26,150,27,147,29,143,29,144,29,144,30,140,29,141,27,142,27,141,27\" />\n";
  $output .= "            <area data-timezone=\"America/Recife\" data-country=\"BR\" data-pin=\"242,163\" data-offset=\"-3\" shape=\"poly\" coords=\"236,163,237,163,238,162,238,164,241,162,242,163,241,165,238,166,237,165,236,166,234,164,232,166,231,165,232,164,232,162,236,163\" />\n";
  $output .= "            <area data-timezone=\"America/Regina\" data-country=\"CA\" data-pin=\"126,66\" data-offset=\"-6\" shape=\"poly\" coords=\"125,68,117,68,117,62,119,62,117,61,117,50,130,50,130,58,129,59,130,60,131,68,125,68\" />\n";
  $output .= "            <area data-timezone=\"America/Resolute\" data-country=\"CA\" data-pin=\"142,26\" data-offset=\"-6\" shape=\"poly\" coords=\"141,24,144,24,144,25,139,25,141,24\" />\n";
  $output .= "            <area data-timezone=\"America/Rio_Branco\" data-country=\"BR\" data-pin=\"187,167\" data-offset=\"-4\" shape=\"poly\" coords=\"177,162,183,164,189,167,186,169,182,168,183,166,180,167,179,166,178,166,178,165,177,163,177,162\" />\n";
  $output .= "            <area data-timezone=\"America/Santa_Isabel\" data-country=\"PR\" data-pin=\"300,150\" data-offset=\"-8\" shape=\"poly\" coords=\"109,96,109,100,112,103,110,103,110,102,107,100,106,97,106,96,109,96\" />\n";
  $output .= "            <area data-timezone=\"America/Santarem\" data-country=\"BR\" data-pin=\"209,154\" data-offset=\"-3\" shape=\"poly\" coords=\"212,150,213,152,214,152,213,153,214,155,212,156,212,158,213,161,212,164,213,165,212,166,205,166,204,165,203,161,206,154,202,151,202,148,204,147,207,147,207,146,208,146,209,147,211,148,212,150\" />\n";
  $output .= "            <area data-timezone=\"America/Santiago\" data-country=\"CL\" data-pin=\"182,206\" data-offset=\"-3\" shape=\"poly\" coords=\"183,239,184,237,186,238,186,241,180,241,183,241,182,240,185,241,184,241,185,241,183,240,184,239,183,239,183,239\" />\n";
  $output .= "            <area data-timezone=\"America/Santiago\" data-country=\"CL\" data-pin=\"182,206\" data-offset=\"-3\" shape=\"poly\" coords=\"178,232,177,234,178,235,180,234,179,236,180,237,186,237,182,238,181,240,179,239,180,239,181,238,181,238,179,238,178,238,178,238,179,238,177,238,177,237,179,237,179,237,179,237,178,237,179,237,179,236,178,235,178,236,179,236,178,236,178,236,178,237,178,236,178,237,177,236,177,235,176,235,178,234,177,234,177,235,177,234,176,234,177,234,176,233,177,233,177,232,176,232,176,231,177,231,176,231,177,231,176,230,178,230,177,229,175,230,176,229,176,229,177,229,176,228,174,228,175,226,177,226,177,228,178,226,177,226,178,226,179,226,177,225,179,224,178,224,179,221,179,221,179,221,179,220,179,220,180,219,177,220,177,219,178,216,177,212,178,212,181,206,181,198,182,194,182,189,183,186,183,181,184,179,186,182,185,184,187,188,188,188,188,190,186,191,186,195,184,197,184,200,182,202,184,207,182,209,183,210,181,211,182,214,180,216,180,220,180,221,181,223,180,224,181,224,180,225,181,225,180,226,181,228,179,230,179,231,178,232\" />\n";
  $output .= "            <area data-timezone=\"America/Santo_Domingo\" data-country=\"DO\" data-pin=\"184,119\" data-offset=\"-4\" shape=\"poly\" coords=\"182,120,181,120,180,119,181,117,183,117,185,118,184,118,186,119,186,120,182,120\" />\n";
  $output .= "            <area data-timezone=\"America/Scoresbysund\" data-country=\"GL\" data-pin=\"263,33\" data-offset=\"-1\" shape=\"poly\" coords=\"259,29,263,30,261,31,263,30,262,31,264,31,264,31,263,32,264,32,263,32,264,32,260,32,259,31,259,29\" />\n";
  $output .= "            <area data-timezone=\"America/Sao_Paulo\" data-country=\"BR\" data-pin=\"222,189\" data-offset=\"-3\" shape=\"poly\" coords=\"217,199,215,202,213,204,216,201,215,200,215,201,211,206,211,205,212,205,210,204,207,201,207,202,205,200,204,200,207,197,211,195,210,193,209,193,209,190,211,188,213,186,215,182,212,181,211,179,215,175,216,171,218,172,218,171,219,172,220,172,223,171,223,175,226,174,233,177,232,179,234,181,234,182,232,185,232,187,230,188,226,188,220,192,219,192,219,193,219,194,219,194,219,197,217,199\" />\n";
  $output .= "            <area data-timezone=\"America/Sitka\" data-country=\"US\" data-pin=\"74,55\" data-offset=\"-9\" shape=\"poly\" coords=\"79,54,80,56,77,55,79,54,79,54\" />\n";
  $output .= "            <area data-timezone=\"America/St_Barthelemy\" data-country=\"BL\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/St_Johns\" data-country=\"CA\" data-pin=\"212,71\" data-offset=\"-3.5\" shape=\"poly\" coords=\"208,64,206,64,207,65,206,65,205,67,206,66,208,67,206,68,208,67,208,68,209,67,211,68,210,69,212,69,210,70,211,71,212,70,211,71,212,71,212,72,211,72,211,71,210,72,210,71,210,70,207,72,209,71,206,71,207,71,207,70,205,71,201,71,203,69,201,69,203,68,204,68,203,68,204,68,203,67,204,65,208,64\" />\n";
  $output .= "            <area data-timezone=\"America/Thule\" data-country=\"GL\" data-pin=\"185,22\" data-offset=\"-4\" shape=\"poly\" coords=\"194,23,186,23,184,23,187,22,181,22,190,21,183,21,184,21,178,20,189,18,194,23\" />\n";
  $output .= "            <area data-timezone=\"America/St_Kitts\" data-country=\"KN\" data-pin=\"195,121\" data-offset=\"-4\" shape=\"poly\" coords=\"195,121,196,121,195,121\" />\n";
  $output .= "            <area data-timezone=\"America/St_Lucia\" data-country=\"LC\" data-pin=\"198,127\" data-offset=\"-4\" shape=\"poly\" coords=\"198,126,198,127,198,126\" />\n";
  $output .= "            <area data-timezone=\"America/St_Thomas\" data-country=\"VI\" data-pin=\"192,119\" data-offset=\"-4\" shape=\"poly\" coords=\"192,119,192,119,192,119\" />\n";
  $output .= "            <area data-timezone=\"America/St_Vincent\" data-country=\"VC\" data-pin=\"198,128\" data-offset=\"-4\" shape=\"poly\" coords=\"198,128,198,128,198,128\" />\n";
  $output .= "            <area data-timezone=\"America/Swift_Current\" data-country=\"CA\" data-pin=\"120,66\" data-offset=\"-6\" shape=\"poly\" coords=\"120,66,120,66,120,66\" />\n";
  $output .= "            <area data-timezone=\"America/Tegucigalpa\" data-country=\"HN\" data-pin=\"155,127\" data-offset=\"-6\" shape=\"poly\" coords=\"155,127,154,128,154,127,151,126,151,125,153,124,157,123,159,124,161,125,158,125,157,127,155,127\" />\n";
  $output .= "            <area data-timezone=\"America/Thunder_Bay\" data-country=\"CA\" data-pin=\"151,69\" data-offset=\"-5\" shape=\"poly\" coords=\"151,69,151,70,151,69\" />\n";
  $output .= "            <area data-timezone=\"America/Tijuana\" data-country=\"MX\" data-pin=\"105,96\" data-offset=\"-8\" shape=\"poly\" coords=\"105,96,105,96,105,96\" />\n";
  $output .= "            <area data-timezone=\"America/Toronto\" data-country=\"CA\" data-pin=\"168,77\" data-offset=\"-5\" shape=\"poly\" coords=\"175,74,176,75,172,77,170,76,172,76,171,77,168,77,167,78,168,79,161,80,164,78,165,76,164,75,165,76,167,75,165,73,164,73,164,74,160,73,159,72,159,70,157,70,156,69,153,68,153,69,151,69,151,70,148,69,148,68,150,68,150,65,149,64,150,63,150,62,152,62,153,61,152,60,150,60,150,56,152,55,154,57,158,58,163,58,163,62,166,64,167,64,168,72,169,73,173,74,175,74\" />\n";
  $output .= "            <area data-timezone=\"America/Tortola\" data-country=\"VG\" data-pin=\"192,119\" data-offset=\"-4\" shape=\"poly\" coords=\"192,119,192,119,192,119\" />\n";
  $output .= "            <area data-timezone=\"America/Vancouver\" data-country=\"CA\" data-pin=\"95,68\" data-offset=\"-8\" shape=\"poly\" coords=\"86,65,91,66,95,69,91,69,92,68,91,68,90,68,89,68,90,67,87,66,88,66,86,65\" />\n";
  $output .= "            <area data-timezone=\"America/Vancouver\" data-country=\"CA\" data-pin=\"95,68\" data-offset=\"-8\" shape=\"poly\" coords=\"75,51,74,50,71,52,68,50,100,50,100,55,94,55,95,58,103,62,103,63,102,63,106,66,106,68,95,68,98,68,97,68,96,67,97,68,95,68,96,67,95,68,95,68,95,68,94,67,95,67,93,67,94,67,93,67,94,66,93,67,92,67,93,66,92,66,93,65,92,65,91,66,90,66,91,65,90,66,89,65,90,65,88,65,89,65,87,64,89,64,87,64,87,64,88,63,89,63,88,63,89,63,88,63,88,62,88,63,87,63,87,62,86,63,87,62,85,61,87,62,85,61,86,60,85,61,83,60,85,59,83,59,83,60,83,59,85,58,83,58,84,58,84,57,83,58,83,56,80,56,78,53,75,51\" />\n";
  $output .= "            <area data-timezone=\"America/Whitehorse\" data-country=\"CA\" data-pin=\"75,49\" data-offset=\"-8\" shape=\"poly\" coords=\"69,34,73,35,73,38,77,38,77,40,79,40,79,42,83,44,84,44,83,45,84,45,85,46,88,48,89,49,92,48,93,50,65,49,65,34,69,34\" />\n";
  $output .= "            <area data-timezone=\"America/Winnipeg\" data-country=\"CA\" data-pin=\"138,67\" data-offset=\"-6\" shape=\"poly\" coords=\"147,69,142,69,141,68,141,68,131,68,130,60,129,59,130,58,130,50,142,50,142,52,145,52,146,54,145,55,148,55,152,55,150,56,150,60,152,60,153,61,152,62,150,62,150,63,149,64,150,65,150,68,147,68,147,69\" />\n";
  $output .= "            <area data-timezone=\"America/Yakutat\" data-country=\"US\" data-pin=\"67,51\" data-offset=\"-9\" shape=\"poly\" coords=\"67,50,67,50,67,50\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"100,37,111,41,114,41,116,42,130,43,130,50,93,50,92,48,89,49,88,48,85,46,84,45,83,45,84,44,83,44,79,42,79,40,77,40,77,38,73,38,73,35,75,36,73,35,76,34,77,34,77,35,84,33,80,34,80,35,82,34,82,35,83,34,88,33,87,32,91,34,92,33,93,34,93,34,95,34,98,34,97,34,97,36,100,37\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"106,21,108,21,106,21,107,22,105,23,103,23,104,22,100,24,95,23,101,21,106,21\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"100,27,104,26,108,28,101,29,100,30,99,31,95,32,90,30,94,27,92,26,98,26,100,27\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"109,23,117,24,117,25,111,26,109,26,115,25,104,25,108,24,105,24,109,24,105,23,109,23\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"108,28,110,28,109,29,112,28,115,29,114,30,117,29,117,33,105,33,105,34,104,33,114,33,104,32,103,32,108,31,103,31,104,31,101,30,103,30,103,29,108,28\" />\n";
  $output .= "            <area data-timezone=\"Antarctica/Macquarie\" data-country=\"AU\" data-pin=\"565,241\" data-offset=\"11\" shape=\"poly\" coords=\"565,241,565,241,565,241\" />\n";
  $output .= "            <area data-timezone=\"Arctic/Longyearbyen\" data-country=\"SJ\" data-pin=\"327,20\" data-offset=\"1\" shape=\"poly\" coords=\"330,17,330,17,329,18,331,17,336,19,332,19,332,20,328,22,328,22,327,22,326,22,328,22,323,21,327,21,325,21,328,20,323,20,323,20,329,19,327,19,328,19,326,19,326,19,324,19,325,19,322,20,321,19,322,19,319,18,321,18,319,18,320,18,319,18,318,17,323,17,321,17,323,18,323,17,324,17,327,18,326,17,330,17\" />\n";
  $output .= "            <area data-timezone=\"Arctic/Longyearbyen\" data-country=\"SJ\" data-pin=\"327,20\" data-offset=\"1\" shape=\"poly\" coords=\"342,16,345,17,339,18,329,16,332,16,332,16,333,16,333,16,337,17,338,16,339,16,339,16,342,16\" />\n";
  $output .= "            <area data-timezone=\"Asia/Aden\" data-country=\"YE\" data-pin=\"375,129\" data-offset=\"3\" shape=\"poly\" coords=\"375,128,372,129,371,125,372,121,377,123,377,124,381,120,387,118,389,122,387,123,387,124,375,128\" />\n";
  $output .= "            <area data-timezone=\"Asia/Almaty\" data-country=\"KZ\" data-pin=\"428,78\" data-offset=\"6\" shape=\"poly\" coords=\"418,79,414,82,411,81,410,79,413,78,413,77,412,76,412,73,404,72,407,70,407,70,408,68,410,68,414,66,413,65,413,63,410,63,411,62,410,62,411,61,410,59,415,58,418,58,419,60,423,60,422,61,428,59,427,60,430,61,433,65,434,64,436,65,439,65,442,67,445,67,446,68,443,69,443,72,438,71,437,74,437,75,436,74,433,75,434,75,435,78,434,80,431,78,426,79,424,78,423,78,422,79,420,79,418,79\" />\n";
  $output .= "            <area data-timezone=\"Asia/Amman\" data-country=\"JO\" data-pin=\"360,97\" data-offset=\"2\" shape=\"poly\" coords=\"362,98,363,99,362,100,360,101,358,101,359,96,361,96,365,94,366,96,362,98,362,98\" />\n";
  $output .= "            <area data-timezone=\"Asia/Anadyr\" data-country=\"RU\" data-pin=\"596,42\" data-offset=\"12\" shape=\"poly\" coords=\"12,38,17,40,16,41,14,40,15,41,12,41,13,41,13,42,11,42,13,43,12,43,8,42,7,41,2,41,2,40,3,39,1,40,1,41,0,42,0,35,8,37,9,39,11,39,9,38,12,38\" />\n";
  $output .= "            <area data-timezone=\"Asia/Anadyr\" data-country=\"RU\" data-pin=\"596,42\" data-offset=\"12\" shape=\"poly\" coords=\"596,42,592,42,597,43,599,46,598,46,595,46,591,47,589,46,584,46,581,45,583,44,581,43,568,41,565,40,565,40,564,39,565,38,563,37,564,36,571,36,571,35,571,34,571,34,580,34,583,35,585,35,584,34,584,33,594,34,600,35,600,42,598,42,596,42\" />\n";
  $output .= "            <area data-timezone=\"Asia/Aqtau\" data-country=\"KZ\" data-pin=\"384,76\" data-offset=\"5\" shape=\"poly\" coords=\"393,75,393,81,390,79,387,80,388,79,385,78,384,76,386,76,385,75,386,74,388,74,388,72,385,72,382,73,381,72,382,72,381,71,378,70,382,70,390,68,390,69,392,69,392,72,394,73,395,75,393,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Aqtobe\" data-country=\"KZ\" data-pin=\"395,66\" data-offset=\"5\" shape=\"poly\" coords=\"391,66,391,65,393,66,394,65,397,65,399,66,402,65,403,64,405,67,404,68,407,70,405,71,402,70,398,74,395,75,394,72,392,71,392,69,389,68,391,67,391,66\" />\n";
  $output .= "            <area data-timezone=\"Asia/Ashgabat\" data-country=\"TM\" data-pin=\"397,87\" data-offset=\"5\" shape=\"poly\" coords=\"408,89,407,90,404,91,402,91,402,89,401,89,399,87,395,86,390,88,390,85,389,85,389,84,390,84,388,83,388,82,387,80,390,79,393,81,395,81,395,80,397,79,398,80,397,79,398,79,400,80,401,81,403,81,404,83,411,87,411,88,409,87,408,89\" />\n";
  $output .= "            <area data-timezone=\"Asia/Baghdad\" data-country=\"IQ\" data-pin=\"374,94\" data-offset=\"3\" shape=\"poly\" coords=\"379,100,378,101,375,101,370,98,365,96,365,94,368,93,369,89,371,88,375,88,376,90,377,90,376,93,377,95,379,96,380,97,379,98,381,100,379,100\" />\n";
  $output .= "            <area data-timezone=\"Asia/Bahrain\" data-country=\"BH\" data-pin=\"384,106\" data-offset=\"3\" shape=\"poly\" coords=\"384,106,384,106,384,106\" />\n";
  $output .= "            <area data-timezone=\"Asia/Bangkok\" data-country=\"TH\" data-pin=\"468,127\" data-offset=\"7\" shape=\"poly\" coords=\"470,139,470,140,469,141,469,140,467,139,467,139,464,136,465,132,466,130,465,127,464,125,465,123,462,119,463,119,463,117,465,117,467,116,468,116,468,117,469,117,468,121,470,120,471,120,472,119,473,119,476,124,475,126,473,126,471,127,472,131,470,129,468,129,468,128,467,128,465,135,466,134,468,138,470,139\" />\n";
  $output .= "            <area data-timezone=\"Asia/Baku\" data-country=\"AZ\" data-pin=\"383,83\" data-offset=\"4\" shape=\"poly\" coords=\"377,80,380,81,381,80,384,83,382,83,381,86,380,85,381,84,380,84,378,85,378,84,376,83,377,83,375,81,378,81,377,80\" />\n";
  $output .= "            <area data-timezone=\"Asia/Chongqing\" data-country=\"CN\" data-pin=\"475,100\" data-offset=\"8\" shape=\"poly\" coords=\"483,114,482,114,481,113,481,114,479,114,478,113,478,112,476,111,473,112,472,112,472,113,471,112,469,113,470,115,469,115,469,114,467,114,467,113,465,113,466,112,465,111,465,110,463,110,463,108,465,107,465,104,463,103,465,103,464,102,465,101,465,98,462,95,463,94,462,94,464,94,465,93,465,92,465,92,465,90,466,87,466,87,466,87,462,85,463,84,461,84,462,82,464,82,462,79,468,79,475,81,479,79,484,79,485,78,488,80,491,80,490,81,490,82,487,83,485,84,484,86,485,87,484,92,485,94,483,95,484,96,482,96,484,98,481,99,481,100,482,102,482,104,481,105,482,105,482,106,483,107,485,106,485,108,486,108,487,109,486,111,487,111,488,113,486,114,485,113,486,113,485,112,483,114\" />\n";
  $output .= "            <area data-timezone=\"Asia/Beirut\" data-country=\"LB\" data-pin=\"359,94\" data-offset=\"2\" shape=\"poly\" coords=\"360,94,359,95,359,93,361,92,361,93,360,94\" />\n";
  $output .= "            <area data-timezone=\"Asia/Bishkek\" data-country=\"KG\" data-pin=\"424,79\" data-offset=\"6\" shape=\"poly\" coords=\"425,82,423,83,423,84,420,85,415,84,422,82,420,81,419,81,417,81,419,80,418,79,419,79,422,79,423,78,424,78,426,79,431,78,434,80,430,82,428,82,427,83,425,82\" />\n";
  $output .= "            <area data-timezone=\"Asia/Brunei\" data-country=\"BN\" data-pin=\"492,142\" data-offset=\"8\" shape=\"poly\" coords=\"492,142,492,143,492,142\" />\n";
  $output .= "            <area data-timezone=\"Asia/Choibalsan\" data-country=\"MN\" data-pin=\"491,70\" data-offset=\"8\" shape=\"poly\" coords=\"494,73,493,74,491,74,489,75,487,75,486,73,486,71,488,70,487,67,491,66,495,67,493,70,493,71,497,70,500,71,499,73,498,72,494,73\" />\n";
  $output .= "            <area data-timezone=\"Asia/Colombo\" data-country=\"LK\" data-pin=\"433,138\" data-offset=\"5.5\" shape=\"poly\" coords=\"436,139,434,140,433,140,433,136,433,134,436,136,436,138,436,139\" />\n";
  $output .= "            <area data-timezone=\"Asia/Damascus\" data-country=\"SY\" data-pin=\"360,94\" data-offset=\"2\" shape=\"poly\" coords=\"362,96,361,96,359,95,359,95,361,93,360,91,361,90,361,89,365,89,371,88,369,89,368,93,362,96\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dhaka\" data-country=\"BD\" data-pin=\"451,110\" data-offset=\"6\" shape=\"poly\" coords=\"450,113,450,112,450,114,449,114,448,110,447,109,448,108,447,107,447,106,449,107,449,106,450,108,454,108,452,110,453,112,454,111,454,115,454,114,454,115,453,113,454,113,453,113,452,112,451,112,451,110,450,110,451,111,448,110,450,111,451,113,450,113\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dili\" data-country=\"TL\" data-pin=\"509,164\" data-offset=\"9\" shape=\"poly\" coords=\"509,164,512,164,509,166,509,164\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dubai\" data-country=\"AE\" data-pin=\"392,108\" data-offset=\"4\" shape=\"poly\" coords=\"393,109,393,110,392,110,392,112,388,112,386,110,391,110,394,107,394,108,393,109\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dushanbe\" data-country=\"TJ\" data-pin=\"415,86\" data-offset=\"5\" shape=\"poly\" coords=\"416,88,413,88,414,86,412,84,414,84,415,83,414,83,417,82,418,82,417,83,418,83,416,83,416,84,419,84,420,85,423,84,423,86,425,86,425,88,422,88,419,89,419,87,418,86,416,88\" />\n";
  $output .= "            <area data-timezone=\"Asia/Gaza\" data-country=\"PS\" data-pin=\"357,98\" data-offset=\"2\" shape=\"poly\" coords=\"357,97,357,98,357,97\" />\n";
  $output .= "            <area data-timezone=\"Asia/Harbin\" data-country=\"CN\" data-pin=\"509,75\" data-offset=\"8\" shape=\"poly\" coords=\"509,82,508,78,507,79,506,78,505,76,504,76,504,75,503,74,505,74,505,72,506,72,504,71,507,69,508,70,510,65,509,64,505,64,504,63,507,61,510,62,513,67,518,69,518,71,524,69,525,70,522,75,520,74,518,75,519,78,517,79,518,79,517,78,516,79,513,80,514,81,512,80,509,82\" />\n";
  $output .= "            <area data-timezone=\"Asia/Hebron\" data-country=\"PS\" data-pin=\"358,97\" data-offset=\"2\" shape=\"poly\" coords=\"359,97,359,96,359,97,358,98,359,97\" />\n";
  $output .= "            <area data-timezone=\"Asia/Ho_Chi_Minh\" data-country=\"VN\" data-pin=\"478,132\" data-offset=\"7\" shape=\"poly\" coords=\"476,135,475,136,475,133,474,133,475,132,477,132,476,131,479,129,479,125,475,119,473,118,475,117,474,116,474,116,472,115,472,114,470,113,471,112,473,112,476,111,478,112,478,113,480,114,478,115,478,116,476,118,482,125,482,129,482,131,478,133,478,134,476,135\" />\n";
  $output .= "            <area data-timezone=\"Asia/Hong_Kong\" data-country=\"HK\" data-pin=\"490,113\" data-offset=\"8\" shape=\"poly\" coords=\"490,112,490,113,490,112\" />\n";
  $output .= "            <area data-timezone=\"Asia/Hovd\" data-country=\"MN\" data-pin=\"453,70\" data-offset=\"7\" shape=\"poly\" coords=\"464,75,463,77,463,79,461,79,459,76,451,75,452,72,450,70,447,69,446,68,454,65,457,66,458,67,462,67,462,68,463,68,465,68,465,69,463,70,464,71,463,72,464,74,464,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Irkutsk\" data-country=\"RU\" data-pin=\"474,63\" data-offset=\"9\" shape=\"poly\" coords=\"493,55,492,56,493,58,495,58,495,59,490,61,491,62,489,63,487,63,484,64,481,64,480,65,481,66,480,67,476,66,472,66,470,66,470,64,464,63,465,61,461,61,459,60,461,59,461,57,463,56,462,55,463,54,467,53,468,54,469,53,471,52,471,51,474,52,476,52,475,51,476,50,476,50,474,49,477,47,478,46,477,45,478,44,478,43,481,43,481,44,480,44,482,44,483,45,482,46,483,46,483,47,483,48,484,48,482,51,483,52,487,51,488,52,493,49,495,50,495,51,498,51,499,53,496,53,495,54,496,55,496,55,493,55\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jakarta\" data-country=\"ID\" data-pin=\"478,160\" data-offset=\"7\" shape=\"poly\" coords=\"487,161,488,163,491,163,491,165,480,163,475,161,477,160,480,160,481,161,484,162,485,161,487,161\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jakarta\" data-country=\"ID\" data-pin=\"478,160\" data-offset=\"7\" shape=\"poly\" coords=\"476,157,476,160,475,159,475,160,474,159,474,160,471,157,466,150,465,150,465,147,459,142,459,141,463,141,464,143,472,149,471,150,473,149,472,151,474,152,475,154,476,154,477,155,476,157\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jayapura\" data-country=\"ID\" data-pin=\"534,154\" data-offset=\"9\" shape=\"poly\" coords=\"525,157,524,157,525,157,523,156,523,155,522,156,522,157,521,155,520,155,522,154,523,155,523,153,521,154,520,152,518,152,519,151,521,151,523,151,524,154,525,156,530,152,535,154,535,165,533,164,534,163,532,164,532,163,531,162,533,162,531,162,532,162,529,158,525,157\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kabul\" data-country=\"AF\" data-pin=\"415,92\" data-offset=\"4.5\" shape=\"poly\" coords=\"405,101,401,100,403,98,401,98,401,95,402,94,401,93,402,91,405,91,410,87,413,88,415,88,418,86,419,87,419,89,422,88,425,88,419,90,419,91,418,93,416,93,417,94,416,95,415,97,414,97,413,98,411,98,410,100,405,101\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jerusalem\" data-country=\"IL\" data-pin=\"359,97\" data-offset=\"2\" shape=\"poly\" coords=\"358,97,359,95,359,95,359,96,358,96,359,97,358,98,359,98,358,101,357,98,358,97\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kamchatka\" data-country=\"RU\" data-pin=\"564,62\" data-offset=\"12\" shape=\"poly\" coords=\"571,54,572,56,570,57,570,58,570,59,567,60,567,62,564,62,564,63,561,65,559,57,562,54,561,54,565,53,570,49,573,49,573,48,573,48,574,46,575,46,572,46,572,47,572,47,571,47,570,46,571,45,570,45,572,44,571,43,572,43,572,42,581,43,583,44,581,45,584,46,589,46,591,47,587,48,584,50,581,49,577,50,577,49,575,50,573,50,572,52,570,53,572,54,571,54\" />\n";
  $output .= "            <area data-timezone=\"Asia/Karachi\" data-country=\"PK\" data-pin=\"412,109\" data-offset=\"5\" shape=\"poly\" coords=\"417,104,416,105,418,107,419,109,418,110,415,109,413,110,412,109,411,109,411,107,403,108,403,106,405,106,406,105,405,105,405,103,403,102,401,100,407,101,410,100,411,98,416,97,416,95,417,94,416,93,418,93,418,92,419,91,419,90,420,89,425,88,427,89,427,90,430,91,426,92,423,92,423,95,426,96,424,97,424,98,420,103,417,104\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kashgar\" data-country=\"CN\" data-pin=\"430,95\" data-offset=\"6\" shape=\"poly\" coords=\"434,99,431,98,431,96,432,96,432,95,431,94,432,93,430,92,430,91,427,90,426,89,424,88,425,88,425,86,423,86,423,85,425,82,427,83,428,82,430,82,434,80,434,79,435,78,434,75,433,75,436,74,438,75,436,76,442,78,438,77,439,78,438,78,438,80,436,82,437,84,438,84,437,88,438,90,437,91,437,92,437,93,439,95,439,97,438,97,439,98,439,99,437,99,435,100,434,99\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kathmandu\" data-country=\"NP\" data-pin=\"442,104\" data-offset=\"5.8\" shape=\"poly\" coords=\"442,103,447,104,447,106,443,106,440,104,439,104,433,102,434,100,436,99,442,102,442,103\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kolkata\" data-country=\"IN\" data-pin=\"447,112\" data-offset=\"5.5\" shape=\"poly\" coords=\"434,128,433,130,433,133,432,133,432,134,432,135,431,135,430,136,429,136,428,135,422,123,421,117,422,115,421,114,422,114,421,114,421,113,421,113,420,115,418,115,415,113,417,112,417,112,415,112,414,111,415,110,414,110,415,110,419,109,416,105,417,103,420,103,424,98,424,97,426,96,423,95,423,92,426,92,430,91,430,92,432,93,431,94,432,95,432,96,431,96,431,98,435,100,433,102,443,106,447,106,447,103,448,103,449,105,453,105,454,105,453,104,454,104,458,101,461,101,460,102,461,102,460,103,462,103,461,104,462,105,460,105,459,106,457,110,456,110,455,113,454,110,453,112,452,111,454,108,450,108,449,106,449,107,447,106,447,107,448,108,447,109,448,110,448,114,448,113,447,114,447,113,445,114,445,115,444,117,442,118,437,122,437,122,434,124,434,128\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"475,19,466,20,471,18,472,18,471,19,473,18,476,19,475,19\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"462,16,464,17,461,17,467,17,466,18,465,18,467,18,459,18,457,17,455,17,462,16\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"459,15,463,16,461,15,462,16,456,17,452,16,459,15\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"461,61,465,62,463,65,464,66,462,67,458,67,457,66,454,65,449,67,449,66,450,66,448,64,447,64,449,62,448,61,449,60,447,59,448,59,447,58,449,57,448,56,449,54,447,53,448,52,448,51,445,50,441,50,441,49,440,49,443,48,441,46,443,45,442,44,443,43,443,42,440,42,441,41,439,40,439,39,437,38,437,37,436,37,438,36,437,35,432,34,435,32,432,31,434,30,431,29,439,31,437,31,437,32,436,33,437,33,437,33,440,34,438,33,440,33,439,31,439,31,435,29,435,28,434,28,435,28,434,27,445,27,446,27,443,26,446,26,443,26,445,26,446,25,445,25,449,24,457,23,455,23,455,23,466,23,465,23,469,22,469,21,474,20,477,21,474,22,479,22,478,23,485,22,487,23,487,23,490,23,489,24,487,24,490,24,488,25,480,27,477,27,478,27,475,29,485,27,485,27,484,28,485,28,484,29,487,30,487,31,488,31,484,32,482,34,477,34,478,35,478,38,476,38,477,39,477,41,478,41,476,42,481,43,478,43,478,44,477,45,478,46,477,47,475,48,474,49,476,50,476,50,475,51,476,52,475,52,472,51,468,54,468,54,467,53,463,54,462,55,463,56,461,57,461,59,459,59,461,61\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kuala_Lumpur\" data-country=\"MY\" data-pin=\"470,145\" data-offset=\"8\" shape=\"poly\" coords=\"473,146,474,148,473,148,469,145,468,143,467,139,469,140,469,141,470,140,472,141,473,146\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kuching\" data-country=\"MY\" data-pin=\"484,147\" data-offset=\"8\" shape=\"poly\" coords=\"492,142,495,138,495,139,495,138,496,139,497,141,499,141,497,142,498,143,493,143,491,148,487,147,486,148,484,149,483,147,483,147,486,148,485,147,486,145,488,145,490,142,491,143,492,142,492,143,492,142\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kuwait\" data-country=\"KW\" data-pin=\"380,101\" data-offset=\"3\" shape=\"poly\" coords=\"380,100,380,100,380,100\" />\n";
  $output .= "            <area data-timezone=\"Asia/Macau\" data-country=\"MO\" data-pin=\"489,113\" data-offset=\"8\" shape=\"poly\" coords=\"489,113,489,113,489,113\" />\n";
  $output .= "            <area data-timezone=\"Asia/Magadan\" data-country=\"RU\" data-pin=\"551,51\" data-offset=\"12\" shape=\"poly\" coords=\"557,50,557,51,559,51,552,52,552,52,554,51,549,50,548,51,546,51,545,51,545,50,543,49,545,48,544,47,540,47,534,46,533,45,534,43,532,42,534,41,533,40,534,39,532,38,535,37,535,36,537,36,536,35,538,35,537,34,537,34,535,33,540,30,549,30,550,30,548,31,554,32,564,32,566,32,566,34,568,34,568,35,571,34,571,35,571,36,564,36,563,37,565,38,564,39,565,40,565,40,572,42,572,43,571,43,572,44,570,45,571,45,570,46,571,47,567,49,567,48,566,48,567,47,562,47,557,50\" />\n";
  $output .= "            <area data-timezone=\"Asia/Makassar\" data-country=\"ID\" data-pin=\"499,159\" data-offset=\"8\" shape=\"poly\" coords=\"494,154,493,156,491,157,491,155,493,152,492,150,491,150,492,149,490,149,492,146,493,143,496,143,496,144,495,144,496,144,495,144,497,146,496,147,498,148,496,148,496,151,494,153,494,154\" />\n";
  $output .= "            <area data-timezone=\"Asia/Makassar\" data-country=\"ID\" data-pin=\"499,159\" data-offset=\"8\" shape=\"poly\" coords=\"504,155,504,156,505,157,503,158,501,156,502,154,500,155,501,159,499,159,499,157,499,156,498,156,498,155,499,152,500,151,500,149,502,148,507,149,509,147,507,149,501,149,500,150,500,151,501,152,503,151,506,151,506,152,502,153,504,155\" />\n";
  $output .= "            <area data-timezone=\"Asia/Manila\" data-country=\"PH\" data-pin=\"502,126\" data-offset=\"8\" shape=\"poly\" coords=\"507,140,507,139,507,138,506,137,506,138,504,137,504,138,503,138,504,137,506,135,506,136,506,137,508,136,508,135,509,135,509,134,510,134,511,138,510,140,509,138,509,141,509,140,507,140\" />\n";
  $output .= "            <area data-timezone=\"Asia/Manila\" data-country=\"PH\" data-pin=\"502,126\" data-offset=\"8\" shape=\"poly\" coords=\"505,126,505,127,506,126,507,127,506,127,507,128,507,129,504,127,504,128,503,127,501,127,502,126,501,125,501,126,500,125,500,123,501,123,501,119,504,119,504,122,502,124,503,127,505,126\" />\n";
  $output .= "            <area data-timezone=\"Asia/Muscat\" data-country=\"OM\" data-pin=\"398,111\" data-offset=\"4\" shape=\"poly\" coords=\"394,120,392,120,392,122,389,122,387,118,392,117,393,113,392,112,392,110,393,110,393,108,395,110,398,111,400,113,398,116,396,116,396,118,395,119,394,120\" />\n";
  $output .= "            <area data-timezone=\"Asia/Nicosia\" data-country=\"CY\" data-pin=\"356,91\" data-offset=\"2\" shape=\"poly\" coords=\"355,92,354,92,358,90,357,91,357,92,355,92\" />\n";
  $output .= "            <area data-timezone=\"Asia/Novokuznetsk\" data-country=\"RU\" data-pin=\"445,60\" data-offset=\"7\" shape=\"poly\" coords=\"448,55,449,57,447,58,448,59,447,59,449,59,449,60,448,61,449,62,447,63,445,62,444,61,445,61,442,59,441,57,448,55\" />\n";
  $output .= "            <area data-timezone=\"Asia/Novosibirsk\" data-country=\"RU\" data-pin=\"438,58\" data-offset=\"7\" shape=\"poly\" coords=\"441,57,442,59,441,60,439,60,437,61,435,59,430,61,427,60,428,59,426,60,426,59,425,57,427,56,426,56,427,55,425,52,428,51,429,49,437,49,439,48,441,49,441,50,445,50,448,52,447,52,449,54,448,55,441,57\" />\n";
  $output .= "            <area data-timezone=\"Asia/Omsk\" data-country=\"RU\" data-pin=\"422,58\" data-offset=\"7\" shape=\"poly\" coords=\"425,52,427,55,426,56,427,56,425,57,426,59,426,60,422,61,423,60,419,60,418,58,417,58,418,56,419,55,417,54,418,52,419,53,421,53,425,52\" />\n";
  $output .= "            <area data-timezone=\"Asia/Omsk\" data-country=\"RU\" data-pin=\"422,58\" data-offset=\"7\" shape=\"poly\" coords=\"447,63,446,64,448,64,450,66,449,66,450,67,446,68,445,67,442,67,439,65,436,65,434,64,433,65,430,61,435,59,437,61,439,60,442,59,445,61,444,61,445,62,447,63\" />\n";
  $output .= "            <area data-timezone=\"Asia/Phnom_Penh\" data-country=\"KH\" data-pin=\"475,131\" data-offset=\"7\" shape=\"poly\" coords=\"476,131,477,132,473,133,473,131,472,132,471,127,473,126,477,127,477,126,479,126,479,129,476,131\" />\n";
  $output .= "            <area data-timezone=\"Asia/Pontianak\" data-country=\"ID\" data-pin=\"482,150\" data-offset=\"7\" shape=\"poly\" coords=\"493,152,491,156,488,155,486,156,486,155,484,155,483,152,482,151,482,147,483,147,484,149,486,148,487,147,490,148,490,149,492,149,491,150,492,150,493,152\" />\n";
  $output .= "            <area data-timezone=\"Asia/Pyongyang\" data-country=\"KP\" data-pin=\"510,85\" data-offset=\"9\" shape=\"poly\" coords=\"512,86,509,87,508,86,509,86,509,85,509,84,508,84,507,83,510,82,511,80,514,81,513,80,515,80,517,78,518,80,516,80,516,82,512,84,514,86,512,86\" />\n";
  $output .= "            <area data-timezone=\"Asia/Qatar\" data-country=\"QA\" data-pin=\"386,108\" data-offset=\"3\" shape=\"poly\" coords=\"386,108,385,109,385,108,385,106,386,108\" />\n";
  $output .= "            <area data-timezone=\"Asia/Qyzylorda\" data-country=\"KZ\" data-pin=\"409,75\" data-offset=\"6\" shape=\"poly\" coords=\"413,63,413,65,414,66,410,68,408,68,407,70,404,68,405,67,404,65,400,64,402,63,401,62,402,62,404,62,402,61,403,61,402,61,402,60,410,59,411,61,410,63,413,63\" />\n";
  $output .= "            <area data-timezone=\"Asia/Qyzylorda\" data-country=\"KZ\" data-pin=\"409,75\" data-offset=\"6\" shape=\"poly\" coords=\"410,78,409,77,403,77,401,76,401,74,402,72,400,72,400,73,401,73,399,73,399,74,398,74,402,70,409,73,412,73,412,76,413,77,413,78,410,79,410,78\" />\n";
  $output .= "            <area data-timezone=\"Asia/Rangoon\" data-country=\"MM\" data-pin=\"300,150\" data-offset=\"6.5\" shape=\"poly\" coords=\"464,125,465,127,466,130,464,133,465,129,463,127,463,122,462,123,461,121,461,122,460,122,459,124,459,123,458,124,458,123,458,123,458,122,457,123,458,121,457,118,457,119,456,118,457,118,456,118,457,118,456,117,455,116,455,116,454,115,455,113,456,110,457,110,459,106,461,104,462,105,461,104,463,102,464,104,465,106,465,107,463,109,463,110,465,110,465,111,466,112,465,113,467,113,467,114,469,114,467,116,463,117,463,119,462,119,465,123,464,125\" />\n";
  $output .= "            <area data-timezone=\"Asia/Riyadh\" data-country=\"SA\" data-pin=\"378,109\" data-offset=\"3\" shape=\"poly\" coords=\"372,121,371,123,368,117,365,115,364,110,362,109,359,103,358,103,358,101,360,101,363,99,362,98,365,96,367,97,375,101,381,102,381,104,384,106,383,107,385,109,386,109,386,110,388,112,392,112,393,113,392,117,381,120,377,124,377,122,374,121,372,121,372,121\" />\n";
  $output .= "            <area data-timezone=\"Asia/Sakhalin\" data-country=\"RU\" data-pin=\"538,72\" data-offset=\"11\" shape=\"poly\" coords=\"540,67,541,69,540,68,538,68,538,70,539,73,539,73,538,72,537,74,536,69,537,65,536,63,536,61,538,60,537,60,538,60,539,62,538,64,540,67\" />\n";
  $output .= "            <area data-timezone=\"Asia/Samarkand\" data-country=\"UZ\" data-pin=\"411,84\" data-offset=\"5\" shape=\"poly\" coords=\"414,85,414,86,413,88,411,88,411,87,404,83,403,81,401,81,400,80,398,79,397,79,398,80,397,79,395,80,395,81,393,81,393,75,398,74,397,76,399,77,401,76,403,78,409,77,410,78,410,80,411,80,411,81,411,83,412,83,412,85,414,85\" />\n";
  $output .= "            <area data-timezone=\"Asia/Seoul\" data-country=\"KR\" data-pin=\"512,87\" data-offset=\"9\" shape=\"poly\" coords=\"515,91,513,92,512,93,512,92,511,93,510,92,512,90,510,89,512,88,511,87,512,86,514,86,515,87,516,90,515,91\" />\n";
  $output .= "            <area data-timezone=\"Asia/Shanghai\" data-country=\"CN\" data-pin=\"502,98\" data-offset=\"8\" shape=\"poly\" coords=\"481,100,481,99,484,98,482,96,484,95,482,95,485,94,484,92,485,87,484,86,485,84,487,83,490,82,490,81,491,80,489,80,487,79,485,78,487,77,486,76,486,75,487,75,489,75,497,72,499,73,500,71,497,70,493,71,493,70,495,67,496,67,499,66,499,66,501,63,500,62,501,61,507,61,504,63,505,64,509,64,510,65,508,70,507,69,504,71,506,72,505,72,505,74,503,74,504,75,504,76,505,76,506,78,507,79,508,78,509,82,502,85,503,84,502,84,502,84,504,83,502,82,498,85,496,85,496,86,497,86,498,86,498,88,499,88,501,87,505,88,504,89,500,90,499,92,500,93,501,96,503,97,500,97,503,98,500,100,504,100,502,101,503,101,502,101,503,103,501,103,500,106,499,105,500,106,499,108,499,107,498,109,497,109,496,110,494,111,494,112,491,112,491,113,490,113,489,112,489,113,489,113,487,112,487,111,486,111,487,109,486,108,485,108,485,106,483,107,482,106,482,105,481,105,482,104,482,102,481,100\" />\n";
  $output .= "            <area data-timezone=\"Asia/Singapore\" data-country=\"SG\" data-pin=\"473,148\" data-offset=\"8\" shape=\"poly\" coords=\"473,148,473,148,473,148\" />\n";
  $output .= "            <area data-timezone=\"Asia/Taipei\" data-country=\"TW\" data-pin=\"503,108\" data-offset=\"8\" shape=\"poly\" coords=\"500,112,500,111,501,109,503,108,503,108,501,113,500,112\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tashkent\" data-country=\"UZ\" data-pin=\"416,81\" data-offset=\"5\" shape=\"poly\" coords=\"414,83,415,83,414,84,412,84,411,81,414,82,418,80,417,81,419,81,420,81,422,82,420,83,418,83,417,82,414,83\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tbilisi\" data-country=\"GE\" data-pin=\"375,80\" data-offset=\"4\" shape=\"poly\" coords=\"371,81,369,81,369,79,367,77,371,78,373,79,375,79,378,81,372,81,371,81\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tehran\" data-country=\"IR\" data-pin=\"386,91\" data-offset=\"3.5\" shape=\"poly\" coords=\"402,98,403,98,401,100,403,102,405,103,405,105,406,105,405,106,403,106,402,108,396,107,394,105,391,106,390,105,386,103,383,100,383,100,381,99,382,100,381,100,379,98,380,97,379,96,377,95,376,93,377,90,376,90,374,87,374,86,373,84,374,84,377,85,380,84,381,84,380,85,381,86,382,87,386,89,390,89,390,88,394,86,399,87,401,89,402,89,402,92,401,93,402,94,401,95,402,98\" />\n";
  $output .= "            <area data-timezone=\"Asia/Thimphu\" data-country=\"BT\" data-pin=\"449,104\" data-offset=\"6\" shape=\"poly\" coords=\"453,105,453,105,450,105,448,105,450,103,453,103,453,105\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tokyo\" data-country=\"JP\" data-pin=\"533,91\" data-offset=\"9\" shape=\"poly\" coords=\"538,75,541,77,542,76,542,78,543,78,540,79,539,80,536,79,534,79,534,80,535,80,533,81,533,79,534,78,534,78,536,78,536,74,538,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tokyo\" data-country=\"JP\" data-pin=\"533,91\" data-offset=\"9\" shape=\"poly\" coords=\"518,93,519,94,519,95,520,95,519,98,518,98,518,97,518,98,517,98,518,95,517,95,517,96,516,96,516,95,517,95,516,95,518,93\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tokyo\" data-country=\"JP\" data-pin=\"533,91\" data-offset=\"9\" shape=\"poly\" coords=\"524,91,527,91,528,88,529,88,528,88,529,89,531,88,533,85,533,82,534,81,535,82,535,81,536,81,537,84,536,86,535,87,534,90,535,90,534,91,533,92,534,91,533,91,531,92,531,91,530,92,528,92,529,92,528,92,528,93,526,94,525,94,526,92,521,93,520,94,518,93,518,93,521,91,524,91\" />\n";
  $output .= "            <area data-timezone=\"Asia/Ulaanbaatar\" data-country=\"MN\" data-pin=\"478,70\" data-offset=\"8\" shape=\"poly\" coords=\"475,81,468,79,463,79,463,77,464,74,463,72,464,71,463,70,465,69,465,69,462,67,464,66,463,65,465,63,470,64,471,66,472,66,478,66,481,68,483,68,487,67,488,70,486,71,486,73,487,75,486,76,487,77,484,79,479,79,475,81\" />\n";
  $output .= "            <area data-timezone=\"Asia/Urumqi\" data-country=\"CN\" data-pin=\"446,77\" data-offset=\"6\" shape=\"poly\" coords=\"464,103,460,103,461,102,460,101,457,101,453,104,450,103,448,105,448,103,443,103,437,100,437,99,438,98,438,99,439,98,438,97,439,97,439,95,437,93,437,92,437,91,438,90,437,88,438,84,437,84,436,82,438,80,438,78,439,78,438,77,442,78,436,76,438,75,437,74,438,71,443,72,443,69,446,68,447,69,450,70,452,72,451,75,459,76,461,79,462,79,464,82,462,82,461,84,463,84,462,85,466,87,466,87,466,87,465,90,465,92,465,92,465,93,464,94,462,94,463,94,462,95,464,96,465,99,465,101,464,102,465,103,464,103\" />\n";
  $output .= "            <area data-timezone=\"Asia/Vientiane\" data-country=\"LA\" data-pin=\"471,120\" data-offset=\"7\" shape=\"poly\" coords=\"477,126,477,127,475,126,476,124,473,119,472,119,471,120,470,120,468,121,469,117,468,117,468,116,467,116,469,114,470,115,469,113,471,114,472,115,474,116,474,116,475,117,473,118,475,119,478,123,479,123,479,126,477,126\" />\n";
  $output .= "            <area data-timezone=\"Asia/Vladivostok\" data-country=\"RU\" data-pin=\"520,78\" data-offset=\"11\" shape=\"poly\" coords=\"519,75,520,74,522,75,525,71,525,70,518,71,518,69,519,68,519,67,518,65,519,64,522,63,522,62,524,63,525,61,524,61,520,61,519,61,519,60,517,60,522,58,518,57,519,56,520,55,518,55,520,54,519,53,520,53,521,52,520,51,520,51,522,49,522,47,523,47,525,48,526,46,520,44,521,43,521,42,519,40,517,40,522,37,522,36,520,35,523,34,522,33,522,33,520,33,520,31,522,30,522,30,521,30,523,31,533,31,532,30,534,30,532,30,536,29,535,29,546,29,539,30,535,33,537,34,537,34,538,35,536,35,537,36,535,36,535,37,532,38,534,39,533,40,534,41,532,42,534,42,533,43,534,44,533,44,533,45,537,47,544,47,545,48,543,49,545,50,545,51,537,51,525,59,528,59,528,60,530,59,529,61,531,60,531,61,531,60,533,60,536,61,535,61,536,63,534,65,534,68,529,74,523,79,522,79,520,78,518,80,517,79,519,78,518,75,519,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Vladivostok\" data-country=\"RU\" data-pin=\"520,78\" data-offset=\"11\" shape=\"poly\" coords=\"532,23,534,24,536,23,536,23,542,24,539,25,533,25,532,26,528,25,529,23,532,23\" />\n";
  $output .= "            <area data-timezone=\"Asia/Yakutsk\" data-country=\"RU\" data-pin=\"516,47\" data-offset=\"10\" shape=\"poly\" coords=\"518,57,522,58,518,60,519,60,519,61,524,61,525,61,524,63,522,62,522,63,519,64,518,66,519,66,519,68,518,68,513,67,510,62,506,61,501,61,500,62,501,62,501,63,499,66,499,67,496,67,491,66,485,68,480,67,480,66,481,66,480,65,481,64,484,64,487,63,489,63,491,62,490,61,495,59,495,58,493,58,492,56,496,55,496,55,495,54,496,53,499,53,498,51,495,51,495,50,493,49,488,52,487,51,484,52,482,51,484,48,483,48,483,47,483,46,482,46,483,45,482,44,480,44,481,44,481,43,476,42,478,41,477,41,477,39,476,38,478,38,478,35,477,34,482,34,484,32,488,31,487,31,487,30,485,29,484,29,485,28,484,28,485,27,488,27,489,28,493,27,505,29,506,28,506,27,507,27,516,28,515,28,516,29,515,29,516,30,514,30,516,31,515,31,518,32,520,32,520,33,522,33,522,33,523,34,520,35,522,36,522,37,517,40,519,40,521,42,521,43,520,44,526,46,525,48,523,47,522,47,522,49,520,51,520,51,521,52,520,53,519,53,520,54,518,55,520,55,519,56,518,57\" />\n";
  $output .= "            <area data-timezone=\"Asia/Yerevan\" data-country=\"AM\" data-pin=\"374,83\" data-offset=\"4\" shape=\"poly\" coords=\"375,81,377,83,376,83,378,84,378,85,377,85,376,84,373,83,372,81,375,81\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Azores\" data-country=\"PT\" data-pin=\"257,87\" data-offset=\"-1\" shape=\"rect\" coords=\"248,93,258,79\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Bermuda\" data-country=\"BM\" data-pin=\"192,96\" data-offset=\"-4\" shape=\"rect\" coords=\"187,101,197,91\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Canary\" data-country=\"ES\" data-pin=\"274,103\" data-offset=\"0\" shape=\"rect\" coords=\"265,109,283,96\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Cape_Verde\" data-country=\"CV\" data-pin=\"261,125\" data-offset=\"-1\" shape=\"rect\" coords=\"253,130,267,116\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Faroe\" data-country=\"FO\" data-pin=\"289,47\" data-offset=\"0\" shape=\"rect\" coords=\"282,53,295,41\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Madeira\" data-country=\"PT\" data-pin=\"272,96\" data-offset=\"0\" shape=\"rect\" coords=\"266,105,279,90\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Reykjavik\" data-country=\"IS\" data-pin=\"264,43\" data-offset=\"0\" shape=\"rect\" coords=\"261,50,277,34\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/South_Georgia\" data-country=\"GS\" data-pin=\"239,240\" data-offset=\"-2\" shape=\"rect\" coords=\"230,249,256,239\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/St_Helena\" data-country=\"SH\" data-pin=\"291,177\" data-offset=\"0\" shape=\"rect\" coords=\"276,217,291,163\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Stanley\" data-country=\"FK\" data-pin=\"204,236\" data-offset=\"-3\" shape=\"rect\" coords=\"193,243,209,230\" />\n";
  $output .= "            <area data-timezone=\"Australia/Adelaide\" data-country=\"AU\" data-pin=\"531,208\" data-offset=\"10.5\" shape=\"poly\" coords=\"525,193,535,193,535,213,533,212,533,211,531,209,533,210,532,210,530,209,531,208,530,207,530,209,528,209,529,208,530,206,530,204,530,205,527,208,527,208,525,208,526,208,525,207,523,204,519,202,515,203,515,193,525,193\" />\n";
  $output .= "            <area data-timezone=\"Australia/Brisbane\" data-country=\"AU\" data-pin=\"555,196\" data-offset=\"10\" shape=\"poly\" coords=\"550,187,551,188,551,189,551,189,553,190,555,193,555,195,556,197,554,197,552,199,550,198,548,198,535,198,535,193,530,193,530,178,533,180,535,179,536,175,536,173,537,171,536,171,537,168,539,170,540,174,541,174,542,175,544,181,548,184,549,188,550,188,550,187\" />\n";
  $output .= "            <area data-timezone=\"Australia/Broken_Hill\" data-country=\"AU\" data-pin=\"536,203\" data-offset=\"10.5\" shape=\"poly\" coords=\"535,204,535,202,537,203,536,204,535,204\" />\n";
  $output .= "            <area data-timezone=\"Australia/Currie\" data-country=\"AU\" data-pin=\"540,217\" data-offset=\"11\" shape=\"poly\" coords=\"540,216,540,216,540,216\" />\n";
  $output .= "            <area data-timezone=\"Australia/Darwin\" data-country=\"AU\" data-pin=\"518,171\" data-offset=\"9.5\" shape=\"poly\" coords=\"515,175,516,175,516,174,516,174,517,171,518,171,518,171,518,171,519,170,521,170,521,169,520,169,524,170,525,171,527,170,526,170,527,170,527,171,528,170,528,171,527,172,527,172,526,174,525,175,530,178,530,193,515,193,515,175\" />\n";
  $output .= "            <area data-timezone=\"Australia/Eucla\" data-country=\"AU\" data-pin=\"515,203\" data-offset=\"8.8\" shape=\"poly\" coords=\"515,202,514,203,509,204,509,202,515,202\" />\n";
  $output .= "            <area data-timezone=\"Australia/Hobart\" data-country=\"AU\" data-pin=\"546,221\" data-offset=\"11\" shape=\"poly\" coords=\"547,218,547,222,545,221,545,223,542,222,542,220,543,221,541,218,542,218,545,219,545,218,547,218\" />\n";
  $output .= "            <area data-timezone=\"Australia/Lindeman\" data-country=\"AU\" data-pin=\"548,184\" data-offset=\"10\" shape=\"poly\" coords=\"548,183,548,183,548,183\" />\n";
  $output .= "            <area data-timezone=\"Australia/Lord_Howe\" data-country=\"AU\" data-pin=\"565,203\" data-offset=\"11\" shape=\"poly\" coords=\"565,203,565,203,565,203\" />\n";
  $output .= "            <area data-timezone=\"Australia/Melbourne\" data-country=\"AU\" data-pin=\"542,213\" data-offset=\"11\" shape=\"poly\" coords=\"538,208,539,208,541,210,547,210,547,211,550,213,546,213,544,214,544,215,542,214,541,214,542,214,542,213,539,215,536,214,535,213,535,207,537,207,538,208\" />\n";
  $output .= "            <area data-timezone=\"Australia/Perth\" data-country=\"AU\" data-pin=\"493,203\" data-offset=\"8\" shape=\"poly\" coords=\"509,175,509,175,509,174,510,174,510,173,511,174,512,173,514,175,513,176,514,175,514,176,514,175,515,175,515,202,509,202,509,204,507,205,506,207,500,207,497,209,493,208,492,207,492,206,493,205,493,203,491,199,489,194,490,194,489,193,490,194,490,194,489,191,490,187,490,186,491,187,491,186,495,184,496,185,502,183,504,180,504,179,505,177,506,179,507,178,506,177,508,177,507,177,508,176,507,177,507,176,508,175,509,176,508,175,509,175\" />\n";
  $output .= "            <area data-timezone=\"Australia/Sydney\" data-country=\"AU\" data-pin=\"552,206\" data-offset=\"11\" shape=\"poly\" coords=\"551,209,550,209,550,213,547,211,547,210,541,210,539,208,535,207,535,204,536,204,537,203,535,202,535,198,548,198,550,198,552,199,554,197,556,197,555,202,552,206,551,206,552,206,551,209\" />\n";
  $output .= "            <area data-timezone=\"Europe/Amsterdam\" data-country=\"NL\" data-pin=\"308,63\" data-offset=\"1\" shape=\"poly\" coords=\"310,64,310,65,308,64,306,64,308,64,307,63,308,63,310,63,309,62,309,61,312,61,311,62,312,63,311,63,310,64\" />\n";
  $output .= "            <area data-timezone=\"Europe/Andorra\" data-country=\"AD\" data-pin=\"303,79\" data-offset=\"1\" shape=\"poly\" coords=\"303,79,302,79,303,79\" />\n";
  $output .= "            <area data-timezone=\"Europe/Athens\" data-country=\"GR\" data-pin=\"340,87\" data-offset=\"2\" shape=\"poly\" coords=\"339,87,339,88,338,87,339,89,338,89,337,89,337,88,336,89,335,87,336,86,339,86,335,86,335,85,335,85,333,84,335,82,344,81,344,82,340,82,341,83,340,83,340,83,338,82,339,85,338,84,338,85,338,85,340,86,340,87,339,87\" />\n";
  $output .= "            <area data-timezone=\"Europe/Belgrade\" data-country=\"RS\" data-pin=\"334,75\" data-offset=\"1\" shape=\"poly\" coords=\"336,75,338,76,337,77,338,78,337,79,334,80,333,79,334,78,332,78,333,77,332,76,332,75,332,75,331,74,334,73,336,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Bucharest\" data-country=\"RO\" data-pin=\"344,76\" data-offset=\"2\" shape=\"poly\" coords=\"347,74,349,75,348,77,345,76,340,77,338,77,337,76,338,76,336,75,336,75,334,73,335,73,337,71,339,70,342,70,345,70,347,72,347,74\" />\n";
  $output .= "            <area data-timezone=\"Europe/Berlin\" data-country=\"DE\" data-pin=\"322,63\" data-offset=\"1\" shape=\"poly\" coords=\"312,68,311,68,310,64,312,63,311,62,312,61,314,61,314,60,316,61,315,60,314,59,315,59,314,59,314,58,317,59,317,59,319,59,318,60,321,59,324,60,324,62,324,62,325,65,320,66,323,69,321,70,322,71,313,71,314,68,312,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Budapest\" data-country=\"HU\" data-pin=\"332,71\" data-offset=\"1\" shape=\"poly\" coords=\"335,73,330,74,327,72,328,72,327,71,329,70,331,70,335,69,338,70,335,73\" />\n";
  $output .= "            <area data-timezone=\"Europe/Chisinau\" data-country=\"MD\" data-pin=\"348,72\" data-offset=\"2\" shape=\"poly\" coords=\"350,73,348,73,348,73,347,74,347,72,344,70,346,69,349,70,350,73\" />\n";
  $output .= "            <area data-timezone=\"Europe/Bratislava\" data-country=\"SK\" data-pin=\"329,70\" data-offset=\"1\" shape=\"poly\" coords=\"328,70,328,69,331,68,338,68,337,69,334,69,331,70,328,70\" />\n";
  $output .= "            <area data-timezone=\"Europe/Brussels\" data-country=\"BE\" data-pin=\"307,65\" data-offset=\"1\" shape=\"poly\" coords=\"305,65,304,65,308,64,311,66,309,68,308,66,307,67,305,65\" />\n";
  $output .= "            <area data-timezone=\"Europe/Copenhagen\" data-country=\"DK\" data-pin=\"321,57\" data-offset=\"1\" shape=\"poly\" coords=\"316,58,316,59,314,58,314,58,313,57,314,56,318,54,317,55,318,56,316,58\" />\n";
  $output .= "            <area data-timezone=\"Europe/Dublin\" data-country=\"IE\" data-pin=\"290,61\" data-offset=\"0\" shape=\"poly\" coords=\"289,60,290,60,289,63,284,64,283,64,284,64,283,64,284,62,286,62,283,62,285,61,283,61,284,60,283,60,286,60,286,59,285,59,288,58,288,58,286,59,289,60\" />\n";
  $output .= "            <area data-timezone=\"Europe/Gibraltar\" data-country=\"GI\" data-pin=\"291,90\" data-offset=\"1\" shape=\"poly\" coords=\"291,90,291,90,291,90\" />\n";
  $output .= "            <area data-timezone=\"Europe/Guernsey\" data-country=\"GG\" data-pin=\"296,68\" data-offset=\"0\" shape=\"poly\" coords=\"296,68,296,68,296,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Helsinki\" data-country=\"FI\" data-pin=\"342,50\" data-offset=\"2\" shape=\"poly\" coords=\"344,49,338,50,338,49,336,49,336,47,335,46,336,45,337,45,338,44,342,42,342,41,339,40,340,39,339,38,339,37,334,35,336,35,337,35,342,36,344,33,347,33,349,34,347,36,350,37,348,38,350,40,349,42,351,43,350,44,353,45,346,49,344,49\" />\n";
  $output .= "            <area data-timezone=\"Europe/Isle_of_Man\" data-country=\"IM\" data-pin=\"293,60\" data-offset=\"0\" shape=\"poly\" coords=\"293,59,292,60,293,59\" />\n";
  $output .= "            <area data-timezone=\"Europe/Istanbul\" data-country=\"TR\" data-pin=\"348,82\" data-offset=\"2\" shape=\"poly\" coords=\"361,89,360,90,360,90,360,88,355,90,352,89,349,90,347,89,346,89,347,88,345,88,346,88,345,87,345,87,344,86,344,86,345,86,345,86,345,84,343,84,344,83,345,83,350,82,349,81,352,81,354,80,358,80,361,81,364,82,371,81,373,82,373,83,375,84,373,84,374,86,374,87,375,88,374,88,371,88,365,89,361,89\" />\n";
  $output .= "            <area data-timezone=\"Europe/Jersey\" data-country=\"JE\" data-pin=\"296,68\" data-offset=\"0\" shape=\"poly\" coords=\"297,68,296,68,297,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Kaliningrad\" data-country=\"RU\" data-pin=\"334,59\" data-offset=\"3\" shape=\"poly\" coords=\"335,58,334,58,338,58,338,59,333,59,334,59,333,59,333,58,335,58\" />\n";
  $output .= "            <area data-timezone=\"Europe/Kiev\" data-country=\"UA\" data-pin=\"351,66\" data-offset=\"2\" shape=\"poly\" coords=\"365,70,364,72,362,72,362,71,361,70,358,70,358,71,357,71,359,73,358,73,354,73,353,72,354,73,353,72,349,74,350,75,348,75,347,74,348,73,350,73,349,70,346,69,341,70,338,68,338,67,340,66,339,64,341,63,351,65,352,63,356,63,357,64,357,64,357,65,359,65,359,66,362,66,363,67,367,67,366,68,367,69,366,69,366,70,365,70\" />\n";
  $output .= "            <area data-timezone=\"Europe/Lisbon\" data-country=\"PT\" data-pin=\"285,85\" data-offset=\"0\" shape=\"poly\" coords=\"287,87,287,88,285,88,286,86,285,86,285,85,284,85,286,82,285,80,286,80,286,80,289,80,290,81,288,82,288,84,287,84,288,86,287,87\" />\n";
  $output .= "            <area data-timezone=\"Europe/Ljubljana\" data-country=\"SI\" data-pin=\"324,73\" data-offset=\"1\" shape=\"poly\" coords=\"327,72,328,73,326,73,326,74,323,74,323,74,322,73,323,72,327,72\" />\n";
  $output .= "            <area data-timezone=\"Europe/London\" data-country=\"GB\" data-pin=\"300,64\" data-offset=\"0\" shape=\"poly\" coords=\"296,64,294,64,291,64,293,62,293,62,292,62,293,61,295,61,295,61,295,60,294,59,295,58,291,58,292,58,292,56,291,57,290,58,291,56,292,55,290,55,291,55,290,54,292,54,291,53,292,53,292,52,295,52,293,53,294,54,293,54,297,54,296,56,294,56,296,56,294,56,296,57,298,59,300,60,300,61,299,60,300,61,300,62,303,62,303,63,301,64,302,64,302,65,294,66,294,66,290,67,293,65,295,65,296,64,296,64\" />\n";
  $output .= "            <area data-timezone=\"Europe/Luxembourg\" data-country=\"LU\" data-pin=\"310,67\" data-offset=\"1\" shape=\"poly\" coords=\"310,67,310,67,310,66,311,67,310,67\" />\n";
  $output .= "            <area data-timezone=\"Europe/Madrid\" data-country=\"ES\" data-pin=\"294,83\" data-offset=\"1\" shape=\"poly\" coords=\"299,87,296,89,293,89,290,90,289,89,290,88,289,89,287,87,288,86,287,84,288,84,288,82,290,81,289,80,286,80,286,80,285,80,286,79,285,78,287,77,297,78,299,79,306,79,305,80,301,82,301,82,299,84,300,85,299,87\" />\n";
  $output .= "            <area data-timezone=\"Europe/Malta\" data-country=\"MT\" data-pin=\"324,90\" data-offset=\"1\" shape=\"poly\" coords=\"324,90,324,90,324,90\" />\n";
  $output .= "            <area data-timezone=\"Europe/Mariehamn\" data-country=\"AX\" data-pin=\"333,50\" data-offset=\"2\" shape=\"poly\" coords=\"335,49,335,49,335,49\" />\n";
  $output .= "            <area data-timezone=\"Europe/Minsk\" data-country=\"BY\" data-pin=\"346,60\" data-offset=\"3\" shape=\"poly\" coords=\"350,64,342,63,339,64,339,63,339,63,340,62,339,60,343,60,343,59,345,58,344,58,344,57,347,56,351,57,352,57,351,59,355,61,352,61,353,63,352,63,351,65,350,64\" />\n";
  $output .= "            <area data-timezone=\"Europe/Monaco\" data-country=\"MC\" data-pin=\"312,77\" data-offset=\"1\" shape=\"poly\" coords=\"312,77,312,77,312,77\" />\n";
  $output .= "            <area data-timezone=\"Europe/Moscow\" data-country=\"RU\" data-pin=\"363,57\" data-offset=\"4\" shape=\"poly\" coords=\"391,28,394,28,392,29,393,29,392,29,393,30,392,30,396,32,389,32,390,31,386,30,389,29,388,29,389,29,388,29,391,28\" />\n";
  $output .= "            <area data-timezone=\"Europe/Moscow\" data-country=\"RU\" data-pin=\"363,57\" data-offset=\"4\" shape=\"poly\" coords=\"400,23,413,22,415,22,414,23,402,24,399,25,400,26,398,25,399,26,397,26,398,26,395,27,396,27,394,27,396,27,394,27,395,28,395,28,390,28,393,27,389,27,394,27,392,26,395,26,393,25,395,25,393,25,400,23\" />\n";
  $output .= "            <area data-timezone=\"Europe/Moscow\" data-country=\"RU\" data-pin=\"363,57\" data-offset=\"4\" shape=\"poly\" coords=\"378,75,378,76,380,77,379,78,381,80,380,81,375,79,373,79,371,78,367,78,361,75,364,73,363,72,366,72,364,71,364,71,366,70,367,70,366,69,367,69,366,68,367,67,363,67,362,66,359,66,359,65,357,65,357,64,357,64,356,63,353,63,352,62,355,61,351,59,352,57,347,56,346,54,346,54,346,54,346,52,347,51,347,50,350,50,348,49,348,49,346,49,353,45,350,44,351,43,349,42,350,40,348,38,350,37,348,36,348,35,347,35,353,33,355,34,354,34,354,34,354,34,356,34,355,35,360,35,368,37,369,39,364,40,353,38,356,39,355,40,358,40,357,41,358,42,358,42,362,44,363,43,361,42,368,42,366,41,370,39,374,40,374,38,373,38,374,37,372,36,377,36,378,37,375,38,378,39,379,39,380,37,382,37,381,37,387,36,390,35,391,35,389,35,390,36,389,36,398,35,399,35,399,36,400,36,402,35,400,34,401,34,408,34,408,35,409,36,409,37,410,37,410,37,410,38,399,42,399,47,394,47,392,49,387,49,387,50,383,50,383,51,381,50,381,49,380,48,379,48,377,50,378,50,378,51,379,52,377,53,380,54,378,54,378,55,382,54,386,56,386,57,388,57,388,56,388,56,389,56,389,57,390,57,389,58,389,59,389,60,386,59,383,59,383,60,380,61,381,62,372,63,371,64,372,65,369,65,370,67,370,68,371,69,370,70,372,71,374,70,374,70,378,71,378,71,379,72,377,73,379,73,378,74,379,74,378,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Oslo\" data-country=\"NO\" data-pin=\"318,50\" data-offset=\"1\" shape=\"poly\" coords=\"314,53,311,53,312,53,309,52,309,52,311,52,310,52,311,51,309,51,312,49,310,50,310,49,309,49,310,49,309,49,308,49,309,49,308,48,312,49,313,48,309,48,308,48,309,48,308,48,309,48,308,47,311,47,308,46,311,47,310,46,311,46,312,46,310,46,314,46,312,45,312,45,314,46,313,45,314,45,314,45,319,44,318,44,319,43,317,44,316,44,320,42,319,42,322,41,320,41,321,41,321,41,321,40,322,40,321,40,324,39,322,40,323,39,322,39,323,39,322,39,323,39,322,38,326,38,324,38,326,37,325,37,326,37,325,37,327,36,328,37,327,37,328,37,327,36,330,36,327,36,329,36,329,35,330,35,329,35,330,34,333,35,332,34,333,34,333,34,334,33,333,35,335,34,334,34,335,33,337,34,336,33,337,33,335,33,336,33,339,33,339,33,341,32,340,32,341,32,343,32,342,32,342,33,344,32,344,33,345,33,346,31,348,32,346,32,347,32,346,33,348,32,352,33,348,33,349,33,349,34,352,34,348,35,349,34,346,33,343,34,343,35,342,36,337,35,335,34,333,35,334,35,333,36,331,36,330,37,328,37,326,40,324,40,324,41,323,42,324,43,323,43,320,44,320,47,321,48,320,48,321,49,320,50,319,52,318,51,318,50,317,52,316,51,314,53\" />\n";
  $output .= "            <area data-timezone=\"Europe/Paris\" data-country=\"FR\" data-pin=\"304,69\" data-offset=\"1\" shape=\"poly\" coords=\"307,78,305,78,305,79,304,79,298,78,297,78,298,76,298,74,299,75,298,74,298,73,297,73,297,71,296,71,293,70,292,70,293,70,292,69,295,69,296,69,298,69,297,67,298,68,301,68,300,67,303,66,303,65,304,65,307,67,308,66,309,68,314,68,313,71,312,71,310,73,311,73,312,74,311,75,312,76,313,76,313,77,310,78,307,78\" />\n";
  $output .= "            <area data-timezone=\"Europe/Podgorica\" data-country=\"ME\" data-pin=\"332,79\" data-offset=\"1\" shape=\"poly\" coords=\"332,78,334,78,333,79,332,80,331,79,332,78\" />\n";
  $output .= "            <area data-timezone=\"Europe/Prague\" data-country=\"CZ\" data-pin=\"324,67\" data-offset=\"1\" shape=\"poly\" coords=\"325,68,323,69,321,68,320,66,325,65,327,66,328,66,329,66,331,67,328,69,325,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Riga\" data-country=\"LV\" data-pin=\"340,55\" data-offset=\"2\" shape=\"poly\" coords=\"337,54,340,55,341,55,341,54,342,53,346,54,347,56,344,57,341,56,335,57,335,55,337,54\" />\n";
  $output .= "            <area data-timezone=\"Europe/Rome\" data-country=\"IT\" data-pin=\"321,80\" data-offset=\"1\" shape=\"poly\" coords=\"328,83,328,84,329,85,326,87,327,85,326,83,319,79,317,77,315,76,313,77,313,76,312,76,311,75,312,74,311,74,314,73,315,74,315,72,317,73,317,72,320,72,323,72,322,73,323,74,320,74,321,75,321,76,323,77,325,80,327,80,327,81,331,83,331,84,328,82,328,83\" />\n";
  $output .= "            <area data-timezone=\"Europe/Samara\" data-country=\"RU\" data-pin=\"384,61\" data-offset=\"4\" shape=\"poly\" coords=\"383,59,388,59,387,60,387,62,385,64,381,62,381,62,380,61,383,60,383,59\" />\n";
  $output .= "            <area data-timezone=\"Europe/Samara\" data-country=\"RU\" data-pin=\"384,61\" data-offset=\"4\" shape=\"poly\" coords=\"391,56,389,57,389,56,388,56,388,56,388,57,386,57,385,56,386,55,385,54,386,54,387,53,390,53,391,55,390,56,391,56\" />\n";
  $output .= "            <area data-timezone=\"Europe/San_Marino\" data-country=\"SM\" data-pin=\"321,77\" data-offset=\"1\" shape=\"poly\" coords=\"321,77,321,77,321,77\" />\n";
  $output .= "            <area data-timezone=\"Europe/Sarajevo\" data-country=\"BA\" data-pin=\"331,77\" data-offset=\"1\" shape=\"poly\" coords=\"326,75,332,75,332,76,333,77,332,77,333,77,332,77,331,79,329,78,326,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Simferopol\" data-country=\"RU\" data-pin=\"357,75\" data-offset=\"2\" shape=\"poly\" coords=\"358,73,359,75,361,74,356,76,355,74,355,74,354,74,356,74,356,73,358,73\" />\n";
  $output .= "            <area data-timezone=\"Europe/Skopje\" data-country=\"MK\" data-pin=\"336,80\" data-offset=\"1\" shape=\"poly\" coords=\"337,79,338,80,338,81,335,82,334,81,335,80,337,79\" />\n";
  $output .= "            <area data-timezone=\"Europe/Sofia\" data-country=\"BG\" data-pin=\"339,79\" data-offset=\"2\" shape=\"poly\" coords=\"346,79,346,79,347,80,344,80,344,81,338,81,337,79,338,78,337,77,338,76,338,77,342,77,345,76,348,77,346,79\" />\n";
  $output .= "            <area data-timezone=\"Europe/Stockholm\" data-country=\"SE\" data-pin=\"330,51\" data-offset=\"1\" shape=\"poly\" coords=\"321,56,322,56,320,54,320,53,319,53,319,52,319,52,320,50,321,49,320,48,321,48,320,47,320,45,321,43,324,43,323,42,324,41,324,40,326,40,327,38,327,37,329,36,330,37,330,36,334,36,333,35,339,37,339,38,340,39,339,39,340,40,336,40,337,41,336,41,336,42,335,42,336,43,334,44,330,45,329,45,330,46,329,46,329,46,328,47,329,49,332,50,330,51,331,51,330,52,329,51,329,52,327,52,328,53,327,53,328,53,327,53,328,54,327,56,324,56,324,58,322,58,321,58,322,57,321,56\" />\n";
  $output .= "            <area data-timezone=\"Europe/Tallinn\" data-country=\"EE\" data-pin=\"341,51\" data-offset=\"2\" shape=\"poly\" coords=\"340,51,343,51,347,51,346,52,346,54,344,54,342,53,341,54,341,53,339,52,339,51,340,51\" />\n";
  $output .= "            <area data-timezone=\"Europe/Tirane\" data-country=\"AL\" data-pin=\"333,81\" data-offset=\"1\" shape=\"poly\" coords=\"334,84,332,83,333,81,332,80,333,79,334,80,334,81,335,82,334,84\" />\n";
  $output .= "            <area data-timezone=\"Europe/Uzhgorod\" data-country=\"UA\" data-pin=\"337,69\" data-offset=\"2\" shape=\"poly\" coords=\"338,68,341,70,338,70,337,69,338,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vaduz\" data-country=\"LI\" data-pin=\"316,71\" data-offset=\"1\" shape=\"poly\" coords=\"316,71,316,72,316,71\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vatican\" data-country=\"VA\" data-pin=\"321,80\" data-offset=\"1\" shape=\"poly\" coords=\"321,80,321,80,321,80\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vienna\" data-country=\"AT\" data-pin=\"327,70\" data-offset=\"1\" shape=\"poly\" coords=\"316,71,322,71,321,70,325,68,328,69,329,70,327,71,328,72,327,72,324,73,321,72,320,72,317,72,316,71\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vilnius\" data-country=\"LT\" data-pin=\"342,59\" data-offset=\"2\" shape=\"poly\" coords=\"335,57,341,56,345,58,343,59,343,60,339,60,338,59,338,58,335,58,335,57\" />\n";
  $output .= "            <area data-timezone=\"Europe/Volgograd\" data-country=\"RU\" data-pin=\"374,69\" data-offset=\"4\" shape=\"poly\" coords=\"378,69,379,70,381,71,382,72,381,72,382,73,381,74,381,73,381,74,378,74,379,73,377,73,379,72,378,71,378,71,377,71,376,70,374,70,374,70,373,71,370,70,371,69,370,68,370,67,369,65,372,65,371,64,372,63,380,62,385,63,384,64,381,66,381,67,379,66,378,69\" />\n";
  $output .= "            <area data-timezone=\"Europe/Volgograd\" data-country=\"RU\" data-pin=\"374,69\" data-offset=\"4\" shape=\"poly\" coords=\"381,49,381,49,381,50,383,51,383,50,385,50,389,50,389,51,390,52,390,53,386,53,386,54,385,54,386,55,386,57,382,54,378,55,378,54,380,54,377,53,379,52,378,51,378,50,377,50,379,48,381,49\" />\n";
  $output .= "            <area data-timezone=\"Europe/Warsaw\" data-country=\"PL\" data-pin=\"335,63\" data-offset=\"1\" shape=\"poly\" coords=\"339,66,338,67,338,68,336,68,332,68,329,66,328,66,327,66,325,65,324,62,324,62,324,60,330,59,333,59,332,60,338,59,340,62,339,63,340,65,339,66\" />\n";
  $output .= "            <area data-timezone=\"Europe/Zagreb\" data-country=\"HR\" data-pin=\"327,74\" data-offset=\"1\" shape=\"poly\" coords=\"332,75,326,75,329,78,327,77,324,75,323,75,322,74,326,74,326,73,327,72,330,74,332,73,332,75,332,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Zaporozhye\" data-country=\"UA\" data-pin=\"359,70\" data-offset=\"2\" shape=\"poly\" coords=\"357,71,358,71,358,70,359,70,362,71,361,72,358,73,359,73,357,71\" />\n";
  $output .= "            <area data-timezone=\"Europe/Zurich\" data-country=\"CH\" data-pin=\"314,71\" data-offset=\"1\" shape=\"poly\" coords=\"310,73,310,73,312,71,314,70,316,71,316,72,317,72,317,73,315,73,315,74,314,73,312,74,311,73,310,73\" />\n";
  $output .= "            <area data-timezone=\"Indian/Antananarivo\" data-country=\"MG\" data-pin=\"379,182\" data-offset=\"3\" shape=\"rect\" coords=\"372,192,383,170\" />\n";
  $output .= "            <area data-timezone=\"Indian/Chagos\" data-country=\"IO\" data-pin=\"421,162\" data-offset=\"6\" shape=\"rect\" coords=\"414,167,426,154\" />\n";
  $output .= "            <area data-timezone=\"Indian/Christmas\" data-country=\"CX\" data-pin=\"476,167\" data-offset=\"7\" shape=\"rect\" coords=\"471,173,481,162\" />\n";
  $output .= "            <area data-timezone=\"Indian/Cocos\" data-country=\"CC\" data-pin=\"462,170\" data-offset=\"6.5\" shape=\"rect\" coords=\"456,175,467,165\" />\n";
  $output .= "            <area data-timezone=\"Indian/Comoro\" data-country=\"KM\" data-pin=\"372,169\" data-offset=\"3\" shape=\"rect\" coords=\"367,176,379,164\" />\n";
  $output .= "            <area data-timezone=\"Indian/Kerguelen\" data-country=\"TF\" data-pin=\"417,232\" data-offset=\"5\" shape=\"rect\" coords=\"384,233,429,213\" />\n";
  $output .= "            <area data-timezone=\"Indian/Mahe\" data-country=\"SC\" data-pin=\"392,158\" data-offset=\"4\" shape=\"rect\" coords=\"377,167,394,156\" />\n";
  $output .= "            <area data-timezone=\"Indian/Maldives\" data-country=\"MV\" data-pin=\"423,143\" data-offset=\"5\" shape=\"rect\" coords=\"416,151,428,138\" />\n";
  $output .= "            <area data-timezone=\"Indian/Mauritius\" data-country=\"MU\" data-pin=\"396,184\" data-offset=\"4\" shape=\"rect\" coords=\"394,184,406,167\" />\n";
  $output .= "            <area data-timezone=\"Indian/Mayotte\" data-country=\"YT\" data-pin=\"375,171\" data-offset=\"3\" shape=\"rect\" coords=\"370,177,380,166\" />\n";
  $output .= "            <area data-timezone=\"Indian/Reunion\" data-country=\"RE\" data-pin=\"392,185\" data-offset=\"4\" shape=\"rect\" coords=\"387,191,398,180\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Apia\" data-country=\"WS\" data-pin=\"14,173\" data-offset=\"14\" shape=\"rect\" coords=\"7,178,19,167\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Auckland\" data-country=\"NZ\" data-pin=\"591,211\" data-offset=\"13\" shape=\"poly\" coords=\"582,228,577,226,581,223,585,221,588,218,589,219,591,218,590,219,591,219,590,220,588,222,589,223,586,224,585,226,582,228\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Auckland\" data-country=\"NZ\" data-pin=\"591,211\" data-offset=\"13\" shape=\"poly\" coords=\"594,218,592,219,591,219,592,217,590,215,591,215,591,213,590,211,591,210,590,211,588,207,591,209,591,212,593,212,592,211,594,213,598,213,597,215,595,215,594,218\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Chatham\" data-country=\"NZ\" data-pin=\"6,223\" data-offset=\"13.8\" shape=\"rect\" coords=\"0,229,12,218\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Chuuk\" data-country=\"FM\" data-pin=\"553,138\" data-offset=\"10\" shape=\"rect\" coords=\"530,146,556,128\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Easter\" data-country=\"CL\" data-pin=\"118,195\" data-offset=\"-5\" shape=\"rect\" coords=\"113,200,123,190\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Enderbury\" data-country=\"KI\" data-pin=\"15,155\" data-offset=\"13\" shape=\"rect\" coords=\"4,163,20,150\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Fakaofo\" data-country=\"TK\" data-pin=\"15,166\" data-offset=\"13\" shape=\"rect\" coords=\"7,171,20,159\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Efate\" data-country=\"VU\" data-pin=\"581,179\" data-offset=\"11\" shape=\"rect\" coords=\"573,184,589,172\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Fiji\" data-country=\"FJ\" data-pin=\"597,180\" data-offset=\"13\" shape=\"poly\" coords=\"2,179,2,179,2,179\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Funafuti\" data-country=\"TV\" data-pin=\"599,164\" data-offset=\"12\" shape=\"rect\" coords=\"588,171,605,154\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Galapagos\" data-country=\"EC\" data-pin=\"151,152\" data-offset=\"-6\" shape=\"rect\" coords=\"142,157,156,142\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Gambier\" data-country=\"PF\" data-pin=\"75,189\" data-offset=\"-9\" shape=\"rect\" coords=\"67,194,80,180\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Kwajalein\" data-country=\"MH\" data-pin=\"579,135\" data-offset=\"12\" shape=\"rect\" coords=\"573,140,585,129\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Guadalcanal\" data-country=\"SB\" data-pin=\"567,166\" data-offset=\"11\" shape=\"rect\" coords=\"559,170,581,158\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Guam\" data-country=\"GU\" data-pin=\"541,128\" data-offset=\"10\" shape=\"rect\" coords=\"536,133,547,122\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Honolulu\" data-country=\"US\" data-pin=\"37,114\" data-offset=\"-10\" shape=\"rect\" coords=\"10,118,42,107\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Johnston\" data-country=\"US\" data-pin=\"300,150\" data-offset=\"-10\" shape=\"rect\" coords=\"12,127,22,117\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Kiritimati\" data-country=\"KI\" data-pin=\"38,147\" data-offset=\"14\" shape=\"rect\" coords=\"32,169,50,142\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Kosrae\" data-country=\"FM\" data-pin=\"572,141\" data-offset=\"11\" shape=\"rect\" coords=\"567,146,577,136\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Majuro\" data-country=\"MH\" data-pin=\"585,138\" data-offset=\"12\" shape=\"rect\" coords=\"568,142,587,126\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Midway\" data-country=\"UM\" data-pin=\"4,103\" data-offset=\"-11\" shape=\"rect\" coords=\"-2,108,9,98\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Marquesas\" data-country=\"PF\" data-pin=\"68,165\" data-offset=\"-9.5\" shape=\"rect\" coords=\"60,173,74,158\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Nauru\" data-country=\"NR\" data-pin=\"578,151\" data-offset=\"12\" shape=\"rect\" coords=\"573,156,583,146\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Niue\" data-country=\"NU\" data-pin=\"17,182\" data-offset=\"-11\" shape=\"rect\" coords=\"12,187,22,177\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Norfolk\" data-country=\"NF\" data-pin=\"580,198\" data-offset=\"11.5\" shape=\"rect\" coords=\"575,204,585,193\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Noumea\" data-country=\"NC\" data-pin=\"577,187\" data-offset=\"11\" shape=\"rect\" coords=\"564,193,587,177\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Pago_Pago\" data-country=\"AS\" data-pin=\"16,174\" data-offset=\"-11\" shape=\"rect\" coords=\"10,179,23,163\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Palau\" data-country=\"PW\" data-pin=\"524,138\" data-offset=\"9\" shape=\"rect\" coords=\"514,150,530,132\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Pitcairn\" data-country=\"PN\" data-pin=\"83,192\" data-offset=\"-8\" shape=\"rect\" coords=\"82,197,92,185\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Pohnpei\" data-country=\"FM\" data-pin=\"564,138\" data-offset=\"11\" shape=\"rect\" coords=\"557,145,573,133\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Port_Moresby\" data-country=\"PG\" data-pin=\"545,166\" data-offset=\"10\" shape=\"rect\" coords=\"537,169,566,151\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Rarotonga\" data-country=\"CK\" data-pin=\"34,185\" data-offset=\"-10\" shape=\"rect\" coords=\"24,187,38,165\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Saipan\" data-country=\"MP\" data-pin=\"543,125\" data-offset=\"10\" shape=\"rect\" coords=\"536,126,548,116\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Tahiti\" data-country=\"PF\" data-pin=\"51,179\" data-offset=\"-10\" shape=\"rect\" coords=\"42,196,73,173\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Tarawa\" data-country=\"KI\" data-pin=\"588,148\" data-offset=\"12\" shape=\"rect\" coords=\"583,154,595,144\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Tongatapu\" data-country=\"TO\" data-pin=\"8,185\" data-offset=\"13\" shape=\"rect\" coords=\"1,187,15,176\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Wake\" data-country=\"UM\" data-pin=\"578,118\" data-offset=\"12\" shape=\"rect\" coords=\"573,123,583,113\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Wallis\" data-country=\"WF\" data-pin=\"6,172\" data-offset=\"12\" shape=\"rect\" coords=\"-2,179,11,167\" />\n";
  $output .= "          </map>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile country selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Country\" id=\"profile_add_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile country") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_timezone\" class=\"control-label col-xs-2\">" . __ ( "Time zone") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"TimeZone\" id=\"profile_add_timezone\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile time zone") . "\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"Africa/Abidjan\">Africa/Abidjan</option>\n";
  $output .= "            <option value=\"Africa/Accra\">Africa/Accra</option>\n";
  $output .= "            <option value=\"Africa/Addis_Ababa\">Africa/Addis Ababa</option>\n";
  $output .= "            <option value=\"Africa/Algiers\">Africa/Algiers</option>\n";
  $output .= "            <option value=\"Africa/Asmara\">Africa/Asmara</option>\n";
  $output .= "            <option value=\"Africa/Bamako\">Africa/Bamako</option>\n";
  $output .= "            <option value=\"Africa/Bangui\">Africa/Bangui</option>\n";
  $output .= "            <option value=\"Africa/Banjul\">Africa/Banjul</option>\n";
  $output .= "            <option value=\"Africa/Bissau\">Africa/Bissau</option>\n";
  $output .= "            <option value=\"Africa/Blantyre\">Africa/Blantyre</option>\n";
  $output .= "            <option value=\"Africa/Brazzaville\">Africa/Brazzaville</option>\n";
  $output .= "            <option value=\"Africa/Bujumbura\">Africa/Bujumbura</option>\n";
  $output .= "            <option value=\"Africa/Cairo\">Africa/Cairo</option>\n";
  $output .= "            <option value=\"Africa/Casablanca\">Africa/Casablanca</option>\n";
  $output .= "            <option value=\"Africa/Ceuta\">Africa/Ceuta</option>\n";
  $output .= "            <option value=\"Africa/Conakry\">Africa/Conakry</option>\n";
  $output .= "            <option value=\"Africa/Dakar\">Africa/Dakar</option>\n";
  $output .= "            <option value=\"Africa/Dar_es_Salaam\">Africa/Dar es Salaam</option>\n";
  $output .= "            <option value=\"Africa/Djibouti\">Africa/Djibouti</option>\n";
  $output .= "            <option value=\"Africa/Douala\">Africa/Douala</option>\n";
  $output .= "            <option value=\"Africa/El_Aaiun\">Africa/El Aaiun</option>\n";
  $output .= "            <option value=\"Africa/Freetown\">Africa/Freetown</option>\n";
  $output .= "            <option value=\"Africa/Gaborone\">Africa/Gaborone</option>\n";
  $output .= "            <option value=\"Africa/Harare\">Africa/Harare</option>\n";
  $output .= "            <option value=\"Africa/Johannesburg\">Africa/Johannesburg</option>\n";
  $output .= "            <option value=\"Africa/Kampala\">Africa/Kampala</option>\n";
  $output .= "            <option value=\"Africa/Khartoum\">Africa/Khartoum</option>\n";
  $output .= "            <option value=\"Africa/Kigali\">Africa/Kigali</option>\n";
  $output .= "            <option value=\"Africa/Kinshasa\">Africa/Kinshasa</option>\n";
  $output .= "            <option value=\"Africa/Lagos\">Africa/Lagos</option>\n";
  $output .= "            <option value=\"Africa/Libreville\">Africa/Libreville</option>\n";
  $output .= "            <option value=\"Africa/Lome\">Africa/Lome</option>\n";
  $output .= "            <option value=\"Africa/Luanda\">Africa/Luanda</option>\n";
  $output .= "            <option value=\"Africa/Lubumbashi\">Africa/Lubumbashi</option>\n";
  $output .= "            <option value=\"Africa/Lusaka\">Africa/Lusaka</option>\n";
  $output .= "            <option value=\"Africa/Malabo\">Africa/Malabo</option>\n";
  $output .= "            <option value=\"Africa/Maputo\">Africa/Maputo</option>\n";
  $output .= "            <option value=\"Africa/Maseru\">Africa/Maseru</option>\n";
  $output .= "            <option value=\"Africa/Mbabane\">Africa/Mbabane</option>\n";
  $output .= "            <option value=\"Africa/Mogadishu\">Africa/Mogadishu</option>\n";
  $output .= "            <option value=\"Africa/Monrovia\">Africa/Monrovia</option>\n";
  $output .= "            <option value=\"Africa/Nairobi\">Africa/Nairobi</option>\n";
  $output .= "            <option value=\"Africa/Ndjamena\">Africa/Ndjamena</option>\n";
  $output .= "            <option value=\"Africa/Niamey\">Africa/Niamey</option>\n";
  $output .= "            <option value=\"Africa/Nouakchott\">Africa/Nouakchott</option>\n";
  $output .= "            <option value=\"Africa/Ouagadougou\">Africa/Ouagadougou</option>\n";
  $output .= "            <option value=\"Africa/Porto-Novo\">Africa/Porto-Novo</option>\n";
  $output .= "            <option value=\"Africa/Sao_Tome\">Africa/Sao Tome</option>\n";
  $output .= "            <option value=\"Africa/Tripoli\">Africa/Tripoli</option>\n";
  $output .= "            <option value=\"Africa/Tunis\">Africa/Tunis</option>\n";
  $output .= "            <option value=\"Africa/Windhoek\">Africa/Windhoek</option>\n";
  $output .= "            <option value=\"America/Adak\">America/Adak</option>\n";
  $output .= "            <option value=\"America/Anchorage\">America/Anchorage</option>\n";
  $output .= "            <option value=\"America/Anguilla\">America/Anguilla</option>\n";
  $output .= "            <option value=\"America/Antigua\">America/Antigua</option>\n";
  $output .= "            <option value=\"America/Araguaina\">America/Araguaina</option>\n";
  $output .= "            <option value=\"America/Argentina/Buenos_Aires\">America/Argentina/Buenos Aires</option>\n";
  $output .= "            <option value=\"America/Argentina/Catamarca\">America/Argentina/Catamarca</option>\n";
  $output .= "            <option value=\"America/Argentina/Cordoba\">America/Argentina/Cordoba</option>\n";
  $output .= "            <option value=\"America/Argentina/Jujuy\">America/Argentina/Jujuy</option>\n";
  $output .= "            <option value=\"America/Argentina/La_Rioja\">America/Argentina/La Rioja</option>\n";
  $output .= "            <option value=\"America/Argentina/Mendoza\">America/Argentina/Mendoza</option>\n";
  $output .= "            <option value=\"America/Argentina/Rio_Gallegos\">America/Argentina/Rio Gallegos</option>\n";
  $output .= "            <option value=\"America/Argentina/Salta\">America/Argentina/Salta</option>\n";
  $output .= "            <option value=\"America/Argentina/San_Juan\">America/Argentina/San Juan</option>\n";
  $output .= "            <option value=\"America/Argentina/San_Luis\">America/Argentina/San Luis</option>\n";
  $output .= "            <option value=\"America/Argentina/Tucuman\">America/Argentina/Tucuman</option>\n";
  $output .= "            <option value=\"America/Argentina/Ushuaia\">America/Argentina/Ushuaia</option>\n";
  $output .= "            <option value=\"America/Aruba\">America/Aruba</option>\n";
  $output .= "            <option value=\"America/Asuncion\">America/Asuncion</option>\n";
  $output .= "            <option value=\"America/Atikokan\">America/Atikokan</option>\n";
  $output .= "            <option value=\"America/Bahia_Banderas\">America/Bahia Banderas</option>\n";
  $output .= "            <option value=\"America/Bahia\">America/Bahia</option>\n";
  $output .= "            <option value=\"America/Barbados\">America/Barbados</option>\n";
  $output .= "            <option value=\"America/Belem\">America/Belem</option>\n";
  $output .= "            <option value=\"America/Belize\">America/Belize</option>\n";
  $output .= "            <option value=\"America/Blanc-Sablon\">America/Blanc-Sablon</option>\n";
  $output .= "            <option value=\"America/Boa_Vista\">America/Boa Vista</option>\n";
  $output .= "            <option value=\"America/Bogota\">America/Bogota</option>\n";
  $output .= "            <option value=\"America/Boise\">America/Boise</option>\n";
  $output .= "            <option value=\"America/Cambridge_Bay\">America/Cambridge Bay</option>\n";
  $output .= "            <option value=\"America/Campo_Grande\">America/Campo Grande</option>\n";
  $output .= "            <option value=\"America/Cancun\">America/Cancun</option>\n";
  $output .= "            <option value=\"America/Caracas\">America/Caracas</option>\n";
  $output .= "            <option value=\"America/Cayenne\">America/Cayenne</option>\n";
  $output .= "            <option value=\"America/Cayman\">America/Cayman</option>\n";
  $output .= "            <option value=\"America/Chicago\">America/Chicago</option>\n";
  $output .= "            <option value=\"America/Chihuahua\">America/Chihuahua</option>\n";
  $output .= "            <option value=\"America/Costa_Rica\">America/Costa Rica</option>\n";
  $output .= "            <option value=\"America/Cuiaba\">America/Cuiaba</option>\n";
  $output .= "            <option value=\"America/Curacao\">America/Curacao</option>\n";
  $output .= "            <option value=\"America/Danmarkshavn\">America/Danmarkshavn</option>\n";
  $output .= "            <option value=\"America/Dawson_Creek\">America/Dawson Creek</option>\n";
  $output .= "            <option value=\"America/Dawson\">America/Dawson</option>\n";
  $output .= "            <option value=\"America/Denver\">America/Denver</option>\n";
  $output .= "            <option value=\"America/Detroit\">America/Detroit</option>\n";
  $output .= "            <option value=\"America/Dominica\">America/Dominica</option>\n";
  $output .= "            <option value=\"America/Edmonton\">America/Edmonton</option>\n";
  $output .= "            <option value=\"America/Eirunepe\">America/Eirunepe</option>\n";
  $output .= "            <option value=\"America/El_Salvador\">America/El Salvador</option>\n";
  $output .= "            <option value=\"America/Fortaleza\">America/Fortaleza</option>\n";
  $output .= "            <option value=\"America/Glace_Bay\">America/Glace Bay</option>\n";
  $output .= "            <option value=\"America/Godthab\">America/Godthab</option>\n";
  $output .= "            <option value=\"America/Goose_Bay\">America/Goose Bay</option>\n";
  $output .= "            <option value=\"America/Grand_Turk\">America/Grand Turk</option>\n";
  $output .= "            <option value=\"America/Grenada\">America/Grenada</option>\n";
  $output .= "            <option value=\"America/Guadeloupe\">America/Guadeloupe</option>\n";
  $output .= "            <option value=\"America/Guatemala\">America/Guatemala</option>\n";
  $output .= "            <option value=\"America/Guayaquil\">America/Guayaquil</option>\n";
  $output .= "            <option value=\"America/Guyana\">America/Guyana</option>\n";
  $output .= "            <option value=\"America/Halifax\">America/Halifax</option>\n";
  $output .= "            <option value=\"America/Havana\">America/Havana</option>\n";
  $output .= "            <option value=\"America/Hermosillo\">America/Hermosillo</option>\n";
  $output .= "            <option value=\"America/Indiana/Indianapolis\">America/Indiana/Indianapolis</option>\n";
  $output .= "            <option value=\"America/Indiana/Knox\">America/Indiana/Knox</option>\n";
  $output .= "            <option value=\"America/Indiana/Marengo\">America/Indiana/Marengo</option>\n";
  $output .= "            <option value=\"America/Indiana/Petersburg\">America/Indiana/Petersburg</option>\n";
  $output .= "            <option value=\"America/Indiana/Tell_City\">America/Indiana/Tell City</option>\n";
  $output .= "            <option value=\"America/Indiana/Vevay\">America/Indiana/Vevay</option>\n";
  $output .= "            <option value=\"America/Indiana/Vincennes\">America/Indiana/Vincennes</option>\n";
  $output .= "            <option value=\"America/Indiana/Winamac\">America/Indiana/Winamac</option>\n";
  $output .= "            <option value=\"America/Inuvik\">America/Inuvik</option>\n";
  $output .= "            <option value=\"America/Iqaluit\">America/Iqaluit</option>\n";
  $output .= "            <option value=\"America/Jamaica\">America/Jamaica</option>\n";
  $output .= "            <option value=\"America/Juneau\">America/Juneau</option>\n";
  $output .= "            <option value=\"America/Kentucky/Louisville\">America/Kentucky/Louisville</option>\n";
  $output .= "            <option value=\"America/Kentucky/Monticello\">America/Kentucky/Monticello</option>\n";
  $output .= "            <option value=\"America/La_Paz\">America/La Paz</option>\n";
  $output .= "            <option value=\"America/Lima\">America/Lima</option>\n";
  $output .= "            <option value=\"America/Los_Angeles\">America/Los Angeles</option>\n";
  $output .= "            <option value=\"America/Maceio\">America/Maceio</option>\n";
  $output .= "            <option value=\"America/Managua\">America/Managua</option>\n";
  $output .= "            <option value=\"America/Manaus\">America/Manaus</option>\n";
  $output .= "            <option value=\"America/Marigot\">America/Marigot</option>\n";
  $output .= "            <option value=\"America/Martinique\">America/Martinique</option>\n";
  $output .= "            <option value=\"America/Matamoros\">America/Matamoros</option>\n";
  $output .= "            <option value=\"America/Mazatlan\">America/Mazatlan</option>\n";
  $output .= "            <option value=\"America/Menominee\">America/Menominee</option>\n";
  $output .= "            <option value=\"America/Merida\">America/Merida</option>\n";
  $output .= "            <option value=\"America/Metlakatla\">America/Metlakatla</option>\n";
  $output .= "            <option value=\"America/Mexico_City\">America/Mexico City</option>\n";
  $output .= "            <option value=\"America/Miquelon\">America/Miquelon</option>\n";
  $output .= "            <option value=\"America/Moncton\">America/Moncton</option>\n";
  $output .= "            <option value=\"America/Monterrey\">America/Monterrey</option>\n";
  $output .= "            <option value=\"America/Montevideo\">America/Montevideo</option>\n";
  $output .= "            <option value=\"America/Montreal\">America/Montreal</option>\n";
  $output .= "            <option value=\"America/Montserrat\">America/Montserrat</option>\n";
  $output .= "            <option value=\"America/Nassau\">America/Nassau</option>\n";
  $output .= "            <option value=\"America/New_York\">America/New York</option>\n";
  $output .= "            <option value=\"America/Nipigon\">America/Nipigon</option>\n";
  $output .= "            <option value=\"America/Nome\">America/Nome</option>\n";
  $output .= "            <option value=\"America/Noronha\">America/Noronha</option>\n";
  $output .= "            <option value=\"America/North_Dakota/Beulah\">America/North Dakota/Beulah</option>\n";
  $output .= "            <option value=\"America/North_Dakota/Center\">America/North Dakota/Center</option>\n";
  $output .= "            <option value=\"America/North_Dakota/New_Salem\">America/North Dakota/New Salem</option>\n";
  $output .= "            <option value=\"America/Ojinaga\">America/Ojinaga</option>\n";
  $output .= "            <option value=\"America/Panama\">America/Panama</option>\n";
  $output .= "            <option value=\"America/Pangnirtung\">America/Pangnirtung</option>\n";
  $output .= "            <option value=\"America/Paramaribo\">America/Paramaribo</option>\n";
  $output .= "            <option value=\"America/Phoenix\">America/Phoenix</option>\n";
  $output .= "            <option value=\"America/Port_of_Spain\">America/Port of Spain</option>\n";
  $output .= "            <option value=\"America/Port-au-Prince\">America/Port-au-Prince</option>\n";
  $output .= "            <option value=\"America/Porto_Velho\">America/Porto Velho</option>\n";
  $output .= "            <option value=\"America/Puerto_Rico\">America/Puerto Rico</option>\n";
  $output .= "            <option value=\"America/Rainy_River\">America/Rainy River</option>\n";
  $output .= "            <option value=\"America/Rankin_Inlet\">America/Rankin Inlet</option>\n";
  $output .= "            <option value=\"America/Recife\">America/Recife</option>\n";
  $output .= "            <option value=\"America/Regina\">America/Regina</option>\n";
  $output .= "            <option value=\"America/Resolute\">America/Resolute</option>\n";
  $output .= "            <option value=\"America/Rio_Branco\">America/Rio Branco</option>\n";
  $output .= "            <option value=\"America/Santa_Isabel\">America/Santa Isabel</option>\n";
  $output .= "            <option value=\"America/Santarem\">America/Santarem</option>\n";
  $output .= "            <option value=\"America/Santiago\">America/Santiago</option>\n";
  $output .= "            <option value=\"America/Santo_Domingo\">America/Santo Domingo</option>\n";
  $output .= "            <option value=\"America/Sao_Paulo\">America/Sao Paulo</option>\n";
  $output .= "            <option value=\"America/Scoresbysund\">America/Scoresbysund</option>\n";
  $output .= "            <option value=\"America/Shiprock\">America/Shiprock</option>\n";
  $output .= "            <option value=\"America/Sitka\">America/Sitka</option>\n";
  $output .= "            <option value=\"America/St_Barthelemy\">America/St Barthelemy</option>\n";
  $output .= "            <option value=\"America/St_Johns\">America/St Johns</option>\n";
  $output .= "            <option value=\"America/St_Kitts\">America/St Kitts</option>\n";
  $output .= "            <option value=\"America/St_Lucia\">America/St Lucia</option>\n";
  $output .= "            <option value=\"America/St_Thomas\">America/St Thomas</option>\n";
  $output .= "            <option value=\"America/St_Vincent\">America/St Vincent</option>\n";
  $output .= "            <option value=\"America/Swift_Current\">America/Swift Current</option>\n";
  $output .= "            <option value=\"America/Tegucigalpa\">America/Tegucigalpa</option>\n";
  $output .= "            <option value=\"America/Thule\">America/Thule</option>\n";
  $output .= "            <option value=\"America/Thunder_Bay\">America/Thunder Bay</option>\n";
  $output .= "            <option value=\"America/Tijuana\">America/Tijuana</option>\n";
  $output .= "            <option value=\"America/Toronto\">America/Toronto</option>\n";
  $output .= "            <option value=\"America/Tortola\">America/Tortola</option>\n";
  $output .= "            <option value=\"America/Vancouver\">America/Vancouver</option>\n";
  $output .= "            <option value=\"America/Whitehorse\">America/Whitehorse</option>\n";
  $output .= "            <option value=\"America/Winnipeg\">America/Winnipeg</option>\n";
  $output .= "            <option value=\"America/Yakutat\">America/Yakutat</option>\n";
  $output .= "            <option value=\"America/Yellowknife\">America/Yellowknife</option>\n";
  $output .= "            <option value=\"Antarctica/Casey\">Antarctica/Casey</option>\n";
  $output .= "            <option value=\"Antarctica/Davis\">Antarctica/Davis</option>\n";
  $output .= "            <option value=\"Antarctica/DumontDUrville\">Antarctica/DumontDUrville</option>\n";
  $output .= "            <option value=\"Antarctica/Macquarie\">Antarctica/Macquarie</option>\n";
  $output .= "            <option value=\"Antarctica/Mawson\">Antarctica/Mawson</option>\n";
  $output .= "            <option value=\"Antarctica/McMurdo\">Antarctica/McMurdo</option>\n";
  $output .= "            <option value=\"Antarctica/Palmer\">Antarctica/Palmer</option>\n";
  $output .= "            <option value=\"Antarctica/Rothera\">Antarctica/Rothera</option>\n";
  $output .= "            <option value=\"Antarctica/South_Pole\">Antarctica/South Pole</option>\n";
  $output .= "            <option value=\"Antarctica/Syowa\">Antarctica/Syowa</option>\n";
  $output .= "            <option value=\"Antarctica/Vostok\">Antarctica/Vostok</option>\n";
  $output .= "            <option value=\"Arctic/Longyearbyen\">Arctic/Longyearbyen</option>\n";
  $output .= "            <option value=\"Asia/Aden\">Asia/Aden</option>\n";
  $output .= "            <option value=\"Asia/Almaty\">Asia/Almaty</option>\n";
  $output .= "            <option value=\"Asia/Amman\">Asia/Amman</option>\n";
  $output .= "            <option value=\"Asia/Anadyr\">Asia/Anadyr</option>\n";
  $output .= "            <option value=\"Asia/Aqtau\">Asia/Aqtau</option>\n";
  $output .= "            <option value=\"Asia/Aqtobe\">Asia/Aqtobe</option>\n";
  $output .= "            <option value=\"Asia/Ashgabat\">Asia/Ashgabat</option>\n";
  $output .= "            <option value=\"Asia/Baghdad\">Asia/Baghdad</option>\n";
  $output .= "            <option value=\"Asia/Bahrain\">Asia/Bahrain</option>\n";
  $output .= "            <option value=\"Asia/Baku\">Asia/Baku</option>\n";
  $output .= "            <option value=\"Asia/Bangkok\">Asia/Bangkok</option>\n";
  $output .= "            <option value=\"Asia/Beirut\">Asia/Beirut</option>\n";
  $output .= "            <option value=\"Asia/Bishkek\">Asia/Bishkek</option>\n";
  $output .= "            <option value=\"Asia/Brunei\">Asia/Brunei</option>\n";
  $output .= "            <option value=\"Asia/Choibalsan\">Asia/Choibalsan</option>\n";
  $output .= "            <option value=\"Asia/Chongqing\">Asia/Chongqing</option>\n";
  $output .= "            <option value=\"Asia/Colombo\">Asia/Colombo</option>\n";
  $output .= "            <option value=\"Asia/Damascus\">Asia/Damascus</option>\n";
  $output .= "            <option value=\"Asia/Dhaka\">Asia/Dhaka</option>\n";
  $output .= "            <option value=\"Asia/Dili\">Asia/Dili</option>\n";
  $output .= "            <option value=\"Asia/Dubai\">Asia/Dubai</option>\n";
  $output .= "            <option value=\"Asia/Dushanbe\">Asia/Dushanbe</option>\n";
  $output .= "            <option value=\"Asia/Gaza\">Asia/Gaza</option>\n";
  $output .= "            <option value=\"Asia/Harbin\">Asia/Harbin</option>\n";
  $output .= "            <option value=\"Asia/Ho_Chi_Minh\">Asia/Ho Chi Minh</option>\n";
  $output .= "            <option value=\"Asia/Hong_Kong\">Asia/Hong Kong</option>\n";
  $output .= "            <option value=\"Asia/Hovd\">Asia/Hovd</option>\n";
  $output .= "            <option value=\"Asia/Irkutsk\">Asia/Irkutsk</option>\n";
  $output .= "            <option value=\"Asia/Jakarta\">Asia/Jakarta</option>\n";
  $output .= "            <option value=\"Asia/Jayapura\">Asia/Jayapura</option>\n";
  $output .= "            <option value=\"Asia/Jerusalem\">Asia/Jerusalem</option>\n";
  $output .= "            <option value=\"Asia/Kabul\">Asia/Kabul</option>\n";
  $output .= "            <option value=\"Asia/Kamchatka\">Asia/Kamchatka</option>\n";
  $output .= "            <option value=\"Asia/Karachi\">Asia/Karachi</option>\n";
  $output .= "            <option value=\"Asia/Kashgar\">Asia/Kashgar</option>\n";
  $output .= "            <option value=\"Asia/Kathmandu\">Asia/Kathmandu</option>\n";
  $output .= "            <option value=\"Asia/Kolkata\">Asia/Kolkata</option>\n";
  $output .= "            <option value=\"Asia/Krasnoyarsk\">Asia/Krasnoyarsk</option>\n";
  $output .= "            <option value=\"Asia/Kuala_Lumpur\">Asia/Kuala Lumpur</option>\n";
  $output .= "            <option value=\"Asia/Kuching\">Asia/Kuching</option>\n";
  $output .= "            <option value=\"Asia/Kuwait\">Asia/Kuwait</option>\n";
  $output .= "            <option value=\"Asia/Macau\">Asia/Macau</option>\n";
  $output .= "            <option value=\"Asia/Magadan\">Asia/Magadan</option>\n";
  $output .= "            <option value=\"Asia/Makassar\">Asia/Makassar</option>\n";
  $output .= "            <option value=\"Asia/Manila\">Asia/Manila</option>\n";
  $output .= "            <option value=\"Asia/Muscat\">Asia/Muscat</option>\n";
  $output .= "            <option value=\"Asia/Nicosia\">Asia/Nicosia</option>\n";
  $output .= "            <option value=\"Asia/Novokuznetsk\">Asia/Novokuznetsk</option>\n";
  $output .= "            <option value=\"Asia/Novosibirsk\">Asia/Novosibirsk</option>\n";
  $output .= "            <option value=\"Asia/Omsk\">Asia/Omsk</option>\n";
  $output .= "            <option value=\"Asia/Oral\">Asia/Oral</option>\n";
  $output .= "            <option value=\"Asia/Phnom_Penh\">Asia/Phnom Penh</option>\n";
  $output .= "            <option value=\"Asia/Pontianak\">Asia/Pontianak</option>\n";
  $output .= "            <option value=\"Asia/Pyongyang\">Asia/Pyongyang</option>\n";
  $output .= "            <option value=\"Asia/Qatar\">Asia/Qatar</option>\n";
  $output .= "            <option value=\"Asia/Qyzylorda\">Asia/Qyzylorda</option>\n";
  $output .= "            <option value=\"Asia/Rangoon\">Asia/Rangoon</option>\n";
  $output .= "            <option value=\"Asia/Riyadh\">Asia/Riyadh</option>\n";
  $output .= "            <option value=\"Asia/Sakhalin\">Asia/Sakhalin</option>\n";
  $output .= "            <option value=\"Asia/Samarkand\">Asia/Samarkand</option>\n";
  $output .= "            <option value=\"Asia/Seoul\">Asia/Seoul</option>\n";
  $output .= "            <option value=\"Asia/Shanghai\">Asia/Shanghai</option>\n";
  $output .= "            <option value=\"Asia/Singapore\">Asia/Singapore</option>\n";
  $output .= "            <option value=\"Asia/Taipei\">Asia/Taipei</option>\n";
  $output .= "            <option value=\"Asia/Tashkent\">Asia/Tashkent</option>\n";
  $output .= "            <option value=\"Asia/Tbilisi\">Asia/Tbilisi</option>\n";
  $output .= "            <option value=\"Asia/Tehran\">Asia/Tehran</option>\n";
  $output .= "            <option value=\"Asia/Thimphu\">Asia/Thimphu</option>\n";
  $output .= "            <option value=\"Asia/Tokyo\">Asia/Tokyo</option>\n";
  $output .= "            <option value=\"Asia/Ulaanbaatar\">Asia/Ulaanbaatar</option>\n";
  $output .= "            <option value=\"Asia/Urumqi\">Asia/Urumqi</option>\n";
  $output .= "            <option value=\"Asia/Vientiane\">Asia/Vientiane</option>\n";
  $output .= "            <option value=\"Asia/Vladivostok\">Asia/Vladivostok</option>\n";
  $output .= "            <option value=\"Asia/Yakutsk\">Asia/Yakutsk</option>\n";
  $output .= "            <option value=\"Asia/Yekaterinburg\">Asia/Yekaterinburg</option>\n";
  $output .= "            <option value=\"Asia/Yerevan\">Asia/Yerevan</option>\n";
  $output .= "            <option value=\"Atlantic/Azores\">Atlantic/Azores</option>\n";
  $output .= "            <option value=\"Atlantic/Bermuda\">Atlantic/Bermuda</option>\n";
  $output .= "            <option value=\"Atlantic/Canary\">Atlantic/Canary</option>\n";
  $output .= "            <option value=\"Atlantic/Cape_Verde\">Atlantic/Cape Verde</option>\n";
  $output .= "            <option value=\"Atlantic/Faroe\">Atlantic/Faroe</option>\n";
  $output .= "            <option value=\"Atlantic/Madeira\">Atlantic/Madeira</option>\n";
  $output .= "            <option value=\"Atlantic/Reykjavik\">Atlantic/Reykjavik</option>\n";
  $output .= "            <option value=\"Atlantic/South_Georgia\">Atlantic/South Georgia</option>\n";
  $output .= "            <option value=\"Atlantic/St_Helena\">Atlantic/St Helena</option>\n";
  $output .= "            <option value=\"Atlantic/Stanley\">Atlantic/Stanley</option>\n";
  $output .= "            <option value=\"Australia/Adelaide\">Australia/Adelaide</option>\n";
  $output .= "            <option value=\"Australia/Brisbane\">Australia/Brisbane</option>\n";
  $output .= "            <option value=\"Australia/Broken_Hill\">Australia/Broken Hill</option>\n";
  $output .= "            <option value=\"Australia/Currie\">Australia/Currie</option>\n";
  $output .= "            <option value=\"Australia/Darwin\">Australia/Darwin</option>\n";
  $output .= "            <option value=\"Australia/Eucla\">Australia/Eucla</option>\n";
  $output .= "            <option value=\"Australia/Hobart\">Australia/Hobart</option>\n";
  $output .= "            <option value=\"Australia/Lindeman\">Australia/Lindeman</option>\n";
  $output .= "            <option value=\"Australia/Lord_Howe\">Australia/Lord Howe</option>\n";
  $output .= "            <option value=\"Australia/Melbourne\">Australia/Melbourne</option>\n";
  $output .= "            <option value=\"Australia/Perth\">Australia/Perth</option>\n";
  $output .= "            <option value=\"Australia/Sydney\">Australia/Sydney</option>\n";
  $output .= "            <option value=\"Europe/Amsterdam\">Europe/Amsterdam</option>\n";
  $output .= "            <option value=\"Europe/Andorra\">Europe/Andorra</option>\n";
  $output .= "            <option value=\"Europe/Athens\">Europe/Athens</option>\n";
  $output .= "            <option value=\"Europe/Belgrade\">Europe/Belgrade</option>\n";
  $output .= "            <option value=\"Europe/Berlin\">Europe/Berlin</option>\n";
  $output .= "            <option value=\"Europe/Bratislava\">Europe/Bratislava</option>\n";
  $output .= "            <option value=\"Europe/Brussels\">Europe/Brussels</option>\n";
  $output .= "            <option value=\"Europe/Bucharest\">Europe/Bucharest</option>\n";
  $output .= "            <option value=\"Europe/Budapest\">Europe/Budapest</option>\n";
  $output .= "            <option value=\"Europe/Chisinau\">Europe/Chisinau</option>\n";
  $output .= "            <option value=\"Europe/Copenhagen\">Europe/Copenhagen</option>\n";
  $output .= "            <option value=\"Europe/Dublin\">Europe/Dublin</option>\n";
  $output .= "            <option value=\"Europe/Gibraltar\">Europe/Gibraltar</option>\n";
  $output .= "            <option value=\"Europe/Guernsey\">Europe/Guernsey</option>\n";
  $output .= "            <option value=\"Europe/Helsinki\">Europe/Helsinki</option>\n";
  $output .= "            <option value=\"Europe/Isle_of_Man\">Europe/Isle of Man</option>\n";
  $output .= "            <option value=\"Europe/Istanbul\">Europe/Istanbul</option>\n";
  $output .= "            <option value=\"Europe/Jersey\">Europe/Jersey</option>\n";
  $output .= "            <option value=\"Europe/Kaliningrad\">Europe/Kaliningrad</option>\n";
  $output .= "            <option value=\"Europe/Kiev\">Europe/Kiev</option>\n";
  $output .= "            <option value=\"Europe/Lisbon\">Europe/Lisbon</option>\n";
  $output .= "            <option value=\"Europe/Ljubljana\">Europe/Ljubljana</option>\n";
  $output .= "            <option value=\"Europe/London\">Europe/London</option>\n";
  $output .= "            <option value=\"Europe/Luxembourg\">Europe/Luxembourg</option>\n";
  $output .= "            <option value=\"Europe/Madrid\">Europe/Madrid</option>\n";
  $output .= "            <option value=\"Europe/Malta\">Europe/Malta</option>\n";
  $output .= "            <option value=\"Europe/Mariehamn\">Europe/Mariehamn</option>\n";
  $output .= "            <option value=\"Europe/Minsk\">Europe/Minsk</option>\n";
  $output .= "            <option value=\"Europe/Monaco\">Europe/Monaco</option>\n";
  $output .= "            <option value=\"Europe/Moscow\">Europe/Moscow</option>\n";
  $output .= "            <option value=\"Europe/Oslo\">Europe/Oslo</option>\n";
  $output .= "            <option value=\"Europe/Paris\">Europe/Paris</option>\n";
  $output .= "            <option value=\"Europe/Podgorica\">Europe/Podgorica</option>\n";
  $output .= "            <option value=\"Europe/Prague\">Europe/Prague</option>\n";
  $output .= "            <option value=\"Europe/Riga\">Europe/Riga</option>\n";
  $output .= "            <option value=\"Europe/Rome\">Europe/Rome</option>\n";
  $output .= "            <option value=\"Europe/Samara\">Europe/Samara</option>\n";
  $output .= "            <option value=\"Europe/San_Marino\">Europe/San Marino</option>\n";
  $output .= "            <option value=\"Europe/Sarajevo\">Europe/Sarajevo</option>\n";
  $output .= "            <option value=\"Europe/Simferopol\">Europe/Simferopol</option>\n";
  $output .= "            <option value=\"Europe/Skopje\">Europe/Skopje</option>\n";
  $output .= "            <option value=\"Europe/Sofia\">Europe/Sofia</option>\n";
  $output .= "            <option value=\"Europe/Stockholm\">Europe/Stockholm</option>\n";
  $output .= "            <option value=\"Europe/Tallinn\">Europe/Tallinn</option>\n";
  $output .= "            <option value=\"Europe/Tirane\">Europe/Tirane</option>\n";
  $output .= "            <option value=\"Europe/Uzhgorod\">Europe/Uzhgorod</option>\n";
  $output .= "            <option value=\"Europe/Vaduz\">Europe/Vaduz</option>\n";
  $output .= "            <option value=\"Europe/Vatican\">Europe/Vatican</option>\n";
  $output .= "            <option value=\"Europe/Vienna\">Europe/Vienna</option>\n";
  $output .= "            <option value=\"Europe/Vilnius\">Europe/Vilnius</option>\n";
  $output .= "            <option value=\"Europe/Volgograd\">Europe/Volgograd</option>\n";
  $output .= "            <option value=\"Europe/Warsaw\">Europe/Warsaw</option>\n";
  $output .= "            <option value=\"Europe/Zagreb\">Europe/Zagreb</option>\n";
  $output .= "            <option value=\"Europe/Zaporozhye\">Europe/Zaporozhye</option>\n";
  $output .= "            <option value=\"Europe/Zurich\">Europe/Zurich</option>\n";
  $output .= "            <option value=\"Indian/Antananarivo\">Indian/Antananarivo</option>\n";
  $output .= "            <option value=\"Indian/Chagos\">Indian/Chagos</option>\n";
  $output .= "            <option value=\"Indian/Christmas\">Indian/Christmas</option>\n";
  $output .= "            <option value=\"Indian/Cocos\">Indian/Cocos</option>\n";
  $output .= "            <option value=\"Indian/Comoro\">Indian/Comoro</option>\n";
  $output .= "            <option value=\"Indian/Kerguelen\">Indian/Kerguelen</option>\n";
  $output .= "            <option value=\"Indian/Mahe\">Indian/Mahe</option>\n";
  $output .= "            <option value=\"Indian/Maldives\">Indian/Maldives</option>\n";
  $output .= "            <option value=\"Indian/Mauritius\">Indian/Mauritius</option>\n";
  $output .= "            <option value=\"Indian/Mayotte\">Indian/Mayotte</option>\n";
  $output .= "            <option value=\"Indian/Reunion\">Indian/Reunion</option>\n";
  $output .= "            <option value=\"Pacific/Apia\">Pacific/Apia</option>\n";
  $output .= "            <option value=\"Pacific/Auckland\">Pacific/Auckland</option>\n";
  $output .= "            <option value=\"Pacific/Chatham\">Pacific/Chatham</option>\n";
  $output .= "            <option value=\"Pacific/Chuuk\">Pacific/Chuuk</option>\n";
  $output .= "            <option value=\"Pacific/Easter\">Pacific/Easter</option>\n";
  $output .= "            <option value=\"Pacific/Efate\">Pacific/Efate</option>\n";
  $output .= "            <option value=\"Pacific/Enderbury\">Pacific/Enderbury</option>\n";
  $output .= "            <option value=\"Pacific/Fakaofo\">Pacific/Fakaofo</option>\n";
  $output .= "            <option value=\"Pacific/Fiji\">Pacific/Fiji</option>\n";
  $output .= "            <option value=\"Pacific/Funafuti\">Pacific/Funafuti</option>\n";
  $output .= "            <option value=\"Pacific/Galapagos\">Pacific/Galapagos</option>\n";
  $output .= "            <option value=\"Pacific/Gambier\">Pacific/Gambier</option>\n";
  $output .= "            <option value=\"Pacific/Guadalcanal\">Pacific/Guadalcanal</option>\n";
  $output .= "            <option value=\"Pacific/Guam\">Pacific/Guam</option>\n";
  $output .= "            <option value=\"Pacific/Honolulu\">Pacific/Honolulu</option>\n";
  $output .= "            <option value=\"Pacific/Johnston\">Pacific/Johnston</option>\n";
  $output .= "            <option value=\"Pacific/Kiritimati\">Pacific/Kiritimati</option>\n";
  $output .= "            <option value=\"Pacific/Kosrae\">Pacific/Kosrae</option>\n";
  $output .= "            <option value=\"Pacific/Kwajalein\">Pacific/Kwajalein</option>\n";
  $output .= "            <option value=\"Pacific/Majuro\">Pacific/Majuro</option>\n";
  $output .= "            <option value=\"Pacific/Marquesas\">Pacific/Marquesas</option>\n";
  $output .= "            <option value=\"Pacific/Midway\">Pacific/Midway</option>\n";
  $output .= "            <option value=\"Pacific/Nauru\">Pacific/Nauru</option>\n";
  $output .= "            <option value=\"Pacific/Niue\">Pacific/Niue</option>\n";
  $output .= "            <option value=\"Pacific/Norfolk\">Pacific/Norfolk</option>\n";
  $output .= "            <option value=\"Pacific/Noumea\">Pacific/Noumea</option>\n";
  $output .= "            <option value=\"Pacific/Pago_Pago\">Pacific/Pago Pago</option>\n";
  $output .= "            <option value=\"Pacific/Palau\">Pacific/Palau</option>\n";
  $output .= "            <option value=\"Pacific/Pitcairn\">Pacific/Pitcairn</option>\n";
  $output .= "            <option value=\"Pacific/Pohnpei\">Pacific/Pohnpei</option>\n";
  $output .= "            <option value=\"Pacific/Port_Moresby\">Pacific/Port Moresby</option>\n";
  $output .= "            <option value=\"Pacific/Rarotonga\">Pacific/Rarotonga</option>\n";
  $output .= "            <option value=\"Pacific/Saipan\">Pacific/Saipan</option>\n";
  $output .= "            <option value=\"Pacific/Tahiti\">Pacific/Tahiti</option>\n";
  $output .= "            <option value=\"Pacific/Tarawa\">Pacific/Tarawa</option>\n";
  $output .= "            <option value=\"Pacific/Tongatapu\">Pacific/Tongatapu</option>\n";
  $output .= "            <option value=\"Pacific/Wake\">Pacific/Wake</option>\n";
  $output .= "            <option value=\"Pacific/Wallis\">Pacific/Wallis</option>\n";
  $output .= "            <option value=\"UTC\">UTC</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile area code field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_areacode\" class=\"control-label col-xs-2\">" . __ ( "Area code") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"AreaCode\" class=\"form-control\" id=\"profile_add_areacode\" placeholder=\"" . __ ( "Profile area code") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile language selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Language\" id=\"profile_add_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile language") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Gateways panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"profile_add_tab_gateways\">\n";

  // Add profile non geographic gateway selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_nggw\" class=\"control-label col-xs-2\">" . __ ( "Non geographic gateway") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"NGGW\" id=\"profile_add_nggw\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway to non geographic calls") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile blocked gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_blocked\" class=\"control-label col-xs-2\">" . __ ( "Blocked gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"blockeds_list\" id=\"profile_add_blocked\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"profile_add_blocked_rightAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable all gateways") . "\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_add_blocked_rightSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable selected gateway(s)") . "\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_add_blocked_leftSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable selected gateway(s)") . "\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_add_blocked_leftAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable all gateways") . "\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"Blockeds\" id=\"profile_add_blocked_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile allowed gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_add_gateways\" class=\"control-label col-xs-2\">" . __ ( "Allowed gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"gateways_list\" id=\"profile_add_gateways\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"profile_add_gateways_rightAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable all gateways") . "\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_add_gateways_rightSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable selected gateway(s)") . "\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_add_gateways_leftSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable selected gateway(s)") . "\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_add_gateways_leftAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable all gateways") . "\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_add_gateways_move_up\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Priorize selected gateway(s)") . "\"><i class=\"fa fa-angle-up\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_add_gateways_move_down\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Unpriorize selected gateway(s)") . "\"><i class=\"fa fa-angle-down\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"Gateways\" id=\"profile_add_gateways_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/profiles\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#profile_add_country').select2 (\n" .
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
              "$('#profile_add_language').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/locales', fields: 'Code,Name', formatID: 'Code', formatText: '%Name%'})\n" .
              "});\n" .
              "$('#profile_add_moh').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/audios', fields: 'ID,Description', formatText: '%Description%'})\n" .
              "});\n" .
              "$('#profile_add_timezone_image').timezonePicker (\n" .
              "{\n" .
              "  changeHandler: function ( timezoneName, countryName, offset)\n" .
              "                 {\n" .
              "                   if ( $('#profile_add_country').val () != countryName)\n" .
              "                   {\n" .
              "                     $('#profile_add_country').val ( countryName).trigger ( 'change');\n" .
              "                   }\n" .
              "                   if ( $('#profile_add_timezone').val () != timezoneName)\n" .
              "                   {\n" .
              "                     $('#profile_add_timezone').val ( timezoneName).trigger ( 'change');\n" .
	      "                   }\n" .
              "                   $('#profile_add_offset').val ( offset);\n" .
              "                 }\n" .
              "}).timezonePicker ( 'detectLocation');\n" .
              "$('#profile_add_timezone').select2 ().on ( 'change', function ( e)\n" .
              "{\n" .
              "  $('#profile_add_timezone_image').timezonePicker ( 'updateTimezone', $('#profile_add_timezone').val ());\n" .
              "});\n" .
              "$('#profile_add_nggw').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/gateways', fields: 'ID,Description,Active', filter: function ( results, fields)\n" .
              "        {\n" .
              "          var result = new Array ();\n" .
              "          for ( var x = 0; x < results.length; x++)\n" .
              "          {\n" .
              "            result.push ( { id: results[x].Active ? results[x].ID : results[x].ID * -1, text: results[x].Description});\n" .
              "          }\n" .
              "          return result;\n" .
              "        }})\n" .
              "}).on ( 'select2:open', function ( e)\n" .
              "{\n" .
              "  $('#profile_add_nggw').find ( 'option').each ( function ( index, option)\n" .
              "  {\n" .
              "    if ( $(option).val () < 0)\n" .
              "    {\n" .
              "      setTimeout ( function () { $('#select2-profile_add_nggw-results').find ( 'li.select2-results__option').eq ( index - 1).addClass ( 'select2-inactive');}, 0);\n" .
              "    }\n" .
              "  });\n" .
              "}).on ( 'change', function ( e)\n" .
              "{\n" .
              "  $('#select2-profile_add_nggw-container').removeClass ( 'select2-inactive');\n" .
              "  if ( $(e.target).val () < 0)\n" .
              "  {\n" .
              "    $('#select2-profile_add_nggw-container').addClass ( 'select2-inactive');\n" .
              "  }\n" .
              "});\n" .
              "$('#profile_add_blocked').multiselect (\n" .
              "{\n" .
              "  afterMoveToRight: function ( left, right, options)\n" .
              "                    {\n" .
              "                      $(options).each ( function ()\n" .
              "                      {\n" .
              "                        if ( $('#profile_add_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').length == 1)\n" .
              "                        {\n" .
              "                          $('#profile_add_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').detach ().prependTo ( $('#profile_add_gateways'));\n" .
              "                        }\n" .
              "                        $('#profile_add_gateways,#profile_add_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').attr ( 'disabled', 'disabled');\n" .
              "                      });\n" .
              "                    },\n" .
              "  afterMoveToLeft: function ( left, right, options)\n" .
              "                   {\n" .
              "                     $(options).each ( function ()\n" .
              "                     {\n" .
              "                       $('#profile_add_gateways,#profile_add_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').removeAttr ( 'disabled');\n" .
              "                     });\n" .
              "                   }\n" .
              "});\n" .
              "$('#profile_add_gateways').multiselect (\n" .
              "{\n" .
              "  afterMoveToRight: function ( left, right, options)\n" .
              "                    {\n" .
              "                      $(right).find ( 'option').each ( function ()\n" .
              "                      {\n" .
              "                        if ( $(this).attr ( 'disabled'))\n" .
              "                        {\n" .
              "                          $(this).detach ().prependTo ( $(left));\n" .
              "                        }\n" .
              "                      });\n" .
              "                    }\n" .
              "});\n" .
              "$('#profile_add_areacode').mask ( '0#');\n" .
              "$('#profile_add_prefix').mask ( '0');\n" .
              "$('#profile_add_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "var gateways = VoIP.APIsearch ( { path: '/gateways', fields: 'ID,Description,Active', filter: function ( results, fields)\n" .
              "               {\n" .
              "                 var result = new Array ();\n" .
              "                 for ( var x = 0; x < results.length; x++)\n" .
              "                 {\n" .
              "                   result.push ( { id: results[x].Active ? results[x].ID : results[x].ID * -1, text: results[x].Description});\n" .
              "                 }\n" .
              "                 return result;\n" .
              "               }});\n" .
              "if ( typeof gateways === 'object')\n" .
              "{\n" .
              "  for ( var gateway in gateways)\n" .
              "  {\n" .
              "    if ( ! gateways.hasOwnProperty ( gateway))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    if ( gateways[gateway].id < 0)\n" .
              "    {\n" .
              "      $('<option value=\"' + gateways[gateway].id + '\" class=\"select2-inactive\">' + gateways[gateway].text + '</option>').appendTo ( '#profile_add_gateways');\n" .
              "      $('<option value=\"' + gateways[gateway].id + '\" class=\"select2-inactive\">' + gateways[gateway].text + '</option>').appendTo ( '#profile_add_blocked');\n" .
              "    } else {\n" .
              "      $('<option value=\"' + gateways[gateway].id + '\">' + gateways[gateway].text + '</option>').appendTo ( '#profile_add_gateways');\n" .
              "      $('<option value=\"' + gateways[gateway].id + '\">' + gateways[gateway].text + '</option>').appendTo ( '#profile_add_blocked');\n" .
              "    }\n" .
              "  }\n" .
              "} else {\n" .
              "  new PNotify ( { title: '" . __ ( "Profile addition") . "', text: '" . __ ( "Error requesting gateways!") . "', type: 'error'});\n" .
              "}\n" .
              "$('#profile_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#profile_add_description'),\n" .
              "    URL: '/profiles',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Profile addition") . "',\n" .
              "    fail: '" . __ ( "Error adding profile!") . "',\n" .
              "    success: '" . __ ( "Profile added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/profiles', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#profile_add_form').data ( 'formData');\n" .
              "  delete ( formData.blockeds_list);\n" .
              "  if ( typeof ( formData.Blockeds) == 'string')\n" .
              "  {\n" .
              "    var tmp = formData.Blockeds;\n" .
              "    formData.Blockeds = new Array ();\n" .
              "    formData.Blockeds.push ( tmp);\n" .
              "  }\n" .
              "  delete ( formData.gateways_list);\n" .
              "  if ( typeof ( formData.Gateways) == 'string')\n" .
              "  {\n" .
              "    var tmp = formData.Gateways;\n" .
              "    formData.Gateways = new Array ();\n" .
              "    formData.Gateways.push ( tmp);\n" .
              "  }\n" .
              "  $('#profile_add_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}

/**
 * Function to generate the profile clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/profiles/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/profiles/add', true, function ()\n" .
              "  {\n" .
              "    $('#profile_add_description').val ( data.Description);\n" .
              "    $('#profile_add_domain').val ( data.Domain);\n" .
              "    $('#profile_add_country').val ( data.Country['ISO3166-2']).trigger ( 'change');\n" .
              "    $('#profile_add_timezone_image').data ( 'origval', data.TimeZone);\n" .
              "    $('#profile_add_timezone').val ( data.TimeZone).trigger ( 'change');\n" .
              "    $('#profile_add_offset').val ( data.Offset);\n" .
              "    $('#profile_add_areacode').val ( data.AreaCode);\n" .
              "    $('#profile_add_language').val ( data.Language.Code).trigger ( 'change');\n" .
              "    $('#profile_add_prefix').val ( data.Prefix);\n" .
              "    $('#profile_add_moh').val ( data.MOH.ID).trigger ( 'change');\n" .
              "    $('#profile_add_emergency').bootstrapToggle ( data.Emergency ? 'on' : 'off');\n" .
              "    $('#profile_add_nggw').val ( data.NGGW.ID).trigger ( 'change');\n" .
              "    for ( var id in data.Gateways)\n" .
              "    {\n" .
              "      if ( ! data.Gateways.hasOwnProperty ( id))\n" .
              "      {\n" .
              "        continue;\n" .
              "      }\n" .
              "      $('#profile_add_gateways').find ( 'option[value=\"' + data.Gateways[id].ID + '\"]').remove().appendTo ( $('#profile_add_gateways_to'));\n" .
              "    }\n" .
              "    for ( var id in data.Blockeds)\n" .
              "    {\n" .
              "      if ( ! data.Blockeds.hasOwnProperty ( id))\n" .
              "      {\n" .
              "        continue;\n" .
              "      }\n" .
              "      $('#profile_add_blocked').find ( 'option[value=\"' + data.Blockeds[id].ID + '\"]').remove().appendTo ( $('#profile_add_blocked_to'));\n" .
              "    }\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Profile cloning") . "', text: '" . __ ( "Error requesting profile data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the profile view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Profiles"));
  sys_set_subtitle ( __ ( "profiles visualization"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Profiles"), "link" => "/profiles"),
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
  $output = "<form class=\"form-horizontal\" id=\"profile_view_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#profile_view_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#profile_view_tab_geographical\">" . __ ( "Geographical") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#profile_view_tab_gateways\">" . __ ( "Gateways") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"profile_view_tab_basic\">\n";

  // Add profile description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"profile_view_description\" placeholder=\"" . __ ( "Profile description") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile domain field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Domain\" class=\"form-control\" id=\"profile_view_domain\" placeholder=\"" . __ ( "Profile domain") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile external prefix field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_prefix\" class=\"control-label col-xs-2\">" . __ ( "External prefix") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Prefix\" class=\"form-control\" id=\"profile_view_prefix\" placeholder=\"" . __ ( "Prefix to access PSTN calls") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile music on hold selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_moh\" class=\"control-label col-xs-2\">" . __ ( "Music on hold") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"MOH\" id=\"profile_view_moh\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile music on hold") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile emergency numbers shortcut option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_emergency\" class=\"control-label col-xs-2\">" . __ ( "Emergency shortcut") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Emergency\" id=\"profile_view_emergency\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Geographical panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"profile_view_tab_geographical\">\n";

  // Add timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Map") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"hidden\" name=\"Offset\" id=\"profile_view_offset\" value=\"\">\n";
  $output .= "          <img id=\"profile_view_timezone_image\" src=\"/vendors/timezonepicker/images/living-600.jpg\" width=\"600\" height=\"300\" usemap=\"#profile_view_timezone_map\" />\n";
  $output .= "          <img class=\"timezone-pin\" src=\"/vendors/timezonepicker/images/pin.png\" style=\"padding-top: 4px;\" />\n";
  $output .= "          <map name=\"profile_view_timezone_map\" id=\"profile_view_timezone_map\">\n";
  $output .= "            <area data-timezone=\"Africa/Abidjan\" data-country=\"CI\" data-pin=\"293,141\" data-offset=\"0\" shape=\"poly\" coords=\"290,142,287,143,288,140,286,139,286,137,287,137,286,136,287,136,286,133,290,132,290,133,291,133,293,134,295,133,296,136,295,139,295,141,290,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Accra\" data-country=\"GH\" data-pin=\"300,141\" data-offset=\"0\" shape=\"poly\" coords=\"301,140,297,142,295,142,295,141,295,139,296,136,295,132,300,132,301,136,301,139,302,140,301,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Addis_Ababa\" data-country=\"ET\" data-pin=\"365,135\" data-offset=\"3\" shape=\"poly\" coords=\"375,142,373,142,370,143,368,143,366,144,360,142,358,139,355,137,355,136,357,136,357,132,358,132,359,129,360,129,361,126,363,127,363,125,364,126,368,126,371,129,370,132,372,132,371,132,372,134,380,137,375,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Algiers\" data-country=\"DZ\" data-pin=\"305,89\" data-offset=\"1\" shape=\"poly\" coords=\"316,100,317,104,317,106,316,106,317,109,319,110,320,111,310,118,306,118,305,117,303,116,300,113,286,105,286,102,286,102,291,100,292,99,294,98,294,97,295,97,295,96,298,96,298,96,297,95,297,92,296,92,305,89,314,88,314,89,314,92,313,94,315,97,316,100\" />\n";
  $output .= "            <area data-timezone=\"Africa/Asmara\" data-country=\"ER\" data-pin=\"365,124\" data-offset=\"3\" shape=\"poly\" coords=\"367,125,372,129,371,129,367,126,364,126,363,125,363,127,361,126,362,122,364,120,366,125,366,124,367,125\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bamako\" data-country=\"ML\" data-pin=\"287,129\" data-offset=\"0\" shape=\"poly\" coords=\"293,129,292,130,291,130,291,133,290,133,290,132,288,133,287,133,287,133,286,132,286,132,286,131,285,129,282,130,281,130,281,128,280,126,281,124,282,125,284,124,291,124,289,108,292,108,302,115,303,116,305,117,305,118,307,118,307,122,306,124,299,125,295,127,294,128,293,128,293,129\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bangui\" data-country=\"CF\" data-pin=\"331,143\" data-offset=\"1\" shape=\"poly\" coords=\"341,142,339,142,338,142,337,143,332,141,331,143,331,144,328,144,327,146,325,142,324,140,326,137,331,137,331,135,334,135,336,132,338,132,339,134,339,135,340,135,340,136,342,137,346,142,343,141,342,142,341,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Banjul\" data-country=\"GM\" data-pin=\"272,128\" data-offset=\"0\" shape=\"poly\" coords=\"277,127,272,128,275,127,277,127\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bissau\" data-country=\"GW\" data-pin=\"274,130\" data-offset=\"0\" shape=\"poly\" coords=\"274,131,274,131,274,131\" />\n";
  $output .= "            <area data-timezone=\"Africa/Blantyre\" data-country=\"MW\" data-pin=\"358,176\" data-offset=\"2\" shape=\"poly\" coords=\"358,172,360,175,359,179,357,177,358,174,356,174,354,173,356,171,355,168,356,168,355,166,358,167,358,169,357,171,358,172\" />\n";
  $output .= "            <area data-timezone=\"Africa/Brazzaville\" data-country=\"CG\" data-pin=\"325,157\" data-offset=\"1\" shape=\"poly\" coords=\"319,157,320,156,319,154,321,154,321,153,323,154,324,153,324,151,323,150,324,148,323,148,322,148,322,146,327,147,328,144,331,144,330,151,327,154,327,157,324,158,324,157,322,158,321,157,320,158,319,157\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bujumbura\" data-country=\"BI\" data-pin=\"349,156\" data-offset=\"2\" shape=\"poly\" coords=\"350,157,349,157,348,154,349,155,350,154,351,154,351,155,350,157\" />\n";
  $output .= "            <area data-timezone=\"Asia/Oral\" data-country=\"KZ\" data-pin=\"386,65\" data-offset=\"5\" shape=\"poly\" coords=\"379,66,381,67,381,67,381,66,382,65,385,64,387,64,391,65,391,67,388,69,386,69,382,70,377,69,379,66\" />\n";
  $output .= "            <area data-timezone=\"Africa/Cairo\" data-country=\"EG\" data-pin=\"352,100\" data-offset=\"2\" shape=\"poly\" coords=\"353,113,342,113,341,100,342,97,348,99,352,97,353,97,353,98,357,98,358,101,357,104,355,102,354,100,354,101,360,110,359,110,359,111,357,114,353,113\" />\n";
  $output .= "            <area data-timezone=\"Africa/Casablanca\" data-country=\"MA\" data-pin=\"287,94\" data-offset=\"0\" shape=\"poly\" coords=\"290,100,288,101,286,102,286,104,278,104,283,101,284,100,284,98,285,96,289,93,290,90,291,90,293,91,295,91,297,92,298,96,295,96,295,97,294,97,294,98,292,99,290,100\" />\n";
  $output .= "            <area data-timezone=\"Africa/Ceuta\" data-country=\"ES\" data-pin=\"291,90\" data-offset=\"1\" shape=\"poly\" coords=\"291,90,291,90,291,90\" />\n";
  $output .= "            <area data-timezone=\"Africa/Conakry\" data-country=\"GN\" data-pin=\"277,134\" data-offset=\"0\" shape=\"poly\" coords=\"285,137,284,138,284,136,282,136,283,136,281,133,279,134,278,135,277,133,276,133,275,131,277,130,277,129,281,129,282,130,285,129,286,131,286,132,286,132,287,133,286,134,287,136,286,136,286,137,285,137\" />\n";
  $output .= "            <area data-timezone=\"Africa/Dakar\" data-country=\"SN\" data-pin=\"271,126\" data-offset=\"0\" shape=\"poly\" coords=\"272,128,275,127,277,128,275,127,272,127,271,125,273,122,276,122,280,126,281,129,275,129,272,129,273,129,272,129,272,128\" />\n";
  $output .= "            <area data-timezone=\"Africa/Dar_es_Salaam\" data-country=\"TZ\" data-pin=\"365,161\" data-offset=\"3\" shape=\"poly\" coords=\"367,167,367,167,367,168,362,170,358,169,357,166,352,164,349,161,349,158,351,155,351,155,351,153,351,152,357,152,363,155,363,156,365,158,365,161,366,162,365,164,367,167\" />\n";
  $output .= "            <area data-timezone=\"Asia/Yekaterinburg\" data-country=\"RU\" data-pin=\"401,55\" data-offset=\"6\" shape=\"poly\" coords=\"400,63,403,64,402,65,399,66,397,65,395,65,393,66,391,65,391,66,389,64,385,64,387,62,387,60,389,60,389,58,389,58,390,57,389,57,391,56,390,55,391,55,389,52,390,52,389,51,390,50,387,50,386,49,392,49,394,47,399,47,399,42,410,38,410,37,410,37,409,37,409,36,408,35,410,35,411,35,409,35,414,36,415,35,414,34,411,34,412,33,412,32,411,32,414,30,416,28,421,29,420,31,421,32,421,33,421,35,423,36,420,39,415,39,415,39,420,40,425,37,424,35,427,35,429,36,428,37,429,38,433,38,429,37,430,36,429,35,423,35,423,34,424,32,422,31,425,30,425,29,426,30,425,31,426,31,431,32,427,30,430,30,429,30,430,29,434,30,432,31,434,32,435,33,432,34,437,35,438,36,436,37,437,37,437,38,439,39,439,40,441,41,440,42,443,42,443,43,442,44,443,45,441,46,443,47,443,48,437,49,429,49,428,51,424,53,419,53,418,52,417,54,419,55,418,56,417,58,415,58,409,59,402,60,402,61,404,62,402,62,401,62,402,63,400,63\" />\n";
  $output .= "            <area data-timezone=\"Africa/Djibouti\" data-country=\"DJ\" data-pin=\"372,131\" data-offset=\"3\" shape=\"poly\" coords=\"372,130,372,130,372,130\" />\n";
  $output .= "            <area data-timezone=\"Africa/Douala\" data-country=\"CM\" data-pin=\"316,143\" data-offset=\"1\" shape=\"poly\" coords=\"324,140,325,142,327,145,327,147,324,146,317,146,316,143,315,143,314,142,315,140,316,139,318,138,319,139,320,138,323,132,324,131,323,128,325,129,325,132,326,133,323,134,326,137,324,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Freetown\" data-country=\"SL\" data-pin=\"278,136\" data-offset=\"0\" shape=\"poly\" coords=\"281,138,281,138,278,136,278,135,279,134,281,133,282,134,282,136,283,136,281,138\" />\n";
  $output .= "            <area data-timezone=\"Africa/Gaborone\" data-country=\"BW\" data-pin=\"343,191\" data-offset=\"2\" shape=\"poly\" coords=\"345,190,342,193,338,192,337,194,334,195,335,193,333,191,333,187,335,187,335,181,339,180,339,181,342,180,344,183,346,184,347,186,349,187,345,190\" />\n";
  $output .= "            <area data-timezone=\"Africa/Harare\" data-country=\"ZW\" data-pin=\"352,180\" data-offset=\"2\" shape=\"poly\" coords=\"352,187,347,186,346,184,344,183,342,180,345,180,349,176,355,178,355,182,355,183,354,186,352,187\" />\n";
  $output .= "            <area data-timezone=\"Africa/El_Aaiun\" data-country=\"EH\" data-pin=\"278,105\" data-offset=\"0\" shape=\"poly\" coords=\"280,107,280,111,278,112,278,114,272,114,272,115,272,114,274,110,273,111,275,109,276,106,277,105,278,104,286,104,286,107,280,107\" />\n";
  $output .= "            <area data-timezone=\"Africa/Johannesburg\" data-country=\"ZA\" data-pin=\"347,194\" data-offset=\"2\" shape=\"poly\" coords=\"339,207,333,208,331,207,331,207,330,205,330,204,330,203,327,198,328,197,329,198,330,198,333,197,333,191,335,193,334,195,336,195,338,192,342,193,345,189,348,187,352,187,353,191,353,193,352,193,351,195,353,196,353,196,353,195,355,195,354,198,347,205,343,207,339,207\" />\n";
  $output .= "            <area data-timezone=\"Africa/Juba\" data-country=\"SS\" data-pin=\"353,142\" data-offset=\"3\" shape=\"poly\" coords=\"358,140,359,141,360,141,360,142,357,142,356,144,351,144,349,142,347,143,346,142,344,139,340,135,342,133,343,133,344,134,348,134,350,133,352,134,354,132,353,130,355,130,355,132,357,133,357,136,355,136,355,137,357,137,358,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Kampala\" data-country=\"UG\" data-pin=\"354,149\" data-offset=\"3\" shape=\"poly\" coords=\"352,152,349,152,350,149,352,146,351,146,351,144,356,144,357,143,358,147,357,150,357,152,352,152\" />\n";
  $output .= "            <area data-timezone=\"Africa/Khartoum\" data-country=\"SD\" data-pin=\"354,124\" data-offset=\"3\" shape=\"poly\" coords=\"360,129,358,132,357,132,357,134,355,132,355,130,355,130,353,130,354,132,352,134,350,133,348,134,344,134,343,133,342,133,341,135,339,135,339,134,338,132,337,129,336,129,338,124,340,124,340,117,342,117,342,113,352,113,357,114,359,111,361,113,362,115,362,119,364,120,362,122,361,127,360,129,360,129\" />\n";
  $output .= "            <area data-timezone=\"Africa/Kinshasa\" data-country=\"CD\" data-pin=\"326,157\" data-offset=\"1\" shape=\"poly\" coords=\"325,158,327,157,327,154,330,151,331,143,332,142,333,141,334,143,339,144,338,144,339,146,338,146,337,147,339,150,338,151,339,151,339,151,341,153,338,153,337,153,337,154,335,154,334,157,333,157,333,162,333,162,332,163,329,164,328,160,320,160,322,158,322,158,324,157,324,158,325,158\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lagos\" data-country=\"NG\" data-pin=\"306,139\" data-offset=\"1\" shape=\"poly\" coords=\"313,143,310,143,308,139,305,139,305,135,306,132,306,131,307,128,309,127,312,128,313,128,316,129,318,128,321,128,323,127,324,130,324,131,323,132,320,138,319,139,317,138,315,140,314,142,313,143\" />\n";
  $output .= "            <area data-timezone=\"Africa/Libreville\" data-country=\"GA\" data-pin=\"316,149\" data-offset=\"1\" shape=\"poly\" coords=\"323,150,324,151,324,154,321,153,321,154,319,154,320,156,319,157,316,154,314,151,315,151,316,149,317,150,316,149,316,148,319,148,319,146,322,146,322,148,324,148,324,149,323,150\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lome\" data-country=\"TG\" data-pin=\"302,140\" data-offset=\"0\" shape=\"poly\" coords=\"302,140,301,139,301,136,300,131,302,132,301,133,302,133,303,140,302,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Kigali\" data-country=\"RW\" data-pin=\"350,153\" data-offset=\"2\" shape=\"poly\" coords=\"350,154,350,155,348,154,349,153,351,152,351,154,350,154\" />\n";
  $output .= "            <area data-timezone=\"Africa/Luanda\" data-country=\"AO\" data-pin=\"322,165\" data-offset=\"1\" shape=\"poly\" coords=\"337,168,337,169,340,168,340,172,337,172,337,177,339,179,335,180,331,179,323,179,322,178,320,179,321,172,323,170,323,168,322,165,322,164,321,160,328,160,329,164,332,163,333,162,336,162,336,166,337,168\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lubumbashi\" data-country=\"CD\" data-pin=\"346,169\" data-offset=\"2\" shape=\"poly\" coords=\"349,158,349,161,351,164,348,164,347,166,348,166,347,169,348,171,350,170,350,172,348,172,345,169,345,170,343,170,342,169,341,169,340,168,337,169,336,162,333,162,333,160,334,159,333,157,334,157,335,154,337,154,337,153,338,153,341,153,339,151,339,151,338,151,339,150,337,147,338,146,339,146,338,144,339,144,337,143,338,142,342,142,343,141,345,141,347,143,349,142,352,144,351,146,352,146,350,149,349,152,348,154,349,158\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lusaka\" data-country=\"ZM\" data-pin=\"347,176\" data-offset=\"2\" shape=\"poly\" coords=\"348,177,348,178,344,180,341,179,339,179,337,177,337,172,340,172,340,168,341,169,342,169,343,170,345,170,345,169,348,172,350,172,350,170,348,171,347,170,348,166,347,166,348,164,351,164,355,166,356,168,355,168,356,171,354,173,355,173,350,175,351,176,348,177\" />\n";
  $output .= "            <area data-timezone=\"Africa/Malabo\" data-country=\"GQ\" data-pin=\"315,144\" data-offset=\"1\" shape=\"poly\" coords=\"319,147,319,148,316,148,316,146,319,146,319,147\" />\n";
  $output .= "            <area data-timezone=\"Africa/Maputo\" data-country=\"MZ\" data-pin=\"354,193\" data-offset=\"2\" shape=\"poly\" coords=\"355,192,354,193,354,194,355,193,355,195,354,195,353,191,352,187,354,186,355,183,355,182,355,178,351,177,350,175,355,173,356,174,357,174,358,175,357,177,359,179,360,175,358,172,358,169,362,170,368,167,368,175,366,177,363,179,361,181,358,183,358,184,359,187,359,190,355,192\" />\n";
  $output .= "            <area data-timezone=\"Africa/Mbabane\" data-country=\"SZ\" data-pin=\"352,194\" data-offset=\"2\" shape=\"poly\" coords=\"353,193,354,195,353,196,351,194,352,193,353,193\" />\n";
  $output .= "            <area data-timezone=\"Africa/Mogadishu\" data-country=\"SO\" data-pin=\"376,147\" data-offset=\"3\" shape=\"poly\" coords=\"371,150,369,153,368,151,368,145,370,143,375,142,380,137,373,135,371,132,372,131,374,133,385,130,385,133,386,133,385,133,385,134,380,143,371,150\" />\n";
  $output .= "            <area data-timezone=\"Africa/Monrovia\" data-country=\"LR\" data-pin=\"282,140\" data-offset=\"0\" shape=\"poly\" coords=\"287,142,287,143,286,142,281,139,283,136,284,136,284,138,285,138,286,137,286,138,286,139,288,140,287,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Nairobi\" data-country=\"KE\" data-pin=\"361,152\" data-offset=\"3\" shape=\"poly\" coords=\"369,153,367,155,365,158,363,156,363,155,357,152,357,150,358,147,357,143,357,142,360,142,366,144,368,143,370,143,368,145,368,151,369,153\" />\n";
  $output .= "            <area data-timezone=\"Africa/Maseru\" data-country=\"LS\" data-pin=\"346,199\" data-offset=\"2\" shape=\"poly\" coords=\"347,200,346,201,345,199,348,198,349,199,347,200\" />\n";
  $output .= "            <area data-timezone=\"Africa/Ndjamena\" data-country=\"TD\" data-pin=\"325,130\" data-offset=\"1\" shape=\"poly\" coords=\"333,135,331,135,331,137,326,138,323,134,324,133,326,133,325,132,325,129,323,128,322,126,326,122,327,116,325,114,325,112,327,111,340,117,340,124,338,124,336,129,337,129,338,131,336,132,335,134,333,135\" />\n";
  $output .= "            <area data-timezone=\"Africa/Niamey\" data-country=\"NE\" data-pin=\"304,127\" data-offset=\"1\" shape=\"poly\" coords=\"308,127,306,129,306,130,305,129,304,130,303,129,302,128,302,128,301,127,300,125,306,124,307,122,307,118,310,118,320,111,324,112,325,112,325,114,327,116,326,122,322,126,323,127,321,128,318,128,316,129,313,128,312,128,309,127,308,127\" />\n";
  $output .= "            <area data-timezone=\"Africa/Nouakchott\" data-country=\"MR\" data-pin=\"273,120\" data-offset=\"0\" shape=\"poly\" coords=\"280,124,280,125,276,122,273,122,272,123,273,120,272,118,273,116,272,115,272,115,278,114,278,112,280,111,280,107,286,107,286,105,292,108,289,108,291,124,284,124,282,125,280,124\" />\n";
  $output .= "            <area data-timezone=\"Africa/Ouagadougou\" data-country=\"BF\" data-pin=\"297,129\" data-offset=\"0\" shape=\"poly\" coords=\"299,132,295,132,295,134,295,133,292,134,291,133,291,130,292,130,293,128,294,128,296,126,300,125,300,125,301,127,302,128,302,128,303,129,304,130,302,132,299,132\" />\n";
  $output .= "            <area data-timezone=\"Africa/Porto-Novo\" data-country=\"BJ\" data-pin=\"304,139\" data-offset=\"1\" shape=\"poly\" coords=\"305,137,305,139,303,140,303,135,301,133,302,131,303,131,305,129,306,130,306,132,305,135,305,137\" />\n";
  $output .= "            <area data-timezone=\"Africa/Tunis\" data-country=\"TN\" data-pin=\"317,89\" data-offset=\"1\" shape=\"poly\" coords=\"319,96,317,97,317,99,316,100,315,97,313,95,313,93,314,91,314,89,315,88,317,88,317,89,318,88,317,90,319,91,317,93,319,95,319,96\" />\n";
  $output .= "            <area data-timezone=\"Africa/Sao_Tome\" data-country=\"ST\" data-pin=\"311,149\" data-offset=\"0\" shape=\"poly\" coords=\"312,147,312,147,312,147\" />\n";
  $output .= "            <area data-timezone=\"Africa/Tripoli\" data-country=\"LY\" data-pin=\"322,95\" data-offset=\"2\" shape=\"poly\" coords=\"342,105,342,117,340,117,340,117,327,111,324,112,317,109,316,106,317,106,317,104,316,100,317,99,317,97,319,96,319,95,325,96,326,98,332,100,333,99,334,96,337,95,339,96,342,97,341,100,342,105\" />\n";
  $output .= "            <area data-timezone=\"Africa/Windhoek\" data-country=\"NA\" data-pin=\"329,188\" data-offset=\"2\" shape=\"poly\" coords=\"333,196,333,197,332,198,329,198,328,197,327,198,326,197,325,194,324,188,320,181,320,179,322,178,323,179,331,179,335,180,340,179,342,180,339,181,339,180,335,181,335,187,333,187,333,196\" />\n";
  $output .= "            <area data-timezone=\"America/Adak\" data-country=\"US\" data-pin=\"6,64\" data-offset=\"-10\" shape=\"poly\" coords=\"8,63,8,63,10,63,8,63\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Salta\" data-country=\"AR\" data-pin=\"191,191\" data-offset=\"-3\" shape=\"poly\" coords=\"194,216,194,218,195,218,192,218,192,220,180,220,180,216,182,214,181,212,182,210,184,212,186,213,186,210,192,210,192,208,194,208,194,216\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Salta\" data-country=\"AR\" data-pin=\"191,191\" data-offset=\"-3\" shape=\"poly\" coords=\"191,187,193,188,193,187,195,187,196,187,196,191,194,193,189,194,189,193,189,192,186,192,186,191,188,190,189,190,189,189,191,191,193,191,193,189,192,189,191,187\" />\n";
  $output .= "            <area data-timezone=\"America/Anchorage\" data-country=\"US\" data-pin=\"50,48\" data-offset=\"-9\" shape=\"poly\" coords=\"51,50,48,51,47,51,48,50,47,50,48,49,52,49,50,48,51,47,47,48,43,51,45,52,43,53,36,56,36,57,30,58,30,57,33,57,33,56,37,54,37,53,39,52,36,52,36,52,35,53,33,52,30,52,30,44,32,44,31,43,32,42,30,42,30,40,32,40,30,39,30,39,31,39,30,38,30,33,39,31,41,31,40,32,42,31,46,32,47,32,46,32,47,33,61,33,65,34,65,50,64,50,60,50,55,49,56,48,53,48,54,48,52,49,53,49,53,50,53,50,51,50\" />\n";
  $output .= "            <area data-timezone=\"America/Anguilla\" data-country=\"AI\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/Antigua\" data-country=\"AG\" data-pin=\"197,122\" data-offset=\"-4\" shape=\"poly\" coords=\"197,121,197,121,197,121\" />\n";
  $output .= "            <area data-timezone=\"America/Araguaina\" data-country=\"BR\" data-pin=\"220,162\" data-offset=\"-3\" shape=\"poly\" coords=\"222,163,223,163,222,165,224,167,222,169,223,169,223,171,220,172,219,172,218,171,218,172,216,171,216,171,216,171,216,168,218,164,218,162,219,161,220,159,219,159,221,159,220,162,222,163\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Buenos_Aires\" data-country=\"AR\" data-pin=\"203,208\" data-offset=\"-3\" shape=\"poly\" coords=\"200,206,203,207,203,208,205,209,204,210,206,212,203,214,198,215,196,215,197,216,196,215,196,217,196,218,195,218,194,218,194,207,197,207,198,206,200,206\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Catamarca\" data-country=\"AR\" data-pin=\"190,197\" data-offset=\"-3\" shape=\"poly\" coords=\"191,225,188,225,187,227,181,227,181,225,180,225,181,224,180,224,181,223,180,221,191,220,193,221,194,220,194,221,192,221,193,222,191,223,191,225\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Catamarca\" data-country=\"AR\" data-pin=\"190,197\" data-offset=\"-3\" shape=\"poly\" coords=\"192,200,189,197,185,196,186,195,186,192,189,192,189,193,190,195,190,196,191,197,191,197,192,200\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Cordoba\" data-country=\"AR\" data-pin=\"193,202\" data-offset=\"-3\" shape=\"poly\" coords=\"195,207,194,207,194,208,192,208,192,204,190,203,190,202,192,200,191,197,193,193,194,193,196,191,196,187,198,190,204,192,202,196,207,196,209,194,209,193,210,193,210,195,207,197,204,201,203,207,200,205,197,207,195,207\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Jujuy\" data-country=\"AR\" data-pin=\"191,190\" data-offset=\"-3\" shape=\"poly\" coords=\"188,188,190,186,191,187,191,188,192,189,193,189,193,190,192,191,191,191,189,189,189,190,189,190,188,188\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/La_Rioja\" data-country=\"AR\" data-pin=\"189,199\" data-offset=\"-3\" shape=\"poly\" coords=\"187,200,185,199,185,198,184,197,185,196,189,197,191,200,190,203,189,203,187,200\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Mendoza\" data-country=\"AR\" data-pin=\"185,205\" data-offset=\"-3\" shape=\"poly\" coords=\"183,204,187,203,188,205,189,210,186,210,186,213,184,212,183,211,182,209,184,206,183,204\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Rio_Gallegos\" data-country=\"AR\" data-pin=\"185,236\" data-offset=\"-3\" shape=\"poly\" coords=\"181,227,187,227,189,228,190,229,190,230,185,234,185,236,186,237,180,237,179,236,180,234,178,235,177,234,178,232,180,231,179,230,180,229,181,227\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/San_Juan\" data-country=\"AR\" data-pin=\"186,203\" data-offset=\"-3\" shape=\"poly\" coords=\"183,200,184,197,185,198,185,199,187,200,189,203,188,203,188,204,185,203,183,204,182,202,183,200\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/San_Luis\" data-country=\"AR\" data-pin=\"189,206\" data-offset=\"-3\" shape=\"poly\" coords=\"190,203,192,204,192,210,189,210,188,203,190,203\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Tucuman\" data-country=\"AR\" data-pin=\"191,195\" data-offset=\"-3\" shape=\"poly\" coords=\"190,194,193,194,192,196,191,197,190,196,190,195,190,194\" />\n";
  $output .= "            <area data-timezone=\"America/Aruba\" data-country=\"AW\" data-pin=\"183,129\" data-offset=\"-4\" shape=\"poly\" coords=\"183,129,184,129,183,129\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Ushuaia\" data-country=\"AR\" data-pin=\"186,241\" data-offset=\"-3\" shape=\"poly\" coords=\"186,241,186,238,186,239,186,239,188,240,191,241,189,242,186,241\" />\n";
  $output .= "            <area data-timezone=\"America/Asuncion\" data-country=\"PY\" data-pin=\"204,192\" data-offset=\"-3\" shape=\"poly\" coords=\"209,194,207,196,202,195,204,192,198,190,196,187,197,183,200,182,203,183,203,187,207,187,208,190,210,190,209,194\" />\n";
  $output .= "            <area data-timezone=\"America/Bahia_Banderas\" data-country=\"MX\" data-pin=\"125,115\" data-offset=\"-6\" shape=\"poly\" coords=\"125,115,124,115,125,115\" />\n";
  $output .= "            <area data-timezone=\"America/Atikokan\" data-country=\"CA\" data-pin=\"147,69\" data-offset=\"-5\" shape=\"poly\" coords=\"150,70,146,69,147,68,148,68,148,69,150,70\" />\n";
  $output .= "            <area data-timezone=\"America/Bahia\" data-country=\"BR\" data-pin=\"236,172\" data-offset=\"-2\" shape=\"poly\" coords=\"224,175,223,175,223,169,222,169,223,168,224,167,225,168,227,167,227,166,229,166,231,165,232,166,234,164,236,165,237,167,236,168,238,169,235,172,235,176,234,181,232,179,234,177,233,176,231,176,230,175,227,175,226,174,224,175\" />\n";
  $output .= "            <area data-timezone=\"America/Barbados\" data-country=\"BB\" data-pin=\"201,128\" data-offset=\"-4\" shape=\"poly\" coords=\"201,128,201,128,201,128\" />\n";
  $output .= "            <area data-timezone=\"America/Belem\" data-country=\"BR\" data-pin=\"219,152\" data-offset=\"-3\" shape=\"poly\" coords=\"214,151,215,150,215,151,216,150,219,150,219,152,216,153,218,153,218,154,219,152,220,151,223,152,222,156,219,159,220,160,218,162,218,164,216,166,212,166,213,165,212,163,213,161,212,158,212,156,214,155,213,153,214,152,212,151,211,148,209,147,209,146,212,146,214,143,216,146,217,148,214,151\" />\n";
  $output .= "            <area data-timezone=\"America/Belize\" data-country=\"BZ\" data-pin=\"153,121\" data-offset=\"-6\" shape=\"poly\" coords=\"154,121,153,121,154,121\" />\n";
  $output .= "            <area data-timezone=\"America/Blanc-Sablon\" data-country=\"CA\" data-pin=\"205,64\" data-offset=\"-4\" shape=\"poly\" coords=\"202,64,201,66,202,64\" />\n";
  $output .= "            <area data-timezone=\"America/Boa_Vista\" data-country=\"BR\" data-pin=\"199,145\" data-offset=\"-4\" shape=\"poly\" coords=\"200,142,200,142,201,143,200,146,200,147,202,148,202,150,200,150,199,151,198,151,197,152,196,151,196,147,193,146,192,143,195,144,196,143,198,142,199,141,200,142\" />\n";
  $output .= "            <area data-timezone=\"America/Bogota\" data-country=\"CO\" data-pin=\"177,142\" data-offset=\"-5\" shape=\"poly\" coords=\"184,152,183,157,182,156,183,155,183,154,180,154,178,154,175,150,171,149,168,147,172,143,171,143,171,140,170,138,171,137,171,136,172,137,172,136,174,134,175,132,178,131,181,129,181,130,179,132,178,135,179,136,179,138,180,138,183,138,184,140,188,140,187,142,188,144,187,145,188,146,189,148,188,146,184,147,184,148,185,149,183,149,184,152\" />\n";
  $output .= "            <area data-timezone=\"America/Boise\" data-country=\"US\" data-pin=\"106,77\" data-offset=\"-7\" shape=\"poly\" coords=\"115,79,115,80,105,80,105,79,103,79,103,76,105,76,106,74,106,73,106,74,110,74,112,76,115,76,115,79\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"119,22,120,23,119,23,121,23,120,24,123,23,124,24,123,25,117,25,117,24,119,24,117,24,118,23,117,23,119,22\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"130,43,116,42,114,41,111,41,97,36,97,34,107,35,110,36,107,37,108,37,117,37,120,38,119,38,121,39,120,39,122,39,121,38,122,38,120,37,124,36,119,36,119,36,123,35,126,37,128,36,131,37,136,37,136,36,138,36,139,36,139,37,140,36,139,38,141,38,141,37,144,36,144,36,144,35,142,35,144,34,139,33,140,32,139,32,139,31,142,30,141,30,144,30,145,31,145,32,147,33,146,33,146,33,147,33,145,34,149,34,148,34,149,35,149,36,150,36,152,35,152,38,130,38,130,43\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"138,29,139,29,139,30,139,30,136,30,137,31,135,31,129,29,133,29,133,28,138,29\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"120,28,124,28,126,31,126,32,132,33,132,34,127,34,128,34,128,35,130,34,130,35,128,35,125,35,125,35,122,34,118,35,111,36,111,35,111,35,106,34,105,33,117,33,117,28,119,29,120,31,121,30,120,28,120,28\" />\n";
  $output .= "            <area data-timezone=\"America/Campo_Grande\" data-country=\"BR\" data-pin=\"209,184\" data-offset=\"-3\" shape=\"poly\" coords=\"212,180,212,181,215,182,215,184,210,190,208,190,207,187,203,187,204,180,206,179,208,179,211,179,210,180,212,180\" />\n";
  $output .= "            <area data-timezone=\"America/Cancun\" data-country=\"MX\" data-pin=\"155,115\" data-offset=\"-6\" shape=\"poly\" coords=\"154,114,155,115,154,117,154,117,154,120,153,119,152,120,151,120,151,117,154,116,154,114\" />\n";
  $output .= "            <area data-timezone=\"America/Caracas\" data-country=\"VE\" data-pin=\"188,133\" data-offset=\"-4.5\" shape=\"poly\" coords=\"196,134,198,134,198,136,200,136,199,137,200,138,198,139,198,140,199,142,195,143,195,144,192,143,193,146,194,146,191,149,191,148,189,149,189,148,188,146,187,145,188,144,187,142,188,140,184,140,183,138,180,138,179,135,178,135,179,132,181,130,180,131,181,132,180,134,180,135,181,135,182,134,181,132,183,131,183,130,184,131,186,131,186,133,190,132,192,133,194,133,193,132,197,132,195,132,196,134\" />\n";
  $output .= "            <area data-timezone=\"America/Cayenne\" data-country=\"GF\" data-pin=\"213,142\" data-offset=\"-3\" shape=\"poly\" coords=\"211,141,214,143,212,146,209,146,210,144,209,142,210,140,211,141\" />\n";
  $output .= "            <area data-timezone=\"America/Cayman\" data-country=\"KY\" data-pin=\"164,118\" data-offset=\"-5\" shape=\"poly\" coords=\"167,117,167,117,167,117\" />\n";
  $output .= "            <area data-timezone=\"America/Chicago\" data-country=\"US\" data-pin=\"154,80\" data-offset=\"-6\" shape=\"poly\" coords=\"154,86,159,88,159,90,157,92,159,96,158,100,156,99,156,99,155,99,155,99,153,100,153,99,153,99,149,100,151,100,150,101,152,101,151,102,150,101,150,102,144,100,142,101,142,100,141,102,139,103,140,102,139,102,139,103,138,103,138,104,137,104,138,105,138,107,135,106,134,104,131,100,129,100,128,102,126,101,125,99,125,97,128,97,128,88,130,88,130,87,131,87,131,84,130,84,130,83,131,83,131,80,132,80,131,77,133,76,132,75,133,75,133,73,131,73,132,72,132,71,127,71,127,68,141,68,141,68,142,69,151,70,147,72,149,72,148,72,153,73,154,75,153,76,154,75,154,80,154,81,156,81,154,82,154,86\" />\n";
  $output .= "            <area data-timezone=\"America/Chihuahua\" data-country=\"MX\" data-pin=\"123,102\" data-offset=\"-7\" shape=\"poly\" coords=\"128,102,127,104,127,106,126,106,123,105,122,107,118,103,119,103,119,98,120,98,120,97,122,97,125,99,125,101,128,102\" />\n";
  $output .= "            <area data-timezone=\"America/Coral_Harbour\" data-country=\"CA\" data-pin=\"160,42\" data-offset=\"-5\" shape=\"poly\" coords=\"158,40,158,41,159,41,164,42,164,43,163,43,166,44,165,44,162,43,158,45,157,44,155,44,156,43,157,41,158,40\" />\n";
  $output .= "            <area data-timezone=\"America/Costa_Rica\" data-country=\"CR\" data-pin=\"160,133\" data-offset=\"-6\" shape=\"poly\" coords=\"158,134,157,133,157,131,161,132,162,134,162,134,162,137,158,133,158,134\" />\n";
  $output .= "            <area data-timezone=\"America/Creston\" data-country=\"CA\" data-pin=\"106,68\" data-offset=\"-7\" shape=\"poly\" coords=\"107,68,105,68,105,67,107,68\" />\n";
  $output .= "            <area data-timezone=\"America/Cuiaba\" data-country=\"BR\" data-pin=\"207,176\" data-offset=\"-3\" shape=\"poly\" coords=\"214,175,212,178,212,180,210,180,211,179,208,179,206,179,204,180,203,179,203,177,200,177,199,173,200,171,200,169,197,168,197,165,202,165,203,162,204,165,205,166,216,166,215,169,216,171,214,175\" />\n";
  $output .= "            <area data-timezone=\"America/Curacao\" data-country=\"CW\" data-pin=\"185,130\" data-offset=\"-4\" shape=\"poly\" coords=\"185,130,185,129,185,130\" />\n";
  $output .= "            <area data-timezone=\"America/Danmarkshavn\" data-country=\"GL\" data-pin=\"269,22\" data-offset=\"0\" shape=\"poly\" coords=\"268,18,267,18,269,18,265,19,265,19,263,21,265,20,268,20,268,21,265,21,269,21,270,22,262,22,267,23,264,23,268,24,262,23,262,17,268,18\" />\n";
  $output .= "            <area data-timezone=\"America/Dawson\" data-country=\"CA\" data-pin=\"68,43\" data-offset=\"-8\" shape=\"poly\" coords=\"68,43,68,43,68,43\" />\n";
  $output .= "            <area data-timezone=\"America/Dawson_Creek\" data-country=\"CA\" data-pin=\"100,50\" data-offset=\"-7\" shape=\"poly\" coords=\"100,55,100,60,95,58,94,55,100,55\" />\n";
  $output .= "            <area data-timezone=\"America/Denver\" data-country=\"US\" data-pin=\"125,84\" data-offset=\"-7\" shape=\"poly\" coords=\"112,76,110,74,109,74,109,72,107,71,107,68,127,68,127,71,130,71,130,72,132,73,131,73,133,74,133,75,132,75,133,76,131,77,132,80,131,80,131,83,130,83,130,84,131,84,131,87,130,87,130,88,128,88,128,97,125,97,125,99,122,97,118,98,118,91,115,91,114,89,114,88,110,88,110,80,115,80,115,76,112,76\" />\n";
  $output .= "            <area data-timezone=\"America/Detroit\" data-country=\"US\" data-pin=\"162,79\" data-offset=\"-5\" shape=\"poly\" coords=\"159,81,155,80,156,79,156,77,156,76,157,75,158,75,159,74,161,75,161,75,161,76,160,78,162,77,163,78,161,80,159,81\" />\n";
  $output .= "            <area data-timezone=\"America/Detroit\" data-country=\"US\" data-pin=\"162,79\" data-offset=\"-5\" shape=\"poly\" coords=\"150,72,152,71,153,72,156,73,158,72,161,73,157,73,156,74,155,73,154,75,155,74,154,73,151,73,150,72\" />\n";
  $output .= "            <area data-timezone=\"America/Dominica\" data-country=\"DM\" data-pin=\"198,125\" data-offset=\"-4\" shape=\"poly\" coords=\"198,124,198,125,198,124\" />\n";
  $output .= "            <area data-timezone=\"America/Edmonton\" data-country=\"CA\" data-pin=\"111,61\" data-offset=\"-7\" shape=\"poly\" coords=\"110,68,106,68,106,66,102,63,103,63,103,62,100,61,100,50,117,50,117,61,119,62,117,62,117,68,110,68\" />\n";
  $output .= "            <area data-timezone=\"America/Eirunepe\" data-country=\"BR\" data-pin=\"184,161\" data-offset=\"-4\" shape=\"poly\" coords=\"187,166,177,162,178,161,179,159,180,158,183,157,187,166\" />\n";
  $output .= "            <area data-timezone=\"America/El_Salvador\" data-country=\"SV\" data-pin=\"151,127\" data-offset=\"-6\" shape=\"poly\" coords=\"151,127,150,127,151,126,154,127,153,128,151,127\" />\n";
  $output .= "            <area data-timezone=\"America/Fortaleza\" data-country=\"BR\" data-pin=\"236,156\" data-offset=\"-3\" shape=\"poly\" coords=\"242,161,242,163,241,162,238,164,238,163,238,162,236,163,232,162,232,164,230,165,227,166,227,167,225,168,223,167,222,165,223,163,220,162,221,159,219,159,222,156,223,152,224,152,224,153,225,152,226,153,225,156,226,154,226,155,228,154,230,155,233,155,238,158,241,159,242,161\" />\n";
  $output .= "            <area data-timezone=\"America/Glace_Bay\" data-country=\"CA\" data-pin=\"200,73\" data-offset=\"-4\" shape=\"poly\" coords=\"200,73,200,74,199,73,200,73\" />\n";
  $output .= "            <area data-timezone=\"America/Godthab\" data-country=\"GL\" data-pin=\"214,43\" data-offset=\"-3\" shape=\"poly\" coords=\"225,50,225,50,226,49,225,50,226,49,225,49,225,49,223,49,224,48,223,49,225,48,220,49,221,48,219,48,220,48,218,47,219,47,218,47,219,47,218,47,219,47,217,47,218,46,216,46,217,45,216,45,216,45,216,45,217,45,215,45,216,44,215,44,216,44,214,44,215,43,214,43,216,43,214,43,216,43,215,42,216,42,217,43,215,41,216,42,213,43,213,42,215,42,213,42,213,41,212,41,216,40,212,41,212,40,211,41,214,40,211,40,216,39,211,40,211,40,211,39,213,39,211,39,213,39,210,38,216,38,210,38,217,37,213,37,214,37,210,37,211,37,213,37,211,36,216,37,214,36,215,36,211,36,215,36,216,35,214,35,215,35,216,35,215,35,216,35,216,34,215,35,216,34,215,34,216,33,209,32,216,33,214,32,215,32,213,32,215,32,213,31,214,31,212,31,214,31,212,31,214,30,211,30,210,30,210,31,208,31,207,31,209,30,207,30,208,29,207,29,209,29,207,28,208,28,207,27,207,27,206,27,206,26,204,26,206,26,202,24,203,24,203,24,194,23,189,18,192,17,192,17,194,16,188,16,194,15,195,15,194,15,198,15,198,14,199,14,198,14,199,14,206,14,201,13,202,13,209,13,211,13,211,14,212,13,217,14,215,13,218,13,215,12,216,12,226,14,226,14,225,13,226,13,226,13,229,13,224,12,234,12,224,11,236,12,235,12,238,11,235,11,242,11,257,11,241,12,258,11,259,12,257,12,264,12,245,14,259,13,255,14,256,14,265,13,264,14,260,16,268,14,267,14,274,14,281,14,274,16,265,16,274,16,266,16,267,17,271,17,270,17,262,17,262,23,268,24,267,25,263,24,264,24,262,24,266,25,263,25,268,26,263,26,263,26,262,27,264,27,264,27,266,27,266,28,263,28,260,27,263,27,257,27,259,27,254,28,256,28,254,28,254,28,258,28,254,29,259,29,256,29,258,29,257,30,259,29,259,31,252,30,254,30,253,31,258,31,253,31,254,32,251,33,256,33,252,33,254,33,258,33,263,33,256,36,250,37,247,37,246,36,247,37,245,37,242,39,238,40,238,40,237,40,238,39,237,39,237,40,236,40,236,41,234,40,233,41,234,41,233,42,231,41,233,43,231,43,232,43,232,44,231,44,232,44,230,44,231,45,230,45,230,45,228,45,230,46,228,46,230,47,229,47,230,47,228,48,229,48,227,48,229,48,227,49,229,49,226,49,228,50,227,49,225,50\" />\n";
  $output .= "            <area data-timezone=\"America/Goose_Bay\" data-country=\"CA\" data-pin=\"199,61\" data-offset=\"-4\" shape=\"poly\" coords=\"205,63,194,63,193,63,194,62,194,62,193,62,192,64,189,63,189,62,188,62,189,61,187,60,188,59,188,58,189,59,189,58,190,59,193,59,194,58,194,58,194,57,194,57,193,56,194,54,193,53,194,52,192,52,193,52,193,51,192,51,192,50,194,51,193,52,195,52,194,53,196,53,194,53,197,54,196,54,198,55,196,55,197,56,196,56,199,57,199,57,199,58,200,58,200,58,201,58,200,59,201,58,201,59,204,59,201,60,203,60,199,61,204,60,205,60,204,61,205,63\" />\n";
  $output .= "            <area data-timezone=\"America/Grand_Turk\" data-country=\"TC\" data-pin=\"181,114\" data-offset=\"-5\" shape=\"poly\" coords=\"181,114,181,114,181,114\" />\n";
  $output .= "            <area data-timezone=\"America/Grenada\" data-country=\"GD\" data-pin=\"197,130\" data-offset=\"-4\" shape=\"poly\" coords=\"198,129,198,129,198,129\" />\n";
  $output .= "            <area data-timezone=\"America/Guadeloupe\" data-country=\"GP\" data-pin=\"197,123\" data-offset=\"-4\" shape=\"poly\" coords=\"197,123,198,123,197,123\" />\n";
  $output .= "            <area data-timezone=\"America/Guatemala\" data-country=\"GT\" data-pin=\"149,126\" data-offset=\"-6\" shape=\"poly\" coords=\"150,127,147,126,146,125,147,123,149,123,148,121,148,121,148,120,151,120,151,124,153,124,150,127\" />\n";
  $output .= "            <area data-timezone=\"America/Guayaquil\" data-country=\"EC\" data-pin=\"167,154\" data-offset=\"-5\" shape=\"poly\" coords=\"169,156,168,158,167,157,166,157,167,154,166,155,165,154,167,149,169,148,171,149,173,149,175,150,174,150,175,152,169,156\" />\n";
  $output .= "            <area data-timezone=\"America/Guyana\" data-country=\"GY\" data-pin=\"203,139\" data-offset=\"-4\" shape=\"poly\" coords=\"204,140,205,141,203,142,203,143,206,147,204,147,202,148,200,147,200,145,201,143,200,142,200,142,198,140,198,139,200,138,199,137,200,136,200,136,203,138,202,139,203,139,204,140\" />\n";
  $output .= "            <area data-timezone=\"America/Halifax\" data-country=\"CA\" data-pin=\"194,76\" data-offset=\"-4\" shape=\"poly\" coords=\"193,75,194,74,192,74,193,73,196,74,197,74,198,74,193,76,191,78,190,77,190,76,192,74,193,75\" />\n";
  $output .= "            <area data-timezone=\"America/Havana\" data-country=\"CU\" data-pin=\"163,111\" data-offset=\"-5\" shape=\"poly\" coords=\"175,116,176,116,170,117,171,116,170,115,169,114,164,113,163,113,164,112,164,112,158,114,161,112,166,111,175,116\" />\n";
  $output .= "            <area data-timezone=\"America/Hermosillo\" data-country=\"MX\" data-pin=\"115,102\" data-offset=\"-7\" shape=\"poly\" coords=\"108,97,109,96,115,98,119,98,119,103,118,103,119,105,118,106,116,105,116,104,113,102,112,98,108,97\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Petersburg\" data-country=\"US\" data-pin=\"155,86\" data-offset=\"-5\" shape=\"poly\" coords=\"155,86,154,86,155,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Tell_City\" data-country=\"US\" data-pin=\"155,87\" data-offset=\"-6\" shape=\"poly\" coords=\"156,87,156,86,156,87\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Vevay\" data-country=\"US\" data-pin=\"158,85\" data-offset=\"-5\" shape=\"poly\" coords=\"159,85,158,85,159,85\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Indianapolis\" data-country=\"US\" data-pin=\"156,84\" data-offset=\"-5\" shape=\"poly\" coords=\"158,86,156,86,156,85,154,85,154,82,156,82,156,80,159,80,159,84,158,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Knox\" data-country=\"US\" data-pin=\"156,81\" data-offset=\"-6\" shape=\"poly\" coords=\"156,81,155,81,156,81\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Marengo\" data-country=\"US\" data-pin=\"156,86\" data-offset=\"-5\" shape=\"poly\" coords=\"156,86,156,86,156,86,156,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Vincennes\" data-country=\"US\" data-pin=\"154,86\" data-offset=\"-5\" shape=\"poly\" coords=\"154,86,154,85,156,85,156,86,154,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Winamac\" data-country=\"US\" data-pin=\"156,82\" data-offset=\"-5\" shape=\"poly\" coords=\"156,81,155,82,156,81\" />\n";
  $output .= "            <area data-timezone=\"America/Inuvik\" data-country=\"CA\" data-pin=\"77,36\" data-offset=\"-7\" shape=\"poly\" coords=\"78,36,77,36,78,36\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"167,24,166,24,168,25,166,25,168,25,158,26,158,24,167,24\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"167,12,174,12,173,13,174,12,171,12,187,12,185,12,187,12,187,14,184,14,187,14,183,15,187,15,187,15,182,16,183,16,179,16,183,17,181,17,176,17,175,17,178,17,170,18,176,18,170,18,176,19,172,20,174,20,174,20,169,20,170,21,169,21,163,21,170,22,169,23,165,23,165,23,162,22,163,23,158,22,158,21,161,21,160,21,163,20,159,21,158,18,163,19,161,19,164,18,159,18,161,18,158,17,158,16,165,17,167,17,161,16,173,15,168,15,172,14,168,15,168,15,167,15,158,16,158,15,163,15,158,15,158,13,159,14,158,13,168,14,162,13,166,12,164,12,169,12,166,12,167,12\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"158,35,159,35,158,35,159,34,163,34,161,34,164,35,163,35,165,36,162,36,165,38,161,39,160,38,158,38,158,35\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"173,29,167,29,165,27,170,27,173,29\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"159,28,164,27,166,29,165,29,166,29,165,30,167,29,170,30,169,30,171,30,169,29,171,29,175,29,175,30,173,30,176,30,174,31,176,31,175,31,177,30,176,31,177,31,177,32,178,31,178,32,179,31,181,31,179,32,180,32,179,32,182,32,180,33,181,32,181,33,184,32,182,33,186,32,183,34,186,33,183,34,187,33,186,34,183,34,187,34,185,35,186,35,185,35,187,35,186,35,187,35,184,35,187,36,187,40,186,40,187,40,187,44,185,44,187,45,187,46,181,45,180,44,181,44,179,44,178,43,178,42,177,43,178,42,176,43,177,42,176,42,175,42,176,43,170,43,170,42,171,41,178,41,176,40,180,38,177,36,178,36,177,36,177,36,174,35,172,36,174,35,171,34,172,34,171,34,172,33,171,34,171,33,168,32,167,33,168,33,169,34,164,33,165,34,162,33,164,33,158,33,158,32,159,31,158,31,158,30,159,30,158,29,158,28,159,29,158,28,160,28,158,28,159,28\" />\n";
  $output .= "            <area data-timezone=\"America/La_Paz\" data-country=\"BO\" data-pin=\"186,178\" data-offset=\"-4\" shape=\"poly\" coords=\"196,185,196,187,193,187,193,188,192,187,190,186,188,188,187,188,185,184,186,182,184,179,185,177,184,176,186,171,184,168,186,169,189,166,191,166,192,170,199,173,200,177,203,177,203,179,204,180,203,183,201,182,197,183,196,185\" />\n";
  $output .= "            <area data-timezone=\"America/Jamaica\" data-country=\"JM\" data-pin=\"172,120\" data-offset=\"-5\" shape=\"poly\" coords=\"173,120,171,120,169,120,171,119,173,120\" />\n";
  $output .= "            <area data-timezone=\"America/Juneau\" data-country=\"US\" data-pin=\"76,53\" data-offset=\"-9\" shape=\"poly\" coords=\"71,52,74,50,79,54,77,55,78,54,77,54,79,54,77,53,78,54,77,53,77,52,75,53,74,51,74,51,75,53,73,53,74,52,73,52,73,52,72,52,73,52,72,53,70,52,71,52\" />\n";
  $output .= "            <area data-timezone=\"America/Kentucky/Louisville\" data-country=\"US\" data-pin=\"157,86\" data-offset=\"-5\" shape=\"poly\" coords=\"157,87,156,86,158,86,157,87\" />\n";
  $output .= "            <area data-timezone=\"America/Kentucky/Monticello\" data-country=\"US\" data-pin=\"159,89\" data-offset=\"-5\" shape=\"poly\" coords=\"159,88,159,89,158,89,159,88\" />\n";
  $output .= "            <area data-timezone=\"America/Kralendijk\" data-country=\"BQ\" data-pin=\"186,130\" data-offset=\"-4\" shape=\"poly\" coords=\"195,121,195,121,195,121\" />\n";
  $output .= "            <area data-timezone=\"America/Lima\" data-country=\"PE\" data-pin=\"172,170\" data-offset=\"-5\" shape=\"poly\" coords=\"184,179,184,180,183,181,181,179,175,176,173,174,173,172,167,162,165,160,165,159,164,157,165,156,166,156,166,157,168,158,169,156,174,153,175,152,174,150,175,150,178,154,180,154,183,155,182,156,183,157,182,157,179,159,178,161,177,163,178,165,178,166,179,166,180,167,183,166,182,168,184,168,186,171,184,176,185,177,184,179\" />\n";
  $output .= "            <area data-timezone=\"America/Managua\" data-country=\"NI\" data-pin=\"156,130\" data-offset=\"-6\" shape=\"poly\" coords=\"158,132,157,131,154,128,155,128,155,127,157,127,158,125,161,125,161,132,158,132\" />\n";
  $output .= "            <area data-timezone=\"America/Manaus\" data-country=\"BR\" data-pin=\"200,155\" data-offset=\"-4\" shape=\"poly\" coords=\"200,150,202,150,203,152,206,154,203,161,203,162,203,165,197,165,195,163,194,163,193,165,192,166,189,166,189,166,187,166,183,157,184,152,183,149,185,149,184,148,184,147,188,146,188,148,189,149,191,148,191,149,193,147,195,146,196,149,196,151,197,152,198,151,199,151,200,150\" />\n";
  $output .= "            <area data-timezone=\"America/Los_Angeles\" data-country=\"US\" data-pin=\"103,93\" data-offset=\"-8\" shape=\"poly\" coords=\"109,74,106,74,106,73,106,74,105,76,103,76,103,79,105,79,105,80,110,80,110,90,109,90,110,93,109,94,109,95,105,96,102,93,99,92,99,91,96,88,96,87,95,87,94,85,93,83,93,81,92,79,93,73,94,73,93,73,94,72,93,72,94,72,93,72,92,69,95,70,95,71,96,70,96,71,95,72,96,71,95,68,107,68,107,71,109,72,109,74\" />\n";
  $output .= "            <area data-timezone=\"America/Lower_Princes\" data-country=\"SX\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/Maceio\" data-country=\"BR\" data-pin=\"240,166\" data-offset=\"-3\" shape=\"poly\" coords=\"239,168,237,169,236,168,237,167,236,166,237,165,238,166,241,165,239,168\" />\n";
  $output .= "            <area data-timezone=\"America/Marigot\" data-country=\"MF\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/Martinique\" data-country=\"MQ\" data-pin=\"198,126\" data-offset=\"-4\" shape=\"poly\" coords=\"198,125,198,125,198,125\" />\n";
  $output .= "            <area data-timezone=\"America/Matamoros\" data-country=\"MX\" data-pin=\"138,107\" data-offset=\"-6\" shape=\"poly\" coords=\"136,107,134,106,131,101,129,101,128,102,128,102,129,100,131,100,134,104,135,106,138,107,138,107,136,107\" />\n";
  $output .= "            <area data-timezone=\"America/Mazatlan\" data-country=\"MX\" data-pin=\"123,111\" data-offset=\"-7\" shape=\"poly\" coords=\"125,112,127,114,126,115,124,115,125,114,124,112,120,108,118,107,118,106,120,105,122,109,125,112\" />\n";
  $output .= "            <area data-timezone=\"America/Mazatlan\" data-country=\"MX\" data-pin=\"123,111\" data-offset=\"-7\" shape=\"poly\" coords=\"110,103,112,103,117,111,117,112,113,109,113,107,111,105,111,105,108,104,110,104,110,103\" />\n";
  $output .= "            <area data-timezone=\"America/Menominee\" data-country=\"US\" data-pin=\"154,75\" data-offset=\"-6\" shape=\"poly\" coords=\"154,74,153,73,149,72,154,73,155,74,154,74\" />\n";
  $output .= "            <area data-timezone=\"America/Mexico_City\" data-country=\"MX\" data-pin=\"135,118\" data-offset=\"-6\" shape=\"poly\" coords=\"143,123,142,123,139,124,137,123,125,118,124,116,125,115,126,115,127,115,126,113,127,110,129,109,129,108,132,109,133,112,137,113,140,119,143,120,146,119,147,120,148,120,148,121,148,121,149,123,147,123,146,126,143,123,143,123\" />\n";
  $output .= "            <area data-timezone=\"America/Merida\" data-country=\"MX\" data-pin=\"151,115\" data-offset=\"-6\" shape=\"poly\" coords=\"149,115,150,115,154,114,154,116,151,117,151,120,147,120,146,119,148,119,149,115\" />\n";
  $output .= "            <area data-timezone=\"America/Metlakatla\" data-country=\"US\" data-pin=\"81,58\" data-offset=\"-8\" shape=\"poly\" coords=\"81,58,81,58,81,58\" />\n";
  $output .= "            <area data-timezone=\"America/Miquelon\" data-country=\"PM\" data-pin=\"206,72\" data-offset=\"-3\" shape=\"poly\" coords=\"206,72,206,71,206,72\" />\n";
  $output .= "            <area data-timezone=\"America/Moncton\" data-country=\"CA\" data-pin=\"192,73\" data-offset=\"-4\" shape=\"poly\" coords=\"189,70,192,70,191,72,192,72,192,73,194,73,190,75,188,75,187,74,187,72,185,71,186,70,189,70\" />\n";
  $output .= "            <area data-timezone=\"America/Monterrey\" data-country=\"MX\" data-pin=\"133,107\" data-offset=\"-6\" shape=\"poly\" coords=\"129,108,129,109,127,110,126,113,125,112,121,108,123,105,126,106,127,106,127,104,128,102,129,101,131,101,135,106,138,107,137,108,138,108,137,108,137,113,133,112,132,109,129,108\" />\n";
  $output .= "            <area data-timezone=\"America/Montevideo\" data-country=\"UY\" data-pin=\"206,208\" data-offset=\"-2\" shape=\"poly\" coords=\"207,208,204,207,203,207,204,200,205,200,207,202,207,201,212,205,210,208,207,208\" />\n";
  $output .= "            <area data-timezone=\"America/Montreal\" data-country=\"CA\" data-pin=\"176,73\" data-offset=\"-5\" shape=\"poly\" coords=\"175,75,176,74,172,74,169,73,167,71,167,64,169,65,168,64,169,63,168,62,168,60,167,59,170,58,172,56,172,53,169,52,171,50,170,49,171,49,170,49,171,49,170,49,171,47,170,47,170,46,176,46,177,46,180,47,180,47,181,47,180,48,181,48,184,48,184,50,182,50,184,50,184,51,185,52,183,52,185,52,186,52,184,54,187,52,186,53,187,53,187,53,189,52,190,53,190,52,191,52,190,51,192,51,191,50,192,50,192,51,193,51,193,52,192,52,194,52,193,53,194,54,193,56,194,57,194,57,194,58,194,58,193,59,190,59,189,58,189,59,188,58,188,59,187,60,189,61,188,62,189,62,189,63,192,64,193,62,194,62,194,62,193,63,194,63,205,63,205,64,202,64,201,66,197,66,189,66,189,66,188,68,186,68,182,72,179,73,189,68,192,68,193,69,191,70,186,70,183,72,182,74,181,75,175,75\" />\n";
  $output .= "            <area data-timezone=\"America/Montserrat\" data-country=\"MS\" data-pin=\"196,122\" data-offset=\"-4\" shape=\"poly\" coords=\"196,122,196,122,196,122\" />\n";
  $output .= "            <area data-timezone=\"America/Nassau\" data-country=\"BS\" data-pin=\"171,108\" data-offset=\"-5\" shape=\"poly\" coords=\"172,107,172,107,172,107\" />\n";
  $output .= "            <area data-timezone=\"America/New_York\" data-country=\"US\" data-pin=\"177,82\" data-offset=\"-5\" shape=\"poly\" coords=\"158,99,157,92,159,90,159,89,156,87,159,85,158,85,159,81,164,81,168,79,168,78,173,77,173,76,175,75,182,75,185,71,187,72,187,74,188,75,186,76,185,76,185,77,183,77,182,79,183,80,183,80,183,81,181,81,181,80,181,81,177,82,175,85,174,84,175,86,173,88,174,87,173,85,173,84,172,85,173,87,171,86,173,87,172,87,173,88,172,88,173,88,174,90,173,89,174,90,172,90,174,91,172,91,173,91,172,92,173,92,172,92,165,96,164,98,167,105,166,108,165,108,163,106,164,105,163,106,162,104,163,104,162,104,162,101,160,100,158,101,158,99\" />\n";
  $output .= "            <area data-timezone=\"America/Nipigon\" data-country=\"CA\" data-pin=\"153,68\" data-offset=\"-5\" shape=\"poly\" coords=\"153,68,153,68,153,68\" />\n";
  $output .= "            <area data-timezone=\"America/Nome\" data-country=\"US\" data-pin=\"24,42\" data-offset=\"-9\" shape=\"poly\" coords=\"30,38,27,38,26,37,22,36,23,35,27,35,30,33,30,38\" />\n";
  $output .= "            <area data-timezone=\"America/Nome\" data-country=\"US\" data-pin=\"24,42\" data-offset=\"-9\" shape=\"poly\" coords=\"30,42,29,43,23,42,22,41,23,41,20,41,27,39,26,40,30,40,30,42\" />\n";
  $output .= "            <area data-timezone=\"America/Nome\" data-country=\"US\" data-pin=\"24,42\" data-offset=\"-9\" shape=\"poly\" coords=\"30,50,26,50,24,49,25,48,23,47,26,45,28,45,30,44,30,50\" />\n";
  $output .= "            <area data-timezone=\"America/Noronha\" data-country=\"BR\" data-pin=\"246,156\" data-offset=\"-2\" shape=\"poly\" coords=\"246,156,246,156,246,156\" />\n";
  $output .= "            <area data-timezone=\"America/North_Dakota/Beulah\" data-country=\"US\" data-pin=\"130,71\" data-offset=\"-6\" shape=\"poly\" coords=\"131,71,130,72,130,71,131,71\" />\n";
  $output .= "            <area data-timezone=\"America/North_Dakota/Center\" data-country=\"US\" data-pin=\"131,71\" data-offset=\"-6\" shape=\"poly\" coords=\"132,71,130,72,132,71\" />\n";
  $output .= "            <area data-timezone=\"America/North_Dakota/New_Salem\" data-country=\"US\" data-pin=\"131,72\" data-offset=\"-6\" shape=\"poly\" coords=\"132,72,132,72,132,73,130,72,132,72\" />\n";
  $output .= "            <area data-timezone=\"America/Ojinaga\" data-country=\"MX\" data-pin=\"126,101\" data-offset=\"-7\" shape=\"poly\" coords=\"122,97,128,102,125,101,125,99,122,97,119,98,120,98,120,97,122,97\" />\n";
  $output .= "            <area data-timezone=\"America/Panama\" data-country=\"PA\" data-pin=\"167,135\" data-offset=\"-5\" shape=\"poly\" coords=\"171,136,171,137,170,138,169,137,170,136,170,136,168,135,166,136,167,137,166,138,165,138,165,137,165,137,163,136,162,137,162,136,162,134,165,135,168,134,171,136\" />\n";
  $output .= "            <area data-timezone=\"America/Pangnirtung\" data-country=\"CA\" data-pin=\"190,40\" data-offset=\"-5\" shape=\"poly\" coords=\"187,12,192,12,198,13,187,14,187,12\" />\n";
  $output .= "            <area data-timezone=\"America/Pangnirtung\" data-country=\"CA\" data-pin=\"190,40\" data-offset=\"-5\" shape=\"poly\" coords=\"187,41,188,41,189,42,189,42,192,43,191,43,192,43,192,44,192,45,191,44,192,45,191,45,191,46,189,44,189,45,187,44,187,41\" />\n";
  $output .= "            <area data-timezone=\"America/Pangnirtung\" data-country=\"CA\" data-pin=\"190,40\" data-offset=\"-5\" shape=\"poly\" coords=\"187,36,189,36,187,36,190,36,189,37,189,37,190,37,190,37,192,37,191,37,192,37,193,38,192,38,193,38,192,38,195,38,194,39,195,38,195,39,197,38,198,39,195,39,197,40,195,40,196,40,195,40,196,41,194,40,194,42,191,40,193,39,190,40,191,39,190,40,187,39,188,39,187,39,188,40,187,40,187,36\" />\n";
  $output .= "            <area data-timezone=\"America/Paramaribo\" data-country=\"SR\" data-pin=\"208,140\" data-offset=\"-3\" shape=\"poly\" coords=\"209,141,210,144,209,146,207,146,207,147,206,147,203,143,204,142,205,141,205,140,210,140,209,141\" />\n";
  $output .= "            <area data-timezone=\"America/Phoenix\" data-country=\"US\" data-pin=\"113,94\" data-offset=\"-7\" shape=\"poly\" coords=\"118,96,118,98,115,98,109,96,110,93,109,90,110,90,110,88,114,88,114,89,115,91,118,91,118,96\" />\n";
  $output .= "            <area data-timezone=\"America/Port-au-Prince\" data-country=\"HT\" data-pin=\"179,119\" data-offset=\"-5\" shape=\"poly\" coords=\"178,118,179,119,178,118\" />\n";
  $output .= "            <area data-timezone=\"America/Port_of_Spain\" data-country=\"TT\" data-pin=\"197,132\" data-offset=\"-4\" shape=\"poly\" coords=\"197,132,197,132,197,132\" />\n";
  $output .= "            <area data-timezone=\"America/Porto_Velho\" data-country=\"BR\" data-pin=\"194,165\" data-offset=\"-4\" shape=\"poly\" coords=\"200,170,199,172,197,173,192,170,191,166,189,166,189,166,192,166,193,165,194,163,195,163,198,165,197,168,200,169,200,170\" />\n";
  $output .= "            <area data-timezone=\"America/Puerto_Rico\" data-country=\"PR\" data-pin=\"190,119\" data-offset=\"-4\" shape=\"poly\" coords=\"190,119,190,119,190,119\" />\n";
  $output .= "            <area data-timezone=\"America/Rainy_River\" data-country=\"CA\" data-pin=\"142,69\" data-offset=\"-6\" shape=\"poly\" coords=\"142,69,142,69,142,69\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,14,150,15,149,15,155,14,147,14,158,13,158,14\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"136,22,137,23,137,24,138,24,133,25,132,24,134,24,130,24,130,24,131,24,130,23,134,24,133,23,134,23,132,23,136,22\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"150,19,151,20,146,20,145,19,147,19,143,18,149,18,141,18,140,17,143,17,139,17,143,17,142,17,143,16,139,16,144,16,141,15,145,15,143,15,145,14,152,16,154,16,153,16,155,17,154,17,158,18,154,19,153,18,153,19,154,19,152,19,152,20,150,19\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,26,147,25,146,25,146,24,145,23,141,23,142,23,138,22,141,22,145,22,144,23,148,22,149,23,147,23,151,23,148,23,152,24,158,24,158,26\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,22,151,22,156,21,153,21,155,20,158,21,158,22\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,33,152,32,151,32,155,32,150,31,150,30,151,30,150,29,153,27,158,27,155,29,156,29,156,30,158,31,155,32,158,31,158,33\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"150,43,149,44,143,43,147,44,149,45,146,45,147,46,144,47,145,47,143,48,144,48,142,50,130,50,130,38,152,38,152,35,153,35,154,36,153,37,155,38,156,38,157,35,158,35,158,40,155,39,157,40,154,41,148,40,155,41,153,43,150,43\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"141,27,144,26,150,27,147,29,143,29,144,29,144,30,140,29,141,27,142,27,141,27\" />\n";
  $output .= "            <area data-timezone=\"America/Recife\" data-country=\"BR\" data-pin=\"242,163\" data-offset=\"-3\" shape=\"poly\" coords=\"236,163,237,163,238,162,238,164,241,162,242,163,241,165,238,166,237,165,236,166,234,164,232,166,231,165,232,164,232,162,236,163\" />\n";
  $output .= "            <area data-timezone=\"America/Regina\" data-country=\"CA\" data-pin=\"126,66\" data-offset=\"-6\" shape=\"poly\" coords=\"125,68,117,68,117,62,119,62,117,61,117,50,130,50,130,58,129,59,130,60,131,68,125,68\" />\n";
  $output .= "            <area data-timezone=\"America/Resolute\" data-country=\"CA\" data-pin=\"142,26\" data-offset=\"-6\" shape=\"poly\" coords=\"141,24,144,24,144,25,139,25,141,24\" />\n";
  $output .= "            <area data-timezone=\"America/Rio_Branco\" data-country=\"BR\" data-pin=\"187,167\" data-offset=\"-4\" shape=\"poly\" coords=\"177,162,183,164,189,167,186,169,182,168,183,166,180,167,179,166,178,166,178,165,177,163,177,162\" />\n";
  $output .= "            <area data-timezone=\"America/Santa_Isabel\" data-country=\"PR\" data-pin=\"300,150\" data-offset=\"-8\" shape=\"poly\" coords=\"109,96,109,100,112,103,110,103,110,102,107,100,106,97,106,96,109,96\" />\n";
  $output .= "            <area data-timezone=\"America/Santarem\" data-country=\"BR\" data-pin=\"209,154\" data-offset=\"-3\" shape=\"poly\" coords=\"212,150,213,152,214,152,213,153,214,155,212,156,212,158,213,161,212,164,213,165,212,166,205,166,204,165,203,161,206,154,202,151,202,148,204,147,207,147,207,146,208,146,209,147,211,148,212,150\" />\n";
  $output .= "            <area data-timezone=\"America/Santiago\" data-country=\"CL\" data-pin=\"182,206\" data-offset=\"-3\" shape=\"poly\" coords=\"183,239,184,237,186,238,186,241,180,241,183,241,182,240,185,241,184,241,185,241,183,240,184,239,183,239,183,239\" />\n";
  $output .= "            <area data-timezone=\"America/Santiago\" data-country=\"CL\" data-pin=\"182,206\" data-offset=\"-3\" shape=\"poly\" coords=\"178,232,177,234,178,235,180,234,179,236,180,237,186,237,182,238,181,240,179,239,180,239,181,238,181,238,179,238,178,238,178,238,179,238,177,238,177,237,179,237,179,237,179,237,178,237,179,237,179,236,178,235,178,236,179,236,178,236,178,236,178,237,178,236,178,237,177,236,177,235,176,235,178,234,177,234,177,235,177,234,176,234,177,234,176,233,177,233,177,232,176,232,176,231,177,231,176,231,177,231,176,230,178,230,177,229,175,230,176,229,176,229,177,229,176,228,174,228,175,226,177,226,177,228,178,226,177,226,178,226,179,226,177,225,179,224,178,224,179,221,179,221,179,221,179,220,179,220,180,219,177,220,177,219,178,216,177,212,178,212,181,206,181,198,182,194,182,189,183,186,183,181,184,179,186,182,185,184,187,188,188,188,188,190,186,191,186,195,184,197,184,200,182,202,184,207,182,209,183,210,181,211,182,214,180,216,180,220,180,221,181,223,180,224,181,224,180,225,181,225,180,226,181,228,179,230,179,231,178,232\" />\n";
  $output .= "            <area data-timezone=\"America/Santo_Domingo\" data-country=\"DO\" data-pin=\"184,119\" data-offset=\"-4\" shape=\"poly\" coords=\"182,120,181,120,180,119,181,117,183,117,185,118,184,118,186,119,186,120,182,120\" />\n";
  $output .= "            <area data-timezone=\"America/Scoresbysund\" data-country=\"GL\" data-pin=\"263,33\" data-offset=\"-1\" shape=\"poly\" coords=\"259,29,263,30,261,31,263,30,262,31,264,31,264,31,263,32,264,32,263,32,264,32,260,32,259,31,259,29\" />\n";
  $output .= "            <area data-timezone=\"America/Sao_Paulo\" data-country=\"BR\" data-pin=\"222,189\" data-offset=\"-3\" shape=\"poly\" coords=\"217,199,215,202,213,204,216,201,215,200,215,201,211,206,211,205,212,205,210,204,207,201,207,202,205,200,204,200,207,197,211,195,210,193,209,193,209,190,211,188,213,186,215,182,212,181,211,179,215,175,216,171,218,172,218,171,219,172,220,172,223,171,223,175,226,174,233,177,232,179,234,181,234,182,232,185,232,187,230,188,226,188,220,192,219,192,219,193,219,194,219,194,219,197,217,199\" />\n";
  $output .= "            <area data-timezone=\"America/Sitka\" data-country=\"US\" data-pin=\"74,55\" data-offset=\"-9\" shape=\"poly\" coords=\"79,54,80,56,77,55,79,54,79,54\" />\n";
  $output .= "            <area data-timezone=\"America/St_Barthelemy\" data-country=\"BL\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/St_Johns\" data-country=\"CA\" data-pin=\"212,71\" data-offset=\"-3.5\" shape=\"poly\" coords=\"208,64,206,64,207,65,206,65,205,67,206,66,208,67,206,68,208,67,208,68,209,67,211,68,210,69,212,69,210,70,211,71,212,70,211,71,212,71,212,72,211,72,211,71,210,72,210,71,210,70,207,72,209,71,206,71,207,71,207,70,205,71,201,71,203,69,201,69,203,68,204,68,203,68,204,68,203,67,204,65,208,64\" />\n";
  $output .= "            <area data-timezone=\"America/Thule\" data-country=\"GL\" data-pin=\"185,22\" data-offset=\"-4\" shape=\"poly\" coords=\"194,23,186,23,184,23,187,22,181,22,190,21,183,21,184,21,178,20,189,18,194,23\" />\n";
  $output .= "            <area data-timezone=\"America/St_Kitts\" data-country=\"KN\" data-pin=\"195,121\" data-offset=\"-4\" shape=\"poly\" coords=\"195,121,196,121,195,121\" />\n";
  $output .= "            <area data-timezone=\"America/St_Lucia\" data-country=\"LC\" data-pin=\"198,127\" data-offset=\"-4\" shape=\"poly\" coords=\"198,126,198,127,198,126\" />\n";
  $output .= "            <area data-timezone=\"America/St_Thomas\" data-country=\"VI\" data-pin=\"192,119\" data-offset=\"-4\" shape=\"poly\" coords=\"192,119,192,119,192,119\" />\n";
  $output .= "            <area data-timezone=\"America/St_Vincent\" data-country=\"VC\" data-pin=\"198,128\" data-offset=\"-4\" shape=\"poly\" coords=\"198,128,198,128,198,128\" />\n";
  $output .= "            <area data-timezone=\"America/Swift_Current\" data-country=\"CA\" data-pin=\"120,66\" data-offset=\"-6\" shape=\"poly\" coords=\"120,66,120,66,120,66\" />\n";
  $output .= "            <area data-timezone=\"America/Tegucigalpa\" data-country=\"HN\" data-pin=\"155,127\" data-offset=\"-6\" shape=\"poly\" coords=\"155,127,154,128,154,127,151,126,151,125,153,124,157,123,159,124,161,125,158,125,157,127,155,127\" />\n";
  $output .= "            <area data-timezone=\"America/Thunder_Bay\" data-country=\"CA\" data-pin=\"151,69\" data-offset=\"-5\" shape=\"poly\" coords=\"151,69,151,70,151,69\" />\n";
  $output .= "            <area data-timezone=\"America/Tijuana\" data-country=\"MX\" data-pin=\"105,96\" data-offset=\"-8\" shape=\"poly\" coords=\"105,96,105,96,105,96\" />\n";
  $output .= "            <area data-timezone=\"America/Toronto\" data-country=\"CA\" data-pin=\"168,77\" data-offset=\"-5\" shape=\"poly\" coords=\"175,74,176,75,172,77,170,76,172,76,171,77,168,77,167,78,168,79,161,80,164,78,165,76,164,75,165,76,167,75,165,73,164,73,164,74,160,73,159,72,159,70,157,70,156,69,153,68,153,69,151,69,151,70,148,69,148,68,150,68,150,65,149,64,150,63,150,62,152,62,153,61,152,60,150,60,150,56,152,55,154,57,158,58,163,58,163,62,166,64,167,64,168,72,169,73,173,74,175,74\" />\n";
  $output .= "            <area data-timezone=\"America/Tortola\" data-country=\"VG\" data-pin=\"192,119\" data-offset=\"-4\" shape=\"poly\" coords=\"192,119,192,119,192,119\" />\n";
  $output .= "            <area data-timezone=\"America/Vancouver\" data-country=\"CA\" data-pin=\"95,68\" data-offset=\"-8\" shape=\"poly\" coords=\"86,65,91,66,95,69,91,69,92,68,91,68,90,68,89,68,90,67,87,66,88,66,86,65\" />\n";
  $output .= "            <area data-timezone=\"America/Vancouver\" data-country=\"CA\" data-pin=\"95,68\" data-offset=\"-8\" shape=\"poly\" coords=\"75,51,74,50,71,52,68,50,100,50,100,55,94,55,95,58,103,62,103,63,102,63,106,66,106,68,95,68,98,68,97,68,96,67,97,68,95,68,96,67,95,68,95,68,95,68,94,67,95,67,93,67,94,67,93,67,94,66,93,67,92,67,93,66,92,66,93,65,92,65,91,66,90,66,91,65,90,66,89,65,90,65,88,65,89,65,87,64,89,64,87,64,87,64,88,63,89,63,88,63,89,63,88,63,88,62,88,63,87,63,87,62,86,63,87,62,85,61,87,62,85,61,86,60,85,61,83,60,85,59,83,59,83,60,83,59,85,58,83,58,84,58,84,57,83,58,83,56,80,56,78,53,75,51\" />\n";
  $output .= "            <area data-timezone=\"America/Whitehorse\" data-country=\"CA\" data-pin=\"75,49\" data-offset=\"-8\" shape=\"poly\" coords=\"69,34,73,35,73,38,77,38,77,40,79,40,79,42,83,44,84,44,83,45,84,45,85,46,88,48,89,49,92,48,93,50,65,49,65,34,69,34\" />\n";
  $output .= "            <area data-timezone=\"America/Winnipeg\" data-country=\"CA\" data-pin=\"138,67\" data-offset=\"-6\" shape=\"poly\" coords=\"147,69,142,69,141,68,141,68,131,68,130,60,129,59,130,58,130,50,142,50,142,52,145,52,146,54,145,55,148,55,152,55,150,56,150,60,152,60,153,61,152,62,150,62,150,63,149,64,150,65,150,68,147,68,147,69\" />\n";
  $output .= "            <area data-timezone=\"America/Yakutat\" data-country=\"US\" data-pin=\"67,51\" data-offset=\"-9\" shape=\"poly\" coords=\"67,50,67,50,67,50\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"100,37,111,41,114,41,116,42,130,43,130,50,93,50,92,48,89,49,88,48,85,46,84,45,83,45,84,44,83,44,79,42,79,40,77,40,77,38,73,38,73,35,75,36,73,35,76,34,77,34,77,35,84,33,80,34,80,35,82,34,82,35,83,34,88,33,87,32,91,34,92,33,93,34,93,34,95,34,98,34,97,34,97,36,100,37\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"106,21,108,21,106,21,107,22,105,23,103,23,104,22,100,24,95,23,101,21,106,21\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"100,27,104,26,108,28,101,29,100,30,99,31,95,32,90,30,94,27,92,26,98,26,100,27\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"109,23,117,24,117,25,111,26,109,26,115,25,104,25,108,24,105,24,109,24,105,23,109,23\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"108,28,110,28,109,29,112,28,115,29,114,30,117,29,117,33,105,33,105,34,104,33,114,33,104,32,103,32,108,31,103,31,104,31,101,30,103,30,103,29,108,28\" />\n";
  $output .= "            <area data-timezone=\"Antarctica/Macquarie\" data-country=\"AU\" data-pin=\"565,241\" data-offset=\"11\" shape=\"poly\" coords=\"565,241,565,241,565,241\" />\n";
  $output .= "            <area data-timezone=\"Arctic/Longyearbyen\" data-country=\"SJ\" data-pin=\"327,20\" data-offset=\"1\" shape=\"poly\" coords=\"330,17,330,17,329,18,331,17,336,19,332,19,332,20,328,22,328,22,327,22,326,22,328,22,323,21,327,21,325,21,328,20,323,20,323,20,329,19,327,19,328,19,326,19,326,19,324,19,325,19,322,20,321,19,322,19,319,18,321,18,319,18,320,18,319,18,318,17,323,17,321,17,323,18,323,17,324,17,327,18,326,17,330,17\" />\n";
  $output .= "            <area data-timezone=\"Arctic/Longyearbyen\" data-country=\"SJ\" data-pin=\"327,20\" data-offset=\"1\" shape=\"poly\" coords=\"342,16,345,17,339,18,329,16,332,16,332,16,333,16,333,16,337,17,338,16,339,16,339,16,342,16\" />\n";
  $output .= "            <area data-timezone=\"Asia/Aden\" data-country=\"YE\" data-pin=\"375,129\" data-offset=\"3\" shape=\"poly\" coords=\"375,128,372,129,371,125,372,121,377,123,377,124,381,120,387,118,389,122,387,123,387,124,375,128\" />\n";
  $output .= "            <area data-timezone=\"Asia/Almaty\" data-country=\"KZ\" data-pin=\"428,78\" data-offset=\"6\" shape=\"poly\" coords=\"418,79,414,82,411,81,410,79,413,78,413,77,412,76,412,73,404,72,407,70,407,70,408,68,410,68,414,66,413,65,413,63,410,63,411,62,410,62,411,61,410,59,415,58,418,58,419,60,423,60,422,61,428,59,427,60,430,61,433,65,434,64,436,65,439,65,442,67,445,67,446,68,443,69,443,72,438,71,437,74,437,75,436,74,433,75,434,75,435,78,434,80,431,78,426,79,424,78,423,78,422,79,420,79,418,79\" />\n";
  $output .= "            <area data-timezone=\"Asia/Amman\" data-country=\"JO\" data-pin=\"360,97\" data-offset=\"2\" shape=\"poly\" coords=\"362,98,363,99,362,100,360,101,358,101,359,96,361,96,365,94,366,96,362,98,362,98\" />\n";
  $output .= "            <area data-timezone=\"Asia/Anadyr\" data-country=\"RU\" data-pin=\"596,42\" data-offset=\"12\" shape=\"poly\" coords=\"12,38,17,40,16,41,14,40,15,41,12,41,13,41,13,42,11,42,13,43,12,43,8,42,7,41,2,41,2,40,3,39,1,40,1,41,0,42,0,35,8,37,9,39,11,39,9,38,12,38\" />\n";
  $output .= "            <area data-timezone=\"Asia/Anadyr\" data-country=\"RU\" data-pin=\"596,42\" data-offset=\"12\" shape=\"poly\" coords=\"596,42,592,42,597,43,599,46,598,46,595,46,591,47,589,46,584,46,581,45,583,44,581,43,568,41,565,40,565,40,564,39,565,38,563,37,564,36,571,36,571,35,571,34,571,34,580,34,583,35,585,35,584,34,584,33,594,34,600,35,600,42,598,42,596,42\" />\n";
  $output .= "            <area data-timezone=\"Asia/Aqtau\" data-country=\"KZ\" data-pin=\"384,76\" data-offset=\"5\" shape=\"poly\" coords=\"393,75,393,81,390,79,387,80,388,79,385,78,384,76,386,76,385,75,386,74,388,74,388,72,385,72,382,73,381,72,382,72,381,71,378,70,382,70,390,68,390,69,392,69,392,72,394,73,395,75,393,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Aqtobe\" data-country=\"KZ\" data-pin=\"395,66\" data-offset=\"5\" shape=\"poly\" coords=\"391,66,391,65,393,66,394,65,397,65,399,66,402,65,403,64,405,67,404,68,407,70,405,71,402,70,398,74,395,75,394,72,392,71,392,69,389,68,391,67,391,66\" />\n";
  $output .= "            <area data-timezone=\"Asia/Ashgabat\" data-country=\"TM\" data-pin=\"397,87\" data-offset=\"5\" shape=\"poly\" coords=\"408,89,407,90,404,91,402,91,402,89,401,89,399,87,395,86,390,88,390,85,389,85,389,84,390,84,388,83,388,82,387,80,390,79,393,81,395,81,395,80,397,79,398,80,397,79,398,79,400,80,401,81,403,81,404,83,411,87,411,88,409,87,408,89\" />\n";
  $output .= "            <area data-timezone=\"Asia/Baghdad\" data-country=\"IQ\" data-pin=\"374,94\" data-offset=\"3\" shape=\"poly\" coords=\"379,100,378,101,375,101,370,98,365,96,365,94,368,93,369,89,371,88,375,88,376,90,377,90,376,93,377,95,379,96,380,97,379,98,381,100,379,100\" />\n";
  $output .= "            <area data-timezone=\"Asia/Bahrain\" data-country=\"BH\" data-pin=\"384,106\" data-offset=\"3\" shape=\"poly\" coords=\"384,106,384,106,384,106\" />\n";
  $output .= "            <area data-timezone=\"Asia/Bangkok\" data-country=\"TH\" data-pin=\"468,127\" data-offset=\"7\" shape=\"poly\" coords=\"470,139,470,140,469,141,469,140,467,139,467,139,464,136,465,132,466,130,465,127,464,125,465,123,462,119,463,119,463,117,465,117,467,116,468,116,468,117,469,117,468,121,470,120,471,120,472,119,473,119,476,124,475,126,473,126,471,127,472,131,470,129,468,129,468,128,467,128,465,135,466,134,468,138,470,139\" />\n";
  $output .= "            <area data-timezone=\"Asia/Baku\" data-country=\"AZ\" data-pin=\"383,83\" data-offset=\"4\" shape=\"poly\" coords=\"377,80,380,81,381,80,384,83,382,83,381,86,380,85,381,84,380,84,378,85,378,84,376,83,377,83,375,81,378,81,377,80\" />\n";
  $output .= "            <area data-timezone=\"Asia/Chongqing\" data-country=\"CN\" data-pin=\"475,100\" data-offset=\"8\" shape=\"poly\" coords=\"483,114,482,114,481,113,481,114,479,114,478,113,478,112,476,111,473,112,472,112,472,113,471,112,469,113,470,115,469,115,469,114,467,114,467,113,465,113,466,112,465,111,465,110,463,110,463,108,465,107,465,104,463,103,465,103,464,102,465,101,465,98,462,95,463,94,462,94,464,94,465,93,465,92,465,92,465,90,466,87,466,87,466,87,462,85,463,84,461,84,462,82,464,82,462,79,468,79,475,81,479,79,484,79,485,78,488,80,491,80,490,81,490,82,487,83,485,84,484,86,485,87,484,92,485,94,483,95,484,96,482,96,484,98,481,99,481,100,482,102,482,104,481,105,482,105,482,106,483,107,485,106,485,108,486,108,487,109,486,111,487,111,488,113,486,114,485,113,486,113,485,112,483,114\" />\n";
  $output .= "            <area data-timezone=\"Asia/Beirut\" data-country=\"LB\" data-pin=\"359,94\" data-offset=\"2\" shape=\"poly\" coords=\"360,94,359,95,359,93,361,92,361,93,360,94\" />\n";
  $output .= "            <area data-timezone=\"Asia/Bishkek\" data-country=\"KG\" data-pin=\"424,79\" data-offset=\"6\" shape=\"poly\" coords=\"425,82,423,83,423,84,420,85,415,84,422,82,420,81,419,81,417,81,419,80,418,79,419,79,422,79,423,78,424,78,426,79,431,78,434,80,430,82,428,82,427,83,425,82\" />\n";
  $output .= "            <area data-timezone=\"Asia/Brunei\" data-country=\"BN\" data-pin=\"492,142\" data-offset=\"8\" shape=\"poly\" coords=\"492,142,492,143,492,142\" />\n";
  $output .= "            <area data-timezone=\"Asia/Choibalsan\" data-country=\"MN\" data-pin=\"491,70\" data-offset=\"8\" shape=\"poly\" coords=\"494,73,493,74,491,74,489,75,487,75,486,73,486,71,488,70,487,67,491,66,495,67,493,70,493,71,497,70,500,71,499,73,498,72,494,73\" />\n";
  $output .= "            <area data-timezone=\"Asia/Colombo\" data-country=\"LK\" data-pin=\"433,138\" data-offset=\"5.5\" shape=\"poly\" coords=\"436,139,434,140,433,140,433,136,433,134,436,136,436,138,436,139\" />\n";
  $output .= "            <area data-timezone=\"Asia/Damascus\" data-country=\"SY\" data-pin=\"360,94\" data-offset=\"2\" shape=\"poly\" coords=\"362,96,361,96,359,95,359,95,361,93,360,91,361,90,361,89,365,89,371,88,369,89,368,93,362,96\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dhaka\" data-country=\"BD\" data-pin=\"451,110\" data-offset=\"6\" shape=\"poly\" coords=\"450,113,450,112,450,114,449,114,448,110,447,109,448,108,447,107,447,106,449,107,449,106,450,108,454,108,452,110,453,112,454,111,454,115,454,114,454,115,453,113,454,113,453,113,452,112,451,112,451,110,450,110,451,111,448,110,450,111,451,113,450,113\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dili\" data-country=\"TL\" data-pin=\"509,164\" data-offset=\"9\" shape=\"poly\" coords=\"509,164,512,164,509,166,509,164\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dubai\" data-country=\"AE\" data-pin=\"392,108\" data-offset=\"4\" shape=\"poly\" coords=\"393,109,393,110,392,110,392,112,388,112,386,110,391,110,394,107,394,108,393,109\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dushanbe\" data-country=\"TJ\" data-pin=\"415,86\" data-offset=\"5\" shape=\"poly\" coords=\"416,88,413,88,414,86,412,84,414,84,415,83,414,83,417,82,418,82,417,83,418,83,416,83,416,84,419,84,420,85,423,84,423,86,425,86,425,88,422,88,419,89,419,87,418,86,416,88\" />\n";
  $output .= "            <area data-timezone=\"Asia/Gaza\" data-country=\"PS\" data-pin=\"357,98\" data-offset=\"2\" shape=\"poly\" coords=\"357,97,357,98,357,97\" />\n";
  $output .= "            <area data-timezone=\"Asia/Harbin\" data-country=\"CN\" data-pin=\"509,75\" data-offset=\"8\" shape=\"poly\" coords=\"509,82,508,78,507,79,506,78,505,76,504,76,504,75,503,74,505,74,505,72,506,72,504,71,507,69,508,70,510,65,509,64,505,64,504,63,507,61,510,62,513,67,518,69,518,71,524,69,525,70,522,75,520,74,518,75,519,78,517,79,518,79,517,78,516,79,513,80,514,81,512,80,509,82\" />\n";
  $output .= "            <area data-timezone=\"Asia/Hebron\" data-country=\"PS\" data-pin=\"358,97\" data-offset=\"2\" shape=\"poly\" coords=\"359,97,359,96,359,97,358,98,359,97\" />\n";
  $output .= "            <area data-timezone=\"Asia/Ho_Chi_Minh\" data-country=\"VN\" data-pin=\"478,132\" data-offset=\"7\" shape=\"poly\" coords=\"476,135,475,136,475,133,474,133,475,132,477,132,476,131,479,129,479,125,475,119,473,118,475,117,474,116,474,116,472,115,472,114,470,113,471,112,473,112,476,111,478,112,478,113,480,114,478,115,478,116,476,118,482,125,482,129,482,131,478,133,478,134,476,135\" />\n";
  $output .= "            <area data-timezone=\"Asia/Hong_Kong\" data-country=\"HK\" data-pin=\"490,113\" data-offset=\"8\" shape=\"poly\" coords=\"490,112,490,113,490,112\" />\n";
  $output .= "            <area data-timezone=\"Asia/Hovd\" data-country=\"MN\" data-pin=\"453,70\" data-offset=\"7\" shape=\"poly\" coords=\"464,75,463,77,463,79,461,79,459,76,451,75,452,72,450,70,447,69,446,68,454,65,457,66,458,67,462,67,462,68,463,68,465,68,465,69,463,70,464,71,463,72,464,74,464,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Irkutsk\" data-country=\"RU\" data-pin=\"474,63\" data-offset=\"9\" shape=\"poly\" coords=\"493,55,492,56,493,58,495,58,495,59,490,61,491,62,489,63,487,63,484,64,481,64,480,65,481,66,480,67,476,66,472,66,470,66,470,64,464,63,465,61,461,61,459,60,461,59,461,57,463,56,462,55,463,54,467,53,468,54,469,53,471,52,471,51,474,52,476,52,475,51,476,50,476,50,474,49,477,47,478,46,477,45,478,44,478,43,481,43,481,44,480,44,482,44,483,45,482,46,483,46,483,47,483,48,484,48,482,51,483,52,487,51,488,52,493,49,495,50,495,51,498,51,499,53,496,53,495,54,496,55,496,55,493,55\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jakarta\" data-country=\"ID\" data-pin=\"478,160\" data-offset=\"7\" shape=\"poly\" coords=\"487,161,488,163,491,163,491,165,480,163,475,161,477,160,480,160,481,161,484,162,485,161,487,161\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jakarta\" data-country=\"ID\" data-pin=\"478,160\" data-offset=\"7\" shape=\"poly\" coords=\"476,157,476,160,475,159,475,160,474,159,474,160,471,157,466,150,465,150,465,147,459,142,459,141,463,141,464,143,472,149,471,150,473,149,472,151,474,152,475,154,476,154,477,155,476,157\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jayapura\" data-country=\"ID\" data-pin=\"534,154\" data-offset=\"9\" shape=\"poly\" coords=\"525,157,524,157,525,157,523,156,523,155,522,156,522,157,521,155,520,155,522,154,523,155,523,153,521,154,520,152,518,152,519,151,521,151,523,151,524,154,525,156,530,152,535,154,535,165,533,164,534,163,532,164,532,163,531,162,533,162,531,162,532,162,529,158,525,157\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kabul\" data-country=\"AF\" data-pin=\"415,92\" data-offset=\"4.5\" shape=\"poly\" coords=\"405,101,401,100,403,98,401,98,401,95,402,94,401,93,402,91,405,91,410,87,413,88,415,88,418,86,419,87,419,89,422,88,425,88,419,90,419,91,418,93,416,93,417,94,416,95,415,97,414,97,413,98,411,98,410,100,405,101\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jerusalem\" data-country=\"IL\" data-pin=\"359,97\" data-offset=\"2\" shape=\"poly\" coords=\"358,97,359,95,359,95,359,96,358,96,359,97,358,98,359,98,358,101,357,98,358,97\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kamchatka\" data-country=\"RU\" data-pin=\"564,62\" data-offset=\"12\" shape=\"poly\" coords=\"571,54,572,56,570,57,570,58,570,59,567,60,567,62,564,62,564,63,561,65,559,57,562,54,561,54,565,53,570,49,573,49,573,48,573,48,574,46,575,46,572,46,572,47,572,47,571,47,570,46,571,45,570,45,572,44,571,43,572,43,572,42,581,43,583,44,581,45,584,46,589,46,591,47,587,48,584,50,581,49,577,50,577,49,575,50,573,50,572,52,570,53,572,54,571,54\" />\n";
  $output .= "            <area data-timezone=\"Asia/Karachi\" data-country=\"PK\" data-pin=\"412,109\" data-offset=\"5\" shape=\"poly\" coords=\"417,104,416,105,418,107,419,109,418,110,415,109,413,110,412,109,411,109,411,107,403,108,403,106,405,106,406,105,405,105,405,103,403,102,401,100,407,101,410,100,411,98,416,97,416,95,417,94,416,93,418,93,418,92,419,91,419,90,420,89,425,88,427,89,427,90,430,91,426,92,423,92,423,95,426,96,424,97,424,98,420,103,417,104\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kashgar\" data-country=\"CN\" data-pin=\"430,95\" data-offset=\"6\" shape=\"poly\" coords=\"434,99,431,98,431,96,432,96,432,95,431,94,432,93,430,92,430,91,427,90,426,89,424,88,425,88,425,86,423,86,423,85,425,82,427,83,428,82,430,82,434,80,434,79,435,78,434,75,433,75,436,74,438,75,436,76,442,78,438,77,439,78,438,78,438,80,436,82,437,84,438,84,437,88,438,90,437,91,437,92,437,93,439,95,439,97,438,97,439,98,439,99,437,99,435,100,434,99\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kathmandu\" data-country=\"NP\" data-pin=\"442,104\" data-offset=\"5.8\" shape=\"poly\" coords=\"442,103,447,104,447,106,443,106,440,104,439,104,433,102,434,100,436,99,442,102,442,103\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kolkata\" data-country=\"IN\" data-pin=\"447,112\" data-offset=\"5.5\" shape=\"poly\" coords=\"434,128,433,130,433,133,432,133,432,134,432,135,431,135,430,136,429,136,428,135,422,123,421,117,422,115,421,114,422,114,421,114,421,113,421,113,420,115,418,115,415,113,417,112,417,112,415,112,414,111,415,110,414,110,415,110,419,109,416,105,417,103,420,103,424,98,424,97,426,96,423,95,423,92,426,92,430,91,430,92,432,93,431,94,432,95,432,96,431,96,431,98,435,100,433,102,443,106,447,106,447,103,448,103,449,105,453,105,454,105,453,104,454,104,458,101,461,101,460,102,461,102,460,103,462,103,461,104,462,105,460,105,459,106,457,110,456,110,455,113,454,110,453,112,452,111,454,108,450,108,449,106,449,107,447,106,447,107,448,108,447,109,448,110,448,114,448,113,447,114,447,113,445,114,445,115,444,117,442,118,437,122,437,122,434,124,434,128\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"475,19,466,20,471,18,472,18,471,19,473,18,476,19,475,19\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"462,16,464,17,461,17,467,17,466,18,465,18,467,18,459,18,457,17,455,17,462,16\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"459,15,463,16,461,15,462,16,456,17,452,16,459,15\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"461,61,465,62,463,65,464,66,462,67,458,67,457,66,454,65,449,67,449,66,450,66,448,64,447,64,449,62,448,61,449,60,447,59,448,59,447,58,449,57,448,56,449,54,447,53,448,52,448,51,445,50,441,50,441,49,440,49,443,48,441,46,443,45,442,44,443,43,443,42,440,42,441,41,439,40,439,39,437,38,437,37,436,37,438,36,437,35,432,34,435,32,432,31,434,30,431,29,439,31,437,31,437,32,436,33,437,33,437,33,440,34,438,33,440,33,439,31,439,31,435,29,435,28,434,28,435,28,434,27,445,27,446,27,443,26,446,26,443,26,445,26,446,25,445,25,449,24,457,23,455,23,455,23,466,23,465,23,469,22,469,21,474,20,477,21,474,22,479,22,478,23,485,22,487,23,487,23,490,23,489,24,487,24,490,24,488,25,480,27,477,27,478,27,475,29,485,27,485,27,484,28,485,28,484,29,487,30,487,31,488,31,484,32,482,34,477,34,478,35,478,38,476,38,477,39,477,41,478,41,476,42,481,43,478,43,478,44,477,45,478,46,477,47,475,48,474,49,476,50,476,50,475,51,476,52,475,52,472,51,468,54,468,54,467,53,463,54,462,55,463,56,461,57,461,59,459,59,461,61\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kuala_Lumpur\" data-country=\"MY\" data-pin=\"470,145\" data-offset=\"8\" shape=\"poly\" coords=\"473,146,474,148,473,148,469,145,468,143,467,139,469,140,469,141,470,140,472,141,473,146\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kuching\" data-country=\"MY\" data-pin=\"484,147\" data-offset=\"8\" shape=\"poly\" coords=\"492,142,495,138,495,139,495,138,496,139,497,141,499,141,497,142,498,143,493,143,491,148,487,147,486,148,484,149,483,147,483,147,486,148,485,147,486,145,488,145,490,142,491,143,492,142,492,143,492,142\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kuwait\" data-country=\"KW\" data-pin=\"380,101\" data-offset=\"3\" shape=\"poly\" coords=\"380,100,380,100,380,100\" />\n";
  $output .= "            <area data-timezone=\"Asia/Macau\" data-country=\"MO\" data-pin=\"489,113\" data-offset=\"8\" shape=\"poly\" coords=\"489,113,489,113,489,113\" />\n";
  $output .= "            <area data-timezone=\"Asia/Magadan\" data-country=\"RU\" data-pin=\"551,51\" data-offset=\"12\" shape=\"poly\" coords=\"557,50,557,51,559,51,552,52,552,52,554,51,549,50,548,51,546,51,545,51,545,50,543,49,545,48,544,47,540,47,534,46,533,45,534,43,532,42,534,41,533,40,534,39,532,38,535,37,535,36,537,36,536,35,538,35,537,34,537,34,535,33,540,30,549,30,550,30,548,31,554,32,564,32,566,32,566,34,568,34,568,35,571,34,571,35,571,36,564,36,563,37,565,38,564,39,565,40,565,40,572,42,572,43,571,43,572,44,570,45,571,45,570,46,571,47,567,49,567,48,566,48,567,47,562,47,557,50\" />\n";
  $output .= "            <area data-timezone=\"Asia/Makassar\" data-country=\"ID\" data-pin=\"499,159\" data-offset=\"8\" shape=\"poly\" coords=\"494,154,493,156,491,157,491,155,493,152,492,150,491,150,492,149,490,149,492,146,493,143,496,143,496,144,495,144,496,144,495,144,497,146,496,147,498,148,496,148,496,151,494,153,494,154\" />\n";
  $output .= "            <area data-timezone=\"Asia/Makassar\" data-country=\"ID\" data-pin=\"499,159\" data-offset=\"8\" shape=\"poly\" coords=\"504,155,504,156,505,157,503,158,501,156,502,154,500,155,501,159,499,159,499,157,499,156,498,156,498,155,499,152,500,151,500,149,502,148,507,149,509,147,507,149,501,149,500,150,500,151,501,152,503,151,506,151,506,152,502,153,504,155\" />\n";
  $output .= "            <area data-timezone=\"Asia/Manila\" data-country=\"PH\" data-pin=\"502,126\" data-offset=\"8\" shape=\"poly\" coords=\"507,140,507,139,507,138,506,137,506,138,504,137,504,138,503,138,504,137,506,135,506,136,506,137,508,136,508,135,509,135,509,134,510,134,511,138,510,140,509,138,509,141,509,140,507,140\" />\n";
  $output .= "            <area data-timezone=\"Asia/Manila\" data-country=\"PH\" data-pin=\"502,126\" data-offset=\"8\" shape=\"poly\" coords=\"505,126,505,127,506,126,507,127,506,127,507,128,507,129,504,127,504,128,503,127,501,127,502,126,501,125,501,126,500,125,500,123,501,123,501,119,504,119,504,122,502,124,503,127,505,126\" />\n";
  $output .= "            <area data-timezone=\"Asia/Muscat\" data-country=\"OM\" data-pin=\"398,111\" data-offset=\"4\" shape=\"poly\" coords=\"394,120,392,120,392,122,389,122,387,118,392,117,393,113,392,112,392,110,393,110,393,108,395,110,398,111,400,113,398,116,396,116,396,118,395,119,394,120\" />\n";
  $output .= "            <area data-timezone=\"Asia/Nicosia\" data-country=\"CY\" data-pin=\"356,91\" data-offset=\"2\" shape=\"poly\" coords=\"355,92,354,92,358,90,357,91,357,92,355,92\" />\n";
  $output .= "            <area data-timezone=\"Asia/Novokuznetsk\" data-country=\"RU\" data-pin=\"445,60\" data-offset=\"7\" shape=\"poly\" coords=\"448,55,449,57,447,58,448,59,447,59,449,59,449,60,448,61,449,62,447,63,445,62,444,61,445,61,442,59,441,57,448,55\" />\n";
  $output .= "            <area data-timezone=\"Asia/Novosibirsk\" data-country=\"RU\" data-pin=\"438,58\" data-offset=\"7\" shape=\"poly\" coords=\"441,57,442,59,441,60,439,60,437,61,435,59,430,61,427,60,428,59,426,60,426,59,425,57,427,56,426,56,427,55,425,52,428,51,429,49,437,49,439,48,441,49,441,50,445,50,448,52,447,52,449,54,448,55,441,57\" />\n";
  $output .= "            <area data-timezone=\"Asia/Omsk\" data-country=\"RU\" data-pin=\"422,58\" data-offset=\"7\" shape=\"poly\" coords=\"425,52,427,55,426,56,427,56,425,57,426,59,426,60,422,61,423,60,419,60,418,58,417,58,418,56,419,55,417,54,418,52,419,53,421,53,425,52\" />\n";
  $output .= "            <area data-timezone=\"Asia/Omsk\" data-country=\"RU\" data-pin=\"422,58\" data-offset=\"7\" shape=\"poly\" coords=\"447,63,446,64,448,64,450,66,449,66,450,67,446,68,445,67,442,67,439,65,436,65,434,64,433,65,430,61,435,59,437,61,439,60,442,59,445,61,444,61,445,62,447,63\" />\n";
  $output .= "            <area data-timezone=\"Asia/Phnom_Penh\" data-country=\"KH\" data-pin=\"475,131\" data-offset=\"7\" shape=\"poly\" coords=\"476,131,477,132,473,133,473,131,472,132,471,127,473,126,477,127,477,126,479,126,479,129,476,131\" />\n";
  $output .= "            <area data-timezone=\"Asia/Pontianak\" data-country=\"ID\" data-pin=\"482,150\" data-offset=\"7\" shape=\"poly\" coords=\"493,152,491,156,488,155,486,156,486,155,484,155,483,152,482,151,482,147,483,147,484,149,486,148,487,147,490,148,490,149,492,149,491,150,492,150,493,152\" />\n";
  $output .= "            <area data-timezone=\"Asia/Pyongyang\" data-country=\"KP\" data-pin=\"510,85\" data-offset=\"9\" shape=\"poly\" coords=\"512,86,509,87,508,86,509,86,509,85,509,84,508,84,507,83,510,82,511,80,514,81,513,80,515,80,517,78,518,80,516,80,516,82,512,84,514,86,512,86\" />\n";
  $output .= "            <area data-timezone=\"Asia/Qatar\" data-country=\"QA\" data-pin=\"386,108\" data-offset=\"3\" shape=\"poly\" coords=\"386,108,385,109,385,108,385,106,386,108\" />\n";
  $output .= "            <area data-timezone=\"Asia/Qyzylorda\" data-country=\"KZ\" data-pin=\"409,75\" data-offset=\"6\" shape=\"poly\" coords=\"413,63,413,65,414,66,410,68,408,68,407,70,404,68,405,67,404,65,400,64,402,63,401,62,402,62,404,62,402,61,403,61,402,61,402,60,410,59,411,61,410,63,413,63\" />\n";
  $output .= "            <area data-timezone=\"Asia/Qyzylorda\" data-country=\"KZ\" data-pin=\"409,75\" data-offset=\"6\" shape=\"poly\" coords=\"410,78,409,77,403,77,401,76,401,74,402,72,400,72,400,73,401,73,399,73,399,74,398,74,402,70,409,73,412,73,412,76,413,77,413,78,410,79,410,78\" />\n";
  $output .= "            <area data-timezone=\"Asia/Rangoon\" data-country=\"MM\" data-pin=\"300,150\" data-offset=\"6.5\" shape=\"poly\" coords=\"464,125,465,127,466,130,464,133,465,129,463,127,463,122,462,123,461,121,461,122,460,122,459,124,459,123,458,124,458,123,458,123,458,122,457,123,458,121,457,118,457,119,456,118,457,118,456,118,457,118,456,117,455,116,455,116,454,115,455,113,456,110,457,110,459,106,461,104,462,105,461,104,463,102,464,104,465,106,465,107,463,109,463,110,465,110,465,111,466,112,465,113,467,113,467,114,469,114,467,116,463,117,463,119,462,119,465,123,464,125\" />\n";
  $output .= "            <area data-timezone=\"Asia/Riyadh\" data-country=\"SA\" data-pin=\"378,109\" data-offset=\"3\" shape=\"poly\" coords=\"372,121,371,123,368,117,365,115,364,110,362,109,359,103,358,103,358,101,360,101,363,99,362,98,365,96,367,97,375,101,381,102,381,104,384,106,383,107,385,109,386,109,386,110,388,112,392,112,393,113,392,117,381,120,377,124,377,122,374,121,372,121,372,121\" />\n";
  $output .= "            <area data-timezone=\"Asia/Sakhalin\" data-country=\"RU\" data-pin=\"538,72\" data-offset=\"11\" shape=\"poly\" coords=\"540,67,541,69,540,68,538,68,538,70,539,73,539,73,538,72,537,74,536,69,537,65,536,63,536,61,538,60,537,60,538,60,539,62,538,64,540,67\" />\n";
  $output .= "            <area data-timezone=\"Asia/Samarkand\" data-country=\"UZ\" data-pin=\"411,84\" data-offset=\"5\" shape=\"poly\" coords=\"414,85,414,86,413,88,411,88,411,87,404,83,403,81,401,81,400,80,398,79,397,79,398,80,397,79,395,80,395,81,393,81,393,75,398,74,397,76,399,77,401,76,403,78,409,77,410,78,410,80,411,80,411,81,411,83,412,83,412,85,414,85\" />\n";
  $output .= "            <area data-timezone=\"Asia/Seoul\" data-country=\"KR\" data-pin=\"512,87\" data-offset=\"9\" shape=\"poly\" coords=\"515,91,513,92,512,93,512,92,511,93,510,92,512,90,510,89,512,88,511,87,512,86,514,86,515,87,516,90,515,91\" />\n";
  $output .= "            <area data-timezone=\"Asia/Shanghai\" data-country=\"CN\" data-pin=\"502,98\" data-offset=\"8\" shape=\"poly\" coords=\"481,100,481,99,484,98,482,96,484,95,482,95,485,94,484,92,485,87,484,86,485,84,487,83,490,82,490,81,491,80,489,80,487,79,485,78,487,77,486,76,486,75,487,75,489,75,497,72,499,73,500,71,497,70,493,71,493,70,495,67,496,67,499,66,499,66,501,63,500,62,501,61,507,61,504,63,505,64,509,64,510,65,508,70,507,69,504,71,506,72,505,72,505,74,503,74,504,75,504,76,505,76,506,78,507,79,508,78,509,82,502,85,503,84,502,84,502,84,504,83,502,82,498,85,496,85,496,86,497,86,498,86,498,88,499,88,501,87,505,88,504,89,500,90,499,92,500,93,501,96,503,97,500,97,503,98,500,100,504,100,502,101,503,101,502,101,503,103,501,103,500,106,499,105,500,106,499,108,499,107,498,109,497,109,496,110,494,111,494,112,491,112,491,113,490,113,489,112,489,113,489,113,487,112,487,111,486,111,487,109,486,108,485,108,485,106,483,107,482,106,482,105,481,105,482,104,482,102,481,100\" />\n";
  $output .= "            <area data-timezone=\"Asia/Singapore\" data-country=\"SG\" data-pin=\"473,148\" data-offset=\"8\" shape=\"poly\" coords=\"473,148,473,148,473,148\" />\n";
  $output .= "            <area data-timezone=\"Asia/Taipei\" data-country=\"TW\" data-pin=\"503,108\" data-offset=\"8\" shape=\"poly\" coords=\"500,112,500,111,501,109,503,108,503,108,501,113,500,112\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tashkent\" data-country=\"UZ\" data-pin=\"416,81\" data-offset=\"5\" shape=\"poly\" coords=\"414,83,415,83,414,84,412,84,411,81,414,82,418,80,417,81,419,81,420,81,422,82,420,83,418,83,417,82,414,83\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tbilisi\" data-country=\"GE\" data-pin=\"375,80\" data-offset=\"4\" shape=\"poly\" coords=\"371,81,369,81,369,79,367,77,371,78,373,79,375,79,378,81,372,81,371,81\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tehran\" data-country=\"IR\" data-pin=\"386,91\" data-offset=\"3.5\" shape=\"poly\" coords=\"402,98,403,98,401,100,403,102,405,103,405,105,406,105,405,106,403,106,402,108,396,107,394,105,391,106,390,105,386,103,383,100,383,100,381,99,382,100,381,100,379,98,380,97,379,96,377,95,376,93,377,90,376,90,374,87,374,86,373,84,374,84,377,85,380,84,381,84,380,85,381,86,382,87,386,89,390,89,390,88,394,86,399,87,401,89,402,89,402,92,401,93,402,94,401,95,402,98\" />\n";
  $output .= "            <area data-timezone=\"Asia/Thimphu\" data-country=\"BT\" data-pin=\"449,104\" data-offset=\"6\" shape=\"poly\" coords=\"453,105,453,105,450,105,448,105,450,103,453,103,453,105\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tokyo\" data-country=\"JP\" data-pin=\"533,91\" data-offset=\"9\" shape=\"poly\" coords=\"538,75,541,77,542,76,542,78,543,78,540,79,539,80,536,79,534,79,534,80,535,80,533,81,533,79,534,78,534,78,536,78,536,74,538,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tokyo\" data-country=\"JP\" data-pin=\"533,91\" data-offset=\"9\" shape=\"poly\" coords=\"518,93,519,94,519,95,520,95,519,98,518,98,518,97,518,98,517,98,518,95,517,95,517,96,516,96,516,95,517,95,516,95,518,93\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tokyo\" data-country=\"JP\" data-pin=\"533,91\" data-offset=\"9\" shape=\"poly\" coords=\"524,91,527,91,528,88,529,88,528,88,529,89,531,88,533,85,533,82,534,81,535,82,535,81,536,81,537,84,536,86,535,87,534,90,535,90,534,91,533,92,534,91,533,91,531,92,531,91,530,92,528,92,529,92,528,92,528,93,526,94,525,94,526,92,521,93,520,94,518,93,518,93,521,91,524,91\" />\n";
  $output .= "            <area data-timezone=\"Asia/Ulaanbaatar\" data-country=\"MN\" data-pin=\"478,70\" data-offset=\"8\" shape=\"poly\" coords=\"475,81,468,79,463,79,463,77,464,74,463,72,464,71,463,70,465,69,465,69,462,67,464,66,463,65,465,63,470,64,471,66,472,66,478,66,481,68,483,68,487,67,488,70,486,71,486,73,487,75,486,76,487,77,484,79,479,79,475,81\" />\n";
  $output .= "            <area data-timezone=\"Asia/Urumqi\" data-country=\"CN\" data-pin=\"446,77\" data-offset=\"6\" shape=\"poly\" coords=\"464,103,460,103,461,102,460,101,457,101,453,104,450,103,448,105,448,103,443,103,437,100,437,99,438,98,438,99,439,98,438,97,439,97,439,95,437,93,437,92,437,91,438,90,437,88,438,84,437,84,436,82,438,80,438,78,439,78,438,77,442,78,436,76,438,75,437,74,438,71,443,72,443,69,446,68,447,69,450,70,452,72,451,75,459,76,461,79,462,79,464,82,462,82,461,84,463,84,462,85,466,87,466,87,466,87,465,90,465,92,465,92,465,93,464,94,462,94,463,94,462,95,464,96,465,99,465,101,464,102,465,103,464,103\" />\n";
  $output .= "            <area data-timezone=\"Asia/Vientiane\" data-country=\"LA\" data-pin=\"471,120\" data-offset=\"7\" shape=\"poly\" coords=\"477,126,477,127,475,126,476,124,473,119,472,119,471,120,470,120,468,121,469,117,468,117,468,116,467,116,469,114,470,115,469,113,471,114,472,115,474,116,474,116,475,117,473,118,475,119,478,123,479,123,479,126,477,126\" />\n";
  $output .= "            <area data-timezone=\"Asia/Vladivostok\" data-country=\"RU\" data-pin=\"520,78\" data-offset=\"11\" shape=\"poly\" coords=\"519,75,520,74,522,75,525,71,525,70,518,71,518,69,519,68,519,67,518,65,519,64,522,63,522,62,524,63,525,61,524,61,520,61,519,61,519,60,517,60,522,58,518,57,519,56,520,55,518,55,520,54,519,53,520,53,521,52,520,51,520,51,522,49,522,47,523,47,525,48,526,46,520,44,521,43,521,42,519,40,517,40,522,37,522,36,520,35,523,34,522,33,522,33,520,33,520,31,522,30,522,30,521,30,523,31,533,31,532,30,534,30,532,30,536,29,535,29,546,29,539,30,535,33,537,34,537,34,538,35,536,35,537,36,535,36,535,37,532,38,534,39,533,40,534,41,532,42,534,42,533,43,534,44,533,44,533,45,537,47,544,47,545,48,543,49,545,50,545,51,537,51,525,59,528,59,528,60,530,59,529,61,531,60,531,61,531,60,533,60,536,61,535,61,536,63,534,65,534,68,529,74,523,79,522,79,520,78,518,80,517,79,519,78,518,75,519,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Vladivostok\" data-country=\"RU\" data-pin=\"520,78\" data-offset=\"11\" shape=\"poly\" coords=\"532,23,534,24,536,23,536,23,542,24,539,25,533,25,532,26,528,25,529,23,532,23\" />\n";
  $output .= "            <area data-timezone=\"Asia/Yakutsk\" data-country=\"RU\" data-pin=\"516,47\" data-offset=\"10\" shape=\"poly\" coords=\"518,57,522,58,518,60,519,60,519,61,524,61,525,61,524,63,522,62,522,63,519,64,518,66,519,66,519,68,518,68,513,67,510,62,506,61,501,61,500,62,501,62,501,63,499,66,499,67,496,67,491,66,485,68,480,67,480,66,481,66,480,65,481,64,484,64,487,63,489,63,491,62,490,61,495,59,495,58,493,58,492,56,496,55,496,55,495,54,496,53,499,53,498,51,495,51,495,50,493,49,488,52,487,51,484,52,482,51,484,48,483,48,483,47,483,46,482,46,483,45,482,44,480,44,481,44,481,43,476,42,478,41,477,41,477,39,476,38,478,38,478,35,477,34,482,34,484,32,488,31,487,31,487,30,485,29,484,29,485,28,484,28,485,27,488,27,489,28,493,27,505,29,506,28,506,27,507,27,516,28,515,28,516,29,515,29,516,30,514,30,516,31,515,31,518,32,520,32,520,33,522,33,522,33,523,34,520,35,522,36,522,37,517,40,519,40,521,42,521,43,520,44,526,46,525,48,523,47,522,47,522,49,520,51,520,51,521,52,520,53,519,53,520,54,518,55,520,55,519,56,518,57\" />\n";
  $output .= "            <area data-timezone=\"Asia/Yerevan\" data-country=\"AM\" data-pin=\"374,83\" data-offset=\"4\" shape=\"poly\" coords=\"375,81,377,83,376,83,378,84,378,85,377,85,376,84,373,83,372,81,375,81\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Azores\" data-country=\"PT\" data-pin=\"257,87\" data-offset=\"-1\" shape=\"rect\" coords=\"248,93,258,79\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Bermuda\" data-country=\"BM\" data-pin=\"192,96\" data-offset=\"-4\" shape=\"rect\" coords=\"187,101,197,91\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Canary\" data-country=\"ES\" data-pin=\"274,103\" data-offset=\"0\" shape=\"rect\" coords=\"265,109,283,96\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Cape_Verde\" data-country=\"CV\" data-pin=\"261,125\" data-offset=\"-1\" shape=\"rect\" coords=\"253,130,267,116\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Faroe\" data-country=\"FO\" data-pin=\"289,47\" data-offset=\"0\" shape=\"rect\" coords=\"282,53,295,41\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Madeira\" data-country=\"PT\" data-pin=\"272,96\" data-offset=\"0\" shape=\"rect\" coords=\"266,105,279,90\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Reykjavik\" data-country=\"IS\" data-pin=\"264,43\" data-offset=\"0\" shape=\"rect\" coords=\"261,50,277,34\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/South_Georgia\" data-country=\"GS\" data-pin=\"239,240\" data-offset=\"-2\" shape=\"rect\" coords=\"230,249,256,239\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/St_Helena\" data-country=\"SH\" data-pin=\"291,177\" data-offset=\"0\" shape=\"rect\" coords=\"276,217,291,163\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Stanley\" data-country=\"FK\" data-pin=\"204,236\" data-offset=\"-3\" shape=\"rect\" coords=\"193,243,209,230\" />\n";
  $output .= "            <area data-timezone=\"Australia/Adelaide\" data-country=\"AU\" data-pin=\"531,208\" data-offset=\"10.5\" shape=\"poly\" coords=\"525,193,535,193,535,213,533,212,533,211,531,209,533,210,532,210,530,209,531,208,530,207,530,209,528,209,529,208,530,206,530,204,530,205,527,208,527,208,525,208,526,208,525,207,523,204,519,202,515,203,515,193,525,193\" />\n";
  $output .= "            <area data-timezone=\"Australia/Brisbane\" data-country=\"AU\" data-pin=\"555,196\" data-offset=\"10\" shape=\"poly\" coords=\"550,187,551,188,551,189,551,189,553,190,555,193,555,195,556,197,554,197,552,199,550,198,548,198,535,198,535,193,530,193,530,178,533,180,535,179,536,175,536,173,537,171,536,171,537,168,539,170,540,174,541,174,542,175,544,181,548,184,549,188,550,188,550,187\" />\n";
  $output .= "            <area data-timezone=\"Australia/Broken_Hill\" data-country=\"AU\" data-pin=\"536,203\" data-offset=\"10.5\" shape=\"poly\" coords=\"535,204,535,202,537,203,536,204,535,204\" />\n";
  $output .= "            <area data-timezone=\"Australia/Currie\" data-country=\"AU\" data-pin=\"540,217\" data-offset=\"11\" shape=\"poly\" coords=\"540,216,540,216,540,216\" />\n";
  $output .= "            <area data-timezone=\"Australia/Darwin\" data-country=\"AU\" data-pin=\"518,171\" data-offset=\"9.5\" shape=\"poly\" coords=\"515,175,516,175,516,174,516,174,517,171,518,171,518,171,518,171,519,170,521,170,521,169,520,169,524,170,525,171,527,170,526,170,527,170,527,171,528,170,528,171,527,172,527,172,526,174,525,175,530,178,530,193,515,193,515,175\" />\n";
  $output .= "            <area data-timezone=\"Australia/Eucla\" data-country=\"AU\" data-pin=\"515,203\" data-offset=\"8.8\" shape=\"poly\" coords=\"515,202,514,203,509,204,509,202,515,202\" />\n";
  $output .= "            <area data-timezone=\"Australia/Hobart\" data-country=\"AU\" data-pin=\"546,221\" data-offset=\"11\" shape=\"poly\" coords=\"547,218,547,222,545,221,545,223,542,222,542,220,543,221,541,218,542,218,545,219,545,218,547,218\" />\n";
  $output .= "            <area data-timezone=\"Australia/Lindeman\" data-country=\"AU\" data-pin=\"548,184\" data-offset=\"10\" shape=\"poly\" coords=\"548,183,548,183,548,183\" />\n";
  $output .= "            <area data-timezone=\"Australia/Lord_Howe\" data-country=\"AU\" data-pin=\"565,203\" data-offset=\"11\" shape=\"poly\" coords=\"565,203,565,203,565,203\" />\n";
  $output .= "            <area data-timezone=\"Australia/Melbourne\" data-country=\"AU\" data-pin=\"542,213\" data-offset=\"11\" shape=\"poly\" coords=\"538,208,539,208,541,210,547,210,547,211,550,213,546,213,544,214,544,215,542,214,541,214,542,214,542,213,539,215,536,214,535,213,535,207,537,207,538,208\" />\n";
  $output .= "            <area data-timezone=\"Australia/Perth\" data-country=\"AU\" data-pin=\"493,203\" data-offset=\"8\" shape=\"poly\" coords=\"509,175,509,175,509,174,510,174,510,173,511,174,512,173,514,175,513,176,514,175,514,176,514,175,515,175,515,202,509,202,509,204,507,205,506,207,500,207,497,209,493,208,492,207,492,206,493,205,493,203,491,199,489,194,490,194,489,193,490,194,490,194,489,191,490,187,490,186,491,187,491,186,495,184,496,185,502,183,504,180,504,179,505,177,506,179,507,178,506,177,508,177,507,177,508,176,507,177,507,176,508,175,509,176,508,175,509,175\" />\n";
  $output .= "            <area data-timezone=\"Australia/Sydney\" data-country=\"AU\" data-pin=\"552,206\" data-offset=\"11\" shape=\"poly\" coords=\"551,209,550,209,550,213,547,211,547,210,541,210,539,208,535,207,535,204,536,204,537,203,535,202,535,198,548,198,550,198,552,199,554,197,556,197,555,202,552,206,551,206,552,206,551,209\" />\n";
  $output .= "            <area data-timezone=\"Europe/Amsterdam\" data-country=\"NL\" data-pin=\"308,63\" data-offset=\"1\" shape=\"poly\" coords=\"310,64,310,65,308,64,306,64,308,64,307,63,308,63,310,63,309,62,309,61,312,61,311,62,312,63,311,63,310,64\" />\n";
  $output .= "            <area data-timezone=\"Europe/Andorra\" data-country=\"AD\" data-pin=\"303,79\" data-offset=\"1\" shape=\"poly\" coords=\"303,79,302,79,303,79\" />\n";
  $output .= "            <area data-timezone=\"Europe/Athens\" data-country=\"GR\" data-pin=\"340,87\" data-offset=\"2\" shape=\"poly\" coords=\"339,87,339,88,338,87,339,89,338,89,337,89,337,88,336,89,335,87,336,86,339,86,335,86,335,85,335,85,333,84,335,82,344,81,344,82,340,82,341,83,340,83,340,83,338,82,339,85,338,84,338,85,338,85,340,86,340,87,339,87\" />\n";
  $output .= "            <area data-timezone=\"Europe/Belgrade\" data-country=\"RS\" data-pin=\"334,75\" data-offset=\"1\" shape=\"poly\" coords=\"336,75,338,76,337,77,338,78,337,79,334,80,333,79,334,78,332,78,333,77,332,76,332,75,332,75,331,74,334,73,336,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Bucharest\" data-country=\"RO\" data-pin=\"344,76\" data-offset=\"2\" shape=\"poly\" coords=\"347,74,349,75,348,77,345,76,340,77,338,77,337,76,338,76,336,75,336,75,334,73,335,73,337,71,339,70,342,70,345,70,347,72,347,74\" />\n";
  $output .= "            <area data-timezone=\"Europe/Berlin\" data-country=\"DE\" data-pin=\"322,63\" data-offset=\"1\" shape=\"poly\" coords=\"312,68,311,68,310,64,312,63,311,62,312,61,314,61,314,60,316,61,315,60,314,59,315,59,314,59,314,58,317,59,317,59,319,59,318,60,321,59,324,60,324,62,324,62,325,65,320,66,323,69,321,70,322,71,313,71,314,68,312,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Budapest\" data-country=\"HU\" data-pin=\"332,71\" data-offset=\"1\" shape=\"poly\" coords=\"335,73,330,74,327,72,328,72,327,71,329,70,331,70,335,69,338,70,335,73\" />\n";
  $output .= "            <area data-timezone=\"Europe/Chisinau\" data-country=\"MD\" data-pin=\"348,72\" data-offset=\"2\" shape=\"poly\" coords=\"350,73,348,73,348,73,347,74,347,72,344,70,346,69,349,70,350,73\" />\n";
  $output .= "            <area data-timezone=\"Europe/Bratislava\" data-country=\"SK\" data-pin=\"329,70\" data-offset=\"1\" shape=\"poly\" coords=\"328,70,328,69,331,68,338,68,337,69,334,69,331,70,328,70\" />\n";
  $output .= "            <area data-timezone=\"Europe/Brussels\" data-country=\"BE\" data-pin=\"307,65\" data-offset=\"1\" shape=\"poly\" coords=\"305,65,304,65,308,64,311,66,309,68,308,66,307,67,305,65\" />\n";
  $output .= "            <area data-timezone=\"Europe/Copenhagen\" data-country=\"DK\" data-pin=\"321,57\" data-offset=\"1\" shape=\"poly\" coords=\"316,58,316,59,314,58,314,58,313,57,314,56,318,54,317,55,318,56,316,58\" />\n";
  $output .= "            <area data-timezone=\"Europe/Dublin\" data-country=\"IE\" data-pin=\"290,61\" data-offset=\"0\" shape=\"poly\" coords=\"289,60,290,60,289,63,284,64,283,64,284,64,283,64,284,62,286,62,283,62,285,61,283,61,284,60,283,60,286,60,286,59,285,59,288,58,288,58,286,59,289,60\" />\n";
  $output .= "            <area data-timezone=\"Europe/Gibraltar\" data-country=\"GI\" data-pin=\"291,90\" data-offset=\"1\" shape=\"poly\" coords=\"291,90,291,90,291,90\" />\n";
  $output .= "            <area data-timezone=\"Europe/Guernsey\" data-country=\"GG\" data-pin=\"296,68\" data-offset=\"0\" shape=\"poly\" coords=\"296,68,296,68,296,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Helsinki\" data-country=\"FI\" data-pin=\"342,50\" data-offset=\"2\" shape=\"poly\" coords=\"344,49,338,50,338,49,336,49,336,47,335,46,336,45,337,45,338,44,342,42,342,41,339,40,340,39,339,38,339,37,334,35,336,35,337,35,342,36,344,33,347,33,349,34,347,36,350,37,348,38,350,40,349,42,351,43,350,44,353,45,346,49,344,49\" />\n";
  $output .= "            <area data-timezone=\"Europe/Isle_of_Man\" data-country=\"IM\" data-pin=\"293,60\" data-offset=\"0\" shape=\"poly\" coords=\"293,59,292,60,293,59\" />\n";
  $output .= "            <area data-timezone=\"Europe/Istanbul\" data-country=\"TR\" data-pin=\"348,82\" data-offset=\"2\" shape=\"poly\" coords=\"361,89,360,90,360,90,360,88,355,90,352,89,349,90,347,89,346,89,347,88,345,88,346,88,345,87,345,87,344,86,344,86,345,86,345,86,345,84,343,84,344,83,345,83,350,82,349,81,352,81,354,80,358,80,361,81,364,82,371,81,373,82,373,83,375,84,373,84,374,86,374,87,375,88,374,88,371,88,365,89,361,89\" />\n";
  $output .= "            <area data-timezone=\"Europe/Jersey\" data-country=\"JE\" data-pin=\"296,68\" data-offset=\"0\" shape=\"poly\" coords=\"297,68,296,68,297,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Kaliningrad\" data-country=\"RU\" data-pin=\"334,59\" data-offset=\"3\" shape=\"poly\" coords=\"335,58,334,58,338,58,338,59,333,59,334,59,333,59,333,58,335,58\" />\n";
  $output .= "            <area data-timezone=\"Europe/Kiev\" data-country=\"UA\" data-pin=\"351,66\" data-offset=\"2\" shape=\"poly\" coords=\"365,70,364,72,362,72,362,71,361,70,358,70,358,71,357,71,359,73,358,73,354,73,353,72,354,73,353,72,349,74,350,75,348,75,347,74,348,73,350,73,349,70,346,69,341,70,338,68,338,67,340,66,339,64,341,63,351,65,352,63,356,63,357,64,357,64,357,65,359,65,359,66,362,66,363,67,367,67,366,68,367,69,366,69,366,70,365,70\" />\n";
  $output .= "            <area data-timezone=\"Europe/Lisbon\" data-country=\"PT\" data-pin=\"285,85\" data-offset=\"0\" shape=\"poly\" coords=\"287,87,287,88,285,88,286,86,285,86,285,85,284,85,286,82,285,80,286,80,286,80,289,80,290,81,288,82,288,84,287,84,288,86,287,87\" />\n";
  $output .= "            <area data-timezone=\"Europe/Ljubljana\" data-country=\"SI\" data-pin=\"324,73\" data-offset=\"1\" shape=\"poly\" coords=\"327,72,328,73,326,73,326,74,323,74,323,74,322,73,323,72,327,72\" />\n";
  $output .= "            <area data-timezone=\"Europe/London\" data-country=\"GB\" data-pin=\"300,64\" data-offset=\"0\" shape=\"poly\" coords=\"296,64,294,64,291,64,293,62,293,62,292,62,293,61,295,61,295,61,295,60,294,59,295,58,291,58,292,58,292,56,291,57,290,58,291,56,292,55,290,55,291,55,290,54,292,54,291,53,292,53,292,52,295,52,293,53,294,54,293,54,297,54,296,56,294,56,296,56,294,56,296,57,298,59,300,60,300,61,299,60,300,61,300,62,303,62,303,63,301,64,302,64,302,65,294,66,294,66,290,67,293,65,295,65,296,64,296,64\" />\n";
  $output .= "            <area data-timezone=\"Europe/Luxembourg\" data-country=\"LU\" data-pin=\"310,67\" data-offset=\"1\" shape=\"poly\" coords=\"310,67,310,67,310,66,311,67,310,67\" />\n";
  $output .= "            <area data-timezone=\"Europe/Madrid\" data-country=\"ES\" data-pin=\"294,83\" data-offset=\"1\" shape=\"poly\" coords=\"299,87,296,89,293,89,290,90,289,89,290,88,289,89,287,87,288,86,287,84,288,84,288,82,290,81,289,80,286,80,286,80,285,80,286,79,285,78,287,77,297,78,299,79,306,79,305,80,301,82,301,82,299,84,300,85,299,87\" />\n";
  $output .= "            <area data-timezone=\"Europe/Malta\" data-country=\"MT\" data-pin=\"324,90\" data-offset=\"1\" shape=\"poly\" coords=\"324,90,324,90,324,90\" />\n";
  $output .= "            <area data-timezone=\"Europe/Mariehamn\" data-country=\"AX\" data-pin=\"333,50\" data-offset=\"2\" shape=\"poly\" coords=\"335,49,335,49,335,49\" />\n";
  $output .= "            <area data-timezone=\"Europe/Minsk\" data-country=\"BY\" data-pin=\"346,60\" data-offset=\"3\" shape=\"poly\" coords=\"350,64,342,63,339,64,339,63,339,63,340,62,339,60,343,60,343,59,345,58,344,58,344,57,347,56,351,57,352,57,351,59,355,61,352,61,353,63,352,63,351,65,350,64\" />\n";
  $output .= "            <area data-timezone=\"Europe/Monaco\" data-country=\"MC\" data-pin=\"312,77\" data-offset=\"1\" shape=\"poly\" coords=\"312,77,312,77,312,77\" />\n";
  $output .= "            <area data-timezone=\"Europe/Moscow\" data-country=\"RU\" data-pin=\"363,57\" data-offset=\"4\" shape=\"poly\" coords=\"391,28,394,28,392,29,393,29,392,29,393,30,392,30,396,32,389,32,390,31,386,30,389,29,388,29,389,29,388,29,391,28\" />\n";
  $output .= "            <area data-timezone=\"Europe/Moscow\" data-country=\"RU\" data-pin=\"363,57\" data-offset=\"4\" shape=\"poly\" coords=\"400,23,413,22,415,22,414,23,402,24,399,25,400,26,398,25,399,26,397,26,398,26,395,27,396,27,394,27,396,27,394,27,395,28,395,28,390,28,393,27,389,27,394,27,392,26,395,26,393,25,395,25,393,25,400,23\" />\n";
  $output .= "            <area data-timezone=\"Europe/Moscow\" data-country=\"RU\" data-pin=\"363,57\" data-offset=\"4\" shape=\"poly\" coords=\"378,75,378,76,380,77,379,78,381,80,380,81,375,79,373,79,371,78,367,78,361,75,364,73,363,72,366,72,364,71,364,71,366,70,367,70,366,69,367,69,366,68,367,67,363,67,362,66,359,66,359,65,357,65,357,64,357,64,356,63,353,63,352,62,355,61,351,59,352,57,347,56,346,54,346,54,346,54,346,52,347,51,347,50,350,50,348,49,348,49,346,49,353,45,350,44,351,43,349,42,350,40,348,38,350,37,348,36,348,35,347,35,353,33,355,34,354,34,354,34,354,34,356,34,355,35,360,35,368,37,369,39,364,40,353,38,356,39,355,40,358,40,357,41,358,42,358,42,362,44,363,43,361,42,368,42,366,41,370,39,374,40,374,38,373,38,374,37,372,36,377,36,378,37,375,38,378,39,379,39,380,37,382,37,381,37,387,36,390,35,391,35,389,35,390,36,389,36,398,35,399,35,399,36,400,36,402,35,400,34,401,34,408,34,408,35,409,36,409,37,410,37,410,37,410,38,399,42,399,47,394,47,392,49,387,49,387,50,383,50,383,51,381,50,381,49,380,48,379,48,377,50,378,50,378,51,379,52,377,53,380,54,378,54,378,55,382,54,386,56,386,57,388,57,388,56,388,56,389,56,389,57,390,57,389,58,389,59,389,60,386,59,383,59,383,60,380,61,381,62,372,63,371,64,372,65,369,65,370,67,370,68,371,69,370,70,372,71,374,70,374,70,378,71,378,71,379,72,377,73,379,73,378,74,379,74,378,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Oslo\" data-country=\"NO\" data-pin=\"318,50\" data-offset=\"1\" shape=\"poly\" coords=\"314,53,311,53,312,53,309,52,309,52,311,52,310,52,311,51,309,51,312,49,310,50,310,49,309,49,310,49,309,49,308,49,309,49,308,48,312,49,313,48,309,48,308,48,309,48,308,48,309,48,308,47,311,47,308,46,311,47,310,46,311,46,312,46,310,46,314,46,312,45,312,45,314,46,313,45,314,45,314,45,319,44,318,44,319,43,317,44,316,44,320,42,319,42,322,41,320,41,321,41,321,41,321,40,322,40,321,40,324,39,322,40,323,39,322,39,323,39,322,39,323,39,322,38,326,38,324,38,326,37,325,37,326,37,325,37,327,36,328,37,327,37,328,37,327,36,330,36,327,36,329,36,329,35,330,35,329,35,330,34,333,35,332,34,333,34,333,34,334,33,333,35,335,34,334,34,335,33,337,34,336,33,337,33,335,33,336,33,339,33,339,33,341,32,340,32,341,32,343,32,342,32,342,33,344,32,344,33,345,33,346,31,348,32,346,32,347,32,346,33,348,32,352,33,348,33,349,33,349,34,352,34,348,35,349,34,346,33,343,34,343,35,342,36,337,35,335,34,333,35,334,35,333,36,331,36,330,37,328,37,326,40,324,40,324,41,323,42,324,43,323,43,320,44,320,47,321,48,320,48,321,49,320,50,319,52,318,51,318,50,317,52,316,51,314,53\" />\n";
  $output .= "            <area data-timezone=\"Europe/Paris\" data-country=\"FR\" data-pin=\"304,69\" data-offset=\"1\" shape=\"poly\" coords=\"307,78,305,78,305,79,304,79,298,78,297,78,298,76,298,74,299,75,298,74,298,73,297,73,297,71,296,71,293,70,292,70,293,70,292,69,295,69,296,69,298,69,297,67,298,68,301,68,300,67,303,66,303,65,304,65,307,67,308,66,309,68,314,68,313,71,312,71,310,73,311,73,312,74,311,75,312,76,313,76,313,77,310,78,307,78\" />\n";
  $output .= "            <area data-timezone=\"Europe/Podgorica\" data-country=\"ME\" data-pin=\"332,79\" data-offset=\"1\" shape=\"poly\" coords=\"332,78,334,78,333,79,332,80,331,79,332,78\" />\n";
  $output .= "            <area data-timezone=\"Europe/Prague\" data-country=\"CZ\" data-pin=\"324,67\" data-offset=\"1\" shape=\"poly\" coords=\"325,68,323,69,321,68,320,66,325,65,327,66,328,66,329,66,331,67,328,69,325,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Riga\" data-country=\"LV\" data-pin=\"340,55\" data-offset=\"2\" shape=\"poly\" coords=\"337,54,340,55,341,55,341,54,342,53,346,54,347,56,344,57,341,56,335,57,335,55,337,54\" />\n";
  $output .= "            <area data-timezone=\"Europe/Rome\" data-country=\"IT\" data-pin=\"321,80\" data-offset=\"1\" shape=\"poly\" coords=\"328,83,328,84,329,85,326,87,327,85,326,83,319,79,317,77,315,76,313,77,313,76,312,76,311,75,312,74,311,74,314,73,315,74,315,72,317,73,317,72,320,72,323,72,322,73,323,74,320,74,321,75,321,76,323,77,325,80,327,80,327,81,331,83,331,84,328,82,328,83\" />\n";
  $output .= "            <area data-timezone=\"Europe/Samara\" data-country=\"RU\" data-pin=\"384,61\" data-offset=\"4\" shape=\"poly\" coords=\"383,59,388,59,387,60,387,62,385,64,381,62,381,62,380,61,383,60,383,59\" />\n";
  $output .= "            <area data-timezone=\"Europe/Samara\" data-country=\"RU\" data-pin=\"384,61\" data-offset=\"4\" shape=\"poly\" coords=\"391,56,389,57,389,56,388,56,388,56,388,57,386,57,385,56,386,55,385,54,386,54,387,53,390,53,391,55,390,56,391,56\" />\n";
  $output .= "            <area data-timezone=\"Europe/San_Marino\" data-country=\"SM\" data-pin=\"321,77\" data-offset=\"1\" shape=\"poly\" coords=\"321,77,321,77,321,77\" />\n";
  $output .= "            <area data-timezone=\"Europe/Sarajevo\" data-country=\"BA\" data-pin=\"331,77\" data-offset=\"1\" shape=\"poly\" coords=\"326,75,332,75,332,76,333,77,332,77,333,77,332,77,331,79,329,78,326,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Simferopol\" data-country=\"RU\" data-pin=\"357,75\" data-offset=\"2\" shape=\"poly\" coords=\"358,73,359,75,361,74,356,76,355,74,355,74,354,74,356,74,356,73,358,73\" />\n";
  $output .= "            <area data-timezone=\"Europe/Skopje\" data-country=\"MK\" data-pin=\"336,80\" data-offset=\"1\" shape=\"poly\" coords=\"337,79,338,80,338,81,335,82,334,81,335,80,337,79\" />\n";
  $output .= "            <area data-timezone=\"Europe/Sofia\" data-country=\"BG\" data-pin=\"339,79\" data-offset=\"2\" shape=\"poly\" coords=\"346,79,346,79,347,80,344,80,344,81,338,81,337,79,338,78,337,77,338,76,338,77,342,77,345,76,348,77,346,79\" />\n";
  $output .= "            <area data-timezone=\"Europe/Stockholm\" data-country=\"SE\" data-pin=\"330,51\" data-offset=\"1\" shape=\"poly\" coords=\"321,56,322,56,320,54,320,53,319,53,319,52,319,52,320,50,321,49,320,48,321,48,320,47,320,45,321,43,324,43,323,42,324,41,324,40,326,40,327,38,327,37,329,36,330,37,330,36,334,36,333,35,339,37,339,38,340,39,339,39,340,40,336,40,337,41,336,41,336,42,335,42,336,43,334,44,330,45,329,45,330,46,329,46,329,46,328,47,329,49,332,50,330,51,331,51,330,52,329,51,329,52,327,52,328,53,327,53,328,53,327,53,328,54,327,56,324,56,324,58,322,58,321,58,322,57,321,56\" />\n";
  $output .= "            <area data-timezone=\"Europe/Tallinn\" data-country=\"EE\" data-pin=\"341,51\" data-offset=\"2\" shape=\"poly\" coords=\"340,51,343,51,347,51,346,52,346,54,344,54,342,53,341,54,341,53,339,52,339,51,340,51\" />\n";
  $output .= "            <area data-timezone=\"Europe/Tirane\" data-country=\"AL\" data-pin=\"333,81\" data-offset=\"1\" shape=\"poly\" coords=\"334,84,332,83,333,81,332,80,333,79,334,80,334,81,335,82,334,84\" />\n";
  $output .= "            <area data-timezone=\"Europe/Uzhgorod\" data-country=\"UA\" data-pin=\"337,69\" data-offset=\"2\" shape=\"poly\" coords=\"338,68,341,70,338,70,337,69,338,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vaduz\" data-country=\"LI\" data-pin=\"316,71\" data-offset=\"1\" shape=\"poly\" coords=\"316,71,316,72,316,71\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vatican\" data-country=\"VA\" data-pin=\"321,80\" data-offset=\"1\" shape=\"poly\" coords=\"321,80,321,80,321,80\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vienna\" data-country=\"AT\" data-pin=\"327,70\" data-offset=\"1\" shape=\"poly\" coords=\"316,71,322,71,321,70,325,68,328,69,329,70,327,71,328,72,327,72,324,73,321,72,320,72,317,72,316,71\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vilnius\" data-country=\"LT\" data-pin=\"342,59\" data-offset=\"2\" shape=\"poly\" coords=\"335,57,341,56,345,58,343,59,343,60,339,60,338,59,338,58,335,58,335,57\" />\n";
  $output .= "            <area data-timezone=\"Europe/Volgograd\" data-country=\"RU\" data-pin=\"374,69\" data-offset=\"4\" shape=\"poly\" coords=\"378,69,379,70,381,71,382,72,381,72,382,73,381,74,381,73,381,74,378,74,379,73,377,73,379,72,378,71,378,71,377,71,376,70,374,70,374,70,373,71,370,70,371,69,370,68,370,67,369,65,372,65,371,64,372,63,380,62,385,63,384,64,381,66,381,67,379,66,378,69\" />\n";
  $output .= "            <area data-timezone=\"Europe/Volgograd\" data-country=\"RU\" data-pin=\"374,69\" data-offset=\"4\" shape=\"poly\" coords=\"381,49,381,49,381,50,383,51,383,50,385,50,389,50,389,51,390,52,390,53,386,53,386,54,385,54,386,55,386,57,382,54,378,55,378,54,380,54,377,53,379,52,378,51,378,50,377,50,379,48,381,49\" />\n";
  $output .= "            <area data-timezone=\"Europe/Warsaw\" data-country=\"PL\" data-pin=\"335,63\" data-offset=\"1\" shape=\"poly\" coords=\"339,66,338,67,338,68,336,68,332,68,329,66,328,66,327,66,325,65,324,62,324,62,324,60,330,59,333,59,332,60,338,59,340,62,339,63,340,65,339,66\" />\n";
  $output .= "            <area data-timezone=\"Europe/Zagreb\" data-country=\"HR\" data-pin=\"327,74\" data-offset=\"1\" shape=\"poly\" coords=\"332,75,326,75,329,78,327,77,324,75,323,75,322,74,326,74,326,73,327,72,330,74,332,73,332,75,332,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Zaporozhye\" data-country=\"UA\" data-pin=\"359,70\" data-offset=\"2\" shape=\"poly\" coords=\"357,71,358,71,358,70,359,70,362,71,361,72,358,73,359,73,357,71\" />\n";
  $output .= "            <area data-timezone=\"Europe/Zurich\" data-country=\"CH\" data-pin=\"314,71\" data-offset=\"1\" shape=\"poly\" coords=\"310,73,310,73,312,71,314,70,316,71,316,72,317,72,317,73,315,73,315,74,314,73,312,74,311,73,310,73\" />\n";
  $output .= "            <area data-timezone=\"Indian/Antananarivo\" data-country=\"MG\" data-pin=\"379,182\" data-offset=\"3\" shape=\"rect\" coords=\"372,192,383,170\" />\n";
  $output .= "            <area data-timezone=\"Indian/Chagos\" data-country=\"IO\" data-pin=\"421,162\" data-offset=\"6\" shape=\"rect\" coords=\"414,167,426,154\" />\n";
  $output .= "            <area data-timezone=\"Indian/Christmas\" data-country=\"CX\" data-pin=\"476,167\" data-offset=\"7\" shape=\"rect\" coords=\"471,173,481,162\" />\n";
  $output .= "            <area data-timezone=\"Indian/Cocos\" data-country=\"CC\" data-pin=\"462,170\" data-offset=\"6.5\" shape=\"rect\" coords=\"456,175,467,165\" />\n";
  $output .= "            <area data-timezone=\"Indian/Comoro\" data-country=\"KM\" data-pin=\"372,169\" data-offset=\"3\" shape=\"rect\" coords=\"367,176,379,164\" />\n";
  $output .= "            <area data-timezone=\"Indian/Kerguelen\" data-country=\"TF\" data-pin=\"417,232\" data-offset=\"5\" shape=\"rect\" coords=\"384,233,429,213\" />\n";
  $output .= "            <area data-timezone=\"Indian/Mahe\" data-country=\"SC\" data-pin=\"392,158\" data-offset=\"4\" shape=\"rect\" coords=\"377,167,394,156\" />\n";
  $output .= "            <area data-timezone=\"Indian/Maldives\" data-country=\"MV\" data-pin=\"423,143\" data-offset=\"5\" shape=\"rect\" coords=\"416,151,428,138\" />\n";
  $output .= "            <area data-timezone=\"Indian/Mauritius\" data-country=\"MU\" data-pin=\"396,184\" data-offset=\"4\" shape=\"rect\" coords=\"394,184,406,167\" />\n";
  $output .= "            <area data-timezone=\"Indian/Mayotte\" data-country=\"YT\" data-pin=\"375,171\" data-offset=\"3\" shape=\"rect\" coords=\"370,177,380,166\" />\n";
  $output .= "            <area data-timezone=\"Indian/Reunion\" data-country=\"RE\" data-pin=\"392,185\" data-offset=\"4\" shape=\"rect\" coords=\"387,191,398,180\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Apia\" data-country=\"WS\" data-pin=\"14,173\" data-offset=\"14\" shape=\"rect\" coords=\"7,178,19,167\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Auckland\" data-country=\"NZ\" data-pin=\"591,211\" data-offset=\"13\" shape=\"poly\" coords=\"582,228,577,226,581,223,585,221,588,218,589,219,591,218,590,219,591,219,590,220,588,222,589,223,586,224,585,226,582,228\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Auckland\" data-country=\"NZ\" data-pin=\"591,211\" data-offset=\"13\" shape=\"poly\" coords=\"594,218,592,219,591,219,592,217,590,215,591,215,591,213,590,211,591,210,590,211,588,207,591,209,591,212,593,212,592,211,594,213,598,213,597,215,595,215,594,218\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Chatham\" data-country=\"NZ\" data-pin=\"6,223\" data-offset=\"13.8\" shape=\"rect\" coords=\"0,229,12,218\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Chuuk\" data-country=\"FM\" data-pin=\"553,138\" data-offset=\"10\" shape=\"rect\" coords=\"530,146,556,128\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Easter\" data-country=\"CL\" data-pin=\"118,195\" data-offset=\"-5\" shape=\"rect\" coords=\"113,200,123,190\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Enderbury\" data-country=\"KI\" data-pin=\"15,155\" data-offset=\"13\" shape=\"rect\" coords=\"4,163,20,150\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Fakaofo\" data-country=\"TK\" data-pin=\"15,166\" data-offset=\"13\" shape=\"rect\" coords=\"7,171,20,159\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Efate\" data-country=\"VU\" data-pin=\"581,179\" data-offset=\"11\" shape=\"rect\" coords=\"573,184,589,172\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Fiji\" data-country=\"FJ\" data-pin=\"597,180\" data-offset=\"13\" shape=\"poly\" coords=\"2,179,2,179,2,179\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Funafuti\" data-country=\"TV\" data-pin=\"599,164\" data-offset=\"12\" shape=\"rect\" coords=\"588,171,605,154\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Galapagos\" data-country=\"EC\" data-pin=\"151,152\" data-offset=\"-6\" shape=\"rect\" coords=\"142,157,156,142\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Gambier\" data-country=\"PF\" data-pin=\"75,189\" data-offset=\"-9\" shape=\"rect\" coords=\"67,194,80,180\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Kwajalein\" data-country=\"MH\" data-pin=\"579,135\" data-offset=\"12\" shape=\"rect\" coords=\"573,140,585,129\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Guadalcanal\" data-country=\"SB\" data-pin=\"567,166\" data-offset=\"11\" shape=\"rect\" coords=\"559,170,581,158\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Guam\" data-country=\"GU\" data-pin=\"541,128\" data-offset=\"10\" shape=\"rect\" coords=\"536,133,547,122\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Honolulu\" data-country=\"US\" data-pin=\"37,114\" data-offset=\"-10\" shape=\"rect\" coords=\"10,118,42,107\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Johnston\" data-country=\"US\" data-pin=\"300,150\" data-offset=\"-10\" shape=\"rect\" coords=\"12,127,22,117\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Kiritimati\" data-country=\"KI\" data-pin=\"38,147\" data-offset=\"14\" shape=\"rect\" coords=\"32,169,50,142\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Kosrae\" data-country=\"FM\" data-pin=\"572,141\" data-offset=\"11\" shape=\"rect\" coords=\"567,146,577,136\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Majuro\" data-country=\"MH\" data-pin=\"585,138\" data-offset=\"12\" shape=\"rect\" coords=\"568,142,587,126\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Midway\" data-country=\"UM\" data-pin=\"4,103\" data-offset=\"-11\" shape=\"rect\" coords=\"-2,108,9,98\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Marquesas\" data-country=\"PF\" data-pin=\"68,165\" data-offset=\"-9.5\" shape=\"rect\" coords=\"60,173,74,158\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Nauru\" data-country=\"NR\" data-pin=\"578,151\" data-offset=\"12\" shape=\"rect\" coords=\"573,156,583,146\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Niue\" data-country=\"NU\" data-pin=\"17,182\" data-offset=\"-11\" shape=\"rect\" coords=\"12,187,22,177\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Norfolk\" data-country=\"NF\" data-pin=\"580,198\" data-offset=\"11.5\" shape=\"rect\" coords=\"575,204,585,193\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Noumea\" data-country=\"NC\" data-pin=\"577,187\" data-offset=\"11\" shape=\"rect\" coords=\"564,193,587,177\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Pago_Pago\" data-country=\"AS\" data-pin=\"16,174\" data-offset=\"-11\" shape=\"rect\" coords=\"10,179,23,163\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Palau\" data-country=\"PW\" data-pin=\"524,138\" data-offset=\"9\" shape=\"rect\" coords=\"514,150,530,132\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Pitcairn\" data-country=\"PN\" data-pin=\"83,192\" data-offset=\"-8\" shape=\"rect\" coords=\"82,197,92,185\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Pohnpei\" data-country=\"FM\" data-pin=\"564,138\" data-offset=\"11\" shape=\"rect\" coords=\"557,145,573,133\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Port_Moresby\" data-country=\"PG\" data-pin=\"545,166\" data-offset=\"10\" shape=\"rect\" coords=\"537,169,566,151\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Rarotonga\" data-country=\"CK\" data-pin=\"34,185\" data-offset=\"-10\" shape=\"rect\" coords=\"24,187,38,165\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Saipan\" data-country=\"MP\" data-pin=\"543,125\" data-offset=\"10\" shape=\"rect\" coords=\"536,126,548,116\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Tahiti\" data-country=\"PF\" data-pin=\"51,179\" data-offset=\"-10\" shape=\"rect\" coords=\"42,196,73,173\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Tarawa\" data-country=\"KI\" data-pin=\"588,148\" data-offset=\"12\" shape=\"rect\" coords=\"583,154,595,144\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Tongatapu\" data-country=\"TO\" data-pin=\"8,185\" data-offset=\"13\" shape=\"rect\" coords=\"1,187,15,176\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Wake\" data-country=\"UM\" data-pin=\"578,118\" data-offset=\"12\" shape=\"rect\" coords=\"573,123,583,113\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Wallis\" data-country=\"WF\" data-pin=\"6,172\" data-offset=\"12\" shape=\"rect\" coords=\"-2,179,11,167\" />\n";
  $output .= "          </map>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile country selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Country\" id=\"profile_view_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile country") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_timezone\" class=\"control-label col-xs-2\">" . __ ( "Time zone") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"TimeZone\" id=\"profile_view_timezone\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile time zone") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile area code field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_areacode\" class=\"control-label col-xs-2\">" . __ ( "Area code") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"AreaCode\" class=\"form-control\" id=\"profile_view_areacode\" placeholder=\"" . __ ( "Profile area code") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile language selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Language\" id=\"profile_view_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile language") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Gateways panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"profile_view_tab_gateways\">\n";

  // Add profile non geographic gateway selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_nggw\" class=\"control-label col-xs-2\">" . __ ( "Non geographic gateway") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"NGGW\" id=\"profile_view_nggw\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway to non geographic calls") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile blocked gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_blockeds\" class=\"control-label col-xs-2\">" . __ ( "Blocked gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Blockeds\" id=\"profile_view_blockeds\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile blocked gateways") . "\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile allowed gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_view_gateways\" class=\"control-label col-xs-2\">" . __ ( "Allowed gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Gateways\" id=\"profile_view_gateways\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile allowed gateways") . "\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/profiles\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#profile_view_country').select2 (\n" .
              "{\n" .
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
              "});\n" .
              "$('#profile_view_language,#profile_view_moh,#profile_view_nggw,#profile_view_timezone').select2 ();\n" .
              "$('#profile_view_blockeds,#profile_view_gateways').select2 ().on ( 'change.select2', function ( e)\n" .
              "{\n" .
              "  var that = this;\n" .
              "  $(e.currentTarget).find ( 'option').each ( function ( index, option)\n" .
              "  {\n" .
              "    if ( $(option).val () < 0)\n" .
              "    {\n" .
              "      $(that).parent ().find ( 'li.select2-selection__choice').eq ( index).addClass ( 'select2-inactive');\n" .
              "    }\n" .
              "  });\n" .
              "});\n" .
              "$('#profile_view_timezone_image').timezonePicker (\n" .
              "{\n" .
              "  readOnly: true\n" .
              "});\n" .
              "$('#profile_view_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#profile_view_form').find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  $('#profile_view_timezone_image').timezonePicker ( 'updateTimezone', $('#profile_view_timezone').val ());\n" .
              "});\n" .
              "$('#profile_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#profile_view_description').val ( data.Description);\n" .
              "  $('#profile_view_domain').val ( data.Domain);\n" .
              "  $('#profile_view_country').append ( $('<option>', { value: data.Country['ISO3166-2'], text: '<span class=\"fi fi-' + data.Country['ISO3166-2'].toLowerCase () + '\"></span> ' + data.Country.Name})).val ( data.Country['ISO3166-2']).trigger ( 'change');\n" .
              "  $('#profile_view_timezone').append ( $('<option>', { value: data.TimeZone, text: data.TimeZone.replace ( '_', ' ')})).val ( data.TimeZone).trigger ( 'change');\n" .
              "  $('#profile_view_areacode').val ( data.AreaCode);\n" .
              "  $('#profile_view_language').append ( $('<option>', { value: data.Language.Code, text: data.Language.Name})).val ( data.Language.Code).trigger ( 'change');\n" .
              "  $('#profile_view_prefix').val ( data.Prefix);\n" .
              "  $('#profile_view_moh').append ( $('<option>', { value: data.MOH.ID, text: data.MOH.Description})).val ( data.MOH.ID).trigger ( 'change');\n" .
              "  $('#profile_view_emergency').bootstrapToggle ( 'enable').bootstrapToggle ( data.Emergency ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#profile_view_nggw').append ( $('<option>', { value: data.NGGW.ID, text: data.NGGW.Description})).val ( data.NGGW.ID).trigger ( 'change');\n" .
              "  if ( ! data.NGGW.Active)\n" .
              "  {\n" .
              "    $('#select2-profile_view_nggw-container.select2-selection__rendered').addClass ( 'select2-inactive');\n" .
              "  }\n" .
              "  var ids = [];\n" .
              "  for ( var id in data.Gateways)\n" .
              "  {\n" .
              "    if ( ! data.Gateways.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    ids.push ( id);\n" .
              "    $('#profile_view_gateways').append ( $('<option>', { value: id, text: data.Gateways[id].Description}));\n" .
              "  }\n" .
              "  $('#profile_view_gateways').val ( ids).trigger ( 'change');\n" .
              "  var ids = [];\n" .
              "  for ( var id in data.Blockeds)\n" .
              "  {\n" .
              "    if ( ! data.Blockeds.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    ids.push ( id);\n" .
              "    $('#profile_view_blockeds').append ( $('<option>', { value: id, text: data.Blockeds[id].Description}));\n" .
              "  }\n" .
              "  $('#profile_view_blockeds').val ( ids).trigger ( 'change');\n" .
              "});\n" .
              "VoIP.rest ( '/profiles/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#profile_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Profile view") . "', text: '" . __ ( "Error viewing profile!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the profile edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Profiles"));
  sys_set_subtitle ( __ ( "profiles edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Profiles"), "link" => "/profiles"),
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
  sys_addjs ( array ( "name" => "multiselect", "src" => "/vendors/multiselect/dist/js/multiselect.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "maphighlight", "src" => "/vendors/maphilight/jquery.maphilight.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "timezonepicker", "src" => "/vendors/timezonepicker/lib/jquery.timezone-picker.js", "dep" => array ( "maphighlight")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"profile_edit_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#profile_edit_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#profile_edit_tab_geographical\">" . __ ( "Geographical") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#profile_edit_tab_gateways\">" . __ ( "Gateways") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"profile_edit_tab_basic\">\n";

  // Add profile description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"profile_edit_description\" placeholder=\"" . __ ( "Profile description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile domain field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Domain\" class=\"form-control\" id=\"profile_edit_domain\" placeholder=\"" . __ ( "Profile domain") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile external prefix field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_prefix\" class=\"control-label col-xs-2\">" . __ ( "External prefix") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Prefix\" class=\"form-control\" id=\"profile_edit_prefix\" placeholder=\"" . __ ( "Prefix to access PSTN calls") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile music on hold selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_moh\" class=\"control-label col-xs-2\">" . __ ( "Music on hold") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"MOH\" id=\"profile_edit_moh\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile music on hold") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile emergency numbers shortcut option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_emergency\" class=\"control-label col-xs-2\">" . __ ( "Emergency shortcut") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Emergency\" id=\"profile_edit_emergency\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Geographical panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"profile_edit_tab_geographical\">\n";

  // Add timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Map") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"hidden\" name=\"Offset\" id=\"profile_edit_offset\" value=\"\">\n";
  $output .= "          <img id=\"profile_edit_timezone_image\" src=\"/vendors/timezonepicker/images/living-600.jpg\" width=\"600\" height=\"300\" usemap=\"#profile_edit_timezone_map\" />\n";
  $output .= "          <img class=\"timezone-pin\" src=\"/vendors/timezonepicker/images/pin.png\" style=\"padding-top: 4px;\" />\n";
  $output .= "          <map name=\"profile_edit_timezone_map\" id=\"profile_edit_timezone_map\">\n";
  $output .= "            <area data-timezone=\"Africa/Abidjan\" data-country=\"CI\" data-pin=\"293,141\" data-offset=\"0\" shape=\"poly\" coords=\"290,142,287,143,288,140,286,139,286,137,287,137,286,136,287,136,286,133,290,132,290,133,291,133,293,134,295,133,296,136,295,139,295,141,290,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Accra\" data-country=\"GH\" data-pin=\"300,141\" data-offset=\"0\" shape=\"poly\" coords=\"301,140,297,142,295,142,295,141,295,139,296,136,295,132,300,132,301,136,301,139,302,140,301,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Addis_Ababa\" data-country=\"ET\" data-pin=\"365,135\" data-offset=\"3\" shape=\"poly\" coords=\"375,142,373,142,370,143,368,143,366,144,360,142,358,139,355,137,355,136,357,136,357,132,358,132,359,129,360,129,361,126,363,127,363,125,364,126,368,126,371,129,370,132,372,132,371,132,372,134,380,137,375,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Algiers\" data-country=\"DZ\" data-pin=\"305,89\" data-offset=\"1\" shape=\"poly\" coords=\"316,100,317,104,317,106,316,106,317,109,319,110,320,111,310,118,306,118,305,117,303,116,300,113,286,105,286,102,286,102,291,100,292,99,294,98,294,97,295,97,295,96,298,96,298,96,297,95,297,92,296,92,305,89,314,88,314,89,314,92,313,94,315,97,316,100\" />\n";
  $output .= "            <area data-timezone=\"Africa/Asmara\" data-country=\"ER\" data-pin=\"365,124\" data-offset=\"3\" shape=\"poly\" coords=\"367,125,372,129,371,129,367,126,364,126,363,125,363,127,361,126,362,122,364,120,366,125,366,124,367,125\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bamako\" data-country=\"ML\" data-pin=\"287,129\" data-offset=\"0\" shape=\"poly\" coords=\"293,129,292,130,291,130,291,133,290,133,290,132,288,133,287,133,287,133,286,132,286,132,286,131,285,129,282,130,281,130,281,128,280,126,281,124,282,125,284,124,291,124,289,108,292,108,302,115,303,116,305,117,305,118,307,118,307,122,306,124,299,125,295,127,294,128,293,128,293,129\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bangui\" data-country=\"CF\" data-pin=\"331,143\" data-offset=\"1\" shape=\"poly\" coords=\"341,142,339,142,338,142,337,143,332,141,331,143,331,144,328,144,327,146,325,142,324,140,326,137,331,137,331,135,334,135,336,132,338,132,339,134,339,135,340,135,340,136,342,137,346,142,343,141,342,142,341,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Banjul\" data-country=\"GM\" data-pin=\"272,128\" data-offset=\"0\" shape=\"poly\" coords=\"277,127,272,128,275,127,277,127\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bissau\" data-country=\"GW\" data-pin=\"274,130\" data-offset=\"0\" shape=\"poly\" coords=\"274,131,274,131,274,131\" />\n";
  $output .= "            <area data-timezone=\"Africa/Blantyre\" data-country=\"MW\" data-pin=\"358,176\" data-offset=\"2\" shape=\"poly\" coords=\"358,172,360,175,359,179,357,177,358,174,356,174,354,173,356,171,355,168,356,168,355,166,358,167,358,169,357,171,358,172\" />\n";
  $output .= "            <area data-timezone=\"Africa/Brazzaville\" data-country=\"CG\" data-pin=\"325,157\" data-offset=\"1\" shape=\"poly\" coords=\"319,157,320,156,319,154,321,154,321,153,323,154,324,153,324,151,323,150,324,148,323,148,322,148,322,146,327,147,328,144,331,144,330,151,327,154,327,157,324,158,324,157,322,158,321,157,320,158,319,157\" />\n";
  $output .= "            <area data-timezone=\"Africa/Bujumbura\" data-country=\"BI\" data-pin=\"349,156\" data-offset=\"2\" shape=\"poly\" coords=\"350,157,349,157,348,154,349,155,350,154,351,154,351,155,350,157\" />\n";
  $output .= "            <area data-timezone=\"Asia/Oral\" data-country=\"KZ\" data-pin=\"386,65\" data-offset=\"5\" shape=\"poly\" coords=\"379,66,381,67,381,67,381,66,382,65,385,64,387,64,391,65,391,67,388,69,386,69,382,70,377,69,379,66\" />\n";
  $output .= "            <area data-timezone=\"Africa/Cairo\" data-country=\"EG\" data-pin=\"352,100\" data-offset=\"2\" shape=\"poly\" coords=\"353,113,342,113,341,100,342,97,348,99,352,97,353,97,353,98,357,98,358,101,357,104,355,102,354,100,354,101,360,110,359,110,359,111,357,114,353,113\" />\n";
  $output .= "            <area data-timezone=\"Africa/Casablanca\" data-country=\"MA\" data-pin=\"287,94\" data-offset=\"0\" shape=\"poly\" coords=\"290,100,288,101,286,102,286,104,278,104,283,101,284,100,284,98,285,96,289,93,290,90,291,90,293,91,295,91,297,92,298,96,295,96,295,97,294,97,294,98,292,99,290,100\" />\n";
  $output .= "            <area data-timezone=\"Africa/Ceuta\" data-country=\"ES\" data-pin=\"291,90\" data-offset=\"1\" shape=\"poly\" coords=\"291,90,291,90,291,90\" />\n";
  $output .= "            <area data-timezone=\"Africa/Conakry\" data-country=\"GN\" data-pin=\"277,134\" data-offset=\"0\" shape=\"poly\" coords=\"285,137,284,138,284,136,282,136,283,136,281,133,279,134,278,135,277,133,276,133,275,131,277,130,277,129,281,129,282,130,285,129,286,131,286,132,286,132,287,133,286,134,287,136,286,136,286,137,285,137\" />\n";
  $output .= "            <area data-timezone=\"Africa/Dakar\" data-country=\"SN\" data-pin=\"271,126\" data-offset=\"0\" shape=\"poly\" coords=\"272,128,275,127,277,128,275,127,272,127,271,125,273,122,276,122,280,126,281,129,275,129,272,129,273,129,272,129,272,128\" />\n";
  $output .= "            <area data-timezone=\"Africa/Dar_es_Salaam\" data-country=\"TZ\" data-pin=\"365,161\" data-offset=\"3\" shape=\"poly\" coords=\"367,167,367,167,367,168,362,170,358,169,357,166,352,164,349,161,349,158,351,155,351,155,351,153,351,152,357,152,363,155,363,156,365,158,365,161,366,162,365,164,367,167\" />\n";
  $output .= "            <area data-timezone=\"Asia/Yekaterinburg\" data-country=\"RU\" data-pin=\"401,55\" data-offset=\"6\" shape=\"poly\" coords=\"400,63,403,64,402,65,399,66,397,65,395,65,393,66,391,65,391,66,389,64,385,64,387,62,387,60,389,60,389,58,389,58,390,57,389,57,391,56,390,55,391,55,389,52,390,52,389,51,390,50,387,50,386,49,392,49,394,47,399,47,399,42,410,38,410,37,410,37,409,37,409,36,408,35,410,35,411,35,409,35,414,36,415,35,414,34,411,34,412,33,412,32,411,32,414,30,416,28,421,29,420,31,421,32,421,33,421,35,423,36,420,39,415,39,415,39,420,40,425,37,424,35,427,35,429,36,428,37,429,38,433,38,429,37,430,36,429,35,423,35,423,34,424,32,422,31,425,30,425,29,426,30,425,31,426,31,431,32,427,30,430,30,429,30,430,29,434,30,432,31,434,32,435,33,432,34,437,35,438,36,436,37,437,37,437,38,439,39,439,40,441,41,440,42,443,42,443,43,442,44,443,45,441,46,443,47,443,48,437,49,429,49,428,51,424,53,419,53,418,52,417,54,419,55,418,56,417,58,415,58,409,59,402,60,402,61,404,62,402,62,401,62,402,63,400,63\" />\n";
  $output .= "            <area data-timezone=\"Africa/Djibouti\" data-country=\"DJ\" data-pin=\"372,131\" data-offset=\"3\" shape=\"poly\" coords=\"372,130,372,130,372,130\" />\n";
  $output .= "            <area data-timezone=\"Africa/Douala\" data-country=\"CM\" data-pin=\"316,143\" data-offset=\"1\" shape=\"poly\" coords=\"324,140,325,142,327,145,327,147,324,146,317,146,316,143,315,143,314,142,315,140,316,139,318,138,319,139,320,138,323,132,324,131,323,128,325,129,325,132,326,133,323,134,326,137,324,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Freetown\" data-country=\"SL\" data-pin=\"278,136\" data-offset=\"0\" shape=\"poly\" coords=\"281,138,281,138,278,136,278,135,279,134,281,133,282,134,282,136,283,136,281,138\" />\n";
  $output .= "            <area data-timezone=\"Africa/Gaborone\" data-country=\"BW\" data-pin=\"343,191\" data-offset=\"2\" shape=\"poly\" coords=\"345,190,342,193,338,192,337,194,334,195,335,193,333,191,333,187,335,187,335,181,339,180,339,181,342,180,344,183,346,184,347,186,349,187,345,190\" />\n";
  $output .= "            <area data-timezone=\"Africa/Harare\" data-country=\"ZW\" data-pin=\"352,180\" data-offset=\"2\" shape=\"poly\" coords=\"352,187,347,186,346,184,344,183,342,180,345,180,349,176,355,178,355,182,355,183,354,186,352,187\" />\n";
  $output .= "            <area data-timezone=\"Africa/El_Aaiun\" data-country=\"EH\" data-pin=\"278,105\" data-offset=\"0\" shape=\"poly\" coords=\"280,107,280,111,278,112,278,114,272,114,272,115,272,114,274,110,273,111,275,109,276,106,277,105,278,104,286,104,286,107,280,107\" />\n";
  $output .= "            <area data-timezone=\"Africa/Johannesburg\" data-country=\"ZA\" data-pin=\"347,194\" data-offset=\"2\" shape=\"poly\" coords=\"339,207,333,208,331,207,331,207,330,205,330,204,330,203,327,198,328,197,329,198,330,198,333,197,333,191,335,193,334,195,336,195,338,192,342,193,345,189,348,187,352,187,353,191,353,193,352,193,351,195,353,196,353,196,353,195,355,195,354,198,347,205,343,207,339,207\" />\n";
  $output .= "            <area data-timezone=\"Africa/Juba\" data-country=\"SS\" data-pin=\"353,142\" data-offset=\"3\" shape=\"poly\" coords=\"358,140,359,141,360,141,360,142,357,142,356,144,351,144,349,142,347,143,346,142,344,139,340,135,342,133,343,133,344,134,348,134,350,133,352,134,354,132,353,130,355,130,355,132,357,133,357,136,355,136,355,137,357,137,358,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Kampala\" data-country=\"UG\" data-pin=\"354,149\" data-offset=\"3\" shape=\"poly\" coords=\"352,152,349,152,350,149,352,146,351,146,351,144,356,144,357,143,358,147,357,150,357,152,352,152\" />\n";
  $output .= "            <area data-timezone=\"Africa/Khartoum\" data-country=\"SD\" data-pin=\"354,124\" data-offset=\"3\" shape=\"poly\" coords=\"360,129,358,132,357,132,357,134,355,132,355,130,355,130,353,130,354,132,352,134,350,133,348,134,344,134,343,133,342,133,341,135,339,135,339,134,338,132,337,129,336,129,338,124,340,124,340,117,342,117,342,113,352,113,357,114,359,111,361,113,362,115,362,119,364,120,362,122,361,127,360,129,360,129\" />\n";
  $output .= "            <area data-timezone=\"Africa/Kinshasa\" data-country=\"CD\" data-pin=\"326,157\" data-offset=\"1\" shape=\"poly\" coords=\"325,158,327,157,327,154,330,151,331,143,332,142,333,141,334,143,339,144,338,144,339,146,338,146,337,147,339,150,338,151,339,151,339,151,341,153,338,153,337,153,337,154,335,154,334,157,333,157,333,162,333,162,332,163,329,164,328,160,320,160,322,158,322,158,324,157,324,158,325,158\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lagos\" data-country=\"NG\" data-pin=\"306,139\" data-offset=\"1\" shape=\"poly\" coords=\"313,143,310,143,308,139,305,139,305,135,306,132,306,131,307,128,309,127,312,128,313,128,316,129,318,128,321,128,323,127,324,130,324,131,323,132,320,138,319,139,317,138,315,140,314,142,313,143\" />\n";
  $output .= "            <area data-timezone=\"Africa/Libreville\" data-country=\"GA\" data-pin=\"316,149\" data-offset=\"1\" shape=\"poly\" coords=\"323,150,324,151,324,154,321,153,321,154,319,154,320,156,319,157,316,154,314,151,315,151,316,149,317,150,316,149,316,148,319,148,319,146,322,146,322,148,324,148,324,149,323,150\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lome\" data-country=\"TG\" data-pin=\"302,140\" data-offset=\"0\" shape=\"poly\" coords=\"302,140,301,139,301,136,300,131,302,132,301,133,302,133,303,140,302,140\" />\n";
  $output .= "            <area data-timezone=\"Africa/Kigali\" data-country=\"RW\" data-pin=\"350,153\" data-offset=\"2\" shape=\"poly\" coords=\"350,154,350,155,348,154,349,153,351,152,351,154,350,154\" />\n";
  $output .= "            <area data-timezone=\"Africa/Luanda\" data-country=\"AO\" data-pin=\"322,165\" data-offset=\"1\" shape=\"poly\" coords=\"337,168,337,169,340,168,340,172,337,172,337,177,339,179,335,180,331,179,323,179,322,178,320,179,321,172,323,170,323,168,322,165,322,164,321,160,328,160,329,164,332,163,333,162,336,162,336,166,337,168\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lubumbashi\" data-country=\"CD\" data-pin=\"346,169\" data-offset=\"2\" shape=\"poly\" coords=\"349,158,349,161,351,164,348,164,347,166,348,166,347,169,348,171,350,170,350,172,348,172,345,169,345,170,343,170,342,169,341,169,340,168,337,169,336,162,333,162,333,160,334,159,333,157,334,157,335,154,337,154,337,153,338,153,341,153,339,151,339,151,338,151,339,150,337,147,338,146,339,146,338,144,339,144,337,143,338,142,342,142,343,141,345,141,347,143,349,142,352,144,351,146,352,146,350,149,349,152,348,154,349,158\" />\n";
  $output .= "            <area data-timezone=\"Africa/Lusaka\" data-country=\"ZM\" data-pin=\"347,176\" data-offset=\"2\" shape=\"poly\" coords=\"348,177,348,178,344,180,341,179,339,179,337,177,337,172,340,172,340,168,341,169,342,169,343,170,345,170,345,169,348,172,350,172,350,170,348,171,347,170,348,166,347,166,348,164,351,164,355,166,356,168,355,168,356,171,354,173,355,173,350,175,351,176,348,177\" />\n";
  $output .= "            <area data-timezone=\"Africa/Malabo\" data-country=\"GQ\" data-pin=\"315,144\" data-offset=\"1\" shape=\"poly\" coords=\"319,147,319,148,316,148,316,146,319,146,319,147\" />\n";
  $output .= "            <area data-timezone=\"Africa/Maputo\" data-country=\"MZ\" data-pin=\"354,193\" data-offset=\"2\" shape=\"poly\" coords=\"355,192,354,193,354,194,355,193,355,195,354,195,353,191,352,187,354,186,355,183,355,182,355,178,351,177,350,175,355,173,356,174,357,174,358,175,357,177,359,179,360,175,358,172,358,169,362,170,368,167,368,175,366,177,363,179,361,181,358,183,358,184,359,187,359,190,355,192\" />\n";
  $output .= "            <area data-timezone=\"Africa/Mbabane\" data-country=\"SZ\" data-pin=\"352,194\" data-offset=\"2\" shape=\"poly\" coords=\"353,193,354,195,353,196,351,194,352,193,353,193\" />\n";
  $output .= "            <area data-timezone=\"Africa/Mogadishu\" data-country=\"SO\" data-pin=\"376,147\" data-offset=\"3\" shape=\"poly\" coords=\"371,150,369,153,368,151,368,145,370,143,375,142,380,137,373,135,371,132,372,131,374,133,385,130,385,133,386,133,385,133,385,134,380,143,371,150\" />\n";
  $output .= "            <area data-timezone=\"Africa/Monrovia\" data-country=\"LR\" data-pin=\"282,140\" data-offset=\"0\" shape=\"poly\" coords=\"287,142,287,143,286,142,281,139,283,136,284,136,284,138,285,138,286,137,286,138,286,139,288,140,287,142\" />\n";
  $output .= "            <area data-timezone=\"Africa/Nairobi\" data-country=\"KE\" data-pin=\"361,152\" data-offset=\"3\" shape=\"poly\" coords=\"369,153,367,155,365,158,363,156,363,155,357,152,357,150,358,147,357,143,357,142,360,142,366,144,368,143,370,143,368,145,368,151,369,153\" />\n";
  $output .= "            <area data-timezone=\"Africa/Maseru\" data-country=\"LS\" data-pin=\"346,199\" data-offset=\"2\" shape=\"poly\" coords=\"347,200,346,201,345,199,348,198,349,199,347,200\" />\n";
  $output .= "            <area data-timezone=\"Africa/Ndjamena\" data-country=\"TD\" data-pin=\"325,130\" data-offset=\"1\" shape=\"poly\" coords=\"333,135,331,135,331,137,326,138,323,134,324,133,326,133,325,132,325,129,323,128,322,126,326,122,327,116,325,114,325,112,327,111,340,117,340,124,338,124,336,129,337,129,338,131,336,132,335,134,333,135\" />\n";
  $output .= "            <area data-timezone=\"Africa/Niamey\" data-country=\"NE\" data-pin=\"304,127\" data-offset=\"1\" shape=\"poly\" coords=\"308,127,306,129,306,130,305,129,304,130,303,129,302,128,302,128,301,127,300,125,306,124,307,122,307,118,310,118,320,111,324,112,325,112,325,114,327,116,326,122,322,126,323,127,321,128,318,128,316,129,313,128,312,128,309,127,308,127\" />\n";
  $output .= "            <area data-timezone=\"Africa/Nouakchott\" data-country=\"MR\" data-pin=\"273,120\" data-offset=\"0\" shape=\"poly\" coords=\"280,124,280,125,276,122,273,122,272,123,273,120,272,118,273,116,272,115,272,115,278,114,278,112,280,111,280,107,286,107,286,105,292,108,289,108,291,124,284,124,282,125,280,124\" />\n";
  $output .= "            <area data-timezone=\"Africa/Ouagadougou\" data-country=\"BF\" data-pin=\"297,129\" data-offset=\"0\" shape=\"poly\" coords=\"299,132,295,132,295,134,295,133,292,134,291,133,291,130,292,130,293,128,294,128,296,126,300,125,300,125,301,127,302,128,302,128,303,129,304,130,302,132,299,132\" />\n";
  $output .= "            <area data-timezone=\"Africa/Porto-Novo\" data-country=\"BJ\" data-pin=\"304,139\" data-offset=\"1\" shape=\"poly\" coords=\"305,137,305,139,303,140,303,135,301,133,302,131,303,131,305,129,306,130,306,132,305,135,305,137\" />\n";
  $output .= "            <area data-timezone=\"Africa/Tunis\" data-country=\"TN\" data-pin=\"317,89\" data-offset=\"1\" shape=\"poly\" coords=\"319,96,317,97,317,99,316,100,315,97,313,95,313,93,314,91,314,89,315,88,317,88,317,89,318,88,317,90,319,91,317,93,319,95,319,96\" />\n";
  $output .= "            <area data-timezone=\"Africa/Sao_Tome\" data-country=\"ST\" data-pin=\"311,149\" data-offset=\"0\" shape=\"poly\" coords=\"312,147,312,147,312,147\" />\n";
  $output .= "            <area data-timezone=\"Africa/Tripoli\" data-country=\"LY\" data-pin=\"322,95\" data-offset=\"2\" shape=\"poly\" coords=\"342,105,342,117,340,117,340,117,327,111,324,112,317,109,316,106,317,106,317,104,316,100,317,99,317,97,319,96,319,95,325,96,326,98,332,100,333,99,334,96,337,95,339,96,342,97,341,100,342,105\" />\n";
  $output .= "            <area data-timezone=\"Africa/Windhoek\" data-country=\"NA\" data-pin=\"329,188\" data-offset=\"2\" shape=\"poly\" coords=\"333,196,333,197,332,198,329,198,328,197,327,198,326,197,325,194,324,188,320,181,320,179,322,178,323,179,331,179,335,180,340,179,342,180,339,181,339,180,335,181,335,187,333,187,333,196\" />\n";
  $output .= "            <area data-timezone=\"America/Adak\" data-country=\"US\" data-pin=\"6,64\" data-offset=\"-10\" shape=\"poly\" coords=\"8,63,8,63,10,63,8,63\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Salta\" data-country=\"AR\" data-pin=\"191,191\" data-offset=\"-3\" shape=\"poly\" coords=\"194,216,194,218,195,218,192,218,192,220,180,220,180,216,182,214,181,212,182,210,184,212,186,213,186,210,192,210,192,208,194,208,194,216\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Salta\" data-country=\"AR\" data-pin=\"191,191\" data-offset=\"-3\" shape=\"poly\" coords=\"191,187,193,188,193,187,195,187,196,187,196,191,194,193,189,194,189,193,189,192,186,192,186,191,188,190,189,190,189,189,191,191,193,191,193,189,192,189,191,187\" />\n";
  $output .= "            <area data-timezone=\"America/Anchorage\" data-country=\"US\" data-pin=\"50,48\" data-offset=\"-9\" shape=\"poly\" coords=\"51,50,48,51,47,51,48,50,47,50,48,49,52,49,50,48,51,47,47,48,43,51,45,52,43,53,36,56,36,57,30,58,30,57,33,57,33,56,37,54,37,53,39,52,36,52,36,52,35,53,33,52,30,52,30,44,32,44,31,43,32,42,30,42,30,40,32,40,30,39,30,39,31,39,30,38,30,33,39,31,41,31,40,32,42,31,46,32,47,32,46,32,47,33,61,33,65,34,65,50,64,50,60,50,55,49,56,48,53,48,54,48,52,49,53,49,53,50,53,50,51,50\" />\n";
  $output .= "            <area data-timezone=\"America/Anguilla\" data-country=\"AI\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/Antigua\" data-country=\"AG\" data-pin=\"197,122\" data-offset=\"-4\" shape=\"poly\" coords=\"197,121,197,121,197,121\" />\n";
  $output .= "            <area data-timezone=\"America/Araguaina\" data-country=\"BR\" data-pin=\"220,162\" data-offset=\"-3\" shape=\"poly\" coords=\"222,163,223,163,222,165,224,167,222,169,223,169,223,171,220,172,219,172,218,171,218,172,216,171,216,171,216,171,216,168,218,164,218,162,219,161,220,159,219,159,221,159,220,162,222,163\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Buenos_Aires\" data-country=\"AR\" data-pin=\"203,208\" data-offset=\"-3\" shape=\"poly\" coords=\"200,206,203,207,203,208,205,209,204,210,206,212,203,214,198,215,196,215,197,216,196,215,196,217,196,218,195,218,194,218,194,207,197,207,198,206,200,206\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Catamarca\" data-country=\"AR\" data-pin=\"190,197\" data-offset=\"-3\" shape=\"poly\" coords=\"191,225,188,225,187,227,181,227,181,225,180,225,181,224,180,224,181,223,180,221,191,220,193,221,194,220,194,221,192,221,193,222,191,223,191,225\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Catamarca\" data-country=\"AR\" data-pin=\"190,197\" data-offset=\"-3\" shape=\"poly\" coords=\"192,200,189,197,185,196,186,195,186,192,189,192,189,193,190,195,190,196,191,197,191,197,192,200\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Cordoba\" data-country=\"AR\" data-pin=\"193,202\" data-offset=\"-3\" shape=\"poly\" coords=\"195,207,194,207,194,208,192,208,192,204,190,203,190,202,192,200,191,197,193,193,194,193,196,191,196,187,198,190,204,192,202,196,207,196,209,194,209,193,210,193,210,195,207,197,204,201,203,207,200,205,197,207,195,207\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Jujuy\" data-country=\"AR\" data-pin=\"191,190\" data-offset=\"-3\" shape=\"poly\" coords=\"188,188,190,186,191,187,191,188,192,189,193,189,193,190,192,191,191,191,189,189,189,190,189,190,188,188\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/La_Rioja\" data-country=\"AR\" data-pin=\"189,199\" data-offset=\"-3\" shape=\"poly\" coords=\"187,200,185,199,185,198,184,197,185,196,189,197,191,200,190,203,189,203,187,200\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Mendoza\" data-country=\"AR\" data-pin=\"185,205\" data-offset=\"-3\" shape=\"poly\" coords=\"183,204,187,203,188,205,189,210,186,210,186,213,184,212,183,211,182,209,184,206,183,204\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Rio_Gallegos\" data-country=\"AR\" data-pin=\"185,236\" data-offset=\"-3\" shape=\"poly\" coords=\"181,227,187,227,189,228,190,229,190,230,185,234,185,236,186,237,180,237,179,236,180,234,178,235,177,234,178,232,180,231,179,230,180,229,181,227\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/San_Juan\" data-country=\"AR\" data-pin=\"186,203\" data-offset=\"-3\" shape=\"poly\" coords=\"183,200,184,197,185,198,185,199,187,200,189,203,188,203,188,204,185,203,183,204,182,202,183,200\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/San_Luis\" data-country=\"AR\" data-pin=\"189,206\" data-offset=\"-3\" shape=\"poly\" coords=\"190,203,192,204,192,210,189,210,188,203,190,203\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Tucuman\" data-country=\"AR\" data-pin=\"191,195\" data-offset=\"-3\" shape=\"poly\" coords=\"190,194,193,194,192,196,191,197,190,196,190,195,190,194\" />\n";
  $output .= "            <area data-timezone=\"America/Aruba\" data-country=\"AW\" data-pin=\"183,129\" data-offset=\"-4\" shape=\"poly\" coords=\"183,129,184,129,183,129\" />\n";
  $output .= "            <area data-timezone=\"America/Argentina/Ushuaia\" data-country=\"AR\" data-pin=\"186,241\" data-offset=\"-3\" shape=\"poly\" coords=\"186,241,186,238,186,239,186,239,188,240,191,241,189,242,186,241\" />\n";
  $output .= "            <area data-timezone=\"America/Asuncion\" data-country=\"PY\" data-pin=\"204,192\" data-offset=\"-3\" shape=\"poly\" coords=\"209,194,207,196,202,195,204,192,198,190,196,187,197,183,200,182,203,183,203,187,207,187,208,190,210,190,209,194\" />\n";
  $output .= "            <area data-timezone=\"America/Bahia_Banderas\" data-country=\"MX\" data-pin=\"125,115\" data-offset=\"-6\" shape=\"poly\" coords=\"125,115,124,115,125,115\" />\n";
  $output .= "            <area data-timezone=\"America/Atikokan\" data-country=\"CA\" data-pin=\"147,69\" data-offset=\"-5\" shape=\"poly\" coords=\"150,70,146,69,147,68,148,68,148,69,150,70\" />\n";
  $output .= "            <area data-timezone=\"America/Bahia\" data-country=\"BR\" data-pin=\"236,172\" data-offset=\"-2\" shape=\"poly\" coords=\"224,175,223,175,223,169,222,169,223,168,224,167,225,168,227,167,227,166,229,166,231,165,232,166,234,164,236,165,237,167,236,168,238,169,235,172,235,176,234,181,232,179,234,177,233,176,231,176,230,175,227,175,226,174,224,175\" />\n";
  $output .= "            <area data-timezone=\"America/Barbados\" data-country=\"BB\" data-pin=\"201,128\" data-offset=\"-4\" shape=\"poly\" coords=\"201,128,201,128,201,128\" />\n";
  $output .= "            <area data-timezone=\"America/Belem\" data-country=\"BR\" data-pin=\"219,152\" data-offset=\"-3\" shape=\"poly\" coords=\"214,151,215,150,215,151,216,150,219,150,219,152,216,153,218,153,218,154,219,152,220,151,223,152,222,156,219,159,220,160,218,162,218,164,216,166,212,166,213,165,212,163,213,161,212,158,212,156,214,155,213,153,214,152,212,151,211,148,209,147,209,146,212,146,214,143,216,146,217,148,214,151\" />\n";
  $output .= "            <area data-timezone=\"America/Belize\" data-country=\"BZ\" data-pin=\"153,121\" data-offset=\"-6\" shape=\"poly\" coords=\"154,121,153,121,154,121\" />\n";
  $output .= "            <area data-timezone=\"America/Blanc-Sablon\" data-country=\"CA\" data-pin=\"205,64\" data-offset=\"-4\" shape=\"poly\" coords=\"202,64,201,66,202,64\" />\n";
  $output .= "            <area data-timezone=\"America/Boa_Vista\" data-country=\"BR\" data-pin=\"199,145\" data-offset=\"-4\" shape=\"poly\" coords=\"200,142,200,142,201,143,200,146,200,147,202,148,202,150,200,150,199,151,198,151,197,152,196,151,196,147,193,146,192,143,195,144,196,143,198,142,199,141,200,142\" />\n";
  $output .= "            <area data-timezone=\"America/Bogota\" data-country=\"CO\" data-pin=\"177,142\" data-offset=\"-5\" shape=\"poly\" coords=\"184,152,183,157,182,156,183,155,183,154,180,154,178,154,175,150,171,149,168,147,172,143,171,143,171,140,170,138,171,137,171,136,172,137,172,136,174,134,175,132,178,131,181,129,181,130,179,132,178,135,179,136,179,138,180,138,183,138,184,140,188,140,187,142,188,144,187,145,188,146,189,148,188,146,184,147,184,148,185,149,183,149,184,152\" />\n";
  $output .= "            <area data-timezone=\"America/Boise\" data-country=\"US\" data-pin=\"106,77\" data-offset=\"-7\" shape=\"poly\" coords=\"115,79,115,80,105,80,105,79,103,79,103,76,105,76,106,74,106,73,106,74,110,74,112,76,115,76,115,79\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"119,22,120,23,119,23,121,23,120,24,123,23,124,24,123,25,117,25,117,24,119,24,117,24,118,23,117,23,119,22\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"130,43,116,42,114,41,111,41,97,36,97,34,107,35,110,36,107,37,108,37,117,37,120,38,119,38,121,39,120,39,122,39,121,38,122,38,120,37,124,36,119,36,119,36,123,35,126,37,128,36,131,37,136,37,136,36,138,36,139,36,139,37,140,36,139,38,141,38,141,37,144,36,144,36,144,35,142,35,144,34,139,33,140,32,139,32,139,31,142,30,141,30,144,30,145,31,145,32,147,33,146,33,146,33,147,33,145,34,149,34,148,34,149,35,149,36,150,36,152,35,152,38,130,38,130,43\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"138,29,139,29,139,30,139,30,136,30,137,31,135,31,129,29,133,29,133,28,138,29\" />\n";
  $output .= "            <area data-timezone=\"America/Cambridge_Bay\" data-country=\"CA\" data-pin=\"125,35\" data-offset=\"-7\" shape=\"poly\" coords=\"120,28,124,28,126,31,126,32,132,33,132,34,127,34,128,34,128,35,130,34,130,35,128,35,125,35,125,35,122,34,118,35,111,36,111,35,111,35,106,34,105,33,117,33,117,28,119,29,120,31,121,30,120,28,120,28\" />\n";
  $output .= "            <area data-timezone=\"America/Campo_Grande\" data-country=\"BR\" data-pin=\"209,184\" data-offset=\"-3\" shape=\"poly\" coords=\"212,180,212,181,215,182,215,184,210,190,208,190,207,187,203,187,204,180,206,179,208,179,211,179,210,180,212,180\" />\n";
  $output .= "            <area data-timezone=\"America/Cancun\" data-country=\"MX\" data-pin=\"155,115\" data-offset=\"-6\" shape=\"poly\" coords=\"154,114,155,115,154,117,154,117,154,120,153,119,152,120,151,120,151,117,154,116,154,114\" />\n";
  $output .= "            <area data-timezone=\"America/Caracas\" data-country=\"VE\" data-pin=\"188,133\" data-offset=\"-4.5\" shape=\"poly\" coords=\"196,134,198,134,198,136,200,136,199,137,200,138,198,139,198,140,199,142,195,143,195,144,192,143,193,146,194,146,191,149,191,148,189,149,189,148,188,146,187,145,188,144,187,142,188,140,184,140,183,138,180,138,179,135,178,135,179,132,181,130,180,131,181,132,180,134,180,135,181,135,182,134,181,132,183,131,183,130,184,131,186,131,186,133,190,132,192,133,194,133,193,132,197,132,195,132,196,134\" />\n";
  $output .= "            <area data-timezone=\"America/Cayenne\" data-country=\"GF\" data-pin=\"213,142\" data-offset=\"-3\" shape=\"poly\" coords=\"211,141,214,143,212,146,209,146,210,144,209,142,210,140,211,141\" />\n";
  $output .= "            <area data-timezone=\"America/Cayman\" data-country=\"KY\" data-pin=\"164,118\" data-offset=\"-5\" shape=\"poly\" coords=\"167,117,167,117,167,117\" />\n";
  $output .= "            <area data-timezone=\"America/Chicago\" data-country=\"US\" data-pin=\"154,80\" data-offset=\"-6\" shape=\"poly\" coords=\"154,86,159,88,159,90,157,92,159,96,158,100,156,99,156,99,155,99,155,99,153,100,153,99,153,99,149,100,151,100,150,101,152,101,151,102,150,101,150,102,144,100,142,101,142,100,141,102,139,103,140,102,139,102,139,103,138,103,138,104,137,104,138,105,138,107,135,106,134,104,131,100,129,100,128,102,126,101,125,99,125,97,128,97,128,88,130,88,130,87,131,87,131,84,130,84,130,83,131,83,131,80,132,80,131,77,133,76,132,75,133,75,133,73,131,73,132,72,132,71,127,71,127,68,141,68,141,68,142,69,151,70,147,72,149,72,148,72,153,73,154,75,153,76,154,75,154,80,154,81,156,81,154,82,154,86\" />\n";
  $output .= "            <area data-timezone=\"America/Chihuahua\" data-country=\"MX\" data-pin=\"123,102\" data-offset=\"-7\" shape=\"poly\" coords=\"128,102,127,104,127,106,126,106,123,105,122,107,118,103,119,103,119,98,120,98,120,97,122,97,125,99,125,101,128,102\" />\n";
  $output .= "            <area data-timezone=\"America/Coral_Harbour\" data-country=\"CA\" data-pin=\"160,42\" data-offset=\"-5\" shape=\"poly\" coords=\"158,40,158,41,159,41,164,42,164,43,163,43,166,44,165,44,162,43,158,45,157,44,155,44,156,43,157,41,158,40\" />\n";
  $output .= "            <area data-timezone=\"America/Costa_Rica\" data-country=\"CR\" data-pin=\"160,133\" data-offset=\"-6\" shape=\"poly\" coords=\"158,134,157,133,157,131,161,132,162,134,162,134,162,137,158,133,158,134\" />\n";
  $output .= "            <area data-timezone=\"America/Creston\" data-country=\"CA\" data-pin=\"106,68\" data-offset=\"-7\" shape=\"poly\" coords=\"107,68,105,68,105,67,107,68\" />\n";
  $output .= "            <area data-timezone=\"America/Cuiaba\" data-country=\"BR\" data-pin=\"207,176\" data-offset=\"-3\" shape=\"poly\" coords=\"214,175,212,178,212,180,210,180,211,179,208,179,206,179,204,180,203,179,203,177,200,177,199,173,200,171,200,169,197,168,197,165,202,165,203,162,204,165,205,166,216,166,215,169,216,171,214,175\" />\n";
  $output .= "            <area data-timezone=\"America/Curacao\" data-country=\"CW\" data-pin=\"185,130\" data-offset=\"-4\" shape=\"poly\" coords=\"185,130,185,129,185,130\" />\n";
  $output .= "            <area data-timezone=\"America/Danmarkshavn\" data-country=\"GL\" data-pin=\"269,22\" data-offset=\"0\" shape=\"poly\" coords=\"268,18,267,18,269,18,265,19,265,19,263,21,265,20,268,20,268,21,265,21,269,21,270,22,262,22,267,23,264,23,268,24,262,23,262,17,268,18\" />\n";
  $output .= "            <area data-timezone=\"America/Dawson\" data-country=\"CA\" data-pin=\"68,43\" data-offset=\"-8\" shape=\"poly\" coords=\"68,43,68,43,68,43\" />\n";
  $output .= "            <area data-timezone=\"America/Dawson_Creek\" data-country=\"CA\" data-pin=\"100,50\" data-offset=\"-7\" shape=\"poly\" coords=\"100,55,100,60,95,58,94,55,100,55\" />\n";
  $output .= "            <area data-timezone=\"America/Denver\" data-country=\"US\" data-pin=\"125,84\" data-offset=\"-7\" shape=\"poly\" coords=\"112,76,110,74,109,74,109,72,107,71,107,68,127,68,127,71,130,71,130,72,132,73,131,73,133,74,133,75,132,75,133,76,131,77,132,80,131,80,131,83,130,83,130,84,131,84,131,87,130,87,130,88,128,88,128,97,125,97,125,99,122,97,118,98,118,91,115,91,114,89,114,88,110,88,110,80,115,80,115,76,112,76\" />\n";
  $output .= "            <area data-timezone=\"America/Detroit\" data-country=\"US\" data-pin=\"162,79\" data-offset=\"-5\" shape=\"poly\" coords=\"159,81,155,80,156,79,156,77,156,76,157,75,158,75,159,74,161,75,161,75,161,76,160,78,162,77,163,78,161,80,159,81\" />\n";
  $output .= "            <area data-timezone=\"America/Detroit\" data-country=\"US\" data-pin=\"162,79\" data-offset=\"-5\" shape=\"poly\" coords=\"150,72,152,71,153,72,156,73,158,72,161,73,157,73,156,74,155,73,154,75,155,74,154,73,151,73,150,72\" />\n";
  $output .= "            <area data-timezone=\"America/Dominica\" data-country=\"DM\" data-pin=\"198,125\" data-offset=\"-4\" shape=\"poly\" coords=\"198,124,198,125,198,124\" />\n";
  $output .= "            <area data-timezone=\"America/Edmonton\" data-country=\"CA\" data-pin=\"111,61\" data-offset=\"-7\" shape=\"poly\" coords=\"110,68,106,68,106,66,102,63,103,63,103,62,100,61,100,50,117,50,117,61,119,62,117,62,117,68,110,68\" />\n";
  $output .= "            <area data-timezone=\"America/Eirunepe\" data-country=\"BR\" data-pin=\"184,161\" data-offset=\"-4\" shape=\"poly\" coords=\"187,166,177,162,178,161,179,159,180,158,183,157,187,166\" />\n";
  $output .= "            <area data-timezone=\"America/El_Salvador\" data-country=\"SV\" data-pin=\"151,127\" data-offset=\"-6\" shape=\"poly\" coords=\"151,127,150,127,151,126,154,127,153,128,151,127\" />\n";
  $output .= "            <area data-timezone=\"America/Fortaleza\" data-country=\"BR\" data-pin=\"236,156\" data-offset=\"-3\" shape=\"poly\" coords=\"242,161,242,163,241,162,238,164,238,163,238,162,236,163,232,162,232,164,230,165,227,166,227,167,225,168,223,167,222,165,223,163,220,162,221,159,219,159,222,156,223,152,224,152,224,153,225,152,226,153,225,156,226,154,226,155,228,154,230,155,233,155,238,158,241,159,242,161\" />\n";
  $output .= "            <area data-timezone=\"America/Glace_Bay\" data-country=\"CA\" data-pin=\"200,73\" data-offset=\"-4\" shape=\"poly\" coords=\"200,73,200,74,199,73,200,73\" />\n";
  $output .= "            <area data-timezone=\"America/Godthab\" data-country=\"GL\" data-pin=\"214,43\" data-offset=\"-3\" shape=\"poly\" coords=\"225,50,225,50,226,49,225,50,226,49,225,49,225,49,223,49,224,48,223,49,225,48,220,49,221,48,219,48,220,48,218,47,219,47,218,47,219,47,218,47,219,47,217,47,218,46,216,46,217,45,216,45,216,45,216,45,217,45,215,45,216,44,215,44,216,44,214,44,215,43,214,43,216,43,214,43,216,43,215,42,216,42,217,43,215,41,216,42,213,43,213,42,215,42,213,42,213,41,212,41,216,40,212,41,212,40,211,41,214,40,211,40,216,39,211,40,211,40,211,39,213,39,211,39,213,39,210,38,216,38,210,38,217,37,213,37,214,37,210,37,211,37,213,37,211,36,216,37,214,36,215,36,211,36,215,36,216,35,214,35,215,35,216,35,215,35,216,35,216,34,215,35,216,34,215,34,216,33,209,32,216,33,214,32,215,32,213,32,215,32,213,31,214,31,212,31,214,31,212,31,214,30,211,30,210,30,210,31,208,31,207,31,209,30,207,30,208,29,207,29,209,29,207,28,208,28,207,27,207,27,206,27,206,26,204,26,206,26,202,24,203,24,203,24,194,23,189,18,192,17,192,17,194,16,188,16,194,15,195,15,194,15,198,15,198,14,199,14,198,14,199,14,206,14,201,13,202,13,209,13,211,13,211,14,212,13,217,14,215,13,218,13,215,12,216,12,226,14,226,14,225,13,226,13,226,13,229,13,224,12,234,12,224,11,236,12,235,12,238,11,235,11,242,11,257,11,241,12,258,11,259,12,257,12,264,12,245,14,259,13,255,14,256,14,265,13,264,14,260,16,268,14,267,14,274,14,281,14,274,16,265,16,274,16,266,16,267,17,271,17,270,17,262,17,262,23,268,24,267,25,263,24,264,24,262,24,266,25,263,25,268,26,263,26,263,26,262,27,264,27,264,27,266,27,266,28,263,28,260,27,263,27,257,27,259,27,254,28,256,28,254,28,254,28,258,28,254,29,259,29,256,29,258,29,257,30,259,29,259,31,252,30,254,30,253,31,258,31,253,31,254,32,251,33,256,33,252,33,254,33,258,33,263,33,256,36,250,37,247,37,246,36,247,37,245,37,242,39,238,40,238,40,237,40,238,39,237,39,237,40,236,40,236,41,234,40,233,41,234,41,233,42,231,41,233,43,231,43,232,43,232,44,231,44,232,44,230,44,231,45,230,45,230,45,228,45,230,46,228,46,230,47,229,47,230,47,228,48,229,48,227,48,229,48,227,49,229,49,226,49,228,50,227,49,225,50\" />\n";
  $output .= "            <area data-timezone=\"America/Goose_Bay\" data-country=\"CA\" data-pin=\"199,61\" data-offset=\"-4\" shape=\"poly\" coords=\"205,63,194,63,193,63,194,62,194,62,193,62,192,64,189,63,189,62,188,62,189,61,187,60,188,59,188,58,189,59,189,58,190,59,193,59,194,58,194,58,194,57,194,57,193,56,194,54,193,53,194,52,192,52,193,52,193,51,192,51,192,50,194,51,193,52,195,52,194,53,196,53,194,53,197,54,196,54,198,55,196,55,197,56,196,56,199,57,199,57,199,58,200,58,200,58,201,58,200,59,201,58,201,59,204,59,201,60,203,60,199,61,204,60,205,60,204,61,205,63\" />\n";
  $output .= "            <area data-timezone=\"America/Grand_Turk\" data-country=\"TC\" data-pin=\"181,114\" data-offset=\"-5\" shape=\"poly\" coords=\"181,114,181,114,181,114\" />\n";
  $output .= "            <area data-timezone=\"America/Grenada\" data-country=\"GD\" data-pin=\"197,130\" data-offset=\"-4\" shape=\"poly\" coords=\"198,129,198,129,198,129\" />\n";
  $output .= "            <area data-timezone=\"America/Guadeloupe\" data-country=\"GP\" data-pin=\"197,123\" data-offset=\"-4\" shape=\"poly\" coords=\"197,123,198,123,197,123\" />\n";
  $output .= "            <area data-timezone=\"America/Guatemala\" data-country=\"GT\" data-pin=\"149,126\" data-offset=\"-6\" shape=\"poly\" coords=\"150,127,147,126,146,125,147,123,149,123,148,121,148,121,148,120,151,120,151,124,153,124,150,127\" />\n";
  $output .= "            <area data-timezone=\"America/Guayaquil\" data-country=\"EC\" data-pin=\"167,154\" data-offset=\"-5\" shape=\"poly\" coords=\"169,156,168,158,167,157,166,157,167,154,166,155,165,154,167,149,169,148,171,149,173,149,175,150,174,150,175,152,169,156\" />\n";
  $output .= "            <area data-timezone=\"America/Guyana\" data-country=\"GY\" data-pin=\"203,139\" data-offset=\"-4\" shape=\"poly\" coords=\"204,140,205,141,203,142,203,143,206,147,204,147,202,148,200,147,200,145,201,143,200,142,200,142,198,140,198,139,200,138,199,137,200,136,200,136,203,138,202,139,203,139,204,140\" />\n";
  $output .= "            <area data-timezone=\"America/Halifax\" data-country=\"CA\" data-pin=\"194,76\" data-offset=\"-4\" shape=\"poly\" coords=\"193,75,194,74,192,74,193,73,196,74,197,74,198,74,193,76,191,78,190,77,190,76,192,74,193,75\" />\n";
  $output .= "            <area data-timezone=\"America/Havana\" data-country=\"CU\" data-pin=\"163,111\" data-offset=\"-5\" shape=\"poly\" coords=\"175,116,176,116,170,117,171,116,170,115,169,114,164,113,163,113,164,112,164,112,158,114,161,112,166,111,175,116\" />\n";
  $output .= "            <area data-timezone=\"America/Hermosillo\" data-country=\"MX\" data-pin=\"115,102\" data-offset=\"-7\" shape=\"poly\" coords=\"108,97,109,96,115,98,119,98,119,103,118,103,119,105,118,106,116,105,116,104,113,102,112,98,108,97\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Petersburg\" data-country=\"US\" data-pin=\"155,86\" data-offset=\"-5\" shape=\"poly\" coords=\"155,86,154,86,155,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Tell_City\" data-country=\"US\" data-pin=\"155,87\" data-offset=\"-6\" shape=\"poly\" coords=\"156,87,156,86,156,87\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Vevay\" data-country=\"US\" data-pin=\"158,85\" data-offset=\"-5\" shape=\"poly\" coords=\"159,85,158,85,159,85\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Indianapolis\" data-country=\"US\" data-pin=\"156,84\" data-offset=\"-5\" shape=\"poly\" coords=\"158,86,156,86,156,85,154,85,154,82,156,82,156,80,159,80,159,84,158,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Knox\" data-country=\"US\" data-pin=\"156,81\" data-offset=\"-6\" shape=\"poly\" coords=\"156,81,155,81,156,81\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Marengo\" data-country=\"US\" data-pin=\"156,86\" data-offset=\"-5\" shape=\"poly\" coords=\"156,86,156,86,156,86,156,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Vincennes\" data-country=\"US\" data-pin=\"154,86\" data-offset=\"-5\" shape=\"poly\" coords=\"154,86,154,85,156,85,156,86,154,86\" />\n";
  $output .= "            <area data-timezone=\"America/Indiana/Winamac\" data-country=\"US\" data-pin=\"156,82\" data-offset=\"-5\" shape=\"poly\" coords=\"156,81,155,82,156,81\" />\n";
  $output .= "            <area data-timezone=\"America/Inuvik\" data-country=\"CA\" data-pin=\"77,36\" data-offset=\"-7\" shape=\"poly\" coords=\"78,36,77,36,78,36\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"167,24,166,24,168,25,166,25,168,25,158,26,158,24,167,24\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"167,12,174,12,173,13,174,12,171,12,187,12,185,12,187,12,187,14,184,14,187,14,183,15,187,15,187,15,182,16,183,16,179,16,183,17,181,17,176,17,175,17,178,17,170,18,176,18,170,18,176,19,172,20,174,20,174,20,169,20,170,21,169,21,163,21,170,22,169,23,165,23,165,23,162,22,163,23,158,22,158,21,161,21,160,21,163,20,159,21,158,18,163,19,161,19,164,18,159,18,161,18,158,17,158,16,165,17,167,17,161,16,173,15,168,15,172,14,168,15,168,15,167,15,158,16,158,15,163,15,158,15,158,13,159,14,158,13,168,14,162,13,166,12,164,12,169,12,166,12,167,12\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"158,35,159,35,158,35,159,34,163,34,161,34,164,35,163,35,165,36,162,36,165,38,161,39,160,38,158,38,158,35\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"173,29,167,29,165,27,170,27,173,29\" />\n";
  $output .= "            <area data-timezone=\"America/Iqaluit\" data-country=\"CA\" data-pin=\"186,44\" data-offset=\"-5\" shape=\"poly\" coords=\"159,28,164,27,166,29,165,29,166,29,165,30,167,29,170,30,169,30,171,30,169,29,171,29,175,29,175,30,173,30,176,30,174,31,176,31,175,31,177,30,176,31,177,31,177,32,178,31,178,32,179,31,181,31,179,32,180,32,179,32,182,32,180,33,181,32,181,33,184,32,182,33,186,32,183,34,186,33,183,34,187,33,186,34,183,34,187,34,185,35,186,35,185,35,187,35,186,35,187,35,184,35,187,36,187,40,186,40,187,40,187,44,185,44,187,45,187,46,181,45,180,44,181,44,179,44,178,43,178,42,177,43,178,42,176,43,177,42,176,42,175,42,176,43,170,43,170,42,171,41,178,41,176,40,180,38,177,36,178,36,177,36,177,36,174,35,172,36,174,35,171,34,172,34,171,34,172,33,171,34,171,33,168,32,167,33,168,33,169,34,164,33,165,34,162,33,164,33,158,33,158,32,159,31,158,31,158,30,159,30,158,29,158,28,159,29,158,28,160,28,158,28,159,28\" />\n";
  $output .= "            <area data-timezone=\"America/La_Paz\" data-country=\"BO\" data-pin=\"186,178\" data-offset=\"-4\" shape=\"poly\" coords=\"196,185,196,187,193,187,193,188,192,187,190,186,188,188,187,188,185,184,186,182,184,179,185,177,184,176,186,171,184,168,186,169,189,166,191,166,192,170,199,173,200,177,203,177,203,179,204,180,203,183,201,182,197,183,196,185\" />\n";
  $output .= "            <area data-timezone=\"America/Jamaica\" data-country=\"JM\" data-pin=\"172,120\" data-offset=\"-5\" shape=\"poly\" coords=\"173,120,171,120,169,120,171,119,173,120\" />\n";
  $output .= "            <area data-timezone=\"America/Juneau\" data-country=\"US\" data-pin=\"76,53\" data-offset=\"-9\" shape=\"poly\" coords=\"71,52,74,50,79,54,77,55,78,54,77,54,79,54,77,53,78,54,77,53,77,52,75,53,74,51,74,51,75,53,73,53,74,52,73,52,73,52,72,52,73,52,72,53,70,52,71,52\" />\n";
  $output .= "            <area data-timezone=\"America/Kentucky/Louisville\" data-country=\"US\" data-pin=\"157,86\" data-offset=\"-5\" shape=\"poly\" coords=\"157,87,156,86,158,86,157,87\" />\n";
  $output .= "            <area data-timezone=\"America/Kentucky/Monticello\" data-country=\"US\" data-pin=\"159,89\" data-offset=\"-5\" shape=\"poly\" coords=\"159,88,159,89,158,89,159,88\" />\n";
  $output .= "            <area data-timezone=\"America/Kralendijk\" data-country=\"BQ\" data-pin=\"186,130\" data-offset=\"-4\" shape=\"poly\" coords=\"195,121,195,121,195,121\" />\n";
  $output .= "            <area data-timezone=\"America/Lima\" data-country=\"PE\" data-pin=\"172,170\" data-offset=\"-5\" shape=\"poly\" coords=\"184,179,184,180,183,181,181,179,175,176,173,174,173,172,167,162,165,160,165,159,164,157,165,156,166,156,166,157,168,158,169,156,174,153,175,152,174,150,175,150,178,154,180,154,183,155,182,156,183,157,182,157,179,159,178,161,177,163,178,165,178,166,179,166,180,167,183,166,182,168,184,168,186,171,184,176,185,177,184,179\" />\n";
  $output .= "            <area data-timezone=\"America/Managua\" data-country=\"NI\" data-pin=\"156,130\" data-offset=\"-6\" shape=\"poly\" coords=\"158,132,157,131,154,128,155,128,155,127,157,127,158,125,161,125,161,132,158,132\" />\n";
  $output .= "            <area data-timezone=\"America/Manaus\" data-country=\"BR\" data-pin=\"200,155\" data-offset=\"-4\" shape=\"poly\" coords=\"200,150,202,150,203,152,206,154,203,161,203,162,203,165,197,165,195,163,194,163,193,165,192,166,189,166,189,166,187,166,183,157,184,152,183,149,185,149,184,148,184,147,188,146,188,148,189,149,191,148,191,149,193,147,195,146,196,149,196,151,197,152,198,151,199,151,200,150\" />\n";
  $output .= "            <area data-timezone=\"America/Los_Angeles\" data-country=\"US\" data-pin=\"103,93\" data-offset=\"-8\" shape=\"poly\" coords=\"109,74,106,74,106,73,106,74,105,76,103,76,103,79,105,79,105,80,110,80,110,90,109,90,110,93,109,94,109,95,105,96,102,93,99,92,99,91,96,88,96,87,95,87,94,85,93,83,93,81,92,79,93,73,94,73,93,73,94,72,93,72,94,72,93,72,92,69,95,70,95,71,96,70,96,71,95,72,96,71,95,68,107,68,107,71,109,72,109,74\" />\n";
  $output .= "            <area data-timezone=\"America/Lower_Princes\" data-country=\"SX\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/Maceio\" data-country=\"BR\" data-pin=\"240,166\" data-offset=\"-3\" shape=\"poly\" coords=\"239,168,237,169,236,168,237,167,236,166,237,165,238,166,241,165,239,168\" />\n";
  $output .= "            <area data-timezone=\"America/Marigot\" data-country=\"MF\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/Martinique\" data-country=\"MQ\" data-pin=\"198,126\" data-offset=\"-4\" shape=\"poly\" coords=\"198,125,198,125,198,125\" />\n";
  $output .= "            <area data-timezone=\"America/Matamoros\" data-country=\"MX\" data-pin=\"138,107\" data-offset=\"-6\" shape=\"poly\" coords=\"136,107,134,106,131,101,129,101,128,102,128,102,129,100,131,100,134,104,135,106,138,107,138,107,136,107\" />\n";
  $output .= "            <area data-timezone=\"America/Mazatlan\" data-country=\"MX\" data-pin=\"123,111\" data-offset=\"-7\" shape=\"poly\" coords=\"125,112,127,114,126,115,124,115,125,114,124,112,120,108,118,107,118,106,120,105,122,109,125,112\" />\n";
  $output .= "            <area data-timezone=\"America/Mazatlan\" data-country=\"MX\" data-pin=\"123,111\" data-offset=\"-7\" shape=\"poly\" coords=\"110,103,112,103,117,111,117,112,113,109,113,107,111,105,111,105,108,104,110,104,110,103\" />\n";
  $output .= "            <area data-timezone=\"America/Menominee\" data-country=\"US\" data-pin=\"154,75\" data-offset=\"-6\" shape=\"poly\" coords=\"154,74,153,73,149,72,154,73,155,74,154,74\" />\n";
  $output .= "            <area data-timezone=\"America/Mexico_City\" data-country=\"MX\" data-pin=\"135,118\" data-offset=\"-6\" shape=\"poly\" coords=\"143,123,142,123,139,124,137,123,125,118,124,116,125,115,126,115,127,115,126,113,127,110,129,109,129,108,132,109,133,112,137,113,140,119,143,120,146,119,147,120,148,120,148,121,148,121,149,123,147,123,146,126,143,123,143,123\" />\n";
  $output .= "            <area data-timezone=\"America/Merida\" data-country=\"MX\" data-pin=\"151,115\" data-offset=\"-6\" shape=\"poly\" coords=\"149,115,150,115,154,114,154,116,151,117,151,120,147,120,146,119,148,119,149,115\" />\n";
  $output .= "            <area data-timezone=\"America/Metlakatla\" data-country=\"US\" data-pin=\"81,58\" data-offset=\"-8\" shape=\"poly\" coords=\"81,58,81,58,81,58\" />\n";
  $output .= "            <area data-timezone=\"America/Miquelon\" data-country=\"PM\" data-pin=\"206,72\" data-offset=\"-3\" shape=\"poly\" coords=\"206,72,206,71,206,72\" />\n";
  $output .= "            <area data-timezone=\"America/Moncton\" data-country=\"CA\" data-pin=\"192,73\" data-offset=\"-4\" shape=\"poly\" coords=\"189,70,192,70,191,72,192,72,192,73,194,73,190,75,188,75,187,74,187,72,185,71,186,70,189,70\" />\n";
  $output .= "            <area data-timezone=\"America/Monterrey\" data-country=\"MX\" data-pin=\"133,107\" data-offset=\"-6\" shape=\"poly\" coords=\"129,108,129,109,127,110,126,113,125,112,121,108,123,105,126,106,127,106,127,104,128,102,129,101,131,101,135,106,138,107,137,108,138,108,137,108,137,113,133,112,132,109,129,108\" />\n";
  $output .= "            <area data-timezone=\"America/Montevideo\" data-country=\"UY\" data-pin=\"206,208\" data-offset=\"-2\" shape=\"poly\" coords=\"207,208,204,207,203,207,204,200,205,200,207,202,207,201,212,205,210,208,207,208\" />\n";
  $output .= "            <area data-timezone=\"America/Montreal\" data-country=\"CA\" data-pin=\"176,73\" data-offset=\"-5\" shape=\"poly\" coords=\"175,75,176,74,172,74,169,73,167,71,167,64,169,65,168,64,169,63,168,62,168,60,167,59,170,58,172,56,172,53,169,52,171,50,170,49,171,49,170,49,171,49,170,49,171,47,170,47,170,46,176,46,177,46,180,47,180,47,181,47,180,48,181,48,184,48,184,50,182,50,184,50,184,51,185,52,183,52,185,52,186,52,184,54,187,52,186,53,187,53,187,53,189,52,190,53,190,52,191,52,190,51,192,51,191,50,192,50,192,51,193,51,193,52,192,52,194,52,193,53,194,54,193,56,194,57,194,57,194,58,194,58,193,59,190,59,189,58,189,59,188,58,188,59,187,60,189,61,188,62,189,62,189,63,192,64,193,62,194,62,194,62,193,63,194,63,205,63,205,64,202,64,201,66,197,66,189,66,189,66,188,68,186,68,182,72,179,73,189,68,192,68,193,69,191,70,186,70,183,72,182,74,181,75,175,75\" />\n";
  $output .= "            <area data-timezone=\"America/Montserrat\" data-country=\"MS\" data-pin=\"196,122\" data-offset=\"-4\" shape=\"poly\" coords=\"196,122,196,122,196,122\" />\n";
  $output .= "            <area data-timezone=\"America/Nassau\" data-country=\"BS\" data-pin=\"171,108\" data-offset=\"-5\" shape=\"poly\" coords=\"172,107,172,107,172,107\" />\n";
  $output .= "            <area data-timezone=\"America/New_York\" data-country=\"US\" data-pin=\"177,82\" data-offset=\"-5\" shape=\"poly\" coords=\"158,99,157,92,159,90,159,89,156,87,159,85,158,85,159,81,164,81,168,79,168,78,173,77,173,76,175,75,182,75,185,71,187,72,187,74,188,75,186,76,185,76,185,77,183,77,182,79,183,80,183,80,183,81,181,81,181,80,181,81,177,82,175,85,174,84,175,86,173,88,174,87,173,85,173,84,172,85,173,87,171,86,173,87,172,87,173,88,172,88,173,88,174,90,173,89,174,90,172,90,174,91,172,91,173,91,172,92,173,92,172,92,165,96,164,98,167,105,166,108,165,108,163,106,164,105,163,106,162,104,163,104,162,104,162,101,160,100,158,101,158,99\" />\n";
  $output .= "            <area data-timezone=\"America/Nipigon\" data-country=\"CA\" data-pin=\"153,68\" data-offset=\"-5\" shape=\"poly\" coords=\"153,68,153,68,153,68\" />\n";
  $output .= "            <area data-timezone=\"America/Nome\" data-country=\"US\" data-pin=\"24,42\" data-offset=\"-9\" shape=\"poly\" coords=\"30,38,27,38,26,37,22,36,23,35,27,35,30,33,30,38\" />\n";
  $output .= "            <area data-timezone=\"America/Nome\" data-country=\"US\" data-pin=\"24,42\" data-offset=\"-9\" shape=\"poly\" coords=\"30,42,29,43,23,42,22,41,23,41,20,41,27,39,26,40,30,40,30,42\" />\n";
  $output .= "            <area data-timezone=\"America/Nome\" data-country=\"US\" data-pin=\"24,42\" data-offset=\"-9\" shape=\"poly\" coords=\"30,50,26,50,24,49,25,48,23,47,26,45,28,45,30,44,30,50\" />\n";
  $output .= "            <area data-timezone=\"America/Noronha\" data-country=\"BR\" data-pin=\"246,156\" data-offset=\"-2\" shape=\"poly\" coords=\"246,156,246,156,246,156\" />\n";
  $output .= "            <area data-timezone=\"America/North_Dakota/Beulah\" data-country=\"US\" data-pin=\"130,71\" data-offset=\"-6\" shape=\"poly\" coords=\"131,71,130,72,130,71,131,71\" />\n";
  $output .= "            <area data-timezone=\"America/North_Dakota/Center\" data-country=\"US\" data-pin=\"131,71\" data-offset=\"-6\" shape=\"poly\" coords=\"132,71,130,72,132,71\" />\n";
  $output .= "            <area data-timezone=\"America/North_Dakota/New_Salem\" data-country=\"US\" data-pin=\"131,72\" data-offset=\"-6\" shape=\"poly\" coords=\"132,72,132,72,132,73,130,72,132,72\" />\n";
  $output .= "            <area data-timezone=\"America/Ojinaga\" data-country=\"MX\" data-pin=\"126,101\" data-offset=\"-7\" shape=\"poly\" coords=\"122,97,128,102,125,101,125,99,122,97,119,98,120,98,120,97,122,97\" />\n";
  $output .= "            <area data-timezone=\"America/Panama\" data-country=\"PA\" data-pin=\"167,135\" data-offset=\"-5\" shape=\"poly\" coords=\"171,136,171,137,170,138,169,137,170,136,170,136,168,135,166,136,167,137,166,138,165,138,165,137,165,137,163,136,162,137,162,136,162,134,165,135,168,134,171,136\" />\n";
  $output .= "            <area data-timezone=\"America/Pangnirtung\" data-country=\"CA\" data-pin=\"190,40\" data-offset=\"-5\" shape=\"poly\" coords=\"187,12,192,12,198,13,187,14,187,12\" />\n";
  $output .= "            <area data-timezone=\"America/Pangnirtung\" data-country=\"CA\" data-pin=\"190,40\" data-offset=\"-5\" shape=\"poly\" coords=\"187,41,188,41,189,42,189,42,192,43,191,43,192,43,192,44,192,45,191,44,192,45,191,45,191,46,189,44,189,45,187,44,187,41\" />\n";
  $output .= "            <area data-timezone=\"America/Pangnirtung\" data-country=\"CA\" data-pin=\"190,40\" data-offset=\"-5\" shape=\"poly\" coords=\"187,36,189,36,187,36,190,36,189,37,189,37,190,37,190,37,192,37,191,37,192,37,193,38,192,38,193,38,192,38,195,38,194,39,195,38,195,39,197,38,198,39,195,39,197,40,195,40,196,40,195,40,196,41,194,40,194,42,191,40,193,39,190,40,191,39,190,40,187,39,188,39,187,39,188,40,187,40,187,36\" />\n";
  $output .= "            <area data-timezone=\"America/Paramaribo\" data-country=\"SR\" data-pin=\"208,140\" data-offset=\"-3\" shape=\"poly\" coords=\"209,141,210,144,209,146,207,146,207,147,206,147,203,143,204,142,205,141,205,140,210,140,209,141\" />\n";
  $output .= "            <area data-timezone=\"America/Phoenix\" data-country=\"US\" data-pin=\"113,94\" data-offset=\"-7\" shape=\"poly\" coords=\"118,96,118,98,115,98,109,96,110,93,109,90,110,90,110,88,114,88,114,89,115,91,118,91,118,96\" />\n";
  $output .= "            <area data-timezone=\"America/Port-au-Prince\" data-country=\"HT\" data-pin=\"179,119\" data-offset=\"-5\" shape=\"poly\" coords=\"178,118,179,119,178,118\" />\n";
  $output .= "            <area data-timezone=\"America/Port_of_Spain\" data-country=\"TT\" data-pin=\"197,132\" data-offset=\"-4\" shape=\"poly\" coords=\"197,132,197,132,197,132\" />\n";
  $output .= "            <area data-timezone=\"America/Porto_Velho\" data-country=\"BR\" data-pin=\"194,165\" data-offset=\"-4\" shape=\"poly\" coords=\"200,170,199,172,197,173,192,170,191,166,189,166,189,166,192,166,193,165,194,163,195,163,198,165,197,168,200,169,200,170\" />\n";
  $output .= "            <area data-timezone=\"America/Puerto_Rico\" data-country=\"PR\" data-pin=\"190,119\" data-offset=\"-4\" shape=\"poly\" coords=\"190,119,190,119,190,119\" />\n";
  $output .= "            <area data-timezone=\"America/Rainy_River\" data-country=\"CA\" data-pin=\"142,69\" data-offset=\"-6\" shape=\"poly\" coords=\"142,69,142,69,142,69\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,14,150,15,149,15,155,14,147,14,158,13,158,14\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"136,22,137,23,137,24,138,24,133,25,132,24,134,24,130,24,130,24,131,24,130,23,134,24,133,23,134,23,132,23,136,22\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"150,19,151,20,146,20,145,19,147,19,143,18,149,18,141,18,140,17,143,17,139,17,143,17,142,17,143,16,139,16,144,16,141,15,145,15,143,15,145,14,152,16,154,16,153,16,155,17,154,17,158,18,154,19,153,18,153,19,154,19,152,19,152,20,150,19\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,26,147,25,146,25,146,24,145,23,141,23,142,23,138,22,141,22,145,22,144,23,148,22,149,23,147,23,151,23,148,23,152,24,158,24,158,26\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,22,151,22,156,21,153,21,155,20,158,21,158,22\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"158,33,152,32,151,32,155,32,150,31,150,30,151,30,150,29,153,27,158,27,155,29,156,29,156,30,158,31,155,32,158,31,158,33\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"150,43,149,44,143,43,147,44,149,45,146,45,147,46,144,47,145,47,143,48,144,48,142,50,130,50,130,38,152,38,152,35,153,35,154,36,153,37,155,38,156,38,157,35,158,35,158,40,155,39,157,40,154,41,148,40,155,41,153,43,150,43\" />\n";
  $output .= "            <area data-timezone=\"America/Rankin_Inlet\" data-country=\"CA\" data-pin=\"147,45\" data-offset=\"-6\" shape=\"poly\" coords=\"141,27,144,26,150,27,147,29,143,29,144,29,144,30,140,29,141,27,142,27,141,27\" />\n";
  $output .= "            <area data-timezone=\"America/Recife\" data-country=\"BR\" data-pin=\"242,163\" data-offset=\"-3\" shape=\"poly\" coords=\"236,163,237,163,238,162,238,164,241,162,242,163,241,165,238,166,237,165,236,166,234,164,232,166,231,165,232,164,232,162,236,163\" />\n";
  $output .= "            <area data-timezone=\"America/Regina\" data-country=\"CA\" data-pin=\"126,66\" data-offset=\"-6\" shape=\"poly\" coords=\"125,68,117,68,117,62,119,62,117,61,117,50,130,50,130,58,129,59,130,60,131,68,125,68\" />\n";
  $output .= "            <area data-timezone=\"America/Resolute\" data-country=\"CA\" data-pin=\"142,26\" data-offset=\"-6\" shape=\"poly\" coords=\"141,24,144,24,144,25,139,25,141,24\" />\n";
  $output .= "            <area data-timezone=\"America/Rio_Branco\" data-country=\"BR\" data-pin=\"187,167\" data-offset=\"-4\" shape=\"poly\" coords=\"177,162,183,164,189,167,186,169,182,168,183,166,180,167,179,166,178,166,178,165,177,163,177,162\" />\n";
  $output .= "            <area data-timezone=\"America/Santa_Isabel\" data-country=\"PR\" data-pin=\"300,150\" data-offset=\"-8\" shape=\"poly\" coords=\"109,96,109,100,112,103,110,103,110,102,107,100,106,97,106,96,109,96\" />\n";
  $output .= "            <area data-timezone=\"America/Santarem\" data-country=\"BR\" data-pin=\"209,154\" data-offset=\"-3\" shape=\"poly\" coords=\"212,150,213,152,214,152,213,153,214,155,212,156,212,158,213,161,212,164,213,165,212,166,205,166,204,165,203,161,206,154,202,151,202,148,204,147,207,147,207,146,208,146,209,147,211,148,212,150\" />\n";
  $output .= "            <area data-timezone=\"America/Santiago\" data-country=\"CL\" data-pin=\"182,206\" data-offset=\"-3\" shape=\"poly\" coords=\"183,239,184,237,186,238,186,241,180,241,183,241,182,240,185,241,184,241,185,241,183,240,184,239,183,239,183,239\" />\n";
  $output .= "            <area data-timezone=\"America/Santiago\" data-country=\"CL\" data-pin=\"182,206\" data-offset=\"-3\" shape=\"poly\" coords=\"178,232,177,234,178,235,180,234,179,236,180,237,186,237,182,238,181,240,179,239,180,239,181,238,181,238,179,238,178,238,178,238,179,238,177,238,177,237,179,237,179,237,179,237,178,237,179,237,179,236,178,235,178,236,179,236,178,236,178,236,178,237,178,236,178,237,177,236,177,235,176,235,178,234,177,234,177,235,177,234,176,234,177,234,176,233,177,233,177,232,176,232,176,231,177,231,176,231,177,231,176,230,178,230,177,229,175,230,176,229,176,229,177,229,176,228,174,228,175,226,177,226,177,228,178,226,177,226,178,226,179,226,177,225,179,224,178,224,179,221,179,221,179,221,179,220,179,220,180,219,177,220,177,219,178,216,177,212,178,212,181,206,181,198,182,194,182,189,183,186,183,181,184,179,186,182,185,184,187,188,188,188,188,190,186,191,186,195,184,197,184,200,182,202,184,207,182,209,183,210,181,211,182,214,180,216,180,220,180,221,181,223,180,224,181,224,180,225,181,225,180,226,181,228,179,230,179,231,178,232\" />\n";
  $output .= "            <area data-timezone=\"America/Santo_Domingo\" data-country=\"DO\" data-pin=\"184,119\" data-offset=\"-4\" shape=\"poly\" coords=\"182,120,181,120,180,119,181,117,183,117,185,118,184,118,186,119,186,120,182,120\" />\n";
  $output .= "            <area data-timezone=\"America/Scoresbysund\" data-country=\"GL\" data-pin=\"263,33\" data-offset=\"-1\" shape=\"poly\" coords=\"259,29,263,30,261,31,263,30,262,31,264,31,264,31,263,32,264,32,263,32,264,32,260,32,259,31,259,29\" />\n";
  $output .= "            <area data-timezone=\"America/Sao_Paulo\" data-country=\"BR\" data-pin=\"222,189\" data-offset=\"-3\" shape=\"poly\" coords=\"217,199,215,202,213,204,216,201,215,200,215,201,211,206,211,205,212,205,210,204,207,201,207,202,205,200,204,200,207,197,211,195,210,193,209,193,209,190,211,188,213,186,215,182,212,181,211,179,215,175,216,171,218,172,218,171,219,172,220,172,223,171,223,175,226,174,233,177,232,179,234,181,234,182,232,185,232,187,230,188,226,188,220,192,219,192,219,193,219,194,219,194,219,197,217,199\" />\n";
  $output .= "            <area data-timezone=\"America/Sitka\" data-country=\"US\" data-pin=\"74,55\" data-offset=\"-9\" shape=\"poly\" coords=\"79,54,80,56,77,55,79,54,79,54\" />\n";
  $output .= "            <area data-timezone=\"America/St_Barthelemy\" data-country=\"BL\" data-pin=\"195,120\" data-offset=\"-4\" shape=\"poly\" coords=\"195,120,195,120,195,120\" />\n";
  $output .= "            <area data-timezone=\"America/St_Johns\" data-country=\"CA\" data-pin=\"212,71\" data-offset=\"-3.5\" shape=\"poly\" coords=\"208,64,206,64,207,65,206,65,205,67,206,66,208,67,206,68,208,67,208,68,209,67,211,68,210,69,212,69,210,70,211,71,212,70,211,71,212,71,212,72,211,72,211,71,210,72,210,71,210,70,207,72,209,71,206,71,207,71,207,70,205,71,201,71,203,69,201,69,203,68,204,68,203,68,204,68,203,67,204,65,208,64\" />\n";
  $output .= "            <area data-timezone=\"America/Thule\" data-country=\"GL\" data-pin=\"185,22\" data-offset=\"-4\" shape=\"poly\" coords=\"194,23,186,23,184,23,187,22,181,22,190,21,183,21,184,21,178,20,189,18,194,23\" />\n";
  $output .= "            <area data-timezone=\"America/St_Kitts\" data-country=\"KN\" data-pin=\"195,121\" data-offset=\"-4\" shape=\"poly\" coords=\"195,121,196,121,195,121\" />\n";
  $output .= "            <area data-timezone=\"America/St_Lucia\" data-country=\"LC\" data-pin=\"198,127\" data-offset=\"-4\" shape=\"poly\" coords=\"198,126,198,127,198,126\" />\n";
  $output .= "            <area data-timezone=\"America/St_Thomas\" data-country=\"VI\" data-pin=\"192,119\" data-offset=\"-4\" shape=\"poly\" coords=\"192,119,192,119,192,119\" />\n";
  $output .= "            <area data-timezone=\"America/St_Vincent\" data-country=\"VC\" data-pin=\"198,128\" data-offset=\"-4\" shape=\"poly\" coords=\"198,128,198,128,198,128\" />\n";
  $output .= "            <area data-timezone=\"America/Swift_Current\" data-country=\"CA\" data-pin=\"120,66\" data-offset=\"-6\" shape=\"poly\" coords=\"120,66,120,66,120,66\" />\n";
  $output .= "            <area data-timezone=\"America/Tegucigalpa\" data-country=\"HN\" data-pin=\"155,127\" data-offset=\"-6\" shape=\"poly\" coords=\"155,127,154,128,154,127,151,126,151,125,153,124,157,123,159,124,161,125,158,125,157,127,155,127\" />\n";
  $output .= "            <area data-timezone=\"America/Thunder_Bay\" data-country=\"CA\" data-pin=\"151,69\" data-offset=\"-5\" shape=\"poly\" coords=\"151,69,151,70,151,69\" />\n";
  $output .= "            <area data-timezone=\"America/Tijuana\" data-country=\"MX\" data-pin=\"105,96\" data-offset=\"-8\" shape=\"poly\" coords=\"105,96,105,96,105,96\" />\n";
  $output .= "            <area data-timezone=\"America/Toronto\" data-country=\"CA\" data-pin=\"168,77\" data-offset=\"-5\" shape=\"poly\" coords=\"175,74,176,75,172,77,170,76,172,76,171,77,168,77,167,78,168,79,161,80,164,78,165,76,164,75,165,76,167,75,165,73,164,73,164,74,160,73,159,72,159,70,157,70,156,69,153,68,153,69,151,69,151,70,148,69,148,68,150,68,150,65,149,64,150,63,150,62,152,62,153,61,152,60,150,60,150,56,152,55,154,57,158,58,163,58,163,62,166,64,167,64,168,72,169,73,173,74,175,74\" />\n";
  $output .= "            <area data-timezone=\"America/Tortola\" data-country=\"VG\" data-pin=\"192,119\" data-offset=\"-4\" shape=\"poly\" coords=\"192,119,192,119,192,119\" />\n";
  $output .= "            <area data-timezone=\"America/Vancouver\" data-country=\"CA\" data-pin=\"95,68\" data-offset=\"-8\" shape=\"poly\" coords=\"86,65,91,66,95,69,91,69,92,68,91,68,90,68,89,68,90,67,87,66,88,66,86,65\" />\n";
  $output .= "            <area data-timezone=\"America/Vancouver\" data-country=\"CA\" data-pin=\"95,68\" data-offset=\"-8\" shape=\"poly\" coords=\"75,51,74,50,71,52,68,50,100,50,100,55,94,55,95,58,103,62,103,63,102,63,106,66,106,68,95,68,98,68,97,68,96,67,97,68,95,68,96,67,95,68,95,68,95,68,94,67,95,67,93,67,94,67,93,67,94,66,93,67,92,67,93,66,92,66,93,65,92,65,91,66,90,66,91,65,90,66,89,65,90,65,88,65,89,65,87,64,89,64,87,64,87,64,88,63,89,63,88,63,89,63,88,63,88,62,88,63,87,63,87,62,86,63,87,62,85,61,87,62,85,61,86,60,85,61,83,60,85,59,83,59,83,60,83,59,85,58,83,58,84,58,84,57,83,58,83,56,80,56,78,53,75,51\" />\n";
  $output .= "            <area data-timezone=\"America/Whitehorse\" data-country=\"CA\" data-pin=\"75,49\" data-offset=\"-8\" shape=\"poly\" coords=\"69,34,73,35,73,38,77,38,77,40,79,40,79,42,83,44,84,44,83,45,84,45,85,46,88,48,89,49,92,48,93,50,65,49,65,34,69,34\" />\n";
  $output .= "            <area data-timezone=\"America/Winnipeg\" data-country=\"CA\" data-pin=\"138,67\" data-offset=\"-6\" shape=\"poly\" coords=\"147,69,142,69,141,68,141,68,131,68,130,60,129,59,130,58,130,50,142,50,142,52,145,52,146,54,145,55,148,55,152,55,150,56,150,60,152,60,153,61,152,62,150,62,150,63,149,64,150,65,150,68,147,68,147,69\" />\n";
  $output .= "            <area data-timezone=\"America/Yakutat\" data-country=\"US\" data-pin=\"67,51\" data-offset=\"-9\" shape=\"poly\" coords=\"67,50,67,50,67,50\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"100,37,111,41,114,41,116,42,130,43,130,50,93,50,92,48,89,49,88,48,85,46,84,45,83,45,84,44,83,44,79,42,79,40,77,40,77,38,73,38,73,35,75,36,73,35,76,34,77,34,77,35,84,33,80,34,80,35,82,34,82,35,83,34,88,33,87,32,91,34,92,33,93,34,93,34,95,34,98,34,97,34,97,36,100,37\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"106,21,108,21,106,21,107,22,105,23,103,23,104,22,100,24,95,23,101,21,106,21\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"100,27,104,26,108,28,101,29,100,30,99,31,95,32,90,30,94,27,92,26,98,26,100,27\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"109,23,117,24,117,25,111,26,109,26,115,25,104,25,108,24,105,24,109,24,105,23,109,23\" />\n";
  $output .= "            <area data-timezone=\"America/Yellowknife\" data-country=\"CA\" data-pin=\"109,46\" data-offset=\"-7\" shape=\"poly\" coords=\"108,28,110,28,109,29,112,28,115,29,114,30,117,29,117,33,105,33,105,34,104,33,114,33,104,32,103,32,108,31,103,31,104,31,101,30,103,30,103,29,108,28\" />\n";
  $output .= "            <area data-timezone=\"Antarctica/Macquarie\" data-country=\"AU\" data-pin=\"565,241\" data-offset=\"11\" shape=\"poly\" coords=\"565,241,565,241,565,241\" />\n";
  $output .= "            <area data-timezone=\"Arctic/Longyearbyen\" data-country=\"SJ\" data-pin=\"327,20\" data-offset=\"1\" shape=\"poly\" coords=\"330,17,330,17,329,18,331,17,336,19,332,19,332,20,328,22,328,22,327,22,326,22,328,22,323,21,327,21,325,21,328,20,323,20,323,20,329,19,327,19,328,19,326,19,326,19,324,19,325,19,322,20,321,19,322,19,319,18,321,18,319,18,320,18,319,18,318,17,323,17,321,17,323,18,323,17,324,17,327,18,326,17,330,17\" />\n";
  $output .= "            <area data-timezone=\"Arctic/Longyearbyen\" data-country=\"SJ\" data-pin=\"327,20\" data-offset=\"1\" shape=\"poly\" coords=\"342,16,345,17,339,18,329,16,332,16,332,16,333,16,333,16,337,17,338,16,339,16,339,16,342,16\" />\n";
  $output .= "            <area data-timezone=\"Asia/Aden\" data-country=\"YE\" data-pin=\"375,129\" data-offset=\"3\" shape=\"poly\" coords=\"375,128,372,129,371,125,372,121,377,123,377,124,381,120,387,118,389,122,387,123,387,124,375,128\" />\n";
  $output .= "            <area data-timezone=\"Asia/Almaty\" data-country=\"KZ\" data-pin=\"428,78\" data-offset=\"6\" shape=\"poly\" coords=\"418,79,414,82,411,81,410,79,413,78,413,77,412,76,412,73,404,72,407,70,407,70,408,68,410,68,414,66,413,65,413,63,410,63,411,62,410,62,411,61,410,59,415,58,418,58,419,60,423,60,422,61,428,59,427,60,430,61,433,65,434,64,436,65,439,65,442,67,445,67,446,68,443,69,443,72,438,71,437,74,437,75,436,74,433,75,434,75,435,78,434,80,431,78,426,79,424,78,423,78,422,79,420,79,418,79\" />\n";
  $output .= "            <area data-timezone=\"Asia/Amman\" data-country=\"JO\" data-pin=\"360,97\" data-offset=\"2\" shape=\"poly\" coords=\"362,98,363,99,362,100,360,101,358,101,359,96,361,96,365,94,366,96,362,98,362,98\" />\n";
  $output .= "            <area data-timezone=\"Asia/Anadyr\" data-country=\"RU\" data-pin=\"596,42\" data-offset=\"12\" shape=\"poly\" coords=\"12,38,17,40,16,41,14,40,15,41,12,41,13,41,13,42,11,42,13,43,12,43,8,42,7,41,2,41,2,40,3,39,1,40,1,41,0,42,0,35,8,37,9,39,11,39,9,38,12,38\" />\n";
  $output .= "            <area data-timezone=\"Asia/Anadyr\" data-country=\"RU\" data-pin=\"596,42\" data-offset=\"12\" shape=\"poly\" coords=\"596,42,592,42,597,43,599,46,598,46,595,46,591,47,589,46,584,46,581,45,583,44,581,43,568,41,565,40,565,40,564,39,565,38,563,37,564,36,571,36,571,35,571,34,571,34,580,34,583,35,585,35,584,34,584,33,594,34,600,35,600,42,598,42,596,42\" />\n";
  $output .= "            <area data-timezone=\"Asia/Aqtau\" data-country=\"KZ\" data-pin=\"384,76\" data-offset=\"5\" shape=\"poly\" coords=\"393,75,393,81,390,79,387,80,388,79,385,78,384,76,386,76,385,75,386,74,388,74,388,72,385,72,382,73,381,72,382,72,381,71,378,70,382,70,390,68,390,69,392,69,392,72,394,73,395,75,393,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Aqtobe\" data-country=\"KZ\" data-pin=\"395,66\" data-offset=\"5\" shape=\"poly\" coords=\"391,66,391,65,393,66,394,65,397,65,399,66,402,65,403,64,405,67,404,68,407,70,405,71,402,70,398,74,395,75,394,72,392,71,392,69,389,68,391,67,391,66\" />\n";
  $output .= "            <area data-timezone=\"Asia/Ashgabat\" data-country=\"TM\" data-pin=\"397,87\" data-offset=\"5\" shape=\"poly\" coords=\"408,89,407,90,404,91,402,91,402,89,401,89,399,87,395,86,390,88,390,85,389,85,389,84,390,84,388,83,388,82,387,80,390,79,393,81,395,81,395,80,397,79,398,80,397,79,398,79,400,80,401,81,403,81,404,83,411,87,411,88,409,87,408,89\" />\n";
  $output .= "            <area data-timezone=\"Asia/Baghdad\" data-country=\"IQ\" data-pin=\"374,94\" data-offset=\"3\" shape=\"poly\" coords=\"379,100,378,101,375,101,370,98,365,96,365,94,368,93,369,89,371,88,375,88,376,90,377,90,376,93,377,95,379,96,380,97,379,98,381,100,379,100\" />\n";
  $output .= "            <area data-timezone=\"Asia/Bahrain\" data-country=\"BH\" data-pin=\"384,106\" data-offset=\"3\" shape=\"poly\" coords=\"384,106,384,106,384,106\" />\n";
  $output .= "            <area data-timezone=\"Asia/Bangkok\" data-country=\"TH\" data-pin=\"468,127\" data-offset=\"7\" shape=\"poly\" coords=\"470,139,470,140,469,141,469,140,467,139,467,139,464,136,465,132,466,130,465,127,464,125,465,123,462,119,463,119,463,117,465,117,467,116,468,116,468,117,469,117,468,121,470,120,471,120,472,119,473,119,476,124,475,126,473,126,471,127,472,131,470,129,468,129,468,128,467,128,465,135,466,134,468,138,470,139\" />\n";
  $output .= "            <area data-timezone=\"Asia/Baku\" data-country=\"AZ\" data-pin=\"383,83\" data-offset=\"4\" shape=\"poly\" coords=\"377,80,380,81,381,80,384,83,382,83,381,86,380,85,381,84,380,84,378,85,378,84,376,83,377,83,375,81,378,81,377,80\" />\n";
  $output .= "            <area data-timezone=\"Asia/Chongqing\" data-country=\"CN\" data-pin=\"475,100\" data-offset=\"8\" shape=\"poly\" coords=\"483,114,482,114,481,113,481,114,479,114,478,113,478,112,476,111,473,112,472,112,472,113,471,112,469,113,470,115,469,115,469,114,467,114,467,113,465,113,466,112,465,111,465,110,463,110,463,108,465,107,465,104,463,103,465,103,464,102,465,101,465,98,462,95,463,94,462,94,464,94,465,93,465,92,465,92,465,90,466,87,466,87,466,87,462,85,463,84,461,84,462,82,464,82,462,79,468,79,475,81,479,79,484,79,485,78,488,80,491,80,490,81,490,82,487,83,485,84,484,86,485,87,484,92,485,94,483,95,484,96,482,96,484,98,481,99,481,100,482,102,482,104,481,105,482,105,482,106,483,107,485,106,485,108,486,108,487,109,486,111,487,111,488,113,486,114,485,113,486,113,485,112,483,114\" />\n";
  $output .= "            <area data-timezone=\"Asia/Beirut\" data-country=\"LB\" data-pin=\"359,94\" data-offset=\"2\" shape=\"poly\" coords=\"360,94,359,95,359,93,361,92,361,93,360,94\" />\n";
  $output .= "            <area data-timezone=\"Asia/Bishkek\" data-country=\"KG\" data-pin=\"424,79\" data-offset=\"6\" shape=\"poly\" coords=\"425,82,423,83,423,84,420,85,415,84,422,82,420,81,419,81,417,81,419,80,418,79,419,79,422,79,423,78,424,78,426,79,431,78,434,80,430,82,428,82,427,83,425,82\" />\n";
  $output .= "            <area data-timezone=\"Asia/Brunei\" data-country=\"BN\" data-pin=\"492,142\" data-offset=\"8\" shape=\"poly\" coords=\"492,142,492,143,492,142\" />\n";
  $output .= "            <area data-timezone=\"Asia/Choibalsan\" data-country=\"MN\" data-pin=\"491,70\" data-offset=\"8\" shape=\"poly\" coords=\"494,73,493,74,491,74,489,75,487,75,486,73,486,71,488,70,487,67,491,66,495,67,493,70,493,71,497,70,500,71,499,73,498,72,494,73\" />\n";
  $output .= "            <area data-timezone=\"Asia/Colombo\" data-country=\"LK\" data-pin=\"433,138\" data-offset=\"5.5\" shape=\"poly\" coords=\"436,139,434,140,433,140,433,136,433,134,436,136,436,138,436,139\" />\n";
  $output .= "            <area data-timezone=\"Asia/Damascus\" data-country=\"SY\" data-pin=\"360,94\" data-offset=\"2\" shape=\"poly\" coords=\"362,96,361,96,359,95,359,95,361,93,360,91,361,90,361,89,365,89,371,88,369,89,368,93,362,96\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dhaka\" data-country=\"BD\" data-pin=\"451,110\" data-offset=\"6\" shape=\"poly\" coords=\"450,113,450,112,450,114,449,114,448,110,447,109,448,108,447,107,447,106,449,107,449,106,450,108,454,108,452,110,453,112,454,111,454,115,454,114,454,115,453,113,454,113,453,113,452,112,451,112,451,110,450,110,451,111,448,110,450,111,451,113,450,113\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dili\" data-country=\"TL\" data-pin=\"509,164\" data-offset=\"9\" shape=\"poly\" coords=\"509,164,512,164,509,166,509,164\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dubai\" data-country=\"AE\" data-pin=\"392,108\" data-offset=\"4\" shape=\"poly\" coords=\"393,109,393,110,392,110,392,112,388,112,386,110,391,110,394,107,394,108,393,109\" />\n";
  $output .= "            <area data-timezone=\"Asia/Dushanbe\" data-country=\"TJ\" data-pin=\"415,86\" data-offset=\"5\" shape=\"poly\" coords=\"416,88,413,88,414,86,412,84,414,84,415,83,414,83,417,82,418,82,417,83,418,83,416,83,416,84,419,84,420,85,423,84,423,86,425,86,425,88,422,88,419,89,419,87,418,86,416,88\" />\n";
  $output .= "            <area data-timezone=\"Asia/Gaza\" data-country=\"PS\" data-pin=\"357,98\" data-offset=\"2\" shape=\"poly\" coords=\"357,97,357,98,357,97\" />\n";
  $output .= "            <area data-timezone=\"Asia/Harbin\" data-country=\"CN\" data-pin=\"509,75\" data-offset=\"8\" shape=\"poly\" coords=\"509,82,508,78,507,79,506,78,505,76,504,76,504,75,503,74,505,74,505,72,506,72,504,71,507,69,508,70,510,65,509,64,505,64,504,63,507,61,510,62,513,67,518,69,518,71,524,69,525,70,522,75,520,74,518,75,519,78,517,79,518,79,517,78,516,79,513,80,514,81,512,80,509,82\" />\n";
  $output .= "            <area data-timezone=\"Asia/Hebron\" data-country=\"PS\" data-pin=\"358,97\" data-offset=\"2\" shape=\"poly\" coords=\"359,97,359,96,359,97,358,98,359,97\" />\n";
  $output .= "            <area data-timezone=\"Asia/Ho_Chi_Minh\" data-country=\"VN\" data-pin=\"478,132\" data-offset=\"7\" shape=\"poly\" coords=\"476,135,475,136,475,133,474,133,475,132,477,132,476,131,479,129,479,125,475,119,473,118,475,117,474,116,474,116,472,115,472,114,470,113,471,112,473,112,476,111,478,112,478,113,480,114,478,115,478,116,476,118,482,125,482,129,482,131,478,133,478,134,476,135\" />\n";
  $output .= "            <area data-timezone=\"Asia/Hong_Kong\" data-country=\"HK\" data-pin=\"490,113\" data-offset=\"8\" shape=\"poly\" coords=\"490,112,490,113,490,112\" />\n";
  $output .= "            <area data-timezone=\"Asia/Hovd\" data-country=\"MN\" data-pin=\"453,70\" data-offset=\"7\" shape=\"poly\" coords=\"464,75,463,77,463,79,461,79,459,76,451,75,452,72,450,70,447,69,446,68,454,65,457,66,458,67,462,67,462,68,463,68,465,68,465,69,463,70,464,71,463,72,464,74,464,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Irkutsk\" data-country=\"RU\" data-pin=\"474,63\" data-offset=\"9\" shape=\"poly\" coords=\"493,55,492,56,493,58,495,58,495,59,490,61,491,62,489,63,487,63,484,64,481,64,480,65,481,66,480,67,476,66,472,66,470,66,470,64,464,63,465,61,461,61,459,60,461,59,461,57,463,56,462,55,463,54,467,53,468,54,469,53,471,52,471,51,474,52,476,52,475,51,476,50,476,50,474,49,477,47,478,46,477,45,478,44,478,43,481,43,481,44,480,44,482,44,483,45,482,46,483,46,483,47,483,48,484,48,482,51,483,52,487,51,488,52,493,49,495,50,495,51,498,51,499,53,496,53,495,54,496,55,496,55,493,55\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jakarta\" data-country=\"ID\" data-pin=\"478,160\" data-offset=\"7\" shape=\"poly\" coords=\"487,161,488,163,491,163,491,165,480,163,475,161,477,160,480,160,481,161,484,162,485,161,487,161\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jakarta\" data-country=\"ID\" data-pin=\"478,160\" data-offset=\"7\" shape=\"poly\" coords=\"476,157,476,160,475,159,475,160,474,159,474,160,471,157,466,150,465,150,465,147,459,142,459,141,463,141,464,143,472,149,471,150,473,149,472,151,474,152,475,154,476,154,477,155,476,157\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jayapura\" data-country=\"ID\" data-pin=\"534,154\" data-offset=\"9\" shape=\"poly\" coords=\"525,157,524,157,525,157,523,156,523,155,522,156,522,157,521,155,520,155,522,154,523,155,523,153,521,154,520,152,518,152,519,151,521,151,523,151,524,154,525,156,530,152,535,154,535,165,533,164,534,163,532,164,532,163,531,162,533,162,531,162,532,162,529,158,525,157\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kabul\" data-country=\"AF\" data-pin=\"415,92\" data-offset=\"4.5\" shape=\"poly\" coords=\"405,101,401,100,403,98,401,98,401,95,402,94,401,93,402,91,405,91,410,87,413,88,415,88,418,86,419,87,419,89,422,88,425,88,419,90,419,91,418,93,416,93,417,94,416,95,415,97,414,97,413,98,411,98,410,100,405,101\" />\n";
  $output .= "            <area data-timezone=\"Asia/Jerusalem\" data-country=\"IL\" data-pin=\"359,97\" data-offset=\"2\" shape=\"poly\" coords=\"358,97,359,95,359,95,359,96,358,96,359,97,358,98,359,98,358,101,357,98,358,97\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kamchatka\" data-country=\"RU\" data-pin=\"564,62\" data-offset=\"12\" shape=\"poly\" coords=\"571,54,572,56,570,57,570,58,570,59,567,60,567,62,564,62,564,63,561,65,559,57,562,54,561,54,565,53,570,49,573,49,573,48,573,48,574,46,575,46,572,46,572,47,572,47,571,47,570,46,571,45,570,45,572,44,571,43,572,43,572,42,581,43,583,44,581,45,584,46,589,46,591,47,587,48,584,50,581,49,577,50,577,49,575,50,573,50,572,52,570,53,572,54,571,54\" />\n";
  $output .= "            <area data-timezone=\"Asia/Karachi\" data-country=\"PK\" data-pin=\"412,109\" data-offset=\"5\" shape=\"poly\" coords=\"417,104,416,105,418,107,419,109,418,110,415,109,413,110,412,109,411,109,411,107,403,108,403,106,405,106,406,105,405,105,405,103,403,102,401,100,407,101,410,100,411,98,416,97,416,95,417,94,416,93,418,93,418,92,419,91,419,90,420,89,425,88,427,89,427,90,430,91,426,92,423,92,423,95,426,96,424,97,424,98,420,103,417,104\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kashgar\" data-country=\"CN\" data-pin=\"430,95\" data-offset=\"6\" shape=\"poly\" coords=\"434,99,431,98,431,96,432,96,432,95,431,94,432,93,430,92,430,91,427,90,426,89,424,88,425,88,425,86,423,86,423,85,425,82,427,83,428,82,430,82,434,80,434,79,435,78,434,75,433,75,436,74,438,75,436,76,442,78,438,77,439,78,438,78,438,80,436,82,437,84,438,84,437,88,438,90,437,91,437,92,437,93,439,95,439,97,438,97,439,98,439,99,437,99,435,100,434,99\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kathmandu\" data-country=\"NP\" data-pin=\"442,104\" data-offset=\"5.8\" shape=\"poly\" coords=\"442,103,447,104,447,106,443,106,440,104,439,104,433,102,434,100,436,99,442,102,442,103\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kolkata\" data-country=\"IN\" data-pin=\"447,112\" data-offset=\"5.5\" shape=\"poly\" coords=\"434,128,433,130,433,133,432,133,432,134,432,135,431,135,430,136,429,136,428,135,422,123,421,117,422,115,421,114,422,114,421,114,421,113,421,113,420,115,418,115,415,113,417,112,417,112,415,112,414,111,415,110,414,110,415,110,419,109,416,105,417,103,420,103,424,98,424,97,426,96,423,95,423,92,426,92,430,91,430,92,432,93,431,94,432,95,432,96,431,96,431,98,435,100,433,102,443,106,447,106,447,103,448,103,449,105,453,105,454,105,453,104,454,104,458,101,461,101,460,102,461,102,460,103,462,103,461,104,462,105,460,105,459,106,457,110,456,110,455,113,454,110,453,112,452,111,454,108,450,108,449,106,449,107,447,106,447,107,448,108,447,109,448,110,448,114,448,113,447,114,447,113,445,114,445,115,444,117,442,118,437,122,437,122,434,124,434,128\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"475,19,466,20,471,18,472,18,471,19,473,18,476,19,475,19\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"462,16,464,17,461,17,467,17,466,18,465,18,467,18,459,18,457,17,455,17,462,16\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"459,15,463,16,461,15,462,16,456,17,452,16,459,15\" />\n";
  $output .= "            <area data-timezone=\"Asia/Krasnoyarsk\" data-country=\"RU\" data-pin=\"455,57\" data-offset=\"8\" shape=\"poly\" coords=\"461,61,465,62,463,65,464,66,462,67,458,67,457,66,454,65,449,67,449,66,450,66,448,64,447,64,449,62,448,61,449,60,447,59,448,59,447,58,449,57,448,56,449,54,447,53,448,52,448,51,445,50,441,50,441,49,440,49,443,48,441,46,443,45,442,44,443,43,443,42,440,42,441,41,439,40,439,39,437,38,437,37,436,37,438,36,437,35,432,34,435,32,432,31,434,30,431,29,439,31,437,31,437,32,436,33,437,33,437,33,440,34,438,33,440,33,439,31,439,31,435,29,435,28,434,28,435,28,434,27,445,27,446,27,443,26,446,26,443,26,445,26,446,25,445,25,449,24,457,23,455,23,455,23,466,23,465,23,469,22,469,21,474,20,477,21,474,22,479,22,478,23,485,22,487,23,487,23,490,23,489,24,487,24,490,24,488,25,480,27,477,27,478,27,475,29,485,27,485,27,484,28,485,28,484,29,487,30,487,31,488,31,484,32,482,34,477,34,478,35,478,38,476,38,477,39,477,41,478,41,476,42,481,43,478,43,478,44,477,45,478,46,477,47,475,48,474,49,476,50,476,50,475,51,476,52,475,52,472,51,468,54,468,54,467,53,463,54,462,55,463,56,461,57,461,59,459,59,461,61\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kuala_Lumpur\" data-country=\"MY\" data-pin=\"470,145\" data-offset=\"8\" shape=\"poly\" coords=\"473,146,474,148,473,148,469,145,468,143,467,139,469,140,469,141,470,140,472,141,473,146\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kuching\" data-country=\"MY\" data-pin=\"484,147\" data-offset=\"8\" shape=\"poly\" coords=\"492,142,495,138,495,139,495,138,496,139,497,141,499,141,497,142,498,143,493,143,491,148,487,147,486,148,484,149,483,147,483,147,486,148,485,147,486,145,488,145,490,142,491,143,492,142,492,143,492,142\" />\n";
  $output .= "            <area data-timezone=\"Asia/Kuwait\" data-country=\"KW\" data-pin=\"380,101\" data-offset=\"3\" shape=\"poly\" coords=\"380,100,380,100,380,100\" />\n";
  $output .= "            <area data-timezone=\"Asia/Macau\" data-country=\"MO\" data-pin=\"489,113\" data-offset=\"8\" shape=\"poly\" coords=\"489,113,489,113,489,113\" />\n";
  $output .= "            <area data-timezone=\"Asia/Magadan\" data-country=\"RU\" data-pin=\"551,51\" data-offset=\"12\" shape=\"poly\" coords=\"557,50,557,51,559,51,552,52,552,52,554,51,549,50,548,51,546,51,545,51,545,50,543,49,545,48,544,47,540,47,534,46,533,45,534,43,532,42,534,41,533,40,534,39,532,38,535,37,535,36,537,36,536,35,538,35,537,34,537,34,535,33,540,30,549,30,550,30,548,31,554,32,564,32,566,32,566,34,568,34,568,35,571,34,571,35,571,36,564,36,563,37,565,38,564,39,565,40,565,40,572,42,572,43,571,43,572,44,570,45,571,45,570,46,571,47,567,49,567,48,566,48,567,47,562,47,557,50\" />\n";
  $output .= "            <area data-timezone=\"Asia/Makassar\" data-country=\"ID\" data-pin=\"499,159\" data-offset=\"8\" shape=\"poly\" coords=\"494,154,493,156,491,157,491,155,493,152,492,150,491,150,492,149,490,149,492,146,493,143,496,143,496,144,495,144,496,144,495,144,497,146,496,147,498,148,496,148,496,151,494,153,494,154\" />\n";
  $output .= "            <area data-timezone=\"Asia/Makassar\" data-country=\"ID\" data-pin=\"499,159\" data-offset=\"8\" shape=\"poly\" coords=\"504,155,504,156,505,157,503,158,501,156,502,154,500,155,501,159,499,159,499,157,499,156,498,156,498,155,499,152,500,151,500,149,502,148,507,149,509,147,507,149,501,149,500,150,500,151,501,152,503,151,506,151,506,152,502,153,504,155\" />\n";
  $output .= "            <area data-timezone=\"Asia/Manila\" data-country=\"PH\" data-pin=\"502,126\" data-offset=\"8\" shape=\"poly\" coords=\"507,140,507,139,507,138,506,137,506,138,504,137,504,138,503,138,504,137,506,135,506,136,506,137,508,136,508,135,509,135,509,134,510,134,511,138,510,140,509,138,509,141,509,140,507,140\" />\n";
  $output .= "            <area data-timezone=\"Asia/Manila\" data-country=\"PH\" data-pin=\"502,126\" data-offset=\"8\" shape=\"poly\" coords=\"505,126,505,127,506,126,507,127,506,127,507,128,507,129,504,127,504,128,503,127,501,127,502,126,501,125,501,126,500,125,500,123,501,123,501,119,504,119,504,122,502,124,503,127,505,126\" />\n";
  $output .= "            <area data-timezone=\"Asia/Muscat\" data-country=\"OM\" data-pin=\"398,111\" data-offset=\"4\" shape=\"poly\" coords=\"394,120,392,120,392,122,389,122,387,118,392,117,393,113,392,112,392,110,393,110,393,108,395,110,398,111,400,113,398,116,396,116,396,118,395,119,394,120\" />\n";
  $output .= "            <area data-timezone=\"Asia/Nicosia\" data-country=\"CY\" data-pin=\"356,91\" data-offset=\"2\" shape=\"poly\" coords=\"355,92,354,92,358,90,357,91,357,92,355,92\" />\n";
  $output .= "            <area data-timezone=\"Asia/Novokuznetsk\" data-country=\"RU\" data-pin=\"445,60\" data-offset=\"7\" shape=\"poly\" coords=\"448,55,449,57,447,58,448,59,447,59,449,59,449,60,448,61,449,62,447,63,445,62,444,61,445,61,442,59,441,57,448,55\" />\n";
  $output .= "            <area data-timezone=\"Asia/Novosibirsk\" data-country=\"RU\" data-pin=\"438,58\" data-offset=\"7\" shape=\"poly\" coords=\"441,57,442,59,441,60,439,60,437,61,435,59,430,61,427,60,428,59,426,60,426,59,425,57,427,56,426,56,427,55,425,52,428,51,429,49,437,49,439,48,441,49,441,50,445,50,448,52,447,52,449,54,448,55,441,57\" />\n";
  $output .= "            <area data-timezone=\"Asia/Omsk\" data-country=\"RU\" data-pin=\"422,58\" data-offset=\"7\" shape=\"poly\" coords=\"425,52,427,55,426,56,427,56,425,57,426,59,426,60,422,61,423,60,419,60,418,58,417,58,418,56,419,55,417,54,418,52,419,53,421,53,425,52\" />\n";
  $output .= "            <area data-timezone=\"Asia/Omsk\" data-country=\"RU\" data-pin=\"422,58\" data-offset=\"7\" shape=\"poly\" coords=\"447,63,446,64,448,64,450,66,449,66,450,67,446,68,445,67,442,67,439,65,436,65,434,64,433,65,430,61,435,59,437,61,439,60,442,59,445,61,444,61,445,62,447,63\" />\n";
  $output .= "            <area data-timezone=\"Asia/Phnom_Penh\" data-country=\"KH\" data-pin=\"475,131\" data-offset=\"7\" shape=\"poly\" coords=\"476,131,477,132,473,133,473,131,472,132,471,127,473,126,477,127,477,126,479,126,479,129,476,131\" />\n";
  $output .= "            <area data-timezone=\"Asia/Pontianak\" data-country=\"ID\" data-pin=\"482,150\" data-offset=\"7\" shape=\"poly\" coords=\"493,152,491,156,488,155,486,156,486,155,484,155,483,152,482,151,482,147,483,147,484,149,486,148,487,147,490,148,490,149,492,149,491,150,492,150,493,152\" />\n";
  $output .= "            <area data-timezone=\"Asia/Pyongyang\" data-country=\"KP\" data-pin=\"510,85\" data-offset=\"9\" shape=\"poly\" coords=\"512,86,509,87,508,86,509,86,509,85,509,84,508,84,507,83,510,82,511,80,514,81,513,80,515,80,517,78,518,80,516,80,516,82,512,84,514,86,512,86\" />\n";
  $output .= "            <area data-timezone=\"Asia/Qatar\" data-country=\"QA\" data-pin=\"386,108\" data-offset=\"3\" shape=\"poly\" coords=\"386,108,385,109,385,108,385,106,386,108\" />\n";
  $output .= "            <area data-timezone=\"Asia/Qyzylorda\" data-country=\"KZ\" data-pin=\"409,75\" data-offset=\"6\" shape=\"poly\" coords=\"413,63,413,65,414,66,410,68,408,68,407,70,404,68,405,67,404,65,400,64,402,63,401,62,402,62,404,62,402,61,403,61,402,61,402,60,410,59,411,61,410,63,413,63\" />\n";
  $output .= "            <area data-timezone=\"Asia/Qyzylorda\" data-country=\"KZ\" data-pin=\"409,75\" data-offset=\"6\" shape=\"poly\" coords=\"410,78,409,77,403,77,401,76,401,74,402,72,400,72,400,73,401,73,399,73,399,74,398,74,402,70,409,73,412,73,412,76,413,77,413,78,410,79,410,78\" />\n";
  $output .= "            <area data-timezone=\"Asia/Rangoon\" data-country=\"MM\" data-pin=\"300,150\" data-offset=\"6.5\" shape=\"poly\" coords=\"464,125,465,127,466,130,464,133,465,129,463,127,463,122,462,123,461,121,461,122,460,122,459,124,459,123,458,124,458,123,458,123,458,122,457,123,458,121,457,118,457,119,456,118,457,118,456,118,457,118,456,117,455,116,455,116,454,115,455,113,456,110,457,110,459,106,461,104,462,105,461,104,463,102,464,104,465,106,465,107,463,109,463,110,465,110,465,111,466,112,465,113,467,113,467,114,469,114,467,116,463,117,463,119,462,119,465,123,464,125\" />\n";
  $output .= "            <area data-timezone=\"Asia/Riyadh\" data-country=\"SA\" data-pin=\"378,109\" data-offset=\"3\" shape=\"poly\" coords=\"372,121,371,123,368,117,365,115,364,110,362,109,359,103,358,103,358,101,360,101,363,99,362,98,365,96,367,97,375,101,381,102,381,104,384,106,383,107,385,109,386,109,386,110,388,112,392,112,393,113,392,117,381,120,377,124,377,122,374,121,372,121,372,121\" />\n";
  $output .= "            <area data-timezone=\"Asia/Sakhalin\" data-country=\"RU\" data-pin=\"538,72\" data-offset=\"11\" shape=\"poly\" coords=\"540,67,541,69,540,68,538,68,538,70,539,73,539,73,538,72,537,74,536,69,537,65,536,63,536,61,538,60,537,60,538,60,539,62,538,64,540,67\" />\n";
  $output .= "            <area data-timezone=\"Asia/Samarkand\" data-country=\"UZ\" data-pin=\"411,84\" data-offset=\"5\" shape=\"poly\" coords=\"414,85,414,86,413,88,411,88,411,87,404,83,403,81,401,81,400,80,398,79,397,79,398,80,397,79,395,80,395,81,393,81,393,75,398,74,397,76,399,77,401,76,403,78,409,77,410,78,410,80,411,80,411,81,411,83,412,83,412,85,414,85\" />\n";
  $output .= "            <area data-timezone=\"Asia/Seoul\" data-country=\"KR\" data-pin=\"512,87\" data-offset=\"9\" shape=\"poly\" coords=\"515,91,513,92,512,93,512,92,511,93,510,92,512,90,510,89,512,88,511,87,512,86,514,86,515,87,516,90,515,91\" />\n";
  $output .= "            <area data-timezone=\"Asia/Shanghai\" data-country=\"CN\" data-pin=\"502,98\" data-offset=\"8\" shape=\"poly\" coords=\"481,100,481,99,484,98,482,96,484,95,482,95,485,94,484,92,485,87,484,86,485,84,487,83,490,82,490,81,491,80,489,80,487,79,485,78,487,77,486,76,486,75,487,75,489,75,497,72,499,73,500,71,497,70,493,71,493,70,495,67,496,67,499,66,499,66,501,63,500,62,501,61,507,61,504,63,505,64,509,64,510,65,508,70,507,69,504,71,506,72,505,72,505,74,503,74,504,75,504,76,505,76,506,78,507,79,508,78,509,82,502,85,503,84,502,84,502,84,504,83,502,82,498,85,496,85,496,86,497,86,498,86,498,88,499,88,501,87,505,88,504,89,500,90,499,92,500,93,501,96,503,97,500,97,503,98,500,100,504,100,502,101,503,101,502,101,503,103,501,103,500,106,499,105,500,106,499,108,499,107,498,109,497,109,496,110,494,111,494,112,491,112,491,113,490,113,489,112,489,113,489,113,487,112,487,111,486,111,487,109,486,108,485,108,485,106,483,107,482,106,482,105,481,105,482,104,482,102,481,100\" />\n";
  $output .= "            <area data-timezone=\"Asia/Singapore\" data-country=\"SG\" data-pin=\"473,148\" data-offset=\"8\" shape=\"poly\" coords=\"473,148,473,148,473,148\" />\n";
  $output .= "            <area data-timezone=\"Asia/Taipei\" data-country=\"TW\" data-pin=\"503,108\" data-offset=\"8\" shape=\"poly\" coords=\"500,112,500,111,501,109,503,108,503,108,501,113,500,112\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tashkent\" data-country=\"UZ\" data-pin=\"416,81\" data-offset=\"5\" shape=\"poly\" coords=\"414,83,415,83,414,84,412,84,411,81,414,82,418,80,417,81,419,81,420,81,422,82,420,83,418,83,417,82,414,83\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tbilisi\" data-country=\"GE\" data-pin=\"375,80\" data-offset=\"4\" shape=\"poly\" coords=\"371,81,369,81,369,79,367,77,371,78,373,79,375,79,378,81,372,81,371,81\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tehran\" data-country=\"IR\" data-pin=\"386,91\" data-offset=\"3.5\" shape=\"poly\" coords=\"402,98,403,98,401,100,403,102,405,103,405,105,406,105,405,106,403,106,402,108,396,107,394,105,391,106,390,105,386,103,383,100,383,100,381,99,382,100,381,100,379,98,380,97,379,96,377,95,376,93,377,90,376,90,374,87,374,86,373,84,374,84,377,85,380,84,381,84,380,85,381,86,382,87,386,89,390,89,390,88,394,86,399,87,401,89,402,89,402,92,401,93,402,94,401,95,402,98\" />\n";
  $output .= "            <area data-timezone=\"Asia/Thimphu\" data-country=\"BT\" data-pin=\"449,104\" data-offset=\"6\" shape=\"poly\" coords=\"453,105,453,105,450,105,448,105,450,103,453,103,453,105\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tokyo\" data-country=\"JP\" data-pin=\"533,91\" data-offset=\"9\" shape=\"poly\" coords=\"538,75,541,77,542,76,542,78,543,78,540,79,539,80,536,79,534,79,534,80,535,80,533,81,533,79,534,78,534,78,536,78,536,74,538,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tokyo\" data-country=\"JP\" data-pin=\"533,91\" data-offset=\"9\" shape=\"poly\" coords=\"518,93,519,94,519,95,520,95,519,98,518,98,518,97,518,98,517,98,518,95,517,95,517,96,516,96,516,95,517,95,516,95,518,93\" />\n";
  $output .= "            <area data-timezone=\"Asia/Tokyo\" data-country=\"JP\" data-pin=\"533,91\" data-offset=\"9\" shape=\"poly\" coords=\"524,91,527,91,528,88,529,88,528,88,529,89,531,88,533,85,533,82,534,81,535,82,535,81,536,81,537,84,536,86,535,87,534,90,535,90,534,91,533,92,534,91,533,91,531,92,531,91,530,92,528,92,529,92,528,92,528,93,526,94,525,94,526,92,521,93,520,94,518,93,518,93,521,91,524,91\" />\n";
  $output .= "            <area data-timezone=\"Asia/Ulaanbaatar\" data-country=\"MN\" data-pin=\"478,70\" data-offset=\"8\" shape=\"poly\" coords=\"475,81,468,79,463,79,463,77,464,74,463,72,464,71,463,70,465,69,465,69,462,67,464,66,463,65,465,63,470,64,471,66,472,66,478,66,481,68,483,68,487,67,488,70,486,71,486,73,487,75,486,76,487,77,484,79,479,79,475,81\" />\n";
  $output .= "            <area data-timezone=\"Asia/Urumqi\" data-country=\"CN\" data-pin=\"446,77\" data-offset=\"6\" shape=\"poly\" coords=\"464,103,460,103,461,102,460,101,457,101,453,104,450,103,448,105,448,103,443,103,437,100,437,99,438,98,438,99,439,98,438,97,439,97,439,95,437,93,437,92,437,91,438,90,437,88,438,84,437,84,436,82,438,80,438,78,439,78,438,77,442,78,436,76,438,75,437,74,438,71,443,72,443,69,446,68,447,69,450,70,452,72,451,75,459,76,461,79,462,79,464,82,462,82,461,84,463,84,462,85,466,87,466,87,466,87,465,90,465,92,465,92,465,93,464,94,462,94,463,94,462,95,464,96,465,99,465,101,464,102,465,103,464,103\" />\n";
  $output .= "            <area data-timezone=\"Asia/Vientiane\" data-country=\"LA\" data-pin=\"471,120\" data-offset=\"7\" shape=\"poly\" coords=\"477,126,477,127,475,126,476,124,473,119,472,119,471,120,470,120,468,121,469,117,468,117,468,116,467,116,469,114,470,115,469,113,471,114,472,115,474,116,474,116,475,117,473,118,475,119,478,123,479,123,479,126,477,126\" />\n";
  $output .= "            <area data-timezone=\"Asia/Vladivostok\" data-country=\"RU\" data-pin=\"520,78\" data-offset=\"11\" shape=\"poly\" coords=\"519,75,520,74,522,75,525,71,525,70,518,71,518,69,519,68,519,67,518,65,519,64,522,63,522,62,524,63,525,61,524,61,520,61,519,61,519,60,517,60,522,58,518,57,519,56,520,55,518,55,520,54,519,53,520,53,521,52,520,51,520,51,522,49,522,47,523,47,525,48,526,46,520,44,521,43,521,42,519,40,517,40,522,37,522,36,520,35,523,34,522,33,522,33,520,33,520,31,522,30,522,30,521,30,523,31,533,31,532,30,534,30,532,30,536,29,535,29,546,29,539,30,535,33,537,34,537,34,538,35,536,35,537,36,535,36,535,37,532,38,534,39,533,40,534,41,532,42,534,42,533,43,534,44,533,44,533,45,537,47,544,47,545,48,543,49,545,50,545,51,537,51,525,59,528,59,528,60,530,59,529,61,531,60,531,61,531,60,533,60,536,61,535,61,536,63,534,65,534,68,529,74,523,79,522,79,520,78,518,80,517,79,519,78,518,75,519,75\" />\n";
  $output .= "            <area data-timezone=\"Asia/Vladivostok\" data-country=\"RU\" data-pin=\"520,78\" data-offset=\"11\" shape=\"poly\" coords=\"532,23,534,24,536,23,536,23,542,24,539,25,533,25,532,26,528,25,529,23,532,23\" />\n";
  $output .= "            <area data-timezone=\"Asia/Yakutsk\" data-country=\"RU\" data-pin=\"516,47\" data-offset=\"10\" shape=\"poly\" coords=\"518,57,522,58,518,60,519,60,519,61,524,61,525,61,524,63,522,62,522,63,519,64,518,66,519,66,519,68,518,68,513,67,510,62,506,61,501,61,500,62,501,62,501,63,499,66,499,67,496,67,491,66,485,68,480,67,480,66,481,66,480,65,481,64,484,64,487,63,489,63,491,62,490,61,495,59,495,58,493,58,492,56,496,55,496,55,495,54,496,53,499,53,498,51,495,51,495,50,493,49,488,52,487,51,484,52,482,51,484,48,483,48,483,47,483,46,482,46,483,45,482,44,480,44,481,44,481,43,476,42,478,41,477,41,477,39,476,38,478,38,478,35,477,34,482,34,484,32,488,31,487,31,487,30,485,29,484,29,485,28,484,28,485,27,488,27,489,28,493,27,505,29,506,28,506,27,507,27,516,28,515,28,516,29,515,29,516,30,514,30,516,31,515,31,518,32,520,32,520,33,522,33,522,33,523,34,520,35,522,36,522,37,517,40,519,40,521,42,521,43,520,44,526,46,525,48,523,47,522,47,522,49,520,51,520,51,521,52,520,53,519,53,520,54,518,55,520,55,519,56,518,57\" />\n";
  $output .= "            <area data-timezone=\"Asia/Yerevan\" data-country=\"AM\" data-pin=\"374,83\" data-offset=\"4\" shape=\"poly\" coords=\"375,81,377,83,376,83,378,84,378,85,377,85,376,84,373,83,372,81,375,81\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Azores\" data-country=\"PT\" data-pin=\"257,87\" data-offset=\"-1\" shape=\"rect\" coords=\"248,93,258,79\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Bermuda\" data-country=\"BM\" data-pin=\"192,96\" data-offset=\"-4\" shape=\"rect\" coords=\"187,101,197,91\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Canary\" data-country=\"ES\" data-pin=\"274,103\" data-offset=\"0\" shape=\"rect\" coords=\"265,109,283,96\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Cape_Verde\" data-country=\"CV\" data-pin=\"261,125\" data-offset=\"-1\" shape=\"rect\" coords=\"253,130,267,116\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Faroe\" data-country=\"FO\" data-pin=\"289,47\" data-offset=\"0\" shape=\"rect\" coords=\"282,53,295,41\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Madeira\" data-country=\"PT\" data-pin=\"272,96\" data-offset=\"0\" shape=\"rect\" coords=\"266,105,279,90\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Reykjavik\" data-country=\"IS\" data-pin=\"264,43\" data-offset=\"0\" shape=\"rect\" coords=\"261,50,277,34\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/South_Georgia\" data-country=\"GS\" data-pin=\"239,240\" data-offset=\"-2\" shape=\"rect\" coords=\"230,249,256,239\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/St_Helena\" data-country=\"SH\" data-pin=\"291,177\" data-offset=\"0\" shape=\"rect\" coords=\"276,217,291,163\" />\n";
  $output .= "            <area data-timezone=\"Atlantic/Stanley\" data-country=\"FK\" data-pin=\"204,236\" data-offset=\"-3\" shape=\"rect\" coords=\"193,243,209,230\" />\n";
  $output .= "            <area data-timezone=\"Australia/Adelaide\" data-country=\"AU\" data-pin=\"531,208\" data-offset=\"10.5\" shape=\"poly\" coords=\"525,193,535,193,535,213,533,212,533,211,531,209,533,210,532,210,530,209,531,208,530,207,530,209,528,209,529,208,530,206,530,204,530,205,527,208,527,208,525,208,526,208,525,207,523,204,519,202,515,203,515,193,525,193\" />\n";
  $output .= "            <area data-timezone=\"Australia/Brisbane\" data-country=\"AU\" data-pin=\"555,196\" data-offset=\"10\" shape=\"poly\" coords=\"550,187,551,188,551,189,551,189,553,190,555,193,555,195,556,197,554,197,552,199,550,198,548,198,535,198,535,193,530,193,530,178,533,180,535,179,536,175,536,173,537,171,536,171,537,168,539,170,540,174,541,174,542,175,544,181,548,184,549,188,550,188,550,187\" />\n";
  $output .= "            <area data-timezone=\"Australia/Broken_Hill\" data-country=\"AU\" data-pin=\"536,203\" data-offset=\"10.5\" shape=\"poly\" coords=\"535,204,535,202,537,203,536,204,535,204\" />\n";
  $output .= "            <area data-timezone=\"Australia/Currie\" data-country=\"AU\" data-pin=\"540,217\" data-offset=\"11\" shape=\"poly\" coords=\"540,216,540,216,540,216\" />\n";
  $output .= "            <area data-timezone=\"Australia/Darwin\" data-country=\"AU\" data-pin=\"518,171\" data-offset=\"9.5\" shape=\"poly\" coords=\"515,175,516,175,516,174,516,174,517,171,518,171,518,171,518,171,519,170,521,170,521,169,520,169,524,170,525,171,527,170,526,170,527,170,527,171,528,170,528,171,527,172,527,172,526,174,525,175,530,178,530,193,515,193,515,175\" />\n";
  $output .= "            <area data-timezone=\"Australia/Eucla\" data-country=\"AU\" data-pin=\"515,203\" data-offset=\"8.8\" shape=\"poly\" coords=\"515,202,514,203,509,204,509,202,515,202\" />\n";
  $output .= "            <area data-timezone=\"Australia/Hobart\" data-country=\"AU\" data-pin=\"546,221\" data-offset=\"11\" shape=\"poly\" coords=\"547,218,547,222,545,221,545,223,542,222,542,220,543,221,541,218,542,218,545,219,545,218,547,218\" />\n";
  $output .= "            <area data-timezone=\"Australia/Lindeman\" data-country=\"AU\" data-pin=\"548,184\" data-offset=\"10\" shape=\"poly\" coords=\"548,183,548,183,548,183\" />\n";
  $output .= "            <area data-timezone=\"Australia/Lord_Howe\" data-country=\"AU\" data-pin=\"565,203\" data-offset=\"11\" shape=\"poly\" coords=\"565,203,565,203,565,203\" />\n";
  $output .= "            <area data-timezone=\"Australia/Melbourne\" data-country=\"AU\" data-pin=\"542,213\" data-offset=\"11\" shape=\"poly\" coords=\"538,208,539,208,541,210,547,210,547,211,550,213,546,213,544,214,544,215,542,214,541,214,542,214,542,213,539,215,536,214,535,213,535,207,537,207,538,208\" />\n";
  $output .= "            <area data-timezone=\"Australia/Perth\" data-country=\"AU\" data-pin=\"493,203\" data-offset=\"8\" shape=\"poly\" coords=\"509,175,509,175,509,174,510,174,510,173,511,174,512,173,514,175,513,176,514,175,514,176,514,175,515,175,515,202,509,202,509,204,507,205,506,207,500,207,497,209,493,208,492,207,492,206,493,205,493,203,491,199,489,194,490,194,489,193,490,194,490,194,489,191,490,187,490,186,491,187,491,186,495,184,496,185,502,183,504,180,504,179,505,177,506,179,507,178,506,177,508,177,507,177,508,176,507,177,507,176,508,175,509,176,508,175,509,175\" />\n";
  $output .= "            <area data-timezone=\"Australia/Sydney\" data-country=\"AU\" data-pin=\"552,206\" data-offset=\"11\" shape=\"poly\" coords=\"551,209,550,209,550,213,547,211,547,210,541,210,539,208,535,207,535,204,536,204,537,203,535,202,535,198,548,198,550,198,552,199,554,197,556,197,555,202,552,206,551,206,552,206,551,209\" />\n";
  $output .= "            <area data-timezone=\"Europe/Amsterdam\" data-country=\"NL\" data-pin=\"308,63\" data-offset=\"1\" shape=\"poly\" coords=\"310,64,310,65,308,64,306,64,308,64,307,63,308,63,310,63,309,62,309,61,312,61,311,62,312,63,311,63,310,64\" />\n";
  $output .= "            <area data-timezone=\"Europe/Andorra\" data-country=\"AD\" data-pin=\"303,79\" data-offset=\"1\" shape=\"poly\" coords=\"303,79,302,79,303,79\" />\n";
  $output .= "            <area data-timezone=\"Europe/Athens\" data-country=\"GR\" data-pin=\"340,87\" data-offset=\"2\" shape=\"poly\" coords=\"339,87,339,88,338,87,339,89,338,89,337,89,337,88,336,89,335,87,336,86,339,86,335,86,335,85,335,85,333,84,335,82,344,81,344,82,340,82,341,83,340,83,340,83,338,82,339,85,338,84,338,85,338,85,340,86,340,87,339,87\" />\n";
  $output .= "            <area data-timezone=\"Europe/Belgrade\" data-country=\"RS\" data-pin=\"334,75\" data-offset=\"1\" shape=\"poly\" coords=\"336,75,338,76,337,77,338,78,337,79,334,80,333,79,334,78,332,78,333,77,332,76,332,75,332,75,331,74,334,73,336,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Bucharest\" data-country=\"RO\" data-pin=\"344,76\" data-offset=\"2\" shape=\"poly\" coords=\"347,74,349,75,348,77,345,76,340,77,338,77,337,76,338,76,336,75,336,75,334,73,335,73,337,71,339,70,342,70,345,70,347,72,347,74\" />\n";
  $output .= "            <area data-timezone=\"Europe/Berlin\" data-country=\"DE\" data-pin=\"322,63\" data-offset=\"1\" shape=\"poly\" coords=\"312,68,311,68,310,64,312,63,311,62,312,61,314,61,314,60,316,61,315,60,314,59,315,59,314,59,314,58,317,59,317,59,319,59,318,60,321,59,324,60,324,62,324,62,325,65,320,66,323,69,321,70,322,71,313,71,314,68,312,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Budapest\" data-country=\"HU\" data-pin=\"332,71\" data-offset=\"1\" shape=\"poly\" coords=\"335,73,330,74,327,72,328,72,327,71,329,70,331,70,335,69,338,70,335,73\" />\n";
  $output .= "            <area data-timezone=\"Europe/Chisinau\" data-country=\"MD\" data-pin=\"348,72\" data-offset=\"2\" shape=\"poly\" coords=\"350,73,348,73,348,73,347,74,347,72,344,70,346,69,349,70,350,73\" />\n";
  $output .= "            <area data-timezone=\"Europe/Bratislava\" data-country=\"SK\" data-pin=\"329,70\" data-offset=\"1\" shape=\"poly\" coords=\"328,70,328,69,331,68,338,68,337,69,334,69,331,70,328,70\" />\n";
  $output .= "            <area data-timezone=\"Europe/Brussels\" data-country=\"BE\" data-pin=\"307,65\" data-offset=\"1\" shape=\"poly\" coords=\"305,65,304,65,308,64,311,66,309,68,308,66,307,67,305,65\" />\n";
  $output .= "            <area data-timezone=\"Europe/Copenhagen\" data-country=\"DK\" data-pin=\"321,57\" data-offset=\"1\" shape=\"poly\" coords=\"316,58,316,59,314,58,314,58,313,57,314,56,318,54,317,55,318,56,316,58\" />\n";
  $output .= "            <area data-timezone=\"Europe/Dublin\" data-country=\"IE\" data-pin=\"290,61\" data-offset=\"0\" shape=\"poly\" coords=\"289,60,290,60,289,63,284,64,283,64,284,64,283,64,284,62,286,62,283,62,285,61,283,61,284,60,283,60,286,60,286,59,285,59,288,58,288,58,286,59,289,60\" />\n";
  $output .= "            <area data-timezone=\"Europe/Gibraltar\" data-country=\"GI\" data-pin=\"291,90\" data-offset=\"1\" shape=\"poly\" coords=\"291,90,291,90,291,90\" />\n";
  $output .= "            <area data-timezone=\"Europe/Guernsey\" data-country=\"GG\" data-pin=\"296,68\" data-offset=\"0\" shape=\"poly\" coords=\"296,68,296,68,296,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Helsinki\" data-country=\"FI\" data-pin=\"342,50\" data-offset=\"2\" shape=\"poly\" coords=\"344,49,338,50,338,49,336,49,336,47,335,46,336,45,337,45,338,44,342,42,342,41,339,40,340,39,339,38,339,37,334,35,336,35,337,35,342,36,344,33,347,33,349,34,347,36,350,37,348,38,350,40,349,42,351,43,350,44,353,45,346,49,344,49\" />\n";
  $output .= "            <area data-timezone=\"Europe/Isle_of_Man\" data-country=\"IM\" data-pin=\"293,60\" data-offset=\"0\" shape=\"poly\" coords=\"293,59,292,60,293,59\" />\n";
  $output .= "            <area data-timezone=\"Europe/Istanbul\" data-country=\"TR\" data-pin=\"348,82\" data-offset=\"2\" shape=\"poly\" coords=\"361,89,360,90,360,90,360,88,355,90,352,89,349,90,347,89,346,89,347,88,345,88,346,88,345,87,345,87,344,86,344,86,345,86,345,86,345,84,343,84,344,83,345,83,350,82,349,81,352,81,354,80,358,80,361,81,364,82,371,81,373,82,373,83,375,84,373,84,374,86,374,87,375,88,374,88,371,88,365,89,361,89\" />\n";
  $output .= "            <area data-timezone=\"Europe/Jersey\" data-country=\"JE\" data-pin=\"296,68\" data-offset=\"0\" shape=\"poly\" coords=\"297,68,296,68,297,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Kaliningrad\" data-country=\"RU\" data-pin=\"334,59\" data-offset=\"3\" shape=\"poly\" coords=\"335,58,334,58,338,58,338,59,333,59,334,59,333,59,333,58,335,58\" />\n";
  $output .= "            <area data-timezone=\"Europe/Kiev\" data-country=\"UA\" data-pin=\"351,66\" data-offset=\"2\" shape=\"poly\" coords=\"365,70,364,72,362,72,362,71,361,70,358,70,358,71,357,71,359,73,358,73,354,73,353,72,354,73,353,72,349,74,350,75,348,75,347,74,348,73,350,73,349,70,346,69,341,70,338,68,338,67,340,66,339,64,341,63,351,65,352,63,356,63,357,64,357,64,357,65,359,65,359,66,362,66,363,67,367,67,366,68,367,69,366,69,366,70,365,70\" />\n";
  $output .= "            <area data-timezone=\"Europe/Lisbon\" data-country=\"PT\" data-pin=\"285,85\" data-offset=\"0\" shape=\"poly\" coords=\"287,87,287,88,285,88,286,86,285,86,285,85,284,85,286,82,285,80,286,80,286,80,289,80,290,81,288,82,288,84,287,84,288,86,287,87\" />\n";
  $output .= "            <area data-timezone=\"Europe/Ljubljana\" data-country=\"SI\" data-pin=\"324,73\" data-offset=\"1\" shape=\"poly\" coords=\"327,72,328,73,326,73,326,74,323,74,323,74,322,73,323,72,327,72\" />\n";
  $output .= "            <area data-timezone=\"Europe/London\" data-country=\"GB\" data-pin=\"300,64\" data-offset=\"0\" shape=\"poly\" coords=\"296,64,294,64,291,64,293,62,293,62,292,62,293,61,295,61,295,61,295,60,294,59,295,58,291,58,292,58,292,56,291,57,290,58,291,56,292,55,290,55,291,55,290,54,292,54,291,53,292,53,292,52,295,52,293,53,294,54,293,54,297,54,296,56,294,56,296,56,294,56,296,57,298,59,300,60,300,61,299,60,300,61,300,62,303,62,303,63,301,64,302,64,302,65,294,66,294,66,290,67,293,65,295,65,296,64,296,64\" />\n";
  $output .= "            <area data-timezone=\"Europe/Luxembourg\" data-country=\"LU\" data-pin=\"310,67\" data-offset=\"1\" shape=\"poly\" coords=\"310,67,310,67,310,66,311,67,310,67\" />\n";
  $output .= "            <area data-timezone=\"Europe/Madrid\" data-country=\"ES\" data-pin=\"294,83\" data-offset=\"1\" shape=\"poly\" coords=\"299,87,296,89,293,89,290,90,289,89,290,88,289,89,287,87,288,86,287,84,288,84,288,82,290,81,289,80,286,80,286,80,285,80,286,79,285,78,287,77,297,78,299,79,306,79,305,80,301,82,301,82,299,84,300,85,299,87\" />\n";
  $output .= "            <area data-timezone=\"Europe/Malta\" data-country=\"MT\" data-pin=\"324,90\" data-offset=\"1\" shape=\"poly\" coords=\"324,90,324,90,324,90\" />\n";
  $output .= "            <area data-timezone=\"Europe/Mariehamn\" data-country=\"AX\" data-pin=\"333,50\" data-offset=\"2\" shape=\"poly\" coords=\"335,49,335,49,335,49\" />\n";
  $output .= "            <area data-timezone=\"Europe/Minsk\" data-country=\"BY\" data-pin=\"346,60\" data-offset=\"3\" shape=\"poly\" coords=\"350,64,342,63,339,64,339,63,339,63,340,62,339,60,343,60,343,59,345,58,344,58,344,57,347,56,351,57,352,57,351,59,355,61,352,61,353,63,352,63,351,65,350,64\" />\n";
  $output .= "            <area data-timezone=\"Europe/Monaco\" data-country=\"MC\" data-pin=\"312,77\" data-offset=\"1\" shape=\"poly\" coords=\"312,77,312,77,312,77\" />\n";
  $output .= "            <area data-timezone=\"Europe/Moscow\" data-country=\"RU\" data-pin=\"363,57\" data-offset=\"4\" shape=\"poly\" coords=\"391,28,394,28,392,29,393,29,392,29,393,30,392,30,396,32,389,32,390,31,386,30,389,29,388,29,389,29,388,29,391,28\" />\n";
  $output .= "            <area data-timezone=\"Europe/Moscow\" data-country=\"RU\" data-pin=\"363,57\" data-offset=\"4\" shape=\"poly\" coords=\"400,23,413,22,415,22,414,23,402,24,399,25,400,26,398,25,399,26,397,26,398,26,395,27,396,27,394,27,396,27,394,27,395,28,395,28,390,28,393,27,389,27,394,27,392,26,395,26,393,25,395,25,393,25,400,23\" />\n";
  $output .= "            <area data-timezone=\"Europe/Moscow\" data-country=\"RU\" data-pin=\"363,57\" data-offset=\"4\" shape=\"poly\" coords=\"378,75,378,76,380,77,379,78,381,80,380,81,375,79,373,79,371,78,367,78,361,75,364,73,363,72,366,72,364,71,364,71,366,70,367,70,366,69,367,69,366,68,367,67,363,67,362,66,359,66,359,65,357,65,357,64,357,64,356,63,353,63,352,62,355,61,351,59,352,57,347,56,346,54,346,54,346,54,346,52,347,51,347,50,350,50,348,49,348,49,346,49,353,45,350,44,351,43,349,42,350,40,348,38,350,37,348,36,348,35,347,35,353,33,355,34,354,34,354,34,354,34,356,34,355,35,360,35,368,37,369,39,364,40,353,38,356,39,355,40,358,40,357,41,358,42,358,42,362,44,363,43,361,42,368,42,366,41,370,39,374,40,374,38,373,38,374,37,372,36,377,36,378,37,375,38,378,39,379,39,380,37,382,37,381,37,387,36,390,35,391,35,389,35,390,36,389,36,398,35,399,35,399,36,400,36,402,35,400,34,401,34,408,34,408,35,409,36,409,37,410,37,410,37,410,38,399,42,399,47,394,47,392,49,387,49,387,50,383,50,383,51,381,50,381,49,380,48,379,48,377,50,378,50,378,51,379,52,377,53,380,54,378,54,378,55,382,54,386,56,386,57,388,57,388,56,388,56,389,56,389,57,390,57,389,58,389,59,389,60,386,59,383,59,383,60,380,61,381,62,372,63,371,64,372,65,369,65,370,67,370,68,371,69,370,70,372,71,374,70,374,70,378,71,378,71,379,72,377,73,379,73,378,74,379,74,378,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Oslo\" data-country=\"NO\" data-pin=\"318,50\" data-offset=\"1\" shape=\"poly\" coords=\"314,53,311,53,312,53,309,52,309,52,311,52,310,52,311,51,309,51,312,49,310,50,310,49,309,49,310,49,309,49,308,49,309,49,308,48,312,49,313,48,309,48,308,48,309,48,308,48,309,48,308,47,311,47,308,46,311,47,310,46,311,46,312,46,310,46,314,46,312,45,312,45,314,46,313,45,314,45,314,45,319,44,318,44,319,43,317,44,316,44,320,42,319,42,322,41,320,41,321,41,321,41,321,40,322,40,321,40,324,39,322,40,323,39,322,39,323,39,322,39,323,39,322,38,326,38,324,38,326,37,325,37,326,37,325,37,327,36,328,37,327,37,328,37,327,36,330,36,327,36,329,36,329,35,330,35,329,35,330,34,333,35,332,34,333,34,333,34,334,33,333,35,335,34,334,34,335,33,337,34,336,33,337,33,335,33,336,33,339,33,339,33,341,32,340,32,341,32,343,32,342,32,342,33,344,32,344,33,345,33,346,31,348,32,346,32,347,32,346,33,348,32,352,33,348,33,349,33,349,34,352,34,348,35,349,34,346,33,343,34,343,35,342,36,337,35,335,34,333,35,334,35,333,36,331,36,330,37,328,37,326,40,324,40,324,41,323,42,324,43,323,43,320,44,320,47,321,48,320,48,321,49,320,50,319,52,318,51,318,50,317,52,316,51,314,53\" />\n";
  $output .= "            <area data-timezone=\"Europe/Paris\" data-country=\"FR\" data-pin=\"304,69\" data-offset=\"1\" shape=\"poly\" coords=\"307,78,305,78,305,79,304,79,298,78,297,78,298,76,298,74,299,75,298,74,298,73,297,73,297,71,296,71,293,70,292,70,293,70,292,69,295,69,296,69,298,69,297,67,298,68,301,68,300,67,303,66,303,65,304,65,307,67,308,66,309,68,314,68,313,71,312,71,310,73,311,73,312,74,311,75,312,76,313,76,313,77,310,78,307,78\" />\n";
  $output .= "            <area data-timezone=\"Europe/Podgorica\" data-country=\"ME\" data-pin=\"332,79\" data-offset=\"1\" shape=\"poly\" coords=\"332,78,334,78,333,79,332,80,331,79,332,78\" />\n";
  $output .= "            <area data-timezone=\"Europe/Prague\" data-country=\"CZ\" data-pin=\"324,67\" data-offset=\"1\" shape=\"poly\" coords=\"325,68,323,69,321,68,320,66,325,65,327,66,328,66,329,66,331,67,328,69,325,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Riga\" data-country=\"LV\" data-pin=\"340,55\" data-offset=\"2\" shape=\"poly\" coords=\"337,54,340,55,341,55,341,54,342,53,346,54,347,56,344,57,341,56,335,57,335,55,337,54\" />\n";
  $output .= "            <area data-timezone=\"Europe/Rome\" data-country=\"IT\" data-pin=\"321,80\" data-offset=\"1\" shape=\"poly\" coords=\"328,83,328,84,329,85,326,87,327,85,326,83,319,79,317,77,315,76,313,77,313,76,312,76,311,75,312,74,311,74,314,73,315,74,315,72,317,73,317,72,320,72,323,72,322,73,323,74,320,74,321,75,321,76,323,77,325,80,327,80,327,81,331,83,331,84,328,82,328,83\" />\n";
  $output .= "            <area data-timezone=\"Europe/Samara\" data-country=\"RU\" data-pin=\"384,61\" data-offset=\"4\" shape=\"poly\" coords=\"383,59,388,59,387,60,387,62,385,64,381,62,381,62,380,61,383,60,383,59\" />\n";
  $output .= "            <area data-timezone=\"Europe/Samara\" data-country=\"RU\" data-pin=\"384,61\" data-offset=\"4\" shape=\"poly\" coords=\"391,56,389,57,389,56,388,56,388,56,388,57,386,57,385,56,386,55,385,54,386,54,387,53,390,53,391,55,390,56,391,56\" />\n";
  $output .= "            <area data-timezone=\"Europe/San_Marino\" data-country=\"SM\" data-pin=\"321,77\" data-offset=\"1\" shape=\"poly\" coords=\"321,77,321,77,321,77\" />\n";
  $output .= "            <area data-timezone=\"Europe/Sarajevo\" data-country=\"BA\" data-pin=\"331,77\" data-offset=\"1\" shape=\"poly\" coords=\"326,75,332,75,332,76,333,77,332,77,333,77,332,77,331,79,329,78,326,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Simferopol\" data-country=\"RU\" data-pin=\"357,75\" data-offset=\"2\" shape=\"poly\" coords=\"358,73,359,75,361,74,356,76,355,74,355,74,354,74,356,74,356,73,358,73\" />\n";
  $output .= "            <area data-timezone=\"Europe/Skopje\" data-country=\"MK\" data-pin=\"336,80\" data-offset=\"1\" shape=\"poly\" coords=\"337,79,338,80,338,81,335,82,334,81,335,80,337,79\" />\n";
  $output .= "            <area data-timezone=\"Europe/Sofia\" data-country=\"BG\" data-pin=\"339,79\" data-offset=\"2\" shape=\"poly\" coords=\"346,79,346,79,347,80,344,80,344,81,338,81,337,79,338,78,337,77,338,76,338,77,342,77,345,76,348,77,346,79\" />\n";
  $output .= "            <area data-timezone=\"Europe/Stockholm\" data-country=\"SE\" data-pin=\"330,51\" data-offset=\"1\" shape=\"poly\" coords=\"321,56,322,56,320,54,320,53,319,53,319,52,319,52,320,50,321,49,320,48,321,48,320,47,320,45,321,43,324,43,323,42,324,41,324,40,326,40,327,38,327,37,329,36,330,37,330,36,334,36,333,35,339,37,339,38,340,39,339,39,340,40,336,40,337,41,336,41,336,42,335,42,336,43,334,44,330,45,329,45,330,46,329,46,329,46,328,47,329,49,332,50,330,51,331,51,330,52,329,51,329,52,327,52,328,53,327,53,328,53,327,53,328,54,327,56,324,56,324,58,322,58,321,58,322,57,321,56\" />\n";
  $output .= "            <area data-timezone=\"Europe/Tallinn\" data-country=\"EE\" data-pin=\"341,51\" data-offset=\"2\" shape=\"poly\" coords=\"340,51,343,51,347,51,346,52,346,54,344,54,342,53,341,54,341,53,339,52,339,51,340,51\" />\n";
  $output .= "            <area data-timezone=\"Europe/Tirane\" data-country=\"AL\" data-pin=\"333,81\" data-offset=\"1\" shape=\"poly\" coords=\"334,84,332,83,333,81,332,80,333,79,334,80,334,81,335,82,334,84\" />\n";
  $output .= "            <area data-timezone=\"Europe/Uzhgorod\" data-country=\"UA\" data-pin=\"337,69\" data-offset=\"2\" shape=\"poly\" coords=\"338,68,341,70,338,70,337,69,338,68\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vaduz\" data-country=\"LI\" data-pin=\"316,71\" data-offset=\"1\" shape=\"poly\" coords=\"316,71,316,72,316,71\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vatican\" data-country=\"VA\" data-pin=\"321,80\" data-offset=\"1\" shape=\"poly\" coords=\"321,80,321,80,321,80\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vienna\" data-country=\"AT\" data-pin=\"327,70\" data-offset=\"1\" shape=\"poly\" coords=\"316,71,322,71,321,70,325,68,328,69,329,70,327,71,328,72,327,72,324,73,321,72,320,72,317,72,316,71\" />\n";
  $output .= "            <area data-timezone=\"Europe/Vilnius\" data-country=\"LT\" data-pin=\"342,59\" data-offset=\"2\" shape=\"poly\" coords=\"335,57,341,56,345,58,343,59,343,60,339,60,338,59,338,58,335,58,335,57\" />\n";
  $output .= "            <area data-timezone=\"Europe/Volgograd\" data-country=\"RU\" data-pin=\"374,69\" data-offset=\"4\" shape=\"poly\" coords=\"378,69,379,70,381,71,382,72,381,72,382,73,381,74,381,73,381,74,378,74,379,73,377,73,379,72,378,71,378,71,377,71,376,70,374,70,374,70,373,71,370,70,371,69,370,68,370,67,369,65,372,65,371,64,372,63,380,62,385,63,384,64,381,66,381,67,379,66,378,69\" />\n";
  $output .= "            <area data-timezone=\"Europe/Volgograd\" data-country=\"RU\" data-pin=\"374,69\" data-offset=\"4\" shape=\"poly\" coords=\"381,49,381,49,381,50,383,51,383,50,385,50,389,50,389,51,390,52,390,53,386,53,386,54,385,54,386,55,386,57,382,54,378,55,378,54,380,54,377,53,379,52,378,51,378,50,377,50,379,48,381,49\" />\n";
  $output .= "            <area data-timezone=\"Europe/Warsaw\" data-country=\"PL\" data-pin=\"335,63\" data-offset=\"1\" shape=\"poly\" coords=\"339,66,338,67,338,68,336,68,332,68,329,66,328,66,327,66,325,65,324,62,324,62,324,60,330,59,333,59,332,60,338,59,340,62,339,63,340,65,339,66\" />\n";
  $output .= "            <area data-timezone=\"Europe/Zagreb\" data-country=\"HR\" data-pin=\"327,74\" data-offset=\"1\" shape=\"poly\" coords=\"332,75,326,75,329,78,327,77,324,75,323,75,322,74,326,74,326,73,327,72,330,74,332,73,332,75,332,75\" />\n";
  $output .= "            <area data-timezone=\"Europe/Zaporozhye\" data-country=\"UA\" data-pin=\"359,70\" data-offset=\"2\" shape=\"poly\" coords=\"357,71,358,71,358,70,359,70,362,71,361,72,358,73,359,73,357,71\" />\n";
  $output .= "            <area data-timezone=\"Europe/Zurich\" data-country=\"CH\" data-pin=\"314,71\" data-offset=\"1\" shape=\"poly\" coords=\"310,73,310,73,312,71,314,70,316,71,316,72,317,72,317,73,315,73,315,74,314,73,312,74,311,73,310,73\" />\n";
  $output .= "            <area data-timezone=\"Indian/Antananarivo\" data-country=\"MG\" data-pin=\"379,182\" data-offset=\"3\" shape=\"rect\" coords=\"372,192,383,170\" />\n";
  $output .= "            <area data-timezone=\"Indian/Chagos\" data-country=\"IO\" data-pin=\"421,162\" data-offset=\"6\" shape=\"rect\" coords=\"414,167,426,154\" />\n";
  $output .= "            <area data-timezone=\"Indian/Christmas\" data-country=\"CX\" data-pin=\"476,167\" data-offset=\"7\" shape=\"rect\" coords=\"471,173,481,162\" />\n";
  $output .= "            <area data-timezone=\"Indian/Cocos\" data-country=\"CC\" data-pin=\"462,170\" data-offset=\"6.5\" shape=\"rect\" coords=\"456,175,467,165\" />\n";
  $output .= "            <area data-timezone=\"Indian/Comoro\" data-country=\"KM\" data-pin=\"372,169\" data-offset=\"3\" shape=\"rect\" coords=\"367,176,379,164\" />\n";
  $output .= "            <area data-timezone=\"Indian/Kerguelen\" data-country=\"TF\" data-pin=\"417,232\" data-offset=\"5\" shape=\"rect\" coords=\"384,233,429,213\" />\n";
  $output .= "            <area data-timezone=\"Indian/Mahe\" data-country=\"SC\" data-pin=\"392,158\" data-offset=\"4\" shape=\"rect\" coords=\"377,167,394,156\" />\n";
  $output .= "            <area data-timezone=\"Indian/Maldives\" data-country=\"MV\" data-pin=\"423,143\" data-offset=\"5\" shape=\"rect\" coords=\"416,151,428,138\" />\n";
  $output .= "            <area data-timezone=\"Indian/Mauritius\" data-country=\"MU\" data-pin=\"396,184\" data-offset=\"4\" shape=\"rect\" coords=\"394,184,406,167\" />\n";
  $output .= "            <area data-timezone=\"Indian/Mayotte\" data-country=\"YT\" data-pin=\"375,171\" data-offset=\"3\" shape=\"rect\" coords=\"370,177,380,166\" />\n";
  $output .= "            <area data-timezone=\"Indian/Reunion\" data-country=\"RE\" data-pin=\"392,185\" data-offset=\"4\" shape=\"rect\" coords=\"387,191,398,180\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Apia\" data-country=\"WS\" data-pin=\"14,173\" data-offset=\"14\" shape=\"rect\" coords=\"7,178,19,167\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Auckland\" data-country=\"NZ\" data-pin=\"591,211\" data-offset=\"13\" shape=\"poly\" coords=\"582,228,577,226,581,223,585,221,588,218,589,219,591,218,590,219,591,219,590,220,588,222,589,223,586,224,585,226,582,228\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Auckland\" data-country=\"NZ\" data-pin=\"591,211\" data-offset=\"13\" shape=\"poly\" coords=\"594,218,592,219,591,219,592,217,590,215,591,215,591,213,590,211,591,210,590,211,588,207,591,209,591,212,593,212,592,211,594,213,598,213,597,215,595,215,594,218\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Chatham\" data-country=\"NZ\" data-pin=\"6,223\" data-offset=\"13.8\" shape=\"rect\" coords=\"0,229,12,218\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Chuuk\" data-country=\"FM\" data-pin=\"553,138\" data-offset=\"10\" shape=\"rect\" coords=\"530,146,556,128\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Easter\" data-country=\"CL\" data-pin=\"118,195\" data-offset=\"-5\" shape=\"rect\" coords=\"113,200,123,190\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Enderbury\" data-country=\"KI\" data-pin=\"15,155\" data-offset=\"13\" shape=\"rect\" coords=\"4,163,20,150\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Fakaofo\" data-country=\"TK\" data-pin=\"15,166\" data-offset=\"13\" shape=\"rect\" coords=\"7,171,20,159\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Efate\" data-country=\"VU\" data-pin=\"581,179\" data-offset=\"11\" shape=\"rect\" coords=\"573,184,589,172\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Fiji\" data-country=\"FJ\" data-pin=\"597,180\" data-offset=\"13\" shape=\"poly\" coords=\"2,179,2,179,2,179\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Funafuti\" data-country=\"TV\" data-pin=\"599,164\" data-offset=\"12\" shape=\"rect\" coords=\"588,171,605,154\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Galapagos\" data-country=\"EC\" data-pin=\"151,152\" data-offset=\"-6\" shape=\"rect\" coords=\"142,157,156,142\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Gambier\" data-country=\"PF\" data-pin=\"75,189\" data-offset=\"-9\" shape=\"rect\" coords=\"67,194,80,180\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Kwajalein\" data-country=\"MH\" data-pin=\"579,135\" data-offset=\"12\" shape=\"rect\" coords=\"573,140,585,129\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Guadalcanal\" data-country=\"SB\" data-pin=\"567,166\" data-offset=\"11\" shape=\"rect\" coords=\"559,170,581,158\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Guam\" data-country=\"GU\" data-pin=\"541,128\" data-offset=\"10\" shape=\"rect\" coords=\"536,133,547,122\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Honolulu\" data-country=\"US\" data-pin=\"37,114\" data-offset=\"-10\" shape=\"rect\" coords=\"10,118,42,107\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Johnston\" data-country=\"US\" data-pin=\"300,150\" data-offset=\"-10\" shape=\"rect\" coords=\"12,127,22,117\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Kiritimati\" data-country=\"KI\" data-pin=\"38,147\" data-offset=\"14\" shape=\"rect\" coords=\"32,169,50,142\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Kosrae\" data-country=\"FM\" data-pin=\"572,141\" data-offset=\"11\" shape=\"rect\" coords=\"567,146,577,136\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Majuro\" data-country=\"MH\" data-pin=\"585,138\" data-offset=\"12\" shape=\"rect\" coords=\"568,142,587,126\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Midway\" data-country=\"UM\" data-pin=\"4,103\" data-offset=\"-11\" shape=\"rect\" coords=\"-2,108,9,98\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Marquesas\" data-country=\"PF\" data-pin=\"68,165\" data-offset=\"-9.5\" shape=\"rect\" coords=\"60,173,74,158\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Nauru\" data-country=\"NR\" data-pin=\"578,151\" data-offset=\"12\" shape=\"rect\" coords=\"573,156,583,146\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Niue\" data-country=\"NU\" data-pin=\"17,182\" data-offset=\"-11\" shape=\"rect\" coords=\"12,187,22,177\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Norfolk\" data-country=\"NF\" data-pin=\"580,198\" data-offset=\"11.5\" shape=\"rect\" coords=\"575,204,585,193\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Noumea\" data-country=\"NC\" data-pin=\"577,187\" data-offset=\"11\" shape=\"rect\" coords=\"564,193,587,177\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Pago_Pago\" data-country=\"AS\" data-pin=\"16,174\" data-offset=\"-11\" shape=\"rect\" coords=\"10,179,23,163\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Palau\" data-country=\"PW\" data-pin=\"524,138\" data-offset=\"9\" shape=\"rect\" coords=\"514,150,530,132\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Pitcairn\" data-country=\"PN\" data-pin=\"83,192\" data-offset=\"-8\" shape=\"rect\" coords=\"82,197,92,185\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Pohnpei\" data-country=\"FM\" data-pin=\"564,138\" data-offset=\"11\" shape=\"rect\" coords=\"557,145,573,133\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Port_Moresby\" data-country=\"PG\" data-pin=\"545,166\" data-offset=\"10\" shape=\"rect\" coords=\"537,169,566,151\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Rarotonga\" data-country=\"CK\" data-pin=\"34,185\" data-offset=\"-10\" shape=\"rect\" coords=\"24,187,38,165\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Saipan\" data-country=\"MP\" data-pin=\"543,125\" data-offset=\"10\" shape=\"rect\" coords=\"536,126,548,116\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Tahiti\" data-country=\"PF\" data-pin=\"51,179\" data-offset=\"-10\" shape=\"rect\" coords=\"42,196,73,173\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Tarawa\" data-country=\"KI\" data-pin=\"588,148\" data-offset=\"12\" shape=\"rect\" coords=\"583,154,595,144\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Tongatapu\" data-country=\"TO\" data-pin=\"8,185\" data-offset=\"13\" shape=\"rect\" coords=\"1,187,15,176\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Wake\" data-country=\"UM\" data-pin=\"578,118\" data-offset=\"12\" shape=\"rect\" coords=\"573,123,583,113\" />\n";
  $output .= "            <area data-timezone=\"Pacific/Wallis\" data-country=\"WF\" data-pin=\"6,172\" data-offset=\"12\" shape=\"rect\" coords=\"-2,179,11,167\" />\n";
  $output .= "          </map>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile country selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Country\" id=\"profile_edit_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile country") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile timezone selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_timezone\" class=\"control-label col-xs-2\">" . __ ( "Time zone") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"TimeZone\" id=\"profile_edit_timezone\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile time zone") . "\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"Africa/Abidjan\">Africa/Abidjan</option>\n";
  $output .= "            <option value=\"Africa/Accra\">Africa/Accra</option>\n";
  $output .= "            <option value=\"Africa/Addis_Ababa\">Africa/Addis Ababa</option>\n";
  $output .= "            <option value=\"Africa/Algiers\">Africa/Algiers</option>\n";
  $output .= "            <option value=\"Africa/Asmara\">Africa/Asmara</option>\n";
  $output .= "            <option value=\"Africa/Bamako\">Africa/Bamako</option>\n";
  $output .= "            <option value=\"Africa/Bangui\">Africa/Bangui</option>\n";
  $output .= "            <option value=\"Africa/Banjul\">Africa/Banjul</option>\n";
  $output .= "            <option value=\"Africa/Bissau\">Africa/Bissau</option>\n";
  $output .= "            <option value=\"Africa/Blantyre\">Africa/Blantyre</option>\n";
  $output .= "            <option value=\"Africa/Brazzaville\">Africa/Brazzaville</option>\n";
  $output .= "            <option value=\"Africa/Bujumbura\">Africa/Bujumbura</option>\n";
  $output .= "            <option value=\"Africa/Cairo\">Africa/Cairo</option>\n";
  $output .= "            <option value=\"Africa/Casablanca\">Africa/Casablanca</option>\n";
  $output .= "            <option value=\"Africa/Ceuta\">Africa/Ceuta</option>\n";
  $output .= "            <option value=\"Africa/Conakry\">Africa/Conakry</option>\n";
  $output .= "            <option value=\"Africa/Dakar\">Africa/Dakar</option>\n";
  $output .= "            <option value=\"Africa/Dar_es_Salaam\">Africa/Dar es Salaam</option>\n";
  $output .= "            <option value=\"Africa/Djibouti\">Africa/Djibouti</option>\n";
  $output .= "            <option value=\"Africa/Douala\">Africa/Douala</option>\n";
  $output .= "            <option value=\"Africa/El_Aaiun\">Africa/El Aaiun</option>\n";
  $output .= "            <option value=\"Africa/Freetown\">Africa/Freetown</option>\n";
  $output .= "            <option value=\"Africa/Gaborone\">Africa/Gaborone</option>\n";
  $output .= "            <option value=\"Africa/Harare\">Africa/Harare</option>\n";
  $output .= "            <option value=\"Africa/Johannesburg\">Africa/Johannesburg</option>\n";
  $output .= "            <option value=\"Africa/Kampala\">Africa/Kampala</option>\n";
  $output .= "            <option value=\"Africa/Khartoum\">Africa/Khartoum</option>\n";
  $output .= "            <option value=\"Africa/Kigali\">Africa/Kigali</option>\n";
  $output .= "            <option value=\"Africa/Kinshasa\">Africa/Kinshasa</option>\n";
  $output .= "            <option value=\"Africa/Lagos\">Africa/Lagos</option>\n";
  $output .= "            <option value=\"Africa/Libreville\">Africa/Libreville</option>\n";
  $output .= "            <option value=\"Africa/Lome\">Africa/Lome</option>\n";
  $output .= "            <option value=\"Africa/Luanda\">Africa/Luanda</option>\n";
  $output .= "            <option value=\"Africa/Lubumbashi\">Africa/Lubumbashi</option>\n";
  $output .= "            <option value=\"Africa/Lusaka\">Africa/Lusaka</option>\n";
  $output .= "            <option value=\"Africa/Malabo\">Africa/Malabo</option>\n";
  $output .= "            <option value=\"Africa/Maputo\">Africa/Maputo</option>\n";
  $output .= "            <option value=\"Africa/Maseru\">Africa/Maseru</option>\n";
  $output .= "            <option value=\"Africa/Mbabane\">Africa/Mbabane</option>\n";
  $output .= "            <option value=\"Africa/Mogadishu\">Africa/Mogadishu</option>\n";
  $output .= "            <option value=\"Africa/Monrovia\">Africa/Monrovia</option>\n";
  $output .= "            <option value=\"Africa/Nairobi\">Africa/Nairobi</option>\n";
  $output .= "            <option value=\"Africa/Ndjamena\">Africa/Ndjamena</option>\n";
  $output .= "            <option value=\"Africa/Niamey\">Africa/Niamey</option>\n";
  $output .= "            <option value=\"Africa/Nouakchott\">Africa/Nouakchott</option>\n";
  $output .= "            <option value=\"Africa/Ouagadougou\">Africa/Ouagadougou</option>\n";
  $output .= "            <option value=\"Africa/Porto-Novo\">Africa/Porto-Novo</option>\n";
  $output .= "            <option value=\"Africa/Sao_Tome\">Africa/Sao Tome</option>\n";
  $output .= "            <option value=\"Africa/Tripoli\">Africa/Tripoli</option>\n";
  $output .= "            <option value=\"Africa/Tunis\">Africa/Tunis</option>\n";
  $output .= "            <option value=\"Africa/Windhoek\">Africa/Windhoek</option>\n";
  $output .= "            <option value=\"America/Adak\">America/Adak</option>\n";
  $output .= "            <option value=\"America/Anchorage\">America/Anchorage</option>\n";
  $output .= "            <option value=\"America/Anguilla\">America/Anguilla</option>\n";
  $output .= "            <option value=\"America/Antigua\">America/Antigua</option>\n";
  $output .= "            <option value=\"America/Araguaina\">America/Araguaina</option>\n";
  $output .= "            <option value=\"America/Argentina/Buenos_Aires\">America/Argentina/Buenos Aires</option>\n";
  $output .= "            <option value=\"America/Argentina/Catamarca\">America/Argentina/Catamarca</option>\n";
  $output .= "            <option value=\"America/Argentina/Cordoba\">America/Argentina/Cordoba</option>\n";
  $output .= "            <option value=\"America/Argentina/Jujuy\">America/Argentina/Jujuy</option>\n";
  $output .= "            <option value=\"America/Argentina/La_Rioja\">America/Argentina/La Rioja</option>\n";
  $output .= "            <option value=\"America/Argentina/Mendoza\">America/Argentina/Mendoza</option>\n";
  $output .= "            <option value=\"America/Argentina/Rio_Gallegos\">America/Argentina/Rio Gallegos</option>\n";
  $output .= "            <option value=\"America/Argentina/Salta\">America/Argentina/Salta</option>\n";
  $output .= "            <option value=\"America/Argentina/San_Juan\">America/Argentina/San Juan</option>\n";
  $output .= "            <option value=\"America/Argentina/San_Luis\">America/Argentina/San Luis</option>\n";
  $output .= "            <option value=\"America/Argentina/Tucuman\">America/Argentina/Tucuman</option>\n";
  $output .= "            <option value=\"America/Argentina/Ushuaia\">America/Argentina/Ushuaia</option>\n";
  $output .= "            <option value=\"America/Aruba\">America/Aruba</option>\n";
  $output .= "            <option value=\"America/Asuncion\">America/Asuncion</option>\n";
  $output .= "            <option value=\"America/Atikokan\">America/Atikokan</option>\n";
  $output .= "            <option value=\"America/Bahia_Banderas\">America/Bahia Banderas</option>\n";
  $output .= "            <option value=\"America/Bahia\">America/Bahia</option>\n";
  $output .= "            <option value=\"America/Barbados\">America/Barbados</option>\n";
  $output .= "            <option value=\"America/Belem\">America/Belem</option>\n";
  $output .= "            <option value=\"America/Belize\">America/Belize</option>\n";
  $output .= "            <option value=\"America/Blanc-Sablon\">America/Blanc-Sablon</option>\n";
  $output .= "            <option value=\"America/Boa_Vista\">America/Boa Vista</option>\n";
  $output .= "            <option value=\"America/Bogota\">America/Bogota</option>\n";
  $output .= "            <option value=\"America/Boise\">America/Boise</option>\n";
  $output .= "            <option value=\"America/Cambridge_Bay\">America/Cambridge Bay</option>\n";
  $output .= "            <option value=\"America/Campo_Grande\">America/Campo Grande</option>\n";
  $output .= "            <option value=\"America/Cancun\">America/Cancun</option>\n";
  $output .= "            <option value=\"America/Caracas\">America/Caracas</option>\n";
  $output .= "            <option value=\"America/Cayenne\">America/Cayenne</option>\n";
  $output .= "            <option value=\"America/Cayman\">America/Cayman</option>\n";
  $output .= "            <option value=\"America/Chicago\">America/Chicago</option>\n";
  $output .= "            <option value=\"America/Chihuahua\">America/Chihuahua</option>\n";
  $output .= "            <option value=\"America/Costa_Rica\">America/Costa Rica</option>\n";
  $output .= "            <option value=\"America/Cuiaba\">America/Cuiaba</option>\n";
  $output .= "            <option value=\"America/Curacao\">America/Curacao</option>\n";
  $output .= "            <option value=\"America/Danmarkshavn\">America/Danmarkshavn</option>\n";
  $output .= "            <option value=\"America/Dawson_Creek\">America/Dawson Creek</option>\n";
  $output .= "            <option value=\"America/Dawson\">America/Dawson</option>\n";
  $output .= "            <option value=\"America/Denver\">America/Denver</option>\n";
  $output .= "            <option value=\"America/Detroit\">America/Detroit</option>\n";
  $output .= "            <option value=\"America/Dominica\">America/Dominica</option>\n";
  $output .= "            <option value=\"America/Edmonton\">America/Edmonton</option>\n";
  $output .= "            <option value=\"America/Eirunepe\">America/Eirunepe</option>\n";
  $output .= "            <option value=\"America/El_Salvador\">America/El Salvador</option>\n";
  $output .= "            <option value=\"America/Fortaleza\">America/Fortaleza</option>\n";
  $output .= "            <option value=\"America/Glace_Bay\">America/Glace Bay</option>\n";
  $output .= "            <option value=\"America/Godthab\">America/Godthab</option>\n";
  $output .= "            <option value=\"America/Goose_Bay\">America/Goose Bay</option>\n";
  $output .= "            <option value=\"America/Grand_Turk\">America/Grand Turk</option>\n";
  $output .= "            <option value=\"America/Grenada\">America/Grenada</option>\n";
  $output .= "            <option value=\"America/Guadeloupe\">America/Guadeloupe</option>\n";
  $output .= "            <option value=\"America/Guatemala\">America/Guatemala</option>\n";
  $output .= "            <option value=\"America/Guayaquil\">America/Guayaquil</option>\n";
  $output .= "            <option value=\"America/Guyana\">America/Guyana</option>\n";
  $output .= "            <option value=\"America/Halifax\">America/Halifax</option>\n";
  $output .= "            <option value=\"America/Havana\">America/Havana</option>\n";
  $output .= "            <option value=\"America/Hermosillo\">America/Hermosillo</option>\n";
  $output .= "            <option value=\"America/Indiana/Indianapolis\">America/Indiana/Indianapolis</option>\n";
  $output .= "            <option value=\"America/Indiana/Knox\">America/Indiana/Knox</option>\n";
  $output .= "            <option value=\"America/Indiana/Marengo\">America/Indiana/Marengo</option>\n";
  $output .= "            <option value=\"America/Indiana/Petersburg\">America/Indiana/Petersburg</option>\n";
  $output .= "            <option value=\"America/Indiana/Tell_City\">America/Indiana/Tell City</option>\n";
  $output .= "            <option value=\"America/Indiana/Vevay\">America/Indiana/Vevay</option>\n";
  $output .= "            <option value=\"America/Indiana/Vincennes\">America/Indiana/Vincennes</option>\n";
  $output .= "            <option value=\"America/Indiana/Winamac\">America/Indiana/Winamac</option>\n";
  $output .= "            <option value=\"America/Inuvik\">America/Inuvik</option>\n";
  $output .= "            <option value=\"America/Iqaluit\">America/Iqaluit</option>\n";
  $output .= "            <option value=\"America/Jamaica\">America/Jamaica</option>\n";
  $output .= "            <option value=\"America/Juneau\">America/Juneau</option>\n";
  $output .= "            <option value=\"America/Kentucky/Louisville\">America/Kentucky/Louisville</option>\n";
  $output .= "            <option value=\"America/Kentucky/Monticello\">America/Kentucky/Monticello</option>\n";
  $output .= "            <option value=\"America/La_Paz\">America/La Paz</option>\n";
  $output .= "            <option value=\"America/Lima\">America/Lima</option>\n";
  $output .= "            <option value=\"America/Los_Angeles\">America/Los Angeles</option>\n";
  $output .= "            <option value=\"America/Maceio\">America/Maceio</option>\n";
  $output .= "            <option value=\"America/Managua\">America/Managua</option>\n";
  $output .= "            <option value=\"America/Manaus\">America/Manaus</option>\n";
  $output .= "            <option value=\"America/Marigot\">America/Marigot</option>\n";
  $output .= "            <option value=\"America/Martinique\">America/Martinique</option>\n";
  $output .= "            <option value=\"America/Matamoros\">America/Matamoros</option>\n";
  $output .= "            <option value=\"America/Mazatlan\">America/Mazatlan</option>\n";
  $output .= "            <option value=\"America/Menominee\">America/Menominee</option>\n";
  $output .= "            <option value=\"America/Merida\">America/Merida</option>\n";
  $output .= "            <option value=\"America/Metlakatla\">America/Metlakatla</option>\n";
  $output .= "            <option value=\"America/Mexico_City\">America/Mexico City</option>\n";
  $output .= "            <option value=\"America/Miquelon\">America/Miquelon</option>\n";
  $output .= "            <option value=\"America/Moncton\">America/Moncton</option>\n";
  $output .= "            <option value=\"America/Monterrey\">America/Monterrey</option>\n";
  $output .= "            <option value=\"America/Montevideo\">America/Montevideo</option>\n";
  $output .= "            <option value=\"America/Montreal\">America/Montreal</option>\n";
  $output .= "            <option value=\"America/Montserrat\">America/Montserrat</option>\n";
  $output .= "            <option value=\"America/Nassau\">America/Nassau</option>\n";
  $output .= "            <option value=\"America/New_York\">America/New York</option>\n";
  $output .= "            <option value=\"America/Nipigon\">America/Nipigon</option>\n";
  $output .= "            <option value=\"America/Nome\">America/Nome</option>\n";
  $output .= "            <option value=\"America/Noronha\">America/Noronha</option>\n";
  $output .= "            <option value=\"America/North_Dakota/Beulah\">America/North Dakota/Beulah</option>\n";
  $output .= "            <option value=\"America/North_Dakota/Center\">America/North Dakota/Center</option>\n";
  $output .= "            <option value=\"America/North_Dakota/New_Salem\">America/North Dakota/New Salem</option>\n";
  $output .= "            <option value=\"America/Ojinaga\">America/Ojinaga</option>\n";
  $output .= "            <option value=\"America/Panama\">America/Panama</option>\n";
  $output .= "            <option value=\"America/Pangnirtung\">America/Pangnirtung</option>\n";
  $output .= "            <option value=\"America/Paramaribo\">America/Paramaribo</option>\n";
  $output .= "            <option value=\"America/Phoenix\">America/Phoenix</option>\n";
  $output .= "            <option value=\"America/Port_of_Spain\">America/Port of Spain</option>\n";
  $output .= "            <option value=\"America/Port-au-Prince\">America/Port-au-Prince</option>\n";
  $output .= "            <option value=\"America/Porto_Velho\">America/Porto Velho</option>\n";
  $output .= "            <option value=\"America/Puerto_Rico\">America/Puerto Rico</option>\n";
  $output .= "            <option value=\"America/Rainy_River\">America/Rainy River</option>\n";
  $output .= "            <option value=\"America/Rankin_Inlet\">America/Rankin Inlet</option>\n";
  $output .= "            <option value=\"America/Recife\">America/Recife</option>\n";
  $output .= "            <option value=\"America/Regina\">America/Regina</option>\n";
  $output .= "            <option value=\"America/Resolute\">America/Resolute</option>\n";
  $output .= "            <option value=\"America/Rio_Branco\">America/Rio Branco</option>\n";
  $output .= "            <option value=\"America/Santa_Isabel\">America/Santa Isabel</option>\n";
  $output .= "            <option value=\"America/Santarem\">America/Santarem</option>\n";
  $output .= "            <option value=\"America/Santiago\">America/Santiago</option>\n";
  $output .= "            <option value=\"America/Santo_Domingo\">America/Santo Domingo</option>\n";
  $output .= "            <option value=\"America/Sao_Paulo\">America/Sao Paulo</option>\n";
  $output .= "            <option value=\"America/Scoresbysund\">America/Scoresbysund</option>\n";
  $output .= "            <option value=\"America/Shiprock\">America/Shiprock</option>\n";
  $output .= "            <option value=\"America/Sitka\">America/Sitka</option>\n";
  $output .= "            <option value=\"America/St_Barthelemy\">America/St Barthelemy</option>\n";
  $output .= "            <option value=\"America/St_Johns\">America/St Johns</option>\n";
  $output .= "            <option value=\"America/St_Kitts\">America/St Kitts</option>\n";
  $output .= "            <option value=\"America/St_Lucia\">America/St Lucia</option>\n";
  $output .= "            <option value=\"America/St_Thomas\">America/St Thomas</option>\n";
  $output .= "            <option value=\"America/St_Vincent\">America/St Vincent</option>\n";
  $output .= "            <option value=\"America/Swift_Current\">America/Swift Current</option>\n";
  $output .= "            <option value=\"America/Tegucigalpa\">America/Tegucigalpa</option>\n";
  $output .= "            <option value=\"America/Thule\">America/Thule</option>\n";
  $output .= "            <option value=\"America/Thunder_Bay\">America/Thunder Bay</option>\n";
  $output .= "            <option value=\"America/Tijuana\">America/Tijuana</option>\n";
  $output .= "            <option value=\"America/Toronto\">America/Toronto</option>\n";
  $output .= "            <option value=\"America/Tortola\">America/Tortola</option>\n";
  $output .= "            <option value=\"America/Vancouver\">America/Vancouver</option>\n";
  $output .= "            <option value=\"America/Whitehorse\">America/Whitehorse</option>\n";
  $output .= "            <option value=\"America/Winnipeg\">America/Winnipeg</option>\n";
  $output .= "            <option value=\"America/Yakutat\">America/Yakutat</option>\n";
  $output .= "            <option value=\"America/Yellowknife\">America/Yellowknife</option>\n";
  $output .= "            <option value=\"Antarctica/Casey\">Antarctica/Casey</option>\n";
  $output .= "            <option value=\"Antarctica/Davis\">Antarctica/Davis</option>\n";
  $output .= "            <option value=\"Antarctica/DumontDUrville\">Antarctica/DumontDUrville</option>\n";
  $output .= "            <option value=\"Antarctica/Macquarie\">Antarctica/Macquarie</option>\n";
  $output .= "            <option value=\"Antarctica/Mawson\">Antarctica/Mawson</option>\n";
  $output .= "            <option value=\"Antarctica/McMurdo\">Antarctica/McMurdo</option>\n";
  $output .= "            <option value=\"Antarctica/Palmer\">Antarctica/Palmer</option>\n";
  $output .= "            <option value=\"Antarctica/Rothera\">Antarctica/Rothera</option>\n";
  $output .= "            <option value=\"Antarctica/South_Pole\">Antarctica/South Pole</option>\n";
  $output .= "            <option value=\"Antarctica/Syowa\">Antarctica/Syowa</option>\n";
  $output .= "            <option value=\"Antarctica/Vostok\">Antarctica/Vostok</option>\n";
  $output .= "            <option value=\"Arctic/Longyearbyen\">Arctic/Longyearbyen</option>\n";
  $output .= "            <option value=\"Asia/Aden\">Asia/Aden</option>\n";
  $output .= "            <option value=\"Asia/Almaty\">Asia/Almaty</option>\n";
  $output .= "            <option value=\"Asia/Amman\">Asia/Amman</option>\n";
  $output .= "            <option value=\"Asia/Anadyr\">Asia/Anadyr</option>\n";
  $output .= "            <option value=\"Asia/Aqtau\">Asia/Aqtau</option>\n";
  $output .= "            <option value=\"Asia/Aqtobe\">Asia/Aqtobe</option>\n";
  $output .= "            <option value=\"Asia/Ashgabat\">Asia/Ashgabat</option>\n";
  $output .= "            <option value=\"Asia/Baghdad\">Asia/Baghdad</option>\n";
  $output .= "            <option value=\"Asia/Bahrain\">Asia/Bahrain</option>\n";
  $output .= "            <option value=\"Asia/Baku\">Asia/Baku</option>\n";
  $output .= "            <option value=\"Asia/Bangkok\">Asia/Bangkok</option>\n";
  $output .= "            <option value=\"Asia/Beirut\">Asia/Beirut</option>\n";
  $output .= "            <option value=\"Asia/Bishkek\">Asia/Bishkek</option>\n";
  $output .= "            <option value=\"Asia/Brunei\">Asia/Brunei</option>\n";
  $output .= "            <option value=\"Asia/Choibalsan\">Asia/Choibalsan</option>\n";
  $output .= "            <option value=\"Asia/Chongqing\">Asia/Chongqing</option>\n";
  $output .= "            <option value=\"Asia/Colombo\">Asia/Colombo</option>\n";
  $output .= "            <option value=\"Asia/Damascus\">Asia/Damascus</option>\n";
  $output .= "            <option value=\"Asia/Dhaka\">Asia/Dhaka</option>\n";
  $output .= "            <option value=\"Asia/Dili\">Asia/Dili</option>\n";
  $output .= "            <option value=\"Asia/Dubai\">Asia/Dubai</option>\n";
  $output .= "            <option value=\"Asia/Dushanbe\">Asia/Dushanbe</option>\n";
  $output .= "            <option value=\"Asia/Gaza\">Asia/Gaza</option>\n";
  $output .= "            <option value=\"Asia/Harbin\">Asia/Harbin</option>\n";
  $output .= "            <option value=\"Asia/Ho_Chi_Minh\">Asia/Ho Chi Minh</option>\n";
  $output .= "            <option value=\"Asia/Hong_Kong\">Asia/Hong Kong</option>\n";
  $output .= "            <option value=\"Asia/Hovd\">Asia/Hovd</option>\n";
  $output .= "            <option value=\"Asia/Irkutsk\">Asia/Irkutsk</option>\n";
  $output .= "            <option value=\"Asia/Jakarta\">Asia/Jakarta</option>\n";
  $output .= "            <option value=\"Asia/Jayapura\">Asia/Jayapura</option>\n";
  $output .= "            <option value=\"Asia/Jerusalem\">Asia/Jerusalem</option>\n";
  $output .= "            <option value=\"Asia/Kabul\">Asia/Kabul</option>\n";
  $output .= "            <option value=\"Asia/Kamchatka\">Asia/Kamchatka</option>\n";
  $output .= "            <option value=\"Asia/Karachi\">Asia/Karachi</option>\n";
  $output .= "            <option value=\"Asia/Kashgar\">Asia/Kashgar</option>\n";
  $output .= "            <option value=\"Asia/Kathmandu\">Asia/Kathmandu</option>\n";
  $output .= "            <option value=\"Asia/Kolkata\">Asia/Kolkata</option>\n";
  $output .= "            <option value=\"Asia/Krasnoyarsk\">Asia/Krasnoyarsk</option>\n";
  $output .= "            <option value=\"Asia/Kuala_Lumpur\">Asia/Kuala Lumpur</option>\n";
  $output .= "            <option value=\"Asia/Kuching\">Asia/Kuching</option>\n";
  $output .= "            <option value=\"Asia/Kuwait\">Asia/Kuwait</option>\n";
  $output .= "            <option value=\"Asia/Macau\">Asia/Macau</option>\n";
  $output .= "            <option value=\"Asia/Magadan\">Asia/Magadan</option>\n";
  $output .= "            <option value=\"Asia/Makassar\">Asia/Makassar</option>\n";
  $output .= "            <option value=\"Asia/Manila\">Asia/Manila</option>\n";
  $output .= "            <option value=\"Asia/Muscat\">Asia/Muscat</option>\n";
  $output .= "            <option value=\"Asia/Nicosia\">Asia/Nicosia</option>\n";
  $output .= "            <option value=\"Asia/Novokuznetsk\">Asia/Novokuznetsk</option>\n";
  $output .= "            <option value=\"Asia/Novosibirsk\">Asia/Novosibirsk</option>\n";
  $output .= "            <option value=\"Asia/Omsk\">Asia/Omsk</option>\n";
  $output .= "            <option value=\"Asia/Oral\">Asia/Oral</option>\n";
  $output .= "            <option value=\"Asia/Phnom_Penh\">Asia/Phnom Penh</option>\n";
  $output .= "            <option value=\"Asia/Pontianak\">Asia/Pontianak</option>\n";
  $output .= "            <option value=\"Asia/Pyongyang\">Asia/Pyongyang</option>\n";
  $output .= "            <option value=\"Asia/Qatar\">Asia/Qatar</option>\n";
  $output .= "            <option value=\"Asia/Qyzylorda\">Asia/Qyzylorda</option>\n";
  $output .= "            <option value=\"Asia/Rangoon\">Asia/Rangoon</option>\n";
  $output .= "            <option value=\"Asia/Riyadh\">Asia/Riyadh</option>\n";
  $output .= "            <option value=\"Asia/Sakhalin\">Asia/Sakhalin</option>\n";
  $output .= "            <option value=\"Asia/Samarkand\">Asia/Samarkand</option>\n";
  $output .= "            <option value=\"Asia/Seoul\">Asia/Seoul</option>\n";
  $output .= "            <option value=\"Asia/Shanghai\">Asia/Shanghai</option>\n";
  $output .= "            <option value=\"Asia/Singapore\">Asia/Singapore</option>\n";
  $output .= "            <option value=\"Asia/Taipei\">Asia/Taipei</option>\n";
  $output .= "            <option value=\"Asia/Tashkent\">Asia/Tashkent</option>\n";
  $output .= "            <option value=\"Asia/Tbilisi\">Asia/Tbilisi</option>\n";
  $output .= "            <option value=\"Asia/Tehran\">Asia/Tehran</option>\n";
  $output .= "            <option value=\"Asia/Thimphu\">Asia/Thimphu</option>\n";
  $output .= "            <option value=\"Asia/Tokyo\">Asia/Tokyo</option>\n";
  $output .= "            <option value=\"Asia/Ulaanbaatar\">Asia/Ulaanbaatar</option>\n";
  $output .= "            <option value=\"Asia/Urumqi\">Asia/Urumqi</option>\n";
  $output .= "            <option value=\"Asia/Vientiane\">Asia/Vientiane</option>\n";
  $output .= "            <option value=\"Asia/Vladivostok\">Asia/Vladivostok</option>\n";
  $output .= "            <option value=\"Asia/Yakutsk\">Asia/Yakutsk</option>\n";
  $output .= "            <option value=\"Asia/Yekaterinburg\">Asia/Yekaterinburg</option>\n";
  $output .= "            <option value=\"Asia/Yerevan\">Asia/Yerevan</option>\n";
  $output .= "            <option value=\"Atlantic/Azores\">Atlantic/Azores</option>\n";
  $output .= "            <option value=\"Atlantic/Bermuda\">Atlantic/Bermuda</option>\n";
  $output .= "            <option value=\"Atlantic/Canary\">Atlantic/Canary</option>\n";
  $output .= "            <option value=\"Atlantic/Cape_Verde\">Atlantic/Cape Verde</option>\n";
  $output .= "            <option value=\"Atlantic/Faroe\">Atlantic/Faroe</option>\n";
  $output .= "            <option value=\"Atlantic/Madeira\">Atlantic/Madeira</option>\n";
  $output .= "            <option value=\"Atlantic/Reykjavik\">Atlantic/Reykjavik</option>\n";
  $output .= "            <option value=\"Atlantic/South_Georgia\">Atlantic/South Georgia</option>\n";
  $output .= "            <option value=\"Atlantic/St_Helena\">Atlantic/St Helena</option>\n";
  $output .= "            <option value=\"Atlantic/Stanley\">Atlantic/Stanley</option>\n";
  $output .= "            <option value=\"Australia/Adelaide\">Australia/Adelaide</option>\n";
  $output .= "            <option value=\"Australia/Brisbane\">Australia/Brisbane</option>\n";
  $output .= "            <option value=\"Australia/Broken_Hill\">Australia/Broken Hill</option>\n";
  $output .= "            <option value=\"Australia/Currie\">Australia/Currie</option>\n";
  $output .= "            <option value=\"Australia/Darwin\">Australia/Darwin</option>\n";
  $output .= "            <option value=\"Australia/Eucla\">Australia/Eucla</option>\n";
  $output .= "            <option value=\"Australia/Hobart\">Australia/Hobart</option>\n";
  $output .= "            <option value=\"Australia/Lindeman\">Australia/Lindeman</option>\n";
  $output .= "            <option value=\"Australia/Lord_Howe\">Australia/Lord Howe</option>\n";
  $output .= "            <option value=\"Australia/Melbourne\">Australia/Melbourne</option>\n";
  $output .= "            <option value=\"Australia/Perth\">Australia/Perth</option>\n";
  $output .= "            <option value=\"Australia/Sydney\">Australia/Sydney</option>\n";
  $output .= "            <option value=\"Europe/Amsterdam\">Europe/Amsterdam</option>\n";
  $output .= "            <option value=\"Europe/Andorra\">Europe/Andorra</option>\n";
  $output .= "            <option value=\"Europe/Athens\">Europe/Athens</option>\n";
  $output .= "            <option value=\"Europe/Belgrade\">Europe/Belgrade</option>\n";
  $output .= "            <option value=\"Europe/Berlin\">Europe/Berlin</option>\n";
  $output .= "            <option value=\"Europe/Bratislava\">Europe/Bratislava</option>\n";
  $output .= "            <option value=\"Europe/Brussels\">Europe/Brussels</option>\n";
  $output .= "            <option value=\"Europe/Bucharest\">Europe/Bucharest</option>\n";
  $output .= "            <option value=\"Europe/Budapest\">Europe/Budapest</option>\n";
  $output .= "            <option value=\"Europe/Chisinau\">Europe/Chisinau</option>\n";
  $output .= "            <option value=\"Europe/Copenhagen\">Europe/Copenhagen</option>\n";
  $output .= "            <option value=\"Europe/Dublin\">Europe/Dublin</option>\n";
  $output .= "            <option value=\"Europe/Gibraltar\">Europe/Gibraltar</option>\n";
  $output .= "            <option value=\"Europe/Guernsey\">Europe/Guernsey</option>\n";
  $output .= "            <option value=\"Europe/Helsinki\">Europe/Helsinki</option>\n";
  $output .= "            <option value=\"Europe/Isle_of_Man\">Europe/Isle of Man</option>\n";
  $output .= "            <option value=\"Europe/Istanbul\">Europe/Istanbul</option>\n";
  $output .= "            <option value=\"Europe/Jersey\">Europe/Jersey</option>\n";
  $output .= "            <option value=\"Europe/Kaliningrad\">Europe/Kaliningrad</option>\n";
  $output .= "            <option value=\"Europe/Kiev\">Europe/Kiev</option>\n";
  $output .= "            <option value=\"Europe/Lisbon\">Europe/Lisbon</option>\n";
  $output .= "            <option value=\"Europe/Ljubljana\">Europe/Ljubljana</option>\n";
  $output .= "            <option value=\"Europe/London\">Europe/London</option>\n";
  $output .= "            <option value=\"Europe/Luxembourg\">Europe/Luxembourg</option>\n";
  $output .= "            <option value=\"Europe/Madrid\">Europe/Madrid</option>\n";
  $output .= "            <option value=\"Europe/Malta\">Europe/Malta</option>\n";
  $output .= "            <option value=\"Europe/Mariehamn\">Europe/Mariehamn</option>\n";
  $output .= "            <option value=\"Europe/Minsk\">Europe/Minsk</option>\n";
  $output .= "            <option value=\"Europe/Monaco\">Europe/Monaco</option>\n";
  $output .= "            <option value=\"Europe/Moscow\">Europe/Moscow</option>\n";
  $output .= "            <option value=\"Europe/Oslo\">Europe/Oslo</option>\n";
  $output .= "            <option value=\"Europe/Paris\">Europe/Paris</option>\n";
  $output .= "            <option value=\"Europe/Podgorica\">Europe/Podgorica</option>\n";
  $output .= "            <option value=\"Europe/Prague\">Europe/Prague</option>\n";
  $output .= "            <option value=\"Europe/Riga\">Europe/Riga</option>\n";
  $output .= "            <option value=\"Europe/Rome\">Europe/Rome</option>\n";
  $output .= "            <option value=\"Europe/Samara\">Europe/Samara</option>\n";
  $output .= "            <option value=\"Europe/San_Marino\">Europe/San Marino</option>\n";
  $output .= "            <option value=\"Europe/Sarajevo\">Europe/Sarajevo</option>\n";
  $output .= "            <option value=\"Europe/Simferopol\">Europe/Simferopol</option>\n";
  $output .= "            <option value=\"Europe/Skopje\">Europe/Skopje</option>\n";
  $output .= "            <option value=\"Europe/Sofia\">Europe/Sofia</option>\n";
  $output .= "            <option value=\"Europe/Stockholm\">Europe/Stockholm</option>\n";
  $output .= "            <option value=\"Europe/Tallinn\">Europe/Tallinn</option>\n";
  $output .= "            <option value=\"Europe/Tirane\">Europe/Tirane</option>\n";
  $output .= "            <option value=\"Europe/Uzhgorod\">Europe/Uzhgorod</option>\n";
  $output .= "            <option value=\"Europe/Vaduz\">Europe/Vaduz</option>\n";
  $output .= "            <option value=\"Europe/Vatican\">Europe/Vatican</option>\n";
  $output .= "            <option value=\"Europe/Vienna\">Europe/Vienna</option>\n";
  $output .= "            <option value=\"Europe/Vilnius\">Europe/Vilnius</option>\n";
  $output .= "            <option value=\"Europe/Volgograd\">Europe/Volgograd</option>\n";
  $output .= "            <option value=\"Europe/Warsaw\">Europe/Warsaw</option>\n";
  $output .= "            <option value=\"Europe/Zagreb\">Europe/Zagreb</option>\n";
  $output .= "            <option value=\"Europe/Zaporozhye\">Europe/Zaporozhye</option>\n";
  $output .= "            <option value=\"Europe/Zurich\">Europe/Zurich</option>\n";
  $output .= "            <option value=\"Indian/Antananarivo\">Indian/Antananarivo</option>\n";
  $output .= "            <option value=\"Indian/Chagos\">Indian/Chagos</option>\n";
  $output .= "            <option value=\"Indian/Christmas\">Indian/Christmas</option>\n";
  $output .= "            <option value=\"Indian/Cocos\">Indian/Cocos</option>\n";
  $output .= "            <option value=\"Indian/Comoro\">Indian/Comoro</option>\n";
  $output .= "            <option value=\"Indian/Kerguelen\">Indian/Kerguelen</option>\n";
  $output .= "            <option value=\"Indian/Mahe\">Indian/Mahe</option>\n";
  $output .= "            <option value=\"Indian/Maldives\">Indian/Maldives</option>\n";
  $output .= "            <option value=\"Indian/Mauritius\">Indian/Mauritius</option>\n";
  $output .= "            <option value=\"Indian/Mayotte\">Indian/Mayotte</option>\n";
  $output .= "            <option value=\"Indian/Reunion\">Indian/Reunion</option>\n";
  $output .= "            <option value=\"Pacific/Apia\">Pacific/Apia</option>\n";
  $output .= "            <option value=\"Pacific/Auckland\">Pacific/Auckland</option>\n";
  $output .= "            <option value=\"Pacific/Chatham\">Pacific/Chatham</option>\n";
  $output .= "            <option value=\"Pacific/Chuuk\">Pacific/Chuuk</option>\n";
  $output .= "            <option value=\"Pacific/Easter\">Pacific/Easter</option>\n";
  $output .= "            <option value=\"Pacific/Efate\">Pacific/Efate</option>\n";
  $output .= "            <option value=\"Pacific/Enderbury\">Pacific/Enderbury</option>\n";
  $output .= "            <option value=\"Pacific/Fakaofo\">Pacific/Fakaofo</option>\n";
  $output .= "            <option value=\"Pacific/Fiji\">Pacific/Fiji</option>\n";
  $output .= "            <option value=\"Pacific/Funafuti\">Pacific/Funafuti</option>\n";
  $output .= "            <option value=\"Pacific/Galapagos\">Pacific/Galapagos</option>\n";
  $output .= "            <option value=\"Pacific/Gambier\">Pacific/Gambier</option>\n";
  $output .= "            <option value=\"Pacific/Guadalcanal\">Pacific/Guadalcanal</option>\n";
  $output .= "            <option value=\"Pacific/Guam\">Pacific/Guam</option>\n";
  $output .= "            <option value=\"Pacific/Honolulu\">Pacific/Honolulu</option>\n";
  $output .= "            <option value=\"Pacific/Johnston\">Pacific/Johnston</option>\n";
  $output .= "            <option value=\"Pacific/Kiritimati\">Pacific/Kiritimati</option>\n";
  $output .= "            <option value=\"Pacific/Kosrae\">Pacific/Kosrae</option>\n";
  $output .= "            <option value=\"Pacific/Kwajalein\">Pacific/Kwajalein</option>\n";
  $output .= "            <option value=\"Pacific/Majuro\">Pacific/Majuro</option>\n";
  $output .= "            <option value=\"Pacific/Marquesas\">Pacific/Marquesas</option>\n";
  $output .= "            <option value=\"Pacific/Midway\">Pacific/Midway</option>\n";
  $output .= "            <option value=\"Pacific/Nauru\">Pacific/Nauru</option>\n";
  $output .= "            <option value=\"Pacific/Niue\">Pacific/Niue</option>\n";
  $output .= "            <option value=\"Pacific/Norfolk\">Pacific/Norfolk</option>\n";
  $output .= "            <option value=\"Pacific/Noumea\">Pacific/Noumea</option>\n";
  $output .= "            <option value=\"Pacific/Pago_Pago\">Pacific/Pago Pago</option>\n";
  $output .= "            <option value=\"Pacific/Palau\">Pacific/Palau</option>\n";
  $output .= "            <option value=\"Pacific/Pitcairn\">Pacific/Pitcairn</option>\n";
  $output .= "            <option value=\"Pacific/Pohnpei\">Pacific/Pohnpei</option>\n";
  $output .= "            <option value=\"Pacific/Port_Moresby\">Pacific/Port Moresby</option>\n";
  $output .= "            <option value=\"Pacific/Rarotonga\">Pacific/Rarotonga</option>\n";
  $output .= "            <option value=\"Pacific/Saipan\">Pacific/Saipan</option>\n";
  $output .= "            <option value=\"Pacific/Tahiti\">Pacific/Tahiti</option>\n";
  $output .= "            <option value=\"Pacific/Tarawa\">Pacific/Tarawa</option>\n";
  $output .= "            <option value=\"Pacific/Tongatapu\">Pacific/Tongatapu</option>\n";
  $output .= "            <option value=\"Pacific/Wake\">Pacific/Wake</option>\n";
  $output .= "            <option value=\"Pacific/Wallis\">Pacific/Wallis</option>\n";
  $output .= "            <option value=\"UTC\">UTC</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile area code field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_areacode\" class=\"control-label col-xs-2\">" . __ ( "Area code") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"AreaCode\" class=\"form-control\" id=\"profile_edit_areacode\" placeholder=\"" . __ ( "Profile area code") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile language selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Language\" id=\"profile_edit_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile language") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Gateways panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"profile_edit_tab_gateways\">\n";

  // Add profile non geographic gateway selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_nggw\" class=\"control-label col-xs-2\">" . __ ( "Non geographic gateway") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"NGGW\" id=\"profile_edit_nggw\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway to non geographic calls") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile blocked gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_blocked\" class=\"control-label col-xs-2\">" . __ ( "Blocked gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"blockeds_list\" id=\"profile_edit_blocked\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_blocked_rightAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable all gateways") . "\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_blocked_rightSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable selected gateway(s)") . "\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_blocked_leftSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable selected gateway(s)") . "\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_blocked_leftAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable all gateways") . "\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"Blockeds\" id=\"profile_edit_blocked_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile allowed gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"profile_edit_gateways\" class=\"control-label col-xs-2\">" . __ ( "Allowed gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"gateways_list\" id=\"profile_edit_gateways\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_gateways_rightAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable all gateways") . "\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_gateways_rightSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable selected gateway(s)") . "\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_gateways_leftSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable selected gateway(s)") . "\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_gateways_leftAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable all gateways") . "\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_gateways_move_up\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Priorize selected gateway(s)") . "\"><i class=\"fa fa-angle-up\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"profile_edit_gateways_move_down\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Unpriorize selected gateway(s)") . "\"><i class=\"fa fa-angle-down\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"Gateways\" id=\"profile_edit_gateways_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/profiles\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#profile_edit_country').select2 (\n" .
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
              "$('#profile_edit_language').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/locales', fields: 'Code,Name', formatID: 'Code', formatText: '%Name%'})\n" .
              "});\n" .
              "$('#profile_edit_moh').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/audios', fields: 'ID,Description', formatText: '%Description%'})\n" .
              "});\n" .
              "$('#profile_edit_timezone_image').timezonePicker (\n" .
              "{\n" .
              "  changeHandler: function ( timezoneName, countryName, offset)\n" .
              "                 {\n" .
              "                   if ( $('#profile_edit_country').val () != countryName)\n" .
              "                   {\n" .
              "                     $('#profile_edit_country').val ( countryName).trigger ( 'change');\n" .
              "                   }\n" .
              "                   if ( $('#profile_edit_timezone').val () != timezoneName)\n" .
              "                   {\n" .
              "                     $('#profile_edit_timezone').val ( timezoneName).trigger ( 'change');\n" .
              "                   }\n" .
              "                   $('#profile_edit_offset').val ( offset);\n" .
              "                 }\n" .
              "}).timezonePicker ( 'detectLocation');\n" .
              "$('#profile_edit_timezone').select2 ().on ( 'change', function ( e)\n" .
              "{\n" .
              "  $('#profile_edit_timezone_image').timezonePicker ( 'updateTimezone', $('#profile_edit_timezone').val ());\n" .
              "});\n" .
              "$('#profile_edit_nggw').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/gateways', fields: 'ID,Description,Active', filter: function ( results, fields)\n" .
              "        {\n" .
              "          var result = new Array ();\n" .
              "          for ( var x = 0; x < results.length; x++)\n" .
              "          {\n" .
              "            result.push ( { id: results[x].Active ? results[x].ID : results[x].ID * -1, text: results[x].Description});\n" .
              "          }\n" .
              "          return result;\n" .
              "        }})\n" .
              "}).on ( 'select2:open', function ( e)\n" .
              "{\n" .
              "  $('#profile_edit_nggw').find ( 'option').each ( function ( index, option)\n" .
              "  {\n" .
              "    if ( $(option).val () < 0)\n" .
              "    {\n" .
              "      setTimeout ( function () { $('#select2-profile_edit_nggw-results').find ( 'li.select2-results__option').eq ( index - 1).addClass ( 'select2-inactive');}, 0);\n" .
              "    }\n" .
              "  });\n" .
              "}).on ( 'change', function ( e)\n" .
              "{\n" .
              "  $('#select2-profile_edit_nggw-container').removeClass ( 'select2-inactive');\n" .
              "  if ( $(e.target).val () < 0)\n" .
              "  {\n" .
              "    $('#select2-profile_edit_nggw-container').addClass ( 'select2-inactive');\n" .
              "  }\n" .
              "});\n" .
              "$('#profile_edit_blocked').multiselect (\n" .
              "{\n" .
              "  afterMoveToRight: function ( left, right, options)\n" .
              "                    {\n" .
              "                      $(options).each ( function ()\n" .
              "                      {\n" .
              "                        if ( $('#profile_edit_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').length == 1)\n" .
              "                        {\n" .
              "                          $('#profile_edit_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').detach ().prependTo ( $('#profile_edit_gateways'));\n" .
              "                        }\n" .
              "                        $('#profile_edit_gateways,#profile_edit_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').attr ( 'disabled', 'disabled');\n" .
              "                      });\n" .
              "                    },\n" .
              "  afterMoveToLeft: function ( left, right, options)\n" .
              "                   {\n" .
              "                     $(options).each ( function ()\n" .
              "                     {\n" .
              "                       $('#profile_edit_gateways,#profile_edit_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').removeAttr ( 'disabled');\n" .
              "                     });\n" .
              "                   }\n" .
              "});\n" .
              "$('#profile_edit_gateways').multiselect (\n" .
              "{\n" .
              "  afterMoveToRight: function ( left, right, options)\n" .
              "                    {\n" .
              "                      $(right).find ( 'option').each ( function ()\n" .
              "                      {\n" .
              "                        if ( $(this).attr ( 'disabled'))\n" .
              "                        {\n" .
              "                          $(this).detach ().prependTo ( $(left));\n" .
              "                        }\n" .
              "                      });\n" .
              "                    }\n" .
              "});\n" .
              "$('#profile_edit_areacode').mask ( '0#');\n" .
              "$('#profile_edit_prefix').mask ( '0');\n" .
              "$('#profile_edit_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "var gateways = VoIP.APIsearch ( { path: '/gateways', fields: 'ID,Description,Active', filter: function ( results, fields)\n" .
              "               {\n" .
              "                 var result = new Array ();\n" .
              "                 for ( var x = 0; x < results.length; x++)\n" .
              "                 {\n" .
              "                   result.push ( { id: results[x].ID, text: results[x].Description, active: results[x].Active});\n" .
              "                 }\n" .
              "                 return result;\n" .
              "               }});\n" .
              "if ( typeof gateways === 'object')\n" .
              "{\n" .
              "  for ( var gateway in gateways)\n" .
              "  {\n" .
              "    if ( ! gateways.hasOwnProperty ( gateway))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    if ( gateways[gateway].active)\n" .
              "    {\n" .
              "      $('<option value=\"' + gateways[gateway].id + '\">' + gateways[gateway].text + '</option>').appendTo ( '#profile_edit_gateways');\n" .
              "      $('<option value=\"' + gateways[gateway].id + '\">' + gateways[gateway].text + '</option>').appendTo ( '#profile_edit_blocked');\n" .
              "    } else {\n" .
              "      $('<option value=\"' + gateways[gateway].id + '\" class=\"select2-inactive\">' + gateways[gateway].text + '</option>').appendTo ( '#profile_edit_gateways');\n" .
              "      $('<option value=\"' + gateways[gateway].id + '\" class=\"select2-inactive\">' + gateways[gateway].text + '</option>').appendTo ( '#profile_edit_blocked');\n" .
              "    }\n" .
              "  }\n" .
              "} else {\n" .
              "  new PNotify ( { title: '" . __ ( "Profile edition") . "', text: '" . __ ( "Error requesting gateways!") . "', type: 'error'});\n" .
              "}\n" .
              "$('#profile_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#profile_edit_description').val ( data.Description);\n" .
              "  $('#profile_edit_domain').val ( data.Domain);\n" .
              "  $('#profile_edit_country').val ( data.Country['ISO3166-2']).trigger ( 'change');\n" .
              "  $('#profile_edit_timezone_image').data ( 'origval', data.TimeZone);\n" .
              "  $('#profile_edit_timezone').val ( data.TimeZone).trigger ( 'change');\n" .
              "  $('#profile_edit_offset').val ( data.Offset);\n" .
              "  $('#profile_edit_areacode').val ( data.AreaCode);\n" .
              "  $('#profile_edit_language').val ( data.Language.Code).trigger ( 'change');\n" .
              "  $('#profile_edit_prefix').val ( data.Prefix);\n" .
              "  $('#profile_edit_moh').val ( data.MOH.ID).trigger ( 'change');\n" .
              "  $('#profile_edit_emergency').bootstrapToggle ( data.Emergency ? 'on' : 'off');\n" .
              "  $('#profile_edit_nggw').val ( data.NGGW.ID).trigger ( 'change');\n" .
              "  for ( var id in data.Gateways)\n" .
              "  {\n" .
              "    if ( ! data.Gateways.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    $('#profile_edit_gateways').find ( 'option[value=\"' + data.Gateways[id].ID + '\"]').remove().appendTo ( $('#profile_edit_gateways_to'));\n" .
              "  }\n" .
              "  for ( var id in data.Blockeds)\n" .
              "  {\n" .
              "    if ( ! data.Blockeds.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    $('#profile_edit_blocked').find ( 'option[value=\"' + data.Blockeds[id].ID + '\"]').remove().appendTo ( $('#profile_edit_blocked_to'));\n" .
              "  }\n" .
              "  $('#profile_edit_description').focus ();\n" .
              "}).find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('#profile_edit_timezone_image').data ( 'origval') == $('#profile_edit_timezone').val ())\n" .
              "  {\n" .
              "    $('#profile_edit_timezone_image').timezonePicker ( 'updateTimezone', $('#profile_edit_timezone').val ());\n" .
              "  }\n" .
              "});\n" .
              "$('#profile_edit_timezone').select2 ();\n" .
              "VoIP.rest ( '/profiles/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#profile_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Profile edition") . "', text: '" . __ ( "Error requesting data from profile!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#profile_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/profiles/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Profile edition") . "',\n" .
              "    fail: '" . __ ( "Error changing profile!") . "',\n" .
              "    success: '" . __ ( "Profile changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/profiles', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#profile_edit_form').data ( 'formData');\n" .
              "  delete ( formData.blockeds_list);\n" .
              "  if ( typeof ( formData.Blockeds) == 'string')\n" .
              "  {\n" .
              "    var tmp = formData.Blockeds;\n" .
              "    formData.Blockeds = new Array ();\n" .
              "    formData.Blockeds.push ( tmp);\n" .
              "  }\n" .
              "  delete ( formData.gateways_list);\n" .
              "  if ( typeof ( formData.Gateways) == 'string')\n" .
              "  {\n" .
              "    var tmp = formData.Gateways;\n" .
              "    formData.Gateways = new Array ();\n" .
              "    formData.Gateways.push ( tmp);\n" .
              "  }\n" .
              "  $('#profile_edit_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}
?>
