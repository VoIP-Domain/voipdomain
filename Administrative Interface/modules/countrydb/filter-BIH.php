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
 * related to country database of Bosnia and Herzegovina.
 *
 * Reference: https://www.itu.int/oth/T020200001B/en (2017-05-15)
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
 * E.164 Bosnia and Herzegovina country hook
 */
framework_add_filter ( "e164_identify_country_BIH", "e164_identify_country_BIH");

/**
 * E.164 Bosnia and Herzegovina area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "BIH" (code for
 * Bosnia and Herzegovina). This hook will verify if phone number is valid,
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
function e164_identify_country_BIH ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Bosnia and Herzegovina
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+387")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "60" => 9,
    "61" => 8,
    "62" => 8,
    "63" => 8,
    "64" => 9,
    "65" => 8,
    "66" => 8,
    "67" => 9,
    "70" => 8
  );
  foreach ( $prefixes as $prefix => $digits)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $digits)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "387", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Bosnia and Herzegovina", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+387 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "59" => "Trebinje",
    "58" => "Foča",
    "57" => "Istočno Sarajevo",
    "56" => "Zvornik",
    "55" => "Bijeljina",
    "54" => "Šamac",
    "53" => "Doboj",
    "52" => "Prijedor",
    "51" => "Banja Luka",
    "50" => "Mrkonjić Grad",
    "49" => "Brčko Distirct B&H",
    "39" => "West Herzegovina Canton",
    "38" => "Bosanian-Podrinje Goražde Canton",
    "37" => "Unsa-Sana Canton",
    "36" => "Hercegovina-Neretva Canton",
    "35" => "Tuzla Canton",
    "34" => "Canton 10",
    "33" => "Sarajevo Canton",
    "32" => "Zenica-Doboj Canton",
    "31" => "Posavina Canton",
    "30" => "Central Bosnia Canton"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "387", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Bosnia and Herzegovina", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+387 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for Premium Rate Numbers network with 2 digits NDC and variable N(S)N digits
   */
  $prefixes = array (
    "96" => 8,
    "94" => 8,
    "92" => 8,
    "91" => 8,
    "90" => 8,
    "85" => 9,
    "83" => 6,
    "82" => 8,
    "81" => 8
  );
  foreach ( $prefixes as $prefix => $digits)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $digits)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "387", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Bosnia and Herzegovina", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+387 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Bosnia and Herzegovina
   * phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
