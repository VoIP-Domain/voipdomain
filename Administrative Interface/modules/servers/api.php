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
 * VoIP Domain servers module API. This module add the API calls related to
 * servers.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Servers
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search servers
 */
framework_add_hook (
  "servers_search",
  "servers_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all servers."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ID,Description,Address,Port,Backups,Extensions",
          "example" => "Description,Address,Port"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the system servers."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "server"
            ),
            "properties" => array (
              "ID" => array (
                "type" => "integer",
                "description" => __ ( "The internal unique identification number of the server."),
                "example" => 1
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the server."),
                "example" => __ ( "Main server")
              ),
              "Address" => array (
                "type" => "string",
                "description" => __ ( "The IP address of the server."),
                "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
                "example" => "192.168.0.1"
              ),
              "Port" => array (
                "type" => "integer",
                "description" => __ ( "The IP port of the server."),
                "minimum" => 0,
                "maximum" => 65535,
                "example" => 5060
              ),
              "Backups" => array (
                "type" => "array",
                "xml" => array (
                  "name" => "Backups",
                  "wrapped" => true
                ),
                "description" => __ ( "An array with backup servers information, if available."),
                "items" => array (
                  "type" => "object",
                  "xml" => array (
                    "name" => "server"
                  ),
                  "properties" => array (
                    "Server" => array (
                      "type" => "object",
                      "properties" => array (
                        "Description" => array (
                          "type" => "string",
                          "description" => __ ( "The description of the backup server."),
                          "example" => __ ( "Main server backup")
                        ),
                        "Address" => array (
                          "type" => "string",
                          "description" => __ ( "The IP address of the backup server."),
                          "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
                          "example" => "192.168.0.2"
                        ),
                        "Port" => array (
                          "type" => "integer",
                          "description" => __ ( "The IP port of the backup server."),
                          "minimum" => 0,
                          "maximum" => 65535,
                          "example" => 5060
                        )
                      )
                    )
                  )
                )
              ),
              "Extensions" => array (
                "type" => "integer",
                "description" => __ ( "The number of extensions allocated to the server."),
                "example" => 329
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
framework_add_permission ( "servers_search", __ ( "Search servers"));
framework_add_api_call (
  "/servers",
  "Read",
  "servers_search",
  array (
    "permissions" => array ( "user", "servers_search"),
    "title" => __ ( "Search servers"),
    "description" => __ ( "Search for system servers.")
  )
);

/**
 * Function to search servers.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "servers_search_start"))
  {
    $parameters = framework_call ( "servers_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Servers", "Ranges", "Extensions"));

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "servers_search_validate"))
  {
    $data = framework_call ( "servers_search_validate", $parameters);
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
  if ( framework_has_hook ( "servers_search_sanitize"))
  {
    $parameters = framework_call ( "servers_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "servers_search_pre"))
  {
    $parameters = framework_call ( "servers_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search servers
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Servers`.`ID`, `Servers`.`Description`, `Servers`.`Address`, `Servers`.`Port`, COUNT(`Extensions`.`Range`) AS `Extensions` FROM `Servers` LEFT JOIN `Ranges` ON `Ranges`.`Server` = `Servers`.`ID` LEFT JOIN `Extensions` ON `Extensions`.`Range` = `Ranges`.`ID`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Servers`.`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%'" : "") . " GROUP BY `Servers`.`ID` ORDER BY `Description`, `Address`, `Port`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ID,Description,Address,Port,Extensions", "ID,Description,Address,Port,Backups,Extensions");
  while ( $result = $results->fetch_assoc ())
  {
    /**
     * Fetch server backups
     */
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT `Description`, `Address`, `Port` FROM `ServerBackup` WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $result["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $result["Backups"] = array ();
    while ( $backup = $result2->fetch_assoc ())
    {
      $result["Backups"][] = $backup;
    }
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "servers_search_post"))
  {
    $data = framework_call ( "servers_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "servers_search_finish"))
  {
    framework_call ( "servers_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get server information
 */
framework_add_hook (
  "servers_view",
  "servers_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the server."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The description of the server."),
              "example" => __ ( "Main server")
            ),
            "Address" => array (
              "type" => "string",
              "description" => __ ( "The IP address of the server."),
              "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
              "example" => "192.168.0.1"
            ),
            "Port" => array (
              "type" => "integer",
              "description" => __ ( "The IP port of the server."),
              "minimum" => 0,
              "maximum" => 65535,
              "example" => 5060
            ),
            "NTP" => array (
              "type" => "array",
              "description" => __ ( "An array with NTP servers information, if available."),
              "items" => array (
                "type" => "object",
                "properties" => array (
                  "Server" => array (
                    "type" => "string",
                    "description" => __ ( "The NTP server address of the server."),
                    "example" => "0.pool.ntp.org"
                  )
                )
              )
            ),
            "Backups" => array (
              "type" => "array",
              "description" => __ ( "An array with backup servers information, if available."),
              "items" => array (
                "type" => "object",
                "properties" => array (
                  "Server" => array (
                    "type" => "object",
                    "properties" => array (
                      "Description" => array (
                        "type" => "string",
                        "description" => __ ( "The description of the backup server."),
                        "example" => __ ( "Main server backup")
                      ),
                      "Address" => array (
                        "type" => "string",
                        "description" => __ ( "The IP address of the backup server."),
                        "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
                        "example" => "192.168.0.2"
                      ),
                      "Port" => array (
                        "type" => "integer",
                        "description" => __ ( "The IP port of the backup server."),
                        "minimum" => 0,
                        "maximum" => 65535,
                        "example" => 5060
                      )
                    )
                  )
                )
              )
            ),
            "Window" => array (
              "type" => "string",
              "description" => __ ( "If the audio file transfer has time window limit."),
              "example" => true
            ),
            "Start" => array (
              "type" => "time",
              "description" => __ ( "If transfer window are enabled, the start time of the window."),
              "pattern" => "/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/",
              "example" => "00:00"
            ),
            "Finish" => array (
              "type" => "time",
              "description" => __ ( "If transfer window are enabled, the finish time of the window."),
              "pattern" => "/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/",
              "example" => "06:00"
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
              "example" => __ ( "Invalid server ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "servers_view", __ ( "View servers information"));
framework_add_api_call (
  "/servers/:ID",
  "Read",
  "servers_view",
  array (
    "permissions" => array ( "user", "servers_view"),
    "title" => __ ( "View servers"),
    "description" => __ ( "Get a system server information."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The server internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate server information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "servers_view_start"))
  {
    $parameters = framework_call ( "servers_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Servers", "Countries", "Gateways"));

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid server ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "servers_view_validate"))
  {
    $data = framework_call ( "servers_view_validate", $parameters);
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
  if ( framework_has_hook ( "servers_view_sanitize"))
  {
    $parameters = framework_call ( "servers_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "servers_view_pre"))
  {
    $parameters = framework_call ( "servers_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search servers
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $server = $result->fetch_assoc ();

  /**
   * Get server backup servers list
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Description`, `Address`, `Port` FROM `ServerBackup` WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $backups = array ();
  while ( $backup = $result->fetch_assoc ())
  {
    $backups[] = $backup;
  }

  /**
   * Format data
   */
  $server["Backups"] = $backups;
  $server["Window"] = $server["TransfStart"] != NULL;
  $server["Start"] = $server["TransfStart"];
  $server["Finish"] = $server["TransfEnd"];
  $server["NTP"] = json_decode ( $server["NTP"], true);
  $data = api_filter_entry ( array ( "Description", "Address", "Port", "NTP", "Backups", "Window", "Start", "Finish"), $server);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "servers_view_post"))
  {
    $data = framework_call ( "servers_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "servers_view_finish"))
  {
    framework_call ( "servers_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new server
 */
framework_add_hook (
  "servers_add",
  "servers_add",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the system server."),
          "required" => true,
          "example" => __ ( "Main server")
        ),
        "Address" => array (
          "type" => "string",
          "description" => __ ( "The IP address of the server."),
          "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
          "required" => true,
          "example" => "192.168.0.1"
        ),
        "Port" => array (
          "type" => "integer",
          "description" => __ ( "The IP port of the server."),
          "minimum" => 0,
          "maximum" => 65535,
          "required" => true,
          "example" => 5060
        ),
        "NTP" => array (
          "type" => "array",
          "description" => __ ( "An array with NTP servers information, if available."),
          "required" => false,
          "xml" => array (
            "name" => "NTP",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "string",
            "description" => __ ( "The NTP server address of the server."),
            "required" => true,
            "example" => "0.pool.ntp.org",
            "xml" => array (
              "name" => "Server"
            )
          )
        ),
        "Backups" => array (
          "type" => "array",
          "description" => __ ( "The list of backup servers for this server."),
          "required" => false,
          "items" => array (
            "type" => "object",
            "properties" => array (
              "Reference" => array (
                "type" => "integer",
                "description" => __ ( "The reference number to backup server. This is used to report any backup server variable error."),
                "required" => true
              ),
              "Description" => array (
                "type" => "string",
                "description" => __ ( "The description of the backup server."),
                "required" => true,
                "example" => __ ( "Main server backup")
              ),
              "Address" => array (
                "type" => "string",
                "description" => __ ( "The IP address of the backup server."),
                "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
                "required" => true,
                "example" => "192.168.0.2"
              ),
              "Port" => array (
                "type" => "integer",
                "description" => __ ( "The IP port of the backup server."),
                "minimum" => 0,
                "maximum" => 65535,
                "required" => true,
                "example" => 5060
              )
            )
          )
        ),
        "Window" => array (
          "type" => "boolean",
          "description" => __ ( "If the file transfer from the server are time windowed."),
          "example" => true
        ),
        "Start" => array (
          "type" => "string",
          "description" => __ ( "If transfer window are enabled, the start time of the window."),
          "pattern" => "/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/",
          "example" => "00:00"
        ),
        "Finish" => array (
          "type" => "string",
          "description" => __ ( "If transfer window are enabled, the finish time of the window."),
          "pattern" => "/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/",
          "example" => "06:00"
        )
      )
    ),
    "response" => array (
      201 => array (
        "description" => __ ( "New system server added sucessfully.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The server description is required.")
            ),
            "Address" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The server address is invalid.")
            ),
            "Port" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The server port is invalid.")
            ),
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The start hour is required when transfer window are enabled.")
            ),
            "Finish" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The finish hour is required when transfer window are enabled.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "servers_add", __ ( "Add servers"));
framework_add_api_call (
  "/servers",
  "Create",
  "servers_add",
  array (
    "permissions" => array ( "user", "servers_add"),
    "title" => __ ( "Add servers"),
    "description" => __ ( "Add a new system server.")
  )
);

/**
 * Function to add a new server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "servers_add_start"))
  {
    $parameters = framework_call ( "servers_add_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The server description is required.");
  }
  $parameters["Address"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Address"])));
  if ( empty ( $parameters["Address"]))
  {
    $data["Address"] = __ ( "The server address is required.");
  }
  if ( ! empty ( $parameters["Address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $parameters["Address"]) && gethostbyname ( $parameters["Address"]) == $parameters["Address"])
  {
    $data["Address"] = __ ( "The server address is invalid.");
  }
  if ( (int) $parameters["Port"] < 0 || (int) $parameters["Port"] >= 65535)
  {
    $data["Port"] = __ ( "The server port is invalid.");
  }
  if ( ! is_array ( $parameters["NTP"]) && ! empty ( $parameters["NTP"]))
  {
    $parameters["NTP"] = array ( $parameters["NTP"]);
  }
  foreach ( $parameters["NTP"] as $index => $ntp)
  {
    $parameters["NTP"][$index] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["NTP"][$index])));
  }

  /**
   * If has backup servers, validate each one
   */
  $backups = array ();
  if ( sizeof ( $parameters["Backups"]) != 0)
  {
    foreach ( $parameters["Backups"] as $backupserver)
    {
      if ( empty ( $backupserver["Description"]) && empty ( $backupserver["Address"]) && empty ( $backupserver["Port"]))
      {
        continue;
      }
      if ( sizeof ( $backups) > 3)
      {
        $data["Backup_Description_" . $backupserver["Reference"]] = __ ( "Backup servers limit exceeded.");
        continue;
      }
      if ( empty ( $backupserver["Description"]) && ( ! empty ( $backupserver["Address"]) || ! empty ( $backupserver["Port"])))
      {
        $data["Backup_Description_" . $backupserver["Reference"]] = __ ( "Empty backup server description.");
      }
      if ( empty ( $backupserver["Address"]) && ! empty ( $backupserver["Description"]))
      {
        $data["Backup_Address_" . $backupserver["Reference"]] = __ ( "The backup server address is required.");
      }
      if ( ! empty ( $backupserver["Address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $backupserver["Address"]) && gethostbyname ( $backupserver["Address"] == $backupserver["Address"]))
      {
        $data["Backup_Address_" . $backupserver["Reference"]] = __ ( "The backup server address is invalid.");
      }
      $backupserver["Port"] = (int) $backupserver["Port"];
      if ( $backupserver["Port"] < 0 || $backupserver["Port"] >= 65535)
      {
        $data["Backup_Port_" . $backupserver["Reference"]] = __ ( "The backup server port is invalid.");
      }
      $backups[] = array ( "Description" => $backupserver["Description"], "Address" => $backupserver["Address"], "Port" => $backupserver["Port"]);
    }
  }

  /**
   * Check if transfer window enabled, and time window provided
   */
  if ( $parameters["Window"] && empty ( $parameters["Start"]))
  {
    $data["Start"] = __ ( "The start hour is required when transfer window are enabled.");
  }
  if ( $parameters["Window"] == "on" && empty ( $parameters["Finish"]))
  {
    $data["Finish"] = __ ( "The finish hour is required when transfer window are enabled.");
  }

  /**
   * Check if server IP and port was not in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `Address` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Address"]) . "' AND `Port` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Port"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Address"] = __ ( "The address and port is already in use by other server.");
  }

   /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "servers_add_validate"))
  {
    $data = framework_call ( "servers_add_validate", $parameters, false, $data);
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
  $parameters["Window"] = (boolean) $parameters["Window"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "servers_add_sanitize"))
  {
    $parameters = framework_call ( "servers_add_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "servers_add_pre"))
  {
    $parameters = framework_call ( "servers_add_pre", $parameters, false, $parameters);
  }

  /**
   * Generate system public/private keys
   */
  $res = openssl_pkey_new ( array ( "digest_alg" => "sha512", "private_key_bits" => 8192, "private_key_type" => OPENSSL_KEYTYPE_RSA));
  openssl_pkey_export ( $res, $privKey);
  $privKey = preg_replace ( "/\n$/m", "", $privKey);
  $pubKey = openssl_pkey_get_details ( $res);
  $pubKey = preg_replace ( "/\n$/m", "", $pubKey["key"]);
  unset ( $res);

  /**
   * Add new server record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Servers` (`Description`, `Address`, `Port`, `NTP`, `TransfStart`, `TransfEnd`, `PublicKey`, `PrivateKey`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Address"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Port"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["NTP"])) . "', " . ( $parameters["Window"] ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . ":00'" : "NULL") . ", " . ( $parameters["Window"] ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Finish"]) . ":59'" : "NULL") . ", '" . $_in["mysql"]["id"]->real_escape_string ( $pubKey) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $privKey) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["ID"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Add backup servers
   */
  if ( sizeof ( $backups) != 0)
  {
    foreach ( $backups as $id => $backupserver)
    {
      /**
       * Generate system public/private keys
       */
      $res = openssl_pkey_new ( array ( "digest_alg" => "sha512", "private_key_bits" => 8192, "private_key_type" => OPENSSL_KEYTYPE_RSA));
      openssl_pkey_export ( $res, $privKey);
      $privKey = preg_replace ( "/\n$/m", "", $privKey);
      $pubKey = openssl_pkey_get_details ( $res);
      $pubKey = preg_replace ( "/\n$/m", "", $pubKey["key"]);
      unset ( $res);

      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ServerBackup` (`Server`, `Description`, `Address`, `Port`, `PublicKey`, `PrivateKey`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $backupserver["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $backupserver["Address"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $backupserver["Port"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $pubKey) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $privKey) . "')"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $backups[$id]["ID"] = $_in["mysql"]["id"]->insert_id;
    }
  }
  $parameters["Backups"] = $backups;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "servers_add_post"))
  {
    framework_call ( "servers_add_post", $parameters);
  }

// **TODO**: Revisar:

// Need to create a Password for each other server, and store it at the database. Also, when creating the server configuration (will run a rebuild), need to sent the password for each other server to create the PJSIP authentication file.
// So, next steps are:
// 1) Create the password for each server except the created one, for sip trunking between the two servers
// 2) Add the password for the server on notify (will need to create a loop changing password for each notified server)
// 3) Add another kind of notification to the created server with the credentials to all other servers... this will create the PJSIP endpoint for each one

// Reference: https://stackoverflow.com/questions/37422603/building-channels-trunks-between-two-asterisk-servers-with-pjsip

  /**
   * Add new server at Asterisk servers
   */
  $notifybackup = array ();
  foreach ( $parameters["Backups"] as $backup)
  {
    $notifybackup[] = array ( "Address" => $backup["Address"], "Port" => $backup["Port"]);
  }
  $notify = array ( "ID" => $parameters["ID"], "Description" => $parameters["Description"], "Address" => $parameters["Address"], "Port" => $parameters["Port"], "Backups" => $notifybackup);
  if ( framework_has_hook ( "servers_add_notify"))
  {
    $notify = framework_call ( "servers_add_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["ID"] * -1, "server_add", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "servers_add_finish"))
  {
    framework_call ( "servers_add_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["api"]["baseurl"] . "/servers/" . $parameters["ID"]);
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing server
 */
framework_add_hook (
  "servers_edit",
  "servers_edit",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Description" => array (
          "type" => "string",
          "description" => __ ( "The description of the system server."),
          "required" => true,
          "example" => __ ( "Main server")
        ),
        "Address" => array (
          "type" => "string",
          "description" => __ ( "The IP address of the server."),
          "pattern" => "/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/",
          "required" => true,
          "example" => "192.168.0.1"
        ),
        "Port" => array (
          "type" => "integer",
          "description" => __ ( "The IP port of the server."),
          "minimum" => 0,
          "maximum" => 65535,
          "required" => true,
          "example" => 5060
        ),
        "NTP" => array (
          "type" => "array",
          "description" => __ ( "An array with NTP servers information, if available."),
          "required" => false,
          "xml" => array (
            "name" => "NTP",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "string",
            "description" => __ ( "The NTP server address of the server."),
            "required" => true,
            "example" => "0.pool.ntp.org",
            "xml" => array (
              "name" => "Server"
            )
          )
        ),
        "Window" => array (
          "type" => "boolean",
          "description" => __ ( "If the file transfer from the server are time windowed."),
          "example" => true
        ),
        "Start" => array (
          "type" => "string",
          "description" => __ ( "If transfer window are enabled, the start time of the window."),
          "pattern" => "/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/",
          "example" => "00:00"
        ),
        "Finish" => array (
          "type" => "string",
          "description" => __ ( "If transfer window are enabled, the finish time of the window."),
          "pattern" => "/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/",
          "example" => "06:00"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system server was sucessfully updated.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Description" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The server description is required.")
            ),
            "Address" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The server address is invalid.")
            ),
            "Port" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The server port is invalid.")
            ),
            "Start" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The start hour is required when transfer window are enabled.")
            ),
            "Finish" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The finish hour is required when transfer window are enabled.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "servers_edit", __ ( "Edit servers"));
framework_add_api_call (
  "/servers/:ID",
  "Modify",
  "servers_edit",
  array (
    "permissions" => array ( "user", "servers_edit"),
    "title" => __ ( "Edit servers"),
    "description" => __ ( "Change a system server information.")
  )
);
framework_add_api_call (
  "/servers/:ID",
  "Edit",
  "servers_edit",
  array (
    "permissions" => array ( "user", "servers_edit"),
    "title" => __ ( "Edit servers"),
    "description" => __ ( "Change a system server information.")
  )
);

/**
 * Function to edit an existing server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "servers_edit_start"))
  {
    $parameters = framework_call ( "servers_edit_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  $parameters["Description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Description"])));
  if ( empty ( $parameters["Description"]))
  {
    $data["Description"] = __ ( "The server description is required.");
  }
  $parameters["Address"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Address"])));
  if ( empty ( $parameters["Address"]))
  {
    $data["Address"] = __ ( "The server address is required.");
  }
  if ( ! empty ( $parameters["Address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $parameters["Address"]) && gethostbyname ( $parameters["Address"]) == $parameters["Address"])
  {
    $data["Address"] = __ ( "The server address is invalid.");
  }
  if ( (int) $parameters["Port"] < 0 || (int) $parameters["Port"] >= 65535)
  {
    $data["Port"] = __ ( "The server port is invalid.");
  }
  if ( ! is_array ( $parameters["NTP"]) && ! empty ( $parameters["NTP"]))
  {
    $parameters["NTP"] = array ( $parameters["NTP"]);
  }
  foreach ( $parameters["NTP"] as $index => $ntp)
  {
    $parameters["NTP"][$index] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["NTP"][$index])));
  }

  /**
   * Check if server exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
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
   * If has backup servers, validate each one
   */
  $backups = array ();
  if ( sizeof ( $parameters["Backups"]) != 0)
  {
    foreach ( $parameters["Backups"] as $backupserver)
    {
      if ( empty ( $backupserver["Description"]) && empty ( $backupserver["Address"]) && empty ( $backupserver["Port"]))
      {
        continue;
      }
      if ( sizeof ( $backups) > 3)
      {
        $data["Backup_Description_" . $backupserver["Reference"]] = __ ( "Backup servers limit exceeded.");
        continue;
      }
      if ( empty ( $backupserver["Description"]) && ( ! empty ( $backupserver["Address"]) || ! empty ( $backupserver["Port"])))
      {
        $data["Backup_Description_" . $backupserver["Reference"]] = __ ( "Empty backup server description.");
      }
      if ( empty ( $backupserver["Address"]) && ! empty ( $backupserver["Description"]))
      {
        $data["Backup_Address_" . $backupserver["Reference"]] = __ ( "The backup server address is required.");
      }
      if ( ! empty ( $backupserver["Address"]) && ! preg_match ( "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/", $backupserver["Address"]) && gethostbyname ( $backupserver["Address"] == $backupserver["Address"]))
      {
        $data["Backup_Address_" . $backupserver["Reference"]] = __ ( "The backup server address is invalid.");
      }
      $backupserver["Port"] = (int) $backupserver["Port"];
      if ( $backupserver["Port"] < 0 || $backupserver["Port"] >= 65535)
      {
        $data["Backup_Port_" . $backupserver["Reference"]] = __ ( "The backup server port is invalid.");
      }
      $backups[] = array ( "Description" => $backupserver["Description"], "Address" => $backupserver["Address"], "Port" => $backupserver["Port"]);
    }
  }
  $backupscopy = $backups;

  /**
   * Check if transfer window enabled, and time window provided
   */
  if ( $parameters["Window"] && ! array_key_exists ( "Start", $parameters))
  {
    $data["Start"] = __ ( "The start hour is required.when transfer window are enabled.");
  }
  if ( $parameters["Window"] && ! array_key_exists ( "Finish", $parameters))
  {
    $data["Finish"] = __ ( "The finish hour is required.when transfer window are enabled.");
  }

  /**
   * Check if server IP and port was not in use
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `Address` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Address"]) . "' AND `Port` = '" . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Port"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["Address"] = __ ( "The address and port is already in use by other server.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "servers_edit_validate"))
  {
    $data = framework_call ( "servers_edit_validate", $parameters, false, $data);
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
  $parameters["Port"] = (int) $parameters["Port"];
  $parameters["Window"] = (boolean) $parameters["Window"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "servers_edit_sanitize"))
  {
    $parameters = framework_call ( "servers_edit_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "servers_edit_pre"))
  {
    $parameters = framework_call ( "servers_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change server record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Servers` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Description"]) . "', `Address` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Address"]) . "', `Port` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Port"]) . "', `NTP` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["NTP"])) . "', `TransfStart` = " . ( $parameters["Window"] ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . ":00'" : "NULL") . ", `TransfEnd` = " . ( $parameters["Window"] ? "'" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Finish"]) . ":59'" : "NULL") . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Fetch current server backups
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ServerBackup` WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $oldbackups = array ();
  while ( $backup = $result->fetch_assoc ())
  {
    $oldbackups[] = $backup;
  }
  $oldbackupscopy = $oldbackups;

  /**
   * Check backup servers
   */
  foreach ( $backups as $bindex => $backup)
  {
    foreach ( $oldbackups as $oindex => $oldbackup)
    {
      if ( $oldbackup["Address"] == $backup["Address"] && $oldbackup["Port"] == $backup["Port"])
      {
        unset ( $oldbackups[$oindex]);
        unset ( $backups[$bindex]);
        if ( $oldbackup["Description"] != $backup["Description"])
        {
          if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `ServerBackup` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $backup["Description"]) . "' WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["ID"]) . " AND `Address` = '" . $_in["mysql"]["id"]->real_escape_string ( $backup["Address"]) . "' AND `Port` = '" . $_in["mysql"]["id"]->real_escape_string ( $backup["Port"]) . "'"))
          {
            header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
            exit ();
          }
          break;
        }
      }
    }
  }
  foreach ( $oldbackups as $oindex => $oldbackup)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ServerBackup` WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["ID"]) . " AND `Address` = '" . $_in["mysql"]["id"]->real_escape_string ( $oldbackup["Address"]) . "' AND `Port` = '" . $_in["mysql"]["id"]->real_escape_string ( $oldbackup["Port"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }
  foreach ( $backups as $bindex => $backup)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ServerBackup` (`Server`, `Description`, `Address`, `Port`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ORIGINAL"]["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $backup["Description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $backup["Address"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $backup["Port"]) . "')"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }
  $parameters["Backups"] = $backups;
  $parameters["BackupsCopy"] = $backupscopy;
  $parameters["OldBackups"] = $oldbackups;
  $parameters["OldBackupsCopy"] = $oldbackupscopy;

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "servers_edit_post"))
  {
    framework_call ( "servers_edit_post", $parameters);
  }

  /**
   * Notify servers about change (if needed)
   */
  if ( sizeof ( $backups) != 0 || sizeof ( $oldbackups) != 0 || $parameters["ORIGINAL"]["Address"] != $parameters["Address"] || $parameters["ORIGINAL"]["Port"] != $parameters["Port"] || $parameters["ORIGINAL"]["Description"] != $parameters["Description"])
  {
    $notify = array ( "ID" => $parameters["ORIGINAL"]["ID"], "Description" => $parameters["Description"], "Address" => $parameters["Address"], "Port" => $parameters["Port"], "Backups" => $backupscopy);
    if ( framework_has_hook ( "servers_edit_reference_notify"))
    {
      $notify = framework_call ( "servers_edit_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["ORIGINAL"]["ID"] * -1, "server_change", $notify);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "servers_edit_finish"))
  {
    framework_call ( "servers_edit_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a server
 */
framework_add_hook (
  "servers_remove",
  "servers_remove",
  IN_HOOK_NULL,
  array (
    "response" => array (
      204 => array (
        "description" => __ ( "The system server was removed.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ID" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid server ID.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "servers_remove", __ ( "Remove servers"));
framework_add_api_call (
  "/servers/:ID",
  "Delete",
  "servers_remove",
  array (
    "permissions" => array ( "user", "servers_remove"),
    "title" => __ ( "Remove servers"),
    "description" => __ ( "Remove a server from system.")
  )
);

/**
 * Function to remove an existing server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "servers_remove_start"))
  {
    $parameters = framework_call ( "servers_remove_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "ID", $parameters) || ! is_numeric ( $parameters["ID"]))
  {
    $data["ID"] = __ ( "Invalid server ID.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "servers_remove_validate"))
  {
    $data = framework_call ( "servers_remove_validate", $parameters, false, $data);
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
  if ( framework_has_hook ( "servers_remove_sanitize"))
  {
    $parameters = framework_call ( "servers_remove_sanitize", $parameters, false, $parameters);
  }

  /**
   * Check if server exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
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
   * Check if server has any active range
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    exit ();
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "servers_remove_pre"))
  {
    $parameters = framework_call ( "servers_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove server database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "servers_remove_post"))
  {
    framework_call ( "servers_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["ORIGINAL"]["ID"]);
  if ( framework_has_hook ( "servers_remove_notify"))
  {
    $notify = framework_call ( "servers_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "server_remove", $notify);

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "servers_remove_finish"))
  {
    framework_call ( "servers_remove_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return $buffer;
}

/**
 * API call to rebuild a server
 */
framework_add_hook (
  "servers_rebuild",
  "servers_rebuild",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "required" => true,
      "properties" => array (
        "Clean" => array (
          "type" => "boolean",
          "description" => __ ( "Force the server to wipe current configuration before rebuild configuration files."),
          "default" => false,
          "example" => true
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "The system server was sucessfully rebuilded.")
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Server" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "The informed server ID was not found.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "servers_rebuild", __ ( "Reinstall servers"));
framework_add_api_call (
  "/servers/:ID/rebuild",
  "Create",
  "servers_rebuild",
  array (
    "permissions" => array ( "user", "servers_rebuild"),
    "title" => __ ( "Rebuild servers"),
    "description" => __ ( "Rebuild server configuration files."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The server internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to rebuild an existing server.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_rebuild ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "servers_rebuild_start"))
  {
    $parameters = framework_call ( "servers_rebuild_start", $parameters);
  }

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Check if server exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["Server"] = __ ( "The informed server ID was not found.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "servers_rebuild_validate"))
  {
    $data = framework_call ( "servers_rebuild_validate", $parameters, false, $data);
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
  $parameters["Clean"] = (boolean) $parameters["Clean"];

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "servers_rebuild_sanitize"))
  {
    $parameters = framework_call ( "servers_rebuild_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "servers_rebuild_pre"))
  {
    $parameters = framework_call ( "servers_rebuild_pre", $parameters, false, $parameters);
  }

  /**
   * Start notify capture
   */
  notify_capture ( $parameters["ID"], "server_rebuild", array ( "CleanUp" => $parameters["Clean"]));

  /**
   * Call server rebuild hook, if exist
   */
  if ( framework_has_hook ( "servers_rebuild_config"))
  {
    framework_call ( "servers_rebuild_config", $parameters);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "servers_rebuild_post"))
  {
    framework_call ( "servers_rebuild_post", $parameters);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "servers_rebuild_finish"))
  {
    framework_call ( "servers_rebuild_finish", $parameters, false);
  }

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to generate server call's report
 */
framework_add_hook (
  "servers_report",
  "servers_report",
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
        "description" => __ ( "An array containing the call records made by the required group."),
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
framework_add_permission ( "servers_report", __ ( "Servers use report"));
framework_add_api_call (
  "/servers/:ID/report",
  "Read",
  "servers_report",
  array (
    "permissions" => array ( "user", "servers_report"),
    "title" => __ ( "Servers report"),
    "description" => __ ( "Generate a server call's usage report.", true, false),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The server internal system unique identifier."),
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
function servers_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "servers_report_start"))
  {
    $parameters = framework_call ( "servers_report_start", $parameters);
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
  if ( framework_has_hook ( "servers_report_validate"))
  {
    $data = framework_call ( "servers_report_validate", $parameters);
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
  if ( framework_has_hook ( "servers_report_sanitize"))
  {
    $parameters = framework_call ( "servers_report_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "servers_report_pre"))
  {
    $parameters = framework_call ( "servers_report_pre", $parameters, false, $parameters);
  }

  /**
   * Get server information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $server = $result->fetch_assoc ();

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `server` = " . $_in["mysql"]["id"]->real_escape_string ( $server["ID"]) . " AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["End"]) . "' ORDER BY `calldate` DESC"))
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
  if ( framework_has_hook ( "servers_report_post"))
  {
    $data = framework_call ( "servers_report_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "servers_report_finish"))
  {
    framework_call ( "servers_report_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to server install script
 */
framework_add_hook (
  "servers_install_script",
  "servers_install_script",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the server install script."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Script" => array (
              "type" => "string",
              "description" => __ ( "The BASH script to install the server.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "servers_install_script", __ ( "Generate servers install script"));
framework_add_api_call (
  "/servers/:ID/install",
  "Read",
  "servers_install_script",
  array (
    "permissions" => array ( "user", "servers_install_script"),
    "title" => __ ( "Server install script"),
    "description" => __ ( "Generate a server installation BASH script."),
    "parameters" => array (
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The server internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to create an existing server install script.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function servers_install_script ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Check if server exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $server = $result->fetch_assoc ();

  /**
   * Read install script template and generate code
   */
  $script = file_get_contents ( dirname ( __FILE__) . "/install.sh");
  $script = str_replace ( "{{{ID}}}", str_replace ( "/", "\/", $server["ID"]), $script);
  $script = str_replace ( "{{{PUBLICKEY}}}", str_replace ( "/", "\/", $server["PublicKey"]), $script);
  $script = str_replace ( "{{{PRIVATEKEY}}}", str_replace ( "/", "\/", $server["PrivateKey"]), $script);
  $script = str_replace ( "{{{URL}}}", str_replace ( "/", "\/", $_in["general"]["baseurl"]), $script);

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "script" => base64_encode ( $script)));
}
?>
