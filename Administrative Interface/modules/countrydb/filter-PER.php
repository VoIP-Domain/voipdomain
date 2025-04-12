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
 * related to country database of Peru.
 *
 * Reference: https://www.itu.int/oth/T02020000A6/en (2010-09-07)
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
 * E.164 Peru country hook
 */
framework_add_filter ( "e164_identify_country_PER", "e164_identify_country_PER");

/**
 * E.164 Peruvian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "PER" (code for Peru). This hook
 * will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_PER ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Peru
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+51")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Peru has 11 or 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 11 || strlen ( $parameters["Number"]) > 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 9 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 12 && substr ( $parameters["Number"], 3, 1) == "9")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "51", "NDC" => "", "Country" => "Peru", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 3), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+51 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for fixed line network with 1 or 2 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "84" => "Cuzco",
    "83" => "Apurimac",
    "82" => "Madre de Dios",
    "76" => "Cajamarca",
    "74" => "Lambayeque",
    "73" => "Plura",
    "72" => "Tumbes",
    "67" => "Huancavelica",
    "66" => "Ayacucho",
    "65" => "Loreto",
    "64" => "Junin",
    "63" => "Pasco",
    "62" => "Huanuco",
    "61" => "Ucayali",
    "56" => "Ica",
    "54" => "Arequipa",
    "53" => "Moquegua",
    "52" => "Tacna",
    "51" => "Puno",
    "44" => "La Liberdad",
    "43" => "Ancash",
    "42" => "San Martín",
    "41" => "Amazonas",
    "1" => "Lima & Callao"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "51", "NDC" => $prefix, "Country" => "Peru", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, strlen ( $prefix)) . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix), 4 - strlen ( $prefix)) . " " . substr ( $parameters["Number"], 7), "International" => "+51 " . substr ( $parameters["Number"], 3, strlen ( $prefix)) . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix), 4 - strlen ( $prefix)) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Peruvian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
