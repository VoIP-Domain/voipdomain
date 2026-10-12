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
 * VoIP Domain extensions conferences module filters. This module add the filter
 * calls related to extensions conferences.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Conferences
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add extensions conference's filters
 */
framework_add_filter ( "objects_types", "conferences_object");
framework_add_filter ( "conference_inuse", "extension_conferences_inuse");
framework_add_filter ( "extensions_add_subpages", "extensions_conferences_add_subpage");
framework_add_filter ( "extensions_clone_subpages", "extensions_conferences_clone_subpage");
framework_add_filter ( "extensions_view_subpages", "extensions_conferences_view_subpage");
framework_add_filter ( "extensions_edit_subpages", "extensions_conferences_edit_subpage");

/**
 * Function to add extensions conference interface object information.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function conferences_object ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "object" => "extension_conference", "type" => "human", "path" => "/extensions", "icon" => "users", "label" => "default", "text" => array ( "singular" => __ ( "Conference"), "plural" => __ ( "Conferences")))));
}

/**
 * Function to check if extension is using specific conference id.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function extension_conferences_inuse ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Search extension using conference entry
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionConference` WHERE `Conference` = " . $parameters["ID"]))
  {
    database_query_error ( "extension_conferences_inuse");
  }

  /**
   * Format data
   */
  $data = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    $data[] = array ( "Type" => "Extension", "ID" => $extension["Extension"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to generate the extension conferences add subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_conferences_add_subpage ( $buffer, $parameters)
{
  /**
   * Add conference panel
   */
  $output = "";

  // Add conference field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_conference\" class=\"control-label col-xs-2\">" . __ ( "Conference") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Conference\" id=\"extension_add_conference\" class=\"form-control\" data-placeholder=\"" . __ ( "Select the conference room") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add admin field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_conference_admin\" class=\"control-label col-xs-2\">" . __ ( "Admin") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Admin\" id=\"extension_add_conference_admin\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["conference-basic"] = array ( "type" => "conference", "label" => __ ( "Conference"), "html" => $output);

  /**
   * Add add form JavaScript code
   */
  $buffer["js"]["init"][] = "$('#extension_add_conference').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/conferences', fields: 'ID,Name,Description', formatText: '%Name% (%Description%)'})\n" .
                            "});\n" .
                            "$('#extension_add_conference_admin').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n";

  return $buffer;
}

/**
 * Function to generate the extension conference clone subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_conferences_clone_subpage ( $buffer, $parameters)
{
  /**
   * Add clone form JavaScript code
   */
  $buffer["js"]["onshow"]["conference"] = "        $('#extension_add_conference').val ( data.Conference.ID).trigger ( 'change');\n" .
                                          "        $('#extension_add_conference_admin').bootstrapToggle ( data.Conference.Admin == 'Y' ? 'on' : 'off');\n";

  return $buffer;
}

/**
 * Function to generate the extension conference view subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_conferences_view_subpage ( $buffer, $parameters)
{
  /**
   * Add conference panel
   */
  $output = "";

  // Add conference field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_conference\" class=\"control-label col-xs-2\">" . __ ( "Conference") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Conference\" id=\"extension_view_conference\" class=\"form-control\" data-placeholder=\"" . __ ( "Select the conference room") . "\" disabled=\"disabled\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add admin field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_conference_admin\" class=\"control-label col-xs-2\">" . __ ( "Admin") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Admin\" id=\"extension_view_conference_admin\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["conference-basic"] = array ( "type" => "conference", "label" => __ ( "Conference"), "html" => $output);

  /**
   * Add view form JavaScript code
   */
  $buffer["js"]["onshow"]["conference"] = "      $('#extension_view_conference').append ( $('<option>', { value: data.Conference.ID, text: data.Conference.Name + ' (' + data.Conference.Description + ')'}));\n" .
                                          "      $('#extension_view_conference').val ( data.Conference.ID).trigger ( 'change');\n" .
                                          "      $('#extension_view_conference_admin').bootstrapToggle ( 'enable').bootstrapToggle ( data.Conference.Admin == 'Y' ? 'on' : 'off').bootstrapToggle ( 'disable');\n";
  $buffer["js"]["init"][] = "$('#extension_view_conference').select2 ();\n" .
                            "$('#extension_view_conference_admin').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n";

  return $buffer;
}

/**
 * Function to generate the extension conference edit subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_conferences_edit_subpage ( $buffer, $parameters)
{
  /**
   * Add conference panel
   */
  $output = "";

  // Add conference field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"extension_edit_conference\" class=\"control-label col-xs-2\">" . __ ( "Conference") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Conference\" id=\"extension_edit_conference\" class=\"form-control\" data-placeholder=\"" . __ ( "Select the conference room") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add admin field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"extension_edit_conference_admin\" class=\"control-label col-xs-2\">" . __ ( "Admin") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"checkbox\" name=\"Admin\" id=\"extension_edit_conference_admin\" class=\"form-control\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $buffer["tabs"]["conference-basic"] = array ( "type" => "conference", "label" => __ ( "Conference"), "html" => $output);

  /**
   * Add edit form JavaScript code
   */
  $buffer["js"]["onshow"]["conference"] = "      $('#extension_edit_conference').val ( data.Conference.ID).trigger ( 'change');\n" .
                                          "      $('#extension_edit_conference_admin').bootstrapToggle ( data.Conference.Admin == 'Y' ? 'on' : 'off');\n";
  $buffer["js"]["init"][] = "$('#extension_edit_conference').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/conferences', fields: 'ID,Name,Description', formatText: '%Name% (%Description%)'})\n" .
                            "});\n" .
                            "$('#extension_edit_conference_admin').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n";

  return $buffer;
}
?>
