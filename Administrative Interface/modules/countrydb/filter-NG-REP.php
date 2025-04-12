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
 * related to country database of Republic Wireless.
 *
 * Reference: https://www.itu.int/oth/T02020000FB/en (2012-01-24)
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
 * E.164 Republic Wireless country hook
 */
framework_add_filter ( "e164_identify_country_NG-REP", "e164_identify_country_NG_REP");

/**
 * E.164 Republic Wireless non geographic area number identification hook. This
 * hook is an e164_identify sub hook, called when the ISO3166 Alpha3 are "NG-REP"
 * (code for Republic Wireless). This hook will verify if phone number is valid,
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
function e164_identify_country_NG_REP ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Republic Wireless
   */
  if ( substr ( $parameters["Number"], 0, 8) != "+8835110")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Republic Wireless has 16 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 16)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Republic Wireless are considered international satellite phones
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "8835110", "NDC" => "", "Country" => "Republic Wireless", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_SATELLITE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 8, 4) . " " . substr ( $parameters["Number"], 12), "International" => "+883 5110 " . substr ( $parameters["Number"], 8, 4) . " " . substr ( $parameters["Number"], 12))));
}
?>
