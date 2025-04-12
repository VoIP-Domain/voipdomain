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
 * related to country database of Angola.
 *
 * Reference: https://www.itu.int/oth/T0202000006/en (2009-06-22)
 * Reference: http://www.inacom.gov.ao/Portals/0/Documentacao/novoplanonacionaldenumeracaodeangola.pdf
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
 * E.164 Angola country hook
 */
framework_add_filter ( "e164_identify_country_AGO", "e164_identify_country_AGO");

/**
 * E.164 Angolan area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "AGO" (code for Angola). This
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
function e164_identify_country_AGO ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Angola
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+244")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network
   */
  if ( substr ( $parameters["Number"], 4, 1) == "9" && strlen ( $parameters["Number"]) == 13)
  {
    /**
     * Check for operator
     */
    switch ( (int) substr ( $parameters["Number"], 5, 1))
    {
      case 1:
        $operator = "Movicel";
        break;
      case 2:
        $operator = "Unitel";
        break;
      default:
        return ( is_array ( $buffer) ? $buffer : false);
        break;
    }
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "244", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Angola", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10, 3), "International" => "+244 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . substr ( $parameters["Number"], 10, 3))));
  }

  /**
   * Check for fixed line network
   */
  if ( ( substr ( $parameters["Number"], 4, 1) == "2" || substr ( $parameters["Number"], 4, 1) == "3") && strlen ( $parameters["Number"]) == 13)
  {
    /**
     * Check for province code
     */
    $prefixes = array (
      "72" => "Benguela",
      "65" => "Cunene",
      "64" => "Namibe",
      "61" => "Huíla",
      "54" => "Moxico",
      "53" => "Lunda Sul",
      "52" => "Lunda Norte",
      "51" => "Malange",
      "49" => "Cuando Cubango",
      "48" => "Bié",
      "41" => "Huambo",
      "36" => "Cuanza Sul",
      "35" => "Cuanza Norte",
      "34" => "Bengo",
      "33" => "Uíge",
      "32" => "Zaire",
      "31" => "Cabinda"
    );
    if ( array_key_exists ( substr ( $parameters["Number"], 5, 2), $prefixes))
    {
      $prefix = substr ( $parameters["Number"], 5, 2);
      $area = $prefixes[$prefix];
    } else {
      $prefix = "";
      $area = "";
    }
    if ( substr ( $parameters["Number"], 5, 1) == "2")
    {
      $prefix = "2";
      $area = "Luanda";
    }

    /**
     * If no area and prefix found, return false
     */
    if ( empty ( $prefix))
    {
      return ( is_array ( $buffer) ? $buffer : false);
    }

    /**
     * Check for operator
     */
    switch ( (int) substr ( $parameters["Number"], 5 + strlen ( $prefix), 1))
    {
      case 2:
        $operator = "Angola Telecom";
        break;
      case 6:
        $operator = "Mercury";
        break;
      case 7:
        $operator = "Mundo Startel";
        break;
      case 8:
        $operator = "Nexus";
        break;
      case 9:
        $operator = "Wezacom";
        break;
      default:
        $operator = "";
        break;
    }

    /**
     * Return data
     */
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "244", "NDC" => substr ( $parameters["Number"], 4, 1 + strlen ( $prefix)), "Country" => "Angola", "Area" => $area, "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10, 3), "International" => "+244 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10, 3))));
  }

  /**
   * If reached here, number wasn't identified as a valid Angolian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
