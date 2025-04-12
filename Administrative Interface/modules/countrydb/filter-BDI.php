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
 * related to country database of Burundi.
 *
 * Reference: https://www.itu.int/oth/T0202000022/en (2018-05-24)
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
 * E.164 Burundi country hook
 */
framework_add_filter ( "e164_identify_country_BDI", "e164_identify_country_BDI");

/**
 * E.164 Burundian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BDI" (code for Burundi). This
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
function e164_identify_country_BDI ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Burundi
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+257")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Burundi has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "79" => "UCOM GSM",
    "78" => "Africell GSM",
    "77" => "Onatel Mobile GSM",
    "76" => "Econet GSM",
    "75" => "Lacell GSM",
    "71" => "UCOM GSM",
    "29" => "UCOM CDMA"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "257", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Burundi", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+257 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "2250" => array ( "area" => "South Zone", "company" => "Onatel"),
    "2240" => array ( "area" => "Central East Zone", "company" => "Onatel"),
    "2230" => array ( "area" => "North Zone", "company" => "Onatel"),
    "2227" => array ( "area" => "Rural Telephony", "company" => "Onatel"),
    "2226" => array ( "area" => "West Zone", "company" => "Onatel"),
    "2225" => array ( "area" => "Bujumbura", "company" => "Onatel"),
    "2224" => array ( "area" => "Bujumbura", "company" => "Onatel"),
    "2223" => array ( "area" => "Bujumbura", "company" => "Onatel"),
    "2222" => array ( "area" => "Bujumbura", "company" => "Onatel"),
    "2221" => array ( "area" => "Bujumbura", "company" => "Onatel"),
    "2220" => array ( "area" => "Bujumbura", "company" => "Onatel"),
    "22" => array ( "area" => "", "company" => "Onatel")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "257", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Burundi", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+257 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Burundian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
