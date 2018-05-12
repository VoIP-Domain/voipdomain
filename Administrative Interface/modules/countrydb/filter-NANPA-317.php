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
 * E.164 United States of America NDC 317 country hook
 */
framework_add_filter ( "e164_identify_NANPA_317", "e164_identify_NANPA_317");

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
function e164_identify_NANPA_317 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 317 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1317")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "998" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "OMNIPOINT COMMUNICATIONS MIDWEST OPERATIONS, LLC"),
    "995" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IN"),
    "970" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "OMNIPOINT COMMUNICATIONS MIDWEST OPERATIONS, LLC"),
    "957" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "LEVEL 3 TELECOM OF INDIANA, LP - IN"),
    "954" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IN"),
    "948" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "HRS INTERNET LLC DBA EVERSTREAM"),
    "944" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "HRS INTERNET LLC DBA EVERSTREAM"),
    "935" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "909" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "OMNIPOINT COMMUNICATIONS MIDWEST OPERATIONS, LLC"),
    "880" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "INDIANA BELL TEL CO INC"),
    "778" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "772" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "OMNIPOINT COMMUNICATIONS MIDWEST OPERATIONS, LLC"),
    "771" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IN"),
    "741" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IN"),
    "719" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IN"),
    "676" => array ( "Area" => "Indiana", "City" => "Marietta", "Operator" => "ONVOY, LLC - IN"),
    "643" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "BANDWIDTH.COM CLEC, LLC - IN"),
    "641" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "YMAX COMMUNICATIONS CORP. - IN"),
    "629" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "SPRINT SPECTRUM, L.P."),
    "628" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "SPRINT SPECTRUM, L.P."),
    "619" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IN"),
    "617" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IN"),
    "601" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IN"),
    "559" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "PEERLESS NETWORK OF INDIANA, LLC - IN"),
    "478" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "473" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IN"),
    "400" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "SPRINT SPECTRUM, L.P."),
    "212" => array ( "Area" => "Indiana", "City" => "Indianapolis", "Operator" => "INDIANA BELL TEL CO INC")
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
