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
 * E.164 United States of America NDC 484 country hook
 */
framework_add_filter ( "e164_identify_NANPA_484", "e164_identify_NANPA_484");

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
function e164_identify_NANPA_484 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 484 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1484")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "990" => array ( "Area" => "Pennsylvania", "City" => "Bethlehem", "Operator" => "HD CARRIER LLC"),
    "891" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "PEERLESS NETWORK OF PENNSYLVANIA, LLC - PA"),
    "877" => array ( "Area" => "Pennsylvania", "City" => "Reading", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "827" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 10", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "822" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - PA"),
    "777" => array ( "Area" => "Pennsylvania", "City" => "Bethlehem", "Operator" => "SERVICE ELECTRIC TELEPHONE COMPANY, LLC"),
    "740" => array ( "Area" => "Pennsylvania", "City" => "Reading", "Operator" => "HD CARRIER LLC"),
    "721" => array ( "Area" => "Pennsylvania", "City" => "Reading", "Operator" => "METROPCS, INC."),
    "658" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - PA"),
    "649" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "628" => array ( "Area" => "Pennsylvania", "City" => "Reading", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - PA"),
    "622" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 30", "Operator" => "VERIZON PENNSYLVANIA, INC."),
    "609" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 10", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "566" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 30", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "560" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "526" => array ( "Area" => "Pennsylvania", "City" => "Bethlehem", "Operator" => "XO COMMUNICATIONS SERVICES, INC. - PA"),
    "523" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "501" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 30", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "481" => array ( "Area" => "Pennsylvania", "City" => "West Chester", "Operator" => "HD CARRIER LLC"),
    "377" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 21", "Operator" => "ONVOY, LLC - PA"),
    "355" => array ( "Area" => "Pennsylvania", "City" => "Reading", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - PA"),
    "216" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 28", "Operator" => "PAETEC COMMUNICATIONS, INC. - PA")
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
