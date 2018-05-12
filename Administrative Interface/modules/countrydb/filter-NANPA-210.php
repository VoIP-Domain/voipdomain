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
 * E.164 United States of America NDC 210 country hook
 */
framework_add_filter ( "e164_identify_NANPA_210", "e164_identify_NANPA_210");

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
function e164_identify_NANPA_210 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 210 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1210")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "997" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "995" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "992" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "990" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "986" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "985" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "984" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "983" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "975" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "972" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "965" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "964" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "956" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "953" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "947" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "942" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "939" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "936" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "934" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "931" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "929" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "919" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "914" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "METROPCS, INC."),
    "909" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "902" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "895" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "873" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "METROPCS, INC."),
    "855" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "847" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "818" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SPRINT SPECTRUM, L.P."),
    "817" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "816" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "815" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "808" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "803" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "802" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "799" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "796" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "795" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "793" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "792" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "790" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "779" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "778" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "777" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "773" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "772" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "768" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "766" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "763" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "760" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "756" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "753" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "751" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "749" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "719" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "METROPCS, INC."),
    "717" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SPRINT SPECTRUM, L.P."),
    "709" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "708" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SPRINT SPECTRUM, L.P."),
    "707" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "703" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "689" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "676" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - TX"),
    "668" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "665" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "644" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "636" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "631" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "609" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "605" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "597" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - TX"),
    "584" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "583" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - TX"),
    "574" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "METROPCS, INC."),
    "570" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "552" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "550" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "548" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "542" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "540" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "539" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "505" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "500" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "486" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "480" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "469" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "BANDWIDTH.COM CLEC, LLC - TX"),
    "466" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "464" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "459" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "454" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "T-MOBILE USA, INC."),
    "450" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "449" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "440" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "430" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "427" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "425" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "423" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "420" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "419" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "409" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "407" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "405" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "401" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "YMAX COMMUNICATIONS CORP. - TX"),
    "400" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "371" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "361" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "ONVOY, LLC - TX"),
    "356" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "SOUTHWESTERN BELL"),
    "330" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "327" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "322" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "312" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "LEVEL 3 OF TELECOM OF TEXAS, LLC - TX"),
    "309" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "303" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "300" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "284" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "243" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "238" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - TX"),
    "234" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "214" => array ( "Area" => "Texas", "City" => "San Antonio", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL")
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
