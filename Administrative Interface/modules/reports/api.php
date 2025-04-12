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
 * VoIP Domain reports module API. This module add the API calls related to
 * reports.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Reports
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch week hourly usage count report
 */
framework_add_hook (
  "reports_weekhour",
  "reports_weekhour",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Type" => array (
          "type" => "integer",
          "description" => __ ( "The type of calls. If not specified, will compute all type of calls. 1 = All calls, 2 = Internal calls, 3 = External calls, 4 = External calls (With no cost), 5 = External calls (With cost), 6 = Mobile calls, 7 = Interstate calls, 8 = International calls"),
          "required" => false,
          "example" => 1
        ),
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array matrix with hourly calls for 7 days and 0 to 23 hours of each day."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "WeekDays",
            "wrapped" => true
          ),
          "description" => __ ( "An array with 7 arrays, starting at `sunday` *0* to `saturday` *6*."),
          "minItems" => 7,
          "maxItems" => 7,
          "items" => array (
            "type" => "array",
            "xml" => array (
              "name" => "Hours"
            ),
            "description" => __ ( "An array with 24 integers, starting at *0* to *23* of numbers of calls for each hour."),
            "minItems" => 24,
            "maxItems" => 24,
            "items" => array (
              "type" => "integer"
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The type is invalid.")
            ),
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The start date is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "reports_weekhour", __ ( "Request week hourly usage count report"));
framework_add_api_call (
  "/reports/weekhour",
  "Read",
  "reports_weekhour",
  array (
    "permissions" => array ( "user", "reports_weekhour"),
    "title" => __ ( "Week hourly usage count report"),
    "description" => __ ( "Generate a week call week hourly usage count report.")
  )
);

/**
 * Function to generate week hourly usage count report data.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_weekhour ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "reports_weekhour_start"))
  {
    $parameters = framework_call ( "reports_weekhour_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( empty ( $parameters["Start"]))
  {
    $data["Start"] = __ ( "Missing start date.");
  }
  $parameters["Start"] = format_form_datetime ( $parameters["Start"]);
  if ( ! array_key_exists ( "Start", $data) && empty ( $parameters["Start"]))
  {
    $data["Start"] = __ ( "Invalid start date.");
  }
  if ( $parameters["Type"] != (int) $parameters["Type"] || ( (int) $parameters["Type"] < 0 || (int) $parameters["Type"] > 8))
  {
    $data["Type"] = __ ( "Invalid type.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "reports_weekhour_validate"))
  {
    $data = framework_call ( "reports_weekhour_validate", $parameters);
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
  $parameters["Type"] = (int) $parameters["Type"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "reports_weekhour_sanitize"))
  {
    $parameters = framework_call ( "reports_weekhour_sanitize", $parameters, false, $parameters);
  }

  /**
   * Calculate end date (7 days from start)
   */
  $parameters["End"] = date ( "Y-m-d", mktime ( 0, 0, 0, substr ( $parameters["Start"], 5, 2), substr ( $parameters["Start"], 8, 2) + 7, substr ( $parameters["Start"], 0, 4)));

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "reports_weekhour_pre"))
  {
    $parameters = framework_call ( "reports_weekhour_pre", $parameters, false, $parameters);
  }

  /**
   * Create filter based on call type
   */
  switch ( $parameters["Type"])
  {
    case "2":
      $filter = " AND `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_EXTENSION) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_EXTENSION);
      break;
    case "3":
      $filter = " AND (`calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_LANDLINE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_LANDLINE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MOBILE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MOBILE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MARINE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MARINE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_TOLLFREE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_TOLLFREE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SPECIAL) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SPECIAL) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SATELLITE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SATELLITE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SERVICES) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SERVICES) . ")";
      break;
    case "4":
      $filter = " AND (`calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_LANDLINE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_LANDLINE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MOBILE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MOBILE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MARINE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MARINE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_TOLLFREE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_TOLLFREE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SPECIAL) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SPECIAL) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SATELLITE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SATELLITE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SERVICES) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SERVICES) . ") AND `value` != 0";
      break;
    case "5":
      $filter = " AND (`calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_LANDLINE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_LANDLINE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MOBILE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MOBILE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MARINE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MARINE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_TOLLFREE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_TOLLFREE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SPECIAL) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SPECIAL) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SATELLITE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SATELLITE) . " OR `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SERVICES) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_SERVICES) . ") AND `value` = 0";
      break;
    case "6":
      $filter = " AND `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MOBILE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLENDPOINT_MOBILE);
      break;
    case "7":
      $filter = " AND `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLTYPE_INTERSTATE) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLTYPE_INTERSTATE);
      break;
    case "8":
      $filter = " AND `calltype` & " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLTYPE_INTERNATIONAL) . " = " . $_in["mysql"]["id"]->real_escape_string ( VD_CALLTYPE_INTERNATIONAL);
      break;
    default:
      $filter = "";
      break;
  }

  /**
   * Request call count from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT SUBSTR(`calldate`, 1, 13) AS `Data`, COUNT(*) AS `Total` FROM `cdr` WHERE `calldate` != '0000-00-00 00:00:00' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . " 00:00:00' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . " 23:59:59'" . $filter . " GROUP BY `Data` ORDER BY `Data`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $matrix = array ();
  $daykey = array ();
  for ( $day = 1; $day <= 7; $day++)
  {
    $daykey[date ( "Y-m-d", mktime ( 0, 0, 0, substr ( $parameters["Start"], 5, 2), substr ( $parameters["Start"], 8, 2) + $day - 1, substr ( $parameters["Start"], 0, 4)))] = $day;
    $matrix[$day] = array ();
    for ( $hour = 0; $hour <= 23; $hour++)
    {
      $matrix[$day][$hour] = 0;
    }
  }
  while ( $record = $result->fetch_assoc ())
  {
    $matrix[$daykey[substr ( $record["Data"], 0, 10)]][(int) substr ( $record["Data"], 11, 2)] = (int) $record["Total"];
  }
  $data = array ();
  for ( $day = 7; $day >= 1; $day--)
  {
    $data[] = $matrix[$day];
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "reports_weekhour_post"))
  {
    $data = framework_call ( "reports_weekhour_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "reports_weekhour_finish"))
  {
    framework_call ( "reports_weekhour_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch extensions listing report
 */
framework_add_hook (
  "reports_list",
  "reports_list",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Name" => array (
          "type" => "string",
          "description" => __ ( "Filter search extensions description based on this value."),
          "example" => __ ( "filter")
        ),
        "Type" => array (
          "type" => "string",
          "description" => __ ( "Filter search type description based on this value."),
          "example" => __ ( "filter")
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the extension list."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "Extension unique system internal identification."),
                "example" => 1
              ),
              "Number" => array (
                "type" => "integer",
                "description" => __ ( "Extension number."),
                "example" => 1000
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "Extension description."),
                "example" => "Ernani Azevedo"
              ),
              "Type" => array (
                "type" => "string",
                "description" => __ ( "Extension type."),
                "example" => "extension_phone"
              )
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "reports_list", __ ( "Request extensions listing"));
framework_add_api_call (
  "/reports/list",
  "Read",
  "reports_list",
  array (
    "permissions" => array ( "user", "reports_list"),
    "title" => __ ( "Extensions list report"),
    "description" => __ ( "Extensions list report.")
  )
);

