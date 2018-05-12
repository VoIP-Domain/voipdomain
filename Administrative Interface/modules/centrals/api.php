<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * VoIP Domain centrals api module. This module add the api calls related to
 * centrals.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Centrals
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search centrals
 */
framework_add_hook ( "centrals_search", "centrals_search");
framework_add_permission ( "centrals_search", __ ( "Centrals search (select list standard)"));
framework_add_api_call ( "/centrals/search", "Read", "centrals_search", array ( "permissions" => array ( "user", "centrals_search")));

/**
 * Function to generate central list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Centrals");

  /**
   * Search centrals
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `ID`, `Name`, `Extension` FROM `Centrals` " . ( ! empty ( $parameters["q"]) ? "WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' OR `Extension` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' " : "") . "ORDER BY `Name`, `Extension`"))
  {
    while ( $central = $result->fetch_assoc ())
    {
      $data[] = array ( $central["ID"], $central["Name"] . " (" . $central["Extension"] . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch centrals listing
 */
framework_add_hook ( "centrals_fetch", "centrals_fetch");
framework_add_permission ( "centrals_fetch", __ ( "Request centrals listing"));
framework_add_api_call ( "/centrals/fetch", "Read", "centrals_fetch", array ( "permissions" => array ( "user", "centrals_fetch")));

