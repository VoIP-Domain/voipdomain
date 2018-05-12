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
 * VoIP Domain reserves module. This module add the feature of key reserves
 * that rings in one or many reserves at same time when called. This is usefull
 * to service numbers inside a company, where many employees can answer the call.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Reserves
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/reserves", "reserves_search_page");
framework_add_hook ( "reserves_search_page", "reserves_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/reserves/add", "reserves_add_page");
framework_add_hook ( "reserves_add_page", "reserves_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/reserves/:id/view", "reserves_view_page");
framework_add_hook ( "reserves_view_page", "reserves_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/reserves/:id/edit", "reserves_edit_page");
framework_add_hook ( "reserves_edit_page", "reserves_edit_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/reserves/:id/report", "reserves_report_page");
framework_add_hook ( "reserves_report_page", "reserves_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main reserves page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reserves_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reserves"));
  sys_set_subtitle ( __ ( "reserves searching"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reserves"))
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
   * Reserve search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Description") . "</th>\n";
  $output .= "      <th>" . __ ( "Extension") . "</th>\n";
  $output .= "      <th>" . __ ( "Actions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add reserve remove modal code
   */
  $output .= "<div id=\"reserve_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"reserve_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Reserve removal") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the reserve %s (%s)?"), "<span id=\"reserve_delete_description\"></span>", "<span id=\"reserve_delete_extension\"></span>") . "</p><input type=\"hidden\" id=\"reserve_delete_id\" value=\"\"></div>\n";
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
              "  data: VoIP.dataTables ( '/reserves/fetch', 'GET'),\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 3 ]},\n" .
              "                { searchable: false, targets: [ 0, 3 ]},\n" .
              "                { data: 'links', render: function ( data, type, full) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info ladda-button\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/reserves/' + full[0] + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning ladda-button\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/reserves/' + full[0] + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><button class=\"btn btn-xs btn-danger ladda-button\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + full[0] + '\" data-description=\"' + full[1] + '\" data-extension=\"' + full[2] + '\"><i class=\"fa fa-times\"></i></button></span>'; }, targets: [ 3 ]},\n" .
              "                { visible: false, targets: [ 0 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '70%', class: 'export min-mobile-l'},\n" .
              "             { width: '20%', class: 'export all'},\n" .
              "             { width: '10%', class: 'all'}\n" .
              "           ]\n" .
              "}));\n" .
              "$('#fastsearch').val ( '');\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/reserves/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#reserve_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#reserve_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#reserve_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#reserve_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#reserve_delete_extension').html ( $(this).data ( 'extension'));\n" .
              "  $('#reserve_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#reserve_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var reserve = VoIP.rest ( '/reserves/' + $('#reserve_delete_id').val (), 'DELETE');\n" .
              "  if ( reserve.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#reserve_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Reserve removal") . "', text: '" . __ ( "Reserve removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#reserve_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Reserve removal") . "', text: '" . __ ( "Error removing reserve!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the reserve add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reserves_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reserves"));
  sys_set_subtitle ( __ ( "reserves addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reserves"), "link" => "/reserves"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"reserve_add_form\">\n";

  // Add reserve description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"reserve_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"reserve_add_description\" placeholder=\"" . __ ( "Reserve description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add reserve extension field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"reserve_add_extension\" class=\"control-label col-xs-2\">" . __ ( "Extension") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"extension\" class=\"form-control\" id=\"reserve_add_extension\" placeholder=\"" . __ ( "Reserve extension") . "\" data-mask=\"0#\" data-mask-maxlength=\"false\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/reserves\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Add") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#reserve_add_description').focus ();\n" .
              "$('#reserve_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/reserves',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Reserve addition") . "',\n" .
              "    fail: '" . __ ( "Error adding reserve!") . "',\n" .
              "    success: '" . __ ( "Reserved added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/reserves', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the reserve view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reserves_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reserves"));
  sys_set_subtitle ( __ ( "reserves viewing"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reserves"), "link" => "/reserves"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"reserve_view_form\">\n";

  // Add reserve description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"reserve_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"reserve_view_description\" placeholder=\"" . __ ( "Reserve description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add reserve extension field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"reserve_view_extension\" class=\"control-label col-xs-2\">" . __ ( "Extension") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"extension\" class=\"form-control\" id=\"reserve_view_extension\" placeholder=\"" . __ ( "Reserve extension") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/reserves\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#reserve_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#reserve_view_description').val ( data.description);\n" .
              "  $('#reserve_view_extension').val ( data.extension);\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var reserve = VoIP.rest ( '/reserves/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( reserve.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#reserve_view_form').trigger ( 'fill', reserve.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Reserve viewing") . "', text: '" . __ ( "Error viewing reserve!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n");

  return $output;
}

/**
 * Function to generate the reserve edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reserves_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reserves"));
  sys_set_subtitle ( __ ( "reserves edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reserves"), "link" => "/reserves"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"reserve_edit_form\">\n";

  // Add reserve description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"reserve_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"description\" class=\"form-control\" id=\"reserve_edit_description\" placeholder=\"" . __ ( "Reserve description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add reserve extension field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"reserve_edit_extension\" class=\"control-label col-xs-2\">" . __ ( "Extension") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"extension\" class=\"form-control\" id=\"reserve_edit_extension\" placeholder=\"" . __ ( "Reserve extension") . "\" data-mask=\"0#\" data-mask-maxlength=\"false\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/reserves\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#reserve_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#reserve_edit_description').val ( data.description);\n" .
              "  $('#reserve_edit_extension').val ( data.extension);\n" .
              "  $('#reserve_edit_description').focus ();\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var reserve = VoIP.rest ( '/reserves/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( reserve.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#reserve_edit_form').trigger ( 'fill', reserve.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Reserve edition") . "', text: '" . __ ( "Error requesting reserve data!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n" .
              "$('#reserve_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/reserves/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Reserve edition") . "',\n" .
              "    fail: '" . __ ( "Error changing reserve!") . "',\n" .
              "    success: '" . __ ( "Reserve changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/reserves', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}
?>
