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
 * related to country database of Afghanistan.
 *
 * Reference: https://www.itu.int/oth/T0202000001/en (2017-03-30)
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
 * E.164 Afghanistan country hook
 */
framework_add_filter ( "e164_identify_country_AFG", "e164_identify_country_AFG");

/**
 * E.164 Afghanistanian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "AFG" (code for
 * Afghanistan). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_AFG ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Afghanistan
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+93")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Afghanistan has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "7569" => array ( "area" => "Paktika", "operator" => "Afghan Telecom Salaam"),
    "7568" => array ( "area" => "Khost", "operator" => "Afghan Telecom Salaam"),
    "7567" => array ( "area" => "Paktia", "operator" => "Afghan Telecom Salaam"),
    "7566" => array ( "area" => "Laghman", "operator" => "Afghan Telecom Salaam"),
    "7565" => array ( "area" => "Kunarha", "operator" => "Afghan Telecom Salaam"),
    "7564" => array ( "area" => "Nurestan", "operator" => "Afghan Telecom Salaam"),
    "7563" => array ( "area" => "Badkhshan", "operator" => "Afghan Telecom Salaam"),
    "7562" => array ( "area" => "Nangarhar", "operator" => "Afghan Telecom Salaam"),
    "7561" => array ( "area" => "Nangarhar", "operator" => "Afghan Telecom Salaam"),
    "7560" => array ( "area" => "Nangarhar", "operator" => "Afghan Telecom Salaam"),
    "7559" => array ( "area" => "Baghlan", "operator" => "Afghan Telecom Salaam"),
    "7558" => array ( "area" => "Samangan", "operator" => "Afghan Telecom Salaam"),
    "7557" => array ( "area" => "Jowzjan", "operator" => "Afghan Telecom Salaam"),
    "7556" => array ( "area" => "Takhar", "operator" => "Afghan Telecom Salaam"),
    "7555" => array ( "area" => "Kunduz", "operator" => "Afghan Telecom Salaam"),
    "7554" => array ( "area" => "Balkh", "operator" => "Afghan Telecom Salaam"),
    "7553" => array ( "area" => "Balkh", "operator" => "Afghan Telecom Salaam"),
    "7552" => array ( "area" => "Balkh", "operator" => "Afghan Telecom Salaam"),
    "7551" => array ( "area" => "Balkh", "operator" => "Afghan Telecom Salaam"),
    "7550" => array ( "area" => "Balkh", "operator" => "Afghan Telecom Salaam"),
    "7549" => array ( "area" => "Farah", "operator" => "Afghan Telecom Salaam"),
    "7548" => array ( "area" => "Ghowr", "operator" => "Afghan Telecom Salaam"),
    "7547" => array ( "area" => "Badghis", "operator" => "Afghan Telecom Salaam"),
    "7546" => array ( "area" => "Sar-E Pol", "operator" => "Afghan Telecom Salaam"),
    "7545" => array ( "area" => "Faryab", "operator" => "Afghan Telecom Salaam"),
    "7544" => array ( "area" => "Herat", "operator" => "Afghan Telecom Salaam"),
    "7543" => array ( "area" => "Herat", "operator" => "Afghan Telecom Salaam"),
    "7542" => array ( "area" => "Herat", "operator" => "Afghan Telecom Salaam"),
    "7541" => array ( "area" => "Herat", "operator" => "Afghan Telecom Salaam"),
    "7540" => array ( "area" => "Herat", "operator" => "Afghan Telecom Salaam"),
    "7539" => array ( "area" => "Helmand", "operator" => "Afghan Telecom Salaam"),
    "7538" => array ( "area" => "Zabol", "operator" => "Afghan Telecom Salaam"),
    "7537" => array ( "area" => "Uruzgan", "operator" => "Afghan Telecom Salaam"),
    "7536" => array ( "area" => "Ghazni", "operator" => "Afghan Telecom Salaam"),
    "7535" => array ( "area" => "Nimruz", "operator" => "Afghan Telecom Salaam"),
    "7534" => array ( "area" => "Kandahar", "operator" => "Afghan Telecom Salaam"),
    "7533" => array ( "area" => "Kandahar", "operator" => "Afghan Telecom Salaam"),
    "7532" => array ( "area" => "Kandahar", "operator" => "Afghan Telecom Salaam"),
    "7531" => array ( "area" => "Kandahar", "operator" => "Afghan Telecom Salaam"),
    "7530" => array ( "area" => "Kandahar", "operator" => "Afghan Telecom Salaam"),
    "7529" => array ( "area" => "Logar", "operator" => "Afghan Telecom Salaam"),
    "7528" => array ( "area" => "Wardak", "operator" => "Afghan Telecom Salaam"),
    "7527" => array ( "area" => "Bamian and Daikondis", "operator" => "Afghan Telecom Salaam"),
    "7526" => array ( "area" => "Kapisa and Panj Shirs", "operator" => "Afghan Telecom Salaam"),
    "7525" => array ( "area" => "Parwan", "operator" => "Afghan Telecom Salaam"),
    "7524" => array ( "area" => "Kabul", "operator" => "Afghan Telecom Salaam"),
    "7523" => array ( "area" => "Kabul", "operator" => "Afghan Telecom Salaam"),
    "7522" => array ( "area" => "Kabul", "operator" => "Afghan Telecom Salaam"),
    "7521" => array ( "area" => "Kabul", "operator" => "Afghan Telecom Salaam"),
    "7520" => array ( "area" => "Kabul", "operator" => "Afghan Telecom Salaam"),
    "767" => array ( "area" => "", "operator" => "MTN"),
    "766" => array ( "area" => "", "operator" => "MTN"),
    "765" => array ( "area" => "", "operator" => "MTN"),
    "764" => array ( "area" => "", "operator" => "MTN"),
    "749" => array ( "area" => "", "operator" => "Afghan Telecom Salaam"),
    "748" => array ( "area" => "", "operator" => "Afghan Telecom Salaam"),
    "747" => array ( "area" => "", "operator" => "Afghan Telecom Salaam"),
    "744" => array ( "area" => "", "operator" => "Afghan Telecom Salaam"),
    "730" => array ( "area" => "", "operator" => "Etisalat"),
    "729" => array ( "area" => "", "operator" => "ROSHAN"),
    "728" => array ( "area" => "", "operator" => "ROSHAN"),
    "711" => array ( "area" => "", "operator" => "AWCC"),
    "79" => array ( "area" => "", "operator" => "Roshan"),
    "78" => array ( "area" => "", "operator" => "Etisalat"),
    "77" => array ( "area" => "", "operator" => "MTN"),
    "70" => array ( "area" => "", "operator" => "AWCC")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "93", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Afghanistan", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 2) . ") " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8, 4), "International" => "+93 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   * ([2-9]XXXXXX)
   */
  $prefixes = array (
    "65" => "Paktika",
    "64" => "Paktia",
    "63" => "Laghman",
    "62" => "Kunarha",
    "61" => "Nurestan",
    "60" => "Nangarhar",
    "58" => "Baghlan",
    "57" => "Faryab",
    "56" => "Sar-E Pol",
    "55" => "Samangan",
    "54" => "Jowzjan",
    "53" => "Takhar",
    "52" => "Badkhshan",
    "51" => "Kunduz",
    "50" => "Balkh",
    "44" => "Nimruz",
    "43" => "Farah",
    "42" => "Ghowr",
    "41" => "Badghis",
    "40" => "Herat",
    "34" => "Helmand",
    "33" => "Zabol",
    "32" => "Uruzgan",
    "31" => "Ghazni",
    "30" => "Kandahar",
    "28" => "Panjshar",
    "27" => "Khost",
    "26" => "Dorkondi",
    "25" => "Logar",
    "24" => "Wardak",
    "23" => "Bamian",
    "22" => "Kapisa",
    "21" => "Parwan",
    "20" => "Kabul"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && (int) substr ( $parameters["Number"], 5, 1) >= 2 && (int) substr ( $parameters["Number"], 5, 1) <= 9)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "93", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Afghanistan", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 2) . ") " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8, 4), "International" => "+93 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Afghanistanian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
