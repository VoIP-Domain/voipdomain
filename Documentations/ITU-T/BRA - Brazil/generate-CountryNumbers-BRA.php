#!/usr/bin/php -q
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
 * Brazil SQL regex generator.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR & E_USER_ERROR);
ini_set ( "display_errors", "false");

/**
 * Check if script is running from CLI
 */
if ( ! defined ( "STDIN"))
{
  echo "This script must be executed into CLI!\n";
  exit ( 1);
}

/**
 * Show software version header
 */
echo chr ( 27) . "[1;37mVoIP Domain Brazil Database Generator Tool" . chr ( 27) . "[1;0m v1.0\n";
echo "\n";

/**
 * Data of Brazil
 */
$countrycode = 76;
$prefixes = array ();

/**
 * Areas of Brazil
 */
$areas = array ();
$areas[] = array ( "Regex" => "1[1-9]", "Area" => "São Paulo", "AreaAbbv" => "SP");
$areas[] = array ( "Regex" => "2[124]", "Area" => "Rio de Janeiro", "AreaAbbv" => "RJ");
$areas[] = array ( "Regex" => "2[78]", "Area" => "Espírito Santo", "AreaAbbv" => "ES");
$areas[] = array ( "Regex" => "3[1234578]", "Area" => "Minas Gerais", "AreaAbbv" => "MG");
$areas[] = array ( "Regex" => "4[123456]", "Area" => "Paraná", "AreaAbbv" => "PR");
$areas[] = array ( "Regex" => "4[789]", "Area" => "Santa Catarina", "AreaAbbv" => "SC");
$areas[] = array ( "Regex" => "5[1345]", "Area" => "Rio Grande do Sul", "AreaAbbv" => "RS");
$areas[] = array ( "Regex" => "61", "Area" => "Distrito Federal", "AreaAbbv" => "DF");
$areas[] = array ( "Regex" => "6[24]", "Area" => "Goiás", "AreaAbbv" => "GO");
$areas[] = array ( "Regex" => "63", "Area" => "Tocantins", "AreaAbbv" => "TO");
$areas[] = array ( "Regex" => "6[56]", "Area" => "Mato Grosso", "AreaAbbv" => "MT");
$areas[] = array ( "Regex" => "67", "Area" => "Mato Grosso do Sul", "AreaAbbv" => "MS");
$areas[] = array ( "Regex" => "68", "Area" => "Acre", "AreaAbbv" => "AC");
$areas[] = array ( "Regex" => "69", "Area" => "Rondônia", "AreaAbbv" => "RO");
$areas[] = array ( "Regex" => "7[13457]", "Area" => "Bahia", "AreaAbbv" => "BA");
$areas[] = array ( "Regex" => "79", "Area" => "Sergipe", "AreaAbbv" => "SE");
$areas[] = array ( "Regex" => "8[17]", "Area" => "Pernambuco", "AreaAbbv" => "PE");
$areas[] = array ( "Regex" => "82", "Area" => "Alagoas", "AreaAbbv" => "AL");
$areas[] = array ( "Regex" => "83", "Area" => "Paraíba", "AreaAbbv" => "PB");
$areas[] = array ( "Regex" => "84", "Area" => "Rio Grande do Norte", "AreaAbbv" => "RN");
$areas[] = array ( "Regex" => "8[58]", "Area" => "Ceará", "AreaAbbv" => "CE");
$areas[] = array ( "Regex" => "8[69]", "Area" => "Piauí", "AreaAbbv" => "PI");
$areas[] = array ( "Regex" => "9[134]", "Area" => "Pará", "AreaAbbv" => "PA");
$areas[] = array ( "Regex" => "9[27]", "Area" => "Amazonas", "AreaAbbv" => "AM");
$areas[] = array ( "Regex" => "95", "Area" => "Roraima", "AreaAbbv" => "RR");
$areas[] = array ( "Regex" => "96", "Area" => "Amapá", "AreaAbbv" => "AM");
$areas[] = array ( "Regex" => "9[8,9]", "Area" => "Maranhão", "AreaAbbv" => "MA");

