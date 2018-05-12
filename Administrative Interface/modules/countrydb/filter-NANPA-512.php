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
 * E.164 United States of America NDC 512 country hook
 */
framework_add_filter ( "e164_identify_NANPA_512", "e164_identify_NANPA_512");

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
function e164_identify_NANPA_512 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 512 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1512")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "998" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "T-MOBILE USA, INC."),
    "983" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "946" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "945" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "939" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "915" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "909" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "T-MOBILE USA, INC."),
    "905" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "T-MOBILE USA, INC."),
    "903" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "T-MOBILE USA, INC."),
    "902" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "889" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "ONVOY SPECTRUM, LLC"),
    "885" => array ( "Area" => "Texas", "City" => "Lockhart", "Operator" => "ONVOY, LLC - TX"),
    "882" => array ( "Area" => "Texas", "City" => "Georgetown", "Operator" => "ONVOY, LLC - TX"),
    "881" => array ( "Area" => "Texas", "City" => "Leander", "Operator" => "ONVOY, LLC - TX"),
    "880" => array ( "Area" => "Texas", "City" => "Bastrop", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "854" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "SOUTHWESTERN BELL"),
    "839" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "820" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "816" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "810" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "803" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "780" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "776" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "766" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "747" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "739" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "727" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "710" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "709" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "SPRINT SPECTRUM, L.P."),
    "705" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "702" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "701" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "679" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "METROPCS, INC."),
    "664" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "654" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "SOUTHWESTERN BELL"),
    "643" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "631" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "ONVOY, LLC - TX"),
    "574" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "570" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "552" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "550" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "544" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "537" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "526" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "522" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "513" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "509" => array ( "Area" => "Texas", "City" => "Georgetown", "Operator" => "FRONTIER SOUTHWEST INC DBA FRONTIER COMM OF TEXAS"),
    "503" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "ONVOY, LLC - TX"),
    "387" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "319" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "317" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "313" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "230" => array ( "Area" => "Texas", "City" => "Austin", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX")
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
