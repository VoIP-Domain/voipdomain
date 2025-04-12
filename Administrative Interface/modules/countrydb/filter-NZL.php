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
 * related to country database of New Zealand.
 *
 * Reference: https://www.itu.int/oth/T0202000099/en (2020-03-25)
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
 * E.164 New Zealand country hook
 */
framework_add_filter ( "e164_identify_country_NZL", "e164_identify_country_NZL");

/**
 * E.164 New Zealian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "NZL" (code for New
 * Zealand). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_NZL ( $buffer, $parameters)
{
  /**
   * Check if number country code is from New Zealand
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+64")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in New Zealand has between 10 and 16 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 10 || strlen ( $parameters["Number"]) > 16)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network
   */
  $prefixes = array (
    "2899" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2898" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2897" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2896" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "NOW"),
    "2895" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2894" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2893" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2892" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2891" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2890" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2889" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "2Talk"),
    "2888" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2887" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2886" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Vodafone"),
    "2885" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Vodafone"),
    "2884" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2883" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2882" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2881" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "2880" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "240" => array ( "Min" => 8, "Max" => 8, "Area" => "Scott Base Antarctica", "Operator" => ""),
    "287" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "286" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "285" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "284" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "2degress"),
    "283" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "282" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "281" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "CallPlus or Black + White"),
    "280" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Compass Communications"),
    "279" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Spark New Zealand"),
    "278" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Spark New Zealand"),
    "277" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Spark New Zealand"),
    "276" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Spark New Zealand"),
    "275" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Spark New Zealand"),
    "274" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Spark New Zealand"),
    "273" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Spark New Zealand"),
    "272" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Spark New Zealand"),
    "271" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Spark New Zealand"),
    "270" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Vodafone"),
    "269" => array ( "Min" => 8, "Max" => 9, "Area" => "", "Operator" => "Spark New Zealand"),
    "268" => array ( "Min" => 8, "Max" => 9, "Area" => "", "Operator" => "Spark New Zealand"),
    "264" => array ( "Min" => 8, "Max" => 9, "Area" => "", "Operator" => "Spark New Zealand"),
    "263" => array ( "Min" => 8, "Max" => 9, "Area" => "", "Operator" => "Spark New Zealand"),
    "262" => array ( "Min" => 8, "Max" => 9, "Area" => "", "Operator" => "Spark New Zealand"),
    "261" => array ( "Min" => 8, "Max" => 9, "Area" => "", "Operator" => "Spark New Zealand"),
    "206" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Voyager Internet"),
    "205" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Vodafone"),
    "204" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Skinny"),
    "203" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Voyager"),
    "202" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Vocus"),
    "201" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Vocus"),
    "29" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "Vodafone"),
    "26" => array ( "Min" => 8, "Max" => 9, "Area" => "", "Operator" => "TeamTalk"),
    "22" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "2degress"),
    "21" => array ( "Min" => 8, "Max" => 10, "Area" => "", "Operator" => "Vodafone"),
    "20" => array ( "Min" => 9, "Max" => 10, "Area" => "", "Operator" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) >= 3 + $data["Min"] && strlen ( $parameters["Number"]) <= 3 + $data["Min"] && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "64", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "New Zealand", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+64 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 1 digit NDC and 7 digits SN
   */
  $prefixes = array (
    "3929" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3928" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3927" => array ( "Area" => "South Island and Chatham Islands", "City" => "Greymouth"),
    "3926" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3925" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3924" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3923" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3922" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3921" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3920" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3409" => array ( "Area" => "South Island and Chatham Islands", "City" => "Queenstown"),
    "3408" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3407" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3406" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3405" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3404" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3403" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3402" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3401" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "3400" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "999" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland"),
    "998" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Whangarei"),
    "997" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland"),
    "996" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland"),
    "995" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland"),
    "994" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland"),
    "993" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland"),
    "992" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland"),
    "991" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland"),
    "990" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Warkworth"),
    "949" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => ""),
    "948" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (northern suburbs)"),
    "947" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (northern suburbs)"),
    "946" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => ""),
    "945" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => ""),
    "944" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (northern suburbs)"),
    "943" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Whangarei, Maungaturoto"),
    "942" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Helensville, Warkworth, Hibiscus Coast, Great Barrier Island"),
    "941" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (northern suburbs)"),
    "940" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Kaikohe, Kaitaia, Kawakawa"),
    "929" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (southern and eastern suburbs)"),
    "928" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (southern and eastern suburbs)"),
    "927" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (southern and eastern suburbs)"),
    "926" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (southern and eastern suburbs)"),
    "925" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (southern and eastern suburbs)"),
    "924" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (southern and eastern suburbs)"),
    "923" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Pukekohe"),
    "922" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (southern and eastern suburbs)"),
    "921" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (southern and eastern suburbs)"),
    "920" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (southern and eastern suburbs)"),
    "799" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "798" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "797" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "796" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Hamilton"),
    "795" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Hamilton"),
    "794" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "793" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Tauranga"),
    "792" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Rotorua, Whakatane, Tauranga"),
    "791" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "790" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Taupo"),
    "789" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Taumarunui"),
    "788" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Matamata, Tokoroa, Putaruru, Tirau and surrounding areas, Morrinsville"),
    "787" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Te Awamutu, Otorohanga, Te Kuiti"),
    "786" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Paeroa, Waihi, Thames, Whangamata"),
    "785" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Hamilton (eastern suburbs)"),
    "784" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Hamilton (western suburbs)"),
    "783" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Hamilton"),
    "782" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Hamilton, Huntly"),
    "781" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "780" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "759" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "758" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "757" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Tauranga"),
    "756" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Hamilton"),
    "755" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "754" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Tauranga"),
    "753" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "752" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "751" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "750" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "739" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "738" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Taupo"),
    "737" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Taupo"),
    "736" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Rotorua"),
    "735" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Rotorua"),
    "734" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Rotorua"),
    "733" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Rotorua, Taupo"),
    "732" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Whakatane"),
    "731" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Whakatane, Opotiki"),
    "730" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => "Whakatane"),
    "699" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "698" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Gisborne"),
    "697" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Napier"),
    "696" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Wanganui, New Plymouth"),
    "695" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Palmerston North, New Plymouth"),
    "694" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Masterton, Levin"),
    "693" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "692" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "691" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "690" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "689" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "688" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "687" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Hastings (city and southern satellite towns)"),
    "686" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Gisborne, Ruatoria"),
    "685" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Waipukurau"),
    "684" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Napier (Napier city)"),
    "683" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Napier (northern satellite towns), Wairoa"),
    "682" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "681" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "680" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "679" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "678" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "677" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "676" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "New Plymouth, Opunake, Stratford"),
    "675" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "New Plymouth, Mokau"),
    "674" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "673" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "672" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "671" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "670" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "639" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "638" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Taihape, Ohakune, Waiouru"),
    "637" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Masterton, Dannevirke, Pahiatua"),
    "636" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Levin"),
    "635" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Palmerston North (city)"),
    "634" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Wanganui"),
    "633" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "632" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Palmerston North (satellite towns), Marton"),
    "631" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "630" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Featherston"),
    "629" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "628" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "627" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => "Hawera"),
    "626" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "625" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "624" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "623" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "622" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "621" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "620" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "499" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "498" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "497" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "496" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "495" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "494" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "493" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "492" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "491" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "490" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Paraparaumu"),
    "489" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "488" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "487" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "486" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "485" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "484" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "483" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "482" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "481" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "480" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington"),
    "429" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Paraparaumu"),
    "428" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "427" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "426" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "425" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "424" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "423" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington (Porirua and Tawa)"),
    "422" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "421" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "420" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "399" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "398" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch, Blenheim, Nelson"),
    "397" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch"),
    "396" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch"),
    "395" => array ( "Area" => "South Island and Chatham Islands", "City" => "Dunedin, Timaru"),
    "394" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch, Invercargill"),
    "393" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "391" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "390" => array ( "Area" => "South Island and Chatham Islands", "City" => "Ashburton"),
    "379" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "378" => array ( "Area" => "South Island and Chatham Islands", "City" => "Westport"),
    "377" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "376" => array ( "Area" => "South Island and Chatham Islands", "City" => "Greymouth"),
    "375" => array ( "Area" => "South Island and Chatham Islands", "City" => "Hokitika, Franz Josef Glacier, Fox Glacier, Haast"),
    "374" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "373" => array ( "Area" => "South Island and Chatham Islands", "City" => "Greymouth"),
    "372" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "371" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "370" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "369" => array ( "Area" => "South Island and Chatham Islands", "City" => "Geraldine"),
    "368" => array ( "Area" => "South Island and Chatham Islands", "City" => "Timaru, Waimate, Fairlie"),
    "367" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "366" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "365" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "364" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "363" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "362" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "361" => array ( "Area" => "South Island and Chatham Islands", "City" => "Timaru, Pleasant Point, Temuka, Cave, St. Andrews, Pareora"),
    "360" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "359" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "358" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "357" => array ( "Area" => "South Island and Chatham Islands", "City" => "Blenheim"),
    "356" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "355" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "354" => array ( "Area" => "South Island and Chatham Islands", "City" => "Nelson"),
    "353" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "352" => array ( "Area" => "South Island and Chatham Islands", "City" => "Murchison, Takaka, Motueka"),
    "351" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "350" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "349" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "348" => array ( "Area" => "South Island and Chatham Islands", "City" => "Dunedin (southwestern suburbs and Mosgiel), Lawrence"),
    "347" => array ( "Area" => "South Island and Chatham Islands", "City" => "Dunedin (central and northern suburbs)"),
    "346" => array ( "Area" => "South Island and Chatham Islands", "City" => "Dunedin (western suburbs), Palmerston"),
    "345" => array ( "Area" => "South Island and Chatham Islands", "City" => "Dunedin (southeastern suburbs), Queenstown"),
    "344" => array ( "Area" => "South Island and Chatham Islands", "City" => "Queenstown, Cromwell, Alexandra, Wanaka, Ranfurly, Roxburgh"),
    "343" => array ( "Area" => "South Island and Chatham Islands", "City" => "Oamaru, Mount Cook, Twizel, Kurow"),
    "342" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "341" => array ( "Area" => "South Island and Chatham Islands", "City" => "Balclutha, Milton"),
    "339" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "338" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch (eastern suburbs)"),
    "337" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch (central city)"),
    "336" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch (central city)"),
    "335" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch (northern suburbs)"),
    "334" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch (western suburbs and Rolleston)"),
    "333" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch (southern suburbs)"),
    "332" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch (satellite towns)"),
    "331" => array ( "Area" => "South Island and Chatham Islands", "City" => "Rangiora, Amberley, Culverden, Darfield, Cheviot, Kaikoura"),
    "330" => array ( "Area" => "South Island and Chatham Islands", "City" => "Ashburton, Akaroa, Chatham Islands"),
    "329" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "328" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch"),
    "327" => array ( "Area" => "South Island and Chatham Islands", "City" => "Kaiapoi"),
    "326" => array ( "Area" => "South Island and Chatham Islands", "City" => "Christchurch"),
    "325" => array ( "Area" => "South Island and Chatham Islands", "City" => ""),
    "324" => array ( "Area" => "South Island and Chatham Islands", "City" => "Tokanui, Lumsden, Te Anau"),
    "323" => array ( "Area" => "South Island and Chatham Islands", "City" => "Riverton, Winton"),
    "322" => array ( "Area" => "South Island and Chatham Islands", "City" => "Otautau"),
    "321" => array ( "Area" => "South Island and Chatham Islands", "City" => "Invercargill (includes Stewart Island / Rakiura)"),
    "320" => array ( "Area" => "South Island and Chatham Islands", "City" => "Gore, Ededale"),
    "98" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (western suburbs)"),
    "97" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => ""),
    "96" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (central-western suburbs)"),
    "95" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (central-eastern and eastern suburbs)"),
    "93" => array ( "Area" => "Auckland, Northland, Tuakau and Pokeno", "City" => "Auckland (inner city and Waiheke Island)"),
    "77" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "74" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "72" => array ( "Area" => "Waikato (excluding Tuakau and Pokeno) and the Bay of Plenty", "City" => ""),
    "66" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "65" => array ( "Area" => "Taranaki, Manawatū-Whanganui (excluding Taumarunui and National Park), Hawke's Bay, Gisborne, the Wairarapa, and Otaki", "City" => ""),
    "47" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "46" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => ""),
    "45" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington (Hutt Valley)"),
    "44" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington (central, western and northern suburbs)"),
    "43" => array ( "Area" => "Wellington metro area and Kapiti Coast district (excluding Otaki)", "City" => "Wellington (southern and eastern suburbs)")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "64", "NDC" => substr ( $parameters["Number"], 3, 1), "Country" => "New Zealand", "Area" => $data["Area"], "City" => $data["City"], "Operator" => "", "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+64 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Tollfree network
   */
  $prefixes = array (
    "508" => array ( "Min" => 7, "Max" => 7),
    "85" => array ( "Min" => 8, "Max" => 13),
    "80" => array ( "Min" => 8, "Max" => 10)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) >= 3 + $data["Min"] && strlen ( $parameters["Number"]) <= 3 + $data["Min"] && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "64", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "New Zealand", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+64 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for PRN network
   */
  if ( substr ( $parameters["Number"], 3, 2) == "90" && strlen ( $parameters["Number"]) >= 12 && strlen ( $parameters["Number"]) <= 14)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "64", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "New Zealand", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+64 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * If reached here, number wasn't identified as a valid New Zealian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
