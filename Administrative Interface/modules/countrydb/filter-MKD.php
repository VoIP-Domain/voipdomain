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
 * related to country database of North Macedonia.
 *
 * Reference: https://www.itu.int/oth/T02020000CE/en (2021-11-24)
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
 * E.164 North Macedonia country hook
 */
framework_add_filter ( "e164_identify_country_MKD", "e164_identify_country_MKD");

/**
 * E.164 Macedonian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "MKD" (code for
 * North Macedonia). This hook will verify if phone number is valid, returning
 * the area code, area name, phone number, others number related information and
 * if possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_MKD ( $buffer, $parameters)
{
  /**
   * Check if number country code is from North Macedonia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+389")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in North Macedonia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network
   */
  $prefixes = array (
    "79" => "Lycamobile MVNO",
    "78" => "A1 Macedonia",
    "77" => "A1 Macedonia",
    "76" => "A1 Macedonia",
    "75" => "A1 Macedonia",
    "74" => "TRD Robi DOOEL Stip MVNO",
    "73" => "A1 Macedonia",
    "72" => "Macedonia Telecom AD Skopje",
    "71" => "Macedonia Telecom AD Skopje",
    "70" => "Macedonia Telecom AD Skopje"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "389", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "North Macedonia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+389 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1 or 2 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "48" => "Prilep, Krusevo",
    "47" => "Bitola, Demir Hisar, Resen",
    "46" => "Ohrid, Struga, Debar",
    "45" => "Kicevo, Makedonski Brod",
    "44" => "Tetovo",
    "43" => "Veles, Kavadarci, Negotino",
    "42" => "Gostivar",
    "34" => "Gevgelija, Valandovo, Strumica, Dojran",
    "33" => "Kocani, Berovo, Delcevo, Vinica",
    "32" => "Stip, Probistip, Sveti Nikole, Radovis",
    "31" => "Kumanovo, Kriva Palanka, Kratovo",
    "2" => "Skopje"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( substr ( $parameters["Number"], 4, 1) == "2")
      {
        $ndc = substr ( $parameters["Number"], 4, 1);
        $sn = substr ( $parameters["Number"], 5);
      } else {
        $ndc = substr ( $parameters["Number"], 4, 2);
        $sn = substr ( $parameters["Number"], 6);
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "389", "NDC" => $ndc, "Country" => "North Macedonia", "Area" => $area, "City" => "", "Operator" => "", "SN" => $sn, "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+389 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 5 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 3) == "800")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "389", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "North Macedonia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+389 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for PRN network with 3 digits NDC and 5 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 1) == "5" && substr ( $parameters["Number"], 5, 1) != "1")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "389", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "North Macedonia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+389 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * If reached here, number wasn't identified as a valid Macedonian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
