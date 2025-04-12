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
 * E.164 United States of America NDC 571 country hook
 */
framework_add_filter ( "e164_identify_NANPA_571", "e164_identify_NANPA_571");

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
function e164_identify_NANPA_571 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 571 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1571")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "767" => array ( "Area" => "Virginia", "City" => "Engleside", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "710" => array ( "Area" => "Virginia", "City" => "Lorton", "Operator" => "ONVOY SPECTRUM, LLC"),
    "649" => array ( "Area" => "Virginia", "City" => "Washington Zone 19", "Operator" => "VERIZON VIRGINIA, INC."),
    "644" => array ( "Area" => "Virginia", "City" => "Engleside", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "614" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "613" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "610" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "608" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "607" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "590" => array ( "Area" => "Virginia", "City" => "Dale City", "Operator" => "ONVOY, LLC - VA"),
    "588" => array ( "Area" => "Virginia", "City" => "Engleside", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "586" => array ( "Area" => "Virginia", "City" => "Herndon", "Operator" => "ONVOY, LLC - VA"),
    "582" => array ( "Area" => "Virginia", "City" => "Lorton Metro", "Operator" => "ONVOY, LLC - VA"),
    "570" => array ( "Area" => "Virginia", "City" => "Washington Zone 17", "Operator" => "ONVOY, LLC - VA"),
    "566" => array ( "Area" => "Virginia", "City" => "Washington Zone 19", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - DC"),
    "558" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "557" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "553" => array ( "Area" => "Virginia", "City" => "Leesburg", "Operator" => "PAETEC COMMUNICATIONS OF VIRGINIA, LLC - VA"),
    "542" => array ( "Area" => "Virginia", "City" => "Dale City", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "537" => array ( "Area" => "Virginia", "City" => "Herndon", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "520" => array ( "Area" => "Virginia", "City" => "Herndon", "Operator" => "ONVOY, LLC - VA"),
    "515" => array ( "Area" => "Virginia", "City" => "Engleside", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "506" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "VERIZON VIRGINIA, INC."),
    "505" => array ( "Area" => "Virginia", "City" => "Washington Zone 17", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "501" => array ( "Area" => "Virginia", "City" => "Washington Zone 17", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "500" => array ( "Area" => "Virginia", "City" => "Herndon", "Operator" => "ONVOY, LLC - VA"),
    "484" => array ( "Area" => "Virginia", "City" => "Herndon", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "478" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "476" => array ( "Area" => "Virginia", "City" => "Washington Zone 19", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - DC"),
    "472" => array ( "Area" => "Virginia", "City" => "Washington Zone 17", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "471" => array ( "Area" => "Virginia", "City" => "Herndon", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "468" => array ( "Area" => "Virginia", "City" => "Washington Zone 17", "Operator" => "VERIZON VIRGINIA, INC."),
    "452" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "VERIZON VIRGINIA, INC."),
    "391" => array ( "Area" => "Virginia", "City" => "Washington Zone 19", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - DC"),
    "372" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "VERIZON VIRGINIA, INC."),
    "362" => array ( "Area" => "Virginia", "City" => "Herndon", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "329" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "315" => array ( "Area" => "Virginia", "City" => "Herndon", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "309" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "304" => array ( "Area" => "Virginia", "City" => "Herndon", "Operator" => "VERIZON VIRGINIA, INC."),
    "296" => array ( "Area" => "Virginia", "City" => "Washington Zone 17", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "294" => array ( "Area" => "Virginia", "City" => "Washington Zone 17", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "289" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "280" => array ( "Area" => "Virginia", "City" => "Washington Zone 19", "Operator" => "VERIZON VIRGINIA, INC."),
    "256" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "VERIZON VIRGINIA, INC."),
    "254" => array ( "Area" => "Virginia", "City" => "Triangle", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - VA"),
    "231" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "VERIZON VIRGINIA, INC."),
    "230" => array ( "Area" => "Virginia", "City" => "Washington Zone 17", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "204" => array ( "Area" => "Virginia", "City" => "Washington Zone 19", "Operator" => "VERIZON VIRGINIA, INC."),
    "200" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "ONVOY, LLC - VA")
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
