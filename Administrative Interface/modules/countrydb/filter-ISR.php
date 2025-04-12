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
 * related to country database of Israel.
 *
 * Reference: https://www.itu.int/oth/T020200006A/en (2018-10-12)
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
 * E.164 Israel country hook
 */
framework_add_filter ( "e164_identify_country_ISR", "e164_identify_country_ISR");

/**
 * E.164 Israelian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "ISR" (code for Israel). This
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
function e164_identify_country_ISR ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Israel
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+972")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "7688" => "Bezeq",
    "7681" => "Bezeq",
    "7680" => "Bezeq",
    "5599" => "Telzar Ltd.",
    "5598" => "Telzar Ltd.",
    "5597" => "Telzar Ltd.",
    "5596" => "Telzar Ltd.",
    "5595" => "Telzar Ltd.",
    "5594" => "Telzar Ltd.",
    "5593" => "Telzar Ltd.",
    "5592" => "Telzar Ltd.",
    "5591" => "Telzar Ltd.",
    "5589" => "Pelephone (Alon Cellular)",
    "5588" => "Pelephone (Alon Cellular)",
    "5587" => "Pelephone (Alon Cellular)",
    "5572" => "Cellact Communications Ltd.",
    "5571" => "Cellact Communications Ltd.",
    "5570" => "Cellact Communications Ltd.",
    "5568" => "Rami Levi",
    "5567" => "Rami Levi",
    "5566" => "Rami Levi",
    "5551" => "LB Annatel Ltd.",
    "5550" => "LB Annatel Ltd.",
    "5533" => "Free Telecom",
    "5532" => "Free Telecom",
    "5525" => "Telzar Ltd.",
    "5524" => "Telzar Ltd.",
    "5523" => "Home Cellular",
    "5522" => "Home Cellular",
    "799" => "Telzar Ltd.",
    "798" => "LB Annatel Ltd.",
    "797" => "Cellat Communications Ltd.",
    "795" => "Hashikma N.G.N. International Communications 015 Ltd.",
    "793" => "Binat Business Ltd.",
    "792" => "Free Telecom",
    "782" => "Golan",
    "765" => "B.I.P.",
    "747" => "Partner Fixed Line",
    "737" => "Veidan",
    "733" => "Cellcom Fixed Line",
    "732" => "Cellcom Fixed Line",
    "723" => "012 Telecom",
    "722" => "012 Telecom",
    "718" => "Exphone 018",
    "569" => "",
    "568" => "",
    "567" => "",
    "566" => "",
    "565" => "",
    "564" => "",
    "563" => "",
    "562" => "",
    "77" => "Hot Telecom",
    "59" => "Jawall",
    "58" => "Golan Telecom",
    "55" => "MVNO",
    "54" => "Partner",
    "53" => "Hot Mobile",
    "52" => "Cellcom",
    "51" => "Marathon 018 Xphone Ltd.",
    "50" => "Pelephone (Alon Cellular)"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "972", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Israel", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+972 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1 digit NDC and 7 digits SN
   */
  $prefixes = array (
    "9" => "Hasharon",
    "8" => "Hashfela and South Regions",
    "4" => "Haifa and North Regions",
    "3" => "Tel Aviv",
    "2" => "Jerusalem"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "972", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Israel", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+972 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Israelian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
