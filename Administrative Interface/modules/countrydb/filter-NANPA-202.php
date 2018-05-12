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
 * E.164 United States of America NDC 202 country hook
 */
framework_add_filter ( "e164_identify_NANPA_202", "e164_identify_NANPA_202");

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
function e164_identify_NANPA_202 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 202 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1202")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "998" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "995" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY SPECTRUM, LLC"),
    "992" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "990" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "987" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "BANDWIDTH.COM CLEC, LLC - DC"),
    "979" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "978" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "972" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "970" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "953" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "952" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "941" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "934" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "932" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "924" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "915" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "881" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "858" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "815" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "814" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "796" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "BANDWIDTH.COM CLEC, LLC - DC"),
    "759" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - DC"),
    "731" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "718" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "710" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "705" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "704" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "703" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY SPECTRUM, LLC"),
    "702" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "681" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "BANDWIDTH.COM CLEC, LLC - DC"),
    "670" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "BANDWIDTH.COM CLEC, LLC - DC"),
    "656" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "BANDWIDTH.COM CLEC, LLC - DC"),
    "648" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "VERIZON WASHINGTON DC, INC."),
    "643" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "BANDWIDTH.COM CLEC, LLC - DC"),
    "630" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "BANDWIDTH.COM CLEC, LLC - DC"),
    "615" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "603" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "599" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "BANDWIDTH.COM CLEC, LLC - DC"),
    "593" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "VERIZON WASHINGTON DC, INC."),
    "578" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "410" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "402" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "VERIZON WASHINGTON DC, INC."),
    "394" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "335" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "325" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "VERIZON WASHINGTON DC, INC."),
    "308" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - DC"),
    "301" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "284" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "VERIZON WASHINGTON DC, INC."),
    "241" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "YMAX COMMUNICATIONS CORP. - DC"),
    "217" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "ONVOY, LLC - DC"),
    "201" => array ( "Area" => "District of Columbia", "City" => "Washington Zone 1", "Operator" => "VERIZON WASHINGTON DC, INC.")
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
