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
 * VoIP Domain configuration module WebUI.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Configuration
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_hook ( "config_page", "config_page");
framework_add_path ( "/config", "config_page");

/**
 * Function to create the configuration page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $output Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function config_page ( $output, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Configurations"));
  sys_set_subtitle ( __ ( "global configurations"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Configurations"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "multiselect", "src" => "/vendors/multiselect/dist/js/multiselect.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "sortable", "src" => "/vendors/Sortable/Sortable.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"config_form\">\n";

  // Add configuration panels
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#config_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#config_tab_datetime\">" . __ ( "Date/Time") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"config_tab_basic\">\n";

  // Add extension length selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"config_number_length\" class=\"control-label col-xs-2\">" . __ ( "Numbers length") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Length\" id=\"config_length\" class=\"form-control\" data-placeholder=\"" . __ ( "Select the number of digits") . "\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "            <option value=\"2\">2 " . __ ( "digits") . "</option>\n";
  $output .= "            <option value=\"3\">3 " . __ ( "digits") . "</option>\n";
  $output .= "            <option value=\"4\">4 " . __ ( "digits") . "</option>\n";
  $output .= "            <option value=\"5\">5 " . __ ( "digits") . "</option>\n";
  $output .= "            <option value=\"6\">6 " . __ ( "digits") . "</option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add language selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"config_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Language\" id=\"config_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Select a language") . "\">\n";
  $output .= "            <option value=\"\"></option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "            <option value=\"" . addslashes ( strip_tags ( $locale)) . "\">" . addslashes ( strip_tags ( strip_tags ( $language))) . "</option>\n";
  }
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add currency selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"config_currency\" class=\"control-label col-xs-2\">" . __ ( "Currency") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Currency\" id=\"config_currency\" class=\"form-control\" data-placeholder=\"" . __ ( "Select default currency") . "\">\n";
  $output .= "            <option value=\"\"></option>\n";
  $output .= "          </select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add operator extension selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"config_operator\" class=\"control-label col-xs-2\">" . __ ( "Operator") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Operator\" id=\"config_operator\" class=\"form-control\" data-placeholder=\"" . __ ( "Operator extension") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add music on hold selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"config_moh\" class=\"control-label col-xs-2\">" . __ ( "Music on Hold") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"MOH\" id=\"config_moh\" class=\"form-control\" data-placeholder=\"" . __ ( "Select default music on hold") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Close basic panel
  $output .= "    </div>\n";

  // Date/time servers panel
  $output .= "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"config_tab_datetime\">\n";

  // Add NTP servers table
  $output .= "      <span id=\"config_ntp_servers\">\n";
  $output .= "        <div class=\"form-group form-ntpserver hidden\">\n";
  $output .= "          <label for=\"config_ntp__X_\" class=\"control-label col-xs-2\">" . __ ( "NTP server") . "<br /><span class=\"drag-handle\">&#9776;</span></label>\n";
  $output .= "          <div class=\"col-xs-10 inputGroupContainer\">\n";
  $output .= "            <div class=\"input-group\">\n";
  $output .= "              <input type=\"text\" name=\"NTP__X_\" class=\"form-control\" id=\"config_ntp__X_\" placeholder=\"" . __ ( "NTP server") . "\" />\n";
  $output .= "              <span class=\"input-group-addon input-icon-button btn btn-error btn-delntpserver\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Remove server") . "\"><i class=\"fa fa-minus\"></i></span>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  // Add new NTP server button
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <div class=\"col-xs-12 add-ntpserver\">\n";
  $output .= "            <span class=\"input-icon-button btn btn-success btn-addntpserver pull-right\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Add server") . "\"><i class=\"fa fa-plus\"></i></span>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </span>\n";

  // Close date/time panel
  $output .= "    </div>\n";

  // Finish tabs
  $output .= "  </div>\n";

  // Add buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add configuration JavaScript code
   */
  sys_addjs ( "$('.btn-addntpserver').data ( 'id', 0).on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).data ( 'id', $(this).data ( 'id') + 1);\n" .
              "  $('<div class=\"form-group form-ntpserver\">' + $('#config_ntp_servers div.hidden').html ().replace ( /_X_/g, $(this).data ( 'id')) + '</div>').insertBefore ( $('#config_ntp_servers div.hidden')).removeClass ( 'hidden').find ( '.btn-delntpserver').tooltip ( { container: 'body'});\n" .
              "  $('#config_ntp_' + $(this).data ( 'id')).focus ();\n" .
              "});\n" .
              "$('#config_ntp_servers').on ( 'click', '.btn-delntpserver', function ()\n" .
              "{\n" .
              "  $(this).tooltip ( 'hide');\n" .
              "  if ( $('#config_ntp_servers div.form-ntpserver').not ( '.hidden').length > 1)\n" .
              "  {\n" .
              "    $(this).closest ( 'div.form-ntpserver').remove ();\n" .
              "  }\n" .
              "});\n" .
              "Sortable.create ( document.getElementById ( 'config_ntp_servers'), { animation: 150, handle: '.drag-handle', draggable: '.form-ntpserver'});\n" .
              "$('#config_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
              "$('#config_currency').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/currencies', fields: 'Name,Code,Symbol', formatID: 'Code', formatText: '%Name% (%Code%/%Symbol%)'})\n" .
              "});\n" .
              "$('#config_operator').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/extensions', fields: 'ID,Description,Number', formatText: '%Description% (%Number%)'})\n" .
              "});\n" .
              "$('#config_moh').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/audios', fields: 'ID,Description,Filename', formatText: '%Description% (%Filename%)'})\n" .
              "});\n" .
              "$('#config_country').select2 (\n" .
              "{\n" .
              "  templateResult: function ( state)\n" .
              "  {\n" .
              "    if ( ! state.id)\n" .
              "    {\n" .
              "      return state.text;\n" .
              "    }\n" .
              "    return $('<span><span class=\"flag flag-' + state.id.toLowerCase () + '\"></span> ' + state.text + '</span>');\n" .
              "  },\n" .
              "  templateSelection: function ( state)\n" .
              "  {\n" .
              "    if ( ! state.id)\n" .
              "    {\n" .
              "      return state.text;\n" .
              "    }\n" .
              "    return $('<span><span class=\"flag flag-' + state.id.toLowerCase () + '\"></span> ' + state.text + '</span>');\n" .
              "  }\n" .
              "});\n" .
              "$('#config_language').select2 ();" .
              "$('#config_length').select2 ().select2 ( 'open');" .
              "$('#config_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#config_length').val ( data.Length).trigger ( 'change');\n" .
              "  if ( data.ImmutableLength)\n" .
              "  {\n" .
              "    $('#config_length').attr ( 'disabled', 'disabled');\n" .
              "  }\n" .
              "  $('#config_language').val ( data.Language).trigger ( 'change');\n" .
              "  $('#config_currency').val ( data.Currency).trigger ( 'change');\n" .
              "  $('#config_operator').val ( data.Operator).trigger ( 'change');\n" .
              "  $('#config_moh').val ( data.MOH).trigger ( 'change');\n" .
              "  if ( data.NTP.length == 0)\n" .
              "  {\n" .
              "    $('.btn-addntpserver').trigger ( 'click');\n" .
              "  } else {\n" .
              "    for ( var index in data.NTP)\n" .
              "    {\n" .
              "      if ( data.NTP.hasOwnProperty ( index))\n" .
              "      {\n" .
              "        $('.btn-addntpserver').trigger ( 'click');\n" .
              "        $('#config_ntp_' + $('.btn-addntpserver').data ( 'id')).val ( data.NTP[index]);\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "  if ( data.ImmutableLength)\n" .
              "  {\n" .
              "    $('#config_language').select2 ( 'open');\n" .
              "  } else {\n" .
              "    $('#config_length').select2 ( 'open');\n" .
              "  }\n" .
              "}).alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/config',\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Configurations") . "',\n" .
              "    fail: '" . __ ( "Error changing configurations!") . "',\n" .
              "    success: '" . __ ( "Configurations sucessfully changed!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#config_form').data ( 'formData');\n" .
              "  formData.NTP = new Array ();\n" .
              "  for ( var index in formData)\n" .
              "  {\n" .
              "    if ( index.substr ( 0, 4) == 'NTP_')\n" .
              "    {\n" .
              "      if ( formData[index] != '')\n" .
              "      {\n" .
              "        formData.NTP.push ( formData[index]);\n" .
              "      }\n" .
              "      delete ( formData[index]);\n" .
              "    }\n" .
              "  }\n" .
              "  $('#config_form').data ( 'formData', formData);\n" .
              "});\n" .
              "VoIP.rest ( '/config', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#config_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Configurations") . "', text: '" . __ ( "Error retrieving configurations!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Add permissions configuration framework hooks, with the relative function.
 */
framework_add_hook ( "permissions_page", "permissions_page");
framework_add_path ( "/config/permissions", "permissions_page");

/**
 * Function to create the permissions configuration page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $output Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function permissions_page ( $output, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Configurations"));
  sys_set_subtitle ( __ ( "system permissions"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Configurations"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "jquery-labelauty", "src" => "/vendors/jquery-labelauty/source/jquery-labelauty.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-labelauty", "src" => "/vendors/jquery-labelauty/source/jquery-labelauty.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"permissions_form\">\n";

  // Add location levels headers
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <span class=\"col-xs-2\"></span>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 column-label center\">" . __ ( "Local") . "</div>\n";
  $output .= "      <div class=\"col-xs-4 column-label center\">" . __ ( "Interstate") . "</div>\n";
  $output .= "      <div class=\"col-xs-4 column-label center\">" . __ ( "International") . "</div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add landline call permissions
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Landline") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Landline_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Landline_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Landline_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Landline_Interstate\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Landline_Interstate\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Landline_Interstate\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Landline_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Landline_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Landline_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add mobile call permissions
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Mobile") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Mobile_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Mobile_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Mobile_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Mobile_Interstate\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Mobile_Interstate\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Mobile_Interstate\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Mobile_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Mobile_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Mobile_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add marine call permissions
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Marine") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Marine_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Marine_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Marine_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Marine_Interstate\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Marine_Interstate\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Marine_Interstate\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Marine_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Marine_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Marine_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add toll free call permissions
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Toll free") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Tollfree_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Tollfree_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Tollfree_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4\">\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Tollfree_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Tollfree_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Tollfree_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add premium rate numbers call permissions
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Premium rate numbers") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"PRN_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"PRN_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"PRN_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4\">\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"PRN_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"PRN_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"PRN_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add satellite call permissions
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label class=\"control-label col-xs-2\">" . __ ( "Satellite") . "</label>\n";
  $output .= "    <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Satellite_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Satellite_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Satellite_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4\">\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"col-xs-4 center\">\n";
  $output .= "        <input type=\"radio\" name=\"Satellite_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Satellite_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "        <input type=\"radio\" name=\"Satellite_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-md-6 col-sm-6 col-xs-12 col-md-offset-3\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary submit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</form>\n";

  /**
   * Add configuration JavaScript code
   */
  sys_addjs ( "$('#permissions_form input[type=\"radio\"]').labelauty ();\n" .
              "$('#permissions_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#permissions_form input[name=\"Landline_Local\"][value=\"' + data.Landline.Local + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Landline_Interstate\"][value=\"' + data.Landline.Interstate + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Landline_International\"][value=\"' + data.Landline.International + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Mobile_Local\"][value=\"' + data.Mobile.Local + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Mobile_Interstate\"][value=\"' + data.Mobile.Interstate + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Mobile_International\"][value=\"' + data.Mobile.International + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Marine_Local\"][value=\"' + data.Marine.Local + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Marine_Interstate\"][value=\"' + data.Marine.Interstate + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Marine_International\"][value=\"' + data.Marine.International + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Tollfree_Local\"][value=\"' + data.Tollfree.Local + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Tollfree_International\"][value=\"' + data.Tollfree.International + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"PRN_Local\"][value=\"' + data.PRN.Local + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"PRN_International\"][value=\"' + data.PRN.International + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Satellite_Local\"][value=\"' + data.Satellite.Local + '\"]').prop ( 'checked', 'checked');\n" .
              "  $('#permissions_form input[name=\"Satellite_International\"][value=\"' + data.Satellite.International + '\"]').prop ( 'checked', 'checked');\n" .
              "});\n" .
              "VoIP.rest ( '/config/permissions', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#permissions_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Permissions configuration") . "', text: '" . __ ( "Error retrieving permissions!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#permissions_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/config/permissions',\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.submit'),\n" .
              "    title: '" . __ ( "System permissions") . "',\n" .
              "    fail: '" . __ ( "Error changing permissions!") . "',\n" .
              "    success: '" . __ ( "Permissions changed sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/', true);\n" .
              "               }\n" .
              "  }\n" .
              "}).on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#permissions_form').data ( 'formData');\n" .
              "  var flatted = true;\n" .
              "  while ( flatted)\n" .
              "  {\n" .
              "    flatted = false;\n" .
              "    for ( let index in formData)\n" .
              "    {\n" .
              "      if ( index.indexOf ( '_') != -1)\n" .
              "      {\n" .
              "        var newindex = index.substr ( 0, index.indexOf ( '_'));\n" .
              "        var newsubindex = index.substr ( index.indexOf ( '_') + 1);\n" .
              "        if ( typeof ( formData[newindex]) === 'undefined')\n" .
              "        {\n" .
              "          formData[newindex] = new Object ();\n" .
              "        }\n" .
              "        formData[newindex][newsubindex] = formData[index];\n" .
              "        delete formData[index];\n" .
              "        flatted = true;\n" .
              "      }\n" .
              "    }\n" .
              "  }\n" .
              "  $('#permissions_form').data ( 'formData', formData);\n" .
              "});");

  return $output;
}

/**
 * Add DNS zone check hooks, with the relative function.
 */
framework_add_hook ( "dns_check_page", "dns_check_page");
framework_add_path ( "/config/dns", "dns_check_page");

/**
 * Function to generate the DNS zone VoIP records.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function dns_check_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Configurations"));
  sys_set_subtitle ( __ ( "VoIP DNS zone checker"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Configurations"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"dns_check_form\">\n";

  // Add DNS check zone field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"dns_zone\" class=\"control-label col-xs-2\">" . __ ( "Zone") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"zone\" class=\"form-control\" id=\"dns_zone\" placeholder=\"" . __ ( "Zone domain name") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary check ladda-button\" data-style=\"expand-left\">" . __ ( "Check") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  // Result modal
  $output .= "<div id=\"dns_check_result\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"dns_check_result\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "VoIP DNS zone check result") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <span id=\"dns_check_domain\"></span>:<br />\n";
  $output .= "        <span id=\"dns_check_naptr\"><i class=\"fa fa-times-circle\"></i></span><br />\n";
  $output .= "        <span id=\"dns_check_srv\"><i class=\"fa fa-check-circle\"></i></span><br />\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add check form JavaScript code
   */
  sys_addjs ( "$('#dns_zone').focus ();\n" .
              "$('#dns_check_form').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).alerts ( 'clearAll');\n" .
              "  if ( $('#dns_zone').val () == '')\n" .
              "  {\n" .
              "    $('#dns_zone').alerts ( 'add', { message: '" . __ ( "Zone must be informed!") . "'});\n" .
              "    return;\n" .
              "  }\n" .
              "  $('button.check').attr ( 'disabled', 'disabled');\n" .
              "  var l = Ladda.create ( $('button.check')[0]);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/config/dns/' + encodeURIComponent ( $('#dns_zone').val ()), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    var zone = $('#dns_zone').val ();\n" .
              "    if ( zone.substr ( -1) == '.')\n" .
              "    {\n" .
              "      zone = zone.substr ( 0, zone.length - 1);\n" .
              "    }\n" .
              "    var message = sprintf ( '" . __ ( "VoIP DNS result for domain %s") . "', '<strong>' + zone + '</strong>') + ':<br /><br />';\n" .
              "    message += '" . __ ( "Name Authority Pointer") . ": ';\n" .
              "    if ( data.NAPTR.length == 0)\n" .
              "    {\n" .
              "      message += '<i class=\"fa fa-times-circle text-danger\"></i> " . __ ( "FAIL!") . "';\n" .
              "    } else {\n" .
              "      var d2u = false;\n" .
              "      var d2t = false;\n" .
              "      for ( var index in data.NAPTR)\n" .
              "      {\n" .
              "        if ( data.NAPTR[index].Services == 'SIP+D2U')\n" .
              "        {\n" .
              "          d2u = true;\n" .
              "        }\n" .
              "        if ( data.NAPTR[index].Services == 'SIP+D2T')\n" .
              "        {\n" .
              "          d2t = true;\n" .
              "        }\n" .
              "      }\n" .
              "      if ( d2u && d2t)\n" .
              "      {\n" .
              "        message += '<i class=\"fa fa-check-circle text-success\"></i> " . __ ( "SUCCESS!") . "';\n" .
              "      } else {\n" .
              "        message += '<i class=\"fa fa-exclamation-circle text-warning\"></i> " . __ ( "INCONSISTENCY!") . "';\n" .
              "      }\n" .
              "    }\n" .
              "    message += '<br />" . __ ( "SIP over UDP service") . ": ';\n" .
              "    if ( data.SRVUDP.length == 0)\n" .
              "    {\n" .
              "      message += '<i class=\"fa fa-times-circle text-danger\"></i> " . __ ( "FAIL!") . "';\n" .
              "    } else {\n" .
              "      message += '<i class=\"fa fa-check-circle text-success\"></i> " . __ ( "SUCCESS!") . "';\n" .
              "    }\n" .
              "    message += '<br />" . __ ( "SIP over TCP service") . ": ';\n" .
              "    if ( data.SRVTCP.length == 0)\n" .
              "    {\n" .
              "      message += '<i class=\"fa fa-times-circle text-danger\"></i> " . __ ( "FAIL!") . "';\n" .
              "    } else {\n" .
              "      message += '<i class=\"fa fa-check-circle text-success\"></i> " . __ ( "SUCCESS!") . "';\n" .
              "    }\n" .
              "    message += '<br /><br />" . __ ( "Debug information") . ": <div id=\"dns_debug\" style=\"width: 100%; border: 1px solid #000000; overflow-x: auto\">NAPTR:<br />';\n" .
              "    if ( data.NAPTR.length == 0)\n" .
              "    {\n" .
              "      message += '<i>" . __ ( "No valid NAPTR data found!") . "</i><br />';\n" .
              "    } else {\n" .
              "      for ( var index in data.NAPTR)\n" .
              "      {\n" .
              "        message += zone + ' ' + data.NAPTR[index].TXT + '<br />';\n" .
              "      }\n" .
              "    }\n" .
              "    message += '<br />SIP UDP SRV:<br />';\n" .
              "    if ( data.SRVUDP.length == 0)\n" .
              "    {\n" .
              "      message += '<i>" . __ ( "No valid SIP over UDP server data found!") . "</i><br />';\n" .
              "    } else {\n" .
              "      for ( var index in data.SRVUDP)\n" .
              "      {\n" .
              "        message += data.SRVUDP[index].TXT + '<br />';\n" .
              "      }\n" .
              "    }\n" .
              "    message += '<br />SIP TCP SRV:<br />';\n" .
              "    if ( data.SRVTCP.length == 0)\n" .
              "    {\n" .
              "      message += '<i>" . __ ( "No valid SIP over TCP server data found!") . "</i><br />';\n" .
              "    } else {\n" .
              "      for ( var index in data.SRVTCP)\n" .
              "      {\n" .
              "        message += data.SRVTCP[index].TXT + '<br />';\n" .
              "      }\n" .
              "    }\n" .
              "    message += '</div>';\n" .
              "    $('#dns_check_result .modal-body').html ( message);\n" .
              "    $('#dns_check_result').modal ( 'show');\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "VoIP DNS zone checker") . "', text: '" . __ ( "Error checking DNS zone!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});");

  return $output;
}

