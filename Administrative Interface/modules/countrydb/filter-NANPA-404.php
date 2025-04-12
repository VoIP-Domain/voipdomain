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
 * E.164 United States of America NDC 404 country hook
 */
framework_add_filter ( "e164_identify_NANPA_404", "e164_identify_NANPA_404");

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
function e164_identify_NANPA_404 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 404 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1404")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "999" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "989" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "988" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "985" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "980" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "971" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "967" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "940" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "938" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "922" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "919" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BANDWIDTH.COM CLEC, LLC - GA"),
    "913" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BANDWIDTH.COM CLEC, LLC - GA"),
    "904" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "903" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "896" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "882" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "868" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "859" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "858" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "852" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "821" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "820" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "807" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "782" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "777" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "772" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "747" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "740" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "738" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "737" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "726" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "710" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "670" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "666" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BANDWIDTH.COM CLEC, LLC - GA"),
    "655" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "623" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "621" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "SPRINT SPECTRUM, L.P."),
    "615" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "613" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "612" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "599" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "SPRINT SPECTRUM, L.P."),
    "595" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "568" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "560" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - GA"),
    "546" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "544" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "533" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY SPECTRUM, LLC"),
    "517" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY SPECTRUM, LLC"),
    "466" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "462" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "YMAX COMMUNICATIONS CORP. - GA"),
    "453" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "METROPCS, INC."),
    "450" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "438" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "METROPCS, INC."),
    "430" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "416" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "413" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "US LEC OF GEORGIA, INC."),
    "399" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "METROPCS, INC."),
    "393" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "LEVEL 3 TELECOM OF GEORGIA, LP - GA"),
    "342" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY SPECTRUM, LLC"),
    "337" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "SPRINT SPECTRUM, L.P."),
    "200" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC.")
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
