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
 * related to country database of Oman.
 *
 * Reference: https://www.itu.int/oth/T020200009F/en (2022-07-14)
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
 * E.164 Oman country hook
 */
framework_add_filter ( "e164_identify_country_OMN", "e164_identify_country_OMN");

/**
 * E.164 Omanian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "OMN" (code for Oman). This
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
function e164_identify_country_OMN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Oman
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+968")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Oman has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "909" => "Omantel Mobile",
    "908" => "Omantel Mobile",
    "907" => "Omantel Mobile",
    "906" => "Omantel Mobile",
    "905" => "Omantel Mobile",
    "904" => "Omantel Mobile",
    "903" => "Omantel Mobile",
    "902" => "Omantel Mobile",
    "901" => "Omantel Mobile",
    "774" => "Vodafone",
    "773" => "Vodafone",
    "772" => "Vodafone",
    "771" => "Vodafone",
    "770" => "Vodafone",
    "99" => "Omantel Mobile",
    "98" => "Omantel Mobile",
    "97" => "Ooredoo",
    "96" => "Ooredoo",
    "95" => "Ooredoo",
    "94" => "Ooredoo",
    "93" => "Omantel Mobile",
    "92" => "Omantel Mobile",
    "91" => "Omantel Mobile",
    "78" => "Ooredoo",
    "79" => "Ooredoo",
    "72" => "Omantel Mobile",
    "71" => "Omantel Mobile"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "968", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Oman", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+968 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "26" => "Al Batinah, Musandam",
    "25" => "A'Dakjliyah, Al Sharqiya, A'Dhahira",
    "24" => "Muscat",
    "23" => "Dhofar, Wal Wusta",
    "22" => "Ooredoo, Omantel, Awaser"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "968", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Oman", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+968 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Omanian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
