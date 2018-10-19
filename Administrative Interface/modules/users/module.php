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
 * VoIP Domain users module. This module add the feature of usering incoming
 * public external calls. Usefull to user tele marketing, spammers and other
 * annoying calls.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Users
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/users", "users_search_page", array ( "permissions" => "administrator"));
framework_add_hook ( "users_search_page", "users_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/users/add", "users_add_page", array ( "permissions" => "administrator"));
framework_add_hook ( "users_add_page", "users_add_page", IN_HOOK_INSERT_FIRST);

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
   * User search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Name") . "</th>\n";
  $output .= "      <th>" . __ ( "Username") . "</th>\n";
  $output .= "      <th>" . __ ( "Actions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add user remove modal code
   */
  $output .= "<div id=\"user_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"user_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "User removal") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the user %s (%s)?"), "<span id=\"user_delete_nome\"></span>", "<span id=\"user_delete_user\"></span>") . "</p><input type=\"hidden\" id=\"user_delete_id\" value=\"\"></div>\n";
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
              "  data: VoIP.dataTables ( '/users/fetch', 'GET'),\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 3 ]},\n" .
              "                { searchable: false, targets: [ 0, 3 ]},\n" .
              "                { data: 'links', render: function ( data, type, full) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/users/' + full[0] + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/users/' + full[0] + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + full[0] + '\" data-name=\"' + full[1] + '\" data-user=\"' + full[2] + '\"' + ( VoIP.getUID () == full[0] ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, targets: [ 3 ]},\n" .
              "                { visible: false, targets: [ 0 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '60%', class: 'export min-tablet-l'},\n" .
              "             { width: '30%', class: 'export all'},\n" .
              "             { width: '10%', class: 'all'}\n" .
              "           ]\n" .
              "}));\n" .
              "$('#fastsearch').val ( '');\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/users/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#user_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#user_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#user_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#user_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#user_delete_user').html ( $(this).data ( 'user'));\n" .
              "  $('#user_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#user_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var user = VoIP.rest ( '/users/' + $('#user_delete_id').val (), 'DELETE');\n" .
              "  if ( user.API.status == 'ok')\n" .
              "  {\n" .
              "    $('#user_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "User removal") . "', text: '" . __ ( "User removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#user_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "User removal") . "', text: '" . __ ( "Error removing user!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
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
  $output .= "      <input type=\"text\" name=\"name\" class=\"form-control\" id=\"user_add_name\" placeholder=\"" . __ ( "User name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user login field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_user\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"user\" class=\"form-control\" id=\"user_add_user\" placeholder=\"" . __ ( "User login name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user email field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"email\" class=\"form-control\" id=\"user_add_email\" placeholder=\"" . __ ( "User e-mail") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user password field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"password\" class=\"form-control\" id=\"user_add_password\" placeholder=\"" . __ ( "User password") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user password confirmation field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_confirmation\" class=\"control-label col-xs-2\">" . __ ( "Confirmation") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"confirmation\" class=\"form-control\" id=\"user_add_confirmation\" placeholder=\"" . __ ( "User password confirmation") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"language\" id=\"user_add_language\" class=\"form-control\" data-placeholder=\"" . __ ( "User language") . "\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( $language)) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user administrator option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_administrator\" class=\"control-label col-xs-2\">" . __ ( "Administrator") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"administrator\" id=\"user_add_administrator\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user auditor option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_add_auditor\" class=\"control-label col-xs-2\">" . __ ( "Auditor") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"auditor\" id=\"user_add_auditor\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/users\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Add") . "</button>\n";
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
              "$('#user_add_name').focus ();\n" .
              "$('#user_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
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
  $output .= "      <input type=\"text\" name=\"name\" class=\"form-control\" id=\"user_view_name\" placeholder=\"" . __ ( "User name") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user login field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_user\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"user\" class=\"form-control\" id=\"user_view_user\" placeholder=\"" . __ ( "User login name") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user email field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"email\" class=\"form-control\" id=\"user_view_email\" placeholder=\"" . __ ( "User e-mail") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"language\" id=\"user_view_language\" class=\"form-control\" data-placeholder=\"" . __ ( "User language") . "\"  disabled=\"disabled\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( $language)) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user administrator option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_administrator\" class=\"control-label col-xs-2\">" . __ ( "Administrator") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"administrator\" id=\"user_view_administrator\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user auditor option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_view_auditor\" class=\"control-label col-xs-2\">" . __ ( "Auditor") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"auditor\" id=\"user_view_auditor\" class=\"form-control\" disabled=\"disabled\" />\n";
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
              "  $('#user_view_name').val ( data.name);\n" .
              "  $('#user_view_user').val ( data.user);\n" .
              "  $('#user_view_email').val ( data.email);\n" .
              "  $('#user_view_language').val ( data.language).trigger ( 'change');\n" .
              "  $('#user_view_administrator').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.administrator ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#user_view_auditor').bootstrapToggle ( 'enable').bootstrapToggle ( ( data.auditor ? 'on' : 'off')).bootstrapToggle ( 'disable');\n" .
              "  $('#user_view_name').focus ();\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var data = VoIP.rest ( '/users/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( data.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#user_view_form').trigger ( 'fill', data.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "User visualization") . "', text: '" . __ ( "Error requesting user data!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n");

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

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"user_edit_form\">\n";

  // Add user name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"name\" class=\"form-control\" id=\"user_edit_name\" placeholder=\"" . __ ( "User name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user login field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_user\" class=\"control-label col-xs-2\">" . __ ( "Username") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"user\" class=\"form-control\" id=\"user_edit_user\" placeholder=\"" . __ ( "User login name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user email field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"email\" class=\"form-control\" id=\"user_edit_email\" placeholder=\"" . __ ( "User e-mail") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user password field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"password\" class=\"form-control\" id=\"user_edit_password\" placeholder=\"" . __ ( "User password") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user password confirmation field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_confirmation\" class=\"control-label col-xs-2\">" . __ ( "Confirmation") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"password\" name=\"confirmation\" class=\"form-control\" id=\"user_edit_confirmation\" placeholder=\"" . __ ( "User password confirmation") . "\" autocomplete=\"off\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user language field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"language\" id=\"user_edit_language\" class=\"form-control\" data-placeholder=\"" . __ ( "User language") . "\">\n";
  $output .= "        <option value=\"default\" selected>" . __ ( "System default language") . "</option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "        <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( $language)) . "</option>\n";
  }
  $output .= "      </select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user administrator option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_administrator\" class=\"control-label col-xs-2\">" . __ ( "Administrator") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"administrator\" id=\"user_edit_administrator\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add user auditor option
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"user_edit_auditor\" class=\"control-label col-xs-2\">" . __ ( "Auditor") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"auditor\" id=\"user_edit_auditor\" class=\"form-control\" />\n";
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
              "  $('#user_edit_name').val ( data.name);\n" .
              "  $('#user_edit_user').val ( data.user);\n" .
              "  $('#user_edit_email').val ( data.email);\n" .
              "  $('#user_edit_language').val ( data.language).trigger ( 'change');\n" .
              "  $('#user_edit_administrator').bootstrapToggle ( ( data.administrator ? 'on' : 'off'));\n" .
              "  $('#user_edit_auditor').bootstrapToggle ( ( data.auditor ? 'on' : 'off'));\n" .
              "  $('#user_edit_name').focus ();\n" .
              "});\n" .
              "setTimeout ( function ()\n" .
              "{\n" .
              "  var data = VoIP.rest ( '/users/' + VoIP.parameters.id, 'GET');\n" .
              "  if ( data.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#user_edit_form').trigger ( 'fill', data.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "User edition") . "', text: '" . __ ( "Error requesting user data!") . "', type: 'error'});\n" .
              "  }\n" .
              "}, 0);\n" .
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
