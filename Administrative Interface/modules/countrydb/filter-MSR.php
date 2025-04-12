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
 * related to country database of Montserrat.
 *
 * Reference: https://www.itu.int/oth/T020200008F/en (2022-01-11)
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
 * E.164 Montserrat country hook
 */
framework_add_filter ( "e164_identify_country_MSR", "e164_identify_country_MSR");

/**
 * E.164 Montserratian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "MSR" (code for
 * Montserrat). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_MSR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Montserrat
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1664")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Montserrat has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "496" => "FLOW",
    "495" => "FLOW",
    "494" => "FLOW",
    "493" => "FLOW",
    "492" => "FLOW",
    "396" => "Digicel",
    "395" => "Digicel",
    "394" => "Digicel",
    "393" => "Digicel",
    "392" => "Digicel"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1664", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Montserrat", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 664 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC 4 digits SN
   */
  $prefixes = array (
    "491" => "FLOW",
    "415" => "FLOW",
    "414" => "FLOW",
    "391" => "Digicel"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1664", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Montserrat", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 664 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for audiotext network with 3 digits NDC 4 digits SN
   */
  $prefixes = array (
    "4927" => "FLOW",
    "4926" => "FLOW",
    "4925" => "FLOW",
    "4924" => "FLOW",
    "4923" => "FLOW",
    "4922" => "FLOW",
    "4921" => "FLOW",
    "4920" => "FLOW",
    "410" => "FLOW",
    "412" => "FLOW",
    "413" => "FLOW"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1664", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Montserrat", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_AUDIOTEXT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 664 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for FMC network with 3 digits NDC 4 digits SN
   */
  if ( substr ( $parameters["Number"], 5, 3) == "349")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1664", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Montserrat", "Area" => "", "City" => "", "Operator" => "FLOW", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 664 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * If reached here, number wasn't identified as a valid Montserratian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
