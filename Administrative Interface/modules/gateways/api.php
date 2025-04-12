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
 * VoIP Domain gateways module API. This module add the API calls related to
 * gateways.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Gateways
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Gateway component documentation
 */
framework_add_component_documentation (
  "gateway",
  array (
    "type" => "object",
    "xml" => array (
      "name" => "gateway"
    ),
    "properties" => array (
      "ID" => array (
        "type" => "integer",
        "description" => __ ( "The internal unique identification number of the gateway."),
        "example" => 1
      ),
      "Description" => array (
        "type" => "string",
        "description" => __ ( "The description of the gateway."),
        "example" => __ ( "My SIP Provider")
      ),
      "Active" => array (
        "type" => "boolean",
        "description" => __ ( "The state of the gateway, if it's active or not.", true, false),
        "example" => true
      ),
      "Type" => array (
        "type" => "string",
        "enum" => array ( __ ( "Digital"), __ ( "Analog"), __ ( "Mobile"), __ ( "VoIP")),
        "description" => __ ( "The translated type of the gateway."),
        "example" => __ ( "Digital")
      ),
      "TypeEN" => array (
        "type" => "string",
        "enum" => array ( "Digital", "Analog", "Mobile", "VoIP"),
        "description" => __ ( "The type of the gateway."),
        "example" => "Digital"
      ),
      "Priority" => array (
        "type" => "string",
        "enum" => array ( __ ( "High"), __ ( "Medium"), __ ( "Low")),
        "description" => __ ( "The translated priority of the gateway."),
        "example" => __ ( "High")
      ),
      "PriorityEN" => array (
        "type" => "string",
        "enum" => array ( "High", "Medium", "Low"),
        "description" => __ ( "The priority of the gateway."),
        "example" => "High"
      ),
      "Currency" => array (
        "type" => "string",
        "description" => __ ( "The currency of the gateway."),
        "minimum" => 3,
        "maximum" => 3,
        "pattern" => "/^[A-Z]{3}$/",
        "example" => "BRL"
      ),
      "Config" => array (
        "type" => "string",
        "enum" => array ( "manual"),
        "description" => __ ( "The configuration schema type of the gateway."),
        "example" => "manual"
      ),
      "Number" => array (
        "type" => "string",
        "description" => __ ( "The telephone number of the gateway (E.164 format)."),
        "pattern" => "/^\+\d{1,15}$/",
        "example" => __ ( "+11235551212")
      ),
      "Address" => array (
        "type" => "string",
        "description" => __ ( "The IP address of the gateway."),
        "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
        "example" => "192.168.1.100"
      ),
      "Port" => array (
        "type" => "integer",
        "description" => __ ( "The IP port of the gateway."),
        "minimum" => 0,
        "maximum" => 65535,
        "example" => 5060
      ),
      "Username" => array (
        "type" => "string",
        "description" => __ ( "The authentication username of the gateway."),
        "example" => __ ( "myusername")
      ),
      "Password" => array (
        "type" => "password",
        "description" => __ ( "The authentication password of the gateway."),
        "example" => __ ( "A_v3ry.sECure,p4ssw0rD")
      ),
      "NAT" => array (
        "type" => "boolean",
        "description" => __ ( "The status of NAT behavior to connect to the gateway."),
        "example" => true
      ),
      "RPID" => array (
        "type" => "boolean",
        "description" => __ ( "If the system expose the remote party ID for internal caller to the gateway."),
        "example" => true
      ),
      "Qualify" => array (
        "type" => "boolean",
        "description" => __ ( "If the system should qualify the response time of the gateway."),
        "example" => true
      ),
      "Discard" => array (
        "type" => "integer",
        "description" => __ ( "How much seconds of billing time to discard call cost if equal or less than for the gateway."),
        "minimum" => 0,
        "example" => 3
      ),
      "Minimum" => array (
        "type" => "integer",
        "description" => __ ( "How much seconds are the minimum billing time for the gateway."),
        "minimum" => 0,
        "example" => 30
      ),
      "Fraction" => array (
        "type" => "integer",
        "description" => __ ( "After the minimum billing time, which fraction of seconds should be calculated for the gateway."),
        "minimum" => 0,
        "example" => 6
      ),
      "oneOf" => array (
        array (
          "type" => "object",
          "description" => __ ( "Variables when gateway config type is MANUAL."),
          "properties" => array (
            "Routes" => array (
              "type" => "array",
              "description" => __ ( "The valid number routes and fares for the gateway."),
              "items" => array (
                "type" => "object",
                "properties" => array (
                  "Route" => array (
                    "type" => "string",
                    "description" => __ ( "The E.164 route mask. Grouping [1-3] and [157] are allowed."),
                    "example" => __ ( "+55[1-9][1-9][6-9]")
                  ),
                  "Cost" => array (
                    "type" => "integer",
                    "format" => "float",
                    "description" => __ ( "The minute cost fare for this route."),
                    "minimum" => 0,
                    "example" => 0.2512
                  )
                )
              )
            ),
            "Translations" => array (
              "type" => "array",
              "description" => __ ( "The translation table from E.164 to number that should be sent to the gateway."),
              "items" => array (
                "type" => "object",
                "properties" => array (
                  "Pattern" => array (
                    "type" => "string",
                    "description" => __ ( "The E.164 pattern to match. Grouping [1-3] and [157] are allowed."),
                    "example" => __ ( "+55[1-9][1-9][6-9]")
                  ),
                  "Remove" => array (
                    "type" => "string",
                    "description" => __ ( "The portion which should be removed."),
                    "example" => __ ( "+55")
                  ),
                  "Add" => array (
                    "type" => "string",
                    "description" => __ ( "The portion which should be added."),
                    "example" => __ ( "021")
                  )
                )
              )
            )
          )
        )
      ),
      "discriminator" => array (
        "propertyName" => "Config"
      )
    )
  )
);

/**
 * API call to search gateways
 */
