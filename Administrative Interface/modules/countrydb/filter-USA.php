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
 * related to country database of United States of America.
 *
 * Reference: https://www.itu.int/oth/T02020000DE/en (2006-11-22)
 *            https://www.nationalpooling.com/reports/region/AllBlocksAugmentedReport.zip (2023-02-13)
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
 * E.164 United States of America country hook
 */
framework_add_filter ( "e164_identify_country_USA", "e164_identify_country_USA");

/**
 * E.164 North American area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "USA" (code for
 * United States of America). This hook will verify if phone number is valid,
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
function e164_identify_country_USA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America
   */
  if ( substr ( $parameters["Number"], 0, 2) != "+1")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Our check are splitted into NDC due to large number of entries
   */
  if ( ! framework_has_hook ( "e164_identify_NANPA_" . substr ( $parameters["Number"], 2, 3)) && is_readable ( "filter-NANPA-" . substr ( $parameters["Number"], 2, 3) . ".php"))
  {
    require_once ( "filter-NANPA-" . substr ( $parameters["Number"], 2, 3) . ".php");
    $data = filters_call ( "e164_identify_NANPA_" . substr ( $parameters["Number"], 2, 3), $parameters);
    if ( is_array ( $data) && sizeof ( $data) != 0)
    {
      return array_merge_recursive ( is_array ( $buffer) ? $buffer : array (), array ( "CC" => "1", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "United States of America", "Area" => $data["Area"], "City" => $data["City"], "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE + VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 2, 3) . ") " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+1 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for toll-free numbers
   */
  switch ( substr ( $parameters["Number"], 2, 3))
  {
    case "800":
    case "822":
    case "833":
    case "844":
    case "855":
    case "866":
    case "877":
    case "880":
    case "881":
    case "882":
    case "883":
    case "884":
    case "885":
    case "886":
    case "887":
    case "888":
    case "889":
      return array_merge_recursive ( is_array ( $buffer) ? $buffer : array (), array ( "CC" => "1", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "United States of America", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 2, 3) . ") " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+1 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5))));
      break;
  }

  /**
   * Check for premium rate numbers
   */
  if ( substr ( $parameters["Number"], 2, 3) == "900")
  {
    return array_merge_recursive ( is_array ( $buffer) ? $buffer : array (), array ( "CC" => "1", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "United States of America", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 2, 3) . ") " . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+1 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5))));
  }

  /**
   * If reached here, number wasn't identified as a valid Honduras phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
