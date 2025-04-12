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
 * related to country database of Libya.
 *
 * Reference: https://www.itu.int/oth/T020200007A/en (2006-07-20)
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
 * E.164 Libya country hook
 */
framework_add_filter ( "e164_identify_country_LBY", "e164_identify_country_LBY");

/**
 * E.164 Libyan area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "LBY" (code for Libya). This hook
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
function e164_identify_country_LBY ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Libya
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+218")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "92",
    "91"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "218", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Libya", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+218 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "884" => array ( "area" => "Jaghbub", "digits" => 8),
    "854" => array ( "area" => "Slenta", "digits" => 8),
    "852" => array ( "area" => "Massa", "digits" => 8),
    "851" => array ( "area" => "Shahat", "digits" => 8),
    "821" => array ( "area" => "Gubba", "digits" => 8),
    "734" => array ( "area" => "Traghen", "digits" => 8),
    "733" => array ( "area" => "Garda", "digits" => 8),
    "732" => array ( "area" => "Bergen", "digits" => 8),
    "731" => array ( "area" => "Wadi Atba", "digits" => 8),
    "729" => array ( "area" => "Ghrefa", "digits" => 8),
    "727" => array ( "area" => "Zawaya", "digits" => 8),
    "726" => array ( "area" => "Um Laranib", "digits" => 8),
    "725" => array ( "area" => "Murzuk", "digits" => 8),
    "724" => array ( "area" => "Ghat", "digits" => 8),
    "723" => array ( "area" => "Edry", "digits" => 8),
    "721" => array ( "area" => "Brak", "digits" => 8),
    "685" => array ( "area" => "Tomina", "digits" => 8),
    "684" => array ( "area" => "Elbayada", "digits" => 8),
    "683" => array ( "area" => "Taknes", "digits" => 8),
    "682" => array ( "area" => "Jardas", "digits" => 8),
    "681" => array ( "area" => "Tolmitha", "digits" => 8),
    "657" => array ( "area" => "Jalo", "digits" => 8),
    "655" => array ( "area" => "Bisher", "digits" => 8),
    "654" => array ( "area" => "Sidi Sultan", "digits" => 8),
    "653" => array ( "area" => "Ojla", "digits" => 8),
    "652" => array ( "area" => "Kofra", "digits" => 8),
    "629" => array ( "area" => "Elmagrun", "digits" => 8),
    "628" => array ( "area" => "Seluk", "digits" => 8),
    "627" => array ( "area" => "Jerdina", "digits" => 8),
    "626" => array ( "area" => "Kaalifa", "digits" => 8),
    "625" => array ( "area" => "Deriana", "digits" => 8),
    "624" => array ( "area" => "Elkuwaifia", "digits" => 8),
    "623" => array ( "area" => "Gmines", "digits" => 8),
    "584" => array ( "area" => "Zella", "digits" => 8),
    "583" => array ( "area" => "Soussa", "digits" => 8),
    "582" => array ( "area" => "Sokna", "digits" => 8),
    "581" => array ( "area" => "Wodan", "digits" => 8),
    "555" => array ( "area" => "Noflia", "digits" => 8),
    "554" => array ( "area" => "Wadi Jeref", "digits" => 8),
    "553" => array ( "area" => "Abengawad", "digits" => 8),
    "551" => array ( "area" => "Abuhadi", "digits" => 8),
    "529" => array ( "area" => "Bugrain", "digits" => 8),
    "526" => array ( "area" => "Zawyat Elmahjub", "digits" => 8),
    "524" => array ( "area" => "Kasarahmad", "digits" => 8),
    "523" => array ( "area" => "Dafnia", "digits" => 8),
    "522" => array ( "area" => "Tawergha", "digits" => 8),
    "521" => array ( "area" => "Zliten", "digits" => 8),
    "484" => array ( "area" => "Ghadames", "digits" => 8),
    "482" => array ( "area" => "Tigi", "digits" => 8),
    "481" => array ( "area" => "Kabaw", "digits" => 8),
    "454" => array ( "area" => "Al Josh", "digits" => 8),
    "453" => array ( "area" => "Reyana", "digits" => 8),
    "452" => array ( "area" => "Rujban", "digits" => 8),
    "427" => array ( "area" => "Kikla", "digits" => 8),
    "425" => array ( "area" => "Buzayan", "digits" => 8),
    "423" => array ( "area" => "Guassem", "digits" => 8),
    "422" => array ( "area" => "Mizda", "digits" => 8),
    "421" => array ( "area" => "Yefren", "digits" => 8),
    "326" => array ( "area" => "Kussabat", "digits" => 8),
    "325" => array ( "area" => "Tarhuna", "digits" => 8),
    "323" => array ( "area" => "Wadi Keam", "digits" => 8),
    "322" => array ( "area" => "Bani Walid", "digits" => 8),
    "284" => array ( "area" => "Hugialin", "digits" => 8),
    "282" => array ( "area" => "Ajailat", "digits" => 8),
    "281" => array ( "area" => "Jmail", "digits" => 8),
    "279" => array ( "area" => "Elmaya", "digits" => 8),
    "277" => array ( "area" => "Mamura", "digits" => 8),
    "275" => array ( "area" => "Matred", "digits" => 8),
    "274" => array ( "area" => "Abu Issa", "digits" => 8),
    "272" => array ( "area" => "Azizia", "digits" => 8),
    "271" => array ( "area" => "Hashan", "digits" => 8),
    "252" => array ( "area" => "Zahra", "digits" => 8),
    "224" => array ( "area" => "Swajni", "digits" => 8),
    "206" => array ( "area" => "Suk Elkhamis", "digits" => 8),
    "205" => array ( "area" => "Sidiessaiah", "digits" => 8),
    "84" => array ( "area" => "El Beida", "digits" => 8),
    "82" => array ( "area" => "Haraua", "digits" => 8),
    "81" => array ( "area" => "Derna", "digits" => 8),
    "73" => array ( "area" => "Ubary", "digits" => 8),
    "71" => array ( "area" => "Sebha", "digits" => 8),
    "67" => array ( "area" => "Elmareg", "digits" => 8),
    "63" => array ( "area" => "Benina", "digits" => 8),
    "61" => array ( "area" => "Benghazi", "digits" => 8),
    "57" => array ( "area" => "Hun", "digits" => 8),
    "54" => array ( "area" => "Sirt", "digits" => 8),
    "51" => array ( "area" => "Misratah", "digits" => 8),
    "47" => array ( "area" => "Nalut", "digits" => 8),
    "41" => array ( "area" => "Garian", "digits" => 8),
    "31" => array ( "area" => "Khums", "digits" => 8),
    "26" => array ( "area" => "Taigura", "digits" => 8),
    "25" => array ( "area" => "Zuara", "digits" => 8),
    "24" => array ( "area" => "Sabratha", "digits" => 8),
    "23" => array ( "area" => "Zawia", "digits" => 8),
    "22" => array ( "area" => "Tripoli International Airport", "digits" => 8),
    "21" => array ( "area" => "Tripoli", "digits" => 9)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["digits"])
    {
      if ( $data["digits"] == 8)
      {
        $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+218 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8));
      } else {
        $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+218 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9));
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "218", "NDC" => (string) $prefix, "Country" => "Libya", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Libyan phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
