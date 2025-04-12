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
 * related to country database of Grenada.
 *
 * Reference: https://www.itu.int/oth/T0202000057/en (2014-04-24)
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
 * E.164 Grenada country hook
 */
framework_add_filter ( "e164_identify_country_GRD", "e164_identify_country_GRD");

/**
 * E.164 Grenadian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "GRD" (code for Grenada). This
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
function e164_identify_country_GRD ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Grenada
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1473")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Grenada has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "901" => "Affordable Island Communication Inc.",
    "538" => "AWS Grenada",
    "537" => "AWS Grenada",
    "536" => "AWS Grenada",
    "535" => "AWS Grenada",
    "534" => "AWS Grenada",
    "533" => "AWS Grenada",
    "521" => "Affordable Island Communication Inc.",
    "520" => "Affordable Island Communication Inc.",
    "420" => "Digicel Grenada",
    "419" => "Digicel Grenada",
    "418" => "Digicel Grenada",
    "417" => "Digicel Grenada",
    "416" => "Digicel Grenada",
    "415" => "Digicel Grenada",
    "414" => "Digicel Grenada",
    "458" => "Cable and Wireless Grenada",
    "410" => "Cable and Wireless Grenada",
    "409" => "Cable and Wireless Grenada",
    "407" => "Cable and Wireless Grenada",
    "406" => "Cable and Wireless Grenada",
    "405" => "Cable and Wireless Grenada",
    "404" => "Cable and Wireless Grenada",
    "403" => "Cable and Wireless Grenada",
    "402" => "Affordable Island Communication Inc."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1473", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Grenada", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 473 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "938" => "Cable and Wireless Grenada",
    "784" => "Cable and Wireless Grenada",
    "758" => "Cable and Wireless Grenada",
    "638" => "Cable and Wireless Grenada",
    "636" => "Cable and Wireless Grenada",
    "490" => "Cable and Wireless Grenada",
    "473" => "Cable and Wireless Grenada",
    "468" => "Cable and Wireless Grenada",
    "459" => "Cable and Wireless Grenada",
    "457" => "Cable and Wireless Grenada",
    "456" => "Cable and Wireless Grenada",
    "455" => "Cable and Wireless Grenada",
    "449" => "Cable and Wireless Grenada",
    "444" => "Cable and Wireless Grenada",
    "443" => "Cable and Wireless Grenada",
    "442" => "Cable and Wireless Grenada",
    "441" => "Cable and Wireless Grenada",
    "440" => "Cable and Wireless Grenada",
    "439" => "Cable and Wireless Grenada",
    "438" => "Cable and Wireless Grenada",
    "437" => "Cable and Wireless Grenada",
    "436" => "Cable and Wireless Grenada",
    "435" => "Cable and Wireless Grenada",
    "408" => "Cable and Wireless Grenada",
    "386" => "Cable and Wireless Grenada",
    "329" => "Cable and Wireless Grenada",
    "328" => "Cable and Wireless Grenada",
    "269" => "Cable and Wireless Grenada",
    "232" => "Columbus Communications Grenada Ltd.",
    "231" => "Columbus Communications Grenada Ltd.",
    "230" => "Columbus Communications Grenada Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1473", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Grenada", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 473 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Grenadian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
