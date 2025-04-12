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
 * related to country database of Portugal.
 *
 * Reference: https://www.itu.int/oth/T02020000A9/en (2013-10-29)
 *            https://www.anacom.pt/pnn/pnnPlanosSelectAll.do?hc=off&channel=graphic&jscript=on&languageId=0&ssl=false&dataInicioDia=dd&dataInicioMes=mm&dataInicioAno=yyyy&dataFimDia=dd&dataFimMes=mm&dataFimAno=yyyy&assunto=Introduzir+qualquer+c%F3digo%2C+designa%E7%E3 (2022-12-27)
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
 * E.164 Portugal country hook
 */
framework_add_filter ( "e164_identify_country_PRT", "e164_identify_country_PRT");

/**
 * E.164 Portuguese area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "PRT" (code for
 * Portugal). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_PRT ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Portugal
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+351")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Portugal has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "96",
    "93",
    "92",
    "91"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "351", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Portugal", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+351 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for fixed line network with 2 or 3 digits NDC and 6 or 7 digits SN
   */
  $prefixes = array (
    "296" => "Ponta Delgada",
    "295" => "Angra do Heroísmo",
    "292" => "Horta",
    "291" => "Funchal",
    "289" => "Faro",
    "286" => "Castro Verde",
    "285" => "Moura",
    "284" => "Beja",
    "283" => "Odemira",
    "282" => "Portimão",
    "281" => "Tavira",
    "279" => "Moncorvo",
    "278" => "Mirandela",
    "277" => "Idanha-a-Nova",
    "276" => "Chaves",
    "275" => "Covilhã",
    "274" => "Proença-a-Nova",
    "273" => "Bragança",
    "272" => "Castelo Branco",
    "271" => "Guarda",
    "269" => "Santiago do Cacém",
    "268" => "Estremoz",
    "266" => "Évora",
    "265" => "Setúbal",
    "263" => "V. Franca de Xira",
    "262" => "Caldas da Raínha",
    "261" => "Torres Vedras",
    "259" => "Vila Real",
    "258" => "Viana do Castelo",
    "256" => "S. João da Madeira",
    "255" => "Penafiel",
    "254" => "Peso da Régua",
    "257" => "Braga",
    "253" => "Braga",
    "252" => "V. N. de Famalicão",
    "251" => "Valença",
    "249" => "Torres Novas",
    "245" => "Portalegre",
    "244" => "Leiria",
    "243" => "Santarém",
    "242" => "Ponte de Sôr",
    "241" => "Abrantes",
    "239" => "Coimbra",
    "238" => "Seia",
    "236" => "Pombal",
    "235" => "Arganil",
    "234" => "Aveiro",
    "233" => "Figueira da Foz",
    "232" => "Viseu",
    "231" => "Mealhada",
    "22" => "Porto",
    "21" => "Lisboa"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "351", "NDC" => $prefix, "Country" => "Portugal", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 4 + strlen ( $prefix)), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+351 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for PRN network with 2 digits NDC and 7 digits SN
   */
  $prefixes = array (
    "762",
    "761",
    "760"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "351", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Portugal", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_PRN, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+351 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * Check for Tollfree network with 3 digits NDC and 6 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 4) == "8008")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "351", "NDC" => substr ( $parameters["Number"], 4, 3), "Country" => "Portugal", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 7), "Type" => VD_PHONETYPE_TOLLFREE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+351 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
  }

  /**
   * Check for VoIP network with 2 digits NDC and 7 digits SN
   */
  if ( substr ( $parameters["Number"], 4, 2) == "30")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "351", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Portugal", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_VOIP, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10), "International" => "+351 " . substr ( $parameters["Number"], 4, 3) . " " . substr ( $parameters["Number"], 7, 3) . " " . substr ( $parameters["Number"], 10))));
  }

  /**
   * If reached here, number wasn't identified as a valid Portuguese phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
