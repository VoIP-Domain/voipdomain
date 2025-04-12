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
 * E.164 United States of America NDC 929 country hook
 */
framework_add_filter ( "e164_identify_NANPA_929", "e164_identify_NANPA_929");

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
function e164_identify_NANPA_929 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 929 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1929")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "998" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "ONVOY, LLC- NY"),
    "929" => array ( "Area" => "New York", "City" => "New York City Zone 13", "Operator" => "REGIONAL TELEPHONE CORPORATION - NY"),
    "654" => array ( "Area" => "New York", "City" => "New York City Zone 12", "Operator" => "ONVOY SPECTRUM, LLC"),
    "653" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "ONVOY SPECTRUM, LLC"),
    "652" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "ONVOY SPECTRUM, LLC"),
    "636" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "634" => array ( "Area" => "New York", "City" => "New York City Zone  9", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "629" => array ( "Area" => "New York", "City" => "New York City Zone 15", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "619" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "ONVOY SPECTRUM, LLC"),
    "617" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "606" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "597" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "ONVOY SPECTRUM, LLC"),
    "584" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "576" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "US LEC COMMUNICATIONS, INC. - NY"),
    "574" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "US LEC COMMUNICATIONS, INC. - NY"),
    "573" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "US LEC COMMUNICATIONS, INC. - NY"),
    "549" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "548" => array ( "Area" => "New York", "City" => "New York City Zone 11", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "546" => array ( "Area" => "New York", "City" => "New York City Zone 11", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "541" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "CSC WIRELESS, LLC"),
    "537" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "527" => array ( "Area" => "New York", "City" => "New York City Zone  9", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "524" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "518" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "503" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "497" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "495" => array ( "Area" => "New York", "City" => "New York City Zone 11", "Operator" => "NUSO, LLC"),
    "483" => array ( "Area" => "New York", "City" => "New York City Zone  9", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "477" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "PEERLESS NETWORK OF NEW YORK, LLC - NY"),
    "461" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "455" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "PAETEC COMMUNICATIONS, INC. - NY"),
    "449" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "440" => array ( "Area" => "New York", "City" => "New York City Zone 14", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "439" => array ( "Area" => "New York", "City" => "New York City Zone 15", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NY"),
    "434" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "426" => array ( "Area" => "New York", "City" => "New York City Zone  9", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "413" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "393" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "391" => array ( "Area" => "New York", "City" => "New York City Zone  9", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "385" => array ( "Area" => "New York", "City" => "New York City Zone  5", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "376" => array ( "Area" => "New York", "City" => "New York City Zone  5", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "351" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "340" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "330" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "326" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "319" => array ( "Area" => "New York", "City" => "New York City Zone 13", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "318" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "317" => array ( "Area" => "New York", "City" => "New York City Zone 15", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "316" => array ( "Area" => "New York", "City" => "New York City Zone 14", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "313" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "310" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "304" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "277" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "245" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "221" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - NY"),
    "217" => array ( "Area" => "New York", "City" => "New York City Zone  5", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "215" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "213" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "209" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "ONVOY, LLC- NY"),
    "207" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "ONVOY, LLC- NY"),
    "205" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "ONVOY, LLC- NY"),
    "203" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "ONVOY, LLC- NY"),
    "201" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "ONVOY, LLC- NY")
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
