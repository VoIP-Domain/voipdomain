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
 * E.164 United States of America NDC 239 country hook
 */
framework_add_filter ( "e164_identify_NANPA_239", "e164_identify_NANPA_239");

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
function e164_identify_NANPA_239 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 239 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1239")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "932" => array ( "Area" => "Florida", "City" => "Lehigh Acres", "Operator" => "ONVOY, LLC - FL"),
    "624" => array ( "Area" => "Florida", "City" => "Naples", "Operator" => "EMBARQ FLORIDA, INC. DBA CENTURYLINK"),
    "610" => array ( "Area" => "Florida", "City" => "Naples", "Operator" => "ONVOY, LLC - FL"),
    "552" => array ( "Area" => "Florida", "City" => "North Naples", "Operator" => "EMBARQ FLORIDA, INC. DBA CENTURYLINK"),
    "533" => array ( "Area" => "Florida", "City" => "Fort Myers", "Operator" => "EMBARQ FLORIDA, INC. DBA CENTURYLINK"),
    "500" => array ( "Area" => "Florida", "City" => "Fort Myers", "Operator" => "US LEC OF FLORIDA, INC."),
    "468" => array ( "Area" => "Florida", "City" => "Bonita Springs", "Operator" => "EMBARQ FLORIDA, INC. DBA CENTURYLINK"),
    "440" => array ( "Area" => "Florida", "City" => "Fort Myers", "Operator" => "METROPCS, INC."),
    "429" => array ( "Area" => "Florida", "City" => "Naples", "Operator" => "ONVOY, LLC - FL"),
    "420" => array ( "Area" => "Florida", "City" => "Marco Island", "Operator" => "ONVOY, LLC - FL"),
    "413" => array ( "Area" => "Florida", "City" => "Fort Myers", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - FL"),
    "412" => array ( "Area" => "Florida", "City" => "Fort Myers", "Operator" => "OMNIPOINT MIAMI E LICENSE, LLC"),
    "377" => array ( "Area" => "Florida", "City" => "North Naples", "Operator" => "US LEC OF FLORIDA, INC."),
    "346" => array ( "Area" => "Florida", "City" => "Fort Myers", "Operator" => "EMBARQ FLORIDA, INC. DBA CENTURYLINK"),
    "343" => array ( "Area" => "Florida", "City" => "Fort Myers", "Operator" => "EMBARQ FLORIDA, INC. DBA CENTURYLINK"),
    "323" => array ( "Area" => "Florida", "City" => "Cape Coral", "Operator" => "ONVOY, LLC - FL"),
    "268" => array ( "Area" => "Florida", "City" => "Cape Coral", "Operator" => "ONVOY, LLC - FL"),
    "252" => array ( "Area" => "Florida", "City" => "Naples", "Operator" => "EMBARQ FLORIDA, INC. DBA CENTURYLINK"),
    "200" => array ( "Area" => "Florida", "City" => "Naples", "Operator" => "METROPCS, INC.")
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
