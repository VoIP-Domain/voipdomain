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
 * VoIP Domain equipments module WebUI. This module add support for equipments.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/equipments", "equipments_search_page");
framework_add_hook ( "equipments_search_page", "equipments_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/equipments/:id/view", "equipments_view_page");
framework_add_hook ( "equipments_view_page", "equipments_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/equipments/:id/configure", "equipments_configure_page");
framework_add_hook ( "equipments_configure_page", "equipments_configure_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main equipments page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Equipments"));
  sys_set_subtitle ( __ ( "equipments search"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Equipments"))
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
   * Equipment search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add search table JavaScript code
   */
  sys_addjs ( "$('#datatables').data ( 'dt', $('#datatables').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Vendor', title: '" . __ ( "Vendor") . "', width: '35%', class: 'export all'},\n" .
              "             { data: 'Model', title: '" . __ ( "Model") . "', width: '50%', class: 'export all'},\n" .
              "             { data: 'Active', title: '" . __ ( "State") . "', width: '5%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { return '<span class=\"label label-' + ( row.Active ? 'success' : 'danger') + '\">' + ( row.Active ? '" . __ ( "Active") . "' : '" . __ ( "Inactive") . "') + '</span>'; }, searchable: false},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/equipments/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Configure") . "\" role=\"button\" title=\"\" href=\"/equipments/' + row.ID + '/configure\"><i class=\"fa fa-cog\"></i></a></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/equipments', fields: 'ID,Vendor,Model,Active,NULL'}, $('#datatables').data ( 'dt'));\n");

  return $output;
}

