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
 * E.164 United States of America NDC 215 country hook
 */
framework_add_filter ( "e164_identify_NANPA_215", "e164_identify_NANPA_215");

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
function e164_identify_NANPA_215 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 215 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1215")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "954" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "METROPCS, INC."),
    "936" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "926" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "VERIZON PENNSYLVANIA, INC."),
    "890" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "BLOCK LINE SYSTEMS, LLC - PA"),
    "847" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "770" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 4", "Operator" => "ONVOY, LLC - PA"),
    "714" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "ONVOY, LLC - PA"),
    "678" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "626" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "METROPCS, INC."),
    "617" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "532" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 43", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "388" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "303" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "METROPCS, INC."),
    "301" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "286" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "ATX TELECOMMUNICATIONS SERVICES, LTD."),
    "251" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "250" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Zone 2", "Operator" => "ONVOY SPECTRUM, LLC")
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
