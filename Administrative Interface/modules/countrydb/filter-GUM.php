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
 * related to country database of Guam.
 *
 * Reference: https://www.itu.int/oth/T0202000059/en (2006-07-20)
 * Reference: https://nationalnanpa.com/pdf_previous/08_02_99/pl_nanp_004.pdf
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
 * E.164 Guam country hook
 */
framework_add_filter ( "e164_identify_country_GUM", "e164_identify_country_GUM");

/**
 * E.164 Guam area number identification hook. This hook is an e164_identify sub
 * hook, called when the ISO3166 Alpha3 are "GUM" (code for Guam). This hook will
 * verify if phone number is valid, returning the area code, area name, phone
 * number, others number related information and if possible, the number type
 * (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_GUM ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Guam
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1671")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Guam has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "998" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "997" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "988" => array ( "area" => "Agana", "operator" => "Guam Wireless Telephone Company"),
    "987" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "977" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "972" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "967" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "929" => array ( "area" => "Agana Heights", "operator" => "Guam Wireless Telephone Company"),
    "898" => array ( "area" => "Santa Rita", "operator" => "Choice Phone LLC Dba Iconn"),
    "888" => array ( "area" => "Agana", "operator" => "Choice Phone LLC Dba Iconn"),
    "878" => array ( "area" => "Agana Heights", "operator" => "Choice Phone LLC Dba Iconn"),
    "868" => array ( "area" => "Santa Rita", "operator" => "Choice Phone LLC Dba Iconn"),
    "858" => array ( "area" => "Agana Heights", "operator" => "Wave Runner"),
    "848" => array ( "area" => "Agana Heights", "operator" => "Wave Runner"),
    "838" => array ( "area" => "Santa Rita", "operator" => "Wave Runner"),
    "797" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "788" => array ( "area" => "Agana", "operator" => "Guam Wireless Telephone Company"),
    "787" => array ( "area" => "Agana", "operator" => "Guam Wireless Telephone Company"),
    "777" => array ( "area" => "Agana", "operator" => "PTI Pacifica Inc."),
    "747" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "727" => array ( "area" => "Agana", "operator" => "PTI Pacifica Inc."),
    "707" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "689" => array ( "area" => "Agana", "operator" => "Guam Wireless Telephone Company"),
    "688" => array ( "area" => "Agana", "operator" => "Guam Wireless Telephone Company"),
    "687" => array ( "area" => "Agana", "operator" => "Guam Wireless Telephone Company"),
    "686" => array ( "area" => "Agana", "operator" => "Guam Wireless Telephone Company"),
    "685" => array ( "area" => "Agana Heights", "operator" => "Guam Wireless Telephone Company"),
    "678" => array ( "area" => "Agana", "operator" => "PTI Pacifica Inc."),
    "489" => array ( "area" => "Agana Heights", "operator" => "Pulse Mobile LLC"),
    "488" => array ( "area" => "Agana", "operator" => "Pulse Mobile LLC"),
    "483" => array ( "area" => "Agana", "operator" => "Pulse Mobile LLC"),
    "482" => array ( "area" => "Agana", "operator" => "Pulse Mobile LLC")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1671", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Guam", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 671 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * Check for fixed line network with 3 digits NDC and 4 digits SN
   */
  $prefixes = array (
    "989" => array ( "area" => "Agana Heights", "operator" => "Guam Telecom"),
    "979" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc. Dba It&e"),
    "971" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc."),
    "969" => array ( "area" => "Agana Heights", "operator" => "Guam Telecom"),
    "922" => array ( "area" => "Agana Heights", "operator" => "PTI Pacifica Inc. Dba It&e"),
    "864" => array ( "area" => "Agana", "operator" => "Pulse Mobile LLC"),
    "828" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "789" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "735" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "734" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "726" => array ( "area" => "Agana", "operator" => "PTI Pacifica Inc. Dba It&e"),
    "654" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "653" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "649" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "648" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "647" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "646" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "645" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "644" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "642" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "638" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "637" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "635" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "634" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "633" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "632" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "588" => array ( "area" => "Agana Heights", "operator" => "Pulse Mobile LLC"),
    "565" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "564" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "563" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "562" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "487" => array ( "area" => "Agana Heights", "operator" => "Pulse Mobile LLC"),
    "486" => array ( "area" => "Agana", "operator" => "Pulse Mobile LLC"),
    "479" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "478" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "477" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "475" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "474" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "473" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "472" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "471" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "456" => array ( "area" => "Agana", "operator" => "PTI Pacifica Inc."),
    "400" => array ( "area" => "Agana Heights", "operator" => "Pacific Data Systems"),
    "366" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "362" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "355" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "349" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "344" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "343" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "339" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "333" => array ( "area" => "Agana", "operator" => "Teleguam Holdings"),
    "300" => array ( "area" => "Agana Heights", "operator" => "Pacific Data Systems")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "1671", "NDC" => substr ( $parameters["Number"], 5, 3), "Country" => "Guam", "Area" => $data["area"], "City" => "", "Operator" => $data["operator"], "SN" => substr ( $parameters["Number"], 8), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8), "International" => "+1 671 " . substr ( $parameters["Number"], 5, 3) . " " . substr ( $parameters["Number"], 8))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Guam phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
