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
 * E.164 United States of America NDC 206 country hook
 */
framework_add_filter ( "e164_identify_NANPA_206", "e164_identify_NANPA_206");

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
function e164_identify_NANPA_206 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 206 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1206")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "884" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "QWEST CORPORATION"),
    "865" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "848" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "844" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "836" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "831" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "827" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "821" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY SPECTRUM, LLC"),
    "820" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "815" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "814" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "747" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "744" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ELECTRIC LIGHTWAVE LLC DBA ALLSTREAM"),
    "740" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "GLOBAL CROSSING LOCAL SERVICES, INC. - WA"),
    "704" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "690" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "672" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "668" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "656" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "649" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "647" => array ( "Area" => "Washington", "City" => "Richmond Beach", "Operator" => "ONVOY, LLC - WA"),
    "646" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - WA"),
    "644" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "COMCAST IP PHONE, LLC"),
    "630" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "QWEST CORPORATION"),
    "614" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "597" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "QWEST CORPORATION"),
    "572" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - WA"),
    "562" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "MAGNA5 LLC"),
    "514" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - WA"),
    "507" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - WA"),
    "500" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "QWEST CORPORATION"),
    "497" => array ( "Area" => "Washington", "City" => "Richmond Beach", "Operator" => "YMAX COMMUNICATIONS CORP. - WA"),
    "486" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "PEERLESS NETWORK OF WASHINGTON, LLC - WA"),
    "481" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "QWEST CORPORATION"),
    "477" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "QWEST CORPORATION"),
    "468" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "T-MOBILE USA, INC."),
    "450" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - WA"),
    "385" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "360" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA"),
    "348" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - WA"),
    "305" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - WA"),
    "207" => array ( "Area" => "Washington", "City" => "Seattle", "Operator" => "ONVOY, LLC - WA")
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
