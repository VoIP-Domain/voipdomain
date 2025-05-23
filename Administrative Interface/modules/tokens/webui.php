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
 * VoIP Domain tokens module WebUI. This module add the feature to manage API
 * access tokens.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Tokens
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/tokens", "tokens_search_page", array ( "permissions" => "administrator"));
framework_add_hook ( "tokens_search_page", "tokens_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/tokens/add", "tokens_add_page", array ( "permissions" => "administrator"));
framework_add_hook ( "tokens_add_page", "tokens_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/tokens/:id/clone", "tokens_clone_function", array ( "permissions" => "administrator"));
framework_add_hook ( "tokens_clone_function", "tokens_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/tokens/:id/view", "tokens_view_page", array ( "permissions" => "administrator"));
framework_add_hook ( "tokens_view_page", "tokens_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/tokens/:id/edit", "tokens_edit_page", array ( "permissions" => "administrator"));
framework_add_hook ( "tokens_edit_page", "tokens_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main tokens page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Tokens"));
  sys_set_subtitle ( __ ( "tokens search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Tokens"))
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
   * Token search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add token remove modal code
   */
  $output .= "<div id=\"token_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"token_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove tokens") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the token %s (expires %s)?"), "<span id=\"token_delete_description\"></span>", "<span id=\"token_delete_validity\"></span>") . "</p><input type=\"hidden\" id=\"token_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '60%', class: 'export all'},\n" .
              "             { data: 'Expire', title: '" . __ ( "Validity") . "', width: '30%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return type === 'sort' ? row.ExpireTimestamp : row.Expire; }},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/tokens/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/tokens/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/tokens/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-description=\"' + row.Description + '\" data-validity=\"' + row.Expire + '\"><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/tokens', fields: 'ID,Description,ExpireTimestamp,Expire,NULL', filters: { Expire: 'datetime'}}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/tokens/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#token_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#token_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#token_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#token_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#token_delete_validity').html ( $(this).data ( 'validity'));\n" .
              "  $('#token_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#token_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/tokens/' + encodeURIComponent ( $('#token_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#token_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Token remove") . "', text: '" . __ ( "Token removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#token_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Token remove") . "', text: '" . __ ( "Error removing token!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the token add page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_add_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Tokens"));
  sys_set_subtitle ( __ ( "tokens addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Tokens"), "link" => "/tokens"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"token_add_form\">\n";

  // Add token description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"token_add_description\" placeholder=\"" . __ ( "Token description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_add_token\" class=\"control-label col-xs-2\">" . __ ( "Token") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group\">\n";
  $output .= "        <input type=\"text\" name=\"Token\" id=\"token_add_token\" class=\"form-control\" placeholder=\"" . __ ( "Token") . "\" readonly=\"readonly\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token access field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_add_access\" class=\"control-label col-xs-2\">" . __ ( "CIDR") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Access\" class=\"form-control\" id=\"token_add_access\" placeholder=\"" . __ ( "Token access CIDR") . "\" maxlength=\"18\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token permissions field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_add_permissions\" class=\"control-label col-xs-2\">" . __ ( "Permissions") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Permissions\" id=\"token_add_permissions\" class=\"form-control\" data-placeholder=\"" . __ ( "Token permissions") . "\" multiple=\"multiple\"></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token validity field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_add_validity\" class=\"control-label col-xs-2\">" . __ ( "Validity") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group date\">\n";
  $output .= "        <input name=\"Validity\" type=\"text\" id=\"token_add_validity\" class=\"form-control\" placeholder=\"" . __ ( "Token validity") . "\" maxlength=\"10\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-calendar\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show calendar") . "\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_add_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Language\" id=\"token_add_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Token language") . "\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( __ ( $language))) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/tokens\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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
  sys_addjs ( "$('button.btn-random').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  var arr = new Uint8Array ( 36);\n" .
              "  window.crypto.getRandomValues ( arr);\n" .
              "  var token = Array.from ( arr, function ( value) { return ( '0' + value.toString ( 16)).substr ( -2); }).join ( '');\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( token.substring ( 0, 16) + '-' + token.substring ( 16, 24) + '-' + token.substring ( 24, 32) + '-' + token.substring ( 40, 48) + '-' + token.substring ( 48));\n" .
              "}).trigger ( 'click');\n" .
              "$('#token_add_access').mask ( '0ZZ.0ZZ.0ZZ.0ZZ/0Z', { translation: { 'Z': { pattern: /[0-9]/, optional: true }}});\n" .
              "$('#token_add_permissions').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/tokens/permissions', fields: 'Token,Description', formatID: 'Token', formatText: '%Description%'})\n" .
              "});\n" .
              "$('#token_add_validity').mask ( '00/00/0000 00:00:00').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/DD/YYYY HH:mm:ss") . "'});\n" .
              "$('#token_add_language').select2 (\n" .
              "{\n" .
              "  allowClear: false\n" .
              "});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('#token_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#token_add_description'),\n" .
              "    URL: '/tokens',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    filters: { Validity: 'datetime'},\n" .
              "    title: '" . __ ( "Token addition") . "',\n" .
              "    fail: '" . __ ( "Error adding token!") . "',\n" .
              "    success: '" . __ ( "Token added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/tokens', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#token_add_form').data ( 'formData');\n" .
              "  if ( typeof ( formData.Permissions) == 'string')\n" .
              "  {\n" .
              "    var tmp = formData.Permissions;\n" .
              "    formData.Permissions = new Array ();\n" .
              "    formData.Permissions.push ( tmp);\n" .
              "  }\n" .
              "  $('#token_add_form').data ( 'formData', formData);\n" .
              "});");  

  return $output;
}

