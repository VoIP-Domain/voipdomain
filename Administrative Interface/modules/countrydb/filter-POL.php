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
 * related to country database of Poland.
 *
 * Reference: https://www.itu.int/oth/T02020000A8/en (2017-05-31)
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
 * E.164 Poland country hook
 */
framework_add_filter ( "e164_identify_country_POL", "e164_identify_country_POL");

/**
 * E.164 Polonian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "POL" (code for Poland). This
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
function e164_identify_country_POL ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Poland
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+48")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Poland has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "88",
    "79",
    "78",
    "73",
    "72",
    "69",
    "66",
    "60",
    "57",
    "53",
    "51",
    "50",
    "45"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "48", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Poland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+48 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "95" => "Gorzow Wielkopolski",
    "94" => "Koszalin",
    "91" => "Szczecin",
    "89" => "Olsztyn",
    "87" => "Suwalki",
    "86" => "Lomza",
    "85" => "Bialystok",
    "84" => "Zamosc",
    "83" => "Biala Podlaska",
    "82" => "Chelm",
    "81" => "Lublin",
    "77" => "Opole",
    "76" => "Legnica",
    "75" => "Jelenia Gora",
    "74" => "Walbrzych",
    "71" => "Wroclaw",
    "68" => "Zielona Gora",
    "67" => "Pila",
    "65" => "Leszno",
    "63" => "Konin",
    "62" => "Kalisz",
    "61" => "Poznan",
    "59" => "Slupsk",
    "58" => "Gdansk",
    "56" => "Torun",
    "55" => "Elblag",
    "54" => "Wloclawek",
    "52" => "Bydgoszcz",
    "48" => "Radom",
    "46" => "Skierniewice",
    "44" => "Piotrkow Trybunalski",
    "43" => "Sieradz",
    "42" => "Lodz",
    "41" => "Kielce",
    "34" => "Czestochowa",
    "33" => "Bielsko Biala",
    "32" => "Katowice",
    "29" => "Ostroleka",
    "25" => "Siedlce",
    "24" => "Plock",
    "23" => "Ciechanow",
    "22" => "Warszawa",
    "18" => "Nowy Sacz",
    "17" => "Rzeszow",
    "16" => "Przemysl",
    "15" => "Tarnobrzeg",
    "14" => "Tarnow",
    "13" => "Krosno",
    "12" => "Krakow"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "48", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Poland", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+48 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 2) == "39")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "48", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Poland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+48 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
  }

  /**
   * Check for Paging network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 2) == "64")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "48", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Poland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+48 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
  }

  /**
   * If reached here, number wasn't identified as a valid Polonian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
