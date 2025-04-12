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
 * related to country database of Uzbekistan.
 *
 * Reference: https://www.itu.int/oth/T02020000E1/en (2006-07-20)
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
 * E.164 Uzbekistan country hook
 */
framework_add_filter ( "e164_identify_country_UZB", "e164_identify_country_UZB");

/**
 * E.164 Uzbekistan area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "UZB" (code for
 * Uzbekistan). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_UZB ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Uzbekistan
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+998")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Uzbekistan has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "99" => "Telephone computer network UzPAK",
    "98" => "Joint Venture Rubicon wireless Communications",
    "97" => "Joint Venture (Uzdunrobita)",
    "96" => "",
    "95" => "",
    "94" => "",
    "93" => "Joint Venture (Coscom)",
    "92" => "Joint Venture (Uzmacom)",
    "91" => "Buztel Company",
    "90" => "Daewoo Central Paging Company"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "998", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Uzbekistan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+998 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 3 or 4 digits NDC and 5 or 4 digits SN
   */
  $prefixes = array (
    "79" => "Navoi",
    "76" => "Surkhandaria",
    "75" => "Kashkadaria",
    "74" => "Andijan",
    "73" => "Fergana",
    "72" => "Jizak",
    "71" => "Tashkent",
    "69" => "Namangan",
    "67" => "Sirdaria",
    "66" => "Samarkand",
    "65" => "Bukhara",
    "62" => "Khorezm",
    "61" => "Karakalpakstan"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "998", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Uzbekistan", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+998 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Uzbekistan phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
