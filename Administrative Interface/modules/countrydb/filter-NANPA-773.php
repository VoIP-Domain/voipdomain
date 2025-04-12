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
 * E.164 United States of America NDC 773 country hook
 */
framework_add_filter ( "e164_identify_NANPA_773", "e164_identify_NANPA_773");

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
function e164_identify_NANPA_773 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 773 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1773")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "996" => array ( "Area" => "Illinois", "City" => "Chicago Zone  8", "Operator" => "T-MOBILE USA, INC."),
    "986" => array ( "Area" => "Illinois", "City" => "Chicago Zone  8", "Operator" => "T-MOBILE USA, INC."),
    "971" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "970" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "964" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "954" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "949" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "SPRINT SPECTRUM, L.P."),
    "939" => array ( "Area" => "Illinois", "City" => "Chicago Zone  8", "Operator" => "T-MOBILE USA, INC."),
    "885" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "SPRINT SPECTRUM, L.P."),
    "861" => array ( "Area" => "Illinois", "City" => "Chicago Zone  4", "Operator" => "FIRST COMMUNICATIONS, LLC - IL"),
    "839" => array ( "Area" => "Illinois", "City" => "Chicago Zone  4", "Operator" => "ONVOY, LLC"),
    "827" => array ( "Area" => "Illinois", "City" => "Chicago Zone  6", "Operator" => "T-MOBILE USA, INC."),
    "812" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "746" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "SPRINT SPECTRUM, L.P."),
    "712" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "709" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "708" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "707" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "706" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IL"),
    "705" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "FIRST COMMUNICATIONS, LLC - IL"),
    "703" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "692" => array ( "Area" => "Illinois", "City" => "Chicago Zone  4", "Operator" => "ONVOY, LLC"),
    "691" => array ( "Area" => "Illinois", "City" => "Chicago Zone  8", "Operator" => "T-MOBILE USA, INC."),
    "682" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IL"),
    "679" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "678" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "SPRINT SPECTRUM, L.P."),
    "674" => array ( "Area" => "Illinois", "City" => "Chicago Zone  6", "Operator" => "ILLINOIS BELL TEL CO"),
    "656" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "630" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "606" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "SPRINT SPECTRUM, L.P."),
    "605" => array ( "Area" => "Illinois", "City" => "Chicago Zone  2", "Operator" => "FIRST COMMUNICATIONS, LLC - IL"),
    "603" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "600" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "574" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "571" => array ( "Area" => "Illinois", "City" => "Chicago Zone  7", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IL"),
    "559" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "SPRINT SPECTRUM, L.P."),
    "543" => array ( "Area" => "Illinois", "City" => "Chicago Zone  4", "Operator" => "T-MOBILE USA, INC."),
    "441" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "440" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "SPRINT SPECTRUM, L.P."),
    "431" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "397" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "387" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "352" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "ONVOY, LLC"),
    "318" => array ( "Area" => "Illinois", "City" => "Chicago Zone  3", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - IL"),
    "240" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "T-MOBILE USA, INC."),
    "234" => array ( "Area" => "Illinois", "City" => "Chicago Zone  6", "Operator" => "BANDWIDTH.COM CLEC, LLC - IL"),
    "232" => array ( "Area" => "Illinois", "City" => "Chicago Zone 10", "Operator" => "ONVOY, LLC")
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
