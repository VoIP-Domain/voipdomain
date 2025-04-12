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
 * related to country database of Cambodia.
 *
 * Reference: https://www.itu.int/oth/T0202000023/en (2009-05-08)
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
 * E.164 Cambodia country hook
 */
framework_add_filter ( "e164_identify_country_KHM", "e164_identify_country_KHM");

/**
 * E.164 Cambodian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "KHM" (code for Cambodia). This
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
function e164_identify_country_KHM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Cambodia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+855")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Cambodia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "99" => "Nationwide Limited TLMRS",
    "93" => "Smart Mobile",
    "92" => "Mobitel Tango Service",
    "91" => "MPTC TLMRS (Trunked land mobile Radio)",
    "19" => "S Telecom (South Korea)",
    "18" => "Camtel AMPS",
    "16" => "Casacom (Samart) GSM 900",
    "15" => "Casacom (Samart) NMT 900",
    "12" => "CamGSM",
    "11" => "Camshin (Shinawatra) DCS 1800",
    "10" => "Smart Mobile"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && (int) substr ( $parameters["Number"], 6, 1) >= 2)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "855", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Cambodia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+855 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "75" => "Ratanakiri",
    "74" => "Stung Treng",
    "73" => "Mondul Kiri",
    "72" => "Kratie",
    "65" => "Udar Meanchey",
    "64" => "Preah Vihear",
    "63" => "Siem Reap",
    "62" => "Kampong Thom",
    "55" => "Pailin",
    "54" => "Banteay Meanchey",
    "53" => "Battambang",
    "52" => "Pursat",
    "44" => "Scvay Rieng",
    "43" => "Prey Veng",
    "42" => "Kampong Cham",
    "36" => "Kep",
    "35" => "Koh Kong",
    "34" => "Sihanoukville",
    "33" => "Kampot",
    "32" => "Takeo",
    "26" => "Kampong Chhang",
    "25" => "Kampong Speu",
    "24" => "Kandal",
    "23" => "Phnom-Penh"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      switch ( substr ( $parameters["Number"], 6, 1))
      {
        case "2":
        case "4":
        case "7":
        case "8":
          $operator = "MPTC";
          break;
        case "3":
          $operator = "Camshin";
          break;
        case "9":
          $operator = "Camintel";
          break;
        default:
          $operator = "";
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "855", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Cambodia", "Area" => $area, "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+855 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for Paging network with 2 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 2) == "95")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "855", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Cambodia", "Area" => "National Paging Service", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+855 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for Maritime Radio network with 2 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 2) == "94")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "855", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Cambodia", "Area" => "Maritime Radio", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MARINERADIO, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+855 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Cambodian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
