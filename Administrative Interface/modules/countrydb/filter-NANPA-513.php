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
 * E.164 United States of America NDC 513 country hook
 */
framework_add_filter ( "e164_identify_NANPA_513", "e164_identify_NANPA_513");

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
function e164_identify_NANPA_513 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 513 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1513")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "974" => array ( "Area" => "Ohio", "City" => "Middletown", "Operator" => "OHIO BELL TEL CO"),
    "938" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "ONVOY, LLC - OH"),
    "937" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "ONVOY, LLC - OH"),
    "912" => array ( "Area" => "Ohio", "City" => "Hamilton", "Operator" => "ONVOY, LLC - OH"),
    "865" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CINCINNATI BELL"),
    "862" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CINCINNATI BELL"),
    "854" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "ONVOY, LLC - OH"),
    "846" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "840" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "ONVOY, LLC - OH"),
    "814" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "803" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CINCINNATI BELL"),
    "767" => array ( "Area" => "Ohio", "City" => "Newtonsville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "764" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "T-MOBILE USA, INC."),
    "748" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "647" => array ( "Area" => "Ohio", "City" => "Middletown", "Operator" => "ONVOY, LLC - OH"),
    "635" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "628" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "581" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "517" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CINCINNATI BELL"),
    "516" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "513" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "ONVOY, LLC - OH"),
    "501" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "T-MOBILE USA, INC."),
    "500" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "495" => array ( "Area" => "Ohio", "City" => "Clermont", "Operator" => "CINCINNATI BELL"),
    "485" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "446" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "441" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "440" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "ONVOY, LLC - OH"),
    "431" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "415" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "391" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "344" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "302" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "T-MOBILE USA, INC."),
    "240" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "222" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OH"),
    "215" => array ( "Area" => "Ohio", "City" => "Cincinnati", "Operator" => "CINCINNATI BELL")
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
