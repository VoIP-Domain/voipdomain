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
 * VoIP Domain IVRs module WebUI. This module add the feature of creating IVR
 * (Interactive Voice Response) workflows.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage IVR
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/ivrs", "ivrs_search_page", array ( "permissions" => "administrator"));
framework_add_hook ( "ivrs_search_page", "ivrs_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/ivrs/add", "ivrs_add_page", array ( "permissions" => "administrator"));
framework_add_hook ( "ivrs_add_page", "ivrs_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/ivrs/:id/clone", "ivrs_clone_function", array ( "permissions" => "administrator"));
framework_add_hook ( "ivrs_clone_function", "ivrs_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/ivrs/:id/view", "ivrs_view_page", array ( "permissions" => "administrator"));
framework_add_hook ( "ivrs_view_page", "ivrs_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/ivrs/:id/edit", "ivrs_edit_page", array ( "permissions" => "administrator"));
framework_add_hook ( "ivrs_edit_page", "ivrs_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main IVR page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "IVR"));
  sys_set_subtitle ( __ ( "IVR search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "IVR"))
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
   * IVR search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add IVR remove modal code
   */
  $output .= "<div id=\"ivr_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"ivr_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "IVR removal") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the IVR %s (%s)?"), "<span id=\"ivr_delete_name\"></span>", "<span id=\"ivr_delete_description\"></span>") . "</p><input type=\"hidden\" id=\"ivr_delete_id\" value=\"\"></div>\n";
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
              "             { data: 'Name', title: '" . __ ( "Name") . "', width: '25%', class: 'export all'},\n" .
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '60%', class: 'export min-tablet-l'},\n" .
              "             { data: 'Uses', title: '" . __ ( "Uses") . "', width: '5%', class: 'export min-tablet-l'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/ivrs/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/ivrs/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/ivrs/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-name=\"' + row.Name + '\" data-description=\"' + row.Description + '\"' + ( row.Uses != 0 ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/ivrs', fields: 'ID,Name,Description,Uses,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/ivrs/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#ivr_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#ivr_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#ivr_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#ivr_delete_name').html ( $(this).data ( 'name'));\n" .
              "  $('#ivr_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#ivr_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#ivr_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/ivrs/' + encodeURIComponent ( $('#ivr_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#ivr_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "IVR removal") . "', text: '" . __ ( "IVR removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#ivr_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "IVR removal") . "', text: '" . __ ( "Error removing IVR!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the IVR add page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_add_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "IVR"));
  sys_set_subtitle ( __ ( "IVR addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "IVR"), "link" => "/ivrs"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "workflow", "src" => "/css/workflow.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/dist/themes/base/jquery-ui.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "jquery-flowchart", "src" => "/vendors/jquery.flowchart/jquery.flowchart.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    if ( $operator["requiredcss"])
    {
      foreach ( $operator["requiredcss"] as $css)
      {
        sys_addcss ( $css);
      }
    }
  }

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/dist/jquery-ui.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-panzoom", "src" => "/vendors/panzoom/dist/jquery.panzoom.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-mousewheel", "src" => "/vendors/jquery-mousewheel/jquery.mousewheel.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-flowchart", "src" => "/vendors/jquery.flowchart/jquery.flowchart.js", "dep" => array ( "jquery-ui", "jquery-panzoom", "jquery-mousewheel")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    if ( $operator["requiredjs"])
    {
      foreach ( $operator["requiredjs"] as $js)
      {
        sys_addjs ( $js);
      }
    }
  }

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"ivr_add_form\">\n";

  // Add IVR name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"ivr_add_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"ivr_add_name\" placeholder=\"" . __ ( "IVR name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add IVR description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"ivr_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"ivr_add_description\" placeholder=\"" . __ ( "IVR description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add IVR flowchart icons field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Actions") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 workflow-itens\">\n";
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    $inputs = array ( "input" => __ ( "In"));
    if ( ! is_array ( $operator["outputs"]))
    {
      $outputs = array ();
      if ( $operator["outputs"] == 1)
      {
        $outputs["output"] = __ ( "Out");
      } else {
        for ( $x = 0; $x < $operator["outputs"]; $x++)
        {
          $outputs["output_" . $x] = __ ( "Out") . " " . ( $x + 1);
        }
      }
    } else {
      $outputs = $operator["outputs"];
    }
    $output .= "      <span class=\"workflow-element dlg-" . $operator["name"] . " btn btn-" . $operator["style"] .  "\" data-name=\"" . $operator["name"] . "\" data-inputs=\"" . base64_encode ( json_encode ( $inputs)) . "\" data-outputs=\"" . base64_encode ( json_encode ( $outputs)) . "\"" . ( $operator["modal"] ? " data-modal=\"" . $operator["name"] . "\"" : "") . "><i class=\"fa fa-" . $operator["icon"] . "\"></i> " . __ ( $operator["title"]) . "</span>\n";
  }
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add IVR flowchart field
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"workflow_container\">\n";
  $output .= "      <div id=\"ivr_add_workflow\" class=\"workflow\"></div>\n";
  $output .= "      <div class=\"workflow_controls\">\n";
  $output .= "        <i class=\"fa fa-search-minus\" data-mode=\"zoomin\" title=\"" . __ ( "Zoom out") . "\"></i>\n";
  $output .= "        <i class=\"fa fa-home\" data-mode=\"reset\" title=\"" . __ ( "Zoom to fit") . "\"></i>\n";
  $output .= "        <i class=\"fa fa-search-plus\" data-mode=\"zoomout\" title=\"" . __ ( "Zoom in") . "\"></i>\n";
  $output .= "        <i class=\"fa fa-trash-alt\" data-mode=\"delete\" title=\"" . __ ( "Delete selected") . "\"></i>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/ivrs\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
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
   * Add workflow elements dialogs
   */

  // Start dialog
  $output .= "<div id=\"workflow-dialog-start\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"workflow-dialog-start\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Start options") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"workflow-dialog-start_form\">\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"workflow-dialog-start_autoanswer\" class=\"control-label col-xs-2\">" . __ ( "Auto answer") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"checkbox\" name=\"autoanswer\" value=\"true\" id=\"workflow-dialog-start_autoanswer\" class=\"form-control\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"workflow-dialog-start_delay\" class=\"control-label col-xs-2\">" . __ ( "Delay") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"text\" name=\"delay\" id=\"workflow-dialog-start_delay\" class=\"form-control\" placeholder=\"" . __ ( "Wait seconds before answer") . "\"/>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "        <button class=\"btn btn-primary\">" . __ ( "Apply") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  // Modular workflow modal contents
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    if ( $operator["modal"])
    {
      $output .= "<div id=\"workflow-dialog-" . $operator["name"] . "\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"workflow-dialog-" . $operator["name"] . "\" aria-hidden=\"true\">\n";
      $output .= "  <div class=\"modal-dialog modal-lg\">\n";
      $output .= "    <div class=\"modal-content\">\n";
      $output .= "      <div class=\"modal-header\">\n";
      $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
      $output .= "        <h3 class=\"modal-title\">" . __ ( $operator["modaltitle"]) . "</h3>\n";
      $output .= "      </div>\n";
      $output .= "      <div class=\"modal-body\">\n";
      $output .= preg_replace ( "/^/m", "        ", $operator["modalcontent"]);
      $output .= "      </div>\n";
      $output .= "      <div class=\"modal-footer\">\n";
      $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
      $output .= "        <button class=\"btn btn-primary\">" . __ ( "Apply") . "</button>\n";
      $output .= "      </div>\n";
      $output .= "    </div>\n";
      $output .= "  </div>\n";
      $output .= "</div>\n";

      if ( $operator["modalfocus"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . "').on ( 'shown.bs.modal', function ( e)\n" .
                    "{\n" .
                    "  e && e.preventDefault ();\n" .
                    "  $('#" . $operator["modalfocus"] . "').focus ();\n" .
                    "});\n");
      }

      if ( $operator["modalshow"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . "').on ( 'show.bs.modal', function ( e)\n" .
                    "{\n" .
                    "  if ( $(this).find ( '.required').length != 0 && $(this).find ( '.require-alert').length == 0)" .
                    "  {" .
                    "    $(this).find ( 'div .modal-body').append ( '<span class=\"require-alert dt-right\"><b style=\"color: #ff0000\">*</b> " . __ ( "required field.") . "</span><br />');" .
                    "  }" .
                    "  currentOperatorId = $(e.relatedTarget).closest ( 'div .flowchart-operator').data ( 'operator_id');\n" .
                    "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
                    "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
                    preg_replace ( "/^/m", "  ", $operator["modalshow"]) .
                    "});\n");
      }

      if ( $operator["modalsave"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . " .modal-footer button.btn-primary').on ( 'click', function ( e)\n" .
                    "{\n" .
                    "  e && e.preventDefault ();\n" .
                    "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
                    "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
                    "  $('#workflow-dialog-" . $operator["name"] . "').trigger ( 'validate');\n" .
                    "  if ( $('#workflow-dialog-" . $operator["name"] . "').data ( 'validation') != undefined && $('#workflow-dialog-" . $operator["name"] . "').data ( 'validation') != '')\n" .
                    "  {\n" .
                    "    new PNotify ( { title: '" . __ ( "Action validation error") . "', text: $('#workflow-dialog-" . $operator["name"] . "').data ( 'validation'), type: 'error'});\n" .
                    "    return false;\n" .
                    "  }\n" .
                    preg_replace ( "/^/m", "  ", $operator["modalsave"]) .
                    "  ivrBlock.properties.ivrData = ivrData;\n" .
                    "  flowchart.flowchart ( 'setOperatorData', currentOperatorId, ivrBlock);\n" .
                    "  $('#workflow-dialog-" . $operator["name"] . "').modal ( 'hide');\n" .
                    "  currentOperatorId = null;\n" .
                    "});\n");
      }

      if ( $operator["validation"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . "').on ( 'validate', function ( e)\n" .
                    "{\n" .
                    "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
                    "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
                    preg_replace ( "/^/m", "  ", $operator["validation"]) .
                    "  $(this).data ( 'validation', result ? result : '');\n" .
                    "});\n");
      }
    }

    // Modular workflow javascript codes
    if ( $operator["javascript"])
    {
      sys_addjs ( $operator["javascript"]);
    }
  }

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "var flowchart = $('#ivr_add_workflow');\n" .
              "flowchart.mode = 'add';\n" .
              "var container = flowchart.parent ();\n" .
              "var currentOperatorId = null;\n" .
              "\n" .
              "var cx = flowchart.width () / 2;\n" .
              "var cy = flowchart.height () / 2;\n" .
              "\n" .
              "flowchart.panzoom ();\n" .
              "flowchart.panzoom ( 'pan', -cx + container.width () / 2, -cy + container.height () / 2);\n" .
              "\n" .
              "var possibleZooms = [0.5, 0.625, 0.75, 0.875, 1, 1.5, 2, 2.5, 3];\n" .
              "var currentZoom = 4;\n" .
              "\n" .
              "$('#ivr_add_form .workflow_controls i').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  switch ( $(this).data ( 'mode'))\n" .
              "  {\n" .
              "    case 'delete':\n" .
              "      if ( flowchart.flowchart ( 'getSelectedOperatorId') != 'start')\n" .
              "      {\n" .
              "        flowchart.flowchart ( 'deleteSelected');\n" .
              "      }\n" .
              "      break;\n" .
              "    case 'zoomout':\n" .
              "      currentZoom = Math.min ( possibleZooms.length - 1, currentZoom + 1);\n" .
              "      flowchart.flowchart ( 'setPositionRatio', possibleZooms[currentZoom]);\n" .
              "      flowchart.panzoom ( 'zoom', possibleZooms[currentZoom],\n" .
              "      {\n" .
              "        animate: true\n" .
              "      });\n" .
              "      break;\n" .
              "    case 'reset':\n" .
              "      flowchart.panzoom ( 'reset');\n" .
              "      break;\n" .
              "    case 'zoomin':\n" .
              "      currentZoom = Math.max ( 0, currentZoom - 1);\n" .
              "      flowchart.flowchart ( 'setPositionRatio', possibleZooms[currentZoom]);\n" .
              "      flowchart.panzoom ( 'zoom', possibleZooms[currentZoom],\n" .
              "      {\n" .
              "        animate: true\n" .
              "      });\n" .
              "      break;\n" .
              "  }\n" .
              "});\n" .
              "\n" .
              "container.on ( 'mousewheel.focal', function ( e)\n" .
              "{\n" .
              "  e.preventDefault ();\n" .
              "  var delta = ( e.delta || e.originalEvent.wheelDelta) || e.originalEvent.detail;\n" .
              "  var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;\n" .
              "  currentZoom = Math.max ( 0, Math.min ( possibleZooms.length - 1, ( currentZoom + ( zoomOut * 2 - 1))));\n" .
              "  flowchart.flowchart ( 'setPositionRatio', possibleZooms[currentZoom]);\n" .
              "  flowchart.panzoom ( 'zoom', possibleZooms[currentZoom],\n" .
              "  {\n" .
              "    animate: true,\n" .
              "    focal: e\n" .
              "  });\n" .
              "});\n" .
              "\n" .
              "data = {\n" .
              "  operators:\n" .
              "  {\n" .
              "    start:\n" .
              "    {\n" .
              "      top: 50,\n" .
              "      left: 50,\n" .
              "      properties:\n" .
              "      {\n" .
              "        title: '<i class=\"fa fa-flag\"></i> " . __ ( "Start") . " <a href=\"#\" data-toggle=\"modal\" data-target=\"#workflow-dialog-start\"><i class=\"fa fa-wrench\"></i></a>',\n" .
              "        inputs: {},\n" .
              "        outputs:\n" .
              "        {\n" .
              "          output:\n" .
              "          {\n" .
              "            label: '" . __ ( "Out") . "'\n" .
              "          }\n" .
              "        }\n" .
              "      }\n" .
              "    }\n" .
              "  },\n" .
              "  links: {}\n" .
              "};\n" .
              "flowchart.flowchart (\n" .
              "{\n" .
              "  data: data,\n" .
              "  grid: 0,\n" .
              "  onLinkCreate: function ( linkId, linkData)\n" .
              "                {\n" .
              "                  return linkData.fromOperator != linkData.toOperator;\n" .
              "                }\n" .
              "}).panzoom ( 'reset');\n" .
              "\n" .
              "flowchart.parent ().siblings ( '.delete_selected_button').click ( function ()\n" .
              "{\n" .
              "  flowchart.flowchart ( 'deleteSelected');\n" .
              "});\n" .
              "\n" .
              "function getOperatorData ( element)\n" .
              "{\n" .
              "  var nbInputs = JSON.parse ( atob ( element.data ( 'inputs')));\n" .
              "  var nbOutputs = JSON.parse ( atob ( element.data ( 'outputs')));\n" .
              "  var data =\n" .
              "  {\n" .
              "    properties:\n" .
              "    {\n" .
              "      title: element.html () + ( element.data ( 'modal') ? '<a href=\"#\" data-toggle=\"modal\" data-target=\"#workflow-dialog-' + element.data ( 'modal') + '\"><i class=\"fa fa-wrench\"></i></a>' : ''),\n" .
              "      titleClass: element.attr ( 'class').indexOf ( 'btn-') ? element.attr ( 'class').replace ( /(.*)(btn-(default|info|success|primary|warning|danger))(.*)/g, 'label-$3') : '',\n" .
              "      inputs: {},\n" .
              "      outputs: {}\n" .
              "    }\n" .
              "  };\n" .
              "\n" .
              "  for ( key in nbInputs)\n" .
              "  {\n" .
              "    data.properties.inputs[key] =\n" .
              "    {\n" .
              "      label: nbInputs[key],\n" .
              "      multipleLinks: true\n" .
              "    };\n" .
              "  }\n" .
              "  for ( key in nbOutputs)\n" .
              "  {\n" .
              "    data.properties.outputs[key] =\n" .
              "    {\n" .
              "      label: nbOutputs[key]\n" .
              "    };\n" .
              "  }\n" .
              "  data.properties.ivrOperator = element.data ( 'name');\n" .
              "\n" .
              "  return data;\n" .
              "}\n" .
              "\n" .
              "var operatorId = 0;\n" .
              "\n" .
              "$('#ivr_add_form .workflow-element').draggable (\n" .
              "{\n" .
              "  cursor: 'move',\n" .
              "  opacity: 0.7,\n" .
              "\n" .
              "  helper: 'clone',\n" .
              "  appendTo: '#ivr_add_workflow',\n" .
              "  zIndex: 1000,\n" .
              "\n" .
              "  helper: function ( e)\n" .
              "  {\n" .
              "    return flowchart.flowchart ( 'getOperatorElement', getOperatorData ( $(this)));\n" .
              "  },\n" .
              "\n" .
              "  drag: function ( e, ui)\n" .
              "  {\n" .
              "    if ( ui.position.left > 0 && ui.position.left < $('#ivr_add_workflow').width () - $(ui.helper).width () && ui.position.top > 0 && ui.position.top < $('#ivr_add_workflow').height () - $(ui.helper).height ())\n" .
              "    {\n" .
              "      var elementOffset = flowchart.offset ();\n" .
              "      ui.position.left = ( ui.offset.left - elementOffset.left) / possibleZooms[currentZoom];\n" .
              "      ui.position.top = ( ui.offset.top - elementOffset.top) / possibleZooms[currentZoom];\n" .
              "    } else {\n" .
              "      ui.position.left = -1000;\n" .
              "      ui.position.top = -1000;\n" .
              "    }\n" .
              "  },\n" .
              "\n" .
              "  stop: function ( e, ui)\n" .
              "  {\n" .
              "    if ( ui.position.left > 0 && ui.position.left < $('#ivr_add_workflow').width () - $(ui.helper).width () && ui.position.top > 0 && ui.position.top < $('#ivr_add_workflow').height () - $(ui.helper).height ())\n" .
              "    {\n" .
              "      var data = getOperatorData ( $(this));\n" .
              "      data.left = ui.position.left;\n" .
              "      data.top = ui.position.top;\n" .
              "\n" .
              "      flowchart.flowchart ( 'addOperator', data);\n" .
              "    }\n" .
              "  }\n" .
              "});\n" .
              "\n" .
              "$('#workflow-dialog-start_autoanswer').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#workflow-dialog-start_delay').mask ( '0#');\n" .
              "$('#workflow-dialog-start').on ( 'show.bs.modal', function ( e)\n" .
              "{\n" .
              "  currentOperatorId = $(e.relatedTarget).closest ( 'div .flowchart-operator').data ( 'operator_id');\n" .
              "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
              "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
              "  $('#workflow-dialog-start_autoanswer').bootstrapToggle ( ivrData.AutoAnswer ? 'on' : 'off');\n" .
              "  $('#workflow-dialog-start_delay').val ( ivrData.Delay);\n" .
              "  if ( ivrData.Delay)\n" .
              "  {\n" .
              "    $('#workflow-dialog-start_delay').prop ( 'disabled', '');\n" .
              "  } else {\n" .
              "    $('#workflow-dialog-start_delay').prop ( 'disabled', 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "$('#workflow-dialog-start .modal-footer button.btn-primary').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
              "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
              "  if ( $('#workflow-dialog-start_autoanswer').prop ( 'checked') && $('#workflow-dialog-start_delay').val () == '')\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Action validation error") . "', text: '" . __ ( "Delay can't be empty when enabled.") . "', type: 'error'});\n" .
              "    return false;\n" .
              "  }\n" .
              "  ivrData.AutoAnswer = $('#workflow-dialog-start_autoanswer').prop ( 'checked');\n" .
              "  ivrData.Delay = $('#workflow-dialog-start_delay').val ();\n" .
              "  ivrBlock.properties.ivrData = ivrData;\n" .
              "  flowchart.flowchart ( 'setOperatorData', currentOperatorId, ivrBlock);\n" .
              "  $('#workflow-dialog-start').modal ( 'hide');\n" .
              "  currentOperatorId = null;\n" .
              "});\n" .
              "$('#workflow-dialog-start_autoanswer').on ( 'change', function ( e)\n" .
              "{\n" .
              "  if ( $(this).prop ( 'checked'))\n" .
              "  {\n" .
              "    $('#workflow-dialog-start_delay').prop ( 'disabled', '');\n" .
              "  } else {\n" .
              "    $('#workflow-dialog-start_delay').val ( '').prop ( 'disabled', 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "\n" .
              "$('#ivr_add_name').mask ( 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ', { 'translation': { Z: { pattern: /[a-z0-9\-\.]/}}});\n" .
              "$('#ivr_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#ivr_add_name'),\n" .
              "    URL: '/ivrs',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "IVR addition") . "',\n" .
              "    fail: '" . __ ( "Error adding IVR!") . "',\n" .
              "    success: '" . __ ( "IVR added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/ivrs', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = new Object ();\n" .
              "  formData.Name = $('#ivr_add_form [name=\"Name\"]').val ();\n" .
              "  formData.Description = $('#ivr_add_form [name=\"Description\"]').val ();\n" .
              "  formData.Workflow = new Object ();\n" .
              "  formData.Workflow.Operators = new Array ();\n" .
              "  var workflow = $('#ivr_add_workflow').flowchart ( 'getData');\n" .
              "  for ( var i in workflow.operators)\n" .
              "  {\n" .
              "    var operator = new Object ();\n" .
              "    operator.ID = i;\n" .
              "    operator.Operator = i == 'start' ? 'start' : workflow.operators[i].properties.ivrOperator;\n" .
              "    operator.Top = workflow.operators[i].top;\n" .
              "    operator.Left = workflow.operators[i].left;\n" .
              "    operator.Outputs = new Array ();\n" .
              "    for ( var y in workflow.operators[i].properties.outputs)\n" .
              "    {\n" .
              "      var output = new Object ();\n" .
              "      output.Name = y;\n" .
              "      output.Label = workflow.operators[i].properties.outputs[y].label;\n" .
              "      operator.Outputs.push ( output);\n" .
              "    }\n" .
              "    operator.Properties = workflow.operators[i].properties.ivrData;\n" .
              "    formData.Workflow.Operators.push ( operator);\n" .
              "  }\n" .
              "  formData.Workflow.Links = new Array ();\n" .
              "  for ( var i in workflow.links)\n" .
              "  {\n" .
              "    var link = new Object ();\n" .
              "    link.FromOperator = workflow.links[i].fromOperator;\n" .
              "    link.FromConnector = workflow.links[i].fromConnector;\n" .
              "    link.ToOperator = workflow.links[i].toOperator;\n" .
              "    formData.Workflow.Links.push ( link);\n" .
              "  }\n" .
              "  $('#ivr_add_form').data ( 'formData', formData);\n" .
              "}).on ( 'reset', function ()\n" .
              "{\n" .
              "  var workflow = $('#ivr_add_workflow').flowchart ( 'getData');\n" .
              "  for ( var i in workflow.operators)\n" .
              "  {\n" .
              "    if ( i != 'start')\n" .
              "    {\n" .
              "      $('#ivr_add_workflow').flowchart ( 'deleteOperator', i);\n" .
              "    }\n" .
              "  }\n" .
              "  var start = $('#ivr_add_workflow').flowchart ( 'getOperatorData', 'start');\n" .
              "  start.properties.ivrData.AutoAnswer = false;\n" .
              "  start.properties.ivrData.Delay = '';\n" .
              "  $('#ivr_add_workflow').flowchart ( 'setOperatorData', 'start', start);\n" .
              "  $('#ivr_add_workflow').panzoom ( 'reset');\n" .
              "});");

  return $output;
}

