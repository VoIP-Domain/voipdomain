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
 * VoIP Domain extensions group hunts module API. This module add the API calls
 * related to extensions group hunts.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Hunts
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API hook to extend Add extensions of group hunt type
 */
framework_add_function_documentation (
  "extensions_search",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "hunt"),
          "example" => "hunt"
        )
      )
    ),
    "response" => array (
      200 => array (
        "schema" => array (
          "items" => array (
            "properties" => array (
              "Type" => array (
                "enum" => array ( "hunt"),
                "example" => "hunt"
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "extensions_view_hunt",
  "extensions_view_hunt"
);
framework_add_function_documentation (
  "extensions_view",
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "properties" => array (
            "Type" => array (
              "enum" => array ( "hunt"),
              "example" => array ( "hunt")
            ),
            "oneOf" => array (
              array (
                "type" => "object",
                "description" => __ ( "An object with hunt extensions information."),
                "properties" => array (
                  "Hunts" => array (
                    "type" => "array",
                    "description" => __ ( "An array with hunt extensions."),
                    "items" => array (
                      "type" => "object",
                      "properties" => array (
                        "ID" => array (
                          "type" => "integer",
                          "description" => __ ( "The extension internal system unique identifier."),
                          "example" => 1
                        ),
                        "Number" => array (
                          "type" => "integer",
                          "description" => __ ( "The telephone number of the extension."),
                          "example" => 1000
                        ),
                        "Description" => array (
                          "type" => "string",
                          "description" => __ ( "The description of the extension."),
                          "example" => __ ( "John Doe")
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
  )
);
framework_add_hook (
  "extensions_add_hunt_sanitize",
  "extensions_hunt_sanitize"
);
framework_add_hook (
  "extensions_edit_hunt_sanitize",
  "extensions_hunt_sanitize"
);
framework_add_hook (
  "extensions_add_hunt_validate",
  "extensions_hunt_validate"
);
framework_add_hook (
  "extensions_edit_hunt_validate",
  "extensions_hunt_validate"
);
framework_add_hook (
  "extensions_add_hunt_post",
  "extensions_add_hunt_post"
);
framework_add_hook (
  "extensions_edit_hunt_post",
  "extensions_edit_hunt_post"
);
framework_add_function_documentation (
  "extensions_add",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "hunt")
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "Hunts" => array (
                "type" => "array",
                "description" => __ ( "The array of extension hunt unique identifiers."),
                "required" => true,
                "items" => array (
                  "type" => "integer"
                )
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
                  "Hunts" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "At least one invalid extension selected.")
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
          "enum" => array ( "hunt")
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "Hunts" => array (
                "type" => "array",
                "description" => __ ( "The array of extension hunt unique identifiers."),
                "required" => true,
                "items" => array (
                  "type" => "integer"
                )
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
                  "Hunts" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "At least one invalid extension selected.")
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
  "extensions_remove_hunt_pre",
  "extensions_remove_hunt_pre"
);

/**
 * Function to extend extensions with group hunt information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_view_hunt ( $buffer, $parameters)
{
  global $_in;

  /**
   * Search extensions for the extension hunt
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Description`, `Number` FROM `Extensions` LEFT JOIN `ExtensionHunt` ON `ExtensionHunt`.`Hunt` = `Extensions`.`ID` WHERE `Extensions`.`Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ExtensionHunt`.`Extension` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "extensions_view_hunt");
  }

  /**
   * Format data
   */
  $buffer["Hunts"] = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    $buffer["Hunts"][] = array ( "ID" => $extension["ID"], "Description" => $extension["Description"], "Number" => $extension["Number"]);
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend Add extensions/edit sanitize of group hunt type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_hunt_sanitize ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanitize parameters
   */
  foreach ( $buffer["Hunts"] as $key => $value)
  {
    $buffer["Hunts"][$key] = (int) $value;
  }

  /**
   * Fetch all new hunts numbers
   */
  $hunttypes = array ();
  foreach ( $_in["hunts"] as $hunttype)
  {
    $hunttypes[] = "'" . $_in["mysql"]["id"]->real_escape_string ( substr ( $hunttype, 10)) . "'";
  }
  $tmp = $buffer["Hunts"];
  $buffer["Hunts"] = array ();
  foreach ( $tmp as $hunt)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number` FROM `Extensions` WHERE `Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ID` = " . (int) $hunt . " AND `Type` IN (" . implode ( ",", $hunttypes) . ")"))
    {
      database_query_error ( "extensions_hunt_sanitize");
    }
    $buffer["Hunts"][$hunt] = intval ( $result->fetch_assoc ()["Number"]);
  }

  /**
   * If it's an edit, fetch old extensions
   */
  if ( array_key_exists ( "ORIGINAL", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Number` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Hunt` = `Extensions`.`ID` WHERE `Extensions`.`Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ExtensionHunt`.`Extension` = " . (int) $parameters["ID"]))
    {
      database_query_error ( "extensions_hunt_sanitize");
    }
    $buffer["ORIGINAL"]["Hunts"] = array ();
    while ( $hunt = $result->fetch_assoc ())
    {
      $buffer["ORIGINAL"]["Hunts"][$hunt["ID"]] = $hunt["Number"];
    }
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend Add extensions/edit validate of group hunt type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_hunt_validate ( $buffer, $parameters)
{
  /**
   * Validate parameters
   */
  if ( sizeof ( $parameters["Hunts"]) == 0)
  {
    $buffer["Hunts"] = __ ( "At least one extension is required.");
  }

  /**
   * Check if hunt extensions are valid
   */
  if ( ! array_key_exists ( "Hunts", $buffer))
  {
    foreach ( $parameters["Hunts"] as $hunt => $number)
    {
      if ( $number == 0)
      {
        $buffer["Hunts"] = __ ( "At least one invalid extension selected.");
      }
    }
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post addition of group hunt type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_hunt_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add each extension group hunt record
   */
  foreach ( $parameters["Hunts"] as $hunt => $number)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionHunt` (`Extension`, `Hunt`) VALUES (" . (int) $parameters["ID"] . ", " . (int) $hunt . ")"))
    {
      framework_call ( "extensions_add_abort", $parameters);
      database_query_error ( "extensions_add_hunt_post");
    }
  }

  /**
   * Add new group hunt at Asterisk server
   */
  $notify = array ( "Number" => $parameters["Number"], "Description" => $parameters["Description"], "Extensions" => array_values ( $parameters["Hunts"]));
  if ( framework_has_hook ( "extension_hunt_add_notify"))
  {
    $notify = framework_call ( "extension_hunt_add_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["Range"]["Server"], "extension_hunt_add", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post edit of group hunt type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_hunt_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check if there's extension change, if changed, update database
   */
  if ( ! array_compare ( $parameters["ORIGINAL"]["Hunts"], $parameters["Hunts"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHunt` WHERE `Extension` = " . (int) $parameters["ID"]))
    {
      framework_call ( "extensions_edit_abort", $parameters);
      database_query_error ( "extensions_edit_hunt_post");
    }
    foreach ( $parameters["Hunts"] as $hunt => $number)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionHunt` (`Extension`, `Hunt`) VALUES (" . (int) $parameters["ID"] . ", " . (int) $hunt . ")"))
      {
        framework_call ( "extensions_edit_abort", $parameters);
        database_query_error ( "extensions_edit_hunt_post");
      }
    }
  }

  /**
   * Notify server about change
   */
  if ( $parameters["Range"]["Server"] == $parameters["ORIGINAL"]["Range"]["Server"])
  {
    $notify = array ( "Number" => (int) $parameters["ORIGINAL"]["Number"], "NewNumber" => (int) $parameters["Number"], "Description" => $parameters["Description"], "Extensions" => array_values ( $parameters["Hunts"]));
    if ( framework_has_hook ( "extension_hunt_edit_notify"))
    {
      $notify = framework_call ( "extension_hunt_edit_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["Range"]["Server"], "extension_hunt_change", $notify);
  } else {
    $notify = array ( "Number" => $parameters["ORIGINAL"]["Number"]);
    if ( framework_has_hook ( "extension_hunt_remove_notify"))
    {
      $notify = framework_call ( "extension_hunt_remove_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["ORIGINAL"]["Range"]["Server"], "extension_hunt_remove", $notify);
    $notify = array ( "Number" => (int) $parameters["Number"], "Description" => $parameters["Description"], "Extensions" => array_values ( $parameters["Hunts"]));
    if ( framework_has_hook ( "extension_hunt_add_notify"))
    {
      $notify = framework_call ( "extension_hunt_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["Range"]["Server"], "extension_hunt_add", $notify);
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions pre remotion of group hunt type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove_hunt_pre ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get extension hunts to record
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Number` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Hunt` = `Extensions`.`ID` WHERE `Extensions`.`Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `ExtensionHunt`.`Extension` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "extensions_remove_hunt_pre");
  }
  $buffer["ORIGINAL"]["Hunts"] = array ();
  while ( $hunt = $result->fetch_assoc ())
  {
    $buffer["ORIGINAL"]["Hunts"][$hunt["ID"]] = $hunt["Number"];
  }

  /**
   * Remove extension hunts from database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHunt` WHERE `Extension` = " . (int) $parameters["ID"]))
  {
    database_query_error ( "extensions_remove_hunt_pre");
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Number" => $buffer["ORIGINAL"]["Number"]);
  if ( framework_has_hook ( "extension_hunt_remove_notify"))
  {
    $notify = framework_call ( "extension_hunt_remove_notify", $parameters, false, $notify);
  }
  notify_server ( $buffer["ORIGINAL"]["Range"]["Server"], "extension_hunt_remove", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * API call to intercept extensions number change
 */
framework_add_hook ( "extensions_number_changed", "extension_hunt_extensions_changed");

/**
 * Function to check if a changed extension are present at extension hunts.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extension_hunt_extensions_changed ( $buffer, $parameters)
{
  global $_in;

  $tenant = (int) $_in["session"]["Tenant"];
  $id = (int) $parameters["ID"];

  /**
   * Hunt groups that include this extension as a member (number may have changed).
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionHunt`.`Extension`, `HuntExt`.`Number`, `HuntExt`.`Description`, `Ranges`.`Server` FROM `ExtensionHunt` LEFT JOIN `Extensions` AS `HuntExt` ON `ExtensionHunt`.`Extension` = `HuntExt`.`ID` LEFT JOIN `Ranges` ON `HuntExt`.`Range` = `Ranges`.`ID` WHERE `HuntExt`.`Tenant` = " . $tenant . " AND `ExtensionHunt`.`Hunt` = " . $id))
  {
    database_query_error ( "extension_hunt_extensions_changed");
  }
  while ( $hunt = $result->fetch_assoc ())
  {
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`MemberExt`.`Number` SEPARATOR ',') AS `Extensions` FROM `ExtensionHunt` LEFT JOIN `Extensions` AS `MemberExt` ON `ExtensionHunt`.`Hunt` = `MemberExt`.`ID` WHERE `MemberExt`.`Tenant` = " . $tenant . " AND `ExtensionHunt`.`Extension` = " . (int) $hunt["Extension"]))
    {
      database_query_error ( "extension_hunt_extensions_changed");
    }
    $members = $result2->fetch_assoc ()["Extensions"];
    if ( $members == "")
    {
      continue;
    }
    $notify = array ( "Number" => $hunt["Number"], "NewNumber" => $hunt["Number"], "Description" => $hunt["Description"], "Extensions" => explode ( ",", $members));
    if ( framework_has_hook ( "extension_hunt_edit_notify"))
    {
      $notify = framework_call ( "extension_hunt_edit_notify", $parameters, false, $notify);
    }
    notify_server ( (int) $hunt["Server"], "extension_hunt_change", $notify);
  }

  /**
   * This extension is a hunt group whose dial number changed.
   */
  if ( array_key_exists ( "Old", $parameters) && array_key_exists ( "New", $parameters) && (int) $parameters["Old"] != (int) $parameters["New"])
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `HuntExt`.`Description`, `Ranges`.`Server` FROM `Extensions` AS `HuntExt` LEFT JOIN `Ranges` ON `HuntExt`.`Range` = `Ranges`.`ID` WHERE `HuntExt`.`Tenant` = " . $tenant . " AND `HuntExt`.`ID` = " . $id . " AND `HuntExt`.`Type` = 'hunt'"))
    {
      database_query_error ( "extension_hunt_extensions_changed");
    }
    if ( $result->num_rows != 0)
    {
      $hunt = $result->fetch_assoc ();
      if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`MemberExt`.`Number` SEPARATOR ',') AS `Extensions` FROM `ExtensionHunt` LEFT JOIN `Extensions` AS `MemberExt` ON `ExtensionHunt`.`Hunt` = `MemberExt`.`ID` WHERE `MemberExt`.`Tenant` = " . $tenant . " AND `ExtensionHunt`.`Extension` = " . $id))
      {
        database_query_error ( "extension_hunt_extensions_changed");
      }
      $members = $result2->fetch_assoc ()["Extensions"];
      if ( $members != "")
      {
        $notify = array ( "Number" => (int) $parameters["Old"], "NewNumber" => (int) $parameters["New"], "Description" => $hunt["Description"], "Extensions" => explode ( ",", $members));
        if ( framework_has_hook ( "extension_hunt_edit_notify"))
        {
          $notify = framework_call ( "extension_hunt_edit_notify", $parameters, false, $notify);
        }
        notify_server ( (int) $hunt["Server"], "extension_hunt_change", $notify);
      }
    }
  }

  /**
   * Return buffer
   */
  return $buffer;
}

/**
 * API call to intercept extension removal
 */
framework_add_hook ( "extensions_remove_pre", "extension_hunt_extensions_remove_pre");

/**
 * Function to check if a removed extension are present at extension hunts.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extension_hunt_extensions_remove_pre ( $buffer, $parameters)
{
  global $_in;

  $tenant = (int) $_in["session"]["Tenant"];
  $id = (int) $parameters["ID"];

  /**
   * Removed extension was a member of hunt group(s): refresh those dialplans.
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionHunt`.`Extension`, `HuntExt`.`Number`, `HuntExt`.`Description`, `Ranges`.`Server` FROM `ExtensionHunt` LEFT JOIN `Extensions` AS `HuntExt` ON `ExtensionHunt`.`Extension` = `HuntExt`.`ID` LEFT JOIN `Ranges` ON `HuntExt`.`Range` = `Ranges`.`ID` WHERE `HuntExt`.`Tenant` = " . $tenant . " AND `ExtensionHunt`.`Hunt` = " . $id))
  {
    database_query_error ( "extension_hunt_extensions_remove_pre");
  }
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHunt` WHERE `Hunt` = " . $id))
  {
    database_query_error ( "extension_hunt_extensions_remove_pre");
  }
  while ( $hunt = $result->fetch_assoc ())
  {
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`MemberExt`.`Number` SEPARATOR ',') AS `Extensions` FROM `ExtensionHunt` LEFT JOIN `Extensions` AS `MemberExt` ON `ExtensionHunt`.`Hunt` = `MemberExt`.`ID` WHERE `MemberExt`.`Tenant` = " . $tenant . " AND `ExtensionHunt`.`Extension` = " . (int) $hunt["Extension"]))
    {
      database_query_error ( "extension_hunt_extensions_remove_pre");
    }
    $members = $result2->fetch_assoc ()["Extensions"];
    if ( $members == "")
    {
      framework_call ( "extensions_remove", array ( "ID" => $hunt["Extension"]));
    } else {
      $notify = array ( "Number" => $hunt["Number"], "NewNumber" => $hunt["Number"], "Description" => $hunt["Description"], "Extensions" => explode ( ",", $members));
      if ( framework_has_hook ( "extension_hunt_edit_notify"))
      {
        $notify = framework_call ( "extension_hunt_edit_notify", $parameters, false, $notify);
      }
      notify_server ( (int) $hunt["Server"], "extension_hunt_change", $notify);
    }
  }

  /**
   * Removed extension was a hunt group: drop member rows only (remove notify is type-specific).
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHunt` WHERE `Extension` = " . $id))
  {
    database_query_error ( "extension_hunt_extensions_remove_pre");
  }

  /**
   * Return buffer
   */
  return $buffer;
}

/**
 * API call to intercept server rebuild
 */
framework_add_hook ( "servers_rebuild_config", "extension_hunt_server_reconfig");

/**
 * Function to notify server to include all hunts.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extension_hunt_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all hunts and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `HuntExt`.`Number`, `HuntExt`.`Description`, GROUP_CONCAT(`MemberExt`.`Number` SEPARATOR ',') AS `Extensions` FROM `ExtensionHunt` LEFT JOIN `Extensions` AS `HuntExt` ON `ExtensionHunt`.`Extension` = `HuntExt`.`ID` LEFT JOIN `Extensions` AS `MemberExt` ON `ExtensionHunt`.`Hunt` = `MemberExt`.`ID` LEFT JOIN `Ranges` ON `HuntExt`.`Range` = `Ranges`.`ID` WHERE `HuntExt`.`Tenant` = " . (int) $_in["session"]["Tenant"] . " AND `HuntExt`.`Type` = 'hunt' AND `Ranges`.`Server` = " . (int) $parameters["ID"] . " GROUP BY `ExtensionHunt`.`Extension`, `HuntExt`.`Number`, `HuntExt`.`Description`"))
  {
    database_query_error ( "extension_hunt_server_reconfig");
  }
  while ( $hunt = $result->fetch_assoc ())
  {
    $notify = array ( "Number" => $hunt["Number"], "Description" => $hunt["Description"], "Extensions" => explode ( ",", $hunt["Extensions"]));
    if ( framework_has_hook ( "extension_hunt_add_notify"))
    {
      $notify = framework_call ( "extension_hunt_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["ID"], "extension_hunt_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
