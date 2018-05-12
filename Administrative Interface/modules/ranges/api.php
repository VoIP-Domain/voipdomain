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
 * VoIP Domain ranges api module. This module add the api calls related to
 * ranges.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Ranges
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search ranges (datatables compatible response)
 */
framework_add_hook ( "ranges_search", "ranges_search");
framework_add_permission ( "ranges_search", __ ( "Ranges search (datatables standard)"));
framework_add_api_call ( "/ranges/search", "Read", "ranges_search", array ( "permissions" => array ( "user", "ranges_search")));

/**
 * Function to generate range list to select box.
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
   * Check for modifications time
   */
  check_table_modification ( array ( "Ranges", "Servers"));

  /**
   * Search ranges
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `Ranges`.`ID`, `Ranges`.`Description`, `Servers`.`Name` FROM `Ranges`, `Servers` WHERE `Ranges`.`Server` = `Servers`.`ID` " . ( ! empty ( $parameters["q"]) ? " AND `Ranges`.`Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%'" : "") . " ORDER BY `Description`"))
  {
    while ( $range = $result->fetch_assoc ())
    {
      $data[] = array ( $range["ID"], $range["Description"] . " (" . $range["Name"] . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch ranges listing
 */
framework_add_hook ( "ranges_fetch", "ranges_fetch");
framework_add_permission ( "ranges_fetch", __ ( "Request ranges listing"));
framework_add_api_call ( "/ranges/fetch", "Read", "ranges_fetch", array ( "permissions" => array ( "user", "ranges_fetch")));

/**
 * Function to generate range list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Ranges", "Servers"));

  /**
   * Search ranges
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Ranges`.*, `Servers`.`Name` FROM `Ranges` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID`"))
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
    $allocations = filters_call ( "count_allocations", array ( "range" => $result["ID"]));
    $total = 0;
    foreach ( $allocations as $allocation)
    {
      $total += $allocation;
    }
    $data[] = array ( $result["ID"], $result["Description"], $result["Name"], $result["Start"], $result["Finish"], $total);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get range information
 */
framework_add_hook ( "ranges_view", "ranges_view");
framework_add_permission ( "ranges_view", __ ( "View ranges informations"));
framework_add_api_call ( "/ranges/:id", "Read", "ranges_view", array ( "permissions" => array ( "user", "ranges_view")));

