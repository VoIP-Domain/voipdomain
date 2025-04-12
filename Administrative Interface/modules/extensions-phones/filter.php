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
 * VoIP Domain extensions phones module filters. This module add the filter calls
 * related to extensions phones.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Phones
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add extensions phone's filters
 */
framework_add_filter ( "objects_types", "phones_object");
framework_add_filter ( "get_extensions", "get_extensions_phone");
framework_add_filter ( "count_extensions", "get_extensions_phone");
framework_add_filter ( "extensions_add_subpages", "extensions_phones_add_subpage");
framework_add_filter ( "extensions_clone_subpages", "extensions_phones_clone_subpage");
framework_add_filter ( "extensions_view_subpages", "extensions_phones_view_subpage");
framework_add_filter ( "extensions_edit_subpages", "extensions_phones_edit_subpage");

/**
 * Function to add extensions phone interface object information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function phones_object ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( array ( "object" => "extension_phone", "path" => "/extensions", "icon" => "phone", "label" => "info", "text" => array ( "singular" => __ ( "Phone"), "plural" => __ ( "Phones")))));
}

/**
 * Function to get/count extensions filtered by ID, number, description, type, range or group.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function get_extensions_phone ( $buffer, $parameters)
{
  global $_in;

  /**
   * Create where clause
   */
  $where = "";
  if ( array_key_exists ( "ID", $parameters) && $parameters["called_filter"] == "get_extensions")
  {
    $where .= " AND `Extensions`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]);
  }
  if ( array_key_exists ( "Number", $parameters))
  {
    $where .= " AND `Extensions`.`Number` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Number"]);
  }
  if ( array_key_exists ( "Description", $parameters))
  {
    $where .= " AND `Extensions`.`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["Description"])))) . "%'";
  }
  if ( array_key_exists ( "Type", $parameters))
  {
    $where .= " AND `Extensions`.`Type` = " . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["Type"])))) . "%'";
  }
  if ( array_key_exists ( "Range", $parameters))
  {
    $where .= " AND `Extensions`.`Range` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Range"]);
  }
  if ( array_key_exists ( "Group", $parameters))
  {
    $where .= " AND `ExtensionPhone`.`Group` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Group"]);
  }

  /**
   * Check into database if queue exists
   */
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT " . ( $parameters["called_filter"] == "get_extensions" ? "`Extensions`.*, `ExtensionPhone`.`Email`, `ExtensionPhone`.`Group`, `ExtensionPhone`.`Permissions`, `ExtensionPhone`.`Options`, `ExtensionPhone`.`CostCenter`" : "COUNT(*) AS `Total`") . " FROM `Extensions` LEFT JOIN `ExtensionPhone` ON `Extensions`.`ID` = `ExtensionPhone`.`Extension`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    if ( $parameters["called_filter"] == "get_extensions")
    {
      while ( $extension = $result->fetch_assoc ())
      {
        $buffer = array_merge ( ( is_array ( $buffer) ? $buffer : array ()), array ( $extension));
      }
    } else {
      $buffer = (int) $buffer + $result->fetch_assoc ()["Total"];
    }
  }
  return $buffer;
}

