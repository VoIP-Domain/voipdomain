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
 * related to country database of Azerbaijan.
 *
 * Reference: https://www.itu.int/oth/T020200000F/en (2018-03-14)
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
 * E.164 Azerbaijan country hook
 */
framework_add_filter ( "e164_identify_country_AZE", "e164_identify_country_AZE");

/**
 * E.164 Azerbaijanian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "AZE" (code for
 * Azerbaijan). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_AZE ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Azerbaijan
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+994")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Azerbaijan has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "77" => "Azerfon LLC",
    "70" => "Azerfon LLC",
    "60" => "Naxtel LLC",
    "55" => "Bakcell LLC",
    "51" => "Azercell Telecom LLC",
    "50" => "Azercell Telecom LLC",
    "44" => "Aztelekom LLC",
    "40" => "Catel LLC"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "994", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Azerbaijan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 4, 2) . ") " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+994 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network
   */
  $prefixes = array (
    "36552" => "Sharur",
    "36550" => "Nakhchivan city",
    "36549" => "Sadarak",
    "36548" => "Kangarli",
    "36547" => "Ordubad",
    "36546" => "Julfa",
    "36544" => "Nakhchivan city",
    "36543" => "Shahbuz",
    "36542" => "Sharur",
    "36541" => "Babek",
    "22428" => "Yevlakh",
    "21428" => "Hajigabul",
    "2638" => "Jabrayil",
    "2632" => "Agdam",
    "2631" => "Fuzuli",
    "2630" => "Hadrut",
    "2629" => "Khojavand",
    "2628" => "Agdara",
    "2627" => "Kalbajar",
    "2626" => "Shusha",
    "2625" => "Zangilan",
    "2624" => "Askaran",
    "2623" => "Qubadli",
    "2622" => "Khankandi",
    "2621" => "Lachin",
    "2620" => "Khojali",
    "2529" => "Bilasuvar",
    "2527" => "Lerik",
    "2525" => "Lankaran",
    "2524" => "Jalilabad",
    "2522" => "Astara",
    "2521" => "Masalli",
    "2520" => "Yardimli",
    "2429" => "Balakan",
    "2427" => "Mingachevir",
    "2425" => "Gakh",
    "2424" => "Shaki",
    "2422" => "Zagatala",
    "2421" => "Oguz",
    "2420" => "Gabala",
    "2338" => "Gusar",
    "2335" => "Shabran",
    "2333" => "Guba",
    "2332" => "Khachmaz",
    "2331" => "Khizi",
    "2330" => "Siyazan",
    "2235" => "Naftalan",
    "2233" => "Yevlakh",
    "2232" => "Gadabay",
    "2231" => "Tovuz",
    "2230" => "Shamkir",
    "2229" => "Gazakh",
    "2227" => "Samukh",
    "2226" => "Ganja",
    "2225" => "Ganja",
    "2224" => "Goranboy",
    "2223" => "Tartar",
    "2222" => "Agstafa",
    "2221" => "Dashkasan",
    "2220" => "Goygol",
    "2128" => "Saatli",
    "2127" => "Agjabadi",
    "2126" => "Neftchala",
    "2125" => "Salyan",
    "2124" => "Imishli",
    "2123" => "Sabirabad",
    "2122" => "Beylagan",
    "2121" => "Shirvan",
    "2120" => "Hajigabul",
    "2029" => "Zardab",
    "2028" => "Ismayilli",
    "2027" => "Goychay",
    "2026" => "Shamakhi",
    "2025" => "Kurdamir",
    "2024" => "Gobustan",
    "2023" => "Agdash",
    "2022" => "Agsu",
    "2021" => "Ujar",
    "2020" => "Barda",
    "18" => "Sumgayit",
    "12" => "Baku"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "994", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Azerbaijan", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 4, 2) . ") " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 4), "International" => "+994 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for Avirtel LLC fixed line
   */
  if ( substr ( $parameters["Number"], 4, 2) == "88")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "994", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Azerbaijan", "Area" => "", "City" => "", "Operator" => "Avirtel LLC", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 4, 2) . ") " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 4), "International" => "+994 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for iDelta Telecom Ltd LLC fixed line
   */
  if ( substr ( $parameters["Number"], 4, 2) == "46")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "994", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Azerbaijan", "Area" => "", "City" => "", "Operator" => "Delta Telecom Ltd LLC", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 4, 2) . ") " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 4), "International" => "+994 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for Naxtel network fixed line
   */
  if ( substr ( $parameters["Number"], 4, 5) == "36554")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "994", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Azerbaijan", "Area" => "", "City" => "", "Operator" => "Naxtel network", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 4, 2) . ") " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 4), "International" => "+994 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Azerbaijanian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