/**
 * Function to generate range informations.
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
   * Check for modifications time
   */
  check_table_modification ( array ( "Ranges", "Servers"));

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search ranges
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Ranges`.*, `Servers`.`Name` FROM `Ranges` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID` WHERE `Ranges`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $range = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $range["Description"];
  $data["server"] = $range["Server"];
  $data["servername"] = $range["Name"];
  $data["start"] = $range["Start"];
  $data["finish"] = $range["Finish"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new range
 */
framework_add_hook ( "ranges_add", "ranges_add");
framework_add_permission ( "ranges_add", __ ( "Add ranges"));
framework_add_api_call ( "/ranges", "Create", "ranges_add", array ( "permissions" => array ( "user", "ranges_add")));

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
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["description"])));
  if ( empty ( $parameters["description"]))
  {
    $data["result"] = false;
    $data["description"] = __ ( "The range description is required.");
  }
  $parameters["server"] = (int) $parameters["server"];
  if ( empty ( $parameters["server"]))
  {
    $data["result"] = false;
    $data["server"] = __ ( "The server is required.");
  }
  if ( $parameters["start"] != (int) $parameters["start"])
  {
    $data["result"] = false;
    $data["start"] = __ ( "The start is invalid.");
  }
  $parameters["start"] = (int) $parameters["start"];
  if ( $parameters["finish"] != (int) $parameters["finish"])
  {
    $data["result"] = false;
    $data["finish"] = __ ( "The finish is invalid.");
  }
  $parameters["finish"] = (int) $parameters["finish"];
  if ( ! array_key_exists ( "start", $data) && ! array_key_exists ( "finish", $data) && $parameters["start"] > $parameters["finish"])
  {
    $data["result"] = false;
    $data["start"] = __ ( "The range start is greater than finish.");
  }

  /**
   * Search server
   */
  if ( ! array_key_exists ( "server", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["server"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $data["result"] = false;
      $data["server"] = __ ( "The selected server is invalid.");
    }
    $server = $result->fetch_assoc ();
  }

  /**
   * Check if range didn't overlap other ranges
   */
  if ( ! array_key_exists ( "start", $data) && ! array_key_exists ( "finish", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE " . $_in["mysql"]["id"]->real_escape_string ( $parameters["finish"]) . " >= `Start` AND `Finish` >= " . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["start"] = __ ( "Values override existing range.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "ranges_add_sanitize"))
  {
    $data = framework_call ( "ranges_add_sanitize", $parameters, false, $data);
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
   * Add new range record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Ranges` (`Description`, `Server`, `Start`, `Finish`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["server"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $parameters["finish"]) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "ranges_add_post"))
  {
    framework_call ( "ranges_add_post", $parameters);
  }

  /**
   * Add new range at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["id"], "Server" => $parameters["server"], "Start" => $parameters["start"], "Finish" => $parameters["finish"]);
  if ( framework_has_hook ( "ranges_add_notify"))
  {
    $notify = framework_call ( "ranges_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "createrange", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Server" => $parameters["server"], "Start" => $parameters["start"], "Finish" => $parameters["finish"]);
  if ( framework_has_hook ( "ranges_add_audit"))
  {
    $audit = framework_call ( "ranges_add_audit", $parameters, false, $audit);
  }
  audit ( "range", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "ranges/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing range
 */
framework_add_hook ( "ranges_edit", "ranges_edit");
framework_add_permission ( "ranges_edit", __ ( "Edit ranges"));
framework_add_api_call ( "/ranges/:id", "Modify", "ranges_edit", array ( "permissions" => array ( "user", "ranges_edit")));
framework_add_api_call ( "/ranges/:id", "Edit", "ranges_edit", array ( "permissions" => array ( "user", "ranges_edit")));

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
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["description"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["description"])));
  if ( empty ( $parameters["description"]))
  {
    $data["result"] = false;
    $data["description"] = __ ( "The range description is required.");
  }
  $parameters["server"] = (int) $parameters["server"];
  if ( empty ( $parameters["server"]))
  {
    $data["result"] = false;
    $data["server"] = __ ( "The server is required.");
  }
  if ( $parameters["start"] != (int) $parameters["start"])
  {
    $data["result"] = false;
    $data["start"] = __ ( "The start is invalid.");
  }
  $parameters["start"] = (int) $parameters["start"];
  if ( $parameters["finish"] != (int) $parameters["finish"])
  {
    $data["result"] = false;
    $data["finish"] = __ ( "The finish is invalid.");
  }
  $parameters["finish"] = (int) $parameters["finish"];
  if ( ! array_key_exists ( "start", $data) && ! array_key_exists ( "finish", $data) && $parameters["start"] > $parameters["finish"])
  {
    $data["result"] = false;
    $data["start"] = __ ( "The range start is greater than finish.");
  }

  /**
   * Search server
   */
  if ( ! array_key_exists ( "server", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["server"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $data["result"] = false;
      $data["server"] = __ ( "The selected server is invalid.");
    }
    $server = $result->fetch_assoc ();
  }

  /**
   * Check if range exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $range = $result->fetch_assoc ();

  /**
   * Check if range didn't overlap other ranges
   */
  if ( ! array_key_exists ( "start", $data) && ! array_key_exists ( "finish", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE " . $_in["mysql"]["id"]->real_escape_string ( $parameters["finish"]) . " >= `Start` AND `Finish` >= " . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . " AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["start"] = __ ( "Values override existing range.");
    }
  }

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "ranges_edit_sanitize"))
  {
    $data = framework_call ( "ranges_edit_sanitize", $parameters, false, $data);
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
   * Change range record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Ranges` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Server` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["server"]) . ", `Start` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["start"]) . ", `Finish` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["finish"]) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "ranges_edit_post"))
  {
    framework_call ( "ranges_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  if ( $range["Server"] != $parameters["server"] || $range["Start"] != $parameters["start"] || $range["Finish"] != $parameters["finish"])
  {
    $notify = array ( "ID" => $parameters["id"], "Server" => $parameters["server"], "Start" => $parameters["start"], "Finish" => $parameters["finish"]);
    if ( framework_has_hook ( "ranges_edit_notify"))
    {
      $notify = framework_call ( "ranges_edit_notify", $parameters, false, $notify);
    }
    notify_server ( 0, "changerange", $notify);
  }

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $parameters["id"];
  if ( $range["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $range["Description"], "New" => $parameters["description"]);
  }
  if ( $range["Server"] != $parameters["server"])
  {
    $audit["Server"] = array ( "Old" => $range["Server"], "New" => $parameters["server"]);
  }
  if ( $range["Start"] != $parameters["start"])
  {
    $audit["Start"] = array ( "Old" => $range["Start"], "New" => $parameters["start"]);
  }
  if ( $range["Finish"] != $parameters["finish"])
  {
    $audit["Finish"] = array ( "Old" => $range["Finish"], "New" => $parameters["finish"]);
  }
  if ( framework_has_hook ( "ranges_edit_audit"))
  {
    $audit = framework_call ( "ranges_edit_audit", $parameters, false, $audit);
  }
  audit ( "range", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a range
 */
framework_add_hook ( "ranges_remove", "ranges_remove");
framework_add_permission ( "ranges_remove", __ ( "Remove ranges"));
framework_add_api_call ( "/ranges/:id", "Delete", "ranges_remove", array ( "permissions" => array ( "user", "ranges_remove")));

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
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if range exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $range = $result->fetch_assoc ();

  /**
   * Check if range has any allocated number
   */
  $allocations = filters_call ( "count_allocations", array ( "range" => $range["ID"]));
  $total = 0;
  foreach ( $allocations as $allocation)
  {
    $total += $allocation;
  }
  if ( $total != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    exit ();
  }

  /**
   * Remove range database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Ranges` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "ranges_remove_post"))
  {
    framework_call ( "ranges_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["id"]);
  if ( framework_has_hook ( "ranges_remove_notify"))
  {
    $notify = framework_call ( "ranges_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "removerange", $notify);

  /**
   * Insert audit registry
   */
  $audit = $range;
  if ( framework_has_hook ( "ranges_remove_audit"))
  {
    $audit = framework_call ( "ranges_remove_audit", $parameters, false, $audit);
  }
  audit ( "range", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to intercept new server
 */
framework_add_hook ( "servers_add_post", "ranges_servers_add_post");

/**
 * Function to notify new server to include all ranges.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_servers_add_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all ranges and send to new server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Ranges`"))
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
    notify_server ( $parameters["id"], "createrange", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