/**
 * Function to generate the extension phone add subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_phones_add_subpage ( $buffer, $parameters)
{
  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/css/bootstrap-slider.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/css/tabdrop.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "jquery-labelauty", "src" => "/vendors/jquery-labelauty/source/jquery-labelauty.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/bootstrap-slider.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/js/bootstrap-tabdrop.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-labelauty", "src" => "/vendors/jquery-labelauty/source/jquery-labelauty.js", "dep" => array ()));

  /**
   * Phone options panel
   */
  $output = "";

  // Add extension email field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Email\" class=\"form-control\" id=\"extension_add_email\" placeholder=\"" . __ ( "Extension e-mail") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension group selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_group\" class=\"control-label col-xs-2\">" . __ ( "Group") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Group\" id=\"extension_add_group\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension group") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension capture groups selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_captures\" class=\"control-label col-xs-2\">" . __ ( "Captures") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Captures\" id=\"extension_add_captures\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension capture groups") . "\" multiple=\"multiple\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"Password\" id=\"extension_add_password\" class=\"form-control\" placeholder=\"" . __ ( "Extension password") . "\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension transhipments selectors
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_transhipments\" class=\"control-label col-xs-2\">" . __ ( "Transhipment") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Transhipments\" id=\"extension_add_transhipments\" class=\"form-control\" data-placeholder=\"" . __ ( "Transhipment extensions") . "\" multiple=\"multiple\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-basic"] = array ( "type" => "phone", "label" => __ ( "Phone"), "html" => $output);

  /**
   * Permissions panel
   */
  $output = "";

  // Add location levels headers
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <span class=\"col-xs-2\"></span>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 column-label center\">" . __ ( "Local") . "</div>\n";
  $output .= "          <div class=\"col-xs-4 column-label center\">" . __ ( "Interstate") . "</div>\n";
  $output .= "          <div class=\"col-xs-4 column-label center\">" . __ ( "International") . "</div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add landline call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Landline") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Interstate\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Interstate\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Interstate\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Landline_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add mobile call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Mobile") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Interstate\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Interstate\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Interstate\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add marine call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Marine") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Interstate\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Interstate\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Interstate\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Marine_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add toll free call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Toll free") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4\">\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add premium rate numbers call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Premium rate numbers") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"PRN_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4\">\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"PRN_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add satellite call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Satellite") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4\">\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-permissions"] = array ( "type" => "phone", "label" => __ ( "Permissions"), "html" => $output);

  /**
   * Accounts panel
   */
  $output = "";

  // Add extension account controls
  $output .= "      <div id=\"extension_add_accounts\">\n";
  $output .= "        <label class=\"control-label col-xs-2\"><span>" . __ ( "Accounts") . "<br /><button class=\"btn btn-success btn-addaccount\" role=\"button\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . __ ( "Add new account") . "\"><i class=\"fa fa-plus\"></i></button></span></label>\n";
  $output .= "        <ul class=\"nav nav-tabs col-xs-10\" role=\"tablist\"></ul>\n";
  $output .= "        <div class=\"tab-content col-xs-offset-2 col-xs-10\">\n";
  $output .= "          <br />\n";
  $output .= "          <div class=\"tab-pane hide\" id=\"extension_add_account_template\">\n";

  // Add equipment type selector
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"extension_add_account__ID__type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"Account__ID__Type\" id=\"extension_add_account__ID__type\" class=\"form-control\" data-placeholder=\"" . __ ( "Equipment type") . "\"><option value=\"\"></option></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add MAC address field
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"extension_add_account__ID__mac\" class=\"control-label col-xs-2\">" . __ ( "MAC address") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"Account__ID__MAC\" class=\"form-control\" id=\"extension_add_account__ID__mac\" placeholder=\"" . __ ( "MAC address") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-accounts"] = array ( "type" => "phone", "label" => __ ( "Accounts"), "html" => $output);

  /**
   * Extra options panel
   */
  $output = "";

  // Add cost center selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_costcenter\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"CostCenter\" id=\"extension_add_costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension cost center") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension voicemail option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_voicemail\" class=\"control-label col-xs-2\">" . __ ( "Voice mail") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"VoiceMail\" id=\"extension_add_voicemail\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add monitor option switch
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_monitor\" class=\"control-label col-xs-2\">" . __ ( "Monitor") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Monitor\" id=\"extension_add_monitor\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add reception volume slider
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_rxvol\" class=\"control-label col-xs-2\">" . __ ( "RX volume") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"VolRX\" id=\"extension_add_volrx\" class=\"form-control tabslider\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add transmission volume slider
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_txvol\" class=\"control-label col-xs-2\">" . __ ( "TX volume") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"VolTX\" id=\"extension_add_voltx\" class=\"form-control tabslider\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension hint selectors
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_add_hints\" class=\"control-label col-xs-2\">" . __ ( "Hints") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Hints\" id=\"extension_add_hints\" class=\"form-control\" data-placeholder=\"" . __ ( "Hints extensions") . "\" multiple=\"multiple\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-extras"] = array ( "type" => "phone", "label" => __ ( "Extras"), "html" => $output);

  /**
   * Add add form JavaScript code
   */
  $buffer["js"]["init"][] = "$('#extension_add_group,#extension_add_captures').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/groups', Fields: 'ID,Description', formatText: '%Description%'})\n" .
                            "});\n" .
                            "$('#extension_add_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
                            "$('#extension_add_password').mask ( '000000');\n" .
                            "$('#extension_add_transhipments,#extension_add_hints').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/extensions', Fields: 'ID,Description,Number', formatText: '%Description% (%Number%)'})\n" .
                            "});\n" .
                            "$('#extension_add_tab_phone-permissions input[type=radio]').labelauty ();\n" .
                            "VoIP.rest ( '/config/permissions', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
                            "{\n" .
                            "  for ( var type in data)\n" .
                            "  {\n" .
                            "    for ( var area in data[type])\n" .
                            "    {\n" .
                            "      switch ( data[type][area])\n" .
                            "      {\n" .
                            "        case 'y':\n" .
                            "          $('#extension_add_form input[name=\"' + type + '_' + area + '\"][value=\"y\"]').prop ( 'checked', 'checked');\n" .
                            "          break;\n" .
                            "        case 'p':\n" .
                            "          $('#extension_add_form input[name=\"' + type + '_' + area + '\"][value=\"y\"]').prop ( 'disabled', 'disabled');\n" .
                            "          $('#extension_add_form input[name=\"' + type + '_' + area + '\"][value=\"p\"]').prop ( 'checked', 'checked');\n" .
                            "          break;\n" .
                            "        case 'n':\n" .
                            "          $('#extension_add_form input[name=\"' + type + '_' + area + '\"][value=\"y\"]').prop ( 'disabled', 'disabled');\n" .
                            "          $('#extension_add_form input[name=\"' + type + '_' + area + '\"][value=\"p\"]').prop ( 'disabled', 'disabled');\n" .
                            "          $('#extension_add_form input[name=\"' + type + '_' + area + '\"][value=\"n\"]').prop ( 'checked', 'checked');\n" .
                            "          break;\n" .
                            "      }\n" .
                            "    }\n" .
                            "  }\n" .
                            "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
                            "{\n" .
                            "  new PNotify ( { title: '" . __ ( "Extension addition") . "', text: '" . __ ( "Error retrieving permissions!") . "', type: 'error'});\n" .
                            "});\n" .
                            "$('#extension_add_costcenter').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  width: '100%',\n" .
                            "  data: VoIP.APIsearch ( { path: '/costcenters', Fields: 'ID,Description,Code', formatText: '%Description% (%Code%)'})\n" .
                            "});\n" .
                            "$('#extension_add_volrx,#extension_add_voltx').bootstrapSlider (\n" .
                            "{\n" .
                            "  ticks: [ -10, 0, 10],\n" .
                            "  ticks_labels: [ '" . __ ( "Low") . "', '" . __ ( "Medium") . "', '" . __ ( "High") . "'],\n" .
                            "  min: -10,\n" .
                            "  max: 10,\n" .
                            "  value: 0,\n" .
                            "  enabled: true,\n" .
                            "  tooltip: 'hide'\n" .
                            "});\n" .
                            "$('#extension_add_form').find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
                            "{\n" .
                            "  $($(e.target).attr ( 'href')).find ( 'input.tabslider').bootstrapSlider ( 'refresh', { useCurrentValue: true });\n" .
                            "});\n" .
                            "$('button.btn-random').on ( 'click', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $(this).parent ().parent ().find ( 'input').val ( Math.floor ( 100000 + Math.random ( ) * 900000));\n" .
                            "});\n" .
                            "$('#extension_add_group').data ( 'last', '').on ( 'select2:select', function ( event)\n" .
                            "{\n" .
                            "  var captures = $('#extension_add_captures').val ();\n" .
                            "  if ( typeof ( captures) === 'undefined')\n" .
                            "  {\n" .
                            "    captures = new Array ();\n" .
                            "  }\n" .
                            "  if ( $(this).data ( 'last') != '' && $.inArray ( $(this).data ( 'last'), captures) != -1)\n" .
                            "  {\n" .
                            "    captures.splice ( $.inArray ( $(this).data ( 'last'), captures), 1);\n" .
                            "  }\n" .
                            "  if ( $.inArray ( '', captures) == -1)\n" .
                            "  {\n" .
                            "    captures.push ( $(this).val ());\n" .
                            "  }\n" .
                            "  $(this).data ( 'last', $(this).val ());\n" .
                            "  $('#extension_add_captures').val ( captures).trigger ( 'change');\n" .
                            "}).on ( 'select2:unselect', function ( event)\n" .
                            "{\n" .
                            "  var captures = $('#extension_add_captures').val ();\n" .
                            "  if ( $(this).val () != '' && $.inArray ( $(this).val (), captures) != -1)\n" .
                            "  {\n" .
                            "    captures.splice ( $.inArray ( $(this).val (), captures), 1);\n" .
                            "  }\n" .
                            "  $('#extension_add_group').data ( 'last', '');\n" .
                            "  $('#extension_add_captures').val ( captures).trigger ( 'change');\n" .
                            "});\n" .
                            "VoIP.rest ( '/equipments', 'GET', { Fields: 'ID,Vendor,Model,AutoProvision'}).done ( function ( result, textStatus, jqXHR)\n" .
                            "{\n" .
                            "  $('#extension_add_accounts').data ( 'types', result);\n" .
                            "  var ret = [];\n" .
                            "  for ( var x in result)\n" .
                            "  {\n" .
                            "    ret.push (\n" .
                            "    {\n" .
                            "      id: result[x].ID,\n" .
                            "      text: result[x].Vendor + ' ' + result[x].Model\n" .
                            "    });\n" .
                            "  }\n" .
                            "  $('#extension_add_accounts').data ( 'typesformated', ret);\n" .
                            "  $('#extension_add_accounts ul.nav-tabs').trigger ( 'addTab');\n" .
                            "  $('#extension_add_accounts ul.nav-tabs li i').remove ();\n" .
                            "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
                            "{\n" .
                            "  new PNotify ( { title: '" . __ ( "Extension addition") . "', text: '" . __ ( "Error retrieving equipment types!") . "', type: 'error'});\n" .
                            "});\n" .
                            "$('#extension_add_accounts').on ( 'click', 'i.tab-del', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  if ( $(this).parent ().parent ().hasClass ( 'active'))\n" .
                            "  {\n" .
                            "    $('#extension_add_accounts ul.nav-tabs li:not(.hide):first a').tab ( 'show');\n" .
                            "  }\n" .
                            "  $('#extension_add_account_' + $(this).data ( 'tabid')).remove ();\n" .
                            "  $(this).parent ().parent ().remove ();\n" .
                            "  var x = 1;\n" .
                            "  $('#extension_add_accounts ul.nav-tabs li:not(.hide):not(:eq(0)) a').each ( function ( idx, li)\n" .
                            "  {\n" .
                            "    x++;\n" .
                            "    $(li).html ( $(li).html ().replace ( /[0-9]+\&nbsp;/, x + '&nbsp;'));\n" .
                            "  });\n" .
                            "  $('#extension_add_accounts ul.nav-tabs').tabdrop ( 'layout');\n" .
                            "  $('#extension_add_accounts button.btn-addaccount').prop ( 'disabled', false);\n" .
                            "});\n" .
                            "$('#extension_add_accounts ul.nav-tabs').data ( 'tabid', 0).on ( 'addTab', function ()\n" .
                            "{\n" .
                            "  $(this).data ( 'tabid', $(this).data ( 'tabid') + 1);\n" .
                            "  $('#extension_add_accounts ul.nav-tabs').append ( '<li><a href=\"#extension_add_account_' + $(this).data ( 'tabid') + '\" role=\"tab\" data-toggle=\"tab\">' + ( $('#extension_add_accounts a[data-toggle=\"tab\"]').length + 1) + '&nbsp;<i class=\"fa fa-times tab-del\" data-tabid=\"' + $(this).data ( 'tabid') + '\"></i></a></li>');\n" .
                            "  $('#extension_add_accounts div.tab-content').append ( '<div class=\"tab-pane\" id=\"extension_add_account_' + $(this).data ( 'tabid') + '\"></div>');\n" .
                            "  $('#extension_add_account_' + $(this).data ( 'tabid')).html ( $('#extension_add_account_template').html ().replace ( /_ID_/mg, $(this).data ( 'tabid')));\n" .
                            "  $('#extension_add_account_' + $(this).data ( 'tabid') + '_type').select2 ( { allowClear: true, data: $('#extension_add_accounts').data ( 'typesformated')}).on ( 'select2:select', function ( event)\n" .
                            "  {\n" .
                            "    $('#' + $(this).attr ( 'id').replace ( /type$/, 'mac')).val ( '').attr ( 'disabled', 'disabled');\n" .
                            "    for ( var sid in $('#extension_add_accounts').data ( 'types'))\n" .
                            "    {\n" .
                            "      if ( $('#extension_add_accounts').data ( 'types')[sid].ID == $(this).val ())\n" .
                            "      {\n" .
                            "        if ( $('#extension_add_accounts').data ( 'types')[sid].AutoProvision)\n" .
                            "        {\n" .
                            "          $('#' + $(this).attr ( 'id').replace ( /type$/, 'mac')).removeAttr ( 'disabled').focus ();\n" .
                            "        }\n" .
                            "      }\n" .
                            "    }\n" .
                            "  }).on ( 'select2:unselect', function ( event)\n" .
                            "  {\n" .
                            "    $('#' + $(this).attr ( 'id').replace ( /type$/, 'mac')).val ( '').attr ( 'disabled', 'disabled');\n" .
                            "  });\n" .
                            "  $('#extension_add_account_' + $(this).data ( 'tabid') + '_mac').mask ( 'xx:xx:xx:xx:xx:xx', { 'translation': { x: { pattern: /[A-Fa-f0-9]/}}});\n" .
                            "  $('#extension_add_accounts ul.nav-tabs a:last').tab ( 'show');\n" .
                            "  $('#extension_add_accounts ul.nav-tabs').tabdrop ( 'layout');\n" .
                            "  if ( $('#extension_add_accounts a[data-toggle=\"tab\"]').length == 10)\n" .
                            "  {\n" .
                            "    $('#extension_add_accounts button.btn-addaccount').prop ( 'disabled', true);\n" .
                            "  }\n" .
                            "});\n" .
                            "$('#extension_add_accounts button.btn-addaccount').prop ( 'disabled', false).on ( 'click', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $('#extension_add_accounts ul.nav-tabs').trigger ( 'addTab');\n" .
                            "});\n" .
                            "$('#extension_add_form').on ( 'formFilter', function ()\n" .
                            "{\n" .
                            "  var formData = $('#extension_add_form').data ( 'formData');\n" .
                            "  if ( typeof ( formData.Captures) == 'string')\n" .
                            "  {\n" .
                            "    var tmp = formData.Captures;\n" .
                            "    formData.Captures = new Array ();\n" .
                            "    formData.Captures.push ( tmp);\n" .
                            "  }\n" .
                            "  if ( typeof ( formData.Transhipments) == 'string')\n" .
                            "  {\n" .
                            "    var tmp = formData.Transhipments;\n" .
                            "    formData.Transhipments = new Array ();\n" .
                            "    formData.Transhipments.push ( tmp);\n" .
                            "  }\n" .
                            "  if ( typeof ( formData.Hints) == 'string')\n" .
                            "  {\n" .
                            "    var tmp = formData.Hints;\n" .
                            "    formData.Hints = new Array ();\n" .
                            "    formData.Hints.push ( tmp);\n" .
                            "  }\n" .
                            "  formData.Permissions = new Object ();\n" .
                            "  formData.Permissions.Landline = new Object ();\n" .
                            "  formData.Permissions.Landline.Local = formData.Landline_Local;\n" .
                            "  delete ( formData.Landline_Local);\n" .
                            "  formData.Permissions.Landline.Interstate = formData.Landline_Interstate;\n" .
                            "  delete ( formData.Landline_Interstate);\n" .
                            "  formData.Permissions.Landline.International = formData.Landline_International;\n" .
                            "  delete ( formData.Landline_International);\n" .
                            "  formData.Permissions.Mobile = new Object ();\n" .
                            "  formData.Permissions.Mobile.Local = formData.Mobile_Local;\n" .
                            "  delete ( formData.Mobile_Local);\n" .
                            "  formData.Permissions.Mobile.Interstate = formData.Mobile_Interstate;\n" .
                            "  delete ( formData.Mobile_Interstate);\n" .
                            "  formData.Permissions.Mobile.International = formData.Mobile_International;\n" .
                            "  delete ( formData.Mobile_International);\n" .
                            "  formData.Permissions.Marine = new Object ();\n" .
                            "  formData.Permissions.Marine.Local = formData.Marine_Local;\n" .
                            "  delete ( formData.Marine_Local);\n" .
                            "  formData.Permissions.Marine.Interstate = formData.Marine_Interstate;\n" .
                            "  delete ( formData.Marine_Interstate);\n" .
                            "  formData.Permissions.Marine.International = formData.Marine_International;\n" .
                            "  delete ( formData.Marine_International);\n" .
                            "  formData.Permissions.Tollfree = new Object ();\n" .
                            "  formData.Permissions.Tollfree.Local = formData.Tollfree_Local;\n" .
                            "  delete ( formData.Tollfree_Local);\n" .
                            "  formData.Permissions.Tollfree.International = formData.Tollfree_International;\n" .
                            "  delete ( formData.Tollfree_International);\n" .
                            "  formData.Permissions.PRN = new Object ();\n" .
                            "  formData.Permissions.PRN.Local = formData.PRN_Local;\n" .
                            "  delete ( formData.PRN_Local);\n" .
                            "  formData.Permissions.PRN.International = formData.PRN_International;\n" .
                            "  delete ( formData.PRN_International);\n" .
                            "  formData.Permissions.Satellite = new Object ();\n" .
                            "  formData.Permissions.Satellite.Local = formData.Satellite_Local;\n" .
                            "  delete ( formData.Satellite_Local);\n" .
                            "  formData.Permissions.Satellite.International = formData.Satellite_International;\n" .
                            "  delete ( formData.Satellite_International);\n" .
                            "  delete ( formData.Account__ID__Type);\n" .
                            "  formData.Accounts = new Array ();\n" .
                            "  for ( var x = 1; x <= $('#extension_add_accounts ul.nav-tabs').data ( 'tabid'); x++)\n" .
                            "  {\n" .
                            "    if ( $('#extension_add_account_' + x + '_type').val ())\n" .
                            "    {\n" .
                            "      var tmp = new Object ();\n" .
                            "      tmp.Reference = x;\n" .
                            "      tmp.Type = $('#extension_add_account_' + x + '_type').val ();\n" .
                            "      tmp.MAC = $('#extension_add_account_' + x + '_mac').val ();\n" .
                            "      formData.Accounts.push ( tmp);\n" .
                            "      delete ( tmp);\n" .
                            "    }\n" .
                            "    delete ( formData['Account_' + x + '_Type']);\n" .
                            "    delete ( formData['Account_' + x + '_MAC']);\n" .
                            "  }\n" .
                            "  formData.Monitor = $('#extensions_add_monitor').prop ( 'checked');\n" .
                            "  formData.VoiceMail = $('#extensions_add_voicemail').prop ( 'checked');\n" .
                            "  $('#extension_add_form').data ( 'formData', formData);\n" .
                            "});\n";

  return $buffer;
}

