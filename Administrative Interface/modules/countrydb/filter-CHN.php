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
 * related to country database of China.
 *
 * Reference: https://www.itu.int/oth/T020200002B/en (2018-10-25)
 * Reference: https://en.wikipedia.org/wiki/Telephone_numbers_in_China
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
 * E.164 China country hook
 */
framework_add_filter ( "e164_identify_country_CHN", "e164_identify_country_CHN");

/**
 * E.164 Chinesian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "CHN" (code for China). This hook
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
function e164_identify_country_CHN ( $buffer, $parameters)
{
  /**
   * Check if number country code is from China
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+86")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 9 digits SN
   */
  $prefixes = array (
    "1709" => "China United Network Communications Group Co., Ltd.",
    "1708" => "China United Network Communications Group Co., Ltd.",
    "1707" => "China United Network Communications Group Co., Ltd.",
    "1706" => "China Mobile Communications Co.",
    "1705" => "China Mobile Communications Co.",
    "1704" => "China United Network Communications Group Co., Ltd.",
    "1703" => "China Mobile Communications Co.",
    "1702" => "China Telecom Co., Ltd.",
    "1701" => "China Telecom Co., Ltd.",
    "1700" => "China Telecom Co., Ltd.",
    "1440" => "China Mobile Communications Co.",
    "1348" => "China Mobile Communications Co.",
    "1347" => "China Mobile Communications Co.",
    "1346" => "China Mobile Communications Co.",
    "1345" => "China Mobile Communications Co.",
    "1344" => "China Mobile Communications Co.",
    "1343" => "China Mobile Communications Co.",
    "1342" => "China Mobile Communications Co.",
    "1341" => "China Mobile Communications Co.",
    "1340" => "China Mobile Communications Co.",
    "199" => "China Telecom Co., Ltd.",
    "198" => "China Mobile Communications Co.",
    "191" => "China Telecom Co., Ltd.",
    "189" => "China Telecom Co., Ltd.",
    "188" => "China Mobile Communications Co.",
    "187" => "China Mobile Communications Co.",
    "186" => "China United Network Communications Group Co., Ltd.",
    "185" => "China United Network Communications Group Co., Ltd.",
    "184" => "China Mobile Communications Co.",
    "183" => "China Mobile Communications Co.",
    "182" => "China Mobile Communications Co.",
    "181" => "China Telecom Co., Ltd.",
    "180" => "China Telecom Co., Ltd.",
    "177" => "China Telecom Co., Ltd.",
    "176" => "China United Network Communications Group Co., Ltd.",
    "175" => "China United Network Communications Group Co., Ltd.",
    "173" => "China Telecom Co., Ltd.",
    "171" => "China United Network Communications Group Co., Ltd.",
    "167" => "China United Network Communications Group Co., Ltd.",
    "166" => "China United Network Communications Group Co., Ltd.",
    "162" => "China Telecom Co., Ltd.",
    "159" => "China Mobile Communications Co.",
    "158" => "China Mobile Communications Co.",
    "157" => "China Mobile Communications Co.",
    "156" => "China United Network Communications Group Co., Ltd.",
    "155" => "China United Network Communications Group Co., Ltd.",
    "153" => "China Telecom Co., Ltd.",
    "152" => "China Mobile Communications Co.",
    "151" => "China Mobile Communications Co.",
    "150" => "China Mobile Communications Co.",
    "148" => "China Mobile Communications Co.",
    "147" => "China Mobile Communications Co.",
    "146" => "China United Network Communications Group Co., Ltd.",
    "145" => "China United Network Communications Group Co., Ltd.",
    "139" => "China Mobile Communications Co.",
    "138" => "China Mobile Communications Co.",
    "137" => "China Mobile Communications Co.",
    "136" => "China Mobile Communications Co.",
    "135" => "China Mobile Communications Co.",
    "133" => "China Telecom Co., Ltd.",
    "132" => "China United Network Communications Group Co., Ltd.",
    "131" => "China United Network Communications Group Co., Ltd.",
    "130" => "China United Network Communications Group Co., Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 14)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "86", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "China", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+86 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 8 digits SN
   */
  $prefixes = array (
    "999" => array ( "area" => "Xinjiang", "city" => "Yining"),
    "998" => array ( "area" => "Xinjiang", "city" => "Kashgar"),
    "997" => array ( "area" => "Xinjiang", "city" => "Aksu"),
    "996" => array ( "area" => "Xinjiang", "city" => "Korla"),
    "995" => array ( "area" => "Xinjiang", "city" => "Turpan"),
    "994" => array ( "area" => "Xinjiang", "city" => "Changji"),
    "993" => array ( "area" => "Xinjiang", "city" => "Shihezi"),
    "992" => array ( "area" => "Xinjiang", "city" => "Kuitun"),
    "991" => array ( "area" => "Xinjiang", "city" => "Ürümqi"),
    "990" => array ( "area" => "Xinjiang", "city" => "Karamay"),
    "979" => array ( "area" => "Qinghai", "city" => "Golmud"),
    "978" => array ( "area" => "Qinghai", "city" => "Menyuan"),
    "977" => array ( "area" => "Qinghai", "city" => "Haixi (Delingha)"),
    "976" => array ( "area" => "Qinghai", "city" => "Yushu"),
    "975" => array ( "area" => "Qinghai", "city" => "Golog"),
    "974" => array ( "area" => "Qinghai", "city" => "Gonghe"),
    "973" => array ( "area" => "Qinghai", "city" => "Tongren"),
    "972" => array ( "area" => "Qinghai", "city" => "Haidong"),
    "971" => array ( "area" => "Qinghai", "city" => "Xining"),
    "970" => array ( "area" => "Qinghai", "city" => "Haiyan"),
    "955" => array ( "area" => "Ningxia", "city" => "Zhongwei"),
    "954" => array ( "area" => "Ningxia", "city" => "Guyuan"),
    "953" => array ( "area" => "Ningxia", "city" => "Wuzhong"),
    "952" => array ( "area" => "Ningxia", "city" => "Shizuishan"),
    "951" => array ( "area" => "Ningxia", "city" => "Yinchuan"),
    "943" => array ( "area" => "Gansu", "city" => "Baiyin"),
    "941" => array ( "area" => "Gansu", "city" => "Gannan"),
    "939" => array ( "area" => "Gansu", "city" => "Longnan"),
    "938" => array ( "area" => "Gansu", "city" => "Tianshui"),
    "937" => array ( "area" => "Gansu", "city" => "Jiuquan"),
    "936" => array ( "area" => "Gansu", "city" => "Zhangye"),
    "935" => array ( "area" => "Gansu", "city" => "Wuwei"),
    "934" => array ( "area" => "Gansu", "city" => "Xifeng"),
    "933" => array ( "area" => "Gansu", "city" => "Pingliang"),
    "932" => array ( "area" => "Gansu", "city" => "Dingxi"),
    "931" => array ( "area" => "Gansu", "city" => "Lanzhou"),
    "930" => array ( "area" => "Gansu", "city" => "Linxia"),
    "919" => array ( "area" => "Shaanxi", "city" => "Tongchuan"),
    "917" => array ( "area" => "Shaanxi", "city" => "Baoji"),
    "916" => array ( "area" => "Shaanxi", "city" => "Hanzhong"),
    "915" => array ( "area" => "Shaanxi", "city" => "Ankang"),
    "914" => array ( "area" => "Shaanxi", "city" => "Shangzhou"),
    "913" => array ( "area" => "Shaanxi", "city" => "Weinan"),
    "912" => array ( "area" => "Shaanxi", "city" => "Yulin"),
    "911" => array ( "area" => "Shaanxi", "city" => "Yan'an"),
    "909" => array ( "area" => "Xinjiang", "city" => "Bole"),
    "908" => array ( "area" => "Xinjiang", "city" => "Artux"),
    "906" => array ( "area" => "Xinjiang", "city" => "Altay"),
    "903" => array ( "area" => "Xinjiang", "city" => "Hotan"),
    "902" => array ( "area" => "Xinjiang", "city" => "Kumul"),
    "901" => array ( "area" => "Xinjiang", "city" => "Tacheng"),
    "898" => array ( "area" => "Hainan", "city" => ""),
    "891" => array ( "area" => "Tibet and Xizang", "city" => "Ngari"),
    "888" => array ( "area" => "Yunnan", "city" => "Lijiang"),
    "887" => array ( "area" => "Yunnan", "city" => "Zhongdian"),
    "886" => array ( "area" => "Yunnan", "city" => "Nujiang"),
    "883" => array ( "area" => "Yunnan", "city" => "Lincang"),
    "881" => array ( "area" => "Yunnan", "city" => "Dongchuan"),
    "879" => array ( "area" => "Yunnan", "city" => "Simao"),
    "878" => array ( "area" => "Yunnan", "city" => "Chuxiong"),
    "877" => array ( "area" => "Yunnan", "city" => "Yuxi"),
    "876" => array ( "area" => "Yunnan", "city" => "Wenshan"),
    "875" => array ( "area" => "Yunnan", "city" => "Baoshan"),
    "874" => array ( "area" => "Yunnan", "city" => "Qujing"),
    "873" => array ( "area" => "Yunnan", "city" => "Gejiu"),
    "872" => array ( "area" => "Yunnan", "city" => "Dali"),
    "871" => array ( "area" => "Yunnan", "city" => "Kunming"),
    "870" => array ( "area" => "Yunnan", "city" => "Zhaotong"),
    "859" => array ( "area" => "Guizhou", "city" => "Xingyi"),
    "858" => array ( "area" => "Guizhou", "city" => "Liupanshui"),
    "857" => array ( "area" => "Guizhou", "city" => "Bijie"),
    "856" => array ( "area" => "Guizhou", "city" => "Tongren"),
    "855" => array ( "area" => "Guizhou", "city" => "Kaili"),
    "854" => array ( "area" => "Guizhou", "city" => "Duyun"),
    "853" => array ( "area" => "Guizhou", "city" => "Anshun"),
    "852" => array ( "area" => "Guizhou", "city" => "Zunyi"),
    "851" => array ( "area" => "Guizhou", "city" => "Guiyang"),
    "839" => array ( "area" => "Sichuan", "city" => "Guangyuan"),
    "838" => array ( "area" => "Sichuan", "city" => "Deyang"),
    "837" => array ( "area" => "Sichuan", "city" => "Ngawa"),
    "836" => array ( "area" => "Sichuan", "city" => "Kangding"),
    "835" => array ( "area" => "Sichuan", "city" => "Ya'an"),
    "834" => array ( "area" => "Sichuan", "city" => "Xichang"),
    "833" => array ( "area" => "Sichuan", "city" => "Leshan"),
    "832" => array ( "area" => "Sichuan", "city" => "Neijiang"),
    "831" => array ( "area" => "Sichuan", "city" => "Yibin"),
    "830" => array ( "area" => "Sichuan", "city" => "Luzhou"),
    "827" => array ( "area" => "Sichuan", "city" => "Bazhong"),
    "826" => array ( "area" => "Sichuan", "city" => "Guang'an"),
    "825" => array ( "area" => "Sichuan", "city" => "Suining"),
    "818" => array ( "area" => "Sichuan", "city" => "Dazhou"),
    "817" => array ( "area" => "Sichuan", "city" => "Nanchong"),
    "816" => array ( "area" => "Sichuan", "city" => "Mianyang"),
    "813" => array ( "area" => "Sichuan", "city" => "Zigong"),
    "812" => array ( "area" => "Sichuan", "city" => "Panzhihua"),
    "799" => array ( "area" => "Jiangxi", "city" => "Pingxiang"),
    "798" => array ( "area" => "Jiangxi", "city" => "Jingdezhen"),
    "797" => array ( "area" => "Jiangxi", "city" => "Ganzhou"),
    "796" => array ( "area" => "Jiangxi", "city" => "Ji'an"),
    "795" => array ( "area" => "Jiangxi", "city" => "Yichun"),
    "794" => array ( "area" => "Jiangxi", "city" => "Fuzhou and Jiangxi"),
    "793" => array ( "area" => "Jiangxi", "city" => "Shangrao"),
    "792" => array ( "area" => "Jiangxi", "city" => "Jiujiang"),
    "791" => array ( "area" => "Jiangxi", "city" => "Nanchang"),
    "790" => array ( "area" => "Jiangxi", "city" => "Xinyu"),
    "779" => array ( "area" => "Guangxi", "city" => "Beihai"),
    "778" => array ( "area" => "Guangxi", "city" => "Hechi"),
    "777" => array ( "area" => "Guangxi", "city" => "Qinzhou"),
    "776" => array ( "area" => "Guangxi", "city" => "Baise"),
    "775" => array ( "area" => "Guangxi", "city" => "Yulin"),
    "774" => array ( "area" => "Guangxi", "city" => "Wuzhou"),
    "773" => array ( "area" => "Guangxi", "city" => "Guilin"),
    "772" => array ( "area" => "Guangxi", "city" => "Liuzhou"),
    "771" => array ( "area" => "Guangxi", "city" => "Nanning"),
    "770" => array ( "area" => "Guangxi", "city" => "Fangchenggang"),
    "769" => array ( "area" => "Guangdong", "city" => "Dongguan"),
    "768" => array ( "area" => "Guangdong", "city" => "Chaozhou"),
    "766" => array ( "area" => "Guangdong", "city" => "Yunfu"),
    "763" => array ( "area" => "Guangdong", "city" => "Qingyuan"),
    "762" => array ( "area" => "Guangdong", "city" => "Heyuan"),
    "760" => array ( "area" => "Guangdong", "city" => "Zhongshan"),
    "759" => array ( "area" => "Guangdong", "city" => "Zhanjiang"),
    "758" => array ( "area" => "Guangdong", "city" => "Zhaoqing"),
    "757" => array ( "area" => "Guangdong", "city" => "Foshan"),
    "756" => array ( "area" => "Guangdong", "city" => "Zhuhai"),
    "755" => array ( "area" => "Guangdong", "city" => "Shenzhen"),
    "754" => array ( "area" => "Guangdong", "city" => "Shantou"),
    "753" => array ( "area" => "Guangdong", "city" => "Meizhou"),
    "752" => array ( "area" => "Guangdong", "city" => "Huizhou"),
    "751" => array ( "area" => "Guangdong", "city" => "Shaoguan"),
    "750" => array ( "area" => "Guangdong", "city" => "Jiangmen"),
    "746" => array ( "area" => "Hunan", "city" => "Yongzhou"),
    "745" => array ( "area" => "Hunan", "city" => "Huaihua"),
    "744" => array ( "area" => "Hunan", "city" => "Zhangjiajie"),
    "743" => array ( "area" => "Hunan", "city" => "Jishou"),
    "739" => array ( "area" => "Hunan", "city" => "Shaoyang"),
    "738" => array ( "area" => "Hunan", "city" => "Loudi"),
    "737" => array ( "area" => "Hunan", "city" => "Yiyang"),
    "736" => array ( "area" => "Hunan", "city" => "Changde"),
    "735" => array ( "area" => "Hunan", "city" => "Chenzhou"),
    "734" => array ( "area" => "Hunan", "city" => "Hengyang"),
    "733" => array ( "area" => "Hunan", "city" => "Zhuzhou"),
    "732" => array ( "area" => "Hunan", "city" => "Xiangtan"),
    "731" => array ( "area" => "Hunan", "city" => "Changsha"),
    "730" => array ( "area" => "Hunan", "city" => "Yueyang"),
    "728" => array ( "area" => "Hubei", "city" => "Xiantao, Tianmen and Qianjiang"),
    "724" => array ( "area" => "Hubei", "city" => "Jingmen"),
    "722" => array ( "area" => "Hubei", "city" => "Suizhou"),
    "719" => array ( "area" => "Hubei", "city" => "Shiyan"),
    "718" => array ( "area" => "Hubei", "city" => "Enshi"),
    "717" => array ( "area" => "Hubei", "city" => "Yichang"),
    "716" => array ( "area" => "Hubei", "city" => "Jingzhou"),
    "715" => array ( "area" => "Hubei", "city" => "Xianning"),
    "714" => array ( "area" => "Hubei", "city" => "Huangshi"),
    "713" => array ( "area" => "Hubei", "city" => "Huanggang"),
    "712" => array ( "area" => "Hubei", "city" => "Xiaogan"),
    "711" => array ( "area" => "Hubei", "city" => "Ezhou"),
    "710" => array ( "area" => "Hubei", "city" => "Xiangfan"),
    "701" => array ( "area" => "Jiangxi", "city" => "Yingtan"),
    "692" => array ( "area" => "Yunnan", "city" => "Dehong"),
    "691" => array ( "area" => "Yunnan", "city" => "Jinghong"),
    "668" => array ( "area" => "Guangdong", "city" => "Maoming"),
    "663" => array ( "area" => "Guangdong", "city" => "Jieyang"),
    "662" => array ( "area" => "Guangdong", "city" => "Yangjiang"),
    "660" => array ( "area" => "Guangdong", "city" => "Shanwei"),
    "635" => array ( "area" => "Shandong", "city" => "Liaocheng"),
    "634" => array ( "area" => "Shandong", "city" => "Laiwu"),
    "633" => array ( "area" => "Shandong", "city" => "Rizhao"),
    "632" => array ( "area" => "Shandong", "city" => "Zaozhuang"),
    "631" => array ( "area" => "Shandong", "city" => "Weihai"),
    "599" => array ( "area" => "Fujian", "city" => "Nanping"),
    "598" => array ( "area" => "Fujian", "city" => "Sanming"),
    "597" => array ( "area" => "Fujian", "city" => "Longyan"),
    "596" => array ( "area" => "Fujian", "city" => "Zhangzhou"),
    "595" => array ( "area" => "Fujian", "city" => "Quanzhou"),
    "594" => array ( "area" => "Fujian", "city" => "Putian"),
    "593" => array ( "area" => "Fujian", "city" => "Ningde"),
    "592" => array ( "area" => "Fujian", "city" => "Xiamen"),
    "591" => array ( "area" => "Fujian", "city" => "Fuzhou"),
    "580" => array ( "area" => "Zhejiang", "city" => "Zhoushan"),
    "579" => array ( "area" => "Zhejiang", "city" => "Jinhua"),
    "578" => array ( "area" => "Zhejiang", "city" => "Lishui"),
    "577" => array ( "area" => "Zhejiang", "city" => "Wenzhou"),
    "576" => array ( "area" => "Zhejiang", "city" => "Taizhou"),
    "575" => array ( "area" => "Zhejiang", "city" => "Shaoxing"),
    "574" => array ( "area" => "Zhejiang", "city" => "Ningbo"),
    "573" => array ( "area" => "Zhejiang", "city" => "Jiaxing"),
    "572" => array ( "area" => "Zhejiang", "city" => "Huzhou"),
    "571" => array ( "area" => "Zhejiang", "city" => "Hangzhou"),
    "570" => array ( "area" => "Zhejiang", "city" => "Quzhou"),
    "566" => array ( "area" => "Anhui", "city" => "Chizhou"),
    "564" => array ( "area" => "Anhui", "city" => "Lu'an"),
    "563" => array ( "area" => "Anhui", "city" => "Xuancheng"),
    "562" => array ( "area" => "Anhui", "city" => "Tongling"),
    "561" => array ( "area" => "Anhui", "city" => "Huaibei"),
    "559" => array ( "area" => "Anhui", "city" => "Huangshan"),
    "558" => array ( "area" => "Anhui", "city" => "Fuyang and Bozhou"),
    "557" => array ( "area" => "Anhui", "city" => "Suzhou"),
    "556" => array ( "area" => "Anhui", "city" => "Anqing"),
    "555" => array ( "area" => "Anhui", "city" => "Ma'anshan"),
    "554" => array ( "area" => "Anhui", "city" => "Huainan"),
    "553" => array ( "area" => "Anhui", "city" => "Wuhu"),
    "552" => array ( "area" => "Anhui", "city" => "Bengbu"),
    "551" => array ( "area" => "Anhui", "city" => "Hefei"),
    "550" => array ( "area" => "Anhui", "city" => "Chuzhou"),
    "546" => array ( "area" => "Shandong", "city" => "Dongying"),
    "543" => array ( "area" => "Shandong", "city" => "Binzhou"),
    "539" => array ( "area" => "Shandong", "city" => "Linyi"),
    "538" => array ( "area" => "Shandong", "city" => "Tai'an"),
    "537" => array ( "area" => "Shandong", "city" => "Jining"),
    "536" => array ( "area" => "Shandong", "city" => "Weifang"),
    "535" => array ( "area" => "Shandong", "city" => "Yantai"),
    "534" => array ( "area" => "Shandong", "city" => "Dezhou"),
    "533" => array ( "area" => "Shandong", "city" => "Zibo"),
    "532" => array ( "area" => "Shandong", "city" => "Qingdao"),
    "531" => array ( "area" => "Shandong", "city" => "Jinan"),
    "530" => array ( "area" => "Shandong", "city" => "Heze"),
    "527" => array ( "area" => "Jiangsu", "city" => "Suqian"),
    "523" => array ( "area" => "Jiangsu", "city" => "Taizhou"),
    "519" => array ( "area" => "Jiangsu", "city" => "Changzhou"),
    "518" => array ( "area" => "Jiangsu", "city" => "Lianyungang"),
    "517" => array ( "area" => "Jiangsu", "city" => "Huai'an"),
    "516" => array ( "area" => "Jiangsu", "city" => "Xuzhou"),
    "515" => array ( "area" => "Jiangsu", "city" => "Yancheng"),
    "514" => array ( "area" => "Jiangsu", "city" => "Yangzhou"),
    "513" => array ( "area" => "Jiangsu", "city" => "Nantong"),
    "512" => array ( "area" => "Jiangsu", "city" => "Suzhou"),
    "511" => array ( "area" => "Jiangsu", "city" => "Zhenjiang"),
    "510" => array ( "area" => "Jiangsu", "city" => "Wuxi"),
    "483" => array ( "area" => "Inner Mongolia", "city" => "Alxa"),
    "482" => array ( "area" => "Inner Mongolia", "city" => "Ulanhot"),
    "479" => array ( "area" => "Inner Mongolia", "city" => "Xilinhot"),
    "478" => array ( "area" => "Inner Mongolia", "city" => "Linhe"),
    "477" => array ( "area" => "Inner Mongolia", "city" => "Dongsheng"),
    "476" => array ( "area" => "Inner Mongolia", "city" => "Chifeng"),
    "475" => array ( "area" => "Inner Mongolia", "city" => "Tongliao"),
    "474" => array ( "area" => "Inner Mongolia", "city" => "Jining"),
    "473" => array ( "area" => "Inner Mongolia", "city" => "Wuhai"),
    "472" => array ( "area" => "Inner Mongolia", "city" => "Baotou"),
    "471" => array ( "area" => "Inner Mongolia", "city" => "Hohhot"),
    "470" => array ( "area" => "Inner Mongolia", "city" => "Hulunbuir"),
    "469" => array ( "area" => "Heilongjiang", "city" => "Shuangyashan"),
    "468" => array ( "area" => "Heilongjiang", "city" => "Hegang"),
    "467" => array ( "area" => "Heilongjiang", "city" => "Jixi"),
    "464" => array ( "area" => "Heilongjiang", "city" => "Qitaihe"),
    "459" => array ( "area" => "Heilongjiang", "city" => "Daqing"),
    "458" => array ( "area" => "Heilongjiang", "city" => "Yichun"),
    "457" => array ( "area" => "Heilongjiang", "city" => "Daxing'anling"),
    "456" => array ( "area" => "Heilongjiang", "city" => "Heihe"),
    "455" => array ( "area" => "Heilongjiang", "city" => "Suihua"),
    "454" => array ( "area" => "Heilongjiang", "city" => "Jiamusi"),
    "453" => array ( "area" => "Heilongjiang", "city" => "Mudanjiang"),
    "452" => array ( "area" => "Heilongjiang", "city" => "Qiqihar"),
    "451" => array ( "area" => "Heilongjiang", "city" => "Harbin"),
    "448" => array ( "area" => "Jilin", "city" => "Meihekou"),
    "440" => array ( "area" => "Jilin", "city" => "Hunchun"),
    "439" => array ( "area" => "Jilin", "city" => "Baishan"),
    "438" => array ( "area" => "Jilin", "city" => "Songyuan"),
    "437" => array ( "area" => "Jilin", "city" => "Liaoyuan"),
    "436" => array ( "area" => "Jilin", "city" => "Baicheng"),
    "435" => array ( "area" => "Jilin", "city" => "Tonghua"),
    "434" => array ( "area" => "Jilin", "city" => "Siping"),
    "433" => array ( "area" => "Jilin", "city" => "Yanji"),
    "432" => array ( "area" => "Jilin", "city" => "Jilin"),
    "431" => array ( "area" => "Jilin", "city" => "Changchun"),
    "429" => array ( "area" => "Liaoning", "city" => "Huludao"),
    "427" => array ( "area" => "Liaoning", "city" => "Panjin"),
    "421" => array ( "area" => "Liaoning", "city" => "Chaoyang"),
    "419" => array ( "area" => "Liaoning", "city" => "Liaoyang"),
    "418" => array ( "area" => "Liaoning", "city" => "Fuxin"),
    "417" => array ( "area" => "Liaoning", "city" => "Yingkou"),
    "416" => array ( "area" => "Liaoning", "city" => "Jinzhou"),
    "415" => array ( "area" => "Liaoning", "city" => "Dandong"),
    "412" => array ( "area" => "Liaoning", "city" => "Anshan"),
    "411" => array ( "area" => "Liaoning", "city" => "Dalian"),
    "398" => array ( "area" => "Henan", "city" => "Sanmenxia"),
    "396" => array ( "area" => "Henan", "city" => "Zhumadian"),
    "395" => array ( "area" => "Henan", "city" => "Luohe"),
    "394" => array ( "area" => "Henan", "city" => "Zhoukou"),
    "393" => array ( "area" => "Henan", "city" => "Puyang"),
    "392" => array ( "area" => "Henan", "city" => "Hebi"),
    "391" => array ( "area" => "Henan", "city" => "Jiaozuo"),
    "379" => array ( "area" => "Henan", "city" => "Luoyang"),
    "377" => array ( "area" => "Henan", "city" => "Nanyang"),
    "376" => array ( "area" => "Henan", "city" => "Xinyang"),
    "375" => array ( "area" => "Henan", "city" => "Pingdingshan"),
    "374" => array ( "area" => "Henan", "city" => "Xuchang"),
    "373" => array ( "area" => "Henan", "city" => "Xinxiang"),
    "372" => array ( "area" => "Henan", "city" => "Anyang"),
    "371" => array ( "area" => "Henan", "city" => "Zhengzhou and Kaifeng"),
    "370" => array ( "area" => "Henan", "city" => "Shangqiu"),
    "359" => array ( "area" => "Shanxi", "city" => "Yuncheng"),
    "358" => array ( "area" => "Shanxi", "city" => "Lüliang"),
    "357" => array ( "area" => "Shanxi", "city" => "Linfen"),
    "356" => array ( "area" => "Shanxi", "city" => "Jincheng"),
    "355" => array ( "area" => "Shanxi", "city" => "Changzhi"),
    "354" => array ( "area" => "Shanxi", "city" => "Jinzhong"),
    "353" => array ( "area" => "Shanxi", "city" => "Yangquan"),
    "352" => array ( "area" => "Shanxi", "city" => "Datong"),
    "351" => array ( "area" => "Shanxi", "city" => "Taiyuan"),
    "350" => array ( "area" => "Shanxi", "city" => "Xinzhou"),
    "349" => array ( "area" => "Shanxi", "city" => "Shuozhou"),
    "335" => array ( "area" => "Hebei", "city" => "Qinhuangdao"),
    "319" => array ( "area" => "Hebei", "city" => "Xingtai"),
    "318" => array ( "area" => "Hebei", "city" => "Hengshui"),
    "317" => array ( "area" => "Hebei", "city" => "Cangzhou"),
    "316" => array ( "area" => "Hebei", "city" => "Langfang"),
    "315" => array ( "area" => "Hebei", "city" => "Tangshan"),
    "314" => array ( "area" => "Hebei", "city" => "Chengde"),
    "313" => array ( "area" => "Hebei", "city" => "Zhangjiakou"),
    "312" => array ( "area" => "Hebei", "city" => "Baoding"),
    "311" => array ( "area" => "Hebei", "city" => "Shijiazhuang"),
    "310" => array ( "area" => "Hebei", "city" => "Handan"),
    "29" => array ( "area" => "Shaanxi", "city" => "Xi'an and Xianyang"),
    "28" => array ( "area" => "Sichuan", "city" => "Chengdu, Meishan and Ziyang"),
    "27" => array ( "area" => "Hubei", "city" => "Wuhan"),
    "26" => array ( "area" => "Shanghai, Tianjin and Chongqing", "city" => "Taipei"),
    "25" => array ( "area" => "Jiangsu", "city" => "Nanjing"),
    "24" => array ( "area" => "Liaoning", "city" => "Shenyang, Tieling, Fushun and Benxi"),
    "23" => array ( "area" => "Shanghai, Tianjin and Chongqing", "city" => "Chongqing"),
    "22" => array ( "area" => "Shanghai, Tianjin and Chongqing", "city" => "Tianjin"),
    "21" => array ( "area" => "Shanghai, Tianjin and Chongqing", "city" => "Shanghai"),
    "20" => array ( "area" => "Guangdong", "city" => "Guangzhou"),
    "10" => array ( "area" => "Beijing", "city" => "Beijing")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "86", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "China", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+86 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for satellite network with 2 digits NDC and 9 digits SN
   */
  $prefixes = array (
    "17400" => "China Satellite Communications Co., Ltd.",
    "17401" => "China Satellite Communications Co., Ltd.",
    "17402" => "China Satellite Communications Co., Ltd.",
    "17403" => "China Satellite Communications Co., Ltd.",
    "17404" => "China Satellite Communications Co., Ltd.",
    "17405" => "China Satellite Communications Co., Ltd.",
    "1749" => "Inmarsat Plc.",
    "1349" => "China Satellite Communications Co., Ltd."
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) == 14)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "86", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "China", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+86 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Chinesian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
