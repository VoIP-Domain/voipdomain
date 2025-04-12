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
 * related to country database of Turkmenistan.
 *
 * Reference: https://www.itu.int/oth/T02020000D7/en (2006-07-20)
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
 * E.164 Turkmenistan country hook
 */
framework_add_filter ( "e164_identify_country_TKM", "e164_identify_country_TKM");

/**
 * E.164 Turkmenistan area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "TKM" (code for
 * Turkmenistan). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_TKM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Turkmenistan
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+993")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Turkmenistan has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "6020" => "Ashgabat City Telephone Network",
    "65" => "Altyn Asyr",
    "64" => "Altyn Asyr",
    "63" => "Altyn Asyr",
    "62" => "Altyn Asyr",
    "61" => "Altyn Asyr"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "993", "NDC" => $prefix, "Country" => "Turkmenistan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5), "International" => "+993 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 2, 3 or 4 digits NDC and 4, 5 or 6 digits SN
   */
  $prefixes = array (
    "2444" => array ( "Area" => "Balkan Province", "City" => "Türkmenbaşy"),
    "1392" => array ( "Area" => "Ahal Province", "City" => "Gypjak"),
    "1359" => array ( "Area" => "Ahal Province", "City" => "Tejen"),
    "1358" => array ( "Area" => "Ahal Province", "City" => "Tejen"),
    "1357" => array ( "Area" => "Ahal Province", "City" => "Tejen"),
    "1356" => array ( "Area" => "Ahal Province", "City" => "Tejen"),
    "1355" => array ( "Area" => "Ahal Province", "City" => "Tejen"),
    "1354" => array ( "Area" => "Ahal Province", "City" => "Altyn Asyr"),
    "1353" => array ( "Area" => "Ahal Province", "City" => "Tejen"),
    "1352" => array ( "Area" => "Ahal Province", "City" => "Tejen"),
    "1351" => array ( "Area" => "Ahal Province", "City" => "Tejen"),
    "1350" => array ( "Area" => "Ahal Province", "City" => "Tejen"),
    "569" => array ( "Area" => "Mary Province", "City" => "Türkmengala"),
    "568" => array ( "Area" => "Mary Province", "City" => "Tagtabazar"),
    "566" => array ( "Area" => "Mary Province", "City" => "Sakarçäge"),
    "565" => array ( "Area" => "Mary Province", "City" => "Murgap"),
    "564" => array ( "Area" => "Mary Province", "City" => "Baýramaly"),
    "561" => array ( "Area" => "Mary Province", "City" => "Serhetabat"),
    "560" => array ( "Area" => "Mary Province", "City" => "Ýolöten"),
    "559" => array ( "Area" => "Mary Province", "City" => "Gulanly, Shatlyk"),
    "558" => array ( "Area" => "Mary Province", "City" => "Mollanepes"),
    "557" => array ( "Area" => "Mary Province", "City" => "Ýagty ýol"),
    "522" => array ( "Area" => "Mary Province", "City" => "Mary"),
    "465" => array ( "Area" => "Lebap Province", "City" => "Dostluk"),
    "461" => array ( "Area" => "Lebap Province", "City" => "Seýdi"),
    "449" => array ( "Area" => "Lebap Province", "City" => "Sakar"),
    "448" => array ( "Area" => "Lebap Province", "City" => "Farap"),
    "447" => array ( "Area" => "Lebap Province", "City" => "Saýat"),
    "446" => array ( "Area" => "Lebap Province", "City" => "Dänew"),
    "445" => array ( "Area" => "Lebap Province", "City" => "Darganata"),
    "444" => array ( "Area" => "Lebap Province", "City" => "Kerki (ex-Atamyrat)"),
    "443" => array ( "Area" => "Lebap Province", "City" => "Garabekewül"),
    "442" => array ( "Area" => "Lebap Province", "City" => "Hojambaz"),
    "441" => array ( "Area" => "Lebap Province", "City" => "Halaç"),
    "440" => array ( "Area" => "Lebap Province", "City" => "Çarşaňňy"),
    "438" => array ( "Area" => "Lebap Province", "City" => "Gazojak"),
    "433" => array ( "Area" => "Lebap Province", "City" => "Cärjew (ex-Serdarabat)"),
    "432" => array ( "Area" => "Lebap Province", "City" => "Nyýazow"),
    "431" => array ( "Area" => "Lebap Province", "City" => "Gowurdak"),
    "422" => array ( "Area" => "Lebap Province", "City" => "Turkmenabat"),
    "360" => array ( "Area" => "Daşoguz Province", "City" => "Köneürgenç"),
    "349" => array ( "Area" => "Daşoguz Province", "City" => "Türkmenbaşy"),
    "348" => array ( "Area" => "Daşoguz Province", "City" => "Nyýazow"),
    "347" => array ( "Area" => "Daşoguz Province", "City" => "Köneürgenç"),
    "346" => array ( "Area" => "Daşoguz Province", "City" => "Boldumsaz"),
    "345" => array ( "Area" => "Daşoguz Province", "City" => "Gubadag"),
    "344" => array ( "Area" => "Daşoguz Province", "City" => "Akdepe"),
    "343" => array ( "Area" => "Daşoguz Province", "City" => "Ýylanly"),
    "340" => array ( "Area" => "Daşoguz Province", "City" => "Tagta"),
    "322" => array ( "Area" => "Daşoguz Province", "City" => "Daşoguz"),
    "248" => array ( "Area" => "Balkan Province", "City" => "Magtymguly (Garrygala)"),
    "247" => array ( "Area" => "Balkan Province", "City" => "Bereket (Gazanjyk)"),
    "246" => array ( "Area" => "Balkan Province", "City" => "Serdar (Gyzylarbat)"),
    "245" => array ( "Area" => "Balkan Province", "City" => "Esenguly"),
    "243" => array ( "Area" => "Balkan Province", "City" => "Türkmenbaşy"),
    "242" => array ( "Area" => "Balkan Province", "City" => "Etrek (Gyzyletrek)"),
    "241" => array ( "Area" => "Balkan Province", "City" => "Gumdag"),
    "240" => array ( "Area" => "Balkan Province", "City" => "Hazar (Çeleken)"),
    "222" => array ( "Area" => "Balkan Province", "City" => "Balkanabat"),
    "138" => array ( "Area" => "Ahal Province", "City" => "Abadan"),
    "137" => array ( "Area" => "Ahal Province", "City" => "Änew"),
    "136" => array ( "Area" => "Ahal Province", "City" => "Babadaýhan"),
    "134" => array ( "Area" => "Ahal Province", "City" => "Sarahs"),
    "133" => array ( "Area" => "Ahal Province", "City" => "Kaka"),
    "132" => array ( "Area" => "Ahal Province", "City" => "Gökdepe"),
    "131" => array ( "Area" => "Ahal Province", "City" => "Baharly (Bäherden)"),
    "12" => array ( "Area" => "Ashgabat", "City" => "Ashgabat")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "993", "NDC" => $prefix, "Country" => "Turkmenistan", "Area" => $data["Area"], "City" => $data["City"], "Operator" => "", "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5), "International" => "+993 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Turkmenistan phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
