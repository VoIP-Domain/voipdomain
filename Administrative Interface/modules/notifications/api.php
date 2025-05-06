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
 * VoIP Domain notifications module API. This module add the API calls related
 * to notifications.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Notifications
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search notification events
 */
framework_add_hook (
  "notifications_events_search",
  "notifications_events_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all notification events."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Event,Description",
          "example" => "Event,Description"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system notification event."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "notification"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the notification event."),
                "example" => 1
              ),
              "Event" => array (
                "type" => "string",
                "description" => __ ( "The event name of the notification event."),
                "example" => __ ( "New Call")
              ),
              "EventEN" => array (
                "type" => "string",
                "description" => __ ( "The original English event name of the notification event."),
                "example" => "New Call"
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the notification event."),
                "example" => __ ( "Event fired when a new call happen at the system.")
              ),
              "DescriptionEN" => array (
                "type" => "string",
                "description" => __ ( "The original English description of the notification event."),
                "example" => "Event fired when a new call happen at the system."
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
framework_add_api_call (
  "/notifications/events",
  "Read",
  "notifications_events_search",
  array (
    "permissions" => array ( "user", "notifications_events_search"),
    "title" => __ ( "Search notification events"),
    "description" => __ ( "Search for system notification events.")
  )
);

/**
 * Function to search notification events.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_events_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "notifications_events_search_start"))
  {
    $parameters = framework_call ( "notifications_events_search_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "notifications_events_search_validate"))
  {
    $data = framework_call ( "notifications_events_search_validate", $parameters);
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
  if ( framework_has_hook ( "notifications_events_search_sanitize"))
  {
    $parameters = framework_call ( "notifications_events_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "notifications_events_search_pre"))
  {
    $parameters = framework_call ( "notifications_events_search_pre", $parameters, false, $parameters);
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["fields"], "ID,Event,Description", "ID,Event,EventEN,Description,DescriptionEN");
  foreach ( $_in["events"] as $event => $entry)
  {
    $result = array ();
    $result["ID"] = $event;
    $result["Event"] = __ ( $entry["Event"]);
    $result["EventEN"] = $entry["Event"];
    $result["Description"] = __ ( $entry["Description"]);
    $result["DescriptionEN"] = $entry["Description"];
    if ( ! empty ( $parameters["Filter"]) && ( strpos ( $result["Event"], $parameters["Filter"]) !== false || strpos ( $result["Description"], $parameters["Filter"]) !== false))
    {
      continue;
    }
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "notifications_events_search_post"))
  {
    $data = framework_call ( "notifications_events_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "notifications_events_search_finish"))
  {
    framework_call ( "notifications_events_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get notification structure
 */
framework_add_hook (
  "notifications_events_view",
  "notifications_events_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system notification event."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The unique identifier of the notification event."),
              "example" => "NewCall"
            ),
            "Event" => array (
              "type" => "string",
              "description" => __ ( "The translated name of the notification event."),
              "example" => __ ( "New call")
            ),
            "EventEN" => array (
              "type" => "string",
              "description" => __ ( "The original English name of the notification event."),
              "example" => "New call"
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The translated description of the notification event."),
              "example" => __ ( "Event fired when a new call happen at the system.")
            ),
            "DescriptionEN" => array (
              "type" => "string",
              "description" => __ ( "The original English description of the notification event."),
              "example" => "Event fired when a new call happen at the system."
            ),
            "Fields" => array (
              "type" => "array",
              "description" => __ ( "An array of objects describing notification event fields."),
              "items" => array (
                "type" => "object",
                "properties" => array (
                  "Variable" => array (
                    "type" => "string",
                    "description" => __ ( "Name of the variable."),
                    "example" => "type"
                  ),
                  "Name" => array (
                    "type" => "string",
                    "description" => __ ( "The translated name of the variable."),
                    "example" => __ ( "Call Type")
                  ),
                  "NameEN" => array (
                    "type" => "string",
                    "description" => __ ( "The original english name of the variable."),
                    "example" => "Call Type"
                  ),
                  "Type" => array (
                    "type" => "string",
                    "enum" => array ( "string", "integer", "double", "date", "time", "datetime", "boolean", "enum"),
                    "description" => __ ( "Name of the variable."),
                    "example" => "string"
                  ),
                  "APIPath" => array (
                    "type" => "string",
                    "description" => __ ( "The API path to access variable information.")
                  ),
                  "TypeEnum" => array (
                    "type" => "array",
                    "description" => __ ( "An array with variable as index and variable description as value. This element is available only if `Type` is *enum*."),
                    "items" => array (
                      "type" => "string"
                    ),
                    "required" => false
                  )
                )
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
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid notification event ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "notifications_events_view", __ ( "View notification events structure information"));
framework_add_api_call (
  "/notifications/events/:ID",
  "Read",
  "notifications_events_view",
  array (
    "permissions" => array ( "user", "notifications_events_view"),
    "title" => __ ( "View notifications"),
    "description" => __ ( "Get a system notification information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "string",
        "description" => __ ( "The notification internal system unique identifier."),
        "example" => "newcall"
      )
    )
  )
);

/**
 * Function to generate notification event structure information.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_events_view ( $buffer, $parameters)
{
  global $_in, $_api;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "notifications_events_view_start"))
  {
    $parameters = framework_call ( "notifications_events_view_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "notifications_events_view_validate"))
  {
    $data = framework_call ( "notifications_events_view_validate", $parameters);
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
  if ( framework_has_hook ( "notifications_events_view_sanitize"))
  {
    $parameters = framework_call ( "notifications_events_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "notifications_events_view_pre"))
  {
    $parameters = framework_call ( "notifications_events_view_pre", $parameters, false, $parameters);
  }

  /**
   * Check if event exist
   */
  if ( ! array_key_exists ( $parameters["ID"], $_in["events"]))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Prepare event structure
   */
  $data = array ();
  $data["Name"] = $parameters["ID"];
  $data["Event"] = __ ( $_in["events"][$parameters["ID"]]["Event"]);
  $data["EventEN"] = $_in["events"][$parameters["ID"]]["Event"];
  $data["Description"] = __ ( $_in["events"][$parameters["ID"]]["Description"]);
  $data["DescriptionEN"] = $_in["events"][$parameters["ID"]]["Description"];
  $data["Fields"] = array ();
  foreach ( $_in["events"][$parameters["ID"]]["Fields"] as $field)
  {
    if ( $field["Type"] == "enum")
    {
      $enum = array ();
      foreach ( $field["TypeEnum"] as $var => $val)
      {
        $enum[$var] = __ ( $val);
      }
      $data["Fields"][] = array ( "Variable" => $field["Variable"], "Name" => __ ( $field["Name"]), "NameEN" => $field["Name"], "Type" => $field["Type"], "APIPath" => $field["APIPath"], "TypeEnum" => $enum);
    } else {
      $data["Fields"][] = array ( "Variable" => $field["Variable"], "Name" => __ ( $field["Name"]), "NameEN" => $field["Name"], "Type" => $field["Type"], "APIPath" => $field["APIPath"]);
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "notifications_events_view_post"))
  {
    $data = framework_call ( "notifications_events_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "notifications_events_view_finish"))
  {
    framework_call ( "notifications_events_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to search notifications
 */
framework_add_hook (
  "notifications_search",
  "notifications_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all notifications."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Event,Expire,ExpireTimestamp",
          "example" => "Description,Event"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system notifications."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "event"
            ),
            "properties" => array (
              "Event" => array (
                "type" => "string",
                "description" => __ ( "The translated event of system notification."),
                "example" => __ ( "New Call")
              ),
              "EventEN" => array (
                "type" => "string",
                "description" => __ ( "The original English event of system notification."),
                "example" => "New Call"
              ),
              "ExpireTimestamp" => array (
                "type" => "integer",
                "description" => __ ( "The UNIX timestamp of the expiration of system notification. If doesn't have expiration, will be *0*."),
                "example" => 1589227252
              ),
              "Expire" => array (
                "type" => "date-time",
                "description" => __ ( "The original English event of system notification. If doesn't have expiration, will be empty."),
                "example" => __ ( "05/11/2020 17:00:00")
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
framework_add_permission ( "notifications_search", __ ( "Search notifications"));
framework_add_api_call (
  "/notifications",
  "Read",
  "notifications_search",
  array (
    "permissions" => array ( "user", "notifications_search"),
    "title" => __ ( "Search notifications"),
    "description" => __ ( "Search for system notifications.")
  )
);

/**
 * Function to search notifications.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "notifications_search_start"))
  {
    $parameters = framework_call ( "notifications_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Notifications");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "notifications_search_validate"))
  {
    $data = framework_call ( "notifications_search_validate", $parameters);
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
  if ( framework_has_hook ( "notifications_search_sanitize"))
  {
    $parameters = framework_call ( "notifications_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "notifications_search_pre"))
  {
    $parameters = framework_call ( "notifications_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search notifications
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Description`, `Event`, `Expire` FROM `Notifications`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Description`, `Event`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Event,Expire,ExpireTimestamp", "ID,Description,Event,EventEN,Expire,ExpireTimestamp");
  while ( $result = $results->fetch_assoc ())
  {
    $result["EventEN"] = $_in["events"][$result["Event"]]["Event"];
    $result["Event"] = __ ( $_in["events"][$result["Event"]]["Event"]);
    $result["ExpireTimestamp"] = format_db_timestamp ( $result["Expire"]);
    $result["Expire"] = $result["Expire"] == "0000-00-00 00:00:00" ? "" : substr ( $result["Expire"], 0, 10);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "notifications_search_post"))
  {
    $data = framework_call ( "notifications_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "notifications_search_finish"))
  {
    framework_call ( "notifications_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get notification information
 */
framework_add_hook (
  "notifications_view",
  "notifications_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system notification."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the notification."),
              "example" => __ ( "My event notification")
            ),
            "Event" => array (
              "type" => "string",
              "description" => __ ( "The event this notification watch for."),
              "example" => "NewCall"
            ),
            "EventName" => array (
              "type" => "string",
              "description" => __ ( "The name and description in format `name (description)` of the event this notification watch for."),
              "example" => __ ( "New Call (Event fired when a new call happen at the system.)")
            ),
            "Fields" => array (
              "type" => "array",
              "description" => __ ( "An array of objects describing notification event fields."),
              "items" => array (
                "type" => "object",
                "properties" => array (
                  "Variable" => array (
                    "type" => "string",
                    "description" => __ ( "Name of the variable."),
                    "example" => "type"
                  ),
                  "Name" => array (
                    "type" => "string",
                    "description" => __ ( "The translated name of the variable."),
                    "example" => __ ( "Call Type")
                  ),
                  "NameEN" => array (
                    "type" => "string",
                    "description" => __ ( "The original english name of the variable."),
                    "example" => "Call Type"
                  ),
                  "Type" => array (
                    "type" => "string",
                    "enum" => array ( "string", "integer", "double", "date", "time", "datetime", "boolean", "enum"),
                    "description" => __ ( "Name of the variable."),
                    "example" => "string"
                  ),
                  "APIPath" => array (
                    "type" => "string",
                    "description" => __ ( "The API path to access variable information.")
                  ),
                  "TypeEnum" => array (
                    "type" => "array",
                    "description" => __ ( "An array with variable as index and variable description as value. This element is available only if `Type` is *enum*."),
                    "items" => array (
                      "type" => "string"
                    ),
                    "required" => false
                  )
                )
              )
            ),
            "Filters" => array (
              "type" => "object",
              "description" => __ ( "An object with the structure of event variables filter."),
              "properties" => array ()
            ),
            "Method" => array (
              "type" => "string",
              "enum" => array ( "GET", "POST", "PUT", "DELETE", "HEAD", "OPTIONS", "TRACE", "CONNECT"),
              "description" => __ ( "The HTTP method the system must use to notify the event."),
              "example" => "POST"
            ),
            "URL" => array (
              "type" => "string",
              "description" => __ ( "The URL to be notified of event."),
              "example" => "https://example.com/crm/newcall"
            ),
            "Type" => array (
              "type" => "string",
              "enum" => array ( "JSON", "FORM-DATA", "PHP"),
              "description" => __ ( "The format the data must be sent to notification endpoint."),
              "example" => "FORM-DATA"
            ),
            "Variables" => array (
              "type" => "array",
              "description" => __ ( "An indexed array with the variables mapping for the event. The index will be the variable name and the value the variable used to notify the endpoint."),
              "items" => array (
                "type" => "string"
              )
            ),
            "Headers" => array (
              "type" => "array",
              "description" => __ ( "An indexed array with the extra HTTP headers that will be used to notify the endpoint. The index will be the header variable and the value will be the header value."),
              "items" => array (
                "type" => "string"
              )
            ),
            "SSL" => array (
              "type" => "boolean",
              "description" => __ ( "If the system must relax SSL validation on HTTPS connection."),
              "example" => true
            ),
            "Validity" => array (
              "type" => "date-time",
              "description" => __ ( "The expiration date of notification in YYYY-MM-DD format. If doesn't have expiration, will be *empty*."),
              "example" => "2020-05-01"
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
              "example" => __ ( "Invalid notification ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "notifications_view", __ ( "View notification information"));
framework_add_api_call (
  "/notifications/:ID",
  "Read",
  "notifications_view",
  array (
    "permissions" => array ( "user", "notifications_view"),
    "title" => __ ( "View notifications"),
    "description" => __ ( "Get a system notification information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The notification internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate notification information.
 *
 * @global array $_in Framework global configuration variable
 * @global array $_api Framework global API configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_view ( $buffer, $parameters)
{
  global $_in, $_api;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "notifications_view_start"))
  {
    $parameters = framework_call ( "notifications_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Notifications");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid notification ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "notifications_view_validate"))
  {
    $data = framework_call ( "notifications_view_validate", $parameters);
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
  if ( framework_has_hook ( "notifications_view_sanitize"))
  {
    $parameters = framework_call ( "notifications_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "notifications_view_pre"))
  {
    $parameters = framework_call ( "notifications_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search notifications
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $notification = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["Description"] = $notification["Description"];
  $data["Event"] = $notification["Event"];
  $data["EventName"] = __ ( $_in["events"][$notification["Event"]]["Event"]) . " (" . __ ( $_in["events"][$notification["Event"]]["Description"]) . ")";
  $data["Fields"] = array ();
  foreach ( $_in["events"][$notification["Event"]]["Fields"] as $field)
  {
    if ( $field["Type"] == "enum")
    {
      $enum = array ();
      foreach ( $field["TypeEnum"] as $var => $val)
      {
        $enum[$var] = __ ( $val);
      }
      $data["Fields"][] = array ( "Variable" => $field["Variable"], "Name" => __ ( $field["Name"]), "NameEN" => $field["Name"], "Type" => $field["Type"], "APIPath" => $field["APIPath"], "TypeEnum" => $enum);
    } else {
      $data["Fields"][] = array ( "Variable" => $field["Variable"], "Name" => __ ( $field["Name"]), "NameEN" => $field["Name"], "Type" => $field["Type"], "APIPath" => $field["APIPath"]);
    }
  }
  $data["Filters"] = json_decode ( $notification["Filters"], true);
  $data["Method"] = $notification["Method"];
  $data["URL"] = $notification["URL"];
  $data["Type"] = $notification["Type"];
  $data["Variables"] = json_decode ( $notification["Variables"], true);
  $data["Headers"] = json_decode ( $notification["Headers"], true);
  $data["SSL"] = $notification["RelaxSSL"] == "Y";
  $data["Validity"] = $notification["Expire"] == "0000-00-00 00:00:00" ? "" : substr ( $notification["Expire"], 0, 10);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "notifications_view_post"))
  {
    $data = framework_call ( "notifications_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "notifications_view_finish"))
  {
    framework_call ( "notifications_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new notification
 */
framework_add_hook (
  "notifications_add",
  "notifications_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the system notification."),
          "required" => true,
          "example" => __ ( "My event notification")
        ),
        "Event" => array (
          "type" => "string",
          "description" => __ ( "The event this notification watch for."),
          "required" => true,
          "example" => "NewCall"
        ),
        "Filters" => array (
          "type" => "object",
          "description" => __ ( "An object with the structure of event variables filter."),
          "properties" => array (),
          "required" => false
        ),
        "Method" => array (
          "type" => "string",
          "enum" => array ( "GET", "POST", "PUT", "DELETE", "HEAD", "OPTIONS", "TRACE", "CONNECT"),
          "description" => __ ( "The HTTP method the system must use to notify the event."),
          "required" => true,
          "example" => "POST"
        ),
        "URL" => array (
          "type" => "string",
          "description" => __ ( "The URL to be notified of event."),
          "required" => true,
          "example" => "https://example.com/crm/newcall"
        ),
        "Type" => array (
          "type" => "string",
          "enum" => array ( "JSON", "FORM-DATA", "PHP"),
          "description" => __ ( "The format the data must be sent to notification endpoint."),
          "required" => true,
          "example" => "FORM-DATA"
        ),
        "Variables" => array (
          "type" => "array",
          "description" => __ ( "An array containing all notification variables mapping, with array index as variable name and value as name to be used."),
          "items" => array (
            "type" => "string"
          )
        ),
        "Headers" => array (
          "type" => "array",
          "description" => __ ( "An array containing request special headers to be used."),
          "items" => array (
            "type" => "object",
            "properties" => array (
              "Reference" => array (
                "type" => "integer",
                "description" => __ ( "The reference number to header. This is used to report any header name/value error."),
                "required" => true
              ),
              "Header" => array (
                "type" => "string",
                "description" => __ ( "The header name. This should not include reserved values and will be appended to 'X-'."),
                "example" => "MyAPI-Key",
                "required" => true
              ),
              "Value" => array (
                "type" => "string",
                "description" => __ ( "The value to be sent with the special header."),
                "example" => "123xxx456",
                "required" => true
              )
            )
          )
        ),
        "SSL" => array (
          "type" => "boolean",
          "description" => __ ( "If the system must relax SSL certificate validation if using HTTPS."),
          "required" => false,
          "example" => true
        ),
        "Validity" => array (
          "type" => "date-time",
          "description" => __ ( "The date of validity to this notification in YYYY-MM-DD format."),
          "required" => false,
          "example" => "2020-05-01"
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system notification added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The description is required.")
            ),
            "Event" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided event and URL already exist.")
            ),
            "Method" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided method is invalid.")
            ),
            "URL" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The URL is required.")
            ),
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided data type is invalid.")
            ),
            "Header_X" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided extra HTTP header name are invalid.")
            ),
            "Value_X" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided extra HTTP header value cannot be empty.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "notifications_add", __ ( "Add notifications"));
framework_add_api_call (
  "/notifications",
  "Create",
  "notifications_add",
  array (
    "permissions" => array ( "user", "notifications_add"),
    "title" => __ ( "Add notifications"),
    "description" => __ ( "Add a new system notification.")
  )
);

/**
 * Function to add a new notification.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "notifications_add_start"))
  {
    $parameters = framework_call ( "notifications_add_start", $parameters);
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
  if ( empty ( $parameters["Event"]))
  {
    $data["Event"] = __ ( "The event is required.");
  }
  if ( ! array_key_exists ( "Event", $data) && ! array_key_exists ( $parameters["Event"], $_in["events"]))
  {
    $data["Event"] = __ ( "The provided event is invalid.");
  }
  if ( empty ( $parameters["Method"]))
  {
    $data["Method"] = __ ( "The method is required.");
  }
  if ( ! array_key_exists ( "Method", $data) && ! in_array ( $parameters["Method"], $_in["methods"]))
  {
    $data["Method"] = __ ( "The provided method is invalid.");
  }
  if ( empty ( $parameters["URL"]))
  {
    $data["URL"] = __ ( "The URL is required.");
  }
  if ( empty ( $parameters["Type"]))
  {
    $data["Type"] = __ ( "The data type is required.");
  }
  if ( ! array_key_exists ( "Type", $data) && ! in_array ( $parameters["Type"], $_in["datatypes"]))
  {
    $data["Type"] = __ ( "The provided data type is invalid.");
  }

  /**
   * Check extra headers
   */
  $headers = array ();
  foreach ( $parameters["Headers"] as $value)
  {
    if ( ! preg_match ( "/^[0-9A-Za-z!#%&'_`\-\$\*\+\.\^\|]+$/", $value["Header"]))
    {
      $data["Header_" . $value["Reference"]] = __ ( "The provided extra HTTP header name are invalid.");
      continue;
    }
    if ( empty ( $value["Value"]))
    {
      $data["Header_" . $value["Reference"]] = __ ( "The provided extra HTTP header value cannot be empty.");
      continue;
    }
    $headers[$value["Header"]] = $value["Value"];
  }
  $parameters["Headers"] = $headers;

  /**
   * Check if notification was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `Event` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Event"]) . "' AND `URL` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["URL"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Event"] = __ ( "The provided event and URL already exist.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "notifications_add_validate"))
  {
    $data = framework_call ( "notifications_add_validate", $parameters, false, $data);
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
  $parameters["Validity"] = ( ! empty ( $parameters["Validity"]) ? format_form_date ( $parameters["Validity"]) . " 00:00:00" : "0000-00-00 00:00:00");
  if ( ! is_array ( $parameters["Filters"]))
  {
    $parameters["Filters"] = array ();
  }
  $parameters["Filters"] = fix_rules ( $parameters["Filters"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "notifications_add_sanitize"))
  {
    $parameters = framework_call ( "notifications_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "notifications_add_pre"))
  {
    $parameters = framework_call ( "notifications_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new notification record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Notifications` (`Description`, `Event`, `Filters`, `URL`, `Method`, `Type`, `Variables`, `Headers`, `RelaxSSL`, `Expire`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Event"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Filters"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["URL"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Method"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Type"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Variables"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Headers"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["SSL"] ? "Y" : "N") . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Validity"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "notifications_add_post"))
  {
    framework_call ( "notifications_add_post", $parameters);
  }

  /**
   * Notify worker's about new notification
   */
  $gm = new GearmanClient ();
  $gm->addServer ( ( $_in["gearman"]["hostname"] ? $_in["gearman"]["hostname"] : "127.0.0.1"), ( $_in["gearman"]["port"] ? $_in["gearman"]["port"] : 4730));
  $gm->doBackground ( "notification_create", json_encode ( $audit));

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "notifications_add_finish"))
  {
    framework_call ( "notifications_add_finish", $parameters, false);
  }

  /**
   * Return OK to notification
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/notifications/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing notification
 */
framework_add_hook (
  "notifications_edit",
  "notifications_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the system notification."),
          "required" => true,
          "example" => __ ( "My event notification")
        ),
        "Event" => array (
          "type" => "string",
          "description" => __ ( "The event this notification watch for."),
          "required" => true,
          "example" => "NewCall"
        ),
        "Filters" => array (
          "type" => "object",
          "description" => __ ( "An object with the structure of event variables filter."),
          "properties" => array (),
          "required" => false
        ),
        "Method" => array (
          "type" => "string",
          "enum" => array ( "GET", "POST", "PUT", "DELETE", "HEAD", "OPTIONS", "TRACE", "CONNECT"),
          "description" => __ ( "The HTTP method the system must use to notify the event."),
          "required" => true,
          "example" => "POST"
        ),
        "URL" => array (
          "type" => "string",
          "description" => __ ( "The URL to be notified of event."),
          "required" => true,
          "example" => "https://example.com/crm/newcall"
        ),
        "Type" => array (
          "type" => "string",
          "enum" => array ( "JSON", "FORM-DATA", "PHP"),
          "description" => __ ( "The format the data must be sent to notification endpoint."),
          "required" => true,
          "example" => "FORM-DATA"
        ),
        "Variables" => array (
          "type" => "array",
          "description" => __ ( "An array containing all notification variables mapping, with array index as variable name and value as name to be used."),
          "items" => array (
            "type" => "string"
          )
        ),
        "Headers" => array (
          "type" => "array",
          "description" => __ ( "An array containing request special headers to be used."),
          "items" => array (
            "type" => "object",
            "properties" => array (
              "Reference" => array (
                "type" => "integer",
                "description" => __ ( "The reference number to header. This is used to report any header name/value error."),
                "required" => true
              ),
              "Header" => array (
                "type" => "string",
                "description" => __ ( "The header name. This should not include reserved values and will be appended to 'X-'."),
                "example" => "MyAPI-Key",
                "required" => true
              ),
              "Value" => array (
                "type" => "string",
                "description" => __ ( "The value to be sent with the special header."),
                "example" => "123xxx456",
                "required" => true
              )
            )
          )
        ),
        "SSL" => array (
          "type" => "boolean",
          "description" => __ ( "If the system must relax SSL certificate validation if using HTTPS."),
          "required" => false,
          "example" => true
        ),
        "Validity" => array (
          "type" => "date-time",
          "description" => __ ( "The date of validity to this notification in YYYY-MM-DD format."),
          "required" => false,
          "example" => "2020-05-01"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system notification was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The description is required.")
            ),
            "Event" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided event and URL already exist.")
            ),
            "Method" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided method is invalid.")
            ),
            "URL" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The URL is required.")
            ),
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided data type is invalid.")
            ),
            "Header_X" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided extra HTTP header name are invalid.")
            ),
            "Value_X" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The provided extra HTTP header value cannot be empty.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "notifications_edit", __ ( "Edit notifications"));
framework_add_api_call (
  "/notifications/:ID",
  "Modify",
  "notifications_edit",
  array (
    "permissions" => array ( "user", "notifications_edit"),
    "title" => __ ( "Edit notifications"),
    "description" => __ ( "Change a system notification information.")
  )
);
framework_add_api_call (
  "/notifications/:ID",
  "Edit",
  "notifications_edit",
  array (
    "permissions" => array ( "user", "notifications_edit"),
    "title" => __ ( "Edit notifications"),
    "description" => __ ( "Change a system notification information.")
  )
);

/**
 * Function to edit an existing notification.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "notifications_edit_start"))
  {
    $parameters = framework_call ( "notifications_edit_start", $parameters);
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
  if ( empty ( $parameters["Event"]))
  {
    $data["Event"] = __ ( "The event is required.");
  }
  if ( ! array_key_exists ( "Event", $data) && ! array_key_exists ( $parameters["Event"], $_in["events"]))
  {
    $data["Event"] = __ ( "The provided event is invalid.");
  }
  if ( empty ( $parameters["Method"]))
  {
    $data["Method"] = __ ( "The method is required.");
  }
  if ( ! array_key_exists ( "Method", $data) && ! in_array ( $parameters["Method"], $_in["methods"]))
  {
    $data["Method"] = __ ( "The provided method is invalid.");
  }
  if ( empty ( $parameters["URL"]))
  {
    $data["URL"] = __ ( "The URL is required.");
  }
  if ( empty ( $parameters["Type"]))
  {
    $data["Type"] = __ ( "The data type is required.");
  }
  if ( ! array_key_exists ( "Type", $data) && ! in_array ( $parameters["Type"], $_in["datatypes"]))
  {
    $data["Type"] = __ ( "The provided data type is invalid.");
  }
  if ( ! is_array ( $parameters["Filters"]))
  {
    $parameters["Filters"] = array ();
  }

  /**
   * Check extra headers
   */
  $headers = array ();
  foreach ( $parameters["Headers"] as $value)
  {
    if ( ! preg_match ( "/^[0-9A-Za-z!#%&'_`\-\$\*\+\.\^\|]+$/", $value["Header"]))
    {
      $data["Header_" . $value["Reference"]] = __ ( "The provided extra HTTP header name are invalid.");
      continue;
    }
    if ( empty ( $value["Value"]))
    {
      $data["Header_" . $value["Reference"]] = __ ( "The provided extra HTTP header value cannot be empty.");
      continue;
    }
    $headers[$value["Header"]] = $value["Value"];
  }
  $parameters["Headers"] = $headers;

  /**
   * Check if notification was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `Event` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["event"]) . "' AND `URL` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["URL"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Event"] = __ ( "The provided event and URL already exist.");
  }

  /**
   * Check if notification exist (could be removed by other notification meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "notifications_edit_validate"))
  {
    $data = framework_call ( "notifications_edit_validate", $parameters, false, $data);
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
  $parameters["Validity"] = ( ! empty ( $parameters["Validity"]) ? format_form_date ( $parameters["Validity"]) . " 00:00:00" : "0000-00-00 00:00:00");
  $parameters["Filters"] = fix_rules ( $parameters["Filters"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "notifications_edit_sanitize"))
  {
    $parameters = framework_call ( "notifications_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "notifications_edit_pre"))
  {
    $parameters = framework_call ( "notifications_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change notification record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Notifications` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Event` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Event"]) . "', `Filters` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Filters"])) . "', `URL` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["URL"]) . "', `Method` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Method"]) . "', `Type` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Type"]) . "', `Variables` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Variables"])) . "', `Headers` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Headers"])) . "', `RelaxSSL` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["SSL"] ? "Y" : "N") . "', `Expire` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Validity"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "notifications_edit_post"))
  {
    framework_call ( "notifications_edit_post", $parameters);
  }

  /**
   * Create audit record
   */
  $audit = array ();
  $audit["ID"] = $parameters["ORIGINAL"]["ID"];
  if ( $parameters["ORIGINAL"]["Description"] != $parameters["Description"])
  {
    $audit["Description"] = array ( "Old" => $parameters["ORIGINAL"]["Description"], "New" => $parameters["Description"]);
  }
  if ( $parameters["ORIGINAL"]["Event"] != $parameters["Event"])
  {
    $audit["Event"] = array ( "Old" => $parameters["ORIGINAL"]["Event"], "New" => $parameters["Event"]);
  }
  if ( ! array_compare ( json_decode ( $parameters["ORIGINAL"]["Filters"], true), $parameters["Filters"]))
  {
    $audit["Filters"] = array ( "Old" => json_decode ( $parameters["ORIGINAL"]["Filters"], true), "New" => $parameters["Filters"]);
  }
  if ( $parameters["ORIGINAL"]["URL"] != $parameters["URL"])
  {
    $audit["URL"] = array ( "Old" => $parameters["ORIGINAL"]["URL"], "New" => $parameters["URL"]);
  }
  if ( $parameters["ORIGINAL"]["Method"] != $parameters["Method"])
  {
    $audit["Method"] = array ( "Old" => $parameters["ORIGINAL"]["Method"], "New" => $parameters["Method"]);
  }
  if ( $parameters["ORIGINAL"]["Type"] != $parameters["Type"])
  {
    $audit["Type"] = array ( "Old" => $parameters["ORIGINAL"]["Type"], "New" => $parameters["Type"]);
  }
  if ( ! array_compare ( json_decode ( $parameters["ORIGINAL"]["Variables"], true), $parameters["Variables"]))
  {
    $audit["Variables"] = array ( "Old" => json_decode ( $parameters["ORIGINAL"]["Variables"], true), "New" => $parameters["Variables"]);
  }
  if ( $parameters["ORIGINAL"]["Headers"] != $headers)
  {
    $audit["Headers"] = array ( "Old" => $parameters["ORIGINAL"]["Headers"], "New" => $headers);
  }
  if ( $parameters["ORIGINAL"]["RelaxSSL"] != ( $parameters["SSL"] ? "Y" : "N"))
  {
    $audit["RelaxSSL"] = array ( "Old" => $parameters["ORIGINAL"]["RelaxSSL"], "New" => $parameters["SSL"]);
  }
  if ( $parameters["ORIGINAL"]["Expire"] != $parameters["Validity"])
  {
    $audit["Expire"] = array ( "Old" => $parameters["ORIGINAL"]["Expire"], "New" => $parameters["Validity"]);
  }
  if ( framework_has_hook ( "notifications_edit_audit"))
  {
    $audit = framework_call ( "notifications_edit_audit", $parameters, false, $audit);
  }
  audit ( "notification", "edit", $audit);

  /**
   * Notify worker's about notification change
   */
  $gm = new GearmanClient ();
  $gm->addServer ( ( $_in["gearman"]["hostname"] ? $_in["gearman"]["hostname"] : "127.0.0.1"), ( $_in["gearman"]["port"] ? $_in["gearman"]["port"] : 4730));
  $gm->doBackground ( "notification_change", json_encode ( $audit));

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "notifications_edit_finish"))
  {
    framework_call ( "notifications_edit_finish", $parameters, false);
  }

  /**
   * Return OK to notification
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a notification
 */
