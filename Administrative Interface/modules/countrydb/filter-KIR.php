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
 * related to country database of Kiribati.
 *
 * Reference: https://www.itu.int/oth/T0202000071/en (2017-08-14)
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
 * E.164 Kiribati country hook
 */
framework_add_filter ( "e164_identify_country_KIR", "e164_identify_country_KIR");

/**
 * E.164 Kiribatian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "KIR" (code for
 * Kiribati). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_KIR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Kiribati
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+686")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 5 or 8 digits SN
   */
  $prefixes = array (
    "73140" => array ( "area" => "** ATHKL Staff Mobile", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "7300" => array ( "area" => "", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "7301" => array ( "area" => "", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "7302" => array ( "area" => "", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "7303" => array ( "area" => "", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "7304" => array ( "area" => "", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "7305" => array ( "area" => "", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "731" => array ( "area" => "Tarawa", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "732" => array ( "area" => "Tarawa", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "733" => array ( "area" => "Abaiang", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "734" => array ( "area" => "Marakei", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "735" => array ( "area" => "Butaritari", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "736" => array ( "area" => "Makin", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "737" => array ( "area" => "Banaba", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "738" => array ( "area" => "Maiana", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "739" => array ( "area" => "Kuria", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "740" => array ( "area" => "Aranuka", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "741" => array ( "area" => "Abemama", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "742" => array ( "area" => "Nonouti", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "743" => array ( "area" => "Tabiteuea North", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "744" => array ( "area" => "Tabiteuea South", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "745" => array ( "area" => "Onotoa", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "746" => array ( "area" => "Beru", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "747" => array ( "area" => "Nikunau", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "748" => array ( "area" => "Tamana", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "749" => array ( "area" => "Arorae", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "783" => array ( "area" => "Tabuaeran (Fanning)", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "784" => array ( "area" => "Teraina (Washington)", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "785" => array ( "area" => "Kanton", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "72" => array ( "area" => "Tarawa", "operator" => "Amalgamating Telecommunication Holdings Kiribati Ltd.", "digits" => 8),
    "29" => array ( "area" => "", "operator" => "", "digits" => 5),
    "9" => array ( "area" => "", "operator" => "", "digits" => 5),
    "7" => array ( "area" => "", "operator" => "Telecom Services Kiribati Ltd.", "digits" => 8),
    "6" => array ( "area" => "", "operator" => "", "digits" => 5)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["digits"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "686", "NDC" => "", "Country" => "Kiribati", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+686 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 3 digits SN
   */
  $prefixes = array (
    "85" => array ( "area" => "Kanton", "city" => "", "operator" => ""),
    "84" => array ( "area" => "Washington (Teraina)", "city" => "", "operator" => ""),
    "83" => array ( "area" => "Fanning (Tabuaeran)", "city" => "", "operator" => ""),
    "82" => array ( "area" => "Kiritimati", "city" => "", "operator" => ""),
    "81" => array ( "area" => "Kiritimati", "city" => "", "operator" => ""),
    "49" => array ( "area" => "Arorae", "city" => "", "operator" => ""),
    "48" => array ( "area" => "Tamana", "city" => "", "operator" => ""),
    "47" => array ( "area" => "Nikunau", "city" => "", "operator" => ""),
    "46" => array ( "area" => "Beru", "city" => "", "operator" => ""),
    "45" => array ( "area" => "Onotoa", "city" => "", "operator" => ""),
    "44" => array ( "area" => "Tabiteuea South", "city" => "", "operator" => ""),
    "43" => array ( "area" => "Tabiteuea North", "city" => "", "operator" => ""),
    "42" => array ( "area" => "Nonouti", "city" => "", "operator" => ""),
    "41" => array ( "area" => "Abemama", "city" => "", "operator" => ""),
    "40" => array ( "area" => "Aranuka", "city" => "", "operator" => ""),
    "39" => array ( "area" => "Kuria", "city" => "", "operator" => ""),
    "38" => array ( "area" => "Maiana", "city" => "", "operator" => ""),
    "37" => array ( "area" => "Banaba", "city" => "", "operator" => ""),
    "36" => array ( "area" => "Makin", "city" => "", "operator" => ""),
    "35" => array ( "area" => "Butaritari", "city" => "", "operator" => ""),
    "34" => array ( "area" => "Marakei", "city" => "", "operator" => ""),
    "33" => array ( "area" => "Abaiang", "city" => "", "operator" => ""),
    "32" => array ( "area" => "Tarawa", "city" => "Abaokoro (Ntarawa)", "operator" => ""),
    "31" => array ( "area" => "Tarawa", "city" => "Abaokoro (Ntarawa)", "operator" => ""),
    "30" => array ( "area" => "Tarawa", "city" => "Tarawa", "operator" => ""),
    "29" => array ( "area" => "Tarawa", "city" => "Bikenibeu", "operator" => ""),
    "28" => array ( "area" => "Tarawa", "city" => "Bikenibeu", "operator" => ""),
    "27" => array ( "area" => "Tarawa", "city" => "Tarawa", "operator" => ""),
    "26" => array ( "area" => "Tarawa", "city" => "Betio", "operator" => ""),
    "25" => array ( "area" => "Tarawa", "city" => "Betio", "operator" => ""),
    "24" => array ( "area" => "Tarawa", "city" => "Bairiki", "operator" => ""),
    "23" => array ( "area" => "Tarawa", "city" => "Bairiki", "operator" => ""),
    "22" => array ( "area" => "Tarawa", "city" => "Bairiki", "operator" => ""),
    "21" => array ( "area" => "Tarawa", "city" => "Bairiki", "operator" => ""),
    "20" => array ( "area" => "", "city" => "", "operator" => "Telecom Services Kiribati Ltd.")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 9)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "686", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Kiribati", "Area" => $data["area"], "City" => $data["city"], "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+686 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for FMC network with 2 digits NDC and 3 digits SN
   */
  $prefixes = array (
    "5"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 9)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "686", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Kiribati", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+686 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Kiribatian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
