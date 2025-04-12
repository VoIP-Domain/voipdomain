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
 * related to country database.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CountryDB
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Check if country code exist
 */
framework_add_filter ( "get_countries", "get_countries");

/**
 * Function to check if a country exist into database.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return mixed Country database entry if exists, otherwise boolean false
 */
function get_countries ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for parameters to query
   */
  $where = "";
  if ( array_key_exists ( "Code", $parameters))
  {
    $where .= " AND `Code` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Code"]);
  }
  if ( array_key_exists ( "Name", $parameters))
  {
    $where .= " AND `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( str_replace ( " ", "%", trim ( strip_tags ( $parameters["Name"])))) . "%'";
  }
  if ( array_key_exists ( "Alpha2", $parameters))
  {
    $where .= " AND `Alpha2` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Alpha2"]) . "'";
  }
  if ( array_key_exists ( "Alpha3", $parameters))
  {
    $where .= " AND `Alpha3` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Alpha3"]) . "'";
  }
  if ( array_key_exists ( "RegionCode", $parameters))
  {
    $where .= " AND `RegionCode` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["RegionCode"]);
  }
  if ( array_key_exists ( "SubRegionCode", $parameters))
  {
    $where .= " AND `SubRegionCode` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["SubRegionCode"]);
  }
  if ( array_key_exists ( "ISO3166-2", $parameters))
  {
    $where .= " AND `ISO3166-2` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ISO3166-2"]) . "'";
  }

  /**
   * Search countries
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Countries`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "")))
  {
    while ( $country = $result->fetch_assoc ())
    {
      $data[] = $country;
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * E.164 hooks
 */
framework_add_filter ( "e164_identify", "e164_identify");

/**
 * E.164 number identification hook. This hook try to identify an E.164 number to
 * check if it is valid, which country it's from, area code, if it's mobile or
 * landline number and other information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check if number is valid
   */
  if ( ! validate_E164 ( $parameters["Number"]))
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Initialize data
   */
  $data = array ();
  $data["E164"] = $parameters["Number"];

  /**
   * First, try to identify the country
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Countries`.*, `CountryCodes`.`Code` AS `CountryCode` FROM `Countries` RIGHT JOIN `CountryCodes` ON `Countries`.`Code` = `CountryCodes`.`Country` ORDER BY LENGTH(`CountryCode`) DESC, `CountryCode` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $country = $result->fetch_assoc ())
  {
    if ( substr ( $parameters["Number"], 1, strlen ( $country["CountryCode"])) == $country["CountryCode"])
    {
      $data["Country"] = array ( "Code" => $country["Code"], "Name" => $country["Name"], "Alpha2" => $country["Alpha2"], "Alpha3" => $country["Alpha3"], "ISO3166-2" => $country["ISO3166-2"], "Region" => array ( "Code" => $country["RegionCode"], "Name" => $country["Region"]), "SubRegion" => array ( "Code" => $country["SubRegionCode"], "Name" => $country["SubRegion"]));

      /**
       * Check if filter country exists
       */
      if ( ! array_key_exists ( $country["Alpha3"], $_filters) && file_exists ( dirname ( __FILE__) . "/filter-" . $country["Alpha3"] . ".php"))
      {
        require_once ( dirname ( __FILE__) . "/filter-" . $country["Alpha3"] . ".php");
      }
      $subdata = filters_call ( "e164_identify_country_" . $country["Alpha3"], array ( "Number" => $parameters["Number"], "Country" => $country));
      $data["Number"] = is_array ( $subdata) ? $subdata : array ();
      break;
    }
  }

  /**
   * Return data to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
