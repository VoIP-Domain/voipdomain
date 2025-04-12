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
 * related to country database of Bolivia.
 *
 * Reference: https://www.itu.int/oth/T020200001A/en (2006-07-20)
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
 * E.164 Bolivia country hook
 */
framework_add_filter ( "e164_identify_country_BOL", "e164_identify_country_BOL");

/**
 * E.164 Bolivian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "BOL" (code for Bolivia). This
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
function e164_identify_country_BOL ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Bolivia
   */
  if ( substr ( $parameters["Number"], 0, 4) != "+591")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Bolivia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 1 digit NDC and 7 digits SN
   */
  $prefixes = array (
    "7291" => array ( "area" => "Pando", "city" => "Cobija", "operator" => "Entel GSM"),
    "7284" => array ( "area" => "Beni", "city" => "Trinidad", "operator" => "Entel GSM"),
    "7245" => array ( "area" => "Oruro", "city" => "Oruro", "operator" => "Entel GSM"),
    "7241" => array ( "area" => "Potosí", "city" => "Potosí", "operator" => "Entel GSM"),
    "7240" => array ( "area" => "Potosí", "city" => "Potosí", "operator" => "Entel GSM"),
    "7197" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Entel TDMA"),
    "7196" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Entel TDMA"),
    "7195" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Entel TDMA"),
    "7194" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Entel TDMA"),
    "7189" => array ( "area" => "Tarija", "city" => "Tarija", "operator" => "Entel TDMA"),
    "7188" => array ( "area" => "Oruro", "city" => "Oruro", "operator" => "Entel TDMA"),
    "7181" => array ( "area" => "Potosí", "city" => "Potosí", "operator" => "Entel TDMA"),
    "7114" => array ( "area" => "Beni", "city" => "Trinidad", "operator" => "Entel TDMA"),
    "7113" => array ( "area" => "Beni", "city" => "Trinidad", "operator" => "Entel TDMA"),
    "7112" => array ( "area" => "Beni", "city" => "Trinidad", "operator" => "Entel TDMA"),
    "7111" => array ( "area" => "Pando", "city" => "Cobija", "operator" => "Entel TDMA"),
    "775" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Telecel"),
    "774" => array ( "area" => "", "city" => "Trinidad and Cochabamba and Potosí", "operator" => "Telecel"),
    "773" => array ( "area" => "Santa Cruz", "city" => "Santa Cruz and Puerto Suarez", "operator" => "Telecel"),
    "772" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Telecel"),
    "771" => array ( "area" => "", "city" => "Sucre and Oruro and Cobija and Tarija", "operator" => "Telecel"),
    "770" => array ( "area" => "Santa Cruz", "city" => "Santa Cruz", "operator" => "Telecel"),
    "729" => array ( "area" => "Tarija", "city" => "Tarija", "operator" => "Entel GSM"),
    "728" => array ( "area" => "Chuquisaca", "city" => "Sucre", "operator" => "Entel GSM"),
    "722" => array ( "area" => "Cochabamba", "city" => "Cochabamba", "operator" => "Entel GSM"),
    "721" => array ( "area" => "Santa Cruz", "city" => "Santa Cruz", "operator" => "Entel GSM"),
    "720" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Entel GSM"),
    "719" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Entel TDMA"),
    "718" => array ( "area" => "", "city" => "Oruro and Potosí and Tarija", "operator" => "Entel TDMA"),
    "717" => array ( "area" => "Cochabamba", "city" => "Cochabamba", "operator" => "Entel TDMA"),
    "716" => array ( "area" => "Santa Cruz", "city" => "Santa Cruz", "operator" => "Entel TDMA"),
    "715" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Entel TDMA"),
    "714" => array ( "area" => "Cochabamba", "city" => "Cochabamba", "operator" => "Entel TDMA"),
    "711" => array ( "area" => "Chuquisaca", "city" => "Sucre", "operator" => "Entel TDMA"),
    "710" => array ( "area" => "Santa Cruz", "city" => "Santa Cruz", "operator" => "Entel TDMA"),
    "708" => array ( "area" => "Santa Cruz", "city" => "Santa Cruz", "operator" => "Viva GSM"),
    "707" => array ( "area" => "Cochabamba", "city" => "Cochabamba", "operator" => "Viva GSM"),
    "706" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Viva GSM")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "591", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Bolivia", "Area" => $data["area"], "City" => $data["city"], "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5), "International" => "+591 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 1 digit NDC and 7 digits SN
   */
  $prefixes = array (
    "4691" => array ( "area" => "Chuquisaca", "city" => "", "operator" => "Entel"),
    "4672" => array ( "area" => "Tarija", "city" => "Villamontes", "operator" => "Entel"),
    "4647" => array ( "area" => "Chuquisaca", "city" => "Monteagudo", "operator" => "Entel"),
    "4611" => array ( "area" => "Tarija", "city" => "", "operator" => "Entel"),
    "4413" => array ( "area" => "Cochabamba", "city" => "", "operator" => "Entel"),
    "4412" => array ( "area" => "Cochabamba", "city" => "", "operator" => "Entel"),
    "4411" => array ( "area" => "Cochabamba", "city" => "", "operator" => "Entel"),
    "3979" => array ( "area" => "Santa Cruz", "city" => "San Matías", "operator" => "Cotas"),
    "3974" => array ( "area" => "Santa Cruz", "city" => "Robore", "operator" => "Cotas"),
    "3972" => array ( "area" => "Santa Cruz", "city" => "San Jose De Chiquitos", "operator" => "Cotas"),
    "3966" => array ( "area" => "Santa Cruz", "city" => "Ascención De Guarayos and Poza Honda", "operator" => "Cotas"),
    "3965" => array ( "area" => "Santa Cruz", "city" => "Cuatro Cañadas and San Julian and San Ramón", "operator" => "Cotas"),
    "3964" => array ( "area" => "Santa Cruz", "city" => "Concepción", "operator" => "Cotas"),
    "3954" => array ( "area" => "Santa Cruz", "city" => "Gutiérrez and Charagua", "operator" => "Cotas"),
    "3963" => array ( "area" => "Santa Cruz", "city" => "San Javier", "operator" => "Cotas"),
    "3962" => array ( "area" => "Santa Cruz", "city" => "San Miguel De Velasco and San Rafael De Velasco and San Ignacio De Velasco", "operator" => "Cotas"),
    "3952" => array ( "area" => "Santa Cruz", "city" => "Camirí", "operator" => "Cotas"),
    "3948" => array ( "area" => "Santa Cruz", "city" => "Mairana", "operator" => "Cotas"),
    "3944" => array ( "area" => "Santa Cruz", "city" => "Samaipata", "operator" => "Cotas"),
    "3942" => array ( "area" => "Santa Cruz", "city" => "Vallegrande", "operator" => "Cotas"),
    "3934" => array ( "area" => "Santa Cruz", "city" => "Santa Fe and San Juan 1 Y 2", "operator" => "Cotas"),
    "3933" => array ( "area" => "Santa Cruz", "city" => "Yapacani", "operator" => "Cotas"),
    "3932" => array ( "area" => "Santa Cruz", "city" => "Buena Vista", "operator" => "Cotas"),
    "3924" => array ( "area" => "Santa Cruz", "city" => "Azubi and Chane Independencia and Hardemann and Loma Alta and Minero and Portachuelo and Puerto Fernandez and Sagrado Corazón and San Pedro", "operator" => "Cotas"),
    "3923" => array ( "area" => "Santa Cruz", "city" => "Warnes and La Bélgica and Los Chacos and Okinawa 1, 2 Y 3", "operator" => "Cotas"),
    "3895" => array ( "area" => "Beni", "city" => "San Borja", "operator" => "Entel"),
    "3892" => array ( "area" => "Beni", "city" => "Rurrenabaque and San Buena Ventura", "operator" => "Entel"),
    "3886" => array ( "area" => "Beni", "city" => "Magdalena", "operator" => "Entel"),
    "3857" => array ( "area" => "Beni", "city" => "Riberalta", "operator" => "Entel"),
    "3855" => array ( "area" => "Beni", "city" => "Guayaramerín", "operator" => "Cotegua"),
    "3852" => array ( "area" => "Beni", "city" => "Riberalta", "operator" => "Coteri"),
    "3842" => array ( "area" => "Pando", "city" => "Cobija", "operator" => "Coteco"),
    "3825" => array ( "area" => "Beni", "city" => "Reyes", "operator" => "Entel"),
    "3484" => array ( "area" => "Beni", "city" => "Santa Ana De Yacuma", "operator" => "Cotemo"),
    "3482" => array ( "area" => "Beni", "city" => "San Ignacio De Moxos", "operator" => "Entel"),
    "3313" => array ( "area" => "Santa Cruz", "city" => "", "operator" => "Entel"),
    "3312" => array ( "area" => "Santa Cruz", "city" => "", "operator" => "Entel"),
    "3311" => array ( "area" => "Santa Cruz", "city" => "", "operator" => "Entel"),
    "2862" => array ( "area" => "La Paz", "city" => "Copacabana", "operator" => "Entel"),
    "2839" => array ( "area" => "La Paz", "city" => "Patacamaya", "operator" => "Entel"),
    "2824" => array ( "area" => "La Paz", "city" => "Caranavi", "operator" => "Entel"),
    "2823" => array ( "area" => "La Paz", "city" => "Caranavi", "operator" => "Cotecar"),
    "2694" => array ( "area" => "Potosí", "city" => "Tupiza", "operator" => "Entel"),
    "2693" => array ( "area" => "Potosí", "city" => "Uyuni", "operator" => "Entel"),
    "2612" => array ( "area" => "Potosí", "city" => "", "operator" => "Entel"),
    "2597" => array ( "area" => "Potosí", "city" => "Villazón", "operator" => "Entel"),
    "2596" => array ( "area" => "Potosí", "city" => "Villazón", "operator" => "Cotevi"),
    "2557" => array ( "area" => "Oruro", "city" => "Challapata", "operator" => "Entel"),
    "2511" => array ( "area" => "Oruro", "city" => "", "operator" => "Entel"),
    "2213" => array ( "area" => "La Paz", "city" => "", "operator" => "Entel"),
    "2212" => array ( "area" => "La Paz", "city" => "", "operator" => "Entel"),
    "2211" => array ( "area" => "La Paz", "city" => "", "operator" => "Entel"),
    "469" => array ( "area" => "Tarija", "city" => "Bermejo", "operator" => "Cotabe"),
    "468" => array ( "area" => "Tarija", "city" => "Yacuiba", "operator" => "Entel"),
    "466" => array ( "area" => "Tarija", "city" => "Tarija", "operator" => "Cosett"),
    "464" => array ( "area" => "Chuquisaca", "city" => "Sucre", "operator" => "Cotes"),
    "462" => array ( "area" => "Chuquisaca", "city" => "Camargo", "operator" => "Entel"),
    "397" => array ( "area" => "Santa Cruz", "city" => "Puerto Quijarro and Puerto Suarez", "operator" => "Cotas"),
    "392" => array ( "area" => "Santa Cruz", "city" => "Montero", "operator" => "Cotas"),
    "346" => array ( "area" => "Beni", "city" => "Trinidad", "operator" => "Coteautri"),
    "262" => array ( "area" => "Potosí", "city" => "Potosí", "operator" => "Cotap"),
    "258" => array ( "area" => "Oruro", "city" => "Llallagua", "operator" => "Entel"),
    "255" => array ( "area" => "Oruro", "city" => "Huanuni", "operator" => "Entel"),
    "252" => array ( "area" => "Oruro", "city" => "Oruro", "operator" => "Coteor"),
    "44" => array ( "area" => "Cochabamba", "city" => "Cochabamba and Quillacollo", "operator" => "Comteco"),
    "33" => array ( "area" => "Santa Cruz", "city" => "Comarapa and Cotoca and Jorochito 1 Y 2 and La Guardia and Paurito and Porongo and Santa Cruz and San Luis and Tiquipaya and Viru Viru", "operator" => "Cotas"),
    "22" => array ( "area" => "La Paz", "city" => "La Paz", "operator" => "Cotel")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "591", "NDC" => substr ( $parameters["Number"], 4, 1), "Country" => "Bolivia", "Area" => $data["area"], "City" => $data["city"], "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5), "International" => "+591 " . substr ( $parameters["Number"], 4, 1) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Bolivian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
