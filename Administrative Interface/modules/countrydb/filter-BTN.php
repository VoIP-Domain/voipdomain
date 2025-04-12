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
 * related to country database of Bhutan.
 *
 * Reference: https://www.itu.int/oth/T0202000019/en (2006-07-20)
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
 * E.164 Bhutan country hook
 */
framework_add_filter ( "e164_identify_country_BTN", "e164_identify_country_BTN");

/**
 * E.164 Buthanian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BTN" (code for Bhutan). This
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
function e164_identify_country_BTN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Bhutan
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+975")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "179" => "B-Mobile Prepaid",
    "178" => "B-Mobile Prepaid",
    "177" => "B-Mobile Prepaid",
    "176" => "B-Mobile Prepaid",
    "175" => "B-Mobile Postpaid",
    "174" => "B-Mobile Postpaid",
    "173" => "B-Mobile Postpaid",
    "172" => "B-Mobile Postpaid",
    "171" => "B-Mobile Postpaid",
    "170" => "B-Mobile Postpaid"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "975", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Bhutan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+975 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 1 digit NDC and 7 digits SN
   */
  $prefixes = array (
    "76713" => "P/Gatshel DAMA",
    "76712" => "Nganglam DAMA",
    "76711" => "Daifam DAMA",
    "76710" => "Bangtar DAMA",
    "66709" => "Panbang DAMA",
    "66708" => "Damphu DAMA",
    "56706" => "Lhamoi Zingkha",
    "46704" => "Sakteng DAMA",
    "36703" => "Zhemgang DAMA",
    "26700" => "Lingshi DAMA",
    "8478" => "Tsimasham",
    "8477" => "Wangkha",
    "8471" => "Chapcha",
    "8375" => "Ha",
    "8371" => "Ha (Damthang)",
    "8271" => "Paro",
    "8270" => "Paro",
    "7471" => "P/Gatshel",
    "7470" => "P/Gatshel",
    "7261" => "Deothang",
    "7260" => "Deothang",
    "7251" => "S/Jongkhar",
    "7250" => "S/Jongkhar",
    "6487" => "Drujegang",
    "6483" => "Dagapela",
    "6481" => "Dagana",
    "6480" => "Dagana",
    "6471" => "Tsirang (Damphu)",
    "6470" => "Tsirang (Damphu)",
    "6365" => "Sarpang",
    "6255" => "Suray",
    "6251" => "Gelephu",
    "6250" => "Gelephu",
    "5382" => "Sibsoo",
    "5381" => "Chargary",
    "5380" => "Chargary",
    "5371" => "Gomtu",
    "5370" => "Gomtu",
    "5365" => "Samtse",
    "5279" => "Gedu and Tala and Padechu and Sinchekha",
    "5277" => "Gedu and Tala and Padechu and Sinchekha",
    "5276" => "Gedu and Tala and Padechu and Sinchekha",
    "5274" => "Gedu and Tala and Padechu and Sinchekha",
    "5273" => "Gedu and Tala and Padechu and Sinchekha",
    "5272" => "Gedu and Tala and Padechu and Sinchekha",
    "5271" => "Gedu and Tala and Padechu and Sinchekha",
    "5270" => "Gedu and Tala and Padechu and Sinchekha",
    "5261" => "Pasakha",
    "5260" => "Pasakha",
    "6252" => "Phuntsholing",
    "6251" => "Phuntsholing",
    "6250" => "Phuntsholing",
    "4785" => "Tsenkharla",
    "4781" => "Trashi Yangtse",
    "4780" => "Trashi Yangtse",
    "4744" => "Gelpoyshing",
    "4641" => "Mongar",
    "4640" => "Mongar",
    "4581" => "Khaling",
    "4580" => "Khaling",
    "4571" => "Wamrong",
    "4570" => "Wamrong",
    "4561" => "Rangjung",
    "4560" => "Rangjung",
    "4546" => "Tangmachu",
    "4545" => "Lhuntse",
    "4535" => "Kanglung",
    "4521" => "Trashigang",
    "4520" => "Trashigang",
    "3741" => "Zhemgang",
    "3740" => "Zhemgang",
    "3641" => "Chumey",
    "3640" => "Chumey",
    "3635" => "Jakar",
    "3631" => "Jakar",
    "3630" => "Jakar",
    "3522" => "Trongsa",
    "3521" => "Trongsa",
    "3520" => "Trongsa",
    "2688" => "Gasa",
    "2584" => "Punakha",
    "2481" => "Wangdue",
    "2480" => "Wangdue",
    "2471" => "Basochu",
    "2470" => "Basochu",
    "2365" => "Taba",
    "2361" => "Dechencholing",
    "2360" => "Dechencholing",
    "2351" => "Simtokha",
    "2350" => "Simtokha",
    "824" => "Shaba",
    "823" => "Drukgyel Dzong",
    "727" => "Nganglam",
    "621" => "Lodrai",
    "375" => "Tingtibi",
    "237" => "Kharsadrapchu",
    "234" => "Thimphu",
    "233" => "Thimphu",
    "232" => "Thimphu"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 11)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "975", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Bhutan", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+975 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Buthanian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
