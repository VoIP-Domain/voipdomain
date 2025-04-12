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
 * related to country database of Curaçao.
 *
 * Reference: https://www.itu.int/oth/T02020000F5/en (2018-11-30)
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
 * E.164 Curaçao country hook
 */
framework_add_filter ( "e164_identify_country_CUW", "e164_identify_country_CUW");

/**
 * E.164 Curaçao area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "CUW" (code for Curaçao). This
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
function e164_identify_country_CUW ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Curaçao
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+599")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Curaçao has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "9531" => "Antelecom N.V.",
    "9530" => "Antelecom N.V.",
    "969" => "Curaçao Telecom N.V.",
    "968" => "Curaçao Telecom N.V.",
    "967" => "Curaçao Telecom N.V.",
    "966" => "Curaçao Telecom N.V.",
    "965" => "Curaçao Telecom N.V.",
    "957" => "Antelecom N.V.",
    "956" => "Antelecom N.V.",
    "954" => "Antelecom N.V.",
    "952" => "Antelecom N.V.",
    "951" => "Antelecom N.V."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "599", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Curaçao", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+599 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "9889" => "Antelecom N.V.",
    "9888" => "Antelecom N.V.",
    "9887" => "Antelecom N.V.",
    "9885" => "Antelecom N.V.",
    "9871" => "Antelecom N.V.",
    "9870" => "Antelecom N.V.",
    "9839" => "Antelecom N.V.",
    "9833" => "Antelecom N.V.",
    "9789" => "Curaçao Cable Television N.V.",
    "9788" => "Curaçao Cable Television N.V.",
    "9787" => "Curaçao Cable Television N.V.",
    "9777" => "Antelecom N.V.",
    "9767" => "Antelecom N.V.",
    "9765" => "Antelecom N.V.",
    "9763" => "Antelecom N.V.",
    "9749" => "Antelecom N.V.",
    "9748" => "Antelecom N.V.",
    "9747" => "Antelecom N.V.",
    "9746" => "Antelecom N.V.",
    "9745" => "Antelecom N.V.",
    "9744" => "Antelecom N.V.",
    "9738" => "Antelecom N.V.",
    "9737" => "Antelecom N.V.",
    "9736" => "Antelecom N.V.",
    "9735" => "Antelecom N.V.",
    "9734" => "Antelecom N.V.",
    "9733" => "Antelecom N.V.",
    "9732" => "Antelecom N.V.",
    "9730" => "Antelecom N.V.",
    "9444" => "Antelecom N.V.",
    "9441" => "Antelecom N.V.",
    "9435" => "Antelecom N.V.",
    "9430" => "Antelecom N.V.",
    "986" => "Antelecom N.V.",
    "984" => "Santa Barbara Utilities N.V.",
    "950" => "Antelecom N.V.",
    "946" => "Antelecom N.V."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "599", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Curaçao", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+599 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Curaçao phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
