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
 * related to country database of Georgia.
 *
 * Reference: https://www.itu.int/oth/T0202000050/en (2011-06-08)
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
 * E.164 Georgia country hook
 */
framework_add_filter ( "e164_identify_country_GEO", "e164_identify_country_GEO");

/**
 * E.164 Georgian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "GEO" (code for Georgia). This
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
function e164_identify_country_GEO ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Georgia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+995")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Georgia has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 5 digits SN
   */
  $prefixes = array (
    "790" => "Magti",
    "599" => "Magti",
    "598" => "Magti",
    "597" => "Mobitel",
    "596" => "Magti",
    "595" => "Magti",
    "593" => "Geo Cell",
    "592" => "Mobitel",
    "579" => "Mobitel",
    "578" => "Silqnet",
    "577" => "Geo Cell",
    "574" => "Mobitel",
    "571" => "Mobitel",
    "570" => "Silqnet",
    "568" => "Mobitel",
    "558" => "Geo Cell",
    "557" => "Geo Cell",
    "555" => "Geo Cell",
    "551" => "Magti",
    "550" => "Magti",
    "514" => "Geo Cell"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "995", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Georgia", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+995 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "497" => "Tkibuli",
    "496" => "Ozurgeti",
    "495" => "Khoni",
    "494" => "Lanchxuti",
    "493" => "Poti",
    "492" => "Zestafoni",
    "491" => "Terdjola",
    "479" => "Chiatura",
    "473" => "Oni",
    "472" => "Tsageri",
    "448" => "Gulripshi",
    "447" => "Gali",
    "446" => "Tkvarcheli",
    "445" => "Ochamchire",
    "444" => "Gudauta",
    "443" => "Gagra",
    "442" => "Sukhumi",
    "439" => "Ambrolauri",
    "437" => "Lentekhi",
    "436" => "Tskaltubo",
    "435" => "Sachkhere",
    "434" => "Bagdati",
    "433" => "Kharagauli",
    "432" => "Vani",
    "431" => "Kutaisi",
    "427" => "Xelvachauri",
    "426" => "Kobuleti",
    "425" => "Qeda",
    "424" => "Shuaxevi",
    "423" => "Xulo",
    "422" => "Batumi",
    "419" => "Choxatauri",
    "418" => "Martvili",
    "417" => "Chkhorotskhu",
    "416" => "Tsalendjikha",
    "415" => "Zugdidi",
    "414" => "Xobi",
    "413" => "Senaki",
    "412" => "Abasha",
    "411" => "Samtredia",
    "410" => "Mestia",
    "374" => "Tigvi",
    "373" => "Mtskheta",
    "372" => "Gardabani",
    "371" => "Kaspi",
    "370" => "Gori",
    "369" => "Kareli",
    "368" => "Khashuri",
    "367" => "Bordjomi",
    "366" => "Adigeni",
    "365" => "Akhaltsikhe",
    "364" => "Aspindza",
    "363" => "Tsalka",
    "362" => "Akhalkalaki",
    "361" => "Ninotsminda",
    "360" => "Dmanisi",
    "359" => "Tetritskaro",
    "358" => "Bolnisi",
    "357" => "Marneuli",
    "356" => "Dedoplistskaro",
    "355" => "Signagi",
    "354" => "Lagodekhi",
    "353" => "Gurdjaani",
    "352" => "Kvareli",
    "351" => "Sagaredjo",
    "350" => "Telavi",
    "349" => "Akhmeta",
    "348" => "Tianeti",
    "347" => "Djava",
    "346" => "Dusheti",
    "345" => "Stefanstminda (Kazbegi)",
    "344" => "Tskhinvali",
    "342" => "Akhalgori",
    "341" => "Rustavi",
    "32" => "Tbilisi"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "995", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Georgia", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+995 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for FMC network with 3 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "591" => "Magti"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "995", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Georgia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+995 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Georgian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
