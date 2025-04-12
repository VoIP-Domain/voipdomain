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
 * related to country database of Luxembourg.
 *
 * Reference: https://www.itu.int/oth/T020200007D/en (2018-06-12)
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
 * E.164 Luxembourg country hook
 */
framework_add_filter ( "e164_identify_country_LUX", "e164_identify_country_LUX");

/**
 * E.164 Luxembourg area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "LUX" (code for
 * Luxembourg). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_LUX ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Luxembourg
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+352")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 9 digits SN
   */
  $prefixes = array (
    "691" => "Tango Mobile",
    "671" => "Join Experience",
    "661" => "Orange Luxembourg",
    "651" => "Eltrona",
    "621" => "POST Telecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "352", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Luxembourg", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+352 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "2799" => "Troisvierges",
    "2798" => "",
    "2797" => "Huldange",
    "2796" => "",
    "2795" => "Wiltz",
    "2794" => "",
    "2793" => "",
    "2792" => "Clervaux, Fischbach and Hosingen",
    "2791" => "",
    "2790" => "",
    "2789" => "",
    "2788" => "Mertzig and Wahl",
    "2787" => "Larochette",
    "2786" => "",
    "2785" => "Bissen and Roost",
    "2784" => "Han and Lesse",
    "2783" => "Vianden",
    "2782" => "",
    "2781" => "Ettelbruck and Reckange-Sur-Mess",
    "2780" => "Diekirch",
    "2779" => "Berdorf and Consdorf",
    "2778" => "Junglinster",
    "2777" => "",
    "2776" => "Wormeldange",
    "2775" => "Grevenmacher-Sur-Moselle",
    "2774" => "Wasserbillig",
    "2773" => "Rosport",
    "2772" => "Echternach",
    "2771" => "Betzdorf",
    "2770" => "",
    "2767" => "Dudelange",
    "2759" => "Soleuvre",
    "2758" => "Soleuvre and Differdange",
    "2757" => "Esch-sur-Alzette and Schifflange",
    "2756" => "Rumelange",
    "2755" => "Esch-Sur-Alzette and Mondercange",
    "2754" => "Esch-Sur-Alzette",
    "2753" => "Esch-Sur-Alzette",
    "2752" => "Dudelange",
    "2751" => "Dudelange, Bettembourg and Livange",
    "2750" => "Bascharage, Petange and Rodange",
    "2749" => "Howald",
    "2748" => "Contern and Foetz",
    "2747" => "Lintgen",
    "2746" => "",
    "2745" => "Diedrich",
    "2744" => "",
    "2743" => "Findel and Kirchberg",
    "2742" => "Plateau de Kirchberg",
    "2741" => "",
    "2740" => "Howald",
    "2739" => "Windhof and Steinfort",
    "2738" => "",
    "2737" => "Leudelange, Ehlange and Mondercange",
    "2736" => "Hesperange, Kockelscheuer and Roeser",
    "2735" => "Sandweiler, Moutfort and Roodt-Sur-Syre",
    "2734" => "Rameldange and Senningerberg",
    "2733" => "Walferdange",
    "2732" => "Lintgen, Mersch and Steinfort",
    "2731" => "Bertrange, Mamer, Munsbach and Strassen",
    "2730" => "Capellen and Kehlen",
    "2729" => "Luxembourg and Kockelscheuer",
    "2728" => "Luxembourg City",
    "2727" => "",
    "2726" => "",
    "2725" => "Luxembourg",
    "2724" => "",
    "2723" => "Mondorf-les-Bains, Bascharage, Noerdange and Remich",
    "2722" => "Luxembourg City",
    "2721" => "Weicherdange",
    "2720" => "",
    "2699" => "Troisvierges",
    "2698" => "",
    "2697" => "Huldange",
    "2696" => "",
    "2695" => "Wiltz",
    "2694" => "",
    "2693" => "",
    "2692" => "Clervaux, Fischbach and Hosingen",
    "2691" => "",
    "2690" => "",
    "2689" => "",
    "2688" => "Mertzig and Wahl",
    "2687" => "Larochette",
    "2686" => "",
    "2685" => "Bissen and Roost",
    "2684" => "Han and Lesse",
    "2683" => "Vianden",
    "2682" => "",
    "2681" => "Ettelbruck and Reckange-Sur-Mess",
    "2680" => "Diekirch",
    "2679" => "Berdorf and Consdorf",
    "2678" => "Junglinster",
    "2677" => "",
    "2676" => "Wormeldange",
    "2675" => "Grevenmacher-Sur-Moselle",
    "2674" => "Wasserbillig",
    "2673" => "Rosport",
    "2672" => "Echternach",
    "2671" => "Betzdorf",
    "2670" => "",
    "2667" => "Dudelange",
    "2659" => "Soleuvre",
    "2658" => "Soleuvre and Differdange",
    "2657" => "Esch-sur-Alzette and Schifflange",
    "2656" => "Rumelange",
    "2655" => "Esch-Sur-Alzette and Mondercange",
    "2654" => "Esch-Sur-Alzette",
    "2653" => "Esch-Sur-Alzette",
    "2652" => "Dudelange",
    "2651" => "Dudelange, Bettembourg and Livange",
    "2650" => "Bascharage, Petange and Rodange",
    "2649" => "Howald",
    "2648" => "Contern and Foetz",
    "2647" => "Lintgen",
    "2646" => "",
    "2645" => "Diedrich",
    "2644" => "",
    "2643" => "Findel and Kirchberg",
    "2642" => "Plateau de Kirchberg",
    "2641" => "",
    "2640" => "Howald",
    "2639" => "Windhof and Steinfort",
    "2638" => "",
    "2637" => "Leudelange, Ehlange and Mondercange",
    "2636" => "Hesperange, Kockelscheuer and Roeser",
    "2635" => "Sandweiler, Moutfort and Roodt-Sur-Syre",
    "2634" => "Rameldange and Senningerberg",
    "2633" => "Walferdange",
    "2632" => "Lintgen, Mersch and Steinfort",
    "2631" => "Bertrange, Mamer, Munsbach and Strassen",
    "2630" => "Capellen and Kehlen",
    "2629" => "Luxembourg and Kockelscheuer",
    "2628" => "Luxembourg City",
    "2627" => "Belair and Luxembourg City",
    "2626" => "",
    "2625" => "Luxembourg",
    "2624" => "",
    "2623" => "Mondorf-les-Bains, Bascharage, Noerdange and Remich",
    "2622" => "Luxembourg City",
    "2621" => "Weicherdange",
    "2620" => "",
    "2499" => "Troisvierges",
    "2498" => "",
    "2497" => "Huldange",
    "2496" => "",
    "2495" => "Wiltz",
    "2494" => "",
    "2493" => "",
    "2492" => "Clervaux, Fischbach and Hosingen",
    "2491" => "",
    "2490" => "",
    "2489" => "",
    "2488" => "Mertzig and Wahl",
    "2487" => "Larochette",
    "2486" => "",
    "2485" => "Bissen and Roost",
    "2484" => "",
    "2483" => "Vianden",
    "2482" => "",
    "2481" => "Ettelbruck and Reckange-Sur-Mess",
    "2480" => "Diekirch",
    "2479" => "Berdorf and Consdorf",
    "2478" => "Junglinster",
    "2477" => "",
    "2476" => "Wormeldange",
    "2475" => "Grevenmacher-Sur-Moselle",
    "2474" => "Wasserbillig",
    "2473" => "Rosport",
    "2472" => "Echternach",
    "2471" => "Betzdorf",
    "2470" => "",
    "2467" => "Dudelange",
    "2459" => "Soleuvre",
    "2458" => "Soleuvre and Differdange",
    "2457" => "Esch-sur-Alzette and Schifflange",
    "2456" => "Rumelange",
    "2455" => "Esch-Sur-Alzette and Mondercange",
    "2454" => "Esch-Sur-Alzette",
    "2453" => "Esch-Sur-Alzette",
    "2452" => "Dudelange",
    "2451" => "Dudelange, Bettembourg and Livange",
    "2450" => "Bascharage, Petange and Rodange",
    "2449" => "Howald",
    "2448" => "Contern and Foetz",
    "2447" => "Lintgen",
    "2446" => "",
    "2445" => "Diedrich",
    "2444" => "",
    "2443" => "Findel and Kirchberg",
    "2442" => "Plateau de Kirchberg",
    "2441" => "",
    "2440" => "Howald",
    "2439" => "Windhof and Steinfort",
    "2438" => "",
    "2437" => "Leudelange, Ehlange and Mondercange",
    "2436" => "Hesperange, Kockelscheuer and Roeser",
    "2435" => "Sandweiler, Moutfort and Roodt-Sur-Syre",
    "2434" => "Rameldange and Senningerberg",
    "2433" => "Walferdange",
    "2432" => "Lintgen, Mersch and Steinfort",
    "2431" => "Bertrange, Mamer, Munsbach and Strassen",
    "2430" => "Capellen and Kehlen",
    "2429" => "Luxembourg and Kockelscheuer",
    "2428" => "Luxembourg City",
    "2427" => "26",
    "2426" => "",
    "2425" => "Luxembourg",
    "2424" => "",
    "2423" => "Mondorf-les-Bains, Bascharage, Noerdange and Remich",
    "2422" => "Luxembourg City",
    "2421" => "Weicherdange",
    "2420" => "",
    "99" => "Troisvierges",
    "98" => "",
    "97" => "Huldange",
    "96" => "",
    "95" => "Wiltz",
    "94" => "",
    "93" => "",
    "92" => "Clervaux, Fischbach and Hosingen",
    "91" => "",
    "90" => "",
    "89" => "Esch-sur-Sûre",
    "88" => "Mertzig and Wahl",
    "87" => "Larochette",
    "86" => "",
    "85" => "Bissen and Roost",
    "84" => "Han and Lesse",
    "83" => "Vianden",
    "82" => "",
    "81" => "Ettelbruck and Reckange-Sur-Mess",
    "80" => "Diekirch",
    "79" => "Berdorf and Consdorf",
    "78" => "Junglinster",
    "77" => "",
    "76" => "Wormeldange",
    "75" => "Grevenmacher-Sur-Moselle",
    "74" => "Wasserbillig",
    "73" => "Rosport",
    "72" => "Echternach",
    "71" => "Betzdorf",
    "70" => "",
    "67" => "Dudelange",
    "59" => "Soleuvre",
    "58" => "Soleuvre and Differdange",
    "57" => "Esch-sur-Alzette and Schifflange",
    "56" => "Rumelange",
    "55" => "Esch-Sur-Alzette and Mondercange",
    "54" => "Esch-Sur-Alzette",
    "53" => "Esch-Sur-Alzette",
    "52" => "Dudelange",
    "51" => "Dudelange, Bettembourg and Livange",
    "50" => "Bascharage, Petange and Rodange",
    "49" => "Howald",
    "48" => "Contern and Foetz",
    "47" => "Lintgen",
    "46" => "",
    "45" => "Diedrich",
    "44" => "",
    "43" => "Findel and Kirchberg",
    "42" => "Plateau de Kirchberg",
    "41" => "",
    "40" => "Howald",
    "39" => "Windhof and Steinfort",
    "38" => "",
    "37" => "Leudelange, Ehlange and Mondercange",
    "36" => "Hesperange, Kockelscheuer and Roeser",
    "35" => "Sandweiler, Moutfort and Roodt-Sur-Syre",
    "34" => "Rameldange and Senningerberg",
    "33" => "Walferdange",
    "32" => "Lintgen, Mersch and Steinfort",
    "31" => "Bertrange, Mamer, Munsbach and Strassen",
    "30" => "Capellen and Kehlen",
    "29" => "Luxembourg and Kockelscheuer",
    "28" => "Luxembourg City",
    "25" => "Luxembourg",
    "23" => "Mondorf-les-Bains, Bascharage, Noerdange and Remich",
    "22" => "Luxembourg City",
    "21" => "Weicherdange",
    "20" => ""
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "352", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Luxembourg", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+352 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Luxembourg phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
