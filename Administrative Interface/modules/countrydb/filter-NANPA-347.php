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
 * E.164 United States of America NDC 347 country hook
 */
framework_add_filter ( "e164_identify_NANPA_347", "e164_identify_NANPA_347");

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
function e164_identify_NANPA_347 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 347 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1347")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "991" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "981" => array ( "Area" => "New York", "City" => "New York City Zone 11", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "973" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "972" => array ( "Area" => "New York", "City" => "New York City Zone 13", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "965" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "METROPCS, INC."),
    "953" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "ONVOY SPECTRUM, LLC"),
    "943" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "942" => array ( "Area" => "New York", "City" => "New York City Zone  5", "Operator" => "METROPCS, INC."),
    "938" => array ( "Area" => "New York", "City" => "New York City Zone 15", "Operator" => "METROPCS, INC."),
    "929" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "ONVOY, LLC- NY"),
    "927" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "903" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "898" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "METROPCS, INC."),
    "877" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "ONVOY SPECTRUM, LLC"),
    "873" => array ( "Area" => "New York", "City" => "New York City Zone  5", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "871" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "863" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "858" => array ( "Area" => "New York", "City" => "New York City Zone 11", "Operator" => "METROPCS, INC."),
    "822" => array ( "Area" => "New York", "City" => "New York City Zone  9", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "815" => array ( "Area" => "New York", "City" => "New York City Zone 12", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "795" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "ONVOY, LLC- NY"),
    "792" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "METROPCS, INC."),
    "791" => array ( "Area" => "New York", "City" => "New York City Zone 14", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "785" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "METROPCS, INC."),
    "784" => array ( "Area" => "New York", "City" => "New York City Zone  5", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "777" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "METROPCS, INC."),
    "766" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "762" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "755" => array ( "Area" => "New York", "City" => "New York City Zone 14", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "746" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "740" => array ( "Area" => "New York", "City" => "New York City Zone  5", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "737" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "METROPCS, INC."),
    "720" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "716" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "ONVOY, LLC- NY"),
    "709" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "703" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "701" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "698" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "687" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "674" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "666" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "METROPCS, INC."),
    "664" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "METROPCS, INC."),
    "654" => array ( "Area" => "New York", "City" => "New York City Zone  9", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "648" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - NY"),
    "618" => array ( "Area" => "New York", "City" => "New York City Zone  9", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "610" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "605" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "600" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "METROPCS, INC."),
    "595" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "METROPCS, INC."),
    "585" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "575" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "568" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "YMAX COMMUNICATIONS CORP. - NY"),
    "567" => array ( "Area" => "New York", "City" => "New York City Zone 12", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "556" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "553" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "550" => array ( "Area" => "New York", "City" => "New York City Zone 12", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "543" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "540" => array ( "Area" => "New York", "City" => "New York City Zone 12", "Operator" => "LTE WIRELESS INC D/B/A LTE WIRELESS - NY"),
    "520" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "METROPCS, INC."),
    "500" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "METROPCS, INC."),
    "499" => array ( "Area" => "New York", "City" => "New York City Zone  5", "Operator" => "METROPCS, INC."),
    "488" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "METROPCS, INC."),
    "485" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "METROPCS, INC."),
    "484" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "476" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "470" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "460" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "459" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "458" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "450" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "447" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "METROPCS, INC."),
    "446" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - DC"),
    "445" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "444" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "METROPCS, INC."),
    "428" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "ONVOY, LLC- NY"),
    "399" => array ( "Area" => "New York", "City" => "New York City Zone  9", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "358" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "METROPCS, INC."),
    "357" => array ( "Area" => "New York", "City" => "New York City Zone 11", "Operator" => "METROPCS, INC."),
    "356" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "METROPCS, INC."),
    "355" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "METROPCS, INC."),
    "354" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "352" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "347" => array ( "Area" => "New York", "City" => "New York City Zone 14", "Operator" => "REGIONAL TELEPHONE CORPORATION - NY"),
    "339" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "METROPCS, INC."),
    "336" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "METROPCS, INC."),
    "323" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "320" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "319" => array ( "Area" => "New York", "City" => "New York City Zone 11", "Operator" => "METROPCS, INC."),
    "315" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "BANDWIDTH.COM CLEC, LLC - NY"),
    "307" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "303" => array ( "Area" => "New York", "City" => "New York City Zone  8", "Operator" => "METROPCS, INC."),
    "299" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "METROPCS, INC."),
    "298" => array ( "Area" => "New York", "City" => "New York City Zone 14", "Operator" => "YMAX COMMUNICATIONS CORP. - NY"),
    "285" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "283" => array ( "Area" => "New York", "City" => "New York City Zone 14", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "279" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "266" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NY"),
    "265" => array ( "Area" => "New York", "City" => "New York City Zone 14", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "264" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "261" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "259" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "257" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "253" => array ( "Area" => "New York", "City" => "New York City Zone 10", "Operator" => "VERIZON NEW YORK, INC."),
    "250" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "VERIZON NEW YORK, INC."),
    "241" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "240" => array ( "Area" => "New York", "City" => "New York City Zone  6", "Operator" => "CABLEVISION LIGHTPATH, INC. - NY"),
    "209" => array ( "Area" => "New York", "City" => "New York City Zone  3", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "208" => array ( "Area" => "New York", "City" => "New York City Zone  4", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "207" => array ( "Area" => "New York", "City" => "New York City Zone  5", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY"),
    "206" => array ( "Area" => "New York", "City" => "New York City Zone  7", "Operator" => "OMNIPOINT COMMUNICATIONS, INC. - NY")
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
