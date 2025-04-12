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
 * related to country database of Tajikistan.
 *
 * Reference: https://www.itu.int/dms_pub/itu-t/oth/02/02/T02020000CA0001PDFE.pdf (2011-06-21)
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
 * E.164 Tajikistan country hook
 */
framework_add_filter ( "e164_identify_country_TJK", "e164_identify_country_TJK");

/**
 * E.164 Tajikistan area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "TJK" (code for
 * Tajikistan). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_TJK ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Tajikistan
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+992")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Tajikistan has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "91903" => "TACOM",
    "91902" => "TACOM",
    "91901" => "TACOM",
    "91900" => "TACOM",
    "9188" => "Babilon Mobile",
    "9186" => "Babilon Mobile",
    "998" => "Tojiktelecom",
    "981" => "Babilon Mobile",
    "973" => "Telecom Ink",
    "962" => "M-Teko",
    "951" => "TK-Mobile",
    "935" => "Indigo Tajikistan",
    "927" => "Somoncom",
    "917" => "TT-Mobile",
    "915" => "TACOM",
    "505" => "Somoncom",
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "992", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Tajikistan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+992 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 3, 4 or 6 digits NDC and 4, 5 or 2 digits SN
   */
  $prefixes = array (
    "3317002" => array ( "Area" => "Khatlon (area zone Kulyab)", "City" => "Khovaling", "Format" => 3),
    "35562" => array ( "Area" => "Badahshan (Centre Khorog)", "City" => "Rushan", "Format" => 2),
    "35552" => array ( "Area" => "Badahshan (Centre Khorog)", "City" => "Roshtkala", "Format" => 2),
    "35542" => array ( "Area" => "Badahshan (Centre Khorog)", "City" => "Murgab", "Format" => 2),
    "35532" => array ( "Area" => "Badahshan (Centre Khorog)", "City" => "Ishkashim", "Format" => 2),
    "35522" => array ( "Area" => "Badahshan (Centre Khorog)", "City" => "Darvaz (F. Kalaykhumb)", "Format" => 2),
    "35512" => array ( "Area" => "Badahshan (Centre Khorog)", "City" => "Vanj", "Format" => 2),
    "35222" => array ( "Area" => "Badahshan (Centre Khorog)", "City" => "Khorog", "Format" => 2),
    "34792" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Ayni", "Format" => 2),
    "34755" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Pendjikent", "Format" => 2),
    "34673" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Kanibadam", "Format" => 2),
    "34652" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Taboshar", "Format" => 2),
    "34642" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Ganchi", "Format" => 2),
    "34622" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Isfara", "Format" => 2),
    "34562" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Khujand", "Format" => 2),
    "34552" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Jabarrasulov (F. Proletarskiy)", "Format" => 2),
    "34542" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Istravshan", "Format" => 2),
    "34532" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Asht", "Format" => 2),
    "34525" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Zafarabad", "Format" => 2),
    "34515" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Khujand", "Format" => 2),
    "34452" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Matchinskiy (C. Buston)", "Format" => 2),
    "34432" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Kayrakum", "Format" => 2),
    "34423" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Gafurov", "Format" => 2),
    "34412" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Spitamen (F. Nou)", "Format" => 2),
    "34226" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Khujand", "Format" => 2),
    "34225" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Khujand", "Format" => 2),
    "34224" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Chkalovsk", "Format" => 2),
    "34222" => array ( "Area" => "Sogd (centre Khujand)", "City" => "Shakhristan", "Format" => 2),
    "33223" => array ( "Area" => "Khatlon (area zone Kulyab)", "City" => "Kulyab", "Format" => 2),
    "33222" => array ( "Area" => "Khatlon (area zone Kulyab)", "City" => "Kulyab", "Format" => 2),
    "33182" => array ( "Area" => "Khatlon (area zone Kulyab)", "City" => "Muminobod", "Format" => 2),
    "33162" => array ( "Area" => "Khatlon (area zone Kulyab)", "City" => "Parkhar", "Format" => 2),
    "33152" => array ( "Area" => "Khatlon (area zone Kulyab)", "City" => "M. Khamadoni (F. Moskowskiy)", "Format" => 2),
    "33142" => array ( "Area" => "Khatlon (area zone Kulyab)", "City" => "Temurmalik (F. Sovetskiy)", "Format" => 2),
    "33122" => array ( "Area" => "Khatlon (area zone Kulyab)", "City" => "Dangara", "Format" => 2),
    "33112" => array ( "Area" => "Khatlon (area zone Kulyab)", "City" => "Vose", "Format" => 2),
    "32522" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Panj", "Format" => 2),
    "32512" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Kabodion", "Format" => 2),
    "32506" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Sarband", "Format" => 2),
    "32494" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Kumsangir (C. Dusti)", "Format" => 2),
    "32482" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Djilikul", "Format" => 2),
    "32474" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Kolkhozabad", "Format" => 2),
    "32462" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Vakhsh", "Format" => 2),
    "32452" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Bokhtar", "Format" => 2),
    "32432" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Abdurakhmana Jami (F. Khudjamaston)", "Format" => 2),
    "32422" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Khuroson (F. Gozimalik)", "Format" => 2),
    "32402" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Shaartuz", "Format" => 2),
    "32223" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Kurgan-Tube", "Format" => 2),
    "32222" => array ( "Area" => "Khatlon (area zone Kurgan-Tube)", "City" => "Kurgan-Tube", "Format" => 2),
    "31562" => array ( "Area" => "Centre of Dushanbe", "City" => "Tavildara", "Format" => 2),
    "31553" => array ( "Area" => "Centre of Dushanbe", "City" => "Shakhrinav", "Format" => 2),
    "31542" => array ( "Area" => "Centre of Dushanbe", "City" => "Tadjikabad", "Format" => 2),
    "31532" => array ( "Area" => "Centre of Dushanbe", "City" => "Varzob", "Format" => 2),
    "31412" => array ( "Area" => "Centre of Dushanbe", "City" => "Yavan", "Format" => 2),
    "31392" => array ( "Area" => "Centre of Dushanbe", "City" => "Hissar", "Format" => 2),
    "31382" => array ( "Area" => "Centre of Dushanbe", "City" => "Nurek", "Format" => 2),
    "31372" => array ( "Area" => "Centre of Dushanbe", "City" => "Rudaki (F. Leninskiy)", "Format" => 2),
    "31362" => array ( "Area" => "Centre of Dushanbe", "City" => "Vakhdat (F. Kofarnikhon)", "Format" => 2),
    "31353" => array ( "Area" => "Centre of Dushanbe", "City" => "Fayzabad", "Format" => 2),
    "31342" => array ( "Area" => "Centre of Dushanbe", "City" => "Rogun", "Format" => 2),
    "31332" => array ( "Area" => "Centre of Dushanbe", "City" => "Nurobod (F. Darband)", "Format" => 2),
    "31322" => array ( "Area" => "Centre of Dushanbe", "City" => "Jirgital", "Format" => 2),
    "31312" => array ( "Area" => "Centre of Dushanbe", "City" => "Rasht (F. Garm)", "Format" => 2),
    "31302" => array ( "Area" => "Centre of Dushanbe", "City" => "Tursun-Zade", "Format" => 2),
    "3723" => array ( "Area" => "Dushanbe", "City" => "Dushanbe", "Format" => 1),
    "3722" => array ( "Area" => "Dushanbe", "City" => "Dushanbe", "Format" => 1)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      switch ( $data["Format"])
      {
        case 1:
          $format = substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9);
          break;
        case 2:
          $format = substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8, 1) . " " . substr ( $parameters["Number"], 9);
          break;
        case 3:
          $format = substr ( $parameters["Number"], 4, 6) . " " . substr ( $parameters["Number"], 10, 1) . " " . substr ( $parameters["Number"], 11);
          break;
        default:
          $format = substr ( $parameters["Number"], 4);
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "992", "NDC" => substr ( $parameters["Number"], 4, strlen ( $prefix) - 1), "Country" => "Tajikistan", "Area" => $data["Area"], "City" => $data["City"], "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => $format, "International" => "+992 " . $format)));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Tajikistan phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
