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
 * VoIP Domain servers module WebUI. This module manage servers. You can have one
 * or more servers managed by the system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Servers
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/servers", "servers_search_page");
framework_add_hook ( "servers_search_page", "servers_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/servers/add", "servers_add_page");
framework_add_hook ( "servers_add_page", "servers_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/servers/:id/clone", "servers_clone_function");
framework_add_hook ( "servers_clone_function", "servers_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/servers/:id/view", "servers_view_page");
framework_add_hook ( "servers_view_page", "servers_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/servers/:id/edit", "servers_edit_page");
framework_add_hook ( "servers_edit_page", "servers_edit_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/servers/:id/report", "servers_report_page");
framework_add_hook ( "servers_report_page", "servers_report_page", IN_HOOK_INSERT_FIRST);

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
  sys_addjs ( array ( "name" => "download", "src" => "/vendors/download/download.js", "dep" => array ()));

  /**
   * Server search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add server rebuild modal code
   */
  $output .= "<div id=\"server_rebuild\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"server_rebuild\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Rebuild server") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to rebuild the server %s?"), "<span id=\"server_rebuild_description\"></span>") . "</p><input type=\"hidden\" id=\"server_rebuild_id\" value=\"\"><br /><span id=\"server_rebuild_serverlist\"></span><br /><input type=\"checkbox\" id=\"server_rebuild_clean\" class=\"noauto\" /> <label for=\"server_rebuild_clean\">" . __ ( "Clear all server configuration files first (CAUTION)!") . "</label></div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "        <button class=\"btn btn-success rebuild ladda-button\" data-style=\"expand-left\">" . __ ( "Rebuild") . "</button>\n";
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
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove server") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the server %s (%s)?"), "<span id=\"server_delete_description\"></span>", "<span id=\"server_delete_address\"></span>") . "</p><input type=\"hidden\" id=\"server_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '50%', class: 'export all'},\n" .
              "             { data: 'Address', title: '" . __ ( "Address") . "', width: '25%', class: 'export min-tablet-l'},\n" .
              "             { data: 'Port', title: '" . __ ( "Port") . "', width: '5%', class: 'export min-tablet-l'},\n" .
              "             { data: 'Extensions', title: '" . __ ( "Extensions") . "', width: '10%', class: 'export min-tablet-l'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/servers/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Report") . "\" role=\"button\" title=\"\" href=\"/servers/' + row.ID + '/report\"><i class=\"fa fa-list\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/servers/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/servers/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-default\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Rebuild") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-description=\"' + row.Description + '\" data-address=\"' + row.Address + '\" data-backups=\"' + btoa ( JSON.stringify ( row.Backups)) + '\"><i class=\"fa fa-recycle\"></i></button><button class=\"btn btn-xs btn-primary ladda-button\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Install script") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\"><i class=\"fa fa-file-code\"></i></button><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-description=\"' + row.Description + '\" data-address=\"' + row.Address + '\"' + ( row.Extensions != 0 ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/servers', fields: 'ID,Description,Address,Port,Backups,Extensions,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/servers/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#server_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#server_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#server_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#server_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#server_delete_address').html ( $(this).data ( 'address'));\n" .
              "  $('#server_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#server_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/servers/' + encodeURIComponent ( $('#server_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#server_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Server remove") . "', text: '" . __ ( "Server removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#server_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Server remove") . "', text: '" . __ ( "Error removing server!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n" .
              "$('#datatables').on ( 'click', 'button.btn-primary', function ( e)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/servers/' + encodeURIComponent ( $(this).data ( 'id')) + '/install', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    download ( atob ( data.script), 'vdclient-install-' + $(this).data ( 'id') + '.sh', 'text/x-shellscript');\n" .
              "    new PNotify ( { title: '" . __ ( "Server install script") . "', text: '" . __ ( "Server install script generated sucessfully!") . "', type: 'success'});\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Server install script") . "', text: '" . __ ( "Error generating server install script!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n" .
              "$('#datatables').on ( 'click', 'button.btn-default', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#server_rebuild button.rebuild').prop ( 'disabled', false);\n" .
              "  $('#server_rebuild_id').val ( $(this).data ( 'id'));\n" .
              "  $('#server_rebuild_clean').prop ( 'checked', false);\n" .
              "  $('#server_rebuild_description').html ( $(this).data ( 'description'));\n" .
              "  var backups = JSON.parse ( atob ( $(this).data ( 'backups')));\n" .
              "  var content = '<input type=\"checkbox\" id=\"server_rebuild_server_main\" checked=\"checked\" /> <label for=\"server_rebuild_server_main\">' + $(this).data ( 'address') + ' (" . __ ( "Main server") . ")</label><br />';\n" .
              "  for ( var x in backups)\n" .
              "  {\n" .
              "    content += '<input type=\"checkbox\" id=\"server_rebuild_backup_' + backups[x].ID + '\" checked=\"checked\" /> <label for=\"server_rebuild_backup_' + backups[x].ID + '\">' + backups[x].Address + ' (" . __ ( "Backup server") . ")</label><br />';\n" .
              "  }\n" .
              "  $('#server_rebuild_serverlist').html ( content);\n" .
              "  $('#server_rebuild').modal ( 'show');\n" .
              "});\n" .
              "$('#server_rebuild button.rebuild').on ( 'click', function ( event)\n" .
              "{\n" .
              "  if ( $('#server_rebuild_serverlist').find ( 'input').length == 0)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Server rebuild") . "', text: '" . __ ( "At least one server must be selected!") . "', type: 'error'});\n" .
              "    return;\n" .
              "  }\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/servers/' + encodeURIComponent ( $('#server_rebuild_id').val ()) + '/rebuild', 'POST', { Clean: $('#server_rebuild_clean').prop ( 'checked')}).done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#server_rebuild').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Server rebuild") . "', text: '" . __ ( "Server rebuilded sucessfully!") . "', type: 'success'});\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Server rebuild") . "', text: '" . __ ( "Error rebuilding server!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
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
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "sortable", "src" => "/vendors/Sortable/Sortable.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"server_add_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#server_add_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_add_tab_backups\">" . __ ( "Backup servers") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_add_tab_transfers\">" . __ ( "Transfers") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"server_add_tab_basic\">\n";

  // Add server description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"server_add_description\" placeholder=\"" . __ ( "Server description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Address\" class=\"form-control\" id=\"server_add_address\" placeholder=\"" . __ ( "Server address") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server port field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_port\" class=\"control-label col-xs-2\">" . __ ( "Port") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Port\" class=\"form-control\" id=\"server_add_port\" placeholder=\"" . __ ( "Server port") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server NTP address field
  $output .= "      <span id=\"server_add_ntp_servers\">\n";
  $output .= "        <div class=\"form-group form-ntpserver hidden\">\n";
  $output .= "          <label for=\"server_add_ntp__X_\" class=\"control-label col-xs-2\">" . __ ( "NTP server") . "<br /><span class=\"drag-handle\">&#9776;</span></label>\n";
  $output .= "          <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "            <div class=\"input-group\">\n";
  $output .= "              <input type=\"text\" name=\"NTP__X_\" class=\"form-control\" id=\"server_add_ntp__X_\" placeholder=\"" . __ ( "NTP server") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-delntpserver\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Remove server") . "\"><i class=\"fa fa-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new NTP server button
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <div class=\"col-xs-12 add-ntpserver\">\n";
  $output .= "            <span class=\"input-icon-button btn btn-success btn-addntpserver pull-right\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Add server") . "\"><i class=\"fa fa-plus\"></i></span>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";

  // Backup servers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_add_tab_backups\">\n";

  // Add backup servers table
  $output .= "      <div class=\"col-xs-2\" style=\"text-align: right\">\n";
  $output .= "        <label for=\"server_backup_description_1\" class=\"control-label\">" . __ ( "Backup server") . "</label><br />\n";
  $output .= "        <button class=\"input-icon-button btn btn-success btn-addbackup pull-right\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Add new backup server") . "\"><i class=\"fa fa-plus\"></i></button>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-10\">\n";
  $output .= "        <span id=\"server_add_backups\">\n";
  $output .= "          <div class=\"hidden\">\n";
  $output .= "            <input type=\"text\" name=\"Backup_Description__X_\" class=\"form-control\" id=\"server_add_backup_description__X_\" placeholder=\"" . __ ( "Backup server name") . "\" />\n";
  $output .= "            <input type=\"text\" name=\"Backup_Address__X_\" class=\"form-control\" id=\"server_add_backup_address__X_\" placeholder=\"" . __ ( "Backup server address") . "\" />\n";
  $output .= "            <input type=\"text\" name=\"Backup_Port__X_\" class=\"form-control\" id=\"server_add_backup_port__X_\" placeholder=\"" . __ ( "Backup server port") . "\" />\n";
  $output .= "            <span class=\"input-group-addon input-icon-button btn btn-error btn-delbackup\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Remove this backup server") . "\"><i class=\"fa fa-minus\"></i></span>\n";
  $output .= "          </div>\n";
  $output .= "        </span>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Transfers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_add_tab_transfers\">\n";

  // Add server transfer window option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_window\" class=\"control-label col-xs-2\">" . __ ( "Limit transfers") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Window\" id=\"server_add_window\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server transfer start/finish time limit field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_add_start\" class=\"control-label col-xs-2\">" . __ ( "Permitted hours") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <div class=\"input-group date\">\n";
  $output .= "                <input name=\"Start\" id=\"server_add_start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "                <div class=\"input-group-btn\">\n";
  $output .= "                  <button class=\"btn btn-default btn-clock\" type=\"button\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show calendar") . "\" disabled=\"disabled\"><i class=\"fa fa-calendar-alt\"></i></button>\n";
  $output .= "                  <button class=\"btn btn-default btn-clean\" type=\"button\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Clear data") . "\" disabled=\"disabled\"><i class=\"fa fa-times\"></i></button>\n";
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
  $output .= "                <input name=\"Finish\" id=\"server_add_finish\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "                <div class=\"input-group-btn\">\n";
  $output .= "                  <button class=\"btn btn-default btn-clock\" type=\"button\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show calendar") . "\" disabled=\"disabled\"><i class=\"fa fa-calendar-alt\"></i></button>\n";
  $output .= "                  <button class=\"btn btn-default btn-clean\" type=\"button\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Clear data") . "\" disabled=\"disabled\"><i class=\"fa fa-times\"></i></button>\n";
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
  sys_addjs ( "$('.btn-addntpserver').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-ntpserver\">' + $('#server_add_ntp_servers div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#server_add_ntp_servers div.hidden')).removeClass ( 'hidden').find ( '.btn-delntpserver').tooltip ( { container: 'body'});\n" .
              "  $('#server_add_ntp_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('#server_add_ntp_servers').on ( 'click', '.btn-delntpserver', function ()\n" .
              "{\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  if ( $('#server_add_ntp_servers div.form-ntpserver').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-ntpserver').remove ();\n" .
              "  }\n" .
              "});\n" .
              "Sortable.create ( document.getElementById ( 'server_add_ntp_servers'), { animation: 150, handle: '.drag-handle', draggable: '.form-ntpserver'});\n" .
              "$('#server_add_port').mask ( '0#');\n" .
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
              "$('#server_add_form .btn-addbackup').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  if ( $('#server_add_backups').find ( 'div.input-group').length >= 3)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Server addition") . "', text: '" . __ ( "Maximum number of backup servers reached!") . "', type: 'error'});\n" .
              "    return;\n" .
              "  }\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"input-group form-group\">' + $('#server_add_tab_backups div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').appendTo ( $('#server_add_backups')).removeClass ( 'hidden').find ( 'span').tooltip ();\n" .
              "  $('#server_add_backup_port_' + $(this).data ( 'id')).mask ( '0#');\n" .
              "  $('#server_add_backup_description_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('#server_add_tab_backups').on ( 'click', '.btn-delbackup', function ()\n" .
              "{\n" .
              "  if ( $('#server_add_backups div.form-group').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).tooltip ( 'hide').closest ( 'div.form-group').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('.btn-addbackup').trigger ( 'click');\n" .
              "$('.btn-addntpserver').trigger ( 'click');\n" .
              "$('#server_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#server_add_description'),\n" .
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
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#server_add_form').data ( 'formData');\n" .
              "  formData.NTP = new Array ();\n" .
              "  var tmp = $('#server_add_ntp_servers').find ( 'input');\n" .
              "  for ( var x = 0; x < tmp.length; x++)\n" .
              "  {\n" .
              "    if ( tmp[x].name.substr ( 4) != '_X_')\n" .
              "    {\n" .
              "      formData.NTP.push ( tmp[x].value);\n" .
              "    }\n" .
              "    delete ( formData[tmp[x].name]);\n" .
              "  }\n" .
              "  formData.Backups = new Array ();\n" .
              "  for ( var x = 1; x <= $('.btn-addbackup').data ( 'id'); x++)\n" .
              "  {\n" .
              "    if ( $('#server_add_backup_description_' + x).val ())\n" .
              "    {\n" .
              "      var tmp = new Object ();\n" .
              "      tmp.Reference = x;\n" .
              "      tmp.Description = $('#server_add_backup_description_' + x).val ();\n" .
              "      tmp.Address = $('#server_add_backup_address_' + x).val ();\n" .
              "      tmp.Port = $('#server_add_backup_port_' + x).val ();\n" .
              "      formData.Backups.push ( tmp);\n" .
              "      delete ( tmp);\n" .
              "    }\n" .
              "    delete ( formData['Backup_Description_' + x]);\n" .
              "    delete ( formData['Backup_Address_' + x]);\n" .
              "    delete ( formData['Backup_Port_' + x]);\n" .
              "  }\n" .
              "  delete ( formData['Backup_Description__X_']);\n" .
              "  delete ( formData['Backup_Address__X_']);\n" .
              "  delete ( formData['Backup_Port__X_']);\n" .
              "  $('#server_add_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}

