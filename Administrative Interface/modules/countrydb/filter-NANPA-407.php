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
 * E.164 United States of America NDC 407 country hook
 */
framework_add_filter ( "e164_identify_NANPA_407", "e164_identify_NANPA_407");

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
function e164_identify_NANPA_407 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 407 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1407")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "986" => array ( "Area" => "Florida", "City" => "Oviedo", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "968" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "917" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "906" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "887" => array ( "Area" => "Florida", "City" => "Kissimmee", "Operator" => "ONVOY, LLC - FL"),
    "879" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "METROPCS NETWORKS, LLC"),
    "860" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "METROPCS NETWORKS, LLC"),
    "819" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "784" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "780" => array ( "Area" => "Florida", "City" => "Kissimmee", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "746" => array ( "Area" => "Florida", "City" => "Winter Park", "Operator" => "EMBARQ FLORIDA, INC. DBA CENTURYLINK"),
    "742" => array ( "Area" => "Florida", "City" => "Kissimmee", "Operator" => "EMBARQ FLORIDA, INC. DBA CENTURYLINK"),
    "728" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "727" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "BRIGHT HOUSE NTWS INFORMATION SVCS (FLORIDA) - FL"),
    "726" => array ( "Area" => "Florida", "City" => "Kissimmee", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "724" => array ( "Area" => "Florida", "City" => "Kissimmee", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "717" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "713" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "683" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "631" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "CENTURYLINK COMMUNICATIONS LLC - FL"),
    "626" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "610" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "YMAX COMMUNICATIONS CORP. - FL"),
    "609" => array ( "Area" => "Florida", "City" => "Winter Park", "Operator" => "CENTURYLINK COMMUNICATIONS LLC - FL"),
    "561" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "500" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "PAETEC COMMUNICATIONS, INC. - FL"),
    "486" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "METROPCS NETWORKS, LLC"),
    "485" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "METROPCS NETWORKS, LLC"),
    "476" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL"),
    "473" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "437" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "407" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - FL"),
    "337" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "ONVOY, LLC - FL"),
    "334" => array ( "Area" => "Florida", "City" => "Kissimmee", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "300" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "METROPCS NETWORKS, LLC"),
    "272" => array ( "Area" => "Florida", "City" => "Sanford", "Operator" => "METROPCS NETWORKS, LLC"),
    "266" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "PAETEC COMMUNICATIONS, INC. - FL"),
    "214" => array ( "Area" => "Florida", "City" => "Orlando", "Operator" => "ONVOY, LLC - FL"),
    "200" => array ( "Area" => "Florida", "City" => "Winter Park", "Operator" => "CENTURYLINK COMMUNICATIONS LLC - FL")
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
