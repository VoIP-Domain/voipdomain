<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain conferences module WebUI. This module add the feature of
 * conference rooms for multi-party calls.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Conferences
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/conferences", "conferences_search_page", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "conferences_search_page", "conferences_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/conferences/add", "conferences_add_page", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "conferences_add_page", "conferences_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/conferences/:id/clone", "conferences_clone_function", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "conferences_clone_function", "conferences_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/conferences/:id/view", "conferences_view_page", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "conferences_view_page", "conferences_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/conferences/:id/edit", "conferences_edit_page", array ( "permissions" => array ( "Administrator")));
framework_add_hook ( "conferences_edit_page", "conferences_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main conferences page.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_search_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Conferences"));
  sys_set_subtitle ( __ ( "conferences search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Conferences"))
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
   * Conference search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add conference remove modal code
   */
  $output .= "<div id=\"conference_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"conference_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove conference") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are you sure you want to remove the conference %s (%s)?"), "<span id=\"conference_delete_name\"></span>", "<span id=\"conference_delete_description\"></span>") . "</p><input type=\"hidden\" id=\"conference_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Name', title: '" . __ ( "Name") . "', width: '20%', class: 'export all'},\n" .
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '40%', class: 'export min-mobile-l'},\n" .
              "             { data: 'MaxMembers', title: '" . __ ( "Max Members") . "', width: '15%', class: 'export min-tablet-l'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/conferences/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/conferences/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/conferences/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-name=\"' + row.Name + '\" data-description=\"' + row.Description + '\"><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/conferences', fields: 'ID,Name,Description,MaxMembers,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/conferences/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#conference_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#conference_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#conference_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#conference_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#conference_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#conference_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#conference_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/conferences/' + encodeURIComponent ( $('#conference_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#conference_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Conference removal") . "', text: '" . __ ( "Conference removed successfully!") . "', type: 'success'});\n" .
              "    $('#conference_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Conference removal") . "', text: '" . __ ( "Error removing conference!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the conference add page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_add_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Conferences"));
  sys_set_subtitle ( __ ( "Add conferences"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Conferences"), "link" => "/conferences"),
    2 => array ( "title" => __ ( "Add"))
  ));
  sys_set_menu_highlight ( "/conferences");

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
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"conference_add_form\">\n";

  // Add conference name field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"conference_add_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"conference_add_name\" placeholder=\"" . __ ( "Conference name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference description field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"conference_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"conference_add_description\" placeholder=\"" . __ ( "Conference description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference PIN field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_add_pin\" class=\"control-label col-xs-2\">" . __ ( "PIN") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"PIN\" class=\"form-control\" id=\"conference_add_pin\" placeholder=\"" . __ ( "Conference PIN (optional)") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference admin PIN field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_add_adminpin\" class=\"control-label col-xs-2\">" . __ ( "Admin PIN") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"AdminPIN\" class=\"form-control\" id=\"conference_add_adminpin\" placeholder=\"" . __ ( "Administrator PIN (optional)") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference max members field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_add_maxmembers\" class=\"control-label col-xs-2\">" . __ ( "Max Members") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"number\" name=\"MaxMembers\" class=\"form-control\" id=\"conference_add_maxmembers\" placeholder=\"" . __ ( "Maximum members (optional, 0 = unlimited)") . "\" value=\"0\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference MOH class field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_add_mohclass\" class=\"control-label col-xs-2\">" . __ ( "MOH Class") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"MOHClass\" class=\"form-control\" id=\"conference_add_mohclass\" placeholder=\"" . __ ( "Music on hold class (optional)") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference record option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_add_recordconference\" class=\"control-label col-xs-2\">" . __ ( "Record Conference") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"RecordConference\" id=\"conference_add_recordconference\" value=\"Y\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference announce join/leave option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_add_announcejoinleave\" class=\"control-label col-xs-2\">" . __ ( "Announce Join/Leave") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"AnnounceJoinLeave\" id=\"conference_add_announcejoinleave\" value=\"Y\" class=\"form-control\" checked=\"checked\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference wait for admin option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_add_waitforadmin\" class=\"control-label col-xs-2\">" . __ ( "Wait for Admin") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"WaitForAdmin\" id=\"conference_add_waitforadmin\" value=\"Y\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/conferences\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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
  sys_addjs ( "$('#conference_add_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#conference_add_name').mask ( 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ', { 'translation': { Z: { pattern: /[a-z0-9\-\.]/}}});\n" .
              "$('#conference_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#conference_add_name'),\n" .
              "    URL: '/conferences',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Add conference") . "',\n" .
              "    fail: '" . __ ( "Error adding conference!") . "',\n" .
              "    success: '" . __ ( "Conference added successfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/conferences', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");  

  return $output;
}

