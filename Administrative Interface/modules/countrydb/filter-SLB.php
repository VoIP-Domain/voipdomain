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
 * related to country database of Solomon Islands.
 *
 * Reference: https://www.itu.int/oth/T02020000BF/en (2017-06-08)
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
 * E.164 Solomon Islands country hook
 */
framework_add_filter ( "e164_identify_country_SLB", "e164_identify_country_SLB");

/**
 * E.164 Solomon Islands area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "SLB" (code for
 * Solomon Islands). This hook will verify if phone number is valid, returning
 * the area code, area name, phone number, others number related information and
 * if possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_SLB ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Solomon Islands
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+677")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Solomon Islands has 9 or 11 digits E.164 format
   */
  if ( ! ( strlen ( $parameters["Number"]) == 9 || strlen ( $parameters["Number"]) == 11))
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "7469" => "Solomon Telekon Company Ltd.",
    "7468" => "Solomon Telekon Company Ltd.",
    "7467" => "Solomon Telekon Company Ltd.",
    "7466" => "Solomon Telekon Company Ltd.",
    "7465" => "Solomon Telekon Company Ltd.",
    "7464" => "Solomon Telekon Company Ltd.",
    "7459" => "Solomon Telekon Company Ltd.",
    "7458" => "Solomon Telekon Company Ltd.",
    "7457" => "Solomon Telekon Company Ltd.",
    "7456" => "Solomon Telekon Company Ltd.",
    "7455" => "Solomon Telekon Company Ltd.",
    "7454" => "Solomon Telekon Company Ltd.",
    "7449" => "Solomon Telekon Company Ltd.",
    "7447" => "Solomon Telekon Company Ltd.",
    "7446" => "Solomon Telekon Company Ltd.",
    "7445" => "Solomon Telekon Company Ltd.",
    "7444" => "Solomon Telekon Company Ltd.",
    "7443" => "Solomon Telekon Company Ltd.",
    "7442" => "Solomon Telekon Company Ltd.",
    "7441" => "Solomon Telekon Company Ltd.",
    "7440" => "Solomon Telekon Company Ltd.",
    "998" => "Smile Ltd.",
    "997" => "Smile Ltd.",
    "996" => "Smile Ltd.",
    "995" => "Smile Ltd.",
    "994" => "Smile Ltd.",
    "993" => "Smile Ltd.",
    "992" => "Smile Ltd.",
    "991" => "Smile Ltd.",
    "990" => "Smile Ltd.",
    "989" => "Smile Ltd.",
    "987" => "Smile Ltd.",
    "986" => "Smile Ltd.",
    "985" => "Smile Ltd.",
    "984" => "Smile Ltd.",
    "983" => "Smile Ltd.",
    "982" => "Smile Ltd.",
    "981" => "Smile Ltd.",
    "980" => "Smile Ltd.",
    "979" => "Smile Ltd.",
    "978" => "Smile Ltd.",
    "976" => "Smile Ltd.",
    "975" => "Smile Ltd.",
    "974" => "Smile Ltd.",
    "973" => "Smile Ltd.",
    "972" => "Smile Ltd.",
    "971" => "Smile Ltd.",
    "959" => "Smile Ltd.",
    "958" => "Smile Ltd.",
    "957" => "Smile Ltd.",
    "956" => "Smile Ltd.",
    "956" => "Smile Ltd.",
    "954" => "Smile Ltd.",
    "953" => "Smile Ltd.",
    "952" => "Smile Ltd.",
    "951" => "Smile Ltd.",
    "950" => "Smile Ltd.",
    "932" => "Satsol Ltd.",
    "931" => "Satsol Ltd.",
    "930" => "Satsol Ltd.",
    "929" => "Satsol Ltd.",
    "928" => "Satsol Ltd.",
    "927" => "Satsol Ltd.",
    "926" => "Satsol Ltd.",
    "925" => "Satsol Ltd.",
    "924" => "Satsol Ltd.",
    "923" => "Satsol Ltd.",
    "921" => "Satsol Ltd.",
    "920" => "Satsol Ltd.",
    "919" => "Satsol Ltd.",
    "918" => "Satsol Ltd.",
    "917" => "Satsol Ltd.",
    "916" => "Satsol Ltd.",
    "915" => "Satsol Ltd.",
    "914" => "Satsol Ltd.",
    "913" => "Satsol Ltd.",
    "912" => "Satsol Ltd.",
    "794" => "Solomon Telekon Company Ltd.",
    "793" => "Solomon Telekon Company Ltd.",
    "792" => "Solomon Telekon Company Ltd.",
    "791" => "Solomon Telekon Company Ltd.",
    "790" => "Solomon Telekon Company Ltd.",
    "759" => "Solomon Telekon Company Ltd.",
    "758" => "Solomon Telekon Company Ltd.",
    "757" => "Solomon Telekon Company Ltd.",
    "756" => "Solomon Telekon Company Ltd.",
    "755" => "Solomon Telekon Company Ltd.",
    "752" => "Solomon Telekon Company Ltd.",
    "750" => "Solomon Telekon Company Ltd.",
    "749" => "Solomon Telekon Company Ltd.",
    "748" => "Solomon Telekon Company Ltd.",
    "747" => "Solomon Telekon Company Ltd.",
    "743" => "Solomon Telekon Company Ltd.",
    "742" => "Solomon Telekon Company Ltd.",
    "741" => "Solomon Telekon Company Ltd.",
    "740" => "Solomon Telekon Company Ltd.",
    "730" => "Solomon Telekon Company Ltd.",
    "96" => "Smile Ltd.",
    "94" => "Smile Ltd.",
    "89" => "Bemobile (SL) Ltd.",
    "88" => "Bemobile (SL) Ltd.",
    "87" => "Bemobile (SL) Ltd.",
    "86" => "Bemobile (SL) Ltd.",
    "85" => "Bemobile (SL) Ltd.",
    "84" => "Bemobile (SL) Ltd.",
    "78" => "Solomon Telekon Company Ltd.",
    "77" => "Solomon Telekon Company Ltd.",
    "76" => "Solomon Telekon Company Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "677", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Solomon Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+677 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with no NDC and 5 digits SN
   */
  $prefixes = array (
    "176" => "Bemobile (SL) Ltd.",
    "175" => "Bemobile (SL) Ltd.",
    "174" => "Bemobile (SL) Ltd.",
    "173" => "Bemobile (SL) Ltd.",
    "172" => "Bemobile (SL) Ltd.",
    "171" => "Bemobile (SL) Ltd.",
    "170" => "Bemobile (SL) Ltd.",
    "63" => "Solomon Telekon Company Ltd.",
    "62" => "Solomon Telekon Company Ltd.",
    "61" => "Solomon Telekon Company Ltd.",
    "60" => "Solomon Telekon Company Ltd.",
    "53" => "Solomon Telekon Company Ltd.",
    "50" => "Solomon Telekon Company Ltd.",
    "42" => "Solomon Telekon Company Ltd.",
    "41" => "Solomon Telekon Company Ltd.",
    "40" => "Solomon Telekon Company Ltd.",
    "19" => "Solomon Telekon Company Ltd.",
    "16" => "Solomon Telekon Company Ltd.",
    "15" => "Solomon Telekon Company Ltd.",
    "14" => "Solomon Telekon Company Ltd.",
    "3" => "Solomon Telekon Company Ltd.",
    "2" => "Solomon Telekon Company Ltd."
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 9 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "677", "NDC" => "", "Country" => "Solomon Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+677 " . substr ( $parameters["Number"], 4))));
    }
  }

 /**
   * Check for VoIP network with no NDC and 5 digits SN
   */
  $prefixes = array (
    "52" => "Solomon Telekon Company Ltd.",
    "51" => "Solomon Telekon Company Ltd."
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 9 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "677", "NDC" => "", "Country" => "Solomon Islands", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4), "International" => "+677 " . substr ( $parameters["Number"], 4))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Solomon Islands phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
