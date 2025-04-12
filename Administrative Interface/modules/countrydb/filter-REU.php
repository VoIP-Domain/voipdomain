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
 * related to country database of Réunion.
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
 * E.164 Réunion country hook
 */
framework_add_filter ( "e164_identify_country_REU", "e164_identify_country_REU");

/**
 * E.164 Réunion area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "REU" (code for Réunion). This
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
function e164_identify_country_REU ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Réunion
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+262")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Réunion has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "69399" => "La Réunion",
    "69397" => "La Réunion",
    "69394" => "La Réunion",
    "69393" => "La Réunion",
    "69392" => "La Réunion",
    "69391" => "La Réunion",
    "69390" => "La Réunion",
    "69388" => "La Réunion",
    "69387" => "La Réunion",
    "69386" => "La Réunion",
    "69385" => "La Réunion",
    "69384" => "La Réunion",
    "69383" => "La Réunion",
    "69382" => "La Réunion",
    "69381" => "La Réunion",
    "69380" => "La Réunion",
    "69377" => "La Réunion",
    "69370" => "La Réunion",
    "69366" => "La Réunion",
    "69362" => "La Réunion",
    "69361" => "La Réunion",
    "69360" => "La Réunion",
    "69355" => "La Réunion",
    "69350" => "La Réunion",
    "69339" => "La Réunion",
    "69333" => "La Réunion",
    "69332" => "La Réunion",
    "69331" => "La Réunion",
    "69330" => "La Réunion",
    "69322" => "La Réunion",
    "69321" => "La Réunion",
    "69320" => "La Réunion",
    "69313" => "La Réunion",
    "69311" => "La Réunion",
    "69310" => "La Réunion",
    "69306" => "La Réunion",
    "69304" => "La Réunion",
    "69303" => "La Réunion",
    "69302" => "La Réunion",
    "69301" => "La Réunion",
    "69300" => "La Réunion",
    "6934" => "La Réunion",
    "692" => "La Réunion"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "262", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Réunion", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+262 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "97637" => "Réunion - Océan indien",
    "97633" => "Réunion - Océan indien",
    "97632" => "Réunion - Océan indien",
    "97631" => "Réunion - Océan indien",
    "97627" => "Réunion - Océan indien",
    "97622" => "Réunion - Océan indien",
    "9769" => "Réunion - Océan indien"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "262", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Réunion", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11), "International" => "+262 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Réunion phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
