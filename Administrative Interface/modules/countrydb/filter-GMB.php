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
 * related to country database of Gambia.
 *
 * Reference: https://www.itu.int/oth/T020200004F/en (2018-12-13)
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
 * E.164 Gambia country hook
 */
framework_add_filter ( "e164_identify_country_GMB", "e164_identify_country_GMB");

/**
 * E.164 Gambian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "GMB" (code for Gambia). This
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
function e164_identify_country_GMB ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Gambia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+220")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Gambia has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "51" => "QCell",
    "50" => "QCell",
    "9" => "Gamcel",
    "7" => "Africell",
    "6" => "Comium",
    "3" => "QCell",
    "2" => "Africell"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "220", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Gambia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+220 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "44195" => array ( "operator" => "Gamtel", "area" => "Berending"),
    "5748" => array ( "operator" => "Gamtel", "area" => "Kaur"),
    "5738" => array ( "operator" => "Gamtel", "area" => "Ngensanjal"),
    "5735" => array ( "operator" => "Gamtel", "area" => "Farafenni"),
    "5725" => array ( "operator" => "Gamtel", "area" => "Iliasa"),
    "5723" => array ( "operator" => "Gamtel", "area" => "Njabakunda"),
    "5720" => array ( "operator" => "Gamtel", "area" => "Kerewan"),
    "5714" => array ( "operator" => "Gamtel", "area" => "Ndugukebbe"),
    "5710" => array ( "operator" => "Gamtel", "area" => "Barra"),
    "5678" => array ( "operator" => "Gamtel", "area" => "Brikama-Ba"),
    "5676" => array ( "operator" => "Gamtel", "area" => "Georgetown"),
    "5674" => array ( "operator" => "Gamtel", "area" => "Bansang"),
    "5666" => array ( "operator" => "Gamtel", "area" => "Numeyel"),
    "5665" => array ( "operator" => "Gamtel", "area" => "Kuntaur"),
    "5547" => array ( "operator" => "Gamtel", "area" => "Jareng"),
    "5546" => array ( "operator" => "Gamtel", "area" => "Kudang"),
    "5545" => array ( "operator" => "Gamtel", "area" => "Pakaliba"),
    "5544" => array ( "operator" => "Gamtel", "area" => "Bureng"),
    "5543" => array ( "operator" => "Gamtel", "area" => "Japeneh and Soma"),
    "5542" => array ( "operator" => "Gamtel", "area" => "Nyorojattaba"),
    "5541" => array ( "operator" => "Gamtel", "area" => "Kwenella"),
    "5540" => array ( "operator" => "Gamtel", "area" => "Kaiaf"),
    "4489" => array ( "operator" => "Gamtel", "area" => "Bwiam"),
    "4488" => array ( "operator" => "Gamtel", "area" => "Sibanor"),
    "4487" => array ( "operator" => "Gamtel", "area" => "Faraba"),
    "4486" => array ( "operator" => "Gamtel", "area" => "Gunjur"),
    "4485" => array ( "operator" => "Gamtel", "area" => "Kafuta"),
    "4480" => array ( "operator" => "Gamtel", "area" => "Bondali"),
    "4419" => array ( "operator" => "Gamtel", "area" => "Kartong"),
    "4417" => array ( "operator" => "Gamtel", "area" => "Sanyang"),
    "4416" => array ( "operator" => "Gamtel", "area" => "Tujereng"),
    "4412" => array ( "operator" => "Gamtel", "area" => "Tanji"),
    "4410" => array ( "operator" => "Gamtel", "area" => "Brufut"),
    "567" => array ( "operator" => "Gamtel", "area" => "Sotuma"),
    "566" => array ( "operator" => "Gamtel", "area" => "Baja Kunda, Basse, Fatoto, Gambisara, Garawol, Misera, Sambakunda and Sudowol"),
    "449" => array ( "operator" => "Gamtel", "area" => "Bakau"),
    "448" => array ( "operator" => "Gamtel", "area" => "Brikama and Kanilia"),
    "447" => array ( "operator" => "Gamtel", "area" => "Yundum"),
    "446" => array ( "operator" => "Gamtel", "area" => "Kotu and Senegambia"),
    "57" => array ( "operator" => "Gamtel", "area" => ""),
    "56" => array ( "operator" => "Gamtel", "area" => ""),
    "43" => array ( "operator" => "Gamtel", "area" => "Bundung and Serekunda"),
    "42" => array ( "operator" => "Gamtel", "area" => "Banjul"),
    "4" => array ( "operator" => "Gamtel", "area" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "220", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Gambia", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+220 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for FMC network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "4414" => "Gamtel",
    "8" => "Gamtel"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "220", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Gambia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+220 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Gambian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
