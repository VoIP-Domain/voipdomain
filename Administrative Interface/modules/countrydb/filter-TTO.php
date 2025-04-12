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
 * related to country database of Trinidad and Tobago.
 *
 * Reference: https://www.itu.int/oth/T02020000D4/en (2022-07-14)
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
 * E.164 Trinidad and Tobago country hook
 */
framework_add_filter ( "e164_identify_country_TTO", "e164_identify_country_TTO");

/**
 * E.164 Trinidad and Tobago area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "TTO" (code for
 * Trinidad and Tobago). This hook will verify if phone number is valid,
 * returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_TTO ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Trinidad and Tobago
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1868")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Trinidad and Tobago has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "719" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "718" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "717" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "716" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "715" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "714" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "713" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "712" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "710" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "678" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "620" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "484" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "483" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "482" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "481" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "480" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "436" => "Digicel Trinidad and Tobago Ltd.",
    "435" => "Digicel Trinidad and Tobago Ltd.",
    "434" => "Digicel Trinidad and Tobago Ltd.",
    "433" => "Digicel Trinidad and Tobago Ltd.",
    "432" => "Digicel Trinidad and Tobago Ltd.",
    "431" => "Digicel Trinidad and Tobago Ltd.",
    "430" => "Digicel Trinidad and Tobago Ltd.",
    "319" => "Digicel Trinidad and Tobago Ltd.",
    "318" => "Digicel Trinidad and Tobago Ltd.",
    "317" => "Digicel Trinidad and Tobago Ltd.",
    "316" => "Digicel Trinidad and Tobago Ltd.",
    "315" => "Digicel Trinidad and Tobago Ltd.",
    "314" => "Digicel Trinidad and Tobago Ltd.",
    "313" => "Digicel Trinidad and Tobago Ltd.",
    "312" => "Digicel Trinidad and Tobago Ltd.",
    "310" => "Digicel Trinidad and Tobago Ltd.",
    "309" => "Digicel Trinidad and Tobago Ltd.",
    "308" => "Digicel Trinidad and Tobago Ltd.",
    "307" => "Digicel Trinidad and Tobago Ltd.",
    "306" => "Digicel Trinidad and Tobago Ltd.",
    "305" => "Digicel Trinidad and Tobago Ltd.",
    "304" => "Digicel Trinidad and Tobago Ltd.",
    "303" => "Digicel Trinidad and Tobago Ltd.",
    "302" => "Digicel Trinidad and Tobago Ltd.",
    "301" => "Digicel Trinidad and Tobago Ltd.",
    "279" => "Digicel Trinidad and Tobago Ltd.",
    "278" => "Digicel Trinidad and Tobago Ltd.",
    "277" => "Digicel Trinidad and Tobago Ltd.",
    "276" => "Digicel Trinidad and Tobago Ltd.",
    "275" => "Digicel Trinidad and Tobago Ltd.",
    "274" => "Digicel Trinidad and Tobago Ltd.",
    "273" => "Digicel Trinidad and Tobago Ltd.",
    "272" => "Digicel Trinidad and Tobago Ltd.",
    "271" => "Digicel Trinidad and Tobago Ltd.",
    "265" => "Digicel Trinidad and Tobago Ltd.",
    "264" => "Digicel Trinidad and Tobago Ltd.",
    "263" => "Digicel Trinidad and Tobago Ltd.",
    "262" => "Digicel Trinidad and Tobago Ltd.",
    "261" => "Digicel Trinidad and Tobago Ltd.",
    "260" => "Digicel Trinidad and Tobago Ltd.",
    "79" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "78" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "77" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "76" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "75" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "74" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "73" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "72" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "68" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "47" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "46" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "39" => "Digicel Trinidad and Tobago Ltd.",
    "38" => "Digicel Trinidad and Tobago Ltd.",
    "37" => "Digicel Trinidad and Tobago Ltd.",
    "36" => "Digicel Trinidad and Tobago Ltd.",
    "35" => "Digicel Trinidad and Tobago Ltd.",
    "34" => "Digicel Trinidad and Tobago Ltd.",
    "33" => "Digicel Trinidad and Tobago Ltd.",
    "32" => "Digicel Trinidad and Tobago Ltd.",
    "29" => "Digicel Trinidad and Tobago Ltd.",
    "28" => "Digicel Trinidad and Tobago Ltd.",
    "25" => "Digicel Trinidad and Tobago Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1868", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Trinidad and Tobago", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 868 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "822" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "821" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "698" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "697" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "696" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "695" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "694" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "693" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "692" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "691" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "690" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "679" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "677" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "676" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "675" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "674" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "673" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "672" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "671" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "670" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "629" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "628" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "627" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "626" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "625" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "624" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "623" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "622" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "621" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "616" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "615" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "614" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "608" => "Air Link Communications Ltd.",
    "607" => "Lisa Communications Ltd.",
    "242" => "Digicel Trinidad and Tobago Ltd.",
    "241" => "Digicel Trinidad and Tobago Ltd.",
    "240" => "Digicel Trinidad and Tobago Ltd.",
    "219" => "Columbus Communications Trinidad Ltd.",
    "218" => "Columbus Communications Trinidad Ltd.",
    "217" => "Columbus Communications Trinidad Ltd.",
    "216" => "Columbus Communications Trinidad Ltd.",
    "215" => "Columbus Communications Trinidad Ltd.",
    "203" => "Wireless Telecommunications Ltd.",
    "201" => "Open Telecom Ltd.",
    "66" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "65" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "64" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "63" => "Telecommunications Services of Trinidad and Tobago (TSTT)",
    "23" => "Digicel Trinidad and Tobago Ltd.",
    "22" => "Columbus Communications Trinidad Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1868", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Trinidad and Tobago", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 868 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for Voicemail network with 3 digits NDC and 4 digits SN
   */
  if ( substr ( $parameters["Number"], 5, 3) == "619")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1868", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Trinidad and Tobago", "Area" => "", "City" => "", "Operator" => "Telecommunications Services of Trinidad and Tobago (TSTT)", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_VOICEMAIL, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 868 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * If reached here, number wasn't identified as a valid Trinidad and Tobago phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
