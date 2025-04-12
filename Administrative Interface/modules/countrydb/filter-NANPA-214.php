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
 * E.164 United States of America NDC 214 country hook
 */
framework_add_filter ( "e164_identify_NANPA_214", "e164_identify_NANPA_214");

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
function e164_identify_NANPA_214 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 214 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1214")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "996" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "970" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "940" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "934" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "933" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "930" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "918" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "910" => array ( "Area" => "Texas", "City" => "Dallas Fort Worth Airport", "Operator" => "ONVOY, LLC - TX"),
    "907" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "898" => array ( "Area" => "Texas", "City" => "Celina", "Operator" => "ONVOY SPECTRUM, LLC"),
    "894" => array ( "Area" => "Texas", "City" => "Lewisville", "Operator" => "ONVOY, LLC - TX"),
    "885" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "881" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "METROPCS, INC."),
    "862" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "846" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "836" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "T-MOBILE USA, INC."),
    "833" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "ONVOY, LLC - TX"),
    "807" => array ( "Area" => "Texas", "City" => "Irving", "Operator" => "ONVOY, LLC - TX"),
    "795" => array ( "Area" => "Texas", "City" => "Dallas Fort Worth Airport", "Operator" => "ONVOY SPECTRUM, LLC"),
    "791" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "METROPCS, INC."),
    "784" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "713" => array ( "Area" => "Texas", "City" => "Ennis", "Operator" => "T-MOBILE USA, INC."),
    "708" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "701" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "639" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "ONVOY SPECTRUM, LLC"),
    "633" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "608" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "602" => array ( "Area" => "Texas", "City" => "Cedar Hill", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "601" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "594" => array ( "Area" => "Texas", "City" => "Irving", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "490" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "487" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "ONVOY SPECTRUM, LLC"),
    "482" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "METROPCS, INC."),
    "481" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "XO TEXAS, INC."),
    "479" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "422" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "409" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "396" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "PEERLESS NETWORK OF TEXAS, LLC - TX"),
    "279" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "XO TEXAS, INC."),
    "230" => array ( "Area" => "Texas", "City" => "Waxahachie", "Operator" => "YMAX COMMUNICATIONS CORP. - TX"),
    "205" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL")
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
