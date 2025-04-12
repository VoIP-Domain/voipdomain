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
 * related to country database of Macao.
 *
 * Reference: https://www.itu.int/oth/T020200007E/en (2007-07-20)
 * Reference: http://telecommunications.ctt.gov.mo/web/tc/
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
 * E.164 Macao country hook
 */
framework_add_filter ( "e164_identify_country_MAC", "e164_identify_country_MAC");

/**
 * E.164 Macao area number identification hook. This hook is an e164_identify sub
 * hook, called when the ISO3166 Alpha3 are "MAC" (code for Macao). This hook
 * will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_MAC ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Macao
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+853")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Macao has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with no NDC and 8 digits SN
   */
  $prefixes = array (
    "66050004" => "China Telecom (Macau) Limitada",
    "66050003" => "China Telecom (Macau) Limitada",
    "66050002" => "China Telecom (Macau) Limitada",
    "66050001" => "China Telecom (Macau) Limitada",
    "66050000" => "China Telecom (Macau) Limitada",
    "66046" => "Smartone - Comunicações Móveis, S.A.",
    "66001" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "65479" => "Smartone - Comunicações Móveis, S.A.",
    "65478" => "Smartone - Comunicações Móveis, S.A.",
    "65477" => "Smartone - Comunicações Móveis, S.A.",
    "65476" => "Smartone - Comunicações Móveis, S.A.",
    "65475" => "Smartone - Comunicações Móveis, S.A.",
    "65474" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "65473" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "65472" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "65471" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "65470" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "65429" => "China Telecom (Macau) Limitada",
    "65428" => "China Telecom (Macau) Limitada",
    "65427" => "China Telecom (Macau) Limitada",
    "65426" => "China Telecom (Macau) Limitada",
    "65425" => "China Telecom (Macau) Limitada",
    "65424" => "Hutchison-Telefone (Macau), Limitada",
    "65423" => "Hutchison-Telefone (Macau), Limitada",
    "65422" => "Hutchison-Telefone (Macau), Limitada",
    "65421" => "Hutchison-Telefone (Macau), Limitada",
    "65420" => "Hutchison-Telefone (Macau), Limitada",
    "6889" => "China Telecom (Macau) Limitada",
    "6888" => "China Telecom (Macau) Limitada",
    "6887" => "China Telecom (Macau) Limitada",
    "6886" => "China Telecom (Macau) Limitada",
    "6885" => "China Telecom (Macau) Limitada",
    "6884" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6883" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6882" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6881" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6880" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6879" => "Hutchison-Telefone (Macau), Limitada",
    "6878" => "Hutchison-Telefone (Macau), Limitada",
    "6877" => "Hutchison-Telefone (Macau), Limitada",
    "6876" => "Hutchison-Telefone (Macau), Limitada",
    "6875" => "Hutchison-Telefone (Macau), Limitada",
    "6874" => "Smartone - Comunicações Móveis, S.A.",
    "6873" => "Smartone - Comunicações Móveis, S.A.",
    "6872" => "Smartone - Comunicações Móveis, S.A.",
    "6871" => "Smartone - Comunicações Móveis, S.A.",
    "6870" => "Smartone - Comunicações Móveis, S.A.",
    "6854" => "Hutchison-Telefone (Macau), Limitada",
    "6853" => "Hutchison-Telefone (Macau), Limitada",
    "6852" => "Hutchison-Telefone (Macau), Limitada",
    "6851" => "Hutchison-Telefone (Macau), Limitada",
    "6850" => "Hutchison-Telefone (Macau), Limitada",
    "6849" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6848" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6847" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6846" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6845" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6844" => "Hutchison-Telefone (Macau), Limitada",
    "6843" => "Hutchison-Telefone (Macau), Limitada",
    "6842" => "Hutchison-Telefone (Macau), Limitada",
    "6841" => "Hutchison-Telefone (Macau), Limitada",
    "6840" => "Hutchison-Telefone (Macau), Limitada",
    "6834" => "Smartone - Comunicações Móveis, S.A.",
    "6833" => "Smartone - Comunicações Móveis, S.A.",
    "6832" => "Smartone - Comunicações Móveis, S.A.",
    "6831" => "Smartone - Comunicações Móveis, S.A.",
    "6830" => "Smartone - Comunicações Móveis, S.A.",
    "6829" => "China Telecom (Macau) Limitada",
    "6828" => "China Telecom (Macau) Limitada",
    "6827" => "China Telecom (Macau) Limitada",
    "6826" => "China Telecom (Macau) Limitada",
    "6825" => "China Telecom (Macau) Limitada",
    "6819" => "Smartone - Comunicações Móveis, S.A.",
    "6818" => "Smartone - Comunicações Móveis, S.A.",
    "6817" => "Smartone - Comunicações Móveis, S.A.",
    "6816" => "Smartone - Comunicações Móveis, S.A.",
    "6815" => "Smartone - Comunicações Móveis, S.A.",
    "6814" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6813" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6812" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6811" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6810" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6809" => "Hutchison-Telefone (Macau), Limitada",
    "6808" => "Hutchison-Telefone (Macau), Limitada",
    "6807" => "Hutchison-Telefone (Macau), Limitada",
    "6806" => "Hutchison-Telefone (Macau), Limitada",
    "6805" => "Hutchison-Telefone (Macau), Limitada",
    "6699" => "China Telecom (Macau) Limitada",
    "6698" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6697" => "Hutchison-Telefone (Macau), Limitada",
    "6696" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6695" => "Hutchison-Telefone (Macau), Limitada",
    "6694" => "Hutchison-Telefone (Macau), Limitada",
    "6693" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6692" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6691" => "Sociedade de Prestação de Serviços Kong Seng Paging Limitada",
    "6690" => "Sociedade de Prestação de Serviços Kong Seng Paging Limitada",
    "6679" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6678" => "Smartone - Comunicações Móveis, S.A.",
    "6677" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6676" => "Hutchison-Telefone (Macau), Limitada",
    "6675" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6674" => "Hutchison-Telefone (Macau), Limitada",
    "6673" => "Smartone - Comunicações Móveis, S.A.",
    "6672" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6671" => "China Telecom (Macau) Limitada",
    "6670" => "China Telecom (Macau) Limitada",
    "6659" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6658" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6657" => "Hutchison-Telefone (Macau), Limitada",
    "6656" => "Hutchison-Telefone (Macau), Limitada",
    "6655" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6654" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6653" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6652" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6651" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6650" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6649" => "China Telecom (Macau) Limitada",
    "6648" => "Hutchison-Telefone (Macau), Limitada",
    "6647" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6646" => "Hutchison-Telefone (Macau), Limitada",
    "6645" => "Hutchison-Telefone (Macau), Limitada",
    "6644" => "Hutchison-Telefone (Macau), Limitada",
    "6643" => "Hutchison-Telefone (Macau), Limitada",
    "6642" => "Hutchison-Telefone (Macau), Limitada",
    "6641" => "Smartone - Comunicações Móveis, S.A.",
    "6640" => "Smartone - Comunicações Móveis, S.A.",
    "6619" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6618" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6617" => "Hutchison-Telefone (Macau), Limitada",
    "6616" => "Hutchison-Telefone (Macau), Limitada",
    "6615" => "Smartone - Comunicações Móveis, S.A.",
    "6614" => "Smartone - Comunicações Móveis, S.A.",
    "6613" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6612" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6611" => "Hutchison-Telefone (Macau), Limitada",
    "6610" => "Hutchison-Telefone (Macau), Limitada",
    "6603" => "Hutchison-Telefone (Macau), Limitada",
    "6602" => "Smartone - Comunicações Móveis, S.A.",
    "6601" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6599" => "China Telecom (Macau) Limitada",
    "6598" => "China Telecom (Macau) Limitada",
    "6597" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6596" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6595" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6594" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6593" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6592" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6591" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6590" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6589" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6588" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6587" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6586" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6585" => "China Telecom (Macau) Limitada",
    "6584" => "China Telecom (Macau) Limitada",
    "6583" => "China Telecom (Macau) Limitada",
    "6582" => "China Telecom (Macau) Limitada",
    "6581" => "China Telecom (Macau) Limitada",
    "6580" => "China Telecom (Macau) Limitada",
    "6579" => "Hutchison-Telefone (Macau), Limitada",
    "6578" => "Hutchison-Telefone (Macau), Limitada",
    "6577" => "Hutchison-Telefone (Macau), Limitada",
    "6576" => "Hutchison-Telefone (Macau), Limitada",
    "6575" => "Hutchison-Telefone (Macau), Limitada",
    "6574" => "Hutchison-Telefone (Macau), Limitada",
    "6573" => "China Telecom (Macau) Limitada",
    "6572" => "China Telecom (Macau) Limitada",
    "6571" => "China Telecom (Macau) Limitada",
    "6570" => "China Telecom (Macau) Limitada",
    "6559" => "China Telecom (Macau) Limitada",
    "6558" => "China Telecom (Macau) Limitada",
    "6557" => "China Telecom (Macau) Limitada",
    "6556" => "China Telecom (Macau) Limitada",
    "6555" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6554" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6553" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6552" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6551" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6550" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6549" => "Smartone - Comunicações Móveis, S.A.",
    "6548" => "Smartone - Comunicações Móveis, S.A.",
    "6546" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6545" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6544" => "China Telecom (Macau) Limitada",
    "6543" => "China Telecom (Macau) Limitada",
    "6541" => "Hutchison-Telefone (Macau), Limitada",
    "6540" => "Hutchison-Telefone (Macau), Limitada",
    "6539" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6538" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6537" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6536" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6535" => "Hutchison-Telefone (Macau), Limitada",
    "6534" => "Hutchison-Telefone (Macau), Limitada",
    "6533" => "Hutchison-Telefone (Macau), Limitada",
    "6532" => "Hutchison-Telefone (Macau), Limitada",
    "6531" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6530" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6529" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6528" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6527" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6526" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6525" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6524" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6523" => "China Telecom (Macau) Limitada",
    "6522" => "China Telecom (Macau) Limitada",
    "6521" => "China Telecom (Macau) Limitada",
    "6520" => "China Telecom (Macau) Limitada",
    "6519" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6518" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6517" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6516" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6515" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6504" => "Hutchison-Telefone (Macau), Limitada",
    "6503" => "Hutchison-Telefone (Macau), Limitada",
    "6502" => "Hutchison-Telefone (Macau), Limitada",
    "6501" => "Hutchison-Telefone (Macau), Limitada",
    "6500" => "Hutchison-Telefone (Macau), Limitada",
    "6399" => "Hutchison-Telefone (Macau), Limitada",
    "6398" => "Hutchison-Telefone (Macau), Limitada",
    "6397" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6396" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6395" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6394" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6393" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6392" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6391" => "China Telecom (Macau) Limitada",
    "6390" => "China Telecom (Macau) Limitada",
    "6389" => "China Telecom (Macau) Limitada",
    "6388" => "China Telecom (Macau) Limitada",
    "6387" => "China Telecom (Macau) Limitada",
    "6386" => "China Telecom (Macau) Limitada",
    "6385" => "Hutchison-Telefone (Macau), Limitada",
    "6384" => "Hutchison-Telefone (Macau), Limitada",
    "6383" => "Hutchison-Telefone (Macau), Limitada",
    "6382" => "Hutchison-Telefone (Macau), Limitada",
    "6381" => "Hutchison-Telefone (Macau), Limitada",
    "6380" => "Hutchison-Telefone (Macau), Limitada",
    "6379" => "Hutchison-Telefone (Macau), Limitada",
    "6378" => "Hutchison-Telefone (Macau), Limitada",
    "6377" => "China Telecom (Macau) Limitada",
    "6376" => "China Telecom (Macau) Limitada",
    "6375" => "China Telecom (Macau) Limitada",
    "6374" => "China Telecom (Macau) Limitada",
    "6373" => "China Telecom (Macau) Limitada",
    "6372" => "China Telecom (Macau) Limitada",
    "6371" => "China Telecom (Macau) Limitada",
    "6370" => "China Telecom (Macau) Limitada",
    "6349" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6348" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6347" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6346" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6345" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6344" => "Hutchison-Telefone (Macau), Limitada",
    "6343" => "China Telecom (Macau) Limitada",
    "6342" => "China Telecom (Macau) Limitada",
    "6341" => "China Telecom (Macau) Limitada",
    "6340" => "China Telecom (Macau) Limitada",
    "6339" => "Hutchison-Telefone (Macau), Limitada",
    "6338" => "Hutchison-Telefone (Macau), Limitada",
    "6337" => "Hutchison-Telefone (Macau), Limitada",
    "6336" => "Hutchison-Telefone (Macau), Limitada",
    "6335" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6334" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6333" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6332" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6331" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6330" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6329" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6328" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6327" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6326" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6325" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6324" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6323" => "China Telecom (Macau) Limitada",
    "6322" => "China Telecom (Macau) Limitada",
    "6321" => "Hutchison-Telefone (Macau), Limitada",
    "6320" => "Hutchison-Telefone (Macau), Limitada",
    "6309" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6308" => "Hutchison-Telefone (Macau), Limitada",
    "6307" => "Hutchison-Telefone (Macau), Limitada",
    "6306" => "Hutchison-Telefone (Macau), Limitada",
    "6305" => "Hutchison-Telefone (Macau), Limitada",
    "6304" => "Hutchison-Telefone (Macau), Limitada",
    "6303" => "Hutchison-Telefone (Macau), Limitada",
    "6302" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6301" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6300" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6296" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6296" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6296" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6296" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6292" => "Hutchison-Telefone (Macau), Limitada",
    "6292" => "Hutchison-Telefone (Macau), Limitada",
    "6292" => "Hutchison-Telefone (Macau), Limitada",
    "6292" => "Hutchison-Telefone (Macau), Limitada",
    "6291" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6290" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6279" => "Hutchison-Telefone (Macau), Limitada",
    "6278" => "Hutchison-Telefone (Macau), Limitada",
    "6277" => "Hutchison-Telefone (Macau), Limitada",
    "6276" => "Hutchison-Telefone (Macau), Limitada",
    "6275" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6274" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6273" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6272" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6271" => "Smartone - Comunicações Móveis, S.A.",
    "6270" => "Smartone - Comunicações Móveis, S.A.",
    "6269" => "Smartone - Comunicações Móveis, S.A.",
    "6268" => "Smartone - Comunicações Móveis, S.A.",
    "6267" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6266" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6265" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6264" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6263" => "Smartone - Comunicações Móveis, S.A.",
    "6262" => "Smartone - Comunicações Móveis, S.A.",
    "6261" => "Smartone - Comunicações Móveis, S.A.",
    "6260" => "Smartone - Comunicações Móveis, S.A.",
    "6259" => "Smartone - Comunicações Móveis, S.A.",
    "6258" => "Smartone - Comunicações Móveis, S.A.",
    "6257" => "Smartone - Comunicações Móveis, S.A.",
    "6256" => "Smartone - Comunicações Móveis, S.A.",
    "6255" => "Smartone - Comunicações Móveis, S.A.",
    "6254" => "Smartone - Comunicações Móveis, S.A.",
    "6253" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6252" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6251" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6250" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6249" => "Hutchison-Telefone (Macau), Limitada",
    "6248" => "Hutchison-Telefone (Macau), Limitada",
    "6247" => "Hutchison-Telefone (Macau), Limitada",
    "6246" => "Hutchison-Telefone (Macau), Limitada",
    "6245" => "Smartone - Comunicações Móveis, S.A.",
    "6244" => "Smartone - Comunicações Móveis, S.A.",
    "6243" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6242" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6241" => "Hutchison-Telefone (Macau), Limitada",
    "6240" => "Hutchison-Telefone (Macau), Limitada",
    "6209" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6208" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6207" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6206" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6205" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6204" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6203" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6202" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6201" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "6200" => "Smartone - Comunicações Móveis, S.A.",
    "686" => "China Telecom (Macau) Limitada",
    "668" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "666" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "663" => "Hutchison-Telefone (Macau), Limitada",
    "662" => "Smartone - Comunicações Móveis, S.A.",
    "656" => "China Telecom (Macau) Limitada",
    "636" => "Smartone - Comunicações Móveis, S.A.",
    "635" => "China Telecom (Macau) Limitada",
    "631" => "Hutchison-Telefone (Macau), Limitada",
    "628" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "623" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "622" => "Hutchison-Telefone (Macau), Limitada",
    "621" => "China Telecom (Macau) Limitada"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "853", "NDC" => "", "Country" => "Macao", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+853 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with no NDC and 8 digits SN
   */
  $prefixes = array (
    "8823" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "8822" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "898" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "889" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "888" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "886" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "880" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "879" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "859" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "850" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "849" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "839" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "829" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "811" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "289" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "288" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "287" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "285" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "284" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "283" => "Companhia de Telecomunicações de Macau, S.A.R.L.",
    "282" => "Companhia de Telecomunicações de Macau, S.A.R.L."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "853", "NDC" => "", "Country" => "Macao", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 4), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+853 " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Macao phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
