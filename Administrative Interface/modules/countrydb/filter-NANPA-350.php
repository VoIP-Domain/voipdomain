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
 * E.164 United States of America NDC 350 country hook
 */
framework_add_filter ( "e164_identify_NANPA_350", "e164_identify_NANPA_350");

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
function e164_identify_NANPA_350 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 350 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1350")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "9999" => array ( "Area" => "California", "City" => "Gustine", "Operator" => "TERRA NOVA TELECOM INC."),
    "9998" => array ( "Area" => "California", "City" => "Gustine", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "9997" => array ( "Area" => "California", "City" => "Gustine", "Operator" => "COMCAST IP PHONE, LLC"),
    "9990" => array ( "Area" => "California", "City" => "Gustine", "Operator" => "COMMIO, LLC"),
    "8889" => array ( "Area" => "California", "City" => "Groveland", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "8888" => array ( "Area" => "California", "City" => "Groveland", "Operator" => "TERRA NOVA TELECOM INC."),
    "8886" => array ( "Area" => "California", "City" => "Groveland", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - CA"),
    "8881" => array ( "Area" => "California", "City" => "Groveland", "Operator" => "TELNYX LLC"),
    "8880" => array ( "Area" => "California", "City" => "Groveland", "Operator" => "COMMIO, LLC"),
    "7777" => array ( "Area" => "California", "City" => "Galt", "Operator" => "TERRA NOVA TELECOM INC."),
    "7770" => array ( "Area" => "California", "City" => "Galt", "Operator" => "TELNYX LLC"),
    "6669" => array ( "Area" => "California", "City" => "San Andreas", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "6666" => array ( "Area" => "California", "City" => "San Andreas", "Operator" => "TERRA NOVA TELECOM INC."),
    "6660" => array ( "Area" => "California", "City" => "San Andreas", "Operator" => "TELNYX LLC"),
    "4449" => array ( "Area" => "California", "City" => "Snelling", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "4444" => array ( "Area" => "California", "City" => "Snelling", "Operator" => "TERRA NOVA TELECOM INC."),
    "4443" => array ( "Area" => "California", "City" => "Snelling", "Operator" => "ONVOY, LLC - CA"),
    "3339" => array ( "Area" => "California", "City" => "Newman", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "3333" => array ( "Area" => "California", "City" => "Newman", "Operator" => "TERRA NOVA TELECOM INC."),
    "3059" => array ( "Area" => "California", "City" => "Yosemite", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "3055" => array ( "Area" => "California", "City" => "Yosemite", "Operator" => "ONVOY, LLC - CA"),
    "3054" => array ( "Area" => "California", "City" => "Yosemite", "Operator" => "ONVOY, LLC - CA"),
    "3053" => array ( "Area" => "California", "City" => "Yosemite", "Operator" => "ONVOY, LLC - CA"),
    "3051" => array ( "Area" => "California", "City" => "Yosemite", "Operator" => "TELNYX LLC"),
    "3050" => array ( "Area" => "California", "City" => "Yosemite", "Operator" => "COMMIO, LLC"),
    "2509" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "2508" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "ONVOY, LLC - CA"),
    "2507" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "ONVOY, LLC - CA"),
    "2506" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "COMMIO, LLC"),
    "2505" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "ONVOY, LLC - CA"),
    "2504" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "ONVOY, LLC - CA"),
    "2503" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "ONVOY, LLC - CA"),
    "2502" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "ONVOY, LLC - CA"),
    "2501" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "2500" => array ( "Area" => "California", "City" => "Tracy", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "2359" => array ( "Area" => "California", "City" => "Clements", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "2353" => array ( "Area" => "California", "City" => "Clements", "Operator" => "ONVOY, LLC - CA"),
    "2352" => array ( "Area" => "California", "City" => "Clements", "Operator" => "ONVOY, LLC - CA"),
    "2229" => array ( "Area" => "California", "City" => "Hughson", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "2222" => array ( "Area" => "California", "City" => "Hughson", "Operator" => "TERRA NOVA TELECOM INC."),
    "2051" => array ( "Area" => "California", "City" => "Planada", "Operator" => "COMCAST IP PHONE, LLC"),
    "2050" => array ( "Area" => "California", "City" => "Planada", "Operator" => "TELNYX LLC"),
    "2049" => array ( "Area" => "California", "City" => "Atwater", "Operator" => "T-MOBILE USA, INC."),
    "2048" => array ( "Area" => "California", "City" => "Atwater", "Operator" => "T-MOBILE USA, INC."),
    "2047" => array ( "Area" => "California", "City" => "Atwater", "Operator" => "T-MOBILE USA, INC."),
    "2046" => array ( "Area" => "California", "City" => "Atwater", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "2039" => array ( "Area" => "California", "City" => "Copperopolis", "Operator" => "DISH WIRELESS, LLC"),
    "2029" => array ( "Area" => "California", "City" => "Thornton", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "2022" => array ( "Area" => "California", "City" => "Thornton", "Operator" => "COMMIO, LLC"),
    "2021" => array ( "Area" => "California", "City" => "Thornton", "Operator" => "COMMIO, LLC"),
    "2020" => array ( "Area" => "California", "City" => "Thornton", "Operator" => "COMMIO, LLC"),
    "2019" => array ( "Area" => "California", "City" => "Wallace", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "2018" => array ( "Area" => "California", "City" => "Wallace", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "2012" => array ( "Area" => "California", "City" => "Wallace", "Operator" => "ONVOY, LLC - CA"),
    "2011" => array ( "Area" => "California", "City" => "Wallace", "Operator" => "COMCAST IP PHONE, LLC"),
    "2010" => array ( "Area" => "California", "City" => "Wallace", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "2004" => array ( "Area" => "California", "City" => "Lodi", "Operator" => "ONVOY, LLC - CA"),
    "2003" => array ( "Area" => "California", "City" => "Lodi", "Operator" => "ONVOY, LLC - CA"),
    "2002" => array ( "Area" => "California", "City" => "Lodi", "Operator" => "BANDWIDTH.COM CLEC, LLC - CA"),
    "2001" => array ( "Area" => "California", "City" => "Lodi", "Operator" => "BANDWIDTH.COM CLEC, LLC - CA"),
    "2000" => array ( "Area" => "California", "City" => "Lodi", "Operator" => "BANDWIDTH.COM CLEC, LLC - CA")
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
