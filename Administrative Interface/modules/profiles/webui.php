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
 * VoIP Domain profiles module WebUI. This module manage profiles. You can have
 * one or more profiles managed by the system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Profiles
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/profiles", "profiles_search_page", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "profiles_search_page", "profiles_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/profiles/add", "profiles_add_page", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "profiles_add_page", "profiles_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/profiles/:id/clone", "profiles_clone_function", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "profiles_clone_function", "profiles_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/profiles/:id/view", "profiles_view_page", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "profiles_view_page", "profiles_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/profiles/:id/edit", "profiles_edit_page", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "profiles_edit_page", "profiles_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main profiles page.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_search_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Profiles"));
  sys_set_subtitle ( __ ( "Search profiles"));
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
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are you sure you want to remove the profile %s?"), "<span id=\"profile_delete_description\"></span>") . "</p><input type=\"hidden\" id=\"profile_delete_id\" value=\"\"></div>\n";
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
              "    new PNotify ( { title: '" . __ ( "Profile removal") . "', text: '" . __ ( "Profile removald successfully!") . "', type: 'success'});\n" .
              "    $('#profile_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Profile removal") . "', text: '" . __ ( "Error removing profile!") . "', type: 'error'});\n" .
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
  sys_set_subtitle ( __ ( "Add profiles"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Profiles"), "link" => "/profiles"),
    2 => array ( "title" => __ ( "Add"))
  ));
  sys_set_menu_highlight ( "/profiles");

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
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"profile_add_description\" placeholder=\"" . __ ( "Profile description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile domain field
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_add_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Domain\" class=\"form-control\" id=\"profile_add_domain\" placeholder=\"" . __ ( "Profile domain") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile external prefix field
  $output .= "      <div class=\"form-group required\">\n";
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
  $output .= "      <div class=\"form-group required\">\n";
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
  $output .= voipdomain_timezonepicker_map_markup ();
  $output .= "          </map>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile country selector
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_add_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Country\" id=\"profile_add_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile country") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile timezone selector
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_add_timezone\" class=\"control-label col-xs-2\">" . __ ( "Time zone") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"TimeZone\" id=\"profile_add_timezone\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile time zone") . "\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= voipdomain_timezonepicker_select_markup ();
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile area code field
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_add_areacode\" class=\"control-label col-xs-2\">" . __ ( "Area code") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"AreaCode\" class=\"form-control\" id=\"profile_add_areacode\" placeholder=\"" . __ ( "Profile area code") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile language selector
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_add_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Language\" id=\"profile_add_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile language") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Gateways panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"profile_add_tab_gateways\">\n";

  // Add profile non geographic gateway selector
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_add_nggw\" class=\"control-label col-xs-2\">" . __ ( "Non geographic gateway") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"NGGW\" id=\"profile_add_nggw\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway to non-geographic calls") . "\"><option value=\"\"></option></select>\n";
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
  $output .= "      <div class=\"form-group required\">\n";
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
              "  new PNotify ( { title: '" . __ ( "Add profile") . "', text: '" . __ ( "Error retrieving gateways!") . "', type: 'error'});\n" .
              "}\n" .
              "$('#profile_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#profile_add_description'),\n" .
              "    URL: '/profiles',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Add profile") . "',\n" .
              "    fail: '" . __ ( "Error adding profile!") . "',\n" .
              "    success: '" . __ ( "Profile added successfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/profiles', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#profile_add_form').data ( 'formData');\n" .
              "  formData.Emergency = $('#profile_add_emergency').prop ( 'checked');\n" .
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
 * @return void
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
              "  new PNotify ( { title: '" . __ ( "Clone profile") . "', text: '" . __ ( "Error retrieving profile data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the profile view page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_view_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Profiles"));
  sys_set_subtitle ( __ ( "View profiles"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Profiles"), "link" => "/profiles"),
    2 => array ( "title" => __ ( "View"))
  ));
  sys_set_menu_highlight ( "/profiles");

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
  $output .= voipdomain_timezonepicker_map_markup ();
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
  $output .= "          <select name=\"NGGW\" id=\"profile_view_nggw\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway to non-geographic calls") . "\" disabled=\"disabled\"></select>\n";
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
              "  new PNotify ( { title: '" . __ ( "View profile") . "', text: '" . __ ( "Error viewing profile!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the profile edit page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_edit_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Profiles"));
  sys_set_subtitle ( __ ( "Edit profiles"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Profiles"), "link" => "/profiles"),
    2 => array ( "title" => __ ( "Edit"))
  ));
  sys_set_menu_highlight ( "/profiles");

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
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"profile_edit_description\" placeholder=\"" . __ ( "Profile description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile domain field
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_edit_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Domain\" class=\"form-control\" id=\"profile_edit_domain\" placeholder=\"" . __ ( "Profile domain") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile external prefix field
  $output .= "      <div class=\"form-group required\">\n";
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
  $output .= "      <div class=\"form-group required\">\n";
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
  $output .= voipdomain_timezonepicker_map_markup ();
  $output .= "          </map>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile country selector
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_edit_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Country\" id=\"profile_edit_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile country") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile timezone selector
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_edit_timezone\" class=\"control-label col-xs-2\">" . __ ( "Time zone") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"TimeZone\" id=\"profile_edit_timezone\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile time zone") . "\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= voipdomain_timezonepicker_select_markup ();
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile area code field
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_edit_areacode\" class=\"control-label col-xs-2\">" . __ ( "Area code") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"AreaCode\" class=\"form-control\" id=\"profile_edit_areacode\" placeholder=\"" . __ ( "Profile area code") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add profile language selector
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_edit_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Language\" id=\"profile_edit_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Profile language") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Gateways panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"profile_edit_tab_gateways\">\n";

  // Add profile non geographic gateway selector
  $output .= "      <div class=\"form-group required\">\n";
  $output .= "        <label for=\"profile_edit_nggw\" class=\"control-label col-xs-2\">" . __ ( "Non geographic gateway") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"NGGW\" id=\"profile_edit_nggw\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway to non-geographic calls") . "\"><option value=\"\"></option></select>\n";
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
  $output .= "      <div class=\"form-group required\">\n";
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
              "  new PNotify ( { title: '" . __ ( "Edit profile") . "', text: '" . __ ( "Error retrieving gateways!") . "', type: 'error'});\n" .
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
              "  new PNotify ( { title: '" . __ ( "Edit profile") . "', text: '" . __ ( "Error retrieving data from profile!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#profile_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/profiles/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Edit profile") . "',\n" .
              "    fail: '" . __ ( "Error changing profile!") . "',\n" .
              "    success: '" . __ ( "Profile changed successfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/profiles', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#profile_edit_form').data ( 'formData');\n" .
              "  formData.Emergency = $('#profile_edit_emergency').prop ( 'checked');\n" .
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
