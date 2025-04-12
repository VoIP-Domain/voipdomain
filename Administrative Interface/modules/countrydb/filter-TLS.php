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
 * related to country database of Timor-Leste.
 *
 * Reference: https://www.itu.int/oth/T02020000D0/en (2013-04-23)
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
 * E.164 Timor-Leste country hook
 */
framework_add_filter ( "e164_identify_country_MLI", "e164_identify_country_MLI");

/**
 * E.164 Timor-Leste area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "MLI" (code for
 * Timor-Leste). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_MLI ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Timor-Leste
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+670")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Timor-Leste has 11 or 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 11 || strlen ( $parameters["Number"]) > 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "78" => "Timor Telecom",
    "77" => "Timor Telecom",
    "76" => "Viettel/Telmor",
    "75" => "Viettel/Telmor",
    "74" => "Telin/Telkom Cel",
    "73" => "Telin/Telkom Cel",
    "72" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "670", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Timor-Leste", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+670 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "44" => array ( "Area" => "Eastern region", "City" => "Lautem"),
    "43" => array ( "Area" => "Eastern region", "City" => "Viqueque"),
    "42" => array ( "Area" => "Eastern region", "City" => "Manatuto"),
    "41" => array ( "Area" => "Eastern region", "City" => "Baucau"),
    "38" => array ( "Area" => "Central region", "City" => "Ermera"),
    "37" => array ( "Area" => "Central region", "City" => "Aileu"),
    "36" => array ( "Area" => "Central region", "City" => "Liquica"),
    "33" => array ( "Area" => "Central region", "City" => "Dili"),
    "32" => array ( "Area" => "Central region", "City" => "Dili"),
    "31" => array ( "Area" => "Central region", "City" => "Dili"),
    "25" => array ( "Area" => "Western region", "City" => "Oekuse"),
    "24" => array ( "Area" => "Western region", "City" => "Ainaro"),
    "23" => array ( "Area" => "Western region", "City" => "Bobonaro"),
    "22" => array ( "Area" => "Western region", "City" => "Cova Lima"),
    "21" => array ( "Area" => "Western region", "City" => "Manufahi")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "670", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Timor-Leste", "Area" => $data["Area"], "City" => $data["City"], "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+670 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Tollfree network with 2 digits NDC and 5 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 11 && substr ( $parameters["Number"], 4, 2) == "80")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "670", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Timor-Leste", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+670 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for PRN network with 2 digits NDC and 5 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 11 && substr ( $parameters["Number"], 4, 2) == "90") 
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "670", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Timor-Leste", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+670 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for Voicemail network with 2 digits NDC and 5 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 11 && substr ( $parameters["Number"], 4, 2) == "71") 
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "670", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Timor-Leste", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOICEMAIL, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+670 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for Paging network with 2 digits NDC and 5 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 11 && substr ( $parameters["Number"], 4, 2) == "79") 
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "670", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Timor-Leste", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+670 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * If reached here, number wasn't identified as a valid Timor-Leste phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
