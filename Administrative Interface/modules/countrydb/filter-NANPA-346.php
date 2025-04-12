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
 * E.164 United States of America NDC 346 country hook
 */
framework_add_filter ( "e164_identify_NANPA_346", "e164_identify_NANPA_346");

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
function e164_identify_NANPA_346 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 346 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1346")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "998" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "PEERLESS NETWORK OF TEXAS, LLC - TX"),
    "834" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "813" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "812" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "804" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "779" => array ( "Area" => "Texas", "City" => "Sugar Land", "Operator" => "METROPCS, INC."),
    "775" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "763" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "729" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "728" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "727" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "726" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "725" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "723" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "722" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "721" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "720" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "719" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "717" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "715" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "714" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "649" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "648" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "638" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "632" => array ( "Area" => "Texas", "City" => "Richmond Rosenberg", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "631" => array ( "Area" => "Texas", "City" => "Richmond Rosenberg", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "630" => array ( "Area" => "Texas", "City" => "Baytown", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "629" => array ( "Area" => "Texas", "City" => "Richmond Rosenberg", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "628" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "625" => array ( "Area" => "Texas", "City" => "Spring", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "607" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "METROPCS, INC."),
    "606" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "605" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "METROPCS, INC."),
    "598" => array ( "Area" => "Texas", "City" => "Tomball", "Operator" => "ONVOY SPECTRUM, LLC"),
    "597" => array ( "Area" => "Texas", "City" => "Langham Creek", "Operator" => "ONVOY SPECTRUM, LLC"),
    "596" => array ( "Area" => "Texas", "City" => "Humble", "Operator" => "ONVOY SPECTRUM, LLC"),
    "593" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "592" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "582" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "578" => array ( "Area" => "Texas", "City" => "Langham Creek", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "571" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "COMCAST IP PHONE, LLC"),
    "565" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "564" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "563" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "562" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "558" => array ( "Area" => "Texas", "City" => "Richmond Rosenberg", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "557" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "556" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "554" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "549" => array ( "Area" => "Texas", "City" => "Baytown", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "544" => array ( "Area" => "Texas", "City" => "Cypress", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "543" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "541" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "531" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "530" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "526" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "516" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "505" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "504" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "503" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "498" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "495" => array ( "Area" => "Texas", "City" => "Spring", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "494" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "493" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "492" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "491" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "490" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "479" => array ( "Area" => "Texas", "City" => "Langham Creek", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "475" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "473" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "469" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "464" => array ( "Area" => "Texas", "City" => "Richmond Rosenberg", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "454" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "448" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "435" => array ( "Area" => "Texas", "City" => "Langham Creek", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "434" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "431" => array ( "Area" => "Texas", "City" => "Baytown", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "430" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "401" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "399" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "383" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "370" => array ( "Area" => "Texas", "City" => "Spring", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "356" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - TX"),
    "343" => array ( "Area" => "Texas", "City" => "Sugar Land", "Operator" => "WINDSTREAM SUGAR LAND, LLC"),
    "340" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "ONVOY, LLC - TX"),
    "339" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "327" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "326" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "ONVOY, LLC - TX"),
    "317" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "303" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "302" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "299" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "ONVOY, LLC - TX"),
    "286" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "284" => array ( "Area" => "Texas", "City" => "Beach City", "Operator" => "METROPCS, INC."),
    "283" => array ( "Area" => "Texas", "City" => "Stafford", "Operator" => "METROPCS, INC."),
    "281" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "276" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "274" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "269" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "259" => array ( "Area" => "Texas", "City" => "Baytown", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "243" => array ( "Area" => "Texas", "City" => "Barker", "Operator" => "METROPCS, INC."),
    "238" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "234" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "231" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - TX"),
    "228" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "225" => array ( "Area" => "Texas", "City" => "Tomball", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "221" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "217" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "214" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "ONVOY, LLC - TX"),
    "213" => array ( "Area" => "Texas", "City" => "Langham Creek", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL")
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
