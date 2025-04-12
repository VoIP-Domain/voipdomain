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
 * E.164 United States of America NDC 948 country hook
 */
framework_add_filter ( "e164_identify_NANPA_948", "e164_identify_NANPA_948");

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
function e164_identify_NANPA_948 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 948 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1948")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "9999" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "FRACTEL, LLC"),
    "9998" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "TERRA NOVA TELECOM INC."),
    "9997" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "METROPCS, INC."),
    "9995" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "METROPCS, INC."),
    "9994" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "METROPCS, INC."),
    "9993" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "METROPCS, INC."),
    "9992" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "METROPCS, INC."),
    "9991" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "9990" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "9464" => array ( "Area" => "Virginia", "City" => "Toano", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "9426" => array ( "Area" => "Virginia", "City" => "Crittenden", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "9422" => array ( "Area" => "Virginia", "City" => "Crittenden", "Operator" => "ONVOY, LLC - VA"),
    "9421" => array ( "Area" => "Virginia", "City" => "Crittenden", "Operator" => "TELNYX LLC"),
    "9420" => array ( "Area" => "Virginia", "City" => "Crittenden", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "9009" => array ( "Area" => "Virginia", "City" => "Chuckatuck", "Operator" => "FRACTEL, LLC"),
    "9002" => array ( "Area" => "Virginia", "City" => "Chuckatuck", "Operator" => "ONVOY, LLC - VA"),
    "9001" => array ( "Area" => "Virginia", "City" => "Chuckatuck", "Operator" => "TELNYX LLC"),
    "9000" => array ( "Area" => "Virginia", "City" => "Chuckatuck", "Operator" => "FRACTEL, LLC"),
    "8889" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8888" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "FRACTEL, LLC"),
    "8887" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8886" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8885" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "8884" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "8883" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "8882" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8881" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8880" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8678" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "COMMIO, LLC"),
    "8677" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "COMMIO, LLC"),
    "8676" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "COMMIO, LLC"),
    "8675" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "COMMIO, LLC"),
    "8009" => array ( "Area" => "Virginia", "City" => "Claremont", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "8008" => array ( "Area" => "Virginia", "City" => "Claremont", "Operator" => "FRACTEL, LLC"),
    "8007" => array ( "Area" => "Virginia", "City" => "Claremont", "Operator" => "ONVOY, LLC - VA"),
    "8000" => array ( "Area" => "Virginia", "City" => "Claremont", "Operator" => "FRACTEL, LLC"),
    "7777" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 6", "Operator" => "FRACTEL, LLC"),
    "7776" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 6", "Operator" => "ONVOY, LLC - VA"),
    "7775" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 6", "Operator" => "ONVOY, LLC - VA"),
    "7426" => array ( "Area" => "Virginia", "City" => "Newport News Zone 4", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "7425" => array ( "Area" => "Virginia", "City" => "Newport News Zone 4", "Operator" => "ONVOY, LLC - VA"),
    "7424" => array ( "Area" => "Virginia", "City" => "Newport News Zone 4", "Operator" => "ONVOY, LLC - VA"),
    "6669" => array ( "Area" => "Virginia", "City" => "Newport News Zone 1", "Operator" => "VONAGE AMERICA LLC"),
    "6667" => array ( "Area" => "Virginia", "City" => "Newport News Zone 1", "Operator" => "TERRA NOVA TELECOM INC."),
    "6666" => array ( "Area" => "Virginia", "City" => "Newport News Zone 1", "Operator" => "FRACTEL, LLC"),
    "6665" => array ( "Area" => "Virginia", "City" => "Newport News Zone 1", "Operator" => "ONVOY, LLC - VA"),
    "6664" => array ( "Area" => "Virginia", "City" => "Newport News Zone 1", "Operator" => "ONVOY, LLC - VA"),
    "6663" => array ( "Area" => "Virginia", "City" => "Newport News Zone 1", "Operator" => "ONVOY, LLC - VA"),
    "6662" => array ( "Area" => "Virginia", "City" => "Newport News Zone 1", "Operator" => "ONVOY, LLC - VA"),
    "6426" => array ( "Area" => "Virginia", "City" => "Temperanceville", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "6421" => array ( "Area" => "Virginia", "City" => "Temperanceville", "Operator" => "DISH WIRELESS, LLC"),
    "6420" => array ( "Area" => "Virginia", "City" => "Temperanceville", "Operator" => "DISH WIRELESS, LLC"),
    "6379" => array ( "Area" => "Virginia", "City" => "Tangier", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "6378" => array ( "Area" => "Virginia", "City" => "Tangier", "Operator" => "ONVOY, LLC - VA"),
    "6377" => array ( "Area" => "Virginia", "City" => "Tangier", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "6006" => array ( "Area" => "Virginia", "City" => "Boykins", "Operator" => "FRACTEL, LLC"),
    "6000" => array ( "Area" => "Virginia", "City" => "Boykins", "Operator" => "FRACTEL, LLC"),
    "5426" => array ( "Area" => "Virginia", "City" => "Parksley", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "5425" => array ( "Area" => "Virginia", "City" => "Parksley", "Operator" => "ONVOY, LLC - VA"),
    "5424" => array ( "Area" => "Virginia", "City" => "Parksley", "Operator" => "ONVOY, LLC - VA"),
    "5299" => array ( "Area" => "Virginia", "City" => "Williamsburg", "Operator" => "FRACTEL, LLC"),
    "5298" => array ( "Area" => "Virginia", "City" => "Williamsburg", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "5293" => array ( "Area" => "Virginia", "City" => "Williamsburg", "Operator" => "FRACTEL, LLC"),
    "5292" => array ( "Area" => "Virginia", "City" => "Williamsburg", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "5291" => array ( "Area" => "Virginia", "City" => "Williamsburg", "Operator" => "METROPCS, INC."),
    "5290" => array ( "Area" => "Virginia", "City" => "Williamsburg", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "5006" => array ( "Area" => "Virginia", "City" => "Eastville", "Operator" => "ONVOY, LLC - VA"),
    "5005" => array ( "Area" => "Virginia", "City" => "Eastville", "Operator" => "FRACTEL, LLC"),
    "5001" => array ( "Area" => "Virginia", "City" => "Eastville", "Operator" => "TELNYX LLC"),
    "5000" => array ( "Area" => "Virginia", "City" => "Eastville", "Operator" => "FRACTEL, LLC"),
    "4688" => array ( "Area" => "Virginia", "City" => "Cape Charles", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "4658" => array ( "Area" => "Virginia", "City" => "Windsor", "Operator" => "FRACTEL, LLC"),
    "4655" => array ( "Area" => "Virginia", "City" => "Windsor", "Operator" => "ONVOY, LLC - VA"),
    "4654" => array ( "Area" => "Virginia", "City" => "Windsor", "Operator" => "ONVOY, LLC - VA"),
    "4653" => array ( "Area" => "Virginia", "City" => "Windsor", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "4650" => array ( "Area" => "Virginia", "City" => "Windsor", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "4446" => array ( "Area" => "Virginia", "City" => "Chincoteague", "Operator" => "ONVOY, LLC - VA"),
    "4445" => array ( "Area" => "Virginia", "City" => "Chincoteague", "Operator" => "TERRA NOVA TELECOM INC."),
    "4444" => array ( "Area" => "Virginia", "City" => "Chincoteague", "Operator" => "FRACTEL, LLC"),
    "4009" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "METROPCS, INC."),
    "4008" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "METROPCS, INC."),
    "4007" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "4006" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "ONVOY SPECTRUM, LLC"),
    "4005" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "ONVOY SPECTRUM, LLC"),
    "4004" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "FRACTEL, LLC"),
    "4003" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "ONVOY SPECTRUM, LLC"),
    "4002" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "ONVOY SPECTRUM, LLC"),
    "4001" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "ONVOY SPECTRUM, LLC"),
    "4000" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "FRACTEL, LLC"),
    "3569" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "FRACTEL, LLC"),
    "3568" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "ONVOY, LLC - VA"),
    "3567" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "FRACTEL, LLC"),
    "3565" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "ONVOY, LLC - VA"),
    "3564" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "ONVOY, LLC - VA"),
    "3563" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "3562" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "3561" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "3560" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "3339" => array ( "Area" => "Virginia", "City" => "Holland", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "3334" => array ( "Area" => "Virginia", "City" => "Holland", "Operator" => "ONVOY, LLC - VA"),
    "3333" => array ( "Area" => "Virginia", "City" => "Holland", "Operator" => "FRACTEL, LLC"),
    "3330" => array ( "Area" => "Virginia", "City" => "Holland", "Operator" => "TELNYX LLC"),
    "3009" => array ( "Area" => "Virginia", "City" => "Wakefield", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "3003" => array ( "Area" => "Virginia", "City" => "Wakefield", "Operator" => "FRACTEL, LLC"),
    "3002" => array ( "Area" => "Virginia", "City" => "Wakefield", "Operator" => "ONVOY, LLC - VA"),
    "3001" => array ( "Area" => "Virginia", "City" => "Wakefield", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "3000" => array ( "Area" => "Virginia", "City" => "Wakefield", "Operator" => "FRACTEL, LLC"),
    "2658" => array ( "Area" => "Virginia", "City" => "Smithfield", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2655" => array ( "Area" => "Virginia", "City" => "Smithfield", "Operator" => "COMMIO, LLC"),
    "2654" => array ( "Area" => "Virginia", "City" => "Smithfield", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "2653" => array ( "Area" => "Virginia", "City" => "Smithfield", "Operator" => "ONVOY, LLC - VA"),
    "2652" => array ( "Area" => "Virginia", "City" => "Smithfield", "Operator" => "ONVOY, LLC - VA"),
    "2651" => array ( "Area" => "Virginia", "City" => "Smithfield", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2650" => array ( "Area" => "Virginia", "City" => "Smithfield", "Operator" => "TELNYX LLC"),
    "2439" => array ( "Area" => "Virginia", "City" => "Surry", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "2438" => array ( "Area" => "Virginia", "City" => "Surry", "Operator" => "ONVOY, LLC - VA"),
    "2437" => array ( "Area" => "Virginia", "City" => "Surry", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "2436" => array ( "Area" => "Virginia", "City" => "Surry", "Operator" => "ONVOY, LLC - VA"),
    "2430" => array ( "Area" => "Virginia", "City" => "Surry", "Operator" => "TELNYX LLC"),
    "2426" => array ( "Area" => "Virginia", "City" => "Ivor", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "2423" => array ( "Area" => "Virginia", "City" => "Ivor", "Operator" => "ONVOY, LLC - VA"),
    "2422" => array ( "Area" => "Virginia", "City" => "Ivor", "Operator" => "TERRA NOVA TELECOM INC."),
    "2229" => array ( "Area" => "Virginia", "City" => "Franklin", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "2224" => array ( "Area" => "Virginia", "City" => "Franklin", "Operator" => "ONVOY, LLC - VA"),
    "2223" => array ( "Area" => "Virginia", "City" => "Franklin", "Operator" => "ONVOY, LLC - VA"),
    "2222" => array ( "Area" => "Virginia", "City" => "Franklin", "Operator" => "FRACTEL, LLC"),
    "2221" => array ( "Area" => "Virginia", "City" => "Franklin", "Operator" => "METROPCS, INC."),
    "2099" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "METROPCS, INC."),
    "2098" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "ONVOY, LLC - VA"),
    "2097" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "ONVOY, LLC - VA"),
    "2096" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "ONVOY, LLC - VA"),
    "2095" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "ONVOY, LLC - VA"),
    "2094" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "VONAGE AMERICA LLC"),
    "2093" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "TELNYX LLC"),
    "2092" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2091" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2090" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2076" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "ONVOY, LLC - VA"),
    "2075" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "ONVOY, LLC - VA"),
    "2074" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "ONVOY, LLC - VA"),
    "2069" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2068" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2067" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2066" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "ONVOY, LLC - VA"),
    "2065" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "ONVOY, LLC - VA"),
    "2064" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "ONVOY, LLC - VA"),
    "2063" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "ONVOY, LLC - VA"),
    "2054" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "ONVOY, LLC - VA"),
    "2053" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "ONVOY, LLC - VA"),
    "2050" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2049" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2048" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2047" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2046" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2042" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2041" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2040" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 3", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2039" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2038" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2037" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2036" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2035" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2034" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2033" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2032" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "METROPCS, INC."),
    "2031" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "METROPCS, INC."),
    "2030" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "2029" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2028" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2027" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "2026" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "ONVOY, LLC - VA"),
    "2025" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "ONVOY, LLC - VA"),
    "2024" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "ONVOY, LLC - VA"),
    "2023" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "COMMIO, LLC"),
    "2022" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "TERRA NOVA TELECOM INC."),
    "2021" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2020" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "2015" => array ( "Area" => "Virginia", "City" => "Toano", "Operator" => "ONVOY, LLC - VA"),
    "2014" => array ( "Area" => "Virginia", "City" => "Toano", "Operator" => "ONVOY, LLC - VA"),
    "2013" => array ( "Area" => "Virginia", "City" => "Toano", "Operator" => "ONVOY, LLC - VA"),
    "2012" => array ( "Area" => "Virginia", "City" => "Toano", "Operator" => "ONVOY, LLC - VA"),
    "2010" => array ( "Area" => "Virginia", "City" => "Toano", "Operator" => "COMMIO, LLC"),
    "2009" => array ( "Area" => "Virginia", "City" => "Whaleyville", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "2003" => array ( "Area" => "Virginia", "City" => "Whaleyville", "Operator" => "ONVOY, LLC - VA"),
    "2002" => array ( "Area" => "Virginia", "City" => "Whaleyville", "Operator" => "FRACTEL, LLC"),
    "2000" => array ( "Area" => "Virginia", "City" => "Whaleyville", "Operator" => "FRACTEL, LLC")
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
