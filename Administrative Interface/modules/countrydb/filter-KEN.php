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
 * related to country database of Kenya.
 *
 * Reference: https://www.itu.int/oth/T0202000070/en (2018-02-13)
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
 * E.164 Kenya country hook
 */
framework_add_filter ( "e164_identify_country_KEN", "e164_identify_country_KEN");

/**
 * E.164 Kenya area number identification hook. This hook is an e164_identify sub
 * hook, called when the ISO3166 Alpha3 are "KEN" (code for Kenya). This hook
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
function e164_identify_country_KEN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Kenya
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+254")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "789" => "Airtel Networks Kenya Ltd.",
    "788" => "Airtel Networks Kenya Ltd.",
    "787" => "Airtel Networks Kenya Ltd.",
    "786" => "Airtel Networks Kenya Ltd.",
    "785" => "Airtel Networks Kenya Ltd.",
    "782" => "Airtel Networks Kenya Ltd.",
    "781" => "Airtel Networks Kenya Ltd.",
    "780" => "Airtel Networks Kenya Ltd.",
    "767" => "Sema Mobile Services Ltd.",
    "766" => "Finserve Africa Ltd.",
    "765" => "Finserve Africa Ltd.",
    "764" => "Finserve Africa Ltd.",
    "763" => "Finserve Africa Ltd.",
    "760" => "Mobile Pay Ltd.",
    "756" => "Airtel Networks Kenya Ltd.",
    "755" => "Airtel Networks Kenya Ltd.",
    "754" => "Airtel Networks Kenya Ltd.",
    "753" => "Airtel Networks Kenya Ltd.",
    "752" => "Airtel Networks Kenya Ltd.",
    "751" => "Airtel Networks Kenya Ltd.",
    "750" => "Airtel Networks Kenya Ltd.",
    "749" => "WiAfrica Kenya Ltd.",
    "748" => "Safaricom Ltd.",
    "747" => "Jamii Telecom Ltd. for Kenya",
    "746" => "Safaricom Ltd.",
    "744" => "Homeland Media Group Ltd.",
    "743" => "Safaricom Ltd.",
    "742" => "Safaricom Ltd.",
    "741" => "Safaricom Ltd.",
    "740" => "Safaricom Ltd.",
    "79" => "Safaricom Ltd.",
    "77" => "Telkom Kenya Ltd.",
    "73" => "Airtel Networks Kenya Ltd.",
    "72" => "Safaricom Ltd.",
    "71" => "Safaricom Ltd.",
    "70" => "Safaricom Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "254", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Kenya", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+254 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 5 to 7 digits SN
   */
  $prefixes = array (
    "573000" => array ( "area" => "Kisumu and Siaya", "operator" => "Jamii Telecommunications Ltd.", "minimum" => 9, "maximum" => 9),
    "523000" => array ( "area" => "Kericho and Bomet", "operator" => "Jamii Telecommunications Ltd.", "minimum" => 9, "maximum" => 9),
    "513100" => array ( "area" => "Nakuru, Njoro and Molo", "operator" => "Jamii Telecommunications Ltd.", "minimum" => 9, "maximum" => 9),
    "414000" => array ( "area" => "Mombasa, Mariakani and Kilifi", "operator" => "Jamii Telecommunications Ltd.", "minimum" => 9, "maximum" => 9),
    "56200" => array ( "area" => "Kakamega and Vihiga", "operator" => "Telkom Kenya Ltd.", "minimum" => 9, "maximum" => 9),
    "54202" => array ( "area" => "Kitale, Moisbridge, Kapenguria and Lodwar", "operator" => "Telkom Kenya Ltd.", "minimum" => 9, "maximum" => 9),
    "44344" => array ( "area" => "Machakos, Makueni, Kitui and Mwingi", "operator" => "Telkom Kenya Ltd.", "minimum" => 9, "maximum" => 9),
    "41650" => array ( "area" => "Mombasa, Mariakani and Kilifi", "operator" => "Jamii Telecommunications Ltd.", "minimum" => 9, "maximum" => 9),
    "20768" => array ( "area" => "Nairobi", "operator" => "WiAfrica Kenya Ltd.", "minimum" => 9, "maximum" => 9),
    "20765" => array ( "area" => "Nairobi", "operator" => "SimbaNet Com Ltd.", "minimum" => 9, "maximum" => 9),
    "20764" => array ( "area" => "Nairobi", "operator" => "Wananchi Telecom Ltd.", "minimum" => 9, "maximum" => 9),
    "20759" => array ( "area" => "Nairobi", "operator" => "Geonet Communications Ltd.", "minimum" => 9, "maximum" => 9),
    "20310" => array ( "area" => "Nairobi", "operator" => "Jamii Telecommunications Ltd.", "minimum" => 9, "maximum" => 9),
    "2032" => array ( "area" => "Nairobi", "operator" => "Telkom Kenya Ltd.", "minimum" => 9, "maximum" => 9),
    "2030" => array ( "area" => "Nairobi", "operator" => "Airtel Networks Kenya Ltd.", "minimum" => 9, "maximum" => 9),
    "69" => array ( "area" => "Marsabit and Moyale", "operator" => "", "minimum" => 7, "maximum" => 9),
    "68" => array ( "area" => "Embu", "operator" => "", "minimum" => 9, "maximum" => 9),
    "67" => array ( "area" => "Kiambu and Kikuyu", "operator" => "", "minimum" => 7, "maximum" => 9),
    "66" => array ( "area" => "Thika and Ruiru", "operator" => "", "minimum" => 8, "maximum" => 9),
    "64" => array ( "area" => "Meru, Maua and Chuka", "operator" => "", "minimum" => 7, "maximum" => 9),
    "62" => array ( "area" => "Nanyuki", "operator" => "", "minimum" => 7, "maximum" => 9),
    "61" => array ( "area" => "Nyeri", "operator" => "", "minimum" => 7, "maximum" => 9),
    "60" => array ( "area" => "Muranga and Kirinyaga", "operator" => "", "minimum" => 7, "maximum" => 9),
    "59" => array ( "area" => "Homabay and Migori", "operator" => "", "minimum" => 7, "maximum" => 9),
    "58" => array ( "area" => "Kisii, Kilgoris, Oyugis and Nyamira", "operator" => "", "minimum" => 9, "maximum" => 9),
    "57" => array ( "area" => "Kisumu and Siaya", "operator" => "", "minimum" => 7, "maximum" => 9),
    "56" => array ( "area" => "Kakamega and Vihiga", "operator" => "", "minimum" => 7, "maximum" => 9),
    "55" => array ( "area" => "Bungoma and Busia", "operator" => "", "minimum" => 7, "maximum" => 9),
    "54" => array ( "area" => "Kitale, Moisbridge, Kapenguria and Lodwar", "operator" => "", "minimum" => 7, "maximum" => 9),
    "53" => array ( "area" => "Eldoret, Turbo, Kapsabet, Iten and Kabarnet", "operator" => "", "minimum" => 7, "maximum" => 9),
    "52" => array ( "area" => "Kericho and Bomet", "operator" => "", "minimum" => 7, "maximum" => 9),
    "51" => array ( "area" => "Nakuru, Njoro and Molo", "operator" => "", "minimum" => 8, "maximum" => 9),
    "50" => array ( "area" => "Narok and Nakuru", "operator" => "", "minimum" => 9, "maximum" => 9),
    "46" => array ( "area" => "North Eastern regions of Garissa, Wajir and Mandera", "operator" => "", "minimum" => 9, "maximum" => 9),
    "45" => array ( "area" => "Ngong, Kajiado, Loitokitok and Athi River", "operator" => "", "minimum" => 7, "maximum" => 9),
    "44" => array ( "area" => "Machakos, Makueni, Kitui and Mwingi", "operator" => "", "minimum" => 7, "maximum" => 9),
    "43" => array ( "area" => "Voi, Mwatate Wundanyi and Taveta", "operator" => "", "minimum" => 9, "maximum" => 9),
    "42" => array ( "area" => "Malindi, Lamu and Garsen", "operator" => "", "minimum" => 7, "maximum" => 9),
    "41" => array ( "area" => "Mombasa, Mariakani and Kilifi", "operator" => "", "minimum" => 9, "maximum" => 9),
    "40" => array ( "area" => "Kwale, Ukunda, Msambweni and Lungalunga", "operator" => "", "minimum" => 8, "maximum" => 9),
    "20" => array ( "area" => "Nairobi", "operator" => "", "minimum" => 8, "maximum" => 9)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 4 + $data["minimum"] && strlen ( $parameters["Number"]) <= 4 + $data["maximum"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "254", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Kenya", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . substr ( $parameters["Number"], 6), "International" => "+254 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Kenya phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
