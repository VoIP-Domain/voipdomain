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
 * related to country database of Andorra.
 *
 * Reference: https://www.itu.int/oth/T0202000005/en (2016-07-08)
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
 * E.164 Andorra country hook
 */
framework_add_filter ( "e164_identify_country_AND", "e164_identify_country_AND");

/**
 * E.164 Andorrian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "AND" (code for Andorra). This
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
function e164_identify_country_AND ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Andorra
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+376")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for toll free numbers (1802XXXX)
   */
  if ( substr ( $parameters["Number"], 4, 4) == "1802" && strlen ( $parameters["Number"]) == 12)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "376", "NDC" => "1", "Country" => "Andorra", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_TOLL_FREE, "CallFormats" => array ( "Local" => "1 802 " . substr ( $parameters["Number"], 8), "International" => "+376 1 802 " . substr ( $parameters["Number"], 8))));
  }

  /**
   * Check for mobile network (690 XXX XXX)
   */
  if ( substr ( $parameters["Number"], 4, 3) == "690" && strlen ( $parameters["Number"]) == 13)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "376", "NDC" => "690", "Country" => "Andorra", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "690 " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10, 3), "International" => "+376 690 " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10, 3))));
  }

  /**
   * Check for mobile network ([36]XX XXX)
   */
  if ( ( substr ( $parameters["Number"], 4, 1) == "6" || substr ( $parameters["Number"], 4, 1) == "3") && strlen ( $parameters["Number"]) == 10)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "376", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Andorra", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+376 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for fixed line network ([78]XX XXX)
   */
  if ( ( substr ( $parameters["Number"], 4, 1) == "7" || substr ( $parameters["Number"], 4, 1) == "8") && strlen ( $parameters["Number"]) == 10)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "376", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Andorra", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+376 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * If reached here, number wasn't identified as a valid Andorrian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
