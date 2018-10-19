<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * VoIP Domain country database filter module. This module add the filter calls
 * related to country database.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CountryDB
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Check if country code exist
 */
framework_add_filter ( "get_countries", "get_countries");

/**
 * Function to check if a country exist into database.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return mixed Country database entry if exists, otherwise boolean false
 */
function get_countries ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for parameters to query
   */
  $where = "";
  if ( array_key_exists ( "code", $parameters))
  {
    $where .= " AND `Code` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["code"]);
  }
  if ( array_key_exists ( "name", $parameters))
  {
    $where .= " AND `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["name"])))) . "%'";
  }
  if ( array_key_exists ( "alpha2", $parameters))
  {
    $where .= " AND `Alpha2` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Alpha2"]) . "'";
  }
  if ( array_key_exists ( "alpha3", $parameters))
  {
    $where .= " AND `Alpha3` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Alpha3"]) . "'";
  }
  if ( array_key_exists ( "regioncode", $parameters))
  {
    $where .= " AND `RegionCode` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["regioncode"]);
  }
  if ( array_key_exists ( "subregioncode", $parameters))
  {
    $where .= " AND `SubRegionCode` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["subregioncode"]);
  }
  if ( array_key_exists ( "iso3166-2", $parameters))
  {
    $where .= " AND `ISO3166-2` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["iso3166-2"]) . "'";
  }

  /**
   * Search countries
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Countries`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    while ( $country = $result->fetch_assoc ())
    {
      $data[] = $country;
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * E.164 hooks
 */
framework_add_filter ( "e164_identify", "e164_identify");

/**
 * E.164 number identification hook. This hook try to identify an E.164 number to
 * check if it is valid, which country it's from, area code, if it's mobile or
 * landline number and other informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["number"]
 * @return array Array contaning many informations about the requested number
 */
function e164_identify ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check if number is valid
   */
  if ( ! validateE164 ( $parameters["number"]))
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Initialize data
   */
  $data = array ();
  $data["e164"] = $parameters["number"];

  /**
   * First, try to identify the country
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Countries`.*, `CountryCodes`.`Code` AS `CountryCode` FROM `Countries` RIGHT JOIN `CountryCodes` ON `Countries`.`Code` = `CountryCodes`.`Country` ORDER BY LENGTH(`CountryCode`) DESC, `CountryCode` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $country = $result->fetch_assoc ())
  {
    if ( substr ( $parameters["number"], 1, strlen ( $country["CountryCode"])) == $country["CountryCode"])
    {
      $data["country"] = $country["Code"];
      $data["countrycode"] = $country["CountryCode"];
      $data["countryname"] = $country["Name"];
      $data["alpha2"] = $country["Alpha2"];
      $data["alpha3"] = $country["Alpha3"];
      $subdata = filters_call ( "e164_identify_country_" . $country["Alpha3"], array ( "number" => $parameters["number"], "country" => $country));
      if ( is_array ( $subdata))
      {
        $data = array_merge_recursive ( $data, $subdata);
      }
      break;
    }
  }

  /**
   * Return data to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * E.164 country specific hooks
 */
framework_add_filter ( "e164_identify_country_BRA", "e164_identify_country_BRA");
framework_add_filter ( "e164_identify_country_USA", "e164_identify_country_USA");
framework_add_filter ( "e164_identify_country_CAN", "e164_identify_country_CAN");
framework_add_filter ( "e164_identify_country_URY", "e164_identify_country_URY");

/**
 * E.164 Brazilian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BRA" (code for Brazil). This
 * hook will verify if phone number is valid, return the area code, area name,
 * phone number and informations if it's a mobile or landline number.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["number"]
 * @return array Array contaning many informations about the requested number
 */
