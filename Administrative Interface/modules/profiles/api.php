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
 * VoIP Domain profiles module API. This module add the API calls related to
 * profiles.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Profiles
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search profiles
 */
framework_add_hook (
  "profiles_search",
  "profiles_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all profiles."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Domain,Country,AreaCode,InUse",
          "example" => "Description,Domain,Country,AreaCode"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system profiles."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "profile"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the profile."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the profile."),
                "example" => __ ( "Default profile")
              ),
              "Domain" => array (
                "type" => "string",
                "description" => __ ( "The SIP domain of the profile."),
                "example" => "voipdomain.io"
              ),
              "Country" => array (
                "type" => "string",
                "description" => __ ( "The country ISO 3166-2 code of the profile."),
                "example" => __ ( "US")
              ),
              "AreaCode" => array (
                "type" => "integer",
                "description" => __ ( "The area code of the profile."),
                "example" => 704
              ),
              "InUse" => array (
                "type" => "boolean",
                "description" => __ ( "If the profile are in use at some server."),
                "example" => true
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
framework_add_permission ( "profiles_search", __ ( "Search profiles"));
framework_add_api_call (
  "/profiles",
  "Read",
  "profiles_search",
  array (
    "permissions" => array ( "user", "profiles_search"),
    "title" => __ ( "Search profiles"),
    "description" => __ ( "Search for profiles.")
  )
);

/**
 * Function to search profiles.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "profiles_search_start"))
  {
    $parameters = framework_call ( "profiles_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Profiles");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "profiles_search_validate"))
  {
    $data = framework_call ( "profiles_search_validate", $parameters);
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
  if ( framework_has_hook ( "profiles_search_sanitize"))
  {
    $parameters = framework_call ( "profiles_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "profiles_search_pre"))
  {
    $parameters = framework_call ( "profiles_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search profiles
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Profiles`.`ID`, `Profiles`.`Description`, `Profiles`.`Domain`, `Profiles`.`AreaCode`, `Countries`.`ISO3166-2` AS `Country` FROM `Profiles` LEFT JOIN `Countries` ON `Profiles`.`Country` = `Countries`.`Code`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Profiles`.`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Profiles`.`Description`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Domain,Country,AreaCode,InUse", "ID,Description,Domain,Country,AreaCode,InUse");
  while ( $result = $results->fetch_assoc ())
  {
    $result["InUse"] = ( sizeof ( filters_call ( "profile_inuse", array ( "ID" => $result["ID"]))) != 0);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "profiles_search_post"))
  {
    $data = framework_call ( "profiles_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "profiles_search_finish"))
  {
    framework_call ( "profiles_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get profile information
 */
framework_add_hook (
  "profiles_view",
  "profiles_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the profile."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the profile."),
              "example" => __ ( "Default Profile")
            ),
            "Domain" => array (
              "type" => "string",
              "description" => __ ( "The SIP domain of the profile."),
              "example" => "voipdomain.io"
            ),
            "Country" => array (
              "type" => "object",
              "description" => __ ( "The country code information of the profile."),
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
              "description" => __ ( "The time zone of the profile."),
              "example" => "America/Los_Angeles"
            ),
            "Offset" => array (
              "type" => "float",
              "description" => __ ( "The time offset of the profile."),
              "example" => -8
            ),
            "AreaCode" => array (
              "type" => "integer",
              "description" => __ ( "The area code of the profile."),
              "example" => 704
            ),
            "Prefix" => array (
              "type" => "string",
              "description" => __ ( "The prefix to access PSTN of the profile."),
              "example" => "0"
            ),
            "NGGW" => array (
              "type" => "object",
              "description" => __ ( "The gateway for non geographic calls used on this profile."),
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
                  "description" => __ ( "If the gateway is active or not."),
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
                  "description" => __ ( "The translated priority of the gateway."),
                  "example" => __ ( "High")
                ),
                "PriorityEN" => array (
                  "type" => "string",
                  "enum" => array ( "High", "Medium", "Low"),
                  "description" => __ ( "The priority of the gateway."),
                  "example" => "High"
                ),
                "Number" => array (
                  "type" => "string",
                  "description" => __ ( "The telephone number of the gateway (E.164 format)."),
                  "example" => __ ( "+1 123 5551212"),
                  "required" => false
                )
              )
            ),
            "Blockeds" => array (
              "type" => "array",
              "description" => __ ( "The list of gateways not allowed to this profile."),
              "xml" => array (
                "name" => "Blockeds",
                "wrapped" => true
              ),
              "items" => array (
                "\$ref" => "#/components/schemas/gateway"
              )
            ),
            "Gateways" => array (
              "type" => "array",
              "description" => __ ( "The list of allowed gateways (in order of preference) to this profile."),
              "xml" => array (
                "name" => "Gateways",
                "wrapped" => true
              ),
              "items" => array (
                "\$ref" => "#/components/schemas/gateway"
              )
            ),
            "Language" => array (
              "type" => "object",
              "description" => __ ( "The language used on this profile."),
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
            "MOH" => array (
              "type" => "object",
              "description" => __ ( "The Music On Hold (MOH) used on this profile."),
              "properties" => array (
                "ID" => array (
                  "type" => "integer",
                  "description" => __ ( "The internal unique identifier audio file for music on hold."),
                  "example" => 1
                ),
                "Filename" => array (
                  "type" => "string",
                  "description" => __ ( "The audio file name for music on hold for this profile."),
                  "example" => __ ( "defaultmoh.mp3")
                ),
                "Description" => array (
                  "type" => "string",
                  "description" => __ ( "The audio file description for music on hold for this profile."),
                  "example" => __ ( "Company MOH file")
                )
              )
            ),
            "Emergency" => array (
              "type" => "boolean",
              "description" => __ ( "If calls for emergency numbers are allowed at this profile."),
              "example" => true
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
              "example" => __ ( "Invalid profile ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "profiles_view", __ ( "View profiles information"));
framework_add_api_call (
  "/profiles/:ID",
  "Read",
  "profiles_view",
  array (
    "permissions" => array ( "user", "profiles_view"),
    "title" => __ ( "View profiles"),
    "description" => __ ( "Get a profile information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The profile internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate profile information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "profiles_view_start"))
  {
    $parameters = framework_call ( "profiles_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Profiles", "Countries", "Gateways"));

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid profile ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "profiles_view_validate"))
  {
    $data = framework_call ( "profiles_view_validate", $parameters);
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
  if ( framework_has_hook ( "profiles_view_sanitize"))
  {
    $parameters = framework_call ( "profiles_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "profiles_view_pre"))
  {
    $parameters = framework_call ( "profiles_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search profiles
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Profiles`.*, `Countries`.`Name` AS `CountryName`, `Countries`.`ISO3166-2` AS `CountryISO`, `Gateways`.`Description` AS `NGGWDescription`, `Gateways`.`Type` AS `NGGWType`, `Gateways`.`Active` AS `NGGWActive`, `Audios`.`Description` AS `MohDescription` FROM `Profiles` LEFT JOIN `Countries` ON `Profiles`.`Country` = `Countries`.`Code` LEFT JOIN `Gateways` ON `Profiles`.`NGGW` = `Gateways`.`ID` LEFT JOIN `Audios` ON `Profiles`.`MOH` = `Audios`.`ID` WHERE `Profiles`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $profile = $result->fetch_assoc ();

  /**
   * Format data
   */
  $profile["Country"] = array ( "ID" => $profile["Country"], "NameEN" => $profile["CountryName"], "Name" => ( array_key_exists ( $profile["CountryISO"], $_in["countries"][$_in["general"]["language"]]) ? $_in["countries"][$_in["general"]["language"]][$profile["CountryISO"]] : $profile["CountryName"]), "ISO3166-2" => $profile["CountryISO"]);
  $profile["NGGW"] = filters_call ( "get_gateways", array ( "ID" => $profile["NGGW"]))[0];
  $profile["Blockeds"] = filters_call ( "get_gateways", array ( "ID" => explode ( ",", $profile["BlockedGW"])));
  $profile["Gateways"] = filters_call ( "get_gateways", array ( "ID" => explode ( ",", $profile["DefaultGW"])));
  $profile["Language"] = filters_call ( "get_locale", array ( "Code" => $profile["Language"]));
  if ( $profile["MOH"] != 0)
  {
    $profile["MOH"] = filters_call ( "get_audios", array ( "ID" => $profile["MOH"]))[0];
  } else {
    $profile["MOH"] = array ();
  }
  $profile["Emergency"] = (boolean) $profile["EmergencyShortcut"];
  $data = api_filter_entry ( array ( "Description", "Domain", "Country", "TimeZone", "Offset", "AreaCode", "Prefix", "NGGW", "Blockeds", "Gateways", "Language", "MOH", "Emergency"), $profile);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "profiles_view_post"))
  {
    $data = framework_call ( "profiles_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "profiles_view_finish"))
  {
    framework_call ( "profiles_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new profile
 */
framework_add_hook (
  "profiles_add",
  "profiles_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the profile."),
          "required" => true,
          "example" => __ ( "Default Profile")
        ),
        "Domain" => array (
          "type" => "string",
          "description" => __ ( "The SIP domain of the profile."),
          "required" => true,
          "example" => "voipdomain.io"
        ),
        "Country" => array (
          "type" => "string",
          "description" => __ ( "The country ISO 3166-2 code of the profile."),
          "required" => true,
          "pattern" => "^[A-Z]{2}$",
          "example" => __ ( "US")
        ),
        "TimeZone" => array (
          "type" => "string",
          "description" => __ ( "The time zone of the profile."),
          "required" => true,
          "pattern" => "^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$",
          "example" => "America/Los_Angeles"
        ),
        "Offset" => array (
          "type" => "float",
          "minimum" => -13,
          "maximum" => 13,
          "description" => __ ( "The time offset of the profile."),
          "required" => true,
          "example" => -8
        ),
        "AreaCode" => array (
          "type" => "integer",
          "description" => __ ( "The area code of the profile."),
          "required" => true,
          "example" => 704
        ),
        "Prefix" => array (
          "type" => "string",
          "description" => __ ( "The prefix to access PSTN of the profile."),
          "required" => true,
          "pattern" => "^[0-9]$",
          "example" => "0"
        ),
        "NGGW" => array (
          "type" => "integer",
          "description" => __ ( "The system unique identifier of the gateway used for non-geographic calls with the profile."),
          "required" => true,
          "example" => 1
        ),
        "Blockeds" => array (
          "type" => "array",
          "description" => __ ( "The system unique identifier list of not allowed gateways to this profile."),
          "items" => array (
            "type" => "integer",
            "description" => __ ( "The system unique identifier of the gateway."),
            "example" => 1
          )
        ),
        "Gateways" => array (
          "type" => "array",
          "description" => __ ( "The system unique identifier list of allowed gateways to this profile."),
          "required" => true,
          "minItems" => 1,
          "items" => array (
            "type" => "integer",
            "description" => __ ( "The system unique identifier of the gateway."),
            "example" => 1
          )
        ),
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The language to be used to configurations at this profile."),
          "required" => true,
          "pattern" => "^[a-zA-Z]{2}(_[a-zA-Z]{2})?$",
          "example" => __ ( "en_US")
        ),
        "MOH" => array (
          "type" => "integer",
          "description" => __ ( "The system unique identifier audio file for music on hold for this profile."),
          "example" => 1
        ),
        "Emergency" => array (
          "type" => "boolean",
          "description" => __ ( "If calls for emergency numbers are allowed at this profile."),
          "required" => true,
          "example" => true
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system profile added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile description is required.")
            ),
            "Domain" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile domain is required.")
            ),
            "Country" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile country is required.")
            ),
            "TimeZone" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile time zone is required.")
            ),
            "Offset" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile time offset is invalid.")
            ),
            "AreaCode" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile area code is required.")
            ),
            "Prefix" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The PSTN access prefix is invalid.")
            ),
            "NGGW" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile non geographic gateway calls is required.")
            ),
            "MOH" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected music on hold file is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "profiles_add", __ ( "Add profiles"));
framework_add_api_call (
  "/profiles",
  "Create",
  "profiles_add",
  array (
    "permissions" => array ( "user", "profiles_add"),
    "title" => __ ( "Add profiles"),
    "description" => __ ( "Add a new system profile.")
  )
);

/**
 * Function to add a new profile.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "profiles_add_start"))
  {
    $parameters = framework_call ( "profiles_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The profile description is required.");
  }
  $parameters["Domain"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Domain"])));
  if ( empty ( $parameters["Domain"]))
  {
    $data["Domain"] = __ ( "The profile domain is required.");
  }
  if ( ! preg_match ( "/^[A-Z]{2}$/", $parameters["Country"]))
  {
    $data["Country"] = __ ( "The profile country is invalid.");
  }
  if ( ! array_key_exists ( "Country", $data) && empty ( $parameters["Country"]))
  {
    $data["Country"] = __ ( "The profile country is required.");
  }
  if ( empty ( $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The profile time zone is required.");
  }
  if ( ! array_key_exists ( "TimeZone", $data) && ! preg_match ( "/^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$/", $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The profile time zone is invalid.");
  }
  if ( empty ( $parameters["Offset"]))
  {
    $data["Offset"] = __ ( "The profile time offset is required.");
  }
  if ( ! array_key_exists ( "Offset", $data) && ( $parameters["Offset"] < -13 || $parameters["Offset"] > 13))
  {
    $data["Offset"] = __ ( "The profile time offset is invalid.");
  }
  if ( empty ( $parameters["AreaCode"]))
  {
    $data["AreaCode"] = __ ( "The profile area code is required.");
  }
  if ( ! array_key_exists ( "AreaCode", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["AreaCode"]))
  {
    $data["AreaCode"] = __ ( "The profile area code is invalid.");
  }
  if ( ! preg_match ( "/^[a-zA-Z]{2}(_[a-zA-Z]{2})?$/", $parameters["Language"]))
  {
    $data["Language"] = __ ( "The profile language is invalid.");
  }
  if ( $parameters["Prefix"] == "")
  {
    $data["Prefix"] = __ ( "The PSTN access prefix is required.");
  }
  if ( ! array_key_exists ( "Prefix", $data) && ! ( strlen ( $parameters["Prefix"]) == 1 && ord ( $parameters["Prefix"]) >= 48 && ord ( $parameters["Prefix"]) <= 57))
  {
    $data["Prefix"] = __ ( "The PSTN access prefix is invalid.");
  }
  if ( empty ( $parameters["NGGW"]))
  {
    $data["NGGW"] = __ ( "The profile non geographic gateway calls is required.");
  }
  if ( ! is_array ( $parameters["Blockeds"]))
  {
    if ( ! empty ( $parameters["Blockeds"]))
    {
      $parameters["Blockeds"] = array ( $parameters["Blockeds"]);
    } else {
      $parameters["Blockeds"] = array ();
    }
  }
  if ( ! is_array ( $parameters["Gateways"]))
  {
    if ( ! empty ( $parameters["Gateways"]))
    {
      $parameters["Gateways"] = array ( $parameters["Gateways"]);
    } else {
      $parameters["Gateways"] = array ();
    }
  }

  /**
   * If provided a music on hold, check if exist
   */
  if ( $parameters["MOH"])
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["MOH"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["MOH"] = __ ( "The selected music on hold file is invalid.");
    }
  }

  /**
   * Check if country exists
   */
  if ( ! array_key_exists ( "Country", $data))
  {
    $check = filters_call ( "get_countries", array ( "ISO3166-2" => $parameters["Country"]));
    if ( sizeof ( $check) != 1)
    {
      $data["Country"] = __ ( "The selected country is invalid.");
    } else {
      $country = $check[0];
    }
  }

  /**
   * If provided some gateway, check if exists
   */
  $gateways = array ();
  if ( array_key_exists ( "Gateways", $parameters) && sizeof ( $parameters["Gateways"]) != 0)
  {
    foreach ( $parameters["Gateways"] as $gateway)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $gateway)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 1)
      {
        $gateways[] = $gateway;
      } else {
        $data["Gateways"] = __ ( "One or more gateways are invalid.");
      }
    }
  }
  $parameters["Gateways"] = $gateways;

  /**
   * If provided some blocked gateway, check if exists
   */
  $blockeds = array ();
  if ( array_key_exists ( "Blockeds", $parameters) && sizeof ( $parameters["Blockeds"]) != 0)
  {
    foreach ( $parameters["Blockeds"] as $gateway)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $gateway)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 1)
      {
        $blockeds[] = $gateway;
      } else {
        $data["Blockeds"] = __ ( "One or more blocked gateways are invalid.");
      }
    }
  }
  $parameters["Blockeds"] = $blockeds;

  /**
   * If provided a non geographic gateway, check if exist
   */
  if ( ! array_key_exists ( "NGGW", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["NGGW"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["NGGW"] = __ ( "The selected non geographic calls gateway is invalid.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "profiles_add_validate"))
  {
    $data = framework_call ( "profiles_add_validate", $parameters, false, $data);
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
  $parameters["AreaCode"] = (int) $parameters["AreaCode"];
  $parameters["MOH"] = (int) $parameters["MOH"];
  $parameters["Emergency"] = (boolean) $parameters["Emergency"];
  $parameters["NGGW"] = (int) ( $parameters["NGGW"] < 0 ? $parameters["NGGW"] * -1 : $parameters["NGGW"]);
  foreach ( $parameters["Blockeds"] as $key => $value)
  {
    $parameters["Blockeds"][$key] = (int) ( $value < 0 ? $value * -1 : $value);
  }
  foreach ( $parameters["Gateways"] as $key => $value)
  {
    $parameters["Gateways"][$key] = (int) ( $value < 0 ? $value * -1 : $value);
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "profiles_add_sanitize"))
  {
    $parameters = framework_call ( "profiles_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "profiles_add_pre"))
  {
    $parameters = framework_call ( "profiles_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new profile record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Profiles` (`Description`, `Domain`, `Country`, `TimeZone`, `Offset`, `AreaCode`, `Language`, `Prefix`, `MOH`, `EmergencyShortcut`, `NGGW`, `DefaultGW`, `BlockedGW`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Domain"]) . "', ". $_in["mysql"]["id"]->real_escape_string ( $country["Code"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["TimeZone"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( (float) $parameters["Offset"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["AreaCode"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Prefix"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["MOH"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Emergency"] ? 1 : 0) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["NGGW"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $gateways)) . "', '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $blockeds)) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "profiles_add_post"))
  {
    framework_call ( "profiles_add_post", $parameters);
  }

  /**
   * Add new profile at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["ID"], "Description" => $parameters["Description"], "Domain" => $parameters["Domain"], "Country" => $parameters["Country"], "TimeZone" => $parameters["TimeZone"], "Offset" => (float) $parameters["Offset"], "AreaCode" => $parameters["AreaCode"], "Language" => $parameters["Language"], "Prefix" => $parameters["Prefix"], "MOH" => $parameters["MOH"], "EmergencyShortcut" => $parameters["Emergency"], "NGGW" => $parameters["NGGW"], "Gateways" => $gateways, "Blockeds" => $blockeds);
  if ( framework_has_hook ( "profiles_add_notify"))
  {
    $notify = framework_call ( "profiles_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "profile_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "profiles_add_finish"))
  {
    framework_call ( "profiles_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/profiles/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing profile
 */
framework_add_hook (
  "profiles_edit",
  "profiles_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the profile."),
          "required" => true,
          "example" => __ ( "Default Profile")
        ),
        "Domain" => array (
          "type" => "string",
          "description" => __ ( "The SIP domain of the profile."),
          "required" => true,
          "example" => "voipdomain.io"
        ),
        "Country" => array (
          "type" => "string",
          "description" => __ ( "The country ISO 3166-2 code of the profile."),
          "required" => true,
          "pattern" => "^[A-Z]{2}$",
          "example" => __ ( "US")
        ),
        "TimeZone" => array (
          "type" => "string",
          "description" => __ ( "The time zone of the profile."),
          "required" => true,
          "pattern" => "^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$",
          "example" => "America/Los_Angeles"
        ),
        "Offset" => array (
          "type" => "float",
          "minimum" => -13,
          "maximum" => 13,
          "description" => __ ( "The time offset of the profile."),
          "required" => true,
          "example" => -8
        ),
        "AreaCode" => array (
          "type" => "integer",
          "description" => __ ( "The area code of the profile."),
          "required" => true,
          "example" => 704
        ),
        "Prefix" => array (
          "type" => "string",
          "description" => __ ( "The prefix to access PSTN of the profile."),
          "required" => true,
          "pattern" => "^[0-9]$",
          "example" => "0"
        ),
        "NGGW" => array (
          "type" => "integer",
          "description" => __ ( "The system unique identifier of the gateway used for non-geographic calls with the profile."),
          "required" => true,
          "example" => 1
        ),
        "Blockeds" => array (
          "type" => "array",
          "description" => __ ( "The system unique identifier list of not allowed gateways to this profile."),
          "items" => array (
            "type" => "integer",
            "description" => __ ( "The system unique identifier of the gateway."),
            "example" => 1
          )
        ),
        "Gateways" => array (
          "type" => "array",
          "description" => __ ( "The system unique identifier list of allowed gateways to this profile."),
          "required" => true,
          "minItems" => 1,
          "items" => array (
            "type" => "integer",
            "description" => __ ( "The system unique identifier of the gateway."),
            "example" => 1
          )
        ),
        "Language" => array (
          "type" => "string",
          "description" => __ ( "The language to be used to configurations at this profile."),
          "required" => true,
          "pattern" => "^[a-zA-Z]{2}(_[a-zA-Z]{2})?$",
          "example" => __ ( "en_US")
        ),
        "MOH" => array (
          "type" => "integer",
          "description" => __ ( "The system unique identifier audio file for music on hold for this profile."),
          "example" => 1
        ),
        "Emergency" => array (
          "type" => "boolean",
          "description" => __ ( "If calls for emergency numbers are allowed at this profile."),
          "required" => true,
          "example" => true
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system profile was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile description is required.")
            ),
            "Domain" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile domain is required.")
            ),
            "Country" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile country is required.")
            ),
            "TimeZone" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile time zone is required.")
            ),
            "Offset" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile time offset is invalid.")
            ),
            "AreaCode" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile area code is required.")
            ),
            "Prefix" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The PSTN access prefix is invalid.")
            ),
            "NGGW" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The profile non geographic gateway calls is required.")
            ),
            "MOH" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The selected music on hold file is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "profiles_edit", __ ( "Edit profiles"));
framework_add_api_call (
  "/profiles/:ID",
  "Modify",
  "profiles_edit",
  array (
    "permissions" => array ( "user", "profiles_edit"),
    "title" => __ ( "Edit profiles"),
    "description" => __ ( "Change a system profile information.")
  )
);
framework_add_api_call (
  "/profiles/:ID",
  "Edit",
  "profiles_edit",
  array (
    "permissions" => array ( "user", "profiles_edit"),
    "title" => __ ( "Edit profiles"),
    "description" => __ ( "Change a system profile information.")
  )
);

/**
 * Function to edit an existing profile.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "profiles_edit_start"))
  {
    $parameters = framework_call ( "profiles_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The profile description is required.");
  }
  $parameters["Domain"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Domain"])));
  if ( empty ( $parameters["Domain"]))
  {
    $data["Domain"] = __ ( "The profile domain is required.");
  }
  if ( ! preg_match ( "/^[A-Z]{2}$/", $parameters["Country"]))
  {
    $data["Country"] = __ ( "The profile country is invalid.");
  }
  if ( ! array_key_exists ( "Country", $data) && empty ( $parameters["Country"]))
  {
    $data["Country"] = __ ( "The profile country is required.");
  }
  if ( empty ( $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The profile time zone is required.");
  }
  if ( ! array_key_exists ( "TimeZone", $data) && ! preg_match ( "/^(?:(?:[A-Za-z_\-]+\/[A-Za-z_\-]+(?:\/[A-Za-z_\-]+)?)|(?:Etc\/[A-Za-z0-9+\-]+(?:\/[A-Za-z0-9]+)?|(?:CET|CST6CDT|EET|EST|EST5EDT|MET|MST|MST7MDT|PST8PDT|HST)))$/", $parameters["TimeZone"]))
  {
    $data["TimeZone"] = __ ( "The profile time zone is invalid.");
  }
  if ( empty ( $parameters["Offset"]))
  {
    $data["Offset"] = __ ( "The profile time offset is required.");
  }
  if ( ! array_key_exists ( "Offset", $data) && ( $parameters["Offset"] < -13 || $parameters["Offset"] > 13))
  {
    $data["Offset"] = __ ( "The profile time offset is invalid.");
  }
  if ( empty ( $parameters["AreaCode"]))
  {
    $data["AreaCode"] = __ ( "The profile area code is required.");
  }
  if ( ! array_key_exists ( "AreaCode", $data) && ! preg_match ( "/^[0-9]+$/", $parameters["AreaCode"]))
  {
    $data["AreaCode"] = __ ( "The profile area code is invalid.");
  }
  if ( ! preg_match ( "/^[a-zA-Z]{2}(_[a-zA-Z]{2})?$/", $parameters["Language"]))
  {
    $data["Language"] = __ ( "The profile language is invalid.");
  }
  if ( $parameters["Prefix"] == "")
  {
    $data["Prefix"] = __ ( "The PSTN access prefix is required.");
  }
  if ( ! array_key_exists ( "Prefix", $data) && ! ( strlen ( $parameters["Prefix"]) == 1 && ord ( $parameters["Prefix"]) >= 48 && ord ( $parameters["Prefix"]) <= 57))
  {
    $data["Prefix"] = __ ( "The PSTN access prefix is invalid.");
  }
  if ( empty ( $parameters["NGGW"]))
  {
    $data["NGGW"] = __ ( "The profile non geographic gateway calls is required.");
  }
  if ( ! is_array ( $parameters["Blockeds"]))
  {
    if ( ! empty ( $parameters["Blockeds"]))
    {
      $parameters["Blockeds"] = array ( $parameters["Blockeds"]);
    } else {
      $parameters["Blockeds"] = array ();
    }
  }
  if ( ! is_array ( $parameters["Gateways"]))
  {
    if ( ! empty ( $parameters["Gateways"]))
    {
      $parameters["Gateways"] = array ( $parameters["Gateways"]);
    } else {
      $parameters["Gateways"] = array ();
    }
  }

  /**
   * If provided a music on hold, check if exist
   */
  if ( $parameters["MOH"])
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Audios` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["MOH"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["MOH"] = __ ( "The selected music on hold file is invalid.");
    }
  }

  /**
   * Check if profile exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Profiles`.*, `Countries`.`ISO3166-2` AS `CountryISO` FROM `Profiles` LEFT JOIN `Countries` ON `Profiles`.`Country` = `Countries`.`Code` WHERE `Profiles`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
   * Check if country exists
   */
  $check = filters_call ( "get_countries", array ( "ISO3166-2" => $parameters["Country"]));
  if ( sizeof ( $check) != 1)
  {
    $data["Country"] = __ ( "The selected country is invalid.");
  } else {
    $country = $check[0];
  }

  /**
   * If provided some gateway, check if exists
   */
  $gateways = array ();
  if ( array_key_exists ( "Gateways", $parameters) && sizeof ( $parameters["Gateways"]) != 0)
  {
    foreach ( $parameters["Gateways"] as $gateway)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $gateway)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 1)
      {
        $gateways[] = $gateway;
      }
    }
  }
  $parameters["Gateways"] = $gateways;

  /**
   * If provided some blocked gateway, check if exists
   */
  $blockeds = array ();
  if ( array_key_exists ( "Blockeds", $parameters) && sizeof ( $parameters["Blockeds"]) != 0)
  {
    foreach ( $parameters["Blockeds"] as $gateway)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $gateway)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 1)
      {
        $blockeds[] = $gateway;
      }
    }
  }
  $parameters["Blockeds"] = $blockeds;

  /**
   * If provided a non geographic gateway, check if exist
   */
  if ( ! array_key_exists ( "NGGW", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["NGGW"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["NGGW"] = __ ( "The selected non geographic calls gateway is invalid.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "profiles_edit_validate"))
  {
    $data = framework_call ( "profiles_edit_validate", $parameters, false, $data);
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
  $parameters["AreaCode"] = (int) $parameters["AreaCode"];
  $parameters["MOH"] = (int) $parameters["MOH"];
  $parameters["Emergency"] = (boolean) $parameters["Emergency"];
  $parameters["NGGW"] = (int) $parameters["NGGW"];
  foreach ( $parameters["Blockeds"] as $key => $value)
  {
    $parameters["Blockeds"][$key] = (int) $value;
  }
  foreach ( $parameters["Gateways"] as $key => $value)
  {
    $parameters["Gateways"][$key] = (int) $value;
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "profiles_edit_sanitize"))
  {
    $parameters = framework_call ( "profiles_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "profiles_edit_pre"))
  {
    $parameters = framework_call ( "profiles_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change profile record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Profiles` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Domain` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Domain"]) . "', `Country` = " . $_in["mysql"]["id"]->real_escape_string ( $country["Code"]) . ", `TimeZone` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["TimeZone"]) . "', `Offset` = " . $_in["mysql"]["id"]->real_escape_string ( (float) $parameters["Offset"]) . ", `AreaCode` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["AreaCode"]) . ", `Language` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Language"]) . "', `Prefix` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Prefix"]) . "', `MOH` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["MOH"]) . ", `EmergencyShortcut` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Emergency"] ? 1 : 0) . "', `NGGW` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["NGGW"]) . ", `DefaultGW` = '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $parameters["Gateways"])) . "', `BlockedGW` = '" . $_in["mysql"]["id"]->real_escape_string ( implode ( ",", $paramters["Blockeds"])) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "profiles_edit_post"))
  {
    framework_call ( "profiles_edit_post", $parameters);
  }

  /**
   * Notify profiles about change
   */
  $notify = array ( "ID" => $parameters["ID"], "Description" => $parameters["Description"], "Domain" => $parameters["Domain"], "Country" => $parameters["Country"], "TimeZone" => $parameters["TimeZone"], "Offset" => (float) $parameters["Offset"], "AreaCode" => $parameters["AreaCode"], "Language" => $parameters["Language"], "Prefix" => $parameters["Prefix"], "MOH" => $parameters["MOH"], "EmergencyShortcut" => $parameters["Emergency"], "NGGW" => $parameters["NGGW"], "Gateways" => $parameters["Gateways"], "Blockeds" => $parameters["Blockeds"]);
  if ( framework_has_hook ( "profiles_edit_notify"))
  {
    $notify = framework_call ( "profiles_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "profile_edit", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "profiles_edit_finish"))
  {
    framework_call ( "profiles_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a profile
 */
framework_add_hook (
  "profiles_remove",
  "profiles_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system profile was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid profile ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "profiles_remove", __ ( "Remove profiles"));
framework_add_api_call (
  "/profiles/:ID",
  "Delete",
  "profiles_remove",
  array (
    "permissions" => array ( "user", "profiles_remove"),
    "title" => __ ( "Remove profiles"),
    "description" => __ ( "Remove a profile from system.")
  )
);

/**
 * Function to remove an existing profile.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "profiles_remove_start"))
  {
    $parameters = framework_call ( "profiles_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid profile ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "profiles_remove_validate"))
  {
    $data = framework_call ( "profiles_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "profiles_remove_sanitize"))
  {
    $parameters = framework_call ( "profiles_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if profile exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Profiles` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
   * Check if profile has any use
   */
  if ( sizeof ( filters_call ( "profile_inuse", array ( "ID" => $parameters["ORIGINAL"]["ID"]))) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    $data = array ( "ID" => __ ( "Profile is already in use."));
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "profiles_remove_pre"))
  {
    $parameters = framework_call ( "profiles_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove profile database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Profiles` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "profiles_remove_post"))
  {
    framework_call ( "profiles_remove_post", $parameters);
  }

  /**
   * Notify profiles about change
   */
  $notify = array ( "ID" => $parameters["ORIGINAL"]["ID"]);
  if ( framework_has_hook ( "profiles_remove_notify"))
  {
    $notify = framework_call ( "profiles_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "profile_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "profiles_remove_finish"))
  {
    framework_call ( "profiles_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "profiles_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "profiles_server_reconfig");

/**
 * Function to notify server to include all profiles.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function profiles_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all profiles and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Profiles`.*, `Countries`.`ISO3166-2` AS `CountryISO` FROM `Profiles` LEFT JOIN `Countries` ON `Profiles`.`Country` = `Countries`.`Code`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $profile = $result->fetch_assoc ())
  {
    $notify = array ( "ID" => $profile["ID"], "Description" => $profile["Description"], "Domain" => $profile["Domain"], "Country" => $profile["CountryISO"], "TimeZone" => $profile["TimeZone"], "Offset" => (float) $profile["Offset"], "AreaCode" => $profile["AreaCode"], "Language" => $profile["Language"], "Prefix" => $profile["Prefix"], "MOH" => $profile["MOH"], "EmergencyShortcut" => (boolean) $profile["EmergencyShortcut"], "NGGW" => $profile["NGGW"], "Gateways" => array_filter ( explode ( ",", $profile["DefaultGW"]), "strlen"), "Blockeds" => array_filter ( explode ( ",", $profile["BlockedGW"]), "strlen"));
    if ( framework_has_hook ( "profiles_add_notify"))
    {
      $notify = framework_call ( "profiles_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "profile_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
