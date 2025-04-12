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
 * related to country database of American Samoa.
 *
 * Reference: https://www.itu.int/oth/T0202000004/en (2006-07-20)
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
 * E.164 American Samoa country hook
 */
framework_add_filter ( "e164_identify_country_ASM", "e164_identify_country_ASM");

/**
 * E.164 American Samoan area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "ASM" (code for
 * American Samoa). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_ASM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from American Samoa
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1684")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in American Samoa has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for 7 digits SN ([2-9]XXXXXX)
   */
  $prefixes = array (
    "733" => array ( "area" => "", "operator" => "ASTCA"),
    "699" => array ( "area" => "Tafuna", "operator" => "ASTCA"),
    "691" => array ( "area" => "Olotela and Aolaan", "operator" => "ASTCA"),
    "688" => array ( "area" => "Leone", "operator" => "ASTCA"),
    "677" => array ( "area" => "Tau", "operator" => "ASTCA"),
    "655" => array ( "area" => "Ofu", "operator" => "ASTCA"),
    "644" => array ( "area" => "Satala", "operator" => "ASTCA"),
    "633" => array ( "area" => "Fagatogo", "operator" => "ASTCA"),
    "622" => array ( "area" => "Fagaitua", "operator" => "ASTCA"),
    "258" => array ( "area" => "", "operator" => "Blue Sky")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1684", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "American Samoa", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => ( substr ( $parameters["Number"], 5, 1) == "2" || substr ( $parameters["Number"], 5, 1) == "7" ? VD_PHONETYPE_MOBILE : VD_PHONETYPE_LANDLINE), "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 684 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid American Samoan phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
