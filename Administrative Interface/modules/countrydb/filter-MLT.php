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
 * related to country database of Malta.
 *
 * Reference: https://www.itu.int/oth/T0202000084/en (2022-02-14)
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
 * E.164 Malta country hook
 */
framework_add_filter ( "e164_identify_country_MLT", "e164_identify_country_MLT");

/**
 * E.164 Maltalian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MLT" (code for Malta). This
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
function e164_identify_country_MLT ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Malta
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+356")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Malta has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network
   */
  $prefixes = array (
    "9897" => "Epic",
    "9889" => "GO Mobile",
    "9813" => "Melita Mobile",
    "9812" => "Melita Mobile",
    "9811" => "Melita Mobile",
    "9696" => "Epic",
    "9231" => "GO Mobile",
    "9211" => "GO Mobile",
    "9210" => "GO Mobile",
    "7210" => "GO Mobile",
    "99" => "Epic",
    "79" => "GO Mobile",
    "77" => "Melita Mobile"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "356", "NDC" => "", "Country" => "Malta", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+356 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 or 4 digits NDC and 5 or 4 digits SN
   */
  $prefixes = array (
    "2065" => "Melita",
    "2060" => "Melita",
    "2034" => "Vanilla",
    "2033" => "Vanilla",
    "2032" => "Vanilla",
    "2031" => "Vanilla",
    "2018" => "Melita",
    "2017" => "Melita",
    "2016" => "Melita",
    "2015" => "Melita",
    "2014" => "Melita",
    "2013" => "Melita",
    "2012" => "Melita",
    "2011" => "Melita",
    "2010" => "Melita",
    "260" => "Melita",
    "209" => "Epic",
    "27" => "Melita",
    "25" => "GO",
    "23" => "GO",
    "22" => "GO",
    "21" => "GO"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "356", "NDC" => "", "Country" => "Malta", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+356 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Maltalian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
