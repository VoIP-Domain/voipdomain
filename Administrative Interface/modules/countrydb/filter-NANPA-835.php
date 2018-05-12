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
 * E.164 United States of America NDC 835 country hook
 */
framework_add_filter ( "e164_identify_NANPA_835", "e164_identify_NANPA_835");

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
function e164_identify_NANPA_835 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 835 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1835")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "9999" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 22", "Operator" => "TERRA NOVA TELECOM INC."),
    "8889" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8888" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "TERRA NOVA TELECOM INC."),
    "8887" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8886" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8885" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8884" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8883" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8882" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8881" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "8880" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "7777" => array ( "Area" => "Pennsylvania", "City" => "Bernville", "Operator" => "TERRA NOVA TELECOM INC."),
    "2151" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NUSO, LLC"),
    "2146" => array ( "Area" => "Pennsylvania", "City" => "Northampton", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - PA"),
    "2145" => array ( "Area" => "Pennsylvania", "City" => "Northampton", "Operator" => "ONVOY, LLC - PA"),
    "2144" => array ( "Area" => "Pennsylvania", "City" => "Northampton", "Operator" => "ONVOY, LLC - PA"),
    "2137" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 14", "Operator" => "ONVOY, LLC - PA"),
    "2136" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 14", "Operator" => "ONVOY, LLC - PA"),
    "2135" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 14", "Operator" => "ONVOY, LLC - PA"),
    "2134" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 14", "Operator" => "ONVOY, LLC - PA"),
    "2129" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 13", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2109" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2108" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2107" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2106" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2105" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2104" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - PA"),
    "2099" => array ( "Area" => "Pennsylvania", "City" => "Easton", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2091" => array ( "Area" => "Pennsylvania", "City" => "Easton", "Operator" => "COMMIO, LLC"),
    "2090" => array ( "Area" => "Pennsylvania", "City" => "Easton", "Operator" => "COMMIO, LLC"),
    "2089" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2088" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2087" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2086" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2085" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 23", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2079" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 30", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2078" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 30", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2072" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 30", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2071" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 30", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2070" => array ( "Area" => "Pennsylvania", "City" => "Philadelphia Suburban Zone 30", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2064" => array ( "Area" => "Pennsylvania", "City" => "Atglen", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - PA"),
    "2059" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2058" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2057" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "2056" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "2055" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2054" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2053" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2052" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "COMMIO, LLC"),
    "2051" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "COMMIO, LLC"),
    "2050" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "COMMIO, LLC"),
    "2049" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2048" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2047" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2046" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2045" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2044" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2043" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2042" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "OMNIPOINT COMMUNICATIONS ENTERPRISES, LP"),
    "2041" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2040" => array ( "Area" => "Pennsylvania", "City" => "Allentown (Lehigh)", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "2039" => array ( "Area" => "Pennsylvania", "City" => "Bally", "Operator" => "COMCAST IP PHONE, LLC"),
    "2029" => array ( "Area" => "Pennsylvania", "City" => "Hamburg", "Operator" => "D&E/OMNIPOINT WIREL JOINT VENT LP DBA PCS ONE"),
    "2019" => array ( "Area" => "Pennsylvania", "City" => "Bethlehem", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - PA"),
    "2010" => array ( "Area" => "Pennsylvania", "City" => "Bethlehem", "Operator" => "BANDWIDTH.COM CLEC, LLC - PA"),
    "2001" => array ( "Area" => "Pennsylvania", "City" => "Lehighton", "Operator" => "NUSO, LLC")
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
