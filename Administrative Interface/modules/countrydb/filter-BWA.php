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
 * related to country database of Botswana.
 *
 * Reference: https://www.itu.int/oth/T020200001C/en (2018-11-12)
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
 * E.164 Botswana country hook
 */
framework_add_filter ( "e164_identify_country_BWA", "e164_identify_country_BWA");

/**
 * E.164 Botswanian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "BWA" (code for
 * Botswana). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_BWA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Botswana
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+267")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "77200" => "Botswana Telecommunications Corporation Limited (BTCL)",
    "778" => "Mascom Wireless",
    "777" => "Mascom Wireless",
    "776" => "Mascom Wireless",
    "775" => "Orange Botswana",
    "774" => "Orange Botswana",
    "771" => "Mascom Wireless",
    "770" => "Mascom Wireless",
    "769" => "Orange Botswana",
    "768" => "Botswana Telecommunications Corporation Limited (BTCL)",
    "767" => "Mascom Wireless",
    "766" => "Mascom Wireless",
    "765" => "Orange Botswana",
    "764" => "Orange Botswana",
    "763" => "Orange Botswana",
    "762" => "Mascom Wireless",
    "761" => "Mascom Wireless",
    "760" => "Mascom Wireless",
    "759" => "Mascom Wireless",
    "758" => "Botswana Telecommunications Corporation Limited (BTCL)",
    "757" => "Orange Botswana",
    "756" => "Mascom Wireless",
    "755" => "Mascom Wireless",
    "754" => "Mascom Wireless",
    "753" => "Orange Botswana",
    "752" => "Orange Botswana",
    "751" => "Orange Botswana",
    "750" => "Orange Botswana",
    "749" => "Botswana Telecommunications Corporation Limited (BTCL)",
    "748" => "Orange Botswana",
    "747" => "Mascom Wireless",
    "746" => "Mascom Wireless",
    "745" => "Mascom Wireless",
    "744" => "Orange Botswana",
    "743" => "Orange Botswana",
    "742" => "Mascom Wireless",
    "741" => "Mascom Wireless",
    "740" => "Mascom Wireless",
    "73" => "Botswana Telecommunications Corporation Limited (BTCL)",
    "72" => "Orange Botswana",
    "71" => "Mascom Wireless"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "267", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Botswana", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+267 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "687" => "Maun",
    "686" => "Maun",
    "680" => "Maun",
    "659" => "Gantsi",
    "654" => "Kgalagadi",
    "651" => "Kgalagadi",
    "625" => "Kasane",
    "623" => "Kasane",
    "622" => "Kasane",
    "621" => "Kasane",
    "599" => "Molepolole",
    "594" => "Molepolole",
    "593" => "Molepolole",
    "592" => "Molepolole",
    "591" => "Molepolole",
    "590" => "Molepolole",
    "588" => "Jwaneng",
    "577" => "Mochudi",
    "574" => "Mochudi",
    "573" => "Mochudi",
    "572" => "Mochudi",
    "571" => "Mochudi",
    "549" => "Barolong/Ngwaketse",
    "548" => "Barolong/Ngwaketse",
    "544" => "Barolong/Ngwaketse",
    "540" => "Barolong/Ngwaketse",
    "539" => "Ramotswa",
    "538" => "Ramotswa",
    "533" => "Lobatse",
    "530" => "Lobatse",
    "495" => "Palapye",
    "494" => "Palapye",
    "493" => "Palapye",
    "492" => "Palapye",
    "491" => "Palapye",
    "490" => "Palapye",
    "477" => "Mahalapye",
    "476" => "Mahalapye",
    "472" => "Mahalapye",
    "471" => "Mahalapye",
    "463" => "Serowe",
    "460" => "Serowe",
    "397" => "Gaborone",
    "395" => "Gaborone",
    "394" => "Gaborone",
    "393" => "Gaborone",
    "392" => "Gaborone",
    "391" => "Gaborone",
    "390" => "Gaborone",
    "371" => "Gaborone",
    "370" => "Gaborone",
    "355" => "Gaborone",
    "319" => "Gaborone",
    "318" => "Gaborone",
    "317" => "Gaborone",
    "316" => "Gaborone",
    "315" => "Gaborone",
    "313" => "Gaborone",
    "312" => "Gaborone",
    "310" => "Outer Gaborone",
    "298" => "Letlhakane/Orapa",
    "297" => "Letlhakane/Orapa",
    "295" => "Letlhakane/Orapa",
    "290" => "Letlhakane/Orapa",
    "264" => "Selebi-Phikwe",
    "262" => "Selebi-Phikwe",
    "261" => "Selebi-Phikwe",
    "260" => "Selebi-Phikwe",
    "248" => "Francistown",
    "244" => "Francistown",
    "243" => "Francistown",
    "242" => "Francistown",
    "241" => "Francistown",
    "240" => "Francistown",
    "36" => "Gaborone"
  );
  foreach ( $prefixes as $prefix => $city)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      switch ( substr ( $parameters["Number"], 4, 1))
      {
        case "2":
          $area = "Francistown Region";
          break;
        case "3":
          $area = "Gaborone";
          break;
        case "4":
          $area = "Palapye Region";
          break;
        case "5":
          $area = "South-East Region";
          break;
        case "6":
          $area = "North and West Regions";
          break;
        default:
          $area = "";
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "267", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Botswana", "Area" => $area, "City" => $city, "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+267 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "79120" => "Dapit Ventures T/A GCSat Botswana",
    "79119" => "Blue Pearl Communications T/A ROI",
    "79118" => "Paratus Africa",
    "79117" => "ConceroTel",
    "79116" => "Mission Communications",
    "79115" => "Abari Communications",
    "79114" => "MTN Business Solutions",
    "79113" => "Stature (OpenVoice)",
    "79112" => "FDI Foneworx",
    "79111" => "Internet Options Botswana",
    "79110" => "Microla Botswana",
    "79109" => "MicroTeck Enterprises",
    "79108" => "Tsagae Communications",
    "79107" => "Stature (OpenVoice)",
    "79106" => "Mega Internet",
    "79105" => "OPQ Net",
    "79104" => "Fourth Dimension",
    "79103" => "Business Solutions Consultants",
    "79102" => "Global Broadband Solutions",
    "79101" => "AfriTel",
    "79100" => "Virtual Business Network Services",
    "7922" => "Orange Botswana",
    "7921" => "Botswana Telecommunications Corporation Limited (BTCL)",
    "7920" => "Orange Botswana"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "267", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Botswana", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+267 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Botswanian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
