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
 * E.164 United States of America NDC 904 country hook
 */
framework_add_filter ( "e164_identify_NANPA_904", "e164_identify_NANPA_904");

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
function e164_identify_NANPA_904 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 904 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1904")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "956" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "947" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "883" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "882" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "869" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "846" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "843" => array ( "Area" => "Florida", "City" => "Middleburg", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "841" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - FL"),
    "840" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "832" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "804" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "776" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "761" => array ( "Area" => "Florida", "City" => "Fernandina Beach", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "749" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "718" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "702" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "691" => array ( "Area" => "Florida", "City" => "Saint Johns", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - FL"),
    "684" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "681" => array ( "Area" => "Florida", "City" => "Jacksonville Beach", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "676" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "664" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "ONVOY, LLC - FL"),
    "659" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "ONVOY, LLC - FL"),
    "649" => array ( "Area" => "Florida", "City" => "Fernandina Beach", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "623" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "609" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "603" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "569" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "ONVOY, LLC - FL"),
    "554" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "METROPCS, INC."),
    "546" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "532" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "512" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL"),
    "500" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "US LEC OF FLORIDA, INC."),
    "492" => array ( "Area" => "Florida", "City" => "Ponte Vedra Beach", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "488" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - FL"),
    "480" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "POWERTEL JACKSONVILLE LICENSES, INC."),
    "462" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - FL"),
    "455" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - FL"),
    "451" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "444" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "METROPCS, INC."),
    "427" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "410" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "YMAX COMMUNICATIONS CORP. - FL"),
    "369" => array ( "Area" => "Florida", "City" => "Orange Park", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "336" => array ( "Area" => "Florida", "City" => "Orange Park", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "316" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "314" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "305" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "304" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "303" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "255" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "238" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "216" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "XO FLORIDA, INC."),
    "204" => array ( "Area" => "Florida", "City" => "Jacksonville", "Operator" => "ONVOY, LLC - FL")
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
