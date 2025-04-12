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
 * related to country database of Guinea.
 *
 * Reference: https://www.itu.int/oth/T020200005B/en (2013-06-13)
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
 * E.164 Guinea country hook
 */
framework_add_filter ( "e164_identify_country_GIN", "e164_identify_country_GIN");

/**
 * E.164 Guinea area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "GIN" (code for Guinea). This
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
function e164_identify_country_GIN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Guinea
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+224")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Guinea has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "669" => "Areeba",
    "666" => "Areeba",
    "664" => "Areeba",
    "662" => "Areeba",
    "657" => "Cellcom",
    "655" => "Cellcom",
    "631" => "Cellcom",
    "628" => "Orange",
    "622" => "Orange",
    "621" => "Orange",
    "601" => "Sotelgui"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "224", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Guinea", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+224 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "30613" => "Télimélé",
    "3098" => "Kissidougou",
    "3097" => "Guéckédou",
    "3094" => "Macenta",
    "3091" => "N'Zérékoré",
    "3081" => "Faranah",
    "3071" => "Kankan",
    "3069" => "Dalaba",
    "3068" => "Mamou Kagoma",
    "3061" => "Kindia",
    "3053" => "Pita",
    "3051" => "Labé",
    "3047" => "Conakry",
    "3046" => "Boussoura",
    "3045" => "Conakry",
    "3043" => "Conakry",
    "3042" => "Sangoya",
    "3041" => "Conakry",
    "3032" => "Kamsar",
    "3031" => "Boké",
    "3024" => "Fria",
    "3"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "224", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Guinea", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+224 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Guinea phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
