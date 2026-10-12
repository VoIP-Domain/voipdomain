<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain conferences module API. This module add the API calls related to
 * conferences.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Conferences
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search conferences
 */
framework_add_hook (
  "conferences_search",
  "conferences_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all conferences."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Name,MaxMembers",
          "example" => "Description,Name,MaxMembers"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system conferences."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "conference"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the conference."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the conference."),
                "example" => __ ( "Weekly Meeting")
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name keyword of the conference."),
                "example" => __ ( "weekly-meeting")
              ),
              "MaxMembers" => array (
                "type" => "integer",
                "description" => __ ( "The maximum number of members allowed in the conference."),
                "example" => 10
              )
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Fields" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Fields contain invalid values.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "conferences_search", __ ( "Search conferences"));
framework_add_api_call (
  "/conferences",
  "Read",
  "conferences_search",
  array (
    "permissions" => array ( "Administrator", "conferences_search"),
    "title" => __ ( "Search conferences"),
    "description" => __ ( "Search for system conferences.")
  )
);

/**
 * Function to search conferences.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add function parameters
   */
  $parameters["function"] = array (
    "DefaultFields" => "ID,Description,Name,MaxMembers",
    "PermittedFields" => "ID,Description,Name,MaxMembers"
  );

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "conferences_search_start"))
  {
    $parameters = framework_call ( "conferences_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Conferences");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Fields", $parameters) || $parameters["Fields"] == "" || ( is_array ( $parameters["Fields"]) && sizeof ( $parameters["Fields"]) == 0))
  {
    $parameters["Fields"] = $parameters["function"]["DefaultFields"];
  }
  if ( ! api_filter_validate ( $parameters["Fields"], $parameters["function"]["PermittedFields"]))
  {
    $data["Fields"] = __ ( "Fields contain invalid values.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "conferences_search_validate"))
  {
    $data = framework_call ( "conferences_search_validate", $parameters);
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
  if ( framework_has_hook ( "conferences_search_sanitize"))
  {
    $parameters = framework_call ( "conferences_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "conferences_search_pre"))
  {
    $parameters = framework_call ( "conferences_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search conferences
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . ( ! empty ( $parameters["Filter"]) ? " AND (`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%')" : "") . " ORDER BY `Name`, `Description`"))
  {
    database_query_error ( "conferences_search");
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], $parameters["function"]["DefaultFields"], $parameters["function"]["PermittedFields"]);
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "conferences_search_post"))
  {
    $data = framework_call ( "conferences_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "conferences_search_finish"))
  {
    framework_call ( "conferences_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get conference information
 */
framework_add_hook (
  "conferences_view",
  "conferences_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the conference."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the conference."),
              "example" => __ ( "Weekly Meeting")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name keyword of the conference."),
              "example" => __ ( "weekly-meeting")
            ),
            "PIN" => array (
              "type" => "string",
              "description" => __ ( "The PIN code to access the conference."),
              "example" => "1234"
            ),
            "AdminPIN" => array (
              "type" => "string",
              "description" => __ ( "The administrator PIN code to access the conference."),
              "example" => "5678"
            ),
            "MaxMembers" => array (
              "type" => "integer",
              "description" => __ ( "The maximum number of members allowed in the conference."),
              "example" => 10
            ),
            "MOHClass" => array (
              "type" => "string",
              "description" => __ ( "The music on hold class for the conference."),
              "example" => "default"
            ),
            "RecordConference" => array (
              "type" => "string",
              "enum" => array ( "Y", "N"),
              "description" => __ ( "Whether the conference should be recorded."),
              "example" => "N"
            ),
            "AnnounceJoinLeave" => array (
              "type" => "string",
              "enum" => array ( "Y", "N"),
              "description" => __ ( "Whether to announce when members join or leave the conference."),
              "example" => "Y"
            ),
            "WaitForAdmin" => array (
              "type" => "string",
              "enum" => array ( "Y", "N"),
              "description" => __ ( "Whether to wait for the administrator before starting the conference."),
              "example" => "N"
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid conference ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "conferences_view", __ ( "View conference information"));
framework_add_api_call (
  "/conferences/:ID",
  "Read",
  "conferences_view",
  array (
    "permissions" => array ( "Administrator", "conferences_view"),
    "title" => __ ( "View conferences"),
    "description" => __ ( "Get conference information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The conference internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate conference information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "conferences_view_start"))
  {
    $parameters = framework_call ( "conferences_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Conferences");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid conference ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "conferences_view_validate"))
  {
    $data = framework_call ( "conferences_view_validate", $parameters);
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
  if ( framework_has_hook ( "conferences_view_sanitize"))
  {
    $parameters = framework_call ( "conferences_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "conferences_view_pre"))
  {
    $parameters = framework_call ( "conferences_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search conferences
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "conferences_view");
  }
  if ( ! $conference = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Format data
   */
  $data = api_filter_entry ( array ( "Description", "Name", "PIN", "AdminPIN", "MaxMembers", "MOHClass", "RecordConference", "AnnounceJoinLeave", "WaitForAdmin"), $conference);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "conferences_view_post"))
  {
    $data = framework_call ( "conferences_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "conferences_view_finish"))
  {
    framework_call ( "conferences_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new conference
 */
framework_add_hook (
  "conferences_add",
  "conferences_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the conference."),
          "required" => true,
          "example" => __ ( "Weekly Meeting")
        ),
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name keyword of the conference."),
          "pattern" => "/^[a-z0-9\-\.]$/",
          "required" => true,
          "example" => __ ( "weekly-meeting")
        ),
        "PIN" => array (
          "type" => "string",
          "description" => __ ( "The PIN code to access the conference."),
          "example" => "1234"
        ),
        "AdminPIN" => array (
          "type" => "string",
          "description" => __ ( "The administrator PIN code to access the conference."),
          "example" => "5678"
        ),
        "MaxMembers" => array (
          "type" => "integer",
          "description" => __ ( "The maximum number of members allowed in the conference."),
          "example" => 10
        ),
        "MOHClass" => array (
          "type" => "string",
          "description" => __ ( "The music on hold class for the conference."),
          "example" => "default"
        ),
        "RecordConference" => array (
          "type" => "string",
          "enum" => array ( "Y", "N"),
          "description" => __ ( "Whether the conference should be recorded."),
          "required" => true,
          "example" => "N"
        ),
        "AnnounceJoinLeave" => array (
          "type" => "string",
          "enum" => array ( "Y", "N"),
          "description" => __ ( "Whether to announce when members join or leave the conference."),
          "required" => true,
          "example" => "Y"
        ),
        "WaitForAdmin" => array (
          "type" => "string",
          "enum" => array ( "Y", "N"),
          "description" => __ ( "Whether to wait for the administrator before starting the conference."),
          "required" => true,
          "example" => "N"
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "A new conference was added successfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The description is required.")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The name can contain only lowercase letters, numbers, hyphen and dot.")
            ),
            "RecordConference" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The record conference option is invalid.")
            ),
            "AnnounceJoinLeave" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The announce join/leave option is invalid.")
            ),
            "WaitForAdmin" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The wait for admin option is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "conferences_add", __ ( "Add conferences"));
framework_add_api_call (
  "/conferences",
  "Create",
  "conferences_add",
  array (
    "permissions" => array ( "Administrator", "conferences_add"),
    "title" => __ ( "Add conferences"),
    "description" => __ ( "Add a new conference.")
  )
);

/**
 * Function to add a new conference.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "conferences_add_start"))
  {
    $parameters = framework_call ( "conferences_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The description is required.");
  }
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name is required.");
  }
  if ( ! array_key_exists ( "Name", $data) && $parameters["Name"] != preg_replace ( "/[^a-z0-9\-\.]/", "", $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name can contain only lowercase letters, numbers, hyphen and dot.");
  }
  if ( ! empty ( $parameters["MaxMembers"]) && ! is_numeric ( $parameters["MaxMembers"]))
  {
    $data["MaxMembers"] = __ ( "The max members must be a number.");
  }
  if ( ! empty ( $parameters["RecordConference"]) && ! in_array ( $parameters["RecordConference"], array ( "Y", "N")))
  {
    $data["RecordConference"] = __ ( "The record conference option is invalid.");
  }
  if ( ! empty ( $parameters["AnnounceJoinLeave"]) && ! in_array ( $parameters["AnnounceJoinLeave"], array ( "Y", "N")))
  {
    $data["AnnounceJoinLeave"] = __ ( "The announce join/leave option is invalid.");
  }
  if ( ! empty ( $parameters["WaitForAdmin"]) && ! in_array ( $parameters["WaitForAdmin"], array ( "Y", "N")))
  {
    $data["WaitForAdmin"] = __ ( "The wait for admin option is invalid.");
  }

  /**
   * Set default values for optional fields
   */
  if ( empty ( $parameters["MaxMembers"]))
  {
    $parameters["MaxMembers"] = 0;
  }
  if ( empty ( $parameters["RecordConference"]))
  {
    $parameters["RecordConference"] = "N";
  }
  if ( empty ( $parameters["AnnounceJoinLeave"]))
  {
    $parameters["AnnounceJoinLeave"] = "Y";
  }
  if ( empty ( $parameters["WaitForAdmin"]))
  {
    $parameters["WaitForAdmin"] = "N";
  }

  /**
   * Check if conference was already added
   */
  if ( ! array_key_exists ( "Name", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "'"))
    {
      database_query_error ( "conferences_add");
    }
    if ( $result->num_rows != 0)
    {
      $data["Name"] = __ ( "The provided name was already in use.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "conferences_add_validate"))
  {
    $data = framework_call ( "conferences_add_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "conferences_add_sanitize"))
  {
    $parameters = framework_call ( "conferences_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "conferences_add_pre"))
  {
    $parameters = framework_call ( "conferences_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new conference record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Conferences` (`Tenant`, `Description`, `Name`, `PIN`, `AdminPIN`, `MaxMembers`, `MOHClass`, `RecordConference`, `AnnounceJoinLeave`, `WaitForAdmin`) VALUES (" . (int) $_in["session"]["Tenant"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["PIN"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["AdminPIN"]) . "', " . (int) $parameters["MaxMembers"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["MOHClass"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["RecordConference"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["AnnounceJoinLeave"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["WaitForAdmin"]) . "')"))
  {
    database_query_error ( "conferences_add");
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "conferences_add_post"))
  {
    framework_call ( "conferences_add_post", $parameters);
  }

  /**
   * Add new conference at Asterisk server
   */
  $notify = array ( "Description" => $parameters["Description"], "Name" => $parameters["Name"], "PIN" => $parameters["PIN"], "AdminPIN" => $parameters["AdminPIN"], "MaxMembers" => (int) $parameters["MaxMembers"], "MOHClass" => $parameters["MOHClass"], "RecordConference" => $parameters["RecordConference"], "AnnounceJoinLeave" => $parameters["AnnounceJoinLeave"], "WaitForAdmin" => $parameters["WaitForAdmin"]);
  if ( framework_has_hook ( "conferences_add_notify"))
  {
    $notify = framework_call ( "conferences_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "conference_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "conferences_add_finish"))
  {
    framework_call ( "conferences_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . voipdomain_location_url ( "/api/conferences/" . $parameters["ID"]));
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing conference
 */
framework_add_hook (
  "conferences_edit",
  "conferences_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the conference."),
          "required" => true,
          "example" => __ ( "Weekly Meeting")
        ),
        "Name" => array (
          "type" => "string",
          "description" => __ ( "The name keyword of the conference."),
          "pattern" => "/^[a-z0-9\-\.]$/",
          "required" => true,
          "example" => __ ( "weekly-meeting")
        ),
        "PIN" => array (
          "type" => "string",
          "description" => __ ( "The PIN code to access the conference."),
          "example" => "1234"
        ),
        "AdminPIN" => array (
          "type" => "string",
          "description" => __ ( "The administrator PIN code to access the conference."),
          "example" => "5678"
        ),
        "MaxMembers" => array (
          "type" => "integer",
          "description" => __ ( "The maximum number of members allowed in the conference."),
          "example" => 10
        ),
        "MOHClass" => array (
          "type" => "string",
          "description" => __ ( "The music on hold class for the conference."),
          "example" => "default"
        ),
        "RecordConference" => array (
          "type" => "string",
          "enum" => array ( "Y", "N"),
          "description" => __ ( "Whether the conference should be recorded."),
          "required" => true,
          "example" => "N"
        ),
        "AnnounceJoinLeave" => array (
          "type" => "string",
          "enum" => array ( "Y", "N"),
          "description" => __ ( "Whether to announce when members join or leave the conference."),
          "required" => true,
          "example" => "Y"
        ),
        "WaitForAdmin" => array (
          "type" => "string",
          "enum" => array ( "Y", "N"),
          "description" => __ ( "Whether to wait for the administrator before starting the conference."),
          "required" => true,
          "example" => "N"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The conference was successfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The conference description is required.")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The name can contain only lowercase letters, numbers, hyphen and dot.")
            ),
            "RecordConference" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The record conference option is invalid.")
            ),
            "AnnounceJoinLeave" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The announce join/leave option is invalid.")
            ),
            "WaitForAdmin" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The wait for admin option is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "conferences_edit", __ ( "Edit conferences"));
framework_add_api_call (
  "/conferences/:ID",
  array ( "Modify", "Edit"),
  "conferences_edit",
  array (
    "permissions" => array ( "Administrator", "conferences_edit"),
    "title" => __ ( "Edit conferences"),
    "description" => __ ( "Change conference information.")
  )
);

/**
 * Function to edit an existing conference.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "conferences_edit_start"))
  {
    $parameters = framework_call ( "conferences_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The conference description is required.");
  }
  if ( empty ( $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name is required.");
  }
  if ( ! array_key_exists ( "Name", $data) && $parameters["Name"] != preg_replace ( "/[^a-z0-9\-\.]/", "", $parameters["Name"]))
  {
    $data["Name"] = __ ( "The name can contain only lowercase letters, numbers, hyphen and dot.");
  }
  if ( ! empty ( $parameters["MaxMembers"]) && ! is_numeric ( $parameters["MaxMembers"]))
  {
    $data["MaxMembers"] = __ ( "The max members must be a number.");
  }
  if ( ! empty ( $parameters["RecordConference"]) && ! in_array ( $parameters["RecordConference"], array ( "Y", "N")))
  {
    $data["RecordConference"] = __ ( "The record conference option is invalid.");
  }
  if ( ! empty ( $parameters["AnnounceJoinLeave"]) && ! in_array ( $parameters["AnnounceJoinLeave"], array ( "Y", "N")))
  {
    $data["AnnounceJoinLeave"] = __ ( "The announce join/leave option is invalid.");
  }
  if ( ! empty ( $parameters["WaitForAdmin"]) && ! in_array ( $parameters["WaitForAdmin"], array ( "Y", "N")))
  {
    $data["WaitForAdmin"] = __ ( "The wait for admin option is invalid.");
  }

  /**
   * Set default values for optional fields
   */
  if ( empty ( $parameters["MaxMembers"]))
  {
    $parameters["MaxMembers"] = 0;
  }
  if ( empty ( $parameters["RecordConference"]))
  {
    $parameters["RecordConference"] = "N";
  }
  if ( empty ( $parameters["AnnounceJoinLeave"]))
  {
    $parameters["AnnounceJoinLeave"] = "Y";
  }
  if ( empty ( $parameters["WaitForAdmin"]))
  {
    $parameters["WaitForAdmin"] = "N";
  }

  /**
   * Check if conference was already added
   */
  if ( ! array_key_exists ( "Name", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "' AND `ID` != " . (int) $parameters["ID"]))
    {
      database_query_error ( "conferences_edit");
    }
    if ( $result->num_rows != 0)
    {
      $data["Name"] = __ ( "The provided name was already in use.");
    }
  }

  /**
   * Check if conference exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "conferences_edit");
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "conferences_edit_validate"))
  {
    $data = framework_call ( "conferences_edit_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "conferences_edit_sanitize"))
  {
    $parameters = framework_call ( "conferences_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "conferences_edit_pre"))
  {
    $parameters = framework_call ( "conferences_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change conference record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Conferences` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "', `PIN` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["PIN"]) . "', `AdminPIN` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["AdminPIN"]) . "', `MaxMembers` = " . (int) $parameters["MaxMembers"] . ", `MOHClass` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["MOHClass"]) . "', `RecordConference` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["RecordConference"]) . "', `AnnounceJoinLeave` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["AnnounceJoinLeave"]) . "', `WaitForAdmin` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["WaitForAdmin"]) . "' WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "conferences_edit");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "conferences_edit_post"))
  {
    framework_call ( "conferences_edit_post", $parameters);
  }

  /**
   * Notify server about change
   */
  $notify = array ( "OldName" => $parameters["ORIGINAL"]["Name"], "Name" => $parameters["Name"], "Description" => $parameters["Description"], "PIN" => $parameters["PIN"], "AdminPIN" => $parameters["AdminPIN"], "MaxMembers" => (int) $parameters["MaxMembers"], "MOHClass" => $parameters["MOHClass"], "RecordConference" => $parameters["RecordConference"], "AnnounceJoinLeave" => $parameters["AnnounceJoinLeave"], "WaitForAdmin" => $parameters["WaitForAdmin"]);
  if ( framework_has_hook ( "conferences_edit_notify"))
  {
    $notify = framework_call ( "conferences_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "conference_change", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "conferences_edit_finish"))
  {
    framework_call ( "conferences_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a conference
 */
framework_add_hook (
  "conferences_remove",
  "conferences_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The conference was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned for each inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid conference ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "conferences_remove", __ ( "Remove conferences"));
framework_add_api_call (
  "/conferences/:ID",
  "Delete",
  "conferences_remove",
  array (
    "permissions" => array ( "Administrator", "conferences_remove"),
    "title" => __ ( "Remove conferences"),
    "description" => __ ( "Remove a conference from the system.")
  )
);

/**
 * Function to remove an existing conference.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "conferences_remove_start"))
  {
    $parameters = framework_call ( "conferences_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid conference ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "conferences_remove_validate"))
  {
    $data = framework_call ( "conferences_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "conferences_remove_sanitize"))
  {
    $parameters = framework_call ( "conferences_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if conference exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "conferences_remove");
  }
  if ( ! $parameters["ORIGINAL"] = $result->fetch_assoc ())
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Check if conference was in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionConference` WHERE `Conference` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "conferences_remove");
  }
  if ( $result->num_rows != 0)
  {
    database_query_error ( "conferences_remove");
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "conferences_remove_pre"))
  {
    $parameters = framework_call ( "conferences_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove conference database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "conferences_remove");
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "conferences_remove_post"))
  {
    framework_call ( "conferences_remove_post", $parameters);
  }

  /**
   * Notify server about change
   */
  $notify = array ( "Name" => $parameters["ORIGINAL"]["Name"]);
  if ( framework_has_hook ( "conferences_remove_notify"))
  {
    $notify = framework_call ( "conferences_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "conference_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "conferences_remove_finish"))
  {
    framework_call ( "conferences_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "conferences_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "conferences_server_reconfig");

/**
 * Function to notify server to include all conferences.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function conferences_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all conferences and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"]))
  {
    database_query_error ( "conferences_server_reconfig");
  }
  while ( $conference = $result->fetch_assoc ())
  {
    $notify = array ( "Description" => $conference["Description"], "Name" => $conference["Name"], "PIN" => $conference["PIN"], "AdminPIN" => $conference["AdminPIN"], "MaxMembers" => (int) $conference["MaxMembers"], "MOHClass" => $conference["MOHClass"], "RecordConference" => $conference["RecordConference"], "AnnounceJoinLeave" => $conference["AnnounceJoinLeave"], "WaitForAdmin" => $conference["WaitForAdmin"]);
    if ( framework_has_hook ( "conferences_add_notify"))
    {
      $notify = framework_call ( "conferences_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "conference_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
