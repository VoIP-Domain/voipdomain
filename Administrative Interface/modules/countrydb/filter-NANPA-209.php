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
 * E.164 United States of America NDC 209 country hook
 */
framework_add_filter ( "e164_identify_NANPA_209", "e164_identify_NANPA_209");

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
function e164_identify_NANPA_209 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 209 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1209")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "971" => array ( "Area" => "California", "City" => "Modesto", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "945" => array ( "Area" => "California", "City" => "Stockton", "Operator" => "UTILITY TELECOM GROUP, LLC - CA"),
    "935" => array ( "Area" => "California", "City" => "Dos Palos", "Operator" => "ONVOY, LLC - CA"),
    "889" => array ( "Area" => "California", "City" => "Stockton", "Operator" => "T-MOBILE USA, INC."),
    "760" => array ( "Area" => "California", "City" => "Angels Camp", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "750" => array ( "Area" => "California", "City" => "Modesto", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "746" => array ( "Area" => "California", "City" => "Merced", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "738" => array ( "Area" => "California", "City" => "Angels Camp", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "716" => array ( "Area" => "California", "City" => "Angels Camp", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "705" => array ( "Area" => "California", "City" => "Manteca", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "698" => array ( "Area" => "California", "City" => "Manteca", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "686" => array ( "Area" => "California", "City" => "Angels Camp", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "562" => array ( "Area" => "California", "City" => "Angels Camp", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "506" => array ( "Area" => "California", "City" => "Merced", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "415" => array ( "Area" => "California", "City" => "Angels Camp", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "359" => array ( "Area" => "California", "City" => "Merced", "Operator" => "PACIFIC BELL"),
    "299" => array ( "Area" => "California", "City" => "Stockton", "Operator" => "ONVOY, LLC - CA"),
    "228" => array ( "Area" => "California", "City" => "Merced", "Operator" => "PACIFIC BELL"),
    "209" => array ( "Area" => "California", "City" => "Modesto", "Operator" => "ONVOY, LLC - CA")
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
