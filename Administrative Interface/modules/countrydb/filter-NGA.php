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
 * related to country database of Nigeria.
 *
 * Reference: https://www.itu.int/oth/T020200009C/en (2010-03-19)
 *            https://www.ncc.gov.ng/?searchword=%22national+numbering+plan%22&searchphrase=any&limit=20&ordering=newest&view=search&option=com_search (2022-12-22)
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
 * E.164 Nigeria country hook
 */
framework_add_filter ( "e164_identify_country_NGA", "e164_identify_country_NGA");

/**
 * E.164 Nigerian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "NGA" (code for Nigeria). This
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
function e164_identify_country_NGA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Nigeria
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+234")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Nigeria has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "7046" => "Visafone",
    "7045" => "Visafone",
    "7044" => "Visafone",
    "7043" => "Visafone",
    "7042" => "Visafone",
    "7041" => "Visafone",
    "7040" => "Visafone",
    "7027" => "Visafone",
    "7026" => "Visafone",
    "7023" => "",
    "7022" => "ICN",
    "7021" => "",
    "7020" => "Smile",
    "916" => "MTN",
    "915" => "Globacom",
    "913" => "MTN",
    "912" => "Aritel",
    "911" => "Aritel",
    "909" => "9mobile",
    "908" => "9mobile",
    "907" => "Aritel",
    "906" => "MTN",
    "905" => "Globacom",
    "904" => "Airtel Networks Ltd.",
    "903" => "MTN",
    "902" => "Airtel Networks Ltd.",
    "901" => "Airtel Networks Ltd.",
    "818" => "9mobile",
    "817" => "9mobile",
    "816" => "MTN",
    "815" => "Globacom",
    "814" => "MTN",
    "813" => "MTN",
    "812" => "Airtel Networks Ltd.",
    "811" => "Globacom",
    "810" => "MTN",
    "809" => "9mobile",
    "808" => "Airtel Networks Ltd.",
    "807" => "Globacom",
    "806" => "MTN",
    "805" => "Globacom",
    "804" => "M-Tel",
    "803" => "MTN",
    "802" => "Airtel Networks Ltd.",
    "801" => "Mafab",
    "708" => "Airtel Networks Ltd.",
    "706" => "MTN",
    "705" => "Globacom",
    "703" => "MTN",
    "701" => "Airtel Networks Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "234", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Nigeria", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+234 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 1 or 2 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "642904" => array ( "Area" => "Kano", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "642903" => array ( "Area" => "Kano", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "642902" => array ( "Area" => "Kano", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "642901" => array ( "Area" => "Kano", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "642900" => array ( "Area" => "Kano", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "392704" => array ( "Area" => "Abeokuta", "Operator" => "21st Century Technologies Ltd."),
    "392703" => array ( "Area" => "Abeokuta", "Operator" => "21st Century Technologies Ltd."),
    "392702" => array ( "Area" => "Abeokuta", "Operator" => "21st Century Technologies Ltd."),
    "392701" => array ( "Area" => "Abeokuta", "Operator" => "21st Century Technologies Ltd."),
    "392700" => array ( "Area" => "Abeokuta", "Operator" => "21st Century Technologies Ltd."),
    "229004" => array ( "Area" => "Ibadan", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "229003" => array ( "Area" => "Ibadan", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "229002" => array ( "Area" => "Ibadan", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "229001" => array ( "Area" => "Ibadan", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "229000" => array ( "Area" => "Ibadan", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "96234" => array ( "Area" => "Abuja", "Operator" => "iPNX Nigeria Ltd."),
    "96233" => array ( "Area" => "Abuja", "Operator" => "iPNX Nigeria Ltd."),
    "96232" => array ( "Area" => "Abuja", "Operator" => "iPNX Nigeria Ltd."),
    "96231" => array ( "Area" => "Abuja", "Operator" => "iPNX Nigeria Ltd."),
    "96230" => array ( "Area" => "Abuja", "Operator" => "iPNX Nigeria Ltd."),
    "95154" => array ( "Area" => "Abuja", "Operator" => "Cyberspace Ltd."),
    "95153" => array ( "Area" => "Abuja", "Operator" => "Cyberspace Ltd."),
    "95152" => array ( "Area" => "Abuja", "Operator" => "Cyberspace Ltd."),
    "95151" => array ( "Area" => "Abuja", "Operator" => "Cyberspace Ltd."),
    "95150" => array ( "Area" => "Abuja", "Operator" => "Cyberspace Ltd."),
    "93014" => array ( "Area" => "Abuja", "Operator" => "Nationwaves Telecom Nig. Ltd."),
    "93013" => array ( "Area" => "Abuja", "Operator" => "Nationwaves Telecom Nig. Ltd."),
    "93012" => array ( "Area" => "Abuja", "Operator" => "Nationwaves Telecom Nig. Ltd."),
    "93011" => array ( "Area" => "Abuja", "Operator" => "Nationwaves Telecom Nig. Ltd."),
    "93010" => array ( "Area" => "Abuja", "Operator" => "Nationwaves Telecom Nig. Ltd."),
    "92900" => array ( "Area" => "Abuja", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "85902" => array ( "Area" => "Uyo", "Operator" => "FNL Engineering Ltd."),
    "85901" => array ( "Area" => "Uyo", "Operator" => "FNL Engineering Ltd."),
    "85900" => array ( "Area" => "Uyo", "Operator" => "FNL Engineering Ltd."),
    "84704" => array ( "Area" => "Port Harcourt", "Operator" => "Inq Digital Nig. Ltd. (Vodacom)"),
    "84703" => array ( "Area" => "Port Harcourt", "Operator" => "Inq Digital Nig. Ltd. (Vodacom)"),
    "84702" => array ( "Area" => "Port Harcourt", "Operator" => "Inq Digital Nig. Ltd. (Vodacom)"),
    "84701" => array ( "Area" => "Port Harcourt", "Operator" => "Inq Digital Nig. Ltd. (Vodacom)"),
    "84700" => array ( "Area" => "Port Harcourt", "Operator" => "Inq Digital Nig. Ltd. (Vodacom)"),
    "84364" => array ( "Area" => "Port Harcourt", "Operator" => "iPNX Nigeria Ltd."),
    "84363" => array ( "Area" => "Port Harcourt", "Operator" => "iPNX Nigeria Ltd."),
    "84362" => array ( "Area" => "Port Harcourt", "Operator" => "iPNX Nigeria Ltd."),
    "84361" => array ( "Area" => "Port Harcourt", "Operator" => "iPNX Nigeria Ltd."),
    "84360" => array ( "Area" => "Port Harcourt", "Operator" => "iPNX Nigeria Ltd."),
    "84290" => array ( "Area" => "Port Harcourt", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "84270" => array ( "Area" => "Port Harcourt", "Operator" => "21st Century Technologies Ltd."),
    "64270" => array ( "Area" => "Kano", "Operator" => "21st Century Technologies Ltd."),
    "62270" => array ( "Area" => "Kaduna", "Operator" => "21st Century Technologies Ltd."),
    "53270" => array ( "Area" => "Warri", "Operator" => "21st Century Technologies Ltd."),
    "47630" => array ( "Area" => "Lafia", "Operator" => "TNT Global Technologies Int'l Ltd."),
    "46290" => array ( "Area" => "Onitsha", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "25011" => array ( "Area" => "Ibadan", "Operator" => "Spectranet Ltd."),
    "25010" => array ( "Area" => "Ibadan", "Operator" => "Spectranet Ltd."),
    "17101" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "17100" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "16284" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "16283" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "16282" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "16281" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "16280" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "14904" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "14903" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "14902" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "14901" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "14900" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "13424" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "13423" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "13422" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "13421" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "13420" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "9904" => array ( "Area" => "Abuja", "Operator" => "Globalcom Ltd."),
    "9903" => array ( "Area" => "Abuja", "Operator" => "Globalcom Ltd."),
    "9700" => array ( "Area" => "Abuja", "Operator" => "Inq Digital Nig. Ltd. (Vodacom)"),
    "9674" => array ( "Area" => "Abuja", "Operator" => "Intercellular Nig. Ltd."),
    "9673" => array ( "Area" => "Abuja", "Operator" => "Intercellular Nig. Ltd."),
    "9672" => array ( "Area" => "Abuja", "Operator" => "Intercellular Nig. Ltd."),
    "9671" => array ( "Area" => "Abuja", "Operator" => "Intercellular Nig. Ltd."),
    "9670" => array ( "Area" => "Abuja", "Operator" => "Intercellular Nig. Ltd."),
    "9624" => array ( "Area" => "Abuja", "Operator" => "iPNX Nigeria Ltd."),
    "9604" => array ( "Area" => "Abuja", "Operator" => "NATCOM Dev. & Inv. Ltd. (nTel)"),
    "9501" => array ( "Area" => "Abuja", "Operator" => "Spectranet Ltd."),
    "9463" => array ( "Area" => "Abuja", "Operator" => "MTN Nig Comm. Plc."),
    "9462" => array ( "Area" => "Abuja", "Operator" => "MTN Nig Comm. Plc."),
    "9461" => array ( "Area" => "Abuja", "Operator" => "MTN Nig Comm. Plc."),
    "9460" => array ( "Area" => "Abuja", "Operator" => "MTN Nig Comm. Plc."),
    "9450" => array ( "Area" => "Abuja", "Operator" => "Airworld Technologies Ltd."),
    "9360" => array ( "Area" => "Abuja", "Operator" => "IT Sky Solutions Ltd."),
    "9320" => array ( "Area" => "Abuja", "Operator" => "Bricklinks Afria Plc."),
    "9310" => array ( "Area" => "Abuja", "Operator" => "Imbil Telecom Solution Nigeria Ltd."),
    "9305" => array ( "Area" => "Abuja", "Operator" => "Realife Telecommunications Ltd."),
    "9299" => array ( "Area" => "Abuja", "Operator" => "Skyway Technology & Services Ltd."),
    "9292" => array ( "Area" => "Abuja", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "9291" => array ( "Area" => "Abuja", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "9278" => array ( "Area" => "Abuja", "Operator" => "21st Century Technologies Ltd."),
    "9277" => array ( "Area" => "Abuja", "Operator" => "21st Century Technologies Ltd."),
    "8946" => array ( "Area" => "Yenagoa", "Operator" => "MTN Nig Comm. Plc."),
    "8866" => array ( "Area" => "Umuahia", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8829" => array ( "Area" => "Umuahia", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8777" => array ( "Area" => "Calabar", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8766" => array ( "Area" => "Calabar", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8746" => array ( "Area" => "Calabar", "Operator" => "MTN Nig Comm. Plc."),
    "8729" => array ( "Area" => "Calabar", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8566" => array ( "Area" => "Uyo", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8529" => array ( "Area" => "Uyo", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8494" => array ( "Area" => "Port Harcourt", "Operator" => "Globalcom Ltd."),
    "8493" => array ( "Area" => "Port Harcourt", "Operator" => "Globalcom Ltd."),
    "8466" => array ( "Area" => "Port Harcourt", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8465" => array ( "Area" => "Port Harcourt", "Operator" => "iPNX Nigeria Ltd."),
    "8463" => array ( "Area" => "Port Harcourt", "Operator" => "Brass-Wave Global Ltd."),
    "8457" => array ( "Area" => "Port Harcourt", "Operator" => "Intercellular Nig. Ltd."),
    "8455" => array ( "Area" => "Port Harcourt", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8446" => array ( "Area" => "Port Harcourt", "Operator" => "MTN Nig Comm. Plc."),
    "8445" => array ( "Area" => "Port Harcourt", "Operator" => "MTN Nig Comm. Plc."),
    "8444" => array ( "Area" => "Port Harcourt", "Operator" => "MTN Nig Comm. Plc."),
    "8430" => array ( "Area" => "Port Harcourt", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8428" => array ( "Area" => "Port Harcourt", "Operator" => "21st Century Technologies Ltd."),
    "8420" => array ( "Area" => "Port Harcourt", "Operator" => "NATCOM Dev. & Inv. Ltd. (nTel)"),
    "8366" => array ( "Area" => "Owerri", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8343" => array ( "Area" => "Owerri", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8255" => array ( "Area" => "Aba", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8246" => array ( "Area" => "Aba", "Operator" => "MTN Nig Comm. Plc."),
    "8244" => array ( "Area" => "Aba", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "8229" => array ( "Area" => "Aba", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "7639" => array ( "Area" => "Maiduguri", "Operator" => "Intercellular Nig. Ltd."),
    "7638" => array ( "Area" => "Maiduguri", "Operator" => "Intercellular Nig. Ltd."),
    "7637" => array ( "Area" => "Maiduguri", "Operator" => "Intercellular Nig. Ltd."),
    "7629" => array ( "Area" => "Maiduguri", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "7329" => array ( "Area" => "Jos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "6995" => array ( "Area" => "Zaria", "Operator" => "Globalcom Ltd."),
    "6994" => array ( "Area" => "Zaria", "Operator" => "Globalcom Ltd."),
    "6993" => array ( "Area" => "Zaria", "Operator" => "Globalcom Ltd."),
    "6938" => array ( "Area" => "Zaria", "Operator" => "Intercellular Nig. Ltd."),
    "6937" => array ( "Area" => "Zaria", "Operator" => "Intercellular Nig. Ltd."),
    "6929" => array ( "Area" => "Zaria", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "6694" => array ( "Area" => "Minna", "Operator" => "Globalcom Ltd."),
    "6693" => array ( "Area" => "Minna", "Operator" => "Globalcom Ltd."),
    "6529" => array ( "Area" => "Katsina", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "6486" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6485" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6484" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6483" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6483" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6483" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6483" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6483" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6483" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6483" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6483" => array ( "Area" => "Kano", "Operator" => "Globalcom Ltd."),
    "6474" => array ( "Area" => "Kano", "Operator" => "Ratel Plus Nig. Ltd."),
    "6473" => array ( "Area" => "Kano", "Operator" => "Ratel Plus Nig. Ltd."),
    "6472" => array ( "Area" => "Kano", "Operator" => "Ratel Plus Nig. Ltd."),
    "6471" => array ( "Area" => "Kano", "Operator" => "Ratel Plus Nig. Ltd."),
    "6470" => array ( "Area" => "Kano", "Operator" => "Ratel Plus Nig. Ltd."),
    "6446" => array ( "Area" => "Kano", "Operator" => "MTN Nig Comm. Plc."),
    "6443" => array ( "Area" => "Kano", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "6440" => array ( "Area" => "Kano", "Operator" => "iPNX Nigeria Ltd."),
    "6433" => array ( "Area" => "Kano", "Operator" => "Intercellular Nig. Ltd."),
    "6432" => array ( "Area" => "Kano", "Operator" => "Intercellular Nig. Ltd."),
    "6431" => array ( "Area" => "Kano", "Operator" => "Intercellular Nig. Ltd."),
    "6296" => array ( "Area" => "Kaduna", "Operator" => "Globalcom Ltd."),
    "6295" => array ( "Area" => "Kaduna", "Operator" => "Globalcom Ltd."),
    "6294" => array ( "Area" => "Kaduna", "Operator" => "Globalcom Ltd."),
    "6293" => array ( "Area" => "Kaduna", "Operator" => "Globalcom Ltd."),
    "6246" => array ( "Area" => "Kaduna", "Operator" => "MTN Nig Comm. Plc."),
    "6239" => array ( "Area" => "Kaduna", "Operator" => "Intercellular Nig. Ltd."),
    "6238" => array ( "Area" => "Kaduna", "Operator" => "Intercellular Nig. Ltd."),
    "6237" => array ( "Area" => "Kaduna", "Operator" => "Intercellular Nig. Ltd."),
    "6229" => array ( "Area" => "Kaduna", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "5694" => array ( "Area" => "Asaba", "Operator" => "Globalcom Ltd."),
    "5693" => array ( "Area" => "Asaba", "Operator" => "Globalcom Ltd."),
    "5629" => array ( "Area" => "Asaba", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "5529" => array ( "Area" => "Agbor", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "5394" => array ( "Area" => "Warri", "Operator" => "Globalcom Ltd."),
    "5393" => array ( "Area" => "Warri", "Operator" => "Globalcom Ltd."),
    "5346" => array ( "Area" => "Warri", "Operator" => "MTN Nig Comm. Plc."),
    "5329" => array ( "Area" => "Warri", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "5296" => array ( "Area" => "Benin", "Operator" => "Globalcom Ltd."),
    "5295" => array ( "Area" => "Benin", "Operator" => "Globalcom Ltd."),
    "5294" => array ( "Area" => "Benin", "Operator" => "Globalcom Ltd."),
    "5293" => array ( "Area" => "Benin", "Operator" => "Globalcom Ltd."),
    "5229" => array ( "Area" => "Benin", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "4888" => array ( "Area" => "Awka", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "4829" => array ( "Area" => "Awka", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "4666" => array ( "Area" => "Onitsha", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "4628" => array ( "Area" => "Onitsha", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "4329" => array ( "Area" => "Abakaliki", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "4266" => array ( "Area" => "Enugu", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "4229" => array ( "Area" => "Enugu", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "3994" => array ( "Area" => "Abeokuta", "Operator" => "Globalcom Ltd."),
    "3993" => array ( "Area" => "Abeokuta", "Operator" => "Globalcom Ltd."),
    "3929" => array ( "Area" => "Abeokuta", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "3795" => array ( "Area" => "Ijebu-Ode", "Operator" => "Globalcom Ltd."),
    "3794" => array ( "Area" => "Ijebu-Ode", "Operator" => "Globalcom Ltd."),
    "3793" => array ( "Area" => "Ijebu-Ode", "Operator" => "Globalcom Ltd."),
    "3146" => array ( "Area" => "Llorin", "Operator" => "MTN Nig Comm. Plc."),
    "3129" => array ( "Area" => "Llorin", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "2905" => array ( "Area" => "Ibadan", "Operator" => "Globalcom Ltd."),
    "2904" => array ( "Area" => "Ibadan", "Operator" => "Globalcom Ltd."),
    "2903" => array ( "Area" => "Ibadan", "Operator" => "Globalcom Ltd."),
    "2628" => array ( "Area" => "Ibadan", "Operator" => "iPNX Nigeria Ltd."),
    "2461" => array ( "Area" => "Ibadan", "Operator" => "MTN Nig Comm. Plc."),
    "2291" => array ( "Area" => "Ibadan", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "1915" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1914" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1913" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1912" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1911" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1910" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1909" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1908" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1907" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1906" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1905" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1904" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1903" => array ( "Area" => "Lagos", "Operator" => "Globalcom Ltd."),
    "1889" => array ( "Area" => "Lagos", "Operator" => "Vezeti Services Ltd."),
    "1888" => array ( "Area" => "Lagos", "Operator" => "Vezeti Services Ltd."),
    "1887" => array ( "Area" => "Lagos", "Operator" => "Vezeti Services Ltd."),
    "1715" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1714" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1713" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1712" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1711" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1700" => array ( "Area" => "Lagos", "Operator" => "Inq Digital Nig. Ltd. (Vodacom)"),
    "1638" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1637" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1636" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1635" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1634" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1633" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1632" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1631" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1630" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1629" => array ( "Area" => "Lagos", "Operator" => "iPNX Nigeria Ltd."),
    "1516" => array ( "Area" => "Lagos", "Operator" => "Cyberspace Ltd."),
    "1515" => array ( "Area" => "Lagos", "Operator" => "Cyberspace Ltd."),
    "1503" => array ( "Area" => "Lagos", "Operator" => "Spectranet Ltd."),
    "1502" => array ( "Area" => "Lagos", "Operator" => "Spectranet Ltd."),
    "1501" => array ( "Area" => "Lagos", "Operator" => "Spectranet Ltd."),
    "1454" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "1453" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "1448" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1424" => array ( "Area" => "Lagos", "Operator" => "NATCOM Dev. & Inv. Ltd. (nTel)"),
    "1423" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1422" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1348" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1347" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1346" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1345" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1344" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1343" => array ( "Area" => "Lagos", "Operator" => "Swift Networks Ltd."),
    "1330" => array ( "Area" => "Lagos", "Operator" => "RouteCall Communications Ltd."),
    "1310" => array ( "Area" => "Lagos", "Operator" => "Imbil Telecom Solution Nigeria Ltd."),
    "1295" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "1293" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "1292" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "1291" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "1290" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Ltd. (Visafone)"),
    "1280" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1279" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1278" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1277" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1271" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1270" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1255" => array ( "Area" => "Lagos", "Operator" => "MYD Technologies Ltd."),
    "1252" => array ( "Area" => "Lagos", "Operator" => "Trucall Solutions Ltd."),
    "1249" => array ( "Area" => "Lagos", "Operator" => "101 Communications Ltd."),
    "1236" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1235" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1229" => array ( "Area" => "Lagos", "Operator" => "Tizeti Network Ltd."),
    "1228" => array ( "Area" => "Lagos", "Operator" => "Broadbased Communications Ltd."),
    "1227" => array ( "Area" => "Lagos", "Operator" => "Broadbased Communications Ltd."),
    "1226" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1225" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "1224" => array ( "Area" => "Lagos", "Operator" => "21st Century Technologies Ltd."),
    "152" => array ( "Area" => "Lagos", "Operator" => "Big Picture Nigeria Ltd."),
    "147" => array ( "Area" => "Lagos", "Operator" => "Intercellular Nig. Ltd."),
    "146" => array ( "Area" => "Lagos", "Operator" => "MTN Nig Comm. Plc.")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      if ( substr ( $parameters["Number"], 4, 1) == "1" || substr ( $parameters["Number"], 4, 1) == "2" || substr ( $parameters["Number"], 4, 1) == "9")
      {
        $ndc = substr ( $parameters["Number"], 4, 1);
        $sn = substr ( $parameters["Number"], 5);
        $local = "0" . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8);
        $international = "+234 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8);
      } else {
        $ndc = substr ( $parameters["Number"], 4, 2);
        $sn = substr ( $parameters["Number"], 6);
        $local = "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9);
        $international = "+234 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9);
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "234", "NDC" => $ndc, "Country" => "Nigeria", "Area" => $data["Area"], "City" => "", "Operator" => $data["Operator"], "SN" => $sn, "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => $local, "International" => $international)));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Nigerian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
