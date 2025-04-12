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
 * related to country database of Tonga.
 *
 * Reference: https://www.itu.int/oth/T02020000D3/en (2021-08-30)
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
 * E.164 Tonga country hook
 */
framework_add_filter ( "e164_identify_country_TON", "e164_identify_country_TON");

/**
 * E.164 Tonga area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "TON" (code for Tonga). This
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
function e164_identify_country_TON ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Tonga
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+676")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Tonga has 9, 11 or 12 digits E.164 format
   */
  if ( ! ( strlen ( $parameters["Number"]) == 9 || strlen ( $parameters["Number"]) == 11 || strlen ( $parameters["Number"]) == 12))
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "890" => "Digicel Tonga Ltd.",
    "880" => "Digicel Tonga Ltd.",
    "870" => "Digicel Tonga Ltd.",
    "860" => "Digicel Tonga Ltd.",
    "840" => "Digicel Tonga Ltd.",
    "780" => "Tonga Communications Company",
    "770" => "Tonga Communications Company",
    "760" => "Tonga Communications Company",
    "750" => "Tonga Communications Company",
    "740" => "Tonga Communications Company",
    "730" => "Tonga Communications Company",
    "720" => "Tonga Communications Company",
    "556" => "Toko Wireless Ltd.",
    "555" => "Toko Wireless Ltd.",
    "554" => "Toko Wireless Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "676", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Tonga", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+676 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 3 digits SN
   */
  $prefixes = array (
    "79" => "Tonga Communications Corporation",
    "76" => "Tonga Communications Corporation",
    "75" => "Tonga Communications Corporation",
    "74" => "Tonga Communications Corporation",
    "72" => "Tonga Communications Corporation",
    "71" => "Tonga Communications Corporation",
    "70" => "Tonga Communications Corporation",
    "69" => "Tonga Communications Corporation",
    "60" => "Tonga Communications Corporation",
    "50" => "Tonga Communications Corporation",
    "43" => "Tonga Communications Corporation",
    "42" => "Tonga Communications Corporation",
    "41" => "Tonga Communications Corporation",
    "40" => "Tonga Communications Corporation",
    "38" => "Tonga Communications Corporation",
    "37" => "Tonga Communications Corporation",
    "36" => "Tonga Communications Corporation",
    "35" => "Tonga Communications Corporation",
    "34" => "Tonga Communications Corporation",
    "33" => "Tonga Communications Corporation",
    "32" => "Tonga Communications Corporation",
    "31" => "Tonga Communications Corporation",
    "30" => "Tonga Communications Corporation",
    "29" => "Tonga Communications Corporation",
    "28" => "Tonga Communications Corporation",
    "27" => "Tonga Communications Corporation",
    "26" => "Tonga Communications Corporation",
    "25" => "Tonga Communications Corporation",
    "24" => "Tonga Communications Corporation",
    "23" => "Tonga Communications Corporation",
    "22" => "Tonga Communications Corporation",
    "21" => "Tonga Communications Corporation",
    "20" => "Tonga Communications Corporation"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 9 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "676", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Tonga", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+676 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "559" => "Toko Wireless Ltd.",
    "558" => "Toko Wireless Ltd.",
    "557" => "Toko Wireless Ltd.",
    "553" => "Toko Wireless Ltd.",
    "552" => "Toko Wireless Ltd.",
    "551" => "Toko Wireless Ltd.",
    "550" => "Toko Wireless Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "676", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Tonga", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+676 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 4 digits SN
   */
  if ( ( strlen ( $parameters["Number"]) == 11 || strlen ( $parameters["Number"]) == 12) && substr ( $parameters["Number"], 4, 3) == "800")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "676", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Tonga", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+676 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * If reached here, number wasn't identified as a valid Tonga phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
