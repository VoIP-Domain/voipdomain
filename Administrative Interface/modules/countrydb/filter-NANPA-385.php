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
 * E.164 United States of America NDC 385 country hook
 */
framework_add_filter ( "e164_identify_NANPA_385", "e164_identify_NANPA_385");

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
function e164_identify_NANPA_385 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 385 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1385")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "646" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "QWEST CORPORATION"),
    "505" => array ( "Area" => "Utah", "City" => "Provo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "502" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "496" => array ( "Area" => "Utah", "City" => "American Fork", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "495" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "468" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "QWEST CORPORATION"),
    "467" => array ( "Area" => "Utah", "City" => "Ogden", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "460" => array ( "Area" => "Utah", "City" => "Ogden", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "459" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "457" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "T-MOBILE USA, INC."),
    "456" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "T-MOBILE USA, INC."),
    "446" => array ( "Area" => "Utah", "City" => "Alta", "Operator" => "BANDWIDTH.COM CLEC, LLC - UT"),
    "441" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "431" => array ( "Area" => "Utah", "City" => "American Fork", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "428" => array ( "Area" => "Utah", "City" => "Murray", "Operator" => "XO UTAH, INC."),
    "413" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "408" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "407" => array ( "Area" => "Utah", "City" => "American Fork", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "397" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "395" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "373" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "372" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "369" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "366" => array ( "Area" => "Utah", "City" => "Midvale", "Operator" => "VERACITY COMMUNICATIONS, INC. - UT"),
    "362" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "357" => array ( "Area" => "Utah", "City" => "American Fork", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "329" => array ( "Area" => "Utah", "City" => "Provo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "328" => array ( "Area" => "Utah", "City" => "American Fork", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "322" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "FIRSTDIGITAL TELECOM, LLC - UT"),
    "321" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "320" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "315" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "305" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "302" => array ( "Area" => "Utah", "City" => "Midvale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "291" => array ( "Area" => "Utah", "City" => "Kaysville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "277" => array ( "Area" => "Utah", "City" => "Provo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "272" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "263" => array ( "Area" => "Utah", "City" => "Alta", "Operator" => "ONVOY, LLC - UT"),
    "247" => array ( "Area" => "Utah", "City" => "Midvale", "Operator" => "ONVOY, LLC - UT"),
    "228" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "224" => array ( "Area" => "Utah", "City" => "Provo", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "217" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "ONVOY, LLC - UT"),
    "216" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "T-MOBILE USA, INC."),
    "214" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - UT"),
    "213" => array ( "Area" => "Utah", "City" => "Salt Lake City", "Operator" => "ONVOY, LLC - UT")
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
