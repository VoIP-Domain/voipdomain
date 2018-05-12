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
 * E.164 United States of America NDC 980 country hook
 */
framework_add_filter ( "e164_identify_NANPA_980", "e164_identify_NANPA_980");

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
function e164_identify_NANPA_980 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 980 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1980")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "993" => array ( "Area" => "North Carolina", "City" => "Monroe", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - NC"),
    "834" => array ( "Area" => "North Carolina", "City" => "Gastonia", "Operator" => "WINDSTREAM COMMUNICATIONS, INC. - NC"),
    "683" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "579" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NC"),
    "523" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "488" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NC"),
    "487" => array ( "Area" => "North Carolina", "City" => "Shelby", "Operator" => "LEVEL 3 TELECOM OF NORTH CAROLINA, LP - NC"),
    "452" => array ( "Area" => "North Carolina", "City" => "Lincolnton", "Operator" => "ONVOY, LLC - NC"),
    "442" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "WINDSTREAM COMMUNICATIONS, INC. - NC"),
    "416" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NC"),
    "401" => array ( "Area" => "North Carolina", "City" => "Gastonia", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "388" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "387" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "386" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "384" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NC"),
    "371" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "361" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "359" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "WINDSTREAM COMMUNICATIONS, INC. - NC"),
    "344" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "343" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "314" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NC"),
    "302" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NC"),
    "299" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "TIME WARNER CBL INFO SVC (NC) DBA TIME WARNER CBL"),
    "249" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "240" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NC"),
    "237" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "TIME WARNER CBL INFO SVC (NC) DBA TIME WARNER CBL"),
    "232" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "227" => array ( "Area" => "North Carolina", "City" => "Locust", "Operator" => "PEERLESS NETWORK OF NORTH CAROLINA, LLC - NC"),
    "217" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "ONVOY, LLC - NC"),
    "214" => array ( "Area" => "North Carolina", "City" => "Charlotte", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NC"),
    "212" => array ( "Area" => "North Carolina", "City" => "Lincolnton", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL")
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
