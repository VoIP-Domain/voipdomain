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
 * related to country database of Maldives.
 *
 * Reference: https://www.itu.int/oth/T0202000082/en (2016-08-08)
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
 * E.164 Maldives country hook
 */
framework_add_filter ( "e164_identify_country_MDV", "e164_identify_country_MDV");

/**
 * E.164 Maldivian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MDV" (code for Maldives). This
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
function e164_identify_country_MDV ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Maldives
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+960")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Maldives has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "72" => "Dhiraagu",
    "73" => "Dhiraagu",
    "74" => "Dhiraagu",
    "75" => "Dhiraagu",
    "76" => "Dhiraagu",
    "77" => "Dhiraagu",
    "78" => "Dhiraagu",
    "79" => "Dhiraagu",
    "91" => "Ooredoo",
    "92" => "Ooredoo",
    "93" => "Ooredoo",
    "94" => "Ooredoo",
    "95" => "Ooredoo",
    "96" => "Ooredoo",
    "97" => "Ooredoo",
    "98" => "Ooredoo",
    "99" => "Ooredoo"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "960", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Maldives", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+960 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "300" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "301" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "302" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "303" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "330" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "331" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "332" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "333" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "334" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "335" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "339" => array ( "Area" => "Male Region", "Operator" => "Dhiraagu"),
    "400" => array ( "Area" => "Non Geographic Fixed Service", "Operator" => "Ooredoo"),
    "401" => array ( "Area" => "Non Geographic Fixed Service", "Operator" => "Ooredoo"),
    "450" => array ( "Area" => "Non Geographic Fixed Service", "Operator" => "Dhiraagu"),
    "650" => array ( "Area" => "Haa Alifu Atoll", "Operator" => "Dhiraagu"),
    "652" => array ( "Area" => "Haa Dhaalu Atoll", "Operator" => "Dhiraagu"),
    "654" => array ( "Area" => "Shaviani Atoll", "Operator" => "Dhiraagu"),
    "656" => array ( "Area" => "Noonu Atoll", "Operator" => "Dhiraagu"),
    "658" => array ( "Area" => "Raa Atoll", "Operator" => "Dhiraagu"),
    "659" => array ( "Area" => "Raa Atoll", "Operator" => "Dhiraagu"),
    "660" => array ( "Area" => "Baa Atoll", "Operator" => "Dhiraagu"),
    "662" => array ( "Area" => "Lhaviyani Atoll", "Operator" => "Dhiraagu"),
    "664" => array ( "Area" => "Kaafu Atoll", "Operator" => "Dhiraagu"),
    "665" => array ( "Area" => "Kaafu Atoll", "Operator" => "Dhiraagu"),
    "666" => array ( "Area" => "Alifu Alifu Atoll", "Operator" => "Dhiraagu"),
    "668" => array ( "Area" => "Alifu Dhaalu Atoll", "Operator" => "Dhiraagu"),
    "670" => array ( "Area" => "Vaavu Atoll", "Operator" => "Dhiraagu"),
    "672" => array ( "Area" => "Meenu Atoll", "Operator" => "Dhiraagu"),
    "674" => array ( "Area" => "Faafu Atoll", "Operator" => "Dhiraagu"),
    "676" => array ( "Area" => "Dhaalu Atoll", "Operator" => "Dhiraagu"),
    "678" => array ( "Area" => "Thaa Atoll", "Operator" => "Dhiraagu"),
    "680" => array ( "Area" => "Laamu Atoll", "Operator" => "Dhiraagu"),
    "682" => array ( "Area" => "Gaafu Alifu Atoll", "Operator" => "Dhiraagu"),
    "684" => array ( "Area" => "Gaafu Dhaalu Atoll", "Operator" => "Dhiraagu"),
    "686" => array ( "Area" => "Gnaviani Atoll", "Operator" => "Dhiraagu"),
    "688" => array ( "Area" => "Seenu Atoll", "Operator" => "Dhiraagu"),
    "689" => array ( "Area" => "Seenu Atoll", "Operator" => "Dhiraagu")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "960", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Maldives", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+960 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Maldivian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