/**
 * Function to generate the server clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/servers/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/servers/add', true, function ()\n" .
              "  {\n" .
              "    $('#server_add_description').val ( data.Description);\n" .
              "    $('#server_add_address').val ( data.Address);\n" .
              "    $('#server_add_port').val ( data.Port);\n" .
              "    for ( var index in data.NTP)\n" .
              "    {\n" .
              "      if ( data.NTP.hasOwnProperty ( index))\n" .
              "      {\n" .
              "        $('#server_add_ntp_' + $('.btn-addntpserver').data ( 'id')).val ( data.NTP[index]).trigger ( 'change');\n" .
              "        $('.btn-addntpserver').trigger ( 'click');\n" .
              "      }\n" .
              "    }\n" .
              "    for ( var index in data.Backups)\n" .
              "    {\n" .
              "      if ( data.Backups.hasOwnProperty ( index))\n" .
              "      {\n" .
              "        $('#server_add_backup_description_' + $('.btn-addbackup').data ( 'id')).val ( data.Backups[index].Description).trigger ( 'change');\n" .
              "        $('#server_add_backup_address_' + $('.btn-addbackup').data ( 'id')).val ( data.Backups[index].Address).trigger ( 'change');\n" .
              "        $('#server_add_backup_port_' + $('.btn-addbackup').data ( 'id')).val ( data.Backups[index].Port).trigger ( 'change');\n" .
              "        $('.btn-addbackup').trigger ( 'click');\n" .
              "      }\n" .
              "    }\n" .
              "    $('#server_add_window').bootstrapToggle ( data.Window ? 'on' : 'off');\n" .
              "    $('#server_add_start').val ( data.Start);\n" .
              "    $('#server_add_finish').val ( data.Finish);\n" .
              "    $('#server_add_description').focus ();\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Server cloning") . "', text: '" . __ ( "Error requesting server data!") . "', type: 'error'});\n" .
              "});\n");
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
  sys_addcss ( array ( "name" => "jquery-labelauty", "src" => "/vendors/jquery-labelauty/source/jquery-labelauty.css", "dep" => array ()));

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
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_view_tab_backups\">" . __ ( "Backup servers") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_view_tab_transfers\">" . __ ( "Transfers") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"server_view_tab_basic\">\n";

  // Add server description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"server_view_description\" placeholder=\"" . __ ( "Server description") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Address\" class=\"form-control\" id=\"server_view_address\" placeholder=\"" . __ ( "Server address") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server port field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_port\" class=\"control-label col-xs-2\">" . __ ( "Port") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Port\" class=\"form-control\" id=\"server_view_port\" placeholder=\"" . __ ( "Server port") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server NTP address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "NTP Server") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <span id=\"server_view_ntp_servers\">\n";
  $output .= "            <div class=\"hidden\">\n";
  $output .= "              <input type=\"text\" name=\"NTP__X_\" class=\"form-control\" id=\"server_view_ntp__X_\" placeholder=\"" . __ ( "Server NTP") . "\" disabled=\"disabled\" />\n";
  $output .= "            </div>\n";
  $output .= "          </span>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Backup servers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_view_tab_backups\">\n";

  // Add backup servers table
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_backup_description__X_\" class=\"control-label col-xs-2\">" . __ ( "Backup server") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <span id=\"server_view_backups\">\n";
  $output .= "            <div class=\"hidden\">\n";
  $output .= "              <input type=\"text\" name=\"Backup_Description__X_\" class=\"form-control\" id=\"server_view_backup_description__X_\" placeholder=\"" . __ ( "Backup server name") . "\" disabled=\"disabled\" />\n";
  $output .= "              <input type=\"text\" name=\"Backup_Address__X_\" class=\"form-control\" id=\"server_view_backup_address__X_\" placeholder=\"" . __ ( "Backup server address") . "\" disabled=\"disabled\" />\n";
  $output .= "              <input type=\"text\" name=\"Backup_Port__X_\" class=\"form-control\" id=\"server_view_backup_port__X_\" placeholder=\"" . __ ( "Backup server port") . "\" disabled=\"disabled\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error\" disabled=\"disabled\"></span>\n";
  $output .= "            </div>\n";
  $output .= "          </span>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Transfers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_view_tab_transfers\">\n";

  // Add server transfer window option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_window\" class=\"control-label col-xs-2\">" . __ ( "Limit transfers") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Window\" id=\"server_view_window\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server transfer start/finish time limit field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_view_start\" class=\"control-label col-xs-2\">" . __ ( "Permitted hours") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <input name=\"Start\" id=\"server_view_start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-md-1\">\n";
  $output .= "            " . __ ( "till") . "\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <input name=\"Finish\" id=\"server_view_finish\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
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
  sys_addjs ( "$('#server_view_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#server_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#server_view_description').val ( data.Description);\n" .
              "  $('#server_view_address').val ( data.Address);\n" .
              "  $('#server_view_port').val ( data.Port);\n" .
              "  for ( var index in data.NTP)\n" .
              "  {\n" .
              "    if ( data.NTP.hasOwnProperty ( index))\n" .
              "    {\n" .
              "      $('<div class=\"input-group col-xs-12\">' + $('#server_view_ntp_servers div.hidden').html ().replace ( /_X_/g, index) + '</div>').appendTo ( $('#server_view_ntp_servers')).removeClass ( 'hidden');\n" .
              "      $('#server_view_ntp_' + index).val ( data.NTP[index]).trigger ( 'change');\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.NTP.length == 0)\n" .
              "  {\n" .
              "    $('<div class=\"input-group col-xs-12\">' + $('#server_view_ntp_servers div.hidden').html ().replace ( /_X_/g, '0') + '</div>').appendTo ( $('#server_view_ntp_servers')).removeClass ( 'hidden');\n" .
              "  }\n" .
              "  for ( var index in data.Backups)\n" .
              "  {\n" .
              "    if ( data.Backups.hasOwnProperty ( index))\n" .
              "    {\n" .
              "      $('<div class=\"input-group form-group col-xs-12\">' + $('#server_view_backups div.hidden').html ().replace ( /_X_/g, index) + '</div>').appendTo ( $('#server_view_backups')).removeClass ( 'hidden');\n" .
              "      $('#server_view_backup_description_' + index).val ( data.Backups[index].Description).trigger ( 'change');\n" .
              "      $('#server_view_backup_address_' + index).val ( data.Backups[index].Address).trigger ( 'change');\n" .
              "      $('#server_view_backup_port_' + index).val ( data.Backups[index].Port).trigger ( 'change');\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.Backups.length == 0)\n" .
              "  {\n" .
              "    $('<div class=\"input-group form-group col-xs-12\">' + $('#server_view_backups div.hidden').html ().replace ( /_X_/g, '0') + '</div>').appendTo ( $('#server_view_backups')).removeClass ( 'hidden');\n" .
              "  }\n" .
              "  $('#server_view_window').bootstrapToggle ( 'enable').bootstrapToggle ( data.Window ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#server_view_start').val ( data.Start);\n" .
              "  $('#server_view_finish').val ( data.Finish);\n" .
              "});\n" .
              "VoIP.rest ( '/servers/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#server_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Server view") . "', text: '" . __ ( "Error viewing server!") . "', type: 'error'});\n" .
              "});\n");

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
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "sortable", "src" => "/vendors/Sortable/Sortable.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"server_edit_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#server_edit_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_edit_tab_backups\">" . __ ( "Backup servers") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#server_edit_tab_transfers\">" . __ ( "Transfers") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"server_edit_tab_basic\">\n";

  // Add server description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"server_edit_description\" placeholder=\"" . __ ( "Server description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Address\" class=\"form-control\" id=\"server_edit_address\" placeholder=\"" . __ ( "Server address") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server port field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_port\" class=\"control-label col-xs-2\">" . __ ( "Port") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Port\" class=\"form-control\" id=\"server_edit_port\" placeholder=\"" . __ ( "Server port") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server NTP address field
  $output .= "      <span id=\"server_edit_ntp_servers\">\n";
  $output .= "        <div class=\"form-group form-ntpserver hidden\">\n";
  $output .= "          <label for=\"server_edit_ntp__X_\" class=\"control-label col-xs-2\">" . __ ( "NTP server") . "<br /><span class=\"drag-handle\">&#9776;</span></label>\n";
  $output .= "          <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "            <div class=\"input-group\">\n";
  $output .= "              <input type=\"text\" name=\"NTP__X_\" class=\"form-control\" id=\"server_edit_ntp__X_\" placeholder=\"" . __ ( "NTP server") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-delntpserver\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Remove server") . "\"><i class=\"fa fa-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new NTP server button
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <div class=\"col-xs-12 edit-ntpserver\">\n";
  $output .= "            <span class=\"input-icon-button btn btn-success btn-addntpserver pull-right\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Add server") . "\"><i class=\"fa fa-plus\"></i></span>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";

  // Backup servers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_edit_tab_backups\">\n";

  // Add backup servers table
  $output .= "      <div class=\"col-xs-2\" style=\"text-align: right\">\n";
  $output .= "        <label for=\"server_backup_description_1\" class=\"control-label\">" . __ ( "Backup server") . "</label><br />\n";
  $output .= "        <button class=\"input-icon-button btn btn-success btn-addbackup pull-right\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Add new backup server") . "\"><i class=\"fa fa-plus\"></i></button>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-10\">\n";
  $output .= "        <span id=\"server_edit_backups\">\n";
  $output .= "          <div class=\"hidden\">\n";
  $output .= "            <input type=\"text\" name=\"Backup_Description__X_\" class=\"form-control\" id=\"server_edit_backup_description__X_\" placeholder=\"" . __ ( "Backup server name") . "\" />\n";
  $output .= "            <input type=\"text\" name=\"Backup_Address__X_\" class=\"form-control\" id=\"server_edit_backup_address__X_\" placeholder=\"" . __ ( "Backup server address") . "\" />\n";
  $output .= "            <input type=\"text\" name=\"Backup_Port__X_\" class=\"form-control\" id=\"server_edit_backup_port__X_\" placeholder=\"" . __ ( "Backup server port") . "\" />\n";
  $output .= "            <span class=\"input-group-addon input-icon-button btn btn-error btn-delbackup\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Remove this backup server") . "\"><i class=\"fa fa-minus\"></i></span>\n";
  $output .= "          </div>\n";
  $output .= "        </span>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Transfers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"server_edit_tab_transfers\">\n";

  // Add server transfer window option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_window\" class=\"control-label col-xs-2\">" . __ ( "Limit transfers") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Window\" id=\"server_edit_window\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add server transfer start/finish time limit field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"server_edit_start\" class=\"control-label col-xs-2\">" . __ ( "Permitted hours") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"col-md-3\">\n";
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <div class=\"input-group date\">\n";
  $output .= "                <input name=\"Start\" id=\"server_edit_start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "                <div class=\"input-group-btn\">\n";
  $output .= "                  <button class=\"btn btn-default btn-clock\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show calendar") . "\" disabled=\"disabled\"><i class=\"fa fa-calendar-alt\"></i></button>\n";
  $output .= "                  <button class=\"btn btn-default btn-clean\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Clear data") . "\" disabled=\"disabled\"><i class=\"fa fa-times\"></i></button>\n";
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
  $output .= "                <input name=\"Finish\" id=\"server_edit_finish\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"5\" disabled=\"disabled\" />\n";
  $output .= "                <div class=\"input-group-btn\">\n";
  $output .= "                  <button class=\"btn btn-default btn-clock\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show calendar") . "\" disabled=\"disabled\"><i class=\"fa fa-calendar-alt\"></i></button>\n";
  $output .= "                  <button class=\"btn btn-default btn-clean\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Clear data") . "\" disabled=\"disabled\"><i class=\"fa fa-times\"></i></button>\n";
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
  sys_addjs ( "$('.btn-addntpserver').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-ntpserver\">' + $('#server_edit_ntp_servers div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#server_edit_ntp_servers div.hidden')).removeClass ( 'hidden').find ( '.btn-delntpserver').tooltip ( { container: 'body'});\n" .
              "  $('#server_edit_ntp_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('#server_edit_ntp_servers').on ( 'click', '.btn-delntpserver', function ()\n" .
              "{\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  if ( $('#server_edit_ntp_servers div.form-ntpserver').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-ntpserver').remove ();\n" .
              "  }\n" .
              "});\n" .
              "Sortable.create ( document.getElementById ( 'server_edit_ntp_servers'), { animation: 150, handle: '.drag-handle', draggable: '.form-ntpserver'});\n" .
              "$('#server_edit_port').mask ( '0#');\n" .
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
              "$('#server_edit_form .btn-addbackup').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  if ( $('#server_edit_backups').find ( 'div.input-group').length >= 4)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Server addition") . "', text: '" . __ ( "Maximum number of backup servers reached!") . "', type: 'error'});\n" .
              "    return;\n" .
              "  }\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"input-group form-group\">' + $('#server_edit_tab_backups div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').appendTo ( $('#server_edit_backups')).removeClass ( 'hidden').find ( 'span').tooltip ();\n" .
              "  $('#server_edit_backup_port_' + $(this).data ( 'id')).mask ( '0#');\n" .
              "  $('#server_edit_backup_description_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('#server_edit_tab_backups').on ( 'click', '.btn-delbackup', function ()\n" .
              "{\n" .
              "  if ( $('#server_edit_backups div.form-group').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).tooltip ( 'hide').closest ( 'div.form-group').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('#server_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#server_edit_description').val ( data.Description);\n" .
              "  $('#server_edit_address').val ( data.Address);\n" .
              "  $('#server_edit_port').val ( data.Port);\n" .
              "  for ( var index in data.NTP)\n" .
              "  {\n" .
              "    if ( data.NTP.hasOwnProperty ( index))\n" .
              "    {\n" .
              "      $('.btn-addntpserver').trigger ( 'click');\n" .
              "      $('#server_edit_ntp_' + $('.btn-addntpserver').data ( 'id')).val ( data.NTP[index]).trigger ( 'change');\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.NTP.length == 0)\n" .
              "  {\n" .
              "    $('.btn-addntpserver').trigger ( 'click');\n" .
              "  }\n" .
              "  for ( var index in data.Backups)\n" .
              "  {\n" .
              "    if ( data.Backups.hasOwnProperty ( index))\n" .
              "    {\n" .
              "      $('.btn-addbackup').trigger ( 'click');\n" .
              "      $('#server_edit_backup_description_' + $('.btn-addbackup').data ( 'id')).val ( data.Backups[index].Description).trigger ( 'change');\n" .
              "      $('#server_edit_backup_address_' + $('.btn-addbackup').data ( 'id')).val ( data.Backups[index].Address).trigger ( 'change');\n" .
              "      $('#server_edit_backup_port_' + $('.btn-addbackup').data ( 'id')).val ( data.Backups[index].Port).trigger ( 'change');\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.Backups.length == 0)\n" .
              "  {\n" .
              "    $('.btn-addbackup').trigger ( 'click');\n" .
              "  }\n" .
              "  $('#server_edit_window').bootstrapToggle ( data.Window ? 'on' : 'off');\n" .
              "  $('#server_edit_start').val ( data.Start);\n" .
              "  $('#server_edit_finish').val ( data.Finish);\n" .
              "  $('#server_edit_description').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/servers/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#server_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Server edition") . "', text: '" . __ ( "Error requesting data from server!") . "', type: 'error'});\n" .
              "});\n" .
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
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#server_edit_form').data ( 'formData');\n" .
              "  formData.NTP = new Array ();\n" .
              "  var tmp = $('#server_edit_ntp_servers').find ( 'input');\n" .
              "  for ( var x = 0; x < tmp.length; x++)\n" .
              "  {\n" .
              "    if ( tmp[x].name.substr ( 4) != '_X_')\n" .
              "    {\n" .
              "      formData.NTP.push ( tmp[x].value);\n" .
              "    }\n" .
              "    delete ( formData[tmp[x].name]);\n" .
              "  }\n" .
              "  formData.Backups = new Array ();\n" .
              "  for ( var x = 1; x <= $('.btn-addbackup').data ( 'id'); x++)\n" .
              "  {\n" .
              "    if ( $('#server_edit_backup_description_' + x).val ())\n" .
              "    {\n" .
              "      var tmp = new Object ();\n" .
              "      tmp.Reference = x;\n" .
              "      tmp.Description = $('#server_edit_backup_description_' + x).val ();\n" .
              "      tmp.Address = $('#server_edit_backup_address_' + x).val ();\n" .
              "      tmp.Port = $('#server_edit_backup_port_' + x).val ();\n" .
              "      formData.Backups.push ( tmp);\n" .
              "      delete ( tmp);\n" .
              "    }\n" .
              "    delete ( formData['Backup_Description_' + x]);\n" .
              "    delete ( formData['Backup_Address_' + x]);\n" .
              "    delete ( formData['Backup_Port_' + x]);\n" .
              "  }\n" .
              "  delete ( formData['Backup_Description__X_']);\n" .
              "  delete ( formData['Backup_Address__X_']);\n" .
              "  delete ( formData['Backup_Port__X_']);\n" .
              "  $('#server_edit_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}

/**
 * Function to generate the server report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Servers"));
  sys_set_subtitle ( __ ( "servers report"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Servers"), "link" => "/servers"),
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
      "URL" => "'/servers/' + encodeURIComponent ( VoIP.parameters.id) + '/report'",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}
?>
