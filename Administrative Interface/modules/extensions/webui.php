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
 * VoIP Domain extensions module WebUI. This module add the feature of
 * extensions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/extensions", "extensions_search_page");
framework_add_hook ( "extensions_search_page", "extensions_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/extensions/add", "extensions_add_page");
framework_add_hook ( "extensions_add_page", "extensions_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/extensions/:id/clone", "extensions_clone_function");
framework_add_hook ( "extensions_clone_function", "extensions_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/extensions/:id/view", "extensions_view_page");
framework_add_hook ( "extensions_view_page", "extensions_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/extensions/:id/edit", "extensions_edit_page");
framework_add_hook ( "extensions_edit_page", "extensions_edit_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/extensions/:id/report", "extensions_report_page");
framework_add_hook ( "extensions_report_page", "extensions_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main extensions page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Extensions"));
  sys_set_subtitle ( __ ( "extensions search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Extensions"))
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
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "accent-neutralise", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Extension search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add extension remove modal code
   */
  $output .= "<div id=\"extension_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"extension_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove extension") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove extension %s (%s)?"), "<span id=\"extension_delete_name\"></span>", "<span id=\"extension_delete_extension\"></span>") . "</p><input type=\"hidden\" id=\"extension_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Number', title: '" . __ ( "Extension") . "', width: '13%', class: 'export all'},\n" .
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '75%', class: 'export all'},\n" .
              "             { data: 'Type', title: '" . __ ( "Type") . "', width: '2%', class: 'export min-tablet-l center', render: function ( data, type, row, meta) { return '<span class=\"label label-' + VoIP.interface.objLabel ( row.Type) + '\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-value=\"' + row.Type + '\" data-original-title=\"' + VoIP.interface.objTextSingular ( row.Type) + '\" title=\"\"><i class=\"fa fa-' + VoIP.interface.objIcon ( row.Type) + '\"></i></span>'; }, orderable: false, searchable: false},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '15%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/extensions/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-success\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Report") . "\" role=\"button\" title=\"\" href=\"/extensions/' + row.ID + '/report\"><i class=\"fa fa-list\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/extensions/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/extensions/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-extension=\"' + row.Number + '\" data-name=\"' + row.Description + '\"><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/extensions', fields: 'ID,Number,Description,Type,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/extensions/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#extension_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#extension_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#extension_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#extension_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#extension_delete_extension').html ( $(this).data ( 'extension'));\n" .
              "  $('#extension_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#extension_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/extensions/' + encodeURIComponent ( $('#extension_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#extension_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Extension removal") . "', text: '" . __ ( "Extension removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#extension_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Extension removal") . "', text: '" . __ ( "Error removing extension!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the extension add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Extensions"));
  sys_set_subtitle ( __ ( "extensions addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Extensions"), "link" => "/extensions"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));

  /**
   * First, we call sub extension add hook's to populate tabs
   */
  $subpages = filters_call ( "extensions_add_subpages", array (), array ( "tabs" => array (), "js" => array ( "init" => array (), "onshow" => array ()), "html" => ""));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"extension_add_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs nav-pages\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\" data-type=\"\"><a class=\"nav-tablink\" href=\"#extension_add_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <li role=\"presentation\" style=\"display: none\" data-type=\"" . $tabinfo["type"] . "\"><a class=\"nav-tablink\" href=\"#extension_add_tab_" . $tab . "\">" . $tabinfo["label"] . "</a></li>\n";
  }
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"extension_add_tab_basic\">\n";

  // Add extension number field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_extension\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"Number\" class=\"form-control\" id=\"extension_add_number\" placeholder=\"" . __ ( "Extension number") . "\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-getnext ladda-button\" data-style=\"zoom-in\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Get next available number") . "\"><i class=\"fa fa-magic\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"extension_add_description\" placeholder=\"" . __ ( "Extension description") . "\" >\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension type selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Type\" id=\"extension_add_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension type") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Add extension type tabs
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"extension_add_tab_" . $tab . "\">\n";
    $output .= $tabinfo["html"];
    $output .= "    </div>\n";
  }
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/extensions\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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
   * Add other subpages html code
   */
  $output .= $subpages["html"];

  /**
   * Prepare onshow JavaScript code
   */
  $onshow = "";
  if ( sizeof ( $subpages["js"]["onshow"]) != 0)
  {
    $onshow .= "  switch ( $(this).val ())\n";
    $onshow .= "  {\n";
    foreach ( $subpages["js"]["onshow"] as $type => $js)
    {
      $onshow .= "    case '" . $type . "':\n";
      $onshow .= $js;
      $onshow .= "      break;\n";
    }
    $onshow .= "  }\n";
  }

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#extension_add_number').mask ( '0#').on ( 'change', function ()\n" .
              "{\n" .
              "  if ( $(this).val () == '')\n" .
              "  {\n" .
              "    $(this).alerts ( 'clear');\n" .
              "    return;\n" .
              "  }\n" .
              "  $(this).data ( 'checkavailability', $(this).val ());\n" .
              "  var that = this;\n" .
              "  VoIP.rest ( '/extensions/' + encodeURIComponent ( $(this).val ()) + '/inuse', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    if ( $(that).data ( 'checkavailability') == $(that).val ())\n" .
              "    {\n" .
              "      if ( jqXHR.status == 200)\n" .
              "      {\n" .
              "        $(that).alerts ( 'add', { message: '" . __ ( "Extension number already in use.") . "'});\n" .
              "      }\n" .
              "      if ( jqXHR.status == 404)\n" .
              "      {\n" .
              "        $(that).alerts ( 'clear');\n" .
              "      }\n" .
              "    }\n" .
              "  });\n" .
              "});\n" .
              "$('#extension_add_type').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.interface.objList ( 'extension_', true)\n" .
              "}).on ( 'select2:select', function ( event)\n" .
              "{\n" .
              "  $('#extension_add_form ul.nav-pages').find ( 'li[data-type!=\'\']').css ( 'display', 'none');\n" .
              "  $('#extension_add_form ul.nav-pages').find ( 'li[data-type=\'' + $(this).val () + '\']').css ( 'display', 'block');\n" .
              $onshow .
              "}).on ( 'select2:unselect', function ( event)\n" .
              "{\n" .
              "  $('#extension_add_form ul.nav-pages').find ( 'li[data-type!=\'\']').css ( 'display', 'none');\n" .
              "});\n" .
              "$('#extension_add_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('button.btn-getnext').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/extensions/nextnumber', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    if ( data.Number != 0)\n" .
              "    {\n" .
              "      $('#extension_add_number').val ( data.Number);\n" .
              "      $('#extension_add_description').focus ();\n" .
              "    } else {\n" .
              "      new PNotify ( { title: '" . __ ( "Add extension") . "', text: '" . __ ( "Error retrieving next available extension number!") . "', type: 'error'});\n" .
              "      $('#extension_add_number').focus ();\n" .
              "    }\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Add extension") . "', text: '" . __ ( "Error retrieving next available extension number!") . "', type: 'error'});\n" .
              "    $('#extension_add_number').focus ();\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n" .
              "$('#extension_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#extension_add_number'),\n" .
              "    URL: '/extensions',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Extension addition") . "',\n" .
              "    fail: '" . __ ( "Error adding extension!") . "',\n" .
              "    success: '" . __ ( "Extension added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/extensions', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  /**
   * Add subpages add form JavaScript code
   */
  foreach ( $subpages["js"]["init"] as $js)
  {
    sys_addjs ( $js);
  }

  return $output;
}

