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
 * related to country database of Bermuda.
 *
 * Reference: https://www.itu.int/oth/T0202000018/en (2010-03-04)
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
 * E.164 Bermuda country hook
 */
framework_add_filter ( "e164_identify_country_BMU", "e164_identify_country_BMU");

/**
 * E.164 Bermudian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BMU" (code for Bermuda). This
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
function e164_identify_country_BMU ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Bermuda
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1441")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Bermuda has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "59" => "Digicel",
    "53" => "Digicel",
    "52" => "Digicel",
    "51" => "Digicel",
    "50" => "Digicel",
    "7" => "Cellular One",
    "3" => "M3 Wireles"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1441", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Bermuda", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 441 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "2236" => "Bermuda Telephone Company",
    "2020" => "Bermuda Telephone Company",
    "824" => "Bermuda Telephone Company",
    "589" => "North Rock Communications",
    "560" => "North Rock Communications",
    "261" => "Bermuda Telephone Company",
    "54" => "North Rock Communications",
    "29" => "Bermuda Telephone Company",
    "27" => "Bermuda Telephone Company",
    "24" => "Bermuda Telephone Company",
    "23" => "Bermuda Telephone Company",
    "6" => "Quantum Communications",
    "4" => "Quantum Communications"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1441", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Bermuda", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 441 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Bermudian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
