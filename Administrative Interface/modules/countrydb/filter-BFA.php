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
 * related to country database of Burkina Faso.
 *
 * Reference: https://www.itu.int/oth/T0202000021/en (2018-03-29)
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
 * E.164 Burkina Faso country hook
 */
framework_add_filter ( "e164_identify_country_BFA", "e164_identify_country_BFA");

/**
 * E.164 Burkina Faso area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "BFA" (code for
 * Burkina Faso). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_BFA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Burkina Faso
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+226")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Burkina Faso has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "79" => "Telecel Faso S.A.",
    "78" => "Telecel Faso S.A.",
    "77" => "Airtel Burkina Faso S.A.",
    "76" => "Airtel Burkina Faso S.A.",
    "75" => "Airtel Burkina Faso S.A.",
    "74" => "Airtel Burkina Faso S.A.",
    "73" => "Onatel S.A. (Telemob)",
    "72" => "Onatel S.A. (Telemob)",
    "71" => "Onatel S.A. (Telemob)",
    "70" => "Onatel S.A. (Telemob)",
    "69" => "Telecel Faso S.A.",
    "68" => "Telecel Faso S.A.",
    "67" => "Airtel Burkina Faso S.A.",
    "66" => "Airtel Burkina Faso S.A.",
    "65" => "Airtel Burkina Faso S.A.",
    "64" => "Airtel Burkina Faso S.A.",
    "63" => "Onatel S.A. (Telemob)",
    "62" => "Onatel S.A. (Telemob)",
    "61" => "Onatel S.A. (Telemob)",
    "60" => "Onatel S.A. (Telemob)",
    "58" => "Telecel Faso S.A.",
    "57" => "Orange Burkina Faso S.A.",
    "56" => "Airtel Burkina Faso S.A.",
    "55" => "Airtel Burkina Faso S.A.",
    "54" => "Orange Burkina Faso S.A.",
    "52" => "Onatel S.A. (Telemob)",
    "51" => "Onatel S.A. (Telemob)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "226", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Burkina Faso", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+226 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "2567" => "Airtel Burkina Faso S.A.",
    "2566" => "Airtel Burkina Faso S.A.",
    "2565" => "Airtel Burkina Faso S.A.",
    "2479" => "Onatel S.A.",
    "2477" => "Onatel S.A.",
    "2471" => "Onatel S.A.",
    "2470" => "Onatel S.A.",
    "2466" => "Airtel Burkina Faso S.A.",
    "2465" => "Airtel Burkina Faso S.A.",
    "2456" => "Onatel S.A.",
    "2455" => "Onatel S.A.",
    "2454" => "Onatel S.A.",
    "2446" => "Onatel S.A.",
    "2445" => "Onatel S.A.",
    "2099" => "Onatel S.A.",
    "2098" => "Onatel S.A.",
    "2097" => "Onatel S.A.",
    "2096" => "Onatel S.A.",
    "2091" => "Onatel S.A.",
    "2090" => "Onatel S.A.",
    "2066" => "Airtel Burkina Faso S.A.",
    "2065" => "Airtel Burkina Faso S.A.",
    "2053" => "Onatel S.A.",
    "2052" => "Onatel S.A.",
    "2049" => "Onatel S.A.",
    "254" => "Onatel S.A.",
    "253" => "Onatel S.A."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      switch ( substr ( $parameters["Number"], 5, 1))
      {
        case "0":
          $area = "West";
          break;
        case "4":
          $area = "Centre";
          break;
        case "5":
          $area = "North and East";
          break;
        default:
          $area = "";
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "226", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Burkina Faso", "Area" => $area, "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+226 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Burkina Faso phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
