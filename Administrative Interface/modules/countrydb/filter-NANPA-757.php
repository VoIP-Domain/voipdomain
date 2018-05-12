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
 * E.164 United States of America NDC 757 country hook
 */
framework_add_filter ( "e164_identify_NANPA_757", "e164_identify_NANPA_757");

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
function e164_identify_NANPA_757 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 757 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1757")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "984" => array ( "Area" => "Virginia", "City" => "Williamsburg", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "983" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "970" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "VONAGE AMERICA LLC"),
    "940" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "932" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "BANDWIDTH.COM CLEC, LLC - VA"),
    "862" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CAVALIER TELEPHONE, LLC - VA"),
    "834" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "ONVOY, LLC - VA"),
    "817" => array ( "Area" => "Virginia", "City" => "Newport News Zone 3", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "795" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "783" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "ONVOY SPECTRUM, LLC"),
    "779" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "SPRINT SPECTRUM, L.P."),
    "773" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "771" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "761" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "759" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "747" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CAVALIER TELEPHONE, LLC - VA"),
    "730" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "705" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "674" => array ( "Area" => "Virginia", "City" => "Newport News Zone 1", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "633" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - VA"),
    "520" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 1", "Operator" => "ONVOY, LLC - VA"),
    "507" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "501" => array ( "Area" => "Virginia", "City" => "Newport News Zone 2", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "458" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CAVALIER TELEPHONE, LLC - VA"),
    "429" => array ( "Area" => "Virginia", "City" => "Temperanceville", "Operator" => "CORETEL VIRGINIA, LLC - VA"),
    "400" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CAVALIER TELEPHONE, LLC - VA"),
    "388" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "385" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "VERIZON SOUTH, INC. - VA (CONTEL)"),
    "341" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "CAVALIER TELEPHONE, LLC - VA"),
    "281" => array ( "Area" => "Virginia", "City" => "Newport News Zone 1", "Operator" => "OMNIPOINT COMMUNICATIONS CAP OPERATIONS, LLC"),
    "261" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 4", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "252" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "COX VIRGINIA TELCOM, INC. - VA"),
    "212" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 6", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - VA"),
    "205" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 6", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - VA"),
    "202" => array ( "Area" => "Virginia", "City" => "Norfolk Zone 2", "Operator" => "SPRINT SPECTRUM, L.P.")
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
