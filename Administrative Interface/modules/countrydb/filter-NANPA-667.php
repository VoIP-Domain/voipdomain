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
 * E.164 United States of America NDC 667 country hook
 */
framework_add_filter ( "e164_identify_NANPA_667", "e164_identify_NANPA_667");

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
function e164_identify_NANPA_667 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 667 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1667")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "999" => array ( "Area" => "Maryland", "City" => "Elkridge", "Operator" => "ONVOY, LLC - MD"),
    "888" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "ONVOY, LLC - MD"),
    "876" => array ( "Area" => "Maryland", "City" => "Annapolis", "Operator" => "CORE COMMUNICATIONS, INC.- MD"),
    "812" => array ( "Area" => "Maryland", "City" => "Waterloo 236", "Operator" => "VERIZON MARYLAND, INC."),
    "627" => array ( "Area" => "Maryland", "City" => "Annapolis", "Operator" => "CORE COMMUNICATIONS, INC.- MD"),
    "422" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "CENTURYLINK COMMUNICATIONS, LLC"),
    "391" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "379" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "373" => array ( "Area" => "Maryland", "City" => "North Beach", "Operator" => "CORE COMMUNICATIONS, INC.- MD"),
    "362" => array ( "Area" => "Maryland", "City" => "Annapolis", "Operator" => "CORE COMMUNICATIONS, INC.- MD"),
    "355" => array ( "Area" => "Maryland", "City" => "Aberdeen", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "343" => array ( "Area" => "Maryland", "City" => "Cambridge", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - MD"),
    "332" => array ( "Area" => "Maryland", "City" => "New Windsor", "Operator" => "CORE COMMUNICATIONS, INC.- MD"),
    "320" => array ( "Area" => "Maryland", "City" => "Aberdeen", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "313" => array ( "Area" => "Maryland", "City" => "Pikesville", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - MD"),
    "306" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - MD"),
    "277" => array ( "Area" => "Maryland", "City" => "Sherwood Forest", "Operator" => "CORE COMMUNICATIONS, INC.- MD"),
    "267" => array ( "Area" => "Maryland", "City" => "Annapolis", "Operator" => "CORE COMMUNICATIONS, INC.- MD"),
    "251" => array ( "Area" => "Maryland", "City" => "Towson", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - MD"),
    "246" => array ( "Area" => "Maryland", "City" => "Centreville", "Operator" => "CORE COMMUNICATIONS, INC.- MD"),
    "233" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "VERIZON MARYLAND, INC."),
    "209" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "203" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD")
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
