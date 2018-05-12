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
 * VoIP Domain Grandstream equipments module filters. This module add the filter
 * calls related to supported Grandstream equipments.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Grandstream
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add Grandstream equipment's filters
 */
framework_add_filter ( "equipments_view_subpages", "equipments_grandstream_view_subpage");
framework_add_filter ( "equipments_configure_subpages", "equipments_grandstream_configure_subpage");

/**
 * Function to generate the equipment phone view subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_grandstream_view_subpage ( $buffer, $parameters)
{
  /**
   * Add extra fields for administrator and user name and password
   */
  $extrausersmodels = array ( "gxp1160", "gxp1165", "gxp1610", "gxp1615", "gxp1620", "gxp1625", "gxp1628", "gxp1630");
  foreach ( $extrausersmodels as $model)
  {
    // Model options:
    $output = "";

    // Add equipment normal user password field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_view_" . $model . "_userpass\" class=\"control-label col-xs-2\">" . __ ( "User password") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <div class=\"input-group\">\n";
    $output .= "            <input type=\"password\" name=\"" . strtoupper ( $model) . "_Userpass\" id=\"equipment_view_" . $model . "_userpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of non-privileged user") . "\" disabled=\"disabled\" />\n";
    $output .= "            <div class=\"input-group-btn\">\n";
    $output .= "              <button class=\"btn btn-default btn-showpass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show password") . "\"><i class=\"fa fa-eye\"></i></button>\n";
    $output .= "              <button class=\"btn btn-default btn-copypass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Copy password") . "\"><i class=\"fa fa-copy\"></i></button>\n";
    $output .= "            </div>\n";
    $output .= "          </div>\n";
    $output .= "        </div>\n";
    $output .= "      </div>\n";

    // Add equipment admin password field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_view_" . $model . "_adminpass\" class=\"control-label col-xs-2\">" . __ ( "Admin password") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <div class=\"input-group\">\n";
    $output .= "            <input type=\"password\" name=\"" . strtoupper ( $model) . "_Adminpass\" id=\"equipment_view_" . $model . "_adminpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of privileged administrator") . "\" disabled=\"disabled\" />\n";
    $output .= "            <div class=\"input-group-btn\">\n";
    $output .= "              <button class=\"btn btn-default btn-showpass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show password") . "\"><i class=\"fa fa-eye\"></i></button>\n";
    $output .= "              <button class=\"btn btn-default btn-copypass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Copy password") . "\"><i class=\"fa fa-copy\"></i></button>\n";
    $output .= "            </div>\n";
    $output .= "          </div>\n";
    $output .= "        </div>\n";
    $output .= "      </div>\n";
    $buffer["tabs"][$model] = array ( "type" => $model, "label" => __ ( "Extras"), "html" => $output);

    /**
     * Add configure form JavaScript code
     */
    $buffer["js"]["onshow"][$model] = "      $('#equipment_view_" . $model . "_userpass').val ( data.ExtraSettings.UserPassword);\n" .
                                      "      $('#equipment_view_" . $model . "_adminpass').val ( data.ExtraSettings.AdminPassword);\n";
    $buffer["js"]["init"][] = "$('#equipment_view_tab_" . $model . " .btn-showpass').on ( 'mousedown', function ( event)\n" .
                              "{\n" .
                              "  event && event.preventDefault ();\n" .
                              "  $(this).parent ().parent ().find ( 'input').attr ( 'type', 'text');\n" .
                              "}).on ( 'mouseup mouseout', function ( event)\n" .
                              "{\n" .
                              "  event && event.preventDefault ();\n" .
                              "  $(this).parent ().parent ().find ( 'input').attr ( 'type', 'password');\n" .
                              "});\n" .
                              "$('#equipment_view_tab_" . $model . " .btn-copypass').on ( 'click', function ( event)\n" .
                              "{\n" .
                              "  event && event.preventDefault ();\n" .
                              "  var target = $(this).parent ().parent ().find ( 'input');\n" .
                              "  $(target).removeAttr ( 'disabled').attr ( 'readonly', 'readonly').attr ( 'type', 'text');\n" .
                              "  $(target).select ();\n" .
                              "  document.execCommand ( 'copy');\n" .
                              "  $(this).focus ();\n" .
                              "  $(target).removeAttr ( 'readonly').attr ( 'disabled', 'disabled').attr ( 'type', 'password');\n" .
                              "  new PNotify ( { title: '" . __ ( "Equipment view") . "', text: '" . __ ( "Password copied to clipboard!") . "', type: 'success'});\n" .
                              "});\n";
  }

  return $buffer;
}

