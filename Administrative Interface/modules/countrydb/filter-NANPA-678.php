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
 * E.164 United States of America NDC 678 country hook
 */
framework_add_filter ( "e164_identify_NANPA_678", "e164_identify_NANPA_678");

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
function e164_identify_NANPA_678 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 678 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1678")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "978" => array ( "Area" => "Georgia", "City" => "Gainesville", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "970" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "LEVEL 3 TELECOM OF GEORGIA, LP - GA"),
    "968" => array ( "Area" => "Georgia", "City" => "Adairsville", "Operator" => "ONVOY, LLC - GA"),
    "955" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "LEVEL 3 TELECOM OF GEORGIA, LP - GA"),
    "914" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "METROPCS, INC."),
    "868" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - GA"),
    "863" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "848" => array ( "Area" => "Georgia", "City" => "Adairsville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "790" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "789" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "METROPCS, INC."),
    "751" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "SPRINT SPECTRUM, L.P."),
    "703" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "LEVEL 3 TELECOM OF GEORGIA, LP - GA"),
    "670" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "663" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "METROPCS, INC."),
    "650" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "599" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "598" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "METROPCS, INC."),
    "550" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "LEVEL 3 TELECOM OF GEORGIA, LP - GA"),
    "544" => array ( "Area" => "Georgia", "City" => "Griffin", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "531" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "METROPCS, INC."),
    "499" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "METROPCS, INC."),
    "312" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "308" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "299" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - GA")
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
