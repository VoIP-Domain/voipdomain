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
 * related to country database of Uganda.
 *
 * Reference: https://www.itu.int/oth/T02020000F1/en (2022-05-16)
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
 * E.164 Uganda country hook
 */
framework_add_filter ( "e164_identify_country_UGA", "e164_identify_country_UGA");

/**
 * E.164 Uganda area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "UGA" (code for Uganda). This
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
function e164_identify_country_UGA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Uganda
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+256")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Uganda has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2, 3 or 4 digits NDC and 7, 6 or 5 digits SN
   */
  $prefixes = array (
    "7261" => "Tangerine Ltd.",
    "7260" => "Tangerine Ltd.",
    "7240" => "Hamilton Ltd.",
    "761" => "MTN Uganda Ltd.",
    "760" => "MTN Uganda Ltd.",
    "742" => "Airtel Uganda Ltd.",
    "741" => "Airtel Uganda Ltd.",
    "740" => "Airtel Uganda Ltd.",
    "720" => "Smile Communications (U) Ltd.",
    "78" => "MTN Uganda Ltd.",
    "77" => "MTN Uganda Ltd.",
    "75" => "Airtel Uganda Ltd.",
    "71" => "Uganda Telecom Ltd.",
    "70" => "Airtel Uganda Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "256", "NDC" => $prefix, "Country" => "Uganda", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+256 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1, 3, 4, 5 or 6 digits NDC and 8, 6, 5, 4 or 3 digits SN
   */
  $prefixes = array (
    "206304" => "Simbanet Uganda Ltd.",
    "206303" => "Simbanet Uganda Ltd.",
    "206302" => "Simbanet Uganda Ltd.",
    "206301" => "Simbanet Uganda Ltd.",
    "206300" => "Simbanet Uganda Ltd.",
    "20611" => "Hamilton Telecom",
    "20307" => "Sombha Solutions Store Ltd.",
    "20306" => "Sombha Solutions Store Ltd.",
    "20240" => "Liquid Telecom Ltd.",
    "2054" => "Roke Investment International Ltd.",
    "2053" => "Roke Investment International Ltd.",
    "2052" => "Roke Investment International Ltd.",
    "2051" => "Roke Investment International Ltd.",
    "2050" => "Roke Investment International Ltd.",
    "207" => "Airtel Uganda Ltd.",
    "201" => "Airtel Uganda Ltd.",
    "200" => "Airtel Uganda Ltd.",
    "4" => "Uganda Telecom Ltd.",
    "3" => "Uganda Telecom Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "256", "NDC" => $prefix, "Country" => "Uganda", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+256 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Uganda phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
