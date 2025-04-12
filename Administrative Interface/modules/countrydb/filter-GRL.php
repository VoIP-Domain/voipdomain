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
 * related to country database of Greenland.
 *
 * Reference: https://www.itu.int/oth/T0202000056/en (2017-08-29)
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
 * E.164 Greenland country hook
 */
framework_add_filter ( "e164_identify_country_GRL", "e164_identify_country_GRL");

/**
 * E.164 Greenlandian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "GRL" (code for
 * Greenland). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_GRL ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Greenland
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+299")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Greenland has 10 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 10)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "59",
    "58",
    "57",
    "56",
    "55",
    "54",
    "53",
    "52",
    "51",
    "49",
    "48",
    "47",
    "46",
    "45",
    "44",
    "43",
    "42",
    "29",
    "28",
    "27",
    "26",
    "25",
    "24",
    "23",
    "22",
    "21"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "299", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Greenland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+299 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "691" => array ( "area" => "South", "city" => "Ivittuut"),
    "99" => array ( "area" => "North/East", "city" => "Illoqqortoormiut"),
    "98" => array ( "area" => "North/East", "city" => "Tasiilaq"),
    "97" => array ( "area" => "North/East", "city" => "Qaanaaq"),
    "96" => array ( "area" => "North/East", "city" => "Upernavik"),
    "95" => array ( "area" => "North/East", "city" => "Uummannaq"),
    "94" => array ( "area" => "North/East", "city" => "Ilulissat"),
    "92" => array ( "area" => "North/East", "city" => "Qeqertasuaq"),
    "91" => array ( "area" => "North/East", "city" => "Qasigannguit"),
    "89" => array ( "area" => "West", "city" => "Aasiaat"),
    "87" => array ( "area" => "West", "city" => "Kangaatsiaq"),
    "86" => array ( "area" => "West", "city" => "Sisimiut"),
    "85" => array ( "area" => "West", "city" => "Sisimiut"),
    "84" => array ( "area" => "West", "city" => "Kangerlussuaq"),
    "81" => array ( "area" => "West", "city" => "Maniitsoq"),
    "68" => array ( "area" => "South", "city" => "Paamiut"),
    "66" => array ( "area" => "South", "city" => "Narsaq"),
    "64" => array ( "area" => "South", "city" => "Qaqortoq"),
    "61" => array ( "area" => "South", "city" => "Nanortalik"),
    "37" => array ( "area" => "", "city" => "Nuuk"),
    "36" => array ( "area" => "", "city" => "Nuuk"),
    "35" => array ( "area" => "", "city" => "Nuuk"),
    "34" => array ( "area" => "", "city" => "Nuuk"),
    "33" => array ( "area" => "", "city" => "Nuuk"),
    "32" => array ( "area" => "", "city" => "Nuuk"),
    "31" => array ( "area" => "", "city" => "Nuuk")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "299", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Greenland", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+299 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "39",
    "38"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "299", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Greenland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+299 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for VSAT network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "1992",
    "1991"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "299", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Greenland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+299 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Greenlandian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
