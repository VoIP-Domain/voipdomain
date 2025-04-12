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
 * related to country database of Zambia.
 *
 * Reference: https://www.itu.int/oth/T02020000E8/en (2022-11-14)
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
 * E.164 Zambia country hook
 */
framework_add_filter ( "e164_identify_country_ZMB", "e164_identify_country_ZMB");

/**
 * E.164 Zambian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "ZMB" (code for Zambia). This
 * hook will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_ZMB ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Zambia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+260")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Zambia has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "99" => "",
    "98" => "Beeline Telecoms",
    "97" => "Airtel",
    "96" => "MTN",
    "95" => "ZAMTEL",
    "94" => "",
    "93" => "",
    "92" => "",
    "91" => "",
    "79" => "",
    "78" => "",
    "77" => "Airtel",
    "76" => "MTN",
    "75" => "ZMATEL",
    "74" => "",
    "73" => "",
    "72" => "",
    "71" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "260", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Zambia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+260 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "219" => array ( "Area" => "", "Operator" => ""),
    "218" => array ( "Area" => "Mongu", "Operator" => "ZAMTEL"),
    "217" => array ( "Area" => "Solwezi", "Operator" => "ZAMTEL"),
    "216" => array ( "Area" => "Chipata", "Operator" => "ZAMTEL"),
    "215" => array ( "Area" => "Kabwe", "Operator" => "ZAMTEL"),
    "214" => array ( "Area" => "Ksama", "Operator" => "ZAMTEL"),
    "213" => array ( "Area" => "Livingstone", "Operator" => "ZAMTEL"),
    "212" => array ( "Area" => "Copperbelt", "Operator" => "ZAMTEL"),
    "211" => array ( "Area" => "Lusaka province", "Operator" => "ZAMTEL"),
    "210" => array ( "Area" => "", "Operator" => ""),
    "29" => array ( "Area" => "", "Operator" => ""),
    "28" => array ( "Area" => "", "Operator" => ""),
    "27" => array ( "Area" => "", "Operator" => ""),
    "26" => array ( "Area" => "", "Operator" => ""),
    "25" => array ( "Area" => "", "Operator" => ""),
    "24" => array ( "Area" => "", "Operator" => ""),
    "23" => array ( "Area" => "", "Operator" => ""),
    "22" => array ( "Area" => "", "Operator" => ""),
    "20" => array ( "Area" => "", "Operator" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "260", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Zambia", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+260 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "69" => "",
    "68" => "",
    "67" => "",
    "66" => "",
    "65" => "",
    "64" => "",
    "63" => "Liquid Telecom Zambia",
    "62" => "",
    "61" => "",
    "60" => ""
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "260", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Zambia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+260 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Tollfree network with 1 digit NDC and 8 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 1) == "8")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "260", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Zambia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+260 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for PRN network with 2 digit NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 2) == "90")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "260", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Zambia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+260 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * If reached here, number wasn't identified as a valid Zambian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
