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
 * VoIP Domain notifications module WebUI. This module add the feature to notify
 * system events to external endpoints.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Notifications
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/notifications", "notifications_search_page", array ( "permissions" => "administrator"));
framework_add_hook ( "notifications_search_page", "notifications_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/notifications/add", "notifications_add_page", array ( "permissions" => "administrator"));
framework_add_hook ( "notifications_add_page", "notifications_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/notifications/:id/clone", "notifications_clone_function", array ( "permissions" => "administrator"));
framework_add_hook ( "notifications_clone_function", "notifications_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/notifications/:id/view", "notifications_view_page", array ( "permissions" => "administrator"));
framework_add_hook ( "notifications_view_page", "notifications_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/notifications/:id/edit", "notifications_edit_page", array ( "permissions" => "administrator"));
framework_add_hook ( "notifications_edit_page", "notifications_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main notifications page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Notifications"));
  sys_set_subtitle ( __ ( "notifications search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Notifications"))
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
   * Notification search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add notification remove modal code
   */
  $output .= "<div id=\"notification_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"notification_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove notifications") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the notification %s (event \"%s\")?", true, false), "<span id=\"notification_delete_description\"></span>", "<span id=\"notification_delete_name\"></span>") . "</p><input type=\"hidden\" id=\"notification_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Event', title: '" . __ ( "Event") . "', width: '20%', class: 'export all'},\n" .
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '50%', class: 'export all'},\n" .
              "             { data: 'Expire', title: '" . __ ( "Validity") . "', width: '20%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return type === 'sort' ? row.ExpireTimestamp : ( moment ( row.Expire, 'YYYY-MM-DD').isValid () ? moment ( row.Expire, 'YYYY-MM-DD').format ( '" . __ ( "MM/DD/YYYY") . "') : '" . __ ( "N/A") . "'); }},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/notifications/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/notifications/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/notifications/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-name=\"' + row.Event + '\" data-description=\"' + row.Description + '\"><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/notifications', fields: 'ID,Event,Description,ExpireTimestamp,Expire,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/notifications/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#notification_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#notification_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#notification_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#notification_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#notification_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#notification_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#notification_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/notifications/' + encodeURIComponent ( $('#notification_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#notification_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Notification remove") . "', text: '" . __ ( "Notification removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#notification_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Notification remove") . "', text: '" . __ ( "Error removing notification!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the notification add page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_add_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Notifications"));
  sys_set_subtitle ( __ ( "notifications addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Notifications"), "link" => "/notifications"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "jquery-querybuilder", "src" => "/vendors/jQuery-QueryBuilder/dist/css/query-builder.default.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "interact.js", "src" => "/vendors/interact.js/packages/interactjs/dist/interact.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-extendext", "src" => "/vendors/jQuery.extendext/jquery-extendext.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-querybuilder", "src" => "/vendors/jQuery-QueryBuilder/dist/js/query-builder.js", "dep" => array ( "bootstrap", "jquery-extendext", "interact.js")));
  if ( $_in["general"]["language"] != "en_US" && is_readable ( dirname ( __FILE__) . "/../../vendors/jQuery-QueryBuilder/dist/i18n/query-builder." . str_replace ( "_", "-", $_in["general"]["language"]) . ".js"))
  {
    sys_addjs ( array ( "name" => "jquery-querybuilder-i18n", "src" => "/vendors/jQuery-QueryBuilder/dist/i18n/query-builder." . str_replace ( "_", "-", $_in["general"]["language"]) . ".js", "dep" => array ( "jquery-querybuilder")));
  }

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"notification_add_form\">\n";

  // Add notification description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"notification_add_description\" placeholder=\"" . __ ( "Notification description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification event selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_event\" class=\"control-label col-xs-2\">" . __ ( "Event") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Event\" id=\"notification_add_event\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification event") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification filters
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_filters\" class=\"control-label col-xs-2\">" . __ ( "Filters") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\" id=\"notification_add_filters\"></div>\n";
  $output .= "  </div>\n";

  // Add notification method selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_method\" class=\"control-label col-xs-2\">" . __ ( "Method") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Method\" id=\"notification_add_method\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification method") . "\">\n";
  $output .= "        <option value=\"\"></option>\n";
  $output .= "        <option value=\"GET\">GET</option>\n";
  $output .= "        <option value=\"POST\">POST</option>\n";
  $output .= "        <option value=\"PUT\">PUT</option>\n";
  $output .= "        <option value=\"DELETE\">DELETE</option>\n";
  $output .= "        <option value=\"HEAD\">HEAD</option>\n";
  $output .= "        <option value=\"OPTIONS\">OPTIONS</option>\n";
  $output .= "        <option value=\"TRACE\">TRACE</option>\n";
  $output .= "        <option value=\"CONNECT\">CONNECT</option>\n";
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification URL field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_url\" class=\"control-label col-xs-2\">" . __ ( "URL") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"url\" name=\"URL\" class=\"form-control\" id=\"notification_add_url\" placeholder=\"" . __ ( "Notification URL") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification data type selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_type\" class=\"control-label col-xs-2\">" . __ ( "Data type") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Type\" id=\"notification_add_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification data type") . "\">\n";
  $output .= "        <option value=\"\"></option>\n";
  $output .= "        <option value=\"JSON\">JSON</option>\n";
  $output .= "        <option value=\"FORM-DATA\">Form Data</option>\n";
  $output .= "        <option value=\"PHP\">PHP</option>\n";
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification variables fields
  $output .= "  <div class=\"form-group\" id=\"notification_add_variables\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Mapping") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-6 column-label\">" . __ ( "Value") . "</div>\n";
  $output .= "      <div class=\"col-xs-6 column-label\">" . __ ( "Variable") . "</div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification aditional headers fields
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Headers") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 column-label\">" . __ ( "Header") . "</div>\n";
  $output .= "      <div class=\"col-xs-7 column-label\">" . __ ( "Value") . "</div>\n";
  $output .= "      <div class=\"col-xs-1 column-label\"></div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "  <div class=\"form-group hide notification_add_header_group notification_add_header__ID_\" id=\"notification_add_header_template\">\n";
  $output .= "    <span class=\"col-xs-2\"></span>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4\">\n";
  $output .= "        <input type=\"text\" name=\"Header__ID_\" class=\"form-control\" id=\"notification_add_header__ID_\" placeholder=\"" . __ ( "Header variable") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-7\">\n";
  $output .= "        <input type=\"text\" name=\"Value__ID_\" class=\"form-control\" id=\"notification_add_value__ID_\" placeholder=\"" . __ ( "Value") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-1\" style=\"display: contents; white-space: nowrap;\">\n";
  $output .= "        <button class=\"btn btn-default btn-delheader\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Remove header") . "\"><i class=\"fa fa-minus\"></i></button>\n";
  $output .= "        <button class=\"btn btn-success btn-addheader\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Add header") . "\"><i class=\"fa fa-plus\"></i></button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification relax SSL switch
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_ssl\" class=\"control-label col-xs-2\">" . __ ( "Relax SSL?") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"SSL\" value=\"true\" id=\"notification_add_ssl\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification validity field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_validity\" class=\"control-label col-xs-2\">" . __ ( "Validity") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group date\">\n";
  $output .= "        <input name=\"Validity\" type=\"text\" id=\"notification_add_validity\" class=\"form-control\" placeholder=\"" . __ ( "Notification validity") . "\" maxlength=\"10\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-calendar\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Show calendar") . "\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/notifications\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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

  /*
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#notification_add_event').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/notifications/events', fields: 'ID,Event,Description', formatText: '%Event% (%Description%)'})\n" .
              "}).on ( 'change', function ()\n" .
              "{\n" .
              "  $('#notification_add_filters').queryBuilder ( 'destroy');\n" .
              "  $('#notification_add_form').find ( 'div.notification_add_variable').remove ();\n" .
              "  var filters = new Array ();\n" .
              "  if ( $(this).val () == '')\n" .
              "  {\n" .
              "    filters.push ( { id: ' ', label: '', type: 'integer' });\n" .
              "    $('#notification_add_filters').queryBuilder (\n" .
              "    {\n" .
              "      select_placeholder: '" . __ ( "Select field") . "',\n" .
              "      allow_empty: true,\n" .
              "      filters: filters,\n" .
              "      plugins:\n" .
              "      [\n" .
              "        'bt-tooltip-errors',\n" .
              "        'not-group',\n" .
              "        'sortable'\n" .
              "      ]\n" .
              "    });\n" .
              "    $('#notification_add_filters').find ( 'button, input, select').prop ( 'disabled', 'disabled');\n" .
              "    $('#notification_add_filters').find ( 'label').addClass ( 'disabled');\n" .
              "  } else {\n" .
              "    VoIP.rest ( '/notifications/events/' + encodeURIComponent ( $(this).val ()), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "    {\n" .
              "      for ( var id in data.Fields)\n" .
              "      {\n" .
              "        if ( ! data.Fields.hasOwnProperty ( id))\n" .
              "        {\n" .
              "          continue;\n" .
              "        }\n" .
              "        $('<div class=\"form-group notification_add_variable\"><span class=\"col-xs-2\"></span><div class=\"col-xs-10 col-nopadding\"><div class=\"col-xs-6\"><input type=\"text\" class=\"form-control\" value=\"' + data.Fields[id].Name + '\" disabled=\"disabled\"></div><div class=\"col-xs-6\"><input type=\"text\" name=\"Variable_' + data.Fields[id].Variable + '\" class=\"form-control\" placeholder=\"" . __ ( "Variable name") . "\" value=\"' + ( $('#notification_add_variables').data ( 'variables') && $('#notification_add_variables').data ( 'variables')[data.Fields[id].Variable] ? $('#notification_add_variables').data ( 'variables')[data.Fields[id].Variable] : data.Fields[id].Variable) + '\"></div></div></div>').insertAfter ( '#notification_add_variables');\n" .
              "        switch ( data.Fields[id].Type)\n" .
              "        {\n" .
              "          case 'datetime':\n" .
              "            filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'datetime', plugin: 'datetimepicker', plugin_config: { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/DD/YYYY") . " HH:mm:ss'}, input_event: 'dp.change'});\n" .
              "            break;\n" .
              "          case 'enum':\n" .
              "            filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'string', input: 'select', values: data.Fields[id].TypeEnum, plugin: 'select2', valueGetter: function ( rule) { return rule.\$el.find ( '.rule-input-container input').select2 ( 'select'); }});\n" .
              "            break;\n" .
              "          default:\n" .
              "            filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: data.Fields[id].Type });\n" .
              "            break;\n" .
              "        }\n" .
              "      }\n" .
              "      $('#notification_add_variables').removeData ( 'variables');\n" .
              "      $('#notification_add_filters').queryBuilder (\n" .
              "      {\n" .
              "        select_placeholder: '" . __ ( "Select field") . "',\n" .
              "        allow_empty: true,\n" .
              "        filters: filters,\n" .
              "        plugins:\n" .
              "        [\n" .
              "          'bt-tooltip-errors',\n" .
              "          'not-group',\n" .
              "          'sortable'\n" .
              "        ]\n" .
              "      });\n" .
              "    }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "    {\n" .
              "      new PNotify ( { title: '" . __ ( "Notification addition") . "', text: '" . __ ( "Error requesting notification structure data!") . "', type: 'error'});\n" .
              "    });\n" .
              "  }\n" .
              "}).trigger ( 'change');\n" .
              "$('#notification_add_method,#notification_add_type').select2 (\n" .
              "{\n" .
              "  allowClear: true\n" .
              "});\n" .
              "$('#notification_add_validity').mask ( '00/00/0000').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/DD/YYYY") . "'});\n" .
              "$('#notification_add_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('#notification_add_header_template').data ( 'regs', 0).on ( 'addReg', function ()\n" .
              "{\n" .
              "  $(this).data ( 'regs', $(this).data ( 'regs') + 1);\n" .
              "  var content = $('<div />').append ( $(this).clone ()).removeAttr ( 'id').removeClass ( 'hide').html ();\n" .
              "  $(content.replace ( /_ID_/mg, $(this).data ( 'regs'))).insertAfter ( $('.notification_add_header_group:last'));\n" .
              "  $('.notification_add_header_group').each ( function ( e)\n" .
              "  {\n" .
              "    $(this).find ( '.btn-addheader').addClass ( 'hide');\n" .
              "    $(this).find ( '.btn-delheader').removeAttr ( 'disabled');\n" .
              "  });\n" .
              "  if ( $('.notification_add_header_group').length == 2)\n" .
              "  {\n" .
              "    $('.notification_add_header_group:last').find ( '.btn-delheader').attr ( 'disabled', 'disabled');\n" .
              "  }\n" .
              "  $('.notification_add_header_' + $(this).data ( 'regs')).removeAttr ( 'id').removeClass ( 'hide').find ( '.btn-addheader').removeClass ( 'hide').tooltip ( { container: 'body'});\n" .
              "  $('.notification_add_header_' + $(this).data ( 'regs')).find ( '.btn-delheader').tooltip ( { container: 'body'});\n" .
              "});\n" .
              "$('#notification_add_form').on ( 'click', '.notification_add_header_group .btn-addheader', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  $('#notification_add_header_template').trigger ( 'addReg');\n" .
              "});\n" .
              "$('#notification_add_form').on ( 'click', '.btn-delheader', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  if ( $('.notification_add_header_group').length > 2)\n" .
              "  {\n" .
              "    $(this).parent ().parent ().parent ().remove ();\n" .
              "    $('.notification_add_header_group').each ( function ( e)\n" .
              "    {\n" .
              "      $(this).find ( '.btn-addheader').addClass ( 'hide');\n" .
              "      $(this).find ( '.btn-delheader').removeAttr ( 'disabled');\n" .
              "    });\n" .
              "    if ( $('.notification_add_header_group').length == 2)\n" .
              "    {\n" .
              "      $('.notification_add_header_group:last').find ( '.btn-delheader').attr ( 'disabled', 'disabled');\n" .
              "    }\n" .
              "    $('.notification_add_header_group:last').find ( '.btn-addheader').removeClass ( 'hide');\n" .
              "  }\n" .
              "});\n" .
              "$('#notification_add_header_template').trigger ( 'addReg');\n" .
              "$('#notification_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#notification_add_description'),\n" .
              "    URL: '/notifications',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Notification addition") . "',\n" .
              "    fail: '" . __ ( "Error adding notification!") . "',\n" .
              "    onvalidate: function ( formData)\n" .
              "                {\n" .
              "                  var filters = $('#notification_add_filters').queryBuilder ( 'getRules', { allow_invalid: true});\n" .
              "                  if ( filters.valid == false)\n" .
              "                  {\n" .
              "                    return false;\n" .
              "                  }\n" .
              "                  for ( var id in formData)\n" .
              "                  {\n" .
              "                    if ( id.substr ( 0, 24) == 'notification_add_filers_')\n" .
              "                    {\n" .
              "                      delete formData[id];\n" .
              "                    }\n" .
              "                  }\n" .
              "                  delete filters['valid'];\n" .
              "                  formData.Filters = filters;\n" .
              "                  return true;\n" .
              "                },\n" .
              "    success: '" . __ ( "Notification added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/notifications', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#notification_add_form').data ( 'formData');\n" .
              "  formData.Headers = new Array ();\n" .
              "  for ( var x = 1; x <= $('#notification_add_header_template').data ( 'regs'); x++)\n" .
              "  {\n" .
              "    if ( $('#notification_add_header_' + x).val ())\n" .
              "    {\n" .
              "      var tmp = new Object ();\n" .
              "      tmp.Reference = x;\n" .
              "      tmp.Header = $('#notification_add_header_' + x).val ();\n" .
              "      tmp.Value = $('#notification_add_value_' + x).val ();\n" .
              "      formData.Headers.push ( tmp);\n" .
              "      delete ( tmp);\n" .
              "    }\n" .
              "    delete ( formData['Header_' + x]);\n" .
              "    delete ( formData['Value_' + x]);\n" .
              "  }\n" .
              "  delete ( formData['Header__ID_']);\n" .
              "  delete ( formData['Value__ID_']);\n" .
              "  formData.Variables = new Object ();\n" .
              "  for ( var varname in formData)\n" .
              "  {\n" .
              "    if ( varname.substring ( 0, 9) == 'Variable_')\n" .
              "    {\n" .
              "      formData.Variables[varname.substring ( 9)] = formData[varname];\n" .
              "      delete ( formData[varname]);\n" .
              "    }\n" .
              "  }\n" .
              "  formData.Validity = moment ( formData.Validity, '" . __ ( "MM/DD/YYYY") . "').isValid () ? moment ( formData.Validity, '" . __ ( "MM/DD/YYYY") . "').format ( 'YYYY-MM-DD') : '';\n" .
              "  $('#notification_add_form').data ( 'formData', formData);\n" .
              "});");  

  return $output;
}

/**
 * Function to generate the notification clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/notifications/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/notifications/add', true, function ()\n" .
              "  {\n" .
              "    $('#notification_add_description').val ( data.Description);\n" .
              "    $('#notification_add_variables').data ( 'variables', data.Variables);\n" .
              "    $('#notification_add_event').val ( data.Event).trigger ( 'change');\n" .
              "    $('#notification_add_filters').queryBuilder ( 'destroy');\n" .
              "    var filters = new Array ();\n" .
              "    for ( var id in data.Fields)\n" .
              "    {\n" .
              "      if ( ! data.Fields.hasOwnProperty ( id))\n" .
              "      {\n" .
              "        continue;\n" .
              "      }\n" .
              "      switch ( data.Fields[id].Type)\n" .
              "      {\n" .
              "        case 'datetime':\n" .
              "          filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'datetime', plugin: 'datetimepicker', plugin_config: { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/DD/YYYY") . " HH:mm:ss'}, input_event: 'dp.change'});\n" .
              "          break;\n" .
              "        case 'enum':\n" .
              "          filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'string', input: 'select', values: data.Fields[id].TypeEnum, plugin: 'select2', valueGetter: function ( rule) { return rule.\$el.find ( '.rule-input-container input').select2 ( 'select'); }});\n" .
              "          break;\n" .
              "        default:\n" .
              "          filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: data.Fields[id].Type });\n" .
              "          break;\n" .
              "      }\n" .
              "    }\n" .
              "    $('#notification_add_filters').queryBuilder (\n" .
              "    {\n" .
              "      select_placeholder: '" . __ ( "Select field") . "',\n" .
              "      allow_empty: true,\n" .
              "      filters: filters,\n" .
              "      rules: data.Filters,\n" .
              "      plugins:\n" .
              "      [\n" .
              "        'bt-tooltip-errors',\n" .
              "        'not-group',\n" .
              "        'sortable'\n" .
              "      ]\n" .
              "    });\n" .
              "    $('#notification_add_method').val ( data.Method).trigger ( 'change');\n" .
              "    $('#notification_add_url').val ( data.URL);\n" .
              "    $('#notification_add_type').val ( data.Type).trigger ( 'change');\n" .
              "    for ( var id in data.Headers)\n" .
              "    {\n" .
              "      if ( ! data.Headers.hasOwnProperty ( id))\n" .
              "      {\n" .
              "        continue;\n" .
              "      }\n" .
              "      $('#notification_add_header_' + $('#notification_add_header_template').data ( 'regs')).val ( id);\n" .
              "      $('#notification_add_value_' + $('#notification_add_header_template').data ( 'regs')).val ( data.Headers[id]);\n" .
              "      $('#notification_add_header_template').trigger ( 'addReg');\n" .
              "    }\n" .
              "    if ( data.Headers.length != 0)\n" .
              "    $('#notification_add_headers').val ( data.Headers);\n" .
              "    {\n" .
              "      $('.notification_add_header_' + $('#notification_add_header_template').data ( 'regs')).find ( '.btn-delheader').trigger ( 'click');\n" .
              "    }\n" .
              "    $('#notification_add_ssl').bootstrapToggle ( data.SSL ? 'on' : 'off');\n" .
              "    $('#notification_add_validity').val ( moment ( data.Validity, 'YYYY-MM-DD').isValid () ? moment ( data.Validity, 'YYYY-MM-DD').format ( '" . __ ( "MM/DD/YYYY") . "') : '');\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Notification cloning") . "', text: '" . __ ( "Error requesting notification data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the notification view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Notifications"));
  sys_set_subtitle ( __ ( "notifications view"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Notifications"), "link" => "/notifications"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "jquery-querybuilder", "src" => "/vendors/jQuery-QueryBuilder/dist/css/query-builder.default.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "interact.js", "src" => "/vendors/interact.js/packages/interactjs/dist/interact.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-extendext", "src" => "/vendors/jQuery.extendext/jquery-extendext.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-querybuilder", "src" => "/vendors/jQuery-QueryBuilder/dist/js/query-builder.js", "dep" => array ( "bootstrap", "jquery-extendext", "interact.js")));
  if ( $_in["general"]["language"] != "en_US" && is_readable ( dirname ( __FILE__) . "/../../vendors/jQuery-QueryBuilder/dist/i18n/query-builder." . str_replace ( "_", "-", $_in["general"]["language"]) . ".js"))
  {
    sys_addjs ( array ( "name" => "jquery-querybuilder-i18n", "src" => "/vendors/jQuery-QueryBuilder/dist/i18n/query-builder." . str_replace ( "_", "-", $_in["general"]["language"]) . ".js", "dep" => array ( "jquery-querybuilder")));
  }

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"notification_view_form\">\n";

  // Add notification description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"notification_view_description\" placeholder=\"" . __ ( "Notification description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification event selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_event\" class=\"control-label col-xs-2\">" . __ ( "Event") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Event\" id=\"notification_view_event\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification event") . "\" disabled=\"disabled\"></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification filters
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_filters\" class=\"control-label col-xs-2\">" . __ ( "Filters") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\" id=\"notification_view_filters\"></div>\n";
  $output .= "  </div>\n";

  // Add notification method selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_method\" class=\"control-label col-xs-2\">" . __ ( "Method") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Method\" id=\"notification_view_method\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification method") . "\" disabled=\"disabled\">\n";
  $output .= "        <option value=\"\"></option>\n";
  $output .= "        <option value=\"GET\">GET</option>\n";
  $output .= "        <option value=\"POST\">POST</option>\n";
  $output .= "        <option value=\"PUT\">PUT</option>\n";
  $output .= "        <option value=\"DELETE\">DELETE</option>\n";
  $output .= "        <option value=\"HEAD\">HEAD</option>\n";
  $output .= "        <option value=\"OPTIONS\">OPTIONS</option>\n";
  $output .= "        <option value=\"TRACE\">TRACE</option>\n";
  $output .= "        <option value=\"CONNECT\">CONNECT</option>\n";
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification URL field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_url\" class=\"control-label col-xs-2\">" . __ ( "URL") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"url\" name=\"URL\" class=\"form-control\" id=\"notification_view_url\" placeholder=\"" . __ ( "Notification URL") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification data type selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_type\" class=\"control-label col-xs-2\">" . __ ( "Data type") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Type\" id=\"notification_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification data type") . "\" disabled=\"disabled\">\n";
  $output .= "        <option value=\"\"></option>\n";
  $output .= "        <option value=\"JSON\">JSON</option>\n";
  $output .= "        <option value=\"FORM-DATA\">Form Data</option>\n";
  $output .= "        <option value=\"PHP\">PHP</option>\n";
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification variables fields
  $output .= "  <div class=\"form-group\" id=\"notification_view_variables\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Mapping") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-6 column-label\">" . __ ( "Value") . "</div>\n";
  $output .= "      <div class=\"col-xs-6 column-label\">" . __ ( "Variable") . "</div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification aditional headers fields
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Headers") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 column-label\">" . __ ( "Header") . "</div>\n";
  $output .= "      <div class=\"col-xs-7 column-label\">" . __ ( "Value") . "</div>\n";
  $output .= "      <div class=\"col-xs-1 column-label\"></div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "  <div class=\"form-group hide notification_view_header_group\" id=\"notification_view_header_template\">\n";
  $output .= "    <span class=\"col-xs-2\"></span>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4\">\n";
  $output .= "        <input type=\"text\" name=\"Header__ID_\" class=\"form-control\" id=\"notification_view_header__ID_\" placeholder=\"" . __ ( "Header variable") . "\" disabled=\"disabled\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-8\">\n";
  $output .= "        <input type=\"text\" name=\"Value__ID_\" class=\"form-control\" id=\"notification_view_value__ID_\" placeholder=\"" . __ ( "Value") . "\" disabled=\"disabled\" />\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification relax SSL switch
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_ssl\" class=\"control-label col-xs-2\">" . __ ( "Relax SSL?") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"SSL\" value=\"true\" id=\"notification_view_ssl\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification validity field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_validity\" class=\"control-label col-xs-2\">" . __ ( "Validity") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Validity\" class=\"form-control\" id=\"notification_view_validity\" placeholder=\"" . __ ( "Notification validity") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/notifications\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#notification_view_event,#notification_view_method,#notification_view_type').select2 ();\n" .
              "$('#notification_view_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#notification_view_filters').queryBuilder (\n" .
              "{\n" .
              "  select_placeholder: '" . __ ( "Select field") . "',\n" .
              "  allow_empty: true,\n" .
              "  read_only: true,\n" .
              "  filters: [{ id: 'temp', label: '', type: 'integer' }],\n" .
              "  plugins:\n" .
              "  [\n" .
              "    'bt-tooltip-errors',\n" .
              "    'not-group',\n" .
              "    'sortable'\n" .
              "  ]\n" .
              "});\n" .
              "$('#notification_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#notification_view_description').val ( data.Description);\n" .
              "  $('#notification_view_event').append ( $('<option>', { value: data.Event, text: data.EventName})).val ( data.Event).trigger ( 'change');\n" .
              "  $('#notification_view_filters').queryBuilder ( 'destroy');\n" .
              "  var filters = new Array ();\n" .
              "  for ( var id in data.Fields)\n" .
              "  {\n" .
              "    if ( ! data.Fields.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    switch ( data.Fields[id].Type)\n" .
              "    {\n" .
              "      case 'datetime':\n" .
              "        filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'datetime', plugin: 'datetimepicker', plugin_config: { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/DD/YYYY") . " HH:mm:ss'}, input_event: 'dp.change'});\n" .
              "        break;\n" .
              "      case 'enum':\n" .
              "        filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'string', input: 'select', values: data.Fields[id].TypeEnum, plugin: 'select2', valueGetter: function ( rule) { return rule.\$el.find ( '.rule-input-container input').select2 ( 'select'); }});\n" .
              "        break;\n" .
              "      default:\n" .
              "        filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: data.Fields[id].Type });\n" .
              "        break;\n" .
              "    }\n" .
              "  }\n" .
              "  $('#notification_view_filters').queryBuilder (\n" .
              "  {\n" .
              "    select_placeholder: '" . __ ( "Select field") . "',\n" .
              "    allow_empty: true,\n" .
              "    filters: filters,\n" .
              "    rules: data.Filters,\n" .
              "    plugins:\n" .
              "    [\n" .
              "      'bt-tooltip-errors',\n" .
              "      'not-group',\n" .
              "      'sortable'\n" .
              "    ]\n" .
              "  });\n" .
              "  $('#notification_view_filters').find ( 'button, input, select').prop ( 'disabled', 'disabled');\n" .
              "  $('#notification_view_filters').find ( 'label').addClass ( 'disabled');\n" .
              "  interact.removeDocument ( document);\n" .
              "  $('#notification_view_method').val ( data.Method).trigger ( 'change');\n" .
              "  $('#notification_view_url').val ( data.URL);\n" .
              "  $('#notification_view_type').val ( data.Type).trigger ( 'change');\n" .
              "  for ( var id in data.Variables)\n" .
              "  {\n" .
              "    if ( ! data.Variables.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    $('<div class=\"form-group notification_view_variable\"><span class=\"col-xs-2\"></span><div class=\"col-xs-10 col-nopadding\"><div class=\"col-xs-6\"><input type=\"text\" class=\"form-control\" value=\"' + id + '\" disabled=\"disabled\"></div><div class=\"col-xs-6\"><input type=\"text\" name=\"Variable_' + id + '\" class=\"form-control\" placeholder=\"" . __ ( "Variable name") . "\" value=\"' + data.Variables[id] + '\" disabled=\"disabled\"></div></div></div>').insertAfter ( '#notification_view_variables');\n" .
              "  }\n" .
              "  var count = 0;\n" .
              "  for ( var id in data.Headers)\n" .
              "  {\n" .
              "    if ( ! data.Headers.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    count++;\n" .
              "    var content = $('<div />').append ( $('#notification_view_header_template').clone ()).html ();\n" .
              "    $(content.replace ( /_ID_/mg, count)).removeAttr ( 'id').removeClass ( 'hide').insertAfter ( $('.notification_view_header_group:last'));\n" .
              "    $('#notification_view_header_' + count).val ( id);\n" .
              "    $('#notification_view_value_' + count).val ( data.Headers[id]);\n" .
              "  }\n" .
              "  $('#notification_view_ssl').removeAttr ( 'disabled').bootstrapToggle ( data.SSL ? 'on' : 'off').attr ( 'disabled', 'disabled');\n" .
              "  $('#notification_view_validity').val ( data.validity);\n" .
              "});\n" .
              "VoIP.rest ( '/notifications/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#notification_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Notification view") . "', text: '" . __ ( "Error requesting notification data!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the notification edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Notifications"));
  sys_set_subtitle ( __ ( "notifications edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Notifications"), "link" => "/notifications"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "jquery-querybuilder", "src" => "/vendors/jQuery-QueryBuilder/dist/css/query-builder.default.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "interact.js", "src" => "/vendors/interact.js/packages/interactjs/dist/interact.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-extendext", "src" => "/vendors/jQuery.extendext/jquery-extendext.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-querybuilder", "src" => "/vendors/jQuery-QueryBuilder/dist/js/query-builder.js", "dep" => array ( "bootstrap", "jquery-extendext", "interact.js")));
  if ( $_in["general"]["language"] != "en_US" && is_readable ( dirname ( __FILE__) . "/../../vendors/jQuery-QueryBuilder/dist/i18n/query-builder." . str_replace ( "_", "-", $_in["general"]["language"]) . ".js"))
  {
    sys_addjs ( array ( "name" => "jquery-querybuilder-i18n", "src" => "/vendors/jQuery-QueryBuilder/dist/i18n/query-builder." . str_replace ( "_", "-", $_in["general"]["language"]) . ".js", "dep" => array ( "jquery-querybuilder")));
  }

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"notification_edit_form\">\n";

  // Add notification description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"notification_edit_description\" placeholder=\"" . __ ( "Notification description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification event selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_event\" class=\"control-label col-xs-2\">" . __ ( "Event") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Event\" id=\"notification_edit_event\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification event") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification filters
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_filters\" class=\"control-label col-xs-2\">" . __ ( "Filters") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\" id=\"notification_edit_filters\"></div>\n";
  $output .= "  </div>\n";

  // Add notification method selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_method\" class=\"control-label col-xs-2\">" . __ ( "Method") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Method\" id=\"notification_edit_method\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification method") . "\">\n";
  $output .= "        <option value=\"\"></option>\n";
  $output .= "        <option value=\"GET\">GET</option>\n";
  $output .= "        <option value=\"POST\">POST</option>\n";
  $output .= "        <option value=\"PUT\">PUT</option>\n";
  $output .= "        <option value=\"DELETE\">DELETE</option>\n";
  $output .= "        <option value=\"HEAD\">HEAD</option>\n";
  $output .= "        <option value=\"OPTIONS\">OPTIONS</option>\n";
  $output .= "        <option value=\"TRACE\">TRACE</option>\n";
  $output .= "        <option value=\"CONNECT\">CONNECT</option>\n";
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification URL field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_url\" class=\"control-label col-xs-2\">" . __ ( "URL") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"url\" name=\"URL\" class=\"form-control\" id=\"notification_edit_url\" placeholder=\"" . __ ( "Notification URL") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification data type selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_type\" class=\"control-label col-xs-2\">" . __ ( "Data type") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Type\" id=\"notification_edit_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification data type") . "\">\n";
  $output .= "        <option value=\"\"></option>\n";
  $output .= "        <option value=\"JSON\">JSON</option>\n";
  $output .= "        <option value=\"FORM-DATA\">Form Data</option>\n";
  $output .= "        <option value=\"PHP\">PHP</option>\n";
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification variables fields
  $output .= "  <div class=\"form-group\" id=\"notification_edit_variables\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Mapping") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-6 column-label\">" . __ ( "Value") . "</div>\n";
  $output .= "      <div class=\"col-xs-6 column-label\">" . __ ( "Variable") . "</div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification aditional headers fields
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Headers") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 column-label\">" . __ ( "Header") . "</div>\n";
  $output .= "      <div class=\"col-xs-7 column-label\">" . __ ( "Value") . "</div>\n";
  $output .= "      <div class=\"col-xs-1 column-label\"></div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "  <div class=\"form-group hide notification_edit_header_group notification_edit_header__ID_\" id=\"notification_edit_header_template\">\n";
  $output .= "    <span class=\"col-xs-2\"></span>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4\">\n";
  $output .= "        <input type=\"text\" name=\"Header__ID_\" class=\"form-control\" id=\"notification_edit_header__ID_\" placeholder=\"" . __ ( "Header variable") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-7\">\n";
  $output .= "        <input type=\"text\" name=\"Value__ID_\" class=\"form-control\" id=\"notification_edit_value__ID_\" placeholder=\"" . __ ( "Value") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-1\" style=\"display: contents; white-space: nowrap;\">\n";
  $output .= "        <button class=\"btn btn-default btn-delheader\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Remove header") . "\"><i class=\"fa fa-minus\"></i></button>\n";
  $output .= "        <button class=\"btn btn-success btn-addheader\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Add header") . "\"><i class=\"fa fa-plus\"></i></button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification relax SSL switch
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_ssl\" class=\"control-label col-xs-2\">" . __ ( "Relax SSL?") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"SSL\" value=\"true\" id=\"notification_edit_ssl\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification validity field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_validity\" class=\"control-label col-xs-2\">" . __ ( "Validity") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group date\">\n";
  $output .= "        <input name=\"Validity\" type=\"text\" id=\"notification_edit_validity\" class=\"form-control\" placeholder=\"" . __ ( "Notification validity") . "\" maxlength=\"10\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-calendar\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Show calendar") . "\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/notifications\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#notification_edit_event').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/notifications/events', fields: 'ID,Event,Description', formatText: '%Event% (%Description%)'})\n" .
              "}).on ( 'change', function ()\n" .
              "{\n" .
              "  $('#notification_edit_filters').queryBuilder ( 'destroy');\n" .
              "  $('#notification_edit_form').find ( 'div.notification_edit_variable').remove ();\n" .
              "  var filters = new Array ();\n" .
              "  if ( $(this).val () == '')\n" .
              "  {\n" .
              "    filters.push ( { id: ' ', label: '', type: 'integer' });\n" .
              "    $('#notification_edit_filters').queryBuilder (\n" .
              "    {\n" .
              "      select_placeholder: '" . __ ( "Select field") . "',\n" .
              "      allow_empty: true,\n" .
              "      filters: filters,\n" .
              "      plugins:\n" .
              "      [\n" .
              "        'bt-tooltip-errors',\n" .
              "        'not-group',\n" .
              "        'sortable'\n" .
              "      ]\n" .
              "    });\n" .
              "    $('#notification_edit_filters').find ( 'button, input, select').prop ( 'disabled', 'disabled');\n" .
              "    $('#notification_edit_filters').find ( 'label').addClass ( 'disabled');\n" .
              "  } else {\n" .
              "    VoIP.rest ( '/notifications/events/' + encodeURIComponent ( $(this).val ()), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "    {\n" .
              "      for ( var id in data.Fields)\n" .
              "      {\n" .
              "        if ( ! data.Fields.hasOwnProperty ( id))\n" .
              "        {\n" .
              "          continue;\n" .
              "        }\n" .
              "        $('<div class=\"form-group notification_edit_variable\"><span class=\"col-xs-2\"></span><div class=\"col-xs-10 col-nopadding\"><div class=\"col-xs-6\"><input type=\"text\" class=\"form-control\" value=\"' + data.Fields[id].Name + '\" disabled=\"disabled\"></div><div class=\"col-xs-6\"><input type=\"text\" name=\"Variable_' + data.Fields[id].Variable + '\" class=\"form-control\" placeholder=\"" . __ ( "Variable name") . "\" value=\"' + ( $('#notification_edit_variables').data ( 'variables') && $('#notification_edit_variables').data ( 'variables')[data.Fields[id].Variable] ? $('#notification_edit_variables').data ( 'variables')[data.Fields[id].Variable] : data.Fields[id].Variable) + '\"></div></div></div>').insertAfter ( '#notification_edit_variables');\n" .
              "        switch ( data.Fields[id].Type)\n" .
              "        {\n" .
              "          case 'datetime':\n" .
              "            filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'datetime', plugin: 'datetimepicker', plugin_config: { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/DD/YYYY") . " HH:mm:ss'}, input_event: 'dp.change'});\n" .
              "            break;\n" .
              "          case 'enum':\n" .
              "            filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'string', input: 'select', values: data.Fields[id].TypeEnum, plugin: 'select2', valueGetter: function ( rule) { return rule.\$el.find ( '.rule-input-container input').select2 ( 'select'); }});\n" .
              "            break;\n" .
              "          default:\n" .
              "            filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: data.Fields[id].Type });\n" .
              "            break;\n" .
              "        }\n" .
              "      }\n" .
              "      $('#notification_edit_variables').removeData ( 'variables');\n" .
              "      $('#notification_edit_filters').queryBuilder (\n" .
              "      {\n" .
              "        select_placeholder: '" . __ ( "Select field") . "',\n" .
              "        allow_empty: true,\n" .
              "        filters: filters,\n" .
              "        plugins:\n" .
              "        [\n" .
              "          'bt-tooltip-errors',\n" .
              "          'not-group',\n" .
              "          'sortable'\n" .
              "        ]\n" .
              "      });\n" .
              "    }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "    {\n" .
              "      new PNotify ( { title: '" . __ ( "Notification edition") . "', text: '" . __ ( "Error requesting notification structure data!") . "', type: 'error'});\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#notification_edit_filters').queryBuilder (\n" .
              "{\n" .
              "  select_placeholder: '" . __ ( "Select field") . "',\n" .
              "  allow_empty: true,\n" .
              "  filters: [{ id: ' ', label: '', type: 'integer' }],\n" .
              "  plugins:\n" .
              "  [\n" .
              "    'bt-tooltip-errors',\n" .
              "    'not-group',\n" .
              "    'sortable'\n" .
              "  ]\n" .
              "});\n" .
              "$('#notification_edit_filters').find ( 'button, input, select').prop ( 'disabled', 'disabled');\n" .
              "$('#notification_edit_filters').find ( 'label').addClass ( 'disabled');\n" .
              "$('#notification_edit_method,#notification_edit_type').select2 (\n" .
              "{\n" .
              "  allowClear: true\n" .
              "});\n" .
              "$('#notification_edit_validity').mask ( '00/00/0000').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/DD/YYYY") . "'});\n" .
              "$('#notification_edit_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('#notification_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#notification_edit_description').val ( data.Description);\n" .
              "  $('#notification_edit_variables').data ( 'variables', data.Variables);\n" .
              "  $('#notification_edit_event').val ( data.Event).trigger ( 'change');\n" .
              "  $('#notification_edit_filters').queryBuilder ( 'destroy');\n" .
              "  var filters = new Array ();\n" .
              "  for ( var id in data.Fields)\n" .
              "  {\n" .
              "    if ( ! data.Fields.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    switch ( data.Fields[id].Type)\n" .
              "    {\n" .
              "      case 'datetime':\n" .
              "        filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'datetime', plugin: 'datetimepicker', plugin_config: { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/DD/YYYY") . " HH:mm:ss'}, input_event: 'dp.change'});\n" .
              "        break;\n" .
              "      case 'enum':\n" .
              "        filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: 'string', input: 'select', values: data.Fields[id].TypeEnum, plugin: 'select2', valueGetter: function ( rule) { return rule.\$el.find ( '.rule-input-container input').select2 ( 'select'); }});\n" .
              "        break;\n" .
              "      default:\n" .
              "        filters.push ( { id: data.Fields[id].Variable, label: data.Fields[id].Name, type: data.Fields[id].Type });\n" .
              "        break;\n" .
              "    }\n" .
              "  }\n" .
              "  $('#notification_edit_filters').queryBuilder (\n" .
              "  {\n" .
              "    select_placeholder: '" . __ ( "Select field") . "',\n" .
              "    allow_empty: true,\n" .
              "    filters: filters,\n" .
              "    rules: data.Filters,\n" .
              "    plugins:\n" .
              "    [\n" .
              "      'bt-tooltip-errors',\n" .
              "      'not-group',\n" .
              "      'sortable'\n" .
              "    ]\n" .
              "  });\n" .
              "  $('#notification_edit_method').val ( data.Method).trigger ( 'change');\n" .
              "  $('#notification_edit_url').val ( data.URL);\n" .
              "  $('#notification_edit_type').val ( data.Type).trigger ( 'change');\n" .
              "  for ( var id in data.Headers)\n" .
              "  {\n" .
              "    if ( ! data.Headers.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    $('#notification_edit_header_template').trigger ( 'addReg');\n" .
              "    $('#notification_edit_header_' + $('#notification_edit_header_template').data ( 'regs')).val ( id);\n" .
              "    $('#notification_edit_value_' + $('#notification_edit_header_template').data ( 'regs')).val ( data.Headers[id]);\n" .
              "  }\n" .
              "  if ( data.Headers.length == 0)\n" .
              "  {\n" .
              "    $('#notification_edit_header_template').trigger ( 'addReg');\n" .
              "  }\n" .
              "  $('#notification_edit_headers').val ( data.Headers);\n" .
              "  $('#notification_edit_ssl').bootstrapToggle ( data.SSL ? 'on' : 'off');\n" .
              "  $('#notification_edit_validity').val ( moment ( data.Validity, 'YYYY-MM-DD').isValid () ? moment ( data.Validity, 'YYYY-MM-DD').format ( '" . __ ( "MM/DD/YYYY") . "') : '');\n" .
              "  $('#notification_edit_description').focus ();\n" .
              "});\n" .
              "$('#notification_edit_header_template').data ( 'regs', 0).on ( 'addReg', function ()\n" .
              "{\n" .
              "  $(this).data ( 'regs', $(this).data ( 'regs') + 1);\n" .
              "  var content = $('<div />').append ( $(this).clone ()).removeAttr ( 'id').removeClass ( 'hide').html ();\n" .
              "  $(content.replace ( /_ID_/mg, $(this).data ( 'regs'))).insertAfter ( $('.notification_edit_header_group:last'));\n" .
              "  $('.notification_edit_header_group').each ( function ( e)\n" .
              "  {\n" .
              "    $(this).find ( '.btn-addheader').addClass ( 'hide');\n" .
              "    $(this).find ( '.btn-delheader').removeAttr ( 'disabled');\n" .
              "  });\n" .
              "  if ( $('.notification_edit_header_group').length == 2)\n" .
              "  {\n" .
              "    $('.notification_edit_header_group:last').find ( '.btn-delheader').attr ( 'disabled', 'disabled');\n" .
              "  }\n" .
              "  $('.notification_edit_header_' + $(this).data ( 'regs')).removeAttr ( 'id').removeClass ( 'hide').find ( '.btn-addheader').removeClass ( 'hide').tooltip ( { container: 'body'});\n" .
              "  $('.notification_edit_header_' + $(this).data ( 'regs')).find ( '.btn-delheader').tooltip ( { container: 'body'});\n" .
              "});\n" .
              "$('#notification_edit_form').on ( 'click', '.notification_edit_header_group .btn-addheader', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  $('#notification_edit_header_template').trigger ( 'addReg');\n" .
              "});\n" .
              "$('#notification_edit_form').on ( 'click', '.btn-delheader', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  if ( $('.notification_edit_header_group').length > 2)\n" .
              "  {\n" .
              "    $(this).parent ().parent ().parent ().remove ();\n" .
              "    $('.notification_edit_header_group').each ( function ( e)\n" .
              "    {\n" .
              "      $(this).find ( '.btn-addheader').addClass ( 'hide');\n" .
              "      $(this).find ( '.btn-delheader').removeAttr ( 'disabled');\n" .
              "    });\n" .
              "    if ( $('.notification_edit_header_group').length == 2)\n" .
              "    {\n" .
              "      $('.notification_edit_header_group:last').find ( '.btn-delheader').attr ( 'disabled', 'disabled');\n" .
              "    }\n" .
              "    $('.notification_edit_header_group:last').find ( '.btn-addheader').removeClass ( 'hide');\n" .
              "  }\n" .
              "});\n" .
              "VoIP.rest ( '/notifications/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#notification_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Notification edition") . "', text: '" . __ ( "Error requesting notification data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#notification_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/notifications/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Notification edition") . "',\n" .
              "    fail: '" . __ ( "Error changing notification!") . "',\n" .
              "    onvalidate: function ( formData)\n" .
              "                {\n" .
              "                  var filters = $('#notification_edit_filters').queryBuilder ( 'getRules', { allow_invalid: true});\n" .
              "                  if ( filters.valid == false)\n" .
              "                  {\n" .
              "                    return false;\n" .
              "                  }\n" .
              "                  for ( var id in formData)\n" .
              "                  {\n" .
              "                    if ( id.substr ( 0, 23) == 'notification_edit_filters_')\n" .
              "                    {\n" .
              "                      delete formData[id];\n" .
              "                    }\n" .
              "                  }\n" .
              "                  delete filters['valid'];\n" .
              "                  formData.Filters = filters;\n" .
              "                  return true;\n" .
              "                },\n" .
              "    success: '" . __ ( "Notification changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/notifications', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#notification_edit_form').data ( 'formData');\n" .
              "  formData.Headers = new Array ();\n" .
              "  for ( var x = 1; x <= $('#notification_edit_header_template').data ( 'regs'); x++)\n" .
              "  {\n" .
              "    if ( $('#notification_edit_header_' + x).val ())\n" .
              "    {\n" .
              "      var tmp = new Object ();\n" .
              "      tmp.Reference = x;\n" .
              "      tmp.Header = $('#notification_edit_header_' + x).val ();\n" .
              "      tmp.Value = $('#notification_edit_value_' + x).val ();\n" .
              "      formData.Headers.push ( tmp);\n" .
              "      delete ( tmp);\n" .
              "    }\n" .
              "    delete ( formData['Header_' + x]);\n" .
              "    delete ( formData['Value_' + x]);\n" .
              "  }\n" .
              "  delete ( formData['Header__ID_']);\n" .
              "  delete ( formData['Value__ID_']);\n" .
              "  formData.Variables = new Object ();\n" .
              "  for ( var varname in formData)\n" .
              "  {\n" .
              "    if ( varname.substring ( 0, 31) == 'notification_edit_filters_rule_')\n" .
              "    {\n" .
              "      delete ( formData[varname]);\n" .
              "    }\n" .
              "    if ( varname.substring ( 0, 9) == 'Variable_')\n" .
              "    {\n" .
              "      formData.Variables[varname.substring ( 9)] = formData[varname];\n" .
              "      delete ( formData[varname]);\n" .
              "    }\n" .
              "  }\n" .
              "  formData.Validity = moment ( formData.Validity, '" . __ ( "MM/DD/YYYY") . "').isValid () ? moment ( formData.Validity, '" . __ ( "MM/DD/YYYY") . "').format ( 'YYYY-MM-DD') : '';\n" .
              "  $('#notification_edit_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}
?>
