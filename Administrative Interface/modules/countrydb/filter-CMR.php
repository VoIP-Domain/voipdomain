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
 * related to country database of Cameroon.
 *
 * Reference: https://www.itu.int/oth/T0202000024/en (2014-11-24)
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
 * E.164 Cameroon country hook
 */
framework_add_filter ( "e164_identify_country_CMR", "e164_identify_country_CMR");

/**
 * E.164 Cameroon area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "CMR" (code for Cameroon). This
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
function e164_identify_country_CMR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Cameroon
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+237")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Cameroon has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "659" => "Orange Cameroun",
    "658" => "Orange Cameroun",
    "657" => "Orange Cameroun",
    "656" => "Orange Cameroun",
    "655" => "Orange Cameroun",
    "654" => "MTN Cameroon",
    "653" => "MTN Cameroon",
    "652" => "MTN Cameroon",
    "651" => "MTN Cameroon",
    "650" => "MTN Cameroon",
    "69" => "Orange Cameroun",
    "67" => "MTN Cameroon",
    "66" => "Nexttel"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "237", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Cameroon", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+237 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line or FMC network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "2334973" => array ( "area" => "Coast", "city" => "Mbanga", "operator" => "Camtel"),
    "2334972" => array ( "area" => "Coast", "city" => "Mbanga", "operator" => "Camtel"),
    "2334971" => array ( "area" => "Coast", "city" => "Loum", "operator" => "Camtel"),
    "2334970" => array ( "area" => "Coast", "city" => "Loum", "operator" => "Camtel"),
    "2334521" => array ( "area" => "West", "city" => "Dschang", "operator" => "Camtel"),
    "2334520" => array ( "area" => "West", "city" => "Dschang", "operator" => "Camtel"),
    "2333664" => array ( "area" => "North West", "city" => "Mbambili", "operator" => "Camtel"),
    "2333663" => array ( "area" => "North West", "city" => "Mbambili", "operator" => "Camtel"),
    "2333662" => array ( "area" => "North West", "city" => "Mbambili", "operator" => "Camtel"),
    "2333661" => array ( "area" => "North West", "city" => "Mbambili", "operator" => "Camtel"),
    "2333660" => array ( "area" => "North West", "city" => "Mbambili", "operator" => "Camtel"),
    "2333554" => array ( "area" => "South West", "city" => "Kumba", "operator" => "Camtel"),
    "2333553" => array ( "area" => "South West", "city" => "Kumba", "operator" => "Camtel"),
    "2333552" => array ( "area" => "South West", "city" => "Kumba", "operator" => "Camtel"),
    "2333551" => array ( "area" => "South West", "city" => "Kumba", "operator" => "Camtel"),
    "2333550" => array ( "area" => "South West", "city" => "Kumba", "operator" => "Camtel"),
    "2333414" => array ( "area" => "South West", "city" => "Manfé", "operator" => "Camtel"),
    "2333413" => array ( "area" => "South West", "city" => "Manfé", "operator" => "Camtel"),
    "2333412" => array ( "area" => "South West", "city" => "Manfé", "operator" => "Camtel"),
    "2333411" => array ( "area" => "South West", "city" => "Manfé", "operator" => "Camtel"),
    "2333410" => array ( "area" => "South West", "city" => "Manfé", "operator" => "Camtel"),
    "2333317" => array ( "area" => "South West", "city" => "Tiko", "operator" => "Camtel"),
    "2333316" => array ( "area" => "South West", "city" => "Tiko", "operator" => "Camtel"),
    "2333315" => array ( "area" => "South West", "city" => "Tiko", "operator" => "Camtel"),
    "2333314" => array ( "area" => "South West", "city" => "Tiko", "operator" => "Camtel"),
    "2333313" => array ( "area" => "South West", "city" => "Tiko", "operator" => "Camtel"),
    "2333312" => array ( "area" => "South West", "city" => "Tiko", "operator" => "Camtel"),
    "2333311" => array ( "area" => "South West", "city" => "Tiko", "operator" => "Camtel"),
    "2333310" => array ( "area" => "South West", "city" => "Tiko", "operator" => "Camtel"),
    "2333213" => array ( "area" => "South West", "city" => "Muyuka", "operator" => "Camtel"),
    "2333212" => array ( "area" => "South West", "city" => "Muyuka", "operator" => "Camtel"),
    "2333211" => array ( "area" => "South West", "city" => "Muyuka", "operator" => "Camtel"),
    "2333210" => array ( "area" => "South West", "city" => "Muyuka", "operator" => "Camtel"),
    "2333057" => array ( "area" => "West", "city" => "Mbouda", "operator" => "Camtel"),
    "2333056" => array ( "area" => "West", "city" => "Mbouda", "operator" => "Camtel"),
    "2333055" => array ( "area" => "West", "city" => "Mbouda", "operator" => "Camtel"),
    "2333054" => array ( "area" => "West", "city" => "Mbouda", "operator" => "Camtel"),
    "2333053" => array ( "area" => "West", "city" => "Mbouda", "operator" => "Camtel"),
    "2333052" => array ( "area" => "West", "city" => "Mbouda", "operator" => "Camtel"),
    "2333051" => array ( "area" => "West", "city" => "Mbouda", "operator" => "Camtel"),
    "2333050" => array ( "area" => "West", "city" => "Mbouda", "operator" => "Camtel"),
    "2332971" => array ( "area" => "West", "city" => "Bafang", "operator" => "Camtel"),
    "2332970" => array ( "area" => "West", "city" => "Bafang", "operator" => "Camtel"),
    "2332773" => array ( "area" => "West", "city" => "Bandjoun", "operator" => "Camtel"),
    "2332772" => array ( "area" => "West", "city" => "Bandjoun", "operator" => "Camtel"),
    "2332771" => array ( "area" => "West", "city" => "Bandjoun", "operator" => "Camtel"),
    "2332770" => array ( "area" => "West", "city" => "Bandjoun", "operator" => "Camtel"),
    "2332679" => array ( "area" => "West", "city" => "Foumbot", "operator" => "Camtel"),
    "2332678" => array ( "area" => "West", "city" => "Foumbot", "operator" => "Camtel"),
    "2332677" => array ( "area" => "West", "city" => "Foumbot", "operator" => "Camtel"),
    "2332676" => array ( "area" => "West", "city" => "Foumbot", "operator" => "Camtel"),
    "2332675" => array ( "area" => "West", "city" => "Foumbot", "operator" => "Camtel"),
    "2332674" => array ( "area" => "West", "city" => "Foumbot", "operator" => "Camtel"),
    "2332631" => array ( "area" => "West", "city" => "Foumban", "operator" => "Camtel"),
    "2332630" => array ( "area" => "West", "city" => "Foumban", "operator" => "Camtel"),
    "2332217" => array ( "area" => "North West", "city" => "Kumbo", "operator" => "Camtel"),
    "2332216" => array ( "area" => "North West", "city" => "Kumbo", "operator" => "Camtel"),
    "2332215" => array ( "area" => "North West", "city" => "Kumbo", "operator" => "Camtel"),
    "2332214" => array ( "area" => "North West", "city" => "Kumbo", "operator" => "Camtel"),
    "2332213" => array ( "area" => "North West", "city" => "Kumbo", "operator" => "Camtel"),
    "2332212" => array ( "area" => "North West", "city" => "Kumbo", "operator" => "Camtel"),
    "2332211" => array ( "area" => "North West", "city" => "Kumbo", "operator" => "Camtel"),
    "2332210" => array ( "area" => "North West", "city" => "Kumbo", "operator" => "Camtel"),
    "2332159" => array ( "area" => "North West", "city" => "Nkambe", "operator" => "Camtel"),
    "2332158" => array ( "area" => "North West", "city" => "Nkambe", "operator" => "Camtel"),
    "2332157" => array ( "area" => "North West", "city" => "Nkambe", "operator" => "Camtel"),
    "2332054" => array ( "area" => "North West", "city" => "Wum", "operator" => "Camtel"),
    "2332053" => array ( "area" => "North West", "city" => "Wum", "operator" => "Camtel"),
    "2332052" => array ( "area" => "North West", "city" => "Wum", "operator" => "Camtel"),
    "2332051" => array ( "area" => "North West", "city" => "Wum", "operator" => "Camtel"),
    "2332050" => array ( "area" => "North West", "city" => "Wum", "operator" => "Camtel"),
    "2224824" => array ( "area" => "South", "city" => "Ambam", "operator" => "Camtel"),
    "2224823" => array ( "area" => "South", "city" => "Ambam", "operator" => "Camtel"),
    "2224822" => array ( "area" => "South", "city" => "Kye-Ossie", "operator" => "Camtel"),
    "2224821" => array ( "area" => "South", "city" => "Kye-Ossie", "operator" => "Camtel"),
    "2224820" => array ( "area" => "South", "city" => "Kye-Ossie", "operator" => "Camtel"),
    "2224796" => array ( "area" => "South", "city" => "Efoulan", "operator" => "Camtel"),
    "2224795" => array ( "area" => "South", "city" => "Efoulan", "operator" => "Camtel"),
    "2224794" => array ( "area" => "South", "city" => "Efoulan", "operator" => "Camtel"),
    "2224793" => array ( "area" => "South", "city" => "Efoulan", "operator" => "Camtel"),
    "2224792" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224791" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224790" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224789" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224788" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224787" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224786" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224785" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224784" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224783" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224782" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224781" => array ( "area" => "South", "city" => "Sangmelima Meyomessala", "operator" => "Camtel"),
    "2224474" => array ( "area" => "Far North", "city" => "Mora", "operator" => "Camtel"),
    "2224473" => array ( "area" => "Far North", "city" => "Mora", "operator" => "Camtel"),
    "2224472" => array ( "area" => "Far North", "city" => "Mora", "operator" => "Camtel"),
    "2224471" => array ( "area" => "Far North", "city" => "Mora", "operator" => "Camtel"),
    "2224470" => array ( "area" => "Far North", "city" => "Mora", "operator" => "Camtel"),
    "2223971" => array ( "area" => "North", "city" => "Figuil", "operator" => "Camtel"),
    "2223970" => array ( "area" => "North", "city" => "Figuil", "operator" => "Camtel"),
    "2223717" => array ( "area" => "Adamaoua", "city" => "Meiganga", "operator" => "Camtel"),
    "2223716" => array ( "area" => "Adamaoua", "city" => "Meiganga", "operator" => "Camtel"),
    "2223715" => array ( "area" => "Adamaoua", "city" => "Meiganga", "operator" => "Camtel"),
    "2223714" => array ( "area" => "Adamaoua", "city" => "Meiganga", "operator" => "Camtel"),
    "2223713" => array ( "area" => "Adamaoua", "city" => "Meiganga", "operator" => "Camtel"),
    "2223712" => array ( "area" => "Adamaoua", "city" => "Meiganga", "operator" => "Camtel"),
    "2223711" => array ( "area" => "Adamaoua", "city" => "Meiganga", "operator" => "Camtel"),
    "2223710" => array ( "area" => "Adamaoua", "city" => "Meiganga", "operator" => "Camtel"),
    "2223549" => array ( "area" => "Adamaoua", "city" => "Galim Tignère", "operator" => "Camtel"),
    "2223548" => array ( "area" => "Adamaoua", "city" => "Galim Tignère", "operator" => "Camtel"),
    "2223547" => array ( "area" => "Adamaoua", "city" => "Galim Tignère", "operator" => "Camtel"),
    "2223546" => array ( "area" => "Adamaoua", "city" => "Galim Tignère", "operator" => "Camtel"),
    "2223545" => array ( "area" => "Adamaoua", "city" => "Galim Tignère", "operator" => "Camtel"),
    "2223350" => array ( "area" => "East", "city" => "Abong-Bang", "operator" => "Camtel"),
    "2223224" => array ( "area" => "Centre", "city" => "Soa", "operator" => "Camtel"),
    "2223223" => array ( "area" => "Centre", "city" => "Soa", "operator" => "Camtel"),
    "2223222" => array ( "area" => "Centre", "city" => "Soa", "operator" => "Camtel"),
    "2223221" => array ( "area" => "Centre", "city" => "Soa", "operator" => "Camtel"),
    "2223220" => array ( "area" => "Centre", "city" => "Soa", "operator" => "Camtel"),
    "2223215" => array ( "area" => "Centre", "city" => "Mfou", "operator" => "Camtel"),
    "2223214" => array ( "area" => "Centre", "city" => "Mfou", "operator" => "Camtel"),
    "2223213" => array ( "area" => "Centre", "city" => "Mfou", "operator" => "Camtel"),
    "2223212" => array ( "area" => "Centre", "city" => "Mfou", "operator" => "Camtel"),
    "2223211" => array ( "area" => "Centre", "city" => "Mfou", "operator" => "Camtel"),
    "2223210" => array ( "area" => "Centre", "city" => "Mfou", "operator" => "Camtel"),
    "2222826" => array ( "area" => "South", "city" => "Mengong", "operator" => "Camtel"),
    "2222625" => array ( "area" => "East", "city" => "Batouri", "operator" => "Camtel"),
    "2222624" => array ( "area" => "East", "city" => "Batouri", "operator" => "Camtel"),
    "2222623" => array ( "area" => "East", "city" => "Batouri", "operator" => "Camtel"),
    "2222622" => array ( "area" => "East", "city" => "Batouri", "operator" => "Camtel"),
    "2222621" => array ( "area" => "East", "city" => "Batouri", "operator" => "Camtel"),
    "2222620" => array ( "area" => "East", "city" => "Batouri", "operator" => "Camtel"),
    "2222569" => array ( "area" => "Adamaoua", "city" => "Mbé", "operator" => "Camtel"),
    "2222568" => array ( "area" => "Adamaoua", "city" => "Mbé", "operator" => "Camtel"),
    "2222567" => array ( "area" => "Adamaoua", "city" => "Mbé", "operator" => "Camtel"),
    "2222566" => array ( "area" => "Adamaoua", "city" => "Mbé", "operator" => "Camtel"),
    "2222565" => array ( "area" => "Adamaoua", "city" => "Mbé", "operator" => "Camtel"),
    "2222564" => array ( "area" => "Adamaoua", "city" => "Beelel", "operator" => "Camtel"),
    "2222563" => array ( "area" => "Adamaoua", "city" => "Beelel", "operator" => "Camtel"),
    "2222562" => array ( "area" => "Adamaoua", "city" => "Beelel", "operator" => "Camtel"),
    "2222561" => array ( "area" => "Adamaoua", "city" => "Beelel", "operator" => "Camtel"),
    "2222560" => array ( "area" => "Adamaoua", "city" => "Beelel", "operator" => "Camtel"),
    "2222543" => array ( "area" => "Adamaoua", "city" => "Dang", "operator" => "Camtel"),
    "2222542" => array ( "area" => "Adamaoua", "city" => "Dang", "operator" => "Camtel"),
    "2222541" => array ( "area" => "Adamaoua", "city" => "Dang", "operator" => "Camtel"),
    "2222540" => array ( "area" => "Adamaoua", "city" => "Dang", "operator" => "Camtel"),
    "2221952" => array ( "area" => "Centre", "city" => "Nanga Eboko", "operator" => "Camtel"),
    "2221951" => array ( "area" => "Centre", "city" => "Nanga Eboko", "operator" => "Camtel"),
    "2221950" => array ( "area" => "Centre", "city" => "Nanga Eboko", "operator" => "Camtel"),
    "2221826" => array ( "area" => "Centre", "city" => "Monatélé", "operator" => "Camtel"),
    "2221825" => array ( "area" => "Centre", "city" => "Monatélé", "operator" => "Camtel"),
    "2221804" => array ( "area" => "Centre", "city" => "Obala", "operator" => "Camtel"),
    "2221803" => array ( "area" => "Centre", "city" => "Obala", "operator" => "Camtel"),
    "2221802" => array ( "area" => "Centre", "city" => "Obala", "operator" => "Camtel"),
    "2221801" => array ( "area" => "Centre", "city" => "Obala", "operator" => "Camtel"),
    "2221800" => array ( "area" => "Centre", "city" => "Obala", "operator" => "Camtel"),
    "2221444" => array ( "area" => "Centre", "city" => "Ngoumou", "operator" => "Camtel"),
    "2221443" => array ( "area" => "Centre", "city" => "Ngoumou", "operator" => "Camtel"),
    "2221442" => array ( "area" => "Centre", "city" => "Ngoumou", "operator" => "Camtel"),
    "2221441" => array ( "area" => "Centre", "city" => "Ngoumou", "operator" => "Camtel"),
    "2221440" => array ( "area" => "Centre", "city" => "Ngoumou", "operator" => "Camtel"),
    "2221366" => array ( "area" => "Centre", "city" => "Mboumnyebel", "operator" => "Camtel"),
    "2221362" => array ( "area" => "Centre", "city" => "Eséka", "operator" => "Camtel"),
    "2221361" => array ( "area" => "Centre", "city" => "Eséka", "operator" => "Camtel"),
    "2221360" => array ( "area" => "Centre", "city" => "Eséka", "operator" => "Camtel"),
    "2221219" => array ( "area" => "Centre", "city" => "Ayos", "operator" => "Camtel"),
    "2221218" => array ( "area" => "Centre", "city" => "Ayos", "operator" => "Camtel"),
    "2221217" => array ( "area" => "Centre", "city" => "Ayos", "operator" => "Camtel"),
    "2221216" => array ( "area" => "Centre", "city" => "Ayos", "operator" => "Camtel"),
    "2221209" => array ( "area" => "Centre", "city" => "Akonolinga", "operator" => "Camtel"),
    "2221208" => array ( "area" => "Centre", "city" => "Akonolinga", "operator" => "Camtel"),
    "2221207" => array ( "area" => "Centre", "city" => "Akonolinga", "operator" => "Camtel"),
    "2221206" => array ( "area" => "Centre", "city" => "Akonolinga", "operator" => "Camtel"),
    "2221205" => array ( "area" => "Centre", "city" => "Akonolinga", "operator" => "Camtel"),
    "2221117" => array ( "area" => "Centre", "city" => "Mbalmayo", "operator" => "Camtel"),
    "2221116" => array ( "area" => "Centre", "city" => "Mbalmayo", "operator" => "Camtel"),
    "2221115" => array ( "area" => "Centre", "city" => "Mbalmayo", "operator" => "Camtel"),
    "2221114" => array ( "area" => "Centre", "city" => "Mbalmayo", "operator" => "Camtel"),
    "2221113" => array ( "area" => "Centre", "city" => "Mbalmayo", "operator" => "Camtel"),
    "2221112" => array ( "area" => "Centre", "city" => "Mbalmayo", "operator" => "Camtel"),
    "2221111" => array ( "area" => "Centre", "city" => "Mbalmayo", "operator" => "Camtel"),
    "2221110" => array ( "area" => "Centre", "city" => "Mbalmayo", "operator" => "Camtel"),
    "233504" => array ( "area" => "", "city" => "", "operator" => "Camtel"),
    "233503" => array ( "area" => "", "city" => "", "operator" => "Camtel"),
    "233502" => array ( "area" => "", "city" => "", "operator" => "Camtel"),
    "233501" => array ( "area" => "", "city" => "", "operator" => "Camtel"),
    "233500" => array ( "area" => "", "city" => "", "operator" => "Camtel"),
    "233496" => array ( "area" => "Coast", "city" => "Nkongsamba", "operator" => "Camtel"),
    "233495" => array ( "area" => "Coast", "city" => "Nkongsamba", "operator" => "Camtel"),
    "233494" => array ( "area" => "Coast", "city" => "Nkongsamba", "operator" => "Camtel"),
    "233493" => array ( "area" => "Coast", "city" => "Nkongsamba", "operator" => "Camtel"),
    "233492" => array ( "area" => "Coast", "city" => "Nkongsamba", "operator" => "Camtel"),
    "233491" => array ( "area" => "Coast", "city" => "Nkongsamba", "operator" => "Camtel"),
    "233490" => array ( "area" => "Coast", "city" => "Nkongsamba", "operator" => "Camtel"),
    "233489" => array ( "area" => "West", "city" => "Bangangté 2", "operator" => "Camtel"),
    "233484" => array ( "area" => "West", "city" => "Bangangté 1", "operator" => "Camtel"),
    "233464" => array ( "area" => "Coast", "city" => "Edéa", "operator" => "Camtel"),
    "233451" => array ( "area" => "West", "city" => "Dschang", "operator" => "Camtel"),
    "233446" => array ( "area" => "West", "city" => "Bafoussam", "operator" => "Camtel"),
    "233445" => array ( "area" => "West", "city" => "Bafoussam", "operator" => "Camtel"),
    "233444" => array ( "area" => "West", "city" => "Bafoussam", "operator" => "Camtel"),
    "233443" => array ( "area" => "West", "city" => "Bafoussam", "operator" => "Camtel"),
    "233442" => array ( "area" => "West", "city" => "Bafoussam", "operator" => "Camtel"),
    "233441" => array ( "area" => "West", "city" => "Bafoussam", "operator" => "Camtel"),
    "233364" => array ( "area" => "North West", "city" => "Bamenda", "operator" => "Camtel"),
    "233363" => array ( "area" => "North West", "city" => "Bamenda", "operator" => "Camtel"),
    "233362" => array ( "area" => "North West", "city" => "Bamenda", "operator" => "Camtel"),
    "233361" => array ( "area" => "North West", "city" => "Bamenda", "operator" => "Camtel"),
    "233360" => array ( "area" => "North West", "city" => "Bamenda", "operator" => "Camtel"),
    "233354" => array ( "area" => "South West", "city" => "Kumba", "operator" => "Camtel"),
    "233339" => array ( "area" => "South West", "city" => "Limbé", "operator" => "Camtel"),
    "233338" => array ( "area" => "South West", "city" => "Limbé", "operator" => "Camtel"),
    "233337" => array ( "area" => "South West", "city" => "Limbé", "operator" => "Camtel"),
    "233336" => array ( "area" => "South West", "city" => "Limbé", "operator" => "Camtel"),
    "233335" => array ( "area" => "South West", "city" => "Limbé", "operator" => "Camtel"),
    "233334" => array ( "area" => "South West", "city" => "Limbé", "operator" => "Camtel"),
    "233333" => array ( "area" => "South West", "city" => "Limbé", "operator" => "Camtel"),
    "233332" => array ( "area" => "South West", "city" => "Limbé", "operator" => "Camtel"),
    "233329" => array ( "area" => "South West", "city" => "Buéa", "operator" => "Camtel"),
    "233328" => array ( "area" => "South West", "city" => "Buéa", "operator" => "Camtel"),
    "233327" => array ( "area" => "South West", "city" => "Buéa", "operator" => "Camtel"),
    "233326" => array ( "area" => "South West", "city" => "Buéa", "operator" => "Camtel"),
    "233325" => array ( "area" => "South West", "city" => "Buéa", "operator" => "Camtel"),
    "233324" => array ( "area" => "South West", "city" => "Buéa", "operator" => "Camtel"),
    "233323" => array ( "area" => "South West", "city" => "Buéa", "operator" => "Camtel"),
    "233322" => array ( "area" => "South West", "city" => "Buéa", "operator" => "Camtel"),
    "233313" => array ( "area" => "Coast", "city" => "Yabassi", "operator" => "Camtel"),
    "233296" => array ( "area" => "West", "city" => "Bafang", "operator" => "Camtel"),
    "233262" => array ( "area" => "West", "city" => "Foumban", "operator" => "Camtel"),
    "222464" => array ( "area" => "South", "city" => "Lolodorf", "operator" => "Camtel"),
    "222463" => array ( "area" => "South", "city" => "Lolodorf", "operator" => "Camtel"),
    "222462" => array ( "area" => "South", "city" => "Kribi", "operator" => "Camtel"),
    "222461" => array ( "area" => "South", "city" => "Kribi", "operator" => "Camtel"),
    "222455" => array ( "area" => "Far North", "city" => "Mokolo", "operator" => "Camtel"),
    "222426" => array ( "area" => "Far North", "city" => "Yagoua", "operator" => "Camtel"),
    "222414" => array ( "area" => "Far North", "city" => "Kousseri", "operator" => "Camtel"),
    "222395" => array ( "area" => "North", "city" => "Guider", "operator" => "Camtel"),
    "222369" => array ( "area" => "Adamaoua", "city" => "Banyo", "operator" => "Camtel"),
    "222355" => array ( "area" => "Adamaoua", "city" => "Tignère", "operator" => "Camtel"),
    "222348" => array ( "area" => "Adamaoua", "city" => "Tibati", "operator" => "Camtel"),
    "222347" => array ( "area" => "Adamaoua", "city" => "N'Gaoundal", "operator" => "Camtel"),
    "222293" => array ( "area" => "Far North", "city" => "Maroua", "operator" => "Camtel"),
    "222292" => array ( "area" => "Far North", "city" => "Maroua", "operator" => "Camtel"),
    "222291" => array ( "area" => "Far North", "city" => "Maroua", "operator" => "Camtel"),
    "222290" => array ( "area" => "Far North", "city" => "Maroua", "operator" => "Camtel"),
    "222284" => array ( "area" => "South", "city" => "Ebolowa", "operator" => "Camtel"),
    "222283" => array ( "area" => "South", "city" => "Ebolowa", "operator" => "Camtel"),
    "222274" => array ( "area" => "North", "city" => "Garoua", "operator" => "Camtel"),
    "222273" => array ( "area" => "North", "city" => "Garoua", "operator" => "Camtel"),
    "222272" => array ( "area" => "North", "city" => "Garoua", "operator" => "Camtel"),
    "222271" => array ( "area" => "North", "city" => "Garoua", "operator" => "Camtel"),
    "222264" => array ( "area" => "East", "city" => "Belabo", "operator" => "Camtel"),
    "222253" => array ( "area" => "Adamaoua", "city" => "N'Gaoundéré", "operator" => "Camtel"),
    "222252" => array ( "area" => "Adamaoua", "city" => "N'Gaoundéré", "operator" => "Camtel"),
    "222251" => array ( "area" => "Adamaoua", "city" => "N'Gaoundéré", "operator" => "Camtel"),
    "222250" => array ( "area" => "Adamaoua", "city" => "N'Gaoundéré", "operator" => "Camtel"),
    "222242" => array ( "area" => "East", "city" => "Bertoua", "operator" => "Camtel"),
    "222241" => array ( "area" => "East", "city" => "Bertoua", "operator" => "Camtel"),
    "222185" => array ( "area" => "Centre", "city" => "Bafia", "operator" => "Camtel"),
    "23399" => array ( "area" => "Centre", "city" => "Gilat", "operator" => "Camtel"),
    "23398" => array ( "area" => "Coast", "city" => "Rascom", "operator" => "Camtel"),
    "23351" => array ( "area" => "", "city" => "", "operator" => "Camtel"),
    "23347" => array ( "area" => "Coast", "city" => "Akwa North", "operator" => "Camtel"),
    "23343" => array ( "area" => "Coast", "city" => "Akwa Centre", "operator" => "Camtel"),
    "23342" => array ( "area" => "Coast", "city" => "Akwa Centre", "operator" => "Camtel"),
    "23341" => array ( "area" => "Coast", "city" => "Bepanda", "operator" => "Camtel"),
    "23340" => array ( "area" => "Coast", "city" => "Bepanda", "operator" => "Camtel"),
    "23339" => array ( "area" => "Coast", "city" => "Bonabéri", "operator" => "Camtel"),
    "23337" => array ( "area" => "Coast", "city" => "Bassa", "operator" => "Camtel"),
    "22299" => array ( "area" => "Centre", "city" => "Gilat", "operator" => "Camtel"),
    "22298" => array ( "area" => "Coast", "city" => "Rascom", "operator" => "Camtel"),
    "22251" => array ( "area" => "", "city" => "", "operator" => "Camtel"),
    "22250" => array ( "area" => "", "city" => "", "operator" => "Camtel"),
    "22231" => array ( "area" => "Centre", "city" => "Biyem Assi", "operator" => "Camtel"),
    "22230" => array ( "area" => "Centre", "city" => "Nkomo", "operator" => "Camtel"),
    "22223" => array ( "area" => "Centre", "city" => "Yaounde", "operator" => "Camtel"),
    "22222" => array ( "area" => "Centre", "city" => "Yaounde", "operator" => "Camtel"),
    "22221" => array ( "area" => "Centre", "city" => "Jamot", "operator" => "Camtel"),
    "22220" => array ( "area" => "Centre", "city" => "Jamot", "operator" => "Camtel")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      switch ( substr ( $parameters["Number"], 4, 3))
      {
        case "222":
        case "233":
          $type = VD_PHONETYPE_LANDLINE;
          break;
        case "242":
        case "243":
          $type = VD_PHONETYPE_FMC;
          break;
        default:
          $type = VD_PHONETYPE_LANDLINE;
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "237", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Cameroon", "Area" => $data["area"], "City" => $data["city"], "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 7), "Type" => $type, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+237 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Cameroon phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
