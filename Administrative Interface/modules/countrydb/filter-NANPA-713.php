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
 * E.164 United States of America NDC 713 country hook
 */
framework_add_filter ( "e164_identify_NANPA_713", "e164_identify_NANPA_713");

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
function e164_identify_NANPA_713 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 713 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1713")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "998" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "SPRINT SPECTRUM, L.P."),
    "931" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "YMAX COMMUNICATIONS CORP. - TX"),
    "925" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "ONVOY SPECTRUM, LLC"),
    "879" => array ( "Area" => "Texas", "City" => "Westfield", "Operator" => "SOUTHWESTERN BELL"),
    "848" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "832" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "ONVOY, LLC - TX"),
    "810" => array ( "Area" => "Texas", "City" => "Tomball", "Operator" => "ONVOY SPECTRUM, LLC"),
    "804" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "ONVOY, LLC - TX"),
    "583" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "556" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "531" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "SOUTHWESTERN BELL"),
    "489" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "486" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "424" => array ( "Area" => "Texas", "City" => "Baytown", "Operator" => "ONVOY, LLC - TX"),
    "399" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "ONVOY SPECTRUM, LLC"),
    "382" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "381" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "379" => array ( "Area" => "Texas", "City" => "Apollo", "Operator" => "ONVOY, LLC - TX"),
    "372" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "370" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "YMAX COMMUNICATIONS CORP. - TX"),
    "347" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "ONVOY, LLC - TX"),
    "323" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - TX"),
    "322" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "274" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "251" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL")
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
