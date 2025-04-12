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
 * related to country database of Cuba.
 *
 * Reference: https://www.itu.int/oth/T0202000033/en (2012-02-20)
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
 * E.164 Cuba country hook
 */
framework_add_filter ( "e164_identify_country_CUB", "e164_identify_country_CUB");

/**
 * E.164 Cuban area number identification hook. This hook is an e164_identify sub
 * hook, called when the ISO3166 Alpha3 are "CUB" (code for Cuba). This hook will
 * verify if phone number is valid, returning the area code, area name, phone
 * number, others number related information and if possible, the number type
 * (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_CUB ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Cuba
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+53")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 1 digit NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 1) == "5" && strlen ( $parameters["Number"]) == 11)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "53", "NDC" => substr ( $parameters["Number"], 3, 1), "Country" => "Cuba", "Area" => "", "City" => "", "Operator" => "Cubacel", "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 4) . " " . substr ( $parameters["Number"], 7), "International" => "+53 " . substr ( $parameters["Number"], 3, 4) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "48" => array ( "area" => "Pinar del Río", "city" => "", "maximum" => 8, "minimum" => 6),
    "47" => array ( "area" => "La Habana", "city" => "", "maximum" => 8, "minimum" => 6),
    "46" => array ( "area" => "Isla de La Juventud", "city" => "", "maximum" => 8, "minimum" => 8),
    "45" => array ( "area" => "Matanzas", "city" => "", "maximum" => 8, "minimum" => 7),
    "43" => array ( "area" => "Cienfuegos", "city" => "", "maximum" => 8, "minimum" => 8),
    "42" => array ( "area" => "Villa Clara", "city" => "", "maximum" => 8, "minimum" => 7),
    "41" => array ( "area" => "Sancti Spíritus", "city" => "", "maximum" => 8, "minimum" => 7),
    "33" => array ( "area" => "Ciego de Ávila", "city" => "", "maximum" => 8, "minimum" => 6),
    "32" => array ( "area" => "Camagüey", "city" => "", "maximum" => 8, "minimum" => 6),
    "31" => array ( "area" => "Las Tunas", "city" => "", "maximum" => 8, "minimum" => 8),
    "24" => array ( "area" => "Holguín", "city" => "", "maximum" => 8, "minimum" => 7),
    "23" => array ( "area" => "Granma", "city" => "", "maximum" => 8, "minimum" => 7),
    "22" => array ( "area" => "Santiago de Cuba", "city" => "", "maximum" => 8, "minimum" => 7),
    "21" => array ( "area" => "Guantánamo", "city" => "", "maximum" => 8, "minimum" => 7),
    "7" => array ( "area" => "La Habana", "city" => "Habana", "maximum" => 8, "minimum" => 7)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= $data["minimum"] + 3 && strlen ( $parameters["Number"]) <= $data["maximum"] + 3)
    {
      if ( strlen ( $prefix) == 1)
      {
        $callformats = array ( "Local" => substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+53 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7));
      } else {
        $callformats = array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+53 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8));
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "53", "NDC" => (string) $prefix, "Country" => "Cuba", "Area" => $data["area"], "City" => $data["city"], "Operator" => "Empresa de Telecomunicaciones de Cuba S.A.", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Cuban phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