function e164_identify_country_BRA ( $buffer, $parameters)
{
  /**
   * Check if number is from Brazil
   */
  if ( substr ( $parameters["number"], 0, 3) != "+55")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for valid area code
   */
  $areacode = array (
    "11" => "São Paulo",
    "12" => "São Paulo",
    "13" => "São Paulo",
    "14" => "São Paulo",
    "15" => "São Paulo",
    "16" => "São Paulo",
    "17" => "São Paulo",
    "18" => "São Paulo",
    "19" => "São Paulo",
    "21" => "Rio de Janeiro",
    "22" => "Rio de Janeiro",
    "24" => "Rio de Janeiro",
    "27" => "Espírito Santo",
    "28" => "Espírito Santo",
    "31" => "Minas Gerais",
    "31" => "Minas Gerais",
    "32" => "Minas Gerais",
    "33" => "Minas Gerais",
    "34" => "Minas Gerais",
    "35" => "Minas Gerais",
    "37" => "Minas Gerais",
    "38" => "Minas Gerais",
    "41" => "Paraná",
    "42" => "Paraná",
    "43" => "Paraná",
    "44" => "Paraná",
    "45" => "Paraná",
    "46" => "Paraná",
    "47" => "Santa Catarina",
    "48" => "Santa Catarina",
    "49" => "Santa Catarina",
    "51" => "Rio Grande do Sul",
    "53" => "Rio Grande do Sul",
    "54" => "Rio Grande do Sul",
    "55" => "Rio Grande do Sul",
    "61" => "Distrito Federal/Goiás",
    "62" => "Goiás",
    "63" => "Tocantins",
    "64" => "Goiás",
    "65" => "Mato Grosso",
    "66" => "Mato Grosso",
    "67" => "Mato Grosso do Sul",
    "68" => "Acre",
    "69" => "Rondônia",
    "71" => "Bahia",
    "73" => "Bahia",
    "74" => "Bahia",
    "75" => "Bahia",
    "77" => "Bahia",
    "79" => "Sergipe",
    "81" => "Pernambuco",
    "82" => "Alagoas",
    "83" => "Paraíba",
    "84" => "Rio Grande do Norte",
    "85" => "Ceará",
    "86" => "Piauí",
    "87" => "Pernambuco",
    "88" => "Ceará",
    "89" => "Piauí",
    "91" => "Pará",
    "92" => "Amazonas",
    "93" => "Pará",
    "94" => "Pará",
    "95" => "Roraima",
    "96" => "Amapá",
    "97" => "Amazonas",
    "98" => "Maranhão",
    "99" => "Maranhão");
  if ( array_key_exists ( substr ( $parameters["number"], 3, 2), $areacode))
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "areacode" => substr ( $parameters["number"], 3, 2), "areaname" => $areacode[substr ( $parameters["number"], 3, 2)], "number" => substr ( $parameters["number"], 5), "mobile" => substr ( $parameters["number"], 5, 1) <= 9 && substr ( $parameters["number"], 5, 1) >= 6, "landline" => substr ( $parameters["number"], 5, 1) <= 5 && substr ( $parameters["number"], 5, 1) >= 2));
  }

  /**
   * If reached here, number wasn't identified as a valid Brazil phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}

/**
 * E.164 North American area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "USA" (code for
 * United States of America). This hook will verify if phone number is valid,
 * return the area code, area name and phone number.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["number"]
 * @return array Array contaning many informations about the requested number
 */
