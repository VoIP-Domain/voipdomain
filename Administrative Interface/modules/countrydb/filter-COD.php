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
 * related to country database of Congo (Democratic Republic of the).
 *
 * Reference: https://www.itu.int/oth/T0202000037/en (2013-02-16)
 *
 * Note: ITU-T specification for Congo (Democratic Republic of the) are not
 *       clear and are inconsistent, probably missing information. Expect a lot
 *       of flaws, and probably will fail on a large number of valid numbers. If
 *       you've better information, please fix this file and submit to
 *       repository.
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
 * E.164 Congo (Democratic Republic of the) country hook
 */
framework_add_filter ( "e164_identify_country_COD", "e164_identify_country_COD");

/**
 * E.164 Congo (Democratic Republic of the) area number identification hook. This
 * hook is an e164_identify sub hook, called when the ISO3166 Alpha3 are "COD"
 * (code for Congo (Democratic Republic of the)). This hook will verify if phone
 * number is valid, returning the area code, area name, phone number, others
 * number related information and if possible, the number type (mobile,
 * landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_COD ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Congo (Democratic Republic of the)
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+243")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Congo (Democratic Republic of the) has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "99" => "Celtel Congo",
    "98" => "Celtel Congo",
    "97" => "Celtel Congo",
    "89" => "Saint-Télécom S.P.R.L.",
    "88" => "Yozma Timeturns",
    "84" => "Congo-Chine Telecom S.A.R.L. (CCT)",
    "82" => "Vodacom Congo R.D.C.",
    "81" => "Vodacom Congo R.D.C.",
    "80" => "Supercell S.P.R.L."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "243", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Congo (Democratic Republic of the)", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+243 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line and FMC network with 1 digit NDC and 8 digits SN
   */
  $prefixes = array (
    "65" => array ( "area" => "Nord-Kivu, Sud-Kivu and Maniema", "operator" => "Congo Korea Telecom S.A.R.L. (CKT)"),
    "55" => array ( "area" => "Province Orientale (Kisanga and Mbandaka)", "operator" => "Congo Korea Telecom S.A.R.L. (CKT)"),
    "45" => array ( "area" => "Kasai-Oriental and Kasai-Occidental", "operator" => "Congo Korea Telecom S.A.R.L. (CKT)"),
    "35" => array ( "area" => "Bas-Congo and Bandundu", "operator" => "Congo Korea Telecom S.A.R.L. (CKT)"),
    "25" => array ( "area" => "Katanga", "operator" => "Congo Korea Telecom S.A.R.L. (CKT)"),
    "15" => array ( "area" => "Kishasa", "operator" => "Congo Korea Telecom S.A.R.L. (CKT)"),
    "12" => array ( "area" => "Kinshasa", "operator" => "SCPT"),
    "6" => array ( "area" => "Nord-Kivu, Sud-Kivu and Maniema", "operator" => ""),
    "5" => array ( "area" => "Province Orientale (Kisanga and Mbandaka)", "operator" => ""),
    "4" => array ( "area" => "Kasai-Oriental and Kasai-Occidental", "operator" => ""),
    "3" => array ( "area" => "Bas-Congo and Bandundu", "operator" => ""),
    "2" => array ( "area" => "Katanga", "operator" => ""),
    "1" => array ( "area" => "Kishasa", "operator" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "243", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Congo (Democratic Republic of the)", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => ( substr ( $parameters["Number"], 6, 1) == "0" ? VD_PHONETYPE_FMC : VD_PHONETYPE_LANDLINE), "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+243 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Congo (Democratic
   * Republic of the) phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
