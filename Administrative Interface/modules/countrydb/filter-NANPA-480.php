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
 * E.164 United States of America NDC 480 country hook
 */
framework_add_filter ( "e164_identify_NANPA_480", "e164_identify_NANPA_480");

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
function e164_identify_NANPA_480 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 480 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1480")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "992" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "944" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "938" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "932" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "920" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY SPECTRUM, LLC"),
    "918" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY SPECTRUM, LLC"),
    "916" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "915" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "914" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "913" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "910" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "908" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "904" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "901" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "887" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - AZ"),
    "886" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "881" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "880" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "876" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "875" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "872" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "870" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "864" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "859" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "853" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "851" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "849" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "848" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "846" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "843" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "828" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "826" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "823" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "819" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "817" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY SPECTRUM, LLC"),
    "815" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "808" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "798" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "788" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "771" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "757" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "744" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "743" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "742" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "T-MOBILE USA, INC."),
    "740" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "737" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "728" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "724" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "721" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "714" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "712" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "709" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "708" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "698" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "680" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "673" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "670" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "662" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "656" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "COMCAST IP PHONE, LLC"),
    "652" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "647" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "640" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "630" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "622" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "617" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "605" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "601" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "594" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "590" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "COMCAST IP PHONE, LLC"),
    "589" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "583" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "COMCAST IP PHONE, LLC"),
    "578" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "574" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "571" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "549" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "544" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "542" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "541" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "535" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "532" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "528" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "521" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "520" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "499" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "XO ARIZONA, INC."),
    "492" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "490" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "489" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "487" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "486" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "485" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "480" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "479" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "470" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "469" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "453" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "METROPCS, INC."),
    "447" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "442" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "440" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "439" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "433" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "432" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "431" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "418" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ONVOY, LLC - AZ"),
    "416" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "414" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "412" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "QWEST CORPORATION"),
    "408" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "405" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - AZ"),
    "402" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - AZ"),
    "399" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "395" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "392" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "382" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "381" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "369" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "364" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "360" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "BANDWIDTH.COM CLEC, LLC - AZ"),
    "356" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "355" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "ELECTRIC LIGHTWAVE LLC DBA ALLSTREAM"),
    "349" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "341" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "340" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "338" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "322" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "318" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "313" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "298" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "286" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "280" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "276" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "271" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "261" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "254" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ"),
    "249" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "235" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "229" => array ( "Area" => "Arizona", "City" => "Phoenix", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - AZ")
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
