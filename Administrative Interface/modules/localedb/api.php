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
 * VoIP Domain locale database module. This module provides the locale
 * database API to the system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage LocaleDB
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search locales
 */
framework_add_hook (
  "locales_search",
  "locales_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all locales."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "Code,Name",
          "example" => "Name"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system locales."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "locale"
            ),
            "properties" => array (
              "Code" => array (
                "type" => "string",
                "description" => __ ( "The locale code"),
                "example" => __ ( "en_US")
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The description of the locale (translated)."),
                "example" => __ ( "English (United States)")
              ),
              "NameEN" => array (
                "type" => "string",
                "description" => __ ( "The original english description of the locale."),
                "example" => "English (United States)"
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
framework_add_permission ( "locales_search", __ ( "Search locales"));
framework_add_api_call (
  "/locales",
  "Read",
  "locales_search",
  array (
    "permissions" => array ( "user", "token"),
    "title" => __ ( "Search locales"),
    "description" => __ ( "Search for system locales.")
  )
);

/**
 * Function to search locales.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function locales_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "locales_search_start"))
  {
    $parameters = framework_call ( "locales_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Locales");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "locales_search_validate"))
  {
    $data = framework_call ( "locales_search_validate", $parameters);
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
  if ( framework_has_hook ( "locales_search_sanitize"))
  {
    $parameters = framework_call ( "locales_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "locales_search_pre"))
  {
    $parameters = framework_call ( "locales_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search locales
   */
  $data = array ();
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Locales`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "'" : "") . " ORDER BY `Name`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "Code,Name", "Code,Name,NameEN");
  while ( $result = $results->fetch_assoc ())
  {
    $result["NameEN"] = $result["Name"];
    $result["Name"] = ( array_key_exists ( $result["Code"], $_in["locales"][$_in["general"]["language"]]) ? $_in["locales"][$_in["general"]["language"]][$result["Code"]] : $result["Name"]);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Sort result before send
   */
  uasort ( $data, function ( $a, $b)
  {
    return strcmp ( iconv ( "UTF-8", "ascii//TRANSLIT", $a["Name"]), iconv ( "UTF-8", "ascii//TRANSLIT", $b["Name"]));
  });

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "locales_search_post"))
  {
    $data = framework_call ( "locales_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "locales_search_finish"))
  {
    framework_call ( "locales_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get locale information
 */
framework_add_hook (
  "locales_view",
  "locales_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the locale."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The locale code"),
              "example" => __ ( "en_US")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The description of the locale (translated)."),
              "example" => __ ( "English (United States)")
            ),
            "NameEN" => array (
              "type" => "string",
              "description" => __ ( "The original english description of the locale."),
              "example" => "English (United States)"
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
              "example" => __ ( "Invalid locale code.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "locales_view", __ ( "View locales information"));
framework_add_api_call (
  "/locales/:Code",
  "Read",
  "locales_view",
  array (
    "permissions" => array ( "user", "token"),
    "title" => __ ( "View locales"),
    "description" => __ ( "Get a locale information."),
    "parameters" => array (
      array (
        "name" => "Code",
        "type" => "string",
        "description" => __ ( "The locale code."),
        "example" => "en_US"
      )
    )
  )
);

/**
 * Function to generate locale information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function locales_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "locales_view_start"))
  {
    $parameters = framework_call ( "locales_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Locales");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "locales_view_validate"))
  {
    $data = framework_call ( "locales_view_validate", $parameters);
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
  if ( framework_has_hook ( "locales_view_sanitize"))
  {
    $parameters = framework_call ( "locales_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "locales_view_pre"))
  {
    $parameters = framework_call ( "locales_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search locale
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Locales` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $data = $result->fetch_assoc ();
  $data["NameEN"] = $data["Name"];
  $data["Name"] = ( array_key_exists ( $data["Code"], $_in["locales"][$_in["general"]["language"]]) ? $_in["locales"][$_in["general"]["language"]][$data["Code"]] : $data["NameEN"]);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "locales_view_post"))
  {
    $data = framework_call ( "locales_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "locales_view_finish"))
  {
    framework_call ( "locales_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