/**
 * Add plugin configuration framework hooks, with the relative function.
 */
framework_add_hook ( "plugins_page", "plugins_page");
framework_add_path ( "/config/plugins", "plugins_page");

/**
 * Function to create the plugin configuration page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $output Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function plugins_page ( $output, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Configurations"));
  sys_set_subtitle ( __ ( "plugins"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Configurations"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/dist/themes/base/jquery-ui.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "jquery-fileupload", "src" => "/vendors/jQuery-File-Upload/css/jquery.fileupload.css", "dep" => array ( "bootstrap")));

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
  sys_addjs ( array ( "name" => "jquery-ui", "src" => "/vendors/jquery-ui/dist/jquery-ui.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "JavaScript-Templates", "src" => "/vendors/JavaScript-Templates/js/tmpl.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-fileupload", "src" => "/vendors/jQuery-File-Upload/js/jquery.fileupload.js", "dep" => array ( "JavaScript-Templates", "jquery-ui")));
  sys_addjs ( array ( "name" => "jquery-iframe-transport", "src" => "/vendors/jQuery-File-Upload/js/jquery.iframe-transport.js", "dep" => array ( "jquery-fileupload")));
  sys_addjs ( array ( "name" => "jquery-fileupload-process", "src" => "/vendors/jQuery-File-Upload/js/jquery.fileupload-process.js", "dep" => array ( "jquery-fileupload")));
  sys_addjs ( array ( "name" => "jquery-fileupload-validate", "src" => "/vendors/jQuery-File-Upload/js/jquery.fileupload-validate.js", "dep" => array ( "jquery-fileupload", "jquery-fileupload-process")));
  sys_addjs ( array ( "name" => "jquery-fileupload-ui", "src" => "/vendors/jQuery-File-Upload/js/jquery.fileupload-ui.js", "dep" => array ( "jquery-fileupload", "jquery-fileupload-process")));

  /**
   * Plugin search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Name") . "</th>\n";
  $output .= "      <th>" . __ ( "Version") . "</th>\n";
  $output .= "      <th>" . __ ( "Description") . "</th>\n";
  $output .= "      <th>" . __ ( "Status") . "</th>\n";
  $output .= "      <th>" . __ ( "Actions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add plugin addition modal code
   */
  $output .= "<div id=\"plugin_add\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"plugin_add\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Plugin addition") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"plugin_add_form\" method=\"POST\" enctype=\"multipart/form-data\">\n";

  /**
   * Add import field
   */
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <label for=\"plugin_add_file\" class=\"control-label col-xs-2\">" . __ ( "Import") . "</label>\n";
  $output .= "          <div class=\"col-xs-6 fileupload-buttonbar\">\n";
  $output .= "            <span class=\"btn btn-success fileinput-button\"><i class=\"fa fa-plus\"></i> <span>" . __ ( "Add...") . "</span><input type=\"file\" name=\"plugin\" multiple></span>\n";
  $output .= "            <span class=\"fileupload-process\"></span>\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 fileupload-progress fade\">\n";
  $output .= "            <div class=\"progress progress-striped active\" role=\"progressbar\" aria-valuemin=\"0\" aria-valuemax=\"100\">\n";
  $output .= "              <div class=\"progress-bar progress-bar-success\" style=\"width:0%;\"></div>\n";
  $output .= "            </div>\n";
  $output .= "            <div class=\"progress-extended\">&nbsp;</div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";

  /**
   * Add file upload list table
   */
  $output .= "        <div class=\"form-group\">\n";
  $output .= "          <div class=\"col-xs-2\"></div>\n";
  $output .= "          <div class=\"col-xs-10\"><table role=\"presentation\" class=\"table table-striped\"><tbody class=\"files\"></tbody></table></div>\n";
  $output .= "        </div>\n";

  /**
   * Finish modal
   */
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn btn-primary\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * jQuery Upload Plugin template
   */
  $output .= "<script id=\"template-upload\" type=\"text/x-tmpl\">\n";
  $output .= "{% for (var i = 0, file; file = o.files[i]; i++) { %}\n";
  $output .= "  <tr class=\"template-upload fade\">\n";
  $output .= "    <td>\n";
  $output .= "      <span class=\"preview\"></span>\n";
  $output .= "    </td>\n";
  $output .= "    <td>\n";
  $output .= "      <p class=\"name\">{%=file.name%}</p>\n";
  $output .= "      <strong class=\"error text-danger\"></strong>\n";
  $output .= "    </td>\n";
  $output .= "    <td>\n";
  $output .= "      <p class=\"size\">" . __ ( "Processing...") . "</p>\n";
  $output .= "      <div class=\"progress progress-striped active\" role=\"progressbar\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"0\"><div class=\"progress-bar progress-bar-success\" style=\"width:0%;\"></div></div>\n";
  $output .= "    </td>\n";
  $output .= "    <td>\n";
  $output .= "      {% if ( ! i && ! o.options.autoUpload) { %}\n";
  $output .= "        <button class=\"btn btn-primary start\" disabled><i class=\"fa fa-upload\"></i> <span>" . __ ( "Upload") . "</span></button>\n";
  $output .= "      {% } %}\n";
  $output .= "      {% if ( ! i) { %}\n";
  $output .= "        <button class=\"btn btn-warning cancel\"><i class=\"fa fa-ban\"></i> <span>" . __ ( "Cancel") . "</span></button>\n";
  $output .= "      {% } %}\n";
  $output .= "    </td>\n";
  $output .= "  </tr>\n";
  $output .= "{% } %}\n";
  $output .= "</script>\n";

  /**
   * Add plugin remove modal code
   */
  $output .= "<div id=\"plugin_remove\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"plugin_remove\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Plugin removal") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the plugin %s (%s)?"), "<span id=\"plugin_remove_name\"></span>", "<span id=\"plugin_remove_description\"></span>") . "</p></div>\n";
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
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 5 ]},\n" .
              "                { searchable: false, targets: [ 0, 5 ]},\n" .
              "                { data: 'links', render: function ( data, type, full) { return '<span class=\"btn-group\"><button class=\"btn btn-xs btn-toggle btn-' + ( full[4] == 'on' ? 'warning' : 'info') + '\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"' + ( full[4] == 'on' ? '" . __ ( "Disable") . "' : '" . __ ( "Enable") . "') + '\" data-status=\"' + full[4] + '\" data-dirname=\"' + full[0] + '\" role=\"button\" title=\"\"><i class=\"fa fa-toggle-' + ( full[4] == 'on' ? 'off' : 'on') + '\"></i></button><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-dirname=\"' + full[0] + '\" data-dirname=\"' + full[0] + '\" data-name=\"' + full[1] + '\" data-description=\"' + full[3] + '\"' + ( full[4] == 'on' ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, targets: [ 5 ]},\n" .
              "                { 'render': function ( data, type, full) { return '<span class=\"label label-' + ( full[4] == 'on' ? 'success' : 'danger') + '\" title=\"\">' + ( full[4] == 'on' ? '" . __ ( "Enabled") . "' : '" . __ ( "Disabled") . "') + '</span>'; }, 'targets': [ 4 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'none'},\n" .
              "             { width: '20%', class: 'export all'},\n" .
              "             { width: '5%', class: 'export all'},\n" .
              "             { width: '60%', class: 'export min-tablet-l'},\n" .
              "             { width: '10%', class: 'export all'},\n" .
              "             { width: '5%', class: 'all'}\n" .
              "           ],\n" .
              "  order: [[ 0, 'asc' ]]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/config/plugin', fields: 'Dirname,Name,Version,Description,Status'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<button class=\"btn btn-success\" href=\"/config/plugin/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "Add") . "</button>').css ( 'margin-right', '5px').find ( 'button').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#plugin_add').modal ( 'show');\n" .
              "});\n" .
              "$('#plugin_add').on ( 'show.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#plugin_add tr.template-upload').remove ();\n" .
              "});\n" .
              "$('#plugin_add_form').fileupload (\n" .
              "{\n" .
              "  url: '/api/config/plugin',\n" .
              "  dataType: 'json',\n" .
              "  autoUpload: true,\n" .
              "  acceptFileTypes: /(\.|\/)(zip)$/i,\n" .
              "  maxFileSize: 16777216,\n" .
              "  downloadTemplateId: '',\n" .
              "  messages: {\n" .
              "    maxNumberOfFiles: '" . __ ( "Maximum number of files exceeded.") . "',\n" .
              "    acceptFileTypes: '" . __ ( "File type not allowed.") . "',\n" .
              "    maxFileSize: '" . __ ( "File is too large.") . "',\n" .
              "    minFileSize: '" . __ ( "File is too small.") . "',\n" .
              "    unknownError: '" . __ ( "Unknown error.") . "',\n" .
              "    uploadedBytes: '" . __ ( "Uploaded bytes exceed file size.") . "'\n" .
              "  },\n" .
              "  headers: {\n" .
              "    'X-INFramework': 'api',\n" .
              "    'X-HTTP-Method-Override': 'POST',\n" .
              "    'Accept': 'application/json'\n" .
              "  }\n" .
              "}).on ( 'fileuploadfail', function ( e, data)\n" .
              "{\n" .
              "  $(data.context).find ( 'strong.error').html ( data.jqXHR.responseJSON.message);\n" .
              "  $(data.context).find ( 'div.progress-bar').removeClass ( 'progress-bar-success').addClass ( 'progress-bar-warning');\n" .
              "  $(data.context).find ( 'button.cancel').attr ( 'disabled', 'disabled');\n" .
              "  $(data.context).find ( 'button.start').removeClass ( 'start');\n" .
              "  return false;\n" .
              "}).on ( 'fileuploaddone', function ( e, data)\n" .
              "{\n" .
              "  $(data.context).find ( 'div.progress-bar').fadeOut ();\n" .
              "  if ( data.result.result == false)\n" .
              "  {\n" .
              "    $(data.context).find ( 'strong.error').html ( data.result.message);\n" .
              "    $(data.context).find ( 'div.progress-bar').removeClass ( 'progress-bar-success').addClass ( 'progress-bar-warning');\n" .
              "    $(data.context).find ( 'button.cancel').attr ( 'disabled', 'disabled');\n" .
              "    $(data.context).find ( 'button.start').removeClass ( 'start');\n" .
              "  } else {\n" .
              "    var newData = VoIP.APIsearch ( { path: '/config/plugin', fields: 'Dirname,Name,Version,Description,Status'});\n" .
              "    $('#datatables').data ( 'dt').clear ();\n" .
              "    $('#datatables').data ( 'dt').rows.add ( newData);\n" .
              "    $('#datatables').data ( 'dt').draw ();\n" .
              "    $(data.context).find ( 'strong.error').html ( '<span style=\"color: green\">' + data.result.message + '</span>');\n" .
              "    $(data.context).find ( 'button.cancel').removeClass ( 'cancel btn-warning').addClass ( 'remove btn-info').html ( '<i class=\"fa fa-trash\"></i> <span>" . __ ( "Remove") . "</span>');\n" .
              "  }\n" .
              "  return false;\n" .
              "});\n" .
              "$('#plugin_add tbody.files').on ( 'click', 'button.remove', function ( e)\n" .
              "{\n" .
              "  $(this).closest ( 'tr').remove ();\n" .
              "});\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#plugin_remove_name').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#plugin_remove_name').data ( 'dirname', $(this).data ( 'dirname'));\n" .
              "  $('#plugin_remove button.del').prop ( 'disabled', false);\n" .
              "  $('#plugin_remove_name').html ( $(this).data ( 'name'));\n" .
              "  $('#plugin_remove_description').html ( $(this).data ( 'description'));\n" .
              "  $('#plugin_remove').modal ( 'show');\n" .
              "}).on ( 'click', 'button.btn-toggle', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/config/plugin/' + encodeURIComponent ( $(this).data ( 'dirname')) + '/toggle', 'PATCH').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Plugin toggle") . "', text: '" . __ ( "Plugin toggled sucessfully!") . "', type: 'success'});\n" .
              "    var newData = VoIP.APIsearch ( { path: '/config/plugin', fields: 'Dirname,Name,Version,Description,Status'});\n" .
              "    $('#datatables').data ( 'dt').clear ();\n" .
              "    $('#datatables').data ( 'dt').rows.add ( newData);\n" .
              "    $('#datatables').data ( 'dt').draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Plugin toggle") . "', text: '" . __ ( "Error toggling plugin!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n" .
              "$('#plugin_remove button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/config/plugin/' + encodeURIComponent ( $('#plugin_remove_name').data ( 'dirname')), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#plugin_remove').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Plugin removal") . "', text: '" . __ ( "Plugin removed sucessfully!") . "', type: 'success'});\n" .
              "    $('#plugin_remove_name').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Plugin removal") . "', text: '" . __ ( "Error removing plugin!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}
?>
