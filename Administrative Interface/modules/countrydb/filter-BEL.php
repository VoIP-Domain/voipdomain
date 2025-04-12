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
 * related to country database of Belgian.
 *
 * Reference: https://www.bipt.be/public/files/en/474/b_nr_pq-status%201-1-2018.pdf
 * Reference: https://en.wikipedia.org/wiki/Telephone_numbers_in_Belgium
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
 * E.164 Belgian country hook
 */
framework_add_filter ( "e164_identify_country_BEL", "e164_identify_country_BEL");

/**
 * E.164 Belgianian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "BEL" (code for
 * Belgian). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_BEL ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Belgian
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+32")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "468" => "Telenet",
    "467" => "Telenet",
    "466" => "Vectone",
    "465" => "Lycamobile",
    "460" => "Proximus",
    "456" => "Unleashed",
    "455" => "VOO",
    "49" => "Orange",
    "48" => "Telenet (Base)",
    "47" => "Proximus"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "32", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Belgian", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 3) . "-" . substr ( $parameters["Number"], 6, 2) . "-" . substr ( $parameters["Number"], 8, 2) . "-" . substr ( $parameters["Number"], 10), "International" => "+32 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8, 2) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "89" => "Genk",
    "87" => "Verviers",
    "86" => "Durbuy",
    "85" => "Huy (Hoei)",
    "84" => "Marche-en-Famenne",
    "83" => "Ciney",
    "82" => "Dinant",
    "81" => "Namur (Namen)",
    "80" => "Stavelot",
    "71" => "Charleroi",
    "69" => "Tournai (Doornik)",
    "68" => "Ath (Aat)",
    "67" => "Nivelles (Nijvel) and Soignies (Zinnik)",
    "65" => "Mons (Bergen) and Casteau",
    "64" => "La Louvière",
    "63" => "Arlon (Aarlen)",
    "61" => "Bastogne (Bastenaken) and Libramont-Chevigny",
    "60" => "Chimay",
    "59" => "Ostend and Bredene (Oostende/Ostende and Bredene)",
    "58" => "Veurne (Furnes)",
    "57" => "Ypres (Ieper)",
    "56" => "Kortrijk (Courtrai) and Comines-Warneton (Komen-Waasten) and Mouscron (Moeskroen)",
    "55" => "Ronse (Renaix)",
    "54" => "Ninove",
    "53" => "Aalst (Alost)",
    "52" => "Dendermonde (Termonde)",
    "51" => "Roeselare (Roulers)",
    "50" => "Bruges (Brugge) and Zeebrugge",
    "19" => "Waremme (Borgworm)",
    "16" => "Leuven (Louvain) and Tienen (Tirlemont)",
    "15" => "Mechelen (Malines)",
    "14" => "Geel and Herentals and Turnhout",
    "13" => "Diest",
    "12" => "Tongeren (Tongres)",
    "11" => "Hasselt",
    "10" => "Wavre (Waver)",
    "9" => "Ghent (Gent/Gand)",
    "4" => "Liège (Luik) and Voeren (Fourons)",
    "3" => "Antwerp (Antwerpen/Anvers) and Sint-Niklaas",
    "2" => "Brussels (Bruxelles/Brussel)"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      if ( strlen ( $prefix) == 1)
      {
        $callformats = array ( "Local" => "0" . substr ( $parameters["Number"], 3, 1) . "-" . substr ( $parameters["Number"], 4, 3) . "-" . substr ( $parameters["Number"], 7, 2) . "-" . substr ( $parameters["Number"], 9), "International" => "+32 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9));
      } else {
        $callformats = array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 2) . "-" . substr ( $parameters["Number"], 7, 2) . "-" . substr ( $parameters["Number"], 9), "International" => "+32 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9));
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "32", "NDC" => (string) $prefix, "Country" => "Belgian", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], ( strlen ( $prefix) == 1 ? 4 : 5)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * Check for Premium Rate Numbers
   */
  if ( substr ( $parameters["Number"], 3, 2) == "70" && strlen ( $parameters["Number"]) == 11)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "32", "NDC" => "70", "Country" => "Belgian", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 3) . "-" . substr ( $parameters["Number"], 8), "International" => "+32 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * If reached here, number wasn't identified as a valid Belgianian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
