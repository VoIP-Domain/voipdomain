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
 * related to country database of Czech Republic.
 *
 * Reference: https://www.itu.int/oth/T0202000035/en (2012-06-12)
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
 * E.164 Czech Republic country hook
 */
framework_add_filter ( "e164_identify_country_CZE", "e164_identify_country_CZE");

/**
 * E.164 Czech Republic area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "CZE" (code for
 * Czech Republic). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_CZE ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Czech Republic
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+420")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Czech Republic has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "608" => "Vodafone Czech Republic",
    "607" => "Telefónica O2 Czech Republic",
    "606" => "Telefónica O2 Czech Republic",
    "605" => "T-Mobile",
    "604" => "T-Mobile",
    "603" => "T-Mobile",
    "602" => "Telefónica O2 Czech Republic",
    "601" => "Telefónica O2 Czech Republic",
    "705" => "Bleskmobil's",
    "704" => "Bleskmobil's",
    "703" => "Bleskmobil's",
    "702" => "Bleskmobil's",
    "79" => "U:Fon (Mobikom)",
    "77" => "Vodafone Czech Republic",
    "73" => "T-Mobile",
    "72" => "Telefónica O2 Czech Republic"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "420", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Czech Republic", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+420 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "59" => "Moravskoslezsky Region",
    "58" => "Olomoucky Region",
    "57" => "Zlinsky Region",
    "56" => "Vysocina Region",
    "55" => "Moravskoslezsky Region",
    "54" => "Jihomoravsky Region",
    "53" => "Jihomoravsky Region",
    "51" => "Jihomoravsky Region",
    "49" => "Kralovehradecky Region",
    "48" => "Liberecky Region",
    "47" => "Ustecky Region",
    "46" => "Pardubicky Region",
    "41" => "Ustecky Region",
    "39" => "Jihocesky Region",
    "38" => "Jihocesky Region",
    "37" => "Plzensky Region",
    "35" => "Karlovarsky Region",
    "32" => "Capital Praha and Stredocesky Region",
    "31" => "Capital Praha and Stredocesky Region",
    "2" => "Capital Praha and Stredocesky Region"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "420", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Czech Republic", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+420 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for Premium Rate Numbers network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "909",
    "908",
    "906",
    "905",
    "900"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "420", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Czech Republic", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+420 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for VoIP network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 3) == "910")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "420", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Czech Republic", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+420 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
  }

  /**
   * If reached here, number wasn't identified as a valid Czech Republic phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
