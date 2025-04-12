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
 * related to country database of Cayman Islands.
 *
 * Reference: https://www.itu.int/oth/T0202000027/en (2007-10-09)
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
 * E.164 Cayman Islands country hook
 */
framework_add_filter ( "e164_identify_country_CYM", "e164_identify_country_CYM");

/**
 * E.164 Cayman Islands area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "CYM" (code for
 * Cayman Islands). This hook will verify if phone number is valid, returning
 * the area code, area name, phone number, others number related information and
 * if possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_CYM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Cayman Islands
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1345")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Cayman Islands has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "939" => "Cable & Wireless Pre Paid GSM",
    "938" => "Cable & Wireless Pre Paid TDMA",
    "929" => "Cable & Wireless Pre Paid GSM",
    "928" => "Cable & Wireless Pre Paid TDMA",
    "927" => "Cable & Wireless Pre Paid TDMA",
    "926" => "Cable & Wireless Post Paid TDMA",
    "925" => "Cable & Wireless Post Paid GSM",
    "924" => "Cable & Wireless Post Paid GSM",
    "919" => "Cable & Wireless Mobile Temp. Location Directory Number",
    "917" => "Cable & Wireless TDMA Pre Paid",
    "916" => "Cable & Wireless TDMA Post Paid",
    "549" => "Wireless Ventures (AT&T) GSM",
    "548" => "Wireless Ventures (AT&T) GSM",
    "547" => "Wireless Ventures (AT&T) GSM",
    "546" => "Wireless Ventures (AT&T) GSM",
    "545" => "Wireless Ventures (AT&T) GSM",
    "527" => "Wireless Ventures (AT&T) GSM",
    "526" => "Wireless Ventures (AT&T) GSM",
    "525" => "Wireless Ventures (AT&T) GSM",
    "517" => "Wireless Ventures (AT&T) GSM",
    "516" => "Wireless Ventures (AT&T) GSM",
    "514" => "Wireless Ventures (AT&T) GSM",
    "329" => "Digicel GSM",
    "327" => "Digicel GSM Pre paid",
    "326" => "Digicel GSM Post Paid",
    "325" => "Digicel GSM",
    "324" => "Digicel GSM",
    "323" => "Digicel GSM"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1345", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Cayman Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+1 345 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "949" => "Cable & Wireless",
    "947" => "Cable & Wireless",
    "946" => "Cable & Wireless",
    "945" => "Cable & Wireless",
    "943" => "Cable & Wireless",
    "940" => "Cable & Wireless",
    "914" => "Cable & Wireless",
    "888" => "Cable & Wireless",
    "848" => "Cable & Wireless",
    "825" => "TeleCayman",
    "815" => "Cable & Wireless",
    "814" => "Cable & Wireless",
    "777" => "Cable & Wireless",
    "769" => "TeleCayman",
    "768" => "TeleCayman",
    "767" => "TeleCayman",
    "766" => "TeleCayman",
    "640" => "Digicel",
    "638" => "Cable & Wireless",
    "623" => "Digicel",
    "444" => "Cable & Wireless",
    "244" => "Cable & Wireless",
    "222" => "Cable & Wireless"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1345", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Cayman Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 345 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for paging service network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "948" => array ( "area" => "Little Cayman and Cayman Brac", "operator" => "Cable & Wireless"),
    "849" => array ( "area" => "", "operator" => "Cable & Wireless")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1345", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Cayman Islands", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 345 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for other services (not specified, but allocated) with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "930" => "Cable & Wireless",
    "749" => "Westtel",
    "747" => "Cable & Wireless",
    "746" => "Westtel",
    "745" => "Westtel",
    "743" => "Westtel",
    "699" => "Infinity Broadband",
    "663" => "E Technologies",
    "626" => "E Technologies",
    "619" => "Infinity Broadband",
    "249" => "E Technologies",
    "235" => "E Technologies",
    "229" => "E Technologies"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1345", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Cayman Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_UNKNOWN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 345 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for Premium Rate Numbers service network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "976" => "Cable & Wireless",
    "266" => "Cable & Wireless"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1345", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Cayman Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 345 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Cayman Islands phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
