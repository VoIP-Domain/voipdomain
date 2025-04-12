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
 * related to country database of Finland.
 *
 * Reference: https://www.itu.int/oth/T0202000049/en (2017-10-13)
 * Reference: https://www.viestintavirasto.fi/en/internettelephone/numberingoftelecommunicationsnetworks.html (2016-02-01)
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
 * E.164 Finland country hook
 */
framework_add_filter ( "e164_identify_country_FIN", "e164_identify_country_FIN");

/**
 * E.164 Finlandian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "FIN" (code for
 * Finland). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_FIN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Finland
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+358")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 to 9 digits SN
   */
  $prefixes = array (
    "50" => array ( "minimum" => 8, "maximum" => 10),
    "49" => array ( "minimum" => 11, "maximum" => 11),
    "48" => array ( "minimum" => 8, "maximum" => 10),
    "47" => array ( "minimum" => 8, "maximum" => 10),
    "46" => array ( "minimum" => 8, "maximum" => 10),
    "45" => array ( "minimum" => 8, "maximum" => 10),
    "44" => array ( "minimum" => 8, "maximum" => 10),
    "43" => array ( "minimum" => 8, "maximum" => 10),
    "42" => array ( "minimum" => 8, "maximum" => 10),
    "41" => array ( "minimum" => 8, "maximum" => 10),
    "40" => array ( "minimum" => 8, "maximum" => 10),
    "39" => array ( "minimum" => 8, "maximum" => 10)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 4 + $data["minimum"] && strlen ( $parameters["Number"]) <= 4 + $data["maximum"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "358", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Finland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+358 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 3 to 8 digits SN
   */
  $prefixes = array (
    "7099" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "2099" => array ( "area" => "", "minimum" => 5, "maximum" => 10),
    "2098" => array ( "area" => "", "minimum" => 5, "maximum" => 10),
    "2097" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2096" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2095" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2094" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2093" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2092" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2091" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2090" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2029" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2028" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2027" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2026" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2025" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2024" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2023" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "2022" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "2021" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "2020" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "759" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "758" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "757" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "756" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "755" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "754" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "753" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "752" => array ( "area" => "", "minimum" => 5, "maximum" => 5),
    "751" => array ( "area" => "", "minimum" => 5, "maximum" => 5),
    "750" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "708" => array ( "area" => "", "minimum" => 10, "maximum" => 10),
    "707" => array ( "area" => "", "minimum" => 10, "maximum" => 10),
    "700" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "606" => array ( "area" => "", "minimum" => 10, "maximum" => 10),
    "602" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "601" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "600" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "309" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "308" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "307" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "306" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "305" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "304" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "303" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "302" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "301" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "300" => array ( "area" => "", "minimum" => 6, "maximum" => 10),
    "208" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "207" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "206" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "205" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "204" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "203" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "201" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "200" => array ( "area" => "", "minimum" => 7, "maximum" => 9),
    "198" => array ( "area" => "Uusimaa II", "minimum" => 7, "maximum" => 9),
    "197" => array ( "area" => "Uusimaa II", "minimum" => 7, "maximum" => 9),
    "196" => array ( "area" => "Uusimaa II", "minimum" => 7, "maximum" => 9),
    "195" => array ( "area" => "Uusimaa II", "minimum" => 7, "maximum" => 9),
    "194" => array ( "area" => "Uusimaa II", "minimum" => 7, "maximum" => 9),
    "193" => array ( "area" => "Uusimaa II", "minimum" => 7, "maximum" => 9),
    "192" => array ( "area" => "Uusimaa II", "minimum" => 7, "maximum" => 9),
    "191" => array ( "area" => "Uusimaa II", "minimum" => 7, "maximum" => 9),
    "188" => array ( "area" => "Åland (Aaland) islands", "minimum" => 7, "maximum" => 9),
    "187" => array ( "area" => "Åland (Aaland) islands", "minimum" => 7, "maximum" => 9),
    "186" => array ( "area" => "Åland (Aaland) islands", "minimum" => 7, "maximum" => 9),
    "185" => array ( "area" => "Åland (Aaland) islands", "minimum" => 7, "maximum" => 9),
    "184" => array ( "area" => "Åland (Aaland) islands", "minimum" => 7, "maximum" => 9),
    "183" => array ( "area" => "Åland (Aaland) islands", "minimum" => 7, "maximum" => 9),
    "182" => array ( "area" => "Åland (Aaland) islands", "minimum" => 7, "maximum" => 9),
    "181" => array ( "area" => "Åland (Aaland) islands", "minimum" => 7, "maximum" => 9),
    "178" => array ( "area" => "Kuopio", "minimum" => 7, "maximum" => 9),
    "177" => array ( "area" => "Kuopio", "minimum" => 7, "maximum" => 9),
    "176" => array ( "area" => "Kuopio", "minimum" => 7, "maximum" => 9),
    "175" => array ( "area" => "Kuopio", "minimum" => 7, "maximum" => 9),
    "174" => array ( "area" => "Kuopio", "minimum" => 7, "maximum" => 9),
    "173" => array ( "area" => "Kuopio", "minimum" => 7, "maximum" => 9),
    "172" => array ( "area" => "Kuopio", "minimum" => 7, "maximum" => 9),
    "171" => array ( "area" => "Kuopio", "minimum" => 7, "maximum" => 9),
    "168" => array ( "area" => "Lappi", "minimum" => 7, "maximum" => 9),
    "167" => array ( "area" => "Lappi", "minimum" => 7, "maximum" => 9),
    "166" => array ( "area" => "Lappi", "minimum" => 7, "maximum" => 9),
    "165" => array ( "area" => "Lappi", "minimum" => 7, "maximum" => 9),
    "164" => array ( "area" => "Lappi", "minimum" => 7, "maximum" => 9),
    "163" => array ( "area" => "Lappi", "minimum" => 7, "maximum" => 9),
    "162" => array ( "area" => "Lappi", "minimum" => 7, "maximum" => 9),
    "161" => array ( "area" => "Lappi", "minimum" => 7, "maximum" => 9),
    "158" => array ( "area" => "Mikkeli", "minimum" => 7, "maximum" => 9),
    "157" => array ( "area" => "Mikkeli", "minimum" => 7, "maximum" => 9),
    "156" => array ( "area" => "Mikkeli", "minimum" => 7, "maximum" => 9),
    "155" => array ( "area" => "Mikkeli", "minimum" => 7, "maximum" => 9),
    "154" => array ( "area" => "Mikkeli", "minimum" => 7, "maximum" => 9),
    "153" => array ( "area" => "Mikkeli", "minimum" => 7, "maximum" => 9),
    "152" => array ( "area" => "Mikkeli", "minimum" => 7, "maximum" => 9),
    "151" => array ( "area" => "Mikkeli", "minimum" => 7, "maximum" => 9),
    "148" => array ( "area" => "Keski-Suomi", "minimum" => 7, "maximum" => 9),
    "147" => array ( "area" => "Keski-Suomi", "minimum" => 7, "maximum" => 9),
    "146" => array ( "area" => "Keski-Suomi", "minimum" => 7, "maximum" => 9),
    "145" => array ( "area" => "Keski-Suomi", "minimum" => 7, "maximum" => 9),
    "144" => array ( "area" => "Keski-Suomi", "minimum" => 7, "maximum" => 9),
    "143" => array ( "area" => "Keski-Suomi", "minimum" => 7, "maximum" => 9),
    "142" => array ( "area" => "Keski-Suomi", "minimum" => 7, "maximum" => 9),
    "141" => array ( "area" => "Keski-Suomi", "minimum" => 7, "maximum" => 9),
    "138" => array ( "area" => "Pohjois-Karjala", "minimum" => 7, "maximum" => 9),
    "137" => array ( "area" => "Pohjois-Karjala", "minimum" => 7, "maximum" => 9),
    "136" => array ( "area" => "Pohjois-Karjala", "minimum" => 7, "maximum" => 9),
    "135" => array ( "area" => "Pohjois-Karjala", "minimum" => 7, "maximum" => 9),
    "134" => array ( "area" => "Pohjois-Karjala", "minimum" => 7, "maximum" => 9),
    "133" => array ( "area" => "Pohjois-Karjala", "minimum" => 7, "maximum" => 9),
    "132" => array ( "area" => "Pohjois-Karjala", "minimum" => 7, "maximum" => 9),
    "131" => array ( "area" => "Pohjois-Karjala", "minimum" => 7, "maximum" => 9),
    "109" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "108" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "107" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "106" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "105" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "104" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "103" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "102" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "101" => array ( "area" => "", "minimum" => 8, "maximum" => 10),
    "100" => array ( "area" => "", "minimum" => 7, "maximum" => 9),
    "98" => array ( "area" => "Uusimaa I", "minimum" => 7, "maximum" => 9),
    "97" => array ( "area" => "Uusimaa I", "minimum" => 7, "maximum" => 9),
    "96" => array ( "area" => "Uusimaa I", "minimum" => 7, "maximum" => 9),
    "95" => array ( "area" => "Uusimaa I", "minimum" => 7, "maximum" => 9),
    "94" => array ( "area" => "Uusimaa I", "minimum" => 7, "maximum" => 9),
    "93" => array ( "area" => "Uusimaa I", "minimum" => 7, "maximum" => 9),
    "92" => array ( "area" => "Uusimaa I", "minimum" => 7, "maximum" => 9),
    "91" => array ( "area" => "Uusimaa I", "minimum" => 7, "maximum" => 9),
    "88" => array ( "area" => "Oulu", "minimum" => 7, "maximum" => 9),
    "87" => array ( "area" => "Oulu", "minimum" => 7, "maximum" => 9),
    "86" => array ( "area" => "Oulu", "minimum" => 7, "maximum" => 9),
    "85" => array ( "area" => "Oulu", "minimum" => 7, "maximum" => 9),
    "84" => array ( "area" => "Oulu", "minimum" => 7, "maximum" => 9),
    "83" => array ( "area" => "Oulu", "minimum" => 7, "maximum" => 9),
    "82" => array ( "area" => "Oulu", "minimum" => 7, "maximum" => 9),
    "81" => array ( "area" => "Oulu", "minimum" => 7, "maximum" => 9),
    "73" => array ( "area" => "", "minimum" => 10, "maximum" => 10),
    "71" => array ( "area" => "", "minimum" => 9, "maximum" => 9),
    "68" => array ( "area" => "Vaasa", "minimum" => 7, "maximum" => 9),
    "67" => array ( "area" => "Vaasa", "minimum" => 7, "maximum" => 9),
    "66" => array ( "area" => "Vaasa", "minimum" => 7, "maximum" => 9),
    "65" => array ( "area" => "Vaasa", "minimum" => 7, "maximum" => 9),
    "64" => array ( "area" => "Vaasa", "minimum" => 7, "maximum" => 9),
    "63" => array ( "area" => "Vaasa", "minimum" => 7, "maximum" => 9),
    "62" => array ( "area" => "Vaasa", "minimum" => 7, "maximum" => 9),
    "61" => array ( "area" => "Vaasa", "minimum" => 7, "maximum" => 9),
    "58" => array ( "area" => "Kymi", "minimum" => 7, "maximum" => 9),
    "57" => array ( "area" => "Kymi", "minimum" => 7, "maximum" => 9),
    "56" => array ( "area" => "Kymi", "minimum" => 7, "maximum" => 9),
    "55" => array ( "area" => "Kymi", "minimum" => 7, "maximum" => 9),
    "54" => array ( "area" => "Kymi", "minimum" => 7, "maximum" => 9),
    "53" => array ( "area" => "Kymi", "minimum" => 7, "maximum" => 9),
    "52" => array ( "area" => "Kymi", "minimum" => 7, "maximum" => 9),
    "51" => array ( "area" => "Kymi", "minimum" => 7, "maximum" => 9),
    "38" => array ( "area" => "Häme", "minimum" => 7, "maximum" => 9),
    "37" => array ( "area" => "Häme", "minimum" => 7, "maximum" => 9),
    "36" => array ( "area" => "Häme", "minimum" => 7, "maximum" => 9),
    "35" => array ( "area" => "Häme", "minimum" => 7, "maximum" => 9),
    "34" => array ( "area" => "Häme", "minimum" => 7, "maximum" => 9),
    "33" => array ( "area" => "Häme", "minimum" => 7, "maximum" => 9),
    "32" => array ( "area" => "Häme", "minimum" => 7, "maximum" => 9),
    "31" => array ( "area" => "Häme", "minimum" => 7, "maximum" => 9),
    "29" => array ( "area" => "", "minimum" => 8, "maximum" => 9),
    "28" => array ( "area" => "Turku and Pori", "minimum" => 7, "maximum" => 9),
    "27" => array ( "area" => "Turku and Pori", "minimum" => 7, "maximum" => 9),
    "26" => array ( "area" => "Turku and Pori", "minimum" => 7, "maximum" => 9),
    "25" => array ( "area" => "Turku and Pori", "minimum" => 7, "maximum" => 9),
    "24" => array ( "area" => "Turku and Pori", "minimum" => 7, "maximum" => 9),
    "23" => array ( "area" => "Turku and Pori", "minimum" => 7, "maximum" => 9),
    "22" => array ( "area" => "Turku and Pori", "minimum" => 7, "maximum" => 9),
    "21" => array ( "area" => "Turku and Pori", "minimum" => 7, "maximum" => 9)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 4 + $data["minimum"] && strlen ( $parameters["Number"]) <= 4 + $data["maximum"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "358", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Finland", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+358 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Finlandian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