/**
 * Function to generate the extension phone clone subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_phones_clone_subpage ( $buffer, $parameters)
{
  /**
   * Add clone form JavaScript code
   */
  $buffer["js"]["onshow"]["phone"] = "        $('#extension_add_extension').val ( data.Extension);\n" .
                                     "        $('#extension_add_name').val ( data.Name);\n" .
                                     "        $('#extension_add_email').val ( data.Email);\n" .
                                     "        $('#extension_add_group').append ( $('<option>', { value: data.Group.ID, text: data.Group.Description})).val ( data.Group.ID).trigger ( 'change');\n" .
                                     "        var ids = [];\n" .
                                     "        for ( var id in data.Captures)\n" .
                                     "        {\n" .
                                     "          if ( ! data.Captures.hasOwnProperty ( id))\n" .
                                     "          {\n" .
                                     "            continue;\n" .
                                     "          }\n" .
                                     "          ids.push ( data.Captures[id].ID);\n" .
                                     "        }\n" .
                                     "        $('#extension_add_captures').val ( ids).trigger ( 'change');\n" .
                                     "        $('#extension_add_voicemail').bootstrapToggle ( data.VoiceMail ? 'on' : 'off');\n" .
                                     "        $('#extension_add_password').val ( data.Password);\n" .
                                     "        var ids = [];\n" .
                                     "        for ( var id in data.Transhipments)\n" .
                                     "        {\n" .
                                     "          if ( ! data.Transhipments.hasOwnProperty ( id))\n" .
                                     "          {\n" .
                                     "            continue;\n" .
                                     "          }\n" .
                                     "          ids.push ( data.Transhipments[id].ID);\n" .
                                     "        }\n" .
                                     "        $('#extension_add_transhipments').val ( ids).trigger ( 'change');\n" .
                                     "        $('#extension_add_accounts').data ( 'accounts', data.Accounts);\n" .
                                     "        $('#extension_add_accounts ul.nav-tabs').tabdrop ( 'layout');\n" .
                                     "        $('#extension_add_costcenter').val ( data.CostCenter.ID).trigger ( 'change');\n" .
                                     "        $('#extension_add_monitor').bootstrapToggle ( data.Monitor ? 'on' : 'off');\n" .
                                     "        $('#extension_add_volrx').data ( 'value', data.VolRX);\n" .
                                     "        $('#extension_add_voltx').data ( 'value', data.VolTX);\n" .
                                     "        for ( var type in data.Permissions)\n" .
                                     "        {\n" .
                                     "          for ( var area in data.Permissions[type])\n" .
                                     "          {\n" .
                                     "            $('#extension_add_form input[name=\"' + type + '_' + area + '\"][value=\"' + data.Permissions[type][area] + '\"]').prop ( 'checked', 'checked');\n" .
                                     "          }\n" .
                                     "        }\n" .
                                     "        var ids = [];\n" .
                                     "        for ( var id in data.Hints)\n" .
                                     "        {\n" .
                                     "          if ( ! data.Hints.hasOwnProperty ( id))\n" .
                                     "          {\n" .
                                     "            continue;\n" .
                                     "          }\n" .
                                     "          ids.push ( data.Hints[id].ID);\n" .
                                     "        }\n" .
                                     "        $('#extension_add_hints').val ( ids).trigger ( 'change');\n";

  return $buffer;
}

