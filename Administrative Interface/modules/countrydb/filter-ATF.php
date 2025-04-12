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
 * related to country database of French Southern Territories.
 *
 * Reference: https://www.itu.int/oth/T020200004B/en (2017-12-15)
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
 * E.164 French Southern Territories country hook
 */
framework_add_filter ( "e164_identify_country_ATF", "e164_identify_country_ATF");

/**
 * E.164 French Southern Territories area number identification hook. This hook
 * is an e164_identify sub hook, called when the ISO3166 Alpha3 are "ATF" (code
 * for French Southern Territories). This hook will verify if phone number is
 * valid, returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_ATF ( $buffer, $parameters)
{
  /**
   * Check if number country code is from French Southern Territories
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+262")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in French Southern Territories has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for fixed line network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "26300" => "ZNE Saint-Denis",
    "26209" => "ZNE Saint-Denis",
    "26208" => "ZNE Saint-Denis",
    "26207" => "ZNE Saint-Denis",
    "26206" => "ZNE Saint-Denis",
    "26205" => "ZNE Saint-Denis",
    "26204" => "ZNE Saint-Denis",
    "26203" => "ZNE Saint-Denis",
    "26202" => "ZNE Saint-Denis",
    "26201" => "ZNE Saint-Denis",
    "2629" => "ZNE Saint-Denis",
    "2628" => "ZNE Saint-Denis",
    "2627" => "ZNE Saint-Denis",
    "2626" => "ZNE Saint-Denis",
    "2625" => "ZNE Saint-Denis",
    "2624" => "ZNE Saint-Denis",
    "2623" => "ZNE Saint-Denis",
    "2622" => "ZNE Saint-Denis",
    "2621" => "ZNE Saint-Denis"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "262", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "French Southern Territories", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+262 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid French Southern
   * Territories phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
