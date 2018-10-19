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
 * VoIP Domain groups module. This module manage the group function of system,
 * that allow you to create user groups, usefull to create pickup groups and to
 * organize reports and company sectors.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Groups
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/groups", "groups_search_page");
framework_add_hook ( "groups_search_page", "groups_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/groups/add", "groups_add_page");
framework_add_hook ( "groups_add_page", "groups_add_page", IN_HOOK_INSERT_FIRST);

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
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Group search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Description") . "</th>\n";
  $output .= "      <th>" . __ ( "Cost Center") . "</th>\n";
  $output .= "      <th>" . __ ( "Extensions") . "</th>\n";
  $output .= "      <th>" . __ ( "Actions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add group remove modal code
   */
  $output .= "<div id=\"group_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"group_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
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
              "  data: VoIP.dataTables ( '/groups/fetch', 'GET'),\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 4 ]},\n" .
              "                { searchable: false, targets: [ 0, 4 ]},\n" .
              "                { data: 'links', render: function ( data, type, full) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/groups/' + full[0] + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Report") . "\" role=\"button\" title=\"\" href=\"/groups/' + full[0] + '/report\"><i class=\"fa fa-list\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/groups/' + full[0] + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + full[0] + '\" data-description=\"' + full[1] + '\" data-costcenter=\"' + full[2] + '\"' + ( full[3] != 0 ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, targets: [ 4 ]},\n" .
              "                { visible: false, targets: [ 0 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '50%', class: 'export all'},\n" .
              "             { width: '30%', class: 'export min-tablet-l'},\n" .
              "             { width: '10%', class: 'export min-tablet-l'},\n" .
              "             { width: '10%', class: 'all'}\n" .
              "           ]\n" .
              "}));\n" .
              "$('#fastsearch').val ( '');\n" .
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
              "  var group = VoIP.rest ( '/groups/' + $('#group_delete_id').val (), 'DELETE');\n" .
              "  if ( group.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#group_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Group removal") . "', text: '" . __ ( "Group removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#group_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Group removal") . "', text: '" . __ ( "Error removing group!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
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
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"group_add_description\" placeholder=\"" . __ ( "Group description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add group cost center selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_add_costcenter\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"costcenter\" id=\"group_add_costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Group cost center") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/groups\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Add") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#group_add_costcenter').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/costcenters/search', 'GET')\n" .
              "});\n" .
              "$('#group_add_description').focus ();\n" .
              "$('#group_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
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
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"group_view_description\" placeholder=\"" . __ ( "Group description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add group cost center selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_view_costcenter\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"costcenter\" id=\"group_view_costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Group cost center") . "\" disabled=\"disabled\"></select>\n";
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
  sys_addjs ( "$('#group_view_costcenter').select2 ();\n" .
              "$('#group_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#group_view_description').val ( data.description);\n" .
              "  $('#group_view_costcenter').append ( $('<option>', { value: data.costcenter, text: data.costcenterdescription})).val ( data.costcenter).trigger ( 'change');\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var group = VoIP.rest ( '/groups/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( group.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#group_view_form').trigger ( 'fill', group.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Group view") . "', text: '" . __ ( "Error viewing group!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n");

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
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"group_edit_description\" placeholder=\"" . __ ( "Group description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add group cost center selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"group_edit_costcenter\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"costcenter\" id=\"group_edit_costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Group cost center") . "\"><option value=\"\"></option></select>\n";
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
  sys_addjs ( "$('#group_edit_costcenter').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/costcenters/search', 'GET')\n" .
              "});\n" .
              "$('#group_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#group_edit_description').val ( data.description);\n" .
              "  $('#group_edit_costcenter').val ( data.costcenter).trigger ( 'change');\n" .
              "  $('#group_edit_description').focus ();\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var group = VoIP.rest ( '/groups/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( group.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#group_edit_form').trigger ( 'fill', group.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Group edition") . "', text: '" . __ ( "Error requesting group data!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n" .
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

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-4\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group\">\n";
  $output .= "          <input name=\"filter\" id=\"filter\" type=\"search\" class=\"form-control\" placeholder=\"" . __ ( "Filter...") . "\" aria-control=\"search\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th>" . __ ( "Direction") . "</th>\n";
  $output .= "      <th>" . __ ( "Source") . "</th>\n";
  $output .= "      <th>" . __ ( "Destination") . "</th>\n";
  $output .= "      <th>" . __ ( "Duration ([[DD:]HH:]MM:SS)") . "</th>\n";
  $output .= "      <th>" . __ ( "Result") . "</th>\n";
  $output .= "      <th>" . __ ( "Type") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Cost") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Quality") . "</th>\n";
  $output .= "      <th>" . __ ( "Observations") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"9\"></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Call details modal
   */
  $output .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $output .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "        <div class=\"tab-content\"><br />\n";

  // Basic informations tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add source number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add destination number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add date and time
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add billing duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call result
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call type
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost center
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add gateway
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // QOS tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add called SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add received packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add RTT
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // Call flow tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $output .= "            <div class=\"diagram\"></div>\n";
  $output .= "          </div>\n";

  // Download tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $output .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><span class=\"glyphicon glyphicon-paperclip\" aria-hidden=\"true\"></span> " . __ ( "PCAP") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><span class=\"glyphicon glyphicon-copy\" aria-hidden=\"true\"></span> " . __ ( "Text") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><span class=\"glyphicon glyphicon-headphones\" aria-hidden=\"true\"></span> " . __ ( "Audio") . "</button>\n";
  $output .= "          </div>\n";

  // Finish modal
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server').select2 ();\n" .
              "$('#call_view_type').select2 ();\n" .
              "$('#call_view_cc').select2 ();\n" .
              "$('#call_view_gateway').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
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
              "  if ( data.server != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.server + '\" selected=\"selected\">' + data.serverdesc + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.server).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( data.date);\n" .
              "  $('#call_view_duration').val ( data.duration);\n" .
              "  $('#call_view_billsec').val ( data.billsec);\n" .
              "  $('#call_view_disposition').val ( data.disposition);\n" .
              "  $('#call_view_source').val ( data.src);\n" .
              "  $('#call_view_destination').val ( data.dst);\n" .
              "  $('#call_view_cost').val ( data.value);\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.type + '\" selected=\"selected\">' + data.typedesc + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.type).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.cc != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.cc + '\" selected=\"selected\">' + data.ccdesc + '</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.cc).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.gw != 0)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.gw + '\" selected=\"selected\">' + data.gwdesc + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.gw).trigger ( 'change');\n" .
              "  }\n" .
              "  if ( data.QOSa.ssrc)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.QOSa.ssrc);\n" .
              "    $('#call_view_qos_themssrc').val ( data.QOSa.themssrc);\n" .
              "    $('#call_view_qos_rxlp').val ( data.QOSa.lp);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.QOSa.rxjitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.QOSa.rxcount);\n" .
              "    $('#call_view_qos_txjitter').val ( data.QOSa.txjitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.QOSa.txcount);\n" .
              "    $('#call_view_qos_txlp').val ( data.QOSa.rlp);\n" .
              "    $('#call_view_qos_rtt').val ( data.QOSa.rtt);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.siptrace.diagram)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('.diagram').html ( data.siptrace.diagram);\n" .
              "    $('#call_view_pcap').data ( 'content', data.siptrace.pcap).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap');\n" .
              "    $('#call_view_text').data ( 'content', data.siptrace.text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt');\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.monitor)\n" .
              "  {\n" .
              "    $('#call_view_audio').data ( 'content', data.uniqueid).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').data ( 'url', data.uniqueid).removeClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#call_view_audio').addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "});\n" .
              "$('#call_view').on ( 'show', function ( event, params)\n" .
              "{\n" .
              "  var l = Ladda.create ( params.button);\n" .
              "  l.start ();\n" .
              "  var call = VoIP.rest ( '/calls/' + encodeURI ( params.id), 'GET');\n" .
              "  if ( call.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#call_view_form').trigger ( 'fill', call.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "}).on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.fileDownload ( 'arquivos-audio.php?download=1&id=' + $(this).data ( 'url'));\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "    $('.diagram tspan').each ( function ()\n" .
              "    {\n" .
              "      $(this).html ( $(this).html ().replace ( '|', ':'));\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  var total_inbound = 0;\n" .
              "  var total_outbound = 0;\n" .
              "  var total_time = 0;\n" .
              "  var total_cost = 0;\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    if ( data.result[x][2] == 'in')\n" .
              "    {\n" .
              "      total_inbound++;\n" .
              "      data.result[x][2] = '<i aria-hidden=\"true\" class=\"fa fa-cloud-download\"></i> " . __ ( "received") . "';\n" .
              "    } else {\n" .
              "      total_outbound++;\n" .
              "      data.result[x][2] = '<i aria-hidden=\"true\" class=\"fa fa-cloud-upload\"></i> " . __ ( "called") . "';\n" .
              "    }\n" .
              "    data.result[x][3] = '<i aria-hidden=\"true\" class=\"fa fa-' + ( data.result[x][3].type == 'external' ? 'phone' : 'user') + '\"></i> ' + data.result[x][3].number;\n" .
              "    var tmp = '<i aria-hidden=\"true\" class=\"fa fa-';\n" .
              "    switch ( data.result[x][4].type)\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "      case 'inbound':\n" .
              "        tmp += 'user';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "      case 'special':\n" .
              "        tmp += 'phone';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        tmp += 'mobile';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        tmp += 'tree';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        tmp += 'globe';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        tmp += 'leaf';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        tmp += 'wrench';\n" .
              "        break;\n" .
              "      default:\n" .
              "        tmp += 'exclamation-triangle';\n" .
              "        break;\n" .
              "    }\n" .
              "    data.result[x][4] = tmp + '\"></i> ' + data.result[x][4].number;\n" .
              "    total_time += parseInt ( data.result[x][5]);\n" .
              "    data.result[x][5] = format_secs_to_string ( data.result[x][5]);\n" .
              "    switch ( data.result[x][7])\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "        data.result[x][7] = '" . __ ( "Extension") . "';\n" .
              "        break;\n" .
              "      case 'inbound':\n" .
              "        data.result[x][7] = '" . __ ( "Received") . "';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "        data.result[x][7] = '" . __ ( "Landline") . "';\n" .
              "        break;\n" .
              "      case 'special':\n" .
              "        data.result[x][7] = '" . __ ( "Special") . "';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        data.result[x][7] = '" . __ ( "Mobile") . "';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        data.result[x][7] = '" . __ ( "Interstate") . "';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        data.result[x][7] = '" . __ ( "International") . "';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        data.result[x][7] = '" . __ ( "Toll free") . "';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        data.result[x][7] = '" . __ ( "Services") . "';\n" .
              "        break;\n" .
              "      default:\n" .
              "        data.result[x][7] = '" . __ ( "Unknown") . "';\n" .
              "        break;\n" .
              "    }\n" .
              "    total_cost += parseFloat ( data.result[x][8].replace ( '.', '').replace ( ',', '.'));\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + data.result[x][11].qos.lossrx + '% " . __ ( "rx") . " / ' + data.result[x][11].qos.losstx + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + data.result[x][11].qos.jitterrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.jittertx + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + data.result[x][11].qos.latencyrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.latencytx + ' ms " . __ ( "tx") . "\">';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '<font color=\"' + data.result[x][11].qualityColor + '\">';\n" .
              "    }\n" .
              "    tmp += data.result[x][11].quality;\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '</font>';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '</abbr>';\n" .
              "    }\n" .
              "    data.result[x][11] = tmp;\n" .
              "    $(table.row.add ( data.result[x]).node ()).addClass ( data.result[x][14]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( '<i class=\"fa fa-cloud\"></i> ' + sprintf ( ( total_inbound + total_outbound) != 1 ? '" . __ ( "%s calls") . "' : '" . __ ( "%s call") . "', total_inbound + total_outbound) + ', <i class=\"fa fa-cloud-upload\"></i> ' + sprintf ( total_outbound != 1 ? '" . __ ( "%s calleds") . "' : '" . __ ( "%s called") . "', total_outbound) + ' " . __ ( "and") . " <i class=\"fa fa-cloud-download\"></i> ' + sprintf ( total_inbound != 1 ? '" . __ ( "%s receiveds") . "' : '" . __ ( "%s received" ) . "', total_inbound));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 3).html ( number_format ( total_cost, 2, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "  $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 12, 13 ]},\n" .
              "                { visible: false, targets: [ 0, 8, 10, 14, 15, 16 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all', dataSort: 0},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export min-desktop'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 8},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 10},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'min-mobile-l', createdCell: function ( cell, cellData, rowData, rowIndex, colIndex) { $(cell).html ( '<button class=\"btn btn-xs btn-call_view btn-info ladda-button\" data-id=\"' + rowData[15] + '\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Details") . "\" role=\"button\"><i class=\"fa fa-search\"></i></button>'); }},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>',\n" .
              "  stateSaveParams: function ( settings, data)\n" .
              "                   {\n" .
              "                     $('#filter').val ( data.search.search);\n" .
              "                   }\n" .
              "}));\n" .
              "$('#start').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#end').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "});\n" .
              "$('#end').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
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
              "$('#report').on ( 'click', 'button.btn-call_view', function ( event) { event && event.preventDefault (); $('#call_view').trigger ( 'show', { button: this, id: $(this).data ( 'id')}); });\n" .
              "$('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( $('#start').val () == '')\n" .
              "  {\n" .
              "    $('#start').alerts ( 'add', { message: '" . __ ( "The start date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#end').val () == '')\n" .
              "  {\n" .
              "    $('#end').alerts ( 'add', { message: '" . __ ( "The finish date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#start').val () == '' || $('#end').val () == '')\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/groups/' + VoIP.parameters.id + '/report', 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfinish: function () { $('#filters').trigger ( 'submit'); }});\n" .
              "$('#filter').focus ();\n");

  return $output;
}
?>