/**
 * Function to generate the extension phone view subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_phones_view_subpage ( $buffer, $parameters)
{
  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/css/bootstrap-slider.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/css/tabdrop.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "jquery-labelauty", "src" => "/vendors/jquery-labelauty/source/jquery-labelauty.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/bootstrap-slider.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/js/bootstrap-tabdrop.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-qrcode", "src" => "/vendors/jquery-qrcode/dist/jquery-qrcode.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-labelauty", "src" => "/vendors/jquery-labelauty/source/jquery-labelauty.js", "dep" => array ()));

  /**
   * Phone options panel
   */
  $output = "";

  // Add extension email field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Email\" class=\"form-control\" id=\"extension_view_email\" placeholder=\"" . __ ( "Extension e-mail") . "\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension group selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_group\" class=\"control-label col-xs-2\">" . __ ( "Group") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Group\" id=\"extension_view_group\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension group") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension capture groups selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_captures\" class=\"control-label col-xs-2\">" . __ ( "Captures") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Captures\" id=\"extension_view_captures\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension capture groups") . "\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"password\" name=\"Password\" id=\"extension_view_password\" class=\"form-control\" placeholder=\"" . __ ( "Extension password") . "\" disabled=\"disabled\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-showpass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Show password") . "\"><i class=\"fa fa-eye\"></i></button>\n";
  $output .= "              <button class=\"btn btn-default btn-copypass\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Copy password") . "\"><i class=\"fa fa-copy\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension transhipments selectors
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_transhipments\" class=\"control-label col-xs-2\">" . __ ( "Transhipment") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Transhipments\" id=\"extension_view_transhipments\" class=\"form-control\" data-placeholder=\"" . __ ( "Transhipment extensions") . "\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-basic"] = array ( "type" => "phone", "label" => __ ( "Phone"), "html" => $output);

  /**
   * Permissions panel
   */
  $output = "";

  // Add location levels headers
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <span class=\"col-xs-2\"></span>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 column-label center\">" . __ ( "Local") . "</div>\n";
  $output .= "          <div class=\"col-xs-4 column-label center\">" . __ ( "Interstate") . "</div>\n";
  $output .= "          <div class=\"col-xs-4 column-label center\">" . __ ( "International") . "</div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add landline call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Landline") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Local\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Local\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Local\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Interstate\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Interstate\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Interstate\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Landline_International\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_International\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_International\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add mobile call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Mobile") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Local\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Local\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Local\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Interstate\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Interstate\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Interstate\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_International\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_International\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_International\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add marine call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Marine") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Local\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Local\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Local\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Interstate\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Interstate\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Interstate\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Marine_International\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_International\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_International\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add toll free call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Toll free") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_Local\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_Local\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_Local\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4\">\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_International\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_International\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_International\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add premium rate numbers call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Premium rate numbers") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"PRN_Local\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_Local\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_Local\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4\">\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"PRN_International\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_International\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_International\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add satellite call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Satellite") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_Local\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_Local\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_Local\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4\">\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_International\" value=\"y\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_International\" value=\"p\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_International\" value=\"n\" class=\"form-control\" disabled=\"disabled\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-permissions"] = array ( "type" => "phone", "label" => __ ( "Permissions"), "html" => $output);

  /**
   * Accounts panel
   */
  $output = "";

  // Add extension account controls
  $output .= "      <div id=\"extension_view_accounts\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Accounts") . "</label>\n";
  $output .= "        <ul class=\"nav nav-tabs col-xs-10\" role=\"tablist\"></ul>\n";
  $output .= "        <div class=\"tab-content col-xs-offset-2 col-xs-10\">\n";
  $output .= "          <br />\n";
  $output .= "          <div class=\"tab-pane hide\" id=\"extension_view_account_template\">\n";

  // Add equipment type selector
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"extension_view_account__ID__type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"Account__ID__Type\" id=\"extension_view_account__ID__type\" class=\"form-control\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add MAC address field
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"extension_view_account__ID__mac\" class=\"control-label col-xs-2\">" . __ ( "MAC address") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"Account__ID__MAC\" class=\"form-control\" id=\"extension_view_account__ID__mac\" placeholder=\"" . __ ( "MAC address") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-accounts"] = array ( "type" => "phone", "label" => __ ( "Accounts"), "html" => $output);

  /**
   * Extra options panel
   */
  $output = "";

  // Add cost center selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_costcenter\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"CostCenter\" id=\"extension_view_costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension voicemail option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_voicemail\" class=\"control-label col-xs-2\">" . __ ( "Voice mail") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"VoiceMail\" value=\"true\" id=\"extension_view_voicemail\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add monitor option switch
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_monitor\" class=\"control-label col-xs-2\">" . __ ( "Monitor") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Monitor\" value=\"true\" id=\"extension_view_monitor\" class=\"form-control\" disabled=\"disabled\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add reception volume slider
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_rxvol\" class=\"control-label col-xs-2\">" . __ ( "RX volume") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"VolRX\" id=\"extension_view_volrx\" class=\"form-control tabslider\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add transmission volume slider
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_txvol\" class=\"control-label col-xs-2\">" . __ ( "TX volume") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"VolTX\" id=\"extension_view_voltx\" class=\"form-control tabslider\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension hint selectors
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_view_hints\" class=\"control-label col-xs-2\">" . __ ( "Hints") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Hints\" id=\"extension_view_hints\" class=\"form-control\" data-placeholder=\"" . __ ( "Hints extensions") . "\" multiple=\"multiple\" disabled=\"disabled\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-extras"] = array ( "type" => "phone", "label" => __ ( "Extras"), "html" => $output);

  /**
   * Add subpage extra html code (qwcode view modal)
   */
  $buffer["html"] .= "<div id=\"extension_export\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"extension_export\" aria-hidden=\"true\">\n";
  $buffer["html"] .= "  <div class=\"modal-dialog\">\n";
  $buffer["html"] .= "    <div class=\"modal-content\">\n";
  $buffer["html"] .= "      <div class=\"modal-header\">\n";
  $buffer["html"] .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $buffer["html"] .= "        <h3 class=\"modal-title\">" . __ ( "Extension export") . "</h3>\n";
  $buffer["html"] .= "      </div>\n";
  $buffer["html"] .= "      <div class=\"modal-body text-center\">\n";
  $buffer["html"] .= "        <select name=\"export_type\" id=\"extension_export_type\">\n";
  $buffer["html"] .= "          <option value=\"grandstream\">" . __ ( "Grandstream Wave QR Code") . "</option>\n";
  $buffer["html"] .= "          <option value=\"portsip\">" . __ ( "PortSIP UC QR Code") . "</option>\n";
  $buffer["html"] .= "          <option value=\"json\">" . __ ( "JSON format") . "</option>\n";
  $buffer["html"] .= "          <option value=\"text\">" . __ ( "Clear text format") . "</option>\n";
  $buffer["html"] .= "        </select><br />\n";
  $buffer["html"] .= "        <div id=\"extension_export_content\"></div>\n";
  $buffer["html"] .= "      </div>\n";
  $buffer["html"] .= "      <div class=\"modal-footer\">\n";
  $buffer["html"] .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $buffer["html"] .= "      </div>\n";
  $buffer["html"] .= "    </div>\n";
  $buffer["html"] .= "  </div>\n";
  $buffer["html"] .= "</div>\n";

  /**
   * Add edit form JavaScript code
   */
  $buffer["js"]["onshow"]["phone"] = "      $('#extension_view_email').val ( data.Email);\n" .
                                     "      $('#extension_view_group').append ( $('<option>', { value: data.Group.ID, text: data.Group.Description})).val ( data.Group.ID).trigger ( 'change');\n" .
                                     "      var ids = [];\n" .
                                     "      for ( var id in data.Captures)\n" .
                                     "      {\n" .
                                     "        if ( ! data.Captures.hasOwnProperty ( id))\n" .
                                     "        {\n" .
                                     "          continue;\n" .
                                     "        }\n" .
                                     "        ids.push ( data.Captures[id].ID);\n" .
                                     "        $('#extension_view_captures').append ( $('<option>', { value: data.Captures[id].ID, text: data.Captures[id].Description}));\n" .
                                     "      }\n" .
                                     "      $('#extension_view_captures').val ( ids).trigger ( 'change');\n" .
                                     "      for ( var type in data.Permissions)\n" .
                                     "      {\n" .
                                     "        for ( var area in data.Permissions[type])\n" .
                                     "        {\n" .
                                     "          $('#extension_view_form input[name=\"' + type + '_' + area + '\"][value=\"' + data.Permissions[type][area] + '\"]').prop ( 'checked', 'checked');\n" .
                                     "        }\n" .
                                     "      }\n" .
                                     "      $('#extension_view_voicemail').bootstrapToggle ( 'enable').bootstrapToggle ( data.VoiceMail ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
                                     "      $('#extension_view_password').val ( data.Password);\n" .
                                     "      var ids = [];\n" .
                                     "      for ( var id in data.Transhipments)\n" .
                                     "      {\n" .
                                     "        if ( ! data.Transhipments.hasOwnProperty ( id))\n" .
                                     "        {\n" .
                                     "          continue;\n" .
                                     "        }\n" .
                                     "        ids.push ( data.Transhipments[id].ID);\n" .
                                     "        $('#extension_view_transhipments').append ( $('<option>', { value: data.Transhipments[id].ID, text: data.Transhipments[id].Description}));\n" .
                                     "      }\n" .
                                     "      $('#extension_view_transhipments').val ( ids).trigger ( 'change');\n" .
                                     "      var count = 0;\n" .
                                     "      for ( var id in data.Accounts)\n" .
                                     "      {\n" .
                                     "        if ( ! data.Accounts.hasOwnProperty ( id))\n" .
                                     "        {\n" .
                                     "          continue;\n" .
                                     "        }\n" .
                                     "        count++;\n" .
                                     "        $('#extension_view_accounts ul.nav-tabs').append ( '<li><a href=\"#extension_view_account_' + id + '\" role=\"tab\" data-toggle=\"tab\">' + count + '</a></li>');\n" .
                                     "        $('#extension_view_accounts div.tab-content').append ( '<div class=\"tab-pane\" id=\"extension_view_account_' + id + '\"></div>');\n" .
                                     "        $('#extension_view_account_' + id).html ( $('#extension_view_account_template').html ().replace ( /_ID_/mg, id));\n" .
                                     "        $('#extension_view_account_' + id + '_type').append ( $('<option>', { value: data.Accounts[id].Type.ID, text: data.Accounts[id].Type.Description})).val ( data.Accounts[id].Type.ID).select2 ();\n" .
                                     "        if ( 'Username' in data.Accounts[id])\n" .
                                     "        {\n" .
                                     "          var field = $('#extension_view_account_' + id + '_mac').parent ().html ();\n" .
                                     "          $('#extension_view_account_' + id + '_mac').parent ().empty ().html ( '<div class=\"input-group\">' + field + '<div class=\"input-group-btn\"><button class=\"btn btn-default btn-export ladda-button\" data-style=\"zoom-in\" data-id=\"' + data.Accounts[id].ID + '\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . __ ( "Extension export") . "\" type=\"button\"><i class=\"fa fa-file-download\"></i></button></div></div>');\n" .
                                     "          $('#extension_view_account_' + id + '_mac').find ( 'button').tooltip ();\n" .
                                     "          $('#extension_view_account_' + id + '_mac').attr ( 'placeholder', '" . __ ( "Username / Password") . "').val ( data.Accounts[id].Username + ' / ' + data.Accounts[id].Password);\n" .
                                     "          $('#extension_view_account_' + id + '_mac').closest ( '.form-group').find ( 'label').text ( '" . __ ( "Username / Password") . "');\n" .
                                     "        } else {\n" .
                                     "          $('#extension_view_account_' + id + '_mac').val ( data.Accounts[id].MAC);\n" .
                                     "        }\n" .
                                     "      }\n" .
                                     "      $('#extension_view_accounts ul.nav-tabs li:not(.hide) a:first').tab ( 'show');\n" .
                                     "      $('#extension_view_accounts ul.nav-tabs').tabdrop ( 'layout');\n" .
                                     "      $('#extension_view_costcenter').append ( $('<option>', { value: data.CostCenter.ID, text: data.CostCenter.Description})).val ( data.CostCenter.ID).trigger ( 'change');\n" .
                                     "      $('#extension_view_monitor').bootstrapToggle ( 'enable').bootstrapToggle ( data.Monitor ? 'on' : 'off').bootstrapToggle ( 'disable');\n" .
                                     "      $('#extension_view_volrx').bootstrapSlider ( 'setValue', data.VolRX);\n" .
                                     "      $('#extension_view_voltx').bootstrapSlider ( 'setValue', data.VolTX);\n" .
                                     "      var ids = [];\n" .
                                     "      for ( var id in data.Hints)\n" .
                                     "      {\n" .
                                     "        if ( ! data.Hints.hasOwnProperty ( id))\n" .
                                     "        {\n" .
                                     "          continue;\n" .
                                     "        }\n" .
                                     "        ids.push ( data.Hints[id].ID);\n" .
                                     "        $('#extension_view_hints').append ( $('<option>', { value: data.Hints[id].ID, text: data.Hints[id].Description}));\n" .
                                     "      }\n" .
                                     "      $('#extension_view_hints').val ( ids).trigger ( 'change');\n";
  $buffer["js"]["init"][] = "$('#extension_view_group,#extension_view_captures,#extension_view_transhipments,#extension_view_hints,#extension_view_costcenter,#extension_export_type').select2 ();\n" .
                            "$('#extension_view_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
                            "$('#extension_view_accounts ul.nav-tabs').tabdrop ();\n" .
                            "$('#extension_view_tab_phone-permissions input[type=radio]').labelauty ();\n" .
                            "$('#extension_view_volrx,#extension_view_voltx').bootstrapSlider (\n" .
                            "{\n" .
                            "  ticks: [ -10, 0, 10],\n" .
                            "  ticks_labels: [ '" . __ ( "Low") . "', '" . __ ( "Medium") . "', '" . __ ( "High") . "'],\n" .
                            "  min: -10,\n" .
                            "  max: 10,\n" .
                            "  enabled: false,\n" .
                            "  tooltip: 'hide'\n" .
                            "});\n" .
                            "$('#extension_view_form').find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
                            "{\n" .
                            "  $($(e.target).attr ( 'href')).find ( 'input.tabslider').bootstrapSlider ( 'refresh', { useCurrentValue: true });\n" .
                            "});\n" .
                            "$('#extension_export_type').on ( 'change', function ( e)\n" .
                            "{\n" .
                            "  var extension = $('#extension_export').data ( 'extension');\n" .
                            "  switch ( $(this).val ())\n" .
                            "  {\n" .
                            "    case 'grandstream':\n" .
                            "      $('#extension_export_content').empty ().qrcode (\n" .
                            "      {\n" .
                            "        render: 'image',\n" .
                            "        minVersion: 1,\n" .
                            "        maxVersion: 40,\n" .
                            "        ecLevel: 'H',\n" .
                            "        size: 400,\n" .
                            "        fill: '#000000',\n" .
                            "        background: '#ffffff',\n" .
                            "        mode: 2,\n" .
                            "        label: 'VoIP Domain',\n" .
                            "        fontname: '\"Helvetica Neue\", Helvetica, Arial, sans-serif',\n" .
                            "        fontcolor: '#932092',\n" .
                            "        text: '<?xml version=\"1.0\" encoding=\"utf-8\"?><AccountConfig version=\"1\"><Account><RegisterServer>' + extension.Domain + '</RegisterServer><OutboundServer>' + extension.ServerIP + '</OutboundServer><UserID>' + extension.Name + '</UserID><AuthID>' + extension.Username + '</AuthID><AuthPass>' + extension.Password + '</AuthPass><AccountName>' + extension.Name + '</AccountName><DisplayName>' + extension.Name + '</DisplayName><Dialplan>{x+|*x+|*++}</Dialplan><RandomPort>0</RandomPort><SecOutboundServer></SecOutboundServer><Voicemail>*97</Voicemail></Account></AccountConfig>'\n" .
                            "      });\n" .
                            "      $('#extension_export_content').append ( '<br /><br /><a class=\"btn btn-info\" href=\"' + $('#extension_export_content img').attr ( 'src') + '\" download=\"VoIP Domain - ' + extension.Username + '.png\">" . __ ( "Download") . "</a>');\n" .
                            "      break;\n" .
                            "    case 'portsip':\n" .
                            "      $('#extension_export_content').empty ().qrcode (\n" .
                            "      {\n" .
                            "        render: 'image',\n" .
                            "        minVersion: 1,\n" .
                            "        maxVersion: 40,\n" .
                            "        ecLevel: 'H',\n" .
                            "        size: 400,\n" .
                            "        fill: '#000000',\n" .
                            "        background: '#ffffff',\n" .
                            "        mode: 2,\n" .
                            "        label: 'VoIP Domain',\n" .
                            "        fontname: '\"Helvetica Neue\", Helvetica, Arial, sans-serif',\n" .
                            "        fontcolor: '#932092',\n" .
                            "        text: '{\"display_name\":\"' + extension.Name + '\",\"sip_domain\":\"' + extension.Domain + '\",\"transports\":[{\"protocol\":\"UDP\",\"port\":' + extension.ServerPort + '}],\"pbx_public_ip\":\"' + extension.ServerIP + '\",\"pbx_private_ip\":\"' + extension.ServerIP + '\",\"email\":\"' + extension.Username + '@' + extension.Domain + '\",\"voicemail_number\":\"*999\",\"extension_number\":\"' + extension.Username + '\",\"extension_password\":\"' + extension.Password + '\",\"web_access_password\":\"' + extension.Password + '\"}'\n" .
                            "      });\n" .
                            "      $('#extension_export_content').append ( '<br /><br /><a class=\"btn btn-info\" href=\"' + $('#extension_export_content img').attr ( 'src') + '\" download=\"VoIP Domain - ' + extension.username + '.png\">" . __ ( "Download") . "</a>');\n" .
                            "      break;\n" .
                            "    case 'json':\n" .
                            "      $('#extension_export_content').empty ().html ( '<span style=\"width: 100%; display: inline-block; overflow-wrap: break-word\">{\"server_ip\":\"' + extension.ServerIP + '\",\"transports\":[{\"protocol\":\"UDP\",\"port\":' + extension.ServerPort + '}],\"domain\":\"' + extension.Domain + '\",\"display_name\":\"' + extension.Name + '\",\"username\":\"' + extension.Username + '\",\"password\":\"' + extension.Password + '\"}</span>');\n" .
                            "      $('#extension_export_content').append ( '<br /><br /><a class=\"btn btn-info\" href=\"data:text/json;base64,' + btoa ( $('#extension_export_content span').text ()) + '\" download=\"VoIP Domain - ' + extension.Username + '.json\">" . __ ( "Download") . "</a>');\n" .
                            "      break;\n" .
                            "    default:\n" .
                            "      $('#extension_export_content').empty ().html ( '<span><div class=\"divTable\"><div class=\"divTableBody\"><div class=\"divTableRow\"><div class=\"divTableCell\"><strong>" . __ ( "Server IP") . "</strong>: </div><div class=\"divTableCell\">' + extension.ServerIP + '\\n</div></div><div class=\"divTableRow\"><div class=\"divTableCell\"><strong>" . __ ( "Transports") . "</strong>:\\n  </div><div class=\"divTableCell\" style=\"padding: unset !important\"><div class=\"divTable\"><div class=\"divTableBody\"><div class=\"divTableRow\"><div class=\"divTableCell\"><strong>" . __ ( "Protocol") . "</strong>: </div><div class=\"divTableCell\">UDP </div></div><div class=\"divTableRow\"><div class=\"divTableCell\"><strong>" . __ ( "Port") . "</strong>: </div><div class=\"divTableCell\">' + extension.ServerPort + '\\n</div></div></div></div></div></div><div class=\"divTableRow\"><div class=\"divTableCell\"><strong>" . __ ( "Domain") . "</strong>: </div><div class=\"divTableCell\">' + extension.Domain + '\\n</div></div><div class=\"divTableRow\"><div class=\"divTableCell\"><strong>" . __ ( "Display name") . "</strong>: </div><div class=\"divTableCell\">' + extension.Name + '\\n</div></div><div class=\"divTableRow\"><div class=\"divTableCell\"><strong>" . __ ( "Username") . "</strong>: </div><div class=\"divTableCell\">' + extension.Username + '\\n</div></div><div class=\"divTableRow\"><div class=\"divTableCell\"><strong>" . __ ( "Password") . "</strong>: </div><div class=\"divTableCell\">' + extension.Password + '\\n</div></div></div></div></span>');\n" .
                            "      $('#extension_export_content').append ( '<br /><br /><a class=\"btn btn-info\" href=\"data:text/plain;base64,' + btoa ( $('#extension_export_content span').text ()) + '\" download=\"VoIP Domain - ' + extension.Username + '.txt\">" . __ ( "Download") . "</a>');\n" .
                            "      break;\n" .
                            "  }\n" .
                            "});\n" .
                            "$('#extension_view_form .btn-showpass').on ( 'mousedown', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $(this).parent ().parent ().find ( 'input').attr ( 'type', 'text');\n" .
                            "}).on ( 'mouseup mouseout', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $(this).parent ().parent ().find ( 'input').attr ( 'type', 'password');\n" .
                            "});\n" .
                            "$('#extension_view_form .btn-copypass').on ( 'click', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  var target = $(this).parent ().parent ().find ( 'input');\n" .
                            "  $(target).removeAttr ( 'disabled').attr ( 'readonly', 'readonly').attr ( 'type', 'text');\n" .
                            "  $(target).select ();\n" .
                            "  document.execCommand ( 'copy');\n" .
                            "  $(this).focus ();\n" .
                            "  $(target).removeAttr ( 'readonly').attr ( 'disabled', 'disabled').attr ( 'type', 'password');\n" .
                            "  new PNotify ( { title: '" . __ ( "Extension view") . "', text: '" . __ ( "Password copyed to clipboard!") . "', type: 'success'});\n" .
                            "});\n" .
                            "$('#extension_view_accounts').on ( 'click', '.btn-export', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $(this).tooltip ( 'hide');\n" .
                            "  var l = Ladda.create ( this);\n" .
                            "  l.start ();\n" .
                            "  VoIP.rest ( '/extensions/' + encodeURIComponent ( VoIP.parameters.id) + '/account/' + encodeURIComponent ( $(this).data ( 'id')), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
                            "  {\n" .
                            "    $('#extension_export').data ( 'extension', data);\n" .
                            "    $('#extension_export_type').val ( 'grandstream').trigger ( 'change');\n" .
                            "    $('#extension_export').modal ( 'show');\n" .
                            "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
                            "  {\n" .
                            "    new PNotify ( { title: '" . __ ( "Extension export") . "', text: '" . __ ( "Error retrieving extension account!") . "', type: 'error'});\n" .
                            "  }).always ( function ()\n" .
                            "  {\n" .
                            "    l.stop ();\n" .
                            "  });\n" .
                            "});\n";

  return $buffer;
}

