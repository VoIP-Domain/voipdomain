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
 * related to country database of Pakistan.
 *
 * Reference: https://www.itu.int/oth/T02020000A1/en (2021-12-17)
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
 * E.164 Pakistan country hook
 */
framework_add_filter ( "e164_identify_country_PAK", "e164_identify_country_PAK");

/**
 * E.164 Pakistanian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "PAK" (code for
 * Pakistan). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_PAK ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Pakistan
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+92")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Pakistan has 12 or 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 12 || strlen ( $parameters["Number"]) > 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 8 digits SN
   */
  $prefixes = array (
    "36",
    "35",
    "34",
    "33",
    "32",
    "31",
    "30"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "92", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Pakistan", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+92 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 3 or 4 digits NDC and 5 or 4 digits SN
   */
  $prefixes = array (
    "5828" => "Bhimer",
    "5827" => "Mirpur",
    "5826" => "Kotli",
    "5825" => "Sudonthi",
    "5824" => "Poonch",
    "5823" => "Bagh",
    "5822" => "Muzaffarabad",
    "5821" => "Neelum",
    "5817" => "Astore",
    "5816" => "Ghanche",
    "5815" => "Skardu",
    "5814" => "Ghizer",
    "5813" => "Hunza",
    "5812" => "Diamer",
    "5811" => "Gilgit",
    "998" => "Kohistan",
    "997" => "Mansehra / Batagram",
    "996" => "Shangla",
    "995" => "Haripur",
    "992" => "Abottabad",
    "969" => "Lakki Marwat",
    "966" => "D.I. Khan",
    "965" => "South Waziristan",
    "963" => "Tank",
    "946" => "Swat",
    "945" => "Lower Dir",
    "944" => "Upper Dir",
    "943" => "Chitral",
    "942" => "Bajaur Agency",
    "939" => "Buner",
    "938" => "Swabi",
    "937" => "Mardan",
    "932" => "Malakand",
    "928" => "Bannu, N. Waziristan",
    "927" => "Karak",
    "926" => "Kurram Agency",
    "925" => "Hangu, Orakzai Agy",
    "924" => "Khyber, Mohmand Agy",
    "923" => "Nowshera",
    "922" => "Kohat",
    "856" => "Awaran",
    "855" => "Panjgur",
    "853" => "Lasbela",
    "852" => "Kech",
    "848" => "Khuzdar",
    "847" => "Kharan",
    "844" => "Kalat",
    "843" => "Mastung",
    "838" => "Jaffarabad, Nasirabad",
    "837" => "Jhal Magsi",
    "835" => "Dera Bugti",
    "833" => "Sibi, Ziarat",
    "832" => "Bolan",
    "829" => "Barkhan, Kohlu",
    "828" => "Musakhel",
    "826" => "K.Abdullah, Pishin",
    "825" => "Chagai",
    "824" => "Loralai",
    "823" => "Killa Saifullah",
    "822" => "Zhob",
    "726" => "Shikarpur",
    "723" => "Ghotki",
    "722" => "Jacobabad",
    "608" => "Lodhran",
    "606" => "Layyah",
    "604" => "Rajanpur",
    "547" => "Hafizabad",
    "546" => "Mandi Bahauddin",
    "544" => "Jhelum",
    "543" => "Chakwal",
    "542" => "Narowal",
    "459" => "Mianwali",
    "457" => "Pakpattan",
    "454" => "Khushab",
    "453" => "Bhakkar",
    "298" => "Thatta",
    "297" => "Badin",
    "244" => "Nawabshah",
    "243" => "Khairpur",
    "242" => "Naushero Feroze",
    "238" => "Umerkot",
    "235" => "Sanghar",
    "233" => "Mirpur Khas",
    "232" => "Tharparkar",
    "91" => "Peshawar, Charsadda",
    "86" => "Gwadar",
    "81" => "Quetta",
    "74" => "Larkana",
    "71" => "Sukkur",
    "68" => "Rahim Yar Khan",
    "67" => "Vehari",
    "66" => "Muzaffargarh",
    "65" => "Khanewal",
    "64" => "Dera Ghazi Khan",
    "63" => "Bahawalnagar",
    "62" => "Bahawalpur",
    "61" => "Multan",
    "57" => "Attock",
    "56" => "Sheikhupura",
    "55" => "Gujranwala",
    "53" => "Gujrat",
    "52" => "Sialkot",
    "51" => "Islamabad, Rawalpindi",
    "49" => "Kasur",
    "48" => "Sargodha",
    "47" => "Jhang",
    "46" => "Toba Tek Singh",
    "44" => "Okara",
    "42" => "Lahore",
    "41" => "Faisalabad",
    "40" => "Sahiwal",
    "25" => "Dadu",
    "22" => "Hyderabad",
    "21" => "Karachi"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    // 21 and 42 NDC has 2 digits NDC and 8 digits SN
    if ( ( $prefix == "21" || $prefix == "42") && strlen ( $parameters["Number"]) != 13)
    {
      continue;
    }
    // NDC with 2 digits (except 21 and 42) has 2 digits NDC and 7 digits SN
    if ( strlen ( $prefix) == 2 && $prefix != "21" && $prefix != "42" && strlen ( $parameters["Number"]) != 12)
    {
      continue;
    }
    // NDC with more than 2 digits has 3 or 4 digits NDC and 6 or 7 digits SN
    if ( strlen ( $prefix) > 2 && ( strlen ( $parameters["Number"]) < 12 || strlen ( $parameters["Number"]) > 13))
    {
      continue;
    }

    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "92", "NDC" => $prefix, "Country" => "Pakistan", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => $prefix . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix)), "International" => "+92 " . $prefix . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix)))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Pakistanian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