/**
 * Function to generate the conference clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function conferences_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/conferences/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/conferences/add', true, function ()\n" .
              "  {\n" .
              "    $('#conference_add_name').val ( data.Name);\n" .
              "    $('#conference_add_description').val ( data.Description);\n" .
              "    $('#conference_add_pin').val ( data.PIN);\n" .
              "    $('#conference_add_adminpin').val ( data.AdminPIN);\n" .
              "    $('#conference_add_maxmembers').val ( data.MaxMembers);\n" .
              "    $('#conference_add_mohclass').val ( data.MOHClass);\n" .
              "    $('#conference_add_recordconference').bootstrapToggle ( data.RecordConference == 'Y' ? 'on' : 'off');\n" .
              "    $('#conference_add_announcejoinleave').bootstrapToggle ( data.AnnounceJoinLeave == 'Y' ? 'on' : 'off');\n" .
              "    $('#conference_add_waitforadmin').bootstrapToggle ( data.WaitForAdmin == 'Y' ? 'on' : 'off');\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Clone conference") . "', text: '" . __ ( "Error retrieving conference data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the conference view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Conferences"));
  sys_set_subtitle ( __ ( "View conferences"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Conferences"), "link" => "/conferences"),
    2 => array ( "title" => __ ( "View"))
  ));
  sys_set_menu_highlight ( "/conferences");

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
  $output = "<form class=\"form-horizontal\" id=\"conference_view_form\">\n";

  // Add conference name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_view_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"conference_view_name\" placeholder=\"" . __ ( "Conference name") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"conference_view_description\" placeholder=\"" . __ ( "Conference description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference PIN field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_view_pin\" class=\"control-label col-xs-2\">" . __ ( "PIN") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"PIN\" class=\"form-control\" id=\"conference_view_pin\" placeholder=\"" . __ ( "Conference PIN (optional)") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference admin PIN field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_view_adminpin\" class=\"control-label col-xs-2\">" . __ ( "Admin PIN") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"AdminPIN\" class=\"form-control\" id=\"conference_view_adminpin\" placeholder=\"" . __ ( "Administrator PIN (optional)") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference max members field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_view_maxmembers\" class=\"control-label col-xs-2\">" . __ ( "Max Members") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"number\" name=\"MaxMembers\" class=\"form-control\" id=\"conference_view_maxmembers\" placeholder=\"" . __ ( "Maximum members (optional, 0 = unlimited)") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference MOH class field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_view_mohclass\" class=\"control-label col-xs-2\">" . __ ( "MOH Class") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"MOHClass\" class=\"form-control\" id=\"conference_view_mohclass\" placeholder=\"" . __ ( "Music on hold class (optional)") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference record option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_view_recordconference\" class=\"control-label col-xs-2\">" . __ ( "Record Conference") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"RecordConference\" id=\"conference_view_recordconference\" value=\"Y\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference announce join/leave option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_view_announcejoinleave\" class=\"control-label col-xs-2\">" . __ ( "Announce Join/Leave") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"AnnounceJoinLeave\" id=\"conference_view_announcejoinleave\" value=\"Y\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference wait for admin option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_view_waitforadmin\" class=\"control-label col-xs-2\">" . __ ( "Wait for Admin") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"WaitForAdmin\" id=\"conference_view_waitforadmin\" value=\"Y\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/conferences\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#conference_view_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#conference_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#conference_view_name').val ( data.Name);\n" .
              "  $('#conference_view_description').val ( data.Description);\n" .
              "  $('#conference_view_pin').val ( data.PIN);\n" .
              "  $('#conference_view_adminpin').val ( data.AdminPIN);\n" .
              "  $('#conference_view_maxmembers').val ( data.MaxMembers);\n" .
              "  $('#conference_view_mohclass').val ( data.MOHClass);\n" .
              "  $('#conference_view_recordconference').bootstrapToggle ( 'enable').bootstrapToggle ( data.RecordConference == 'Y' ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#conference_view_announcejoinleave').bootstrapToggle ( 'enable').bootstrapToggle ( data.AnnounceJoinLeave == 'Y' ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#conference_view_waitforadmin').bootstrapToggle ( 'enable').bootstrapToggle ( data.WaitForAdmin == 'Y' ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "});\n" .
              "VoIP.rest ( '/conferences/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#conference_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Conference view") . "', text: '" . __ ( "Error viewing conference!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the conference edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Conferences"));
  sys_set_subtitle ( __ ( "Edit conferences"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Conferences"), "link" => "/conferences"),
    2 => array ( "title" => __ ( "Edit"))
  ));
  sys_set_menu_highlight ( "/conferences");

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
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"conference_edit_form\">\n";

  // Add conference name field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"conference_edit_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"conference_edit_name\" placeholder=\"" . __ ( "Conference name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference description field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"conference_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"conference_edit_description\" placeholder=\"" . __ ( "Conference description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference PIN field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_edit_pin\" class=\"control-label col-xs-2\">" . __ ( "PIN") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"PIN\" class=\"form-control\" id=\"conference_edit_pin\" placeholder=\"" . __ ( "Conference PIN (optional)") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference admin PIN field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_edit_adminpin\" class=\"control-label col-xs-2\">" . __ ( "Admin PIN") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"AdminPIN\" class=\"form-control\" id=\"conference_edit_adminpin\" placeholder=\"" . __ ( "Administrator PIN (optional)") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference max members field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_edit_maxmembers\" class=\"control-label col-xs-2\">" . __ ( "Max Members") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"number\" name=\"MaxMembers\" class=\"form-control\" id=\"conference_edit_maxmembers\" placeholder=\"" . __ ( "Maximum members (optional, 0 = unlimited)") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference MOH class field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_edit_mohclass\" class=\"control-label col-xs-2\">" . __ ( "MOH Class") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"MOHClass\" class=\"form-control\" id=\"conference_edit_mohclass\" placeholder=\"" . __ ( "Music on hold class (optional)") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference record option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_edit_recordconference\" class=\"control-label col-xs-2\">" . __ ( "Record Conference") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"RecordConference\" id=\"conference_edit_recordconference\" value=\"Y\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference announce join/leave option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_edit_announcejoinleave\" class=\"control-label col-xs-2\">" . __ ( "Announce Join/Leave") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"AnnounceJoinLeave\" id=\"conference_edit_announcejoinleave\" value=\"Y\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add conference wait for admin option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"conference_edit_waitforadmin\" class=\"control-label col-xs-2\">" . __ ( "Wait for Admin") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"WaitForAdmin\" id=\"conference_edit_waitforadmin\" value=\"Y\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/conferences\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#conference_edit_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#conference_edit_name').mask ( 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ', { 'translation': { Z: { pattern: /[a-z0-9\-\.]/}}});\n" .
              "$('#conference_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#conference_edit_name').val ( data.Name);\n" .
              "  $('#conference_edit_description').val ( data.Description);\n" .
              "  $('#conference_edit_pin').val ( data.PIN);\n" .
              "  $('#conference_edit_adminpin').val ( data.AdminPIN);\n" .
              "  $('#conference_edit_maxmembers').val ( data.MaxMembers);\n" .
              "  $('#conference_edit_mohclass').val ( data.MOHClass);\n" .
              "  $('#conference_edit_recordconference').bootstrapToggle ( data.RecordConference == 'Y' ? 'on' : 'off');\n" .
              "  $('#conference_edit_announcejoinleave').bootstrapToggle ( data.AnnounceJoinLeave == 'Y' ? 'on' : 'off');\n" .
              "  $('#conference_edit_waitforadmin').bootstrapToggle ( data.WaitForAdmin == 'Y' ? 'on' : 'off');\n" .
              "  $('#conference_edit_name').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/conferences/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#conference_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Edit conference") . "', text: '" . __ ( "Error retrieving conference data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#conference_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/conferences/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Edit conference") . "',\n" .
              "    fail: '" . __ ( "Error changing conference!") . "',\n" .
              "    success: '" . __ ( "Conference changed successfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/conferences', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}
?>
