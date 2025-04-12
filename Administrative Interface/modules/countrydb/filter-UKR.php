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
 * related to country database of Ukraine.
 *
 * Reference: https://www.itu.int/oth/T02020000DB/en (2019-09-09)
 * Reference: https://en.wikipedia.org/wiki/Telephone_numbers_in_Ukraine
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
 * E.164 Ukraine country hook
 */
framework_add_filter ( "e164_identify_country_UKR", "e164_identify_country_UKR");

/**
 * E.164 Ukraine area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "UKR" (code for Ukraine). This
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
function e164_identify_country_UKR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Ukraine
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+380")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "99" => "VF Ukraine PrJSC",
    "98" => "Kyivstar PrJSC",
    "97" => "Kyivstar PrJSC",
    "96" => "Kyivstar PrJSC",
    "95" => "VF Ukraine PrJSC",
    "94" => "Intertelecom LLC",
    "93" => "Lifecell LLC",
    "92" => "Telesystems of Ukraine PrJSC",
    "91" => "TriMob LLC",
    "73" => "Lifecell LLC",
    "68" => "Kyivstar PrJSC",
    "67" => "Kyivstar PrJSC",
    "66" => "VF Ukraine PrJSC",
    "63" => "Lifecell LLC",
    "50" => "VF Ukraine PrJSC"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 13)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "380", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ukraine", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . "-" . substr ( $parameters["Number"], 9, 2) . "-" . substr ( $parameters["Number"], 11), "International" => "+380 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
      } else {
        return ( is_array ( $buffer) ? $buffer : false);
      }
    }
  }

  /**
   * Check for fixed line network
   */
  $prefixes = array (
    "899" => array ( "area" => "", "operator" => "Velton.Telecom LLC"),
    "897" => array ( "area" => "", "operator" => "Kyivstar LLC"),
    "894" => array ( "area" => "", "operator" => "Atlantis Telecom LLC"),
    "893" => array ( "area" => "", "operator" => "T.R. Communication LLC"),
    "892" => array ( "area" => "", "operator" => "Ukrtelecom JSC"),
    "891" => array ( "area" => "", "operator" => "Datagroup PrJSC"),
    "48" => array ( "area" => "Odessa", "operator" => ""),
    "46" => array ( "area" => "Chernihiv", "operator" => ""),
    "45" => array ( "area" => "Brovary", "operator" => ""),
    "44" => array ( "area" => "Kyiv", "operator" => ""),
    "32" => array ( "area" => "Lviv", "operator" => ""),
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 13)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "380", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ukraine", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . "-" . substr ( $parameters["Number"], 9, 2) . "-" . substr ( $parameters["Number"], 11), "International" => "+380 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11))));
      } else {
        return ( is_array ( $buffer) ? $buffer : false);
      }
    }
  }

  /**
   * Check for Tollfree network
   */
  $prefixes = array (
    "80080" => "T.R. Coomunication LLC",
    "80075" => "Intertelecom LLC",
    "80060" => "Farlep-Invest PrJSC",
    "80050" => "Ukrtelecom JSC",
    "80040" => "VF Ukraine PrJSC",
    "80033" => "BINOTEL LLC",
    "80031" => "MAXNET LLC",
    "80030" => "Kyivstar PrJSC",
    "80021" => "Datagroup PrJSC",
    "80020" => "Lifecell LLC",
    "80010" => "Velton.Telecom LLC"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 13 || strlen ( $parameters["Number"]) == 14)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "380", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ukraine", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 8, 3) . "-" . substr ( $parameters["Number"], 11), "International" => "+380 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 8, 3) . " " . substr ( $parameters["Number"], 11))));
      } else {
        return ( is_array ( $buffer) ? $buffer : false);
      }
    }
  }

  /**
   * Check for PRN network
   */
  $prefixes = array (
    "90090" => "Microcom LLC",
    "90032" => "EURO-INFORM LLC",
    "90031" => "Datagroup PrJSC",
    "90030" => "Ukrtelecom JSC",
    "90025" => "T.R. Comunication LC",
    "90023" => "Audiotex LLC"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 13 || strlen ( $parameters["Number"]) == 14)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "380", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ukraine", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 8, 3) . "-" . substr ( $parameters["Number"], 11), "International" => "+380 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 8, 3) . " " . substr ( $parameters["Number"], 11))));
      } else {
        return ( is_array ( $buffer) ? $buffer : false);
      }
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Ukraine phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
