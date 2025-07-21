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
 * VoIP Domain multi-tenant module API. This module add the API calls related to
 * system multi-tenant.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Tenants
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search tenants
 */
framework_add_hook (
  "tenants_search",
  "tenants_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all tenants."),
          "example" => __ ( "customer")
        ),
        "Status" => array (
          "type" => "string",
          "enum" => array ( "Active", "Suspended"),
          "description" => __ ( "Filter by tenant status."),
          "example" => "Active"
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Name,Domain,Status,CreatedAt",
          "example" => "Name,Domain,Status"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system tenants."),
        "schema" => array (
          "type" => "array",
          "items" => array (
            "type" => "object",
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the tenant."),
                "example" => 1
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the tenant organization."),
                "example" => __ ( "Acme Corporation")
              ),
              "Domain" => array (
                "type" => "string",
                "description" => __ ( "The domain for tenant access."),
                "example" => __ ( "example.com")
              ),
              "Status" => array (
                "type" => "string",
                "enum" => array ( __ ( "Active"), __ ( "Suspended")),
                "description" => __ ( "The status of the tenant."),
                "example" => __ ( "Active")
              ),
              "StatusEN" => array (
                "type" => "string",
                "enum" => array ( "Active", "Suspended"),
                "description" => __ ( "The status of the tenant in English."),
                "example" => "Active"
              ),
              "CreatedAt" => array (
                "type" => "string",
                "description" => __ ( "The date and time of tenant creation."),
                "example" => "2024-01-15T10:30:00Z"
              ),
              "UpdatedAt" => array (
                "type" => "string",
                "description" => __ ( "The date and time of latest tenant modification."),
                "example" => "2024-01-15T10:30:00Z"
              ),
              "UserCount" => array (
                "type" => "integer",
                "description" => __ ( "Number of users in the tenant."),
                "example" => 25
              ),
              "ExtensionCount" => array (
                "type" => "integer",
                "description" => __ ( "Number of extensions in the tenant."),
                "example" => 150
              ),
              "GatewayCount" => array (
                "type" => "integer",
                "description" => __ ( "Number of gateways in the tenant."),
                "example" => 2
              ),
              "Country" => array (
                "type" => "string",
                "description" => __ ( "The ISO3166-2 (abbreviation of country with 2 letters) of the tenant."),
                "example" => __ ( "US")
              ),
              "TimeZone" => array (
                "type" => "string",
                "description" => __ ( "The time zone of the tenant."),
                "example" => __ ( "America/Los_Angeles")
              ),
              "Offset" => array (
                "type" => "float",
                "description" => __ ( "The time offset of the tenant."),
                "example" => -8
              ),
              "Language" => array (
                "type" => "string",
                "description" => __ ( "The default language of the tenant."),
                "example" => __ ( "en_US")
              ),
              "Currency" => array (
                "type" => "string",
                "description" => __ ( "The currency code of the tenant."),
                "example" => __ ( "BRL")
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
            "Status" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Status contains invalid values.")
            ),
            "Filter" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Fields contains invalid values.")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/tenants",
  "Read",
  "tenants_search",
  array (
    "permissions" => array ( "Super-Administrator"),
    "title" => __ ( "Search tenants"),
    "description" => __ ( "Search for system tenants.")
  )
);

/**
 * Function to search tenants.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add function parameters
   */
  $parameters["function"] = array (
    "DefaultFields" => "ID,Name,Domain,Status,CreatedAt",
    "PermittedFields" => "ID,Name,Domain,Status,StatusEN,Country,TimeZone,Offset,Currency,CreatedAt,UpdatedAt,UserCount,ExtensionCount,GatewayCount"
  );

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tenants_search_start"))
  {
    $parameters = framework_call ( "tenants_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Tenants");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Fields", $parameters) || $parameters["Fields"] == "" || sizeof ( $parameters["Fields"]) == 0)
  {
    $parameters["Fields"] = $parameters["function"]["DefaultFields"];
  }
  if ( ! api_filter_validate ( $parameters["Fields"], $parameters["function"]["PermittedFields"]))
  {
    $data["Fields"] = __ ( "Fields contains invalid values.");
  }
  if ( array_key_exists ( "Status", $parameters) && array_diff ( explode ( ",", $parameters["Status"]), array ( "Active", "Suspended")))
  {
    $data["Status"] = __ ( "Status contains invalid values.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tenants_search_validate"))
  {
    $data = framework_call ( "tenants_search_validate", $parameters);
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
  if ( framework_has_hook ( "tenants_search_sanitize"))
  {
    $parameters = framework_call ( "tenants_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tenants_search_pre"))
  {
    $parameters = framework_call ( "tenants_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search tenants
   */
  $where = "";
  $conditions = array ();
  if ( array_key_exists ( "Filter", $parameters))
  {
    $filter = $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]);
    $conditions[] = "(`Name` LIKE '%" . $filter . "%' OR `Domain` LIKE '%" . $filter . "%')";
  }
  if ( array_key_exists ( "Status", $parameters))
  {
    $conditions[] = "`Status` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Status"]) . "'";
  }
  if ( sizeof ( $conditions) != 0)
  {
    $where = " WHERE " . implode ( " AND ", $conditions);
  }
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Tenants`.`ID`, `Tenants`.`Name`, `Tenants`.`Domain`, `Tenants`.`Country`, `Tenants`.`TimeZone`, `Tenants`.`Offset`, `Currencies`.`Code` AS `Currency`, `Tenants`.`Status`, `Tenants`.`CreatedAt`, `Tenants`.`UpdatedAt`, COALESCE(`Users`.`Count`, 0) AS `UserCount`, COALESCE(`Extensions`.`Count`, 0) AS `ExtensionCount`, COALESCE(`Gateways`.`Count`, 0) AS `GatewayCount` FROM `Tenants` LEFT JOIN (SELECT `Tenant`, COUNT(*) AS `Count` FROM `Users` GROUP BY `Tenant`) `Users` ON `Tenants`.`ID` = `Users`.`Tenant` LEFT JOIN (SELECT `Tenant`, COUNT(*) AS `Count` FROM `Extensions` GROUP BY `Tenant`) `Extensions` ON `Tenants`.`ID` = `Extensions`.`Tenant` LEFT JOIN (SELECT `Tenant`, COUNT(*) AS `Count` FROM `Gateways` GROUP BY `Tenant`) `Gateways` ON `Tenants`.`ID` = `Gateways`.`Tenant` LEFT JOIN `Currencies` ON `Tenants`.`Currency` = `Currencies`.`ISO4217`" . $where))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], $parameters["function"]["DefaultFields"], $parameters["function"]["PermittedFields"]);
  while ( $result = $results->fetch_assoc ())
  {
    $result["StatusEN"] = $result["Status"];
    $result["Status"] = __ ( $result["Status"]);
    $result["CreatedAt"] = format_db_iso8601 ( $result["CreatedAt"]);
    $result["UpdatedAt"] = format_db_iso8601 ( $result["UpdatedAt"]);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tenants_search_post"))
  {
    $data = framework_call ( "tenants_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tenants_search_finish"))
  {
    framework_call ( "tenants_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get system tenant information
 */
framework_add_hook (
  "tenants_view",
  "tenants_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing system tenant information."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "integer",
              "description" => __ ( "The ID of the tenant."),
              "example" => 1
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name of the tenant organization."),
              "example" => __ ( "Acme Corporation")
            ),
            "Domain" => array (
              "type" => "string",
              "description" => __ ( "The domain for tenant access."),
              "example" => __ ( "example.com")
            ),
            "Status" => array (
              "type" => "string",
              "enum" => array ( __ ( "Active"), __ ( "Suspended")),
              "description" => __ ( "The status of the tenant."),
              "example" => __ ( "Active")
            ),
            "StatusEN" => array (
              "type" => "string",
              "enum" => array ( "Active", "Suspended"),
              "description" => __ ( "The status of the tenant in English."),
              "example" => "Active"
            ),
            "CreatedAt" => array (
              "type" => "string",
              "description" => __ ( "The date and time of tenant creation."),
              "example" => "2024-01-15T10:30:00Z"
            ),
            "UpdatedAt" => array (
              "type" => "string",
              "description" => __ ( "The date and time of latest tenant modification."),
              "example" => "2024-01-15T10:30:00Z"
            ),
            "UserCount" => array (
              "type" => "integer",
              "description" => __ ( "Number of users in the tenant."),
              "example" => 25
            ),
            "ExtensionCount" => array (
              "type" => "integer",
              "description" => __ ( "Number of extensions in the tenant."),
              "example" => 150
            ),
            "GatewayCount" => array (
              "type" => "integer",
              "description" => __ ( "Number of gateways in the tenant."),
              "example" => 2
            ),
            "Country" => array (
              "type" => "object",
              "description" => __ ( "The country code information of the tenant."),
              "properties" => array (
                "ID" => array (
                  "type" => "integer",
                  "description" => __ ( "The country code unique identifier."),
                  "example" => 840
                ),
                "NameEN" => array (
                  "type" => "string",
                  "description" => __ ( "The english name of the country."),
                  "example" => "United States of America"
                ),
                "Name" => array (
                  "type" => "string",
                  "description" => __ ( "The translated name of the country."),
                  "example" => __ ( "United States of America")
                ),
                "ISO3166-2" => array (
                  "type" => "string",
                  "description" => __ ( "The *ISO3166-2* of the country."),
                  "example" => __ ( "US")
                )
              )
            ),
            "TimeZone" => array (
              "type" => "string",
              "description" => __ ( "The time zone of the tenant."),
              "example" => __ ( "America/Los_Angeles")
            ),
            "Offset" => array (
              "type" => "float",
              "description" => __ ( "The time offset of the tenant."),
              "example" => -8
            ),
            "Language" => array (
              "type" => "object",
              "description" => __ ( "The language used on this tenant."),
              "properties" => array (
                "Code" => array (
                  "type" => "string",
                  "description" => __ ( "The code of the language."),
                  "example" => "en_US"
                ),
                "DescriptionEN" => array (
                  "type" => "string",
                  "description" => __ ( "The description in English of the language."),
                  "example" => "English (United States)"
                ),
                "Description" => array (
                  "type" => "string",
                  "description" => __ ( "The translated description of the language."),
                  "example" => __ ( "English (United States)")
                )
              )
            ),
            "Currency" => array (
              "type" => "string",
              "description" => __ ( "The currency code of the tenant."),
              "example" => __ ( "BRL")
            ),
            "AdminName" => array (
              "type" => "string",
              "description" => __ ( "The name of the tenant administrator."),
              "example" => __ ( "John Doe")
            ),
            "Username" => array (
              "type" => "string",
              "description" => __ ( "The username of the tenant administrator."),
              "example" => __ ( "johndoe")
            ),
            "Email" => array (
              "type" => "email",
              "description" => __ ( "The email of the tenant administrator."),
              "example" => __ ( "johndoe@voipdomain.io")
            )
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
              "example" => __ ( "Invalid tenant ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/tenants/:ID",
  "Read",
  "tenants_view",
  array (
    "permissions" => array ( "Super-Administrator"),
    "title" => __ ( "View tenant"),
    "description" => __ ( "View system tenant information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The tenant internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate system tenant information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tenants_view_start"))
  {
    $parameters = framework_call ( "tenants_view_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tenants_view_validate"))
  {
    $data = framework_call ( "tenants_view_validate", $parameters);
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
  if ( framework_has_hook ( "tenants_view_sanitize"))
  {
    $parameters = framework_call ( "tenants_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tenants_view_pre"))
  {
    $parameters = framework_call ( "tenants_view_pre", $parameters, false, $parameters);
  }

  /**
   * Get tenant from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Tenants`.`ID`, `Tenants`.`Name`, `Tenants`.`Domain`, `Tenants`.`Status`, `Tenants`.`Country`, `Tenants`.`TimeZone`, `Tenants`.`Offset`, `Currencies`.`Code` AS `Currency`, `Tenants`.`Language`, `Tenants`.`CreatedAt`, `Tenants`.`UpdatedAt`, `Countries`.`Name` AS `CountryName`, `Countries`.`ISO3166-2` AS `CountryISO`, COALESCE(`Users`.`Count`, 0) AS `UserCount`, COALESCE(`Extensions`.`Count`, 0) AS `ExtensionCount`, COALESCE(`Gateways`.`Count`, 0) AS `GatewayCount` FROM `Tenants` LEFT JOIN (SELECT `Tenant`, COUNT(*) AS `Count` FROM `Users` GROUP BY `Tenant`) `Users` ON `Tenants`.`ID` = `Users`.`Tenant` LEFT JOIN (SELECT `Tenant`, COUNT(*) AS `Count` FROM `Extensions` GROUP BY `Tenant`) `Extensions` ON `Tenants`.`ID` = `Extensions`.`Tenant` LEFT JOIN (SELECT `Tenant`, COUNT(*) AS `Count` FROM `Gateways` GROUP BY `Tenant`) `Gateways` ON `Tenants`.`ID` = `Gateways`.`Tenant` LEFT JOIN `Countries` ON `Tenants`.`Country` = `Countries`.`Code` LEFT JOIN `Currencies` ON `Tenants`.`Currency` = `Currencies`.`ISO4217` WHERE `Tenants`.`ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $tenant = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Get oldest tenant admin from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Name`, `Username`, `Email` FROM `Users` WHERE `Tenant` = " . (int) $parameters["ID"] . " ORDER BY `ID` ASC LIMIT 0,1"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $tenantadmin = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Format data
   */
  $tenant["Country"] = array ( "ID" => $tenant["Country"], "NameEN" => $tenant["CountryName"], "Name" => ( array_key_exists ( $tenant["CountryISO"], $_in["countries"][$_in["general"]["language"]]) ? $_in["countries"][$_in["general"]["language"]][$tenant["CountryISO"]] : $tenant["CountryName"]), "ISO3166-2" => $tenant["CountryISO"]);
  $tenant["Language"] = filters_call ( "get_locale", array ( "Code" => $tenant["Language"]));
  $tenant["StatusEN"] = $tenant["Status"];
  $tenant["Status"] = __ ( $tenant["Status"]);
  $tenant["CreatedAt"] = format_db_iso8601 ( $tenant["CreatedAt"]);
  $tenant["UpdatedAt"] = format_db_iso8601 ( $tenant["UpdatedAt"]);
  $tenant["AdminName"] = $tenantadmin["Name"];
  $tenant["Username"] = $tenantadmin["Username"];
  $tenant["Email"] = $tenantadmin["Email"];
  $data = api_filter_entry ( array ( "ID", "Name", "Domain", "Country", "TimeZone", "Offset", "Currency", "Language", "Status", "StatusEN", "CreatedAt", "UpdatedAt", "UserCount", "ExtensionCount", "GatewayCount", "AdminName", "Username", "Email"), $tenant);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tenants_view_post"))
  {
    $data = framework_call ( "tenants_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tenants_view_finish"))
  {
    framework_call ( "tenants_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new tenant
 */
framework_add_hook (
  "tenants_add",
  "tenants_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the tenant."),
          "required" => true,
          "example" => __ ( "Acme Corporation")
        ),
        "Domain" => array (
          "type" => "string",
          "description" => __ ( "The domain of the tenant."),
          "required" => true,
          "example" => __ ( "example.com")
        ),
        "Country" => array (
          "type" => "string",
          "description" => __ ( "The ISO3166-2 (abbreviation of country with 2 letters) of the tenant."),
          "required" => true,
          "minLength" => 2,
          "maxLength" => 2,
          "pattern" => "/^[A-Z]{2}$/",
          "example" => __ ( "US")
        ),
        "TimeZone" => array (
          "type" => "string",
          "description" => __ ( "The time zone of the tenant."),
          "required" => true,
          "pattern" => "^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$",
          "example" => __ ( "America/Los_Angeles")
        ),
        "Offset" => array (
          "type" => "float",
          "minimum" => -13,
          "maximum" => 13,
          "description" => __ ( "The time offset of the tenant."),
          "required" => true,
          "example" => -8
        ),
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The default language of the tenant."),
          "required" => true,
          "pattern" => "^[a-zA-Z]{2}(_[a-zA-Z]{2})?$",
          "example" => __ ( "en_US")
        ),
        "Currency" => array (
          "type" => "string",
          "description" => __ ( "The currency code of the tenant."),
          "required" => true,
          "minimum" => 3,
          "maximum" => 3,
          "pattern" => "/^[A-Z]{3}$/",
          "example" => __ ( "BRL")
        ),
        "AdminName" => array (
          "type" => "string",
          "description" => __ ( "The name of the tenant administrator."),
          "required" => true,
          "example" => __ ( "John Doe")
        ),
        "Username" => array (
          "type" => "string",
          "description" => __ ( "The username of the tenant administrator."),
          "required" => true,
          "example" => __ ( "johndoe")
        ),
        "Email" => array (
          "type" => "email",
          "description" => __ ( "The email of the tenant administrator."),
          "required" => true,
          "example" => __ ( "johndoe@voipdomain.io")
        ),
        "Password" => array (
          "type" => "password",
          "description" => __ ( "The password of the tenant administrator."),
          "required" => true,
          "example" => __ ( "mypassword")
        ),
        "Confirmation" => array (
          "type" => "password",
          "description" => __ ( "The confirmation of the tenant administrator password."),
          "required" => true,
          "example" => __ ( "mypassword")
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system tenanted added successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The tenant name is required.")
            ),
            "Domain" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The tenant domain must be valid.")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/tenants",
  "Create",
  "tenants_add",
  array (
    "permissions" => array ( "Super-Administrator"),
    "title" => __ ( "Add tenants"),
    "description" => __ ( "Add a new system tenant.")
  )
);

