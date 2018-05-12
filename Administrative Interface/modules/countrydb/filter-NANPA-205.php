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
 * E.164 United States of America NDC 205 country hook
 */
framework_add_filter ( "e164_identify_NANPA_205", "e164_identify_NANPA_205");

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
function e164_identify_NANPA_205 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 205 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1205")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "971" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTH CENTRAL BELL TEL"),
    "892" => array ( "Area" => "Alabama", "City" => "Cordova", "Operator" => "ONVOY, LLC - AL"),
    "866" => array ( "Area" => "Alabama", "City" => "Dora", "Operator" => "ONVOY SPECTRUM, LLC"),
    "864" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "851" => array ( "Area" => "Alabama", "City" => "Montevallo", "Operator" => "ONVOY, LLC - AL"),
    "846" => array ( "Area" => "Alabama", "City" => "Livingston (Sumter)", "Operator" => "ONVOY, LLC - AL"),
    "842" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "827" => array ( "Area" => "Alabama", "City" => "Flatwood", "Operator" => "ONVOY, LLC - AL"),
    "820" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "DELTACOM, INC. - AL"),
    "767" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "766" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTH CENTRAL BELL TEL"),
    "765" => array ( "Area" => "Alabama", "City" => "Tuscaloosa", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "704" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "701" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "YMAX COMMUNICATIONS CORP. - AL"),
    "653" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTH CENTRAL BELL TEL"),
    "645" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "643" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "POWERTEL BIRMINGHAM LICENSES, INC."),
    "638" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTH CENTRAL BELL TEL"),
    "604" => array ( "Area" => "Alabama", "City" => "Butler", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "603" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "600" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "597" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "524" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTH CENTRAL BELL TEL"),
    "514" => array ( "Area" => "Alabama", "City" => "Gardendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "495" => array ( "Area" => "Alabama", "City" => "Guin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "475" => array ( "Area" => "Alabama", "City" => "Bessemer", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "470" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "449" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "LEVEL 3 TELECOM OF ALABAMA, LLC - AL"),
    "236" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "ONVOY, LLC - AL"),
    "234" => array ( "Area" => "Alabama", "City" => "Birmingham", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AL"),
    "200" => array ( "Area" => "Alabama", "City" => "Springville", "Operator" => "POWERTEL BIRMINGHAM LICENSES, INC.")
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
