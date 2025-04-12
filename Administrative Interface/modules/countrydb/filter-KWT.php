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
 * related to country database of Kuwait.
 *
 * Reference: https://www.itu.int/oth/T0202000073/en (2018-07-13)
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
 * E.164 Kuwait country hook
 */
framework_add_filter ( "e164_identify_country_KWT", "e164_identify_country_KWT");

/**
 * E.164 Kuwaitian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "KWT" (code for Kuwait). This
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
function e164_identify_country_KWT ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Kuwait
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+965")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 8 digits SN
   */
  $prefixes = array (
    "9" => "Zain",
    "6" => "Ooredoo",
    "5" => "Viva"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "965", "NDC" => "", "Country" => "Kuwait", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+965 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for fixed line network with no NDC and 8 digits SN
   */
  $prefixes = array (
    "2"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "965", "NDC" => "", "Country" => "Kuwait", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+965 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for toll free network with no NDC and 8 digits SN
   */
  $prefixes = array (
    "18"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "965", "NDC" => "", "Country" => "Kuwait", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_TOLL_FREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+965 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Kuwaitian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
