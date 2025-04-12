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
 * E.164 United States of America NDC 832 country hook
 */
framework_add_filter ( "e164_identify_NANPA_832", "e164_identify_NANPA_832");

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
function e164_identify_NANPA_832 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 832 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1832")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "997" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "988" => array ( "Area" => "Texas", "City" => "Sugar Land", "Operator" => "METROPCS, INC."),
    "985" => array ( "Area" => "Texas", "City" => "Apollo", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "983" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "980" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "979" => array ( "Area" => "Texas", "City" => "Humble", "Operator" => "ONVOY, LLC - TX"),
    "972" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "970" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "967" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "964" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "961" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "951" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "949" => array ( "Area" => "Texas", "City" => "Channelview", "Operator" => "METROPCS, INC."),
    "946" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "938" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "936" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "ONVOY SPECTRUM, LLC"),
    "933" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "931" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "929" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "927" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "924" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - TX"),
    "923" => array ( "Area" => "Texas", "City" => "Barker", "Operator" => "METROPCS, INC."),
    "921" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "908" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "907" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "903" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "898" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "897" => array ( "Area" => "Texas", "City" => "Stafford", "Operator" => "METROPCS, INC."),
    "896" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "894" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "893" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "892" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "891" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "890" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "889" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "888" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "887" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "885" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "884" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "883" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "882" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "881" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "880" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "874" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "873" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "870" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "856" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "853" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "846" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "842" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "840" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "831" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "COMCAST IP PHONE, LLC"),
    "829" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "817" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "815" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "812" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "810" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "809" => array ( "Area" => "Texas", "City" => "Barker", "Operator" => "SOUTHWESTERN BELL"),
    "805" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "801" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SPRINT SPECTRUM, L.P."),
    "796" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "785" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "776" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "774" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "765" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "760" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SPRINT SPECTRUM, L.P."),
    "751" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "750" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "748" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "744" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "733" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SOUTHWESTERN BELL"),
    "729" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "728" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "718" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SPRINT SPECTRUM, L.P."),
    "716" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "712" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "707" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "699" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "691" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "686" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "SPRINT SPECTRUM, L.P."),
    "682" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "670" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "662" => array ( "Area" => "Texas", "City" => "Splendora", "Operator" => "PEERLESS NETWORK OF TEXAS, LLC - TX"),
    "650" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "ONVOY, LLC - TX"),
    "638" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "627" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "625" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - TX"),
    "624" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - TX"),
    "614" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "609" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SPRINT SPECTRUM, L.P."),
    "600" => array ( "Area" => "Texas", "City" => "Richmond Rosenberg", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "574" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "SPRINT SPECTRUM, L.P."),
    "550" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "XO TEXAS, INC."),
    "546" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "544" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "540" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "537" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "523" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "522" => array ( "Area" => "Texas", "City" => "Barker", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "517" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "507" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "505" => array ( "Area" => "Texas", "City" => "Dickinson", "Operator" => "FRONTIER SOUTHWEST INC DBA FRONTIER COMM OF TEXAS"),
    "503" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "502" => array ( "Area" => "Texas", "City" => "Spring", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "499" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "477" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "475" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "474" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "472" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "470" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "469" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "459" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "454" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "438" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "437" => array ( "Area" => "Texas", "City" => "Katy", "Operator" => "COMCAST IP PHONE, LLC"),
    "424" => array ( "Area" => "Texas", "City" => "Langham Creek", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "421" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "420" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "405" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "396" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "388" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "383" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "382" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "370" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "362" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "SPRINT SPECTRUM, L.P."),
    "359" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "357" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "352" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "346" => array ( "Area" => "Texas", "City" => "Bammel", "Operator" => "ONVOY, LLC - TX"),
    "343" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "341" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "337" => array ( "Area" => "Texas", "City" => "Houston Suburban", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - TX"),
    "331" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "329" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "322" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "316" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "314" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "313" => array ( "Area" => "Texas", "City" => "Westfield", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "312" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "297" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "292" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "281" => array ( "Area" => "Texas", "City" => "Spring", "Operator" => "ONVOY, LLC - TX"),
    "273" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "272" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "270" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "268" => array ( "Area" => "Texas", "City" => "Apollo", "Operator" => "YMAX COMMUNICATIONS CORP. - TX"),
    "267" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "258" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "254" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "245" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "231" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "AERIAL COMMUNICATIONS, INC."),
    "227" => array ( "Area" => "Texas", "City" => "Barker", "Operator" => "SOUTHWESTERN BELL"),
    "218" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "208" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "207" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "206" => array ( "Area" => "Texas", "City" => "Houston", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL")
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
