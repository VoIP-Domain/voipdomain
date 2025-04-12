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
 * related to country database of Saudi Arabia.
 *
 * Reference: https://www.itu.int/oth/T02020000B7/en (2022-01-28)
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
 * E.164 Saudi Arabia country hook
 */
framework_add_filter ( "e164_identify_country_SAU", "e164_identify_country_SAU");

/**
 * E.164 Saudi Arabia area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "SAU" (code for
 * Saudi Arabia). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_SAU ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Saudi Arabia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+966")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Saudi Arabia has 12 to 14 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 12 || strlen ( $parameters["Number"]) > 14)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "589" => "",
    "588" => "",
    "587" => "",
    "586" => "",
    "585" => "",
    "584" => "",
    "583" => "",
    "582" => "",
    "581" => "Zain",
    "580" => "Zain",
    "578" => "Etihad Jawraa (Lebara Mobile KSA)",
    "577" => "Etihad Jawraa (Lebara Mobile KSA)",
    "576" => "Etihad Jawraa (Lebara Mobile KSA)",
    "575" => "Future Networks Communication (Red Bull Mobile KSA)",
    "572" => "Virgin Mobile Saudi Consortium LLC",
    "571" => "Virgin Mobile Saudi Consortium LLC",
    "570" => "Virgin Mobile Saudi Consortium LLC",
    "511" => "Integrated Telecom Mobile (Salam Mobile KSA)",
    "510" => "Integrated Telecom Mobile (Salam Mobile KSA)",
    "59" => "Zain",
    "56" => "Mobily",
    "55" => "STC",
    "54" => "Mobily",
    "53" => "STC",
    "52" => "",
    "50" => "STC"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "966", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Saudi Arabia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+966 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "17" => array ( "Area" => "Abha, Najran, Jezan", "Operator" => "STC, Atheb"),
    "16" => array ( "Area" => "Hail, Qasim", "Operator" => "STC, Atheb"),
    "15" => array ( "Area" => "", "Operator" => "STC, Atheb"),
    "14" => array ( "Area" => "Madenah, Arar, Tabuk, Yanbu", "Operator" => "STC, Atheb"),
    "13" => array ( "Area" => "Dammam, Khobar, Dahran", "Operator" => "STC, Atheb"),
    "12" => array ( "Area" => "Makkah, Jeddah", "Operator" => "STC, Atheb"),
    "11" => array ( "Area" => "Riyadh, Kharj", "Operator" => "STC, Atheb")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "966", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Saudi Arabia", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+966 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for PRN network with 3 digits NDC and 5 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 12 && substr ( $parameters["Number"], 4, 3) == "700")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "966", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Saudi Arabia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+966 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 7 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 14 && substr ( $parameters["Number"], 4, 3) == "800")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "966", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Saudi Arabia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+966 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
  }


  /**
   * If reached here, number wasn't identified as a valid Saudi Arabia phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