framework_add_hook (
  "notifications_remove",
  "notifications_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system notification was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid notification ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "notifications_remove", __ ( "Remove notifications"));
framework_add_api_call (
  "/notifications/:ID",
  "Delete",
  "notifications_remove",
  array (
    "permissions" => array ( "user", "notifications_remove"),
    "title" => __ ( "Remove notifications"),
    "description" => __ ( "Remove a notification from system.")
  )
);

/**
 * Function to remove an existing notification.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function notifications_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "notifications_remove_start"))
  {
    $parameters = framework_call ( "notifications_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid notification ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "notifications_remove_validate"))
  {
    $data = framework_call ( "notifications_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "notifications_remove_sanitize"))
  {
    $parameters = framework_call ( "notifications_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if notification exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Notifications` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
  if ( framework_has_hook ( "notifications_remove_pre"))
  {
    $parameters = framework_call ( "notifications_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove notification database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Notifications` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "notifications_remove_post"))
  {
    framework_call ( "notifications_remove_post", $parameters);
  }

  /**
   * Notify worker's about notification removal
   */
  $gm = new GearmanClient ();
  $gm->addServer ( ( $_in["gearman"]["hostname"] ? $_in["gearman"]["hostname"] : "127.0.0.1"), ( $_in["gearman"]["port"] ? $_in["gearman"]["port"] : 4730));
  $gm->doBackground ( "notification_destroy", json_encode ( $audit));

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "notifications_remove_finish"))
  {
    framework_call ( "notifications_remove_finish", $parameters, false);
  }

  /**
   * Return OK to notification
   */
  return $buffer;
}
?>