/**
 * Function to add a new tenant.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tenants_add_start"))
  {
    $parameters = framework_call ( "tenants_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"]))) == "")
  {
    $data["Name"] = __ ( "The tenant name is required.");
  }
  if ( empty ( $parameters["Domain"]))
  {
    $data["Domain"] = __ ( "The tenant domain is required.");
  }
  if ( ! array_key_exists ( "Domain", $data) && ! preg_match ( "/^([A-Za-z0-9-]+)([\.]{1})([A-Za-z0-9]+)([\.]{0,1})([A-Za-z0-9]*)$/i", $parameters["Domain"]))
  {
    $data["Domain"] = __ ( "The tenant domain must be valid.");
  }
  if ( ! preg_match ( "/^[A-Z]{2}$/", $parameters["Country"]))
  {
    $data["Country"] = __ ( "The tenant country is invalid.");
  }
  if ( ! array_key_exists ( "Country", $data) && empty ( $parameters["Country"]))
  {
    $data["Country"] = __ ( "The tenant country is required.");
  }
  if ( empty ( $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The tenant time zone is required.");
  }
  if ( ! array_key_exists ( "TimeZone", $data) && ! preg_match ( "/^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$/", $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The tenant time zone is invalid.");
  }
  if ( empty ( $parameters["Offset"]))
  {
    $data["Offset"] = __ ( "The tenant time offset is required.");
  }
  if ( ! array_key_exists ( "Offset", $data) && ( $parameters["Offset"] < -13 || $parameters["Offset"] > 13))
  {
    $data["Offset"] = __ ( "The tenant time offset is invalid.");
  }
  if ( ! preg_match ( "/^[a-zA-Z]{2}(_[a-zA-Z]{2})?$/", $parameters["Language"]) || ! array_key_exists ( $parameters["Language"], $_in["languages"]))
  {
    $data["Language"] = __ ( "The tenant language is invalid.");
  }
  if ( empty ( $parameters["Currency"]))
  {
    $data["Currency"] = __ ( "The tenant currency is required.");
  }
  if ( preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["AdminName"]))) == "")
  {
    $data["AdminName"] = __ ( "The tenant administrator name is required.");
  }
  if ( preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Username"]))) == "")
  {
    $data["Username"] = __ ( "The tenant administrator login name is required.");
  }
  if ( empty ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The tenant administrator e-mail is required.");
  }
  if ( ! empty ( $parameters["Email"]) && ! validate_email ( $parameters["Email"]))
  {
    $data["Email"] = __ ( "The informed tenant administrator e-mail is invalid.");
  }
  if ( empty ( $parameters["Password"]))
  {
    $data["Password"] = __ ( "The tenant administrator password is required.");
  }
  if ( empty ( $parameters["Confirmation"]))
  {
    $data["Confirmation"] = __ ( "The tenant administrator confirmation password is required.");
  }
  if ( ! empty ( $parameters["Password"]) && ! empty ( $parameters["Confirmation"]) && $parameters["Password"] != $parameters["Confirmation"])
  {
    $data["Confirmation"] = __ ( "The passwords didn't match.");
  }

  /**
   * Check if provided currency is recognized by the system
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Currencies` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Currency"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $currency = $result->fetch_assoc ())
  {
    if ( ! array_key_exists ( "Currency", $data))
    {
      $data["Currency"] = __ ( "The informed currency is invalid.");
    }
  }

  /**
   * Check if provided country is recognized by the system
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Countries` WHERE `ISO3166-2` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Country"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $country = $result->fetch_assoc ())
  {
    if ( ! array_key_exists ( "Country", $data))
    {
      $data["Country"] = __ ( "The informed country is invalid.");
    }
  }

  /**
   * Check if domain was already added
   */
  if ( ! array_key_exists ( "Domain", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tenants` WHERE `Domain` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Domain"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Domain"] = __ ( "The provided domain was already in use.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tenants_add_validate"))
  {
    $data = framework_call ( "tenants_add_validate", $parameters, false, $data);
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
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  $parameters["Domain"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Domain"])));
  $parameters["AdminName"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["AdminName"])));
  $parameters["Username"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Username"])));
  $parameters["Email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Email"])));
  $parameters["Salt"] = secure_rand ( 32);
  $parameters["Permissions"] = array ( "Administrator");

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "tenants_add_sanitize"))
  {
    $parameters = framework_call ( "tenants_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tenants_add_pre"))
  {
    $parameters = framework_call ( "tenants_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new tenant record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Tenants` (`Name`, `Domain`, `Status`, `Settings`, `Country`, `TimeZone`, `Offset`, `Currency`, `Language`, `CreatedAt`, `UpdatedAt`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Domain"]) . "', 'Active', '[]', ". (int) $country["Code"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["TimeZone"]) . "', " . (float) $parameters["Offset"] . ", " . (int) $currency["ISO4217"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "', NOW(), NOW())"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Add new tenant administrator user
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Users` (`Tenant`, `Name`, `Username`, `Password`, `Permissions`, `Email`, `Since`, `Salt`, `Iterations`, `Language`) VALUES (" . (int) $parameters["ID"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["AdminName"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Username"]) . "', '" . hash_pbkdf2 ( "sha256", $parameters["Password"], $parameters["Salt"], ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000), 64) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Permissions"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Email"]) . "', NOW(), '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Salt"]) . "', " . ( $_in["security"]["iterations"] != 0 ? $_in["security"]["iterations"] : 40000) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tenants_add_post"))
  {
    framework_call ( "tenants_add_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tenants_add_finish"))
  {
    framework_call ( "tenants_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "tenants/" . $parameters["ID"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to change system multi-tenant
 */
framework_add_hook (
  "tenants_edit",
  "tenants_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name of the tenant."),
          "required" => true,
          "example" => __ ( "Acme Corporation")
        ),
        "Domain" => array (
          "type" => "string",
          "description" => __ ( "The domain of the tenant."),
          "required" => true,
          "example" => __ ( "example.com")
        ),
        "Country" => array (
          "type" => "string",
          "description" => __ ( "The ISO3166-2 (abbreviation of country with 2 letters) of the tenant."),
          "required" => true,
          "minLength" => 2,
          "maxLength" => 2,
          "pattern" => "/^[A-Z]{2}$/",
          "example" => __ ( "US")
        ),
        "TimeZone" => array (
          "type" => "string",
          "description" => __ ( "The time zone of the tenant."),
          "required" => true,
          "pattern" => "^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$",
          "example" => __ ( "America/Los_Angeles")
        ),
        "Offset" => array (
          "type" => "float",
          "minimum" => -13,
          "maximum" => 13,
          "description" => __ ( "The time offset of the tenant."),
          "required" => true,
          "example" => -8
        ),
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The default language of the tenant."),
          "required" => true,
          "pattern" => "^[a-zA-Z]{2}(_[a-zA-Z]{2})?$",
          "example" => __ ( "en_US")
        ),
        "Currency" => array (
          "type" => "string",
          "description" => __ ( "The currency code of the tenant."),
          "required" => true,
          "minimum" => 3,
          "maximum" => 3,
          "pattern" => "/^[A-Z]{3}$/",
          "example" => __ ( "BRL")
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "System tenant updated successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The tenant name is required.")
            ),
            "Domain" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The tenant domain must be valid.")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/tenants/:ID",
  array ( "Modify", "Edit"),
  "tenants_edit",
  array (
    "permissions" => array ( "Super-Administrator"),
    "title" => __ ( "Edit tenant"),
    "description" => __ ( "Edit a system tenant.")
  )
);

/**
 * Function to change system tenant.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tenants_edit_start"))
  {
    $parameters = framework_call ( "tenants_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"]))) == "")
  {
    $data["Name"] = __ ( "The tenant name is required.");
  }
  if ( empty ( $parameters["Domain"]))
  {
    $data["Domain"] = __ ( "The tenant domain is required.");
  }
  if ( ! array_key_exists ( "Domain", $data) && ! preg_match ( "/^([A-Za-z0-9-]+)([\.]{1})([A-Za-z0-9]+)([\.]{0,1})([A-Za-z0-9]*)$/i", $parameters["Domain"]))
  {
    $data["Domain"] = __ ( "The tenant domain must be valid.");
  }
  if ( ! preg_match ( "/^[A-Z]{2}$/", $parameters["Country"]))
  {
    $data["Country"] = __ ( "The tenant country is invalid.");
  }
  if ( ! array_key_exists ( "Country", $data) && empty ( $parameters["Country"]))
  {
    $data["Country"] = __ ( "The tenant country is required.");
  }
  if ( empty ( $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The tenant time zone is required.");
  }
  if ( ! array_key_exists ( "TimeZone", $data) && ! preg_match ( "/^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$/", $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The tenant time zone is invalid.");
  }
  if ( empty ( $parameters["Offset"]))
  {
    $data["Offset"] = __ ( "The tenant time offset is required.");
  }
  if ( ! array_key_exists ( "Offset", $data) && ( $parameters["Offset"] < -13 || $parameters["Offset"] > 13))
  {
    $data["Offset"] = __ ( "The tenant time offset is invalid.");
  }
  if ( ! preg_match ( "/^[a-zA-Z]{2}(_[a-zA-Z]{2})?$/", $parameters["Language"]) || ! array_key_exists ( $parameters["Language"], $_in["languages"]))
  {
    $data["Language"] = __ ( "The tenant language is invalid.");
  }
  if ( empty ( $parameters["Currency"]))
  {
    $data["Currency"] = __ ( "The tenant currency is required.");
  }

  /**
   * Check if provided currency is recognized by the system
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Currencies` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Currency"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $currency = $result->fetch_assoc ())
  {
    if ( ! array_key_exists ( "Currency", $data))
    {
      $data["Currency"] = __ ( "The informed currency is invalid.");
    }
  }

  /**
   * Check if provided country is recognized by the system
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Countries` WHERE `ISO3166-2` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Country"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $country = $result->fetch_assoc ())
  {
    if ( ! array_key_exists ( "Country", $data))
    {
      $data["Country"] = __ ( "The informed country is invalid.");
    }
  }

  /**
   * Check if domain was already in use
   */
  if ( ! array_key_exists ( "Domain", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tenants` WHERE `Domain` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Domain"]) . "' AND `ID` != " . (int) $parameters["ID"]))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Domain"] = __ ( "The provided domain was already in use.");
    }
  }

  /**
   * Check if tenant exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tenants` WHERE `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tenants_edit_validate"))
  {
    $data = framework_call ( "tenants_edit_validate", $parameters, false, $data);
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
  $parameters["Name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Name"])));
  $parameters["Domain"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Domain"])));

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "tenants_edit_sanitize"))
  {
    $parameters = framework_call ( "tenants_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tenants_edit_pre"))
  {
    $parameters = framework_call ( "tenants_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change tenant entry
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Tenants` SET `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', `Domain` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Domain"]) . "', `Country` = ". (int) $country["Code"] . ", `TimeZone` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["TimeZone"]) . "', `Offset` = " . (float) $parameters["Offset"] . ", `Currency` = " . (int) $currency["ISO4217"] . ", `Language` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "' WHERE `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tenants_edit_post"))
  {
    framework_call ( "tenants_edit_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tenants_edit_finish"))
  {
    framework_call ( "tenants_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to remove a tenant
 */
framework_add_hook (
  "tenants_remove",
  "tenants_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system tenant was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid tenant ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_api_call (
  "/tenants/:ID",
  "Delete",
  "tenants_remove",
  array (
    "permissions" => array ( "Super-Administrator"),
    "title" => __ ( "Remove tenants"),
    "description" => __ ( "Remove a tenant from system.")
  )
);

/**
 * Function to remove an existing tenant.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tenants_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "tenants_remove_start"))
  {
    $parameters = framework_call ( "tenants_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid tenant ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "tenants_remove_validate"))
  {
    $data = framework_call ( "tenants_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "tenants_remove_sanitize"))
  {
    $parameters = framework_call ( "tenants_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if tenant exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tenants` WHERE `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "tenants_remove_pre"))
  {
    $parameters = framework_call ( "tenants_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove tenant database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Tenants` WHERE `ID` = " . (int) $parameters["ID"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "tenants_remove_post"))
  {
    framework_call ( "tenants_remove_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "tenants_remove_finish"))
  {
    framework_call ( "tenants_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}
?>
