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
 * VoIP Domain dialer module. This module add the resource to create a dialer to
 * automate the dialing process into a call center environment.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Dialer
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/campaigns", "campaign_search_page");
framework_add_hook ( "campaign_search_page", "campaign_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/campaigns/add", "campaign_add_page");
framework_add_hook ( "campaign_add_page", "campaign_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/campaigns/import", "campaign_import_page");
framework_add_hook ( "campaign_import_page", "campaign_import_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/campaigns/:id/view", "campaign_view_page");
framework_add_hook ( "campaign_view_page", "campaign_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/campaigns/:id/edit", "campaign_edit_page");
framework_add_hook ( "campaign_edit_page", "campaign_edit_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/campaigns/:id/report", "campaign_report_page");
framework_add_hook ( "campaign_report_page", "campaign_report_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/campaigns/:id/associate", "campaign_associate_page");
framework_add_hook ( "campaign_associate_page", "campaign_associate_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main dialer page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Dialer"));
  sys_set_subtitle ( __ ( "dialer campaigns"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Dialer"))
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
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Dialer search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Description") . "</th>\n";
  $output .= "      <th>" . __ ( "State") . "</th>\n";
  $output .= "      <th>" . __ ( "Registers") . "</th>\n";
  $output .= "      <th>" . __ ( "Actions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add dialer remove modal code
   */
  $output .= "<div id=\"campaign_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"campaign_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove dialer campaign") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the campaign %s (with %s entries)?"), "<span id=\"campaign_delete_description\"></span>", "<span id=\"campaign_delete_entries\"></span>") . "</p><input type=\"hidden\" id=\"campaign_delete_id\" value=\"\"></div>\n";
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
              "  data: VoIP.dataTables ( '/campaigns/fetch', 'GET'),\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 4 ]},\n" .
              "                { searchable: false, targets: [ 0, 4 ]},\n" .
              "                { data: 'links', render: function ( data, type, full) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/campaigns/' + full[0] + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Records") . "\" role=\"button\" title=\"\" href=\"/campaigns/' + full[0] + '/report\"><i class=\"fa fa-list-ol\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Associate") . "\" role=\"button\" title=\"\" href=\"/campaigns/' + full[0] + '/associate\"' + ( full[3] == 0 ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-play\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/campaigns/' + full[0] + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + full[0] + '\" data-description=\"' + full[1] + '\" data-entries=\"' + full[3] + '\"><i class=\"fa fa-times\"></i></button></span>'; }, targets: [ 4 ]},\n" .
              "                { visible: false, targets: [ 0 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '70%', class: 'export all'},\n" .
              "             { width: '10%', class: 'export min-tablet-l'},\n" .
              "             { width: '10%', class: 'export min-tablet-l'},\n" .
              "             { width: '10%', class: 'all'}\n" .
              "           ],\n" .
              "  rowCallback: function ( row, data, index)\n" .
              "               {\n" .
              "                 if ( data[2] == 'A')\n" .
              "                 {\n" .
              "                   $('td:eq(1)', row).html ( '<span class=\"label label-success\">" . __ ( "Active") . "</span>');\n" .
              "                 } else {\n" .
              "                   $('td:eq(1)', row).html ( '<span class=\"label label-danger\">" . __ ( "Inactive") . "</span>');\n" .
              "                 }\n" .
              "               }\n" .
              "}));\n" .
              "$('#fastsearch').val ( '');\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/campaigns/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'a.btn-primary', function ( e)\n" .
              "{\n" .
              "  if ( $(this).attr ( 'disabled') == 'disabled')\n" .
              "  {\n" .
              "    e && e.preventDefault ();\n" .
              "    e && e.stopPropagation ();\n" .
              "  }\n" .
              "});\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#campaign_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#campaign_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#campaign_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#campaign_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#campaign_delete_entries').html ( $(this).data ( 'entries'));\n" .
              "  $('#campaign_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#campaign_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var dialer = VoIP.rest ( '/campaigns/' + $('#campaign_delete_id').val (), 'DELETE');\n" .
              "  if ( dialer.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#campaign_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Dialer campaign removal") . "', text: '" . __ ( "Dialer campaign removed successfully!") . "', type: 'success'});\n" .
              "    $('#campaign_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Dialer campaign removal") . "', text: '" . __ ( "Error removing dialer campaign!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the dialer add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Dialer"));
  sys_set_subtitle ( __ ( "dialer campaign addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Dialer"), "link" => "/campaigns"),
    2 => array ( "title" => __ ( "Add"))
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
  $output = "<form class=\"form-horizontal\" id=\"campaign_add_form\">\n";

  // Add dialer description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"campaign_add_description\" placeholder=\"" . __ ( "Campaign description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add dialer state field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_add_state\" class=\"control-label col-xs-2\">" . __ ( "State") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"state\" class=\"form-control\" id=\"campaign_add_state\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/campaigns\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Add") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#campaign_add_state').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "'});\n" .
              "$('#campaign_add_description').focus ();\n" .
              "$('#campaign_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/campaigns',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Dialer campaign addition") . "',\n" .
              "    fail: '" . __ ( "Error adding campaign!") . "',\n" .
              "    success: '" . __ ( "Dialer campaign added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/campaigns', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the dialer view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Dialer"));
  sys_set_subtitle ( __ ( "dialer campaign view"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Dialer"), "link" => "/campaigns"),
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
  $output = "<form class=\"form-horizontal\" id=\"campaign_view_form\">\n";

  // Add dialer description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"campaign_view_description\" placeholder=\"" . __ ( "Campaign description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add dialer state field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_view_state\" class=\"control-label col-xs-2\">" . __ ( "State") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"state\" class=\"form-control\" id=\"campaign_view_state\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add dialer campaign selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_view_queue\" class=\"control-label col-xs-2\">" . __ ( "Queue") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"queue\" id=\"campaign_view_queue\" class=\"form-control\" data-placeholder=\"" . __ ( "Assoaciated queue") . "\" disabled=\"disabled\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/campaigns\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#campaign_view_state').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "'});\n" .
              "$('#campaign_view_queue').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/queues/search', 'GET')\n" .
              "});\n" .
              "var dialer = VoIP.rest ( '/campaigns/' + VoIP.parameters.id, 'GET');\n" .
              "if ( dialer.API.status === 'ok')\n" .
              "{\n" .
              "  $('#campaign_view_description').val ( dialer.result.description);\n" .
              "  $('#campaign_view_state').bootstrapToggle ( 'enable').bootstrapToggle ( ( dialer.result.state ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#campaign_view_queue').append ( $('<option>', { value: dialer.result.queue, text: dialer.result.queuedescription})).val ( dialer.result.queue).trigger ( 'change');\n" .
              "} else {\n" .
              "  new PNotify ( { title: '" . __ ( "Dialer campaign view") . "', text: '" . __ ( "Error viewing dialer campaign!") . "', type: 'error'});\n" .
              "}\n");

  return $output;
}

