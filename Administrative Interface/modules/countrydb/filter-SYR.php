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
 * related to country database of Syrian Arab Republic.
 *
 * Reference: https://www.itu.int/oth/T02020000C9/en (2022-09-30)
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
 * E.164 Syrian Arab Republic country hook
 */
framework_add_filter ( "e164_identify_country_SYR", "e164_identify_country_SYR");

/**
 * E.164 Syrian Arab Republicn area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "SYR" (code for
 * Syrian Arab Republic). This hook will verify if phone number is valid,
 * returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_SYR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Syrian Arab Republic
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+963")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Syrian Arab Republic has 12 or 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 12 || strlen ( $parameters["Number"]) > 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "99" => "Syriatel",
    "98" => "Syriatel",
    "97" => "",
    "96" => "MTN Syria",
    "95" => "MTN Syria",
    "94" => "MTN Syria",
    "93" => "Syriatel",
    "92" => "WAFA Telecom",
    "91" => "WAFA Telecom",
    "90" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "963", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Syrian Arab Republic", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 4) . " " . substr ( $parameters["Number"], 10), "International" => "+963 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 4) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "52" => array ( "Length" => 6, "Area" => "Al Hasakeh"),
    "51" => array ( "Length" => 6, "Area" => "Deir Elzzour"),
    "48" => array ( "Length" => 7, "Area" => ""),
    "47" => array ( "Length" => 7, "Area" => ""),
    "43" => array ( "Length" => 7, "Area" => "Tartous"),
    "41" => array ( "Length" => 7, "Area" => "Latakia"),
    "33" => array ( "Length" => 7, "Area" => "Hama"),
    "31" => array ( "Length" => 7, "Area" => "Homs"),
    "23" => array ( "Length" => 6, "Area" => "Edleb"),
    "22" => array ( "Length" => 6, "Area" => "Al Rakkah"),
    "21" => array ( "Length" => 7, "Area" => "Aleppo"),
    "16" => array ( "Length" => 6, "Area" => "Al Sweda"),
    "15" => array ( "Length" => 7, "Area" => "Daraa"),
    "14" => array ( "Length" => 7, "Area" => "Al Quneitra"),
    "11" => array ( "Length" => 7, "Area" => "Damascus & Rural")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 6 + $data["Length"] && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "963", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Syrian Arab Republic", "Area" => $data["Area"], "City" => "", "Operator" => "Syrian Telecom", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+963 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Syrian Arab Republicn phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
