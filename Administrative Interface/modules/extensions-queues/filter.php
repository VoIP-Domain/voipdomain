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
 * VoIP Domain extensions queues module filters. This module add the filter calls
 * related to extensions queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Queues
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights queued.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add extensions queue's filters
 */
framework_add_filter ( "objects_types", "queues_object");
framework_add_filter ( "extensions_add_subpages", "extensions_queues_add_subpage");
framework_add_filter ( "extensions_clone_subpages", "extensions_queues_clone_subpage");
framework_add_filter ( "extensions_view_subpages", "extensions_queues_view_subpage");
framework_add_filter ( "extensions_edit_subpages", "extensions_queues_edit_subpage");

/**
 * Function to add extensions queue interface object information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function queues_object ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "object" => "extension_queue", "type" => "human", "path" => "/extensions", "icon" => "code-branch", "label" => "default", "text" => array ( "singular" => __ ( "Queue"), "plural" => __ ( "Queues")))));
}

/**
 * Function to generate the extension queues add subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_queues_add_subpage ( $buffer, $parameters)
{
  /**
   * Add queue panel
   */
  $output = "";

  // Add queue field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_queue\" class=\"control-label col-xs-2\">" . __ ( "Queue") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Queue\" id=\"extension_add_queue\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension queue") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["queue-basic"] = array ( "type" => "queue", "label" => __ ( "Queue"), "html" => $output);

  /**
   * Add add form JavaScript code
   */
  $buffer["js"]["init"][] = "$('#extension_add_queue').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/queues', fields: 'ID,Description,StrategyText', formatText: '%Description% (%StrategyText%)'})\n" .
                            "});\n";

  return $buffer;
}

/**
 * Function to generate the extension queue clone subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_queues_clone_subpage ( $buffer, $parameters)
{
  /**
   * Add clone form JavaScript code
   */
  $buffer["js"]["onshow"]["queue"] = "        $('#extension_add_queue').val ( data.Queue.ID).trigger ( 'change');\n";

  return $buffer;
}

/**
 * Function to generate the extension queue view subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_queues_view_subpage ( $buffer, $parameters)
{
  /**
   * Add queue panel
   */
  $output = "";

  // Add queue extensions field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_queue\" class=\"control-label col-xs-2\">" . __ ( "Queue") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Queue\" id=\"extension_view_queue\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension queue") . "\" disabled=\"disabled\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["queue-basic"] = array ( "type" => "queue", "label" => __ ( "Queue"), "html" => $output);

  /**
   * Add add form JavaScript code
   */
  $buffer["js"]["onshow"]["queue"] = "      $('#extension_view_queue').append ( $('<option>', { value: data.Queue.ID, text: data.Queue.Description + ' (' + data.Queue.StrategyText + ')'}));\n" .
                                     "      $('#extension_view_queue').val ( data.Queue.ID).trigger ( 'change');\n";
  $buffer["js"]["init"][] = "$('#extension_view_queue').select2 ();\n";

  return $buffer;
}

/**
 * Function to generate the extension queue edit subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_queues_edit_subpage ( $buffer, $parameters)
{
  /**
   * Add queue panel
   */
  $output = "";

  // Add queue extensions field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"extension_edit_queue\" class=\"control-label col-xs-2\">" . __ ( "Queue") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <select name=\"Queue\" id=\"extension_edit_queue\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension queue") . "\"><option value=\"\"></option></select>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $buffer["tabs"]["queue-basic"] = array ( "type" => "queue", "label" => __ ( "Queue"), "html" => $output);

  /**
   * Add edit form JavaScript code
   */
  $buffer["js"]["onshow"]["queue"] = "      $('#extension_edit_queue').val ( data.Queue.ID).trigger ( 'change');\n";
  $buffer["js"]["init"][] = "$('#extension_edit_queue').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/queues', fields: 'ID,Description,StrategyText', formatText: '%Description% (%StrategyText%)'})\n" .
                            "});\n";

  return $buffer;
}
?>