framework_add_hook (
  "gateways_search",
  "gateways_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all gateways."),
          "example" => __ ( "filter")
        ),
        "ActiveOnly" => array (
          "type" => "boolean",
          "description" => __ ( "Filter only currently enabled gateways. If not provided, return all gateways."),
          "default" => false,
          "example" => true
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Active,Type,Priority",
          "example" => "Description,Type"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system gateways."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "\$ref" => "#/components/schemas/gateway"
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
framework_add_permission ( "gateways_search", __ ( "Search gateways"));
framework_add_api_call (
  "/gateways",
  "Read",
  "gateways_search",
  array (
    "permissions" => array ( "user", "gateways_search"),
    "title" => __ ( "Search gateways"),
    "description" => __ ( "Search for system gateways.")
  )
);

/**
 * Function to search gateways.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateways_search_start"))
  {
    $parameters = framework_call ( "gateways_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Gateways");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateways_search_validate"))
  {
    $data = framework_call ( "gateways_search_validate", $parameters);
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
  if ( framework_has_hook ( "gateways_search_sanitize"))
  {
    $parameters = framework_call ( "gateways_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateways_search_pre"))
  {
    $parameters = framework_call ( "gateways_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search gateways
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Description`, `Active`, `Type`, `Priority`, `Number` FROM `Gateways`" . ( ! empty ( $parameters["Filter"]) ? " WHERE (`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Number` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%')" : "") . ( ! empty ( $parameters["ActiveOnly"]) ? ( ! empty ( $parameters["Filter"]) ? " AND" : " WHERE") . " `Active` = TRUE" : "") . " ORDER BY `Description`, `Number`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Active,Type,Priority", "ID,Description,Active,Type,TypeEN,Priority,PriorityEN,Number");
  while ( $result = $results->fetch_assoc ())
  {
    $result["Active"] = (boolean) $result["Active"];
    $result["TypeEN"] = $result["Type"];
    $result["Type"] = __ ( $result["Type"]);
    switch ( $result["Priority"])
    {
      case 0:
        $result["PriorityEN"] = "High";
        break;
      case 1:
        $result["PriorityEN"] = "Medium";
        break;
      case 2:
        $result["PriorityEN"] = "Low";
        break;
    }
    $result["Priority"] = __ ( $result["PriorityEN"]);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateways_search_post"))
  {
    $data = framework_call ( "gateways_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateways_search_finish"))
  {
    framework_call ( "gateways_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to search gateways fares
 */
framework_add_hook (
  "gateways_fares_search",
  "gateways_fares_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all gateway fares."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Name",
          "example" => "Name"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system gateway fares."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "fare"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the gateway fare."),
                "example" => 1
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the gateway fare."),
                "example" => __ ( "My SIP Provider")
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
framework_add_permission ( "gateways_fares_search", __ ( "Search gateways fares"));
framework_add_api_call (
  "/gateways/fares",
  "Read",
  "gateways_fares_search",
  array (
    "permissions" => array ( "user", "gateways_fares_search"),
    "title" => __ ( "Search gateway fares"),
    "description" => __ ( "Search for system gateway fares.")
  )
);

/**
 * Function to search gateway fares.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_fares_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_search_start"))
  {
    $parameters = framework_call ( "gateways_fares_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Files");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_search_validate"))
  {
    $data = framework_call ( "gateways_fares_search_validate", $parameters);
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
  if ( framework_has_hook ( "gateways_fares_search_sanitize"))
  {
    $parameters = framework_call ( "gateways_fares_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_search_pre"))
  {
    $parameters = framework_call ( "gateways_fares_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search fares
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Name` FROM `Files` WHERE `Type` = 'fares'" . ( ! empty ( $parameters["Filter"]) ? " AND `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "")))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Name", "ID,Name");
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_search_post"))
  {
    $data = framework_call ( "gateways_fares_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_search_finish"))
  {
    framework_call ( "gateways_fares_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch gateways fares file
 */
framework_add_hook (
  "gateways_fares_file",
  "gateways_fares_file",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing a large three with gateway fare values information."),
        "schema" => array (
          "type" => "object",
          "additionalProperties" => array (
            "type" => "string"
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid gateway fare ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "gateways_fares_file", __ ( "Request fares file"));
framework_add_api_call (
  "/gateways/fares/:ID",
  "Read",
  "gateways_fares_file",
  array (
    "permissions" => array ( "user", "gateways_fares_file"),
    "title" => __ ( "View gateway fares files"),
    "description" => __ ( "Get the gateway fares files information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The gateway fare file internal system unique identifier."),
        "example" => 1
      )
    ),
  )
);

/**
 * Function to generate gateway fares file.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_fares_file ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_view_start"))
  {
    $parameters = framework_call ( "gateways_fares_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Files");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid gateway ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_view_validate"))
  {
    $data = framework_call ( "gateways_fares_view_validate", $parameters);
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
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_view_sanitize"))
  {
    $parameters = framework_call ( "gateways_fares_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_view_pre"))
  {
    $parameters = framework_call ( "gateways_fares_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search fare
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Files` WHERE `Type` = 'fares' AND `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $fare = $result->fetch_assoc ();

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_view_post"))
  {
    $data = framework_call ( "gateways_fares_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateways_fares_view_finish"))
  {
    framework_call ( "gateways_fares_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), json_decode ( $fare["Content"], true));
}

/**
 * API call to get gateway information
 */
framework_add_hook (
  "gateways_view",
  "gateways_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system gateway."),
        "schema" => array (
          "\$ref" => "#/components/schemas/gateway"
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid gateway ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "gateways_view", __ ( "View gateways information"));
framework_add_api_call (
  "/gateways/:ID",
  "Read",
  "gateways_view",
  array (
    "permissions" => array ( "user", "gateways_view"),
    "title" => __ ( "View gateways"),
    "description" => __ ( "Get a gateway information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The system gateway internal system unique identifier."),
        "example" => 1
      )
    ),
  )
);

