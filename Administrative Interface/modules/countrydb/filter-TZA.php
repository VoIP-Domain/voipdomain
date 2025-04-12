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
 * related to country database of Tanzania, United Republic of.
 *
 * Reference: https://www.itu.int/oth/T02020000CB/en (2022-08-12)
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
 * E.164 Tanzania, United Republic of country hook
 */
framework_add_filter ( "e164_identify_country_TZA", "e164_identify_country_TZA");

/**
 * E.164 Tanzania, United Republic of area number identification hook. This hook
 * is an e164_identify sub hook, called when the ISO3166 Alpha3 are "TZA" (code
 * for Tanzania, United Republic of). This hook will verify if phone number is
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
function e164_identify_country_TZA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Tanzania, United Republic of
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+255")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Tanzania, United Republic of has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "79" => "",
    "78" => "Airtel Tanzania",
    "77" => "Zanzibar Telecom",
    "76" => "Vodacom Tanzania",
    "75" => "Vodacom Tanzania",
    "74" => "Vodacom Tanzania",
    "73" => "Tanzania Telecommunications Corporation",
    "72" => "",
    "71" => "MIC Tanzania",
    "70" => "",
    "69" => "Airtel Tanzania",
    "68" => "Airtel Tanzania",
    "67" => "MIC Tanzania",
    "66" => "Smile Communications",
    "65" => "MIC Tanzania",
    "64" => "",
    "63" => "",
    "62" => "Viettel Tanzania",
    "61" => "Viettel Tanzania",
    "60" => "",
    "5" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "255", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Tanzania, United Republic of", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+255 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "29" => "",
    "28" => "Mwanza, Shinyanga, Mara, Geita, Simiyu, Kagera, Kigoma",
    "27" => "Arusha, Manyara, Kilimanjaro, Tanga",
    "26" => "Dodoma, Iringa, Njombe, Singida, Tabora",
    "25" => "Mbeya, Songwe, Ruvuma, Katavi, Rukwa",
    "24" => "Zanzibar (Unguja and Pemba)",
    "23" => "Coast, Morogoro, Lindi, Mtwara",
    "22" => "Dar-Es-Salaam",
    "21" => "",
    "20" => ""
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "255", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Tanzania, United Republic of", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+255 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 2) == "41")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "255", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Tanzania, United Republic of", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+255 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
  }

  /**
   * Check for PRN network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "808",
    "90"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "255", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Tanzania, United Republic of", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+255 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Tanzania, United Republic of phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