/**
 * Function to generate central list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Centrals", "CentralExtension"));

  /**
   * Search centrals
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Centrals`.*, COUNT(`CentralExtension`.`Extension`) AS `Extensions` FROM `Centrals` RIGHT JOIN `CentralExtension` ON `Centrals`.`ID` = `CentralExtension`.`Central` GROUP BY `Extension`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create table structure
   */
  $data = array ();
  while ( $result = $results->fetch_assoc ())
  {
    $data[] = array ( $result["ID"], $result["Extension"], $result["Name"], $result["Extensions"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get central information
 */
framework_add_hook ( "centrals_view", "centrals_view");
framework_add_permission ( "centrals_view", __ ( "View centrals informations"));
framework_add_api_call ( "/centrals/:id", "Read", "centrals_view", array ( "permissions" => array ( "user", "centrals_view")));

/**
 * Function to generate central informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Centrals", "CentralExtension", "Extensions"));

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search centrals
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Centrals` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $central = $result->fetch_assoc ();

  /**
   * Search extensions for the central
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Name`, `Extensions`.`Extension` FROM `CentralExtension` LEFT JOIN `Extensions` ON `CentralExtension`.`Extension` = `Extensions`.`ID` WHERE `CentralExtension`.`Central` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extensions = array ();
  $extensionsName = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    $extensions[$extension["ID"]] = strip_tags ( $extension["Name"]) . " (" . $extension["Extension"] . ")";
  }

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["extension"] = $central["Extension"];
  $data["name"] = $central["Name"];
  $data["extensions"] = $extensions;

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new central
 */
framework_add_hook ( "centrals_add", "centrals_add");
framework_add_permission ( "centrals_add", __ ( "Add centrals"));
framework_add_api_call ( "/centrals", "Create", "centrals_add", array ( "permissions" => array ( "user", "centrals_add")));

/**
 * Function to add a new central.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_add ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  if ( $parameters["extension"] != (int) $parameters["extension"])
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The provided extension is invalid.");
  }
  $parameters["extension"] = (int) $parameters["extension"];
  if ( empty ( $parameters["extension"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The extension is required.");
  }
  $parameters["name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["name"])));
  if ( empty ( $parameters["name"]))
  {
    $data["result"] = false;
    $data["name"] = __ ( "The name is required.");
  }
  if ( ! is_array ( $parameters["extensions"]))
  {
    if ( ! empty ( $parameters["extensions"]))
    {
      $parameters["extensions"] = array ( $parameters["extensions"]);
    } else {
      $parameters["extensions"] = array ();
    }
  }
  foreach ( $parameters["extensions"] as $key => $value)
  {
    $parameters["extensions"][$key] = (int) $value;
  }
  if ( sizeof ( $parameters["extensions"]) == 0)
  {
    $data["result"] = false;
    $data["extensions"] = __ ( "At least one extension is required.");
  }

  /**
   * Check if extension number is inside a valid range
   */
  if ( ! array_key_exists ( "extension", $data))
  {
    $range = filters_call ( "search_range", array ( "number" => $parameters["extension"]));
    if ( sizeof ( $range) == 0)
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension isn't inside a valid system range.");
    }
  }

  /**
   * Check if extensions exists
   */
  if ( ! array_key_exists ( "extensions", $data))
  {
    $extensions = array ();
    foreach ( $parameters["extensions"] as $extension)
    {
      $extension = filters_call ( "get_extensions", array ( "id" => $extension));
      if ( sizeof ( $extension) != 1)
      {
        $data["result"] = false;
        $data["extensions"] = __ ( "The selected extension is invalid.");
      } else {
        $extensions[] = $extension[0]["Record"]["Extension"];
      }
    }
  }

  /**
   * Check if extension number was already in use
   */
  if ( ! array_key_exists ( "extension", $data))
  {
    $check = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
    if ( sizeof ( $check) != 0)
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension is already in use.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "centrals_add_sanitize"))
  {
    $data = framework_call ( "centrals_add_sanitize", $parameters, false, $data);
  }

  /**
   * Return error data if some error ocurred
   */
  if ( $data["result"] == false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Add new central record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Centrals` (`Extension`, `Name`, `Range`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["extension"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $range["ID"]) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Add each central extension
   */
  foreach ( $parameters["extensions"] as $extension)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `CentralExtension` (`Central`, `Extension`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $extension) . ")"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "centrals_add_post"))
  {
    framework_call ( "centrals_add_post", $parameters);
  }

  /**
   * Add new central at Asterisk servers
   */
  $notify = array ( "Extension" => $parameters["extension"], "Name" => $parameters["name"], "Extensions" => $extensions);
  if ( framework_has_hook ( "centrals_add_notify"))
  {
    $notify = framework_call ( "centrals_add_notify", $parameters, false, $notify);
  }
  notify_server ( $range["Server"], "createcentral", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Extension" => $parameters["extension"], "Name" => $parameters["name"], "Extensions" => $extensions);
  if ( framework_has_hook ( "centrals_add_audit"))
  {
    $audit = framework_call ( "centrals_add_audit", $parameters, false, $audit);
  }
  audit ( "central", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "centrals/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing central
 */
framework_add_hook ( "centrals_edit", "centrals_edit");
framework_add_permission ( "centrals_edit", __ ( "Edit centrals"));
framework_add_api_call ( "/centrals/:id", "Modify", "centrals_edit", array ( "permissions" => array ( "user", "centrals_edit")));
framework_add_api_call ( "/centrals/:id", "Edit", "centrals_edit", array ( "permissions" => array ( "user", "centrals_edit")));

/**
 * Function to edit an existing central.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_edit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  if ( $parameters["extension"] != (int) $parameters["extension"])
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The provided extension is invalid.");
  }
  $parameters["extension"] = (int) $parameters["extension"];
  if ( empty ( $parameters["extension"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The extension is required.");
  }
  $parameters["name"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["name"])));
  if ( empty ( $parameters["name"]))
  {
    $data["result"] = false;
    $data["name"] = __ ( "The name is required.");
  }
  if ( ! is_array ( $parameters["extensions"]))
  {
    if ( ! empty ( $parameters["extensions"]))
    {
      $parameters["extensions"] = array ( $parameters["extensions"]);
    } else {
      $parameters["extensions"] = array ();
    }
  }
  foreach ( $parameters["extensions"] as $key => $value)
  {
    $parameters["extensions"][$key] = (int) $value;
  }
  if ( sizeof ( $parameters["extensions"]) == 0)
  {
    $data["result"] = false;
    $data["extensions"] = __ ( "At least one extension is required.");
  }

  /**
   * Check if extension number is inside a valid range
   */
  if ( ! array_key_exists ( "extension", $data))
  {
    $range = filters_call ( "search_range", array ( "number" => $parameters["extension"]));
    if ( sizeof ( $range) == 0)
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension isn't inside a valid system range.");
    }
  }

  /**
   * Check if extensions exists
   */
  if ( ! array_key_exists ( "extensions", $data))
  {
    $extensions = array ();
    foreach ( $parameters["extensions"] as $extension)
    {
      $extension = filters_call ( "get_extensions", array ( "id" => $extension));
      if ( sizeof ( $extension) != 1)
      {
        $data["result"] = false;
        $data["extensions"] = __ ( "The selected extension is invalid.");
      } else {
        $extensions[] = $extension[0]["Record"]["Extension"];
      }
    }
  }

  /**
   * Check if central exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Centrals` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $central = $result->fetch_assoc ();

  /**
   * Check if extension number was already in use
   */
  if ( ! array_key_exists ( "extension", $data) && $parameters["extension"] != $central["Extension"])
  {
    $check = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
    if ( sizeof ( $check) != 0)
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension is already in use.");
    }
  }

  /**
   * Return error data if some error ocurred
   */
  if ( $data["result"] == false)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Check the actual central extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Extension` FROM `CentralExtension` LEFT JOIN `Extensions` ON `CentralExtension`.`Extension` = `Extensions`.`ID` WHERE `CentralExtension`.`Central` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $dbextensions = array ();
  $dbextensionsindex = array ();
  while ( $extension = $result->fetch_assoc ())
  {
    $dbextensions[] = $extension["ID"];
    $dbextensionsindex[$extension["ID"]] = $extension["Extension"];
  }

  /**
   * Change central record
   */
  if ( $parameters["name"] != $central["Name"])
  {
    if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Centrals` SET `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["extension"]) . ", `Name` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["name"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Check if there's extension change, if changed, update database
   */
  if ( ! array_compare ( $dbextensions, $parameters["extensions"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `CentralExtension` WHERE `Central` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    foreach ( $parameters["extensions"] as $extension)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `CentralExtension` (`Central`, `Extension`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $extension) . ")"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
    }
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "centrals_edit_post"))
  {
    framework_call ( "centrals_add_post", $parameters);
  }

  /**
   * Notify server about change
   */
  if ( $central["Server"] == $range["Server"])
  {
    $notify = array ( "Extension" => $central["Extension"], "NewExtension" => $parameters["extension"], "Name" => $parameters["name"], "Extensions" => $extensions);
    if ( framework_has_hook ( "centrals_edit_notify"))
    {
      $notify = framework_call ( "centrals_edit_notify", $parameters, false, $notify);
    }
    notify_server ( $range["Server"], "changecentral", $notify);
  } else {
    $notify = array ( "Extension" => $central["Extension"]);
    if ( framework_has_hook ( "centrals_remove_notify"))
    {
      $notify = framework_call ( "centrals_remove_notify", $parameters, false, $notify);
    }
    notify_server ( $central["Server"], "removecentral", $notify);
    $notify = array ( "Extension" => $parameters["extension"], "Name" => $parameters["name"], "Extensions" => $extensions);
    if ( framework_has_hook ( "centrals_add_notify"))
    {
      $notify = framework_call ( "centrals_add_notify", $parameters, false, $notify);
    }
    notify_server ( $range["Server"], "createcentral", $notify);
  }

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $central["ID"];
  if ( $parameters["extension"] != $central["Extension"])
  {
    $audit["Extension"] = array ( "Old" => $central["Extension"], "New" => $parameters["extension"]);
  }
  if ( $parameters["name"] != $central["Name"])
  {
    $audit["Name"] = array ( "Old" => $central["Name"], "New" => $parameters["name"]);
  }
  if ( ! array_compare ( $dbextensions, $parameters["extensions"]))
  {
    $add = array ();
    foreach ( $parameters["extensions"] as $extension)
    {
      if ( ! array_key_exists ( $extension, $dbextensionsindex))
      {
        $add[] = $extensionsindex[$extension];
      }
    }
    $remove = array ();
    foreach ( $dbextensions as $extension)
    {
      if ( ! array_key_exists ( $extension, $extensionsindex))
      {
        $remove[] = $dbextensionsindex[$extension];
      }
    }
    $audit["Extensions"] = array ( "Old" => $dbextensions, "New" => $parameters["extensions"], "Add" => $add, "Remove" => $remove);
  }
  if ( framework_has_hook ( "centrals_edit_audit"))
  {
    $audit = framework_call ( "centrals_edit_audit", $parameters, false, $audit);
  }
  audit ( "central", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a central
 */
framework_add_hook ( "centrals_remove", "centrals_remove");
framework_add_permission ( "centrals_remove", __ ( "Remove centrals"));
framework_add_api_call ( "/centrals/:id", "Delete", "centrals_remove", array ( "permissions" => array ( "user", "centrals_remove")));

/**
 * Function to remove an existing central.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if central exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Centrals` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $central = $result->fetch_assoc ();

  /**
   * Get central extensions to audit record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Extension` FROM `CentralExtension` LEFT JOIN `Extensions` ON `CentralExtension`.`Extension` = `Extensions`.`ID` WHERE `CentralExtension`.`Central` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $extensions = array ();
  while ( $tmp = $result->fetch_assoc ())
  {
    $extensions[] = $tmp["Extension"];
  }

  /**
   * Get central range
   */
  $range = filters_call ( "search_range", array ( "number" => $central["Extension"]));
  if ( sizeof ( $range) == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove central database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Centrals` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "centrals_remove_post"))
  {
    framework_call ( "centrals_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Extension" => $central["Extension"]);
  if ( framework_has_hook ( "centrals_remove_notify"))
  {
    $notify = framework_call ( "centrals_remove_notify", $parameters, false, $notify);
  }
  notify_server ( $range["Server"], "removecentral", $notify);

  /**
   * Insert audit registry
   */
  $audit = $central;
  $audit["Extensions"] = $extensions;
  if ( framework_has_hook ( "centrals_remove_audit"))
  {
    $audit = framework_call ( "centrals_remove_audit", $parameters, false, $audit);
  }
  audit ( "central", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to generate central call's report
 */
framework_add_hook ( "centrals_report", "centrals_report");
framework_add_permission ( "centrals_report", __ ( "Centrals usage report"));
framework_add_api_call ( "/centrals/:id/report", "Read", "centrals_report", array ( "permissions" => array ( "user", "centrals_report")));

/**
 * Function to generate report data.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function centrals_report ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanityze input data
   */
  $parameters["start"] = format_form_datetime ( empty ( $parameters["start"]) ? date ( __ ( "m/d/Y") . " 00:00", strtotime ( "29 days ago")) : urldecode ( $parameters["start"]));
  $parameters["end"] = format_form_datetime ( empty ( $parameters["end"]) ? date ( __ ( "m/d/Y") . " 23:59", time ()) : urldecode ( $parameters["end"]));
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Get extension informations
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Centrals` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $central = $result->fetch_assoc ();

  /**
   * Get call records from database
   */
  if ( ! $records = @$_in["mysql"]["id"]->query ( "SELECT * FROM `cdr` WHERE `dst` = '" . $_in["mysql"]["id"]->real_escape_string ( $central["Extension"]) . "' AND `calldate` >= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . "' AND `calldate` <= '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["end"]) . "' ORDER BY `calldate` DESC"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Process each record
   */
  $output = array ();
  while ( $data = $records->fetch_assoc ())
  {
    $data["extension"] = $extension["Extension"];
    $output[] = filters_call ( "process_call", $data);
  }

  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}
?>