/**
 * Function to generate extensions list report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_list ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "reports_list_start"))
  {
    $parameters = framework_call ( "reports_list_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "reports_list_validate"))
  {
    $data = framework_call ( "reports_list_validate", $parameters);
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
  $parameters["Type"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Type"])));

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "reports_list_sanitize"))
  {
    $parameters = framework_call ( "reports_list_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "reports_list_pre"))
  {
    $parameters = framework_call ( "reports_list_pre", $parameters, false, $parameters);
  }

  /**
   * Prepare where clause
   */
  $where = "";
  if ( ! empty ( $parameters["Name"]))
  {
    $where .= " AND `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Name"]) . "%'";
  }
  if ( ! empty ( $parameters["Type"]))
  {
    $where .= " AND `Type` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Type"]) . "'";
  }

  /**
   * Request extensions from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Number`, `Description`, `Type` FROM `Extensions`" . ( ! empty ( $where) ? " WHERE" . substr ( $where, 4) : "") . " ORDER BY `Number`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $data = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    $extension["Type"] = "extension_" . $extension["Type"];
    $data[] = api_filter_entry ( array ( "ID", "Number", "Description", "Type"), $extension);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "reports_list_post"))
  {
    $data = framework_call ( "reports_list_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "reports_list_finish"))
  {
    framework_call ( "reports_list_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch ranges listing report
 */
framework_add_hook (
  "reports_ranges",
  "reports_ranges",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Range" => array (
          "type" => "integer",
          "description" => __ ( "The ID of range to be filtered. If not specified, return all ranges."),
          "required" => false,
          "example" => 1
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the extension list."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "Extension unique system internal identification."),
                "example" => 1
              ),
              "Extension" => array (
                "type" => "integer",
                "description" => __ ( "Extension number."),
                "example" => 1000
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "Extension description."),
                "example" => "Ernani Azevedo"
              ),
              "Type" => array (
                "type" => "string",
                "description" => __ ( "Extension type."),
                "example" => "extension_phone"
              ),
              "Range" => array (
                "type" => "string",
                "description" => __ ( "Extension range."),
                "example" => __ ( "Headquarter")
              ),
              "RangeID" => array (
                "type" => "integer",
                "description" => __ ( "Extension range ID."),
                "example" => 1
              ),
              "Server" => array (
                "type" => "string",
                "description" => __ ( "Extension server."),
                "example" => __ ( "Main server")
              ),
              "ServerID" => array (
                "type" => "integer",
                "description" => __ ( "Extension server ID."),
                "example" => 1
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
            "Range" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The range is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "reports_ranges", __ ( "Request ranges listing"));
framework_add_api_call (
  "/reports/ranges",
  "Read",
  "reports_ranges",
  array (
    "permissions" => array ( "user", "reports_ranges"),
    "title" => __ ( "Ranges listing"),
    "description" => __ ( "Generate range extension listings.")
  )
);

/**
 * Function to generate ranges list report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_ranges ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "reports_ranges_start"))
  {
    $parameters = framework_call ( "reports_ranges_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( $parameters["Range"] != (int) $parameters["Range"])
  {
    $data["Range"] = __ ( "Invalid range.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "reports_ranges_validate"))
  {
    $data = framework_call ( "reports_ranges_validate", $parameters);
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
  $parameters["Range"] = (int) $parameters["Range"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "reports_ranges_sanitize"))
  {
    $parameters = framework_call ( "reports_ranges_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "reports_ranges_pre"))
  {
    $parameters = framework_call ( "reports_ranges_pre", $parameters, false, $parameters);
  }

  /**
   * Request extensions from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Ranges`.*, `Servers`.`ID` AS `ServerID`, `Servers`.`Description` AS `ServerDescription` FROM `Ranges` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID`" . ( $parameters["Range"] ? " WHERE `Ranges`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Range"]) : "")))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $data = array ();
  while ( $range = $result->fetch_assoc ())
  {
    for ( $extension = $range["Start"]; $extension <= $range["Finish"]; $extension++)
    {
      $allocation = filters_call ( "get_extensions", array ( "Number" => $extension));
      if ( sizeof ( $allocation) != 0)
      {
        $data[] = array ( "ID" => $allocation[0]["ID"], "Extension" => (int) $extension, "Description" => $allocation[0]["Description"], "Type" => "extension_" . $allocation[0]["Type"], "Range" => $range["Description"], "RangeID" => $range["ID"], "Server" => $range["ServerDescription"], "ServerID" => $range["ServerID"]);
      } else {
        $data[] = array ( "ID" => null, "Extension" => (int) $extension, "Description" => null, "Type" => null, "Range" => $range["Description"], "RangeID" => $range["ID"], "Server" => $range["ServerDescription"], "ServerID" => $range["ServerID"]);
      }
    }
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "reports_ranges_post"))
  {
    $data = framework_call ( "reports_ranges_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "reports_ranges_finish"))
  {
    framework_call ( "reports_ranges_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch cost centers financial report
 */
