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
 * E.164 United States of America NDC 716 country hook
 */
framework_add_filter ( "e164_identify_NANPA_716", "e164_identify_NANPA_716");

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
function e164_identify_NANPA_716 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 716 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1716")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "900" => array ( "Area" => "New York", "City" => "Youngstown", "Operator" => "REGIONAL TELEPHONE CORPORATION - NY"),
    "716" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "REGIONAL TELEPHONE CORPORATION - NY"),
    "715" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "657" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "ONVOY, LLC- NY"),
    "613" => array ( "Area" => "New York", "City" => "Jamestown", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "600" => array ( "Area" => "New York", "City" => "Niagara Falls", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "521" => array ( "Area" => "New York", "City" => "Akron", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "500" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "476" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "468" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "467" => array ( "Area" => "New York", "City" => "Fredonia", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "421" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "399" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "387" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "383" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "368" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "359" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "345" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "344" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "339" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "336" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "334" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "329" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "327" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "323" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "291" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "275" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "274" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "MOSAIC NETWORX LLC - NY"),
    "271" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "ONVOY, LLC- NY"),
    "255" => array ( "Area" => "New York", "City" => "Tonawanda", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "225" => array ( "Area" => "New York", "City" => "Buffalo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY")
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
