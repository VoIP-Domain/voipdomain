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
 * related to country database of Faroe Islands.
 *
 * Reference: https://www.itu.int/oth/T0202000047/en (2018-12-13)
 * Reference: http://www.fjarskiftiseftirlitid.fo/fo/fjarskifti/nummarskipan/ (2018-12-13)
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
 * E.164 Faroe Islands country hook
 */
framework_add_filter ( "e164_identify_country_FRO", "e164_identify_country_FRO");

/**
 * E.164 Faroe Islands area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "FRO" (code for
 * Faroe Islands). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_FRO ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Faroe Islands
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+298")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Faroe Islands has 10 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 10)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "79" => "Faroese Telecom",
    "78" => "Faroese Telecom",
    "77" => "Kall",
    "76" => "Kall",
    "75" => "Kall",
    "74" => "Kall",
    "73" => "Kall",
    "72" => "Kall",
    "71" => "Kall",
    "29" => "Faroese Telecom",
    "28" => "Faroese Telecom",
    "27" => "Faroese Telecom",
    "26" => "Faroese Telecom",
    "25" => "Faroese Telecom",
    "24" => "Faroese Telecom",
    "23" => "Faroese Telecom",
    "22" => "Faroese Telecom",
    "21" => "Faroese Telecom",
    "5" => "Kali"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "298", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Faroe Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+298 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "20" => "Kall",
    "4" => "Faroese Telecom",
    "3" => "Faroese Telecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "298", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Faroe Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+298 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "99" => "",
    "98" => "",
    "97" => "",
    "96" => "",
    "95" => "",
    "94" => "",
    "93" => "",
    "92" => "",
    "91" => "",
    "89" => "Kall",
    "88" => "",
    "87" => "",
    "86" => "",
    "85" => "",
    "84" => "",
    "83" => "",
    "82" => "",
    "81" => "Kall",
    "69" => "",
    "68" => "",
    "67" => "",
    "66" => "Faroese Telecom",
    "65" => "",
    "64" => "",
    "63" => "Faroese Telecom",
    "62" => "Faroese Telecom",
    "61" => "Faroese Telecom",
    "60" => "Faroese Telecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "298", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Faroe Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+298 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Faroe Islands phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
