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
 * E.164 United States of America NDC 826 country hook
 */
framework_add_filter ( "e164_identify_NANPA_826", "e164_identify_NANPA_826");

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
function e164_identify_NANPA_826 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 826 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1826")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "9999" => array ( "Area" => "Virginia", "City" => "Boones Mill", "Operator" => "FRACTEL, LLC"),
    "9998" => array ( "Area" => "Virginia", "City" => "Boones Mill", "Operator" => "FRACTEL, LLC"),
    "9269" => array ( "Area" => "Virginia", "City" => "Oriskany", "Operator" => "ONVOY, LLC - VA"),
    "9267" => array ( "Area" => "Virginia", "City" => "Oriskany", "Operator" => "ONVOY, LLC - VA"),
    "9266" => array ( "Area" => "Virginia", "City" => "Oriskany", "Operator" => "ONVOY, LLC - VA"),
    "9009" => array ( "Area" => "Virginia", "City" => "Gore", "Operator" => "FRACTEL, LLC"),
    "9008" => array ( "Area" => "Virginia", "City" => "Gore", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "9000" => array ( "Area" => "Virginia", "City" => "Gore", "Operator" => "FRACTEL, LLC"),
    "8888" => array ( "Area" => "Virginia", "City" => "Boyce", "Operator" => "FRACTEL, LLC"),
    "8887" => array ( "Area" => "Virginia", "City" => "Boyce", "Operator" => "ONVOY, LLC - VA"),
    "8886" => array ( "Area" => "Virginia", "City" => "Boyce", "Operator" => "ONVOY, LLC - VA"),
    "8880" => array ( "Area" => "Virginia", "City" => "Boyce", "Operator" => "TERRA NOVA TELECOM INC."),
    "8678" => array ( "Area" => "Virginia", "City" => "Union Hall", "Operator" => "ONVOY, LLC - VA"),
    "8677" => array ( "Area" => "Virginia", "City" => "Union Hall", "Operator" => "ONVOY, LLC - VA"),
    "8289" => array ( "Area" => "Virginia", "City" => "Middleburg", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "8288" => array ( "Area" => "Virginia", "City" => "Middleburg", "Operator" => "TERRA NOVA TELECOM INC."),
    "8286" => array ( "Area" => "Virginia", "City" => "Middleburg", "Operator" => "ONVOY, LLC - VA"),
    "8278" => array ( "Area" => "Virginia", "City" => "King George", "Operator" => "ONVOY, LLC - VA"),
    "8277" => array ( "Area" => "Virginia", "City" => "King George", "Operator" => "ONVOY, LLC - VA"),
    "8276" => array ( "Area" => "Virginia", "City" => "King George", "Operator" => "ONVOY, LLC - VA"),
    "8257" => array ( "Area" => "Virginia", "City" => "Calverton", "Operator" => "ONVOY, LLC - VA"),
    "8256" => array ( "Area" => "Virginia", "City" => "Calverton", "Operator" => "ONVOY, LLC - VA"),
    "8008" => array ( "Area" => "Virginia", "City" => "Greenwood", "Operator" => "FRACTEL, LLC"),
    "8000" => array ( "Area" => "Virginia", "City" => "Greenwood", "Operator" => "FRACTEL, LLC"),
    "7777" => array ( "Area" => "Virginia", "City" => "Floyd", "Operator" => "FRACTEL, LLC"),
    "7426" => array ( "Area" => "Virginia", "City" => "Narrows", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "7420" => array ( "Area" => "Virginia", "City" => "Narrows", "Operator" => "COMMIO, LLC"),
    "7267" => array ( "Area" => "Virginia", "City" => "The Plains", "Operator" => "ONVOY, LLC - VA"),
    "7266" => array ( "Area" => "Virginia", "City" => "The Plains", "Operator" => "ONVOY, LLC - VA"),
    "6999" => array ( "Area" => "Virginia", "City" => "Harrisonburg", "Operator" => "TERRA NOVA TELECOM INC."),
    "6666" => array ( "Area" => "Virginia", "City" => "Catoctin", "Operator" => "FRACTEL, LLC"),
    "6665" => array ( "Area" => "Virginia", "City" => "Catoctin", "Operator" => "ONVOY, LLC - VA"),
    "6006" => array ( "Area" => "Virginia", "City" => "Mount Gilead", "Operator" => "FRACTEL, LLC"),
    "6000" => array ( "Area" => "Virginia", "City" => "Mount Gilead", "Operator" => "FRACTEL, LLC"),
    "5299" => array ( "Area" => "Virginia", "City" => "Gordonsville", "Operator" => "FRACTEL, LLC"),
    "5298" => array ( "Area" => "Virginia", "City" => "Gordonsville", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "5293" => array ( "Area" => "Virginia", "City" => "Gordonsville", "Operator" => "FRACTEL, LLC"),
    "5292" => array ( "Area" => "Virginia", "City" => "Gordonsville", "Operator" => "ONVOY, LLC - VA"),
    "5290" => array ( "Area" => "Virginia", "City" => "Gordonsville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "5005" => array ( "Area" => "Virginia", "City" => "Shenandoah", "Operator" => "FRACTEL, LLC"),
    "5000" => array ( "Area" => "Virginia", "City" => "Shenandoah", "Operator" => "FRACTEL, LLC"),
    "4552" => array ( "Area" => "Virginia", "City" => "Paint Bank", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "4447" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "ONVOY, LLC - VA"),
    "4446" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "ONVOY, LLC - VA"),
    "4445" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "FRACTEL, LLC"),
    "4444" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "FRACTEL, LLC"),
    "4443" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "COMMIO, LLC"),
    "4442" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "4441" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "4440" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "TERRA NOVA TELECOM INC."),
    "4004" => array ( "Area" => "Virginia", "City" => "Unionville", "Operator" => "FRACTEL, LLC"),
    "4000" => array ( "Area" => "Virginia", "City" => "Unionville", "Operator" => "FRACTEL, LLC"),
    "3569" => array ( "Area" => "Virginia", "City" => "Craigsville", "Operator" => "FRACTEL, LLC"),
    "3567" => array ( "Area" => "Virginia", "City" => "Craigsville", "Operator" => "FRACTEL, LLC"),
    "3372" => array ( "Area" => "Virginia", "City" => "Montvale", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "3371" => array ( "Area" => "Virginia", "City" => "Montvale", "Operator" => "SHENTEL COMMUNICATIONS, LLC. - VA"),
    "3342" => array ( "Area" => "Virginia", "City" => "Salem", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "3334" => array ( "Area" => "Virginia", "City" => "Eagle Rock", "Operator" => "ONVOY, LLC - VA"),
    "3333" => array ( "Area" => "Virginia", "City" => "Eagle Rock", "Operator" => "FRACTEL, LLC"),
    "3008" => array ( "Area" => "Virginia", "City" => "Washington (Rappahannock)", "Operator" => "ONVOY, LLC - VA"),
    "3005" => array ( "Area" => "Virginia", "City" => "Washington (Rappahannock)", "Operator" => "ONVOY, LLC - VA"),
    "3004" => array ( "Area" => "Virginia", "City" => "Washington (Rappahannock)", "Operator" => "ONVOY, LLC - VA"),
    "3003" => array ( "Area" => "Virginia", "City" => "Washington (Rappahannock)", "Operator" => "FRACTEL, LLC"),
    "3000" => array ( "Area" => "Virginia", "City" => "Washington (Rappahannock)", "Operator" => "FRACTEL, LLC"),
    "2227" => array ( "Area" => "Virginia", "City" => "Brokenburg", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - VA"),
    "2225" => array ( "Area" => "Virginia", "City" => "Brokenburg", "Operator" => "COMCAST IP PHONE, LLC"),
    "2223" => array ( "Area" => "Virginia", "City" => "Brokenburg", "Operator" => "FRACTEL, LLC"),
    "2222" => array ( "Area" => "Virginia", "City" => "Brokenburg", "Operator" => "FRACTEL, LLC"),
    "2220" => array ( "Area" => "Virginia", "City" => "Brokenburg", "Operator" => "TERRA NOVA TELECOM INC."),
    "2202" => array ( "Area" => "Virginia", "City" => "Pembroke", "Operator" => "CEBRIDGE TELECOM VA, LLC DBA SUDDENLINK COMM"),
    "2137" => array ( "Area" => "Virginia", "City" => "Spotsylvania", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - VA"),
    "2122" => array ( "Area" => "Virginia", "City" => "Warrenton", "Operator" => "TERRA NOVA TELECOM INC."),
    "2120" => array ( "Area" => "Virginia", "City" => "Warrenton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2101" => array ( "Area" => "Virginia", "City" => "Newport", "Operator" => "CEBRIDGE TELECOM VA, LLC DBA SUDDENLINK COMM"),
    "2099" => array ( "Area" => "Virginia", "City" => "Newport", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2089" => array ( "Area" => "Virginia", "City" => "Winchester", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2081" => array ( "Area" => "Virginia", "City" => "Winchester", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2080" => array ( "Area" => "Virginia", "City" => "Winchester", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2070" => array ( "Area" => "Virginia", "City" => "Upperville", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "2068" => array ( "Area" => "Virginia", "City" => "Warrenton", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2066" => array ( "Area" => "Virginia", "City" => "Warrenton", "Operator" => "DISH WIRELESS, LLC"),
    "2065" => array ( "Area" => "Virginia", "City" => "Warrenton", "Operator" => "DISH WIRELESS, LLC"),
    "2061" => array ( "Area" => "Virginia", "City" => "Warrenton", "Operator" => "TELNYX LLC"),
    "2060" => array ( "Area" => "Virginia", "City" => "Warrenton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2051" => array ( "Area" => "Virginia", "City" => "Roanoke", "Operator" => "NUSO, LLC"),
    "2049" => array ( "Area" => "Virginia", "City" => "Fredericksburg", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2048" => array ( "Area" => "Virginia", "City" => "Fredericksburg", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2046" => array ( "Area" => "Virginia", "City" => "Fredericksburg", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "2045" => array ( "Area" => "Virginia", "City" => "Fredericksburg", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "2044" => array ( "Area" => "Virginia", "City" => "Fredericksburg", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "2043" => array ( "Area" => "Virginia", "City" => "Fredericksburg", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "2042" => array ( "Area" => "Virginia", "City" => "Fredericksburg", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "2041" => array ( "Area" => "Virginia", "City" => "Fredericksburg", "Operator" => "NUSO, LLC"),
    "2040" => array ( "Area" => "Virginia", "City" => "Fredericksburg", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2039" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "DISH WIRELESS, LLC"),
    "2031" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2030" => array ( "Area" => "Virginia", "City" => "Chancellor", "Operator" => "DISH WIRELESS, LLC"),
    "2029" => array ( "Area" => "Virginia", "City" => "Christiansburg", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2028" => array ( "Area" => "Virginia", "City" => "Christiansburg", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2027" => array ( "Area" => "Virginia", "City" => "Christiansburg", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2024" => array ( "Area" => "Virginia", "City" => "Christiansburg", "Operator" => "COMCAST IP PHONE, LLC"),
    "2023" => array ( "Area" => "Virginia", "City" => "Christiansburg", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2022" => array ( "Area" => "Virginia", "City" => "Christiansburg", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2021" => array ( "Area" => "Virginia", "City" => "Christiansburg", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2020" => array ( "Area" => "Virginia", "City" => "Christiansburg", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2017" => array ( "Area" => "Virginia", "City" => "Strasburg", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - VA"),
    "2011" => array ( "Area" => "Virginia", "City" => "Strasburg", "Operator" => "COMCAST IP PHONE, LLC"),
    "2000" => array ( "Area" => "Virginia", "City" => "Orange", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA")
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
