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
 * VoIP Domain gateways module. This module add the feature of key gateways
 * that rings in one or many gateways at same time when called. This is usefull
 * to service numbers inside a company, where many employees can answer the call.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Gateways
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/gateways", "gateways_search_page");
framework_add_hook ( "gateways_search_page", "gateways_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/gateways/add", "gateways_add_page");
framework_add_hook ( "gateways_add_page", "gateways_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/gateways/:id/view", "gateways_view_page");
framework_add_hook ( "gateways_view_page", "gateways_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/gateways/:id/edit", "gateways_edit_page");
framework_add_hook ( "gateways_edit_page", "gateways_edit_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/gateways/:id/report", "gateways_report_page");
framework_add_hook ( "gateways_report_page", "gateways_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main gateways page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Gateways"));
  sys_set_subtitle ( __ ( "gateways search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Gateways"))
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
   * Gateway search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th>" . __ ( "Code") . "</th>\n";
  $output .= "      <th>" . __ ( "Description") . "</th>\n";
  $output .= "      <th>" . __ ( "State") . "</th>\n";
  $output .= "      <th>" . __ ( "Type") . "</th>\n";
  $output .= "      <th>" . __ ( "Number") . "</th>\n";
  $output .= "      <th>" . __ ( "Actions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add gateway remove modal code
   */
  $output .= "<div id=\"gateway_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"gateway_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove gateway") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the gateway %s (%s)?"), "<span id=\"gateway_delete_description\"></span>", "<span id=\"gateway_delete_number\"></span>") . "</p><input type=\"hidden\" id=\"gateway_delete_id\" value=\"\"></div>\n";
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
              "  data: VoIP.dataTables ( '/gateways/search', 'GET'),\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 5 ]},\n" .
              "                { searchable: false, targets: [ 0, 2, 5 ]},\n" .
              "                { data: 'links', render: function ( data, type, full) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/gateways/' + full[0] + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Report") . "\" role=\"button\" title=\"\" href=\"/gateways/' + full[0] + '/report\"><i class=\"fa fa-list\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/gateways/' + full[0] + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + full[0] + '\" data-description=\"' + full[1] + '\" data-number=\"' + full[4] + '\"><i class=\"fa fa-times\"></i></button></span>'; }, targets: [ 5 ]},\n" .
              "                { 'render': function ( data, type, full) { return '<span class=\"label label-' + ( full[2] ? 'success' : 'danger') + '\">' + ( full[2] ? '" . __ ( "Active") . "' : '" . __ ( "Inactive") . "') + '</span>'; }, 'targets': [ 2 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { width: '5%', class: 'export'},\n" .
              "             { width: '60%', class: 'export'},\n" .
              "             { width: '5%', class: 'export'},\n" .
              "             { width: '5%', class: 'export'},\n" .
              "             { width: '15%', class: 'export'},\n" .
              "             { width: '10%'}\n" .
              "           ]\n" .
              "}));\n" .
              "$('#fastsearch').val ( '');\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/gateways/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#gateway_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#gateway_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#gateway_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#gateway_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#gateway_delete_number').html ( $(this).data ( 'number'));\n" .
              "  $('#gateway_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#gateway_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var gateway = VoIP.rest ( '/gateways/' + $('#gateway_delete_id').val (), 'DELETE');\n" .
              "  if ( gateway.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#gateway_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway removal") . "', text: '" . __ ( "Gateway sucessfully removed!") . "', type: 'success'});\n" .
              "    $('#gateway_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway removal") . "', text: '" . __ ( "Error removing gateway!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the gateway add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Gateways"));
  sys_set_subtitle ( __ ( "gateways addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Gateways"), "link" => "/gateways"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/css/bootstrap-slider.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/css/tabdrop.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/bootstrap-slider.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/js/bootstrap-tabdrop.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "sortable", "src" => "/vendors/Sortable/Sortable.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"gateway_add_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_advanced\">" . __ ( "Advanced") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_timing\">" . __ ( "Timming") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_routes\">" . __ ( "Routes") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_translations\">" . __ ( "Translations") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_files\">" . __ ( "Files") . "</a><li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"gateway_add_tab_basic\">\n";

  // Add gateway description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"description\" class=\"form-control\" id=\"gateway_add_description\" placeholder=\"" . __ ( "Gateway description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway activation option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_state\" class=\"control-label col-xs-2\">" . __ ( "State") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"state\" id=\"gateway_add_state\" class=\"form-control\" checked=\"checked\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway type
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"type\" class=\"form-control\" id=\"gateway_add_type\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"Digital\">" . __ ( "Digital") . "</option>\n";
  $output .= "            <option value=\"Analog\">" . __ ( "Analog") . "</option>\n";
  $output .= "            <option value=\"Mobile\">" . __ ( "Mobile") . "</option>\n";
  $output .= "            <option value=\"VoIP\">" . __ ( "VoIP") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway priority
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_priority\" class=\"control-label col-xs-2\">" . __ ( "Priority") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"priority\" class=\"form-control\" id=\"gateway_add_priority\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"1\">" . __ ( "High") . "</option>\n";
  $output .= "            <option value=\"2\">" . __ ( "Medium") . "</option>\n";
  $output .= "            <option value=\"3\">" . __ ( "Low") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway configuration type select field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_config\" class=\"control-label col-xs-2\">" . __ ( "Configuration") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"config\" id=\"gateway_add_config\" class=\"form-control\">\n";
  $output .= "            <option value=\"manual\">" . __ ( "Manual") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway number field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_number\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"number\" class=\"form-control\" id=\"gateway_add_number\" placeholder=\"" . __ ( "Gateway number (E.164 standard)") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway IP address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"address\" class=\"form-control\" id=\"gateway_add_address\" placeholder=\"" . __ ( "Gateway address") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway port field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_port\" class=\"control-label col-xs-2\">" . __ ( "Port") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"port\" class=\"form-control\" id=\"gateway_add_port\" placeholder=\"" . __ ( "Gateway port") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway user field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"username\" class=\"form-control\" id=\"gateway_add_username\" placeholder=\"" . __ ( "Gateway username") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"password\" class=\"form-control\" id=\"gateway_add_password\" placeholder=\"" . __ ( "Gateway password") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Advanced options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_add_tab_advanced\">\n";

  // Add NAT option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_nat\" class=\"control-label col-xs-2\">" . __ ( "NAT") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"nat\" id=\"gateway_add_nat\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add RPID option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_rpid\" class=\"control-label col-xs-2\">" . __ ( "Expose user") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"rpid\" id=\"gateway_add_rpid\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add qualify option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_qualify\" class=\"control-label col-xs-2\">" . __ ( "Qualify") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"qualify\" id=\"gateway_add_qualify\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Timing options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_add_tab_timing\">\n";

  // Add gateway discard time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_discard\" class=\"control-label col-xs-2\">" . __ ( "Discard time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"discard\" class=\"form-control\" id=\"gateway_add_discard\" placeholder=\"" . __ ( "Gateway discard time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway minimum time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_minimum\" class=\"control-label col-xs-2\">" . __ ( "Minimum fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"minimum\" class=\"form-control\" id=\"gateway_add_minimum\" placeholder=\"" . __ ( "Gateway minimum fare time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway time fraction field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_fraction\" class=\"control-label col-xs-2\">" . __ ( "Fraction fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"fraction\" class=\"form-control\" id=\"gateway_add_fraction\" placeholder=\"" . __ ( "Gateway fraction fare time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Routes panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_add_tab_routes\">\n";

  // Add route table
  $output .= "      <span id=\"gateway_add_routes\">\n";
  $output .= "        <div class=\"form-group form-route hidden\">\n";
  $output .= "          <label for=\"gateway_add_route__X_\" class=\"control-label col-xs-2\">" . __ ( "Route") . "<br /><span class=\"drag-handle\">&#9776;</span></label>\n";
  $output .= "          <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "            <div class=\"input-group\">\n";
  $output .= "              <input type=\"text\" name=\"route__X_\" class=\"form-control\" id=\"gateway_add_route__X_\" placeholder=\"" . __ ( "Route mask (E.164 standard)") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"cost__X_\" class=\"form-control\" id=\"gateway_add_cost__X_\" placeholder=\"" . __ ( "Cost (US\$/m)") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-delroute\"><i class=\"glyphicon glyphicon-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new route button
  $output .= "        <div class=\"col-xs-12 add-route\">\n";
  $output .= "          <span class=\"input-icon-button btn btn-success btn-addroute pull-right\"><i class=\"glyphicon glyphicon-plus\"></i></span>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";

  // Translations panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_add_tab_translations\">\n";

  // Add translation table
  $output .= "      <span id=\"gateway_add_translations\">\n";
  $output .= "        <div class=\"form-group form-translation hidden\">\n";
  $output .= "          <label for=\"gateway_add_match__X_\" class=\"control-label col-xs-2\">" . __ ( "Translation") . "<br /><span class=\"drag-handle\">&#9776;</span></label>\n";
  $output .= "          <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "            <div class=\"input-group\">\n";
  $output .= "              <input type=\"text\" name=\"pattern__X_\" class=\"form-control\" id=\"gateway_add_pattern__X_\" placeholder=\"" . __ ( "Pattern (match content)") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"remove__X_\" class=\"form-control\" id=\"gateway_add_remove__X_\" placeholder=\"" . __ ( "Content to remove") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"add__X_\" class=\"form-control\" id=\"gateway_add_add__X_\" placeholder=\"" . __ ( "Content to add") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-deltranslation\"><i class=\"glyphicon glyphicon-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new translation button
  $output .= "        <div class=\"col-xs-12 add-translation\">\n";
  $output .= "          <span class=\"input-icon-button btn btn-success btn-addtranslation pull-right\"><i class=\"glyphicon glyphicon-plus\"></i></span>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";

  // Fares file load panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_add_tab_files\">\n";

  // Add file selection field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_files\" class=\"control-label col-xs-2\">" . __ ( "Files") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"files\" id=\"gateway_add_files\" class=\"form-control\" data-placeholder=\"" . __ ( "Fares files") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add load button
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"col-xs-12\">\n";
  $output .= "          <button id=\"gateway_add_file_load\" class=\"btn btn-danger ladda-button\" data-style=\"pull-right\" disabled=\"disabled\">" . __ ( "Load") . "</button><br /><br />\n";
  $output .= "          <p class=\"pull-right\"><strong>" . __ ( "WARNING") . "</strong>: " . __ ( "When loading fares file, the filled data will be overwritted!") . "</p>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/gateways\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Add") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('.btn-addroute').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-route\">' + $('#gateway_add_tab_routes div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#gateway_add_tab_routes div.hidden')).removeClass ( 'hidden');\n" .
              "  $('#gateway_add_cost_' + $(this).data ( 'id')).mask ( '#.##0,00000', { reverse: true});\n" .
              "  $('#gateway_add_route_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('.btn-addtranslation').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-translation\">' + $('#gateway_add_tab_translations div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#gateway_add_tab_translations div.hidden')).removeClass ( 'hidden');\n" .
              "  $('#gateway_add_pattern_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('#gateway_add_tab_routes').on ( 'click', '.btn-delroute', function ()\n" .
              "{\n" .
              "  if ( $('#gateway_add_tab_routes div.form-route').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-route').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_add_tab_translations').on ( 'click', '.btn-deltranslation', function ()\n" .
              "{\n" .
              "  if ( $('#gateway_add_tab_translations div.form-translation').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-translation').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_add_files').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/gateways/fares/fetch', 'GET')\n" .
              "}).on ( 'select2:select', function ( event)\n" .
              "{\n" .
              "  $('#gateway_add_file_load').removeAttr ( 'disabled');\n" .
              "}).on ( 'select2:unselect', function ( event)\n" .
              "{\n" .
              "  $('#gateway_add_file_load').attr ( 'disabled', 'disabled');\n" .
              "});\n" .
              "$('#gateway_add_file_load').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var fare = VoIP.rest ( '/gateways/fares/' + $('#gateway_add_files').val (), 'GET');\n" .
              "  if ( fare.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#gateway_add_tab_routes div.form-route').not ( '.hidden').remove ();\n" .
              "    $('#gateway_add_tab_translations div.form-translation').not ( '.hidden').remove ();\n" .
              "    $('.btn-addroute').data ( 'id', 0);\n" .
              "    $('.btn-addtranslation').data ( 'id', 0);\n" .
              "    for ( var index in fare.result)\n" .
              "    {\n" .
              "      if ( index == 'routes')\n" .
              "      {\n" .
              "        for ( var x in fare.result.routes)\n" .
              "        {\n" .
              "          $('.btn-addroute').trigger ( 'click');\n" .
              "          $('#gateway_add_route_' + $('.btn-addroute').data ( 'id')).val ( fare.result.routes[x].route);\n" .
              "          $('#gateway_add_cost_' + $('.btn-addroute').data ( 'id')).val ( fare.result.routes[x].cost);\n" .
              "        }\n" .
              "        continue;\n" .
              "      }\n" .
              "      if ( index == 'translations')\n" .
              "      {\n" .
              "        for ( var x in fare.result.translations)\n" .
              "        {\n" .
              "          $('.btn-addtranslation').trigger ( 'click');\n" .
              "          $('#gateway_add_pattern_' + $('.btn-addtranslation').data ( 'id')).val ( fare.result.translations[x].pattern);\n" .
              "          $('#gateway_add_remove_' + $('.btn-addtranslation').data ( 'id')).val ( fare.result.translations[x].remove);\n" .
              "          $('#gateway_add_add_' + $('.btn-addtranslation').data ( 'id')).val ( fare.result.translations[x].add);\n" .
              "        }\n" .
              "        continue;\n" .
              "      }\n" .
              "      if ( $('#gateway_add_' + index).length == 1)\n" .
              "      {\n" .
              "        $('#gateway_add_' + index).val ( fare.result[index]).trigger ( 'change');\n" .
              "      } else {\n" .
              "        console.log ( 'Fare variable \"' + index + '\" with value \"' + fare.result[index] + '\" not found!');\n" .
              "      }\n" .
              "    }\n" .
              "    if ( $('.btn-addroute').data ( 'id') == 0)\n" .
              "    {\n" .
              "      $('.btn-addroute').trigger ( 'click');\n" .
              "    }\n" .
              "    if ( $('.btn-addtranslation').data ( 'id') == 0)\n" .
              "    {\n" .
              "      $('.btn-addtranslation').trigger ( 'click');\n" .
              "    }\n" .
              "    $('#gateway_add_files').val ( null).trigger ( 'change').trigger ( 'select2:unselect');\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway addition") . "', text: '" . __ ( "Fares file loaded sucessfully!") . "', type: 'success'});\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway addition") . "', text: '" . __ ( "Error loading fares file!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "});\n" .
              "$('#gateway_add_state').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "', onstyle: 'success', offstyle: 'danger'});\n" .
              "$('#gateway_add_type').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway type") . "' });\n" .
              "$('#gateway_add_priority').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway priority") . "' });\n" .
              "if ( $('#gateway_add_config option').length == 1)\n" .
              "{\n" .
              "  $('#gateway_add_config').closest ( '.form-group').css ( 'display', 'none');\n" .
              "} else {\n" .
              "  $('#gateway_add_config').select2 ( { allowClear: false, placeholder: '" . __ ( "Gateway configuration type") . "' });\n" .
              "}\n" .
              "$('#gateway_add_number').mask ( '+0#');\n" .
              "$('#gateway_add_port').mask ( '09999');\n" .
              "$('#gateway_add_nat,#gateway_add_rpid,#gateway_add_qualify').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#gateway_add_discard,#gateway_add_minimum,#gateway_add_fraction').mask ( '0#');\n" .
              "$('.btn-addroute,.btn-addtranslation').trigger ( 'click');\n" .
              "Sortable.create ( document.getElementById ( 'gateway_add_routes'), { animation: 150, handle: '.drag-handle', draggable: '.form-route'});\n" .
              "Sortable.create ( document.getElementById ( 'gateway_add_translations'), { animation: 150, handle: '.drag-handle', draggable: '.form-translation'});\n" .
              "$('#gateway_add_description').focus ();\n" .
              "$('#gateway_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/gateways',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Gateway addition") . "',\n" .
              "    fail: '" . __ ( "Error adding gateway!") . "',\n" .
              "    success: '" . __ ( "Gateway added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/gateways', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the gateway view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Gateways"));
  sys_set_subtitle ( __ ( "gateways visualization"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Gateways"), "link" => "/gateways"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/css/bootstrap-slider.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/css/tabdrop.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/bootstrap-slider.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/js/bootstrap-tabdrop.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"gateway_view_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_advanced\">" . __ ( "Advanced") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_timing\">" . __ ( "Timming") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_routes\">" . __ ( "Routes") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_translations\">" . __ ( "Translations") . "</a><li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"gateway_view_tab_basic\">\n";

  // Add gateway description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"description\" class=\"form-control\" id=\"gateway_view_description\" placeholder=\"" . __ ( "Gateway description") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway activation option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_state\" class=\"control-label col-xs-2\">" . __ ( "State") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"state\" id=\"gateway_view_state\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway type
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"type\" class=\"form-control\" id=\"gateway_view_type\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"Digital\">" . __ ( "Digital") . "</option>\n";
  $output .= "            <option value=\"Analog\">" . __ ( "Analog") . "</option>\n";
  $output .= "            <option value=\"Mobile\">" . __ ( "Mobile") . "</option>\n";
  $output .= "            <option value=\"VoIP\">" . __ ( "VoIP") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway priority
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_priority\" class=\"control-label col-xs-2\">" . __ ( "Priority") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"priority\" class=\"form-control\" id=\"gateway_view_priority\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"1\">" . __ ( "High") . "</option>\n";
  $output .= "            <option value=\"2\">" . __ ( "Medium") . "</option>\n";
  $output .= "            <option value=\"3\">" . __ ( "Low") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway configuration type select field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_config\" class=\"control-label col-xs-2\">" . __ ( "Configuration") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"config\" id=\"gateway_view_config\" class=\"form-control\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"manual\">" . __ ( "Manual") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway number field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_number\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"number\" class=\"form-control\" id=\"gateway_view_number\" placeholder=\"" . __ ( "Gateway number (E.164 standard)") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway IP address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"address\" class=\"form-control\" id=\"gateway_view_address\" placeholder=\"" . __ ( "Gateway address") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway port field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_port\" class=\"control-label col-xs-2\">" . __ ( "Port") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"port\" class=\"form-control\" id=\"gateway_view_port\" placeholder=\"" . __ ( "Gateway port") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway user field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"username\" class=\"form-control\" id=\"gateway_view_username\" placeholder=\"" . __ ( "Gateway username") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"password\" class=\"form-control\" id=\"gateway_view_password\" placeholder=\"" . __ ( "Gateway password") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Advanced options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_view_tab_advanced\">\n";

  // Add NAT option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_nat\" class=\"control-label col-xs-2\">" . __ ( "NAT") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"nat\" id=\"gateway_view_nat\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add RPID option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_rpid\" class=\"control-label col-xs-2\">" . __ ( "Expose user") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"rpid\" id=\"gateway_view_rpid\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add qualify option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_qualify\" class=\"control-label col-xs-2\">" . __ ( "Qualify") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"qualify\" id=\"gateway_view_qualify\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Timing options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_view_tab_timing\">\n";

  // Add gateway discard time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_discard\" class=\"control-label col-xs-2\">" . __ ( "Discard time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"discard\" class=\"form-control\" id=\"gateway_view_discard\" placeholder=\"" . __ ( "Gateway discard time") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway minimum time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_minimum\" class=\"control-label col-xs-2\">" . __ ( "Minimum fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"minimum\" class=\"form-control\" id=\"gateway_view_minimum\" placeholder=\"" . __ ( "Gateway minimum fare time") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway time fraction field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_fraction\" class=\"control-label col-xs-2\">" . __ ( "Fraction fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"fraction\" class=\"form-control\" id=\"gateway_view_fraction\" placeholder=\"" . __ ( "Gateway fraction fare time") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Routes panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_view_tab_routes\">\n";

  // Add route table
  $output .= "      <span id=\"gateway_view_routes\">\n";
  $output .= "        <div class=\"form-group form-route hidden\">\n";
  $output .= "          <label for=\"gateway_view_route__X_\" class=\"control-label col-xs-2\">" . __ ( "Route") . "</label>\n";
  $output .= "          <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "            <div class=\"input-group\">\n";
  $output .= "              <input type=\"text\" name=\"route__X_\" class=\"form-control\" id=\"gateway_view_route__X_\" placeholder=\"" . __ ( "Route mask (E.164 standard)") . "\" disabled=\"disabled\" />\n";
  $output .= "              <input type=\"text\" name=\"cost__X_\" class=\"form-control\" id=\"gateway_view_cost__X_\" placeholder=\"" . __ ( "Cost (US\$/m)") . "\" disabled=\"disabled\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error\"></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";

  // Translations panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_view_tab_translations\">\n";

  // Add translation table
  $output .= "      <span id=\"gateway_view_translations\">\n";
  $output .= "        <div class=\"form-group form-translation hidden\">\n";
  $output .= "          <label for=\"gateway_view_match__X_\" class=\"control-label col-xs-2\">" . __ ( "Translation") . "</label>\n";
  $output .= "          <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "            <div class=\"input-group\">\n";
  $output .= "              <input type=\"text\" name=\"pattern__X_\" class=\"form-control\" id=\"gateway_view_pattern__X_\" placeholder=\"" . __ ( "Pattern (match content)") . "\" disabled=\"disabled\" />\n";
  $output .= "              <input type=\"text\" name=\"remove__X_\" class=\"form-control\" id=\"gateway_view_remove__X_\" placeholder=\"" . __ ( "Content to remove") . "\" disabled=\"disabled\" />\n";
  $output .= "              <input type=\"text\" name=\"add__X_\" class=\"form-control\" id=\"gateway_view_add__X_\" placeholder=\"" . __ ( "Content to add") . "\" disabled=\"disabled\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error\"></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/gateways\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#gateway_view_state').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "', onstyle: 'success', offstyle: 'danger'});\n" .
              "$('#gateway_view_type').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway type") . "' });\n" .
              "$('#gateway_view_priority').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway priority") . "' });\n" .
              "$('#gateway_view_config').select2 ( { allowClear: true, placeholder: 'Selecione o tipo de configuração do gateway' });\n" .
              "$('#gateway_view_nat,#gateway_view_rpid,#gateway_view_qualify').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#gateway_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#gateway_view_description').val ( data.description);\n" .
              "  $('#gateway_view_state').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.state ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#gateway_view_type').val ( data.type).trigger ( 'change');\n" .
              "  $('#gateway_view_priority').val ( data.priority).trigger ( 'change');\n" .
              "  $('#gateway_view_config').val ( data.config).trigger ( 'change');\n" .
              "  $('#gateway_view_number').val ( data.number);\n" .
              "  $('#gateway_view_address').val ( data.address);\n" .
              "  $('#gateway_view_port').val ( data.port);\n" .
              "  $('#gateway_view_username').val ( data.username);\n" .
              "  $('#gateway_view_password').val ( data.password);\n" .
              "  $('#gateway_view_nat').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.nat ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#gateway_view_rpid').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.rpid ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#gateway_view_qualify').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.qualify ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#gateway_view_discard').val ( data.discard);\n" .
              "  $('#gateway_view_minimum').val ( data.minimum);\n" .
              "  $('#gateway_view_fraction').val ( data.fraction);\n" .
              "  for ( var id in data.routes)\n" .
              "  {\n" .
              "    if ( data.routes.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      $('<div class=\"form-group form-route\">' + $('#gateway_view_tab_routes div.hidden').html ().replace ( /_X_/g, id) + '</div>').insertBefore ( $('#gateway_view_tab_routes div.hidden')).removeClass ( 'hidden');\n" .
              "      $('#gateway_view_cost_' + id).val ( data.routes[id].cost);\n" .
              "      $('#gateway_view_route_' + id).val ( data.routes[id].route);\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.routes.length == 0)\n" .
              "  {\n" .
              "    $('<div class=\"form-group form-route\">' + $('#gateway_view_tab_routes div.hidden').html ().replace ( /_X_/g, '0') + '</div>').insertBefore ( $('#gateway_view_tab_routes div.hidden')).removeClass ( 'hidden');\n" .
              "  }\n" .
              "  for ( var id in data.translations)\n" .
              "  {\n" .
              "    if ( data.translations.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      $('<div class=\"form-group form-translation\">' + $('#gateway_view_tab_translations div.hidden').html ().replace ( /_X_/g, id) + '</div>').insertBefore ( $('#gateway_view_tab_translations div.hidden')).removeClass ( 'hidden');\n" .
              "      $('#gateway_view_pattern_' + id).val ( data.translations[id].pattern);\n" .
              "      $('#gateway_view_remove_' + id).val ( data.translations[id].remove);\n" .
              "      $('#gateway_view_add_' + id).val ( data.translations[id].add);\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.translations.length == 0)\n" .
              "  {\n" .
              "    $('<div class=\"form-group form-translation\">' + $('#gateway_view_tab_translations div.hidden').html ().replace ( /_X_/g, '0') + '</div>').insertBefore ( $('#gateway_view_tab_translations div.hidden')).removeClass ( 'hidden');\n" .
              "  }\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var gateway = VoIP.rest ( '/gateways/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( gateway.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#gateway_view_form').trigger ( 'fill', gateway.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway visualization") . "', text: '" . __ ( "Error viewing gateway!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n");

  return $output;
}

