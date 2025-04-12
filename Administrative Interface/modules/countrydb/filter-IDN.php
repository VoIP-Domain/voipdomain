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
 * related to country database of Indonesia.
 *
 * Reference: https://www.itu.int/oth/T0202000064/en (2006-07-20)
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
 * E.164 Indonesia country hook
 */
framework_add_filter ( "e164_identify_country_IDN", "e164_identify_country_IDN");

/**
 * E.164 Indonesian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "IDN" (code for
 * Indonesia). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_IDN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Indonesia
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+62")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 to 8 digits SN
   */
  $prefixes = array (
    "8169" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8168" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8167" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8166" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8165" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8164" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8163" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8162" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8161" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8160" => array ( "area" => "", "minimum" => 10, "maximum" => 10),
    "8129" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8128" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8127" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8126" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8125" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8124" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8123" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8122" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8121" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8119" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8118" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8117" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8116" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8115" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8114" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8113" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8112" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8111" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "8110" => array ( "area" => "", "minimum" => 10, "maximum" => 10),
    "829" => array ( "area" => "Irian", "minimum" => 9, "maximum" => 9),
    "828" => array ( "area" => "", "minimum" => 9, "maximum" => 9),
    "827" => array ( "area" => "Medan", "minimum" => 9, "maximum" => 9),
    "826" => array ( "area" => "Medan", "minimum" => 9, "maximum" => 9),
    "825" => array ( "area" => "Kalimantan", "minimum" => 9, "maximum" => 9),
    "824" => array ( "area" => "Sulawesi", "minimum" => 9, "maximum" => 9),
    "823" => array ( "area" => "Surabaya", "minimum" => 9, "maximum" => 9),
    "822" => array ( "area" => "Solo", "minimum" => 9, "maximum" => 9),
    "821" => array ( "area" => "Jakarta", "minimum" => 9, "maximum" => 9),
    "820" => array ( "area" => "", "minimum" => 9, "maximum" => 9),
    "819" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "818" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "817" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "815" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "813" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "88" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "86" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "85" => array ( "area" => "", "minimum" => 9, "maximum" => 10),
    "83" => array ( "area" => "Surabaya", "minimum" => 9, "maximum" => 9)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= $data["minimum"] + 3 && strlen ( $parameters["Number"]) <= $data["maximum"] + 3)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "62", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Indonesia", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 2) . ")-" . substr ( $parameters["Number"], 5, 4) . "-" . substr ( $parameters["Number"], 9), "International" => "+62 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 5 to 8 digits SN
   */
  $prefixes = array (
    "77" => array ( "area" => "Batam", "minimum" => 8, "maximum" => 9),
    "76" => array ( "area" => "Riau", "minimum" => 8, "maximum" => 9),
    "75" => array ( "area" => "West Sumatra", "minimum" => 8, "maximum" => 9),
    "74" => array ( "area" => "South Sumatra et Betar Lampung", "minimum" => 8, "maximum" => 9),
    "73" => array ( "area" => "South Sumatra et Betar Lampung", "minimum" => 8, "maximum" => 9),
    "72" => array ( "area" => "South Sumatra et Betar Lampung", "minimum" => 7, "maximum" => 9),
    "71" => array ( "area" => "South Sumatra et Betar Lampung", "minimum" => 8, "maximum" => 9),
    "70" => array ( "area" => "South Sumatra et Betar Lampung", "minimum" => 8, "maximum" => 8),
    "39" => array ( "area" => "Kupang", "minimum" => 7, "maximum" => 9),
    "38" => array ( "area" => "Ende", "minimum" => 7, "maximum" => 9),
    "37" => array ( "area" => "Sumbawa", "minimum" => 7, "maximum" => 9),
    "36" => array ( "area" => "Denpasar, Bali", "minimum" => 7, "maximum" => 9),
    "35" => array ( "area" => "Madiun", "minimum" => 7, "maximum" => 9),
    "34" => array ( "area" => "Malang", "minimum" => 7, "maximum" => 9),
    "33" => array ( "area" => "Jember", "minimum" => 7, "maximum" => 9),
    "32" => array ( "area" => "Jombang", "minimum" => 7, "maximum" => 9),
    "31" => array ( "area" => "Surabaya", "minimum" => 7, "maximum" => 9),
    "29" => array ( "area" => "Demak", "minimum" => 7, "maximum" => 9),
    "28" => array ( "area" => "Purwakarta", "minimum" => 7, "maximum" => 9),
    "27" => array ( "area" => "Solo, Yogyakarta", "minimum" => 7, "maximum" => 9),
    "26" => array ( "area" => "Sumedang", "minimum" => 7, "maximum" => 9),
    "25" => array ( "area" => "Bogor", "minimum" => 7, "maximum" => 9),
    "24" => array ( "area" => "Semarang", "minimum" => 7, "maximum" => 9),
    "23" => array ( "area" => "Cirebon", "minimum" => 7, "maximum" => 9),
    "22" => array ( "area" => "Betung", "minimum" => 7, "maximum" => 9),
    "21" => array ( "area" => "Jakarta", "minimum" => 7, "maximum" => 10),
    "9" => array ( "area" => "Irian et Maluku Islets", "minimum" => 8, "maximum" => 10),
    "6" => array ( "area" => "North Sumatra", "minimum" => 8, "maximum" => 9),
    "5" => array ( "area" => "Kalimantan Islet", "minimum" => 7, "maximum" => 9),
    "4" => array ( "area" => "Sulawesi Islet", "minimum" => 7, "maximum" => 9)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= $data["minimum"] + 3 && strlen ( $parameters["Number"]) <= $data["maximum"] + 3)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "62", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Indonesia", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 2) . ")-" . substr ( $parameters["Number"], 5, 4) . "-" . substr ( $parameters["Number"], 9), "International" => "+62 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for paging network with 2 digits NDC and 3 to 6 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 2) == "13" && strlen ( $parameters["Number"]) >= 8 && strlen ( $parameters["Number"]) <= 11)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "62", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Indonesia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 2) . ")-" . substr ( $parameters["Number"], 5, 4) . "-" . substr ( $parameters["Number"], 9), "International" => "+62 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Indonesian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
