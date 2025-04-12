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
 * related to country database of Micronesia (Federated States of).
 *
 * Reference: https://www.itu.int/oth/T020200008B/en (2006-07-20)
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
 * E.164 Micronesia (Federated States of) country hook
 */
framework_add_filter ( "e164_identify_country_FSM", "e164_identify_country_FSM");

/**
 * E.164 Micronesian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "FSM" (code for
 * Micronesia (Federated States of)). This hook will verify if phone number is
 * valid, returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_FSM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Micronesia (Federated States of)
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+691")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Micronesia (Federated States of) has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for landline and mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "370" => "Kosrae",
    "350" => "Yap",
    "330" => "Chuuk",
    "320" => "Pohnpei"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && (int) substr ( $parameters["Number"], 7, 1) >= 1)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "691", "NDC" => $prefix, "Country" => "Micronesia (Federated States of)", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE + VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+691 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }
  if ( (int) substr ( $parameters["Number"], 4) >= 9201000 && (int) substr ( $parameters["Number"], 4) <= 9299999)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "691", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Micronesia (Federated States of)", "Area" => "Pohnpei and Pohnpei outer island", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE + VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+691 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }
  if ( (int) substr ( $parameters["Number"], 4) >= 9301000 && (int) substr ( $parameters["Number"], 4) <= 9399999)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "691", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Micronesia (Federated States of)", "Area" => "Chuuk and Chuuk outer island", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE + VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+691 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }
  if ( (int) substr ( $parameters["Number"], 4) >= 9501000 && (int) substr ( $parameters["Number"], 4) <= 9599999)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "691", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Micronesia (Federated States of)", "Area" => "Yap and Yap outer island", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE + VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+691 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  if ( (int) substr ( $parameters["Number"], 4) >= 9701000 && (int) substr ( $parameters["Number"], 4) <= 9799999)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "691", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Micronesia (Federated States of)", "Area" => "Kosrae", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+691 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * If reached here, number wasn't identified as a valid Micronesian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
