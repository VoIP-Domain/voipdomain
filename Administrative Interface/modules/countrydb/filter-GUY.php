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
 * related to country database of Guyana.
 *
 * Reference: https://www.itu.int/oth/T020200005D/en (2015-03-13)
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
 * E.164 Guyana country hook
 */
framework_add_filter ( "e164_identify_country_GUY", "e164_identify_country_GUY");

/**
 * E.164 Guyanian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "GUY" (code for Guyana). This
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
function e164_identify_country_GUY ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Guyana
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+592")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Guyana has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "659" => "Digicel Guyana Inc.",
    "604" => "Digicel Guyana Inc.",
    "603" => "Digicel Guyana Inc.",
    "602" => "Digicel Guyana Inc.",
    "601" => "Digicel Guyana Inc.",
    "600" => "Digicel Guyana Inc.",
    "69" => "Digicel Guyana Inc.",
    "68" => "Digicel Guyana Inc.",
    "67" => "Digicel Guyana Inc.",
    "66" => "Digicel Guyana Inc."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "592", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Guyana", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+592 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "7779" => "Mabaruma",
    "7778" => "Mabaruma",
    "7777" => "Mabaruma",
    "7776" => "Mabaruma",
    "7775" => "Mabaruma",
    "7774" => "Port Kaituma",
    "7773" => "Port Kaituma",
    "7772" => "Port Kaituma",
    "7771" => "Port Kaituma",
    "7770" => "Port Kaituma",
    "7752" => "Matthews Ridge",
    "7751" => "Matthews Ridge",
    "7750" => "Matthews Ridge",
    "7730" => "Aishalton",
    "7723" => "Lethem",
    "7722" => "Lethem",
    "7721" => "Lethem",
    "7720" => "Lethem",
    "4560" => "Mahdia",
    "4553" => "Bartica",
    "4552" => "Bartica",
    "4551" => "Bartica",
    "4550" => "Bartica",
    "4449" => "Canvas City",
    "4448" => "Canvas City",
    "4447" => "Wisroc",
    "4446" => "Linden",
    "4445" => "Wisroc",
    "4444" => "Linden",
    "4443" => "Linden",
    "4442" => "Linden",
    "4441" => "Linden",
    "4440" => "Linden",
    "4424" => "Amelia’s Ward",
    "4423" => "Amelia’s Ward",
    "4421" => "Christianburg",
    "4420" => "Christianburg",
    "4412" => "Ituni",
    "4411" => "Ituni",
    "4410" => "Ituni",
    "4402" => "Kwakwani",
    "4401" => "Kwakwani",
    "4400" => "Kwakwani",
    "3394" => "No: 52",
    "3393" => "Skeldon",
    "3392" => "Skeldon",
    "3391" => "Skeldon",
    "3390" => "Skeldon",
    "3384" => "Massiah",
    "3382" => "Benab/No. 65 Village",
    "3381" => "Benab/No. 65 Village",
    "3375" => "Rose Hall",
    "3374" => "Rose Hall",
    "3373" => "Liverpool",
    "3372" => "Whim/Bloomfield",
    "3371" => "Whim/Bloomfield",
    "3368" => "Port Mourant",
    "3367" => "Port Mourant",
    "3366" => "Port Mourant",
    "3365" => "Edinburg",
    "3354" => "No: 76",
    "3353" => "No: 76",
    "3351" => "Crabwood Creek, Corentyne",
    "3350" => "Crabwood Creek",
    "3340" => "New Amsterdam",
    "3339" => "New Amsterdam",
    "3338" => "New Amsterdam",
    "3337" => "New Amsterdam",
    "3336" => "New Amsterdam",
    "3335" => "New Amsterdam",
    "3334" => "New Amsterdam",
    "3333" => "New Amsterdam",
    "3332" => "New Amsterdam",
    "3331" => "New Amsterdam",
    "3323" => "Susannah",
    "3321" => "Sheet Anchor",
    "3320" => "Sheet Anchor",
    "3313" => "Joanna",
    "3310" => "Adventure",
    "3302" => "Rosignol",
    "3301" => "Rosignol",
    "3300" => "Rosignol",
    "3295" => "Ithaca",
    "3293" => "Fort Wellington",
    "3290" => "Willemstad",
    "3288" => "Bath/Waterloo",
    "3287" => "Bath/Waterloo",
    "3284" => "Onverwagt",
    "3283" => "Tempe",
    "3282" => "Tempe",
    "3281" => "Cottage",
    "3277" => "Cumberland",
    "3275" => "Shieldstown",
    "3272" => "Cumberland",
    "3270" => "Blairmont",
    "3264" => "No. 40",
    "3262" => "Fryish",
    "3261" => "Adelphi",
    "3260" => "Adelphi",
    "3255" => "Joppa/Brighton",
    "3253" => "No: 34",
    "3250" => "Mibikuri",
    "3225" => "Hampshire",
    "3224" => "Nigg",
    "3223" => "Nigg",
    "3221" => "Kilcoy",
    "3220" => "Kilcoy",
    "2793" => "Stanleytown",
    "2790" => "Good Hope",
    "2774" => "Uitvlugt",
    "2773" => "Uitvlugt",
    "2771" => "Zeeburg",
    "2770" => "Zeeburg",
    "2764" => "Hague/Fellowship",
    "2763" => "Hague/Fellowship",
    "2761" => "Anna Catherina/Cornelia Ida",
    "2760" => "Anna Catherina/Cornelia Ida",
    "2751" => "Met-en-Meer-Zorg",
    "2750" => "Met-en-Meer-Zorg",
    "2741" => "Vigilance",
    "2740" => "Vigilance",
    "2720" => "B/V West",
    "2713" => "Canal No. 2",
    "2711" => "Canal No. 1",
    "2708" => "Enmore",
    "2707" => "Enmore",
    "2706" => "Enmore",
    "2705" => "Non Pariel",
    "2704" => "Non Pariel",
    "2703" => "Melanie",
    "2702" => "Melanie",
    "2701" => "Melanie",
    "2700" => "Melanie",
    "2691" => "Windsor Forest",
    "2690" => "Windsor Forest",
    "2684" => "Leonora",
    "2683" => "Leonora",
    "2682" => "Leonora",
    "2681" => "Leonora",
    "2680" => "Leonora",
    "2672" => "Wales",
    "2671" => "Wales",
    "2670" => "Wales",
    "2665" => "Land of Canaan",
    "2664" => "New Hope/Friendship/Grove",
    "2663" => "New Hope/Friendship/Grove",
    "2662" => "New Hope/Friendship/Grove",
    "2661" => "New Hope/Friendship/Grove",
    "2660" => "New Hope/Friendship/Grove",
    "2657" => "Diamond",
    "2656" => "Diamond",
    "2655" => "Diamond",
    "2654" => "Diamond",
    "2653" => "Diamond",
    "2652" => "Diamond",
    "2651" => "Diamond",
    "2650" => "Diamond",
    "2643" => "Vreed-en-Hoop",
    "2642" => "Vreed-en-Hoop",
    "2641" => "Vreed-en-Hoop",
    "2640" => "Vreed-en-Hoop",
    "2620" => "Parika",
    "2618" => "Long Creek",
    "2617" => "Long Creek",
    "2616" => "Soesdyke",
    "2615" => "Soesdyke",
    "2614" => "Timehri",
    "2613" => "Timehri",
    "2612" => "Timehri",
    "2611" => "Timehri",
    "2610" => "Timehri",
    "2604" => "Parika",
    "2603" => "Parika",
    "2602" => "Tuschen",
    "2601" => "Tuschen",
    "2593" => "Unity",
    "2591" => "Clonbrook",
    "2590" => "Clonbrook",
    "2583" => "Mortice",
    "2580" => "Planters Hall",
    "2573" => "Strangroen",
    "2570" => "Cane Grove",
    "2565" => "Hope West E.C.D.",
    "2564" => "Hope West",
    "2563" => "Hope West",
    "2561" => "Victoria",
    "2560" => "Victoria",
    "2553" => "Golden Grove/Haslington",
    "2542" => "New Road/Best",
    "2540" => "New Road/Best",
    "2533" => "Goed Fortuin",
    "2530" => "La Grange",
    "2341" => "B/V Central",
    "2340" => "B/V Central",
    "2337" => "Nandy Park",
    "2336" => "Nandy Park",
    "2335" => "Nandy Park",
    "2333" => "Eccles",
    "2332" => "Eccles",
    "2331" => "Agricola/Houston",
    "2330" => "Agricola/Houston",
    "2329" => "Bush Lot",
    "2324" => "Belladrum W.C.B.",
    "2323" => "Belladrum",
    "2322" => "Novar/Catherine",
    "2321" => "Novar/Catherine",
    "2320" => "Bush Lot",
    "2298" => "Enterprise E.C.D.",
    "2297" => "Enterprise",
    "2296" => "Enterprise",
    "2295" => "Cove & John",
    "2294" => "Cove & John",
    "2293" => "Cove & John",
    "2292" => "Cove & John",
    "2291" => "Cove & John",
    "2285" => "Belmont",
    "2283" => "Mahaica",
    "2282" => "Mahaica",
    "2281" => "Mahaica",
    "2251" => "Paradise",
    "2250" => "Paradise",
    "2213" => "Mahaicony",
    "2212" => "Mahaicony",
    "2199" => "Sophia",
    "2198" => "Sophia",
    "2197" => "Sophia",
    "2196" => "Sophia",
    "2195" => "Sophia",
    "2194" => "Sophia",
    "2193" => "Sophia",
    "2192" => "Sophia",
    "2191" => "Sophia",
    "2190" => "Georgetown",
    "2171" => "Mocha E.B.D.",
    "2170" => "Mocha E.B.D.",
    "231" => "Georgetown",
    "227" => "Georgetown",
    "226" => "Georgetown",
    "225" => "Georgetown",
    "223" => "Georgetown",
    "222" => "B/V West",
    "220" => "B/V Central",
    "218" => "Georgetown (S/R/Veldt)",
    "216" => "Diamond/Grove"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "592", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Guyana", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+592 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "7745" => "Suddie",
    "7744" => "Suddie",
    "7740" => "Friendship",
    "7715" => "Anna Regina",
    "7714" => "Anna Regina",
    "7710" => "Charity",
    "3370" => "No: 40",
    "3330" => "New Amsterdam",
    "3280" => "Cottage",
    "2637" => "Wales",
    "2636" => "Mochaa",
    "2635" => "La Grange, Canal 1",
    "2634" => "La Grange, Canal 1",
    "2630" => "Georgetown",
    "2600" => "Tuschen",
    "2280" => "Mahaica"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "592", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Guyana", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+592 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Guyanian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
