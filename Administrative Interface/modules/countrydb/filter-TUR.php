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
 * related to country database of Turkey.
 *
 * Reference: https://www.itu.int/oth/T02020000D6/en (2018-04-27)
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
 * E.164 Turkey country hook
 */
framework_add_filter ( "e164_identify_country_TUR", "e164_identify_country_TUR");

/**
 * E.164 Turkey area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "TUR" (code for Turkey). This
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
function e164_identify_country_TUR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Turkey
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+90")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Turkey has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "812",
    "592",
    "559",
    "555",
    "554",
    "553",
    "552",
    "551",
    "524",
    "516",
    "510",
    "507",
    "506",
    "505",
    "501",
    "54",
    "53"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "90", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Turkey", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+90 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "811" => "",
    "488" => "Batman",
    "486" => "Şırnak",
    "484" => "Siirt",
    "482" => "Mardin",
    "478" => "Ardahan",
    "476" => "Iğdır",
    "474" => "Kars",
    "471" => "Ağrı",
    "466" => "Artvin",
    "464" => "Rize",
    "462" => "Trabzon",
    "458" => "Bayburt",
    "456" => "Gümüşhane",
    "454" => "Giresun",
    "452" => "Ordu",
    "446" => "Erzincan",
    "442" => "Erzurum",
    "438" => "Hakkari",
    "436" => "Muş",
    "434" => "Bitlis",
    "432" => "Van",
    "428" => "Tunceli",
    "426" => "Bingöl",
    "424" => "Elazığ",
    "422" => "Malatya",
    "416" => "Adıyaman",
    "414" => "Şanlıurfa",
    "412" => "Diyarbakır",
    "388" => "Niğde",
    "386" => "Kırşehir",
    "384" => "Nevşehir",
    "382" => "Aksaray",
    "380" => "Düzce",
    "378" => "Bartın",
    "376" => "Çankırı",
    "374" => "Bolu",
    "372" => "Zongdulak",
    "370" => "Karabuk",
    "368" => "Sinop",
    "366" => "Kastamonu",
    "364" => "Çorum",
    "362" => "Samsun",
    "358" => "Amasya",
    "356" => "Tokat",
    "354" => "Yozgat",
    "352" => "Kayseri",
    "348" => "Kilis",
    "346" => "Sivas",
    "344" => "Kahramanmaraş",
    "342" => "Gaziantep",
    "338" => "Karaman",
    "332" => "Konya",
    "328" => "Osmaniye",
    "326" => "Hatay",
    "324" => "İçel",
    "322" => "Adana",
    "318" => "Kırıkkale",
    "312" => "Ankara",
    "288" => "Kırklareli",
    "286" => "Çanakkale",
    "284" => "Edirne",
    "282" => "Tekirdağ",
    "276" => "Uşak",
    "274" => "Kütahya",
    "272" => "Afyon",
    "266" => "Balıkesir",
    "264" => "Sakarya",
    "262" => "Kocaeli",
    "258" => "Denizli",
    "256" => "Aydın",
    "252" => "Muğla",
    "248" => "Burdur",
    "246" => "Isparta",
    "242" => "Antalya",
    "236" => "Manisa",
    "232" => "İzmir",
    "228" => "Bilecik",
    "226" => "Yalova",
    "224" => "Bursa",
    "222" => "Eskişehira",
    "216" => "İstanbul (Anatolian Part)",
    "212" => "İstanbul (European Part)"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "90", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Turkey", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+90 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "800")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "90", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Turkey", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+90 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
  }

  /**
   * Check for VoIP network with 3 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "850")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "90", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Turkey", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+90 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
  }

  /**
   * Check for PRN network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "900",
    "898",
    "888"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "90", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Turkey", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+90 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Turkey phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
