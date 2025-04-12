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
 * related to country database of Netherlands.
 *
 * Reference: https://www.itu.int/oth/T0202000096/en (2016-02-25)
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
 * E.164 Netherlands country hook
 */
framework_add_filter ( "e164_identify_country_NLD", "e164_identify_country_NLD");

/**
 * E.164 Netherlandian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "NLD" (code for
 * Netherlands). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_NLD ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Netherlands
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+31")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Netherlands has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "68",
    "65",
    "64",
    "63",
    "62",
    "61"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "31", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Netherlands", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+31 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 2 or 3 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "599" => "Stadskanaal",
    "598" => "Hoogezand-Sappemeer",
    "597" => "Winschoten",
    "596" => "Appingedam",
    "595" => "Warffum",
    "594" => "Zuidhorn",
    "593" => "Beilen",
    "592" => "Assen",
    "591" => "Emmen",
    "578" => "Epe",
    "577" => "Uddel",
    "575" => "Zutphen",
    "573" => "Lochem",
    "572" => "Raalte",
    "571" => "Voorst",
    "570" => "Deventer",
    "566" => "Irnsum",
    "562" => "Terschelling/Vlieland",
    "561" => "Wolvega",
    "548" => "Rijssen",
    "547" => "Goor",
    "546" => "Almelo",
    "545" => "Neede",
    "544" => "Groenlo",
    "543" => "Winterswijk",
    "541" => "Oldenzaal",
    "529" => "Ommen",
    "528" => "Hoogeveen",
    "527" => "Emmeloord",
    "525" => "Elburg",
    "524" => "Coevorden",
    "523" => "Hardenberg",
    "522" => "Meppel",
    "521" => "Steenwijk",
    "519" => "Dokkum",
    "518" => "St. Annaparochie",
    "517" => "Franeker",
    "516" => "Oosterwolde",
    "515" => "Sneek",
    "514" => "Balk",
    "513" => "Heerenveen",
    "512" => "Drachten",
    "511" => "Veenwouden",
    "499" => "Best",
    "497" => "Eersel",
    "495" => "Weert",
    "493" => "Deurne",
    "492" => "Helmond",
    "488" => "Zetten",
    "487" => "Druten",
    "486" => "Grave",
    "485" => "Cuijk",
    "481" => "Bemmel",
    "478" => "Venray",
    "475" => "Roermond",
    "418" => "Zaltbommel",
    "416" => "Waalwijk",
    "413" => "Veghel",
    "412" => "Oss",
    "411" => "Boxtel",
    "348" => "Woerden",
    "347" => "Vianen",
    "346" => "Maarssen",
    "345" => "Culemborg",
    "344" => "Tiel",
    "343" => "Doorn",
    "342" => "Barneveld",
    "341" => "Harderwijk",
    "321" => "Dronten",
    "320" => "Lelystad",
    "318" => "Ede / Veenendaal",
    "317" => "Wageningen",
    "316" => "Zevenaar",
    "315" => "Terborg",
    "314" => "Doetinchem",
    "313" => "Dieren",
    "299" => "Purmerend",
    "297" => "Aalsmeer",
    "294" => "Weesp",
    "255" => "IJmuiden",
    "252" => "Hillegom",
    "251" => "Beverwijk",
    "229" => "Hoorn",
    "228" => "Enkhuizen",
    "227" => "Medemblik",
    "226" => "Harenkarspel",
    "224" => "Schagen",
    "223" => "Den Helder",
    "222" => "Texel",
    "187" => "Middelharnis",
    "186" => "Oud-Beijerland",
    "184" => "Sliedrecht",
    "183" => "Gorinchem",
    "182" => "Gouda",
    "181" => "Spijkenisse",
    "180" => "Ridderkerk and Zuidplas",
    "174" => "Naaldwijk",
    "172" => "Alphen aan den Rijn",
    "168" => "Zevenbergen",
    "167" => "Steenbergen",
    "166" => "Tholen",
    "165" => "Roosendaal",
    "164" => "Bergen op Zoom",
    "162" => "Oosterhout",
    "161" => "Gilze-Rijen",
    "118" => "Middelburg / Vlissingen",
    "117" => "Sluis",
    "115" => "Terneuzen",
    "114" => "Hulst",
    "113" => "Goes",
    "111" => "Zierikzee",
    "88" => "",
    "79" => "Zoetermeer",
    "78" => "Dordrecht",
    "77" => "Venlo",
    "76" => "Breda",
    "75" => "Zaandam",
    "74" => "Hengelo",
    "73" => "'s-Hertogenbosch",
    "72" => "Alkmaar",
    "71" => "Leiden",
    "70" => "The Hague",
    "58" => "Leeuwarden",
    "55" => "Apeldoorn",
    "53" => "Enschede",
    "50" => "Groningen",
    "46" => "Sittard",
    "45" => "Heerlen",
    "43" => "Maastricht",
    "40" => "Eindhoven",
    "38" => "Zwolle",
    "36" => "Almere",
    "35" => "Hilversum",
    "33" => "Amersfoort",
    "30" => "Utrecht",
    "26" => "Arnhem",
    "24" => "Nijmegen",
    "23" => "Haarlem",
    "20" => "Amsterdam",
    "15" => "Delft",
    "13" => "Tilburg",
    "10" => "Rotterdam"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "31", "NDC" => $prefix, "Country" => "Netherlands", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, strlen ( $prefix)) . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix)), "International" => "+31 " . substr ( $parameters["Number"], 3, strlen ( $prefix)) . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix)))));
    }
  }

  /**
   * Check for Audiotext network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "675",
    "674",
    "673",
    "672",
    "671",
    "670"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "31", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Netherlands", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_AUDIOTEXT, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+31 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for Tollfree network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "800"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "31", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Netherlands", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+31 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for VPN network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array ( 
    "82"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "31", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Netherlands", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VPN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+31 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for PRN network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array ( 
    "909",
    "906",
    "900",
    "87",
    "84"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "31", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Netherlands", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+31 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "91",
    "85"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "31", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Netherlands", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+31 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Netherlandian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
