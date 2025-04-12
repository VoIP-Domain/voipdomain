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
 * related to country database of Iceland.
 *
 * Reference: https://www.itu.int/oth/T0202000062/en (2012-07-24)
 * Reference: http://www.pfs.is/english/telecom-affairs/numbering/ (2018-02-08)
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
 * E.164 Iceland country hook
 */
framework_add_filter ( "e164_identify_country_ISL", "e164_identify_country_ISL");

/**
 * E.164 Icelandian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "ISL" (code for
 * Iceland). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_ISL ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Iceland
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+354")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 or 6 digits SN
   */
  $prefixes = array (
    "888" => array ( "digits" => 7, "operator" => "Síminn"),
    "882" => array ( "digits" => 7, "operator" => "Síminn"),
    "859" => array ( "digits" => 7, "operator" => "Síminn"),
    "858" => array ( "digits" => 7, "operator" => "Síminn"),
    "857" => array ( "digits" => 7, "operator" => "Síminn"),
    "856" => array ( "digits" => 7, "operator" => "Síminn"),
    "855" => array ( "digits" => 7, "operator" => "Síminn"),
    "854" => array ( "digits" => 7, "operator" => "Síminn"),
    "853" => array ( "digits" => 7, "operator" => "Síminn"),
    "852" => array ( "digits" => 7, "operator" => "Síminn"),
    "851" => array ( "digits" => 7, "operator" => "Síminn"),
    "829" => array ( "digits" => 7, "operator" => "Síminn"),
    "825" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "824" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "823" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "822" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "821" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "820" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "793" => array ( "digits" => 7, "operator" => "Nova ehf."),
    "792" => array ( "digits" => 7, "operator" => "Nova ehf."),
    "791" => array ( "digits" => 7, "operator" => "Nova ehf."),
    "790" => array ( "digits" => 7, "operator" => "Nova ehf."),
    "688" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "687" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "686" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "680" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "670" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "669" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "666" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "665" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "664" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "663" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "662" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "661" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "660" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "659" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "655" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "651" => array ( "digits" => 7, "operator" => "IMC"),
    "650" => array ( "digits" => 7, "operator" => "IMC"),
    "649" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "647" => array ( "digits" => 7, "operator" => "IMC"),
    "646" => array ( "digits" => 7, "operator" => "IMC"),
    "644" => array ( "digits" => 7, "operator" => "Nova ehf."),
    "632" => array ( "digits" => 7, "operator" => "Tismi BV"),
    "630" => array ( "digits" => 7, "operator" => "IMC"),
    "626" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "625" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "624" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "623" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "622" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "621" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "620" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "618" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "617" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "616" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "615" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf."),
    "614" => array ( "digits" => 7, "operator" => "365 miðlar hf"),
    "613" => array ( "digits" => 7, "operator" => "365 miðlar hf"),
    "612" => array ( "digits" => 7, "operator" => "365 miðlar hf"),
    "611" => array ( "digits" => 7, "operator" => "365 miðlar hf"),
    "389" => array ( "digits" => 9, "operator" => "IMC"),
    "388" => array ( "digits" => 9, "operator" => "IMC"),
    "385" => array ( "digits" => 9, "operator" => "Síminn"),
    "352" => array ( "digits" => 9, "operator" => "Tismi BV"),
    "89" => array ( "digits" => 7, "operator" => "Síminn"),
    "86" => array ( "digits" => 7, "operator" => "Síminn"),
    "84" => array ( "digits" => 7, "operator" => "Síminn"),
    "83" => array ( "digits" => 7, "operator" => "Síminn"),
    "78" => array ( "digits" => 7, "operator" => "Nova ehf."),
    "77" => array ( "digits" => 7, "operator" => "Nova ehf."),
    "76" => array ( "digits" => 7, "operator" => "Nova ehf."),
    "69" => array ( "digits" => 7, "operator" => "Fjarskipti/ Vodafone ehf.")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["digits"])
    {
      switch ( $data["digits"])
      {
        case 7:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7));
          break;
        case 9:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10));
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "354", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Iceland", "Area" => "", "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => $callformats));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "599" => "Fjarskipti/ Vodafone ehf.",
    "598" => "Fjarskipti/ Vodafone ehf.",
    "596" => "Fjarskipti/ Vodafone ehf.",
    "595" => "Fjarskipti/ Vodafone ehf.",
    "594" => "Fjarskipti/ Vodafone ehf.",
    "593" => "Síminn",
    "591" => "Fjarskipti/ Vodafone ehf.",
    "590" => "Fjarskipti/ Vodafone ehf.",
    "589" => "Síminn",
    "588" => "Síminn",
    "587" => "Síminn",
    "586" => "Síminn",
    "585" => "Síminn",
    "583" => "Síminn",
    "582" => "365 miðlar hf",
    "581" => "Síminn",
    "580" => "Síminn",
    "578" => "Fjarskipti/ Vodafone ehf.",
    "577" => "Síminn",
    "575" => "Síminn",
    "572" => "365 miðlar hf",
    "571" => "Fjarskipti/ Vodafone ehf.",
    "570" => "Síminn",
    "559" => "Fjarskipti/ Vodafone ehf.",
    "558" => "Fjarskipti/ Vodafone ehf.",
    "557" => "Síminn",
    "556" => "Fjarskipti/ Vodafone ehf.",
    "555" => "Síminn",
    "554" => "Síminn",
    "553" => "Síminn",
    "552" => "Síminn",
    "551" => "Síminn",
    "550" => "Síminn",
    "547" => "Hringdu ehf",
    "546" => "Símafélagið ehf",
    "545" => "Síminn",
    "544" => "Síminn",
    "543" => "Fjarskipti/ Vodafone ehf.",
    "540" => "Síminn",
    "539" => "Tismi BV",
    "537" => "Hringdu ehf",
    "535" => "Síminn",
    "534" => "Fjarskipti/ Vodafone ehf.",
    "533" => "Síminn",
    "532" => "Síminn",
    "531" => "Síminn",
    "530" => "Síminn",
    "528" => "Síminn",
    "527" => "Fjarskipti/ Vodafone ehf.",
    "525" => "Síminn",
    "522" => "Síminn",
    "520" => "Síminn",
    "519" => "Nova ehf.",
    "518" => "Fjarskipti/ Vodafone ehf.",
    "517" => "Fjarskipti/ Vodafone ehf.",
    "516" => "Síminn",
    "515" => "Síminn",
    "514" => "Fjarskipti/ Vodafone ehf.",
    "513" => "Fjarskipti/ Vodafone ehf.",
    "512" => "Fjarskipti/ Vodafone ehf.",
    "511" => "Síminn",
    "510" => "Síminn",
    "505" => "Síminn",
    "488" => "Síminn",
    "487" => "Síminn",
    "486" => "Síminn",
    "483" => "Síminn",
    "482" => "Síminn",
    "481" => "Síminn",
    "480" => "Síminn",
    "478" => "Síminn",
    "477" => "Síminn",
    "476" => "Síminn",
    "475" => "Síminn",
    "474" => "Síminn",
    "473" => "Síminn",
    "472" => "Síminn",
    "471" => "Síminn",
    "470" => "Síminn",
    "469" => "Fjarskipti/ Vodafone ehf.",
    "468" => "Síminn",
    "467" => "Síminn",
    "466" => "Síminn",
    "465" => "Síminn",
    "464" => "Síminn",
    "463" => "Síminn",
    "462" => "Síminn",
    "461" => "Síminn",
    "460" => "Síminn",
    "458" => "Síminn",
    "456" => "Síminn",
    "455" => "Síminn",
    "454" => "Símafélagið ehf",
    "453" => "Síminn",
    "452" => "Síminn",
    "451" => "Síminn",
    "450" => "Síminn",
    "445" => "365 miðlar hf",
    "444" => "Síminn",
    "442" => "Síminn",
    "441" => "Fjarskipti/ Vodafone ehf.",
    "440" => "Fjarskipti/ Vodafone ehf.",
    "438" => "Síminn",
    "437" => "Síminn",
    "436" => "Síminn",
    "435" => "Síminn",
    "434" => "Síminn",
    "433" => "Síminn",
    "432" => "Síminn",
    "431" => "Síminn",
    "430" => "Síminn",
    "427" => "Síminn",
    "426" => "Síminn",
    "425" => "Síminn",
    "424" => "Síminn",
    "423" => "Síminn",
    "422" => "Síminn",
    "421" => "Síminn",
    "420" => "Síminn",
    "419" => "Nova ehf.",
    "416" => "Símafélagið ehf",
    "415" => "Símafélagið ehf",
    "414" => "Fjarskipti/ Vodafone ehf.",
    "412" => "Fjarskipti/ Vodafone ehf.",
    "411" => "Fjarskipti/ Vodafone ehf.",
    "410" => "Fjarskipti/ Vodafone ehf.",
    "56" => "Síminn"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "354", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Iceland", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for PRN network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "9087" => "Fjarskipti/ Vodafone ehf.",
    "9086" => "Síminn",
    "9085" => "Fjarskipti/ Vodafone ehf.",
    "9083" => "Fjarskipti/ Vodafone ehf.",
    "9082" => "Síminn",
    "9081" => "Fjarskipti/ Vodafone ehf.",
    "9080" => "Símafélagið ehf",
    "9077" => "Síminn",
    "9073" => "Fjarskipti/ Vodafone ehf.",
    "9072" => "Síminn",
    "9071" => "Fjarskipti/ Vodafone ehf.",
    "9059" => "Símafélagið ehf",
    "9057" => "Fjarskipti/ Vodafone ehf.",
    "9056" => "Fjarskipti/ Vodafone ehf.",
    "9055" => "Síminn",
    "9052" => "Síminn",
    "9047" => "Fjarskipti/ Vodafone ehf.",
    "9046" => "Fjarskipti/ Vodafone ehf.",
    "9045" => "Síminn",
    "9044" => "Síminn",
    "9042" => "Síminn",
    "9041" => "Síminn",
    "9040" => "Síminn",
    "9039" => "Símafélagið ehf",
    "9037" => "Fjarskipti/ Vodafone ehf.",
    "9036" => "Fjarskipti/ Vodafone ehf.",
    "9035" => "Síminn",
    "9033" => "Síminn",
    "9031" => "Síminn",
    "9029" => "Símafélagið ehf",
    "9027" => "Fjarskipti/ Vodafone ehf.",
    "9026" => "Fjarskipti/ Vodafone ehf.",
    "9025" => "Síminn",
    "9021" => "Síminn",
    "9020" => "Síminn",
    "9019" => "Símafélagið ehf",
    "9017" => "Fjarskipti/ Vodafone ehf.",
    "9016" => "Fjarskipti/ Vodafone ehf.",
    "9015" => "Síminn",
    "9009" => "Fjarskipti/ Vodafone ehf.",
    "900" => "Síminn"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "354", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Iceland", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for payphone network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "909"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "354", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Iceland", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PAYPHONE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "499" => "Síminn",
    "497" => "Símafélagið ehf",
    "496" => "365 miðlar hf",
    "495" => "Hringiðan",
    "494" => "Sími og net fjarskipti ehf.",
    "493" => "",
    "492" => "Hringdu ehf",
    "491" => "365 miðlar hf",
    "490" => "Fjarskipti/ Vodafone ehf."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "354", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Iceland", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Toll Free network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "808" => "Síminn",
    "807" => "Síminn",
    "806" => "Síminn",
    "805" => "Síminn",
    "804" => "Síminn",
    "803" => "Síminn",
    "802" => "Síminn",
    "801" => "Síminn"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "354", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Iceland", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TOLL_FREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for TETRA network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "641" => "Öryggisfjarskipti ehf.",
    "640" => "Öryggisfjarskipti ehf.",
    "639" => "Öryggisfjarskipti ehf.",
    "638" => "Öryggisfjarskipti ehf.",
    "637" => "Öryggisfjarskipti ehf."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "354", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Iceland", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TETRA, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for FAX network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "872" => "Síminn"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "354", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Iceland", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_FAX, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Voice Mail network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "958" => "Síminn",
    "954" => "Síminn",
    "880" => "Síminn",
    "879" => "Síminn",
    "878" => "Síminn",
    "871" => "Síminn",
    "870" => "Síminn",
    "689" => "Fjarskipti/ Vodafone ehf."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "354", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Iceland", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VOICEMAIL, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+354 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Icelandian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
