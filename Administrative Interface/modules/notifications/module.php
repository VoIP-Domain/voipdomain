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
 * VoIP Domain notifications module. This module add the feature of notificationing incoming
 * public external calls. Usefull to notification tele marketing, spammers and other
 * annoying calls.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Notifications
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/notifications", "notifications_search_page", array ( "permissions" => "administrator"));
framework_add_hook ( "notifications_search_page", "notifications_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/notifications/add", "notifications_add_page", array ( "permissions" => "administrator"));
framework_add_hook ( "notifications_add_page", "notifications_add_page", IN_HOOK_INSERT_FIRST);

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
   * Notification search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Event") . "</th>\n";
  $output .= "      <th>" . __ ( "Description") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Validity") . "</th>\n";
  $output .= "      <th>" . __ ( "Actions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add notification remove modal code
   */
  $output .= "<div id=\"notification_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"notification_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
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
              "  data: VoIP.dataTables ( '/notifications/fetch', 'GET'),\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 5 ]},\n" .
              "                { searchable: false, targets: [ 0, 3, 5 ]},\n" .
              "                { data: 'links', render: function ( data, type, full) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/notifications/' + full[0] + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/notifications/' + full[0] + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + full[0] + '\" data-name=\"' + full[1] + '\" data-description=\"' + full[2] + '\"><i class=\"fa fa-times\"></i></button></span>'; }, targets: [ 5 ]},\n" .
              "                { visible: false, targets: [ 0, 3 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '20%', class: 'export all'},\n" .
              "             { width: '50%', class: 'export all'},\n" .
              "             { class: 'never'},\n" .
              "             { width: '20%', class: 'export min-tablet-l', dataSort: 2},\n" .
              "             { width: '10%', class: 'all'}\n" .
              "           ]\n" .
              "}));\n" .
              "$('#fastsearch').val ( '');\n" .
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
              "  var notification = VoIP.rest ( '/notifications/' + $('#notification_delete_id').val (), 'DELETE');\n" .
              "  if ( notification.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#notification_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Notification remove") . "', text: '" . __ ( "Notification removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#notification_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Notification remove") . "', text: '" . __ ( "Error removing notification!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the notification add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_add_page ( $buffer, $parameters)
{
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
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"notification_add_form\">\n";

  // Add notification description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"notification_add_description\" placeholder=\"" . __ ( "Notification description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification event selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_event\" class=\"control-label col-xs-2\">" . __ ( "Event") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"event\" id=\"notification_add_event\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification event") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification method selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_method\" class=\"control-label col-xs-2\">" . __ ( "Method") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"method\" id=\"notification_add_method\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification method") . "\">\n";
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
  $output .= "      <input type=\"text\" name=\"url\" class=\"form-control\" id=\"notification_add_url\" placeholder=\"" . __ ( "Notification URL") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification data type selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_type\" class=\"control-label col-xs-2\">" . __ ( "Data type") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"type\" id=\"notification_add_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification data type") . "\">\n";
  $output .= "        <option value=\"\"></option>\n";
  $output .= "        <option value=\"JSON\">JSON</option>\n";
  $output .= "        <option value=\"FORM-DATA\">Form Data</option>\n";
  $output .= "        <option value=\"PHP\">PHP</option>\n";
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification aditional headers textarea
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_headers\" class=\"control-label col-xs-2\">" . __ ( "Headers") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <textarea class=\"form-control\" name=\"headers\" id=\"notification_add_headers\" style=\"resize: none\"></textarea>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification relax SSL switch
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_ssl\" class=\"control-label col-xs-2\">" . __ ( "Relax SSL?") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"ssl\" id=\"notification_add_ssl\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification validity field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_add_validity\" class=\"control-label col-xs-2\">" . __ ( "Validity") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group date\">\n";
  $output .= "        <input name=\"validity\" type=\"text\" id=\"notification_add_validity\" class=\"form-control\" placeholder=\"" . __ ( "Notification validity") . "\" maxlength=\"10\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/notifications\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Add") . "</button>\n";
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
              "  data: VoIP.select2 ( '/notifications/events', 'GET')\n" .
              "});\n" .
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
              "$('#notification_add_description').focus ();\n" .
              "$('#notification_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/notifications',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Notification addition") . "',\n" .
              "    fail: '" . __ ( "Error adding notification!") . "',\n" .
              "    success: '" . __ ( "Notification added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/notifications', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");  

  return $output;
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

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"notification_view_form\">\n";

  // Add notification description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"notification_view_description\" placeholder=\"" . __ ( "Notification description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification event selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_event\" class=\"control-label col-xs-2\">" . __ ( "Event") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"event\" id=\"notification_view_event\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification event") . "\" disabled=\"disabled\"></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification method selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_method\" class=\"control-label col-xs-2\">" . __ ( "Method") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"method\" id=\"notification_view_method\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification method") . "\" disabled=\"disabled\">\n";
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
  $output .= "      <input type=\"text\" name=\"url\" class=\"form-control\" id=\"notification_view_url\" placeholder=\"" . __ ( "Notification URL") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification data type selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_type\" class=\"control-label col-xs-2\">" . __ ( "Data type") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"type\" id=\"notification_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification data type") . "\" disabled=\"disabled\">\n";
  $output .= "        <option value=\"\"></option>\n";
  $output .= "        <option value=\"JSON\">JSON</option>\n";
  $output .= "        <option value=\"FORM-DATA\">Form Data</option>\n";
  $output .= "        <option value=\"PHP\">PHP</option>\n";
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification aditional headers textarea
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_headers\" class=\"control-label col-xs-2\">" . __ ( "Headers") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <textarea class=\"form-control\" name=\"headers\" id=\"notification_view_headers\" style=\"resize: none\" disabled=\"disabled\"></textarea>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification relax SSL switch
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_ssl\" class=\"control-label col-xs-2\">" . __ ( "Relax SSL?") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"ssl\" id=\"notification_view_ssl\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification validity field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_view_validity\" class=\"control-label col-xs-2\">" . __ ( "Validity") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"validity\" class=\"form-control\" id=\"notification_view_validity\" placeholder=\"" . __ ( "Notification validity") . "\" disabled=\"disabled\" />\n";
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
              "$('#notification_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#notification_view_description').val ( data.description);\n" .
              "  $('#notification_view_event').append ( $('<option>', { value: data.event, text: data.eventname})).val ( data.event).trigger ( 'change');\n" .
              "  $('#notification_view_method').val ( data.method).trigger ( 'change');\n" .
              "  $('#notification_view_url').val ( data.url);\n" .
              "  $('#notification_view_type').val ( data.type).trigger ( 'change');\n" .
              "  $('#notification_view_headers').val ( data.headers);\n" .
              "  $('#notification_view_ssl').removeAttr ( 'disabled').bootstrapToggle ( data.ssl ? 'on' : 'off').attr ( 'disabled', 'disabled');\n" .
              "  $('#notification_view_validity').val ( data.validity);\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var data = VoIP.rest ( '/notifications/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( data.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#notification_view_form').trigger ( 'fill', data.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Notification view") . "', text: '" . __ ( "Error requesting notification data!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n");

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
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"notification_edit_form\">\n";

  // Add notification description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"notification_edit_description\" placeholder=\"" . __ ( "Notification description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification event selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_event\" class=\"control-label col-xs-2\">" . __ ( "Event") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"event\" id=\"notification_edit_event\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification event") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification method selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_method\" class=\"control-label col-xs-2\">" . __ ( "Method") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"method\" id=\"notification_edit_method\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification method") . "\">\n";
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
  $output .= "      <input type=\"text\" name=\"url\" class=\"form-control\" id=\"notification_edit_url\" placeholder=\"" . __ ( "Notification URL") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification data type selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_type\" class=\"control-label col-xs-2\">" . __ ( "Data type") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"type\" id=\"notification_edit_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Notification data type") . "\">\n";
  $output .= "        <option value=\"\"></option>\n";
  $output .= "        <option value=\"JSON\">JSON</option>\n";
  $output .= "        <option value=\"FORM-DATA\">Form Data</option>\n";
  $output .= "        <option value=\"PHP\">PHP</option>\n";
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification aditional headers textarea
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_headers\" class=\"control-label col-xs-2\">" . __ ( "Headers") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <textarea class=\"form-control\" name=\"headers\" id=\"notification_edit_headers\" style=\"resize: none\"></textarea>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification relax SSL switch
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_ssl\" class=\"control-label col-xs-2\">" . __ ( "Relax SSL?") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"ssl\" id=\"notification_edit_ssl\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add notification validity field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"notification_edit_validity\" class=\"control-label col-xs-2\">" . __ ( "Validity") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group date\">\n";
  $output .= "        <input name=\"validity\" type=\"text\" id=\"notification_edit_validity\" class=\"form-control\" placeholder=\"" . __ ( "Notification validity") . "\" maxlength=\"10\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
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
              "  data: VoIP.select2 ( '/notifications/events', 'GET')\n" .
              "});\n" .
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
              "  $('#notification_edit_description').val ( data.description);\n" .
              "  $('#notification_edit_event').val ( data.event).trigger ( 'change');\n" .
              "  $('#notification_edit_method').val ( data.method).trigger ( 'change');\n" .
              "  $('#notification_edit_url').val ( data.url);\n" .
              "  $('#notification_edit_type').val ( data.type).trigger ( 'change');\n" .
              "  $('#notification_edit_headers').val ( data.headers);\n" .
              "  $('#notification_edit_ssl').bootstrapToggle ( data.ssl ? 'on' : 'off');\n" .
              "  $('#notification_edit_validity').val ( data.validity);\n" .
              "  $('#notification_edit_description').focus ();\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var data = VoIP.rest ( '/notifications/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( data.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#notification_edit_form').trigger ( 'fill', data.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Notification edition") . "', text: '" . __ ( "Error requesting notification data!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n" .
              "$('#notification_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/notifications/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Notification edition") . "',\n" .
              "    fail: '" . __ ( "Error changing notification!") . "',\n" .
              "    success: '" . __ ( "Notification changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/notifications', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}
?>
