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
 * related to country database of Jamaica.
 *
 * Reference: https://www.itu.int/oth/T020200006C/en (2006-11-22)
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
 * E.164 Jamaica country hook
 */
framework_add_filter ( "e164_identify_country_JAM", "e164_identify_country_JAM");

/**
 * E.164 Jamaican area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "JAM" (code for Jamaica). This
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
function e164_identify_country_JAM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Jamaica
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1876")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Jamaica has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "999",
    "997",
    "995",
    "990",
    "919",
    "909",
    "879",
    "878",
    "877",
    "875",
    "874",
    "873",
    "871",
    "870",
    "869",
    "868",
    "867",
    "866",
    "865",
    "864",
    "863",
    "862",
    "861",
    "860",
    "859",
    "858",
    "857",
    "856",
    "855",
    "854",
    "853",
    "852",
    "851",
    "850",
    "849",
    "848",
    "847",
    "846",
    "845",
    "844",
    "843",
    "842",
    "841",
    "840",
    "839",
    "838",
    "837",
    "836",
    "835",
    "834",
    "833",
    "832",
    "831",
    "830",
    "829",
    "828",
    "827",
    "826",
    "825",
    "824",
    "823",
    "822",
    "821",
    "820",
    "819",
    "818",
    "817",
    "816",
    "815",
    "814",
    "813",
    "812",
    "809",
    "808",
    "807",
    "806",
    "799",
    "798",
    "797",
    "796",
    "793",
    "792",
    "791",
    "790",
    "789",
    "788",
    "787",
    "784",
    "783",
    "782",
    "781",
    "779",
    "778",
    "777",
    "776",
    "775",
    "774",
    "773",
    "772",
    "771",
    "770",
    "707",
    "700",
    "494",
    "493",
    "492",
    "491",
    "490",
    "429",
    "428",
    "427",
    "426",
    "425",
    "424",
    "423",
    "422",
    "421",
    "420",
    "399",
    "398",
    "397",
    "396",
    "395",
    "394",
    "393",
    "392",
    "391",
    "390",
    "389",
    "388",
    "387",
    "386",
    "385",
    "374",
    "373",
    "372",
    "371",
    "370",
    "369",
    "368",
    "367",
    "366",
    "365",
    "364",
    "363",
    "362",
    "361",
    "360",
    "359",
    "358",
    "357",
    "356",
    "355",
    "354",
    "353",
    "352",
    "351",
    "350",
    "349",
    "348",
    "347",
    "346",
    "345",
    "344",
    "343",
    "342",
    "341",
    "340",
    "339",
    "338",
    "337",
    "336",
    "335",
    "334",
    "333",
    "332",
    "331",
    "330",
    "322",
    "321",
    "320",
    "304",
    "303",
    "302",
    "301",
    "210"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1 876", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Jamaica", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 876 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "998",
    "996",
    "994",
    "993",
    "992",
    "989",
    "988",
    "987",
    "986",
    "985",
    "984",
    "983",
    "982",
    "981",
    "980",
    "979",
    "978",
    "977",
    "976",
    "975",
    "974",
    "973",
    "972",
    "971",
    "970",
    "969",
    "968",
    "967",
    "966",
    "965",
    "964",
    "963",
    "962",
    "961",
    "960",
    "958",
    "957",
    "956",
    "955",
    "954",
    "953",
    "952",
    "949",
    "948",
    "947",
    "946",
    "945",
    "944",
    "943",
    "942",
    "941",
    "940",
    "939",
    "938",
    "937",
    "936",
    "935",
    "934",
    "933",
    "932",
    "931",
    "930",
    "929",
    "928",
    "927",
    "926",
    "925",
    "924",
    "923",
    "922",
    "920",
    "918",
    "917",
    "913",
    "912",
    "910",
    "908",
    "907",
    "906",
    "905",
    "904",
    "903",
    "902",
    "901",
    "795",
    "794",
    "786",
    "785",
    "780",
    "765",
    "764",
    "759",
    "758",
    "757",
    "755",
    "754",
    "750",
    "749",
    "748",
    "746",
    "745",
    "740",
    "734",
    "733",
    "726",
    "725",
    "724",
    "715",
    "709",
    "708",
    "706",
    "705",
    "704",
    "703",
    "702",
    "694",
    "684",
    "680",
    "675",
    "670",
    "663",
    "650",
    "640",
    "634",
    "625",
    "624",
    "623",
    "617",
    "612",
    "610",
    "609",
    "607",
    "605",
    "603",
    "602",
    "601",
    "563",
    "525",
    "523",
    "518",
    "516",
    "514",
    "513",
    "512",
    "511",
    "510",
    "502",
    "501"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1 876", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Jamaica", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 876 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for voicemail network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "699",
    "698",
    "697",
    "696",
    "695"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1 876", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Jamaica", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 876 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for voicemail network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "805",
    "804",
    "803",
    "802",
    "801"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1 876", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Jamaica", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_VOICEMAIL, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 876 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Jamaican phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
