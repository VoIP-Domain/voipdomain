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
 * VoIP Domain ranges module WebUI. This module manage the server number range
 * limits.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Ranges
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/ranges", "ranges_search_page");
framework_add_hook ( "ranges_search_page", "ranges_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/ranges/add", "ranges_add_page");
framework_add_hook ( "ranges_add_page", "ranges_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/ranges/:id/clone", "ranges_clone_function");
framework_add_hook ( "ranges_clone_function", "ranges_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/ranges/:id/view", "ranges_view_page");
framework_add_hook ( "ranges_view_page", "ranges_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/ranges/:id/edit", "ranges_edit_page");
framework_add_hook ( "ranges_edit_page", "ranges_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main ranges page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Ranges"));
  sys_set_subtitle ( __ ( "ranges searching"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Ranges"))
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
   * Range search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add range remove modal code
   */
  $output .= "<div id=\"range_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"range_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Range removal") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the range %s (from %s to %s)?"), "<span id=\"range_delete_description\"></span>", "<span id=\"range_delete_start\"></span>", "<span id=\"range_delete_finish\"></span>") . "</p><input type=\"hidden\" id=\"range_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '31%', class: 'export min-tablet-l'},\n" .
              "             { data: 'Server', title: '" . __ ( "Server") . "', width: '31%', class: 'export min-mobile-l'},\n" .
              "             { data: 'Start', title: '" . __ ( "Start") . "', width: '9%', class: 'export min-mobile-l'},\n" .
              "             { data: 'Finish', title: '" . __ ( "Finish") . "', width: '9%', class: 'export min-mobile-l'},\n" .
              "             { data: 'Extensions', title: '" . __ ( "In use") . "', width: '9%', class: 'export min-tablet-l'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/ranges/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/ranges/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/ranges/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-description=\"' + row.Description + '\" data-start=\"' + row.Server + '\" data-finish=\"' + row.Start + '\"' + ( row.Extensions != 0 ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/ranges', fields: 'ID,Description,Server,Start,Finish,Extensions,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/ranges/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#range_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#range_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#range_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#range_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#range_delete_start').html ( $(this).data ( 'start'));\n" .
              "  $('#range_delete_finish').html ( $(this).data ( 'finish'));\n" .
              "  $('#range_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#range_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/ranges/' + encodeURIComponent ( $('#range_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#range_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Range removal") . "', text: '" . __ ( "Range sucessfully removed!") . "', type: 'success'});\n" .
              "    $('#range_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Range removal") . "', text: '" . __ ( "Error removing range!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the range add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Ranges"));
  sys_set_subtitle ( __ ( "ranges addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Ranges"), "link" => "/ranges"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"range_add_form\">\n";

  // Add range description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"range_add_description\" placeholder=\"" . __ ( "Range description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add range server selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_add_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Server\" id=\"range_add_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Range server") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add range start field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_add_start\" class=\"control-label col-xs-2\">" . __ ( "Start") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Start\" class=\"form-control\" id=\"range_add_start\" placeholder=\"" . __ ( "Range start") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add range finish field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_add_finish\" class=\"control-label col-xs-2\">" . __ ( "Finish") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Finish\" class=\"form-control\" id=\"range_add_finish\" placeholder=\"" . __ ( "Range finish") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/ranges\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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
  sys_addjs ( "$('#range_add_server').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/servers', fields: 'ID,Description', formatText: '%Description%'})\n" .
              "});\n" .
              "$('#range_add_start').mask ( '0#');\n" .
              "$('#range_add_finish').mask ( '0#');\n" .
              "$('#range_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#range_add_description'),\n" .
              "    URL: '/ranges',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Range addition") . "',\n" .
              "    fail: '" . __ ( "Error adding range!") . "',\n" .
              "    success: '" . __ ( "Range added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/ranges', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the range clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/ranges/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/ranges/add', true, function ()\n" .
              "  {\n" .
              "    $('#range_add_description').val ( data.Description);\n" .
              "    $('#range_add_server').val ( data.Server).trigger ( 'change');\n" .
              "    $('#range_add_start').val ( data.Start);\n" .
              "    $('#range_add_finish').val ( data.Finish);\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Range cloning") . "', text: '" . __ ( "Error requesting range data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the range view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Ranges"));
  sys_set_subtitle ( __ ( "ranges visualization"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Ranges"), "link" => "/ranges"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"range_view_form\">\n";

  // Add range description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"range_view_description\" placeholder=\"" . __ ( "Range description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add range server selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Server\" id=\"range_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Range server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add range start field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_view_start\" class=\"control-label col-xs-2\">" . __ ( "Start") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Start\" class=\"form-control\" id=\"range_view_start\" placeholder=\"" . __ ( "Range start") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add range finish field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_view_finish\" class=\"control-label col-xs-2\">" . __ ( "Finish") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Finish\" class=\"form-control\" id=\"range_view_finish\" placeholder=\"" . __ ( "Range finish") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/ranges\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#range_view_server').select2 ();\n" .
              "$('#range_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#range_view_description').val ( data.Description);\n" .
              "  $('#range_view_server').append ( $('<option>', { value: data.Server, text: data.ServerDescription})).val ( data.Server).trigger ( 'change');\n" .
              "  $('#range_view_start').val ( data.Start);\n" .
              "  $('#range_view_finish').val ( data.Finish);\n" .
              "});\n" .
              "VoIP.rest ( '/ranges/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#range_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Range visualization") . "', text: '" . __ ( "Error viewing range!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the range edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Ranges"));
  sys_set_subtitle ( __ ( "ranges edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Ranges"), "link" => "/ranges"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"range_edit_form\">\n";

  // Add range description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"range_edit_description\" placeholder=\"" . __ ( "Range description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add range server selector
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_edit_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Server\" id=\"range_edit_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Range server") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add range start field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_edit_start\" class=\"control-label col-xs-2\">" . __ ( "Start") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Start\" class=\"form-control\" id=\"range_edit_start\" placeholder=\"" . __ ( "Range start") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add range finish field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"range_edit_finish\" class=\"control-label col-xs-2\">" . __ ( "Finish") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Finish\" class=\"form-control\" id=\"range_edit_finish\" placeholder=\"" . __ ( "Range finish") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/ranges\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#range_edit_server').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/servers', fields: 'ID,Description', formatText: '%Description%'})\n" .
              "});\n" .
              "$('#range_edit_start').mask ( '0#');\n" .
              "$('#range_edit_finish').mask ( '0#');\n" .
              "$('#range_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#range_edit_description').val ( data.Description);\n" .
              "  $('#range_edit_server').val ( data.Server).trigger ( 'change');\n" .
              "  $('#range_edit_start').val ( data.Start);\n" .
              "  $('#range_edit_finish').val ( data.Finish);\n" .
              "  $('#range_edit_description').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/ranges/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#range_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Range edition") . "', text: '" . __ ( "Error requesting range data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#range_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/ranges/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Range edition") . "',\n" .
              "    fail: '" . __ ( "Error changing range!") . "',\n" .
              "    success: '" . __ ( "Range changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/ranges', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}
?>
