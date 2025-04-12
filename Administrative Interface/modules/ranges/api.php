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
 * VoIP Domain ranges module API. This module add the API calls related to
 * ranges.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Ranges
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search ranges
 */
framework_add_hook (
  "ranges_search",
  "ranges_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all ranges."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Start,Finish,Server,Extensions",
          "example" => "Description,Start,Finish"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system ranges."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "range"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the ranges."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the range."),
                "example" => __ ( "Headquarter")
              ),
              "Start" => array (
                "type" => "integer",
                "description" => __ ( "The start number of range."),
                "example" => 1000
              ),
              "Finish" => array (
                "type" => "integer",
                "description" => __ ( "The finish number of range."),
                "example" => 3999
              ),
              "Server" => array (
                "type" => "string",
                "description" => __ ( "The name of the server where this range is allocated."),
                "example" => __ ( "Main server")
              ),
              "Extensions" => array (
                "type" => "integer",
                "description" => __ ( "The number of extensions using this range."),
                "example" => 728
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
framework_add_permission ( "ranges_search", __ ( "Search ranges"));
framework_add_api_call (
  "/ranges",
  "Read",
  "ranges_search",
  array (
    "permissions" => array ( "user", "ranges_search"),
    "title" => __ ( "Search ranges"),
    "description" => __ ( "Search for system ranges.")
  )
);

/**
 * Function to search ranges.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ranges_search_start"))
  {
    $parameters = framework_call ( "ranges_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Ranges", "Servers"));

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ranges_search_validate"))
  {
    $data = framework_call ( "ranges_search_validate", $parameters);
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
  if ( framework_has_hook ( "ranges_search_sanitize"))
  {
    $parameters = framework_call ( "ranges_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ranges_search_pre"))
  {
    $parameters = framework_call ( "ranges_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search ranges
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Ranges`.`ID`, `Ranges`.`Description`, `Ranges`.`Start`, `Ranges`.`Finish`, `Servers`.`Description` AS `Server` FROM `Ranges` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Ranges`.`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " ORDER BY `Description`, `Start`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Start,Finish,Server,Extensions", "ID,Description,Start,Finish,Server,Extensions");
  while ( $result = $results->fetch_assoc ())
  {
    if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `Extensions` WHERE `Range` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $result["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $result["Extensions"] = intval ( $count->fetch_assoc ()["Total"]);
    $count->free ();
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ranges_search_post"))
  {
    $data = framework_call ( "ranges_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ranges_search_finish"))
  {
    framework_call ( "ranges_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get range information
 */
framework_add_hook (
  "ranges_view",
  "ranges_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the system range."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "integer",
              "description" => __ ( "The internal unique identification number of the range."),
              "example" => 1
            ),
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the range."),
              "example" => __ ( "Headquarter")
            ),
            "Server" => array (
              "type" => "integer",
              "description" => __ ( "The system server unique identifier where this range is allocated to."),
              "example" => 2
            ),
            "ServerDescription" => array (
              "type" => "string",
              "description" => __ ( "The system server name where this range is allocated to."),
              "example" => __ ( "Main server")
            ),
            "Start" => array (
              "type" => "integer",
              "description" => __ ( "The start allocation number for this range."),
              "example" => 1000
            ),
            "Finish" => array (
              "type" => "integer",
              "description" => __ ( "The finish allocation number for this range."),
              "example" => 1000
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
              "example" => __ ( "Invalid range ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "ranges_view", __ ( "View ranges information"));
framework_add_api_call (
  "/ranges/:ID",
  "Read",
  "ranges_view",
  array (
    "permissions" => array ( "user", "ranges_view"),
    "title" => __ ( "View ranges"),
    "description" => __ ( "Get a system range information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The system range internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate range information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ranges_view_start"))
  {
    $parameters = framework_call ( "ranges_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Ranges", "Servers"));

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid range ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ranges_view_validate"))
  {
    $data = framework_call ( "ranges_view_validate", $parameters);
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
  if ( framework_has_hook ( "ranges_view_sanitize"))
  {
    $parameters = framework_call ( "ranges_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ranges_view_pre"))
  {
    $parameters = framework_call ( "ranges_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search ranges
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Ranges`.*, `Servers`.`Description` AS `ServerDescription` FROM `Ranges` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID` WHERE `Ranges`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $range = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = api_filter_entry ( array ( "ID", "Description", "Server", "ServerDescription", "Start", "Finish"), $range);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ranges_view_post"))
  {
    $data = framework_call ( "ranges_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ranges_view_finish"))
  {
    framework_call ( "ranges_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new range
 */
framework_add_hook (
  "ranges_add",
  "ranges_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the range."),
          "required" => true,
          "example" => __ ( "Headquarter")
        ),
        "Server" => array (
          "type" => "integer",
          "description" => __ ( "The system server unique identifier where the range will be allocated."),
          "required" => true,
          "example" => 1
        ),
        "Start" => array (
          "type" => "integer",
          "description" => __ ( "The start allocation number of the range."),
          "required" => true,
          "example" => 1000
        ),
        "Finish" => array (
          "type" => "integer",
          "description" => __ ( "The finish allocation number of the range."),
          "required" => true,
          "example" => 1999
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system range was added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The range description is required.")
            ),
            "Server" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The server is required.")
            ),
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The range start is greater than finish.")
            ),
            "Finish" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The finish is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "ranges_add", __ ( "Add ranges"));
framework_add_api_call (
  "/ranges",
  "Create",
  "ranges_add",
  array (
    "permissions" => array ( "user", "ranges_add"),
    "title" => __ ( "Add ranges"),
    "description" => __ ( "Add a new system range.")
  )
);

/**
 * Function to add a new range.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "blocks_add_start"))
  {
    $parameters = framework_call ( "blocks_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The range description is required.");
  }
  if ( empty ( $parameters["Server"]))
  {
    $data["Server"] = __ ( "The server is required.");
  }
  if ( $parameters["Start"] != (int) $parameters["Start"])
  {
    $data["Start"] = __ ( "The start is invalid.");
  }
  if ( $parameters["Finish"] != (int) $parameters["Finish"])
  {
    $data["Finish"] = __ ( "The finish is invalid.");
  }
  if ( ! array_key_exists ( "Start", $data) && ! array_key_exists ( "Finish", $data) && $parameters["Start"] > $parameters["Finish"])
  {
    $data["Start"] = __ ( "The range start is greater than finish.");
  }

  /**
   * Search server
   */
  if ( ! array_key_exists ( "Server", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Server"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $data["Server"] = __ ( "The selected server is invalid.");
    }
    $server = $result->fetch_assoc ();
  }

  /**
   * Check if range didn't overlap other ranges
   */
  if ( ! array_key_exists ( "Start", $data) && ! array_key_exists ( "Finish", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Finish"]) . " >= `Start` AND `Finish` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Start"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Start"] = __ ( "Values override existing range.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "blocks_add_validate"))
  {
    $data = framework_call ( "blocks_add_validate", $parameters, false, $data);
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
  $parameters["Server"] = (int) $parameters["Server"];
  $parameters["Start"] = (int) $parameters["Start"];
  $parameters["Finish"] = (int) $parameters["Finish"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "blocks_add_sanitize"))
  {
    $parameters = framework_call ( "blocks_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "blocks_add_pre"))
  {
    $parameters = framework_call ( "blocks_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new range record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Ranges` (`Description`, `Server`, `Start`, `Finish`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Server"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Finish"]) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "blocks_add_post"))
  {
    framework_call ( "blocks_add_post", $parameters);
  }

  /**
   * Add new range at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["ID"], "Server" => $parameters["Server"], "Start" => $parameters["Start"], "Finish" => $parameters["Finish"]);
  if ( framework_has_hook ( "ranges_add_notify"))
  {
    $notify = framework_call ( "ranges_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "range_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "blocks_add_finish"))
  {
    framework_call ( "blocks_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/ranges/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing range
 */
framework_add_hook (
  "ranges_edit",
  "ranges_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the range."),
          "required" => true,
          "example" => __ ( "Headquarter")
        ),
        "Server" => array (
          "type" => "integer",
          "description" => __ ( "The system server unique identifier where the range will be allocated."),
          "required" => true,
          "example" => 1
        ),
        "Start" => array (
          "type" => "integer",
          "description" => __ ( "The start allocation number of the range."),
          "required" => true,
          "example" => 1000
        ),
        "Finish" => array (
          "type" => "integer",
          "description" => __ ( "The finish allocation number of the range."),
          "required" => true,
          "example" => 1999
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system range was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The range description is required.")
            ),
            "Server" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The server is required.")
            ),
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The range start is greater than finish.")
            ),
            "Finish" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The finish is invalid.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "ranges_edit", __ ( "Edit ranges"));
framework_add_api_call (
  "/ranges/:ID",
  "Modify",
  "ranges_edit",
  array (
    "permissions" => array ( "user", "ranges_edit"),
    "title" => __ ( "Edit ranges"),
    "description" => __ ( "Change a system range information.")
  )
);
framework_add_api_call (
  "/ranges/:ID",
  "Edit",
  "ranges_edit",
  array (
    "permissions" => array ( "user", "ranges_edit"),
    "title" => __ ( "Edit ranges"),
    "description" => __ ( "Change a system range information.")
  )
);

/**
 * Function to edit an existing range.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ranges_edit_start"))
  {
    $parameters = framework_call ( "ranges_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The range description is required.");
  }
  if ( empty ( $parameters["Server"]))
  {
    $data["Server"] = __ ( "The server is required.");
  }
  if ( $parameters["Start"] != (int) $parameters["Start"])
  {
    $data["Start"] = __ ( "The start is invalid.");
  }
  if ( $parameters["Finish"] != (int) $parameters["Finish"])
  {
    $data["Finish"] = __ ( "The finish is invalid.");
  }
  if ( ! array_key_exists ( "Start", $data) && ! array_key_exists ( "Finish", $data) && $parameters["Start"] > $parameters["Finish"])
  {
    $data["Start"] = __ ( "The range start is greater than finish.");
  }

  /**
   * Search server
   */
  if ( ! array_key_exists ( "Server", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Server"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["Server"] = __ ( "The selected server is invalid.");
    }
    $server = $result->fetch_assoc ();
  }

  /**
   * Check if range exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
   * Check if range didn't overlap other ranges
   */
  if ( ! array_key_exists ( "Start", $data) && ! array_key_exists ( "Finish", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Finish"]) . " >= `Start` AND `Finish` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Start"]) . " AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["Start"] = __ ( "Values override existing range.");
    }
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ranges_edit_validate"))
  {
    $data = framework_call ( "ranges_edit_validate", $parameters, false, $data);
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
  $parameters["Server"] = (int) $parameters["Server"];
  $parameters["Start"] = (int) $parameters["Start"];
  $parameters["Finish"] = (int) $parameters["Finish"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "ranges_edit_sanitize"))
  {
    $parameters = framework_call ( "ranges_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ranges_edit_pre"))
  {
    $parameters = framework_call ( "ranges_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change range record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Ranges` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Server"]) . ", `Start` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . ", `Finish` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Finish"]) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ranges_edit_post"))
  {
    framework_call ( "ranges_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  if ( $parameters["ORIGINAL"]["Server"] != $parameters["Server"] || $parameters["ORIGINAL"]["Start"] != $parameters["Start"] || $parameters["ORIGINAL"]["Finish"] != $parameters["Finish"])
  {
    $notify = array ( "ID" => $parameters["ID"], "Server" => $parameters["Server"], "Start" => $parameters["Start"], "Finish" => $parameters["Finish"]);
    if ( framework_has_hook ( "ranges_edit_notify"))
    {
      $notify = framework_call ( "ranges_edit_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "range_change", $notify);
  }

  /**
   * Call range server change hook if needed
   */
  if ( $parameters["ORIGINAL"]["Server"] != $parameters["Server"] && framework_has_hook ( "ranges_server_changed"))
  {
    framework_call ( "ranges_server_changed", array ( "ID" => $parameters["ORIGINAL"]["ID"], "Old" => $parameters["ORIGINAL"]["Server"], "New" => $parameters["Server"]));
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ranges_edit_finish"))
  {
    framework_call ( "ranges_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a range
 */
framework_add_hook (
  "ranges_remove",
  "ranges_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system range was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid range ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "ranges_remove", __ ( "Remove ranges"));
framework_add_api_call (
  "/ranges/:ID",
  "Delete",
  "ranges_remove",
  array (
    "permissions" => array ( "user", "ranges_remove"),
    "title" => __ ( "Remove ranges"),
    "description" => __ ( "Remove a system range.")
  )
);

/**
 * Function to remove an existing range.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "ranges_remove_start"))
  {
    $parameters = framework_call ( "ranges_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid range ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "ranges_remove_validate"))
  {
    $data = framework_call ( "ranges_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "ranges_remove_sanitize"))
  {
    $parameters = framework_call ( "ranges_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if range exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
   * Check if range has any allocated number
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "SELECT COUNT(*) AS `Total` FROM `Extensions` WHERE `Range` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $total = intval ( $count->fetch_assoc ()["Total"]);
  $count->free ();
  if ( $total != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "ranges_remove_pre"))
  {
    $parameters = framework_call ( "ranges_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove range database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Ranges` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "ranges_remove_post"))
  {
    framework_call ( "ranges_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["ID"]);
  if ( framework_has_hook ( "ranges_remove_notify"))
  {
    $notify = framework_call ( "ranges_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "range_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "ranges_remove_finish"))
  {
    framework_call ( "ranges_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "ranges_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "ranges_server_reconfig");

/**
 * Function to notify server to include all ranges.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all ranges and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE `Server` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $range = $result->fetch_assoc ())
  {
    $notify = array ( "ID" => $range["ID"], "Server" => $range["Server"], "Start" => $range["Start"], "Finish" => $range["Finish"]);
    if ( framework_has_hook ( "ranges_add_notify"))
    {
      $notify = framework_call ( "ranges_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "range_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
