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
 * VoIP Domain blocks api module. This module add the api calls related to
 * blocks.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Blocks
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch blocks listing
 */
framework_add_hook ( "blocks_fetch", "blocks_fetch");
framework_add_permission ( "blocks_fetch", __ ( "Request block listing"));
framework_add_api_call ( "/blocks/fetch", "Read", "blocks_fetch", array ( "permissions" => array ( "user", "blocks_fetch")));

/**
 * Function to generate block list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Blocks");

  /**
   * Search blocks
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks`"))
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
 * API call to get block information
 */
framework_add_hook ( "blocks_view", "blocks_view");
framework_add_permission ( "blocks_view", __ ( "View block informations"));
framework_add_api_call ( "/blocks/:id", "Read", "blocks_view", array ( "permissions" => array ( "user", "blocks_view")));

/**
 * Function to generate block informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Blocks");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search blocks
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $block = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["description"] = $block["Description"];
  $data["number"] = $block["Number"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new block
 */
framework_add_hook ( "blocks_add", "blocks_add");
framework_add_permission ( "blocks_add", __ ( "Add blocks"));
framework_add_api_call ( "/blocks", "Create", "blocks_add", array ( "permissions" => array ( "user", "blocks_add")));

/**
 * Function to add a new block.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_add ( $buffer, $parameters)
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
    $data["description"] = __ ( "The block description is required.");
  }
  $parameters["number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["number"])));
  if ( empty ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The block number is required.");
  }
  if ( ! validateE164 ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The number must be in E.164 format, including the prefix +.");
  }

  /**
   * Check if number was already added
   */
  if ( ! array_key_exists ( "number", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["number"] = __ ( "The provided number was already in use.");
    }
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "blocks_add_sanitize"))
  {
    $data = framework_call ( "blocks_add_sanitize", $parameters, false, $data);
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
   * Call add pre hook, if exist
   */
  if ( framework_has_hook ( "blocks_add_pre"))
  {
    $parameters = framework_call ( "blocks_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new block record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Blocks` (`Description`, `Number`) VALUES ('" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "')"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "blocks_add_post"))
  {
    framework_call ( "blocks_add_post", $parameters);
  }

  /**
   * Add new block at Asterisk servers
   */
  $notify = array ( "Description" => $parameters["description"], "Number" => $parameters["number"]);
  if ( framework_has_hook ( "blocks_add_notify"))
  {
    $notify = framework_call ( "blocks_add_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "createblock", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Description" => $parameters["description"], "Number" => $parameters["number"]);
  if ( framework_has_hook ( "blocks_add_audit"))
  {
    $audit = framework_call ( "blocks_add_audit", $parameters, false, $audit);
  }
  audit ( "block", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "blocks/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing block
 */
framework_add_hook ( "blocks_edit", "blocks_edit");
framework_add_permission ( "blocks_edit", __ ( "Edit blocks"));
framework_add_api_call ( "/blocks/:id", "Modify", "blocks_edit", array ( "permissions" => array ( "user", "blocks_edit")));
framework_add_api_call ( "/blocks/:id", "Edit", "blocks_edit", array ( "permissions" => array ( "user", "blocks_edit")));

/**
 * Function to edit an existing block.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_edit ( $buffer, $parameters)
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
    $data["description"] = __ ( "The block description is required.");
  }
  $parameters["number"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["number"])));
  if ( empty ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The block number is required.");
  }
  if ( ! validateE164 ( $parameters["number"]))
  {
    $data["result"] = false;
    $data["number"] = __ ( "The number must be in E.164 format, including the prefix +.");
  }

  /**
   * Check if number was already added
   */
  if ( ! array_key_exists ( "number", $data))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $data["result"] = false;
      $data["number"] = __ ( "The provided number was already in use.");
    }
  }

  /**
   * Check if block exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $block = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "blocks_edit_sanitize"))
  {
    $data = framework_call ( "blocks_edit_sanitize", $parameters, false, $data);
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
   * Call edit pre hook, if exist
   */
  if ( framework_has_hook ( "blocks_edit_pre"))
  {
    $parameters = framework_call ( "blocks_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change block record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Blocks` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Number` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["number"]) . "' WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "blocks_edit_post"))
  {
    framework_call ( "blocks_edit_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Description" => $parameters["description"], "Number" => $block["Number"], "NewNumber" => $parameters["number"]);
  if ( framework_has_hook ( "blocks_edit_notify"))
  {
    $notify = framework_call ( "blocks_edit_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "changeblock", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $parameters["id"];
  if ( $block["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $block["Description"], "New" => $parameters["description"]);
  }
  if ( $block["Number"] != $parameters["number"])
  {
    $audit["Number"] = array ( "Old" => $block["Number"], "New" => $parameters["number"]);
  }
  if ( framework_has_hook ( "blocks_edit_audit"))
  {
    $audit = framework_call ( "blocks_edit_audit", $parameters, false, $audit);
  }
  audit ( "block", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a block
 */
framework_add_hook ( "blocks_remove", "blocks_remove");
framework_add_permission ( "blocks_remove", __ ( "Remove blocks"));
framework_add_api_call ( "/blocks/:id", "Delete", "blocks_remove", array ( "permissions" => array ( "user", "blocks_remove")));

/**
 * Function to remove an existing block.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if block exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $block = $result->fetch_assoc ();

  /**
   * Call remove pre hook, if exist
   */
  if ( framework_has_hook ( "blocks_remove_pre"))
  {
    $parameters = framework_call ( "blocks_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove block database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Blocks` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "blocks_remove_post"))
  {
    framework_call ( "blocks_remove_post", $parameters);
  }

  /**
   * Notify servers about change
   */
  $notify = array ( "Number" => $block["Number"]);
  if ( framework_has_hook ( "blocks_remove_notify"))
  {
    $notify = framework_call ( "blocks_remove_notify", $parameters, false, $notify);
  }
  notify_server ( 0, "removeblock", $notify);

  /**
   * Insert audit registry
   */
  $audit = $block;
  if ( framework_has_hook ( "blocks_remove_audit"))
  {
    $audit = framework_call ( "blocks_remove_audit", $parameters, false, $audit);
  }
  audit ( "block", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to intercept new server and server reinstall
 */
framework_add_hook ( "servers_add_post", "blocks_server_reconfig");
framework_add_hook ( "servers_reinstall_config", "blocks_server_reconfig");

/**
 * Function to notify server to include all blocks.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function blocks_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all blocks and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Blocks`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $block = $result->fetch_assoc ())
  {
    $notify = array ( "Description" => $block["Description"], "Number" => $block["Number"]);
    if ( framework_has_hook ( "blocks_add_notify"))
    {
      $notify = framework_call ( "blocks_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["id"], "createblock", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
