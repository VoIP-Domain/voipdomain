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
 * related to country database of Vanuatu.
 *
 * Reference: https://www.itu.int/oth/T02020000E2/en (2021-12-14)
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
 * E.164 Vanuatu country hook
 */
framework_add_filter ( "e164_identify_country_VUT", "e164_identify_country_VUT");

/**
 * E.164 Vanuatu area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "VUT" (code for Vanuatu). This
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
function e164_identify_country_VUT ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Vanuatu
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+678")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Vanuatu has 9, 10 or 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 9 || strlen ( $parameters["Number"]) > 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "900" => "WanTok Network Ltd.",
    "77" => "Telecom Vanuatu Ltd.",
    "76" => "Telecom Vanuatu Ltd.",
    "75" => "Telecom Vanuatu Ltd.",
    "74" => "Telecom Vanuatu Ltd.",
    "73" => "Telecom Vanuatu Ltd.",
    "72" => "",
    "71" => "Telecom Vanuatu Ltd.",
    "70" => "Telecom Vanuatu Ltd.",
    "8" => "WanTok Network Ltd.",
    "5" => "Digicel Vanuatu Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "678", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Vanuatu", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+678 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 3 digits SN
   */
  $prefixes = array (
    "489" => array ( "Area" => "Malampa province", "Operator" => "Telecom Vanuatu Ltd."),
    "488" => array ( "Area" => "Malampa province", "Operator" => "Telecom Vanuatu Ltd."),
    "487" => array ( "Area" => "Malampa province", "Operator" => "Telecom Vanuatu Ltd."),
    "486" => array ( "Area" => "Malampa province", "Operator" => "Telecom Vanuatu Ltd."),
    "485" => array ( "Area" => "Malampa province", "Operator" => "Telecom Vanuatu Ltd."),
    "484" => array ( "Area" => "Malampa province", "Operator" => "Telecom Vanuatu Ltd."),
    "388" => array ( "Area" => "Penama and Torba provinces", "Operator" => "Telecom Vanuatu Ltd."),
    "387" => array ( "Area" => "Penama and Torba provinces", "Operator" => "Telecom Vanuatu Ltd."),
    "386" => array ( "Area" => "Penama and Torba provinces", "Operator" => "Telecom Vanuatu Ltd."),
    "385" => array ( "Area" => "Penama and Torba provinces", "Operator" => "Telecom Vanuatu Ltd."),
    "384" => array ( "Area" => "Penama and Torba provinces", "Operator" => "Telecom Vanuatu Ltd."),
    "383" => array ( "Area" => "Penama and Torba provinces", "Operator" => "Telecom Vanuatu Ltd."),
    "382" => array ( "Area" => "Penama and Torba provinces", "Operator" => "Telecom Vanuatu Ltd."),
    "381" => array ( "Area" => "Penama and Torba provinces", "Operator" => "Telecom Vanuatu Ltd."),
    "380" => array ( "Area" => "Penama and Torba provinces", "Operator" => "Telecom Vanuatu Ltd."),
    "88" => array ( "Area" => "Tafea province", "Operator" => "Telecom Vanuatu Ltd."),
    "37" => array ( "Area" => "Luganville area", "Operator" => "Telecom Vanuatu Ltd."),
    "36" => array ( "Area" => "Sanma province", "Operator" => "Telecom Vanuatu Ltd."),
    "35" => array ( "Area" => "", "Operator" => "Digicel Vanuatu Ltd."),
    "33" => array ( "Area" => "Government fixed network", "Operator" => "Digicel Vanuatu Ltd."),
    "29" => array ( "Area" => "Shefa province and Port-Vila areas", "Operator" => "Telecom Vanuatu Ltd."),
    "28" => array ( "Area" => "Shefa province and Port-Vila areas", "Operator" => "Telecom Vanuatu Ltd."),
    "27" => array ( "Area" => "Shefa province and Port-Vila areas", "Operator" => "Telecom Vanuatu Ltd."),
    "26" => array ( "Area" => "Shefa province and Port-Vila areas", "Operator" => "Telecom Vanuatu Ltd."),
    "25" => array ( "Area" => "Shefa province and Port-Vila areas", "Operator" => "Telecom Vanuatu Ltd."),
    "24" => array ( "Area" => "Shefa province and Port-Vila areas", "Operator" => "Telecom Vanuatu Ltd."),
    "23" => array ( "Area" => "Shefa province and Port-Vila areas", "Operator" => "Telecom Vanuatu Ltd."),
    "22" => array ( "Area" => "Shefa province and Port-Vila areas", "Operator" => "Telecom Vanuatu Ltd."),
    "20" => array ( "Area" => "", "Operator" => "Telecom Vanuatu Ltd.")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 9 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "678", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Vanuatu", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+678 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * Check for FMC network with 2 digits NDC and 3 digits SN
   */
  $prefixes = array (
    "34" => array ( "Area" => "", "Operator" => "Digicel Vanuatu Ltd."),
    "30" => array ( "Area" => "", "Operator" => "Telecom Vanuatu Ltd.")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 9 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "678", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Vanuatu", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+678 " . substr ( $parameters["Number"], 4))));
    } 
  }

  /**
   * Check for VoIP network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "911" => "VCOM Proprietary Ltd.",
    "910" => "VCOM Proprietary Ltd.",
    "90" => "WaTok Networks Ltd."
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "678", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Vanuatu", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+678 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 3 digits SN
   */
  $prefixes = array (
    "818" => "Digicel Vanuatu Ltd.",
    "811" => "Telecom Vanuatu Ltd."
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 10 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "678", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Vanuatu", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+678 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for PRN network with 3 digits NDC and 3 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 10 && substr ( $parameters["Number"], 4, 3) == "900")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "678", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Vanuatu", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+678 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * If reached here, number wasn't identified as a valid Vanuatu phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
