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
 * related to country database of Mongolia.
 *
 * Reference: https://www.itu.int/oth/T020200008E/en (2020-05-13)
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
 * E.164 Mongolia country hook
 */
framework_add_filter ( "e164_identify_country_MNG", "e164_identify_country_MNG");

/**
 * E.164 Mongolian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MNG" (code for Mongolia). This
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
function e164_identify_country_MNG ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Mongolia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+976")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Mongolia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "971" => "G-MOBILE",
    "970" => "G-MOBILE",
    "934" => "G-MOBILE",
    "933" => "G-MOBILE",
    "932" => "G-MOBILE",
    "931" => "G-MOBILE",
    "930" => "G-MOBILE",
    "831" => "G-MOBILE",
    "830" => "G-MOBILE",
    "705" => "Mongolia Telecom Company",
    "704" => "Mongolia Telecom Company",
    "703" => "Mongolia Telecom Company",
    "702" => "Mongolia Telecom Company",
    "701" => "Mongolia Telecom Company",
    "700" => "Mongolia Telecom Company",
    "99" => "MOBICON",
    "98" => "G-MOBILE",
    "96" => "SKYTEL",
    "95" => "MOBICON",
    "94" => "MOBICON",
    "91" => "SKYTEL",
    "90" => "SKYTEL",
    "89" => "UNITEL",
    "88" => "UNITEL",
    "86" => "UNITEL",
    "85" => "MOBICON",
    "80" => "UNITEL",
    "55" => "UNITEL",
    "50" => "UNITEL"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "976", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Mongolia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+976 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "7128" => "Mongolia Telecom Company",
    "1148" => "Mongolia Telecom Company",
    "1146" => "Mongolia Telecom Company",
    "1145" => "Mongolia Telecom Company",
    "113" => "Mongolia Telecom Company"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "976", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Mongolia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+976 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "7595" => "MOBINET",
    "7585" => "MOBINET",
    "7577" => "MOBINET",
    "7575" => "MOBINET",
    "7557" => "MOBINET",
    "7555" => "MOBINET",
    "7535" => "MOBINET",
    "7515" => "MOBINET",
    "7511" => "MOBINET",
    "7510" => "MOBINET",
    "7509" => "MOBINET",
    "7507" => "MOBINET",
    "7505" => "MOBINET",
    "7500" => "MOBINET",
    "7533" => "MOBINET",
    "7100" => "MONVSAT NETWORK",
    "781" => "GMOBILENET",
    "780" => "GMOBILENET",
    "767" => "SKYMEDIA",
    "766" => "SKYMEDIA",
    "761" => "SKYMEDIA",
    "760" => "SKYMEDIA",
    "77" => "UNIVISION"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "976", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Mongolia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+976 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Mongolian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
