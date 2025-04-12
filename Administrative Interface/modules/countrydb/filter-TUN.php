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
 * related to country database of Tunisia.
 *
 * Reference: https://www.itu.int/oth/T02020000D5/en (2015-11-13)
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
 * E.164 Tunisia country hook
 */
framework_add_filter ( "e164_identify_country_TUN", "e164_identify_country_TUN");

/**
 * E.164 Tunisian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "TUN" (code for Tunisia). This
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
function e164_identify_country_TUN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Tunisia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+216")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Tunisia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "5600" => "Orange Tunisie",
    "4600" => "Ooredoo Tunisie",
    "4500" => "Watany Ettisalat",
    "4300" => "Lyca Mobile",
    "426" => "Tunisie Télécom",
    "425" => "Tunisie Télécom",
    "424" => "Tunisie Télécom",
    "423" => "Tunisie Télécom",
    "422" => "Tunisie Télécom",
    "421" => "Tunisie Télécom",
    "58" => "Orange Tunisie",
    "55" => "Orange Tunisie",
    "54" => "Orange Tunisie",
    "53" => "Orange Tunisie",
    "52" => "Orange Tunisie",
    "51" => "Orange Tunisie",
    "50" => "Orange Tunisie",
    "44" => "Tunisie Télécom",
    "41" => "Tunisie Télécom",
    "40" => "Tunisie Télécom",
    "9" => "Tunisie Télécom",
    "2" => "Ooredoo Tunisie"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "216", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Tunisia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+216 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "3240" => "Orange Tunisie",
    "3140" => "Orange Tunisie",
    "3001" => "Orange Tunisie",
    "391" => "Orange Tunisie",
    "364" => "Orange Tunisie",
    "363" => "Orange Tunisie",
    "362" => "Orange Tunisie",
    "361" => "Orange Tunisie",
    "360" => "Orange Tunisie",
    "315" => "Orange Tunisie",
    "313" => "Orange Tunisie",
    "312" => "Orange Tunisie",
    "311" => "Orange Tunisie",
    "7" => "Tunisia Telecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "216", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Tunisia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+216 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for PRN network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "8210" => "Tunisia Telecom",
    "8110" => "",
    "8010" => "Tunisia Telecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "216", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Tunisia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+216 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "8860",
    "8850",
    "8840"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "216", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Tunisia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_AUDIOTEXT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+216 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8 ,2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Tunisian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
