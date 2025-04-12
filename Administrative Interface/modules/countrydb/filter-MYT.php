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
 * related to country database of Mayotte.
 *
 * Reference: https://www.itu.int/oth/T020200004B/en (2017-12-15)
 *            https://extranet.arcep.fr/portail/LinkClick.aspx?fileticket=PBA1WK-wnOU%3d&tabid=217&portalid=0&mid=850
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
 * E.164 Mayotte country hook
 */
framework_add_filter ( "e164_identify_country_MYT", "e164_identify_country_MYT");

/**
 * E.164 Mayotte area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MYT" (code for Mayotte). This
 * hook will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_MYT ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Mayotte
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+262")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Mayotte has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "63999" => "Mayotte",
    "63997" => "Mayotte",
    "63996" => "Mayotte",
    "63995" => "Mayotte",
    "63994" => "Mayotte",
    "63990" => "Mayotte",
    "63950" => "Mayotte",
    "63940" => "Mayotte",
    "63939" => "Mayotte",
    "63930" => "Mayotte",
    "63919" => "Mayotte",
    "63911" => "Mayotte",
    "63910" => "Mayotte",
    "63909" => "Mayotte",
    "63907" => "Mayotte",
    "63906" => "Mayotte",
    "63905" => "Mayotte",
    "63904" => "Mayotte",
    "63903" => "Mayotte",
    "63902" => "Mayotte",
    "63901" => "Mayotte",
    "63900" => "Mayotte",
    "6397" => "Mayotte",
    "6396" => "Mayotte",
    "6392" => "Mayotte"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "262", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Mayotte", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+262 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "26980" => "ZNE Mayotte",
    "26970" => "ZNE Mayotte",
    "26969" => "ZNE Mayotte",
    "26968" => "ZNE Mayotte",
    "26967" => "ZNE Mayotte",
    "26966" => "Mayotte",
    "26965" => "Mayotte",
    "26964" => "ZNE Mayotte",
    "26963" => "ZNE Mayotte",
    "26962" => "ZNE Mayotte",
    "26961" => "ZNE Mayotte",
    "26960" => "ZNE Mayotte",
    "26952" => "ZNE Mayotte",
    "26951" => "ZNE Mayotte",
    "26950" => "ZNE Mayotte",
    "26907" => "ZNE Mayotte",
    "26906" => "ZNE Mayotte"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "262", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Mayotte", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+262 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Mayotte phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
