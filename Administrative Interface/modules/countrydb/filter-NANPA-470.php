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
 * E.164 United States of America NDC 470 country hook
 */
framework_add_filter ( "e164_identify_NANPA_470", "e164_identify_NANPA_470");

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
function e164_identify_NANPA_470 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 470 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1470")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "995" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "ONVOY, LLC - GA"),
    "994" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "ONVOY, LLC - GA"),
    "993" => array ( "Area" => "Georgia", "City" => "Social Circle", "Operator" => "ONVOY, LLC - GA"),
    "992" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "989" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "985" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "983" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "981" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "979" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "969" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "967" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "965" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "963" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "962" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "956" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "955" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "953" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "952" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "951" => array ( "Area" => "Georgia", "City" => "Cumming", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "939" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "930" => array ( "Area" => "Georgia", "City" => "Gainesville", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "929" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "927" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "926" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "925" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "923" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "921" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "920" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "919" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "917" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "915" => array ( "Area" => "Georgia", "City" => "Buford", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "913" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "909" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "908" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "907" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "904" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "902" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "901" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "899" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "898" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "896" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "894" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "886" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "884" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "883" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "882" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "871" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "861" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "859" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "856" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "855" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "854" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "843" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "841" => array ( "Area" => "Georgia", "City" => "Cumming", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "838" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "836" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "834" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "831" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "830" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "829" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "827" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "825" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "818" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "817" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "815" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "814" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "813" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "810" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "807" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "806" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "793" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "792" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "783" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "779" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "776" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "772" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "757" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "753" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "750" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "ONVOY, LLC - GA"),
    "746" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "740" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "ONVOY, LLC - GA"),
    "738" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "736" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "733" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "732" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "728" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "727" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "725" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "721" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "717" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "716" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "714" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "707" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "705" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BANDWIDTH.COM CLEC, LLC - GA"),
    "704" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "701" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "699" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "696" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "692" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "687" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "685" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "676" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "675" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "674" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "673" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "667" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "662" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "659" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "658" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "656" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "652" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "647" => array ( "Area" => "Georgia", "City" => "Gainesville", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "644" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "643" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "642" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "641" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "637" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "631" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "630" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "629" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "626" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "620" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "618" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "612" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "609" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "608" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "606" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "605" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "603" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "599" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "597" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "595" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "591" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "590" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "587" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "586" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "585" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "583" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "581" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "580" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "578" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "WINDSTREAM NUVOX, INC."),
    "576" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "572" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "567" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "ONVOY, LLC - GA"),
    "566" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - GA"),
    "565" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "562" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "561" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "559" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "557" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "556" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "552" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "PEERLESS NETWORK OF GEORGIA, LLC - GA"),
    "551" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "547" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "546" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "545" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "COMCAST IP PHONE, LLC"),
    "544" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "543" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "542" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "537" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - GA"),
    "535" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "530" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "528" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "527" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "526" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "525" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "522" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "521" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "515" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "512" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "510" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "503" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "502" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "BANDWIDTH.COM CLEC, LLC - GA"),
    "501" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "496" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "494" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "493" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "492" => array ( "Area" => "Georgia", "City" => "Adairsville", "Operator" => "PEERLESS NETWORK OF GEORGIA, LLC - GA"),
    "485" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "BANDWIDTH.COM CLEC, LLC - GA"),
    "484" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "481" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BANDWIDTH.COM CLEC, LLC - GA"),
    "477" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "SOUTHERN COMMUNICATIONS SERVICES, INC."),
    "476" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "475" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "473" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "469" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "463" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - GA"),
    "461" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "459" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "456" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "453" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "452" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "446" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "442" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "438" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "434" => array ( "Area" => "Georgia", "City" => "Buford", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - GA"),
    "432" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "430" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "425" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "424" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "421" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "418" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "417" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "416" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "413" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "412" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "ONVOY, LLC - GA"),
    "410" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "ONVOY, LLC - GA"),
    "409" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "405" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "399" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "395" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "COMCAST IP PHONE, LLC"),
    "393" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "392" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "390" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "388" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "383" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "382" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "379" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "376" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "371" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "368" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "367" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "366" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "356" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "355" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "COMCAST IP PHONE, LLC"),
    "353" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "352" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "345" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "344" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BANDWIDTH.COM CLEC, LLC - GA"),
    "342" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "341" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - GA"),
    "340" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "337" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "335" => array ( "Area" => "Georgia", "City" => "Cedartown", "Operator" => "PEERLESS NETWORK OF GEORGIA, LLC - GA"),
    "332" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "331" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "325" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "324" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "320" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "316" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "304" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "303" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "301" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "300" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "ONVOY, LLC - GA"),
    "293" => array ( "Area" => "Georgia", "City" => "Jackson", "Operator" => "PEERLESS NETWORK OF GEORGIA, LLC - GA"),
    "269" => array ( "Area" => "Georgia", "City" => "Winder", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "267" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - GA"),
    "265" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "POWERTEL ATLANTA LICENSES, INC."),
    "264" => array ( "Area" => "Georgia", "City" => "Atlanta South", "Operator" => "ONVOY, LLC - GA"),
    "262" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "SPRINT SPECTRUM, L.P."),
    "261" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "260" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "ONVOY, LLC - GA"),
    "259" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "254" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - GA"),
    "249" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "247" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "US LEC OF GEORGIA, INC."),
    "245" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "244" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "SPRINT SPECTRUM, L.P."),
    "234" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "SPRINT SPECTRUM, L.P."),
    "231" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "BANDWIDTH.COM CLEC, LLC - GA"),
    "230" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "224" => array ( "Area" => "Georgia", "City" => "Atlanta Northwest", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "218" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "217" => array ( "Area" => "Georgia", "City" => "Atlanta", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "214" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - GA"),
    "212" => array ( "Area" => "Georgia", "City" => "Atlanta Northeast", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - GA")
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
