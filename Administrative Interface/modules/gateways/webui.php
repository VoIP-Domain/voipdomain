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
 * VoIP Domain gateways module WebUI. This module add the gateways support.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Gateways
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/gateways", "gateways_search_page");
framework_add_hook ( "gateways_search_page", "gateways_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/gateways/add", "gateways_add_page");
framework_add_hook ( "gateways_add_page", "gateways_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/gateways/:id/clone", "gateways_clone_function");
framework_add_hook ( "gateways_clone_function", "gateways_clone_function", IN_HOOK_INSERT_FIRST);

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
   * Gateway search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add gateway remove modal code
   */
  $output .= "<div id=\"gateway_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"gateway_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
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
              "  columns: [\n" .
              "             { data: 'ID', title: '" . __ ( "Code") . "', width: '5%', class: 'export all', searchable: false},\n" .
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '70%', class: 'export all'},\n" .
              "             { data: 'Active', title: '" . __ ( "State") . "', width: '5%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return '<span class=\"label label-' + ( row.Active ? 'success' : 'danger') + '\">' + ( row.Active ? '" . __ ( "Active") . "' : '" . __ ( "Inactive") . "') + '</span>'; }, searchable: false},\n" .
              "             { data: 'Type', title: '" . __ ( "Type") . "', width: '5%', class: 'export min-tablet-l'},\n" .
              "             { data: 'Priority', title: '" . __ ( "Priority") . "', width: '5%', class: 'export min-tablet-l'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/gateways/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Report") . "\" role=\"button\" title=\"\" href=\"/gateways/' + row.ID + '/report\"><i class=\"fa fa-list\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/gateways/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/gateways/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-description=\"' + row.Description + '\" data-number=\"' + row.Number + '\"><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/gateways', fields: 'ID,Description,Active,Type,Priority,Number,NULL'}, $('#datatables').data ( 'dt'));\n" .
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
              "  VoIP.rest ( '/gateways/' + encodeURIComponent ( $('#gateway_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#gateway_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway removal") . "', text: '" . __ ( "Gateway sucessfully removed!") . "', type: 'success'});\n" .
              "    $('#gateway_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway removal") . "', text: '" . __ ( "Error removing gateway!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
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
  sys_addcss ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/css/tabdrop.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/js/bootstrap-tabdrop.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "sortable", "src" => "/vendors/Sortable/Sortable.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"gateway_add_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_advanced\">" . __ ( "Advanced") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_timing\">" . __ ( "Timming") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_routes\">" . __ ( "Routes") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_translations\">" . __ ( "Translations") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_files\">" . __ ( "Files") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"gateway_add_tab_basic\">\n";

  // Add gateway description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"gateway_add_description\" placeholder=\"" . __ ( "Gateway description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway activation option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_active\" class=\"control-label col-xs-2\">" . __ ( "State") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Active\" id=\"gateway_add_active\" value=\"true\" class=\"form-control\" checked=\"checked\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway type
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Type\" class=\"form-control\" id=\"gateway_add_type\">\n";
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
  $output .= "          <select name=\"Priority\" class=\"form-control\" id=\"gateway_add_priority\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"High\">" . __ ( "High") . "</option>\n";
  $output .= "            <option value=\"Medium\">" . __ ( "Medium") . "</option>\n";
  $output .= "            <option value=\"Low\">" . __ ( "Low") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway configuration type select field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_config\" class=\"control-label col-xs-2\">" . __ ( "Configuration") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Config\" id=\"gateway_add_config\" class=\"form-control\">\n";
  $output .= "            <option value=\"manual\">" . __ ( "Manual") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway number field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_number\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Number\" class=\"form-control\" id=\"gateway_add_number\" placeholder=\"" . __ ( "Gateway number (E.164 standard)") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway IP address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Address\" class=\"form-control\" id=\"gateway_add_address\" placeholder=\"" . __ ( "Gateway address") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway port field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_port\" class=\"control-label col-xs-2\">" . __ ( "Port") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Port\" class=\"form-control\" id=\"gateway_add_port\" placeholder=\"" . __ ( "Gateway port") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway user field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"gateway_add_username\" placeholder=\"" . __ ( "Gateway username") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Password\" class=\"form-control\" id=\"gateway_add_password\" placeholder=\"" . __ ( "Gateway password") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway currency
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_currency\" class=\"control-label col-xs-2\">" . __ ( "Currency") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Currency\" class=\"form-control\" id=\"gateway_add_currency\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Advanced options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_add_tab_advanced\">\n";

  // Add NAT option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_nat\" class=\"control-label col-xs-2\">" . __ ( "NAT") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"NAT\" id=\"gateway_add_nat\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add RPID option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_rpid\" class=\"control-label col-xs-2\">" . __ ( "Expose user") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"RPID\" id=\"gateway_add_rpid\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add qualify option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_qualify\" class=\"control-label col-xs-2\">" . __ ( "Qualify") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Qualify\" id=\"gateway_add_qualify\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Timing options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_add_tab_timing\">\n";

  // Add gateway discard time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_discard\" class=\"control-label col-xs-2\">" . __ ( "Discard time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Discard\" class=\"form-control\" id=\"gateway_add_discard\" placeholder=\"" . __ ( "Gateway discard time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway minimum time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_minimum\" class=\"control-label col-xs-2\">" . __ ( "Minimum fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Minimum\" class=\"form-control\" id=\"gateway_add_minimum\" placeholder=\"" . __ ( "Gateway minimum fare time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway time fraction field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_fraction\" class=\"control-label col-xs-2\">" . __ ( "Fraction fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Fraction\" class=\"form-control\" id=\"gateway_add_fraction\" placeholder=\"" . __ ( "Gateway fraction fare time") . "\" />\n";
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
  $output .= "              <input type=\"text\" name=\"Route__X_\" class=\"form-control\" id=\"gateway_add_route__X_\" placeholder=\"" . __ ( "Route mask (E.164 standard)") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"Cost__X_\" class=\"form-control\" id=\"gateway_add_cost__X_\" placeholder=\"" . __ ( "Cost (US\$/m)") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-delroute\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Remove route") . "\"><i class=\"fa fa-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new route button
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <div class=\"col-xs-12 add-route\">\n";
  $output .= "            <span class=\"input-icon-button btn btn-success btn-addroute pull-right\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Add route") . "\"><i class=\"fa fa-plus\"></i></span>\n";
  $output .= "          </div>\n";
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
  $output .= "              <input type=\"text\" name=\"Pattern__X_\" class=\"form-control\" id=\"gateway_add_pattern__X_\" placeholder=\"" . __ ( "Pattern (match content)") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"Remove__X_\" class=\"form-control\" id=\"gateway_add_remove__X_\" placeholder=\"" . __ ( "Content to remove") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"Add__X_\" class=\"form-control\" id=\"gateway_add_add__X_\" placeholder=\"" . __ ( "Content to add") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-deltranslation\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Remove translation") . "\"><i class=\"fa fa-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new translation button
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <div class=\"col-xs-12 add-translation\">\n";
  $output .= "            <span class=\"input-icon-button btn btn-success btn-addtranslation pull-right\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Add translation") . "\"><i class=\"fa fa-plus\"></i></span>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";

  // Fares file load panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_add_tab_files\">\n";

  // Add file selection field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_add_files\" class=\"control-label col-xs-2\">" . __ ( "Files") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\" style=\"display: flex\">\n";
  $output .= "          <select name=\"Files\" id=\"gateway_add_files\" class=\"form-control\" data-placeholder=\"" . __ ( "Fares files") . "\"><option value=\"\"></option></select>&nbsp;<button id=\"gateway_add_file_load\" class=\"btn btn-danger ladda-button\" data-style=\"pull-right\" disabled=\"disabled\">" . __ ( "Load") . "</button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add load button
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"col-xs-12\">\n";
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
  sys_addjs ( "$('.btn-addroute').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-route\">' + $('#gateway_add_tab_routes div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#gateway_add_tab_routes div.hidden')).removeClass ( 'hidden').find ( '.btn-delroute').tooltip ( { container: 'body'});\n" .
              "  $('#gateway_add_cost_' + $(this).data ( 'id')).mask ( '#.##0,00000', { reverse: true});\n" .
              "  $('#gateway_add_route_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('.btn-addtranslation').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-translation\">' + $('#gateway_add_tab_translations div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#gateway_add_tab_translations div.hidden')).removeClass ( 'hidden').find ( '.btn-deltranslation').tooltip ( { container: 'body'});\n" .
              "  $('#gateway_add_pattern_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('#gateway_add_tab_routes').on ( 'click', '.btn-delroute', function ()\n" .
              "{\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  if ( $('#gateway_add_tab_routes div.form-route').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-route').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_add_tab_translations').on ( 'click', '.btn-deltranslation', function ()\n" .
              "{\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  if ( $('#gateway_add_tab_translations div.form-translation').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-translation').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_add_files').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/gateways/fares', fields: 'ID,Name', formatText: '%Name%'})\n" .
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
              "  VoIP.rest ( '/gateways/fares/' + encodeURIComponent ( $('#gateway_add_files').val ()), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#gateway_add_tab_routes div.form-route').not ( '.hidden').remove ();\n" .
              "    $('#gateway_add_tab_translations div.form-translation').not ( '.hidden').remove ();\n" .
              "    $('.btn-addroute').data ( 'id', 0);\n" .
              "    $('.btn-addtranslation').data ( 'id', 0);\n" .
              "    for ( var index in data)\n" .
              "    {\n" .
              "      if ( index == 'routes')\n" .
              "      {\n" .
              "        for ( var x in data.routes)\n" .
              "        {\n" .
              "          $('.btn-addroute').trigger ( 'click');\n" .
              "          $('#gateway_add_route_' + $('.btn-addroute').data ( 'id')).val ( data.routes[x].Route);\n" .
              "          $('#gateway_add_cost' + $('.btn-addroute').data ( 'id')).val ( number_format ( data.routes[x].Cost, 5, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "        }\n" .
              "        continue;\n" .
              "      }\n" .
              "      if ( index == 'translations')\n" .
              "      {\n" .
              "        for ( var x in data.translations)\n" .
              "        {\n" .
              "          $('.btn-addtranslation').trigger ( 'click');\n" .
              "          $('#gateway_add_pattern_' + $('.btn-addtranslation').data ( 'id')).val ( data.translations[x].Pattern).trigger ( 'change');\n" .
              "          $('#gateway_add_remove_' + $('.btn-addtranslation').data ( 'id')).val ( data.translations[x].Remove).trigger ( 'change');\n" .
              "          $('#gateway_add_add_' + $('.btn-addtranslation').data ( 'id')).val ( data.translations[x].Add).trigger ( 'change');\n" .
              "        }\n" .
              "        continue;\n" .
              "      }\n" .
              "      if ( $('#gateway_add_form [name=\"' + index + '\"]').length == 1)\n" .
              "      {\n" .
              "        $('#gateway_add_form [name=\"' + index + '\"]').val ( data[index]).trigger ( 'change');\n" .
              "      } else {\n" .
              "        console.log ( 'Fare variable \"' + index + '\" with value \"' + data[index] + '\" not found!');\n" .
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
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway addition") . "', text: '" . __ ( "Error loading fares file!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n" .
              "$('#gateway_add_active').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "', onstyle: 'success', offstyle: 'danger'});\n" .
              "$('#gateway_add_type').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway type") . "' });\n" .
              "$('#gateway_add_priority').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway priority") . "' });\n" .
              "$('#gateway_add_currency').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  placeholder: '" . __ ( "Select the gateway currency") . "',\n" .
              "  width: '100%',\n" .
              "  data: VoIP.APIsearch ( { path: '/currencies', fields: 'Code,Symbol,Name', filter: function ( results, fields)\n" .
              "        {\n" .
              "          var result = new Array ();\n" .
              "          for ( var x = 0; x < results.length; x++)\n" .
              "          {\n" .
              "            result.push ( { id: results[x].Code, text: results[x].Symbol + ' (' + results[x].Name + ')'});\n" .
              "          }\n" .
              "          setTimeout ( function ()\n" .
              "          {\n" .
              "            $('#gateway_add_currency').val ( VoIP.getCurrency ()).trigger ( 'change');\n" .
              "          }, 100);\n" .
              "          return result;\n" .
              "        }\n" .
              "  })\n" .
              "});\n" .
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
              "$('#gateway_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#gateway_add_description'),\n" .
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
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#gateway_add_form').data ( 'formData');\n" .
              "  formData.Routes = new Array ();\n" .
              "  for ( var x = 1; x <= $('.btn-addroute').data ( 'id'); x++)\n" .
              "  {\n" .
              "    if ( $('#gateway_add_route_' + x).val ())\n" .
              "    {\n" .
              "      var tmp = new Object ();\n" .
              "      tmp.Reference = x;\n" .
              "      tmp.Route = $('#gateway_add_route_' + x).val ();\n" .
              "      tmp.Cost = parseFloat ( $('#gateway_add_cost_' + x).val ().replace ( /\\" . __ ( ",") . "/g, '').replace ( '" . __ ( ".") . "', '.'));\n" .
              "      formData.Routes.push ( tmp);\n" .
              "      delete ( tmp);\n" .
              "    }\n" .
              "    delete ( formData['Route_' + x]);\n" .
              "    delete ( formData['Cost_' + x]);\n" .
              "  }\n" .
              "  delete ( formData['Route__X_']);\n" .
              "  delete ( formData['Cost__X_']);\n" .
              "  formData.Translations = new Array ();\n" .
              "  for ( var x = 1; x <= $('.btn-addtranslation').data ( 'id'); x++)\n" .
              "  {\n" .
              "    if ( $('#gateway_add_pattern_' + x).val ())\n" .
              "    {\n" .
              "      var tmp = new Object ();\n" .
              "      tmp.Reference = x;\n" .
              "      tmp.Pattern = $('#gateway_add_pattern_' + x).val ();\n" .
              "      tmp.Remove = $('#gateway_add_remove_' + x).val ();\n" .
              "      tmp.Add = $('#gateway_add_add_' + x).val ();\n" .
              "      formData.Translations.push ( tmp);\n" .
              "      delete ( tmp);\n" .
              "    }\n" .
              "    delete ( formData['Pattern_' + x]);\n" .
              "    delete ( formData['Remove_' + x]);\n" .
              "    delete ( formData['Add_' + x]);\n" .
              "  }\n" .
              "  delete ( formData['Pattern__X_']);\n" .
              "  delete ( formData['Remove__X_']);\n" .
              "  delete ( formData['Add__X_']);\n" .
              "  $('#gateway_add_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}

