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
 * VoIP Domain Snom equipments module filters. This module add the filter
 * calls related to supported Snom equipments.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Snom
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add Snom equipment's filters
 */
framework_add_filter ( "equipments_view_subpages", "equipments_snom_view_subpage");
framework_add_filter ( "equipments_configure_subpages", "equipments_snom_configure_subpage");

/**
 * Function to generate the equipment phone view subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_snom_view_subpage ( $buffer, $parameters)
{
  /**
   * Add extra fields for administrator and user name and password
   */
  $extrausersmodels = array ( "snom710", "snom720", "snom870");
  foreach ( $extrausersmodels as $model)
  {
    // Model options:
    $output = "";

    // Add equipment admin username field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_view_" . $model . "_username\" class=\"control-label col-xs-2\">" . __ ( "Admin username") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <input type=\"text\" name=\"" . strtoupper ( $model) . "_Username\" id=\"equipment_view_" . $model . "_username\" class=\"form-control\" placeholder=\"" . __ ( "Username of privileged administrator") . "\" disabled=\"disabled\" />\n";
    $output .= "        </div>\n";
    $output .= "      </div>\n";

    // Add equipment admin password field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_view_" . $model . "_password\" class=\"control-label col-xs-2\">" . __ ( "Admin password") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <div class=\"input-group\">\n";
    $output .= "            <input type=\"password\" name=\"" . strtoupper ( $model) . "_Password\" id=\"equipment_view_" . $model . "_password\" class=\"form-control\" placeholder=\"" . __ ( "Password of privileged administrator") . "\" disabled=\"disabled\" />\n";
    $output .= "            <div class=\"input-group-btn\">\n";
    $output .= "              <button class=\"btn btn-default btn-showpass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show password") . "\"><i class=\"fa fa-eye\"></i></button>\n";
    $output .= "              <button class=\"btn btn-default btn-copypass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Copy password") . "\"><i class=\"fa fa-copy\"></i></button>\n";
    $output .= "            </div>\n";
    $output .= "          </div>\n";
    $output .= "        </div>\n";
    $output .= "      </div>\n";

    // Add equipment admin mode password field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_view_" . $model . "_adminpassword\" class=\"control-label col-xs-2\">" . __ ( "Admin mode password") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <div class=\"input-group\">\n";
    $output .= "            <input type=\"adminpassword\" name=\"" . strtoupper ( $model) . "_AdminPassword\" id=\"equipment_view_" . $model . "_adminpassword\" class=\"form-control\" placeholder=\"" . __ ( "Password for phone admin mode") . "\" disabled=\"disabled\" />\n";
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
    $buffer["js"]["onshow"][$model] = "      $('#equipment_view_" . $model . "_username').val ( data.ExtraSettings.Username);\n" .
                                      "      $('#equipment_view_" . $model . "_password').val ( data.ExtraSettings.Password);\n" .
                                      "      $('#equipment_view_" . $model . "_adminpassword').val ( data.ExtraSettings.AdminModePassword);\n";
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
function equipments_snom_configure_subpage ( $buffer, $parameters)
{
  /**
   * Add extra fields for administrator and user name and password
   */
  $output = "";
  $extrausersmodels = array ( "snom710", "snom720", "snom870");
  foreach ( $extrausersmodels as $model)
  {
    // Model options:
    $output = "";

    // Add equipment admin username field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_configure_" . $model . "_username\" class=\"control-label col-xs-2\">" . __ ( "Admin username") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <input type=\"text\" name=\"" . strtoupper ( $model) . "_Username\" id=\"equipment_configure_" . $model . "_username\" class=\"form-control\" placeholder=\"" . __ ( "Username of privileged administrator") . "\" />\n";
    $output .= "        </div>\n";
    $output .= "      </div>\n";

    // Add equipment admin password field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_configure_" . $model . "_password\" class=\"control-label col-xs-2\">" . __ ( "Admin password") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <div class=\"input-group\">\n";
    $output .= "            <input type=\"text\" name=\"" . strtoupper ( $model) . "_Password\" id=\"equipment_configure_" . $model . "_password\" class=\"form-control\" placeholder=\"" . __ ( "Password of privileged administrator") . "\" />\n";
    $output .= "            <div class=\"input-group-btn\">\n";
    $output .= "              <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
    $output .= "            </div>\n";
    $output .= "          </div>\n";
    $output .= "        </div>\n";
    $output .= "      </div>\n";

    // Add equipment admin mode password field
    $output .= "      <div class=\"form-group\">\n";
    $output .= "        <label for=\"equipment_configure_" . $model . "_adminpassword\" class=\"control-label col-xs-2\">" . __ ( "Admin mode password") . "</label>\n";
    $output .= "        <div class=\"col-xs-10\">\n";
    $output .= "          <div class=\"input-group\">\n";
    $output .= "            <input type=\"text\" name=\"" . strtoupper ( $model) . "_AdminPassword\" id=\"equipment_configure_" . $model . "_adminpassword\" class=\"form-control\" placeholder=\"" . __ ( "Password for phone admin mode") . "\" />\n";
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
    $buffer["js"]["onshow"][$model] = "      $('#equipment_configure_" . $model . "_username').val ( data.ExtraSettings.Username);\n" .
                                      "      $('#equipment_configure_" . $model . "_password').val ( data.ExtraSettings.Password);\n" .
                                      "      $('#equipment_configure_" . $model . "_adminpassword').val ( data.ExtraSettings.AdminModePassword);\n";
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
                              "    formData.ExtraSettings.Username = formData['" . strtoupper ( $model) . "_Username'];\n" .
                              "    formData.ExtraSettings.Password = formData['" . strtoupper ( $model) . "_Password'];\n" .
                              "    formData.ExtraSettings.AdminModePassword = formData['" . strtoupper ( $model) . "_AdminPassword'];\n" .
                              "  }\n" .
                              "  delete ( formData['" . strtoupper ( $model) . "_Username']);\n" .
                              "  delete ( formData['" . strtoupper ( $model) . "_Password']);\n" .
                              "  delete ( formData['" . strtoupper ( $model) . "_AdminPassword']);\n" .
                              "  $('#equipment_configure_form').data ( 'formData', formData);\n" .
                              "});\n";
  }

  return $buffer;
}
?>
