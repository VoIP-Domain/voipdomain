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
 * related to country database of Ghana.
 *
 * Reference: https://www.itu.int/oth/T0202000052/en (2018-03-29)
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
 * E.164 Ghana country hook
 */
framework_add_filter ( "e164_identify_country_GHA", "e164_identify_country_GHA");

/**
 * E.164 Ghana area number identification hook. This hook is an e164_identify sub
 * hook, called when the ISO3166 Alpha3 are "GHA" (code for Ghana). This hook
 * will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_GHA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Ghana
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+233")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Ghana has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "2540" => "Comsys Ghana Limited",
    "561" => "Airtel",
    "560" => "Airtel",
    "558" => "Scancom Ghana Limited (MTN)",
    "557" => "Scancom Ghana Limited (MTN)",
    "556" => "Scancom Ghana Limited (MTN)",
    "555" => "Scancom Ghana Limited (MTN)",
    "554" => "Scancom Ghana Limited (MTN)",
    "553" => "Scancom Ghana Limited (MTN)",
    "57" => "Millicom (Tigo)",
    "54" => "Scancom Ghana Limited (MTN)",
    "50" => "GT-Vodafone (Ghana)",
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "233", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Ghana", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+233 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for toll free network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "800"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 9)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "233", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Ghana", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TOLL_FREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+233 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "34292" => array ( "area" => "Eastern", "city" => "Akim Oda"),
    "3920" => array ( "area" => "Upper West", "city" => "Wa"),
    "3822" => array ( "area" => "Upper East", "city" => "Bawku"),
    "3821" => array ( "area" => "Upper East", "city" => "Navrongo"),
    "3820" => array ( "area" => "Upper East", "city" => "Bokgatanga"),
    "3726" => array ( "area" => "Northern", "city" => "Salaga"),
    "3725" => array ( "area" => "Northern", "city" => "Bole"),
    "3724" => array ( "area" => "Northern", "city" => "Yendi"),
    "3723" => array ( "area" => "Northern", "city" => "Damango"),
    "3722" => array ( "area" => "Northern", "city" => "Buipe"),
    "3721" => array ( "area" => "Northern", "city" => "Walewale"),
    "3720" => array ( "area" => "Northern", "city" => "Tamale"),
    "3626" => array ( "area" => "Volta", "city" => "Keta & Akatsi"),
    "3625" => array ( "area" => "Volta", "city" => "Denu / Aflao"),
    "3624" => array ( "area" => "Volta", "city" => "Kete-Krachi"),
    "3623" => array ( "area" => "Volta", "city" => "Kpandu"),
    "3622" => array ( "area" => "Volta", "city" => "Hohoe"),
    "3621" => array ( "area" => "Volta", "city" => "Amedzofe"),
    "3620" => array ( "area" => "Volta", "city" => "Ho"),
    "3527" => array ( "area" => "Brong Ahafo", "city" => "Yeji"),
    "3526" => array ( "area" => "Brong Ahafo", "city" => "Atebubu"),
    "3525" => array ( "area" => "Brong Ahafo", "city" => "Techiman"),
    "3524" => array ( "area" => "Brong Ahafo", "city" => "Wenchi"),
    "3523" => array ( "area" => "Brong Ahafo", "city" => "Dormaa Ahenkro"),
    "3522" => array ( "area" => "Brong Ahafo", "city" => "Berekum"),
    "3521" => array ( "area" => "Brong Ahafo", "city" => "Bechem"),
    "3520" => array ( "area" => "Brong Ahafo", "city" => "Sunyani"),
    "3431" => array ( "area" => "Eastern", "city" => "Nkawkaw"),
    "3430" => array ( "area" => "Eastern", "city" => "Akosombo"),
    "3428" => array ( "area" => "Eastern", "city" => "Aburi"),
    "3427" => array ( "area" => "Eastern", "city" => "Akwapim Mampong"),
    "3426" => array ( "area" => "Eastern", "city" => "Asamankese"),
    "3425" => array ( "area" => "Eastern", "city" => "Suhum"),
    "3424" => array ( "area" => "Eastern", "city" => "Donkorkrom"),
    "3423" => array ( "area" => "Eastern", "city" => "Mpraeso"),
    "3421" => array ( "area" => "Eastern", "city" => "Nsawam"),
    "3420" => array ( "area" => "Eastern", "city" => "Koforidua"),
    "3323" => array ( "area" => "Central", "city" => "Winneba"),
    "3322" => array ( "area" => "Central", "city" => "Dunkwa"),
    "3321" => array ( "area" => "Central", "city" => "Cape Coast"),
    "3320" => array ( "area" => "Central", "city" => "Swedru"),
    "3225" => array ( "area" => "Ashanti", "city" => "Obuasi"),
    "3224" => array ( "area" => "Ashanti", "city" => "Bekwai"),
    "3223" => array ( "area" => "Ashanti", "city" => "Ejura"),
    "3222" => array ( "area" => "Ashanti", "city" => "Ashanti Mampong"),
    "3221" => array ( "area" => "Ashanti", "city" => "Konongo"),
    "3220" => array ( "area" => "Ashanti", "city" => "Kumasi"),
    "3126" => array ( "area" => "Western", "city" => "Enchi"),
    "3125" => array ( "area" => "Western", "city" => "Samreboi"),
    "3124" => array ( "area" => "Western", "city" => "Asankragwa"),
    "3123" => array ( "area" => "Western", "city" => "Tarkwa"),
    "3122" => array ( "area" => "Western", "city" => "Elubo"),
    "3121" => array ( "area" => "Western", "city" => "Axim"),
    "3120" => array ( "area" => "Western", "city" => "Takoradi"),
    "3035" => array ( "area" => "Greater Accra", "city" => "Ada"),
    "303" => array ( "area" => "Greater Accra", "city" => "Tema"),
    "302" => array ( "area" => "Greater Accra", "city" => "Accra"),
    "39" => array ( "area" => "Upper West", "city" => ""),
    "38" => array ( "area" => "Upper East", "city" => ""),
    "37" => array ( "area" => "Northern", "city" => ""),
    "36" => array ( "area" => "Volta", "city" => ""),
    "35" => array ( "area" => "Brong Ahafo", "city" => ""),
    "34" => array ( "area" => "Eastern", "city" => ""),
    "33" => array ( "area" => "Central", "city" => ""),
    "32" => array ( "area" => "Ashanti", "city" => ""),
    "31" => array ( "area" => "Western", "city" => ""),
    "30" => array ( "area" => "Greater Accra", "city" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "233", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Ghana", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+233 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Ghana phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
