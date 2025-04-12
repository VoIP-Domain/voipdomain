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
 * related to country database of Kosovo.
 *
 * Reference: https://www.itu.int/oth/T02020000FD/en (2017-05-31)
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
 * E.164 Kosovo country hook
 */
framework_add_filter ( "e164_identify_country_UNK", "e164_identify_country_UNK");

/**
 * E.164 Kosovo area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "UNK" (code for Kosovo). This
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
function e164_identify_country_UNK ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Kosovo
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+383")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "4715" => "MTS D.O.O.",
    "4714" => "MTS D.O.O.",
    "4713" => "MTS D.O.O.",
    "4712" => "MTS D.O.O.",
    "4711" => "MTS D.O.O.",
    "4710" => "MTS D.O.O.",
    "459" => "Telecom of Kosovo J.S.C.",
    "458" => "Telecom of Kosovo J.S.C.",
    "457" => "Telecom of Kosovo J.S.C.",
    "456" => "MVNO Dardafon.Net LLC",
    "455" => "MVNO Dardafon.Net LLC",
    "454" => "Telecom of Kosovo J.S.C.",
    "453" => "Telecom of Kosovo J.S.C.",
    "452" => "Telecom of Kosovo J.S.C.",
    "451" => "Telecom of Kosovo J.S.C.",
    "450" => "Telecom of Kosovo J.S.C.",
    "439" => "IPKO Telecommunications LLC",
    "438" => "IPKO Telecommunications LLC",
    "437" => "IPKO Telecommunications LLC",
    "436" => "IPKO Telecommunications LLC",
    "435" => "IPKO Telecommunications LLC",
    "434" => "MVNO Dukagjini Telecommunications LLC",
    "433" => "MVNO Dukagjini Telecommunications LLC",
    "432" => "MVNO Dukagjini Telecommunications LLC",
    "431" => "IPKO Telecommunications LLC",
    "430" => "IPKO Telecommunications LLC",
    "49" => "IPKO Telecommunications LLC",
    "48" => "",
    "47" => "",
    "46" => "",
    "44" => "Telecom of Kosovo J.S.C.",
    "42" => "",
    "41" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "383", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Kosovo", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+383 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "390" => array ( "area" => "Gjakova", "minimum" => 9, "maximum" => 8),
    "290" => array ( "area" => "Ferizaj", "minimum" => 9, "maximum" => 8),
    "280" => array ( "area" => "Gjilani", "minimum" => 9, "maximum" => 8),
    "39" => array ( "area" => "Peja", "minimum" => 8, "maximum" => 8),
    "38" => array ( "area" => "Prishtina", "minimum" => 8, "maximum" => 8),
    "29" => array ( "area" => "Prizreni", "minimum" => 8, "maximum" => 8),
    "28" => array ( "area" => "Mitrovica", "minimum" => 8, "maximum" => 8)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 4 + $data["minimum"] && strlen ( $parameters["Number"]) <= 4 + $data["maximum"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "383", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Kosovo", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+383 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Kosovo phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
