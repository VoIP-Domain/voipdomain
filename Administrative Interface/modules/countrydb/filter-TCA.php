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
 * related to country database of Turks and Caicos Islands.
 *
 * Reference: https://www.itu.int/oth/T02020000D8/en (2016-07-08)
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
 * E.164 Turks and Caicos Islands country hook
 */
framework_add_filter ( "e164_identify_country_TCA", "e164_identify_country_TCA");

/**
 * E.164 Turks and Caicos Islands area number identification hook. This hook is
 * an e164_identify sub hook, called when the ISO3166 Alpha3 are "TCA" (code for
 * Turks and Caicos Islands). This hook will verify if phone number is valid,
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
function e164_identify_country_TCA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Turks and Caicos Islands
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1649")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Turks and Caicos Islands has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefimxes = array (
    "443" => "IslandCom Communications Ltd.",
    "442" => "IslandCom Communications Ltd.",
    "441" => "IslandCom Communications Ltd.",
    "433" => "IslandCom Communications Ltd.",
    "432" => "IslandCom Communications Ltd.",
    "431" => "IslandCom Communications Ltd.",
    "348" => "Digicel (TCI) Ltd.",
    "347" => "Digicel (TCI) Ltd.",
    "345" => "Digicel (TCI) Ltd.",
    "344" => "Digicel (TCI) Ltd.",
    "343" => "Digicel (TCI) Ltd.",
    "342" => "Digicel (TCI) Ltd.",
    "341" => "Digicel (TCI) Ltd.",
    "333" => "Digicel (TCI) Ltd.",
    "332" => "Digicel (TCI) Ltd.",
    "331" => "Digicel (TCI) Ltd.",
    "245" => "Cable and Wireless (West Indies) Ltd.",
    "244" => "Cable and Wireless (West Indies) Ltd.",
    "243" => "Cable and Wireless (West Indies) Ltd.",
    "242" => "Cable and Wireless (West Indies) Ltd.",
    "241" => "Cable and Wireless (West Indies) Ltd.",
    "239" => "Cable and Wireless (TCI) Ltd.",
    "232" => "Cable and Wireless (West Indies) Ltd.",
    "231" => "Cable and Wireless (West Indies) Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1649", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Turks and Caicos Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 649 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "950" => array ( "Area" => "West Caicos", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "949" => array ( "Area" => "Parrot Cay", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "948" => array ( "Area" => "Pine Cay", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "947" => array ( "Area" => "North Caicos", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "946" => array ( "Area" => "Grand Turk", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "945" => array ( "Area" => "Salt Cay", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "944" => array ( "Area" => "Middle Caicos", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "943" => array ( "Area" => "South Caicos", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "942" => array ( "Area" => "Ambergris Cay", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "941" => array ( "Area" => "Providenciales", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "940" => array ( "Area" => "East Caicos", "Operator" => "Cable and Wireless (West Indies) Ltd."),
    "712" => array ( "Area" => "", "Operator" => "Andrew's Communcation Ltd."),
    "266" => array ( "Area" => "", "Operator" => "Cable and Wireless (West Indies) Ltd.")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1649", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Turks and Caicos Islands", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 649 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for FMC network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "339" => "Digicel (TCI) Ltd.",
    "338" => "Digicel (TCI) Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1649", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Turks and Caicos Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 649 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "711" => "Andrew's Communcation Ltd.",
    "710" => "Andrew's Communcation Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1649", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Turks and Caicos Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 649 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for Audiotext network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "446" => "Cable and Wireless (West Indies) Ltd.",
    "445" => "Cable and Wireless (West Indies) Ltd.",
    "444" => "Cable and Wireless (West Indies) Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1649", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Turks and Caicos Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_AUDIOTEXT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 649 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Turks and Caicos Islands phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
