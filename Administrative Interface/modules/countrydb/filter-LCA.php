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
 * related to country database of Saint Lucia.
 *
 * Reference: https://www.itu.int/oth/T02020000B1/en (2014-04-24)
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
 * E.164 Saint Lucia country hook
 */
framework_add_filter ( "e164_identify_country_LCA", "e164_identify_country_LCA");

/**
 * E.164 Saint Lucia area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "LCA" (code for
 * Saint Lucia). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_LCA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Saint Lucia
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1758")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Saint Lucia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "728" => "Digicel",
    "727" => "Digicel",
    "726" => "Digicel",
    "725" => "Digicel",
    "724" => "Digicel",
    "723" => "Digicel",
    "722" => "Digicel",
    "721" => "Digicel",
    "720" => "Digicel",
    "719" => "Digicel",
    "718" => "Digicel",
    "717" => "Digicel",
    "716" => "Digicel",
    "715" => "Digicel",
    "714" => "Digicel",
    "713" => "Digicel",
    "712" => "Digicel",
    "520" => "AT&T",
    "519" => "AT&T",
    "518" => "AT&T",
    "584" => "Cable & Wireless",
    "489" => "Cable & Wireless",
    "488" => "Cable & Wireless",
    "487" => "Cable & Wireless",
    "486" => "Cable & Wireless",
    "485" => "Cable & Wireless",
    "484" => "Cable & Wireless",
    "461" => "Cable & Wireless",
    "460" => "Cable & Wireless",
    "384" => "Cable & Wireless",
    "287" => "Cable & Wireless",
    "286" => "Cable & Wireless",
    "285" => "Cable & Wireless",
    "284" => "Cable & Wireless"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1758", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Saint Lucia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 758 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "638" => "Cable & Wireless",
    "572" => "Kelcom International Limited (Karib Cable)",
    "571" => "Kelcom International Limited (Karib Cable)",
    "570" => "Kelcom International Limited (Karib Cable)",
    "482" => "Cable & Wireless",
    "481" => "Cable & Wireless",
    "480" => "Cable & Wireless",
    "469" => "Cable & Wireless",
    "468" => "Cable & Wireless",
    "467" => "Cable & Wireless",
    "466" => "Cable & Wireless",
    "465" => "Cable & Wireless",
    "464" => "Cable & Wireless",
    "463" => "Cable & Wireless",
    "462" => "Cable & Wireless",
    "45" => "Cable & Wireless"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1758", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Saint Lucia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 758 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Saint Lucia phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
