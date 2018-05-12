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
 * E.164 United States of America NDC 910 country hook
 */
framework_add_filter ( "e164_identify_NANPA_910", "e164_identify_NANPA_910");

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
function e164_identify_NANPA_910 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 910 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1910")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "999" => array ( "Area" => "North Carolina", "City" => "Carolina Beach", "Operator" => "ONVOY, LLC - NC"),
    "963" => array ( "Area" => "North Carolina", "City" => "Wilmington", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "951" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "CAROLINA TEL AND TEL CO., LLC DBA CENTURYLINK"),
    "927" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "CAROLINA TEL AND TEL CO., LLC DBA CENTURYLINK"),
    "925" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "WINDSTREAM COMMUNICATIONS, INC. - NC"),
    "910" => array ( "Area" => "North Carolina", "City" => "Jacksonville", "Operator" => "ONVOY, LLC - NC"),
    "908" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "LEVEL 3 TELECOM OF NORTH CAROLINA, LP - NC"),
    "905" => array ( "Area" => "North Carolina", "City" => "Laurinburg", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "900" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "ONVOY, LLC - NC"),
    "888" => array ( "Area" => "North Carolina", "City" => "Scotts Hill", "Operator" => "ONVOY, LLC - NC"),
    "883" => array ( "Area" => "North Carolina", "City" => "Rockingham", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "873" => array ( "Area" => "North Carolina", "City" => "Rockingham", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "819" => array ( "Area" => "North Carolina", "City" => "Wilmington", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "771" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - NC"),
    "769" => array ( "Area" => "North Carolina", "City" => "Wilmington", "Operator" => "TIME WARNER CBL INFO SVC (NC) DBA TIME WARNER CBL"),
    "760" => array ( "Area" => "North Carolina", "City" => "Clinton", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "747" => array ( "Area" => "North Carolina", "City" => "Clinton", "Operator" => "SUNCOM DBA T-MOBILE USA"),
    "731" => array ( "Area" => "North Carolina", "City" => "Rockingham", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "715" => array ( "Area" => "North Carolina", "City" => "Pinehurst", "Operator" => "CAROLINA TEL AND TEL CO., LLC DBA CENTURYLINK"),
    "709" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NC"),
    "662" => array ( "Area" => "North Carolina", "City" => "Wilmington", "Operator" => "DELTACOM, INC. - NC"),
    "656" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - NC"),
    "651" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NC"),
    "643" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "LEVEL 3 TELECOM OF NORTH CAROLINA, LP - NC"),
    "615" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "CAROLINA TEL AND TEL CO., LLC DBA CENTURYLINK"),
    "598" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - NC"),
    "589" => array ( "Area" => "North Carolina", "City" => "Wilmington", "Operator" => "BELLSOUTH TELECOMM INC DBA SOUTHERN BELL TEL & TEL"),
    "570" => array ( "Area" => "North Carolina", "City" => "Fayetteville", "Operator" => "LEVEL 3 TELECOM OF NORTH CAROLINA, LP - NC"),
    "440" => array ( "Area" => "North Carolina", "City" => "Jacksonville", "Operator" => "CAROLINA TEL AND TEL CO., LLC DBA CENTURYLINK")
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
