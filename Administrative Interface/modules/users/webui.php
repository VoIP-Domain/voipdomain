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
 * VoIP Domain users module WebUI. This module add the feature to manage
 * Adminstrative Interface users.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Users
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/users", "users_search_page", array ( "permissions" => "administrator"));
framework_add_hook ( "users_search_page", "users_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/users/add", "users_add_page", array ( "permissions" => "administrator"));
framework_add_hook ( "users_add_page", "users_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/users/:id/clone", "users_clone_function", array ( "permissions" => "administrator"));
framework_add_hook ( "users_clone_function", "users_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/users/:id/view", "users_view_page", array ( "permissions" => "administrator"));
framework_add_hook ( "users_view_page", "users_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/users/:id/edit", "users_edit_page", array ( "permissions" => "administrator"));
framework_add_hook ( "users_edit_page", "users_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main users page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Users"));
  sys_set_subtitle ( __ ( "users search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Users"))
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
   * User search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add user remove modal code
   */
  $output .= "<div id=\"user_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"user_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "User removal") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the user %s (%s)?"), "<span id=\"user_delete_name\"></span>", "<span id=\"user_delete_username\"></span>") . "</p><input type=\"hidden\" id=\"user_delete_id\" value=\"\"></div>\n";
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
              "  data: VoIP.APIsearch ( { path: '/users', fields: 'ID,Name,Username,NULL'}),\n" .
              "  columns: [\n" .
              "             { data: 'Name', title: '" . __ ( "Name") . "', width: '60%', class: 'export min-tablet-l'},\n" .
              "             { data: 'Username', title: '" . __ ( "Username") . "', width: '30%', class: 'export all'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/users/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/users/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/users/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-name=\"' + row.Name + '\" data-username=\"' + row.Username + '\"' + ( VoIP.getUID () == row.ID ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/users/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#user_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#user_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#user_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#user_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#user_delete_username').html ( $(this).data ( 'username'));\n" .
              "  $('#user_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#user_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/users/' + encodeURIComponent ( $('#user_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#user_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "User removal") . "', text: '" . __ ( "User removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#user_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "User removal") . "', text: '" . __ ( "Error removing user!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the user add page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_add_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Users"));
  sys_set_subtitle ( __ ( "users addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Users"), "link" => "/users"),
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
  $output = "<form class=\"form-horizontal\" id=\"user_add_form\">\n";

  // Add user name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"user_add_name\" placeholder=\"" . __ ( "User name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user login field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"user_add_username\" placeholder=\"" . __ ( "User login name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user email field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"email\" name=\"Email\" class=\"form-control\" id=\"user_add_email\" placeholder=\"" . __ ( "User e-mail") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user password field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"Password\" class=\"form-control\" id=\"user_add_password\" placeholder=\"" . __ ( "User password") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user password confirmation field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_confirmation\" class=\"control-label col-xs-2\">" . __ ( "Confirmation") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"Confirmation\" class=\"form-control\" id=\"user_add_confirmation\" placeholder=\"" . __ ( "User password confirmation") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Language\" id=\"user_add_language\" class=\"form-control\" data-placeholder=\"" . __ ( "User language") . "\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( __ ( $language))) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user administrator option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_administrator\" class=\"control-label col-xs-2\">" . __ ( "Administrator") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"Administrator\" value=\"true\" id=\"user_add_administrator\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user auditor option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_auditor\" class=\"control-label col-xs-2\">" . __ ( "Auditor") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"Auditor\" value=\"true\" id=\"user_add_auditor\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/users\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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
  sys_addjs ( "$('#user_add_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#user_add_language').select2 (\n" .
              "{\n" .
              "  allowClear: false\n" .
              "});\n" .
              "$('#user_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#user_add_name'),\n" .
              "    URL: '/users',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "User addition") . "',\n" .
              "    fail: '" . __ ( "Error adding user!") . "',\n" .
              "    success: '" . __ ( "User added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/users', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");  

  return $output;
}

