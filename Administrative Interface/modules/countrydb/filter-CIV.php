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
 * related to country database of Côte d'Ivoire.
 *
 * Reference: https://www.itu.int/oth/T0202000031/en (2015-02-09)
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
 * E.164 Côte d'Ivoire country hook
 */
framework_add_filter ( "e164_identify_country_CIV", "e164_identify_country_CIV");

/**
 * E.164 Côte d'Ivoire area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "CIV" (code for
 * Côte d'Ivoire). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_CIV ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Côte d'Ivoire
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+225")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Côte d'Ivoire has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "78" => "Orange",
    "77" => "Orange",
    "69" => "Aircomm",
    "67" => "Comium",
    "66" => "Comium",
    "60" => "Oricel",
    "54" => "MTN",
    "50" => "Warid",
    "49" => "Orange",
    "48" => "Orange",
    "47" => "Orange",
    "46" => "MTN",
    "45" => "MTN",
    "44" => "MTN",
    "09" => "Orange",
    "08" => "Orange",
    "07" => "Orange",
    "06" => "MTN",
    "05" => "MTN",
    "04" => "MTN",
    "03" => "Atlantic Telecom",
    "02" => "Atlantic Telecom",
    "01" => "Atlantic Telecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "225", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Côte d'Ivoire", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+225 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "368" => array ( "area" => "Savanes", "city" => "Korhogo", "operator" => "Côte d'Ivoire Telecom"),
    "360" => array ( "area" => "Savanes", "city" => "Korhogo", "operator" => "Arobase Telecom"),
    "359" => array ( "area" => "Comoé", "city" => "Abengourou", "operator" => "Côte d'Ivoire Telecom"),
    "350" => array ( "area" => "Comoé", "city" => "Abengourou", "operator" => "Arobase Telecom"),
    "347" => array ( "area" => "Bas-Sassandra", "city" => "San-Pédro", "operator" => "Côte d'Ivoire Telecom"),
    "340" => array ( "area" => "Bas-Sassandra", "city" => "San-Pédro", "operator" => "Arobase Telecom"),
    "337" => array ( "area" => "Montagnes", "city" => "Man", "operator" => "Côte d'Ivoire Telecom"),
    "330" => array ( "area" => "Montagnes", "city" => "Man", "operator" => "Arobase Telecom"),
    "327" => array ( "area" => "Sassandra-Marahoué", "city" => "Daloa", "operator" => "Côte d'Ivoire Telecom"),
    "320" => array ( "area" => "Sassandra-Marahoué", "city" => "Daloa", "operator" => "Arobase Telecom"),
    "319" => array ( "area" => "Vallée du Bandama", "city" => "Bouaké", "operator" => "Côte d'Ivoire Telecom"),
    "316" => array ( "area" => "Vallée du Bandama", "city" => "Bouaké", "operator" => "Côte d'Ivoire Telecom"),
    "310" => array ( "area" => "Vallée du Bandama", "city" => "Bouaké", "operator" => "Arobase Telecom"),
    "306" => array ( "area" => "Yamoussoukro", "city" => "Yamoussoukro", "operator" => "Côte d'Ivoire Telecom"),
    "300" => array ( "area" => "Yamoussoukro", "city" => "Yamoussoukro", "operator" => "Arobase Telecom"),
    "245" => array ( "area" => "Abidjan", "city" => "Abobo", "operator" => "Côte d'Ivoire Telecom"),
    "244" => array ( "area" => "Abidjan", "city" => "Abobo", "operator" => "Côte d'Ivoire Telecom"),
    "243" => array ( "area" => "Abidjan", "city" => "Abobo", "operator" => "Côte d'Ivoire Telecom"),
    "240" => array ( "area" => "Abidjan", "city" => "Abobo", "operator" => "Arobase Telecom"),
    "235" => array ( "area" => "Abidjan", "city" => "Banco", "operator" => "Côte d'Ivoire Telecom"),
    "234" => array ( "area" => "Abidjan", "city" => "Banco", "operator" => "Côte d'Ivoire Telecom"),
    "230" => array ( "area" => "Abidjan", "city" => "Banco", "operator" => "Arobase Telecom"),
    "225" => array ( "area" => "Abidjan", "city" => "Cocody", "operator" => "Côte d'Ivoire Telecom"),
    "224" => array ( "area" => "Abidjan", "city" => "Cocody", "operator" => "Côte d'Ivoire Telecom"),
    "220" => array ( "area" => "Abidjan", "city" => "Cocody", "operator" => "Arobase Telecom"),
    "217" => array ( "area" => "Abidjan", "city" => "Abidjan South", "operator" => "Côte d'Ivoire Telecom"),
    "215" => array ( "area" => "Abidjan", "city" => "Abidjan South", "operator" => "Côte d'Ivoire Telecom"),
    "213" => array ( "area" => "Abidjan", "city" => "Abidjan South", "operator" => "Côte d'Ivoire Telecom"),
    "212" => array ( "area" => "Abidjan", "city" => "Abidjan South", "operator" => "Côte d'Ivoire Telecom"),
    "210" => array ( "area" => "Abidjan", "city" => "Abidjan South", "operator" => "Arobase Telecom"),
    "203" => array ( "area" => "Abidjan", "city" => "Plateau", "operator" => "Côte d'Ivoire Telecom"),
    "202" => array ( "area" => "Abidjan", "city" => "Plateau", "operator" => "Côte d'Ivoire Telecom"),
    "200" => array ( "area" => "Abidjan", "city" => "Plateau", "operator" => "Arobase Telecom")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "225", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Côte d'Ivoire", "Area" => $data["area"], "City" => $data["city"], "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+225 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Côte d'Ivoire phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
