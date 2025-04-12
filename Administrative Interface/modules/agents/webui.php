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
 * VoIP Domain agents module WebUI. This module add the feature to manage call
 * queue agents.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Agents
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/agents", "agents_search_page");
framework_add_hook ( "agents_search_page", "agents_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/agents/add", "agents_add_page");
framework_add_hook ( "agents_add_page", "agents_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/agents/:id/clone", "agents_clone_function");
framework_add_hook ( "agents_clone_function", "agents_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/agents/:id/view", "agents_view_page");
framework_add_hook ( "agents_view_page", "agents_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/agents/:id/edit", "agents_edit_page");
framework_add_hook ( "agents_edit_page", "agents_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main agents page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Agents"));
  sys_set_subtitle ( __ ( "agents search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Agents"))
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
   * Agent search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add agent remove modal code
   */
  $output .= "<div id=\"agent_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"agent_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove agent") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the agent %s (%s)?"), "<span id=\"agent_delete_name\"></span>", "<span id=\"agent_delete_code\"></span>") . "</p><input type=\"hidden\" id=\"agent_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Name', title: '" . __ ( "Name") . "', width: '70%', class: 'export all'},\n" .
              "             { data: 'Code', title: '" . __ ( "Code") . "', width: '20%', class: 'export min-table-l'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/agents/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/agents/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/agents/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-name=\"' + row.Name + '\" data-code=\"' + row.Code + '\"><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/agents', fields: 'ID,Name,Code,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/agents/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#agent_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#agent_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#agent_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#agent_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#agent_delete_code').html ( $(this).data ( 'code'));\n" .
              "  $('#agent_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#agent_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/agents/' + encodeURIComponent ( $('#agent_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#agent_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Agent removal") . "', text: '" . __ ( "Agent removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#agent_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Agent removal") . "', text: '" . __ ( "Error removing agent!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the agent add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Agents"));
  sys_set_subtitle ( __ ( "agents addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Agents"), "link" => "/agents"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"agent_add_form\">\n";

  // Add agent name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"agent_add_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"agent_add_name\" placeholder=\"" . __ ( "Agent name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add agent code field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"agent_add_code\" class=\"control-label col-xs-2\">" . __ ( "Code") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Code\" class=\"form-control\" id=\"agent_add_code\" placeholder=\"" . __ ( "Agent code") . "\" maxlength=\"4\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add agent password field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"agent_add_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group\">\n";
  $output .= "        <input type=\"text\" name=\"Password\" id=\"agent_add_password\" class=\"form-control\" placeholder=\"" . __ ( "Agent password") . "\" maxlength=\"6\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/agents\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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
  sys_addjs ( "$('#agent_add_code').mask ( '0#');\n" .
              "$('#agent_add_password').mask ( '000000');\n" .
              "$('button.btn-random').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( Math.floor ( 100000 + Math.random ( ) * 900000));\n" .
              "});\n" .
              "$('#agent_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#agent_add_name'),\n" .
              "    URL: '/agents',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Agent addition") . "',\n" .
              "    fail: '" . __ ( "Error adding agent!") . "',\n" .
              "    success: '" . __ ( "Agent added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/agents', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");  

  return $output;
}

/**
 * Function to generate the agent clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/agents/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/agents/add', true, function ()\n" .
              "  {\n" .
              "    $('#agent_add_name').val ( data.Name);\n" .
              "    $('#agent_add_code').val ( data.Code);\n" .
              "    $('#agent_add_password').val ( data.Password);\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Agent cloning") . "', text: '" . __ ( "Error requesting agent data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the agent view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Agents"));
  sys_set_subtitle ( __ ( "agents visualization"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Agents"), "link" => "/agents"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"agent_view_form\">\n";

  // Add agent name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"agent_view_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"agent_view_name\" placeholder=\"" . __ ( "Agent name") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add agent code field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"agent_view_code\" class=\"control-label col-xs-2\">" . __ ( "Code") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Code\" class=\"form-control\" id=\"agent_view_code\" placeholder=\"" . __ ( "Agent code") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add agent password field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"agent_view_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Password\" class=\"form-control\" id=\"agent_view_password\" placeholder=\"" . __ ( "Agent password") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/agents\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#agent_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#agent_view_name').val ( data.Name);\n" .
              "  $('#agent_view_code').val ( data.Code);\n" .
              "  $('#agent_view_password').val ( data.Password);\n" .
              "});\n" .
              "VoIP.rest ( '/agents/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#agent_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Agent view") . "', text: '" . __ ( "Error viewing agent!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the agent edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function agents_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Agents"));
  sys_set_subtitle ( __ ( "agents editing"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Agents"), "link" => "/agents"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"agent_edit_form\">\n";

  // Add agent name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"agent_edit_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"agent_edit_name\" placeholder=\"" . __ ( "Agent name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add agent code field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"agent_edit_code\" class=\"control-label col-xs-2\">" . __ ( "Code") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Code\" class=\"form-control\" id=\"agent_edit_code\" placeholder=\"" . __ ( "Agent code") . "\" maxlength=\"4\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add agent password field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"agent_edit_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group\">\n";
  $output .= "        <input type=\"text\" name=\"Password\" id=\"agent_edit_password\" class=\"form-control\" placeholder=\"" . __ ( "Agent password") . "\" maxlength=\"6\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/agents\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#agent_edit_code').mask ( '0#');\n" .
              "$('#agent_edit_password').mask ( '000000');\n" .
              "$('button.btn-random').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( Math.floor ( 100000 + Math.random ( ) * 900000));\n" .
              "});\n" .
              "$('#agent_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#agent_edit_name').val ( data.Name);\n" .
              "  $('#agent_edit_code').val ( data.Code);\n" .
              "  $('#agent_edit_password').val ( data.Password);\n" .
              "  $('#agent_edit_name').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/agents/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#agent_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Agent edition") . "', text: '" . __ ( "Error requesting agent data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#agent_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/agents/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Agent edition") . "',\n" .
              "    fail: '" . __ ( "Error changing agent!") . "',\n" .
              "    success: '" . __ ( "Agent changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/agents', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}
?>
