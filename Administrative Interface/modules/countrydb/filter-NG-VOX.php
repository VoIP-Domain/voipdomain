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
 * related to country database of Voxbone SA.
 *
 * Reference: https://www.itu.int/oth/T02020000F3/en (2011-12-16)
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
 * E.164 Voxbone SA country hook
 */
framework_add_filter ( "e164_identify_country_NG-VOX", "e164_identify_country_NG_VOX");

/**
 * E.164 Voxbone SA non geographic area number identification hook. This
 * hook is an e164_identify sub hook, called when the ISO3166 Alpha3 are "NG-VOX"
 * (code for Voxbone SA). This hook will verify if phone number is valid,
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
function e164_identify_country_NG_VOX ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Voxbone SA
   */
  if ( substr ( $parameters["Number"], 0, 8) != "+8835100")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Voxbone SA has 14 or 17 digits E.164 format
   */
  if ( ! ( strlen ( $parameters["Number"]) == 14 || strlen ( $parameters["Number"]) == 17))
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Voxbone SA are considered international satellite phones
   */
  if ( strlen ( $parameters["Number"]) == 14)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "8835100", "NDC" => "", "Country" => "Voxbone SA", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_SATELLITE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+883 510 0" . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
  } else {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "8835100", "NDC" => "", "Country" => "Voxbone SA", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_SATELLITE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10, 3) . " " . substr ( $parameters["Number"], 13), "International" => "+883 510 0" . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10, 3) . " " . substr ( $parameters["Number"], 13))));
  }
}
?>