/**
 * Function to generate the equipment phone configure subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_grandstream_configure_subpage ( $buffer, $parameters)
{
  /**
   * Add extra fields for administrator and user name and password
   */
  $output = "";
  $extrausersmodels = array ( "gxp1160", "gxp1165", "gxp1610", "gxp1615", "gxp1620", "gxp1625", "gxp1628", "gxp1630");
  foreach ( $extrausersmodels as $model)
  {
    // Model options:
    $output = "";

    // Add equipment normal user password field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_configure_" . $model . "_userpass\" class=\"control-label col-xs-2\">" . __ ( "User password") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <div class=\"input-group\">\n";
    $output .= "            <input type=\"text\" name=\"" . strtoupper ( $model) . "_Userpass\" id=\"equipment_configure_" . $model . "_userpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of non-privileged user") . "\" />\n";
    $output .= "            <div class=\"input-group-btn\">\n";
    $output .= "              <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
    $output .= "            </div>\n";
    $output .= "          </div>\n";
    $output .= "        </div>\n";
    $output .= "      </div>\n";

    // Add equipment admin password field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_configure_" . $model . "_adminpass\" class=\"control-label col-xs-2\">" . __ ( "Admin password") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <div class=\"input-group\">\n";
    $output .= "            <input type=\"text\" name=\"" . strtoupper ( $model) . "_Adminpass\" id=\"equipment_configure_" . $model . "_adminpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of privileged administrator") . "\" />\n";
    $output .= "            <div class=\"input-group-btn\">\n";
    $output .= "              <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
    $output .= "            </div>\n";
    $output .= "          </div>\n";
    $output .= "        </div>\n";
    $output .= "      </div>\n";
    $buffer["tabs"][$model] = array ( "type" => $model, "label" => __ ( "Extras"), "html" => $output);

    /**
     * Add configure form JavaScript code
     */
    $buffer["js"]["onshow"][$model] = "      $('#equipment_configure_" . $model . "_userpass').val ( data.ExtraSettings.UserPassword);\n" .
                                      "      $('#equipment_configure_" . $model . "_adminpass').val ( data.ExtraSettings.AdminPassword);\n";
    $buffer["js"]["init"][] = "$('#equipment_configure_tab_" . $model . " .btn-random').on ( 'click', function ( event)\n" .
                              "{\n" .
                              "  event && event.preventDefault ();\n" .
                              "  let chars = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ';\n" .
                              "  var password = '';\n" .
                              "  for ( var i = 0; i <= 12; i++)\n" .
                              "  {\n" .
                              "    var randomNumber = Math.floor ( Math.random () * chars.length);\n" .
                              "    password += chars.substring ( randomNumber, randomNumber + 1);\n" .
                              "  }\n" .
                              "  $(this).parent ().parent ().find ( 'input').val ( password);\n" .
                              "});\n" .
                              "$('#equipment_configure_form').on ( 'formFilter', function ()\n" .
                              "{\n" .
                              "  var formData = $('#equipment_configure_form').data ( 'formData');\n" .
                              "  if ( $('#equipment_configure_uid').val () == '" . $model . "')\n" .
                              "  {\n" .
                              "    formData.ExtraSettings = new Object ();\n" .
                              "    formData.ExtraSettings.UserPassword = formData['" . strtoupper ( $model) . "_Userpass'];\n" .
                              "    formData.ExtraSettings.AdminPassword = formData['" . strtoupper ( $model) . "_Adminpass'];\n" .
                              "  }\n" .
                              "  delete ( formData['" . strtoupper ( $model) . "_Userpass']);\n" .
                              "  delete ( formData['" . strtoupper ( $model) . "_Adminpass']);\n" .
                              "  $('#equipment_configure_form').data ( 'formData', formData);\n" .
                              "});\n";
  }

  return $buffer;
}
?>
