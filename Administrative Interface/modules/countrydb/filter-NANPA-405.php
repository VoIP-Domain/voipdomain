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
 * E.164 United States of America NDC 405 country hook
 */
framework_add_filter ( "e164_identify_NANPA_405", "e164_identify_NANPA_405");

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
function e164_identify_NANPA_405 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 405 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1405")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "985" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "965" => array ( "Area" => "Oklahoma", "City" => "Chandler", "Operator" => "T-MOBILE USA, INC."),
    "935" => array ( "Area" => "Oklahoma", "City" => "Britton", "Operator" => "SOUTHWESTERN BELL"),
    "889" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "887" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "T-MOBILE USA, INC."),
    "886" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "885" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "882" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "881" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "863" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "UNITED STATES CELLULAR - OK"),
    "802" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "761" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "693" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "651" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "626" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "549" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "537" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "SPRINT SPECTRUM, L.P."),
    "519" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OK"),
    "496" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "490" => array ( "Area" => "Oklahoma", "City" => "Anadarko", "Operator" => "T-MOBILE USA, INC."),
    "465" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OK"),
    "464" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "441" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OK"),
    "439" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OK"),
    "436" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "SPRINT SPECTRUM, L.P."),
    "435" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OK"),
    "430" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "T-MOBILE USA, INC."),
    "423" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OK"),
    "403" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OK"),
    "397" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "394" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OK"),
    "365" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "343" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "318" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - OK"),
    "315" => array ( "Area" => "Oklahoma", "City" => "Edmond", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "201" => array ( "Area" => "Oklahoma", "City" => "Oklahoma City", "Operator" => "UNITED STATES CELLULAR - OK")
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
