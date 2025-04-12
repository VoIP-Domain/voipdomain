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
 * related to country database of Costa Rica.
 *
 * Reference: https://www.itu.int/oth/T0202000030/en (2017-03-14)
 * Reference: https://en.wikipedia.org/wiki/Telephone_numbers_in_Costa_Rica
 *
 * Note: This country ITU-T paper is a mass of misplaced information (129 pages
 *       of unconsistence data). This filter module was made mixing information
 *       from Wikipedia and the ITU-T paper. Expect a lot of flaws, and probably
 *       will fail on a large number of valid numbers. If you've better
 *       information, please fix this file and submit to repository.
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
 * E.164 Costa Rica country hook
 */
framework_add_filter ( "e164_identify_country_CRI", "e164_identify_country_CRI");

/**
 * E.164 Costa Rica area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "CRI" (code for
 * Costa Rica). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_CRI ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Costa Rica
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+506")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Costa Rica has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "571" => "Tuyo",
    "570" => "Tuyo",
    "504" => "MVNO's",
    "503" => "MVNO's",
    "502" => "MVNO's",
    "501" => "MVNO's",
    "500" => "MVNO's",
    "8" => "Instituto Costarricense de Electricidad",
    "7" => "Claro CR Telecomunicaciones S.A.",
    "6" => "Telefónica de Costa Rica TC S.A."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "506", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Costa Rica", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . "-" . substr ( $parameters["Number"], 8), "International" => "+506 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "27" => array ( "area" => "Limon and South Puntarenas Province", "operator" => "Instituto Costarricense de Electricidad"),
    "26" => array ( "area" => "Guanacaste, Central and North Puntarenas", "operator" => "Instituto Costarricense de Electricidad"),
    "25" => array ( "area" => "Cartago, San José and Heredia", "operator" => "Instituto Costarricense de Electricidad"),
    "24" => array ( "area" => "Alajuela", "operator" => "Instituto Costarricense de Electricidad"),
    "22" => array ( "area" => "San José and Heredia", "operator" => "Instituto Costarricense de Electricidad")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "506", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Costa Rica", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . "-" . substr ( $parameters["Number"], 8), "International" => "+506 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for VoIP network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "404000" => "E-Diay",
    "402900" => "",
    "43004" => "Antares de Costa Rica S.A.",
    "43003" => "Antares de Costa Rica S.A.",
    "43002" => "Antares de Costa Rica S.A.",
    "43001" => "Antares de Costa Rica S.A.",
    "43000" => "Antares de Costa Rica S.A.",
    "40904" => "Itellum Limitada S.A.",
    "40903" => "Itellum Limitada S.A.",
    "40902" => "Itellum Limitada S.A.",
    "40901" => "Itellum Limitada S.A.",
    "40900" => "Itellum Limitada S.A.",
    "40824" => "Telecable S.A.",
    "40823" => "Telecable S.A.",
    "40822" => "Telecable S.A.",
    "40821" => "Telecable S.A.",
    "40820" => "Telecable S.A.",
    "40701" => "InterPhone S.A.",
    "40700" => "InterPhone S.A.",
    "40604" => "RACSA S.A.",
    "40603" => "RACSA S.A.",
    "40602" => "RACSA S.A.",
    "40601" => "RACSA S.A.",
    "40600" => "RACSA S.A.",
    "40200" => "",
    "40107" => "R&H International S.A.",
    "40106" => "R&H International S.A.",
    "40105" => "R&H International S.A.",
    "40104" => "R&H International S.A.",
    "40103" => "",
    "40102" => "",
    "40101" => "",
    "40100" => "",
    "4705" => "Televisora de Costa Rica S.A.",
    "4704" => "Televisora de Costa Rica S.A.",
    "4703" => "Televisora de Costa Rica S.A.",
    "4400" => "Claro CR Telecomunicaciones S.A.",
    "4203" => "PRD Internacional S.A.",
    "4202" => "PRD Internacional S.A.",
    "4201" => "PRD Internacional S.A.",
    "4200" => "PRD Internacional S.A.",
    "4081" => "Telecable S.A.",
    "4080" => "Telecable S.A.",
    "4037" => "Amnet S.A.",
    "4036" => "Amnet S.A.",
    "4035" => "Amnet S.A.",
    "4034" => "Amnet S.A.",
    "4033" => "Amnet S.A.",
    "4032" => "Amnet S.A.",
    "4031" => "Amnet Cable Costa Rica S.A.",
    "4030" => "Amnet Cable Costa Rica S.A.",
    "4002" => "Call My Way NY S.A.",
    "4001" => "Call My Way NY S.A.",
    "2100" => "Instituto Costarricense de Electricidad",
    "2101" => "Instituto Costarricense de Electricidad",
    "2102" => "Instituto Costarricense de Electricidad",
    "2103" => "Instituto Costarricense de Electricidad",
    "2104" => "Instituto Costarricense de Electricidad",
    "2105" => "Instituto Costarricense de Electricidad",
    "2106" => "Instituto Costarricense de Electricidad",
    "411" => "Telefónica de Costa Rica TC S.A.",
    "410" => "Telefónica de Costa Rica TC S.A.",
    "405" => "American Data Network S.A."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "506", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Costa Rica", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . "-" . substr ( $parameters["Number"], 8), "International" => "+506 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Costa Rica phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
