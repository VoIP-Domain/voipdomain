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
 * E.164 United States of America NDC 786 country hook
 */
framework_add_filter ( "e164_identify_NANPA_786", "e164_identify_NANPA_786");

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
function e164_identify_NANPA_786 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 786 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1786")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "998" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "ONVOY, LLC - FL"),
    "973" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "970" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "961" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "956" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "937" => array ( "Area" => "Florida", "City" => "Homestead", "Operator" => "ONVOY, LLC - FL"),
    "936" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY, LLC - FL"),
    "925" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "918" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "912" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "910" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "903" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "898" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "896" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "893" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "891" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "ONVOY, LLC - FL"),
    "890" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "887" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY SPECTRUM, LLC"),
    "882" => array ( "Area" => "Florida", "City" => "Perrine", "Operator" => "ONVOY, LLC - FL"),
    "881" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "874" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "873" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "867" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "858" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "856" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "854" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "852" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "849" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "846" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "841" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "ONVOY, LLC - FL"),
    "840" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY, LLC - FL"),
    "836" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "835" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "834" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "832" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "831" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "830" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "825" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "824" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "820" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "819" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "818" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "816" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "810" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "806" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "796" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY, LLC - FL"),
    "795" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "794" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "793" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "790" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "789" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY, LLC - FL"),
    "785" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL"),
    "784" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "782" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "781" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "778" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "769" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "757" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "750" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "748" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "746" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY, LLC - FL"),
    "742" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "737" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "736" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "731" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "729" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "726" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "722" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "721" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "719" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "716" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "715" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "710" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "708" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "706" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY, LLC - FL"),
    "702" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "696" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "695" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "694" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "690" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "689" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "688" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "686" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "ONVOY, LLC - FL"),
    "682" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "679" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "678" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "674" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "673" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY, LLC - FL"),
    "669" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "658" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "653" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "643" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "642" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "632" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "630" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "614" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "612" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "605" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL"),
    "603" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "595" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "588" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "575" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "570" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "562" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "560" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "559" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "557" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "540" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "537" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "529" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "505" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL"),
    "500" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "PAETEC COMMUNICATIONS, INC. - FL"),
    "499" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "494" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "480" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "ONVOY, LLC - FL"),
    "479" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "462" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "YMAX COMMUNICATIONS CORP. - FL"),
    "460" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY, LLC - FL"),
    "459" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "451" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "448" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "418" => array ( "Area" => "Florida", "City" => "Perrine", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL"),
    "415" => array ( "Area" => "Florida", "City" => "Homestead", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "408" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "407" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "403" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "392" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "PAETEC COMMUNICATIONS, INC. - FL"),
    "386" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "ONVOY, LLC - FL"),
    "381" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "370" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "366" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "354" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "343" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "340" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "330" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "328" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "292" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "283" => array ( "Area" => "Florida", "City" => "Homestead", "Operator" => "METROPCS, INC."),
    "241" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "204" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL")
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
