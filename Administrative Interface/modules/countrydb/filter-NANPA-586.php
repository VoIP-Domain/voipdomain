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
 * E.164 United States of America NDC 586 country hook
 */
framework_add_filter ( "e164_identify_NANPA_586", "e164_identify_NANPA_586");

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
function e164_identify_NANPA_586 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 586 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1586")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "985" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "955" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "922" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "885" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "881" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "848" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "845" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "835" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "828" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "818" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "787" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "767" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "748" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "735" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "733" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "OMNIPOINT COMMUNICATIONS MIDWEST OPERATIONS, LLC"),
    "728" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "715" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "695" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "689" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "683" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "659" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "641" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "638" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "629" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "628" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "622" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "606" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "602" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "599" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "559" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "550" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "545" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "539" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "527" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "515" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "514" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "505" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "499" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "494" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "487" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "483" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "475" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "449" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "448" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "444" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "433" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "429" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "428" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "424" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "414" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "399" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "396" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "395" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "392" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "389" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "388" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "386" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "385" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "377" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "375" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "357" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "348" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "342" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "338" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "332" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "327" => array ( "Area" => "Michigan", "City" => "Roseville", "Operator" => "ONVOY, LLCV - MI"),
    "324" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "320" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "312" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "305" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "289" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "288" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "282" => array ( "Area" => "Michigan", "City" => "Center Line", "Operator" => "MICHIGAN BELL TEL CO"),
    "266" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "259" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "253" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "249" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "245" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "240" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "236" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "235" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "234" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "233" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "230" => array ( "Area" => "Michigan", "City" => "Warren", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "227" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "224" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "OMNIPOINT COMMUNICATIONS MIDWEST OPERATIONS, LLC"),
    "223" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "220" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "208" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "205" => array ( "Area" => "Michigan", "City" => "Mount Clemens", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI")
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
