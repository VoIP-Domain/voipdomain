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
 * related to country database of Virgin Islands (British).
 *
 * Reference: https://www.itu.int/oth/T020200001E/en (2008-07-29)
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
 * E.164 Virgin Islands (British) country hook
 */
framework_add_filter ( "e164_identify_country_VGB", "e164_identify_country_VGB");

/**
 * E.164 Virgin Islands (British) area number identification hook. This hook is
 * an e164_identify sub hook, called when the ISO3166 Alpha3 are "VGB" (code for
 * Virgin Islands (British)). This hook will verify if phone number is valid,
 * returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_VGB ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Virgin Islands (British)
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1284")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Virgin Islands (British) has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "4969" => "CCT Global Communications",
    "4968" => "CCT Global Communications",
    "4967" => "CCT Global Communications",
    "4966" => "CCT Global Communications",
    "544" => "Cable & Wireless (BVI) Ltd.",
    "543" => "Cable & Wireless (BVI) Ltd.",
    "542" => "Cable & Wireless (BVI) Ltd.",
    "541" => "Cable & Wireless (BVI) Ltd.",
    "540" => "Cable & Wireless (BVI) Ltd.",
    "499" => "CCT Global Communications",
    "468" => "CCT Global Communications",
    "445" => "CCT Global Communications",
    "444" => "CCT Global Communications",
    "443" => "CCT Global Communications",
    "442" => "CCT Global Communications",
    "441" => "CCT Global Communications",
    "440" => "CCT Global Communications",
    "303" => "Digicel (BVI) Ltd.",
    "302" => "Digicel (BVI) Ltd.",
    "301" => "Digicel (BVI) Ltd.",
    "300" => "Digicel (BVI) Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1284", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Virgin Islands (British)", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 284 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "4965" => "Cable & Wireless (BVI) Ltd.",
    "4964" => "Cable & Wireless (BVI) Ltd.",
    "4963" => "Cable & Wireless (BVI) Ltd.",
    "4962" => "Cable & Wireless (BVI) Ltd.",
    "4961" => "Cable & Wireless (BVI) Ltd.",
    "4960" => "Cable & Wireless (BVI) Ltd.",
    "869" => "Cable & Wireless (BVI) Ltd.",
    "865" => "Cable & Wireless (BVI) Ltd.",
    "864" => "Cable & Wireless (BVI) Ltd.",
    "852" => "Cable & Wireless (BVI) Ltd.",
    "495" => "Cable & Wireless (BVI) Ltd.",
    "494" => "Cable & Wireless (BVI) Ltd.",
    "446" => "CCT Global Communications",
    "229" => "Cable & Wireless (BVI) Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1284", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Virgin Islands (British)", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 284 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Virgin Islands
   * (British) phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
