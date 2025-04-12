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
 * related to country database of Sweden.
 *
 * Reference: https://www.itu.int/oth/T02020000C7/en (2018-05-22)
 *            https://www.pts.se/contentassets/156d4de72d0e403cb5be0bcc29e0c4f3/the-swedish-numbering-plan-for-telephony-accordning-to-itu---2022-01-04.pdf (2022-01-04)
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
 * E.164 Sweden country hook
 */
framework_add_filter ( "e164_identify_country_SWE", "e164_identify_country_SWE");

/**
 * E.164 Sweden area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "SWE" (code for Sweden). This
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
function e164_identify_country_SWE ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Sweden
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+46")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Sweden has 10 to 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) < 10 || strlen ( $parameters["Number"]) > 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network
   */
  $prefixes = array (
    "79",
    "76",
    "73",
    "72",
    "70"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "46", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Sweden", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 5) . " " . substr ( $parameters["Number"], 8), "International" => "+46 " . substr ( $parameters["Number"], 3, 5) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 1, 2 or 3 digits NDC and 6, 7 or 8 digits SN
   */
  $prefixes = array (
    "981" => array ( "Max" => 9, "Min" => 8, "Area" => "Vittangi"),
    "980" => array ( "Max" => 9, "Min" => 8, "Area" => "Kiruna"),
    "978" => array ( "Max" => 9, "Min" => 8, "Area" => "Pajala"),
    "977" => array ( "Max" => 9, "Min" => 8, "Area" => "Korpilombolo"),
    "976" => array ( "Max" => 9, "Min" => 8, "Area" => "Vuollerim"),
    "975" => array ( "Max" => 9, "Min" => 8, "Area" => "Hakkas"),
    "973" => array ( "Max" => 9, "Min" => 8, "Area" => "Porjus"),
    "971" => array ( "Max" => 9, "Min" => 8, "Area" => "Jokkmokk"),
    "970" => array ( "Max" => 9, "Min" => 8, "Area" => "Gällivare"),
    "961" => array ( "Max" => 9, "Min" => 8, "Area" => "Arjeplog"),
    "960" => array ( "Max" => 9, "Min" => 8, "Area" => "Arvidsjaur"),
    "954" => array ( "Max" => 9, "Min" => 8, "Area" => "Tärnaby"),
    "953" => array ( "Max" => 9, "Min" => 8, "Area" => "Malå"),
    "952" => array ( "Max" => 9, "Min" => 8, "Area" => "Sorsele"),
    "951" => array ( "Max" => 9, "Min" => 8, "Area" => "Storuman"),
    "950" => array ( "Max" => 9, "Min" => 8, "Area" => "Lycksele"),
    "943" => array ( "Max" => 9, "Min" => 8, "Area" => "Fredrika"),
    "942" => array ( "Max" => 9, "Min" => 8, "Area" => "Dorotea"),
    "941" => array ( "Max" => 9, "Min" => 8, "Area" => "Åsele"),
    "940" => array ( "Max" => 9, "Min" => 8, "Area" => "Vilhelmina"),
    "935" => array ( "Max" => 9, "Min" => 8, "Area" => "Vännäs"),
    "934" => array ( "Max" => 9, "Min" => 8, "Area" => "Robertsfors"),
    "933" => array ( "Max" => 9, "Min" => 8, "Area" => "Vindeln"),
    "932" => array ( "Max" => 9, "Min" => 8, "Area" => "Bjurholm"),
    "930" => array ( "Max" => 9, "Min" => 8, "Area" => "Nordmaling"),
    "929" => array ( "Max" => 9, "Min" => 8, "Area" => "Älvsbyn"),
    "928" => array ( "Max" => 9, "Min" => 8, "Area" => "Harads"),
    "927" => array ( "Max" => 9, "Min" => 8, "Area" => "Övertorneå"),
    "926" => array ( "Max" => 9, "Min" => 8, "Area" => "Överkalix"),
    "925" => array ( "Max" => 9, "Min" => 8, "Area" => "Lakaträsk"),
    "924" => array ( "Max" => 9, "Min" => 8, "Area" => "Råneå"),
    "923" => array ( "Max" => 9, "Min" => 8, "Area" => "Kalix"),
    "922" => array ( "Max" => 9, "Min" => 8, "Area" => "Haparanda"),
    "921" => array ( "Max" => 9, "Min" => 8, "Area" => "Boden"),
    "920" => array ( "Max" => 9, "Min" => 8, "Area" => "Luleå"),
    "918" => array ( "Max" => 9, "Min" => 8, "Area" => "Norsjö"),
    "916" => array ( "Max" => 9, "Min" => 8, "Area" => "Jörn"),
    "915" => array ( "Max" => 9, "Min" => 8, "Area" => "Bastuträsk"),
    "914" => array ( "Max" => 9, "Min" => 8, "Area" => "Burträsk"),
    "913" => array ( "Max" => 9, "Min" => 8, "Area" => "Lövånger"),
    "912" => array ( "Max" => 9, "Min" => 8, "Area" => "Byske"),
    "911" => array ( "Max" => 9, "Min" => 8, "Area" => "Piteå"),
    "910" => array ( "Max" => 9, "Min" => 8, "Area" => "Skellefteå"),
    "696" => array ( "Max" => 9, "Min" => 8, "Area" => "Hammarstrand"),
    "695" => array ( "Max" => 9, "Min" => 8, "Area" => "Stugun"),
    "693" => array ( "Max" => 9, "Min" => 8, "Area" => "Bräcke-Gällö"),
    "692" => array ( "Max" => 9, "Min" => 8, "Area" => "Liden"),
    "691" => array ( "Max" => 9, "Min" => 8, "Area" => "Torpshammar"),
    "690" => array ( "Max" => 9, "Min" => 8, "Area" => "Ånge"),
    "687" => array ( "Max" => 9, "Min" => 8, "Area" => "Svenstavik"),
    "684" => array ( "Max" => 9, "Min" => 8, "Area" => "Hede-Funäsdalen"),
    "682" => array ( "Max" => 9, "Min" => 8, "Area" => "Rätan"),
    "680" => array ( "Max" => 9, "Min" => 8, "Area" => "Sveg"),
    "672" => array ( "Max" => 9, "Min" => 8, "Area" => "Gäddede"),
    "671" => array ( "Max" => 9, "Min" => 8, "Area" => "Hoting"),
    "670" => array ( "Max" => 9, "Min" => 8, "Area" => "Strömsund"),
    "663" => array ( "Max" => 9, "Min" => 8, "Area" => "Husum"),
    "662" => array ( "Max" => 9, "Min" => 8, "Area" => "Björna"),
    "661" => array ( "Max" => 9, "Min" => 8, "Area" => "Bredbyn"),
    "660" => array ( "Max" => 9, "Min" => 8, "Area" => "Örnsköldsvik"),
    "657" => array ( "Max" => 9, "Min" => 8, "Area" => "Los"),
    "653" => array ( "Max" => 9, "Min" => 8, "Area" => "Delsbo"),
    "652" => array ( "Max" => 9, "Min" => 8, "Area" => "Bergsjö"),
    "651" => array ( "Max" => 9, "Min" => 8, "Area" => "Ljusdal"),
    "650" => array ( "Max" => 9, "Min" => 8, "Area" => "Hudiksvall"),
    "647" => array ( "Max" => 9, "Min" => 8, "Area" => "Åre-Järpen"),
    "645" => array ( "Max" => 9, "Min" => 8, "Area" => "Föllinge"),
    "644" => array ( "Max" => 9, "Min" => 8, "Area" => "Hammerdal"),
    "643" => array ( "Max" => 9, "Min" => 8, "Area" => "Hallen-Oviken"),
    "642" => array ( "Max" => 9, "Min" => 8, "Area" => "Lit"),
    "640" => array ( "Max" => 9, "Min" => 8, "Area" => "Krokom"),
    "624" => array ( "Max" => 9, "Min" => 8, "Area" => "Backe"),
    "623" => array ( "Max" => 9, "Min" => 8, "Area" => "Ramsele"),
    "622" => array ( "Max" => 9, "Min" => 8, "Area" => "Näsåker"),
    "621" => array ( "Max" => 9, "Min" => 8, "Area" => "Junsele"),
    "620" => array ( "Max" => 9, "Min" => 8, "Area" => "Sollefteå"),
    "613" => array ( "Max" => 9, "Min" => 8, "Area" => "Ullånger"),
    "612" => array ( "Max" => 9, "Min" => 8, "Area" => "Kramfors"),
    "611" => array ( "Max" => 9, "Min" => 8, "Area" => "Härnösand"),
    "591" => array ( "Max" => 9, "Min" => 8, "Area" => "Hällefors-Grythyttan"),
    "590" => array ( "Max" => 9, "Min" => 8, "Area" => "Filipstad"),
    "589" => array ( "Max" => 9, "Min" => 8, "Area" => "Arboga"),
    "587" => array ( "Max" => 9, "Min" => 8, "Area" => "Nora"),
    "586" => array ( "Max" => 9, "Min" => 8, "Area" => "Karlskoga-Degerfors"),
    "585" => array ( "Max" => 9, "Min" => 8, "Area" => "Fjugesta-Svartå"),
    "584" => array ( "Max" => 9, "Min" => 8, "Area" => "Laxå"),
    "583" => array ( "Max" => 9, "Min" => 8, "Area" => "Askersund"),
    "582" => array ( "Max" => 9, "Min" => 8, "Area" => "Hallsberg"),
    "581" => array ( "Max" => 9, "Min" => 8, "Area" => "Lindesberg"),
    "580" => array ( "Max" => 9, "Min" => 8, "Area" => "Kopparberg"),
    "573" => array ( "Max" => 9, "Min" => 8, "Area" => "Årjäng"),
    "571" => array ( "Max" => 9, "Min" => 8, "Area" => "Charlottenberg-Åmotfors"),
    "570" => array ( "Max" => 9, "Min" => 8, "Area" => "Arvika"),
    "565" => array ( "Max" => 9, "Min" => 8, "Area" => "Sunne"),
    "564" => array ( "Max" => 9, "Min" => 8, "Area" => "Sysslebäck"),
    "563" => array ( "Max" => 9, "Min" => 8, "Area" => "Hagfors-Munkfors"),
    "560" => array ( "Max" => 9, "Min" => 8, "Area" => "Torsby"),
    "555" => array ( "Max" => 9, "Min" => 8, "Area" => "Grums"),
    "554" => array ( "Max" => 9, "Min" => 8, "Area" => "Kil"),
    "553" => array ( "Max" => 9, "Min" => 8, "Area" => "Molkom"),
    "552" => array ( "Max" => 9, "Min" => 8, "Area" => "Deje"),
    "551" => array ( "Max" => 9, "Min" => 8, "Area" => "Gullspång"),
    "550" => array ( "Max" => 9, "Min" => 8, "Area" => "Kristinehamn"),
    "534" => array ( "Max" => 9, "Min" => 8, "Area" => "Ed"),
    "533" => array ( "Max" => 9, "Min" => 8, "Area" => "Säffle"),
    "532" => array ( "Max" => 9, "Min" => 8, "Area" => "Åmål"),
    "531" => array ( "Max" => 9, "Min" => 8, "Area" => "Bengtsfors"),
    "530" => array ( "Max" => 9, "Min" => 8, "Area" => "Mellerud"),
    "528" => array ( "Max" => 9, "Min" => 8, "Area" => "Färgelanda"),
    "526" => array ( "Max" => 9, "Min" => 8, "Area" => "Strömstad"),
    "525" => array ( "Max" => 9, "Min" => 8, "Area" => "Grebbestad"),
    "524" => array ( "Max" => 9, "Min" => 8, "Area" => "Munkedal"),
    "523" => array ( "Max" => 9, "Min" => 8, "Area" => "Lysekil"),
    "522" => array ( "Max" => 9, "Min" => 8, "Area" => "Uddevalla"),
    "521" => array ( "Max" => 9, "Min" => 8, "Area" => "Vänersborg"),
    "520" => array ( "Max" => 9, "Min" => 8, "Area" => "Trollhättan"),
    "515" => array ( "Max" => 9, "Min" => 8, "Area" => "Falköping"),
    "514" => array ( "Max" => 9, "Min" => 8, "Area" => "Grästorp"),
    "513" => array ( "Max" => 9, "Min" => 8, "Area" => "Herrljunga"),
    "512" => array ( "Max" => 9, "Min" => 8, "Area" => "Vara-Nossebro"),
    "511" => array ( "Max" => 9, "Min" => 8, "Area" => "Skara-Götene"),
    "510" => array ( "Max" => 9, "Min" => 8, "Area" => "Lidköping"),
    "506" => array ( "Max" => 9, "Min" => 8, "Area" => "Töreboda-Hova"),
    "505" => array ( "Max" => 9, "Min" => 8, "Area" => "Karlsborg"),
    "504" => array ( "Max" => 9, "Min" => 8, "Area" => "Tibro"),
    "503" => array ( "Max" => 9, "Min" => 8, "Area" => "Hjo"),
    "502" => array ( "Max" => 9, "Min" => 8, "Area" => "Tidaholm"),
    "501" => array ( "Max" => 9, "Min" => 8, "Area" => "Mariestad"),
    "500" => array ( "Max" => 9, "Min" => 8, "Area" => "Skövde"),
    "499" => array ( "Max" => 9, "Min" => 8, "Area" => "Mönsterås"),
    "498" => array ( "Max" => 9, "Min" => 8, "Area" => "Gotland"),
    "496" => array ( "Max" => 9, "Min" => 8, "Area" => "Mariannelund"),
    "495" => array ( "Max" => 9, "Min" => 8, "Area" => "Hultsfred-Virserum"),
    "494" => array ( "Max" => 9, "Min" => 8, "Area" => "Kisa"),
    "493" => array ( "Max" => 9, "Min" => 8, "Area" => "Gamleby"),
    "492" => array ( "Max" => 9, "Min" => 8, "Area" => "Vimmerby"),
    "491" => array ( "Max" => 9, "Min" => 8, "Area" => "Oskarshamn-Högsby"),
    "490" => array ( "Max" => 9, "Min" => 8, "Area" => "Västervik"),
    "486" => array ( "Max" => 9, "Min" => 8, "Area" => "Torsås"),
    "485" => array ( "Max" => 9, "Min" => 8, "Area" => "Öland"),
    "481" => array ( "Max" => 9, "Min" => 8, "Area" => "Nybro"),
    "480" => array ( "Max" => 9, "Min" => 8, "Area" => "Kalmar"),
    "479" => array ( "Max" => 9, "Min" => 8, "Area" => "Osby"),
    "478" => array ( "Max" => 9, "Min" => 8, "Area" => "Lessebo"),
    "477" => array ( "Max" => 9, "Min" => 8, "Area" => "Tingsryd"),
    "476" => array ( "Max" => 9, "Min" => 8, "Area" => "Älmhult"),
    "474" => array ( "Max" => 9, "Min" => 8, "Area" => "Åseda-Lenhovda"),
    "472" => array ( "Max" => 9, "Min" => 8, "Area" => "Alvesta-Rydaholm"),
    "471" => array ( "Max" => 9, "Min" => 8, "Area" => "Emmaboda"),
    "470" => array ( "Max" => 9, "Min" => 8, "Area" => "Växjö"),
    "459" => array ( "Max" => 9, "Min" => 8, "Area" => "Ryd"),
    "457" => array ( "Max" => 9, "Min" => 8, "Area" => "Ronneby"),
    "456" => array ( "Max" => 9, "Min" => 8, "Area" => "Sölvesborg-Bromölla"),
    "455" => array ( "Max" => 9, "Min" => 8, "Area" => "Karlskrona"),
    "454" => array ( "Max" => 9, "Min" => 8, "Area" => "Karlshamn-Olofström"),
    "451" => array ( "Max" => 9, "Min" => 8, "Area" => "Hässleholm"),
    "435" => array ( "Max" => 9, "Min" => 8, "Area" => "Klippan-Perstorp"),
    "433" => array ( "Max" => 9, "Min" => 8, "Area" => "Markaryd-Strömsnäsbruk"),
    "431" => array ( "Max" => 9, "Min" => 8, "Area" => "Ängelholm-Båstad"),
    "430" => array ( "Max" => 9, "Min" => 8, "Area" => "Laholm"),
    "418" => array ( "Max" => 9, "Min" => 8, "Area" => "Landskrona-Svalöv"),
    "417" => array ( "Max" => 9, "Min" => 8, "Area" => "Tomelilla"),
    "416" => array ( "Max" => 9, "Min" => 8, "Area" => "Sjöbo"),
    "415" => array ( "Max" => 9, "Min" => 8, "Area" => "Hörby"),
    "414" => array ( "Max" => 9, "Min" => 8, "Area" => "Simrishamn"),
    "413" => array ( "Max" => 9, "Min" => 8, "Area" => "Eslöv-Höör"),
    "411" => array ( "Max" => 9, "Min" => 8, "Area" => "Ystad"),
    "410" => array ( "Max" => 9, "Min" => 8, "Area" => "Trelleborg"),
    "393" => array ( "Max" => 9, "Min" => 8, "Area" => "Vaggeryd"),
    "392" => array ( "Max" => 9, "Min" => 8, "Area" => "Mullsjö"),
    "390" => array ( "Max" => 9, "Min" => 8, "Area" => "Gränna"),
    "383" => array ( "Max" => 9, "Min" => 8, "Area" => "Vetlanda"),
    "382" => array ( "Max" => 9, "Min" => 8, "Area" => "Sävsjö"),
    "381" => array ( "Max" => 9, "Min" => 8, "Area" => "Eksjö"),
    "380" => array ( "Max" => 9, "Min" => 8, "Area" => "Nässjö"),
    "372" => array ( "Max" => 9, "Min" => 8, "Area" => "Ljungby"),
    "371" => array ( "Max" => 9, "Min" => 8, "Area" => "Gislaved-Anderstorp"),
    "370" => array ( "Max" => 9, "Min" => 8, "Area" => "Värnamo"),
    "346" => array ( "Max" => 9, "Min" => 8, "Area" => "Falkenberg"),
    "345" => array ( "Max" => 9, "Min" => 8, "Area" => "Hyltebruk-Torup"),
    "340" => array ( "Max" => 9, "Min" => 8, "Area" => "Varberg"),
    "325" => array ( "Max" => 9, "Min" => 8, "Area" => "Svenljunga-Tranemo"),
    "322" => array ( "Max" => 9, "Min" => 8, "Area" => "Alingsås-Vårgårda"),
    "321" => array ( "Max" => 9, "Min" => 8, "Area" => "Ulricehamn"),
    "320" => array ( "Max" => 9, "Min" => 8, "Area" => "Kinna"),
    "304" => array ( "Max" => 9, "Min" => 8, "Area" => "Orust-Tjörn"),
    "303" => array ( "Max" => 9, "Min" => 8, "Area" => "Kungälv"),
    "302" => array ( "Max" => 9, "Min" => 8, "Area" => "Lerum"),
    "301" => array ( "Max" => 9, "Min" => 8, "Area" => "Hindås"),
    "300" => array ( "Max" => 9, "Min" => 8, "Area" => "Kungsbacka"),
    "297" => array ( "Max" => 9, "Min" => 8, "Area" => "Ockelbo-Hamrånge"),
    "295" => array ( "Max" => 9, "Min" => 8, "Area" => "Örbyhus-Dannemora"),
    "294" => array ( "Max" => 9, "Min" => 8, "Area" => "Karlholmsbruk-Skärplinge"),
    "293" => array ( "Max" => 9, "Min" => 8, "Area" => "Tierp-Söderfors"),
    "292" => array ( "Max" => 9, "Min" => 8, "Area" => "Tärnsjö-Östervåla"),
    "291" => array ( "Max" => 9, "Min" => 8, "Area" => "Hedesunda-Österfärnebo"),
    "290" => array ( "Max" => 9, "Min" => 8, "Area" => "Hofors-Storvik"),
    "281" => array ( "Max" => 9, "Min" => 8, "Area" => "Vansbro"),
    "280" => array ( "Max" => 9, "Min" => 8, "Area" => "Malung"),
    "278" => array ( "Max" => 9, "Min" => 8, "Area" => "Bollnäs"),
    "271" => array ( "Max" => 9, "Min" => 8, "Area" => "Alfta-Edsbyn"),
    "270" => array ( "Max" => 9, "Min" => 8, "Area" => "Söderhamn"),
    "258" => array ( "Max" => 9, "Min" => 8, "Area" => "Furudal"),
    "253" => array ( "Max" => 9, "Min" => 8, "Area" => "Idre-Särna"),
    "251" => array ( "Max" => 9, "Min" => 8, "Area" => "Älvdalen"),
    "250" => array ( "Max" => 9, "Min" => 8, "Area" => "Mora-Orsa"),
    "248" => array ( "Max" => 9, "Min" => 8, "Area" => "Rättvik"),
    "247" => array ( "Max" => 9, "Min" => 8, "Area" => "Leksand-Insjön"),
    "246" => array ( "Max" => 9, "Min" => 8, "Area" => "Svärdsjö-Enviken"),
    "243" => array ( "Max" => 9, "Min" => 8, "Area" => "Borlänge"),
    "241" => array ( "Max" => 9, "Min" => 8, "Area" => "Gagnef-Floda"),
    "240" => array ( "Max" => 9, "Min" => 8, "Area" => "Ludvika-Smedjebacken"),
    "227" => array ( "Max" => 9, "Min" => 8, "Area" => "Kungsör"),
    "226" => array ( "Max" => 9, "Min" => 8, "Area" => "Avesta-Krylbo"),
    "225" => array ( "Max" => 9, "Min" => 8, "Area" => "Hedemora-Säter"),
    "224" => array ( "Max" => 9, "Min" => 8, "Area" => "Sala-Heby"),
    "223" => array ( "Max" => 9, "Min" => 8, "Area" => "Fagersta-Norberg"),
    "222" => array ( "Max" => 9, "Min" => 8, "Area" => "Skinnskatteberg"),
    "221" => array ( "Max" => 9, "Min" => 8, "Area" => "Köping"),
    "220" => array ( "Max" => 9, "Min" => 8, "Area" => "Hallstahammar-Surahammar"),
    "176" => array ( "Max" => 9, "Min" => 8, "Area" => "Norrtälje"),
    "175" => array ( "Max" => 9, "Min" => 8, "Area" => "Hallstavik-Rimbo"),
    "174" => array ( "Max" => 9, "Min" => 8, "Area" => "Alunda"),
    "173" => array ( "Max" => 9, "Min" => 8, "Area" => "Öregrund-Östhammar"),
    "171" => array ( "Max" => 9, "Min" => 8, "Area" => "Enköping"),
    "159" => array ( "Max" => 9, "Min" => 8, "Area" => "Mariefred"),
    "158" => array ( "Max" => 9, "Min" => 8, "Area" => "Gnesta"),
    "157" => array ( "Max" => 9, "Min" => 8, "Area" => "Flen-Malmköping"),
    "156" => array ( "Max" => 9, "Min" => 8, "Area" => "Trosa-Vagnhärad"),
    "155" => array ( "Max" => 9, "Min" => 8, "Area" => "Nyköping-Oxelösund"),
    "152" => array ( "Max" => 9, "Min" => 8, "Area" => "Strängnäs"),
    "151" => array ( "Max" => 9, "Min" => 8, "Area" => "Vingåker"),
    "150" => array ( "Max" => 9, "Min" => 8, "Area" => "Katrineholm"),
    "144" => array ( "Max" => 9, "Min" => 8, "Area" => "Ödeshög"),
    "143" => array ( "Max" => 9, "Min" => 8, "Area" => "Vadstena"),
    "142" => array ( "Max" => 9, "Min" => 8, "Area" => "Mjölby-Skänninge-Boxholm"),
    "141" => array ( "Max" => 9, "Min" => 8, "Area" => "Motala"),
    "140" => array ( "Max" => 9, "Min" => 8, "Area" => "Tranås"),
    "125" => array ( "Max" => 9, "Min" => 8, "Area" => "Vikbolandet"),
    "123" => array ( "Max" => 9, "Min" => 8, "Area" => "Valdemarsvik"),
    "122" => array ( "Max" => 9, "Min" => 8, "Area" => "Finspång"),
    "121" => array ( "Max" => 9, "Min" => 8, "Area" => "Söderköping"),
    "120" => array ( "Max" => 9, "Min" => 8, "Area" => "Åtvidaberg"),
    "90" => array ( "Max" => 9, "Min" => 7, "Area" => "Umeå"),
    "63" => array ( "Max" => 9, "Min" => 7, "Area" => "Östersund"),
    "60" => array ( "Max" => 9, "Min" => 7, "Area" => "Sundsvall-Timrå"),
    "54" => array ( "Max" => 9, "Min" => 8, "Area" => "Karlstad"),
    "46" => array ( "Max" => 9, "Min" => 7, "Area" => "Lund"),
    "44" => array ( "Max" => 9, "Min" => 7, "Area" => "Kristianstad"),
    "42" => array ( "Max" => 9, "Min" => 7, "Area" => "Helsingborg-Höganäs"),
    "40" => array ( "Max" => 9, "Min" => 7, "Area" => "Malmö"),
    "36" => array ( "Max" => 9, "Min" => 7, "Area" => "Jönköping-Huskvarna"),
    "35" => array ( "Max" => 9, "Min" => 7, "Area" => "Halmstad"),
    "33" => array ( "Max" => 9, "Min" => 7, "Area" => "Borås"),
    "31" => array ( "Max" => 9, "Min" => 8, "Area" => "Göteborg"),
    "26" => array ( "Max" => 9, "Min" => 7, "Area" => "Gävle-Sandviken"),
    "23" => array ( "Max" => 9, "Min" => 7, "Area" => "Falun"),
    "21" => array ( "Max" => 9, "Min" => 7, "Area" => "Västerås"),
    "19" => array ( "Max" => 9, "Min" => 8, "Area" => "Örebro-Kumla"),
    "18" => array ( "Max" => 9, "Min" => 8, "Area" => "Uppsala"),
    "16" => array ( "Max" => 9, "Min" => 7, "Area" => "Eskilstuna-Torshälla"),
    "13" => array ( "Max" => 9, "Min" => 7, "Area" => "Linköping"),
    "11" => array ( "Max" => 9, "Min" => 7, "Area" => "Norrköping"),
    "8" => array ( "Max" => 9, "Min" => 7, "Area" => "Stockholm")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( strlen ( $parameters["Number"]) >= 3 + $data["Min"] && strlen ( $parameters["Number"]) <= 3 + $data["Max"] && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "46", "NDC" => $prefix, "Country" => "Sweden", "Area" => $data["Area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => trim ( substr ( $parameters["Number"], 3, strlen ( $prefix)) . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix))), "International" => "+46 " . substr ( $parameters["Number"], 3, strlen ( $prefix)) . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix)))));
    }
  }

  /**
   * Check for Paging network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "74"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) == 12 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "46", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Sweden", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 5) . " " . substr ( $parameters["Number"], 8), "International" => "+46 " . substr ( $parameters["Number"], 3, 5) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for PRN network with 3 digits NDC and 5 or 4 digits SN
   */
  $prefixes = array (
    "9448",
    "9447",
    "9446",
    "9445",
    "9444",
    "9443",
    "9442",
    "9441",
    "9398",
    "9397",
    "9396",
    "9395",
    "9394",
    "9393",
    "9392",
    "9391",
    "9008",
    "9007",
    "9006",
    "9005",
    "9004",
    "9003",
    "9002",
    "9001"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( strlen ( $parameters["Number"]) >= 10 && strlen ( $parameters["Number"]) <= 13 && (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "46", "NDC" => $prefix, "Country" => "Sweden", "Area" => $data["Area"], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => trim ( substr ( $parameters["Number"], 3, strlen ( $prefix)) . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix))), "International" => "+46 " . substr ( $parameters["Number"], 3, strlen ( $prefix)) . " " . substr ( $parameters["Number"], 3 + strlen ( $prefix)))));
    }
  }


  /**
   * If reached here, number wasn't identified as a valid Swedish phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
