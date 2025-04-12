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
 * related to country database of Viet Nam.
 *
 * Reference: https://www.itu.int/oth/T02020000E4/en (2020-05-25)
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
 * E.164 Viet Nam country hook
 */
framework_add_filter ( "e164_identify_country_VNM", "e164_identify_country_VNM");

/**
 * E.164 Viet Nam area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "VNM" (code for Viet Nam). This
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
function e164_identify_country_VNM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Viet Nam
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+84")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Viet Nam has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "899" => "Mobifone",
    "898" => "Mobifone",
    "896" => "Mobifone",
    "889" => "VNPT",
    "888" => "VNPT",
    "886" => "VNPT",
    "879" => "I-Telecom",
    "869" => "Viettel",
    "868" => "Viettel",
    "867" => "Viettel",
    "866" => "Viettel",
    "865" => "Viettel",
    "862" => "Viettel",
    "559" => "Reddi",
    "528" => "Vietnamobile",
    "523" => "Vietnamobile",
    "522" => "Vietnamobile",
    "85" => "",
    "84" => "",
    "83" => "",
    "82" => "",
    "81" => "",
    "79" => "",
    "78" => "",
    "77" => "",
    "76" => "",
    "70" => "",
    "59" => "",
    "58" => "",
    "56" => "",
    "39" => "",
    "38" => "",
    "37" => "",
    "36" => "",
    "35" => "",
    "34" => "",
    "33" => "",
    "32" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "84", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Viet Nam", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 5) . " " . substr ( $parameters["Number"], 8), "International" => "+84 " . substr ( $parameters["Number"], 3, 5) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "299" => "Soc Trang province",
    "297" => "Kien Giang province",
    "296" => "An Giang province",
    "294" => "Tra Vinh province",
    "293" => "Hau Giang province",
    "292" => "Can Tho city",
    "291" => "Bac Lieu province",
    "290" => "Ca Mau province",
    "277" => "Dong Thap province",
    "276" => "Tay Ninh province",
    "275" => "Ben Tre province",
    "274" => "Binh Duong province",
    "273" => "Tien Giang province",
    "272" => "Long An province",
    "271" => "Binh Phuoc province",
    "270" => "Ving Long province",
    "269" => "Gia Lai province",
    "263" => "Lam Dong province",
    "262" => "Dak Lak province",
    "261" => "Dak Nong province",
    "260" => "Kon Tum province",
    "259" => "Ninh Thuan province",
    "258" => "Khanh Hoa province",
    "257" => "Phu Yen province",
    "256" => "Binh Dinh province",
    "255" => "Quang Ngai province",
    "254" => "Ba Ria Vung Tau province",
    "252" => "Binh Thuan province",
    "251" => "Dong Nai province",
    "239" => "Ha Tinh province",
    "238" => "Nghe An province",
    "237" => "Thanh Hoa province",
    "236" => "Da Nang city",
    "235" => "Quang Nam province",
    "234" => "Thua Thien Hue province",
    "233" => "Quang Tri province",
    "232" => "Quang Binh province",
    "229" => "Ninh Binh province",
    "228" => "Nam Dinh province",
    "227" => "Thai Binh province",
    "226" => "Ha Nam province",
    "225" => "Hai Phong city",
    "222" => "Bac Ninh province",
    "221" => "Hung Yen province",
    "220" => "Hai Duong province",
    "216" => "Yen Bai province",
    "215" => "Dien Bien province",
    "214" => "Lao Cai province",
    "213" => "Lai Chau province",
    "212" => "Son La province",
    "210" => "Hai Duong province",
    "209" => "Bac Can province",
    "208" => "Thai Nguyen province",
    "207" => "Tuyen Quang province",
    "206" => "Cao Bang province",
    "205" => "Lang Son province",
    "204" => "Bac Giang province",
    "203" => "Quang Ninh province",
    "28" => "Ho Chi Minh city",
    "24" => "Ha Noi city"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      switch ( substr ( $parameters["Number"], strlen ( $prefix) + 3, 1))
      {
        case "2":
          $operator = "EVN";
          break;
        case "3":
          $operator = "VNPT";
          break;
        case "4":
          $operator = "VTC";
          break;
        case "5":
          $operator = "SPT";
          break;
        case "6":
          $operator = "Viettel";
          break;
        default:
          $operator = "";
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "84", "NDC" => $prefix, "Country" => "Viet Nam", "Area" => $area, "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+84 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for VSAT network with 3 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "672")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "84", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Viet Nam", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 5) . " " . substr ( $parameters["Number"], 8), "International" => "+84 " . substr ( $parameters["Number"], 3, 5) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * If reached here, number wasn't identified as a valid Viet Nam phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
