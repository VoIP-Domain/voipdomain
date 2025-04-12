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
 * related to country database of Australia.
 *
 * Reference: https://www.itu.int/oth/T020200000D/en (2011-08-03)
 * Reference: https://www.legislation.gov.au/Details/F2016C00283
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
 * E.164 Australia country hook
 */
framework_add_filter ( "e164_identify_country_AUS", "e164_identify_country_AUS");

/**
 * E.164 Australian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "AUS" (code for
 * Australia). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_AUS ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Australia
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+61")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Australia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "49157" => "ACMA",
    "48990" => "Novatel Telephony Pty Ltd",
    "48984" => "Victorian Rail Track",
    "42011" => "COMPATEL Limited",
    "42010" => "Pivotel Satellite Pty Ltd",
    "42009" => "CLX",
    "42003" => "Symbio Network Pty Ltd",
    "42002" => "Dialogue Communications Pty Ltd",
    "42001" => "Rail Corporation New South Wales",
    "42000" => "Rail Corporation New South Wales",
    "4914" => "Telstra",
    "4913" => "Telstra",
    "4912" => "Telstra",
    "4911" => "Telstra",
    "4910" => "Telstra",
    "4899" => "Pivotel",
    "4898" => "Novatel Telephony Pty Ltd",
    "4897" => "Novatel Telephony Pty Ltd",
    "4896" => "Novatel Telephony Pty Ltd",
    "4895" => "Novatel Telephony Pty Ltd",
    "4894" => "Novatel Telephony Pty Ltd",
    "4893" => "Novatel Telephony Pty Ltd",
    "4892" => "Novatel Telephony Pty Ltd",
    "4891" => "Novatel Telephony Pty Ltd",
    "4890" => "Novatel Telephony Pty Ltd",
    "4889" => "Telstra",
    "4888" => "Pivotel",
    "4887" => "Telstra",
    "4886" => "Telstra",
    "4885" => "Telstra",
    "4884" => "Telstra",
    "4883" => "Telstra",
    "4882" => "Telstra",
    "4881" => "Telstra",
    "4880" => "Telstra",
    "4820" => "Optus",
    "4791" => "Optus",
    "4790" => "Optus",
    "4729" => "Telstra",
    "4728" => "Telstra",
    "4727" => "Telstra",
    "4726" => "Telstra",
    "4725" => "Telstra",
    "4707" => "Lycamobile",
    "4706" => "Lycamobile",
    "4705" => "Lycamobile",
    "4704" => "Lycamobile",
    "4703" => "Lycamobile",
    "4702" => "Lycamobile",
    "4701" => "Lycamobile",
    "4700" => "Lycamobile",
    "4689" => "Optus",
    "4688" => "Optus",
    "4687" => "Optus",
    "4686" => "Optus",
    "4685" => "Optus",
    "4684" => "Optus",
    "4683" => "Optus",
    "4526" => "Vodafone",
    "4525" => "Vodafone",
    "4524" => "Vodafone",
    "4523" => "Vodafone",
    "4522" => "Vodafone",
    "4521" => "Vodafone",
    "4520" => "Vodafone",
    "4445" => "MBLOX",
    "4444" => "Telstra",
    "4209" => "Vodafone",
    "4208" => "Vodafone",
    "4207" => "Vodafone",
    "4206" => "Vodafone",
    "4205" => "Vodafone",
    "4204" => "Vodafone",
    "4203" => "Vodafone",
    "4202" => "Vodafone",
    "499" => "Telstra",
    "498" => "Telstra",
    "497" => "Telstra",
    "490" => "Telstra",
    "487" => "Telstra",
    "484" => "Telstra",
    "481" => "Optus",
    "478" => "Optus",
    "477" => "Telstra",
    "476" => "Telstra",
    "475" => "Telstra",
    "474" => "Telstra",
    "473" => "Telstra",
    "469" => "Lycamobile",
    "467" => "Telstra",
    "466" => "Optus",
    "459" => "Telstra",
    "458" => "Telstra",
    "457" => "Telstra",
    "456" => "Telstra",
    "455" => "Telstra",
    "451" => "Vodafone",
    "450" => "Vodafone",
    "449" => "Vodafone",
    "448" => "Telstra",
    "447" => "Telstra",
    "439" => "Telstra",
    "438" => "Telstra",
    "437" => "Telstra",
    "436" => "Telstra",
    "435" => "Optus",
    "434" => "Optus",
    "433" => "Vodafone",
    "432" => "Optus",
    "431" => "Optus",
    "430" => "Vodafone",
    "429" => "Telstra",
    "428" => "Telstra",
    "427" => "Telstra",
    "426" => "Vodafone",
    "425" => "Vodafone",
    "424" => "Vodafone",
    "423" => "Optus",
    "422" => "Optus",
    "421" => "Optus",
    "419" => "Telstra",
    "418" => "Telstra",
    "417" => "Telstra",
    "416" => "Vodafone",
    "415" => "Vodafone",
    "414" => "Vodafone",
    "413" => "Optus",
    "412" => "Optus",
    "411" => "Optus",
    "410" => "Vodafone",
    "409" => "Telstra",
    "408" => "Telstra",
    "407" => "Telstra",
    "406" => "Vodafone",
    "405" => "Vodafone",
    "404" => "Vodafone",
    "403" => "Optus",
    "402" => "Optus",
    "401" => "Optus",
    "400" => "Telstra",
    "50" => "Universal personal telecommunications service"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "61", "NDC" => (string) $prefix, "Country" => "Australia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 1) . ") " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+61 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network
   */
  $prefixes = array (
    "899" => "Carnamah, Carnarvon, Geraldton, Meekatharra, Morawa, Mullewa, Wongan Hills",
    "898" => "Albany, Katanning, Kondinin, Narrogin, Wagin",
    "897" => "Bridgetown, Bunbury, Busselton, Pinjarra",
    "896" => "Moora, Northam, Wongan Hills, Wyalkatchem, York",
    "895" => "Bullsbrook East, Northam, Pinjarra",
    "894" => "Perth",
    "893" => "Perth",
    "892" => "Perth",
    "891" => "Christmas Island, Cocos (Keeling) Islands, Derby, Great Sandy, Port Hedland",
    "890" => "Bruce Rock, Great Victoria, Kalgoorlie, Merredin",
    "889" => "Alice Springs, Darwin",
    "888" => "Clare, Kadina, Port Lincoln, Burra, Balaklava, Maitland, Gawler, Yorketown",
    "887" => "Bordertown, Mount Gambier, Naracoorte",
    "886" => "Ceduna, Port Augusta, Port Pirie, Port Lincoln, Gladstone, Peterborough, Cook, Woomera",
    "885" => "Berri, Gawler, Kangaroo Island, Malalla, Murray Bridge, Nurioopta, Tailem Bend, Victor Harbour, Waikerie",
    "884" => "Adelaide",
    "883" => "Adelaide",
    "882" => "Adelaide",
    "881" => "Adelaide",
    "880" => "Broken Hill",
    "879" => "Alice Springs, Darwin",
    "878" => "Clare, Kadina, Port Lincoln, Burra, Balaklava, Maitland, Gawler, Yorketown",
    "877" => "Bordertown, Mount Gambier, Naracoorte",
    "876" => "Ceduna, Port Augusta, Port Pirie, Port Lincoln, Gladstone, Peterborough, Cook, Woomera",
    "875" => "Berri, Gawler, Kangaroo Island, Malalla, Murray Bridge, Nurioopta, Tailem Bend, Victor Harbour, Waikerie",
    "874" => "Adelaide",
    "873" => "Adelaide",
    "872" => "Adelaide",
    "871" => "Adelaide",
    "870" => "Adelaide",
    "868" => "Albany, Katanning, Kondinin, Narrogin, Wagin",
    "866" => "Moora, Northam, Wongan Hills, Wyalkatchem, York",
    "865" => "Perth",
    "864" => "Perth",
    "863" => "Perth",
    "862" => "Perth",
    "861" => "Perth",
    "860" => "Bruce Rock, Great Victoria, Kalgoorlie, Merredin",
    "854" => "Perth",
    "853" => "Perth",
    "852" => "Perth",
    "851" => "Christmas Island, Cocos (Keeling) Islands, Derby, Great Sandy, Port Hedland",
    "776" => "Charleville, Dalby, Dirranbandi, Goondiwindi, Inglewood, Longreach, Miles, Roma, Stanthorpe, Toowoomba, Warwick",
    "757" => "Beaudesert, Southport, Tweed Heads",
    "756" => "Beaudesert, Southport, Tweed Heads",
    "755" => "Beaudesert, Southport, Tweed Heads",
    "754" => "Caboolture, Esk, Gatton, Gympie, Nambour",
    "753" => "Caboolture, Esk, Gatton, Gympie, Nambour",
    "752" => "Caboolture, Esk, Gatton, Gympie, Nambour",
    "749" => "Biloela, Emerald, Gladstone, Mackay, Rockhampton",
    "748" => "Biloela, Emerald, Gladstone, Mackay, Rockhampton",
    "747" => "Cloncurry, Hughenden, Townsville",
    "746" => "Charleville, Dalby, Dirranbandi, Goondiwindi, Inglewood, Longreach, Miles, Roma, Stanthorpe, Toowoomba, Warwick",
    "745" => "Charleville, Dalby, Dirranbandi, Goondiwindi, Inglewood, Longreach, Miles, Roma, Stanthorpe, Toowoomba, Warwick",
    "744" => "Cloncurry, Hughenden, Townsville",
    "743" => "Bundaberg, Gayndah, Kingaroy, Maryborough, Murgon",
    "742" => "Cairns",
    "741" => "Bundaberg, Gayndah, Kingaroy, Maryborough, Murgon",
    "740" => "Cairns",
    "367" => "Deloraine, Flinders Island, Launceston, Scottsdale, St Mary’s",
    "365" => "Burnie, Devonport, King Island, Queenstown, Smithton",
    "364" => "Burnie, Devonport, King Island, Queenstown, Smithton",
    "363" => "Deloraine, Flinders Island, Launceston, Scottsdale, St Mary’s",
    "362" => "Hobart, Geeveston, Oatlands, Ouse",
    "361" => "Hobart, Geeveston, Oatlands, Ouse",
    "359" => "Mornington, Warragul",
    "358" => "Deniliquin, Numurkah, Shepparton",
    "357" => "Alexandra, Myrtleford, Seymour, Wangaratta, Deniliquin, Numurkah, Shepparton",
    "356" => "Foster, Korumburra, Warragul",
    "355" => "Camperdown, Casterton, Edenhope, Hamilton, Portland, Warrnambool",
    "354" => "Bendigo, Charlton, Echuca, Kerang, Kyneton, Maryborough",
    "353" => "Ararat, Ballarat, Horsham, Kyneton, Nhill",
    "352" => "Geelong, Colac",
    "351" => "Bairnsdale, Morwell, Sale",
    "350" => "Balranald, Hopetoun, Mildura, Ouyen, Swan Hill",
    "349" => "Mornington, Warragul",
    "347" => "Alexandra, Myrtleford, Seymour, Wangaratta, Deniliquin, Numurkah, Shepparton",
    "345" => "Camperdown, Casterton, Edenhope, Hamilton, Portland, Warrnambool",
    "344" => "Bendigo, Charlton, Echuca, Kerang, Kyneton, Maryborough",
    "343" => "Ararat, Ballarat, Horsham, Kyneton, Nhill",
    "342" => "Geelong, Colac",
    "341" => "Bairnsdale, Morwell, Sale",
    "340" => "Balranald, Hopetoun, Mildura, Ouyen, Swan Hill",
    "253" => "Bathurst, Cowra, Lithgow, Mudgee, Orange, Rylstone, Young",
    "269" => "Adelong, Griffith, Hay, Narrandera, Temora, Wagga Wagga, West Wyalong",
    "268" => "Bourke, Condoblin, Coonamble, Dubbo, Forbes, Moree, Nyngan, Parkes, Wellington",
    "267" => "Armidale, Barraba, Gunnedah, Inverell, Moree, Narrabri, Glen Innes, Tamworth",
    "266" => "Casino, Coffs Harbour, Graton, Kyogle, Lismore, Murwillumbah",
    "265" => "Kempsey, Lord Howe Island, Muswellbrook, Singleton, Taree, Wauchope",
    "264" => "Bega, Cooma",
    "263" => "Bathurst, Cowra, Lithgow, Mudgee, Orange, Rylstone, Young",
    "262" => "Canberra",
    "261" => "Canberra",
    "260" => "Albury, Corryong",
    "259" => "Adelong, Griffith, Hay, Narrandera, Temora, Wagga Wagga, West Wyalong",
    "258" => "Bourke, Condoblin, Coonamble, Dubbo, Forbes, Moree, Nyngan, Parkes, Wellington",
    "257" => "Armidale, Barraba, Gunnedah, Inverell, Moree, Narrabri, Glen Innes, Tamworth",
    "256" => "Casino, Coffs Harbour, Graton, Kyogle, Lismore, Murwillumbah",
    "255" => "Kempsey, Lord Howe Island, Muswellbrook, Singleton, Taree, Wauchope",
    "252" => "Canberra",
    "251" => "Canberra",
    "250" => "Albury, Corryong",
    "249" => "Newcastle",
    "248" => "Bowral, Crookwell, Goulburn, Marulan",
    "247" => "Penrith",
    "246" => "Campbelltown",
    "245" => "Windsor",
    "244" => "Moruya, Nowra",
    "243" => "Gosford",
    "242" => "Wollongong",
    "241" => "Newcastle",
    "240" => "Newcastle",
    "238" => "Bowral, Crookwell, Goulburn, Marulan",
    "233" => "Gosford",
    "73" => "Brisbane, Bribie Island, Esk",
    "72" => "Brisbane, Bribie Island, Esk",
    "39" => "Melbourne",
    "38" => "Melbourne",
    "37" => "Melbourne",
    "29" => "Sydney",
    "28" => "Sydney",
    "27" => "Sydney",
    "8" => "Central and West Region (Western Australia, South Australia, the Northern Territory and parts of New South Wales)",
    "7" => "North East Region (Queensland)",
    "2" => "Central East Region (New South Wales, the Australian Capital Territory and parts of northern Victoria)",
    "3" => "South East Region (Tasmania, most of Victoria and parts of southern New South Wales)",
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "61", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Australia", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 1) . ") " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+61 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for VoIP network
   */
  if ( substr ( $parameters["Number"], 3, 2) == "55")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "61", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Australia", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => "(0" . substr ( $parameters["Number"], 3, 1) . ") " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8), "International" => "+61 " . substr ( $parameters["Number"], 3, 1) . " " . substr ( $parameters["Number"], 4, 4) . " " . substr ( $parameters["Number"], 8))));
  }

  /**
   * If reached here, number wasn't identified as a valid Australian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
