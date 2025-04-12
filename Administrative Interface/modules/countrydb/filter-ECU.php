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
 * related to country database of Ecuador.
 *
 * Reference: https://www.itu.int/oth/T020200003D/en (2012-09-20)
 * Reference: http://www.arcotel.gob.ec/wp-content/uploads/downloads/2013/07/plan_tecnico_fundamental_numeracion.pdf
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
 * E.164 Ecuador country hook
 */
framework_add_filter ( "e164_identify_country_ECU", "e164_identify_country_ECU");

/**
 * E.164 Ecuadorian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "ECU" (code for
 * Ecuador). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_ECU ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Ecuador
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+593")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "99" => "",
    "98" => "",
    "97" => "",
    "96" => "Telecsa S.A.",
    "95" => "Otecel S.A.",
    "94" => "Concecel S.A.",
    "93" => "",
    "92" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "593", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ecuador", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 1) . " " . substr ( $parameters["Number"], 6), "International" => "+593 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 1) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "27" => "Loja, Cañar, Azuay, Zamora, El Oro and Morona Santiago",
    "26" => "Carchi, Imbabura, Esmeraldas, Sucumbios, Napo and Orellana",
    "25" => "Manabí, Los Ríos and Galápagos",
    "24" => "Guayas",
    "23" => "Cotopaxi, Tungurahua, Chimborazo, Bolívar and Pastaza",
    "22" => "Pichincha"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "593", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Ecuador", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+593 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Ecuadorian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
