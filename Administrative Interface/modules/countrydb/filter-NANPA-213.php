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
 * related to country database of United States of America.
 *
 * Reference: https://www.itu.int/oth/T02020000DE/en (2006-11-22)
 *            https://www.nationalpooling.com/reports/region/AllBlocksAugmentedReport.zip (2023-02-13)
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
 * E.164 United States of America NDC 213 country hook
 */
framework_add_filter ( "e164_identify_NANPA_213", "e164_identify_NANPA_213");

/**
 * E.164 North American area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "USA" (code for
 * United States of America). This hook will verify if phone number is valid,
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
function e164_identify_NANPA_213 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 213 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1213")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "980" => array ( "Area" => "California", "City" => "Los Angeles:da 04", "Operator" => "ONVOY SPECTRUM, LLC"),
    "802" => array ( "Area" => "California", "City" => "Los Angeles:da 01", "Operator" => "ONVOY, LLC - CA"),
    "778" => array ( "Area" => "California", "City" => "Los Angeles:da 01", "Operator" => "ONVOY SPECTRUM, LLC"),
    "775" => array ( "Area" => "California", "City" => "Los Angeles:da 01", "Operator" => "ONVOY SPECTRUM, LLC"),
    "681" => array ( "Area" => "California", "City" => "Los Angeles:da 07", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "676" => array ( "Area" => "California", "City" => "Los Angeles:da 14", "Operator" => "T-MOBILE USA, INC."),
    "651" => array ( "Area" => "California", "City" => "Los Angeles:da 01", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "638" => array ( "Area" => "California", "City" => "Los Angeles:da 10", "Operator" => "METROPCS NETWORKS, LLC"),
    "609" => array ( "Area" => "California", "City" => "Los Angeles:da 09", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "601" => array ( "Area" => "California", "City" => "Los Angeles:da 14", "Operator" => "METROPCS NETWORKS, LLC"),
    "586" => array ( "Area" => "California", "City" => "Los Angeles:da 02", "Operator" => "METROPCS NETWORKS, LLC"),
    "578" => array ( "Area" => "California", "City" => "Montebello", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "565" => array ( "Area" => "California", "City" => "Montebello", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "564" => array ( "Area" => "California", "City" => "Los Angeles:da 09", "Operator" => "T-MOBILE USA, INC."),
    "541" => array ( "Area" => "California", "City" => "Montebello", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "492" => array ( "Area" => "California", "City" => "Los Angeles:da 09", "Operator" => "T-MOBILE USA, INC."),
    "472" => array ( "Area" => "California", "City" => "Montebello", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "428" => array ( "Area" => "California", "City" => "Los Angeles:da 01", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - CA"),
    "424" => array ( "Area" => "California", "City" => "Montebello", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "295" => array ( "Area" => "California", "City" => "Los Angeles:da 07", "Operator" => "ONVOY, LLC - CA"),
    "264" => array ( "Area" => "California", "City" => "Los Angeles:da 01", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "262" => array ( "Area" => "California", "City" => "Los Angeles:da 01", "Operator" => "BANDWIDTH.COM CLEC, LLC - CA"),
    "242" => array ( "Area" => "California", "City" => "Los Angeles:da 01", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "231" => array ( "Area" => "California", "City" => "Los Angeles:da 01", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - CA")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( is_array ( $buffer) ? $buffer : array (), $data);
    }
  }
  return array_merge_recursive ( is_array ( $buffer) ? $buffer : array (), array ( "Area" => $data["Area"]));
}
