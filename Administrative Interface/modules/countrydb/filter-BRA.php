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
 * related to country database of Brazil.
 *
 * Reference: https://www.itu.int/oth/T020200001D/en (2016-01-28)
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
 * E.164 Brazil country hook
 */
framework_add_filter ( "e164_identify_country_BRA", "e164_identify_country_BRA");

/**
 * E.164 Brazilian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BRA" (code for Brazil). This
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
function e164_identify_country_BRA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Brazil
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+55")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile and fixed line network with 2 digits NDC and 8 digits SN ([2-5]XXXXXXX) for fixed line or 9 digits SN ([6-9]XXXXXXXX) for mobile
   */
  $prefixes = array (
    "99" => "Maranhão",
    "98" => "Maranhão",
    "97" => "Amazonas",
    "96" => "Amapá",
    "95" => "Roraima",
    "94" => "Pará",
    "93" => "Pará",
    "92" => "Amazonas",
    "91" => "Pará",
    "89" => "Piauí",
    "88" => "Ceará",
    "87" => "Pernambuco",
    "86" => "Piauí",
    "85" => "Ceará",
    "84" => "Rio Grande do Norte",
    "83" => "Paraíba",
    "82" => "Alagoas",
    "81" => "Pernambuco",
    "79" => "Sergipe",
    "77" => "Bahia",
    "75" => "Bahia",
    "74" => "Bahia",
    "73" => "Bahia",
    "71" => "Bahia",
    "69" => "Rondônia",
    "68" => "Acre",
    "67" => "Mato Grosso do Sul",
    "66" => "Mato Grosso",
    "65" => "Mato Grosso",
    "64" => "Goiás",
    "63" => "Tocantins",
    "62" => "Goiás",
    "61" => "Distrito Federal/Goiás",
    "55" => "Rio Grande do Sul",
    "54" => "Rio Grande do Sul",
    "53" => "Rio Grande do Sul",
    "51" => "Rio Grande do Sul",
    "49" => "Santa Catarina",
    "48" => "Santa Catarina",
    "47" => "Santa Catarina",
    "46" => "Paraná",
    "45" => "Paraná",
    "44" => "Paraná",
    "43" => "Paraná",
    "42" => "Paraná",
    "41" => "Paraná",
    "38" => "Minas Gerais",
    "37" => "Minas Gerais",
    "35" => "Minas Gerais",
    "34" => "Minas Gerais",
    "33" => "Minas Gerais",
    "32" => "Minas Gerais",
    "31" => "Minas Gerais",
    "28" => "Espírito Santo",
    "27" => "Espírito Santo",
    "24" => "Rio de Janeiro",
    "22" => "Rio de Janeiro",
    "21" => "Rio de Janeiro",
    "19" => "São Paulo",
    "18" => "São Paulo",
    "17" => "São Paulo",
    "16" => "São Paulo",
    "16" => "São Paulo",
    "15" => "São Paulo",
    "14" => "São Paulo",
    "13" => "São Paulo",
    "12" => "São Paulo",
    "11" => "São Paulo"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      /**
       * Mobile number has 2 digits NDC and 9 digits SN ([6-9]XXXXXXXX)
       */
      if ( strlen ( $parameters["Number"]) == 14 && (int) substr ( $parameters["Number"], 5, 1) >= 6 && (int) substr ( $parameters["Number"], 5, 1) <= 9)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "55", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Brazil", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 3, 2) . ") " . substr ( $parameters["Number"], 5, 5) . "-" . substr ( $parameters["Number"], 10, 4), "International" => "+55 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
      }

      /**
       * Fixed line number has 2 digits NDC and 8 digits SN ([2-5]XXXXXXX)
       */
      if ( strlen ( $parameters["Number"]) == 13 && (int) substr ( $parameters["Number"], 5, 1) >= 2 && (int) substr ( $parameters["Number"], 5, 1) <= 5)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "55", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Brazil", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(" . substr ( $parameters["Number"], 3, 2) . ") " . substr ( $parameters["Number"], 5, 4) . "-" . substr ( $parameters["Number"], 9, 4), "International" => "+55 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
      }
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Brazilian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
