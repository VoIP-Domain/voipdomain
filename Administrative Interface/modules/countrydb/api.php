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
 * VoIP Domain country database module API. This module provides the country
 * database API to the system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CountryDB
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search countries
 */
framework_add_hook (
  "countries_search",
  "countries_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all countries."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "Code,Name,Alpha2,Alpha3",
          "example" => "Code,Name,Alpha2,Region,SubRegion"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the countries information."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "country"
            ),
            "properties" => array (
              "Code" => array (
                "type" => "integer",
                "description" => __ ( "The code of the country."),
                "example" => 76
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the country (translated to current request language)."),
                "example" => __ ( "Brazil")
              ),
              "NameEN" => array (
                "type" => "string",
                "description" => __ ( "The english name of the country."),
                "example" => "Brazil"
              ),
              "Alpha2" => array (
                "type" => "string",
                "description" => __ ( "The Alpha2 (abbreviation of country with 2 letters) of the country."),
                "minLength" => 2,
                "maxLength" => 2,
                "pattern" => "/^[A-Z]{2}$/",
                "example" => "BR"
              ),
              "Alpha3" => array (
                "type" => "string",
                "description" => __ ( "The Alpha3 (abbreviation of country with 3 letters) of the country."),
                "minLength" => 3,
                "maxLength" => 3,
                "pattern" => "/^[A-Z]{3}$/",
                "example" => "BRA"
              ),
              "Region" => array (
                "type" => "string",
                "description" => __ ( "The region of the country."),
                "example" => __ ( "Americas")
              ),
              "SubRegion" => array (
                "type" => "integer",
                "description" => __ ( "The sub region of the country."),
                "example" => __ ( "South America")
              ),
              "ISO3166-2" => array (
                "type" => "string",
                "description" => __ ( "The ISO3166-2 (abbreviation of country with 2 letters) of the country."),
                "minLength" => 2,
                "maxLength" => 2,
                "pattern" => "/^[A-Z]{2}$/",
                "example" => "BR"
              )
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Filter" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid filter content.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "countries_search", __ ( "Search countries"));
framework_add_api_call (
  "/countries",
  "Read",
  "countries_search",
  array (
    "permissions" => array ( "user", "token"),
    "title" => __ ( "Search countries"),
    "description" => __ ( "Search for countries database.")
  )
);

/**
 * Function to search countries.
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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "counries_search_start"))
  {
    $parameters = framework_call ( "countries_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Countries");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "countries_search_validate"))
  {
    $data = framework_call ( "countries_search_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "countries_search_sanitize"))
  {
    $parameters = framework_call ( "countries_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "countries_search_pre"))
  {
    $parameters = framework_call ( "countries_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search countries
   */
  $data = array ();
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Countries`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `ISO3166-2` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "'" : "") . " ORDER BY `Name`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "Code,Name,Alpha2,Alpha3", "Code,Name,NameEN,Alpha2,Alpha3,Region,SubRegion,ISO3166-2");
  while ( $result = $results->fetch_assoc ())
  {
    $result["NameEN"] = $result["Name"];
    $result["Name"] = __ ( $result["Name"], true, false);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "countries_search_post"))
  {
    $data = framework_call ( "countries_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "countries_search_finish"))
  {
    framework_call ( "countries_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get country information
 */
framework_add_hook (
  "countries_view",
  "countries_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing the country information."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Code" => array (
              "type" => "integer",
              "description" => __ ( "The code of the country."),
              "example" => 76
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name of the country (translated to current request language)."),
              "example" => __ ( "Brazil")
            ),
            "NameEN" => array (
              "type" => "string",
              "description" => __ ( "The english name of the country."),
              "example" => "Brazil"
            ),
            "Alpha2" => array (
              "type" => "string",
              "description" => __ ( "The Alpha2 (abbreviation of country with 2 letters) of the country."),
              "minLength" => 2,
              "maxLength" => 2,
              "pattern" => "/^[A-Z]{2}$/",
              "example" => "BR"
            ),
            "Alpha3" => array (
              "type" => "string",
              "description" => __ ( "The Alpha3 (abbreviation of country with 3 letters) of the country."),
              "minLength" => 3,
              "maxLength" => 3,
              "pattern" => "/^[A-Z]{3}$/",
              "example" => "BRA"
            ),
            "Region" => array (
              "type" => "string",
              "description" => __ ( "The region of the country."),
              "example" => __ ( "Americas")
            ),
            "SubRegion" => array (
              "type" => "integer",
              "description" => __ ( "The sub region of the country."),
              "example" => __ ( "South America")
            ),
            "ISO3166-2" => array (
              "type" => "string",
              "description" => __ ( "The ISO3166-2 (abbreviation of country with 2 letters) of the country."),
              "minLength" => 2,
              "maxLength" => 2,
              "pattern" => "/^[A-Z]{2}$/",
              "example" => "BR"
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid country code.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "countries_view", __ ( "View countries information"));
framework_add_api_call (
  "/countries/:Code",
  "Read",
  "countries_view",
  array (
    "permissions" => array ( "user", "token"),
    "title" => __ ( "View country"),
    "description" => __ ( "View a country information."),
    "parameters" => array (
      array (
        "name" => "Code",
        "type" => "integer",
        "description" => __ ( "The code of country to be requested."),
        "example" => 76
      )
    )
  )
);

/**
 * Function to generate country information.
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
   * Call start hook if exist
   */
  if ( framework_has_hook ( "countries_view_start"))
  {
    $parameters = framework_call ( "countries_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Countries");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Code", $parameters) || ! is_numeric ( $parameters["Code"]))
  {
    $data["Code"] = __ ( "Invalid country code.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "countries_view_validate"))
  {
    $data = framework_call ( "countries_view_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["Code"] = (int) $parameters["Code"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "countries_view_sanitize"))
  {
    $parameters = framework_call ( "countries_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "countries_view_pre"))
  {
    $parameters = framework_call ( "countries_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search country
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Countries` WHERE `Code` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $country = $result->fetch_assoc ();

  /**
   * Filter return fields
   */
  $country["NameEN"] = $country["Name"];
  $country["Name"] = __ ( $country["Name"], true, false);
  $data = api_filter_entry ( array ( "Code", "Name", "NameEN", "Alpha2", "Alpha3", "Region", "SubRegion", "ISO3166-2"), $country);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "countries_view_post"))
  {
    $data = framework_call ( "countries_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "countries_view_finish"))
  {
    framework_call ( "countries_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
