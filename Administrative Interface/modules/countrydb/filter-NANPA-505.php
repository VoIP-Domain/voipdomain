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
 * E.164 United States of America NDC 505 country hook
 */
framework_add_filter ( "e164_identify_NANPA_505", "e164_identify_NANPA_505");

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
function e164_identify_NANPA_505 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 505 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1505")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "918" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "917" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NM"),
    "635" => array ( "Area" => "New Mexico", "City" => "Farmington", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NM"),
    "633" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "BANDWIDTH.COM CLEC, LLC - NM"),
    "606" => array ( "Area" => "New Mexico", "City" => "Los Alamos", "Operator" => "QWEST CORPORATION"),
    "549" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NM"),
    "541" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "536" => array ( "Area" => "New Mexico", "City" => "Farmington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "534" => array ( "Area" => "New Mexico", "City" => "Aztec", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NM"),
    "528" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "ONVOY SPECTRUM, LLC"),
    "526" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NM"),
    "525" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NM"),
    "505" => array ( "Area" => "New Mexico", "City" => "Santa Fe", "Operator" => "ONVOY, LLC - NM"),
    "497" => array ( "Area" => "New Mexico", "City" => "Aztec", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NM"),
    "493" => array ( "Area" => "New Mexico", "City" => "Farmington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "491" => array ( "Area" => "New Mexico", "City" => "Farmington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "445" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "BANDWIDTH.COM CLEC, LLC - NM"),
    "443" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "CENTURYLINK COMMUNICATIONS, LLC"),
    "442" => array ( "Area" => "New Mexico", "City" => "Farmington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "413" => array ( "Area" => "New Mexico", "City" => "Gallup", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NM"),
    "405" => array ( "Area" => "New Mexico", "City" => "Ojo Caliente", "Operator" => "ONVOY, LLC - NM"),
    "383" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "QWEST CORPORATION"),
    "382" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NM"),
    "374" => array ( "Area" => "New Mexico", "City" => "Farmington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "283" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "QWEST CORPORATION"),
    "253" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "LEVEL 3 TELECOM OF NEW MEXICO, LLC - NM"),
    "226" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "BANDWIDTH.COM CLEC, LLC - NM"),
    "207" => array ( "Area" => "New Mexico", "City" => "Albuquerque", "Operator" => "ONVOY, LLC - NM")
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
