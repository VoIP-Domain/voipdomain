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
 * related to country database of Armenia.
 *
 * Reference: https://www.itu.int/oth/T020200000A/en (2018-03-29)
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
 * E.164 Armenia country hook
 */
framework_add_filter ( "e164_identify_country_ARM", "e164_identify_country_ARM");

/**
 * E.164 Armenian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "ARM" (code for Armenia). This
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
function e164_identify_country_ARM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Armenia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+374")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Armenia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network
   */
  $prefixes = array (
    "3226" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "3126" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "VEON Armenia (Beeline)"),
    "2876" => array ( "area" => "Vayots Dzor", "city" => "Jermuk", "operator" => "VEON Armenia (Beeline)"),
    "2866" => array ( "area" => "Syunik", "city" => "Meghri", "operator" => "VEON Armenia (Beeline)"),
    "2856" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "VEON Armenia (Beeline)"),
    "2846" => array ( "area" => "Syunik", "city" => "Goris", "operator" => "VEON Armenia (Beeline)"),
    "2836" => array ( "area" => "Syunik", "city" => "Sisian", "operator" => "VEON Armenia (Beeline)"),
    "2826" => array ( "area" => "Vayots Dzor", "city" => "Vaik", "operator" => "VEON Armenia (Beeline)"),
    "2816" => array ( "area" => "Vayots Dzor", "city" => "Yeghegnadzor", "operator" => "VEON Armenia (Beeline)"),
    "2696" => array ( "area" => "Gegharkunik", "city" => "Vardenis", "operator" => "VEON Armenia (Beeline)"),
    "2686" => array ( "area" => "Tavush", "city" => "Dilijan", "operator" => "VEON Armenia (Beeline)"),
    "2676" => array ( "area" => "Tavush", "city" => "Berd", "operator" => "VEON Armenia (Beeline)"),
    "2666" => array ( "area" => "Tavush", "city" => "Noyemberian", "operator" => "VEON Armenia (Beeline)"),
    "2656" => array ( "area" => "Gegharkunik", "city" => "Chambarak", "operator" => "VEON Armenia (Beeline)"),
    "2646" => array ( "area" => "Gegharkunik", "city" => "Gavar", "operator" => "VEON Armenia (Beeline)"),
    "2636" => array ( "area" => "Tavush", "city" => "Ijevan", "operator" => "VEON Armenia (Beeline)"),
    "2626" => array ( "area" => "Gegharkunik", "city" => "Martuni", "operator" => "VEON Armenia (Beeline)"),
    "2616" => array ( "area" => "Gegharkunik", "city" => "Sevan", "operator" => "VEON Armenia (Beeline)"),
    "2576" => array ( "area" => "Aragatsotn", "city" => "Tsaghkahovit", "operator" => "VEON Armenia (Beeline)"),
    "2566" => array ( "area" => "Lori", "city" => "Stepanavan", "operator" => "VEON Armenia (Beeline)"),
    "2556" => array ( "area" => "Lori", "city" => "Spitak", "operator" => "VEON Armenia (Beeline)"),
    "2546" => array ( "area" => "Lori", "city" => "Tashir", "operator" => "VEON Armenia (Beeline)"),
    "2536" => array ( "area" => "Lori", "city" => "Alaverdi", "operator" => "VEON Armenia (Beeline)"),
    "2526" => array ( "area" => "Aragatsotn", "city" => "Aparan", "operator" => "VEON Armenia (Beeline)"),
    "2496" => array ( "area" => "Aragatsotn", "city" => "Talin", "operator" => "VEON Armenia (Beeline)"),
    "2466" => array ( "area" => "Shirak", "city" => "Amasia Region", "operator" => "Ucom"),
    "2456" => array ( "area" => "Shirak", "city" => "Ashotsk Region", "operator" => "VEON Armenia (Beeline)"),
    "2446" => array ( "area" => "Shirak", "city" => "Artik", "operator" => "VEON Armenia (Beeline)"),
    "2426" => array ( "area" => "Shirak", "city" => "Maralik", "operator" => "VEON Armenia (Beeline)"),
    "2376" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "VEON Armenia (Beeline)"),
    "2366" => array ( "area" => "Ararat", "city" => "Masis", "operator" => "VEON Armenia (Beeline)"),
    "2356" => array ( "area" => "Ararat", "city" => "Artashat", "operator" => "VEON Armenia (Beeline)"),
    "2346" => array ( "area" => "Ararat", "city" => "Vedi and Ararat", "operator" => "VEON Armenia (Beeline)"),
    "2336" => array ( "area" => "Armavir", "city" => "Baghramian", "operator" => "VEON Armenia (Beeline)"),
    "2326" => array ( "area" => "Aragatsotn", "city" => "Ashtarak", "operator" => "VEON Armenia (Beeline)"),
    "2316" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "VEON Armenia (Beeline)"),
    "2266" => array ( "area" => "Kotayk", "city" => "Charentsavan", "operator" => "VEON Armenia (Beeline)"),
    "2246" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "VEON Armenia (Beeline)"),
    "2236" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "VEON Armenia (Beeline)"),
    "2226" => array ( "area" => "Kotayk", "city" => "Abovyan", "operator" => "VEON Armenia (Beeline)"),
    "99" => array ( "area" => "", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "98" => array ( "area" => "", "city" => "", "operator" => "MTS Armenia (Vivacell MTS)"),
    "96" => array ( "area" => "", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "95" => array ( "area" => "", "city" => "", "operator" => "Ucom GSM (Ucom)"),
    "94" => array ( "area" => "", "city" => "", "operator" => "MTS Armenia (Vivacell MTS)"),
    "93" => array ( "area" => "", "city" => "", "operator" => "MTS Armenia (Vivacell MTS)"),
    "91" => array ( "area" => "", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "88" => array ( "area" => "", "city" => "", "operator" => "MTS Armenia (Vivacell MTS)"),
    "77" => array ( "area" => "", "city" => "", "operator" => "MTS Armenia (Vivacell MTS)"),
    "45" => array ( "area" => "", "city" => "", "operator" => "Ucom GSM (Ucom)"),
    "44" => array ( "area" => "", "city" => "", "operator" => "Ucom GSM (Ucom)"),
    "43" => array ( "area" => "", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "41" => array ( "area" => "", "city" => "", "operator" => "Ucom GSM (Ucom)")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "374", "NDC" => substr ( $parameters["Number"], 4, ( strlen ( $prefix) == 2 ? 2 : 3)), "Country" => "Armenia", "Area" => $data["area"], "City" => $data["city"], "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], ( strlen ( $prefix) == 2 ? 6 : 7)), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 4, ( strlen ( $prefix) == 2 ? 2 : 3)) . ") " . substr ( $parameters["Number"], 4 + ( strlen ( $prefix) == 2 ? 2 : 3)), "International" => "+374 " . substr ( $parameters["Number"], 4, ( strlen ( $prefix) == 2 ? 2 : 3)) . " " . substr ( $parameters["Number"], 4 + ( strlen ( $prefix) == 2 ? 2 : 3)))));
    }
  }

  /**
   * Check for fixed line network
   */
  $prefixes = array (
    "312859" => array ( "area" => "Shirak", "city" => "Akhurian Region", "operator" => "GNC-Alfa (Rostelecom)"),
    "312858" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "GNC-Alfa (Rostelecom)"),
    "312857" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "GNC-Alfa (Rostelecom)"),
    "312856" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "GNC-Alfa (Rostelecom)"),
    "312855" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "GNC-Alfa (Rostelecom)"),
    "312854" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "GNC-Alfa (Rostelecom)"),
    "312853" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "GNC-Alfa (Rostelecom)"),
    "312852" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "GNC-Alfa (Rostelecom)"),
    "312851" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "GNC-Alfa (Rostelecom)"),
    "312850" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "GNC-Alfa (Rostelecom)"),
    "286819" => array ( "area" => "Syunik", "city" => "Agarak and Shvanidzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "286818" => array ( "area" => "Syunik", "city" => "Agarak and Shvanidzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "286817" => array ( "area" => "Syunik", "city" => "Agarak and Shvanidzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "286816" => array ( "area" => "Syunik", "city" => "Agarak and Shvanidzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "286815" => array ( "area" => "Syunik", "city" => "Agarak and Shvanidzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "286814" => array ( "area" => "Syunik", "city" => "Meghri", "operator" => "GNC-Alfa (Rostelecom)"),
    "286813" => array ( "area" => "Syunik", "city" => "Meghri", "operator" => "GNC-Alfa (Rostelecom)"),
    "286812" => array ( "area" => "Syunik", "city" => "Meghri", "operator" => "GNC-Alfa (Rostelecom)"),
    "286811" => array ( "area" => "Syunik", "city" => "Meghri", "operator" => "GNC-Alfa (Rostelecom)"),
    "286810" => array ( "area" => "Syunik", "city" => "Meghri", "operator" => "GNC-Alfa (Rostelecom)"),
    "285819" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "GNC-Alfa (Rostelecom)"),
    "285818" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "GNC-Alfa (Rostelecom)"),
    "285817" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "GNC-Alfa (Rostelecom)"),
    "285816" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "GNC-Alfa (Rostelecom)"),
    "285815" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "GNC-Alfa (Rostelecom)"),
    "285814" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "GNC-Alfa (Rostelecom)"),
    "285813" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "GNC-Alfa (Rostelecom)"),
    "285812" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "GNC-Alfa (Rostelecom)"),
    "285811" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "GNC-Alfa (Rostelecom)"),
    "285810" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "GNC-Alfa (Rostelecom)"),
    "282929" => array ( "area" => "Vayots Dzor", "city" => "Vaik", "operator" => "VEON Armenia (Beeline)"),
    "282928" => array ( "area" => "Vayots Dzor", "city" => "Vaik", "operator" => "VEON Armenia (Beeline)"),
    "243006" => array ( "area" => "Shirak", "city" => "Arapi", "operator" => "VEON Armenia (Beeline)"),
    "243005" => array ( "area" => "Shirak", "city" => "Arapi", "operator" => "VEON Armenia (Beeline)"),
    "237819" => array ( "area" => "Armavir", "city" => "Metsamor, V. Armavir, Bambakashat, Mrgashat, Nalbandyan and Tandzyt", "operator" => "GNC-Alfa (Rostelecom)"),
    "237818" => array ( "area" => "Armavir", "city" => "Metsamor, V. Armavir, Bambakashat, Mrgashat, Nalbandyan and Tandzyt", "operator" => "GNC-Alfa (Rostelecom)"),
    "237817" => array ( "area" => "Armavir", "city" => "Metsamor, V. Armavir, Bambakashat, Mrgashat, Nalbandyan and Tandzyt", "operator" => "GNC-Alfa (Rostelecom)"),
    "237816" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "GNC-Alfa (Rostelecom)"),
    "237815" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "GNC-Alfa (Rostelecom)"),
    "237814" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "GNC-Alfa (Rostelecom)"),
    "237813" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "GNC-Alfa (Rostelecom)"),
    "237812" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "GNC-Alfa (Rostelecom)"),
    "237811" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "GNC-Alfa (Rostelecom)"),
    "237810" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "GNC-Alfa (Rostelecom)"),
    "234519" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "234518" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "234517" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "234516" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "234515" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "234514" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "GNC-Alfa (Rostelecom)"),
    "234513" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "GNC-Alfa (Rostelecom)"),
    "234512" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "GNC-Alfa (Rostelecom)"),
    "234511" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "GNC-Alfa (Rostelecom)"),
    "234510" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "GNC-Alfa (Rostelecom)"),
    "231819" => array ( "area" => "Armavir", "city" => "Zvartnots", "operator" => "GNC-Alfa (Rostelecom)"),
    "231818" => array ( "area" => "Armavir", "city" => "Zvartnots", "operator" => "GNC-Alfa (Rostelecom)"),
    "231817" => array ( "area" => "Armavir", "city" => "Zvartnots", "operator" => "GNC-Alfa (Rostelecom)"),
    "231816" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "GNC-Alfa (Rostelecom)"),
    "231815" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "GNC-Alfa (Rostelecom)"),
    "231814" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "GNC-Alfa (Rostelecom)"),
    "231813" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "GNC-Alfa (Rostelecom)"),
    "231812" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "GNC-Alfa (Rostelecom)"),
    "231811" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "GNC-Alfa (Rostelecom)"),
    "231810" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "GNC-Alfa (Rostelecom)"),
    "224819" => array ( "area" => "Kotayk", "city" => "Nor-Hachn", "operator" => "GNC-Alfa (Rostelecom)"),
    "224818" => array ( "area" => "Kotayk", "city" => "Nor-Hachn", "operator" => "GNC-Alfa (Rostelecom)"),
    "224817" => array ( "area" => "Kotayk", "city" => "Nor-Hachn", "operator" => "GNC-Alfa (Rostelecom)"),
    "224816" => array ( "area" => "Kotayk", "city" => "Nor-Hachn", "operator" => "GNC-Alfa (Rostelecom)"),
    "224815" => array ( "area" => "Kotayk", "city" => "Nor-Hachn", "operator" => "GNC-Alfa (Rostelecom)"),
    "224814" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "GNC-Alfa (Rostelecom)"),
    "224813" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "GNC-Alfa (Rostelecom)"),
    "224812" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "GNC-Alfa (Rostelecom)"),
    "224811" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "GNC-Alfa (Rostelecom)"),
    "224810" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "GNC-Alfa (Rostelecom)"),
    "223819" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "223818" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "223817" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "223816" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "223815" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "223814" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "GNC-Alfa (Rostelecom)"),
    "223813" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "GNC-Alfa (Rostelecom)"),
    "223812" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "GNC-Alfa (Rostelecom)"),
    "223811" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "GNC-Alfa (Rostelecom)"),
    "223810" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "GNC-Alfa (Rostelecom)"),
    "32281" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "GNC-Alfa (Rostelecom)"),
    "32266" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "32265" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "32264" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "32263" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "32262" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "32261" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "32260" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "31284" => array ( "area" => "Shirak", "city" => "Akhurian Region", "operator" => "Ucom"),
    "31283" => array ( "area" => "Shirak", "city" => "Akhurian Region", "operator" => "Ucom"),
    "31282" => array ( "area" => "Shirak", "city" => "Akhurian Region", "operator" => "Ucom"),
    "31281" => array ( "area" => "Shirak", "city" => "Akhurian Region", "operator" => "Ucom"),
    "31280" => array ( "area" => "Shirak", "city" => "Akhurian Region", "operator" => "Ucom"),
    "28794" => array ( "area" => "Vayots Dzor", "city" => "Gndevaz", "operator" => "VEON Armenia (Beeline)"),
    "28781" => array ( "area" => "Vayots Dzor", "city" => "Jermuk and Gndevaz", "operator" => "GNC-Alfa (Rostelecom)"),
    "28695" => array ( "area" => "Syunik", "city" => "Shvanidzor", "operator" => "VEON Armenia (Beeline)"),
    "28549" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "Ucom"),
    "28548" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "Ucom"),
    "28547" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "Ucom"),
    "28546" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "Ucom"),
    "28545" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "Ucom"),
    "28544" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "Ucom"),
    "28543" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "Ucom"),
    "28542" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "Ucom"),
    "28541" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "Ucom"),
    "28540" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "Ucom"),
    "28481" => array ( "area" => "Syunik", "city" => "Goris Region", "operator" => "GNC-Alfa (Rostelecom)"),
    "28351" => array ( "area" => "Syunik", "city" => "Sisian Region", "operator" => "GNC-Alfa (Rostelecom)"),
    "28281" => array ( "area" => "Vayots Dzor", "city" => "Vaik and Vaik Region", "operator" => "GNC-Alfa (Rostelecom)"),
    "28199" => array ( "area" => "Vayots Dzor", "city" => "Shatin", "operator" => "VEON Armenia (Beeline)"),
    "28195" => array ( "area" => "Vayots Dzor", "city" => "Malishka", "operator" => "VEON Armenia (Beeline)"),
    "28181" => array ( "area" => "Vayots Dzor", "city" => "Yeghegnadzor, Malishka and Shatin", "operator" => "GNC-Alfa (Rostelecom)"),
    "26981" => array ( "area" => "Gegharkunik", "city" => "Vardenis", "operator" => "GNC-Alfa (Rostelecom)"),
    "26897" => array ( "area" => "Tavush", "city" => "Teghut", "operator" => "VEON Armenia (Beeline)"),
    "26895" => array ( "area" => "Tavush", "city" => "Haghartsin", "operator" => "VEON Armenia (Beeline)"),
    "26881" => array ( "area" => "Tavush", "city" => "Dilijan, Haghartsin and Teghut", "operator" => "GNC-Alfa (Rostelecom)"),
    "26797" => array ( "area" => "Tavush", "city" => "Norashen", "operator" => "VEON Armenia (Beeline)"),
    "26796" => array ( "area" => "Tavush", "city" => "Mosesgegh", "operator" => "VEON Armenia (Beeline)"),
    "26791" => array ( "area" => "Tavush", "city" => "Navur", "operator" => "VEON Armenia (Beeline)"),
    "26781" => array ( "area" => "Tavush", "city" => "Berd, Mosesgegh, Navur and Norashen", "operator" => "GNC-Alfa (Rostelecom)"),
    "26699" => array ( "area" => "Tavush", "city" => "Koti", "operator" => "VEON Armenia (Beeline)"),
    "26696" => array ( "area" => "Tavush", "city" => "Voskepar", "operator" => "VEON Armenia (Beeline)"),
    "26681" => array ( "area" => "Tavush", "city" => "Noyemberian, Voskepar, Koti and Koghb", "operator" => "GNC-Alfa (Rostelecom)"),
    "26653" => array ( "area" => "Tavush", "city" => "Koghb", "operator" => "VEON Armenia (Beeline)"),
    "26652" => array ( "area" => "Tavush", "city" => "Koghb", "operator" => "VEON Armenia (Beeline)"),
    "26596" => array ( "area" => "Gegharkunik", "city" => "Vahan", "operator" => "VEON Armenia (Beeline)"),
    "26581" => array ( "area" => "Gegharkunik", "city" => "Chambarak and Vahan", "operator" => "GNC-Alfa (Rostelecom)"),
    "26481" => array ( "area" => "Gegharkunik", "city" => "Gavar", "operator" => "GNC-Alfa (Rostelecom)"),
    "26392" => array ( "area" => "Tavush", "city" => "Achajur", "operator" => "VEON Armenia (Beeline)"),
    "26381" => array ( "area" => "Tavush", "city" => "Ijevan, Aygehovit and Achajur", "operator" => "GNC-Alfa (Rostelecom)"),
    "26374" => array ( "area" => "Tavush", "city" => "Aygehovit", "operator" => "VEON Armenia (Beeline)"),
    "26281" => array ( "area" => "Gegharkunik", "city" => "Martuni and Vardenik", "operator" => "GNC-Alfa (Rostelecom)"),
    "26253" => array ( "area" => "Gegharkunik", "city" => "Vardenik", "operator" => "VEON Armenia (Beeline)"),
    "26252" => array ( "area" => "Gegharkunik", "city" => "Vardenik", "operator" => "VEON Armenia (Beeline)"),
    "26181" => array ( "area" => "Gegharkunik", "city" => "Sevan", "operator" => "GNC-Alfa (Rostelecom)"),
    "25781" => array ( "area" => "Aragatsotn", "city" => "Tsaghkahovit Region", "operator" => "GNC-Alfa (Rostelecom)"),
    "25702" => array ( "area" => "Aragatsotn", "city" => "Tsaghkahovit", "operator" => "VEON Armenia (Beeline)"),
    "25681" => array ( "area" => "Lori", "city" => "Stepanavan", "operator" => "GNC-Alfa (Rostelecom)"),
    "25581" => array ( "area" => "Lori", "city" => "Spitak", "operator" => "GNC-Alfa (Rostelecom)"),
    "25494" => array ( "area" => "Lori", "city" => "Metsavan", "operator" => "VEON Armenia (Beeline)"),
    "25481" => array ( "area" => "Lori", "city" => "Tashir and Metsavan", "operator" => "GNC-Alfa (Rostelecom)"),
    "25381" => array ( "area" => "Lori", "city" => "Alaverdi, Akhtala and Tumanyan", "operator" => "GNC-Alfa (Rostelecom)"),
    "25357" => array ( "area" => "Lori", "city" => "Tumanyan", "operator" => "VEON Armenia (Beeline)"),
    "25352" => array ( "area" => "Lori", "city" => "Akhtala", "operator" => "VEON Armenia (Beeline)"),
    "25295" => array ( "area" => "Aragatsotn", "city" => "Artavan", "operator" => "VEON Armenia (Beeline)"),
    "25291" => array ( "area" => "Aragatsotn", "city" => "Quchak", "operator" => "VEON Armenia (Beeline)"),
    "25281" => array ( "area" => "Aragatsotn", "city" => "Aparan, Artavan and Quchak", "operator" => "GNC-Alfa (Rostelecom)"),
    "24997" => array ( "area" => "Aragatsotn", "city" => "Mastara", "operator" => "VEON Armenia (Beeline)"),
    "24995" => array ( "area" => "Aragatsotn", "city" => "Aragats", "operator" => "VEON Armenia (Beeline)"),
    "24981" => array ( "area" => "Aragatsotn", "city" => "Talin, Aragats, Katnaghbyur and Mastara", "operator" => "GNC-Alfa (Rostelecom)"),
    "24973" => array ( "area" => "Aragatsotn", "city" => "Katnaghbyur", "operator" => "VEON Armenia (Beeline)"),
    "24681" => array ( "area" => "Shirak", "city" => "Amasia Region", "operator" => "GNC-Alfa (Rostelecom)"),
    "24581" => array ( "area" => "Shirak", "city" => "Ashotsk Region", "operator" => "GNC-Alfa (Rostelecom)"),
    "24492" => array ( "area" => "Shirak", "city" => "Panik", "operator" => "VEON Armenia (Beeline)"),
    "24481" => array ( "area" => "Shirak", "city" => "Artik and Panik", "operator" => "GNC-Alfa (Rostelecom)"),
    "24281" => array ( "area" => "Shirak", "city" => "Maralik and Sarnaghbyur", "operator" => "GNC-Alfa (Rostelecom)"),
    "24231" => array ( "area" => "Shirak", "city" => "Sarnaghbyur", "operator" => "VEON Armenia (Beeline)"),
    "23796" => array ( "area" => "Armavir", "city" => "Tandzyt", "operator" => "VEON Armenia (Beeline)"),
    "23792" => array ( "area" => "Armavir", "city" => "Nalbandyan", "operator" => "VEON Armenia (Beeline)"),
    "23779" => array ( "area" => "Armavir", "city" => "Bambakashat", "operator" => "VEON Armenia (Beeline)"),
    "23772" => array ( "area" => "Armavir", "city" => "Mrgashat", "operator" => "VEON Armenia (Beeline)"),
    "23771" => array ( "area" => "Armavir", "city" => "V. Armavir", "operator" => "VEON Armenia (Beeline)"),
    "23749" => array ( "area" => "Armavir", "city" => "Metsamor, V. Armavir, Bambakashat, Mrgashat, Nalbandyan and Tandzyt", "operator" => "Ucom"),
    "23748" => array ( "area" => "Armavir", "city" => "Metsamor, V. Armavir, Bambakashat, Mrgashat, Nalbandyan and Tandzyt", "operator" => "Ucom"),
    "23747" => array ( "area" => "Armavir", "city" => "Metsamor, V. Armavir, Bambakashat, Mrgashat, Nalbandyan and Tandzyt", "operator" => "Ucom"),
    "23746" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "Ucom"),
    "23745" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "Ucom"),
    "23744" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "Ucom"),
    "23743" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "Ucom"),
    "23742" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "Ucom"),
    "23741" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "Ucom"),
    "23740" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "Ucom"),
    "23681" => array ( "area" => "Ararat", "city" => "Masis", "operator" => "GNC-Alfa (Rostelecom)"),
    "23593" => array ( "area" => "Ararat", "city" => "Norashen", "operator" => "VEON Armenia (Beeline)"),
    "23592" => array ( "area" => "Ararat", "city" => "Norashen", "operator" => "VEON Armenia (Beeline)"),
    "23581" => array ( "area" => "Ararat", "city" => "Artashat and Norashen", "operator" => "GNC-Alfa (Rostelecom)"),
    "23486" => array ( "area" => "Ararat", "city" => "Urtsadzor", "operator" => "VEON Armenia (Beeline)"),
    "23479" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "Ucom"),
    "23478" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "Ucom"),
    "23477" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "Ucom"),
    "23476" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "Ucom"),
    "23475" => array ( "area" => "Ararat", "city" => "Ararat and Urtsadzor", "operator" => "Ucom"),
    "23474" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "Ucom"),
    "23473" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "Ucom"),
    "23472" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "Ucom"),
    "23471" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "Ucom"),
    "23470" => array ( "area" => "Ararat", "city" => "Vedi", "operator" => "Ucom"),
    "23381" => array ( "area" => "Armavir", "city" => "Baghramian and Myasnikian", "operator" => "GNC-Alfa (Rostelecom)"),
    "23374" => array ( "area" => "Armavir", "city" => "Myasnikian", "operator" => "VEON Armenia (Beeline)"),
    "23294" => array ( "area" => "Aragatsotn", "city" => "Byurakan", "operator" => "VEON Armenia (Beeline)"),
    "23290" => array ( "area" => "Aragatsotn", "city" => "Ohanavan", "operator" => "VEON Armenia (Beeline)"),
    "23281" => array ( "area" => "Aragatsotn", "city" => "Ashtarak, Byurakan and Ohanavan", "operator" => "GNC-Alfa (Rostelecom)"),
    "22681" => array ( "area" => "Kotayk", "city" => "Charentsavan", "operator" => "GNC-Alfa (Rostelecom)"),
    "22379" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "Ucom"),
    "22378" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "Ucom"),
    "22377" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "Ucom"),
    "22376" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "Ucom"),
    "22375" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "Ucom"),
    "22374" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "Ucom"),
    "22373" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "Ucom"),
    "22372" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "Ucom"),
    "22371" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "Ucom"),
    "22370" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "Ucom"),
    "22298" => array ( "area" => "Kotayk", "city" => "Arinj", "operator" => "VEON Armenia (Beeline)"),
    "22297" => array ( "area" => "Kotayk", "city" => "Geghashen", "operator" => "VEON Armenia (Beeline)"),
    "22294" => array ( "area" => "Kotayk", "city" => "Arzni", "operator" => "VEON Armenia (Beeline)"),
    "22281" => array ( "area" => "Kotayk", "city" => "Abovyan, Arzni, Geghashen and Arinj", "operator" => "GNC-Alfa (Rostelecom)"),
    "3229" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "3227" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "Ucom"),
    "3225" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "3224" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "3223" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "3222" => array ( "area" => "Lori", "city" => "Vanadzor", "operator" => "VEON Armenia (Beeline)"),
    "3129" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "Ucom"),
    "3127" => array ( "area" => "Shirak", "city" => "Akhurian Region", "operator" => "VEON Armenia (Beeline)"),
    "3125" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "VEON Armenia (Beeline)"),
    "3124" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "VEON Armenia (Beeline)"),
    "3123" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "VEON Armenia (Beeline)"),
    "3122" => array ( "area" => "Shirak", "city" => "Gyumri", "operator" => "VEON Armenia (Beeline)"),
    "2879" => array ( "area" => "Vayots Dzor", "city" => "Jermuk", "operator" => "VEON Armenia (Beeline)"),
    "2873" => array ( "area" => "Vayots Dzor", "city" => "Jermuk and Gndevaz", "operator" => "Ucom"),
    "2872" => array ( "area" => "Vayots Dzor", "city" => "Jermuk", "operator" => "VEON Armenia (Beeline)"),
    "2869" => array ( "area" => "Syunik", "city" => "Meghri", "operator" => "VEON Armenia (Beeline)"),
    "2865" => array ( "area" => "Syunik", "city" => "Agarak and Shvanidzor", "operator" => "Ucom"),
    "2864" => array ( "area" => "Syunik", "city" => "Meghri", "operator" => "VEON Armenia (Beeline)"),
    "2863" => array ( "area" => "Syunik", "city" => "Meghri", "operator" => "Ucom"),
    "2862" => array ( "area" => "Syunik", "city" => "Agarak", "operator" => "VEON Armenia (Beeline)"),
    "2859" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "VEON Armenia (Beeline)"),
    "2855" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "VEON Armenia (Beeline)"),
    "2853" => array ( "area" => "Syunik", "city" => "Kajaran", "operator" => "VEON Armenia (Beeline)"),
    "2852" => array ( "area" => "Syunik", "city" => "Kapan", "operator" => "VEON Armenia (Beeline)"),
    "2849" => array ( "area" => "Syunik", "city" => "Goris Region", "operator" => "VEON Armenia (Beeline)"),
    "2845" => array ( "area" => "Syunik", "city" => "Goris Region", "operator" => "Ucom"),
    "2844" => array ( "area" => "Syunik", "city" => "Goris Region", "operator" => "VEON Armenia (Beeline)"),
    "2843" => array ( "area" => "Syunik", "city" => "Goris Region", "operator" => "VEON Armenia (Beeline)"),
    "2842" => array ( "area" => "Syunik", "city" => "Goris Region", "operator" => "VEON Armenia (Beeline)"),
    "2839" => array ( "area" => "Syunik", "city" => "Sisian Region", "operator" => "VEON Armenia (Beeline)"),
    "2838" => array ( "area" => "Syunik", "city" => "Sisian Region", "operator" => "VEON Armenia (Beeline)"),
    "2837" => array ( "area" => "Syunik", "city" => "Sisian Region", "operator" => "VEON Armenia (Beeline)"),
    "2833" => array ( "area" => "Syunik", "city" => "Sisian Region", "operator" => "Ucom"),
    "2832" => array ( "area" => "Syunik", "city" => "Sisian Region", "operator" => "VEON Armenia (Beeline)"),
    "2830" => array ( "area" => "Syunik", "city" => "Sisian Region", "operator" => "VEON Armenia (Beeline)"),
    "2829" => array ( "area" => "Vayots Dzor", "city" => "Vaik Region", "operator" => "VEON Armenia (Beeline)"),
    "2823" => array ( "area" => "Vayots Dzor", "city" => "Vaik and Vaik Region", "operator" => "Ucom"),
    "2822" => array ( "area" => "Vayots Dzor", "city" => "Vaik", "operator" => "VEON Armenia (Beeline)"),
    "2819" => array ( "area" => "Vayots Dzor", "city" => "Yeghegnadzor", "operator" => "VEON Armenia (Beeline)"),
    "2815" => array ( "area" => "Vayots Dzor", "city" => "Yeghegnadzor", "operator" => "VEON Armenia (Beeline)"),
    "2813" => array ( "area" => "Vayots Dzor", "city" => "Yeghegnadzor, Malishka and Shatin", "operator" => "Ucom"),
    "2812" => array ( "area" => "Vayots Dzor", "city" => "Yeghegnadzor", "operator" => "VEON Armenia (Beeline)"),
    "2699" => array ( "area" => "Gegharkunik", "city" => "Vardenis", "operator" => "VEON Armenia (Beeline)"),
    "2697" => array ( "area" => "Gegharkunik", "city" => "Vardenis", "operator" => "VEON Armenia (Beeline)"),
    "2694" => array ( "area" => "Gegharkunik", "city" => "Vardenis", "operator" => "Ucom"),
    "2693" => array ( "area" => "Gegharkunik", "city" => "Vardenis", "operator" => "VEON Armenia (Beeline)"),
    "2692" => array ( "area" => "Gegharkunik", "city" => "Vardenis", "operator" => "VEON Armenia (Beeline)"),
    "2689" => array ( "area" => "Tavush", "city" => "Dilijan", "operator" => "VEON Armenia (Beeline)"),
    "2684" => array ( "area" => "Tavush", "city" => "Dilijan, Haghartsin and Teghut", "operator" => "Ucom"),
    "2683" => array ( "area" => "Tavush", "city" => "Dilijan", "operator" => "VEON Armenia (Beeline)"),
    "2682" => array ( "area" => "Tavush", "city" => "Dilijan", "operator" => "VEON Armenia (Beeline)"),
    "2680" => array ( "area" => "Tavush", "city" => "Dilijan", "operator" => "VEON Armenia (Beeline)"),
    "2679" => array ( "area" => "Tavush", "city" => "Berd", "operator" => "VEON Armenia (Beeline)"),
    "2677" => array ( "area" => "Tavush", "city" => "Berd", "operator" => "VEON Armenia (Beeline)"),
    "2675" => array ( "area" => "Tavush", "city" => "Berd", "operator" => "VEON Armenia (Beeline)"),
    "2673" => array ( "area" => "Tavush", "city" => "Berd, Mosesgegh, Navur and Norashen", "operator" => "Ucom"),
    "2672" => array ( "area" => "Tavush", "city" => "Berd", "operator" => "VEON Armenia (Beeline)"),
    "2669" => array ( "area" => "Tavush", "city" => "Noyemberian", "operator" => "VEON Armenia (Beeline)"),
    "2667" => array ( "area" => "Tavush", "city" => "Noyemberian", "operator" => "VEON Armenia (Beeline)"),
    "2665" => array ( "area" => "Tavush", "city" => "Noyemberian", "operator" => "VEON Armenia (Beeline)"),
    "2663" => array ( "area" => "Tavush", "city" => "Noyemberian, Voskepar, Koti and Koghb", "operator" => "Ucom"),
    "2662" => array ( "area" => "Tavush", "city" => "Noyemberian", "operator" => "VEON Armenia (Beeline)"),
    "2659" => array ( "area" => "Gegharkunik", "city" => "Chambarak", "operator" => "VEON Armenia (Beeline)"),
    "2654" => array ( "area" => "Gegharkunik", "city" => "Chambarak and Vahan", "operator" => "Ucom"),
    "2653" => array ( "area" => "Gegharkunik", "city" => "Chambarak", "operator" => "VEON Armenia (Beeline)"),
    "2652" => array ( "area" => "Gegharkunik", "city" => "Chambarak", "operator" => "VEON Armenia (Beeline)"),
    "2649" => array ( "area" => "Gegharkunik", "city" => "Gavar", "operator" => "VEON Armenia (Beeline)"),
    "2645" => array ( "area" => "Gegharkunik", "city" => "Gavar", "operator" => "Ucom"),
    "2644" => array ( "area" => "Gegharkunik", "city" => "Gavar", "operator" => "VEON Armenia (Beeline)"),
    "2643" => array ( "area" => "Gegharkunik", "city" => "Gavar", "operator" => "VEON Armenia (Beeline)"),
    "2642" => array ( "area" => "Gegharkunik", "city" => "Gavar", "operator" => "VEON Armenia (Beeline)"),
    "2639" => array ( "area" => "Tavush", "city" => "Ijevan", "operator" => "VEON Armenia (Beeline)"),
    "2637" => array ( "area" => "Tavush", "city" => "Ijevan", "operator" => "VEON Armenia (Beeline)"),
    "2634" => array ( "area" => "Tavush", "city" => "Ijevan", "operator" => "VEON Armenia (Beeline)"),
    "2633" => array ( "area" => "Tavush", "city" => "Ijevan", "operator" => "VEON Armenia (Beeline)"),
    "2632" => array ( "area" => "Tavush", "city" => "Ijevan, Aygehovit and Achajur", "operator" => "Ucom"),
    "2629" => array ( "area" => "Gegharkunik", "city" => "Martuni", "operator" => "VEON Armenia (Beeline)"),
    "2627" => array ( "area" => "Gegharkunik", "city" => "Martuni", "operator" => "VEON Armenia (Beeline)"),
    "2625" => array ( "area" => "Gegharkunik", "city" => "Martuni", "operator" => "VEON Armenia (Beeline)"),
    "2624" => array ( "area" => "Gegharkunik", "city" => "Martuni", "operator" => "VEON Armenia (Beeline)"),
    "2623" => array ( "area" => "Gegharkunik", "city" => "Martuni and Vardenik", "operator" => "Ucom"),
    "2622" => array ( "area" => "Gegharkunik", "city" => "Martuni", "operator" => "VEON Armenia (Beeline)"),
    "2619" => array ( "area" => "Gegharkunik", "city" => "Sevan", "operator" => "VEON Armenia (Beeline)"),
    "2614" => array ( "area" => "Gegharkunik", "city" => "Sevan", "operator" => "Ucom"),
    "2613" => array ( "area" => "Gegharkunik", "city" => "Sevan", "operator" => "VEON Armenia (Beeline)"),
    "2612" => array ( "area" => "Gegharkunik", "city" => "Sevan", "operator" => "VEON Armenia (Beeline)"),
    "2573" => array ( "area" => "Aragatsotn", "city" => "Tsaghkahovit Region", "operator" => "Ucom"),
    "2572" => array ( "area" => "Aragatsotn", "city" => "Tsaghkahovit Region", "operator" => "VEON Armenia (Beeline)"),
    "2569" => array ( "area" => "Lori", "city" => "Stepanavan", "operator" => "VEON Armenia (Beeline)"),
    "2564" => array ( "area" => "Lori", "city" => "Stepanavan", "operator" => "Ucom"),
    "2563" => array ( "area" => "Lori", "city" => "Stepanavan", "operator" => "VEON Armenia (Beeline)"),
    "2562" => array ( "area" => "Lori", "city" => "Stepanavan", "operator" => "VEON Armenia (Beeline)"),
    "2554" => array ( "area" => "Lori", "city" => "Spitak", "operator" => "Ucom"),
    "2553" => array ( "area" => "Lori", "city" => "Spitak", "operator" => "VEON Armenia (Beeline)"),
    "2552" => array ( "area" => "Lori", "city" => "Spitak", "operator" => "VEON Armenia (Beeline)"),
    "2549" => array ( "area" => "Lori", "city" => "Tashir", "operator" => "VEON Armenia (Beeline)"),
    "2547" => array ( "area" => "Lori", "city" => "Tashir", "operator" => "VEON Armenia (Beeline)"),
    "2543" => array ( "area" => "Lori", "city" => "Tashir and Metsavan", "operator" => "Ucom"),
    "2542" => array ( "area" => "Lori", "city" => "Tashir", "operator" => "VEON Armenia (Beeline)"),
    "2537" => array ( "area" => "Lori", "city" => "Alaverdi, Akhtala and Tumanyan", "operator" => "Ucom"),
    "2535" => array ( "area" => "Lori", "city" => "Alaverdi", "operator" => "VEON Armenia (Beeline)"),
    "2534" => array ( "area" => "Lori", "city" => "Alaverdi", "operator" => "VEON Armenia (Beeline)"),
    "2533" => array ( "area" => "Lori", "city" => "Alaverdi", "operator" => "VEON Armenia (Beeline)"),
    "2532" => array ( "area" => "Lori", "city" => "Alaverdi", "operator" => "VEON Armenia (Beeline)"),
    "2529" => array ( "area" => "Aragatsotn", "city" => "Aparan", "operator" => "VEON Armenia (Beeline)"),
    "2524" => array ( "area" => "Aragatsotn", "city" => "Aparan, Artavan and Quchak", "operator" => "Ucom"),
    "2523" => array ( "area" => "Aragatsotn", "city" => "Aparan", "operator" => "VEON Armenia (Beeline)"),
    "2522" => array ( "area" => "Aragatsotn", "city" => "Aparan", "operator" => "VEON Armenia (Beeline)"),
    "2499" => array ( "area" => "Aragatsotn", "city" => "Talin", "operator" => "VEON Armenia (Beeline)"),
    "2497" => array ( "area" => "Aragatsotn", "city" => "Talin", "operator" => "VEON Armenia (Beeline)"),
    "2494" => array ( "area" => "Aragatsotn", "city" => "Talin, Aragats, Katnaghbyur and Mastara", "operator" => "Ucom"),
    "2493" => array ( "area" => "Aragatsotn", "city" => "Talin", "operator" => "VEON Armenia (Beeline)"),
    "2492" => array ( "area" => "Aragatsotn", "city" => "Talin", "operator" => "VEON Armenia (Beeline)"),
    "2464" => array ( "area" => "Shirak", "city" => "Amasia Region", "operator" => "Ucom"),
    "2463" => array ( "area" => "Shirak", "city" => "Amasia Region", "operator" => "VEON Armenia (Beeline)"),
    "2462" => array ( "area" => "Shirak", "city" => "Amasia Region", "operator" => "VEON Armenia (Beeline)"),
    "2454" => array ( "area" => "Shirak", "city" => "Ashotsk Region", "operator" => "Ucom"),
    "2453" => array ( "area" => "Shirak", "city" => "Ashotsk Region", "operator" => "VEON Armenia (Beeline)"),
    "2452" => array ( "area" => "Shirak", "city" => "Ashotsk Region", "operator" => "VEON Armenia (Beeline)"),
    "2449" => array ( "area" => "Shirak", "city" => "Artik", "operator" => "VEON Armenia (Beeline)"),
    "2445" => array ( "area" => "Shirak", "city" => "Artik", "operator" => "VEON Armenia (Beeline)"),
    "2444" => array ( "area" => "Shirak", "city" => "Artik and Panik", "operator" => "Ucom"),
    "2443" => array ( "area" => "Shirak", "city" => "Artik", "operator" => "VEON Armenia (Beeline)"),
    "2442" => array ( "area" => "Shirak", "city" => "Artik", "operator" => "VEON Armenia (Beeline)"),
    "2429" => array ( "area" => "Shirak", "city" => "Maralik", "operator" => "VEON Armenia (Beeline)"),
    "2425" => array ( "area" => "Shirak", "city" => "Maralik and Sarnaghbyur", "operator" => "Ucom"),
    "2424" => array ( "area" => "Shirak", "city" => "Maralik", "operator" => "VEON Armenia (Beeline)"),
    "2423" => array ( "area" => "Shirak", "city" => "Maralik", "operator" => "VEON Armenia (Beeline)"),
    "2422" => array ( "area" => "Shirak", "city" => "Maralik", "operator" => "VEON Armenia (Beeline)"),
    "2379" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "VEON Armenia (Beeline)"),
    "2377" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "VEON Armenia (Beeline)"),
    "2375" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "VEON Armenia (Beeline)"),
    "2373" => array ( "area" => "Armavir", "city" => "Metsamor", "operator" => "VEON Armenia (Beeline)"),
    "2372" => array ( "area" => "Armavir", "city" => "Armavir", "operator" => "VEON Armenia (Beeline)"),
    "2369" => array ( "area" => "Ararat", "city" => "Masis", "operator" => "VEON Armenia (Beeline)"),
    "2365" => array ( "area" => "Ararat", "city" => "Masis", "operator" => "Ucom"),
    "2364" => array ( "area" => "Ararat", "city" => "Masis", "operator" => "VEON Armenia (Beeline)"),
    "2363" => array ( "area" => "Ararat", "city" => "Masis", "operator" => "VEON Armenia (Beeline)"),
    "2362" => array ( "area" => "Ararat", "city" => "Masis", "operator" => "VEON Armenia (Beeline)"),
    "2359" => array ( "area" => "Ararat", "city" => "Artashat", "operator" => "VEON Armenia (Beeline)"),
    "2357" => array ( "area" => "Ararat", "city" => "Artashat", "operator" => "VEON Armenia (Beeline)"),
    "2355" => array ( "area" => "Ararat", "city" => "Artashat", "operator" => "VEON Armenia (Beeline)"),
    "2353" => array ( "area" => "Ararat", "city" => "Artashat and Norashen", "operator" => "Ucom"),
    "2352" => array ( "area" => "Ararat", "city" => "Artashat", "operator" => "VEON Armenia (Beeline)"),
    "2349" => array ( "area" => "Ararat", "city" => "Vedi and Ararat", "operator" => "VEON Armenia (Beeline)"),
    "2348" => array ( "area" => "Ararat", "city" => "Vedi and Ararat", "operator" => "VEON Armenia (Beeline)"),
    "2344" => array ( "area" => "Ararat", "city" => "Vedi and Ararat", "operator" => "VEON Armenia (Beeline)"),
    "2343" => array ( "area" => "Ararat", "city" => "Vedi and Ararat", "operator" => "VEON Armenia (Beeline)"),
    "2342" => array ( "area" => "Ararat", "city" => "Vedi and Ararat", "operator" => "VEON Armenia (Beeline)"),
    "2339" => array ( "area" => "Armavir", "city" => "Baghramian", "operator" => "VEON Armenia (Beeline)"),
    "2337" => array ( "area" => "Armavir", "city" => "Baghramian", "operator" => "VEON Armenia (Beeline)"),
    "2333" => array ( "area" => "Armavir", "city" => "Baghramian and Myasnikian", "operator" => "Ucom"),
    "2332" => array ( "area" => "Armavir", "city" => "Baghramian", "operator" => "VEON Armenia (Beeline)"),
    "2329" => array ( "area" => "Aragatsotn", "city" => "Ashtarak", "operator" => "VEON Armenia (Beeline)"),
    "2324" => array ( "area" => "Aragatsotn", "city" => "Ashtarak, Byurakan and Ohanavan", "operator" => "Ucom"),
    "2323" => array ( "area" => "Aragatsotn", "city" => "Ashtarak", "operator" => "VEON Armenia (Beeline)"),
    "2322" => array ( "area" => "Aragatsotn", "city" => "Ashtarak", "operator" => "VEON Armenia (Beeline)"),
    "2319" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "VEON Armenia (Beeline)"),
    "2317" => array ( "area" => "Armavir", "city" => "Zvartnots", "operator" => "VEON Armenia (Beeline)"),
    "2315" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "VEON Armenia (Beeline)"),
    "2314" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "VEON Armenia (Beeline)"),
    "2313" => array ( "area" => "Armavir", "city" => "Zvartnots", "operator" => "Ucom"),
    "2312" => array ( "area" => "Armavir", "city" => "Echmiadzin", "operator" => "Ucom"),
    "2267" => array ( "area" => "Kotayk", "city" => "Charentsavan", "operator" => "VEON Armenia (Beeline)"),
    "2264" => array ( "area" => "Kotayk", "city" => "Charentsavan", "operator" => "VEON Armenia (Beeline)"),
    "2263" => array ( "area" => "Kotayk", "city" => "Charentsavan", "operator" => "Ucom"),
    "2262" => array ( "area" => "Kotayk", "city" => "Charentsavan", "operator" => "VEON Armenia (Beeline)"),
    "2249" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "VEON Armenia (Beeline)"),
    "2247" => array ( "area" => "Kotayk", "city" => "Eghvard and Nor-Hachn", "operator" => "Ucom"),
    "2245" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "VEON Armenia (Beeline)"),
    "2244" => array ( "area" => "Kotayk", "city" => "Nor-Hachn", "operator" => "VEON Armenia (Beeline)"),
    "2243" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "VEON Armenia (Beeline)"),
    "2242" => array ( "area" => "Kotayk", "city" => "Eghvard", "operator" => "VEON Armenia (Beeline)"),
    "2239" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "VEON Armenia (Beeline)"),
    "2235" => array ( "area" => "Kotayk", "city" => "Tsaghkadzor", "operator" => "VEON Armenia (Beeline)"),
    "2234" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "VEON Armenia (Beeline)"),
    "2233" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "VEON Armenia (Beeline)"),
    "2232" => array ( "area" => "Kotayk", "city" => "Hrazdan", "operator" => "VEON Armenia (Beeline)"),
    "2229" => array ( "area" => "Kotayk", "city" => "Abovyan", "operator" => "VEON Armenia (Beeline)"),
    "2227" => array ( "area" => "Kotayk", "city" => "Abovyan", "operator" => "VEON Armenia (Beeline)"),
    "2225" => array ( "area" => "Kotayk", "city" => "Abovyan, Arzni, Geghashen and Arinj", "operator" => "Ucom"),
    "2224" => array ( "area" => "Kotayk", "city" => "Abovyan", "operator" => "VEON Armenia (Beeline)"),
    "2223" => array ( "area" => "Kotayk", "city" => "Abovyan", "operator" => "VEON Armenia (Beeline)"),
    "2222" => array ( "area" => "Kotayk", "city" => "Abovyan", "operator" => "VEON Armenia (Beeline)"),
    "129" => array ( "area" => "Yerevan", "city" => "", "operator" => "GNC-Alfa (Rostelecom)"),
    "128" => array ( "area" => "Yerevan", "city" => "", "operator" => "GNC-Alfa (Rostelecom)"),
    "127" => array ( "area" => "Yerevan", "city" => "", "operator" => "GNC-Alfa (Rostelecom)"),
    "126" => array ( "area" => "Yerevan", "city" => "", "operator" => "GNC-Alfa (Rostelecom)"),
    "125" => array ( "area" => "Yerevan", "city" => "", "operator" => "GNC-Alfa (Rostelecom)"),
    "124" => array ( "area" => "Yerevan", "city" => "", "operator" => "GNC-Alfa (Rostelecom)"),
    "123" => array ( "area" => "Yerevan", "city" => "", "operator" => "GNC-Alfa (Rostelecom)"),
    "122" => array ( "area" => "Yerevan", "city" => "", "operator" => "GNC-Alfa (Rostelecom)"),
    "119" => array ( "area" => "Yerevan", "city" => "", "operator" => "Ucom"),
    "118" => array ( "area" => "Yerevan", "city" => "", "operator" => "Ucom"),
    "117" => array ( "area" => "Yerevan", "city" => "", "operator" => "Ucom"),
    "116" => array ( "area" => "Yerevan", "city" => "", "operator" => "Ucom"),
    "115" => array ( "area" => "Yerevan", "city" => "", "operator" => "Ucom"),
    "114" => array ( "area" => "Yerevan", "city" => "", "operator" => "Ucom"),
    "113" => array ( "area" => "Yerevan", "city" => "", "operator" => "Ucom"),
    "112" => array ( "area" => "Yerevan", "city" => "", "operator" => "Ucom"),
    "109" => array ( "area" => "Yerevan", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "108" => array ( "area" => "Yerevan", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "107" => array ( "area" => "Yerevan", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "106" => array ( "area" => "Yerevan", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "105" => array ( "area" => "Yerevan", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "104" => array ( "area" => "Yerevan", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "103" => array ( "area" => "Yerevan", "city" => "", "operator" => "VEON Armenia (Beeline)"),
    "102" => array ( "area" => "Yerevan", "city" => "", "operator" => "VEON Armenia (Beeline)")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      /**
       * Some numbers has less digits. Local we use less digits, but in E.164
       * format we prefix with zeroes
       */
      $zeros = "";
      if ( substr ( $parameters["Number"], 4, 1) == "1")
      {
        $prefix = substr ( $parameters["Number"], 4, 2);
        $number = substr ( $parameters["Number"], 6);
      } else {
        $prefix = substr ( $parameters["Number"], 4, 3);
        $number = substr ( $parameters["Number"], 7);
        while ( substr ( $number, 0, 1) == "0")
        {
          $zeros .= "0";
          $number = substr ( $number, 1);
        }
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "374", "NDC" => substr ( $parameters["Number"], 4, ( strlen ( $prefix) == 2 ? 2 : 3)), "Country" => "Armenia", "Area" => $data["area"], "City" => $data["city"], "Operator" => $data["operator"], "SN" => $number, "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(" . $prefix . ") " . $number, "International" => "+374 " . $prefix . ( strlen ( $zeros) ? " " . $zeros : "") . " " . $number)));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Armenian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
