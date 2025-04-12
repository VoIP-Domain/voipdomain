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
 * VoIP Domain Cisco equipments module filters. This module add the filter
 * calls related to supported Cisco equipments.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Cisco
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add Cisco equipment's filters
 */
framework_add_filter ( "equipments_configure_subpages", "equipments_cisco_configure_subpage");

/**
 * Function to generate the equipment phone view subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_cisco_configure_subpage ( $buffer, $parameters)
{
  /**
   * Add configure form JavaScript code
   */
  $buffer["js"]["init"][] = "var tmp = $('#equipment_configure_audio_codecs').data ( 'crlcu.multiselect');\n" .
                            "tmp.callbacks.beforeMoveToLeft = function ( left, right, option)\n" .
                            "{\n" .
                            "  var codecs = new Array;\n" .
                            "  $(option).each ( function ()\n" .
                            "  {\n" .
                            "    codecs.push ( $(this).val ());\n" .
                            "  });\n" .
                            "  if ( $.inArray ( 'ULAW', codecs) != -1 || $.inArray ( 'ALAW', codecs) != -1 || $.inArray ( 'G729', codecs) != -1)\n" .
                            "  {\n" .
                            "    new PNotify ( { title: '" . __ ( "Equipment configuration") . "', text: '" . __ ( "This equipment does not allow disabling this codec!") . "', type: 'error'});\n" .
                            "    return false;\n" .
                            "  }\n" .
                            "  return true;\n" .
                            "};\n" .
                            "$('#equipment_configure_audio_codecs').data ( 'crlcu.multiselect', tmp);\n" .
                            "$('#equipment_configure_form').on ( 'formFilter', function ()\n" .
                            "{\n" .
                            "  var formData = $('#equipment_configure_form').data ( 'formData');\n" .
                            "  if ( $('#equipment_configure_uid').val () == '3905')\n" .
                            "  {\n" .
                            "    formData.AudioCodecs = new Array ();\n" .
                            "    var tmp = $('#equipment_configure_audio_codecs_to option');\n" .
                            "    for ( i = 0; i < tmp.length; i++)\n" .
                            "    {\n" .
                            "      formData.AudioCodecs.push ( $(tmp[i]).val ());\n" .
                            "    }\n" .
                            "  }\n" .
                            "  delete ( formData.T20P_Username);\n" .
                            "  delete ( formData.T20P_Userpass);\n" .
                            "  delete ( formData.T20P_Adminname);\n" .
                            "  delete ( formData.T20P_Adminpass);\n" .
                            "  $('#equipment_configure_form').data ( 'formData', formData);\n" .
                            "});\n";

  return $buffer;
}
?>