/**
 * Function to generate the equipment view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Equipments"));
  sys_set_subtitle ( __ ( "equipments visualization"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Equipments"), "link" => "/equipments"),
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
  sys_addjs ( array ( "name" => "multiselect", "src" => "/vendors/multiselect/dist/js/multiselect.js", "dep" => array ()));

  /**
   * First, we call sub equipment view hook's to populate tabs
   */
  $subpages = filters_call ( "equipments_view_subpages", array (), array ( "tabs" => array (), "js" => array ( "init" => array (), "onshow" => array ()), "html" => ""));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"equipment_view_form\">\n";
  $output .= "<input type=\"hidden\" id=\"equipment_view_uid\" />\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs nav-pages\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_view_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_view_tab_audio_codecs\">" . __ ( "Audio codecs") . "</a></li>\n";
  $output .= "    <li role=\"presentation\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_view_tab_video_codecs\">" . __ ( "Video codecs") . "</a></li>\n";
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <li role=\"presentation\" style=\"display: none\" data-type=\"" . $tabinfo["type"] . "\"><a class=\"nav-tablink\" href=\"#equipment_view_tab_" . $tab . "\">" . $tabinfo["label"] . "</a></li>\n";
  }
  $output .= "    <li role=\"presentation\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_view_tab_firmwares\">" . __ ( "Firmwares") . "</a></li>\n";
  $output .= "    <li role=\"presentation\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_view_tab_about\">" . __ ( "About") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"equipment_view_tab_basic\">\n";

  // Add equipment image (right column)
  $output .= "      <div class=\"form-group\" style=\"display: inline-block; position: absolute; width: 100%; right: 3px\">\n";
  $output .= "        <div class=\"col-xs-2\"></div>\n";
  $output .= "        <div class=\"col-xs-7\"></div>\n";
  $output .= "        <div class=\"col-xs-3 float-right\">\n";
  $output .= "          <img id=\"equipment_view_image\" src=\"/modules/equipments/images/missing.png\" alt=\"\" width=\"100%\" style=\"background-color: #eeeeee; border-color: #d2d6de; border-style: solid; border-width: 1px\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment vendor field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_vendor\" class=\"control-label col-xs-2\">" . __ ( "Vendor") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"text\" name=\"Vendor\" class=\"form-control\" id=\"equipment_view_vendor\" placeholder=\"" . __ ( "Equipment vendor") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment model field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_model\" class=\"control-label col-xs-2\">" . __ ( "Model") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"text\" name=\"Model\" class=\"form-control\" id=\"equipment_view_model\" placeholder=\"" . __ ( "Equipment model") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"equipment_view_description\" placeholder=\"" . __ ( "Equipment description") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment active status option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_active\" class=\"control-label col-xs-2\">" . __ ( "Status") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Active\" id=\"equipment_view_active\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment type field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <select name=\"Type\" id=\"equipment_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Equipment type") . "\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"\" selected></option>\n";
  foreach ( get_defined_constants () as $key => $val)
  {
    if ( substr ( $key, 0, 18) == "VD_EQUIPMENT_TYPE_")
    {
      $output .= "            <option value=\"" . addslashes ( strip_tags ( substr ( $key, 18))) . "\">" . addslashes ( strip_tags ( __ ( $val))) . "</option>\n";
    }
  }
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment support level field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_supportlevel\" class=\"control-label col-xs-2\">" . __ ( "Support level") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <select name=\"SupportLevel\" id=\"equipment_view_supportlevel\" class=\"form-control\" data-placeholder=\"" . __ ( "Equipment support level") . "\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"\" selected></option>\n";
  $output .= "            <option value=\"None\">" . __ ( "None") . "</option>\n";
  $output .= "            <option value=\"Basic\">" . __ ( "Basic") . "</option>\n";
  $output .= "            <option value=\"Complete\">" . __ ( "Complete") . "</option>\n";
  $output .= "            <option value=\"Premium\">" . __ ( "Premium") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment video support option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_videosupport\" class=\"control-label col-xs-2\">" . __ ( "Video support") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"checkbox\" name=\"VideoSupport\" id=\"equipment_view_videosupport\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment auto provisioninig option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_autoprovision\" class=\"control-label col-xs-2\">" . __ ( "Auto provisioning support") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"checkbox\" name=\"AutoProvision\" id=\"equipment_view_autoprovision\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment BLF support option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_blfsupport\" class=\"control-label col-xs-2\">" . __ ( "BLF support") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"checkbox\" name=\"BLF\" id=\"equipment_view_blfsupport\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment number of accounts field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_accounts\" class=\"control-label col-xs-2\">" . __ ( "Accounts") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"text\" name=\"Accounts\" class=\"form-control\" id=\"equipment_view_accounts\" placeholder=\"" . __ ( "Equipment accounts") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment number of shortcuts field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_shortcuts\" class=\"control-label col-xs-2\">" . __ ( "Shortcuts") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"text\" name=\"Shortcuts\" class=\"form-control\" id=\"equipment_view_shortcuts\" placeholder=\"" . __ ( "Equipment shortcuts") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment number of extensions field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_extensions\" class=\"control-label col-xs-2\">" . __ ( "Extensions") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"text\" name=\"Extensions\" class=\"form-control\" id=\"equipment_view_extensions\" placeholder=\"" . __ ( "Equipment extensions") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment shortcuts per extension field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_spe\" class=\"control-label col-xs-2\">" . __ ( "Shortcuts Per Extension") . "</label>\n";
  $output .= "        <div class=\"col-xs-7\">\n";
  $output .= "          <input type=\"text\" name=\"SPE\" class=\"form-control\" id=\"equipment_view_spe\" placeholder=\"" . __ ( "Equipment shortcuts per extension") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Audio codecs panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_view_tab_audio_codecs\">\n";

  // Add equipment audio codecs selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_audio_codecs\" class=\"control-label col-xs-2\">" . __ ( "Audio codecs") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"AvailableAudioCodecs\" id=\"equipment_view_audio_codecs\" class=\"form-control\" multiple=\"multiple\" disabled=\"disabled\">\n";
  foreach ( get_defined_constants () as $key => $val)
  {
    $codecs = array ();
    if ( substr ( $key, 0, 15) == "VD_AUDIO_CODEC_")
    {
      $codecs[substr ( $key, 15)] = $val;
    }
    asort ( $codecs);
    foreach ( $codecs as $key => $val)
    {
      $output .= "              <option value=\"" . addslashes ( strip_tags ( $key)) . "\">" . addslashes ( strip_tags ( __ ( $val))) . "</option>\n";
    }
  }
  $output .= "            </select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_audio_codecs_rightAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable all codecs") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_audio_codecs_rightSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable selected codec(s)") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_audio_codecs_leftSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable selected codec(s)") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_audio_codecs_leftAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable all codecs") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_audio_codecs_move_up\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Move codec up") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-up\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_audio_codecs_move_down\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Move codec down") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-down\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"AudioCodecs\" id=\"equipment_view_audio_codecs_to\" class=\"form-control\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Video codecs panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_view_tab_video_codecs\">\n";

  // Add equipment video codecs selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_video_codecs\" class=\"control-label col-xs-2\">" . __ ( "Video codecs") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"AvailableVideoCodecs\" id=\"equipment_view_video_codecs\" class=\"form-control\" multiple=\"multiple\" disabled=\"disabled\">\n";
  foreach ( get_defined_constants () as $key => $val)
  {
    $codecs = array ();
    if ( substr ( $key, 0, 15) == "VD_VIDEO_CODEC_")
    {
      $codecs[substr ( $key, 15)] = $val;
    }
    asort ( $codecs);
    foreach ( $codecs as $key => $val)
    {
      $output .= "              <option value=\"" . addslashes ( strip_tags ( $key)) . "\">" . addslashes ( strip_tags ( __ ( $val))) . "</option>\n";
    }
  }
  $output .= "            </select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_video_codecs_rightAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable all codecs") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_video_codecs_rightSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable selected codec(s)") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_video_codecs_leftSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable selected codec(s)") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_video_codecs_leftAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable all codecs") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_video_codecs_move_up\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Move codec up") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-up\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_view_video_codecs_move_down\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Move codec down") . "\" disabled=\"disabled\"><i class=\"fa fa-angle-down\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"VideoCodecs\" id=\"equipment_view_video_codecs_to\" class=\"form-control\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Add equipment models tabs
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_view_tab_" . $tab . "\">\n";
    $output .= $tabinfo["html"];
    $output .= "    </div>\n";
  }

  // Firmwares panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_view_tab_firmwares\">\n";

  // Add firmware template
  $output .= "      <div class=\"form-group form-firmware firmware-_VERSION_ hidden\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Firmware") . " _VERSION_</label>\n";
  $output .= "        <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "          <div class=\"input-group form-firmware-file hidden\">\n";
  $output .= "            <input type=\"text\" name=\"Firmware_Filename__X_\" class=\"form-control\" id=\"equipment_view_firmware__X__filename\" placeholder=\"" . __ ( "Firmware filename") . "\" disabled=\"disabled\" />\n";
  $output .= "            <input type=\"text\" name=\"Firmware_SHA256__X_\" class=\"form-control\" id=\"equipment_view_firmware__X__sha256\" placeholder=\"" . __ ( "File content SHA256 hash") . "\" disabled=\"disabled\" />\n";
  $output .= "            <input type=\"text\" name=\"Firmware_Size__X_\" class=\"form-control\" id=\"equipment_view_firmware__X__size\" placeholder=\"" . __ ( "File size") . "\" disabled=\"disabled\" />\n";
  $output .= "            <span class=\"input-group-addon input-icon-button btn btn-_CLASS_\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"_TITLE_\" disabled=\"disabled\"><i class=\"fa fa-_ICON_\"></i></span>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // About equipment panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_view_tab_about\">\n";

  // Add equipment vendor link
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_vendor_link\" class=\"control-label col-xs-2\">" . __ ( "Vendor link") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"VendorLink\" class=\"form-control\" id=\"equipment_view_vendor_link\" placeholder=\"" . __ ( "Equipment vendor link") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-vendorlink\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Visit equipment vendor site") . "\" disabled=\"disabled\"><i class=\"fa fa-external-link-alt\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment model link
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_model_link\" class=\"control-label col-xs-2\">" . __ ( "Model link") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"ModelLink\" class=\"form-control\" id=\"equipment_view_model_link\" placeholder=\"" . __ ( "Equipment model link") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-modellink\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Visit equipment model site") . "\" disabled=\"disabled\"><i class=\"fa fa-external-link-alt\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment image
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_about_image\" class=\"control-label col-xs-2\">" . __ ( "Image") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <img id=\"equipment_view_about_image\" src=\"/modules/equipments/images/missing.png\" alt=\"\" width=\"240\" style=\"background-color: #eeeeee; border-color: #d2d6de; border-style: solid; border-width: 1px\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/equipments\" alt=\"\">" . __ ( "Return") . "</a>\n";
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
    $onshow .= "  switch ( data.UID)\n";
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
  sys_addjs ( "$('#equipment_view_type,#equipment_view_supportlevel').select2 ();\n" .
              "$('#equipment_view_videosupport,#equipment_view_autoprovision,#equipment_view_blfsupport').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#equipment_view_active').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "', onstyle: 'success', offstyle: 'danger'});\n" .
              "$('#equipment_view_audio_codecs,#equipment_view_video_codecs').multiselect ();\n" .
              "$('#equipment_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#equipment_view_uid').val ( data.UID);\n" .
              "  $('#equipment_view_vendor').val ( data.Vendor);\n" .
              "  $('#equipment_view_model').val ( data.Model);\n" .
              "  $('#equipment_view_description').val ( data.Description);\n" .
              "  $('#equipment_view_active').bootstrapToggle ( 'enable').bootstrapToggle ( data.Active ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#equipment_view_type').val ( data.Type).trigger ( 'change');\n" .
              "  $('#equipment_view_supportlevel').val ( data.SupportLevel).trigger ( 'change');\n" .
              "  $('#equipment_view_videosupport').bootstrapToggle ( 'enable').bootstrapToggle ( data.VideoSupport ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#equipment_view_autoprovision').bootstrapToggle ( 'enable').bootstrapToggle ( data.AutoProvision ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#equipment_view_blfsupport').bootstrapToggle ( 'enable').bootstrapToggle ( data.BLFSupport ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#equipment_view_accounts').val ( data.Accounts);\n" .
              "  $('#equipment_view_shortcuts').val ( data.Shortcuts);\n" .
              "  $('#equipment_view_extensions').val ( data.Extensions);\n" .
              "  $('#equipment_view_spe').val ( data.ShortcutsPerExtension);\n" .
              "  $('#equipment_view_audio_codecs').find ( 'option').each ( function ()\n" .
              "  {\n" .
              "    if ( ! data.SupportedAudioCodecs.includes ( $(this).val ()))\n" .
              "    {\n" .
              "      $(this).remove ();\n" .
              "    }\n" .
              "  });\n" .
              "  for ( var index = data.AudioCodecs.length - 1; index >= 0; index--)\n" .
              "  {\n" .
              "    if ( $('#equipment_view_audio_codecs').find ( 'option[value=\"' + data.AudioCodecs[index] + '\"]').length == 1)\n" .
              "    {\n" .
              "      $('#equipment_view_audio_codecs').find ( 'option[value=\"' + data.AudioCodecs[index] + '\"]').detach ().prependTo ( $('#equipment_view_audio_codecs_to'));\n" .
              "    }\n" .
              "  }\n" .
              "  if ( ! data.VideoSupport)\n" .
              "  {\n" .
              "    $('a[href=\"#equipment_view_tab_video_codecs\"]').parent ().addClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#equipment_view_video_codecs').find ( 'option').each ( function ()\n" .
              "    {\n" .
              "      if ( ! data.SupportedVideoCodecs.includes ( $(this).val ()))\n" .
              "      {\n" .
              "        $(this).remove ();\n" .
              "      }\n" .
              "    });\n" .
              "    for ( var index = data.VideoCodecs.length - 1; index >= 0; index--)\n" .
              "    {\n" .
              "      if ( $('#equipment_view_video_codecs').find ( 'option[value=\"' + data.VideoCodecs[index] + '\"]').length == 1)\n" .
              "      {\n" .
              "        $('#equipment_view_video_codecs').find ( 'option[value=\"' + data.VideoCodecs[index] + '\"]').detach ().prependTo ( $('#equipment_view_video_codecs_to'));\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "  if ( ! data.AutoProvision)\n" .
              "  {\n" .
              "    $('a[href=\"#equipment_view_tab_firmwares\"]').parent ().addClass ( 'disabled');\n" .
              "  } else {\n" .
              "    var id = 0;\n" .
              "    for ( var index = data.SupportedFirmwares.length - 1; index >= 0; index--)\n" .
              "    {\n" .
              "      var formclass = 'firmware-' + data.SupportedFirmwares[index].Version.replace ( /\./g, '_').replace ( /[^a-zA-Z0-9_]/g, '');\n" .
              "      $('<div class=\"form-group form-firmware ' + formclass + '\">' + $('#equipment_view_tab_firmwares div.form-firmware.hidden').html ().replace ( /_VERSION_/g, data.SupportedFirmwares[index].Version) + '</div>').insertAfter ( $('#equipment_view_tab_firmwares div.form-firmware.hidden')).removeClass ( 'hidden');\n" .
              "      for ( var fileindex = data.SupportedFirmwares[index].Files.length - 1; fileindex >= 0; fileindex--)\n" .
              "      {\n" .
              "        $('<div class=\"input-group form-firmware-file form-firmware-file-' + id + '\">' + $('#equipment_view_tab_firmwares div.' + formclass + ' div.form-firmware-file.hidden').html ().replace ( /_X_/g, id).replace ( /_TITLE_/g, data.SupportedFirmwares[index].Files[fileindex].Available ? '" . __ ( "Available") . "' : '" . __ ( "Unavailable") . "').replace ( /_ICON_/g, data.SupportedFirmwares[index].Files[fileindex].Available ? 'check' : 'times').replace ( /_CLASS_/g, data.SupportedFirmwares[index].Files[fileindex].Available ? 'success' : 'danger') + '</div>').insertAfter ( $('#equipment_view_tab_firmwares div.' + formclass + ' div.form-firmware-file.hidden')).removeClass ( 'hidden').tooltip ( { container: 'body'});\n" .
              "        $('#equipment_view_firmware_' + id + '_filename').val ( data.SupportedFirmwares[index].Files[fileindex].Filename);\n" .
              "        $('#equipment_view_firmware_' + id + '_sha256').val ( data.SupportedFirmwares[index].Files[fileindex].SHA256);\n" .
              "        $('#equipment_view_firmware_' + id + '_size').val ( data.SupportedFirmwares[index].Files[fileindex].Size);\n" .
              "        id++;\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.Image != '')\n" .
              "  {\n" .
              "    $('#equipment_view_image').attr ( 'src', '/modules/equipments/images/' + data.Image);\n" .
              "    $('#equipment_view_about_image').attr ( 'src', '/modules/equipments/images/' + data.Image);\n" .
              "  }\n" .
              "  if ( data.VendorLink != '')\n" .
              "  {\n" .
              "    $('#equipment_view_vendor_link').val ( data.VendorLink);\n" .
              "    $('.btn-vendorlink').removeAttr ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.ModelLink != '')\n" .
              "  {\n" .
              "    $('#equipment_view_model_link').val ( data.ModelLink);\n" .
              "    $('.btn-modellink').removeAttr ( 'disabled');\n" .
              "  }\n" .
              "  $('#equipment_view_form ul.nav-pages').find ( 'li[data-type=\'' + data.UID + '\']').css ( 'display', 'block');\n" .
              $onshow .
              "});\n" .
              "$('.btn-vendorlink').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  window.open ( $('#equipment_view_vendor_link').val (), '_blank').focus ();\n" .
              "});\n" .
              "$('.btn-modellink').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  window.open ( $('#equipment_view_model_link').val (), '_blank').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/equipments/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#equipment_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Equipment view") . "', text: '" . __ ( "Error viewing equipment!") . "', type: 'error'});\n" .
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
 * Function to generate the equipment configuration page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_configure_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Equipments"));
  sys_set_subtitle ( __ ( "equipments configuration"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Equipments"), "link" => "/equipments"),
    2 => array ( "title" => __ ( "Configure"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "multiselect", "src" => "/vendors/multiselect/dist/js/multiselect.js", "dep" => array ()));

  /**
   * First, we call sub equipment configure hook's to populate tabs
   */
  $subpages = filters_call ( "equipments_configure_subpages", array (), array ( "tabs" => array (), "js" => array ( "init" => array (), "onshow" => array ()), "html" => ""));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"equipment_configure_form\">\n";
  $output .= "<input type=\"hidden\" id=\"equipment_configure_uid\" />\n";

  // Add tabs
  $output .= "  <ul class=\"nav nav-tabs nav-pages\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_configure_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_configure_tab_audio_codecs\">" . __ ( "Audio codecs") . "</a></li>\n";
  $output .= "    <li role=\"presentation\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_configure_tab_video_codecs\">" . __ ( "Video codecs") . "</a></li>\n";
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <li role=\"presentation\" style=\"display: none\" data-type=\"" . $tabinfo["type"] . "\"><a class=\"nav-tablink\" href=\"#equipment_configure_tab_" . $tab . "\">" . $tabinfo["label"] . "</a></li>\n";
  }
  $output .= "    <li role=\"presentation\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_configure_tab_firmwares\">" . __ ( "Firmwares") . "</a></li>\n";
  $output .= "    <li role=\"presentation\" data-type=\"\"><a class=\"nav-tablink\" href=\"#equipment_configure_tab_about\">" . __ ( "About") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"equipment_configure_tab_basic\">\n";

  // Add equipment vendor field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_vendor\" class=\"control-label col-xs-2\">" . __ ( "Vendor") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Vendor\" class=\"form-control\" id=\"equipment_configure_vendor\" placeholder=\"" . __ ( "Equipment vendor") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment model field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_model\" class=\"control-label col-xs-2\">" . __ ( "Model") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Model\" class=\"form-control\" id=\"equipment_configure_model\" placeholder=\"" . __ ( "Equipment model") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment description field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"equipment_configure_description\" placeholder=\"" . __ ( "Equipment description") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment active status option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_active\" class=\"control-label col-xs-2\">" . __ ( "Status") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Active\" id=\"equipment_configure_active\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment type field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Type\" id=\"equipment_configure_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Equipment type") . "\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"\" selected></option>\n";
  foreach ( get_defined_constants () as $key => $val)
  {
    if ( substr ( $key, 0, 18) == "VD_EQUIPMENT_TYPE_")
    {
      $output .= "            <option value=\"" . addslashes ( strip_tags ( substr ( $key, 18))) . "\">" . addslashes ( strip_tags ( __ ( $val))) . "</option>\n";
    }
  }
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment support level field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_supportlevel\" class=\"control-label col-xs-2\">" . __ ( "Support level") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"SupportLevel\" id=\"equipment_configure_supportlevel\" class=\"form-control\" data-placeholder=\"" . __ ( "Equipment support level") . "\" disabled=\"disabled\">\n";
  $output .= "            <option value=\"\" selected></option>\n";
  $output .= "            <option value=\"None\">" . __ ( "None") . "</option>\n";
  $output .= "            <option value=\"Basic\">" . __ ( "Basic") . "</option>\n";
  $output .= "            <option value=\"Complete\">" . __ ( "Complete") . "</option>\n";
  $output .= "            <option value=\"Premium\">" . __ ( "Premium") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment video support option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_videosupport\" class=\"control-label col-xs-2\">" . __ ( "Video support") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"VideoSupport\" id=\"equipment_configure_videosupport\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment auto provisioninig option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_autoprovision\" class=\"control-label col-xs-2\">" . __ ( "Auto provisioning support") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"AutoProvision\" id=\"equipment_configure_autoprovision\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment BLF support option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_blfsupport\" class=\"control-label col-xs-2\">" . __ ( "BLF support") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"BLF\" id=\"equipment_configure_blfsupport\" value=\"true\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment number of accounts field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_accounts\" class=\"control-label col-xs-2\">" . __ ( "Accounts") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Accounts\" class=\"form-control\" id=\"equipment_configure_accounts\" placeholder=\"" . __ ( "Equipment accounts") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment number of shortcuts field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_shortcuts\" class=\"control-label col-xs-2\">" . __ ( "Shortcuts") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Shortcuts\" class=\"form-control\" id=\"equipment_configure_shortcuts\" placeholder=\"" . __ ( "Equipment shortcuts") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment number of extensions field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_extensions\" class=\"control-label col-xs-2\">" . __ ( "Extensions") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Extensions\" class=\"form-control\" id=\"equipment_configure_extensions\" placeholder=\"" . __ ( "Equipment extensions") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment shortcuts per extension field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_spe\" class=\"control-label col-xs-2\">" . __ ( "Shortcuts Per Extension") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"SPE\" class=\"form-control\" id=\"equipment_configure_spe\" placeholder=\"" . __ ( "Equipment shortcuts per extension") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Audio codecs panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_configure_tab_audio_codecs\">\n";

  // Add equipment audio codecs selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_audio_codecs\" class=\"control-label col-xs-2\">" . __ ( "Audio codecs") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"AvailableAudioCodecs\" id=\"equipment_configure_audio_codecs\" class=\"form-control\" multiple=\"multiple\">\n";
  foreach ( get_defined_constants () as $key => $val)
  {
    $codecs = array ();
    if ( substr ( $key, 0, 15) == "VD_AUDIO_CODEC_")
    {
      $codecs[substr ( $key, 15)] = $val;
    }
    asort ( $codecs);
    foreach ( $codecs as $key => $val)
    {
      $output .= "              <option value=\"" . addslashes ( strip_tags ( $key)) . "\">" . addslashes ( strip_tags ( __ ( $val))) . "</option>\n";
    }
  }
  $output .= "            </select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_audio_codecs_rightAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable all codecs") . "\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_audio_codecs_rightSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable selected codec(s)") . "\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_audio_codecs_leftSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable selected codec(s)") . "\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_audio_codecs_leftAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable all codecs") . "\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_audio_codecs_move_up\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Move codec up") . "\"><i class=\"fa fa-angle-up\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_audio_codecs_move_down\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Move codec down") . "\"><i class=\"fa fa-angle-down\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"AudioCodecs\" id=\"equipment_configure_audio_codecs_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Video codecs panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_configure_tab_video_codecs\">\n";

  // Add equipment video codecs selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_video_codecs\" class=\"control-label col-xs-2\">" . __ ( "Video codecs") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 multiselect\">\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"AvailableVideoCodecs\" id=\"equipment_configure_video_codecs\" class=\"form-control\" multiple=\"multiple\">\n";
  foreach ( get_defined_constants () as $key => $val)
  {
    $codecs = array ();
    if ( substr ( $key, 0, 15) == "VD_VIDEO_CODEC_")
    {
      $codecs[substr ( $key, 15)] = $val;
    }
    asort ( $codecs);
    foreach ( $codecs as $key => $val)
    {
      $output .= "              <option value=\"" . addslashes ( strip_tags ( $key)) . "\">" . addslashes ( strip_tags ( __ ( $val))) . "</option>\n";
    }
  }
  $output .= "            </select>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-2\">\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_video_codecs_rightAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable all codecs") . "\"><i class=\"fa fa-angle-double-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_video_codecs_rightSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Enable selected codec(s)") . "\"><i class=\"fa fa-angle-right\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_video_codecs_leftSelected\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable selected codec(s)") . "\"><i class=\"fa fa-angle-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_video_codecs_leftAll\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Disable all codecs") . "\"><i class=\"fa fa-angle-double-left\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_video_codecs_move_up\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Move codec up") . "\"><i class=\"fa fa-angle-up\"></i></button>\n";
  $output .= "            <button type=\"button\" id=\"equipment_configure_video_codecs_move_down\" class=\"btn btn-block\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Move codec down") . "\"><i class=\"fa fa-angle-down\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-sm-5\">\n";
  $output .= "            <select name=\"VideoCodecs\" id=\"equipment_configure_video_codecs_to\" class=\"form-control\" multiple=\"multiple\"></select>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Add equipment model tabs
  foreach ( $subpages["tabs"] as $tab => $tabinfo)
  {
    $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_configure_tab_" . $tab . "\">\n";
    $output .= $tabinfo["html"];
    $output .= "    </div>\n";
  }

  // Firmwares panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_configure_tab_firmwares\">\n";

  // Add firmware template
  $output .= "      <div class=\"form-group form-firmware firmware-_VERSION_ hidden\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Firmware") . " _VERSION_</label>\n";
  $output .= "        <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "          <div class=\"input-group form-firmware-file hidden\">\n";
  $output .= "            <input type=\"text\" name=\"Firmware_Filename__X_\" class=\"form-control firmware-filename\" id=\"equipment_configure_firmware__X__filename\" placeholder=\"" . __ ( "Firmware filename") . "\" disabled=\"disabled\" />\n";
  $output .= "            <input type=\"file\" name=\"Firmware_File__X_\" class=\"hidden firmware-file\" id=\"equipment_configure_firmware__X__file\" />\n";
  $output .= "            <input type=\"text\" name=\"Firmware_SHA256__X_\" class=\"form-control firmware-sha256\" id=\"equipment_configure_firmware__X__sha256\" placeholder=\"" . __ ( "File content SHA256 hash") . "\" disabled=\"disabled\" />\n";
  $output .= "            <input type=\"text\" name=\"Firmware_Size__X_\" class=\"form-control firmware-size\" id=\"equipment_configure_firmware__X__size\" placeholder=\"" . __ ( "File size") . "\" disabled=\"disabled\" />\n";
  $output .= "            <span class=\"input-group-addon input-icon-button btn btn-_CLASS_ ladda-button\" data-style=\"zoom-in\" data-spinner-size=\"20px\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"_TITLE_\"><i class=\"fa fa-_ICON_\"></i></span>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // About equipment panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"equipment_configure_tab_about\">\n";

  // Add equipment vendor link
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_vendor_link\" class=\"control-label col-xs-2\">" . __ ( "Vendor link") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"VendorLink\" class=\"form-control\" id=\"equipment_configure_vendor_link\" placeholder=\"" . __ ( "Equipment vendor link") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-vendorlink\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Visit equipment vendor site") . "\" disabled=\"disabled\"><i class=\"fa fa-external-link-alt\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment model link
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_model_link\" class=\"control-label col-xs-2\">" . __ ( "Model link") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"ModelLink\" class=\"form-control\" id=\"equipment_configure_model_link\" placeholder=\"" . __ ( "Equipment model link") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-modellink\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Visit equipment model site") . "\" disabled=\"disabled\"><i class=\"fa fa-external-link-alt\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment image
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_about_image\" class=\"control-label col-xs-2\">" . __ ( "Image") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <img id=\"equipment_configure_about_image\" src=\"/modules/equipments/images/missing.png\" alt=\"\" width=\"240\" style=\"background-color: #eeeeee; border-color: #d2d6de; border-style: solid; border-width: 1px\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/equipments\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add firmware remove confirmation modal code
   */
  $output .= "<div id=\"equipment_firmware_remove\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"equipment_firmware_remove\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Firmware remove") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the firmware file %s?"), "<strong><span id=\"equipment_firmware_remove_filename\"></span></strong>") . "</p></div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "        <button class=\"btn btn-primary del ladda-button\" data-style=\"expand-left\">" . __ ( "Remove") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

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
    $onshow .= "  switch ( data.UID)\n";
    $onshow .= "  {\n";
    foreach ( $subpages["js"]["onshow"] as $model => $js)
    {
      $onshow .= "    case '" . $model . "':\n";
      $onshow .= $js;
      $onshow .= "      break;\n";
    }
    $onshow .= "  }\n";
  }

  /**
   * Add configure form JavaScript code
   */
  sys_addjs ( "$('#equipment_configure_type,#equipment_configure_supportlevel').select2 ();\n" .
              "$('#equipment_configure_videosupport,#equipment_configure_autoprovision,#equipment_configure_blfsupport').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#equipment_configure_active').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "', onstyle: 'success', offstyle: 'danger'});\n" .
              "$('#equipment_configure_audio_codecs,#equipment_configure_video_codecs').multiselect ();\n" .
              "$('#equipment_configure_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#equipment_configure_uid').val ( data.UID);\n" .
              "  $('#equipment_configure_vendor').val ( data.Vendor);\n" .
              "  $('#equipment_configure_model').val ( data.Model);\n" .
              "  $('#equipment_configure_description').val ( data.Description);\n" .
              "  $('#equipment_configure_active').bootstrapToggle ( 'enable').bootstrapToggle ( data.Active ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#equipment_configure_type').val ( data.Type).trigger ( 'change');\n" .
              "  $('#equipment_configure_supportlevel').val ( data.SupportLevel).trigger ( 'change');\n" .
              "  $('#equipment_configure_videosupport').bootstrapToggle ( 'enable').bootstrapToggle ( data.VideoSupport ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#equipment_configure_autoprovision').bootstrapToggle ( 'enable').bootstrapToggle ( data.AutoProvision ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#equipment_configure_blfsupport').bootstrapToggle ( 'enable').bootstrapToggle ( data.BLFSupport ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
              "  $('#equipment_configure_accounts').val ( data.Accounts);\n" .
              "  $('#equipment_configure_shortcuts').val ( data.Shortcuts);\n" .
              "  $('#equipment_configure_extensions').val ( data.Extensions);\n" .
              "  $('#equipment_configure_spe').val ( data.ShortcutsPerExtension);\n" .
              "  $('#equipment_configure_audio_codecs').find ( 'option').each ( function ()\n" .
              "  {\n" .
              "    if ( ! data.SupportedAudioCodecs.includes ( $(this).val ()))\n" .
              "    {\n" .
              "      $(this).remove ();\n" .
              "    }\n" .
              "  });\n" .
              "  for ( var index = data.AudioCodecs.length - 1; index >= 0; index--)\n" .
              "  {\n" .
              "    if ( $('#equipment_configure_audio_codecs').find ( 'option[value=\"' + data.AudioCodecs[index] + '\"]').length == 1)\n" .
              "    {\n" .
              "      $('#equipment_configure_audio_codecs').find ( 'option[value=\"' + data.AudioCodecs[index] + '\"]').detach ().prependTo ( $('#equipment_configure_audio_codecs_to'));\n" .
              "    }\n" .
              "  }\n" .
              "  if ( ! data.VideoSupport)\n" .
              "  {\n" .
              "    $('a[href=\"#equipment_configure_tab_video_codecs\"]').parent ().addClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#equipment_configure_video_codecs').find ( 'option').each ( function ()\n" .
              "    {\n" .
              "      if ( ! data.SupportedVideoCodecs.includes ( $(this).val ()))\n" .
              "      {\n" .
              "        $(this).remove ();\n" .
              "      }\n" .
              "    });\n" .
              "    for ( var index = data.VideoCodecs.length - 1; index >= 0; index--)\n" .
              "    {\n" .
              "      if ( $('#equipment_configure_video_codecs').find ( 'option[value=\"' + data.VideoCodecs[index] + '\"]').length == 1)\n" .
              "      {\n" .
              "        $('#equipment_configure_video_codecs').find ( 'option[value=\"' + data.VideoCodecs[index] + '\"]').detach ().prependTo ( $('#equipment_configure_video_codecs_to'));\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "  if ( ! data.AutoProvision)\n" .
              "  {\n" .
              "    $('a[href=\"#equipment_configure_tab_firmwares\"]').parent ().addClass ( 'disabled');\n" .
              "  } else {\n" .
              "    var id = 0;\n" .
              "    for ( var index = data.SupportedFirmwares.length - 1; index >= 0; index--)\n" .
              "    {\n" .
              "      var formclass = 'firmware-' + data.SupportedFirmwares[index].Version.replace ( /\./g, '_').replace ( /[^a-zA-Z0-9_]/g, '');\n" .
              "      $('<div class=\"form-group form-firmware ' + formclass + '\">' + $('#equipment_configure_tab_firmwares div.form-firmware.hidden').html ().replace ( /_VERSION_/g, data.SupportedFirmwares[index].Version) + '</div>').insertAfter ( $('#equipment_configure_tab_firmwares div.form-firmware.hidden')).data ( 'firmware_version', data.SupportedFirmwares[index].Version).data ( 'firmware_priority', data.SupportedFirmwares[index].Priority).data ( 'firmware_available', data.SupportedFirmwares[index].Available).data ( 'firmware_files', data.SupportedFirmwares[index].Files).removeClass ( 'hidden');\n" .
              "      for ( var fileindex = data.SupportedFirmwares[index].Files.length - 1; fileindex >= 0; fileindex--)\n" .
              "      {\n" .
              "        $('<div class=\"input-group form-firmware-file form-firmware-file-' + id + '\">' + $('#equipment_configure_tab_firmwares div.' + formclass + ' div.form-firmware-file.hidden').html ().replace ( /_X_/g, id).replace ( /_TITLE_/g, data.SupportedFirmwares[index].Files[fileindex].Available ? '" . __ ( "Remove firmware") . "' : '" . __ ( "Upload firmware") . "').replace ( /_ICON_/g, data.SupportedFirmwares[index].Files[fileindex].Available ? 'times' : 'file-upload').replace ( /_CLASS_/g, data.SupportedFirmwares[index].Files[fileindex].Available ? 'success btn-removefile' : 'primary btn-uploadfile') + '</div>').insertAfter ( $('#equipment_configure_tab_firmwares div.' + formclass + ' div.form-firmware-file.hidden')).data ( 'firmware_version', data.SupportedFirmwares[index].Version).data ( 'file_name', data.SupportedFirmwares[index].Files[fileindex].Filename).data ( 'file_sha', data.SupportedFirmwares[index].Files[fileindex].SHA256).data ( 'file_size', data.SupportedFirmwares[index].Files[fileindex].Size).data ( 'file_available', data.SupportedFirmwares[index].Files[fileindex].Available).removeClass ( 'hidden').tooltip ( { container: 'body'});\n" .
              "        $('#equipment_configure_firmware_' + id + '_filename').val ( data.SupportedFirmwares[index].Files[fileindex].Filename);\n" .
              "        $('#equipment_configure_firmware_' + id + '_sha256').val ( data.SupportedFirmwares[index].Files[fileindex].SHA256);\n" .
              "        $('#equipment_configure_firmware_' + id + '_size').val ( data.SupportedFirmwares[index].Files[fileindex].Size);\n" .
              "        id++;\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.Image != '')\n" .
              "  {\n" .
              "    $('#equipment_configure_about_image').attr ( 'src', '/modules/equipments/images/' + data.Image);\n" .
              "  }\n" .
              "  if ( data.VendorLink != '')\n" .
              "  {\n" .
              "    $('#equipment_configure_vendor_link').val ( data.VendorLink);\n" .
              "    $('.btn-vendorlink').removeAttr ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.ModelLink != '')\n" .
              "  {\n" .
              "    $('#equipment_configure_model_link').val ( data.ModelLink);\n" .
              "    $('.btn-modellink').removeAttr ( 'disabled');\n" .
              "  }\n" .
              "  $('#equipment_configure_form ul.nav-pages').find ( 'li[data-type=\'' + data.UID + '\']').css ( 'display', 'block');\n" .
              $onshow .
              "});\n" .
              "$('#equipment_configure_tab_firmwares').on ( 'click', '.btn-uploadfile', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().find ( 'input:file').trigger ( 'click');\n" .
              "});\n" .
              "$('#equipment_configure_tab_firmwares').on ( 'click', '.btn-removefile', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#equipment_firmware_remove').data ( 'firmware', $(this).parent ());\n" .
              "  $('#equipment_firmware_remove button.del').prop ( 'disabled', false);\n" .
              "  $('#equipment_firmware_remove_filename').html ( $(this).parent ().find ( 'input.firmware-filename').val ());\n" .
              "  $('#equipment_firmware_remove').modal ( 'show');\n" .
              "});\n" .
              "$('#equipment_firmware_remove button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/equipments/' + encodeURIComponent ( VoIP.parameters.id) + '/firmware/' + encodeURIComponent ( $('#equipment_firmware_remove_filename').html ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#equipment_firmware_remove').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Equipment configuration") . "', text: '" . __ ( "Firmware removed sucessfully!") . "', type: 'success'});\n" .
              "    var firmware = $('#equipment_firmware_remove').data ( 'firmware');\n" .
              "    $(firmware).find ( 'input.firmware-file').val ( '');\n" .
              "    $(firmware).find ( '.btn-removefile').removeClass ( 'btn-success btn-removefile').addClass ( 'btn-primary btn-uploadfile').attr ( 'data-original-title', '" . __ ( "Upload firmware") . "').find ( 'i').removeClass ( 'fa-times').addClass ( 'fa-file-upload');\n" .
              "    VoIP.rest ( '/equipments/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "    {\n" .
              "      if ( $('#equipment_configure_active').is ( ':checked') != data.Active && ! data.Active)\n" .
              "      {\n" .
              "        new PNotify ( { title: '" . __ ( "Equipment configuration") . "', text: '" . __ ( "Equipment automatically deactivated!") . "', type: 'info'});\n" .
              "        $('#equipment_configure_active').bootstrapToggle ( 'enable').bootstrapToggle ( 'off').bootstrapToggle ( 'disable');\n" .
              "      }\n" .
              "    });\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Equipment configuration") . "', text: '" . __ ( "Error removing firmware!") . "', type: 'error'});\n" .
              "  }).then ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n" .
              "$('#equipment_configure_tab_firmwares').on ( 'change', 'input[type=\"file\"]', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  if ( $(this).val () == '')\n" .
              "  {\n" .
              "    return;\n" .
              "  }\n" .
              "  var that = this;\n" .
              "  $(this).parent ().find ( '.btn-uploadfile').tooltip ( 'hide');\n" .
              "  var button = $(this).parent ().find ( '.btn-uploadfile')[0];\n" .
              "  var l = Ladda.create ( button);\n" .
              "  l.start ();\n" .
              "  $(button).attr ( 'disabled', 'disabled');\n" .
              "  var fr = new FileReader ();\n" .
              "  fr.onload = function ()\n" .
              "  {\n" .
              "    that.filecontent = btoa ( fr.result);\n" .
              "  }\n" .
              "  fr.readAsBinaryString ( this.files[0]);\n" .
              "  new Promise ( ( resolve, reject) =>\n" .
              "  {\n" .
              "    var fr = new FileReader ();\n" .
              "    fr.onload = () =>\n" .
              "    {\n" .
              "      resolve ( fr.result);\n" .
              "    };\n" .
              "    fr.readAsArrayBuffer ( that.files[0]);\n" .
              "  }).then ( function ( result)\n" .
              "  {\n" .
              "    result = new Uint8Array ( result);\n" .
              "    return window.crypto.subtle.digest ( 'SHA-256', result);\n" .
              "  }).then ( function ( result)\n" .
              "  {\n" .
              "    result = new Uint8Array ( result);\n" .
              "    var resulthex = Uint8ArrayToHexString ( result);\n" .
              "    if ( resulthex != $(that).parent ().find ( 'input.firmware-sha256').val () || that.files[0].size != $(that).parent ().find ( 'input.firmware-size').val ())\n" .
              "    {\n" .
              "      $(that).val ( '');\n" .
              "      new PNotify ( { title: '" . __ ( "Equipment configuration") . "', text: '" . __ ( "File didn't match required firmware content!") . "', type: 'error'});\n" .
              "      $(button).removeAttr ( 'disabled');\n" .
              "      l.stop ();\n" .
              "    } else {\n" .
              "      VoIP.rest ( '/equipments/' + encodeURIComponent ( VoIP.parameters.id) + '/firmware', 'POST', { Filename: $(that).parent ().find ( 'input.firmware-filename').val (), SHA256: $(that).parent ().find ( 'input.firmware-sha256').val (), Size: $(that).parent ().find ( 'input.firmware-size').val (), FileContent: that.filecontent}).done ( function ( data, textStatus, jqXHR)\n" .
              "      {\n" .
              "        new PNotify ( { title: '" . __ ( "Equipment configuration") . "', text: '" . __ ( "Firmware sucessfully uploaded!") . "', type: 'success'});\n" .
              "        if ( $('#equipment_configure_active').is ( ':checked') != data.Active && data.Active)\n" .
              "        {\n" .
              "          new PNotify ( { title: '" . __ ( "Equipment configuration") . "', text: '" . __ ( "Equipment automatically activated!") . "', type: 'info'});\n" .
              "          $('#equipment_configure_active').bootstrapToggle ( 'enable').bootstrapToggle ( 'on').bootstrapToggle ( 'disable');\n" .
              "        }\n" .
              "        $(that).parent ().find ( '.btn-uploadfile').removeClass ( 'btn-primary btn-uploadfile').addClass ( 'btn-success btn-removefile').attr ( 'data-original-title', '" . __ ( "Remove firmware") . "').find ( 'i').removeClass ( 'fa-file-upload').addClass ( 'fa-times');\n" .
              "      }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "      {\n" .
              "        try\n" .
              "        {\n" .
              "          var jsonresult = JSON.parse ( jqXHR.responseText);\n" .
              "        } catch ( e) {\n" .
              "          var jsonresult = [];\n" .
              "        }\n" .
              "        $(that).parent ().find ( 'input.firmware-filename').data ( 'popover', $(that).parent ().find ( 'input.firmware-filename').popover ( { placement: 'auto top', content: jsonresult.Filename, trigger: 'manual'}).popover ( 'show'));\n" .
              "        $(that).parent ().find ( 'input.firmware-filename').addClass ( 'alert-danger');\n" .
              "        new PNotify ( { title: '" . __ ( "Equipment configuration") . "', text: '" . __ ( "Cannot upload firmware file!") . "', type: 'error'});\n" .
              "      }).then ( function ()\n" .
              "      {\n" .
              "        $(button).removeAttr ( 'disabled');\n" .
              "        l.stop ();\n" .
              "      });\n" .
              "    }\n" .
              "  });\n" .
              "});\n" .
              "$('.btn-vendorlink').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  window.open ( $('#equipment_configure_vendor_link').val (), '_blank').focus ();\n" .
              "});\n" .
              "$('.btn-modellink').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  window.open ( $('#equipment_configure_model_link').val (), '_blank').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/equipments/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#equipment_configure_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Equipment configuration") . "', text: '" . __ ( "Error requesting equipment data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#equipment_configure_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/equipments/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Equipment configuration") . "',\n" .
              "    fail: '" . __ ( "Error changing equipment!") . "',\n" .
              "    success: '" . __ ( "Equipment configured sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/equipments', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  /**
   * Add subpages configure form JavaScript code
   */
  foreach ( $subpages["js"]["init"] as $js)
  {
    sys_addjs ( $js);
  }

  return $output;
}
?>
