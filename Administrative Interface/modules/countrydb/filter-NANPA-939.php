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
 * related to country database of United States of America.
 *
 * Reference: https://www.itu.int/oth/T02020000DE/en (2006-11-22)
 *            https://www.nationalpooling.com/reports/region/AllBlocksAugmentedReport.zip (2023-02-13)
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
 * E.164 United States of America NDC 939 country hook
 */
framework_add_filter ( "e164_identify_NANPA_939", "e164_identify_NANPA_939");

/**
 * E.164 North American area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "USA" (code for
 * United States of America). This hook will verify if phone number is valid,
 * returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_NANPA_939 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 939 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1939")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "905" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "LIBERTY MOBILE PUERTO RICO INC."),
    "904" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "LIBERTY MOBILE PUERTO RICO INC."),
    "903" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "LIBERTY MOBILE PUERTO RICO INC."),
    "902" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "LIBERTY MOBILE PUERTO RICO INC."),
    "891" => array ( "Area" => "Puerto Rico", "City" => "Fajardo", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "881" => array ( "Area" => "Puerto Rico", "City" => "Trujillo Alto", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "835" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "788" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "777" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "775" => array ( "Area" => "Puerto Rico", "City" => "Trujillo Alto", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "745" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "652" => array ( "Area" => "Puerto Rico", "City" => "Carolina", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "625" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "608" => array ( "Area" => "Puerto Rico", "City" => "San German", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "529" => array ( "Area" => "Puerto Rico", "City" => "Fajardo", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "525" => array ( "Area" => "Puerto Rico", "City" => "San German", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "499" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "498" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "497" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "496" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "494" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "493" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "491" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "490" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "464" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "460" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "458" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "457" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "456" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "454" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "452" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SPRINT SPECTRUM, L.P."),
    "451" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "450" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "449" => array ( "Area" => "Puerto Rico", "City" => "Trujillo Alto", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "438" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Sur", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "429" => array ( "Area" => "Puerto Rico", "City" => "Arecibo", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "428" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "425" => array ( "Area" => "Puerto Rico", "City" => "Naranjito", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "422" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "419" => array ( "Area" => "Puerto Rico", "City" => "Cayey", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "418" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "416" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "415" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "414" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "413" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "410" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "LIBERTY MOBILE PUERTO RICO INC."),
    "408" => array ( "Area" => "Puerto Rico", "City" => "Carolina", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "407" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "405" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "404" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "403" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "402" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "401" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "400" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "395" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "388" => array ( "Area" => "Puerto Rico", "City" => "Guaynabo", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "384" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "366" => array ( "Area" => "Puerto Rico", "City" => "Aguadilla", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "362" => array ( "Area" => "Puerto Rico", "City" => "Cayey", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "356" => array ( "Area" => "Puerto Rico", "City" => "Manati", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "351" => array ( "Area" => "Puerto Rico", "City" => "Carolina", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "350" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SPRINT SPECTRUM, L.P."),
    "349" => array ( "Area" => "Puerto Rico", "City" => "Aguadilla", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "343" => array ( "Area" => "Puerto Rico", "City" => "Naranjito", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "340" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "339" => array ( "Area" => "Puerto Rico", "City" => "Aguadilla", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "338" => array ( "Area" => "Puerto Rico", "City" => "Rio Piedras", "Operator" => "LIBERTY COMMUNICATIONS OF PUERTO RICO LLC"),
    "337" => array ( "Area" => "Puerto Rico", "City" => "Canovanas", "Operator" => "LIBERTY COMMUNICATIONS OF PUERTO RICO LLC"),
    "336" => array ( "Area" => "Puerto Rico", "City" => "Canovanas", "Operator" => "LIBERTY COMMUNICATIONS OF PUERTO RICO LLC"),
    "334" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "331" => array ( "Area" => "Puerto Rico", "City" => "Manati", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "330" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "328" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "327" => array ( "Area" => "Puerto Rico", "City" => "Santurce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "326" => array ( "Area" => "Puerto Rico", "City" => "Carolina", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "325" => array ( "Area" => "Puerto Rico", "City" => "Cayey", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "323" => array ( "Area" => "Puerto Rico", "City" => "Aguadilla", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "321" => array ( "Area" => "Puerto Rico", "City" => "Manati", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "317" => array ( "Area" => "Puerto Rico", "City" => "Carolina", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "314" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "313" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Sur", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "312" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Sur", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "310" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "308" => array ( "Area" => "Puerto Rico", "City" => "Arecibo", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "306" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "305" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "304" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "302" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "301" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "299" => array ( "Area" => "Puerto Rico", "City" => "Mayaguez", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "295" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "292" => array ( "Area" => "Puerto Rico", "City" => "Levittown", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "290" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "289" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "288" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "287" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "286" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "285" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "284" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "283" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "282" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "281" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "280" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "279" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "278" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "277" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "276" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "275" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "274" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "273" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "272" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "271" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "270" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "269" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "268" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "267" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "266" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "265" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "264" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "263" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "262" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "261" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "260" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "259" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "258" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "257" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "256" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "255" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "254" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "253" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "251" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "250" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "249" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "248" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "247" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "246" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "245" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "244" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "243" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "242" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "PUERTO RICO TELCO DBA CLARO PUERTO RICO"),
    "240" => array ( "Area" => "Puerto Rico", "City" => "Manati", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "238" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Sur", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "235" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Sur", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "232" => array ( "Area" => "Puerto Rico", "City" => "Bayamon Norte", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "228" => array ( "Area" => "Puerto Rico", "City" => "San German", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "227" => array ( "Area" => "Puerto Rico", "City" => "Pueblo Viejo", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "224" => array ( "Area" => "Puerto Rico", "City" => "Aguadilla", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "223" => array ( "Area" => "Puerto Rico", "City" => "Carolina", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "222" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "219" => array ( "Area" => "Puerto Rico", "City" => "Arecibo", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "217" => array ( "Area" => "Puerto Rico", "City" => "Ponce", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "213" => array ( "Area" => "Puerto Rico", "City" => "Cayey", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "208" => array ( "Area" => "Puerto Rico", "City" => "Caguas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "205" => array ( "Area" => "Puerto Rico", "City" => "Pueblo Viejo", "Operator" => "TELEFONICA LARGA DISTANCIA DE PUERTO RICO - PR"),
    "204" => array ( "Area" => "Puerto Rico", "City" => "Rio Piedras", "Operator" => "LIBERTY COMMUNICATIONS OF PUERTO RICO LLC")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( is_array ( $buffer) ? $buffer : array (), $data);
    }
  }
  return array_merge_recursive ( is_array ( $buffer) ? $buffer : array (), array ( "Area" => $data["Area"]));
}
