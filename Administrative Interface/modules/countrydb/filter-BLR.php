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
 * related to country database of Belarus.
 *
 * Reference: https://www.itu.int/oth/T0202000014/en (2019-05-24)
 * Reference: https://www.beltelecom.by/en/private/telephony/phone-codes (2019-08)
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
 * E.164 Belarus country hook
 */
framework_add_filter ( "e164_identify_country_BLR", "e164_identify_country_BLR");

/**
 * E.164 Belarusian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "BLR" (code for
 * Belarus). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_BLR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Belarus
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+375")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Belarus has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "299" => "Unitary Enterprise Velcom",
    "298" => "Mobile TeleSystems",
    "297" => "Mobile TeleSystems",
    "296" => "Unitary Enterprise Velcom",
    "295" => "Mobile TeleSystems",
    "293" => "Unitary Enterprise Velcom",
    "292" => "Mobile TeleSystems",
    "291" => "Unitary Enterprise Velcom",
    "259" => "Belarusian Telecommunications Network",
    "257" => "Belarusian Telecommunications Network",
    "256" => "Belarusian Telecommunications Network",
    "255" => "Belarusian Telecommunications Network",
    "44" => "Unitary Enterprise Velcom",
    "33" => "Mobile TeleSystems"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "375", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Belarus", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+375 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "2357" => "Oktyabrskiy",
    "2356" => "Lelchitsy",
    "2355" => "Narovlya",
    "2354" => "Yelsk",
    "2353" => "Zhitkovichi",
    "2350" => "Petrikov",
    "2347" => "Loyev",
    "2346" => "Khoyniki",
    "2345" => "Kalinkovichi",
    "2344" => "Bragin",
    "2342" => "Svetlogorsk",
    "2340" => "Rechitsa",
    "2339" => "Rogachev",
    "2337" => "Korma",
    "2336" => "Budo-Koshelevo",
    "2334" => "Zhlobin",
    "2333" => "Dobrush",
    "2332" => "Chechersk",
    "2330" => "Vetka",
    "2248" => "Dribin",
    "2247" => "Khotimsk",
    "2246" => "Slavgorod",
    "2245" => "Kostyukovichi",
    "2244" => "Klimovichi",
    "2243" => "Cherikov",
    "2242" => "Chausy",
    "2241" => "Krichev",
    "2240" => "Mstislavl",
    "2239" => "Shklov",
    "2238" => "Krasnopolye",
    "2237" => "Kirovsk",
    "2236" => "Klichev",
    "2235" => "Osipovichi",
    "2234" => "Krugloye",
    "2233" => "Gorki",
    "2232" => "Belynichi",
    "2231" => "Byhov",
    "2230" => "Gluzsk",
    "2159" => "Rossony",
    "2158" => "Ushachi",
    "2157" => "Dokshitsy",
    "2156" => "Glubokoye",
    "2155" => "Postavy",
    "2154" => "Sharkovshchina",
    "2153" => "Braslav",
    "2152" => "Miory",
    "2151" => "Verhnedvinsk",
    "2139" => "Gorodok",
    "2138" => "Liozno",
    "2137" => "Dubrovno",
    "2136" => "Tolochin",
    "2135" => "Senno",
    "2133" => "Chashniki",
    "2132" => "Lepel",
    "2131" => "Beshenkovichi",
    "2130" => "Shumilino",
    "1797" => "Myadel",
    "1796" => "Krupki",
    "1795" => "Slutsk",
    "1794" => "Lyuban",
    "1793" => "Kletsk",
    "1792" => "Starye Dorogi",
    "1776" => "Smolevichi",
    "1775" => "Zhodino",
    "1774" => "Logoysk",
    "1772" => "Volozhin",
    "1771" => "Vileyka",
    "1770" => "Nesvizh",
    "1719" => "Kopyl",
    "1718" => "Uzda",
    "1717" => "Stolbtsy",
    "1716" => "Dzerzhinsk",
    "1715" => "Berezino",
    "1714" => "Cherven",
    "1713" => "Maryina Gorka",
    "1655" => "Stolin",
    "1652" => "Ivanovo",
    "1651" => "Malorita",
    "1647" => "Luninets",
    "1646" => "Gantsevichi",
    "1645" => "Ivatsevichi",
    "1644" => "Drogichin",
    "1643" => "Bereza",
    "1642" => "Kobrin",
    "1641" => "Zhabinka",
    "1633" => "Lyakhovichi",
    "1632" => "Pruzhany",
    "1631" => "Kamenets",
    "1597" => "Novogrudok",
    "1596" => "Korelichi",
    "1595" => "Ivye",
    "1594" => "Voronovo",
    "1593" => "Oshmyany",
    "1592" => "Smorgon",
    "1591" => "Ostrovets",
    "1564" => "Zelva",
    "1563" => "Dyatlovo",
    "1562" => "Slonim",
    "1515" => "Mosty",
    "1514" => "Shchuchin",
    "1513" => "Svisloch",
    "1512" => "Volkovysk",
    "1511" => "Berestovitsa",
    "236" => "Mozyr",
    "232" => "Gomel",
    "225" => "Bobruysk",
    "222" => "Mogilev",
    "216" => "Orsha",
    "214" => "Polotsk",
    "212" => "Vitebsk",
    "177" => "Borisov",
    "176" => "Molodechno",
    "174" => "Soligorsk",
    "165" => "Pinsk",
    "163" => "Baranovichi",
    "162" => "Brest",
    "154" => "Lida",
    "152" => "Grodno",
    "17" => "Minsk"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "375", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Belarus", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+375 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Belarusian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
