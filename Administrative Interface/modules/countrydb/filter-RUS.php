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
 * related to country database of Russia.
 *
 * Reference: https://www.itu.int/oth/T02020000AD/en (2022-05-16)
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
 * E.164 Russia country hook
 */
framework_add_filter ( "e164_identify_country_RUS", "e164_identify_country_RUS");

/**
 * E.164 Russian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "RUS" (code for Russia). This
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
function e164_identify_country_RUS ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Russia
   */
  if ( substr ( $parameters["Number"], 0, 2) != "+7")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Russia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "979",
    "978",
    "977",
    "976",
    "975",
    "974",
    "973",
    "972",
    "959",
    "958",
    "957",
    "956",
    "955",
    "953",
    "952",
    "951",
    "950",
    "99",
    "98",
    "96",
    "94",
    "93",
    "92",
    "91",
    "90"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 2, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "7", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Russia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+7 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "879" => "Stavropol territory (Mineralnye Vody)",
    "878" => "Karachayevo-Cherkessia Republic (Cherkessk)",
    "877" => "Republic of Adygea (Maikop)",
    "873" => "Republic of Ingushetia (Nazran)",
    "872" => "Republic of Dagestan (Makhachkala)",
    "871" => "Chechen Republic (Grozny)",
    "869" => "Sevastopol (city)",
    "867" => "Republic of North Ossetia – Alania (Vladikavkaz)",
    "866" => "Kabardino-Balkarian Republic (Nalchik)",
    "865" => "Stavropol region (Stavropol)",
    "863" => "Rostov region (Rostov-on Don)",
    "862" => "Krasnodar territory (Sochi)",
    "861" => "Krasnodar territory (Krasnodar)",
    "855" => "Republic of Tatarstan (Naberezhnye Chelny)",
    "851" => "Astrakhan region (Astrakhan)",
    "848" => "Samara Region (Tolyatti)",
    "847" => "Republic of Kalmykia (Elista)",
    "846" => "Samara region (Samara)",
    "845" => "Saratov region (Saratov)",
    "844" => "Volgograd region (Volgograd)",
    "843" => "Republic of Tatarstan (Kazan)",
    "842" => "Ulyanovsk region (Ulyanovsk)",
    "841" => "Penza region (Penza)",
    "836" => "Republic of Mari El (Yoshkar-Ola)",
    "835" => "Chuvash Republic (Cheboksary)",
    "834" => "Republic of Mordovia (Saransk)",
    "833" => "Kirov region (Kirov)",
    "831" => "Nizhny Novgorod (Nizhny Novgorod)",
    "821" => "Komi Republic (Syktyvkar)",
    "820" => "Vologda region (Cherepovets)",
    "818" => "Arkhangelsk region (Arkhangelsk)",
    "817" => "Vologda region (Vologda)",
    "816" => "Novgorod region (Velikiy Novgorod)",
    "815" => "Murmansk region (Murmansk)",
    "814" => "Republic of Karelia (Petrozavodsk)",
    "813" => "Leningrad region",
    "812" => "Saint Petersburg (city)",
    "811" => "Pskov region (Pskov)",
    "499" => "Moscow region",
    "498" => "Moscow region",
    "496" => "Moscow (city)",
    "495" => "Moscow (city)",
    "494" => "Kostroma region (Kostroma)",
    "493" => "Ivanovo region (Ivanovo)",
    "492" => "Vladimir region (Vladimir)",
    "491" => "Ryazan region (Ryazan)",
    "487" => "Tula region (Tula)",
    "486" => "Orel region (Orel)",
    "485" => "Yaroslavl region (Yaroslavl)",
    "483" => "Bryansk region (Bryansk)",
    "482" => "Tver region (Tver)",
    "481" => "Smolensk region (Smolensk)",
    "475" => "Tambov region (Tambov)",
    "474" => "Lipetsk region (Lipetsk)",
    "473" => "Voronezh region (Voronezh)",
    "472" => "Belgorod region (Belgorod)",
    "471" => "Kursk region (Kursk)",
    "427" => "Chukotka autonomous Okrug (Anadyr)",
    "426" => "Jewish autonomous Region (Birobidzhan)",
    "424" => "Sakhalin Region (Yuzhno-Sakhalinsk)",
    "423" => "Primorsky territory (Vladivostok)",
    "421" => "Khabarovsk territory (Khabarovsk)",
    "416" => "Amur Region (Blagoveshchensk)",
    "415" => "Kamchatka region, Koryak autonomous region (Petropavlovsk-Kamchatsky)",
    "413" => "Magadan region (Magadan)",
    "411" => "Republic of Sakha (Yakutia) (Yakutsk)",
    "401" => "Kaliningrad region (Kaliningrad)",
    "395" => "Irkutsk region (Irkutsk)",
    "394" => "Republic of Tyva (Kyzyl)",
    "391" => "Krasnoyarsk Territory, Evenk autonomous region, Taimyr (Dolgan-Nenets) autonomous region (Krasnoyarsk)",
    "390" => "Republic of Khakassia (Abakan)",
    "388" => "Republic of Altai (Gorno-Altaisk)",
    "385" => "Altai territory (Barnaul)",
    "384" => "Kemerovo region (Kemerovo)",
    "383" => "Novosibirsk region (Novosibirsk)",
    "382" => "Tomsk region (Tomsk)",
    "381" => "Omsk region (Omsk)",
    "365" => "Republic of Crimea (Simferopol)",
    "353" => "Orenburg region (Orenburg)",
    "352" => "Kurgan region (Kurgan)",
    "351" => "Chelyabinsk region (Chelyabinsk)",
    "349" => "Yamalo-Nenets autonomous region (Salekhard)",
    "347" => "Republic of Bashkortostan (Ufa)",
    "346" => "Khanty – Mansiysk autonomous region – Yugra (Surgut)",
    "345" => "Tyumen region (Tyumen)",
    "343" => "Sverdlovsk region (Ekaterinburg)",
    "342" => "Perm territory (Perm)",
    "341" => "Republic of Udmurtia (Izhevsk)",
    "336" => "Baikonur",
    "302" => "Chita region, Agin-Buryat autonomous region (Chita)",
    "301" => "Republic of Buryatia (Ulan – Ude)"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 2, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "7", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Russia", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+7 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 2, 3) == "800")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "7", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Russia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+7 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * Check for PRN network with 3 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 2, 3) == "809")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "7", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Russia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+7 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * If reached here, number wasn't identified as a valid Russian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