/**
 * Trunkings of Brazil
 */
$trunking = array ();
$trunking[] = array ( "Regex" => "1[1-9]7[0789]", "Area" => "São Paulo", "AreaAbbv" => "SP");
$trunking[] = array ( "Regex" => "2[124]7[078]", "Area" => "Rio de Janeiro", "AreaAbbv" => "RJ");
$trunking[] = array ( "Regex" => "2778", "Area" => "Espírito Santo", "AreaAbbv" => "ES");
$trunking[] = array ( "Regex" => "3[147]7[78]", "Area" => "Minas Gerais", "AreaAbbv" => "MG");
$trunking[] = array ( "Regex" => "4[1234]78", "Area" => "Paraná", "AreaAbbv" => "PR");
$trunking[] = array ( "Regex" => "4[78]78", "Area" => "Santa Catarina", "AreaAbbv" => "SC");
$trunking[] = array ( "Regex" => "5[14]78", "Area" => "Rio Grande do Sul", "AreaAbbv" => "RS");
$trunking[] = array ( "Regex" => "6178", "Area" => "Distrito Federal", "AreaAbbv" => "DF");
$trunking[] = array ( "Regex" => "6278", "Area" => "Goiás", "AreaAbbv" => "GO");
$trunking[] = array ( "Regex" => "6578", "Area" => "Mato Grosso", "AreaAbbv" => "MT");
$trunking[] = array ( "Regex" => "7[135]78", "Area" => "Bahia", "AreaAbbv" => "BA");
$trunking[] = array ( "Regex" => "8178", "Area" => "Pernambuco", "AreaAbbv" => "PE");
$trunking[] = array ( "Regex" => "8578", "Area" => "Ceará", "AreaAbbv" => "CE");

/**
 * Script header
 */
$output = "--    ___ ___       ___ _______     ______                        __\n";
$output .= "--   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.\n";
$output .= "--   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |\n";
$output .= "--   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|\n";
$output .= "--   |:  1   |     |:  |:  |       |:  1    /\n";
$output .= "--    \:.. ./      |::.|::.|       |::.. . /\n";
$output .= "--     `---'       `---`---'       `------'\n";
$output .= "--\n";
$output .= "-- Copyright (C) 2016-2025 Ernani José Camargo Azevedo\n";
$output .= "--\n";
$output .= "-- This program is free software: you can redistribute it and/or modify\n";
$output .= "-- it under the terms of the GNU General Public License as published by\n";
$output .= "-- the Free Software Foundation, either version 3 of the License, or\n";
$output .= "-- (at your option) any later version.\n";
$output .= "--\n";
$output .= "-- This program is distributed in the hope that it will be useful,\n";
$output .= "-- but WITHOUT ANY WARRANTY; without even the implied warranty of\n";
$output .= "-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n";
$output .= "-- GNU General Public License for more details.\n";
$output .= "--\n";
$output .= "-- You should have received a copy of the GNU General Public License\n";
$output .= "-- along with this program.  If not, see <https://www.gnu.org/licenses/>.\n";
$output .= "--\n";
$output .= "\n";
$output .= "--\n";
$output .= "-- VoIP Domain country database filters. This module add the filter calls\n";
$output .= "-- related to country database of Brazil.\n";
$output .= "--\n";
$output .= "-- Reference: https://www.itu.int/oth/T020200001D/en (2016-01-28)\n";
$output .= "--            https://www.gov.br/anatel/pt-br/regulado/numeracao/plano-de-numeracao-brasileiro (2015-03-03)\n";
$output .= "--\n";
$output .= "-- @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>\n";
$output .= "-- @version    1.0\n";
$output .= "-- @package    VoIP Domain\n";
$output .= "-- @subpackage CountryDB\n";
$output .= "-- @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.\n";
$output .= "-- @license    https://www.gnu.org/licenses/gpl-3.0.en.html\n";
$output .= "--\n";
$output .= "\n";

