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
 * related to country database of Madagascar.
 *
 * Reference: https://www.itu.int/oth/T020200007F/en (2013-10-07)
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
 * E.164 Madagascar country hook
 */
framework_add_filter ( "e164_identify_country_MDG", "e164_identify_country_MDG");

/**
 * E.164 Madagascarian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "MDG" (code for
 * Madagascar). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_MDG ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Madagascar
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+261")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Madagascar has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "39" => "Blueline MVNO",
    "34" => "Telma mobile",
    "33" => "Airtel Madagascar",
    "32" => "Orange Madagascar"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "261", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Madagascar", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+261 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "20729" => array ( "area" => "Fianarantsoa", "city" => "Mananjary", "operator" => "Telecom Malagasy"),
    "20722" => array ( "area" => "Fianarantsoa", "city" => "Manakara", "operator" => "Telecom Malagasy"),
    "2095" => array ( "area" => "Toliary", "city" => "Morondava", "operator" => "Telecom Malagasy"),
    "2094" => array ( "area" => "Toliary", "city" => "Toliary", "operator" => "Telecom Malagasy"),
    "2092" => array ( "area" => "Toliary", "city" => "Tolagnaro", "operator" => "Telecom Malagasy"),
    "2088" => array ( "area" => "Antsiranana", "city" => "Sambava", "operator" => "Telecom Malagasy"),
    "2086" => array ( "area" => "Antsiranana", "city" => "Nosy Be", "operator" => "Telecom Malagasy"),
    "2082" => array ( "area" => "Antsiranana", "city" => "Antsiranana", "operator" => "Telecom Malagasy"),
    "2075" => array ( "area" => "Fianarantsoa", "city" => "Fianarantsoa", "operator" => "Telecom Malagasy"),
    "2073" => array ( "area" => "Fianarantsoa", "city" => "Farafangana", "operator" => "Telecom Malagasy"),
    "2069" => array ( "area" => "Mahajanga", "city" => "Maintirano", "operator" => "Telecom Malagasy"),
    "2067" => array ( "area" => "Mahajanga", "city" => "Antsohihy", "operator" => "Telecom Malagasy"),
    "2062" => array ( "area" => "Mahajanga", "city" => "Mahajanga", "operator" => "Telecom Malagasy"),
    "2057" => array ( "area" => "Toamasina", "city" => "Sainte-Marie", "operator" => "Telecom Malagasy"),
    "2056" => array ( "area" => "Toamasina", "city" => "Moramanga", "operator" => "Telecom Malagasy"),
    "2054" => array ( "area" => "Toamasina", "city" => "Ambatondrazaka", "operator" => "Telecom Malagasy"),
    "2053" => array ( "area" => "Toamasina", "city" => "Toamasina", "operator" => "Telecom Malagasy"),
    "202" => array ( "area" => "Antananarivo", "city" => "Antananarivo", "operator" => "Telecom Malagasy")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "261", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Madagascar", "Area" => $data["area"], "City" => $data["city"], "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+261 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for VSAT 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "22" => "Gulfsat Madagascar"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "261", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Madagascar", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+261 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Madagascarian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
