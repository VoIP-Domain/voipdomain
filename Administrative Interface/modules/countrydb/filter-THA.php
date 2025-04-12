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
 * related to country database of Thailand.
 *
 * Reference: https://www.itu.int/oth/T02020000CD/en (2020-04-14)
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
 * E.164 Thailand country hook
 */
framework_add_filter ( "e164_identify_country_THA", "e164_identify_country_THA");

/**
 * E.164 Thailand area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "THA" (code for Thailand). This
 * hook will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information, if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_THA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Thailand
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+66")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Thailand has 11 or 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 11 || strlen ( $parameters["Number"]) > 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC, 7 digits SN
   */
  $prefixes = array (
    "88",
    "87",
    "86",
    "85",
    "84",
    "83",
    "82",
    "81",
    "80",
    "9"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "66", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Thailand", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+66 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC, 6 digits SN
   */
  $prefixes = array (
    "79" => array ( "Area" => "Southern region", "City" => ""),
    "78" => array ( "Area" => "Southern region", "City" => ""),
    "77" => array ( "Area" => "Southern region", "City" => "Surat Thani, Chumpon, Ranong"),
    "76" => array ( "Area" => "Southern region", "City" => "Phuket, Phang-nga"),
    "75" => array ( "Area" => "Southern region", "City" => "Trang, Nakhon Sri Thammarat, Krabi"),
    "74" => array ( "Area" => "Southern region", "City" => "Songkla, Phattalung, Satun"),
    "73" => array ( "Area" => "Southern region", "City" => "Yala, Pattani, Narathiwat"),
    "72" => array ( "Area" => "Southern region", "City" => ""),
    "71" => array ( "Area" => "Southern region", "City" => ""),
    "70" => array ( "Area" => "Southern region", "City" => ""),
    "59" => array ( "Area" => "Northern region", "City" => ""),
    "58" => array ( "Area" => "Northern region", "City" => ""),
    "57" => array ( "Area" => "Northern region", "City" => ""),
    "56" => array ( "Area" => "Northern region", "City" => "Nakhon Sawan, Uthai Thani, Phichit Chainat, Phetchabun"),
    "55" => array ( "Area" => "Northern region", "City" => "Phitsanulok, Uttaradit, Tak, Sukhothai, Kamphaeng Phet"),
    "54" => array ( "Area" => "Northern region", "City" => "Lampang, Phayao, Phrae, Nan"),
    "53" => array ( "Area" => "Northern region", "City" => "Chiang Mai, Lamphun, Mae Hong Son, Chiang Rai"),
    "52" => array ( "Area" => "Northern region", "City" => "Chiang Mai, Lamphun, Mae Hong Son, Chiang Rai"),
    "51" => array ( "Area" => "Northern region", "City" => ""),
    "50" => array ( "Area" => "Northern region", "City" => ""),
    "49" => array ( "Area" => "North-eastern region", "City" => ""),
    "48" => array ( "Area" => "North-eastern region", "City" => ""),
    "47" => array ( "Area" => "North-eastern region", "City" => ""),
    "46" => array ( "Area" => "North-eastern region", "City" => ""),
    "45" => array ( "Area" => "North-eastern region", "City" => "Ubon Ratchathani, Amnat Charoen, Srisaket, Yasothon"),
    "44" => array ( "Area" => "North-eastern region", "City" => "Nakhon Ratchasima, Surin, Buriram, Chaiyaphum"),
    "43" => array ( "Area" => "North-eastern region", "City" => "Khon Kean, Roi Et, Maha Sarakham, Kalasin"),
    "42" => array ( "Area" => "North-eastern region", "City" => "Udon Thani, Bueng Kan, Nhongbua Lamphu, Nong Khai, Nakhon Phanom, Mukdahan, Sakon Nakhon, Loei"),
    "41" => array ( "Area" => "North-eastern region", "City" => ""),
    "40" => array ( "Area" => "North-eastern region", "City" => ""),
    "39" => array ( "Area" => "Eastern region", "City" => "Chanthaburi, Trat"),
    "38" => array ( "Area" => "Eastern region", "City" => "Chacheongsao, Rayong, Chon Buri"),
    "37" => array ( "Area" => "Eastern region", "City" => "Prachin Buri, Sa Kaeo, Nakhon Nayok"),
    "36" => array ( "Area" => "Central region", "City" => "Saraburi, Lopburi, Singburi"),
    "35" => array ( "Area" => "Central region", "City" => "Pranakhon Sri Ayutthaya, Suphan Buri, Ang Thong"),
    "34" => array ( "Area" => "Central region", "City" => "Nakhon Pathom, Samut Sakhon, Kanchanaburi, Samut Songkhram"),
    "33" => array ( "Area" => "Eastern region", "City" => "Chacheongsao, Rayong, Chon Buri"),
    "32" => array ( "Area" => "Central region", "City" => "Ratchaburi, Phetchaburi, Prachuap Khirikhan"),
    "31" => array ( "Area" => "Central, Eastern, Western regions (except Bangkok, vicinity)", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "30" => array ( "Area" => "Central, Eastern, Western regions (except Bangkok, vicinity)", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "29" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "28" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "27" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "26" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "25" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "24" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "23" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "22" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "21" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "20" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "19" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "18" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "16" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn"),
    "10" => array ( "Area" => "Bangkok, Vicinity", "City" => "Nonthaburi, Pathum Thani, Samut Prakarn")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 11 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "66", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Thailand", "Area" => $data["Area"], "City" => $data["City"], "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+66 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for mobile network with 2 digits NDC, 7 digits SN
   */
  if ( strlen ( $parameters["Number"]) == 12 && substr ( $parameters["Number"], 3, 2) == "60")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "66", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Thailand", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+66 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * If reached here, number wasn't identified as a valid Thailand phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
