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
 * E.164 United States of America NDC 305 country hook
 */
framework_add_filter ( "e164_identify_NANPA_305", "e164_identify_NANPA_305");

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
function e164_identify_NANPA_305 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 305 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1305")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "988" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "980" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "ONVOY SPECTRUM, LLC"),
    "927" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "912" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "896" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "METROPCS, INC."),
    "879" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "METROPCS, INC."),
    "848" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "YMAX COMMUNICATIONS CORP. - FL"),
    "833" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "814" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "800" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "PAETEC COMMUNICATIONS, INC. - FL"),
    "765" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY SPECTRUM, LLC"),
    "703" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "ONVOY, LLC - FL"),
    "697" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "686" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "ONVOY, LLC - FL"),
    "602" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL"),
    "589" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "540" => array ( "Area" => "Florida", "City" => "North Dade", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "497" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "464" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "457" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "427" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "419" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "ONVOY, LLC - FL"),
    "399" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "339" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "334" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "ONVOY SPECTRUM, LLC"),
    "316" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "METROPCS, INC."),
    "306" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "BANDWIDTH.COM CLEC, LLC - FL"),
    "209" => array ( "Area" => "Florida", "City" => "Keys", "Operator" => "PEERLESS NETWORK OF FLORIDA, LLC - FL"),
    "202" => array ( "Area" => "Florida", "City" => "Miami", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA")
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