/**
 * Function to generate the gateway clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/gateways/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/gateways/add', true, function ()\n" .
              "  {\n" .
              "    $('#gateway_add_description').val ( data.Description);\n" .
              "    $('#gateway_add_active').bootstrapToggle ( ( data.Active ? 'on' : 'off'));\n" .
              "    $('#gateway_add_type').val ( data.Type).trigger ( 'change');\n" .
              "    $('#gateway_add_priority').val ( data.PriorityEN).trigger ( 'change');\n" .
              "    $('#gateway_add_currency').val ( data.Currency).trigger ( 'change');\n" .
              "    $('#gateway_add_config').val ( data.Config).trigger ( 'change');\n" .
              "    $('#gateway_add_number').val ( data.Number);\n" .
              "    $('#gateway_add_address').val ( data.Address);\n" .
              "    $('#gateway_add_port').val ( data.Port);\n" .
              "    $('#gateway_add_username').val ( data.Username);\n" .
              "    $('#gateway_add_password').val ( data.Password);\n" .
              "    $('#gateway_add_nat').bootstrapToggle ( ( data.NAT ? 'on' : 'off'));\n" .
              "    $('#gateway_add_rpid').bootstrapToggle ( ( data.RPID ? 'on' : 'off'));\n" .
              "    $('#gateway_add_qualify').bootstrapToggle ( ( data.Qualify ? 'on' : 'off'));\n" .
              "    $('#gateway_add_discard').val ( data.Discard);\n" .
              "    $('#gateway_add_minimum').val ( data.Minimum);\n" .
              "    $('#gateway_add_fraction').val ( data.Fraction);\n" .
              "    $('#gateway_add_tab_routes div.form-route').not ( '.hidden').remove ();\n" .
              "    $('#gateway_add_tab_translations div.form-translation').not ( '.hidden').remove ();\n" .
              "    $('.btn-addroute,.btn-addtranslation').data ( 'id', 0);\n" .
              "    for ( var x in data.Routes)\n" .
              "    {\n" .
              "      if ( data.Routes.hasOwnProperty ( x))\n" .
              "      {\n" .
              "        $('.btn-addroute').trigger ( 'click');\n" .
              "        $('#gateway_add_route_' + $('.btn-addroute').data ( 'id')).val ( data.Routes[x].Route).trigger ( 'change');\n" .
              "        $('#gateway_add_cost_' + $('.btn-addroute').data ( 'id')).val ( number_format ( data.Routes[x].Cost, 5, '" . __ ( ".") . "', '" . __ ( ",") . "')).trigger ( 'change');\n" .
              "      }\n" .
              "    }\n" .
              "    for ( var x in data.Translations)\n" .
              "    {\n" .
              "      if ( data.Translations.hasOwnProperty ( x))\n" .
              "      {\n" .
              "        $('.btn-addtranslation').trigger ( 'click');\n" .
              "        $('#gateway_add_pattern_' + $('.btn-addtranslation').data ( 'id')).val ( data.Translations[x].Pattern).trigger ( 'change');\n" .
              "        $('#gateway_add_remove_' + $('.btn-addtranslation').data ( 'id')).val ( data.Translations[x].Remove).trigger ( 'change');\n" .
              "        $('#gateway_add_add_' + $('.btn-addtranslation').data ( 'id')).val ( data.Translations[x].Add).trigger ( 'change');\n" .
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
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Gateway cloning") . "', text: '" . __ ( "Error requesting gateway data!") . "', type: 'error'});\n" .
              "});\n");
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
  sys_addcss ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/css/tabdrop.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/js/bootstrap-tabdrop.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"gateway_view_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_advanced\">" . __ ( "Advanced") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_timing\">" . __ ( "Timming") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_routes\">" . __ ( "Routes") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_translations\">" . __ ( "Translations") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"gateway_view_tab_basic\">\n";

  // Add gateway description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"gateway_view_description\" placeholder=\"" . __ ( "Gateway description") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway activation option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_active\" class=\"control-label col-xs-2\">" . __ ( "State") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Active\" value=\"true\" id=\"gateway_view_active\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway type
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Type\" class=\"form-control\" id=\"gateway_view_type\" disabled=\"disabled\">\n";
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
  $output .= "          <select name=\"Priority\" class=\"form-control\" id=\"gateway_view_priority\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"High\">" . __ ( "High") . "</option>\n";
  $output .= "            <option value=\"Medium\">" . __ ( "Medium") . "</option>\n";
  $output .= "            <option value=\"Low\">" . __ ( "Low") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway configuration type select field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_config\" class=\"control-label col-xs-2\">" . __ ( "Configuration") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Config\" id=\"gateway_view_config\" class=\"form-control\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"manual\">" . __ ( "Manual") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway number field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_number\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Number\" class=\"form-control\" id=\"gateway_view_number\" placeholder=\"" . __ ( "Gateway number (E.164 standard)") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway IP address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Address\" class=\"form-control\" id=\"gateway_view_address\" placeholder=\"" . __ ( "Gateway address") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway port field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_port\" class=\"control-label col-xs-2\">" . __ ( "Port") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Port\" class=\"form-control\" id=\"gateway_view_port\" placeholder=\"" . __ ( "Gateway port") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway user field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"gateway_view_username\" placeholder=\"" . __ ( "Gateway username") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Password\" class=\"form-control\" id=\"gateway_view_password\" placeholder=\"" . __ ( "Gateway password") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway currency
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_currency\" class=\"control-label col-xs-2\">" . __ ( "Currency") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Currency\" class=\"form-control\" id=\"gateway_view_currency\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Advanced options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_view_tab_advanced\">\n";

  // Add NAT option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_nat\" class=\"control-label col-xs-2\">" . __ ( "NAT") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"NAT\" value=\"true\" id=\"gateway_view_nat\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add RPID option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_rpid\" class=\"control-label col-xs-2\">" . __ ( "Expose user") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"RPID\" value=\"true\" id=\"gateway_view_rpid\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add qualify option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_qualify\" class=\"control-label col-xs-2\">" . __ ( "Qualify") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Qualify\" value=\"true\" id=\"gateway_view_qualify\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Timing options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_view_tab_timing\">\n";

  // Add gateway discard time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_discard\" class=\"control-label col-xs-2\">" . __ ( "Discard time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Discard\" class=\"form-control\" id=\"gateway_view_discard\" placeholder=\"" . __ ( "Gateway discard time") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway minimum time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_minimum\" class=\"control-label col-xs-2\">" . __ ( "Minimum fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Minimum\" class=\"form-control\" id=\"gateway_view_minimum\" placeholder=\"" . __ ( "Gateway minimum fare time") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway time fraction field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_view_fraction\" class=\"control-label col-xs-2\">" . __ ( "Fraction fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Fraction\" class=\"form-control\" id=\"gateway_view_fraction\" placeholder=\"" . __ ( "Gateway fraction fare time") . "\" disabled=\"disabled\" />\n";
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
  $output .= "              <input type=\"text\" name=\"Route__X_\" class=\"form-control\" id=\"gateway_view_route__X_\" placeholder=\"" . __ ( "Route mask (E.164 standard)") . "\" disabled=\"disabled\" />\n";
  $output .= "              <input type=\"text\" name=\"Cost__X_\" class=\"form-control\" id=\"gateway_view_cost__X_\" placeholder=\"" . __ ( "Cost (US\$/m)") . "\" disabled=\"disabled\" />\n";
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
  $output .= "              <input type=\"text\" name=\"Pattern__X_\" class=\"form-control\" id=\"gateway_view_pattern__X_\" placeholder=\"" . __ ( "Pattern (match content)") . "\" disabled=\"disabled\" />\n";
  $output .= "              <input type=\"text\" name=\"Remove__X_\" class=\"form-control\" id=\"gateway_view_remove__X_\" placeholder=\"" . __ ( "Content to remove") . "\" disabled=\"disabled\" />\n";
  $output .= "              <input type=\"text\" name=\"Add__X_\" class=\"form-control\" id=\"gateway_view_add__X_\" placeholder=\"" . __ ( "Content to add") . "\" disabled=\"disabled\" />\n";
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
  sys_addjs ( "$('#gateway_view_active').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "', onstyle: 'success', offstyle: 'danger'});\n" .
              "$('#gateway_view_type').select2 ( { width: '100%', placeholder: '" . __ ( "Select the gateway type") . "' });\n" .
              "$('#gateway_view_priority').select2 ( { width: '100%', placeholder: '" . __ ( "Select the gateway priority") . "' });\n" .
              "$('#gateway_view_currency').select2 (\n" .
              "{\n" .
              "  placeholder: '" . __ ( "Select the gateway currency") . "',\n" .
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
              "$('#gateway_view_config').select2 ( { allowClear: true, placeholder: 'Selecione o tipo de configuração do gateway' });\n" .
              "$('#gateway_view_nat,#gateway_view_rpid,#gateway_view_qualify').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#gateway_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#gateway_view_description').val ( data.Description);\n" .
              "  $('#gateway_view_active').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.Active ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#gateway_view_type').val ( data.TypeEN).trigger ( 'change');\n" .
              "  $('#gateway_view_priority').val ( data.PriorityEN).trigger ( 'change');\n" .
              "  $('#gateway_view_currency').val ( data.Currency).trigger ( 'change');\n" .
              "  $('#gateway_view_config').val ( data.Config).trigger ( 'change');\n" .
              "  $('#gateway_view_number').val ( data.Number);\n" .
              "  $('#gateway_view_address').val ( data.Address);\n" .
              "  $('#gateway_view_port').val ( data.Port);\n" .
              "  $('#gateway_view_username').val ( data.Username);\n" .
              "  $('#gateway_view_password').val ( data.Password);\n" .
              "  $('#gateway_view_nat').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.NAT ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#gateway_view_rpid').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.RPID ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#gateway_view_qualify').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.Qualify ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#gateway_view_discard').val ( data.Discard);\n" .
              "  $('#gateway_view_minimum').val ( data.Minimum);\n" .
              "  $('#gateway_view_fraction').val ( data.Fraction);\n" .
              "  for ( var index in data.Routes)\n" .
              "  {\n" .
              "    if ( data.Routes.hasOwnProperty ( index))\n" .
              "    {\n" .
              "      $('<div class=\"form-group form-route\">' + $('#gateway_view_tab_routes div.hidden').html ().replace ( /_X_/g, index) + '</div>').insertBefore ( $('#gateway_view_tab_routes div.hidden')).removeClass ( 'hidden');\n" .
              "      $('#gateway_view_route_' + index).val ( data.Routes[index].Route).trigger ( 'change');\n" .
              "      $('#gateway_view_cost_' + index).val ( number_format ( data.Routes[index].Cost, 5, '" . __ ( ".") . "', '" . __ ( ",") . "')).trigger ( 'change');\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.Routes.length == 0)\n" .
              "  {\n" .
              "    $('<div class=\"form-group form-route\">' + $('#gateway_view_tab_routes div.hidden').html ().replace ( /_X_/g, '0') + '</div>').insertBefore ( $('#gateway_view_tab_routes div.hidden')).removeClass ( 'hidden');\n" .
              "  }\n" .
              "  for ( var index in data.Translations)\n" .
              "  {\n" .
              "    if ( data.Translations.hasOwnProperty ( index))\n" .
              "    {\n" .
              "      $('<div class=\"form-group form-translation\">' + $('#gateway_view_tab_translations div.hidden').html ().replace ( /_X_/g, index) + '</div>').insertBefore ( $('#gateway_view_tab_translations div.hidden')).removeClass ( 'hidden');\n" .
              "      $('#gateway_view_pattern_' + index).val ( data.Translations[index].Pattern).trigger ( 'change');\n" .
              "      $('#gateway_view_remove_' + index).val ( data.Translations[index].Remove).trigger ( 'change');\n" .
              "      $('#gateway_view_add_' + index).val ( data.Translations[index].Add).trigger ( 'change');\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.Translations.length == 0)\n" .
              "  {\n" .
              "    $('<div class=\"form-group form-translation\">' + $('#gateway_view_tab_translations div.hidden').html ().replace ( /_X_/g, '0') + '</div>').insertBefore ( $('#gateway_view_tab_translations div.hidden')).removeClass ( 'hidden');\n" .
              "  }\n" .
              "});\n" .
              "VoIP.rest ( '/gateways/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#gateway_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Gateway visualization") . "', text: '" . __ ( "Error viewing gateway!") . "', type: 'error'});\n" .
              "});\n");

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
  sys_addcss ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/css/tabdrop.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/js/bootstrap-tabdrop.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "sortable", "src" => "/vendors/Sortable/Sortable.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"gateway_edit_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_advanced\">" . __ ( "Advanced") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_timing\">" . __ ( "Timming") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_routes\">" . __ ( "Routes") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_translations\">" . __ ( "Translations") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_files\">" . __ ( "Files") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"gateway_edit_tab_basic\">\n";

  // Add gateway description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"gateway_edit_description\" placeholder=\"" . __ ( "Gateway description") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway activation option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_active\" class=\"control-label col-xs-2\">" . __ ( "State") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Active\" id=\"gateway_edit_active\" value=\"true\" class=\"form-control\" checked=\"checked\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway type
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Type\" class=\"form-control\" id=\"gateway_edit_type\">\n";
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
  $output .= "          <select name=\"Priority\" class=\"form-control\" id=\"gateway_edit_priority\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"High\">" . __ ( "High") . "</option>\n";
  $output .= "            <option value=\"Medium\">" . __ ( "Medium") . "</option>\n";
  $output .= "            <option value=\"Low\">" . __ ( "Low") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway configuration type select field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_config\" class=\"control-label col-xs-2\">" . __ ( "Configuration") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Config\" id=\"gateway_edit_config\" class=\"form-control\">\n";
  $output .= "            <option value=\"manual\">" . __ ( "Manual") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway number field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_number\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Number\" class=\"form-control\" id=\"gateway_edit_number\" placeholder=\"" . __ ( "Gateway number (E.164 standard)") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway IP address field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_address\" class=\"control-label col-xs-2\">" . __ ( "Address") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Address\" class=\"form-control\" id=\"gateway_edit_address\" placeholder=\"" . __ ( "Gateway address") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway port field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_port\" class=\"control-label col-xs-2\">" . __ ( "Port") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Port\" class=\"form-control\" id=\"gateway_edit_port\" placeholder=\"" . __ ( "Gateway port") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway user field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"gateway_edit_username\" placeholder=\"" . __ ( "Gateway username") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Password\" class=\"form-control\" id=\"gateway_edit_password\" placeholder=\"" . __ ( "Gateway password") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway currency
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_currency\" class=\"control-label col-xs-2\">" . __ ( "Currency") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Currency\" class=\"form-control\" id=\"gateway_edit_currency\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Advanced options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_edit_tab_advanced\">\n";

  // Add NAT option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_nat\" class=\"control-label col-xs-2\">" . __ ( "NAT") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"NAT\" id=\"gateway_edit_nat\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add RPID option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_rpid\" class=\"control-label col-xs-2\">" . __ ( "Expose user") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"RPID\" id=\"gateway_edit_rpid\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add qualify option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_qualify\" class=\"control-label col-xs-2\">" . __ ( "Qualify") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Qualify\" id=\"gateway_edit_qualify\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Timing options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_edit_tab_timing\">\n";

  // Add gateway discard time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_discard\" class=\"control-label col-xs-2\">" . __ ( "Discard time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Discard\" class=\"form-control\" id=\"gateway_edit_discard\" placeholder=\"" . __ ( "Gateway discard time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway minimum time field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_minimum\" class=\"control-label col-xs-2\">" . __ ( "Minimum fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Minimum\" class=\"form-control\" id=\"gateway_edit_minimum\" placeholder=\"" . __ ( "Gateway minimum fare time") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add gateway time fraction field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_fraction\" class=\"control-label col-xs-2\">" . __ ( "Fraction fare time") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Fraction\" class=\"form-control\" id=\"gateway_edit_fraction\" placeholder=\"" . __ ( "Gateway fraction fare time") . "\" />\n";
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
  $output .= "              <input type=\"text\" name=\"Route__X_\" class=\"form-control\" id=\"gateway_edit_route__X_\" placeholder=\"" . __ ( "Route mask (E.164 standard)") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"Cost__X_\" class=\"form-control\" id=\"gateway_edit_cost__X_\" placeholder=\"" . __ ( "Cost (US\$/m)") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-delroute\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Remove route") . "\"><i class=\"fa fa-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new route button
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <div class=\"col-xs-12 add-route\">\n";
  $output .= "            <span class=\"input-icon-button btn btn-success btn-addroute pull-right\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Add route") . "\"><i class=\"fa fa-plus\"></i></span>\n";
  $output .= "          </div>\n";
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
  $output .= "              <input type=\"text\" name=\"Pattern__X_\" class=\"form-control\" id=\"gateway_edit_pattern__X_\" placeholder=\"" . __ ( "Pattern (match content)") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"Remove__X_\" class=\"form-control\" id=\"gateway_edit_remove__X_\" placeholder=\"" . __ ( "Content to remove") . "\" />\n";
  $output .= "              <input type=\"text\" name=\"Add__X_\" class=\"form-control\" id=\"gateway_edit_add__X_\" placeholder=\"" . __ ( "Content to add") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-deltranslation\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Remove translation") . "\"><i class=\"fa fa-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new translation button
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <div class=\"col-xs-12 add-translation\">\n";
  $output .= "            <span class=\"input-icon-button btn btn-success btn-addtranslation pull-right\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Add translation") . "\"><i class=\"fa fa-plus\"></i></span>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";
  $output .= "    </div>\n";

  // Fares file load panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_edit_tab_files\">\n";

  // Add file selection field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"gateway_edit_files\" class=\"control-label col-xs-2\">" . __ ( "Files") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\" style=\"display: flex\">\n";
  $output .= "          <select name=\"Files\" id=\"gateway_edit_files\" class=\"form-control\" data-placeholder=\"" . __ ( "Fares files") . "\"><option value=\"\"></option></select>&nbsp;<button id=\"gateway_add_file_load\" class=\"btn btn-danger ladda-button\" data-style=\"pull-right\" disabled=\"disabled\">" . __ ( "Load") . "</button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add load button
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"col-xs-12\">\n";
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
              "  $('<div class=\"form-group form-route\">' + $('#gateway_edit_tab_routes div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#gateway_edit_tab_routes div.hidden')).removeClass ( 'hidden').find ( '.btn-delroute').tooltip ( { container: 'body'});\n" .
              "  $('#gateway_edit_cost_' + $(this).data ( 'id')).mask ( '#.##0,00000', { reverse: true});\n" .
              "  $('#gateway_edit_route_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('.btn-addtranslation').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-translation\">' + $('#gateway_edit_tab_translations div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#gateway_edit_tab_translations div.hidden')).removeClass ( 'hidden').find ( '.btn-deltranslation').tooltip ( { container: 'body'});\n" .
              "  $('#gateway_edit_pattern_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('#gateway_edit_tab_routes').on ( 'click', '.btn-delroute', function ()\n" .
              "{\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  if ( $('#gateway_edit_tab_routes div.form-route').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-route').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_edit_tab_translations').on ( 'click', '.btn-deltranslation', function ()\n" .
              "{\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  if ( $('#gateway_edit_tab_translations div.form-translation').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-translation').remove ();\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_edit_files').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/gateways/fares', fields: 'ID,Name', formatText: '%Name%'})\n" .
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
              "  VoIP.rest ( '/gateways/fares/' + encodeURIComponent ( $('#gateway_edit_files').val ()), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#gateway_edit_tab_routes div.form-route').not ( '.hidden').remove ();\n" .
              "    $('#gateway_edit_tab_translations div.form-translation').not ( '.hidden').remove ();\n" .
              "    $('.btn-addroute').data ( 'id', 0);\n" .
              "    $('.btn-addtranslation').data ( 'id', 0);\n" .
              "    for ( var index in data)\n" .
              "    {\n" .
              "      if ( index == 'Routes')\n" .
              "      {\n" .
              "        for ( var x in data.Routes)\n" .
              "        {\n" .
              "          $('.btn-addroute').trigger ( 'click');\n" .
              "          $('#gateway_edit_route_' + $('.btn-addroute').data ( 'id')).val ( data.Routes[x].Route).trigger ( 'change');\n" .
              "          $('#gateway_edit_cost_' + $('.btn-addroute').data ( 'id')).val ( number_format ( data.Routes[x].Cost, 5, '" . __ ( ".") . "', '" . __ ( ",") . "')).trigger ( 'change');\n" .
              "        }\n" .
              "        continue;\n" .
              "      }\n" .
              "      if ( index == 'Translations')\n" .
              "      {\n" .
              "        for ( var x in data.Translations)\n" .
              "        {\n" .
              "          $('.btn-addtranslation').trigger ( 'click');\n" .
              "          $('#gateway_edit_pattern_' + $('.btn-addtranslation').data ( 'id')).val ( data.Translations[x].Pattern).trigger ( 'change');\n" .
              "          $('#gateway_edit_remove_' + $('.btn-addroute').data ( 'id')).val ( data.Translations[x].Remove).trigger ( 'change');\n" .
              "          $('#gateway_edit_add_' + $('.btn-addroute').data ( 'id')).val ( data.Translations[x].Add).trigger ( 'change');\n" .
              "        }\n" .
              "        continue;\n" .
              "      }\n" .
              "      if ( $('#gateway_edit_form [name=\"' + index + '\"]').length == 1)\n" .
              "      {\n" .
              "        $('#gateway_edit_form [name=\"' + index + '\"]').val ( data[index]).trigger ( 'change');\n" .
              "      } else {\n" .
              "        console.log ( 'Fare variable \"' + index + '\" with value \"' + data[index] + '\" not found!');\n" .
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
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Gateway edition") . "', text: '" . __ ( "Error loading fares file!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n" .
              "$('#gateway_edit_active').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "', onstyle: 'success', offstyle: 'danger'});\n" .
              "$('#gateway_edit_type').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway type") . "' });\n" .
              "$('#gateway_edit_priority').select2 ( { allowClear: true, placeholder: '" . __ ( "Select the gateway priority") . "' });\n" .
              "$('#gateway_edit_currency').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  placeholder: '" . __ ( "Select the gateway currency") . "',\n" .
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
              "  $('#gateway_edit_description').val ( data.Description);\n" .
              "  $('#gateway_edit_active').bootstrapToggle ( ( data.Active ? 'on' : 'off'));\n" .
              "  $('#gateway_edit_type').val ( data.Type).trigger ( 'change');\n" .
              "  $('#gateway_edit_priority').val ( data.PriorityEN).trigger ( 'change');\n" .
              "  $('#gateway_edit_currency').val ( data.Currency).trigger ( 'change');\n" .
              "  $('#gateway_edit_config').val ( data.Config).trigger ( 'change');\n" .
              "  $('#gateway_edit_number').val ( data.Number);\n" .
              "  $('#gateway_edit_address').val ( data.Address);\n" .
              "  $('#gateway_edit_port').val ( data.Port);\n" .
              "  $('#gateway_edit_username').val ( data.Username);\n" .
              "  $('#gateway_edit_password').val ( data.Password);\n" .
              "  $('#gateway_edit_nat').bootstrapToggle ( ( data.NAT ? 'on' : 'off'));\n" .
              "  $('#gateway_edit_rpid').bootstrapToggle ( ( data.RPID ? 'on' : 'off'));\n" .
              "  $('#gateway_edit_qualify').bootstrapToggle ( ( data.Qualify ? 'on' : 'off'));\n" .
              "  $('#gateway_edit_discard').val ( data.Discard);\n" .
              "  $('#gateway_edit_minimum').val ( data.Minimum);\n" .
              "  $('#gateway_edit_fraction').val ( data.Fraction);\n" .
              "  $('#gateway_edit_tab_routes div.form-route').not ( '.hidden').remove ();\n" .
              "  $('#gateway_edit_tab_translations div.form-translation').not ( '.hidden').remove ();\n" .
              "  $('.btn-addroute,.btn-addtranslation').data ( 'id', 0);\n" .
              "  for ( var x in data.Routes)\n" .
              "  {\n" .
              "    if ( data.Routes.hasOwnProperty ( x))\n" .
              "    {\n" .
              "      $('.btn-addroute').trigger ( 'click');\n" .
              "      $('#gateway_edit_route_' + $('.btn-addroute').data ( 'id')).val ( data.Routes[x].Route).trigger ( 'change');\n" .
              "      $('#gateway_edit_cost_' + $('.btn-addroute').data ( 'id')).val ( number_format ( data.Routes[x].Cost, 5, '" . __ ( ".") . "', '" . __ ( ",") . "')).trigger ( 'change');\n" .
              "    }\n" .
              "  }\n" .
              "  for ( var x in data.Translations)\n" .
              "  {\n" .
              "    if ( data.Translations.hasOwnProperty ( x))\n" .
              "    {\n" .
              "      $('.btn-addtranslation').trigger ( 'click');\n" .
              "      $('#gateway_edit_pattern_' + $('.btn-addtranslation').data ( 'id')).val ( data.Translations[x].Pattern).trigger ( 'change');\n" .
              "      $('#gateway_edit_remove_' + $('.btn-addtranslation').data ( 'id')).val ( data.Translations[x].Remove).trigger ( 'change');\n" .
              "      $('#gateway_edit_add_' + $('.btn-addtranslation').data ( 'id')).val ( data.Translations[x].Add).trigger ( 'change');\n" .
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
              "VoIP.rest ( '/gateways/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#gateway_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Gateway edition") . "', text: '" . __ ( "Error viewing gateway!") . "', type: 'error'});\n" .
              "});\n" .
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
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#gateway_edit_form').data ( 'formData');\n" .
              "  formData.Routes = new Array ();\n" .
              "  for ( var x = 1; x <= $('.btn-addroute').data ( 'id'); x++)\n" .
              "  {\n" .
              "    if ( $('#gateway_edit_route_' + x).val ())\n" .
              "    {\n" .
              "      var tmp = new Object ();\n" .
              "      tmp.Reference = x;\n" .
              "      tmp.Route = $('#gateway_edit_route_' + x).val ();\n" .
              "      tmp.Cost = parseFloat ( $('#gateway_edit_cost_' + x).val ().replace ( /\\" . __ ( ",") . "/g, '').replace ( '" . __ ( ".") . "', '.'));\n" .
              "      formData.Routes.push ( tmp);\n" .
              "      delete ( tmp);\n" .
              "    }\n" .
              "    delete ( formData['Route_' + x]);\n" .
              "    delete ( formData['Cost_' + x]);\n" .
              "  }\n" .
              "  delete ( formData['Route__X_']);\n" .
              "  delete ( formData['Cost__X_']);\n" .
              "  formData.Translations = new Array ();\n" .
              "  for ( var x = 1; x <= $('.btn-addtranslation').data ( 'id'); x++)\n" .
              "  {\n" .
              "    if ( $('#gateway_edit_pattern_' + x).val ())\n" .
              "    {\n" .
              "      var tmp = new Object ();\n" .
              "      tmp.Reference = x;\n" .
              "      tmp.Pattern = $('#gateway_edit_pattern_' + x).val ();\n" .
              "      tmp.Remove = $('#gateway_edit_remove_' + x).val ();\n" .
              "      tmp.Add = $('#gateway_edit_add_' + x).val ();\n" .
              "      formData.Translations.push ( tmp);\n" .
              "      delete ( tmp);\n" .
              "    }\n" .
              "    delete ( formData['Pattern_' + x]);\n" .
              "    delete ( formData['Remove_' + x]);\n" .
              "    delete ( formData['Add_' + x]);\n" .
              "  }\n" .
              "  delete ( formData['Pattern__X_']);\n" .
              "  delete ( formData['Remove__X_']);\n" .
              "  delete ( formData['Add__X_']);\n" .
              "  $('#gateway_edit_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}

/**
 * Function to generate the gateway report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Gateways"));
  sys_set_subtitle ( __ ( "gateways report"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Gateways"), "link" => "/gateways"),
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
      "URL" => "'/gateways/' + encodeURIComponent ( VoIP.parameters.id) + '/report'",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}
?>
