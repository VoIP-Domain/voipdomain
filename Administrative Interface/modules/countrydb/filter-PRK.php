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
 * related to country database of Korea (Democratic People's Republic of).
 *
 * Reference: https://www.itu.int/oth/T0202000036/en (2011-06-01)
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
 * E.164 Korea (Democratic People's Republic of) country hook
 */
framework_add_filter ( "e164_identify_country_PRK", "e164_identify_country_PRK");

/**
 * E.164 Korea (Democratic People's Republic of) area number identification hook.
 * This hook is an e164_identify sub hook, called when the ISO3166 Alpha3 are
 * "PRK" (code for Korea (Democratic People's Republic of)). This hook will
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
function e164_identify_country_PRK ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Korea (Democratic People's Republic of)
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+850")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "191" => "CHEO Technical Joint Venture Company (Koryolink)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 14)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "850", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Korea (Democratic People's Republic of)", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+850 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 1 or 2 digits NDC and 4 to 13 digits SN
   */
  $prefixes = array (
    "8529" => array ( "area" => "Rason", "city" => "Rason", "operator" => "KPTC", "digits" => 4),
    "2885" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 13),
    "2883" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 13),
    "2882" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 13),
    "2881" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 13),
    "2880" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 13),
    "2772" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 4),
    "2771" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 4),
    "2381" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 4),
    "218" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 3),
    "195" => array ( "area" => "Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 7),
    "79" => array ( "area" => "Ryanggang", "city" => "Hyesan", "operator" => "KPTC", "digits" => 6),
    "73" => array ( "area" => "North Hamgyong", "city" => "Chongjin", "operator" => "KPTC", "digits" => 6),
    "67" => array ( "area" => "Jagang", "city" => "Kanggye", "operator" => "KPTC", "digits" => 6),
    "61" => array ( "area" => "North Pyongyang", "city" => "Sinuiju", "operator" => "KPTC", "digits" => 6),
    "57" => array ( "area" => "Kangwon", "city" => "Wonsan", "operator" => "KPTC", "digits" => 6),
    "53" => array ( "area" => "South Hamgyong", "city" => "Hamhung", "operator" => "KPTC", "digits" => 6),
    "49" => array ( "area" => "North Hwanghae", "city" => "Kaesong", "operator" => "KPTC", "digits" => 6),
    "45" => array ( "area" => "South Hwanghae", "city" => "Haeju", "operator" => "KPTC", "digits" => 6),
    "41" => array ( "area" => "North Hwanghae", "city" => "Sariwon", "operator" => "KPTC", "digits" => 6),
    "39" => array ( "area" => "Nampo", "city" => "Nampo", "operator" => "KPTC", "digits" => 6),
    "31" => array ( "area" => "South Pyongyang", "city" => "Pyongyang", "operator" => "KPTC", "digits" => 6)
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 4 + strlen ( $prefix) + $data["digits"])
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "850", "NDC" => (string) $prefix, "Country" => "Korea (Democratic People's Republic of)", "Area" => $data["area"], "City" => $data["city"], "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+850 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Korea (Democratic
   * People's Republic of) phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
