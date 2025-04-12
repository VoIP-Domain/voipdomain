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
 * related to country database of Korea (Republic of).
 *
 * Reference: https://www.itu.int/oth/T0202000072/en (2012-03-22)
 *
 * Note: South Korea ITU-T numbering plan document has no numbering plan, only
 *       contact information to telephony companies. All information here is
 *       not trustable because was found on unofficial sources.
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
 * E.164 Korea (Republic of) country hook
 */
framework_add_filter ( "e164_identify_country_KOR", "e164_identify_country_KOR");

/**
 * E.164 Korea (Republic of) area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "KOR" (code for
 * Korea (Republic of)). This hook will verify if phone number is valid,
 * returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_KOR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Korea (Republic of)
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+82")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 8 digits SN
   */
  $prefixes = array (
    "19",
    "18",
    "17",
    "16",
    "11",
    "10"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "82", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Korea (Republic of)", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+82 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1 or 2 digits NDC and 7 or 8 digits SN
   */
  $prefixes = array (
    "64" => "Jeju-do",
    "63" => "Jeollabuk-do",
    "62" => "Gwangju",
    "61" => "Jeollanam-do",
    "55" => "Gyeongsangnam-do and a few neighborhoods of Ulsan",
    "54" => "Gyeongsangbuk-do",
    "53" => "Daegu and a part of Gyeongsangbuk-do (Gyeongsan)",
    "52" => "Ulsan",
    "51" => "Busan",
    "49" => "Kaesong Industrial Region",
    "44" => "Sejong City",
    "43" => "Chungcheongbuk-do",
    "42" => "Daejeon and a part of Chungcheongnam-do (Gyeryong)",
    "41" => "Chungcheongnam-do",
    "33" => "Gangwon-do",
    "32" => "Incheon and parts of Gyeonggi-do (Bucheon and some insular communities of Ansan)",
    "31" => "Gyeonggi-do",
    "2" => "Seoul and parts of Gyeonggi-do (Gwacheon, Gwangmyeong and some neighborhoods of Goyang and Hanam)"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      switch ( strlen ( $prefix))
      {
        case 1:
          $callformats = array ( "Local" => "0" . substr ( $parameters["Number"], 3, 1) . "-" . substr ( $parameters["Number"], 4, 4) . "-" . substr ( $parameters["Number"], 8), "International" => "+82 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8));
          break;
        case 2:
          $callformats = array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+82 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8));
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "82", "NDC" => (string) $prefix, "Country" => "Korea (Republic of)", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Korea (Republic of)
   * phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
