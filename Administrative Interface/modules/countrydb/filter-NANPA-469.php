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
 * E.164 United States of America NDC 469 country hook
 */
framework_add_filter ( "e164_identify_NANPA_469", "e164_identify_NANPA_469");

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
function e164_identify_NANPA_469 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 469 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1469")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "997" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "996" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "994" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "991" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "ONVOY, LLC - TX"),
    "982" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "978" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "973" => array ( "Area" => "Texas", "City" => "Lewisville", "Operator" => "CENTURYLINK COMMUNICATIONS, LLC"),
    "967" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "961" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "955" => array ( "Area" => "Texas", "City" => "Irving", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "953" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "934" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "927" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "T-MOBILE USA, INC."),
    "924" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "921" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "918" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "891" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "882" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "880" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "875" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "873" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "861" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "859" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "852" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "849" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "841" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "835" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "834" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "832" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "826" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "823" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "822" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "818" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "800" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "799" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "798" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "792" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "787" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "764" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "SOUTHWESTERN BELL"),
    "760" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "756" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "744" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "740" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "739" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "724" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "705" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "695" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "690" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "686" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "681" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "679" => array ( "Area" => "Texas", "City" => "Ennis", "Operator" => "T-MOBILE USA, INC."),
    "670" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "662" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "655" => array ( "Area" => "Texas", "City" => "Forney", "Operator" => "T-MOBILE USA, INC."),
    "650" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "METROPCS, INC."),
    "623" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "618" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "607" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "603" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "601" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "600" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "597" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "580" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "578" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "571" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "560" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "559" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "558" => array ( "Area" => "Texas", "City" => "Ennis", "Operator" => "T-MOBILE USA, INC."),
    "544" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "542" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "509" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "SPRINT SPECTRUM, L.P."),
    "508" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "METROPCS, INC."),
    "500" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "495" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "494" => array ( "Area" => "Texas", "City" => "Ennis", "Operator" => "T-MOBILE USA, INC."),
    "488" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "SOUTHWESTERN BELL TELEPHONE COMPANY - TX"),
    "465" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "463" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "462" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "SOUTHWESTERN BELL"),
    "439" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "436" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "ONVOY, LLC - TX"),
    "435" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "432" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "427" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "419" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "413" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL"),
    "407" => array ( "Area" => "Texas", "City" => "Forney", "Operator" => "T-MOBILE USA, INC."),
    "405" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "ONVOY, LLC - TX"),
    "404" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "400" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "394" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "386" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "380" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "362" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "SOUTHWESTERN BELL"),
    "328" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "321" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "318" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "316" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "SPRINT SPECTRUM, L.P."),
    "313" => array ( "Area" => "Texas", "City" => "De Soto", "Operator" => "PEERLESS NETWORK OF TEXAS, LLC - TX"),
    "308" => array ( "Area" => "Texas", "City" => "Lewisville", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "303" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "SOUTHWESTERN BELL TELEPHONE COMPANY - TX"),
    "302" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "MCLEODUSA TELECOMMUNICATION SERVICES, INC. - TX"),
    "292" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "SOUTHWESTERN BELL TELEPHONE COMPANY - TX"),
    "282" => array ( "Area" => "Texas", "City" => "Irving", "Operator" => "SOUTHWESTERN BELL TELEPHONE COMPANY - TX"),
    "274" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "258" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "243" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "236" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "230" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "226" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "220" => array ( "Area" => "Texas", "City" => "Irving", "Operator" => "FRONTIER SOUTHWEST INC DBA FRONTIER COMM OF TEXAS"),
    "210" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "ONVOY, LLC - TX"),
    "205" => array ( "Area" => "Texas", "City" => "Dallas Fort Worth Airport", "Operator" => "ONVOY, LLC - TX"),
    "204" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "SOUTHWESTERN BELL")
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
