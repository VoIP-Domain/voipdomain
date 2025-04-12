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
 * E.164 United States of America NDC 614 country hook
 */
framework_add_filter ( "e164_identify_NANPA_614", "e164_identify_NANPA_614");

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
function e164_identify_NANPA_614 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 614 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1614")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "998" => array ( "Area" => "Ohio", "City" => "Hilliard", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "988" => array ( "Area" => "Ohio", "City" => "Gahanna", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "966" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "952" => array ( "Area" => "Ohio", "City" => "Hilliard", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "949" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "941" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "938" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - OH"),
    "936" => array ( "Area" => "Ohio", "City" => "Hilliard", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "935" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "925" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "919" => array ( "Area" => "Ohio", "City" => "Hilliard", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "913" => array ( "Area" => "Ohio", "City" => "Hilliard", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "912" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "910" => array ( "Area" => "Ohio", "City" => "Gahanna", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "909" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "903" => array ( "Area" => "Ohio", "City" => "Gahanna", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "894" => array ( "Area" => "Ohio", "City" => "Gahanna", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "815" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "814" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - OH"),
    "813" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "800" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "788" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "OHIO BELL TEL CO"),
    "787" => array ( "Area" => "Ohio", "City" => "Dublin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "773" => array ( "Area" => "Ohio", "City" => "Hilliard", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "772" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "765" => array ( "Area" => "Ohio", "City" => "New Albany (Franklin)", "Operator" => "XO OHIO, INC."),
    "712" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "709" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "708" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "703" => array ( "Area" => "Ohio", "City" => "Hilliard", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "685" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "OHIO BELL TEL CO"),
    "680" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "671" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "667" => array ( "Area" => "Ohio", "City" => "Gahanna", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "650" => array ( "Area" => "Ohio", "City" => "Gahanna", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "623" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "615" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "613" => array ( "Area" => "Ohio", "City" => "Dublin", "Operator" => "CHOICE ONE COMMUNICATIONS, INC. - OH"),
    "609" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "576" => array ( "Area" => "Ohio", "City" => "Gahanna", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "550" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "533" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "OHIO BELL TEL CO"),
    "514" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "OHIO BELL TEL CO"),
    "512" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "412" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "ONVOY, LLC - OH"),
    "407" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "PEERLESS NETWORK OF OHIO, LLC - OH"),
    "400" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "393" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "391" => array ( "Area" => "Ohio", "City" => "Groveport", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "386" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "XO OHIO, INC."),
    "381" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "377" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "366" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "OHIO BELL TEL CO"),
    "362" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "BANDWIDTH.COM CLEC, LLC - OH"),
    "359" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "357" => array ( "Area" => "Ohio", "City" => "Columbus", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "200" => array ( "Area" => "Ohio", "City" => "Alton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH")
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