function e164_identify_country_USA ( $buffer, $parameters)
{
  /**
   * Check if number is from Brazil
   */
  if ( substr ( $parameters["number"], 0, 2) != "+1")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for valid area code
   */
  $areacode = array (
    "201" => "New Jersey",
    "202" => "District of Columbia",
    "203" => "Connecticut",
    "205" => "Alabama",
    "206" => "Washington",
    "207" => "Maine",
    "208" => "Idaho",
    "209" => "California",
    "210" => "Texas",
    "212" => "New York",
    "213" => "California",
    "214" => "Texas",
    "215" => "Pennsylvania",
    "216" => "Ohio",
    "217" => "Illinois",
    "218" => "Minnesota",
    "219" => "Indiana",
    "220" => "Ohio",
    "224" => "Illinois",
    "225" => "Louisiana",
    "228" => "Mississippi",
    "229" => "Georgia",
    "231" => "Michigan",
    "234" => "Ohio",
    "239" => "Florida",
    "240" => "Maryland",
    "248" => "Michigan",
    "251" => "Alabama",
    "252" => "North Carolina",
    "253" => "Washington",
    "254" => "Texas",
    "256" => "Alabama",
    "260" => "Indiana",
    "262" => "Wisconsin",
    "267" => "Pennsylvania",
    "269" => "Michigan",
    "270" => "Kentucky",
    "272" => "Pennsylvania",
    "276" => "Virginia",
    "281" => "Texas",
    "301" => "Maryland",
    "302" => "Delaware",
    "303" => "Colorado",
    "304" => "West Virginia",
    "305" => "Florida",
    "307" => "Wyoming",
    "308" => "Nebraska",
    "309" => "Illinois",
    "310" => "California",
    "312" => "Illinois",
    "313" => "Michigan",
    "314" => "Missouri",
    "315" => "New York",
    "316" => "Kansas",
    "317" => "Indiana",
    "318" => "Louisiana",
    "319" => "Iowa",
    "320" => "Minnesota",
    "321" => "Florida",
    "323" => "California",
    "325" => "Texas",
    "330" => "Ohio",
    "331" => "Illinois",
    "334" => "Alabama",
    "336" => "North Carolina",
    "337" => "Louisiana",
    "339" => "Massachusetts",
    "346" => "Texas",
    "347" => "New York",
    "351" => "Massachusetts",
    "352" => "Florida",
    "360" => "Washington",
    "361" => "Texas",
    "364" => "Kentucky",
    "385" => "Utah",
    "386" => "Florida",
    "401" => "Rhode Island",
    "402" => "Nebraska",
    "404" => "Georgia",
    "405" => "Oklahoma",
    "406" => "Montana",
    "407" => "Florida",
    "408" => "California",
    "409" => "Texas",
    "410" => "Maryland",
    "412" => "Pennsylvania",
    "413" => "Massachusetts",
    "414" => "Wisconsin",
    "415" => "California",
    "417" => "Missouri",
    "419" => "Ohio",
    "423" => "Tennessee",
    "424" => "California",
    "425" => "Washington",
    "432" => "Texas",
    "434" => "Virginia",
    "435" => "Utah",
    "440" => "Ohio",
    "442" => "California",
    "443" => "Maryland",
    "458" => "Oregon",
    "469" => "Texas",
    "478" => "Georgia",
    "479" => "Arkansas",
    "480" => "Arizona",
    "484" => "Pennsylvania",
    "501" => "Arkansas",
    "502" => "Kentucky",
    "503" => "Oregon",
    "504" => "Louisiana",
    "505" => "New Mexico",
    "507" => "Minnesota",
    "508" => "Massachusetts",
    "509" => "Washington",
    "510" => "California",
    "512" => "Texas",
    "513" => "Ohio",
    "515" => "Iowa",
    "516" => "New York",
    "517" => "Michigan",
    "518" => "New York",
    "520" => "Arizona",
    "530" => "California",
    "531" => "Nebraska",
    "539" => "Oklahoma",
    "540" => "Virginia",
    "541" => "Oregon",
    "551" => "New Jersey",
    "559" => "California",
    "561" => "Florida",
    "562" => "California",
    "563" => "Iowa",
    "567" => "Ohio",
    "570" => "Pennsylvania",
    "571" => "Virginia",
    "573" => "Missouri",
    "574" => "Indiana",
    "575" => "New Mexico",
    "580" => "Oklahoma",
    "585" => "New York",
    "586" => "Michigan",
    "601" => "Mississippi",
    "602" => "Arizona",
    "603" => "New Hampshire",
    "605" => "South Dakota",
    "606" => "Kentucky",
    "607" => "New York",
    "608" => "Wisconsin",
    "609" => "New Jersey",
    "610" => "Pennsylvania",
    "612" => "Minnesota",
    "614" => "Ohio",
    "615" => "Tennessee",
    "616" => "Michigan",
    "617" => "Massachusetts",
    "618" => "Illinois",
    "619" => "California",
    "620" => "Kansas",
    "623" => "Arizona",
    "626" => "California",
    "628" => "California",
    "629" => "Tennessee",
    "630" => "Illinois",
    "631" => "New York",
    "636" => "Missouri",
    "641" => "Iowa",
    "646" => "New York",
    "650" => "California",
    "651" => "Minnesota",
    "657" => "California",
    "660" => "Missouri",
    "661" => "California",
    "662" => "Mississippi",
    "667" => "Maryland",
    "669" => "California",
    "678" => "Georgia",
    "681" => "West Virginia",
    "682" => "Texas",
    "701" => "North Dakota",
    "702" => "Nevada",
    "703" => "Virginia",
    "704" => "North Carolina",
    "706" => "Georgia",
    "707" => "California",
    "708" => "Illinois",
    "712" => "Iowa",
    "713" => "Texas",
    "714" => "California",
    "715" => "Wisconsin",
    "716" => "New York",
    "717" => "Pennsylvania",
    "718" => "New York",
    "719" => "Colorado",
    "720" => "Colorado",
    "724" => "Pennsylvania",
    "725" => "Nevada",
    "727" => "Florida",
    "731" => "Tennessee",
    "732" => "New Jersey",
    "734" => "Michigan",
    "737" => "Texas",
    "740" => "Ohio",
    "743" => "North Carolina",
    "747" => "California",
    "754" => "Florida",
    "757" => "Virginia",
    "760" => "California",
    "762" => "Georgia",
    "763" => "Minnesota",
    "765" => "Indiana",
    "769" => "Mississippi",
    "770" => "Georgia",
    "772" => "Florida",
    "773" => "Illinois",
    "774" => "Massachusetts",
    "775" => "Nevada",
    "779" => "Illinois",
    "781" => "Massachusetts",
    "785" => "Kansas",
    "786" => "Florida",
    "801" => "Utah",
    "802" => "Vermont",
    "803" => "South Carolina",
    "804" => "Virginia",
    "805" => "California",
    "806" => "Texas",
    "808" => "Hawaii",
    "810" => "Michigan",
    "812" => "Indiana",
    "813" => "Florida",
    "814" => "Pennsylvania",
    "815" => "Illinois",
    "816" => "Missouri",
    "817" => "Texas",
    "818" => "California",
    "828" => "North Carolina",
    "830" => "Texas",
    "831" => "California",
    "832" => "Texas",
    "843" => "South Carolina",
    "845" => "New York",
    "847" => "Illinois",
    "848" => "New Jersey",
    "850" => "Florida",
    "854" => "South Carolina",
    "856" => "New Jersey",
    "857" => "Massachusetts",
    "858" => "California",
    "859" => "Kentucky",
    "860" => "Connecticut",
    "862" => "New Jersey",
    "863" => "Florida",
    "864" => "South Carolina",
    "865" => "Tennessee",
    "870" => "Arkansas",
    "878" => "Pennsylvania",
    "901" => "Tennessee",
    "903" => "Texas",
    "904" => "Florida",
    "907" => "Alaska",
    "908" => "New Jersey",
    "909" => "California",
    "910" => "North Carolina",
    "912" => "Georgia",
    "913" => "Kansas",
    "914" => "New York",
    "915" => "Texas",
    "916" => "California",
    "917" => "New York",
    "918" => "Oklahoma",
    "919" => "North Carolina",
    "920" => "Wisconsin",
    "925" => "California",
    "928" => "Arizona",
    "929" => "New York",
    "930" => "Indiana",
    "931" => "Tennessee",
    "936" => "Texas",
    "937" => "Ohio",
    "940" => "Texas",
    "941" => "Florida",
    "947" => "Michigan",
    "949" => "California",
    "951" => "California",
    "952" => "Minnesota",
    "954" => "Florida",
    "956" => "Texas",
    "959" => "Connecticut",
    "970" => "Colorado",
    "971" => "Oregon",
    "972" => "Texas",
    "973" => "New Jersey",
    "978" => "Massachusetts",
    "979" => "Texas",
    "980" => "North Carolina",
    "984" => "North Carolina",
    "985" => "Louisiana",
    "989" => "Michigan");
  if ( array_key_exists ( substr ( $parameters["number"], 2, 3), $areacode))
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "areacode" => substr ( $parameters["number"], 2, 3), "areaname" => $areacode[substr ( $parameters["number"], 2, 3)], "number" => substr ( $parameters["number"], 5)));
  }

  /**
   * If reached here, number wasn't identified as a valid United States phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}

/**
 * E.164 Canada area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "CAN" (code for Canada). This
 * hook will verify if phone number is valid, return the area code, area name
 * and phone number.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["number"]
 * @return array Array contaning many informations about the requested number
 */
