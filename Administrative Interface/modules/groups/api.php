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
 * VoIP Domain groups api module. This module add the api calls related to
 * groups.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Groups
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search groups
 */
framework_add_hook ( "groups_search", "groups_search");
framework_add_permission ( "groups_search", __ ( "Gateways search (select list standard)"));
framework_add_api_call ( "/groups/search", "Read", "groups_search", array ( "permissions" => array ( "user", "groups_search")));

/**
 * Function to generate groups list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Groups");

  /**
   * Search groups
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` " . ( ! empty ( $parameters["q"]) ? "WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' " : "") . "ORDER BY `Description`, `Code`"))
  {
    while ( $group = $result->fetch_assoc ())
    {
      $data[] = array ( $group["ID"], $group["Description"]);
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to fetch groups listing
 */
framework_add_hook ( "groups_fetch", "groups_fetch");
framework_add_permission ( "groups_fetch", __ ( "Request groups listing"));
framework_add_api_call ( "/groups/fetch", "Read", "groups_fetch", array ( "permissions" => array ( "user", "groups_fetch")));

/**
 * Function to generate group list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Groups", "CostCenters"));

  /**
   * Search groups
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT `Groups`.*, `CostCenters`.`Description` AS `CostCenter` FROM `Groups` LEFT JOIN `CostCenters` ON `Groups`.`CostCenter` = `CostCenters`.`ID` GROUP BY `Groups`.`ID`"))
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
    $allocations = filters_call ( "count_extensions", array ( "group" => $result["ID"]));
    $total = 0;
    foreach ( $allocations as $allocation)
    {
      $total += $allocation;
    }
    $data[] = array ( $result["ID"], $result["Description"], $result["Code"], $result["CostCenter"], $total);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get group information
 */
framework_add_hook ( "groups_view", "groups_view");
framework_add_permission ( "groups_view", __ ( "View groups informations"));
framework_add_api_call ( "/groups/:id", "Read", "groups_view", array ( "permissions" => array ( "user", "groups_view")));

/**
 * Function to generate group informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Groups", "CostCenters"));

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search groups
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Groups`.*, `CostCenters`.`Description` AS `CostCenterDescription`, `CostCenters`.`Code` AS `CostCenterCode` FROM `Groups` LEFT JOIN `CostCenters` ON `Groups`.`CostCenter` = `CostCenters`.`ID` WHERE `Groups`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"]) . " GROUP BY `Groups`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $group = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $group["Description"];
  $data["code"] = $group["Code"];
  $data["costcenter"] = $group["CostCenter"];
  $data["costcenterdescription"] = $group["CostCenterDescription"] . " (" . $group["CostCenterCode"] . ")";

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new group
 */
framework_add_hook ( "groups_add", "groups_add");
framework_add_permission ( "groups_add", __ ( "Add groups"));
framework_add_api_call ( "/groups", "Create", "groups_add", array ( "permissions" => array ( "user", "groups_add")));