/**
 * Function to generate the extension phone edit subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_phones_edit_subpage ( $buffer, $parameters)
{
  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/css/bootstrap-toggle.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/css/bootstrap-slider.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/css/tabdrop.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "jquery-labelauty", "src" => "/vendors/jquery-labelauty/source/jquery-labelauty.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-toggle", "src" => "/vendors/bootstrap-toggle/js/bootstrap-toggle.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/bootstrap-slider.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-tabdrop", "src" => "/vendors/bootstrap-tabdrop/js/bootstrap-tabdrop.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-labelauty", "src" => "/vendors/jquery-labelauty/source/jquery-labelauty.js", "dep" => array ()));

  /**
   * Phone options panel
   */
  $output = "";

  // Add extension email field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_email\" class=\"control-label col-xs-2\">" . __ ( "E-mail") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Email\" class=\"form-control\" id=\"extension_edit_email\" placeholder=\"" . __ ( "Extension e-mail") . "\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension group selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_group\" class=\"control-label col-xs-2\">" . __ ( "Group") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Group\" id=\"extension_edit_group\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension group") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension capture groups selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_captures\" class=\"control-label col-xs-2\">" . __ ( "Captures") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Captures\" id=\"extension_edit_captures\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension capture groups") . "\" multiple=\"multiple\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension password field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_password\" class=\"control-label col-xs-2\">" . __ ( "Password") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <div class=\"input-group\">\n";
  $output .= "            <input type=\"text\" name=\"Password\" id=\"extension_edit_password\" class=\"form-control\" placeholder=\"" . __ ( "Extension password") . "\" />\n";
  $output .= "            <div class=\"input-group-btn\">\n";
  $output .= "              <button class=\"btn btn-default btn-random\" type=\"button\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Generate random password") . "\"><i class=\"fa fa-random\"></i></button>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension transhipments selectors
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_transhipments\" class=\"control-label col-xs-2\">" . __ ( "Transhipment") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Transhipments\" id=\"extension_edit_transhipments\" class=\"form-control\" data-placeholder=\"" . __ ( "Transhipment extensions") . "\" multiple=\"multiple\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-basic"] = array ( "type" => "phone", "label" => __ ( "Phone"), "html" => $output);

  /**
   * Permissions panel
   */
  $output = "";

  // Add location levels headers
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <span class=\"col-xs-2\"></span>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 column-label center\">" . __ ( "Local") . "</div>\n";
  $output .= "          <div class=\"col-xs-4 column-label center\">" . __ ( "Interstate") . "</div>\n";
  $output .= "          <div class=\"col-xs-4 column-label center\">" . __ ( "International") . "</div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add landline call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Landline") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Interstate\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Interstate\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_Interstate\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Landline_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Landline_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add mobile call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Mobile") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Interstate\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Interstate\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_Interstate\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Mobile_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add marine call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Marine") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Interstate\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Interstate\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_Interstate\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Marine_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Marine_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add toll free call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Toll free") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4\">\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Tollfree_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add premium rate numbers call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Premium rate numbers") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"PRN_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4\">\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"PRN_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"PRN_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add satellite call permissions
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Satellite") . "</label>\n";
  $output .= "        <div class=\"col-xs-10 col-nopadding\">\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_Local\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_Local\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_Local\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4\">\n";
  $output .= "          </div>\n";
  $output .= "          <div class=\"col-xs-4 center\">\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_International\" value=\"y\" class=\"form-control\" data-labelauty=\"" . __ ( "Permitted") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_International\" value=\"p\" class=\"form-control\" data-labelauty=\"" . __ ( "Passworded") . "\" />\n";
  $output .= "            <input type=\"radio\" name=\"Satellite_International\" value=\"n\" class=\"form-control\" data-labelauty=\"" . __ ( "Blocked") . "\" />\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-permissions"] = array ( "type" => "phone", "label" => __ ( "Permissions"), "html" => $output);

  /**
   * Accounts panel
   */
  $output = "";

  // Add extension account controls
  $output .= "      <div id=\"extension_edit_accounts\">\n";
  $output .= "        <label class=\"control-label col-xs-2\"><span>" . __ ( "Accounts") . "<br /><button class=\"btn btn-success btn-addaccount\" role=\"button\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . __ ( "Add new account") . "\"><i class=\"fa fa-plus\"></i></button></span></label>\n";
  $output .= "        <ul class=\"nav nav-tabs col-xs-10\" role=\"tablist\"></ul>\n";
  $output .= "        <div class=\"tab-content col-xs-offset-2 col-xs-10\">\n";
  $output .= "          <br />\n";
  $output .= "          <div class=\"tab-pane hide\" id=\"extension_edit_account_template\">\n";

  // Add equipment type selector
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"extension_edit_account__ID__type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"Account__ID__Type\" id=\"extension_edit_account__ID__type\" class=\"form-control\" data-placeholder=\"" . __ ( "Equipment type") . "\"><option value=\"\"></option></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add MAC address field
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"extension_edit_account__ID__mac\" class=\"control-label col-xs-2\">" . __ ( "MAC address") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"Account__ID__MAC\" class=\"form-control\" id=\"extension_edit_account__ID__mac\" placeholder=\"" . __ ( "MAC address") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-accounts"] = array ( "type" => "phone", "label" => __ ( "Accounts"), "html" => $output);

  /**
   * Extra options panel
   */
  $output = "";

  // Add cost center selector
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_costcenter\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"CostCenter\" id=\"extension_edit_costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension cost center") . "\"><option value=\"\"></option></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension voicemail option
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_voicemail\" class=\"control-label col-xs-2\">" . __ ( "Voice mail") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"VoiceMail\" id=\"extension_edit_voicemail\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add monitor option switch
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_monitor\" class=\"control-label col-xs-2\">" . __ ( "Monitor") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Monitor\" id=\"extension_edit_monitor\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add reception volume slider
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_rxvol\" class=\"control-label col-xs-2\">" . __ ( "RX volume") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"VolRX\" id=\"extension_edit_volrx\" class=\"form-control tabslider\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add transmission volume slider
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_txvol\" class=\"control-label col-xs-2\">" . __ ( "TX volume") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"VolTX\" id=\"extension_edit_voltx\" class=\"form-control tabslider\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add extension hint selectors
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"extension_edit_hints\" class=\"control-label col-xs-2\">" . __ ( "Hints") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <select name=\"Hints\" id=\"extension_edit_hints\" class=\"form-control\" data-placeholder=\"" . __ ( "Hints extensions") . "\" multiple=\"multiple\"></select>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["phone-extras"] = array ( "type" => "phone", "label" => __ ( "Extras"), "html" => $output);

  /**
   * Add add form JavaScript code
   */
  $buffer["js"]["onshow"]["phone"] = "      $('#extension_edit_extension').val ( data.Extension);\n" .
                                     "      $('#extension_edit_name').val ( data.Name);\n" .
                                     "      $('#extension_edit_email').val ( data.Email);\n" .
                                     "      $('#extension_edit_group').append ( $('<option>', { value: data.Group.ID, text: data.Group.Description})).val ( data.Group.ID).trigger ( 'change');\n" .
                                     "      var ids = [];\n" .
                                     "      for ( var id in data.Captures)\n" .
                                     "      {\n" .
                                     "        if ( ! data.Captures.hasOwnProperty ( id))\n" .
                                     "        {\n" .
                                     "          continue;\n" .
                                     "        }\n" .
                                     "        ids.push ( data.Captures[id].ID);\n" .
                                     "      }\n" .
                                     "      $('#extension_edit_captures').val ( ids).trigger ( 'change');\n" .
                                     "      $('#extension_edit_voicemail').bootstrapToggle ( data.VoiceMail ? 'on' : 'off');\n" .
                                     "      $('#extension_edit_password').val ( data.Password);\n" .
                                     "      var ids = [];\n" .
                                     "      for ( var id in data.Transhipments)\n" .
                                     "      {\n" .
                                     "        if ( ! data.Transhipments.hasOwnProperty ( id))\n" .
                                     "        {\n" .
                                     "          continue;\n" .
                                     "        }\n" .
                                     "        ids.push ( data.Transhipments[id].ID);\n" .
                                     "      }\n" .
                                     "      $('#extension_edit_transhipments').val ( ids).trigger ( 'change');\n" .
                                     "      $('#extension_edit_accounts').data ( 'accounts', data.Accounts);\n" .
                                     "      $('#extension_edit_accounts ul.nav-tabs').tabdrop ( 'layout');\n" .
                                     "      $('#extension_edit_costcenter').val ( data.CostCenter.ID).trigger ( 'change');\n" .
                                     "      $('#extension_edit_monitor').bootstrapToggle ( data.Monitor ? 'on' : 'off');\n" .
                                     "      $('#extension_edit_volrx').data ( 'value', data.VolRX);\n" .
                                     "      $('#extension_edit_voltx').data ( 'value', data.VolTX);\n" .
                                     "      for ( var type in data.Permissions)\n" .
                                     "      {\n" .
                                     "        for ( var area in data.Permissions[type])\n" .
                                     "        {\n" .
                                     "          $('#extension_edit_form input[name=\"' + type + '_' + area + '\"][value=\"' + data.Permissions[type][area] + '\"]').prop ( 'checked', 'checked');\n" .
                                     "        }\n" .
                                     "      }\n" .
                                     "      var ids = [];\n" .
                                     "      for ( var id in data.Hints)\n" .
                                     "      {\n" .
                                     "        if ( ! data.Hints.hasOwnProperty ( id))\n" .
                                     "        {\n" .
                                     "          continue;\n" .
                                     "        }\n" .
                                     "        ids.push ( data.Hints[id].ID);\n" .
                                     "      }\n" .
                                     "      $('#extension_edit_hints').val ( ids).trigger ( 'change');\n";
  $buffer["js"]["init"][] = "$('#extension_edit_group,#extension_edit_captures').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  data: VoIP.APIsearch ( { path: '/groups', Fields: 'ID,Description', formatText: '%Description%'})\n" .
                            "});\n" .
                            "$('#extension_edit_form input[type=checkbox]').bootstrapToggle ( { on: '" . __ ( "Yes") . "', off: '" . __ ( "No") . "'});\n" .
                            "$('#extension_edit_password').mask ( '000000');\n" .
                            "$('#extension_edit_transhipments,#extension_edit_hints').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  data: VoIP.APIsearch ( { path: '/extensions', Fields: 'ID,Description,Number', formatText: '%Description% (%Number%)', data: { Except: VoIP.parameters.id}})\n" .
                            "});\n" .
                            "$('#extension_edit_costcenter').select2 (\n" .
                            "{\n" .
                            "  allowClear: true,\n" .
                            "  data: VoIP.APIsearch ( { path: '/costcenters', Fields: 'ID,Description,Code', formatText: '%Description% (%Code%)'})\n" .
                            "});\n" .
                            "$('#extension_edit_form input[type=radio]').labelauty ();\n" .
                            "VoIP.rest ( '/config/permissions', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
                            "{\n" .
                            "  for ( var type in data)\n" .
                            "  {\n" .
                            "    for ( var area in data[type])\n" .
                            "    {\n" .
                            "      switch ( data[type][area])\n" .
                            "      {\n" .
                            "        case 'p':\n" .
                            "          $('#extension_edit_form input[name=\"' + type + '_' + area + '\"][value=\"y\"]').prop ( 'disabled', 'disabled');\n" .
                            "          break;\n" .
                            "        case 'n':\n" .
                            "          $('#extension_edit_form input[name=\"' + type + '_' + area + '\"][value=\"y\"]').prop ( 'disabled', 'disabled');\n" .
                            "          $('#extension_edit_form input[name=\"' + type + '_' + area + '\"][value=\"p\"]').prop ( 'disabled', 'disabled');\n" .
                            "          break;\n" .
                            "      }\n" .
                            "    }\n" .
                            "  }\n" .
                            "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
                            "{\n" .
                            "  new PNotify ( { title: '" . __ ( "Extension edition") . "', text: '" . __ ( "Error retrieving permissions!") . "', type: 'error'});\n" .
                            "});\n" .
                            "$('#extension_edit_volrx,#extension_edit_voltx').bootstrapSlider (\n" .
                            "{\n" .
                            "  ticks: [ -10, 0, 10],\n" .
                            "  ticks_labels: [ '" . __ ( "Low") . "', '" . __ ( "Medium") . "', '" . __ ( "High") . "'],\n" .
                            "  min: -10,\n" .
                            "  max: 10,\n" .
                            "  value: 0,\n" .
                            "  enabled: true,\n" .
                            "  tooltip: 'hide'\n" .
                            "});\n" .
                            "$('#extension_edit_volrx').bootstrapSlider ( 'setValue', $('#extension_edit_volrx').data ( 'value'));\n" .
                            "$('#extension_edit_voltx').bootstrapSlider ( 'setValue', $('#extension_edit_voltx').data ( 'value'));\n" .
                            "$('#extension_edit_form').find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
                            "{\n" .
                            "  $($(e.target).attr ( 'href')).find ( 'input.tabslider').bootstrapSlider ( 'refresh', { useCurrentValue: true });\n" .
                            "});\n" .
                            "$('button.btn-random').on ( 'click', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $(this).parent ().parent ().find ( 'input').val ( Math.floor ( 100000 + Math.random ( ) * 900000));\n" .
                            "});\n" .
                            "$('#extension_edit_group').data ( 'last', '').on ( 'select2:select', function ( event)\n" .
                            "{\n" .
                            "  var captures = $('#extension_edit_captures').val ();\n" .
                            "  if ( typeof ( captures) === 'undefined')\n" .
                            "  {\n" .
                            "    captures = new Array ();\n" .
                            "  }\n" .
                            "  if ( $(this).data ( 'last') != '' && $.inArray ( $(this).data ( 'last'), captures) != -1)\n" .
                            "  {\n" .
                            "    captures.splice ( $.inArray ( $(this).data ( 'last'), captures), 1);\n" .
                            "  }\n" .
                            "  if ( $.inArray ( '', captures) == -1)\n" .
                            "  {\n" .
                            "    captures.push ( $(this).val ());\n" .
                            "  }\n" .
                            "  $(this).data ( 'last', $(this).val ());\n" .
                            "  $('#extension_edit_captures').val ( captures).trigger ( 'change');\n" .
                            "}).on ( 'select2:unselect', function ( event)\n" .
                            "{\n" .
                            "  var captures = $('#extension_edit_captures').val ();\n" .
                            "  if ( $(this).val () != '' && $.inArray ( $(this).val (), captures) != -1)\n" .
                            "  {\n" .
                            "    captures.splice ( $.inArray ( $(this).val (), captures), 1);\n" .
                            "  }\n" .
                            "  $('#extension_edit_group').data ( 'last', '');\n" .
                            "  $('#extension_edit_captures').val ( captures).trigger ( 'change');\n" .
                            "});\n" .
                            "VoIP.rest ( '/equipments', 'GET', { Fields: 'ID,Vendor,Model,AutoProvision'}).done ( function ( result, textStatus, jqXHR)\n" .
                            "{\n" .
                            "  $('#extension_edit_accounts').data ( 'types', result);\n" .
                            "  var ret = [];\n" .
                            "  for ( var x in result)\n" . 
                            "  {\n" . 
                            "    ret.push (\n" . 
                            "    {\n" . 
                            "      id: result[x].ID,\n" . 
                            "      text: result[x].Vendor + ' ' + result[x].Model\n" .
                            "    });\n" . 
                            "  }\n" . 
                            "  $('#extension_edit_accounts').data ( 'typesformated', ret);\n" . 
                            "  var accounts = $('#extension_edit_accounts').data ( 'accounts');\n" .
                            "  for ( var id in accounts)\n" .
                            "  {\n" .
                            "    if ( ! accounts.hasOwnProperty ( id))\n" .
                            "    {\n" .
                            "      continue;\n" .
                            "    }\n" .
                            "    $('#extension_edit_accounts ul.nav-tabs').trigger ( 'addTab', accounts[id]);\n" .
                            "  }\n" .
                            "  $('#extension_edit_accounts ul.nav-tabs li:not(.hide) a:first').tab ( 'show');\n" .
                            "  $('#extension_edit_account_0').removeClass ( 'hide').addClass ( 'active');\n" .
                            "  $('#extension_edit_accounts ul.nav-tabs li i').remove ();\n" .
                            "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
                            "{\n" . 
                            "  new PNotify ( { title: '" . __ ( "Extension addition") . "', text: '" . __ ( "Error retrieving equipment types!") . "', type: 'error'});\n" .
                            "});\n" .
                            "$('#extension_edit_accounts').on ( 'click', 'i.tab-del', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  if ( $(this).parent ().parent ().hasClass ( 'active'))\n" .
                            "  {\n" .
                            "    $('#extension_edit_accounts ul.nav-tabs li:not(.hide):first a').tab ( 'show');\n" .
                            "  }\n" .
                            "  $('#extension_edit_account_' + $(this).data ( 'tabid')).remove ();\n" .
                            "  $(this).parent ().parent ().remove ();\n" .
                            "  var x = 1;\n" .
                            "  $('#extension_edit_accounts ul.nav-tabs li:not(.hide):not(:eq(0)) a').each ( function ( idx, li)\n" .
                            "  {\n" .
                            "    x++;\n" .
                            "    $(li).html ( $(li).html ().replace ( /[0-9]+\&nbsp;/, x + '&nbsp;'));\n" .
                            "  });\n" .
                            "  $('#extension_edit_accounts ul.nav-tabs').tabdrop ( 'layout');\n" .
                            "  $('#extension_edit_accounts button.btn-addaccount').prop ( 'disabled', false);\n" .
                            "});\n" .
                            "$('#extension_edit_accounts ul.nav-tabs').data ( 'tabid', 0).on ( 'addTab', function ()\n" .
                            "{\n" .
                            "  var account = ( arguments.length == 2 ? arguments[1] : new Object ());\n" .
                            "  $(this).data ( 'tabid', $(this).data ( 'tabid') + 1);\n" .
                            "  $('#extension_edit_accounts ul.nav-tabs').append ( '<li><a href=\"#extension_edit_account_' + $(this).data ( 'tabid') + '\" role=\"tab\" data-toggle=\"tab\">' + ( $('#extension_edit_accounts a[data-toggle=\"tab\"]').length + 1) + ( $('#extension_edit_accounts a[data-toggle=\"tab\"]').length >= 1 ? '&nbsp;<i class=\"fa fa-times tab-del\" data-tabid=\"' + $(this).data ( 'tabid') + '\"></i>' : '') + '</a></li>');\n" .
                            "  $('#extension_edit_accounts div.tab-content').append ( '<div class=\"tab-pane\" id=\"extension_edit_account_' + $(this).data ( 'tabid') + '\"></div>');\n" .
                            "  $('#extension_edit_account_' + $(this).data ( 'tabid')).html ( $('#extension_edit_account_template').html ().replace ( /_ID_/mg, $(this).data ( 'tabid')));\n" .
                            "  $('#extension_edit_account_' + $(this).data ( 'tabid') + '_type').select2 ( { allowClear: true, data: $('#extension_edit_accounts').data ( 'typesformated')}).on ( 'select2:select', function ( event)\n" .
                            "  {\n" .
                            "    $('#' + $(this).attr ( 'id').replace ( /type$/, 'mac')).val ( '').attr ( 'disabled', 'disabled');\n" .
                            "    for ( var sid in $('#extension_edit_accounts').data ( 'types'))\n" .
                            "    {\n" .
                            "      if ( $('#extension_edit_accounts').data ( 'types')[sid].ID == $(this).val ())\n" .
                            "      {\n" .
                            "        if ( $('#extension_edit_accounts').data ( 'types')[sid].AutoProvision)\n" .
                            "        {\n" .
                            "          $('#' + $(this).attr ( 'id').replace ( /type$/, 'mac')).removeAttr ( 'disabled').focus ();\n" .
                            "        }\n" .
                            "      }\n" .
                            "    }\n" .
                            "  }).on ( 'select2:unselect', function ( event)\n" .
                            "  {\n" .
                            "    $('#' + $(this).attr ( 'id').replace ( /type$/, 'mac')).val ( '').attr ( 'disabled', 'disabled');\n" .
                            "  }).val ( account.hasOwnProperty ( 'Type') ? account.Type.ID : '');\n" .
                            "  $('#extension_edit_account_' + $(this).data ( 'tabid') + '_mac').mask ( 'xx:xx:xx:xx:xx:xx', { 'translation': { x: { pattern: /[A-Fa-f0-9]/}}}).val ( account.hasOwnProperty ( 'MAC') ? account.MAC : '');\n" .
                            "  if ( account.hasOwnProperty ( 'MAC'))\n" .
                            "  {\n" .
                            "    $('#extension_edit_account_' + $(this).data ( 'tabid') + '_mac').removeAttr ( 'disabled');\n" .
                            "  }\n" .
                            "  $('#extension_edit_accounts ul.nav-tabs a:last').tab ( 'show');\n" .
                            "  $('#extension_edit_accounts ul.nav-tabs').tabdrop ( 'layout');\n" .
                            "  if ( $('#extension_edit_accounts a[data-toggle=\"tab\"]').length == 10)\n" .
                            "  {\n" .
                            "    $('#extension_edit_accounts button.btn-addaccount').prop ( 'disabled', true);\n" .
                            "  }\n" .
                            "});\n" .
                            "$('#extension_edit_accounts button.btn-addaccount').prop ( 'disabled', false).on ( 'click', function ( event)\n" .
                            "{\n" .
                            "  event && event.preventDefault ();\n" .
                            "  $('#extension_edit_accounts ul.nav-tabs').trigger ( 'addTab');\n" .
                            "});\n" .
                            "$('#extension_edit_form').on ( 'formFilter', function ()\n" .
                            "{\n" .
                            "  var formData = $('#extension_edit_form').data ( 'formData');\n" .
                            "  if ( typeof ( formData.Captures) == 'string')\n" .
                            "  {\n" .
                            "    var tmp = formData.Captures;\n" .
                            "    formData.Captures = new Array ();\n" .
                            "    formData.Captures.push ( tmp);\n" .
                            "  }\n" .
                            "  if ( typeof ( formData.Transhipments) == 'string')\n" .
                            "  {\n" .
                            "    var tmp = formData.Transhipments;\n" .
                            "    formData.Transhipments = new Array ();\n" .
                            "    formData.Transhipments.push ( tmp);\n" .
                            "  }\n" .
                            "  if ( typeof ( formData.Hints) == 'string')\n" .
                            "  {\n" .
                            "    var tmp = formData.Hints;\n" .
                            "    formData.Hints = new Array ();\n" .
                            "    formData.Hints.push ( tmp);\n" .
                            "  }\n" .
                            "  formData.Permissions = new Object ();\n" .
                            "  formData.Permissions.Landline = new Object ();\n" .
                            "  formData.Permissions.Landline.Local = formData.Landline_Local;\n" .
                            "  delete ( formData.Landline_Local);\n" .
                            "  formData.Permissions.Landline.Interstate = formData.Landline_Interstate;\n" .
                            "  delete ( formData.Landline_Interstate);\n" .
                            "  formData.Permissions.Landline.International = formData.Landline_International;\n" .
                            "  delete ( formData.Landline_International);\n" .
                            "  formData.Permissions.Mobile = new Object ();\n" .
                            "  formData.Permissions.Mobile.Local = formData.Mobile_Local;\n" .
                            "  delete ( formData.Mobile_Local);\n" .
                            "  formData.Permissions.Mobile.Interstate = formData.Mobile_Interstate;\n" .
                            "  delete ( formData.Mobile_Interstate);\n" .
                            "  formData.Permissions.Mobile.International = formData.Mobile_International;\n" .
                            "  delete ( formData.Mobile_International);\n" .
                            "  formData.Permissions.Marine = new Object ();\n" .
                            "  formData.Permissions.Marine.Local = formData.Marine_Local;\n" .
                            "  delete ( formData.Marine_Local);\n" .
                            "  formData.Permissions.Marine.Interstate = formData.Marine_Interstate;\n" .
                            "  delete ( formData.Marine_Interstate);\n" .
                            "  formData.Permissions.Marine.International = formData.Marine_International;\n" .
                            "  delete ( formData.Marine_International);\n" .
                            "  formData.Permissions.Tollfree = new Object ();\n" .
                            "  formData.Permissions.Tollfree.Local = formData.Tollfree_Local;\n" .
                            "  delete ( formData.Tollfree_Local);\n" .
                            "  formData.Permissions.Tollfree.International = formData.Tollfree_International;\n" .
                            "  delete ( formData.Tollfree_International);\n" .
                            "  formData.Permissions.PRN = new Object ();\n" .
                            "  formData.Permissions.PRN.Local = formData.PRN_Local;\n" .
                            "  delete ( formData.PRN_Local);\n" .
                            "  formData.Permissions.PRN.International = formData.PRN_International;\n" .
                            "  delete ( formData.PRN_International);\n" .
                            "  formData.Permissions.Satellite = new Object ();\n" .
                            "  formData.Permissions.Satellite.Local = formData.Satellite_Local;\n" .
                            "  delete ( formData.Satellite_Local);\n" .
                            "  formData.Permissions.Satellite.International = formData.Satellite_International;\n" .
                            "  delete ( formData.Satellite_International);\n" .
                            "  delete ( formData.Account__ID__Type);\n" .
                            "  delete ( formData.Account__ID__MAC);\n" .
                            "  formData.Accounts = new Array ();\n" .
                            "  for ( var x = 1; x <= $('#extension_edit_accounts ul.nav-tabs').data ( 'tabid'); x++)\n" .
                            "  {\n" .
                            "    if ( $('#extension_edit_account_' + x + '_type').val ())\n" .
                            "    {\n" .
                            "      var tmp = new Object ();\n" .
                            "      tmp.Reference = x;\n" .
                            "      tmp.Type = $('#extension_edit_account_' + x + '_type').val ();\n" .
                            "      tmp.MAC = $('#extension_edit_account_' + x + '_mac').val ();\n" .
                            "      formData.Accounts.push ( tmp);\n" .
                            "      delete ( tmp);\n" .
                            "    }\n" .
                            "    delete ( formData['Account_' + x + '_Type']);\n" .
                            "    delete ( formData['Account_' + x + '_MAC']);\n" .
                            "  }\n" .
                            "  formData.Monitor = $('#extensions_edit_monitor').prop ( 'checked');\n" .
                            "  formData.VoiceMail = $('#extensions_edit_voicemail').prop ( 'checked');\n" .
                            "  $('#extension_edit_form').data ( 'formData', formData);\n" .
                            "});\n";

  return $buffer;
}
?>
