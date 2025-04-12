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
 * related to country database of Arruba.
 *
 * Reference: https://www.itu.int/oth/T020200000B/en (2011-04-18)
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
 * E.164 Arruba country hook
 */
framework_add_filter ( "e164_identify_country_ABW", "e164_identify_country_ABW");

/**
 * E.164 Arrubian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "ABW" (code for Arruba). This
 * hook will verify if phone number is valid, returning the area code, area
 * name, phone number, others number related information and if possible, the
 * number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_ABW ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Arruba
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+297")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Arruba has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for fixed line network
   */
  $prefixes = array (
    "58" => "Setar N. V.",
    "56" => "Setar N. V.",
    "52" => "Setar N. V."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "297", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Arruba", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . "-" . substr ( $parameters["Number"], 7), "International" => "+297 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for mobile network
   */
  $prefixes = array (
    "998" => "Setar N. V.",
    "997" => "Setar N. V.",
    "996" => "Setar N. V.",
    "995" => "Setar N. V.",
    "690" => "Setar N. V.",
    "661" => "Setar N. V.",
    "660" => "Setar N. V.",
    "641" => "New Millennium Telecom Services N.V./Digicel Aruba",
    "640" => "New Millennium Telecom Services N.V./Digicel Aruba",
    "630" => "New Millennium Telecom Services N.V./Digicel Aruba",
    "622" => "DTH Television & Telecommunications N.V./MIO Aruba",
    "600" => "DTH Television & Telecommunications N.V./MIO Aruba",
    "598" => "Setar N. V.",
    "597" => "Setar N. V.",
    "594" => "Setar N. V.",
    "593" => "Setar N. V.",
    "592" => "Setar N. V.",
    "74" => "New Millennium Telecom Services N.V./Digicel Aruba",
    "73" => "New Millennium Telecom Services N.V./Digicel Aruba"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "297", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Arruba", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . "-" . substr ( $parameters["Number"], 7), "International" => "+297 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for VoIP network
   */
  $prefixes = array (
    "501" => "Setar N. V.",
    "28" => "Setar N. V."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "297", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Arruba", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . "-" . substr ( $parameters["Number"], 7), "International" => "+297 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Arrubian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
