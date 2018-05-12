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
 * E.164 United States of America NDC 682 country hook
 */
framework_add_filter ( "e164_identify_NANPA_682", "e164_identify_NANPA_682");

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
function e164_identify_NANPA_682 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 682 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1682")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "774" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "772" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "760" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "716" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "715" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "583" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "540" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "414" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "406" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "405" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "403" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "402" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "401" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "397" => array ( "Area" => "Texas", "City" => "North Richland Hills", "Operator" => "ONVOY SPECTRUM, LLC"),
    "396" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "ONVOY SPECTRUM, LLC"),
    "395" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "394" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "392" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "391" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "389" => array ( "Area" => "Texas", "City" => "Granbury", "Operator" => "T-MOBILE USA, INC."),
    "388" => array ( "Area" => "Texas", "City" => "Roanoke", "Operator" => "SOUTHWESTERN BELL"),
    "387" => array ( "Area" => "Texas", "City" => "Granbury", "Operator" => "T-MOBILE USA, INC."),
    "381" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "377" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "376" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "374" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "373" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "372" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "359" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "358" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "355" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "346" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "340" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "335" => array ( "Area" => "Texas", "City" => "Grapevine", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "329" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "322" => array ( "Area" => "Texas", "City" => "Mansfield", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "315" => array ( "Area" => "Texas", "City" => "Euless", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "309" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "303" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "SOUTHWESTERN BELL"),
    "295" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - TX"),
    "287" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "283" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "278" => array ( "Area" => "Texas", "City" => "Euless", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "275" => array ( "Area" => "Texas", "City" => "Euless", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "272" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "264" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "261" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "256" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "242" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "SOUTHWESTERN BELL"),
    "236" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "SOUTHWESTERN BELL"),
    "234" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "230" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "221" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "METROPCS, INC."),
    "215" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "212" => array ( "Area" => "Texas", "City" => "Keller", "Operator" => "SOUTHWESTERN BELL TELEPHONE COMPANY - TX"),
    "204" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "ONVOY, LLC - TX")
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
