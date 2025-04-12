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
 * related to country database of Liechtenstein.
 *
 * Reference: https://www.itu.int/oth/T020200007B/en (2014-04-23)
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
 * E.164 Liechtenstein country hook
 */
framework_add_filter ( "e164_identify_country_LIE", "e164_identify_country_LIE");

/**
 * E.164 Liechtenstein area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "LIE" (code for
 * Liechtenstein). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_LIE ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Liechtenstein
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+423")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 7 digits SN
   */
  $prefixes = array (
    "799" => "Telecom Liechtenstein AG",
    "798" => "Telecom Liechtenstein AG",
    "797" => "Telecom Liechtenstein AG",
    "796" => "Telecom Liechtenstein AG",
    "795" => "Telecom Liechtenstein AG",
    "794" => "Telecom Liechtenstein AG",
    "793" => "Telecom Liechtenstein AG",
    "792" => "Telecom Liechtenstein AG",
    "791" => "Telecom Liechtenstein AG",
    "790" => "Telecom Liechtenstein AG",
    "789" => "Salt (Liechtenstein) AG",
    "788" => "Salt (Liechtenstein) AG",
    "787" => "Salt (Liechtenstein) AG",
    "786" => "Salt (Liechtenstein) AG",
    "785" => "Salt (Liechtenstein) AG",
    "784" => "Salt (Liechtenstein) AG",
    "783" => "Salt (Liechtenstein) AG",
    "782" => "Salt (Liechtenstein) AG",
    "781" => "Salt (Liechtenstein) AG",
    "780" => "Salt (Liechtenstein) AG",
    "779" => "Swisscom (Schweiz) AG",
    "778" => "Swisscom (Schweiz) AG",
    "777" => "Swisscom (Schweiz) AG",
    "776" => "Swisscom (Schweiz) AG",
    "775" => "Swisscom (Schweiz) AG",
    "774" => "Swisscom (Schweiz) AG",
    "773" => "Swisscom (Schweiz) AG",
    "772" => "Swisscom (Schweiz) AG",
    "771" => "Swisscom (Schweiz) AG",
    "770" => "Swisscom (Schweiz) AG",
    "742" => "First Mobile AG"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "423", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Liechtenstein", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+423 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with no NDC and 7 digits SN
   */
  $prefixes = array (
    "399" => "Telecom Liechtenstein AG",
    "396" => "Telecom Liechtenstein AG",
    "392" => "Telecom Liechtenstein AG",
    "390" => "Telecom Liechtenstein AG",
    "388" => "Telecom Liechtenstein AG",
    "384" => "Telecom Liechtenstein AG",
    "380" => "Telecom Liechtenstein AG",
    "377" => "Telecom Liechtenstein AG",
    "376" => "Telecom Liechtenstein AG",
    "375" => "Telecom Liechtenstein AG",
    "373" => "Telecom Liechtenstein AG",
    "371" => "Telecom Liechtenstein AG",
    "370" => "Telecom Liechtenstein AG",
    "340" => "Voxphone AG",
    "333" => "Supranet AG",
    "296" => "Telecom Liechtenstein AG",
    "268" => "Telecom Liechtenstein AG",
    "267" => "Telecom Liechtenstein AG",
    "265" => "Telecom Liechtenstein AG",
    "264" => "Telecom Liechtenstein AG",
    "263" => "Telecom Liechtenstein AG",
    "262" => "Telecom Liechtenstein AG",
    "260" => "Telecom Liechtenstein AG",
    "239" => "Telecom Liechtenstein AG",
    "238" => "Telecom Liechtenstein AG",
    "237" => "Telecom Liechtenstein AG",
    "236" => "Telecom Liechtenstein AG",
    "235" => "Telecom Liechtenstein AG",
    "234" => "Telecom Liechtenstein AG",
    "233" => "Telecom Liechtenstein AG",
    "232" => "Telecom Liechtenstein AG",
    "231" => "Telecom Liechtenstein AG",
    "230" => "Telecom Liechtenstein AG",
    "222" => "Telecom Liechtenstein AG",
    "217" => "Telecom Liechtenstein AG",
    "201" => "Montan Telecom AG"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "423", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Liechtenstein", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+423 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for voicemail network with no NDC and 9 digits SN
   */
  $prefixes = array (
    "69789" => "Salt (Liechtenstein) AG",
    "69788" => "Salt (Liechtenstein) AG",
    "69787" => "Salt (Liechtenstein) AG",
    "69786" => "Salt (Liechtenstein) AG",
    "69785" => "Salt (Liechtenstein) AG",
    "69784" => "Salt (Liechtenstein) AG",
    "69783" => "Salt (Liechtenstein) AG",
    "69782" => "Salt (Liechtenstein) AG",
    "69781" => "Salt (Liechtenstein) AG",
    "69780" => "Salt (Liechtenstein) AG",
    "69779" => "Swisscom (Schweiz) AG",
    "69778" => "Swisscom (Schweiz) AG",
    "69777" => "Swisscom (Schweiz) AG",
    "69776" => "Swisscom (Schweiz) AG",
    "69775" => "Swisscom (Schweiz) AG",
    "69774" => "Swisscom (Schweiz) AG",
    "69773" => "Swisscom (Schweiz) AG",
    "69772" => "Swisscom (Schweiz) AG",
    "69771" => "Swisscom (Schweiz) AG",
    "69770" => "Swisscom (Schweiz) AG",
    "69742" => "First Mobile AG"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "423", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Liechtenstein", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_VOICEMAIL, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+423 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for international mobile network with no NDC and 9 digits SN
   */
  $prefixes = array (
    "6639" => "Emnify GmbH",
    "6638" => "Emnify GmbH",
    "6637" => "Emnify GmbH",
    "6629" => "Datamobile AG",
    "6628" => "Datamobile AG",
    "6627" => "Datamobile AG",
    "6626" => "Datamobile AG",
    "6620" => "Telecom Liechtenstein AG",
    "6610" => "DIMOCO Messaging AG",
    "6607" => "Telecom Liechtenstein AG",
    "6606" => "Telecom Liechtenstein AG",
    "6605" => "Telecom Liechtenstein AG",
    "6604" => "Telecom Liechtenstein AG",
    "6603" => "Telecom Liechtenstein AG",
    "6602" => "Telecom Liechtenstein AG",
    "6601" => "Telecom Liechtenstein AG",
    "6600" => "Telecom Liechtenstein AG",
    "6509" => "SORACOM LI, LTD.",
    "6508" => "SORACOM LI, LTD.",
    "6507" => "SORACOM LI, LTD.",
    "6506" => "SORACOM LI, LTD.",
    "6505" => "SORACOM LI, LTD.",
    "6504" => "SORACOM LI, LTD.",
    "6503" => "SORACOM LI, LTD.",
    "6502" => "SORACOM LI, LTD.",
    "6501" => "SORACOM LI, LTD.",
    "6500" => "SORACOM LI, LTD.",
    "6499" => "SORACOM LI, LTD.",
    "653" => "Cubic Aktiengesellschaft",
    "652" => "Cubic Aktiengesellschaft",
    "651" => "Cubic Aktiengesellschaft"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "423", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Liechtenstein", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+423 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Liechtenstein phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
