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
 * related to country database of Malaysia.
 *
 * Reference: https://www.itu.int/oth/T0202000081/en (2013-06-12)
 * Reference: https://www.skmm.gov.my/skmmgovmy/media/General/pdf/MCMC_NEAP-2016_3.pdf (2016-10-17)
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
 * E.164 Malaysia country hook
 */
framework_add_filter ( "e164_identify_country_MYS", "e164_identify_country_MYS");

/**
 * E.164 Malaysiaian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "MYS" (code for
 * Malaysia). This hook will verify if phone number is valid, returning the area
 * code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_MYS ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Malaysia
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+60")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Malaysia has 12 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 12)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "60", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Malaysia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+60 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for fixed line network with 2 digits NDC and 6 digits SN
   */
  $prefixes = array (
    "52" => array ( "area" => "Pusing", "operator" => ""),
    "5289" => array ( "area" => "Pusing", "operator" => "Telekom Malaysia Berhad"),
    "5288" => array ( "area" => "Pusing", "operator" => "Telekom Malaysia Berhad"),
    "5287" => array ( "area" => "Pusing", "operator" => "Packet One Networks (M) Sdn Bhd"),
    "5286" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5285" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5282" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5281" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5280" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5256" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5255" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5254" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5253" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5249" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5246" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5245" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5244" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5243" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5242" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5241" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5240" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5237" => array ( "area" => "Ipoh", "operator" => "TT dotCom Sdn Bhd"),
    "5236" => array ( "area" => "Ipoh", "operator" => "TT dotCom Sdn Bhd"),
    "52321" => array ( "area" => "Ipoh", "operator" => "Maxis Broadband Sdn Bhd"),
    "52320" => array ( "area" => "Ipoh", "operator" => "Maxis Broadband Sdn Bhd"),
    "5230" => array ( "area" => "Ipoh", "operator" => "Celcom Axiata Berhad"),
    "5226" => array ( "area" => "Ipoh", "operator" => "Packet One Networks (M) Sdn Bhd"),
    "5225" => array ( "area" => "Ipoh", "operator" => "Packet One Networks (M) Sdn Bhd"),
    "5220" => array ( "area" => "Ipoh", "operator" => "TT dotCom Sdn Bhd"),
    "5212" => array ( "area" => "Ipoh", "operator" => "Jaring Communications Sdn Bhd"),
    "5211" => array ( "area" => "Ipoh", "operator" => "Maxis Broadband Sdn Bhd"),
    "5210" => array ( "area" => "Ipoh", "operator" => "Maxis Broadband Sdn Bhd"),
    "5209" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5208" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5205" => array ( "area" => "Ipoh", "operator" => "Telekom Malaysia Berhad"),
    "5203" => array ( "area" => "Chemor", "operator" => "Telekom Malaysia Berhad"),
    "5201" => array ( "area" => "Chemor", "operator" => "Telekom Malaysia Berhad"),
  );
  foreach ( $prefixes as $prefix => $operator)
  {
    if ( (int) substr ( $parameters["Number"], 4, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "60", "NDC" => substr ( $parameters["Number"], 4, 2), "Country" => "Malaysia", "Area" => "", "City" => "", "Operator" => $operator, "SN" => substr ( $parameters["Number"], 6), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9), "International" => "+60 " . substr ( $parameters["Number"], 4, 2) . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Malaysiaian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
