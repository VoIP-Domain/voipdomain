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
 * related to country database of Yemen.
 *
 * Reference: https://www.itu.int/oth/T02020000E7/en (2013-04-04)
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
 * E.164 Yemen country hook
 */
framework_add_filter ( "e164_identify_country_MLI", "e164_identify_country_MLI");

/**
 * E.164 Yemen area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MLI" (code for Yemen). This
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
function e164_identify_country_MLI ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Yemen
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+967")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Yemen has 11, 12 or 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 12 || strlen ( $parameters["Number"]) > 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "77" => "Yemen Mobile",
    "73" => "MTN",
    "71" => "SebaFon",
    "70" => "Y Telecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "967", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Yemen", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+967 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 1 digit NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "7879" => array ( "Length" => 7, "Area" => "Al Mahweet"),
    "7878" => array ( "Length" => 7, "Area" => "Sa'adah"),
    "7877" => array ( "Length" => 7, "Area" => "Amran"),
    "7876" => array ( "Length" => 7, "Area" => "Amran"),
    "7875" => array ( "Length" => 7, "Area" => "Sa'adah"),
    "7874" => array ( "Length" => 7, "Area" => "Al Mahweet"),
    "7873" => array ( "Length" => 7, "Area" => "Hajjah"),
    "7872" => array ( "Length" => 7, "Area" => "Hajjah"),
    "7871" => array ( "Length" => 7, "Area" => "Hajjah"),
    "7870" => array ( "Length" => 7, "Area" => "Hajjah"),
    "7845" => array ( "Length" => 7, "Area" => "Al Mahweet"),
    "6869" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6868" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6867" => array ( "Length" => 7, "Area" => "Dhamar"),
    "6866" => array ( "Length" => 7, "Area" => "Dhamar"),
    "6865" => array ( "Length" => 7, "Area" => "Dhamar"),
    "6864" => array ( "Length" => 7, "Area" => "Dhamar"),
    "6863" => array ( "Length" => 7, "Area" => "Ma'areb"),
    "6862" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6861" => array ( "Length" => 7, "Area" => "Dhamar"),
    "6860" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6859" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6858" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6857" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6856" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6855" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6854" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6853" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "6850" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "4849" => array ( "Length" => 7, "Area" => "Ibb"),
    "4848" => array ( "Length" => 7, "Area" => "Ibb"),
    "4847" => array ( "Length" => 7, "Area" => "Ibb"),
    "4846" => array ( "Length" => 7, "Area" => "Ibb"),
    "4845" => array ( "Length" => 7, "Area" => "Ibb"),
    "4844" => array ( "Length" => 7, "Area" => "Ibb"),
    "4843" => array ( "Length" => 7, "Area" => "Taiz"),
    "4842" => array ( "Length" => 7, "Area" => "Taiz"),
    "4841" => array ( "Length" => 7, "Area" => "Taiz"),
    "4840" => array ( "Length" => 7, "Area" => "Taiz"),
    "2842" => array ( "Length" => 7, "Area" => "Dhale'a"),
    "2841" => array ( "Length" => 7, "Area" => "Dhale'a"),
    "2840" => array ( "Length" => 7, "Area" => "Aden"),
    "786" => array ( "Length" => 7, "Area" => "Amran"),
    "785" => array ( "Length" => 7, "Area" => "Sa'adah"),
    "684" => array ( "Length" => 7, "Area" => "Dhamar"),
    "683" => array ( "Length" => 7, "Area" => "Ma'areb"),
    "682" => array ( "Length" => 7, "Area" => "Dhamar"),
    "657" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "656" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "655" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "654" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "653" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "652" => array ( "Length" => 7, "Area" => "Al Baidha"),
    "651" => array ( "Length" => 7, "Area" => "Dhamar"),
    "650" => array ( "Length" => 7, "Area" => "Dhamar"),
    "639" => array ( "Length" => 7, "Area" => "Dhamar"),
    "638" => array ( "Length" => 7, "Area" => "Ma'areb"),
    "636" => array ( "Length" => 7, "Area" => "Ma'areb"),
    "634" => array ( "Length" => 7, "Area" => "Aljawf"),
    "633" => array ( "Length" => 7, "Area" => "Ma'areb"),
    "630" => array ( "Length" => 7, "Area" => "Ma'areb"),
    "569" => array ( "Length" => 7, "Area" => "Al Mahrah"),
    "568" => array ( "Length" => 7, "Area" => "Al Mahrah"),
    "567" => array ( "Length" => 7, "Area" => "Al Mahrah"),
    "566" => array ( "Length" => 7, "Area" => "Soqatrah"),
    "565" => array ( "Length" => 7, "Area" => "Al Mahrah"),
    "564" => array ( "Length" => 7, "Area" => "Al Mahrah"),
    "563" => array ( "Length" => 7, "Area" => "Al Mahrah"),
    "562" => array ( "Length" => 7, "Area" => "Al Mahrah"),
    "561" => array ( "Length" => 7, "Area" => "Al Mahrah"),
    "560" => array ( "Length" => 7, "Area" => "Al Mahrah"),
    "485" => array ( "Length" => 7, "Area" => "Ibb"),
    "483" => array ( "Length" => 7, "Area" => "Taiz"),
    "433" => array ( "Length" => 7, "Area" => "Ibb"),
    "385" => array ( "Length" => 7, "Area" => "Hodaidah"),
    "383" => array ( "Length" => 7, "Area" => "Hodaidah"),
    "286" => array ( "Length" => 7, "Area" => "Abyan"),
    "285" => array ( "Length" => 7, "Area" => "Lahj"),
    "282" => array ( "Length" => 7, "Area" => "Aden"),
    "182" => array ( "Length" => 7, "Area" => "Sana'a"),
    "181" => array ( "Length" => 7, "Area" => "Sana'a"),
    "175" => array ( "Length" => 8, "Area" => "Sana'a"),
    "76" => array ( "Length" => 7, "Area" => "Amran"),
    "75" => array ( "Length" => 7, "Area" => "Sa'adah"),
    "74" => array ( "Length" => 7, "Area" => "Al Mahweet"),
    "72" => array ( "Length" => 7, "Area" => "Hajjah"),
    "64" => array ( "Length" => 7, "Area" => "Dhamar"),
    "55" => array ( "Length" => 7, "Area" => "Hadhrmout"),
    "54" => array ( "Length" => 7, "Area" => "Hadhrmout"),
    "53" => array ( "Length" => 7, "Area" => "Hadhrmout"),
    "52" => array ( "Length" => 7, "Area" => "Shabwah"),
    "45" => array ( "Length" => 7, "Area" => "Ibb"),
    "44" => array ( "Length" => 7, "Area" => "Ibb"),
    "43" => array ( "Length" => 7, "Area" => "Taiz"),
    "42" => array ( "Length" => 7, "Area" => "Taiz"),
    "35" => array ( "Length" => 7, "Area" => "Hodaidah"),
    "33" => array ( "Length" => 7, "Area" => "Hodaidah"),
    "32" => array ( "Length" => 7, "Area" => "Hodaidah"),
    "26" => array ( "Length" => 7, "Area" => "Abyan"),
    "25" => array ( "Length" => 7, "Area" => "Lahj"),
    "24" => array ( "Length" => 7, "Area" => "Dhale'a"),
    "23" => array ( "Length" => 7, "Area" => "Aden"),
    "22" => array ( "Length" => 7, "Area" => "Aden"),
    "16" => array ( "Length" => 7, "Area" => "Sana'a"),
    "15" => array ( "Length" => 7, "Area" => "Sana'a"),
    "14" => array ( "Length" => 7, "Area" => "Sana'a"),
    "13" => array ( "Length" => 7, "Area" => "Sana'a"),
    "12" => array ( "Length" => 7, "Area" => "Sana'a")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 4 + $data["Length"] && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "967", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Yemen", "Area" => $data["Area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+967 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Yemen phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
