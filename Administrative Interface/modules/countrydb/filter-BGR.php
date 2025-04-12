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
 * related to country database of Bulgaria.
 *
 * Reference: http://www.crc.bg/section.php?lang=en&id=119
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
 * E.164 Bulgaria country hook
 */
framework_add_filter ( "e164_identify_country_BGR", "e164_identify_country_BGR");

/**
 * E.164 Bulgarian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BGR" (code for Bulgaria). This
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
function e164_identify_country_BGR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Bulgaria
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+359")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and variable digits SN
   */
  $prefixes = array (
    "989" => 9,
    "988" => 9,
    "987" => 9,
    "986" => 9,
    "985" => 9,
    "984" => 9,
    "439" => 8,
    "438" => 8,
    "437" => 8,
    "99" => 9,
    "89" => 9,
    "88" => 9,
    "87" => 9
  );
  foreach ( $prefixes as $prefix => $digits)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $digits)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "359", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Bulgaria", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 4, 2) . ") " . substr ( $parameters["Number"], 6, 4) . " " . substr ( $parameters["Number"], 10), "International" => "+359 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 4) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network
   */
  $prefixes = array (
    "973" => "Kozloduy",
    "971" => "Lom",
    "953" => "Berkovitsa",
    "938" => "Kula",
    "936" => "Belogradchik",
    "915" => "Byala Slatina",
    "910" => "Mezdra",
    "817" => "Byala",
    "777" => "Radomir",
    "751" => "Gotse Delchev",
    "750" => "Borovets",
    "749" => "Bansko",
    "747" => "Raziog",
    "746" => "Sandanski",
    "745" => "Petrich",
    "729" => "Godech",
    "726" => "Svoge",
    "723" => "Botevgrad",
    "722" => "Samokov",
    "721" => "Kostinbrod",
    "720" => "Etropole",
    "718" => "Koprivshtitsa",
    "707" => "Sapareva Banya",
    "706" => "Kyustendil Region",
    "705" => "Kyustendil Region",
    "704" => "Kyustendil Region",
    "703" => "Kyustendil Region",
    "702" => "Bobov Dol",
    "701" => "Dupnitsa",
    "697" => "Lukovit",
    "675" => "Sevlievo",
    "670" => "Troyan",
    "658" => "Belene",
    "650" => "Levski",
    "631" => "Svishtov",
    "618" => "Gorna Oryahovitza",
    "610" => "Pavlikeni",
    "608" => "Popovo",
    "601" => "Targovishte",
    "596" => "Pomorie",
    "590" => "Ahtopol",
    "579" => "Balchik",
    "570" => "Kavarna",
    "554" => "Nesebar",
    "550" => "Sinemorets",
    "538" => "Veliki Preslav",
    "537" => "Novi Pazar",
    "519" => "Devnya",
    "518" => "Provadiya",
    "478" => "Elhovo",
    "470" => "Topolovgrad",
    "454" => "Tvarditza",
    "457" => "Nova Zagora",
    "453" => "Kotel",
    "436" => "Stara Zagora Region",
    "435" => "Stara Zagora Region",
    "434" => "Stara Zagora Region",
    "433" => "Stara Zagora Region",
    "432" => "Stara Zagora Region",
    "431" => "Kazanlak",
    "416" => "Chirpan",
    "391" => "Dimitrovgrad",
    "379" => "Svilengrad",
    "373" => "Harmanli",
    "361" => "Kardzhali",
    "359" => "Velingrad",
    "350" => "Peshtera",
    "331" => "Asenovgrad",
    "309" => "Pamporovo",
    "301" => "Smolyan",
    "97" => "Montana Region and Vratsa Region",
    "96" => "Montana",
    "95" => "Montana Region",
    "94" => "Vidin",
    "93" => "Vidin Region",
    "92" => "Vratsa",
    "91" => "Vratsa Region",
    "86" => "Silistra and Silistra Region",
    "84" => "Razgrad and Razgrad Region",
    "82" => "Ruse",
    "81" => "Ruse Region",
    "79" => "Kyustendil Region",
    "78" => "Kyustendil",
    "77" => "Pernik Region",
    "76" => "Pernik",
    "75" => "Blagoevgrad Region and Sofia Region",
    "74" => "Blagoevgrad Region",
    "73" => "Blagoevgrad",
    "72" => "Sofia Region",
    "71" => "Sofia Region",
    "69" => "Lovech Region",
    "68" => "Lovech",
    "67" => "Gabrovo Region and Lovech Region",
    "66" => "Gabrovo",
    "65" => "Pleven Region",
    "64" => "Pleven",
    "63" => "Veliko Tarnovo Region and Pleven Region",
    "62" => "Veliko Tarnovo",
    "61" => "Veliko Tarnovo Region",
    "60" => "Targovishte and Targovishte Region",
    "59" => "Burgas Region",
    "58" => "Dobrich",
    "57" => "Dobrich Region",
    "56" => "Burgas",
    "55" => "Burgas Region",
    "54" => "Shumen",
    "53" => "Shumen Region",
    "52" => "Varna",
    "51" => "Varna Region",
    "47" => "Yambol Region and Haskovo Region",
    "46" => "Yambol",
    "45" => "Sliven Region",
    "44" => "Sliven",
    "42" => "Stara Zagora",
    "41" => "Stara Zagora Region",
    "39" => "Haskovo Region",
    "38" => "Haskovo",
    "37" => "Haskovo Region",
    "36" => "Kardzhali, Kardzhali Region and Haskovo Region",
    "35" => "Pazardzhik Region",
    "34" => "Pazardzhik",
    "33" => "Plovdiv Region",
    "32" => "Plovdiv",
    "31" => "Plovdiv Region",
    "30" => "Smolyan, Smolyan Region and Plovdiv Region",
    "2" => "Sofia"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      if ( $prefix == "2")
      {
        $callformats = array ( "Local" => "(0" . substr ( $parameters["Number"], 4, 1) . ") " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+359 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8));
      } else {
        $callformats = array ( "Local" => "(0" . substr ( $parameters["Number"], 4, 2) . ") " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+359 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9));
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "359", "NDC" => substr ( $parameters["Number"], 4, ( $prefix == "2" ? 1 : 2)), "Country" => "Bulgaria", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], ( $prefix == "2" ? 5 : 6)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Bulgarian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
