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
 * related to country database of Romania.
 *
 * Reference: https://www.itu.int/oth/T02020000AC/en (2016-03-02)
 *            https://www.ancom.ro/en/presentation-of-romanian-national-numbering-plan-according-to-itu-t-recommendation-e129-_5523 (2022-12-27)
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
 * E.164 Romania country hook
 */
framework_add_filter ( "e164_identify_country_ROU", "e164_identify_country_ROU");

/**
 * E.164 Romanian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "ROU" (code for Romania). This
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
function e164_identify_country_ROU ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Romania
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+40")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Romania has 9 to 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 9 || strlen ( $parameters["Number"]) > 12)
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
    "74",
    "73",
    "72",
    "71",
    "69",
    "68",
    "67",
    "66",
    "65",
    "64",
    "63",
    "62",
    "61"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "40", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Romania", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+40 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 or 3 digits NDC and 3 to 6 digits SN
   */
  $prefixes = array (
    "69" => "Sibiu",
    "68" => "Brașov",
    "67" => "Covasna",
    "66" => "Harghita",
    "65" => "Mureș",
    "64" => "Cluj",
    "63" => "Bistrița-Năsăud",
    "62" => "Maramureș",
    "61" => "Satu Mare",
    "60" => "Sălaj",
    "59" => "Bihor",
    "58" => "Alba",
    "57" => "Arad",
    "56" => "Timiș",
    "55" => "Caraș-Severin",
    "54" => "Hunedoara",
    "53" => "Gorj",
    "52" => "Mehedinți",
    "51" => "Dolj",
    "50" => "Vâlcea",
    "49" => "Olt",
    "48" => "Argeș",
    "47" => "Teleorman",
    "46" => "Giurgiu",
    "45" => "Dâmbovița",
    "44" => "Prahova",
    "43" => "Ialomița",
    "42" => "Călărași",
    "41" => "Constanța",
    "40" => "Tulcea",
    "39" => "Brăila",
    "38" => "Buzău",
    "37" => "Vrancea",
    "36" => "Galați",
    "35" => "Vaslui",
    "34" => "Bacău",
    "33" => "Neamț",
    "32" => "Iași",
    "31" => "Botoșani",
    "30" => "Suceava",
    "1" => "București, Ilfov"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( ( substr ( $parameters["Number"], 3, 1) == "2" || substr ( $parameters["Number"], 3, 1) == "3") && strlen ( $parameters["Number"]) >= 9 && strlen ( $parameters["Number"]) <= 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "40", "NDC" => substr ( $parameters["Number"], 3, 1) . $prefix, "Country" => "Romania", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+40 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "800" && strlen ( $parameters["Number"]) == 12)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "40", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Romania", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+40 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for PRN network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "906",
    "903",
    "900"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "40", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Romania", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+40 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Romanian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
