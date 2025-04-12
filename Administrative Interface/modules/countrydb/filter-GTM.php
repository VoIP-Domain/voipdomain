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
 * related to country database of Guatemala.
 *
 * Reference: https://www.itu.int/oth/T020200005A/en (2006-11-22)
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
 * E.164 Guatemala country hook
 */
framework_add_filter ( "e164_identify_country_GTM", "e164_identify_country_GTM");

/**
 * E.164 Guatemala area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "GTM" (code for Guatemala). This
 * hook will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_GTM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Guatemala
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+502")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 1 digit NDC and 7 digits SN
   */
  $prefixes = array (
    "5",
    "4"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "502", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Guatemala", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+502 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 1 digit NDC and 7 digits SN
   */
  $prefixes = array (
    "7868" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "7867" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "789" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "788" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "787" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "785" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "784" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "783" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "782" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "781" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "780" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "79" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "77" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "76" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "75" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "74" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "73" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "72" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "71" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "70" => "Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "6" => "Guatemala suburban metropolitan area",
    "2" => "Guatemala city metropolitan area"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "502", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Guatemala", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+502 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 1 digit NDC and 7 digits SN
   */
  $prefixes = array (
    "7869" => "Rural area of Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "7866" => "Rural area of Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "7865" => "Rural area of Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "7864" => "Rural area of Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "7863" => "Rural area of Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "7862" => "Rural area of Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "7861" => "Rural area of Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz",
    "7860" => "Rural area of Quetzaltenango, Huehuetenango, El Quiché, Totonicapán, Sololá, San Marcos, Retalhuleu, Sacatepéquez, Chimaltenango, Escuintla, Santa Rosa, Jutiapa, Suchitepéquez, El Petén, Izabal, Zacapa, Chiquimula, El Progreso, Jalapa, Alta Verrapaz and Baja Verapaz"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "502", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Guatemala", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+502 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for toll free network with 4 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "1800"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 15)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "502", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Guatemala", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_TOLL_FREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8, 3) . " " . substr ( $parameters["Number"], 11), "International" => "+502 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8, 3) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for PRN network with 4 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "1900"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 15)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "502", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Guatemala", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8, 3) . " " . substr ( $parameters["Number"], 11), "International" => "+502 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8, 3) . " " . substr ( $parameters["Number"], 11))));
    }
  }

  /**
   * Check for Marine Radio network with 3 digits NDC and 3 digits SN
   */
  $prefixes = array (
    "332"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "502", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Guatemala", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MARINERADIO, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+502 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Guatemala phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
