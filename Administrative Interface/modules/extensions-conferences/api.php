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
 * VoIP Domain extensions conferences module API. This module add the API calls
 * related to extensions conferences.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Conferences
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API hook to extend Add extensions of conference type
 */
framework_add_function_documentation (
  "extensions_search",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "conference"),
          "example" => "conference"
        )
      )
    ),
    "response" => array (
      200 => array (
        "schema" => array (
          "items" => array (
            "properties" => array (
              "Type" => array (
                "enum" => array ( "conference"),
                "example" => "conference"
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "extensions_view_conference",
  "extensions_view_conference"
);
framework_add_function_documentation (
  "extensions_view",
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "properties" => array (
            "Type" => array (
              "enum" => array ( "conference"),
              "example" => array ( "conference")
            ),
            "oneOf" => array (
              array (
                "type" => "object",
                "description" => __ ( "An object with conference information."),
                "properties" => array (
                  "Conference" => array (
                    "type" => "object",
                    "properties" => array (
                      "ID" => array (
                        "type" => "integer",
                        "description" => __ ( "The conference room internal system unique identifier."),
                        "example" => 1
                      ),
                      "Name" => array (
                        "type" => "string",
                        "description" => __ ( "The conference room name."),
                        "example" => __ ( "Sales")
                      ),
                      "Description" => array (
                        "type" => "string",
                        "description" => __ ( "The conference room description."),
                        "example" => __ ( "Sales conference room.")
                      ),
                      "Admin" => array (
                        "type" => "string",
                        "enum" => array ( "Y", "N"),
                        "description" => __ ( "If the caller should enter as conference administrator."),
                        "example" => "N"
                      )
                    )
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
framework_add_hook (
  "extensions_add_conference_validate",
  "extensions_conference_validate"
);
framework_add_hook (
  "extensions_edit_conference_validate",
  "extensions_conference_validate"
);
framework_add_hook (
  "extensions_add_conference_post",
  "extensions_add_conference_post"
);
framework_add_hook (
  "extensions_edit_conference_post",
  "extensions_edit_conference_post"
);
framework_add_function_documentation (
  "extensions_add",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "conference")
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "Conference" => array (
                "type" => "integer",
                "description" => __ ( "The conference room system unique identifier."),
                "required" => true
              ),
              "Admin" => array (
                "type" => "string",
                "enum" => array ( "Y", "N"),
                "description" => __ ( "If the caller should enter as conference administrator."),
                "required" => true
              )
            )
          )
        )
      )
    ),
    "response" => array (
      422 => array (
        "schema" => array (
          "properties" => array (
            "anyOf" => array (
              array (
                "type" => "object",
                "properties" => array (
                  "Conference" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The selected conference room is invalid.")
                  ),
                  "Admin" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Admin must be Y or N.")
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
framework_add_function_documentation (
  "extensions_edit",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "conference")
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "Conference" => array (
                "type" => "integer",
                "description" => __ ( "The conference room system unique identifier."),
                "required" => true
              ),
              "Admin" => array (
                "type" => "string",
                "enum" => array ( "Y", "N"),
                "description" => __ ( "If the caller should enter as conference administrator."),
                "required" => true
              )
            )
          )
        )
      )
    ),
    "response" => array (
      422 => array (
        "schema" => array (
          "properties" => array (
            "anyOf" => array (
              array (
                "type" => "object",
                "properties" => array (
                  "Conference" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The selected conference room is invalid.")
                  ),
                  "Admin" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Admin must be Y or N.")
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
framework_add_hook (
  "extensions_remove_conference_pre",
  "extensions_remove_conference_pre"
);

/**
 * Function to extend extensions with conference information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_view_conference ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch conference information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Conferences`.`ID`, `Conferences`.`Name`, `Conferences`.`Description`, `ExtensionConference`.`Admin` FROM `Conferences` LEFT JOIN `ExtensionConference` ON `ExtensionConference`.`Conference` = `Conferences`.`ID` WHERE `Conferences`.`Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ExtensionConference`.`Extension` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "extensions_view_conference");
  }
  $conference = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["Conference"] = array ( "ID" => $conference["ID"], "Name" => $conference["Name"], "Description" => $conference["Description"], "Admin" => $conference["Admin"]);

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to extend Add extensions/edit validate of conference type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_conference_validate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Validate conference parameter
   */
  if ( $parameters["Conference"] != (int) $parameters["Conference"])
  {
    $buffer["Conference"] = __ ( "The selected conference room is invalid.");
  }
  $parameters["Conference"] = (int) $parameters["Conference"];
  if ( ! array_key_exists ( "Conference", $buffer) && empty ( $parameters["Conference"]))
  {
    $buffer["Conference"] = __ ( "Conference is required.");
  }

  /**
   * Validate admin parameter
   */
  if ( ! array_key_exists ( "Admin", $parameters) || empty ( $parameters["Admin"]))
  {
    $buffer["Admin"] = __ ( "Admin field is required.");
  }
  if ( ! array_key_exists ( "Admin", $buffer) && ! in_array ( $parameters["Admin"], array ( "Y", "N")))
  {
    $buffer["Admin"] = __ ( "Admin must be Y or N.");
  }

  /**
   * Check if conference exists
   */
  if ( ! array_key_exists ( "Conference", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["Conference"]))
    {
      database_query_error ( "extensions_conference_validate");
    }
    if ( $result->num_rows != 1)
    {
      $buffer["Conference"] = __ ( "The selected conference room is invalid.");
    }
  }

  /**
   * If it's an edit, fetch old conference
   */
  if ( array_key_exists ( "ORIGINAL", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionConference`.`Conference`, `ExtensionConference`.`Admin` FROM `ExtensionConference` LEFT JOIN `Extensions` ON `ExtensionConference`.`Extension` = `Extensions`.`ID` WHERE `Extensions`.`Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ExtensionConference`.`Extension` = " . (int) $parameters["ID"]))
    {
      database_query_error ( "extensions_conference_validate");
    }
    $old = $result->fetch_assoc ();
    if ( ! $old)
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
      exit ();
    }
    $buffer["ORIGINAL"]["Conference"] = $old["Conference"];
    $buffer["ORIGINAL"]["Admin"] = $old["Admin"];
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post addition of conference type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_conference_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add new extension conference record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionConference` (`Conference`, `Extension`, `Admin`) VALUES (" . (int) $parameters["Conference"] . ", " . (int) $parameters["ID"] . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Admin"]) . "')"))
  {
    framework_call ( "extensions_add_abort", $parameters);
    database_query_error ( "extensions_add_conference_post");
  }

  /**
   * Get conference name
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["Conference"]))
  {
    framework_call ( "extensions_add_abort", $parameters);
    database_query_error ( "extensions_add_conference_post");
  }
  if ( ! $conference = $result->fetch_assoc ())
  {
    framework_call ( "extensions_add_abort", $parameters);
    database_query_error ( "extensions_add_conference_post");
  }

  /**
   * Associate conference to extension at Asterisk server
   */
  $notify = array ( "Number" => $parameters["Number"], "Conference" => $conference["Name"], "Admin" => $parameters["Admin"]);
  if ( framework_has_hook ( "extension_conference_add_notify"))
  {
    $notify = framework_call ( "extension_conference_add_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["Range"]["Server"], "extension_conference_add", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post edit of conference type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_conference_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check if there's conference change, if changed, update database
   */
  if ( ! array_compare ( $parameters["ORIGINAL"]["Conference"], $parameters["Conference"]) || ! array_compare ( $parameters["ORIGINAL"]["Admin"], $parameters["Admin"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `ExtensionConference` SET `Conference` = " . (int) $parameters["Conference"] . ", `Admin` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Admin"]) . "' WHERE `Extension` = " . (int) $parameters["ID"]))
    {
      framework_call ( "extensions_edit_abort", $parameters);
      database_query_error ( "extensions_edit_conference_post");
    }
  }

  /**
   * Get conference name
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Conferences` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["Conference"]))
  {
    framework_call ( "extensions_edit_abort", $parameters);
    database_query_error ( "extensions_edit_conference_post");
  }
  if ( ! $conference = $result->fetch_assoc ())
  {
    framework_call ( "extensions_edit_abort", $parameters);
    database_query_error ( "extensions_edit_conference_post");
  }

  /**
   * Notify server about change
   */
  $notify = array ( "Number" => $parameters["ORIGINAL"]["Number"], "NewNumber" => $parameters["Number"], "Conference" => $conference["Name"], "Admin" => $parameters["Admin"]);
  if ( framework_has_hook ( "extension_conference_change_notify"))
  {
    $notify = framework_call ( "extension_conference_change_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["Range"]["Server"], "extension_conference_change", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions pre remotion of conference type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove_conference_pre ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get extension conference to record
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Conference`, `Admin` FROM `ExtensionConference` WHERE `Extension` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "extensions_remove_conference_pre");
  }
  $old = $result->fetch_assoc ();
  if ( ! $old)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $buffer["ORIGINAL"]["Conference"] = $old["Conference"];
  $buffer["ORIGINAL"]["Admin"] = $old["Admin"];

  /**
   * Remove extension conference from database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionConference` WHERE `Extension` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "extensions_remove_conference_pre");
  }

  /**
   * Get extension number to notify server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number` FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "extensions_remove_conference_pre");
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Number" => $result->fetch_assoc ()["Number"]);
  if ( framework_has_hook ( "extension_conference_remove_notify"))
  {
    $notify = framework_call ( "extension_conference_remove_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["ORIGINAL"]["Range"]["Server"], "extension_conference_remove", $notify);

  /**
   * Return data
   */
  return $buffer;
}

framework_add_hook ( "servers_rebuild_config", "extensions_conference_server_reconfig");

/**
 * Function to rebuild extensions of conference type on server rebuild.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_conference_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all extension conferences and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionConference`.`Conference`, `ExtensionConference`.`Admin`, `Extensions`.`Number`, `Conferences`.`Name` AS `ConferenceName` FROM `ExtensionConference` LEFT JOIN `Extensions` ON `ExtensionConference`.`Extension` = `Extensions`.`ID` LEFT JOIN `Conferences` ON `ExtensionConference`.`Conference` = `Conferences`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `Extensions`.`Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `Ranges`.`Server` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "extensions_conference_server_reconfig");
  }
  while ( $conference = $result->fetch_assoc ())
  {
    $notify = array ( "Number" => $conference["Number"], "Conference" => $conference["ConferenceName"], "Admin" => $conference["Admin"]);
    if ( framework_has_hook ( "extension_conference_add_notify"))
    {
      $notify = framework_call ( "extension_conference_add_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $parameters["ID"], "extension_conference_add", $notify);
  }

  /**
   * Return data
   */
  return $buffer;
}
?>
