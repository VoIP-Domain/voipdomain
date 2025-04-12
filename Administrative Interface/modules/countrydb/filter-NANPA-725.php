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
 * E.164 United States of America NDC 725 country hook
 */
framework_add_filter ( "e164_identify_NANPA_725", "e164_identify_NANPA_725");

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
function e164_identify_NANPA_725 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 725 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1725")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "800" => array ( "Area" => "Nevada", "City" => "Jean", "Operator" => "ONVOY, LLC - NV"),
    "799" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "COMCAST IP PHONE, LLC"),
    "712" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "590" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CENTRAL TEL. CO. - NEVADA DBA CENTURYLINK"),
    "525" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "485" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "433" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "334" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "327" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY SPECTRUM, LLC"),
    "290" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "289" => array ( "Area" => "Nevada", "City" => "Laughlin", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "284" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "283" => array ( "Area" => "Nevada", "City" => "Jean", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "282" => array ( "Area" => "Nevada", "City" => "Nelson", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "280" => array ( "Area" => "Nevada", "City" => "Mount Charleston", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "276" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "275" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "274" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "270" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "265" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "264" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "260" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "259" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "257" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "249" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "247" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "243" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "233" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "221" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV")
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
