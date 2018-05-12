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
 * E.164 United States of America NDC 442 country hook
 */
framework_add_filter ( "e164_identify_NANPA_442", "e164_identify_NANPA_442");

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
function e164_identify_NANPA_442 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 442 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1442")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "387" => array ( "Area" => "California", "City" => "Salton", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "383" => array ( "Area" => "California", "City" => "Desert Hot Springs", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "381" => array ( "Area" => "California", "City" => "Indio", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "376" => array ( "Area" => "California", "City" => "Escondido", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "371" => array ( "Area" => "California", "City" => "Palm Desert", "Operator" => "U.S. TELEPACIFIC CORP. DBA TPX COMMUNICATIONS"),
    "365" => array ( "Area" => "California", "City" => "Vtvl Apvy", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "364" => array ( "Area" => "California", "City" => "Brawley", "Operator" => "T-MOBILE USA, INC."),
    "361" => array ( "Area" => "California", "City" => "Palm Springs", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "360" => array ( "Area" => "California", "City" => "Palm Springs", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "352" => array ( "Area" => "California", "City" => "Palm Springs", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "351" => array ( "Area" => "California", "City" => "Escondido", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "349" => array ( "Area" => "California", "City" => "Escondido", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "344" => array ( "Area" => "California", "City" => "Victorville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "343" => array ( "Area" => "California", "City" => "Victorville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "338" => array ( "Area" => "California", "City" => "Palm Springs", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "337" => array ( "Area" => "California", "City" => "Victorville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "335" => array ( "Area" => "California", "City" => "Palm Springs", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "332" => array ( "Area" => "California", "City" => "Palm Springs", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "331" => array ( "Area" => "California", "City" => "Escondido", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "329" => array ( "Area" => "California", "City" => "Escondido", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "314" => array ( "Area" => "California", "City" => "Victorville", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "279" => array ( "Area" => "California", "City" => "Brawley", "Operator" => "T-MOBILE USA, INC."),
    "265" => array ( "Area" => "California", "City" => "El Centro", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - CA")
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
