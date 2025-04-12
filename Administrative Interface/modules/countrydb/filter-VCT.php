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
 * related to country database of Saint Vincent and the Grenadines.
 *
 * Reference: https://www.itu.int/oth/T02020000B3/en (2022-08-12)
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
 * E.164 Saint Vincent and the Grenadines country hook
 */
framework_add_filter ( "e164_identify_country_VCT", "e164_identify_country_VCT");

/**
 * E.164 Saint Vincent and the Grenadines area number identification hook. This
 * hook is an e164_identify sub hook, called when the ISO3166 Alpha3 are "VCT"
 * (code for Saint Vincent and the Grenadines). This hook will verify if phone
 * number is valid, returning the area code, area name, phone number, others
 * number related information and if possible, the number type (mobile,
 * landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_VCT ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Saint Vincent and the Grenadines
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1784")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Saint Vincent and the Grenadines has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "593" => "Cable & Wireless Limited",
    "534" => "Digicel (SVG) Limited",
    "533" => "Digicel (SVG) Limited",
    "532" => "Digicel (SVG) Limited",
    "531" => "Digicel (SVG) Limited",
    "530" => "Digicel (SVG) Limited",
    "529" => "Digicel (SVG) Limited",
    "528" => "Digicel (SVG) Limited",
    "527" => "Digicel (SVG) Limited",
    "526" => "Digicel (SVG) Limited",
    "498" => "Cable & Wireless Limited",
    "497" => "Cable & Wireless Limited",
    "496" => "Cable & Wireless Limited",
    "495" => "Cable & Wireless Limited",
    "494" => "Cable & Wireless Limited",
    "493" => "Cable & Wireless Limited",
    "492" => "Cable & Wireless Limited",
    "491" => "Cable & Wireless Limited",
    "455" => "Cable & Wireless Limited",
    "454" => "Cable & Wireless Limited",
    "434" => "Digicel (SVG) Limited",
    "433" => "Digicel (SVG) Limited",
    "432" => "Digicel (SVG) Limited",
    "431" => "Digicel (SVG) Limited",
    "430" => "Digicel (SVG) Limited"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1784", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Saint Vincent and the Grenadines", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 784 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "784" => "Cable & Wireless Limited",
    "720" => "Digicel (SVG) Limited",
    "638" => "Cable & Wireless Limited",
    "571" => "Columbus Communications Inc. (Kelcom International Limited)",
    "570" => "Columbus Communications Inc. (Kelcom International Limited)",
    "555" => "Cable & Wireless Limited",
    "490" => "Cable & Wireless Limited",
    "458" => "Cable & Wireless Limited",
    "457" => "Cable & Wireless Limited",
    "456" => "Cable & Wireless Limited",
    "453" => "Cable & Wireless Limited",
    "452" => "Cable & Wireless Limited",
    "451" => "Cable & Wireless Limited",
    "450" => "Cable & Wireless Limited",
    "438" => "Cable & Wireless Limited",
    "386" => "Cable & Wireless Limited",
    "385" => "Cable & Wireless Limited",
    "384" => "Cable & Wireless Limited",
    "383" => "Cable & Wireless Limited",
    "382" => "Cable & Wireless Limited",
    "381" => "Cable & Wireless Limited",
    "380" => "Cable & Wireless Limited",
    "369" => "Cable & Wireless Limited",
    "368" => "Cable & Wireless Limited",
    "367" => "Cable & Wireless Limited",
    "366" => "Cable & Wireless Limited",
    "266" => "Cable & Wireless Limited",
    "48" => "Cable & Wireless Limited",
    "37" => "Cable & Wireless Limited"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1784", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Saint Vincent and the Grenadines", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 784 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "512" => "Friends Telecom Ltd. (Friends Associates Ltd.)",
    "510" => "Friends Telecom Ltd. (Friends Associates Ltd.)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1784", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Saint Vincent and the Grenadines", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 784 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Saint Vincent and the Grenadines phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
