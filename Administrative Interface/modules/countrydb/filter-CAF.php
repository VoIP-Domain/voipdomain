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
 * VoIP Domain country database module filters. This module add the filter calls
 * related to country database of Central African Republic.
 *
 * Reference: https://www.itu.int/oth/T0202000028/en (2008-05-09)
 *
 * Glossary:
 *  CC - Country Code
 *  NDC - National Destination Code (also known as area code)
 *  N(S)N - National (Significant) Number
 *  SN - Subscriber Number
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CountryDB
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * E.164 Central African Republic country hook
 */
framework_add_filter ( "e164_identify_country_CAF", "e164_identify_country_CAF");

/**
 * E.164 Central African Republic area number identification hook. This hook is
 * an e164_identify sub hook, called when the ISO3166 Alpha3 are "CAF" (code for
 * Central African Republic). This hook will verify if phone number is valid,
 * returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_CAF ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Central African Republic
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+236")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Central African Republic has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "7789" => "Nationlink",
    "7788" => "Nationlink",
    "7759" => "Nationlink",
    "7749" => "Nationlink",
    "7748" => "Nationlink",
    "7744" => "Nationlink",
    "7709" => "Nationlink",
    "7708" => "Nationlink",
    "7706" => "Nationlink",
    "7558" => "Telecel",
    "7557" => "Telecel",
    "7556" => "Telecel",
    "7555" => "Telecel",
    "7554" => "Telecel",
    "7550" => "Telecel",
    "7520" => "Telecel",
    "7505" => "Telecel",
    "7504" => "Telecel",
    "7503" => "Telecel",
    "7099" => "Acell RCA",
    "7098" => "Acell RCA",
    "7097" => "Acell RCA",
    "7096" => "Acell RCA",
    "7095" => "Acell RCA",
    "7090" => "Acell RCA",
    "7085" => "Acell RCA",
    "7080" => "Acell RCA",
    "7046" => "Acell RCA",
    "7045" => "Acell RCA",
    "7040" => "Acell RCA",
    "7002" => "Acell RCA",
    "7001" => "Acell RCA",
    "72" => "Orange"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "236", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Central African Republic", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+236 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "8522" => array ( "area" => "", "operator" => "Socatel"),
    "2291" => array ( "area" => "", "operator" => "Socatel"),
    "2281" => array ( "area" => "", "operator" => "Socatel"),
    "2251" => array ( "area" => "", "operator" => "Socatel"),
    "2241" => array ( "area" => "", "operator" => "Socatel"),
    "2231" => array ( "area" => "", "operator" => "Socatel"),
    "2221" => array ( "area" => "Berberati", "operator" => "Socatel"),
    "2165" => array ( "area" => "Bangui", "operator" => "Socatel"),
    "2162" => array ( "area" => "Bangui", "operator" => "Socatel"),
    "2161" => array ( "area" => "Bangui", "operator" => "Socatel")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "236", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Central African Republic", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+236 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Central African
   * Republic phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
