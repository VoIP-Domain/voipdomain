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
 * related to country database of Egypt.
 *
 * Reference: https://www.itu.int/oth/T020200003E/en (2017-10-31)
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
 * E.164 Egypt country hook
 */
framework_add_filter ( "e164_identify_country_EGY", "e164_identify_country_EGY");

/**
 * E.164 Egyptian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "EGY" (code for Egypt). This hook
 * will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_EGY ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Egypt
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+20")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 8 digits SN
   */
  $prefixes = array (
    "15" => "Telecom Egypt",
    "12" => "Orange",
    "11" => "Etisalat",
    "10" => "Vodafone"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "20", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Egypt", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+20 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1 or 2 digits NDC and 8 or 9 digits SN
   */
  $prefixes = array (
    "97" => array ( "area" => "Aswan", "minimum" => 9, "maximum" => 9),
    "96" => array ( "area" => "Quena", "minimum" => 9, "maximum" => 9),
    "95" => array ( "area" => "Luxor", "minimum" => 9, "maximum" => 9),
    "93" => array ( "area" => "Souhag", "minimum" => 9, "maximum" => 9),
    "92" => array ( "area" => "El wadi El Gadeed", "minimum" => 9, "maximum" => 9),
    "88" => array ( "area" => "Assiut", "minimum" => 9, "maximum" => 9),
    "86" => array ( "area" => "Elminia", "minimum" => 9, "maximum" => 9),
    "84" => array ( "area" => "Elfayoum", "minimum" => 9, "maximum" => 9),
    "82" => array ( "area" => "Beni Suef", "minimum" => 9, "maximum" => 9),
    "69" => array ( "area" => "South Sinai", "minimum" => 9, "maximum" => 9),
    "68" => array ( "area" => "North Sinai", "minimum" => 9, "maximum" => 9),
    "66" => array ( "area" => "Port Said", "minimum" => 9, "maximum" => 9),
    "65" => array ( "area" => "El Red Sea", "minimum" => 9, "maximum" => 9),
    "64" => array ( "area" => "Ismailia", "minimum" => 9, "maximum" => 9),
    "62" => array ( "area" => "ElSuez", "minimum" => 9, "maximum" => 9),
    "57" => array ( "area" => "Damietta", "minimum" => 9, "maximum" => 8),
    "55" => array ( "area" => "Al Sharqia", "minimum" => 9, "maximum" => 9),
    "50" => array ( "area" => "El Dakahlia", "minimum" => 9, "maximum" => 9),
    "48" => array ( "area" => "El Menufia", "minimum" => 9, "maximum" => 9),
    "47" => array ( "area" => "Kafr el-Sheikh", "minimum" => 9, "maximum" => 9),
    "46" => array ( "area" => "Mersa Matrouh", "minimum" => 9, "maximum" => 9),
    "45" => array ( "area" => "Elbehera", "minimum" => 9, "maximum" => 9),
    "40" => array ( "area" => "Elgharbia", "minimum" => 9, "maximum" => 9),
    "15" => array ( "area" => "10th of Ramadan", "minimum" => 8, "maximum" => 9),
    "13" => array ( "area" => "Elqalubia", "minimum" => 9, "maximum" => 9),
    "3" => array ( "area" => "Alexandria", "minimum" => 8, "maximum" => 8),
    "2" => array ( "area" => "Cairo", "minimum" => 9, "maximum" => 9)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 3 + $data["minimum"] && strlen ( $parameters["Number"]) <= 3 + $data["maximum"])
    {
      if ( strlen ( $prefix) == 1)
      {
        $callformats = array ( "Local" => "0" . substr ( $parameters["Number"], 3, 1) . "-" . substr ( $parameters["Number"], 4, 4) . "-" . substr ( $parameters["Number"], 8), "International" => "+20 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8));
      } else {
        $callformats = array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+20 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8));
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "20", "NDC" => (string) $prefix, "Country" => "Egypt", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Egyptian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
