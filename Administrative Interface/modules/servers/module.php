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
 * VoIP Domain servers module. This module manage servers. You can have one or
 * more servers managed by the system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Servers
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/servers", "servers_search_page");
framework_add_hook ( "servers_search_page", "servers_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/servers/add", "servers_add_page");
framework_add_hook ( "servers_add_page", "servers_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/servers/:id/view", "servers_view_page");
framework_add_hook ( "servers_view_page", "servers_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/servers/:id/edit", "servers_edit_page");
framework_add_hook ( "servers_edit_page", "servers_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main servers page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Servers"));
  sys_set_subtitle ( __ ( "servers search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Servers"))
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
   * Server search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Name") . "</th>\n";
  $output .= "      <th>" . __ ( "Address") . "</th>\n";
  $output .= "      <th>" . __ ( "Domain") . "</th>\n";
  $output .= "      <th>" . __ ( "Extensions") . "</th>\n";
  $output .= "      <th>" . __ ( "Actions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add server reinstall modal code
   */
  $output .= "<div id=\"server_reinstall\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"server_reinstall\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Reinstall server") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to reinstall the server %s (%s)?"), "<span id=\"server_reinstall_name\"></span>", "<span id=\"server_reinstall_address\"></span>") . "</p><input type=\"hidden\" id=\"server_reinstall_id\" value=\"\"></div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "        <button class=\"btn btn-success reinstall ladda-button\" data-style=\"expand-left\">" . __ ( "Reinstall") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add server remove modal code
   */
  $output .= "<div id=\"server_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"server_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove server") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the server %s (%s)?"), "<span id=\"server_delete_name\"></span>", "<span id=\"server_delete_address\"></span>") . "</p><input type=\"hidden\" id=\"server_delete_id\" value=\"\"></div>\n";
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
              "  data: VoIP.dataTables ( '/servers/fetch', 'GET'),\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 5 ]},\n" .
              "                { searchable: false, targets: [ 0, 5 ]},\n" .
              "                { data: 'links', render: function ( data, type, full) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/servers/' + full[0] + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/servers/' + full[0] + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><button class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Reinstall") . "\" role=\"button\" title=\"\" data-id=\"' + full[0] + '\" data-name=\"' + full[1] + '\" data-address=\"' + full[2] + '\"><i class=\"fa fa-recycle\"></i></button><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + full[0] + '\" data-name=\"' + full[1] + '\" data-address=\"' + full[2] + '\"' + ( full[4] != 0 ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, targets: [ 5 ]},\n" .
              "                { visible: false, targets: [ 0 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '40%', class: 'export all'},\n" .
              "             { width: '20%', class: 'export min-tablet-l'},\n" .
              "             { width: '20%', class: 'export min-tablet-l'},\n" .
              "             { width: '10%', class: 'export min-tablet-l'},\n" .
              "             { width: '10%', class: 'all'}\n" .
              "           ]\n" .
              "}));\n" .
              "$('#fastsearch').val ( '');\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/servers/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#server_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#server_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#server_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#server_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#server_delete_address').html ( $(this).data ( 'address'));\n" .
              "  $('#server_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#server_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var server = VoIP.rest ( '/servers/' + $('#server_delete_id').val (), 'DELETE');\n" .
              "  if ( server.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#server_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Server remove") . "', text: '" . __ ( "Server removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#server_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Server remove") . "', text: '" . __ ( "Error removing server!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "});\n" .
              "$('#datatables').on ( 'click', 'button.btn-success', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#server_reinstall_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#server_reinstall button.reinstall').prop ( 'disabled', false);\n" .
              "  $('#server_reinstall_id').val ( $(this).data ( 'id'));\n" .
              "  $('#server_reinstall_name').html ( $(this).data ( 'name'));\n" .
              "  $('#server_reinstall_address').html ( $(this).data ( 'address'));\n" .
              "  $('#server_reinstall').modal ( 'show');\n" .
              "});\n" .
              "$('#server_reinstall button.reinstall').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var server = VoIP.rest ( '/servers/' + $('#server_reinstall_id').val () + '/reinstall', 'POST');\n" .
              "  if ( server.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#server_reinstall').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Server reinstall") . "', text: '" . __ ( "Server reinstalled sucessfully!") . "', type: 'success'});\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Server reinstall") . "', text: '" . __ ( "Error reinstalling server!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the server add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Servers"));
  sys_set_subtitle ( __ ( "servers addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Servers"), "link" => "/servers"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "multiselect", "src" => "/vendors/multiselect/dist/js/multiselect.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"server_add_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#server_add_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_add_tab_gateways\">" . __ ( "Gateways") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_add_tab_transfers\">" . __ ( "Transfers") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"server_add_tab_basic\">\n";

  // Add server name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"name\" class=\"form-control\" id=\"server_add_name\" placeholder=\"" . __ ( "Server name") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"address\" class=\"form-control\" id=\"server_add_address\" placeholder=\"" . __ ( "Server address") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server domain field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"domain\" class=\"form-control\" id=\"server_add_domain\" placeholder=\"" . __ ( "Server domain") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server country selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"country\" id=\"server_add_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Server country") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server area code field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_areacode\" class=\"control-label col-xs-2\">" . __ ( "Area code") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"areacode\" class=\"form-control\" id=\"server_add_areacode\" placeholder=\"" . __ ( "Server area code") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Gateways panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_add_tab_gateways\">\n";

  // Add server non geographic gateway selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_nggw\" class=\"control-label col-xs-2\">" . __ ( "Non geographic gateway") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"nggw\" id=\"server_add_nggw\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway to non geographic calls") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server blocked gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_blocked\" class=\"control-label col-xs-2\">" . __ ( "Blocked gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"blockeds_list\" id=\"server_add_blocked\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"server_add_blocked_rightAll\" class=\"btn btn-block\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_add_blocked_rightSelected\" class=\"btn btn-block\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_add_blocked_leftSelected\" class=\"btn btn-block\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_add_blocked_leftAll\" class=\"btn btn-block\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"blockeds\" id=\"server_add_blocked_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server preferred gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_gateways\" class=\"control-label col-xs-2\">" . __ ( "Preferred gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"gateways_list\" id=\"server_add_gateways\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"server_add_gateways_rightAll\" class=\"btn btn-block\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_add_gateways_rightSelected\" class=\"btn btn-block\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_add_gateways_leftSelected\" class=\"btn btn-block\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_add_gateways_leftAll\" class=\"btn btn-block\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_add_gateways_move_up\" class=\"btn btn-block\"><i class=\"fa fa-angle-up\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_add_gateways_move_down\" class=\"btn btn-block\"><i class=\"fa fa-angle-down\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"gateways\" id=\"server_add_gateways_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Transfers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_add_tab_transfers\">\n";

  // Add server transfer window option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_window\" class=\"control-label col-xs-2\">" . __ ( "Limit transfers") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"window\" id=\"server_add_window\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server transfer start/finish time limit field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_start\" class=\"control-label col-xs-2\">" . __ ( "Permitted hours") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <div class=\"input-group date\">\n";
  $output .= "                <input name=\"start\" id=\"server_add_start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "                <div class=\"input-group-btn\">\n";
  $output .= "                  <button class=\"btn btn-default btn-clock\" type=\"button\" disabled=\"disabled\"><i class=\"fa fa-calendar-alt\"></i></button>\n";
  $output .= "                  <button class=\"btn btn-default btn-clean\" type=\"button\" disabled=\"disabled\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "                </div>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-md-1\">\n";
  $output .= "            " . __ ( "till") . "\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <div class=\"input-group date\">\n";
  $output .= "                <input name=\"finish\" id=\"server_add_finish\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "                <div class=\"input-group-btn\">\n";
  $output .= "                  <button class=\"btn btn-default btn-clock\" type=\"button\" disabled=\"disabled\"><i class=\"fa fa-calendar-alt\"></i></button>\n";
  $output .= "                  <button class=\"btn btn-default btn-clean\" type=\"button\" disabled=\"disabled\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "                </div>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/servers\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Add") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#server_add_country').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/countries/search', 'GET')\n" .
              "});\n" .
              "$('#server_add_nggw').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/gateways/search', 'GET')\n" .
              "});\n" .
              "$('#server_add_blocked').multiselect (\n" .
              "{\n" .
              "  afterMoveToRight: function ( left, right, options)\n" .
              "                    {\n" .
              "                      $(options).each ( function ()\n" .
              "                      {\n" .
              "                        if ( $('#server_add_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').length == 1)\n" .
              "                        {\n" .
              "                          $('#server_add_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').detach ().prependTo ( $('#server_add_gateways'));\n" .
              "                        }\n" .
              "                        $('#server_add_gateways,#server_add_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').attr ( 'disabled', 'disabled');\n" .
              "                      });\n" .
              "                    },\n" .
              "  afterMoveToLeft: function ( left, right, options)\n" .
              "                   {\n" .
              "                     $(options).each ( function ()\n" .
              "                     {\n" .
              "                       $('#server_add_gateways,#server_add_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').removeAttr ( 'disabled');\n" .
              "                     });\n" .
              "                   }\n" .
              "});\n" .
              "$('#server_add_gateways').multiselect (\n" .
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
              "$('#server_add_areacode').mask ( '0#');\n" .
              "$('#server_add_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#server_add_window').on ( 'change', function ( event)\n" .
              "{\n" .
              "  if ( $(this).prop ( 'checked'))\n" .
              "  {\n" .
              "    $('#server_add_start,#server_add_finish').removeAttr ( 'disabled').parent ().find ( 'button').removeAttr ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#server_add_start,#server_add_finish').attr ( 'disabled', 'disabled').parent ().find ( 'button').attr ( 'disabled', 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "$('#server_add_start,#server_add_finish').mask ( '00:00').datetimepicker ( { format: 'HH:mm'});\n" .
              "$('#server_add_start').on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#server_add_finish').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "});\n" .
              "$('#server_add_finish').on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#server_add_start').data ( 'DateTimePicker').maxDate ( e.date);\n" .
              "});\n" .
              "$('button.btn-clock').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "var gateways = VoIP.select2 ( '/gateways/search', 'GET');\n" .
              "if ( typeof gateways === 'object')\n" .
              "{\n" .
              "  for ( var gateway in gateways)\n" .
              "  {\n" .
              "    if ( ! gateways.hasOwnProperty ( gateway))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    $('<option value=\"' + gateways[gateway].id + '\">' + gateways[gateway].text + '</option>').appendTo ( '#server_add_gateways');\n" .
              "    $('<option value=\"' + gateways[gateway].id + '\">' + gateways[gateway].text + '</option>').appendTo ( '#server_add_blocked');\n" .
              "  }\n" .
              "} else {\n" .
              "  new PNotify ( { title: '" . __ ( "Server addition") . "', text: '" . __ ( "Error requesting gateways!") . "', type: 'error'});\n" .
              "}\n" .
              "$('#server_add_name').focus ();\n" .
              "$('#server_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/servers',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Server addition") . "',\n" .
              "    fail: '" . __ ( "Error adding server!") . "',\n" .
              "    success: '" . __ ( "Server added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/servers', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the server view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Servers"));
  sys_set_subtitle ( __ ( "servers visualization"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Servers"), "link" => "/servers"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"server_view_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#server_view_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_view_tab_gateways\">" . __ ( "Gateways") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_view_tab_transfers\">" . __ ( "Transfers") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"server_view_tab_basic\">\n";

  // Add server name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"name\" class=\"form-control\" id=\"server_view_name\" placeholder=\"" . __ ( "Server name") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"address\" class=\"form-control\" id=\"server_view_address\" placeholder=\"" . __ ( "Server address") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server domain field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"domain\" class=\"form-control\" id=\"server_view_domain\" placeholder=\"" . __ ( "Server domain") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server country selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"country\" id=\"server_view_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Server country") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server area code field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_areacode\" class=\"control-label col-xs-2\">" . __ ( "Area code") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"areacode\" class=\"form-control\" id=\"server_view_areacode\" placeholder=\"" . __ ( "Server area code") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Gateways panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_view_tab_gateways\">\n";

  // Add server non geographic gateway selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_nggw\" class=\"control-label col-xs-2\">" . __ ( "Non geographic gateway") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"nggw\" id=\"server_view_nggw\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway to non geographic calls") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server blocked gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_blockeds\" class=\"control-label col-xs-2\">" . __ ( "Blocked gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"blockeds\" id=\"server_view_blockeds\" class=\"form-control\" data-placeholder=\"" . __ ( "Server blocked gateways") . "\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server preferred gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_gateways\" class=\"control-label col-xs-2\">" . __ ( "Preferred gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"gateways\" id=\"server_view_gateways\" class=\"form-control\" data-placeholder=\"" . __ ( "Server preferred gateways") . "\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Transfers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_view_tab_transfers\">\n";

  // Add server transfer window option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_window\" class=\"control-label col-xs-2\">" . __ ( "Limit transfers") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"window\" id=\"server_view_window\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server transfer start/finish time limit field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_start\" class=\"control-label col-xs-2\">" . __ ( "Permitted hours") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <input name=\"start\" id=\"server_view_start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-md-1\">\n";
  $output .= "            " . __ ( "till") . "\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <input name=\"finish\" id=\"server_view_finish\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/servers\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#server_view_country').select2 ();\n" .
              "$('#server_view_blockeds').select2 ();\n" .
              "$('#server_view_gateways').select2 ();\n" .
              "$('#server_view_nggw').select2 ();\n" .
              "$('#server_view_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#server_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#server_view_name').val ( data.name);\n" .
              "  $('#server_view_address').val ( data.address);\n" .
              "  $('#server_view_domain').val ( data.domain);\n" .
              "  $('#server_view_country').append ( $('<option>', { value: data.country, text: data.countryname})).val ( data.country).trigger ( 'change');\n" .
              "  $('#server_view_areacode').val ( data.areacode);\n" .
              "  $('#server_view_nggw').append ( $('<option>', { value: data.nggw, text: data.nggwname})).val ( data.nggw).trigger ( 'change');\n" .
              "  var ids = [];\n" .
              "  for ( var id in data.gateways)\n" .
              "  {\n" .
              "    if ( ! data.gateways.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    ids.push ( id);\n" .
              "    $('#server_view_gateways').append ( $('<option>', { value: id, text: data.gateways[id]}));\n" .
              "  }\n" .
              "  $('#server_view_gateways').val ( ids).trigger ( 'change');\n" .
              "  var ids = [];\n" .
              "  for ( var id in data.blockeds)\n" .
              "  {\n" .
              "    if ( ! data.blockeds.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    ids.push ( id);\n" .
              "    $('#server_view_blockeds').append ( $('<option>', { value: id, text: data.blockeds[id]}));\n" .
              "  }\n" .
              "  $('#server_view_blockeds').val ( ids).trigger ( 'change');\n" .
              "  $('#server_view_window').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.window ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#server_view_start').val ( data.start);\n" .
              "  $('#server_view_finish').val ( data.finish);\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var server = VoIP.rest ( '/servers/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( server.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#server_view_form').trigger ( 'fill', server.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Server view") . "', text: '" . __ ( "Error viewing server!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n");

  return $output;
}

