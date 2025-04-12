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
 * related to country database of Senegal.
 *
 * Reference: https://www.itu.int/oth/T02020000B8/en (2022-10-28)
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
 * E.164 Senegal country hook
 */
framework_add_filter ( "e164_identify_country_SEN", "e164_identify_country_SEN");

/**
 * E.164 Senegal area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "SEN" (code for Senegal). This
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
function e164_identify_country_SEN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Senegal
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+221")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Senegal has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "79" => "ADIE",
    "78" => "Sonatel Mobiles (Orange)",
    "77" => "Sonatel Mobiles (Orange)",
    "76" => "FREE Sénégal",
    "75" => "Sirius Telecoms Afrique (Promobile)",
    "72" => "CSU SA (HAYO)",
    "70" => "Expresso Sénégal"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "221", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Senegal", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+221 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "339" => array ( "Area" => "", "Operator" => "Sonatel (Orange)"),
    "338" => array ( "Area" => "Dakar", "Operator" => "Sonatel (Orange)")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "221", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Senegal", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+221 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "93929" => "WAW SAS",
    "93928" => "",
    "93927" => "",
    "93926" => "",
    "93925" => "",
    "93924" => "",
    "93923" => "",
    "93922" => "",
    "93921" => "",
    "93920" => "",
    "93319" => "",
    "93318" => "",
    "93317" => "",
    "93316" => "",
    "93315" => "",
    "93314" => "",
    "93313" => "",
    "93312" => "",
    "93311" => "",
    "93310" => "ARC Telecom S.A.",
    "9399" => "",
    "9398" => "",
    "9397" => "",
    "9396" => "",
    "9395" => "",
    "9394" => "",
    "9393" => "",
    "9391" => "",
    "9390" => "",
    "9339" => "",
    "9338" => "",
    "9337" => "",
    "9336" => "",
    "9335" => "",
    "9334" => "",
    "9333" => "",
    "9332" => "",
    "9330" => "",
    "391" => "ADIE",
    "390" => "ADIE",
    "938" => "",
    "937" => "",
    "936" => "",
    "935" => "",
    "934" => "",
    "932" => "",
    "931" => "",
    "930" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "221", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Senegal", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+221 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for FMC network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "39" => "ADIE",
    "36" => "CSU SA (HAYO)",
    "32" => "FREE Sénégal",
    "30" => "Expresso Sénégal"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "221", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Senegal", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+221 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Senegal phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
