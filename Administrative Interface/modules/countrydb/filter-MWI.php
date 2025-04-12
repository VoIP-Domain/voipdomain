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
 * related to country database of Malawi.
 *
 * Reference: https://www.itu.int/oth/T0202000080/en (2018-01-31)
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
 * E.164 Malawi country hook
 */
framework_add_filter ( "e164_identify_country_MWI", "e164_identify_country_MWI");

/**
 * E.164 Malawian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MWI" (code for Malawi). This
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
function e164_identify_country_MWI ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Malawi
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+265")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 9 digits SN
   */
  $prefixes = array (
    "111" => "Malawi Telecommunications Ltd.",
    "99" => "Zain Malawi Limited",
    "88" => "Telekom Networks Malawi Plc",
    "77" => "Globally Advanced Integrated Networks Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "265", "NDC" => "", "Country" => "Malawi", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+265 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with no NDC and 7 or 9 digits SN
   */
  $prefixes = array (
    "21" => array ( "operator" => "Access Communications Ltd", "length" => 9),
    "19" => array ( "operator" => "Malawi Telecommunications Ltd.", "length" => 7),
    "18" => array ( "operator" => "Malawi Telecommunications Ltd.", "length" => 7),
    "17" => array ( "operator" => "Malawi Telecommunications Ltd.", "length" => 7),
    "16" => array ( "operator" => "Malawi Telecommunications Ltd.", "length" => 7),
    "15" => array ( "operator" => "Malawi Telecommunications Ltd.", "length" => 7),
    "13" => array ( "operator" => "Malawi Telecommunications Ltd.", "length" => 7),
    "12" => array ( "operator" => "Malawi Telecommunications Ltd.", "length" => 7)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["length"])
    {
      switch ( $data["length"])
      {
        case 7:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+265 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8));
          break;
        case 9:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+265 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9));
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "265", "NDC" => "", "Country" => "Malawi", "Area" => "", "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * Check for VoIP network with no NDC and 9 digits SN
   */
  $prefixes = array (
    "31" => "Telekom Networks Malawi Plc"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "265", "NDC" => "", "Country" => "Malawi", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+265 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Malawian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
