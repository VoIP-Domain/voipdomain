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
 * related to country database of Venezuela (Bolivarian Republic of).
 *
 * Reference: https://www.itu.int/oth/T02020000E3/en (2011-05-19)
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
 * E.164 Venezuela (Bolivarian Republic of) country hook
 */
framework_add_filter ( "e164_identify_country_MLI", "e164_identify_country_MLI");

/**
 * E.164 Venezuela (Bolivarian Republic of) area number identification hook. This
 * hook is an e164_identify sub hook, called when the ISO3166 Alpha3 are "MLI"
 * (code for Venezuela (Bolivarian Republic of)). This hook will verify if phone
 * number is valid, returning the area code, area name, phone number, others
 * number related information and if possible, the number type (mobile,
 * landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_MLI ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Venezuela (Bolivarian Republic of)
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+58")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Venezuela (Bolivarian Republic of) has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "426" => "Telecomunicacion es Movilnet C.A.",
    "424" => "TELCEL C.A. (Movistar)",
    "416" => "Telecomunicacion es Movilnet C.A.",
    "415" => "Globalstar de Venezela C.A.",
    "414" => "TELCEL C.A. (Movistar)",
    "412" => "Corporación Digitel C.A."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "58", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Venezuela (Bolivarian Republic of)", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+58 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "296" => "Amazonas state",
    "295" => "Nueva Esparta state",
    "294" => "Sucre state",
    "293" => "Sucre state",
    "292" => "Anzoátegui and Monagas states",
    "291" => "Monagas state",
    "289" => "Bolívar state",
    "288" => "Bolívar state",
    "287" => "Delta Amacuro and Monagas states",
    "286" => "Bolívar and Monagas states",
    "285" => "Bolívar and Anzoátegui states",
    "284" => "Bolívar state",
    "283" => "Anzoátegui state",
    "282" => "Anzoátegui state",
    "281" => "Anzoátegui state",
    "279" => "Falcón state",
    "278" => "Barinas state",
    "277" => "Táchira and Mérida states",
    "276" => "Táchira state",
    "275" => "Zulia, Mérida and Táchira states",
    "274" => "Mérida state",
    "273" => "Barinas state",
    "272" => "Trujillo state",
    "271" => "Trujillo, Zulia and Mérida states",
    "269" => "Falcón state",
    "268" => "Falcón state",
    "267" => "Zulia state",
    "266" => "Zulia state",
    "265" => "Zulia state",
    "264" => "Zulia state",
    "263" => "Zulia state",
    "262" => "Zulia state",
    "261" => "Zulia state",
    "259" => "Falcón state",
    "258" => "Cojedes and Barinas states",
    "257" => "Portuguese State",
    "256" => "Portuguese State",
    "255" => "Portuguese State",
    "254" => "Yaracuy state",
    "253" => "Lara and Yaracuy states",
    "252" => "Lara state",
    "251" => "Lara and Yaracuy states",
    "249" => "Carabobo state",
    "248" => "Amazonas state",
    "247" => "Apure, Barinas and Guárico states",
    "246" => "Aragua and Guárico states",
    "245" => "Carabobo state",
    "244" => "Aragua state",
    "243" => "Aragua and Carabobo states",
    "242" => "Carabobo state",
    "241" => "Carabobo state",
    "240" => "Apure and Barinas states",
    "239" => "Miranda state",
    "238" => "Guárico state",
    "237" => "Islands (Federal Dependencies)",
    "235" => "Guárico state",
    "234" => "Miranda state",
    "212" => "Capital District and Vargas and Miranda states"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "58", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Venezuela (Bolivarian Republic of)", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+58 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for FMC network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "501",
    "500",
    "400"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "58", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Venezuela (Bolivarian Republic of)", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+58 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "800")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "58", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Venezuela (Bolivarian Republic of)", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+58 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Venezuela (Bolivarian Republic of) phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
