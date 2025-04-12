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
 * related to country database of Mali.
 *
 * Reference: https://www.itu.int/oth/T0202000083/en (2018-04-27)
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
 * E.164 Mali country hook
 */
framework_add_filter ( "e164_identify_country_MLI", "e164_identify_country_MLI");

/**
 * E.164 Malian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MLI" (code for Mali). This
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
function e164_identify_country_MLI ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Mali
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+223")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Mali has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network
   */
  $prefixes = array (
    "2079" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "217" => array ( "Area" => "", "Operator" => "Sotelma SA"),
    "99" => array ( "Area" => "", "Operator" => "Sotelma SA"),
    "98" => array ( "Area" => "", "Operator" => "Sotelma SA"),
    "97" => array ( "Area" => "", "Operator" => "Sotelma SA"),
    "96" => array ( "Area" => "", "Operator" => "Sotelma SA"),
    "95" => array ( "Area" => "", "Operator" => "Sotelma SA"),
    "94" => array ( "Area" => "", "Operator" => "Orange Mali SA"),
    "93" => array ( "Area" => "", "Operator" => "Orange Mali SA"),
    "92" => array ( "Area" => "", "Operator" => "Orange Mali SA"),
    "91" => array ( "Area" => "", "Operator" => "Orange Mali SA"),
    "90" => array ( "Area" => "", "Operator" => "Orange Mali SA"),
    "89" => array ( "Area" => "", "Operator" => "Sotelma SA"),
    "83" => array ( "Area" => "", "Operator" => "Orange Mali SA"),
    "82" => array ( "Area" => "", "Operator" => "Orange Mali SA"),
    "50" => array ( "Area" => "", "Operator" => "Atel SA"),
    "7" => array ( "Area" => "", "Operator" => "Orange Mali SA"),
    "6" => array ( "Area" => "", "Operator" => "Sotelma SA")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "223", "NDC" => $prefix, "Country" => "Mali", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+223 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 3 or 4 digits NDC and 5 or 4 digits SN
   */
  $prefixes = array (
    "2127" => array ( "Area" => "Koulikoro Region", "Operator" => "Sotelma SA"),
    "2126" => array ( "Area" => "Koulikoro Region", "Operator" => "Sotelma SA"),
    "2078" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "2077" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "2076" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "2075" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "2074" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "2073" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "2072" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "2071" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "2070" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA"),
    "449" => array ( "Area" => "Bamako District", "Operator" => "Orange Mali SA"),
    "443" => array ( "Area" => "Bamako District", "Operator" => "Orange Mali SA"),
    "442" => array ( "Area" => "Bamako District", "Operator" => "Orange Mali SA"),
    "441" => array ( "Area" => "", "Operator" => "Orange Mali SA"),
    "404" => array ( "Area" => "", "Operator" => "Atel SA"),
    "403" => array ( "Area" => "", "Operator" => "Atel SA"),
    "402" => array ( "Area" => "", "Operator" => "Atel SA"),
    "401" => array ( "Area" => "", "Operator" => "Atel SA"),
    "400" => array ( "Area" => "", "Operator" => "Atel SA"),
    "219" => array ( "Area" => "Tombouctou Region", "Operator" => "Sotelma SA"),
    "218" => array ( "Area" => "Gao and Kidal Regions", "Operator" => "Sotelma SA"),
    "216" => array ( "Area" => "Sikasso Region", "Operator" => "Sotelma SA"),
    "215" => array ( "Area" => "Kayes Region", "Operator" => "Sotelma SA"),
    "214" => array ( "Area" => "Mopti Region", "Operator" => "Sotelma SA"),
    "202" => array ( "Area" => "Bamako District", "Operator" => "Sotelma SA")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "223", "NDC" => $prefix, "Country" => "Mali", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+223 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Malian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