/**
 * Add landline and mobile for each state area
 */
$output .= "INSERT INTO `CountryNumbers` (`Country`, `NumberRegex`, `Data`) VALUES ";
foreach ( $areas as $area)
{
  $prefixes[] = "55" . ( strpos ( $area["Regex"], "[") !== false ? substr ( $area["Regex"], 0, strpos ( $area["Regex"], "[")) : $area["Regex"]);
  $output .= "(" . $countrycode . ", '^\\\\+55" . addslashes ( $area["Regex"]) . "[2-5][0-9][0-9][0-9][0-9][0-9][0-9][0-9]\$', '{\"CC\":55,\"NDC\":\"{3,2}\",\"SN\":\"{5,}\",\"Area\":\"" . addslashes ( $area["Area"]) . "\",\"AreaAbbv\":\"" . addslashes ( $area["AreaAbbv"]) . "\",\"City\":\"\",\"CityAbbv\":\"\",\"Operator\":\"\",\"Type\":1,\"CallFormats\":{\"Local\":\"({3,2}) {5,4}-{9,}\",\"International\":\"+55 {3,2} {5,}\"}}'), ";
  $output .= "(" . $countrycode . ", '^\\\\+55" . addslashes ( $area["Regex"]) . "9[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]\$', '{\"CC\":55,\"NDC\":\"{3,2}\",\"SN\":\"{5,}\",\"Area\":\"" . addslashes ( $area["Area"]) . "\",\"AreaAbbv\":\"" . addslashes ( $area["AreaAbbv"]) . "\",\"City\":\"\",\"CityAbbv\":\"\",\"Operator\":\"\",\"Type\":2,\"CallFormats\":{\"Local\":\"({3,2}) {5,5}-{10,}\",\"International\":\"+55 {3,2} {5,}\"}}'), ";
}

/**
 * Add trunking special areas
 */
foreach ( $trunking as $trunk)
{
  $prefixes[] = "55" . ( strpos ( $trunk["Regex"], "[") !== false ? substr ( $trunk["Regex"], 0, strpos ( $trunk["Regex"], "[")) : $trunk["Regex"]);
  $output .= "(" . $countrycode . ", '^\\\\+55" . addslashes ( $trunk["Regex"]) . "[0-9][0-9][0-9][0-9][0-9][0-9]\$', '{\"CC\":55,\"NDC\":\"{3,2}\",\"SN\":\"{5,}\",\"Area\":\"" . addslashes ( $trunk["Area"]) . "\",\"AreaAbbv\":\"" . addslashes ( $trunk["AreaAbbv"]) . "\",\"City\":\"\",\"CityAbbv\":\"\",\"Operator\":\"\",\"Type\":131070,\"CallFormats\":{\"Local\":\"({3,2}) {5,4}-{9,}\",\"International\":\"+55 {3,2} {5,}\"}}'), ";
}
$output = substr ( $output, 0, strlen ( $output) - 2) . ";\n";

/**
 * Add each prefix to CountryCodes table
 */
$output .= "INSERT INTO `CountryCodes` (`Country`, `Code`, `Prefix`) VALUES ";
foreach ( array_unique ( $prefixes) as $prefix)
{
  if ( $prefix != "55")
  {
    $output .= "(" . $countrycode . ", '55', '" . addslashes ( $prefix) . "'), ";
  }
}
$output = substr ( $output, 0, strlen ( $output) - 2) . ";\n";

/**
 * Write SQL file
 */
echo "Writing script file \"CountryNumbers-BRA.sql\"... ";
file_put_contents ( "CountryNumbers-BRA.sql", $output);
echo "Done!\n";

/**
 * Finished!
 */
echo "\n";
echo "Finished!\n";
?>
