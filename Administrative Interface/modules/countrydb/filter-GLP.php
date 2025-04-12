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
 * related to country database of Guadeloupe.
 *
 * Reference: https://www.itu.int/oth/T0202000058/en (2017-12-15)
 * Reference: https://extranet.arcep.fr/portail/LinkClick.aspx?fileticket=PBA1WK-wnOU%3d&tabid=217&portalid=0&mid=850
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
 * E.164 Guadeloupe country hook
 */
framework_add_filter ( "e164_identify_country_GLP", "e164_identify_country_GLP");

/**
 * E.164 Guadeloupe area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "GLP" (code for
 * Guadeloupe). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_GLP ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Guadeloupe
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+590")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Guadeloupe has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "691",
    "690"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "590", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Guadeloupe", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+590 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "59089" => "ZNE Basse-Terre",
    "59088" => "ZNE Basse-Terre",
    "59087" => "ZNE Saint-Martin",
    "59086" => "ZNE Basse-Terre",
    "59085" => "ZNE Basse-Terre",
    "59084" => "ZNE Basse-Terre",
    "59083" => "ZNE Basse-Terre",
    "59082" => "ZNE Basse-Terre",
    "59081" => "ZNE Basse-Terre",
    "59080" => "ZNE Basse-Terre",
    "59079" => "ZNE Saint-Martin",
    "59078" => "ZNE Basse-Terre",
    "59077" => "ZNE Saint-Martin",
    "59070" => "ZNE Basse-Terre",
    "59069" => "ZNE Basse-Terre",
    "59068" => "ZNE Basse-Terre",
    "59061" => "ZNE Basse-Terre",
    "59060" => "ZNE Basse-Terre",
    "59059" => "ZNE Basse-Terre",
    "59058" => "ZNE Saint-Martin",
    "59057" => "ZNE Basse-Terre",
    "59056" => "ZNE Saint-Martin",
    "59055" => "ZNE Basse-Terre",
    "59054" => "ZNE Basse-Terre",
    "59053" => "ZNE Basse-Terre",
    "59052" => "ZNE Saint-Martin",
    "59051" => "ZNE Saint-Martin",
    "59050" => "ZNE Saint-Martin",
    "59049" => "ZNE Basse-Terre",
    "59048" => "ZNE Basse-Terre",
    "59047" => "ZNE Basse-Terre",
    "59046" => "ZNE Basse-Terre",
    "59045" => "ZNE Basse-Terre",
    "59044" => "ZNE Basse-Terre",
    "59043" => "ZNE Saint-Martin",
    "59042" => "ZNE Basse-Terre",
    "59041" => "ZNE Basse-Terre",
    "59040" => "ZNE Basse-Terre",
    "59039" => "ZNE Basse-Terre",
    "59038" => "ZNE Basse-Terre",
    "59032" => "ZNE Basse-Terre",
    "59031" => "ZNE Basse-Terre",
    "59030" => "ZNE Saint-Martin",
    "59029" => "ZNE Saint-Martin",
    "59028" => "ZNE Basse-Terre",
    "59027" => "ZNE Saint-Martin",
    "59026" => "ZNE Basse-Terre",
    "59025" => "ZNE Basse-Terre",
    "59024" => "ZNE Basse-Terre",
    "59023" => "ZNE Basse-Terre",
    "59022" => "ZNE Basse-Terre",
    "59021" => "ZNE Basse-Terre",
    "59020" => "ZNE Basse-Terre",
    "59013" => "ZNE Saint-Martin",
    "59012" => "ZNE Basse-Terre",
    "59011" => "ZNE Basse-Terre",
    "59010" => "ZNE Basse-Terre",
    "59009" => "ZNE Saint-Martin",
    "59008" => "ZNE Basse-Terre",
    "59007" => "ZNE Saint-Martin",
    "59006" => "ZNE Basse-Terre",
    "59005" => "ZNE Basse-Terre",
    "59004" => "ZNE Basse-Terre",
    "59003" => "ZNE Basse-Terre",
    "59002" => "ZNE Basse-Terre",
    "59001" => "ZNE Basse-Terre",
    "59000" => "ZNE Saint-Martin"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "590", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Guadeloupe", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+590 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Guadeloupe phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
