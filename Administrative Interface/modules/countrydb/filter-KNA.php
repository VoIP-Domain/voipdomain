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
 * related to country database of Saint Kitts and Nevis.
 *
 * Reference: https://www.itu.int/oth/T02020000B0/en (2014-04-24)
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
 * E.164 Saint Kitts and Nevis country hook
 */
framework_add_filter ( "e164_identify_country_KNA", "e164_identify_country_KNA");

/**
 * E.164 Saint Kitts and Nevis area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "KNA" (code for
 * Saint Kitts and Nevis). This hook will verify if phone number is valid,
 * returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_KNA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Saint Kitts and Nevis
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1869")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Saint Kitts and Nevis has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "766" => "Wireless Ventures Limited (Digicel)",
    "765" => "Wireless Ventures Limited (Digicel)",
    "764" => "Wireless Ventures Limited (Digicel)",
    "763" => "Wireless Ventures Limited (Digicel)",
    "762" => "Wireless Ventures Limited (Digicel)",
    "760" => "Wireless Ventures Limited (Digicel)",
    "669" => "Cable & Wireless",
    "668" => "Cable & Wireless",
    "667" => "Cable & Wireless",
    "665" => "Cable & Wireless",
    "664" => "Cable & Wireless",
    "663" => "Cable & Wireless",
    "662" => "Cable & Wireless",
    "661" => "Cable & Wireless",
    "660" => "Cable & Wireless",
    "567" => "The Cable",
    "566" => "The Cable",
    "565" => "The Cable",
    "558" => "CariGlobe",
    "557" => "CariGlobe",
    "556" => "CariGlobe"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1869", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Saint Kitts and Nevis", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1869 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "470" => "Cable & Wireless",
    "469" => "Cable & Wireless",
    "468" => "Cable & Wireless",
    "467" => "Cable & Wireless",
    "466" => "Cable & Wireless",
    "465" => "Cable & Wireless",
    "461" => "Cable & Wireless",
    "460" => "Cable & Wireless",
    "236" => "Cable & Wireless",
    "229" => "Cable & Wireless"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1869", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Saint Kitts and Nevis", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1869 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Saint Kitts and Nevis phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
