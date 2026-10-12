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
 * VoIP Domain administrators module WebUI. This module add the feature to manage
 * master portal administrators.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Admins
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/admins", "admins_search_page", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "admins_search_page", "admins_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/admins/add", "admins_add_page", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "admins_add_page", "admins_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/admins/:id/clone", "admins_clone_function", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "admins_clone_function", "admins_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/admins/:id/view", "admins_view_page", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "admins_view_page", "admins_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/admins/:id/edit", "admins_edit_page", array ( "permissions" => array ( "Super-Administrator")));
framework_add_hook ( "admins_edit_page", "admins_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main administrators page.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_search_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Administrators"));
  sys_set_subtitle ( __ ( "administrators search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Administrators"))
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
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Administrator search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add administrator remove modal code
   */
  $output .= "<div id=\"admin_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"admin_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Administrator removal") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are you sure you want to remove administrator %s (%s)?"), "<span id=\"admin_delete_name\"></span>", "<span id=\"admin_delete_username\"></span>") . "</p><input type=\"hidden\" id=\"admin_delete_id\" value=\"\"></div>\n";
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
              "  data: VoIP.APIsearch ( { path: '/admins', fields: 'ID,Name,Username,NULL'}),\n" .
              "  columns: [\n" .
              "             { data: 'Name', title: '" . __ ( "Name") . "', width: '60%', class: 'export min-tablet-l'},\n" .
              "             { data: 'Username', title: '" . __ ( "Username") . "', width: '30%', class: 'export all'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/admins/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/admins/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/admins/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-name=\"' + row.Name + '\" data-username=\"' + row.Username + '\"' + ( VoIP.getUID () == row.ID ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/admins/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#admin_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#admin_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#admin_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#admin_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#admin_delete_username').html ( $(this).data ( 'username'));\n" .
              "  $('#admin_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#admin_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/admins/' + encodeURIComponent ( $('#admin_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#admin_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Administrator removal") . "', text: '" . __ ( "Administrator removed successfully!") . "', type: 'success'});\n" .
              "    $('#admin_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Administrator removal") . "', text: '" . __ ( "Error removing administrator!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the administrator add page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_add_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Administrators"));
  sys_set_subtitle ( __ ( "add administrator"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Administrators"), "link" => "/admins"),
    2 => array ( "title" => __ ( "Add"))
  ));
  sys_set_menu_highlight ( "/admins");

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
  $output = "<form class=\"form-horizontal\" id=\"admin_add_form\">\n";

  // Add administrator name field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"admin_add_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"admin_add_name\" placeholder=\"" . __ ( "Administrator name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator login field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"admin_add_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"admin_add_username\" placeholder=\"" . __ ( "Administrator login name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator e-mail field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"admin_add_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"email\" name=\"Email\" class=\"form-control\" id=\"admin_add_email\" placeholder=\"" . __ ( "Administrator e-mail") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator password field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"admin_add_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"Password\" class=\"form-control\" id=\"admin_add_password\" placeholder=\"" . __ ( "Administrator password") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator password confirmation field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"admin_add_confirmation\" class=\"control-label col-xs-2\">" . __ ( "Confirmation") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"Confirmation\" class=\"form-control\" id=\"admin_add_confirmation\" placeholder=\"" . __ ( "Administrator password confirmation") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_add_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Language\" id=\"admin_add_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Administrator language") . "\">\n";
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
  $output .= "      <a class=\"btn btn-default\" href=\"/admins\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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
  sys_addjs ( "$('#admin_add_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#admin_add_language').select2 (\n" .
              "{\n" .
              "  allowClear: false\n" .
              "});\n" .
              "$('#admin_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#admin_add_name'),\n" .
              "    URL: '/admins',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Add administrator") . "',\n" .
              "    fail: '" . __ ( "Error adding administrator!") . "',\n" .
              "    success: '" . __ ( "Administrator added successfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/admins', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the administrator clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return void
 */
function admins_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/admins/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/admins/add', true, function ()\n" .
              "  {\n" .
              "    $('#admin_add_name').val ( data.Name);\n" .
              "    $('#admin_add_username').val ( data.Username);\n" .
              "    $('#admin_add_email').val ( data.Email);\n" .
              "    $('#admin_add_language').val ( data.Language).trigger ( 'change');\n" .
              ( framework_has_hook ( "admins_clone_inject") ? framework_call ( "admins_clone_inject") : "") .
              "    $('#admin_add_name').focus ();\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Clone administrator") . "', text: '" . __ ( "Error retrieving administrator data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the administrator view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Administrators"));
  sys_set_subtitle ( __ ( "view administrator"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Administrators"), "link" => "/admins"),
    2 => array ( "title" => __ ( "View"))
  ));
  sys_set_menu_highlight ( "/admins");

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
  $output = "<form class=\"form-horizontal\" id=\"admin_view_form\">\n";

  // Add administrator name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_view_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"admin_view_name\" placeholder=\"" . __ ( "Administrator name") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator login field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_view_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"admin_view_username\" placeholder=\"" . __ ( "Administrator login name") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator e-mail field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_view_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"email\" name=\"Email\" class=\"form-control\" id=\"admin_view_email\" placeholder=\"" . __ ( "Administrator e-mail") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_view_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Language\" id=\"admin_view_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Administrator language") . "\"  disabled=\"disabled\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( __ ( $language))) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add SFA option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_view_sfa\" class=\"control-label col-xs-2\">" . __ ( "Second factor authentication") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"SFA\" value=\"true\" id=\"admin_view_sfa\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/admins\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#admin_view_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#admin_view_language').select2 ();\n" .
              "$('#admin_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#admin_view_name').val ( data.Name);\n" .
              "  $('#admin_view_username').val ( data.Username);\n" .
              "  $('#admin_view_email').val ( data.Email);\n" .
              "  $('#admin_view_language').val ( data.Language).trigger ( 'change');\n" .
              "  $('#admin_view_sfa').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.SFA ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#admin_view_name').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/admins/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#admin_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "View administrator") . "', text: '" . __ ( "Error retrieving administrator data!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the administrator edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function admins_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Administrators"));
  sys_set_subtitle ( __ ( "edit administrator"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Administrators"), "link" => "/admins"),
    2 => array ( "title" => __ ( "Edit"))
  ));
  sys_set_menu_highlight ( "/admins");

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-qrcode", "src" => "/vendors/jquery-qrcode/dist/jquery-qrcode.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"admin_edit_form\">\n";

  // Add administrator name field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"admin_edit_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"admin_edit_name\" placeholder=\"" . __ ( "Administrator name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator login field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"admin_edit_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"admin_edit_username\" placeholder=\"" . __ ( "Administrator login name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator e-mail field
  $output .= "  <div class=\"form-group required\">\n";
  $output .= "    <label for=\"admin_edit_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"email\" name=\"Email\" class=\"form-control\" id=\"admin_edit_email\" placeholder=\"" . __ ( "Administrator e-mail") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator password field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_edit_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"Password\" class=\"form-control\" id=\"admin_edit_password\" placeholder=\"" . __ ( "Administrator password") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator password confirmation field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_edit_confirmation\" class=\"control-label col-xs-2\">" . __ ( "Confirmation") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"Confirmation\" class=\"form-control\" id=\"admin_edit_confirmation\" placeholder=\"" . __ ( "Administrator password confirmation") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_edit_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Language\" id=\"admin_edit_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Administrator language") . "\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( __ ( $language))) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add administrator SFA option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"admin_edit_sfa\" class=\"control-label col-xs-2\">" . __ ( "Second factor authentication") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"SFA\" value=\"true\" id=\"admin_edit_sfa\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/admins\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  // Second factor authentication activation modal
  $output .= "\n";
  $output .= "<div id=\"admin_edit_sfa_activate\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"admin_edit_sfa_activate\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Second factor authentication") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <img src=\"/img/sfa.png\" alt=\"\" class=\"dt-right\">\n";
  $output .= "        <p>" . __ ( "Follow the steps below to set up a second factor authentication app") . ":</p>\n";
  $output .= "        <p><strong>1) " . __ ( "Download a two-factor authenticator app.") . "</strong> " . __ ( "The apps below are recommended, but any authentication app will work.") . "</p>\n";
  $output .= "        <p>\n";
  $output .= "          <i class=\"fab fa-apple\"></i> " . __ ( "iOS devices") . ": <a href=\"https://itunes.apple.com/us/app/authy/id494168017\" target=\"_blank\">Authy</a><br />\n";
  $output .= "          <i class=\"fab fa-android\"></i> " . __ ( "Android devices") . ": <a href=\"https://play.google.com/store/apps/details?id=com.authy.authy\" target=\"_blank\">Authy</a><br />\n";
  $output .= "          <i class=\"fab fa-windows\"></i> " . __ ( "Windows devices") . ": <a href=\"https://www.microsoft.com/p/authenticator/9wzdncrfj3rj\" target=\"_blank\">Microsoft Authenticator</a>\n";
  $output .= "        <p><strong>2) " . __ ( "Scan the QR Code below with your authenticator app.") . "</strong> " . __ ( "Or enter the key below.") . "</p>\n";
  $output .= "        <div class=\"dt-center\"><div id=\"admin_edit_sfa_activate_qrcode\" width=\"200\" height=\"200\"></div><strong><br />" . __ ( "Key") . "</strong>: <code id=\"admin_edit_sfa_activate_key\" title=\"" . __ ( "Key") . "\" aria-label=\"" . __ ( "Key") . "\"></code></div>\n";
  $output .= "        <p><label for=\"admin_edit_sfa_activate_code\"><strong>3)</strong> " . __ ( "Enter the 6-digit verification code from the app") . ":</label> <input type=\"text\" id=\"admin_edit_sfa_activate_code\" value=\"\" maxlength=\"6\" size=\"6\" autocomplete=\"off\" autocapitalize=\"none\" autocorrect=\"none\" spellcheck=\"false\"></p>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn btn-primary ladda-button\" id=\"admin_edit_sfa_activate_button\" data-style=\"expand-left\">" . __ ( "Activate") . "</button>\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  // Second factor authentication removal modal
  $output .= "\n";
  $output .= "<div id=\"admin_edit_sfa_remove\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"admin_edit_sfa_remove\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header alert-danger\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Second factor authentication") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <p><strong>" . __ ( "Warning") . ":</strong> " . __ ( "Disabling second factor authentication will destroy your key. You will need to set up another key to re-enable it.") ."</p>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn btn-danger ladda-button\" id=\"admin_edit_sfa_remove_button\" data-style=\"expand-left\">" . __ ( "Disable") . "</button>\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#admin_edit_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#admin_edit_language').select2 (\n" .
              "{\n" .
              "  allowClear: false\n" .
              "});\n" .
              "$('#admin_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#admin_edit_name').val ( data.Name);\n" .
              "  $('#admin_edit_username').val ( data.Username);\n" .
              "  $('#admin_edit_email').val ( data.Email);\n" .
              "  $('#admin_edit_language').val ( data.Language).trigger ( 'change');\n" .
              "  $('#admin_edit_sfa').bootstrapToggle ( ( data.SFA ? 'on' : 'off'));\n" .
              "  if ( VoIP.getUID () != VoIP.parameters.id)\n" .
              "  {\n" .
              "    $('#admin_edit_sfa').bootstrapToggle ( 'disable');\n" .
              "  } else {\n" .
              "    $('#admin_edit_sfa_activate_code').mask ( '000000');\n" .
              "    $('#admin_edit_sfa').on ( 'change', function ( e)\n" .
              "    {\n" .
              "      if ( ! $(this).data ( 'ignore') && $(this).prop ( 'checked'))\n" .
              "      {\n" .
              "        $(this).bootstrapToggle ( 'disable');\n" .
              "        var that = this;\n" .
              "        VoIP.rest ( '/admins/sfa', 'POST').done ( function ( data, textStatus, jqXHR)\n" .
              "        {\n" .
              "          $('#admin_edit_sfa_activate_key').html ( data.Key);\n" .
              "          $('#admin_edit_sfa_activate_qrcode').empty ().qrcode (\n" .
              "          {\n" .
              "            render: 'image',\n" .
              "            minVersion: 1,\n" .
              "            maxVersion: 40,\n" .
              "            ecLevel: 'H',\n" .
              "            size: 200,\n" .
              "            fill: '#000000',\n" .
              "            background: '#ffffff',\n" .
              "            mode: 2,\n" .
              "            label: 'VoIP Domain',\n" .
              "            fontname: '\"Helvetica Neue\", Helvetica, Arial, sans-serif',\n" .
              "            fontcolor: '#932092',\n" .
              "            nonce: VoIP.getNonce (),\n" .
              "            text: data.URI\n" .
              "          });\n" .
              "          $('#admin_edit_sfa_activate_code').val ( '').alerts ( 'clear');\n" .
              "          $('#admin_edit_sfa_activate').modal ( 'show');\n" .
              "          $(that).bootstrapToggle ( 'enable');\n" .
              "        }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "        {\n" .
              "          $(that).data ( 'ignore', true).bootstrapToggle ( 'enable').bootstrapToggle ( 'off');\n" .
              "          new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Error fetching second factor authentication data!") . "', type: 'error'});\n" .
              "        });\n" .
              "      } else {\n" .
              "        if ( $(this).data ( 'ignore'))\n" .
              "        {\n" .
              "          $(this).data ( 'ignore', false);\n" .
              "        } else {\n" .
              "          $('#admin_edit_sfa_remove').modal ( 'show');\n" .
              "        }\n" .
              "      }\n" .
              "    });\n" .
              "    $('#admin_edit_sfa_activate_button').on ( 'click', function ()\n" .
              "    {\n" .
              "      $('#admin_edit_sfa_activate_code').alerts ( 'clear');\n" .
              "      var l = Ladda.create ( this);\n" .
              "      l.start ();\n" .
              "      $('#admin_edit_sfa_activate').find ( 'button[data-dismiss=\"modal\"]').attr ( 'disabled', 'disabled');\n" .
              "      VoIP.rest ( '/admins/sfa', 'POST', { 'Code': $('#admin_edit_sfa_activate_code').val ()}).done ( function ( data, textStatus, jqXHR)\n" .
              "      {\n" .
              "        if ( jqXHR.status == 201)\n" .
              "        {\n" .
              "          $('#admin_edit_sfa').data ( 'activated', true);\n" .
              "          $('#admin_edit_sfa_activate').modal ( 'hide');\n" .
              "          new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Second factor authentication activated!") . "', type: 'success'});\n" .
              "        } else {\n" .
              "          if ( data.hasOwnProperty ( 'Code'))\n" .
              "          {\n" .
              "            $('#admin_edit_sfa_activate_code').alerts ( 'add', $.extend ( {}, {}, { message: data.Code}));\n" .
              "          } else {\n" .
              "            new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Invalid second factor authentication code!") . "', type: 'error'});\n" .
              "          }\n" .
              "        }\n" .
              "      }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "      {\n" .
              "        new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Invalid second factor authentication code!") . "', type: 'error'});\n" .
              "      }).always ( function ()\n" .
              "      {\n" .
              "        l.stop ();\n" .
              "        $('#admin_edit_sfa_activate').find ( 'button[data-dismiss=\"modal\"]').removeAttr ( 'disabled');\n" .
              "      });\n" .
              "    });\n" .
              "    $('#admin_edit_sfa_activate').on ( 'shown.bs.modal', function ()\n" .
              "    {\n" .
              "      $(this).find ( 'button[data-dismiss=\"modal\"]').removeAttr ( 'disabled');\n" .
              "      $('#admin_edit_sfa_activate_code').focus ();\n" .
              "    }).on ( 'hide.bs.modal', function ()\n" .
              "    {\n" .
              "      if ( $('#admin_edit_sfa').data ( 'activated') == true)\n" .
              "      {\n" .
              "        $('#admin_edit_sfa').data ( 'activated', false);\n" .
              "      } else {\n" .
              "        $('#admin_edit_sfa').data ( 'ignore', true).bootstrapToggle ( 'off');\n" .
              "      }\n" .
              "    });\n" .
              "    $('#admin_edit_sfa_remove_button').on ( 'click', function ()\n" .
              "    {\n" .
              "      var l = Ladda.create ( this);\n" .
              "      l.start ();\n" .
              "      $('#admin_edit_sfa_remove').find ( 'button[data-dismiss=\"modal\"]').attr ( 'disabled', 'disabled');\n" .
              "      VoIP.rest ( '/admins/sfa', 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "      {\n" .
              "        if ( jqXHR.status == 204)\n" .
              "        {\n" .
              "          $('#admin_edit_sfa').data ( 'removed', true);\n" .
              "          $('#admin_edit_sfa_remove').modal ( 'hide');\n" .
              "          new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Second factor authentication disabled!") . "', type: 'success'});\n" .
              "        } else {\n" .
              "          new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Error disabling second factor authentication!") . "', type: 'error'});\n" .
              "          $('#admin_edit_sfa').data ( 'ignore', true).bootstrapToggle ( 'on');\n" .
              "        }\n" .
              "      }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "      {\n" .
              "        new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Error disabling second factor authentication!") . "', type: 'error'});\n" .
              "        $('#admin_edit_sfa').data ( 'ignore', true).bootstrapToggle ( 'on');\n" .
              "      }).always ( function ()\n" .
              "      {\n" .
              "        l.stop ();\n" .
              "        $('#admin_edit_sfa_remove').find ( 'button[data-dismiss=\"modal\"]').removeAttr ( 'disabled');\n" .
              "      });\n" .
              "    });\n" .
              "    $('#admin_edit_sfa_remove').on ( 'shown.bs.modal', function ()\n" .
              "    {\n" .
              "      $(this).find ( 'button[data-dismiss=\"modal\"]').removeAttr ( 'disabled');\n" .
              "    }).on ( 'hide.bs.modal', function ()\n" .
              "    {\n" .
              "      if ( $('#admin_edit_sfa').data ( 'removed') == true)\n" .
              "      {\n" .
              "        $('#admin_edit_sfa').data ( 'removed', false);\n" .
              "      } else {\n" .
              "        $('#admin_edit_sfa').data ( 'ignore', true).bootstrapToggle ( 'on');\n" .
              "      }\n" .
              "    });\n" .
              "  }\n" .
              "  $('#admin_edit_name').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/admins/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#admin_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Edit administrator") . "', text: '" . __ ( "Error retrieving administrator data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#admin_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/admins/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Edit administrator") . "',\n" .
              "    fail: '" . __ ( "Error changing administrator!") . "',\n" .
              "    success: '" . __ ( "Administrator changed successfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/admins', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#admin_edit_form').data ( 'formData');\n" .
              "  formData.SFA = $('#admin_edit_sfa').prop ( 'checked');\n" .
              "  $('#admin_edit_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}
?>
