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
 * related to country database of Greece.
 *
 * Reference: https://www.itu.int/oth/T0202000055/en (2006-07-20)
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
 * E.164 Greece country hook
 */
framework_add_filter ( "e164_identify_country_GRC", "e164_identify_country_GRC");

/**
 * E.164 Greece area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "GRC" (code for Greece). This
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
function e164_identify_country_GRC ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Greece
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+30")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Greece has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "699",
    "697",
    "694",
    "693"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "30", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Greece", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+30 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "2897" => "Limin Chersonisou",
    "2895" => "An. Viannos",
    "2894" => "Ag. Varvara",
    "2893" => "Pyrgos (Creta)",
    "2892" => "Moires",
    "2891" => "Arkalochori",
    "2844" => "Tzermiades",
    "2843" => "Sitia",
    "2842" => "Ierapetra",
    "2841" => "Ag. Nikolaos",
    "2834" => "Perama",
    "2833" => "Amari",
    "2832" => "Spili",
    "2831" => "Rethymno",
    "2825" => "Vamos",
    "2824" => "Kolympari",
    "2823" => "Kantanos",
    "2822" => "Kissamos",
    "2821" => "Chania",
    "2797" => "Tropea",
    "2796" => "Levidi",
    "2795" => "Vitina",
    "2792" => "Kastri",
    "2791" => "Megalopoli",
    "2765" => "Kopanaki",
    "2763" => "Gargalianoi",
    "2761" => "Kyparissia",
    "2757" => "Leonidio",
    "2755" => "Astros",
    "2754" => "Kranidi",
    "2753" => "Lygourgio",
    "2752" => "Nafplion",
    "2751" => "Argos",
    "2747" => "Kalianoi",
    "2746" => "Nemea",
    "2744" => "Loutraki",
    "2743" => "Xylokastro",
    "2742" => "Kiato",
    "2741" => "Korinthos",
    "2736" => "Kythira",
    "2735" => "Skala",
    "2734" => "Neapoli Lakonias",
    "2733" => "Gytheio",
    "2732" => "Molaoi",
    "2731" => "Sparti",
    "2725" => "Koroni",
    "2724" => "Meligalas",
    "2723" => "Pylos",
    "2722" => "Messini",
    "2721" => "Kalamata",
    "2696" => "Akrata",
    "2695" => "Zakynthos",
    "2694" => "Chalandritsa",
    "2693" => "K. Achaia",
    "2692" => "Kalavrita",
    "2691" => "Aegio",
    "2685" => "Voulgareli",
    "2684" => "Kanalaki",
    "2683" => "Philippiada",
    "2682" => "Preveza",
    "2681" => "Arta",
    "2674" => "Sami",
    "2671" => "Argostoli",
    "2666" => "Paramythia",
    "2665" => "Igoumenitsa",
    "2664" => "Filiates",
    "2663" => "Skripero",
    "2662" => "Lefkimi",
    "2661" => "Corfu",
    "2659" => "Kalentzi",
    "2658" => "Zitsa",
    "2657" => "Delvinaki",
    "2656" => "Tampouria",
    "2655" => "Konitsa",
    "2654" => "Perdika",
    "2653" => "Karies",
    "2651" => "Ioannina",
    "2647" => "N. Chalkiopoulo",
    "2646" => "Fyties",
    "2645" => "Lefkada",
    "2644" => "Thermo",
    "2643" => "Vonitsa",
    "2642" => "Amfilochia",
    "2641" => "Agrinio",
    "2635" => "Mataraga",
    "2634" => "Nafpaktos",
    "2632" => "Aitoliko",
    "2631" => "Messologi",
    "2626" => "Andritsena",
    "2625" => "Krestena",
    "2624" => "Anc. Olympia",
    "2623" => "Lechena",
    "2622" => "Amaliada",
    "2621" => "Pyrgos",
    "2594" => "N. Peramos",
    "2593" => "Limenari",
    "2592" => "Eleftheroupoli",
    "2591" => "Chrysoupoli",
    "2556" => "Kyprinos",
    "2555" => "Feres",
    "2554" => "Soufli",
    "2553" => "Didymoticho",
    "2552" => "Orestiada",
    "2551" => "Alexandroupoli",
    "2544" => "Echinos",
    "2542" => "Stavroupoli",
    "2541" => "Xanthi",
    "2535" => "Kallisti",
    "2534" => "Iasmos",
    "2533" => "Xylagani",
    "2532" => "Sapes",
    "2531" => "Komotini",
    "2524" => "Paranesti",
    "2523" => "K. Nevrokopi",
    "2522" => "Prosotsani",
    "2521" => "Drama",
    "2495" => "Makrychori",
    "2494" => "Agia",
    "2493" => "Elassona",
    "2492" => "Tyrnavos",
    "2491" => "Farsala",
    "2468" => "Neapoli Kozanis",
    "2467" => "Kastoria",
    "2465" => "Siatista",
    "2464" => "Servia",
    "2463" => "Ptolemaida",
    "2462" => "Grevena",
    "2461" => "Kozani",
    "2445" => "Mouzaki",
    "2444" => "Palamas",
    "2443" => "Sofades",
    "2441" => "Karditsa",
    "2434" => "Pyli",
    "2433" => "Farkadona",
    "2432" => "Kalabaka",
    "2431" => "Trikala",
    "2428" => "Volos",
    "2427" => "Skiathos",
    "2426" => "Zagora",
    "2425" => "Velestino",
    "2424" => "Skopelos",
    "2423" => "Kala Nera",
    "2422" => "Almyros",
    "2421" => "Volos",
    "2399" => "N. Kallikrateia",
    "2397" => "Asprovalta",
    "2396" => "Vasilika",
    "2395" => "Sochos",
    "2394" => "Lagadas",
    "2393" => "Lagadikia",
    "2392" => "Perea",
    "2391" => "Chlkidona",
    "2386" => "Amynteo",
    "2385" => "Florina",
    "2384" => "Aridea",
    "2382" => "Giannitsa",
    "2381" => "Edessa",
    "2377" => "Ierissos",
    "2376" => "Stratoni",
    "2375" => "Nikitas",
    "2374" => "Kassandreia",
    "2373" => "N. Moudania",
    "2372" => "Arnea",
    "2371" => "Polygyros",
    "2353" => "Aeginio",
    "2352" => "Plaka",
    "2351" => "Katerini",
    "2343" => "Polikastro",
    "2341" => "Kilkis",
    "2333" => "Alexandria",
    "2332" => "Naousa",
    "2331" => "Veroia",
    "2327" => "Rodopoli",
    "2325" => "Herakleia",
    "2324" => "N. Zichni",
    "2323" => "Sidirokastro",
    "2322" => "Nigrita",
    "2321" => "Serres",
    "2299" => "Markopoulo",
    "2298" => "Poros",
    "2297" => "Aegina",
    "2296" => "Megara",
    "2295" => "Afidnes",
    "2294" => "Rafina",
    "2293" => "Ag. Sotira",
    "2292" => "Lavrion",
    "2291" => "Lagonisi",
    "2289" => "Mikonos",
    "2288" => "Kea",
    "2287" => "Milos",
    "2286" => "Thira",
    "2285" => "Naxos",
    "2284" => "Paros",
    "2283" => "Tinos",
    "2282" => "Andros",
    "2281" => "Siros",
    "2275" => "Ag. Kirikos",
    "2274" => "Volissos",
    "2273" => "Samos",
    "2272" => "Kardamila",
    "2271" => "Chios",
    "2268" => "Aliartos",
    "2267" => "Distomo",
    "2266" => "Lidoriki",
    "2265" => "Amfissa",
    "2264" => "Domvrena",
    "2263" => "Vilia",
    "2262" => "Thiva",
    "2261" => "Livadeia",
    "2254" => "Mirina",
    "2253" => "Kalloni",
    "2252" => "Agiasos",
    "2251" => "Mitilini",
    "2247" => "Leros",
    "2246" => "Salakos",
    "2245" => "Karpathos",
    "2244" => "Archagelos",
    "2243" => "Kalimnos",
    "2242" => "Kos",
    "2241" => "Rhodes",
    "2238" => "Stilida",
    "2237" => "Karpenisi",
    "2236" => "Makrakomi",
    "2235" => "Kam.vourla",
    "2234" => "Amfikleia",
    "2233" => "Atalanti",
    "2232" => "Domokos",
    "2231" => "Lamia",
    "2229" => "Eretria",
    "2228" => "Psachna",
    "2227" => "Mantoudi",
    "2226" => "Loutra Aidipsou",
    "2224" => "Karystos",
    "2223" => "Aliveri",
    "2222" => "Kymi",
    "2221" => "Halkida",
    "281" => "Iraklion",
    "271" => "Tripoli",
    "261" => "Patra",
    "251" => "Kavala",
    "241" => "Larissa",
    "231" => "Thessaloniki",
    "21" => "Athens"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "30", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Greece", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+30 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Greece phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