/**
 * Function to generate the extension clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * First, we call sub extension clone hook's to populate tabs
   */
  $subpages = filters_call ( "extensions_clone_subpages", array (), array ( "tabs" => array (), "js" => array ( "init" => array (), "onshow" => array ()), "html" => ""));

  /**
   * Prepare onshow JavaScript code
   */
  $onshow = "";
  if ( sizeof ( $subpages["js"]["onshow"]) != 0)
  {
    $onshow .= "    switch ( data.Type)\n";
    $onshow .= "    {\n";
    foreach ( $subpages["js"]["onshow"] as $type => $js)
    {
      $onshow .= "      case '" . $type . "':\n";
      $onshow .= $js;
      $onshow .= "        break;\n";
    }
    $onshow .= "    }\n";
  }

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/extensions/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/extensions/add', true, function ()\n" .
              "  {\n" .
              "    $('#extension_add_number').val ( data.Number);\n" .
              "    $('#extension_add_description').val ( data.Description);\n" .
              "    $('#extension_add_type').append ( $('<option>', { value: data.Type, text: VoIP.interface.objTextSingular ( 'extension_' + data.Type)})).val ( data.Type).trigger ( 'change');\n" .
              "    $('#extension_add_form ul.nav-pages').find ( 'li[data-type=\'' + data.Type + '\']').css ( 'display', 'block');\n" .
              $onshow .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Extension cloning") . "', text: '" . __ ( "Error requesting extension data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the extension view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Extensions"));
  sys_set_subtitle ( __ ( "extensions view"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Extensions"), "link" => "/extensions"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * First, we call sub extension view hook's to populate tabs
   */
  $subpages = filters_call ( "extensions_view_subpages", array (), array ( "tabs" => array (), "js" => array ( "init" => array (), "onshow" => array ()), "html" => ""));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"extension_view_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs nav-pages\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\" data-type=\"\"><a class=\"nav-tablink\" href=\"#extension_view_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <li role=\"presentation\" style=\"display: none\" data-type=\"" . $tabinfo["type"] . "\"><a class=\"nav-tablink\" href=\"#extension_view_tab_" . $tab . "\">" . $tabinfo["label"] . "</a></li>\n";
  }
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"extension_view_tab_basic\">\n";

  // Add extension number field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_number\" class=\"control-label col-xs-2\">" . __ ( "Extension") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Number\" class=\"form-control\" id=\"extension_view_number\" placeholder=\"" . __ ( "Extension number") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_description\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"extension_view_description\" placeholder=\"" . __ ( "Extension description") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension type selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Type\" id=\"extension_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Add extension type tabs
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"extension_view_tab_" . $tab . "\">\n";
    $output .= $tabinfo["html"];
    $output .= "    </div>\n";
  }
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/extensions\" alt=\"\">Retornar</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add other subpages html code
   */
  $output .= $subpages["html"];

  /**
   * Prepare onshow JavaScript code
   */
  $onshow = "";
  if ( sizeof ( $subpages["js"]["onshow"]) != 0)
  {
    $onshow .= "  switch ( data.Type)\n";
    $onshow .= "  {\n";
    foreach ( $subpages["js"]["onshow"] as $type => $js)
    {
      $onshow .= "    case '" . $type . "':\n";
      $onshow .= $js;
      $onshow .= "      break;\n";
    }
    $onshow .= "  }\n";
  }

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#extension_view_type').select2 ();\n" .
              "$('#extension_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#extension_view_number').val ( data.Number);\n" .
              "  $('#extension_view_description').val ( data.Description);\n" .
              "  $('#extension_view_type').append ( $('<option>', { value: data.Type, text: VoIP.interface.objTextSingular ( 'extension_' + data.Type)})).val ( data.Type).trigger ( 'change');\n" .
              "  $('#extension_view_form ul.nav-pages').find ( 'li[data-type=\'' + data.Type + '\']').css ( 'display', 'block');\n" .
              $onshow .
              "});\n" .
              "VoIP.rest ( '/extensions/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#extension_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Extension view") . "', text: '" . __ ( "Error viewing extension!") . "', type: 'error'});\n" .
              "});\n");

  /**
   * Add subpages view form JavaScript code
   */
  foreach ( $subpages["js"]["init"] as $js)
  {
    sys_addjs ( $js);
  }

  return $output;
}

