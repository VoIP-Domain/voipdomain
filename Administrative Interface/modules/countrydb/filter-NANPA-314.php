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
 * E.164 United States of America NDC 314 country hook
 */
framework_add_filter ( "e164_identify_NANPA_314", "e164_identify_NANPA_314");

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
function e164_identify_NANPA_314 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 314 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1314")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "980" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MO"),
    "979" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - MO"),
    "906" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "T-MOBILE USA, INC."),
    "900" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "ONVOY, LLC - MO"),
    "883" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - MO"),
    "847" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MO"),
    "825" => array ( "Area" => "Missouri", "City" => "Kirkwood", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "796" => array ( "Area" => "Missouri", "City" => "Creve Coeur", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - MO"),
    "774" => array ( "Area" => "Missouri", "City" => "Kirkwood", "Operator" => "YMAX COMMUNICATIONS CORP. - MO"),
    "759" => array ( "Area" => "Missouri", "City" => "Kirkwood", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "745" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "T-MOBILE USA, INC."),
    "728" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "T-MOBILE USA, INC."),
    "689" => array ( "Area" => "Missouri", "City" => "Kirkwood", "Operator" => "ONVOY, LLC - MO"),
    "688" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "METROPCS, INC."),
    "634" => array ( "Area" => "Missouri", "City" => "Kirkwood", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "627" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "PEERLESS NETWORK OF MISSOURI, LLC - MO"),
    "618" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "ONVOY SPECTRUM, LLC"),
    "597" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "ONVOY, LLC - MO"),
    "586" => array ( "Area" => "Missouri", "City" => "Kirkwood", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "585" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "T-MOBILE USA, INC."),
    "582" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "ONVOY, LLC - MO"),
    "547" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - MO"),
    "532" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "519" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "MCIMETRO ACCESS TRANSMISSION SERVICES LLC - MO"),
    "474" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "BANDWIDTH.COM CLEC, LLC - MO"),
    "437" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "337" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "327" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "314" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "ONVOY, LLC - MO"),
    "310" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "ONVOY, LLC - MO"),
    "305" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "SPRINT SPECTRUM, L.P."),
    "295" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "285" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "273" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "SOUTHWESTERN BELL"),
    "271" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "ONVOY, LLC - MO"),
    "251" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "SOUTHWESTERN BELL"),
    "250" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "248" => array ( "Area" => "Missouri", "City" => "Mehlville", "Operator" => "ONVOY, LLC - MO"),
    "243" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - IL"),
    "237" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "ONVOY, LLC - MO"),
    "228" => array ( "Area" => "Missouri", "City" => "Creve Coeur", "Operator" => "ONVOY, LLC - MO"),
    "207" => array ( "Area" => "Missouri", "City" => "Saint Louis", "Operator" => "ONVOY, LLC - MO"),
    "201" => array ( "Area" => "Missouri", "City" => "Ladue (St Louis)", "Operator" => "T-MOBILE USA, INC.")
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
