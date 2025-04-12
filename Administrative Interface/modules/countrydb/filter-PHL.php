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
 * related to country database of Philippines.
 *
 * Reference: https://www.itu.int/oth/T02020000A7/en (2006-07-20)
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
 * E.164 Philippines country hook
 */
framework_add_filter ( "e164_identify_country_PHL", "e164_identify_country_PHL");

/**
 * E.164 Filipino area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "PHL" (code for Philippines).
 * This hook will verify if phone number is valid, returning the area code, area
 * name, phone number, others number related information and if possible, the
 * number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_PHL ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Philippines
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+63")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Philippines has 11, 12 or 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 11 || strlen ( $parameters["Number"]) > 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "9259" => "Sun",
    "9258" => "Globe Postpaid",
    "9257" => "Globe Postpaid",
    "9256" => "Globe Postpaid",
    "9255" => "Globe Postpaid",
    "9254" => "Sun",
    "9253" => "Globe PostPaid",
    "9252" => "Sun",
    "9251" => "Sun",
    "9250" => "Sun",
    "9179" => "Globe/TM",
    "9178" => "Globe PostPaid",
    "9177" => "Globe/TM",
    "9176" => "Globe PostPaid",
    "9175" => "Globe PostPaid",
    "9174" => "Globe/TM",
    "9173" => "Globe PostPaid",
    "9172" => "Globe/TM",
    "9171" => "Globe/TM",
    "9170" => "Globe/TM",
    "999" => "Smart",
    "998" => "Smart",
    "997" => "Globe/TM",
    "996" => "Globe/TM",
    "995" => "Globe/TM",
    "994" => "DITO",
    "993" => "DITO",
    "992" => "DITO",
    "991" => "DITO",
    "979" => "Globe/TM",
    "978" => "Globe/TM",
    "977" => "Globe/TM",
    "976" => "Globe/GOMO",
    "975" => "Globe/TM",
    "974" => "Sun",
    "973" => "Sun",
    "967" => "Globe/TM",
    "966" => "Globe/TM",
    "965" => "Globe/TM",
    "961" => "Smart",
    "956" => "Globe/TM",
    "955" => "Globe/TM",
    "954" => "Globe/TM",
    "953" => "Globe/TM",
    "951" => "Smart",
    "950" => "TNT",
    "949" => "Smart",
    "948" => "TNT",
    "947" => "Smart",
    "946" => "TNT",
    "945" => "Globe/TM",
    "943" => "Sun",
    "942" => "Sun",
    "941" => "Sun",
    "940" => "Sun",
    "939" => "Smart",
    "938" => "TNT",
    "937" => "Globe/TM",
    "936" => "Globe/TM",
    "935" => "Globe/TM",
    "934" => "Sun",
    "933" => "Sun",
    "932" => "Sun",
    "931" => "Sun",
    "930" => "TNT",
    "929" => "Smart",
    "928" => "Smart",
    "927" => "Globe/TM",
    "926" => "Globe/TM",
    "924" => "Sun",
    "923" => "Sun",
    "922" => "Sun",
    "921" => "Smart",
    "920" => "Smart",
    "919" => "Smart",
    "918" => "Smart",
    "916" => "Globe/TM",
    "915" => "Globe/TM",
    "912" => "TNT",
    "910" => "TNT",
    "909" => "TNT",
    "908" => "Smart",
    "907" => "TNT",
    "906" => "Globe/TM",
    "905" => "Globe/TM",
    "898" => "DITO",
    "897" => "DITO",
    "896" => "DITO",
    "895" => "DITO",
    "817" => "Globe"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "63", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Philippines", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . "-" . substr ( $parameters["Number"], 9), "International" => "+63 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1 or 2 digits NDC and 5 or 4 digits SN
   */
  $prefixes = array (
    "88" => array ( "Area" => "Northern, Eastern and Southern Mindanao (including Davao Region, Caraga and Southern Soccsksargen)", "City" => "Bukidnon, Camiguin, Misamis Occidental, Misamis Oriental"),
    "87" => array ( "Area" => "Northern, Eastern and Southern Mindanao (including Davao Region, Caraga and Southern Soccsksargen)", "City" => "Davao Oriental"),
    "86" => array ( "Area" => "Northern, Eastern and Southern Mindanao (including Davao Region, Caraga and Southern Soccsksargen)", "City" => "Dinagat Islands, Surigao del Norte, Surigao del Sur"),
    "85" => array ( "Area" => "Northern, Eastern and Southern Mindanao (including Davao Region, Caraga and Southern Soccsksargen)", "City" => "Agusan del Norte, Agusan del Sur"),
    "84" => array ( "Area" => "Northern, Eastern and Southern Mindanao (including Davao Region, Caraga and Southern Soccsksargen)", "City" => "Davao de Oro, Davao del Norte"),
    "83" => array ( "Area" => "Northern, Eastern and Southern Mindanao (including Davao Region, Caraga and Southern Soccsksargen)", "City" => "Sarangani, South Cotabato"),
    "82" => array ( "Area" => "Northern, Eastern and Southern Mindanao (including Davao Region, Caraga and Southern Soccsksargen)", "City" => "Davao del Sur, Davao Occidental"),
    "78" => array ( "Area" => "Northern Luzon (Ilocos Region, Cagayan Valley and Cordillera Administrative Region)", "City" => "Batanes, Cagayan, Isabela, Nueva Vizcaya, Quirino"),
    "77" => array ( "Area" => "Northern Luzon (Ilocos Region, Cagayan Valley and Cordillera Administrative Region)", "City" => "Ilocos Norte, Ilocos Sur"),
    "75" => array ( "Area" => "Northern Luzon (Ilocos Region, Cagayan Valley and Cordillera Administrative Region)", "City" => "Pangasinan"),
    "74" => array ( "Area" => "Northern Luzon (Ilocos Region, Cagayan Valley and Cordillera Administrative Region)", "City" => "Abra, Apayao, Benguet, Ifugao, Kalinga, Mountain Province"),
    "72" => array ( "Area" => "Northern Luzon (Ilocos Region, Cagayan Valley and Cordillera Administrative Region)", "City" => "La Union"),
    "68" => array ( "Area" => "Western and Central Mindanao (Zamboanga Peninsula, Bangsamoro and Northern Soccsksargen)", "City" => "Sulu, Tawi-Tawi"),
    "65" => array ( "Area" => "Western and Central Mindanao (Zamboanga Peninsula, Bangsamoro and Northern Soccsksargen)", "City" => "Zamboanga del Norte"),
    "64" => array ( "Area" => "Western and Central Mindanao (Zamboanga Peninsula, Bangsamoro and Northern Soccsksargen)", "City" => "Cotabato, Maguindanao del Norte, Maguindanao del Sur, Sultan Kudarat"),
    "63" => array ( "Area" => "Western and Central Mindanao (Zamboanga Peninsula, Bangsamoro and Northern Soccsksargen)", "City" => "Lanao del Norte, Lanao del Sur"),
    "62" => array ( "Area" => "Western and Central Mindanao (Zamboanga Peninsula, Bangsamoro and Northern Soccsksargen)", "City" => "Basilan, Zamboanga del Sur (including Zamboanga City), Zamboanga Sibugay"),
    "56" => array ( "Area" => "Bicol Region and Eastern Visayas", "City" => "Masbate, Sorsogon"),
    "55" => array ( "Area" => "Bicol Region and Eastern Visayas", "City" => "Eastern Samar, Northern Samar, Western Samar"),
    "54" => array ( "Area" => "Bicol Region and Eastern Visayas", "City" => "Camarines Norte, Camarines Sur"),
    "53" => array ( "Area" => "Bicol Region and Eastern Visayas", "City" => "Biliran, Leyte, Southern Leyte"),
    "52" => array ( "Area" => "Bicol Region and Eastern Visayas", "City" => "Albay, Catanduanes"),
    "49" => array ( "Area" => "Central Luzon and Southern Tagalog (Calabarzon and Mimaropa)", "City" => "Laguna (except San Pedro)"),
    "48" => array ( "Area" => "Central Luzon and Southern Tagalog (Calabarzon and Mimaropa)", "City" => "Palawan"),
    "47" => array ( "Area" => "Central Luzon and Southern Tagalog (Calabarzon and Mimaropa)", "City" => "Bataan, Zambales"),
    "46" => array ( "Area" => "Central Luzon and Southern Tagalog (Calabarzon and Mimaropa)", "City" => "Cavite (except Bacoor)"),
    "45" => array ( "Area" => "Central Luzon and Southern Tagalog (Calabarzon and Mimaropa)", "City" => "Pampanga, Tarlac"),
    "44" => array ( "Area" => "Central Luzon and Southern Tagalog (Calabarzon and Mimaropa)", "City" => "Bulacan, Nueva Ecija"),
    "43" => array ( "Area" => "Central Luzon and Southern Tagalog (Calabarzon and Mimaropa)", "City" => "Batangas, Occidental Mindoro, Oriental Mindoro"),
    "42" => array ( "Area" => "Central Luzon and Southern Tagalog (Calabarzon and Mimaropa)", "City" => "Aurora, Marinduque, Quezon, Romblon"),
    "38" => array ( "Area" => "Western and Central Visayas", "City" => "Bohol"),
    "36" => array ( "Area" => "Western and Central Visayas", "City" => "Aklan, Antique, Capiz"),
    "35" => array ( "Area" => "Western and Central Visayas", "City" => "Negros Oriental, Siquijor"),
    "34" => array ( "Area" => "Western and Central Visayas", "City" => "Negros Occidental"),
    "33" => array ( "Area" => "Western and Central Visayas", "City" => "Iloilo, Guimaras"),
    "32" => array ( "Area" => "Western and Central Visayas", "City" => "Cebu"),
    "2" => array ( "Area" => "National Capital Region, Rizal and Surroundings", "City" => "Metro Manila, Rizal, Cavite (Bacoor), Laguna (San Pedro)")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( ( strlen ( $parameters["Number"]) == 11 || strlen ( $parameters["Number"]) == 12) && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      $operators = array (
        "57" => "Eastern Communications Philippines",
        "56" => "Eastern Communications Philippines",
        "55" => "Eastern Communications Philippines",
        "54" => "Eastern Communications Philippines",
        "53" => "Eastern Communications Philippines",
        "66" => "ABS-CBN Convergence",
        "65" => "ABS-CBN Convergence",
        "64" => "ABS-CBN Convergence",
        "63" => "ABS-CBN Convergence",
        "62" => "ABS-CBN Convergence",
        "61" => "ABS-CBN Convergence",
        "60" => "ABS-CBN Convergence",
        "34" => "Bayan Telecommunications",
        "33" => "Bayan Telecommunications",
        "32" => "Bayan Telecommunications",
        "31" => "Bayan Telecommunications",
        "30" => "Bayan Telecommunications",
        "8" => "PLDT / Digital Telecommunications Philippines",
        "7" => "Globe Telecom / Innove Communications"
      );
      $operator = "";
      foreach ( $operators as $opprefix => $opoperator)
      {
        if ( substr ( $parameters["Number"], 4, strlen ( $opprefix)) == $opprefix)
        {
          $operator = $opoperator;
        }
      }
      if ( $prefix == "2")
      {
        $ndc = substr ( $parameters["Number"], 3, 1);
        $sn = substr ( $parameters["Number"], 4);
        $local = "0" . substr ( $parameters["Number"], 3, 1) . "-" . substr ( $parameters["Number"], 4, 3) . "-" . substr ( $parameters["Number"], 7);
        $international = "+63 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7);
      } else {
        $ndc = substr ( $parameters["Number"], 3, 2);
        $sn = substr ( $parameters["Number"], 5);
        $local = "0" . substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8);
        $international = "+63 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8);
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "63", "NDC" => $ndc, "Country" => "Philippines", "Area" => $data["Area"], "City" => $data["City"], "Operator" => $operator, "SN" => $sn, "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => $local, "International" => $international)));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Filipino phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
