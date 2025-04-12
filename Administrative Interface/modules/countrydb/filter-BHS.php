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
 * related to country database of Bahamas.
 *
 * Reference: https://www.itu.int/oth/T0202000010/en (2017-10-13)
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
 * E.164 Bahamas country hook
 */
framework_add_filter ( "e164_identify_country_BHS", "e164_identify_country_BHS");

/**
 * E.164 Bahamian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BHS" (code for Bahamas). This
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
function e164_identify_country_BHS ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Bahamas
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1242")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Bahamas has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "889" => "NewCo2015 Limited",
    "819" => "NewCo2015 Limited",
    "818" => "NewCo2015 Limited",
    "817" => "NewCo2015 Limited",
    "816" => "NewCo2015 Limited",
    "815" => "NewCo2015 Limited",
    "814" => "NewCo2015 Limited",
    "813" => "NewCo2015 Limited",
    "812" => "NewCo2015 Limited",
    "810" => "NewCo2015 Limited",
    "809" => "NewCo2015 Limited",
    "808" => "NewCo2015 Limited",
    "807" => "NewCo2015 Limited",
    "806" => "NewCo2015 Limited",
    "805" => "NewCo2015 Limited",
    "804" => "NewCo2015 Limited",
    "803" => "NewCo2015 Limited",
    "802" => "NewCo2015 Limited",
    "801" => "NewCo2015 Limited",
    "738" => "NewCo2015 Limited",
    "727" => "The Bahamas Telecommunications Company Limited",
    "646" => "The Bahamas Telecommunications Company Limited",
    "636" => "The Bahamas Telecommunications Company Limited",
    "559" => "The Bahamas Telecommunications Company Limited",
    "558" => "The Bahamas Telecommunications Company Limited",
    "557" => "The Bahamas Telecommunications Company Limited",
    "556" => "The Bahamas Telecommunications Company Limited",
    "554" => "The Bahamas Telecommunications Company Limited",
    "553" => "The Bahamas Telecommunications Company Limited",
    "552" => "The Bahamas Telecommunications Company Limited",
    "551" => "The Bahamas Telecommunications Company Limited",
    "544" => "The Bahamas Telecommunications Company Limited",
    "535" => "The Bahamas Telecommunications Company Limited",
    "533" => "The Bahamas Telecommunications Company Limited",
    "525" => "The Bahamas Telecommunications Company Limited",
    "524" => "The Bahamas Telecommunications Company Limited",
    "481" => "The Bahamas Telecommunications Company Limited",
    "468" => "The Bahamas Telecommunications Company Limited",
    "467" => "The Bahamas Telecommunications Company Limited",
    "466" => "The Bahamas Telecommunications Company Limited",
    "465" => "The Bahamas Telecommunications Company Limited",
    "464" => "The Bahamas Telecommunications Company Limited",
    "463" => "The Bahamas Telecommunications Company Limited",
    "462" => "The Bahamas Telecommunications Company Limited",
    "458" => "The Bahamas Telecommunications Company Limited",
    "457" => "The Bahamas Telecommunications Company Limited",
    "456" => "The Bahamas Telecommunications Company Limited",
    "455" => "The Bahamas Telecommunications Company Limited",
    "454" => "The Bahamas Telecommunications Company Limited",
    "453" => "The Bahamas Telecommunications Company Limited",
    "452" => "The Bahamas Telecommunications Company Limited",
    "451" => "The Bahamas Telecommunications Company Limited",
    "449" => "The Bahamas Telecommunications Company Limited",
    "448" => "The Bahamas Telecommunications Company Limited",
    "447" => "The Bahamas Telecommunications Company Limited",
    "446" => "The Bahamas Telecommunications Company Limited",
    "445" => "The Bahamas Telecommunications Company Limited",
    "443" => "The Bahamas Telecommunications Company Limited",
    "442" => "The Bahamas Telecommunications Company Limited",
    "441" => "The Bahamas Telecommunications Company Limited",
    "439" => "The Bahamas Telecommunications Company Limited",
    "438" => "The Bahamas Telecommunications Company Limited",
    "437" => "The Bahamas Telecommunications Company Limited",
    "436" => "The Bahamas Telecommunications Company Limited",
    "435" => "The Bahamas Telecommunications Company Limited",
    "434" => "The Bahamas Telecommunications Company Limited",
    "433" => "The Bahamas Telecommunications Company Limited",
    "432" => "The Bahamas Telecommunications Company Limited",
    "431" => "The Bahamas Telecommunications Company Limited",
    "429" => "The Bahamas Telecommunications Company Limited",
    "428" => "The Bahamas Telecommunications Company Limited",
    "427" => "The Bahamas Telecommunications Company Limited",
    "426" => "The Bahamas Telecommunications Company Limited",
    "425" => "The Bahamas Telecommunications Company Limited",
    "424" => "The Bahamas Telecommunications Company Limited",
    "423" => "The Bahamas Telecommunications Company Limited",
    "422" => "The Bahamas Telecommunications Company Limited",
    "421" => "The Bahamas Telecommunications Company Limited",
    "395" => "The Bahamas Telecommunications Company Limited",
    "376" => "The Bahamas Telecommunications Company Limited",
    "375" => "The Bahamas Telecommunications Company Limited",
    "359" => "The Bahamas Telecommunications Company Limited",
    "357" => "The Bahamas Telecommunications Company Limited",
    "82" => "NewCo2015 Limited",
    "47" => "The Bahamas Telecommunications Company Limited"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1242", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Bahamas", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 242 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "6996" => array ( "area" => "Eleuthera", "operator" => "Systems Resource Group Limited"),
    "6995" => array ( "area" => "Eleuthera", "operator" => "Systems Resource Group Limited"),
    "6994" => array ( "area" => "Abaco", "operator" => "Systems Resource Group Limited"),
    "6993" => array ( "area" => "Abaco", "operator" => "Systems Resource Group Limited"),
    "6992" => array ( "area" => "Abaco", "operator" => "Systems Resource Group Limited"),
    "6991" => array ( "area" => "Abaco", "operator" => "Systems Resource Group Limited"),
    "6990" => array ( "area" => "Abaco", "operator" => "Systems Resource Group Limited"),
    "3475" => array ( "area" => "Bimini and Cat Cay", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3474" => array ( "area" => "Bimini and Cat Cay", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3473" => array ( "area" => "Bimini and Cat Cay", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3472" => array ( "area" => "Bimini and Cat Cay", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3471" => array ( "area" => "Bimini and Cat Cay", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3470" => array ( "area" => "Bimini and Cat Cay", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3443" => array ( "area" => "Acklins", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3442" => array ( "area" => "Crooked Island", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3441" => array ( "area" => "Ragged Island", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3394" => array ( "area" => "Mayaguana", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3393" => array ( "area" => "Mayaguana", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3392" => array ( "area" => "Inagua", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3391" => array ( "area" => "Inagua", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3313" => array ( "area" => "San Salvador", "operator" => "The Bahamas Telecommunications Company Limited"),
    "3312" => array ( "area" => "Rum Cay and San Salvador", "operator" => "The Bahamas Telecommunications Company Limited"),
    "788" => array ( "area" => "", "operator" => "Cable Bahamas Limited"),
    "702" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "698" => array ( "area" => "New Providence", "operator" => "Systems Resource Group Limited"),
    "688" => array ( "area" => "Grand Bahama", "operator" => "Systems Resource Group Limited"),
    "687" => array ( "area" => "Grand Bahama", "operator" => "Systems Resource Group Limited"),
    "677" => array ( "area" => "New Providence", "operator" => "Systems Resource Group Limited"),
    "676" => array ( "area" => "New Providence", "operator" => "Systems Resource Group Limited"),
    "650" => array ( "area" => "", "operator" => "Peace Holdings Communications Limited"),
    "640" => array ( "area" => "", "operator" => "JazzTel Bahamas Limited"),
    "623" => array ( "area" => "", "operator" => "Last Mile Communications Limited"),
    "621" => array ( "area" => "", "operator" => "Andros Lakeside Development Company Limited"),
    "620" => array ( "area" => "", "operator" => "Tycoon Global Limited"),
    "612" => array ( "area" => "", "operator" => "IP Solutions International Limited"),
    "604" => array ( "area" => "", "operator" => "Cable Bahamas Limited"),
    "603" => array ( "area" => "", "operator" => "Cable Bahamas Limited"),
    "602" => array ( "area" => "Grand Bahama", "operator" => "Cable Bahamas Limited"),
    "601" => array ( "area" => "New Providence", "operator" => "Cable Bahamas Limited"),
    "502" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "461" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "397" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "396" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "394" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "393" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "392" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "384" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "383" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "382" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "381" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "380" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "377" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "374" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "373" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "369" => array ( "area" => "Andros", "operator" => "The Bahamas Telecommunications Company Limited"),
    "368" => array ( "area" => "Andros", "operator" => "The Bahamas Telecommunications Company Limited"),
    "367" => array ( "area" => "Abaco", "operator" => "The Bahamas Telecommunications Company Limited"),
    "366" => array ( "area" => "Abaco", "operator" => "The Bahamas Telecommunications Company Limited"),
    "365" => array ( "area" => "Abaco", "operator" => "The Bahamas Telecommunications Company Limited"),
    "364" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "363" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "362" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "361" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "358" => array ( "area" => "Exuma", "operator" => "The Bahamas Telecommunications Company Limited"),
    "356" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "355" => array ( "area" => "Exuma", "operator" => "The Bahamas Telecommunications Company Limited"),
    "354" => array ( "area" => "Cat Island", "operator" => "The Bahamas Telecommunications Company Limited"),
    "353" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "352" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "351" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "350" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "349" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "348" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "347" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "346" => array ( "area" => "Grand Bahama", "operator" => "The Bahamas Telecommunications Company Limited"),
    "345" => array ( "area" => "Exuma", "operator" => "The Bahamas Telecommunications Company Limited"),
    "342" => array ( "area" => "Cat Island", "operator" => "The Bahamas Telecommunications Company Limited"),
    "341" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "340" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "338" => array ( "area" => "Long Island", "operator" => "The Bahamas Telecommunications Company Limited"),
    "337" => array ( "area" => "Long Island", "operator" => "The Bahamas Telecommunications Company Limited"),
    "336" => array ( "area" => "Exuma", "operator" => "The Bahamas Telecommunications Company Limited"),
    "335" => array ( "area" => "Eleuthera", "operator" => "The Bahamas Telecommunications Company Limited"),
    "334" => array ( "area" => "Eleuthera", "operator" => "The Bahamas Telecommunications Company Limited"),
    "333" => array ( "area" => "Eleuthera", "operator" => "The Bahamas Telecommunications Company Limited"),
    "332" => array ( "area" => "Eleuthera", "operator" => "The Bahamas Telecommunications Company Limited"),
    "329" => array ( "area" => "Andros", "operator" => "The Bahamas Telecommunications Company Limited"),
    "328" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "327" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "326" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "325" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "324" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "323" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "322" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "321" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited"),
    "302" => array ( "area" => "New Providence", "operator" => "The Bahamas Telecommunications Company Limited")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1242", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Bahamas", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 242 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Bahamian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
