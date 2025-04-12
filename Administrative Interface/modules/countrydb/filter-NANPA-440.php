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
 * E.164 United States of America NDC 440 country hook
 */
framework_add_filter ( "e164_identify_NANPA_440", "e164_identify_NANPA_440");

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
function e164_identify_NANPA_440 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 440 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1440")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "952" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "910" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - OH"),
    "908" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "894" => array ( "Area" => "Ohio", "City" => "Elyria", "Operator" => "ONVOY, LLC - OH"),
    "818" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "806" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "803" => array ( "Area" => "Ohio", "City" => "Victory", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "802" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "800" => array ( "Area" => "Ohio", "City" => "Victory", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "770" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "763" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "727" => array ( "Area" => "Ohio", "City" => "Victory", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "718" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "713" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "712" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "706" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "704" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "702" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "674" => array ( "Area" => "Ohio", "City" => "Elyria", "Operator" => "T-MOBILE USA, INC."),
    "651" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "633" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "OHIO BELL TEL CO"),
    "631" => array ( "Area" => "Ohio", "City" => "Victory", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "626" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "621" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "620" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - OH"),
    "619" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "618" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "615" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "609" => array ( "Area" => "Ohio", "City" => "Victory", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "608" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "601" => array ( "Area" => "Ohio", "City" => "Trinity", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "592" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - OH"),
    "559" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "518" => array ( "Area" => "Ohio", "City" => "Victory", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "515" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "509" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "504" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "501" => array ( "Area" => "Ohio", "City" => "Victory", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "495" => array ( "Area" => "Ohio", "City" => "Victory", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "480" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "475" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "470" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "465" => array ( "Area" => "Ohio", "City" => "Cleveland", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "464" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "462" => array ( "Area" => "Ohio", "City" => "Cleveland", "Operator" => "BANDWIDTH.COM CLEC, LLC - OH"),
    "451" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "450" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "441" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "432" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "430" => array ( "Area" => "Ohio", "City" => "Trinity", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "418" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "416" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "410" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "408" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "407" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "405" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "404" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "402" => array ( "Area" => "Ohio", "City" => "Trinity", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "400" => array ( "Area" => "Ohio", "City" => "Chagrin Falls", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "314" => array ( "Area" => "Ohio", "City" => "Berea", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "217" => array ( "Area" => "Ohio", "City" => "Hillcrest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "208" => array ( "Area" => "Ohio", "City" => "Brecksville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH")
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
