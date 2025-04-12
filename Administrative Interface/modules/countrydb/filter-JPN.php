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
 * related to country database of Japan.
 *
 * Reference: https://www.itu.int/oth/T020200006D/en (2014-06-24)
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
 * E.164 Japan country hook
 */
framework_add_filter ( "e164_identify_country_JPN", "e164_identify_country_JPN");

/**
 * E.164 Japan area number identification hook. This hook is an e164_identify sub
 * hook, called when the ISO3166 Alpha3 are "JPN" (code for Japan). This hook
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
function e164_identify_country_JPN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Japan
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+81")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "909",
    "908",
    "907",
    "906",
    "905",
    "904",
    "903",
    "902",
    "901",
    "809",
    "808",
    "807",
    "806",
    "805",
    "804",
    "803",
    "802",
    "801",
    "709",
    "709",
    "707",
    "706",
    "705",
    "704",
    "703",
    "702",
    "701"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "81", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Japan", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6), "International" => "+81 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for fixed line network with 1 to 4 digits NDC and 5 to 8 digits SN
   */
  $prefixes = array (
    "9969" => "Nakakoshiki",
    "9913" => "Iojima",
    "9912" => "Nakanoshima",
    "9802" => "Minamidaito",
    "9496" => "Nogata",
    "8514" => "Ama",
    "8512" => "Saigo",
    "8477" => "Tojo",
    "8396" => "Yamaguti",
    "8388" => "Tamagawa",
    "8387" => "Tamagawa",
    "7468" => "Kamikitayama",
    "5979" => "Kumano",
    "5769" => "Shoukawa",
    "4998" => "Ogasawara",
    "4996" => "Hachijojima",
    "4994" => "Miyake",
    "4992" => "Izu oshima",
    "1658" => "Kamikawa",
    "1656" => "Bifuka",
    "1655" => "Nayoro",
    "1654" => "Nayoro",
    "1648" => "Yagisiri",
    "1635" => "Hamatonbetsu",
    "1634" => "Hamatonbetsu",
    "1632" => "Teshio",
    "1587" => "Nakayubetsu",
    "1586" => "Nakayubetsu",
    "1564" => "Kamishihoro",
    "1558" => "Hiroo",
    "1547" => "Shiranuka",
    "1466" => "Erimo",
    "1457" => "Monbetsutomikawa",
    "1456" => "Monbetsutomikawa",
    "1398" => "Kumaishi",
    "1397" => "Okushiri",
    "1392" => "Kikonai",
    "1377" => "Yakumo",
    "1374" => "Mori",
    "1372" => "Shikabe",
    "1267" => "Iwamizawa",
    "997" => "Naze, Tokunoshima, Tanegashima, Yakushima and Setouchi",
    "996" => "Izumi and Sendai",
    "995" => "Kajiki and Okuchi",
    "994" => "Kanoya and Onejime",
    "993" => "Ibusuki and Kaseda",
    "987" => "Nichinan",
    "986" => "Miyakonojo",
    "985" => "Miyazaki",
    "984" => "Kobayashi",
    "983" => "Takanabe",
    "982" => "Nobeoka, Hyuga and Takachiho",
    "980" => "Nago, Okinawamiyako and Yaeyama",
    "979" => "Nakatsu",
    "978" => "Kunisaki, Bungotakada and Kitsuki",
    "977" => "Beppu",
    "974" => "Mie and Taketa",
    "973" => "Hita and Kusu",
    "972" => "Saeki and Usuki",
    "969" => "Amakusa",
    "968" => "Yamaga and Tamana",
    "967" => "Kumamotoichinomiya, Yabe and Takamori",
    "966" => "Hitoyoshi and Minamata",
    "965" => "Yatsushiro",
    "964" => "Matsubase",
    "959" => "Oseto, Fukue and Arikawa",
    "957" => "Isahaya and Shimabara",
    "956" => "Sasebo",
    "955" => "Karatsu and Imari",
    "954" => "Takeo and Kashima",
    "952" => "Saga",
    "950" => "Hirado",
    "949" => "Nogata",
    "948" => "Iizuka",
    "947" => "Tagawa",
    "946" => "Amagi",
    "944" => "Setaka",
    "943" => "Yame and Tanushimaru",
    "942" => "Kurume",
    "940" => "Munakata",
    "930" => "Yukuhashi",
    "920" => "Gonoura, Izuhara and Tsushimasaga",
    "898" => "Imabari",
    "897" => "Hakata and Nihama",
    "896" => "Iyomishima",
    "895" => "Uwajima and Misho",
    "894" => "Yawatahama and Uwa",
    "893" => "Ozu",
    "892" => "Kuma",
    "889" => "Sakawa and Susaki",
    "887" => "Reihoku, Muroto, Aki and Tosayamada",
    "885" => "Komatsushima",
    "884" => "Anan, Nyudani and Mugi",
    "883" => "Kamojima, Wakimachi and Awaikeda",
    "880" => "Tosanakamura, Sukumo, Kubokawa and Tosashimizu",
    "879" => "Sanbonmatsu and Tonosho",
    "877" => "Marugame",
    "875" => "Kanonji",
    "869" => "Bizen and Oku",
    "868" => "Tsuyama and Mimasaka",
    "867" => "Niimi, Kamogawa and Kuse",
    "866" => "Takahashi, Ibara and Soja",
    "865" => "Kamogata and Kasaoka",
    "863" => "Tamano",
    "859" => "Yonago and Neu",
    "858" => "Koge and Kurayoshi",
    "857" => "Tottori",
    "856" => "Masuta and Tsuwano",
    "855" => "Hamada, Gotsu and Kawamoto",
    "854" => "Yasugi, Kisuki, Kakeya and Iwamioda",
    "853" => "Izumo",
    "852" => "Matsue",
    "848" => "Onomichi",
    "847" => "Fuchu, Kozan and Tojo",
    "846" => "Takehara and Kinoe",
    "845" => "Innoshima",
    "838" => "Hagi",
    "837" => "Nagato and Mine",
    "836" => "Ube",
    "835" => "Hofu",
    "834" => "Tokuyama",
    "833" => "Kudamatsu",
    "829" => "Hatsukaichi",
    "827" => "Iwakuni",
    "826" => "Chiyoda, Akiyoshida and Kake",
    "824" => "Miyoshi and Shobara",
    "823" => "Kure",
    "820" => "Yanai and Kuka",
    "799" => "Sumoto and Tsuna",
    "798" => "Nishinomiya",
    "797" => "Nishinomiya",
    "796" => "Toyooka and Hamasaka",
    "795" => "Nishiwaki and Tanbakaibara",
    "794" => "Miki",
    "791" => "Tatsuno and Aioi",
    "790" => "Harimayamasaki and Fukusaki",
    "779" => "Ono",
    "778" => "Takefu",
    "776" => "Fukui",
    "774" => "Uji",
    "773" => "Fukuchiyama and Maizuru",
    "772" => "Miyazu and Mineyama",
    "771" => "Kameoka and Sonobe",
    "770" => "Tsuruga and Obama",
    "768" => "Wajima and Noto",
    "767" => "Nanao and Hakui",
    "766" => "Takaoka",
    "765" => "Uozu",
    "763" => "Fukuno",
    "761" => "Komatsu and Kaga",
    "749" => "Hikone and Nagahama",
    "748" => "Yokaichi and Minakuchi",
    "747" => "Gojo and Shimoichi",
    "746" => "Yoshino and Totsukawa",
    "745" => "Yamatotakada and Yamatohaibara",
    "744" => "Yamatotakada",
    "743" => "Nara",
    "742" => "Nara",
    "740" => "Imazu",
    "739" => "Tanabe",
    "738" => "Gobo",
    "737" => "Yuasa",
    "736" => "Iwade and Wakayamahashimoto",
    "735" => "Shingu and Kushimoto",
    "725" => "Izumi",
    "721" => "Tondabayashi",
    "599" => "Toba and Ago",
    "598" => "Matsuzaka and Misedani",
    "597" => "Owase and Kumano",
    "596" => "Ise",
    "595" => "Ueno and Kameyama",
    "594" => "Kuwana",
    "587" => "Ichinomiya",
    "586" => "Ichinomiya",
    "585" => "Ibigawa",
    "584" => "Ogaki",
    "581" => "Takatomi",
    "578" => "Kamioka",
    "577" => "Takayama",
    "576" => "Gero",
    "575" => "Seki and Gujoyahata",
    "574" => "Minokamo and Minoshirakawa",
    "573" => "Ena and Nakatsugawa",
    "572" => "Tajimi",
    "569" => "Handa",
    "568" => "Kasugai",
    "567" => "Tsushima",
    "566" => "Kariya",
    "565" => "Toyota",
    "564" => "Okazaki",
    "563" => "Nishio",
    "562" => "Owariyokosuka",
    "561" => "Seto",
    "558" => "Syuzenjiohito and Shimoda",
    "557" => "Ito",
    "556" => "Kajikazawaaoyagi and Minobu",
    "555" => "Yoshida",
    "554" => "Otsuki",
    "553" => "Yamanashi",
    "551" => "Nirasaki",
    "550" => "Gotenba",
    "548" => "Haibara",
    "547" => "Shimada",
    "545" => "Fuji",
    "544" => "Fujinomiya",
    "539" => "Tenryu",
    "538" => "Iwata",
    "537" => "Kakegawa",
    "536" => "Shinshiro and Shitara",
    "533" => "Toyohashi",
    "532" => "Toyohashi",
    "531" => "Tahara",
    "495" => "Honjo",
    "494" => "Chichibu",
    "493" => "Higashimatsuyama",
    "480" => "Kuki",
    "479" => "Choshi and Youkaichiba",
    "478" => "Sawara",
    "476" => "Narita",
    "475" => "Togane and Mobara",
    "470" => "Tateyama and Ohara",
    "467" => "Fujisawa",
    "466" => "Fujisawa",
    "465" => "Odawara",
    "463" => "Hiratsuka",
    "460" => "Odawara",
    "439" => "Kisarazu",
    "438" => "Kisarazu",
    "436" => "Ichikawa",
    "428" => "Ome",
    "422" => "Musashinomitaka",
    "299" => "Ishioka and Itako",
    "297" => "Ryugasaki and Mitsukaido",
    "296" => "Kasama and Shimodate",
    "295" => "Hitachiomiya and Daigo",
    "294" => "Hitachiota",
    "293" => "Takahagi",
    "291" => "Hokota",
    "289" => "Kanuma",
    "288" => "Imaichi",
    "287" => "Kuroiso, Otawara and Karasuyana",
    "285" => "Mooka and Oyama",
    "284" => "Ashikaga",
    "283" => "Sano",
    "282" => "Tochigi",
    "280" => "Furukawa",
    "279" => "Shibukawa and Naganohara",
    "278" => "Numata",
    "277" => "Kiryu",
    "276" => "Ota",
    "274" => "Tomioka and Fujioka",
    "270" => "Isesaki",
    "269" => "Nakano and Iiyama",
    "268" => "Ueda",
    "267" => "Komoro and Saku",
    "266" => "Suwa",
    "265" => "Ina and Iida",
    "264" => "Kisofukushima",
    "263" => "Matsumoto",
    "261" => "Omachi",
    "260" => "Anancho",
    "259" => "Sado",
    "258" => "Nagaoka",
    "257" => "Kashiwazaki",
    "256" => "Maki and Sanjo",
    "255" => "Arai",
    "254" => "Shibata, Murakami and Tsugawa",
    "250" => "Niitsu",
    "248" => "Sukagawa and Shirakawa",
    "247" => "Ishikawa and Miharu",
    "246" => "Iwaki",
    "244" => "Haramachi",
    "243" => "Nihonmatsu",
    "242" => "Aizuwakamatsu",
    "241" => "Kitakata, Tajima, Aizuyamaguchi and Yanaizu",
    "240" => "Iwakitomioka",
    "238" => "Yonezawa and Nagai",
    "237" => "Sagae and Murayama",
    "235" => "Tsuruoka",
    "234" => "Sakata",
    "233" => "Shinjo",
    "229" => "Furukawa",
    "228" => "Tsukidate",
    "226" => "Kesennuma",
    "225" => "Ishinomaki",
    "224" => "Ogawara and Shiroishi",
    "223" => "Iwanuma",
    "220" => "Hasama",
    "198" => "Hanamaki and Tono",
    "197" => "Mizusawa and Kitakami",
    "195" => "Iwate and Ninohe",
    "194" => "Kuji and Iwaizumi",
    "193" => "Miyako and Kamaishi",
    "192" => "Ofunato",
    "191" => "Ichinoseki",
    "187" => "Omagari and Kakunodate",
    "186" => "Kazuno, Odate and Takanosu",
    "185" => "Oga and Noshiro",
    "184" => "Honjo",
    "183" => "Yuzawa",
    "182" => "Yokote",
    "179" => "Sannohe",
    "178" => "Hachinohe",
    "176" => "Towada",
    "175" => "Mutsu and Noheji",
    "174" => "Kanita",
    "173" => "Goshogawara and Ajigasawa",
    "172" => "Hirosaki",
    "167" => "Furano",
    "166" => "Asahikawa",
    "165" => "Shibetsu",
    "164" => "Rumoi, Ishikarifukagawa and Haboro",
    "163" => "Kitamiesashi and Rishirirebun",
    "162" => "Wakkanai",
    "158" => "Monbetsu, Engaru and Okoppe",
    "157" => "Kitami",
    "156" => "Honbetsu and Tokachishimizu",
    "155" => "Obihiro",
    "154" => "Kushiro",
    "153" => "Akkeshi, Nemuro, Nakashibetsu and Nemuroshibetsu",
    "152" => "Abashiri, Shari and Bihoro",
    "146" => "Urakawa and Shizunai",
    "145" => "Hayakita and Mukawa",
    "144" => "Tomakomai",
    "143" => "Muroran",
    "142" => "Date",
    "139" => "Matsumae and Esashi",
    "138" => "Hakodate",
    "137" => "Yakumo and Imakane",
    "136" => "Kutchan and Suttsu",
    "135" => "Yoichi and Iwanai",
    "134" => "Otaru",
    "133" => "Ishikari and Tobetsu",
    "126" => "Iwamizawa",
    "125" => "Takigawa",
    "124" => "Ashibetsu",
    "123" => "Chitose,, Yubari and Kuriyama",
    "99" => "Kagoshima and Shibushi",
    "98" => "Naha",
    "97" => "Oita",
    "96" => "Kumamoto",
    "95" => "Nagasaki",
    "93" => "Kitakyushu",
    "92" => "Maebaru and Fukuoka",
    "89" => "Matsuyama",
    "88" => "Tokushima and Kochi",
    "87" => "Takamatsu",
    "86" => "Akaiwa, Kurashiki and Okayama",
    "84" => "Onomichi and Fukuyama",
    "83" => "Shimonoseki and Yamaguchi",
    "82" => "Hiroshima and Higashihiroshima",
    "79" => "Himeji, Kakogawa, Mita and Yoka",
    "78" => "Kobe",
    "77" => "Otsu",
    "76" => "Kanazawa and Toyama",
    "75" => "Kyoto",
    "73" => "Wakayama",
    "72" => "Neyagawa, Sakai, Kishiwadakaizuka, Ibaraki, Ikeda and Yao",
    "59" => "Tsu and Yokkaichi",
    "58" => "Gifu",
    "55" => "Kofu and Numazu",
    "54" => "Shizuoka",
    "53" => "Hamamatsu",
    "52" => "Nagoya",
    "49" => "Kawagoe",
    "48" => "Urawa, Kawaguchi, Kumagaya and Soka",
    "47" => "Ichikawa and Funabashi",
    "46" => "Atsugi and Yokosuka",
    "45" => "Yokohama",
    "44" => "Kawasaki",
    "43" => "Chiba",
    "42" => "Kokubunji, Musashinomitaka, Tachikawa, Hachioji, Sagamihara and Hanno",
    "29" => "Mito and Tsuchiura",
    "28" => "Utsunomiya",
    "27" => "Maebashi and Takasaki",
    "26" => "Nagano",
    "25" => "Niigata, joetsu, Itoigawa, Yasuzuka, Tokamachi, Muikamachi and Koide",
    "24" => "Fukushima and Koriyama",
    "23" => "Yamagata",
    "22" => "Sendai",
    "19" => "Morioka",
    "18" => "Akita",
    "17" => "Aomori",
    "15" => "Teshikaga and Tokachiikeda",
    "11" => "Sapporo",
    "6" => "Osaka",
    "4" => "Tokorozawa, Kashiwa and Kamogawa",
    "3" => "Tokyo"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 12)
    {
      switch ( strlen ( $prefix))
      {
        case 1:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+81 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8));
          break;
        case 2:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+81 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8));
          break;
        case 3:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 1) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8), "International" => "+81 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6, 2) . " " . substr ( $parameters["Number"], 8));
          break;
        case 4:
          $callformats = array ( "Local" => substr ( $parameters["Number"], 3, 2) . "-" . substr ( $parameters["Number"], 5, 2) . " " . substr ( $parameters["Number"], 7, 1) . " " . substr ( $parameters["Number"], 8), "International" => "+81 " . substr ( $parameters["Number"], 3, 4) . " " . substr ( $parameters["Number"], 7, 1) . " " . substr ( $parameters["Number"], 8));
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "81", "NDC" => (string) $prefix, "Country" => "Japan", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * Check for FMC network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "609",
    "608",
    "607",
    "606",
    "605",
    "604",
    "603",
    "602",
    "601"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "81", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Japan", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_FMC, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6), "International" => "+81 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for VoIP network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "509",
    "508",
    "507",
    "506",
    "505",
    "504",
    "503",
    "502",
    "501"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "81", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Japan", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6), "International" => "+81 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for Paging network with 3 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "209",
    "208",
    "207",
    "206",
    "205",
    "204",
    "203",
    "202",
    "201"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "81", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Japan", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PAGING, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6), "International" => "+81 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6))));
    }
  }

  /**
   * Check for PRN network with 3 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 3, 3) == "990" && strlen ( $parameters["Number"]) == 12)
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "81", "NDC" => substr ( $parameters["Number"], 3, 3), "Country" => "Japan", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6), "International" => "+81 " . substr ( $parameters["Number"], 3, 3) . " " . substr ( $parameters["Number"], 6))));
  }

  /**
   * If reached here, number wasn't identified as a valid Japan phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