/**
 * Function to generate the extension edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Extensions"));
  sys_set_subtitle ( __ ( "extensions edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Extensions"), "link" => "/extensions"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));

  /**
   * First, we call sub extension edit hook's to populate tabs
   */
  $subpages = filters_call ( "extensions_edit_subpages", array (), array ( "tabs" => array (), "js" => array ( "init" => array (), "onshow" => array ()), "html" => ""));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"extension_edit_form\">\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs nav-pages\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\" data-type=\"\"><a class=\"nav-tablink\" href=\"#extension_edit_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <li role=\"presentation\" style=\"display: none\" data-type=\"" . $tabinfo["type"] . "\"><a class=\"nav-tablink\" href=\"#extension_edit_tab_" . $tab . "\">" . $tabinfo["label"] . "</a></li>\n";
  }
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"extension_edit_tab_basic\">\n";

  // Add extension number field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_ndit_number\" class=\"control-label col-xs-2\">" . __ ( "Number") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Number\" class=\"form-control\" id=\"extension_edit_number\" placeholder=\"" . __ ( "Extension number") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"extension_edit_description\" placeholder=\"" . __ ( "Extension description") . "\" >\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension type selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Type\" id=\"extension_edit_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Add extension type tabs
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"extension_edit_tab_" . $tab . "\">\n";
    $output .= $tabinfo["html"];
    $output .= "    </div>\n";
  }
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/extensions\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add other subpages html code
   */
  $output .= $subpages["html"];

  /**
   * Prepare onshow JavaScript code
   */
  $onshow = "";
  if ( sizeof ( $subpages["js"]["onshow"]) != 0)
  {
    $onshow .= "  switch ( data.Type)\n";
    $onshow .= "  {\n";
    foreach ( $subpages["js"]["onshow"] as $type => $js)
    {
      $onshow .= "    case '" . $type . "':\n";
      $onshow .= $js;
      $onshow .= "      break;\n";
    }
    $onshow .= "  }\n";
  }

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#extension_edit_number').mask ( '0#').on ( 'change', function ()\n" .
              "{\n" .
              "  if ( $(this).val () == '' || $(this).data ( 'originalnumber') == $(this).val ())\n" .
              "  {\n" .
              "    $(this).alerts ( 'clear');\n" .
              "    return;\n" .
              "  }\n" .
              "  $(this).data ( 'checkavailability', $(this).val ());\n" .
              "  var that = this;\n" .
              "  VoIP.rest ( '/extensions/' + encodeURIComponent ( $(this).val ()) + '/inuse', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    if ( $(that).data ( 'checkavailability') == $(that).val ())\n" .
              "    {\n" .
              "      if ( jqXHR.status == 200)\n" .
              "      {\n" .
              "        $(that).alerts ( 'add', { message: '" . __ ( "Extension number already in use.") . "'});\n" .
              "      }\n" .
              "      if ( jqXHR.status == 404)\n" .
              "      {\n" .
              "        $(that).alerts ( 'clear');\n" .
              "      }\n" .
              "    }\n" .
              "  });\n" .
              "});\n" .
              "$('#extension_edit_type').select2 ();\n" .
              "$('#extension_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#extension_edit_number').data ( 'originalnumber', data.Number).val ( data.Number);\n" .
              "  $('#extension_edit_description').val ( data.Description);\n" .
              "  $('#extension_edit_type').append ( $('<option>', { value: data.Type, text: VoIP.interface.objTextSingular ( 'extension_' + data.Type)})).val ( data.Type).trigger ( 'change');\n" .
              "  $('#extension_edit_form ul.nav-pages').find ( 'li[data-type=\'' + data.Type + '\']').css ( 'display', 'block');\n" .
              $onshow .
              "  $('#extension_edit_number').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/extensions/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#extension_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Extension edition") . "', text: '" . __ ( "Error requesting extension data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#extension_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/extensions/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Extension edition") . "',\n" .
              "    fail: '" . __ ( "Error changing extension!") . "',\n" .
              "    success: '" . __ ( "Extension sucessfully changed!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/extensions', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  /**
   * Add subpages edit form JavaScript code
   */
  foreach ( $subpages["js"]["init"] as $js)
  {
    sys_addjs ( $js);
  }

  return $output;
}

/**
 * Function to generate the extension report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Extensions"));
  sys_set_subtitle ( __ ( "extensions report"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Extensions"), "link" => "/extensions"),
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
    "DataFilter" => "      data[x].ID = VoIP.parameters.id;\n" .
                    "      data[x].Flow = ( data[x].Destination.ExtensionID == VoIP.parameters.id ? 'in' : 'out');\n",
    "Endpoint" => array (
      "URL" => "'/extensions/' + encodeURIComponent ( VoIP.parameters.id) + '/report'",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}
?>
