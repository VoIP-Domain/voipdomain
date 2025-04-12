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
 * related to country database of Sint Maarten (Dutch part).
 *
 * Reference: https://www.itu.int/oth/T02020000F7/en (2012-05-18)
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
 * E.164 Sint Maarten (Dutch part) country hook
 */
framework_add_filter ( "e164_identify_country_SXM", "e164_identify_country_SXM");

/**
 * E.164 Sint Maarten (Dutch part) area number identification hook. This hook is
 * an e164_identify sub hook, called when the ISO3166 Alpha3 are "SXM" (code for
 * Sint Maarten (Dutch part)). This hook will verify if phone number is valid,
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
function e164_identify_country_SXM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Sint Maarten (Dutch part)
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1721")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Sint Maarten (Dutch part) has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "588" => "Radcomm",
    "587" => "Radcomm",
    "586" => "Radcomm",
    "585" => "Radcomm",
    "584" => "Radcomm",
    "581" => "Radcomm",
    "580" => "Radcomm",
    "559" => "Telcell",
    "557" => "Telcell",
    "556" => "Telcell",
    "554" => "Radcomm",
    "553" => "Radcomm",
    "550" => "Telcell",
    "529" => "Telcell",
    "528" => "Telcell",
    "527" => "Telcell",
    "526" => "Telcell",
    "524" => "Telcell",
    "523" => "Telcell",
    "522" => "Telcell",
    "521" => "Telcell",
    "520" => "Telcell"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1721", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Sint Maarten (Dutch part)", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1721 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "596" => array ( "Area" => "", "Operator" => "Smitcoms"),
    "595" => array ( "Area" => "", "Operator" => "Smitcoms"),
    "590" => array ( "Area" => "", "Operator" => "Antelecom"),
    "548" => array ( "Area" => "Cul de Sac, Ebenezer, South Reward, Betty’s Estate, Saunders", "Operator" => ""),
    "547" => array ( "Area" => "Dutch Quarter, Middle Region, Belvedere", "Operator" => ""),
    "546" => array ( "Area" => "", "Operator" => ""),
    "545" => array ( "Area" => "Simpson Bay, Beacon Hill, Maho, Cupecoy", "Operator" => ""),
    "544" => array ( "Area" => "Colebay, Pelican, Caybay", "Operator" => ""),
    "543" => array ( "Area" => "Philipsburg, Pointe Blanche, Guana Bay, Oyster Pond", "Operator" => ""),
    "542" => array ( "Area" => "Philipsburg, Pointe Blanche, Guana Bay, Oyster Pond", "Operator" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1721", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Sint Maarten (Dutch part)", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1721 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for FMCnetwork with 3 digits NDC and 4 digits SN
   */
  if ( substr ( $parameters["Number"], 5, 3) == "525")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1721", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Sint Maarten (Dutch part)", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1721 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * If reached here, number wasn't identified as a valid Sint Maarten (Dutch part) phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