/**
 * Function to generate gateway information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateways_view_start"))
  {
    $parameters = framework_call ( "gateways_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Gateways", "Countries"));

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid gateway ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateways_view_validate"))
  {
    $data = framework_call ( "gateways_view_validate", $parameters);
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
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateways_view_sanitize"))
  {
    $parameters = framework_call ( "gateways_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateways_view_pre"))
  {
    $parameters = framework_call ( "gateways_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search gateways
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $gateway = $result->fetch_assoc ();

  /**
   * Format data
   */
  $gateway["Active"] = (boolean) $gateway["Active"];
  $gateway["TypeEN"] = $gateway["Type"];
  $gateway["Type"] = __ ( $gateway["Type"]);
  switch ( $gateway["Priority"])
  {
    case 0:
      $gateway["PriorityEN"] = "High";
      break;
    case 1:
      $gateway["PriorityEN"] = "Medium";
      break;
    case 2:
      $gateway["PriorityEN"] = "Low";
      break;
  }
  $gateway["Priority"] = __ ( $gateway["PriorityEN"]);
  $gateway["NAT"] = (boolean) $gateway["NAT"];
  $gateway["RPID"] = (boolean) $gateway["RPID"];
  $gateway["Qualify"] = (boolean) $gateway["Qualify"];
  $gateway["Routes"] = json_decode ( $gateway["Routes"], true);
  $gateway["Translations"] = json_decode ( $gateway["Translations"], true);
  $data = api_filter_entry ( array ( "ID", "Description", "Active", "Type", "TypeEN", "Priority", "PriorityEN", "Config", "Number", "Address", "Port", "Username", "Password", "NAT", "RPID", "Qualify", "Discard", "Minimum", "Fraction", "Routes", "Translations", "Currency"), $gateway);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateways_view_post"))
  {
    $data = framework_call ( "gateways_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateways_view_finish"))
  {
    framework_call ( "gateways_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new gateway
 */
framework_add_hook (
  "gateways_add",
  "gateways_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The descripton of the system gateway."),
          "required" => true,
          "example" => __ ( "My SIP Provider")
        ),
        "Config" => array (
          "type" => "string",
          "enum" => array ( "manual"),
          "description" => __ ( "The configuration schema type of the gateway."),
          "default" => "manual",
          "required" => true,
          "example" => "manual"
        ),
        "Active" => array (
          "type" => "boolean",
          "description" => __ ( "The state of the gateway, if it's active or not.", true, false),
          "required" => true,
          "example" => true
        ),
        "Number" => array (
          "type" => "string",
          "description" => __ ( "The telephone number of the gateway (E.164 format)."),
          "pattern" => "/^\+\d{1,15}$/",
          "required" => false,
          "example" => __ ( "+11235551212")
        ),
        "Type" => array (
          "type" => "string",
          "enum" => array ( "Digital", "Analog", "Mobile", "VoIP"),
          "description" => __ ( "The type of the gateway."),
          "required" => true,
          "example" => "VoIP"
        ),
        "Priority" => array (
          "type" => "string",
          "enum" => array ( "High", "Medium", "Low"),
          "description" => __ ( "The priority of the gateway."),
          "required" => true,
          "example" => "High"
        ),
        "Currency" => array (
          "type" => "string",
          "description" => __ ( "The currency of the gateway."),
          "required" => true,
          "example" => "BRL"
        ),
        "Address" => array (
          "type" => "string",
          "description" => __ ( "The IP address of the gateway."),
          "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
          "required" => true,
          "example" => "192.168.1.100"
        ),
        "Port" => array (
          "type" => "integer",
          "description" => __ ( "The IP port of the gateway."),
          "minimum" => 1,
          "maximum" => 65535,
          "required" => true,
          "example" => 5060
        ),
        "Username" => array (
          "type" => "string",
          "description" => __ ( "The authentication username of the gateway."),
          "required" => true,
          "example" => __ ( "myusername")
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "The authentication password of the gateway."),
          "required" => true,
          "example" => __ ( "A_v3ry.sECure,p4ssw0rD")
        ),
        "NAT" => array (
          "type" => "boolean",
          "description" => __ ( "The status of NAT behavior to connect to the gateway."),
          "required" => true,
          "example" => true
        ),
        "RPID" => array (
          "type" => "boolean",
          "description" => __ ( "If the system expose the remote party ID for internal caller to the gateway."),
          "required" => true,
          "example" => false
        ),
        "Qualify" => array (
          "type" => "boolean",
          "description" => __ ( "If the system should qualify the response time of the gateway."),
          "required" => true,
          "example" => true
        ),
        "Discard" => array (
          "type" => "integer",
          "description" => __ ( "How much seconds of billing time to discard call cost if equal or less than for the gateway."),
          "minimum" => 0,
          "required" => true,
          "example" => 3
        ),
        "Minimum" => array (
          "type" => "integer",
          "description" => __ ( "How much seconds are the minimum billing time for the gateway."),
          "minimum" => 0,
          "required" => true,
          "example" => 30
        ),
        "Fraction" => array (
          "type" => "integer",
          "description" => __ ( "After the minimum billing time, which fraction of seconds should be calculated for the gateway."),
          "minimum" => 0,
          "required" => true,
          "example" => 6
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "description" => __ ( "Variables when gateway config type is MANUAL."),
            "properties" => array (
              "Routes" => array (
                "type" => "array",
                "description" => __ ( "The valid number routes and fares for the gateway."),
                "items" => array (
                  "type" => "object",
                  "properties" => array (
                    "Reference" => array (
                      "type" => "integer",
                      "description" => __ ( "The reference number to gateway route. This is used to report any route route/cost error."),
                      "required" => true
                    ),
                    "Route" => array (
                      "type" => "string",
                      "description" => __ ( "The E.164 route mask. Grouping [1-3] and [157] are allowed."),
                      "example" => __ ( "+55[1-9][1-9][6-9]"),
                      "required" => true
                    ),
                    "Cost" => array (
                      "type" => "integer",
                      "format" => "float",
                      "description" => __ ( "The minute cost fare for this route."),
                      "minimum" => 0,
                      "example" => 0.2512,
                      "required" => true
                    )
                  )
                )
              ),
              "Translations" => array (
                "type" => "array",
                "description" => __ ( "The translation table from E.164 to number that should be sent to the gateway."),
                "items" => array (
                  "type" => "object",
                  "properties" => array (
                    "Reference" => array (
                      "type" => "integer",
                      "description" => __ ( "The reference number to gateway translation. This is used to report any translation pattern/remove/add error."),
                      "required" => true
                    ),
                    "Pattern" => array (
                      "type" => "string",
                      "description" => __ ( "The E.164 pattern to match. Grouping [1-3] and [157] are allowed."),
                      "example" => __ ( "+55[1-9][1-9][6-9]"),
                      "required" => true
                    ),
                    "Remove" => array (
                      "type" => "string",
                      "description" => __ ( "The portion which should be removed."),
                      "example" => __ ( "+55"),
                      "required" => false
                    ),
                    "Add" => array (
                      "type" => "string",
                      "description" => __ ( "The portion which should be added."),
                      "example" => __ ( "021"),
                      "required" => false
                    )
                  )
                )
              )
            )
          )
        )
      ),
      "discriminator" => array (
        "propertyName" => "Config"
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system gateway was sucessfully added.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The gateway description is required.")
            ),
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The gateway type is invalid.")
            ),
            "Priority" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The informed priority is invalid.")
            ),
            "Number" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The number must be in E.164 format, including the + prefix.")
            ),
            "Address" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The gateway address is required.")
            ),
            "Port" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The gateway port is required.")
            ),
            "Route_X" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "At least one route must be created.")
            ),
            "Pattern_X" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The translation *X* must have a remotion, addition or both procedures.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "gateways_add", __ ( "Add gateways"));
