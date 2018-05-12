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
 * E.164 United States of America NDC 615 country hook
 */
framework_add_filter ( "e164_identify_NANPA_615", "e164_identify_NANPA_615");

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
function e164_identify_NANPA_615 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 615 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1615")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "980" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TN"),
    "979" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "968" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "POWERTEL NASHVILLE LICENSES, INC."),
    "966" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "WINDSTREAM NUVOX, INC."),
    "961" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "946" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "935" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "POWERTEL NASHVILLE LICENSES, INC."),
    "934" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "918" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "POWERTEL NASHVILLE LICENSES, INC."),
    "917" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "906" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "879" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "877" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "POWERTEL NASHVILLE LICENSES, INC."),
    "875" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTH CENTRAL BELL TEL"),
    "839" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "POWERTEL NASHVILLE LICENSES, INC."),
    "827" => array ( "Area" => "Tennessee", "City" => "Hendersonville", "Operator" => "ONVOY, LLC - TN"),
    "825" => array ( "Area" => "Tennessee", "City" => "Smyrna", "Operator" => "ONVOY, LLC - TN"),
    "798" => array ( "Area" => "Tennessee", "City" => "Franklin", "Operator" => "XO TENNESSEE, INC."),
    "785" => array ( "Area" => "Tennessee", "City" => "Murfreesboro", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "753" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "725" => array ( "Area" => "Tennessee", "City" => "Franklin", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTH CENTRAL BELL TEL"),
    "718" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TN"),
    "710" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "POWERTEL NASHVILLE LICENSES, INC."),
    "705" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "POWERTEL NASHVILLE LICENSES, INC."),
    "704" => array ( "Area" => "Tennessee", "City" => "Old Hickory", "Operator" => "ONVOY, LLC - TN"),
    "694" => array ( "Area" => "Tennessee", "City" => "Goodlettsville", "Operator" => "ONVOY, LLC - TN"),
    "689" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "SPRINT SPECTRUM, L.P."),
    "684" => array ( "Area" => "Tennessee", "City" => "Smithville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "681" => array ( "Area" => "Tennessee", "City" => "Goodlettsville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "676" => array ( "Area" => "Tennessee", "City" => "Lavergne", "Operator" => "ONVOY, LLC - TN"),
    "670" => array ( "Area" => "Tennessee", "City" => "Lafayette", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "669" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "BANDWIDTH.COM CLEC, LLC - TN"),
    "658" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "XO TENNESSEE, INC."),
    "638" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "POWERTEL NASHVILLE LICENSES, INC."),
    "634" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "XO TENNESSEE, INC."),
    "626" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "602" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "POWERTEL NASHVILLE LICENSES, INC."),
    "530" => array ( "Area" => "Tennessee", "City" => "Old Hickory", "Operator" => "YMAX COMMUNICATIONS CORP. - TN"),
    "487" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "413" => array ( "Area" => "Tennessee", "City" => "Murfreesboro", "Operator" => "ONVOY, LLC - TN"),
    "388" => array ( "Area" => "Tennessee", "City" => "Lafayette", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "381" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - TN"),
    "339" => array ( "Area" => "Tennessee", "City" => "Nashville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TN"),
    "328" => array ( "Area" => "Tennessee", "City" => "Gallatin", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTH CENTRAL BELL TEL")
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
