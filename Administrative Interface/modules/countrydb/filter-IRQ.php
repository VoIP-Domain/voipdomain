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
 * related to country database of Iraq.
 *
 * Reference: https://www.itu.int/oth/T0202000067/en (2015-10-14)
 *
 * Note: The ITU-T specification to Iraq has no information about the Iraq
 *       telephony allocation plan. There's only some misplaced inconsistent
 *       information about some mobile prefixes. If you have other trusted
 *       source, please update this file. Many information was taken from
 *       other untrusted sources. Please don't rely on those information.
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
 * E.164 Iraq country hook
 */
framework_add_filter ( "e164_identify_country_IRQ", "e164_identify_country_IRQ");

/**
 * E.164 Iraqian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "IRQ" (code for
 * Iraq). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_IRQ ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Iraq
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+964")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 10 digits SN
   */
  $prefixes = array (
    "7906" => array ( "operator" => "Zain", "area" => ""),
    "7905" => array ( "operator" => "Zain", "area" => ""),
    "7904" => array ( "operator" => "Zain", "area" => ""),
    "7903" => array ( "operator" => "Zain", "area" => ""),
    "7902" => array ( "operator" => "Zain", "area" => ""),
    "7901" => array ( "operator" => "Zain", "area" => ""),
    "7835" => array ( "operator" => "Zain", "area" => ""),
    "7834" => array ( "operator" => "Zain", "area" => ""),
    "7833" => array ( "operator" => "Zain", "area" => ""),
    "7832" => array ( "operator" => "Zain", "area" => ""),
    "7831" => array ( "operator" => "Zain", "area" => ""),
    "7830" => array ( "operator" => "Zain", "area" => ""),
    "7736" => array ( "operator" => "Asiacell", "area" => ""),
    "7735" => array ( "operator" => "Asiacell", "area" => ""),
    "7734" => array ( "operator" => "Asiacell", "area" => ""),
    "7733" => array ( "operator" => "Asiacell", "area" => ""),
    "7732" => array ( "operator" => "Asiacell", "area" => ""),
    "7731" => array ( "operator" => "Asiacell", "area" => ""),
    "7730" => array ( "operator" => "Asiacell", "area" => ""),
    "7517" => array ( "operator" => "Korek Telecom", "area" => ""),
    "7516" => array ( "operator" => "Korek Telecom", "area" => ""),
    "7515" => array ( "operator" => "Korek Telecom", "area" => ""),
    "7514" => array ( "operator" => "Korek Telecom", "area" => ""),
    "7513" => array ( "operator" => "Korek Telecom", "area" => ""),
    "7512" => array ( "operator" => "Korek Telecom", "area" => ""),
    "7511" => array ( "operator" => "Korek Telecom", "area" => ""),
    "7510" => array ( "operator" => "Korek Telecom", "area" => ""),
    "7494" => array ( "operator" => "", "area" => "Imam Hussien Holy Shrine"),
    "7491" => array ( "operator" => "", "area" => "ITPC"),
    "7481" => array ( "operator" => "", "area" => "ITC Fanoos"),
    "7480" => array ( "operator" => "", "area" => "ITC Fanoos"),
    "7444" => array ( "operator" => "Mobitel", "area" => ""),
    "7435" => array ( "operator" => "", "area" => "Kalimat"),
    "7401" => array ( "operator" => "", "area" => "Itisaluna"),
    "7400" => array ( "operator" => "", "area" => "Itisaluna"),
    "782" => array ( "operator" => "Zain", "area" => ""),
    "781" => array ( "operator" => "Zain", "area" => ""),
    "780" => array ( "operator" => "Zain", "area" => ""),
    "772" => array ( "operator" => "Asiacell", "area" => ""),
    "771" => array ( "operator" => "Asiacell", "area" => ""),
    "770" => array ( "operator" => "Asiacell", "area" => ""),
    "750" => array ( "operator" => "Korek Telecom", "area" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 14)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "964", "NDC" => "", "Country" => "Iraq", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+964 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "66" => "Erbil",
    "62" => "Duhok",
    "60" => "Mosul",
    "53" => "Sulaymaniyah",
    "50" => "Kirkuk",
    "43" => "Amarah",
    "42" => "Nasiriyah",
    "40" => "Basra",
    "37" => "Samawa",
    "36" => "Diwaniya",
    "33" => "Najaf",
    "32" => "Karbala",
    "30" => "Hillah",
    "25" => "Baqubah",
    "24" => "Ramadi",
    "23" => "Kut",
    "21" => "Tikrit",
    "1" => "Baghdad"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      switch ( strlen ( $prefix))
      {
        case 1:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+964 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8));
          break;
        default:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+964 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9));
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "964", "NDC" => (string) $prefix, "Country" => "Iraq", "Area" => $area, "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Iraqian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
