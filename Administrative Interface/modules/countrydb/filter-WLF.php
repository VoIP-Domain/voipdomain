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
 * related to country database of Wallis and Futuna.
 *
 * Reference: https://www.itu.int/oth/T02020000E6/en (2015-12-11)
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
 * E.164 Wallis and Futuna country hook
 */
framework_add_filter ( "e164_identify_country_WLF", "e164_identify_country_WLF");

/**
 * E.164 Wallis and Futuna area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "WLF" (code for
 * Wallis and Futuna). This hook will verify if phone number is valid, returning
 * the area code, area name, phone number, others number related information and
 * if possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_WLF ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Wallis and Futuna
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+681")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Wallis and Futuna has 10 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 10)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "499" => "Roaming Number (MSRN)",
    "83" => "Futuna",
    "82" => "Wallis"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "681", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Wallis and Futuna", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+681 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 4 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 2) == "72")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "681", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Wallis and Futuna", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+681 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for voicemail network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "80",
    "40"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "681", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Wallis and Futuna", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOICEMAIL, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+681 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Wallis and Futuna phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
