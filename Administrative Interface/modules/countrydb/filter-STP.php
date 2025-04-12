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
 * related to country database of Sao Tome and Principe.
 *
 * Reference: https://www.itu.int/oth/T02020000B6/en (2019-01-25)
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
 * E.164 Sao Tome and Principe country hook
 */
framework_add_filter ( "e164_identify_country_STP", "e164_identify_country_STP");

/**
 * E.164 Sao Tome and Principe area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "STP" (code for Sao
 * Tome and Principe). This hook will verify if phone number is valid, returning
 * the area code, area name, phone number, others number related information and
 * if possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_STP ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Sao Tome and Principe
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+239")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Sao Tome and Principe has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "99" => "Companhia Santomense de Telecomunicações (CST)",
    "98" => "Companhia Santomense de Telecomunicações (CST)",
    "9" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "239", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Sao Tome and Principe", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6), "International" => "+239 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for fixed line network with 3 or 4 digits NDC and 5 or 4 digits SN
   */
  $prefixes = array (
    "22721" => array ( "Area" => "Madalena", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22652" => array ( "Area" => "Santana, Ribeira Afonso", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22651" => array ( "Area" => "Santana, Ribeira Afonso", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22650" => array ( "Area" => "Santana, Ribeira Afonso", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22611" => array ( "Area" => "Angolares, Porto Alegre", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22610" => array ( "Area" => "Angolares, Porto Alegre", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22513" => array ( "Area" => "Região Autonoma do Príncipe", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22512" => array ( "Area" => "Região Autonoma do Príncipe", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22511" => array ( "Area" => "Região Autonoma do Príncipe", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22510" => array ( "Area" => "Região Autonoma do Príncipe", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22332" => array ( "Area" => "Neves, Santa Catarina", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22331" => array ( "Area" => "Neves, Santa Catarina", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22330" => array ( "Area" => "Neves, Santa Catarina", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22311" => array ( "Area" => "Guadalupe", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22310" => array ( "Area" => "Guadalupe", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22201" => array ( "Area" => "Santo Amaro", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "22200" => array ( "Area" => "Santo Amaro", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2271" => array ( "Area" => "Trindade", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2228" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2227" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2226" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2225" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2224" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2223" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2222" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2221" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "229" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "228" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "224" => array ( "Area" => "Água Grande", "Operator" => "Companhia Santomense de Telecomunicações (CST)"),
    "2" => array ( "Area" => "", "Operator" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "239", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Sao Tome and Principe", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+239 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for PRN network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "624" => "Companhia Santomense de Telecomunicações (CST)",
    "6" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "239", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Sao Tome and Principe", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+239 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Sao Tome and Principe phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
