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
 * E.164 United States of America NDC 336 country hook
 */
framework_add_filter ( "e164_identify_NANPA_336", "e164_identify_NANPA_336");

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
function e164_identify_NANPA_336 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 336 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1336")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "966" => array ( "Area" => "North Carolina", "City" => "Winston Salem", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NC"),
    "935" => array ( "Area" => "North Carolina", "City" => "Greensboro", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "900" => array ( "Area" => "North Carolina", "City" => "Greensboro", "Operator" => "ONVOY, LLC - NC"),
    "868" => array ( "Area" => "North Carolina", "City" => "Greensboro", "Operator" => "LEVEL 3 TELECOM OF NORTH CAROLINA, LP - NC"),
    "839" => array ( "Area" => "North Carolina", "City" => "Julian", "Operator" => "YMAX COMMUNICATIONS CORP. - NC"),
    "833" => array ( "Area" => "North Carolina", "City" => "Greensboro", "Operator" => "YMAX COMMUNICATIONS CORP. - NC"),
    "825" => array ( "Area" => "North Carolina", "City" => "Greensboro", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "772" => array ( "Area" => "North Carolina", "City" => "Greensboro", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "763" => array ( "Area" => "North Carolina", "City" => "Greensboro", "Operator" => "TIME WARNER CBL INFO SVC (NC) DBA TIME WARNER CBL"),
    "702" => array ( "Area" => "North Carolina", "City" => "Winston Salem", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "596" => array ( "Area" => "North Carolina", "City" => "Lexington", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NC"),
    "487" => array ( "Area" => "North Carolina", "City" => "Greensboro", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "440" => array ( "Area" => "North Carolina", "City" => "Burlington", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "203" => array ( "Area" => "North Carolina", "City" => "Greensboro", "Operator" => "ONVOY, LLC - NC")
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
