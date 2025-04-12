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
 * related to country database of Seychelles.
 *
 * Reference: https://www.itu.int/oth/T02020000BA/en (2022-01-11)
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
 * E.164 Seychelles country hook
 */
framework_add_filter ( "e164_identify_country_SYC", "e164_identify_country_SYC");

/**
 * E.164 Seychelles area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "SYC" (code for
 * Seychelles). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_SYC ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Seychelles
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+248")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Seychelles has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 1 digit NDC and 6 digits SN
   */
  $prefixes = array (
    "2789" => "AIRTEL",
    "2788" => "AIRTEL",
    "2787" => "AIRTEL",
    "2786" => "AIRTEL",
    "2785" => "AIRTEL",
    "2784" => "AIRTEL",
    "2782" => "AIRTEL",
    "2777" => "AIRTEL",
    "2776" => "AIRTEL",
    "2775" => "AIRTEL",
    "2774" => "AIRTEL",
    "2773" => "AIRTEL",
    "2772" => "AIRTEL",
    "2771" => "AIRTEL",
    "2770" => "AIRTEL",
    "2559" => "CWS",
    "2558" => "CWS",
    "2557" => "CWS",
    "2556" => "CWS",
    "279" => "AIRTEL",
    "276" => "AIRTEL",
    "275" => "AIRTEL",
    "274" => "AIRTEL",
    "273" => "AIRTEL",
    "272" => "AIRTEL",
    "271" => "AIRTEL",
    "270" => "AIRTEL",
    "259" => "CWS",
    "258" => "CWS",
    "257" => "CWS",
    "256" => "CWS",
    "254" => "CWS",
    "253" => "CWS",
    "252" => "CWS",
    "251" => "CWS",
    "250" => "CWS",
    "29" => "",
    "28" => "AIRTEL",
    "26" => "CWS",
    "24" => "",
    "23" => "",
    "22" => "INTELVISION",
    "21" => "INTELVISION",
    "20" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "248", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Seychelles", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+248 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 1 digit NDC and 6 digits SN
   */
  $prefixes = array (
    "49" => "",
    "48" => "",
    "47" => "",
    "46" => "AIRTEL",
    "45" => "",
    "44" => "INTELVISION",
    "43" => "CWS",
    "42" => "CWS",
    "41" => "",
    "40" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "248", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Seychelles", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+248 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for FMC network with 1 digit NDC and 6 digits SN
   */
  $prefixes = array (
    "2783" => "AIRTEL",
    "2781" => "AIRTEL",
    "2780" => "AIRTEL",
    "2555" => "CWS",
    "2554" => "CWS",
    "2553" => "CWS",
    "2552" => "CWS",
    "2551" => "CWS",
    "2550" => "CWS"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "248", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Seychelles", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+248 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for VoIP network with 1 digit NDC and 6 digits SN
   */
  $prefixes = array (
    "69" => "",
    "68" => "",
    "67" => "",
    "66" => "",
    "65" => "",
    "64" => "KOKONET",
    "63" => "",
    "62" => "",
    "61" => "",
    "60" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "248", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Seychelles", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+248 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for Tollfree network with 3 digit NDC and 4 digits SN
   */
  $prefixes = array (
    "8009" => "",
    "8008" => "CWS",
    "8007" => "",
    "8006" => "",
    "8005" => "",
    "8004" => "",
    "8003" => "",
    "8002" => "",
    "8001" => "",
    "8000" => "AIRTEL",
    "809" => "",
    "808" => "",
    "807" => "",
    "806" => "",
    "805" => "",
    "804" => "",
    "803" => "",
    "802" => "",
    "801" => "",
    "89" => "",
    "88" => "",
    "87" => "",
    "86" => "",
    "85" => "",
    "84" => "",
    "83" => "",
    "82" => "",
    "81" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "248", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Seychelles", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+248 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for PRN network with 3 digit NDC and 4 digits SN
   */
  $prefixes = array (
    "998" => "",
    "997" => "",
    "996" => "",
    "995" => "",
    "994" => "",
    "993" => "",
    "992" => "",
    "991" => "",
    "990" => "",
    "979" => "",
    "978" => "",
    "977" => "",
    "976" => "",
    "975" => "",
    "974" => "",
    "973" => "",
    "972" => "",
    "971" => "CWS",
    "970" => "",
    "98" => "",
    "95" => "CWS",
    "94" => "",
    "93" => "",
    "92" => "",
    "91" => "",
    "90" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "248", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Seychelles", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+248 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Seychelles phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
