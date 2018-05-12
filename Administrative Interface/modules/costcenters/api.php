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
 * VoIP Domain costcenters api module. This module add the api calls related to
 * costcenters.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Cost Centers
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search costcenters
 */
framework_add_hook ( "costcenters_search", "costcenters_search");
framework_add_permission ( "costcenters_search", __ ( "Search cost centers (select list standard)"));
framework_add_api_call ( "/costcenters/search", "Read", "costcenters_search", array ( "permissions" => array ( "user", "costcenters_search")));

/**
 * Function to generate cost centers list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "CostCenters");

  /**
   * Search cost centers
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` " . ( ! empty ( $parameters["q"]) ? "WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' OR `Code` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' " : "") . "ORDER BY `Description`, `Code`"))
  {
    while ( $costcenter = $result->fetch_assoc ())
    {
      $data[] = array ( $costcenter["ID"], $costcenter["Description"] . " (" . $costcenter["Code"] . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch costcenters listing
 */
framework_add_hook ( "costcenters_fetch", "costcenters_fetch");
framework_add_permission ( "costcenters_fetch", __ ( "Request costs centers listing"));
framework_add_api_call ( "/costcenters/fetch", "Read", "costcenters_fetch", array ( "permissions" => array ( "user", "costcenters_fetch")));

/**
 * Function to generate costcenter list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "CostCenters");

  /**
   * Search costcenters
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters`"))
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
    $data[] = array ( $result["ID"], $result["Description"], $result["Code"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get costcenter information
 */
framework_add_hook ( "costcenters_view", "costcenters_view");
framework_add_permission ( "costcenters_view", __ ( "View costs centers informations"));
framework_add_api_call ( "/costcenters/:id", "Read", "costcenters_view", array ( "permissions" => array ( "user", "costcenters_view")));

/**
 * Function to generate costcenter informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "CostCenters");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search costcenters
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $costcenter = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $costcenter["Description"];
  $data["code"] = $costcenter["Code"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new costcenter
 */
framework_add_hook ( "costcenters_add", "costcenters_add");
framework_add_permission ( "costcenters_add", __ ( "Add costs centers"));
framework_add_api_call ( "/costcenters", "Create", "costcenters_add", array ( "permissions" => array ( "user", "costcenters_add")));

/**
 * Function to add a new costcenter.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_add ( $buffer, $parameters)
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
    $data["description"] = __ ( "The cost center description is required.");
  }
  if ( $parameters["code"] != (int) $parameters["code"])
  {
    $data["result"] = false;
    $data["code"] = __ ( "The informed code is invalid.");
  }
  $parameters["code"] = (int) $parameters["code"];
  if ( ! array_key_exists ( "code", $data) && empty ( $parameters["code"]))
  {
    $data["result"] = false;
    $data["code"] = __ ( "The cost center code is required.");
  }

  /**
   * Check if code was in use
   */
  if ( ! array_key_exists ( "code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `Code` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["code"] = __ ( "The code was already in use.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "costcenters_add_sanitize"))
  {
    $data = framework_call ( "costcenters_add_sanitize", $parameters, false, $data);
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
   * Add new costcenter record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `CostCenters` (`Description`, `Code`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "costcenters_add_post"))
  {
    framework_call ( "costcenters_add_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Code" => $parameters["code"]);
  if ( framework_has_hook ( "costcenters_add_audit"))
  {
    $audit = framework_call ( "costcenters_add_audit", $parameters, false, $audit);
  }
  audit ( "costcenter", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "costcenters/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing costcenter
 */
framework_add_hook ( "costcenters_edit", "costcenters_edit");
framework_add_permission ( "costcenters_edit", __ ( "Edit costs centers"));
framework_add_api_call ( "/costcenters/:id", "Modify", "costcenters_edit", array ( "permissions" => array ( "user", "costcenters_edit")));
framework_add_api_call ( "/costcenters/:id", "Edit", "costcenters_edit", array ( "permissions" => array ( "user", "costcenters_edit")));

/**
 * Function to edit an existing costcenter.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_edit ( $buffer, $parameters)
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
    $data["description"] = __ ( "The cost center description is required.");
  }
  if ( $parameters["code"] != (int) $parameters["code"])
  {
    $data["result"] = false;
    $data["code"] = __ ( "The informed code is invalid.");
  }
  $parameters["code"] = (int) $parameters["code"];
  if ( ! array_key_exists ( "code", $data) && empty ( $parameters["code"]))
  {
    $data["result"] = false;
    $data["code"] = __ ( "The cost center code is required.");
  }

  /**
   * Check if code was in use
   */
  if ( ! array_key_exists ( "code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `Code` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . " AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["code"] = __ ( "The code was already in use.");
    }
  }

  /**
   * Check if cost center exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $costcenter = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "costcenters_edit_sanitize"))
  {
    $data = framework_call ( "costcenters_edit_sanitize", $parameters, false, $data);
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
   * Change cost center record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `CostCenters` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Code` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "costcenters_edit_post"))
  {
    framework_call ( "costcenters_edit_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $parameters["id"];
  if ( $costcenter["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $costcenter["Description"], "New" => $parameters["description"]);
  }
  if ( $costcenter["Code"] != $parameters["code"])
  {
    $audit["Code"] = array ( "Old" => $costcenter["Code"], "New" => $parameters["code"]);
  }
  if ( framework_has_hook ( "costcenters_edit_audit"))
  {
    $audit = framework_call ( "costcenters_edit_audit", $parameters, false, $audit);
  }
  audit ( "costcenter", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a costcenter
 */
framework_add_hook ( "costcenters_remove", "costcenters_remove");
framework_add_permission ( "costcenters_remove", __ ( "Remove costs centers"));
framework_add_api_call ( "/costcenters/:id", "Delete", "costcenters_remove", array ( "permissions" => array ( "user", "costcenters_remove")));

/**
 * Function to remove an existing costcenter.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenters_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if costcenter exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $costcenter = $result->fetch_assoc ();

  /**
   * Remove costcenter database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "costcenters_remove_post"))
  {
    framework_call ( "costcenters_remove_post", $parameters);
  }

  /**
   * Insert audit registry
   */
  $audit = $costcenter;
  if ( framework_has_hook ( "costcenters_remove_audit"))
  {
    $audit = framework_call ( "costcenters_remove_audit", $parameters, false, $audit);
  }
  audit ( "costcenter", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}
?>
