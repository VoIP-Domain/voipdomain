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
 * E.164 United States of America NDC 817 country hook
 */
framework_add_filter ( "e164_identify_NANPA_817", "e164_identify_NANPA_817");

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
function e164_identify_NANPA_817 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 817 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1817")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "995" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "977" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "965" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "960" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "SOUTHWESTERN BELL"),
    "948" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "941" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "SPRINT SPECTRUM, L.P."),
    "908" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "902" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "METROPCS, INC."),
    "897" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "883" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "T-MOBILE USA, INC."),
    "879" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "848" => array ( "Area" => "Texas", "City" => "Euless", "Operator" => "SOUTHWESTERN BELL"),
    "830" => array ( "Area" => "Texas", "City" => "North Richland Hills", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "815" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "SOUTHWESTERN BELL"),
    "814" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "SOUTHWESTERN BELL"),
    "802" => array ( "Area" => "Texas", "City" => "Roanoke", "Operator" => "SOUTHWESTERN BELL"),
    "773" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "739" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "733" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "718" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "702" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "SOUTHWESTERN BELL"),
    "699" => array ( "Area" => "Texas", "City" => "Roanoke", "Operator" => "SOUTHWESTERN BELL"),
    "682" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "647" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "646" => array ( "Area" => "Texas", "City" => "Atlas", "Operator" => "ONVOY, LLC - TX"),
    "603" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "600" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "584" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "582" => array ( "Area" => "Texas", "City" => "Atlas", "Operator" => "XO TEXAS, INC."),
    "549" => array ( "Area" => "Texas", "City" => "Arlington", "Operator" => "XO TEXAS, INC."),
    "400" => array ( "Area" => "Texas", "City" => "Grapevine", "Operator" => "YMAX COMMUNICATIONS CORP. - TX"),
    "388" => array ( "Area" => "Texas", "City" => "Grapevine", "Operator" => "SOUTHWESTERN BELL TELEPHONE COMPANY - TX"),
    "384" => array ( "Area" => "Texas", "City" => "Fort Worth", "Operator" => "ONVOY SPECTRUM, LLC"),
    "372" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "324" => array ( "Area" => "Texas", "City" => "Euless", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "240" => array ( "Area" => "Texas", "City" => "Cleburne", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "201" => array ( "Area" => "Texas", "City" => "Glendale", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL")
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
