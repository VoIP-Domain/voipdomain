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
 * related to country database of Barbados.
 *
 * Reference: https://beltelecom.by/en/subscribers/phone-codes (January 2018)
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
 * E.164 Barbados country hook
 */
framework_add_filter ( "e164_identify_country_BRB", "e164_identify_country_BRB");

/**
 * E.164 Barbadoan area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BRB" (code for Barbados). This
 * hook will verify if phone number is valid, returning the* area code, area
 * name, phone number, others number related information and if possible, the
 * number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_BRB ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Barbados
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1246")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Barbados has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "883" => "Digicel (Barbados) Ltd.",
    "289" => "Cable & Wireless (Barbados) Ltd.",
    "287" => "Cable & Wireless (Barbados) Ltd.",
    "286" => "Cable & Wireless (Barbados) Ltd.",
    "285" => "Cable & Wireless (Barbados) Ltd.",
    "284" => "Cable & Wireless (Barbados) Ltd.",
    "283" => "Cable & Wireless (Barbados) Ltd.",
    "282" => "Cable & Wireless (Barbados) Ltd.",
    "281" => "Cable & Wireless (Barbados) Ltd.",
    "280" => "Cable & Wireless (Barbados) Ltd.",
    "259" => "Digicel (Barbados) Ltd.",
    "258" => "Digicel (Barbados) Ltd.",
    "257" => "Digicel (Barbados) Ltd.",
    "256" => "Digicel (Barbados) Ltd.",
    "255" => "Cable & Wireless (Barbados) Ltd.",
    "254" => "Cable & Wireless (Barbados) Ltd.",
    "253" => "Cable & Wireless (Barbados) Ltd.",
    "252" => "Cable & Wireless (Barbados) Ltd.",
    "251" => "Cable & Wireless (Barbados) Ltd.",
    "250" => "Cable & Wireless (Barbados) Ltd.",
    "249" => "Cable & Wireless (Barbados) Ltd.",
    "248" => "Cable & Wireless (Barbados) Ltd.",
    "247" => "Cable & Wireless (Barbados) Ltd.",
    "245" => "Cable & Wireless (Barbados) Ltd.",
    "244" => "Cable & Wireless (Barbados) Ltd.",
    "243" => "Cable & Wireless (Barbados) Ltd.",
    "242" => "Cable & Wireless (Barbados) Ltd.",
    "241" => "Cable & Wireless (Barbados) Ltd.",
    "240" => "Cable & Wireless (Barbados) Ltd.",
    "85" => "Digicel (Barbados) Ltd.",
    "84" => "Digicel (Barbados) Ltd.",
    "83" => "Digicel (Barbados) Ltd.",
    "82" => "Digicel (Barbados) Ltd.",
    "45" => "Sunbeach Communications Inc.",
    "26" => "Digicel (Barbados) Ltd.",
    "23" => "Cable & Wireless (Barbados) Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1246", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Barbados", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 246 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "963" => "Cable & Wireless (Barbados) Ltd.",
    "757" => "Cable & Wireless (Barbados) Ltd.",
    "753" => "Cable & Wireless (Barbados) Ltd.",
    "737" => "Cable & Wireless (Barbados) Ltd.",
    "736" => "Cable & Wireless (Barbados) Ltd.",
    "638" => "Cable & Wireless (Barbados) Ltd.",
    "573" => "TeleBarbados Inc.",
    "572" => "TeleBarbados Inc.",
    "571" => "TeleBarbados Inc.",
    "554" => "Cable & Wireless (Barbados) Ltd.",
    "520" => "Blue Communications Ltd.",
    "467" => "Cable & Wireless (Barbados) Ltd.",
    "444" => "Cable & Wireless (Barbados) Ltd.",
    "419" => "Cable & Wireless (Barbados) Ltd.",
    "418" => "Cable & Wireless (Barbados) Ltd.",
    "417" => "Cable & Wireless (Barbados) Ltd.",
    "416" => "Cable & Wireless (Barbados) Ltd.",
    "415" => "Cable & Wireless (Barbados) Ltd.",
    "414" => "Cable & Wireless (Barbados) Ltd.",
    "412" => "Cable & Wireless (Barbados) Ltd.",
    "410" => "Cable & Wireless (Barbados) Ltd.",
    "367" => "Cable & Wireless (Barbados) Ltd.",
    "292" => "Cable & Wireless (Barbados) Ltd.",
    "274" => "Cable & Wireless (Barbados) Ltd.",
    "273" => "Cable & Wireless (Barbados) Ltd.",
    "272" => "Cable & Wireless (Barbados) Ltd.",
    "271" => "Cable & Wireless (Barbados) Ltd.",
    "270" => "Cable & Wireless (Barbados) Ltd.",
    "229" => "Cable & Wireless (Barbados) Ltd.",
    "228" => "Cable & Wireless (Barbados) Ltd.",
    "227" => "Cable & Wireless (Barbados) Ltd.",
    "72" => "TeleBarbados Inc.",
    "54" => "Digicel (Barbados) Ltd.",
    "53" => "Digicel (Barbados) Ltd.",
    "43" => "Cable & Wireless (Barbados) Ltd.",
    "42" => "Cable & Wireless (Barbados) Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1246", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Barbados", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 246 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for FMC network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "919" => "Wiiscom Technologies Inc.",
    "918" => "Wiiscom Technologies Inc."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1246", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Barbados", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 246 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "31" => "Cable & Wireless (Barbados) Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1246", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Barbados", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 246 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Barbadoan phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