framework_add_hook (
  "reports_financial_costcenters",
  "reports_financial_costcenters",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the cost center extension list."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "Extension" => array (
                "type" => "object",
                "description" => __ ( "Extension information."),
                "properties" => array (
                  "ID" => array (
                    "type" => "integer",
                    "description" => __ ( "Extension unique system internal identification."),
                    "example" => 1
                  ),
                  "Number" => array (
                    "type" => "integer",
                    "description" => __ ( "Extension number."),
                    "example" => 1000
                  ),
                  "Description" => array (
                    "type" => "string",
                    "description" => __ ( "Extension description."),
                    "example" => "Ernani Azevedo"
                  )
                )
              ),
              "Total" => array (
                "type" => "integer",
                "description" => __ ( "Total of calls."),
                "example" => 15
              ),
              "Time" => array (
                "type" => "integer",
                "description" => __ ( "Total duration of calls in seconds."),
                "example" => 731
              ),
              "FormattedTime" => array (
                "type" => "string",
                "description" => __ ( "Total duration of calls formatted in [[DD:]HH:]MM:SS."),
                "example" => "12:11"
              ),
              "Cost" => array (
                "type" => "float",
                "description" => __ ( "Total cost of calls."),
                "example" => 21.32
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
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The type is invalid.")
            ),
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The start date is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "reports_financial_costcenters", __ ( "Request financial cost centers report"));
framework_add_api_call (
  "/reports/financial/costcenter/:ID",
  "Read",
  "reports_financial_costcenters",
  array (
    "permissions" => array ( "user", "reports_financial_costcenters"),
    "title" => __ ( "Cost center financial reports"),
    "description" => __ ( "Generate cost center financial reports."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The cost center internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate cost centers financial report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_financial_costcenters ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "reports_financial_costcenters_start"))
  {
    $parameters = framework_call ( "reports_financial_costcenters_start", $parameters);
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
  if ( framework_has_hook ( "reports_financial_costcenters_validate"))
  {
    $data = framework_call ( "reports_financial_costcenters_validate", $parameters);
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
  if ( framework_has_hook ( "reports_financial_costcenters_sanitize"))
  {
    $parameters = framework_call ( "reports_financial_costcenters_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "reports_financial_costcenters_pre"))
  {
    $parameters = framework_call ( "reports_financial_costcenters_pre", $parameters, false, $parameters);
  }

  /**
   * Request extensions with the requested cost center from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.* FROM `Extensions` LEFT JOIN `ExtensionPhone` ON `Extensions`.`ID` = `ExtensionPhone`.`Extension` LEFT JOIN `Groups` ON `ExtensionPhone`.`Group` = `Groups`.`ID` WHERE ( `ExtensionPhone`.`CostCenter` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " OR ( `ExtensionPhone`.`CostCenter` IS NULL AND `Groups`.`CostCenter` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ")) ORDER BY `Extensions`.`Number`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $data = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    if ( ! $sum = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total`, SUM(value) AS `Cost`, SUM(billsec) as `Time` FROM `cdr` WHERE `src` = '" . $_in["mysql"]["id"]->real_escape_string ( $extension["Number"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $sum->num_rows == 1)
    {
      $values = $sum->fetch_assoc ();
    } else {
      $values = array ( "Total" => 0, "Cost" => 0, "Time" => 0);
    }
    $data[] = array ( "Extension" => array ( "ID" => (int) $extension["ID"], "Number" => $extension["Number"], "Description" => $extension["Description"]), "Total" => (int) $values["Total"], "Time" => (int) $values["Time"], "FormattedTime" => format_secs_to_string ( $values["Time"]), "Cost" => (float) $values["Cost"]);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "reports_financial_costcenters_post"))
  {
    $data = framework_call ( "reports_financial_costcenters_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "reports_financial_costcenters_finish"))
  {
    framework_call ( "reports_financial_costcenters_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch groups financial report
 */
framework_add_hook (
  "reports_financial_groups",
  "reports_financial_groups",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the range extension list."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "Extension" => array (
                "type" => "object",
                "description" => __ ( "Extension information."),
                "properties" => array (
                  "ID" => array (
                    "type" => "integer",
                    "description" => __ ( "Extension unique system internal identification."),
                    "example" => 1
                  ),
                  "Number" => array (
                    "type" => "integer",
                    "description" => __ ( "Extension number."),
                    "example" => 1000
                  ),
                  "Description" => array (
                    "type" => "string",
                    "description" => __ ( "Extension description."),
                    "example" => "Ernani Azevedo"
                  )
                )
              ),
              "Total" => array (
                "type" => "integer",
                "description" => __ ( "Total of calls."),
                "example" => 15
              ),
              "Time" => array (
                "type" => "integer",
                "description" => __ ( "Total duration of calls in seconds."),
                "example" => 731
              ),
              "FormattedTime" => array (
                "type" => "string",
                "description" => __ ( "Total duration of calls formatted in [[DD:]HH:]MM:SS."),
                "example" => "12:11"
              ),
              "Cost" => array (
                "type" => "float",
                "description" => __ ( "Total cost of calls."),
                "example" => 21.32
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
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The type is invalid.")
            ),
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The start date is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "reports_financial_groups", __ ( "Request financial groups report"));
framework_add_api_call (
  "/reports/financial/group/:ID",
  "Read",
  "reports_financial_groups",
  array (
    "permissions" => array ( "user", "reports_financial_groups"),
    "title" => __ ( "Group financial reports"),
    "description" => __ ( "Generate group financial reports."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The group internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate groups financial report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_financial_groups ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "reports_financial_groups_start"))
  {
    $parameters = framework_call ( "reports_financial_groups_start", $parameters);
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
  if ( framework_has_hook ( "reports_financial_groups_validate"))
  {
    $data = framework_call ( "reports_financial_groups_validate", $parameters);
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
  if ( framework_has_hook ( "reports_financial_groups_sanitize"))
  {
    $parameters = framework_call ( "reports_financial_groups_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "reports_financial_groups_pre"))
  {
    $parameters = framework_call ( "reports_financial_groups_pre", $parameters, false, $parameters);
  }

  /**
   * Request extensions with the requested group from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` LEFT JOIN `ExtensionPhone` ON `ExtensionPhone`.`Extension` = `Extensions`.`ID` WHERE `ExtensionPhone`.`Group` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " ORDER BY `Extensions`.`Number`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $data = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    if ( ! $sum = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total`, SUM(value) AS `Cost`, SUM(billsec) as `Time` FROM `cdr` WHERE `src` = '" . $_in["mysql"]["id"]->real_escape_string ( $extension["Number"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $sum->num_rows == 1)
    {
      $values = $sum->fetch_assoc ();
    } else {
      $values = array ( "Total" => 0, "Cost" => 0, "Time" => 0);
    }
    $data[] = array ( "Extension" => array ( "ID" => (int) $extension["ID"], "Number" => $extension["Number"], "Description" => $extension["Description"]), "Total" => (int) $values["Total"], "Time" => (int) $values["Time"], "FormattedTime" => format_secs_to_string ( $values["Time"]), "Cost" => (float) $values["Cost"]);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "reports_financial_groups_post"))
  {
    $data = framework_call ( "reports_financial_groups_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "reports_financial_groups_finish"))
  {
    framework_call ( "reports_financial_groups_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch gateways financial report
 */
framework_add_hook (
  "reports_financial_gateways",
  "reports_financial_gateways",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the range extension list."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "Gateway" => array (
                "type" => "object",
                "description" => __ ( "Gateway information."),
                "properties" => array (
                  "ID" => array (
                    "type" => "integer",
                    "description" => __ ( "Gateway unique system internal identification."),
                    "example" => 1
                  ),
                  "Description" => array (
                    "type" => "string",
                    "description" => __ ( "Gateway description."),
                    "example" => __ ( "My SIP Provider")
                  )
                )
              ),
              "Total" => array (
                "type" => "integer",
                "description" => __ ( "Total of calls."),
                "example" => 15
              ),
              "Time" => array (
                "type" => "integer",
                "description" => __ ( "Total duration of calls in seconds."),
                "example" => 731
              ),
              "FormattedTime" => array (
                "type" => "string",
                "description" => __ ( "Total duration of calls formatted in [[DD:]HH:]MM:SS."),
                "example" => "12:11"
              ),
              "Cost" => array (
                "type" => "float",
                "description" => __ ( "Total cost of calls."),
                "example" => 21.32
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
            "Type" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The type is invalid.")
            ),
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The start date is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "reports_financial_gateways", __ ( "Request financial gateways report"));
framework_add_api_call (
  "/reports/financial/gateway",
  "Read",
  "reports_financial_gateways",
  array (
    "permissions" => array ( "user", "reports_financial_gateways"),
    "title" => __ ( "Gateways financial reports"),
    "description" => __ ( "Generate gateways financial reports.")
  )
);

/**
 * Function to generate gateways financial report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_financial_gateways ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "reports_financial_gateways_start"))
  {
    $parameters = framework_call ( "reports_financial_gateways_start", $parameters);
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
  if ( framework_has_hook ( "reports_financial_gateways_validate"))
  {
    $data = framework_call ( "reports_financial_gateways_validate", $parameters);
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

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "reports_financial_gateways_sanitize"))
  {
    $parameters = framework_call ( "reports_financial_gateways_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "reports_financial_gateways_pre"))
  {
    $parameters = framework_call ( "reports_financial_gateways_pre", $parameters, false, $parameters);
  }

  /**
   * Request gateways from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` ORDER BY `Description`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $data = array ();
  while ( $gateway = $result->fetch_assoc ())
  {
    if ( ! $sum = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total`, SUM(value) AS `Cost`, SUM(billsec) as `Time` FROM `cdr` WHERE `gateway` = '" . $_in["mysql"]["id"]->real_escape_string ( $gateway["ID"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $sum->num_rows == 1)
    {
      $values = $sum->fetch_assoc ();
    } else {
      $values = array ( "Total" => 0, "Cost" => 0, "Time" => 0);
    }
    $data[] = array ( "Gateway" => array ( "ID" => (int) $gateway["ID"], "Description" => $gateway["Description"]), "Total" => (int) $values["Total"], "Time" => (int) $values["Time"], "FormattedTime" => format_secs_to_string ( $values["Time"]), "Cost" => (float) $values["Cost"]);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "reports_financial_gateways_post"))
  {
    $data = framework_call ( "reports_financial_gateways_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "reports_financial_gateways_finish"))
  {
    framework_call ( "reports_financial_gateways_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch system health information
 */
framework_add_hook (
  "reports_system_health",
  "reports_system_health",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system health."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Memory" => array (
              "type" => "object",
              "description" => __ ( "An object containing memory usage information."),
              "properties" => array (
                "Percent" => array (
                  "type" => "integer",
                  "description" => __ ( "The percentage of used memory (rounded)."),
                  "example" => 26
                ),
                "Used" => array (
                  "type" => "string",
                  "description" => __ ( "The total of used memory."),
                  "example" => "518.84 MB"
                ),
                "Total" => array (
                  "type" => "string",
                  "description" => __ ( "The total of memory."),
                  "example" => "1.95 GB"
                )
              )
            ),
            "CPU" => array (
              "type" => "object",
              "description" => __ ( "An object containing CPU usage information."),
              "properties" => array (
                "Percent" => array (
                  "type" => "integer",
                  "description" => __ ( "The percentage of used CPU (rounded)."),
                  "example" => 1
                ),
                "Processors" => array (
                  "type" => "integer",
                  "description" => __ ( "The number of processors in server."),
                  "example" => 16
                )
              )
            ),
            "Storage" => array (
              "type" => "object",
              "description" => __ ( "An object containing storage usage information."),
              "properties" => array (
                "Percent" => array (
                  "type" => "integer",
                  "description" => __ ( "The percentage of used storage (rounded)."),
                  "example" => 59
                ),
                "Used" => array (
                  "type" => "string",
                  "description" => __ ( "The total of used storage."),
                  "example" => "10.76 TB"
                ),
                "Total" => array (
                  "type" => "string",
                  "description" => __ ( "The total of storage."),
                  "example" => "18.18 TB"
                )
              )
            ),
          )
        )
      )
    )
  )
);
framework_add_permission ( "reports_system_health", __ ( "Request server health"));
framework_add_api_call (
  "/reports/status",
  "Read",
  "reports_system_health",
  array (
    "permissions" => array ( "user", "reports_system_health"),
    "title" => __ ( "System usage health report"),
    "description" => __ ( "Provide the system usage health of server (Memory, CPU and Storage).")
  )
);

/**
 * Function to generate system health information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_system_health ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "reports_system_health_start"))
  {
    $parameters = framework_call ( "reports_system_health_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "reports_system_health_validate"))
  {
    $data = framework_call ( "reports_system_health_validate", $parameters);
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
  if ( framework_has_hook ( "reports_system_health_sanitize"))
  {
    $parameters = framework_call ( "reports_system_health_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "reports_system_health_pre"))
  {
    $parameters = framework_call ( "reports_system_health_pre", $parameters, false, $parameters);
  }

  /**
   * Get CPU usage information
   */
  $exec_loads = sys_getloadavg ();
  $exec_cores = (int) trim ( shell_exec ( "grep -P '^processor' /proc/cpuinfo | wc -l"));
  $cpu = round ( $exec_loads[1] / ( $exec_cores + 1) * 100, 0);

  /**
   * Get memory usage information
   */
  $exec_free = trim ( shell_exec ( "free | grep -P '^Mem:'"));
  $get_mem = preg_split ( "/[\s]+/", $exec_free);
  $mem = round ( $get_mem[2] / $get_mem[1] * 100, 0);
  $suffix = array ( "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
  $class = min ( (int) log ( $get_mem[1], 1024), count ( $suffix) - 1);
  $total_mem = sprintf ( "%1.2f", $get_mem[1] / pow ( 1024, $class)) . " " . $suffix[$class];
  $class = min ( (int) log ( $get_mem[2], 1024), count ( $suffix) - 1);
  $used_mem = sprintf ( "%1.2f", $get_mem[2] / pow ( 1024, $class)) . " " . $suffix[$class];

  /**
   * Get storage usage information
   */
  $total_disk = disk_total_space ( ".");
  $used_disk = $total_disk - disk_free_space ( ".");
  $disk = round ( $used_disk * 100 / $total_disk, 0);
  $class = min ( (int) log ( $total_disk, 1024) , count ( $suffix) - 1);
  $total_disk = sprintf ( "%1.2f", $total_disk / pow ( 1024, $class)) . " " . $suffix[$class];
  $class = min ( (int) log ( $used_disk, 1024) , count ( $suffix) - 1);
  $used_disk = sprintf ( "%1.2f", $used_disk / pow ( 1024, $class)) . " " . $suffix[$class];

  /**
   * Format data
   */
  $data = array ();
  $data["Memory"] = array ( "Percent" => $mem, "Used" => $used_mem, "Total" => $total_mem);
  $data["CPU"] = array ( "Percent" => $cpu, "Processors" => $exec_cores);
  $data["Storage"] = array ( "Percent" => $disk, "Used" => $used_disk, "Total" => $total_disk);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "reports_system_health_post"))
  {
    $data = framework_call ( "reports_system_health_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "reports_system_health_finish"))
  {
    framework_call ( "reports_system_health_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch extensions activity listing report
 */
framework_add_hook (
  "reports_activity",
  "reports_activity",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the report results."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "Extension unique system internal identification."),
                "example" => 1
              ),
              "Number" => array (
                "type" => "integer",
                "description" => __ ( "Extension number."),
                "example" => 1000
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "Extension description."),
                "example" => "Ernani Azevedo"
              ),
              "Type" => array (
                "type" => "string",
                "description" => __ ( "Extension type."),
                "example" => "extension_phone"
              ),
              "LastDialed" => array (
                "type" => "object",
                "description" => __ ( "Last date and time that extension did a call."),
                "properties" => array (
                  "Datetime" => array (
                    "type" => "date-time",
                    "description" => __ ( "The date and time of last placed call. Empty if none."),
                    "example" => "2020-05-01T00:00:00Z"
                  ),
                  "Timestamp" => array (
                    "type" => "integer",
                    "description" => __ ( "The timestamp of last placed call. Empty if none."),
                    "example" => 1588291200
                  )
                )
              ),
              "LastReceived" => array (
                "type" => "object",
                "description" => __ ( "Last date and time that extension received a call."),
                "properties" => array (
                  "Datetime" => array (
                    "type" => "date-time",
                    "description" => __ ( "The date and time of last received call. Empty if none."),
                    "example" => "2020-05-01T00:00:00Z"
                  ),
                  "Timestamp" => array (
                    "type" => "integer",
                    "description" => __ ( "The timestamp of last received call. Empty if none."),
                    "example" => 1588291200
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "reports_activity", __ ( "Request extensions activity report"));
framework_add_api_call (
  "/reports/activity",
  "Read",
  "reports_activity",
  array (
    "permissions" => array ( "user", "reports_activity"),
    "title" => __ ( "Call activity report"),
    "description" => __ ( "Generate an activity report with all extensions with latest received and dialed date time.")
  )
);

/**
 * Function to generate extensions activity list report.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_activity ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "reports_activity_start"))
  {
    $parameters = framework_call ( "reports_activity_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "reports_activity_validate"))
  {
    $data = framework_call ( "reports_activity_validate", $parameters);
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
  if ( framework_has_hook ( "reports_activity_sanitize"))
  {
    $parameters = framework_call ( "reports_activity_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "reports_activity_pre"))
  {
    $parameters = framework_call ( "reports_activity_pre", $parameters, false, $parameters);
  }

  /**
   * Request extensions with activity from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Number`, `Extensions`.`Description`, `Extensions`.`Type`, `ExtensionActivity`.`LastDialed`, `ExtensionActivity`.`LastReceived` FROM `Extensions` LEFT JOIN `ExtensionActivity` ON `Extensions`.`ID` = `ExtensionActivity`.`UID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Format data
   */
  $data = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    $extension["LastDialed"] = array (
      "Datetime" => format_db_iso8601 ( $extension["LastDialed"]),
      "Timestamp" => format_db_timestamp ( $extension["LastDialed"])
    );
    $extension["LastReceived"] = array (
      "Datetime" => format_db_iso8601 ( $extension["LastReceived"]),
      "Timestamp" => format_db_timestamp ( $extension["LastReceived"])
    );
    $extension["Type"] = "extension_" . $extension["Type"];
    $data[] = api_filter_entry ( array ( "ID", "Number", "Description", "Type", "LastDialed", "LastReceived"), $extension);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "reports_activity_post"))
  {
    $data = framework_call ( "reports_activity_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "reports_activity_finish"))
  {
    framework_call ( "reports_activity_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate user received call's report
 */
framework_add_hook (
  "user_received_report",
  "user_received_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the calls."),
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
framework_add_permission ( "user_received_report", __ ( "User received calls report"));
framework_add_api_call (
  "/reports/received/user/:ID",
  "Read",
  "user_received_report",
  array (
    "permissions" => array ( "user", "user_received_report"),
    "title" => __ ( "User received calls report"),
    "description" => __ ( "Generate user received calls reports."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The extension internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate user received calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function user_received_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "user_received_report_start"))
  {
    $parameters = framework_call ( "user_received_report_start", $parameters);
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
  if ( framework_has_hook ( "user_received_report_validate"))
  {
    $data = framework_call ( "user_received_report_validate", $parameters);
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
  if ( framework_has_hook ( "user_received_report_sanitize"))
  {
    $parameters = framework_call ( "user_received_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "user_received_report_pre"))
  {
    $parameters = framework_call ( "user_received_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get user extension information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `dstid` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
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
    $call["extension"] = $extension["Number"];
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "user_received_report_post"))
  {
    $data = framework_call ( "user_received_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "user_received_report_finish"))
  {
    framework_call ( "user_received_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate group received call's report
 */
framework_add_hook (
  "group_received_report",
  "group_received_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the calls."),
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
framework_add_permission ( "group_received_report", __ ( "Group received calls report"));
framework_add_api_call (
  "/reports/received/group/:ID",
  "Read",
  "group_received_report",
  array (
    "permissions" => array ( "user", "group_received_report"),
    "title" => __ ( "Group received calls report"),
    "description" => __ ( "Generate group received calls reports."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The group internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate group received calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function group_received_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "group_received_report_start"))
  {
    $parameters = framework_call ( "group_received_report_start", $parameters);
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
  if ( framework_has_hook ( "group_received_report_validate"))
  {
    $data = framework_call ( "group_received_report_validate", $parameters);
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
  if ( framework_has_hook ( "group_received_report_sanitize"))
  {
    $parameters = framework_call ( "group_received_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "group_received_report_pre"))
  {
    $parameters = framework_call ( "group_received_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get all extensions from group information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`ExtensionPhone`.`Extension` SEPARATOR ',') AS `Extensions` FROM `Groups` LEFT JOIN `ExtensionPhone` ON `Groups`.`ID` = `ExtensionPhone`.`Group` WHERE `Groups`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " GROUP BY `Groups`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extensions = $result->fetch_assoc ()["Extensions"];

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `dstid` IN ('" . $_in["mysql"]["id"]->real_escape_string ( $extensions) . "') AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
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
    $call["extension"] = $extension["Number"];
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "group_received_report_post"))
  {
    $data = framework_call ( "group_received_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "group_received_report_finish"))
  {
    framework_call ( "group_received_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate gateway received call's report
 */
framework_add_hook (
  "gateway_received_report",
  "gateway_received_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the calls."),
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
framework_add_permission ( "gateway_received_report", __ ( "Gateway received calls report"));
framework_add_api_call (
  "/reports/received/gateway/:ID",
  "Read",
  "gateway_received_report",
  array (
    "permissions" => array ( "user", "gateway_received_report"),
    "title" => __ ( "Gateway received calls report"),
    "description" => __ ( "Generate gateway received calls reports."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The gateway internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate gateway received calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateway_received_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateway_received_report_start"))
  {
    $parameters = framework_call ( "gateway_received_report_start", $parameters);
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
  if ( framework_has_hook ( "gateway_received_report_validate"))
  {
    $data = framework_call ( "gateway_received_report_validate", $parameters);
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
  if ( framework_has_hook ( "gateway_received_report_sanitize"))
  {
    $parameters = framework_call ( "gateway_received_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateway_received_report_pre"))
  {
    $parameters = framework_call ( "gateway_received_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `gateway` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' AND `dstid` != 0 ORDER BY `calldate` DESC"))
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
    $call["extension"] = $extension["Number"];
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateway_received_report_post"))
  {
    $data = framework_call ( "gateway_received_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateway_received_report_finish"))
  {
    framework_call ( "gateway_received_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate system received call's report
 */
framework_add_hook (
  "system_received_report",
  "system_received_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the calls."),
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
framework_add_permission ( "system_received_report", __ ( "All received calls report"));
framework_add_api_call (
  "/reports/received/all",
  "Read",
  "system_received_report",
  array (
    "permissions" => array ( "user", "system_received_report"),
    "title" => __ ( "System received calls report"),
    "description" => __ ( "Generate system received calls reports.")
  )
);

/**
 * Function to generate system received calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function system_received_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "system_received_report_start"))
  {
    $parameters = framework_call ( "system_received_report_start", $parameters);
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
  if ( framework_has_hook ( "system_received_report_validate"))
  {
    $data = framework_call ( "system_received_report_validate", $parameters);
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

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "system_received_report_sanitize"))
  {
    $parameters = framework_call ( "system_received_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "system_received_report_pre"))
  {
    $parameters = framework_call ( "system_received_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `dstid` != 0 AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
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
    $call["extension"] = $extension["Number"];
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "system_received_report_post"))
  {
    $data = framework_call ( "system_received_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "system_received_report_finish"))
  {
    framework_call ( "system_received_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate user made call's report
 */
framework_add_hook (
  "user_made_report",
  "user_made_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the calls."),
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
framework_add_permission ( "user_made_report", __ ( "User made calls report"));
framework_add_api_call (
  "/reports/made/user/:ID",
  "Read",
  "user_made_report",
  array (
    "permissions" => array ( "user", "user_made_report"),
    "title" => __ ( "User made calls report"),
    "description" => __ ( "Generate user made calls reports."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The extension internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate users made calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function user_made_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "user_made_report_start"))
  {
    $parameters = framework_call ( "user_made_report_start", $parameters);
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
  if ( framework_has_hook ( "user_made_report_validate"))
  {
    $data = framework_call ( "user_made_report_validate", $parameters);
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
  if ( framework_has_hook ( "user_made_report_sanitize"))
  {
    $parameters = framework_call ( "user_made_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "user_made_report_pre"))
  {
    $parameters = framework_call ( "user_made_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get user extension information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `srcid` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
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
    $call["extension"] = $extension["Number"];
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "user_made_report_post"))
  {
    $data = framework_call ( "user_made_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "user_made_report_finish"))
  {
    framework_call ( "user_made_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate group made call's report
 */
framework_add_hook (
  "group_made_report",
  "group_made_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the calls."),
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
framework_add_permission ( "group_made_report", __ ( "Group made calls report"));
framework_add_api_call (
  "/reports/made/group/:ID",
  "Read",
  "group_made_report",
  array (
    "permissions" => array ( "user", "group_made_report"),
    "title" => __ ( "Group made calls report"),
    "description" => __ ( "Generate group made calls reports."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The group internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate group made calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function group_made_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "group_made_report_start"))
  {
    $parameters = framework_call ( "group_made_report_start", $parameters);
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
  if ( framework_has_hook ( "group_made_report_validate"))
  {
    $data = framework_call ( "group_made_report_validate", $parameters);
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
  if ( framework_has_hook ( "group_made_report_sanitize"))
  {
    $parameters = framework_call ( "group_made_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "group_made_report_pre"))
  {
    $parameters = framework_call ( "group_made_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get all extensions from group information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`ExtensionPhone`.`Extension` SEPARATOR ',') AS `Extensions` FROM `Groups` LEFT JOIN `ExtensionPhone` ON `Groups`.`ID` = `ExtensionPhone`.`Group` WHERE `Groups`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " GROUP BY `Groups`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extensions = $result->fetch_assoc ()["Extensions"];

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `srcid` IN ('" . $_in["mysql"]["id"]->real_escape_string ( $extensions) . "') AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
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
    $call["extension"] = $extension["Number"];
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "group_made_report_post"))
  {
    $data = framework_call ( "group_made_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "group_made_report_finish"))
  {
    framework_call ( "group_made_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate gateway made call's report
 */
framework_add_hook (
  "gateway_made_report",
  "gateway_made_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the calls."),
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
framework_add_permission ( "gateway_made_report", __ ( "Gateway made calls report"));
framework_add_api_call (
  "/reports/made/gateway/:ID",
  "Read",
  "gateway_made_report",
  array (
    "permissions" => array ( "user", "gateway_made_report"),
    "title" => __ ( "Gateway made calls report"),
    "description" => __ ( "Generate gateway made calls reports."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The gateway internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate gateway made calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateway_made_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "gateway_made_report_start"))
  {
    $parameters = framework_call ( "gateway_made_report_start", $parameters);
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
  if ( framework_has_hook ( "gateway_made_report_validate"))
  {
    $data = framework_call ( "gateway_made_report_validate", $parameters);
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
  if ( framework_has_hook ( "gateway_made_report_sanitize"))
  {
    $parameters = framework_call ( "gateway_made_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "gateway_made_report_pre"))
  {
    $parameters = framework_call ( "gateway_made_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `gateway` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' AND `srcid` != 0 ORDER BY `calldate` DESC"))
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
    $call["extension"] = $extension["Number"];
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "gateway_made_report_post"))
  {
    $data = framework_call ( "gateway_made_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "gateway_made_report_finish"))
  {
    framework_call ( "gateway_made_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate system made call's report
 */
framework_add_hook (
  "system_made_report",
  "system_made_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Start" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report start calls."),
          "required" => true,
          "example" => "2020-05-01T00:00:00Z"
        ),
        "End" => array (
          "type" => "date",
          "description" => __ ( "The date and time of report end calls."),
          "required" => true,
          "example" => "2020-05-02T00:00:00Z"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the calls."),
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
framework_add_permission ( "system_made_report", __ ( "All made calls report"));
framework_add_api_call (
  "/reports/made/all",
  "Read",
  "system_made_report",
  array (
    "permissions" => array ( "user", "system_made_report"),
    "title" => __ ( "System made calls report"),
    "description" => __ ( "Generate system made calls reports.")
  )
);

/**
 * Function to generate system made calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function system_made_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "system_made_report_start"))
  {
    $parameters = framework_call ( "system_made_report_start", $parameters);
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
  if ( framework_has_hook ( "system_made_report_validate"))
  {
    $data = framework_call ( "system_made_report_validate", $parameters);
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

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "system_made_report_sanitize"))
  {
    $parameters = framework_call ( "system_made_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "system_made_report_pre"))
  {
    $parameters = framework_call ( "system_made_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `srcid` != 0 AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
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
    $call["extension"] = $extension["Number"];
    $data[] = filters_call ( "process_call", $call);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "system_made_report_post"))
  {
    $data = framework_call ( "system_made_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "system_made_report_finish"))
  {
    framework_call ( "system_made_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate consolidated extensions call's report
 */
framework_add_hook (
  "consolidated_extension_report",
  "consolidated_extension_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Month" => array (
          "type" => "string",
          "description" => __ ( "The month and year to generate consolidate report."),
          "pattern" => "^\d{2}\/\d{4}\$",
          "required" => true,
          "example" => "05/2020"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing report extensions."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "Extension unique system internal identification."),
                "example" => 1
              ),
              "Number" => array (
                "type" => "integer",
                "description" => __ ( "Extension number."),
                "example" => 1000
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "Extension description."),
                "example" => "Ernani Azevedo"
              ),
              "Type" => array (
                "type" => "string",
                "description" => __ ( "Extension type."),
                "example" => "extension_phone"
              ),
              "Local" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Local"
                ),
                "description" => __ ( "Local calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "Interstate" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Interstate"
                ),
                "description" => __ ( "Interstate calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "International" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "International"
                ),
                "description" => __ ( "International calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "Others" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Others"
                ),
                "description" => __ ( "Others calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
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
            "Month" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Requested month date is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "consolidated_extension_report", __ ( "Consolidated extensions calls report"));
framework_add_api_call (
  "/reports/consolidated/extensions",
  "Read",
  "consolidated_extension_report",
  array (
    "permissions" => array ( "user", "consolidated_extension_report"),
    "title" => __ ( "Consolidated extensions calls report"),
    "description" => __ ( "Generate a consolidated calls report grouped by extensions.")
  )
);

/**
 * Function to generate consolidated extensions calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function consolidated_extension_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "consolidated_extension_report_start"))
  {
    $parameters = framework_call ( "consolidated_extension_report_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! preg_match ( "/^\d{2}\/\d{4}$/", $parameters["Month"]) || ( (int) substr ( $parameters["Month"], 0, 2) < 1 && (int) substr ( $parameters["Month"], 0, 2) > 12))
  {
    $data["Month"] = __ ( "Requested month date is invalid.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "consolidated_extension_report_validate"))
  {
    $data = framework_call ( "consolidated_extension_report_validate", $parameters);
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
  if ( empty ( $parameters["Month"]))
  {
    $base = time ();
  } else {
    $base = mktime ( 0, 0, 0, substr ( $parameters["Month"], 0, strpos ( $parameters["Month"], "/")), 1, substr ( $parameters["Month"], strpos ( $parameters["Month"], "/") + 1));
  }
  $parameters["Start"] = date ( "Y-m-01", $base) . " 00:00";
  $parameters["End"] = date ( "Y-m-t", $base) . " 23:59";

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "consolidated_extension_report_sanitize"))
  {
    $parameters = framework_call ( "consolidated_extension_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "consolidated_extension_report_pre"))
  {
    $parameters = framework_call ( "consolidated_extension_report_pre", $parameters, false, $parameters);
  }

  /**
   * Generate report from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Number`, `Description`, `Type` FROM `Extensions`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    return $buffer;
  }
  $data = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    /**
     * Get all calls from extension for the requested period
     */
    if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' AND `srcid` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $matrix = array (
      "Local" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "Interstate" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "International" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "Others" => array ( "Total" => 0, "Time" => 0, "Cost" => 0)
    );
    while ( $call = $records->fetch_assoc ())
    {
      if ( $call["calltype"] & VD_CALLTYPE_LOCAL)
      {
        $type = "Local";
      } elseif ( $call["calltype"] & VD_CALLTYPE_INTERSTATE)
      {
        $type = "Interstate";
      } elseif ( $call["calltype"] & VD_CALLTYPE_INTERNATIONAL)
      {
        $type = "International";
      } else {
        $type = "Others";
      }
      $matrix[$type]["Total"]++;
      $matrix[$type]["Time"] += $call["billsec"];
      $matrix[$type]["Cost"] += $call["value"];
    }
    $data[] = api_filter_entry ( array ( "ID", "Number", "Description", "Type", "Local", "Interstate", "International", "Others"), array_merge ( array ( "ID" => $extension["ID"], "Number" => $extension["Number"], "Description" => $extension["Description"], "Type" => "extension_" . $extension["Type"]), $matrix));
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "consolidated_extension_report_post"))
  {
    $data = framework_call ( "consolidated_extension_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "consolidated_extension_report_finish"))
  {
    framework_call ( "consolidated_extension_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate consolidated groups call's report
 */
framework_add_hook (
  "consolidated_group_report",
  "consolidated_group_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Month" => array (
          "type" => "string",
          "description" => __ ( "The month and year to generate consolidate report."),
          "pattern" => "^\d{2}\/\d{4}\$",
          "required" => true,
          "example" => "05/2020"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing report extensions."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "Group unique system internal identification."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "Group description."),
                "example" => __ ( "IT Team")
              ),
              "Local" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Local"
                ),
                "description" => __ ( "Local calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "Interstate" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Interstate"
                ),
                "description" => __ ( "Interstate calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "International" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "International"
                ),
                "description" => __ ( "International calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "Others" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Others"
                ),
                "description" => __ ( "Others calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
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
            "Month" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Requested month date is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "consolidated_group_report", __ ( "Consolidated groups calls report"));
framework_add_api_call (
  "/reports/consolidated/groups",
  "Read",
  "consolidated_group_report",
  array (
    "permissions" => array ( "user", "consolidated_group_report"),
    "title" => __ ( "Consolidated groups calls report"),
    "description" => __ ( "Generate a consolidated calls report grouped by groups.")
  )
);

/**
 * Function to generate consolidated groups calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function consolidated_group_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "consolidated_group_report_start"))
  {
    $parameters = framework_call ( "consolidated_group_report_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! preg_match ( "/^\d{2}\/\d{4}$/", $parameters["Month"]) || ( (int) substr ( $parameters["Month"], 0, 2) < 1 && (int) substr ( $parameters["Month"], 0, 2) > 12))
  {
    $data["Month"] = __ ( "Requested month date is invalid.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "consolidated_group_report_validate"))
  {
    $data = framework_call ( "consolidated_group_report_validate", $parameters);
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
  if ( empty ( $parameters["Month"]))
  {
    $base = time ();
  } else {
    $base = mktime ( 0, 0, 0, substr ( $parameters["Month"], 0, strpos ( $parameters["Month"], "/")), 1, substr ( $parameters["Month"], strpos ( $parameters["Month"], "/") + 1));
  }
  $parameters["Start"] = date ( "Y-m-01", $base) . " 00:00";
  $parameters["End"] = date ( "Y-m-t", $base) . " 23:59";

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "consolidated_group_report_sanitize"))
  {
    $parameters = framework_call ( "consolidated_group_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "consolidated_group_report_pre"))
  {
    $parameters = framework_call ( "consolidated_group_report_pre", $parameters, false, $parameters);
  }

  /**
   * Generate report from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Groups`.`ID`, `Groups`.`Description`, GROUP_CONCAT(`ExtensionPhone`.`Extension` SEPARATOR ',') AS `Extensions` FROM `Groups` LEFT JOIN `ExtensionPhone` ON `Groups`.`ID` = `ExtensionPhone`.`Group` GROUP BY `Groups`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    return $buffer;
  }
  $data = array ();
  while ( $group = $result->fetch_assoc ())
  {
    /**
     * Get all calls from extension for the requested period
     */
    if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' AND `srcid` IN ('" . $_in["mysql"]["id"]->real_escape_string ( $group["Extensions"]) . "')"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $matrix = array (
      "Local" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "Interstate" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "International" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "Others" => array ( "Total" => 0, "Time" => 0, "Cost" => 0)
    );
    while ( $call = $records->fetch_assoc ())
    {
      if ( $call["calltype"] & VD_CALLTYPE_LOCAL)
      {
        $type = "Local";
      } elseif ( $call["calltype"] & VD_CALLTYPE_INTERSTATE)
      {
        $type = "Interstate";
      } elseif ( $call["calltype"] & VD_CALLTYPE_INTERNATIONAL)
      {
        $type = "International";
      } else {
        $type = "Others";
      }
      $matrix[$type]["Total"]++;
      $matrix[$type]["Time"] += $call["billsec"];
      $matrix[$type]["Cost"] += $call["value"];
    }
    $data[] = api_filter_entry ( array ( "ID", "Description", "Local", "Interstate", "International", "Others"), array_merge ( array ( "ID" => $group["ID"], "Description" => $group["Description"]), $matrix));
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "consolidated_group_report_post"))
  {
    $data = framework_call ( "consolidated_group_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "consolidated_group_report_finish"))
  {
    framework_call ( "consolidated_group_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate consolidated gateways call's report
 */
framework_add_hook (
  "consolidated_gateway_report",
  "consolidated_gateway_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Month" => array (
          "type" => "string",
          "description" => __ ( "The month and year to generate consolidate report."),
          "pattern" => "^\d{2}\/\d{4}\$",
          "required" => true,
          "example" => "05/2020"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing report extensions."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "Gateway unique system internal identification."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "Gateway description."),
                "example" => __ ( "My SIP Provider")
              ),
              "Local" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Local"
                ),
                "description" => __ ( "Local calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "Interstate" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Interstate"
                ),
                "description" => __ ( "Interstate calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "International" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "International"
                ),
                "description" => __ ( "International calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "Others" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Others"
                ),
                "description" => __ ( "Others calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
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
            "Month" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Requested month date is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "consolidated_gateway_report", __ ( "Consolidated gateways calls report"));
framework_add_api_call (
  "/reports/consolidated/gateways",
  "Read",
  "consolidated_gateway_report",
  array (
    "permissions" => array ( "user", "consolidated_gateway_report"),
    "title" => __ ( "Consolidated gateways calls report"),
    "description" => __ ( "Generate a consolidated calls report grouped by gateways.")
  )
);

/**
 * Function to generate consolidated gateways calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function consolidated_gateway_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "consolidated_gateway_report_start"))
  {
    $parameters = framework_call ( "consolidated_gateway_report_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! preg_match ( "/^\d{2}\/\d{4}$/", $parameters["Month"]) || ( (int) substr ( $parameters["Month"], 0, 2) < 1 && (int) substr ( $parameters["Month"], 0, 2) > 12))
  {
    $data["Month"] = __ ( "Requested month date is invalid.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "consolidated_gateway_report_validate"))
  {
    $data = framework_call ( "consolidated_gateway_report_validate", $parameters);
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
  if ( empty ( $parameters["Month"]))
  {
    $base = time ();
  } else {
    $base = mktime ( 0, 0, 0, substr ( $parameters["Month"], 0, strpos ( $parameters["Month"], "/")), 1, substr ( $parameters["Month"], strpos ( $parameters["Month"], "/") + 1));
  }
  $parameters["Start"] = date ( "Y-m-01", $base) . " 00:00";
  $parameters["End"] = date ( "Y-m-t", $base) . " 23:59";

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "consolidated_gateway_report_sanitize"))
  {
    $parameters = framework_call ( "consolidated_gateway_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "consolidated_gateway_report_pre"))
  {
    $parameters = framework_call ( "consolidated_gateway_report_pre", $parameters, false, $parameters);
  }

  /**
   * Generate report from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Description` FROM `Gateways`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    return $buffer;
  }
  $data = array ();
  while ( $gateway = $result->fetch_assoc ())
  {
    /**
     * Get all calls made from this gateway for the requested period
     */
    if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' AND `gateway` = " . $_in["mysql"]["id"]->real_escape_string ( $gateway["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $matrix = array (
      "Local" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "Interstate" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "International" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "Others" => array ( "Total" => 0, "Time" => 0, "Cost" => 0)
    );
    while ( $call = $records->fetch_assoc ())
    {
      if ( $call["calltype"] & VD_CALLTYPE_LOCAL)
      {
        $type = "Local";
      } elseif ( $call["calltype"] & VD_CALLTYPE_INTERSTATE)
      {
        $type = "Interstate";
      } elseif ( $call["calltype"] & VD_CALLTYPE_INTERNATIONAL)
      {
        $type = "International";
      } else {
        $type = "Others";
      }
      $matrix[$type]["Total"]++;
      $matrix[$type]["Time"] += $call["billsec"];
      $matrix[$type]["Cost"] += $call["value"];
    }
    $data[] = api_filter_entry ( array ( "ID", "Description", "Local", "Interstate", "International", "Others"), array_merge ( array ( "ID" => $gateway["ID"], "Description" => $gateway["Description"]), $matrix));
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "consolidated_gateway_report_post"))
  {
    $data = framework_call ( "consolidated_gateway_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "consolidated_gateway_report_finish"))
  {
    framework_call ( "consolidated_gateway_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to generate consolidated servers call's report
 */
framework_add_hook (
  "consolidated_server_report",
  "consolidated_server_report",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Month" => array (
          "type" => "string",
          "description" => __ ( "The month and year to generate consolidate report."),
          "pattern" => "^\d{2}\/\d{4}\$",
          "required" => true,
          "example" => "05/2020"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing report extensions."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "extension"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "Server unique system internal identification."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "Server description."),
                "example" => __ ( "Main server")
              ),
              "Local" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Local"
                ),
                "description" => __ ( "Local calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "Interstate" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Interstate"
                ),
                "description" => __ ( "Interstate calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "International" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "International"
                ),
                "description" => __ ( "International calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
                  )
                )
              ),
              "Others" => array (
                "type" => "object",
                "xml" => array (
                  "name" => "Others"
                ),
                "description" => __ ( "Others calls made by extension."),
                "properties" => array (
                  "Total" => array (
                    "type" => "integer",
                    "description" => __ ( "Total of calls."),
                    "example" => 15
                  ),
                  "Time" => array (
                    "type" => "integer",
                    "description" => __ ( "Total duration of calls in seconds."),
                    "example" => 731
                  ),
                  "Cost" => array (
                    "type" => "float",
                    "description" => __ ( "Total cost of calls."),
                    "example" => 21.32
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
            "Month" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Requested month date is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "consolidated_server_report", __ ( "Consolidated servers calls report"));
framework_add_api_call (
  "/reports/consolidated/servers",
  "Read",
  "consolidated_server_report",
  array (
    "permissions" => array ( "user", "consolidated_server_report"),
    "title" => __ ( "Consolidated server calls report"),
    "description" => __ ( "Generate a consolidated calls report grouped by servers.")
  )
);

/**
 * Function to generate consolidated servers calls report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function consolidated_server_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "consolidated_server_report_start"))
  {
    $parameters = framework_call ( "consolidated_server_report_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! preg_match ( "/^\d{2}\/\d{4}$/", $parameters["Month"]) || ( (int) substr ( $parameters["Month"], 0, 2) < 1 && (int) substr ( $parameters["Month"], 0, 2) > 12))
  {
    $data["Month"] = __ ( "Requested month date is invalid.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "consolidated_server_report_validate"))
  {
    $data = framework_call ( "consolidated_server_report_validate", $parameters);
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
  if ( empty ( $parameters["Month"]))
  {
    $base = time ();
  } else {
    $base = mktime ( 0, 0, 0, substr ( $parameters["Month"], 0, strpos ( $parameters["Month"], "/")), 1, substr ( $parameters["Month"], strpos ( $parameters["Month"], "/") + 1));
  }
  $parameters["Start"] = date ( "Y-m-01", $base) . " 00:00";
  $parameters["End"] = date ( "Y-m-t", $base) . " 23:59";

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "consolidated_server_report_sanitize"))
  {
    $parameters = framework_call ( "consolidated_server_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "consolidated_server_report_pre"))
  {
    $parameters = framework_call ( "consolidated_server_report_pre", $parameters, false, $parameters);
  }

  /**
   * Generate report from database
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Description` FROM `Servers`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    return $buffer;
  }
  $data = array ();
  while ( $server = $result->fetch_assoc ())
  {
    /**
     * Get all calls from extension for the requested period
     */
    if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' AND `server` = " . $_in["mysql"]["id"]->real_escape_string ( $server["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $matrix = array (
      "Local" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "Interstate" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "International" => array ( "Total" => 0, "Time" => 0, "Cost" => 0),
      "Others" => array ( "Total" => 0, "Time" => 0, "Cost" => 0)
    );
    while ( $call = $records->fetch_assoc ())
    {
      if ( $call["calltype"] & VD_CALLTYPE_LOCAL)
      {
        $type = "Local";
      } elseif ( $call["calltype"] & VD_CALLTYPE_INTERSTATE)
      {
        $type = "Interstate";
      } elseif ( $call["calltype"] & VD_CALLTYPE_INTERNATIONAL)
      {
        $type = "International";
      } else {
        $type = "Others";
      }
      $matrix[$type]["Total"]++;
      $matrix[$type]["Time"] += $call["billsec"];
      $matrix[$type]["Cost"] += $call["value"];
    }
    $data[] = api_filter_entry ( array ( "ID", "Description", "Local", "Interstate", "International", "Others"), array_merge ( array ( "ID" => $server["ID"], "Description" => $server["Description"]), $matrix));
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "consolidated_server_report_post"))
  {
    $data = framework_call ( "consolidated_server_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "consolidated_server_report_finish"))
  {
    framework_call ( "consolidated_server_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
