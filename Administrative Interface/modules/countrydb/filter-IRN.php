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
 * related to country database of Iran.
 *
 * Reference: https://www.itu.int/oth/T0202000066/en (2018-10-25)
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
 * E.164 Iran country hook
 */
framework_add_filter ( "e164_identify_country_IRN", "e164_identify_country_IRN");

/**
 * E.164 Iranian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "IRN" (code for Iran). This hook
 * will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_IRN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Iran
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+98")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 8 digits SN
   */
  $prefixes = array (
    "99999",
    "99998",
    "99997",
    "99996",
    "99977",
    "99921",
    "99914",
    "99913",
    "99911",
    "99910",
    "99903",
    "99901",
    "99900",
    "99888",
    "99811",
    "99810",
    "99510",
    "9944",
    "9044",
    "991",
    "990",
    "922",
    "921",
    "920",
    "905",
    "903",
    "902",
    "901",
    "93",
    "91"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "98", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Iran", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . "-" . substr ( $parameters["Number"], 6, 3) . "-" . substr ( $parameters["Number"], 9), "International" => "+98 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 3 to 8 digits SN
   */
  $prefixes = array (
    "87" => "Kurdestan",
    "86" => "Markazi",
    "84" => "Ilam",
    "83" => "Kermanshahan",
    "81" => "Hamadan",
    "77" => "Bushehr",
    "76" => "Hormozgan",
    "74" => "Kohgiluoye va Boyer Ahmad",
    "71" => "Fars",
    "66" => "Lorestan",
    "61" => "Khuzestan",
    "58" => "North Khorasan",
    "56" => "South Khorasan",
    "54" => "Sistan va Balochestan",
    "51" => "Razavi Khorasan",
    "45" => "Ardabil",
    "44" => "West Azarbayjan",
    "41" => "East Azarbayjan",
    "38" => "Chahar Mahal va Bakhtiari",
    "35" => "Yazd",
    "34" => "Kerman",
    "31" => "Isfahan",
    "28" => "Ghazvin",
    "26" => "Alborz",
    "25" => "Qom",
    "24" => "Zanjan",
    "23" => "Semnan",
    "21" => "Tehran",
    "17" => "Golestan",
    "13" => "Gilan",
    "11" => "Mazandaran"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 8 && strlen ( $parameters["Number"]) <= 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "98", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Iran", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+98 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for public payphones network with 2 digits NDC and 3 to 8 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 4) == "9950" && strlen ( $parameters["Number"]) >= 8 && strlen ( $parameters["Number"]) <= 13)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "98", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Iran", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PAYPHONE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+98 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
  }

  /**
   * If reached here, number wasn't identified as a valid Iranian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