/**
 * Function to generate the user clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/users/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/users/add', true, function ()\n" .
              "  {\n" .
              "    $('#user_add_name').val ( data.Name);\n" .
              "    $('#user_add_username').val ( data.Username);\n" .
              "    $('#user_add_email').val ( data.Email);\n" .
              "    $('#user_add_language').val ( data.Language).trigger ( 'change');\n" .
              "    $('#user_add_administrator').bootstrapToggle ( ( data.Administrator ? 'on' : 'off'));\n" .
              "    $('#user_add_auditor').bootstrapToggle ( ( data.Auditor ? 'on' : 'off'));\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "User cloning") . "', text: '" . __ ( "Error requesting user data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the user view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Users"));
  sys_set_subtitle ( __ ( "users visualization"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Users"), "link" => "/users"),
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
  $output = "<form class=\"form-horizontal\" id=\"user_view_form\">\n";

  // Add user name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"user_view_name\" placeholder=\"" . __ ( "User name") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user login field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"user_view_username\" placeholder=\"" . __ ( "User login name") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user email field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"email\" name=\"Email\" class=\"form-control\" id=\"user_view_email\" placeholder=\"" . __ ( "User e-mail") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Language\" id=\"user_view_language\" class=\"form-control\" data-placeholder=\"" . __ ( "User language") . "\"  disabled=\"disabled\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( __ ( $language))) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user administrator option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_administrator\" class=\"control-label col-xs-2\">" . __ ( "Administrator") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"Administrator\" value=\"true\" id=\"user_view_administrator\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user auditor option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_auditor\" class=\"control-label col-xs-2\">" . __ ( "Auditor") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"Auditor\" value=\"true\" id=\"user_view_auditor\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add SFA option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_sfa\" class=\"control-label col-xs-2\">" . __ ( "Second factor authentication") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"SFA\" value=\"true\" id=\"user_view_sfa\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/users\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#user_view_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#user_view_language').select2 ();\n" .
              "$('#user_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#user_view_name').val ( data.Name);\n" .
              "  $('#user_view_username').val ( data.Username);\n" .
              "  $('#user_view_email').val ( data.Email);\n" .
              "  $('#user_view_language').val ( data.Language).trigger ( 'change');\n" .
              "  $('#user_view_administrator').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.Administrator ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#user_view_auditor').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.Auditor ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#user_view_2fa').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.SFA ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#user_view_name').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/users/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#user_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "User visualization") . "', text: '" . __ ( "Error requesting user data!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the user edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function users_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Users"));
  sys_set_subtitle ( __ ( "users edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Users"), "link" => "/users"),
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
  sys_addjs ( array ( "name" => "jquery-qrcode", "src" => "/vendors/jquery-qrcode/dist/jquery-qrcode.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"user_edit_form\">\n";

  // Add user name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"user_edit_name\" placeholder=\"" . __ ( "User name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user login field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_username\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Username\" class=\"form-control\" id=\"user_edit_username\" placeholder=\"" . __ ( "User login name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user email field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"email\" name=\"Email\" class=\"form-control\" id=\"user_edit_email\" placeholder=\"" . __ ( "User e-mail") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user password field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"Password\" class=\"form-control\" id=\"user_edit_password\" placeholder=\"" . __ ( "User password") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user password confirmation field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_confirmation\" class=\"control-label col-xs-2\">" . __ ( "Confirmation") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"Confirmation\" class=\"form-control\" id=\"user_edit_confirmation\" placeholder=\"" . __ ( "User password confirmation") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Language\" id=\"user_edit_language\" class=\"form-control\" data-placeholder=\"" . __ ( "User language") . "\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( __ ( $language))) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user administrator option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_administrator\" class=\"control-label col-xs-2\">" . __ ( "Administrator") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"Administrator\" value=\"true\" id=\"user_edit_administrator\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user auditor option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_auditor\" class=\"control-label col-xs-2\">" . __ ( "Auditor") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"Auditor\" value=\"true\" id=\"user_edit_auditor\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user SFA option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_sfa\" class=\"control-label col-xs-2\">" . __ ( "Second factor authentication") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"SFA\" value=\"true\" id=\"user_edit_sfa\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/users\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  // Second factor authentication activation modal
  $output .= "\n";
  $output .= "<div id=\"user_edit_sfa_activate\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"user_edit_sfa_activate\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Second factor authentication") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <img src=\"/img/sfa.png\" alt=\"\" class=\"dt-right\">\n";
  $output .= "        <p>" . __ ( "Follow the steps below to set up the second factor authentication app") . ":</p>\n";
  $output .= "        <p><strong>1) " . __ ( "Download a two-step authenticator app.") . "</strong> " . __ ( "The apps below are the recommended one, but any authentication app also works.") . "</p>\n";
  $output .= "        <p>\n";
  $output .= "          <i class=\"fab fa-apple\"></i> " . __ ( "iOS devices") . ": <a href=\"https://itunes.apple.com/us/app/authy/id494168017\" target=\"_blank\">Authy</a><br />\n";
  $output .= "          <i class=\"fab fa-android\"></i> " . __ ( "Android devices") . ": <a href=\"https://play.google.com/store/apps/details?id=com.authy.authy\" target=\"_blank\">Authy</a><br />\n";
  $output .= "          <i class=\"fab fa-windows\"></i> " . __ ( "Windows devices") . ": <a href=\"https://www.microsoft.com/p/authenticator/9wzdncrfj3rj\" target=\"_blank\">Microsoft Authenticator</a>\n";
  $output .= "        <p><strong>2) " . __ ( "Scan the below QR Code with your authenticator app.") . "</strong> " . __ ( "Or enter the key code below.") . "</p>\n";
  $output .= "        <div class=\"dt-center\"><div id=\"user_edit_sfa_activate_qrcode\" width=\"200\" height=\"200\"></div><strong><br />" . __ ( "Key") . "</strong>: <code id=\"user_edit_sfa_activate_key\" title=\"" . __ ( "Key") . "\" aria-label=\"" . __ ( "Key") . "\"></code></div>\n";
  $output .= "        <p><label for=\"user_edit_sfa_activate_code\"><strong>3)</strong> " . __ ( "Enter the 6 digit verification code from the app") . ":</label> <input type=\"text\" id=\"user_edit_sfa_activate_code\" value=\"\" maxlength=\"6\" size=\"6\" autocomplete=\"off\" autocapitalize=\"none\" autocorrect=\"none\" spellcheck=\"false\"></p>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn btn-primary ladda-button\" id=\"user_edit_sfa_activate_button\" data-style=\"expand-left\">" . __ ( "Activate") . "</button>\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  // Second factor authentication removal modal
  $output .= "\n";
  $output .= "<div id=\"user_edit_sfa_remove\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"user_edit_sfa_remove\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header alert-danger\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Second factor authentication") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <p><strong>" . __ ( "Warning") . ":</strong> " . __ ( "Disabling the second factor authentication will destroy your key. You will need to setup another key to re-enable it.") ."</p>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn btn-danger ladda-button\" id=\"user_edit_sfa_remove_button\" data-style=\"expand-left\">" . __ ( "Disable") . "</button>\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#user_edit_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#user_edit_language').select2 (\n" .
              "{\n" .
              "  allowClear: false\n" .
              "});\n" .
              "$('#user_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#user_edit_name').val ( data.Name);\n" .
              "  $('#user_edit_username').val ( data.Username);\n" .
              "  $('#user_edit_email').val ( data.Email);\n" .
              "  $('#user_edit_language').val ( data.Language).trigger ( 'change');\n" .
              "  $('#user_edit_administrator').bootstrapToggle ( ( data.Administrator ? 'on' : 'off'));\n" .
              "  $('#user_edit_auditor').bootstrapToggle ( ( data.Auditor ? 'on' : 'off'));\n" .
              "  $('#user_edit_sfa').bootstrapToggle ( ( data.SFA ? 'on' : 'off'));\n" .
              "  if ( VoIP.getUID () != VoIP.parameters.id)\n" .
              "  {\n" .
              "    $('#user_edit_sfa').bootstrapToggle ( 'disable');\n" .
              "  } else {\n" .
              "    $('#user_edit_sfa_activate_code').mask ( '000000');\n" .
              "    $('#user_edit_sfa').on ( 'change', function ( e)\n" .
              "    {\n" .
              "      if ( ! $(this).data ( 'ignore') && $(this).prop ( 'checked'))\n" .
              "      {\n" .
              "        $(this).bootstrapToggle ( 'disable');\n" .
              "        var that = this;\n" .
              "        VoIP.rest ( '/users/sfa', 'POST').done ( function ( data, textStatus, jqXHR)\n" .
              "        {\n" .
              "          $('#user_edit_sfa_activate_key').html ( data.Key);\n" .
              "          $('#user_edit_sfa_activate_qrcode').empty ().qrcode (\n" .
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
              "          $('#user_edit_sfa_activate_code').val ( '').alerts ( 'clear');\n" .
              "          $('#user_edit_sfa_activate').modal ( 'show');\n" .
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
              "          $('#user_edit_sfa_remove').modal ( 'show');\n" .
              "        }\n" .
              "      }\n" .
              "    });\n" .
              "    $('#user_edit_sfa_activate_button').on ( 'click', function ()\n" .
              "    {\n" .
              "      $('#user_edit_sfa_activate_code').alerts ( 'clear');\n" .
              "      var l = Ladda.create ( this);\n" .
              "      l.start ();\n" .
              "      $('#user_edit_sfa_activate').find ( 'button[data-dismiss=\"modal\"]').attr ( 'disabled', 'disabled');\n" .
              "      VoIP.rest ( '/users/sfa', 'POST', { 'Code': $('#user_edit_sfa_activate_code').val ()}).done ( function ( data, textStatus, jqXHR)\n" .
              "      {\n" .
              "        if ( jqXHR.status == 201)\n" .
              "        {\n" .
              "          $('#user_edit_sfa').data ( 'activated', true);\n" .
              "          $('#user_edit_sfa_activate').modal ( 'hide');\n" .
              "          new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Second factor authentication activated!") . "', type: 'success'});\n" .
              "        } else {\n" .
              "          if ( data.hasOwnProperty ( 'Code'))\n" .
              "          {\n" .
              "            $('#user_edit_sfa_activate_code').alerts ( 'add', $.extend ( {}, {}, { message: data.Code}));\n" .
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
              "        $('#user_edit_sfa_activate').find ( 'button[data-dismiss=\"modal\"]').removeAttr ( 'disabled');\n" .
              "      });\n" .
              "    });\n" .
              "    $('#user_edit_sfa_activate').on ( 'shown.bs.modal', function ()\n" .
              "    {\n" .
              "      $(this).find ( 'button[data-dismiss=\"modal\"]').removeAttr ( 'disabled');\n" .
              "      $('#user_edit_sfa_activate_code').focus ();\n" .
              "    }).on ( 'hide.bs.modal', function ()\n" .
              "    {\n" .
              "      if ( $('#user_edit_sfa').data ( 'activated') == true)\n" .
              "      {\n" .
              "        $('#user_edit_sfa').data ( 'activated', false);\n" .
              "      } else {\n" .
              "        $('#user_edit_sfa').data ( 'ignore', true).bootstrapToggle ( 'off');\n" .
              "      }\n" .
              "    });\n" .
              "    $('#user_edit_sfa_remove_button').on ( 'click', function ()\n" .
              "    {\n" .
              "      var l = Ladda.create ( this);\n" .
              "      l.start ();\n" .
              "      $('#user_edit_sfa_remove').find ( 'button[data-dismiss=\"modal\"]').attr ( 'disabled', 'disabled');\n" .
              "      VoIP.rest ( '/users/sfa', 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "      {\n" .
              "        if ( jqXHR.status == 204)\n" .
              "        {\n" .
              "          $('#user_edit_sfa').data ( 'removed', true);\n" .
              "          $('#user_edit_sfa_remove').modal ( 'hide');\n" .
              "          new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Second factor authentication disabled!") . "', type: 'success'});\n" .
              "        } else {\n" .
              "          new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Error disabling second factor authentication!") . "', type: 'error'});\n" .
              "          $('#user_edit_sfa').data ( 'ignore', true).bootstratToggle ( 'on');\n" .
              "        }\n" .
              "      }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "      {\n" .
              "        new PNotify ( { title: '" . __ ( "Second factor authentication") . "', text: '" . __ ( "Error disabling second factor authentication!") . "', type: 'error'});\n" .
              "        $('#user_edit_sfa').data ( 'ignore', true).bootstratToggle ( 'on');\n" .
              "      }).always ( function ()\n" .
              "      {\n" .
              "        l.stop ();\n" .
              "        $('#user_edit_sfa_remove').find ( 'button[data-dismiss=\"modal\"]').removeAttr ( 'disabled');\n" .
	      "      });\n" .
	      "    });\n" .
              "    $('#user_edit_sfa_remove').on ( 'shown.bs.modal', function ()\n" .
              "    {\n" .
              "      $(this).find ( 'button[data-dismiss=\"modal\"]').removeAttr ( 'disabled');\n" .
              "    }).on ( 'hide.bs.modal', function ()\n" .
              "    {\n" .
              "      if ( $('#user_edit_sfa').data ( 'removed') == true)\n" .
              "      {\n" .
              "        $('#user_edit_sfa').data ( 'removed', false);\n" .
              "      } else {\n" .
              "        $('#user_edit_sfa').data ( 'ignore', true).bootstrapToggle ( 'on');\n" .
              "      }\n" .
              "    });\n" .
              "  }\n" .
              "  $('#user_edit_name').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/users/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#user_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "User edition") . "', text: '" . __ ( "Error requesting user data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#user_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/users/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "User edition") . "',\n" .
              "    fail: '" . __ ( "Error changing user!") . "',\n" .
              "    success: '" . __ ( "User changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/users', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}
?>
