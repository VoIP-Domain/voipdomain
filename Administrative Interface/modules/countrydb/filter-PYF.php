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
 * related to country database of French Polynesia.
 *
 * Reference: https://www.itu.int/oth/T020200004D/en (2014-09-01)
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
 * E.164 French Polynesia country hook
 */
framework_add_filter ( "e164_identify_country_PYF", "e164_identify_country_PYF");

/**
 * E.164 French Polynesia area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "PYF" (code for
 * French Polynesia). This hook will verify if phone number is valid, returning
 * the area code, area name, phone number, others number related information and
 * if possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_PYF ( $buffer, $parameters)
{
  /**
   * Check if number country code is from French Polynesia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+689")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "89411" => array ( "operator" => "MSRN Vodafone", "area" => "Polynesia", "length" => 8),
    "87411" => array ( "operator" => "MSRN VINI", "area" => "Polynesia", "length" => 8),
    "4117" => array ( "operator" => "", "area" => "", "length" => 6),
    "4116" => array ( "operator" => "", "area" => "", "length" => 6),
    "4114" => array ( "operator" => "Vodafone", "area" => "", "length" => 6),
    "4113" => array ( "operator" => "Vodafone", "area" => "", "length" => 6),
    "4112" => array ( "operator" => "Vodafone", "area" => "", "length" => 6),
    "3917" => array ( "operator" => "", "area" => "", "length" => 6),
    "89" => array ( "operator" => "Vodafone", "area" => "Polynesia", "length" => 8),
    "87" => array ( "operator" => "VINI", "area" => "Polynesia", "length" => 8),
    "37" => array ( "operator" => "", "area" => "", "length" => 6),
    "36" => array ( "operator" => "", "area" => "", "length" => 6),
    "35" => array ( "operator" => "", "area" => "", "length" => 6),
    "34" => array ( "operator" => "", "area" => "", "length" => 6),
    "33" => array ( "operator" => "", "area" => "", "length" => 6),
    "32" => array ( "operator" => "", "area" => "", "length" => 6),
    "31" => array ( "operator" => "", "area" => "", "length" => 6),
    "30" => array ( "operator" => "", "area" => "", "length" => 6),
    "7" => array ( "operator" => "", "area" => "", "length" => 6),
    "2" => array ( "operator" => "", "area" => "", "length" => 6)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["length"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "689", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "French Polynesia", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""), "International" => "+689 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""))));
    }
  }

  /**
   * Check for payphones network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "4088" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Polynesia", "length" => 8)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["length"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "689", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "French Polynesia", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PAYPHONE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""), "International" => "+689 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "499" => array ( "operator" => "ManaBox VINI", "area" => "Polynesia", "length" => 8),
    "498" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Polynesia", "length" => 8),
    "496" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Polynesia", "length" => 8),
    "495" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Polynesia", "length" => 8),
    "494" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Polynesia", "length" => 8)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["length"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "689", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "French Polynesia", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""), "International" => "+689 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""))));
    }
  }

  /**
   * Check for Audiotext network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "44" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 6)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["length"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "689", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "French Polynesia", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_AUDIOTEXT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""), "International" => "+689 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "4089" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "4087" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "4086" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "4085" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "4084" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "4083" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "4082" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "4081" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "4080" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "409" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Remote archipelago", "length" => 8),
    "406" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles Sous-le-Vent", "length" => 8),
    "405" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "404" => array ( "operator" => "Office des Postes et Télécommunications", "area" => "Îles du Vent", "length" => 8),
    "9" => array ( "operator" => "", "area" => "", "length" => 6),
    "8" => array ( "operator" => "", "area" => "", "length" => 6),
    "6" => array ( "operator" => "", "area" => "", "length" => 6),
    "5" => array ( "operator" => "", "area" => "", "length" => 6),
    "4" => array ( "operator" => "", "area" => "", "length" => 6)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + $data["length"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "689", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "French Polynesia", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""), "International" => "+689 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . ( $data["length"] > 6 ? " " . substr ( $parameters["Number"], 10) : ""))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid French Polynesia phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
