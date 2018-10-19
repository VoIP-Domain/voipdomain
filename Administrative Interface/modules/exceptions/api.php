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
 * VoIP Domain exceptions api module. This module add the api calls related to
 * exceptions.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Exceptions
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch exceptions listing
 */
framework_add_hook ( "exceptions_fetch", "exceptions_fetch");
framework_add_permission ( "exceptions_fetch", __ ( "Request exceptions list"));
framework_add_api_call ( "/exceptions/fetch", "Read", "exceptions_fetch", array ( "permissions" => array ( "user", "exceptions_fetch")));

/**
 * Function to generate exception list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Exceptions");

  /**
   * Search exceptions
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions`"))
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
    $data[] = array ( $result["ID"], $result["Description"], $result["Number"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get exception information
 */
framework_add_hook ( "exceptions_view", "exceptions_view");
framework_add_permission ( "exceptions_view", __ ( "View exceptions informations"));
framework_add_api_call ( "/exceptions/:id", "Read", "exceptions_view", array ( "permissions" => array ( "user", "exceptions_view")));

/**
 * Function to generate exception informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Exceptions");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search exceptions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $exception = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $exception["Description"];
  $data["number"] = $exception["Number"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new exception
 */
framework_add_hook ( "exceptions_add", "exceptions_add");
framework_add_permission ( "exceptions_add", __ ( "Add exceptions"));
framework_add_api_call ( "/exceptions", "Create", "exceptions_add", array ( "permissions" => array ( "user", "exceptions_add")));

/**
 * Function to add a new exception.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_add ( $buffer, $parameters)
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
    $data["description"] = __ ( "The exception description is required.");
  }
  $parameters["number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["number"])));
  if ( empty ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The exception number is required.");
  }
  if ( ! validateE164 ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The number must be in E.164 format, including the + prefix.");
  }

  /**
   * Check if exception already exist
   */
  if ( ! array_key_exists ( "number", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["number"] = __ ( "The number entered is already registered in the system.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "exceptions_add_sanitize"))
  {
    $data = framework_call ( "exceptions_add_sanitize", $parameters, false, $data);
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
   * Add new exception record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Exceptions` (`Description`, `Number`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "exceptions_add_post"))
  {
    framework_call ( "exceptions_add_post", $parameters);
  }

  /**
   * Add new exception at Asterisk servers
   */
  $notify = array ( "Description" => $parameters["description"], "Number" => $parameters["number"]);
  if ( framework_has_hook ( "exceptions_add_notify"))
  {
    $notify = framework_call ( "exceptions_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "createexception", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Number" => $parameters["number"]);
  if ( framework_has_hook ( "exceptions_add_audit"))
  {
    $audit = framework_call ( "exceptions_add_audit", $parameters, false, $audit);
  }
  audit ( "exception", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "exceptions/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing exception
 */
framework_add_hook ( "exceptions_edit", "exceptions_edit");
framework_add_permission ( "exceptions_edit", __ ( "Edit exceptions"));
framework_add_api_call ( "/exceptions/:id", "Modify", "exceptions_edit", array ( "permissions" => array ( "user", "exceptions_edit")));
framework_add_api_call ( "/exceptions/:id", "Edit", "exceptions_edit", array ( "permissions" => array ( "user", "exceptions_edit")));

/**
 * Function to edit an existing exception.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_edit ( $buffer, $parameters)
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
    $data["description"] = __ ( "The exception description is required.");
  }
  $parameters["number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["number"])));
  if ( empty ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The exception number is required.");
  }
  if ( ! validateE164 ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The number must be in E.164 format, including the + prefix.");
  }

  /**
   * Check if exception already exist
   */
  if ( ! array_key_exists ( "number", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["number"] = __ ( "The number entered is already registered in the system.");
    }
  }

  /**
   * Check if exception exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $exception = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "exceptions_edit_sanitize"))
  {
    $data = framework_call ( "exceptions_edit_sanitize", $parameters, false, $data);
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
   * Change exception record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Exceptions` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "exceptions_edit_post"))
  {
    framework_call ( "exceptions_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Description" => $parameters["description"], "Number" => $exception["Number"], "NewNumber" => $parameters["number"]);
  if ( framework_has_hook ( "exceptions_edit_notify"))
  {
    $notify = framework_call ( "exceptions_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "changeexception", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $parameters["id"];
  if ( $exception["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $exception["Description"], "New" => $parameters["description"]);
  }
  if ( $exception["Number"] != $parameters["number"])
  {
    $audit["Number"] = array ( "Old" => $exception["Number"], "New" => $parameters["number"]);
  }
  if ( framework_has_hook ( "exceptions_edit_audit"))
  {
    $audit = framework_call ( "exceptions_edit_audit", $parameters, false, $audit);
  }
  audit ( "exception", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a exception
 */
framework_add_hook ( "exceptions_remove", "exceptions_remove");
framework_add_permission ( "exceptions_remove", __ ( "Remove exceptions"));
framework_add_api_call ( "/exceptions/:id", "Delete", "exceptions_remove", array ( "permissions" => array ( "user", "exceptions_remove")));

/**
 * Function to remove an existing exception.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if exception exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $exception = $result->fetch_assoc ();

  /**
   * Remove exception database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Exceptions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "exceptions_remove_post"))
  {
    framework_call ( "exceptions_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Number" => $exception["Number"]);
  if ( framework_has_hook ( "exceptions_remove_notify"))
  {
    $notify = framework_call ( "exceptions_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "removeexception", $notify);

  /**
   * Insert audit registry
   */
  $audit = $exception;
  if ( framework_has_hook ( "exceptions_remove_audit"))
  {
    $audit = framework_call ( "exceptions_remove_audit", $parameters, false, $audit);
  }
  audit ( "exception", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to intercept new server and server reinstall
 */
framework_add_hook ( "servers_add_post", "exceptions_server_reconfig");
framework_add_hook ( "servers_reinstall_config", "exceptions_server_reconfig");

/**
 * Function to notify server to include all exceptions.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function exceptions_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all exceptions and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Exceptions`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $exception = $result->fetch_assoc ())
  {
    $notify = array ( "Description" => $exception["Description"], "Number" => $exception["Number"]);
    if ( framework_has_hook ( "exceptions_add_notify"))
    {
      $notify = framework_call ( "exceptions_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["id"], "createexception", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
