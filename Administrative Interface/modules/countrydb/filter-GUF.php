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
 * related to country database of French Guiana.
 *
 * Reference: https://www.itu.int/oth/T020200004C/en (2017-12-15)
 * Reference: https://extranet.arcep.fr/portail/LinkClick.aspx?fileticket=PBA1WK-wnOU%3d&tabid=217&portalid=0&mid=850
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
 * E.164 French Guiana country hook
 */
framework_add_filter ( "e164_identify_country_GUF", "e164_identify_country_GUF");

/**
 * E.164 French Guiana area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "GUF" (code for
 * French Guiana). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_GUF ( $buffer, $parameters)
{
  /**
   * Check if number country code is from French Guiana
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+594")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in French Guiana has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "69438" => "Guyane",
    "69434" => "Guyane",
    "69433" => "Guyane",
    "69432" => "Guyane",
    "69431" => "Guyane",
    "69430" => "Guyane",
    "6949" => "Guyane",
    "6944" => "Guyane",
    "6942" => "Guyane",
    "6941" => "Guyane",
    "6940" => "Guyane"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "594", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "French Guiana", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+594 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "97652" => "Guyane",
    "97651" => "Guyane",
    "97650" => "Guyane",
    "97647" => "Guyane",
    "97641" => "Guyane",
    "97640" => "Guyane",
    "59494" => "ZNE Cayenne",
    "59491" => "ZNE Saint-Laurent-Du-Maroni",
    "59490" => "ZNE Kourou",
    "59480" => "ZNE Cayenne",
    "59463" => "ZNE Grand-Santi",
    "59462" => "ZNE Maripasoula",
    "59461" => "ZNE Roura",
    "59460" => "ZNE Sinnamary",
    "59459" => "ZNE Saint-Laurent-Du-Maroni",
    "59458" => "ZNE Kourou",
    "59457" => "ZNE Cayenne",
    "59456" => "ZNE Cayenne",
    "59449" => "ZNE Grand-Santi",
    "59448" => "ZNE Saint-Laurent-Du-Maroni",
    "59447" => "ZNE Kourou",
    "59446" => "ZNE Cayenne",
    "59445" => "ZNE Cayenne",
    "59444" => "ZNE Cayenne",
    "59443" => "ZNE Kourou",
    "59441" => "ZNE Cayenne",
    "59440" => "ZNE Cayenne",
    "59439" => "ZNE Cayenne",
    "59438" => "ZNE Cayenne",
    "59437" => "ZNE Cayenne",
    "59436" => "ZNE Maripasoula",
    "59435" => "ZNE Cayenne",
    "59434" => "ZNE Saint-Laurent-Du-Maroni",
    "59433" => "ZNE Kourou",
    "59432" => "ZNE Kourou",
    "59431" => "ZNE Cayenne",
    "59430" => "ZNE Cayenne",
    "59429" => "ZNE Cayenne",
    "59428" => "ZNE Roura",
    "59427" => "ZNE Roura",
    "59426" => "ZNE Cayenne",
    "59425" => "ZNE Cayenne",
    "59424" => "ZNE Sinnamary",
    "59423" => "ZNE Saint-Laurent-Du-Maroni",
    "59422" => "ZNE Kourou",
    "59421" => "ZNE Kourou",
    "59420" => "ZNE Cayenne",
    "59411" => "ZNE Grand-Santi",
    "59410" => "ZNE Cayenne",
    "59409" => "ZNE Maripasoula",
    "59408" => "ZNE Sinnamary",
    "59407" => "ZNE Saint-Laurent-Du-Maroni",
    "59406" => "ZNE Cayenne",
    "59405" => "ZNE Roura",
    "59404" => "ZNE Kourou",
    "59403" => "ZNE Cayenne",
    "59402" => "ZNE Kourou",
    "59401" => "ZNE Cayenne",
    "59400" => "ZNE Cayenne"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "594", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "French Guiana", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+594 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid French Guiana phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
