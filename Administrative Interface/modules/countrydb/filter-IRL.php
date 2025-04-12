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
 * related to country database of Ireland.
 *
 * Reference: https://www.itu.int/oth/T0202000068/en (2018-09-10)
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
 * E.164 Ireland country hook
 */
framework_add_filter ( "e164_identify_country_IRL", "e164_identify_country_IRL");

/**
 * E.164 Irelandian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "IRL" (code for
 * Ireland). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_IRL ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Ireland
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+353")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "89",
    "88",
    "87",
    "86",
    "85",
    "84",
    "83",
    "82"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 13 && strlen ( $parameters["Number"]) <= 16)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "353", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ireland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+353 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1 to 3 digits NDC and 6 to 9 digits SN
   */
  $prefixes = array (
    "505" => "Roscrea",
    "504" => "Thurles",
    "404" => "Wicklow",
    "402" => "Arklow",
    "99" => "Kilronan",
    "98" => "Westport",
    "97" => "Belmullet",
    "96" => "Ballina",
    "95" => "Clifden",
    "94" => "Castlebar, Claremorris, Castlerea and Ballinrobe",
    "93" => "Tuam",
    "91" => "Galway, Gort and Loughrea",
    "90" => "Athlone, Ballinasloe, Portumna and Roscommon",
    "74" => "Letterkenny, Donegal, Dungloe and Buncrana",
    "71" => "Sligo, Manorhamilton and Carrick-on-Shannon",
    "69" => "Newcastlewest",
    "68" => "Listowel",
    "67" => "Nenagh",
    "66" => "Tralee, Dingle, Killorglin and Cahirciveen",
    "65" => "Ennis, Ennistymon and Kilrush",
    "64" => "Killarney and Rathmore",
    "63" => "Rathluirc",
    "62" => "Tipperary and Cashel",
    "61" => "Limerick and Scariff",
    "59" => "Carlow, Muine Bheag, Athy and Baltinglass",
    "58" => "Dungarvan",
    "57" => "Portlaoise, Abbeyleix, Tullamore and Birr",
    "56" => "Kilkenny, Castlecomer and Freshford",
    "53" => "Wexford, Enniscorthy, Ferns and Gorey",
    "52" => "Clonmel, Cahir and Killenaule",
    "51" => "Waterford, Carrick-On-Suir, New Ross and Kilmacthomas",
    "49" => "Cavan, Cootehill, Oldcastle and Belturbet",
    "47" => "Monaghan and Clones",
    "46" => "Navan, Kells, Trim, Edenderry and Enfield",
    "45" => "Naas, Kildare and Curragh",
    "44" => "Mullingar, Castlepollard and Tyrellspass",
    "43" => "Longford and Granard",
    "42" => "Dundalk, Carrickmacross and Castleblaney",
    "41" => "Drogheda and Ardee",
    "29" => "Kanturk",
    "28" => "Skibbereen",
    "27" => "Bantry",
    "26" => "Macroom",
    "25" => "Fermoy",
    "24" => "Youghal",
    "23" => "Bandon",
    "22" => "Mallow",
    "21" => "Cork, Kinsale and Coachford",
    "1" => "Dublin"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + strlen ( $prefix) + 7)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "353", "NDC" => (string) $prefix, "Country" => "Ireland", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => $prefix . " " . substr ( $parameters["Number"], 4 + strlen ( $prefix)), "International" => "+353 " . $prefix . " " . substr ( $parameters["Number"], 4 + strlen ( $prefix)))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 2) == "76" && strlen ( $parameters["Number"]) == 13)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "353", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ireland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+353 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Irelandian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