/**
 * Function to generate the server edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Servers"));
  sys_set_subtitle ( __ ( "servers edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Servers"), "link" => "/servers"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "multiselect", "src" => "/vendors/multiselect/dist/js/multiselect.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"server_edit_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#server_edit_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_edit_tab_gateways\">" . __ ( "Gateways") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_edit_tab_transfers\">" . __ ( "Transfers") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"server_edit_tab_basic\">\n";

  // Add server name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"name\" class=\"form-control\" id=\"server_edit_name\" placeholder=\"" . __ ( "Server name") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"address\" class=\"form-control\" id=\"server_edit_address\" placeholder=\"" . __ ( "Server address") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server domain field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_domain\" class=\"control-label col-xs-2\">" . __ ( "Domain") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"domain\" class=\"form-control\" id=\"server_edit_domain\" placeholder=\"" . __ ( "Server domain") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server country selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_country\" class=\"control-label col-xs-2\">" . __ ( "Country") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"country\" id=\"server_edit_country\" class=\"form-control\" data-placeholder=\"" . __ ( "Server country") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server area code field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_areacode\" class=\"control-label col-xs-2\">" . __ ( "Area code") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"areacode\" class=\"form-control\" id=\"server_edit_areacode\" placeholder=\"" . __ ( "Server area code") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Gateways panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_edit_tab_gateways\">\n";

  // Add server non geographic gateway selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_nggw\" class=\"control-label col-xs-2\">" . __ ( "Non geographic gateway") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"nggw\" id=\"server_edit_nggw\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway to non geographic calls") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server blocked gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_blocked\" class=\"control-label col-xs-2\">" . __ ( "Blocked gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"blockeds_list\" id=\"server_edit_blocked\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"server_edit_blocked_rightAll\" class=\"btn btn-block\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_edit_blocked_rightSelected\" class=\"btn btn-block\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_edit_blocked_leftSelected\" class=\"btn btn-block\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_edit_blocked_leftAll\" class=\"btn btn-block\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"blockeds\" id=\"server_edit_blocked_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server preferred gateways selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_gateways\" class=\"control-label col-xs-2\">" . __ ( "Preferred gateways") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"gateways_list\" id=\"server_edit_gateways\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"server_edit_gateways_rightAll\" class=\"btn btn-block\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_edit_gateways_rightSelected\" class=\"btn btn-block\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_edit_gateways_leftSelected\" class=\"btn btn-block\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_edit_gateways_leftAll\" class=\"btn btn-block\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_edit_gateways_move_up\" class=\"btn btn-block\"><i class=\"fa fa-angle-up\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"server_edit_gateways_move_down\" class=\"btn btn-block\"><i class=\"fa fa-angle-down\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"gateways\" id=\"server_edit_gateways_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Transfers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_edit_tab_transfers\">\n";

  // Add server transfer window option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_window\" class=\"control-label col-xs-2\">" . __ ( "Limit transfers") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"window\" id=\"server_edit_window\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server transfer start/finish time limit field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_start\" class=\"control-label col-xs-2\">" . __ ( "Permitted hours") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <div class=\"input-group date\">\n";
  $output .= "                <input name=\"start\" id=\"server_edit_start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "                <div class=\"input-group-btn\">\n";
  $output .= "                  <button class=\"btn btn-default btn-clock\" type=\"button\" disabled=\"disabled\"><i class=\"fa fa-calendar-alt\"></i></button>\n";
  $output .= "                  <button class=\"btn btn-default btn-clean\" type=\"button\" disabled=\"disabled\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "                </div>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-md-1\">\n";
  $output .= "            " . __ ( "till") . "\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <div class=\"input-group date\">\n";
  $output .= "                <input name=\"finish\" id=\"server_edit_finish\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "                <div class=\"input-group-btn\">\n";
  $output .= "                  <button class=\"btn btn-default btn-clock\" type=\"button\" disabled=\"disabled\"><i class=\"fa fa-calendar-alt\"></i></button>\n";
  $output .= "                  <button class=\"btn btn-default btn-clean\" type=\"button\" disabled=\"disabled\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "                </div>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/servers\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#server_edit_country').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/countries/search', 'GET')\n" .
              "});\n" .
              "$('#server_edit_nggw').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/gateways/search', 'GET')\n" .
              "});\n" .
              "$('#server_edit_blocked').multiselect (\n" .
              "{\n" .
              "  afterMoveToRight: function ( left, right, options)\n" .
              "                    {\n" .
              "                      $(options).each ( function ()\n" .
              "                      {\n" .
              "                        if ( $('#server_edit_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').length == 1)\n" .
              "                        {\n" .
              "                          $('#server_edit_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').detach ().prependTo ( $('#server_edit_gateways'));\n" .
              "                        }\n" .
              "                        $('#server_edit_gateways,#server_edit_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').attr ( 'disabled', 'disabled');\n" .
              "                      });\n" .
              "                    },\n" .
              "  afterMoveToLeft: function ( left, right, options)\n" .
              "                   {\n" .
              "                     $(options).each ( function ()\n" .
              "                     {\n" .
              "                       $('#server_edit_gateways,#server_edit_gateways_to').find ( 'option[value=\"' + $(this).val () + '\"]').removeAttr ( 'disabled');\n" .
              "                     });\n" .
              "                   }\n" .
              "});\n" .
              "$('#server_edit_gateways').multiselect (\n" .
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
              "$('#server_edit_areacode').mask ( '0#');\n" .
              "$('#server_edit_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#server_edit_window').on ( 'change', function ( event)\n" .
              "{\n" .
              "  if ( $(this).prop ( 'checked'))\n" .
              "  {\n" .
              "    $('#server_edit_start,#server_edit_finish').removeAttr ( 'disabled').parent ().find ( 'button').removeAttr ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#server_edit_start,#server_edit_finish').attr ( 'disabled', 'disabled').parent ().find ( 'button').attr ( 'disabled', 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "$('#server_edit_start,#server_edit_finish').mask ( '00:00').datetimepicker ( { format: 'HH:mm'});\n" .
              "$('#server_edit_start').on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#server_edit_finish').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "});\n" .
              "$('#server_edit_finish').on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#server_edit_start').data ( 'DateTimePicker').maxDate ( e.date);\n" .
              "});\n" .
              "$('button.btn-clock').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "var gateways = VoIP.select2 ( '/gateways/search', 'GET');\n" .
              "if ( typeof gateways === 'object')\n" .
              "{\n" .
              "  for ( var gateway in gateways)\n" .
              "  {\n" .
              "    if ( ! gateways.hasOwnProperty ( gateway))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    $('<option value=\"' + gateways[gateway].id + '\">' + gateways[gateway].text + '</option>').appendTo ( '#server_edit_gateways');\n" .
              "    $('<option value=\"' + gateways[gateway].id + '\">' + gateways[gateway].text + '</option>').appendTo ( '#server_edit_blocked');\n" .
              "  }\n" .
              "} else {\n" .
              "  new PNotify ( { title: '" . __ ( "Server edition") . "', text: '" . __ ( "Error requesting gateways!") . "', type: 'error'});\n" .
              "}\n" .
              "$('#server_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#server_edit_name').val ( data.name);\n" .
              "  $('#server_edit_address').val ( data.address);\n" .
              "  $('#server_edit_domain').val ( data.domain);\n" .
              "  $('#server_edit_country').val ( data.country).trigger ( 'change');\n" .
              "  $('#server_edit_areacode').val ( data.areacode);\n" .
              "  $('#server_edit_nggw').val ( data.nggw).trigger ( 'change');\n" .
              "  for ( var id in data.gateways)\n" .
              "  {\n" .
              "    if ( ! data.gateways.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    $('#server_edit_gateways').find ( 'option[value=\"' + id + '\"]').remove().appendTo ( $('#server_edit_gateways_to'));\n" .
              "  }\n" .
              "  for ( var id in data.blockeds)\n" .
              "  {\n" .
              "    if ( ! data.blockeds.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    $('#server_edit_blocked').find ( 'option[value=\"' + id + '\"]').remove().appendTo ( $('#server_edit_blocked_to'));\n" .
              "  }\n" .
              "  $('#server_edit_window').bootstrapToggle ( ( data.window ? 'on' : 'off'));\n" .
              "  $('#server_edit_start').val ( data.start);\n" .
              "  $('#server_edit_finish').val ( data.finish);\n" .
              "  $('#server_edit_name').focus ();\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var server = VoIP.rest ( '/servers/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( server.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#server_edit_form').trigger ( 'fill', server.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Server edition") . "', text: '" . __ ( "Error requesting data from server!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n" .
              "$('#server_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/servers/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Server edition") . "',\n" .
              "    fail: '" . __ ( "Error changing server!") . "',\n" .
              "    success: '" . __ ( "Server changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/servers', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}
?>
