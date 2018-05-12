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
 * VoIP Domain Khomp equipments module filters. This module add the filter
 * calls related to supported Khomp equipments.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Equipments Khomp
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add Khomp equipment's filters
 */
framework_add_filter ( "equipments_view_subpages", "equipments_khomp_view_subpage");
framework_add_filter ( "equipments_configure_subpages", "equipments_khomp_configure_subpage");

/**
 * Function to generate the equipment phone view subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function equipments_khomp_view_subpage ( $buffer, $parameters)
{
  /**
   * Khomp IPS200 options panel
   */
  $output = "";

  // Add equipment normal user name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_ips200_username\" class=\"control-label col-xs-2\">" . __ ( "User name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"IPS200_Username\" class=\"form-control\" id=\"equipment_view_ips200_username\" placeholder=\"" . __ ( "Username of non-privileged user") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment normal user password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_ips200_userpass\" class=\"control-label col-xs-2\">" . __ ( "User password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"password\" name=\"IPS200_Userpass\" id=\"equipment_view_ips200_userpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of non-privileged user") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-showpass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show password") . "\"><i class=\"fa fa-eye\"></i></button>\n";
  $output .= "              <button class=\"btn btn-default btn-copypass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Copy password") . "\"><i class=\"fa fa-copy\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment admin name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_ips200_adminname\" class=\"control-label col-xs-2\">" . __ ( "Admin name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"IPS200_Adminname\" class=\"form-control\" id=\"equipment_view_ips200_adminname\" placeholder=\"" . __ ( "Username of privileged adminstrator") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment admin password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_ips200_adminpass\" class=\"control-label col-xs-2\">" . __ ( "Admin password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"password\" name=\"IPS200_Adminpass\" id=\"equipment_view_ips200_adminpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of privileged administrator") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-showpass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show password") . "\"><i class=\"fa fa-eye\"></i></button>\n";
  $output .= "              <button class=\"btn btn-default btn-copypass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Copy password") . "\"><i class=\"fa fa-copy\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["ips200"] = array ( "type" => "ips200", "label" => __ ( "Extras"), "html" => $output);

  /**
   * Add configure form JavaScript code
   */
  $buffer["js"]["onshow"]["ips200"] = "      $('#equipment_view_ips200_username').val ( data.ExtraSettings.User.Name);\n" .
                                      "      $('#equipment_view_ips200_userpass').val ( data.ExtraSettings.User.Password);\n" .
                                      "      $('#equipment_view_ips200_adminname').val ( data.ExtraSettings.Admin.Name);\n" .
                                      "      $('#equipment_view_ips200_adminpass').val ( data.ExtraSettings.Admin.Password);\n";
  $buffer["js"]["init"][] = "$('#equipment_view_tab_ips200 .btn-showpass').on ( 'mousedown', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $(this).parent ().parent ().find ( 'input').attr ( 'type', 'text');\n" .
                            "}).on ( 'mouseup mouseout', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $(this).parent ().parent ().find ( 'input').attr ( 'type', 'password');\n" .
                            "});\n" .
                            "$('#equipment_view_tab_ips200 .btn-copypass').on ( 'click', function ( event)\n" .
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

  /**
   * Khomp IPS212 options panel
   */
  $output = "";

  // Add equipment normal user name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_ips212_username\" class=\"control-label col-xs-2\">" . __ ( "User name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"IPS212_Username\" class=\"form-control\" id=\"equipment_view_ips212_username\" placeholder=\"" . __ ( "Username of non-privileged user") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment normal user password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_ips212_userpass\" class=\"control-label col-xs-2\">" . __ ( "User password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"password\" name=\"IPS212_Userpass\" id=\"equipment_view_ips212_userpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of non-privileged user") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-showpass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show password") . "\"><i class=\"fa fa-eye\"></i></button>\n";
  $output .= "              <button class=\"btn btn-default btn-copypass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Copy password") . "\"><i class=\"fa fa-copy\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment admin name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_ips212_adminname\" class=\"control-label col-xs-2\">" . __ ( "Admin name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"IPS212_Adminname\" class=\"form-control\" id=\"equipment_view_ips212_adminname\" placeholder=\"" . __ ( "Username of privileged adminstrator") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment admin password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_view_ips212_adminpass\" class=\"control-label col-xs-2\">" . __ ( "Admin password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"password\" name=\"IPS212_Adminpass\" id=\"equipment_view_ips212_adminpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of privileged administrator") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-showpass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show password") . "\"><i class=\"fa fa-eye\"></i></button>\n";
  $output .= "              <button class=\"btn btn-default btn-copypass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Copy password") . "\"><i class=\"fa fa-copy\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["ips212"] = array ( "type" => "ips212", "label" => __ ( "Extras"), "html" => $output);

  /**
   * Add configure form JavaScript code
   */
  $buffer["js"]["onshow"]["ips212"] = "      $('#equipment_view_ips212_username').val ( data.ExtraSettings.User.Name);\n" .
                                      "      $('#equipment_view_ips212_userpass').val ( data.ExtraSettings.User.Password);\n" .
                                      "      $('#equipment_view_ips212_adminname').val ( data.ExtraSettings.Admin.Name);\n" .
                                      "      $('#equipment_view_ips212_adminpass').val ( data.ExtraSettings.Admin.Password);\n";
  $buffer["js"]["init"][] = "$('#equipment_view_tab_ips212 .btn-showpass').on ( 'mousedown', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $(this).parent ().parent ().find ( 'input').attr ( 'type', 'text');\n" .
                            "}).on ( 'mouseup mouseout', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $(this).parent ().parent ().find ( 'input').attr ( 'type', 'password');\n" .
                            "});\n" .
                            "$('#equipment_view_tab_ips212 .btn-copypass').on ( 'click', function ( event)\n" .
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
function equipments_khomp_configure_subpage ( $buffer, $parameters)
{
  /**
   * Khomp IPS200 options panel
   */
  $output = "";

  // Add equipment normal user name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_ips200_username\" class=\"control-label col-xs-2\">" . __ ( "User name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"IPS200_Username\" class=\"form-control\" id=\"equipment_configure_ips200_username\" placeholder=\"" . __ ( "Username of non-privileged user") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment normal user password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_ips200_userpass\" class=\"control-label col-xs-2\">" . __ ( "User password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"IPS200_Userpass\" id=\"equipment_configure_ips200_userpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of non-privileged user") . "\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment admin name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_ips200_adminname\" class=\"control-label col-xs-2\">" . __ ( "Admin name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"IPS200_Adminname\" class=\"form-control\" id=\"equipment_configure_ips200_adminname\" placeholder=\"" . __ ( "Username of privileged adminstrator") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment admin password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_ips200_adminpass\" class=\"control-label col-xs-2\">" . __ ( "Admin password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"IPS200_Adminpass\" id=\"equipment_configure_ips200_adminpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of privileged administrator") . "\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["ips200"] = array ( "type" => "ips200", "label" => __ ( "Extras"), "html" => $output);

  /**
   * Add configure form JavaScript code
   */
  $buffer["js"]["onshow"]["ips200"] = "      $('#equipment_configure_ips200_username').val ( data.ExtraSettings.User.Name);\n" .
                                      "      $('#equipment_configure_ips200_userpass').val ( data.ExtraSettings.User.Password);\n" .
                                      "      $('#equipment_configure_ips200_adminname').val ( data.ExtraSettings.Admin.Name);\n" .
                                      "      $('#equipment_configure_ips200_adminpass').val ( data.ExtraSettings.Admin.Password);\n";
  $buffer["js"]["init"][] = "$('#equipment_configure_tab_ips200 .btn-random').on ( 'click', function ( event)\n" .
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
                            "  if ( $('#equipment_configure_uid').val () == 'ips200')\n" .
                            "  {\n" .
                            "    formData.ExtraSettings = new Object ();\n" .
                            "    formData.ExtraSettings.User = new Object ();\n" .
                            "    formData.ExtraSettings.User.Name = formData.IPS200_Username;\n" .
                            "    formData.ExtraSettings.User.Password = formData.IPS200_Userpass;\n" .
                            "    formData.ExtraSettings.Admin = new Object ();\n" .
                            "    formData.ExtraSettings.Admin.Name = formData.IPS200_Adminname;\n" .
                            "    formData.ExtraSettings.Admin.Password = formData.IPS200_Adminpass;\n" .
                            "  }\n" .
                            "  delete ( formData.IPS200_Username);\n" .
                            "  delete ( formData.IPS200_Userpass);\n" .
                            "  delete ( formData.IPS200_Adminname);\n" .
                            "  delete ( formData.IPS200_Adminpass);\n" .
                            "  $('#equipment_configure_form').data ( 'formData', formData);\n" .
                            "});\n";

  /**
   * Khomp IPS212 options panel
   */
  $output = "";

  // Add equipment normal user name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_ips212_username\" class=\"control-label col-xs-2\">" . __ ( "User name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"IPS212_Username\" class=\"form-control\" id=\"equipment_configure_ips212_username\" placeholder=\"" . __ ( "Username of non-privileged user") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment normal user password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_ips212_userpass\" class=\"control-label col-xs-2\">" . __ ( "User password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"IPS212_Userpass\" id=\"equipment_configure_ips212_userpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of non-privileged user") . "\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment admin name field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_ips212_adminname\" class=\"control-label col-xs-2\">" . __ ( "Admin name") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"IPS212_Adminname\" class=\"form-control\" id=\"equipment_configure_ips212_adminname\" placeholder=\"" . __ ( "Username of privileged adminstrator") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add equipment admin password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"equipment_configure_ips212_adminpass\" class=\"control-label col-xs-2\">" . __ ( "Admin password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"IPS212_Adminpass\" id=\"equipment_configure_ips212_adminpass\" class=\"form-control\" placeholder=\"" . __ ( "Password of privileged administrator") . "\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["ips212"] = array ( "type" => "ips212", "label" => __ ( "Extras"), "html" => $output);

  /**
   * Add configure form JavaScript code
   */
  $buffer["js"]["onshow"]["ips212"] = "      $('#equipment_configure_ips212_username').val ( data.ExtraSettings.User.Name);\n" .
                                      "      $('#equipment_configure_ips212_userpass').val ( data.ExtraSettings.User.Password);\n" .
                                      "      $('#equipment_configure_ips212_adminname').val ( data.ExtraSettings.Admin.Name);\n" .
                                      "      $('#equipment_configure_ips212_adminpass').val ( data.ExtraSettings.Admin.Password);\n";
  $buffer["js"]["init"][] = "$('#equipment_configure_tab_ips212 .btn-random').on ( 'click', function ( event)\n" .
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
                            "  if ( $('#equipment_configure_uid').val () == 'ips212')\n" .
                            "  {\n" .
                            "    formData.ExtraSettings = new Object ();\n" .
                            "    formData.ExtraSettings.User = new Object ();\n" .
                            "    formData.ExtraSettings.User.Name = formData.IPS212_Username;\n" .
                            "    formData.ExtraSettings.User.Password = formData.IPS212_Userpass;\n" .
                            "    formData.ExtraSettings.Admin = new Object ();\n" .
                            "    formData.ExtraSettings.Admin.Name = formData.IPS212_Adminname;\n" .
                            "    formData.ExtraSettings.Admin.Password = formData.IPS212_Adminpass;\n" .
                            "  }\n" .
                            "  delete ( formData.IPS212_Username);\n" .
                            "  delete ( formData.IPS212_Userpass);\n" .
                            "  delete ( formData.IPS212_Adminname);\n" .
                            "  delete ( formData.IPS212_Adminpass);\n" .
                            "  $('#equipment_configure_form').data ( 'formData', formData);\n" .
                            "});\n";

  return $buffer;
}
?>
