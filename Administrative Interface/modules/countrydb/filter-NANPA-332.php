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
 * E.164 United States of America NDC 332 country hook
 */
framework_add_filter ( "e164_identify_NANPA_332", "e164_identify_NANPA_332");

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
function e164_identify_NANPA_332 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 332 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1332")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "963" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "888" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "800" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "777" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "666" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "500" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "456" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "444" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "388" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "334" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "332" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "REGIONAL TELEPHONE CORPORATION - NY"),
    "322" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "300" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "271" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "268" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "264" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "260" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "259" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "257" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "250" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "247" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "244" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "243" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "TC SYSTEMS, INC. - NY"),
    "235" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "232" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "229" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "RCN TELECOM SERVICES, INC. - NY"),
    "227" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "226" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "224" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "221" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "INTRADO COMMUNICATIONS, LLC"),
    "219" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "218" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "215" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "204" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "202" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "200" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC.")
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
