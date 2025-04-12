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
 * related to country database of Togo.
 *
 * Reference: https://www.itu.int/oth/T02020000D1/en (2021-02-25)
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
 * E.164 Togo country hook
 */
framework_add_filter ( "e164_identify_country_TGO", "e164_identify_country_TGO");

/**
 * E.164 Togo area number identification hook. This hook is an e164_identify sub
 * hook, called when the ISO3166 Alpha3 are "TGO" (code for Togo). This hook will
 * verify if phone number is valid, returning the area code, area name, phone
 * number, others number related information and if possible, the number type
 * (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_TGO ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Togo
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+228")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Togo has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 or 3 digits NDC and 5 or 6 digits SN
   */
  $prefixes = array (
    "799" => "Atlantique Telecom Togo",
    "798" => "Atlantique Telecom Togo",
    "797" => "Atlantique Telecom Togo",
    "796" => "Atlantique Telecom Togo",
    "795" => "Atlantique Telecom Togo",
    "794" => "Atlantique Telecom Togo",
    "793" => "Atlantique Telecom Togo",
    "792" => "",
    "791" => "",
    "790" => "",
    "709" => "",
    "708" => "",
    "707" => "",
    "706" => "",
    "705" => "Togo Cellulaire",
    "704" => "Togo Cellulaire",
    "703" => "Togo Cellulaire",
    "702" => "Togo Cellulaire",
    "701" => "Togo Cellulaire",
    "700" => "Togo Cellulaire",
    "99" => "Atlantique Telecom Togo",
    "98" => "Atlantique Telecom Togo",
    "97" => "Atlantique Telecom Togo",
    "96" => "Atlantique Telecom Togo",
    "95" => "",
    "94" => "",
    "93" => "Togo Cellulaire",
    "92" => "Togo Cellulaire",
    "91" => "Togo Cellulaire",
    "90" => "Togo Cellulaire",
    "78" => "",
    "77" => "",
    "76" => "",
    "75" => "",
    "74" => "",
    "73" => "",
    "72" => "",
    "71" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "228", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Togo", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+228 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed landline network with 3 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "279" => array ( "Area" => "Savannah region", "Operator" => ""),
    "278" => array ( "Area" => "Savannah region", "Operator" => ""),
    "277" => array ( "Area" => "Savannah region", "Operator" => "Togo Telecom"),
    "276" => array ( "Area" => "Savannah region", "Operator" => ""),
    "275" => array ( "Area" => "Savannah region", "Operator" => ""),
    "274" => array ( "Area" => "Savannah region", "Operator" => ""),
    "273" => array ( "Area" => "Savannah region", "Operator" => ""),
    "272" => array ( "Area" => "Savannah region", "Operator" => ""),
    "271" => array ( "Area" => "Savannah region", "Operator" => ""),
    "270" => array ( "Area" => "Savannah region", "Operator" => ""),
    "269" => array ( "Area" => "Kara region", "Operator" => ""),
    "268" => array ( "Area" => "Kara region", "Operator" => ""),
    "267" => array ( "Area" => "Kara region", "Operator" => ""),
    "266" => array ( "Area" => "Kara region", "Operator" => "Togo Telecom"),
    "265" => array ( "Area" => "Kara region", "Operator" => ""),
    "264" => array ( "Area" => "Kara region", "Operator" => ""),
    "263" => array ( "Area" => "Kara region", "Operator" => ""),
    "262" => array ( "Area" => "Kara region", "Operator" => ""),
    "261" => array ( "Area" => "Kara region", "Operator" => ""),
    "260" => array ( "Area" => "Kara region", "Operator" => ""),
    "259" => array ( "Area" => "Central region", "Operator" => ""),
    "258" => array ( "Area" => "Central region", "Operator" => ""),
    "257" => array ( "Area" => "Central region", "Operator" => ""),
    "256" => array ( "Area" => "Central region", "Operator" => ""),
    "255" => array ( "Area" => "Central region", "Operator" => "Togo Telecom"),
    "254" => array ( "Area" => "Central region", "Operator" => ""),
    "253" => array ( "Area" => "Central region", "Operator" => ""),
    "252" => array ( "Area" => "Central region", "Operator" => ""),
    "252" => array ( "Area" => "Central region", "Operator" => ""),
    "251" => array ( "Area" => "Central region", "Operator" => ""),
    "250" => array ( "Area" => "Central region", "Operator" => ""),
    "249" => array ( "Area" => "Plateaux region", "Operator" => ""),
    "248" => array ( "Area" => "Plateaux region", "Operator" => ""),
    "247" => array ( "Area" => "Plateaux region", "Operator" => ""),
    "246" => array ( "Area" => "Plateaux region", "Operator" => ""),
    "245" => array ( "Area" => "Plateaux region", "Operator" => ""),
    "244" => array ( "Area" => "Plateaux region", "Operator" => "Togo Telecom"),
    "243" => array ( "Area" => "Plateaux region", "Operator" => ""),
    "242" => array ( "Area" => "Plateaux region", "Operator" => ""),
    "241" => array ( "Area" => "Plateaux region", "Operator" => ""),
    "240" => array ( "Area" => "Plateaux region", "Operator" => ""),
    "239" => array ( "Area" => "Maritime region", "Operator" => ""),
    "238" => array ( "Area" => "Maritime region", "Operator" => ""),
    "237" => array ( "Area" => "Maritime region", "Operator" => ""),
    "236" => array ( "Area" => "Maritime region", "Operator" => ""),
    "235" => array ( "Area" => "Maritime region", "Operator" => ""),
    "234" => array ( "Area" => "Maritime region", "Operator" => ""),
    "233" => array ( "Area" => "Maritime region", "Operator" => "Togo Telecom"),
    "232" => array ( "Area" => "Maritime region", "Operator" => ""),
    "231" => array ( "Area" => "Maritime region", "Operator" => ""),
    "230" => array ( "Area" => "Maritime region", "Operator" => ""),
    "229" => array ( "Area" => "Lomé", "Operator" => ""),
    "228" => array ( "Area" => "Lomé", "Operator" => ""),
    "227" => array ( "Area" => "Lomé", "Operator" => ""),
    "226" => array ( "Area" => "Lomé", "Operator" => "Togo Telecom"),
    "225" => array ( "Area" => "Lomé", "Operator" => "Togo Telecom"),
    "224" => array ( "Area" => "Lomé", "Operator" => ""),
    "223" => array ( "Area" => "Lomé", "Operator" => ""),
    "222" => array ( "Area" => "Lomé", "Operator" => "Togo Telecom"),
    "221" => array ( "Area" => "Lomé", "Operator" => ""),
    "220" => array ( "Area" => "Lomé", "Operator" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "228", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Togo", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10), "International" => "+228 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Togo phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
