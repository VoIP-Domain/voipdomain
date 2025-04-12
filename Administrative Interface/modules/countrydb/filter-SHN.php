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
 * related to country database of Saint Helena, Ascension and Tristan da Cunha.
 *
 * Reference: https://www.itu.int/oth/T02020000AF/en (2015-06-30)
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
 * E.164 Saint Helena, Ascension and Tristan da Cunha country hook
 */
framework_add_filter ( "e164_identify_country_SHN", "e164_identify_country_SHN");

/**
 * E.164 Saint Helena, Ascension and Tristan da Cunha area number identification
 * hook. This hook is an e164_identify sub hook, called when the ISO3166 Alpha3
 * are "SHN" (code for Saint Helena, Ascension and Tristan da Cunha). This hook
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
function e164_identify_country_SHN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Saint Helena, Ascension and Tristan da Cunha
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+290" && substr ( $parameters["Number"], 0, 4) != "+247")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for Saint Helena and Tristan da Cunha numbers
   */
  if ( substr ( $parameters["Number"], 0, 4) == "+290")
  {
    /**
     * All numbers in Saint Helena and Tristan da Cunha has 9 digits E.164 format
     */
    if ( strlen ( $parameters["Number"]) != 9)
    {
      return ( is_array ( $buffer) ? $buffer : false);
    }

    /**
     * Check for mobile network with 1 digit NDC and 4 digits SN
     */
    $prefixes = array (
      "6",
      "5"
    );
    foreach ( $prefixes as $prefix)
    {
      if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => substr ( $parameters["Number"], 1, 3), "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Saint Helena, Ascension and Tristan da Cunha", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+" . substr ( $parameters["Number"], 1, 3) . " " . substr ( $parameters["Number"], 4))));
      }
    }

    /**
     * Check for fixed line network with 2 digits NDC and 3 digits SN
     */
    $prefixes = array (
      "269" => array ( "Area" => "", "City" => ""),
      "268" => array ( "Area" => "", "City" => ""),
      "267" => array ( "Area" => "", "City" => ""),
      "266" => array ( "Area" => "", "City" => ""),
      "265" => array ( "Area" => "", "City" => ""),
      "264" => array ( "Area" => "", "City" => ""),
      "29" => array ( "Area" => "", "City" => ""),
      "28" => array ( "Area" => "", "City" => ""),
      "27" => array ( "Area" => "", "City" => ""),
      "25" => array ( "Area" => "", "City" => ""),
      "24" => array ( "Area" => "", "City" => ""),
      "23" => array ( "Area" => "", "City" => ""),
      "22" => array ( "Area" => "St. Helena", "City" => "Jamestown"),
      "21" => array ( "Area" => "", "City" => ""),
      "20" => array ( "Area" => "", "City" => ""),
      "8" => array ( "Area" => "Tristan da Cunha", "City" => "")
    );
    foreach ( $prefixes as $prefix => $data)
    {
      if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => substr ( $parameters["Number"], 1, 3), "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Saint Helena, Ascension and Tristan da Cunha", "Area" => $data["Area"], "City" => $data["City"], "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+" . substr ( $parameters["Number"], 1, 3) . " " . substr ( $parameters["Number"], 4))));
      }
    }
  }

  /**
   * Check for Ascension numbers
   */
  if ( substr ( $parameters["Number"], 0, 4) == "+247")
  {
    /**
     * All numbers in Ascension has 9 or 10 digits E.164 format
     */
    if ( strlen ( $parameters["Number"]) < 9 || strlen ( $parameters["Number"]) > 10)
    {
      return ( is_array ( $buffer) ? $buffer : false);
    }

    /**
     * Check for mobile network with 1 digit NDC and 5 digits SN
     */
    $prefixes = array (
      "9" => 6,
      "8" => 6,
      "5" => 6,
      "4" => 5,
      "1" => 6,
      "0" => 6
    );
    foreach ( $prefixes as $prefix => $length)
    {
      if ( strlen ( $parameters["Number"]) == 4 + $length && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => substr ( $parameters["Number"], 1, 3), "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Saint Helena, Ascension and Tristan da Cunha", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+" . substr ( $parameters["Number"], 1, 3) . " " . substr ( $parameters["Number"], 4))));
      }
    }

    /**
     * Check for fixed line network with 2 digits NDC and 4 digits SN
     */
    $prefixes = array (
      "67" => "Georgetown",
      "66" => "Georgetown",
      "64" => "Two Boats",
      "63" => "Travellers Hill, Airhead",
      "62" => "U.S. Base"
    );
    foreach ( $prefixes as $prefix => $area)
    {
      if ( strlen ( $parameters["Number"]) == 9 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => substr ( $parameters["Number"], 1, 3), "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Saint Helena, Ascension and Tristan da Cunha", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+" . substr ( $parameters["Number"], 1, 3) . " " . substr ( $parameters["Number"], 4))));
      }
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Saint Helena, Ascension and Tristan da Cunha phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
