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
 * related to country database of Croatia.
 *
 * Reference: https://www.itu.int/oth/T0202000032/en (2016-05-13)
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
 * E.164 Croatia country hook
 */
framework_add_filter ( "e164_identify_country_HRV", "e164_identify_country_HRV");

/**
 * E.164 Croatian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "HRV" (code for Croatia). This
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
function e164_identify_country_HRV ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Croatia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+385")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "9751" => array ( "operator" => "", "maximum" => 9, "minimum" => 9),
    "979" => array ( "operator" => "MultiPlus Mobile", "maximum" => 9, "minimum" => 9),
    "977" => array ( "operator" => "Bonbon", "maximum" => 9, "minimum" => 9),
    "976" => array ( "operator" => "Bonbon", "maximum" => 9, "minimum" => 9),
    "970" => array ( "operator" => "", "maximum" => 9, "minimum" => 9),
    "99" => array ( "operator" => "T-Hrvatski Telekon", "maximum" => 9, "minimum" => 9),
    "98" => array ( "operator" => "T-Hrvatski Telekon", "maximum" => 9, "minimum" => 8),
    "95" => array ( "operator" => "Tele2", "maximum" => 9, "minimum" => 9),
    "92" => array ( "operator" => "Tomato", "maximum" => 9, "minimum" => 9),
    "91" => array ( "operator" => "VIPnet", "maximum" => 9, "minimum" => 9)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= $data["minimum"] + 4 && strlen ( $parameters["Number"]) <= $data["maximum"] + 4)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "385", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Croatia", "Area" => "", "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+385 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1 or 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "53" => array ( "area" => "Istria, Kvarner and Korski Kotar", "city" => "Lika-Senj", "maximum" => 9, "minimum" => 8),
    "52" => array ( "area" => "Istria, Kvarner and Korski Kotar", "city" => "Istra", "maximum" => 9, "minimum" => 8),
    "51" => array ( "area" => "Istria, Kvarner and Korski Kotar", "city" => "Primorsko-goranska", "maximum" => 9, "minimum" => 8),
    "49" => array ( "area" => "Central Croatia", "city" => "Krapina-Zagorje", "maximum" => 9, "minimum" => 8),
    "48" => array ( "area" => "Central Croatia", "city" => "Koprivnica-Križevci", "maximum" => 9, "minimum" => 8),
    "47" => array ( "area" => "Central Croatia", "city" => "Karlovac", "maximum" => 9, "minimum" => 8),
    "44" => array ( "area" => "Central Croatia", "city" => "Sisak-Moslavina", "maximum" => 9, "minimum" => 8),
    "43" => array ( "area" => "Central Croatia", "city" => "Bjelovar-Bilogora", "maximum" => 9, "minimum" => 8),
    "42" => array ( "area" => "Central Croatia", "city" => "Varaždin", "maximum" => 9, "minimum" => 8),
    "40" => array ( "area" => "Central Croatia", "city" => "Međimurje", "maximum" => 9, "minimum" => 8),
    "35" => array ( "area" => "Slavonia", "city" => "Brod-Posavina", "maximum" => 9, "minimum" => 8),
    "34" => array ( "area" => "Slavonia", "city" => "Požega-Slavonia", "maximum" => 9, "minimum" => 8),
    "33" => array ( "area" => "Slavonia", "city" => "Virovitica-Podravina", "maximum" => 9, "minimum" => 8),
    "32" => array ( "area" => "Slavonia", "city" => "Vukovar-Srijem", "maximum" => 9, "minimum" => 8),
    "31" => array ( "area" => "Slavonia", "city" => "Osijek-Baranja", "maximum" => 9, "minimum" => 8),
    "23" => array ( "area" => "Dalmatia", "city" => "Zadar", "maximum" => 9, "minimum" => 8),
    "22" => array ( "area" => "Dalmatia", "city" => "Šibenik-Knin", "maximum" => 9, "minimum" => 8),
    "21" => array ( "area" => "Dalmatia", "city" => "Split-Dalmatia", "maximum" => 9, "minimum" => 8),
    "20" => array ( "area" => "Dalmatia", "city" => "Dubrovnik-Neretva", "maximum" => 9, "minimum" => 8),
    "1" => array ( "area" => "Central Croatia", "city" => "Zagreb and The City of Zagreb", "maximum" => 8, "minimum" => 8)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= $data["minimum"] + 4 && strlen ( $parameters["Number"]) <= $data["maximum"] + 4)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "385", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Croatia", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+385 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for Premium Rate Numbers network with 2 digits NDC and 4 to 6 digits SN
   */
  $prefixes = array (
    "609" => array ( "area" => "Humanitarian services", "maximum" => 8, "minimum" => 6),
    "69" => array ( "area" => "Services for children", "maximum" => 8, "minimum" => 8),
    "65" => array ( "area" => "Games of fortune services", "maximum" => 8, "minimum" => 8),
    "64" => array ( "area" => "Adult services", "maximum" => 8, "minimum" => 8),
    "61" => array ( "area" => "Televoting services", "maximum" => 8, "minimum" => 6),
    "60" => array ( "area" => "General services", "maximum" => 8, "minimum" => 6),
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= $data["minimum"] + 4 && strlen ( $parameters["Number"]) <= $data["maximum"] + 4)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "385", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Croatia", "Area" => $data["area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+385 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Croatian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
