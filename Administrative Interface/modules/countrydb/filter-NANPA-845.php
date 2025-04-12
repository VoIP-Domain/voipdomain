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
 * E.164 United States of America NDC 845 country hook
 */
framework_add_filter ( "e164_identify_NANPA_845", "e164_identify_NANPA_845");

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
function e164_identify_NANPA_845 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 845 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1845")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "978" => array ( "Area" => "New York", "City" => "Middletown", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "912" => array ( "Area" => "New York", "City" => "Pleasant Valley (Dutchess)", "Operator" => "ONVOY, LLC- NY"),
    "866" => array ( "Area" => "New York", "City" => "Monticello", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "845" => array ( "Area" => "New York", "City" => "Poughkeepsie", "Operator" => "REGIONAL TELEPHONE CORPORATION - NY"),
    "798" => array ( "Area" => "New York", "City" => "Monticello", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "761" => array ( "Area" => "New York", "City" => "Piermont", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - NY"),
    "706" => array ( "Area" => "New York", "City" => "Kingston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "696" => array ( "Area" => "New York", "City" => "Amenia", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "649" => array ( "Area" => "New York", "City" => "Middletown", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "631" => array ( "Area" => "New York", "City" => "Monticello", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "596" => array ( "Area" => "New York", "City" => "New City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "587" => array ( "Area" => "New York", "City" => "New City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "550" => array ( "Area" => "New York", "City" => "Woodstock", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "542" => array ( "Area" => "New York", "City" => "Newburgh", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "541" => array ( "Area" => "New York", "City" => "Newburgh", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "505" => array ( "Area" => "New York", "City" => "Poughkeepsie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "464" => array ( "Area" => "New York", "City" => "Poughkeepsie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "453" => array ( "Area" => "New York", "City" => "Poughkeepsie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "399" => array ( "Area" => "New York", "City" => "Kingston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "389" => array ( "Area" => "New York", "City" => "Kingston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "333" => array ( "Area" => "New York", "City" => "Middletown", "Operator" => "CITIZENS TELECOMM CO OF NY DBA FRONTIER COMM OF NY"),
    "325" => array ( "Area" => "New York", "City" => "Monroe", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "301" => array ( "Area" => "New York", "City" => "Suffern", "Operator" => "PEERLESS NETWORK OF NEW YORK, LLC - NY"),
    "283" => array ( "Area" => "New York", "City" => "Middletown", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC")
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
