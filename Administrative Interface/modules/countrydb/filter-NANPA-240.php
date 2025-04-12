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
 * E.164 United States of America NDC 240 country hook
 */
framework_add_filter ( "e164_identify_NANPA_240", "e164_identify_NANPA_240");

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
function e164_identify_NANPA_240 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 240 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1240")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "964" => array ( "Area" => "Maryland", "City" => "Cumberland", "Operator" => "VERIZON MARYLAND, INC."),
    "962" => array ( "Area" => "Maryland", "City" => "Laurel 236", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "954" => array ( "Area" => "Maryland", "City" => "Hagerstown", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MD"),
    "953" => array ( "Area" => "Maryland", "City" => "Hagerstown", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MD"),
    "952" => array ( "Area" => "Maryland", "City" => "Hagerstown", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MD"),
    "951" => array ( "Area" => "Maryland", "City" => "Hagerstown", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MD"),
    "947" => array ( "Area" => "Maryland", "City" => "Myersville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "919" => array ( "Area" => "Maryland", "City" => "Ashton 236", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "904" => array ( "Area" => "Maryland", "City" => "Laurel 238", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "900" => array ( "Area" => "Maryland", "City" => "Washington Zone  4", "Operator" => "ONVOY, LLC - MD"),
    "856" => array ( "Area" => "Maryland", "City" => "Laurel 238", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "826" => array ( "Area" => "Maryland", "City" => "Washington Zone 10", "Operator" => "VERIZON MARYLAND, INC."),
    "756" => array ( "Area" => "Maryland", "City" => "Washington Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "740" => array ( "Area" => "Maryland", "City" => "Washington Zone 10", "Operator" => "ATLANTECH ONLINE, INC. - MD"),
    "729" => array ( "Area" => "Maryland", "City" => "Ashton 236", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "710" => array ( "Area" => "Maryland", "City" => "Frederick", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - MD"),
    "701" => array ( "Area" => "Maryland", "City" => "Washington Zone  3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "677" => array ( "Area" => "Maryland", "City" => "Washington Zone  5", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "640" => array ( "Area" => "Maryland", "City" => "Waldorf", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "613" => array ( "Area" => "Maryland", "City" => "Washington Zone  4", "Operator" => "US LEC OF MARYLAND, INC."),
    "612" => array ( "Area" => "Maryland", "City" => "Washington Zone  5", "Operator" => "LEVEL 3 TELECOM OF MARYLAND, LLC - MD"),
    "592" => array ( "Area" => "Maryland", "City" => "Washington Zone 13", "Operator" => "VERIZON MARYLAND, INC."),
    "579" => array ( "Area" => "Maryland", "City" => "Ashton 236", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "567" => array ( "Area" => "Maryland", "City" => "Washington Zone 10", "Operator" => "VERIZON MARYLAND, INC."),
    "562" => array ( "Area" => "Maryland", "City" => "Gaithersburg", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "550" => array ( "Area" => "Maryland", "City" => "Washington Zone 10", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "521" => array ( "Area" => "Maryland", "City" => "Laurel 236", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "517" => array ( "Area" => "Maryland", "City" => "Laurel 238", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "484" => array ( "Area" => "Maryland", "City" => "Laurel 236", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "480" => array ( "Area" => "Maryland", "City" => "Washington Zone  2", "Operator" => "SPRINT SPECTRUM, L.P."),
    "402" => array ( "Area" => "Maryland", "City" => "Washington Zone 13", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "370" => array ( "Area" => "Maryland", "City" => "Washington Zone  2", "Operator" => "SPRINT SPECTRUM, L.P."),
    "344" => array ( "Area" => "Maryland", "City" => "Frederick", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "286" => array ( "Area" => "Maryland", "City" => "Washington Zone  2", "Operator" => "SPRINT SPECTRUM, L.P."),
    "276" => array ( "Area" => "Maryland", "City" => "Washington Zone 10", "Operator" => "VERIZON MARYLAND, INC.")
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
