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
 * E.164 United States of America NDC 443 country hook
 */
framework_add_filter ( "e164_identify_NANPA_443", "e164_identify_NANPA_443");

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
function e164_identify_NANPA_443 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 443 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1443")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "982" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "YMAX COMMUNICATIONS CORP. - MD"),
    "970" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "954" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "PAETEC COMMUNICATIONS, INC. - MD"),
    "935" => array ( "Area" => "Maryland", "City" => "Towson", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "932" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "916" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "915" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "913" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "900" => array ( "Area" => "Maryland", "City" => "Towson", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "894" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "887" => array ( "Area" => "Maryland", "City" => "Catonsville", "Operator" => "CAVALIER TELEPHONE MID-ATLANTIC, LLC - MD"),
    "882" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "876" => array ( "Area" => "Maryland", "City" => "Bel Air", "Operator" => "SPRINT SPECTRUM, L.P."),
    "862" => array ( "Area" => "Maryland", "City" => "Towson", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "861" => array ( "Area" => "Maryland", "City" => "Aberdeen", "Operator" => "VERIZON MARYLAND, INC."),
    "809" => array ( "Area" => "Maryland", "City" => "Towson", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - MD"),
    "806" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "752" => array ( "Area" => "Maryland", "City" => "Bel Air", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "726" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "723" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "680" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "654" => array ( "Area" => "Maryland", "City" => "Waterloo 236", "Operator" => "VERIZON MARYLAND, INC."),
    "635" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "634" => array ( "Area" => "Maryland", "City" => "Waterloo 236", "Operator" => "VERIZON MARYLAND, INC."),
    "633" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "617" => array ( "Area" => "Maryland", "City" => "Fallston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "580" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "571" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "560" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "531" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "525" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "500" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "473" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "469" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "468" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "467" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "447" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "444" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "VERIZON MARYLAND, INC."),
    "435" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "401" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD"),
    "395" => array ( "Area" => "Maryland", "City" => "Aberdeen", "Operator" => "VERIZON MARYLAND, INC."),
    "314" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "284" => array ( "Area" => "Maryland", "City" => "Waterloo 238", "Operator" => "CORE COMMUNICATIONS, INC.- MD"),
    "240" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "208" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "SPRINT SPECTRUM, L.P."),
    "202" => array ( "Area" => "Maryland", "City" => "Baltimore", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MD")
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
