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
 * E.164 United States of America NDC 646 country hook
 */
framework_add_filter ( "e164_identify_NANPA_646", "e164_identify_NANPA_646");

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
function e164_identify_NANPA_646 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 646 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1646")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "997" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "990" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "988" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "987" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "986" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "984" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "983" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "972" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "967" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "962" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "953" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "947" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "946" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "945" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "938" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "933" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "928" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "TIME WARNER CABLE INFO SVCE (NEW YORK) LLC-NY"),
    "921" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "920" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "913" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "909" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "903" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "899" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "897" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "893" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "888" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "886" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "882" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "TIME WARNER CABLE INFO SVCE (NEW YORK) LLC-NY"),
    "869" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "TIME WARNER CABLE INFO SVCE (NEW YORK) LLC-NY"),
    "855" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "850" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "TIME WARNER CABLE INFO SVCE (NEW YORK) LLC-NY"),
    "849" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "846" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "844" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "820" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "816" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CABLEVISION LIGHTPATH, INC. - NY"),
    "815" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CABLEVISION LIGHTPATH, INC. - NY"),
    "814" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "803" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "TC SYSTEMS, INC. - NY"),
    "801" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "782" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "779" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "771" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "761" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "754" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "750" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "748" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "747" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "743" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "741" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "730" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "723" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "EUREKA TELECOM, INC. DBA EUREKA NETWORKS - NY"),
    "722" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "697" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "691" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "690" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "686" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CABLEVISION LIGHTPATH, INC. - NY"),
    "683" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "675" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "659" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "656" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "655" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "646" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "REGIONAL TELEPHONE CORPORATION - NY"),
    "636" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "635" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "634" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "631" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "629" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "617" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "615" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CABLEVISION LIGHTPATH, INC. - NY"),
    "614" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CABLEVISION LIGHTPATH, INC. - NY"),
    "608" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "605" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "XO NEW YORK, INC."),
    "590" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "TIME WARNER CABLE INFO SVCE (NEW YORK) LLC-NY"),
    "585" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "584" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "580" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "579" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "577" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "575" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "574" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "573" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "566" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY SPECTRUM, LLC"),
    "550" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "544" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "543" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "538" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "535" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "516" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY, LLC- NY"),
    "508" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "504" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "501" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CABLEVISION LIGHTPATH, INC. - NY"),
    "494" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "493" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "488" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "482" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "TC SYSTEMS, INC. - NY"),
    "481" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "477" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "474" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "470" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "469" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "468" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "451" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "450" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "447" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "446" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "433" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "426" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "399" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "397" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "389" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "384" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "377" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "353" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "METROPCS, INC."),
    "333" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "332" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "ONVOY SPECTRUM, LLC"),
    "323" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "317" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "VERIZON NEW YORK, INC."),
    "310" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "TC SYSTEMS, INC. - NY"),
    "309" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "301" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "288" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "287" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "266" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "260" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "SPRINT SPECTRUM, L.P."),
    "204" => array ( "Area" => "New York", "City" => "New York City Zone  1", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY")
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
