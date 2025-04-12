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
 * related to country database of Switzerland.
 *
 * Reference: https://www.itu.int/oth/T02020000C8/en (2016-12-07)
 *            https://www.bakom.admin.ch/bakom/en/homepage/telecommunication/numbering-and-telephony/number-blocks-and-codes.html (2023-01-10)
 *            https://www.bakom.admin.ch/dam/bakom/en/dokumente/tc/rechtliche_grundlagen/sr_784_101_113_22nummerierungsplane164.pdf.download.pdf/sr_784_101_113_22numberingplane1642002.pdf (2010-01-28)
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
 * E.164 Switzerland country hook
 */
framework_add_filter ( "e164_identify_country_CHE", "e164_identify_country_CHE");

/**
 * E.164 Switzerland area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "CHE" (code for
 * Switzerland). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_CHE ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Switzerland
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+41")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Switzerland has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "79",
    "78",
    "77",
    "76",
    "75",
    "73"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "41", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Switzerland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+41 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "91" => "Bellinzona",
    "81" => "Chur",
    "71" => "St. Gallen",
    "62" => "Olten",
    "61" => "Basel",
    "58" => "",
    "56" => "Baden",
    "55" => "Rapperswil",
    "52" => "Winterthur",
    "51" => "",
    "44" => "Zurich",
    "43" => "Zurich",
    "41" => "Lucerne",
    "34" => "Burgdorf, Langnau i.E",
    "33" => "Thun",
    "32" => "Biel/Bienne, Neuchâtel, Solothurn, Jura",
    "31" => "Bern",
    "27" => "Sion",
    "26" => "Fribourg",
    "24" => "Yverdon, Aigle",
    "22" => "Geneva",
    "21" => "Lausanne",
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "41", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Switzerland", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+41 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for paging network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 2) == "74")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "41", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Switzerland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+41 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for Tollfree network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "800")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "41", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Switzerland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+41 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for voicemail network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "860")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "41", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Switzerland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOICEMAIL, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+41 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for VPN network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "869")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "41", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Switzerland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VPN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+41 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for PRN network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "906",
    "901",
    "900"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "41", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Switzerland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+41 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Switzerland phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
