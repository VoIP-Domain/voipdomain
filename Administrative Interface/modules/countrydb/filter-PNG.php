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
 * related to country database of Papua New Guinea.
 *
 * Reference: https://www.itu.int/oth/T02020000A4/en (2022-07-14)
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
 * E.164 Papua New Guinea country hook
 */
framework_add_filter ( "e164_identify_country_PNG", "e164_identify_country_PNG");

/**
 * E.164 Papuan area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "PNG" (code for Papua New
 * Guinea). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_PNG ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Papua New Guinea
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+675")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Papua New Guinea has 11 or 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 11 || strlen ( $parameters["Number"]) > 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "88" => "Digicel (PNG) Limited (Digicel)",
    "81" => "Digivoip Communications Limited",
    "79" => "Digicel (PNG) Limited (Digicel)",
    "78" => "Telikom PNG Limited (Citifon)",
    "77" => "Bemobile (PNG) Limited (BeMobile Vodafone)",
    "76" => "Bemobile (PNG) Limited (BeMobile Vodafone)",
    "75" => "Bemobile (PNG) Limited (BeMobile Vodafone)",
    "74" => "Digicel (PNG) Limited (Digicel)",
    "73" => "Digicel (PNG) Limited (Digicel)",
    "72" => "Digicel (PNG) Limited (Digicel)",
    "71" => "Digicel (PNG) Limited (Digicel)",
    "70" => "Digicel (PNG) Limited (Digicel)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "675", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Papua New Guinea", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+675 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "989" => array ( "Area" => "East New Britain", "Operator" => "Telikom PNG Limited"),
    "987" => array ( "Area" => "East New Britain", "Operator" => "Telikom PNG Limited"),
    "985" => array ( "Area" => "East New Britain", "Operator" => "Telikom PNG Limited"),
    "984" => array ( "Area" => "West New Britain", "Operator" => "Telikom PNG Limited"),
    "983" => array ( "Area" => "New Ireland", "Operator" => "Telikom PNG Limited"),
    "982" => array ( "Area" => "East New Britain", "Operator" => "Telikom PNG Limited"),
    "981" => array ( "Area" => "East New Britain", "Operator" => "Telikom PNG Limited"),
    "980" => array ( "Area" => "East New Britain", "Operator" => "Telikom PNG Limited"),
    "976" => array ( "Area" => "North Solomons", "Operator" => "Telikom PNG Limited"),
    "975" => array ( "Area" => "North Solomons", "Operator" => "Telikom PNG Limited"),
    "973" => array ( "Area" => "North Solomons", "Operator" => "Telikom PNG Limited"),
    "970" => array ( "Area" => "Manus", "Operator" => "Telikom PNG Limited"),
    "649" => array ( "Area" => "Tabubil & Kiunga", "Operator" => "Telikom PNG Limited"),
    "648" => array ( "Area" => "Gulf", "Operator" => "Telikom PNG Limited"),
    "646" => array ( "Area" => "Western", "Operator" => "Telikom PNG Limited"),
    "645" => array ( "Area" => "Western", "Operator" => "Telikom PNG Limited"),
    "644" => array ( "Area" => "MobileSAT", "Operator" => "Telikom PNG Limited"),
    "643" => array ( "Area" => "Milne Bay", "Operator" => "Telikom PNG Limited"),
    "642" => array ( "Area" => "Milne Bay & Oro", "Operator" => "Telikom PNG Limited"),
    "641" => array ( "Area" => "Milne Bay", "Operator" => "Telikom PNG Limited"),
    "549" => array ( "Area" => "Southern Highlands", "Operator" => "Telikom PNG Limited"),
    "547" => array ( "Area" => "Enga", "Operator" => "Telikom PNG Limited"),
    "546" => array ( "Area" => "Western Highlands", "Operator" => "Telikom PNG Limited"),
    "545" => array ( "Area" => "Western Highlands", "Operator" => "Telikom PNG Limited"),
    "542" => array ( "Area" => "Western Highlands", "Operator" => "Telikom PNG Limited"),
    "541" => array ( "Area" => "Western Highlands", "Operator" => "Telikom PNG Limited"),
    "540" => array ( "Area" => "Southern Highlands", "Operator" => "Telikom PNG Limited"),
    "537" => array ( "Area" => "Eastern Highlands", "Operator" => "Telikom PNG Limited"),
    "535" => array ( "Area" => "Chimbu", "Operator" => "Telikom PNG Limited"),
    "532" => array ( "Area" => "Eastern Highlands", "Operator" => "Telikom PNG Limited"),
    "531" => array ( "Area" => "Eastern Highlands", "Operator" => "Telikom PNG Limited"),
    "530" => array ( "Area" => "Eastern Highlands", "Operator" => "Telikom PNG Limited"),
    "459" => array ( "Area" => "Sandaun", "Operator" => "Telikom PNG Limited"),
    "458" => array ( "Area" => "Sandaun", "Operator" => "Telikom PNG Limited"),
    "457" => array ( "Area" => "Sandaun", "Operator" => "Telikom PNG Limited"),
    "456" => array ( "Area" => "East Sepik", "Operator" => "Telikom PNG Limited"),
    "450" => array ( "Area" => "East Sepik", "Operator" => "Telikom PNG Limited"),
    "424" => array ( "Area" => "Madang", "Operator" => "Telikom PNG Limited"),
    "423" => array ( "Area" => "Madang", "Operator" => "Telikom PNG Limited"),
    "422" => array ( "Area" => "Madang", "Operator" => "Telikom PNG Limited"),
    "329" => array ( "Area" => "Central Rural", "Operator" => "Telikom PNG Limited"),
    "328" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "327" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "326" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "325" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "324" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "323" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "322" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "321" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "320" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "312" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "311" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "310" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited"),
    "30" => array ( "Area" => "Port Moresby", "Operator" => "Telikom PNG Limited")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "675", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Papua New Guinea", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+675 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Paging network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "271" => "Telikom PNG Limited",
    "270" => "Telikom PNG Limited"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "675", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Papua New Guinea", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+675 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for VSAT network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "278" => "Telikom PNG Limited (DomSat)",
    "276" => "Telikom PNG Limited (TeliSat)",
    "275" => "Telikom PNG Limited (VSAT Systems)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "675", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Papua New Guinea", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+675 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "207" => "Digivoip Communications Limited",
    "205" => "Quadima Holdings Limited",
    "204" => "Digitec PNG Limited",
    "203" => "Oceanic Broadband",
    "202" => "Digicel (PNG) Limited",
    "201" => "Telikom PNG Limited",
    "200" => "Bemobile Limited"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "675", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Papua New Guinea", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+675 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Papuan phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
