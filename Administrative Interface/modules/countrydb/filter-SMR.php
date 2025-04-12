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
 * related to country database of San Marino.
 *
 * Reference: https://www.itu.int/oth/T02020000B5/en (2010-08-03)
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
 * E.164 San Marino country hook
 */
framework_add_filter ( "e164_identify_country_SMR", "e164_identify_country_SMR");

/**
 * E.164 San Marino area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "SMR" (code for San
 * Marino). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_SMR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from San Marino
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+378")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in San Marino has 10 to 14 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 10 || strlen ( $parameters["Number"]) > 14)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "69" => "",
    "68" => "",
    "67" => "",
    "66" => "San Marino Telecom S.p.A.",
    "65" => "",
    "64" => "",
    "63" => "",
    "62" => "",
    "61" => "Telenet S.r.l.",
    "60" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "378", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "San Marino", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+378 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for fixed line network with 2 to 4 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "89" => "",
    "88" => "",
    "87" => "",
    "86" => "",
    "85" => "Telenet S.r.l.",
    "84" => "",
    "83" => "",
    "82" => "",
    "81" => "Telenet S.r.l.",
    "80" => "",
    "9" => "",
    "054989" => "RSM STATE",
    "054988" => "RSM STATE",
    "054987" => "Telecom Italia San Marino S.p.A.",
    "054986" => "",
    "054985" => "Telenet S.r.l.",
    "054984" => "",
    "054983" => "",
    "054982" => "",
    "054981" => "Telenet S.r.l.",
    "054980" => "San Marino Telecom S.p.A.",
    "05499" => "Telecom Italia San Marino S.p.A. (and others)",
    "05497" => "",
    "05496" => "",
    "05495" => "",
    "05494" => "",
    "05493" => "",
    "05492" => "",
    "05491" => "",
    "05490" => "",
    "0548" => "",
    "0547" => "",
    "0546" => "",
    "0545" => "",
    "0544" => "",
    "0543" => "",
    "0542" => "",
    "0541" => "",
    "0540" => "",
    "059" => "",
    "058" => "",
    "057" => "",
    "056" => "",
    "055" => "",
    "053" => "",
    "052" => "",
    "051" => "",
    "050" => "",
    "09" => "",
    "08" => "",
    "07" => "",
    "06" => "",
    "04" => "",
    "03" => "",
    "02" => "",
    "01" => "",
    "00" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( substr ( $parameters["Number"], 4, 4) == "0549")
      {
        $ndc = substr ( $parameters["Number"], 4, 4);
        $sn = substr ( $parameters["Number"], 8);
        $local = "(" . substr ( $parameters["Number"], 4, 4) . ") " . substr ( $parameters["Number"], 8);
        $international = "+378 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8);
      } else {
        $ndc = substr ( $parameters["Number"], 4, 2);
        $sn = substr ( $parameters["Number"], 6);
        $local = substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6);
        $international = "+378 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6);
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "378", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "San Marino", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => $local, "International" => $international)));
    }
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "59" => "",
    "58" => "Telecom Italia San Marino S.p.A.",
    "57" => "",
    "56" => "",
    "55" => "San Marino Telecom S.p.A.",
    "54" => "",
    "53" => "",
    "52" => "",
    "51" => "Telenet S.r.l.",
    "50" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "378", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "San Marino", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+378 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for PRN network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "79" => "",
    "78" => "Telecom Italia San Marino S.p.A.",
    "77" => "San Marino Telecom S.p.A.",
    "76" => "",
    "75" => "",
    "74" => "",
    "73" => "",
    "72" => "",
    "71" => "Telenet S.r.l.",
    "70" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "378", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "San Marino", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+378 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid San Marino phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
