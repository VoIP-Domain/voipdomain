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
 * E.164 United States of America NDC 602 country hook
 */
framework_add_filter ( "e164_identify_NANPA_602", "e164_identify_NANPA_602");

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
function e164_identify_NANPA_602 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 602 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1602")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "969" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY SPECTRUM, LLC"),
    "962" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "960" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "933" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "COMCAST IP PHONE, LLC"),
    "932" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "922" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "902" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "898" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "894" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "887" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "880" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "878" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "876" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "874" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY SPECTRUM, LLC"),
    "858" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "856" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "854" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "846" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY SPECTRUM, LLC"),
    "844" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "839" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "834" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "830" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "827" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "816" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "815" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "806" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "784" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "756" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "753" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "PEERLESS NETWORK OF ARIZONA, LLC - AZ"),
    "706" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "666" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "PEERLESS NETWORK OF ARIZONA, LLC - AZ"),
    "655" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "COMCAST IP PHONE, LLC"),
    "610" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "584" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "560" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "539" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "YMAX COMMUNICATIONS CORP. - AZ"),
    "496" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "473" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "METROPCS, INC."),
    "428" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "394" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "362" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "PEERLESS NETWORK OF ARIZONA, LLC - AZ"),
    "342" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "303" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "SPRINT SPECTRUM, L.P.")
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
