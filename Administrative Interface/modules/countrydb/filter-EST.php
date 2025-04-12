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
 * related to country database of Estonia.
 *
 * Reference: https://www.itu.int/oth/T0202000043/en (2018-09-10)
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
 * E.164 Estonia country hook
 */
framework_add_filter ( "e164_identify_country_EST", "e164_identify_country_EST");

/**
 * E.164 Estonian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "EST" (code for Estonia). This
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
function e164_identify_country_EST ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Estonia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+372")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "89" => array ( "minimum" => 8, "maximum" => 8),
    "86" => array ( "minimum" => 8, "maximum" => 8),
    "86" => array ( "minimum" => 8, "maximum" => 8),
    "85" => array ( "minimum" => 8, "maximum" => 8),
    "84" => array ( "minimum" => 8, "maximum" => 8),
    "83" => array ( "minimum" => 8, "maximum" => 8),
    "82" => array ( "minimum" => 8, "maximum" => 8),
    "81" => array ( "minimum" => 8, "maximum" => 8),
    "5" => array ( "minimum" => 7, "maximum" => 8)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 4 + $data["minimum"] && strlen ( $parameters["Number"]) <= 4 + $data["maximum"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "372", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Estonia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+372 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "88",
    "79",
    "78",
    "77",
    "76",
    "75",
    "74",
    "73",
    "72",
    "71",
    "69",
    "68",
    "67",
    "66",
    "65",
    "64",
    "63",
    "62",
    "61",
    "60",
    "48",
    "47",
    "46",
    "45",
    "44",
    "43",
    "39",
    "38",
    "35",
    "33",
    "32"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "372", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Estonia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+372 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for PRN network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "901",
    "900"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "372", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Estonia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+372 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Estonian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
