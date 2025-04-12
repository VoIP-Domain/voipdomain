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
 * related to country database of Benin.
 *
 * Reference: https://www.itu.int/oth/T0202000017/en (2006-07-20)
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
 * E.164 Benin country hook
 */
framework_add_filter ( "e164_identify_country_BEN", "e164_identify_country_BEN");

/**
 * E.164 Benian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BEN" (code for Benin). This
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
function e164_identify_country_BEN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Benin
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+229")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Benin has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "9798" => "Spacetel (Areeba)",
    "9797" => "Spacetel (Areeba)",
    "9789" => "Spacetel (Areeba)",
    "9788" => "Spacetel (Areeba)",
    "9787" => "Spacetel (Areeba)",
    "9777" => "Spacetel (Areeba)",
    "9776" => "Spacetel (Areeba)",
    "9772" => "Spacetel (Areeba)",
    "9769" => "Spacetel (Areeba)",
    "9768" => "Spacetel (Areeba)",
    "9764" => "Spacetel (Areeba)",
    "9760" => "Spacetel (Areeba)",
    "9758" => "Spacetel (Areeba)",
    "9757" => "Spacetel (Areeba)",
    "9748" => "Spacetel (Areeba)",
    "9747" => "Spacetel (Areeba)",
    "9744" => "Spacetel (Areeba)",
    "9713" => "Spacetel (Areeba)",
    "9709" => "Spacetel (Areeba)",
    "9708" => "Spacetel (Areeba)",
    "9707" => "Spacetel (Areeba)",
    "9596" => "Telecel",
    "9595" => "Telecel",
    "9586" => "Telecel",
    "9585" => "Telecel",
    "9584" => "Telecel",
    "9581" => "Telecel",
    "9579" => "Telecel",
    "9571" => "Telecel",
    "9556" => "Telecel",
    "9545" => "Telecel",
    "9542" => "Telecel",
    "9540" => "Telecel",
    "9528" => "Telecel",
    "9515" => "Telecel",
    "9506" => "Telecel",
    "9505" => "Telecel",
    "9399" => "BBCom",
    "9374" => "BBCom",
    "9370" => "BBCom",
    "9323" => "BBCom",
    "9320" => "BBCom",
    "9314" => "BBCom",
    "9094" => "Libercom",
    "9093" => "Libercom",
    "9092" => "Libercom",
    "9091" => "Libercom",
    "9090" => "Libercom",
    "9066" => "Libercom",
    "9004" => "Libercom",
    "9003" => "Libercom",
    "9002" => "Libercom",
    "9001" => "Libercom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "229", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Benin", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+229 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "2383" => array ( "area" => "Atacora and Donga and Alibori and Borgou", "city" => "Tanguiéta"),
    "2382" => array ( "area" => "Atacora and Donga and Alibori and Borgou", "city" => "Natitingou"),
    "2380" => array ( "area" => "Atacora and Donga and Alibori and Borgou", "city" => "Djougou"),
    "2367" => array ( "area" => "Atacora and Donga and Alibori and Borgou", "city" => "Malanville"),
    "2365" => array ( "area" => "Atacora and Donga and Alibori and Borgou", "city" => "Banikoara"),
    "2363" => array ( "area" => "Atacora and Donga and Alibori and Borgou", "city" => "Kandi and Gogounou and Ségbana"),
    "2362" => array ( "area" => "Atacora and Donga and Alibori and Borgou", "city" => "Nikki and Ndali"),
    "2361" => array ( "area" => "Atacora and Donga and Alibori and Borgou", "city" => "Parakou"),
    "2255" => array ( "area" => "Mono and Couffo and Zou and Collines", "city" => "Savè"),
    "2254" => array ( "area" => "Mono and Couffo and Zou and Collines", "city" => "Savalou"),
    "2253" => array ( "area" => "Mono and Couffo and Zou and Collines", "city" => "Dassa-Zoumé"),
    "2252" => array ( "area" => "Mono and Couffo and Zou and Collines", "city" => "Covè"),
    "2251" => array ( "area" => "Mono and Couffo and Zou and Collines", "city" => "Bohicon"),
    "2250" => array ( "area" => "Mono and Couffo and Zou and Collines", "city" => "Abomey"),
    "2246" => array ( "area" => "Mono and Couffo and Zou and Collines", "city" => "Dogbo"),
    "2243" => array ( "area" => "Mono and Couffo and Zou and Collines", "city" => "Come"),
    "2241" => array ( "area" => "Mono and Couffo and Zou and Collines", "city" => "Lokossa"),
    "2138" => array ( "area" => "Littoral and Atlantique", "city" => "Kouhounou Fixe"),
    "2137" => array ( "area" => "Littoral and Atlantique", "city" => "Allada"),
    "2136" => array ( "area" => "Littoral and Atlantique", "city" => "Abomey-Calaci"),
    "2135" => array ( "area" => "Littoral and Atlantique", "city" => "Godomey"),
    "2134" => array ( "area" => "Littoral and Atlantique", "city" => "Ouidah"),
    "2133" => array ( "area" => "Littoral and Atlantique", "city" => "Akpakpa"),
    "2132" => array ( "area" => "Littoral and Atlantique", "city" => "Jéricho"),
    "2131" => array ( "area" => "Littoral and Atlantique", "city" => "Ganhi"),
    "2130" => array ( "area" => "Littoral and Atlantique", "city" => "Cadjehoun"),
    "2027" => array ( "area" => "Ouémé and Plateau", "city" => "Adjohoun"),
    "2026" => array ( "area" => "Ouémé and Plateau", "city" => "Sakété and Igolo"),
    "2025" => array ( "area" => "Ouémé and Plateau", "city" => "Pobè and Kétou"),
    "2024" => array ( "area" => "Ouémé and Plateau", "city" => "Sèmè"),
    "2022" => array ( "area" => "Ouémé and Plateau", "city" => "Kandiévé"),
    "2021" => array ( "area" => "Ouémé and Plateau", "city" => "Ongala")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "229", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Benin", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+229 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for FMC network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "2259" => "Mono and Couffo and Zou and Collines",
    "2249" => "Mono and Couffo and Zou and Collines",
    "2139" => "Littoral and Atlantique",
    "2029" => "Ouémé and Plateau"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "229", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Benin", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+229 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for Audiotext network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "807617" => "No. Videotext 4",
    "807615" => "No. Videotext 3",
    "807614" => "No. Videotext 2",
    "807610" => "No. Videotext 1"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "229", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Benin", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_AUDIOTEXT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+229 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for VoIP network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "8578" => "Nàsuba",
    "8575" => "Nàsuba"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "229", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Benin", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+229 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for Maritime Radio network with 4 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "21736" => "Littoral and Atlantique"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "229", "NDC" => substr ( $parameters["Number"], 4, 4), "Country" => "Benin", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MARINERADIO, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+229 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Benian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