/**
 * Function to generate the dialer edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Dialer"));
  sys_set_subtitle ( __ ( "dialer editing"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Dialer"), "link" => "/campaigns"),
    2 => array ( "title" => __ ( "Edit"))
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
  $output = "<form class=\"form-horizontal\" id=\"campaign_edit_form\">\n";

  // Add dialer description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"campaign_edit_description\" placeholder=\"" . __ ( "Campaign description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add dialer state field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_edit_state\" class=\"control-label col-xs-2\">" . __ ( "State") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"state\" class=\"form-control\" id=\"campaign_edit_state\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/campaigns\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#campaign_edit_state').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "'});\n" .
              "var dialer = VoIP.rest ( '/campaigns/' + VoIP.parameters.id, 'GET');\n" .
              "if ( dialer.API.status === 'ok')\n" .
              "{\n" .
              "  $('#campaign_edit_description').val ( dialer.result.description);\n" .
              "  $('#campaign_edit_state').bootstrapToggle ( ( dialer.result.state ? 'on' : 'off'));\n" .
              "  $('#campaign_edit_description').focus ();\n" .
              "} else {\n" .
              "  new PNotify ( { title: '" . __ ( "Dialer campaign edition") . "', text: '" . __ ( "Error requesting dialer campaign data!") . "', type: 'error'});\n" .
              "}\n" .
              "$('#campaign_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/campaigns/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Dialer campaign edition") . "',\n" .
              "    fail: '" . __ ( "Error changing dialer campaign!") . "',\n" .
              "    success: '" . __ ( "Dialer campaign sucessfully changed!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/campaigns', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the dialer import page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_import_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Dialer"));
  sys_set_subtitle ( __ ( "dialer campaign import"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Dialer"), "link" => "/campaigns"),
    2 => array ( "title" => __ ( "Import"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/jquery-ui.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "jquery-fileupload", "src" => "/vendors/jQuery-File-Upload/css/jquery.fileupload.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/jquery-ui.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "JavaScript-Templates", "src" => "/vendors/JavaScript-Templates/js/tmpl.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-fileupload", "src" => "/vendors/jQuery-File-Upload/js/jquery.fileupload.js", "dep" => array ( "JavaScript-Templates", "jquery-ui")));
  sys_addjs ( array ( "name" => "jquery-iframe-transport", "src" => "/vendors/jQuery-File-Upload/js/jquery.iframe-transport.js", "dep" => array ( "jquery-fileupload")));
  sys_addjs ( array ( "name" => "jquery-fileupload-process", "src" => "/vendors/jQuery-File-Upload/js/jquery.fileupload-process.js", "dep" => array ( "jquery-fileupload")));
  sys_addjs ( array ( "name" => "jquery-fileupload-validate", "src" => "/vendors/jQuery-File-Upload/js/jquery.fileupload-validate.js", "dep" => array ( "jquery-fileupload", "jquery-fileupload-process")));
  sys_addjs ( array ( "name" => "jquery-fileupload-ui", "src" => "/vendors/jQuery-File-Upload/js/jquery.fileupload-ui.js", "dep" => array ( "jquery-fileupload", "jquery-fileupload-process")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"campaign_import_form\" method=\"POST\" enctype=\"multipart/form-data\">\n";

  // Add dialer campaign selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_import_campaign\" class=\"control-label col-xs-2\">" . __ ( "Campaign") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"campaign\" id=\"campaign_import_campaign\" class=\"form-control\" data-placeholder=\"" . __ ( "Dialer campaign") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add import field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_import_file\" class=\"control-label col-xs-2\">" . __ ( "Import") . "</label>\n";
  $output .= "    <div class=\"col-xs-6 fileupload-buttonbar\">\n";
  $output .= "      <span class=\"btn btn-success fileinput-button\"><i class=\"fa fa-plus\"></i> <span>" . __ ( "Add...") . "</span><input type=\"file\" name=\"files[]\" multiple></span>\n";
  $output .= "      <button type=\"submit\" class=\"btn btn-primary start\"><i class=\"fa fa-upload\"></i> <span>" . __ ( "Upload all") . "</span></button>\n";
  $output .= "      <button type=\"reset\" class=\"btn btn-warning cancel\"><i class=\"fa fa-ban\"></i> <span>" . __ ( "Cancel all") . "</span></button>\n";
  $output .= "      <span class=\"fileupload-process\"></span>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-xs-4 fileupload-progress fade\">\n";
  $output .= "      <div class=\"progress progress-striped active\" role=\"progressbar\" aria-valuemin=\"0\" aria-valuemax=\"100\">\n";
  $output .= "        <div class=\"progress-bar progress-bar-success\" style=\"width:0%;\"></div>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"progress-extended\">&nbsp;</div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add file upload list table
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\"><table role=\"presentation\" class=\"table table-striped\"><tbody class=\"files\"></tbody></table></div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/campaigns\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  // Report modal
  $output .= "<div id=\"reportModal\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"reportModal\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Campaign import report") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\" style=\"display: flow-root\">\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"campaign_import_filename\" class=\"control-label col-xs-2\">" . __ ( "Filename") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"text\" class=\"form-control\" id=\"campaign_import_filename\" disabled=\"disabled\">\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"campaign_import_size\" class=\"control-label col-xs-2\">" . __ ( "Size") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"text\" class=\"form-control\" id=\"campaign_import_size\" disabled=\"disabled\">\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"campaign_import_processed\" class=\"control-label col-xs-2\">" . __ ( "Processed") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"text\" class=\"form-control\" id=\"campaign_import_processed\" disabled=\"disabled\">\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"campaign_import_failed\" class=\"control-label col-xs-2\">" . __ ( "Failed") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"text\" class=\"form-control\" id=\"campaign_import_failed\" disabled=\"disabled\">\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"campaign_import_report\" class=\"control-label col-xs-2\">" . __ ( "Report") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <textarea class=\"form-control\" id=\"campaign_import_report\" style=\"resize: none\" disabled=\"disabled\"></textarea>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  // jQuery Upload Plugin template
  $output .= "<script id=\"template-upload\" type=\"text/x-tmpl\">\n";
  $output .= "{% for (var i = 0, file; file = o.files[i]; i++) { %}\n";
  $output .= "  <tr class=\"template-upload fade\">\n";
  $output .= "    <td>\n";
  $output .= "      <span class=\"preview\"></span>\n";
  $output .= "    </td>\n";
  $output .= "    <td>\n";
  $output .= "      <p class=\"name\">{%=file.name%}</p>\n";
  $output .= "      <strong class=\"error text-danger\"></strong>\n";
  $output .= "    </td>\n";
  $output .= "    <td>\n";
  $output .= "      <p class=\"size\">" . __ ( "Processing...") . "</p>\n";
  $output .= "      <div class=\"progress progress-striped active\" role=\"progressbar\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"0\"><div class=\"progress-bar progress-bar-success\" style=\"width:0%;\"></div></div>\n";
  $output .= "    </td>\n";
  $output .= "    <td>\n";
  $output .= "      {% if ( ! i && ! o.options.autoUpload) { %}\n";
  $output .= "        <button class=\"btn btn-primary start\" disabled><i class=\"fa fa-upload\"></i> <span>" . __ ( "Upload") . "</span></button>\n";
  $output .= "      {% } %}\n";
  $output .= "      {% if ( ! i) { %}\n";
  $output .= "        <button class=\"btn btn-warning cancel\"><i class=\"fa fa-ban\"></i> <span>" . __ ( "Cancel") . "</span></button>\n";
  $output .= "      {% } %}\n";
  $output .= "    </td>\n";
  $output .= "  </tr>\n";
  $output .= "{% } %}\n";
  $output .= "</script>\n";

  /**
   * Add import form JavaScript code
   */
  sys_addjs ( "$('#campaign_import_campaign').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/campaigns/search', 'GET')\n" .
              "});\n" .
              "$('#campaign_import_form').on ( 'submit', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "}).fileupload (\n" .
              "{\n" .
              "  url: '/api/campaigns/import',\n" .
              "  dataType: 'json',\n" .
              "  autoUpload: false,\n" .
              "  acceptFileTypes: /(\.|\/)(csv)$/i,\n" .
              "  maxFileSize: 16777216,\n" .
              "  downloadTemplateId: '',\n" .
              "  messages: {\n" .
              "    maxNumberOfFiles: '" . __ ( "Maximum number of files exceeded.") . "',\n" .
              "    acceptFileTypes: '" . __ ( "File type not allowed.") . "',\n" .
              "    maxFileSize: '" . __ ( "File is too large.") . "',\n" .
              "    minFileSize: '" . __ ( "File is too small.") . "',\n" .
              "    unknownError: '" . __ ( "Unknown error.") . "',\n" .
              "    uploadedBytes: '" . __ ( "Uploaded bytes exceed file size.") . "'\n" .
              "  },\n" .
              "  headers: {\n" .
              "    'X-INFramework': 'api',\n" .
              "    'X-HTTP-Method-Override': 'POST',\n" .
              "    'Accept': 'application/json'\n" .
              "  }\n" .
              "}).on ( 'fileuploadsubmit', function ( e, data)\n" .
              "{\n" .
              "  if ( $('#campaign_import_campaign').val () == '')\n" .
              "  {\n" .
              "    if ( $('tbody.files').find ( 'p.name').first ().html () == data.files[0].name)\n" .
              "    {\n" .
              "      new PNotify ( { title: '" . __ ( "Dialer campaign import") . "', text: '" . __ ( "You must select campaign first!") . "', type: 'error'});\n" .
              "    }\n" .
              "    $('tbody.files').find ( 'button.start').removeAttr ( 'disabled');\n" .
              "    return false;\n" .
              "  }\n" .
              "}).on ( 'fileuploaddone', function ( e, data)\n" .
              "{\n" .
              "  $(data.context).find ( 'div.progress-bar').fadeOut ();\n" .
              "  if ( data.result[0].error)\n" .
              "  {\n" .
              "    $(data.context).find ( 'strong.error').html ( data.result[0].error);\n" .
              "    $(data.context).find ( 'button.cancel').attr ( 'disabled', 'disabled');\n" .
              "    $(data.context).find ( 'button.start').removeClass ( 'start');\n" .
              "  } else {\n" .
              "    $(data.context).find ( 'button.cancel').parent ().append ( '<button class=\"btn btn-success report\" data-filename=\"' + data.result[0].name + '\" data-size=\"' + data.result[0].size + '\" data-processed=\"' + data.result[0].processed + '\" data-failed=\"' + data.result[0].failed + '\" data-report=\"' + data.result[0].report + '\"><i class=\"fa fa-th-list\"></i> " . __ ( "Report") . "</button>');\n" .
              "    $(data.context).find ( 'button.cancel').removeClass ( 'cancel btn-warning').addClass ( 'remove btn-info').html ( '<i class=\"fa fa-trash\"></i> <span>" . __ ( "Remove") . "</span>');\n" .
              "  }\n" .
              "  return false;\n" .
              "});\n" .
              "$('tbody.files').on ( 'click', 'button.remove', function ( e)\n" .
              "{\n" .
              "  $(this).closest ( 'tr').remove ();\n" .
              "});\n" .
              "$('tbody.files').on ( 'click', 'button.report', function ( e)\n" .
              "{\n" .
              "  $('#campaign_import_filename').val ( $(this).data ( 'filename'));\n" .
              "  $('#campaign_import_size').val ( $(this).data ( 'size'));\n" .
              "  $('#campaign_import_processed').val ( $(this).data ( 'processed'));\n" .
              "  $('#campaign_import_failed').val ( $(this).data ( 'failed'));\n" .
              "  $('#campaign_import_report').val ( atob ( $(this).data ( 'report')));\n" .
              "  $('#reportModal').modal ( 'show');\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the dialer campaign records report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Dialer"));
  sys_set_subtitle ( __ ( "dialer campaign records report"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Dialer"), "link" => "/campaigns"),
    2 => array ( "title" => __ ( "Records"))
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

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
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
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"campaign\" id=\"campaign\" class=\"form-control\" data-placeholder=\"" . __ ( "Campaign") . "\"><option value=\"\"></option></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Description") . "</th>\n";
  $output .= "      <th>" . __ ( "Grouper") . "</th>\n";
  $output .= "      <th>" . __ ( "Number") . "</th>\n";
  $output .= "      <th>" . __ ( "Tries") . "</th>\n";
  $output .= "      <th>" . __ ( "State") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Insert date") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#campaign').select2 (\n" .
              "{\n" .
              "  allowClear: false,\n" .
              "  data: VoIP.select2 ( '/campaigns/search', 'GET')\n" .
              "}).on ( 'select2:select, select2:unselect, change', function ()\n" .
              "{\n" .
              "  $('#filters').trigger ( 'submit');\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0 ]},\n" .
              "                { searchable: false, targets: [ 0, 6 ]},\n" .
              "                { visible: false, targets: [ 0, 6 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '30%', class: 'export min-tablet-l'},\n" .
              "             { width: '15%', class: 'export min-tablet-l'},\n" .
              "             { width: '20%', class: 'export all'},\n" .
              "             { width: '5%', class: 'export min-mobile-l'},\n" .
              "             { width: '5%', class: 'export min-mobile-l'},\n" .
              "             { class: 'never'},\n" .
              "             { width: '25%', class: 'export min-tablet-l', dataSort: 6}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>',\n" .
              "  rowCallback: function ( row, data, index)\n" .
              "               {\n" .
              "               }\n" .
              "}));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  var pathname = '/campaigns/' + $('#campaign').val () + '/report';\n" .
              "  if ( location.pathname != pathname)\n" .
              "  {\n" .
              "    history.pushState ( null, null, pathname);\n" .
              "  }\n" .
              "  var table = $('#report').data ( 'dt');\n" .
              "  table.processing ( true).clear ();\n" .
              "  var data = VoIP.rest ( '/campaigns/' + $('#campaign').val () + '/fetch', 'GET');\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    switch ( data.result[x][5])\n" .
              "    {\n" .
              "      case 'W':\n" .
              "        data.result[x][5] = '<span class=\"label label-warning\">" . __ ( "Waiting") . "</span>';\n" .
              "        break;\n" .
              "      case 'D':\n" .
              "        data.result[x][5] = '<span class=\"label label-primary\">" . __ ( "Dialing") . "</span>';\n" .
              "        break;\n" .
              "      case 'A':\n" .
              "        data.result[x][5] = '<span class=\"label label-info\">" . __ ( "Talking") . "</span>';\n" .
              "        break;\n" .
              "      case 'F':\n" .
              "        data.result[x][5] = '<span class=\"label label-success\">" . __ ( "Completed") . "</span>';\n" .
              "        break;\n" .
              "      case 'C':\n" .
              "        data.result[x][5] = '<span class=\"label label-default\">" . __ ( "Cancelled") . "</span>';\n" .
              "        break;\n" .
              "      case 'I':\n" .
              "        data.result[x][5] = '<span class=\"label label-danger\">" . __ ( "Invalid") . "</span>';\n" .
              "        break;\n" .
              "      default:\n" .
              "        data.result[x][5] = '<i>" . __ ( "N/A") . "</i>';\n" .
              "        break;\n" .
              "    }\n" .
              "    table.row.add ( data.result[x]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "});\n" .
              "$('#campaign').val ( VoIP.parameters.id).trigger ( 'change');\n");

  return $output;
}