function e164_identify_country_CAN ( $buffer, $parameters)
{
  /**
   * Check if number is from Brazil
   */
  if ( substr ( $parameters["number"], 0, 2) != "+1")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for valid area code
   */
  $areacode = array (
    "204" => "Manitoba",
    "226" => "Ontario",
    "236" => "British Columbia",
    "249" => "Ontario",
    "250" => "British Columbia",
    "289" => "Ontario",
    "306" => "Saskatchewan",
    "343" => "Ontario",
    "365" => "Ontario",
    "403" => "Alberta",
    "416" => "Ontario",
    "418" => "Québec",
    "431" => "Manitoba",
    "437" => "Ontario",
    "438" => "Québec",
    "450" => "Québec",
    "506" => "New Brunswick",
    "514" => "Québec",
    "519" => "Ontario",
    "579" => "Québec",
    "581" => "Québec",
    "587" => "Alberta",
    "604" => "British Columbia",
    "613" => "Ontario",
    "639" => "Saskatchewan",
    "647" => "Ontario",
    "705" => "Ontario",
    "709" => "Newfoundland and Labrador",
    "778" => "British Columbia",
    "780" => "Alberta",
    "782" => "Nova Scotia",
    "782" => "Prince Edward Island",
    "807" => "Ontario",
    "819" => "Québec",
    "825" => "Alberta",
    "867" => "Northwest Territories",
    "867" => "Nunavut",
    "867" => "Yukon Territory",
    "873" => "Québec",
    "902" => "Nova Scotia",
    "902" => "Prince Edward Island",
    "905" => "Ontario");
  if ( array_key_exists ( substr ( $parameters["number"], 2, 3), $areacode))
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "areacode" => substr ( $parameters["number"], 2, 3), "areaname" => $areacode[substr ( $parameters["number"], 2, 3)], "number" => substr ( $parameters["number"], 5)));
  }

  /**
   * If reached here, number wasn't identified as a valid Canada phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}

/**
 * E.164 Uruguay area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "URY" (code for Uruguay). This
 * hook will verify if phone number is valid, return the area code, area name,
 * phone number and informations if it's a mobile or landline number.
 * Reference: https://en.wikipedia.org/wiki/Telephone_numbers_in_Uruguay
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["number"]
 * @return array Array contaning many informations about the requested number
 */
