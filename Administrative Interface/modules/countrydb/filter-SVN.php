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
 * related to country database of Slovenia.
 *
 * Reference: https://www.itu.int/oth/T02020000BE/en (2018-09-10)
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
 * E.164 Slovenia country hook
 */
framework_add_filter ( "e164_identify_country_SVN", "e164_identify_country_SVN");

/**
 * E.164 Slovenian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "SVN" (code for Slovenia). This
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
function e164_identify_country_SVN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Slovenia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+386")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Slovenia has 12 to 14 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 12 || strlen ( $parameters["Number"]) > 14)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "6919" => "",
    "6918" => "",
    "6917" => "",
    "6916" => "",
    "6915" => "",
    "6914" => "",
    "6913" => "",
    "6912" => "",
    "6911" => "",
    "6910" => "Compatel Ltd.",
    "6579" => "",
    "6578" => "",
    "6577" => "",
    "6576" => "",
    "6575" => "",
    "6574" => "",
    "6573" => "",
    "6572" => "",
    "6571" => "",
    "6570" => "NOVATEL d.o.o.",
    "6569" => "",
    "6568" => "",
    "6567" => "",
    "6566" => "",
    "6565" => "",
    "6564" => "",
    "6563" => "",
    "6562" => "",
    "6561" => "",
    "6560" => "Softnet d.o.o.",
    "6559" => "",
    "6558" => "",
    "6557" => "",
    "6556" => "",
    "6555" => "Mega M d.o.o.",
    "6554" => "",
    "6553" => "",
    "6552" => "",
    "6551" => "",
    "6550" => "",
    "699" => "HOT mobil d.o.o.",
    "698" => "",
    "697" => "",
    "696" => "HOT mobil d.o.o.",
    "695" => "",
    "694" => "",
    "693" => "",
    "692" => "",
    "690" => "",
    "659" => "",
    "658" => "",
    "654" => "",
    "653" => "",
    "652" => "",
    "651" => "SŽ - Infrastruktura, d.o.o.",
    "650" => "",
    "71" => "",
    "70" => "Telemach d.o.o.",
    "68" => "A1 Slovenija d.d.",
    "67" => "",
    "66" => "",
    "64" => "T-2 d.o.o.",
    "63" => "",
    "62" => "",
    "61" => "",
    "60" => "",
    "51" => "Telekom Slovenije d.d.",
    "41" => "Telekom Slovenije d.d.",
    "40" => "A1 Slovenija d.d.",
    "31" => "Telekom Slovenije d.d.",
    "30" => "A1 Slovenija d.d."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "386", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Slovenia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "(0)" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+386 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "78" => "Novo mesto, Krško",
    "77" => "Novo mesto, Krško",
    "76" => "Novo mesto, Krško",
    "75" => "Novo mesto, Krško",
    "74" => "Novo mesto, Krško",
    "73" => "Novo mesto, Krško",
    "72" => "Novo mesto, Krško",
    "58" => "Nova Gorica, Koper, Postojna",
    "57" => "Nova Gorica, Koper, Postojna",
    "56" => "Nova Gorica, Koper, Postojna",
    "55" => "Nova Gorica, Koper, Postojna",
    "54" => "Nova Gorica, Koper, Postojna",
    "53" => "Nova Gorica, Koper, Postojna",
    "52" => "Nova Gorica, Koper, Postojna",
    "48" => "Kranj",
    "47" => "Kranj",
    "46" => "Kranj",
    "45" => "Kranj",
    "44" => "Kranj",
    "43" => "Kranj",
    "42" => "Kranj",
    "38" => "Celje, Trbovlje",
    "37" => "Celje, Trbovlje",
    "36" => "Celje, Trbovlje",
    "35" => "Celje, Trbovlje",
    "34" => "Celje, Trbovlje",
    "33" => "Celje, Trbovlje",
    "32" => "Celje, Trbovlje",
    "28" => "Maribor, Ravne na Koroškem, Murska Sobota",
    "27" => "Maribor, Ravne na Koroškem, Murska Sobota",
    "26" => "Maribor, Ravne na Koroškem, Murska Sobota",
    "25" => "Maribor, Ravne na Koroškem, Murska Sobota",
    "24" => "Maribor, Ravne na Koroškem, Murska Sobota",
    "23" => "Maribor, Ravne na Koroškem, Murska Sobota",
    "22" => "Maribor, Ravne na Koroškem, Murska Sobota",
    "18" => "Ljubljana",
    "17" => "Ljubljana",
    "16" => "Ljubljana",
    "15" => "Ljubljana",
    "14" => "Ljubljana",
    "13" => "Ljubljana",
    "12" => "Ljubljana"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "386", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Slovenia", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(0)" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+386 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "8289" => "SHCG SK S.R.O.",
    "8288" => "Voxbone S.A.",
    "8287" => "",
    "8286" => "",
    "8285" => "",
    "8284" => "SHCG SK S.R.O.",
    "8283" => "",
    "8282" => "NOVATEL d.o.o.",
    "8281" => "NOVATEL d.o.o.",
    "8280" => "NOVATEL d.o.o.",
    "8229" => "",
    "8228" => "Alstar d.o.o.",
    "8227" => "Eurotel d.o.o.",
    "8226" => "",
    "8225" => "",
    "8224" => "",
    "8223" => "SHCG SK S.R.O.",
    "8222" => "",
    "8221" => "",
    "8220" => "Akton d.o.o.",
    "8189" => "SIA Netbalt",
    "8188" => "Triksera d.o.o.",
    "8187" => "",
    "8186" => "",
    "8185" => "",
    "8184" => "",
    "8183" => "SHCG SK S.R.O.",
    "8182" => "",
    "8181" => "Mega M d.o.o.",
    "8180" => "Compatel Ltd.",
    "839" => "Telemach d.o.o.",
    "838" => "Telemach d.o.o.",
    "837" => "",
    "836" => "",
    "835" => "",
    "834" => "",
    "833" => "A1 Slovenija d.d.",
    "832" => "",
    "830" => "",
    "829" => "",
    "827" => "",
    "826" => "",
    "825" => "",
    "824" => "",
    "823" => "",
    "821" => "",
    "820" => "Telekom Slovenije d.d.",
    "819" => "",
    "817" => "Telekom Slovenije d.d.",
    "816" => "Softnet d.o.o.",
    "815" => "",
    "814" => "",
    "813" => "",
    "812" => "",
    "811" => "",
    "810" => "",
    "599" => "Telekom Slovenije d.d.",
    "598" => "Telekom Slovenije d.d.",
    "597" => "Telemach d.o.o.",
    "596" => "A2 Slovenija d.d.",
    "595" => "T-2 d.o.o.",
    "594" => "T-2 d.o.o.",
    "593" => "T-2 d.o.o.",
    "592" => "Detel Global d.o.o.",
    "591" => "Telemach d.o.o.",
    "590" => "T-2 d.o.o.",
    "49" => "Telekom Slovenije d.d."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "386", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Slovenia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+386 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for PRN network with 2 digits NDC and 4 to 6 digits SN
   */
  if ( strlen ( $parameters["Number"]) >= 12 && strlen ( $parameters["Number"]) <= 14 && substr ( $parameters["Number"], 4, 2) == "90")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "386", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Slovenia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "(0)" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+386 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Slovenian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
