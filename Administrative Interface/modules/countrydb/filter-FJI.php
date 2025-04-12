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
 * related to country database of Fiji.
 *
 * Reference: https://www.itu.int/oth/T0202000048/en (2008-09-11)
 * Reference: https://en.wikipedia.org/wiki/Telephone_numbers_in_Fiji
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
 * E.164 Fiji country hook
 */
framework_add_filter ( "e164_identify_country_FJI", "e164_identify_country_FJI");

/**
 * E.164 Fijian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "FJI" (code for Fiji). This hook
 * will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_FJI ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Fiji
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+679")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Fiji has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "99" => "Vodafone Fiji",
    "98" => "Inkk Mobile",
    "97" => "Vodafone Fiji",
    "96" => "Inkk Mobile",
    "95" => "Inkk Mobile",
    "94" => "Vodafone Fiji",
    "93" => "Vodafone Fiji",
    "92" => "Vodafone Fiji",
    "91" => "Vodafone Fiji",
    "90" => "Vodafone Fiji",
    "89" => "Vodafone Fiji",
    "87" => "Inkk Mobile",
    "86" => "Vodafone Fiji",
    "84" => "Inkk Mobile",
    "83" => "Vodafone Fiji",
    "80" => "Vodafone Fiji",
    "74" => "Digicel Fiji Ltd.",
    "73" => "Digicel Fiji Ltd.",
    "72" => "Digicel Fiji Ltd.",
    "71" => "Digicel Fiji Ltd.",
    "70" => "Digicel Fiji Ltd.",
    "58" => "Vodafone Fiji",
    "29" => "Vodafone Fiji",
    "28" => "Vodafone Fiji",
    "27" => "Vodafone Fiji",
    "22" => "Inkk Mobile",
    "21" => "Inkk Mobile",
    "20" => "Inkk Mobile",
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "679", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Fiji", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+679 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "400" => "",
    "339" => "Nasinu, Laucala Beach, Caubati, Kinoya, Nadera, Valelevu, Nadawa, Nepani, Nasole, Kalabu, Laqere, Makoi and Narere",
    "338" => "Samabula, Raiwai, Raiwaqa, Vatuwaqa and Nabua",
    "336" => "Delainavesi, Lami and nearby areas",
    "334" => "Nasinu, Laucala Beach, Caubati, Kinoya, Nadera, Valelevu, Nadawa, Nepani, Nasole, Kalabu, Laqere, Makoi and Narere",
    "333" => "Suva City CBD and surrounding areas",
    "332" => "Tamavua, Namadi Heights areas",
    "88" => "Vanua Levu (Labasa, Savusavu, Nabouwalu and Taveuni), Laucala Island and Lau Group of Islands",
    "85" => "Northern Division",
    "82" => "Northern Division",
    "67" => "Nadi",
    "66" => "Lautoka, Ba, Vatukoula, Tavua and Rakiraki",
    "65" => "Coral Coast and Sigatoka",
    "63" => "Western Division",
    "62" => "Western Division",
    "60" => "Lau Group of Islands",
    "38" => "Central Division (Suva)",
    "37" => "Central Eastern Division",
    "36" => "Central Eastern Division",
    "35" => "Central Eastern Division",
    "34" => "Suva, Nausori, Korovou (Tailevu), Lami, Navua, Deuba and Pacific Harbour",
    "33" => "",
    "32" => "Suva City CBD"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "679", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Fiji", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+679 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Fijian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
