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
 * related to country database of United Arab Emirates.
 *
 * Reference: https://www.itu.int/oth/T02020000DC/en (2012-08-08)
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
 * E.164 United Arab Emirates country hook
 */
framework_add_filter ( "e164_identify_country_ARE", "e164_identify_country_ARE");

/**
 * E.164 United Arab Emirates area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "ARE" (code for
 * United Arab Emirates). This hook will verify if phone number is valid,
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
function e164_identify_country_ARE ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United Arab Emirates
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+971")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in United Arab Emirates has between 9 and 16 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 9 || strlen ( $parameters["Number"]) > 16)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "56" => "Etisalat",
    "55" => "du",
    "52" => "du",
    "50" => "Etisalat"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "971", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "United Arab Emirates", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+971 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1 digit NDC and 7 digits SN
   */
  $prefixes = array (
    "9" => "East Coast region (Fujairah)",
    "7" => "Ras Al Khai mah",
    "6" => "West Coast region (Sharjah, Ajman and Umm Al-Qaiwain)",
    "4" => "Dubai",
    "3" => "Al Ain",
    "2" => "Abu Dhabi"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && (int) substr ( $parameters["Number"], 5, 1) >= 2 && (int) substr ( $parameters["Number"], 5, 1) <= 8 && substr ( $parameters["Number"], 6, 2) != "00")
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "971", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "United Arab Emirates", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+971 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for PRN line network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "7005",
    "7000",
    "6005",
    "6002",
    "9002",
    "9000",
    "500",
    "400",
    "300",
    "200"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "971", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "United Arab Emirates", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+971 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Tollfree line network with 3 digits NDC and 2 to 9 digits SN
   */
  if ( strlen ( $parameters["Number"]) >= 9 && strlen ( $parameters["Number"]) <= 16 && substr ( $parameters["Number"], 4, 3) == "800")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "971", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "United Arab Emirates", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+971 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * If reached here, number wasn't identified as a valid United Arab Emirates phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
