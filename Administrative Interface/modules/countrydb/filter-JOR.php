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
 * related to country database of Jordan.
 *
 * Reference: https://www.itu.int/oth/T020200006E/en (2017-10-31)
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
 * E.164 Jordan country hook
 */
framework_add_filter ( "e164_identify_country_JOR", "e164_identify_country_JOR");

/**
 * E.164 Jordanian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "JOR" (code for Jordan). This
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
function e164_identify_country_JOR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Jordan
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+962")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 9 digits SN
   */
  $prefixes = array (
    "799" => "Jordan Mobile Telephone Services Company (Zain)",
    "798" => "Jordan Mobile Telephone Services Company (Zain)",
    "797" => "Jordan Mobile Telephone Services Company (Zain)",
    "796" => "Jordan Mobile Telephone Services Company (Zain)",
    "795" => "Jordan Mobile Telephone Services Company (Zain)",
    "792" => "Jordan Mobile Telephone Services Company (Zain)",
    "791" => "Jordan Mobile Telephone Services Company (Zain)",
    "790" => "Jordan Mobile Telephone Services Company (Zain)",
    "789" => "Umniah Mobile Company",
    "788" => "Umniah Mobile Company",
    "787" => "Umniah Mobile Company",
    "786" => "Umniah Mobile Company",
    "785" => "Umniah Mobile Company",
    "781" => "Umniah Mobile Company",
    "780" => "Umniah Mobile Company",
    "779" => "Petra Jordanian Mobile Telecommunication Company (Orange Mobile)",
    "778" => "Petra Jordanian Mobile Telecommunication Company (Orange Mobile)",
    "777" => "Petra Jordanian Mobile Telecommunication Company (Orange Mobile)",
    "776" => "Petra Jordanian Mobile Telecommunication Company (Orange Mobile)",
    "775" => "Petra Jordanian Mobile Telecommunication Company (Orange Mobile)",
    "772" => "Petra Jordanian Mobile Telecommunication Company (Orange Mobile)",
    "770" => "Petra Jordanian Mobile Telecommunication Company (Orange Mobile)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "962", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Jordan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+962 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "87901" => array ( "operator" => "Metrobeam Wireless Telecommunications", "area" => ""),
    "87900" => array ( "operator" => "Metrobeam Wireless Telecommunications", "area" => ""),
    "87000" => array ( "operator" => "Viacloud Jordan L.L.C.", "area" => ""),
    "8799" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => ""),
    "8778" => array ( "operator" => "Jordan Data Communications", "area" => ""),
    "8770" => array ( "operator" => "Tarasol Telecom", "area" => ""),
    "8720" => array ( "operator" => "Batelco Jordan", "area" => ""),
    "6599" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => ""),
    "6593" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6592" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6590" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6588" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6586" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6585" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6584" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6583" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6582" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6581" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6580" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6579" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6577" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6573" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6572" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6571" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6569" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6568" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6567" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6566" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6565" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6563" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6562" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6561" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6560" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6559" => array ( "operator" => "Viacloud Jordan L.L.C.", "area" => ""),
    "6556" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6555" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6554" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6553" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6552" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6551" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6550" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6548" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6547" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6541" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6539" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6538" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6537" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6535" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6534" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6533" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6532" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6531" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6530" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6524" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6523" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6520" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6516" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6515" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6510" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6506" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6505" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6500" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6492" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6491" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6490" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6489" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6488" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6487" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6480" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6479" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6478" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6477" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6476" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6475" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6474" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6473" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6472" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6471" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6470" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6469" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6468" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6465" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6464" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6463" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6462" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6461" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6460" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6449" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6448" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6446" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6445" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6442" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6440" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6439" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6438" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6437" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6430" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6429" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6426" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6425" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6420" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6417" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6416" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6415" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6414" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6413" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6412" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6405" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6402" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "6401" => array ( "operator" => "Al-Moakhah for Telecom/Mada", "area" => ""),
    "6400" => array ( "operator" => "Al-Moakhah for Telecom/Mada", "area" => ""),
    "6333" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Amman"),
    "6300" => array ( "operator" => "Tarasol Telecom", "area" => ""),
    "6250" => array ( "operator" => "Metrobeam Wireless Telecommunications", "area" => ""),
    "6200" => array ( "operator" => "Batelco Jordan", "area" => ""),
    "5399" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5398" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5397" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5396" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5393" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5392" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5391" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5390" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5386" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5385" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5384" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5383" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5382" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5381" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5375" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5374" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5365" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5361" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5359" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5358" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5357" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5356" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5355" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5353" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5352" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5351" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5350" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5349" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5333" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Balqa"),
    "5332" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Balqa"),
    "5330" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Balqa"),
    "5329" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5325" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5324" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5323" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5322" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5321" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5320" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "5313" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Madaba"),
    "5312" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Madaba"),
    "5310" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Madaba"),
    "5303" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Zarqa"),
    "5302" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Zarqa"),
    "5301" => array ( "operator" => "Al-Moakhah for Telecom/Mada", "area" => "Zarqa"),
    "5300" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Zarqa"),
    "3273" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Karak"),
    "3272" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Karak"),
    "3270" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Karak"),
    "3263" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Tafeleh"),
    "3260" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Tafileh"),
    "3253" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Maan"),
    "3252" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Maan"),
    "3250" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Maan"),
    "3243" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Aqaba"),
    "3242" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Aqaba"),
    "3241" => array ( "operator" => "Al-Moakhah for Telecom/Mada", "area" => "Aqaba"),
    "3240" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Aqaba"),
    "3239" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3238" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3237" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3236" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3235" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3234" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3233" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3232" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3231" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3230" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3227" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3226" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3225" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3224" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3222" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Tafileh"),
    "3220" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3217" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3216" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3215" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3213" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3212" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3211" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3209" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3206" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3205" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3204" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3203" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3202" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "3201" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2758" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2757" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2755" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2753" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2752" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2751" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2750" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2749" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2741" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2740" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2739" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2738" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2736" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2735" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2734" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2733" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2732" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2731" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2730" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2727" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2726" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2725" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2724" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2721" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2720" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2710" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2709" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2707" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2706" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2705" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2704" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2703" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2702" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2701" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2693" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Irbid"),
    "2692" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Irbid"),
    "2691" => array ( "operator" => "Al-Moakhah for Telecom/Mada", "area" => "Irbid"),
    "2690" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Irbid"),
    "2683" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Ajloun"),
    "2682" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Ajloun"),
    "2680" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Ajloun"),
    "2673" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Jarash"),
    "2672" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Jarash"),
    "2670" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Jarash"),
    "2663" => array ( "operator" => "Jordan Data Communications (Orange Internet)", "area" => "Mafraq"),
    "2662" => array ( "operator" => "Al Bahrainia Al Urdunia Liltaknia Wa Alitisalat", "area" => "Mafraq"),
    "2660" => array ( "operator" => "Jordan Mobile Telephone Services Company (Zain)", "area" => "Mafraq"),
    "2658" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2657" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2656" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2655" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2654" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2652" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2651" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2650" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2647" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2646" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2645" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2644" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2642" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2638" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2637" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2635" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2634" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2633" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2632" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2631" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2630" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2629" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2628" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2627" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2626" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2625" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2623" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2622" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2621" => array ( "operator" => "Jordan Telecom", "area" => ""),
    "2620" => array ( "operator" => "Jordan Telecom", "area" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "962", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Jordan", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5), "International" => "+962 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for paging network with no NDC and 9 digits SN
   */
  $prefixes = array (
    "7477" => "Mirsal",
    "7466" => "Mirsal"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "962", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Jordan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+962 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Jordanian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
