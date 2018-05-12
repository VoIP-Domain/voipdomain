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
 * E.164 United States of America NDC 561 country hook
 */
framework_add_filter ( "e164_identify_NANPA_561", "e164_identify_NANPA_561");

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
function e164_identify_NANPA_561 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 561 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1561")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "917" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "897" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "887" => array ( "Area" => "Florida", "City" => "Delray Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "884" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "875" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "871" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "858" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "ONVOY, LLC - FL"),
    "857" => array ( "Area" => "Florida", "City" => "Delray Beach", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "834" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "ONVOY, LLC - FL"),
    "823" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "ONVOY, LLC - FL"),
    "817" => array ( "Area" => "Florida", "City" => "Delray Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "782" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "ONVOY, LLC - FL"),
    "780" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "ONVOY SPECTRUM, LLC"),
    "724" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "698" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "696" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "690" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "679" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "669" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "664" => array ( "Area" => "Florida", "City" => "Delray Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "663" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "648" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "647" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "646" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "643" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "607" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "597" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "595" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "592" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "591" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "590" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "587" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "583" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "579" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "574" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "METROPCS, INC."),
    "561" => array ( "Area" => "Florida", "City" => "Boynton Beach", "Operator" => "ONVOY, LLC - FL"),
    "556" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "ONVOY, LLC - FL"),
    "548" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "546" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "545" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "539" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "538" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "528" => array ( "Area" => "Florida", "City" => "Delray Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "527" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "525" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "524" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "522" => array ( "Area" => "Florida", "City" => "Delray Beach", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "521" => array ( "Area" => "Florida", "City" => "Boynton Beach", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "520" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "LEVEL 3 TELECOM OF FLORIDA, LP - FL"),
    "519" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "500" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "PAETEC COMMUNICATIONS, INC. - FL"),
    "497" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "492" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "490" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "481" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "480" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "466" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "461" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "ONVOY, LLC - FL"),
    "460" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "428" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "426" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "399" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "398" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "397" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "384" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "382" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "380" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "321" => array ( "Area" => "Florida", "City" => "Boca Raton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "298" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "294" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "METROPCS, INC."),
    "263" => array ( "Area" => "Florida", "City" => "Jupiter", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "220" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "ONVOY, LLC - FL"),
    "219" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "PAETEC COMMUNICATIONS, INC. - FL"),
    "201" => array ( "Area" => "Florida", "City" => "West Palm Beach", "Operator" => "METROPCS, INC.")
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