framework_add_api_call (
  "/gateways",
  "Create",
  "gateways_add",
  array (
    "permissions" => array ( "user", "gateways_add"),
    "title" => __ ( "Add gateways"),
    "description" => __ ( "Add a new system gateway.")
  )
);

/**
 * Function to add a new gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateways_add_start"))
  {
    $parameters = framework_call ( "gateways_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The gateway description is required.");
  }
  $parameters["Type"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Type"])));
  if ( empty ( $parameters["Type"]))
  {
    $data["Type"] = __ ( "The gateway type is required.");
  }
  if ( ! empty ( $parameters["Type"]) && ! in_array ( $parameters["Type"], $_in["gwtypes"]))
  {
    $data["Type"] = __ ( "The gateway type is invalid.");
  }
  switch ( $parameters["Priority"])
  {
    case "High":
      $parameters["Priority"] = 0;
      break;
    case "Medium":
      $parameters["Priority"] = 1;
      break;
    case "Low":
      $parameters["Priority"] = 2;
      break;
    case "":
      $data["Priority"] = __ ( "The gateway priority is required.");
      break;
    default:
      $data["Priority"] = __ ( "The informed priority is invalid.");
      break;
  }
  if ( empty ( $parameters["Currency"]))
  {
    $data["Currency"] = __ ( "The gateway currency is required.");
  }
  $parameters["Number"] = preg_replace ( "/ /", "", trim ( strip_tags ( $parameters["Number"])));
  if ( ! empty ( $parameters["Number"]) && ! validate_E164 ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The number must be in E.164 format, including the + prefix.");
  }
  $parameters["Address"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Address"])));
  if ( empty ( $parameters["Address"]))
  {
    $data["Address"] = __ ( "The gateway address is required.");
  }
  if ( ! empty ( $parameters["Address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $parameters["Address"]) && gethostbyname ( $parameters["Address"]) == $parameters["Address"])
  {
    $data["Address"] = __ ( "The gateway address is invalid.");
  }
  if ( empty ( $parameters["Port"]))
  {
    $data["Port"] = __ ( "The gateway port is required.");
  }
  if ( ! empty ( $parameters["Port"]) && ( (int) $parameters["Port"] < 1 || (int) $parameters["Port"] > 65535))
  {
    $data["Port"] = __ ( "The informed port is invalid.");
  }

  /**
   * Process provided routes
   */
  $routes = array ();
  foreach ( $parameters["Routes"] as $key => $value)
  {
    if ( ! $value["Route"])
    {
      $data["Route_" . (int) $value["Reference"]] = __ ( "The route pattern is required.");
      continue;
    }
    if ( ! $value["Cost"])
    {
      $data["Cost_" . (int) $value["Reference"]] = __ ( "The route cost is required.");
      continue;
    }
    $routes[] = array ( "Route" => $value["Route"], "Cost" => (float) $value["Cost"]);
  }
  if ( $parameters["Config"] == "manual" && sizeof ( $routes) == 0)
  {
    $data["Route_1"] = __ ( "At least one route must be created.");
  }

  /**
   * Process provided translations
   */
  $translations = array ();
  foreach ( $parameters["Translations"] as $key => $value)
  {
    if ( empty ( $value["Remove"]) && empty ( $value["Add"]))
    {
      $data["Pattern_" . (int) $value["Reference"]] = __ ( "The translation must have a remotion, addition or both procedures.");
      continue;
    }
    $translations[] = array ( "Pattern" => $value["Pattern"], "Remove" => preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $value["Remove"]))), "Add" => preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $value["Add"]))));
  }

  /**
   * Check if provided number is recognized by the system
   */
  if ( ! array_key_exists ( "Number", $data) && ! empty ( $parameters["Number"]))
  {
    $number = filters_call ( "e164_identify", array ( "Number" => $parameters["Number"]));
    if ( ! array_key_exists ( "Number", $number) || sizeof ( $number["Number"]) == 0)
    {
      $data["Number"] = __ ( "The informed number is invalid.");
    }
    $parameters["Number"] = $number["Number"]["CallFormats"]["International"];
  }

  /**
   * Check if provided currency is recognized by the system
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Currencies` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Currency"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["Currency"] = __ ( "The informed currency is invalid.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateways_add_sanitize"))
  {
    $data = framework_call ( "gateways_add_sanitize", $parameters, false, $data);
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
  $parameters["Port"] = (int) $parameters["Port"];
  $parameters["Active"] = (boolean) $parameters["Active"];
  $parameters["NAT"] = (boolean) $parameters["NAT"];
  $parameters["RPID"] = (boolean) $parameters["RPID"];
  $parameters["Qualify"] = (boolean) $parameters["Qualify"];
  $parameters["Fraction"] = (int) $parameters["Fraction"];
  $parameters["Minimum"] = (int) $parameters["Minimum"];
  $parameters["Discard"] = (int) $parameters["Discard"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateways_add_sanitize"))
  {
    $parameters = framework_call ( "gateways_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateways_add_pre"))
  {
    $parameters = framework_call ( "gateways_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new gateway record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Gateways` (`Description`, `Config`, `Active`, `Number`, `Type`, `Priority`, `Currency`, `Address`, `Port`, `Username`, `Password`, `Routes`, `Translations`, `Discard`, `Minimum`, `Fraction`, `NAT`, `RPID`, `Qualify`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Config"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Active"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Type"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Priority"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Currency"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Address"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Port"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Password"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $routes)) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $translations)) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Discard"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Minimum"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Fraction"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["NAT"] ? 1 : 0) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["RPID"] ? 1 : 0) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Qualify"] ? 1 : 0) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateways_add_post"))
  {
    framework_call ( "gateways_add_post", $parameters);
  }

  /**
   * Add new gateway at Asterisk servers
   */
  if ( $parameters["Active"])
  {
    $notify = array ( "ID" => $parameters["ID"], "Description" => $parameters["Description"], "Domain" => $_in["general"]["domain"], "Username" => $parameters["Username"], "Password" => $parameters["Password"], "Address" => $parameters["Address"], "Port" => $parameters["Port"], "Qualify" => $parameters["Qualify"], "NAT" => $parameters["NAT"], "RPID" => $parameters["RPID"], "Config" => $parameters["Config"], "Number" => str_replace ( " ", "", $parameters["Number"]), "Type" => $parameters["Type"], "Priority" => $parameters["Priority"], "Currency" => $parameters["Code"], "Routes" => $routes, "Translations" => $translations, "Discard" => $parameters["Discard"], "Minimum" => $parameters["Minimum"], "Fraction" => $parameters["Fraction"]);
    if ( framework_has_hook ( "gateways_add_notify"))
    {
      $notify = framework_call ( "gateways_add_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "gateway_add", $notify);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateways_add_finish"))
  {
    framework_call ( "gateways_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/gateways/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing gateway
 */
framework_add_hook (
  "gateways_edit",
  "gateways_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The descripton of the system gateway."),
          "required" => true,
          "example" => __ ( "My SIP Provider")
        ),
        "Currency" => array (
          "type" => "string",
          "description" => __ ( "The currency of the gateway."),
          "minimum" => 3,
          "maximum" => 3,
          "pattern" => "/^[A-Z]{3}$/",
          "example" => "BRL"
        ),
        "Config" => array (
          "type" => "string",
          "enum" => array ( "manual"),
          "description" => __ ( "The configuration schema type of the gateway."),
          "default" => "manual",
          "required" => true,
          "example" => "manual"
        ),
        "Active" => array (
          "type" => "boolean",
          "description" => __ ( "The state of the gateway, if it's active or not.", true, false),
          "required" => true,
          "example" => true
        ),
        "Number" => array (
          "type" => "string",
          "description" => __ ( "The telephone number of the gateway (E.164 format)."),
          "pattern" => "/^\+\d{1,15}$/",
          "required" => false,
          "example" => __ ( "+1 123 5551212")
        ),
        "Type" => array (
          "type" => "string",
          "enum" => array ( "Digital", "Analog", "Mobile", "VoIP"),
          "description" => __ ( "The type of the gateway."),
          "required" => true,
          "example" => "VoIP"
        ),
        "Priority" => array (
          "type" => "string",
          "enum" => array ( "High", "Medium", "Low"),
          "description" => __ ( "The priority of the gateway."),
          "required" => true,
          "example" => "High"
        ),
        "Address" => array (
          "type" => "string",
          "description" => __ ( "The IP address of the gateway."),
          "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
          "required" => true,
          "example" => "192.168.1.100"
        ),
        "Port" => array (
          "type" => "integer",
          "description" => __ ( "The IP port of the gateway."),
          "minimum" => 1,
          "maximum" => 65535,
          "required" => true,
          "example" => 5060
        ),
        "Username" => array (
          "type" => "string",
          "description" => __ ( "The authentication username of the gateway."),
          "required" => true,
          "example" => __ ( "myusername")
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "The authentication password of the gateway."),
          "required" => true,
          "example" => __ ( "A_v3ry.sECure,p4ssw0rD")
        ),
        "NAT" => array (
          "type" => "boolean",
          "description" => __ ( "The status of NAT behavior to connect to the gateway."),
          "required" => true,
          "example" => true
        ),
        "RPID" => array (
          "type" => "boolean",
          "description" => __ ( "If the system expose the remote party ID for internal caller to the gateway."),
          "required" => true,
          "example" => false
        ),
        "Qualify" => array (
          "type" => "boolean",
          "description" => __ ( "If the system should qualify the response time of the gateway."),
          "required" => true,
          "example" => true
        ),
        "Discard" => array (
          "type" => "integer",
          "description" => __ ( "How much seconds of billing time to discard call cost if equal or less than for the gateway."),
          "minimum" => 0,
          "required" => true,
          "example" => 3
        ),
        "Minimum" => array (
          "type" => "integer",
          "description" => __ ( "How much seconds are the minimum billing time for the gateway."),
          "minimum" => 0,
          "required" => true,
          "example" => 30
        ),
        "Fraction" => array (
          "type" => "integer",
          "description" => __ ( "After the minimum billing time, which fraction of seconds should be calculated for the gateway."),
          "minimum" => 0,
          "required" => true,
          "example" => 6
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "description" => __ ( "Variables when gateway config type is MANUAL."),
            "properties" => array (
              "Routes" => array (
                "type" => "array",
                "description" => __ ( "The valid number routes and fares for the gateway."),
                "items" => array (
                  "type" => "object",
                  "properties" => array (
                    "Reference" => array (
                      "type" => "integer",
                      "description" => __ ( "The reference number to gateway route. This is used to report any route route/cost error."),
                      "required" => true
                    ),
                    "Route" => array (
                      "type" => "string",
                      "description" => __ ( "The E.164 route mask. Grouping [1-3] and [157] are allowed."),
                      "example" => __ ( "+55[1-9][1-9][6-9]"),
                      "required" => true
                    ),
                    "Cost" => array (
                      "type" => "integer",
                      "format" => "float",
                      "description" => __ ( "The minute cost fare for this route."),
                      "minimum" => 0,
                      "example" => 0.2512,
                      "required" => true
                    )
                  )
                )
              ),
              "Translations" => array (
                "type" => "array",
                "description" => __ ( "The translation table from E.164 to number that should be sent to the gateway."),
                "items" => array (
                  "type" => "object",
                  "properties" => array (
                    "Reference" => array (
                      "type" => "integer",
                      "description" => __ ( "The reference number to gateway translation. This is used to report any translation pattern/remove/add error."),
                      "required" => true
                    ),
                    "Pattern" => array (
                      "type" => "string",
                      "description" => __ ( "The E.164 pattern to match. Grouping [1-3] and [157] are allowed."),
                      "example" => __ ( "+55[1-9][1-9][6-9]"),
                      "required" => true
                    ),
                    "Remove" => array (
                      "type" => "string",
                      "description" => __ ( "The portion which should be removed."),
                      "example" => __ ( "+55"),
                      "required" => false
                    ),
                    "Add" => array (
                      "type" => "string",
                      "description" => __ ( "The portion which should be added."),
                      "example" => __ ( "021"),
                      "required" => false
                    )
                  )
                )
              )
            )
          )
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system call center agent added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The gateway description is required.")
            ),
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The gateway type is invalid.")
            ),
            "Priority" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The informed priority is invalid.")
            ),
            "Number" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The number must be in E.164 format, including the + prefix.")
            ),
            "Address" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The gateway address is required.")
            ),
            "Port" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The gateway port is required.")
            ),
            "Route_X" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "At least one route must be created.")
            ),
            "Pattern_X" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The translation *X* must have a remotion, addition or both procedures.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "gateways_edit", __ ( "Edit gateways"));
