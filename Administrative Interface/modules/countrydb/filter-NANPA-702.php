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
 * E.164 United States of America NDC 702 country hook
 */
framework_add_filter ( "e164_identify_NANPA_702", "e164_identify_NANPA_702");

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
function e164_identify_NANPA_702 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 702 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1702")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "997" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - NV"),
    "994" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "980" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "METROPCS, INC."),
    "962" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CENTRAL TEL. CO. - NEVADA DBA CENTURYLINK"),
    "961" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CENTRAL TEL. CO. - NEVADA DBA CENTURYLINK"),
    "937" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "934" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "927" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "907" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "BANDWIDTH.COM CLEC, LLC - NV"),
    "890" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "886" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "885" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "883" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "881" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "852" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "XO NEVADA, LLC"),
    "850" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "BANDWIDTH.COM CLEC, LLC - NV"),
    "841" => array ( "Area" => "Nevada", "City" => "Searchlight", "Operator" => "XO NEVADA, LLC"),
    "828" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CENTRAL TEL. CO. - NEVADA DBA CENTURYLINK"),
    "819" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "817" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "814" => array ( "Area" => "Nevada", "City" => "Searchlight", "Operator" => "XO NEVADA, LLC"),
    "805" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "BANDWIDTH.COM CLEC, LLC - NV"),
    "801" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "METROPCS, INC."),
    "783" => array ( "Area" => "Nevada", "City" => "Mount Charleston", "Operator" => "XO NEVADA, LLC"),
    "782" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "778" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "COMCAST IP PHONE, LLC"),
    "773" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "772" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "771" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "766" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "764" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "762" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "755" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "753" => array ( "Area" => "Nevada", "City" => "Searchlight", "Operator" => "XO NEVADA, LLC"),
    "752" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "746" => array ( "Area" => "Nevada", "City" => "Searchlight", "Operator" => "XO NEVADA, LLC"),
    "742" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "741" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "729" => array ( "Area" => "Nevada", "City" => "Mount Charleston", "Operator" => "XO NEVADA, LLC"),
    "718" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "715" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "710" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "702" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "ONVOY, LLC - NV"),
    "695" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "689" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "METROPCS, INC."),
    "687" => array ( "Area" => "Nevada", "City" => "Laughlin", "Operator" => "XO NEVADA, LLC"),
    "685" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "COMCAST IP PHONE, LLC"),
    "663" => array ( "Area" => "Nevada", "City" => "Nelson", "Operator" => "XO NEVADA, LLC"),
    "660" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "BANDWIDTH.COM CLEC, LLC - NV"),
    "627" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "XO NEVADA, LLC"),
    "625" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "BANDWIDTH.COM CLEC, LLC - NV"),
    "624" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "623" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "BANDWIDTH.COM CLEC, LLC - NV"),
    "622" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "621" => array ( "Area" => "Nevada", "City" => "Jean", "Operator" => "XO NEVADA, LLC"),
    "619" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "METROPCS, INC."),
    "618" => array ( "Area" => "Nevada", "City" => "Searchlight", "Operator" => "XO NEVADA, LLC"),
    "608" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "BANDWIDTH.COM CLEC, LLC - NV"),
    "607" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CENTRAL TEL. CO. - NEVADA DBA CENTURYLINK"),
    "606" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "601" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "600" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "586" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "COMCAST IP PHONE, LLC"),
    "569" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "559" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "METROPCS, INC."),
    "542" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "METROPCS, INC."),
    "540" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "539" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "536" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "XO NEVADA, LLC"),
    "533" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "532" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "XO NEVADA, LLC"),
    "519" => array ( "Area" => "Nevada", "City" => "Laughlin", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC"),
    "518" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "BANDWIDTH.COM CLEC, LLC - NV"),
    "516" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "YMAX COMMUNICATIONS CORP. - NV"),
    "512" => array ( "Area" => "Nevada", "City" => "Jean", "Operator" => "XO NEVADA, LLC"),
    "504" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "METROPCS, INC."),
    "502" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "METROPCS, INC."),
    "488" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "476" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "COMCAST IP PHONE, LLC"),
    "469" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "468" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "467" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "465" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "463" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "COMCAST IP PHONE, LLC"),
    "449" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "427" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "426" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "423" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "421" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "YMAX COMMUNICATIONS CORP. - NV"),
    "417" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "409" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC"),
    "402" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CENTRAL TEL. CO. - NEVADA DBA CENTURYLINK"),
    "356" => array ( "Area" => "Nevada", "City" => "Nelson", "Operator" => "XO NEVADA, LLC"),
    "329" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "BANDWIDTH.COM CLEC, LLC - NV"),
    "305" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - NV"),
    "276" => array ( "Area" => "Nevada", "City" => "Mount Charleston", "Operator" => "XO NEVADA, LLC"),
    "237" => array ( "Area" => "Nevada", "City" => "Las Vegas", "Operator" => "T-MOBILE USA, INC."),
    "200" => array ( "Area" => "Nevada", "City" => "Nelson", "Operator" => "XO NEVADA, LLC")
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
