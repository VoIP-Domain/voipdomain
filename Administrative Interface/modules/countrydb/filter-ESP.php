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
 * related to country database of Spain.
 *
 * Reference: https://www.itu.int/oth/T02020000C2/en (2019-06-04)
 *            https://avancedigital.mineco.gob.es/es-es/Servicios/Numeracion/Paginas/Plan.aspx (2023-01-04)
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
 * E.164 Spain country hook
 */
framework_add_filter ( "e164_identify_country_ESP", "e164_identify_country_ESP");

/**
 * E.164 Spain area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "ESP" (code for Spain). This
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
function e164_identify_country_ESP ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Spain
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+34")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Spain has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "74",
    "73",
    "72",
    "71",
    "6"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "34", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Spain", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+34 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "988" => "Orense",
    "987" => "León",
    "986" => "Pontevedra",
    "985" => "Asturias",
    "984" => "Asturias",
    "983" => "Valladolid",
    "982" => "Lugo",
    "981" => "A Coruña",
    "980" => "Zamora",
    "979" => "Palencia",
    "978" => "Teruel",
    "977" => "Tarragona",
    "976" => "Zaragoza",
    "975" => "Soria",
    "974" => "Huesca",
    "973" => "Lérida/Lleida",
    "972" => "Gerona/Girona",
    "971" => "Baleares",
    "969" => "Cuenca",
    "968" => "Murcia",
    "967" => "Albacete",
    "966" => "Alicante/Alacant",
    "965" => "Alicante/Alacant",
    "964" => "Castellón",
    "963" => "Valencia",
    "962" => "Valencia",
    "961" => "Valencia",
    "960" => "Valencia",
    "959" => "Huelva",
    "958" => "Granada",
    "957" => "Córdoba",
    "956" => "Cádiz",
    "955" => "Sevilla",
    "954" => "Sevilla",
    "953" => "Jaén",
    "952" => "Málaga",
    "951" => "Málaga",
    "950" => "Almería",
    "949" => "Guadalajara",
    "948" => "Navarra",
    "947" => "Burgos",
    "946" => "Vizcaya/Bizkaia",
    "945" => "Álava/Araba",
    "944" => "Vizcaya/Bizkaia",
    "943" => "Guipúzcoa/Gipuzkoa",
    "942" => "Cantabria",
    "941" => "La Rioja",
    "938" => "Barcelona",
    "937" => "Barcelona",
    "936" => "Barcelona",
    "935" => "Barcelona",
    "934" => "Barcelona",
    "933" => "Barcelona",
    "932" => "Barcelona",
    "931" => "Barcelona",
    "930" => "Barcelona",
    "928" => "Las Palmas",
    "927" => "Cáceres",
    "926" => "Ciudad Real",
    "925" => "Toledo",
    "924" => "Badajoz",
    "923" => "Salamanca",
    "922" => "Tenerife",
    "921" => "Segovia",
    "920" => "Ávila",
    "888" => "Orense",
    "887" => "León",
    "886" => "Pontevedra",
    "884" => "Asturias",
    "883" => "Valladolid",
    "882" => "Lugo",
    "881" => "A Coruña",
    "880" => "Zamora",
    "879" => "Palencia",
    "878" => "Teruel",
    "877" => "Tarragona",
    "876" => "Zaragoza",
    "875" => "Soria",
    "874" => "Huesca",
    "873" => "Lérida, Lleida",
    "872" => "Gerona, Girona",
    "871" => "Baleares",
    "869" => "Cuenca",
    "868" => "Murcia",
    "867" => "Albacete",
    "865" => "Alicante/Alacant",
    "864" => "Castellón",
    "860" => "Valencia",
    "859" => "Huelva",
    "858" => "Granada",
    "857" => "Córdoba",
    "856" => "Cádiz",
    "854" => "Sevilla",
    "853" => "Jaén",
    "851" => "Málaga",
    "850" => "Almería",
    "849" => "Guadalajara",
    "848" => "Navarra",
    "847" => "Burgos",
    "846" => "Vizcaya/Bizkaia",
    "845" => "Álava/Araba",
    "843" => "Guipúzcoa/Gipuzkoa",
    "842" => "Cantabria",
    "841" => "La Rioja",
    "830" => "Barcelona",
    "828" => "Las Palmas",
    "827" => "Cáceres",
    "826" => "Ciudad Real",
    "825" => "Toledo",
    "824" => "Badajoz",
    "823" => "Salamanca",
    "822" => "Tenerife",
    "821" => "Segovia",
    "820" => "Ávila",
    "815" => "Madrid",
    "810" => "Madrid",
    "91" => "Madrid"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "34", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Spain", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+34 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "800" || substr ( $parameters["Number"], 3, 3) == "900")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "34", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Spain", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6), "International" => "+34 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6))));
  }

  /**
   * Check for PRN network with 3 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "803" || substr ( $parameters["Number"], 3, 3) == "806" || substr ( $parameters["Number"], 3, 3) == "807")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "34", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Spain", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6), "International" => "+34 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6))));
  }

  /**
   * If reached here, number wasn't identified as a valid Spain phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
