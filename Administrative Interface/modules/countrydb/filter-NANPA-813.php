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
 * E.164 United States of America NDC 813 country hook
 */
framework_add_filter ( "e164_identify_NANPA_813", "e164_identify_NANPA_813");

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
function e164_identify_NANPA_813 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 813 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1813")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "943" => array ( "Area" => "Florida", "City" => "Tampa North", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "942" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "ONVOY SPECTRUM, LLC"),
    "921" => array ( "Area" => "Florida", "City" => "Tampa North", "Operator" => "ONVOY, LLC - FL"),
    "916" => array ( "Area" => "Florida", "City" => "Tampa East", "Operator" => "FRONTIER FLORIDA LLC DBA FRONTIER COMM OF FLORIDA"),
    "912" => array ( "Area" => "Florida", "City" => "Tampa West", "Operator" => "HD CARRIER LLC"),
    "900" => array ( "Area" => "Florida", "City" => "Tampa", "Operator" => "METROPCS, INC."),
    "826" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "823" => array ( "Area" => "Florida", "City" => "Tampa East", "Operator" => "HD CARRIER LLC"),
    "821" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "MCI WORLDCOM COMMUNICATIONS, INC. - FL"),
    "800" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "PAETEC COMMUNICATIONS, INC. - FL"),
    "799" => array ( "Area" => "Florida", "City" => "Tampa South", "Operator" => "HD CARRIER LLC"),
    "777" => array ( "Area" => "Florida", "City" => "Tampa", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "761" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "ONVOY, LLC - FL"),
    "725" => array ( "Area" => "Florida", "City" => "Tampa East", "Operator" => "ONVOY, LLC - FL"),
    "721" => array ( "Area" => "Florida", "City" => "Plant City", "Operator" => "HD CARRIER LLC"),
    "667" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "YMAX COMMUNICATIONS CORP. - FL"),
    "660" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "FRONTIER FLORIDA LLC DBA FRONTIER COMM OF FLORIDA"),
    "648" => array ( "Area" => "Florida", "City" => "Tampa East", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "647" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "646" => array ( "Area" => "Florida", "City" => "Zephyrhills", "Operator" => "HD CARRIER LLC"),
    "617" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "FRONTIER FLORIDA LLC DBA FRONTIER COMM OF FLORIDA"),
    "585" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "581" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - FL"),
    "573" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "557" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "529" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "516" => array ( "Area" => "Florida", "City" => "Tampa", "Operator" => "METROPCS, INC."),
    "480" => array ( "Area" => "Florida", "City" => "Tampa", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "460" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "459" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "456" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "451" => array ( "Area" => "Florida", "City" => "Tampa", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "444" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL"),
    "443" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "BRIGHT HOUSE NTWS INFORMATION SVCS (FLORIDA) - FL"),
    "430" => array ( "Area" => "Florida", "City" => "Tampa West", "Operator" => "ONVOY, LLC - FL"),
    "420" => array ( "Area" => "Florida", "City" => "Tampa", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "398" => array ( "Area" => "Florida", "City" => "Plant City", "Operator" => "YMAX COMMUNICATIONS CORP. - FL"),
    "374" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "BRIGHT HOUSE NTWS INFORMATION SVCS (FLORIDA) - FL"),
    "372" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "FRONTIER FLORIDA LLC DBA FRONTIER COMM OF FLORIDA"),
    "366" => array ( "Area" => "Florida", "City" => "Tampa West", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - FL"),
    "297" => array ( "Area" => "Florida", "City" => "Tampa Central", "Operator" => "SPRINT SPECTRUM, L.P."),
    "206" => array ( "Area" => "Florida", "City" => "Tampa", "Operator" => "WINDSTREAM NUVOX, INC."),
    "203" => array ( "Area" => "Florida", "City" => "Tampa East", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "200" => array ( "Area" => "Florida", "City" => "Tampa", "Operator" => "XO FLORIDA, INC.")
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
