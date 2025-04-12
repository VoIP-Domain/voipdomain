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
 * related to country database of Samoa.
 *
 * Reference: https://www.itu.int/oth/T02020000B4/en (2009-11-06)
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
 * E.164 Samoa country hook
 */
framework_add_filter ( "e164_identify_country_WSM", "e164_identify_country_WSM");

/**
 * E.164 Samoan area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "WSM" (code for Samoa). This
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
function e164_identify_country_WSM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Samoa
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+685")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Samoa has 9 or 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 9 && strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "729" => "Digicel",
    "728" => "Digicel",
    "726" => "Digicel",
    "725" => "Digicel",
    "724" => "Digicel",
    "723" => "Digicel",
    "722" => "Digicel",
    "721" => "Digicel",
    "720" => "Digicel",
    "77" => "Digicel",
    "76" => "SamoaTel",
    "75" => "SamoaTel"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "685", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Samoa", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+685 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 3 digits SN
   */
  $prefixes = array (
    "3449" => "Apia",
    "3448" => "Apia",
    "3447" => "Apia",
    "3446" => "Apia",
    "3445" => "Apia",
    "3443" => "Apia",
    "3442" => "Apia",
    "3441" => "Apia",
    "3440" => "Apia",
    "559" => "Savaii",
    "558" => "Savaii",
    "557" => "Savaii",
    "556" => "Savaii",
    "554" => "Savaii",
    "553" => "Savaii",
    "552" => "Savaii",
    "551" => "Savaii",
    "550" => "Savaii",
    "369" => "Apia",
    "368" => "Apia",
    "367" => "Apia",
    "365" => "Apia",
    "364" => "Apia",
    "363" => "Apia",
    "362" => "Apia",
    "361" => "Apia",
    "360" => "Apia",
    "349" => "Apia",
    "348" => "Apia",
    "347" => "Apia",
    "346" => "Apia",
    "345" => "Apia",
    "343" => "Apia",
    "342" => "Apia",
    "341" => "Apia",
    "340" => "Apia",
    "69" => "Apia",
    "68" => "Apia",
    "67" => "Apia",
    "66" => "Apia",
    "65" => "Apia",
    "64" => "Apia",
    "63" => "Apia",
    "62" => "Apia",
    "61" => "Apia",
    "59" => "Savaii",
    "58" => "Savaii",
    "57" => "Savaii",
    "56" => "Savaii",
    "54" => "Savaii",
    "53" => "Savaii",
    "52" => "Savaii",
    "51" => "Savaii",
    "50" => "Savaii",
    "39" => "Apia",
    "38" => "Apia",
    "37" => "Apia",
    "35" => "Apia",
    "33" => "Apia",
    "32" => "Apia",
    "31" => "Apia",
    "30" => "Apia",
    "4" => "Upolu Rural",
    "2" => "Apia"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( strlen ( $parameters["Number"]) == 9 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "685", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Samoa", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+685 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for FMC network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "84" => "Digicel"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "685", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Samoa", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+685 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Samoan phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
