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
 * related to country database of Swaziland.
 *
 * Reference: https://www.itu.int/oth/T02020000C6/en (2018-03-23)
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
 * E.164 Swaziland country hook
 */
framework_add_filter ( "e164_identify_country_SWZ", "e164_identify_country_SWZ");

/**
 * E.164 Swazilandian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "SWZ" (code for
 * Swaziland). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_SWZ ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Swaziland
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+268")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Swaziland has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "79" => "Swazi Mobile Ltd.",
    "78" => "Swazi MTN Ltd.",
    "77" => "SPTC",
    "76" => "Swazi MTN Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "268", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Swaziland", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+268 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "359" => array ( "area" => "Manzini", "operator" => "Swazi MTN Ltd."),
    "358" => array ( "area" => "Manzini", "operator" => "Swazi MTN Ltd."),
    "357" => array ( "area" => "Manzini", "operator" => "Swazi MTN Ltd."),
    "356" => array ( "area" => "Manzini", "operator" => "Swazi MTN Ltd."),
    "355" => array ( "area" => "Manzini", "operator" => "Swazi MTN Ltd."),
    "354" => array ( "area" => "Manzini", "operator" => "Swazi Mobile Ltd."),
    "353" => array ( "area" => "Manzini", "operator" => "Swazi Mobile Ltd."),
    "352" => array ( "area" => "Manzini", "operator" => "Swazi Mobile Ltd."),
    "351" => array ( "area" => "Manzini", "operator" => "Swazi Mobile Ltd."),
    "350" => array ( "area" => "Manzini", "operator" => "Swazi Mobile Ltd."),
    "349" => array ( "area" => "Hhohho", "operator" => "Swazi MTN Ltd."),
    "348" => array ( "area" => "Hhohho", "operator" => "Swazi MTN Ltd."),
    "347" => array ( "area" => "Hhohho", "operator" => "Swazi MTN Ltd."),
    "346" => array ( "area" => "Hhohho", "operator" => "Swazi MTN Ltd."),
    "345" => array ( "area" => "Hhohho", "operator" => "Swazi MTN Ltd."),
    "344" => array ( "area" => "Hhohho", "operator" => "Swazi Mobile Ltd."),
    "343" => array ( "area" => "Hhohho", "operator" => "Swazi Mobile Ltd."),
    "342" => array ( "area" => "Hhohho", "operator" => "Swazi Mobile Ltd."),
    "341" => array ( "area" => "Hhohho", "operator" => "Swazi Mobile Ltd."),
    "340" => array ( "area" => "Hhohho", "operator" => "Swazi Mobile Ltd."),
    "339" => array ( "area" => "Lubombo", "operator" => "Swazi MTN Ltd."),
    "338" => array ( "area" => "Lubombo", "operator" => "Swazi MTN Ltd."),
    "337" => array ( "area" => "Lubombo", "operator" => "Swazi MTN Ltd."),
    "336" => array ( "area" => "Lubombo", "operator" => "Swazi MTN Ltd."),
    "335" => array ( "area" => "Lubombo", "operator" => "Swazi MTN Ltd."),
    "334" => array ( "area" => "Lubombo", "operator" => "Swazi Mobile Ltd."),
    "333" => array ( "area" => "Lubombo", "operator" => "Swazi Mobile Ltd."),
    "332" => array ( "area" => "Lubombo", "operator" => "Swazi Mobile Ltd."),
    "331" => array ( "area" => "Lubombo", "operator" => "Swazi Mobile Ltd."),
    "330" => array ( "area" => "Lubombo", "operator" => "Swazi Mobile Ltd."),
    "329" => array ( "area" => "Shiselweni", "operator" => "Swazi MTN Ltd."),
    "328" => array ( "area" => "Shiselweni", "operator" => "Swazi MTN Ltd."),
    "327" => array ( "area" => "Shiselweni", "operator" => "Swazi MTN Ltd."),
    "326" => array ( "area" => "Shiselweni", "operator" => "Swazi MTN Ltd."),
    "325" => array ( "area" => "Shiselweni", "operator" => "Swazi MTN Ltd."),
    "324" => array ( "area" => "Shiselweni", "operator" => "Swazi Mobile Ltd."),
    "323" => array ( "area" => "Shiselweni", "operator" => "Swazi Mobile Ltd."),
    "322" => array ( "area" => "Shiselweni", "operator" => "Swazi Mobile Ltd."),
    "321" => array ( "area" => "Shiselweni", "operator" => "Swazi Mobile Ltd."),
    "320" => array ( "area" => "Shiselweni", "operator" => "Swazi Mobile Ltd."),
    "25" => array ( "area" => "Manzini", "operator" => "SPTC"),
    "24" => array ( "area" => "Hhohho", "operator" => "SPTC"),
    "23" => array ( "area" => "Lubombo", "operator" => "SPTC"),
    "22" => array ( "area" => "Shiselweni", "operator" => "SPTC")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "268", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Swaziland", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+268 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "70"
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "268", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Swaziland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+268 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Swazilandian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
