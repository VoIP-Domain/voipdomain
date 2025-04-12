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
 * related to country database of Suriname.
 *
 * Reference: https://www.itu.int/oth/T02020000C5/en (2017-05-15)
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
 * E.164 Suriname country hook
 */
framework_add_filter ( "e164_identify_country_SUR", "e164_identify_country_SUR");

/**
 * E.164 Surinamian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "SUR" (code for
 * Suriname). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_SUR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Suriname
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+597")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Suriname has 10 or 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 10 || strlen ( $parameters["Number"]) > 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "779" => "",
    "778" => "",
    "777" => "",
    "776" => "",
    "775" => "",
    "774" => "Telesur",
    "773" => "Telesur",
    "772" => "Telesur",
    "771" => "Telesur",
    "770" => "Telesur",
    "89" => "Telesur",
    "88" => "Telesur",
    "87" => "Telesur",
    "86" => "Telesur",
    "85" => "Telesur",
    "84" => "Intelsur",
    "83" => "Intelsur",
    "82" => "Digicel Suriname N.V.",
    "81" => "Digicel Suriname N.V.",
    "76" => "Digicel Suriname N.V.",
    "75" => "Telesur",
    "74" => "Digicel Suriname N.V.",
    "73" => "",
    "72" => "Digicel Suriname N.V.",
    "71" => "Digicel Suriname N.V."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "597", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Suriname", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+597 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "58" => array ( "Area" => "Paramaribo", "Operator" => "Telesur"),
    "55" => array ( "Area" => "Paramaribo", "Operator" => "Telesur"),
    "54" => array ( "Area" => "Paramaribo", "Operator" => "Telesur"),
    "53" => array ( "Area" => "Paramaribo", "Operator" => "Telesur"),
    "52" => array ( "Area" => "Paramaribo", "Operator" => "Telesur"),
    "40" => array ( "Area" => "Paramaribo", "Operator" => "Telesur"),
    "37" => array ( "Area" => "Mid and East Suriname", "Operator" => "Telesur"),
    "36" => array ( "Area" => "Mid and East Suriname", "Operator" => "Telesur"),
    "35" => array ( "Area" => "Mid and East Suriname", "Operator" => "Telesur"),
    "34" => array ( "Area" => "Mid and East Suriname", "Operator" => "Telesur"),
    "33" => array ( "Area" => "Mid and East Suriname", "Operator" => "Telesur"),
    "32" => array ( "Area" => "Mid and East Suriname", "Operator" => "Telesur"),
    "31" => array ( "Area" => "Mid and East Suriname", "Operator" => "Telesur"),
    "30" => array ( "Area" => "Mid and East Suriname", "Operator" => "Telesur"),
    "23" => array ( "Area" => "West Suriname", "Operator" => "Telesur"),
    "22" => array ( "Area" => "West Suriname", "Operator" => "Telesur"),
    "21" => array ( "Area" => "West Suriname", "Operator" => "Telesur")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 10 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "597", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Suriname", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+597 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for FMC network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "684" => "Telesur",
    "683" => "Telesur",
    "682" => "Telesur",
    "681" => "Telesur",
    "680" => "Telesur"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "597", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Suriname", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+597 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 4 or 5 digits SN
   */
  $prefixes = array (
    "5904" => array ( "Length" => 11, "Operator" => "Intelsur"),
    "5903" => array ( "Length" => 11, "Operator" => "Intelsur"),
    "5902" => array ( "Length" => 11, "Operator" => "Intelsur"),
    "5901" => array ( "Length" => 11, "Operator" => "Intelsur"),
    "5900" => array ( "Length" => 11, "Operator" => "Intelsur"),
    "56" => array ( "Length" => 10, "Operator" => "Telesur"),
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == $data["Length"] && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "597", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Suriname", "Area" => "", "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+597 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Surinamian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