function e164_identify_country_URY ( $buffer, $parameters)
{
  /**
   * Check if number is from Uruguay
   */
  if ( substr ( $parameters["number"], 0, 4) != "+598")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for valid area code
   */
  $areacodes = array (
    "77" => array ( "department" => "Artigas", "city" => ""),
    "776" => array ( "department" => "Artigas", "city" => "Baltasar Brum"),
    "777" => array ( "department" => "Artigas", "city" => "Tomás Gomensoro"),
    "778" => array ( "department" => "Artigas", "city" => "Mones Quintela"),
    "779" => array ( "department" => "Artigas", "city" => "Bella Unión"),
    "312" => array ( "department" => "Canelones", "city" => "San Ramón"),
    "313" => array ( "department" => "Canelones", "city" => ""),
    "315" => array ( "department" => "Canelones", "city" => "Tala"),
    "317" => array ( "department" => "Canelones", "city" => ""),
    "33" => array ( "department" => "Canelones", "city" => ""),
    "37" => array ( "department" => "Canelones", "city" => ""),
    "377" => array ( "department" => "Canelones", "city" => "Piedras de Afilar"),
    "378" => array ( "department" => "Canelones", "city" => ""),
    "379" => array ( "department" => "Canelones", "city" => ""),
    "64" => array ( "department" => "Cerro Largo", "city" => "Melo"),
    "675" => array ( "department" => "Cerro Largo", "city" => "Río Branco"),
    "679" => array ( "department" => "Cerro Largo", "city" => "Lago Merín"),
    "688" => array ( "department" => "Cerro Largo", "city" => "Fraile Muerto"),
    "52" => array ( "department" => "Colonia", "city" => "Colonia"),
    "542" => array ( "department" => "Colonia", "city" => ""),
    "544" => array ( "department" => "Colonia", "city" => "Nueva Palmira"),
    "55" => array ( "department" => "Colonia", "city" => ""),
    "574" => array ( "department" => "Colonia", "city" => ""),
    "575" => array ( "department" => "Colonia", "city" => "Colonia Miguelete"),
    "576" => array ( "department" => "Colonia", "city" => "Ombúes de Lavalle"),
    "577" => array ( "department" => "Colonia", "city" => "Conchillas"),
    "586" => array ( "department" => "Colonia", "city" => "Juan Lacaze"),
    "587" => array ( "department" => "Colonia", "city" => "Playa Fomento"),
    "588" => array ( "department" => "Colonia", "city" => "Santa Ana"),
    "36" => array ( "department" => "Durazno", "city" => "Durazno"),
    "365" => array ( "department" => "Durazno", "city" => "Carmen"),
    "367" => array ( "department" => "Durazno", "city" => "Sarandí del Yí"),
    "368" => array ( "department" => "Durazno", "city" => "Carlos Reyles"),
    "364" => array ( "department" => "Flores", "city" => "Trinidad"),
    "539" => array ( "department" => "Flores", "city" => "Ismael Cortinas"),
    "311" => array ( "department" => "Florida", "city" => ""),
    "318" => array ( "department" => "Florida", "city" => "Cerro Colorado"),
    "319" => array ( "department" => "Florida", "city" => "Chamizo"),
    "338" => array ( "department" => "Florida", "city" => ""),
    "339" => array ( "department" => "Florida", "city" => ""),
    "35" => array ( "department" => "Florida", "city" => "Florida"),
    "354" => array ( "department" => "Florida", "city" => "Sarandí Grande"),
    "44" => array ( "department" => "Lavalleja", "city" => "Minas"),
    "447" => array ( "department" => "Lavalleja", "city" => "Solís de Mataojo"),
    "448" => array ( "department" => "Lavalleja", "city" => "Pirarajá"),
    "449" => array ( "department" => "Lavalleja", "city" => "Mariscala"),
    "455" => array ( "department" => "Lavalleja", "city" => "José Pedro Varela"),
    "463" => array ( "department" => "Lavalleja", "city" => "Zapicán"),
    "466" => array ( "department" => "Lavalleja", "city" => "Cerro Chato"),
    "469" => array ( "department" => "Lavalleja", "city" => "José Battle y Ordoñez"),
    "42" => array ( "department" => "Maldonado", "city" => ""),
    "43" => array ( "department" => "Maldonado", "city" => ""),
    "446" => array ( "department" => "Maldonado", "city" => "Aiguá"),
    "486" => array ( "department" => "Maldonado", "city" => "Faro José Ignacio"),
    "2" => array ( "department" => "Montevideo", "city" => "Montevideo"),
    "72" => array ( "department" => "Paysandú", "city" => "Paysandú"),
    "742" => array ( "department" => "Paysandú", "city" => "Guichón"),
    "747" => array ( "department" => "Paysandú", "city" => "Piedras Coloradas"),
    "754" => array ( "department" => "Paysandú", "city" => "Quebracho"),
    "56" => array ( "department" => "Río Negro", "city" => "Fray Bentos"),
    "567" => array ( "department" => "Río Negro", "city" => "Young"),
    "568" => array ( "department" => "Río Negro", "city" => "Nuevo Berlín"),
    "569" => array ( "department" => "Río Negro", "city" => "San Javier"),
    "62" => array ( "department" => "Rivera", "city" => "Rivera"),
    "654" => array ( "department" => "Rivera", "city" => "Vichadero"),
    "656" => array ( "department" => "Rivera", "city" => "Tranqueras"),
    "658" => array ( "department" => "Rivera", "city" => "Minas de Corrales"),
    "456" => array ( "department" => "Rocha", "city" => "Lascano"),
    "457" => array ( "department" => "Rocha", "city" => "Velázquez"),
    "459" => array ( "department" => "Rocha", "city" => ""),
    "47" => array ( "department" => "Rocha", "city" => "Rocha"),
    "474" => array ( "department" => "Rocha", "city" => ""),
    "475" => array ( "department" => "Rocha", "city" => ""),
    "476" => array ( "department" => "Rocha", "city" => "La Coronilla"),
    "477" => array ( "department" => "Rocha", "city" => "Santa Teresa"),
    "479" => array ( "department" => "Rocha", "city" => ""),
    "73" => array ( "department" => "Salto", "city" => ""),
    "764" => array ( "department" => "Salto", "city" => "Constitución"),
    "766" => array ( "department" => "Salto", "city" => "Belén"),
    "768" => array ( "department" => "Salto", "city" => "Termas del Arapey"),
    "34" => array ( "department" => "San José", "city" => "San José"),
    "345" => array ( "department" => "San José", "city" => ""),
    "346" => array ( "department" => "San José", "city" => "Rafael Perazza"),
    "348" => array ( "department" => "San José", "city" => "Villa Rodriguez"),
    "349" => array ( "department" => "San José", "city" => "Ecilda Paullier"),
    "53" => array ( "department" => "Soriano", "city" => "Mercedes"),
    "534" => array ( "department" => "Soriano", "city" => "Dolores"),
    "536" => array ( "department" => "Soriano", "city" => "Cardona"),
    "537" => array ( "department" => "Soriano", "city" => "Palmitas"),
    "538" => array ( "department" => "Soriano", "city" => ""),
    "369" => array ( "department" => "Tacuarembó", "city" => "San Gregorio de Polanco"),
    "63" => array ( "department" => "Tacuarembó", "city" => "Tacuarembó"),
    "664" => array ( "department" => "Tacuarembó", "city" => "Paso de los Toros"),
    "45" => array ( "department" => "Treinta y Tres", "city" => "Treinta y Tres"),
    "458" => array ( "department" => "Treinta y Tres", "city" => "Vergara"),
    "464" => array ( "department" => "Treinta y Tres", "city" => "Santa Clara de Olimar")
  );
  krsort ( $areacodes, SORT_NUMERIC);
  foreach ( $areacodes as $areacode => $data)
  {
    if ( (int) substr ( $parameters["number"], 4, strlen ( $areacode)) == $areacode)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "areacode" => $areacode, "areaname" => $data["department"], "city" => $data["city"], "number" => substr ( $parameters["number"], 4 + strlen ( $areacode)), "mobile" => false, "landline" => true));
    }
  }
  if ( substr ( $parameters["number"], 4, 1) == "9")
  {
    switch ( substr ( $parameters["number"], 5, 1))
    {
      case "1":
      case "2":
      case "8":
      case "9":
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "areacode" => substr ( $parameters["number"], 4, 2), "areaname" => "Uruguay mobile", "city" => "", "operator" => "Administración Nacional de Telecomunicaciones (Ancel)", "number" => substr ( $parameters["number"], 4), "mobile" => true, "landline" => false));
        break;
      case "3":
      case "4":
      case "5":
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "areacode" => substr ( $parameters["number"], 4, 2), "areaname" => "Uruguay mobile", "city" => "", "operator" => "Abiatar S.A. (Movicom)", "number" => substr ( $parameters["number"], 4), "mobile" => true, "landline" => false));
        break;
      case "6":
      case "7":
        return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "areacode" => substr ( $parameters["number"], 4, 2), "areaname" => "Uruguay mobile", "city" => "", "operator" => "AM Wireless Uruguay S.A. (Claro)", "number" => substr ( $parameters["number"], 4), "mobile" => true, "landline" => false));
        break;
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Uruguay phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
