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
 * related to country database of Cabo Verde.
 *
 * Reference: https://www.itu.int/oth/T0202000026/en (2010-05-20)
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
 * E.164 Cabo Verde country hook
 */
framework_add_filter ( "e164_identify_country_CPV", "e164_identify_country_CPV");

/**
 * E.164 Cabo Verde area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "CPV" (code for
 * Cabo Verde). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and
 * if possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_CPV ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Cabo Verde
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+238")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Cabo Verde has 11 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 11)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "997" => "",
    "996" => "",
    "995" => "",
    "994" => "",
    "993" => "",
    "992" => "",
    "991" => "T+ Telecomunicações S.A.",
    "59" => "CVMóvel"
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "238", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Cabo Verde", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+238 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "285" => array ( "area" => "Brava", "city" => "Nova Síntra"),
    "284" => array ( "area" => "Fogo", "city" => "São Jorge"),
    "283" => array ( "area" => "Fogo", "city" => "Mosteiros"),
    "282" => array ( "area" => "Fogo", "city" => "Cova Figueira"),
    "281" => array ( "area" => "Fogo", "city" => "São Filipe"),
    "273" => array ( "area" => "Santiago", "city" => "Calheta São Miguel"),
    "272" => array ( "area" => "Santiago", "city" => "Picos"),
    "271" => array ( "area" => "Santiago", "city" => "São Lourenço dos Órgãos and São Jorge"),
    "269" => array ( "area" => "Santiago", "city" => "Pedra Badejo"),
    "268" => array ( "area" => "Santiago", "city" => "São Domingos"),
    "267" => array ( "area" => "Santiago", "city" => "Cidade Velha"),
    "266" => array ( "area" => "Santiago", "city" => "Tarrafal Santiago"),
    "265" => array ( "area" => "Santiago", "city" => "Santa Catarina"),
    "264" => array ( "area" => "Santiago", "city" => "Praia"),
    "263" => array ( "area" => "Santiago", "city" => "Praia"),
    "262" => array ( "area" => "Santiago", "city" => "Praia"),
    "261" => array ( "area" => "Santiago", "city" => "Praia"),
    "260" => array ( "area" => "Santiago", "city" => "Praia"),
    "256" => array ( "area" => "Maio", "city" => "Calheta do Maio"),
    "255" => array ( "area" => "Maio", "city" => "Vila Maio"),
    "252" => array ( "area" => "Boa Vista", "city" => "Fundo das Figueiras"),
    "251" => array ( "area" => "Boa Vista", "city" => "Sal Rei"),
    "242" => array ( "area" => "Sal", "city" => "Santa Maria"),
    "241" => array ( "area" => "Sal", "city" => "Espargos"),
    "238" => array ( "area" => "São Nicolau", "city" => "Praia Branca"),
    "237" => array ( "area" => "São Nicolau", "city" => "Fajã"),
    "236" => array ( "area" => "São Nicolau", "city" => "Tarrafal São Nicolau"),
    "235" => array ( "area" => "São Nicolau", "city" => "Ribeira Brava"),
    "232" => array ( "area" => "São Vicente", "city" => "Mindelo"),
    "231" => array ( "area" => "São Vicente", "city" => "Mindelo"),
    "230" => array ( "area" => "São Vicente", "city" => "Mindelo"),
    "227" => array ( "area" => "Santo Antão", "city" => "Ribeira das Patas (Lajedos and Alto Mira)"),
    "226" => array ( "area" => "Santo Antão", "city" => "Manta Velha and Châ de Igreja"),
    "225" => array ( "area" => "Santo Antão", "city" => "Ponta Sol"),
    "224" => array ( "area" => "Santo Antão", "city" => "Coculi"),
    "223" => array ( "area" => "Santo Antão", "city" => "Paul"),
    "222" => array ( "area" => "Santo Antão", "city" => "Porto Novo"),
    "221" => array ( "area" => "Santo Antão", "city" => "Ribeira Grande")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "238", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Cabo Verde", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7), "International" => "+238 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Cabo Verde phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
