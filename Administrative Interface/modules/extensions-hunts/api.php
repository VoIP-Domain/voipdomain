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
 * VoIP Domain extensions group hunts module API. This module add the API calls
 * related to extensions group hunts.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Hunts
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API hook to extend extensions addition of group hunt type
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
framework_add_hook (
  "extensions_add_hunt_audit",
  "extensions_add_hunt_audit"
);
framework_add_hook (
  "extensions_edit_hunt_audit",
  "extensions_edit_hunt_audit"
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
framework_add_hook (
  "extensions_remove_hunt_audit",
  "extensions_remove_hunt_audit"
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Description`, `Number` FROM `Extensions` LEFT JOIN `ExtensionHunt` ON `ExtensionHunt`.`Hunt` = `Extensions`.`ID` WHERE `ExtensionHunt`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
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
 * Function to extend extensions addition/edition sanitize of group hunt type.
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
  $hunttypes = "";
  foreach ( $_in["hunts"] as $hunttype)
  {
    $hunttypes .= ", '" . $_in["mysql"]["id"]->real_escape_string ( substr ( $hunttype, 10)) . "'";
  }
  $hunttypes = substr ( $hunttypes, 2);
  $tmp = $buffer["Hunts"];
  $buffer["Hunts"] = array ();
  foreach ( $tmp as $hunt)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number` FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $hunt) . " AND `Type` IN (" . $hunttypes . ")"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $buffer["Hunts"][$hunt] = intval ( $result->fetch_assoc ()["Number"]);
  }

  /**
   * If it's an edition, fetch old extensions
   */
  if ( array_key_exists ( "ORIGINAL", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Number` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Hunt` = `Extensions`.`ID` WHERE `ExtensionHunt`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
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
 * Function to extend extensions addition/edition validate of group hunt type.
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
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionHunt` (`Extension`, `Hunt`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $hunt) . ")"))
    {
      framework_call ( "extensions_add_abort", $parameters);
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
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
 * Function to extend extensions audit addition of group hunt type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_hunt_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  $buffer["Extensions"] = $parameters["Hunts"];

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post edition of group hunt type.
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
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHunt` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      framework_call ( "extensions_edit_abort", $parameters);
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    foreach ( $parameters["Hunts"] as $hunt => $number)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionHunt` (`Extension`, `Hunt`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $hunt) . ")"))
      {
        framework_call ( "extensions_edit_abort", $parameters);
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
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
 * Function to extend extensions audit edition of group hunt type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_hunt_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  if ( ! array_compare_with_keys ( $parameters["ORIGINAL"]["Hunts"], $parameters["Hunts"]))
  {
    $add = array ();
    foreach ( $parameters["Hunts"] as $hunt => $number)
    {
      if ( ! array_key_exists ( $hunt, $parameters["ORIGINAL"]["Hunts"]))
      {
        $add[$hunt] = $number;
      }
    }
    $remove = array ();
    foreach ( $parameters["ORIGINAL"]["Hunts"] as $hunt => $number)
    {
      if ( ! array_key_exists ( $hunt, $parameters["Hunts"]))
      {
        $remove[$hunt] = $number;
      }
    }
    $buffer["Hunts"] = array ( "Old" => $parameters["ORIGINAL"]["Hunts"], "New" => $parameters["Hunts"], "Add" => $add, "Remove" => $remove);
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
   * Get extension hunts to audit record
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Number` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Hunt` = `Extensions`.`ID` WHERE `ExtensionHunt`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $buffer["ORIGINAL"]["Hunts"] = array ();
  while ( $hunt = $result->fetch_assoc ())
  {
    $buffer["ORIGINAL"]["Hunts"][$hunt["ID"]] = $hung["Number"];
  }

  /**
   * Remove extension hunts from database
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHunt` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Number" => $buffer["ORIGINAL"]["Number"]);
  if ( framework_has_hook ( "extension_hunt_remove_notify"))
  {
    $notify = framework_call ( "extension_hunt_remove_notify", $parameters, false, $notify);
  }
  notify_server ( $buffer["ORIGINAL"]["Server"], "extension_hunt_remove", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions audit remotion of group hunt type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove_hunt_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  $buffer["Hunts"] = $parameters["ORIGINAL"]["Hunts"];

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

  /**
   * Search for extension hunts that has the changed extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionHunt`.`Hunt`, `ExtensionHunt`.`Extension`, `Extensions`.`Number`, `Extensions`.`Description` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Extension` = `Extensions`.`ID` WHERE `ExtensionHunt`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $hunt = $result->fetch_assoc ())
  {
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`Extensions`.`Number` SEPARATOR ',') AS `Extensions` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Extension` = `Extensions`.`ID` WHERE `ExtensionHunt`.`Hunt` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $hunt["Hunt"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $notify = array ( "Number" => $hunt["Number"], "NewNumber" => $hunt["Number"], "Description" => $hunt["Description"], "Extensions" => explode ( ",", $result2->fetch_assoc ()["Extensions"]));
    if ( framework_has_hook ( "extension_hunt_edit_notify"))
    {
      $notify = framework_call ( "extension_hunt_edit_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["Range"]["Server"], "extension_hunt_change", $notify);
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

  /**
   * Search for extension hunts that has the removed extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionHunt`.`Hunt`, `ExtensionHunt`.`Extension`, `Extensions`.`Number`, `Extensions`.`Description` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Extension` = `Extensions`.`ID` WHERE `ExtensionHunt`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHunt` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $hunt = $result->fetch_assoc ())
  {
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`Extensions`.`Number` SEPARATOR ',') AS `Extensions` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Extension` = `Extensions`.`ID` WHERE `ExtensionHunt`.`Hunt` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $hunt["Hunt"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $hunts = $result2->fetch_assoc ()["Extensions"];
    if ( $hunts == "")
    {
      framework_call ( "extensions_remove", array ( "ID" => $hunt["Hunt"]));
    } else {
      $notify = array ( "Number" => $hunt["Number"], "NewNumber" => $hunt["Number"], "Description" => $hunt["Description"], "Extensions" => explode ( ",", $hunts));
      if ( framework_has_hook ( "extension_hunt_edit_notify"))
      {
        $notify = framework_call ( "extension_hunt_edit_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["Range"]["Server"], "extension_hunt_change", $notify);
    }
  }

  /**
   * Check if the removed extesion exist into any other extension hunt and remove it
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionHunt`.`Hunt`, `ExtensionHunt`.`Extension`, `Extensions`.`Number`, `Extensions`.`Description` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Extension` = `Extensions`.`ID` WHERE `ExtensionHunt`.`Hunt` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionHunt` WHERE `Hunt` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $hunt = $result->fetch_assoc ())
  {
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`Extensions`.`Number` SEPARATOR ',') AS `Extensions` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Extension` = `Extensions`.`ID` WHERE `ExtensionHunt`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $hunt["Extension"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $hunts = $result2->fetch_assoc ()["Extensions"];
    if ( $hunts == "")
    {
      framework_call ( "extensions_remove", array ( "ID" => $hunt["Hunt"]));
    } else {
      $notify = array ( "Number" => $hunt["Number"], "NewNumber" => $hunt["Number"], "Description" => $hunt["Description"], "Extensions" => explode ( ",", $hunts));
      if ( framework_has_hook ( "extension_hunt_edit_notify"))
      {
        $notify = framework_call ( "extension_hunt_edit_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["Range"]["Server"], "extension_hunt_change", $notify);
    }
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
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT (SELECT `Number` FROM `Extensions` WHERE `ID` = `ExtensionHunt`.`Extension`) AS `Number`, `Extensions`.`Description`, GROUP_CONCAT(`Extensions`.`Number` SEPARATOR ',') AS `Extensions` FROM `ExtensionHunt` LEFT JOIN `Extensions` ON `ExtensionHunt`.`Extension` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `Extensions`.`Type` = 'hunt' AND `Ranges`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]) . " GROUP BY `ExtensionHunt`.`Extension`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
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
