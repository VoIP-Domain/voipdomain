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
 * VoIP Domain queues api module. This module add the api calls related to
 * queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Queues
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to fetch queues listing
 */
framework_add_hook ( "queues_fetch", "queues_fetch");
framework_add_permission ( "queues_fetch", __ ( "Request queue listing"));
framework_add_api_call ( "/queues/fetch", "Read", "queues_fetch", array ( "permissions" => array ( "user", "queues_fetch")));

/**
 * Function to generate queue list.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_fetch ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Queues");

  /**
   * Search queues
   */
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues`"))
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
    $data[] = array ( $result["ID"], $result["Extension"], $result["Description"]);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to search queues
 */
framework_add_hook ( "queues_search", "queues_search");
framework_add_permission ( "queues_search", __ ( "Search queues (select list standard)"));
framework_add_api_call ( "/queues/search", "Read", "queues_search", array ( "permissions" => array ( "user", "queues_search")));

/**
 * Function to generate queue list to select box.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Queues");

  /**
   * Search queues
   */
  $data = array ();
  if ( $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` " . ( ! empty ( $parameters["q"]) ? "WHERE `Description` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "%' OR `Extension` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["q"]) . "' " : "") . "ORDER BY `Description`"))
  {
    while ( $queue = $result->fetch_assoc ())
    {
      $data[] = array ( $queue["ID"], $queue["Description"] . " (" . $queue["Extension"] . ")");
    }
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get queue information
 */
framework_add_hook ( "queues_view", "queues_view");
framework_add_permission ( "queues_view", __ ( "View queue informations"));
framework_add_api_call ( "/queues/:id", "Read", "queues_view", array ( "permissions" => array ( "user", "queues_view")));

/**
 * Function to generate queue informations.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( "Queues");

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Search queues
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $queue = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["result"] = true;
  $data["extension"] = $queue["Extension"];
  $data["description"] = $queue["Description"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to add a new queue
 */
framework_add_hook ( "queues_add", "queues_add");
framework_add_permission ( "queues_add", __ ( "Add queues"));
framework_add_api_call ( "/queues", "Create", "queues_add", array ( "permissions" => array ( "user", "queues_add")));

/**
 * Function to add a new queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_add ( $buffer, $parameters)
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
    $data["description"] = __ ( "The queue description is required.");
  }
  $parameters["extension"] = (int) $parameters["extension"];
  if ( empty ( $parameters["extension"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The queue number is required.");
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
   * Get queue range
   */
  $range = filters_call ( "search_range", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $range) == 0)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "Invalid extension.");
  }

  /**
   * Check if queue was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["result"] = false;
    $data["description"] = __ ( "The provided description was already in use.");
  }

  /**
   * Call add sanitize hook, if exist
   */
  if ( framework_has_hook ( "queues_add_sanitize"))
  {
    $data = framework_call ( "queues_add_sanitize", $parameters, false, $data);
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
  if ( framework_has_hook ( "queues_add_pre"))
  {
    $parameters = framework_call ( "queues_add_pre", $parameters, false, $parameters);
  }

  /**
   * Add new queue record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `Queues` (`Extension`, `Description`, `Range`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["extension"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $range["ID"]) . ")"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $parameters["id"] = $_in["mysql"]["id"]->insert_id;

  /**
   * Call add post hook, if exist
   */
  if ( framework_has_hook ( "queues_add_post"))
  {
    framework_call ( "queues_add_post", $parameters);
  }

  /**
   * Add new queue at Asterisk server
   */
  $notify = array ( "Description" => $parameters["description"], "Extension" => $parameters["extension"]);
  if ( framework_has_hook ( "queues_add_notify"))
  {
    $notify = framework_call ( "queues_add_notify", $parameters, false, $notify);
  }
  notify_server ( $range["Server"], "createqueue", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "ID" => $parameters["id"], "Extension" => $parameters["extension"], "Description" => $parameters["description"], "Range" => $range["ID"]);
  if ( framework_has_hook ( "queues_add_audit"))
  {
    $audit = framework_call ( "queues_add_audit", $parameters, false, $audit);
  }
  audit ( "queue", "add", $audit);

  /**
   * Return OK to user
   */
  header ( $_SERVER["SERVER_PROTOCOL"] . " 201 Created");
  header ( "Location: " . $_in["general"]["baseurl"] . "queues/" . $parameters["id"] . "/view");
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to edit an existing queue
 */
framework_add_hook ( "queues_edit", "queues_edit");
framework_add_permission ( "queues_edit", __ ( "Edit queues"));
framework_add_api_call ( "/queues/:id", "Modify", "queues_edit", array ( "permissions" => array ( "user", "queues_edit")));
framework_add_api_call ( "/queues/:id", "Edit", "queues_edit", array ( "permissions" => array ( "user", "queues_edit")));

/**
 * Function to edit an existing queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_edit ( $buffer, $parameters)
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
    $data["description"] = __ ( "The queue description is required.");
  }
  $parameters["extension"] = (int) $parameters["extension"];
  if ( empty ( $parameters["extension"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The queue number is required.");
  }

  /**
   * Check if extension was in use
   */
  $check = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $check) != 0 && ! ( $check[0]["Type"] == "Queue" && $check[0]["Record"]["ID"] == $parameters["id"]))
  {
    $data["result"] = false;
    $data["extension"] = __ ( "Extension already in use.");
  }

  /**
   * Get queue range
   */
  $range = filters_call ( "search_range", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $range) == 0)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "Invalid extension.");
  }

  /**
   * Check if queue was already added
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "' AND `ID` != " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data["result"] = false;
    $data["description"] = __ ( "The provided description was already in use.");
  }

  /**
   * Check if queue exist (could be removed by other user meanwhile)
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $queue = $result->fetch_assoc ();

  /**
   * Call edit sanitize hook, if exist
   */
  if ( framework_has_hook ( "queues_edit_sanitize"))
  {
    $data = framework_call ( "queues_edit_sanitize", $parameters, false, $data);
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
  if ( framework_has_hook ( "queues_edit_pre"))
  {
    $parameters = framework_call ( "queues_edit_pre", $parameters, false, $parameters);
  }

  /**
   * Change queue record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `Queues` SET `Description` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["description"]) . "', `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["extension"]) . ", `Range` = " . $_in["mysql"]["id"]->real_escape_string ( $range["ID"]) . " WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call edit post hook, if exist
   */
  if ( framework_has_hook ( "queues_edit_post"))
  {
    framework_call ( "queues_edit_post", $parameters);
  }

  /**
   * Notify server about change
   */
  $oldrange = filters_call ( "search_range", array ( "number" => $queue["Extension"]));
  if ( $oldrange["Server"] == $range["Server"])
  {
    $notify = array ( "Extension" => $queue["Extension"], "NewExtension" => $parameters["extension"], "Description" => $parameters["description"]);
    if ( framework_has_hook ( "queues_edit_notify"))
    {
      $notify = framework_call ( "queues_edit_notify", $parameters, false, $notify);
    }
    notify_server ( $range["Server"], "changequeue", $notify);
  } else {
    $notify = array ( "Extension" => $queue["Extension"]);
    if ( framework_has_hook ( "queues_remove_notify"))
    {
      $notify = framework_call ( "queues_remove_notify", $parameters, false, $notify);
    }
    notify_server ( $oldrange["Server"], "removequeue", $notify);
    $notify = array ( "Extension" => $parameters["extension"], "Description" => $parameters["description"]);
    if ( framework_has_hook ( "queues_add_notify"))
    {
      $notify = framework_call ( "queues_add_notify", $parameters, false, $notify);
    }
    notify_server ( $range["Server"], "createqueue", $notify);
  }

  /**
   * Insert audit registry
   */
  $audit = array ();
  $audit["ID"] = $parameters["id"];
  if ( $queue["Description"] != $parameters["description"])
  {
    $audit["Description"] = array ( "Old" => $queue["Description"], "New" => $parameters["description"]);
  }
  if ( $queue["Extension"] != $parameters["extension"])
  {
    $audit["Extension"] = array ( "Old" => $queue["Extension"], "New" => $parameters["extension"]);
  }
  if ( framework_has_hook ( "queues_edit_audit"))
  {
    $audit = framework_call ( "queues_edit_audit", $parameters, false, $audit);
  }
  audit ( "queue", "edit", $audit);

  /**
   * Return OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to remove a queue
 */
framework_add_hook ( "queues_remove", "queues_remove");
framework_add_permission ( "queues_remove", __ ( "Remove queues"));
framework_add_api_call ( "/queues/:id", "Delete", "queues_remove", array ( "permissions" => array ( "user", "queues_remove")));

/**
 * Function to remove an existing queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_remove ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["id"] = (int) $parameters["id"];

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $queue = $result->fetch_assoc ();

  /**
   * Get queue range
   */
  $range = filters_call ( "search_range", array ( "number" => $queue["Extension"]));
  if ( sizeof ( $range) == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove pre hook, if exist
   */
  if ( framework_has_hook ( "queues_remove_pre"))
  {
    $parameters = framework_call ( "queues_remove_pre", $parameters, false, $parameters);
  }

  /**
   * Remove queue database record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `Queues` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Call remove post hook, if exist
   */
  if ( framework_has_hook ( "queues_remove_post"))
  {
    framework_call ( "queues_remove_post", $parameters);
  }

  /**
   * Notify server about change
   */
  $notify = array ( "Extension" => $queue["Extension"]);
  if ( framework_has_hook ( "queues_remove_notify"))
  {
    $notify = framework_call ( "queues_remove_notify", $parameters, false, $notify);
  }
  notify_server ( $range["Server"], "removequeue", $notify);

  /**
   * Insert audit registry
   */
  $audit = $queue;
  if ( framework_has_hook ( "queues_remove_audit"))
  {
    $audit = framework_call ( "queues_remove_audit", $parameters, false, $audit);
  }
  audit ( "queue", "remove", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to log an extension into queue
 */
framework_add_hook ( "queues_login", "queues_login");
framework_add_permission ( "queues_login", __ ( "Add extension to queues"));
framework_add_api_call ( "/queues/:id/login", "Create", "queues_login", array ( "permissions" => array ( "user", "queues_login")));

/**
 * Function to log an extension into queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_login ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["extension"] = (int) $parameters["extension"];
  $parameters["agent"] = (int) $parameters["agent"];

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Queues`.*, `Ranges`.`Server` FROM `Queues` LEFT JOIN `Ranges` ON `Queues`.`Range` = `Ranges`.`ID` WHERE `Queues`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["result"] = false;
    $data["queue"] = __ ( "The provided queue doesn't exist.");
  }
  $queue = $result->fetch_assoc ();

  /**
   * Check if extension exists
   */
  $extension = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $extension) != 1)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The provided extension doesn't exist.");
  }

  /**
   * Check for agent if provided
   */
  if ( $parameters["agent"])
  {
    $agent = filters_call ( "get_agents", array ( "code" => $parameters["agent"]));
    if ( sizeof ( $agent) != 1)
    {
      $data["result"] = false;
      $data["agent"] = __ ( "The provided agent doesn't exist.");
    } else {
      if ( $agent[0]["Password"] != $parameters["password"])
      {
        $data["result"] = false;
        $data["agent"] = __ ( "The provided agent password didn't match.");
      }
    }
  } else {
    /**
     * Check if password match (if not using agent)
     */
    if ( ! array_key_exists ( "extension", $data) && $extension[0]["Record"]["Password"] != $parameters["password"])
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension password didn't match.");
    }
  }

  /**
   * Check if extension is from the same server as queue
   */
  if ( ! array_key_exists ( "extension", $data) && $extension[0]["Record"]["Server"] != $queue["Server"])
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The extension must be at same server where queue is running.");
  }

  /**
   * Call login sanitize hook, if exist
   */
  if ( framework_has_hook ( "queues_login_sanitize"))
  {
    $data = framework_call ( "queues_login_sanitize", $parameters, false, $data);
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
   * Call login post hook, if exist
   */
  if ( framework_has_hook ( "queues_login_post"))
  {
    framework_call ( "queues_login_post", $parameters);
  }

  /**
   * Notify server about login
   */
  $notify = array ( "Queue" => $queue["Extension"], "Extension" => $parameters["extension"]);
  if ( $parameters["agent"])
  {
    $notify["Agent"] = $parameters["agent"];
  }
  if ( framework_has_hook ( "queues_login_notify"))
  {
    $notify = framework_call ( "queues_login_notify", $parameters, false, $notify);
  }
  notify_server ( $queue["Server"], "joinqueue", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "Queue" => $parameters["id"], "Extension" => $parameters["extension"]);
  if ( $parameters["agent"])
  {
    $audit["Agent"] = $parameters["agent"];
  }
  if ( framework_has_hook ( "queues_login_audit"))
  {
    $audit = framework_call ( "queues_login_audit", $parameters, false, $audit);
  }
  audit ( "queue", "login", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to pause a member
 */
framework_add_hook ( "queues_pause", "queues_pause");
framework_add_permission ( "queues_pause", __ ( "Pause member on a queue"));
framework_add_api_call ( "/queues/:id/pause", "Create", "queues_pause", array ( "permissions" => array ( "user", "queues_pause")));

/**
 * Function to pause a member from a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_pause ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["extension"] = (int) $parameters["extension"];
  $parameters["agent"] = (int) $parameters["agent"];

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Queues`.*, `Ranges`.`Server` FROM `Queues` LEFT JOIN `Ranges` ON `Queues`.`Range` = `Ranges`.`ID` WHERE `Queues`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["result"] = false;
    $data["queue"] = __ ( "The provided queue doesn't exist.");
  }
  $queue = $result->fetch_assoc ();

  /**
   * Check if extension exists
   */
  $extension = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $extension) != 1)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The provided extension doesn't exist.");
  }

  /**
   * Check for agent if provided
   */
  if ( $parameters["agent"])
  {
    $agent = filters_call ( "get_agents", array ( "code" => $parameters["agent"]));
    if ( sizeof ( $agent) != 1)
    {
      $data["result"] = false;
      $data["agent"] = __ ( "The provided agent doesn't exist.");
    } else {
      if ( $agent[0]["Password"] != $parameters["password"])
      {
        $data["result"] = false;
        $data["agent"] = __ ( "The provided agent password didn't match.");
      }
    }
  } else {
    /**
     * Check if password match (if not using agent)
     */
    if ( ! array_key_exists ( "extension", $data) && $extension[0]["Record"]["Password"] != $parameters["password"])
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension password didn't match.");
    }
  }

  /**
   * Call pause sanitize hook, if exist
   */
  if ( framework_has_hook ( "queues_pause_sanitize"))
  {
    $data = framework_call ( "queues_pause_sanitize", $parameters, false, $data);
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
   * Call pause post hook, if exist
   */
  if ( framework_has_hook ( "queues_pause_post"))
  {
    framework_call ( "queues_pause_post", $parameters);
  }

  /**
   * Notify server about change
   */
  $notify = array ( "Queue" => $queue["Extension"], "Extension" => $parameters["extension"]);
  if ( $parameters["agent"])
  {
    $notify["Agent"] = $parameters["agent"];
  }
  if ( framework_has_hook ( "queues_pause_notify"))
  {
    $notify = framework_call ( "queues_pause_notify", $parameters, false, $notify);
  }
  notify_server ( $queue["Server"], "pausequeue", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "Queue" => $parameters["id"], "Extension" => $parameters["extension"]);
  if ( $parameters["agent"])
  {
    $audit["Agent"] = $parameters["agent"];
  }
  if ( framework_has_hook ( "queues_pause_audit"))
  {
    $audit = framework_call ( "queues_pause_audit", $parameters, false, $audit);
  }
  audit ( "queue", "pause", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to unpause a member
 */
framework_add_hook ( "queues_unpause", "queues_unpause");
framework_add_permission ( "queues_unpause", __ ( "Unpause member on a queue"));
framework_add_api_call ( "/queues/:id/unpause", "Create", "queues_unpause", array ( "permissions" => array ( "user", "queues_unpause")));

/**
 * Function to unpause a member from a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_unpause ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["extension"] = (int) $parameters["extension"];
  $parameters["agent"] = (int) $parameters["agent"];

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Queues`.*, `Ranges`.`Server` FROM `Queues` LEFT JOIN `Ranges` ON `Queues`.`Range` = `Ranges`.`ID` WHERE `Queues`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["result"] = false;
    $data["queue"] = __ ( "The provided queue doesn't exist.");
  }
  $queue = $result->fetch_assoc ();

  /**
   * Check if extension exists
   */
  $extension = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $extension) != 1)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The provided extension doesn't exist.");
  }

  /**
   * Check for agent if provided
   */
  if ( $parameters["agent"])
  {
    $agent = filters_call ( "get_agents", array ( "code" => $parameters["agent"]));
    if ( sizeof ( $agent) != 1)
    {
      $data["result"] = false;
      $data["agent"] = __ ( "The provided agent doesn't exist.");
    } else {
      if ( $agent[0]["Password"] != $parameters["password"])
      {
        $data["result"] = false;
        $data["agent"] = __ ( "The provided agent password didn't match.");
      }
    }
  } else {
    /**
     * Check if password match (if not using agent)
     */
    if ( ! array_key_exists ( "extension", $data) && $extension[0]["Record"]["Password"] != $parameters["password"])
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension password didn't match.");
    }
  }

  /**
   * Call unpause sanitize hook, if exist
   */
  if ( framework_has_hook ( "queues_unpause_sanitize"))
  {
    $data = framework_call ( "queues_unpause_sanitize", $parameters, false, $data);
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
   * Call unpause post hook, if exist
   */
  if ( framework_has_hook ( "queues_unpause_post"))
  {
    framework_call ( "queues_unpause_post", $parameters);
  }

  /**
   * Notify server about change
   */
  $notify = array ( "Queue" => $queue["Extension"], "Extension" => $parameters["extension"]);
  if ( $parameters["agent"])
  {
    $notify["Agent"] = $parameters["agent"];
  }
  if ( framework_has_hook ( "queues_unpause_notify"))
  {
    $notify = framework_call ( "queues_unpause_notify", $parameters, false, $notify);
  }
  notify_server ( $queue["Server"], "unpausequeue", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "Queue" => $parameters["id"], "Extension" => $parameters["extension"]);
  if ( $parameters["agent"])
  {
    $audit["Agent"] = $parameters["agent"];
  }
  if ( framework_has_hook ( "queues_unpause_audit"))
  {
    $audit = framework_call ( "queues_unpause_audit", $parameters, false, $audit);
  }
  audit ( "queue", "unpause", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to logoff a member from a queue
 */
framework_add_hook ( "queues_logout", "queues_logout");
framework_add_permission ( "queues_logout", __ ( "Logout member from a queue"));
framework_add_api_call ( "/queues/:id/logout", "Create", "queues_logout", array ( "permissions" => array ( "user", "queues_logout")));

/**
 * Function to logoff a member from a queue.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_logout ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $data = array ();
  $data["result"] = true;
  $parameters["id"] = (int) $parameters["id"];
  $parameters["extension"] = (int) $parameters["extension"];
  $parameters["agent"] = (int) $parameters["agent"];

  /**
   * Check if queue exists
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Queues`.*, `Ranges`.`Server` FROM `Queues` LEFT JOIN `Ranges` ON `Queues`.`Range` = `Ranges`.`ID` WHERE `Queues`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    $data["result"] = false;
    $data["queue"] = __ ( "The provided queue doesn't exist.");
  }
  $queue = $result->fetch_assoc ();

  /**
   * Check if extension exists
   */
  $extension = filters_call ( "get_extensions", array ( "number" => $parameters["extension"]));
  if ( sizeof ( $extension) != 1)
  {
    $data["result"] = false;
    $data["extension"] = __ ( "The provided extension doesn't exist.");
  }

  /**
   * Check for agent if provided
   */
  if ( $parameters["agent"])
  {
    $agent = filters_call ( "get_agents", array ( "code" => $parameters["agent"]));
    if ( sizeof ( $agent) != 1)
    {
      $data["result"] = false;
      $data["agent"] = __ ( "The provided agent doesn't exist.");
    } else {
      if ( $agent[0]["Password"] != $parameters["password"])
      {
        $data["result"] = false;
        $data["agent"] = __ ( "The provided agent password didn't match.");
      }
    }
  } else {
    /**
     * Check if password match (if not using agent)
     */
    if ( ! array_key_exists ( "extension", $data) && $extension[0]["Record"]["Password"] != $parameters["password"])
    {
      $data["result"] = false;
      $data["extension"] = __ ( "The extension password didn't match.");
    }
  }

  /**
   * Call logout sanitize hook, if exist
   */
  if ( framework_has_hook ( "queues_logout_sanitize"))
  {
    $data = framework_call ( "queues_logout_sanitize", $parameters, false, $data);
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
   * Call logout post hook, if exist
   */
  if ( framework_has_hook ( "queues_logout_post"))
  {
    framework_call ( "queues_logout_post", $parameters);
  }

  /**
   * Notify server about change
   */
  $notify = array ( "Queue" => $queue["Extension"], "Extension" => $parameters["extension"]);
  if ( $parameters["agent"])
  {
    $notify["Agent"] = $parameters["agent"];
  }
  if ( framework_has_hook ( "queues_logout_notify"))
  {
    $notify = framework_call ( "queues_logout_notify", $parameters, false, $notify);
  }
  notify_server ( $queue["Server"], "logoutqueue", $notify);

  /**
   * Insert audit registry
   */
  $audit = array ( "Queue" => $parameters["id"], "Extension" => $parameters["extension"]);
  if ( $parameters["agent"])
  {
    $audit["Agent"] = $parameters["agent"];
  }
  if ( framework_has_hook ( "queues_logout_audit"))
  {
    $audit = framework_call ( "queues_logout_audit", $parameters, false, $audit);
  }
  audit ( "queue", "logout", $audit);

  /**
   * Retorn OK to user
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "result" => true));
}

/**
 * API call to intercept new server and server reinstall
 */
framework_add_hook ( "servers_add_post", "queues_server_reconfig");
framework_add_hook ( "servers_reinstall_config", "queues_server_reconfig");

/**
 * Function to notify server to include all queues.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function queues_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Fetch all queues and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Queues`.`Description`, `Queues`.`Extension` FROM `Queues` LEFT JOIN `Ranges` ON `Queues`.`Range` = `Ranges`.`ID` WHERE `Ranges`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["id"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $queue = $result->fetch_assoc ())
  {
    $notify = array ( "Description" => $queue["Description"], "Extension" => $queue["Extension"]);
    if ( framework_has_hook ( "queues_add_notify"))
    {
      $notify = framework_call ( "queues_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["id"], "createqueue", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
