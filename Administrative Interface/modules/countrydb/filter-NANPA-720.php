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
 * E.164 United States of America NDC 720 country hook
 */
framework_add_filter ( "e164_identify_NANPA_720", "e164_identify_NANPA_720");

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
function e164_identify_NANPA_720 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 720 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1720")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "992" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - CO"),
    "969" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "960" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "955" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "948" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "926" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "919" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "T-MOBILE USA, INC."),
    "916" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "912" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "910" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "860" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY SPECTRUM, LLC"),
    "847" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "822" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "821" => array ( "Area" => "Colorado", "City" => "Longmont", "Operator" => "ONVOY, LLC - CO"),
    "807" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "793" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "792" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "788" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "783" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "781" => array ( "Area" => "Colorado", "City" => "Longmont", "Operator" => "T-MOBILE USA, INC."),
    "780" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "777" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "765" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "757" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "T-MOBILE USA, INC."),
    "755" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "T-MOBILE USA, INC."),
    "754" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "LEVEL 3 TELECOM OF COLORADO, LLC - CO"),
    "752" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - CO"),
    "742" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "T-MOBILE USA, INC."),
    "737" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "718" => array ( "Area" => "Colorado", "City" => "Longmont", "Operator" => "QWEST CORPORATION"),
    "714" => array ( "Area" => "Colorado", "City" => "Longmont", "Operator" => "ONVOY, LLC - CO"),
    "706" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "705" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "699" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "686" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "XO COLORADO, LLC"),
    "682" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "677" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "661" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "660" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "657" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "BANDWIDTH.COM CLEC, LLC - CO"),
    "655" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "T-MOBILE USA, INC."),
    "654" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "638" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "COMCAST IP PHONE, LLC"),
    "637" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "632" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - CO"),
    "631" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "626" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "623" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "618" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "610" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "607" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "605" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "601" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "591" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "589" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "T-MOBILE USA, INC."),
    "584" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "576" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "575" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "574" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "BANDWIDTH.COM CLEC, LLC - CO"),
    "569" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "556" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "553" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "543" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "PEERLESS NETWORK OF COLORADO, LLC - CO"),
    "522" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "XO COLORADO, LLC"),
    "517" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "516" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "515" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "BANDWIDTH.COM CLEC, LLC - CO"),
    "513" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "ONVOY, LLC - CO"),
    "507" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "BANDWIDTH.COM CLEC, LLC - CO"),
    "503" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "498" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "478" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "LEVEL 3 TELECOM OF COLORADO, LLC - CO"),
    "471" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "467" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "464" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "BANDWIDTH.COM CLEC, LLC - CO"),
    "461" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "METROPCS, INC."),
    "455" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "452" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "PEERLESS NETWORK OF COLORADO, LLC - CO"),
    "447" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "433" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "432" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "BANDWIDTH.COM CLEC, LLC - CO"),
    "431" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "430" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "AT&T - LOCAL"),
    "429" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "426" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "425" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "424" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "423" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "421" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "T-MOBILE USA, INC."),
    "415" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "413" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO"),
    "412" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "405" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "INTRADO COMMUNICATIONS, LLC"),
    "401" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "T-MOBILE USA, INC."),
    "396" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "AT&T - LOCAL"),
    "395" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "AT&T - LOCAL"),
    "370" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "BANDWIDTH.COM CLEC, LLC - CO"),
    "369" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "368" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "XO COLORADO, LLC"),
    "367" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "XO COLORADO, LLC"),
    "349" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - CO"),
    "342" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "AT&T - LOCAL"),
    "337" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "321" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "QWEST CORPORATION"),
    "316" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "BANDWIDTH.COM CLEC, LLC - CO"),
    "295" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "BANDWIDTH.COM CLEC, LLC - CO"),
    "286" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - CO"),
    "265" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "XO COLORADO, LLC"),
    "247" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "XO COLORADO, LLC"),
    "202" => array ( "Area" => "Colorado", "City" => "Denver", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - CO")
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
