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
 * related to country database of Lithuania.
 *
 * Reference: https://www.itu.int/oth/T020200007C/en (2012-10-31)
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
 * E.164 Lithuania country hook
 */
framework_add_filter ( "e164_identify_country_LTU", "e164_identify_country_LTU");

/**
 * E.164 Lithuanian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "LTU" (code for
 * Lithuania). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_LTU ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Lithuania
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+370")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Lithuania has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 8 digits SN
   */
  $prefixes = array (
    "6"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "370", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Lithuania", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9), "International" => "+370 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 or 3 digits NDC and 5 or 6 digits SN
   */
  $prefixes = array (
    "528" => array ( "area" => "Trakai district", "format" => 1),
    "527" => array ( "area" => "Vilnius city and district", "format" => 3),
    "526" => array ( "area" => "Vilnius city and district", "format" => 3),
    "525" => array ( "area" => "Vilnius city and district", "format" => 3),
    "524" => array ( "area" => "Vilnius city and district", "format" => 3),
    "523" => array ( "area" => "Vilnius city and district", "format" => 3),
    "522" => array ( "area" => "Vilnius city and district", "format" => 3),
    "521" => array ( "area" => "Vilnius city and district", "format" => 3),
    "520" => array ( "area" => "Vilnius city and district", "format" => 3),
    "469" => array ( "area" => "Neringa town", "format" => 1),
    "464" => array ( "area" => "Klaipėda city and district", "format" => 2),
    "463" => array ( "area" => "Klaipėda city and district", "format" => 2),
    "462" => array ( "area" => "Klaipėda city and district", "format" => 2),
    "460" => array ( "area" => "Palanga town", "format" => 1),
    "459" => array ( "area" => "Kupiškis district", "format" => 1),
    "458" => array ( "area" => "Rokiškis district", "format" => 1),
    "455" => array ( "area" => "Panevėžys city and district", "format" => 2),
    "454" => array ( "area" => "Panevėžys city and district", "format" => 2),
    "451" => array ( "area" => "Pasvalys district", "format" => 1),
    "450" => array ( "area" => "Biržai district", "format" => 1),
    "449" => array ( "area" => "Šilalė district", "format" => 1),
    "448" => array ( "area" => "Plungė district", "format" => 1),
    "447" => array ( "area" => "Jurbarkas district", "format" => 1),
    "446" => array ( "area" => "Tauragė district", "format" => 1),
    "445" => array ( "area" => "Kretinga district", "format" => 1),
    "444" => array ( "area" => "Telšiai district", "format" => 1),
    "443" => array ( "area" => "Mažeikiai district", "format" => 1),
    "441" => array ( "area" => "Šilutė district", "format" => 1),
    "440" => array ( "area" => "Skuodas district", "format" => 1),
    "428" => array ( "area" => "Raseiniai district", "format" => 1),
    "427" => array ( "area" => "Kelmė district", "format" => 1),
    "426" => array ( "area" => "Joniškis district", "format" => 1),
    "425" => array ( "area" => "Akmenė district", "format" => 1),
    "422" => array ( "area" => "Radviliškis district", "format" => 1),
    "421" => array ( "area" => "Pakruojis district", "format" => 1),
    "389" => array ( "area" => "Utena district", "format" => 1),
    "387" => array ( "area" => "Švenčionys district", "format" => 1),
    "386" => array ( "area" => "Ignalina district and Visaginas town", "format" => 1),
    "385" => array ( "area" => "Zarasai district", "format" => 1),
    "383" => array ( "area" => "Molėtai district", "format" => 1),
    "382" => array ( "area" => "Širvintos district", "format" => 1),
    "381" => array ( "area" => "Anykščiai district", "format" => 1),
    "380" => array ( "area" => "Šalčininkai district", "format" => 1),
    "349" => array ( "area" => "Jonava district", "format" => 1),
    "347" => array ( "area" => "Kėdainiai district", "format" => 1),
    "346" => array ( "area" => "Kaišiadorys district", "format" => 1),
    "345" => array ( "area" => "Šakiai district", "format" => 1),
    "343" => array ( "area" => "Marijampolė district", "format" => 1),
    "342" => array ( "area" => "Vilkaviškis district", "format" => 1),
    "340" => array ( "area" => "Ukmergė district", "format" => 1),
    "319" => array ( "area" => "Prienai district and Birštonas town", "format" => 1),
    "318" => array ( "area" => "Lazdijai district", "format" => 1),
    "315" => array ( "area" => "Alytus town and district", "format" => 1),
    "313" => array ( "area" => "Druskininkai town", "format" => 1),
    "310" => array ( "area" => "Varėna district", "format" => 1),
    "41" => array ( "area" => "Šiauliai city and district", "format" => 2),
    "37" => array ( "area" => "Kaunas city and district", "format" => 2)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      switch ( $data["format"])
      {
        case 1:
          return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "370", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Lithuania", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9), "International" => "+370 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9))));
          break;
        case 2:
          return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "370", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Lithuania", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+370 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
          break;
        case 3:
          return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "370", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Lithuania", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+370 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
          break;
      }
    }
  }

  /**
   * Check for toll free network with no NDC and 8 digits SN
   */
  $prefixes = array (
    "800"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "370", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Lithuania", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9), "International" => "+370 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for PRN network with no NDC and 8 digits SN
   */
  $prefixes = array (
    "910",
    "909",
    "903",
    "902",
    "900"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "370", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Lithuania", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9), "International" => "+370 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Lithuanian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
