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
 * VoIP Domain extensions group hunts module filters. This module add the filter
 * calls related to extensions group hunts.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Hunts
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add extensions hunt's filters
 */
framework_add_filter ( "objects_types", "hunts_object");
framework_add_filter ( "extensions_add_subpages", "extensions_hunts_add_subpage");
framework_add_filter ( "extensions_clone_subpages", "extensions_hunts_clone_subpage");
framework_add_filter ( "extensions_view_subpages", "extensions_hunts_view_subpage");
framework_add_filter ( "extensions_edit_subpages", "extensions_hunts_edit_subpage");

/**
 * Function to add hunt interface object information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function hunts_object ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "object" => "extension_hunt", "path" => "/extensions", "icon" => "crosshairs", "label" => "primary", "text" => array ( "singular" => __ ( "Line hunting"), "plural" => __ ( "Lines hunting")))));
}

/**
 * Function to generate the extension group hunt add subpage code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_hunts_add_subpage ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add hunt panel
   */
  $output = "";

  // Add hunt extensions field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_hunts\" class=\"control-label col-xs-2\">" . __ ( "Extensions") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Hunts\" id=\"extension_add_hunts\" class=\"form-control\" data-placeholder=\"" . __ ( "Hunt extensions") . "\" multiple=\"multiple\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["hunt-basic"] = array ( "type" => "hunt", "label" => __ ( "Hunt"), "html" => $output);

  /**
   * Add add form JavaScript code
   */
  $buffer["js"]["init"][] = "$('#extension_add_hunts').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/extensions', fields: 'ID,Number,Description,Type', filter: function ( results, fields)\n" .
                            "  {\n" .
                            "    var hunttypes = " . json_encode ( $_in["hunts"]) . ";\n" .
                            "    var result = new Array ();\n" .
                            "    for ( var x = 0; x < results.length; x++)\n" .
                            "    {\n" .
                            "      if ( hunttypes.indexOf ( results[x].Type) != -1)\n" .
                            "      {\n" .
                            "        result.push ( { id: results[x].ID, text: results[x].Description + ' (' + results[x].Number + ')'});\n" .
                            "      }\n" .
                            "    }\n" .
                            "    return result;\n" .
                            "  }})\n" .
                            "});\n" .
                            "$('#extension_add_form').on ( 'formFilter', function ()\n" .
                            "{\n" .
                            "  var formData = $('#extension_add_form').data ( 'formData');\n" .
                            "  if ( typeof ( formData.Hunts) == 'string')\n" .
                            "  {\n" .
                            "    var tmp = formData.Hunts;\n" .
                            "    formData.Hunts = new Array ();\n" .
                            "    formData.Hunts.push ( tmp);\n" .
                            "  }\n" .
                            "  $('#extension_add_form').data ( 'formData', formData);\n" .
                            "});\n";

  return $buffer;
}

/**
 * Function to generate the extension group hunt clone subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_hunts_clone_subpage ( $buffer, $parameters)
{
  /**
   * Add clone form JavaScript code
   */
  $buffer["js"]["onshow"]["hunt"] = "        var ids = [];\n" .
                                    "        for ( var x in data.Hunts)\n" .
                                    "        {\n" .
                                    "          if ( ! data.Hunts.hasOwnProperty ( x))\n" .
                                    "          {\n" .
                                    "            continue;\n" .
                                    "          }\n" .
                                    "          ids.push ( data.Hunts[x].ID);\n" .
                                    "        }\n" .
                                    "        $('#extension_add_hunts').val ( ids).trigger ( 'change');\n";

  return $buffer;
}

/**
 * Function to generate the extension group hunt view subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_hunts_view_subpage ( $buffer, $parameters)
{
  /**
   * Add hunt panel
   */
  $output = "";

  // Add hunt extensions field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_hunts\" class=\"control-label col-xs-2\">" . __ ( "Extensions") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Hunts\" id=\"extension_view_hunts\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension hunts") . "\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["hunt-basic"] = array ( "type" => "hunt", "label" => __ ( "Hunt"), "html" => $output);

  /**
   * Add add form JavaScript code
   */
  $buffer["js"]["onshow"]["hunt"] = "      var ids = [];\n" .
                                    "      for ( var x in data.Hunts)\n" .
                                    "      {\n" .
                                    "        if ( ! data.Hunts.hasOwnProperty ( x))\n" .
                                    "        {\n" .
                                    "          continue;\n" .
                                    "        }\n" .
                                    "        ids.push ( data.Hunts[x].ID);\n" .
                                    "        $('#extension_view_hunts').append ( $('<option>', { value: data.Hunts[x].ID, text: data.Hunts[x].Description + ' (' + data.Hunts[x].Number + ')'}));\n" .
                                    "      }\n" .
                                    "      $('#extension_view_hunts').val ( ids).trigger ( 'change');\n";
  $buffer["js"]["init"][] = "$('#extension_view_hunts').select2 ();\n";

  return $buffer;
}

/**
 * Function to generate the extension group hunt edit subpage code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_hunts_edit_subpage ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add hunt panel
   */
  $output = "";

  // Add hunt extensions field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"extension_edit_hunts\" class=\"control-label col-xs-2\">" . __ ( "Extensions") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Hunts\" id=\"extension_edit_hunts\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension hunts") . "\" multiple=\"multiple\"></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $buffer["tabs"]["hunt-basic"] = array ( "type" => "hunt", "label" => __ ( "Hunt"), "html" => $output);

  /**
   * Add edit form JavaScript code
   */
  $buffer["js"]["onshow"]["hunt"] = "      var ids = [];\n" .
                                    "      for ( var x in data.Hunts)\n" .
                                    "      {\n" .
                                    "        if ( ! data.Hunts.hasOwnProperty ( x))\n" .
                                    "        {\n" .
                                    "          continue;\n" .
                                    "        }\n" .
                                    "        ids.push ( data.Hunts[x].ID);\n" .
                                    "      }\n" .
                                    "      $('#extension_edit_hunts').val ( ids).trigger ( 'change');\n";
  $buffer["js"]["init"][] = "$('#extension_edit_hunts').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/extensions', fields: 'ID,Number,Description,Type', filter: function ( results, fields)\n" .
                            "  {\n" .
                            "    var hunttypes = " . json_encode ( $_in["hunts"]) . ";\n" .
                            "    var result = new Array ();\n" .
                            "    for ( var x = 0; x < results.length; x++)\n" .
                            "    {\n" .
                            "      if ( hunttypes.indexOf ( results[x].Type) != -1)\n" .
                            "      {\n" .
                            "        result.push ( { id: results[x].ID, text: results[x].Description + ' (' + results[x].Number + ')'});\n" .
                            "      }\n" .
                            "    }\n" .
                            "    return result;\n" .
                            "  }})\n" .
                            "});\n" .
                            "$('#extension_edit_form').on ( 'formFilter', function ()\n" .
                            "{\n" .
                            "  var formData = $('#extension_edit_form').data ( 'formData');\n" .
                            "  if ( typeof ( formData.Hunts) == 'string')\n" .
                            "  {\n" .
                            "    var tmp = formData.Hunts;\n" .
                            "    formData.Hunts = new Array ();\n" .
                            "    formData.Hunts.push ( tmp);\n" .
                            "  }\n" .
                            "  $('#extension_edit_form').data ( 'formData', formData);\n" .
                            "});\n";


  return $buffer;
}
?>
