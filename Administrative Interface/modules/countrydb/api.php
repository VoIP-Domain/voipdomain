<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * VoIP Domain country database module. This module provides the country
 * database API to the system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CountryDB
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search countries
 */
framework_add_hook ( "countries_search", "countries_search");
framework_add_permission ( "countries_search", __ ( "Search countries (select list standard)"));
framework_add_api_call ( "/countries/search", "Read", "countries_search", array ( "permissions" => array ( "user", "token")));

/**
 * Function to generate country list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function countries_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Countries");

  /**
   * Search countries
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `Code`, `Name`, `ISO3166-2` FROM `Countries` " . ( ! empty ( $parameters["q"]) ? "WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' OR `ISO3166-2` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "' " : "") . "ORDER BY `Name`"))
  {
    while ( $country = $result->fetch_assoc ())
    {
      $data[] = array ( $country["Code"], $country["Name"] . " (" . $country["ISO3166-2"] . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch countries listing
 */
framework_add_hook ( "countries_fetch", "countries_fetch");
framework_add_permission ( "countries_fetch", __ ( "Request countries listing"));
framework_add_api_call ( "/countries/fetch", "Read", "countries_fetch", array ( "permissions" => array ( "user", "countries_fetch")));

/**
 * Function to generate countries list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function countries_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Countries");

  /**
   * Search countries
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Countries`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create table structure
   */
  $data = array ();
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = array ( $result["Code"], __ ( $result["Name"]) . " (" . $result["Alpha2"] . ")");
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get country information
 */
framework_add_hook ( "countries_view", "countries_view");
framework_add_permission ( "countries_view", __ ( "View countries informations"));
framework_add_api_call ( "/countries/:code", "Read", "countries_view", array ( "permissions" => array ( "user", "token")));

/**
 * Function to generate country informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function countries_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Countries");

  /**
   * Check basic parameters
   */
  $parameters["code"] = (int) $parameters["code"];

  /**
   * Search country
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Countries` WHERE `Code` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $country = $result->fetch_assoc ();

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $country);
}
?>
