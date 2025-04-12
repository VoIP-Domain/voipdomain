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
 * VoIP Domain extensions IVRs module filters. This module add the filter calls
 * related to extensions IVRs.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions IVRs
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights ivrd.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add extensions ivr's filters
 */
framework_add_filter ( "objects_types", "ivrs_object");
framework_add_filter ( "ivr_inuse", "extension_ivrs_inuse");
framework_add_filter ( "extensions_add_subpages", "extensions_ivrs_add_subpage");
framework_add_filter ( "extensions_clone_subpages", "extensions_ivrs_clone_subpage");
framework_add_filter ( "extensions_view_subpages", "extensions_ivrs_view_subpage");
framework_add_filter ( "extensions_edit_subpages", "extensions_ivrs_edit_subpage");

/**
 * Function to add extensions ivr interface object information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function ivrs_object ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "object" => "extension_ivr", "type" => "human", "path" => "/extensions", "icon" => "list-ul", "label" => "success", "text" => array ( "singular" => __ ( "IVR"), "plural" => __ ( "IVRs")))));
}

/**
 * Function to check if extension is using specific IVR id.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function extension_ivrs_inuse ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Search extension using IVR entry
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionIVR` WHERE `IVR` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
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
 * Function to generate the extension IVRs add subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_ivrs_add_subpage ( $buffer, $parameters)
{
  /**
   * Add IVR panel
   */
  $output = "";

  // Add IVR field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_ivr\" class=\"control-label col-xs-2\">" . __ ( "IVR") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"IVR\" id=\"extension_add_ivr\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension IVR") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["ivr-basic"] = array ( "type" => "ivr", "label" => __ ( "IVR"), "html" => $output);

  /**
   * Add add form JavaScript code
   */
  $buffer["js"]["init"][] = "$('#extension_add_ivr').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/ivrs', fields: 'ID,Name,Description', formatText: '%Name% (%Description%)'})\n" .
                            "});\n";

  return $buffer;
}

/**
 * Function to generate the extension IVR clone subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_ivrs_clone_subpage ( $buffer, $parameters)
{
  /**
   * Add clone form JavaScript code
   */
  $buffer["js"]["onshow"]["ivr"] = "        $('#extension_add_ivr').val ( data.IVR.ID).trigger ( 'change');\n";

  return $buffer;
}

/**
 * Function to generate the extension IVR view subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_ivrs_view_subpage ( $buffer, $parameters)
{
  /**
   * Add IVR panel
   */
  $output = "";

  // Add IVR extensions field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_ivr\" class=\"control-label col-xs-2\">" . __ ( "IVR") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"IVR\" id=\"extension_view_ivr\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension IVR") . "\" disabled=\"disabled\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["ivr-basic"] = array ( "type" => "ivr", "label" => __ ( "IVR"), "html" => $output);

  /**
   * Add add form JavaScript code
   */
  $buffer["js"]["onshow"]["ivr"] = "      $('#extension_view_ivr').append ( $('<option>', { value: data.IVR.ID, text: data.IVR.Name + ' (' + data.IVR.Description + ')'}));\n" .
                                   "      $('#extension_view_ivr').val ( data.IVR.ID).trigger ( 'change');\n";
  $buffer["js"]["init"][] = "$('#extension_view_ivr').select2 ();\n";

  return $buffer;
}

/**
 * Function to generate the extension IVR edit subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_ivrs_edit_subpage ( $buffer, $parameters)
{
  /**
   * Add IVR panel
   */
  $output = "";

  // Add IVR extensions field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"extension_edit_ivr\" class=\"control-label col-xs-2\">" . __ ( "IVR") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"IVR\" id=\"extension_edit_ivr\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension IVR") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $buffer["tabs"]["ivr-basic"] = array ( "type" => "ivr", "label" => __ ( "IVR"), "html" => $output);

  /**
   * Add edit form JavaScript code
   */
  $buffer["js"]["onshow"]["ivr"] = "      $('#extension_edit_ivr').val ( data.IVR.ID).trigger ( 'change');\n";
  $buffer["js"]["init"][] = "$('#extension_edit_ivr').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/ivrs', fields: 'ID,Name,Description', formatText: '%Name% (%Description%)'})\n" .
                            "});\n";

  return $buffer;
}
?>
