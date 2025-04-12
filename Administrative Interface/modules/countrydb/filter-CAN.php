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
 * related to country database of Canada.
 *
 * Reference: https://www.itu.int/oth/T0202000025/en (2006-11-22)
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
 * E.164 Canada country hook
 */
framework_add_filter ( "e164_identify_country_CAN", "e164_identify_country_CAN");

/**
 * E.164 Canadian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "CAN" (code for Canada). This
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
function e164_identify_country_CAN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Canada
   */
  if ( substr ( $parameters["Number"], 0, 2) != "+1")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Canada has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "622",
    "600",
    "588",
    "577",
    "566",
    "544",
    "533",
    "523",
    "522",
    "521",
    "500"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 2, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Canada", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . "-" . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+1 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "905" => "Southern part of Province of Ontario surrounding Toronto",
    "902" => "Provinces of Nova Scotia and Prince Edward Island",
    "879" => "Province of Newfoundland and Labrador",
    "873" => "North & North-Western part of Province of Québec",
    "867" => "Northwest Territories, Nunavut and Yukon",
    "825" => "Province of Alberta",
    "819" => "North & North-Western part of Province of Québec",
    "807" => "North-Western part of Province of Ontario",
    "782" => "Provinces of Nova Scotia and Prince Edward Island",
    "780" => "Northern part of Province of Alberta",
    "778" => "Province of British Columbia",
    "709" => "Province of Newfoundland and Labrador",
    "705" => "North-Eastern part of Province of Ontario",
    "672" => "Province of British Columbia",
    "647" => "Part of Province of Ontario – Toronto area",
    "639" => "Province of Saskatchewan",
    "613" => "Eastern part of Province of Ontario",
    "604" => "Part of Province of British Columbia, including Lower Mainland (Vancouver)",
    "587" => "Province of Alberta",
    "581" => "North-Eastern part of Province of Québec",
    "579" => "Part of Province of Québec – Area surrounding Montréal",
    "548" => "South-Western part of Province of Ontario",
    "519" => "South-Western part of Province of Ontario",
    "514" => "Part of Province of Québec – Montréal area",
    "506" => "Province of New Brunswick",
    "450" => "Part of Province of Québec – Area surrounding Montréal",
    "438" => "Part of Province of Québec – Montréal area",
    "437" => "Part of Province of Ontario – Toronto area",
    "431" => "Province of Manitoba",
    "428" => "Province of New Brunswick",
    "418" => "North-Eastern part of Province of Québec",
    "416" => "Part of Province of Ontario – Toronto area",
    "403" => "Southern part of Province of Alberta",
    "367" => "North-Eastern part of Province of Québec",
    "365" => "Southern part of Province of Ontario surrounding Toronto",
    "343" => "Eastern part of Province of Ontario",
    "306" => "Province of Saskatchewan",
    "289" => "Southern part of Province of Ontario surrounding Toronto",
    "250" => "Part of Province of British Columbia including Vancouver Island & Mainland, excluding Lower Mainland",
    "249" => "North-Eastern part of Province of Ontario",
    "236" => "Province of British Columbia",
    "226" => "South-Western part of Province of Ontario",
    "204" => "Province of Manitoba"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 2, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Canada", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . "-" . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+1 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Canadian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