/**
 * Function to generate the dialer campaign associate page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function campaign_associate_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Dialer"));
  sys_set_subtitle ( __ ( "dialer campaign associate"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Dialer"), "link" => "/campaigns"),
    2 => array ( "title" => __ ( "Associate"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"campaign_associate_form\">\n";

  // Add dialer description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_associate_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"campaign_associate_description\" placeholder=\"" . __ ( "Campaign description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add dialer campaign selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"campaign_associate_queue\" class=\"control-label col-xs-2\">" . __ ( "Queue") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"queue\" id=\"campaign_associate_queue\" class=\"form-control\" data-placeholder=\"" . __ ( "Queue to assoaciate") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/campaigns\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary associate ladda-button\" data-style=\"expand-left\">" . __ ( "Associate") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add associate form JavaScript code
   */
  sys_addjs ( "$('#campaign_associate_queue').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/queues/search', 'GET')\n" .
              "});\n" .
              "$('#campaign_associate_queue').focus ();\n" .
              "$('#campaign_associate_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/campaigns/' + VoIP.parameters.id + '/associate',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.associate'),\n" .
              "    title: '" . __ ( "Dialer queue association") . "',\n" .
              "    fail: '" . __ ( "Error associating dialer campaign!") . "',\n" .
              "    success: '" . __ ( "Dialer queue associated sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/campaigns', true);\n" .
              "               }\n" .
              "  }\n" .
              "});" .
              "var dialer = VoIP.rest ( '/campaigns/' + VoIP.parameters.id, 'GET');\n" .
              "if ( dialer.API.status === 'ok')\n" .
              "{\n" .
              "  $('#campaign_associate_description').val ( dialer.result.description);\n" .
              "  $('#campaign_associate_queue').append ( $('<option>', { value: dialer.result.queue, text: dialer.result.queuedescription})).val ( dialer.result.queue).trigger ( 'change');\n" .
              "} else {\n" .
              "  new PNotify ( { title: '" . __ ( "Dialer queue associate") . "', text: '" . __ ( "Error viewing dialer campaign!") . "', type: 'error'});\n" .
              "}\n");

  return $output;
}
?>
