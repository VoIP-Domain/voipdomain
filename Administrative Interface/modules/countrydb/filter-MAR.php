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
 * related to country database of Morocco.
 *
 * Reference: https://www.itu.int/oth/T0202000090/en (2022-09-14)
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
 * E.164 Morocco country hook
 */
framework_add_filter ( "e164_identify_country_MAR", "e164_identify_country_MAR");

/**
 * E.164 Moroccian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MAR" (code for Morocco). This
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
function e164_identify_country_MAR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Morocco
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+212")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Morocco has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "783" => "Orange",
    "782" => "Orange",
    "781" => "Orange",
    "780" => "Orange",
    "768" => "Itisslat Al-Maghrib",
    "767" => "Itisslat Al-Maghrib",
    "766" => "Itisslat Al-Maghrib",
    "765" => "Itisslat Al-Maghrib",
    "764" => "Itisslat Al-Maghrib",
    "763" => "Itisslat Al-Maghrib",
    "762" => "Itisslat Al-Maghrib",
    "761" => "Itisslat Al-Maghrib",
    "760" => "Itisslat Al-Maghrib",
    "722" => "INWI",
    "721" => "INWI",
    "720" => "INWI",
    "699" => "INWI",
    "698" => "INWI",
    "697" => "Itisslat Al-Maghrib",
    "696" => "Itisslat Al-Maghrib",
    "695" => "INWI",
    "694" => "Orange",
    "693" => "Orange",
    "691" => "Orange",
    "690" => "INWI",
    "689" => "Itisslat Al-Maghrib",
    "688" => "Orange",
    "687" => "INWI",
    "684" => "Orange",
    "682" => "Itisslat Al-Maghrib",
    "681" => "INWI",
    "680" => "INWI",
    "679" => "Orange",
    "678" => "Itisslat Al-Maghrib",
    "677" => "Itisslat Al-Maghrib",
    "676" => "Itisslat Al-Maghrib",
    "675" => "Orange",
    "674" => "Orange",
    "673" => "Itisslat Al-Maghrib",
    "672" => "Itisslat Al-Maghrib",
    "671" => "Itisslat Al-Maghrib",
    "670" => "Itisslat Al-Maghrib",
    "669" => "Orange",
    "668" => "Itisslat Al-Maghrib",
    "667" => "Itisslat Al-Maghrib",
    "666" => "Itisslat Al-Maghrib",
    "665" => "Orange",
    "664" => "Orange",
    "663" => "Orange",
    "662" => "Itisslat Al-Maghrib",
    "661" => "Itisslat Al-Maghrib",
    "660" => "Orange",
    "659" => "Itisslat Al-Maghrib",
    "658" => "Itisslat Al-Maghrib",
    "657" => "Orange",
    "656" => "Orange",
    "655" => "Itisslat Al-Maghrib",
    "654" => "Itisslat Al-Maghrib",
    "653" => "Itisslat Al-Maghrib",
    "652" => "Itisslat Al-Maghrib",
    "651" => "Itisslat Al-Maghrib",
    "650" => "Itisslat Al-Maghrib",
    "649" => "Orange",
    "648" => "Itisslat Al-Maghrib",
    "647" => "INWI",
    "646" => "INWI",
    "645" => "Orange",
    "644" => "Orange",
    "643" => "Itisslat Al-Maghrib",
    "642" => "Itisslat Al-Maghrib",
    "641" => "Itisslat Al-Maghrib",
    "640" => "INWI",
    "639" => "Itisslat Al-Maghrib",
    "638" => "INWI",
    "637" => "Itisslat Al-Maghrib",
    "636" => "Itisslat Al-Maghrib",
    "635" => "INWI",
    "634" => "INWI",
    "633" => "INWI",
    "632" => "Orange",
    "631" => "Orange",
    "630" => "INWI",
    "629" => "INWI",
    "628" => "Itisslat Al-Maghrib",
    "627" => "INWI",
    "626" => "INWI",
    "625" => "Orange",
    "624" => "Itisslat Al-Maghrib",
    "623" => "Itisslat Al-Maghrib",
    "622" => "Itisslat Al-Maghrib",
    "621" => "Orange",
    "620" => "Orange",
    "619" => "Orange",
    "618" => "Itisslat Al-Maghrib",
    "617" => "Orange",
    "616" => "Itisslat Al-Maghrib",
    "615" => "Itisslat Al-Maghrib",
    "614" => "Orange",
    "613" => "Itisslat Al-Maghrib",
    "612" => "Orange",
    "611" => "Itisslat Al-Maghrib",
    "610" => "Itisslat Al-Maghrib",
    "553" => "INWI",
    "550" => "INWI",
    "547" => "INWI",
    "546" => "INWI",
    "540" => "INWI",
    "534" => "Orange",
    "533" => "Orange",
    "527" => "INWI",
    "526" => "INWI",
    "77" => "Orange",
    "71" => "INWI",
    "70" => "INWI",
    "60" => "INWI"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "212", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Morocco", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+212 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "52999" => "INWI",
    "52998" => "INWI",
    "52997" => "INWI",
    "52996" => "INWI",
    "52995" => "INWI",
    "52994" => "INWI",
    "52993" => "INWI",
    "52992" => "INWI",
    "52991" => "INWI",
    "52931" => "INWI",
    "52930" => "INWI",
    "5399" => "Maroc Telecom",
    "5398" => "Maroc Telecom",
    "5397" => "Maroc Telecom",
    "5396" => "Maroc Telecom",
    "5395" => "Maroc Telecom",
    "5394" => "Maroc Telecom",
    "5393" => "Maroc Telecom",
    "5389" => "INWI",
    "5388" => "INWI",
    "5381" => "INWI",
    "5380" => "INWI",
    "5368" => "Maroc Telecom",
    "5367" => "Maroc Telecom",
    "5366" => "Maroc Telecom",
    "5365" => "Maroc Telecom",
    "5364" => "Maroc Telecom",
    "5363" => "Maroc Telecom",
    "5362" => "Maroc Telecom",
    "5360" => "Maroc Telecom",
    "5359" => "Maroc Telecom",
    "5358" => "Maroc Telecom",
    "5357" => "Maroc Telecom",
    "5356" => "Maroc Telecom",
    "5355" => "Maroc Telecom",
    "5354" => "Maroc Telecom",
    "5353" => "Maroc Telecom",
    "5352" => "Maroc Telecom",
    "5350" => "Maroc Telecom",
    "5299" => "INWI",
    "5298" => "INWI",
    "5292" => "INWI",
    "5291" => "INWI",
    "5290" => "INWI",
    "5289" => "Maroc Telecom",
    "5288" => "Maroc Telecom",
    "5287" => "Maroc Telecom",
    "5286" => "Maroc Telecom",
    "5285" => "Maroc Telecom",
    "5283" => "Maroc Telecom",
    "5282" => "Maroc Telecom",
    "5280" => "Maroc Telecom",
    "5248" => "Maroc Telecom",
    "5247" => "Maroc Telecom",
    "5246" => "Maroc Telecom",
    "5244" => "Maroc Telecom",
    "5243" => "Maroc Telecom",
    "5242" => "Maroc Telecom",
    "5240" => "Maroc Telecom",
    "5238" => "Maroc Telecom",
    "5237" => "Maroc Telecom",
    "5235" => "Maroc Telecom",
    "5234" => "Maroc Telecom",
    "5233" => "Maroc Telecom",
    "5232" => "Maroc Telecom",
    "5231" => "Maroc Telecom",
    "5230" => "Maroc Telecom",
    "532" => "Orange",
    "531" => "Orange",
    "537" => "Maroc Telecom",
    "530" => "Orange",
    "525" => "Orange",
    "522" => "Maroc Telecom",
    "521" => "Orange",
    "520" => "Orange"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "212", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Morocco", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+212 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "8086" => "Maroc Telecom",
    "8085" => "Maroc Telecom",
    "8084" => "Maroc Telecom",
    "8083" => "Maroc Telecom",
    "8082" => "Orange",
    "8081" => "INWI",
    "8080" => "Maroc Telecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "212", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Morocco", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+212 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for VSAT network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "59293" => "Maroc Telecom",
    "59242" => "Gulfsat Maghreb",
    "59241" => "Spacecom",
    "59240" => "Cimecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "212", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Morocco", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+212 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for Payphone network with 3 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 5) == "89293")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "212", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Morocco", "Area" => "", "City" => "", "Operator" => "Maroc Telecom", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PAYPHONE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+212 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
  }

  /**
   * If reached here, number wasn't identified as a valid Moroccian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
