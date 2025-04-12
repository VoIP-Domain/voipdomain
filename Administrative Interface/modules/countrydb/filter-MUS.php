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
 * related to country database of Mauritius.
 *
 * Reference: https://www.itu.int/oth/T0202000088/en (2021-02-15)
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
 * E.164 Mauritius country hook
 */
framework_add_filter ( "e164_identify_country_MUS", "e164_identify_country_MUS");

/**
 * E.164 Mauritian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MUS" (code for Mauritius). This
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
function e164_identify_country_MUS ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Mauritius
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+230")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Mauritius has 11 or 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 11 || strlen ( $parameters["Number"]) > 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network
   */
  $prefixes = array (
    "5878" => array ( "Area" => "Rodrigues", "Operator" => "Cellplus Mobile Communications Ltd"),
    "5877" => array ( "Area" => "Rodrigues", "Operator" => "Cellplus Mobile Communications Ltd"),
    "5876" => array ( "Area" => "Rodrigues", "Operator" => "Cellplus Mobile Communications Ltd"),
    "5875" => array ( "Area" => "Rodrigues", "Operator" => "Cellplus Mobile Communications Ltd"),
    "5871" => array ( "Area" => "Rodrigues", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "5471" => array ( "Area" => "", "Operator" => "Mauritius Telecom Ltd"),
    "5472" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5473" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5474" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5475" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5476" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5477" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5478" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5479" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5429" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5428" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5423" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5422" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "5421" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "598" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "597" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "596" => array ( "Area" => "", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "595" => array ( "Area" => "", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "594" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "593" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "592" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "591" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "590" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "589" => array ( "Area" => "", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "588" => array ( "Area" => "", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "586" => array ( "Area" => "", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "585" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "584" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "583" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "582" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "581" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "580" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "579" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "578" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "577" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "576" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "575" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "574" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "573" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "572" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "571" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "570" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "550" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "549" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "548" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "545" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "544" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "529" => array ( "Area" => "", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "528" => array ( "Area" => "", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "527" => array ( "Area" => "", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "526" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd"),
    "525" => array ( "Area" => "", "Operator" => "Cellplus Mobile Communications Ltd")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "230", "NDC" => "", "Country" => "Mauritius", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+230 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network
   */
  $prefixes = array (
    "8327" => array ( "Area" => "La Ferme (LF2, Dependent Island/RLU)", "Operator" => ""),
    "8325" => array ( "Area" => "Mont Lubin (ML2, Dependent Island/RLU)", "Operator" => ""),
    "8320" => array ( "Area" => "Mont Venus (MV2, Dependent Island/RLU)", "Operator" => ""),
    "8319" => array ( "Area" => "Riviere Coco (RC2, Dependent Island/RLU)", "Operator" => ""),
    "8318" => array ( "Area" => "Roche Bon Dieu (RB2, Dependent Island/RLU)", "Operator" => ""),
    "6235" => array ( "Area" => "Bel Ombre (MX30A/IRT site)", "Operator" => "Mauritius Telecom Ltd"),
    "6215" => array ( "Area" => "Baie du Cap (MX30A/IRT site)", "Operator" => "Mauritius Telecom Ltd"),
    "6205" => array ( "Area" => "La Prairie (MX30A/IRT site)", "Operator" => "Mauritius Telecom Ltd"),
    "4145" => array ( "Area" => "Olivia (MX30A/IRT site)", "Operator" => ""),
    "4036" => array ( "Area" => "Flic-en-Flac", "Operator" => ""),
    "2175" => array ( "Area" => "Signal Mountain (MX30A/IRT site)", "Operator" => ""),
    "839" => array ( "Area" => "Rodrigues (Dependent Island/RLU)", "Operator" => "Mahanagar Telephone Mauritius Ltd"),
    "831" => array ( "Area" => "Rodrigues (Dependent Island/RLU)", "Operator" => "Mauritius Telecom Ltd"),
    "814" => array ( "Area" => "Agalega (Dependent Island/RLU)", "Operator" => "Mauritius Telecom Ltd"),
    "698" => array ( "Area" => "Floreal", "Operator" => "Mauritius Telecom Ltd"),
    "697" => array ( "Area" => "Floreal", "Operator" => "Mauritius Telecom Ltd"),
    "696" => array ( "Area" => "Floreal", "Operator" => "Mauritius Telecom Ltd"),
    "686" => array ( "Area" => "Floreal", "Operator" => "Mauritius Telecom Ltd"),
    "684" => array ( "Area" => "Glen Park", "Operator" => "Mauritius Telecom Ltd"),
    "677" => array ( "Area" => "Nouvelle France", "Operator" => "Mauritius Telecom Ltd"),
    "676" => array ( "Area" => "Forest Side", "Operator" => "Mauritius Telecom Ltd"),
    "675" => array ( "Area" => "Forest Side", "Operator" => "Mauritius Telecom Ltd"),
    "674" => array ( "Area" => "Forest Side", "Operator" => "Mauritius Telecom Ltd"),
    "670" => array ( "Area" => "Forest Side", "Operator" => "Mauritius Telecom Ltd"),
    "665" => array ( "Area" => "Dubreuil", "Operator" => "Mauritius Telecom Ltd"),
    "664" => array ( "Area" => "Seizième Mile", "Operator" => "Mauritius Telecom Ltd"),
    "637" => array ( "Area" => "Plaisance", "Operator" => "Mauritius Telecom Ltd"),
    "636" => array ( "Area" => "L'Escalier", "Operator" => "Mauritius Telecom Ltd"),
    "634" => array ( "Area" => "Vieux Grand Port", "Operator" => "Mauritius Telecom Ltd"),
    "633" => array ( "Area" => "Riche-en-Eau", "Operator" => "Mauritius Telecom Ltd"),
    "631" => array ( "Area" => "Mahebourg", "Operator" => "Mauritius Telecom Ltd"),
    "627" => array ( "Area" => "Rose Belle", "Operator" => "Mauritius Telecom Ltd"),
    "626" => array ( "Area" => "Rivière des Anguilles", "Operator" => "Mauritius Telecom Ltd"),
    "625" => array ( "Area" => "Souillac", "Operator" => "Mauritius Telecom Ltd"),
    "622" => array ( "Area" => "Chemin Grenier", "Operator" => "Mauritius Telecom Ltd"),
    "617" => array ( "Area" => "Grand Bois", "Operator" => "Mauritius Telecom Ltd"),
    "489" => array ( "Area" => "", "Operator" => "Mauritius Telecom Ltd"),
    "483" => array ( "Area" => "Tamarin", "Operator" => ""),
    "480" => array ( "Area" => "Trou d'eau Douce", "Operator" => ""),
    "467" => array ( "Area" => "Rose Hill", "Operator" => ""),
    "466" => array ( "Area" => "Rose Hill", "Operator" => ""),
    "465" => array ( "Area" => "Rose Hill", "Operator" => ""),
    "464" => array ( "Area" => "Rose Hill", "Operator" => ""),
    "454" => array ( "Area" => "Rose Hill", "Operator" => ""),
    "453" => array ( "Area" => "Flic en Flac", "Operator" => ""),
    "452" => array ( "Area" => "Bambous", "Operator" => ""),
    "451" => array ( "Area" => "La Gaulette", "Operator" => ""),
    "450" => array ( "Area" => "Le Morne", "Operator" => ""),
    "437" => array ( "Area" => "Montagne Blanche", "Operator" => ""),
    "435" => array ( "Area" => "Quartier Militaire", "Operator" => ""),
    "433" => array ( "Area" => "Moka", "Operator" => ""),
    "431" => array ( "Area" => "Ripailles", "Operator" => ""),
    "427" => array ( "Area" => "Candos", "Operator" => ""),
    "426" => array ( "Area" => "Candos", "Operator" => ""),
    "425" => array ( "Area" => "Candos", "Operator" => ""),
    "424" => array ( "Area" => "Candos", "Operator" => ""),
    "419" => array ( "Area" => "Bel Air", "Operator" => ""),
    "418" => array ( "Area" => "Brisée Verdière", "Operator" => ""),
    "417" => array ( "Area" => "Quatre Soeurs", "Operator" => ""),
    "416" => array ( "Area" => "Camp de Masque", "Operator" => ""),
    "415" => array ( "Area" => "Belle Mare", "Operator" => ""),
    "413" => array ( "Area" => "Flacq", "Operator" => ""),
    "412" => array ( "Area" => "Rivière du Rempart", "Operator" => ""),
    "411" => array ( "Area" => "Roches Noires", "Operator" => ""),
    "410" => array ( "Area" => "Poste Lafayette", "Operator" => ""),
    "289" => array ( "Area" => "", "Operator" => "Mauritius Telecom Ltd"),
    "288" => array ( "Area" => "Grand Gaube", "Operator" => ""),
    "286" => array ( "Area" => "Pailles", "Operator" => ""),
    "283" => array ( "Area" => "Goodlands", "Operator" => ""),
    "282" => array ( "Area" => "Goodlands", "Operator" => ""),
    "269" => array ( "Area" => "Grand Bay", "Operator" => ""),
    "266" => array ( "Area" => "Mapou", "Operator" => ""),
    "265" => array ( "Area" => "Trou Aux Biches", "Operator" => ""),
    "264" => array ( "Area" => "Piton", "Operator" => ""),
    "263" => array ( "Area" => "Grand Bay", "Operator" => ""),
    "262" => array ( "Area" => "Cap Malheureux", "Operator" => ""),
    "261" => array ( "Area" => "Triolet", "Operator" => ""),
    "249" => array ( "Area" => "Terre Rouge", "Operator" => ""),
    "248" => array ( "Area" => "Terre Rouge", "Operator" => ""),
    "247" => array ( "Area" => "Tombeau Bay", "Operator" => ""),
    "245" => array ( "Area" => "Long Mountain", "Operator" => ""),
    "243" => array ( "Area" => "Pamplemousses", "Operator" => ""),
    "242" => array ( "Area" => "Plaine Verte", "Operator" => ""),
    "241" => array ( "Area" => "Plaine Verte", "Operator" => ""),
    "240" => array ( "Area" => "Plaine Verte", "Operator" => ""),
    "238" => array ( "Area" => "Albion", "Operator" => ""),
    "234" => array ( "Area" => "Pointe aux Sables", "Operator" => ""),
    "233" => array ( "Area" => "Coromandel", "Operator" => ""),
    "216" => array ( "Area" => "Plaine Verte", "Operator" => ""),
    "212" => array ( "Area" => "Port Louis", "Operator" => ""),
    "211" => array ( "Area" => "Port Louis", "Operator" => ""),
    "210" => array ( "Area" => "Port Louis", "Operator" => ""),
    "208" => array ( "Area" => "Port Louis", "Operator" => ""),
    "201" => array ( "Area" => "Government Centre", "Operator" => ""),
    "69" => array ( "Area" => "South Region", "Operator" => "Mauritius Telecom Ltd"),
    "68" => array ( "Area" => "South Region", "Operator" => "Mauritius Telecom Ltd"),
    "67" => array ( "Area" => "South Region", "Operator" => "Mauritius Telecom Ltd"),
    "66" => array ( "Area" => "South Region", "Operator" => "Mauritius Telecom Ltd"),
    "65" => array ( "Area" => "", "Operator" => "Emtel Ltd"),
    "64" => array ( "Area" => "South Region", "Operator" => "Mauritius Telecom Ltd"),
    "63" => array ( "Area" => "South Region", "Operator" => "Mauritius Telecom Ltd"),
    "62" => array ( "Area" => "South Region", "Operator" => "Mauritius Telecom Ltd"),
    "61" => array ( "Area" => "South Region", "Operator" => "Mauritius Telecom Ltd"),
    "60" => array ( "Area" => "South Region", "Operator" => "Mauritius Telecom Ltd"),
    "29" => array ( "Area" => "North Region", "Operator" => "Mauritius Telecom Ltd or Mahanagar Telephone Mauritius Ltd"),
    "4" => array ( "Area" => "", "Operator" => ""),
    "2" => array ( "Area" => "", "Operator" => "")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "230", "NDC" => "", "Country" => "Mauritius", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+230 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Audiotext network
   */
  $prefixes = array (
    "317"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "230", "NDC" => "", "Country" => "Mauritius", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_AUDIOTEXT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+230 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for Audiotext (Premium) network
   */
  $prefixes = array (
    "3019",
    "3016",
    "3011",
    "303",
    "302"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "230", "NDC" => "", "Country" => "Mauritius", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_AUDIOTEXT + VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+230 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for FMC line network
   */
  $prefixes = array (
    "543" => "Emtel Ltd"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "230", "NDC" => "", "Country" => "Mauritius", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+230 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for PRN line network
   */
  $prefixes = array (
    "30"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "230", "NDC" => "", "Country" => "Mauritius", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+230 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Mauritian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
