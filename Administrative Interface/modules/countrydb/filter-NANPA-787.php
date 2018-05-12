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
 * E.164 United States of America NDC 787 country hook
 */
framework_add_filter ( "e164_identify_NANPA_787", "e164_identify_NANPA_787");

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
function e164_identify_NANPA_787 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 787 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1787")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "989" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SPRINT SPECTRUM, L.P."),
    "981" => array ( "Area" => "Puerto Rico", "City" => "Caguas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "975" => array ( "Area" => "Puerto Rico", "City" => "Levittown", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "974" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "955" => array ( "Area" => "Puerto Rico", "City" => "Caguas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "951" => array ( "Area" => "Puerto Rico", "City" => "Mayaguez", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "948" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SPRINT SPECTRUM, L.P."),
    "930" => array ( "Area" => "Puerto Rico", "City" => "Caguas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "916" => array ( "Area" => "Puerto Rico", "City" => "Juncos", "Operator" => "AT&T, INC. - PR"),
    "908" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Sur", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "907" => array ( "Area" => "Puerto Rico", "City" => "Caguas", "Operator" => "SPRINT SPECTRUM, L.P."),
    "902" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SPRINT SPECTRUM, L.P."),
    "901" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "800" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SPRINT SPECTRUM, L.P."),
    "718" => array ( "Area" => "Puerto Rico", "City" => "Caguas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "702" => array ( "Area" => "Puerto Rico", "City" => "Naranjito", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "692" => array ( "Area" => "Puerto Rico", "City" => "Pueblo Viejo", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "690" => array ( "Area" => "Puerto Rico", "City" => "Carolina", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "685" => array ( "Area" => "Puerto Rico", "City" => "Caguas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "678" => array ( "Area" => "Puerto Rico", "City" => "Cayey", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "675" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Sur", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "673" => array ( "Area" => "Puerto Rico", "City" => "San German", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "669" => array ( "Area" => "Puerto Rico", "City" => "Arecibo", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "664" => array ( "Area" => "Puerto Rico", "City" => "Pueblo Viejo", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "662" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "619" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "595" => array ( "Area" => "Puerto Rico", "City" => "Cayey", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "524" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELEPHONE COMPANY, INC."),
    "513" => array ( "Area" => "Puerto Rico", "City" => "Carolina", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "481" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "480" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELEPHONE COMPANY, INC."),
    "478" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "461" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SPRINT SPECTRUM, L.P."),
    "459" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "456" => array ( "Area" => "Puerto Rico", "City" => "Aguadilla", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "452" => array ( "Area" => "Puerto Rico", "City" => "Arecibo", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "400" => array ( "Area" => "Puerto Rico", "City" => "Guaynabo", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "359" => array ( "Area" => "Puerto Rico", "City" => "Naranjito", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "341" => array ( "Area" => "Puerto Rico", "City" => "Arroyo", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "224" => array ( "Area" => "Puerto Rico", "City" => "Levittown", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "223" => array ( "Area" => "Puerto Rico", "City" => "Guaynabo", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "221" => array ( "Area" => "Puerto Rico", "City" => "Pueblo Viejo", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA")
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
