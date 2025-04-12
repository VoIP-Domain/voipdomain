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
 * related to country database of Bonaire, Sint Eustatius and Saba.
 *
 * Reference: https://www.itu.int/oth/T02020000F8/en (2017-01-13)
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
 * E.164 Bonaire, Sint Eustatius and Saba country hook
 */
framework_add_filter ( "e164_identify_country_BES", "e164_identify_country_BES");

/**
 * E.164 Bonaire, Sint Eustatius and Saba area number identification hook. This
 * hook is an e164_identify sub hook, called when the ISO3166 Alpha3 are "BES"
 * (code for Bonaire, Sint Eustatius and Saba). This hook will verify if phone
 * number is valid, returning the area code, area name, phone number, others
 * number related information and if possible, the number type (mobile,
 * landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_BES ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Bonaire, Sint Eustatius and Saba
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+599")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Bonaire, Sint Eustatius and Saba has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * First, check for the country
   */
  switch ( substr ( $parameters["Number"], 4, 1))
  {
    case "7":
      $country = "Bonaire";
      break;
    case "4":
      $country = "Saba";
      break;
    case "3":
      $country = "Sint Eustatius";
      break;
    default:
      return ( is_array ( $buffer) ? $buffer : false);
      break;
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "4169" => "Satel N.V.",
    "4168" => "WICC N.V.",
    "4167" => "WICC N.V.",
    "4166" => "WICC N.V.",
    "4165" => "WICC N.V.",
    "4164" => "WICC N.V.",
    "4161" => "Satel N.V.",
    "3198" => "WICC N.V.",
    "3197" => "WICC N.V.",
    "3195" => "WICC N.V.",
    "3194" => "WICC N.V.",
    "3191" => "WICC N.V.",
    "3188" => "Eutel N.V.",
    "3187" => "Eutel N.V.",
    "3186" => "Eutel N.V.",
    "3185" => "Eutel N.V.",
    "3184" => "Eutel N.V.",
    "3181" => "Eutel N.V.",
    "796" => "Setel N.V.",
    "795" => "Setel N.V.",
    "790" => "Setel N.V.",
    "777" => "Telbo N.V.",
    "770" => "Telbo N.V.",
    "701" => "Antillano Por N.V.",
    "700" => "Antillano Por N.V.",
    "78" => "Antillano Por N.V.",
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "599", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => $country, "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+599 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "4170" => "Satel N.V.",
    "4163" => "Satel N.V.",
    "4162" => "Satel N.V.",
    "4160" => "Satel N.V.",
    "3183" => "Eutel N.V.",
    "3182" => "Eutel N.V.",
    "3180" => "Eutel N.V.",
    "750" => "Flamingo TV and Bonaire B.V.",
    "718" => "Telbo N.V.",
    "717" => "Telbo N.V.",
    "715" => "Telbo N.V."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "599", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => $country, "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+599 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Bonaire, Sint Eustatius
   * and Saba phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
