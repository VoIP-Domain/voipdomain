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
 * related to country database of Saint Pierre and Miquelon.
 *
 * Reference: https://www.itu.int/oth/T02020000B2/en (2017-12-15)
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
 * E.164 Saint Pierre and Miquelon country hook
 */
framework_add_filter ( "e164_identify_country_SPM", "e164_identify_country_SPM");

/**
 * E.164 Saint Pierre and Miquelon area number identification hook. This hook is
 * an e164_identify sub hook, called when the ISO3166 Alpha3 are "SPM" (code for
 * Saint Pierre and Miquelon). This hook will verify if phone number is valid,
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
function e164_identify_country_SPM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Saint Pierre and Miquelon
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+508")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Saint Pierre and Miquelon has 10 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 10)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for landline or mobile network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "56",
    "55",
    "51",
    "50",
    "46",
    "45",
    "44",
    "43",
    "42",
    "41",
    "40"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "508", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Saint Pierre and Miquelon", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONE_LANDLINE + VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8), "International" => "+508 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Saint Pierre and Miquelon phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