/**
 * Function to generate the ivr clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/ivrs/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/ivrs/add', true, function ()\n" .
              "  {\n" .
              "    $('#ivr_add_name').val ( data.Name);\n" .
              "    $('#ivr_add_description').val ( data.Description);\n" .
              "    var workflow = {\n" .
              "      operators: {},\n" .
              "      links: {}\n" .
              "    };\n" .
              "    for ( var x = 0; x < data.Workflow.Operators.length; x++)\n" .
              "    {\n" .
              "      if ( data.Workflow.Operators[x].Operator == 'start')\n" .
              "      {\n" .
              "        var operator = {\n" .
              "          properties: {\n" .
              "            ivrOperator: 'start',\n" .
              "            title: '<i class=\"fa fa-flag\"></i> " . __ ( "Start") . " <a href=\"#\" data-toggle=\"modal\" data-target=\"#workflow-dialog-start\"><i class=\"fa fa-wrench\"></i></a>',\n" .
              "            titleClass: ''\n" .
              "          }\n" .
              "        };\n" .
              "      } else {\n" .
              "        var operator = getOperatorData ( $('span.dlg-' + data.Workflow.Operators[x].Operator));\n" .
              "      }\n" .
              "      operator.properties.ivrData = data.Workflow.Operators[x].Properties;\n" .
              "      if ( data.Workflow.Operators[x].Operator != 'start')\n" .
              "      {\n" .
              "        operator.properties.inputs = { input: { label: '" . __ ( "In") . "', multipleLinks: true}};" .
              "      }\n" .
              "      operator.properties.outputs = new Array ();\n" .
              "      for ( var y = 0; y < data.Workflow.Operators[x].Outputs.length; y++)\n" .
              "      {\n" .
              "        var output = new Object ();\n" .
              "        output.label = data.Workflow.Operators[x].Outputs[y].Label;\n" .
              "        output.multipleLinks = data.Workflow.Operators[x].Outputs[y].Multiple;\n" .
              "        operator.properties.outputs[data.Workflow.Operators[x].Outputs[y].Name] = output;\n" .
              "      }\n" .
              "      operator.left = data.Workflow.Operators[x].Left;\n" .
              "      operator.top = data.Workflow.Operators[x].Top;\n" .
              "      workflow.operators[data.Workflow.Operators[x].ID] = operator;\n" .
              "    }\n" .
              "    for ( var x = 0; x < data.Workflow.Links.length; x++)\n" .
              "    {\n" .
              "      var link = new Object ();\n" .
              "      link.fromOperator = data.Workflow.Links[x].FromOperator;\n" .
              "      link.fromConnector = data.Workflow.Links[x].FromConnector;\n" .
              "      link.toOperator = data.Workflow.Links[x].ToOperator;\n" .
              "      link.toConnector = 'input';\n" .
              "      workflow.links[x] = link;\n" .
              "    }\n" .
              "    $('#ivr_add_workflow').flowchart ( 'setData', workflow);\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "IVR cloning") . "', text: '" . __ ( "Error requesting IVR data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the IVR view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "IVR"));
  sys_set_subtitle ( __ ( "IVR visualization"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "IVR"), "link" => "/ivrs"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "workflow", "src" => "/css/workflow.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/dist/themes/base/jquery-ui.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "jquery-flowchart", "src" => "/vendors/jquery.flowchart/jquery.flowchart.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    if ( $operator["requiredcss"])
    {
      foreach ( $operator["requiredcss"] as $css)
      {
        sys_addcss ( $css);
      }
    }
  }

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/dist/jquery-ui.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-panzoom", "src" => "/vendors/panzoom/dist/jquery.panzoom.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-mousewheel", "src" => "/vendors/jquery-mousewheel/jquery.mousewheel.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-flowchart", "src" => "/vendors/jquery.flowchart/jquery.flowchart.js", "dep" => array ( "jquery-ui", "jquery-panzoom", "jquery-mousewheel")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    if ( $operator["requiredjs"])
    {
      foreach ( $operator["requiredjs"] as $js)
      {
        sys_addjs ( $js);
      }
    }
  }

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"ivr_view_form\">\n";

  // Add IVR name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"ivr_view_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"ivr_view_name\" placeholder=\"" . __ ( "IVR name") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add IVR description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"ivr_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"ivr_view_description\" placeholder=\"" . __ ( "IVR description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add IVR flowchart icons field
  $output .= "  <div class=\"form-group hidden\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Actions") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 workflow-itens\">\n";
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    $inputs = array ( "input" => __ ( "In"));
    if ( ! is_array ( $operator["outputs"]))
    {
      $outputs = array ();
      if ( $operator["outputs"] == 1)
      {
        $outputs["output"] = __ ( "Out");
      } else {
        for ( $x = 0; $x < $operator["outputs"]; $x++)
        {
          $outputs["output_" . $x] = __ ( "Out") . " " . ( $x + 1);
        }
      }
    } else {
      $outputs = $operator["outputs"];
    }
    $output .= "      <span class=\"workflow-element dlg-" . $operator["name"] . " btn btn-" . $operator["style"] .  "\" data-name=\"" . $operator["name"] . "\" data-inputs=\"" . base64_encode ( json_encode ( $inputs)) . "\" data-outputs=\"" . base64_encode ( json_encode ( $outputs)) . "\"" . ( $operator["modal"] ? " data-modal=\"" . $operator["name"] . "\"" : "") . "><i class=\"fa fa-" . $operator["icon"] . "\"></i> " . __ ( $operator["title"]) . "</span>\n";
  }
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add IVR flowchart field
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"workflow_container\">\n";
  $output .= "      <div id=\"ivr_view_workflow\" class=\"workflow\"></div>\n";
  $output .= "      <div class=\"workflow_controls\">\n";
  $output .= "        <i class=\"fa fa-search-minus\" data-mode=\"zoomin\" title=\"" . __ ( "Zoom out") . "\"></i>\n";
  $output .= "        <i class=\"fa fa-home\" data-mode=\"reset\" title=\"" . __ ( "Zoom to fit") . "\"></i>\n";
  $output .= "        <i class=\"fa fa-search-plus\" data-mode=\"zoomout\" title=\"" . __ ( "Zoom in") . "\"></i>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/ivrs\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add workflow elements dialogs
   */

  // Start dialog
  $output .= "<div id=\"workflow-dialog-start\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"workflow-dialog-start\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Start options") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"workflow-dialog-start_form\">\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"workflow-dialog-start_autoanswer\" class=\"control-label col-xs-2\">" . __ ( "Auto answer") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"checkbox\" name=\"autoanswer\" value=\"true\" id=\"workflow-dialog-start_autoanswer\" class=\"form-control\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"workflow-dialog-start_delay\" class=\"control-label col-xs-2\">" . __ ( "Delay") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"text\" name=\"delay\" id=\"workflow-dialog-start_delay\" class=\"form-control\" placeholder=\"" . __ ( "Wait seconds before answer") . "\"/>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "        <button class=\"btn btn-primary\">" . __ ( "Apply") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  // Modular workflow modal contents
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    if ( $operator["modal"])
    {
      $output .= "<div id=\"workflow-dialog-" . $operator["name"] . "\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"workflow-dialog-" . $operator["name"] . "\" aria-hidden=\"true\">\n";
      $output .= "  <div class=\"modal-dialog modal-lg\">\n";
      $output .= "    <div class=\"modal-content\">\n";
      $output .= "      <div class=\"modal-header\">\n";
      $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
      $output .= "        <h3 class=\"modal-title\">" . __ ( $operator["modaltitle"]) . "</h3>\n";
      $output .= "      </div>\n";
      $output .= "      <div class=\"modal-body\">\n";
      $output .= preg_replace ( "/^/m", "        ", $operator["modalcontent"]);
      $output .= "      </div>\n";
      $output .= "      <div class=\"modal-footer\">\n";
      $output .= "        <button class=\"btn btn-primary\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
      $output .= "      </div>\n";
      $output .= "    </div>\n";
      $output .= "  </div>\n";
      $output .= "</div>\n";

      if ( $operator["modalfocus"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . "').on ( 'shown.bs.modal', function ( e)\n" .
                    "{\n" .
                    "  e && e.preventDefault ();\n" .
                    "  $('#" . $operator["modalfocus"] . "').focus ();\n" .
                    "});\n");
      }

      if ( $operator["modalshow"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . "').on ( 'show.bs.modal', function ( e)\n" .
                    "{\n" .
                    "  if ( $(this).find ( '.required').length != 0 && $(this).find ( '.require-alert').length == 0)" .
                    "  {" .
                    "    $(this).find ( 'div .modal-body').append ( '<span class=\"require-alert dt-right\"><b style=\"color: #ff0000\">*</b> " . __ ( "required field.") . "</span><br />');" .
                    "  }" .
                    "  currentOperatorId = $(e.relatedTarget).closest ( 'div .flowchart-operator').data ( 'operator_id');\n" .
                    "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
                    "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
                    "  $(this).find ( 'input,textarea,select').not ( '.hidden').prop ( 'disabled', '');\n" .
                    "  $(this).find ( 'input:checkbox').not ( '.hidden').bootstrapToggle ( 'enable');\n" .
                    preg_replace ( "/^/m", "  ", $operator["modalshow"]) .
                    "  $(this).find ( 'input,textarea,select').not ( '.hidden').prop ( 'disabled', 'disabled');\n" .
                    "  $(this).find ( 'input:checkbox').not ( '.hidden').bootstrapToggle ( 'disable');\n" .
                    "});\n");
      }
    }

    // Modular workflow javascript codes
    if ( $operator["javascript"])
    {
      sys_addjs ( $operator["javascript"]);
    }
  }

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "var flowchart = $('#ivr_view_workflow');\n" .
              "flowchart.mode = 'view';\n" .
              "var container = flowchart.parent ();\n" .
              "var currentOperatorId = null;\n" .
              "\n" .
              "var cx = flowchart.width () / 2;\n" .
              "var cy = flowchart.height () / 2;\n" .
              "\n" .
              "flowchart.panzoom ();\n" .
              "flowchart.panzoom ( 'pan', -cx + container.width () / 2, -cy + container.height () / 2);\n" .
              "\n" .
              "var possibleZooms = [0.5, 0.625, 0.75, 0.875, 1, 1.5, 2, 2.5, 3];\n" .
              "var currentZoom = 4;\n" .
              "\n" .
              "$('#ivr_view_form .workflow_controls i').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  switch ( $(this).data ( 'mode'))\n" .
              "  {\n" .
              "    case 'zoomout':\n" .
              "      currentZoom = Math.min ( possibleZooms.length - 1, currentZoom + 1);\n" .
              "      flowchart.flowchart ( 'setPositionRatio', possibleZooms[currentZoom]);\n" .
              "      flowchart.panzoom ( 'zoom', possibleZooms[currentZoom],\n" .
              "      {\n" .
              "        animate: true\n" .
              "      });\n" .
              "      break;\n" .
              "    case 'reset':\n" .
              "      flowchart.panzoom ( 'reset');\n" .
              "      break;\n" .
              "    case 'zoomin':\n" .
              "      currentZoom = Math.max ( 0, currentZoom - 1);\n" .
              "      flowchart.flowchart ( 'setPositionRatio', possibleZooms[currentZoom]);\n" .
              "      flowchart.panzoom ( 'zoom', possibleZooms[currentZoom],\n" .
              "      {\n" .
              "        animate: true\n" .
              "      });\n" .
              "      break;\n" .
              "  }\n" .
              "});\n" .
              "\n" .
              "container.on ( 'mousewheel.focal', function ( e)\n" .
              "{\n" .
              "  e.preventDefault ();\n" .
              "  var delta = ( e.delta || e.originalEvent.wheelDelta) || e.originalEvent.detail;\n" .
              "  var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;\n" .
              "  currentZoom = Math.max ( 0, Math.min ( possibleZooms.length - 1, ( currentZoom + ( zoomOut * 2 - 1))));\n" .
              "  flowchart.flowchart ( 'setPositionRatio', possibleZooms[currentZoom]);\n" .
              "  flowchart.panzoom ( 'zoom', possibleZooms[currentZoom],\n" .
              "  {\n" .
              "    animate: true,\n" .
              "    focal: e\n" .
              "  });\n" .
              "});\n" .
              "\n" .
              "flowchart.flowchart (\n" .
              "{\n" .
              "  grid: 0,\n" .
              "  canUserEditLinks: false,\n" .
              "  canUserMoveOperators: false\n" .
              "}).panzoom ( 'reset');\n" .
              "\n" .
              "function getOperatorData ( element)\n" .
              "{\n" .
              "  var nbInputs = JSON.parse ( atob ( element.data ( 'inputs')));\n" .
              "  var nbOutputs = JSON.parse ( atob ( element.data ( 'outputs')));\n" .
              "  var data =\n" .
              "  {\n" .
              "    properties:\n" .
              "    {\n" .
              "      title: element.html () + ( element.data ( 'modal') ? '<a href=\"#\" data-toggle=\"modal\" data-target=\"#workflow-dialog-' + element.data ( 'modal') + '\"><i class=\"fa fa-wrench\"></i></a>' : ''),\n" .
              "      titleClass: element.attr ( 'class').indexOf ( 'btn-') ? element.attr ( 'class').replace ( /(.*)(btn-(default|info|success|primary|warning|danger))(.*)/g, 'label-$3') : '',\n" .
              "      inputs: {},\n" .
              "      outputs: {}\n" .
              "    }\n" .
              "  };\n" .
              "\n" .
              "  for ( key in nbInputs)\n" .
              "  {\n" .
              "    data.properties.inputs[key] =\n" .
              "    {\n" .
              "      label: nbInputs[key],\n" .
              "      multipleLinks: true\n" .
              "    };\n" .
              "  }\n" .
              "  for ( key in nbOutputs)\n" .
              "  {\n" .
              "    data.properties.outputs[key] =\n" .
              "    {\n" .
              "      label: nbOutputs[key]\n" .
              "    };\n" .
              "  }\n" .
              "  data.properties.ivrOperator = element.data ( 'name');\n" .
              "\n" .
              "  return data;\n" .
              "}\n" .
              "\n" .
              "var operatorId = 0;\n" .
              "\n" .
              "$('#workflow-dialog-start_autoanswer').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#workflow-dialog-start').on ( 'show.bs.modal', function ( e)\n" .
              "{\n" .
              "  currentOperatorId = $(e.relatedTarget).closest ( 'div .flowchart-operator').data ( 'operator_id');\n" .
              "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
              "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
              "  $('#workflow-dialog-start_autoanswer').bootstrapToggle ( 'enable').bootstrapToggle ( ivrData.AutoAnswer ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#workflow-dialog-start_delay').val ( ivrData.Delay);\n" .
              "  $('#workflow-dialog-start_delay').prop ( 'disabled', 'disabled');\n" .
              "});\n" .
              "\n" .
              "$('#ivr_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#ivr_view_name').val ( data.Name);\n" .
              "  $('#ivr_view_description').val ( data.Description);\n" .
              "  var workflow = {\n" .
              "    operators: {},\n" .
              "    links: {}\n" .
              "  };\n" .
              "  for ( var x = 0; x < data.Workflow.Operators.length; x++)\n" .
              "  {\n" .
              "    if ( data.Workflow.Operators[x].Operator == 'start')\n" .
              "    {\n" .
              "      var operator = {\n" .
              "        properties: {\n" .
              "          ivrOperator: 'start',\n" .
              "          title: '<i class=\"fa fa-flag\"></i> " . __ ( "Start") . " <a href=\"#\" data-toggle=\"modal\" data-target=\"#workflow-dialog-start\"><i class=\"fa fa-wrench\"></i></a>',\n" .
              "          titleClass: ''\n" .
              "        }\n" .
              "      };\n" .
              "    } else {\n" .
              "      var operator = getOperatorData ( $('span.dlg-' + data.Workflow.Operators[x].Operator));\n" .
              "    }\n" .
              "    operator.properties.ivrData = data.Workflow.Operators[x].Properties;\n" .
              "    if ( data.Workflow.Operators[x].Operator != 'start')\n" .
              "    {\n" .
              "      operator.properties.inputs = { input: { label: '" . __ ( "In") . "', multipleLinks: true}};" .
              "    }\n" .
              "    operator.properties.outputs = new Array ();\n" .
              "    for ( var y = 0; y < data.Workflow.Operators[x].Outputs.length; y++)\n" .
              "    {\n" .
              "      var output = new Object ();\n" .
              "      output.label = data.Workflow.Operators[x].Outputs[y].Label;\n" .
              "      output.multipleLinks = false;\n" .
              "      operator.properties.outputs[data.Workflow.Operators[x].Outputs[y].Name] = output;\n" .
              "    }\n" .
              "    operator.left = data.Workflow.Operators[x].Left;\n" .
              "    operator.top = data.Workflow.Operators[x].Top;\n" .
              "    workflow.operators[data.Workflow.Operators[x].ID] = operator;\n" .
              "  }\n" .
              "  for ( var x = 0; x < data.Workflow.Links.length; x++)\n" .
              "  {\n" .
              "    var link = new Object ();\n" .
              "    link.fromOperator = data.Workflow.Links[x].FromOperator;\n" .
              "    link.fromConnector = data.Workflow.Links[x].FromConnector;\n" .
              "    link.toOperator = data.Workflow.Links[x].ToOperator;\n" .
              "    link.toConnector = 'input';\n" .
              "    workflow.links[x] = link;\n" .
              "  }\n" .
              "  $('#ivr_view_workflow').flowchart ( 'setData', workflow);\n" .
              "});\n" .
              "VoIP.rest ( '/ivrs/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#ivr_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "IVR view") . "', text: '" . __ ( "Error viewing IVR!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the IVR edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ivrs_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "IVR"));
  sys_set_subtitle ( __ ( "IVR edition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "IVR"), "link" => "/ivrs"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "workflow", "src" => "/css/workflow.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/dist/themes/base/jquery-ui.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "jquery-flowchart", "src" => "/vendors/jquery.flowchart/jquery.flowchart.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    if ( $operator["requiredcss"])
    {
      foreach ( $operator["requiredcss"] as $css)
      {
        sys_addcss ( $css);
      }
    }
  }

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/dist/jquery-ui.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-panzoom", "src" => "/vendors/panzoom/dist/jquery.panzoom.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-mousewheel", "src" => "/vendors/jquery-mousewheel/jquery.mousewheel.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-flowchart", "src" => "/vendors/jquery.flowchart/jquery.flowchart.js", "dep" => array ( "jquery-ui", "jquery-panzoom", "jquery-mousewheel")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    if ( $operator["requiredjs"])
    {
      foreach ( $operator["requiredjs"] as $js)
      {
        sys_addjs ( $js);
      }
    }
  }

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"ivr_edit_form\">\n";

  // Add IVR name field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"ivr_edit_name\" class=\"control-label col-xs-2\">" . __ ( "Name") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Name\" class=\"form-control\" id=\"ivr_edit_name\" placeholder=\"" . __ ( "IVR name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add IVR description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"ivr_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"ivr_edit_description\" placeholder=\"" . __ ( "IVR description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add IVR flowchart icons field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Actions") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 workflow-itens\">\n";
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    $inputs = array ( "input" => __ ( "In"));
    if ( ! is_array ( $operator["outputs"]))
    {
      $outputs = array ();
      if ( $operator["outputs"] == 1)
      {
        $outputs["output"] = __ ( "Out");
      } else {
        for ( $x = 0; $x < $operator["outputs"]; $x++)
        {
          $outputs["output_" . $x] = __ ( "Out") . " " . ( $x + 1);
        }
      }
    } else {
      $outputs = $operator["outputs"];
    }
    $output .= "      <span class=\"workflow-element dlg-" . $operator["name"] . " btn btn-" . $operator["style"] .  "\" data-name=\"" . $operator["name"] . "\" data-inputs=\"" . base64_encode ( json_encode ( $inputs)) . "\" data-outputs=\"" . base64_encode ( json_encode ( $outputs)) . "\"" . ( $operator["modal"] ? " data-modal=\"" . $operator["name"] . "\"" : "") . "><i class=\"fa fa-" . $operator["icon"] . "\"></i> " . __ ( $operator["title"]) . "</span>\n";
  }
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add IVR flowchart field
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"workflow_container\">\n";
  $output .= "      <div id=\"ivr_edit_workflow\" class=\"workflow\"></div>\n";
  $output .= "      <div class=\"workflow_controls\">\n";
  $output .= "        <i class=\"fa fa-search-minus\" data-mode=\"zoomin\" title=\"" . __ ( "Zoom out") . "\"></i>\n";
  $output .= "        <i class=\"fa fa-home\" data-mode=\"reset\" title=\"" . __ ( "Zoom to fit") . "\"></i>\n";
  $output .= "        <i class=\"fa fa-search-plus\" data-mode=\"zoomout\" title=\"" . __ ( "Zoom in") . "\"></i>\n";
  $output .= "        <i class=\"fa fa-trash-alt\" data-mode=\"delete\" title=\"" . __ ( "Delete selected") . "\"></i>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/ivrs\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add workflow elements dialogs
   */

  // Start dialog
  $output .= "<div id=\"workflow-dialog-start\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"workflow-dialog-start\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Start options") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"workflow-dialog-start_form\">\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"workflow-dialog-start_autoanswer\" class=\"control-label col-xs-2\">" . __ ( "Auto answer") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"checkbox\" name=\"autoanswer\" value=\"true\" id=\"workflow-dialog-start_autoanswer\" class=\"form-control\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"workflow-dialog-start_delay\" class=\"control-label col-xs-2\">" . __ ( "Delay") . "</label>\n";
  $output .= "          <div class=\"col-xs-10\">\n";
  $output .= "            <input type=\"text\" name=\"delay\" id=\"workflow-dialog-start_delay\" class=\"form-control\" placeholder=\"" . __ ( "Wait seconds before answer") . "\"/>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "        <button class=\"btn btn-primary\">" . __ ( "Apply") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  // Modular workflow modal contents
  foreach ( $_in["ivr"]["operators"] as $operator)
  {
    if ( $operator["modal"])
    {
      $output .= "<div id=\"workflow-dialog-" . $operator["name"] . "\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"workflow-dialog-" . $operator["name"] . "\" aria-hidden=\"true\">\n";
      $output .= "  <div class=\"modal-dialog modal-lg\">\n";
      $output .= "    <div class=\"modal-content\">\n";
      $output .= "      <div class=\"modal-header\">\n";
      $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
      $output .= "        <h3 class=\"modal-title\">" . __ ( $operator["modaltitle"]) . "</h3>\n";
      $output .= "      </div>\n";
      $output .= "      <div class=\"modal-body\">\n";
      $output .= preg_replace ( "/^/m", "        ", $operator["modalcontent"]);
      $output .= "      </div>\n";
      $output .= "      <div class=\"modal-footer\">\n";
      $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
      $output .= "        <button class=\"btn btn-primary\">" . __ ( "Apply") . "</button>\n";
      $output .= "      </div>\n";
      $output .= "    </div>\n";
      $output .= "  </div>\n";
      $output .= "</div>\n";

      if ( $operator["modalfocus"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . "').on ( 'shown.bs.modal', function ( e)\n" .
                    "{\n" .
                    "  e && e.preventDefault ();\n" .
                    "  $('#" . $operator["modalfocus"] . "').focus ();\n" .
                    "});\n");
      }

      if ( $operator["modalshow"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . "').on ( 'show.bs.modal', function ( e)\n" .
                    "{\n" .
                    "  if ( $(this).find ( '.required').length != 0 && $(this).find ( '.require-alert').length == 0)" .
                    "  {" .
                    "    $(this).find ( 'div .modal-body').append ( '<span class=\"require-alert dt-right\"><b style=\"color: #ff0000\">*</b> " . __ ( "required field.") . "</span><br />');" .
                    "  }" .
                    "  currentOperatorId = $(e.relatedTarget).closest ( 'div .flowchart-operator').data ( 'operator_id');\n" .
                    "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
                    "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
                    preg_replace ( "/^/m", "  ", $operator["modalshow"]) .
                    "});\n");
      }

      if ( $operator["modalsave"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . " .modal-footer button.btn-primary').on ( 'click', function ( e)\n" .
                    "{\n" .
                    "  e && e.preventDefault ();\n" .
                    "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
                    "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
                    "  $('#workflow-dialog-" . $operator["name"] . "').trigger ( 'validate');\n" .
                    "  if ( $('#workflow-dialog-" . $operator["name"] . "').data ( 'validation') != undefined && $('#workflow-dialog-" . $operator["name"] . "').data ( 'validation') != '')\n" .
                    "  {\n" .
                    "    new PNotify ( { title: '" . __ ( "Action validation error") . "', text: $('#workflow-dialog-" . $operator["name"] . "').data ( 'validation'), type: 'error'});\n" .
                    "    return false;\n" .
                    "  }\n" .
                    preg_replace ( "/^/m", "  ", $operator["modalsave"]) .
                    "  ivrBlock.properties.ivrData = ivrData;\n" .
                    "  flowchart.flowchart ( 'setOperatorData', currentOperatorId, ivrBlock);\n" .
                    "  $('#workflow-dialog-" . $operator["name"] . "').modal ( 'hide');\n" .
                    "  currentOperatorId = null;\n" .
                    "});\n");
      }

      if ( $operator["validation"])
      {
        sys_addjs ( "$('#workflow-dialog-" . $operator["name"] . "').on ( 'validate', function ( e)\n" .
                    "{\n" .
                    "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
                    "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
                    preg_replace ( "/^/m", "  ", $operator["validation"]) .
                    "  $(this).data ( 'validation', result ? result : '');\n" .
                    "});\n");
      }
    }

    // Modular workflow javascript codes
    if ( $operator["javascript"])
    {
      sys_addjs ( $operator["javascript"]);
    }
  }

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "var flowchart = $('#ivr_edit_workflow');\n" .
              "flowchart.mode = 'edit';\n" .
              "var container = flowchart.parent ();\n" .
              "var currentOperatorId = null;\n" .
              "\n" .
              "var cx = flowchart.width () / 2;\n" .
              "var cy = flowchart.height () / 2;\n" .
              "\n" .
              "flowchart.panzoom ();\n" .
              "flowchart.panzoom ( 'pan', -cx + container.width () / 2, -cy + container.height () / 2);\n" .
              "\n" .
              "var possibleZooms = [0.5, 0.625, 0.75, 0.875, 1, 1.5, 2, 2.5, 3];\n" .
              "var currentZoom = 4;\n" .
              "\n" .
              "$('#ivr_edit_form .workflow_controls i').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  switch ( $(this).data ( 'mode'))\n" .
              "  {\n" .
              "    case 'delete':\n" .
              "      if ( flowchart.flowchart ( 'getSelectedOperatorId') != 'start')\n" .
              "      {\n" .
              "        flowchart.flowchart ( 'deleteSelected');\n" .
              "      }\n" .
              "      break;\n" .
              "    case 'zoomout':\n" .
              "      currentZoom = Math.min ( possibleZooms.length - 1, currentZoom + 1);\n" .
              "      flowchart.flowchart ( 'setPositionRatio', possibleZooms[currentZoom]);\n" .
              "      flowchart.panzoom ( 'zoom', possibleZooms[currentZoom],\n" .
              "      {\n" .
              "        animate: true\n" .
              "      });\n" .
              "      break;\n" .
              "    case 'reset':\n" .
              "      flowchart.panzoom ( 'reset');\n" .
              "      break;\n" .
              "    case 'zoomin':\n" .
              "      currentZoom = Math.max ( 0, currentZoom - 1);\n" .
              "      flowchart.flowchart ( 'setPositionRatio', possibleZooms[currentZoom]);\n" .
              "      flowchart.panzoom ( 'zoom', possibleZooms[currentZoom],\n" .
              "      {\n" .
              "        animate: true\n" .
              "      });\n" .
              "      break;\n" .
              "  }\n" .
              "});\n" .
              "\n" .
              "container.on ( 'mousewheel.focal', function ( e)\n" .
              "{\n" .
              "  e.preventDefault ();\n" .
              "  var delta = ( e.delta || e.originalEvent.wheelDelta) || e.originalEvent.detail;\n" .
              "  var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;\n" .
              "  currentZoom = Math.max ( 0, Math.min ( possibleZooms.length - 1, ( currentZoom + ( zoomOut * 2 - 1))));\n" .
              "  flowchart.flowchart ( 'setPositionRatio', possibleZooms[currentZoom]);\n" .
              "  flowchart.panzoom ( 'zoom', possibleZooms[currentZoom],\n" .
              "  {\n" .
              "    animate: true,\n" .
              "    focal: e\n" .
              "  });\n" .
              "});\n" .
              "\n" .
              "flowchart.flowchart (\n" .
              "{\n" .
              "  grid: 0,\n" .
              "  onLinkCreate: function ( linkId, linkData)\n" .
              "                {\n" .
              "                  return linkData.fromOperator != linkData.toOperator;\n" .
              "                }\n" .
              "}).panzoom ( 'reset');\n" .
              "\n" .
              "flowchart.parent ().siblings ( '.delete_selected_button').click ( function ()\n" .
              "{\n" .
              "  flowchart.flowchart ( 'deleteSelected');\n" .
              "});\n" .
              "\n" .
              "function getOperatorData ( element)\n" .
              "{\n" .
              "  var nbInputs = JSON.parse ( atob ( element.data ( 'inputs')));\n" .
              "  var nbOutputs = JSON.parse ( atob ( element.data ( 'outputs')));\n" .
              "  var data =\n" .
              "  {\n" .
              "    properties:\n" .
              "    {\n" .
              "      title: element.html () + ( element.data ( 'modal') ? '<a href=\"#\" data-toggle=\"modal\" data-target=\"#workflow-dialog-' + element.data ( 'modal') + '\"><i class=\"fa fa-wrench\"></i></a>' : ''),\n" .
              "      titleClass: element.attr ( 'class').indexOf ( 'btn-') ? element.attr ( 'class').replace ( /(.*)(btn-(default|info|success|primary|warning|danger))(.*)/g, 'label-$3') : '',\n" .
              "      inputs: {},\n" .
              "      outputs: {}\n" .
              "    }\n" .
              "  };\n" .
              "\n" .
              "  for ( key in nbInputs)\n" .
              "  {\n" .
              "    data.properties.inputs[key] =\n" .
              "    {\n" .
              "      label: nbInputs[key],\n" .
              "      multipleLinks: true\n" .
              "    };\n" .
              "  }\n" .
              "  for ( key in nbOutputs)\n" .
              "  {\n" .
              "    data.properties.outputs[key] =\n" .
              "    {\n" .
              "      label: nbOutputs[key]\n" .
              "    };\n" .
              "  }\n" .
              "  data.properties.ivrOperator = element.data ( 'name');\n" .
              "\n" .
              "  return data;\n" .
              "}\n" .
              "\n" .
              "var operatorId = 0;\n" .
              "\n" .
              "$('#ivr_edit_form .workflow-element').draggable (\n" .
              "{\n" .
              "  cursor: 'move',\n" .
              "  opacity: 0.7,\n" .
              "\n" .
              "  helper: 'clone',\n" .
              "  appendTo: '#ivr_edit_workflow',\n" .
              "  zIndex: 1000,\n" .
              "\n" .
              "  helper: function ( e)\n" .
              "  {\n" .
              "    return flowchart.flowchart ( 'getOperatorElement', getOperatorData ( $(this)));\n" .
              "  },\n" .
              "\n" .
              "  drag: function ( e, ui)\n" .
              "  {\n" .
              "    if ( ui.position.left > 0 && ui.position.left < $('#ivr_edit_workflow').width () - $(ui.helper).width () && ui.position.top > 0 && ui.position.top < $('#ivr_edit_workflow').height () - $(ui.helper).height ())\n" .
              "    {\n" .
              "      var elementOffset = flowchart.offset ();\n" .
              "      ui.position.left = ( ui.offset.left - elementOffset.left) / possibleZooms[currentZoom];\n" .
              "      ui.position.top = ( ui.offset.top - elementOffset.top) / possibleZooms[currentZoom];\n" .
              "    } else {\n" .
              "      ui.position.left = -1000;\n" .
              "      ui.position.top = -1000;\n" .
              "    }\n" .
              "  },\n" .
              "\n" .
              "  stop: function ( e, ui)\n" .
              "  {\n" .
              "    if ( ui.position.left > 0 && ui.position.left < $('#ivr_edit_workflow').width () - $(ui.helper).width () && ui.position.top > 0 && ui.position.top < $('#ivr_edit_workflow').height () - $(ui.helper).height ())\n" .
              "    {\n" .
              "      var data = getOperatorData ( $(this));\n" .
              "      data.left = ui.position.left;\n" .
              "      data.top = ui.position.top;\n" .
              "\n" .
              "      flowchart.flowchart ( 'addOperator', data);\n" .
              "    }\n" .
              "  }\n" .
              "});\n" .
              "\n" .
              "$('#workflow-dialog-start_autoanswer').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#workflow-dialog-start_delay').mask ( '0#');\n" .
              "$('#workflow-dialog-start').on ( 'show.bs.modal', function ( e)\n" .
              "{\n" .
              "  currentOperatorId = $(e.relatedTarget).closest ( 'div .flowchart-operator').data ( 'operator_id');\n" .
              "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
              "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
              "  $('#workflow-dialog-start_autoanswer').bootstrapToggle ( ivrData.AutoAnswer ? 'on' : 'off');\n" .
              "  $('#workflow-dialog-start_delay').val ( ivrData.Delay);\n" .
              "  if ( ivrData.Delay)\n" .
              "  {\n" .
              "    $('#workflow-dialog-start_delay').prop ( 'disabled', '');\n" .
              "  } else {\n" .
              "    $('#workflow-dialog-start_delay').prop ( 'disabled', 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "$('#workflow-dialog-start .modal-footer button.btn-primary').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  var ivrBlock = flowchart.flowchart ( 'getOperatorData', currentOperatorId);\n" .
              "  var ivrData = ivrBlock.properties && ivrBlock.properties.ivrData ? ivrBlock.properties.ivrData : new Object ();\n" .
              "  if ( $('#workflow-dialog-start_autoanswer').prop ( 'checked') && $('#workflow-dialog-start_delay').val () == '')\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Action validation error") . "', text: '" . __ ( "Delay can't be empty when enabled.") . "', type: 'error'});\n" .
              "    return false;\n" .
              "  }\n" .
              "  ivrData.AutoAnswer = $('#workflow-dialog-start_autoanswer').prop ( 'checked');\n" .
              "  ivrData.Delay = $('#workflow-dialog-start_delay').val ();\n" .
              "  ivrBlock.properties.ivrData = ivrData;\n" .
              "  flowchart.flowchart ( 'setOperatorData', currentOperatorId, ivrBlock);\n" .
              "  $('#workflow-dialog-start').modal ( 'hide');\n" .
              "  currentOperatorId = null;\n" .
              "});\n" .
              "$('#workflow-dialog-start_autoanswer').on ( 'change', function ( e)\n" .
              "{\n" .
              "  if ( $(this).prop ( 'checked'))\n" .
              "  {\n" .
              "    $('#workflow-dialog-start_delay').prop ( 'disabled', '');\n" .
              "  } else {\n" .
              "    $('#workflow-dialog-start_delay').val ( '').prop ( 'disabled', 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "\n" .
              "$('#ivr_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/ivrs/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "IVR edition") . "',\n" .
              "    fail: '" . __ ( "Error editing IVR!") . "',\n" .
              "    success: '" . __ ( "IVR edited sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/ivrs', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = new Object ();\n" .
              "  formData.Name = $('#ivr_edit_form [name=\"Name\"]').val ();\n" .
              "  formData.Description = $('#ivr_edit_form [name=\"Description\"]').val ();\n" .
              "  formData.Workflow = new Object ();\n" .
              "  formData.Workflow.Operators = new Array ();\n" .
              "  var workflow = $('#ivr_edit_workflow').flowchart ( 'getData');\n" .
              "  for ( var i in workflow.operators)\n" .
              "  {\n" .
              "    var operator = new Object ();\n" .
              "    operator.ID = i;\n" .
              "    operator.Operator = i == 'start' ? 'start' : workflow.operators[i].properties.ivrOperator;\n" .
              "    operator.Top = workflow.operators[i].top;\n" .
              "    operator.Left = workflow.operators[i].left;\n" .
              "    operator.Outputs = new Array ();\n" .
              "    for ( var y in workflow.operators[i].properties.outputs)\n" .
              "    {\n" .
              "      var output = new Object ();\n" .
              "      output.Name = y;\n" .
              "      output.Label = workflow.operators[i].properties.outputs[y].label;\n" .
              "      operator.Outputs.push ( output);\n" .
              "    }\n" .
              "    operator.Properties = workflow.operators[i].properties.ivrData;\n" .
              "    formData.Workflow.Operators.push ( operator);\n" .
              "  }\n" .
              "  formData.Workflow.Links = new Array ();\n" .
              "  for ( var i in workflow.links)\n" .
              "  {\n" .
              "    var link = new Object ();\n" .
              "    link.FromOperator = workflow.links[i].fromOperator;\n" .
              "    link.FromConnector = workflow.links[i].fromConnector;\n" .
              "    link.ToOperator = workflow.links[i].toOperator;\n" .
              "    formData.Workflow.Links.push ( link);\n" .
              "  }\n" .
              "  $('#ivr_edit_form').data ( 'formData', formData);\n" .
              "});" .
              "\n" .
              "$('#ivr_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#ivr_edit_name').val ( data.Name);\n" .
              "  $('#ivr_edit_description').val ( data.Description);\n" .
              "  var workflow = {\n" .
              "    operators: {},\n" .
              "    links: {}\n" .
              "  };\n" .
              "  for ( var x = 0; x < data.Workflow.Operators.length; x++)\n" .
              "  {\n" .
              "    if ( data.Workflow.Operators[x].Operator == 'start')\n" .
              "    {\n" .
              "      var operator = {\n" .
              "        properties: {\n" .
              "          ivrOperator: 'start',\n" .
              "          title: '<i class=\"fa fa-flag\"></i> " . __ ( "Start") . " <a href=\"#\" data-toggle=\"modal\" data-target=\"#workflow-dialog-start\"><i class=\"fa fa-wrench\"></i></a>',\n" .
              "          titleClass: ''\n" .
              "        }\n" .
              "      };\n" .
              "    } else {\n" .
              "      var operator = getOperatorData ( $('span.dlg-' + data.Workflow.Operators[x].Operator));\n" .
              "    }\n" .
              "    operator.properties.ivrData = data.Workflow.Operators[x].Properties;\n" .
              "    if ( data.Workflow.Operators[x].Operator != 'start')\n" .
              "    {\n" .
              "      operator.properties.inputs = { input: { label: '" . __ ( "In") . "', multipleLinks: true}};" .
              "    }\n" .
              "    operator.properties.outputs = new Array ();\n" .
              "    for ( var y = 0; y < data.Workflow.Operators[x].Outputs.length; y++)\n" .
              "    {\n" .
              "      var output = new Object ();\n" .
              "      output.label = data.Workflow.Operators[x].Outputs[y].Label;\n" .
              "      output.multipleLinks = data.Workflow.Operators[x].Outputs[y].Multiple;\n" .
              "      operator.properties.outputs[data.Workflow.Operators[x].Outputs[y].Name] = output;\n" .
              "    }\n" .
              "    operator.left = data.Workflow.Operators[x].Left;\n" .
              "    operator.top = data.Workflow.Operators[x].Top;\n" .
              "    workflow.operators[data.Workflow.Operators[x].ID] = operator;\n" .
              "  }\n" .
              "  for ( var x = 0; x < data.Workflow.Links.length; x++)\n" .
              "  {\n" .
              "    var link = new Object ();\n" .
              "    link.fromOperator = data.Workflow.Links[x].FromOperator;\n" .
              "    link.fromConnector = data.Workflow.Links[x].FromConnector;\n" .
              "    link.toOperator = data.Workflow.Links[x].ToOperator;\n" .
              "    link.toConnector = 'input';\n" .
              "    workflow.links[x] = link;\n" .
              "  }\n" .
              "  $('#ivr_edit_workflow').flowchart ( 'setData', workflow);\n" .
              "  $('#ivr_edit_name').focus ();\n" .
              "});\n" .
              "\n" .
              "$('#ivr_edit_name').mask ( 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ', { 'translation': { Z: { pattern: /[a-z0-9\-\.]/}}});\n" .
              "\n" .
              "VoIP.rest ( '/ivrs/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#ivr_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "IVR edit") . "', text: '" . __ ( "Error editing IVR!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}
?>
