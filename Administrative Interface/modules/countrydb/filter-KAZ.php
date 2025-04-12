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
 * related to country database of Kazakhstan.
 *
 * Reference: https://www.itu.int/oth/T020200006F/en (2012-11-13)
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
 * E.164 Kazakhstan country hook
 */
framework_add_filter ( "e164_identify_country_KAZ", "e164_identify_country_KAZ");

/**
 * E.164 Kazakhstanian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "KAZ" (code for
 * Kazakhstan). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_KAZ ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Kazakhstan
   */
  if ( substr ( $parameters["Number"], 0, 2) != "+7")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Kazakhstan has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "778" => "GSM Kazakhstan",
    "777" => "KaR-Tel (K-Mobile)",
    "775" => "GSM Kazakhstan (K-Cell)",
    "771" => "KaR-Tel (K-Mobile)",
    "707" => "Mobile Telecom Service",
    "705" => "KaR-Tel (K-Mobile)",
    "702" => "GSM Kazakhstan (K-Cell)",
    "701" => "GSM Kazakhstan (K-Cell)",
    "700" => "ALTEL"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 2, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "7", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Kazakhstan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5), "International" => "+7 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 4 or 5 digits NDC and 5 or 6 digits SN
   */
  $prefixes = array (
    "7272983" => array ( "operator" => "", "area" => "Kaskelen"),
    "7272956" => array ( "operator" => "", "area" => "Talgar"),
    "7272517" => array ( "operator" => "", "area" => "Otegen Batyra"),
    "712303" => array ( "operator" => "", "area" => "Tengizs"),
    "712302" => array ( "operator" => "", "area" => "Tengizshevroil"),
    "72938" => array ( "operator" => "", "area" => "Fort Shevchenko"),
    "72937" => array ( "operator" => "", "area" => "Kuryk"),
    "72935" => array ( "operator" => "", "area" => "Zhetybai"),
    "72934" => array ( "operator" => "", "area" => "Zhanaozen"),
    "72932" => array ( "operator" => "", "area" => "Beineu"),
    "72931" => array ( "operator" => "", "area" => "Shetpe"),
    "72843" => array ( "operator" => "", "area" => "Lepsy"),
    "72842" => array ( "operator" => "", "area" => "Kogaly"),
    "72841" => array ( "operator" => "", "area" => "Kapal"),
    "72840" => array ( "operator" => "", "area" => "Saryozek"),
    "72839" => array ( "operator" => "", "area" => "Sarkand"),
    "72838" => array ( "operator" => "", "area" => "Balpyk bi"),
    "72837" => array ( "operator" => "", "area" => "Kabanbai"),
    "72836" => array ( "operator" => "", "area" => "Karabulak"),
    "72835" => array ( "operator" => "", "area" => "Tekeli"),
    "72834" => array ( "operator" => "", "area" => "Ushtobe"),
    "72833" => array ( "operator" => "", "area" => "Usharal"),
    "72832" => array ( "operator" => "", "area" => "Zhansugurov"),
    "72831" => array ( "operator" => "", "area" => "Zharkent"),
    "72779" => array ( "operator" => "", "area" => "Narynkol"),
    "72778" => array ( "operator" => "", "area" => "Chundzha"),
    "72777" => array ( "operator" => "", "area" => "Kegen"),
    "72776" => array ( "operator" => "", "area" => "Shelek"),
    "72775" => array ( "operator" => "", "area" => "Esik"),
    "72774" => array ( "operator" => "", "area" => "Talgar"),
    "72773" => array ( "operator" => "", "area" => "Bakanas"),
    "72772" => array ( "operator" => "", "area" => "Kapchagai"),
    "72771" => array ( "operator" => "", "area" => "Kaskelen"),
    "72770" => array ( "operator" => "", "area" => "Uzynagash"),
    "72757" => array ( "operator" => "", "area" => "Akshi"),
    "72752" => array ( "operator" => "", "area" => "Otegen Batyra"),
    "72644" => array ( "operator" => "", "area" => "Karatau"),
    "72643" => array ( "operator" => "", "area" => "Shu"),
    "72642" => array ( "operator" => "", "area" => "Moiynkum"),
    "72641" => array ( "operator" => "", "area" => "Akkol"),
    "72639" => array ( "operator" => "", "area" => "Saudakent"),
    "72638" => array ( "operator" => "", "area" => "Tole bi"),
    "72637" => array ( "operator" => "", "area" => "Sarykemer"),
    "72636" => array ( "operator" => "", "area" => "Kordai"),
    "72635" => array ( "operator" => "", "area" => "Bauyrzhan Mamyshuly"),
    "72634" => array ( "operator" => "", "area" => "Zhanatas"),
    "72633" => array ( "operator" => "", "area" => "Asa"),
    "72632" => array ( "operator" => "", "area" => "Merke"),
    "72631" => array ( "operator" => "", "area" => "Kulan"),
    "72561" => array ( "operator" => "", "area" => "** Commercial Network Easttelecom"),
    "72548" => array ( "operator" => "", "area" => "Shayan"),
    "72547" => array ( "operator" => "", "area" => "Lenger"),
    "72546" => array ( "operator" => "", "area" => "Sholakkorgan"),
    "72544" => array ( "operator" => "", "area" => "Shaulder"),
    "72542" => array ( "operator" => "", "area" => "Asykata"),
    "72541" => array ( "operator" => "", "area" => "Myrzakent"),
    "72540" => array ( "operator" => "", "area" => "Arys"),
    "72539" => array ( "operator" => "", "area" => "Kazygurt"),
    "72538" => array ( "operator" => "", "area" => "Turara Ryskulova"),
    "72537" => array ( "operator" => "", "area" => "Saryagash"),
    "72536" => array ( "operator" => "", "area" => "Kentau"),
    "72535" => array ( "operator" => "", "area" => "Shardara"),
    "72534" => array ( "operator" => "", "area" => "Zhetysai"),
    "72533" => array ( "operator" => "", "area" => "Turkestan"),
    "72532" => array ( "operator" => "", "area" => "Abai"),
    "72531" => array ( "operator" => "", "area" => "Aksukent"),
    "72530" => array ( "operator" => "", "area" => "Temirlanovka"),
    "72438" => array ( "operator" => "", "area" => "Aiteke bi"),
    "72437" => array ( "operator" => "", "area" => "Zhosaly"),
    "72436" => array ( "operator" => "", "area" => "Terenozek"),
    "72435" => array ( "operator" => "", "area" => "Zhanakorgan"),
    "72433" => array ( "operator" => "", "area" => "Aralsk"),
    "72432" => array ( "operator" => "", "area" => "Shiyeli"),
    "72431" => array ( "operator" => "", "area" => "Zhalagash"),
    "72353" => array ( "operator" => "", "area" => "Novaya Shulba"),
    "72351" => array ( "operator" => "", "area" => "Borodulikha"),
    "72348" => array ( "operator" => "", "area" => "Kokpekty"),
    "72347" => array ( "operator" => "", "area" => "Kalbatau"),
    "72346" => array ( "operator" => "", "area" => "Aksuat"),
    "72345" => array ( "operator" => "", "area" => "Shar"),
    "72344" => array ( "operator" => "", "area" => "Akzhar"),
    "72343" => array ( "operator" => "", "area" => "Terekty"),
    "72342" => array ( "operator" => "", "area" => "Katon-Karagai"),
    "72341" => array ( "operator" => "", "area" => "Ulken Naryn"),
    "72340" => array ( "operator" => "", "area" => "Zaisan"),
    "72339" => array ( "operator" => "", "area" => "Kurchum"),
    "72338" => array ( "operator" => "", "area" => "Molodezhnyi"),
    "72337" => array ( "operator" => "", "area" => "Serebryansk"),
    "72336" => array ( "operator" => "", "area" => "Ridder"),
    "72335" => array ( "operator" => "", "area" => "Zyryanovsk"),
    "72334" => array ( "operator" => "", "area" => "Tavricheskoye"),
    "72333" => array ( "operator" => "", "area" => "Samarskoye"),
    "72332" => array ( "operator" => "", "area" => "Shemonaikha"),
    "72331" => array ( "operator" => "", "area" => "Glubokoye"),
    "72257" => array ( "operator" => "", "area" => "Shulbinsk"),
    "72256" => array ( "operator" => "", "area" => "Kainar"),
    "72252" => array ( "operator" => "", "area" => "Karaul"),
    "72251" => array ( "operator" => "", "area" => "Kurchatov"),
    "72246" => array ( "operator" => "", "area" => "Barshatas"),
    "72239" => array ( "operator" => "", "area" => "Makanchi"),
    "72237" => array ( "operator" => "", "area" => "Ayagoz"),
    "72236" => array ( "operator" => "", "area" => "Beskaragai"),
    "72230" => array ( "operator" => "", "area" => "Urdzhar"),
    "72156" => array ( "operator" => "", "area" => "Shakhtinsk"),
    "72154" => array ( "operator" => "", "area" => "Botakara"),
    "72153" => array ( "operator" => "", "area" => "Topar"),
    "72149" => array ( "operator" => "", "area" => "Osakarovka"),
    "72148" => array ( "operator" => "", "area" => "Molodezhnoye"),
    "72147" => array ( "operator" => "", "area" => "Egindybulak"),
    "72146" => array ( "operator" => "", "area" => "Karkaralinsk"),
    "72144" => array ( "operator" => "", "area" => "Kiyevka"),
    "72138" => array ( "operator" => "", "area" => "Gabidena Mustafina"),
    "72137" => array ( "operator" => "", "area" => "Saran"),
    "72131" => array ( "operator" => "", "area" => "Abai"),
    "71845" => array ( "operator" => "", "area" => "Pavlodar"),
    "71841" => array ( "operator" => "", "area" => "Aktogai"),
    "71840" => array ( "operator" => "", "area" => "Bayanaul"),
    "71839" => array ( "operator" => "", "area" => "Akku"),
    "71838" => array ( "operator" => "", "area" => "Koktobe"),
    "71837" => array ( "operator" => "", "area" => "Aksu"),
    "71836" => array ( "operator" => "", "area" => "Sharbakty"),
    "71834" => array ( "operator" => "", "area" => "Uspenka"),
    "71833" => array ( "operator" => "", "area" => "Terenkol"),
    "71832" => array ( "operator" => "", "area" => "Irtyshsk"),
    "71831" => array ( "operator" => "", "area" => "Zhelezinka"),
    "71661" => array ( "operator" => "", "area" => "** Commercial Network Easttelecom"),
    "71651" => array ( "operator" => "", "area" => "Kabanbai Batyr"),
    "71648" => array ( "operator" => "", "area" => "Derzhavinsk"),
    "71647" => array ( "operator" => "", "area" => "Esil"),
    "71646" => array ( "operator" => "", "area" => "Makinsk"),
    "71645" => array ( "operator" => "", "area" => "Stepnogorsk"),
    "71644" => array ( "operator" => "", "area" => "Arshaly"),
    "71643" => array ( "operator" => "", "area" => "Atbasar"),
    "71642" => array ( "operator" => "", "area" => "Egendykol"),
    "71641" => array ( "operator" => "", "area" => "Astrakhanka"),
    "71640" => array ( "operator" => "", "area" => "Balkashino"),
    "71639" => array ( "operator" => "", "area" => "Stepnyak"),
    "71638" => array ( "operator" => "", "area" => "Akkol"),
    "71637" => array ( "operator" => "", "area" => "Korgalzhyn"),
    "71636" => array ( "operator" => "", "area" => "Shuchinsk"),
    "71635" => array ( "operator" => "", "area" => "Zhaksy"),
    "71633" => array ( "operator" => "", "area" => "Ereimentau"),
    "71632" => array ( "operator" => "", "area" => "Zerenda"),
    "71631" => array ( "operator" => "", "area" => "Shortandy"),
    "71630" => array ( "operator" => "", "area" => "Burabay"),
    "71546" => array ( "operator" => "", "area" => "Talshik"),
    "71544" => array ( "operator" => "", "area" => "Presnovka"),
    "71543" => array ( "operator" => "", "area" => "Yavlenka"),
    "71542" => array ( "operator" => "", "area" => "Kishkenekol"),
    "71541" => array ( "operator" => "", "area" => "Mamlutka"),
    "71538" => array ( "operator" => "", "area" => "Beskol"),
    "71537" => array ( "operator" => "", "area" => "Timiryazevo"),
    "71536" => array ( "operator" => "", "area" => "Taiynsha"),
    "71535" => array ( "operator" => "", "area" => "Novoishimski"),
    "71534" => array ( "operator" => "", "area" => "Sergeyevka"),
    "71533" => array ( "operator" => "", "area" => "Saumalkol"),
    "71532" => array ( "operator" => "", "area" => "Smirnovo"),
    "71531" => array ( "operator" => "", "area" => "Bulayevo"),
    "71456" => array ( "operator" => "", "area" => "Kachar"),
    "71455" => array ( "operator" => "", "area" => "Zatobolsk"),
    "71454" => array ( "operator" => "", "area" => "Karamendy"),
    "71453" => array ( "operator" => "", "area" => "Auliekol"),
    "71452" => array ( "operator" => "", "area" => "Karasu"),
    "71451" => array ( "operator" => "", "area" => "Sarykol"),
    "71448" => array ( "operator" => "", "area" => "Oktyabrskoye"),
    "71445" => array ( "operator" => "", "area" => "Ubaganskoye"),
    "71444" => array ( "operator" => "", "area" => "Uzunkol"),
    "71443" => array ( "operator" => "", "area" => "Borovskoi"),
    "71442" => array ( "operator" => "", "area" => "Fyodorovka"),
    "71441" => array ( "operator" => "", "area" => "Karabalyk"),
    "71440" => array ( "operator" => "", "area" => "Amangeldy"),
    "71439" => array ( "operator" => "", "area" => "Torgai"),
    "71437" => array ( "operator" => "", "area" => "Kamysty"),
    "71436" => array ( "operator" => "", "area" => "Taranovskoye"),
    "71435" => array ( "operator" => "", "area" => "Zhitikara"),
    "71434" => array ( "operator" => "", "area" => "Denisovka"),
    "71433" => array ( "operator" => "", "area" => "Lisakovsk"),
    "71431" => array ( "operator" => "", "area" => "Rudny"),
    "71430" => array ( "operator" => "", "area" => "Arkalyk"),
    "71346" => array ( "operator" => "", "area" => "Shubarkuduk"),
    "71345" => array ( "operator" => "", "area" => "Karauylkeldy"),
    "71343" => array ( "operator" => "", "area" => "Irgiz"),
    "71342" => array ( "operator" => "", "area" => "Badamsha"),
    "71341" => array ( "operator" => "", "area" => "Khobda"),
    "71339" => array ( "operator" => "", "area" => "Komsomolskoye"),
    "71337" => array ( "operator" => "", "area" => "Alga"),
    "71336" => array ( "operator" => "", "area" => "Khromtau"),
    "71335" => array ( "operator" => "", "area" => "Shalkar"),
    "71334" => array ( "operator" => "", "area" => "Emba"),
    "71333" => array ( "operator" => "", "area" => "Kandyagash"),
    "71332" => array ( "operator" => "", "area" => "Uil"),
    "71331" => array ( "operator" => "", "area" => "Martuk"),
    "71239" => array ( "operator" => "", "area" => "Makat"),
    "71238" => array ( "operator" => "", "area" => "Miyaly"),
    "71237" => array ( "operator" => "", "area" => "Kulsary"),
    "71236" => array ( "operator" => "", "area" => "Makhambet"),
    "71235" => array ( "operator" => "", "area" => "Dossor"),
    "71234" => array ( "operator" => "", "area" => "Indernborski"),
    "71233" => array ( "operator" => "", "area" => "Ganyushkino"),
    "71231" => array ( "operator" => "", "area" => "Akkystau"),
    "71145" => array ( "operator" => "", "area" => "Karatobe"),
    "71144" => array ( "operator" => "", "area" => "Kaztalovka"),
    "71143" => array ( "operator" => "", "area" => "Akzhaik"),
    "71142" => array ( "operator" => "", "area" => "Taipak"),
    "71141" => array ( "operator" => "", "area" => "Zhangala"),
    "71140" => array ( "operator" => "", "area" => "Saikhin"),
    "71139" => array ( "operator" => "", "area" => "Taskala"),
    "71138" => array ( "operator" => "", "area" => "Zhalpaktal"),
    "71137" => array ( "operator" => "", "area" => "Chingirlau"),
    "71136" => array ( "operator" => "", "area" => "Chapayev"),
    "71135" => array ( "operator" => "", "area" => "Zhanibek"),
    "71134" => array ( "operator" => "", "area" => "Zhympity"),
    "71133" => array ( "operator" => "", "area" => "Aksai"),
    "71132" => array ( "operator" => "", "area" => "Fyodorovka"),
    "71131" => array ( "operator" => "", "area" => "Darinskoye"),
    "71130" => array ( "operator" => "", "area" => "Peremetnoye"),
    "71063" => array ( "operator" => "", "area" => "Satpaev"),
    "71043" => array ( "operator" => "", "area" => "Zhairem"),
    "71042" => array ( "operator" => "", "area" => "Zharyk"),
    "71040" => array ( "operator" => "", "area" => "Zhairem (GOK)"),
    "71039" => array ( "operator" => "", "area" => "Priozersk"),
    "71038" => array ( "operator" => "", "area" => "Shashubai"),
    "71037" => array ( "operator" => "", "area" => "Aktogai"),
    "71036" => array ( "operator" => "", "area" => "Balkhash"),
    "71035" => array ( "operator" => "", "area" => "Ulytau"),
    "71034" => array ( "operator" => "", "area" => "Zhezdy"),
    "71033" => array ( "operator" => "", "area" => "Agadyr"),
    "71032" => array ( "operator" => "", "area" => "Karazhal"),
    "71031" => array ( "operator" => "", "area" => "Aksu-Ayuly"),
    "71030" => array ( "operator" => "", "area" => "Atasu"),
    "7292" => array ( "operator" => "", "area" => "Aktau"),
    "7282" => array ( "operator" => "", "area" => "Taldykorgan"),
    "7273" => array ( "operator" => "", "area" => "Almaty"),
    "7272" => array ( "operator" => "", "area" => "Almaty"),
    "7262" => array ( "operator" => "", "area" => "Taraz"),
    "7252" => array ( "operator" => "", "area" => "Shymkent"),
    "7242" => array ( "operator" => "", "area" => "Kyzylorda"),
    "7232" => array ( "operator" => "", "area" => "Ust-Kamenogorsk"),
    "7222" => array ( "operator" => "", "area" => "Semey"),
    "7213" => array ( "operator" => "", "area" => "Aktau and Temirtau"),
    "7212" => array ( "operator" => "", "area" => "Karaganda"),
    "7187" => array ( "operator" => "", "area" => "Ekibastuz"),
    "7182" => array ( "operator" => "", "area" => "Pavlodar"),
    "7172" => array ( "operator" => "", "area" => "Astana"),
    "7162" => array ( "operator" => "", "area" => "Kokshetau and Krasni Yar"),
    "7152" => array ( "operator" => "", "area" => "Petropavlovsk"),
    "7142" => array ( "operator" => "", "area" => "Kostanai"),
    "7132" => array ( "operator" => "", "area" => "Aktobe and Kargalinskoye"),
    "7122" => array ( "operator" => "", "area" => "Atyrau"),
    "7112" => array ( "operator" => "", "area" => "Uralsk"),
    "7102" => array ( "operator" => "", "area" => "Zhezkazgan"),
    "764" => array ( "operator" => "2Day Telecom", "area" => "** Commercial Network"),
    "763" => array ( "operator" => "Arna", "area" => "** Commercial Network")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 2, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "7", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Kazakhstan", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5), "International" => "+7 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for VSAT network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "72959" => "",
    "72859" => "",
    "72830" => "",
    "72759" => "",
    "72740" => "",
    "72659" => "",
    "72640" => "",
    "72559" => "",
    "72459" => "",
    "72439" => "",
    "72359" => "",
    "72330" => "",
    "72259" => "",
    "72159" => "",
    "71844" => "",
    "71843" => "",
    "71842" => "",
    "71659" => "",
    "71649" => "",
    "71559" => "",
    "71547" => "",
    "71545" => "",
    "71459" => "",
    "71457" => "",
    "71447" => "",
    "71446" => "",
    "71359" => "",
    "71349" => "",
    "71348" => "",
    "71347" => "",
    "71340" => "",
    "71259" => "",
    "71159" => "",
    "71149" => "",
    "71147" => "",
    "71146" => "",
    "71059" => "",
    "762" => "Nursat",
    "760" => "Kulan"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 2, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "7", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Kazakhstan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5), "International" => "+7 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "751"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 2, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "7", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Kazakhstan", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5), "International" => "+7 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for PRN network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "785" => "Darkhan Telecom"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 2, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "7", "NDC" => substr ( $parameters["Number"], 2, 3), "Country" => "Kazakhstan", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5), "International" => "+7 " . substr ( $parameters["Number"], 2, 3) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Kazakhstanian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
