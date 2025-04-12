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
 * related to country database of Denmark.
 *
 * Reference: https://www.itu.int/oth/T0202000038/en (2016-11-15)
 * Reference: https://ens.dk/sites/ens.dk/files/Tele/nummerplanen_2016_november.pdf
 *
 * Note: There's no ITU-T specification to Denmark. There's only a link to a PDF
 *       page in Danish. This module could have many flaws, and probably will
 *       fail on a large number of valid numbers. If you've better information,
 *       please fix this file and submit to repository.
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
 * E.164 Denmark country hook
 */
framework_add_filter ( "e164_identify_country_DNK", "e164_identify_country_DNK");

/**
 * E.164 Denmark area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "DNK" (code for Denmark). This
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
function e164_identify_country_DNK ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Denmark
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+45")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Denmark has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "93",
    "92",
    "91",
    "81",
    "71",
    "61",
    "60",
    "53",
    "52",
    "51",
    "50",
    "42",
    "41",
    "40",
    "31",
    "30",
    "2"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "45", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Denmark", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 2) . "-" . substr ( $parameters["Number"], 7, 2) . "-" . substr ( $parameters["Number"], 9), "International" => "+45 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "99",
    "98",
    "97",
    "96",
    "89",
    "88",
    "87",
    "86",
    "82",
    "79",
    "78",
    "77",
    "76",
    "75",
    "74",
    "73",
    "72",
    "69",
    "66",
    "65",
    "64",
    "63",
    "62",
    "59",
    "58",
    "57",
    "56",
    "55",
    "54",
    "49",
    "48",
    "47",
    "46",
    "45",
    "44",
    "43",
    "39",
    "38",
    "36",
    "35",
    "34",
    "33",
    "32"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "45", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Denmark", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 2) . "-" . substr ( $parameters["Number"], 7, 2) . "-" . substr ( $parameters["Number"], 9), "International" => "+45 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for Premium Rate Numbers network with 2 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 2) == "90")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "45", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Denmark", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 2) . "-" . substr ( $parameters["Number"], 7, 2) . "-" . substr ( $parameters["Number"], 9), "International" => "+45 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Denmark phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
