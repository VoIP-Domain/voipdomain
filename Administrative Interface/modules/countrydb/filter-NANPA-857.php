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
 * E.164 United States of America NDC 857 country hook
 */
framework_add_filter ( "e164_identify_NANPA_857", "e164_identify_NANPA_857");

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
function e164_identify_NANPA_857 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 857 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1857")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "996" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC  - MA"),
    "869" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "855" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "ONVOY, LLC - MA"),
    "666" => array ( "Area" => "Massachusetts", "City" => "Brookline", "Operator" => "ONVOY, LLC - MA"),
    "615" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "507" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "444" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "PEERLESS NETWORK OF MASSACHUSETTS, LLC - MA"),
    "417" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "408" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "395" => array ( "Area" => "Massachusetts", "City" => "Roxbury", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "393" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "390" => array ( "Area" => "Massachusetts", "City" => "Brookline", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "377" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "PAETEC COMMUNICATIONS, INC. - MA"),
    "372" => array ( "Area" => "Massachusetts", "City" => "Quincy", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "369" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - MA"),
    "368" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "PAETEC COMMUNICATIONS, INC. - MA"),
    "360" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "359" => array ( "Area" => "Massachusetts", "City" => "Quincy", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "329" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "324" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "319" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "312" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "307" => array ( "Area" => "Massachusetts", "City" => "Brookline", "Operator" => "VERIZON NEW ENGLAND INC."),
    "303" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "295" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "292" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "291" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "286" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "283" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "282" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "VERIZON NEW ENGLAND INC."),
    "281" => array ( "Area" => "Massachusetts", "City" => "Quincy", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "278" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "276" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "275" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "274" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MA"),
    "272" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "266" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "261" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "258" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "251" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "249" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "247" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "238" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "VERIZON NEW ENGLAND INC."),
    "237" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "236" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "METROPCS, INC."),
    "235" => array ( "Area" => "Massachusetts", "City" => "Cambridge", "Operator" => "VERIZON NEW ENGLAND INC."),
    "232" => array ( "Area" => "Massachusetts", "City" => "Newton", "Operator" => "ONVOY, LLC - MA"),
    "224" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "VERIZON NEW ENGLAND INC."),
    "219" => array ( "Area" => "Massachusetts", "City" => "Newton", "Operator" => "ONVOY, LLC - MA"),
    "215" => array ( "Area" => "Massachusetts", "City" => "Brookline", "Operator" => "VERIZON NEW ENGLAND INC."),
    "213" => array ( "Area" => "Massachusetts", "City" => "Boston", "Operator" => "PAETEC COMMUNICATIONS, INC. - MA")
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