/**
 * Function to generate the token clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/tokens/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/tokens/add', true, function ()\n" .
              "  {\n" .
              "    $('#token_add_description').val ( data.Description);\n" .
              "    $('#token_add_token').val ( data.Token);\n" .
              "    $('#token_add_access').val ( data.Access);\n" .
              "    var ids = [];\n" .
              "    for ( var id in data.Permissions)\n" .
              "    {\n" .
              "      if ( ! data.Permissions.hasOwnProperty ( id))\n" .
              "      {\n" .
              "        continue;\n" .
              "      }\n" .
              "      ids.push ( id);\n" .
              "    }\n" .
              "    $('#token_add_permissions').val ( ids).trigger ( 'change');\n" .
              "    $('#token_add_validity').val ( moment ( data.Validity).isValid () ? moment ( data.Validity).format ( 'L LTS') : '');\n" .
              "    $('#token_add_language').val ( data.Language).trigger ( 'change');\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Token cloning") . "', text: '" . __ ( "Error requesting token data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the token view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Tokens"));
  sys_set_subtitle ( __ ( "tokens view"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Tokens"), "link" => "/tokens"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"token_view_form\">\n";

  // Add token description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"token_view_description\" placeholder=\"" . __ ( "Token description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_view_token\" class=\"control-label col-xs-2\">" . __ ( "Token") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group\">\n";
  $output .= "        <input type=\"text\" name=\"Token\" class=\"form-control\" id=\"token_view_token\" placeholder=\"" . __ ( "Token") . "\" disabled=\"disabled\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-copy\" type=\"button\"><i class=\"fa fa-copy\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token access field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_view_access\" class=\"control-label col-xs-2\">" . __ ( "CIDR") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Access\" class=\"form-control\" id=\"token_view_access\" placeholder=\"" . __ ( "Token access CIDR") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token permissions field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_view_permissions\" class=\"control-label col-xs-2\">" . __ ( "Permissions") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Permissions\" id=\"token_view_permissions\" class=\"form-control\" data-placeholder=\"" . __ ( "Token permissions") . "\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token validity field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_view_validity\" class=\"control-label col-xs-2\">" . __ ( "Validity") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Validity\" class=\"form-control\" id=\"token_view_validity\" placeholder=\"" . __ ( "Token validity") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_view_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Language\" id=\"token_view_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Token language") . "\"  disabled=\"disabled\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( __ ( $language))) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/tokens\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#token_view_permissions,#token_view_language').select2 ();\n" .
              "$('#token_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#token_view_description').val ( data.Description);\n" .
              "  $('#token_view_token').val ( data.Token);\n" .
              "  $('#token_view_access').val ( data.Access);\n" .
              "  var ids = [];\n" .
              "  for ( var id in data.Permissions)\n" .
              "  {\n" .
              "    if ( ! data.Permissions.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    ids.push ( id);\n" .
              "    $('#token_view_permissions').append ( $('<option>', { value: id, text: data.Permissions[id]}));\n" .
              "  }\n" .
              "  $('#token_view_permissions').val ( ids).trigger ( 'change');\n" .
              "  $('#token_view_validity').val ( moment ( data.Validity).isValid () ? moment ( data.Validity).format ( 'L LTS') : '');\n" .
              "  $('#token_view_language').val ( data.Language).trigger ( 'change');\n" .
              "});\n" .
              "$('button.btn-copy').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  var target = $(this).parent ().parent ().find ( 'input');\n" .
              "  $(target).removeAttr ( 'disabled').attr ( 'readonly', 'readonly');\n" .
              "  $(target).select ();\n" .
              "  document.execCommand ( 'copy');\n" .
              "  $(target).removeAttr ( 'readonly').attr ( 'disabled', 'disabled');\n" .
              "  $(this).focus ();\n" .
              "  new PNotify ( { title: '" . __ ( "Token view") . "', text: '" . __ ( "Token copyed to clipboard!") . "', type: 'success'});\n" .
              "});\n" .
              "VoIP.rest ( '/tokens/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#token_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Token view") . "', text: '" . __ ( "Error requesting token data!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the token edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tokens_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Tokens"));
  sys_set_subtitle ( __ ( "tokens edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Tokens"), "link" => "/tokens"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"token_edit_form\">\n";

  // Add token description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"token_edit_description\" placeholder=\"" . __ ( "Token description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_edit_token\" class=\"control-label col-xs-2\">" . __ ( "Token") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group\">\n";
  $output .= "        <input type=\"text\" name=\"Token\" id=\"token_edit_token\" class=\"form-control\" placeholder=\"" . __ ( "Token") . "\" readonly=\"readonly\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token access field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_edit_access\" class=\"control-label col-xs-2\">" . __ ( "CIDR") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Access\" class=\"form-control\" id=\"token_edit_access\" placeholder=\"" . __ ( "Token access CIDR") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token permissions field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_edit_permissions\" class=\"control-label col-xs-2\">" . __ ( "Permissions") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Permissions\" id=\"token_edit_permissions\" class=\"form-control\" data-placeholder=\"" . __ ( "Token permissions") . "\" multiple=\"multiple\"></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token validity field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_edit_validity\" class=\"control-label col-xs-2\">" . __ ( "Validity") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group date\">\n";
  $output .= "        <input name=\"Validity\" type=\"text\" id=\"token_edit_validity\" class=\"form-control\" placeholder=\"" . __ ( "Token validity") . "\" maxlength=\"10\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-calendar\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show calendar") . "\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add token language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"token_edit_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Language\" id=\"token_edit_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Token language") . "\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( __ ( $language))) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/tokens\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('button.btn-random').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  var arr = new Uint8Array ( 36);\n" .
              "  window.crypto.getRandomValues ( arr);\n" .
              "  var token = Array.from ( arr, function ( value) { return ( '0' + value.toString ( 16)).substr ( -2); }).join ( '');\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( token.substring ( 0, 16) + '-' + token.substring ( 16, 24) + '-' + token.substring ( 24, 32) + '-' + token.substring ( 40, 48) + '-' + token.substring ( 48));\n" .
              "});\n" .
              "$('#token_edit_access').mask ( '0ZZ.0ZZ.0ZZ.0ZZ/0Z', { translation: { 'Z': { pattern: /[0-9]/, optional: true }}});\n" .
              "$('#token_edit_permissions').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/tokens/permissions', fields: 'Token,Description', formatID: 'Token', formatText: '%Description%'})\n" .
              "});\n" .
              "$('#token_edit_validity').mask ( '00/00/0000 00:00:00').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/DD/YYYY HH:mm:ss") . "'});\n" .
              "$('#token_edit_language').select2 (\n" .
              "{\n" .
              "  allowClear: false\n" .
              "});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('#token_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#token_edit_description').val ( data.Description);\n" .
              "  $('#token_edit_token').val ( data.Token);\n" .
              "  $('#token_edit_access').val ( data.Access);\n" .
              "  var ids = [];\n" .
              "  for ( var id in data.Permissions)\n" .
              "  {\n" .
              "    if ( ! data.Permissions.hasOwnProperty ( id))\n" .
              "    {\n" .
              "      continue;\n" .
              "    }\n" .
              "    ids.push ( id);\n" .
              "  }\n" .
              "  $('#token_edit_permissions').val ( ids).trigger ( 'change');\n" .
              "  $('#token_edit_validity').val ( moment ( data.Validity).isValid () ? moment ( data.Validity).format ( 'L LTS') : '');\n" .
              "  $('#token_edit_language').val ( data.Language).trigger ( 'change');\n" .
              "  $('#token_edit_description').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/tokens/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#token_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Token edition") . "', text: '" . __ ( "Error requesting token data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#token_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/tokens/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    filters: { Validity: 'datetime'},\n" .
              "    title: '" . __ ( "Token edition") . "',\n" .
              "    fail: '" . __ ( "Error changing token!") . "',\n" .
              "    success: '" . __ ( "Token changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/tokens', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#token_edit_form').data ( 'formData');\n" .
              "  if ( typeof ( formData.Permissions) == 'string')\n" .
              "  {\n" .
              "    var tmp = formData.Permissions;\n" .
              "    formData.Permissions = new Array ();\n" .
              "    formData.Permissions.push ( tmp);\n" .
              "  }\n" .
              "  $('#token_edit_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}
?>
