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
 * E.164 United States of America NDC 945 country hook
 */
framework_add_filter ( "e164_identify_NANPA_945", "e164_identify_NANPA_945");

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
function e164_identify_NANPA_945 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 945 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1945")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "495" => array ( "Area" => "Texas", "City" => "Prosper", "Operator" => "ONVOY, LLC - TX"),
    "289" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "283" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "282" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "CAPROCK TELECOMMUNICATIONS CORP. - TX"),
    "275" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "274" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "T-MOBILE USA, INC."),
    "273" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "272" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "269" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "268" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "267" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "266" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "265" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "264" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "258" => array ( "Area" => "Texas", "City" => "Dallas", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "257" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "256" => array ( "Area" => "Texas", "City" => "Allen", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "254" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "ONVOY SPECTRUM, LLC"),
    "252" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "247" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "246" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "244" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "243" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "241" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "233" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "232" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "230" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "227" => array ( "Area" => "Texas", "City" => "Plano", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "225" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "220" => array ( "Area" => "Texas", "City" => "Mckinney", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "217" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "T-MOBILE USA, INC."),
    "214" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "213" => array ( "Area" => "Texas", "City" => "Frisco", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "210" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "209" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "208" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "T-MOBILE USA, INC."),
    "204" => array ( "Area" => "Texas", "City" => "Prosper", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "201" => array ( "Area" => "Texas", "City" => "Grand Prairie", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX")
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
