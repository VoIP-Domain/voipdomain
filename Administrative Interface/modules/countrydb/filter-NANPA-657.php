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
 * E.164 United States of America NDC 657 country hook
 */
framework_add_filter ( "e164_identify_NANPA_657", "e164_identify_NANPA_657");

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
function e164_identify_NANPA_657 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 657 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1657")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "957" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "668" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "665" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "663" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "662" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "661" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "659" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "658" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "654" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "653" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "651" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "649" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "648" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "646" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "642" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "641" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "640" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "639" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "638" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "637" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "636" => array ( "Area" => "California", "City" => "Garden Grove", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "635" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "634" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "630" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "628" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "627" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "624" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "621" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "617" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "614" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "609" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "607" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "605" => array ( "Area" => "California", "City" => "Orange", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "603" => array ( "Area" => "California", "City" => "Cypress", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "601" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "598" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "597" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "596" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "595" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "593" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "592" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "591" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "590" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "589" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "586" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "584" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "583" => array ( "Area" => "California", "City" => "Garden Grove", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "581" => array ( "Area" => "California", "City" => "Orange", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "580" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "578" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "576" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "575" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "574" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "573" => array ( "Area" => "California", "City" => "Cypress", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "572" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "570" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "569" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "568" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "564" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "563" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "560" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "559" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "558" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "557" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "552" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "550" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "548" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "547" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "546" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "545" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "542" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "541" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "539" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "538" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "536" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "528" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "527" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "T-MOBILE USA, INC."),
    "524" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "521" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "519" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "518" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "517" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "516" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "515" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "513" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "512" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "510" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "509" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "507" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "506" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "504" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "503" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "501" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "498" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "497" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "496" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "495" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "494" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "493" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "492" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "491" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "490" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "489" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "488" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "484" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "483" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "482" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "481" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "480" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "479" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "477" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "476" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "475" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "474" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "473" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "472" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "471" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "470" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "469" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "468" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "466" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "463" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "462" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "461" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "460" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "459" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "458" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "457" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "455" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "454" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "453" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "451" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "450" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "449" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "448" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "447" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "442" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "440" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "437" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "436" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "435" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "434" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "433" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "432" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "430" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "429" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "426" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "424" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "423" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "421" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "420" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "419" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "418" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "417" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "416" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "415" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "414" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "412" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "409" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "407" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "406" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "405" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "404" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "403" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "402" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "401" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "399" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "398" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "396" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "393" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "392" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "389" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "388" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "386" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "382" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "381" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "380" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "379" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "374" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "371" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "370" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "369" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "367" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "361" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "353" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "350" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "349" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "344" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "343" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "338" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "336" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "330" => array ( "Area" => "California", "City" => "Fullerton", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "327" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "324" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "323" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "322" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "320" => array ( "Area" => "California", "City" => "Orange", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - CA"),
    "316" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "309" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "308" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "307" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "305" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "303" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "302" => array ( "Area" => "California", "City" => "Westminster", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "300" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "ONVOY, LLC - CA"),
    "299" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "292" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "290" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "287" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "285" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "283" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "279" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "ONVOY, LLC - CA"),
    "278" => array ( "Area" => "California", "City" => "Fullerton", "Operator" => "PACIFIC BELL"),
    "270" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "268" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "265" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "264" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "260" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "249" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "241" => array ( "Area" => "California", "City" => "Huntington Beach", "Operator" => "PAETEC COMMUNICATIONS, INC. - CA"),
    "240" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "228" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "225" => array ( "Area" => "California", "City" => "Fullerton", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA"),
    "219" => array ( "Area" => "California", "City" => "Santa Ana", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "209" => array ( "Area" => "California", "City" => "Silverado", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CA"),
    "202" => array ( "Area" => "California", "City" => "Anaheim", "Operator" => "PEERLESS NETWORK OF CALIFORNIA, LLC - CA")
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
