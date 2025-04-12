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
 * E.164 United States of America NDC 703 country hook
 */
framework_add_filter ( "e164_identify_NANPA_703", "e164_identify_NANPA_703");

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
function e164_identify_NANPA_703 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 703 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1703")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "985" => array ( "Area" => "Virginia", "City" => "Triangle", "Operator" => "VERIZON SOUTH, INC. - VA (CONTEL)"),
    "983" => array ( "Area" => "Virginia", "City" => "Washington Zone 17", "Operator" => "LEVEL 3 TELECOM OF VIRGINIA, LLC - VA"),
    "977" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "ONVOY, LLC - VA"),
    "895" => array ( "Area" => "Virginia", "City" => "Manassas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "844" => array ( "Area" => "Virginia", "City" => "Dulles", "Operator" => "PEERLESS NETWORK OF VIRGINIA, LLC - VA"),
    "571" => array ( "Area" => "Virginia", "City" => "Washington Zone  8", "Operator" => "VERIZON VIRGINIA, INC."),
    "523" => array ( "Area" => "Virginia", "City" => "Dale City", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "374" => array ( "Area" => "Virginia", "City" => "Washington Zone 19", "Operator" => "VERIZON VIRGINIA, INC.")
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
