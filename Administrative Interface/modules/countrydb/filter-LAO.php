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
 * related to country database of Lao People's Democratic Republic.
 *
 * Reference: https://www.itu.int/oth/T0202000075/en (2011-02-15)
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
 * E.164 Lao People's Democratic Republic country hook
 */
framework_add_filter ( "e164_identify_country_LAO", "e164_identify_country_LAO");

/**
 * E.164 Lao People's Democratic Republic area number identification hook. This
 * hook is an e164_identify sub hook, called when the ISO3166 Alpha3 are "LAO"
 * (code for Lao People's Democratic Republic). This hook will verify if phone
 * number is valid, returning the area code, area name, phone number, others
 * number related information and if possible, the number type (mobile,
 * landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_LAO ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Lao People's Democratic Republic
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+856")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 8 digits SN
   */
  $prefixes = array (
    "309" => "Unitel Lao",
    "307" => "Beeline Lao",
    "305" => "Lao Telecom",
    "304" => "Unitel Lao",
    "302" => "ETL Co. Ltd.",
    "209" => "Unitel Lao",
    "207" => "Beeline Lao",
    "205" => "Lao Telecom",
    "202" => "ETL Co. Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 14)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "856", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Lao People's Democratic Republic", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 3) . " " . substr ( $parameters["Number"], 11), "International" => "+856 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 3) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "88" => "Phongsali",
    "86" => "Louang Namtha",
    "84" => "Bokeo",
    "81" => "Oudomxay",
    "74" => "Sainhabouli",
    "71" => "Louang Prabang",
    "64" => "Houaphanh",
    "61" => "Xiengkhouang",
    "54" => "Bolikhamsai",
    "51" => "Khammouan",
    "41" => "Savannakhet",
    "38" => "Sekong",
    "36" => "Attapeu",
    "34" => "Salavan",
    "31" => "Champasak",
    "23" => "Vientiane & Xaisomboun",
    "21" => "Vientiane Prefecture"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "856", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Lao People's Democratic Republic", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+856 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Lao People's Democratic
   * Republic phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
