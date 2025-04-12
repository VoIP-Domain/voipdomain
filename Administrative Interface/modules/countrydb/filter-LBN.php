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
 * related to country database of Lebanon.
 *
 * Reference: https://www.itu.int/oth/T0202000077/en (2015-06-12)
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
 * E.164 Lebanon country hook
 */
framework_add_filter ( "e164_identify_country_LBN", "e164_identify_country_LBN");

/**
 * E.164 Lebanon area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "LBN" (code for Lebanon). This
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
function e164_identify_country_LBN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Lebanon
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+961")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 5 or 6 digits SN
   */
  $prefixes = array (
    "79324" => array ( "company" => "Alfa company", "digits" => 8),
    "79323" => array ( "company" => "Alfa company", "digits" => 8),
    "79322" => array ( "company" => "Alfa company", "digits" => 8),
    "79321" => array ( "company" => "Alfa company", "digits" => 8),
    "79320" => array ( "company" => "Alfa company", "digits" => 8),
    "78979" => array ( "company" => "Touch company", "digits" => 8),
    "78978" => array ( "company" => "Touch company", "digits" => 8),
    "78977" => array ( "company" => "Touch company", "digits" => 8),
    "78976" => array ( "company" => "Touch company", "digits" => 8),
    "78975" => array ( "company" => "Touch company", "digits" => 8),
    "7931" => array ( "company" => "Alfa company", "digits" => 8),
    "7930" => array ( "company" => "Alfa company", "digits" => 8),
    "7899" => array ( "company" => "Touch company", "digits" => 8),
    "7898" => array ( "company" => "Touch company", "digits" => 8),
    "7894" => array ( "company" => "", "digits" => 8),
    "7893" => array ( "company" => "", "digits" => 8),
    "7892" => array ( "company" => "", "digits" => 8),
    "7891" => array ( "company" => "", "digits" => 8),
    "7890" => array ( "company" => "", "digits" => 8),
    "810" => array ( "company" => "Touch company", "digits" => 7),
    "811" => array ( "company" => "Alfa company", "digits" => 7),
    "812" => array ( "company" => "Alfa company", "digits" => 7),
    "813" => array ( "company" => "Alfa company", "digits" => 7),
    "814" => array ( "company" => "Alfa company", "digits" => 7),
    "815" => array ( "company" => "Alfa company", "digits" => 7),
    "816" => array ( "company" => "Touch company", "digits" => 7),
    "817" => array ( "company" => "Touch company", "digits" => 7),
    "818" => array ( "company" => "Touch company", "digits" => 7),
    "819" => array ( "company" => "Touch company", "digits" => 7),
    "792" => array ( "company" => "", "digits" => 8),
    "791" => array ( "company" => "", "digits" => 8),
    "788" => array ( "company" => "", "digits" => 8),
    "769" => array ( "company" => "", "digits" => 8),
    "768" => array ( "company" => "", "digits" => 8),
    "767" => array ( "company" => "", "digits" => 8),
    "766" => array ( "company" => "", "digits" => 8),
    "765" => array ( "company" => "", "digits" => 8),
    "764" => array ( "company" => "", "digits" => 8),
    "763" => array ( "company" => "", "digits" => 8),
    "761" => array ( "company" => "", "digits" => 8),
    "760" => array ( "company" => "", "digits" => 8),
    "71" => array ( "company" => "", "digits" => 8),
    "70" => array ( "company" => "", "digits" => 8),
    "3" => array ( "company" => "", "digits" => 7)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["digits"])
    {
      if ( $data["digits"] == 7)
      {
        $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+961 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8));
      } else {
        $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+961 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9));
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "961", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Lebanon", "Area" => "", "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => $callformats));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "799" => "South Lebanon",
    "798" => "South Lebanon",
    "797" => "South Lebanon",
    "796" => "South Lebanon",
    "795" => "South Lebanon",
    "794" => "South Lebanon",
    "790" => "South Lebanon",
    "787" => "South Lebanon",
    "786" => "South Lebanon",
    "785" => "South Lebanon",
    "784" => "South Lebanon",
    "783" => "South Lebanon",
    "782" => "South Lebanon",
    "781" => "South Lebanon",
    "780" => "South Lebanon",
    "762" => "South Lebanon",
    "89" => "South Lebanon",
    "88" => "South Lebanon",
    "87" => "South Lebanon",
    "86" => "South Lebanon",
    "85" => "South Lebanon",
    "84" => "South Lebanon",
    "83" => "South Lebanon",
    "82" => "South Lebanon",
    "80" => "South Lebanon",
    "77" => "South Lebanon",
    "75" => "South Lebanon",
    "74" => "South Lebanon",
    "73" => "South Lebanon",
    "72" => "South Lebanon",
    "9" => "Mount Lebanon (Jbeil & Keserwan area)",
    "6" => "North Lebanon",
    "5" => "Mount Lebanon (Chouf area)",
    "4" => "Mount Lebanon (Metn area)",
    "1" => "Beirut"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "961", "NDC" => (string) $prefix, "Country" => "Lebanon", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+961 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Lebanon phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
