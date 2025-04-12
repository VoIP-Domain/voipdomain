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
 * related to country database of Ethiopia.
 *
 * Reference: https://www.itu.int/oth/T0202000044/en (2006-07-20)
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
 * E.164 Ethiopia country hook
 */
framework_add_filter ( "e164_identify_country_ETH", "e164_identify_country_ETH");

/**
 * E.164 Ethiopian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "ETH" (code for Ethiopia). This
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
function e164_identify_country_ETH ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Ethiopia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+251")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Ethiopia has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "91877" => "North-Western Region",
    "91876" => "North-Western Region",
    "91835" => "North-Western Region",
    "91834" => "North-Western Region",
    "91781" => "Western Region",
    "91780" => "Western Region",
    "91757" => "Western Region",
    "91755" => "Western Region",
    "91683" => "Southern Region",
    "91682" => "Southern Region",
    "91658" => "Southern Region",
    "91575" => "East Region",
    "91574" => "East Region",
    "91573" => "East Region",
    "91533" => "East Region",
    "91532" => "East Region",
    "91472" => "Northern Region",
    "91471" => "Northern Region",
    "91470" => "Northern Region",
    "91431" => "Northern Region",
    "91430" => "Northern Region",
    "91189" => "Addis Ababa and Surroundings",
    "91188" => "Addis Ababa and Surroundings",
    "91187" => "Addis Ababa and Surroundings",
    "91186" => "Addis Ababa and Surroundings",
    "91184" => "Addis Ababa and Surroundings",
    "91169" => "Addis Ababa and Surroundings",
    "91168" => "Addis Ababa and Surroundings",
    "91167" => "Addis Ababa and Surroundings",
    "91166" => "Addis Ababa and Surroundings",
    "91165" => "Addis Ababa and Surroundings",
    "91164" => "Addis Ababa and Surroundings",
    "91163" => "Addis Ababa and Surroundings",
    "91162" => "Addis Ababa and Surroundings",
    "91161" => "Addis Ababa and Surroundings",
    "91160" => "Addis Ababa and Surroundings",
    "91150" => "Addis Ababa and Surroundings",
    "91149" => "Addis Ababa and Surroundings",
    "91148" => "Addis Ababa and Surroundings",
    "91147" => "Addis Ababa and Surroundings",
    "91146" => "Addis Ababa and Surroundings",
    "91145" => "Addis Ababa and Surroundings",
    "91144" => "Addis Ababa and Surroundings",
    "91143" => "Addis Ababa and Surroundings",
    "91142" => "Addis Ababa and Surroundings",
    "91141" => "Addis Ababa and Surroundings",
    "91140" => "Addis Ababa and Surroundings",
    "91125" => "Addis Ababa and Surroundings",
    "91124" => "Addis Ababa and Surroundings",
    "91123" => "Addis Ababa and Surroundings",
    "91122" => "Addis Ababa and Surroundings",
    "91121" => "Addis Ababa and Surroundings",
    "91120" => "Addis Ababa and Surroundings",
    "91119" => "Addis Ababa and Surroundings",
    "91118" => "Addis Ababa and Surroundings",
    "91117" => "Addis Ababa and Surroundings",
    "91116" => "Addis Ababa and Surroundings",
    "91115" => "Addis Ababa and Surroundings",
    "91114" => "Addis Ababa and Surroundings",
    "91113" => "Addis Ababa and Surroundings",
    "91112" => "Addis Ababa and Surroundings",
    "91111" => "Addis Ababa and Surroundings",
    "91110" => "Addis Ababa and Surroundings"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "251", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ethiopia", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+251 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "116880" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Enwari"),
    "116870" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Sheno"),
    "116860" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Sendafa"),
    "116650" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Civil Aviation"),
    "116640" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Bole V"),
    "113870" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Alem Gena"),
    "113420" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Tullu Bollo"),
    "113390" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Teji"),
    "113380" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Sebeta"),
    "113320" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Gunchire"),
    "113310" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Endibir"),
    "112860" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Enchini"),
    "112850" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Wolenkomi"),
    "112820" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Guder"),
    "111860" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Sululta"),
    "111340" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Muke Turi"),
    "111330" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Deber Tsige"),
    "111320" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Alem Ketema"),
    "58779" => array ( "area" => "North-Western Region (Region 8)", "city" => "Jiga"),
    "58778" => array ( "area" => "North-Western Region (Region 8)", "city" => "Debre Markos II"),
    "58777" => array ( "area" => "North-Western Region (Region 8)", "city" => "Amanuel"),
    "58776" => array ( "area" => "North-Western Region (Region 8)", "city" => "Dejen"),
    "58775" => array ( "area" => "North-Western Region (Region 8)", "city" => "Finote-selam"),
    "58774" => array ( "area" => "North-Western Region (Region 8)", "city" => "Bure"),
    "58773" => array ( "area" => "North-Western Region (Region 8)", "city" => "Denbecha"),
    "58772" => array ( "area" => "North-Western Region (Region 8)", "city" => "Lumame"),
    "58771" => array ( "area" => "North-Western Region (Region 8)", "city" => "Debre-Markos I"),
    "58770" => array ( "area" => "North-Western Region (Region 8)", "city" => "Mankusa"),
    "58665" => array ( "area" => "North-Western Region (Region 8)", "city" => "Bichena"),
    "58664" => array ( "area" => "North-Western Region (Region 8)", "city" => "Gunde-woin"),
    "58663" => array ( "area" => "North-Western Region (Region 8)", "city" => "Debre-work"),
    "58662" => array ( "area" => "North-Western Region (Region 8)", "city" => "Keraniyo"),
    "58661" => array ( "area" => "North-Western Region (Region 8)", "city" => "Motta"),
    "58550" => array ( "area" => "North-Western Region (Region 8)", "city" => "Pawe"),
    "58448" => array ( "area" => "North-Western Region (Region 8)", "city" => "Teda"),
    "58447" => array ( "area" => "North-Western Region (Region 8)", "city" => "Mekane-eyesus"),
    "58446" => array ( "area" => "North-Western Region (Region 8)", "city" => "Worota"),
    "58445" => array ( "area" => "North-Western Region (Region 8)", "city" => "Nefas Mewcha"),
    "58444" => array ( "area" => "North-Western Region (Region 8)", "city" => "Addis Zemen"),
    "58443" => array ( "area" => "North-Western Region (Region 8)", "city" => "Hamusit"),
    "58441" => array ( "area" => "North-Western Region (Region 8)", "city" => "Debre-tabour"),
    "58440" => array ( "area" => "North-Western Region (Region 8)", "city" => "Ebinat"),
    "58338" => array ( "area" => "North-Western Region (Region 8)", "city" => "Adet"),
    "58336" => array ( "area" => "North-Western Region (Region 8)", "city" => "Delgi"),
    "58335" => array ( "area" => "North-Western Region (Region 8)", "city" => "Kola-deba"),
    "58334" => array ( "area" => "North-Western Region (Region 8)", "city" => "Chewahit"),
    "58333" => array ( "area" => "North-Western Region (Region 8)", "city" => "Chilga"),
    "58332" => array ( "area" => "North-Western Region (Region 8)", "city" => "Maksegnit"),
    "58331" => array ( "area" => "North-Western Region (Region 8)", "city" => "Metema"),
    "58330" => array ( "area" => "North-Western Region (Region 8)", "city" => "Merawi"),
    "58229" => array ( "area" => "North-Western Region (Region 8)", "city" => "Tilili"),
    "58227" => array ( "area" => "North-Western Region (Region 8)", "city" => "Enjibara Kosober"),
    "58226" => array ( "area" => "North-Western Region (Region 8)", "city" => "Bahirdar II"),
    "58225" => array ( "area" => "North-Western Region (Region 8)", "city" => "Chagni/Metekel"),
    "58224" => array ( "area" => "North-Western Region (Region 8)", "city" => "Gimjabetmariam"),
    "58223" => array ( "area" => "North-Western Region (Region 8)", "city" => "Durbette/Abcheklite"),
    "58221" => array ( "area" => "North-Western Region (Region 8)", "city" => "Dangla"),
    "58220" => array ( "area" => "North-Western Region (Region 8)", "city" => "Bahir-dar I"),
    "58119" => array ( "area" => "North-Western Region (Region 8)", "city" => "Gilgel Beles"),
    "58114" => array ( "area" => "North-Western Region (Region 8)", "city" => "Azezo"),
    "58111" => array ( "area" => "North-Western Region (Region 8)", "city" => "Gonder"),
    "57778" => array ( "area" => "South-Western Region (Region 7)", "city" => "Guliso"),
    "57777" => array ( "area" => "South-Western Region (Region 7)", "city" => "Billa"),
    "57776" => array ( "area" => "South-Western Region (Region 7)", "city" => "Mendi"),
    "57775" => array ( "area" => "South-Western Region (Region 7)", "city" => "Assosa"),
    "57774" => array ( "area" => "South-Western Region (Region 7)", "city" => "Nedjo"),
    "57771" => array ( "area" => "South-Western Region (Region 7)", "city" => "Ghimbi"),
    "57668" => array ( "area" => "South-Western Region (Region 7)", "city" => "Sire"),
    "57667" => array ( "area" => "South-Western Region (Region 7)", "city" => "Arjo"),
    "57666" => array ( "area" => "South-Western Region (Region 7)", "city" => "Shambu"),
    "57665" => array ( "area" => "South-Western Region (Region 7)", "city" => "Backo"),
    "57664" => array ( "area" => "South-Western Region (Region 7)", "city" => "Fincha"),
    "57661" => array ( "area" => "South-Western Region (Region 7)", "city" => "Nekemte"),
    "57555" => array ( "area" => "South-Western Region (Region 7)", "city" => "Dembidolo"),
    "57550" => array ( "area" => "South-Western Region (Region 7)", "city" => "Ejaji"),
    "57227" => array ( "area" => "South-Western Region (Region 7)", "city" => "Ghedo"),
    "47559" => array ( "area" => "South-Western Region (Region 7)", "city" => "Abebo"),
    "47558" => array ( "area" => "South-Western Region (Region 7)", "city" => "Macha"),
    "47556" => array ( "area" => "South-Western Region (Region 7)", "city" => "Tepi"),
    "47554" => array ( "area" => "South-Western Region (Region 7)", "city" => "Gore"),
    "47553" => array ( "area" => "South-Western Region (Region 7)", "city" => "Jikawo"),
    "47552" => array ( "area" => "South-Western Region (Region 7)", "city" => "Itang"),
    "47551" => array ( "area" => "South-Western Region (Region 7)", "city" => "Gambela"),
    "47446" => array ( "area" => "South-Western Region (Region 7)", "city" => "Hurumu"),
    "47445" => array ( "area" => "South-Western Region (Region 7)", "city" => "Bedele"),
    "47444" => array ( "area" => "South-Western Region (Region 7)", "city" => "Darimu"),
    "47443" => array ( "area" => "South-Western Region (Region 7)", "city" => "Dembi"),
    "47441" => array ( "area" => "South-Western Region (Region 7)", "city" => "Metu"),
    "47337" => array ( "area" => "South-Western Region (Region 7)", "city" => "Chora"),
    "47336" => array ( "area" => "South-Western Region (Region 7)", "city" => "Aman"),
    "47335" => array ( "area" => "South-Western Region (Region 7)", "city" => "Mizan Teferi"),
    "47334" => array ( "area" => "South-Western Region (Region 7)", "city" => "Maji"),
    "47333" => array ( "area" => "South-Western Region (Region 7)", "city" => "Yayo"),
    "47331" => array ( "area" => "South-Western Region (Region 7)", "city" => "Bonga"),
    "47229" => array ( "area" => "South-Western Region (Region 7)", "city" => "Ghembe"),
    "47228" => array ( "area" => "South-Western Region (Region 7)", "city" => "Atnago"),
    "47226" => array ( "area" => "South-Western Region (Region 7)", "city" => "Yebu"),
    "47225" => array ( "area" => "South-Western Region (Region 7)", "city" => "Haro"),
    "47224" => array ( "area" => "South-Western Region (Region 7)", "city" => "Limmu Genet"),
    "47223" => array ( "area" => "South-Western Region (Region 7)", "city" => "Dedo"),
    "47222" => array ( "area" => "South-Western Region (Region 7)", "city" => "Ghembo"),
    "47221" => array ( "area" => "South-Western Region (Region 7)", "city" => "Agaro"),
    "47118" => array ( "area" => "South-Western Region (Region 7)", "city" => "Shebe"),
    "47117" => array ( "area" => "South-Western Region (Region 7)", "city" => "Sekoru"),
    "47116" => array ( "area" => "South-Western Region (Region 7)", "city" => "Seka"),
    "47115" => array ( "area" => "South-Western Region (Region 7)", "city" => "Omonada"),
    "47114" => array ( "area" => "South-Western Region (Region 7)", "city" => "Assendabo"),
    "47113" => array ( "area" => "South-Western Region (Region 7)", "city" => "Serbo"),
    "47112" => array ( "area" => "South-Western Region (Region 7)", "city" => "Jimma II"),
    "47111" => array ( "area" => "South-Western Region (Region 7)", "city" => "Jimma I"),
    "46883" => array ( "area" => "South Region (Region 6)", "city" => "Buii"),
    "46882" => array ( "area" => "South Region (Region 6)", "city" => "Kibet"),
    "46881" => array ( "area" => "South Region (Region 6)", "city" => "Arba Minch"),
    "46777" => array ( "area" => "South Region (Region 6)", "city" => "Sawla"),
    "46774" => array ( "area" => "South Region (Region 6)", "city" => "Gidole"),
    "46771" => array ( "area" => "South Region (Region 6)", "city" => "Werabe"),
    "46660" => array ( "area" => "South Region (Region 6)", "city" => "Kebado"),
    "46559" => array ( "area" => "South Region (Region 6)", "city" => "Boditi"),
    "46558" => array ( "area" => "South Region (Region 6)", "city" => "Enseno"),
    "46556" => array ( "area" => "South Region (Region 6)", "city" => "Alaba Kulito"),
    "46555" => array ( "area" => "South Region (Region 6)", "city" => "Hossena"),
    "46554" => array ( "area" => "South Region (Region 6)", "city" => "Durame"),
    "46551" => array ( "area" => "South Region (Region 6)", "city" => "Wollayta"),
    "46449" => array ( "area" => "South Region (Region 6)", "city" => "Dolo Odo"),
    "46446" => array ( "area" => "South Region (Region 6)", "city" => "Yabello"),
    "46445" => array ( "area" => "South Region (Region 6)", "city" => "Negele Borena"),
    "46444" => array ( "area" => "South Region (Region 6)", "city" => "Moyale"),
    "46443" => array ( "area" => "South Region (Region 6)", "city" => "Hagere Mariam"),
    "46441" => array ( "area" => "South Region (Region 6)", "city" => "Ziway"),
    "46335" => array ( "area" => "South Region (Region 6)", "city" => "Kibre-Mengist"),
    "46334" => array ( "area" => "South Region (Region 6)", "city" => "Shakiso"),
    "46333" => array ( "area" => "South Region (Region 6)", "city" => "Wonago"),
    "46332" => array ( "area" => "South Region (Region 6)", "city" => "Yirga-Chefe"),
    "46331" => array ( "area" => "South Region (Region 6)", "city" => "Dilla"),
    "46227" => array ( "area" => "South Region (Region 6)", "city" => "Chuko"),
    "46226" => array ( "area" => "South Region (Region 6)", "city" => "Leku"),
    "46225" => array ( "area" => "South Region (Region 6)", "city" => "Yirgalem"),
    "46224" => array ( "area" => "South Region (Region 6)", "city" => "Aleta Wondo"),
    "46222" => array ( "area" => "South Region (Region 6)", "city" => "Wonda Basha"),
    "46221" => array ( "area" => "South Region (Region 6)", "city" => "Awassa II"),
    "46220" => array ( "area" => "South Region (Region 6)", "city" => "Awassa I"),
    "46118" => array ( "area" => "South Region (Region 6)", "city" => "Kuyera"),
    "46117" => array ( "area" => "South Region (Region 6)", "city" => "Adame Tulu"),
    "46116" => array ( "area" => "South Region (Region 6)", "city" => "Arsi Negele"),
    "46115" => array ( "area" => "South Region (Region 6)", "city" => "Butajira"),
    "46114" => array ( "area" => "South Region (Region 6)", "city" => "Wondo Kela"),
    "46112" => array ( "area" => "South Region (Region 6)", "city" => "Kofele"),
    "46111" => array ( "area" => "South Region (Region 6)", "city" => "Shashamane II"),
    "46110" => array ( "area" => "South Region (Region 6)", "city" => "Shashamane I"),
    "34775" => array ( "area" => "North Region (Region 4)", "city" => "Axum"),
    "34774" => array ( "area" => "North Region (Region 4)", "city" => "Alemata"),
    "34773" => array ( "area" => "North Region (Region 4)", "city" => "Edaga-Hamus"),
    "34772" => array ( "area" => "North Region (Region 4)", "city" => "Inticho"),
    "34771" => array ( "area" => "North Region (Region 4)", "city" => "Adwa"),
    "34663" => array ( "area" => "North Region (Region 4)", "city" => "Waja"),
    "34662" => array ( "area" => "North Region (Region 4)", "city" => "Mai-Tebri"),
    "34661" => array ( "area" => "North Region (Region 4)", "city" => "Endabaguna"),
    "34660" => array ( "area" => "North Region (Region 4)", "city" => "Adi Gudem"),
    "34556" => array ( "area" => "North Region (Region 4)", "city" => "Adi Daero"),
    "34555" => array ( "area" => "North Region (Region 4)", "city" => "Rama"),
    "34554" => array ( "area" => "North Region (Region 4)", "city" => "A.Selam"),
    "34552" => array ( "area" => "North Region (Region 4)", "city" => "Betemariam"),
    "34551" => array ( "area" => "North Region (Region 4)", "city" => "Korem"),
    "34550" => array ( "area" => "North Region (Region 4)", "city" => "Shiraro"),
    "34448" => array ( "area" => "North Region (Region 4)", "city" => "Humera"),
    "34447" => array ( "area" => "North Region (Region 4)", "city" => "Senkata"),
    "34446" => array ( "area" => "North Region (Region 4)", "city" => "Abi Adi"),
    "34445" => array ( "area" => "North Region (Region 4)", "city" => "Adigrat"),
    "34444" => array ( "area" => "North Region (Region 4)", "city" => "Shire Endasselassie"),
    "34443" => array ( "area" => "North Region (Region 4)", "city" => "Wukro"),
    "34442" => array ( "area" => "North Region (Region 4)", "city" => "Quiha"),
    "34441" => array ( "area" => "North Region (Region 4)", "city" => "Mekele II"),
    "34440" => array ( "area" => "North Region (Region 4)", "city" => "Mekele I"),
    "33667" => array ( "area" => "North-East Region (Region 3)", "city" => "Decheotto"),
    "33666" => array ( "area" => "North-East Region (Region 3)", "city" => "Semera"),
    "33664" => array ( "area" => "North-East Region (Region 3)", "city" => "Shoa Robit"),
    "33661" => array ( "area" => "North-East Region (Region 3)", "city" => "Epheson"),
    "33660" => array ( "area" => "North-East Region (Region 3)", "city" => "Majate"),
    "33556" => array ( "area" => "North-East Region (Region 3)", "city" => "Dupti"),
    "33555" => array ( "area" => "North-East Region (Region 3)", "city" => "Assayta"),
    "33554" => array ( "area" => "North-East Region (Region 3)", "city" => "Kemise"),
    "33553" => array ( "area" => "North-East Region (Region 3)", "city" => "Bati"),
    "33552" => array ( "area" => "North-East Region (Region 3)", "city" => "Harbu"),
    "33551" => array ( "area" => "North-East Region (Region 3)", "city" => "Kombolcha"),
    "33550" => array ( "area" => "North-East Region (Region 3)", "city" => "Logia"),
    "33444" => array ( "area" => "North-East Region (Region 3)", "city" => "Ansokia"),
    "33440" => array ( "area" => "North-East Region (Region 3)", "city" => "Sekota"),
    "33339" => array ( "area" => "North-East Region (Region 3)", "city" => "Manda"),
    "33338" => array ( "area" => "North-East Region (Region 3)", "city" => "Bure"),
    "33336" => array ( "area" => "North-East Region (Region 3)", "city" => "Lalibela"),
    "33334" => array ( "area" => "North-East Region (Region 3)", "city" => "Kobo"),
    "33333" => array ( "area" => "North-East Region (Region 3)", "city" => "Mersa"),
    "33331" => array ( "area" => "North-East Region (Region 3)", "city" => "Woldia"),
    "33330" => array ( "area" => "North-East Region (Region 3)", "city" => "Sirinka"),
    "33226" => array ( "area" => "North-East Region (Region 3)", "city" => "Jama"),
    "33225" => array ( "area" => "North-East Region (Region 3)", "city" => "Elidar"),
    "33224" => array ( "area" => "North-East Region (Region 3)", "city" => "Wuchale"),
    "33223" => array ( "area" => "North-East Region (Region 3)", "city" => "Mille"),
    "33222" => array ( "area" => "North-East Region (Region 3)", "city" => "Hayk"),
    "33221" => array ( "area" => "North-East Region (Region 3)", "city" => "Bistima"),
    "33220" => array ( "area" => "North-East Region (Region 3)", "city" => "Mekana Selam"),
    "33118" => array ( "area" => "North-East Region (Region 3)", "city" => "Senbete"),
    "33117" => array ( "area" => "North-East Region (Region 3)", "city" => "Tenta"),
    "33116" => array ( "area" => "North-East Region (Region 3)", "city" => "Wore-ilu"),
    "33114" => array ( "area" => "North-East Region (Region 3)", "city" => "Akesta"),
    "33113" => array ( "area" => "North-East Region (Region 3)", "city" => "Kobo Robit"),
    "33112" => array ( "area" => "North-East Region (Region 3)", "city" => "Dessie II"),
    "33111" => array ( "area" => "North-East Region (Region 3)", "city" => "Dessie I"),
    "33110" => array ( "area" => "North-East Region (Region 3)", "city" => "Kabe"),
    "25880" => array ( "area" => "Eastern Region (Region 5)", "city" => "Kelafo"),
    "25779" => array ( "area" => "Eastern Region (Region 5)", "city" => "Chinagson"),
    "25777" => array ( "area" => "Eastern Region (Region 5)", "city" => "Teferi Ber"),
    "25776" => array ( "area" => "Eastern Region (Region 5)", "city" => "Godie"),
    "25775" => array ( "area" => "Eastern Region (Region 5)", "city" => "Jigiga"),
    "25774" => array ( "area" => "Eastern Region (Region 5)", "city" => "Kabri Dehar"),
    "25772" => array ( "area" => "Eastern Region (Region 5)", "city" => "Gursum"),
    "25771" => array ( "area" => "Eastern Region (Region 5)", "city" => "Degahabur"),
    "25669" => array ( "area" => "Eastern Region (Region 5)", "city" => "Kebribeyah"),
    "25667" => array ( "area" => "Eastern Region (Region 5)", "city" => "Harar II"),
    "25666" => array ( "area" => "Eastern Region (Region 5)", "city" => "Harar I"),
    "25665" => array ( "area" => "Eastern Region (Region 5)", "city" => "Babile"),
    "25662" => array ( "area" => "Eastern Region (Region 5)", "city" => "Aweday"),
    "25661" => array ( "area" => "Eastern Region (Region 5)", "city" => "Alemaya"),
    "25554" => array ( "area" => "Eastern Region (Region 5)", "city" => "Assebot"),
    "25551" => array ( "area" => "Eastern Region (Region 5)", "city" => "Asebe Teferi"),
    "25447" => array ( "area" => "Eastern Region (Region 5)", "city" => "Hurso"),
    "25446" => array ( "area" => "Eastern Region (Region 5)", "city" => "Erer"),
    "25444" => array ( "area" => "Eastern Region (Region 5)", "city" => "Miesso"),
    "25441" => array ( "area" => "Eastern Region (Region 5)", "city" => "Hirna"),
    "25338" => array ( "area" => "Eastern Region (Region 5)", "city" => "Kombolocha"),
    "25337" => array ( "area" => "Eastern Region (Region 5)", "city" => "Kobo"),
    "25336" => array ( "area" => "Eastern Region (Region 5)", "city" => "Kersa"),
    "25335" => array ( "area" => "Eastern Region (Region 5)", "city" => "Chelenko"),
    "25334" => array ( "area" => "Eastern Region (Region 5)", "city" => "Grawa"),
    "25333" => array ( "area" => "Eastern Region (Region 5)", "city" => "Deder"),
    "25332" => array ( "area" => "Eastern Region (Region 5)", "city" => "Bedeno"),
    "25116" => array ( "area" => "Eastern Region (Region 5)", "city" => "Melka Jeldu"),
    "25115" => array ( "area" => "Eastern Region (Region 5)", "city" => "Artshek"),
    "25114" => array ( "area" => "Eastern Region (Region 5)", "city" => "Shinile"),
    "25112" => array ( "area" => "Eastern Region (Region 5)", "city" => "Dire Dawa II"),
    "25111" => array ( "area" => "Eastern Region (Region 5)", "city" => "DireDawa I"),
    "22668" => array ( "area" => "South-East Region (Region 2)", "city" => "Dolomena"),
    "22666" => array ( "area" => "South-East Region (Region 2)", "city" => "Dodolla"),
    "22665" => array ( "area" => "South-East Region (Region 2)", "city" => "Robe"),
    "22664" => array ( "area" => "South-East Region (Region 2)", "city" => "Ghinir"),
    "22663" => array ( "area" => "South-East Region (Region 2)", "city" => "Adaba"),
    "22662" => array ( "area" => "South-East Region (Region 2)", "city" => "Gessera"),
    "22661" => array ( "area" => "South-East Region (Region 2)", "city" => "Bale Goba"),
    "22447" => array ( "area" => "South-East Region (Region 2)", "city" => "Goro"),
    "22446" => array ( "area" => "South-East Region (Region 2)", "city" => "Gobesa"),
    "22444" => array ( "area" => "South-East Region (Region 2)", "city" => "Ticho"),
    "22441" => array ( "area" => "South-East Region (Region 2)", "city" => "Abomsa"),
    "22339" => array ( "area" => "South-East Region (Region 2)", "city" => "Diksis"),
    "22338" => array ( "area" => "South-East Region (Region 2)", "city" => "Sagure"),
    "22337" => array ( "area" => "South-East Region (Region 2)", "city" => "Kersa"),
    "22336" => array ( "area" => "South-East Region (Region 2)", "city" => "Assasa"),
    "22335" => array ( "area" => "South-East Region (Region 2)", "city" => "Iteya"),
    "22334" => array ( "area" => "South-East Region (Region 2)", "city" => "Huruta"),
    "22333" => array ( "area" => "South-East Region (Region 2)", "city" => "Dera"),
    "22332" => array ( "area" => "South-East Region (Region 2)", "city" => "Bokoji"),
    "22331" => array ( "area" => "South-East Region (Region 2)", "city" => "Asela"),
    "22330" => array ( "area" => "South-East Region (Region 2)", "city" => "Sire"),
    "22227" => array ( "area" => "South-East Region (Region 2)", "city" => "Agarfa"),
    "22226" => array ( "area" => "South-East Region (Region 2)", "city" => "Metehara"),
    "22225" => array ( "area" => "South-East Region (Region 2)", "city" => "Melkasa"),
    "22224" => array ( "area" => "South-East Region (Region 2)", "city" => "Awash"),
    "22223" => array ( "area" => "South-East Region (Region 2)", "city" => "Arerti"),
    "22221" => array ( "area" => "South-East Region (Region 2)", "city" => "Shoa"),
    "22220" => array ( "area" => "South-East Region (Region 2)", "city" => "Wonji"),
    "22118" => array ( "area" => "South-East Region (Region 2)", "city" => "Meki"),
    "22116" => array ( "area" => "South-East Region (Region 2)", "city" => "Modjo"),
    "22115" => array ( "area" => "South-East Region (Region 2)", "city" => "Alem Tena"),
    "22114" => array ( "area" => "South-East Region (Region 2)", "city" => "Melkawarer"),
    "22113" => array ( "area" => "South-East Region (Region 2)", "city" => "Wolenchiti"),
    "22112" => array ( "area" => "South-East Region (Region 2)", "city" => "Nazreth II"),
    "22111" => array ( "area" => "South-East Region (Region 2)", "city" => "Nazreth I"),
    "11685" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Mehal Meda"),
    "11681" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Debre Birehan"),
    "11680" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Debre Sina"),
    "11669" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Bole VI "),
    "11663" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Bole IV"),
    "11662" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Bole III"),
    "11661" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Bole II"),
    "11660" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Kotebe"),
    "11647" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Yeka Rss III"),
    "11646" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Yeka II"),
    "11645" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "YekaI"),
    "11629" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Gerji"),
    "11626" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Bole Michael"),
    "11618" => array ( "area" => "Addis Ababa (Region 1) Zone 6 East Addis Ababa Zone", "city" => "Bole I"),
    "11554" => array ( "area" => "Addis Ababa (Region 1) Zone 5 Central Addis Ababa Zone", "city" => "Filwha VII"),
    "11553" => array ( "area" => "Addis Ababa (Region 1) Zone 5 Central Addis Ababa Zone", "city" => "Filwha V"),
    "11552" => array ( "area" => "Addis Ababa (Region 1) Zone 5 Central Addis Ababa Zone", "city" => "Filwha VI"),
    "11551" => array ( "area" => "Addis Ababa (Region 1) Zone 5 Central Addis Ababa Zone", "city" => "Filwoha III"),
    "11550" => array ( "area" => "Addis Ababa (Region 1) Zone 5 Central Addis Ababa Zone", "city" => "Filwoha IV"),
    "11544" => array ( "area" => "Addis Ababa (Region 1) Zone 5 Central Addis Ababa Zone", "city" => "ECA"),
    "11517" => array ( "area" => "Addis Ababa (Region 1) Zone 5 Central Addis Ababa Zone", "city" => "Sheraton/DID"),
    "11515" => array ( "area" => "Addis Ababa (Region 1) Zone 5 Central Addis Ababa Zone", "city" => "Filwoha II"),
    "11468" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Keria V"),
    "11467" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Keira IV"),
    "11466" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Keria III"),
    "11465" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Keria II"),
    "11443" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Nifas Silk II"),
    "11442" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Nifas Silk I"),
    "11440" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Nifas Silk III"),
    "11439" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Kaliti"),
    "11434" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Akaki"),
    "11433" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Debre Zeit"),
    "11432" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Dukem"),
    "11419" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Hana Mariam"),
    "11416" => array ( "area" => "Addis Ababa (Region 1) Zone 4 South Addis Ababa Zone", "city" => "Keira I"),
    "11374" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Old Airport V"),
    "11373" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Old Airport IV"),
    "11372" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Old Airport III"),
    "11371" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Old Airport II"),
    "11349" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Keranyo"),
    "11348" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Jimmaber (Ayer Tena)"),
    "11341" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Ghion"),
    "11330" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Wolkite"),
    "11321" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Mekanisa"),
    "11320" => array ( "area" => "Addis Ababa (Region 1) Zone 3 South-West Addis Ababa Zone", "city" => "Old Airport I"),
    "11284" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Burayu"),
    "11283" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Addis Alem"),
    "11279" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Kolfe"),
    "11278" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Addis Ketema VI"),
    "11277" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Addis Ketema IV"),
    "11276" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Addis Ketema III"),
    "11275" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Addis Ketema II"),
    "11270" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Asko"),
    "11259" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Shegole"),
    "11258" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Ginchi"),
    "11238" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Jeldu"),
    "11237" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Holeta Gent"),
    "11236" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Hagere Hiwot"),
    "11213" => array ( "area" => "Addis Ababa (Region 1) Zone 2 West Addis Ababa Zone", "city" => "Addis Ketema I"),
    "11188" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Chancho"),
    "11187" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Goha Tsion"),
    "11158" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Arada VI"),
    "11157" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Arada V"),
    "11156" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Arada IV"),
    "11155" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Arada III"),
    "11135" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Fitche"),
    "11131" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Kuyu"),
    "11127" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Addisu Gebeya"),
    "11125" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Sidist Kilo Rss I"),
    "11124" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Sidist Kilo III"),
    "11123" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Sidist Kilo II"),
    "11122" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Sidist Kilo I"),
    "11114" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "French Legasion"),
    "11112" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Arada I"),
    "11111" => array ( "area" => "Addis Ababa (Region 1) Zone 1 North Addis Ababa Zone", "city" => "Arada I")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "251", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ethiopia", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+251 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for FMC network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "47119" => array ( "area" => "South-Western Region (Region 7)", "city" => "Jimma"),
    "46884" => array ( "area" => "South Region (Region 6)", "city" => "Arbaminch"),
    "46119" => array ( "area" => "South Region (Region 6)", "city" => "Shasemene"),
    "34559" => array ( "area" => "North Region (Region 4)", "city" => "Mekele"),
    "22119" => array ( "area" => "South-East Region (Region 2)", "city" => "Nazreth")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "251", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ethiopia", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+251 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for VSAT network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "98119" => "Faraway",
    "98111" => "Dialaway"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "251", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Ethiopia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => "0" . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+251 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Ethiopian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
