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
 * related to country database of Kyrgyzstan.
 *
 * Reference: https://www.itu.int/oth/T0202000074/en (2018-06-12)
 *
 * Note: Kyrgyzstan's ITU-T documentation has some conflicts. Don't blind trust
 *       this file. If you've more trusted source information, please fix this
 *       and submit changes.
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
 * E.164 Kyrgyzstan country hook
 */
framework_add_filter ( "e164_identify_country_KGZ", "e164_identify_country_KGZ");

/**
 * E.164 Kyrgyzstanian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "KGZ" (code for
 * Kyrgyzstan). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_KGZ ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Kyrgyzstan
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+996")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Kyrgyzstan has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 or 3 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "77599" => array ( "area" => "", "operator" => "Sky Mobile"),
    "77598" => array ( "area" => "", "operator" => "Sky Mobile"),
    "77597" => array ( "area" => "", "operator" => "Sky Mobile"),
    "77558" => array ( "area" => "", "operator" => "Sky Mobile"),
    "56689" => array ( "area" => "", "operator" => "Winline"),
    "56688" => array ( "area" => "", "operator" => "Winline"),
    "56687" => array ( "area" => "", "operator" => "Winline"),
    "56669" => array ( "area" => "", "operator" => "Winline"),
    "56647" => array ( "area" => "", "operator" => "Winline"),
    "56550" => array ( "area" => "", "operator" => "Winline"),
    "54596" => array ( "area" => "", "operator" => "AkTel (Fonex)"),
    "54595" => array ( "area" => "", "operator" => "AkTel (Fonex)"),
    "5669" => array ( "area" => "", "operator" => "Winline"),
    "999" => array ( "area" => "", "operator" => "Sky Mobile"),
    "996" => array ( "area" => "", "operator" => "Sky Mobile"),
    "779" => array ( "area" => "", "operator" => "Sky Mobile"),
    "778" => array ( "area" => "", "operator" => "Sky Mobile"),
    "777" => array ( "area" => "", "operator" => "Sky Mobile"),
    "773" => array ( "area" => "", "operator" => "Sky Mobile"),
    "772" => array ( "area" => "", "operator" => "Sky Mobile"),
    "771" => array ( "area" => "", "operator" => "Sky Mobile"),
    "770" => array ( "area" => "", "operator" => "Sky Mobile"),
    "543" => array ( "area" => "", "operator" => "AkTel (Fonex)"),
    "396" => array ( "area" => "Issyk-Kol", "operator" => ""),
    "377" => array ( "area" => "Jalal-Abat", "operator" => ""),
    "376" => array ( "area" => "Jalal-Abat", "operator" => ""),
    "366" => array ( "area" => "Batken", "operator" => ""),
    "356" => array ( "area" => "Naryn", "operator" => ""),
    "346" => array ( "area" => "Talas", "operator" => ""),
    "316" => array ( "area" => "Chuy", "operator" => ""),
    "315" => array ( "area" => "Bishkek", "operator" => ""),
    "205" => array ( "area" => "", "operator" => "AkTel (Fonex)"),
    "203" => array ( "area" => "", "operator" => "AkTel (Fonex)"),
    "202" => array ( "area" => "", "operator" => "AkTel (Fonex)"),
    "201" => array ( "area" => "", "operator" => "AkTel (Fonex)"),
    "200" => array ( "area" => "", "operator" => "AkTel (Fonex)"),
    "70" => array ( "area" => "", "operator" => "Nur Telecom"),
    "57" => array ( "area" => "", "operator" => "Sotel"),
    "56" => array ( "area" => "", "operator" => "WinLine"),
    "55" => array ( "area" => "", "operator" => "ALFA Telecom"),
    "54" => array ( "area" => "", "operator" => "AkTel (Fonex)"),
    "51" => array ( "area" => "", "operator" => "Katel"),
    "22" => array ( "area" => "", "operator" => "Sky Mobile")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "996", "NDC" => (string) $prefix, "Country" => "Kyrgyzstan", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 1) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+996 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 1) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 3 or 4 digits NDC and 5 or 6 digits SN
   */
  $prefixes = array (
    "3948" => array ( "area" => "Issyk-Kol", "city" => "Ak-Suu"),
    "3947" => array ( "area" => "Issyk-Kol", "city" => "Kadji-Say (Ton district)"),
    "3947" => array ( "area" => "Issyk-Kol", "city" => "Bokombaevo (Ton district)"),
    "3946" => array ( "area" => "Issyk-Kol", "city" => "Kyzyl-Suu (Jety-Oguz district)"),
    "3945" => array ( "area" => "Issyk-Kol", "city" => "Tup (Tup district)"),
    "3944" => array ( "area" => "Issyk-Kol", "city" => "Balykchy"),
    "3943" => array ( "area" => "Issyk-Kol", "city" => "Cholpon-Ata (Issyk-Kul district)"),
    "3942" => array ( "area" => "Issyk-Kol", "city" => "Ananyevo"),
    "3922" => array ( "area" => "Issyk-Kol", "city" => "Karakol"),
    "3749" => array ( "area" => "Jalal-Abat", "city" => "Kanysh-Kya (Chatkal)"),
    "3748" => array ( "area" => "Jalal-Abat", "city" => "Suzak (Suzak district)"),
    "3748" => array ( "area" => "Jalal-Abat", "city" => "Kok-Jangak"),
    "3747" => array ( "area" => "Jalal-Abat", "city" => "Toktogul (Toktogul district)"),
    "3746" => array ( "area" => "Jalal-Abat", "city" => "Kara-Kul"),
    "3745" => array ( "area" => "Jalal-Abat", "city" => "Tash-Kumyr"),
    "3744" => array ( "area" => "Jalal-Abat", "city" => "Mailuu-Suu"),
    "3742" => array ( "area" => "Jalal-Abat", "city" => "Kerben (Aksy district)"),
    "3741" => array ( "area" => "Jalal-Abat", "city" => "Ala-Buka (Ala-Buka district)"),
    "3738" => array ( "area" => "Jalal-Abat", "city" => "Kazarman (Toguz-Toro district)"),
    "3736" => array ( "area" => "Jalal-Abat", "city" => "Bazar-Korgon (Bazar-Korgon district)"),
    "3734" => array ( "area" => "Jalal-Abat", "city" => "Massy (Nooken district)"),
    "3734" => array ( "area" => "Jalal-Abat", "city" => "Kochkor-Ata"),
    "3722" => array ( "area" => "Jalal-Abat", "city" => "Jalal-Abat"),
    "3657" => array ( "area" => "Batken", "city" => "Kyzylkia"),
    "3656" => array ( "area" => "Batken", "city" => "Isfana (Laylak district)"),
    "3655" => array ( "area" => "Batken", "city" => "Pulgon (Kadamjay district)"),
    "3653" => array ( "area" => "Batken", "city" => "Sulukta"),
    "3622" => array ( "area" => "Batken", "city" => "Batken"),
    "3537" => array ( "area" => "Naryn", "city" => "Baetov (Ak-Tala district)"),
    "3536" => array ( "area" => "Naryn", "city" => "Minkush (Minkush district)"),
    "3536" => array ( "area" => "Naryn", "city" => "Chaek (Jumgal district)"),
    "3535" => array ( "area" => "Naryn", "city" => "Kochkorka (Kochkor district)"),
    "3534" => array ( "area" => "Naryn", "city" => "At-Bashy (At-Bashy district)"),
    "3522" => array ( "area" => "Naryn", "city" => "Naryn"),
    "3459" => array ( "area" => "Talas", "city" => "Pokrovka (Manas district)"),
    "3458" => array ( "area" => "Talas", "city" => "Kokoy (Talas district)"),
    "3457" => array ( "area" => "Talas", "city" => "Bakay-Ata (Bakay-Ata district)"),
    "3456" => array ( "area" => "Talas", "city" => "Kyzyl-Adyr (Kyzyl-Adyr district)"),
    "3422" => array ( "area" => "Talas", "city" => "Talas"),
    "3239" => array ( "area" => "Osh", "city" => "Kara-Kulja (Kara-Kulja district)"),
    "3237" => array ( "area" => "Osh", "city" => "Daroot-Korgon (Chon-Alai district)"),
    "3234" => array ( "area" => "Osh", "city" => "Gulcha (Alai district)"),
    "3233" => array ( "area" => "Osh", "city" => "Uzgen (Uzgen district)"),
    "3232" => array ( "area" => "Osh", "city" => "Kara-Suu (Kara-Suu district)"),
    "3231" => array ( "area" => "Osh", "city" => "Aravan (Aravan district)"),
    "3230" => array ( "area" => "Osh", "city" => "Eski-Nookat (Nookat district)"),
    "3222" => array ( "area" => "Osh", "city" => "Osh"),
    "3139" => array ( "area" => "Chuy", "city" => "Lebedinovka (Alamudun district)"),
    "3138" => array ( "area" => "Chuy", "city" => "Tokmok (Tokmok district)"),
    "3137" => array ( "area" => "Chuy", "city" => "Kayndy (Panfilov district)"),
    "3135" => array ( "area" => "Chuy", "city" => "Kemin (Kemin district)"),
    "3134" => array ( "area" => "Chuy", "city" => "Sokuluk (Sokuluk district)"),
    "3133" => array ( "area" => "Chuy", "city" => "Kara-Balta (Jayl district)"),
    "3132" => array ( "area" => "Chuy", "city" => "Kant (Issyk-Ata district)"),
    "3131" => array ( "area" => "Chuy", "city" => "Belovodskoe (Moskovsky district"),
    "312" => array ( "area" => "Bishkek", "city" => "Bishkek")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "996", "NDC" => (string) $prefix, "Country" => "Kyrgyzstan", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+996 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for satellite network with 2 or 3 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "52" => array ( "area" => "", "operator" => "Ay Sat Systems")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "996", "NDC" => (string) $prefix, "Country" => "Kyrgyzstan", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 1) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+996 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 1) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Kyrgyzstanian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
