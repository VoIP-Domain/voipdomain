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
 * related to country database of Sri Lanka.
 *
 * Reference: https://www.itu.int/oth/T02020000C3/en (2009-09-28)
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
 * E.164 Sri Lanka country hook
 */
framework_add_filter ( "e164_identify_country_LKA", "e164_identify_country_LKA");

/**
 * E.164 Sri Lanka area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "LKA" (code for Sri Lanka). This
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
function e164_identify_country_LKA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Sri Lanka
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+94")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Sri Lanka has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "79" => "",
    "78" => "Hutch",
    "77" => "Dialog",
    "76" => "Dialog",
    "75" => "Airtel",
    "74" => "Dialog",
    "73" => "",
    "72" => "Hutch",
    "71" => "SLT-Mobitel",
    "70" => "SLT-Mobitel"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "94", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Sri Lanka", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+94 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC, 1 digit operator code and 6 digits SN
   */
  $prefixes = array (
    "91" => array ( "Area" => "Galle", "City" => "Galle"),
    "81" => array ( "Area" => "Kandy", "City" => "Kandy"),
    "67" => array ( "Area" => "Ampara", "City" => "Kalmunai"),
    "66" => array ( "Area" => "Matale", "City" => "Matale"),
    "65" => array ( "Area" => "Batticaloa", "City" => "Batticaloa"),
    "63" => array ( "Area" => "Ampara", "City" => "Ampara"),
    "57" => array ( "Area" => "Badulla", "City" => "Bandarawela"),
    "56" => array ( "Area" => "Badulla", "City" => "Badulla"),
    "55" => array ( "Area" => "Monaragala", "City" => "Monaragala"),
    "54" => array ( "Area" => "Kandy", "City" => "Nawalapitiya"),
    "52" => array ( "Area" => "Nuwara Eliya", "City" => "Nuwara Eliya"),
    "51" => array ( "Area" => "Nuwara Eliya", "City" => "Hatton"),
    "47" => array ( "Area" => "Hambantota", "City" => "Hambantota"),
    "45" => array ( "Area" => "Ratnapura", "City" => "Ratnapura"),
    "41" => array ( "Area" => "Matara", "City" => "Matara"),
    "38" => array ( "Area" => "Kalutara", "City" => "Panadura"),
    "37" => array ( "Area" => "Kurunegala", "City" => "Kurunegala"),
    "36" => array ( "Area" => "Colombo", "City" => "Avissawella"),
    "35" => array ( "Area" => "Kegalle", "City" => "Kegalle"),
    "34" => array ( "Area" => "Kalutara", "City" => "Kalutara"),
    "33" => array ( "Area" => "Gampaha", "City" => "Gampaha"),
    "32" => array ( "Area" => "Puttalam", "City" => "Chilaw"),
    "31" => array ( "Area" => "Gampaha", "City" => "Negombo"),
    "27" => array ( "Area" => "Polonnaruwa", "City" => "Polonnaruwa"),
    "26" => array ( "Area" => "Trincomalee", "City" => "Trincomalee"),
    "25" => array ( "Area" => "Anuradhapura", "City" => "Anuradhapura"),
    "24" => array ( "Area" => "Vavuniya", "City" => "Vavuniya"),
    "23" => array ( "Area" => "Mannar", "City" => "Mannar"),
    "21" => array ( "Area" => "Jaffna", "City" => "Jaffna"),
    "11" => array ( "Area" => "Colombo", "City" => "Colombo")
  );
  $operators = array (
    "9" => array ( "Operator" => "Tritel", "Type" => VD_PHONETYPE_PAYPHONE),
    "8" => array ( "Operator" => "", "Type" => VD_PHONETYPE_LANDLINE),
    "7" => array ( "Operator" => "Dialog", "Type" => VD_PHONETYPE_FMC),
    "6" => array ( "Operator" => "", "Type" => VD_PHONETYPE_LANDLINE),
    "5" => array ( "Operator" => "Lanka Bell", "Type" => VD_PHONETYPE_FMC),
    "4" => array ( "Operator" => "Dialog", "Type" => VD_PHONETYPE_FMC),
    "3" => array ( "Operator" => "SLT-Mobitel", "Type" => VD_PHONETYPE_FMC),
    "2" => array ( "Operator" => "SLT-Mobitel", "Type" => VD_PHONETYPE_LANDLINE),
    "1" => array ( "Operator" => "", "Type" => VD_PHONETYPE_LANDLINE),
    "0" => array ( "Operator" => "Lanka Bell", "Type" => VD_PHONETYPE_FMC)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "94", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Sri Lanka", "Area" => $data["Area"], "City" => $data["City"], "Operator" => $operators[substr ( $parameters["Number"], 5, 1)]["Operator"], "SN" => substr ( $parameters["Number"], 5), "Type" => $operators[substr ( $parameters["Number"], 5, 1)]["Type"], "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 1) . " " . substr ( $parameters["Number"], 6), "International" => "+94 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 1) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Sri Lanka phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
