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
 * related to country database of Chad.
 *
 * Reference: https://www.itu.int/oth/T0202000029/en (2016-01-15)
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
 * E.164 Chad country hook
 */
framework_add_filter ( "e164_identify_country_TCD", "e164_identify_country_TCD");

/**
 * E.164 Chadian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "TCD" (code for Chad). This hook
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
function e164_identify_country_TCD ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Chad
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+235")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Chad has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "954" => "Millicom",
    "953" => "Millicom",
    "952" => "Millicom",
    "951" => "Millicom",
    "950" => "Millicom",
    "634" => "Zain",
    "633" => "Zain",
    "632" => "Zain",
    "631" => "Zain",
    "630" => "Zain",
    "99" => "Millicom",
    "66" => "Zain",
    "65" => "Airtel Tchad"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "235", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Chad", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+235 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "2269" => "Sotel Tchad",
    "2268" => "Sotel Tchad",
    "2255" => "Sotel Tchad",
    "2254" => "Sotel Tchad",
    "2253" => "Sotel Tchad",
    "2252" => "Sotel Tchad",
    "2251" => "Sotel Tchad",
    "2250" => "Sotel Tchad",
    "77" => "Soltel Tchad (Salam)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "235", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Chad", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+235 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "2290" => "Sotel Tchad (Tawali)",
    "2280" => "Sotel Tchad (Tawali)",
    "2270" => "Sotel Tchad (Tawali)",
    "2230" => "Sotel Tchad (Tawali)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "235", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Chad", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+235 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Chadian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
