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
 * related to country database of Paraguay.
 *
 * Reference: https://www.itu.int/oth/T02020000A5/en (2021-10-07)
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
 * E.164 Paraguay country hook
 */
framework_add_filter ( "e164_identify_country_PRY", "e164_identify_country_PRY");

/**
 * E.164 Paraguaian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "PRY" (code for
 * Paraguay). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_PRY ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Paraguay
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+595")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Paraguay has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 1 digit NDC and 8 digits SN
   */
  $prefixes = array (
    "999" => "",
    "998" => "",
    "997" => "",
    "996" => "",
    "995" => "Claro",
    "994" => "",
    "993" => "Claro",
    "992" => "Claro",
    "991" => "Claro",
    "990" => "",
    "989" => "",
    "988" => "",
    "987" => "",
    "986" => "",
    "985" => "Tigo",
    "984" => "Tigo",
    "983" => "Tigo",
    "982" => "Tigo",
    "981" => "Tigo",
    "980" => "",
    "979" => "",
    "978" => "",
    "977" => "",
    "976" => "Personal",
    "975" => "Personal",
    "974" => "Personal",
    "973" => "Personal",
    "972" => "Personal",
    "971" => "Personal",
    "970" => "",
    "969" => "",
    "968" => "",
    "967" => "",
    "966" => "",
    "965" => "",
    "964" => "",
    "963" => "VOX",
    "962" => "",
    "961" => "VOX",
    "960" => "",
    "95" => "",
    "94" => "",
    "93" => "",
    "92" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "595", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Paraguay", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+595 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "21" => "Copaco S.A."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "595", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Paraguay", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+595 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for VoIP network with 1 digits NDC and 8 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 1) == "8")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "595", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Paraguay", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+595 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Paraguaian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
