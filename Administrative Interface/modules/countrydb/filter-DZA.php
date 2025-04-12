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
 * related to country database of Algeria.
 *
 * Reference: https://www.itu.int/oth/T0202000003/en (2008-01-23)
 * Reference: https://en.wikipedia.org/wiki/Telephone_numbers_in_Algeria
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
 * E.164 Algeria country hook
 */
framework_add_filter ( "e164_identify_country_DZA", "e164_identify_country_DZA");

/**
 * E.164 Algerian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "DZA" (code for Algeria). This
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
function e164_identify_country_DZA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Algeria
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+213")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN ([2-9]XXXXXX)
   */
  $prefixes = array (
    "796" => "Orascom Télécom Algérie (Djezzy)",
    "795" => "Orascom Télécom Algérie (Djezzy)",
    "794" => "Orascom Télécom Algérie (Djezzy)",
    "793" => "Orascom Télécom Algérie (Djezzy)",
    "792" => "Orascom Télécom Algérie (Djezzy)",
    "791" => "Orascom Télécom Algérie (Djezzy)",
    "790" => "Orascom Télécom Algérie (Djezzy)",
    "699" => "Algérie Télécom Mobile (Mobilis)",
    "698" => "Algérie Télécom Mobile (Mobilis)",
    "697" => "Algérie Télécom Mobile (Mobilis)",
    "77" => "Orascom Télécom Algérie (Djezzy)",
    "66" => "Algérie Télécom Mobile (Mobilis)",
    "55" => "Wataniya Télécom Algérie (Nedjma)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 13)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "213", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Algeria", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+213 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
      } else {
        return ( is_array ( $buffer) ? $buffer : false);
      }
    }
  }

  /**
   * Check for fixed line network
   */
  $prefixes = array (
    "20" => array ( "area" => "Central region", "operator" => "Algérie Télécom"),
    "21" => array ( "area" => "Alger", "operator" => "Algérie Télécom"),
    "24" => array ( "area" => "Boumerdes and Tipaza", "operator" => "Algérie Télécom"),
    "25" => array ( "area" => "Blida and Medea", "operator" => "Algérie Télécom"),
    "26" => array ( "area" => "Tizi Ouzou and Bouira", "operator" => "Algérie Télécom"),
    "27" => array ( "area" => "Chlef, Ain Defla and Djelfa", "operator" => "Algérie Télécom"),
    "29" => array ( "area" => "Ouargla, laghouat, Ghardaia, Tamenrasset and Illizi", "operator" => "Algérie Télécom"),
    "30" => array ( "area" => "Eastern region", "operator" => "Algérie Télécom"),
    "31" => array ( "area" => "Constantine, Mila", "operator" => "Algérie Télécom"),
    "32" => array ( "area" => "Oum El Bouaghi, Khenchela and El Oued", "operator" => "Algérie Télécom"),
    "33" => array ( "area" => "Batna and Biskra", "operator" => "Algérie Télécom"),
    "34" => array ( "area" => "Bejaia and Jijel", "operator" => "Algérie Télécom"),
    "35" => array ( "area" => "M’sila and Bordj Bou Arreridj", "operator" => "Algérie Télécom"),
    "36" => array ( "area" => "Setif", "operator" => "Algérie Télécom"),
    "37" => array ( "area" => "Guelma, Tebessa and Souk Ahras", "operator" => "Algérie Télécom"),
    "38" => array ( "area" => "Annaba, Skikda and El Tarf", "operator" => "Algérie Télécom"),
    "40" => array ( "area" => "Western region", "operator" => "Algérie Télécom"),
    "41" => array ( "area" => "Oran", "operator" => "Algérie Télécom"),
    "43" => array ( "area" => "Tlemcen and Ain Temouchent", "operator" => "Algérie Télécom"),
    "45" => array ( "area" => "Mascara and Mostaganem", "operator" => "Algérie Télécom"),
    "46" => array ( "area" => "Tiaret, tissemsilt and Relizane", "operator" => "Algérie Télécom"),
    "48" => array ( "area" => "Sidi Bel Abbes and Saida", "operator" => "Algérie Télécom"),
    "49" => array ( "area" => "Bechar, Adrar, El Bayadh, Naama and Tindouf", "operator" => "Algérie Télécom"),
    "4" => array ( "area" => "", "operator" => "Algérie Télécom"),
    "3" => array ( "area" => "", "operator" => "Algérie Télécom"),
    "2" => array ( "area" => "", "operator" => "Algérie Télécom"),
    "1" => array ( "area" => "", "operator" => "Consortium Algérien des Télécommunications (CAT)")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 12)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "213", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Algeria", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+213 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
      } else {
        return ( is_array ( $buffer) ? $buffer : false);
      }
    }
  }

  /**
   * Check for VSAT network
   */
  $prefixes = array (
    "96192" => "Divona",
    "96191" => "Orascom Télécom Algérie",
    "96190" => "Algérie Télécom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 13)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "213", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Algeria", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+213 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
      } else {
        return ( is_array ( $buffer) ? $buffer : false);
      }
    }
  }

  /**
   * Check for VoIP network
   */
  $prefixes = array (
    "9830" => "Algérie Télécom",
    "982" => ""
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( strlen ( $parameters["Number"]) == 13)
      {
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "213", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Algeria", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => "0 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11, 2), "International" => "+213 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9, 2) . " " . substr ( $parameters["Number"], 11, 2))));
      } else {
        return ( is_array ( $buffer) ? $buffer : false);
      }
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Algerian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
