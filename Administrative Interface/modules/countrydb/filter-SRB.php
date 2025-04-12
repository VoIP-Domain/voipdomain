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
 * related to country database of Serbia.
 *
 * Reference: https://www.itu.int/oth/T02020000B9/en (2011-07-04)
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
 * E.164 Serbia country hook
 */
framework_add_filter ( "e164_identify_country_SRB", "e164_identify_country_SRB");

/**
 * E.164 Serbian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "SRB" (code for Serbia). This
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
function e164_identify_country_SRB ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Serbia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+381")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Serbia has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "6"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "381", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Serbia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+381 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "399" => "Peć",
    "398" => "Peć",
    "397" => "Peć",
    "396" => "Peć",
    "395" => "Peć",
    "394" => "Peć",
    "393" => "Peć",
    "392" => "Peć",
    "391" => "Peć",
    "390" => "Dakovica",
    "299" => "Prizren",
    "298" => "Prizren",
    "297" => "Prizren",
    "296" => "Prizren",
    "295" => "Prizren",
    "294" => "Prizren",
    "293" => "Prizren",
    "292" => "Prizren",
    "291" => "Prizren",
    "290" => "Uroševac",
    "289" => "Kosovska Mitrovica",
    "288" => "Kosovska Mitrovica",
    "287" => "Kosovska Mitrovica",
    "286" => "Kosovska Mitrovica",
    "285" => "Kosovska Mitrovica",
    "284" => "Kosovska Mitrovica",
    "283" => "Kosovska Mitrovica",
    "282" => "Kosovska Mitrovica",
    "281" => "Kosovska Mitrovica",
    "280" => "Gnjilane",
    "239" => "Zrenjanin",
    "238" => "Zrenjanin",
    "237" => "Zrenjanin",
    "236" => "Zrenjanin",
    "235" => "Zrenjanin",
    "234" => "Zrenjanin",
    "233" => "Zrenjanin",
    "232" => "Zrenjanin",
    "231" => "Zrenjanin",
    "230" => "Kikinda",
    "38" => "Priština",
    "37" => "Kruševac",
    "36" => "Kraljevo",
    "35" => "Jagodina",
    "34" => "Kragujevac (TC)",
    "33" => "Prijepolje",
    "32" => "Čačak",
    "31" => "Užice",
    "30" => "Bor",
    "27" => "Prokuplje",
    "26" => "Smederevo",
    "25" => "Sombor",
    "24" => "Subotica",
    "22" => "Sremska Mitrovica",
    "21" => "Novi Sad",
    "20" => "Novi Pazar",
    "19" => "Zaječar",
    "18" => "Niš",
    "17" => "Vranje",
    "16" => "Leskovac",
    "15" => "Šabac",
    "14" => "Valjevo",
    "13" => "Pančevo",
    "12" => "Požarevac",
    "11" => "Beograd",
    "10" => "Pirot"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "381", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Serbia", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+381 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 3) == "800")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "381", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Serbia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+381 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
  }

  /**
   * If reached here, number wasn't identified as a valid Serbian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
