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
 * related to country database of Albania.
 *
 * Reference: https://www.itu.int/oth/T0202000002/en (2017-03-30)
 * Reference: https://en.wikipedia.org/wiki/Telephone_numbers_in_Albania
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
 * E.164 Albania country hook
 */
framework_add_filter ( "e164_identify_country_ALB", "e164_identify_country_ALB");

/**
 * E.164 Albanian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "ALB" (code for Albania). This
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
function e164_identify_country_ALB ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Albania
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+355")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC (6[2-9]) and 7 digits SN
   * ([2-9]XXXXXX)
   */
  $prefixes = array (
    "69" => "Vodafone Albania",
    "68" => "Telekom Albania",
    "67" => "ALBtelecom Mobile",
    "66" => "",
    "65" => "",
    "64" => "",
    "63" => "",
    "62" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 6, 1) >= 2 && (int) substr ( $parameters["Number"], 6, 1) <= 9)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "355", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Albania", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . "-" . substr ( $parameters["Number"], 9), "International" => "+355 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
      } else {
        return ( is_array ( $buffer) ? $buffer : false);
      }
    }
  }

  /**
   * Check for fixed line network
   */
  $prefixes = array (
    "891" => "Konispol",
    "885" => "Memaliaj",
    "881" => "Libohovë",
    "875" => "Këlcyrë",
    "871" => "Leskovik",
    "861" => "Maliq",
    "815" => "Delvinë",
    "814" => "Tepelenë",
    "813" => "Përmet",
    "812" => "Ersekë",
    "811" => "Bilisht",
    "591" => "Përrenjas",
    "583" => "Bradashesh",
    "582" => "Belsh",
    "581" => "Cërrik",
    "577" => "Rrogozhinë",
    "573" => "Sukth",
    "572" => "Manëz",
    "571" => "Shijak",
    "563" => "Fushë-Krujë",
    "561" => "Mamurras",
    "514" => "Librazhd",
    "513" => "Gramsh",
    "512" => "Peqin",
    "511" => "Kruje",
    "393" => "Himarë",
    "392" => "Selenicë",
    "391" => "Orikum",
    "382" => "Roskovec",
    "381" => "Patos",
    "371" => "Divjakë",
    "368" => "Poliçan",
    "361" => "Ura Vajgurore",
    "313" => "Ballsh",
    "312" => "Çorovodë",
    "311" => "Kuçovë",
    "287" => "Klos",
    "284" => "Rubik",
    "271" => "Fushë-Arrëz",
    "261" => "Vau-Dejës",
    "219" => "Bulqizë",
    "218" => "Peshkopi",
    "217" => "Burrel",
    "216" => "Rrëshen",
    "215" => "Lezhë",
    "214" => "Krumë",
    "213" => "Bajram Curri",
    "212" => "Pukë",
    "211" => "Koplik",
    "85" => "Sarandë",
    "84" => "Gjirokastër",
    "83" => "Pogradec",
    "82" => "Korçë",
    "55" => "Kavajë",
    "54" => "Elbasan",
    "53" => "Laç",
    "52" => "Durrës",
    "47" => "Kamëz and Vorë",
    "35" => "Lushnje",
    "34" => "Fier",
    "33" => "Vlorë",
    "32" => "Berat",
    "24" => "Kukës",
    "22" => "Shkodër",
    "4" => "Tirana"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4 + strlen ( $prefix), 1) >= 2 && (int) substr ( $parameters["Number"], 4 + strlen ( $prefix), 1) <= 9)
      {
        switch ( strlen ( $prefix))
        {
          case 1:
            $callformats = array ( "Local" => "0" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8, 4), "International" => "+355 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8, 4));
            break;
          case 2:
            $callformats = array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 3), "International" => "+355 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 3));
            break;
          case 3:
            $callformats = array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 3), "International" => "+355 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 3));
            break;
        }
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "355", "NDC" => $prefix, "Country" => "Albania", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
      }
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Albanian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
