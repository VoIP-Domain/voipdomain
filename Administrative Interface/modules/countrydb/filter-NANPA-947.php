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
 * E.164 United States of America NDC 947 country hook
 */
framework_add_filter ( "e164_identify_NANPA_947", "e164_identify_NANPA_947");

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
function e164_identify_NANPA_947 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 947 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1947")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "997" => array ( "Area" => "Michigan", "City" => "Southfield", "Operator" => "METROPCS, INC."),
    "996" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "994" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "987" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "970" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "956" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "939" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "938" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "913" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "885" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "882" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "867" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "862" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "859" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "853" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "851" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "844" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "830" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "829" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "823" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "770" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "768" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "762" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "757" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "755" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "750" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "749" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "744" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "740" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "722" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "678" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "630" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "616" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "577" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "566" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "550" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "539" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "524" => array ( "Area" => "Michigan", "City" => "Royal Oak", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MI"),
    "523" => array ( "Area" => "Michigan", "City" => "Royal Oak", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MI"),
    "522" => array ( "Area" => "Michigan", "City" => "Royal Oak", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MI"),
    "521" => array ( "Area" => "Michigan", "City" => "Royal Oak", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MI"),
    "520" => array ( "Area" => "Michigan", "City" => "Royal Oak", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MI"),
    "519" => array ( "Area" => "Michigan", "City" => "Royal Oak", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MI"),
    "518" => array ( "Area" => "Michigan", "City" => "Royal Oak", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MI"),
    "509" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "505" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "489" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "485" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "470" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "456" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "444" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "433" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "428" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "420" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "419" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "399" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "387" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "381" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "361" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "344" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "326" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "313" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "309" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "289" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "288" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "272" => array ( "Area" => "Michigan", "City" => "Pontiac", "Operator" => "ONVOY, LLCV - MI"),
    "264" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "256" => array ( "Area" => "Michigan", "City" => "Milford White Lake", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "255" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "250" => array ( "Area" => "Michigan", "City" => "Farmington", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "241" => array ( "Area" => "Michigan", "City" => "Southfield", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "220" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "213" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "212" => array ( "Area" => "Michigan", "City" => "Clarkston", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "206" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI"),
    "202" => array ( "Area" => "Michigan", "City" => "Ortonville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - MI")
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