framework_add_api_call (
  "/gateways/:ID",
  "Modify",
  "gateways_edit",
  array (
    "permissions" => array ( "user", "gateways_edit"),
    "title" => __ ( "Edit gateways"),
    "description" => __ ( "Change a system gateway information.")
  )
);
framework_add_api_call (
  "/gateways/:ID",
  "Edit",
  "gateways_edit",
  array (
    "permissions" => array ( "user", "gateways_edit"),
    "title" => __ ( "Edit gateways"),
    "description" => __ ( "Change a system gateway information.")
  )
);

/**
 * Function to edit an existing gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateways_edit_start"))
  {
    $parameters = framework_call ( "gateways_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The gateway description is required.");
  }
  $parameters["Type"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Type"])));
  if ( empty ( $parameters["Type"]))
  {
    $data["Type"] = __ ( "The gateway type is required.");
  }
  if ( ! empty ( $parameters["Type"]) && ! in_array ( $parameters["Type"], $_in["gwtypes"]))
  {
    $data["Type"] = __ ( "The gateway type is invalid.");
  }
  switch ( $parameters["Priority"])
  {
    case "High":
      $parameters["Priority"] = 0;
      break;
    case "Medium":
      $parameters["Priority"] = 1;
      break;
    case "Low":
      $parameters["Priority"] = 2;
      break;
    case "":
      $data["Priority"] = __ ( "The gateway priority is required.");
      break;
    default:
      $data["Priority"] = __ ( "The informed priority is invalid.");
      break;
  }
  if ( empty ( $parameters["Currency"]))
  {
    $data["Currency"] = __ ( "The gateway currency is required.");
  }
  $parameters["Number"] = preg_replace ( "/ /", "", trim ( strip_tags ( $parameters["Number"])));
  if ( ! empty ( $parameters["Number"]) && ! validate_E164 ( $parameters["Number"]))
  {
    $data["Number"] = __ ( "The number must be in E.164 format, including the + prefix.");
  }
  $parameters["Address"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Address"])));
  if ( empty ( $parameters["Address"]))
  {
    $data["Address"] = __ ( "The gateway address is required.");
  }
  if ( ! empty ( $parameters["Address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $parameters["Address"]) && gethostbyname ( $parameters["Address"]) == $parameters["Address"])
  {
    $data["Address"] = __ ( "The gateway address is invalid.");
  }
  if ( empty ( $parameters["Port"]))
  {
    $data["Port"] = __ ( "The gateway port is required.");
  }
  if ( ! empty ( $parameters["Port"]) && ( (int) $parameters["Port"] < 1 || (int) $parameters["Port"] > 65535))
  {
    $data["Port"] = __ ( "The informed port is invalid.");
  }

  /**
   * Process provided routes
   */
  $routes = array ();
  foreach ( $parameters["Routes"] as $key => $value)
  {
    if ( ! $value["Route"])
    {
      $data["Route_" . (int) $value["Reference"]] = __ ( "The route pattern is required.");
      continue;
    }
    if ( ! $value["Cost"])
    {
      $data["Cost_" . (int) $value["Reference"]] = __ ( "The route cost is required.");
      continue;
    }
    $routes[] = array ( "Route" => $value["Route"], "Cost" => (float) $value["Cost"]);
  }
  if ( $parameters["Config"] == "manual" && sizeof ( $routes) == 0)
  {
    $data["Route_1"] = __ ( "At least one route must be created.");
  }

  /**
   * Process provided translations
   */
  $translations = array ();
  foreach ( $parameters["Translations"] as $key => $value)
  {
    if ( empty ( $value["Remove"]) && empty ( $value["Add"]))
    {
      $data["Pattern_" . (int) $value["Reference"]] = __ ( "The translation must have a remotion, addition or both procedures.");
      continue;
    }
    $translations[] = array ( "Pattern" => $value["Pattern"], "Remove" => preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $value["Remove"]))), "Add" => preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $value["Add"]))));
  }

  /**
   * Check if provided number is recognized by the system
   */
  if ( ! array_key_exists ( "Number", $data) && ! empty ( $parameters["Number"]))
  {
    $number = filters_call ( "e164_identify", array ( "Number" => $parameters["Number"]));
    if ( sizeof ( $number["Number"]) == 0)
    {
      $data["Number"] = __ ( "The informed number is invalid.");
    }
  }

  /**
   * Check if provided currency is recognized by the system
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Currencies` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Currency"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["Currency"] = __ ( "The informed currency is invalid.");
  }

  /**
   * Get gateway information from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $parameters["ORIGINAL"] = $result->fetch_assoc ();

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateways_edit_sanitize"))
  {
    $data = framework_call ( "gateways_edit_sanitize", $parameters, false, $data);
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
  $parameters["Port"] = (int) $parameters["Port"];
  $parameters["Active"] = (boolean) $parameters["Active"];
  $parameters["NAT"] = (boolean) $parameters["NAT"];
  $parameters["RPID"] = (boolean) $parameters["RPID"];
  $parameters["Qualify"] = (boolean) $parameters["Qualify"];
  $parameters["Fraction"] = (int) $parameters["Fraction"];
  $parameters["Minimum"] = (int) $parameters["Minimum"];
  $parameters["Discard"] = (int) $parameters["Discard"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateways_edit_sanitize"))
  {
    $parameters = framework_call ( "gateways_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateways_edit_pre"))
  {
    $parameters = framework_call ( "gateways_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Update gateway record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Gateways` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Config` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Config"]) . "', `Active` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Active"]) . "', `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Number"]) . "', `Type` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Type"]) . "', `Priority` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Priority"]) . "', `Currency` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Currency"]) . "', `Address` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Address"]) . "', `Port` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Port"]) . ", `Username` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "', `Password` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Password"]) . "', `Routes` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $routes)) . "', `Translations` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $translations)) . "', `Discard` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Discard"]) . ", `Minimum` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Minimum"]) . ", `Fraction` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Fraction"]) . ", `NAT` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["NAT"] ? 1 : 0) . "', `RPID` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["RPID"] ? 1 : 0) . "', `Qualify` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Qualify"] ? 1 : 0) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateways_edit_post"))
  {
    framework_call ( "gateways_edit_post", $parameters);
  }

  /**
   * If gateway were active, notify Asterisk servers about changes
   */
  if ( $parameters["ORIGINAL"]["Active"])
  {
    if ( $parameters["Active"])
    {
      if ( $parameters["ORIGINAL"]["Description"] != $parameters["Description"] || $parameters["ORIGINAL"]["Address"] != $parameters["Address"] || $parameters["ORIGINAL"]["Port"] != $parameters["Port"] || $parameters["ORIGINAL"]["Username"] != $parameters["Username"] || $parameters["ORIGINAL"]["Password"] != $parameters["Password"] || $parameters["ORIGINAL"]["NAT"] != $parameters["NAT"] || $parameters["ORIGINAL"]["RPID"] != $parameters["RPID"] || $parameters["ORIGINAL"]["Qualify"] != $parameters["Qualify"] || $parameters["ORIGINAL"]["Currency"] != $parameters["Currency"])
      {
        $notify = array ( "ID" => $parameters["ID"], "Description" => $parameters["Description"], "Domain" => $_in["general"]["domain"], "Username" => $parameters["Username"], "Password" => $parameters["Password"], "Address" => $parameters["Address"], "Port" => $parameters["Port"], "Qualify" => $parameters["Qualify"], "NAT" => $parameters["NAT"], "RPID" => $parameters["RPID"], "Config" => $parameters["Config"], "Number" => str_replace ( " ", "", $parameters["Number"]), "Type" => $parameters["Type"], "Priority" => $parameters["Priority"], "Currency" => $parameters["Currency"], "Routes" => $routes, "Translations" => $translations, "Discard" => $parameters["Discard"], "Minimum" => $parameters["Minimum"], "Fraction" => $parameters["Fraction"]);
        if ( framework_has_hook ( "gateways_edit_notify"))
        {
          $notify = framework_call ( "gateways_edit_notify", $parameters, false, $notify);
        }
        notify_server ( 0, "gateway_change", $notify);
      }
    } else {
      $notify = array ( "ID" => $parameters["ORIGINAL"]["ID"]);
      if ( framework_has_hook ( "gateways_remove_notify"))
      {
        $notify = framework_call ( "gateways_remove_notify", $parameters, false, $notify);
      }
      notify_server ( 0, "gateway_remove", $notify);
    }
  }

  /**
   * If gateway were inactive and has activated, notify Asterisk servers about changes
   */
  if ( ! $parameters["ORIGINAL"]["Active"] && $parameters["Active"])
  {
    $notify = array ( "ID" => $parameters["ID"], "Description" => $parameters["Description"], "Domain" => $_in["general"]["domain"], "Username" => $parameters["Username"], "Password" => $parameters["Password"], "Address" => $parameters["Address"], "Port" => $parameters["Port"], "Qualify" => $parameters["Qualify"], "NAT" => $parameters["NAT"], "RPID" => $parameters["RPID"], "Config" => $parameters["Config"], "Number" => str_replace ( " ", "", $parameters["Number"]), "Type" => $parameters["Type"], "Priority" => $parameters["Priority"], "Currency" => $parameters["Currency"], "Routes" => $routes, "Translations" => $translations, "Discard" => $parameters["Discard"], "Minimum" => $parameters["Minimum"], "Fraction" => $parameters["Fraction"]);
    if ( framework_has_hook ( "gateways_add_notify"))
    {
      $notify = framework_call ( "gateways_add_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "gateway_add", $notify);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateways_edit_finish"))
  {
    framework_call ( "gateways_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a gateway
 */
framework_add_hook (
  "gateways_remove",
  "gateways_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system gateway was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid gateway ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "gateways_remove", __ ( "Remove gateways"));
framework_add_api_call (
  "/gateways/:ID",
  "Delete",
  "gateways_remove",
    array ( "permissions" => array ( "user", "gateways_remove"),
    "title" => __ ( "Remove gateways"),
    "description" => __ ( "Remove a system gateway from system.")
  )
);

/**
 * Function to remove an existing gateway.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateways_remove_start"))
  {
    $parameters = framework_call ( "gateways_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid gateway ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateways_remove_validate"))
  {
    $data = framework_call ( "gateways_remove_validate", $parameters, false, $data);
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
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateways_remove_sanitize"))
  {
    $parameters = framework_call ( "gateways_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if gateway exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $parameters["ORIGINAL"] = $result->fetch_assoc ();

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateways_remove_pre"))
  {
    $parameters = framework_call ( "gateways_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove gateway database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateways_remove_post"))
  {
    framework_call ( "gateways_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["ORIGINAL"]["ID"]);
  if ( framework_has_hook ( "gateways_remove_notify"))
  {
    $notify = framework_call ( "gateways_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "gateway_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateways_remove_finish"))
  {
    framework_call ( "gateways_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to generate gateway call's report
 */
framework_add_hook (
  "gateways_report",
  "gateways_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-04-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-31T23:59:59Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the call records made by the required gateway."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "\$ref" => "#/components/schemas/call"
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid start date.")
            ),
            "End" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid end date.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "gateways_report", __ ( "Gateways use report"));
framework_add_api_call (
  "/gateways/:ID/report",
  "Read",
  "gateways_report",
  array (
    "permissions" => array ( "user", "gateways_report"),
    "title" => __ ( "Gateways report"),
    "description" => __ ( "Generate a gateway call's usage report.", true, false),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The system gateway internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateways_report_start"))
  {
    $parameters = framework_call ( "gateways_report_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( empty ( $parameters["Start"]))
  {
    $data["Start"] = __ ( "Missing start date.");
  }
  $datecheck = format_form_datetime ( $parameters["Start"]);
  if ( ! array_key_exists ( "Start", $data) && empty ( $datecheck))
  {
    $data["Start"] = __ ( "Invalid start date.");
  }
  if ( empty ( $parameters["End"]))
  {
    $data["End"] = __ ( "Missing end date.");
  }
  $datecheck = format_form_datetime ( $parameters["End"]);
  if ( ! array_key_exists ( "End", $data) && empty ( $datecheck))
  {
    $data["End"] = __ ( "Invalid end date.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "gateways_report_validate"))
  {
    $data = framework_call ( "gateways_report_validate", $parameters);
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
  $parameters["Start"] = format_form_datetime ( $parameters["Start"]);
  $parameters["End"] = format_form_datetime ( $parameters["End"]);
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "gateways_report_sanitize"))
  {
    $parameters = framework_call ( "gateways_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateways_report_pre"))
  {
    $parameters = framework_call ( "gateways_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get gateway information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $gateway = $result->fetch_assoc ();

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `gateway` = " . $_in["mysql"]["id"]->real_escape_string ( $gateway["ID"]) . " AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $data = array ();
  while ( $call = $records->fetch_assoc ())
  {
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateways_report_post"))
  {
    $data = framework_call ( "gateways_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateways_report_finish"))
  {
    framework_call ( "gateways_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "gateways_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "gateways_server_reconfig");

/**
 * Function to notify server to include all gateways.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all gateways and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $gateway = $result->fetch_assoc ())
  {
    if ( $gateway["Active"])
    {
      $notify = array ( "ID" => $gateway["ID"], "Description" => $gateway["Description"], "Domain" => $_in["general"]["domain"], "Username" => $gateway["Username"], "Password" => $gateway["Password"], "Address" => $gateway["Address"], "Port" => $gateway["Port"], "Qualify" => $gateway["Qualify"] == 1, "NAT" => $gateway["NAT"] == 1, "RPID" => $gateway["RPID"] == 1, "Config" => $gateway["Config"], "Number" => str_replace ( " ", "", $gateway["Number"]), "Type" => $gateway["Type"], "Priority" => $gateway["Priority"], "Currency" => $gateway["Currency"], "Routes" => json_decode ( $gateway["Routes"], true), "Translations" => json_decode ( $gateway["Translations"], true), "Discard" => $gateway["Discard"], "Minimum" => $gateway["Minimum"], "Fraction" => $gateway["Fraction"]);
      if ( framework_has_hook ( "gateways_add_notify"))
      {
        $notify = framework_call ( "gateways_add_notify", $parameters, false, $notify);
      }
      notify_server ( (int) $parameters["ID"], "gateway_add", $notify);
    }
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
