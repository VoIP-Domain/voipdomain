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
 * related to country database of South Africa.
 *
 * Reference: https://www.itu.int/oth/T02020000C1/en (2012-10-30)
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
 * E.164 South Africa country hook
 */
framework_add_filter ( "e164_identify_country_ZAF", "e164_identify_country_ZAF");

/**
 * E.164 South African area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "ZAF" (code for
 * South Africa). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_ZAF ( $buffer, $parameters)
{
  /**
   * Check if number country code is from South Africa
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+27")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in South Africa has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "84",
    "83",
    "82",
    "81",
    "79",
    "78",
    "77",
    "76",
    "74",
    "73",
    "72",
    "71",
    "70",
    "65",
    "64",
    "63",
    "62",
    "61",
    "60"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "27", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "South Africa", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+27 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "59" => "",
    "58" => "Eastern part of Free State (including Bethlehem)",
    "57" => "Free State Goldfields (including Welkom)",
    "56" => "Northern part of Free State (including Kroonstad)",
    "55" => "",
    "54" => "Gordonia (including Upington)",
    "53" => "Eastern part of Northern Cape (including Kimberley) and far Western part of North West",
    "52" => "",
    "51" => "Southern and Central parts of Free State (including Bloemfontein) and far Eastern part of Eastern Cape (including Aliwal North)",
    "50" => "",
    "49" => "Western part of Eastern Cape (including Graaff-Reinet)",
    "48" => "Northern part of Eastern Cape (including Steynsburg)",
    "47" => "Eastern part of Eastern Cape (including Mthatha)",
    "46" => "Southern and Eastern parts of Eastern Cape (including Grahamstown)",
    "45" => "Northern and Eastern parts of Eastern Cape (including Queenstown)",
    "44" => "Garden Route (including Oudtshoorn, Knysna, Plettenberg Bay, Mossel Bay and George)",
    "43" => "East London region",
    "42" => "Southern and Central parts of Eastern Cape (including Humansdorp)",
    "41" => "Port Elizabeth region (including Uitenhage)",
    "40" => "Bhisho Region",
    "39" => "Eastern Pondoland and Southern coast of KwaZulu Natal (including Port Shepstone)",
    "38" => "",
    "37" => "",
    "36" => "Drakensberg (including Ladysmith)",
    "35" => "Zululand (including St. Lucia and Richards Bay)",
    "34" => "Northern KwaZulu Natal (including Vryheid and Newcastle)",
    "33" => "KwaZulu Natal Midlands (including Pietermaritzburg)",
    "32" => "KwaZulu Natal central coast (including Stanger)",
    "31" => "Durban region",
    "30" => "",
    "29" => "",
    "28" => "Southern coast of Western Cape (including Swellendam, Caledon and Hermanus)",
    "27" => "Namaqualand (including Vredendal, Calvinia, Clanwilliam, Springbok, Alexander Bay and Port Nolloth)",
    "26" => "",
    "25" => "",
    "24" => "",
    "23" => "Karoo (including Worcester and Beaufort West)",
    "22" => "Western coast of Western Cape and Boland (including Malmesbury)",
    "21" => "Cape Town region (including Stellenbosch, Somerset West and Gordons Bay)",
    "20" => "Southern part of Northern Cape (including Fraserberg) and the North-Eastern part of the Western Cape (including Leeugamka and Merweville)",
    "19" => "",
    "18" => "Southern part of Nort West (including Potchefstroom and Kleksdorp)",
    "17" => "Southern part of Mpumalanga (including Ermelo)",
    "16" => "Vaal Triangle (including Vereeniging, Vanderbijlpark and Sasolburg)",
    "15" => "Norther and Eastern parts of Limpopo (including Polokwane)",
    "14" => "Norther part of North West and Southern and Western parts of Limpopo (including Rustenburg and Nylstroom)",
    "13" => "Norther and Western parts of Mpumalanga (including Middelburg, Witbank and Nelspruit)",
    "12" => "Tshwane region (including Pretoria)",
    "11" => "Johannesburg",
    "10" => "Johannesburg"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "27", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "South Africa", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 2) . ") " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+27 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for Tollfree network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 2) == "80")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "27", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "South Africa", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 2) . ") " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+27 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * Check for VoIP network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 2) == "87")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "27", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "South Africa", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 2) . ") " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+27 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * Check for PRN network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 2) == "90")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "27", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "South Africa", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 2) . ") " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+27 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * If reached here, number wasn't identified as a valid South African phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
