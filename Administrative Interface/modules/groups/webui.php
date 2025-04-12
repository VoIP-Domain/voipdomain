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
 * VoIP Domain groups module WebUI. This module manage the group function of
 * system, that allow you to create user groups, usefull to create pickup groups
 * and to organize reports and company sectors.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Groups
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/groups", "groups_search_page");
framework_add_hook ( "groups_search_page", "groups_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/groups/add", "groups_add_page");
framework_add_hook ( "groups_add_page", "groups_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/groups/:id/clone", "groups_clone_function");
framework_add_hook ( "groups_clone_function", "groups_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/groups/:id/view", "groups_view_page");
framework_add_hook ( "groups_view_page", "groups_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/groups/:id/edit", "groups_edit_page");
framework_add_hook ( "groups_edit_page", "groups_edit_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/groups/:id/report", "groups_report_page");
framework_add_hook ( "groups_report_page", "groups_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main groups page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Groups"));
  sys_set_subtitle ( __ ( "groups search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Groups"))
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
   * Group search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add group remove modal code
   */
  $output .= "<div id=\"group_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"group_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Group remove") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the group %s (%s)?"), "<span id=\"group_delete_description\"></span>", "<span id=\"group_delete_costcenter\"></span>") . "</p><input type=\"hidden\" id=\"group_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '40%', class: 'export all'},\n" .
              "             { data: 'Profile', title: '" . __ ( "Profile") . "', width: '20%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return row.Profile.Description; }},\n" .
              "             { data: 'CostCenter', title: '" . __ ( "Cost Center") . "', width: '20%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return row.CostCenter.Description; }},\n" .
              "             { data: 'Extensions', title: '" . __ ( "Extensions") . "', width: '10%', class: 'export min-tablet-l'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/groups/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Report") . "\" role=\"button\" title=\"\" href=\"/groups/' + row.ID + '/report\"><i class=\"fa fa-list\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/groups/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/groups/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-description=\"' + row.Description + '\" data-costcenter=\"' + row.CostCenter.ID + '\"' + ( row.Extensions != 0 ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/groups', fields: 'ID,Description,Profile,CostCenter,Extensions,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/groups/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#group_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#group_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#group_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#group_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#group_delete_costcenter').html ( $(this).data ( 'costcenter'));\n" .
              "  $('#group_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#group_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/groups/' + encodeURIComponent ( $('#group_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#group_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Group removal") . "', text: '" . __ ( "Group removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#group_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Group removal") . "', text: '" . __ ( "Error removing group!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the group add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Groups"));
  sys_set_subtitle ( __ ( "groups addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Groups"), "link" => "/groups"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"group_add_form\">\n";

  // Add group description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"group_add_description\" placeholder=\"" . __ ( "Group description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add group profile selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_add_profile\" class=\"control-label col-xs-2\">" . __ ( "Profile") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Profile\" id=\"group_add_profile\" class=\"form-control\" data-placeholder=\"" . __ ( "Group profile") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add group cost center selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_add_costcenter\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"CostCenter\" id=\"group_add_costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Group cost center") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/groups\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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
  sys_addjs ( "$('#group_add_profile').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/profiles', fields: 'ID,Description', formatText: '%Description%'})\n" .
              "});\n" .
              "$('#group_add_costcenter').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/costcenters', fields: 'ID,Description,Code', formatText: '%Description% (%Code%)'})\n" .
              "});\n" .
              "$('#group_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#group_add_description'),\n" .
              "    URL: '/groups',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Group addition") . "',\n" .
              "    fail: '" . __ ( "Error adding group!") . "',\n" .
              "    success: '" . __ ( "Group added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/groups', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the group clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/groups/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/groups/add', true, function ()\n" .
              "  {\n" .
              "    $('#group_add_description').val ( data.Description);\n" .
              "    $('#group_add_profile').val ( data.Profile.ID).trigger ( 'change');\n" .
              "    $('#group_add_costcenter').val ( data.CostCenter.ID).trigger ( 'change');\n" .
              "    $('#group_add_description').focus ();\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Group cloning") . "', text: '" . __ ( "Error requesting group data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the group view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Groups"));
  sys_set_subtitle ( __ ( "groups visualization"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Groups"), "link" => "/groups"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"group_view_form\">\n";

  // Add group description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"group_view_description\" placeholder=\"" . __ ( "Group description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add group profile selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_view_profile\" class=\"control-label col-xs-2\">" . __ ( "Profile") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Profile\" id=\"group_view_profile\" class=\"form-control\" data-placeholder=\"" . __ ( "Group profile") . "\" disabled=\"disabled\"></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add group cost center selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_view_costcenter\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"CostCenter\" id=\"group_view_costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Group cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/groups\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#group_view_profile,#group_view_costcenter').select2 ();\n" .
              "$('#group_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#group_view_description').val ( data.Description);\n" .
              "  $('#group_view_profile').append ( $('<option>', { value: data.Profile.ID, text: data.Profile.Description})).val ( data.Profile.ID).trigger ( 'change');\n" .
              "  $('#group_view_costcenter').append ( $('<option>', { value: data.CostCenter.ID, text: data.CostCenter.Description})).val ( data.CostCenter.ID).trigger ( 'change');\n" .
              "});\n" .
              "VoIP.rest ( '/groups/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#group_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Group view") . "', text: '" . __ ( "Error viewing group!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the group edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Groups"));
  sys_set_subtitle ( __ ( "groups edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Groups"), "link" => "/groups"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"group_edit_form\">\n";

  // Add group description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"group_edit_description\" placeholder=\"" . __ ( "Group description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add group profile selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_edit_profile\" class=\"control-label col-xs-2\">" . __ ( "Profile") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Profile\" id=\"group_edit_profile\" class=\"form-control\" data-placeholder=\"" . __ ( "Group profile") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add group cost center selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_edit_costcenter\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"CostCenter\" id=\"group_edit_costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Group cost center") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/groups\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#group_edit_profile').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/profiles', fields: 'ID,Description', formatText: '%Description%'})\n" .
              "});\n" .
              "$('#group_edit_costcenter').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/costcenters', fields: 'ID,Description,Code', formatText: '%Description% (%Code%)'})\n" .
              "});\n" .
              "$('#group_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#group_edit_description').val ( data.Description);\n" .
              "  $('#group_edit_profile').val ( data.Profile.ID).trigger ( 'change');\n" .
              "  $('#group_edit_costcenter').val ( data.CostCenter.ID).trigger ( 'change');\n" .
              "  $('#group_edit_description').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/groups/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#group_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Group edition") . "', text: '" . __ ( "Error requesting group data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#group_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/groups/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Group edition") . "',\n" .
              "    fail: '" . __ ( "Error changing group!") . "',\n" .
              "    success: '" . __ ( "Group changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/groups', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the group report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Groups"));
  sys_set_subtitle ( __ ( "groups report"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Groups"), "link" => "/groups"),
    2 => array ( "title" => __ ( "Report"))
  ));

  // Generate call report page through hook call:
  return framework_call ( "call_report_page_generate", array (
    "Filters" => "    <div class=\"col-md-4\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <div class=\"input-group\">\n" .
                 "          <input name=\"Filter\" id=\"filter\" type=\"search\" class=\"form-control\" placeholder=\"" . __ ( "Filter...") . "\" aria-control=\"search\" />\n" .
                 "          <div class=\"input-group-btn\">\n" .
                 "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n" .
                 "          </div>\n" .
                 "        </div>\n" .
                 "      </div>\n" .
                 "    </div>\n",
    "Endpoint" => array (
      "URL" => "'/groups/' + encodeURIComponent ( VoIP.parameters.id) + '/report'",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}
?>
