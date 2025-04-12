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
 * related to country database of Bahrain.
 *
 * Reference: https://www.itu.int/oth/T0202000011/en (2019-02-25)
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
 * E.164 Bahrain country hook
 */
framework_add_filter ( "e164_identify_country_BHR", "e164_identify_country_BHR");

/**
 * E.164 Bahrainian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "BHR" (code for
 * Bahrain). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_BHR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Bahrain
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+973")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Bahrain has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "389" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "388" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "387" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "384" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "383" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "382" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "381" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "380" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "359" => "VIVA Bahrain B.S.C. Closed",
    "356" => "VIVA Bahrain B.S.C. Closed",
    "355" => "VIVA Bahrain B.S.C. Closed",
    "353" => "VIVA Bahrain B.S.C. Closed",
    "351" => "VIVA Bahrain B.S.C. Closed",
    "350" => "VIVA Bahrain B.S.C. Closed",
    "345" => "VIVA Bahrain B.S.C. Closed",
    "344" => "VIVA Bahrain B.S.C. Closed",
    "343" => "VIVA Bahrain B.S.C. Closed",
    "342" => "VIVA Bahrain B.S.C. Closed",
    "341" => "VIVA Bahrain B.S.C. Closed",
    "340" => "VIVA Bahrain B.S.C. Closed",
    "323" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "322" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "321" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "320" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "39" => "Bahrain Telecommunications Company (BATELCO) B.S.C.",
    "37" => "Zain Bahrain B.S.C. Closed",
    "36" => "Zain Bahrain B.S.C. Closed",
    "33" => "VIVA Bahrain B.S.C. Closed"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "973", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Bahrain", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+973 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "1619" => "Kalam Telecom Bahrain B.S.C. Closed",
    "1616" => "Kalam Telecom Bahrain B.S.C. Closed",
    "1610" => "Kalam Telecom Bahrain B.S.C. Closed",
    "1607" => "Nuetel Communications S.P.C.",
    "1606" => "Nuetel Communications S.P.C.",
    "1603" => "Nuetel Communications S.P.C.",
    "1602" => "Nuetel Communications S.P.C.",
    "1601" => "Nuetel Communications S.P.C.",
    "1600" => "Nuetel Communications S.P.C.",
    "1311" => "Viva",
    "1310" => "Viva",
    "166" => "Lightspeed Communications W.L.L.",
    "165" => "2Connect W.L.L.",
    "136" => "Zain Bahrain B.S.C. Closed",
    "133" => "Etisalcom Bahrain Company W.L.L.",
    "17" => "Bahrain Telecommunications Company (BATELCO) B.S.C."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "973", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Bahrain", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+973 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Bahrainian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
