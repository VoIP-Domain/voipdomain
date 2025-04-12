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
 * related to country database of Dominica.
 *
 * Reference: https://www.itu.int/oth/T020200003B/en (2014-04-24)
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
 * E.164 Dominica country hook
 */
framework_add_filter ( "e164_identify_country_DMA", "e164_identify_country_DMA");

/**
 * E.164 Dominican area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "DMA" (code for Dominica). This
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
function e164_identify_country_DMA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Dominica
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1767")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Dominica has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "617" => "Digicel Dominica",
    "616" => "Digicel Dominica",
    "615" => "Digicel Dominica",
    "614" => "Digicel Dominica",
    "613" => "Digicel Dominica",
    "612" => "Digicel Dominica",
    "317" => "Digicel Dominica",
    "316" => "Digicel Dominica",
    "315" => "Digicel Dominica",
    "295" => "Cable and Wireless Dominica",
    "285" => "Cable and Wireless Dominica",
    "277" => "Cable and Wireless Dominica",
    "276" => "Cable and Wireless Dominica",
    "275" => "Cable and Wireless Dominica",
    "265" => "Cable and Wireless Dominica",
    "245" => "Cable and Wireless Dominica",
    "235" => "Cable and Wireless Dominica",
    "225" => "Cable and Wireless Dominica"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1767", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Dominica", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 767 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "703" => "SAT Telecoms Ltd.",
    "702" => "SAT Telecoms Ltd.",
    "701" => "SAT Telecoms Ltd.",
    "504" => "Marpin Telecom Dominica",
    "503" => "Marpin Telecom Dominica",
    "502" => "Marpin Telecom Dominica",
    "501" => "Marpin Telecom Dominica",
    "500" => "Marpin Telecom Dominica",
    "449" => "Cable and Wireless Dominica",
    "448" => "Cable and Wireless Dominica",
    "447" => "Cable and Wireless Dominica",
    "446" => "Cable and Wireless Dominica",
    "445" => "Cable and Wireless Dominica",
    "442" => "Cable and Wireless Dominica",
    "441" => "Cable and Wireless Dominica",
    "440" => "Cable and Wireless Dominica",
    "421" => "Digicel Dominica",
    "420" => "Digicel Dominica",
    "266" => "Cable and Wireless Dominica",
    "255" => "Cable and Wireless Dominica"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1767", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Dominica", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 767 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Dominican phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
