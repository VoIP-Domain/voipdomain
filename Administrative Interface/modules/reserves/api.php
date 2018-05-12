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
 * VoIP Domain reserves api module. This module add the api calls related to
 * reserves.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Reserves
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch reserves listing
 */
framework_add_hook ( "reserves_fetch", "reserves_fetch");
framework_add_permission ( "reserves_fetch", __ ( "Request reserves listing"));
framework_add_api_call ( "/reserves/fetch", "Read", "reserves_fetch", array ( "permissions" => array ( "user", "reserves_fetch")));

/**
 * Function to generate reserve list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reserves_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Reserves");

  /**
   * Search reserves
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Reserves`"))
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
    $data[] = array ( $result["ID"], $result["Description"], $result["Extension"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get reserve information
 */
framework_add_hook ( "reserves_view", "reserves_view");
framework_add_permission ( "reserves_view", __ ( "View reserves informations"));
framework_add_api_call ( "/reserves/:id", "Read", "reserves_view", array ( "permissions" => array ( "user", "reserves_view")));

/**
 * Function to generate reserve informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reserves_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Reserves");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search reserves
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Reserves` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $reserve = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $reserve["Description"];
  $data["extension"] = $reserve["Extension"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new reserve
 */
framework_add_hook ( "reserves_add", "reserves_add");
framework_add_permission ( "reserves_add", __ ( "Add reserves"));
framework_add_api_call ( "/reserves", "Create", "reserves_add", array ( "permissions" => array ( "user", "reserves_add")));

/**
 * Function to add a new reserve.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reserves_add ( $buffer, $parameters)
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
    $data["description"] = __ ( "The reserve description is required.");
  }
  $parameters["extension"] = (int) $parameters["extension"];
  if ( empty ( $parameters["extension"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The reserve number is required.");
  }

  /**
   * Check if extension was in use
   */
  $check = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $check) != 0)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "Extension already in use.");
  }

  /**
   * Get server range
   */
  $range = filters_call ( "search_range", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $range) == 0)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "Invalid extension.");
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "reserves_add_sanitize"))
  {
    $data = framework_call ( "reserves_add_sanitize", $parameters, false, $data);
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
   * Add new reserve record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Reserves` (`Description`, `Extension`, `Range`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["extension"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $range["ID"]) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "reserves_add_post"))
  {
    framework_call ( "reserves_add_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Extension" => $parameters["extension"]);
  if ( framework_has_hook ( "reserves_add_audit"))
  {
    $audit = framework_call ( "reserves_add_audit", $parameters, false, $audit);
  }
  audit ( "reserve", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "reserves/" . $id . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing reserve
 */
framework_add_hook ( "reserves_edit", "reserves_edit");
framework_add_permission ( "reserves_edit", __ ( "Edit reserves"));
framework_add_api_call ( "/reserves/:id", "Modify", "reserves_edit", array ( "permissions" => array ( "user", "reserves_edit")));
framework_add_api_call ( "/reserves/:id", "Edit", "reserves_edit", array ( "permissions" => array ( "user", "reserves_edit")));

/**
 * Function to edit an existing reserve.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reserves_edit ( $buffer, $parameters)
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
    $data["description"] = __ ( "The reserve description is required.");
  }
  $parameters["extension"] = (int) $parameters["extension"];
  if ( empty ( $parameters["extension"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The reserve number is required.");
  }

  /**
   * Check if extension was in use
   */
  $check = filters_call ( "get_allocations", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $check) != 0 && ! ( $check[0]["Type"] == "Reserve" && $check[0]["Record"]["ID"] == $parameters["id"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "Extension already in use.");
  }

  /**
   * Get server range
   */
  $range = filters_call ( "search_range", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $range) == 0)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "Invalid extension.");
  }

  /**
   * Check if reserve exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Reserves` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $reserve = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "reserves_edit_sanitize"))
  {
    $data = framework_call ( "reserves_edit_sanitize", $parameters, false, $data);
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
   * Change reserve record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Reserves` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["extension"]) . ", `Range` = " . $_in["mysql"]["id"]->real_escape_string ( $range["ID"]) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "reserves_edit_post"))
  {
    framework_call ( "reserves_edit_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $parameters["id"];
  if ( $reserve["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $reserve["Description"], "New" => $parameters["description"]);
  }
  if ( $reserve["Extension"] != $parameters["extension"])
  {
    $audit["Extension"] = array ( "Old" => $reserve["Extension"], "New" => $parameters["extension"]);
  }
  if ( framework_has_hook ( "reserves_edit_audit"))
  {
    $audit = framework_call ( "reserves_edit_audit", $parameters, false, $audit);
  }
  audit ( "reserve", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a reserve
 */
framework_add_hook ( "reserves_remove", "reserves_remove");
framework_add_permission ( "reserves_remove", __ ( "Remove reserves"));
framework_add_api_call ( "/reserves/:id", "Delete", "reserves_remove", array ( "permissions" => array ( "user", "reserves_remove")));

/**
 * Function to remove an existing reserve.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reserves_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if reserve exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Reserves` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $reserve = $result->fetch_assoc ();

  /**
   * Remove reserve database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Reserves` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "reserves_remove_post"))
  {
    framework_call ( "reserves_remove_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = $reserve;
  if ( framework_has_hook ( "reserves_remove_audit"))
  {
    $audit = framework_call ( "reserves_remove_audit", $parameters, false, $audit);
  }
  audit ( "reserve", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}
?>
