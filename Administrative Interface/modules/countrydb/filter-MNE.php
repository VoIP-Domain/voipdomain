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
 * related to country database of Montenegro.
 *
 * Reference: https://www.itu.int/oth/T02020000DA/en (2015-01-27)
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
 * E.164 Montenegro country hook
 */
framework_add_filter ( "e164_identify_country_MNE", "e164_identify_country_MNE");

/**
 * E.164 Montenegrian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MNE" (code for Montenegro). This
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
function e164_identify_country_MNE ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Montenegro
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+382")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Montenegro has 5 to 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 5 || strlen ( $parameters["Number"]) > 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDN and 6 digits SN
   */
  $prefixes = array (
    "69" => array ( "Min" => 5, "Operator" => "Telenor"),
    "68" => array ( "Min" => 4, "Operator" => "Mtel"),
    "67" => array ( "Min" => 5, "Operator" => "Crnogorski Telekom"),
    "66" => array ( "Min" => 5, "Operator" => "Crnogorski Telekom"),
    "63" => array ( "Min" => 5, "Operator" => "Telenor"),
    "60" => array ( "Min" => 4, "Operator" => "Mtel")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) >= $data["Min"] && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "382", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Montenegro", "Area" => $area, "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+382 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "52" => "Pljevlja (Pljevlja, Žabljak)",
    "51" => "Berane (Berane, Andrijevica, Rožaje, Plav)",
    "50" => "Bijelo Polje (Bijelo Polje, Mojkovac)",
    "41" => "Cetinje",
    "40" => "Nikšić (Nikšić, Šavnik, Plužine)",
    "33" => "Budva",
    "32" => "Kotor (Kotor, Tivat)",
    "31" => "Herceg Novi",
    "30" => "Bar (Bar, Ulcinj)",
    "20" => "Podgorica (Podgorica, Danilovgrad, Kolašin)"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "382", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Montenegro", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+382 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 6 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 12 && substr ( $parameters["Number"], 4, 2) == "78")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "382", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Montenegro", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+382 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for Toll Free network with 2 digits NDC and 6 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 12 && substr ( $parameters["Number"], 4, 2) == "80")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "382", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Montenegro", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+382 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * Check for PRN network with 2 digits NDC and 6 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 12 && ( substr ( $parameters["Number"], 4, 2) == "94" || substr ( $parameters["Number"], 4, 2) == "95"))
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "382", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Montenegro", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+382 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Montenegrian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