/**
 * Function to add a new group.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_add ( $buffer, $parameters)
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
    $data["description"] = __ ( "The group description is required.");
  }
  $parameters["code"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["code"])));
  if ( empty ( $parameters["code"]))
  {
    $data["result"] = false;
    $data["code"] = __ ( "The group code is required.");
  }
  $parameters["costcenter"] = (int) $parameters["costcenter"];
  if ( empty ( $parameters["costcenter"]))
  {
    $data["result"] = false;
    $data["costcenter"] = __ ( "The cost center is required.");
  }

  /**
   * Check if code was in use
   */
  if ( ! array_key_exists ( "code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["code"] = __ ( "The informed code is already in use.");
    }
  }

  /**
   * Check if cost center exist
   */
  if ( ! array_key_exists ( "costcenter", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["costcenter"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["result"] = false;
      $data["costcenter"] = __ ( "Invalid cost center.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "groups_add_sanitize"))
  {
    $data = framework_call ( "groups_add_sanitize", $parameters, false, $data);
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
   * Add new group record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Groups` (`Description`, `Code`, `CostCenter`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["costcenter"]) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "groups_add_post"))
  {
    framework_call ( "groups_add_post", $parameters);
  }

  /**
   * Add new group at Asterisk servers
   */
  $notify = array ( "ID" => $parameters["id"], "Code" => $parameters["code"], "CostCenter" => $parameters["costcenter"]);
  if ( framework_has_hook ( "groups_add_notify"))
  {
    $notify = framework_call ( "groups_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "creategroup", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Code" => $parameters["code"], "CostCenter" => $parameters["costcenter"]);
  if ( framework_has_hook ( "groups_add_audit"))
  {
    $audit = framework_call ( "groups_add_audit", $parameters, false, $audit);
  }
  audit ( "group", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "groups/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing group
 */
framework_add_hook ( "groups_edit", "groups_edit");
framework_add_permission ( "groups_edit", __ ( "Edit groups"));
framework_add_api_call ( "/groups/:id", "Modify", "groups_edit", array ( "permissions" => array ( "user", "groups_edit")));
framework_add_api_call ( "/groups/:id", "Edit", "groups_edit", array ( "permissions" => array ( "user", "groups_edit")));

/**
 * Function to edit an existing group.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_edit ( $buffer, $parameters)
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
    $data["description"] = __ ( "The group description is required.");
  }
  $parameters["code"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["code"])));
  if ( empty ( $parameters["code"]))
  {
    $data["result"] = false;
    $data["code"] = __ ( "The group code is required.");
  }
  $parameters["costcenter"] = (int) $parameters["costcenter"];
  if ( empty ( $parameters["costcenter"]))
  {
    $data["result"] = false;
    $data["costcenter"] = __ ( "The cost center is required.");
  }

  /**
   * Check if code was in use
   */
  if ( ! array_key_exists ( "code", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["code"] = __ ( "The informed code is already in use.");
    }
  }

  /**
   * Check if cost center exist
   */
  if ( ! array_key_exists ( "costcenter", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["costcenter"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      $data["result"] = false;
      $data["costcenter"] = __ ( "Invalid cost center.");
    }
  }

  /**
   * Check if group exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $group = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "groups_edit_sanitize"))
  {
    $data = framework_call ( "groups_edit_sanitize", $parameters, false, $data);
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
   * Change group record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Groups` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["code"]) . "', `CostCenter` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["costcenter"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "groups_edit_post"))
  {
    framework_call ( "groups_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "ID" => $parameters["id"], "Code" => $group["Code"], "NewCode" => $parameters["code"], "CostCenter" => $parameters["costcenter"]);
  if ( framework_has_hook ( "groups_edit_notify"))
  {
    $notify = framework_call ( "groups_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "changegroup", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $parameters["id"];
  if ( $group["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $group["Description"], "New" => $parameters["description"]);
  }
  if ( $group["Code"] != $parameters["code"])
  {
    $audit["Code"] = array ( "Old" => $group["Code"], "New" => $parameters["code"]);
  }
  if ( $group["CostCenter"] != $parameters["costcenter"])
  {
    $audit["CostCenter"] = array ( "Old" => $group["CostCenter"], "New" => $parameters["costcenter"]);
  }
  if ( framework_has_hook ( "groups_edit_audit"))
  {
    $audit = framework_call ( "groups_edit_audit", $parameters, false, $audit);
  }
  audit ( "group", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a group
 */
framework_add_hook ( "groups_remove", "groups_remove");
framework_add_permission ( "groups_remove", __ ( "Remove groups"));
framework_add_api_call ( "/groups/:id", "Delete", "groups_remove", array ( "permissions" => array ( "user", "groups_remove")));

/**
 * Function to remove an existing group.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if group exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $group = $result->fetch_assoc ();

  /**
   * Check if group has any extension
   */
  $allocations = filters_call ( "count_extensions", array ( "group" => $paramters["id"]));
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
   * Remove group database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "groups_remove_post"))
  {
    framework_call ( "groups_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Code" => $group["Code"]);
  if ( framework_has_hook ( "groups_remove_notify"))
  {
    $notify = framework_call ( "groups_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "removegroup", $notify);

  /**
   * Insert audit registry
   */
  $audit = $group;
  if ( framework_has_hook ( "groups_remove_audit"))
  {
    $audit = framework_call ( "groups_remove_audit", $parameters, false, $audit);
  }
  audit ( "group", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to intercept new server
 */
framework_add_hook ( "servers_add_post", "groups_servers_add_post");

/**
 * Function to notify new server to include all groups.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function groups_servers_add_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all groups and send to new server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $group = $result->fetch_assoc ())
  {
    $notify = array ( "ID" => $group["ID"], "Code" => $group["Code"], "CostCenter" => $group["CostCenter"]);
    if ( framework_has_hook ( "groups_add_notify"))
    {
      $notify = framework_call ( "groups_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["id"], "creategroup", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
