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
 * E.164 United States of America NDC 267 country hook
 */
framework_add_filter ( "e164_identify_NANPA_267", "e164_identify_NANPA_267");

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
function e164_identify_NANPA_267 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 267 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1267")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "985" => array ( "Area" => "Pennsylvania", "City" => "Quakertown", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - PA"),
    "964" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "941" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "PAETEC COMMUNICATIONS, INC. - PA"),
    "924" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "922" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "902" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "896" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "889" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "887" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "883" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "862" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - PA"),
    "842" => array ( "Area" => "Pennsylvania", "City" => "Doylestown", "Operator" => "CORE COMMUNICATIONS, INC. - PA"),
    "822" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "821" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "ONVOY, LLC - PA"),
    "816" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "806" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "805" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 3", "Operator" => "ONVOY, LLC - PA"),
    "788" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "770" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 3", "Operator" => "METROPCS, INC."),
    "769" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "752" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "710" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 4", "Operator" => "ONVOY, LLC - PA"),
    "709" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "701" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "698" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "694" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "683" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "674" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "658" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "623" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "618" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "616" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "605" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "600" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "595" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "593" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "588" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "586" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "584" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "582" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "581" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "561" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "BROADVIEW NETWORKS, INC. - PA"),
    "542" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "539" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "533" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - PA"),
    "524" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "506" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "501" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "ONVOY, LLC - PA"),
    "473" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "453" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "444" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 4", "Operator" => "METROPCS, INC."),
    "441" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "438" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "431" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "425" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "VERIZON PENNSYLVANIA, INC."),
    "418" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "400" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "398" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "359" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - PA"),
    "355" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "349" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "340" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "333" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 3", "Operator" => "METROPCS, INC."),
    "325" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "320" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "294" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "271" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "268" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "264" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "260" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "BROADVIEW NETWORKS, INC. - PA"),
    "225" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "PEERLESS NETWORK OF PENNSYLVANIA, LLC - PA"),
    "214" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "ONVOY, LLC - PA"),
    "208" => array ( "Area" => "Pennsylvania", "City" => "Bedminster", "Operator" => "YMAX COMMUNICATIONS CORP. - PA"),
    "206" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP")
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