/**
 * Function to generate the gateway edit page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_edit_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Gateways"));
  sys_set_subtitle ( __ ( "gateways edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Gateways"), "link" => "/gateways"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/css/bootstrap-slider.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/css/tabdrop.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/bootstrap-slider.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/js/bootstrap-tabdrop.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "sortable", "src" => "/vendors/Sortable/Sortable.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"gateway_edit_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_advanced\">" . __ ( "Advanced") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_timing\">" . __ ( "Timming") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_routes\">" . __ ( "Routes") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_translations\">" . __ ( "Translations") . "</a><li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_files\">" . __ ( "Files") . "</a><li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"gateway_edit_tab_basic\">\n";

  // Add gateway description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"description\" class=\"form-control\" id=\"gateway_edit_description\" placeholder=\"" . __ ( "Gateway description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway activation option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_state\" class=\"control-label col-xs-2\">" . __ ( "State") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"state\" id=\"gateway_edit_state\" class=\"form-control\" checked=\"checked\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway type
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"type\" class=\"form-control\" id=\"gateway_edit_type\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"Digital\">" . __ ( "Digital") . "</option>\n";
  $output .= "            <option value=\"Analog\">" . __ ( "Analog") . "</option>\n";
  $output .= "            <option value=\"Mobile\">" . __ ( "Mobile") . "</option>\n";
  $output .= "            <option value=\"VoIP\">" . __ ( "VoIP") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway priority
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_priority\" class=\"control-label col-xs-2\">" . __ ( "Priority") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"priority\" class=\"form-control\" id=\"gateway_edit_priority\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"1\">" . __ ( "High") . "</option>\n";
  $output .= "            <option value=\"2\">" . __ ( "Medium") . "</option>\n";
  $output .= "            <option value=\"3\">" . __ ( "Low") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway configuration type select field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_config\" class=\"control-label col-xs-2\">" . __ ( "Configuration") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"config\" id=\"gateway_edit_config\" class=\"form-control\">\n";
  $output .= "            <option value=\"manual\">" . __ ( "Manual") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway number field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_number\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"number\" class=\"form-control\" id=\"gateway_edit_number\" placeholder=\"" . __ ( "Gateway number (E.164 standard)") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway IP address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"address\" class=\"form-control\" id=\"gateway_edit_address\" placeholder=\"" . __ ( "Gateway address") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway port field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_port\" class=\"control-label col-xs-2\">" . __ ( "Port") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"port\" class=\"form-control\" id=\"gateway_edit_port\" placeholder=\"" . __ ( "Gateway port") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway user field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"username\" class=\"form-control\" id=\"gateway_edit_username\" placeholder=\"" . __ ( "Gateway username") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"password\" class=\"form-control\" id=\"gateway_edit_password\" placeholder=\"" . __ ( "Gateway password") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Advanced options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_edit_tab_advanced\">\n";

  // Add NAT option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_nat\" class=\"control-label col-xs-2\">" . __ ( "NAT") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"nat\" id=\"gateway_edit_nat\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add RPID option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_rpid\" class=\"control-label col-xs-2\">" . __ ( "Expose user") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"rpid\" id=\"gateway_edit_rpid\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add qualify option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_qualify\" class=\"control-label col-xs-2\">" . __ ( "Qualify") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"qualify\" id=\"gateway_edit_qualify\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Timing options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_edit_tab_timing\">\n";

  // Add gateway discard time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_discard\" class=\"control-label col-xs-2\">" . __ ( "Discard time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"discard\" class=\"form-control\" id=\"gateway_edit_discard\" placeholder=\"" . __ ( "Gateway discard time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway minimum time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_minimum\" class=\"control-label col-xs-2\">" . __ ( "Minimum fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"minimum\" class=\"form-control\" id=\"gateway_edit_minimum\" placeholder=\"" . __ ( "Gateway minimum fare time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway time fraction field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_fraction\" class=\"control-label col-xs-2\">" . __ ( "Fraction fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"fraction\" class=\"form-control\" id=\"gateway_edit_fraction\" placeholder=\"" . __ ( "Gateway fraction fare time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Routes panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_edit_tab_routes\">\n";

  // Add route table
  $output .= "      <span id=\"gateway_edit_routes\">\n";
  $output .= "        <div class=\"form-group form-route hidden\">\n";
  $output .= "          <label for=\"gateway_edit_route__X_\" class=\"control-label col-xs-2\">" . __ ( "Route") . "<br /><span class=\"drag-handle\">&#9776;</span></label>\n";
  $output .= "          <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "            <div class=\"input-group\">\n";
  $output .= "              <input type=\"text\" name=\"route__X_\" class=\"form-control\" id=\"gateway_edit_route__X_\" placeholder=\"" . __ ( "Route mask (E.164 standard)") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"cost__X_\" class=\"form-control\" id=\"gateway_edit_cost__X_\" placeholder=\"" . __ ( "Cost (US\$/m)") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-delroute\"><i class=\"glyphicon glyphicon-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new route button
  $output .= "        <div class=\"col-xs-12 add-route\">\n";
  $output .= "          <span class=\"input-icon-button btn btn-success btn-addroute pull-right\"><i class=\"glyphicon glyphicon-plus\"></i></span>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";

  // Translations panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_edit_tab_translations\">\n";

  // Add translation table
  $output .= "      <span id=\"gateway_edit_translations\">\n";
  $output .= "        <div class=\"form-group form-translation hidden\">\n";
  $output .= "          <label for=\"gateway_edit_match__X_\" class=\"control-label col-xs-2\">" . __ ( "Translation") . "<br /><span class=\"drag-handle\">&#9776;</span></label>\n";
  $output .= "          <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "            <div class=\"input-group\">\n";
  $output .= "              <input type=\"text\" name=\"pattern__X_\" class=\"form-control\" id=\"gateway_edit_pattern__X_\" placeholder=\"" . __ ( "Pattern (match content)") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"remove__X_\" class=\"form-control\" id=\"gateway_edit_remove__X_\" placeholder=\"" . __ ( "Content to remove") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"add__X_\" class=\"form-control\" id=\"gateway_edit_add__X_\" placeholder=\"" . __ ( "Content to add") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-deltranslation\"><i class=\"glyphicon glyphicon-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new translation button
  $output .= "        <div class=\"col-xs-12 add-translation\">\n";
  $output .= "          <span class=\"input-icon-button btn btn-success btn-addtranslation pull-right\"><i class=\"glyphicon glyphicon-plus\"></i></span>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";

  // Fares file load panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_edit_tab_files\">\n";

  // Add file selection field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_files\" class=\"control-label col-xs-2\">" . __ ( "Files") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"files\" id=\"gateway_edit_files\" class=\"form-control\" data-placeholder=\"" . __ ( "Fares files") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add load button
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"col-xs-12\">\n";
  $output .= "          <button id=\"gateway_edit_file_load\" class=\"btn btn-danger ladda-button\" data-style=\"pull-right\" disabled=\"disabled\">" . __ ( "Load") . "</button><br /><br />\n";
  $output .= "          <p class=\"pull-right\"><strong>" . __ ( "WARNING") . "</strong>: " . __ ( "When loading fares file, the filled data will be overwritted!") . "</p>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/gateways\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('.btn-addroute').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-route\">' + $('#gateway_edit_tab_routes div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#gateway_edit_tab_routes div.hidden')).removeClass ( 'hidden');\n" .
              "  $('#gateway_edit_cost_' + $(this).data ( 'id')).mask ( '#.##0,00000', { reverse: true});\n" .
              "  $('#gateway_edit_route_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('.btn-addtranslation').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-translation\">' + $('#gateway_edit_tab_translations div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#gateway_edit_tab_translations div.hidden')).removeClass ( 'hidden');\n" .
              "  $('#gateway_edit_pattern_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('#gateway_edit_tab_routes').on ( 'click', '.btn-delroute', function ()\n" .
              "{\n" .
              "  if ( $('#gateway_edit_tab_routes div.form-route').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-route').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_edit_tab_translations').on ( 'click', '.btn-deltranslation', function ()\n" .
              "{\n" .
              "  if ( $('#gateway_edit_tab_translations div.form-translation').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-translation').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_edit_files').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/gateways/fares/fetch', 'GET')\n" .
              "}).on ( 'select2:select', function ( event)\n" .
              "{\n" .
              "  $('#gateway_edit_file_load').removeAttr ( 'disabled');\n" .
              "}).on ( 'select2:unselect', function ( event)\n" .
              "{\n" .
              "  $('#gateway_edit_file_load').attr ( 'disabled', 'disabled');\n" .
              "});\n" .
              "$('#gateway_edit_file_load').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var fare = VoIP.rest ( '/gateways/fares/' + $('#gateway_edit_files').val (), 'GET');\n" .
              "  if ( fare.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#gateway_edit_tab_routes div.form-route').not ( '.hidden').remove ();\n" .
              "    $('#gateway_edit_tab_translations div.form-translation').not ( '.hidden').remove ();\n" .
              "    $('.btn-addroute').data ( 'id', 0);\n" .
              "    $('.btn-addtranslation').data ( 'id', 0);\n" .
              "    for ( var index in fare.result)\n" .
              "    {\n" .
              "      if ( index == 'routes')\n" .
              "      {\n" .
              "        for ( var x in fare.result.routes)\n" .
              "        {\n" .
              "          $('.btn-addroute').trigger ( 'click');\n" .
              "          $('#gateway_edit_route_' + $('.btn-addroute').data ( 'id')).val ( fare.result.routes[x].route).trigger ( 'change');\n" .
              "          $('#gateway_edit_cost_' + $('.btn-addroute').data ( 'id')).val ( fare.result.routes[x].cost).trigger ( 'change');\n" .
              "        }\n" .
              "        continue;\n" .
              "      }\n" .
              "      if ( index == 'translations')\n" .
              "      {\n" .
              "        for ( var x in fare.result.translations)\n" .
              "        {\n" .
              "          $('.btn-addtranslation').trigger ( 'click');\n" .
              "          $('#gateway_edit_pattern_' + $('.btn-addtranslation').data ( 'id')).val ( fare.result.translations[x].pattern).trigger ( 'change');\n" .
              "          $('#gateway_edit_remove_' + $('.btn-addroute').data ( 'id')).val ( fare.result.routes[x].remove).trigger ( 'change');\n" .
              "          $('#gateway_edit_add_' + $('.btn-addroute').data ( 'id')).val ( fare.result.routes[x].add).trigger ( 'change');\n" .
              "        }\n" .
              "        continue;\n" .
              "      }\n" .
              "      if ( $('#gateway_edit_' + index).length == 1)\n" .
              "      {\n" .
              "        $('#gateway_edit_' + index).val ( fare.result[index]).trigger ( 'change');\n" .
              "      } else {\n" .
              "        console.log ( 'Fare variable \"' + index + '\" with value \"' + fare.result[index] + '\" not found!');\n" .
              "      }\n" .
              "    }\n" .
              "    if ( $('.btn-addroute').data ( 'id') == 0)\n" .
              "    {\n" .
              "      $('.btn-addroute').trigger ( 'click');\n" .
              "    }\n" .
              "    if ( $('.btn-addtranslation').data ( 'id') == 0)\n" .
              "    {\n" .
              "      $('.btn-addtranslation').trigger ( 'click');\n" .
              "    }\n" .
              "    $('#gateway_edit_files').val ( null).trigger ( 'change').trigger ( 'select2:unselect');\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway edition") . "', text: '" . __ ( "Fares file loaded sucessfully!") . "', type: 'success'});\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway edition") . "', text: '" . __ ( "Error loading fares file!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "});\n" .
              "$('#gateway_edit_state').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "', onstyle: 'success', offstyle: 'danger'});\n" .
              "$('#gateway_edit_type').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway type") . "' });\n" .
              "$('#gateway_edit_priority').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway priority") . "' });\n" .
              "if ( $('#gateway_edit_config option').length == 1)\n" .
              "{\n" .
              "  $('#gateway_edit_config').closest ( '.form-group').css ( 'display', 'none');\n" .
              "} else {\n" .
              "  $('#gateway_edit_config').select2 ( { allowClear: false, placeholder: '" . __ ( "Gateway configuration type") . "' });\n" .
              "}\n" .
              "$('#gateway_edit_number').mask ( '+0#');\n" .
              "$('#gateway_edit_port').mask ( '09999');\n" .
              "$('#gateway_edit_nat,#gateway_edit_rpid,#gateway_edit_qualify').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#gateway_edit_discard,#gateway_edit_minimum,#gateway_edit_fraction').mask ( '0#');\n" .
              "Sortable.create ( document.getElementById ( 'gateway_edit_routes'), { animation: 150, handle: '.drag-handle', draggable: '.form-route'});\n" .
              "Sortable.create ( document.getElementById ( 'gateway_edit_translations'), { animation: 150, handle: '.drag-handle', draggable: '.form-translation'});\n" .
              "$('#gateway_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#gateway_edit_description').val ( data.description);\n" .
              "  $('#gateway_edit_state').bootstrapToggle ( ( data.state ? 'on' : 'off'));\n" .
              "  $('#gateway_edit_type').val ( data.type).trigger ( 'change');\n" .
              "  $('#gateway_edit_priority').val ( data.priority).trigger ( 'change');\n" .
              "  $('#gateway_edit_config').val ( data.config).trigger ( 'change');\n" .
              "  $('#gateway_edit_number').val ( data.number);\n" .
              "  $('#gateway_edit_address').val ( data.address);\n" .
              "  $('#gateway_edit_port').val ( data.port);\n" .
              "  $('#gateway_edit_username').val ( data.username);\n" .
              "  $('#gateway_edit_password').val ( data.password);\n" .
              "  $('#gateway_edit_nat').bootstrapToggle ( ( data.nat ? 'on' : 'off'));\n" .
              "  $('#gateway_edit_rpid').bootstrapToggle ( ( data.rpid ? 'on' : 'off'));\n" .
              "  $('#gateway_edit_qualify').bootstrapToggle ( ( data.qualify ? 'on' : 'off'));\n" .
              "  $('#gateway_edit_discard').val ( data.discard);\n" .
              "  $('#gateway_edit_minimum').val ( data.minimum);\n" .
              "  $('#gateway_edit_fraction').val ( data.fraction);\n" .
              "  $('#gateway_edit_tab_routes div.form-route').not ( '.hidden').remove ();\n" .
              "  $('#gateway_edit_tab_translations div.form-translation').not ( '.hidden').remove ();\n" .
              "  $('.btn-addroute,.btn-addtranslation').data ( 'id', 0);\n" .
              "  for ( var x in data.routes)\n" .
              "  {\n" .
              "    if ( data.routes.hasOwnProperty ( x))\n" .
              "    {\n" .
              "      $('.btn-addroute').trigger ( 'click');\n" .
              "      $('#gateway_edit_route_' + $('.btn-addroute').data ( 'id')).val ( data.routes[x].route);\n" .
              "      $('#gateway_edit_cost_' + $('.btn-addroute').data ( 'id')).val ( data.routes[x].cost);\n" .
              "    }\n" .
              "  }\n" .
              "  for ( var x in data.translations)\n" .
              "  {\n" .
              "    if ( data.translations.hasOwnProperty ( x))\n" .
              "    {\n" .
              "      $('.btn-addtranslation').trigger ( 'click');\n" .
              "      $('#gateway_edit_pattern_' + $('.btn-addtranslation').data ( 'id')).val ( data.translations[x].pattern);\n" .
              "      $('#gateway_edit_remove_' + $('.btn-addtranslation').data ( 'id')).val ( data.translations[x].remove);\n" .
              "      $('#gateway_edit_add_' + $('.btn-addtranslation').data ( 'id')).val ( data.translations[x].add);\n" .
              "    }\n" .
              "  }\n" .
              "  if ( $('.btn-addroute').data ( 'id') == 0)\n" .
              "  {\n" .
              "    $('.btn-addroute').trigger ( 'click');\n" .
              "  }\n" .
              "  if ( $('.btn-addtranslation').data ( 'id') == 0)\n" .
              "  {\n" .
              "    $('.btn-addtranslation').trigger ( 'click');\n" .
              "  }\n" .
              "  $('#gateway_edit_description').focus ();\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var gateway = VoIP.rest ( '/gateways/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( gateway.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#gateway_edit_form').trigger ( 'fill', gateway.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway edition") . "', text: '" . __ ( "Error viewing gateway!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n" .
              "$('#gateway_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/gateways/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Gateway edition") . "',\n" .
              "    fail: '" . __ ( "Error changing gateway!") . "',\n" .
              "    success: '" . __ ( "Gateway sucessfully changed!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/gateways', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}
?>
